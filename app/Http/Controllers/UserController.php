<?php

namespace App\Http\Controllers;



// class UserController extends Controller
// {
//     public function index()
//     {
//         $users = User::all();
//         return  response($users, 201);
//     }

//     public function register(Request $request)
//     {
//         $validatedData = $request->validate([
//             'name' => 'required',
//             'email' => 'required|email|unique:users,email',
//             'password' => 'required|min:6',
//         ]);

//         $user = User::create([
//             'name' => $validatedData['name'],
//             'email' => $validatedData['email'],
//             'password' => Hash::make($validatedData['password']),
//         ]);

//         $accessToken = $user->createToken('API Token')->accessToken;

//         return response()->json([
//             'status' => true,
//             'message' => 'User created successfully',
//             'access_token' => $accessToken,
//         ], 200);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         if (!auth()->attempt($credentials)) {
//             throw ValidationException::withMessages([
//                 'email' => ['Invalid credentials'],
//             ]);
//         }

//         $accessToken = auth()->user()->createToken('API Token')->accessToken;

//         return response()->json([
//             'status' => true,
//             'message' => 'User Logged In successfully',
//             'access_token' => $accessToken,
//         ], 200);
//     }

//     public function logout(Request $request)
//     {
//         $request->user()->tokens()->delete();

//         return response([
//             'message' => 'Logged out successfully'
//         ]);
//     }




//     public function update(Request $request, $id)
//     {        
//         $user = Auth::user();

//         try {
//             $userToUpdate = User::findOrFail($id);

            
//             if ($user->role === "1") {
                
//                 $validator = Validator::make($request->all(), [
//                     'name' => 'nullable',  
//                     'password' => 'required',                  
//                     'address' => 'nullable',
//                     'graduating' => 'nullable',
//                     'email' => 'nullable|email',
//                     'role' => 'required'
//                 ]);

//                 if ($validator->fails()) {
//                     return response()->json([
//                         'status' => false,
//                         'errors' => $validator->errors()->all()
//                     ], 400);
//                 }

//                 $userToUpdate->name = $request->input('name');                
//                 $userToUpdate->password = Hash::make($request->input('password'));
//                 $userToUpdate->address = $request->input('address');
//                 $userToUpdate->graduating = $request->input('graduating');
//                 $userToUpdate->email = $request->input('email');                
//                 $userToUpdate->role = $request->input('role');
//             } else {
                
//                 $validator = Validator::make($request->all(), [
//                     'name' => 'required',
//                     'password' => 'required',
//                     'address' => 'nullable',               
//                 ]);

//                 if ($validator->fails()) {
//                     return response()->json([
//                         'status' => false,
//                         'errors' => $validator->errors()->all()
//                     ], 400);
//                 }

//                 $userToUpdate->name = $request->input('name');
//                 $userToUpdate->password = Hash::make($request->input('password'));
//                 $userToUpdate->address = $request->input('address');
//             }

//             $userToUpdate->save();

//             return response()->json([
//                 'status' => true,
//                 'message' => 'User updated successfully',
//                 'user' => $userToUpdate
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'status' => false,
//                 'message' => 'Failed to update user.',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }



//     public function show($id)
//     {
//         $User = User::findOrFail($id);
//         return response($User, 201);
//     }

//     public function destroy(Request $request)
//     {
//         $request->validate([

//             'email' => 'required',
//             'password' => 'required'
//         ]);

//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             return response([
//                 'message' => 'The provided credentials are incorrect.',
//             ]);
//         } else {

//             $request->user()->delete();

//             return response([
//                 'message' => 'User Deleted successfully'
//             ]);
//         }
//     }
// }

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response($users, 201);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $accessToken = $user->createToken('API Token')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'access_token' => $accessToken,
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $accessToken = auth()->user()->createToken('API Token')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'User Logged In successfully',
            'access_token' => $accessToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logged out successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $userToUpdate = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'password' => 'required',
            'address' => 'nullable',
            'graduating' => 'nullable',
            'email' => 'nullable|email',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        $userToUpdate->name = $request->input('name');
        $userToUpdate->password = Hash::make($request->input('password'));
        $userToUpdate->address = $request->input('address');
        $userToUpdate->graduating = $request->input('graduating');
        $userToUpdate->email = $request->input('email');
        $userToUpdate->role = $request->input('role');

        $userToUpdate->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $userToUpdate,
        ], 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response($user, 201);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect.',
            ]);
        } else {
            $user->delete();

            return response([
                'message' => 'User Deleted successfully',
            ]);
        }
    }
}

