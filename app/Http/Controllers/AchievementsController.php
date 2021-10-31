<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $achievementController = new AchievementController();
        $achievements = $achievementController->index($user->id);
        return response()->json([
            'unlocked_achievements' => $achievements['unlockedAchievements'],
            'next_available_achievements' => $achievements['nextAvailableAchievement'],
            'current_badge' => $achievements['currentBadge'],
            'next_badge' => $achievements['nextBadge'],
            'remaining_to_unlock_next_badge' => $achievements['remainingToUnlockNextBadge']
        ]);
    }
}
