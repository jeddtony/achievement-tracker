<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\AchievementController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
// Route::get('/users/{user}/achievements', [AchievementController::class, 'getUnlockedAchievements']);
// Route::get('/users/{user}/achievements', [AchievementController::class, 'getNextAvailableAchievement']);
// Route::get('/users/{user}/achievements', [AchievementController::class, 'getCurrentBadge']);
// Route::get('/users/{user}/achievements', [AchievementController::class, 'getNextBadge']);



