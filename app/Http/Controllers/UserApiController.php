<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Ad;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{

    public function postRegister(Request $request)
    {
        //Retrieve the name input field
        $name = $request->input('name');

        //Retrieve the username input field
        $username = $request->email;

        //Retrieve the password input field
        $password = $request->password;
        $email = $request->useremail;
        $res = User::query()->create(["name" => $name, "email" => $username, "password" => $password, "email" => $email]);
        if ($res == true)
            echo "successfully added";
    }

    public function signup(UserRequest $request)
    {

        $validated = $request->validated();
        $validated['password']=Hash::make($validated['password']);

        /** @var User $res */
        $res = User::query()->create($validated);

        if($res)
        {
            $token = $res->createToken("");
            return ['token' => $token->plainTextToken,'user' => $res];
        }
        return $res;
    }

    public function login(Request $request)
    {
        $password = $request->password;
        $email = $request->input("email");

        /** @var User $user */
        $user = User::query()->where("email", $email)->get()->first();
         if ($user) {
            if (Hash::check($password,$user->password)) {
                $token = $user->createToken("");
                return ['token' => $token->plainTextToken,
                    'user' => $user];
            } else
                throw new AuthenticationException();
        } else
             throw new AuthenticationException();
    }

    public function logout(Request $request)
    {
      /** @var User $user */
        $user =$request->user();
        $user->tokens()->delete();
    }

    public function getUsers(Request $request)
    {

        $users = User::query()->get();
        foreach ($users as $user) {
            echo "$user->name -- $user->email <br> ";
        }

    }

    public function getUser(Request $request, $id)
    {

        $user = User::query()->where("id", $id)->get()->first();
        if ($user != null)
            echo "$user->name -- $user->email <br> ";
        else
            echo "$user->name -- $user->email <br> ";


    }
}
