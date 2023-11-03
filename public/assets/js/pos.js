$(document).ready(function() {
    $("#customerform").submit(function(event) {
      event.preventDefault();

      var formData = new FormData($(this)[0]);

      $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert("Customer saved successfully!");
            $('#addCustomerModal').modal('hide');
        },
        error: function(xhr, status, error) {
          console.error('Error occurred while submitting the form:', error);
          alert('Error occurred while saving customer!');
        }
      });
    });
  });