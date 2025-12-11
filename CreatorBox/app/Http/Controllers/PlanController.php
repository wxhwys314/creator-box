<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('creator_id', Auth::id())->latest()->get();

        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'monthly_amount' => [
                'required',
                'integer',
                'min:100',
                //todo:
                function ($attribute, $value, $fail) {
                    $existingPlan = Plan::where('creator_id', Auth::id())
                        ->where('monthly_amount', $value)
                        ->exists();
                        
                    if ($existingPlan) {
                        $fail('You already have a plan with this amount. Please choose a different amount.');
                    }
                },
            ],
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('plans/covers', 'public');
        }

        $validated['creator_id'] = Auth::id();

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan created successfully!');
    }

    public function show(Plan $plan)
    {
        $plan->load('creator');

        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {        
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            // 'monthly_amount' => 'required|integer|min:100',   monthly_amount cannot be changed after creation
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($plan->cover) {
                Storage::disk('public')->delete($plan->cover);
            }
            $validated['cover'] = $request->file('cover')->store('plans/covers', 'public');
        }

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        $activeSponsorships = $plan->sponsorships()->where('status', 'active')->exists();

        if ($activeSponsorships) {
            $plan->update([
                'is_active' => false
            ]);

            return redirect()->route('plans.index')->with('warning', 'Plan disabled. It will be deleted once all active supporters end.');
        }

        if ($plan->cover) {
            Storage::disk('public')->delete($plan->cover);
        }

        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully!');
    }
}