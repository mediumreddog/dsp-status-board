function updateClock() {
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );

    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;

    $("#clockAMPM").html(( currentHours < 12 ) ? "AM" : "PM");

    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;

    var currentTimeString = currentHours + ":" + currentMinutes;

    $("#clockNum").html(currentTimeString);

    var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    var currentDateString = days[currentTime.getDay()] + ", " + months[currentTime.getMonth()] + " " + currentTime.getDate();

    $("#clockDate").html(currentDateString);
}

function loadCalendar() {
  $.getJSON("https://www.googleapis.com/calendar/v3/calendars/dspeboard%40gmail.com/events?singleEvents=true&timeMin="+new Date(Date.now()).toISOString()+"&orderBy=startTime&key=AIzaSyDyq-41653zwrojWvFKnuzM6L9Kh0DJk7s&maxResults=10", function(data) {
    $.each(data["items"], function(key, value) {
      console.log(value["summary"]);
    });

  });
}

function updateCalendar() {

}


$(document).ready(function()
{
  loadCalendar();

   setInterval('updateClock()', 2000);
});
