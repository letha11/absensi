<?php

return [
    'office_latitude' => env('OFFICE_LATITUDE', -6.331777974700566),
    'office_longitude' => env('OFFICE_LONGITUDE', 106.71675909777613),
    // Consider increasing PRESENCE_RADIUS_METERS. 
    // A 10-20m radius is very strict for GPS, which can have accuracies of 10-50m.
    // A radius of 50-100m might be more practical to avoid users being incorrectly marked as outside the zone due to GPS inaccuracies.
    'radius_meters' => env('PRESENCE_RADIUS_METERS', 20000),
    'default_start_time' => env('DEFAULT_START_TIME', '07:00:00'), // Default office start time
]; 