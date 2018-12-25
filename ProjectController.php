<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;
use App\Project;
use Auth;
use Validator;
use App\User;
use App\Campaign;
use App\Projectcampaigntable; 

class ProjectController extends Controller
{
    //Project Create function
    public function index(Request $request){
       
        $validator = Validator::make($request->all(), [
            'projectname' => 'required',
        ],
        [
            'projectname.required' => 'Project name is required.',
        ]);

        if ($validator->fails()) {
            return $data = response()->json($validator->errors(), 422);
        } else {
           //Project created
            $project = new Project;
            $project->user_id = Auth::User()->id;
            $project->projectname = $request->projectname;
            $project->slug = str_slug($request->projectname);
            $check = Project::where('user_id',Auth::User()->id)->where('slug',str_slug($request->projectname))->count();
            if($check == '0'){
                $project->save();
                return response()->json(['success'=>'Project Created']);
            }else{
                return $data = response()->json(['success'=>'Sorry, project name already exists'], 422);
            }
           
        }
    }

    public function detail($id){
        //First level detail
        $projects = Project::orderBy('created_at','DESC')->where('user_id',Auth::User()->id)->with('campaigns')->get();
        $projectsc = Project::orderBy('created_at','DESC')->where('user_id',Auth::User()->id)->where('id',$id)->with('campaigns')->get();
        return view('home-detail',['projects' => $projects,'projectsc' => $projectsc]);
    }
    public function detailcampain($id){
        //second level detail
        $projects = Project::orderBy('created_at','DESC')->where('user_id',Auth::User()->id)->with('campaigns')->get();
        $projectsc = Project::orderBy('created_at','DESC')->where('user_id',Auth::User()->id)->where('id',$id)->with('campaigns.pctable')->get();
        
        return view('home-detail-detail',['projects' => $projects,'projectsc' => $projectsc]);
    }

    public function projectcampaign(Request $request, $id){
        
        //dd($request->all());
       $campaign = Campaign::where('id',$id)->with('project')->with('pctable')->first();
       //check where form has id or not
      if(!empty($campaign->pctable)){
       //if has id update data according to user input
       $pcform = Projectcampaigntable::find($campaign->pctable->id);
       $pcform->category = $request->category;
       $pcform->city = $request->city;
       $pcform->citytype = $request->citytype;
       $pcform->phone = $request->phone;
       $pcform->phonetype = $request->phonetype;
       $pcform->naming = $request->naming;
       $pcform->api_row = $request->api_row;
       $pcform->project_id = $campaign->project_id;
       $pcform->project_name = $campaign->project->projectname;
       $pcform->campaign_id = $id;
       $pcform->campaign_name = $campaign->campaignname;
       $pcform->save();
       return back();
      }else{
          //if data is not ceated previously create data
        $pcform = new Projectcampaigntable;
        $pcform->category = $request->category;
        $pcform->city = $request->city;
        $pcform->citytype = $request->citytype;
        $pcform->phone = $request->phone;
        $pcform->phonetype = $request->phonetype;
        $pcform->naming = $request->naming;
        $pcform->api_row = $request->api_row;
        $pcform->project_id = $campaign->project_id;
        $pcform->project_name = $campaign->project->projectname;
        $pcform->campaign_id = $id;
        $pcform->campaign_name = $campaign->campaignname;
        $pcform->user_id = Auth::User()->id;
        $pcform->save();
        return back();
      }
    }

    //Generate csv 
    public function generatecsv($id){
      //$alldata = Projectcampaigntable::where('user_id',Auth::User()->id)->where('id',$id)->first();
      echo shell_exec('public/files/echo.py 2>&1');
    }

    
}
