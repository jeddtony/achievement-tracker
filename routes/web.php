<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\AchievementController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);



