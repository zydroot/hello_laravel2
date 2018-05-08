<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show', 'store', 'create', 'index']
        ]);
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function index(){ //?
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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

        Auth::login($user);
        session()->flash('success', '注册成功，开启一段新的旅程!');
        return redirect()->route('users.show', [$user->id]);
    }

    public function edit(User $user){ //?
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user){ //?
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '资料更新成功');
        return redirect()->route('users.show',[$user->id]);


    }

    public function destroy(User $user){ //?

        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '恭喜，删除成功！');
        return redirect()->back();
    }
}
