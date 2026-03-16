<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

$results = [
    'table_exists' => Schema::hasTable('roles'),
    'columns' => Schema::getColumnListing('roles'),
    'first_role' => Role::first() ? Role::first()->toArray() : 'None',
];

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
