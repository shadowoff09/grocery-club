<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the application can connect to the database.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            DB::connection()->getPdo();
            $this->info("✅ Database connection successful.");
        } catch (\Exception $e) {
            $this->error("❌ Could not connect to the database. Error: " . $e->getMessage());
        }
    }

}
