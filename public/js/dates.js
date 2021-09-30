let dateInput = new Date();

let year = dateInput.getFullYear().toString();
let month = (dateInput.getMonth() + 1).toString().padStart(2, 0);
let today = dateInput.getDate().toString().padStart(2, 0);
let yesterday = (dateInput.getDate() - 1).toString().padStart(2, 0);
// let datePattern = year + '-' + month + '-' + today;

document.getElementById('todayDate').value = year + '-' + month + '-' + today;
document.getElementById('yesterdayDate').value = year + '-' + month + '-' + yesterday;
