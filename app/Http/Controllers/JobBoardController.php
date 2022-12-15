<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Directory;
use App\Models\JobBoard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;

class JobBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id)->jobBoards;

        return $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request, $id)
    {
//        $search = $request['q'] ?? '';

        $jobBoard = JobBoard::find($id)->users;

//        if ($search != null){
//            $jobBoard = JobBoard::find($id)->users->where('name', 'LIKE', "%$search%");
//        }

        return $jobBoard;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'title' => 'required',
        ]);

        $jobBoard = new JobBoard();
        $jobBoard->title = $request->input('title');
        $jobBoard->user_id = Auth::user()->id;
        $jobBoard->save();

        $user = User::find(Auth::user()->id);
        $user->jobBoards()->attach(JobBoard::find($jobBoard->id));

        return response()->json(['success' => 'Job board store successfully']);
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
        $jobBoard = JobBoard::find($id);
        $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:5048',
        ]);

        $input = $request->all();
        if ($image = $request->file('image')) {
            $jobBoard = JobBoard::find($id);
            Storage::disk('public')->delete($jobBoard->image);

            $destinationPath = $image->store('images', 'public');
            $input['image'] = $destinationPath;
        }

        $jobBoard->update($input);

        return $jobBoard;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attachUser(Request $request, $id)
    {
        $request->validate([
            'list_user' => 'required',
        ]);

        foreach ($request->list_user as $us){
            $user = User::find($us);
            $user->jobBoards()->attach(JobBoard::find($id));
        }

        return response()->json(['success' => 'Job board attach successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detachUser(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required',
        ]);

        $user = User::find($request->input('id_user'));
        $user->jobBoards()->detach(JobBoard::find($id));

        $directories = Directory::with('cards.users')->where('job_boards_id',$id)->get();

        foreach ($directories as $dir){
            foreach ($dir->cards as $card){
                foreach ($card->users as $user){
                    if ($user->id == $request->input('id_user')) {
                        $card = Card::find($card->id);
                        $card->users()->detach(User::find($user->id));
                    }
                }
            }
        }

        return response()->json(['success' => 'Job board detach successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobBoard = JobBoard::find($id);
        Storage::disk('public')->delete($jobBoard->image);
        $jobBoard->delete();

        return response()->json(['success' => 'Job board delete successfully']);
    }
}
