<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return Card::find($id)->files;
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
            'file' => 'required|mimes:doc,docx,pdf,txt,csv,png,jpg,jpeg,gif,svg|max:10148',
        ]);

        if ($file = $request->file('file')) {
            $path = $file->store('files', 'public');
            $name = $file->getClientOriginalName();

            //store your file into directory and db
            $save = new File();
            $save->name = $name;
            $save->path = $path;
            $save->card_id = $id;
            $save->save();
        }

        return response()->json(['success' => 'File store successfully']);
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
            'name' => 'required',
        ]);

        $file = File::find($id);
        $file->name = $request->input('name');
        $file->update();

        return response()->json(['success' => 'File update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::find($id);
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['success' => 'File delete successfully']);
    }
}
