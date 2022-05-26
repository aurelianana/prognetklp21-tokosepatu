@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Button trigger modal -->
        {{-- {{ alertbs_form($errors) }} --}}

        <div class="card card-rounded mt-2">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title pt-2"> <i class="fas fa-database me-1"></i> Data Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 ms-auto">
                        <form method="get" action="">
                            <div class="input-group mb-3">
                                {{-- <input type="text" value="{{ $request->get('search') }}" name="search" id="search"
                                    class="form-control" placeholder="Cari Produk" aria-describedby="helpId">
                                @if ($request->get('search'))
                                    <a href="{{ route('admin.produk') }}" class="input-group-text btn btn-success btn-md">
                                        <i class="fas fa-sync pr-2"></i>Refresh</a>
                                @endif --}}
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive mt-1">
                    <table class="table table-striped table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Shipping Cost</th>
                                <th>Courier</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no =1;@endphp
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->address }}</td>
                                    <td>{{ $transaction->regency . ', ' . $transaction->province }}</td>
                                    <td>{{ number_format($transaction->shipping_cost, 2, ',', '.') }}</td>
                                    <td class="text-uppercase">{{ $transaction->courier->courier }}</td>
                                    <td>{{ $transaction->status }}</td>
                                    <td>{{ number_format($transaction->total, 2, ',', '.') }}</td>
                                    <td class="justify-content-center">
                                        <div class="mb-2">
                                            @if ($transaction->status != 'canceled' && $transaction->status != 'success' && $transaction->status != 'delivered')
                                                <form action="{{ route('admin.transaction.cancel', $transaction->id) }}"
                                                    method="post" id="form-cancel-transaction">
                                                    @csrf
                                                    <a onclick="if(confirm('Apakah kamu yakin ingin membatalkan transaksi ini?')){return document.getElementById('form-cancel-transaction').submit()}"
                                                        class="btn btn-danger btn-xs" data-bs-placement="top"
                                                        title="Cancel Transaction">
                                                        <i class="fa fa-ban"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <div class="mb-2">
                                                <a data-bs-toggle="modal"
                                                    data-bs-target="#product--{{ $transaction->id }}"
                                                    class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="mb-2">
                                                <a data-bs-toggle="modal" data-bs-target="#proof--{{ $transaction->id }}"
                                                    class="btn btn-primary btn-xs">
                                                    <i class="fas fa-file-image"></i>
                                                </a>
                                            </div>
                                            @if ($transaction->status == 'unverified')
                                                <form action="{{ route('admin.transaction.accept', $transaction->id) }}"
                                                    method="post" id="form-acc-{{ $transaction->id }}">
                                                    @csrf
                                                    <a onclick="document.getElementById('form-acc-{{ $transaction->id }}').submit()"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Accept Payment" class="btn btn-success btn-xs">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                </form>
                                            @endif
                                            @if ($transaction->status == 'verified')
                                                <form action="{{ route('admin.transaction.shipped', $transaction->id) }}"
                                                    method="post" id="form-shipped-{{ $transaction->id }}">
                                                    @csrf
                                                    <a onclick="document.getElementById('form-shipped-{{ $transaction->id }}').submit()"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Has Shipped"
                                                        class="btn btn-warning btn-xs">
                                                        <i class="fa fa-truck"></i>
                                                    </a>
                                                </form>
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br>
                {{-- {{ $transactions->links() }} --}}
            </div>
        </div>

        <!-- Modal -->
        @foreach ($transactions as $transaction)
            {{-- modal --}}
            <div class="modal fade" id="product--{{ $transaction->id }}" tabindex="-1" aria-labelledby="productLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="productLabel">Transaction ID :
                                #{{ $transaction->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaction->products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ number_format($product->price, 2, ',', '.') }}
                                                </td>
                                                <td>{{ $product->pivot->qty }}</td>
                                                </td>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modal proof payment --}}
            <div class="modal fade" id="proof--{{ $transaction->id }}" tabindex="-1" aria-labelledby="proofLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="proofLabel">Proof Of Payment Transaction ID :
                                #{{ $transaction->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('assets/images/' . $transaction->proof_of_payment) }}"
                                class="img-fluid">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
