<?php
use Jenssegers\Agent\Agent;
        $agent=new Agent();
?>
@extends('template')
@section('title','login')
@section('content')
<div class="container">
        <div class="m-5 p-5">
        </div>
        <h1 class="text-danger">Admin</h1>
        <hr class="text-danger row col-md-4 bg-primary" size="10px">
        <?php
            $tanggal= mktime(date("m"),date("d"),date("Y"));
            echo "Tanggal : <b>".date("d-M-Y", $tanggal)."</b> ";
            date_default_timezone_set('Asia/Jakarta');
            $jam=date("H:i:s");
            echo "| Pukul : <b>". $jam." "."</b>";
            $a = date ("H");
        ?> 
{{ $slot }}
</div>
@endsection