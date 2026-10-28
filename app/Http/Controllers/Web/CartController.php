<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
        public function __construct(
        private CartService $cartService
    ) {
    }

    // Display the current cart.

     public function index(Request $request): View
    {
        $cart = $this->currentCart($request);
        $cart->load('items.product.category');

        $summary = $this->cartService->getSummary($cart);

        return view('cart.index', compact('cart', 'summary'));
    }

    // Add a product to the cart.
    public function addItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        $cart = $this->currentCart($request);

        $this->cartService->addItem(
            $cart,
            (int) $validated['product_id'],
            (int) ($validated['quantity'] ?? 1)
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to your cart.');
    }

    // Update the quantity of a cart item.

    public function updateItem(
        Request $request,
        int $item
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->currentCart($request);

        $this->cartService->updateQuantity(
            $cart,
            $item,
            (int) $validated['quantity']
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart quantity updated.');
    }

    // Remove one item from the cart.

    public function removeItem(
        Request $request,
        int $item
    ): RedirectResponse {
        $cart = $this->currentCart($request);

        $this->cartService->removeItem($cart, $item);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product removed from your cart.');
    }

    // Remove all items without deleting the cart itself

    public function clear(Request $request): RedirectResponse
    {
        $cart = $this->currentCart($request);

        $this->cartService->clearCart($cart);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Your cart is now empty.');
    }

    
    //   Display the checkout summary (no order or payment).
     
    public function summary(Request $request): View
    {
        $cart = $this->currentCart($request);
        $summary = $this->cartService->getSummary($cart);

        return view('cart.summary', compact('cart', 'summary'));
    }

     
    //   Resolve the cart belonging to this user or guest session.
     
    private function currentCart(Request $request): Cart
    {
        return $this->cartService->getOrCreateCart(
            $request->user(),
            $request->user() ? null : $request->session()->getId()
        );
    }


}
