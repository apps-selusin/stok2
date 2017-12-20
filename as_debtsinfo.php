<?php

// Global variable for table object
$as_debts = NULL;

//
// Table class for as_debts
//
class cas_debts extends cTable {
	var $debtID;
	var $debtNo;
	var $invoiceID;
	var $invoiceNo;
	var $supplierID;
	var $supplierName;
	var $supplierAddress;
	var $debtTotal;
	var $incomingTotal;
	var $reductionTotal;
	var $status;
	var $staffID;
	var $staffName;
	var $createdDate;
	var $createdUserID;
	var $modifiedDate;
	var $modifiedUserID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'as_debts';
		$this->TableName = 'as_debts';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`as_debts`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// debtID
		$this->debtID = new cField('as_debts', 'as_debts', 'x_debtID', 'debtID', '`debtID`', '`debtID`', 3, -1, FALSE, '`debtID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->debtID->Sortable = TRUE; // Allow sort
		$this->debtID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['debtID'] = &$this->debtID;

		// debtNo
		$this->debtNo = new cField('as_debts', 'as_debts', 'x_debtNo', 'debtNo', '`debtNo`', '`debtNo`', 200, -1, FALSE, '`debtNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debtNo->Sortable = TRUE; // Allow sort
		$this->fields['debtNo'] = &$this->debtNo;

		// invoiceID
		$this->invoiceID = new cField('as_debts', 'as_debts', 'x_invoiceID', 'invoiceID', '`invoiceID`', '`invoiceID`', 3, -1, FALSE, '`invoiceID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->invoiceID->Sortable = TRUE; // Allow sort
		$this->invoiceID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoiceID'] = &$this->invoiceID;

		// invoiceNo
		$this->invoiceNo = new cField('as_debts', 'as_debts', 'x_invoiceNo', 'invoiceNo', '`invoiceNo`', '`invoiceNo`', 200, -1, FALSE, '`invoiceNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->invoiceNo->Sortable = TRUE; // Allow sort
		$this->fields['invoiceNo'] = &$this->invoiceNo;

		// supplierID
		$this->supplierID = new cField('as_debts', 'as_debts', 'x_supplierID', 'supplierID', '`supplierID`', '`supplierID`', 3, -1, FALSE, '`supplierID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->supplierID->Sortable = TRUE; // Allow sort
		$this->supplierID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['supplierID'] = &$this->supplierID;

		// supplierName
		$this->supplierName = new cField('as_debts', 'as_debts', 'x_supplierName', 'supplierName', '`supplierName`', '`supplierName`', 200, -1, FALSE, '`supplierName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->supplierName->Sortable = TRUE; // Allow sort
		$this->fields['supplierName'] = &$this->supplierName;

		// supplierAddress
		$this->supplierAddress = new cField('as_debts', 'as_debts', 'x_supplierAddress', 'supplierAddress', '`supplierAddress`', '`supplierAddress`', 201, -1, FALSE, '`supplierAddress`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->supplierAddress->Sortable = TRUE; // Allow sort
		$this->fields['supplierAddress'] = &$this->supplierAddress;

		// debtTotal
		$this->debtTotal = new cField('as_debts', 'as_debts', 'x_debtTotal', 'debtTotal', '`debtTotal`', '`debtTotal`', 5, -1, FALSE, '`debtTotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debtTotal->Sortable = TRUE; // Allow sort
		$this->debtTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debtTotal'] = &$this->debtTotal;

		// incomingTotal
		$this->incomingTotal = new cField('as_debts', 'as_debts', 'x_incomingTotal', 'incomingTotal', '`incomingTotal`', '`incomingTotal`', 5, -1, FALSE, '`incomingTotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->incomingTotal->Sortable = TRUE; // Allow sort
		$this->incomingTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['incomingTotal'] = &$this->incomingTotal;

		// reductionTotal
		$this->reductionTotal = new cField('as_debts', 'as_debts', 'x_reductionTotal', 'reductionTotal', '`reductionTotal`', '`reductionTotal`', 5, -1, FALSE, '`reductionTotal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reductionTotal->Sortable = TRUE; // Allow sort
		$this->reductionTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['reductionTotal'] = &$this->reductionTotal;

		// status
		$this->status = new cField('as_debts', 'as_debts', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// staffID
		$this->staffID = new cField('as_debts', 'as_debts', 'x_staffID', 'staffID', '`staffID`', '`staffID`', 3, -1, FALSE, '`staffID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffID->Sortable = TRUE; // Allow sort
		$this->staffID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staffID'] = &$this->staffID;

		// staffName
		$this->staffName = new cField('as_debts', 'as_debts', 'x_staffName', 'staffName', '`staffName`', '`staffName`', 200, -1, FALSE, '`staffName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffName->Sortable = TRUE; // Allow sort
		$this->fields['staffName'] = &$this->staffName;

		// createdDate
		$this->createdDate = new cField('as_debts', 'as_debts', 'x_createdDate', 'createdDate', '`createdDate`', ew_CastDateFieldForLike('`createdDate`', 0, "DB"), 135, 0, FALSE, '`createdDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdDate->Sortable = TRUE; // Allow sort
		$this->createdDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['createdDate'] = &$this->createdDate;

		// createdUserID
		$this->createdUserID = new cField('as_debts', 'as_debts', 'x_createdUserID', 'createdUserID', '`createdUserID`', '`createdUserID`', 3, -1, FALSE, '`createdUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdUserID->Sortable = TRUE; // Allow sort
		$this->createdUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['createdUserID'] = &$this->createdUserID;

		// modifiedDate
		$this->modifiedDate = new cField('as_debts', 'as_debts', 'x_modifiedDate', 'modifiedDate', '`modifiedDate`', ew_CastDateFieldForLike('`modifiedDate`', 0, "DB"), 135, 0, FALSE, '`modifiedDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedDate->Sortable = TRUE; // Allow sort
		$this->modifiedDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modifiedDate'] = &$this->modifiedDate;

		// modifiedUserID
		$this->modifiedUserID = new cField('as_debts', 'as_debts', 'x_modifiedUserID', 'modifiedUserID', '`modifiedUserID`', '`modifiedUserID`', 3, -1, FALSE, '`modifiedUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedUserID->Sortable = TRUE; // Allow sort
		$this->modifiedUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modifiedUserID'] = &$this->modifiedUserID;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`as_debts`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->debtID->setDbValue($conn->Insert_ID());
			$rs['debtID'] = $this->debtID->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('debtID', $rs))
				ew_AddFilter($where, ew_QuotedName('debtID', $this->DBID) . '=' . ew_QuotedValue($rs['debtID'], $this->debtID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`debtID` = @debtID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->debtID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@debtID@", ew_AdjustSql($this->debtID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "as_debtslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "as_debtslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("as_debtsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("as_debtsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "as_debtsadd.php?" . $this->UrlParm($parm);
		else
			$url = "as_debtsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("as_debtsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("as_debtsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("as_debtsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "debtID:" . ew_VarToJson($this->debtID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->debtID->CurrentValue)) {
			$sUrl .= "debtID=" . urlencode($this->debtID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["debtID"]))
				$arKeys[] = ew_StripSlashes($_POST["debtID"]);
			elseif (isset($_GET["debtID"]))
				$arKeys[] = ew_StripSlashes($_GET["debtID"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->debtID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->debtID->setDbValue($rs->fields('debtID'));
		$this->debtNo->setDbValue($rs->fields('debtNo'));
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->supplierID->setDbValue($rs->fields('supplierID'));
		$this->supplierName->setDbValue($rs->fields('supplierName'));
		$this->supplierAddress->setDbValue($rs->fields('supplierAddress'));
		$this->debtTotal->setDbValue($rs->fields('debtTotal'));
		$this->incomingTotal->setDbValue($rs->fields('incomingTotal'));
		$this->reductionTotal->setDbValue($rs->fields('reductionTotal'));
		$this->status->setDbValue($rs->fields('status'));
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// debtID
		// debtNo
		// invoiceID
		// invoiceNo
		// supplierID
		// supplierName
		// supplierAddress
		// debtTotal
		// incomingTotal
		// reductionTotal
		// status
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID
		// debtID

		$this->debtID->ViewValue = $this->debtID->CurrentValue;
		$this->debtID->ViewCustomAttributes = "";

		// debtNo
		$this->debtNo->ViewValue = $this->debtNo->CurrentValue;
		$this->debtNo->ViewCustomAttributes = "";

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// supplierID
		$this->supplierID->ViewValue = $this->supplierID->CurrentValue;
		$this->supplierID->ViewCustomAttributes = "";

		// supplierName
		$this->supplierName->ViewValue = $this->supplierName->CurrentValue;
		$this->supplierName->ViewCustomAttributes = "";

		// supplierAddress
		$this->supplierAddress->ViewValue = $this->supplierAddress->CurrentValue;
		$this->supplierAddress->ViewCustomAttributes = "";

		// debtTotal
		$this->debtTotal->ViewValue = $this->debtTotal->CurrentValue;
		$this->debtTotal->ViewCustomAttributes = "";

		// incomingTotal
		$this->incomingTotal->ViewValue = $this->incomingTotal->CurrentValue;
		$this->incomingTotal->ViewCustomAttributes = "";

		// reductionTotal
		$this->reductionTotal->ViewValue = $this->reductionTotal->CurrentValue;
		$this->reductionTotal->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// createdDate
		$this->createdDate->ViewValue = $this->createdDate->CurrentValue;
		$this->createdDate->ViewValue = ew_FormatDateTime($this->createdDate->ViewValue, 0);
		$this->createdDate->ViewCustomAttributes = "";

		// createdUserID
		$this->createdUserID->ViewValue = $this->createdUserID->CurrentValue;
		$this->createdUserID->ViewCustomAttributes = "";

		// modifiedDate
		$this->modifiedDate->ViewValue = $this->modifiedDate->CurrentValue;
		$this->modifiedDate->ViewValue = ew_FormatDateTime($this->modifiedDate->ViewValue, 0);
		$this->modifiedDate->ViewCustomAttributes = "";

		// modifiedUserID
		$this->modifiedUserID->ViewValue = $this->modifiedUserID->CurrentValue;
		$this->modifiedUserID->ViewCustomAttributes = "";

		// debtID
		$this->debtID->LinkCustomAttributes = "";
		$this->debtID->HrefValue = "";
		$this->debtID->TooltipValue = "";

		// debtNo
		$this->debtNo->LinkCustomAttributes = "";
		$this->debtNo->HrefValue = "";
		$this->debtNo->TooltipValue = "";

		// invoiceID
		$this->invoiceID->LinkCustomAttributes = "";
		$this->invoiceID->HrefValue = "";
		$this->invoiceID->TooltipValue = "";

		// invoiceNo
		$this->invoiceNo->LinkCustomAttributes = "";
		$this->invoiceNo->HrefValue = "";
		$this->invoiceNo->TooltipValue = "";

		// supplierID
		$this->supplierID->LinkCustomAttributes = "";
		$this->supplierID->HrefValue = "";
		$this->supplierID->TooltipValue = "";

		// supplierName
		$this->supplierName->LinkCustomAttributes = "";
		$this->supplierName->HrefValue = "";
		$this->supplierName->TooltipValue = "";

		// supplierAddress
		$this->supplierAddress->LinkCustomAttributes = "";
		$this->supplierAddress->HrefValue = "";
		$this->supplierAddress->TooltipValue = "";

		// debtTotal
		$this->debtTotal->LinkCustomAttributes = "";
		$this->debtTotal->HrefValue = "";
		$this->debtTotal->TooltipValue = "";

		// incomingTotal
		$this->incomingTotal->LinkCustomAttributes = "";
		$this->incomingTotal->HrefValue = "";
		$this->incomingTotal->TooltipValue = "";

		// reductionTotal
		$this->reductionTotal->LinkCustomAttributes = "";
		$this->reductionTotal->HrefValue = "";
		$this->reductionTotal->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// staffID
		$this->staffID->LinkCustomAttributes = "";
		$this->staffID->HrefValue = "";
		$this->staffID->TooltipValue = "";

		// staffName
		$this->staffName->LinkCustomAttributes = "";
		$this->staffName->HrefValue = "";
		$this->staffName->TooltipValue = "";

		// createdDate
		$this->createdDate->LinkCustomAttributes = "";
		$this->createdDate->HrefValue = "";
		$this->createdDate->TooltipValue = "";

		// createdUserID
		$this->createdUserID->LinkCustomAttributes = "";
		$this->createdUserID->HrefValue = "";
		$this->createdUserID->TooltipValue = "";

		// modifiedDate
		$this->modifiedDate->LinkCustomAttributes = "";
		$this->modifiedDate->HrefValue = "";
		$this->modifiedDate->TooltipValue = "";

		// modifiedUserID
		$this->modifiedUserID->LinkCustomAttributes = "";
		$this->modifiedUserID->HrefValue = "";
		$this->modifiedUserID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// debtID
		$this->debtID->EditAttrs["class"] = "form-control";
		$this->debtID->EditCustomAttributes = "";
		$this->debtID->EditValue = $this->debtID->CurrentValue;
		$this->debtID->ViewCustomAttributes = "";

		// debtNo
		$this->debtNo->EditAttrs["class"] = "form-control";
		$this->debtNo->EditCustomAttributes = "";
		$this->debtNo->EditValue = $this->debtNo->CurrentValue;
		$this->debtNo->PlaceHolder = ew_RemoveHtml($this->debtNo->FldCaption());

		// invoiceID
		$this->invoiceID->EditAttrs["class"] = "form-control";
		$this->invoiceID->EditCustomAttributes = "";
		$this->invoiceID->EditValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->PlaceHolder = ew_RemoveHtml($this->invoiceID->FldCaption());

		// invoiceNo
		$this->invoiceNo->EditAttrs["class"] = "form-control";
		$this->invoiceNo->EditCustomAttributes = "";
		$this->invoiceNo->EditValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->PlaceHolder = ew_RemoveHtml($this->invoiceNo->FldCaption());

		// supplierID
		$this->supplierID->EditAttrs["class"] = "form-control";
		$this->supplierID->EditCustomAttributes = "";
		$this->supplierID->EditValue = $this->supplierID->CurrentValue;
		$this->supplierID->PlaceHolder = ew_RemoveHtml($this->supplierID->FldCaption());

		// supplierName
		$this->supplierName->EditAttrs["class"] = "form-control";
		$this->supplierName->EditCustomAttributes = "";
		$this->supplierName->EditValue = $this->supplierName->CurrentValue;
		$this->supplierName->PlaceHolder = ew_RemoveHtml($this->supplierName->FldCaption());

		// supplierAddress
		$this->supplierAddress->EditAttrs["class"] = "form-control";
		$this->supplierAddress->EditCustomAttributes = "";
		$this->supplierAddress->EditValue = $this->supplierAddress->CurrentValue;
		$this->supplierAddress->PlaceHolder = ew_RemoveHtml($this->supplierAddress->FldCaption());

		// debtTotal
		$this->debtTotal->EditAttrs["class"] = "form-control";
		$this->debtTotal->EditCustomAttributes = "";
		$this->debtTotal->EditValue = $this->debtTotal->CurrentValue;
		$this->debtTotal->PlaceHolder = ew_RemoveHtml($this->debtTotal->FldCaption());
		if (strval($this->debtTotal->EditValue) <> "" && is_numeric($this->debtTotal->EditValue)) $this->debtTotal->EditValue = ew_FormatNumber($this->debtTotal->EditValue, -2, -1, -2, 0);

		// incomingTotal
		$this->incomingTotal->EditAttrs["class"] = "form-control";
		$this->incomingTotal->EditCustomAttributes = "";
		$this->incomingTotal->EditValue = $this->incomingTotal->CurrentValue;
		$this->incomingTotal->PlaceHolder = ew_RemoveHtml($this->incomingTotal->FldCaption());
		if (strval($this->incomingTotal->EditValue) <> "" && is_numeric($this->incomingTotal->EditValue)) $this->incomingTotal->EditValue = ew_FormatNumber($this->incomingTotal->EditValue, -2, -1, -2, 0);

		// reductionTotal
		$this->reductionTotal->EditAttrs["class"] = "form-control";
		$this->reductionTotal->EditCustomAttributes = "";
		$this->reductionTotal->EditValue = $this->reductionTotal->CurrentValue;
		$this->reductionTotal->PlaceHolder = ew_RemoveHtml($this->reductionTotal->FldCaption());
		if (strval($this->reductionTotal->EditValue) <> "" && is_numeric($this->reductionTotal->EditValue)) $this->reductionTotal->EditValue = ew_FormatNumber($this->reductionTotal->EditValue, -2, -1, -2, 0);

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// staffID
		$this->staffID->EditAttrs["class"] = "form-control";
		$this->staffID->EditCustomAttributes = "";
		$this->staffID->EditValue = $this->staffID->CurrentValue;
		$this->staffID->PlaceHolder = ew_RemoveHtml($this->staffID->FldCaption());

		// staffName
		$this->staffName->EditAttrs["class"] = "form-control";
		$this->staffName->EditCustomAttributes = "";
		$this->staffName->EditValue = $this->staffName->CurrentValue;
		$this->staffName->PlaceHolder = ew_RemoveHtml($this->staffName->FldCaption());

		// createdDate
		$this->createdDate->EditAttrs["class"] = "form-control";
		$this->createdDate->EditCustomAttributes = "";
		$this->createdDate->EditValue = ew_FormatDateTime($this->createdDate->CurrentValue, 8);
		$this->createdDate->PlaceHolder = ew_RemoveHtml($this->createdDate->FldCaption());

		// createdUserID
		$this->createdUserID->EditAttrs["class"] = "form-control";
		$this->createdUserID->EditCustomAttributes = "";
		$this->createdUserID->EditValue = $this->createdUserID->CurrentValue;
		$this->createdUserID->PlaceHolder = ew_RemoveHtml($this->createdUserID->FldCaption());

		// modifiedDate
		$this->modifiedDate->EditAttrs["class"] = "form-control";
		$this->modifiedDate->EditCustomAttributes = "";
		$this->modifiedDate->EditValue = ew_FormatDateTime($this->modifiedDate->CurrentValue, 8);
		$this->modifiedDate->PlaceHolder = ew_RemoveHtml($this->modifiedDate->FldCaption());

		// modifiedUserID
		$this->modifiedUserID->EditAttrs["class"] = "form-control";
		$this->modifiedUserID->EditCustomAttributes = "";
		$this->modifiedUserID->EditValue = $this->modifiedUserID->CurrentValue;
		$this->modifiedUserID->PlaceHolder = ew_RemoveHtml($this->modifiedUserID->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->debtID->Exportable) $Doc->ExportCaption($this->debtID);
					if ($this->debtNo->Exportable) $Doc->ExportCaption($this->debtNo);
					if ($this->invoiceID->Exportable) $Doc->ExportCaption($this->invoiceID);
					if ($this->invoiceNo->Exportable) $Doc->ExportCaption($this->invoiceNo);
					if ($this->supplierID->Exportable) $Doc->ExportCaption($this->supplierID);
					if ($this->supplierName->Exportable) $Doc->ExportCaption($this->supplierName);
					if ($this->supplierAddress->Exportable) $Doc->ExportCaption($this->supplierAddress);
					if ($this->debtTotal->Exportable) $Doc->ExportCaption($this->debtTotal);
					if ($this->incomingTotal->Exportable) $Doc->ExportCaption($this->incomingTotal);
					if ($this->reductionTotal->Exportable) $Doc->ExportCaption($this->reductionTotal);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->staffID->Exportable) $Doc->ExportCaption($this->staffID);
					if ($this->staffName->Exportable) $Doc->ExportCaption($this->staffName);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				} else {
					if ($this->debtID->Exportable) $Doc->ExportCaption($this->debtID);
					if ($this->debtNo->Exportable) $Doc->ExportCaption($this->debtNo);
					if ($this->invoiceID->Exportable) $Doc->ExportCaption($this->invoiceID);
					if ($this->invoiceNo->Exportable) $Doc->ExportCaption($this->invoiceNo);
					if ($this->supplierID->Exportable) $Doc->ExportCaption($this->supplierID);
					if ($this->supplierName->Exportable) $Doc->ExportCaption($this->supplierName);
					if ($this->debtTotal->Exportable) $Doc->ExportCaption($this->debtTotal);
					if ($this->incomingTotal->Exportable) $Doc->ExportCaption($this->incomingTotal);
					if ($this->reductionTotal->Exportable) $Doc->ExportCaption($this->reductionTotal);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->staffID->Exportable) $Doc->ExportCaption($this->staffID);
					if ($this->staffName->Exportable) $Doc->ExportCaption($this->staffName);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->debtID->Exportable) $Doc->ExportField($this->debtID);
						if ($this->debtNo->Exportable) $Doc->ExportField($this->debtNo);
						if ($this->invoiceID->Exportable) $Doc->ExportField($this->invoiceID);
						if ($this->invoiceNo->Exportable) $Doc->ExportField($this->invoiceNo);
						if ($this->supplierID->Exportable) $Doc->ExportField($this->supplierID);
						if ($this->supplierName->Exportable) $Doc->ExportField($this->supplierName);
						if ($this->supplierAddress->Exportable) $Doc->ExportField($this->supplierAddress);
						if ($this->debtTotal->Exportable) $Doc->ExportField($this->debtTotal);
						if ($this->incomingTotal->Exportable) $Doc->ExportField($this->incomingTotal);
						if ($this->reductionTotal->Exportable) $Doc->ExportField($this->reductionTotal);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->staffID->Exportable) $Doc->ExportField($this->staffID);
						if ($this->staffName->Exportable) $Doc->ExportField($this->staffName);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					} else {
						if ($this->debtID->Exportable) $Doc->ExportField($this->debtID);
						if ($this->debtNo->Exportable) $Doc->ExportField($this->debtNo);
						if ($this->invoiceID->Exportable) $Doc->ExportField($this->invoiceID);
						if ($this->invoiceNo->Exportable) $Doc->ExportField($this->invoiceNo);
						if ($this->supplierID->Exportable) $Doc->ExportField($this->supplierID);
						if ($this->supplierName->Exportable) $Doc->ExportField($this->supplierName);
						if ($this->debtTotal->Exportable) $Doc->ExportField($this->debtTotal);
						if ($this->incomingTotal->Exportable) $Doc->ExportField($this->incomingTotal);
						if ($this->reductionTotal->Exportable) $Doc->ExportField($this->reductionTotal);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->staffID->Exportable) $Doc->ExportField($this->staffID);
						if ($this->staffName->Exportable) $Doc->ExportField($this->staffName);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
