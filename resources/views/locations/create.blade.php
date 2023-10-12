<!-- The Modal -->
<div class="modal" id="addLocationModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Location</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="{{route('location.store')}}" method="post">
          @csrf
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" required>
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