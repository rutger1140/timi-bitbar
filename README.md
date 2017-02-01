# timi-bitbar
A [bitbar](https://github.com/matryer/bitbar) plugin that shows running timers from the Dutch timetracking app [Timi](https://timiapp.com) in the menubar on macOS.

## Usage
* Add the script into BitBar's plug-in folder
* Add your email and [API token](https://timiapp.com/users/edit)
* Give the script excutable permissions using `chmod +x timi.30s.php`
* Reload BitBar by opening it and using `⌘+R`

## Options
* `$ANIMATECLOCK` - show simple clock 'animation' by swaping emoji or one static emoji
* `$TIMEZONE` - set your timezone for PHP (default should suffice)

## Screenshot
![Timi bitbar screenshot](https://raw.githubusercontent.com/lekkerduidelijk/timi-bitbar/master/screenshot.png)

## Todo
* Allow for timers to stop directly from app (API update needed?)
* Allow to start timers directly from app (API update needed?)
* Add weekly hours summary
