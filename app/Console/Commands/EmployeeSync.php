<?php

namespace App\Console\Commands;

use App\Employee;
use App\Position;
use App\User;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EmployeeSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Employee sync from EHR';

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
                    'http://hr.uztrans.uz/api/get-employees',
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
                        $item['e_id'] = $item['id'];
                        $faker = Factory::create();
                        $e_name = '';
                        for ($i = 0; $i < 6; $i++) {
                            $e_name .= $faker->unique()->randomDigit;
                        }
                        $item['email'] = $e_name.'@mintrans.uz';
                        $item['password'] = Hash::make('secret');
                        $item['status'] = 'active';
                        $item['role_id'] = 2;
                        //$position = Position::where(['p_id' => $item['p_id']])->first();
                        $user = User::where(['e_id' => $item['e_id']])->first();
                        if($user != null){
                            /*
                             * Agar boshqa lavozimga otgan bolsa?
                             * Agar ishdan boshagan bolsa?
                            */
                            if($user->p_id == $item['p_id']){
                                $user->update($item);
                                $updates++;
                            }
                        }else{
                            $user = User::create($item);
                            $createds++;
                        }
                    }
                    $this->info('Updated employees '.$updates);
                    $this->info('Created employees '.$createds);
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
        //throw $e;
            return false;
        }
    }
}
