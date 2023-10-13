<div class="modal" tabindex="-1" role="dialog" id="locationModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
        <!-- Form inside the modal -->
            <form id="locationForm">
            <!-- Location select field -->
            <div class="form-group">
                <label for="location">Location</label>
                <select class="form-control" id="location" name="location">
                    @foreach($locations as $location)
                        <option value="{{$location->id}}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <p></p>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="form-group">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
  </div>
</div>