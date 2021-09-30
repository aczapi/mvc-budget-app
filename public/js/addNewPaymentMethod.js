$(function () {

  $('#newPayment').on('click', function () {

    bootbox.prompt({
      title: "Add new payment method",
      placeholder: "Enter a name of the new payment method:",
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

        const payment = $('.bootbox-input-text').val();

        if (result) {
          $.ajax({
            url: '/settings/addNewPaymentMethod',
            type: 'POST',
            data: { payment: payment },
            success: function (response) {
              if (response) {
                newRow = '<tr id="' + response + '"><td class="pl-3" data-target="payment-name">' + payment + '</td>' +
                  '<td data-role="updatePayment" data-id="' + response + '"><i class="justify-content-center input-group-append bi bi-pencil-square inputGroup-sizing-lg mt-2"></i></td>' +
                  '<td data-role="deletePayment" data-id="' + response + '"><i class="justify-content-center input-group-append bi bi-trash inputGroup-sizing-lg mt-2"></i></td></tr>'
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