Unzip these files into a sub-directory on your web server.
NOTE: Please keep in mind that:
  - /config/ and /logos/ directories and /config/country_codes.php should be writable for the web server.
  - allow_url_fopen parameter should be enabled in PHP settings
  - cURL module should be installed and enabled in PHP settings
  - In case Selinux is used, httpd scripts by default are not allowed to make outgoing requests. Please use the following command as root/sudo user to allow outgoing connections: setsebool -P httpd_can_network_connect 1

1) Open the wizard page: http://<yourdomain>/path/to/dir/click2callwiz.php
2) Enter your login and pasword to PortaBilling.
3) The tool is developed to work in two modes ("Mode" option on the form): 'UM Callback' and 'WebRTC'
4.a) In order to configure the application for callback service, please fill in the below form:
* Account ID - specify the account ID that will be charged for both callback legs.
* Password - specify the service password for the above account.
* List of Delays (min) e.g. 1,2,7 - specify a delay list which will be provided to customer, if 'none' - delay is disabled. If you enter a value equal to 0, the option "Now" will appear in the drop-down menu.
* Anti-bot protection - enable/disable anti-bot protection (an alternative to CAPTCHA).
* Header of the popup - specify a header of the popup.
* Popup text - specify text which will be displayed on the popup.
* Max length of the destination number - specify maximum length of the destination number.
* Max number of attempts - specify maximum number of callback attempts for the same user (same IP address or same destination number) during the specified period.
* Period (hr) - specify the period in hours (it will be used for the "max amount of callback attempts" option).
* Max number of strings in the log - specify maximum number of strings in the log file.
* Lifetime of the log records (weeks) - specify lifetime of records in the "calls.log" file. Default is 1 week, i.e. all records older than a week will be removed from the log file.
* Logo - you can define a path to the logo image that can be inserted in the popup. If you want to remove the logo mark "Remove logo" checkbox.
* Move allowed destinations to the right box.
4.b) To use the service in the "WebRTC" mode please follow the instruction: 
* Make sure you are using HTTPS protocol (the solution works only via HTTPS)
* Download web_rtc package at the 'Solutions' tab of 'My Company' page of PortaBilling admin interface, unzip it and move all files (config.js, index.html, load.js) to the parent folder with the solution (http://<yourdomain>/path/to/dir/).
* Fill in the next fields (the fields will appear, when the "WebRTC" mode is selected):
** Number to dial - define a number which will receive the call, usually IVR with queue or a huntgroup
** Domain - PortaBilling administrator domain (e.g. 'mybilling.<yourdomain>.com' or please contact your service provider for more information)
** WSS - custom WSS address (e.g. 'mybilling.<yourdomain>.com/webrtc/' (the field it optional))
** Enable dialpad - tick the check-box if you wish dialpad to appear after a call is connected
5) Click "Submit" button.

Once the above steps are done you can open index page (http://<yourdomain>/path/to/dir/index.php) to test the feature and to see an integration example.