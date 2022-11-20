<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = DB::table('schedules')->paginate(10);
        return view('admin.schedule', ['schedules' => $schedules]);
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $schedules = Schedule::where('title', 'like', "%" . $keyword . "%")->paginate(5);
        return view('admin.schedule', compact('schedules'))->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function searching(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $keyword = $request->search;
            if ($keyword === null || trim($keyword) === '') {
                $products = DB::table('schedules')->paginate(10);
            } else $products = Schedule::where('title', 'like', "%" . $keyword . "%")->paginate(5);
            if ($products) {
                foreach ($products as $key => $product) {
                    $output .=
                        "<tr>
                            <th scope='row'>$product->id</th>
                            <td>
                                <img height='50' width='50' src='https://kel-ketintang.id/storage/public/schedule/$product->name' />
                            </td>
                            <td>$product->title</td>
                            <td>$product->date</td>
                            <td>
                                <a  class='btn btn-danger delete-confirm' href='/delete-schedule/$product->id'>Delete</a>
                                <a class='btn btn-warning' href='/edit-schedule/$product->id'>Edit</a>
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
        return view('admin.create-schedule');
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
      
        $request->file('image')->storeAs('public/schedule', $imageName);


        Schedule::create([
            'name' => $imageName,
            'date' => $request->date,
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect('create-schedule')->with('status', 'Data Sukses Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = new Agent();
        $schedule = Schedule::find($id);
        return view('schedule-detail', ['schedule' => $schedule,'agent' => $agent,'title'=>"Detail"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ----------------------------------------
        // |fianto itu gk ngenes cuma pura2 ngenes|
        // ---------------------------------------
        $schedule = Schedule::find($id);
        return view('admin.edit-schedule', ['schedule' => $schedule]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            "title" => 'required',
            "name" => 'required',
            "description" => 'required',
            "date" => 'required',
            // "image" => 'required',
        ]);

        if ($request->file('image')!= null) {
         // delete file
         $data = Schedule::find($schedule->id);
         $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'schedule' . '/' . $data->name;
         unlink($image_path);
 
         // store new file
         $imageName = time() . '.' . $request->name . '.' . $request->file('image')->getClientOriginalExtension();
         $request->file('image')->storeAs('public/schedule', $imageName);
 
         // update db
         Schedule::where('id', $schedule->id)
             ->update([
                 'name' => $imageName,
                 'date' => $request->date,
                 'title' => $request->title,
                 'description' => $request->description,
             ]);

        }
        else{
            // update db
         Schedule::where('id', $schedule->id)
         ->update([
             'date' => $request->date,
             'title' => $request->title,
             'description' => $request->description,
         ]);
        }

         
         return redirect('admin-schedule')->with('status', 'Data Sukses Diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // image::destroy($id);
         $data = Schedule::find($id);
         $image_path = public_path() . '/' . 'storage' . '/' . 'public' . '/' . 'schedule' . '/' . $data->name;
         unlink($image_path);
         $data->delete();
         return redirect('admin-schedule')->with('status', 'Agenda Sukses Dihapus');
    }
}
