<!-- Modal for item search -->
<div class="modal" id="receivablesCustomerModal">
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
        
        <form name="receive_payment_form" id="receive_payment_form" action="{{route('pos.receivepayment')}}" method="post">
          @csrf
          <input type="text" hidden class="form-control" id="customer_id" name="customer_id">
          <div class="row">
            <div class="col-md-3">
              <label for="payment_received" class="form-label">Payment Received:</label>
            </div>
            <div class="col-md-3">
              <input type="number" class="form-control" id="payment_received" min="1" name="payment_received">
            </div>
            <div class="col-md-3">
              <select class="form-select" id="method_received" name="method_received" required>
                <option value="Cash">Cash</option>
                <option value="Gcash">Gcash</option>
                <option value="Card">Card</option>
              </select>
            
            </div>

            <div class="col-md-3">
              <label>&nbsp;</label> 
              <!-- <button type="submit" class="btn btn-success btn-block">Receive Payment</button> -->
              <button type="button" class="btn btn-success btn-block" id="receivePaymentBtn">Receive Payment</button>
            </div>
          </div>
        </form>
        <hr>
        <div class="footer" id="downloadbuttondiv">
          
        </div>
      </div>
    </div>
  </div>
</div>