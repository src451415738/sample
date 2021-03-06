<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    public function create() 
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
        // 第二个参数为布尔值，提供记住我的功能
        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                 // 登陆成功后的相关操作
                session()->flash('success', '欢迎回来！');
                return redirect()->route('users.show', [Auth::user()]);
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        // 路径，可省略/
        return redirect('login');
        // name
        // return redirect()->route('login');
    }
}
