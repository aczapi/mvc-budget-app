

let date = new Date();

let year = date.getFullYear().toString();
let month = (date.getMonth() + 1).toString().padStart(2, 0);
let today = date.getDate().toString().padStart(2, 0);
let yesterday = (date.getDate() - 1).toString().padStart(2, 0);
// let datePattern = year + '-' + month + '-' + today;

document.getElementById('todayDate').value = year + '/' + month + '/' + today;
document.getElementById('yesterdayDate').value = year + '/' + month + '/' + yesterday;
