
$(function () {
  $(document).on('click', 'td[data-role=deleteCategory]', function () {
    const id = ($(this).data('id'));
    const category = $(this).closest('tr').children('td:first').text();

    bootbox.confirm({
      message: "Do you really want to delete expense?",
      centerVertical: true,
      backdrop: true,
      buttons: {
        confirm: {
          label: '<i class="bi bi-check-lg"></i> Ok',
          className: 'btn modal-select px-0 py-2 m-2'
        },
        cancel: {
          label: '<i class="bi bi-x-lg"></i> Cancel',
          className: 'btn modal-close px-0 py-2 btn-secondary m-2'
        }
      },
      callback: function (result) {
        if (result) {

          $.ajax({
            url: '/settings/deleteExpenseCategory',
            type: 'POST',
            data: { category: category },
            success: function (response) {

              if (response) {
                $('#' + id).css('background', 'lightcoral');
                $('#' + id).fadeOut(1000, function () {
                  $(this).remove();
                });
                bootbox.alert({
                  message: '<i class="bi bi-exclamation-square pr-2"></i>Incomes in this category have been moved to the "Another"!',
                  centerVertical: true,
                  backdrop: true,
                });
              } else {
                bootbox.alert('Record not deleted.');
              }
            }
          });
        }
      }
    });

  });
});