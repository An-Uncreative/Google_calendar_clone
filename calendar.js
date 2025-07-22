const calendarEl = document.getElementById('calendar');
const monthYearEl = document.getElementById('monthYear');
const modalEl = document.getElementById('eventModal');
let currentDate = new Date();

function renderCalendar(date = new Date()) {
    console.log('Rendering calendar for:', date);
    console.log('Events data:', events);
    
    calendarEl.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();
    const today = new Date();

    console.log('Year:', year, 'Month:', month);

    const totalDays = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    console.log('Total days:', totalDays, 'First day:', firstDay);

    // Display month and year
    monthYearEl.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekDays.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.className = 'day-name';
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl);
    });

    for (let i = 0; i < firstDay; i++) {
        calendarEl.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        const cell = document.createElement('div');
        cell.className = 'day';

        if(
            day=== today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ) {
            cell.classList.add('today');
        }

        const dayEl = document.createElement('div');
        dayEl.className = 'date-number';
        dayEl.textContent = day;
        cell.appendChild(dayEl);

        const eventToday = events[dateStr] || [];
        const eventBox = document.createElement('div');
        eventBox.className = 'events';

        // Render events for the day
        eventToday.forEach(event => {
            const ev = document.createElement('div');
            ev.className = 'event';

            const courseEl = document.createElement('div');
            courseEl.className = 'course';
            courseEl.textContent = event.title.split(' - ')[0];

            const instructorEl = document.createElement('div');
            instructorEl.className = 'instructor';
            instructorEl.textContent = event.title.split(' - ')[1];

            const timeEl = document.createElement('div');
            timeEl.className = 'time';
            timeEl.textContent = event.start_time + ' - ' + event.end_time;

            ev.appendChild(courseEl);
            ev.appendChild(instructorEl);
            ev.appendChild(timeEl);
            eventBox.appendChild(ev);
        });

        //overlay buttons
        const overlay = document.createElement('div');
        overlay.className = 'day-overlay';

        const addBtn = document.createElement('button');
        addBtn.className = 'overlay-btn';
        addBtn.textContent = '+ Add';
        addBtn.onclick = e => {
            e.stopPropagation();
            openModalForAdd(dateStr);
        };

        overlay.appendChild(addBtn);

        if (eventToday.length > 0) {
            const editBtn = document.createElement('button');
            editBtn.className = 'overlay-btn';
            editBtn.textContent = 'Edit';
            editBtn.onclick = e => {
                e.stopPropagation();
                openModalForEdit(eventToday);
            };
            overlay.appendChild(editBtn);
        }

        cell.appendChild(overlay);
        cell.appendChild(eventBox);
        calendarEl.appendChild(cell);
    }
}


function openModalForAdd(dateStr) {
    document.getElementById('formAction').value = 'add';
    document.getElementById('eventId').value = '';
    document.getElementById('deleteEventId').value = '';
    document.getElementById('courseName').value = '';
    document.getElementById('instructorName').value = '';
    document.getElementById('startDate').value = dateStr;
    document.getElementById('endDate').value = dateStr;
    document.getElementById('startTime').value = '09:00';
    document.getElementById('endTime').value = '10:00';
    // modalEl.querySelector('.modal-title').textContent = 'Add Event';
    // modalEl.querySelector('.date-input').value = dateStr;
    // modalEl.querySelector('.event-title').value = '';
    // modalEl.querySelector('.start-time').value = '';
    // modalEl.querySelector('.end-time').value = '';
    // modalEl.classList.add('open');

    const selector = document.getElementById("eventSelector");
    const wrapper = document.getElementById("eventSelectorWrapper");
    if (selector && wrapper){
        selector.innerHTML = '';
        wrapper.style.display = 'none';
    }

    modalEl.style.display= 'flex';
}

//edit event modal
function openModalForEdit(eventsToday) {
    document.getElementById('formAction').value = 'edit';
    modalEl.style.display = 'flex';

    const selector = document.getElementById("eventSelector");
    const wrapper = document.getElementById("eventSelectorWrapper");
    selector.innerHTML = '<option disabled selected>Select Event...</option>';

        eventsToday.forEach(e => {
            const option = document.createElement('option');
            option.value = JSON.stringify(e);
            option.textContent = `${e.title} ${e.start_time} to ${e.end_time}`;
            selector.appendChild(option);
        });

        if (eventsToday.length > 1) {
            wrapper.style.display = 'block';
        }else {
            wrapper.style.display = 'none';
        }

        handleEventSelection(JSON.stringify(eventsToday[0]));
}

//populate form with selected event data
function handleEventSelection(eventJSON){
    const event = JSON.parse(eventJSON);
    document.getElementById('eventId').value = event.id;
    document.getElementById('deleteEventId').value = event.id;

    const [course, instructor] = event.title.split(' - ').map(e => e.trim());
    document.getElementById('courseName').value = course || "";
    document.getElementById('instructorName').value = instructor || "";
    document.getElementById('startDate').value = event.start_date || "";
    document.getElementById('endDate').value = event.end_date || "";
    document.getElementById('startTime').value = event.start_time || "";
    document.getElementById('endTime').value = event.end_time || "";
}

function closeModal() {
    modalEl.style.display = 'none';
}

//navigate month

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar(currentDate);
}

//live clock
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('clock');
    clock.textContent = [now.getHours().toString().padStart(2, '0'),
                          now.getMinutes().toString().padStart(2, '0'),
                          now.getSeconds().toString().padStart(2, '0')].join(':');
}

// initialization
renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);

// Add navigation event listeners
document.querySelectorAll('.nav-btn').forEach((btn, index) => {
    btn.addEventListener('click', () => {
        changeMonth(index === 0 ? -1 : 1);
    });
});

// Add modal close functionality
document.addEventListener('click', (e) => {
    if (e.target === modalEl) {
        closeModal();
    }
});

// Add cancel button functionality
document.querySelector('button[type="button"]').addEventListener('click', closeModal);

// Add event selector change functionality
document.getElementById('eventSelector').addEventListener('change', (e) => {
    if (e.target.value) {
        handleEventSelection(e.target.value);
    }
});
