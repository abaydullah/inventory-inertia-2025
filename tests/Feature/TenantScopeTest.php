<?php

use Illuminate\Support\Facades\File;

test('a model has a tenant id on the migration', function () {
    $now = now();
    $this->artisan('make:model Test -m');
    $filename = $now->year . '_' . $now->format('m') . '_' . $now->format('d') . '_' . $now->format('h') . $now->format('i') . $now->format('s') .
        '_create_tests_table.php';
    $this->assertFileExists(database_path('migrations/' . $filename));
    $this->assertStringContainsString('$table->unsignedBigInteger(\'tenant_id\')->index();', File::get(database_path('migrations/' . $filename)));
    File::delete(database_path('migrations/' . $filename));
    File::delete(app_path('Models/Test.php'));
});
