<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Forum;
use App\Models\GRCar;
use App\Models\Leaderboard;
use App\Models\News;
use App\Models\OAuthSetting;
use App\Models\User;
use App\Models\Video;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $this->seedPlaceholderImages();

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gtcup26.local'],
            [
                'name' => 'AMTC Admin',
                'phone' => '0500000000',
                'type' => 1,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        $drivers = collect([
            [
                'email' => 'driver1@gtcup26.local',
                'name' => 'Khaled Al-Rashid',
                'phone' => '0500000001',
                'google_id' => null,
                'profile' => [
                    'birthdate' => '1998-05-10',
                    'whatsapp' => '0500000001',
                    'gender' => 'male',
                    'city' => "Sana'a",
                    'skill_level' => 'intermediate',
                    'has_ps5' => true,
                    'primary_platform' => 'ps5',
                    'regular_games' => 'Gran Turismo 7, EA FC 25',
                    'weekly_hours' => '5_to_10',
                    'favorite_games' => ['gt7', 'fifa', 'cod'],
                    'gt7_ranking' => 'top3',
                    'toyota_gr_knowledge' => 'knowledgeable',
                    'favorite_car' => 'Toyota GR Supra for stable cornering balance.',
                    'participated_before' => true,
                    'wants_training' => true,
                    'join_whatsapp' => true,
                    'heard_about' => 'social_media',
                    'motivation' => ['challenge', 'prizes'],
                    'preferred_time' => 'evening',
                    'suggestions' => 'Would love a wet-condition qualifier round.',
                ],
            ],
            [
                'email' => 'driver2@gtcup26.local',
                'name' => 'Noor Al-Qahtani',
                'phone' => '0500000002',
                'google_id' => null,
                'profile' => [
                    'birthdate' => '2000-09-15',
                    'whatsapp' => '0500000002',
                    'gender' => 'female',
                    'city' => 'Aden',
                    'skill_level' => 'expert',
                    'has_ps5' => true,
                    'primary_platform' => 'ps5',
                    'regular_games' => 'Gran Turismo 7',
                    'weekly_hours' => 'more_than_10',
                    'favorite_games' => ['gt7', 'apex'],
                    'gt7_ranking' => 'top1',
                    'toyota_gr_knowledge' => 'expert',
                    'favorite_car' => 'Toyota GR Yaris because of agile handling.',
                    'participated_before' => true,
                    'wants_training' => false,
                    'join_whatsapp' => true,
                    'heard_about' => 'friends',
                    'motivation' => ['challenge', 'toyota_experience'],
                    'preferred_time' => 'weekend',
                    'suggestions' => 'Share sector-by-sector telemetry after races.',
                ],
            ],
            [
                'email' => 'driver3@gtcup26.local',
                'name' => 'Faisal Al-Harbi',
                'phone' => '0500000003',
                'google_id' => 'google-demo-driver3',
                'profile' => [
                    'birthdate' => '1996-01-23',
                    'whatsapp' => '0500000003',
                    'gender' => 'male',
                    'city' => 'Mukalla',
                    'skill_level' => 'beginner',
                    'has_ps5' => false,
                    'primary_platform' => 'pc',
                    'regular_games' => 'Gran Turismo 7, Forza Motorsport',
                    'weekly_hours' => 'less_than_5',
                    'favorite_games' => ['gt7', 'minecraft'],
                    'gt7_ranking' => 'lower',
                    'toyota_gr_knowledge' => 'heard',
                    'favorite_car' => 'Toyota GR86 as a learning platform.',
                    'participated_before' => false,
                    'wants_training' => true,
                    'join_whatsapp' => true,
                    'heard_about' => 'websites',
                    'motivation' => ['skill_development'],
                    'preferred_time' => 'flexible',
                    'suggestions' => 'Beginner onboarding race clinic would help.',
                ],
            ],
            [
                'email' => 'driver4@gtcup26.local',
                'name' => 'Sara Al-Mutairi',
                'phone' => '0500000004',
                'google_id' => null,
                'profile' => [
                    'birthdate' => '1997-11-04',
                    'whatsapp' => '0500000004',
                    'gender' => 'female',
                    'city' => 'Hodeidah',
                    'skill_level' => 'intermediate',
                    'has_ps5' => true,
                    'primary_platform' => 'ps4',
                    'regular_games' => 'Gran Turismo 7, Fortnite',
                    'weekly_hours' => '5_to_10',
                    'favorite_games' => ['gt7', 'fortnite', 'gta'],
                    'gt7_ranking' => 'top5',
                    'toyota_gr_knowledge' => 'knowledgeable',
                    'favorite_car' => 'Toyota GR Corolla for all-around performance.',
                    'participated_before' => false,
                    'wants_training' => true,
                    'join_whatsapp' => false,
                    'heard_about' => 'gaming_cafes',
                    'motivation' => ['challenge', 'love_cars'],
                    'preferred_time' => 'afternoon',
                    'suggestions' => 'Please add women-only warm-up sessions.',
                ],
            ],
            [
                'email' => 'driver5@gtcup26.local',
                'name' => 'Yousef Al-Shehri',
                'phone' => '0500000005',
                'google_id' => null,
                'profile' => [
                    'birthdate' => '1994-03-30',
                    'whatsapp' => '0500000005',
                    'gender' => 'male',
                    'city' => "Sana'a",
                    'skill_level' => 'expert',
                    'has_ps5' => true,
                    'primary_platform' => 'ps5',
                    'regular_games' => 'Gran Turismo 7, Call of Duty',
                    'weekly_hours' => 'more_than_10',
                    'favorite_games' => ['gt7', 'cod', 'pes'],
                    'gt7_ranking' => 'top1',
                    'toyota_gr_knowledge' => 'expert',
                    'favorite_car' => 'Toyota GR010 concept and GR Supra GT4.',
                    'participated_before' => true,
                    'wants_training' => false,
                    'join_whatsapp' => true,
                    'heard_about' => 'social_media',
                    'motivation' => ['prizes', 'toyota_experience', 'challenge'],
                    'preferred_time' => 'evening',
                    'suggestions' => 'Publish provisional results faster after each round.',
                ],
            ],
        ]);

        $driverUsers = $drivers->map(function (array $driver) use ($now) {
            $user = User::query()->updateOrCreate(
                ['email' => $driver['email']],
                [
                    'name' => $driver['name'],
                    'phone' => $driver['phone'],
                    'type' => 0,
                    'google_id' => $driver['google_id'],
                    'email_verified_at' => $now,
                    'password' => Hash::make('password'),
                ]
            );

            DB::table('profiles')->updateOrInsert(
                ['user_id' => $user->id],
                array_merge(
                    [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    $this->formatProfileForStorage($driver['profile'])
                )
            );

            return $user;
        });

        $newsItems = collect([
            [
                'slug' => 'season-launch',
                'title' => ['en' => 'GT Cup 26 Season Launch', 'ar' => 'انطلاق موسم GT Cup 26'],
                'description' => ['en' => 'Season registration is now open with new tracks and a refreshed points format.', 'ar' => 'تم فتح التسجيل للموسم الجديد مع حلبات جديدة ونظام نقاط محدث.'],
                'image' => 'seed/news/season-launch.svg',
                'status' => 1,
                'days_ago' => 12,
            ],
            [
                'slug' => 'qualifier-week-announced',
                'title' => ['en' => 'Qualifier Week Schedule Released', 'ar' => 'إعلان جدول أسبوع التصفيات'],
                'description' => ['en' => 'Regional qualifiers are split across multiple cities with time-slot booking.', 'ar' => 'تم تقسيم التصفيات الإقليمية على عدة مدن مع حجز المواعيد.'],
                'image' => 'seed/news/qualifier-week.svg',
                'status' => 1,
                'days_ago' => 9,
            ],
            [
                'slug' => 'driver-coaching-camp',
                'title' => ['en' => 'Driver Coaching Camp Opens', 'ar' => 'افتتاح معسكر تدريب السائقين'],
                'description' => ['en' => 'Training sessions now include braking drills, race pace, and consistency labs.', 'ar' => 'تشمل جلسات التدريب تدريبات الكبح، وإيقاع السباق، ومعامل الثبات.'],
                'image' => 'seed/news/coaching-camp.svg',
                'status' => 1,
                'days_ago' => 7,
            ],
            [
                'slug' => 'safety-rules-v2',
                'title' => ['en' => 'Safety Rules v2 Published', 'ar' => 'نشر نسخة قواعد السلامة v2'],
                'description' => ['en' => 'The latest rulebook update covers pit behavior and collision penalties.', 'ar' => 'يغطي تحديث القواعد الأخير سلوك منطقة الصيانة وعقوبات التصادم.'],
                'image' => 'seed/news/safety-rules.svg',
                'status' => 1,
                'days_ago' => 5,
            ],
            [
                'slug' => 'regional-finals-announced',
                'title' => ['en' => 'Regional Finals Announced', 'ar' => 'إعلان النهائيات الإقليمية'],
                'description' => ['en' => "Top drivers from each qualifier will race in the Sana'a final showdown.", 'ar' => 'سيتسابق أفضل السائقين من كل تصفية في نهائي صنعاء الكبير.'],
                'image' => 'seed/news/regional-finals.svg',
                'status' => 1,
                'days_ago' => 2,
            ],
            [
                'slug' => 'community-night-recap',
                'title' => ['en' => 'Community Night Recap', 'ar' => 'ملخص ليلة المجتمع'],
                'description' => ['en' => 'Photo highlights, driver interviews, and lap analysis are now available.', 'ar' => 'أصبحت لقطات الصور ومقابلات السائقين وتحليل اللفات متاحة الآن.'],
                'image' => 'seed/news/community-night.svg',
                'status' => 0,
                'days_ago' => 1,
            ],
        ]);

        $newsItems->each(function (array $item) use ($now) {
            $createdAt = Carbon::now()->subDays($item['days_ago']);

            News::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'status' => $item['status'],
                    'created_at' => $createdAt,
                    'updated_at' => $now,
                ]
            );
        });

        $tournaments = [
            1 => [
                'title' => ['en' => 'GT Cup 26 Season Hub', 'ar' => 'المركز الرئيسي لموسم GT Cup 26'],
                'description' => ['en' => 'Parent tournament container for season stages.', 'ar' => 'حاوية البطولة الرئيسية لمراحل الموسم.'],
                'locations' => ["Sana'a"],
                'status' => 1,
                'start_date' => Carbon::now()->subDays(30)->toDateString(),
                'end_date' => Carbon::now()->addDays(120)->toDateString(),
                'image' => 'seed/tournaments/season-hub.svg',
            ],
            2 => [
                'title' => ['en' => "Sana'a Qualifier", 'ar' => 'تصفيات صنعاء'],
                'description' => ['en' => 'Opening qualifier for central region competitors.', 'ar' => 'التصفية الافتتاحية لمتسابقي المنطقة الوسطى.'],
                'locations' => ["Sana'a", 'Aden'],
                'status' => 1,
                'start_date' => Carbon::now()->subDays(9)->toDateString(),
                'end_date' => Carbon::now()->subDays(8)->toDateString(),
                'image' => 'seed/tournaments/riyadh-qualifier.svg',
            ],
            3 => [
                'title' => ['en' => 'Aden Coastal Qualifier', 'ar' => 'تصفيات عدن الساحلية'],
                'description' => ['en' => 'Technical track with tire strategy emphasis.', 'ar' => 'حلبة تقنية مع تركيز على استراتيجية الإطارات.'],
                'locations' => ['Aden', 'Hodeidah'],
                'status' => 1,
                'start_date' => Carbon::now()->subDays(3)->toDateString(),
                'end_date' => Carbon::now()->subDays(2)->toDateString(),
                'image' => 'seed/tournaments/jeddah-qualifier.svg',
            ],
            4 => [
                'title' => ['en' => 'Mukalla Sprint Qualifier', 'ar' => 'تصفيات المكلا السريعة'],
                'description' => ['en' => 'Short sprint rounds with rapid leaderboard updates.', 'ar' => 'جولات سريعة قصيرة مع تحديثات متواصلة للترتيب.'],
                'locations' => ['Mukalla', 'Hodeidah'],
                'status' => 1,
                'start_date' => Carbon::now()->addDays(7)->toDateString(),
                'end_date' => Carbon::now()->addDays(8)->toDateString(),
                'image' => 'seed/tournaments/dammam-qualifier.svg',
            ],
            5 => [
                'title' => ['en' => "Final Showdown Sana'a", 'ar' => 'النهائي الكبير في صنعاء'],
                'description' => ['en' => 'Grand finale with top qualifiers and broadcast coverage.', 'ar' => 'النهائي الكبير مع أفضل المتأهلين وتغطية مباشرة.'],
                'locations' => ["Sana'a"],
                'status' => 1,
                'start_date' => Carbon::now()->addDays(24)->toDateString(),
                'end_date' => Carbon::now()->addDays(25)->toDateString(),
                'image' => 'seed/tournaments/final-showdown.svg',
            ],
            6 => [
                'title' => ['en' => 'Practice Invitational', 'ar' => 'دعوة تدريبية'],
                'description' => ['en' => 'Closed invitational event for setup testing.', 'ar' => 'حدث تدريبي مغلق لاختبار الإعدادات.'],
                'locations' => ['Aden'],
                'status' => 0,
                'start_date' => Carbon::now()->addDays(18)->toDateString(),
                'end_date' => Carbon::now()->addDays(18)->toDateString(),
                'image' => 'seed/tournaments/practice-invitational.svg',
            ],
        ];

        foreach ($tournaments as $id => $data) {
            DB::table('tournaments')->updateOrInsert(
                ['id' => $id],
                [
                    'title' => $this->toJsonTranslation($data['title']),
                    'description' => $this->toJsonTranslation($data['description']),
                    'tournament_id' => 1,
                    'location' => json_encode($data['locations']),
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'image' => $data['image'],
                    'status' => $data['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $leaderboards = [
            ['id' => 1, 'tournament_id' => 2, 'location' => "Sana'a"],
            ['id' => 2, 'tournament_id' => 3, 'location' => 'Aden'],
            ['id' => 3, 'tournament_id' => 4, 'location' => 'Mukalla'],
        ];

        foreach ($leaderboards as $board) {
            Leaderboard::query()->updateOrCreate(
                ['id' => $board['id']],
                [
                    'tournament_id' => $board['tournament_id'],
                    'user_id' => $admin->id,
                    'location' => $board['location'],
                    'time_taken' => null,
                    'position' => null,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $registrations = [
            ['email' => 'driver1@gtcup26.local', 'tournament_id' => 2, 'location' => "Sana'a", 'board' => 1, 'position' => 2, 'time' => 566],
            ['email' => 'driver2@gtcup26.local', 'tournament_id' => 2, 'location' => "Sana'a", 'board' => 1, 'position' => 1, 'time' => 552],
            ['email' => 'driver3@gtcup26.local', 'tournament_id' => 2, 'location' => 'Aden', 'board' => null, 'position' => null, 'time' => null],
            ['email' => 'driver4@gtcup26.local', 'tournament_id' => 3, 'location' => 'Aden', 'board' => 2, 'position' => 3, 'time' => 598],
            ['email' => 'driver5@gtcup26.local', 'tournament_id' => 3, 'location' => 'Aden', 'board' => 2, 'position' => 1, 'time' => 548],
            ['email' => 'driver1@gtcup26.local', 'tournament_id' => 4, 'location' => 'Mukalla', 'board' => 3, 'position' => 4, 'time' => 612],
            ['email' => 'driver2@gtcup26.local', 'tournament_id' => 4, 'location' => 'Hodeidah', 'board' => 3, 'position' => 2, 'time' => 571],
            ['email' => 'driver3@gtcup26.local', 'tournament_id' => 5, 'location' => "Sana'a", 'board' => null, 'position' => null, 'time' => null],
            ['email' => 'driver4@gtcup26.local', 'tournament_id' => 5, 'location' => "Sana'a", 'board' => null, 'position' => null, 'time' => null],
            ['email' => 'driver5@gtcup26.local', 'tournament_id' => 6, 'location' => 'Aden', 'board' => null, 'position' => null, 'time' => null],
        ];

        foreach ($registrations as $registration) {
            $driver = User::query()->where('email', $registration['email'])->first();
            if (! $driver) {
                continue;
            }

            DB::table('participants')->updateOrInsert(
                ['user_id' => $driver->id, 'tournament_id' => $registration['tournament_id']],
                [
                    'location' => $registration['location'],
                    'leaderboard_id' => $registration['board'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            if (is_null($registration['board']) || is_null($registration['position']) || is_null($registration['time'])) {
                continue;
            }

            $participantId = DB::table('participants')
                ->where('user_id', $driver->id)
                ->where('tournament_id', $registration['tournament_id'])
                ->value('id');

            if (! $participantId) {
                continue;
            }

            DB::table('leaderboard_participant')->updateOrInsert(
                ['leaderboard_id' => $registration['board'], 'participant_id' => $participantId],
                [
                    'position' => $registration['position'],
                    'time_taken' => $registration['time'],
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $forumSeeds = [
            [
                'slug' => 'best-gr-car-for-time-trial',
                'title' => 'Best GR car for time trial?',
                'body' => 'What setup are you using for dry tracks this season?',
                'status' => 1,
                'image' => 'seed/forums/time-trial.svg',
                'author_email' => 'driver1@gtcup26.local',
            ],
            [
                'slug' => 'wheel-settings-for-consistency',
                'title' => 'Wheel settings for consistency',
                'body' => 'Share force feedback and sensitivity settings that improved lap consistency.',
                'status' => 1,
                'image' => 'seed/forums/wheel-settings.svg',
                'author_email' => 'driver2@gtcup26.local',
            ],
            [
                'slug' => 'favorite-night-race-track',
                'title' => 'Favorite night race track',
                'body' => 'Which night track gives you the best rhythm and why?',
                'status' => 0,
                'image' => 'seed/forums/night-race.svg',
                'author_email' => 'driver5@gtcup26.local',
            ],
        ];

        $forumIds = [];

        foreach ($forumSeeds as $item) {
            $author = User::query()->where('email', $item['author_email'])->first();
            if (! $author) {
                continue;
            }

            DB::table('forums')->updateOrInsert(
                ['slug' => $item['slug']],
                [
                    'user_id' => $author->id,
                    'title' => $item['title'],
                    'body' => $item['body'],
                    'image' => $item['image'],
                    'upvotes' => 0,
                    'status' => $item['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $forumIds[$item['slug']] = DB::table('forums')->where('slug', $item['slug'])->value('id');
        }

        $comments = [
            [
                'forum_slug' => 'best-gr-car-for-time-trial',
                'user_email' => 'driver2@gtcup26.local',
                'body' => 'I am using the GR Supra with medium downforce and soft tires.',
            ],
            [
                'forum_slug' => 'best-gr-car-for-time-trial',
                'user_email' => 'driver4@gtcup26.local',
                'body' => 'GR Yaris feels better for technical sectors, especially hairpins.',
            ],
            [
                'forum_slug' => 'wheel-settings-for-consistency',
                'user_email' => 'driver1@gtcup26.local',
                'body' => 'I lowered sensitivity to 5 and braking smoothness got much better.',
            ],
        ];

        foreach ($comments as $comment) {
            $forumId = $forumIds[$comment['forum_slug']] ?? null;
            $userId = User::query()->where('email', $comment['user_email'])->value('id');

            if (! $forumId || ! $userId) {
                continue;
            }

            DB::table('comments')->updateOrInsert(
                [
                    'forum_id' => $forumId,
                    'user_id' => $userId,
                    'parent_id' => null,
                ],
                [
                    'body' => $comment['body'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $forumVoteMap = [
            'best-gr-car-for-time-trial' => ['driver2@gtcup26.local', 'driver3@gtcup26.local', 'driver5@gtcup26.local'],
            'wheel-settings-for-consistency' => ['driver1@gtcup26.local', 'driver4@gtcup26.local'],
        ];

        foreach ($forumVoteMap as $slug => $emails) {
            $forumId = $forumIds[$slug] ?? null;
            if (! $forumId) {
                continue;
            }

            foreach ($emails as $email) {
                $voterId = User::query()->where('email', $email)->value('id');
                if (! $voterId) {
                    continue;
                }

                Vote::query()->updateOrCreate(
                    [
                        'user_id' => $voterId,
                        'upvoteable_type' => Forum::class,
                        'upvoteable_id' => $forumId,
                    ],
                    [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            DB::table('forums')->where('id', $forumId)->update([
                'upvotes' => Vote::query()
                    ->where('upvoteable_type', Forum::class)
                    ->where('upvoteable_id', $forumId)
                    ->count(),
                'updated_at' => $now,
            ]);
        }

        $contactMessages = [
            ['name' => 'Khaled Al-Rashid', 'email' => 'driver1@gtcup26.local', 'message' => 'Please share race regulation PDF and tire rules.'],
            ['name' => 'Noor Al-Qahtani', 'email' => 'driver2@gtcup26.local', 'message' => 'Can we get a dedicated women qualifier warm-up lobby?'],
            ['name' => 'Yousef Al-Shehri', 'email' => 'driver5@gtcup26.local', 'message' => 'Interested in volunteering for race steward support.'],
        ];

        foreach ($contactMessages as $msg) {
            Contact::query()->updateOrCreate(
                ['email' => $msg['email']],
                [
                    'name' => $msg['name'],
                    'message' => $msg['message'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        if (Schema::hasTable('gr_cars')) {
            $cars = [
                [
                    'name' => 'Toyota GR Supra',
                    'slug' => 'toyota-gr-supra',
                    'hero_tagline' => 'Aero Precision',
                    'description' => 'Built for balanced speed and stability with aggressive corner exit control.',
                    'image' => 'seed/gr-cars/gr-supra.svg',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Toyota GR Yaris',
                    'slug' => 'toyota-gr-yaris',
                    'hero_tagline' => 'Rally DNA',
                    'description' => 'Compact, sharp, and confidence-inspiring on tight and technical layouts.',
                    'image' => 'seed/gr-cars/gr-yaris.svg',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Toyota GR86',
                    'slug' => 'toyota-gr86',
                    'hero_tagline' => 'Pure Balance',
                    'description' => 'A lightweight platform that rewards clean lines and disciplined braking.',
                    'image' => 'seed/gr-cars/gr86.svg',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'name' => 'Toyota GR Corolla',
                    'slug' => 'toyota-gr-corolla',
                    'hero_tagline' => 'Street to Circuit',
                    'description' => 'All-wheel confidence and punchy acceleration for mixed-condition events.',
                    'image' => 'seed/gr-cars/gr-corolla.svg',
                    'is_active' => false,
                    'sort_order' => 4,
                ],
            ];

            foreach ($cars as $car) {
                GRCar::query()->updateOrCreate(['slug' => $car['slug']], $car + ['created_at' => $now, 'updated_at' => $now]);
            }
        }

        if (Schema::hasTable('videos')) {
            $videos = [
                [
                    'title' => 'GT Cup Launch Trailer',
                    'slug' => 'gt-cup-launch-trailer',
                    'description' => 'Official launch trailer for the GT Cup season.',
                    'source_type' => 'external',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'video_file' => null,
                    'thumbnail' => 'seed/videos/trailer.svg',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'title' => "Sana'a Qualifier Highlights",
                    'slug' => 'riyadh-qualifier-highlights',
                    'description' => "Fastest laps and overtakes from Sana'a qualifier.",
                    'source_type' => 'external',
                    'video_url' => 'https://vimeo.com/76979871',
                    'video_file' => null,
                    'thumbnail' => 'seed/videos/riyadh-highlights.svg',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Driver Coaching Session',
                    'slug' => 'driver-coaching-session',
                    'description' => 'A tactical briefing for race consistency and tire strategy.',
                    'source_type' => 'upload',
                    'video_url' => null,
                    'video_file' => 'seed/videos/demo-coaching.mp4',
                    'thumbnail' => 'seed/videos/coaching.svg',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Community Night Recap',
                    'slug' => 'community-night-recap-video',
                    'description' => 'Highlights from the latest community race night.',
                    'source_type' => 'external',
                    'video_url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                    'video_file' => null,
                    'thumbnail' => 'seed/videos/community.svg',
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'title' => 'Technical Setup Deep Dive',
                    'slug' => 'technical-setup-deep-dive',
                    'description' => 'Suspension and brake balance tuning walkthrough.',
                    'source_type' => 'external',
                    'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                    'video_file' => null,
                    'thumbnail' => 'seed/videos/setup.svg',
                    'is_active' => false,
                    'sort_order' => 5,
                ],
            ];

            foreach ($videos as $video) {
                Video::query()->updateOrCreate(
                    ['slug' => $video['slug']],
                    $video + ['created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        if (Schema::hasTable('oauth_settings')) {
            OAuthSetting::query()->updateOrCreate(
                ['id' => 1],
                [
                    'google_client_id' => 'demo-google-client-id.apps.googleusercontent.com',
                    'google_client_secret' => 'demo-google-client-secret',
                    'google_redirect_uri' => url('/auth/google/callback'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function formatProfileForStorage(array $profile): array
    {
        return [
            'birthdate' => $profile['birthdate'],
            'whatsapp' => $profile['whatsapp'],
            'gender' => $profile['gender'],
            'city' => $profile['city'],
            'skill_level' => $profile['skill_level'],
            'has_ps5' => $profile['has_ps5'] ? 1 : 0,
            'primary_platform' => $profile['primary_platform'],
            'regular_games' => $profile['regular_games'],
            'weekly_hours' => $profile['weekly_hours'],
            'favorite_games' => json_encode($profile['favorite_games']),
            'gt7_ranking' => $profile['gt7_ranking'],
            'toyota_gr_knowledge' => $profile['toyota_gr_knowledge'],
            'favorite_car' => $profile['favorite_car'],
            'participated_before' => $profile['participated_before'] ? 1 : 0,
            'wants_training' => $profile['wants_training'] ? 1 : 0,
            'join_whatsapp' => $profile['join_whatsapp'] ? 1 : 0,
            'heard_about' => $profile['heard_about'],
            'motivation' => json_encode($profile['motivation']),
            'preferred_time' => $profile['preferred_time'],
            'suggestions' => $profile['suggestions'],
        ];
    }

    private function toJsonTranslation(array $translations): string
    {
        return json_encode($translations, JSON_UNESCAPED_UNICODE);
    }

    private function seedPlaceholderImages(): void
    {
        $definitions = [
            'news/season-launch.svg' => ['Season Launch', '#8b0000', '#e60010'],
            'news/qualifier-week.svg' => ['Qualifier Week', '#54000a', '#c8102e'],
            'news/coaching-camp.svg' => ['Coaching Camp', '#6b0010', '#f03'],
            'news/safety-rules.svg' => ['Safety Rules', '#4b0007', '#b60e1f'],
            'news/regional-finals.svg' => ['Regional Finals', '#5d000d', '#da1125'],
            'news/community-night.svg' => ['Community Night', '#720012', '#ff1e3a'],
            'tournaments/season-hub.svg' => ['Season Hub', '#2b2b2b', '#7c0a14'],
            'tournaments/riyadh-qualifier.svg' => ["Sana'a Qualifier", '#3a0009', '#d1122f'],
            'tournaments/jeddah-qualifier.svg' => ['Aden Qualifier', '#3d0010', '#b30a21'],
            'tournaments/dammam-qualifier.svg' => ['Mukalla Qualifier', '#310008', '#a90c20'],
            'tournaments/final-showdown.svg' => ['Final Showdown', '#45000f', '#ff1330'],
            'tournaments/practice-invitational.svg' => ['Practice Invitational', '#2f2f2f', '#7b1b28'],
            'forums/time-trial.svg' => ['Time Trial Thread', '#35000a', '#d10f26'],
            'forums/wheel-settings.svg' => ['Wheel Settings', '#3b000b', '#be1025'],
            'forums/night-race.svg' => ['Night Race', '#220006', '#7f0d1d'],
            'gr-cars/gr-supra.svg' => ['GR Supra', '#4a000f', '#d6132c'],
            'gr-cars/gr-yaris.svg' => ['GR Yaris', '#4f0013', '#ca1228'],
            'gr-cars/gr86.svg' => ['GR86', '#3f000e', '#b81124'],
            'gr-cars/gr-corolla.svg' => ['GR Corolla', '#470012', '#d21029'],
            'videos/trailer.svg' => ['Launch Trailer', '#240006', '#a0081f'],
            'videos/riyadh-highlights.svg' => ["Sana'a Highlights", '#2e0008', '#b40b24'],
            'videos/coaching.svg' => ['Coaching Session', '#2a0007', '#9c0a1d'],
            'videos/community.svg' => ['Community Recap', '#33000a', '#b90d25'],
            'videos/setup.svg' => ['Setup Deep Dive', '#250006', '#93091a'],
        ];

        $basePath = storage_path('app/public/seed');
        File::ensureDirectoryExists($basePath);

        foreach ($definitions as $relative => [$label, $startColor, $endColor]) {
            $fullPath = storage_path('app/public/seed/'.$relative);
            File::ensureDirectoryExists(dirname($fullPath));

            if (File::exists($fullPath)) {
                continue;
            }

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 720">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$startColor}"/>
      <stop offset="100%" stop-color="{$endColor}"/>
    </linearGradient>
  </defs>
  <rect width="1280" height="720" fill="url(#bg)"/>
  <rect x="44" y="44" width="1192" height="632" rx="22" fill="none" stroke="rgba(255,255,255,0.35)" stroke-width="4"/>
  <text x="640" y="360" text-anchor="middle" fill="#ffffff" font-size="68" font-family="Arial, sans-serif" font-weight="700">{$label}</text>
  <text x="640" y="420" text-anchor="middle" fill="rgba(255,255,255,0.85)" font-size="30" font-family="Arial, sans-serif">AMTC GR GT CUP Demo Asset</text>
</svg>
SVG;

            File::put($fullPath, $svg);
        }
    }
}
