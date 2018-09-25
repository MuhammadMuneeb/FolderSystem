@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <div class="container-fluid">
            <h3>
                My Files
            </h3>
            <button type="button" class="btn btn-default" id="new_button" onclick="add_new()">New Folder</button>
        </div>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="container-fluid">
                    <table class="table table-responsive" id="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Folder</th>
                            <th scope="col">Size</th>
                            <th scope="col">Modified</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($folders as $folder)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <th scope="row">{{$folder->name}}</th>
                            <td>{{$folder->size}}</td>
                            <td>{{$folder->updated_at}}</td>
                            <td>
                                <button type="button" class="btn btn-default" id="rename">Rename</button>
                                <button type="button" class="btn btn-warning" id="delete">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                        <tr style="display:none" id="new_form">
                            <td></td>
                            <td>
                                <form id='form' onsubmit="create()">
                                    {{csrf_field()}}
                                    <input type="text" id="folder_name" name="name">
                                    <button type="button" class="btn btn-default" id="create_folder" onclick="create()">Create</button>
                                    <button type="button" class="btn btn-warning" id="cancel_folder" onclick="cancel()">Cancel</button>
                                </form>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function add_new(){
        var row_new = document.getElementById('new_form');
        row_new.style.display = 'block';
    }

    function create(){
        var name = $('input[name=name]').val();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            method: 'POST',
            url: 'create_folder',
            data: {
                _token: csrf_token,
                name: name
            },
            dataType: 'json'
        }).done(function(data){
            console.log(data);
            $('table tbody').html('');
            var i = 0;
            $.each(data, function(index, datum){
                i++;
                $('table tbody').append(`
                 <tr>
                 <td>${i}</td>
                   <td>${datum.name}</td>
                   <td>${datum.size}</td>
                    <td>${datum.updated_at}</td>
                    <td>                                <button type="button" class="btn btn-default" id="rename">Rename</button>
                                <button type="button" class="btn btn-warning" id="delete">Delete</button></td>
                </tr>
                `)

            });
        });
    }

    function cancel(){
        var row_new = document.getElementById('new_form');
        row_new.style.display = 'none';
    }
</script>
@endsection