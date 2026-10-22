<?php

return [
    'admin_path' => trim(env('ADMIN_PATH', 'studio-'.substr(hash('sha256', env('APP_KEY', 'local')), 0, 12)), '/'),
    'owner_name' => env('PORTFOLIO_OWNER', 'Muhamad Fadhillah Dinurahman'),
];
