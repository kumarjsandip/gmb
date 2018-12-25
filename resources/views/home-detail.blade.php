@extends('layouts.dashboard')

@section('content')

    
    <div class="col-md-3 col-sm-3 col-xs-12 second-row">
<div class="row">
        <h6><img class="iconc" src="{{ asset(url('public/icons/date.png')) }}" />Date</h6>
        <ul>
            @foreach($projectsc as $project)
            @foreach($project->campaigns as $campaign)
        <li><a href="{{ asset(url('home/campain/create/detail/'.$project->id )) }}?campaign={{ $campaign->id }}"><i class="fas fa-circle"></i> {{ $campaign->campaignname }} </a>
            <i onclick="campaigndelete({{ $campaign->id }})" class="fas fa-trash-alt pull-right"></i>
        </li>
            @endforeach
            @endforeach
        </ul>
    </div>
    </div>
    
   

<script>
    // Get the <datalist> and <input> elements.
    var dataList = document.getElementById('json-datalist');
    var input = document.getElementById('ajax');

    // Create a new XMLHttpRequest.
    var request = new XMLHttpRequest();

    // Handle state changes for the request.
    request.onreadystatechange = function (response) {
        if (request.readyState === 4) {
            if (request.status === 200) {
                // Parse the JSON
                var jsonOptions = JSON.parse(request.responseText);

                // Loop over the JSON array.
                jsonOptions.forEach(function (item) {
                    // Create a new <option> element.
                    var option = document.createElement('option');
                    // Set the value using the item in the JSON array.
                    option.value = item;
                    // Add the <option> element to the <datalist>.
                    dataList.appendChild(option);
                });

                // Update the placeholder text.
                input.placeholder = "e.g. datalist";
            } else {
                // An error occured :(
                input.placeholder = "Couldn't load datalist options :(";
            }
        }
    };


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

</script>
@endsection