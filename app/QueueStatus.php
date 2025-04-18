<?php
namespace App;

enum QueueStatus: string {
    case Waiting = 'waiting';
    case Calling = 'calling';
    case Done    = 'done';
    case Skipped = 'skipped';
}
