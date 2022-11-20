@extends('admin.template')
@section('image-active','text-white bg-primary')
@section('title','Foto')
@section('contain')
<div class="container">
  @include('admin/component/space_contain')
  <div class="card mt-2">
  <form class="d-flex p-3" enctype="multipart/form-data" action="/search-image" method="post">
            @csrf
            <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" search="/searching-image">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
  
  <div class="card my-5">
    <h1 class="d-flex">Daftar Banner</h1>
    @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
    @endif
    <div class="d-flex flex-row-reverse bd-highlight container">
      <a href="/create-image" class="btn btn-success">Tambah Banner</a>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Banner</th>
            <th scope="col">Nama</th>
            <th scope="col">Link</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($images as $image)
          <tr>
            <th scope="row">{{$image->id}}</th>
            <td>
              <img height="50" width="50" src="https://kel-ketintang.id/storage/public/banner/{{$image->link}}" />
            </td>
            <td>{{$image->name}}</td>
            <td><a href="https://kel-ketintang.id/storage/public/banner/{{$image->link}}">Link Ada Disini</a></td>
            <td>
              <a class="btn btn-danger delete-confirm" href="/delete-image/{{$image->id}}">Delete</a>
              <a class="btn btn-warning" href="/edit-image/{{$image->id}}">Edit</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="kumpulan-button d-flex flex-row-reverse bd-highlight mx-1 mb-1">
      {{ $images->links() }}
    </div>
  </div>
</div>
@endsection