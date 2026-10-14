<?php

namespace App\Services;

use App\Models\Branch;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class BranchOrderHours
{
    public function status(Branch $branch, ?CarbonImmutable $at = null): array
    {
        $now = ($at ?? CarbonImmutable::now('Asia/Dhaka'))->setTimezone('Asia/Dhaka');
        $day = $now->toDateString();
        $settings = DB::table('branch_order_settings')->where('branch_id', $branch->id)->first();
        $validBranch = $branch->status === 'active' && $branch->accepting_orders && ! $branch->trashed();

        if (! $settings || ! $settings->start_time || ! $settings->end_time) {
            return ['can_order' => false, 'configured' => false, 'start' => null, 'end' => null,
                'message' => 'Online ordering is not configured for this branch. Please try another branch.'];
        }

        $today = $this->timesForDay($branch->id, $day, $settings);
        $yesterday = $this->timesForDay($branch->id, $now->subDay()->toDateString(), $settings);
        $hhmm = $now->format('H:i');
        $start = $today['start'];
        $end = $today['end'];

        // A time window ending earlier than it begins runs across midnight.
        $canOrderToday = $start !== $end && ($start < $end
            ? ($hhmm >= $start && $hhmm < $end)
            : ($hhmm >= $start));
        $canOrderYesterday = $yesterday['start'] > $yesterday['end'] && $hhmm < $yesterday['end'];
        $canOrder = $validBranch && ($canOrderToday || $canOrderYesterday);
        $displayStart = $this->clock($start);
        $displayEnd = $this->clock($end);

        return ['can_order' => $canOrder, 'configured' => true, 'start' => $start, 'end' => $end,
            'message' => $validBranch
                ? ($canOrder ? 'Ordering is currently available.' : "Ordering available from {$displayStart} to {$displayEnd} for {$branch->name}.")
                : 'This branch is not currently accepting online orders.'];
    }

    private function timesForDay(int $branchId, string $date, object $settings): array
    {
        $override = DB::table('branch_order_time_overrides')
            ->where('branch_id', $branchId)->where('order_date', $date)->first();
        return [
            'start' => substr((string) ($override->start_time ?? $settings->start_time), 0, 5),
            'end' => substr((string) ($override->end_time ?? $settings->end_time), 0, 5),
        ];
    }

    private function clock(string $time): string
    {
        return CarbonImmutable::createFromFormat('!H:i', $time)->format('g:i A');
    }
}
