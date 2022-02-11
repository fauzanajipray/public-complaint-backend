
<div class="modal fade" id="deletePosition" tabindex="-1" role="dialog" aria-labelledby="deletePositionModalLabel"
aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.position.destroy', $position->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePositionModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <p>Apakah yakin ingin menghapus data?</p>

                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Masukkan Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <input type="submit" value="Konfirmasi" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>