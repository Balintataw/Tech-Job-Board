<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Job;

class JobController extends Controller
{
    public function index() {
        $jobs = Job::all();
        return ['jobs' => $jobs];
    }

    public function show($id, Job $job) {
        $job['company'] = $job->company;
        return ['job' => $job];
    }

    public function create(Request $request) {
        $job = Job::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'address' => $request->address,
            'type' => $request->type,
            'position' => $request->position,
            'remote' => $request->remote,
            'description' => $request->description,
            'roles' => $request->roles,
            'category_id' => 1
        ]);

        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job
        ]);
    }
}
