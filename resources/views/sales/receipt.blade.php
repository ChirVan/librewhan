@extends('layouts.app')

@section('title', "Receipt - {$order->order_number}")

@section('content')
<div class="container">
  <div class="card p-3">
    <div class="d-flex justify-content-between">
      <div>
        <h5>Librewhan Cafe</h5>
        <small>Receipt: <strong>{{ $order->order_number }}</strong></small><br>
        <small>{{ $order->created_at->format('Y-m-d H:i') }}</small>
      </div>
      <div>
        <strong>Payment: </strong>{{ $order->payment_mode }}<br>
        <strong>Status: </strong>{{ $order->status }}
      </div>
    </div>

    <hr>

    <table class="table table-sm">
      <thead><tr><th>Item</th><th>Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
      <tbody>
        @foreach($order->items as $it)
          <tr>
            <td>{{ $it->name }}</td>
            <td>{{ $it->qty }}</td>
            <td class="text-end">{{ number_format($it->price,2) }}</td>
            <td class="text-end">{{ number_format($it->price * $it->qty,2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end">{{ number_format($order->subtotal,2) }}</td></tr>
        <tr><td colspan="3" class="text-end">Total</td><td class="text-end">{{ number_format($order->total,2) }}</td></tr>
        <tr><td colspan="3" class="text-end">Paid</td><td class="text-end">{{ number_format($order->amount_paid,2) }}</td></tr>
        <tr><td colspan="3" class="text-end">Change</td><td class="text-end">{{ number_format($order->change_due,2) }}</td></tr>
      </tfoot>
    </table>

    <div class="text-center mt-4">
      <small>Thank you for your purchase!</small>
    </div>

    <div class="mt-3">
      <a href="#" class="btn btn-primary" onclick="window.print(); return false;">Print</a>
    </div>
  </div>
</div>
@endsection
