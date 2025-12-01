<?php

namespace App\Http\Controllers\Web\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Post;
use App\Services\OfficerService;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    protected $officerService;

    public function __construct(OfficerService $officerService)
    {
        $this->officerService = $officerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officers = $this->officerService->getAll();
        return view('officers.index', compact('officers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $posts = Post::where('status', 'Active')->get(['id', 'name']);
        return view('officers.create', compact('posts'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->officerService->store($request->all());
        return redirect()->route('officers.index')->with('success', 'Officer Created Successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Officer $officer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Officer $officer)
    {
        $posts = Post::all();
        return view('officers.edit', compact('posts', 'officer'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Officer $officer)
    {
        $this->officerService->update($officer, $request->all());
        return redirect()->route('officers.index')->with('success', 'Officer Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Officer $officer)
    {
        //
    }

    public function appointments($id)
    {
        $officer = $this->officerService->appointments($id);

        return view('officers.appointment', compact('officer'));
    }




    public function activate(Officer $officer)
    {
        $response = $this->officerService->activate($officer);
        return back()->with($response['success'] ? 'success' : 'error', $response['message']);
    }

    public function deactivate(Officer $officer)
    {
        $response = $this->officerService->deactivate($officer);
        return back()->with($response['success'] ? 'success' : 'error', $response['message']);
    }
}
