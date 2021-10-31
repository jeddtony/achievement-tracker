<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class CommentWrittenListener
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
    public function handle( CommentWritten $event)
    {
        
        $userId = $event->comment->user_id;
        // Count the number of comments
        $commentCount = $this->getUserCommentCount($userId);
        // See if it matches the next comment achievement for the user
        $nextAchievement = $this->getNextCommentAchievement($userId);


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
            ->where(['user_id' => $userId, 'achievement_id' => $nextAchievement->id, 'is_completed' => false])
            ->increment('current_step', 1);
        }
        
    }

    private function getUserCommentCount($userId) 
    {
        return Comment::where('user_id', $userId)->count();
    }

    private function getNextCommentAchievement($userId) 
    {
        $achievements = User::where('id', $userId)->with('nextAchievement')->first()->nextAchievement;
        $achievementToReturn = false;
        foreach ($achievements as  $achievement) {
           if($achievement->type == 'comment'){
               $achievementToReturn = $achievement;
           }
        }
        return $achievementToReturn;
    }

}
