$(function () {
  $(document).on('click', 'td[data-role=updateCategory]', function () {
    const id = ($(this).data('id'));
    const category = $(this).closest('tr').children('td:first').text();

    $('#incomeCategoryId').val(id);
    $('#incomeCategory').val(category);
    $('#updateCategoryModal').modal('toggle');
  });


  $('#updateCategory').on('click', function (event) {
    event.preventDefault();

    const category = $('#incomeCategory').val();
    const id = $('#incomeCategoryId').val();


    $.ajax({
      url: '/settings/updateIncomeCategory',
      method: 'POST',
      data: { category: category, id: id },
      traditional: true,
      success: function (response) {

        if (response) {

          $('#' + id).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
          $('#' + id).fadeOut(500, function () {
            $('#' + id).children('td[data-target=income-category]').text(category);
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
