<?php

namespace App\Http\Controllers;

use App\Models\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = DB::table('images')->paginate(10);
        return view('admin.image', ['images' => $images]);
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $images = Image::where('name', 'like', "%" . $keyword . "%")->paginate(5);
        return view('admin.image', compact('images'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searching(Request $request){
        if ($request->ajax()) {
            $output = "";
            $keyword = $request->search;
            if ($keyword === null || trim($keyword) === '') {
                $products = DB::table('images')->paginate(10);
            } else $products = Image::where('name', 'like', "%" . $keyword . "%")->paginate(5);
            if ($products) {
                foreach ($products as $key => $product) {
                    $output .=
                        "<tr>
                            <th scope='row'>$product->id</th>
                            <td>
                                <img height='50' width='50' src='https://kel-ketintang.id/storage/public/banner/$product->link' />
                            </td>
                            <td>$product->name</td>
                            <td><a href='https://kel-ketintang.id/storage/public/banner/$product->link'>Link Ada Disini</a></td>
                            <td>
                                <a  class='btn btn-danger delete-confirm' href='/delete-image/$product->id'>Delete</a>
                                <a class='btn btn-warning' href='/edit-image/$product->id'>Edit</a>
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
        return view('admin.create-image');
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
            "image" => 'required',
            "name" => 'required',
        ]);

        $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('public/banner', $imageName);


        image::create([
            'name' => $request->name,
            'link' => $imageName,
        ]);
        return redirect('create-image')->with('status', 'Data Sukses Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::find($id);
        return view('admin.edit-image', ['image' => $image]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            // "image" => 'required',
            "name" => 'required',
        ]);


        $data = Image::find($image->id);

        if ($request->file('image') != null) {
            // delete file
            $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'banner' . '/' . $data->link;
            unlink($image_path);

            // store new file
            $imageName =  $request->name . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/banner', $imageName);

            // update db
            Image::where('id', $image->id)
                ->update([
                    'name' => $request->name,
                    'link' => $imageName,
                ]);
        } else {
            Image::where('id', $image->id)
                ->update([
                    'name' => $request->name,
                ]);
        }
        return redirect('admin-image')->with('status', 'Data Sukses Diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Image::find($id);
        $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'banner' . '/' . $data->link;
        unlink($image_path);
        $data->delete();
        return redirect('admin-image')->with('status', 'Gambar Sukses Dihapus');
    }
}
