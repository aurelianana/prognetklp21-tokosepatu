<?php

function rupiah($angka)
{
    $hasil = 'Rp. ' . number_format($angka, 0, ',', '.');
    return $hasil;
}
$temp_shipping = 0;
?>
@extends('layouts.frontend')
@section('content')

    <div class="container mt-5" style="margin-bottom:100px">
        <div class="row">
            <div class="row">
                <div class="col-6">
                    @if ($transaksi->proof_of_payment == null)
                        <div class="section-content">
                            <h5 class="section-content__title">Time Countdown</h5>
                            <input type="text" id="temp_timeout" value="{{ $transaksi->timeout }}" hidden>
                        </div>
                        <div class="card-countdown-time m-t-40 m-b-30"
                            style=" display:flex; align-items:center; justify-content:center;width:100%; margin-top:20px;">
                            <div class="wrapper" style="user-select:none; width:100%;">
                                <div class="time"
                                    style="width:100%; display:flex; align-items:center; justify-content:center; border:1px solid #E2E2E2; padding: 20px; height:200px; border-radius:6px; box-shadow:10px 10px 20px rgba(0,0,0,0.09);">
                                    <span class="hour"
                                        style="width:100px;text-align:center;font-size:50px; font-weight:500;">00</span>
                                    <span class="colon"
                                        style="width:100px;text-align:center; font-size:50px; font-weight:500;">:</span>
                                    <span class="minute"
                                        style="width:100px;text-align:center;font-size:50px; font-weight:500;">00</span>
                                    <span class="colon"
                                        style="width:100px;text-align:center; font-size:50px; font-weight:500;">:</span>
                                    <span class="second"
                                        style="width:100px;text-align:center; font-size:50px; font-weight:500;">00</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="section-content">
                            <h5 class="section-content__title">Time Countdown</h5>
                            <input type="text" id="temp_timeout" value="{{ $transaksi->timeout }}" hidden>
                        </div>
                        <div class="card-countdown-time m-t-40 m-b-30"
                            style=" display:flex; align-items:center; justify-content:center;width:100%; margin-top:20px;">
                            <div class="wrapper" style="user-select:none; width:100%;">
                                <img src="../assets/images/{{ $transaksi->proof_of_payment }}"
                                    style="width:100%; height:300px" alt="">
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-6">
                    <div class="checkout-cart-total">
                        <h2 class="checkout-title">YOUR ORDER</h2>
                        <h4>Product <span>Total</span></h4>
                        <ul style="list-style:none;">
                            @foreach ($transaksi->transaction_details as $dd)
                                <li><span class="left">{{ $dd->product->product_name }} X {{ $dd->qty }}
                                    </span> <span class="right">{{ $dd->product->price * $dd->qty }} </span></li>
                            @endforeach
                        </ul>
                        <p>Sub Total <span>{{ rupiah($transaksi->sub_total) }}</span></p>
                        <p>Shipping Fee <span>{{ rupiah($transaksi->shipping_cost) }}</span></p>
                        <h4>Grand Total <span>{{ rupiah($transaksi->total) }}</span></h4>
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

                        @if ($transaksi->proof_of_payment == null && $transaksi->status != 'canceled')
                            <form action="{{ route('upload.pembayaran', $transaksi->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <input type="file" name="gambar" id="gambar">
                                </div>
                                @error('gambar')
                                    <div><span class="text-danger">*</span> Mohon upload bukti pembayaran</div>
                                @enderror
                                <div class="modal-footer">
                                    <button type="submit" class="place-order w-100">Upload Bukti Pembayaran</button>

                                </div>
                            </form>
                            @if ($transaksi->status == 'unverified')
                                <form action="{{ route('transaksi.cancel', $transaksi->id) }}" id="cancel-order"
                                    method="post" class="modal-footer">
                                    @csrf
                                    <button type="button" onclick="document.getElementById('cancel-order').submit()"
                                        class="place-order w-100 text-danger">Batalkan
                                        Pesanan</button>
                                </form>
                            @endif
                        @elseif( $transaksi->status == 'canceled')
                            <button type="submit" class="place-order w-100">Anda telah membatalkan pesanan ini</button>
                        @else
                        <button type="submit" class="place-order w-100">Anda telah melakukan upload bukti
                            pembayaran</button>
                        @endif


                        <div>
                            {{-- make transaction status heading if else --}}
                            @if ($transaksi->status == 'unverified')
                                <h5>Status Pembayaran : <span class="badge badge-warning">Unverified</span></h5>
                            @elseif ($transaksi->status == 'success')
                                <h5>Status Pembayaran : <span class="badge badge-success">Success</span></h5>
                            @elseif ($transaksi->status == 'verified')
                                <h5>Status Pembayaran : <span class="badge badge-success">Verified</span></h5>
                            @elseif ($transaksi->status == 'canceled')
                                <h5>Status Pembayaran : <span class="badge badge-danger">Canceled</span></h5>
                            @elseif ($transaksi->status == 'expired')
                                <h5>Status Pembayaran : <span class="badge badge-danger">Expired</span></h5>
                            @elseif ($transaksi->status == 'delivered')
                                <h5>Status Pembayaran : <span class="badge badge-danger">Delivered</span></h5>
                            @endif
                        </div>



                        @if ($transaksi->status == 'delivered')
                            <form action="{{ route('transaksi.success', $transaksi->id) }}" method="post"
                                class="mt-5">
                                @csrf
                                <button type="submit" class="place-order w-100 btn btn-primary">Saya sudah menerima
                                    transaksi ini</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <hr style="margin-top:50px">
            @if ($transaksi->is_review == 0)
                <div class="row" style="margin-top:50px;">
                    <div class="section-content">
                        <h2 class="section-content__title text-center">Reviews</h2>
                    </div>
                    <form action="{{ route('upload.review.user', $transaksi->id) }}" method="post">
                        @csrf
                        <div class="card-countdown-time m-t-40 m-b-30"
                            style=" display:flex; align-items:center; justify-content:center;width:100%; margin-top:20px;">
                            <div class="wrapper" style="user-select:none; width:100%;">
                                @foreach ($transaksi->transaction_details as $dd)
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row"
                                                style="display: flex; align-items:center;justify-content:center;">
                                                <div class="col-4">
                                                    <img src="../assets/images/{{ $transaksi->proof_of_payment }}"
                                                        style="width:100%; height:100px; border-radius:10%" alt="">
                                                </div>
                                                <div class="col-8">
                                                    <p style="font-size: 18px; font-weight: 500;">
                                                        {{ $dd->product->product_name }}</p>
                                                    <p style="font-size: 16px; font-weight: normal;">
                                                        {{ rupiah($dd->product->price) }}</p>
                                                    <div class="star-widget">
                                                        <input type="radio" name="rate[]" value="5" id="rate-5">
                                                        <label for="rate-5" class="fas fa-star"></label>
                                                        <input type="radio" name="rate[]" value="4" id="rate-4">
                                                        <label for="rate-4" class="fas fa-star"></label>
                                                        <input type="radio" value="3" name="rate[]" id="rate-3">
                                                        <label for="rate-3" class="fas fa-star"></label>
                                                        <input type="radio" value="2" name="rate[]" id="rate-2">
                                                        <label for="rate-2" class="fas fa-star"></label>
                                                        <input type="radio" value="1" name="rate[]" id="rate-1">
                                                        <label for="rate-1" class="fas fa-star"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="product_id[]" value="{{ $dd->product->id }}" hidden>
                                            <textarea name="content[]" id="" style="width:100%" rows="5"></textarea>
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="place-order w-100" style="margin-top:50px;">Upload
                                    review</button>

                            </div>
                        </div>
                    </form>
                </div>
                <hr style="margin-top:100px;">
            @endif
        </div>
    </div>
    </div>

    <style>
        .container .post {
            /* display: none; */
        }

        .container .text {
            font-size: 25px;
            color: #666;
            font-weight: 500;
        }

        .container .edit {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 16px;
            color: #666;
            font-weight: 500;
            cursor: pointer;
        }

        .container .edit:hover {
            text-decoration: underline;
        }

        .container .star-widget input {
            display: none;
        }

        .star-widget label {
            font-size: 40px;
            color: #444;
            padding: 10px;
            float: right;
            transition: all 0.2s ease;
        }

        input:not(:checked)~label:hover,
        input:not(:checked)~label:hover~label {
            color: #fd4;
        }

        input:checked~label {
            color: #fd4;
        }

        input#rate-5:checked~label {
            color: #fe7;
            text-shadow: 0 0 20px #952;
        }

        #rate-1:checked~form header:before {
            content: "I just hate it ";
        }

        #rate-2:checked~form header:before {
            content: "I don't like it ";
        }

        #rate-3:checked~form header:before {
            content: "It is awesome ";
        }

        #rate-4:checked~form header:before {
            content: "I just like it ";
        }

        #rate-5:checked~form header:before {
            content: "I just love it ";
        }

    </style>

@endsection
@section('javascript')
    <script>
        const btn = document.querySelector("button");
        const post = document.querySelector(".post");
        const widget = document.querySelector(".star-widget");
        const editBtn = document.querySelector(".edit");
        btn.onclick = () => {
            widget.style.display = "none";
            post.style.display = "block";
            editBtn.onclick = () => {
                widget.style.display = "block";
                post.style.display = "none";
            }
            return false;
        }
    </script>

    <script type="text/javascript">
        var x = document.getElementById("temp_timeout");
        let launchDate = new Date(x.value).getTime();

        let timer = setInterval(tick, 1000);

        function tick() {
            let now = new Date().getTime();

            let t = launchDate - now;

            if (t > 0) {
                let hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                if (hours < 10) {
                    hours = "0" + hours;
                }

                let mins = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
                if (mins < 10) {
                    mins = "0" + mins;
                }

                let secs = Math.floor((t % (1000 * 60)) / 1000);
                if (secs < 10) {
                    secs = "0" + secs;
                }

                document.querySelector('.hour').innerText = hours;
                document.querySelector('.minute').innerText = mins;
                document.querySelector('.second').innerText = secs;



            }

        }
    </script>
@endsection
