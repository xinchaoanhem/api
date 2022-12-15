<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $cards = Card::with('users', 'labels', 'files' ,'jobLists.jobListChilds', 'directory')->where('directory_id',$id)->orderBy('index', 'ASC')->get();

        return $cards;
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

        $card = new Card();
        $card->title = $request->input('title');
        $card->index = $request->input('index');
        $card->status = 0;
        $card->directory_id = $id;
        $card->save();

        return response()->json(['success' => 'Card store successfully']);
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
//        $request->validate([
//            'title' => 'required',
//            'description' => 'required',
//        ]);

        $card = Card::find($id);
        if ($request->input('title')) $card->title = $request->input('title');
        if ($request->input('description')) $card->description = $request->input('description');
        $card->update();

        return response()->json(['success' => 'Card update successfully']);
    }

    public function updateIndex(Request $request, $id)
    {
        $request->validate([
            'card_list' => 'required'
        ]);

        $cards = Card::where('directory_id',$id)->get();

        foreach ($cards as $ca){
            $ca->timestamps = false;
            $idc = $ca->id;
            foreach ($request->card_list as $cal){
                if ($cal['id'] == $idc) {
                    $ca->update(['index' => $cal['index']]);
                }
//                else if ($cal['id'] != $id) {
//                    $ca->update(['index' => $cal['index']]);
//                }
            }
        }
        return response()->json(['success' => 'Card index update successfully']);
    }

    public function updateDirectory(Request $request, $id)
    {
        $request->validate([
            'card_list' => 'required',
            'directory_id' => 'required',
        ]);
        $card = Card::find($id);
        $card->directory_id = $request->input('directory_id');
        $card->update();

        $cards = Card::where('directory_id',$request->directory_id)->get();

        foreach ($cards as $ca){
            $ca->timestamps = false;
            $idc = $ca->id;
            foreach ($request->card_list as $cal){
                if ($cal['id'] == $idc) {
                    $ca->update(['index' => $cal['index']]);
                }
            }
        }

        return response()->json(['success' => 'Card directory update successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $card = Card::find($id);
        $card->status = $request->input('status');
        $card->update();

        return response()->json(['success' => 'Card status update successfully']);
    }

    public function updateDeadline(Request $request, $id)
    {
        $request->validate([
            'deadline' => 'required',
        ]);

        $card = Card::find($id);
        $card->deadline = $request->input('deadline');
        $card->update();

        return response()->json(['success' => 'Card deadline update successfully']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attachUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $card = Card::find($id);
        $card->users()->attach(User::find($request->input('user_id')));

        return response()->json(['success' => 'User attach store successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detachUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $card = Card::find($id);
        $card->users()->detach(User::find($request->input('user_id')));

        return response()->json(['success' => 'User detach store successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Card::destroy($id);
    }
}
