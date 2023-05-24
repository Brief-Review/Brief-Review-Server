<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    public function index()
    {
        try {
            $assets = Asset::select('assets.*', 'users.name as user')
                ->join('users', 'users.id', '=', 'assets.user_id')
                ->get();

            return response()->json($assets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'link' => 'required',
                'image' => 'required|image|mimes:png,jpg,jpeg',
                'tags' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $asset = new Asset($request->all());
            $asset->user_id = $request->user()->id;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $asset->image = $imagePath;
            }

            $asset->save();

            return response()->json([
                'status' => true,
                'message' => 'New asset created successfully',
                'asset' => $asset,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Asset $asset)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $asset
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Asset $asset)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'link' => 'required',
                'image' => 'nullable|image',
                'tags' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $asset->fill($request->except('image'));

            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($asset->image);
                $imagePath = $request->file('image')->store('images', 'public');
                $asset->image = $imagePath;
            }

            $asset->save();

            return response()->json([
                'status' => true,
                'message' => 'Asset updated successfully',
                'asset' => $asset,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Asset $asset)
    {
        try {
            $asset->delete();

            return response()->json([
                'status' => true,
                'message' => 'Asset deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assetsByUser()
    {
        try {
            $assets = Asset::select(DB::raw('count(assets.id) as count, users.name'))
                ->join('users', 'users.id', '=', 'assets.user_id')
                ->groupBy('users.name')
                ->get();

            return response()->json($assets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets by user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function all()
    {
        try {
            $assets = Asset::select('assets.*', 'users.name as user')
                ->join('users', 'users.id', '=', 'assets.user_id')
                ->get();

            return response()->json($assets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
