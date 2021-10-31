<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\AwardCommentAchievement;
use App\Listeners\AwardLessonAchievement;
use App\Listeners\BadgeUnlockedListener;
use App\Listeners\CommentWrittenListener;
use App\Listeners\LessonWatchedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            //
            CommentWrittenListener::class,
        ],
        LessonWatched::class => [
            //
            // AwardLessonAchievement::class,
            LessonWatchedListener::class
        ],
        AchievementUnlocked::class => [
            AchievementUnlockedListener::class
        ],
        BadgeUnlocked::class => [
            BadgeUnlockedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
