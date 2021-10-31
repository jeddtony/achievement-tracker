<?php

namespace App\Listeners;

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
        $userId = $event->user->id;
        // Count the number of comments
        $commentCount = $this->getUserLessonCount($userId);
        // See if it matches the next comment achievement for the user
        $nextAchievement = $this->getNextLessonAchievement($userId);

        if(!$nextAchievement){
            return;
        }

        $numberOfStepsRequired = $nextAchievement->pivot->no_of_steps_required;

         if($commentCount == $numberOfStepsRequired){
        // If it matches then trigger an achievement event
        // The triggered achievement will update the old achievement 
        // and then set a new achievement
        $user = User::find($userId);
        AchievementUnlocked::dispatch($nextAchievement->slug, $user);
        }
        else{
            // else increment the current step in the achievement user
            DB::table('achievement_user')
            ->where(['user_id' => $userId, 'achievement_id' => $nextAchievement->id,  'is_completed' => false])
            ->increment('current_step', 1);
        }
    }

    private function getUserLessonCount($userId) 
    {
        $user =  User::where('id', $userId)->with('watched')->first();
        return $user->watched->count();
    }

    private function getNextLessonAchievement($userId) 
    {
        $achievements = User::where('id', $userId)->with('nextAchievement')->first()->nextAchievement;
        $achievementToReturn = false;
        foreach ($achievements as  $achievement) {
           if($achievement->type == 'lesson'){
               $achievementToReturn = $achievement;
           }
        }
        return $achievementToReturn;
    }
}
