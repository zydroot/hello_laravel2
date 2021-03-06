<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show', 'store', 'create', 'index', 'confirmEmail']
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

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的邮箱，请注意查收！');
        return redirect('/');

        /*Auth::login($user);
        session()->flash('success', '注册成功，开启一段新的旅程!');
        return redirect()->route('users.show', [$user->id]);*/

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

    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token){
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;

        Auth::login($user);
        session()->flash('success', '恭喜您，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
