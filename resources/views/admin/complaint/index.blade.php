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
                    <h1 class="h3 mb-0 text-gray-800">Complaint</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <form action="{{ url('admin/complaint') }}" method="get">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="search">Pencarian</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                    placeholder="Cari judul, detail.." value="{{ ($requests->search) ? $requests->search : '' }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="custom-select" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="Menunggu" {{ ($requests->status == "Menunggu") ? "selected" : '' }}>Menunggu</option>
                                        <option value="Diterima" {{ ($requests->status == "Diterima") ? "selected" : '' }}>Diterima</option>
                                        <option value="Ditolak" {{ ($requests->status == "Ditolak") ? "selected" : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="recipient">Penerima</label>
                                    <select class="custom-select" id="recipient" name="recipient">
                                        <option value="">Pilih Penerima</option>
                                        @foreach ($data['recipients'] as $recipient)
                                        <option value="{{ $recipient->id }}" {{ ($requests->recipient == $recipient->id) ? "selected" : '' }}>{{ $recipient->name }}</option>    
                                        @endforeach
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
                                    <button type="submit" class="btn btn-primary btn-block">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Pengaju</th>
                                        <th>Penerima</th>
                                        <th>Anonim</th>
                                        <th>Privasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Pengaju</th>
                                        <th>Penerima</th>
                                        <th>Anonim</th>
                                        <th>Privasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($data['complaints'] as $complaint)
                                    <tr>
                                        <td>{{ $complaint->title }}</td>
                                        <td>
                                            <?php
                                             if (strlen($complaint->description) < 100) {
                                                echo $complaint->description;
                                            } else {
                                                echo substr($complaint->description, 0, 100) . '....';
                                            }
                                            ?>
                                        </td>
                                        <td>{{ $complaint->user_name }}</td>
                                        <td>{{ $complaint->recipient_name }}</td>
                                        <td>{{ ($complaint->is_anonymous == 1) ? 'Yes' :'No' }}</td>
                                        <td>{{ ($complaint->is_private == 1) ? 'Yes' : 'No' }} </td>
                                        <td>
                                            <a href="{{ url('admin/complaint/'.$complaint->id) }}" 
                                                class="btn btn-primary btn-sm mb-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ url('admin/complaint/'.$complaint->id.'/edit') }}" 
                                                class="btn btn-warning btn-sm mb-1" hidden>
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ url('admin/complaint/'.$complaint->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm mb-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    <span>Copyright &copy; Your Website 2021</span>
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
@livewire('admin.show-complaint-modal', [
    'com' => $user,
])

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

