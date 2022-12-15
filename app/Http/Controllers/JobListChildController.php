<?php

namespace App\Http\Controllers;

use App\Models\JobList;
use App\Models\JobListChild;
use Illuminate\Http\Request;

class JobListChildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return JobList::find($id)->jobListChilds;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'job_list_id' => 'required',
        ]);

        $jobBoard = new JobListChild();
        $jobBoard->title = $request->input('title');
        $jobBoard->job_list_id = $request->input('job_list_id');
        $jobBoard->save();

        return response()->json(['success' => 'Job list child store successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $jobBoard = JobListChild::find($id);
        $jobBoard->title = $request->input('title');
        $jobBoard->update();

        return response()->json(['success' => 'Job list child update successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $jobBoard = JobListChild::find($id);
        $jobBoard->status = $request->input('status');
        $jobBoard->update();

        return response()->json(['success' => 'Job list child status update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return JobListChild::destroy($id);
    }
}
