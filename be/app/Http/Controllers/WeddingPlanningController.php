<?php

namespace App\Http\Controllers;

use App\Models\WeddingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WeddingPlanningController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->hasRole('user'), 403);

        $maxAmount = 9999999999999.99;
        $categoryKeys = implode(',', array_keys(WeddingPlan::BUDGET_CATEGORIES));
        $rules = [
            'event_date' => ['nullable', 'date'],
            'total_budget' => ['required', 'numeric', 'min:0', 'max:'.$maxAmount],
            'budget_breakdown' => ['required', 'array:'.$categoryKeys],
        ];

        foreach (array_keys(WeddingPlan::BUDGET_CATEGORIES) as $category) {
            $rules['budget_breakdown.'.$category] = ['required', 'numeric', 'min:0', 'max:'.$maxAmount];
        }

        $validated = $request->validate($rules);
        $breakdown = [];

        foreach (WeddingPlan::BUDGET_CATEGORIES as $category => $label) {
            $breakdown[$category] = $validated['budget_breakdown'][$category];
        }

        WeddingPlan::updateOrCreate(
            ['user_uuid' => $request->user()->uuid],
            [
                'event_date' => $validated['event_date'] ?? null,
                'total_budget' => $validated['total_budget'],
                'budget_breakdown' => $breakdown,
            ]
        );

        return back()->with('success', 'Rencana pernikahan berhasil disimpan.');
    }

    public function updateTask(Request $request, string $task): RedirectResponse
    {
        abort_unless($request->user()->hasRole('user'), 403);
        abort_unless(array_key_exists($task, WeddingPlan::TASKS), 404);

        $validated = $request->validate([
            'completed' => ['required', 'boolean'],
        ]);

        $plan = WeddingPlan::firstOrNew(['user_uuid' => $request->user()->uuid]);
        $completedTasks = $plan->completed_tasks ?? [];

        if ($validated['completed']) {
            $completedTasks[] = $task;
        } else {
            $completedTasks = array_values(array_filter(
                $completedTasks,
                fn (string $completedTask): bool => $completedTask !== $task
            ));
        }

        $plan->completed_tasks = array_values(array_unique($completedTasks));
        $plan->save();

        return back()->with('success', 'Checklist berhasil diperbarui.');
    }
}
