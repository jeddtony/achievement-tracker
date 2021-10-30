<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
        dd($event->comment);
        dd('A new comment was created');
        // Count the number of comments
        // See if it matches the next comment achievement for the user
        // If it matches then trigger an achievement event
        
    }
}
j