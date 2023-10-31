<!-- Modal for item search -->
<div class="modal" id="itemSearchModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemSearchModalLabel">Search Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="searchItemName" class="form-label">Item Name:</label>
            <input type="text" class="form-control" id="searchItemName" required autocomplete="off">
          </div>

          <div class="mb-3">
            <table class="table">
              <thead>
                <tr>
                  <th>Item Name</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="searchResultsTableBody">
                  
              </tbody>
            </table>
          </div>
        <div id="searchResults"></div>
      </div>
    </div>
  </div>
</div>
