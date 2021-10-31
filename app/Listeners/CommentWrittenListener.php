<?php

namespace App\Listeners;

use App\Achievements\CommentAchievement;
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
        $commentAchievement = new CommentAchievement();
        $commentAchievement->handle($event);
        
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
