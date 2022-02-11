@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
@endsection

@section('content')

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @livewire('admin.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @livewire('admin.header')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Profil</h1>
                    {{-- <a href="{{ route('admin.position.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" >
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                    </a> --}}
                </div>
                <div class="">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error')}}
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <form action="{{ route('admin.profile.update', $data['user']->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_updateProfile" value="info">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        @if ($data['user']->detail->avatar != null)  
                                        <img src="{{ asset($data['user']->detail->avatar) }}" alt="{{ $data['user']->name }}" width="200" id="imgPreview" class="img-fluid m-2">
                                        @endif
                                        <img src="{{ asset('images/undraw_profile.svg') }}" alt="{{ $data['user']->name }}" width="200" id="imgPreview" class="img-fluid m-2" style="display:none;">
                                        <label for="image">Ganti gambar</label>                                        
                                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="showPreview(event);">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control mt-1" id="name" name="name" 
                                        placeholder="" value="{{ $data['user']->name }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control mt-1" id="email" name="email"
                                        placeholder="" value="{{ $data['user']->email }}" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control mt-1" id="phone" name="phone"
                                        placeholder="" value="{{ $data['user']->detail->phone }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control mt-1" id="address" name="address"
                                        placeholder="" value="{{ $data['user']->detail->address }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nik">NIK</label>
                                        <input type="text" class="form-control mt-1" id="nik" name="nik"
                                        placeholder="" value="{{ $data['user']->detail->nik }}">
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <form action="{{ route('admin.profile.update', $data['user']->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_updateProfile" value="email">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ganti Email</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="new_email">Email Baru</label>
                                        <input type="text" class="form-control mt-1" id="new_email" name="new_email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="new_email_confirmation">Konfirmasi Email Baru</label>
                                        <input type="text" class="form-control mt-1" id="new_email_confirmation" name="new_email_confirmation">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control mt-1" id="password" name="password">
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="card shadow mb-4">
                            <form action="{{ route('admin.profile.update', $data['user']->id)  }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_updateProfile" value="password">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Ganti Password</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="" name="new_password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password_confirmation">New Password Confirmation</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="" name="new_password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Pengaduan Masyarakat 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
@livewire('admin.logout-modal')

@endsection

@section('js')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript">
//doc ready
    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById('imgPreview');
            preview.src = src;
            preview.style.display = "block";
        }
    }
</script>
@endsection

