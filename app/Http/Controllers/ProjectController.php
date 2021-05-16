<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\TwitterUser;
use App\Models\UserMention;
use App\Services\GuzzleConnection;
use App\Services\RepositoryInterface;
use Illuminate\Support\Facades\DB;

use App\Exceptions\NoDataFetchedException;
use App\Exceptions\NoApiResponseException;
use App\Exceptions\DataInsertionFailedException;

class ProjectController extends Controller
{
    protected $conexion;
    private $repositoryInterface;
    public function __construct(GuzzleConnection $conn, RepositoryInterface $repositoryInterface)
    {
        $this->repositoryInterface = $repositoryInterface;
        $this->conn = $conn->conexion();
        if (!$this->conn) {
            throw  new NoApiResponseException('connection with api could not be established');
        }
    }

    public function tweetRetrieval()
    {

        $url = env('TWITTER_API_URL') . 'tweets';

        // query parameters
        $params = [
            'ids' => '1263150595717730305,1227640996038684673,1199786642791452673',
            'tweet.fields' => 'created_at',
            'expansions' => 'author_id',
            'user.fields' => 'created_at'
        ];

        try {
            DB::beginTransaction();
            $response = $this->conn->request(
                'GET',
                $url,
                [
                    'query' => $params,
                ]
            );
            if (empty($response)) {
                throw new NoDataFetchedException('No data found for this request');
            }
            $responseBody = json_decode($response->getBody());

            foreach ($responseBody->data as $row) {

                // use of repository design pattern
                $data = $this->repositoryInterface->createTweets($row);

                if (!$data) {
                    throw new DataInsertionFailedException('Data insertion was failed try one more time');
                }
                DB::commit();
                return  response()->json(['message' => 'success', 'data' => $data], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'fail', 'data' => []]);
        }
    }


    public function userMentionRetrieval()
    {

        $user = 2244994945; // random user id of a twitter account holder

        // url for retrieving user mentions on twitter
        $url = env('TWITTER_API_URL') . 'users/' . $user . '/mentions';
        $params = [
            'max_results' => 100,
            'tweet.fields' => 'created_at',
        ];

        try {
            DB::beginTransaction();

            $response = $this->conn->request('GET', $url, [
                'query' => $params,
            ]);

            if (empty($response)) {
                throw new NoDataFetchedException('No data available to work with');
            }
            $responseBody = json_decode($response->getBody());
            $mention = new UserMention();
            foreach ($responseBody->data as $row) {

                // use of repository design pattern
                $data = $this->repositoryInterface->createMentions($row, $responseBody);

                if (!$data) {
                    throw new DataInsertionFailedException('Data could be inserted try again');
                }

                DB::commit();
                return response()->json(['message' => 'success', 'data' => $data], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'fail', 'data' => []]);
        }
    }
}
