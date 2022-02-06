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
                    <h1 class="h3 mb-0 text-gray-800">Detail User</h1>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
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
                            <div class="card-header py-3 ">
                                <div class="float-left">
                                    @if ($data['user']->role_id == 1)
                                    <span class="badge badge-primary">Admin</span> 
                                    @elseif ($data['user']->role_id == 2)
                                    <span class="badge badge-success">Pengadu</span>
                                    @else
                                    <span class="badge badge-warning mr-2">Divisi</span> <span class="font-weight-bold text-primary">{{ $data['user']->position->name }} </span>
                                    @endif    
                                </div>
                                <div class="float-right">
                                    <p class="m-0 font-weight-bold text-primary">
                                        ID : {{ $data['user']->id }}
                                        <span class="ml-3">
                                            <a href=# class="btn btn-warning btn-sm mb-1" data-toggle="modal" data-target="#settingsUserModal"> 
                                                <i class="fas fa-cog"></i> 
                                            </a>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <div class="mt-2 mb-5">     
                                    @if ($data['user']->detail->avatar)
                                        <img src="{{ $data['user']->detail->avatar }}" alt="{{ $data['user']->name }}" class="img-thumbnail" width="350">
                                    @else 
                                        <img src="{{ asset('images/avatar.png') }}" alt="{{ $data['user']->name }}" class="img-thumbnail" width="350">
                                    @endif
                                </div>
                                <div>
                                    <h2 class="mb-3">
                                        {{ $data['user']->name }}
                                    </h2>
                                    <p class="mb-3">
                                        <span class="mb-3">
                                            <i class="fas fa-address-card mr-2"></i>
                                            {{ $data['user']->detail->nik }}
                                        </span>
                                    </p>
                                    <p class="mb-3">
                                        <span class="mb-3 mr-3">
                                            <i class="fas fa-envelope mr-2"></i>
                                            {{ $data['user']->email }}
                                        </span>
                                        <span class="mb-3 mr-3">
                                            <i class="fas fa-phone mr-2"></i> 
                                            {{ ($data['user']->detail->phone) ? $data['user']->detail->phone : '-' }}
                                        </span> 
                                    </p>
                                    <p class="mb-3">
                                        <span class="mb-3"> 
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            {{ ($data['user']->detail->address) ? $data['user']->detail->address : '-' }}
                                        </span>
                                    </p>
                                    <p class="mb-3">
                                        <span class="mb-3"> 
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            {{ $data['user']->created_at->format('d F Y') }}
                                        </span>
                                    </p>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="card shadow" >
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Komentar
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Komentar</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Komentar</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @if ($data['user']->comments->count() > 0)
                                            @foreach ($data['user']->comments as $comment)
                                            <tr>
                                                <td>
                                                    @if($requests->pg_cm == 1 || $requests->pg_cm == null)
                                                        {{ $loop->iteration }}
                                                    @else
                                                        {{ ($requests->pg_cm - 1) * 10 + $loop->iteration }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($comment->user_role == '1')
                                                    <span class="badge badge-primary">Admin</span>
                                                    @elseif ($comment->user_role == '2')
                                                    <span class="badge badge-success">Pengadu</span>
                                                    @else
                                                    <span class="badge badge-warning">Staff</span>
                                                    @endif
                                                    <a href="{{ url('admin/user/'.$comment->user_id) }}">
                                                        {{ $comment->user_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $comment->body }}</td>       
                                                <td class="text-center">
                                                    @if ($comment->status == 'Diteruskan')
                                                    <span class="badge badge-info">Diteruskan</span>
                                                    @elseif ($comment->status == 'Ditolak')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                    @else
                                                    <span class="badge badge-success">Diterima</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @livewire('convert-date-indo', ['date' => $comment->created_at ])
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    Belum ada komentar
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <div class="float-left">
                                        <p>Total Data : {{ $data['user']->comments->total() }}</p>
                                    </div>
                                    <div class="float-right">
                                        {{ $data['user']->comments->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($data['user']->role_id == 3)
                    <div class="col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Pengaduan Kepada {{ $data['user']->position->name }}
                                </h6>
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
                                        <tfoot class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Judul</th>
                                                <th>Deskripsi</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        @if ($data['position_complaints']->count() > 0)
                                                
                                            @foreach ($data['position_complaints'] as $complaint)
                                            <tr>
                                                <td>
                                                    @if($requests->pg_pscp == 1 || $requests->pg_pscp == null)
                                                        {{ $loop->iteration }}
                                                    @else
                                                        {{ ($requests->pg_pscp - 1) * 10 + $loop->iteration }}
                                                    @endif
                                                </td>
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
                                    <div class="col-md-12">
                                        <div class="float-left">
                                            <p>Total Data : {{ $data['position_complaints']->total() }}</p>
                                        </div>
                                        <div class="float-right">
                                            {{ $data['position_complaints']->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- {{ dd($data['user']->complaint ) }} --}}
                    @if ($data['user']->role_id != 3 )
                    <div class="col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Pengaduan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="" width="100%" cellspacing="0">
                                        <thead class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Judul</th>
                                                <th>Deskripsi</th>
                                                <th>Penerima</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Judul</th>
                                                <th>Deskripsi</th>
                                                <th>Penerima</th>
                                                <th>Status</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        @if ($data['user']->complaints->count() > 0)
                                                
                                            @foreach ($data['user']->complaints as $complaint)
                                            <tr>
                                                <td>
                                                    @if($requests->pg_cp == 1 || $requests->pg_cp == null)
                                                        {{ $loop->iteration }}
                                                    @else
                                                        {{ ($requests->pg_cp - 1) * 10 + $loop->iteration }}
                                                    @endif
                                                </td>
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
                                                <td>{{ $complaint->position_name }}</td>
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
                                                <td> {{ $complaint->created_at->diffForHumans() }}</td>
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
                                    <div class="col-md-12">
                                        <div class="float-left">
                                            <p>Total Data : {{ $data['user']->complaints->total() }}</p>
                                        </div>
                                        <div class="float-right">
                                            {{ $data['user']->complaints->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    
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
@livewire('admin.setting-users-modal', [
    'user' => $data['user'],
    'positions' => $data['positions'],
])

@endsection

@section('js')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>

    $(document).ready(function () {

        $('#dataTable').DataTable();

        $('#roleSelect').change(function () {
            if ($(this).val() == '3') {
                $('#positionSelect').removeClass('d-none');
            } else {
                $('#positionSelect').addClass('d-none');
            }
        });

        if ($('#roleSelect').val() == '3') {
            $('#positionSelect').removeClass('d-none');
        } else {
            $('#positionSelect').addClass('d-none');
        }

    });

</script>
@endsection

