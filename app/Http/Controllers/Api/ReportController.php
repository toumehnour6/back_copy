<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\controller;
use App\Models\User;

class ReportController extends Controller
{
    public function store(Request $request)
    {
    $data=$request->validate(['reported_user_id'=>'required|exists:users,id',
    'description'=>'required|string']);
        if($request->user()->id==$data['reported_user_id'])
        {
            return response()->json(['message'=>"لا يمكنك الابلاغ عن نفسك"],422);
        }
        $reportedUser= User::findOrFail($data['reported_user_id']);
        if($reportedUser->role=='admin')
        {
           return response()->json(['message'=>' لا يمكن الابلاغ عن الادمن']);
        }
         $report=Report::create(['reporter_id'=>$request->user()->id,'reported_user_id'=>$data['reported_user_id'],'description'=>$data['description'],'status'=>'pending']);
    return response()->json(['message'=>'تم ارسال البلاغ بنجاح','report=>$report'],201);
    }

    public function adminDecision(Request $request, int $id)
    {
        $data=$request->validate(['status'=>'required|in:accepted,rejected',
        'admin_decision'=>'nullable|string']);
        $report=Report::findOrFail($id);
        $report->update(['status'=>$data['status'],
        'admin_decision'=>$data['admin_decision']??null]);
        return response()->json(['message'=>'تم تحديث قرار الادمن', 'report'=>$report]);
    }
    public function index()
    {
        $reports=Report::with(['reporter','reportedUser'])->latest()->get();
        return response()->json(['reports'=>$reports],200);
    }
}   