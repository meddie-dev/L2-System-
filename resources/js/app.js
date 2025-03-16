import "./bootstrap";
import AOS from 'aos';
import L from 'leaflet';

window.addEventListener("DOMContentLoaded", (event) => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        if (localStorage.getItem("sb|sidebar-toggle") === "true") {
            document.body.classList.toggle("sb-sidenav-toggled");
        }
        sidebarToggle.addEventListener("click", (event) => {
            event.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
            localStorage.setItem(
                "sb|sidebar-toggle",
                document.body.classList.contains("sb-sidenav-toggled")
            );
        });
    }
});

window.addEventListener("DOMContentLoaded", (event) => {
    // Select all tables with the class "datatable"
    const datatables = document.querySelectorAll(".datatable");

    datatables.forEach((table) => {
        new simpleDatatables.DataTable(table);
    });
});

// Auto hide alert
document.addEventListener("DOMContentLoaded", function () {
    var alertElement = document.getElementById("autoHideAlert");
    if (alertElement) {
        // Set timeout to auto-hide after 3 seconds (3000ms)
        setTimeout(function () {
            var alertInstance = new bootstrap.Alert(alertElement);
            alertInstance.close();
        }, 3000); // 3000ms = 3 seconds
    }
});

// Preloader
const preloader = document.querySelector('#preloader');
if (preloader) {
  window.addEventListener('load', () => {
    preloader.remove();
  });
}

// Scroll Top
let scrollTop = document.querySelector('.scroll-top');

function toggleScrollTop() {
  if (scrollTop) {
    window.scrollY > 50 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
  }
}
scrollTop.addEventListener('click', (e) => {
  e.preventDefault();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

window.addEventListener('load', toggleScrollTop);
document.addEventListener('scroll', toggleScrollTop);

// Animation on scroll

AOS.init();
function aosInit() {
    AOS.init({
        duration: 600,
        easing: "ease-in-out",
        once: true,
        mirror: false,
    });
}
window.addEventListener("load", aosInit);

window.addEventListener("load", function (e) {
    if (window.location.hash) {
        if (document.querySelector(window.location.hash)) {
            setTimeout(() => {
                let section = document.querySelector(window.location.hash);
                let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
                window.scrollTo({
                    top: section.offsetTop - parseInt(scrollMarginTop),
                    behavior: "smooth",
                });
            }, 100);
        }
    }
});


import { Calendar } from '@fullcalendar/core';
import multiMonthPlugin from '@fullcalendar/multimonth';

const calendarEl = document.getElementById('calendar');
const calendar = new Calendar(calendarEl, {
  plugins: [multiMonthPlugin],
  initialView: 'multiMonthFourMonth',
  views: {
    multiMonthFourMonth: {
      type: 'multiMonth',
      duration: { months: 12 }
    }
  },
  headerToolbar: {
    left: '',
    center: 'title',
    right: ''
  },
  eventClick: function(info) {
    const eventObj = info.event;
    const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));

    // Populate modal fields with event data
    document.getElementById('reservation-dateTitle').textContent = new Intl.DateTimeFormat('en-US', { day: 'numeric', month: 'long', year: 'numeric' }).format(eventObj.start);
    document.getElementById('reservation-number').textContent = eventObj.title;
    document.getElementById('vehicle-type').textContent = eventObj.extendedProps.vehicle || 'N/A';
    document.getElementById('reservation-date').textContent = eventObj.start.toISOString().split('T')[0];
    document.getElementById('reservation-time').textContent = eventObj.extendedProps.reservationTime || 'N/A';
    document.getElementById('pickup-location').textContent = eventObj.extendedProps.pickupLocation || 'N/A';
    document.getElementById('dropoff-location').textContent = eventObj.extendedProps.dropoffLocation || 'N/A';
    document.getElementById('approval-status').textContent = eventObj.extendedProps.approvalStatus || 'N/A';

    // Show modal
    reservationModal.show();
  }
});

calendar.render();

function loadExistingReservations() {
  existingReservations.forEach(reservation => {
    const eventTitle = `${reservation.reservationNumber}`;
    
    let backgroundColor;
    switch (reservation.approval_status) {
      case 'approved':
        backgroundColor = 'rgba(0, 128, 0, 0.5)';
        break;
      case 'pending':
        backgroundColor = 'rgba(255, 223, 0, 0.5)';
        break;
      case 'cancelled':
        backgroundColor = 'rgba(255, 0, 0, 0.5)';
        break;
      default:
        backgroundColor = '#2196f3';
    }

    calendar.addEvent({
      start: reservation.reservationDate,
      title: eventTitle,
      backgroundColor: backgroundColor,
      borderColor: backgroundColor.replace('0.5', '1'),
      extendedProps: {
        reservationTime: reservation.reservationTime,
        vehicle: reservation.vehicle_type,
        pickupLocation: reservation.pickUpLocation,
        dropoffLocation: reservation.dropOffLocation,
        approvalStatus: reservation.approval_status
      },
    });
  });
}

loadExistingReservations();
