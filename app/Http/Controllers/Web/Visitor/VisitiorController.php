<?php

namespace App\Http\Controllers\Web\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Services\VisitorService;
use Illuminate\Http\Request;

class VisitiorController extends Controller
{


    protected $visitorService;

    public function __construct(VisitorService $visitorService)
    {
        $this->visitorService = $visitorService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitors = $this->visitorService->getAll();
        return view('visitors.index', compact('visitors'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('visitors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required',
            'email' => 'required|email|max:255',

        ]);

        $this->visitorService->create($request->only('name', 'mobile_no', 'email'));

        return redirect()->route('visitors.index')->with('success', 'Visitor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        return view('visitors.edit', compact('visitor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable',
            'email' => 'nullable|email|max:255',
        ]);

        // Update using service
        $this->visitorService->update($visitor, $request->only('name', 'mobile_no', 'email'));

        return redirect()->route('visitors.index')
            ->with('success', 'Visitor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        //
    }

    public function activate($id)
    {
        $response = $this->visitorService->activate($id);

        if (!$response['success']) {
            return back()->with('error', $response['message']);
        }

        return back()->with('success', $response['message']);
    }

    public function deactivate($id)
    {
        $response = $this->visitorService->deactivate($id);

        if (!$response['success']) {
            return back()->with('error', $response['message']);
        }

        return back()->with('success', $response['message']);
    }
}
