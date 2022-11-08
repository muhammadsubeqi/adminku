@extends('layouts.master.template')
@section('title', 'Admin | Data Pegawai')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 d-flex flex-row">
                        <h1>Data User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">/ Data User
                            </li>
                        </ol>
                    </div>
                </div>
                <a href="{{ route('admin.data-user-create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus-circle mx-2"></i>Create User</a>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @if (Session::has('message'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-check"></i> Success</h5>
                                    {!! Session::get('message') !!}
                                </div>
                            @endif
                            @if (Session::has('failed'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i> Failed</h5>
                                    {!! Session::get('message') !!}
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="table1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataUsers as $dataUser)
                                            <tr>
                                                <td class="align-middle">{{ $dataUser->id }}</td>
                                                <td>
                                                    @if ($dataUser->user->photo == null)
                                                        <img src="{{ 'https://picsum.photos/' . 100 }}"
                                                            class="profile-user-img img-fluid img-circle" alt="User Image">
                                                    @else
                                                        @php
                                                            $photo = $dataUser->user->photo;
                                                        @endphp
                                                        <img src="{{ asset("/foto_user/$photo") }}"
                                                            class="profile-user-img img-fluid img-circle"
                                                            style="width: 100px; height: 100px; object-fit:cover;"
                                                            alt="User Image">
                                                    @endif
                                                </td>
                                                <td class="align-middle">{{ $dataUser->name }}</td>
                                                <td class="align-middle">{{ $dataUser->user->email }}</td>
                                                <td class="align-middle">{{ $dataUser->user->role }}</td>
                                                <td class="align-middle">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Klik
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.data-user-show', ['id' => $dataUser->id]) }}">Detail</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.data-user-edit', ['id' => $dataUser->id]) }}">Edit</a>
                                                            <form
                                                                action="{{ route('admin.data-user-delete', ['id' => $dataUser->id]) }}"
                                                                class="form-hapus" method="POST"
                                                                onsubmit="deleteData(event)">
                                                                @method('delete')
                                                                @csrf
                                                                <input type="hidden" value="{{ $dataUser->id }}"
                                                                    name="id">
                                                                <input type="hidden" value="{{ $dataUser->name }}"
                                                                    name="name">
                                                                <button class="dropdown-item" type="submit">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('script')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $("#table1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        function deleteData(event) {
            event.preventDefault();
            var id = event.target.querySelector('input[name="id"]').value;
            var name = event.target.querySelector('input[name="name"]').value;
            swalDelete(id, name, event.target);
        }
    </script>
@endpush
