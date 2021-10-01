$(function () {
  $(document).on('click', 'td[data-role=deleteExpense]', function () {
    const id = ($(this).data('id'));
    const category = $('#expense-' + id).children('td[data-target=expense-category]').text();


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
            url: '/expense/deleteExpense',
            type: 'POST',
            data: { id: id },
            success: function (response) {

              if (response) {
                $('#expense-' + id).css('background', 'lightcoral');
                $('#expense-' + id).fadeOut(1000, function () {
                  $(this).remove();
                });

                const startDate = document.getElementById('startDate').innerHTML;
                const endDate = document.getElementById('endDate').innerHTML;

                $.ajax({
                  url: '/balance/getExpensesSum',
                  method: 'POST',
                  data: { startDate: startDate, endDate: endDate },
                  dataType: 'JSON',
                  traditional: true,
                  success: function (response) {
                    if (response) {

                      let tableBody = $('#expenseTbody');
                      tableBody.empty();
                      buildExpenseTable(response);


                      if (calculateBalance() != 0) {
                        drawPieChart();
                      }
                    }
                  }
                });
              } else {
                bootbox.alert('Expense not deleted.');
              }
            }
          });
        }
      }
    });

  });
});