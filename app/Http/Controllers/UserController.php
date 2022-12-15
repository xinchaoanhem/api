<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\JobBoard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request, $id)
    {
        $search = $request['q'] ?? '';

        $users = User::all()->whereNotIn('id', JobBoard::find($id)->users()->pluck('users.id')->toArray());

        if ($search != null){
            $users = User::where('email', 'LIKE', "%$search%")->whereNotIn('id', JobBoard::find($id)->users()->pluck('users.id')->toArray())->get();
        }

        return $users;
    }

    public function getInCard($id)
    {
        return Card::find($id)->users;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập được cung cấp không chính xác.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('logout', 201);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        $user =User::find(Auth::user()->id);
        $request->validate([
            'name' => 'required',
            'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:5048',
        ]);

        $input = $request->all();
        if ($image = $request->file('avatar')) {
            $us =User::find(Auth::user()->id);
            if ($us->avatar) {
                Storage::disk('public')->delete($us->avatar);
            }

            $destinationPath = $image->store('avatars', 'public');
            $input['avatar'] = $destinationPath;
        }

        $user->update($input);

        return $user;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();
        if(Hash::check($request->old_password, $user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json(['success' => 'User password update successfully']);
        } else {
            return response()->json(['message' => 'Mật khẩu cũ không đúng'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
