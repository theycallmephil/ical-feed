<?php

header("Content-type: text/calendar; charset=utf-8");
header("Content-Disposition: inline; filename=ical.ics");

$url = "https://symbion.dk/wp-content/plugins/event-json/json_feed.php";
$decode = file_get_contents($url);
$data = json_decode($decode, true);

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd\THis');
 
$output = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Symbion//Event Calendar//EN\n";
 
// loop over events
foreach ($data as $event):
    // go through events from a specific location
    // if($event["location_id"] == "b25") 
$output .=
"BEGIN:VTIMEZONE
TZID:Europe/Copenhagen
X-LIC-LOCATION:Europe/Copenhagen 
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100 
TZOFFSETTO:+0200 
TZNAME:CEST 
DTSTART:19700329T020000 
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200 
TZOFFSETTO:+0100 
TZNAME:CET 
DTSTART:19701025T030000 
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE
BEGIN:VEVENT
SUMMARY:" . $event["name"] . "
UID:" . $event["id"] . "
STATUS:" . strtoupper($event["status"]) . "
DTSTART:" . date(DATE_ICAL, strtotime($event["date"])) . "
DTEND:" . date(DATE_ICAL, strtotime($event["date_end"])) . "
DTSTAMP:" . date(DATE_ICAL, strtotime($event["publish_date"])) . "
LAST-MODIFIED:" . date(DATE_ICAL, strtotime($event["modified_date"])) . "
URL:" . $event["permalink_url"] . "
LOCATION:" . $event["room"] . "\, " . $event["location_name"] . "\, " . $event["address_display"] . "
END:VEVENT\n";
endforeach;
 
// close calendar
$output .= "END:VCALENDAR";
 
echo $output;

// DURATION:" . $event["duration"] . "
// DESCRIPTION:" . $event["exerpt"] . "

?>
