<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Matrix\Exception;
use GuzzleHttp\Client;

class EmployeeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update employees (recruitment,moves,fires)';

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
        $token = $this->getToken();
        if($token){
            $this->info('Token success');
            try {
                $client = new Client();
                $newresponse = $client->request(
                    'GET',
                    'http://mhr.loc/api/update-employees',
                    ['headers' =>
                        [
                            'Authorization' => "Bearer {$token}",
                        ]
                    ]
                )->getBody()->getContents();
                $result = json_decode($newresponse,true);
                if(!empty($result['result'])){
                    $this->info('Result success');
                    if(!empty($result['result']['recruitment'])){
                        $this->info(count($result['result']['recruitment']).' new employee');
                    }
                    if(!empty($result['result']['fires'])){
                        $this->info(count($result['result']['fires']).' fired employee');
                    }
                    if(!empty($result['result']['moves'])){
                        $this->info(count($result['result']['moves']).' moved employee');
                    }
                }
            }catch (\Exception $e){
                $this->info('Something went wrong');
                throw $e;
                return 0;
            }
        }else{
            $this->info('Token failed');exit;
        }
        return 0;
    }

    public function getToken()
    {
        //Send query
        try {
            $client = new \GuzzleHttp\Client();
            $resp = $client->post(
                'http://mhr.loc/api/login',
                array(
                    'form_params' => array(
                        'email' => 'sayyid2112@gmail.com',
                        'password' => '123456'
                    )
                )
            );
            $data = $resp->getBody()->getContents();
            $data = json_decode($data,true);
            if(!empty($data['token'])){
                $token = $data['token'];
                return $token;
            }
            return false;
        } catch (\Exception $e) {
            //throw $e;
            return false;
        }
    }
}
