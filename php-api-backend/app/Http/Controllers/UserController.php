<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $felids = $request->validate([
            'name' => 'required | string',
            'email' => 'required | unique:users,email',
            'password' => 'required | confirmed',
            'points' => 'required',
            'health' => 'required',
            'x' => 'required',
            'y' => 'required',
            'z' => 'required',

        ]);

        $user = User::Create([
            'name'=> $felids['name'],
            'email'=> $felids['email'],
            'password'=>bcrypt($felids['password']),
            'points'=> $felids['points'],
            'health'=> $felids['health'],
            'x'=> $felids['x'],
            'y'=> $felids['y'],
            'z'=> $felids['z'],
        ]);

        $token = $user->createToken('mygametoken')->plainTextToken;

        $respone = [
            'user' => $user,
            'token' => $token

        ];

        return response($respone , 201);
    }

    public function login(Request $request)
    {
        $felids = $request->validate([
            'email' => 'required |string',
            'password' => 'required |string',
        ]);

        //Check for user
        $user = User::where('email', $felids['email'])->first();

        if(!$user || Hash::check($felids['password'] , $user->password))
        {
            return response([
                'messege' => 'Bad Creidinals'
            ], 401);
        }


        $token = $user->createToken('mygametoken')->plainTextToken;

        $respone = [

            'user'=> $user,
            'token' => $token

        ];

        return response($respone , 201);
    }

    public function update(Request $request){

        $felids = $request->validate([
           
            'x'=>'required | string',
            'y'=>'required',
            'z'=>'required',
            'points'=>'required',
            'health'=>'required',
            'id'=> 'required',

        ]);

        $userid = User::find($felids['id']);
        $userid->x = $felids['x'];
        $userid->y = $felids['y'];
        $userid->z = $felids['z'];
        $userid->points = $felids['points'];
        $userid->health = $felids['health'];
        $userid->save();
        $respone = [
            'user' => $userid,
            
        ];

        return response($respone , 201);
    }
}
