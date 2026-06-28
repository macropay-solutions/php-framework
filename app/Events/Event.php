<?php

namespace App\Events;

use MacropaySolutions\Kernel\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
