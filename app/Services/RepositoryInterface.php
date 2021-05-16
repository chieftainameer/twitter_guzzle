<?php

namespace App\Services;

interface RepositoryInterface
{
    public function createTweets($data);

    public function createMentions($data, $body);
}
