<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Find or create the cart for a customer or guest.
     */
    public function getOrCreateCart(
        ?User $user,
        ?string $sessionId = null
     ): Cart {
        if ($user !== null) {
            return Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => null]
            );
        }

        if (!$sessionId) {
            throw new \InvalidArgumentException(
                'A session ID is required for a guest cart.'
            );
        }

        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => null]
        );
    }

    /**
     * Add a product or increase its existing quantity.
     */
    public function addItem(
        Cart $cart,
        int $productId,
        int $quantity = 1
     ): CartItem {
        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.',
            ]);
        }

        return DB::transaction(function () use (
            $cart,
            $productId,
            $quantity
        ) {
            $product = Product::query()
                ->whereKey($productId)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                throw ValidationException::withMessages([
                    'product_id' => 'This product is unavailable.',
                ]);
            }

            $item = CartItem::query()
                ->where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            $newQuantity = ($item?->quantity ?? 0) + $quantity;

            if ($newQuantity > $product->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Requested quantity exceeds available stock.',
                ]);
            }

            if ($item) {
                $item->update(['quantity' => $newQuantity]);

                return $item->refresh();
            }

            return CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        });
    }

    /**
     * Change the quantity of an existing cart item.
     */
    public function updateQuantity(
        Cart $cart,
        int $itemId,
        int $quantity
     ): CartItem {
        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.',
            ]);
        }

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($itemId)
            ->with(['product' => fn($query)=> $query->withTrashed(),])
            ->firstOrFail();

        $product = $item->product;

        if (!$product || $product->trashed()) {
                throw ValidationException::withMessages([
                'quantity' => 'This product is no longer available.',
            ]);
        }

        if ($quantity > $item->product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Requested quantity exceeds available stock.',
            ]);
        }

        $item->update(['quantity' => $quantity]);

        return $item->refresh();
    }

    /**
     * Remove one item from the cart.
     */
    public function removeItem(Cart $cart, int $itemId): void
    {
        CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($itemId)
            ->delete();
    }

    /**
     * Empty the cart while keeping the cart record.
     */
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    /**
     * Calculate totals using current catalog prices.
     */
    public function getSummary(Cart $cart): array
    {
        $items = $cart->items()
            ->with([
            'product' => fn ($query) => $query->withTrashed(),
            ])
            ->get();

        $lines = $items->map(function (CartItem $item) {

             $product = $item->product;

              $available = $product !== null
              && !$product->trashed();


             if (!$available) {
                return [
                    'cart_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'title' => $product?->title ?? 'Unavailable product',
                    'quantity' => $item->quantity,
                    'unit_price' => null,
                    'subtotal' => null,
                    'available' => false,
                ];
            }

            $price = (float) $item->product->base_price;


            return [
                'cart_item_id' => $item->id,
                'product_id' => $item->product_id,
                'title' => $item->product->title,
                'quantity' => $item->quantity,
                'unit_price' => round($price, 2),
                'subtotal' => round($price * $item->quantity, 2),
                'available' => true,
            ];
        });

        $availableLines = $lines->filter(
            fn (array $line) => $line['available']
        );

        return [
            'items' => $lines,
            'item_count' => $items->sum('quantity'),
            'grand_total' => round($lines->sum('subtotal'), 2),
             'unavailable_count' => $lines->where('available', false)->count(),
        ];
    }

    

//   Merge a guest cart into the authenticated customer's cart.
 
    public function mergeGuestCart(string $guestSessionId, User $user): void
    {
        DB::transaction(function () use ($guestSessionId, $user) {
            $guestCart = Cart::where('session_id', $guestSessionId)
                ->whereNull('user_id')
                ->with('items')
                ->lockForUpdate()
                ->first();

            if (!$guestCart) {
                return;
            }

            $customerCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => null]
            );

            $customerCart = Cart::whereKey($customerCart->id)
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($guestCart->items as $guestItem) {
                $customerItem = CartItem::where('cart_id', $customerCart->id)
                    ->where('product_id', $guestItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($customerItem) {
                    $customerItem->quantity += $guestItem->quantity;
                    $customerItem->save();
                } else {
                    CartItem::create([
                        'cart_id'           => $customerCart->id,
                        'product_id'        => $guestItem->product_id,
                        'quantity'          => $guestItem->quantity,
                        'unavailable_count' => $lines->where('available', false)->count(),
                    ]);
                }
            }

            // Delete the temporary cart and its items after a successful merge.
            $guestCart->delete();
        });
    }


}
