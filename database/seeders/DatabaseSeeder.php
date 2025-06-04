<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fellowship;
use App\Models\Attendance;
use App\Models\Offering;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@firstlove.church',
            'password' => Hash::make('password'),
            'phone' => '+260-971-123456',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create pastor
        $pastor = User::create([
            'name' => 'Pastor John Mwale',
            'email' => 'pastor@firstlove.church',
            'password' => Hash::make('password'),
            'phone' => '+260-971-234567',
            'role' => 'pastor',
            'is_active' => true,
        ]);

        // Create treasurer
        $treasurer = User::create([
            'name' => 'Mary Banda',
            'email' => 'treasurer@firstlove.church',
            'password' => Hash::make('password'),
            'phone' => '+260-971-345678',
            'role' => 'treasurer',
            'is_active' => true,
        ]);

        // Create fellowships
        $fellowships = [
            [
                'name' => 'UNILUS Fellowship',
                'description' => 'Fellowship for University of Lusaka students and staff',
                'location' => 'UNILUS Campus, Room 101',
                'meeting_day' => 3, // Wednesday
                'meeting_time' => '18:00:00',
                'pastor_id' => $pastor->id,
                'is_active' => true,
            ],
            [
                'name' => 'CBU Fellowship',
                'description' => 'Fellowship for Copperbelt University students',
                'location' => 'CBU Campus, Block A',
                'meeting_day' => 5, // Friday
                'meeting_time' => '17:30:00',
                'pastor_id' => $pastor->id,
                'is_active' => true,
            ],
            [
                'name' => 'Youth Fellowship',
                'description' => 'Fellowship for young professionals and youth',
                'location' => 'Foxdale Community Center',
                'meeting_day' => 6, // Saturday
                'meeting_time' => '16:00:00',
                'pastor_id' => $pastor->id,
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Fellowship',
                'description' => 'Fellowship for women in the community',
                'location' => 'Church Main Hall',
                'meeting_day' => 2, // Tuesday
                'meeting_time' => '14:00:00',
                'pastor_id' => $pastor->id,
                'is_active' => true,
            ]
        ];

        $createdFellowships = [];
        foreach ($fellowships as $fellowshipData) {
            $createdFellowships[] = Fellowship::create($fellowshipData);
        }

        // Create fellowship leaders
        $leaders = [
            [
                'name' => 'James Phiri',
                'email' => 'james@firstlove.church',
                'password' => Hash::make('password'),
                'phone' => '+260-971-456789',
                'role' => 'leader',
                'fellowship_id' => $createdFellowships[0]->id, // UNILUS
                'is_active' => true,
            ],
            [
                'name' => 'Grace Mulenga',
                'email' => 'grace@firstlove.church',
                'password' => Hash::make('password'),
                'phone' => '+260-971-567890',
                'role' => 'leader',
                'fellowship_id' => $createdFellowships[1]->id, // CBU
                'is_active' => true,
            ],
            [
                'name' => 'David Chanda',
                'email' => 'david@firstlove.church',
                'password' => Hash::make('password'),
                'phone' => '+260-971-678901',
                'role' => 'leader',
                'fellowship_id' => $createdFellowships[2]->id, // Youth
                'is_active' => true,
            ],
            [
                'name' => 'Ruth Kapata',
                'email' => 'ruth@firstlove.church',
                'password' => Hash::make('password'),
                'phone' => '+260-971-789012',
                'role' => 'leader',
                'fellowship_id' => $createdFellowships[3]->id, // Women's
                'is_active' => true,
            ]
        ];

        $createdLeaders = [];
        foreach ($leaders as $leaderData) {
            $createdLeaders[] = User::create($leaderData);
        }

        // Update fellowships with leaders
        for ($i = 0; $i < count($createdFellowships); $i++) {
            $createdFellowships[$i]->update(['leader_id' => $createdLeaders[$i]->id]);
        }

        // Create members
        $members = [
            ['name' => 'Peter Mwanza', 'email' => 'peter@firstlove.church', 'fellowship_id' => $createdFellowships[0]->id],
            ['name' => 'Sarah Tembo', 'email' => 'sarah@firstlove.church', 'fellowship_id' => $createdFellowships[0]->id],
            ['name' => 'Michael Banda', 'email' => 'michael@firstlove.church', 'fellowship_id' => $createdFellowships[1]->id],
            ['name' => 'Esther Nkandu', 'email' => 'esther@firstlove.church', 'fellowship_id' => $createdFellowships[1]->id],
            ['name' => 'Joshua Simukoko', 'email' => 'joshua@firstlove.church', 'fellowship_id' => $createdFellowships[2]->id],
            ['name' => 'Mercy Kabwe', 'email' => 'mercy@firstlove.church', 'fellowship_id' => $createdFellowships[2]->id],
            ['name' => 'Elizabeth Sakala', 'email' => 'elizabeth@firstlove.church', 'fellowship_id' => $createdFellowships[3]->id],
            ['name' => 'Hannah Mubanga', 'email' => 'hannah@firstlove.church', 'fellowship_id' => $createdFellowships[3]->id],
        ];

        foreach ($members as $memberData) {
            User::create([
                'name' => $memberData['name'],
                'email' => $memberData['email'],
                'password' => Hash::make('password'),
                'phone' => '+260-971-' . rand(100000, 999999),
                'role' => 'member',
                'fellowship_id' => $memberData['fellowship_id'],
                'is_active' => true,
            ]);
        }

        // Create sample attendance records for the past 8 weeks
        foreach ($createdFellowships as $fellowship) {
            for ($week = 8; $week >= 1; $week--) {
                $attendanceDate = Carbon::now()->subWeeks($week);
                
                // Adjust to the fellowship meeting day
                $attendanceDate = $attendanceDate->setDayOfWeek($fellowship->meeting_day - 1);
                
                $memberCount = $fellowship->members()->count();
                $attendanceCount = rand(ceil($memberCount * 0.6), $memberCount); // 60-100% attendance
                
                Attendance::create([
                    'fellowship_id' => $fellowship->id,
                    'attendance_date' => $attendanceDate->format('Y-m-d'),
                    'attendance_count' => $attendanceCount,
                    'bible_study_topic' => $this->getRandomBibleTopic(),
                    'notes' => 'Great fellowship session with active participation.',
                    'recorded_by' => $fellowship->leader_id,
                ]);
            }
        }

        // Create sample offering records
        foreach ($createdFellowships as $fellowship) {
            for ($week = 8; $week >= 1; $week--) {
                $offeringDate = Carbon::now()->subWeeks($week);
                $offeringDate = $offeringDate->setDayOfWeek($fellowship->meeting_day - 1);
                
                $amount = rand(50, 500); // K50 to K500
                $status = $week <= 2 ? 'pending' : 'confirmed'; // Recent ones pending
                
                $offering = Offering::create([
                    'fellowship_id' => $fellowship->id,
                    'amount' => $amount,
                    'offering_date' => $offeringDate->format('Y-m-d'),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'transaction_reference' => $this->generateTransactionRef(),
                    'notes' => 'Weekly fellowship offering',
                    'submitted_by' => $fellowship->leader_id,
                    'status' => $status,
                ]);

                if ($status === 'confirmed') {
                    $offering->update([
                        'confirmed_by' => $treasurer->id,
                        'confirmed_at' => $offeringDate->addDays(2),
                    ]);
                }
            }
        }

        // Create sample announcements
        $announcements = [
            [
                'title' => 'Welcome to First Love Church CMS',
                'content' => 'We are excited to launch our new Church Management System! This platform will help us better manage our fellowships, track attendance, and handle offerings more efficiently.',
                'target_audience' => 'all',
                'priority' => 'high',
                'is_active' => true,
                'published_at' => Carbon::now()->subDays(1),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Monthly Fellowship Leaders Meeting',
                'content' => 'All fellowship leaders are invited to attend the monthly meeting this Saturday at 10:00 AM in the main church hall. We will discuss upcoming events and ministry plans.',
                'target_audience' => 'leaders',
                'priority' => 'normal',
                'is_active' => true,
                'published_at' => Carbon::now()->subDays(5),
                'created_by' => $pastor->id,
            ],
            [
                'title' => 'Financial Report Submission Deadline',
                'content' => 'Reminder: All fellowship leaders must submit their monthly financial reports by the 25th of each month. Please ensure all offering records are accurately recorded.',
                'target_audience' => 'leaders',
                'priority' => 'urgent',
                'is_active' => true,
                'published_at' => Carbon::now()->subDays(3),
                'created_by' => $treasurer->id,
            ],
            [
                'title' => 'Easter Celebration Planning',
                'content' => 'We are beginning preparations for our Easter celebration. Each fellowship is encouraged to participate in the special program. More details will follow soon.',
                'target_audience' => 'all',
                'priority' => 'normal',
                'is_active' => true,
                'published_at' => Carbon::now()->subDays(7),
                'created_by' => $pastor->id,
            ],
        ];

        foreach ($announcements as $announcementData) {
            Announcement::create($announcementData);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('🔑 Login credentials:');
        $this->command->info('   Admin: admin@firstlove.church / password');
        $this->command->info('   Pastor: pastor@firstlove.church / password');
        $this->command->info('   Treasurer: treasurer@firstlove.church / password');
        $this->command->info('   Leaders: james@firstlove.church / password (and others)');
        $this->command->info('   Members: peter@firstlove.church / password (and others)');
    }

    /**
     * Get a random Bible study topic
     */
    private function getRandomBibleTopic()
    {
        $topics = [
            'The Love of Christ',
            'Walking in Faith',
            'Prayer and Fasting',
            'Understanding Grace',
            'Christian Fellowship',
            'Serving Others',
            'Biblical Leadership',
            'Spiritual Growth',
            'God\'s Promises',
            'Living in Hope',
            'Forgiveness and Mercy',
            'The Great Commission',
        ];

        return $topics[array_rand($topics)];
    }

    /**
     * Get a random payment method
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['cash', 'mobile_money', 'bank_transfer'];
        return $methods[array_rand($methods)];
    }

    /**
     * Generate a random transaction reference
     */
    private function generateTransactionRef()
    {
        return 'TXN' . strtoupper(substr(md5(rand()), 0, 8));
    }
} 