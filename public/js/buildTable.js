


function buildIncomeTable(data) {
  let table = document.getElementById('incomeTbody');

  for (let i = 0; i < data.length; i++) {
    let row = '<tr id="income-' + data[i].income_category_name + '"><td data-target="income-name" class="pl-3">' + data[i].income_category_name + '</td>' + '<td data-target="income-sum" class="text-center">' + data[i].sum_incomes + '</td></tr>'
    table.innerHTML += row;
  }
}

function buildExpenseTable(data) {
  let table = document.getElementById('expenseTbody');

  for (let i = 0; i < data.length; i++) {
    let row = '<tr id="expense-' + data[i].expense_category_name + '"><td data-target="expense-name" class="pl-3">' + data[i].expense_category_name + '</td>' + '<td data-target="expense-sum" class="text-center">' + data[i].sum_expenses + '</td></tr>'
    table.innerHTML += row;
  }
}

window.onload = function drawTables() {

  const startDate = document.getElementById('startDate').innerHTML;
  const endDate = document.getElementById('endDate').innerHTML;

  let myIncomeArray = [];
  let myExpenseArray = [];

  buildIncomeTable(myIncomeArray);
  buildExpenseTable(myExpenseArray);


  $.ajax({

    url: '/balance/getIncomesSum',
    method: 'POST',
    data: { startDate: startDate, endDate: endDate },
    dataType: "JSON",
    traditional: true,
    success: function (response) {
      if (response) {
        myIncomeArray = response;
        buildIncomeTable(myIncomeArray);
      }
    }
  });

  $.ajax({

    url: '/balance/getExpensesSum',
    method: 'POST',
    data: { startDate: startDate, endDate: endDate },
    dataType: "JSON",
    traditional: true,
    success: function (response) {
      if (response) {
        myExpenseArray = response;
        buildExpenseTable(myExpenseArray);
        if (calculateBalance() != 0) {
          drawPieChart();
        }
      }
    }
  });

}


