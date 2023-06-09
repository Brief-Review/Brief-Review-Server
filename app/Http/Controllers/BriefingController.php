<?php

namespace App\Http\Controllers;

use App\Models\Briefing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BriefingController extends Controller
{

    public function index()
    {
        $briefings = Briefing::paginate(10); 
        return response()->json([
            'status' => true,
            'data' => $briefings
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'repoGithub' => 'required',
            'feedback' => 'required',
            'graduating_id' => 'required',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $briefing = new Briefing($request->input());
        $briefing->user_id = auth()->id();
        $briefing->save();

        return response()->json([
            'status' => true,
            'message' => 'New Briefing created Successfully'
        ], 200);
    }


    public function show(Briefing $briefing)
    {
        return response()->json(['status' => true, 'data' => $briefing]);
    }


    public function update(Request $request, Briefing $briefing)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'repoGithub' => 'required',
            'feedback' => 'required',
            'graduating_id' => 'required',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $briefing->user_id = auth()->id();
        $briefing->update($request->input());

        return response()->json([
            'status' => true,
            'message' => 'Briefing Updated successfully'
        ], 200);
    }


    public function destroy(Briefing $briefing)
    {
        $briefing->delete();
        return response()->json([
            'status' => true,
            'message' => 'Briefing Deleted successfully'
        ], 200);
    }

    public function briefingsByGraduating()
    {
        $briefings = Briefing::select(DB::raw('count(briefings.id) as count', 'graduatings.name'))->join('graduatings', 'graduatings.id', '=', 'briefings.graduating_id')->groupBy('graduatings.name')->get();
        return response()->json($briefings);
    }

    public function all()
    {
        $briefings = Briefing::select('briefings.*', 'graduatings.name as graduating')->join('graduatings', 'graduatings.id', '=', 'briefings.graduating_id')->get();
        return response()->json($briefings);
    }
}
