@extends('admin.template')
@section('photo-active','bg-primary text-white')
@section('title','Foto')
@section('contain')

<div class="container">
    @include('admin/component/space_contain')
    <div class="card mt-2">
        <form class="d-flex p-3" enctype="multipart/form-data" action="/search-photo" method="post">
            @csrf
            <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" search="/searching-photo">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
    <div class="card my-5">
        <h1 class="d-flex">Daftar Gambar</h1>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="d-flex flex-row-reverse bd-highlight container">
            <a href="/create-photo" class="btn btn-success">Tambah Gambar</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Judul Foto</th>
                        <th scope="col">Tanggal</th>
                        <!-- <th scope="col">Link</th> -->
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($photos as $photo)
                    <tr>
                        <th scope="row">{{$photo->id}}</th>
                        <td>
                            <img height="50" width="50" src="https://kel-ketintang.id/storage/public/photo/{{$photo->name}}" />
                        </td>
                        <td>{{$photo->title}}</td>
                        <td>{{$photo->date}}</td>
                        <!-- <td><a href="https://kel-ketintang.id/storage/public/photo/{{$photo->name}}">Link Ada Disini</a></td> -->
                        <td>
                            <a  class="btn btn-danger delete-confirm" href="/delete-photo/{{$photo->id}}">Delete</a>
                            <a class="btn btn-warning" href="/edit-photo/{{$photo->id}}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="kumpulan-button d-flex flex-row-reverse bd-highlight mx-1 mb-1">
            {{ $photos->links() }}
        </div>
    </div>
</div>
@endsection