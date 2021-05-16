<?php

namespace App\Repository;

use App\Models\TwitterUser;
use App\Models\UserMention;
use App\Services\RepositoryInterface;

class UserRepository implements RepositoryInterface
{

    public function createTweets($data)
    {
        return TwitterUser::create([
            'id' => $data->id,
            'text' => $data->text,
            'author_id' => $data->author_id,
            'created_at' => $data->created_at
        ]);
    }

    public function createMentions($row, $responseBody)
    {
        return UserMention::create(
            [
                'post_id' => $row->id,
                'text' => $row->text,
                'users_oldest_id' => $responseBody->meta->oldest_id,
                'users_newest_id' => $responseBody->meta->newest_id,
                'username_appearance_count' => $responseBody->meta->result_count,
            ]
        );
    }
}
