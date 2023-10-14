let totalDue = 0;
document.addEventListener('DOMContentLoaded', function() {  
  $(document).ready(function() {
    $('#searchinputitem').on('keyup change', function() {
        var searchText = $('#searchinputitem').val().toLowerCase();

        $('#itemstable tbody tr').each(function() {
            var row = $(this);
            var isMatch = false;

            row.find('td').each(function() {
                var cellText = $(this).text().toLowerCase();
                if (cellText.includes(searchText)) {
                    isMatch = true;
                    return false; 
                }
            });

            row.toggle(isMatch);
        });
    });
  });

  $(document).on('click', '.edit-item', function() {
    var item = $(this).data('item');
    $('.edit-id').val(item.id);
    $('.edit-name').val(item.name);
    $('.edit-uom').val(item.uom);
    $('.edit-selling-price').val(item.selling_price);
    $('#editItemForm').attr('action', '/item/' + item.id);
    $('#editItemModal').modal('show');
    console.log(item);
  });

  $(document).on('keydown', function(event) {
    if (event.key === "F2") {
      event.preventDefault();
      $('#itemSearchModal').modal('show');
      $('#searchItemName').val('');
      // console.log('item');
    }
    
    else if (event.key === "F3") {
      event.preventDefault();
      $('#customerSearchModal').modal('show');
    }

    else if (event.key === "F5") {
      event.preventDefault();
      var customerValue = $('#customer').val();
      if (customerValue.trim() !== ''){
        saveOrder();
      }
      else{
        alert('Add Customer');
      }
      
    }

    else if (event.key === "F6") {
      event.preventDefault();
      $('#addCustomerModal').modal('show');
    }

    else{

    }
  });

  $('#itemSearchModal').on('shown.bs.modal', function () {
    $('#searchItemName').focus();
  });

  $('#customerSearchModal').on('shown.bs.modal', function () {
    $('#searchCustomerName').focus();
  });

  // SEARCH CUSTOMER
  $('#searchCustomerName').on('input', function() {
    event.preventDefault();
    var searchQuery = $('#searchCustomerName').val();
    // console.log(searchQuery);

    $.ajax({
      url: '/search-customers',
      type: 'GET',
      data: { search_query: searchQuery },
      success: function(data) {
        $('#searchResultsCustomerTableBody').empty();

        data.forEach(function(customer) {
            var resultHtml = '<tr>' +
                '<td>' + customer.name + '</td>' +
                '<td><button class="btn btn-primary btn-sm float-end" onclick="addToCustomerField(\'' + customer.id + '\', \'' + customer.name + '\')">Add</button></td>'+
                '</tr>';
            $('#searchResultsCustomerTableBody').append(resultHtml);
            
        });
      }
    });
  });  
    
  // SEARCH ITEMS
  $('#searchItemName').on('input', function() {
    event.preventDefault();
    var searchQuery = $('#searchItemName').val();

    $.ajax({
      url: '/search-items',
      type: 'GET',
      data: { search_query: searchQuery },
      success: function(data) {
        $('#searchResultsTableBody').empty();

        data.forEach(function(item) {
            var formattedPrice = parseFloat(item.selling_price).toFixed(2);
            var resultHtml = '<tr>' +
                '<td>' + item.name + '</td>' +
                '<td>' + formattedPrice + '</td>' +
                '<td>' + item.uom + '</td>' +
                '<td><button class="btn btn-primary btn-sm float-end" onclick="addItemToTable(\'' + item.id + '\', \'' + item.name + '\', \'' + item.uom + '\', \'' + item.selling_price + '\')">Add</button></td>' +
                '</tr>';
            $('#searchResultsTableBody').append(resultHtml);
        });
      }
    });
  });

  $('#selectedItemsTable tbody').on('input', 'input[type="number"]', function() {
    updateDueAmount();
    updateChange();
  });

  $('#loginform').submit(function(event) {
    var selectedLocation = $('#location').val();

    if (selectedLocation === '0') {
        event.preventDefault();

        alert('Please select a valid location.');
    }
  });

  $('#received').on('input', function() {
    // console.log(parseFloat($('#received').val()) || 1);
    updateChange();
  });


});

function updateChange(){
  var totalDue = (parseFloat($('#dueAmount').val()) || 1);
  var received = (parseFloat($('#received').val()) || 0); // - totalDue;
  if(received !==0){
    var change = received - totalDue;
    $('#change').val(change.toLocaleString(undefined,{maximumFractionDigits:2, minimumFractionDigits:2}));
  }
  
  
}

function addToCustomerField(id,name){
  $('#customer').val(id);
  $('#customername').val(name);
  $('#customerSearchModal').modal('hide');
}

function addItemToTable(id, name, uom, selling_price) {
  var quantity = parseFloat($('#quantity_' + id).val()) || 1;
  var formattedSellingPrice = parseFloat(selling_price).toFixed(2);
  var total = quantity * selling_price;
  var formattedTotal = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  var newRow = '<tr>' + 
    '<td class="text-end item_id" name="item_id" input type="text" readonly class="form-control-plaintext" style="width: 30px;">' + id + '</td>' +
    '<td style="display:none" class="text-end"><input name="item_id[]" value="' + id +'" type="text" readonly class="form-control-plaintext" style="width: 30px;">' + '</td>' +
    '<td>' + name + '</td>' +
    '<td>' + uom + '</td>' + 
    '<td class="text-end selling_price" name="selling_price" style="width: 150px;">' + formattedSellingPrice + '</td>' +
    '<td style="padding-top:10px;"><input type="number" name="quantity[]" class="form-control col-md-2 no-border quantity" value="1" id="quantity_' + id + '" oninput="updateTotal(this, ' + formattedSellingPrice + ')"></td>' +
    '<td class="text-end total" id="total_' + id + '">' + formattedTotal + '</td>' +
    '</tr>';
  $('#selectedItemsTable tbody').append(newRow);
  updateDueAmount();
  updateTotal($('#quantity_' + id)[0], selling_price);
  updateChange();
  $('#itemSearchModal').modal('hide');
}

function updateTotal(input, price) {
  var quantity = parseFloat(input.value) || 0; // Parse the quantity as an integer or default to 0
  if(quantity<0){
    input.value = "1";
    quantity = 1;
  }
  var total = quantity * price;
  var formattedTotal = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); // Format total

  // Update the total column in the same row
  var row = input.closest('tr');
  var totalColumn = row.querySelector('#total_' + row.cells[0].textContent); // Assuming the first cell contains the item ID
  totalColumn.textContent = formattedTotal;
}

function updateDueAmount() {
  var totalDues = 0;
  $('#selectedItemsTable tbody tr').each(function() {
      // var quantity = parseFloat($(this).find('input[type="number"]').val()) || 0;
      // var priceText = $(this).find('.selling_price').text().match(/\d+(\.\d+)?/);
      // var price = priceText ? Number(priceText[0]) : 0;

      var priceText = $(this).find('.total').text().match(/\d+(\.\d+)?/);
      var price = priceText ? Number(priceText[0]) : 0;
      totalDues += price;
  });
  

  $('#dueAmount').val(totalDues.toLocaleString(undefined,{maximumFractionDigits:2, minimumFractionDigits:2}));
}

function saveOrder() {
  var formData = $('form').serialize(); // Serialize the form data
  console.log(formData);
  $.ajax({
    url: '/save-order',
    type: 'POST',
    data: formData,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
      alert("Thank you!");
      $('form')[0].reset();
      $('#selectedItemsTable tbody').empty();
      // console.log('Form submitted successfully:', response);
      // Handle the success response as needed
      },
      error: function(error) {
          console.error('Error occurred while submitting the form:', error);
          // Handle errors, if any
      }
  });
}
// function saveOrder() {
//   var formData = $('form').serialize(); // Serialize the form data
//   console.log(formData);
//   $.ajax({
//       url: '/save-order', // Replace with the actual endpoint URL
//   //     type: 'POST',
//   //     data: formData,
//   //     success: function(response) {
//   //         // Handle the success response
//   //         console.log('Order saved successfully:', response);
//   //     },
//   //     error: function(error) {
//   //         // Handle errors, if any
//   //         console.error('Error occurred while saving the order:', error);
//   //     }
//   });
// }
