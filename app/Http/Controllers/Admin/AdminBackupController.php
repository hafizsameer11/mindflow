<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class AdminBackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of backups
     */
    public function index()
    {
        $backups = $this->getBackups();
        
        // Calculate total size
        $totalSize = collect($backups)->sum('size');
        
        // Get backup statistics
        $stats = [
            'total_backups' => count($backups),
            'total_size' => $totalSize,
            'encrypted_backups' => collect($backups)->where('encrypted', true)->count(),
            'latest_backup' => collect($backups)->sortByDesc('created_at')->first(),
        ];

        return view('admin.backups.index', compact('backups', 'stats'));
    }

    /**
     * Create a new backup
     */
    public function store(Request $request)
    {
        $request->validate([
            'encrypt' => 'nullable|boolean',
        ]);

        try {
            $encrypt = $request->has('encrypt');
            
            // Run backup command
            Artisan::call('db:backup', [
                '--encrypt' => $encrypt,
            ]);

            $output = Artisan::output();
            
            return redirect()->route('admin.backups.index')
                ->withSuccess('Database backup created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($backupPath)) {
            abort(404, 'Backup file not found.');
        }

        return response()->download($backupPath, $filename);
    }

    /**
     * Restore database from backup
     */
    public function restore(Request $request, $filename)
    {
        $request->validate([
            'confirm' => 'required|accepted',
        ]);

        try {
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return redirect()->route('admin.backups.index')
                    ->withErrors(['error' => 'Backup file not found.']);
            }

            // Check if encrypted
            $backups = $this->getBackups();
            $backup = collect($backups)->firstWhere('filename', $filename);
            
            if ($backup && $backup['encrypted']) {
                // Decrypt first
                $decryptedPath = $this->decryptFile($backupPath);
                $restorePath = $decryptedPath;
            } else {
                $restorePath = $backupPath;
            }

            // Get database credentials
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Find mysql executable
            $mysql = $this->findMysql();
            
            if (!$mysql) {
                throw new \Exception('mysql client not found. Please ensure MySQL is installed and mysql is in your system PATH.');
            }

            // Restore database
            $command = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s %s < %s',
                escapeshellarg($mysql),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($restorePath)
            );

            // Suppress password warning
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command .= ' 2>nul';
            } else {
                $command .= ' 2>/dev/null';
            }

            exec($command, $output, $returnVar);

            // Clean up decrypted file if it was created
            if (isset($decryptedPath) && $decryptedPath !== $backupPath) {
                unlink($decryptedPath);
            }

            if ($returnVar !== 0) {
                throw new \Exception('Database restore failed. Please check MySQL credentials.');
            }

            return redirect()->route('admin.backups.index')
                ->withSuccess('Database restored successfully from backup: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->withErrors(['error' => 'Restore failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a backup
     */
    public function destroy($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (file_exists($backupPath)) {
                unlink($backupPath);
            }

            // Update metadata
            $this->removeBackupFromMetadata($filename);

            return redirect()->route('admin.backups.index')
                ->withSuccess('Backup deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all backups from metadata
     */
    private function getBackups()
    {
        $metadataFile = storage_path('app/backups/backups.json');
        
        if (!file_exists($metadataFile)) {
            return [];
        }

        $backups = json_decode(file_get_contents($metadataFile), true) ?: [];
        
        // Verify files still exist and update sizes
        $backups = collect($backups)->map(function($backup) {
            $filepath = $backup['filepath'] ?? storage_path('app/backups/' . $backup['filename']);
            
            if (file_exists($filepath)) {
                $backup['size'] = filesize($filepath);
                $backup['exists'] = true;
            } else {
                $backup['exists'] = false;
            }
            
            return $backup;
        })->filter(function($backup) {
            return $backup['exists'] ?? false;
        })->values()->all();

        // Update metadata file
        file_put_contents($metadataFile, json_encode($backups, JSON_PRETTY_PRINT));

        return $backups;
    }

    /**
     * Decrypt a backup file
     */
    private function decryptFile($encryptedFile)
    {
        $encryptionKey = config('app.backup_encryption_key', config('app.key'));
        
        // Remove 'base64:' prefix if present
        $key = str_replace('base64:', '', $encryptionKey);
        $key = base64_decode($key);
        
        // Use first 32 bytes for AES-256
        $key = substr(hash('sha256', $key), 0, 32);
        
        $cipher = 'aes-256-cbc';
        
        $encryptedData = file_get_contents($encryptedFile);
        
        // Extract IV (first 16 bytes)
        $iv = substr($encryptedData, 0, 16);
        $encrypted = substr($encryptedData, 16);
        
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        if ($decrypted === false) {
            throw new \Exception('Failed to decrypt backup file.');
        }
        
        // Save decrypted file temporarily
        $decryptedPath = storage_path('app/backups/temp_' . uniqid() . '.sql');
        file_put_contents($decryptedPath, $decrypted);
        
        return $decryptedPath;
    }

    /**
     * Find mysql executable
     */
    private function findMysql()
    {
        // Common paths for mysql client
        $paths = [
            'mysql', // If in PATH
            'C:\\xampp\\mysql\\bin\\mysql.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.xx\\bin\\mysql.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysql.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysql.exe',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
            '/opt/homebrew/bin/mysql',
        ];

        foreach ($paths as $path) {
            if (is_executable($path) || (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($path))) {
                return $path;
            }
        }

        // Try to find in PATH
        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        exec("$which mysql 2>&1", $output, $returnVar);
        
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    /**
     * Remove backup from metadata
     */
    private function removeBackupFromMetadata($filename)
    {
        $metadataFile = storage_path('app/backups/backups.json');
        
        if (!file_exists($metadataFile)) {
            return;
        }

        $backups = json_decode(file_get_contents($metadataFile), true) ?: [];
        $backups = collect($backups)->reject(function($backup) use ($filename) {
            return $backup['filename'] === $filename;
        })->values()->all();

        file_put_contents($metadataFile, json_encode($backups, JSON_PRETTY_PRINT));
    }
}
