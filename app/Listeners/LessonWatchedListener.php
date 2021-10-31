<?php

namespace App\Listeners;

use App\Achievements\LessonAchievement;
use App\Events\LessonWatched;
use App\Models\Lesson;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Facades\DB;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $lessonAchievement = new LessonAchievement();
        $lessonAchievement->handle($event);
    }

}
