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
                    <h1 class="h3 mb-0 text-gray-800">Tabel Pengaduan</h1>
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                </div>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <form action="{{ url('admin/complaint') }}" method="get">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="search">Pencarian</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                    placeholder="Cari judul, detail, nama" value="{{ ($requests->search) ? $requests->search : '' }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label>
                                    <select class="custom-select" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="Menunggu" {{ ($requests->status == "Menunggu") ? "selected" : '' }}>Menunggu</option>
                                        <option value="Diteruskan" {{ ($requests->status == "Diteruskan") ? "selected" : '' }}>Diteruskan</option>
                                        <option value="Diterima" {{ ($requests->status == "Diterima") ? "selected" : '' }}>Diterima</option>
                                        <option value="Ditolak" {{ ($requests->status == "Ditolak") ? "selected" : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="position">Penerima</label>
                                    <select class="custom-select" id="position" name="position">
                                        <option value="">Pilih Penerima</option>
                                        @foreach ($data['positions'] as $position)
                                        <option value="{{ $position->id }}" {{ ($requests->position == $position->id) ? "selected" : '' }}>{{ $position->name }}</option>    
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
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Pengaju</th>
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
                                        <th>Pengaju</th>
                                        <th>Penerima</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if ($data['complaints']->count() > 0)
                                        
                                    @foreach ($data['complaints'] as $complaint)
                                    <tr>
                                        <td>
                                            @if($requests->page == 1 || $requests->page == null)
                                                {{ $loop->iteration }}
                                            @else
                                                {{ ($requests->page - 1) * 20 + $loop->iteration }}
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
                                        <td>{{ $complaint->user_name }}</td>
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
                                    <p>Total Data : {{ $data['complaints']->total() }}</p>
                                </div>
                                <div class="float-right">
                                    {{ $data['complaints']->links() }}
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
@if (isset($data['complaint']))
@endif

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

