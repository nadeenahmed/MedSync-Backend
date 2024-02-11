<?php

namespace App\Console\Commands;

use App\Models\Diagnoses;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicates from the diagnoses table';

    /**
     * Execute the console command.
     */

    // public function handle()
    // {
    //      $tableName = $this->argument('table');
 
    //      // Check if the table exists
    //      if (!Schema::hasTable($tableName)) {
    //          $this->error("Table '{$tableName}' does not exist.");
    //          return;
    //      }
 
    //      // Get all unique values from the specified table
    //      $uniqueValues = Model::resolveConnection()->table($tableName)->select('*')->distinct()->get();
 
    //      foreach ($uniqueValues as $uniqueValue) {
    //          // Find all records with the same values
    //          $duplicates = Model::resolveConnection()->table($tableName)
    //              ->where($uniqueValue->toArray())
    //              ->get();
 
    //          // Keep the first record and delete the rest
    //          foreach ($duplicates->skip(1) as $duplicate) {
    //              Model::resolveConnection()->table($tableName)->where($duplicate->toArray())->delete();
    //          }
    //      }
 
    //      $this->info("Duplicates removed successfully from table '{$tableName}'.");
    // }
    public function handle()
    {
        // Get all unique names from the diagnoses table
        $uniqueNames = Diagnoses::select('name')->distinct()->get();

        foreach ($uniqueNames as $uniqueName) {
            // Find all records with the same name
            $duplicates = Diagnoses::where('name', $uniqueName->name)->get();

            // Keep the first record and delete the rest
            foreach ($duplicates->skip(1) as $duplicate) {
                $duplicate->delete();
            }
        }

        $this->info('Duplicates removed successfully.');

        
    }
}
