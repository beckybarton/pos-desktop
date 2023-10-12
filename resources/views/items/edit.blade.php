<div id="editItemModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemTypeModal">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit-name">ID:</label>
                        <input type="text" class="form-control edit-id" id="edit-id" name="id" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit-name">Name:</label>
                        <input type="text" class="form-control edit-name" id="edit-name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-name">UOM:</label>
                        <input type="text" class="form-control edit-uom" id="edit-uom" name="uom" required>
                    </div>


                    <div class="form-group">
                        <label for="edit-amount">Amount:</label>
                        <input type="number" class="form-control edit-selling-price" id="edit-selling-price" name="selling_price" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveEdit">Save changes</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>