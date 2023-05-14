<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index ()
    {
        $users = User::all();
        return  response($users,201);

    }

    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'graduating_id'=>'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()

                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'graduating_id'=>$request->graduating_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'=> false,
                'message' => $th->getMessage()
            ], 500);
        }       
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our records.'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' =>$th->getMessage()
            ], 500);
        }

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message'=>'Logged out successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $Comment=User::findOrFail($id);
        $Comment->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        return response([
            'message'=>'Your data has been updated successfully'
        ],201);
    }

    public function show($id)
    {
        $User=User::findOrFail($id);
        return response($User,201);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user|| !Hash::check($request->password,$user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect.',
            ]);
        } else{

            $request->user()->delete();

            return response([
            'message'=>'User Deleted successfully'
            ]);
        }
    }
}
