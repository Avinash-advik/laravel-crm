@extends('layouts.master')
@section('title', $title)
@section('content')
    <div class="card mt-5">
        <div class="card-header">
            Custom Field List
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCustomFieldModal">Add New</button>
        </div>
        <span class="alert alert-success d-none" id="alert-success"></span>
        <span class="alert alert-danger d-none" id="alert-danger"></span>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="tabledata">
                </tbody>
            </table>
        </div>
    </div>
    {{-- Add Custom Field Modal --}}
    <div class="modal fade" id="addCustomFieldModal" tabindex="-1" role="dialog" aria-labelledby="addCustomFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomFieldModalLabel">Add Custom Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addCustomFieldForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                <span id="name_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="text">Text</option>
                                    <option value="date">Date</option>
                                    <option value="number">Number</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                                <span id="type_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary addBtn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Custom Field Modal --}}
    <div class="modal fade" id="editCustomFieldModal" tabindex="-1" role="dialog" aria-labelledby="editCustomFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomFieldModalLabel">Edit Custom Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editCustomFieldForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="editname">Name</label>
                                <input type="text" class="form-control" id="editname" name="name" placeholder="Name">
                                <span id="name_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edittype">Type</label>
                                <select name="type" id="edittype" class="form-control">
                                    <option value="text">Text</option>
                                    <option value="date">Date</option>
                                    <option value="number">Number</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                                <span id="type_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary editModalBtn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Custom Field Modal --}}
    <div class="modal fade" id="deleteCustomFieldModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCustomFieldModalLabel">Delete Custom Field</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger deleteModalBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function fetchTableData(){
                var url = "{{route('customfield.listing')}}";
                $.ajax({
                    url: url,
                    method: "GET",
                    contentType: false,
                    processData: false,
                    success: function(resp){
                        if(resp.success == true){
                            result = resp.data;
                            $(document).find('.tabledata').empty();
                            var html = "";
                            $.each(result, function(index, item) {
                                itr = index+1;
                                html += "<tr><td>"+itr+"</td><td>"+item.name+"</td><td>"+item.type+"</td><td><button class='btn btn-success editBtn mr-1' data-id='"+item.id+"' data-toggle='modal' data-target='#editCustomFieldModal'>Edit</button><button class='btn btn-danger deleteBtn' data-id='"+item.id+"' data-toggle='modal' data-target='#deleteCustomFieldModal'>Delete</button></td></tr>";
                            });
                            $(document).find('.tabledata').append(html);
                        }else if(resp.success == false){
                            printErrorMsg(resp.msg);
                        }
                    }
                });
            }

            fetchTableData();

            $("#addCustomFieldForm").on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{route("customfield.store")}}',
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('.addBtn').prop('disabled', true);
                    },
                    complete: function(){
                        $('.addBtn').prop('disabled', false);
                    },
                    success: function(resp){
                        if(resp.success == true){
                            $('#addCustomFieldForm')[0].reset();
                            $('#addCustomFieldModal').modal('hide');
                            printSuccessMsg(resp.msg);
                            fetchTableData();
                        }else if(resp.success == false){
                            printErrorMsg(resp.msg);
                        }else{
                            printValidationErrorMsg(resp.msg);
                        }
                    }
                });
            });

            $(document).on('click','.deleteBtn', function(){
                var that = $(this);
                var id = $(this).attr('data-id');
                $('.deleteModalBtn').on('click', function(){
                    var form_data = new FormData();
                    form_data.append('id', id);
                    var url = "{{route('customfield.delete','customfieldid')}}";
                    url = url.replace('customfieldid', id);
                    $.ajax({
                        url: url,
                        method: "GET",
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            $('.addBtn').prop('disabled', true);
                        },
                        complete: function(){
                            $('.addBtn').prop('disabled', false);
                        },
                        success: function(data){
                            if(data.success == true){
                                $('#deleteCustomFieldModal').modal('hide');
                                fetchTableData();
                                printSuccessMsg(data.msg);
                            }else if(data.success == false){
                                printErrorMsg(data.msg);
                            }else{
                                printValidationErrorMsg(data.msg);
                            }
                        }
                    });
                });
            });

            $(document).on('click','.editBtn', function(){
                var id = $(this).attr('data-id');
                var form_data = new FormData();
                form_data.append('id', id);
                var url = "{{route('customfield.edit','customfieldid')}}";
                url = url.replace('customfieldid', id);
                $.ajax({
                    url: url,
                    method: "GET",
                    contentType: false,
                    processData: false,
                    success: function(resp){
                        if(resp.success == true){
                            result = resp.data;
                            $(document).find('#id').val(result.id);
                            $(document).find('#editname').val(result.name);
                            $(document).find('#edittype').val(result.type);
                        }else if(data.success == false){
                            $('#editCustomFieldModal').modal('hide');
                            printErrorMsg(data.msg);
                        }
                    }
                });
            });

            $("#editCustomFieldForm").on('submit', function(e) {
                e.preventDefault();
                var id = $(this).find('#id').val();
                let formData = new FormData($(this)[0]);
                var url = "{{route('customfield.update','customfieldid')}}";
                url = url.replace('customfieldid', id);
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('.editModalBtn').prop('disabled', true);
                    },
                    complete: function(){
                        $('.editModalBtn').prop('disabled', false);
                    },
                    success: function(data){
                        if(data.success == true){
                            $('#editCustomFieldForm')[0].reset();
                            $('#editCustomFieldModal').modal('hide');
                            printSuccessMsg(data.msg);
                            fetchTableData();
                        }else if(data.success == false){
                            printErrorMsg(data.msg);
                        }else{
                            printValidationErrorMsg(data.msg);
                        }
                    }
                });
            });

            function printValidationErrorMsg(msg){
                $.each(msg, function(field_name, error){
                    $(document).find('#'+field_name+'_error').text(error);
                });
            }
            function printErrorMsg(msg){
                $('#alert-danger').html('');
                $('#alert-danger').removeClass('d-none');
                $('#alert-danger').append(msg);
            }
            function printSuccessMsg(msg){
                $('#alert-success').html('');
                $('#alert-success').removeClass('d-none');
                $('#alert-success').append(msg);
            }
        });
    </script>
@endsection
