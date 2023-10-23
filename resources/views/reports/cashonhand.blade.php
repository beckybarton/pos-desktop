<!-- Modal for item search -->
<div class="modal" id="cashonhandModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="itemSearchModalLabel">Record Cash on Hand</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="cashonhand">
                <form action="{{ route('report.dailysave') }}" method="post">
                    <div class="row">
                        <div class="col-md-3">Denomination</div>
                        <div class="col-md-3">Pieces</div>
                        <div class="col-md-3">Amount</div>
                    </div>
                    <div id="cashonhanddenominations">

                    </div>
                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
  