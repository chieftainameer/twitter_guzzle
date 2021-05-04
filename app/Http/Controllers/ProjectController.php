<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

use App\Models\TwitterUser;
use App\Models\UserMention;

class ProjectController extends Controller
{
    public function tweetRetrieval(){
        $client = new Client(); // new guzzle http client 

        $url = 'https://api.twitter.com/2/tweets';

        // query parameters
        $params = [
                'ids' => '1263150595717730305,1227640996038684673,1199786642791452673',
                'tweet.fields' => 'created_at',
                'expansions' => 'author_id',
                'user.fields' => 'created_at'     
        ];

        // authorization token
        $headers = [
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAAPnhNAEAAAAAwt5vDJ%2BNlfs1qRYzRGYv8P89jf0%3DGxFJcptaF31fecaSq7qt0JrFbFaEt7auWRh0JJG3WhCCTtj29i
            '
        ];

        $response = $client->request('GET',$url,[
            'headers' => $headers,
            'query' => $params,
            
        ]
        );

        $responseBody = json_decode($response->getBody());

        foreach( $responseBody->data as $row){
            $data = TwitterUser::create(
                [
                    'id' => $row->id,
                    'text' => $row->text,
                    'author_id' => $row->author_id,
                    'created_at' => $row->created_at
                ]
            );
            if($data){
                Log::info('data inserted to db.', ['id' => $data->id]);
                echo "Data retrieved and has been persisted to database successfully\n";
            }
            else
            {
                echo "Sorry! Data could be loaded to database";
            }
        }

        // dd(sizeof($responseBody->data));

    }


    public function userMentionRetrieval(){
        $client = new Client();
        $user = 2244994945; // random user id of a twitter account holder

        // url for retrieving user mentions on twitter
        $url = 'https://api.twitter.com/2/users/'.$user.'/mentions';
        $params = [
            'max_results' => 100,
            'tweet.fields' => 'created_at',
        ];

        $headers = [
            'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAAPnhNAEAAAAAwt5vDJ%2BNlfs1qRYzRGYv8P89jf0%3DGxFJcptaF31fecaSq7qt0JrFbFaEt7auWRh0JJG3WhCCTtj29i
            '
        ];

        $response = $client->request('GET',$url,[
            'headers' => $headers,
            'query' => $params,
        ]);

        $responseBody = json_decode($response->getBody());
        $mention = new UserMention();
        foreach($responseBody->data as $row){
            $data = $mention::create([
                'post_id' => $row->id,
                'text' => $row->text,
                'users_oldest_id' => $responseBody->meta->oldest_id,
                'users_newest_id' => $responseBody->meta->newest_id,
                'username_appearance_count' => $responseBody->meta->result_count,
            ]);
            if($data){
                Log::info('data persisted to db',['id' => $data->id]);
            }
            else
            {
                Log::error("Some error occured");
            }
        }
        // dd($responseBody);  
    }
}
