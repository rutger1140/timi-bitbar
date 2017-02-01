#!/usr/bin/php
<?php
# <bitbar.title>TimiApp</bitbar.title>
# <bitbar.version>v1.3</bitbar.version>
# <bitbar.author>Rutger Laurman</bitbar.author>
# <bitbar.author.github>lekkerduidelijk</bitbar.author.github>
# <bitbar.desc>Show running timers from timiapp.com.</bitbar.desc>
# <bitbar.image>https://timiapp.com/apple-touch-icon.png</bitbar.image>
# <bitbar.dependencies>php</bitbar.dependencies>
# <bitbar.abouturl>https://timiapp.com/</bitbar.abouturl>

// =============================================================================
// Configuration
// =============================================================================

$TOKEN = "x";
$EMAIL = "x";
$TIMEZONE = "Europe/Amsterdam";
$ANIMATECLOCK = true;

// Don't edit below this line unless you know what you're doing
// =============================================================================

// Set correct time zone
date_default_timezone_set($TIMEZONE);

// =============================================================================
// Functions
// =============================================================================

function doCurl($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return json_decode($output);
}

// =============================================================================
// API endpoint
// =============================================================================

$apiEndpoint = "https://timiapp.com/api/v1/";
$apiAuth = "email=" . $EMAIL . "&token=" . $TOKEN;

// =============================================================================
// Get entries for today
// =============================================================================

// Format url and get data
$TODAY = date("Y-m-d");
$URL = $apiEndpoint . "time_entries.json?" . $apiAuth . "&date=" . $TODAY;
$outputToday = doCurl($URL);

// Process data
$timeToday = $outputToday->time_entries;

// Define vars
$minutesToday = 0;
$hoursToday = 0;

// Loop through time entries
foreach ($timeToday as $entry) {
  $minutesToday = $minutesToday + $entry->duration;
}

// Format minutes to proper notation
$hoursToday = gmdate("H:i", ($minutesToday));

// =============================================================================
// Get timer
// =============================================================================

// Format url and get data
$URL = $apiEndpoint . "time_entries/timer.json?" . $apiAuth;
$outputTimer = doCurl($URL);

$hasRunningTimer = (count(get_object_vars($outputTimer)) > 0) ? true : false;

if($hasRunningTimer) {

  // Define vars
  $timerDuration = $outputTimer->duration;
  $timerStarted  = $outputTimer->timer_started_at;
  $timerBody     = $outputTimer->body;
  $timerId       = $outputTimer->id;

  $timerDurationMinutes = floor($timerDuration/60);

  // Add time difference from timer_started_at
  $currentTimer = new DateTime($timerStarted, new DateTimeZone($TIMEZONE));
  $minutesTimer = new DateTime("now", new DateTimeZone($TIMEZONE));

  $hoursDiff = $minutesTimer->diff($currentTimer)->h;
  $minutesDiff = $minutesTimer->diff($currentTimer)->i;

  $minutesDiffTotal = ($hoursDiff * 60) + $minutesDiff;

  $currentMinutes = $timerDurationMinutes + (int)$minutesDiffTotal;

  $hours = floor($currentMinutes / 60);
  $minutes = ($currentMinutes % 60);

  $hoursTimer = $hours . ":" . sprintf('%02d', $minutes);

}

// =============================================================================
// Format output
// =============================================================================

// Check if timer is running
if($hasRunningTimer) {

  if($ANIMATECLOCK) {
    echo "Timi: $hoursTimer 🕛 |dropdown=false\n";
    echo "Timi: $hoursTimer 🕑 |dropdown=false\n";
    echo "Timi: $hoursTimer 🕓 |dropdown=false\n";
    echo "Timi: $hoursTimer 🕕 |dropdown=false\n";
    echo "Timi: $hoursTimer 🕗 |dropdown=false\n";
    echo "Timi: $hoursTimer 🕙 |dropdown=false\n";
  } else {
    echo "Timi: $hoursTimer ⏱️ |dropdown=false\n";
  }
  echo "---\n";
  echo "Timer actief: $hoursTimer\n";
  echo "-- $timerBody| length=40\n";

} else {

  echo "Timi: 0:00\n";
  echo "---\n";
  echo "Timer niet actief\n";
}

echo "Vandaag: $hoursToday uur\n";
echo "---\n";
echo "Open Timi| href=https://timiapp.com\n";
echo "---";
