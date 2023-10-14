<!-- The Modal -->
<div class="modal" id="addItemModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Items</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="{{route('item.store')}}" method="post">
          @csrf
          <div class="form-group">
            <label for="category">Category</label>
            <select class="form-select" id="category" name="category_id" required>
                <option value="0">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{ $category->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="form-group">
            <label for="uom">UOM:</label>
            <input type="text" class="form-control" name="uom" required>
          </div>
          <div class="form-group">
            <label for="amount">Selling Price:</label>
            <input type="number" class="form-control" name="selling_price" required>
          </div>
          <div class="modal-footer">
            <div class="form-group">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>  
              <input type="submit" class="btn btn-success" value="Save">  
            </div>
           </div>
        </form>
      </div>

    </div>
  </div>
</div>