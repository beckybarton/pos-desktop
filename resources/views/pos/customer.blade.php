<!-- Modal for item search -->
<div class="modal" id="customerSearchModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemSearchModalLabel">Search Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Search Form -->
        <!-- <form id="itemSearchForm"> -->
          <div class="mb-3">
            <label for="searchCustomerName" class="form-label">Customer Name:</label>
            <input type="text" class="form-control" id="searchCustomerName" required>
          </div>

          <div class="mb-3">
          <table class="table">
            <thead>
              <tr>
                <th>Customer Name</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="searchResultsCustomerTableBody">
                
            </tbody>
        </table>
          </div>
        <!-- </form> -->
        
        <div id="searchResultsCustomer"></div>
      </div>
    </div>
  </div>
</div>
