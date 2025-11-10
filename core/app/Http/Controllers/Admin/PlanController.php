<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Plan;

class PlanController extends Controller
{
    // Show all plans
    public function index()
    {
        $data['pageTitle'] = 'Plans';
        $data['navPlanActiveClass'] = 'active';
        $data['plans'] = Plan::latest()->paginate();
    
        return view('backend.plans.index')->with($data);
    }

    public function create()
    {
        $data['pageTitle'] = 'Create Plan';
        $data['countries'] = Country::active()->get();
        $data['navPlanActiveClass'] = 'active';
    
        return view('backend.plans.create')->with($data);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'plan_type' => 'required|in:single_entry,multiple_entry',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'country_ids' => 'required|array',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
            'is_recommended' => 'required|boolean',
        ]);

        $validated['country_ids'] = collect($request->country_ids)->map(fn($id) => (int) $id)->toArray();

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    // Show edit form
    public function edit(Plan $plan)
    {
        $data['pageTitle'] = 'Update Plan';
        $data['navPlanActiveClass'] = 'active';
        $data['countries'] = Country::active()->get();
        $data['plan'] = $plan;

        return view('backend.plans.edit')->with($data);
    }

    // Update plan
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'plan_type' => 'required|in:single_entry,multiple_entry',
            'heading' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'country_ids' => 'required|array',
            'status' => 'required|boolean',
            'is_recommended' => 'required|boolean',
        ]);
        $validated['country_ids'] = collect($request->country_ids)->map(fn($id) => (int) $id)->toArray();

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    // Delete plan
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
