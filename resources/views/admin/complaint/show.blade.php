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
                    <h1 class="h3 mb-0 text-gray-800">Detail Pengaduan</h1>
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
                        <div class="alert alert-error" role="alert">
                            {{ Session::get('error')}}
                        </div>
                    @endif
                </div>

                <div class="row">
                    
                    <div class="col-md-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <div class="float-left">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        @livewire('convert-date-indo', ['date' => $data['complaint']->created_at ])
                                    </h6>
                                </div>
                                <div class="float-right">
                                    <p class="m-0 font-weight-bold text-primary"> 
                                        <span class="mr-2">
                                            @if($data['complaint']->status == 'Menunggu')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($data['complaint']->status == 'Diteruskan')
                                                <span class="badge badge-info">Diteruskan</span>
                                            @elseif($data['complaint']->status == 'Diterima')
                                                <span class="badge badge-success">Diterima</span>
                                            @else
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </span> 
                                        ID : {{ $data['complaint']->id }}</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center mt-2 mb-5">
                                    <img src="{{ asset($data['complaint']->image) }}" alt="" class="img-fluid img-center" width="500">
                                </div>
                                <div>
                                    <h2 class="mb-3">
                                        {{ $data['complaint']->title }}
                                    </h2>
                                    <p class="text-justify mb-3">
                                        {{ $data['complaint']->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($data['complaint']->status == 'Menunggu')
                    <div class="col-md-12 mb-4">
                        <div class="card shadow"> 
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Konfirmasi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="position">Pesan akan didisposisikan kepada</label>
                                    <select class="custom-select" id="position" name="position">
                                        <option value="">Pilih Penerima</option>
                                        @foreach ($data['positions'] as $position)
                                        <option value="{{ $position->id }}" {{ ($data['complaint']->position_id == $position->id) ? "selected" : '' }}>{{ $position->name }}</option>    
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-right mb-3">
                                    <a class="btn btn-danger m-1" data-toggle="modal" data-target="#complaintRejectModal">
                                        <i class="fas fa-times" ></i> &nbsp Tolak
                                    </button>
                                    <a class="btn btn-success m-1" data-toggle="modal" data-target="#complaintConfirmModal">
                                        <i class="fas fa-check"></i> &nbsp Terima
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($data['complaint']->status != 'Menunggu')
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
                                            @if ($data['complaint']['comments']->count() > 0)
                                            @foreach ($data['complaint']['comments'] as $comment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($comment->from_role == 'Admin')
                                                    <span class="badge badge-primary">Admin</span>
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

<div class="modal fade" id="complaintConfirmModal" tabindex="-1" role="dialog" aria-labelledby="complaintConfirmLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.complaint.confirm', $data['complaint']->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pesan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tambahkan pesan kepada user</p>
                    <div class="form-group">
                        <textarea name="message" id="messageToUserConfirm" cols="30" rows="10" class="form-control" 
                        placeholder="Tulis pesan disini"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" value="Konfirmasi" class="btn btn-primary">
                    </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="complaintRejectModal" tabindex="-1" role="dialog" aria-labelledby="complaintRejectLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.complaint.reject', $data['complaint']->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Batalkan Pesan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tambahkan pesan kepada user</p>
                    
                    <div class="form-group">
                        <textarea name="message" id="messageToUserReject" cols="30" rows="10" class="form-control" 
                        placeholder="Tulis pesan disini"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" value="Konfirmasi" class="btn btn-primary">
                    </div>
            </form>
        </div>
    </div>
</div>



<!-- Logout Modal-->
@livewire('admin.logout-modal')

@endsection

@section('js')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>

    $(document).ready(function () {
        var stringPesanConfirm = "Laporan akan didisposisikan kepada {{ $data['complaint']->position_name }}.";
        $('#messageToUserConfirm').text(stringPesanConfirm);

        var stringPesanReject = "Mohon maaf laporan ini ditolak dikarenakan melanggar peraturan yang berlaku.";
        $('#messageToUserReject').text(stringPesanReject);
    });

</script>
@endsection

