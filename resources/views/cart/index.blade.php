 @extends('layouts.app')

 @section('content')
 <div class="container py-4">
     <h2 class="mb-4">Shopping Cart</h2>

     <!-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

     @if ($summary['items']->isEmpty())
     <div class="alert alert-info">
         Your cart is empty.
     </div>

     <a href="{{ route('catalog.index') }}" class="btn btn-primary">
         Browse Products
     </a>
     @else

     @if ($summary['unavailable_count'] > 0)
     <div class="alert alert-warning">
         Some products are no longer available. They are excluded
         from the grand total and cannot be purchased.
         Please remove them from your cart.
     </div>
     @endif
     <div class="table-responsive">
         <table class="table table-bordered align-middle">
             <thead>
                 <tr>
                     <th>Product</th>
                     <th>Unit Price</th>
                     <th>Quantity</th>
                     <th>Subtotal</th>
                     <th>Actions</th>
                 </tr>
             </thead>

             <tbody>
                 @foreach ($summary['items'] as $item)
                 <tr>
                     <td>{{ $item['title'] }}</td>

                     <td>
                         ₹{{ number_format($item['unit_price'], 2) }}
                     </td>

                     <td>
                         <form action="{{ route('cart.items.update', $item['cart_item_id']) }}" method="POST"
                             class="d-flex gap-2">
                             @csrf
                             @method('PATCH')

                             <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                 class="form-control" style="width: 90px" required>

                             <button class="btn btn-sm btn-primary">
                                 Update
                             </button>
                         </form>
                     </td>

                     <td>
                         ₹{{ number_format($item['subtotal'], 2) }}
                     </td>

                     <td>
                         <form action="{{ route('cart.items.destroy', $item['cart_item_id']) }}" method="POST"
                             onsubmit="return confirm('Remove this product from your cart?')">
                             @csrf
                             @method('DELETE')

                             <button class="btn btn-sm btn-danger">
                                 Remove
                             </button>
                         </form>
                     </td>
                 </tr>
                 @endforeach
             </tbody>

             <tfoot>
                 <tr>
                     <th colspan="3" class="text-end">
                         Grand Total
                     </th>
                     <th colspan="2">
                         ₹{{ number_format($summary['grand_total'], 2) }}
                     </th>
                 </tr>
             </tfoot>
         </table>
     </div>

     <div class="d-flex flex-wrap gap-2">
         <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">
             Continue Shopping
         </a>

         <a href="{{ route('cart.summary') }}" class="btn btn-success">
             View Checkout Summary
         </a>

         <form action="{{ route('cart.clear') }}" method="POST"
             onsubmit="return confirm('Clear all items from your cart?')">
             @csrf
             @method('DELETE')

             <button class="btn btn-outline-danger">
                 Clear Cart
             </button>
         </form>
     </div>
     @endif
 </div>
 @endsection