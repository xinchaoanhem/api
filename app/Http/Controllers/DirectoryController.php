<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Directory;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $directories = Directory::with('cards','cards.users','cards.labels','cards.files','cards.jobLists.jobListChilds','cards.directory')->where('job_boards_id',$id)->orderBy('index', 'ASC')->get();

        return $directories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
        $directories = Directory::with('cards','cards.users','cards.labels','cards.files','cards.jobLists.jobListChilds','cards.directory')
            ->where('job_boards_id',1)
            ->whereHas('cards', function ($query) {
                $query->whereHas('files', function ($query) {
                    $query->where('id', 2);
                });
            })
            ->orderBy('index', 'ASC')->get();

        return $directories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'index' => 'required',
        ]);

        $directory = new Directory();
        $directory->title = $request->input('title');
        $directory->index = $request->input('index');
        $directory->job_boards_id = $id;
        $directory->save();

        return response()->json(['success' => 'Directory store successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $directory = Directory::find($id);
        $directory->title = $request->input('title');
        $directory->update();

        return response()->json(['success' => 'Directory update successfully']);
    }

    public function updateIndex(Request $request, $id)
    {
        $request->validate([
            'directory_order_list' => 'required'
        ]);

        $directories = Directory::where('job_boards_id',$id)->get();

        foreach ($directories as $dir){
            $dir->timestamps = false;
            $id = $dir->id;
            foreach ($request->directory_order_list as $diro){
                if ($diro['id'] == $id) {
                    $dir->update(['index' => $diro['index']]);
                }
            }
        }

        return response()->json(['success' => 'Directory index update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $request->validate([
            'job_boards_id' => 'required',
        ]);

        $directory = Directory::find($id);
        $directory->delete();

        $directories = Directory::where('job_boards_id',$request->input('job_boards_id'))->orderBy('index', 'ASC')->get();

        $i = 0;
        foreach ($directories as $dir){
            $dir->timestamps = false;
            $dir->update(['index' => $i]);
            $i++;
        }

        return response()->json(['success' => 'Directory delete successfully']);
    }
}
