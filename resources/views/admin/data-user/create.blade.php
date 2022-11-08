@extends('layouts.master.template')
@section('title', 'Admin | Create User')
@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data User</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.data-user') }}">Data User</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create User</h3>
                        <div class="card-tools">
                            <!-- Maximize Button -->
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                    class="fas fa-expand"></i></button>
                        </div>
                    </div>
                    <!-- form start -->
                    <form id="quickForm" action="{{ route('admin.data-user-store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="input" name="name" class="form-control" id="name"
                                    placeholder="Masukkan nama">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="Enter email">
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="input" name="tempat_lahir" class="form-control" id="tempat_lahir"
                                            placeholder="Masukkan tempat lahir">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal-lahir">Tanggal Lahir</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" name="tanggal_lahir" id="tanggal_lahir"
                                                placeholder="Pilih tanggal lahir" />
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control select2bs4 w-100" name="jenis_kelamin" id="jenis_kelamin">
                                    <option value="">Pilih..</option>
                                    @foreach ($bulkData->jenisKelamin as $jk)
                                        <option>{{ $jk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alat">Alamat</label>
                                <textarea class="form-control" rows="3" placeholder="Masukkan alamat" name="alamat" id="alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Nomer Telepon</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" data-inputmask='"mask": "(999) 999-999-999"'
                                        data-mask placeholder="081xxxxxxxx" name="phone" id="phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label><br>
                                <img class="profile-user-img img-fluid img-circle" id="img-preview"
                                    style="width: 100px; height: 100px; object-fit:cover;" alt="User Image">
                                <div class="custom-file mt-2">
                                    <input type="file" class="custom-file-input" id="photo"
                                        onchange="previewPhoto()" name="photo" accept="image/png, image/jpeg">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('script')
    <!-- jquery-validation -->
    <script src="{{ asset('/admin/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- daterange picker -->
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('/admin/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'DD-MM-yyyy'
        });
        //phone mask
        $('[data-mask]').inputmask()

        //add name to fileinput
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

    <script>
        $(function() {
            $.validator.setDefaults({
                submitHandler: function() {
                    submit();
                }
            });
            $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    tempat_lahir: {
                        required: false,
                    },
                    tanggal_lahir: {
                        required: false,
                    },
                    jenis_kelamin: {
                        required: false,
                    },
                    alamat: {
                        required: false,
                    },
                    phone: {
                        required: false,
                    },
                    photo: {
                        extension: "png|jpe?g"
                    }
                },
                messages: {
                    name: {
                        required: "Perlu diisi",
                    },
                    email: {
                        required: "Perlu diisi",
                        email: "Harus menggunakan format email @",
                    },
                    tempat_lahir: {
                        required: "Perlu diisi",
                    },
                    tanggal_lahir: {
                        required: "Perlu diisi",
                    },
                    jenis_kelamin: {
                        required: "Perlu diisi",
                    },
                    alamat: {
                        required: "Perlu diisi",
                    },
                    phone: {
                        required: "Perlu diisi",
                    },
                    photo: {
                        extension: "Hanya bisa format jpg/png"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
    <script>
        function previewPhoto() {
            const photo = document.querySelector("#photo");
            const imgPreview = document.querySelector("#img-preview");
            const oFReader = new FileReader();
            oFReader.readAsDataURL(photo.files[0]);
            // oFReader.onLoad = function(oFREvent) {
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            };
        }
    </script>
@endpush
