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
                    <h1 class="h3 mb-0 text-gray-800">Tabel Jabatan</h1>
                    <a href="{{ route('admin.position.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" >
                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                    </a>
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

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Jabatan</th>
                                        <th>Nama</th>
                                        <th>Jumlah Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Jabatan</th>
                                        <th>Nama</th>
                                        <th>Jumlah Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if ($data['positions']->count() > 0)
                                    @foreach ($data['positions'] as $position)   
                                        <tr>
                                            <td class="text-center">
                                                @if($requests->page == 1 || $requests->page == null)
                                                {{ $loop->iteration }}
                                                @else
                                                    {{ ($requests->page - 1) * 20 + $loop->iteration }}
                                                @endif
                                            </td>
                                            <td>{{ $position->name }}</td>
                                            <td>{{ ($position->user != null) ? $position->user->name : "-"}}</td>
                                            <td class="text-center">{{ $position->complaints->count() }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.position.show', $position->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ url('admin/complaint?position='.$position->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <a href="{{ route('admin.position.edit', $position->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                                    <p>Total Data : {{ $data['positions']->total() }}</p>
                                </div>
                                <div class="float-right">
                                    {{ $data['positions']->links() }}
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

        $('#PositionModalEdit').on('show.bs.modal', function (event) {
            console.log('modal show');
            var button = $(event.relatedTarget) // Button that triggered the modal
            
            })
    });
    

</script>
@endsection

