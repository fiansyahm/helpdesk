<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Kontak Pengaduan</title>
</head>

<body>
    <div class="" style="background-color:blue;color:#dee2e6;padding:3rem;margin:3rem;border-color:#212529;border-width:5px;border:1px solid #dee2e6;">
        <div class="icon">
            <img style="margin-top:2rem;margin-right:.5rem!important" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/City_of_Surabaya_Logo.svg/1200px-City_of_Surabaya_Logo.svg.png"  width="60" height="60" />
            <span style="display:inline-flex;"><p>Kelurahan Ketintang<br/>Kecamatan Gayungan</p></span>
        </div>
        <h1 style="margin-top:1.5rem;text-align:center;">Pengaduan Kelurahan</h1>
        <form>
            <div style="margin-bottom:1rem;">
                <label for="email" style="margin-bottom:.5rem">Pengirim</label>
                <input type="email" style="display:block;width:100%;padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#212529;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;-webkit-appearance:none;-moz-appearance:none;appearance:none;border-radius:.25rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out" id="email" name="email" value="{{$details['title']}}">
            </div>
            <div style="margin-bottom:1rem;">
                <label for="saran" style="margin-bottom:.5rem">Saran</label>
                <textarea style="display:block;width:100%;padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#212529;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;-webkit-appearance:none;-moz-appearance:none;appearance:none;border-radius:.25rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out" id="saran" name="saran" rows="4" cols="50">{{$details['body']}}
            </textarea>
        </form>
        </div>
</body>

</html>