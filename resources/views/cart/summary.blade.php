@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Checkout Summary</h2>

    @if ($summary['items']->isEmpty())
    <div class="alert alert-info">
        Your cart is empty. Add products before viewing the summary.
    </div>

    <a href="{{ route('catalog.index') }}" class="btn btn-primary">
        Browse Products
    </a>
    @else
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Order Estimate</h5>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($summary['items'] as $item)
                        <tr>
                            <td>{{ $item['title'] }}</td>
                            <td>
                                @if ($item['available'])

                                ₹{{ number_format($item['unit_price'], 2) }}
                                @else
                                <span class="text-danger">Unavailable</span>

                                @endif
                            </td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>
                                @if ($item['available'])

                                ₹{{ number_format($item['subtotal'], 2) }}
                                @else
                                <span class="text-danger">Not included</span>
                                
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <span>Total items</span>
                <strong>{{ $summary['item_count'] }}</strong>
            </div>

            <div class="d-flex justify-content-between mt-2">
                <span>Grand Total</span>
                <strong>
                    ₹{{ number_format($summary['grand_total'], 2) }}
                </strong>
            </div>

            <p class="text-muted mt-3 mb-0">
                This is a price summary only. No order or payment
                has been placed.
            </p>
        </div>
    </div>

    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary mt-3">
        Back to Cart
    </a>
    @endif
</div>
@endsection