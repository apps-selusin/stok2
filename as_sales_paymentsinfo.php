<?php

// Global variable for table object
$as_sales_payments = NULL;

//
// Table class for as_sales_payments
//
class cas_sales_payments extends cTable {
	var $paymentID;
	var $paymentNo;
	var $invoiceID;
	var $invoiceNo;
	var $soNo;
	var $paymentDate;
	var $payType;
	var $bankNo;
	var $bankName;
	var $bankAC;
	var $effectiveDate;
	var $total;
	var $customerID;
	var $customerName;
	var $customerAddress;
	var $ref;
	var $note;
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
		$this->TableVar = 'as_sales_payments';
		$this->TableName = 'as_sales_payments';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`as_sales_payments`";
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

		// paymentID
		$this->paymentID = new cField('as_sales_payments', 'as_sales_payments', 'x_paymentID', 'paymentID', '`paymentID`', '`paymentID`', 3, -1, FALSE, '`paymentID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->paymentID->Sortable = TRUE; // Allow sort
		$this->paymentID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['paymentID'] = &$this->paymentID;

		// paymentNo
		$this->paymentNo = new cField('as_sales_payments', 'as_sales_payments', 'x_paymentNo', 'paymentNo', '`paymentNo`', '`paymentNo`', 200, -1, FALSE, '`paymentNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->paymentNo->Sortable = TRUE; // Allow sort
		$this->fields['paymentNo'] = &$this->paymentNo;

		// invoiceID
		$this->invoiceID = new cField('as_sales_payments', 'as_sales_payments', 'x_invoiceID', 'invoiceID', '`invoiceID`', '`invoiceID`', 3, -1, FALSE, '`invoiceID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->invoiceID->Sortable = TRUE; // Allow sort
		$this->invoiceID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoiceID'] = &$this->invoiceID;

		// invoiceNo
		$this->invoiceNo = new cField('as_sales_payments', 'as_sales_payments', 'x_invoiceNo', 'invoiceNo', '`invoiceNo`', '`invoiceNo`', 200, -1, FALSE, '`invoiceNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->invoiceNo->Sortable = TRUE; // Allow sort
		$this->fields['invoiceNo'] = &$this->invoiceNo;

		// soNo
		$this->soNo = new cField('as_sales_payments', 'as_sales_payments', 'x_soNo', 'soNo', '`soNo`', '`soNo`', 200, -1, FALSE, '`soNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->soNo->Sortable = TRUE; // Allow sort
		$this->fields['soNo'] = &$this->soNo;

		// paymentDate
		$this->paymentDate = new cField('as_sales_payments', 'as_sales_payments', 'x_paymentDate', 'paymentDate', '`paymentDate`', ew_CastDateFieldForLike('`paymentDate`', 0, "DB"), 133, 0, FALSE, '`paymentDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->paymentDate->Sortable = TRUE; // Allow sort
		$this->paymentDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['paymentDate'] = &$this->paymentDate;

		// payType
		$this->payType = new cField('as_sales_payments', 'as_sales_payments', 'x_payType', 'payType', '`payType`', '`payType`', 200, -1, FALSE, '`payType`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->payType->Sortable = TRUE; // Allow sort
		$this->fields['payType'] = &$this->payType;

		// bankNo
		$this->bankNo = new cField('as_sales_payments', 'as_sales_payments', 'x_bankNo', 'bankNo', '`bankNo`', '`bankNo`', 200, -1, FALSE, '`bankNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bankNo->Sortable = TRUE; // Allow sort
		$this->fields['bankNo'] = &$this->bankNo;

		// bankName
		$this->bankName = new cField('as_sales_payments', 'as_sales_payments', 'x_bankName', 'bankName', '`bankName`', '`bankName`', 200, -1, FALSE, '`bankName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bankName->Sortable = TRUE; // Allow sort
		$this->fields['bankName'] = &$this->bankName;

		// bankAC
		$this->bankAC = new cField('as_sales_payments', 'as_sales_payments', 'x_bankAC', 'bankAC', '`bankAC`', '`bankAC`', 200, -1, FALSE, '`bankAC`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bankAC->Sortable = TRUE; // Allow sort
		$this->fields['bankAC'] = &$this->bankAC;

		// effectiveDate
		$this->effectiveDate = new cField('as_sales_payments', 'as_sales_payments', 'x_effectiveDate', 'effectiveDate', '`effectiveDate`', ew_CastDateFieldForLike('`effectiveDate`', 0, "DB"), 133, 0, FALSE, '`effectiveDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->effectiveDate->Sortable = TRUE; // Allow sort
		$this->effectiveDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['effectiveDate'] = &$this->effectiveDate;

		// total
		$this->total = new cField('as_sales_payments', 'as_sales_payments', 'x_total', 'total', '`total`', '`total`', 5, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;

		// customerID
		$this->customerID = new cField('as_sales_payments', 'as_sales_payments', 'x_customerID', 'customerID', '`customerID`', '`customerID`', 3, -1, FALSE, '`customerID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->customerID->Sortable = TRUE; // Allow sort
		$this->customerID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['customerID'] = &$this->customerID;

		// customerName
		$this->customerName = new cField('as_sales_payments', 'as_sales_payments', 'x_customerName', 'customerName', '`customerName`', '`customerName`', 200, -1, FALSE, '`customerName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->customerName->Sortable = TRUE; // Allow sort
		$this->fields['customerName'] = &$this->customerName;

		// customerAddress
		$this->customerAddress = new cField('as_sales_payments', 'as_sales_payments', 'x_customerAddress', 'customerAddress', '`customerAddress`', '`customerAddress`', 201, -1, FALSE, '`customerAddress`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->customerAddress->Sortable = TRUE; // Allow sort
		$this->fields['customerAddress'] = &$this->customerAddress;

		// ref
		$this->ref = new cField('as_sales_payments', 'as_sales_payments', 'x_ref', 'ref', '`ref`', '`ref`', 200, -1, FALSE, '`ref`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ref->Sortable = TRUE; // Allow sort
		$this->fields['ref'] = &$this->ref;

		// note
		$this->note = new cField('as_sales_payments', 'as_sales_payments', 'x_note', 'note', '`note`', '`note`', 201, -1, FALSE, '`note`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->note->Sortable = TRUE; // Allow sort
		$this->fields['note'] = &$this->note;

		// staffID
		$this->staffID = new cField('as_sales_payments', 'as_sales_payments', 'x_staffID', 'staffID', '`staffID`', '`staffID`', 3, -1, FALSE, '`staffID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffID->Sortable = TRUE; // Allow sort
		$this->staffID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staffID'] = &$this->staffID;

		// staffName
		$this->staffName = new cField('as_sales_payments', 'as_sales_payments', 'x_staffName', 'staffName', '`staffName`', '`staffName`', 200, -1, FALSE, '`staffName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffName->Sortable = TRUE; // Allow sort
		$this->fields['staffName'] = &$this->staffName;

		// createdDate
		$this->createdDate = new cField('as_sales_payments', 'as_sales_payments', 'x_createdDate', 'createdDate', '`createdDate`', ew_CastDateFieldForLike('`createdDate`', 0, "DB"), 135, 0, FALSE, '`createdDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdDate->Sortable = TRUE; // Allow sort
		$this->createdDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['createdDate'] = &$this->createdDate;

		// createdUserID
		$this->createdUserID = new cField('as_sales_payments', 'as_sales_payments', 'x_createdUserID', 'createdUserID', '`createdUserID`', '`createdUserID`', 3, -1, FALSE, '`createdUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdUserID->Sortable = TRUE; // Allow sort
		$this->createdUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['createdUserID'] = &$this->createdUserID;

		// modifiedDate
		$this->modifiedDate = new cField('as_sales_payments', 'as_sales_payments', 'x_modifiedDate', 'modifiedDate', '`modifiedDate`', ew_CastDateFieldForLike('`modifiedDate`', 0, "DB"), 135, 0, FALSE, '`modifiedDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedDate->Sortable = TRUE; // Allow sort
		$this->modifiedDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modifiedDate'] = &$this->modifiedDate;

		// modifiedUserID
		$this->modifiedUserID = new cField('as_sales_payments', 'as_sales_payments', 'x_modifiedUserID', 'modifiedUserID', '`modifiedUserID`', '`modifiedUserID`', 3, -1, FALSE, '`modifiedUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`as_sales_payments`";
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
			$this->paymentID->setDbValue($conn->Insert_ID());
			$rs['paymentID'] = $this->paymentID->DbValue;
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
			if (array_key_exists('paymentID', $rs))
				ew_AddFilter($where, ew_QuotedName('paymentID', $this->DBID) . '=' . ew_QuotedValue($rs['paymentID'], $this->paymentID->FldDataType, $this->DBID));
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
		return "`paymentID` = @paymentID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->paymentID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@paymentID@", ew_AdjustSql($this->paymentID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "as_sales_paymentslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "as_sales_paymentslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("as_sales_paymentsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("as_sales_paymentsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "as_sales_paymentsadd.php?" . $this->UrlParm($parm);
		else
			$url = "as_sales_paymentsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("as_sales_paymentsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("as_sales_paymentsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("as_sales_paymentsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "paymentID:" . ew_VarToJson($this->paymentID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->paymentID->CurrentValue)) {
			$sUrl .= "paymentID=" . urlencode($this->paymentID->CurrentValue);
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
			if ($isPost && isset($_POST["paymentID"]))
				$arKeys[] = ew_StripSlashes($_POST["paymentID"]);
			elseif (isset($_GET["paymentID"]))
				$arKeys[] = ew_StripSlashes($_GET["paymentID"]);
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
			$this->paymentID->CurrentValue = $key;
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
		$this->paymentID->setDbValue($rs->fields('paymentID'));
		$this->paymentNo->setDbValue($rs->fields('paymentNo'));
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->soNo->setDbValue($rs->fields('soNo'));
		$this->paymentDate->setDbValue($rs->fields('paymentDate'));
		$this->payType->setDbValue($rs->fields('payType'));
		$this->bankNo->setDbValue($rs->fields('bankNo'));
		$this->bankName->setDbValue($rs->fields('bankName'));
		$this->bankAC->setDbValue($rs->fields('bankAC'));
		$this->effectiveDate->setDbValue($rs->fields('effectiveDate'));
		$this->total->setDbValue($rs->fields('total'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
		$this->ref->setDbValue($rs->fields('ref'));
		$this->note->setDbValue($rs->fields('note'));
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
		// paymentID
		// paymentNo
		// invoiceID
		// invoiceNo
		// soNo
		// paymentDate
		// payType
		// bankNo
		// bankName
		// bankAC
		// effectiveDate
		// total
		// customerID
		// customerName
		// customerAddress
		// ref
		// note
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID
		// paymentID

		$this->paymentID->ViewValue = $this->paymentID->CurrentValue;
		$this->paymentID->ViewCustomAttributes = "";

		// paymentNo
		$this->paymentNo->ViewValue = $this->paymentNo->CurrentValue;
		$this->paymentNo->ViewCustomAttributes = "";

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// soNo
		$this->soNo->ViewValue = $this->soNo->CurrentValue;
		$this->soNo->ViewCustomAttributes = "";

		// paymentDate
		$this->paymentDate->ViewValue = $this->paymentDate->CurrentValue;
		$this->paymentDate->ViewValue = ew_FormatDateTime($this->paymentDate->ViewValue, 0);
		$this->paymentDate->ViewCustomAttributes = "";

		// payType
		$this->payType->ViewValue = $this->payType->CurrentValue;
		$this->payType->ViewCustomAttributes = "";

		// bankNo
		$this->bankNo->ViewValue = $this->bankNo->CurrentValue;
		$this->bankNo->ViewCustomAttributes = "";

		// bankName
		$this->bankName->ViewValue = $this->bankName->CurrentValue;
		$this->bankName->ViewCustomAttributes = "";

		// bankAC
		$this->bankAC->ViewValue = $this->bankAC->CurrentValue;
		$this->bankAC->ViewCustomAttributes = "";

		// effectiveDate
		$this->effectiveDate->ViewValue = $this->effectiveDate->CurrentValue;
		$this->effectiveDate->ViewValue = ew_FormatDateTime($this->effectiveDate->ViewValue, 0);
		$this->effectiveDate->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// customerAddress
		$this->customerAddress->ViewValue = $this->customerAddress->CurrentValue;
		$this->customerAddress->ViewCustomAttributes = "";

		// ref
		$this->ref->ViewValue = $this->ref->CurrentValue;
		$this->ref->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

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

		// paymentID
		$this->paymentID->LinkCustomAttributes = "";
		$this->paymentID->HrefValue = "";
		$this->paymentID->TooltipValue = "";

		// paymentNo
		$this->paymentNo->LinkCustomAttributes = "";
		$this->paymentNo->HrefValue = "";
		$this->paymentNo->TooltipValue = "";

		// invoiceID
		$this->invoiceID->LinkCustomAttributes = "";
		$this->invoiceID->HrefValue = "";
		$this->invoiceID->TooltipValue = "";

		// invoiceNo
		$this->invoiceNo->LinkCustomAttributes = "";
		$this->invoiceNo->HrefValue = "";
		$this->invoiceNo->TooltipValue = "";

		// soNo
		$this->soNo->LinkCustomAttributes = "";
		$this->soNo->HrefValue = "";
		$this->soNo->TooltipValue = "";

		// paymentDate
		$this->paymentDate->LinkCustomAttributes = "";
		$this->paymentDate->HrefValue = "";
		$this->paymentDate->TooltipValue = "";

		// payType
		$this->payType->LinkCustomAttributes = "";
		$this->payType->HrefValue = "";
		$this->payType->TooltipValue = "";

		// bankNo
		$this->bankNo->LinkCustomAttributes = "";
		$this->bankNo->HrefValue = "";
		$this->bankNo->TooltipValue = "";

		// bankName
		$this->bankName->LinkCustomAttributes = "";
		$this->bankName->HrefValue = "";
		$this->bankName->TooltipValue = "";

		// bankAC
		$this->bankAC->LinkCustomAttributes = "";
		$this->bankAC->HrefValue = "";
		$this->bankAC->TooltipValue = "";

		// effectiveDate
		$this->effectiveDate->LinkCustomAttributes = "";
		$this->effectiveDate->HrefValue = "";
		$this->effectiveDate->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

		// customerID
		$this->customerID->LinkCustomAttributes = "";
		$this->customerID->HrefValue = "";
		$this->customerID->TooltipValue = "";

		// customerName
		$this->customerName->LinkCustomAttributes = "";
		$this->customerName->HrefValue = "";
		$this->customerName->TooltipValue = "";

		// customerAddress
		$this->customerAddress->LinkCustomAttributes = "";
		$this->customerAddress->HrefValue = "";
		$this->customerAddress->TooltipValue = "";

		// ref
		$this->ref->LinkCustomAttributes = "";
		$this->ref->HrefValue = "";
		$this->ref->TooltipValue = "";

		// note
		$this->note->LinkCustomAttributes = "";
		$this->note->HrefValue = "";
		$this->note->TooltipValue = "";

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

		// paymentID
		$this->paymentID->EditAttrs["class"] = "form-control";
		$this->paymentID->EditCustomAttributes = "";
		$this->paymentID->EditValue = $this->paymentID->CurrentValue;
		$this->paymentID->ViewCustomAttributes = "";

		// paymentNo
		$this->paymentNo->EditAttrs["class"] = "form-control";
		$this->paymentNo->EditCustomAttributes = "";
		$this->paymentNo->EditValue = $this->paymentNo->CurrentValue;
		$this->paymentNo->PlaceHolder = ew_RemoveHtml($this->paymentNo->FldCaption());

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

		// soNo
		$this->soNo->EditAttrs["class"] = "form-control";
		$this->soNo->EditCustomAttributes = "";
		$this->soNo->EditValue = $this->soNo->CurrentValue;
		$this->soNo->PlaceHolder = ew_RemoveHtml($this->soNo->FldCaption());

		// paymentDate
		$this->paymentDate->EditAttrs["class"] = "form-control";
		$this->paymentDate->EditCustomAttributes = "";
		$this->paymentDate->EditValue = ew_FormatDateTime($this->paymentDate->CurrentValue, 8);
		$this->paymentDate->PlaceHolder = ew_RemoveHtml($this->paymentDate->FldCaption());

		// payType
		$this->payType->EditAttrs["class"] = "form-control";
		$this->payType->EditCustomAttributes = "";
		$this->payType->EditValue = $this->payType->CurrentValue;
		$this->payType->PlaceHolder = ew_RemoveHtml($this->payType->FldCaption());

		// bankNo
		$this->bankNo->EditAttrs["class"] = "form-control";
		$this->bankNo->EditCustomAttributes = "";
		$this->bankNo->EditValue = $this->bankNo->CurrentValue;
		$this->bankNo->PlaceHolder = ew_RemoveHtml($this->bankNo->FldCaption());

		// bankName
		$this->bankName->EditAttrs["class"] = "form-control";
		$this->bankName->EditCustomAttributes = "";
		$this->bankName->EditValue = $this->bankName->CurrentValue;
		$this->bankName->PlaceHolder = ew_RemoveHtml($this->bankName->FldCaption());

		// bankAC
		$this->bankAC->EditAttrs["class"] = "form-control";
		$this->bankAC->EditCustomAttributes = "";
		$this->bankAC->EditValue = $this->bankAC->CurrentValue;
		$this->bankAC->PlaceHolder = ew_RemoveHtml($this->bankAC->FldCaption());

		// effectiveDate
		$this->effectiveDate->EditAttrs["class"] = "form-control";
		$this->effectiveDate->EditCustomAttributes = "";
		$this->effectiveDate->EditValue = ew_FormatDateTime($this->effectiveDate->CurrentValue, 8);
		$this->effectiveDate->PlaceHolder = ew_RemoveHtml($this->effectiveDate->FldCaption());

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = $this->total->CurrentValue;
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

		// customerID
		$this->customerID->EditAttrs["class"] = "form-control";
		$this->customerID->EditCustomAttributes = "";
		$this->customerID->EditValue = $this->customerID->CurrentValue;
		$this->customerID->PlaceHolder = ew_RemoveHtml($this->customerID->FldCaption());

		// customerName
		$this->customerName->EditAttrs["class"] = "form-control";
		$this->customerName->EditCustomAttributes = "";
		$this->customerName->EditValue = $this->customerName->CurrentValue;
		$this->customerName->PlaceHolder = ew_RemoveHtml($this->customerName->FldCaption());

		// customerAddress
		$this->customerAddress->EditAttrs["class"] = "form-control";
		$this->customerAddress->EditCustomAttributes = "";
		$this->customerAddress->EditValue = $this->customerAddress->CurrentValue;
		$this->customerAddress->PlaceHolder = ew_RemoveHtml($this->customerAddress->FldCaption());

		// ref
		$this->ref->EditAttrs["class"] = "form-control";
		$this->ref->EditCustomAttributes = "";
		$this->ref->EditValue = $this->ref->CurrentValue;
		$this->ref->PlaceHolder = ew_RemoveHtml($this->ref->FldCaption());

		// note
		$this->note->EditAttrs["class"] = "form-control";
		$this->note->EditCustomAttributes = "";
		$this->note->EditValue = $this->note->CurrentValue;
		$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

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
					if ($this->paymentID->Exportable) $Doc->ExportCaption($this->paymentID);
					if ($this->paymentNo->Exportable) $Doc->ExportCaption($this->paymentNo);
					if ($this->invoiceID->Exportable) $Doc->ExportCaption($this->invoiceID);
					if ($this->invoiceNo->Exportable) $Doc->ExportCaption($this->invoiceNo);
					if ($this->soNo->Exportable) $Doc->ExportCaption($this->soNo);
					if ($this->paymentDate->Exportable) $Doc->ExportCaption($this->paymentDate);
					if ($this->payType->Exportable) $Doc->ExportCaption($this->payType);
					if ($this->bankNo->Exportable) $Doc->ExportCaption($this->bankNo);
					if ($this->bankName->Exportable) $Doc->ExportCaption($this->bankName);
					if ($this->bankAC->Exportable) $Doc->ExportCaption($this->bankAC);
					if ($this->effectiveDate->Exportable) $Doc->ExportCaption($this->effectiveDate);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->customerID->Exportable) $Doc->ExportCaption($this->customerID);
					if ($this->customerName->Exportable) $Doc->ExportCaption($this->customerName);
					if ($this->customerAddress->Exportable) $Doc->ExportCaption($this->customerAddress);
					if ($this->ref->Exportable) $Doc->ExportCaption($this->ref);
					if ($this->note->Exportable) $Doc->ExportCaption($this->note);
					if ($this->staffID->Exportable) $Doc->ExportCaption($this->staffID);
					if ($this->staffName->Exportable) $Doc->ExportCaption($this->staffName);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				} else {
					if ($this->paymentID->Exportable) $Doc->ExportCaption($this->paymentID);
					if ($this->paymentNo->Exportable) $Doc->ExportCaption($this->paymentNo);
					if ($this->invoiceID->Exportable) $Doc->ExportCaption($this->invoiceID);
					if ($this->invoiceNo->Exportable) $Doc->ExportCaption($this->invoiceNo);
					if ($this->soNo->Exportable) $Doc->ExportCaption($this->soNo);
					if ($this->paymentDate->Exportable) $Doc->ExportCaption($this->paymentDate);
					if ($this->payType->Exportable) $Doc->ExportCaption($this->payType);
					if ($this->bankNo->Exportable) $Doc->ExportCaption($this->bankNo);
					if ($this->bankName->Exportable) $Doc->ExportCaption($this->bankName);
					if ($this->bankAC->Exportable) $Doc->ExportCaption($this->bankAC);
					if ($this->effectiveDate->Exportable) $Doc->ExportCaption($this->effectiveDate);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->customerID->Exportable) $Doc->ExportCaption($this->customerID);
					if ($this->customerName->Exportable) $Doc->ExportCaption($this->customerName);
					if ($this->ref->Exportable) $Doc->ExportCaption($this->ref);
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
						if ($this->paymentID->Exportable) $Doc->ExportField($this->paymentID);
						if ($this->paymentNo->Exportable) $Doc->ExportField($this->paymentNo);
						if ($this->invoiceID->Exportable) $Doc->ExportField($this->invoiceID);
						if ($this->invoiceNo->Exportable) $Doc->ExportField($this->invoiceNo);
						if ($this->soNo->Exportable) $Doc->ExportField($this->soNo);
						if ($this->paymentDate->Exportable) $Doc->ExportField($this->paymentDate);
						if ($this->payType->Exportable) $Doc->ExportField($this->payType);
						if ($this->bankNo->Exportable) $Doc->ExportField($this->bankNo);
						if ($this->bankName->Exportable) $Doc->ExportField($this->bankName);
						if ($this->bankAC->Exportable) $Doc->ExportField($this->bankAC);
						if ($this->effectiveDate->Exportable) $Doc->ExportField($this->effectiveDate);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->customerID->Exportable) $Doc->ExportField($this->customerID);
						if ($this->customerName->Exportable) $Doc->ExportField($this->customerName);
						if ($this->customerAddress->Exportable) $Doc->ExportField($this->customerAddress);
						if ($this->ref->Exportable) $Doc->ExportField($this->ref);
						if ($this->note->Exportable) $Doc->ExportField($this->note);
						if ($this->staffID->Exportable) $Doc->ExportField($this->staffID);
						if ($this->staffName->Exportable) $Doc->ExportField($this->staffName);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					} else {
						if ($this->paymentID->Exportable) $Doc->ExportField($this->paymentID);
						if ($this->paymentNo->Exportable) $Doc->ExportField($this->paymentNo);
						if ($this->invoiceID->Exportable) $Doc->ExportField($this->invoiceID);
						if ($this->invoiceNo->Exportable) $Doc->ExportField($this->invoiceNo);
						if ($this->soNo->Exportable) $Doc->ExportField($this->soNo);
						if ($this->paymentDate->Exportable) $Doc->ExportField($this->paymentDate);
						if ($this->payType->Exportable) $Doc->ExportField($this->payType);
						if ($this->bankNo->Exportable) $Doc->ExportField($this->bankNo);
						if ($this->bankName->Exportable) $Doc->ExportField($this->bankName);
						if ($this->bankAC->Exportable) $Doc->ExportField($this->bankAC);
						if ($this->effectiveDate->Exportable) $Doc->ExportField($this->effectiveDate);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->customerID->Exportable) $Doc->ExportField($this->customerID);
						if ($this->customerName->Exportable) $Doc->ExportField($this->customerName);
						if ($this->ref->Exportable) $Doc->ExportField($this->ref);
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
