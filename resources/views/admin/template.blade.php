<?php

use Jenssegers\Agent\Agent;

$agent = new Agent();

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- sweetalert allert -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- Jquery -->
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <!-- ajax -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

  <title>@yield('title')</title>
  <style>

  </style>
</head>

<body class="">
  @if($agent->isMobile())
  <!-- start nav -->
  <div class="fixed-top">
    <div class="container mb-5 bg-body rounded py-3">
      <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid ">
          <a class="navbar-brand text-center justify-content-center" href="/">
            <img src="https://i.ibb.co/HGPWSYv/logoktt.png" height="70%" width="70%" />
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              @include('admin/component/list')
            </ul>
            <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Cari" aria-label="Search">
              <button class="btn btn-outline-success btn-danger text-white" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div class="bg-primary">
    @yield('contain')
  </div>
  @else
  <div class="row">
    <div class="col-md-2 bg-white">
      <div class="sidebar">
        <div class="icon container my-3">
          <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/City_of_Surabaya_Logo.svg/1200px-City_of_Surabaya_Logo.svg.png" class="bi me-2" width="60" height="60" />
            <span class="fs-4">Kelurahan Ketintang</span>
          </a>
          <hr class="my-4">
          <ul class="nav nav-pills flex-column mb-auto">
        </div>
        <!--Menu item-->
        <div class="container">
          <ul class="list-unstyled link-dark">
            @include('admin/component/list')
          </ul>
        </div>
      </div>
      <div class="my-5">
        <div class="my-5">
        </div>
      </div>
    </div>
    <div class="col-md-10 bg-primary">
      <div class="container">
        @yield('contain')
      </div>
    </div>
  </div>
  @endif



  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->

  <script type="text/javascript">
    $('.create-confirm').click(function(event) {
      var form = $(this).closest("form");
      var name = $(this).data("name");
      event.preventDefault();
      swal({
          title: `Apakah anda yakin mengirim form ini?`,
          text: "Form akan otomatis mengirim data yang telah diisi",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            form.submit();
            // swal("Form Sukses Dikirim", {
            //   icon: "success",
            // });
          }
        });
    });

    $('.delete-confirm').on('click', function(event) {
      event.preventDefault();
      const url = $(this).attr('href');
      swal({
        title: "Yakin ingin menghapus?",
        text: "Setelah dihapus, data tidak bisa dipulihkan",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then(function(value) {
        if (value) {
          window.location.href = url;
          swal("Data sukses dihapus,tunggu loading selesai", {
            icon: "success",
          });
        }
      });
    });
  </script>


  <script type="text/javascript">
    $('#search').on('keyup', function() {

      $value = $(this).val();
      $urll = $(this).attr('search');

      $.ajax({

        type: 'get',

        url: $urll,

        data: {
          'search': $value
        },

        success: function(data) {

          $('tbody').html(data);

        }

      });



    })
  </script>

  <script type="text/javascript">
    $.ajaxSetup({
      headers: {
        'csrftoken': '{{ csrf_token() }}'
      }
    });
  </script>


</body>

</html>