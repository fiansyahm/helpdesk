<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;


class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $photos = DB::table('photos')->paginate(10);
        return view('admin.photo', ['photos' => $photos]);
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $photos = Photo::where('title', 'like', "%" . $keyword . "%")->paginate(5);
        return view('admin.photo', compact('photos'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function searching(Request $request){
        if ($request->ajax()) {
            $output = "";
            $keyword = $request->search;
            if ($keyword === null || trim($keyword) === '') {
                $products = DB::table('photos')->paginate(10);
            } else $products = Photo::where('title', 'like', "%" . $keyword . "%")->paginate(5);
            if ($products) {
                foreach ($products as $key => $product) {
                    $output .=
                        "<tr>
                            <th scope='row'>$product->id</th>
                            <td>
                                <img height='50' width='50' src='https://kel-ketintang.id/storage/public/photo/$product->name' />
                            </td>
                            <td>$product->title</td>
                            <td>$product->date</td>
                            <td>
                                <a  class='btn btn-danger delete-confirm' href='/delete-photo/$product->id'>Delete</a>
                                <a class='btn btn-warning' href='/edit-photo/$product->id'>Edit</a>
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
        return view('admin.create-photo');
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
            "description" => 'required',
            "date" => 'required',
            "image" => 'required',
        ]);

        $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();

        $request->file('image')->storeAs('public/photo', $imageName);


        Photo::create([
            'name' => $imageName,
            'date' => $request->date,
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect('create-photo')->with('status', 'Data Sukses Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = new Agent();
        $photo = Photo::find($id);
        return view('photo-detail', ['photo' => $photo, 'agent' => $agent, 'title' => "Detail"]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $photo = Photo::find($id);
        return view('admin.edit-photo', ['photo' => $photo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            "title" => 'required',
            "name" => 'required',
            "description" => 'required',
            "date" => 'required',
            // "image" => 'required',
        ]);

        if ($request->file('image') != null) {
            // delete file
            $data = Photo::find($photo->id);
            $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'photo' . '/' . $data->name;
            unlink($image_path);

            // store new file
            $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/photo', $imageName);

            // update db
            Photo::where('id', $photo->id)
                ->update([
                    'name' => $imageName,
                    'date' => $request->date,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
        } else {
            Photo::where('id', $photo->id)
                ->update([
                    'date' => $request->date,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
        }



        return redirect('admin-photo')->with('status', 'Data Sukses Diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Photo::find($id);
        $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'photo' . '/' . $data->name;
        unlink($image_path);
        $data->delete();
        return redirect('admin-photo')->with('status', 'Gambar Sukses Dihapus');
    }
}
