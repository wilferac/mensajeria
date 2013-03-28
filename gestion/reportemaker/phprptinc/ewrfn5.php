<?php

// Functions for PHP Report Maker 5
// (C) 2007-2011 e.World Technology Limited

if (!function_exists("G")) {

	function &G($name) {
		return $GLOBALS[$name];
	}
}

// Get current page object
function &CurrentPage() {
	return $GLOBALS["Page"];
}

// Get current main table object
function &CurrentTable() {
	return $GLOBALS["Table"];
}

/**
 * Langauge class for reports
 */

class crLanguage {
	var $LanguageId;
	var $Phrases = NULL;

	// Constructor
	function crLanguage() {
		global $gsLanguage;
		$this->LoadFileList(); // Set up file list
		if (@$_GET["language"] <> "") { // Set up language id
			$this->LanguageId = $_GET["language"];
			$_SESSION[EWRPT_SESSION_LANGUAGE_ID] = $this->LanguageId;
		} elseif (@$_SESSION[EWRPT_SESSION_LANGUAGE_ID] <> "") {
			$this->LanguageId = $_SESSION[EWRPT_SESSION_LANGUAGE_ID];
		} else {
			$this->LanguageId = EWRPT_LANGUAGE_DEFAULT_ID;
		}
		$gsLanguage = $this->LanguageId;
		$this->Load($this->LanguageId);
	}

	// Load language file list
	function LoadFileList() {
		global $EWRPT_LANGUAGE_FILE;
		if (is_array($EWRPT_LANGUAGE_FILE)) {
			$cnt = count($EWRPT_LANGUAGE_FILE);
			for ($i = 0; $i < $cnt; $i++)
				$EWRPT_LANGUAGE_FILE[$i][1] = $this->LoadFileDesc(EWRPT_LANGUAGE_FOLDER . $EWRPT_LANGUAGE_FILE[$i][2]);
		}
	}

	// Load language file description
	function LoadFileDesc($File) {
		if (EWRPT_USE_DOM_XML) {
			$this->Phrases = new crXMLDocument();
			if ($this->Phrases->Load($File))
				return $this->GetNodeAtt($this->Phrases->DocumentElement(), "desc");
		} else {
			$ar = ewrpt_Xml2Array(substr(file_get_contents($File), 0, 512)); // Just read the first part
			return (is_array($ar)) ? @$ar['ew-language']['attr']['desc'] : "";
		}
	}

	// Load language file
	function Load($id) {
		$sFileName = $this->GetFileName($id);
		if ($sFileName == "")
			$sFileName = $this->GetFileName(EWRPT_LANGUAGE_DEFAULT_ID);
		if ($sFileName == "")
			return;
		if (EWRPT_USE_DOM_XML) {
			$this->Phrases = new crXMLDocument();
			$this->Phrases->Load($sFileName);
		} else {
			if (is_array(@$_SESSION[EWRPT_PROJECT_VAR . "_" . $sFileName])) {
				$this->Phrases = $_SESSION[EWRPT_PROJECT_VAR . "_" . $sFileName];
			} else {
				$this->Phrases = ewrpt_Xml2Array(file_get_contents($sFileName));
			}
		}
	}

	// Get language file name
	function GetFileName($Id) {
		global $EWRPT_LANGUAGE_FILE;
		if (is_array($EWRPT_LANGUAGE_FILE)) {
			$cnt = count($EWRPT_LANGUAGE_FILE);
			for ($i = 0; $i < $cnt; $i++)
				if ($EWRPT_LANGUAGE_FILE[$i][0] == $Id) {
					return EWRPT_LANGUAGE_FOLDER . $EWRPT_LANGUAGE_FILE[$i][2];
			}
		}
		return "";
	}

	// Get node attribute
	function GetNodeAtt($Nodes, $Att) {
		$value = ($Nodes) ? $this->Phrases->GetAttribute($Nodes, $Att) : "";

		//return ewrpt_ConvertFromUtf8($value);
		return $value;
	}

	// Get phrase
	function Phrase($Name) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Name) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Name)]['attr']['value']);
		}
	}

	// Get project phrase
	function ProjectPhrase($Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set project phrase
	function setProjectPhrase($Id, $Value) {
		if (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get menu phrase
	function MenuPhrase($MenuId, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/menu[@id='" . $MenuId . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['menu'][$MenuId]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set menu phrase
	function setMenuPhrase($MenuId, $Id, $Value) {
		if (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['menu'][$MenuId]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get table phrase
	function TablePhrase($TblVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set table phrase
	function setTablePhrase($TblVar, $Id, $Value) {
		if (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get chart phrase
	function ChartPhrase($TblVar, $ChtVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/chart[@id='" . strtolower($ChtVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['chart'][strtolower($ChtVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set chart phrase
	function setChartPhrase($TblVar, $FldVar, $Id, $Value) {
		if (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['chart'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get field phrase
	function FieldPhrase($TblVar, $FldVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/field[@id='" . strtolower($FldVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewrpt_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['field'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set field phrase
	function setFieldPhrase($TblVar, $FldVar, $Id, $Value) {
	if (is_array($this->Phrases)) {
		$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['field'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Output XML as JSON
	function XmlToJSON($XPath) {
		$NodeList = $this->Phrases->SelectNodes($XPath);
		$Str = "{";
		foreach ($NodeList as $Node) {
			$Id = $this->GetNodeAtt($Node, "id");
			$Value = $this->GetNodeAtt($Node, "value");
			$Str .= "\"" . ewrpt_JsEncode2($Id) . "\":\"" . ewrpt_JsEncode2($Value) . "\",";
		}
		if (substr($Str, -1) == ",") $Str = substr($Str, 0, strlen($Str)-1);
		$Str .= "}";
		return $Str;
	}

	// Output array as JSON
	function ArrayToJSON($client) {
		$ar = @$this->Phrases['ew-language']['global']['phrase'];
		$Str = "{";
		if (is_array($ar)) {
			foreach ($ar as $id => $node) {
				$is_client = @$node['attr']['client'] == '1';
				$value = ewrpt_ConvertFromUtf8(@$node['attr']['value']);
				if (!$client || ($client && $is_client))
					$Str .= "\"" . ewrpt_JsEncode2($id) . "\":\"" . ewrpt_JsEncode2($value) . "\",";
			}
		}
		if (substr($Str, -1) == ",") $Str = substr($Str, 0, strlen($Str)-1);
		$Str .= "}";
		return $Str;
	}

	// Output all phrases as JSON
	function AllToJSON() {
		if (is_object($this->Phrases)) {
			return "var ewLanguage = new ewrpt_Language(" . $this->XmlToJSON("//global/phrase") . ");";
		} elseif (is_array($this->Phrases)) {
			return "var ewLanguage = new ewrpt_Language(" . $this->ArrayToJSON(FALSE) . ");";
		}
	}

	// Output client phrases as JSON
	function ToJSON() {
		if (is_object($this->Phrases)) {
			return "var ewLanguage = new ewrpt_Language(" . $this->XmlToJSON("//global/phrase[@client='1']") . ");";
		} elseif (is_array($this->Phrases)) {
			return "var ewLanguage = new ewrpt_Language(" . $this->ArrayToJSON(TRUE) . ");";
		}
	}
}

// Convert XML to array
function ewrpt_Xml2Array($contents) {
	if (!$contents) return array(); 
	if (!function_exists('xml_parser_create')) return FALSE;
	$get_attributes = 1; // Always get attributes. DO NOT CHANGE!

	// Get the XML Parser of PHP
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); // Always return in utf-8
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);
	if (!$xml_values) return;
	$xml_array = array();
	$parents = array();
	$opened_tags = array();
	$arr = array();
	$current = &$xml_array;
	$repeated_tag_index = array(); // Multiple tags with same name will be turned into an array
	foreach ($xml_values as $data) {
		unset($attributes, $value); // Remove existing values

		// Extract these variables into the foreach scope
		// tag(string), type(string), level(int), attributes(array)

		extract($data);
		$result = array();
		if (isset($value))
			$result['value'] = $value; // Put the value in a assoc array

		// Set the attributes
		if (isset($attributes) and $get_attributes) {
			foreach ($attributes as $attr => $val)
				$result['attr'][$attr] = $val; // Set all the attributes in a array called 'attr'
		} 

		// See tag status and do the needed
		if ($type == "open") { // The starting of the tag '<tag>'
			$parent[$level-1] = &$current;
			if (!is_array($current) || !in_array($tag, array_keys($current))) { // Insert New tag
				if ($tag <> 'ew-language' && @$result['attr']['id'] <> '') { // 
					$last_item_index = $result['attr']['id'];
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					$current = &$current[$tag][$last_item_index];
				} else {
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 0;
					$current = &$current[$tag];
				}
			} else { // Another element with the same tag name
				if ($repeated_tag_index[$tag.'_'.$level] > 0) { // If there is a 0th element it is already an array
					if (@$result['attr']['id'] <> '') {
						$last_item_index = $result['attr']['id'];
					} else {
						$last_item_index = $repeated_tag_index[$tag.'_'.$level];
					}
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level]++;
				} else { // Make the value an array if multiple tags with the same name appear together
					$temp = $current[$tag];
					$current[$tag] = array();
					if (@$temp['attr']['id'] <> '') {
						$current[$tag][$temp['attr']['id']] = $temp;
					} else {
						$current[$tag][] = $temp;
					}
					if (@$result['attr']['id'] <> '') {
						$last_item_index = $result['attr']['id'];
					} else {
						$last_item_index = 1;
					}
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 2;
				} 
				$current = &$current[$tag][$last_item_index];
			}
		} elseif ($type == "complete") { // Tags that ends in one line '<tag>'
			if (!isset($current[$tag])) { // New key
				$current[$tag] = array(); // Always use array for "complete" type
				if (@$result['attr']['id'] <> '') {
					$current[$tag][$result['attr']['id']] = $result;
				} else {
					$current[$tag][] = $result;
				}
				$repeated_tag_index[$tag.'_'.$level] = 1;
			} else { // Existing key
				if (@$result['attr']['id'] <> '') {
			  	$current[$tag][$result['attr']['id']] = $result;
				} else {
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
				}
			  $repeated_tag_index[$tag.'_'.$level]++;
			}
		} elseif ($type == 'close') { // End of tag '</tag>' 
			$current = &$parent[$level-1];
		}
	}
	return($xml_array);
}

/**
 * XML document class
 */

class crXMLDocument {
	var $Encoding = "utf-8";
	var $RootTagName;
	var $RowTagName;
	var $XmlDoc = FALSE;
	var $XmlTbl;
	var $XmlRow;
	var $NullValue = 'NULL';

	function crXMLDocument($encoding = "") {
		if ($encoding <> "")
			$this->Encoding = $encoding;
		if ($this->Encoding <> "") {
			$this->XmlDoc = new DOMDocument("1.0", strval($this->Encoding));
		} else {
			$this->XmlDoc = new DOMDocument("1.0");
		}
	}

	function Load($filename) {
		$filepath = realpath($filename);
		return $this->XmlDoc->load($filepath);
	}

	function &DocumentElement() {
		$de = $this->XmlDoc->documentElement;
		return $de;
	}

	function GetAttribute($element, $name) {
		return ($element) ? ewrpt_ConvertFromUtf8($element->getAttribute($name)) : "";
	}

	function SelectSingleNode($query) {
		$elements = $this->SelectNodes($query);
		return ($elements->length > 0) ? $elements->item(0) : NULL;
	}

	function SelectNodes($query) {
		$xpath = new DOMXPath($this->XmlDoc);
		return $xpath->query($query);
	}

	function AddRoot($roottagname = 'table') {
		$this->RootTagName = $roottagname;
		$this->XmlTbl = $this->XmlDoc->createElement($this->RootTagName);
		$this->XmlDoc->appendChild($this->XmlTbl);
	}

	function AddRow($rowtagname = 'row') {
		$this->RowTagName = $rowtagname;
		$this->XmlRow = $this->XmlDoc->createElement($this->RowTagName);
		if ($this->XmlTbl)
			$this->XmlTbl->appendChild($this->XmlRow);
	}

	function AddField($name, $value) {
		if (is_null($value)) $value = $this->NullValue;
		$value = ewrpt_ConvertToUtf8($value); // Convert to UTF-8
		$xmlfld = $this->XmlDoc->createElement($name);
		$this->XmlRow->appendChild($xmlfld);
		$xmlfld->appendChild($this->XmlDoc->createTextNode($value));
	}

	function XML() {
		return $this->XmlDoc->saveXML();
	}
}

// Select nodes from XML document
function &ewrpt_SelectNodes(&$xmldoc, $query) {
	if ($xmldoc) {
		$xpath = new DOMXPath($xmldoc);
		return $xpath->query($query);
	}
	return NULL;
}

// Select single node from XML document
function &ewrpt_SelectSingleNode(&$xmldoc, $query) {
	$elements = ewrpt_SelectNodes($xmldoc, $query);
	return ($elements && $elements->length > 0) ? $elements->item(0) : NULL;
}

// Debug timer
class crTimer {
	var $StartTime;
	var $EndTime;

	function crTimer($start = TRUE) {
		if ($start)
			$this->Start();
	}

	function GetTime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	// Get script start time
	function Start() {
		if (EWRPT_DEBUG_ENABLED)
			$this->StartTime = $this->GetTime();
	}

	// display elapsed time (in seconds)
	function Stop() {
		if (EWRPT_DEBUG_ENABLED)
			$this->EndTime = $this->GetTime();
		if (isset($this->EndTime) && isset($this->StartTime) &&
			$this->EndTime > $this->StartTime)
			echo '<p>Page processing time: ' . ($this->EndTime - $this->StartTime) . ' seconds</p>';
	}
}

/**
 * Field class
 */

class crField {
	var $TblName; // Table name
	var $TblVar; // Table variable name
	var $FldName; // Field name
	var $FldVar; // Field variable name
	var $FldExpression; // Field expression (used in SQL)
	var $FldDefaultErrMsg; // Default error message
	var $FldType; // Field type
	var $FldDataType; // PHPMaker Field type
	var $FldDateTimeFormat; // Date time format
	var $Count; // Count
	var $SumValue; // Sum
	var $AvgValue; // Average
	var $MinValue; // Minimum
	var $MaxValue; // MaxValue
	var $SumViewValue; // Sum
	var $AvgViewValue; // Average
	var $MinViewValue; // Minimum
	var $MaxViewValue; // MaxValue
	var $OldValue; // Old Value
	var $CurrentValue; // Current value
	var $ViewValue; // View value
	var $HrefValue; // Href value
	var $FormValue; // Form value
	var $QueryStringValue; // QueryString value
	var $DbValue; // Database value
	var $ImageWidth = 0; // Image width
	var $ImageHeight = 0; // Image height
	var $ImageResize = FALSE; // Image resize
	var $Sortable = TRUE; // Sortable
	var $GroupingFieldId = 0; // Grouping field id
	var $UploadPath = EWRPT_UPLOAD_DEST_PATH; // Upload path
	var $TruncateMemoRemoveHtml = FALSE; // Remove HTML from memo field
	var $CellAttrs = array(); // Cell custom attributes
	var $ViewAttrs = array(); // View custom attributes
	var $LinkAttrs = array(); // Href custom attributes
	var $FldGroupByType; // Group By Type
	var $FldGroupInt; // Group Interval
	var $FldGroupSql; // Group SQL
	var $GroupDbValues; // Group DB Values
	var $GroupViewValue; // Group View Value
	var $GroupSummaryOldValue; // Group Summary Old Value
	var $GroupSummaryValue; // Group Summary Value
	var $GroupSummaryViewValue; // Group Summary View Value
	var $SqlSelect; // Field SELECT
	var $SqlGroupBy; // Field GROUP BY
	var $SqlOrderBy; // Field ORDER BY
	var $ValueList; // Value List
	var $SelectionList; // Selection List
	var $DefaultSelectionList; // Default Selection List
	var $AdvancedFilters; // Advanced Filters
	var $RangeFrom; // Range From
	var $RangeTo; // Range To
	var $DropDownList; // Dropdown List
	var $DropDownValue; // Dropdown Value
	var $DefaultDropDownValue; // Default Dropdown Value
	var $DateFilter; // Date Filter
	var $SearchValue; // Search Value 1
	var $SearchValue2; // Search Value 2
	var $SearchOperator; // Search Operator 1
	var $SearchOperator2; // Search Operator 2
	var $SearchCondition; // Search Condition
	var $DefaultSearchValue; // Default Search Value 1
	var $DefaultSearchValue2; // Default Search Value 2
	var $DefaultSearchOperator; // Default Search Operator 1
	var $DefaultSearchOperator2; // Default Search Operator 2
	var $DefaultSearchCondition; // Default Search Condition

	// Constructor
	function crField($tblvar, $tblname, $fldvar, $fldname, $fldexpression, $fldtype, $flddatatype, $flddtfmt) {
		$this->TblVar = $tblvar;
		$this->TblName = $tblname;
		$this->FldVar = $fldvar;
		$this->FldName = $fldname;
		$this->FldExpression = $fldexpression;
		$this->FldType = $fldtype;
		$this->FldDataType = $flddatatype;
		$this->FldDateTimeFormat = $flddtfmt;
	}

	// Field caption
	function FldCaption() {
		global $ReportLanguage;
		return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldCaption");
	}

	// Field title
	function FldTitle() {
		global $ReportLanguage;
		return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldTitle");
	}

	// Field image alt
	function FldAlt() {
		global $ReportLanguage;
		return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldAlt");
	}

	// Field error message
	function FldErrMsg() {
		global $ReportLanguage;
		$err = $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldErrMsg");
		if ($err == "") $err = $this->FldDefaultErrMsg . " - " . $this->FldCaption();
		return $err;
	}

	// Reset CSS styles for field object
	function ResetCSS() {
		$this->CellAttrs["style"] = "";
		$this->CellAttrs["class"] = "";
		$this->ViewAttrs["style"] = "";
		$this->ViewAttrs["class"] = "";
	}

	// View Attributes
	function ViewAttributes() {
		$sAtt = "";
		if (intval($this->ImageWidth) > 0 && (!$this->ImageResize || ($this->ImageResize && intval($this->ImageHeight) <= 0)))
			$sAtt .= " width=\"" . intval($this->ImageWidth) . "\"";
		if (intval($this->ImageHeight) > 0 && (!$this->ImageResize || ($this->ImageResize && intval($this->ImageWidth) <= 0)))
			$sAtt .= " height=\"" . intval($this->ImageHeight) . "\"";
		foreach ($this->ViewAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Link Attributes
	function LinkAttributes() {
		$sAtt = "";
		$sHref = trim($this->HrefValue);
		foreach ($this->LinkAttrs as $k => $v) {
			if (trim($v) <> "") {
				if ($k == "href")
					$sHref .= " " . $v;
				else
					$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
			}
		}
		if ($sHref <> "")
			$sAtt .= " href=\"" . trim($sHref) . "\"";

//		if (trim($this->LinkCustomAttributes) <> "")
//			$sAtt .= " " . trim($this->LinkCustomAttributes);

		return $sAtt;
	}

	// Cell attributes
	function CellAttributes() {
		$sAtt = "";
		foreach ($this->CellAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Sort
	function getSort() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TblVar . "_" . EWRPT_TABLE_SORT . "_" . $this->FldVar];
	}

	function setSort($v) {
		if (@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TblVar . "_" . EWRPT_TABLE_SORT . "_" . $this->FldVar] <> $v) {
			$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TblVar . "_" . EWRPT_TABLE_SORT . "_" . $this->FldVar] = $v;
		}
	}

	function ReverseSort() {
		return ($this->getSort() == "ASC") ? "DESC" : "ASC";
	}

	// List view value
	function ListViewValue() {
		$value = trim(strval($this->ViewValue));
		if ($value <> "") {
			$value2 = trim(preg_replace('/<[^img][^>]*>/i', '', strval($value)));
			return ($value2 <> "") ? $this->ViewValue : "&nbsp;";
		} else {
			return "&nbsp;";
		}
	}

	// Form value
	function setFormValue($v) {
		$this->FormValue = ewrpt_StripSlashes($v);
		if (is_array($this->FormValue))
			$this->FormValue = implode(",", $this->FormValue);
		$this->CurrentValue = $this->FormValue;
	}

	// QueryString value
	function setQueryStringValue($v) {
		$this->QueryStringValue = ewrpt_StripSlashes($v);
		$this->CurrentValue = $this->QueryStringValue;
	}

	// Database value
	function setDbValue($v) {
		$this->OldValue = $this->DbValue;
		if (EWRPT_IS_MSSQL && ($this->FldType == 131 || $this->FldType == 139)) // MS SQL adNumeric/adVarNumeric field
			$this->DbValue = floatval($v);
		else
			$this->DbValue = $v;
		$this->CurrentValue = $this->DbValue;
	}

	// Group value
	function GroupValue() {
		return $this->getGroupValue($this->CurrentValue);
	}

	// Group old value
	function GroupOldValue() {
		return $this->getGroupValue($this->OldValue);
	}

	// Get group value
	function getGroupValue($v) {
		if ($this->GroupingFieldId == 1) {
			return $v;
		} elseif (is_array($this->GroupDbValues)) {
			return @$this->GroupDbValues[$v];
		} elseif ($this->FldGroupByType <> "" && $this->FldGroupByType <> "n") {
			return ewrpt_GroupValue($this, $v);
		} else {
			return $v;
		}
	}
}

/**
 * Chart class
 */

class crChart {
	var $TblName; // Table name
	var $TblVar; // Table variable name
	var $ChartName; // Chart name
	var $ChartVar; // Chart variable name
	var $ChartXFldName; // Chart X Field name
	var $ChartYFldName; // Chart Y Field name
	var $ChartSFldName; // Chart Series Field name
	var $ChartType; // Chart Type
	var $ChartSummaryType; // Chart Type
	var $ChartWidth; // Chart Width
	var $ChartHeight; // Chart Height
	var $ChartAlign; // Chart Align
	var $SqlSelect;
	var $SqlGroupBy;
	var $SqlOrderBy;
	var $XAxisDateFormat;
	var $NameDateFormat;
	var $SeriesDateType;
	var $SqlSelectSeries;
	var $SqlGroupBySeries;
	var $SqlOrderBySeries;
	var $ID;
	var $Parms = array();
	var $Trends;
	var $Data;
	var $Series;
	var $XmlDoc;
	var $XmlRoot;

	// Constructor
	function crChart($tblvar, $tblname, $chartvar, $chartname, $xfld, $yfld, $sfld, $type, $smrytype, $width, $height, $align="") {
		$this->TblVar = $tblvar;
		$this->TblName = $tblname;
		$this->ChartVar = $chartvar;
		$this->ChartName = $chartname;
		$this->ChartXFldName = $xfld;
		$this->ChartYFldName = $yfld;
		$this->ChartSFldName = $sfld;
		$this->ChartType = $type;
		$this->ChartSummaryType = $smrytype;
		$this->ChartWidth = $width;
		$this->ChartHeight = $height;
		$this->ChartAlign = $align;
		$this->ID = NULL;
		$this->Parms = NULL;
		$this->Trends = NULL;
		$this->Data = NULL;
		$this->Series = NULL;
		$this->XmlDoc = new DOMDocument("1.0", "utf-8");
	}

	// Chart caption
	function ChartCaption() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->TblVar, $this->ChartVar, "ChartCaption");
	}

	// function xaxisname
	function ChartXAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->TblVar, $this->ChartVar, "ChartXAxisName");
	}

	// function yaxisname
	function ChartYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->TblVar, $this->ChartVar, "ChartYAxisName");
	}

	// function PYAxisName
	function ChartPYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->TblVar, $this->ChartVar, "ChartPYAxisName");
	}

	// function SYAxisName
	function ChartSYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->TblVar, $this->ChartVar, "ChartSYAxisName");
	}

	// Set chart parameters
	function SetChartParam($Name, $Value, $Output) {
		$this->Parms[$Name] = array($Name, $Value, $Output);
	}

	// Set up default chart parm
	function SetupDefaultChartParm($key, $value) {
		if (is_array($this->Parms)) {
			$parm = $this->LoadParm($key);
			if (is_null($parm)) {
				$this->Parms[$key] = array($key, $value, TRUE);
			} elseif ($parm == "") {
				$this->SaveParm($key, $value);
			}
		}
	}

	// Load chart parm
	function LoadParm($key) {
		if (is_array($this->Parms) && array_key_exists($key, $this->Parms))
			return $this->Parms[$key][1];
		return NULL;
	}

	// Save chart parm
	function SaveParm($key, $value) {
		if (is_array($this->Parms) && array_key_exists($key, $this->Parms))
			$this->Parms[$key][1] = $value;
	}

	// Chart Xml
	function ChartXml() {

		// Initialize default values
		$this->SetupDefaultChartParm("caption", "Chart");

		// Show names/values/hover
		$this->SetupDefaultChartParm("shownames", "1"); // Default show names
		$this->SetupDefaultChartParm("showvalues", "1"); // Default show values
		$this->SetupDefaultChartParm("showhover", "1"); // Default show hover

		// Get showvalues/showhovercap
		$cht_showValues = $this->LoadParm("showvalues");
		$cht_showHoverCap = $this->LoadParm("showhovercap");

		// Format percent for Pie charts
		$cht_showPercentageValues = $this->LoadParm("showPercentageValues");
		$cht_showPercentageInLabel = $this->LoadParm("showPercentageInLabel");
		$cht_type = $this->LoadParm("type");
		if ($cht_type == 2 || $cht_type == 6 || $cht_type == 8 || $cht_type == 101) {
			if (($cht_showHoverCap == "1" && $cht_showPercentageValues == "1") ||
			($cht_showValues == "1" && $cht_showPercentageInLabel == "1")) {
				$this->SetupDefaultChartParm("formatNumber", "1");
				$this->SaveParm("formatNumber", "1");
			}
		} elseif ($cht_type == 20) {
			$this->SetupDefaultChartParm("bearBorderColor", "E33C3C");
			$this->SetupDefaultChartParm("bearFillColor", "E33C3C");
		}

		// Process chart parms
		$this->ProcessChartParms($cht_type, $this->Parms);
		$chartseries =& $this->Series;
		$chartdata =& $this->Data;
		$cht_series = ((intval($cht_type) >= 9 && intval($cht_type) <= 19) || (intval($cht_type) >= 102 && intval($cht_type) <= 103)) ? 1 : 0; // $cht_series = 1 (Multi series charts)
		$cht_series_type = $this->LoadParm("seriestype");
		$cht_alpha = $this->LoadParm("alpha");

		// Hide legend for single series (Bar 3D / Column 2D / Line 2D / Area 2D)
		$scrollchart = (intval($this->LoadParm("numVisiblePlot")) > 0 && ($cht_type == 1 || $cht_type == 4 || $cht_type == 7)) ? 1 : 0;
		$cht_single_series = ($cht_type == 104 || $scrollchart == 1) ? 1 : 0;
		if ($cht_single_series == 1) {
			$this->SetupDefaultChartParm("showLegend", "0");
			$this->SaveParm("showLegend", "0");
		}
		if (is_array($chartdata)) {
			$this->WriteChartHeader(); // Write chart header

			// Candlestick
			if ($cht_type == 20) {

				// Write candlestick cat
				if (count($chartdata[0]) >= 7) {
					$cats = $this->XmlDoc->createElement('categories');
					$this->XmlRoot->appendChild($cats);
					$cntcat = count($chartdata);
					for ($i = 0; $i < $cntcat; $i++) {
						$xindex = $i+1;
						$name = $chartdata[$i][6];
						if ($name <> "")
							$this->WriteChartCandlestickCatContent($cats, $xindex, $name);
					}
				}

				// Write candlestick data
				$data = $this->XmlDoc->createElement('data');
				$this->XmlRoot->appendChild($data);
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$open = is_null($chartdata[$i][2]) ? 0 : (float)$chartdata[$i][2];
					$high = is_null($chartdata[$i][3]) ? 0 : (float)$chartdata[$i][3];
					$low = is_null($chartdata[$i][4]) ? 0 : (float)$chartdata[$i][4];
					$close = is_null($chartdata[$i][5]) ? 0 : (float)$chartdata[$i][5];
					$xindex = $i+1;
					$this->WriteChartCandlestickContent($data, $open, $high, $low, $close, $xindex);
				}

			// Multi series
			} else if ($cht_series == 1) {

				// Multi-Y values
				if ($cht_series_type == "1") {

					// Write cat
					$cats = $this->XmlDoc->createElement('categories');
					$this->XmlRoot->appendChild($cats);
					$cntcat = count($chartdata);
					for ($i = 0; $i < $cntcat; $i++) {
						$name = $this->ChartFormatName($chartdata[$i][0]);
						$this->WriteChartCatContent($cats, $name);
					}

					// Write series
					$cntdata = count($chartdata);
					$cntseries = count($chartseries);
					if ($cntseries > count($chartdata[0])-2) $cntseries = count($chartdata[0])-2;
					for ($i = 0; $i < $cntseries; $i++) {
						$color = $this->GetPaletteColor($i);
						$bShowSeries = EWRPT_CHART_SHOW_BLANK_SERIES;
						$dataset = $this->XmlDoc->createElement('dataset');
						$this->WriteChartSeriesHeader($dataset, $chartseries[$i], $color, $cht_alpha);
						$bWriteSeriesHeader = TRUE;
						for ($j = 0; $j < $cntdata; $j++) {
							$val = $chartdata[$j][$i+2];
							$val = (is_null($val)) ? 0 : (float)$val;
							if ($val <> 0) $bShowSeries = TRUE;
							$this->WriteChartSeriesContent($dataset, $val);
						}
						if ($bShowSeries)
							$this->XmlRoot->appendChild($dataset);
					}

				// Series field
				} else {

					// Get series names
					if (is_array($chartseries)) {
						$nSeries = count($chartseries);
					} else {
						$nSeries = 0;
					}

					// Write cat
					$cats = $this->XmlDoc->createElement('categories');
					$this->XmlRoot->appendChild($cats);
					$chartcats = array();
					$cntdata = count($chartdata);
					for ($i = 0; $i < $cntdata; $i++) {
						$name = $chartdata[$i][0];
						if (!in_array($name, $chartcats)) {
							$this->WriteChartCatContent($cats, $name);
							$chartcats[] = $name;
						}
					}

					// Write series
					for ($i = 0; $i < $nSeries; $i++) {
						$seriesname = (is_array($chartseries[$i])) ? $chartseries[$i][0] : $chartseries[$i];
						$color = $this->GetPaletteColor($i);
						$bShowSeries = EWRPT_CHART_SHOW_BLANK_SERIES;
						$dataset = $this->XmlDoc->createElement('dataset');
						$this->WriteChartSeriesHeader($dataset, $chartseries[$i], $color, $cht_alpha);
						$cntcats = count($chartcats);
						$cntdata = count($chartdata);
						for ($j = 0; $j < $cntcats; $j++) {
							$val = 0;
							for ($k = 0; $k < $cntdata; $k++) {
								if ($chartdata[$k][0] == $chartcats[$j] && $chartdata[$k][1] == $seriesname) {
									$val = $chartdata[$k][2];
									$val = (is_null($val)) ? 0 : (float)$val;
									if ($val <> 0) $bShowSeries = TRUE;
									break;
								}
							}
							$this->WriteChartSeriesContent($dataset, $val);
						}
						if ($bShowSeries)
							$this->XmlRoot->appendChild($dataset);
					}
				}

			// Show single series
			} elseif ($cht_single_series == 1) {

				// Write multiple cats
				$cats = $this->XmlDoc->createElement('categories');
				$this->XmlRoot->appendChild($cats);
				$cntcat = count($chartdata);
				for ($i = 0; $i < $cntcat; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$this->WriteChartCatContent($cats, $name);
				}

				// Write series
				$toolTipSep = $this->LoadParm("toolTipSepChar");
				if ($toolTipSep == "") $toolTipSep = ":";
				$cntdata = count($chartdata);
				$dataset = $this->XmlDoc->createElement('dataset');
				$this->WriteChartSeriesHeader($dataset, '', '', $cht_alpha);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = (is_null($val)) ? 0 : (float)$val;
					$color = $this->GetPaletteColor($i);
					$toolText = $name . $toolTipSep . $val;
					$this->WriteChartSeriesContent($dataset, $val, $color, $cht_alpha, '', $toolText);
					$this->XmlRoot->appendChild($dataset);
				}

			// Single series
			} else {
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					$color = $this->GetPaletteColor($i);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = (is_null($val)) ? 0 : (float)$val;
					$this->WriteChartContent($this->XmlRoot, $name, $val, $color, $cht_alpha, @$link); // Get chart content
				}
			}

			// Get trend lines
			$this->WriteChartTrendLines();
		}
		$wrk = $this->XmlDoc->saveXML();
		return $wrk;

		// ewrpt_Trace($wrk);
	}

	// Show chart (FusionCharts Free)
	// typ: chart type (1/2/3/4/...)
	// id: chart id
	// parms: "bgcolor=FFFFFF|..."
	// trends: trend lines
	function ShowChartFCF($xml) {
		$typ = $this->ChartType;
		$id = $this->ID;
		$parms = $this->Parms;
		$trends = $this->Trends;
		$data = $this->Data;
		$series = $this->Series;
		$width = $this->ChartWidth;
		$height = $this->ChartHeight;
		$align = $this->ChartAlign;
		if (empty($typ))
			$typ = 1;

		// Get chart swf
		switch ($typ) {

		// Single Series
			case 1:	$chartswf = "FCF_Column2D.swf"; break; // Column 2D
			case 2:	$chartswf = "FCF_Pie2D.swf"; break; // Pie 2D
			case 3:	$chartswf = "FCF_Bar2D.swf"; break; // Bar 2D
			case 4: $chartswf = "FCF_Line.swf"; break; // Line 2D
			case 5: $chartswf = "FCF_Column3D.swf"; break; // Column 3D
			case 6: $chartswf = "FCF_Pie3D.swf"; break; // Pie 3D
			case 7: $chartswf = "FCF_Area2D.swf"; break; // Area 2D
			case 8: $chartswf = "FCF_Doughnut2D.swf"; break; // Doughnut 2D

		// Multi Series
			case 9: $chartswf = "FCF_MSColumn2D.swf"; break; // Multi-series Column 2D
			case 10: $chartswf = "FCF_MSColumn3D.swf"; break; // Multi-series Column 3D
			case 11: $chartswf = "FCF_MSLine.swf"; break; // Multi-series Line 2D
			case 12: $chartswf = "FCF_MSArea2D.swf"; break; // Multi-series Area 2D
			case 13: $chartswf = "FCF_MSBar2D.swf"; break; // Multi-series Bar 2D

		// Stacked
			case 14: $chartswf = "FCF_StackedColumn2D.swf"; break; // Stacked Column 2D
			case 15: $chartswf = "FCF_StackedColumn3D.swf"; break; // Stacked Column 3D
			case 16: $chartswf = "FCF_StackedArea2D.swf"; break; // Stacked Area 2D
			case 17: $chartswf = "FCF_StackedBar2D.swf"; break; // Stacked Bar 2D

		// Combination
			case 18: $chartswf = "FCF_MSColumn2DLineDY.swf"; break; // Multi-series Column 2D Line Dual Y Chart
			case 19: $chartswf = "FCF_MSColumn3DLineDY.swf"; break; // Multi-series Column 3D Line Dual Y Chart

		// Financial
			case 20: $chartswf = "FCF_Candlestick.swf"; break; // Candlestick

		// Other
			case 21: $chartswf = "FCF_Gantt.swf"; break; // Gantt
			case 22: $chartswf = "FCF_Funnel.swf"; break; // Funnel

		// Additional FusionCharts
			case 101: $chartswf = "FCF_Doughnut2D.swf"; break; // Doughnut 3D, switch back to 2D
			case 102: $chartswf = "FCF_MSBar2D.swf"; break; // Multi-series Bar 3D, switch back to 2D
			case 103: $chartswf = "FCF_StackedBar2D.swf"; break; // Stacked Bar 3D, switch back to 2D
			case 104: $chartswf = "FCF_Bar2D.swf"; break; // Bar 3D, switch back to 2D

		// Default
			default: $chartswf = "FCF_Column2D.swf"; // Default = Column 2D
		}

		// Set width, height and align
		if (is_numeric($width) && is_numeric($height)) {
			$wrkwidth = $width;
			$wrkheight = $height;
		} else { // default
			$wrkwidth = EWRPT_CHART_WIDTH;
			$wrkheight = EWRPT_CHART_HEIGHT;
		}
		if (strtolower($align) == "left" || strtolower($align) == "right") {
			$wrkalign = strtolower($align);
		} else {
			$wrkalign = EWRPT_CHART_ALIGN; // default
		}

		// Output JavaScript for FCF
		$chartxml = $xml;
		$wrk = "<script type=\"text/javascript\">\n";
		$wrk .= "var chartwidth = \"$wrkwidth\";\n";
		$wrk .= "var chartheight = \"$wrkheight\";\n";

		//$wrk .= "var chartalign = \"$wrkalign\";\n";
		$wrk .= "var chartxml = \"" . ewrpt_EscapeJs($chartxml) . "\";\n";
		$wrk .= "var chartid = \"div_$id\";\n";
		$wrk .= "var chartswf = \"" . EWRPT_FUSIONCHARTS_FREE_CHART_PATH . $chartswf . "\";\n";
		$wrk .= "var cht_$id = new FusionChartsFree(chartswf, \"chart_$id\", chartwidth, chartheight);\n";
		$wrk .= "cht_$id.addParam(\"wmode\", \"transparent\");\n";
		$wrk .= "cht_$id.setDataXML(chartxml);\n";
		$wrk .= "var f = " . CurrentPage()->PageObjName . ".Chart_Rendering;\n";
		$wrk .= "if (typeof f == \"function\") f(cht_$id, 'chart_$id');\n";
		$wrk .= "cht_$id.render(chartid);\n";
		$wrk .= "f = " . CurrentPage()->PageObjName . ".Chart_Rendered;\n";
		$wrk .= "if (typeof f == \"function\") f(cht_$id, 'chart_$id');\n";
		$wrk .= "</script>\n";

		// Add debug xml
		if (EWRPT_DEBUG_ENABLED)
			$wrk .= "<p>(Chart XML): " . ewrpt_HtmlEncode($chartxml) . "</p>";
		return $wrk;
	}

	// Show Chart Xml
	function ShowChartXml() {

		// Build chart content
		$sChartContent = $this->ChartXml();
		header("Content-Type: text/xml; charset=UTF-8");

		// Write utf-8 BOM
		echo "\xEF\xBB\xBF";

		// Write utf-8 encoding
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";

		// Write content
		echo $sChartContent;
	}

	// Show Chart Text
	function ShowChartText() {

		// Build chart content
		$sChartContent = $this->ChartXml();
		header("Content-Type: text/plain; charset=UTF-8");

		// Write content
		echo $sChartContent;
	}

	// Get color
	function GetPaletteColor($i) {
		$colorpalette = $this->LoadParm("colorpalette");
		$ar_cht_colorpalette = explode("|", $colorpalette);
		if (is_array($ar_cht_colorpalette))
			$cntar = count($ar_cht_colorpalette);
		return $ar_cht_colorpalette[$i % $cntar];
	}

	// Convert to HTML color
	function ColorCode($c) {
		if ($c <> "") {

			// remove #
			$color = str_replace("#", "", $c);

			// fill to 6 digits
			return str_pad($color, 6, "0", STR_PAD_LEFT);
		} else {
			return "";
		}
	}

	// Output chart header
	function WriteChartHeader() {
		$cht_parms = $this->Parms;
		$chartElement = ($this->ChartType == 20 || (EWRPT_FUSIONCHARTS_FREE && $this->ChartType <> 21 && $this->ChartType <> 22)) ? 'graph' : 'chart';
		$chart = $this->XmlDoc->createElement($chartElement);
		$this->XmlRoot =& $chart;
		$this->XmlDoc->appendChild($chart);
		if (is_array($cht_parms)) {
			foreach ($cht_parms as $parm) {
				if ($parm[2])
					$this->WriteAtt($chart, $parm[0], $parm[1]);
			}
		}
	}

	// Get TrendLine XML
	// <trendlines>
	//    <line startvalue='0.8' displayValue='Good' color='FF0000' thickness='1' isTrendZone='0'/>
	//    <line startvalue='-0.4' displayValue='Bad' color='009999' thickness='1' isTrendZone='0'/>
	// </trendlines>
	function WriteChartTrendLines() {
		$cht_trends = $this->Trends;
		if (is_array($cht_trends)) {
			foreach ($cht_trends as $trend) {
				$trends = $this->XmlDoc->createElement('trendlines');
				$this->XmlRoot->appendChild($trends);

				// Get all trend lines
				$this->WriteChartTrendLine($trends, $trend[0], $trend[1], $trend[2], $trend[3], $trend[4], $trend[5], $trend[6], $trend[7]);
			}
		}
	}

	// Output trend line
	function WriteChartTrendLine(&$node, $startval, $endval, $color, $dispval, $thickness, $trendzone, $showontop, $alpha) {
		$line = $this->XmlDoc->createElement('line');
		$this->WriteAtt($line, "startValue", $startval); // Starting y value
		if ($endval <> 0)
			$this->WriteAtt($line, "endValue", $endval); // Ending y value
		$this->WriteAtt($line, "color", $this->CheckColorCode($color)); // Color
		if ($dispval <> "")
			$this->WriteAtt($line, "displayValue", $dispval); // Display value
		if ($thickness > 0)
			$this->WriteAtt($line, "thickness", $thickness); // Thickness
		$this->WriteAtt($line, "isTrendZone", $trendzone); // Display trend as zone or line
		$this->WriteAtt($line, "showOnTop", $showontop); // Show on top
		if ($alpha > 0)
			$this->WriteAtt($line, "alpha", $alpha); // Alpha
		$node->appendChild($line);
	}

	// Series header/footer XML (multi series)
	function WriteChartSeriesHeader(&$node, $series, $color, $alpha) {
		global $ReportLanguage;
		$seriesname = is_array($series) ? $series[0] : $series;
		if (is_null($seriesname)) {
			$seriesname = $ReportLanguage->Phrase("NullLabel");
		} elseif ($seriesname == "") {
			$seriesname = $ReportLanguage->Phrase("EmptyLabel");
		}
		$this->WriteAtt($node, "seriesname", $seriesname);
		$this->WriteAtt($node, "color", $this->ColorCode($color));
		$this->WriteAtt($node, "alpha", $alpha);
		if (is_array($series))
			$this->WriteAtt($node, "parentYAxis", $series[1]);
	}

	// Series content XML (multi series)
	function WriteChartSeriesContent(&$node, $val, $color = "", $alpha = "", $lnk = "", $toolText = "") {
		$set = $this->XmlDoc->createElement('set');
		$this->WriteAtt($set, "value", $this->ChartFormatNumber($val));
		if ($color <> "")
			$this->WriteAtt($set, "color", $this->ColorCode($color));
		if ($alpha <> "")
			$this->WriteAtt($set, "alpha", $alpha);
		if ($lnk <> "")
			$this->WriteAtt($set, "link", $lnk);
		if ($toolText <> "")
			$this->WriteAtt($set, "toolText", $toolText);
		$node->appendChild($set);
	}

	// Category content XML (Candlestick category)
	function WriteChartCandlestickCatContent(&$node, $xindex, $name) {
		$cat = $this->XmlDoc->createElement('category');
		$this->WriteAtt($cat, "name", $name);
		$this->WriteAtt($cat, "xindex", $xindex);
		$this->WriteAtt($cat, "showline", "1");
		$node->appendChild($cat);
	}

	// Chart content XML (Candlestick)
	function WriteChartCandlestickContent(&$node, $open, $high, $low, $close, $xindex) {
		$set = $this->XmlDoc->createElement('set');
		$this->WriteAtt($set, "open", $this->ChartFormatNumber($open));
		$this->WriteAtt($set, "high", $this->ChartFormatNumber($high));
		$this->WriteAtt($set, "low", $this->ChartFormatNumber($low));
		$this->WriteAtt($set, "close", $this->ChartFormatNumber($close));
		if ($xindex <> "")
			$this->WriteAtt($set, "xindex", $xindex);
		$node->appendChild($set);
	}

	// Format name for chart
	function ChartFormatName($name) {
		global $ReportLanguage;
		if (is_null($name)) {
			return $ReportLanguage->Phrase("NullLabel");
		} elseif ($name == "") {
			return $ReportLanguage->Phrase("EmptyLabel");
		} else {
			return $name;
		}
	}

	// Write attribute
	function WriteAtt(&$node, $name, $val) {
		$val = $this->CheckColorCode(strval($val));
		$val = $this->ChartEncode($val);
		if ($node->hasAttribute($name)) {
			$node->getAttributeNode($name)->value = ewrpt_XmlEncode(ewrpt_ConvertToUtf8($val));
		} else {
			$att = $this->XmlDoc->createAttribute($name);
			$att->value = ewrpt_XmlEncode(ewrpt_ConvertToUtf8($val));
			$node->appendChild($att);
		}
	}

	// Check color code
	function CheckColorCode($val) {
		if (substr($val, 0, 1) == "#" && strlen($val) == 7) {
			return substr($val, 1);
		} else {
			return $val;
		}
	}

	// Process chart parms
	function ProcessChartParms(&$ChartType, &$Parms) {
		if ($ChartType == 104) $ChartType = 3; // Bar 3D, Switch back to Bar 2D

		// Remove numVisiblePlot (scroll charts)
		if (array_key_exists("numVisiblePlot", $Parms))
			unset($Parms["numVisiblePlot"]);
	}

	// Encode special characters for FusionChartsFree
	// + => %2B
	function ChartEncode($val) {
		$v = str_replace("+", "%2B", $val);
		return $v;
	}

	// Format number for chart
	function ChartFormatNumber($v) {
		$cht_decimalprecision = $this->LoadParm("decimalPrecision");
		if (is_null($cht_decimalprecision)) {
			$cht_decimalprecision = (($v-(int)$v) == 0) ? 0 : strlen(abs($v-(int)$v))-2; // Use original decimal precision
		}
		return number_format($v, $cht_decimalprecision, '.', '');
	}

	// Category content XML (multi series)
	function WriteChartCatContent(&$node, $name) {
		$cat = $this->XmlDoc->createElement('category');
		$this->WriteAtt($cat, "name", $name);
		$node->appendChild($cat);
	}

	// Chart content XML
	function WriteChartContent(&$node, $name, $val, $color, $alpha, $lnk) {
		$cht_shownames = $this->LoadParm("shownames");
		$set = $this->XmlDoc->createElement('set');
		$this->WriteAtt($set, "name", $name);
		$this->WriteAtt($set, "value", $this->ChartFormatNumber($val));
		$this->WriteAtt($set, "color", $this->ColorCode($color));
		$this->WriteAtt($set, "alpha", $alpha);
		$this->WriteAtt($set, "link", $lnk);
		if ($cht_shownames == "1")
			$this->WriteAtt($set, "showName", "1");
		$node->appendChild($set);
	}
}

//
// Column class
//
class crCrosstabColumn {
	var $Caption;
	var $Value;
	var $Visible;

	function crCrosstabColumn($value, $caption, $visible = TRUE) {
		$this->Caption = $caption;
		$this->Value = $value;
		$this->Visible = $visible;
    }
}

//
// Advanced filter class
//
class crAdvancedFilter {
	var $ID;
	var $Name;
	var $FunctionName;
	var $Enabled = TRUE;

	function crAdvancedFilter($filterid, $filtername, $filterfunc) {
		$this->ID = $filterid;
		$this->Name = $filtername;
		$this->FunctionName = $filterfunc;
	}
}

/**
 * List option collection class
 */

class crListOptions {
	var $Items = array();
	var $CustomItem = "";
	var $Tag = "td";
	var $Separator = "";

	// Add and return a new option (return-by-reference is for PHP 5 only)
	function &Add($Name) {
		$item = new crListOption($Name, $this->Tag, $this->Separator);
		$item->Parent =& $this;
		$this->Items[$Name] = $item;
		return $item;
	}

	// Load default settings
	function LoadDefault() {
		$this->CustomItem = "";
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Body = "";
	}

	// Hide all options
	function HideAllOptions() {
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Visible = FALSE;
	}

	// Show all options
	function ShowAllOptions() {
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Visible = TRUE;
	}

	// Get item by name (return-by-reference is for PHP 5 only)
	// predefined names: view/edit/copy/delete/detail_<DetailTable>/userpermission/checkbox
	function &GetItem($Name) {
		$item = array_key_exists($Name, $this->Items) ? $this->Items[$Name] : NULL;
		return $item;
	}

	// Move item to position
	function MoveItem($Name, $Pos) {
		$cnt = count($this->Items);
		if ($Pos < 0 || $Pos >= $cnt)
			return;
		$item = $this->GetItem($Name);
		if ($item) {
			unset($this->Items[$Name]);
			$this->Items = array_merge(array_slice($this->Items, 0, $Pos),
				array($Name => $item), array_slice($this->Items, $Pos));
		}
	}

	// Render list options
	function Render($Part, $Pos="") {
		if ($this->CustomItem <> "") {
			$cnt = 0;
			foreach ($this->Items as &$item) {
				if ($item->Visible && $this->ShowPos($item->OnLeft, $Pos))
					$cnt++;
				if ($item->Name == $this->CustomItem)
					$opt = $item;
			}
			if (is_object($opt) && $cnt > 0) {
				if ($this->ShowPos($opt->OnLeft, $Pos)) {
					echo $opt->Render($Part, $cnt);
				} else {
					echo $opt->Render("", $cnt);
				}
			}
		} else {
			foreach ($this->Items as &$item) {
				if ($item->Visible && $this->ShowPos($item->OnLeft, $Pos))
					echo $item->Render($Part);
			}
		}
	}

	function ShowPos($OnLeft, $Pos) {
		return ($OnLeft && $Pos == "left") || (!$OnLeft && $Pos == "right") || ($Pos == "");
	}
}

/**
 * List option class
 */

class crListOption {
	var $Name;
	var $OnLeft;
	var $CssStyle;
	var $Visible = TRUE;
	var $Header;
	var $Body;
	var $Footer;
	var $Tag = "td";
	var $Separator = "";
	var $Parent;

	function crListOption($Name, $Tag, $Separator) {
		$this->Name = $Name;
		$this->Tag = $Tag;
		$this->Separator = $Separator;
	}

	function MoveTo($Pos) {
		$this->Parent->MoveItem($this->Name, $Pos);
	}

	function Render($Part, $ColSpan = 1) {
		if ($Part == "header") {
			$value = $this->Header;
		} elseif ($Part == "body") {
			$value = $this->Body;
		} elseif ($Part == "footer") {
			$value = $this->Footer;
		} else {
			$value = $Part;
		}
		if (strval($value) == "" && strtolower($this->Tag) <> "td")
			return "";
		$res = ($value <> "") ? $value : "&nbsp;";
		$tage = "</" . $this->Tag . ">";
		$tags = "<" . $this->Tag;
		if ($this->CssStyle <> "")
			$tags .= " style=\"" . $this->CssStyle . "\"";
		if (strtolower($this->Tag) == "td" && $ColSpan > 1)
			$tags .= " colspan=\"" . $ColSpan . "\"";
		$tags .= " class=\"phpmaker\"";
		$tags .= ">";
		$res = $tags . $res . $tage . $this->Separator;
		return $res;
	}
}

/**
 * Advanced Security class
 */

class crAdvancedSecurity {
	var $UserLevel = array(); // All User Levels
	var $UserLevelPriv = array(); // All User Level permissions
	var $UserLevelID = array(); // User Level ID array
	var $UserID = array(); // User ID array
	var $CurrentUserLevelID;
	var $CurrentUserLevel; // Permissions
	var $CurrentUserID;
	var $CurrentParentUserID;

	// Constructor
	function crAdvancedSecurity() {

		// Init User Level
		$this->CurrentUserLevelID = $this->SessionUserLevelID();
		if (is_numeric($this->CurrentUserLevelID) && intval($this->CurrentUserLevelID) >= -1) {
			$this->UserLevelID[] = $this->CurrentUserLevelID;
		}

		// Init User ID
		$this->CurrentUserID = $this->SessionUserID();
		$this->CurrentParentUserID = $this->SessionParentUserID();

		// Load user level
		$this->LoadUserLevel();
	}

	// Session User ID
	function SessionUserID() {
		return strval(@$_SESSION[EWRPT_SESSION_USER_ID]);
	}

	function setSessionUserID($v) {
		$_SESSION[EWRPT_SESSION_USER_ID] = trim(strval($v));
		$this->CurrentUserID = trim(strval($v));
	}

	// Session Parent User ID
	function SessionParentUserID() {
		return strval(@$_SESSION[EWRPT_SESSION_PARENT_USER_ID]);
	}

	function setSessionParentUserID($v) {
		$_SESSION[EWRPT_SESSION_PARENT_USER_ID] = trim(strval($v));
		$this->CurrentParentUserID = trim(strval($v));
	}

	// Session User Level ID
	function SessionUserLevelID() {
		return @$_SESSION[EWRPT_SESSION_USER_LEVEL_ID];
	}

	function setSessionUserLevelID($v) {
		$_SESSION[EWRPT_SESSION_USER_LEVEL_ID] = $v;
		$this->CurrentUserLevelID = $v;
		if (is_numeric($v) && $v >= -1)
			$this->UserLevelID = array($v);
	}

	// Session User Level value
	function SessionUserLevel() {
		return @$_SESSION[EWRPT_SESSION_USER_LEVEL];
	}

	function setSessionUserLevel($v) {
		$_SESSION[EWRPT_SESSION_USER_LEVEL] = $v;
		$this->CurrentUserLevel = $v;
	}

	// Current user name
	function getCurrentUserName() {
		return strval(@$_SESSION[EWRPT_SESSION_USER_NAME]);
	}

	function setCurrentUserName($v) {
		$_SESSION[EWRPT_SESSION_USER_NAME] = $v;
	}

	function CurrentUserName() {
		return $this->getCurrentUserName();
	}

	// Current User ID
	function CurrentUserID() {
		return $this->CurrentUserID;
	}

	// Current Parent User ID
	function CurrentParentUserID() {
		return $this->CurrentParentUserID;
	}

	// Current User Level ID
	function CurrentUserLevelID() {
		return $this->CurrentUserLevelID;
	}

	// Current User Level value
	function CurrentUserLevel() {
		return $this->CurrentUserLevel;
	}

	// Can list
	function CanList() {
		return (($this->CurrentUserLevel & EWRPT_ALLOW_LIST) == EWRPT_ALLOW_LIST);
	}

	function setCanList($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWRPT_ALLOW_LIST);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWRPT_ALLOW_LIST));
		}
	}

	// Can report
	function CanReport() {
		return (($this->CurrentUserLevel & EWRPT_ALLOW_REPORT) == EWRPT_ALLOW_REPORT);
	}

	function setCanReport($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWRPT_ALLOW_REPORT);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWRPT_ALLOW_REPORT));
		}
	}

	// Can admin
	function CanAdmin() {
		return (($this->CurrentUserLevel & EWRPT_ALLOW_ADMIN) == EWRPT_ALLOW_ADMIN);
	}

	function setCanAdmin($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWRPT_ALLOW_ADMIN);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWRPT_ALLOW_ADMIN));
		}
	}

	// Last URL
	function LastUrl() {
		return @$_COOKIE[EWRPT_PROJECT_VAR]['LastUrl'];
	}

	// Save last URL
	function SaveLastUrl() {
		$s = ewrpt_ServerVar("SCRIPT_NAME");
		$q = ewrpt_ServerVar("QUERY_STRING");
		if ($q <> "") $s .= "?" . $q;
		if ($this->LastUrl() == $s) $s = "";
		@setcookie(EWRPT_PROJECT_VAR . '[LastUrl]', $s);
	}

	// Auto login
	function AutoLogin() {
		if (@$_COOKIE[EWRPT_PROJECT_VAR]['AutoLogin'] == "autologin") {
			$usr = TEAdecrypt(@$_COOKIE[EWRPT_PROJECT_VAR]['Username'], EWRPT_RANDOM_KEY);
			$pwd = TEAdecrypt(@$_COOKIE[EWRPT_PROJECT_VAR]['Password'], EWRPT_RANDOM_KEY);
			$AutoLogin = $this->ValidateUser($usr, $pwd, TRUE);
		} else {
			$AutoLogin = FALSE;
		}
		return $AutoLogin;
	}

	// Validate user
	function ValidateUser($usr, $pwd, $autologin) {
		global $conn, $ReportLanguage;
		$ValidateUser = FALSE;

		// Call User Custom Validate event
		if (EWRPT_USE_CUSTOM_LOGIN) {
			$ValidateUser = $this->User_CustomValidate($usr, $pwd);
			if ($ValidateUser) {
				$_SESSION[EWRPT_SESSION_STATUS] = "login";
			}
		}
		if (!$ValidateUser)
			$_SESSION[EWRPT_SESSION_STATUS] = ""; // Clear login status
		return $ValidateUser;
	}

	// No User Level security
	function SetUpUserLevel() {}

	// Add user permission
	function AddUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (strval($UserLevelName) == strval($name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (strtolower($table) == strtolower(EWRPT_TABLE_PREFIX . $TableName) && strval($levelid) == strval($UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv | $UserPermission; // Add permission
					break;
				}
			}
		}
	}

	// Delete user permission
	function DeleteUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (strval($UserLevelName) == strval($name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (strtolower($table) == strtolower(EWRPT_TABLE_PREFIX . $TableName) && strval($levelid) == strval($UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv & (127 - $UserPermission); // Remove permission
					break;
				}
			}
		}
	}

	// Load current User Level
	function LoadCurrentUserLevel($Table) {
		$this->LoadUserLevel();
		$this->setSessionUserLevel($this->CurrentUserLevelPriv($Table));
	}

	// Get current user privilege
	function CurrentUserLevelPriv($TableName) {
		if ($this->IsLoggedIn()) {
			$Priv= 0;
			foreach ($this->UserLevelID as $UserLevelID)
				$Priv |= $this->GetUserLevelPrivEx($TableName, $UserLevelID);
			return $Priv;
		} else {
			return 0;
		}
	}

	// Get User Level ID by User Level name
	function GetUserLevelID($UserLevelName) {
		if (strval($UserLevelName) == "Administrator") {
			return -1;
		} elseif ($UserLevelName <> "") {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (strval($name) == strval($UserLevelName))
						return $levelid;
				}
			}
		}
		return -2;
	}

	// Add User Level (for use with UserLevel_Loading event)
	function AddUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		if (!in_array($UserLevelID, $this->UserLevelID))
			$this->UserLevelID[] = $UserLevelID;
	}

	// Delete User Level (for use with UserLevel_Loading event)
	function DeleteUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		$cnt = count($this->UserLevelID);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->UserLevelID[$i] == $UserLevelID) {
				unset($this->UserLevelID[$i]);
				break;
			}
		}
	}

	// User Level list
	function UserLevelList() {
		return implode(", ", $this->UserLevelID);
	}

	// User Level name list
	function UserLevelNameList() {
		$list = "";
		foreach ($this->UserLevelID as $UserLevelID) {
			if ($list <> "") $lList .= ", ";
			$list .= ewrpt_QuotedValue($this->GetUserLevelName($UserLevelID), EWRPT_DATATYPE_STRING);
		}
		return $list;
	}

	// Get user privilege based on table name and User Level
	function GetUserLevelPrivEx($TableName, $UserLevelID) {
		if (strval($UserLevelID) == "-1") { // System Administrator
			return 31; // Use old User Level values
		} elseif ($UserLevelID >= 0) {
			if (is_array($this->UserLevelPriv)) {
				foreach ($this->UserLevelPriv as $row) {
					list($table, $levelid, $priv) = $row;
					if (strtolower($table) == strtolower(EWRPT_TABLE_PREFIX . $TableName) && strval($levelid) == strval($UserLevelID)) {
						if (is_null($priv) || !is_numeric($priv)) return 0;
						return intval($priv);
					}
				}
			}
		}
		return 0;
	}

	// Get current User Level name
	function CurrentUserLevelName() {
		return $this->GetUserLevelName($this->CurrentUserLevelID());
	}

	// Get User Level name based on User Level
	function GetUserLevelName($UserLevelID) {
		if (strval($UserLevelID) == "-1") {
			return "Administrator";
		} elseif ($UserLevelID >= 0) {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (strval($levelid) == strval($UserLevelID))
						return $name;
				}
			}
		}
		return "";
	}

	// Display all the User Level settings (for debug only)
	function ShowUserLevelInfo() {
		echo "<pre class=\"phpmaker\">";
		print_r($this->UserLevel);
		print_r($this->UserLevelPriv);
		echo "</pre>";
		echo "<p>Current User Level ID = " . $this->CurrentUserLevelID() . "</p>";
		echo "<p>Current User Level ID List = " . $this->UserLevelList() . "</p>";
	}

	// Check privilege for List page (for menu items)
	function AllowList($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EWRPT_ALLOW_LIST);
	}

	// Check if user is logged in
	function IsLoggedIn() {
		return (@$_SESSION[EWRPT_SESSION_STATUS] == "login");
	}

	// Check if user is system administrator
	function IsSysAdmin() {
		return (@$_SESSION[EWRPT_SESSION_SYSTEM_ADMIN] == 1);
	}

	// Check if user is administrator
	function IsAdmin() {
		$IsAdmin = $this->IsSysAdmin();
		return $IsAdmin;
  }

	// Save User Level to Session
	function SaveUserLevel() {
		$_SESSION[EWRPT_SESSION_AR_USER_LEVEL] = $this->UserLevel;
		$_SESSION[EWRPT_SESSION_AR_USER_LEVEL_PRIV] = $this->UserLevelPriv;
	}

	// Load User Level from Session
	function LoadUserLevel() {
		if (!is_array(@$_SESSION[EWRPT_SESSION_AR_USER_LEVEL]) || !is_array(@$_SESSION[EWRPT_SESSION_AR_USER_LEVEL_PRIV])) {
			$this->SetupUserLevel();
			$this->SaveUserLevel();
		} else {
			$this->UserLevel = $_SESSION[EWRPT_SESSION_AR_USER_LEVEL];
			$this->UserLevelPriv = $_SESSION[EWRPT_SESSION_AR_USER_LEVEL_PRIV];
		}
	}

	// Get current user info
	function CurrentUserInfo($fieldname) {
		$info = NULL;
		return $info;
	}

	// UserID Loading event
	function UserID_Loading() {

		//echo "UserID Loading: " . $this->CurrentUserID() . "<br>";
	}

	// UserID Loaded event
	function UserID_Loaded() {

		//echo "UserID Loaded: " . $this->UserIDList() . "<br>";
	}

	// User Level Loaded event
	function UserLevel_Loaded() {

		//$this->AddUserPermission(<UserLevelName>, <TableName>, <UserPermission>);
		//$this->DeleteUserPermission(<UserLevelName>, <TableName>, <UserPermission>);

	}

	// User Custom Validate event
	function User_CustomValidate(&$usr, &$pwd) {

		// Return FALSE to continue with default validation after event exits, or return TRUE to skip default validation
		return FALSE;
	}

	// User Validated event
	function User_Validated(&$rs) {

		// Example:
		//$_SESSION['UserEmail'] = $rs['Email'];

	}
}

/**
 * Functions for backward compatibilty
 */

// Get current user name
function CurrentUserName() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserName() : strval(@$_SESSION[EWRPT_SESSION_USER_NAME]);
}

// Get current user ID
function CurrentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserID() : strval(@$_SESSION[EWRPT_SESSION_USER_ID]);
}

// Get current parent user ID
function CurrentParentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentParentUserID() : strval(@$_SESSION[EWRPT_SESSION_PARENT_USER_ID]);
}

// Get current user level
function CurrentUserLevel() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserLevelID() : @$_SESSION[EWRPT_SESSION_USER_LEVEL_ID];
}

// Get current user level list
function CurrentUserLevelList() {
	global $Security;
	return (isset($Security)) ? $Security->UserLevelList() : strval(@$_SESSION[EWRPT_SESSION_USER_LEVEL_ID]);
}

// Get Current user info
function CurrentUserInfo($fldname) {
	global $Security;
	if (isset($Security)) {
		return $Security->CurrentUserInfo($fldname);
	} elseif (defined("EWRPT_USER_TABLE") && !IsSysAdmin()) {
		$user = CurrentUserName();
		if (strval($user) <> "")
			return ew_ExecuteScalar("SELECT " . ew_QuotedName($fldname) . " FROM " . EW_USER_TABLE . " WHERE " .
				str_replace("%u", ew_AdjustSql($user), EWRPT_USER_NAME_FILTER));
	}
	return NULL;
}

// Is logged in
function IsLoggedIn() {
	global $Security;
	return (isset($Security)) ? $Security->IsLoggedIn() : (@$_SESSION[EWRPT_SESSION_STATUS] == "login");
}

// Check if user is system administrator
function IsSysAdmin() {
	return (@$_SESSION[EWRPT_SESSION_SYSTEM_ADMIN] == 1);
}

// Get current page ID
function CurrentPageID() {
	if (isset($GLOBALS["Page"])) {
		return $GLOBALS["Page"]->PageID;
	} elseif (defined("EWRPT_PAGE_ID")) {
		return EWRPT_PAGE_ID;
	}
	return "";
}

// Allow list
function AllowList($TableName) {
	global $Security;
	return $Security->AllowList($TableName);
}

// Load recordset
function &ew_LoadRecordset($SQL) {
	global $conn;
	$conn->raiseErrorFn = 'ewrpt_ErrorFn'; //*** changed to "ewrpt_"
	$rs = $conn->Execute($SQL);
	$conn->raiseErrorFn = '';
	return $rs;
}

// Executes the query, and returns the first column of the first row
function ew_ExecuteScalar($SQL) {
	$res = FALSE;
	$rs = ew_LoadRecordset($SQL);
	if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
		$res = $rs->fields[0];
		$rs->Close();
	}
	return $res;
}

// Executes the query, and returns the first row
function ew_ExecuteRow($SQL) {
	$res = FALSE;
	$rs = ew_LoadRecordset($SQL);
	if ($rs && !$rs->EOF) {
		$res = $rs->fields;
		$rs->Close();
	}
	return $res;
}

// Check if valid operator
function ewrpt_IsValidOpr($Opr, $FldType) {
	$valid = ($Opr == "=" || $Opr == "<" || $Opr == "<=" ||
		$Opr == ">" || $Opr == ">=" || $Opr == "<>");
	if ($FldType == EWRPT_DATATYPE_STRING || $FldType == EWRPT_DATATYPE_MEMO)
		$valid = ($valid || $Opr == "LIKE" || $Opr == "NOT LIKE" || $Opr == "STARTS WITH");
	return $valid;
}

// Quote table/field name
function ewrpt_QuotedName($Name) {
	$Name = str_replace(EWRPT_DB_QUOTE_END, EWRPT_DB_QUOTE_END . EWRPT_DB_QUOTE_END, $Name);
	return EWRPT_DB_QUOTE_START . $Name . EWRPT_DB_QUOTE_END;
}

// quote field values
function ewrpt_QuotedValue($Value, $FldType) {
	if (is_null($Value))
		return "NULL";
	switch ($FldType) {
	case EWRPT_DATATYPE_STRING:
	case EWRPT_DATATYPE_BLOB:
	case EWRPT_DATATYPE_MEMO:
	case EWRPT_DATATYPE_TIME:
			return "'" . ewrpt_AdjustSql($Value) . "'";
	case EWRPT_DATATYPE_DATE:
		return (EWRPT_IS_MSACCESS) ? "#" . ewrpt_AdjustSql($Value) . "#" :
			"'" . ewrpt_AdjustSql($Value) . "'";

//	case EWRPT_DATATYPE_GUID:
//		if (EWRPT_IS_MSACCESS) {
//			if (strlen($Value) == 38) {
//				return "{guid " . $Value . "}";
//			} elseif (strlen($Value) == 36) {
//				return "{guid {" . $Value . "}}";
//			}
//		} else {
//		  return "'" . $Value . "'";
//		}

	case EWRPT_DATATYPE_BOOLEAN: // enum('Y'/'N') or enum('1'/'0')

		//return "'" . $Value . "'";
		return (EWRPT_IS_MSACCESS) ? $Value : "'" . ewrpt_AdjustSql($Value) . "'";
	default:
		return $Value;
	}
}

// Get distinct values
function ewrpt_GetDistinctValues($FldOpr, $sql) {
	global $conn;
	$ar = array();
	if (strval($sql) == "")
		return;
	$wrkrs = $conn->Execute($sql);
	if ($wrkrs && !$wrkrs->EOF) {
		$ar[] = ewrpt_ConvertValue($FldOpr, $wrkrs->fields[0]);
		$wrkrs->MoveNext();
		while (!$wrkrs->EOF) {
			$wrkval = ewrpt_ConvertValue($FldOpr, $wrkrs->fields[0]);
			$cntar = count($ar);
			if ($wrkval <> $ar[$cntar-1])
				$ar[] = $wrkval;
			$wrkrs->MoveNext();
		}
	}
	if ($wrkrs) $wrkrs->Close();
	return $ar;
}

// Convert value
function ewrpt_ConvertValue($FldOpr, $val) {
	if (is_null($val)) {
		return EWRPT_NULL_VALUE;
	} elseif ($val == "") {
		return EWRPT_EMPTY_VALUE;
	}
	if (is_float($val))
		$val = (float)$val;
	if ($FldOpr == "")
		return $val;
	if ($ar = explode(" ", $val)) {
		$ar = explode("-", $ar[0]);
	} else {
		return $val;
	}
	if (!$ar || count($ar) <> 3)
		return $val;
	list($year, $month, $day) = $ar;
	switch (strtolower($FldOpr)) {
	case "year":
		return $year;
	case "quarter":
		return "$year|" . ceil(intval($month)/3);
	case "month":
		return "$year|$month";
	case "day":
		return "$year|$month|$day";
	case "date":
		return "$year-$month-$day";
	}
}

// Dropdown display values
function ewrpt_DropDownDisplayValue($v, $t, $fmt) {
	global $ReportLanguage;
	if ($v == EWRPT_NULL_VALUE) {
		return $ReportLanguage->Phrase("NullLabel");
	} elseif ($v == EWRPT_EMPTY_VALUE) {
		return $ReportLanguage->Phrase("EmptyLabel");
	} elseif (strtolower($t) == "boolean") {
		return ewrpt_BooleanName($v);
	}
	if ($t == "")
		return $v;
	$ar = explode("|", strval($v));
	switch (strtolower($t)) {
	case "year":
		return $v;
	case "quarter":
		if (count($ar) >= 2)
			return ewrpt_QuarterName($ar[1]) . " " . $ar[0];
	case "month":
		if (count($ar) >= 2)
			return ewrpt_MonthName($ar[1]) . " " . $ar[0];
	case "day":
		if (count($ar) >= 3)
			return ewrpt_FormatDateTime($ar[0] . "-" . $ar[1] . "-" . $ar[2], $fmt);
	case "date":
		return ewrpt_FormatDateTime($v, $fmt);
	}
}

// Get Boolean Name
// - Treat "T" / "True" / "Y" / "Yes" / "1" As True
function ewrpt_BooleanName($v) {
	global $ReportLanguage;
	if (is_null($v))
		return $ReportLanguage->Phrase("NullLabel");
	elseif (strtoupper($v) == "T" || strtoupper($v) == "TRUE" || strtoupper($v) == "Y" || strtoupper($v) == "YES" Or strval($v) == "1")
		return $ReportLanguage->Phrase("BooleanYes");
	else
		return $ReportLanguage->Phrase("BooleanNo");
}

// Quarter name
function ewrpt_QuarterName($q) {
	global $ReportLanguage;
	switch ($q) {
	case 1:
		return $ReportLanguage->Phrase("Qtr1");
	case 2:
		return $ReportLanguage->Phrase("Qtr2");
	case 3:
		return $ReportLanguage->Phrase("Qtr3");
	case 4:
		return $ReportLanguage->Phrase("Qtr4");
	default:
		return $q;
	}
}

// Month name
function ewrpt_MonthName($m) {
	global $ReportLanguage;
	switch ($m) {
	case 1:
		return $ReportLanguage->Phrase("MonthJan");
	case 2:
		return $ReportLanguage->Phrase("MonthFeb");
	case 3:
		return $ReportLanguage->Phrase("MonthMar");
	case 4:
		return $ReportLanguage->Phrase("MonthApr");
	case 5:
		return $ReportLanguage->Phrase("MonthMay");
	case 6:
		return $ReportLanguage->Phrase("MonthJun");
	case 7:
		return $ReportLanguage->Phrase("MonthJul");
	case 8:
		return $ReportLanguage->Phrase("MonthAug");
	case 9:
		return $ReportLanguage->Phrase("MonthSep");
	case 10:
		return $ReportLanguage->Phrase("MonthOct");
	case 11:
		return $ReportLanguage->Phrase("MonthNov");
	case 12:
		return $ReportLanguage->Phrase("MonthDec");
	default:
		return $m;
	}
}

// Join array
function ewrpt_JoinArray($ar, $sep, $ft, $pos=0) {
	if (!is_array($ar))
		return "";
	$arwrk = array_slice($ar, $pos); // return array from position pos
	$cntar = count($arwrk);
	for ($i = 0; $i < $cntar; $i++)
		$arwrk[$i] = ewrpt_QuotedValue($arwrk[$i], $ft);
	return implode($sep, $arwrk);
}

// Unformat date time based on format type
function ewrpt_UnFormatDateTime($dt, $namedformat) {
	$dt = trim($dt);
	while (strpos($dt, "  ") !== FALSE) $dt = str_replace("  ", " ", $dt);
	$arDateTime = explode(" ", $dt);
	if (count($arDateTime) == 0) return $dt;
	$arDatePt = explode(EWRPT_DATE_SEPARATOR, $arDateTime[0]);
	if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8) {
		$arDefFmt = explode(EWRPT_DATE_SEPARATOR, EWRPT_DEFAULT_DATE_FORMAT);
		if ($arDefFmt[0] == "yyyy") {
			$namedformat = 9;
		} elseif ($arDefFmt[0] == "mm") {
			$namedformat = 10;
		} elseif ($arDefFmt[0] == "dd") {
			$namedformat = 11;
		}
	}
	if (count($arDatePt) == 3) {
		switch ($namedformat) {
		case 5:
		case 9: //yyyymmdd
			if (ewrpt_CheckDate($arDateTime[0])) {
				list($year, $month, $day) = $arDatePt;
				break;
			} else {
				return $dt;
			}
		case 6:
		case 10: //mmddyyyy
			if (ewrpt_CheckUSDate($arDateTime[0])) {
				list($month, $day, $year) = $arDatePt;
				break;
			} else {
				return $dt;
			}
		case 7:
		case 11: //ddmmyyyy
			if (ewrpt_CheckEuroDate($arDateTime[0])) {
				list($day, $month, $year) = $arDatePt;
				break;
			} else {
				return $dt;
			}
		case 12:
		case 15: //yymmdd
			if (ewrpt_CheckShortDate($arDateTime[0])) {
				list($year, $month, $day) = $arDatePt;
				$year = ewrpt_UnformatYear($year);
				break;
			} else {
				return $dt;
			}
		case 13:
		case 16: //mmddyy
			if (ewrpt_CheckShortUSDate($arDateTime[0])) {
				list($month, $day, $year) = $arDatePt;
				$year = ewrpt_UnformatYear($year);
				break;
			} else {
				return $dt;
			}
		case 14:
		case 17: //ddmmyy
			if (ewrpt_CheckShortEuroDate($arDateTime[0])) {
				list($day, $month, $year) = $arDatePt;
				$year = ewrpt_UnformatYear($year);
				break;
			} else {
				return $dt;
			}
		default:
			return $dt;
		}
		if (strlen($year) <= 4 && strlen($month) <= 2 && strlen($day) <= 2) {
			return $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" .
				 str_pad($day, 2, "0", STR_PAD_LEFT) .
				((count($arDateTime) > 1) ? " " . $arDateTime[1] : "");
		} else {
			return $dt;
		}
	} else {
		return $dt;
	}
}

// ViewValue
// - return &nbsp; if empty
function ewrpt_ViewValue($value) {
	if ($value <> "")
		return $value;
	else
		return "&nbsp;";
}

// Get current year
function ewrpt_CurrentYear() {
	return intval(date('Y'));
}

// Get current quarter
function ewrpt_CurrentQuarter() {
	return ceil(intval(date('n'))/3);
}

// Get current month
function ewrpt_CurrentMonth() {
	return intval(date('n'));
}

// Get current day
function ewrpt_CurrentDay() {
	return intval(date('j'));
}

// FormatDateTime
// Format a timestamp, datetime, date or time field from MySQL
// $namedformat:
// 0 - General Date
// 1 - Long Date
// 2 - Short Date (Default)
// 3 - Long Time
// 4 - Short Time (hh:mm:ss)
// 5 - Short Date (yyyy/mm/dd)
// 6 - Short Date (mm/dd/yyyy)
// 7 - Short Date (dd/mm/yyyy)
// 8 - Short Date (Default) + Short Time (if not 00:00:00)
// 9 - Short Date (yyyy/mm/dd) + Short Time (hh:mm:ss)
// 10 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 11 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
// 12 - Short Date - 2 digit year (yy/mm/dd)
// 13 - Short Date - 2 digit year (mm/dd/yy)
// 14 - Short Date - 2 digit year (dd/mm/yy)
// 15 - Short Date - 2 digit year (yy/mm/dd) + Short Time (hh:mm:ss)
// 16 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 17 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
function ewrpt_FormatDateTime($ts, $namedformat) {
	$DefDateFormat = str_replace("yyyy", "%Y", EWRPT_DEFAULT_DATE_FORMAT);
	$DefDateFormat = str_replace("mm", "%m", $DefDateFormat);
	$DefDateFormat = str_replace("dd", "%d", $DefDateFormat);
	if (is_numeric($ts)) // timestamp
	{
		switch (strlen($ts)) {
			case 14:
				$patt = '/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 12:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 10:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 8:
				$patt = '/(\d{4})(\d{2})(\d{2})/';
				break;
			case 6:
				$patt = '/(\d{2})(\d{2})(\d{2})/';
				break;
			case 4:
				$patt = '/(\d{2})(\d{2})/';
				break;
			case 2:
				$patt = '/(\d{2})/';
				break;
			default:
				return $ts;
		}
		if ((isset($patt))&&(preg_match($patt, $ts, $matches)))
		{
			$year = $matches[1];
			$month = @$matches[2];
			$day = @$matches[3];
			$hour = @$matches[4];
			$min = @$matches[5];
			$sec = @$matches[6];
		}
		if (($namedformat==0)&&(strlen($ts)<10)) $namedformat = 2;
	}
	elseif (is_string($ts))
	{
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // datetime
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
		}
		elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) // date
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			if ($namedformat==0) $namedformat = 2;
		}
		elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // time
		{
			$hour = $matches[2];
			$min = $matches[3];
			$sec = $matches[4];
			if (($namedformat==0)||($namedformat==1)) $namedformat = 3;
			if ($namedformat==2) $namedformat = 4;
		}
		else
		{
			return $ts;
		}
	}
	else
	{
		return $ts;
	}
	if (!isset($year)) $year = 0; // dummy value for times
	if (!isset($month)) $month = 1;
	if (!isset($day)) $day = 1;
	if (!isset($hour)) $hour = 0;
	if (!isset($min)) $min = 0;
	if (!isset($sec)) $sec = 0;
	$uts = @mktime($hour, $min, $sec, $month, $day, $year);
	if ($uts < 0 || $uts == FALSE || // failed to convert
		(intval($year) == 0 && intval($month) == 0 && intval($day) == 0)) {
		$year = substr_replace("0000", $year, -1 * strlen($year));
		$month = substr_replace("00", $month, -1 * strlen($month));
		$day = substr_replace("00", $day, -1 * strlen($day));
		$hour = substr_replace("00", $hour, -1 * strlen($hour));
		$min = substr_replace("00", $min, -1 * strlen($min));
		$sec = substr_replace("00", $sec, -1 * strlen($sec));
		$DefDateFormat = str_replace("yyyy", $year, EWRPT_DEFAULT_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {
			case 0:
				return $DefDateFormat." $hour:$min:$sec";
				break;
			case 1://unsupported, return general date
				return $DefDateFormat." $hour:$min:$sec";
				break;
			case 2:
				return $DefDateFormat;
				break;
			case 3:
				if (intval($hour)==0)
					return "12:$min:$sec AM";
				elseif (intval($hour)>0 && intval($hour)<12)
					return "$hour:$min:$sec AM";
				elseif (intval($hour)==12)
					return "$hour:$min:$sec PM";
				elseif (intval($hour)>12 && intval($hour)<=23)
					return (intval($hour)-12).":$min:$sec PM";
				else
					return "$hour:$min:$sec";
				break;
			case 4:
				return "$hour:$min:$sec";
				break;
			case 5:
				return "$year". EWRPT_DATE_SEPARATOR . "$month" . EWRPT_DATE_SEPARATOR . "$day";
				break;
			case 6:
				return "$month". EWRPT_DATE_SEPARATOR ."$day" . EWRPT_DATE_SEPARATOR . "$year";
				break;
			case 7:
				return "$day" . EWRPT_DATE_SEPARATOR ."$month" . EWRPT_DATE_SEPARATOR . "$year";
				break;
			case 8:
				return $DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " $hour:$min:$sec");
				break;
			case 9:
				return "$year". EWRPT_DATE_SEPARATOR . "$month" . EWRPT_DATE_SEPARATOR . "$day $hour:$min:$sec";
				break;
			case 10:
				return "$month". EWRPT_DATE_SEPARATOR ."$day" . EWRPT_DATE_SEPARATOR . "$year $hour:$min:$sec";
				break;
			case 11:
				return "$day" . EWRPT_DATE_SEPARATOR ."$month" . EWRPT_DATE_SEPARATOR . "$year $hour:$min:$sec";
				break;
			case 12:
				return substr($year,-2) . EWRPT_DATE_SEPARATOR . $month . EWRPT_DATE_SEPARATOR . $day;
				break;
			case 13:
				return substr($year,-2) . EWRPT_DATE_SEPARATOR . $month . EWRPT_DATE_SEPARATOR . $day;
				break;
			case 14:
				return substr($year,-2) . EWRPT_DATE_SEPARATOR . $month . EWRPT_DATE_SEPARATOR . $day;
				break;
			default:
				return $ts;
		}
	} else {
		switch ($namedformat) {
			case 0:
				return strftime($DefDateFormat." %H:%M:%S", $uts);
				break;
			case 1:
				return strftime("%A, %B %d, %Y", $uts);
				break;
			case 2:
				return strftime($DefDateFormat, $uts);
				break;
			case 3:
				return strftime("%I:%M:%S %p", $uts);
				break;
			case 4:
				return strftime("%H:%M:%S", $uts);
				break;
			case 5:
				return strftime("%Y" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%d", $uts);
				break;
			case 6:
				return strftime("%m" . EWRPT_DATE_SEPARATOR . "%d" . EWRPT_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 7:
				return strftime("%d" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 8:
				return strftime($DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " %H:%M:%S"), $uts);
				break;
			case 9:
				return strftime("%Y" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%d %H:%M:%S", $uts);
				break;
			case 10:
				return strftime("%m" . EWRPT_DATE_SEPARATOR . "%d" . EWRPT_DATE_SEPARATOR . "%Y %H:%M:%S", $uts);
				break;
			case 11:
				return strftime("%d" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%Y %H:%M:%S", $uts);
				break;
			case 12:
				return strftime("%y" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%d", $uts);
				break;
			case 13:
				return strftime("%m" . EWRPT_DATE_SEPARATOR . "%d" . EWRPT_DATE_SEPARATOR . "%y", $uts);
				break;
			case 14:
				return strftime("%d" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%y", $uts);
				break;
			case 15:
				return strftime("%y" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%d %H:%M:%S", $uts);
				break;
			case 16:
				return strftime("%m" . EWRPT_DATE_SEPARATOR . "%d" . EWRPT_DATE_SEPARATOR . "%y %H:%M:%S", $uts);
				break;
			case 17:
				return strftime("%d" . EWRPT_DATE_SEPARATOR . "%m" . EWRPT_DATE_SEPARATOR . "%y %H:%M:%S", $uts);
				break;
			default:
				return $ts;
		}
	}
}

// FormatCurrency
// FormatCurrency(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
//  [,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the
// right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
// arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewrpt_FormatCurrency($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;

	// export the values returned by localeconv into the local scope
	extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = EWRPT_DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = EWRPT_DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = EWRPT_DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = EWRPT_DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = EWRPT_DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = EWRPT_DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = EWRPT_DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = EWRPT_DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = EWRPT_DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = EWRPT_DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = EWRPT_DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount),
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;

		// "extracts" the boolean value as an integer
		$n_cs_precedes  = intval($n_cs_precedes  == true);
		$n_sep_by_space = intval($n_sep_by_space == true);
		$key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$p_cs_precedes  = intval($p_cs_precedes  == true);
		$p_sep_by_space = intval($p_sep_by_space == true);
		$key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
	}
	$formats = array(

	  // currency symbol is after amount
	  // no space between amount and sign

	  '000' => '(%s' . $currency_symbol . ')',
	  '001' => $sign . '%s ' . $currency_symbol,
	  '002' => '%s' . $currency_symbol . $sign,
	  '003' => '%s' . $sign . $currency_symbol,
	  '004' => '%s' . $sign . $currency_symbol,

	  // one space between amount and sign
	  '010' => '(%s ' . $currency_symbol . ')',
	  '011' => $sign . '%s ' . $currency_symbol,
	  '012' => '%s ' . $currency_symbol . $sign,
	  '013' => '%s ' . $sign . $currency_symbol,
	  '014' => '%s ' . $sign . $currency_symbol,

	  // currency symbol is before amount
	  // no space between amount and sign

	  '100' => '(' . $currency_symbol . '%s)',
	  '101' => $sign . $currency_symbol . '%s',
	  '102' => $currency_symbol . '%s' . $sign,
	  '103' => $sign . $currency_symbol . '%s',
	  '104' => $currency_symbol . $sign . '%s',

	  // one space between amount and sign
	  '110' => '(' . $currency_symbol . ' %s)',
	  '111' => $sign . $currency_symbol . ' %s',
	  '112' => $currency_symbol . ' %s' . $sign,
	  '113' => $sign . $currency_symbol . ' %s',
	  '114' => $currency_symbol . ' ' . $sign . '%s');

  // lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatNumber
// FormatNumber(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
// 	[,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the
// right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
// arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewrpt_FormatNumber($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;

	// export the values returned by localeconv into the local scope
	extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = EWRPT_DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = EWRPT_DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = EWRPT_DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = EWRPT_DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = EWRPT_DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = EWRPT_DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = EWRPT_DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = EWRPT_DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = EWRPT_DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = EWRPT_DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = EWRPT_DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount),
						  $frac_digits,
						  $mon_decimal_point,
						  $mon_thousands_sep);

	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s)',
		'1' => $sign . '%s',
		'2' => $sign . '%s',
		'3' => $sign . '%s',
		'4' => $sign . '%s');

	// lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatPercent
// FormatPercent(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
// 	[,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the
// right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
// arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewrpt_FormatPercent($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;

	// export the values returned by localeconv into the local scope
	extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = EWRPT_DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = EWRPT_DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = EWRPT_DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = EWRPT_DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = EWRPT_DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = EWRPT_DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = EWRPT_DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = EWRPT_DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = EWRPT_DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = EWRPT_DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = EWRPT_DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = EWRPT_DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount)*100,
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s%%)',
		'1' => $sign . '%s%%',
		'2' => $sign . '%s%%',
		'3' => $sign . '%s%%',
		'4' => $sign . '%s%%');

	// lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// Add slashes for SQL
function ewrpt_AdjustSql($val) {
	$val = addslashes(trim($val));
	return $val;
}

// Build Report SQL
function ewrpt_BuildReportSql($sSelect, $sWhere, $sGroupBy, $sHaving, $sOrderBy, $sFilter, $sSort) {
	$sDbWhere = $sWhere;
	if ($sDbWhere <> "") $sDbWhere = "(" . $sDbWhere . ")";
	if ($sFilter <> "") {
		if ($sDbWhere <> "") $sDbWhere .= " AND ";
		$sDbWhere .= "(" . $sFilter . ")";
	}
	$sDbOrderBy = ewrpt_UpdateSortFields($sOrderBy, $sSort, 1);
	$sSql = $sSelect;
	if ($sDbWhere <> "") $sSql .= " WHERE " . $sDbWhere;
	if ($sGroupBy <> "") $sSql .= " GROUP BY " . $sGroupBy;
	if ($sHaving <> "") $sSql .= " HAVING " . $sHaving;
	if ($sDbOrderBy <> "") $sSql .= " ORDER BY " . $sDbOrderBy;
	return $sSql;
}

// Update sort fields
// opt = 1, merge all sort fields
// opt = 2, merge sOrderBy fields only
function ewrpt_UpdateSortFields($sOrderBy, $sSort, $opt) {
	if ($sOrderBy == "") {
		if ($opt == 1)
			return $sSort;
		else
			return "";
	} elseif ($sSort == "") {
		return $sOrderBy;
	} else {

		// Merge sort field list
		$arorderby = ewrpt_GetSortFlds($sOrderBy);
		$cntorderby = count($arorderby);
		$arsort = ewrpt_GetSortFlds($sSort);
		$cntsort = count($arsort);
		for ($i = 0; $i < $cntsort; $i++) {

			// Get sort field
			$sortfld = trim($arsort[$i]);
			if (strtoupper(substr($sortfld,-4)) == " ASC") {
				$sortfld = trim(substr($sortfld,0,-4));
			} elseif (strtoupper(substr($sortfld,-5)) == " DESC") {
				$sortfld = trim(substr($sortfld,0,-4));
			}
			for ($j = 0; $j < $cntorderby; $j++) {

				// Get orderby field
				$orderfld = trim($arorderby[$j]);
				if (strtoupper(substr($orderfld,-4)) == " ASC") {
					$orderfld = trim(substr($orderfld,0,-4));
				} elseif (strtoupper(substr($orderfld,-5)) == " DESC") {
					$orderfld = trim(substr($orderfld,0,-4));
				}

				// Replace field
				if ($orderfld == $sortfld) {
					$arorderby[$j] = $arsort[$i];
					break;
				}
			}

			// Append field
			if ($opt == 1) {
				if ($orderfld <> $sortfld)
					$arorderby[] = $arsort[$i];
			}
		}
		return implode(", ", $arorderby);
	}
}

// Get sort fields
function ewrpt_GetSortFlds($flds) {
	$offset = -1;
	$fldpos = 0;
	$ar = array();
	while ($offset = strpos($flds, ",", $offset + 1)) {
		$orderfld = substr($flds,$fldpos,$offset-$fldpos);
		if ((strtoupper(substr($orderfld,-4)) == " ASC") || (strtoupper(substr($orderfld,-5)) == " DESC")) {
			$fldpos = $offset+1;
			$ar[] = $orderfld;
		}
	}
	$ar[] = substr($flds,$fldpos);
	return $ar;
}

// Get reverse sort
function ewrpt_ReverseSort($sorttype) {
	return ($sorttype == "ASC") ? "DESC" : "ASC";
}

// Construct a crosstab field name
function ewrpt_CrossTabField($smrytype, $smryfld, $colfld, $datetype, $val, $qc, $alias="") {
	if ($val == EWRPT_NULL_VALUE) {
		$wrkval = "NULL";
		$wrkqc = "";
	} elseif ($val == EWRPT_EMPTY_VALUE) {
		$wrkval = "";
		$wrkqc = $qc;
	} else {
		$wrkval = $val;
		$wrkqc = $qc;
	}
	switch ($smrytype) {
	case "SUM":
		$fld = $smrytype . "(" . $smryfld . "*" . ewrpt_SQLDistinctFactor($colfld, $datetype, $val, $qc) . ")";
		break;
	case "COUNT":
		$fld = "SUM(" . ewrpt_SQLDistinctFactor($colfld, $datetype, $wrkval, $wrkqc) . ")";
		break;
	case "MIN":
	case "MAX":
		$aggwrk = ewrpt_SQLDistinctFactor($colfld, $datetype, $wrkval, $wrkqc);
		$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		if (EWRPT_IS_MSACCESS)
			$fld = $smrytype . "(IIf(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		elseif (EWRPT_IS_MSSQL || EWRPT_IS_ORACLE)
			$fld = $smrytype . "(CASE " . $aggwrk . " WHEN 0 THEN NULL ELSE " . $smryfld . " END)";
		elseif (EWRPT_IS_MYSQL || EWRPT_IS_POSTGRESQL)
			$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		break;
	case "AVG":
		$sumwrk = "SUM(" . $smryfld . "*" . ewrpt_SQLDistinctFactor($colfld, $datetype, $wrkval, $wrkqc) . ")";
		if ($alias != "")

//			$sumwrk .= " AS SUM_" . $alias;
			$sumwrk .= " AS sum_" . $alias;
		$cntwrk =	"SUM(" . ewrpt_SQLDistinctFactor($colfld, $datetype, $wrkval, $wrkqc) . ")";
		if ($alias != "")

//			$cntwrk .= " AS CNT_" . $alias;
			$cntwrk .= " AS cnt_" . $alias;
		return $sumwrk . ", " . $cntwrk;
	}
	if ($alias != "")
		$fld .= " AS " . $alias;
	return $fld;
}

// Construct SQL Distinct factor (MySQL)
// - ACCESS
// y: IIf(Year(FieldName)=1996,1,0)
// q: IIf(DatePart(""q"",FieldName,1,0)=1,1,0))
// m: (IIf(DatePart(""m"",FieldName,1,0)=1,1,0)))
// others: (IIf(FieldName=val,1,0)))
// - MS SQL
// y: (1-ABS(SIGN(Year(FieldName)-1996)))
// q: (1-ABS(SIGN(DatePart(q,FieldName)-1)))
// m: (1-ABS(SIGN(DatePart(m,FieldName)-1)))
// d: (CASE Convert(VarChar(10),FieldName,120) WHEN '1996-1-1' THEN 1 ELSE 0 END)
// - MySQL
// y: IF(YEAR(FieldName)=1996,1,0))
// q: IF(QUARTER(FieldName)=1,1,0))
// m: IF(MONTH(FieldName)=1,1,0))
// - PostgreSql
// y: IF(EXTRACT(YEAR FROM FieldName)=1996,1,0))
// q: IF(EXTRACT(QUARTER FROM FieldName)=1,1,0))
// m: IF(EXTRACT(MONTH FROM FieldName)=1,1,0))
function ewrpt_SQLDistinctFactor($sFld, $dateType, $val, $qc) {

	// ACCESS
	if (EWRPT_IS_MSACCESS) {
		if ($dateType == "y" && is_numeric($val)) {
			return "IIf(Year(" . $sFld . ")=" . $val . ",1,0)";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "IIf(DatePart(\"" . $dateType . "\"," . $sFld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL")
				return "IIf(" . $sFld . " IS NULL,1,0)";
			else
				return "IIf(" . $sFld . "=" . $qc . ewrpt_AdjustSql($val) . $qc . ",1,0)";
		}

	// MS SQL
	} elseif (EWRPT_IS_MSSQL) {
		if ($dateType == "y" && is_numeric($val)) {
			return "(1-ABS(SIGN(Year(" . $sFld . ")-" . $val . ")))";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "(1-ABS(SIGN(DatePart(" . $dateType . "," . $sFld . ")-" . $val . ")))";
		} elseif ($dateType == "d") {
			return "(CASE CONVERT(VARCHAR(10)," . $sFld . ",120) WHEN " . $qc . ewrpt_AdjustSql($val) . $qc . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "dt") {
			return "(CASE CONVERT(VARCHAR," . $sFld . ",120) WHEN " . $qc . ewrpt_AdjustSql($val) . $qc . " THEN 1 ELSE 0 END)";
		} else {
			if ($val == "NULL")
				return "(CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END)";
			else
				return "(CASE " . $sFld . " WHEN " . $qc . ewrpt_AdjustSql($val) . $qc . " THEN 1 ELSE 0 END)";
		}

	// MySQL
	} elseif (EWRPT_IS_MYSQL) {
		if ($dateType == "y" && is_numeric($val)) {
			return "IF(YEAR(" . $sFld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "IF(QUARTER(" . $sFld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "IF(MONTH(" . $sFld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL") {
				return "IF(" . $sFld . " IS NULL,1,0)";
			} else {
				return "IF(" . $sFld . "=" . $qc . ewrpt_AdjustSql($val) . $qc . ",1,0)";
			}
		}

	// PostgreSql
	} elseif (EWRPT_IS_POSTGRESQL) {
		if ($dateType == "y" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'YYYY')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'Q')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'MM')=LPAD('" . $val . "',2,'0') THEN 1 ELSE 0 END";
		} else {
			if ($val == "NULL") {
				return "CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END";
			} else {
				return "CASE WHEN " . $sFld . "=" . $qc . ewrpt_AdjustSql($val) . $qc . " THEN 1 ELSE 0 END";
			}
		}

	// Oracle
	} elseif (EWRPT_IS_ORACLE || EWRPT_IS_POSTGRESQL) {
		if ($dateType == "y" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'YYYY'),'" . $val . "',1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'Q'),'" . $val . "',1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'MM'),LPAD('" . $val . "',2,'0'),1,0)";
		} elseif ($dateType == "d") {
			return "DECODE(" . $sFld . ",TO_DATE(" . $qc . ewrpt_AdjustSql($val) . $qc . ",'YYYY/MM/DD'),1,0)";
		} elseif ($dateType == "dt") {
			return "DECODE(" . $sFld . ",TO_DATE(" . $qc . ewrpt_AdjustSql($val) . $qc . ",'YYYY/MM/DD HH24:MI:SS'),1,0)";
		} else {
			if ($val == "NULL") {
				return "(CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END)";
			} else {
				return "DECODE(" . $sFld . "," . $qc . ewrpt_AdjustSql($val) . $qc . ",1,0)";
			}
		}
	}
}

// Evaluate summary value
function ewrpt_SummaryValue($val1, $val2, $ityp) {
	switch ($ityp) {
	case "SUM":
	case "COUNT":
	case "AVG":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1;
		} else {
			return ($val1 + $val2);
		}
	case "MIN":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif (is_null($val1)) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 < $val2) {
			return $val1;
		} else {
			return $val2;
		}
	case "MAX":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif (is_null($val1)) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 > $val2) {
			return $val1;
		} else {
			return $val2;
		}
	}
}

// Match filter value
function ewrpt_MatchedFilterValue($ar, $value) {
	if (!is_array($ar)) {
		return (strval($ar) == strval($value));
	} else {
		foreach ($ar as $val) {
			if (strval($val) == strval($value))
				return TRUE;
		}
		return FALSE;
	}
}

// Render repeat column table
// rowcnt - zero based row count
function ewrpt_RepeatColumnTable($totcnt, $rowcnt, $repeatcnt, $rendertype) {
	$sWrk = "";
	if ($rendertype == 1) { // Render control start
		if ($rowcnt == 0) $sWrk .= "<table class=\"" . EWRPT_ITEM_TABLE_CLASSNAME . "\">";
		if ($rowcnt % $repeatcnt == 0) $sWrk .= "<tr>";
		$sWrk .= "<td>";
	} elseif ($rendertype == 2) { // Render control end
		$sWrk .= "</td>";
		if ($rowcnt % $repeatcnt == $repeatcnt - 1) {
			$sWrk .= "</tr>";
		} elseif ($rowcnt == $totcnt - 1) {
			for ($i = ($rowcnt % $repeatcnt) + 1; $i < $repeatcnt; $i++) {
				$sWrk .= "<td>&nbsp;</td>";
			}
			$sWrk .= "</tr>";
		}
		if ($rowcnt == $totcnt - 1) $sWrk .= "</table>";
	}
	return $sWrk;
}

// Check if the value is selected
function ewrpt_IsSelectedValue(&$ar, $value, $ft) {
	if (!is_array($ar))
		return TRUE;
	$af = (substr($value, 0, 2) == "@@");
	foreach ($ar as $val) {
		if ($af || substr($val, 0, 2) == "@@") { // Advanced filters
			if ($val == $value)
				return TRUE;
		} else {
			if (ewrpt_CompareValue($val, $value, $ft))
				return TRUE;
		}
	}
	return FALSE;
}

// Set up distinct values
// ar: array for distinct values
// val: value
// label: display value
// dup: check duplicate
function ewrpt_SetupDistinctValues(&$ar, $val, $label, $dup) {
	$isarray = is_array($ar);
	if ($dup && $isarray && in_array($val, array_keys($ar)))
		return;
	if (!$isarray) {
		$ar = array($val => $label);
	} elseif ($val == EWRPT_EMPTY_VALUE || $val == EWRPT_NULL_VALUE) { // Null/Empty
		$ar = array_reverse($ar, TRUE);
		$ar[$val] = $label; // Insert at top
		$ar = array_reverse($ar, TRUE);
	} else {
		$ar[$val] = $label; // Default insert at end
	}
}

// Compare values based on field type
function ewrpt_CompareValue($v1, $v2, $ft) {
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt
	case 20:
	case 3:
	case 2:
	case 16:
	case 17:
	case 18:
	case 19:
	case 21:
		if (is_numeric($v1) && is_numeric($v2)) {
			return (intval($v1) == intval($v2));
		}
		break;

	// Case adSingle, adDouble, adNumeric, adCurrency
	case 4:
	case 5:
	case 131:
	case 6:
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 == (float)$v2);
		}
		break;

	//	Case adDate, adDBDate, adDBTime, adDBTimeStamp
	case 7:
	case 133:
	case 134:
	case 135:
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) == strtotime($v2));
		}
		break;
	default:
		return (strcmp($v1, $v2) == 0); // treat as string
	}
}

// Register custom filter (retained for backward compatibility)
function ewrpt_RegisterCustomFilter(&$fld, $ID, $Name, $FunctionName) {
	ewrpt_RegisterFilter($fld, $ID, $Name, $FunctionName);
}

// Register filter
function ewrpt_RegisterFilter(&$fld, $ID, $Name, $FunctionName) {
	if (!is_array($fld->AdvancedFilters))
		$fld->AdvancedFilters = array();
	$wrkid = (substr($ID,0,2) == "@@") ? $ID : "@@" . $ID;
	$key = substr($wrkid,2);
	$fld->AdvancedFilters[$key] = new crAdvancedFilter($wrkid, $Name, $FunctionName);
}

function ewrpt_UnregisterFilter(&$fld, $ID) {
	if (is_array($fld->AdvancedFilters)) {
		$wrkid = (substr($ID,0,2) == "@@") ? $ID : "@@" . $ID;
		$key = substr($wrkid,2);
		foreach ($fld->AdvancedFilters as $filter) {
			if ($filter->ID == $wrkid) {
				unset($fld->AdvancedFilters[$key]);
				break;
			}
		}
	}
}

// Get custom filter
function ewrpt_GetCustomFilter(&$fld, $FldVal) {
	$sWrk = "";
	if (is_array($fld->AdvancedFilters)) {
		foreach ($fld->AdvancedFilters as $filter) {
			if ($filter->ID == $FldVal && $filter->Enabled) {
				$sFld = $fld->FldExpression;
				$sFn = $filter->FunctionName;
				$sWrk = $sFn($sFld);
				break;
			}
		}
	}
	return $sWrk;
}

// Return date value
function ewrpt_DateVal($FldOpr, $FldVal, $ValType) {

	// Compose date string
	switch (strtolower($FldOpr)) {
	case "year":
		if ($ValType == 1) {
			$wrkVal = "$FldVal-01-01";
		} elseif ($ValType == 2) {
			$wrkVal = "$FldVal-12-31";
		}
		break;
	case "quarter":
		list($y, $q) = explode("|", $FldVal);
		if (intval($y) == 0 || intval($q) == 0) {
			$wrkVal = "0000-00-00";
		} else {
			if ($ValType == 1) {
				$m = ($q - 1) * 3 + 1;
				$m = str_pad($m, 2, "0", STR_PAD_LEFT);
				$wrkVal = "$y-$m-01";
			} elseif ($ValType == 2) {
				$m = ($q - 1) * 3 + 3;
				$m = str_pad($m, 2, "0", STR_PAD_LEFT);
				$wrkVal = "$y-$m-" . ewrpt_DaysInMonth($y, $m);
			}
		}
		break;
	case "month":
		list($y, $m) = explode("|", $FldVal);
		if (intval($y) == 0 || intval($m) == 0) {
			$wrkVal = "0000-00-00";
		} else {
			if ($ValType == 1) {
				$m = str_pad($m, 2, "0", STR_PAD_LEFT);
				$wrkVal = "$y-$m-01";
			} elseif ($ValType == 2) {
				$m = str_pad($m, 2, "0", STR_PAD_LEFT);
				$wrkVal = "$y-$m-" . ewrpt_DaysInMonth($y, $m);
			}
		}
		break;
	case "day":
		$wrkVal = str_replace("|", "-", $FldVal);
	}

	// Add time if necessary
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2})/', $wrkVal)) { // date without time
		if ($ValType == 1) {
			$wrkVal .= " 00:00:00";
		} elseif ($ValType == 2) {
			$wrkVal .= " 23:59:59";
		}
	}

	// Check if datetime
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})/', $wrkVal)) { // datetime
		$DateVal = $wrkVal;
	} else {
		$DateVal = "";
	}
	return $DateVal;
}

// "Past"
function ewrpt_IsPast($FldExpression) {
	return ("($FldExpression < '" . date("Y-m-d H:i:s") . "')");
}

// "Future";
function ewrpt_IsFuture($FldExpression) {
	return ("($FldExpression > '" . date("Y-m-d H:i:s") . "')");
}

// "Last 30 days"
function ewrpt_IsLast30Days($FldExpression) {
	$dt1 = date("Y-m-d", strtotime("-29 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last 14 days"
function ewrpt_IsLast14Days($FldExpression) {
	$dt1 = date("Y-m-d", strtotime("-13 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last 7 days"
function ewrpt_IsLast7Days($FldExpression) {
	$dt1 = date("Y-m-d", strtotime("-6 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next 30 days"
function ewrpt_IsNext30Days($FldExpression) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+30 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next 14 days"
function ewrpt_IsNext14Days($FldExpression) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+14 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next 7 days"
function ewrpt_IsNext7Days($FldExpression) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+7 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Yesterday"
function ewrpt_IsYesterday($FldExpression) {
	$dt1 = date("Y-m-d", strtotime("-1 days"));
	$dt2 = date("Y-m-d");
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Today"
function ewrpt_IsToday($FldExpression) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Tomorrow"
function ewrpt_IsTomorrow($FldExpression) {
	$dt1 = date("Y-m-d", strtotime("+1 days"));
	$dt2 = date("Y-m-d", strtotime("+2 days"));
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last month"
function ewrpt_IsLastMonth($FldExpression) {
	$dt1 = date("Y-m", strtotime("-1 months")) . "-01";
	$dt2 = date("Y-m") . "-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "This month"
function ewrpt_IsThisMonth($FldExpression) {
	$dt1 = date("Y-m") . "-01";
	$dt2 = date("Y-m", strtotime("+1 months")) . "-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next month"
function ewrpt_IsNextMonth($FldExpression) {
	$dt1 = date("Y-m", strtotime("+1 months")) . "-01";
	$dt2 = date("Y-m", strtotime("+2 months")) . "-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last two weeks"
function ewrpt_IsLast2Weeks($FldExpression) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-14 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-14 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last week"
function ewrpt_IsLastWeek($FldExpression) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "This week"
function ewrpt_IsThisWeek($FldExpression) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days last Sunday"));
	}
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next week"
function ewrpt_IsNextWeek($FldExpression) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days last Sunday"));
	}
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next two week"
function ewrpt_IsNext2Weeks($FldExpression) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days last Sunday"));
	}
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Last year"
function ewrpt_IsLastYear($FldExpression) {
	$dt1 = date("Y", strtotime("-1 years")) . "-01-01";
	$dt2 = date("Y") . "-01-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "This year"
function ewrpt_IsThisYear($FldExpression) {
	$dt1 = date("Y") . "-01-01";
	$dt2 = date("Y", strtotime("+1 years")) . "-01-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next year"
function ewrpt_IsNextYear($FldExpression) {
	$dt1 = date("Y", strtotime("+1 years")) . "-01-01";
	$dt2 = date("Y", strtotime("+2 years")) . "-01-01";
	return ("($FldExpression >= '$dt1' AND $FldExpression < '$dt2')");
}

// "Next year"
function ewrpt_DaysInMonth($y, $m) {
	if (in_array($m, array(1, 3, 5, 7, 8, 10, 12))) {
		return 31;
	} elseif (in_array($m, array(4, 6, 9, 11))) {
		return 30;
	} elseif ($m == 2) {
		return ($y % 4 == 0) ? 29 : 28;
	}
	return 0;
}

// Function to calculate date difference
function ewrpt_DateDiff($dateTimeBegin, $dateTimeEnd, $interval = "d") {
	$dateTimeBegin = strtotime($dateTimeBegin);
	if ($dateTimeBegin === -1 || $dateTimeBegin === FALSE)
		return FALSE;
	$dateTimeEnd = strtotime($dateTimeEnd);
	if($dateTimeEnd === -1 || $dateTimeEnd === FALSE)
		return FALSE;
	$dif = $dateTimeEnd - $dateTimeBegin;	
	$arBegin = getdate($dateTimeBegin);
	$dateBegin = mktime(0, 0, 0, $arBegin["mon"], $arBegin["mday"], $arBegin["year"]);
	$arEnd = getdate($dateTimeEnd);
	$dateEnd = mktime(0, 0, 0, $arEnd["mon"], $arEnd["mday"], $arEnd["year"]);
	$difDate = $dateEnd - $dateBegin;
	switch ($interval) {
		case "s": // seconds
			return $dif;
		case "n": // minutes
			return ($dif > 0) ? floor($dif/60) : ceil($dif/60);
		case "h": // hours
			return ($dif > 0) ? floor($dif/3600) : ceil($dif/3600);
		case "d": // days
			return ($difDate > 0) ? floor($difDate/86400) : ceil($difDate/86400);
		case "w": // weeks
			return ($difDate > 0) ? floor($difDate/604800) : ceil($difDate/604800);
		case "ww": // calendar weeks
			$difWeek = (($dateEnd - $arEnd["wday"]*86400) - ($dateBegin - $arBegin["wday"]*86400))/604800;
			return ($difWeek > 0) ? floor($difWeek) : ceil($difWeek);
		case "m": // months
			return (($arEnd["year"]*12 + $arEnd["mon"]) -	($arBegin["year"]*12 + $arBegin["mon"]));
		case "yyyy": // years
			return ($arEnd["year"] - $arBegin["year"]);
	}
}

// Set up distinct values from ext. filter
function ewrpt_SetupDistinctValuesFromFilter(&$ar, $af) {
	if (is_array($af)) {
		foreach ($af as $filter) {
			if ($filter->Enabled)
				ewrpt_SetupDistinctValues($ar, $filter->ID, $filter->Name, FALSE);
		}
	}
}

// Get group value
// - Get the group value based on field type, group type and interval
// - ft: field type
// * 1: numeric, 2: date, 3: string
// - gt: group type
// * numeric: i = interval, n = normal
// * date: d = Day, w = Week, m = Month, q = Quarter, y = Year
// * string: f = first nth character, n = normal
// - intv: interval
function ewrpt_GroupValue(&$fld, $val) {
	$ft = $fld->FldType;
	$grp = $fld->FldGroupByType;
	$intv = $fld->FldGroupInt;
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		if (!is_numeric($val)) return $val;	
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return intval($val/$wrkIntv);
			default:
				return $val;
		}

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
//	case 7:
//	case 133:
//	case 134:
//	case 135:
	// Case adLongVarChar, adLongVarWChar, adChar, adWChar, adVarChar, adVarWChar (string)

	case 201: // string
	case 203:
	case 129:
	case 130:
	case 200:
	case 202:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 1;
		switch ($grp) {
			case "f":
				return substr($val, 0, $wrkIntv);
			default:
				return $val;
		}
	default:
		return $val; // ignore
	}
}

// Display group value
function ewrpt_DisplayGroupValue(&$fld, $val) {
	global $ReportLanguage;
	$ft = $fld->FldType;
	$grp = $fld->FldGroupByType;
	$intv = $fld->FldGroupInt;
	if (is_null($val)) return $ReportLanguage->Phrase("NullLabel");
	if ($val == "") return $ReportLanguage->Phrase("EmptyLabel");
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return strval($val*$wrkIntv) . " - " . strval(($val+1)*$wrkIntv-1);
			default:
				return $val;
		}
		break;

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
	case 7:
	case 133:
	case 134:
	case 135:
		$ar = explode("|", $val);
		switch ($grp) {
			Case "y":
				return $ar[0];
			Case "q":
				if (count($ar) < 2) return $val;
				return ewrpt_FormatQuarter($ar[0], $ar[1]);
			Case "m":
				if (count($ar) < 2) return $val;
				return ewrpt_FormatMonth($ar[0], $ar[1]);
			Case "w":
				if (count($ar) < 2) return $val;
				return ewrpt_FormatWeek($ar[0], $ar[1]);
			Case "d":
				if (count($ar) < 3) return $val;
				return ewrpt_FormatDay($ar[0], $ar[1], $ar[2]);
			Case "h":
				return ewrpt_FormatHour($ar[0]);
			Case "min":
				return ewrpt_FormatMinute($ar[0]);
			default:
				return $val;
		}
		break;
	default: // string and others
		return $val; // ignore
	}
}

function ewrpt_FormatQuarter($y, $q) {
	return "Q" . $q . "/" . $y;
}

function ewrpt_FormatMonth($y, $m) {
	return $m . "/" . $y;
}

function ewrpt_FormatWeek($y, $w) {
	return "WK" . $w . "/" . $y;
}

function ewrpt_FormatDay($y, $m, $d) {
	return $y . "-" . $m . "-" . $d;
}

function ewrpt_FormatHour($h) {
	if (intval($h) == 0) {
		return "12 AM";
	} elseif (intval($h) < 12) {
		return $h . " AM";
	} elseif (intval($h) == 12) {
		return "12 PM";
	} else {
		return ($h-12) . " PM";
	}
}

function ewrpt_FormatMinute($n) {
	return $n . " MIN";
}

// Get JavaScript data in the form of:
// [value1, text1, selected], [value2, text2, selected], ...
// where value1: "value 1", text1: "text 1": selected: true|false
function ewrpt_GetJsData(&$fld, $ft) {
	$jsdata = "";
	$arv = $fld->ValueList;
	$ars = $fld->SelectionList;
	if (is_array($arv)) {
		foreach ($arv as $key => $value) {
			$jsselect = (ewrpt_IsSelectedValue($ars, $key, $ft)) ? "true" : "false";
			if ($jsdata <> "") $jsdata .= ",";
			$jsdata .= "[\"" . ewrpt_EscapeJs($key) . "\",\"" . ewrpt_EscapeJs($value) . "\",$jsselect]";
		}
	}
	return $jsdata;
}

// Return detail filter SQL
function ewrpt_DetailFilterSQL(&$fld, $fn, $val) {
	$ft = $fld->FldDataType;
	if ($fld->FldGroupSql <> "") $ft = EWRPT_DATATYPE_STRING;
	$sqlwrk = $fn;
	if (is_null($val)) {
		$sqlwrk .= " IS NULL";
	} else {
		$sqlwrk .= " = " . ewrpt_QuotedValue($val, $ft);
	}
	return $sqlwrk;
}

// Return popup filter SQL
function ewrpt_FilterSQL(&$fld, $fn, $ft) {
	$ar = $fld->SelectionList;
	$af = $fld->AdvancedFilters;
	$gt = $fld->FldGroupByType;
	$gi = $fld->FldGroupInt;
	$sql = $fld->FldGroupSql;
	if (!is_array($ar)) {
		return TRUE;
	} else {
		$sqlwrk = "";
		$i = 0;
		foreach ($ar as $value) {
			if ($value == EWRPT_EMPTY_VALUE) { // Empty string
				$sqlwrk .= "$fn = '' OR ";
			} elseif ($value == EWRPT_NULL_VALUE) { // Null value
				$sqlwrk .= "$fn IS NULL OR ";
			} elseif (substr($value, 0, 2) == "@@") { // Advanced filter
				if (is_array($af)) {
					$afsql = ewrpt_AdvancedFilterSQL($af, $fn, $value); // Process popup filter
					if (!is_null($afsql))
						$sqlwrk .= $afsql . " OR ";
				}
			} elseif ($sql <> "") {
				$sqlwrk .= str_replace("%s", $fn, $sql) . " = '" . $value . "' OR ";
			} else {
				$sqlwrk .= "$fn IN (" . ewrpt_JoinArray($ar, ", ", $ft, $i) . ") OR ";
				break;
			}
			$i++;
		}
	}
	if ($sqlwrk != "")
		$sqlwrk = "(" . substr($sqlwrk, 0, -4) . ")";
	return $sqlwrk;
}

// Return Advanced Filter SQL
function ewrpt_AdvancedFilterSQL(&$af, $fn, $val) {
	if (!is_array($af)) {
		return NULL;
	} elseif (is_null($val)) {
		return NULL;
	} else {
		foreach ($af as $filter) {
			if (strval($val) == strval($filter->ID) && $filter->Enabled) {
				$func = $filter->FunctionName;
				return $func($fn);
			}
		}
		return NULL;
	}
}

// Truncate Memo Field based on specified length, string truncated to nearest space or CrLf
function ewrpt_TruncateMemo($memostr, $ln, $removehtml) {
	$str = ($removehtml) ? ewrpt_RemoveHtml($memostr) : $memostr;
	if (strlen($str) > 0 && strlen($str) > $ln) {
		$k = 0;
		while ($k >= 0 && $k < strlen($str)) {
			$i = strpos($str, " ", $k);
			$j = strpos($str, chr(10), $k);
			if ($i === FALSE && $j === FALSE) { // Not able to truncate
				return $str;
			} else {

				// Get nearest space or CrLf
				if ($i > 0 && $j > 0) {
					if ($i < $j) {
						$k = $i;
					} else {
						$k = $j;
					}
				} elseif ($i > 0) {
					$k = $i;
				} elseif ($j > 0) {
					$k = $j;
				}

				// Get truncated text
				if ($k >= $ln) {
					return substr($str, 0, $k) . "...";
				} else {
					$k++;
				}
			}
		}
	} else {
		return $str;
	}
}

// Remove HTML tags from text
function ewrpt_RemoveHtml($str) {
	return preg_replace('/<[^>]*>/', '', strval($str));
}

// Escape string for JavaScript
function ewrpt_EscapeJs($str) {
	$str = strval($str);
	$str = str_replace("\"", "\\\"", $str);
	$str = str_replace("\r", "\\r", $str);
	$str = str_replace("\n", "\\n", $str);
	return $str;
}

// Load Chart Series
function ewrpt_LoadChartSeries($sSql, &$cht) {
	global $conn;
	$rscht = $conn->Execute($sSql);
	$sdt = $cht->SeriesDateType;
	while ($rscht && !$rscht->EOF) {
		$cht->Series[] = ewrpt_ChartSeriesValue($rscht->fields[0], $sdt); // Series value
		$rscht->MoveNext();
	}
	if ($rscht) $rscht->Close();
}

// Load Chart Data
function ewrpt_LoadChartData($sSql, &$cht) {
	global $conn;
	$rscht = $conn->Execute($sSql);
	$sdt = $cht->SeriesDateType;
	$xdt = $cht->XAxisDateFormat;
	$ndt = ($cht->ChartType == 20) ? $cht->NameDateFormat : "";
	if ($sdt <> "") $xdt = $sdt;
	while ($rscht && !$rscht->EOF) {
		$temp = array();
		$temp[0] = ewrpt_ChartXValue($rscht->fields[0], $xdt); // X value

//echo "0: " . $rscht->fields[0] . "<br>";
		$temp[1] = ewrpt_ChartSeriesValue($rscht->fields[1], $sdt); // Series value
		for ($i=2; $i < $rscht->FieldCount(); $i++) {
			if ($ndt <> "" && $i == $rscht->FieldCount()-1)
				$temp[$i] = ewrpt_ChartXValue($rscht->fields[$i], $ndt); // Name value
			else
				$temp[$i] = $rscht->fields[$i]; // Y values
		}

//echo "1: " . $rscht->fields[1] . "<br>";
		$cht->Data[] = $temp;
		$rscht->MoveNext();
	}
	if ($rscht) $rscht->Close();
}

// Get Chart X value
function ewrpt_ChartXValue($val, $dt) {
	if (is_numeric($dt)) {
		return ewrpt_FormatDateTime($val, $dt);
	} elseif ($dt == "xyq") {
		$ar = explode("|", $val);
		if (count($ar) >= 2)
			return $ar[0] . " " . ewrpt_QuarterName($ar[1]);
		else
			return $val;
	}
	elseif ($dt == "xym") {
		$ar = explode("|", $val);
		if (count($ar) >= 2)
			return $ar[0] . " " . ewrpt_MonthName($ar[1]);
		else
			return $val;
	}
	elseif ($dt == "xq") {
		return ewrpt_QuarterName($val);
	}
	elseif ($dt == "xm") {
		return ewrpt_MonthName($val);
	}
	else {
		if (is_string($val))
			return trim($val);
		else
			return $val;
	}
}

// Get Chart Series value
function ewrpt_ChartSeriesValue($val, $dt) {
	if ($dt == "syq") {
		$ar = explode("|", $val);
		if (count($ar) >= 2)
			return $ar[0] . " " . ewrpt_QuarterName($ar[1]);
		else
			return $val;
	}
	elseif ($dt == "sym") {
		$ar = explode("|", $val);
		if (count($ar) >= 2)
			return $ar[0] . " " . ewrpt_MonthName($ar[1]);
		else
			return $val;
	}
	elseif ($dt == "sq") {
		return ewrpt_QuarterName($val);
	}
	elseif ($dt == "sm") {
		return ewrpt_MonthName($val);
	}
	else {
		if (is_string($val))
			return trim($val);
		else
			return $val;
	}
}

// Sort chart data
function ewrpt_SortChartData(&$ar, $opt, $seq="") {
	if ((($opt < 3 || $opt > 4) && $seq == "") || (($opt < 1 || $opt > 4) && $seq <> ""))
		return;
	if (is_array($ar)) {
		$cntar = count($ar);
		for ($i = 0; $i < $cntar; $i++) {
			for ($j = $i+1; $j < $cntar; $j++) {
				switch ($opt) {
					case 1: // X values ascending
						$bSwap = ewrpt_CompareValueCustom($ar[$i][0], $ar[$j][0], $seq);
						break;
					case 2: // X values descending
						$bSwap = ewrpt_CompareValueCustom($ar[$j][0], $ar[$i][0], $seq);
						break;
					case 3: // Y values ascending
						$bSwap = ewrpt_CompareValueCustom($ar[$i][2], $ar[$j][2], $seq);
						break;
					case 4: // Y values descending
						$bSwap = ewrpt_CompareValueCustom($ar[$j][2], $ar[$i][2], $seq);
				}
				if ($bSwap) {
					$tmpar = $ar[$i];
					$ar[$i] = $ar[$j];
					$ar[$j] = $tmpar;
				}
			}
		}
	}
}

// Sort chart multi series data
function ewrpt_SortMultiChartData(&$ar, $opt, $seq="") {
	if (!is_array($ar) || (($opt < 3 || $opt > 4) && $seq == "") || (($opt < 1 || $opt > 4) && $seq <> ""))
		return;

	// Obtain a list of columns
	foreach ($ar as $key => $row) {
		$xvalues[$key] = $row[0];
		$series[$key] = $row[1];
		$yvalues[$key] = $row[2];
		$ysums[$key] = $row[0]; // store the x-value for the time being
		if (isset($xsums[$row[0]])) {
			$xsums[$row[0]] += $row[2];
		} else {
			$xsums[$row[0]] = $row[2];
		}
	}

	// Set up Y sum
	if ($opt == 3 || $opt == 4) {
		$cnt = count($ysums);
		for ($i=0; $i<$cnt; $i++)
			$ysums[$i] = $xsums[$ysums[$i]];
	}

	// No specific sequence, use array_multisort
	if ($seq == "") {
		switch ($opt) {
			case 1: // X values ascending
				array_multisort($xvalues, SORT_ASC, $ar);
				break;
			case 2: // X values descending
				array_multisort($xvalues, SORT_DESC, $ar);
				break;
			case 3:
			case 4: // Y values
				if ($opt == 3) { // ascending
					array_multisort($ysums, SORT_ASC, $ar);
				} elseif ($opt == 4) { // descending
					array_multisort($ysums, SORT_DESC, $ar);
				}
		}

	// Handle specific sequence
	} else {

		// Build key list
		if ($opt == 1 || $opt == 2)
			$vals = array_unique($xvalues);
		else
			$vals = array_unique($ysums);
		foreach ($vals as $key => $val) {
			$keys[] = array($key, $val);
		}

		// Sort key list based on specific sequence
		$cntkey = count($keys);
		for ($i = 0; $i < $cntkey; $i++) {
			for ($j = $i+1; $j < $cntkey; $j++) {
				switch ($opt) {

					// Ascending
					case 1:
					case 3:
						$bSwap = ewrpt_CompareValueCustom($keys[$i][1], $keys[$j][1], $seq);
						break;

					// Descending
					case 2:
					case 4:
						$bSwap = ewrpt_CompareValueCustom($keys[$j][1], $keys[$i][1], $seq);
						break;
				}
				if ($bSwap) {
					$tmpkey = $keys[$i];
					$keys[$i] = $keys[$j];
					$keys[$j] = $tmpkey;
				}
			}
		}
		for ($i = 0; $i < $cntkey; $i++) {
			$xsorted[] = $xvalues[$keys[$i][0]];
		}

		// Sort array based on x sequence
		$arwrk = $ar;
		$rowcnt = 0;
		$cntx = intval(count($xsorted));
		for ($i = 0; $i < $cntx; $i++) {
			foreach ($arwrk as $key => $row) {
				if ($row[0] == $xsorted[$i]) {
					$ar[$rowcnt] = $row;
					$rowcnt++;
				}
			}
		}
	}
}

// Compare values by custom sequence
function ewrpt_CompareValueCustom($v1, $v2, $seq) {
	if ($seq == "_number") { // Number
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 > (float)$v2);
		}
	} else if ($seq == "_date") { // Date
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) > strtotime($v2));
		}
	} else if ($seq <> "") { // Custom sequence
		$ar = explode("|", $seq);
		if (in_array($v1, $ar) && in_array($v2, $ar))
			return (array_search($v1, $ar) > array_search($v2, $ar));
		else
			return in_array($v2, $ar);
	}
	return ($v1 > $v2);
}

// Load array from sql
function ewrpt_LoadArrayFromSql($sql, &$ar) {
	global $conn;
	if (strval($sql) == "")
		return;
	$rswrk = $conn->Execute($sql);
	if ($rswrk) {
		while (!$rswrk->EOF) {
			$v = $rswrk->fields[0];
			if (is_null($v)) {
				$v = EWRPT_NULL_VALUE;
			} elseif ($v == "") {
				$v = EWRPT_EMPTY_VALUE;
			}
			if (!is_array($ar))
				$ar = array();
			$ar[] = $v;
			$rswrk->MoveNext();
		}
		$rswrk->Close();
	}
}

// Function to Match array
function ewrpt_MatchedArray(&$ar1, &$ar2) {
	if (!is_array($ar1) && !is_array($ar2)) {
		return TRUE;
	} elseif (is_array($ar1) && is_array($ar2)) {
		return (count(array_diff($ar1, $ar2)) == 0);
	}
	return FALSE;
}

// Write a value to file for debug
function ewrpt_Trace($msg) {
	$filename = "debug.txt";
	if (!$handle = fopen($filename, 'a')) exit;
	if (is_writable($filename)) fwrite($handle, $msg . "\n");
	fclose($handle);
}

// Connection/Query error handler
function ewrpt_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
	if ($ErrorType == 'CONNECT') {
		$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
	} elseif ($ErrorType == 'EXECUTE') {
		$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
	} 
	$_SESSION[EWRPT_SESSION_MESSAGE] = $msg;
}

// Write HTTP header
function ewrpt_Header($cache, $charset = EWRPT_CHARSET) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
	$export = @$_GET["export"];
	if ($cache || !$cache && ewrpt_IsHttps() && $export <> "" && $export <> "print") { // Allow cache
		header("Cache-Control: private, must-revalidate"); // HTTP/1.1
	} else { // No cache
		header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache"); // HTTP/1.0
	}
	if ($charset <> "")
		header("Content-Type: text/html; charset=" . $charset); // Charset
}

// Connect to database
function &ewrpt_Connect() {
	$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
	$conn = new mysqlt_driver_ADOConnection();
	$conn->debug = EWRPT_DEBUG_ENABLED;

//	$conn->debug_echo = FALSE;
	$conn->debug_echo = EWRPT_DEBUG_ENABLED;
	$info = array("host" => EWRPT_CONN_HOST, "port" => EWRPT_CONN_PORT,
		"user" => EWRPT_CONN_USER, "pass" => EWRPT_CONN_PASS, "db" => EWRPT_CONN_DB);

	// Database connecting event
	Database_Connecting($info);
	$conn->port = intval($info["port"]);
	$conn->raiseErrorFn = 'ewrpt_ErrorFn';
	$conn->Connect($info["host"], $info["user"], $info["pass"], $info["db"]);
	if (EWRPT_MYSQL_CHARSET <> "")
		$conn->Execute("SET NAMES '" . EWRPT_MYSQL_CHARSET . "'");
	$conn->raiseErrorFn = '';

	// Database connected event
	Database_Connected($conn);
	return $conn;
}

// Database Connecting event
function Database_Connecting(&$info) {

	// Example:
	//var_dump($info);
	//if (ew_ServerVar("REMOTE_ADDR") == "127.0.0.1") { // testing on local PC
	//	$info["host"] = "locahost";
	//	$info["user"] = "root";
	//	$info["pass"] = "";
	//}

}

// Database Connected event
function Database_Connected(&$conn) {

	// Example:
	//$conn->Execute("Your SQL");

}

// Check if boolean value is TRUE
function ewrpt_ConvertToBool($value) {
	return ($value === TRUE || strval($value) == "1" ||
		strtolower(strval($value)) == "y" || strtolower(strval($value)) == "t");
}

// Check if HTTP POST
function ewrpt_IsHttpPost() {
	$ct = ewrpt_ServerVar("CONTENT_TYPE");
	if (empty($ct)) $ct = ewrpt_ServerVar("HTTP_CONTENT_TYPE");
	return ($ct == "application/x-www-form-urlencoded");
}

// Strip slashes
function ewrpt_StripSlashes($value) {
	if (!get_magic_quotes_gpc()) return $value;
	if (is_array($value)) { 
		return array_map('ewrpt_StripSlashes', $value);
	} else {
		return stripslashes($value);
	}
}

// Escape chars for XML
function ewrpt_XmlEncode($val) {
	return htmlspecialchars(strval($val));
}

// Output SCRIPT tag
function ewrpt_AddClientScript($src, $attrs = NULL) {
	$atts = array("type"=>"text/javascript", "src"=>$src);
	if (is_array($attrs))
		$atts = array_merge($atts, $attrs);
	echo ewrpt_HtmlElement("script", $atts, "") . "\n";
}

// Output LINK tag
function ewrpt_AddStylesheet($href, $attrs = NULL) {
	$atts = array("rel"=>"stylesheet", "type"=>"text/css", "href"=>$href);
	if (is_array($attrs))
		$atts = array_merge($atts, $attrs);
	echo ewrpt_HtmlElement("link", $atts, "", FALSE) . "\n";
}

// Build HTML element
function ewrpt_HtmlElement($tagname, $attrs, $innerhtml = "", $endtag = TRUE) {
	$html = "<" . $tagname;
	if (is_array($attrs)) {
		foreach ($attrs as $name => $attr) {
			if (strval($attr) <> "")
				$html .= " " . $name . "=\"" . ewrpt_HtmlEncode($attr) . "\"";
		}
	}
	$html .= ">";
	if (strval($innerhtml) <> "")
		$html .= $innerhtml;
	if ($endtag)
		$html .= "</" . $tagname . ">";
	return $html;
}

// Encode html
function ewrpt_HtmlEncode($exp) {
	return htmlspecialchars(strval($exp));
}

// View Option Separator
function ewrpt_ViewOptionSeparator($rowcnt) {
	return ", ";
}

// Functions for TEA encryption/decryption
function long2str($v, $w) {
	$len = count($v);
	$s = array();
	for ($i = 0; $i < $len; $i++)
	{
		$s[$i] = pack("V", $v[$i]);
	}
	if ($w) {
		return substr(join('', $s), 0, $v[$len - 1]);
	}	else {
		return join('', $s);
	}
}

function str2long($s, $w) {
	$v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
	$v = array_values($v);
	if ($w) {
		$v[count($v)] = strlen($s);
	}
	return $v;
}

function TEAencrypt($str, $key) {
	if ($str == "") {
		return "";
	}
	$v = str2long($str, true);
	$k = str2long($key, false);
	$cntk = count($k);
	if ($cntk < 4) {
		for ($i = $cntk; $i < 4; $i++) {
			$k[$i] = 0;
		}
	}
	$n = count($v) - 1;
	$z = $v[$n];
	$y = $v[0];
	$delta = 0x9E3779B9;
	$q = floor(6 + 52 / ($n + 1));
	$sum = 0;
	while (0 < $q--) {
		$sum = int32($sum + $delta);
		$e = $sum >> 2 & 3;
		for ($p = 0; $p < $n; $p++) {
			$y = $v[$p + 1];
			$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$z = $v[$p] = int32($v[$p] + $mx);
		}
		$y = $v[0];
		$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
		$z = $v[$n] = int32($v[$n] + $mx);
	}
	return ewrpt_UrlEncode(long2str($v, false));
}

function TEAdecrypt($str, $key) {
	$str = ewrpt_UrlDecode($str);
	if ($str == "") {
		return "";
	}
	$v = str2long($str, false);
	$k = str2long($key, false);
	$cntk = count($k);
	if ($cntk < 4) {
		for ($i = $cntk; $i < 4; $i++) {
			$k[$i] = 0;
		}
	}
	$n = count($v) - 1;
	$z = $v[$n];
	$y = $v[0];
	$delta = 0x9E3779B9;
	$q = floor(6 + 52 / ($n + 1));
	$sum = int32($q * $delta);
	while ($sum != 0) {
		$e = $sum >> 2 & 3;
		for ($p = $n; $p > 0; $p--) {
			$z = $v[$p - 1];
			$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$y = $v[$p] = int32($v[$p] - $mx);
		}
		$z = $v[$n];
		$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
		$y = $v[0] = int32($v[0] - $mx);
		$sum = int32($sum - $delta);
	}
	return long2str($v, true);
}

function int32($n) {
	while ($n >= 2147483648) $n -= 4294967296;
	while ($n <= -2147483649) $n += 4294967296;
	return (int)$n;
}

function ewrpt_UrlEncode($string) {
	$data = base64_encode($string);
	return str_replace(array('+','/','='), array('-','_','.'), $data);
}

function ewrpt_UrlDecode($string) {
	$data = str_replace(array('-','_','.'), array('+','/','='), $string);
	return base64_decode($data);
}

/**
 * Pager item class
 */

class crPagerItem {
	var $Start;
	var $Text;
	var $Enabled;
}

/**
 * Numeric pager class
 */

class crNumericPager {
	var $Items = array();
	var $Count, $FromIndex, $ToIndex, $RecordCount, $PageSize, $Range;
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $ButtonCount = 0;
	var $Visible = TRUE;

	function crNumericPager($StartRec, $DisplayRecs, $TotalRecs, $RecRange)
	{
		$this->FirstButton = new crPagerItem;
		$this->PrevButton = new crPagerItem;
		$this->NextButton = new crPagerItem;
		$this->LastButton = new crPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		$this->Range = intval($RecRange);
		if ($this->PageSize == 0) return;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// setup
		$this->SetupNumericPager();

		// update button count
		if ($this->FirstButton->Enabled) $this->ButtonCount++;
		if ($this->PrevButton->Enabled) $this->ButtonCount++;
		if ($this->NextButton->Enabled) $this->ButtonCount++;
		if ($this->LastButton->Enabled) $this->ButtonCount++;
		$this->ButtonCount += count($this->Items);
	}

	// Add pager item
	function AddPagerItem($StartIndex, $Text, $Enabled)
	{
		$Item = new crPagerItem;
		$Item->Start = $StartIndex;
		$Item->Text = $Text;
		$Item->Enabled = $Enabled;
		$this->Items[] = $Item;
	}

	// Setup pager items
	function SetupNumericPager()
	{
		if ($this->RecordCount > $this->PageSize) {
			$Eof = ($this->RecordCount < ($this->FromIndex + $this->PageSize));
			$HasPrev = ($this->FromIndex > 1);

			// First Button
			$TempIndex = 1;
			$this->FirstButton->Start = $TempIndex;
			$this->FirstButton->Enabled = ($this->FromIndex > $TempIndex);

			// Prev Button
			$TempIndex = $this->FromIndex - $this->PageSize;
			if ($TempIndex < 1) $TempIndex = 1;
			$this->PrevButton->Start = $TempIndex;
			$this->PrevButton->Enabled = $HasPrev;

			// Page links
			if ($HasPrev || !$Eof) {
				$x = 1;
				$y = 1;
				$dx1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->PageSize*$this->Range + 1;
				$dy1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->Range + 1;
				if (($dx1+$this->PageSize*$this->Range-1) > $this->RecordCount) {
					$dx2 = intval($this->RecordCount/$this->PageSize)*$this->PageSize + 1;
					$dy2 = intval($this->RecordCount/$this->PageSize) + 1;
				} else {
					$dx2 = $dx1 + $this->PageSize*$this->Range - 1;
					$dy2 = $dy1 + $this->Range - 1;
				}
				while ($x <= $this->RecordCount) {
					if ($x >= $dx1 && $x <= $dx2) {
						$this->AddPagerItem($x, $y, $this->FromIndex<>$x);
						$x += $this->PageSize;
						$y++;
					} elseif ($x >= ($dx1-$this->PageSize*$this->Range) && $x <= ($dx2+$this->PageSize*$this->Range)) {
						if ($x+$this->Range*$this->PageSize < $this->RecordCount) {
							$this->AddPagerItem($x, $y . "-" . ($y+$this->Range-1), TRUE);
						} else {
							$ny = intval(($this->RecordCount-1)/$this->PageSize) + 1;
							if ($ny == $y) {
								$this->AddPagerItem($x, $y, TRUE);
							} else {
								$this->AddPagerItem($x, $y . "-" . $ny, TRUE);
							}
						}
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					} else {
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					}
				}
			}

			// Next Button
			$TempIndex = $this->FromIndex + $this->PageSize;
			$this->NextButton->Start = $TempIndex;
			$this->NextButton->Enabled = !$Eof;

			// Last Button
			$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
			$this->LastButton->Start = $TempIndex;
			$this->LastButton->Enabled = ($this->FromIndex < $TempIndex);
		}
	}
}

/**
 * PrevNext pager class
 */

class crPrevNextPager {
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $CurrentPage, $PageCount, $FromIndex, $ToIndex, $RecordCount;
	var $Visible = TRUE;

	function crPrevNextPager($StartRec, $DisplayRecs, $TotalRecs)
	{
		$this->FirstButton = new crPagerItem;
		$this->PrevButton = new crPagerItem;
		$this->NextButton = new crPagerItem;
		$this->LastButton = new crPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		if ($this->PageSize == 0) return;
		$this->CurrentPage = intval(($this->FromIndex-1)/$this->PageSize) + 1;
		$this->PageCount = intval(($this->RecordCount-1)/$this->PageSize) + 1;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// First Button
		$TempIndex = 1;
		$this->FirstButton->Start = $TempIndex;
		$this->FirstButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Prev Button
		$TempIndex = $this->FromIndex - $this->PageSize;
		if ($TempIndex < 1) $TempIndex = 1;
		$this->PrevButton->Start = $TempIndex;
		$this->PrevButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Next Button
		$TempIndex = $this->FromIndex + $this->PageSize;
		if ($TempIndex > $this->RecordCount)
			$TempIndex = $this->FromIndex;
		$this->NextButton->Start = $TempIndex;
		$this->NextButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Last Button
		$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
		$this->LastButton->Start = $TempIndex;
		$this->LastButton->Enabled = ($TempIndex <> $this->FromIndex);
  }
}

/**
 * Email class
 */

class crEmail {

	// Class properties
	var $Sender = ""; // Sender
	var $Recipient = ""; // Recipient
	var $Cc = ""; // Cc
	var $Bcc = ""; // Bcc
	var $Subject = ""; // Subject
	var $Format = ""; // Format
	var $Content = ""; // Content
	var $AttachmentContent = ""; // Attachement content
	var $AttachmentFileName = ""; // Attachment file name
	var $Charset = ""; // Charset
	var $SendErrDescription; // Send error description

	// Method to load email from template
	function Load($fn) {
		$fn = ewrpt_ScriptFolder() . EWRPT_PATH_DELIMITER . $fn;
		$sWrk = file_get_contents($fn); // Load text file content
		if ($sWrk <> "") {

			// Locate Header & Mail Content
			if (EWRPT_IS_WINDOWS) {
				$i = strpos($sWrk, "\r\n\r\n");
			} else {
				$i = strpos($sWrk, "\n\n");
				if ($i === FALSE) $i = strpos($sWrk, "\r\n\r\n");
			}
			if ($i > 0) {
				$sHeader = substr($sWrk, 0, $i);
				$this->Content = trim(substr($sWrk, $i, strlen($sWrk)));
				if (EWRPT_IS_WINDOWS) {
					$arrHeader = explode("\r\n", $sHeader);
				} else {
					$arrHeader = explode("\n", $sHeader);
				}
				for ($j = 0; $j < count($arrHeader); $j++) {
					$i = strpos($arrHeader[$j], ":");
					if ($i > 0) {
						$sName = trim(substr($arrHeader[$j], 0, $i));
						$sValue = trim(substr($arrHeader[$j], $i+1, strlen($arrHeader[$j])));
						switch (strtolower($sName))
						{
							case "subject":
								$this->Subject = $sValue;
								break;
							case "from":
								$this->Sender = $sValue;
								break;
							case "to":
								$this->Recipient = $sValue;
								break;
							case "cc":
								$this->Cc = $sValue;
								break;
							case "bcc":
								$this->Bcc = $sValue;
								break;
							case "format":
								$this->Format = $sValue;
								break;
						}
					}
				}
			}
		}
	}

	// Method to replace sender
	function ReplaceSender($ASender) {
		$this->Sender = str_replace('<!--$From-->', $ASender, $this->Sender);
	}

	// Method to replace recipient
	function ReplaceRecipient($ARecipient) {
		$this->Recipient = str_replace('<!--$To-->', $ARecipient, $this->Recipient);
	}

	// Method to add Cc email
	function AddCc($ACc) {
		if ($ACc <> "") {
			if ($this->Cc <> "") $this->Cc .= ";";
			$this->Cc .= $ACc;
		}
	}

	// Method to add Bcc email
	function AddBcc($ABcc) {
		if ($ABcc <> "")  {
			if ($this->Bcc <> "") $this->Bcc .= ";";
			$this->Bcc .= $ABcc;
		}
	}

	// Method to replace subject
	function ReplaceSubject($ASubject) {
		$this->Subject = str_replace('<!--$Subject-->', $ASubject, $this->Subject);
	}

	// Method to replace content
	function ReplaceContent($Find, $ReplaceWith) {
		$this->Content = str_replace($Find, $ReplaceWith, $this->Content);
	}

	// Method to send email
	function Send() {
		global $gsEmailErrDesc;
		$result = ewrpt_SendEmail($this->Sender, $this->Recipient, $this->Cc, $this->Bcc,
			$this->Subject, $this->Content, $this->Format, $this->Charset, $this->AttachmentFileName, $this->AttachmentContent);
		$this->SendErrDescription = $gsEmailErrDesc;
		return $result;
	}
}

// Include PHPMailer class
include_once("phpmailer51/class.phpmailer.php");

// Function to send email
function ewrpt_SendEmail($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat, $sCharset, $sAttachmentFileName = "", $sAttachmentContent = "") {
	global $ReportLanguage, $gsEmailErrDesc;
	$res = FALSE;
	$mail = new PHPMailer();
	$mail->IsSMTP(); 
	$mail->Host = EWRPT_SMTP_SERVER;
	$mail->SMTPAuth = (EWRPT_SMTP_SERVER_USERNAME <> "" && EWRPT_SMTP_SERVER_PASSWORD <> "");
	$mail->Username = EWRPT_SMTP_SERVER_USERNAME;
	$mail->Password = EWRPT_SMTP_SERVER_PASSWORD;
	$mail->Port = EWRPT_SMTP_SERVER_PORT;
	$mail->From = $sFrEmail;
	$mail->FromName = $sFrEmail;
	$mail->Subject = $sSubject;
	$mail->Body = $sMail;
	if ($sCharset <> "" && strtolower($sCharset) <> "iso-8859-1")
		$mail->CharSet = $sCharset;
	$sToEmail = str_replace(";", ",", $sToEmail);
	$arrTo = explode(",", $sToEmail);
	foreach ($arrTo as $sTo) {
		$mail->AddAddress(trim($sTo));
	}
	if ($sCcEmail <> "") {
		$sCcEmail = str_replace(";", ",", $sCcEmail);
		$arrCc = explode(",", $sCcEmail);
		foreach ($arrCc as $sCc) {
			$mail->AddCC(trim($sCc));
		}
	}
	if ($sBccEmail <> "") {
		$sBccEmail = str_replace(";", ",", $sBccEmail);
		$arrBcc = explode(",", $sBccEmail);
		foreach ($arrBcc as $sBcc) {
			$mail->AddBCC(trim($sBcc));
		}
	}
	if (strtolower($sFormat) == "html") {
		$mail->ContentType = "text/html";
	} else {
		$mail->ContentType = "text/plain";
	}
	if ($sAttachmentContent <> "" && $sAttachmentFileName <> "") {
		$mail->AddStringAttachment($sAttachmentContent, $sAttachmentFileName);
	} else if ($sAttachmentFileName <> "") {
		$mail->AddAttachment($sAttachmentFileName);
	}
	$res = $mail->Send();
	$gsEmailErrDesc = $mail->ErrorInfo;

	// Uncomment to debug
//		var_dump($mail); exit();

	return $res;
}

// Load email count
function ewrpt_LoadEmailCount() {

	// Read from log
	if (EWRPT_EMAIL_WRITE_LOG) {
		$ip = ewrpt_ServerVar("REMOTE_ADDR");

		// Load from database
		if (EWRPT_EMAIL_WRITE_LOG_TO_DATABASE) {
			global $conn;
			$dt1 = date("Y-m-d H:i:s", strtotime("- " . EWRPT_MAX_EMAIL_SENT_PERIOD . "minute"));
			$dt2 = date("Y-m-d H:i:s");
			$sEmailSql = "SELECT COUNT(*) FROM " . ewrpt_QuotedName(EWRPT_EMAIL_LOG_TABLE_NAME) .
				" WHERE " . ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_DATETIME) .
				" BETWEEN " . ewrpt_QuotedValue($dt1, EWRPT_DATATYPE_DATE) . " AND " . ewrpt_QuotedValue($dt2, EWRPT_DATATYPE_DATE) .
				" AND " . ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_IP) . 
				" = " . ewrpt_QuotedValue($ip, EWRPT_DATATYPE_STRING);
			$rscnt = $conn->Execute($sEmailSql);
			if ($rscnt) {
				$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER] = ($rscnt->RecordCount()>1) ? $rscnt->RecordCount() : $rscnt->fields[0];
				$rscnt->Close();
			} else {
				$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER] = 0;
			}

		// Load from log file
		} else {
			$pfx = "email";
			$sTab = "\t";
			$sFolder = EWRPT_UPLOAD_DEST_PATH;
			$randomkey = TEAencrypt(date("Ymd"), EWRPT_RANDOM_KEY);
			$sFn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename = ewrpt_UploadPathEx(TRUE, $sFolder) . $sFn;
			if (file_exists($filename)) {
				$arLines = file($filename);
				$cnt = 0;
				foreach ($arLines as $line) {
					if ($line <> "") {
						list($dtwrk, $ipwrk, $senderwrk, $recipientwrk, $subjectwrk, $messagewrk) = explode($sTab, $line);
						$timediff = intval((strtotime("now") - strtotime($dtwrk,0))/60);
						if ($ipwrk == $ip && $timediff < EWRPT_MAX_EMAIL_SENT_PERIOD) $cnt++;
					}
				}
				$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER] = $cnt;
			} else {
				$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER] = 0;
			}
		}
	}
	if (!isset($_SESSION[EWRPT_EXPORT_EMAIL_COUNTER]))
		$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER] = 0;
	return intval($_SESSION[EWRPT_EXPORT_EMAIL_COUNTER]);
}

// Add email log
function ewrpt_AddEmailLog($sender, $recipient, $subject, $message) {
	$_SESSION[EWRPT_EXPORT_EMAIL_COUNTER]++;

	// Save to email log
	if (EWRPT_EMAIL_WRITE_LOG) {
		$dt = date("Y-m-d H:i:s");
		$ip = ewrpt_ServerVar("REMOTE_ADDR");
		$senderwrk = ewrpt_TruncateText($sender);
		$recipientwrk = ewrpt_TruncateText($recipient);
		$subjectwrk = ewrpt_TruncateText($subject);
		$messagewrk = ewrpt_TruncateText($message);

		// Save to database
		if (EWRPT_EMAIL_WRITE_LOG_TO_DATABASE) {
			global $conn;
			$sEmailSql = "INSERT INTO " . ewrpt_QuotedName(EWRPT_EMAIL_LOG_TABLE_NAME) .
				" (" . ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_DATETIME) . ", " .
				ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_IP) . ", " .
				ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_SENDER) . ", " .
				ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_RECIPIENT) . ", " .
				ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_SUBJECT) . ", " .
				ewrpt_QuotedName(EWRPT_EMAIL_LOG_FIELD_NAME_MESSAGE) . ") VALUES (" .
				ewrpt_QuotedValue($dt, EWRPT_DATATYPE_DATE) . ", " .
				ewrpt_QuotedValue($ip, EWRPT_DATATYPE_STRING) . ", " .
				ewrpt_QuotedValue($senderwrk, EWRPT_DATATYPE_STRING) . ", " .
				ewrpt_QuotedValue($recipientwrk, EWRPT_DATATYPE_STRING) . ", " .
				ewrpt_QuotedValue($subjectwrk, EWRPT_DATATYPE_STRING) . ", " .
				ewrpt_QuotedValue($messagewrk, EWRPT_DATATYPE_STRING) . ")";
			$conn->Execute($sEmailSql);

		// Save to log file
		} else {
			$pfx = "email";
			$sTab = "\t";
			$sHeader = "date/time" . $sTab . "ip" . $sTab . "sender" . $sTab . "recipient" . $sTab . "subject" . $sTab . "message";
			$sMsg = $dt . $sTab . $ip . $sTab . $senderwrk . $sTab . $recipientwrk . $sTab . $subjectwrk . $sTab . $messagewrk;
			$sFolder = EWRPT_UPLOAD_DEST_PATH;
			$randomkey = TEAencrypt(date("Ymd"), EWRPT_RANDOM_KEY);
			$sFn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename = ewrpt_UploadPathEx(TRUE, $sFolder) . $sFn;
			if (file_exists($filename)) {
				$fileHandler = fopen($filename, "a+b");
			} else {
				$fileHandler = fopen($filename, "a+b");
				fwrite($fileHandler,$sHeader."\r\n");
			}
			fwrite($fileHandler, $sMsg."\r\n");
			fclose($fileHandler);
		}
	}
}

function ewrpt_TruncateText($v) {
	$maxlen = EWRPT_EMAIL_LOG_SIZE_LIMIT;
	$v = str_replace("\r\n", " ", $v);
	$v = str_replace("\t", " ", $v);
	if (strlen($v) > $maxlen)
		$v = substr($v, 0, $maxlen-3) . "...";
	return $v;
}

// Read global debug message
function ewrpt_DebugMsg() {
	global $gsDebugMsg;
	return ($gsDebugMsg <> "") ? "<p>" . $gsDebugMsg . "</p>" : "";
}

// Write global debug message
function ewrpt_SetDebugMsg($v, $newline = TRUE) {
	global $gsDebugMsg;
	if ($newline && $gsDebugMsg <> "")
		$gsDebugMsg .= "<br>";
	$gsDebugMsg .=  $v;
}

/**
 * Functions for converting encoding
 */

function ewrpt_ConvertToUtf8($str) {
	return ewrpt_Convert(EWRPT_ENCODING, "UTF-8", $str);
}

function ewrpt_ConvertFromUtf8($str) {
	return ewrpt_Convert("UTF-8", EWRPT_ENCODING, $str);
}

function ewrpt_Convert($from, $to, $str) {
	if ($from != "" && $to != "" && strtoupper($from) != strtoupper($to)) {
		if (function_exists("iconv")) {
			return iconv($from, $to, $str);
		} elseif (function_exists("mb_convert_encoding")) {
			return mb_convert_encoding($str, $to, $from);
		} else {
			return $str;
		}
	} else {
		return $str;
	}
}

// Encode value for single-quoted JavaScript string
function ewrpt_JsEncode($val) {
	$val = str_replace("\\", "\\\\", strval($val));
	$val = str_replace("'", "\\'", $val);
	$val = str_replace("\r\n", "<br>", $val);
	$val = str_replace("\r", "<br>", $val);
	$val = str_replace("\n", "<br>", $val);
	return $val;
}

// Encode value for double-quoted Javascript string
function ewrpt_JsEncode2($val) {
	$val = str_replace("\\", "\\\\", strval($val));
	$val = str_replace("\"", "\\\"", $val);
	$val = str_replace("\r\n", "<br>", $val);
	$val = str_replace("\r", "<br>", $val);
	$val = str_replace("\n", "<br>", $val);
	return $val;
}

// Get current page name
function ewrpt_CurrentPage() {
	return ewrpt_GetPageName(ewrpt_ScriptName());
}

// Get page name
function ewrpt_GetPageName($url) {
	$PageName = "";
	if ($url <> "") {
		$PageName = $url;
		$p = strpos($PageName, "?");
		if ($p !== FALSE)
			$PageName = substr($PageName, 0, $p); // Remove QueryString
		$p = strrpos($PageName, "/");
		if ($p !== FALSE)
			$PageName = substr($PageName, $p+1); // Remove path
	}
	return $PageName;
}

// Adjust text for caption
function ewrpt_BtnCaption($Caption) {
	$Min = 10;
	if (strlen($Caption) < $Min) {
		$Pad = abs(intval(($Min - strlen($Caption))/2*-1));
		$Caption = str_repeat(" ", $Pad) . $Caption . str_repeat(" ", $Pad);
	}
	return $Caption;
}

// Get script name (function name is prefix with "ew_" only for compatibility with PHPMaker)
function ew_ScriptName() {
	$sn = ewrpt_ServerVar("PHP_SELF");
	if (empty($sn)) $sn = ewrpt_ServerVar("SCRIPT_NAME");
	if (empty($sn)) $sn = ewrpt_ServerVar("ORIG_PATH_INFO");
	if (empty($sn)) $sn = ewrpt_ServerVar("ORIG_SCRIPT_NAME");
	if (empty($sn)) $sn = ewrpt_ServerVar("REQUEST_URI");
	if (empty($sn)) $sn = ewrpt_ServerVar("URL");
	if (empty($sn)) $sn = "UNKNOWN";
	return $sn;
}

// Get server variable by name
function ewrpt_ServerVar($Name) {
	$str = @$_SERVER[$Name];
	if (empty($str)) $str = @$_ENV[$Name];
	return $str;
}

// YUI files host
function ewrpt_YuiHost() {

	// Use files online
	if (ewrpt_IsHttps()) {
		return "https://ajax.googleapis.com/ajax/libs/yui/2.9.0/";
	} else {
		return "http://yui.yahooapis.com/2.9.0/";
	}
}

// jQuery files host
function ewrpt_jQueryHost() {

	// Use files online
	if (ewrpt_IsHttps()) {
		return "https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/";
	} else {
		return "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/";
	}
}

// Check if HTTPS
function ewrpt_IsHttps() {
	return (ewrpt_ServerVar("HTTPS") <> "" && ewrpt_ServerVar("HTTPS") <> "off");
}

// Encrypt password
function ewrpt_EncryptPassword($input, $salt = '') {
	return (strval($salt) <> "") ? md5($input . $salt) . ":" . $salt : md5($input);
}

// Compare password
// Note: If salted, password must be stored in '<hashedstring>:<salt>' format
function ewrpt_ComparePassword($pwd, $input) {
	@list($crypt, $salt) = explode(":", $pwd, 2);
	if (EWRPT_CASE_SENSITIVE_PASSWORD) {
		if (EWRPT_ENCRYPTED_PASSWORD) {
			return ($pwd == ewrpt_EncryptPassword($input, @$salt));
		} else {
			return ($pwd == $input);
		}
	} else {
		if (EWRPT_ENCRYPTED_PASSWORD) {
			return ($pwd == ewrpt_EncryptPassword(strtolower($input), @$salt));
		} else {
			return (strtolower($pwd) == strtolower($input));
		}
	}
}

// Get domain URL
function ewrpt_DomainUrl() {
	$sUrl = "http";
	$bSSL = (ewrpt_ServerVar("HTTPS") <> "" && ewrpt_ServerVar("HTTPS") <> "off");
	$sPort = strval(ewrpt_ServerVar("SERVER_PORT"));
	$defPort = ($bSSL) ? "443" : "80";
	$sPort = ($sPort == $defPort) ? "" : ":$sPort";
	$sUrl .= ($bSSL) ? "s" : "";
	$sUrl .= "://";
	$sUrl .= ewrpt_ServerVar("SERVER_NAME") . $sPort;
	return $sUrl;
}

// Get full URL
function ewrpt_FullUrl() {
	return ewrpt_DomainUrl() . ewrpt_ScriptName();
}

// Get current URL
function ewrpt_CurrentUrl() {
	$s = ewrpt_ScriptName();
	$q = ewrpt_ServerVar("QUERY_STRING");
	if ($q <> "") $s .= "?" . $q;
	return $s;
}

// Convert to full URL
function ewrpt_ConvertFullUrl($url) {
	if ($url == "") return "";
	if (strpos($url, "://") === FALSE && strpos($url, "\\") === FALSE) {
		$sUrl = ewrpt_FullUrl();
		return substr($sUrl, 0, strrpos($sUrl, "/")+1) . $url;
	} else {
		return $url;
	}
}

// Get script name
function ewrpt_ScriptName() {
	$sn = ewrpt_ServerVar("PHP_SELF");
	if (empty($sn)) $sn = ewrpt_ServerVar("SCRIPT_NAME");
	if (empty($sn)) $sn = ewrpt_ServerVar("ORIG_PATH_INFO");
	if (empty($sn)) $sn = ewrpt_ServerVar("ORIG_SCRIPT_NAME");
	if (empty($sn)) $sn = ewrpt_ServerVar("REQUEST_URI");
	if (empty($sn)) $sn = ewrpt_ServerVar("URL");
	if (empty($sn)) $sn = "UNKNOWN";
	return $sn;
}

// Remove XSS
function ewrpt_RemoveXSS($val) {

	// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	// this prevents some character re-spacing such as <java\0script>
	// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs

	$val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

	// straight replacements, the user should never need these since they're normal characters
	// this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>

	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	for ($i = 0; $i < strlen($search); $i++) {

		// ;? matches the ;, which is optional
		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		// &#x0040 @ search for the hex values

		$val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;

		// &#00064 @ 0{0,7} matches '0' zero to seven times
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	}

	// now the only remaining whitespace attacks are \t, \n, and \r 
	$ra = $GLOBALS["EWRPT_XSS_ARRAY"]; // Note: Customize $EWRPT_XSS_ARRAY in ewrcfg*.php
	$found = true; // keep replacing as long as the previous round replaced something
	while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) { 
				if ($j > 0) {
					$pattern .= '('; 
					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?'; 
					$pattern .= '|(&#0{0,8}([9][10][13]);?)?'; 
					$pattern .= ')?'; 
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
			$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			if ($val_before == $val) {

				// no replacements were made, so exit the loop
				$found = false;
			}
		}
	}
	return $val;
}

// Load selection from a filter clause
function ewrpt_LoadSelectionFromFilter(&$fld, $filter, &$sel) {
	$sel = "";
	if ($filter <> "") {
		$sSql = ewrpt_BuildReportSql($fld->SqlSelect, "", "", "", $fld->SqlOrderBy, $filter, "");
		ewrpt_LoadArrayFromSql($sSql, $sel);
	}
}

// Build dropdown filter
function ewrpt_BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr) {
	$FldVal = $fld->DropDownValue;
	$sSql = "";
	if (is_array($FldVal)) {
		foreach ($FldVal as $val) {
			$sWrk = ewrpt_GetDropDownfilter($fld, $val, $FldOpr);
			if ($sWrk <> "") {
				if ($sSql <> "")
					$sSql .= " OR " . $sWrk;
				else
					$sSql = $sWrk;
			}
		}
	} else {
		$sSql = ewrpt_GetDropDownfilter($fld, $FldVal, $FldOpr);
	}
	if ($sSql <> "") {
		if ($FilterClause <> "") $FilterClause = "(" . $FilterClause . ") AND ";
		$FilterClause .= "(" . $sSql . ")";
	}
}

function ewrpt_GetDropDownfilter(&$fld, $FldVal, $FldOpr) {
	$FldName = $fld->FldName;
	$FldExpression = $fld->FldExpression;
	$FldDataType = $fld->FldDataType;
	$sWrk = "";
	if ($FldVal == EWRPT_NULL_VALUE) {
		$sWrk = $FldExpression . " IS NULL";
	} elseif ($FldVal == EWRPT_NOT_NULL_VALUE) {
		$sWrk = $FldExpression . " IS NOT NULL";
	} elseif ($FldVal == EWRPT_EMPTY_VALUE) {
		$sWrk = $FldExpression . " = ''";
	} else {
		if (substr($FldVal, 0, 2) == "@@") {
			$sWrk = ewrpt_GetCustomFilter($fld, $FldVal);
		} else {
			if ($FldVal <> "" && $FldVal <> EWRPT_INIT_VALUE && $FldVal <> EWRPT_ALL_VALUE) {
				if ($FldDataType == EWRPT_DATATYPE_DATE && $FldOpr <> "") {
					$sWrk = ewrpt_DateFilterString($FldOpr, $FldVal, $FldDataType);
				} else {
					$sWrk = ewrpt_FilterString("=", $FldVal, $FldDataType);
				}
			}
			if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
		}
	}
	return $sWrk;
}

// Get extended filter
function ewrpt_BuildExtendedFilter(&$fld, &$FilterClause) {
	$FldName = $fld->FldName;
	$FldExpression = $fld->FldExpression;
	$FldDataType = $fld->FldDataType;
	$FldDateTimeFormat = $fld->FldDateTimeFormat;
	$FldVal1 = $fld->SearchValue;
	$FldOpr1 = $fld->SearchOperator;
	$FldCond = $fld->SearchCondition;
	$FldVal2 = $fld->SearchValue2;
	$FldOpr2 = $fld->SearchOperator2;
	$sWrk = "";
	$FldOpr1 = strtoupper(trim($FldOpr1));
	if ($FldOpr1 == "") $FldOpr1 = "=";
	$FldOpr2 = strtoupper(trim($FldOpr2));
	if ($FldOpr2 == "") $FldOpr2 = "=";
	$wrkFldVal1 = $FldVal1;
	$wrkFldVal2 = $FldVal2;
	if ($FldDataType == EWRPT_DATATYPE_BOOLEAN) {
		if (EWRPT_IS_MSACCESS) {
			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "True" : "False";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "True" : "False";
		} else {

			//if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;
			//if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;

			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "1" : "0";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "1" : "0";
		}
	} elseif ($FldDataType == EWRPT_DATATYPE_DATE) {
		if ($wrkFldVal1 <> "") $wrkFldVal1 = ewrpt_UnFormatDateTime($wrkFldVal1, $FldDateTimeFormat);
		if ($wrkFldVal2 <> "") $wrkFldVal2 = ewrpt_UnFormatDateTime($wrkFldVal2, $FldDateTimeFormat);
	}
	if ($FldOpr1 == "BETWEEN") {
		$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
			($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1) && is_numeric($wrkFldVal2)));
		if ($wrkFldVal1 <> "" && $wrkFldVal2 <> "" && $IsValidValue)
			$sWrk = $FldExpression . " BETWEEN " . ewrpt_QuotedValue($wrkFldVal1, $FldDataType) .
				" AND " . ewrpt_QuotedValue($wrkFldVal2, $FldDataType);
	} elseif ($FldVal1 == EWRPT_NULL_VALUE || $FldOpr1 == "IS NULL") {
        $sWrk = $FldExpression . " IS NULL";
    } elseif ($FldVal1 == EWRPT_NOT_NULL_VALUE || $FldOpr1 == "IS NOT NULL") {
        $sWrk = $FldExpression . " IS NOT NULL";
	} else {
		$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
			($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1)));
		if ($wrkFldVal1 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr1, $FldDataType))
			$sWrk = $FldExpression . ewrpt_FilterString($FldOpr1, $wrkFldVal1, $FldDataType);
		$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
			($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal2)));
		if ($wrkFldVal2 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr2, $FldDataType)) {
			if ($sWrk <> "")
				$sWrk .= " " . (($FldCond == "OR") ? "OR" : "AND") . " ";
			$sWrk .= $FldExpression . ewrpt_FilterString($FldOpr2, $wrkFldVal2, $FldDataType);
		}
	}
	if ($sWrk <> "") {
		if ($FilterClause <> "") $FilterClause .= " AND ";
		$FilterClause .= "(" . $sWrk . ")";
	}
}

// Return filter string
function ewrpt_FilterString($FldOpr, $FldVal, $FldType) {
	if ($FldOpr == "LIKE" || $FldOpr == "NOT LIKE") {
		return " " . $FldOpr . " " . ewrpt_QuotedValue("%$FldVal%", $FldType);
	} elseif ($FldOpr == "STARTS WITH") {
		return " LIKE " . ewrpt_QuotedValue("$FldVal%", $FldType);
	} else {
		return " $FldOpr " . ewrpt_QuotedValue($FldVal, $FldType);
	}
}

// Return date search string
function ewrpt_DateFilterString($FldOpr, $FldVal, $FldType) {
	$wrkVal1 = ewrpt_DateVal($FldOpr, $FldVal, 1);
	$wrkVal2 = ewrpt_DateVal($FldOpr, $FldVal, 2);
	if ($wrkVal1 <> "" && $wrkVal2 <> "") {
		return " BETWEEN " . ewrpt_QuotedValue($wrkVal1, $FldType) . " AND " . ewrpt_QuotedValue($wrkVal2, $FldType);
	} else {
		return "";
	}
}

/**
 * Validation functions
 */

// Check date format
// format: std/us/euro
function ewrpt_CheckDateEx($value, $format, $sep) {
	if (strval($value) == "")	return TRUE;
	while (strpos($value, "  ") !== FALSE)
		$value = str_replace("  ", " ", $value);
	$value = trim($value);
	$arDT = explode(" ", $value);
	if (count($arDT) > 0) {
		$wrksep = "\\$sep";
		switch ($format) {
			case "std":
				$pattern = '/^([0-9]{4})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
				break;
			case "stdshort":
				$pattern = '/^([0-9]{2})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
				break;
			case "us":
				$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{4})$/';
				break;
			case "usshort":
				$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{2})$/';
				break;
			case "euro":
				$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{4})$/';
				break;
			case "euroshort":
				$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{2})$/';
				break;
		}
		if (!preg_match($pattern, $arDT[0])) return FALSE;
		$arD = explode(EWRPT_DATE_SEPARATOR, $arDT[0]);
		switch ($format) {
			case "std":
			case "stdshort":
				$sYear = ewrpt_UnformatYear($arD[0]);
				$sMonth = $arD[1];
				$sDay = $arD[2];
				break;
			case "us":
			case "usshort":
				$sYear = ewrpt_UnformatYear($arD[2]);
				$sMonth = $arD[0];
				$sDay = $arD[1];
				break;
			case "euro":
			case "euroshort":
				$sYear = ewrpt_UnformatYear($arD[2]);
				$sMonth = $arD[1];
				$sDay = $arD[0];
				break;
		}
		if (!ewrpt_CheckDay($sYear, $sMonth, $sDay)) return FALSE;
	}
	if (count($arDT) > 1 && !ewrpt_CheckTime($arDT[1])) return FALSE;
	return TRUE;
}

// Unformat 2 digit year to 4 digit year
function ewrpt_UnformatYear($yr) {
	if (strlen($yr) == 2) {
		if ($yr > EWRPT_UNFORMAT_YEAR)
			return "19" . $yr;
		else
			return "20" . $yr;
	} else {
		return $yr;
	}
}

// Check Date format (yyyy/mm/dd)
function ewrpt_CheckDate($value) {
	return ewrpt_CheckDateEx($value, "std", EWRPT_DATE_SEPARATOR);
}

// Check Date format (yy/mm/dd)
function ewrpt_CheckShortDate($value) {
	return ewrpt_CheckDateEx($value, "stdshort", EWRPT_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yyyy)
function ewrpt_CheckUSDate($value) {
	return ewrpt_CheckDateEx($value, "us", EWRPT_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yy)
function ewrpt_CheckShortUSDate($value) {
	return ewrpt_CheckDateEx($value, "usshort", EWRPT_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yyyy)
function ewrpt_CheckEuroDate($value) {
	return ewrpt_CheckDateEx($value, "euro", EWRPT_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yy)
function ewrpt_CheckShortEuroDate($value) {
	return ewrpt_CheckDateEx($value, "euroshort", EWRPT_DATE_SEPARATOR);
}

// Check day
function ewrpt_CheckDay($checkYear, $checkMonth, $checkDay) {
	$maxDay = 31;
	if ($checkMonth == 4 || $checkMonth == 6 ||	$checkMonth == 9 || $checkMonth == 11) {
		$maxDay = 30;
	} elseif ($checkMonth == 2)	{
		if ($checkYear % 4 > 0) {
			$maxDay = 28;
		} elseif ($checkYear % 100 == 0 && $checkYear % 400 > 0) {
			$maxDay = 28;
		} else {
			$maxDay = 29;
		}
	}
	return ewrpt_CheckRange($checkDay, 1, $maxDay);
}

// Check integer
function ewrpt_CheckInteger($value) {
	if (strval($value) == "")	return TRUE;
	return preg_match('/^\-?\+?[0-9]+$/', $value);
}

// Check number range
function ewrpt_NumberRange($value, $min, $max) {
	if ((!is_null($min) && $value < $min) ||
		(!is_null($max) && $value > $max))
		return FALSE;
	return TRUE;
}

// Check number
function ewrpt_CheckNumber($value) {
	if (strval($value) == "")	return TRUE;
	return is_numeric(trim($value));
}

// Check range
function ewrpt_CheckRange($value, $min, $max) {
	if (strval($value) == "")	return TRUE;
	if (!ewrpt_CheckNumber($value)) return FALSE;
	return ewrpt_NumberRange($value, $min, $max);
}

// Check time
function ewrpt_CheckTime($value) {
	if (strval($value) == "")	return TRUE;
	return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $value);
}

// Check US phone number
function ewrpt_CheckPhone($value) {
	if (strval($value) == "")	return TRUE;
	return preg_match('/^\(\d{3}\) ?\d{3}( |-)?\d{4}|^\d{3}( |-)?\d{3}( |-)?\d{4}$/', $value);
}

// Check US zip code
function ewrpt_CheckZip($value) {
	if (strval($value) == "")	return TRUE;
	return preg_match('/^\d{5}$|^\d{5}-\d{4}$/', $value);
}

// Check credit card
function ewrpt_CheckCreditCard($value, $type="") {
	if (strval($value) == "")	return TRUE;
	$creditcard = array("visa" => "/^4\d{3}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"mastercard" => "/^5[1-5]\d{2}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"discover" => "/^6011[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"amex" => "/^3[4,7]\d{13}$/",
		"diners" => "/^3[0,6,8]\d{12}$/",
		"bankcard" => "/^5610[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"jcb" => "/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
		"enroute" => "/^[2014|2149]\d{11}$/",
		"switch" => "/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
	if (empty($type))	{
		$match = FALSE;
		foreach ($creditcard as $type => $pattern) {
			if (@preg_match($pattern, $value) == 1) {
				$match = TRUE;
				break;
			}
		}
		return ($match) ? ewrpt_CheckSum($value) : FALSE;
	}	else {
		if (!preg_match($creditcard[strtolower(trim($type))], $value)) return FALSE;
		return ewrpt_CheckSum($value);
	}
}

// Check sum
function ewrpt_CheckSum($value) {
	$value = str_replace(array('-',' '), array('',''), $value);
	$checksum = 0;
	for ($i=(2-(strlen($value) % 2)); $i<=strlen($value); $i+=2)
		$checksum += (int)($value[$i-1]);
  for ($i=(strlen($value)%2)+1; $i <strlen($value); $i+=2) {
	  $digit = (int)($value[$i-1]) * 2;
		$checksum += ($digit < 10) ? $digit : ($digit-9);
  }
	return ($checksum % 10 == 0);
}

// Check US social security number
function ewrpt_CheckSSC($value) {
	if (strval($value) == "")	return TRUE;
	return preg_match('/^(?!000)([0-6]\d{2}|7([0-6]\d|7[012]))([ -]?)(?!00)\d\d\3(?!0000)\d{4}$/', $value);
}

// Check emails
function ewrpt_CheckEmailList($value, $email_cnt) {
	if (strval($value) == "")	return TRUE;
	$emailList = str_replace(",", ";", $value);
	$arEmails = explode(";", $emailList);
	$cnt = count($arEmails);
	if ($cnt > $email_cnt && $email_cnt > 0)
		return FALSE;
	foreach ($arEmails as $email) {
		if (!ewrpt_CheckEmail($email))
			return FALSE;
	}
	return TRUE;
}

// Check email
function ewrpt_CheckEmail($value) {
	if (strval($value) == "")	return TRUE;

	//return preg_match('/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/', trim($value));
	return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i', trim($value));
}

// Check GUID
function ewrpt_CheckGUID($value) {
	if (strval($value) == "")	return TRUE;
	$p1 = '/^{{1}([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}}{1}$/';
	$p2 = '/^([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}$/';
	return preg_match($p1, $value) || preg_match($p2, $value);
}

// Check by preg
function ewrpt_CheckByRegEx($value, $pattern) {
	if (strval($value) == "") return TRUE;
	return preg_match($pattern, $value);
}

/**
 * End Validation functions
 */

// Upload path
// If PhyPath is TRUE(1), return physical path on the server
// If PhyPath is FALSE(0), return relative URL
function ewrpt_UploadPathEx($PhyPath, $DestPath) {
	if ($PhyPath) {
		$Path = ewrpt_PathCombine(ewrpt_AppRoot(), str_replace("/", EWRPT_PATH_DELIMITER, $DestPath), TRUE);
	} else {
		$Path = EWRPT_ROOT_RELATIVE_PATH;
		$Path = str_replace("\\\\", "/", $Path);
		$Path = str_replace("\\", "/", $Path);
		$Path = ewrpt_PathCombine(ewrpt_IncludeTrailingDelimiter($Path, FALSE), $DestPath, FALSE);
	}
	return ewrpt_IncludeTrailingDelimiter($Path, $PhyPath);
}

// Get a temp folder for temp file
function ewrpt_TmpFolder() {
	$tmpfolder = NULL;
	$folders = array();
	if (EWRPT_IS_WINDOWS) {
		$folders[] = ewrpt_ServerVar("TEMP");
		$folders[] = ewrpt_ServerVar("TMP");
	} else {
		if (EWRPT_UPLOAD_TMP_PATH <> "") $folders[] = ewrpt_AppRoot() . str_replace("/", EWRPT_PATH_DELIMITER, EWRPT_UPLOAD_TMP_PATH);
		$folders[] = '/tmp';
	}
	if (ini_get('upload_tmp_dir')) {
		$folders[] = ini_get('upload_tmp_dir');
	}
	foreach ($folders as $folder) {
		if (!$tmpfolder && is_dir($folder)) {
			$tmpfolder = $folder;
		}
	}

	//if ($tmpfolder) $tmpfolder = ewrpt_IncludeTrailingDelimiter($tmpfolder, TRUE);
	return $tmpfolder;
}

// Application root
function ewrpt_AppRoot() {

	// 1. use root relative path
	if (EWRPT_ROOT_RELATIVE_PATH <> "") {
		$Path = realpath(EWRPT_ROOT_RELATIVE_PATH);
		$Path = str_replace("\\\\", EWRPT_PATH_DELIMITER, $Path);
	}

	// 2. if empty, use the document root if available
	if (empty($Path)) $Path = ewrpt_ServerVar("DOCUMENT_ROOT");

	// 3. if empty, use current folder
	if (empty($Path)) $Path = realpath(".");

	// 4. use custom path, uncomment the following line and enter your path
	// e.g. $Path = 'C:\Inetpub\wwwroot\MyWebRoot'; // Windows
	//$Path = 'enter your path here';

	if (empty($Path)) die("Path of website root unknown.");
	return ewrpt_IncludeTrailingDelimiter($Path, TRUE);
}

// Get path relative to application root
function ewrpt_ServerMapPath($Path) {
	return ewrpt_PathCombine(ewrpt_AppRoot(), $Path, TRUE);
}

// Get path relative to a base path
function ewrpt_PathCombine($BasePath, $RelPath, $PhyPath) {
	$BasePath = ewrpt_RemoveTrailingDelimiter($BasePath, $PhyPath);
	if ($PhyPath) {
		$Delimiter = EWRPT_PATH_DELIMITER;
		$RelPath = str_replace('/', EWRPT_PATH_DELIMITER, $RelPath);
		$RelPath = str_replace('\\', EWRPT_PATH_DELIMITER, $RelPath);
	} else {
		$Delimiter = '/';
		$RelPath = str_replace('\\', '/', $RelPath);
	}
	if ($RelPath == '.' || $RelPath == '..') $RelPath .= $Delimiter;
	$p1 = strpos($RelPath, $Delimiter);
	$Path2 = "";
	while ($p1 !== FALSE) {
		$Path = substr($RelPath, 0, $p1 + 1);
		if ($Path == $Delimiter || $Path == ".$Delimiter") {

			// Skip
		} elseif ($Path == "..$Delimiter") {
			$p2 = strrpos($BasePath, $Delimiter);
			if ($p2 !== FALSE) $BasePath = substr($BasePath, 0, $p2);
		} else {
			$Path2 .= $Path;
		}
		$RelPath = substr($RelPath, $p1+1);
		if ($RelPath === FALSE)
			$RelPath = "";
		$p1 = strpos($RelPath, $Delimiter);
	}
	return ewrpt_IncludeTrailingDelimiter($BasePath, $PhyPath) . $Path2 . $RelPath;
}

// Remove the last delimiter for a path
function ewrpt_RemoveTrailingDelimiter($Path, $PhyPath) {
	$Delimiter = ($PhyPath) ? EWRPT_PATH_DELIMITER : '/';
	while (substr($Path, -1) == $Delimiter)
		$Path = substr($Path, 0, strlen($Path)-1);
	return $Path;
}

// Include the last delimiter for a path
function ewrpt_IncludeTrailingDelimiter($Path, $PhyPath) {
	$Path = ewrpt_RemoveTrailingDelimiter($Path, $PhyPath);
	$Delimiter = ($PhyPath) ? EWRPT_PATH_DELIMITER : '/';
	return $Path . $Delimiter;
}

// Create folder
function ewrpt_CreateFolder($dir, $mode = 0777) {
	if ($dir == "")
		return TRUE;
	if (is_dir($dir) || @mkdir($dir, $mode))
		return TRUE;
	if (!ewrpt_CreateFolder(dirname($dir), $mode))
		return FALSE;
	return @mkdir($dir, $mode);
}

// Save file
function ewrpt_SaveFile($folder, $fn, $filedata) {
	$res = FALSE;
	if (ewrpt_CreateFolder($folder)) {
		if ($handle = fopen($folder . $fn, 'w')) { // P6
			$res = fwrite($handle, $filedata);
    	fclose($handle);
		}
		if ($res)
			chmod($folder . $fn, EWRPT_UPLOADED_FILE_MODE);
	}
	return $res;
}

// Init array
function &ewrpt_InitArray($len, $value) {
	if ($len > 0)
		$ar = array_fill(0, $len, $value);
	else
		$ar = array();
	return $ar;
}

// Init 2D array
function &ewrpt_Init2DArray($len1, $len2, $value) {
	return ewrpt_InitArray($len1, ewrpt_InitArray($len2, $value));
}

// function to generate random number
function ewrpt_Random() {
	return mt_rand();
}

// Convert string to float
function ewrpt_StrToFloat($v) {
	$v = str_replace(" ", "", $v);	
	if (!EWRPT_USE_DEFAULT_LOCALE) extract(localeconv()); // PHP 4 >= 4.0.5
	if (empty($decimal_point)) $decimal_point = EWRPT_DEFAULT_DECIMAL_POINT;
	$v = str_replace($decimal_point, ".", $v);
	return $v;
}

// Convert different data type value
function ewrpt_Conv($v, $t) {
	switch ($t) {
	case 2:
	case 3:
	case 16:
	case 17:
	case 18:
	case 19: // adSmallInt/adInteger/adTinyInt/adUnsignedTinyInt/adUnsignedSmallInt
		return (is_null($v)) ? NULL : intval($v);
	case 4:
	Case 5:
	case 6:
	case 131:
	case 139: // adSingle/adDouble/adCurrency/adNumeric/adVarNumeric
		return (is_null($v)) ? NULL : (float)$v;
	default:
		return (is_null($v)) ? NULL : $v;
	}
}

// Convert byte array to binary string
function ewrpt_BytesToStr($bytes) {
	$str = "";
	foreach ($bytes as $byte)
		$str .= chr($byte);
	return $str;
}

// Create temp image file from binary data
function ewrpt_TmpImage(&$filedata) {
	global $gTmpImages;

//  $f = tempnam(ew_TmpFolder(), "tmp");
	$folder = ewrpt_AppRoot() . EWRPT_UPLOAD_DEST_PATH;
	$f = tempnam($folder, "tmp");
	$handle = fopen($f, 'w+');
	fwrite($handle, $filedata);
	fclose($handle);
	$info = getimagesize($f);
	switch ($info[2]) {
	case 1:
		rename($f, $f .= '.gif'); break;
	case 2:
		rename($f, $f .= '.jpg'); break;
	case 3:
		rename($f, $f .= '.png'); break;
	default:
		return "";
	}
	$tmpimage = basename($f);
	$gTmpImages[] = $tmpimage;
	return EWRPT_UPLOAD_DEST_PATH . $tmpimage;
}

// Delete temp images
function ewrpt_DeleteTmpImages() {
	global $gTmpImages;
	foreach ($gTmpImages as $tmpimage)
		@unlink(ewrpt_AppRoot() . EWRPT_UPLOAD_DEST_PATH . $tmpimage);
}

// Create temp file
function ewrpt_TmpFile($file) {
	global $gTmpImages;
	if (file_exists($file)) { // Copy only

//  	$f = tempnam(ew_TmpFolder(), "tmp");
		$folder = ewrpt_AppRoot() . EWRPT_UPLOAD_DEST_PATH;
		$f = tempnam($folder, "tmp");
		@unlink($f);
		$info = pathinfo($file);
		if ($info["extension"] <> "")
			$f .= "." . $info["extension"];
		copy($file, $f);
		$tmpimage = basename($f);
		$gTmpImages[] = $tmpimage;
		return EWRPT_UPLOAD_DEST_PATH . $tmpimage;
	} else {
		return "";
	}
}
?>
<?php

/**
 * Functions for image resize
 */

// Resize binary to thumbnail
function ewrpt_ResizeBinary($filedata, &$width, &$height, $quality) {
	return TRUE; // No resize
}

// Resize file to thumbnail file
function ewrpt_ResizeFile($fn, $tn, &$width, &$height, $quality) {
	if (file_exists($fn)) { // Copy only
		return ($fn <> $tn) ? copy($fn, $tn) : TRUE;
	} else {
		return FALSE;
	}
}

// Resize file to binary
function ewrpt_ResizeFileToBinary($fn, &$width, &$height, $quality) {
	return file_get_contents($fn); // Return original file content only
}
?>
