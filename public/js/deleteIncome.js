
$(function () {
  $(document).on('click', 'td[data-role=deleteIncome]', function () {
    const id = ($(this).data('id'));
    const category = $('#income-' + id).children('td[data-target=income-category]').text();

    bootbox.confirm({
      message: "Do you really want to delete income?",
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
            url: '/income/deleteIncome',
            type: 'POST',
            data: { id: id },
            success: function (response) {
              if (response) {
                $('#income-' + id).css('background', 'lightcoral');
                $('#income-' + id).fadeOut(1000, function () {
                  $(this).remove();
                });

                const startDate = document.getElementById('startDate').innerHTML;
                const endDate = document.getElementById('endDate').innerHTML;

                $.ajax({
                  url: '/balance/updateIncomesSum',
                  method: 'POST',
                  data: { startDate: startDate, endDate: endDate, category: category },
                  traditional: true,
                  success: function (response) {
                    if (response == 0) {
                      $('#income-' + category).css('background', 'lightcoral');
                      $('#income-' + category).fadeOut(1000, function () {
                        $(this).remove();
                        if (calculateBalance() != 0) {
                          drawPieChart();

                        }
                      });
                    } else {
                      $('#income-' + category).children('td[data-target=income-sum]').text(response);
                    }

                    if (calculateBalance() != 0) {
                      drawPieChart();

                    }
                  }
                });
              } else {
                bootbox.alert('Income not deleted.');
              }
            }
          });
        }
      }
    });
  });
});