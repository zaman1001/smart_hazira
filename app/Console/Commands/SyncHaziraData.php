<?php

namespace App\Console\Commands;

use App\Models\Attendence_log;
use App\Models\Organizations;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncHaziraData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-hazira-data {offset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offset = $this->argument('offset');
        $limit = 10;

        if($offset>15){
            exit;
        } elseif($offset>0){
            $offset=($offset*$limit)+$limit;
        }
        try{
            $attendenceObj = Attendence_log::where('is_updated',0)->offset($offset)->limit($limit)->get(['id','devicename']);
            if(count($attendenceObj) > 0){

                foreach($attendenceObj as $aObj){
                    $devicename = trim($aObj->devicename);
                    $orgObj = Organizations::where('devicename',$devicename)->first(['id','company_id','company_branch_id']);

                    Attendence_log::where('id',$aObj->id)->where('devicename',$devicename)
                        ->update([
                            'organization_id' => $orgObj->id,
                            'company_id' => $orgObj->company_id,
                            'company_branch_id' => $orgObj->company_branch_id,
                            'is_updated' => 1,
                        ]);
                }
            }
        }
        catch(\Exception $e)
        {
            Log::error($e->getMessage() . 'F:' . $e->getFile() . 'L:' . $e->getLine());
        }
    }
}