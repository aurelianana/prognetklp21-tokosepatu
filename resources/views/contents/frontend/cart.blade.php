@extends('layouts.frontend')
@section('content')

<div class="container mt-5" style="margin-bottom:100px">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Produk Name</th>
      <th scope="col">Harga</th>
      <th scope="col">Qty</th>
      <th scope="col">Aksi</th>
    </tr>
  </thead>
  <tbody>
      @foreach($carts as $cart)
    <tr>
      <th scope="row">{{$loop->index+1}}</th>
      <td>{{$cart->product->product_name}}</td>
      <td>{{$cart->product->price}}</td>
      <td>{{$cart->qty}}</td>
      <td>
        <a href="javascript:void(0)"
                class="btn btn-success btn-sm ubah" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <a href="" class="btn btn-danger btn-sm"
                onclick="javascript:return confirm(`Data ingin dihapus ?`);" title="Delete">
                <i class="fa fa-times"></i>
            </a>
        </td>
    </tr>
    @endforeach

  </tbody>
</table>
<a href="{{route('checkout')}}" class="btn btn--box btn--large btn--radius btn--green btn--green-hover-black btn--uppercase font--bold m-t-20 m-r-20"><button type="button" class="btn btn-success">Checkout</button></a>
</div>
@endsection
@section('javascript')
@endsection