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
        $('.edit-selling-price').val(item.selling_price);
        $('#editItemForm').attr('action', '/item/' + item.id);
        $('#editItemModal').modal('show');
        console.log(item);
    });

});