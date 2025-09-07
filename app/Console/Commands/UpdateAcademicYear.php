<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolClass;

class UpdateAcademicYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'academic:update-year {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the academic year for all classes to the current year or specified year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');

        $this->info("Updating academic year to {$year} for all classes...");

        $count = SchoolClass::query()->update(['academic_year' => $year]);

        $this->info("Successfully updated {$count} classes to academic year {$year}");

        return Command::SUCCESS;
    }
}