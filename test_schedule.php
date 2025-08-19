<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

// Test day mapping
$dayMapping = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];

$today = $dayMapping[date('l')] ?? null;
echo "Today in Indonesian: " . $today . "\n";

// Test schedule query
echo "Testing schedule query...\n";
$schedules = \App\Models\Schedule::where('class_id', 16)
                                 ->where('is_active', true)
                                 ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                                 ->orderBy('start_time')
                                 ->get();

echo "Found " . $schedules->count() . " schedules\n";

foreach ($schedules as $schedule) {
    echo "- {$schedule->day} {$schedule->start_time}-{$schedule->end_time}: {$schedule->subject->name}\n";
}

echo "\nToday's schedules:\n";
$todaySchedules = $schedules->where('day', $today);
foreach ($todaySchedules as $schedule) {
    echo "- {$schedule->start_time}-{$schedule->end_time}: {$schedule->subject->name}\n";
}

echo "\nTest completed successfully!\n";
