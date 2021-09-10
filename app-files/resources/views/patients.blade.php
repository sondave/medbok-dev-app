@extends('layouts.main')


@section('page-content')
<div class="box">
    <div class="box-header">
      <button type="button" class="btn btn-primary btn-sm" id="create-new-btn">Add new patient</button>
    </div>

    <!-- page-modal -->
    <div class="modal fade" id="patient-modal" data-backdrop="static">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New patient details</h4>
            </div>

            <form class='form-horizontal' id="patient-frm">
                <div class="modal-body">

                    
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="name" class="control-lable">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter patient name">
                            <span class="text-danger error-class" id="name_error"></span>
                        </div>
                        <div class="col-sm-4">
                            <label for="dob" class="control-lable">Date of birth</label>
                            <input type="date" name="dob" class="form-control">
                            <span class="text-danger error-class" id="dob_error"></span>
                        </div>
                        <div class="col-sm-4">
                            <label for="gender" class="control-lable">Gender</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value="" disabled="" selected>Select gender</option>
                            </select>
                            <span class="text-danger error-class" id="gender_error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                            <label for="type_of_service" class="control-lable">Type of service</label>
                            <select class="form-control" name="type_of_service" id="type_of_service">
                                <option value="" disabled="" selected>Select service type</option>
                            </select>
                            <span class="text-danger error-class" id="type_of_service_error"></span>
                        </div>

                        <div class="col-sm-8">
                            <label for="comments" class="control-lable">General coments</label>
                            <textarea class="form-control" rows="1" name="comments" > </textarea> 
                            <span class="text-danger error-class" id="comments_error"></span>
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
      <table id="patients-tbl" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>Name</th>
          <th>Date Of Birth</th>
          <th>Gender</th>
          <th>Type Of Service</th>
          <th>General comments</th>
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
    loadPatients();
    populateGenderField()
    populateServicesField()
  });

  function loadPatients()
  {
    var url = api_url + 'patients';
    $.ajax({
      type:'GET',
      url:url,
      success: function(response)
      {


        var table_row = '';
        $('#patients-tbl').dataTable().fnClearTable();
        $('#patients-tbl').dataTable().fnDestroy();

        $.each(response, function(k, v) {

          table_row+="<tr><td>"+v.name+"</td><td>"+v.dob+"</td><td>"+v.gender+"</td><td>"+v.service+"</td><td>"+v.comments+"</td></tr>";

             
        }); 
        $("#patients-tbl").append(table_row);
        $('#patients-tbl').DataTable({
          info:true,
          searching:true,
          bFilter:true,
          lengthChange:true
        });
      }
    });
  }

  $('#create-new-btn').click(function(){
      document.getElementById("patient-frm").reset();
      $('.error-class').empty();
      $('#patient-modal').modal('show');
  })


  function populateGenderField()
  {
    var gender_options = "";
    $.get(api_url+'gender', function (data) {

      gender_options+="<option value='' selected disabled>"+'Select gender'+"</option>";

      $.each(data, function(k, v) {

        gender_options+="<option value='"+v.id+"'>"+v.name+"</option>";     

      }); 

      $("#gender").html(gender_options);
    }); 
  }

  function populateServicesField()
  {
    var services_options = "";
    $.get(api_url+'services', function (data) {

      services_options+="<option value='' selected disabled>"+'Select service type'+"</option>";

      $.each(data, function(k, v) {

        services_options+="<option value='"+v.id+"'>"+v.name+"</option>";     

      }); 

      $("#type_of_service").html(services_options);
    }); 
  }

  $('#patient-frm').on('submit', function(event){
        event.preventDefault();
        $('.error-class').empty();
        $.ajax({
            url:api_url+'patients',
            method:"POST",
            data:$('#patient-frm').serialize(),

            success:function(data) {

                if(data['errors']) {
                  $( '#name_error' ).html(data['errors']['name']);
                  $( '#dob_error' ).html(data['errors']['dob']);
                  $( '#gender_error' ).html(data['errors']['gender']);
                  $( '#type_of_service_error' ).html(data['errors']['type_of_service']);
                  $( '#comments_error' ).html(data['errors']['comments']);
                  
                }
                else {
                    $('#patient-modal').modal('hide');
                    loadPatients();

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
    $('#patients-tbl').DataTable({
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
