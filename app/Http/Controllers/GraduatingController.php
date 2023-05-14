<?php

namespace App\Http\Controllers;

use App\Models\Graduating;
use Illuminate\Http\Request;

class GraduatingController extends Controller
{

    public function index()
    {
        $graduatings = Graduating::all();
        return response()->json($graduatings);
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category' => 'required',
            'duration' => 'required',
            'location' => 'required',
            'partners' => 'required',
            'manager' => 'required',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $graduating = new Graduating($request->input());
        $graduating->save();
        return response()->json([
            'status' => true,
            'message' => 'New Graduating Group created Successfully'
        ], 200);

    }


    public function show(Graduating $graduating)
    {
        return response()->json(['status' => true, 'data' => $graduating]);
    }


    public function update(Request $request, Graduating $graduating)
    {
        $rules = [
            'name' => 'required',
            'category' => 'required',
            'duration' => 'required',
            'location' => 'required',
            'partners' => 'required',
            'manager' => 'required',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $graduating->update($request->input());
        return response()->json([
            'status' => true,
            'message' => 'Graduating Group Updated successfully'
        ], 200);
    }


    public function destroy(Graduating $graduating)
    {
        $graduating->delete();
        return response()->json([
            'status' => true,
            'message' => 'Graduating Group Deleted successfully'
        ], 200);
    }
}
