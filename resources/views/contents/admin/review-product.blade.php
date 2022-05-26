@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Button trigger modal -->
        {{-- {{ alertbs_form($errors) }} --}}

        <div class="card card-rounded mt-2">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title pt-2"> <i class="fas fa-database me-1"></i> Review Produk</h5>
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
                                <th>Rate</th>
                                <th>Content</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no =1;@endphp
                            @forelse($review as $r)
                                <tr>
                                    <td>{{ $no }}</td>

                                    <td>{{ $r->user->name }}</td>
                                    <td>{{ $r->rate }} ⭐</td>
                                    <td>{{ $r->content }}</td>
                                    <td>
                                        {{-- buttom for trigger modal --}}
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#modal-review-{{ $r->id }}" class="btn btn-success"><i
                                                class="fa fa-comments"></i></button>
                                    </td>
                                </tr>
                                @php $no++;@endphp
                            @empty
                                <tr>
                                    <td colspan="7"> Tidak Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @foreach ($review as $r)
        {{-- modal for response on review --}}
        <div class="modal fade" id="modal-review-{{ $r->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Response
                            Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.review.store', $r->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="3">{{ $r->response->content ?? '' }}</textarea>
                                </textarea>
                            </div>
                            @if (!$r->response()->exists())
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit
                                        Response</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
