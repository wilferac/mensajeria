<?php

// Global user functions
// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression) {
	$today = getdate();
	$lastmonth = mktime(0, 0, 0, $today['mon']-1, 1, $today['year']);
	$sVal = date("Y|m", $lastmonth);
	$sWrk = $FldExpression . " BETWEEN " .
		ewrpt_QuotedValue(ewrpt_DateVal("month", $sVal, 1), EWRPT_DATATYPE_DATE) .
		" AND " .
		ewrpt_QuotedValue(ewrpt_DateVal("month", $sVal, 2), EWRPT_DATATYPE_DATE);
	return $sWrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression) {
	return $FldExpression . " LIKE 'A%'";
}

// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
?>
