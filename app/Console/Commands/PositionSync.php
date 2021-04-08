<?php

namespace App\Console\Commands;

use App\Position;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\User;
use Faker\Factory;

class PositionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'position:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Position sync from EHR';

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
                    'http://mhr.loc/api/get-positions',
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
                        $position = Position::where('p_id', '=',$item['p_id'])->first();
                        if($position){
                            if((int)$position->count < (int)$item['count']){
                                $this->info($position->name.' has changed in count');
                                $new_n = (int)$item['count'] - (int)$position->count;
                                for($n = 1; $n <= $new_n;$n++){
                                    $data_arr = [];
                                    $faker = Factory::create();
                                    $e_name = '';
                                    for ($i = 0; $i < 6; $i++) {
                                        $e_name .= $faker->unique()->randomDigit;
                                    }
                                    $data_arr['email'] = $e_name.'@mintrans.uz';
                                    $data_arr['password'] = Hash::make('secret');
                                    $data_arr['status'] = 'vacant';
                                    $data_arr['role_id'] = 2;
                                    $data_arr['name'] = 'Vakant';
                                    $data_arr['surename'] = 'Vakantov';
                                    $user = User::create($data_arr);
                                }
                            }
                            $position->update($item);
                            $updates++;
                        }else{
                            $position = Position::create($item);
                            for($n = 1; $n <= $position->count;$n++){
                                $data_arr = [];
                                $faker = Factory::create();
                                $e_name = '';
                                for ($i = 0; $i < 6; $i++) {
                                    $e_name .= $faker->unique()->randomDigit;
                                }
                                $data_arr['email'] = $e_name.'@mintrans.uz';
                                $data_arr['password'] = Hash::make('secret');
                                $data_arr['status'] = 'vacant';
                                $data_arr['role_id'] = 2;
                                $data_arr['name'] = 'Vakant';
                                $data_arr['surename'] = 'Vakantov';
                                $data_arr['p_id'] = $position->id;
                                $user = User::create($data_arr);
                            }
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
            return false;
        }
    }
}
