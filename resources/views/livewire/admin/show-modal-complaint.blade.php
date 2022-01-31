{{-- Create Complaint Modal with bootstrap 4 --}}
<div class="modal fade" id="show-complaint-modal-{{$complaint->id}}" width tabindex="-1" role="dialog" aria-labelledby="showComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showComplaintModalLabel">Show Complaint</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="font-weight-bold"> {{ $complaint->title }} </h5>
                <p> {{ $complaint->created_at }}</p>
                <p>
                    <img src="{{ $complaint->image }}" alt="{{ $complaint->image }}" class="img-thumbnail">
                </p>
                <p> {{ $complaint->description }} </p>
                <p> {{ $complaint->status }} </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>