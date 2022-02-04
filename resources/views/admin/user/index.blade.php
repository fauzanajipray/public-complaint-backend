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
                    <h1 class="h3 mb-0 text-gray-800">Tabel Pengguna</h1>
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                </div>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <form action="{{ url('admin/user') }}" method="get">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="search">Pencarian</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                    placeholder="Cari nama, email" value="{{ ($requests->search) ? $requests->search : '' }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="custom-select" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="1" {{ ($requests->status == 1) ? "selected" : '' }}>Terverifikasi</option>
                                        <option value="0" {{ ($requests->status == 0) ? "selected" : '' }}>Belum Terverifikasi</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="role">Role</label>
                                    <select class="custom-select" id="role" name="role">
                                        <option value="">Pilih Role</option>
                                        <option value="1" {{ ($requests->role == 1) ? "selected" : '' }}>Admin</option>
                                        <option value="2" {{ ($requests->role == 2) ? "selected" : '' }}>Pengadu</option>
                                        <option value="3" {{ ($requests->role == 3) ? "selected" : '' }}>Staff</option>
                                   </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="order">Urutkan</label>
                                    <select class="custom-select" name="order">
                                        <option value="DESC" {{ ($requests->order == 'DESC') ? "selected" : '' }} >Terbaru</option>
                                        <option value="ASC" {{ ($requests->order == 'ASC') ? "selected" : '' }} >Terlama</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Verified</th>
                                        <th>Pendaftaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Verified</th>
                                        <th>Pendaftaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if ($data['users']->count() > 0)
                                    @foreach ($data['users'] as $user)
                                    <tr class="text-center">
                                        <td>
                                            @if($requests->page == 1 || $requests->page == null)
                                                {{ $loop->iteration }}
                                            @else
                                                {{ ($requests->page - 1) * 20 + $loop->iteration }}
                                            @endif    
                                        </td>   
                                        <td class="text-left">{{ $user->name }}</td>
                                        <td class="text-left">{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role_id == 1)
                                            <span class="badge badge-primary">Admin</span>
                                            @elseif ($user->role_id == 2)
                                            <span class="badge badge-success">Pengadu</span>
                                            @else
                                            <span class="badge badge-warning">Staff</span>
                                            @endif    
                                        </td> 
                                        <td>
                                            @if ($user->is_email_verified == 1)
                                            <span class="badge badge-success">Terverifikasi</span>
                                            @else
                                            <span class="badge badge-warning">Belum Terverifikasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            <a href="{{ url('admin/user/'.$user->id) }}" class="btn btn-primary btn-sm mb-1"> <i class="fas fa-eye"></i>
                                                </a>
                                            @if($user->role_id != 1)
                                            <a href="{{ url('admin/user/'.$user->id.'/edit') }}" class="btn btn-warning btn-sm mb-1"> <i class="fas fa-edit"></i> </a>
                                            @endif
                                            <form action="{{ url('admin/user/'.$user->id) }}" method="delete" class="d-inline-block mb-1" style="padding-top: -50px">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm " style=""> <i class="fas fa-trash"></i> </button>
                                            </form>
                                        </td>
                                    </tr>                  
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="float-left">
                                    <p>Total Data : {{ $data['users']->total() }}</p>
                                </div>
                                <div class="float-right">
                                    {{ $data['users']->links() }}
                                </div>
                            </div>
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

<script>

    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

</script>
@endsection

