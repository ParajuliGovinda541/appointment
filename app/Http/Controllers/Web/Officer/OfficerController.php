<?php

namespace App\Http\Controllers\Web\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Post;
use App\Services\OfficerService;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    protected $service;

    public function __construct(OfficerService $service)
    {
        $this->service = $service;
    }

    /**
     * List all officers.
     */
    public function index()
    {
        $officers = $this->service->getAll();
        return view('officers.index', compact('officers'));
    }

    /**
     * Show create form.
     */
    public function create()
    {

        $posts = Post::where('status', 'Active')->get();
        return view('officers.create', compact('posts'));
    }

    /**
     * Store new officer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'post_id'         => 'required|exists:posts,id',
            'work_start_time' => 'required',
            'work_end_time'   => 'required',
            'status'          => 'nullable|string',
        ]);

        $this->service->store($validated);

        return redirect()->route('officers.index')->with('success', 'Officer created successfully!');
    }

    /**
     * Show officer details.
     */
    public function show($id)
    {
        $officer = $this->service->getById($id);
        return view('officers.show', compact('officer'));
    }

    /**
     * Edit officer.
     */
    public function edit($id)
    {
        $officer = $this->service->getById($id);
        return view('officers.edit', compact('officer'));
    }

    /**
     * Update officer details.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'post_id'         => 'required|exists:posts,id',
            'work_start_time' => 'required',
            'work_end_time'   => 'required',
        ]);

        $officer = Officer::findOrFail($id);
        $this->service->update($officer, $validated);

        return redirect()->route('officers.index')->with('success', 'Officer updated successfully!');
    }

    /**
     * Activate officer.
     */
    public function activate($id)
    {
        $officer = Officer::findOrFail($id);
        $response = $this->service->activate($officer);

        return redirect()->back()->with('success', $response['message']);
    }

    /**
     * Deactivate officer.
     */
    public function deactivate($id)
    {
        $officer = Officer::findOrFail($id);
        $response = $this->service->deactivate($officer);

        return redirect()->back()->with('success', $response['message']);
    }

    /**
     * Officer appointments detail.
     */
    public function appointments($id)
    {
        $officer = $this->service->appointments($id);
        return view('officers.appointment', compact('officer'));
    }
}
