@extends('template')
@section('main')
<h1>halo semua</h1>


<div class="table-responsive">
  <table class="table">
        <thead>
            <tr>
                <th class="text-center" scope="row" rowspan="{{count($rank)}}">Iterasi\Penulis</th>
                @foreach ($authors as $i)
                    <th scope="col">{{$i}}</th>
                @endforeach 
            </tr>
        </thead>
        <tbody>
        <?php
            $val=1;
        ?>
            @foreach ($rank as $users)
            <tr>
                @if ($val < count($rank) )
                    <th>{{$val++}}</th>
                @else
                    <th>Rank</th>
                @endif
                

                @foreach ($users as $user)    
                    <td>{{ $user}}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection