<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // If resource is an array, extract data
        $record = is_array($this) ? $this : $this->resource;

        $date = $record['attendance_date'] ?? null;

        // Format Check-in/out times
        $checkIn  = !empty($record['check_in_at'])
            ? Carbon::parse($record['check_in_at'])->format('H:i:s')
            : null;

        $checkOut = !empty($record['check_out_at'])
            ? Carbon::parse($record['check_out_at'])->format('H:i:s')
            : null;

        // Worked hours (convert minutes to hr min)
        $workedHour = null;
        if (!empty($record['worked_hour'])) {
            $hours   = floor($record['worked_hour'] / 60);
            $minutes = $record['worked_hour'] % 60;
            $workedHour = ($hours > 0 ? $hours . 'hr ' : '') . ($minutes > 0 ? $minutes . 'm' : '0');
        }

        // Status
        $status = 'Absent';
        if (!empty($record['shift']) && strtolower($record['shift']) === 'weekend') {
            $status = 'Weekend';
        } elseif (!empty($record['attendance_status']) && $record['attendance_status'] == 1) {
            $status = 'Present';
        }

        return [
            'date'         => $date ? Carbon::parse($date)->format('d M Y (l)') : null,
            'check_in_at'  => $checkIn,
            'check_out_at' => $checkOut,
            'worked_hour'  => $workedHour,
            'status'       => $status,
            'shift'        => $record['shift'] ?? null,
        ];
    }
}
