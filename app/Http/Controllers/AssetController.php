<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::select('assets.*', 'users.name as user')->join('users', 'users.id', '=', 'assets.user_id');
        return response()->json($assets);
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'link' => 'required',
            'image' => 'required',
            'tags' => 'required',

        ];

        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $asset = new asset($request->input());
        $asset->user_id = $request->user()->id;
        $asset->save();
        return response()->json([
            'status' => true,
            'message' => 'New asset created Successfully'
        ], 200);
    }


    public function show(Asset $asset)
    {
        return response()->json(['status' => true, 'data' => $asset]);
    }


    public function update(Request $request, Asset $asset)
    {
        $rules = [
            'title' => 'required',
            'link' => 'required',
            'image' => 'required',
            'tags' => 'required',
            
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $asset->user_id = auth()->id();
        $asset->update($request->input());

        return response()->json([
            'status' => true,
            'message' => 'asset Updated successfully'
        ], 200);
    }


    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json([
            'status' => true,
            'message' => 'asset Deleted successfully'
        ], 200);
    }

    public function AssetsByuser()
    {
        $assets = Asset::select(DB::raw('count(assets.id) as count', 'users.name'))->join('users', 'users.id', '=', 'assets.user_id')->groupBy('users.name')->get();
        return response()->json($assets);
    }

    public function all()
    {
        $assets = Asset::select('assets.*', 'users.name as user')->join('users', 'users.id', '=', 'assets.user_id')->get();
        return response()->json($assets);
    }
}
