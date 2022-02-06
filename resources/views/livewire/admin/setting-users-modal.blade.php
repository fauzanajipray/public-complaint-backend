{{-- Create Complaint Modal with bootstrap 4 --}}
<div class="modal fade" id="settingsUserModal" width tabindex="-1" role="dialog" aria-labelledby="settingsUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="settingsUserModalLabel">Pengaturan User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('admin/user/setting/'.$user->id) }}" method="POSt">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="custom-select" id="roleSelect" name="role">
                            <option value="1" {{ ($user->role_id == 1) ? "selected" : '' }}>Admin</option>
                            <option value="2" {{ ($user->role_id == 2) ? "selected" : '' }}>Pengadu</option>
                            <option value="3" {{ ($user->role_id == 3) ? "selected" : '' }}>Staff</option>
                    </select>
                    </div>
                    <div class="form-group d-none" id="positionSelect">
                        <label for="position">Posisi</label>
                        <select class="custom-select" id="position" name="position">
                            <option value="" {{ (!$user->position_id) ? "selected" : '' }}>Tambahkan Jabatan</option>
                            @if($user->position_id != null)
                            <option value="{{ $user->position->id }}" selected>{{ $user->position->name }}</option>
                            @endif
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ ($user->position_id  == $position->id) ? "selected" : '' }}>{{ $position->name }} </option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Masukkan passwordmu</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <input type="submit" class="btn btn-primary" value="Konfirmasi">
                </div>
            </form>
        </div>
    </div>
</div>