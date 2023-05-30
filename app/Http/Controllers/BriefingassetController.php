<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Briefingasset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BriefingassetController extends Controller
{

    public function index()
    {
        try {
            $briefingassets = Briefingasset::select('briefingassets.*', 'briefings.title as briefing')
                ->join('briefings', 'briefings.id', '=', 'briefingassets.briefing_id')
                ->get();

            return response()->json($briefingassets, 200);
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
                'briefing_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $briefingasset = new Briefingasset($request->all());

            if ($request->hasFile('image')) {                
                $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                $briefingasset->image = $uploadedFileUrl;
            }

            $briefingasset->save();

            return response()->json([
                'status' => true,
                'message' => 'New asset created successfully',
                'asset' => $briefingasset,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Briefingasset $briefingasset)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $briefingasset
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Briefingasset $briefingasset)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'link' => 'required',
                'image' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }

            $briefingasset->fill($request->except('image'));

            if ($request->hasFile('image')) {
                Cloudinary::destroy($briefingasset->image);
                $uploadedFile = $request->file('image');
                $uploadResult = Cloudinary::upload($uploadedFile->getRealPath());
                $briefingasset->image = $uploadResult->getSecurePath();                
            }

            $briefingasset->save();

            return response()->json([
                'status' => true,
                'message' => 'Asset updated successfully',
                'asset' => $briefingasset,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Briefingasset $briefingasset)
    {
        try {            
            Cloudinary::destroy($briefingasset->image);            
            $briefingasset->delete();

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

    public function assetsByBriefing()
    {
        try {
            $briefingassets = Briefingasset::select(DB::raw('count(briefingassets.id) as count, briefings.title'))
                ->join('briefings', 'briefings.id', '=', 'briefingassets.briefing_id')
                ->groupBy('briefings.title')
                ->get();

            return response()->json($briefingassets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets by briefing.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function all()
    {
        try {
            $briefingassets = Briefingasset::select('briefingassets.*', 'briefings.title as briefing')
                ->join('briefings', 'briefings.id', '=', 'briefingassets.briefing_id')
                ->get();

            return response()->json($briefingassets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve assets.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
