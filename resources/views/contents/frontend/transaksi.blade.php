@extends('layouts.frontend')

@section('content')
<main id="content" role="main" class="cart-page">
    <div class="container">
        <div class="mb-4 mt-4">
            <h1 class="text-center">My Transaction</h1>
        </div>

        {{-- card for transaction user --}}
        <div class="row">
            <div class="col-12">
                @forelse ($transactions as $transaction)
                    <div class="card my-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Transaction</h5>
                                    <p class="card-text">
                                        <strong>Transaction ID</strong> : {{ $transaction->id }} <br>
                                        <strong>Transaction Date</strong> : {{ $transaction->created_at }} <br>
                                        <strong>Total Price</strong> : Rp.
                                        {{ number_format($transaction->total, 0, ',', '.') }}
                                        <br>
                                        <strong>Status</strong> : <span
                                            class="font-weight-bold">{{ $transaction->status }}</span> <br>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title">Shipping Address</h5>
                                    <p class="card-text">
                                        <strong>Address</strong> : {{ $transaction->address }} <br>
                                        <strong>City</strong> : {{ $transaction->regency }} <br>
                                        <strong>Province</strong> : {{ $transaction->province }} <br>
                                        <br>
                                    </p>
                                </div>
                            </div>
                            {{-- button for detail transaction --}}
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('transaksi.detail', $transaction->id) }}" class="btn btn-primary">View
                                Transaction</a>
                        </div>
                    </div>
                @empty
                    <div class="card my-3">
                        <div class="card-body">
                            <p class="card-text">
                                You don't have any transaction yet.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
            {{ $transactions->links() }}
        </div>
    </div>
</main>
@endsection