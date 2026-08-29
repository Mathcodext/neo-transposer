<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportFrenchSongsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-french-songs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import French songbook (book id 6) and songs/chords into MySQL database from french_song_data.sql';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = base_path('french_song_data.sql');

        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("Importing French songs into database...");

        $content = File::get($filePath);
        $lines = explode("\n", $content);

        $statements = [];
        $currentStatement = '';

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed) || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                continue;
            }

            $currentStatement .= ' ' . $trimmed;

            if (str_ends_with($trimmed, ';')) {
                $statements[] = trim($currentStatement);
                $currentStatement = '';
            }
        }

        $count = 0;
        DB::beginTransaction();
        try {
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    DB::statement($stmt);
                    $count++;
                }
            }
            DB::commit();
            $this->info("Successfully executed {$count} SQL statements! French book (id_book=6) is now in the database.");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Failed to import SQL statement: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
