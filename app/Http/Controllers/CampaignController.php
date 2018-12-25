<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Campaign;
use App\Project;
use App\Projectcampaigntable;


class CampaignController extends Controller
{
    //Create Campaign 
    public function index(Request $request){
       
        $validator = Validator::make($request->all(), [
            'campainname' => 'required',
            'project_id'   => 'required',
        ],
        [
            'campainname.required' => 'Campaign name is required.',
            'project_id.required' => 'Please select project.',
        ]);

        if ($validator->fails()) {
            return $data = response()->json($validator->errors(), 422);
        } else {
            //Check this Campaign or project is belongs to this user or not
           $check = Project::where('user_id',Auth::User()->id)->where('id',$request->project_id)->count();
           if($check != "0"){
            $campaign = new Campaign;
            $campaign->campaignname = $request->campainname;
            $campaign->slug = str_slug($request->campainname);
            $campaign->project_id = $request->project_id;
            $check = Campaign::where('project_id',$request->project_id)->where('slug',str_slug($request->campainname))->count();
            if($check == '0'){
                $result = $campaign->save();
                if($result){
                    $projectcampaigntable = new Projectcampaigntable;
                    $projectcampaigntable->campaign_id = $campaign->id;
                    $projectcampaigntable->project_id = $campaign->project_id;
                    $projectcampaigntable->save();
                    return response()->json(['success'=>'Campaign Created']);
                }
              
            }else{
                return $data = response()->json(['success'=>'Sorry, Campaign name already exists'], 422);
            }
           }else{
            return $data = response()->json(['success'=>'Sorry, You are doing something wrong.'], 422);
           }
        }
    }

    public function campaindelete($id){
        $campain = Campaign::find($id);
        $projectcampaigntable = Projectcampaigntable::where('campaign_id','=',$campain->id)->first();
        if(!empty($projectcampaigntable)){
            $projectcampaigntable->delete();
        }
        $campain->delete();
        return back();
    }
}
