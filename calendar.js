// Function to display the current month's calendar
function displayCalendar(monthOffset = 0) {
    const monthYearElem = document.getElementById('month-year');
    const calendarBody = document.getElementById('calendar-body');
    
    const currentDate = new Date();
    currentDate.setMonth(currentDate.getMonth() + monthOffset);
    
    const month = currentDate.getMonth();
    const year = currentDate.getFullYear();
    
    // Update the month/year display
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    monthYearElem.textContent = `${monthNames[month]} ${year}`;
    
    // Get the first day of the month and number of days in the month
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    
    const firstDay = firstDayOfMonth.getDay(); // Day of the week (0-6, where 0 is Sunday)
    const totalDaysInMonth = lastDayOfMonth.getDate();
    
    // Clear the existing calendar rows
    calendarBody.innerHTML = '';
    
    let row = document.createElement('tr');
    let dayCounter = 1;
    
    // Create empty cells before the first day
    for (let i = 0; i < firstDay; i++) {
        row.appendChild(document.createElement('td')).classList.add('empty');
    }
    
    // Create cells for each day in the month
    for (let i = firstDay; i < 7; i++) {
        const cell = document.createElement('td');
        cell.textContent = dayCounter;
        if (dayCounter === currentDate.getDate()) {
            cell.classList.add('current-day'); // Highlight current day
        }
        row.appendChild(cell);
        dayCounter++;
    }
    
    // Append the first row
    calendarBody.appendChild(row);
    
    // Create additional rows for remaining days
    while (dayCounter <= totalDaysInMonth) {
        row = document.createElement('tr');
        for (let i = 0; i < 7 && dayCounter <= totalDaysInMonth; i++) {
            const cell = document.createElement('td');
            cell.textContent = dayCounter;
            if (dayCounter === currentDate.getDate()) {
                cell.classList.add('current-day'); // Highlight current day
            }
            row.appendChild(cell);
            dayCounter++;
        }
        calendarBody.appendChild(row);
    }
}

// Initialize the calendar with the current month
displayCalendar();

// Event listeners for navigation buttons
document.getElementById('prev-month').addEventListener('click', () => {
    displayCalendar(-1); // Show previous month
});

document.getElementById('next-month').addEventListener('click', () => {
    displayCalendar(1); // Show next month
});
