<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class BadgeUnlockedListener
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
    public function handle(BadgeUnlocked $badgeUnlocked)
    {
        //
        $badgeName = $badgeUnlocked->badgeName;
        $user = $badgeUnlocked->user;

        $badge = Badge::where('slug', $badgeName)->first();

        // Update previous badge record
        DB::table('badge_user')
        ->where(['badge_id' => $badge->id, 'user_id' => $user->id])
        ->increment('no_of_current_achievements', 1, ['is_completed' => true]);

        // create a new badge_user record if there is another badge
        if($badge->next_badge_id){
            $nextBadgeId = $badge->next_badge_id;
            $nextBadge = Badge::find($nextBadgeId);

            DB::table('badge_user')
                ->insert([
                    'user_id' => $user->id,
                    'badge_id' => $nextBadge->id,
                    'no_of_current_achievements' => $badge->no_of_achievements,
                    'no_of_required_achievements' => $nextBadge->no_of_achievements,
                    'is_completed' => false
                ]);
        }
        // dd('Badge has been updated');
    }
}
