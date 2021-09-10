@extends('layouts.main')


@section('page-content')
<div class="box">
    <div class="box-header">
      <button type="button" class="btn btn-primary btn-sm" id="create-new-btn">Add gender</button>
    </div>

    <!-- page-modal -->
    <div class="modal fade" id="new-patient-modal" data-backdrop="static">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New gender details</h4>
            </div>

            <form class='form-horizontal' id="gender-frm">
                <div class="modal-body">

                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="name" class="control-lable">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter gender name">
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
      <table id="gender-tbl" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Gender</th>
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
    loadGenders();
  });


  function loadGenders()
  {
    var url = api_url + 'gender';
    $.ajax({
      type:'GET',
      url:url,
      success: function(gender)
      {
        var gender_row = '';
        var no = 0;
        $('#gender-tbl').dataTable().fnClearTable();
        $('#gender-tbl').dataTable().fnDestroy();
        $.each(gender, function(k, v) {
          no=no+1;
          gender_row+="<tr><td>"+no+"</td><td>"+v.name+"</td></tr>";

             
        }); 
        $("#gender-tbl").append(gender_row);
        $('#gender-tbl').DataTable({
          info:true,
          searching:true,
          bFilter:true,
          lengthChange:true
        });
      }
    });
  }


  $('#create-new-btn').click(function(){
        document.getElementById("gender-frm").reset();
        $('.error-class').empty();
        $('#new-patient-modal').modal('show');
    })

  $('#gender-frm').on('submit', function(event){
        event.preventDefault();
        $('.error-class').empty();
        $.ajax({
            url:api_url+'gender',
            method:"POST",
            data:$('#gender-frm').serialize(),

            success:function(data) {

                if(data['errors']) {
                    $( '#name_error' ).html(data['errors']['name']);
                }
                else {
                    $('#new-patient-modal').modal('hide');
                    loadGenders();

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
    $('#gender-tbl').DataTable({
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
