<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\JobPostRequest;
use App\Job;
use App\Company;

class JobController extends Controller
{
    public function index() {
        $jobs = Job::with('company')->get();
        return ['jobs' => $jobs];
    }

    public function show($id, Job $job) {
        $job['company'] = $job->company;
        return ['job' => $job];
    }

    public function create(JobPostRequest $request) {
        $user_id = auth()->user()->id;
        $company = Company::where('user_id', $user_id)->firstOrFail();

        $job = Job::create([
            'title' => $request->title,
            'user_id' => $user_id,
            'company_id' => $company->id,
            'slug' => Str::slug($request->title),
            'address' => $request->address,
            'type' => $request->type,
            'position' => $request->position,
            'remote' => $request->remote,
            'description' => $request->description,
            'roles' => $request->roles,
            'category_id' => $request->category_id,
            'last_date' => $request->last_date,
            'status' => 1
        ]);

        return response()->json([
            'redirect' => '/company/' . $company->slug, 
            'message' => 'Job created successfully',
            'job' => $job,
        ], 200);
    }

    public function update(JobPostRequest $request) {

        $job_id = $request->id;

        Job::where('id', $job_id)->update([
            'title' => $request->title,
            'address' => $request->address,
            'type' => $request->type,
            'position' => $request->position,
            'remote' => $request->remote,
            'description' => $request->description,
            'roles' => $request->roles,
            'category_id' => $request->category_id,
            'last_date' => $request->last_date,
            'status' => 1
        ]);
        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function myjobs() {
        $user_id = auth()->user()->id;
        $jobs = Job::with('company')->where('user_id', $user_id)->get();
        return response()->json([
            'jobs' => $jobs
        ], 200);
    }

    public function destroy(Request $request) {
        $result = Job::destroy($request->id);
        return response()->json([
            'message' => 'Job deleted successfully',
            'result' => $result
        ], 200);
    }
}
