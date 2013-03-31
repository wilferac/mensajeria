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
$Crosstab1 = NULL;

//
// Table class for Crosstab1
//
class crCrosstab1 {
	var $TableVar = 'Crosstab1';
	var $TableName = 'Crosstab1';
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

	// Summary cells
	var $SummaryCellAttrs;
	var $SummaryViewAttrs;
	var $SummaryCurrentValue;
	var $SummaryViewValue;

	// Summary cell attributes
	function SummaryCellAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryCellAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryCellAttrs)) {
				$Attrs = $this->SummaryCellAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "")
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $sAtt;
	}

	// Summary view attributes
	function SummaryViewAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryViewAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryViewAttrs)) {
				$Attrs = $this->SummaryViewAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "")
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $sAtt;
	}

	//
	// Table class constructor
	//
	function crCrosstab1() {
		global $ReportLanguage;

		// idguia
		$this->idguia = new crField('Crosstab1', 'Crosstab1', 'x_idguia', 'idguia', '`idguia`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->idguia->GroupingFieldId = 1;
		$this->idguia->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['idguia'] =& $this->idguia;
		$this->idguia->DateFilter = "";
		$this->idguia->SqlSelect = "";
		$this->idguia->SqlOrderBy = "";

		// numero_guia
		$this->numero_guia = new crField('Crosstab1', 'Crosstab1', 'x_numero_guia', 'numero_guia', '`numero_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->numero_guia->GroupingFieldId = 2;
		$this->fields['numero_guia'] =& $this->numero_guia;
		$this->numero_guia->DateFilter = "";
		$this->numero_guia->SqlSelect = "";
		$this->numero_guia->SqlOrderBy = "";

		// orden_servicio_idorden_servicio
		$this->orden_servicio_idorden_servicio = new crField('Crosstab1', 'Crosstab1', 'x_orden_servicio_idorden_servicio', 'orden_servicio_idorden_servicio', '`orden_servicio_idorden_servicio`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->orden_servicio_idorden_servicio->GroupingFieldId = 3;
		$this->orden_servicio_idorden_servicio->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['orden_servicio_idorden_servicio'] =& $this->orden_servicio_idorden_servicio;
		$this->orden_servicio_idorden_servicio->DateFilter = "";
		$this->orden_servicio_idorden_servicio->SqlSelect = "";
		$this->orden_servicio_idorden_servicio->SqlOrderBy = "";

		// zona_idzona
		$this->zona_idzona = new crField('Crosstab1', 'Crosstab1', 'x_zona_idzona', 'zona_idzona', '`zona_idzona`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->zona_idzona->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['zona_idzona'] =& $this->zona_idzona;
		$this->zona_idzona->DateFilter = "";
		$this->zona_idzona->SqlSelect = "";
		$this->zona_idzona->SqlOrderBy = "";

		// causal_devolucion_idcausal_devolucion
		$this->causal_devolucion_idcausal_devolucion = new crField('Crosstab1', 'Crosstab1', 'x_causal_devolucion_idcausal_devolucion', 'causal_devolucion_idcausal_devolucion', '`causal_devolucion_idcausal_devolucion`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->causal_devolucion_idcausal_devolucion->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['causal_devolucion_idcausal_devolucion'] =& $this->causal_devolucion_idcausal_devolucion;
		$this->causal_devolucion_idcausal_devolucion->DateFilter = "";
		$this->causal_devolucion_idcausal_devolucion->SqlSelect = "";
		$this->causal_devolucion_idcausal_devolucion->SqlOrderBy = "";

		// manifiesto_idmanifiesto
		$this->manifiesto_idmanifiesto = new crField('Crosstab1', 'Crosstab1', 'x_manifiesto_idmanifiesto', 'manifiesto_idmanifiesto', '`manifiesto_idmanifiesto`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->manifiesto_idmanifiesto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['manifiesto_idmanifiesto'] =& $this->manifiesto_idmanifiesto;
		$this->manifiesto_idmanifiesto->DateFilter = "";
		$this->manifiesto_idmanifiesto->SqlSelect = "";
		$this->manifiesto_idmanifiesto->SqlOrderBy = "";

		// producto_idproducto
		$this->producto_idproducto = new crField('Crosstab1', 'Crosstab1', 'x_producto_idproducto', 'producto_idproducto', '`producto_idproducto`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->producto_idproducto->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['producto_idproducto'] =& $this->producto_idproducto;
		$this->producto_idproducto->DateFilter = "";
		$this->producto_idproducto->SqlSelect = "";
		$this->producto_idproducto->SqlOrderBy = "";

		// ciudad_iddestino
		$this->ciudad_iddestino = new crField('Crosstab1', 'Crosstab1', 'x_ciudad_iddestino', 'ciudad_iddestino', '`ciudad_iddestino`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->ciudad_iddestino->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['ciudad_iddestino'] =& $this->ciudad_iddestino;
		$this->ciudad_iddestino->DateFilter = "";
		$this->ciudad_iddestino->SqlSelect = "";
		$this->ciudad_iddestino->SqlOrderBy = "";

		// valor_declarado_guia
		$this->valor_declarado_guia = new crField('Crosstab1', 'Crosstab1', 'x_valor_declarado_guia', 'valor_declarado_guia', '`valor_declarado_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['valor_declarado_guia'] =& $this->valor_declarado_guia;
		$this->valor_declarado_guia->DateFilter = "";
		$this->valor_declarado_guia->SqlSelect = "";
		$this->valor_declarado_guia->SqlOrderBy = "";

		// nombre_destinatario_guia
		$this->nombre_destinatario_guia = new crField('Crosstab1', 'Crosstab1', 'x_nombre_destinatario_guia', 'nombre_destinatario_guia', '`nombre_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['nombre_destinatario_guia'] =& $this->nombre_destinatario_guia;
		$this->nombre_destinatario_guia->DateFilter = "";
		$this->nombre_destinatario_guia->SqlSelect = "";
		$this->nombre_destinatario_guia->SqlOrderBy = "";

		// direccion_destinatario_guia
		$this->direccion_destinatario_guia = new crField('Crosstab1', 'Crosstab1', 'x_direccion_destinatario_guia', 'direccion_destinatario_guia', '`direccion_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['direccion_destinatario_guia'] =& $this->direccion_destinatario_guia;
		$this->direccion_destinatario_guia->DateFilter = "";
		$this->direccion_destinatario_guia->SqlSelect = "";
		$this->direccion_destinatario_guia->SqlOrderBy = "";

		// telefono_destinatario_guia
		$this->telefono_destinatario_guia = new crField('Crosstab1', 'Crosstab1', 'x_telefono_destinatario_guia', 'telefono_destinatario_guia', '`telefono_destinatario_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['telefono_destinatario_guia'] =& $this->telefono_destinatario_guia;
		$this->telefono_destinatario_guia->DateFilter = "";
		$this->telefono_destinatario_guia->SqlSelect = "";
		$this->telefono_destinatario_guia->SqlOrderBy = "";

		// peso_guia
		$this->peso_guia = new crField('Crosstab1', 'Crosstab1', 'x_peso_guia', 'peso_guia', '`peso_guia`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['peso_guia'] =& $this->peso_guia;
		$this->peso_guia->DateFilter = "";
		$this->peso_guia->SqlSelect = "";
		$this->peso_guia->SqlOrderBy = "";

		// ciudad_idorigen
		$this->ciudad_idorigen = new crField('Crosstab1', 'Crosstab1', 'x_ciudad_idorigen', 'ciudad_idorigen', '`ciudad_idorigen`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->ciudad_idorigen->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['ciudad_idorigen'] =& $this->ciudad_idorigen;
		$this->ciudad_idorigen->DateFilter = "";
		$this->ciudad_idorigen->SqlSelect = "";
		$this->ciudad_idorigen->SqlOrderBy = "";

		// tercero_idremitente
		$this->tercero_idremitente = new crField('Crosstab1', 'Crosstab1', 'x_tercero_idremitente', 'tercero_idremitente', '`tercero_idremitente`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->tercero_idremitente->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['tercero_idremitente'] =& $this->tercero_idremitente;
		$this->tercero_idremitente->DateFilter = "";
		$this->tercero_idremitente->SqlSelect = "";
		$this->tercero_idremitente->SqlOrderBy = "";

		// tercero_iddestinatario
		$this->tercero_iddestinatario = new crField('Crosstab1', 'Crosstab1', 'x_tercero_iddestinatario', 'tercero_iddestinatario', '`tercero_iddestinatario`', 3, EWRPT_DATATYPE_NUMBER, -1);
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
	function ColumnField() { // Column field
		return "`zona_idzona`";
	}

	function ColumnDateType() { // Column date type
		return "";
	}

	function SummaryField() { // Summary field
		return "`causal_devolucion_idcausal_devolucion`";
	}

	function SummaryType() { // Summary type
		return "SUM";
	}

	function ColumnCaptions() { // Column captions
		global $ReportLanguage;
		return "";
	}

	function ColumnNames() { // Column names
		return "";
	}

	function ColumnValues() { // Column values
		return "";
	}

	function SqlFrom() { // From
		return "`guia`";
	}

	function SqlSelect() { // Select
		return "SELECT `idguia`, `numero_guia`, `orden_servicio_idorden_servicio`, <DistinctColumnFields> FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		return "";
	}

	function SqlGroupBy() { // Group By
		return "`idguia`, `numero_guia`, `orden_servicio_idorden_servicio`";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "`idguia` ASC, `numero_guia` ASC, `orden_servicio_idorden_servicio` ASC";
	}

	function SqlDistinctSelect() {
		return "SELECT DISTINCT `zona_idzona` FROM `guia`";
	}

	function SqlDistinctWhere() {
		return "";
	}

	function SqlDistinctOrderBy() {
		return "`zona_idzona` ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`idguia`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`idguia` ASC";
	}

	function SqlSelectAgg() {
		return "SELECT <DistinctColumnFields> FROM " . $this->SqlFrom();
	}

	function SqlGroupByAgg() {
		return "";
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
$Crosstab1_crosstab = new crCrosstab1_crosstab();
$Page =& $Crosstab1_crosstab;

// Page init
$Crosstab1_crosstab->Page_Init();

// Page main
$Crosstab1_crosstab->Page_Main();
?>
<?php include_once "phprptinc/header.php"; ?>
<script type="text/javascript">

// Create page object
var Crosstab1_crosstab = new ewrpt_Page("Crosstab1_crosstab");

// page properties
Crosstab1_crosstab.PageID = "crosstab"; // page ID
Crosstab1_crosstab.FormID = "fCrosstab1crosstabfilter"; // form ID
var EWRPT_PAGE_ID = Crosstab1_crosstab.PageID;

// extend page with Chart_Rendering function
Crosstab1_crosstab.Chart_Rendering =  
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// extend page with Chart_Rendered function
Crosstab1_crosstab.Chart_Rendered =  
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
<!-- Table container (begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top container (begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<p class="phpreportmaker ewTitle"><?php echo $Crosstab1->TableCaption() ?>
&nbsp;&nbsp;<?php $Crosstab1_crosstab->ExportOptions->Render("body"); ?></p>
<?php $Crosstab1_crosstab->ShowPageHeader(); ?>
<?php $Crosstab1_crosstab->ShowMessage(); ?>
<br><br>
</div></td></tr>
<!-- Top container (end) -->
<tr>
	<!-- Left container (begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- left slot -->
	</div></td>
	<!-- Left container (end) -->
	<!-- Center container (report) (begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<!-- crosstab report starts -->
<?php if ($Crosstab1->Export <> "pdf") { ?>
<div id="report_crosstab">
<table class="ewGrid" cellspacing="0"><tr>
	<td class="ewGridContent">
<?php } ?>
<div class="ewGridUpperPanel">
<form action="<?php echo ewrpt_CurrentPage() ?>" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Crosstab1_crosstab->StartGrp, $Crosstab1_crosstab->DisplayGrps, $Crosstab1_crosstab->TotalGrps) ?>
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
	<?php if ($Crosstab1_crosstab->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Crosstab1_crosstab->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Crosstab1_crosstab->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Crosstab1_crosstab->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Crosstab1_crosstab->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Crosstab1_crosstab->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Crosstab1_crosstab->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Crosstab1_crosstab->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Crosstab1_crosstab->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Crosstab1_crosstab->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($Crosstab1->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<!-- Report grid (begin) -->
<?php if ($Crosstab1->Export <> "pdf") { ?>
<div class="ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Crosstab1_crosstab->ReportTableClass ?>" cellspacing="0">
<?php if ($Crosstab1_crosstab->ShowFirstHeader) { // Show header ?>
	<thead>
	<!-- Table header -->
	<tr>
		<td class="ewRptColSummary" colspan="3" style="white-space: nowrap;"><div class="phpreportmaker"><?php echo $Crosstab1->causal_devolucion_idcausal_devolucion->FldCaption() ?>&nbsp;(<?php echo $ReportLanguage->Phrase("RptSum") ?>)&nbsp;</div></td>
		<td class="ewRptColHeader" colspan="<?php echo @$Crosstab1_crosstab->ColSpan; ?>" style="white-space: nowrap;">
			<?php echo $Crosstab1->zona_idzona->FldCaption() ?>
		</td>
	</tr>
	<tr>
<td class="ewTableHeader">
<?php if ($Crosstab1->Export <> "") { ?>
<?php echo $Crosstab1->idguia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Crosstab1->SortUrl($Crosstab1->idguia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Crosstab1->idguia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Crosstab1->SortUrl($Crosstab1->idguia) ?>',0);"><?php echo $Crosstab1->idguia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Crosstab1->idguia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Crosstab1->idguia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Crosstab1->Export <> "") { ?>
<?php echo $Crosstab1->numero_guia->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Crosstab1->SortUrl($Crosstab1->numero_guia) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Crosstab1->numero_guia->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Crosstab1->SortUrl($Crosstab1->numero_guia) ?>',0);"><?php echo $Crosstab1->numero_guia->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Crosstab1->numero_guia->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Crosstab1->numero_guia->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Crosstab1->Export <> "") { ?>
<?php echo $Crosstab1->orden_servicio_idorden_servicio->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Crosstab1->SortUrl($Crosstab1->orden_servicio_idorden_servicio) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Crosstab1->orden_servicio_idorden_servicio->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Crosstab1->SortUrl($Crosstab1->orden_servicio_idorden_servicio) ?>',0);"><?php echo $Crosstab1->orden_servicio_idorden_servicio->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Crosstab1->orden_servicio_idorden_servicio->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Crosstab1->orden_servicio_idorden_servicio->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<!-- Dynamic columns begin -->
	<?php
	$cntval = count($Crosstab1_crosstab->Val);
	for ($iy = 1; $iy < $cntval; $iy++) {
		if ($Crosstab1_crosstab->Col[$iy]->Visible) {
			$Crosstab1->SummaryCurrentValue[$iy-1] = $Crosstab1_crosstab->Col[$iy]->Caption;
			$Crosstab1->SummaryViewValue[$iy-1] = $Crosstab1->SummaryCurrentValue[$iy-1];
	?>
		<td class="ewTableHeader"<?php echo $Crosstab1->zona_idzona->CellAttributes() ?>><span<?php echo $Crosstab1->zona_idzona->ViewAttributes() ?>><?php echo $Crosstab1->SummaryViewValue[$iy-1]; ?></span></td>
	<?php
		}
	}
	?>
<!-- Dynamic columns end -->
	</tr>
	</thead>
<?php } // End show header ?>
	<tbody>
<?php
if ($Crosstab1_crosstab->TotalGrps > 0) {

// Set the last group to display if not export all
if ($Crosstab1->ExportAll && $Crosstab1->Export <> "") {
	$Crosstab1_crosstab->StopGrp = $Crosstab1_crosstab->TotalGrps;
} else {
	$Crosstab1_crosstab->StopGrp = $Crosstab1_crosstab->StartGrp + $Crosstab1_crosstab->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Crosstab1_crosstab->StopGrp) > intval($Crosstab1_crosstab->TotalGrps)) {
	$Crosstab1_crosstab->StopGrp = $Crosstab1_crosstab->TotalGrps;
}

// Navigate
$Crosstab1_crosstab->RecCount = 0;

// Get first row
if ($Crosstab1_crosstab->TotalGrps > 0) {
	$Crosstab1_crosstab->GetGrpRow(1);
	$Crosstab1_crosstab->GrpCount = 1;
}
while ($rsgrp && !$rsgrp->EOF && $Crosstab1_crosstab->GrpCount <= $Crosstab1_crosstab->DisplayGrps) {

	// Build detail SQL
	$sWhere = ewrpt_DetailFilterSQL($Crosstab1->idguia, $Crosstab1->SqlFirstGroupField(), $Crosstab1->idguia->GroupValue());
	if ($Crosstab1_crosstab->Filter != "")
		$sWhere = "($Crosstab1_crosstab->Filter) AND ($sWhere)";
	$sSql = ewrpt_BuildReportSql($Crosstab1_crosstab->SqlSelectWork, $Crosstab1->SqlWhere(), $Crosstab1->SqlGroupBy(), "", $Crosstab1->SqlOrderBy(), $sWhere, $Crosstab1_crosstab->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Crosstab1_crosstab->GetRow(1);
	while ($rs && !$rs->EOF) {
		$Crosstab1_crosstab->RecCount++;

		// Render row
		$Crosstab1->ResetCSS();
		$Crosstab1->RowType = EWRPT_ROWTYPE_DETAIL;
		$Crosstab1_crosstab->RenderRow();
?>
	<!-- Data -->
	<tr<?php echo $Crosstab1->RowAttributes(); ?>>
		<!-- idguia -->
		<td<?php echo $Crosstab1->idguia->CellAttributes(); ?>>
<span<?php echo $Crosstab1->idguia->ViewAttributes(); ?>><?php echo $Crosstab1->idguia->GroupViewValue; ?></span></td>
		<!-- numero guia -->
		<td<?php echo $Crosstab1->numero_guia->CellAttributes(); ?>>
<span<?php echo $Crosstab1->numero_guia->ViewAttributes(); ?>><?php echo $Crosstab1->numero_guia->GroupViewValue; ?></span></td>
		<!-- orden servicio idorden servicio -->
		<td<?php echo $Crosstab1->orden_servicio_idorden_servicio->CellAttributes(); ?>>
<span<?php echo $Crosstab1->orden_servicio_idorden_servicio->ViewAttributes(); ?>><?php echo $Crosstab1->orden_servicio_idorden_servicio->GroupViewValue; ?></span></td>
<!-- Dynamic columns begin -->
	<?php
		$cntcol = count($Crosstab1->SummaryViewValue);
		for ($iy = 1; $iy <= $cntcol; $iy++) {
			$bColShow = ($iy <= $Crosstab1_crosstab->ColCount) ? $Crosstab1_crosstab->Col[$iy]->Visible : TRUE;
			$sColDesc = ($iy <= $Crosstab1_crosstab->ColCount) ? $Crosstab1_crosstab->Col[$iy]->Caption : $ReportLanguage->Phrase("Summary");
			if ($bColShow) {
	?>
		<!-- <?php //echo $Crosstab1_crosstab->Col[$iy]->Caption; ?> -->
		<!-- <?php echo $sColDesc; ?> -->
		<td<?php echo $Crosstab1->SummaryCellAttributes($iy-1) ?>><span<?php echo $Crosstab1->SummaryViewAttributes($iy-1); ?>><?php echo $Crosstab1->SummaryViewValue[$iy-1]; ?></span></td>
	<?php
			}
		}
	?>
<!-- Dynamic columns end -->
	</tr>
<?php

		// Accumulate page summary
		$Crosstab1_crosstab->AccumulateSummary();

		// Get next record
		$Crosstab1_crosstab->GetRow(2);
?>
<?php
	} // End detail records loop
?>
<?php
	$Crosstab1_crosstab->GetGrpRow(2);
	$Crosstab1_crosstab->GrpCount++;
}
?>
	</tbody>
	<tfoot>
<?php } ?>
	</tfoot>
</table>
<?php if ($Crosstab1->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Crosstab1_crosstab->TotalGrps > 0) { ?>
<div class="ewGridLowerPanel">
<form action="<?php echo ewrpt_CurrentPage() ?>" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Crosstab1_crosstab->StartGrp, $Crosstab1_crosstab->DisplayGrps, $Crosstab1_crosstab->TotalGrps) ?>
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
	<?php if ($Crosstab1_crosstab->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Crosstab1_crosstab->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Crosstab1_crosstab->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Crosstab1_crosstab->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Crosstab1_crosstab->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Crosstab1_crosstab->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Crosstab1_crosstab->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Crosstab1_crosstab->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Crosstab1_crosstab->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Crosstab1_crosstab->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($Crosstab1->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<?php } ?>
<?php if ($Crosstab1->Export <> "pdf") { ?>
</td></tr></table>
</div>
<?php } ?>
<!-- Crosstab report ends -->
	</div><br></td>
	<!-- Center container (report) (end) -->
	<!-- Right container (begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
	</div></td>
	<!-- Right container (end) -->
</tr>
<!-- Bottom container (begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
	</div><br></td></tr>
<!-- Bottom container (end) -->
</table>
<!-- Table container (end) -->
<?php $Crosstab1_crosstab->ShowPageFooter(); ?>
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
$Crosstab1_crosstab->Page_Terminate();
?>
<?php

//
// Page class
//
class crCrosstab1_crosstab {

	// Page ID
	var $PageID = 'crosstab';

	// Table name
	var $TableName = 'Crosstab1';

	// Page object name
	var $PageObjName = 'Crosstab1_crosstab';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $Crosstab1;
		if ($Crosstab1->UseTokenInUrl) $PageUrl .= "t=" . $Crosstab1->TableVar . "&"; // Add page token
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
		global $Crosstab1;
		if ($Crosstab1->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($Crosstab1->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($Crosstab1->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crCrosstab1_crosstab() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (Crosstab1)
		$GLOBALS["Crosstab1"] = new crCrosstab1();
		$GLOBALS["Table"] =& $GLOBALS["Crosstab1"];

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'crosstab', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'Crosstab1', TRUE);

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
		global $Crosstab1;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$Crosstab1->Export = $_GET["export"];
		}
		$gsExport = $Crosstab1->Export; // Get export parameter, used in header
		$gsExportFile = $Crosstab1->TableVar; // Get export file, used in header

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $ReportLanguage, $Crosstab1;

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
		$item->Body = "<a name=\"emf_Crosstab1\" id=\"emf_Crosstab1\" href=\"javascript:void(0);\" onclick=\"ewrpt_EmailDialogShow({lnk:'emf_Crosstab1',hdr:ewLanguage.Phrase('ExportToEmail')});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Reset filter
		$item =& $this->ExportOptions->Add("resetfilter");
		$item->Body = "<a href=\"" . ewrpt_CurrentPage() . "?cmd=reset\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</a>";
		$item->Visible = FALSE;
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($Crosstab1->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Set up table class
		if ($Crosstab1->Export == "word" || $Crosstab1->Export == "excel" || $Crosstab1->Export == "pdf")
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
		global $Crosstab1;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($Crosstab1->Export == "email") {
			$sContent = ob_get_contents();
			$this->ExportEmail($sContent);
			ob_end_clean();

			 // Close connection
			$conn->Close();
			header("Location: " . ewrpt_CurrentPage());
			exit();
		}

		// Export to PDF (use ob_file_contents for PHP)
		if ($Crosstab1->Export == "pdf") {
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
	var $Cnt, $Col, $Val, $Smry;
	var $ColCount, $ColSpan;
	var $SqlSelectWork, $SqlSelectAggWork;
	var $SqlChartWork;

	//
	// Page main
	//
	function Page_Main() {
		global $Crosstab1;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Get sort
		$this->Sort = $this->GetSort();

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Popup values and selections
		// Load custom filters

		$Crosstab1->Filters_Load();

		// Set up popup filter
		$this->SetupPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Load columns to array
		$this->GetColumns();

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

		// Get total group count
		$sGrpSort = ewrpt_UpdateSortFields($Crosstab1->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewrpt_BuildReportSql($Crosstab1->SqlSelectGroup(), $Crosstab1->SqlWhere(), $Crosstab1->SqlGroupBy(), "", $Crosstab1->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($Crosstab1->ExportAll && $Crosstab1->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup();

		// Hide all options if export
		if ($Crosstab1->Export <> "") {
			$this->ExportOptions->HideAllOptions();
		}

		// Get total groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;

		// Set up column attributes
		$Crosstab1->zona_idzona->ViewAttrs["style"] = "";
		$Crosstab1->zona_idzona->CellAttrs["style"] = "vertical-align: top;";
	}

	// Get column values
	function GetColumns() {
		global $conn;
		global $Crosstab1;
		global $ReportLanguage;

		// Build SQL
		$sSql = ewrpt_BuildReportSql($Crosstab1->SqlDistinctSelect(), $Crosstab1->SqlDistinctWhere(), "", "", $Crosstab1->SqlDistinctOrderBy(), $this->Filter, "");

		// Load recordset
		$rscol = $conn->Execute($sSql);

		// Get distinct column count
		$this->ColCount = ($rscol) ? $rscol->RecordCount() : 0;

/* Uncomment to show phrase
		if ($this->ColCount == 0) {
			if ($rscol) $rscol->Close();
			echo $ReportLanguage->Phrase("NoDistinctColVals") . $sSql . "<br>";
			exit();
		}
*/

		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of distinct values

		$nGrps = 3;
		$this->Col =& ewrpt_InitArray($this->ColCount+1, NULL);
		$this->Val =& ewrpt_InitArray($this->ColCount+1, NULL);
		$this->ValCnt =& ewrpt_InitArray($this->ColCount+1, NULL);
		$this->Cnt =& ewrpt_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->Smry =& ewrpt_Init2DArray($this->ColCount+1, $nGrps+1, NULL);
		$this->SmryCnt =& ewrpt_Init2DArray($this->ColCount+1, $nGrps+1, NULL);

		// Reset summary values
		$this->ResetLevelSummary(0);
		$colcnt = 0;
		while (!$rscol->EOF) {
			if (is_null($rscol->fields[0])) {
				$wrkValue = EWRPT_NULL_VALUE;
				$wrkCaption = $ReportLanguage->Phrase("NullLabel");
			} elseif ($rscol->fields[0] == "") {
				$wrkValue = EWRPT_EMPTY_VALUE;
				$wrkCaption = $ReportLanguage->Phrase("EmptyLabel");
			} else {
				$wrkValue = $rscol->fields[0];
				$wrkCaption = $rscol->fields[0];
			}
			$colcnt++;
			$this->Col[$colcnt] = new crCrosstabColumn($wrkValue, $wrkCaption, TRUE);
			$rscol->MoveNext();
		}
		$rscol->Close();

		// Get active columns
		if (!is_array($Crosstab1->zona_idzona->SelectionList)) {
			$this->ColSpan = $this->ColCount;
		} else {
			$this->ColSpan = 0;
			for ($i = 1; $i <= $this->ColCount; $i++) {
				$bSelected = FALSE;
				$cntsel = count($Crosstab1->zona_idzona->SelectionList);
				for ($j = 0; $j < $cntsel; $j++) {
					if (ewrpt_CompareValue($Crosstab1->zona_idzona->SelectionList[$j], $this->Col[$i]->Value, $Crosstab1->zona_idzona->FldType)) {
						$this->ColSpan++;
						$bSelected = TRUE;
						break;
					}
				}
				$this->Col[$i]->Visible = $bSelected;
			}
		}

		// Update crosstab sql
		$sSqlFlds = "";
		for ($colcnt = 1; $colcnt <= $this->ColCount; $colcnt++) {
			$sFld = ewrpt_CrossTabField($Crosstab1->SummaryType(), $Crosstab1->SummaryField(), $Crosstab1->ColumnField(), $Crosstab1->ColumnDateType(), $this->Col[$colcnt]->Value, "", "C" . $colcnt);
			if ($sSqlFlds <> "")
				$sSqlFlds .= ", ";
			$sSqlFlds .= $sFld;
		}
		$this->SqlSelectWork = str_replace("<DistinctColumnFields>", $sSqlFlds, $Crosstab1->SqlSelect());
		$this->SqlSelectAggWork = str_replace("<DistinctColumnFields>", $sSqlFlds, $Crosstab1->SqlSelectAgg());

		// Update chart sql if Y Axis = Column Field
		$this->SqlChartWork = "";
		for ($i = 0; $i < $this->ColCount; $i++) {
			if ($this->Col[$i+1]->Visible) {
				$sChtFld = ewrpt_CrossTabField("SUM", $Crosstab1->SummaryField(), $Crosstab1->ColumnField(), $Crosstab1->ColumnDateType(), $this->Col[$i+1]->Value, "");
				if ($this->SqlChartWork != "") $this->SqlChartWork .= "+";
				$this->SqlChartWork .= $sChtFld;
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		global $conn;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		global $Crosstab1;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

	//		$rsgrp->MoveFirst(); // NOTE: no need to move position
			$Crosstab1->idguia->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF) {
			$Crosstab1->idguia->setDbValue($rsgrp->fields[0]);
		} else {
			$Crosstab1->idguia->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $Crosstab1;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1)
				$Crosstab1->idguia->setDbValue($rs->fields('idguia'));
			$Crosstab1->numero_guia->setDbValue($rs->fields('numero_guia'));
			$Crosstab1->orden_servicio_idorden_servicio->setDbValue($rs->fields('orden_servicio_idorden_servicio'));
			$cntval = count($this->Val);
			for ($ix = 1; $ix < $cntval; $ix++)
				$this->Val[$ix] = $rs->fields[$ix+3-1];
		} else {
			$Crosstab1->idguia->setDbValue("");
			$Crosstab1->numero_guia->setDbValue("");
			$Crosstab1->orden_servicio_idorden_servicio->setDbValue("");
		}
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		global $Crosstab1;
		switch ($lvl) {
			case 1:
				return (is_null($Crosstab1->idguia->CurrentValue) && !is_null($Crosstab1->idguia->OldValue)) ||
					(!is_null($Crosstab1->idguia->CurrentValue) && is_null($Crosstab1->idguia->OldValue)) ||
					($Crosstab1->idguia->GroupValue() <> $Crosstab1->idguia->GroupOldValue());
			case 2:
				return (is_null($Crosstab1->numero_guia->CurrentValue) && !is_null($Crosstab1->numero_guia->OldValue)) ||
					(!is_null($Crosstab1->numero_guia->CurrentValue) && is_null($Crosstab1->numero_guia->OldValue)) ||
					($Crosstab1->numero_guia->GroupValue() <> $Crosstab1->numero_guia->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($Crosstab1->orden_servicio_idorden_servicio->CurrentValue) && !is_null($Crosstab1->orden_servicio_idorden_servicio->OldValue)) ||
					(!is_null($Crosstab1->orden_servicio_idorden_servicio->CurrentValue) && is_null($Crosstab1->orden_servicio_idorden_servicio->OldValue)) ||
					($Crosstab1->orden_servicio_idorden_servicio->GroupValue() <> $Crosstab1->orden_servicio_idorden_servicio->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		global $Crosstab1;
		$cntx = count($this->Smry);
		for ($ix = 1; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 0; $iy < $cnty; $iy++) {
				$valwrk = $this->Val[$ix];
				$this->Cnt[$ix][$iy]++;
				$this->Smry[$ix][$iy] = ewrpt_SummaryValue($this->Smry[$ix][$iy], $valwrk, $Crosstab1->SummaryType());
			}
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = 1; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = $lvl; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				$this->Smry[$ix][$iy] = 0;
			}
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Set up starting group
	function SetUpStartGroup() {
		global $Crosstab1;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$Crosstab1->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$Crosstab1->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $Crosstab1->getStartGroup();
			}
		} else {
			$this->StartGrp = $Crosstab1->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$Crosstab1->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$Crosstab1->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$Crosstab1->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $Crosstab1;

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
		global $Crosstab1;

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$Crosstab1->setStartGroup($this->StartGrp);
	}

	// Check if any column values is present
	function HasColumnValues(&$rs) {
		$cntcol = count($this->Col);
		for ($i = 1; $i < $cntcol; $i++) {
			if ($this->Col[$i]->Visible) {
				if ($rs->fields[3+$i-1] <> 0) return TRUE;
			}
		}
		return FALSE;
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $Crosstab1;
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
			$Crosstab1->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$Crosstab1->setStartGroup($this->StartGrp);
		} else {
			if ($Crosstab1->getGroupPerPage() <> "") {
				$this->DisplayGrps = $Crosstab1->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $Security;
		global $Crosstab1;

		// Set up summary values
		$colcnt = $this->ColCount;
		$Crosstab1->SummaryCellAttrs =& ewrpt_InitArray($colcnt, NULL);
		$Crosstab1->SummaryViewAttrs =& ewrpt_InitArray($colcnt, NULL);
		$Crosstab1->SummaryCurrentValue =& ewrpt_InitArray($colcnt, NULL);
		$Crosstab1->SummaryViewValue =& ewrpt_InitArray($colcnt, NULL);
		if ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// aggregate sql
			$sSql = ewrpt_BuildReportSql($this->SqlSelectAggWork, $Crosstab1->SqlWhere(), $Crosstab1->SqlGroupByAgg(), "", "", $this->Filter, "");
			$rsagg = $conn->Execute($sSql);
			if ($rsagg && !$rsagg->EOF) $rsagg->MoveFirst();
		}
		for ($i = 1; $i <= $this->ColCount; $i++) {
			if ($this->Col[$i]->Visible) {
				if ($Crosstab1->RowType == EWRPT_ROWTYPE_DETAIL) { // Detail row
					$thisval = $this->Val[$i];
				} elseif ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_GROUP) { // Group total
					$thisval = $this->Smry[$i][$Crosstab1->RowGroupLevel];
				} elseif ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_PAGE) { // Page total
					$thisval = $this->Smry[$i][0];
				} elseif ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total
					$thisval = ($rsagg && !$rsagg->EOF) ? $rsagg->fields[$i+0-1] : 0;
				}
				$Crosstab1->SummaryCurrentValue[$i-1] = $thisval;
			}
		}
		if ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total
			if ($rsagg) $rsagg->Close();
		}

		// Call Row_Rendering event
		$Crosstab1->Row_Rendering();

		//
		//  Render view codes
		//

		if ($Crosstab1->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// idguia
			$Crosstab1->idguia->GroupViewValue = $Crosstab1->idguia->GroupOldValue();
			$Crosstab1->idguia->CellAttrs["class"] = ($Crosstab1->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";

			// numero_guia
			$Crosstab1->numero_guia->GroupViewValue = $Crosstab1->numero_guia->GroupOldValue();
			$Crosstab1->numero_guia->CellAttrs["class"] = ($Crosstab1->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";

			// orden_servicio_idorden_servicio
			$Crosstab1->orden_servicio_idorden_servicio->GroupViewValue = $Crosstab1->orden_servicio_idorden_servicio->GroupOldValue();
			$Crosstab1->orden_servicio_idorden_servicio->CellAttrs["class"] = ($Crosstab1->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";

			// Set up summary values
			$scvcnt = count($Crosstab1->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$Crosstab1->SummaryViewValue[$i] = $Crosstab1->SummaryCurrentValue[$i];
				$Crosstab1->SummaryViewAttrs[$i]["style"] = "";
				$Crosstab1->SummaryCellAttrs[$i]["style"] = "";
				$Crosstab1->SummaryCellAttrs[$i]["class"] = ($Crosstab1->RowTotalType == EWRPT_ROWTOTAL_GROUP) ? "ewRptGrpSummary" . $Crosstab1->RowGroupLevel : "";
			}

			// idguia
			$Crosstab1->idguia->HrefValue = "";

			// numero_guia
			$Crosstab1->numero_guia->HrefValue = "";

			// orden_servicio_idorden_servicio
			$Crosstab1->orden_servicio_idorden_servicio->HrefValue = "";
		} else {

			// idguia
			$Crosstab1->idguia->GroupViewValue = $Crosstab1->idguia->GroupValue();
			$Crosstab1->idguia->CellAttrs["class"] = "ewRptGrpField1";
			if ($Crosstab1->idguia->GroupValue() == $Crosstab1->idguia->GroupOldValue() && !$this->ChkLvlBreak(1))
				$Crosstab1->idguia->GroupViewValue = "&nbsp;";

			// numero_guia
			$Crosstab1->numero_guia->GroupViewValue = $Crosstab1->numero_guia->GroupValue();
			$Crosstab1->numero_guia->CellAttrs["class"] = "ewRptGrpField2";
			if ($Crosstab1->numero_guia->GroupValue() == $Crosstab1->numero_guia->GroupOldValue() && !$this->ChkLvlBreak(2))
				$Crosstab1->numero_guia->GroupViewValue = "&nbsp;";

			// orden_servicio_idorden_servicio
			$Crosstab1->orden_servicio_idorden_servicio->GroupViewValue = $Crosstab1->orden_servicio_idorden_servicio->GroupValue();
			$Crosstab1->orden_servicio_idorden_servicio->CellAttrs["class"] = "ewRptGrpField3";
			if ($Crosstab1->orden_servicio_idorden_servicio->GroupValue() == $Crosstab1->orden_servicio_idorden_servicio->GroupOldValue() && !$this->ChkLvlBreak(3))
				$Crosstab1->orden_servicio_idorden_servicio->GroupViewValue = "&nbsp;";

			// Set up summary values
			$scvcnt = count($Crosstab1->SummaryCurrentValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$Crosstab1->SummaryViewValue[$i] = $Crosstab1->SummaryCurrentValue[$i];
				$Crosstab1->SummaryViewAttrs[$i]["style"] = "";
				$Crosstab1->SummaryCellAttrs[$i]["style"] = "";
				$Crosstab1->SummaryCellAttrs[$i]["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			}

			// idguia
			$Crosstab1->idguia->HrefValue = "";

			// numero_guia
			$Crosstab1->numero_guia->HrefValue = "";

			// orden_servicio_idorden_servicio
			$Crosstab1->orden_servicio_idorden_servicio->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($Crosstab1->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// idguia
			$CurrentValue = $Crosstab1->idguia->GroupOldValue();
			$ViewValue =& $Crosstab1->idguia->GroupViewValue;
			$ViewAttrs =& $Crosstab1->idguia->ViewAttrs;
			$CellAttrs =& $Crosstab1->idguia->CellAttrs;
			$HrefValue =& $Crosstab1->idguia->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->idguia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// numero_guia
			$CurrentValue = $Crosstab1->numero_guia->GroupOldValue();
			$ViewValue =& $Crosstab1->numero_guia->GroupViewValue;
			$ViewAttrs =& $Crosstab1->numero_guia->ViewAttrs;
			$CellAttrs =& $Crosstab1->numero_guia->CellAttrs;
			$HrefValue =& $Crosstab1->numero_guia->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->numero_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// orden_servicio_idorden_servicio
			$CurrentValue = $Crosstab1->orden_servicio_idorden_servicio->GroupOldValue();
			$ViewValue =& $Crosstab1->orden_servicio_idorden_servicio->GroupViewValue;
			$ViewAttrs =& $Crosstab1->orden_servicio_idorden_servicio->ViewAttrs;
			$CellAttrs =& $Crosstab1->orden_servicio_idorden_servicio->CellAttrs;
			$HrefValue =& $Crosstab1->orden_servicio_idorden_servicio->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->orden_servicio_idorden_servicio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$CurrentValue = $Crosstab1->SummaryCurrentValue[$i];
				$ViewValue =& $Crosstab1->SummaryViewValue[$i];
				$ViewAttrs =& $Crosstab1->SummaryViewAttrs[$i];
				$CellAttrs =& $Crosstab1->SummaryCellAttrs[$i];
				$Crosstab1->Cell_Rendered($Crosstab1->causal_devolucion_idcausal_devolucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);
			}
		} else {

			// idguia
			$CurrentValue = $Crosstab1->idguia->GroupValue();
			$ViewValue =& $Crosstab1->idguia->GroupViewValue;
			$ViewAttrs =& $Crosstab1->idguia->ViewAttrs;
			$CellAttrs =& $Crosstab1->idguia->CellAttrs;
			$HrefValue =& $Crosstab1->idguia->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->idguia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// numero_guia
			$CurrentValue = $Crosstab1->numero_guia->GroupValue();
			$ViewValue =& $Crosstab1->numero_guia->GroupViewValue;
			$ViewAttrs =& $Crosstab1->numero_guia->ViewAttrs;
			$CellAttrs =& $Crosstab1->numero_guia->CellAttrs;
			$HrefValue =& $Crosstab1->numero_guia->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->numero_guia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);

			// orden_servicio_idorden_servicio
			$CurrentValue = $Crosstab1->orden_servicio_idorden_servicio->GroupValue();
			$ViewValue =& $Crosstab1->orden_servicio_idorden_servicio->GroupViewValue;
			$ViewAttrs =& $Crosstab1->orden_servicio_idorden_servicio->ViewAttrs;
			$CellAttrs =& $Crosstab1->orden_servicio_idorden_servicio->CellAttrs;
			$HrefValue =& $Crosstab1->orden_servicio_idorden_servicio->HrefValue;
			$Crosstab1->Cell_Rendered($Crosstab1->orden_servicio_idorden_servicio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);
			for ($i = 0; $i < $scvcnt; $i++) {
				$CurrentValue = $Crosstab1->SummaryCurrentValue[$i];
				$ViewValue =& $Crosstab1->SummaryViewValue[$i];
				$ViewAttrs =& $Crosstab1->SummaryViewAttrs[$i];
				$CellAttrs =& $Crosstab1->SummaryCellAttrs[$i];
				$Crosstab1->Cell_Rendered($Crosstab1->causal_devolucion_idcausal_devolucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue);
			}
		}

		// Call Row_Rendered event
		$Crosstab1->Row_Rendered();
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $Crosstab1;
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
	}

	// Return poup filter
	function GetPopupFilter() {
		global $Crosstab1;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $Crosstab1;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$Crosstab1->setOrderBy("");
				$Crosstab1->setStartGroup(1);
				$Crosstab1->idguia->setSort("");
				$Crosstab1->numero_guia->setSort("");
				$Crosstab1->orden_servicio_idorden_servicio->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$Crosstab1->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$Crosstab1->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $Crosstab1->SortSql();
			$Crosstab1->setOrderBy($sSortSql);
			$Crosstab1->setStartGroup(1);
		}
		return $Crosstab1->getOrderBy();
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
