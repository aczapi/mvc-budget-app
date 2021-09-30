function formatDate(date_str) {
  let formatDate;
  if (date_str.length == 10) {
    let mon1 = parseInt(date_str.substring(5, 7));
    let dt1 = parseInt(date_str.substring(8, 10));
    let yr1 = parseInt(date_str.substring(0, 4));

    formatDate = new Date(yr1, mon1 - 1, dt1);

  } else {
    let mon1 = parseInt(date_str.substring(6, 8));
    let dt1 = parseInt(date_str.substring(9, 11));
    let yr1 = parseInt(date_str.substring(1, 5));

    formatDate = new Date(yr1, mon1 - 1, dt1);
  }

  return formatDate;
}