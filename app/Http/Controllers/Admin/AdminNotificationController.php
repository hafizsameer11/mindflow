<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AdminAnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Announcement::with('creator');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by target audience
        if ($request->filled('target_audience')) {
            $query->where('target_audience', $request->target_audience);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->latest()->get();

        // Statistics
        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'total_sent' => Announcement::sum('sent_count'),
            'announcements' => Announcement::where('type', 'announcement')->count(),
            'reminders' => Announcement::where('type', 'reminder')->count(),
            'policy_updates' => Announcement::where('type', 'policy_update')->count(),
        ];

        return view('admin.notifications.index', compact('announcements', 'stats'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'type' => 'required|in:announcement,reminder,policy_update,system_alert',
            'target_audience' => 'required|in:all,patients,psychologists,admins',
            'priority' => 'required|in:low,normal,high,urgent',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after:scheduled_at',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target_audience' => $request->target_audience,
            'priority' => $request->priority,
            'is_active' => $request->has('send_immediately') || !$request->filled('scheduled_at'),
            'scheduled_at' => $request->scheduled_at,
            'expires_at' => $request->expires_at,
            'created_by' => Auth::id(),
        ]);

        // Send immediately if requested
        if ($request->has('send_immediately') && $request->send_immediately == '1') {
            $sentCount = $this->sendAnnouncement($announcement);
            return redirect()->route('admin.notifications.index')
                ->withSuccess("Announcement created and sent successfully to {$sentCount} users.");
        }

        return redirect()->route('admin.notifications.index')
            ->withSuccess('Announcement created successfully. Click "Send Now" to send it to users.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        return view('admin.notifications.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.notifications.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'type' => 'required|in:announcement,reminder,policy_update,system_alert',
            'target_audience' => 'required|in:all,patients,psychologists,admins',
            'priority' => 'required|in:low,normal,high,urgent',
            'scheduled_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:scheduled_at',
        ]);

        $announcement->update([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target_audience' => $request->target_audience,
            'priority' => $request->priority,
            'scheduled_at' => $request->scheduled_at,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.notifications.index')->withSuccess('Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.notifications.index')->withSuccess('Announcement deleted successfully.');
    }

    public function send(Announcement $announcement)
    {
        $sentCount = $this->sendAnnouncement($announcement);
        return redirect()->back()->withSuccess("Announcement sent successfully to {$sentCount} users.");
    }

    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return redirect()->back()->withSuccess('Announcement status updated.');
    }

    private function sendAnnouncement(Announcement $announcement)
    {
        $query = User::query();

        // Only send to active users
        $query->where('status', 'active');

        // Filter by target audience
        switch ($announcement->target_audience) {
            case 'patients':
                $query->where('role', 'patient');
                break;
            case 'psychologists':
                $query->where('role', 'psychologist');
                break;
            case 'admins':
                $query->where('role', 'admin');
                break;
            case 'all':
            default:
                // Send to all active users
                break;
        }

        $users = $query->get();
        $sentCount = 0;

        \Log::info('Sending announcement', [
            'announcement_id' => $announcement->id,
            'target_audience' => $announcement->target_audience,
            'users_count' => $users->count(),
        ]);

        foreach ($users as $user) {
            try {
                // Send notification (will store in database even if email fails)
                $user->notify(new AdminAnnouncementNotification($announcement));
                $sentCount++;
                
                \Log::info('Notification sent successfully', [
                    'announcement_id' => $announcement->id,
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send announcement notification', [
                    'announcement_id' => $announcement->id,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $announcement->update([
            'sent_count' => $announcement->sent_count + $sentCount,
            'is_active' => true,
        ]);

        return $sentCount;
    }
}
