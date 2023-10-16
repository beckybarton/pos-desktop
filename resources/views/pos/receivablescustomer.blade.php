<!-- Modal for item search -->
<div class="modal fade" id="receivablesCustomerModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemSearchModalLabel">Unpaid Transactions of <span id="customernamereceivables"> </span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="receivablescustomer" style="height:75vh; overflow-y: auto;" class="table-responsive">
          <table class="table table-striped table-bordered table-hover" id="receivablescustomertable">
            <thead class="thead-dark">
              <tr>
                <th class="small">ID</th>
                <th class="small">Date</th>
                <th class="small">Item</th>
                <th class="small text-end">Unit Price</th>
                <th class="small text-end">Quantity</th>
                <th class="small text-end">Total</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
