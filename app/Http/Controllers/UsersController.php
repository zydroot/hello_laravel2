<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function index(){
        return view('users.index');
    }

    public function show(User $user){
        return view('users.show', compact('user'));
    }
    //
    public function create(){
        return view('users.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        session()->flash('success', '注册成功，开启一段新的旅程!');
        return redirect()->route('users.show', [$user->id]);
    }

    public function edit(User $user){
        return view('users.edit');
    }

    public function update(){

    }

    public function destroy(){

    }
}
