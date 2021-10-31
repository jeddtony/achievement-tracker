<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $achievementUnlocked)
    {
        $achievement_name = $achievementUnlocked->achievementName;
        $user = $achievementUnlocked->user;
        
        // Get achievement_id using the achievement_name
        $achievement = Achievement::where('slug', $achievement_name)->first();

        // update the achievement_user record
        DB::table('achievement_user')
        ->where(['achievement_id' => $achievement->id, 'user_id' => $user->id])
        ->increment('current_step', 1, ['is_completed' => true]);
        
        // Create a new achievement_user record if there is another achievement
        if($achievement->next_achievement_id){
            $nextAchievementId = $achievement->next_achievement_id;
            $nextAchievement = Achievement::find($nextAchievementId);
            DB::table('achievement_user')
            ->insert([
                'user_id' => $user->id,
                'achievement_id' => $nextAchievement->id,
                'current_step' => $achievement->condition,
                'no_of_steps_required' => $nextAchievement->condition
            ]);
        }

        // Get count of current achievements;
        $achievementCount = DB::table('achievement_user')
        ->where(['user_id' => $user->id, 'is_completed' => true])->count();

        // Get next badge requirement
        $nextBadge = $this->getNextBadge($user->id);

        // If the requirement is met then trigger badgeUnlock event
        if($achievementCount == $nextBadge->no_of_required_achievements){
            $badgeName = Badge::find($nextBadge->badge_id);
            BadgeUnlocked::dispatch($badgeName->slug, $user);
        }
        // else just increment the no_of_current_achievements in the current badge

        // Increment the no_of_current_achievements on the badge_users table
        DB::table('badge_user')
        ->where(['user_id' => $user->id, 'is_completed' => false])
        ->increment('no_of_current_achievements', 1);

    }

    public function getNextBadge($userId){
        return DB::table('badge_user')
        ->where(['user_id' => $userId, 'is_completed' => false])
        ->first();
    }
}
