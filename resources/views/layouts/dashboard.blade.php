<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GMB Wizard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,400,500,700" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
        crossorigin="anonymous">

        <link rel="stylesheet" href="{{ asset(url('public/multiple-select.css')) }}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset(url('public/css/custom.css')) }}" />
    <link rel="stylesheet" href="{{ asset(url('public/css/sweetalert.css')) }}" />
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ asset(url('public/multiple-select.js')) }}"></script>
<script src="{{ asset(url('public/js/sweetalert.min.js')) }}"></script>
</head>

<body>
    <div id="app">

        <nav class="navbar navbar-default navbar-modify">
            <div class="container-fluid">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="{{ asset(url('home')) }}"><img class="iconc" src="{{ asset(url('public/icons/Shape2.png')) }}" /><span>GMB
                                Wizard</span></a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 secondc">
                    <div class="inner-addon right-addon enjoy-cssx" style="display:none;">
                        <img class="iconc" src="{{ asset(url('public/icons/searcht.png')) }}" />
                        <input type="text" placeholder="" class="form-control enjoy-css" />
                    </div>
                     <?php 
                    $check = DB::table('projects')->where('user_id',Auth::User()->id)->count();
                    ?>
                    @if($check != "0")
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                        <img class="iconc" src="{{ asset(url('public/icons/pluss.png')) }}" />
                    </button>
                    @endif
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">

                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="pull-right lout">
                        <a style="color:#5185ef;" class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>


        <main class="py-4">
                <div class="container-fluid">
                      
                            @include('includes.left-sidebar')
                            @yield('content')
                      
                    </div>
        </main>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New Campaign</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                    <div class="form-gorup">
                        <input type="text" class="form-control campainname" name="campaignname" placeholder="Campaign Name">
                    </div>
                    <?php 
                $projects = DB::table('projects')->where('user_id',Auth::User()->id)->orderBy('created_at','DESC')->get();
                ?>
                    <br>
                    <div class="form-group">
                        <select class="form-control" id="project">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)

                            <option value="{{ $project->id }}">{{ $project->projectname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-danger errors" id="campaignmessage" style="display:none;"></div>
                    <div class="alert alert-success quotationsendmessage" style="display:none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="campaigncreate" class="btn btn-primary">Create Campaign</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#campaigncreate').on('click', function () {
            var campainname = $(".campainname").val();
            var project_id = $("#project option:selected").val();

            var campaindata = {
                '_token': $("#token").val(),
                'campainname': $(".campainname").val(),
                'project_id': $("#project option:selected").val(),
            };

            $.ajax({
                type: "POST",
                'data': campaindata,
                'url': "<?php echo url('home/campaign/create'); ?>",
                success: function (data) {
                    $(".campainname").val('');
                    $('.alert-success').text(data.success);
                    $(".alert-success").show();
                    $('#campaignmessage').hide();
                    setTimeout(function () { $('.modal').modal('hide'); }, 1000);
                    window.setTimeout(function () { location.reload() }, 1000)
                },
                error: function (data) {
                    var errors = '';
                    for (datos in data.responseJSON) {
                        errors += data.responseJSON[datos] + '<br>';
                    }
                    $('#campaignmessage').show().html(errors); //this is my div with messages
                }

            })
        });
        $(".enjoy-css").focus(function () {
            $(".enjoy-css").css('width', '200px');
            $(".right-addon ").css('width', '200px');

        });
    </script>
</body>
<div class="modal fade" id="Mashup" tabindex="-1" role="dialog" aria-labelledby="Mashup">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
                {{ Form::open(array('url' => 'home/mashup/save/'.Request()->campaign)) }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Mashup</h4>
            </div>
            <div class="modal-body" style="height: 450px;overflow: scroll;">
            	<table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Level 1</th>
                        <th scope="col">Level 2</th>
                        <th scope="col">Level 3</th>
                        <th scope="col">Category</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        use App\Mashup;
                        //$mashups = DB::table('mashups')->where('projectcampaigntable_id',Request()->campaign)->get();
                        $mashups = Mashup::where('projectcampaigntable_id',Request()->campaign)->get();
                     
                        ?>
                        @foreach($mashups as $glevel1)
                        <tr>
                        <th><div class="form-group"><input value="{{ $glevel1->level1 }}" style="font-weight: normal;" type="text" class="form-control" name="level1[]"></div></th>
                        <td><div class="form-group"><input value="{{ $glevel1->level2 }}" type="text" class="form-control" name="level2[]"></div></td>
                        <td><div class="form-group"><input value="{{ $glevel1->level3 }}" type="text" class="form-control" name="level3[]"></div></td>
                        <td><div class="form-group"><input value="{{ $glevel1->category }}" type="text" class="form-control" name="category[]"></div></td>
                        </tr>
                        @endforeach
                        @for($i = 1; $i<=300; $i++)
                      <tr>
                        <th><div class="form-group"><input style="font-weight: normal;" type="text" class="form-control" name="level1[]"></div></th>
                        <td><div class="form-group"><input type="text" class="form-control" name="level2[]"></div></td>
                        <td><div class="form-group"><input type="text" class="form-control" name="level3[]"></div></td>
                        <td><div class="form-group"><input type="text" class="form-control" name="category[]"></div></td>
                      </tr>
                    @endfor
                    </tbody>
                  </table>
                 
            </div>
            
            
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
          </div>
          {{ Form::close() }}
          
        </div>
      </div>
      <script>
function campaigndelete(cid){
    
    swal({
  title: "Are you sure?",
  text: "You want to delete this Campaign!",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, delete it!",
  closeOnConfirm: false
},
function(){
    window.location = "{{ url('home/campain/delete/') }}/"+cid;
}); 
}
function mashup(id){
$("#Mashup").modal();
$(".projectcampaigntable_id").val(id);
}
$(document).ready(function() {
    $("#add_row").on("click", function() {
        // Dynamic Rows Code
        
        // Get max row id and set new id
        var newid = 0;
        $.each($("#tab_logic tr"), function() {
            if (parseInt($(this).data("id")) > newid) {
                newid = parseInt($(this).data("id"));
            }
        });
        newid++;
        
        var tr = $("<tr></tr>", {
            id: "addr"+newid,
            "data-id": newid
        });
        
        // loop through each td and create new elements with name of newid
        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
            var cur_td = $(this);
            
            var children = cur_td.children();
            
            // add new td and element if it has a nane
            if ($(this).data("name") != undefined) {
                var td = $("<td></td>", {
                    "data-name": $(cur_td).data("name")
                });
                
                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                c.attr("name", $(cur_td).data("name") + newid);
                c.appendTo($(td));
                td.appendTo($(tr));
            } else {
                var td = $("<td></td>", {
                    'text': $('#tab_logic tr').length
                }).appendTo($(tr));
            }
        });
        
        // add delete button and td
        /*
        $("<td></td>").append(
            $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                .click(function() {
                    $(this).closest("tr").remove();
                })
        ).appendTo($(tr));
        */
        
        // add the new row
        $(tr).appendTo($('#tab_logic'));
        
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
        });
});




    // Sortable Code
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
    
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        
        return $helper;
    };
  
    $(".table-sortable tbody").sortable({
        helper: fixHelperModified      
    }).disableSelection();

    $(".table-sortable thead").disableSelection();
    $("#add_row").trigger("click");
});
          </script>
</html>