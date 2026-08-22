<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    public function index()
    {
        $steps = ProcessStep::orderBy('display_order')->get();
        return view('admin.process_steps.index', compact('steps'));
    }

    public function create()
    {
        return view('admin.process_steps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['display_order'] = $validated['display_order'] ?? (ProcessStep::max('display_order') + 1);

        ProcessStep::create($validated);

        return redirect()->route('admin.process-steps.index')->with('success', 'Langkah Cara Pemesanan berhasil ditambahkan.');
    }

    public function edit(ProcessStep $processStep)
    {
        return view('admin.process_steps.edit', compact('processStep'));
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $validated = $request->validate([
            'step_number' => 'nullable|string|max:10',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['display_order'] = $validated['display_order'] ?? $processStep->display_order;

        $processStep->update($validated);

        return redirect()->route('admin.process-steps.index')->with('success', 'Langkah Cara Pemesanan berhasil diperbarui.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();
        return back()->with('success', 'Langkah Cara Pemesanan berhasil dihapus.');
    }
}
