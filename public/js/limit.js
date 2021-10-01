$(function () {

  // const date = document.getElementById('todayDate').innerHTML;

  $('#inputAmount').on("keyup keydown change", function () {
    const category = $('#expenseCategory').val();
    const amount = $(this).val();
    const date = $('#todayDate').val();

    $.ajax({
      url: "/expense/getLimit",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {
        $('#showLimit').html(response);
      }
    });
  });

  $('#expenseCategory').on('change', function () {
    const category = $(this).val();
    const amount = $('#inputAmount').val();
    const date = $('#todayDate').val();
    $.ajax({
      url: "/expense/getLimit",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {
        $('#showLimit').html(response);
      }
    });
  });

  $('#inputAmount').on("keyup keydown change", function () {
    const amount = $(this).val();
    const category = $('#expenseCategory').val();
    const date = $('#todayDate').val();

    $.ajax({
      url: "/expense/getValue",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {
        if (!response) {
          $('#showLimit').removeClass('alert-danger');
          $('#showLimit').addClass('alert-success');

        } else {
          $('#showLimit').removeClass('alert-success');
          $('#showLimit').addClass('alert-danger');
        }
      }
    });
  });

  $('#expenseCategory').on('change', function () {
    const category = $(this).val();
    const amount = $('#inputAmount').val();
    const date = $('#todayDate').val();

    $.ajax({
      url: "/expense/getValue",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {
        if (!response) {
          $('#showLimit').removeClass('alert-danger');
          $('#showLimit').addClass('alert-success');

        } else {
          $('#showLimit').removeClass('alert-success');
          $('#showLimit').addClass('alert-danger');
        }
      }
    });
  });

  $('#todayDate').on('change', function () {
    const category = $('#expenseCategory').val();
    const amount = $('#inputAmount').val();
    const date = $(this).val();

    $.ajax({
      url: "/expense/getLimit",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {
        $('#showLimit').html(response);
      }
    });
  });

  $('#todayDate').on('change', function () {
    const category = $('#expenseCategory').val();
    const amount = $('#inputAmount').val();
    const date = $(this).val();

    $.ajax({
      url: "/expense/getValue",
      method: "POST",
      data: {
        category: category, amount: amount, date: date
      },
      success: function (response) {


        if (!response) {
          $('#showLimit').removeClass('alert-danger');
          $('#showLimit').addClass('alert-success');

        } else {
          $('#showLimit').removeClass('alert-success');
          $('#showLimit').addClass('alert-danger');
        }
      }
    });
  });

});