@extends('layouts.dashboard')

@section('content')

    
    <div class="col-md-3 col-sm-3 col-xs-12 second-row">
<div class="row">
        <h5 style="color:#666;margin-left: 25px;">Campaigns</h5>
        <ul>
            @foreach($projectsc as $project)
            @foreach($project->campaigns as $campaign)
        <li><a href="{{ asset(url('home/campain/create/detail/'.$project->id )) }}?campaign={{ $campaign->id }}"><i class="fas fa-circle"></i> {{ $campaign->campaignname }}    </a>
            <i onclick="campaigndelete({{ $campaign->id }})" class="fas fa-trash-alt pull-right"></i>
           
        </li>
            @endforeach
            @endforeach
        </ul>
    </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 third-row">
            <div class="">
                    {{ Form::open(array('url' => 'home/project/campaign/data/save/'.Request()->campaign ,'id' => 'tdata')) }}
                <h5 style="color: #666;
                margin-top: 25px;"><?php echo $cname = DB::table('campaigns')->where('id',Request()->campaign)->pluck('campaignname')->first(); ?>
            <input type="submit" value="Save" class="btn btn-primary pull-right savebtn">    
            </h5>
                <hr>
                <br>
               <?php 
                $tabledata = DB::table('projectcampaigntables')->where('campaign_id',Request()->campaign)->first();
                   if(!empty($tabledata->category)){
                    $getc = explode(', ',$tabledata->category);  
                   }
               ?>
                 <div class="form-group row">
                    <label class="control-label col-sm-2" for="email">Category</label>
                    <div class="col-sm-10">
                            <?php $categories = DB::table('categories')->get(); ?>
                        <select multiple="multiple">
                            <?php //$a = "0"; ?> 
                                @foreach ($categories as $item)
                        <option value="{{ $item->displayName }}" @if(!empty($tabledata->category)) @foreach($getc as $c) @if($item->displayName == $c)selected="selected"@endif @endforeach @endif>{{ $item->displayName }}</option>
                        @endforeach  
                        </select>
                    </div>
                </div>
                <input type="hidden" value="" name="category" class="gcategory">
                <div class="form-group row fs">
                    <label for="colFormLabel" class="col-sm-2 control-label">City</label>
                    <div class="col-sm-10">
                        <img class="iconc" src="{{ asset(url('public/icons/search.png')) }}" />
                        <input type="text" required class="form-control" value="@if(!empty($tabledata)) {{ $tabledata->city }} @endif" name="city" id="colFormLabel">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="colFormLabel" class="col-sm-2 control-label">City Source</label>
                    <div class="col-sm-10">
                            <label class="containerc">Loopnet
                                    <input  name="citytype" @if(empty($tabledata->citytype)) checked @endif value="loopnet" @if(!empty($tabledata)) @if($tabledata->citytype == "loopnet") checked @endif @endif type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                                  <label class="containerc">42Floors
                                    <input name="citytype" value="42Floors" @if(!empty($tabledata))  @if($tabledata->citytype == "42Floors") checked @endif @endif  type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                                  <label class="containerc">Local
                                    <input name="citytype" value="Local" @if(!empty($tabledata))  @if($tabledata->citytype == "Local") checked @endif @endif type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                      
                    </div>
                </div>
                <div class="form-group row">
                    <label for="colFormLabel" style="padding: 0;" class="col-sm-2 control-label">Re verify</label>
                    <div class="col-sm-10">
                              <label class="containerc">
                                    <input name="reverify" value="Yes" @if(!empty($tabledata))  @if($tabledata->reverify == "Yes") checked @endif @endif type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                             
                    </div>
                </div>
                
                <div class="form-group row fsp">
                    <label for="colFormLabel" class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10">
                        <img class="iconc" src="{{ asset(url('public/icons/search.png')) }}" />
                        <input type="text" name="phone" value="@if(!empty($tabledata)) {{ $tabledata->phone }} @endif" class="form-control colFormLabel">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="colFormLabel" class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                             
                              <label class="containerc">Twilio
                                <input name="phonetype" @if(empty($tabledata->phonetype )) checked @endif value="Twilio" @if(!empty($tabledata))  @if($tabledata->phonetype == "Twilio") checked @endif @endif type="checkbox">
                                <span class="checkmark"></span>
                              </label>
                              <label class="containerc">Plivo
                                    <input name="phonetype" value="Plivo" @if(!empty($tabledata))  @if($tabledata->phonetype == "Plivo") checked @endif @endif type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                            
                    </div>
                </div>
                <div class="form-group row">
                    <label for="colFormLabel" class="col-sm-2 control-label">Naming</label>
                    <div class="col-sm-10">
                              <label class="containerc">Templates
                                <input name="naming" @if(empty($tabledata->naming)) checked @endif value="Templates" @if(!empty($tabledata))  @if($tabledata->naming == "Templates") checked @endif @endif type="checkbox">
                                <span class="checkmark"></span>
                              </label>
                              <label class="containerc">Industry Mashup
                                    <input name="naming" value="Industry Mashup" @if(!empty($tabledata))  @if($tabledata->naming == "Industry Mashup") checked @endif @endif type="checkbox">
                                    <span class="checkmark"></span>
                                  </label>
                             
                    </div>
                </div>
                <div class="form-group row fsp">
                    <label for="colFormLabel" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        
                    <input type="email" style="padding-left: 10px !important;" required name="email" value="@if(!empty($tabledata)) {{ $tabledata->email }} @endif" class="form-control colFormLabel">
                    </div>
                </div>
                <div class="form-group row fsp">
                    <label for="colFormLabel" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                       
                        <input type="password" style="padding-left: 10px !important;" required name="password" value="@if(!empty($tabledata)) {{ $tabledata->password }} @endif" class="form-control colFormLabel">
                    </div>
                </div>
                <div class="form-group row fsp">
                    <label for="colFormLabel" class="col-sm-3 control-label">Verification Email</label>
                    <div class="col-sm-9">
                       
                        <input type="email" style="padding-left: 10px !important;" required name="vemail" value="@if(!empty($tabledata)) {{ $tabledata->vemail }} @endif" class="form-control colFormLabel">
                    </div>
                </div>

                <div class="form-group row fsp">
                        <label for="colFormLabel" class="col-sm-3 control-label">Number of CSVs</label>
                        <div class="col-sm-9">
                                <input type="text" name="api_row" value="@if(!empty($tabledata)) {{ $tabledata->api_row }} @endif" style="
                                border-radius: 40px;
                                margin-top: -5px;background: #eeeeee;border: 0;"
                                        class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                    <label for="colFormLabel" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                            <button type="button" class="btn btn-info" data-toggle="modal" onclick="mashup(@if(!empty($tabledata->id)){{ $tabledata->id }}@endif)">
                                    Mashup
                                  </button>
                    </div>
                    </div>
              
                {{ Form::close() }}
                <div class="listing col-md-12 verifylisting">
                    @if(!empty($tabledata))
                    <a class="gcsv" href="{{ asset(url('home/generate/csv/'.$tabledata->id )) }}">
                        @else 
                        <a class="gcsv" href="#">
                        @endif
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                Generate CSV
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <img id="loading" class="vicon iconc pull-right" src="{{ asset(url('public/icons/loading.png')) }}" />
                            </div>
                        </div>
                    </a>
                </div>
    
            </div>
    
        </div>
        <?php 
        $phoneverify = DB::table('phonenumbers')->where('campaign_id',Request()->campaign)->first();
        ?>
        <div class="col-md-3 col-sm-3 col-xs-12 forth-row" @if(!empty($phoneverify)) style="opacity:1;" @endif>
                <div class="">
                    <h6>Once listing verification is done, CSV will auto-populate bellow.</h6>
                   
                    
                    <div class="listing col-md-12">
                        <div class="row">
                                @if(!empty($phoneverify))
                            <div class="col-md-12 col-xs-12">
                                Verification triggered # : 
                                <p>{{ $phoneverify->pycheck }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                  
                    <div class="conectgoogle col-md-12">
                        <p>Connecting with googleAuth</p>
                        <p>Verifying post cards..</p>
                        <p>Verifying Phone Lising</p>
                    </div>
                    <div class="listing col-md-12 report">
                        <a href="#">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <span><img class="iconc" src="{{ asset(url('public/icons/download.png')) }}" /></span>
                                    Report
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-6">
                                                    <img class="iconc" src="{{ asset(url('public/icons/call.png')) }}" />
                                                    4
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="row">
                                                        <img class="iconc" src="{{ asset(url('public/icons/location.png')) }}" />
                                                        2
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="listing col-md-12 verifylisting">
                    <a href="#">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    ReVerify Listing
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <img class="iconc pull-right" src="{{ asset(url('public/icons/verify.png')) }}" />
                                </div>
                            </div>
                        </a>
                    </div>
        
                </div>
            </div>
           
           
<script>
    $('#createproject').on('click', function () {
        var projectname = $(".projectname").val();
        var projectdata = {
            '_token': $("#token").val(),
            'projectname': $(".projectname").val(),
        };
        $.ajax({
            type: "POST",
            'data': projectdata,
            'url': "<?php echo url('home/project/create'); ?>",
            success: function (data) {
                $(".projectname").val('');
                $('.alert-success').text(data.success);
                $(".alert-success").show();
                $('#projectcreatemessage').hide();
                setTimeout(function () { $('.modal').modal('hide'); }, 1000);
                window.setTimeout(function () { location.reload() }, 1000)
            },
            error: function (data) {
                var errors = '';
                for (datos in data.responseJSON) {
                    errors += data.responseJSON[datos] + '<br>';
                }
                $('#projectcreatemessage').show().html(errors); //this is my div with messages
            }

        })
    });
    $('input[type="checkbox"]').on('change', function() {
    $('input[name="' + this.name + '"]').not(this).prop('checked', false);
});
</script>
 <script>
$(".vicon").hide();
$(".gcsv").click(function(){
    $(".vicon").show();
    //event.preventDefault();
    //document.getElementById("tdata").submit();
     // window.location = $(this).attr('href');  
})
/*
var res = $(".result").text().length;
if(res != "0"){
    var addressValue = $(".gcsv").attr("href");
   // alert(addressValue);
   // window.location = $(this).attr(addressValue);  
    $(location).attr('href', addressValue)
}*/
</script>
<script>
    $("select").multipleSelect({
        filter: true
    });
    $("li").click(function(){
var a = $("#test").text();
$(".gcategory").val(a);
});
</script>
@endsection