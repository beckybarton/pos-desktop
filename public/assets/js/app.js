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

    else if (event.key === "F7") {
      event.preventDefault();
      viewreceivables();
      // $('#receivablesModal').modal('show');
      // console.log('view ar list');
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

  $('#addCustomerModal').on('shown.bs.modal', function () {
    $('#customername').focus();
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
    updateChange();
  });

  $(document).ready(function() {
    $('#searchInputUnpaidCustomer').on('keyup change', function() {
        var searchText = $('#searchInputUnpaidCustomer').val().toLowerCase();

        $('#receivablestable tbody tr').each(function() {
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
  console.log($('#customer').val());
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
      var priceText = $(this).find('.total').text().match(/\d+(\.\d+)?/);
      var price = priceText ? Number(priceText[0]) : 0;
      totalDues += price;
  });
  

  $('#dueAmount').val(totalDues.toLocaleString(undefined,{maximumFractionDigits:2, minimumFractionDigits:2}));
}

function saveOrder() {
  var formData = $('form').serialize(); // Serialize the form data
  // console.log(formData);
  $.ajax({
    url: '/save-order',
    type: 'POST',
    data: formData,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        alert("Transaction saved!");
        $('form')[0].reset();
        $('#selectedItemsTable tbody').empty();
      },
      error: function(error) {
          console.error('Error occurred while submitting the form:', error);
      }
  });
}

function viewreceivables(){
  // var paginationLinksHTML = '{{ $customerNames->links("pagination::bootstrap-4") }}';
 
  $.ajax({
    url: '/view-receivables',
    type: 'GET',
    
    // success: function(response) {
    //   var tableBody = $('#receivablestable tbody');
    //   tableBody.empty();
    //   $.each(response.orders.original.orders, function(index, order) {
    //       var row = $('<tr>');
    //       row.append($('<td class="small">').text(String(order.customer_id)));
    //       row.append($('<td class="small">').text(order.customer_name));
    //       row.append($('<td class="small text-end">').text(order.total_price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })));  
    //       row.append($('<td class="small">').append($('<button class="btn btn-primary btn-sm">VIEW</button>')
    //           .click(function() {
    //               $.ajax({
    //                   url: '/customer-receivables',
    //                   type: 'GET',
    //                   data: { customerId: order.customer_id },
    //                   success: function(response) {
    //                       var receivablescustomertable = $('#receivablescustomertable tbody');
    //                       receivablescustomertable.empty();
    //                       $.each(response.customerorders.original.orders, function(index, customerorder) {
    //                         var createdAt = new Date(customerorder.created_at);
    //                         var formattedDate = createdAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    //                         var row = $('<tr>');
    //                         row.append($('<td class="small">').text(String(customerorder.id)));
    //                         row.append($('<td class="small">').text(formattedDate));
    //                         row.append($('<td class="small">').text(String(customerorder.item_name)));
    //                         row.append($('<td class="small text-end">').text(String(customerorder.price)));
    //                         row.append($('<td class="small text-end">').text(String(customerorder.quantity)));
    //                         row.append($('<td class="small text-end">').text(String(customerorder.total_price)));
    //                         receivablescustomertable.append(row);
    //                       });
  
    //                       $('#receivablesCustomerModal').modal('show');
    //                   },
    //                   error: function(error) {
    //                       console.log(error);
    //                   }
    //               });
    //           })));
    //       tableBody.append(row);
    //   });
  
    //   $('#receivablesModal').modal('show');
    // },
    success: function(response) {
      var tableBody = $('#receivablestable tbody');
      tableBody.empty();
  
      $.each(response.orders.original.orders, function(index, order) {
          var row = $('<tr>');
          row.append($('<td class="small">').text(String(order.customer_id)));
          row.append($('<td class="small">').text(order.customer_name));
          row.append($('<td class="small text-end">').text(order.total_price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })));  
          row.append($('<td class="small">').append($('<button class="btn btn-primary btn-sm">VIEW</button>')
              .click(function() {
                  $('#receivablesModal').modal('hide'); // Hide the main modal temporarily
  
                  $.ajax({
                      url: '/customer-receivables',
                      type: 'GET',
                      data: { customerId: order.customer_id },
                      success: function(response) {
                        var receivablescustomertable = $('#receivablescustomertable tbody');
                        receivablescustomertable.empty();
                        $.each(response.customerorders.original.orders, function(index, customerorder) {
                            var createdAt = new Date(customerorder.created_at);
                            var formattedDate = createdAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                            var row = $('<tr>');
                            row.append($('<td class="small">').text(String(customerorder.id)));
                            row.append($('<td class="small">').text(formattedDate));
                            row.append($('<td class="small">').text(String(customerorder.item_name)));
                            row.append($('<td class="small text-end">').text(String(customerorder.price.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                            row.append($('<td class="small text-end">').text(String(customerorder.quantity.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                            row.append($('<td class="small text-end">').text(String((customerorder.total_price).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                            receivablescustomertable.append(row);
                        });
                        var row = $('<tr>');
                        row.append($('<td class="small text-danger font-weight-bold" colspan="5"><strong>').text("Total"));
                        row.append($('<td class="small text-danger font-weight-bold text-end" colspan="1"><strong>').text(String(response.customerorders.original.totalPriceSum.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                        receivablescustomertable.append(row);

                        var customerNameSpan = document.getElementById("customernamereceivables");
                        customerNameSpan.textContent = response.customerorders.original.customer_name;
                        // console.log(response.customerorders.original.customer_name);
  
                        $('#receivablesCustomerModal').modal('show');
                      },
                      error: function(error) {
                          console.log(error);
                      }
                  });
              })));
          tableBody.append(row);
      });
  
      $('#receivablesModal').modal('show'); // Show the main modal again when the loop finishes
      $('#receivablesCustomerModal').on('hidden.bs.modal', function (e) {
        $('#receivablesModal').modal('show'); // Show the main modal when the customer modal is closed
    });
    },
  
    error: function(xhr, status, error) {
        // Handle AJAX errors here
        console.error(error);
    }
  
  });
}