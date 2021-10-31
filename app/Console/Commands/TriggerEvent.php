<?php

namespace App\Console\Commands;

use App\Http\Controllers\AchievementController;
use Illuminate\Console\Command;

class TriggerEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triggerEvent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $achievementController = new AchievementController();
        $achievementController->storeComment();
        $achievementController->storeLesson();
        return Command::SUCCESS;
    }
}
