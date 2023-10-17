<!-- Modal for item search -->
<div class="modal" id="receivablesModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemSearchModalLabel">List of Receivables</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="receivables" style="height:75vh; overflow-y: auto;" class="table-responsive">
          <input type="text" class="form-control" id="searchInputUnpaidCustomer" placeholder="Search by Customer Name">
          <table class="table table-striped table-bordered table-hover" id="receivablestable">
            <thead class="thead-dark">
              <tr>
                <th class="small">ID</th>
                <th class="small">Customer</th>
                <th class="small text-end">Total Due</th>
                <th class="small">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="mt-4" id="receivables_pagination">
        </div>
      </div>
    </div>
  </div>
</div>
