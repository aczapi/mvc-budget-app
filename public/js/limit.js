$(function () {
  $('#inputAmount').on("keyup keydown change", function () {
    const category = $('#expenseCategory').val();
    const amount = $(this).val();

    $.ajax({
      url: "/expense/getLimit",
      method: "POST",
      data: {
        category: category, amount: amount
      },
      success: function (response) {
        $('#showLimit').html(response);
      }
    });
  });

  $('#expenseCategory').on('change', function () {
    const category = $(this).val();
    const amount = $('#inputAmount').val();
    $.ajax({
      url: "/expense/getLimit",
      method: "POST",
      data: {
        category: category, amount: amount
      },
      success: function (response) {
        $('#showLimit').html(response);
      }
    });
  });

  $('#inputAmount').on("keyup keydown change", function () {
    const amount = $(this).val();
    const category = $('#expenseCategory').val();

    $.ajax({
      url: "/expense/getValue",
      method: "POST",
      data: {
        category: category, amount: amount
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

    $.ajax({
      url: "/expense/getValue",
      method: "POST",
      data: {
        category: category, amount: amount
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