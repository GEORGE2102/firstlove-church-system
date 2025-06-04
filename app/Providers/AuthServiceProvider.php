<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Fellowship;
use App\Models\Attendance;
use App\Models\Offering;
use App\Models\Announcement;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Admin gate - can do everything
        Gate::define('admin-access', function (User $user) {
            return $user->isAdmin();
        });

        // Pastor gates
        Gate::define('pastor-access', function (User $user) {
            return $user->isPastor() || $user->isAdmin();
        });

        Gate::define('view-fellowship-reports', function (User $user, Fellowship $fellowship) {
            return $user->isAdmin() || 
                   ($user->isPastor() && $fellowship->pastor_id === $user->id) ||
                   ($user->isLeader() && $fellowship->leader_id === $user->id);
        });

        // Fellowship Leader gates
        Gate::define('leader-access', function (User $user) {
            return $user->isLeader() || $user->isPastor() || $user->isAdmin();
        });

        Gate::define('manage-fellowship', function (User $user, Fellowship $fellowship) {
            return $user->isAdmin() || 
                   ($user->isLeader() && $fellowship->leader_id === $user->id);
        });

        Gate::define('record-attendance', function (User $user, Fellowship $fellowship) {
            return $user->isAdmin() || 
                   ($user->isLeader() && $fellowship->leader_id === $user->id);
        });

        Gate::define('submit-offering', function (User $user, Fellowship $fellowship) {
            return $user->isAdmin() || 
                   ($user->isLeader() && $fellowship->leader_id === $user->id);
        });

        // Treasurer gates
        Gate::define('treasurer-access', function (User $user) {
            return $user->isTreasurer() || $user->isAdmin();
        });

        Gate::define('confirm-offering', function (User $user) {
            return $user->isTreasurer() || $user->isAdmin();
        });

        // Announcement gates
        Gate::define('create-announcements', function (User $user) {
            return $user->hasAnyRole(['admin', 'pastor', 'leader']);
        });

        Gate::define('manage-announcement', function (User $user, Announcement $announcement) {
            return $user->isAdmin() || $announcement->created_by === $user->id;
        });

        // User management gates
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Reports access
        Gate::define('view-reports', function (User $user) {
            return $user->hasAnyRole(['admin', 'pastor', 'treasurer']);
        });
    }
} 