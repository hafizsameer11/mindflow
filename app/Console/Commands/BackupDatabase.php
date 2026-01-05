<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--encrypt : Encrypt the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an encrypted database backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $filename = "backup_{$database}_{$timestamp}.sql";
            $filepath = $backupDir . '/' . $filename;

            // Create mysqldump command
            // For Windows, use full path or ensure mysqldump is in PATH
            $mysqldump = $this->findMysqldump();
            
            if (!$mysqldump) {
                throw new \Exception('mysqldump not found. Please ensure MySQL is installed and mysqldump is in your system PATH.');
            }

            $command = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($mysqldump),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute backup (suppress password warning)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows: redirect stderr to null
                $command .= ' 2>nul';
            } else {
                // Unix/Linux: redirect stderr to /dev/null
                $command .= ' 2>/dev/null';
            }
            
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Database backup failed. Please check MySQL credentials and mysqldump availability.');
            }

            if (!file_exists($filepath) || filesize($filepath) === 0) {
                throw new \Exception('Backup file was not created or is empty.');
            }

            $this->info("Backup created: {$filename}");

            // Encrypt if requested
            if ($this->option('encrypt')) {
                $encryptedFilename = $filepath . '.encrypted';
                $this->encryptFile($filepath, $encryptedFilename);
                
                // Remove unencrypted file
                unlink($filepath);
                $filename = basename($encryptedFilename);
                $filepath = $encryptedFilename;
                
                $this->info("Backup encrypted: {$filename}");
            }

            // Store backup metadata
            $this->storeBackupMetadata($filename, $filepath, $this->option('encrypt'));

            $this->info('Database backup completed successfully!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Encrypt a file using OpenSSL
     */
    private function encryptFile($inputFile, $outputFile)
    {
        $encryptionKey = config('app.backup_encryption_key', config('app.key'));
        
        // Remove 'base64:' prefix if present
        $key = str_replace('base64:', '', $encryptionKey);
        $key = base64_decode($key);
        
        // Use first 32 bytes for AES-256
        $key = substr(hash('sha256', $key), 0, 32);
        
        $iv = openssl_random_pseudo_bytes(16);
        $cipher = 'aes-256-cbc';
        
        $data = file_get_contents($inputFile);
        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        // Prepend IV to encrypted data
        $encryptedData = $iv . $encrypted;
        
        file_put_contents($outputFile, $encryptedData);
    }

    /**
     * Find mysqldump executable
     */
    private function findMysqldump()
    {
        // Common paths for mysqldump
        $paths = [
            'mysqldump', // If in PATH
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.xx\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump',
        ];

        foreach ($paths as $path) {
            if (is_executable($path) || (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($path))) {
                return $path;
            }
        }

        // Try to find in PATH
        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        exec("$which mysqldump 2>&1", $output, $returnVar);
        
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    /**
     * Store backup metadata in database
     */
    private function storeBackupMetadata($filename, $filepath, $encrypted)
    {
        // Store in a JSON file for simplicity (or use database table)
        $metadataFile = storage_path('app/backups/backups.json');
        $backups = [];
        
        if (file_exists($metadataFile)) {
            $backups = json_decode(file_get_contents($metadataFile), true) ?: [];
        }
        
        $backups[] = [
            'filename' => $filename,
            'filepath' => $filepath,
            'size' => filesize($filepath),
            'encrypted' => $encrypted,
            'created_at' => Carbon::now()->toDateTimeString(),
            'database' => config('database.connections.mysql.database'),
        ];
        
        file_put_contents($metadataFile, json_encode($backups, JSON_PRETTY_PRINT));
    }
}
