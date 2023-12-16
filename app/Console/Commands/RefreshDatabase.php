<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the database excluding certain tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $excludedTables = ['drugs', 'diagnoses','lab_tests','medical_facilities','specialities','symptoms','treatments','vaccines'];
    }
}
