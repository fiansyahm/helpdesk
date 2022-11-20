@extends('admin.template')
@section('image-active','text-white bg-primary')
@section('title','Tambah Banner')
@section('contain')
@include('admin/component/space_contain')
<div class="container">
    <div class="card mt-2">
        <form class="d-flex p-3">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
    <div class="card mt-2">
        <h1 class="d-flex mx-5 my-3">Tambah Banner</h1>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <form enctype="multipart/form-data" class="mx-5 mb-5" class="ps-checkout__form" action="/add-image" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Banner</label>
                <input type="name" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Gambar">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- <div class="mb-3">
                <label for="link" class="form-label">Link Banner</label>
                <input type="link" class="form-control" id="link" name="link" placeholder="Link Gambar">
            </div> -->
            <div class="mb-3">
                <label for="formFile" class="form-label">Default file input example</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary create-confirm">Submit</button>
        </form>
    </div>
</div>
@endsection