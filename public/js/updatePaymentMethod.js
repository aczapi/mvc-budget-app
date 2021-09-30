$(function () {
  $(document).on('click', 'td[data-role=updatePayment]', function () {
    const id = ($(this).data('id'));
    const payment = $(this).closest('tr').children('td:first').text();

    $('#paymentMethodId').val(id);
    $('#paymentMethod').val(payment);
    $('#updatePaymentModal').modal('toggle');
  });


  $('#updatePayment').on('click', function (event) {
    event.preventDefault();

    const payment = $('#paymentMethod').val();
    const id = $('#paymentMethodId').val();


    $.ajax({
      url: '/settings/updatePaymentMethod',
      method: 'POST',
      data: { payment: payment, id: id },
      traditional: true,
      success: function (response) {

        if (response) {

          $('#' + id).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
          $('#' + id).fadeOut(500, function () {
            $('#' + id).children('td[data-target=payment-name]').text(payment);
            $(this).fadeIn(0);
            $('#' + id).css({ "background-color": "transparent", "opacity": "1.0" });
          });

        } else {
          bootbox.alert({
            message: '<i class="bi bi-exclamation-square pr-2"></i>Payment method already exists!',
            centerVertical: true,
            backdrop: true,
          });
        }
        $('#updatePaymentModal').modal('toggle');
      }
    });

  });
});