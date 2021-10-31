<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Http\Controllers\AchievementController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\BadgeUnlockedListener;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class AchievementTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();

        $this->user = $user;

        // Create achievement
        Achievement::create([
            'slug' => 'first_lesson_watched',
            'title' => 'First Lesson Watched',
            'description' => 'The description for the first lesson watched',
            'type' => 'comment',
            'condition' => 1,
            'next_achievement_id' => 2
        ]);

        // Create badge
        Badge::create([
            'slug' => 'beginner',
            'name' => 'Beginner',
            'description' => 'The description for the beginner',
            'no_of_achievements' => 0,
            'next_badge_id' => 2
        ]);

        Badge::create([
            'slug' => 'intermediate',
            'name' => 'Intermediate',
            'description' => 'The description for the intermediate badge',
            'no_of_achievements' => 0,
            'next_badge_id' => 2
        ]);

        Lesson::factory()
            ->count(20)
            ->create();

        DB::table('achievement_user')->insert([
            'user_id' => 1,
            'achievement_id' => 1,
            'current_step' => 1,
            'no_of_steps_required' => 1,
            'is_completed' => true
        ]);

        DB::table('achievement_user')->insert([
            'user_id' => 1,
            'achievement_id' => 2,
            'current_step' => 2,
            'no_of_steps_required' => 5,
            'is_completed' => false
        ]);

        DB::table('badge_user')->insert([
            'user_id' => 1,
            'badge_id' => 2,
            'no_of_current_achievements' => 0,
            'no_of_required_achievements' => 4,
            'is_completed' => false
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/users/1/achievements');

        $response->assertStatus(200);
    }

    /**
     * Test that CommentWritten was dispatched.
     *
     * @return void
     */
    public function test_comment_written_was_dispatched()
    {
        Event::fake();

        $achievement = new AchievementController();
        $achievement->storeComment();

        Event::assertDispatched(CommentWritten::class);
    }

    /**
     * Test that AchievementUnlocked was dispatched.
     *
     * @return void
     */
    public function test_achievement_unlocked_was_dispatched()
    {
        Event::fake();

        AchievementUnlocked::dispatch('first_lesson_watched', $this->user);

        Event::assertDispatched(AchievementUnlocked::class);
    }

    /**
     * Test that BadgeUnlocked was dispatched.
     *
     * @return void
     */
    public function test_badge_unlocked_was_dispatched()
    {
        Event::fake();

        BadgeUnlocked::dispatch('beginner', $this->user);

        Event::assertDispatched(BadgeUnlocked::class);
    }

    /**
     * Test that AchievementUnlocked has a listener.
     *
     * @return void
     */
    public function test_achievement_unlocked_has_a_listener()
    {
        Event::fake();

        Event::assertListening(
            AchievementUnlocked::class,
            AchievementUnlockedListener::class
        );
    }

    /**
     * Test that BadgeUnlocked has a listener.
     *
     * @return void
     */
    public function test_badge_unlocked_has_a_listener()
    {
        Event::fake();

        Event::assertListening(
            BadgeUnlocked::class,
            BadgeUnlockedListener::class
        );
    }

    /**
     * Test that unlocked achievements are being returned for a user.
     *
     * @return void
     */
    public function test_that_a_user_has_unlocked_achievements()
    {
        $achievementController = new AchievementController();
        $achievements = $achievementController->getUnlockedAchievements($this->user->id);
        $this->assertIsArray($achievements);
        $this->assertEquals(1, count($achievements));
    }

    /**
     * Test that next available achievements are being returned for a user.
     *
     * @return void
     */
    public function test_that_a_user_has_next_available_achievements()
    {
        $achievementController = new AchievementController();
        $nextAchievement = $achievementController->getUnlockedAchievements($this->user->id);

        $this->assertIsArray($nextAchievement);
    }

    /**
     * Test that next available achievements are being returned for a user.
     *
     * @return void
     */
    public function test_that_a_user_has_next_achievement()
    {
        $achievementController = new AchievementController();
        $nextBadge = $achievementController->getNextBadge($this->user->id);

        $this->assertIsString($nextBadge);
    }

    /**
     * Test that next available achievements are being returned for a user.
     *
     * @return void
     */
    public function test_that_a_badge_has_remaining_items_for_next_badge()
    {
        $achievementController = new AchievementController();
        $nextBadge = $achievementController->getRemainingToUnlockNextBadge($this->user->id);

        $this->assertIsNumeric($nextBadge);
    }
}
