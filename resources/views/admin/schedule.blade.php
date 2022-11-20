@extends('admin.template')
@section('schedule-active','text-white bg-primary')
@section('title','Agenda')
@section('contain')

<div class="container">
@include('admin/component/space_contain')
    <div class="card mt-2">
        <form class="d-flex p-3" enctype="multipart/form-data" action="/search-schedule" method="post">
            @csrf
            <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" search="/searching-schedule">
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
            <a href="/create-schedule" class="btn btn-success">Tambah Agenda</a>
        </div>
        <div class="table-responsive">
            <table class="table bg-white table-responsive">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Agenda</th>
                        <!-- <th scope="col">Nama</th> -->
                        <th scope="col">Judul Agenda</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                    <tr>
                        <th scope="row">{{$schedule->id}}</th>
                        <td>
                            <img height="50" width="50" class="img-responsive" src="https://kel-ketintang.id/storage/public/schedule/{{$schedule->name}}" />
                        </td>
                        <!-- <td>{{$schedule->name}}</td> -->
                        <td>{{$schedule->title}}</td>
                        <td>{{$schedule->date}}</td>
                        <td>
                            <a class="btn btn-danger delete-confirm" href="/delete-schedule/{{$schedule->id}}">Delete</a>
                            <a class="btn btn-warning" href="/edit-schedule/{{$schedule->id}}">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="kumpulan-button d-flex flex-row-reverse bd-highlight mx-1 mb-1">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection