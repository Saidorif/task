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
                    'http://mhr.loc/api/get-employees',
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
                        $item['status'] = 'active';
                        $item['role_id'] = 2;
                        $user = User::where(['e_id' => $item['e_id']])->first();
                        if($user != null){
                            if($user->p_id == $item['p_id']){
                                $user->update($item);
                                $updates++;
                            }
                            //Boshqa lavozimga otgan
                            elseif((int)$user->p_id != (int)$item['p_id']){
                                //$position = Position::with('employee')->where(['p_id' => $item['p_id']])->first();
                                $vacant = User::where(['status' => 'vacant','p_id' => $item['p_id']])->first();
                                if($vacant){
                                    $vacant->update($item);
                                }
                                $user->status = 'vacant';
                                $user->name = 'vacant';
                                $user->surename = 'surename';
                                $user->lastname = null;
                                $user->e_id = null;
                                $user->save();
                                $updates++;
                            }
                        }else{
                            $user = User::where(['p_id' => $item['p_id'],'status' => 'vacant'])->first();
                            if($user){
                                $user->update($item);
                                $this->info($user->name.' moved');
                                $createds++;
                            }
                            else{
                                $this->info('Position ID: '.$item['p_id'].'. Employee: '.$item['name'].' e_id: '.$item['e_id']);
                                //$user = User::create($item);
                            }
                        }
                    }
                    $this->info('Updated employees '.$updates);
                    $this->info('Moved employees '.$createds);
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
        //throw $e;
            return false;
        }
    }
}
