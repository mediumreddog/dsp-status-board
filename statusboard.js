function updateClock() {
	var currentTime = new Date();

	var time = timeString(currentTime);

	$("#clockNum").html(time[0]);
	$("#clockAMPM").html(time[1]);

	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	var currentDateString = days[currentTime.getDay()] + ", " + months[currentTime.getMonth()] + " " + currentTime.getDate();

	$("#clockDate").html(currentDateString);
}

function loadDSPCalendar() {
	$.getJSON("https://www.googleapis.com/calendar/v3/calendars/dspeboard%40gmail.com/events?singleEvents=true&timeMin=" + new Date(Date.now()).toISOString() + "&orderBy=startTime&key=AIzaSyDyq-41653zwrojWvFKnuzM6L9Kh0DJk7s&maxResults=10", function (data) {
		$.each(data["items"], function (key, value) {

			displayEvent("DSPCAL", value);
		});

	});
}

function loadATHCalendar() {
	$.getJSON("https://www.googleapis.com/calendar/v3/calendars/dsp.athletics%40gmail.com/events?singleEvents=true&timeMin=" + new Date(Date.now()).toISOString() + "&orderBy=startTime&key=AIzaSyDyq-41653zwrojWvFKnuzM6L9Kh0DJk7s&maxResults=10", function (data) {

		if (data["items"].length == 0) {
			$("#ATHCAL").append('<p class="noItems">No Upcoming Events</p>');
		} else {
			$.each(data["items"], function (key, value) {
				displayEvent("ATHCAL", value);
			});
		}
	});
}

function updateCalendars() {
	$("#ATHCAL").empty();
	loadATHCalendar()

	delete displayEvent.month;

	$("#DSPCAL").empty();
	loadDSPCalendar()
}

function timeString(currentTime) {
	var ret = {};

	var currentHours = currentTime.getHours();
	var currentMinutes = currentTime.getMinutes();

	ret[1] = (currentHours < 12) ? "AM" : "PM";

	currentMinutes = ((currentMinutes < 10) ? "0" : "") + currentMinutes;

	currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
	currentHours = (currentHours == 0) ? 12 : currentHours;

	ret[0] = currentHours + ":" + currentMinutes;

	return ret;
}

function displayEvent(calendar, values) {

	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	if ("dateTime" in values["start"]) {
		var date = new Date(values["start"]["dateTime"]);
		var day = date.getDate();
		var time = timeString(date);
		time = time[0] + " " + time[1];

	} else {
		var date = new Date(values["start"]["date"]);
		var day = date.getDate();
		var time = "ALL DAY";

	}

	if (!displayEvent.month || displayEvent.month != date.getMonth()) {
		displayEvent.month = date.getMonth();
		$("#" + calendar).append('<h5 class="calendarMonth">' + months[displayEvent.month] + '</h5>');
	}


	$("#" + calendar).append('\
  <li>\
    <span class="calendarDate">' + day + '</span>\
    <span class="calendarEvent">' + values["summary"] + '</span>\
    <a class="calendarTime">' + time + '</a>\
 </li>');

}



function loadChairAnnouncements() {
	$.ajax({
		url: 'https://sheets.googleapis.com/v4/spreadsheets/1ChIxEqBwPGLrEhcwdVmv6pv4fVwtu_xJ2gkw1aPnm-M/values/A2:C22?key=AIzaSyDyq-41653zwrojWvFKnuzM6L9Kh0DJk7s',
		dataType: 'jsonp',
		success: function (data) {
      do {
        loadChairAnnouncements.curMessage++;
        if(loadChairAnnouncements.curMessage >= data["values"].length) {
          loadChairAnnouncements.curMessage = 0;
        }
      } while (data["values"][loadChairAnnouncements.curMessage].length < 3);

      var i = loadChairAnnouncements.curMessage;

      $("#announcement").fadeOut(500, function() {
        $("#announcementName").html(data["values"][i][1]);
        $("#announcementPosition").html(data["values"][i][0]);
        $("#announcementMessage").html(data["values"][i][2]);
      });

      $("#announcement").fadeIn(500);
		}
	});
}


$(document).ready(function () {
	updateCalendars();
	setInterval('updateCalendars()', 1000 * 60 * 60);

  loadChairAnnouncements.curMessage = 0;
	loadChairAnnouncements();
  setInterval('loadChairAnnouncements()', 10000);



	setInterval('updateClock()', 2000);
});
