@extends('template')
@section('main')
<h1>halo semua</h1>
<!-- <img class="img-fluid" src="/my-image" alt="" id="my-image"> -->
<img class="img-fluid" src="/my-imagekekek" alt="" id="my-image">
<script>
    document.getElementById('my-image').onerror = function() {
        this.onerror = null;
        this.src = 'https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif?20170503175831';
    };
</script>

@endsection