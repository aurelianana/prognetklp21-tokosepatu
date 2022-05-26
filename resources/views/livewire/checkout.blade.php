<?php

  function rupiah ($angka) {
    $hasil = 'Rp. ' . number_format($angka, 0, ",", ".");
    return $hasil;
  }
	$temp_shipping=0;
?>

<form method="post" action="{{ route('checkout.confirm') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="row">
                <div class="form-group ">
                    <label for="">Negara</label>
                    <select class="custom-select" name="negara" id="kategori">
                            <option value="">Indonesia</option>
                    </select>
                    @error('negara')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-6">
                    <label for="">Province</label>
                    <select class="custom-select" name="province" wire:model="input_provinsi" id="kategori"
                        >
                    @foreach($provinces as $dd)
                            <option value="{{$dd->title}}">{{$dd->title}}</option>
                    @endforeach
                    </select>
                    @error('province')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-6">
                    <label for="">City</label>
                    <select class="custom-select" name="regency" id="kategori" wire:model="input_region"
                        >
                    @if(!is_null($regions))
                    @foreach($regions as $dd)
                            <option value="{{$dd->title}}">{{ $dd->title }}</option>
                    @endforeach
                    @endif
                    </select>
                    @error('regency')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-12">
                    <label for="">Kurir</label>
                    <select class="custom-select" name="courier_id" id="kategori" wire:model="input_kurir"
                        >
                    @foreach($kurir as $dd)
                            <option value="{{$dd->id}}">{{$dd->courier}}</option>
                    @endforeach
                    </select>
                    @error('courier_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="">Shipping Cost</label>
                    <input type="number" class="form-control @error('shipping_cost') is-invalid @enderror" 
                        value="{{$cost_value}}" name="shipping_cost" id="shipping_cost" placeholder="" hidden>
                    <input type="text" class="form-control"
                        value="{{rupiah($cost_value)}}" placeholder="">
                    <?php $temp_ongkir=$cost_value ?>
                    @error('shipping_cost')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="">Alamat</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" rows="5"  name="address"
                        id="alamat" placeholder="">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                </div>
            </div>

            <div class="col-6">
                <div class="checkout-cart-total">
                    <h2 class="checkout-title">YOUR ORDER</h2>
                    <h4>Product <span>Total</span></h4>
                    <ul style="list-style:none;">
                        @foreach($cart as $dd)
                        <li><span class="left">{{$dd->product->product_name}} X {{$dd->qty}} </span> <span
                                class="right">{{$dd->product->price* $dd->qty}} </span></li>
                        @endforeach
                    </ul>
                    <p>Sub Total <span>{{rupiah($subtotal)}}</span></p>
                    <p>Shipping Fee <span>{{rupiah($temp_ongkir)}}</span></p>
                    <h4>Grand Total <span>{{rupiah($subtotal+$cost_value)}}</span></h4>
                    <div class="method-notice mt--25">
                        <article>
                            <h3 class="d-none sr-only">blog-article</h3>
                            Sorry, it seems that there are no available payment methods for
                            your state. Please contact us if you
                            require
                            assistance
                            or wish to make alternate arrangements.
                        </article>
                    </div>
                    <div class="term-block">
                        <input type="checkbox" id="accept_terms2">
                        <label for="accept_terms2">I’ve read and accept the terms &
                            conditions</label>
                    </div>
                    <button type="submit" class="place-order w-100">Place order</button>
                </div>
            </div>
        </div>

        </div>
        <!-- <div class="mod''''''''''''''''''al-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div> -->
        
</form>
    </div>
</div>

