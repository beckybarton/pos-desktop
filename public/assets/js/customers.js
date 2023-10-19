document.addEventListener('DOMContentLoaded', function() {  
    $(document).on('click', '.view-customer', function() {
        const closestRow = event.target.closest('tr');
        const customerId = closestRow.querySelector('.customer-id').textContent;
        console.log(customerId);
        $.ajax({
            url: '/customer-receivables',
            type: 'GET',
            data: { customerId:  customerId},
            success: function(response) {
              var receivablescustomertable = $('#receivablescustomertable tbody');
              receivablescustomertable.empty();
              // console.log(response.customerorders.original.orders[1].price);
              $.each(response.customerorders.original.orders, function(index, customerorder) {
                  var createdAt = new Date(customerorder.created_at);
                  var formattedDate = createdAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                  var row = $('<tr>');
                  row.append($('<td class="small">').text(String(customerorder.id)));
                  row.append($('<td class="small">').text(formattedDate));
                  row.append($('<td class="small">').text(String(customerorder.item_name)));
                  row.append($('<td class="small text-end">').text(String(customerorder.price.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                  row.append($('<td class="small text-end">').text(String(customerorder.quantity.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                  row.append($('<td class="small text-end">').text(String(((customerorder.price * customerorder.quantity)).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                  receivablescustomertable.append(row);
              });
              var row_unpaid = $('<tr>');
              row_unpaid.append($('<td class="small font-weight-bold" colspan="5"><strong>').text("Total Payables"));
              row_unpaid.append($('<td class="small font-weight-bold text-end" colspan="1"><strong>').text(String(response.customerorders.original.total_payable.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
              receivablescustomertable.append(row_unpaid);

              var row_payment = $('<tr>');
              row_payment.append($('<td class="small font-weight-bold" colspan="5"><strong>').text("Total Payments"));
              row_payment.append($('<td class="small font-weight-bold text-end" colspan="1"><strong>').text(String(response.customerorders.original.total_payment.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
              receivablescustomertable.append(row_payment);
              
              var row_due = $('<tr>');
              row_due.append($('<td class="small text-danger font-weight-bold" colspan="5"><strong>').text("Remaining Due"));
              row_due.append($('<td class="small text-danger font-weight-bold text-end" colspan="1"><strong>').text(String(response.customerorders.original.total_remaining_due.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
              receivablescustomertable.append(row_due);
              

              var customerNameSpan = document.getElementById("customernamereceivables");
              customerNameSpan.textContent = response.customerorders.original.customer_name;
              $('#customer_id').val(customerId);
              $('#receivablesCustomerModal').modal('show');
            },
            error: function(error) {
                console.log(error);
            }
        });

        $('#receivablesCustomerModal').modal('show');
    });
});