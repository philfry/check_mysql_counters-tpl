<?php
/*
check_mysql_counters.php version 1.5.1

Licensed under the BSD simplified 2 clause license

Copyright (c) 2013, WebPT, LLC.
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

check_mysql_counters.php - a pnp4nagios template to display performance information captured by the check_mysql_counters nagios plugin inspired by the Percona MySQL cacti templates
Written by Jason Holtzapple - jason@bitflip.net
Modified by Philippe Kueck <projects at unixadm dot org>

*/


$myds = array();
for ($i = 1; $i <= count($DS); $i++) {$myds[$NAME[$i]] = $i;}

$num = 0;
$ds_name[$num] = 'Database Activity';
$opt[$num] = "--title  \"$hostname - Database Activity\" --vertical-label \"avg statements/sec\" --units-exponent 0 --lower-limit 0";
$def[$num] = "";
$col = 0;
foreach (array(
	"COM_SELECT" => "Select",
	"COM_INSERT" => "Insert",
	"COM_UPDATE" => "Update",
	"COM_CALL_PROCEDURE" => "Call",
	"COM_DELETE" => "Delete",
	"COM_REPLACE" => "Replace"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d, 25));
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Connections';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"MAX_CONNECTIONS"      => array("Max Connections",      1, "MAX"),
	"MAX_USED_CONNECTIONS" => array("Max Connections Used", 1, "MAX"),
	"ABORTED_CLIENTS"      => array("Aborted Clients",      0, "AVERAGE"),
	"ABORTED_CONNECTS"     => array("Aborted Connections",  0, "AVERAGE"),
	"THREADS_CONNECTED"    => array("Threads Connected",    0, "AVERAGE"),
	"CONNECTIONS"          => array("New Connections",      0, "AVERAGE")
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], $d[2]);
	if ($d[1] == 0) $def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d[0], 25));
	else $def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d[0], 25));
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Command Counters';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"QUESTIONS" => "Questions",
	"COM_SELECT" => "Select",
	"COM_DELETE" => "Delete",
	"COM_INSERT" => "Insert",
	"COM_UPDATE" => "Update",
	"COM_REPLACE" => "Replace",
	"COM_LOAD" => "Load",
	"COM_DELETE_MULTI" => "Delete Multi",
	"COM_INSERT_SELECT" => "Insert Select",
	"COM_UPDATE_MULTI" => "Update Multi",
	"COM_REPLACE_SELECT" => "Replace Select"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25), true);
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Files and Tables';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"TABLE_OPEN_CACHE" => array("Table Cache",   1, "AVERAGE"),
	"OPEN_TABLES"      => array("Open Tables",   0, "AVERAGE"),
	"OPENED_FILES"     => array("Opened Files",  0, "AVERAGE"),
	"OPENED_TABLES"    => array("Opened Tables", 0, "AVERAGE"),
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], $d[2]);
	if ($d[1] == 0) $def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d[0], 25));
	else $def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d[0], 25));
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'MySQL Handlers';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"HANDLER_WRITE"         => "Handler Write",
	"HANDLER_UPDATE"        => "Handler Update",
	"HANDLER_DELETE"        => "Handler Delete",
	"HANDLER_READ_FIRST"    => "Handler Read First",
	"HANDLER_READ_KEY"      => "Handler Read Key",
	"HANDLER_READ_NEXT"     => "Handler Read Next",
	"HANDLER_READ_PREV"     => "Handler Read Prev",
	"HANDLER_READ_RND"      => "Handler Read Rnd",
	"HANDLER_READ_RND_NEXT" => "Handler Read Rnd Next"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25), true);
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'MySQL Query Cache';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"QCACHE_QUERIES_IN_CACHE" => "Queries In Cache",
	"QCACHE_HITS"             => "Cache Hits",
	"QCACHE_INSERTS"          => "Cache Inserts",
	"QCACHE_NOT_CACHED"       => "Not Cached",
	"QCACHE_LOWMEM_PRUNES"    => "Low-Memory Prunes",
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25));
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Prepared Statements';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"PREPARED_STMT_COUNT" => "Prepared Statement Count"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25), true);
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Select Types';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"SELECT_FULL_JOIN"       => "Full Join",
	"SELECT_FULL_RANGE_JOIN" => "Full Range Join",
	"SELECT_RANGE"           => "Range",
	"SELECT_RANGE_CHECK"     => "Range Check",
	"SELECT_SCAN"            => "Scan"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25), true);
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Sorts';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"SORT_ROWS"         => array("Rows Sorted",  0, "AVERAGE"),
	"SORT_RANGE"        => array("Range",        1, "AVERAGE"),
	"SORT_MERGE_PASSES" => array("Merge Passes", 1, "AVERAGE"),
	"SORT_SCAN"         => array("Scan",         1, "AVERAGE")
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], $d[2]);
	if ($d[1] == 0) $def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d[0], 25));
	else $def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d[0], 25));
	$def[$num] .= rrd::gprint($k, array('LAST', 'AVERAGE', 'MAX'), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Table Locks';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"TABLE_LOCKS_IMMEDIATE" => "Table Locks Immediate",
	"TABLE_LOCKS_WAITED"    => "Table Locks Waited"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d, 25), true);
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

$num++;
$ds_name[$num] = 'Temporary Objects';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"CREATED_TMP_TABLES"      => array("Temp Tables",      1, "AVERAGE"),
	"CREATED_TMP_DISK_TABLES" => array("Temp Disk Tables", 0, "AVERAGE"),
	"CREATED_TMP_FILES"       => array("Temp Files",       0, "AVERAGE"),
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], $d[2]);
	if ($d[1] == 0) $def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d[0], 25));
	else $def[$num] .= rrd::area($k, rrd::color($col++), rrd::cut($d[0], 25));
	$def[$num] .= rrd::gprint($k, array('LAST', 'AVERAGE', 'MAX'), '%5.0lf');
}


$num++;
$ds_name[$num] = 'Transaction Handler';
$opt[$num] = "--title \"$hostname - ".$ds_name[$num]."\"";
$def[$num] = "";
$col = 0;
foreach (array(
	"HANDLER_COMMIT"             => "Handler Commit",
	"HANDLER_ROLLBACK"           => "Handler Rolback",
	"HANDLER_SAVEPOINT"          => "Handler Savepoint",
	"HANDLER_SAVEPOINT_ROLLBACK" => "Handler Savepoint Rollback"
) as $k => $d) {
	if (!isset($myds[$k])) continue;
	$def[$num] .= rrd::def($k, $RRDFILE[$myds[$k]], $DS[$myds[$k]], 'AVERAGE');
	$def[$num] .= rrd::line1($k, rrd::color($col++), rrd::cut($d, 25));
	$def[$num] .= rrd::gprint($k, array("LAST", "AVERAGE", "MAX"), '%5.0lf');
}

?>
