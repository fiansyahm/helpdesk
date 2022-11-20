@extends('admin.template')
@section('video-active','text-white bg-primary')
@section('title','Buat Agenda')
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
        <h1 class="d-flex mx-5 my-3">Tambah Video</h1>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <form enctype="multipart/form-data" class="mx-5 mb-5" class="ps-checkout__form" action="/add-video" method="post">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Judul Video</label>
                <input type="title" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title Video">
                @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama File</label>
                <input type="name" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama File">
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Input Poster</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Tanggal:</label>
                <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date">
                @error('date')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Input Video</label>
                <input class="form-control @error('video') is-invalid @enderror" type="file" id="video" name="video">
                @error('video')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary create-confirm">Submit</button>
        </form>
    </div>
</div>
@endsection