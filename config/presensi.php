<?php

return [
    // office_latitude, office_longitude, and radius_meters are now managed in the database via KonfigurasiLokasi model.
    // The .env variables OFFICE_LATITUDE, OFFICE_LONGITUDE, and PRESENCE_RADIUS_METERS are no longer directly used by this config file
    // for these settings but might be used for initial seeding or as a fallback if the database record is missing in some contexts (though current implementation relies on DB record).

    'default_start_time' => env('DEFAULT_START_TIME', '07:00:00'), // Default office start time
]; 