<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mashup;
class MashupController extends Controller
{
    public function index(Request $request, $id){

        $check = Mashup::where('projectcampaigntable_id',$id)->count();
        if($check == "0"){
            foreach($request->level1 as $key => $v){
                if(!empty($v)){
                 $mashup = new Mashup;
                 $mashup->projectcampaigntable_id = $id; //This is campaign id
                 $mashup->level1 = $v;
                 $mashup->level2 = $request->level2[$key];
                 $mashup->level3 = $request->level3[$key];
                 $mashup->category = $request->category[$key];
                 $mashup->save();  
                 return back();
                }
             }
        }else{
            foreach($request->level1 as $key => $v){
                if(!empty($v)){
                 $getid = Mashup::where('projectcampaigntable_id',$id)->first();
                 $mashup = Mashup::find($getid->id);
                 $mashup->projectcampaigntable_id = $id; //This is campaign id
                 $mashup->level1 = $v;
                 $mashup->level2 = $request->level2[$key];
                 $mashup->level3 = $request->level3[$key];
                 $mashup->category = $request->category[$key];
                 $mashup->save(); 
                 return back(); 
                }
             }
        }
        
    }
}
