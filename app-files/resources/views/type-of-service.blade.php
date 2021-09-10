@extends('layouts.main')


@section('page-content')
<div class="box">
    <div class="box-header">
      <button type="button" class="btn btn-primary btn-sm" id="create-new-btn">Add service</button>
    </div>

    <!-- page-modal -->
    <div class="modal fade" id="services-modal" data-backdrop="static">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New service details</h4>
            </div>

            <form class='form-horizontal' id="services-frm">
                <div class="modal-body">

                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="name" class="control-lable">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter service name">
                            <span class="text-danger error-class" id="name_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- end of page-modal -->


    <!-- page-table -->
    <div class="box-body">
      <table id="services-tbl" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Service Name</th>
        </tr>
        </thead>
        <tbody>

        
        </tbody>

      </table>
    </div>
    <!-- page-table -->
</div>
<!-- /.box -->

<!-- page script -->
<script>

  $( document ).ready(function() {
    loadServices();
  });


  function loadServices()
  {
    var url = api_url + 'services';
    $.ajax({
      type:'GET',
      url:url,
      success: function(gender)
      {
        var data_row = '';
        var no = 0;
        $('#services-tbl').dataTable().fnClearTable();
        $('#services-tbl').dataTable().fnDestroy();
        $.each(gender, function(k, v) {
          no=no+1;
          data_row+="<tr><td>"+no+"</td><td>"+v.name+"</td></tr>";

             
        }); 
        $("#services-tbl").append(data_row);
        $('#services-tbl').DataTable({
          info:true,
          searching:true,
          bFilter:true,
          lengthChange:true
        });
      }
    });
  }


  $('#create-new-btn').click(function(){
        document.getElementById("services-frm").reset();
        $('.error-class').empty();
        $('#services-modal').modal('show');
    })

  $('#services-frm').on('submit', function(event){
        event.preventDefault();
        $('.error-class').empty();
        $.ajax({
            url:api_url+'services',
            method:"POST",
            data:$('#services-frm').serialize(),

            success:function(data) {

                if(data['errors']) {
                    $( '#name_error' ).html(data['errors']['name']);
                }
                else {
                    $('#services-modal').modal('hide');
                    loadServices();

                    new PNotify({
                        title: 'Success',
                        text: data['message'],
                        icon: 'icon-checkmark3',
                        addclass: 'bg-success border-success',
                        type:'success'
                    });
                }
            },
            error:function(data){

                new PNotify({
                    title: 'Error',
                    text: "An error occured,try again",
                    icon: 'icon-checkmark3',
                    addclass: 'bg-danger border-danger',
                    type:'error'
                });
            }
        });
    });


  $(function () {
    $('#services-tbl').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
@endsection
