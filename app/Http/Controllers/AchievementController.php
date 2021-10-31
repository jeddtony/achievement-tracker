<?php

namespace App\Http\Controllers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        //
        $unlockedAchievements     = $this->getUnlockedAchievements($userId);
        $nextAvailableAchievement = $this->getNextAvailableAchievement($userId);
        $currentBadge             = $this->getCurrentBadge($userId);
        $nextBadge                = $this->getNextBadge($userId);
        $remainingAchievements    = $this->getRemainingToUnlockNextBadge($userId);

        return [
            'unlockedAchievements'       => $unlockedAchievements,
            'nextAvailableAchievement'   => $nextAvailableAchievement,
            'currentBadge'               => $currentBadge,
            'nextBadge'                  => $nextBadge,
            'remainingToUnlockNextBadge' => $remainingAchievements
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function storeComment()
    {
        //
        $comment = new Comment();
        $comment->body = 'This is the body';
        $comment->user_id = 1;
        $comment->save();
        
        // $user = User::find(1);
        CommentWritten::dispatch($comment);
        // Todo:: Create an event that updates the achievement_user table
        // AchievementUnlocked::dispatch('first_lesson_watched', $user);

        // BadgeUnlocked::dispatch('beginner', $user);
    }

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function storeLesson()
    {
        //
        // $comment = new Comment();
        // $comment->body = 'This is the body';
        // $comment->user_id = 1;
        // $comment->save();
        
       DB::table('lesson_user')
        ->insert([
            'user_id' => 1,
            'lesson_id' => 1,
            'watched' => 1
        ]);
        $lesson = Lesson::find(1);
        $user = User::find(1);
        // $user = User::find(1);
        LessonWatched::dispatch($lesson, $user);
        // Todo:: Create an event that updates the achievement_user table
        // AchievementUnlocked::dispatch('first_lesson_watched', $user);

        // BadgeUnlocked::dispatch('beginner', $user);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function show(Achievement $achievement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function edit(Achievement $achievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Achievement $achievement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Achievement $achievement)
    {
        //
    }

    public function getUnlockedAchievements($userId): array
    {
        $achievements = User::where('id', $userId)->with('completedAchievements')->first();
        
        $arrayOfTitles = [];
        $completedAchievements = $achievements->completedAchievements;
        foreach($completedAchievements as $completedAchievement => $completed){
            array_push($arrayOfTitles, $completed->title);
        }
        return $arrayOfTitles;
    }

    public function getNextAvailableAchievement($userId): array
    {
        $achievements = User::where('id', $userId)->with('nextAchievement')->first();

        $arrayOfTitles = [];
        $completedAchievements = $achievements->nextAchievement;
        foreach($completedAchievements as $completedAchievement => $completed){
            array_push($arrayOfTitles, $completed->title);
        }
        return $arrayOfTitles;
    }

    public function getCurrentBadge($userId) {
        $user = User::where('id', $userId)->with('badges')->first();
        $completedBadges = $user->badges;
        if(count($completedBadges)){
            return $completedBadges[$completedBadges->count() - 1]->name;
           
        }
        return null;
    }

    public function getNextBadge($userId) {
        $user = User::where('id', $userId)->with('nextBadges')->first();
        $uncompletedBadges = $user->nextBadges;
        if(count($uncompletedBadges)){
            return $uncompletedBadges[$uncompletedBadges->count() - 1]->name;
        }
        return null;
    }

    public function getRemainingToUnlockNextBadge($userId){
        $user = User::where('id', $userId)->with('nextBadges')->first();
        $uncompletedBadges = $user->nextBadges;
        if(count($uncompletedBadges)){
            return $uncompletedBadges[0]->pivot->no_of_required_achievements - $uncompletedBadges[0]->pivot->no_of_current_achievements;
        }
        return 0;
    }
}
