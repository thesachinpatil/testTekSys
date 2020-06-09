<!DOCTYPE html>
  
<html lang="en">
<head>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Exercise1</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>
    
    <div class="container">
        <h2>Exercise1</h2>
        <br>
        <a href="javascript:void(0)" class="btn btn-info ml-3" id="create-new-router">Add New</a>
        <br><br>
        
        <table class="table table-bordered table-striped" id="exercise1">
            <thead>
                <tr>
                    <!-- <th class="hidden">#</th> -->
                    <th>#</th>
                    <th>Sap ID</th>
                    <th>HostName</th>
                    <th>Type</th>
                    <th>LoopBack</th>
                    <th>MacAddress</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div class="modal fade" id="ajax-router-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="routerCrudModal"></h4>
                </div>
                <div class="modal-body">
                    <form id="routerForm" name="routerForm" class="form-horizontal">
                        <input type="hidden" name="router_id" id="router_id">
                        <div class="form-group">
                            <label for="sap_id" class="col-sm-2 control-label">Sap ID</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="sap_id" name="sap_id" placeholder="Enter Sap ID" value="" maxlength="18" required="required">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">HostName</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="host_name" name="host_name" placeholder="Enter HostName" value="" maxlength="14" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="loopback" class="col-sm-2 control-label">LoopBack</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="loopback" name="loopback" placeholder="Enter Loopback" value="" pattern='((^|\.)((25[0-5]_*)|(2[0-4]\d_*)|(1\d\d_*)|([1-9]?\d_*))){4}_*$' required="required">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="type" name="type">
                                    <option value="AG1">AG1</option>
                                    <option value="CSS">CSS</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Mac Address</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="mac_address" name="mac_address" pattern='^(([\da-fA-F]{2}[-:]){5}[\da-fA-F]{2})$' placeholder="Enter Mac Address" value="" required="required">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes
                        </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

<script>
    var SITEURL = '{{URL::to('')}}' + '/';
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#exercise1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: SITEURL + "router-list",
                type: 'GET',
            },
            columns: [
                // { data: 'id', name: 'id', 'visible': false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'sap_id', name: 'sap_id' },
                { data: 'host_name', name: 'host_name' },
                { data: 'type', name: 'type' },
                { data: 'loopback', name: 'loopback' },
                { data: 'mac_address', name: 'mac_address' },
                { data: 'action', name: 'action', orderable: false },
            ],
            order: [[0, 'desc']]
        });
        
        // add
        $('#create-new-router').click(function () {
            $('#btn-save').val("create-router");
            $('#router_id').val('');
            $('#routerForm').trigger("reset");
            $('#routerCrudModal').html("Add New Router");
            $('#ajax-router-modal').modal('show');
        });
        
        $('body').on('click', '.edit-router', function () {
            var router_id = $(this).data('id');
            $.get('router-list/edit/' + router_id, function (data) {
                $('#sap_id-error').hide();
                $('#host_name-error').hide();
                $('#type-error').hide();
                $('#routerCrudModal').html("Edit Router");
                $('#btn-save').val("edit-router");
                $('#ajax-router-modal').modal('show');
                $('#router_id').val(data.id);
                $('#sap_id').val(data.sap_id);
                $('#host_name').val(data.host_name);
                $('#type').val(data.type);
                $('#loopback').val(data.loopback);
                $('#mac_address').val(data.mac_address);
            })
        });
        
        $('body').on('click', '#delete-router', function () {
            var router_id = $(this).data("id");
            if(confirm("Are You sure want to delete !")){
                $.ajax({
                    type: "get",
                    url: SITEURL + "router-list/delete/"+router_id,
                    success: function (data) {
                        var oTable = $('#exercise1').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
    
    if ($("#routerForm").length > 0) {
        $("#routerForm").validate({
            submitHandler: function(form) {
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');

                $.ajax({
                    data: $('#routerForm').serialize(),
                    url: SITEURL + "router-list/store",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.status == 1) {
                            $('#routerForm').trigger("reset");
                            $('#ajax-router-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            var oTable = $('#exercise1').dataTable();
                            oTable.fnDraw(false);
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                    }
                });
            }
        });
    }
</script>
</body>
</html>