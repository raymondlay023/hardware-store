<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:backup {--compress : Compress the backup as .zip}';

    /**
     * The console command description.
     */
    protected $description = 'Backup the database to storage/backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        // Create backups directory if it doesn't exist
        $backupPath = storage_path('backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Generate filename with timestamp
        $filename = 'backup-' . now()->format('Y-m-d_His') . '.sql';
        $fullPath = $backupPath . '/' . $filename;

        // Get database configuration
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        // Build mysqldump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%d %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            $port,
            escapeshellarg($database),
            escapeshellarg($fullPath)
        );

        try {
            // Execute mysqldump
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Backup failed! mysqldump returned error code: ' . $returnCode);
                return 1;
            }

            // Check if file was created
            if (!File::exists($fullPath)) {
                $this->error('Backup file was not created!');
                return 1;
            }

            $fileSize = number_format(File::size($fullPath) / 1024 / 1024, 2);
            $this->info("✓ Backup created: {$filename} ({$fileSize} MB)");

            // Compress if requested
            if ($this->option('compress')) {
                $zipPath = $backupPath . '/' . str_replace('.sql', '.zip', $filename);
                $zip = new \ZipArchive();
                
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    $zip->addFile($fullPath, $filename);
                    $zip->close();
                    
                    // Delete original SQL file
                    File::delete($fullPath);
                    
                    $zipSize = number_format(File::size($zipPath) / 1024 / 1024, 2);
                    $this->info("✓ Backup compressed: " . basename($zipPath) . " ({$zipSize} MB)");
                } else {
                    $this->warn('Could not create zip file, keeping SQL backup.');
                }
            }

            // Clean up old backups (keep only last 30 days)
            $this->cleanupOldBackups($backupPath);

            $this->info('✓ Database backup completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clean up backups older than 30 days
     */
    protected function cleanupOldBackups($path)
    {
        $files = File::files($path);
        $cutoffDate = now()->subDays(30);
        $deleted = 0;

        foreach ($files as $file) {
            $fileTime = File::lastModified($file);
            if ($fileTime < $cutoffDate->timestamp) {
                File::delete($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("✓ Cleaned up {$deleted} old backup(s)");
        }
    }
}
