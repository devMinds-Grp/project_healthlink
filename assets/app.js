import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'timeGridWeek', // Vue hebdomadaire avec heures
        locale: 'fr', // Localisation en français
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: '<i class="fas fa-calendar-day"></i> Aujourd\'hui',
            month: '<i class="fas fa-calendar-alt"></i> Mois',
            week: '<i class="fas fa-calendar-week"></i> Semaine',
            day: '<i class="fas fa-calendar-day"></i> Jour'
        },
        slotMinTime: '08:00:00', // Heure de début de la journée
        slotMaxTime: '18:00:00', // Heure de fin de la journée
        events: '/api/events', // Endpoint pour récupérer les événements
        eventColor: '#4a90e2', // Couleur des événements
        eventTextColor: '#ffffff', // Couleur du texte des événements
        eventBorderColor: '#357abd', // Couleur de la bordure des événements
        dayMaxEvents: true, // Affiche un indicateur si trop d'événements
        navLinks: true, // Permet de naviguer vers les vues jour/mois
        nowIndicator: true, // Affiche un indicateur de l'heure actuelle
        editable: true, // Permet de déplacer et redimensionner les événements
        selectable: true, // Permet de sélectionner des créneaux horaires
        selectMirror: true, // Affiche un miroir lors de la sélection
        dateClick: function(info) {
            alert('Date cliquée : ' + info.dateStr);
        },
        eventClick: function(info) {
            alert('Événement cliqué : ' + info.event.title);
        },
        select: function(info) {
            alert('Créneau sélectionné : ' + info.startStr + ' à ' + info.endStr);
        }
    });

    calendar.render();
});