$(function () {

  $('#newExpense').on('click', function () {

    bootbox.prompt({
      title: "Add new category",
      placeholder: "Enter a name of the new category:",
      centerVertical: true,
      backdrop: true,
      inputType: 'text',
      required: true,
      buttons: {
        confirm: {
          label: '<i class="bi bi-check-lg"></i> Add',
          className: 'btn modal-select px-0 py-2 m-2'
        },
        cancel: {
          label: '<i class="bi bi-x-lg"></i> Cancel',
          className: 'btn modal-close px-0 py-2 btn-secondary m-2'
        }
      },
      callback: function (result) {

        const category = $('.bootbox-input-text').val();

        if (result) {
          $.ajax({
            url: '/settings/addNewExpenseCategory',
            type: 'POST',
            data: { category: category },
            success: function (response) {
              console.log(response);
              if (response) {
                newRow = '<tr id="' + response + '"><td class="pl-3" data-target="expense-category">' + category + '</td>' +
                  '<td class="pl-3 text-center" data-target="category-limit" data-id="' + response + '"></td> ' +
                  '<td data-role="updateCategory" data-id="' + response + '"><i class="justify-content-center input-group-append bi bi-pencil-square inputGroup-sizing-lg mt-2"></i></td>' +
                  '<td data-role="deleteCategory" data-id="' + response + '"><i class="justify-content-center input-group-append bi bi-trash inputGroup-sizing-lg mt-2"></i></td></tr>'
                $('table tbody').append(newRow);
              } else {
                bootbox.alert({
                  message: '<i class="bi bi-exclamation-square pr-2"></i>Category already exists!',
                  centerVertical: true,
                  backdrop: true,
                });
              }
            }
          });
        }
      }
    });
  });
});