<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request['q'] ?? '';

        $labels = Label::all();

        if ($search != null){
            $labels = Label::where('name', 'LIKE', "%$search%")->get();
        }

        return $labels;
    }

    public function getInCard($id)
    {
        return Card::find($id)->labels;
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
            'name' => 'required',
            'color' => 'required',
        ]);

        $label = new Label();
        $label->name = $request->input('name');
        $label->color = $request->input('color');
        $label->save();

        $card = Card::find($id);
        $card->labels()->attach(Label::find($label->id));

        return response()->json(['success' => 'Label store successfully']);
    }

    public function storeAttach(Request $request, $id)
    {
        $request->validate([
            'label_id' => 'required',
        ]);

        $card = Card::find($id);
        $card->labels()->attach(Label::find($request->input('label_id')));

        return response()->json(['success' => 'Label attach store successfully']);
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
            'color' => 'required',
        ]);

        $label = Label::find($id);
        $label->name = $request->input('name');
        $label->color = $request->input('color');
        $label->update();

        return response()->json(['success' => 'Label update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Label::destroy($id);
    }

    public function detach(Request $request, $id)
    {
        $card = Card::find($id);
        $card->labels()->detach(Label::find($request->input('label_id')));
    }
}
