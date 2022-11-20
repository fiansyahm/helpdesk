@extends('admin.template')
@section('video-active','text-white bg-primary')
@section('title','Video')
@section('contain')

<div class="container">
@include('admin/component/space_contain')
    <div class="card mt-2">
        <form class="d-flex p-3" enctype="multipart/form-data" action="/search-video" method="post">
            @csrf
            <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" search="/searching-video">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
    <div class="card my-5">
        <h1 class="d-flex">Daftar Agenda</h1>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="d-flex flex-row-reverse bd-highlight container">
            <a href="/create-video" class="btn btn-success">Tambah Video</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <!-- <th scope="col">Video</th> -->
                        <th scope="col">Poster</th>
                        <th scope="col">Judul Video</th>
                        <!-- <th scope="col">Nama Poster</th> -->
                        <th scope="col">Tanggal</th>
                        <!-- <th scope="col">Link</th> -->
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($videos as $video)
                    <tr>
                        <th scope="row">{{$video->id}}</th>
                        <!-- <td>
                        <video height="50" width="50" src="https://kel-ketintang.id/storage/public/video/{{$video->name}}" />
                    </td> -->
                        <td>
                            <image height="50" width="50" src="https://kel-ketintang.id/storage/public/thumbnail_video/{{$video->poster}}" />
                        </td>
                        <td>{{$video->title}}</td>
                        <!-- <td>{{$video->poster}}</td> -->
                        <td>{{$video->date}}</td>
                        <!-- <td><a href="https://kel-ketintang.id/storage/public/video/{{$video->name}}">Link Ada Disini</a></td> -->
                        <td>
                            <a class="btn btn-danger delete-confirm" href="/delete-video/{{$video->id}}">Delete</a>
                            <a class="btn btn-warning" href="/edit-video/{{$video->id}}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="kumpulan-button d-flex flex-row-reverse bd-highlight mx-1 mb-1">
            {{ $videos->links() }}
        </div>
    </div>


</div>

@endsection