$(function () {
  $(document).on('click', 'td[data-role=updateCategory]', function () {
    const id = ($(this).data('id'));
    const category = $(this).closest('tr').children('td:first').text();
    const categoryLimit = $('#' + id).children('td[data-target=category-limit]').text();

    $('#expenseCategoryId').val(id);
    $('#expenseCategory').val(category);
    $('#limit').val(categoryLimit);
    $('#updateCategoryModal').modal('toggle');
  });


  $('#updateCategory').on('click', function (event) {
    event.preventDefault();

    const category = $('#expenseCategory').val();
    const id = $('#expenseCategoryId').val();
    const categoryLimit = $('#limit').val();

    $.ajax({
      url: '/settings/updateExpenseCategory',
      method: 'POST',
      data: { category: category, id: id, categoryLimit: categoryLimit },
      traditional: true,
      success: function (response) {

        if (response) {

          $('#' + id).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
          $('#' + id).fadeOut(500, function () {
            if (categoryLimit == "") {
              $('#' + id).children('td[data-target=category-limit]').text(categoryLimit);
            } else {
              $('#' + id).children('td[data-target=category-limit]').text(parseFloat(categoryLimit).toFixed(2));
            }
            $('#' + id).children('td[data-target=expense-category]').text(category);
            $(this).fadeIn(0);
            $('#' + id).css({ "background-color": "transparent", "opacity": "1.0" });
          });

        } else {
          bootbox.alert({
            message: '<i class="bi bi-exclamation-square pr-2"></i>Category already exists!',
            centerVertical: true,
            backdrop: true,
          });
        }
        $('#updateCategoryModal').modal('toggle');
      }
    });

  });
});