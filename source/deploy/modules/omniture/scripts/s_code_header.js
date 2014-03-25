/* SiteCatalyst code version: H.17.
Copyright 1997-2008 Omniture, Inc. More info available at
http://www.omniture.com */

var s_account = Drupal.settings.omniture.s_account;
var s = s_gi(s_account);
var domain = window.location.hostname;
/************************** CONFIG SECTION **************************/
/* You may add or alter any code config here. */
s.charSet="ISO-8859-1"
/* Conversion Config */
s.currencyCode="USD"
/* Link Tracking Config */
s.trackDownloadLinks=true
s.trackExternalLinks=true
s.trackInlineStats=true
s.linkDownloadFileTypes="exe,zip,wav,mp3,mov,mpg,avi,wmv,doc,pdf,xls"
s.linkInternalFilters="javascript:,"+domain
s.linkLeaveQueryString=false
s.linkTrackVars="None"
s.linkTrackEvents="None"
/* WARNING: Changing any of the below variables will cause drastic
changes to how your visitor data is collected. Changes should only be
made when instructed to do so by your account manager.*/
s.dc="112"

/* Page Name Plugin Config */
s.siteID = Drupal.settings.omniture.siteID;    // leftmost value in pagename
s.queryVarsList=""         // query parameters to keep
s.pathExcludeDelim=";"     // portion of the path to exclude
s.pathConcatDelim="/"      // page name component separator
s.pathExcludeList=""       // elements to exclude from the path

jQuery(function() {
  

// DO NOT CLOSE IT
// all custom dynamic code will be placed here
// THIS document.ready IS CLOSED IN s_code_footer.js FILE

