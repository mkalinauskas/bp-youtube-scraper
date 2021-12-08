<?php

namespace App\Exception;

class ChannelNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Channel not found');
    }
}