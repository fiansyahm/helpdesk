<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = DB::table('videos')->paginate(10);
        return view('admin.video', ['videos' => $videos]);
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $videos = Video::where('title', 'like', "%" . $keyword . "%")->paginate(5);
        return view('admin.video', compact('videos'))->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function searching(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $keyword = $request->search;
            if ($keyword === null || trim($keyword) === '') {
                $products = DB::table('videos')->paginate(10);
            } else $products = Video::where('title', 'like', "%" . $keyword . "%")->paginate(5);
            if ($products) {
                foreach ($products as $key => $product) {
                    $output .=
                        "<tr>
                            <th scope='row'>$product->id</th>
                            <td>
                                <img height='50' width='50' src='https://kel-ketintang.id/storage/public/thumbnail_video/$product->poster' />
                            </td>
                            <td>$product->title</td>
                            <td>$product->date</td>
                            <td>
                                <a  class='btn btn-danger delete-confirm' href='/delete-video/$product->id'>Delete</a>
                                <a class='btn btn-warning' href='/edit-video/$product->id'>Edit</a>
                            </td>
                        </tr>";
                }
                return Response($output);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-video');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => 'required',
            "name" => 'required',
            "image" => 'required',
            "date" => 'required',
            "video" => 'required',
        ]);

        $videoName = time() . '.' . $request->name . '.' . $request->file('video')->getClientOriginalExtension();
        $request->file('video')->storeAs('public/video', $videoName);

        $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('public/thumbnail_video', $imageName);


        Video::create([
            'name' => $videoName,
            'poster' => $imageName,
            'date' => $request->date,
            'title' => $request->title,
        ]);
        return redirect('create-video')->with('status', 'Data Sukses Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = new Agent();
        $video = Video::find($id);
        return view('video-detail', ['video' => $video, 'agent' => $agent,'title'=>"Detail"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::find($id);
        return view('admin.edit-video', ['video' => $video]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            "title" => 'required',
            "name" => 'required',
            // "image" => 'required',
            "date" => 'required',
            // "video" => 'required',
        ]);

        if ($request->file('image') != null) {
            // delete file
            $data = Video::find($video->id);
            $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'thumbnail_video' . '/' . $data->poster;
            unlink($image_path);

            // store new file
            $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/thumbnail_video', $imageName);
            // update db
            Video::where('id', $video->id)
                ->update([
                    'poster' => $imageName,
                    'date' => $request->date,
                    'title' => $request->title,
                ]);
        }

        if ($request->file('video') != null) {
            // delete file
            $video_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'video' . '/' . $data->name;
            unlink($video_path);

            // store new file
            $videoName = time() . '.' . $request->name . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->storeAs('public/video', $videoName);
            // update db
            Video::where('id', $video->id)
                ->update([
                    'name' => $videoName,
                    'date' => $request->date,
                    'title' => $request->title,
                ]);
        }

        return redirect('admin-video')->with('status', 'Data Sukses Diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // image::destroy($id);
        $data = Video::find($id);
        $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'thumbnail_video' . '/' . $data->poster;
        unlink($image_path);

        $video_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'video' . '/' . $data->name;
        unlink($video_path);
        $data->delete();
        return redirect('admin-video')->with('status', 'Gambar Sukses Dihapus');
    }
}
