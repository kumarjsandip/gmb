<div class="col-md-2 col-sm-2 col-xs-12 first-row">
        <div class="row">
            <a class="btn btn-primary" data-toggle="modal" data-target="#newproject" href="#">New Project <img class="iconc pull-right"
                    src="{{ asset(url('public/icons/createp.png')) }}" /></a>
            <ul>
                @foreach($projects as $project)
                <li><a class="nav" href="{{ asset(url('home/project/create/detail/'.$project->id )) }}"><i class="fas fa-circle"></i>{{
                        $project->projectname }} <span class="pull-right">{{ count($project->campaigns) }}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="modal fade" id="newproject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">New Project</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                        <div class="form-group">
                            <input type="text" class="form-control projectname" name="projectname" placeholder="Project Name">
                        </div>
                        <div class="alert alert-danger errors" id="projectcreatemessage" style="display:none;"></div>
                        <div class="alert alert-success quotationsendmessage" style="display:none"></div>
                    </div>
        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="createproject" class="btn btn-primary">Create Project</button>
                    </div>
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
    $(document).ready(function () {
        $('.first-row ul li a').click(function (e) {

            $('.first-row ul li a').removeClass('active');
            $('.first-row ul li a').addClass('nav');
            $(this).toggleClass('nav');
            $(this).toggleClass('active');
            // e.preventDefault();
        });

    });
</script>