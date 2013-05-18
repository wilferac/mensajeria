<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg5.php"; ?>
<?php include_once "phprptinc/ewmysql.php"; ?>
<?php include_once "phprptinc/ewrfn5.php"; ?>
<?php include_once "phprptinc/ewrusrfn.php"; ?>
<?php

// Global variable for table object
$guias = NULL;

//
// Table class for guias
//
class crguias {
	var $TableVar = 'guias';
	var $TableName = 'guias';
	var $TableType = 'REPORT';
	var $ShowCurrentFilter = EWRPT_SHOW_CURRENT_FILTER;
	var $FilterPanelOption = EWRPT_FILTER_PANEL_OPTION;
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type

	// Table caption
	function TableCaption() {
		global $ReportLanguage;
		return $ReportLanguage->TablePhrase($this->TableVar, "TblCaption");
	}

	// Session Group Per Page
	function getGroupPerPage() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"];
	}

	function setGroupPerPage($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"] = $v;
	}

	// Session Start Group
	function getStartGroup() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"];
	}

	function setStartGroup($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"] = $v;
	}

	// Session Order By
	function getOrderBy() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"];
	}

	function setOrderBy($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"] = $v;
	}

//	var $SelectLimit = TRUE;
	var $idguia;
	var $numero_guia;
	var $orden_servicio_idorden_servicio;
	var $zona_idzona;
	var $causal_devolucion_idcausal_devolucion;
	var $manifiesto_idmanifiesto;
	var $producto_idproducto;
	var $ciudad_iddestino;
	var $valor_declarado_guia;
	var $nombre_destinatario_guia;
	var $direccion_destinatario_guia;
	var $telefono_destinatario_guia;
	var $peso_guia;
	var $ciudad_idorigen;
	var $tercero_idremitente;
	var $tercero_iddestinatario;
	var $fields = array();
	var $Export; // Export
	var $ExportAll = TRUE;
	var $UseTokenInUrl = EWRPT_USE_TOKEN_IN_URL;
	var $RowType; // Row type
	var $RowTotalType; // Row total type
	var $RowTotalSubType; // Row total subtype
	var $RowGroupLevel; // Row group level
	var $RowAttrs = array(); // Row attributes

	// Reset CSS styles for table object
	function ResetCSS() {
    	$this->RowAttrs["style"] = "";
		$this->RowAttrs["class"] = "";
		foreach ($this->fields as $fld) {
			$fld->ResetCSS();
		}
	}

	//
	// Table class constructor
	//
	function crguias() {
		global $ReportLanguage;

		// idguia
		$this->idguia = new crField('guias', 'guias', 'x_idguia', 'idguia', '`idguia`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->idguia->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['idguia'] =& $this->idguia;
		$this->idguia->DateFilter = "";
		$this->idguia->SqlSelect = "";
		$this->idguia->SqlOrderBy = "";

		// numero_guia
		$this->numero_guia = new crField('guias', 'guias', 'x_numero_guia', 'numero_guia', '`numero_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['numero_guia'] =& $this->numero_guia;
		$this->numero_guia->DateFilter = "";
		$this->numero_guia->SqlSelect = "";
		$this->numero_guia->SqlOrderBy = "";

		// orden_servicio_idorden_servicio
		$this->orden_servicio_idorden_servicio = new crField('guias', 'guias', 'x_orden_servicio_idorden_servicio', 'orden_servicio_idorden_servicio', '`orden_servicio_idorden_servicio`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->orden_servicio_idorden_servicio->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['orden_servicio_idorden_servicio'] =& $this->orden_servicio_idorden_servicio;
		$this->orden_servicio_idorden_servicio->DateFilter = "";
		$this->orden_servicio_idorden_servicio->SqlSelect = "";
		$this->orden_servicio_idorden_servicio->SqlOrderBy = "";

		// zona_idzona
		$this->zona_idzona = new crField('guias', 'guias', 'x_zona_idzona', 'zona_idzona', '`zona_idzona`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->zona_idzona->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['zona_idzona'] =& $this->zona_idzona;
		$this->zona_idzona->DateFilter = "";
		$this->zona_idzona->SqlSelect = "";
		$this->zona_idzona->SqlOrderBy = "";

		// causal_devolucion_idcausal_devolucion
		$this->causal_devolucion_idcausal_devolucion = new crField('guias', 'guias', 'x_causal_devolucion_idcausal_devolucion', 'causal_devolucion_idcausal_devolucion', '`causal_devolucion_idcausal_devolucion`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->causal_devolucion_idcausal_devolucion->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['causal_devolucion_idcausal_devolucion'] =& $this->causal_devolucion_idcausal_devolucion;
		$this->causal_devolucion_idcausal_devolucion->DateFilter = "";
		$this->causal_devolucion_idcausal_devolucion->SqlSelect = "";
		$this->causal_devolucion_idcausal_devolucion->SqlOrderBy = "";

		// manifiesto_idmanifiesto
		$this->manifiesto_idmanifiesto = new crField('guias', 'guias', 'x_manifiesto_idmanifiesto', 'manifiesto_idmanifiesto', '`manifiesto_idmanifiesto`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->manifiesto_idmanifiesto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['manifiesto_idmanifiesto'] =& $this->manifiesto_idmanifiesto;
		$this->manifiesto_idmanifiesto->DateFilter = "";
		$this->manifiesto_idmanifiesto->SqlSelect = "";
		$this->manifiesto_idmanifiesto->SqlOrderBy = "";

		// producto_idproducto
		$this->producto_idproducto = new crField('guias', 'guias', 'x_producto_idproducto', 'producto_idproducto', '`producto_idproducto`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->producto_idproducto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['producto_idproducto'] =& $this->producto_idproducto;
		$this->producto_idproducto->DateFilter = "";
		$this->producto_idproducto->SqlSelect = "";
		$this->producto_idproducto->SqlOrderBy = "";

		// ciudad_iddestino
		$this->ciudad_iddestino = new crField('guias', 'guias', 'x_ciudad_iddestino', 'ciudad_iddestino', '`ciudad_iddestino`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->ciudad_iddestino->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['ciudad_iddestino'] =& $this->ciudad_iddestino;
		$this->ciudad_iddestino->DateFilter = "";
		$this->ciudad_iddestino->SqlSelect = "";
		$this->ciudad_iddestino->SqlOrderBy = "";

		// valor_declarado_guia
		$this->valor_declarado_guia = new crField('guias', 'guias', 'x_valor_declarado_guia', 'valor_declarado_guia', '`valor_declarado_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['valor_declarado_guia'] =& $this->valor_declarado_guia;
		$this->valor_declarado_guia->DateFilter = "";
		$this->valor_declarado_guia->SqlSelect = "";
		$this->valor_declarado_guia->SqlOrderBy = "";

		// nombre_destinatario_guia
		$this->nombre_destinatario_guia = new crField('guias', 'guias', 'x_nombre_destinatario_guia', 'nombre_destinatario_guia', '`nombre_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['nombre_destinatario_guia'] =& $this->nombre_destinatario_guia;
		$this->nombre_destinatario_guia->DateFilter = "";
		$this->nombre_destinatario_guia->SqlSelect = "";
		$this->nombre_destinatario_guia->SqlOrderBy = "";

		// direccion_destinatario_guia
		$this->direccion_destinatario_guia = new crField('guias', 'guias', 'x_direccion_destinatario_guia', 'direccion_destinatario_guia', '`direccion_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['direccion_destinatario_guia'] =& $this->direccion_destinatario_guia;
		$this->direccion_destinatario_guia->DateFilter = "";
		$this->direccion_destinatario_guia->SqlSelect = "";
		$this->direccion_destinatario_guia->SqlOrderBy = "";

		// telefono_destinatario_guia
		$this->telefono_destinatario_guia = new crField('guias', 'guias', 'x_telefono_destinatario_guia', 'telefono_destinatario_guia', '`telefono_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['telefono_destinatario_guia'] =& $this->telefono_destinatario_guia;
		$this->telefono_destinatario_guia->DateFilter = "";
		$this->telefono_destinatario_guia->SqlSelect = "";
		$this->telefono_destinatario_guia->SqlOrderBy = "";

		// peso_guia
		$this->peso_guia = new crField('guias', 'guias', 'x_peso_guia', 'peso_guia', '`peso_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['peso_guia'] =& $this->peso_guia;
		$this->peso_guia->DateFilter = "";
		$this->peso_guia->SqlSelect = "";
		$this->peso_guia->SqlOrderBy = "";

		// ciudad_idorigen
		$this->ciudad_idorigen = new crField('guias', 'guias', 'x_ciudad_idorigen', 'ciudad_idorigen', '`ciudad_idorigen`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->ciudad_idorigen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['ciudad_idorigen'] =& $this->ciudad_idorigen;
		$this->ciudad_idorigen->DateFilter = "";
		$this->ciudad_idorigen->SqlSelect = "";
		$this->ciudad_idorigen->SqlOrderBy = "";

		// tercero_idremitente
		$this->tercero_idremitente = new crField('guias', 'guias', 'x_tercero_idremitente', 'tercero_idremitente', '`tercero_idremitente`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->tercero_idremitente->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['tercero_idremitente'] =& $this->tercero_idremitente;
		$this->tercero_idremitente->DateFilter = "";
		$this->tercero_idremitente->SqlSelect = "";
		$this->tercero_idremitente->SqlOrderBy = "";

		// tercero_iddestinatario
		$this->tercero_iddestinatario = new crField('guias', 'guias', 'x_tercero_iddestinatario', 'tercero_iddestinatario', '`tercero_iddestinatario`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->tercero_iddestinatario->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['tercero_iddestinatario'] =& $this->tercero_iddestinatario;
		$this->tercero_iddestinatario->DateFilter = "";
		$this->tercero_iddestinatario->SqlSelect = "";
		$this->tercero_iddestinatario->SqlOrderBy = "";
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = "";
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fld->FldExpression, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fld->FldExpression . " " . $fld->getSort();
				} else {
					if ($sDtlSortSql <> "") $sDtlSortSql .= ", ";
					$sDtlSortSql .= $fld->FldExpression . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ",";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`guia`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		return "";
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "";
	}

	function SqlSelectAgg() {
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlAggPfx() {
		return "";
	}

	function SqlAggSfx() {
		return "";
	}

	function SqlSelectCount() {
		return "SELECT COUNT(*) FROM " . $this->SqlFrom();
	}

	// Sort URL
	function SortUrl(&$fld) {
		return "";
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = "";
		foreach ($this->RowAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Field object by fldvar
	function &fields($fldvar) {
		return $this->fields[$fldvar];
	}

	// Table level events
	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// Load Filters event
	function Filters_Load() {

		// Enter your code here	
		// Example: Register/Unregister Custom Extended Filter
		//ewrpt_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter');
		//ewrpt_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//global $MyTable;
		//$MyTable->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Chart Rendering event
	function Chart_Rendering(&$chart) {

		// var_dump($chart);
	}

	// Chart Rendered event
	function Chart_Rendered($chart, &$chartxml) {

		// Example:	
		//$doc = $chart->XmlDoc; // Get the DOMDocument object
		// Enter your code to manipulate the DOMDocument object here
		//$chartxml = $doc->saveXML(); // Output the XML

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}
}
?>
<?php ewrpt_Header(FALSE) ?>
<?php

// Create page object
$guias_summary = new crguias_summary();
$Page =& $guias_summary;

// Page init
$guias_summary->Page_Init();

// Page main
$guias_summary->Page_Main();
?>
<?php include_once "phprptinc/header.php"; ?>
<script type="text/javascript">

// Create page object
var guias_summary = new ewrpt_Page("guias_summary");

// page properties
guias_summary.PageID = "summary"; // page ID
guias_summary.FormID = "fguiassummaryfilter"; // form ID
var EWRPT_PAGE_ID = guias_summary.PageID;

// extend page with Chart_Rendering function
guias_summary.Chart_Rendering =  
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// extend page with Chart_Rendered function
guias_summary.Chart_Rendered =  
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
//-->

</script>
<script src="<?php echo EWRPT_FUSIONCHARTS_FREE_JSCLASS_FILE; ?>" type="text/javascript"></script>
<div id="ewrpt_PopupFilter"><div class="bd"></div></div>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
</script>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<p class="phpreportmaker ewTitle"><?php echo $guias->TableCaption() ?>
&nbsp;&nbsp;<?php $guias_summary->ExportOptions->Render("body"); ?></p>
<?php $guias_summary->ShowPageHeader(); ?>
<?php $guias_summary->ShowMessage(); ?>
<br><br>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($guias->Export <> "pdf") { ?>
<div id="report_summary">
<table class="ewGrid" cellspacing="0"><tr>
	<td class="ewGridContent">
<?php } ?>
<div class="ewGridUpperPanel">
<form action="<?php echo ewrpt_CurrentPage() ?>" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($guias_summary->StartGrp, $guias_summary->DisplayGrps, $guias_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/lastdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("of") ?> <?php echo $Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $Pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $Pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($guias_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($guias_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($guias_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($guias_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($guias_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($guias_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($guias_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($guias_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($guias_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($guias_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($guias->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<!-- Report Grid (Begin) -->
<?php if ($guias->Export <> "pdf") { ?>
<div class="ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $guias_summary->ReportTableClass ?>" cellspacing="0">
<?php

// Set the last group to display if not export all
if ($guias->ExportAll && $guias->Export <> "") {
	$guias_summary->StopGrp = $guias_summary->TotalGrps;
} else {
	$guias_summary->StopGrp = $guias_summary->StartGrp + $guias_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($guias_summary->StopGrp) > intval($guias_summary->TotalGrps))
	$guias_summary->StopGrp = $guias_summary->TotalGrps;
$guias_summary->RecCount = 0;

// Get first row
if ($guias_summary->TotalGrps > 0) {
	$guias_summary->GetRow(1);
	$guias_summary->GrpCount = 1;
}
while (($rs && !$rs->EOF && $guias_summary->GrpCount <= $guias_summary->DisplayGrps) || $guias_summary->ShowFirstHeader) {

	// Show header
	if ($guias_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->idguia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->idguia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->idguia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->idguia) ?>',0);"><?php echo $guias->idguia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->idguia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->idguia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->numero_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->numero_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->numero_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->numero_guia) ?>',0);"><?php echo $guias->numero_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->numero_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->numero_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->orden_servicio_idorden_servicio->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->orden_servicio_idorden_servicio) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->orden_servicio_idorden_servicio->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->orden_servicio_idorden_servicio) ?>',0);"><?php echo $guias->orden_servicio_idorden_servicio->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->orden_servicio_idorden_servicio->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->orden_servicio_idorden_servicio->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->zona_idzona->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->zona_idzona) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->zona_idzona->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->zona_idzona) ?>',0);"><?php echo $guias->zona_idzona->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->zona_idzona->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->zona_idzona->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->causal_devolucion_idcausal_devolucion->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->causal_devolucion_idcausal_devolucion) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->causal_devolucion_idcausal_devolucion->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->causal_devolucion_idcausal_devolucion) ?>',0);"><?php echo $guias->causal_devolucion_idcausal_devolucion->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->causal_devolucion_idcausal_devolucion->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->causal_devolucion_idcausal_devolucion->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->manifiesto_idmanifiesto->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->manifiesto_idmanifiesto) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->manifiesto_idmanifiesto->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->manifiesto_idmanifiesto) ?>',0);"><?php echo $guias->manifiesto_idmanifiesto->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->manifiesto_idmanifiesto->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->manifiesto_idmanifiesto->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->producto_idproducto->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->producto_idproducto) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->producto_idproducto->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->producto_idproducto) ?>',0);"><?php echo $guias->producto_idproducto->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->producto_idproducto->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->producto_idproducto->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->ciudad_iddestino->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->ciudad_iddestino) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->ciudad_iddestino->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->ciudad_iddestino) ?>',0);"><?php echo $guias->ciudad_iddestino->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->ciudad_iddestino->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->ciudad_iddestino->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->valor_declarado_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->valor_declarado_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->valor_declarado_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->valor_declarado_guia) ?>',0);"><?php echo $guias->valor_declarado_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->valor_declarado_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->valor_declarado_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->nombre_destinatario_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->nombre_destinatario_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->nombre_destinatario_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->nombre_destinatario_guia) ?>',0);"><?php echo $guias->nombre_destinatario_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->nombre_destinatario_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->nombre_destinatario_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->direccion_destinatario_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->direccion_destinatario_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->direccion_destinatario_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->direccion_destinatario_guia) ?>',0);"><?php echo $guias->direccion_destinatario_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->direccion_destinatario_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->direccion_destinatario_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->telefono_destinatario_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->telefono_destinatario_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->telefono_destinatario_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->telefono_destinatario_guia) ?>',0);"><?php echo $guias->telefono_destinatario_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->telefono_destinatario_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->telefono_destinatario_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->peso_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->peso_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->peso_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->peso_guia) ?>',0);"><?php echo $guias->peso_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->peso_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->peso_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->ciudad_idorigen->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->ciudad_idorigen) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->ciudad_idorigen->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->ciudad_idorigen) ?>',0);"><?php echo $guias->ciudad_idorigen->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->ciudad_idorigen->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->ciudad_idorigen->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->tercero_idremitente->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->tercero_idremitente) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->tercero_idremitente->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->tercero_idremitente) ?>',0);"><?php echo $guias->tercero_idremitente->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->tercero_idremitente->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->tercero_idremitente->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($guias->Export <> "") { ?>
<?php echo $guias->tercero_iddestinatario->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($guias->SortUrl($guias->tercero_iddestinatario) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $guias->tercero_iddestinatario->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $guias->SortUrl($guias->tercero_iddestinatario) ?>',0);"><?php echo $guias->tercero_iddestinatario->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($guias->tercero_iddestinatario->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($guias->tercero_iddestinatario->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$guias_summary->ShowFirstHeader = FALSE;
	}
	$guias_summary->RecCount++;

		// Render detail row
		$guias->ResetCSS();
		$guias->RowType = EWRPT_ROWTYPE_DETAIL;
		$guias_summary->RenderRow();
?>
	<tr<?php echo $guias->RowAttributes(); ?>>
		<td<?php echo $guias->idguia->CellAttributes() ?>>
<span<?php echo $guias->idguia->ViewAttributes(); ?>><?php echo $guias->idguia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->numero_guia->CellAttributes() ?>>
<span<?php echo $guias->numero_guia->ViewAttributes(); ?>><?php echo $guias->numero_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->orden_servicio_idorden_servicio->CellAttributes() ?>>
<span<?php echo $guias->orden_servicio_idorden_servicio->ViewAttributes(); ?>><?php echo $guias->orden_servicio_idorden_servicio->ListViewValue(); ?></span></td>
		<td<?php echo $guias->zona_idzona->CellAttributes() ?>>
<span<?php echo $guias->zona_idzona->ViewAttributes(); ?>><?php echo $guias->zona_idzona->ListViewValue(); ?></span></td>
		<td<?php echo $guias->causal_devolucion_idcausal_devolucion->CellAttributes() ?>>
<span<?php echo $guias->causal_devolucion_idcausal_devolucion->ViewAttributes(); ?>><?php echo $guias->causal_devolucion_idcausal_devolucion->ListViewValue(); ?></span></td>
		<td<?php echo $guias->manifiesto_idmanifiesto->CellAttributes() ?>>
<span<?php echo $guias->manifiesto_idmanifiesto->ViewAttributes(); ?>><?php echo $guias->manifiesto_idmanifiesto->ListViewValue(); ?></span></td>
		<td<?php echo $guias->producto_idproducto->CellAttributes() ?>>
<span<?php echo $guias->producto_idproducto->ViewAttributes(); ?>><?php echo $guias->producto_idproducto->ListViewValue(); ?></span></td>
		<td<?php echo $guias->ciudad_iddestino->CellAttributes() ?>>
<span<?php echo $guias->ciudad_iddestino->ViewAttributes(); ?>><?php echo $guias->ciudad_iddestino->ListViewValue(); ?></span></td>
		<td<?php echo $guias->valor_declarado_guia->CellAttributes() ?>>
<span<?php echo $guias->valor_declarado_guia->ViewAttributes(); ?>><?php echo $guias->valor_declarado_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->nombre_destinatario_guia->CellAttributes() ?>>
<span<?php echo $guias->nombre_destinatario_guia->ViewAttributes(); ?>><?php echo $guias->nombre_destinatario_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->direccion_destinatario_guia->CellAttributes() ?>>
<span<?php echo $guias->direccion_destinatario_guia->ViewAttributes(); ?>><?php echo $guias->direccion_destinatario_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->telefono_destinatario_guia->CellAttributes() ?>>
<span<?php echo $guias->telefono_destinatario_guia->ViewAttributes(); ?>><?php echo $guias->telefono_destinatario_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->peso_guia->CellAttributes() ?>>
<span<?php echo $guias->peso_guia->ViewAttributes(); ?>><?php echo $guias->peso_guia->ListViewValue(); ?></span></td>
		<td<?php echo $guias->ciudad_idorigen->CellAttributes() ?>>
<span<?php echo $guias->ciudad_idorigen->ViewAttributes(); ?>><?php echo $guias->ciudad_idorigen->ListViewValue(); ?></span></td>
		<td<?php echo $guias->tercero_idremitente->CellAttributes() ?>>
<span<?php echo $guias->tercero_idremitente->ViewAttributes(); ?>><?php echo $guias->tercero_idremitente->ListViewValue(); ?></span></td>
		<td<?php echo $guias->tercero_iddestinatario->CellAttributes() ?>>
<span<?php echo $guias->tercero_iddestinatario->ViewAttributes(); ?>><?php echo $guias->tercero_iddestinatario->ListViewValue(); ?></span></td>
	</tr>
<?php

		// Accumulate page summary
		$guias_summary->AccumulateSummary();

		// Get next record
		$guias_summary->GetRow(2);
	$guias_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($guias_summary->TotalGrps > 0) {
	$guias->ResetCSS();
	$guias->RowType = EWRPT_ROWTYPE_TOTAL;
	$guias->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$guias->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$guias->RowAttrs["class"] = "ewRptGrandSummary";
	$guias_summary->RenderRow();
?>
	<!-- tr><td colspan="16"><span class="phpreportmaker">&nbsp;<br></span></td></tr -->
	<tr<?php echo $guias->RowAttributes(); ?>><td colspan="16"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($guias_summary->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
	</tfoot>
</table>
<?php if ($guias->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($guias_summary->TotalGrps > 0) { ?>
<div class="ewGridLowerPanel">
<form action="<?php echo ewrpt_CurrentPage() ?>" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($guias_summary->StartGrp, $guias_summary->DisplayGrps, $guias_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo ewrpt_CurrentPage() ?>?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/lastdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("of") ?> <?php echo $Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $Pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $Pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($guias_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($guias_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($guias_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($guias_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($guias_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($guias_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($guias_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($guias_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($guias_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($guias_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($guias->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<?php } ?>
<?php if ($guias->Export <> "pdf") { ?>
</td></tr></table>
</div>
<?php } ?>
<!-- Summary Report Ends -->
	</div><br></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
	</div><br></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php $guias_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php include_once "phprptinc/footer.php"; ?>
<?php
$guias_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crguias_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'guias';

	// Page object name
	var $PageObjName = 'guias_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $guias;
		if ($guias->UseTokenInUrl) $PageUrl .= "t=" . $guias->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;

	// Message
	function getMessage() {
		return @$_SESSION[EWRPT_SESSION_MESSAGE];
	}

	function setMessage($v) {
		if (@$_SESSION[EWRPT_SESSION_MESSAGE] <> "") { // Append
			$_SESSION[EWRPT_SESSION_MESSAGE] .= "<br>" . $v;
		} else {
			$_SESSION[EWRPT_SESSION_MESSAGE] = $v;
		}
	}

	// Show message
	function ShowMessage() {
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage);
		if ($sMessage <> "") { // Message in Session, display
			echo "<p><span class=\"ewMessage\">" . $sMessage . "</span></p>";
			$_SESSION[EWRPT_SESSION_MESSAGE] = ""; // Clear message in Session
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sHeader . "</span></p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sFooter . "</span></p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $guias;
		if ($guias->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($guias->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($guias->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crguias_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (guias)
		$GLOBALS["guias"] = new crguias();
		$GLOBALS["Table"] =& $GLOBALS["guias"];

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'guias', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		$conn = ewrpt_Connect();

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->Separator = "&nbsp;&nbsp;";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $ReportLanguage, $Security;
		global $guias;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$guias->Export = $_GET["export"];
		}
		$gsExport = $guias->Export; // Get export parameter, used in header
		$gsExportFile = $guias->TableVar; // Get export file, used in header

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $ReportLanguage, $guias;

		// Printer friendly
		$item =& $this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item =& $this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;

		// Export to Word
		$item =& $this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item =& $this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;
		// Export to Email

		$item =& $this->ExportOptions->Add("email");
		$item->Body = "<a name=\"emf_guias\" id=\"emf_guias\" href=\"javascript:void(0);\" onclick=\"ewrpt_EmailDialogShow({lnk:'emf_guias',hdr:ewLanguage.Phrase('ExportToEmail')});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Reset filter
		$item =& $this->ExportOptions->Add("resetfilter");
		$item->Body = "<a href=\"" . ewrpt_CurrentPage() . "?cmd=reset\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</a>";
		$item->Visible = FALSE;
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($guias->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Set up table class
		if ($guias->Export == "word" || $guias->Export == "excel" || $guias->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "ewTable ewTableSeparate";
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;
		global $ReportLanguage;
		global $guias;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($guias->Export == "email") {
			$sContent = ob_get_contents();
			$this->ExportEmail($sContent);
			ob_end_clean();

			 // Close connection
			$conn->Close();
			header("Location: " . ewrpt_CurrentPage());
			exit();
		}

		// Export to PDF (use ob_file_contents for PHP)
		if ($guias->Export == "pdf") {
			$sContent = ob_get_contents();
			$this->ExportPDF($sContent);
			ob_end_clean();

			 // Close connection
			$conn->Close();
		}

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWRPT_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options

	// Paging variables
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $UserIDFilter = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $FilterApplied;
	var $ShowFirstHeader;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;

	//
	// Page main
	//
	function Page_Main() {
		global $guias;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 17;
		$nGrps = 1;
		$this->Val =& ewrpt_InitArray($nDtls, 0);
		$this->Cnt =& ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry =& ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn =& ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx =& ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry =& ewrpt_InitArray($nDtls, 0);
		$this->GrandMn =& ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx =& ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Load custom filters
		$guias->Filters_Load();

		// Set up popup filter
		$this->SetupPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewrpt_SetDebugMsg("popup filter: " . $sPopupFilter);
		if ($sPopupFilter <> "") {
			if ($this->Filter <> "")
				$this->Filter = "($this->Filter) AND ($sPopupFilter)";
			else
				$this->Filter = $sPopupFilter;
		}

		// No filter
		$this->FilterApplied = FALSE;
		$this->ExportOptions->GetItem("resetfilter")->Visible = $this->FilterApplied;

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total count
		$sSql = ewrpt_BuildReportSql($guias->SqlSelect(), $guias->SqlWhere(), $guias->SqlGroupBy(), $guias->SqlHaving(), $guias->SqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($guias->ExportAll && $guias->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Hide all options if export
		if ($guias->Export <> "") {
			$this->ExportOptions->HideAllOptions();
		}

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy]++;
				if ($this->Col[$iy]) {
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk) || !is_numeric($valwrk)) {

						// skip
					} else {
						$this->Smry[$ix][$iy] += $valwrk;
						if (is_null($this->Mn[$ix][$iy])) {
							$this->Mn[$ix][$iy] = $valwrk;
							$this->Mx[$ix][$iy] = $valwrk;
						} else {
							if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
							if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 1; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->Cnt[0][0]++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {

					// skip
				} else {
					$this->GrandSmry[$iy] += $valwrk;
					if (is_null($this->GrandMn[$iy])) {
						$this->GrandMn[$iy] = $valwrk;
						$this->GrandMx[$iy] = $valwrk;
					} else {
						if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
						if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		global $conn;
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get rs
	function GetRs($sql, $start, $grps) {
		global $conn;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $guias;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$guias->idguia->setDbValue($rs->fields('idguia'));
			$guias->numero_guia->setDbValue($rs->fields('numero_guia'));
			$guias->orden_servicio_idorden_servicio->setDbValue($rs->fields('orden_servicio_idorden_servicio'));
			$guias->zona_idzona->setDbValue($rs->fields('zona_idzona'));
			$guias->causal_devolucion_idcausal_devolucion->setDbValue($rs->fields('causal_devolucion_idcausal_devolucion'));
			$guias->manifiesto_idmanifiesto->setDbValue($rs->fields('manifiesto_idmanifiesto'));
			$guias->producto_idproducto->setDbValue($rs->fields('producto_idproducto'));
			$guias->ciudad_iddestino->setDbValue($rs->fields('ciudad_iddestino'));
			$guias->valor_declarado_guia->setDbValue($rs->fields('valor_declarado_guia'));
			$guias->nombre_destinatario_guia->setDbValue($rs->fields('nombre_destinatario_guia'));
			$guias->direccion_destinatario_guia->setDbValue($rs->fields('direccion_destinatario_guia'));
			$guias->telefono_destinatario_guia->setDbValue($rs->fields('telefono_destinatario_guia'));
			$guias->peso_guia->setDbValue($rs->fields('peso_guia'));
			$guias->ciudad_idorigen->setDbValue($rs->fields('ciudad_idorigen'));
			$guias->tercero_idremitente->setDbValue($rs->fields('tercero_idremitente'));
			$guias->tercero_iddestinatario->setDbValue($rs->fields('tercero_iddestinatario'));
			$this->Val[1] = $guias->idguia->CurrentValue;
			$this->Val[2] = $guias->numero_guia->CurrentValue;
			$this->Val[3] = $guias->orden_servicio_idorden_servicio->CurrentValue;
			$this->Val[4] = $guias->zona_idzona->CurrentValue;
			$this->Val[5] = $guias->causal_devolucion_idcausal_devolucion->CurrentValue;
			$this->Val[6] = $guias->manifiesto_idmanifiesto->CurrentValue;
			$this->Val[7] = $guias->producto_idproducto->CurrentValue;
			$this->Val[8] = $guias->ciudad_iddestino->CurrentValue;
			$this->Val[9] = $guias->valor_declarado_guia->CurrentValue;
			$this->Val[10] = $guias->nombre_destinatario_guia->CurrentValue;
			$this->Val[11] = $guias->direccion_destinatario_guia->CurrentValue;
			$this->Val[12] = $guias->telefono_destinatario_guia->CurrentValue;
			$this->Val[13] = $guias->peso_guia->CurrentValue;
			$this->Val[14] = $guias->ciudad_idorigen->CurrentValue;
			$this->Val[15] = $guias->tercero_idremitente->CurrentValue;
			$this->Val[16] = $guias->tercero_iddestinatario->CurrentValue;
		} else {
			$guias->idguia->setDbValue("");
			$guias->numero_guia->setDbValue("");
			$guias->orden_servicio_idorden_servicio->setDbValue("");
			$guias->zona_idzona->setDbValue("");
			$guias->causal_devolucion_idcausal_devolucion->setDbValue("");
			$guias->manifiesto_idmanifiesto->setDbValue("");
			$guias->producto_idproducto->setDbValue("");
			$guias->ciudad_iddestino->setDbValue("");
			$guias->valor_declarado_guia->setDbValue("");
			$guias->nombre_destinatario_guia->setDbValue("");
			$guias->direccion_destinatario_guia->setDbValue("");
			$guias->telefono_destinatario_guia->setDbValue("");
			$guias->peso_guia->setDbValue("");
			$guias->ciudad_idorigen->setDbValue("");
			$guias->tercero_idremitente->setDbValue("");
			$guias->tercero_iddestinatario->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $guias;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$guias->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$guias->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $guias->getStartGroup();
			}
		} else {
			$this->StartGrp = $guias->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$guias->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$guias->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$guias->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $guias;

		// Initialize popup
		// Process post back form

		if (ewrpt_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewrpt_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWRPT_INIT_VALUE;
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewrpt_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewrpt_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		global $guias;
		$this->StartGrp = 1;
		$guias->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $guias;
		$sWrk = @$_GET[EWRPT_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$guias->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$guias->setStartGroup($this->StartGrp);
		} else {
			if ($guias->getGroupPerPage() <> "") {
				$this->DisplayGrps = $guias->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $guias;
		if ($guias->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($guias->SqlSelectCount(), $guias->SqlWhere(), $guias->SqlGroupBy(), $guias->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}
		}

		// Call Row_Rendering event
		$guias->Row_Rendering();

		//
		// Render view codes
		//

		if ($guias->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row
		} else {

			// idguia
			$guias->idguia->ViewValue = $guias->idguia->CurrentValue;
			$guias->idguia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// numero_guia
			$guias->numero_guia->ViewValue = $guias->numero_guia->CurrentValue;
			$guias->numero_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// orden_servicio_idorden_servicio
			$guias->orden_servicio_idorden_servicio->ViewValue = $guias->orden_servicio_idorden_servicio->CurrentValue;
			$guias->orden_servicio_idorden_servicio->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// zona_idzona
			$guias->zona_idzona->ViewValue = $guias->zona_idzona->CurrentValue;
			$guias->zona_idzona->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// causal_devolucion_idcausal_devolucion
			$guias->causal_devolucion_idcausal_devolucion->ViewValue = $guias->causal_devolucion_idcausal_devolucion->CurrentValue;
			$guias->causal_devolucion_idcausal_devolucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// manifiesto_idmanifiesto
			$guias->manifiesto_idmanifiesto->ViewValue = $guias->manifiesto_idmanifiesto->CurrentValue;
			$guias->manifiesto_idmanifiesto->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// producto_idproducto
			$guias->producto_idproducto->ViewValue = $guias->producto_idproducto->CurrentValue;
			$guias->producto_idproducto->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ciudad_iddestino
			$guias->ciudad_iddestino->ViewValue = $guias->ciudad_iddestino->CurrentValue;
			$guias->ciudad_iddestino->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// valor_declarado_guia
			$guias->valor_declarado_guia->ViewValue = $guias->valor_declarado_guia->CurrentValue;
			$guias->valor_declarado_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombre_destinatario_guia
			$guias->nombre_destinatario_guia->ViewValue = $guias->nombre_destinatario_guia->CurrentValue;
			$guias->nombre_destinatario_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// direccion_destinatario_guia
			$guias->direccion_destinatario_guia->ViewValue = $guias->direccion_destinatario_guia->CurrentValue;
			$guias->direccion_destinatario_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// telefono_destinatario_guia
			$guias->telefono_destinatario_guia->ViewValue = $guias->telefono_destinatario_guia->CurrentValue;
			$guias->telefono_destinatario_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// peso_guia
			$guias->peso_guia->ViewValue = $guias->peso_guia->CurrentValue;
			$guias->peso_guia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ciudad_idorigen
			$guias->ciudad_idorigen->ViewValue = $guias->ciudad_idorigen->CurrentValue;
			$guias->ciudad_idorigen->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tercero_idremitente
			$guias->tercero_idremitente->ViewValue = $guias->tercero_idremitente->CurrentValue;
			$guias->tercero_idremitente->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tercero_iddestinatario
			$guias->tercero_iddestinatario->ViewValue = $guias->tercero_iddestinatario->CurrentValue;
			$guias->tercero_iddestinatario->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// idguia
			$guias->idguia->HrefValue = "";

			// numero_guia
			$guias->numero_guia->HrefValue = "";

			// orden_servicio_idorden_servicio
			$guias->orden_servicio_idorden_servicio->HrefValue = "";

			// zona_idzona
			$guias->zona_idzona->HrefValue = "";

			// causal_devolucion_idcausal_devolucion
			$guias->causal_devolucion_idcausal_devolucion->HrefValue = "";

			// manifiesto_idmanifiesto
			$guias->manifiesto_idmanifiesto->HrefValue = "";

			// producto_idproducto
			$guias->producto_idproducto->HrefValue = "";

			// ciudad_iddestino
			$guias->ciudad_iddestino->HrefValue = "";

			// valor_declarado_guia
			$guias->valor_declarado_guia->HrefValue = "";

			// nombre_destinatario_guia
			$guias->nombre_destinatario_guia->HrefValue = "";

			// direccion_destinatario_guia
			$guias->direccion_destinatario_guia->HrefValue = "";

			// telefono_destinatario_guia
			$guias->telefono_destinatario_guia->HrefValue = "";

			// peso_guia
			$guias->peso_guia->HrefValue = "";

			// ciudad_idorigen
			$guias->ciudad_idorigen->HrefValue = "";

			// tercero_idremitente
			$guias->tercero_idremitente->HrefValue = "";

			// tercero_iddestinatario
			$guias->tercero_iddestinatario->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($guias->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row
		} else {

			// idguia
			$CurrentValue = $guias->idguia->CurrentValue;
			$ViewValue =& $guias->idguia->ViewValue;
			$ViewAttrs =& $guias->idguia->ViewAttrs;
			$CellAttrs =& $guias->idguia->CellAttrs;
			$HrefValue =& $guias->idguia->HrefValue;
			$guias->Cell_Rendered($guias->idguia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// numero_guia
			$CurrentValue = $guias->numero_guia->CurrentValue;
			$ViewValue =& $guias->numero_guia->ViewValue;
			$ViewAttrs =& $guias->numero_guia->ViewAttrs;
			$CellAttrs =& $guias->numero_guia->CellAttrs;
			$HrefValue =& $guias->numero_guia->HrefValue;
			$guias->Cell_Rendered($guias->numero_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// orden_servicio_idorden_servicio
			$CurrentValue = $guias->orden_servicio_idorden_servicio->CurrentValue;
			$ViewValue =& $guias->orden_servicio_idorden_servicio->ViewValue;
			$ViewAttrs =& $guias->orden_servicio_idorden_servicio->ViewAttrs;
			$CellAttrs =& $guias->orden_servicio_idorden_servicio->CellAttrs;
			$HrefValue =& $guias->orden_servicio_idorden_servicio->HrefValue;
			$guias->Cell_Rendered($guias->orden_servicio_idorden_servicio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// zona_idzona
			$CurrentValue = $guias->zona_idzona->CurrentValue;
			$ViewValue =& $guias->zona_idzona->ViewValue;
			$ViewAttrs =& $guias->zona_idzona->ViewAttrs;
			$CellAttrs =& $guias->zona_idzona->CellAttrs;
			$HrefValue =& $guias->zona_idzona->HrefValue;
			$guias->Cell_Rendered($guias->zona_idzona, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// causal_devolucion_idcausal_devolucion
			$CurrentValue = $guias->causal_devolucion_idcausal_devolucion->CurrentValue;
			$ViewValue =& $guias->causal_devolucion_idcausal_devolucion->ViewValue;
			$ViewAttrs =& $guias->causal_devolucion_idcausal_devolucion->ViewAttrs;
			$CellAttrs =& $guias->causal_devolucion_idcausal_devolucion->CellAttrs;
			$HrefValue =& $guias->causal_devolucion_idcausal_devolucion->HrefValue;
			$guias->Cell_Rendered($guias->causal_devolucion_idcausal_devolucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// manifiesto_idmanifiesto
			$CurrentValue = $guias->manifiesto_idmanifiesto->CurrentValue;
			$ViewValue =& $guias->manifiesto_idmanifiesto->ViewValue;
			$ViewAttrs =& $guias->manifiesto_idmanifiesto->ViewAttrs;
			$CellAttrs =& $guias->manifiesto_idmanifiesto->CellAttrs;
			$HrefValue =& $guias->manifiesto_idmanifiesto->HrefValue;
			$guias->Cell_Rendered($guias->manifiesto_idmanifiesto, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// producto_idproducto
			$CurrentValue = $guias->producto_idproducto->CurrentValue;
			$ViewValue =& $guias->producto_idproducto->ViewValue;
			$ViewAttrs =& $guias->producto_idproducto->ViewAttrs;
			$CellAttrs =& $guias->producto_idproducto->CellAttrs;
			$HrefValue =& $guias->producto_idproducto->HrefValue;
			$guias->Cell_Rendered($guias->producto_idproducto, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// ciudad_iddestino
			$CurrentValue = $guias->ciudad_iddestino->CurrentValue;
			$ViewValue =& $guias->ciudad_iddestino->ViewValue;
			$ViewAttrs =& $guias->ciudad_iddestino->ViewAttrs;
			$CellAttrs =& $guias->ciudad_iddestino->CellAttrs;
			$HrefValue =& $guias->ciudad_iddestino->HrefValue;
			$guias->Cell_Rendered($guias->ciudad_iddestino, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// valor_declarado_guia
			$CurrentValue = $guias->valor_declarado_guia->CurrentValue;
			$ViewValue =& $guias->valor_declarado_guia->ViewValue;
			$ViewAttrs =& $guias->valor_declarado_guia->ViewAttrs;
			$CellAttrs =& $guias->valor_declarado_guia->CellAttrs;
			$HrefValue =& $guias->valor_declarado_guia->HrefValue;
			$guias->Cell_Rendered($guias->valor_declarado_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// nombre_destinatario_guia
			$CurrentValue = $guias->nombre_destinatario_guia->CurrentValue;
			$ViewValue =& $guias->nombre_destinatario_guia->ViewValue;
			$ViewAttrs =& $guias->nombre_destinatario_guia->ViewAttrs;
			$CellAttrs =& $guias->nombre_destinatario_guia->CellAttrs;
			$HrefValue =& $guias->nombre_destinatario_guia->HrefValue;
			$guias->Cell_Rendered($guias->nombre_destinatario_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// direccion_destinatario_guia
			$CurrentValue = $guias->direccion_destinatario_guia->CurrentValue;
			$ViewValue =& $guias->direccion_destinatario_guia->ViewValue;
			$ViewAttrs =& $guias->direccion_destinatario_guia->ViewAttrs;
			$CellAttrs =& $guias->direccion_destinatario_guia->CellAttrs;
			$HrefValue =& $guias->direccion_destinatario_guia->HrefValue;
			$guias->Cell_Rendered($guias->direccion_destinatario_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// telefono_destinatario_guia
			$CurrentValue = $guias->telefono_destinatario_guia->CurrentValue;
			$ViewValue =& $guias->telefono_destinatario_guia->ViewValue;
			$ViewAttrs =& $guias->telefono_destinatario_guia->ViewAttrs;
			$CellAttrs =& $guias->telefono_destinatario_guia->CellAttrs;
			$HrefValue =& $guias->telefono_destinatario_guia->HrefValue;
			$guias->Cell_Rendered($guias->telefono_destinatario_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// peso_guia
			$CurrentValue = $guias->peso_guia->CurrentValue;
			$ViewValue =& $guias->peso_guia->ViewValue;
			$ViewAttrs =& $guias->peso_guia->ViewAttrs;
			$CellAttrs =& $guias->peso_guia->CellAttrs;
			$HrefValue =& $guias->peso_guia->HrefValue;
			$guias->Cell_Rendered($guias->peso_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// ciudad_idorigen
			$CurrentValue = $guias->ciudad_idorigen->CurrentValue;
			$ViewValue =& $guias->ciudad_idorigen->ViewValue;
			$ViewAttrs =& $guias->ciudad_idorigen->ViewAttrs;
			$CellAttrs =& $guias->ciudad_idorigen->CellAttrs;
			$HrefValue =& $guias->ciudad_idorigen->HrefValue;
			$guias->Cell_Rendered($guias->ciudad_idorigen, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// tercero_idremitente
			$CurrentValue = $guias->tercero_idremitente->CurrentValue;
			$ViewValue =& $guias->tercero_idremitente->ViewValue;
			$ViewAttrs =& $guias->tercero_idremitente->ViewAttrs;
			$CellAttrs =& $guias->tercero_idremitente->CellAttrs;
			$HrefValue =& $guias->tercero_idremitente->HrefValue;
			$guias->Cell_Rendered($guias->tercero_idremitente, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// tercero_iddestinatario
			$CurrentValue = $guias->tercero_iddestinatario->CurrentValue;
			$ViewValue =& $guias->tercero_iddestinatario->ViewValue;
			$ViewAttrs =& $guias->tercero_iddestinatario->ViewAttrs;
			$CellAttrs =& $guias->tercero_iddestinatario->CellAttrs;
			$HrefValue =& $guias->tercero_iddestinatario->HrefValue;
			$guias->Cell_Rendered($guias->tercero_iddestinatario, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);
		}

		// Call Row_Rendered event
		$guias->Row_Rendered();
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $guias;
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
	}

	// Return poup filter
	function GetPopupFilter() {
		global $guias;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $guias;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$guias->setOrderBy("");
				$guias->setStartGroup(1);
				$guias->idguia->setSort("");
				$guias->numero_guia->setSort("");
				$guias->orden_servicio_idorden_servicio->setSort("");
				$guias->zona_idzona->setSort("");
				$guias->causal_devolucion_idcausal_devolucion->setSort("");
				$guias->manifiesto_idmanifiesto->setSort("");
				$guias->producto_idproducto->setSort("");
				$guias->ciudad_iddestino->setSort("");
				$guias->valor_declarado_guia->setSort("");
				$guias->nombre_destinatario_guia->setSort("");
				$guias->direccion_destinatario_guia->setSort("");
				$guias->telefono_destinatario_guia->setSort("");
				$guias->peso_guia->setSort("");
				$guias->ciudad_idorigen->setSort("");
				$guias->tercero_idremitente->setSort("");
				$guias->tercero_iddestinatario->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$guias->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$guias->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $guias->SortSql();
			$guias->setOrderBy($sSortSql);
			$guias->setStartGroup(1);
		}
		return $guias->getOrderBy();
	}

	// Export PDF
	function ExportPDF($html) {
		global $gsExportFile;
		include_once "dompdf060b2/dompdf_config.inc.php";
		@ini_set("memory_limit", EWRPT_PDF_MEMORY_LIMIT);
		set_time_limit(EWRPT_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ob_end_clean();
		ewrpt_DeleteTmpImages();
		$dompdf->stream($gsExportFile . ".pdf", array("Attachment" => 1)); // 0 to open in browser, 1 to download

//		exit();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	function Message_Showing(&$msg) {

		// Example:
		//$msg = "your new message";

	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
