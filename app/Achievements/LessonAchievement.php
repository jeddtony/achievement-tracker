<?php

namespace App\Achievements;

use App\Events\LessonWatched;
use App\Models\User;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Facades\DB;

class LessonAchievement implements Achievement
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    public function handle($event)
    {
        $userId = $event->user->id;
        // Count the number of comments
        $commentCount = $this->getUserCurrentCount($userId);
        // See if it matches the next comment achievement for the user
        $nextAchievement = $this->getNextAchievement($userId);

        if (!$nextAchievement) {
            return;
        }

        $numberOfStepsRequired = $nextAchievement->pivot->no_of_steps_required;

        if ($commentCount == $numberOfStepsRequired) {
            // If it matches then trigger an achievement event
            // The triggered achievement will update the old achievement 
            // and then set a new achievement
            $user = User::find($userId);
            AchievementUnlocked::dispatch($nextAchievement->slug, $user);
        } else {
            // else increment the current step in the achievement user
            DB::table('achievement_user')
                ->where(['user_id' => $userId, 'achievement_id' => $nextAchievement->id,  'is_completed' => false])
                ->increment('current_step', 1);
        }
    }

    public function getUserCurrentCount($userId)
    {
        $user =  User::where('id', $userId)->with('watched')->first();
        return $user->watched->count();
    }

    public function getNextAchievement($userId)
    {
        $achievements = User::where('id', $userId)->with('nextAchievement')->first()->nextAchievement;
        $achievementToReturn = false;
        foreach ($achievements as  $achievement) {
            if ($achievement->type == 'lesson') {
                $achievementToReturn = $achievement;
            }
        }
        return $achievementToReturn;
    }
}
