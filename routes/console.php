<?php

use Illuminate\Support\Facades\Schedule;

// Move Files to Google Drive (Daily)
Schedule::command('move:files')->dailyAt('1:00 AM')->timezone('Asia/Manila');

// Compute Asset Depreciation (Yearly)
Schedule::command('compute:depreciation')->yearlyOn(1, 1, '00:00');

