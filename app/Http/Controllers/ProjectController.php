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
use App\Phonenumber; 

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
       $pcform->reverify = $request->reverify;
       $pcform->email = $request->email;
       $pcform->password = $request->password;
       $pcform->vemail = $request->vemail;
       $pcform->save();
       return back()->with('success','Saved');
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
        $pcform->campaign_name = $campaign->campaignname;
       $pcform->reverify = $request->reverify;
       $pcform->email = $request->email;
       $pcform->password = $request->password;
       $pcform->vemail = $request->vemail;
        $pcform->save();
        return back();
      }
    }

    //Generate csv 
    public function generatecsv($id){
      $alldata = Projectcampaigntable::where('user_id',Auth::User()->id)->where('id',$id)->first();

      //data
      $project_name = $alldata->project_name;
      $campaign_name = $alldata->campaign_name;
      $category = $alldata->category;
      $city = $alldata->city;
      $citytype = $alldata->citytype;
      $phone = $alldata->phone;
      $phonetype = $alldata->phonetype;
      $naming = $alldata->naming;
      $api_row = $alldata->api_row;
      $reverify = $alldata->reverify;
      $email    = $alldata->email;
      $password = $alldata->password;
      $vemail   = $alldata->vemail;
     
      $data = array(
          'projectname' => $project_name, 
          'campaign_name' => $campaign_name,
          'category'      => $category,
          'city'          => $city,
          'city_type'     => $citytype,
          'phone'         => $phonetype,
          'naming'        => $naming,
          'api_row'       => $api_row,
          'reverify'      => $reverify,
          'email'         => $email,
          'password'      => $password,
          'verify_email' => $vemail,
          );
          
          $b = shell_exec("python newfile.py ". escapeshellarg(json_encode($data))." 2>&1");
            $check = Phonenumber::where('campaign_id',$alldata->campaign_id)->count();
            if($check == "0"){
            $verify = new Phonenumber();
            $verify->campaign_id = $alldata->campaign_id;
            $verify->pycheck = $b;
            $verify->save();
            return back();
            }else{
            $getid = Phonenumber::where('campaign_id',$alldata->campaign_id)->first();
            $verify = Phonenumber::find($getid->id);
            $verify->campaign_id = $alldata->campaign_id;
            $verify->pycheck = $b;
            $verify->save();
            return back();
            }
            
        
    }

    
}
