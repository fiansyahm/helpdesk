<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Http\Request;
 
class MailController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function send(Request $request)
    {
        $details=[
            'title'=>$request->nama,
            'body'=>$request->saran,
        ];
        // Mail::to("kel.ketintang@gmail.com")->send(new TestMail($details));
        Mail::to("maudini.nurwulan@gmail.com")->send(new TestMail($details));
        return redirect('kontak')->with('status', 'Sukses Mengirim Pengaduan');
    }
}