@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid">
                        <h3 id="crumbs">
                            My Files
                        </h3>
                        <span id="abc">

                            </span>
                        <br>
                        <button type="button" class="btn btn-default" id="new_button" onclick="add_new()">New Folder
                        </button>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8">
                    <div class="container-fluid">
                        <table class="table table-responsive" id="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Size</th>
                                <th scope="col">Modified</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($folders as $folder)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <th scope="row" id="name"><a href="#"
                                                                 onclick="load_files('{{$folder->id}}')">{{$folder->name}}</a>
                                    </th>
                                    <th scope="row" id="edit_form" style="display:none">
                                        <input type="text" value="{{$folder->name}}" name="edit_name">
                                        <button type="button" class="btn btn-default" id="save_new"
                                                onclick="save_edit('{{$folder->id}}', this.parentNode.parentNode.rowIndex, event)">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-warning" id="cancel_edit"
                                                onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel
                                        </button>
                                    </th>
                                    <td>{{$folder->size}} {{$folder->unit}}</td>
                                    <td>{{$folder->updated_at}}</td>
                                    <td>
                                        <button type="button" class="btn btn-default" id="rename"
                                                onclick="rename(this.parentNode.parentNode.rowIndex);">Rename
                                        </button>
                                        <button type="button" class="btn btn-warning" id="delete"
                                                onclick="delete_row('{{$folder->id}}', this.parentNode.parentNode.rowIndex);">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">

                                        <input type="text" id="folder_name" name="name">
                                        <button type="button" class="btn btn-default" id="create_folder"
                                                onclick="create(event)">Create
                                        </button>
                                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                                onclick="cancel()">Cancel
                                        </button>
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


        //PUT FOLDER CODE IN SEPERATE FILE
        function add_new() {
            var row_new = document.getElementById('new_form');
            row_new.style.display = 'block';
        }

        function create(event) {
            event.preventDefault();
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
            }).done(function (data) {
                console.log(data);
                $('table tbody').html('');
                var i = 0;
                $.each(data, function (index, datum) {
                    i++;
                    $('table tbody').append(`
                 <tr>
                   <td>${i}</td>
                   <td><a href="#" onclick="load_files(${datum.id})"> ${datum.name} </a> </td>
                   <th scope="row" id="edit_form" style="display:none">
                                        <input type="text" value="${datum.name}" name="edit_name">
                                        <button type="button" class="btn btn-default" id="save_new"
                                                onclick="save_edit(${datum.id}, this.parentNode.parentNode.rowIndex, event)">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-warning" id="cancel_edit"
                                                onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel
                                        </button>
                                    </th>
                   <td>${datum.size} ${datum.unit}</td>
                   <td>${datum.updated_at}</td>
                   <td>
                         <button type="button" class="btn btn-default" id="rename"
                                                onclick="rename(this.parentNode.parentNode.rowIndex);">Rename
                                        </button>
                                        <button type="button" class="btn btn-warning" id="delete"
                                                onclick="delete_row(${datum.id} , this.parentNode.parentNode.rowIndex);">
                                            Delete
                                        </button>
                   </td>
                </tr>


                    <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">
                        <input type="text" id="folder_name" name="name">
                        <button type="button" class="btn btn-default" id="create_folder"
                                onclick="create(event)">Create
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                onclick="cancel()">Cancel
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>`)

                });
            });
        }

        function cancel() {
            var row_new = document.getElementById('new_form');
            row_new.style.display = 'none';
        }

        function rename(va) {
            var table = document.getElementById('table');
            var row = table.rows[va];
            var cell = row.cells[1];
            var cell_form = row.cells[2];
            cell_form.style.display = 'block';
            cell.style.display = 'none';
        }

        function revert(va) {
            var table = document.getElementById('table');
            var row = table.rows[va];
            var cell = row.cells[1];
            var cell_form = row.cells[2];
            cell_form.style.display = 'none';
            cell.style.display = 'block';
        }

        function save_edit(id, rowed, event) {
            event.preventDefault();
            var table = document.getElementById('table');
            var row = table.rows[rowed];
            var cell = row.cells[1];
            var cell_form = row.cells[2];
            var name = cell_form.children[0].value;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                method: 'POST',
                url: 'edit_name/' + id,
                data: {
                    _token: csrf_token,
                    name: name
                },
                dataType: 'json'
            }).done(function (data) {
                cell_form.style.display = 'none';
                cell.style.display = 'block';
                row.cells[1].innerHTML = `<a href="#" onclick="load_files(${data.id})">${data.name}</a>`;
                row.cells[3].innerHTML = data.size+data.unit;
                row.cells[4].innerHTML = data.updated_at;
            });

        }

        function delete_row(id, row) {
            var con = confirm("Are you sure?");
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            if (con) {
                $.ajax({
                    method: 'POST',
                    url: 'delete_folder/' + id,
                    data: {
                        _token: csrf_token,
                        id: id
                    },
                    dataType: 'json'
                }).done(function (data) {
                    $('table tbody').html('');
                    var i = 0;
                    $.each(data, function (index, datum) {
                        i++;
                        $('table tbody').append(`
                 <tr>
                   <td>${i}</td>
                   <td><a href="#" onclick="load_files(${datum.id})"> ${datum.name}</a></td>
                   <th scope="row" id="edit_form" style="display:none">
                                        <input type="text" value="${datum.name}" name="edit_name">
                                        <button type="button" class="btn btn-default" id="save_new"
                                                onclick="save_edit(${datum.id}, this.parentNode.parentNode.rowIndex, event)">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn-warning" id="cancel_edit"
                                                onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel
                                        </button>
                                    </th>
                   <td>${datum.size} ${datum.unit}</td>
                   <td>${datum.updated_at}</td>
                   <td>
                         <button type="button" class="btn btn-default" id="rename"
                                                onclick="rename(this.parentNode.parentNode.rowIndex);">Rename
                                        </button>
                                        <button type="button" class="btn btn-warning" id="delete"
                                                onclick="delete_row(${datum.id} , this.parentNode.parentNode.rowIndex);">
                                            Delete
                                        </button>
                   </td>
                </tr>


                    <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">
                        <input type="text" id="folder_name" name="name">
                        <button type="button" class="btn btn-default" id="create_folder"
                                onclick="create(event)">Create
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                onclick="cancel()">Cancel
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>
`);

                    });
                });

            }
        }


        //FILE FUNCTIONS. PUT THEM IN SEPARATE FILE

        function load_files(folder_id) {
            $.ajax({
                method: 'GET',
                url: 'all_files/' + folder_id,
                dataType: 'json'
            }).done(function (data) {
                console.log(data);
                var file=data[0].file;
                console.log("File", file);
                $('table tbody').html('');
                var crumb = document.getElementById('crumbs');
                var sub_form = document.getElementById('abc');
                var div = document.createElement('div');
                var idea = data[0].id;
                sub_form.innerHTML =
                    `<form enctype="multipart/form-data" onsubmit="add_file('+idea+')"> <input type="file" id="upload" class="btn btn-default" name="file"> <button class="btn btn-success" onclick="add_file(${idea}, event)" type="button">Submit</button> </form>`;
                crumb.append(
                    `>> ${data[0].name}`
                );
                $.each(file, function (index, datum) {
                    $('table tbody').append(`

                 <tr>
                   <td>${index + 1}</td>
                   <td>${datum.file_name}</td>
                   <td scope="row" id="edit_file" style="display:none">
                                <input type="text" value="${datum.file_name}" name="edit_name">
                                <button type="button" class="btn btn-default" id="save_new" onclick="rename_file('${datum.id}', this.parentNode.parentNode.rowIndex, event)">Save</button>
                                <button type="button" class="btn btn-warning" id="cancel_edit_file" onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel</button>
                            </td>
                   <td>${datum.size} ${datum.unit}</td>
                   <td>${datum.updated_at}</td>
                   <td>
                        <button type="button" class="btn btn-default" id="rename" onclick="rename_file_form(this.parentNode.parentNode.rowIndex);">Rename</button>
                        <button type="button" class="btn btn-warning" id="delete" onclick="remove_file(${datum.id}, this.parentNode.parentNode.rowIndex);">Delete</button>
                   </td>
                </tr>

                  <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">
                        <input type="text" id="folder_name" name="name">
                        <button type="button" class="btn btn-default" id="create_folder"
                                onclick="create(event)">Create
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                onclick="cancel()">Cancel
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>
`);
                });
            });
        }

        function rename_file_form(row) {
            var table = document.getElementById('table');
            var row = table.rows[row];
            var cell = row.cells[1];
            var cell_form = row.cells[2];
            cell_form.style.display = 'block';
            cell.style.display = 'none';
        }

        function add_file(folder_id, event) {
            event.preventDefault();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            var nnn = document.getElementById('upload');
            var file = $('#upload')[0].files[0];
            var form = new FormData();
            form.append('file', file);
            form.append('size', file.size);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                method: 'POST',
                url: 'add_file/' + folder_id,
                data: form,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false
            }).done(function (data) {
                $('table tbody').html('');
                $.each(data, function (index, datum) {
                    $('table tbody').append(`

                 <tr>
                   <td>${index + 1}</td>
                   <td>${datum.file_name}</td>
                   <td scope="row" id="edit_file" style="display:none">
                                <input type="text" value="${datum.file_name}" name="edit_name">
                                <button type="button" class="btn btn-default" id="save_new" onclick="rename_file('${datum.id}', this.parentNode.parentNode.rowIndex, event)">Save</button>
                                <button type="button" class="btn btn-warning" id="cancel_edit_file" onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel</button>
                            </td>
                   <td>${datum.size} ${datum.unit}</td>
                   <td>${datum.updated_at}</td>
                   <td>
                        <button type="button" class="btn btn-default" id="rename" onclick="rename_file_form(this.parentNode.parentNode.rowIndex);">Rename</button>
                        <button type="button" class="btn btn-warning" id="delete" onclick="remove_file(${datum.id}, this.parentNode.parentNode.rowIndex);">Delete</button>
                   </td>
                </tr>

                  <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">
                        <input type="text" id="folder_name" name="name">
                        <button type="button" class="btn btn-default" id="create_folder"
                                onclick="create(event)">Create
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                onclick="cancel()">Cancel
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>
`);
                });
            });
        }

        function rename_file(file_id, rowed, event) {
            event.preventDefault();
            var table = document.getElementById('table');
            var row = table.rows[rowed];
            var cell = row.cells[1];
            var cell_form = row.cells[2];
            var name = cell_form.children[0].value;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                method: 'POST',
                url: 'rename_file/' + file_id,
                data: {
                    _token: csrf_token,
                    name: name
                },
                dataType: 'json'
            }).done(function (data) {
                cell_form.style.display = 'none';
                cell.style.display = 'block';
                row.cells[1].innerHTML = data.file_name;
                row.cells[3].innerHTML = data.size;
                row.cells[4].innerHTML = data.updated_at;
            });

        }

        function remove_file(file_id, row) {
            var con = confirm("Are you sure?");
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            if (con) {
                $.ajax({
                    method: 'POST',
                    url: 'delete_file/' + file_id,
                    data: {
                        _token: csrf_token,
                        id: file_id
                    },
                    dataType: 'json'
                }).done(function (data) {
                    $('table tbody').html('');
                    $.each(data, function (index, datum) {
                        $('table tbody').append(`

                 <tr>
                   <td>${index + 1}</td>
                   <td>${datum.file_name}</td>
                   <td scope="row" id="edit_file" style="display:none">
                                <input type="text" value="${datum.file_name}" name="edit_name">
                                <button type="button" class="btn btn-default" id="save_new" onclick="rename_file('${datum.id}', this.parentNode.parentNode.rowIndex, event)">Save</button>
                                <button type="button" class="btn btn-warning" id="cancel_edit_file" onclick="revert(this.parentNode.parentNode.rowIndex);">Cancel</button>
                            </td>
                   <td>${datum.size} ${datum.unit}</td>
                   <td>${datum.updated_at}</td>
                   <td>
                        <button type="button" class="btn btn-default" id="rename" onclick="rename_file_form(this.parentNode.parentNode.rowIndex);">Rename</button>
                        <button type="button" class="btn btn-warning" id="delete" onclick="remove_file(${datum.id}, this.parentNode.parentNode.rowIndex);">Delete</button>
                   </td>
                </tr>

                  <tr style="display:none" id="new_form">
                                <td></td>
                                <td>
                                    <form id='form' onsubmit="create(event)">
                        <input type="text" id="folder_name" name="name">
                        <button type="button" class="btn btn-default" id="create_folder"
                                onclick="create(event)">Create
                        </button>
                        <button type="button" class="btn btn-warning" id="cancel_folder"
                                onclick="cancel()">Cancel
                        </button>
                    </form>
                </td>
                <td></td>
                <td></td>
            </tr>
`);

                    });
                });

            }
        }

        function create_sub_folder(folder_id) {

        }


    </script>
@endsection