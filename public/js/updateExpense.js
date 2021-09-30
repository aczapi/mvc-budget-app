$(function () {
  $(document).on('click', 'td[data-role=updateExpense]', function () {
    const id = ($(this).data('id'));
    const category = $('#expense-' + id).children('td[data-target=expense-category]').text();
    const amount = $('#expense-' + id).children('td[data-target=expense-amount]').text();
    const date = $('#expense-' + id).children('td[data-target=expense-date]').text();
    const paymentMethod = $('#expense-' + id).children('td[data-target=expense-paymentMethod]').text();
    const comment = $('#expense-' + id).children('td[data-target=expense-comment]').text();


    $('#inputAmount').val(amount);
    $('#selectedDate').val(date);
    $('#category').val(category);
    $('#payment').val(paymentMethod);
    $('#comment').val(comment);
    $('#expenseId').val(id);
    $('#editExpenseModal').modal('toggle');

  });

  $('#expenseSubmit').on('click', function (event) {
    event.preventDefault();

    const id = $('#expenseId').val();
    const amount = $('#inputAmount').val();
    const date = $('#selectedDate').val();
    const category = $('#category').val();
    const payment = $('#payment').val();
    const comment = $('#comment').val();
    const categoryOriginal = $('#expense-' + id).children('td[data-target=expense-category]').text();


    const startDate = document.getElementById('startDate').innerHTML;
    const endDate = document.getElementById('endDate').innerHTML;

    let date_strToDate = formatDate(date);
    let startDate_strToDate = formatDate(startDate);
    let endDate_strToDate = formatDate(endDate);

    $.ajax({
      url: '/expense/updateExpense',
      method: 'POST',
      data: { amount: amount, date: date, category: category, payment: payment, comment: comment, id: id },
      traditional: true,
      success: function (response) {

        if (response) {
          if (date_strToDate < startDate_strToDate || date_strToDate > endDate_strToDate || amount == 0) {
            $('#expense-' + id).css('background', 'lightcoral');
            $('#expense-' + id).fadeOut(1000, function () {
              $(this).remove();
            });
          } else {

            $('#expense-' + id).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
            $('#expense-' + id).fadeOut(500, function () {
              $('#expense-' + id).children('td[data-target=expense-amount]').text(parseFloat(amount).toFixed(2));
              $('#expense-' + id).children('td[data-target=expense-date]').text(date);
              $('#expense-' + id).children('td[data-target=expense-category]').text(category);
              $('#expense-' + id).children('td[data-target=expense-paymentMethod]').text(payment);
              $('#expense-' + id).children('td[data-target=expense-comment]').text(comment);
              $(this).fadeIn(0);
              $('#expense-' + id).css({ "background-color": "transparent", "opacity": "1.0" });
            });
          }


          $.ajax({
            url: '/balance/updateExpensesSum',
            method: 'POST',
            data: { startDate: startDate, endDate: endDate, category: category },
            traditional: true,
            success: function (response) {

              if (response == 0) {
                $('#expense-' + category).css('background', 'lightcoral');
                $('#expense-' + category).fadeOut(1000, function () {
                  $(this).remove();
                  if (calculateBalance() != 0) {
                    drawPieChart();
                  }
                });
              } else {
                $('#expense-' + category).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
                $('#expense-' + category).fadeOut(500, function () {
                  $('#expense-' + category).children('td[data-target=expense-sum]').text(response);
                  $(this).fadeIn(0);
                  $('#expense-' + category).css({ "background-color": "transparent", "opacity": "1.0" });


                  if (calculateBalance() != 0) {
                    drawPieChart();
                  }
                });

              }

            }

          });

          // if (category != categoryOriginal) {
          $.ajax({
            url: '/balance/updateExpensesSum',
            method: 'POST',
            data: { startDate: startDate, endDate: endDate, category: categoryOriginal },
            traditional: true,
            success: function (response) {

              if (response == 0) {
                $('#expense-' + categoryOriginal).css('background', 'lightcoral');
                $('#expense-' + categoryOriginal).fadeOut(1000, function () {
                  $(this).remove();
                  if (calculateBalance() != 0) {
                    drawPieChart();
                  }
                });
              } else {
                $('#expense-' + categoryOriginal).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
                $('#expense-' + categoryOriginal).fadeOut(500, function () {
                  $('#expense-' + categoryOriginal).children('td[data-target=expense-sum]').text(response);
                  $(this).fadeIn(0);
                  $('#expense-' + categoryOriginal).css({ "background-color": "transparent", "opacity": "1.0" });


                  if (calculateBalance() != 0) {
                    drawPieChart();
                  }
                });

              }

            }

          });
          // }

        } else {
          bootbox.alert('Expense not updated.');

        }
        $('#editExpenseModal').modal('toggle');

      }
    });

  });

});
