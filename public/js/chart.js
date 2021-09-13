window.onload = function () {

  if (calculateBalance() != 0) {
    drawPieChart();
  }
}

function drawPieChart() {

  let dataPoint = [];
  let total = calculateAllExpenses();

  $("#expensesTable tbody tr").each(function () {
    dataPoint.push({
      y: ($(this).find("td:last").text() / total),
      name: $(this).find("td:first").text()
    });
  });


  let chart = new CanvasJS.Chart("myChart", {
    animationEnabled: true,
    backgroundColor: "transparent",
    title: {
      text: "All expenses in selected period of time",
      fontFamily: "Della Respira",
      fontColor: "#2C383B",
      fontSize: 24,
    },
    legend: {
      cursor: "pointer",
      itemclick: explodePie
    },
    data: [{
      cursor: "pointer",
      type: "pie",
      fontColor: "#2C383B",
      showInLegend: true,
      toolTipContent: "{name}: <strong>{y}</strong>",
      indexLabel: "{name} - {y}",
      yValueFormatString: "####.0%",
      dataPoints: dataPoint
    }]
  });
  chart.render();

  function explodePie(e) {
    if (typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
      e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
    } else {
      e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
    }
    e.chart.render();

  }

}


function calculateAllExpenses() {

  let expensesTable = document.getElementById("expensesTable");
  let sumOfExpenses = 0;

  $("#expensesTable tbody tr").each(function () {
    sumOfExpenses += Number($(this).find("td:last").text());
  });
  return sumOfExpenses;
};

function calculateAllIncomes() {

  let incomesTable = document.getElementById("incomesTable");
  let sumOfIncomes = 0;
  $("#incomesTable tbody tr").each(function () {
    sumOfIncomes += Number($(this).find("td:last").text());
  });
  return sumOfIncomes;
};

function calculateBalance() {


  let totalIncome = calculateAllIncomes();
  let totalExpense = calculateAllExpenses();
  var balance = Math.round((totalIncome - totalExpense) * 100) / 100;
  var message = "";

  if (balance > 0) { message = "You are doing great! You saved " + balance; }
  else if (balance < 0) { message = "Oh no! Your debt is " + balance; }
  else { message = "Balance: " + balance; }

  $("#balance").html(message);

  if (balance > 0) { $("#balance").css({ 'color': '#16462e', 'font-style': 'italic' }); }
  else if (balance < 0) { $("#balance").css({ 'color': '#7e2128', 'font-style': 'italic' }); }

  return balance;
};

