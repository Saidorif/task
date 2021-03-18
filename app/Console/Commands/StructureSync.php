<?php

namespace App\Console\Commands;

use App\Position;
use App\Structure;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class StructureSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync structure from EHR';

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
                //Send query
                $client = new Client();
                $newresponse = $client->request(
                    'GET',
                    'http://hr.uztrans.uz/api/get-structure',
                    ['headers' =>
                        [
                            'Authorization' => "Bearer {$token}",
                        ]
                    ]
                )->getBody()->getContents();
                $result = json_decode($newresponse,true);
                if(!empty($result['result'])){
                    $this->info(count($result['result']));
                    $updates = 0;
                    $createds = 0;
                    foreach ($result['result'] as $item){
                        $item['name'] = $item['name_uz'];
                        $position = Structure::where('s_id', '=',$item['s_id'])->first();
                        if($position){
                            $position->update($item);
                            $updates++;
                        }else{
                            $position = Structure::create($item);
                            $createds++;
                        }
                    }
                    $this->info('Updated positions '.$updates);
                    $this->info('Created positions '.$createds);
                }else{
                    $this->info($result['message']);
                }
            }
            catch (\Exception $e){
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
                'http://hr.uztrans.uz/api/login',
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
            return false;
        }
    }
}
