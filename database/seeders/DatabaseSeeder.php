<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->count(10)->create();

        // User::factory()->hasAchievements(1, [
        //     'current_step' => 0,
        //     'no_of_steps_remaining' => 1,
        // ]);
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

            $arrayOfAchievements = [
                [
                    'slug' => 'first_lesson_watched',
                   'title' => 'First Lesson Watched',
                   'description' => 'The description for the first lesson watched',
                   'type' => 'lesson',
                   'condition' => 1,
                   'next_achievement_id' => 2
                ],
                [
                    'slug' => '5_lessons_watched',
                    'title' => '5 Lessons Watched',
                    'description' => 'The description for the 5 lessons watched',
                    'type' => 'lesson',
                    'condition' => 5,
                    'next_achievement_id' => 3
                ],
                [
                    'slug' => '10_lessons_watched',
                    'title' => '10 Lessons Watched',
                    'description' => 'The description for the 10 lessons watched',
                    'type' => 'lesson',
                    'condition' => 10,
                    'next_achievement_id' => 4
                ],
                [
                    'slug' => '25_lessons_watched',
                    'title' => '25 Lessons Watched',
                    'description' => 'The description for the 25 lessons watched',
                    'type' => 'lesson',
                    'condition' => 25,
                    'next_achievement_id' => 5
                ],
                [
                    'slug' => '50_lessons_watched',
                    'title' => '50 Lessons Watched',
                    'description' => 'The description for the 50 lessons watched',
                    'type' => 'lesson',
                    'condition' => 50
                ],
                [
                    'slug' => 'first_comment_written',
                   'title' => 'First Comment Written',
                   'description' => 'The achievement is given for the first comment made',
                   'type' => 'comment',
                   'condition' => 1,
                   'next_achievement_id' => 7
                ],
                [
                    'slug' => '3_comments_written',
                    'title' => '3 comments written',
                    'description' => 'The achievement is given after the user makes 3 comments',
                    'type' => 'comment',
                    'condition' => 3,
                    'next_achievement_id' => 8
                ],
                [
                    'slug' => '5_comments_written',
                    'title' => '5 comments Written',
                    'description' => 'The achievement is given after the user makes 5 comments',
                    'type' => 'comment',
                    'condition' => 5,
                    'next_achievement_id' => 9
                ],
                [
                    'slug' => '10_comment_written',
                    'title' => '10 comment Written',
                    'description' => 'The achievement is given after the user makes 10 comments',
                    'type' => 'comment',
                    'condition' => 10,
                    'next_achievement_id' => 10
                ],
                [
                    'slug' => '20_comments_written',
                    'title' => '20 Comments Written',
                    'description' => 'The achievement is given after the user makes 20 comments',
                    'type' => 'comment',
                    'condition' => 50
                ]
                ];

               
                array_map([$this, "createAchievement"], $arrayOfAchievements);
        
        
                $badgesArray = [
                    [
                        'slug' => 'beginner',
                        'name' => 'Beginner',
                        'description' => 'The description for the beginner',
                        'no_of_achievements' => 0,
                        'next_badge_id' => 2
                    ],
                    [
                        'slug' => 'intermediate',
                        'name' => 'Intermediate',
                        'description' => 'The description for the intermediate',
                        'no_of_achievements' => 4,
                        'next_badge_id' => 3
                    ],
                    [
                        'slug' => 'advanced',
                        'name' => 'Advanced',
                        'description' => 'The description for the advanced',
                        'no_of_achievements' => 8,
                        'next_badge_id' => 4
                    ],
                    [
                        'slug' => 'master',
                        'name' => 'Master',
                        'description' => 'The description for the master',
                        'no_of_achievements' => 10,
                    ]
                ];


                array_map([$this, "createBadge"], $badgesArray);


        DB::table('achievement_user')->insert([
            'user_id' => 1,
            'achievement_id' => 1,
            'current_step' => 0,
            'no_of_steps_required' => 1,
            'is_completed' => false
        ]);

        DB::table('achievement_user')->insert([
            'user_id' => 1,
            'achievement_id' => 6,
            'current_step' => 0,
            'no_of_steps_required' => 1,
            'is_completed' => false
        ]);

        DB::table('badge_user')->insert([
            'user_id' => 1,
            'badge_id' => 1,
            'no_of_current_achievements' => 0,
            'no_of_required_achievements' => 0,
            'is_completed' => true
        ]);

        DB::table('badge_user')->insert([
            'user_id' => 1,
            'badge_id' => 2,
            'no_of_current_achievements' => 0,
            'no_of_required_achievements' => 4,
            'is_completed' => false
        ]);
    }

    public function createAchievement($achievement){
        return Achievement::create($achievement);
     }


    public function createBadge($badge){
        return Badge::create($badge);
     }
}
