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
                    <h1 class="h3 mb-0 text-gray-800">Detail Position</h1>
                </div>
                <!-- Session Message -->
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
                    <div class="col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col-6">
                                        {{-- <h6 class="m-0 font-weight-bold text-primary">
                                        </h6> --}}
                                        <span class="m-0 font-weight-bold text-primary mr-3">ID : {{ $data['position']->id }}</span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button  class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePosition">
                                            <i class="fas fa-trash"></i>
                                            <span class="d-none d-md-inline">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <h4 class="mb-3">
                                        {{ $data['position']->name }}
                                    </h4>
                                    <p class="text-justify mb-3">
                                        @if($data['position']->description)
                                            {{ $data['position']->description }}
                                        @else
                                            Deskripsi Tidak Ada
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-bullhorn"></i>
                                            <span class="m-0 font-weight-bold text-primary mr-3">Pengaduan Kepada {{ $data['position']->name }}</span>
                                        </h6>
                                    </div>
                                    @if($data['position']->complaints->count() > 0)
                                    <div class="col-6 text-right">
                                        <a href="{{ url('admin/complaint?position='.$data['position']->id)}}" class="btn btn-primary btn-sm">
                                            <span class="d-none d-md-inline mr-1">Lihat lainnya</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                        <thead class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Judul</th>
                                                <th>Deskripsi</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($data['position']->complaints->count() > 0)
                                                
                                            @foreach ($data['position']->complaints as $complaint)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if (strlen($complaint->title) < 50)
                                                        {{ $complaint->title }}
                                                    @else
                                                        {{ substr($complaint->title, 0, 50) . '....' }}
                                                    @endif
                                                </td>
                                                </td>
                                                <td>
                                                    <?php
                                                     if (strlen($complaint->description) < 40) {
                                                        echo $complaint->description;
                                                    } else {
                                                        echo substr($complaint->description, 0, 40) . '....';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    @if ($complaint->status == "Menunggu")
                                                        <span class="badge badge-warning">{{ $complaint->status }}</span>
                                                    @elseif ($complaint->status == "Diteruskan")
                                                        <span class="badge badge-info">{{ $complaint->status }}</span>
                                                    @elseif ($complaint->status == "Diterima")
                                                        <span class="badge badge-success">{{ $complaint->status }}</span>
                                                    @elseif ($complaint->status == "Ditolak")
                                                        <span class="badge badge-danger">{{ $complaint->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center"> {{ $complaint->created_at->diffForHumans() }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('admin/complaint/'.$complaint->id) }}"
                                                        class="btn btn-primary btn-sm mb-1" >
                                                        <i class="fas fa-eye mr-1"></i>Detail
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
@livewire('admin.position.delete-position-modal', ['position' => $data['position']])

@endsection

@section('js')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>

    $(document).ready(function () {

        $('#dataTable').DataTable();
    });

</script>
@endsection

