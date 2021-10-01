$(function () {
  $(document).on('click', 'td[data-role=updateIncome]', function () {
    const id = ($(this).data('id'));
    const category = $('#income-' + id).children('td[data-target=income-category]').text();
    const amount = $('#income-' + id).children('td[data-target=income-amount]').text();
    const date = $('#income-' + id).children('td[data-target=income-date]').text();
    const comment = $('#income-' + id).children('td[data-target=income-comment]').text();

    $('#incomeAmount').val(amount);
    $('#selectedIncomeDate').val(date);
    $('#incomeCategory').val(category);
    $('#incomeComment').val(comment);
    $('#incomeId').val(id);
    $('#editIncomeModal').modal('toggle');

  });


  $('#incomeSubmit').on('click', function (event) {
    event.preventDefault();

    const id = $('#incomeId').val();
    const amount = $('#incomeAmount').val();
    const date = $('#selectedIncomeDate').val();
    const category = $('#incomeCategory').val();
    const comment = $('#incomeComment').val();
    const categoryOriginal = $('#income-' + id).children('td[data-target=income-category]').text();

    const startDate = document.getElementById('startDate').innerHTML;
    const endDate = document.getElementById('endDate').innerHTML;

    let date_strToDate = formatDate(date);
    let startDate_strToDate = formatDate(startDate);
    let endDate_strToDate = formatDate(endDate);

    $.ajax({
      url: '/income/updateIncome',
      method: 'POST',
      data: { amount: amount, date: date, category: category, comment: comment, id: id },
      traditional: true,
      success: function (response) {

        if (response) {
          if (date_strToDate < startDate_strToDate || date_strToDate > endDate_strToDate || amount == 0) {
            $('#income-' + id).css('background', 'lightcoral');
            $('#income-' + id).fadeOut(1000, function () {
              $(this).remove();
            });
          } else {

            $('#income-' + id).css({ "background-color": "#ffe7c9", "opacity": "0.5" });
            $('#income-' + id).fadeOut(500, function () {
              $('#income-' + id).children('td[data-target=income-amount]').text(parseFloat(amount).toFixed(2));
              $('#income-' + id).children('td[data-target=income-date]').text(date);
              $('#income-' + id).children('td[data-target=income-category]').text(category);
              $('#income-' + id).children('td[data-target=income-comment]').text(comment);
              $(this).fadeIn(0);
              $('#income-' + id).css({ "background-color": "transparent", "opacity": "1.0" });
            });


          }

          $.ajax({
            url: '/balance/getIncomesSum',
            method: 'POST',
            data: { startDate: startDate, endDate: endDate },
            dataType: "JSON",
            traditional: true,
            success: function (response) {

              if (response) {

                let tableBody = $('#incomeTbody');
                tableBody.empty();
                buildIncomeTable(response);

                // for (let i = 0; i < response.length; i++) {
                //   let row = '<tr id="income-"' + response[i].income_category_name + '><td data-target="income-name" class="pl-3">' + response[i].income_category_name + '</td>' + '<td data-target="income-sum" class="text-center">' + response[i].sum_incomes + '</td></tr>'
                //   $('table #incomeTbody').append(row);
                // }
                if (calculateBalance() != 0) {
                  drawPieChart();
                }

              }

            }
          });

        } else {
          bootbox.alert('Income not updated.');
        }
        $('#editIncomeModal').modal('toggle');

      }
    });
  });
});
