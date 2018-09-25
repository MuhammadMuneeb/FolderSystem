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
                            <th scope="row" id="name">{{$folder->name}}</th>
                            <th scope="row" id="edit_form" style="display:none">
                            {{--<form>--}}
                                <input type="text" value="{{$folder->name}}" name="edit_name">
                                <button type="button" class="btn btn-default" id="save_new" onclick="save_edit('{{$folder->id}}', this.parentNode.parentNode.rowIndex)">Save</button>
                                <button type="button" class="btn btn-warning" id="cancel_edit" onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel</button>
                            {{--</form>--}}
                            </th>
                            <td>{{$folder->size}}</td>
                            <td>{{$folder->updated_at}}</td>
                            <td>
                                <button type="button" class="btn btn-default" id="rename" onclick="rename(this.parentNode.parentNode.rowIndex);">Rename</button>
                                <button type="button" class="btn btn-warning" id="delete" onclick="delete_row('{{$folder->id}}', this.parentNode.parentNode.rowIndex);">Delete</button>
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
                   <td>
                        <button type="button" class="btn btn-default" id="rename">Rename</button>
                        <button type="button" class="btn btn-warning" id="delete">Delete</button>
                   </td>
                </tr>
                `)

            });
        });
    }

    function cancel(){
        var row_new = document.getElementById('new_form');
        row_new.style.display = 'none';
    }

    function rename(va){
        var table = document.getElementById('table');
        var row = table.rows[va];
        var cell = row.cells[1];
        var cell_form = row.cells[2];
        cell_form.style.display = 'block';
        cell.style.display = 'none';
    }

    function revert(va){
        var table = document.getElementById('table');
        var row = table.rows[va];
        var cell = row.cells[1];
        var cell_form = row.cells[2];
        cell_form.style.display = 'none';
        cell.style.display = 'block';
    }

    function save_edit(id, rowed){
       var table = document.getElementById('table');
        var row = table.rows[rowed];
        var cell = row.cells[1];
        var cell_form = row.cells[2];
        var name = cell_form.children[0].value;
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            method:'POST',
            url: 'edit_name/'+id,
            data: {
                _token: csrf_token,
                name: name
            },
            dataType: 'json'
        }).done(function(data){
            cell_form.style.display = 'none';
            cell.style.display = 'block';
            row.cells[1].innerHTML = data.name;
            row.cells[3].innerHTML = data.size;
            row.cells[4].innerHTML = data.updated_at;
        });

    }

    function delete_row(id, row){
        var con = confirm("you sure you wanna delete");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        if(con){
            $.ajax({
                method:'POST',
                url: 'delete_folder/'+id,
                data: {
                    _token: csrf_token,
                    id: id
                },
                dataType: 'json'
            }).done(function(data){
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
                   <td>
                        <button type="button" class="btn btn-default" id="rename">Rename</button>
                        <button type="button" class="btn btn-warning" id="delete">Delete</button>
                   </td>
                </tr>
                `);

                });
            });

        }else{

        }
    }

</script>
@endsection