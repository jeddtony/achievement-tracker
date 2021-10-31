<?php

namespace App\Achievements;

interface Achievement {
    public function handle($event);
    public function getUserCurrentCount($userId);
    public function getNextAchievement($userId);
}