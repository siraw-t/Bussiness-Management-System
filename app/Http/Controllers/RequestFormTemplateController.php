<?php

namespace App\Http\Controllers;

use App\Models\RequestFormTemplate;
use Illuminate\Http\Request;

class RequestFormTemplateController extends Controller
{
    public function index()
    {
        $templates = RequestFormTemplate::all();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'fields' => 'required|string', // Adjust validation as needed
        ]);

        RequestFormTemplate::create([
            'form_name' => $request->form_name,
            'fields' => $request->fields,
            'created_by' => auth()->id(),
            'company_id' => $request->company_id, // Ensure company_id is passed in the request
        ]);

        return redirect()->route('templates.index')->with('success', 'Template created successfully.');
    }

    public function show(RequestFormTemplate $template)
    {
        $template = RequestFormTemplate::findOrFail($id);
        $fields = json_decode($template->fields); // Decode JSON
        return view('templates.show', compact('template', 'fields'));
    }

    public function edit(RequestFormTemplate $template)
    {
        
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, RequestFormTemplate $template)
    {
        $request->validate([
            'form_name' => 'required|string|max:255',
            'fields' => 'required|string',
        ]);

        $template->update([
            'form_name' => $request->form_name,
            'fields' => $request->fields,
        ]);

        return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy(RequestFormTemplate $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Template deleted successfully.');
    }

   
}