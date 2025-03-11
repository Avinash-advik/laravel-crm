@extends('layouts.master')
@section('title', $title)
@section('content')
    <div class="card mt-5">
        <div class="card-header">
            Contact List
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addContactModal">Add New</button>
        </div>
        <span class="alert alert-success d-none" id="alert-success"></span>
        <span class="alert alert-danger d-none" id="alert-danger"></span>
        <div class="card-body">
            <div class="input-group mb-3 col-md-4 offset-md-8">
                <input type="text" class="form-control" name="searchValue" id="searchValue" placeholder="Search">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary searchBtn" type="button">Search</button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Profile</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Merged</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="tabledata">
                    {{-- @if (count($results) > 0)
                    @foreach ($results as $result)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$result->profile}}</td>
                            <td>{{$result->name}}</td>
                            <td>{{$result->email}}</td>
                            <td>{{$result->phone}}</td>
                            <td>{{$result->gender}}</td>
                            <td>
                                <button class="btn btn-success editBtn" data-id="{{$result->id}}" data-toggle="modal" data-target="#editContactModal">Edit</button>
                                <button class="btn btn-danger deleteBtn" data-id="{{$result->id}}" data-toggle="modal" data-target="#deleteContactModal">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="7">No records found</td>
                        </tr>
                    @endif --}}

                </tbody>
            </table>
        </div>
    </div>
    {{-- Add Contact Modal --}}
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactModalLabel">Add Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addContactForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                <span id="name_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com">
                                <span id="email_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="9123XXXXXX">
                                <span id="phone_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <div>
                                    <label for="gender">Gender</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender1" value="Male">
                                    <label class="form-check-label" for="gender1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender2" value="Female">
                                    <label class="form-check-label" for="gender2">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender3" value="Other">
                                    <label class="form-check-label" for="gender3">Other</label>
                                </div>
                                <span id="gender_error" class="text-danger d-block"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="profile_image">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                                <span id="profile_image_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="additional_file">Document</label>
                                <input type="file" class="form-control" id="additional_file" name="additional_file">
                                <span id="additional_file_error" class="text-danger"></span>
                            </div>
                            @if (count($custom_fields) > 0)
                                <div class="col-md-12">
                                    <h4>Additional Info</h4>
                                </div>
                                @foreach ($custom_fields as $field)
                                    <div class="form-group col-md-6">
                                        <label for="{{$field->name}}">{{$field->name}}</label>
                                        @if ($field->type == 'textarea')
                                            <textarea name="custom[{{$field->id}}_{{$field->name}}]" id="{{$field->name}}" class="form-control"></textarea>
                                        @else
                                            <input type="{{$field->type}}" name="custom[{{$field->id}}_{{$field->name}}]" class="form-control">
                                        @endif
                                    </div>
                                @endforeach
                            @endif
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

    {{-- Edit Contact Modal --}}
    <div class="modal fade" id="editContactModal" tabindex="-1" role="dialog" aria-labelledby="editContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactModalLabel">Edit Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editContactForm" enctype="multipart/form-data">
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
                                <label for="editemail">Email</label>
                                <input type="email" class="form-control" id="editemail" name="email[]" placeholder="example@gmail.com">
                                <span id="email_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editphone">Phone</label>
                                <input type="number" class="form-control" id="editphone" name="phone[]" placeholder="9123XXXXXX">
                                <span id="phone_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <div>
                                    <label for="editgender">Gender</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="editgender1" value="Male">
                                    <label class="form-check-label" for="editgender1">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="editgender2" value="Female">
                                    <label class="form-check-label" for="editgender2">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="editgender3" value="Other">
                                    <label class="form-check-label" for="editgender3">Other</label>
                                </div>
                                <span id="gender_error" class="text-danger d-block"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editprofile_image">Profile Image</label>
                                <input type="file" class="form-control" id="editprofile_image" name="profile_image">
                                <span id="profile_image_error" class="text-danger"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editadditional_file">Document</label>
                                <input type="file" class="form-control" id="editadditional_file" name="additional_file">
                                <span id="additional_file_error" class="text-danger"></span>
                            </div>
                            @if (count($custom_fields) > 0)
                                <div class="col-md-12">
                                    <h4>Additional Info</h4>
                                </div>
                                @foreach ($custom_fields as $field)
                                    <div class="form-group col-md-6">
                                        <label for="{{$field->name}}">{{$field->name}}</label>
                                        @if ($field->type == 'textarea')
                                            <textarea name="custom[{{$field->id}}_{{$field->name}}]" id="edit{{$field->name}}" class="form-control"></textarea>
                                        @else
                                            <input type="{{$field->type}}" name="custom[{{$field->id}}_{{$field->name}}]" class="form-control" id="edit{{$field->name}}">
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="row editsecondarycontact d-none">

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

    {{-- Delete Contact Modal --}}
    <div class="modal fade" id="deleteContactModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteContactModalLabel">Delete Contact</h5>
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

    {{-- Merge Contact Modal --}}
    <div class="modal fade" id="mergeContactModal" tabindex="-1" role="dialog" aria-labelledby="mergeContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mergeContactModalLabel">Merge Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="mergeContactForm">
                    @csrf
                    <input type="hidden" name="secondary_id" id="secondaryContactId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="masterContactId">Select Master Contact</label>
                                <select name="master" id="masterContactId" class="form-control">

                                </select>
                                <span id="master_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger mergeModalBtn">Merge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Contact Modal --}}
    <div class="modal fade" id="viewContactModal" tabindex="-1" role="dialog" aria-labelledby="viewContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewContactModalLabel">View Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-2 viewprofileimage">

                            </div>
                            <div class="form-group col-md-5">
                                <h6>Name</h6>
                                <p id="viewname"></p>
                            </div>
                            <div class="form-group col-md-5">
                                <h6>Email</h6>
                                <p id="viewemail"></p>
                            </div>
                            <div class="form-group col-md-4">
                                <h6>Phone</h6>
                                <p id="viewphone"></p>
                            </div>
                            <div class="form-group col-md-4">
                                <h6>Gender</h6>
                                <p id="viewgender"></p>
                            </div>
                            <div class="form-group col-md-4 viewaddfile">
                                <h6>Document</h6>
                            </div>
                            @if (count($custom_fields) > 0)
                                <div class="col-md-12">
                                    <h4>Additional Info</h4>
                                </div>
                                @foreach ($custom_fields as $field)
                                    <div class="form-group col-md-4">
                                        <h6>{{$field->name}}</h6>
                                        <p id="view{{$field->name}}"></p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="row secondarycontact d-none">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function fetchTableData(){
                var search = $(document).find('#searchValue').val();
                var url = "{{route('contact.listing','search')}}";
                    url = url.replace('search', search);
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
                                var imghtml = "";
                                if(item.profile_image !== null){
                                    var profileurl = "{{ asset('profileimage') }}";
                                    profileurl = profileurl.replace('profileimage', item.profile_image);
                                    imghtml = "<img src='"+profileurl+"' class='img-fluid' alt='profile' />";
                                }
                                merged_name = '-';
                                actions = '-';
                                if(item.merged_into !== null){
                                    merged_name = "Merged into <b>"+item.merged_into.name+"</b>";
                                }else{
                                    merged_name = "<button class='btn btn-warning mergeBtn mb-1' data-id='"+item.id+"' data-toggle='modal' data-target='#mergeContactModal'>Merge</button>";

                                    actions = "<button class='btn btn-secondary viewBtn mr-1 mb-1' data-id='"+item.id+"' data-toggle='modal' data-target='#viewContactModal'>View</button><button class='btn btn-success editBtn mr-1 mb-1' data-id='"+item.id+"' data-toggle='modal' data-target='#editContactModal'>Edit</button><button class='btn btn-danger deleteBtn mr-1 mb-1' data-id='"+item.id+"' data-toggle='modal' data-target='#deleteContactModal'>Delete</button>";
                                }

                                html += "<tr><td>"+itr+"</td><td>"+imghtml+"</td><td>"+item.name+"</td><td>"+item.email+"</td><td>"+item.phone+"</td><td>"+item.gender+"</td><td>"+merged_name+"</td><td>"+actions+"</td></tr>";
                            });
                            $(document).find('.tabledata').append(html);
                        }else if(resp.success == false){
                            printErrorMsg(resp.msg);
                        }
                    }
                });
            }
            fetchTableData();

            $(document).on('click', '.searchBtn', function() {
                fetchTableData();
            });

            $("#addContactForm").on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{route("contact.store")}}',
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
                            $('#addContactForm')[0].reset();
                            $('#addContactModal').modal('hide');
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
                    var url = "{{route('contact.delete','contactid')}}";
                    url = url.replace('contactid', id);
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
                                $('#deleteContactModal').modal('hide');
                                fetchTableData();
                                printSuccessMsg(data.msg);
                            }else if(data.success == false){
                                $('#deleteContactModal').modal('hide');
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
                var url = "{{route('contact.edit','contactid')}}";
                url = url.replace('contactid', id);
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
                            $(document).find('.editsecondarycontact').addClass('d-none');
                            $(document).find('.editsecondarycontact').empty();
                            let emails = result.email.split(",");
                            let phones = result.phone.split(",");
                            var secondaryhtml = "";
                            var primaryemail = "";
                            var primaryphone = "";
                            $.each(emails, function(index, email) {
                                if(index == 0){
                                    primaryemail = email;
                                }else{
                                    secondaryhtml += '<div class="form-group col-md-6"><label for="editemail'+index+'">Secondary Email</label><input type="email" class="form-control" id=""editemail'+index+'"" name="email[]" placeholder="example@gmail.com" value="'+email+'"><span id="email_error" class="text-danger"></span></div>';
                                }
                            });
                            $.each(phones, function(index, phone) {
                                if(index == 0){
                                    primaryphone = phone;
                                }else{
                                    secondaryhtml += '<div class="form-group col-md-6"><label for="editphone'+index+'">Secondary Phone</label><input type="number" class="form-control" id=""editphone'+index+'"" name="phone[]" placeholder="9127XXXXXX" value="'+phone.trim()+'"><span id="phone_error" class="text-danger"></span></div>';
                                }
                            });
                            $(document).find('#editemail').val(primaryemail);
                            $(document).find('#editphone').val(primaryphone);
                            if(secondaryhtml != ""){
                                $(document).find('.editsecondarycontact').removeClass('d-none');
                                $(document).find('.editsecondarycontact').html(secondaryhtml);
                            }
                            $('#editContactForm').find('input[name="gender"][value="'+result.gender+'"]').attr('checked', 'checked');
                            $.each(result.contact_custom_values, function(index, item) {
                                var name = item.custom_field_id+'_'+item.custom_field.name;
                                if(item.custom_field.type == 'textarea'){
                                    $('#editContactModal').find('textarea[name="custom['+name+']"]').val(item.value);
                                }else{
                                    $('#editContactModal').find('input[name="custom['+name+']"]').val(item.value);
                                }

                            });
                        }else if(resp.success == false){
                            $('#editContactModal').modal('hide');
                            printErrorMsg(data.msg);
                        }
                    }
                });
            });

            $("#editContactForm").on('submit', function(e) {
                e.preventDefault();
                var id = $(this).find('#id').val();
                let formData = new FormData($(this)[0]);
                var url = "{{route('contact.update','contactid')}}";
                url = url.replace('contactid', id);
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
                            $('#editContactForm')[0].reset();
                            $('#editContactModal').modal('hide');
                            printSuccessMsg(data.msg);
                            fetchTableData();
                        }else if(data.success == false){
                            $('#editContactModal').modal('hide');
                            printErrorMsg(data.msg);
                        }else{
                            printValidationErrorMsg(data.msg);
                        }
                    }
                });
            });

            $(document).on('click', '.mergeBtn', function() {
                var sec_id = $(this).attr('data-id');
                $('#mergeContactModal').find('#secondaryContactId').val(sec_id);
                // Remove current secondary id from the master contact list
                var url = "{{route('contact.mastercontact')}}";
                $.ajax({
                    url: url,
                    method: "GET",
                    contentType: false,
                    processData: false,
                    success: function(resp){
                        if(resp.success == true){
                            // masterContactId
                            result = resp.data;
                            $(document).find('#id').val(result.id);
                            $('#masterContactId').empty();
                            var options = "";
                            $.each(result, function(index, item) {
                                if(item.id != sec_id){
                                    options += "<option value='"+item.id+"'>"+item.name+"</option>";
                                }
                            });
                            $('#masterContactId').append(options);
                        }else if(resp.success == false){
                            $('#editContactModal').modal('hide');
                            printErrorMsg(data.msg);
                        }
                    }
                });
            });

            $("#mergeContactForm").on('submit', function(e) {
                e.preventDefault();
                var id = $(this).find('#secondaryContactId').val();
                let formData = new FormData($(this)[0]);
                var url = "{{route('contact.merge')}}";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('.mergeModalBtn').prop('disabled', true);
                    },
                    complete: function(){
                        $('.mergeModalBtn').prop('disabled', false);
                    },
                    success: function(data){
                        if(data.success == true){
                            $('#mergeContactForm')[0].reset();
                            $('#mergeContactModal').modal('hide');
                            printSuccessMsg(data.msg);
                            fetchTableData();
                        }else if(data.success == false){
                            $('#mergeContactModal').modal('hide');
                            printErrorMsg(data.msg);
                        }else{
                            printValidationErrorMsg(data.msg);
                        }
                    }
                });
            });

            $(document).on('click','.viewBtn', function(){
                var id = $(this).attr('data-id');
                var form_data = new FormData();
                form_data.append('id', id);
                var url = "{{route('contact.edit','contactid')}}";
                url = url.replace('contactid', id);
                $.ajax({
                    url: url,
                    method: "GET",
                    contentType: false,
                    processData: false,
                    success: function(resp){
                        if(resp.success == true){
                            $(document).find('.viewprofileimage').empty();
                            $(document).find('.viewaddfile').empty();
                            $(document).find('.viewaddfile').append('<h6>Document</h6>');
                            result = resp.data;
                            $(document).find('#viewname').text(result.name);
                            let emails = result.email.split(",");
                            var emailhtml = "";
                            $.each(emails, function(index, email) {
                                if(index == 0){
                                    emailhtml += "<b>Primary: </b>"+email;
                                }else{
                                    emailhtml += "</br><b>Secondary: </b>"+email
                                }
                            });
                            $(document).find('#viewemail').html(emailhtml);

                            let phones = result.phone.split(",");
                            var phonehtml = "";
                            $.each(phones, function(index, phone) {
                                if(index == 0){
                                    phonehtml += "<b>Primary: </b>"+phone;
                                }else{
                                    phonehtml += "</br><b>Secondary: </b>"+phone
                                }
                            });
                            $(document).find('#viewphone').html(phonehtml);
                            $(document).find('#viewgender').text(result.gender);
                            var profilehtml = '<img src="https://th.bing.com/th/id/OIP.w0TcjC4y9CxTrY3sitYa_AAAAA?rs=1&pid=ImgDetMain" alt="profile" class="profile_image">';
                            if(result.profile_image !== null){
                                var profileurl = "{{ asset('profileimage') }}";
                                profileurl = profileurl.replace('profileimage', result.profile_image);
                                profilehtml = '<img src="'+profileurl+'" alt="profile" class="profile_image">'
                            }
                            $(document).find('.viewprofileimage').append(profilehtml);
                            if(result.additional_file !== null){
                                var additionalfile = "{{ asset('addfile') }}";
                                additionalfile = additionalfile.replace('addfile', result.additional_file);
                                addfilehtml = '<a href="'+additionalfile+'" target="_blank">View</a>';
                                $(document).find('.viewaddfile').append(addfilehtml);
                            }
                            if(result.contact_custom_values.length > 0){
                                $.each(result.contact_custom_values, function(index, item) {
                                    var name = item.custom_field.name;
                                    $(document).find('#view'+name).text(item.value);
                                });
                            }

                            $(document).find('.secondarycontact').addClass('d-none');
                            $(document).find('.secondarycontact').empty();
                            if(result.merged_contacts.length > 0){
                                var mergedhtml = "<div class='col-md-12'><h4>Secondary Contact Details</h4></div>";
                                $.each(result.merged_contacts, function(ind, i) {
                                    mergedhtml += "<div class='col-md-12'></div><div class='form-group col-md-4'><h6>Name</h6><p>"+i.name+"</p></div><div class='form-group col-md-4'><h6>Email</h6><p>"+i.email+"</p></div><div class='form-group col-md-4'><h6>Phone</h6><p>"+i.phone+"</p></div><div class='form-group col-md-4'><h6>Gender</h6><p>"+i.gender+"</p></div>";
                                });
                                $(document).find('.secondarycontact').removeClass('d-none');
                                $(document).find('.secondarycontact').append(mergedhtml);
                            }

                        }else if(resp.success == false){
                            $('#editContactModal').modal('hide');
                            printErrorMsg(data.msg);
                        }
                    }
                });
            });

            // function printValidationErrorMsg(msg){
            //     $.each(msg, function(field_name, error){
            //         $(document).find('#'+field_name+'_error').text(error);
            //     });
            // }
            // function printErrorMsg(msg){
            //     $('#alert-danger').html('');
            //     $('#alert-danger').removeClass('d-none');
            //     $('#alert-danger').append(msg);
            // }
            // function printSuccessMsg(msg){
            //     $('#alert-success').html('');
            //     $('#alert-success').removeClass('d-none');
            //     $('#alert-success').append(msg);
            // }
        });
    </script>
@endsection
