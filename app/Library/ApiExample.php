<?php

namespace App\Library;

use App\Contracts\ApiInterface;

class ApiExample implements ApiInterface
{
    /**
     * Create a new class instance.
     */
    public function fazAlgumaCoisa(): string
    {
        return "Texto da classe concreta ApiExample.\n";
    }
}
