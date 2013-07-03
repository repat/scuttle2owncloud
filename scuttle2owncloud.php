<?php
/**
* scuttle2owncloud.php
* released under MIT License(see extra file)
* version 0.9.1
* (c) by repat, <repat[at]repat[dot]de>, http://repat.de
* June 2013
*/

//-----------------------//
// CONFIGURE THIS FIRST!
include 'scuttle2owncloud_config.php';
//-----------------------//

// Connect the the hosts
$scLink = mysql_connect($scuttleHost, $scuttleUser, $scuttlePassword);
if (!$scLink) {
    die('Scuttle connection failed: ' . mysql_error());
}
echo 'Scuttle connected successfully<br /><br />';

$ocLink = mysql_connect($owncloudHost, $owncloudUser, $owncloudPassword);
if (!$ocLink) {
    die('Owncloud connection failed: ' . mysql_error());
}
echo 'Owncloud connected successfully<br /><br />';


// Select the databases
$scDbSelected = mysql_select_db($scuttleDb, $scLink);
if (!$scDbSelected) {
    die ('Cannot use ' . $scuttleDb . ": " . mysql_error());
}

$ocDbSelected = mysql_select_db($owncloudDb, $ocLink);
if (!$ocDbSelected) {
    die ('Cannot use ' . $owncloudDb . ": " . mysql_error());
}

// Get the scuttle bookmarks
$scResultBm = mysql_query('SELECT bId, bTitle, bAddress, bDescription FROM ' . $scuttleBmTable, $scLink);
if (!$scResultBm) {
    die('Illegal scResultBm-query: ' . mysql_error());
}

// --- actual conversion ---
$entries = 0;
$tags = 0;
// get bookmark array
while ($bmRow = mysql_fetch_row($scResultBm)) {
	
	// insert bookmark into owncloud
	$ocResultBm = mysql_query('INSERT INTO ' . $owncloudBmTable . " (title, url, description, user_id, added, lastmodified) VALUES ('" .  mysql_real_escape_string($bmRow[$TITLE]) . "', '" .  mysql_real_escape_string($bmRow[$URL]) . "', '" .  mysql_real_escape_string($bmRow[$DESCRIPTION]) . "', '" . mysql_real_escape_string($owncloudUsername) . "', '" . time() . "', '" . time() . "')", $ocLink);
	if (!$ocResultBm) {
		die('Illegal ocResultBm-query: ' . mysql_error());
	}
	
	// get the matching tags where bId = bId
	$scResultT = mysql_query('SELECT bId, tag FROM ' . $scuttleTagsTable . " WHERE (bId = '" . $bmRow[$ID] . "')", $scLink);
	if (!$scResultT) {
    	die('Illegal scResultT-query: ' . mysql_error());
	}
	// get tag array and insert all the tags for current bookmark ID
	while ($tRow = mysql_fetch_row($scResultT)) {
		$ocResultT = mysql_query('INSERT INTO ' . $owncloudTagsTable . " (bookmark_id, tag) VALUES ( '" .  $bmRow[$ID] . "', '" .  mysql_real_escape_string($tRow[$TAG]) . "')", $ocLink);
		if (!$ocResultT) {
    		die('Illegal ocResultT-query: ' . mysql_error());
    		
		}
		$tags++;
	}
	$entries++;	
}

// fix a "bug" that mysql_real_escape_string() causes
$fixEscStrBmResult = mysql_query('UPDATE ' . $owncloudBmTable . " SET description = '' WHERE description = 'system:unfiled'", $ocLink);
if (!$fixEscStrBmResult) {
   	die('Illegal fixEscStrResult-query: ' . mysql_error());
}

$fixEscStrTResult = mysql_query('UPDATE ' . $owncloudTagsTable . " SET tag = '' WHERE tag = 'system:unfiled'", $ocLink);
if (!$fixEscStrTResult) {
   	die('Illegal fixEscStrResult-query: ' . mysql_error());
}
echo "<p>Fixed system:unfiled entries.</p>";
	
// Close both MySQL connections
mysql_close($scLink);
mysql_close($ocLink);

echo "<p>Everything worked fine: Converted " . $entries .  " entries with " . $tags . " tags.</p>";
?>
