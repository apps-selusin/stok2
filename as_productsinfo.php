<?php

// Global variable for table object
$as_products = NULL;

//
// Table class for as_products
//
class cas_products extends cTable {
	var $productID;
	var $productCode;
	var $productName;
	var $categoryID;
	var $brandID;
	var $unit;
	var $unitPrice1;
	var $unitPrice2;
	var $unitPrice3;
	var $hpp;
	var $purchasePrice;
	var $note;
	var $stockAmount;
	var $image;
	var $minimumStock;
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
		$this->TableVar = 'as_products';
		$this->TableName = 'as_products';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`as_products`";
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

		// productID
		$this->productID = new cField('as_products', 'as_products', 'x_productID', 'productID', '`productID`', '`productID`', 3, -1, FALSE, '`productID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->productID->Sortable = TRUE; // Allow sort
		$this->productID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['productID'] = &$this->productID;

		// productCode
		$this->productCode = new cField('as_products', 'as_products', 'x_productCode', 'productCode', '`productCode`', '`productCode`', 200, -1, FALSE, '`productCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->productCode->Sortable = TRUE; // Allow sort
		$this->fields['productCode'] = &$this->productCode;

		// productName
		$this->productName = new cField('as_products', 'as_products', 'x_productName', 'productName', '`productName`', '`productName`', 200, -1, FALSE, '`productName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->productName->Sortable = TRUE; // Allow sort
		$this->fields['productName'] = &$this->productName;

		// categoryID
		$this->categoryID = new cField('as_products', 'as_products', 'x_categoryID', 'categoryID', '`categoryID`', '`categoryID`', 3, -1, FALSE, '`categoryID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->categoryID->Sortable = TRUE; // Allow sort
		$this->categoryID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoryID'] = &$this->categoryID;

		// brandID
		$this->brandID = new cField('as_products', 'as_products', 'x_brandID', 'brandID', '`brandID`', '`brandID`', 3, -1, FALSE, '`brandID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->brandID->Sortable = TRUE; // Allow sort
		$this->brandID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['brandID'] = &$this->brandID;

		// unit
		$this->unit = new cField('as_products', 'as_products', 'x_unit', 'unit', '`unit`', '`unit`', 3, -1, FALSE, '`unit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unit->Sortable = TRUE; // Allow sort
		$this->unit->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['unit'] = &$this->unit;

		// unitPrice1
		$this->unitPrice1 = new cField('as_products', 'as_products', 'x_unitPrice1', 'unitPrice1', '`unitPrice1`', '`unitPrice1`', 5, -1, FALSE, '`unitPrice1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unitPrice1->Sortable = TRUE; // Allow sort
		$this->unitPrice1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['unitPrice1'] = &$this->unitPrice1;

		// unitPrice2
		$this->unitPrice2 = new cField('as_products', 'as_products', 'x_unitPrice2', 'unitPrice2', '`unitPrice2`', '`unitPrice2`', 5, -1, FALSE, '`unitPrice2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unitPrice2->Sortable = TRUE; // Allow sort
		$this->unitPrice2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['unitPrice2'] = &$this->unitPrice2;

		// unitPrice3
		$this->unitPrice3 = new cField('as_products', 'as_products', 'x_unitPrice3', 'unitPrice3', '`unitPrice3`', '`unitPrice3`', 5, -1, FALSE, '`unitPrice3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unitPrice3->Sortable = TRUE; // Allow sort
		$this->unitPrice3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['unitPrice3'] = &$this->unitPrice3;

		// hpp
		$this->hpp = new cField('as_products', 'as_products', 'x_hpp', 'hpp', '`hpp`', '`hpp`', 5, -1, FALSE, '`hpp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hpp->Sortable = TRUE; // Allow sort
		$this->hpp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['hpp'] = &$this->hpp;

		// purchasePrice
		$this->purchasePrice = new cField('as_products', 'as_products', 'x_purchasePrice', 'purchasePrice', '`purchasePrice`', '`purchasePrice`', 5, -1, FALSE, '`purchasePrice`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->purchasePrice->Sortable = TRUE; // Allow sort
		$this->purchasePrice->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['purchasePrice'] = &$this->purchasePrice;

		// note
		$this->note = new cField('as_products', 'as_products', 'x_note', 'note', '`note`', '`note`', 201, -1, FALSE, '`note`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->note->Sortable = TRUE; // Allow sort
		$this->fields['note'] = &$this->note;

		// stockAmount
		$this->stockAmount = new cField('as_products', 'as_products', 'x_stockAmount', 'stockAmount', '`stockAmount`', '`stockAmount`', 3, -1, FALSE, '`stockAmount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->stockAmount->Sortable = TRUE; // Allow sort
		$this->stockAmount->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['stockAmount'] = &$this->stockAmount;

		// image
		$this->image = new cField('as_products', 'as_products', 'x_image', 'image', '`image`', '`image`', 201, -1, FALSE, '`image`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->image->Sortable = TRUE; // Allow sort
		$this->fields['image'] = &$this->image;

		// minimumStock
		$this->minimumStock = new cField('as_products', 'as_products', 'x_minimumStock', 'minimumStock', '`minimumStock`', '`minimumStock`', 3, -1, FALSE, '`minimumStock`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->minimumStock->Sortable = TRUE; // Allow sort
		$this->minimumStock->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['minimumStock'] = &$this->minimumStock;

		// createdDate
		$this->createdDate = new cField('as_products', 'as_products', 'x_createdDate', 'createdDate', '`createdDate`', ew_CastDateFieldForLike('`createdDate`', 0, "DB"), 135, 0, FALSE, '`createdDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdDate->Sortable = TRUE; // Allow sort
		$this->createdDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['createdDate'] = &$this->createdDate;

		// createdUserID
		$this->createdUserID = new cField('as_products', 'as_products', 'x_createdUserID', 'createdUserID', '`createdUserID`', '`createdUserID`', 3, -1, FALSE, '`createdUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdUserID->Sortable = TRUE; // Allow sort
		$this->createdUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['createdUserID'] = &$this->createdUserID;

		// modifiedDate
		$this->modifiedDate = new cField('as_products', 'as_products', 'x_modifiedDate', 'modifiedDate', '`modifiedDate`', ew_CastDateFieldForLike('`modifiedDate`', 0, "DB"), 135, 0, FALSE, '`modifiedDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedDate->Sortable = TRUE; // Allow sort
		$this->modifiedDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modifiedDate'] = &$this->modifiedDate;

		// modifiedUserID
		$this->modifiedUserID = new cField('as_products', 'as_products', 'x_modifiedUserID', 'modifiedUserID', '`modifiedUserID`', '`modifiedUserID`', 3, -1, FALSE, '`modifiedUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`as_products`";
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
			$this->productID->setDbValue($conn->Insert_ID());
			$rs['productID'] = $this->productID->DbValue;
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
			if (array_key_exists('productID', $rs))
				ew_AddFilter($where, ew_QuotedName('productID', $this->DBID) . '=' . ew_QuotedValue($rs['productID'], $this->productID->FldDataType, $this->DBID));
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
		return "`productID` = @productID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->productID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@productID@", ew_AdjustSql($this->productID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "as_productslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "as_productslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("as_productsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("as_productsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "as_productsadd.php?" . $this->UrlParm($parm);
		else
			$url = "as_productsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("as_productsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("as_productsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("as_productsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "productID:" . ew_VarToJson($this->productID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->productID->CurrentValue)) {
			$sUrl .= "productID=" . urlencode($this->productID->CurrentValue);
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
			if ($isPost && isset($_POST["productID"]))
				$arKeys[] = ew_StripSlashes($_POST["productID"]);
			elseif (isset($_GET["productID"]))
				$arKeys[] = ew_StripSlashes($_GET["productID"]);
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
			$this->productID->CurrentValue = $key;
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
		$this->productID->setDbValue($rs->fields('productID'));
		$this->productCode->setDbValue($rs->fields('productCode'));
		$this->productName->setDbValue($rs->fields('productName'));
		$this->categoryID->setDbValue($rs->fields('categoryID'));
		$this->brandID->setDbValue($rs->fields('brandID'));
		$this->unit->setDbValue($rs->fields('unit'));
		$this->unitPrice1->setDbValue($rs->fields('unitPrice1'));
		$this->unitPrice2->setDbValue($rs->fields('unitPrice2'));
		$this->unitPrice3->setDbValue($rs->fields('unitPrice3'));
		$this->hpp->setDbValue($rs->fields('hpp'));
		$this->purchasePrice->setDbValue($rs->fields('purchasePrice'));
		$this->note->setDbValue($rs->fields('note'));
		$this->stockAmount->setDbValue($rs->fields('stockAmount'));
		$this->image->setDbValue($rs->fields('image'));
		$this->minimumStock->setDbValue($rs->fields('minimumStock'));
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
		// productID
		// productCode
		// productName
		// categoryID
		// brandID
		// unit
		// unitPrice1
		// unitPrice2
		// unitPrice3
		// hpp
		// purchasePrice
		// note
		// stockAmount
		// image
		// minimumStock
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID
		// productID

		$this->productID->ViewValue = $this->productID->CurrentValue;
		$this->productID->ViewCustomAttributes = "";

		// productCode
		$this->productCode->ViewValue = $this->productCode->CurrentValue;
		$this->productCode->ViewCustomAttributes = "";

		// productName
		$this->productName->ViewValue = $this->productName->CurrentValue;
		$this->productName->ViewCustomAttributes = "";

		// categoryID
		$this->categoryID->ViewValue = $this->categoryID->CurrentValue;
		$this->categoryID->ViewCustomAttributes = "";

		// brandID
		$this->brandID->ViewValue = $this->brandID->CurrentValue;
		$this->brandID->ViewCustomAttributes = "";

		// unit
		$this->unit->ViewValue = $this->unit->CurrentValue;
		$this->unit->ViewCustomAttributes = "";

		// unitPrice1
		$this->unitPrice1->ViewValue = $this->unitPrice1->CurrentValue;
		$this->unitPrice1->ViewCustomAttributes = "";

		// unitPrice2
		$this->unitPrice2->ViewValue = $this->unitPrice2->CurrentValue;
		$this->unitPrice2->ViewCustomAttributes = "";

		// unitPrice3
		$this->unitPrice3->ViewValue = $this->unitPrice3->CurrentValue;
		$this->unitPrice3->ViewCustomAttributes = "";

		// hpp
		$this->hpp->ViewValue = $this->hpp->CurrentValue;
		$this->hpp->ViewCustomAttributes = "";

		// purchasePrice
		$this->purchasePrice->ViewValue = $this->purchasePrice->CurrentValue;
		$this->purchasePrice->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// stockAmount
		$this->stockAmount->ViewValue = $this->stockAmount->CurrentValue;
		$this->stockAmount->ViewCustomAttributes = "";

		// image
		$this->image->ViewValue = $this->image->CurrentValue;
		$this->image->ViewCustomAttributes = "";

		// minimumStock
		$this->minimumStock->ViewValue = $this->minimumStock->CurrentValue;
		$this->minimumStock->ViewCustomAttributes = "";

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

		// productID
		$this->productID->LinkCustomAttributes = "";
		$this->productID->HrefValue = "";
		$this->productID->TooltipValue = "";

		// productCode
		$this->productCode->LinkCustomAttributes = "";
		$this->productCode->HrefValue = "";
		$this->productCode->TooltipValue = "";

		// productName
		$this->productName->LinkCustomAttributes = "";
		$this->productName->HrefValue = "";
		$this->productName->TooltipValue = "";

		// categoryID
		$this->categoryID->LinkCustomAttributes = "";
		$this->categoryID->HrefValue = "";
		$this->categoryID->TooltipValue = "";

		// brandID
		$this->brandID->LinkCustomAttributes = "";
		$this->brandID->HrefValue = "";
		$this->brandID->TooltipValue = "";

		// unit
		$this->unit->LinkCustomAttributes = "";
		$this->unit->HrefValue = "";
		$this->unit->TooltipValue = "";

		// unitPrice1
		$this->unitPrice1->LinkCustomAttributes = "";
		$this->unitPrice1->HrefValue = "";
		$this->unitPrice1->TooltipValue = "";

		// unitPrice2
		$this->unitPrice2->LinkCustomAttributes = "";
		$this->unitPrice2->HrefValue = "";
		$this->unitPrice2->TooltipValue = "";

		// unitPrice3
		$this->unitPrice3->LinkCustomAttributes = "";
		$this->unitPrice3->HrefValue = "";
		$this->unitPrice3->TooltipValue = "";

		// hpp
		$this->hpp->LinkCustomAttributes = "";
		$this->hpp->HrefValue = "";
		$this->hpp->TooltipValue = "";

		// purchasePrice
		$this->purchasePrice->LinkCustomAttributes = "";
		$this->purchasePrice->HrefValue = "";
		$this->purchasePrice->TooltipValue = "";

		// note
		$this->note->LinkCustomAttributes = "";
		$this->note->HrefValue = "";
		$this->note->TooltipValue = "";

		// stockAmount
		$this->stockAmount->LinkCustomAttributes = "";
		$this->stockAmount->HrefValue = "";
		$this->stockAmount->TooltipValue = "";

		// image
		$this->image->LinkCustomAttributes = "";
		$this->image->HrefValue = "";
		$this->image->TooltipValue = "";

		// minimumStock
		$this->minimumStock->LinkCustomAttributes = "";
		$this->minimumStock->HrefValue = "";
		$this->minimumStock->TooltipValue = "";

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

		// productID
		$this->productID->EditAttrs["class"] = "form-control";
		$this->productID->EditCustomAttributes = "";
		$this->productID->EditValue = $this->productID->CurrentValue;
		$this->productID->ViewCustomAttributes = "";

		// productCode
		$this->productCode->EditAttrs["class"] = "form-control";
		$this->productCode->EditCustomAttributes = "";
		$this->productCode->EditValue = $this->productCode->CurrentValue;
		$this->productCode->PlaceHolder = ew_RemoveHtml($this->productCode->FldCaption());

		// productName
		$this->productName->EditAttrs["class"] = "form-control";
		$this->productName->EditCustomAttributes = "";
		$this->productName->EditValue = $this->productName->CurrentValue;
		$this->productName->PlaceHolder = ew_RemoveHtml($this->productName->FldCaption());

		// categoryID
		$this->categoryID->EditAttrs["class"] = "form-control";
		$this->categoryID->EditCustomAttributes = "";
		$this->categoryID->EditValue = $this->categoryID->CurrentValue;
		$this->categoryID->PlaceHolder = ew_RemoveHtml($this->categoryID->FldCaption());

		// brandID
		$this->brandID->EditAttrs["class"] = "form-control";
		$this->brandID->EditCustomAttributes = "";
		$this->brandID->EditValue = $this->brandID->CurrentValue;
		$this->brandID->PlaceHolder = ew_RemoveHtml($this->brandID->FldCaption());

		// unit
		$this->unit->EditAttrs["class"] = "form-control";
		$this->unit->EditCustomAttributes = "";
		$this->unit->EditValue = $this->unit->CurrentValue;
		$this->unit->PlaceHolder = ew_RemoveHtml($this->unit->FldCaption());

		// unitPrice1
		$this->unitPrice1->EditAttrs["class"] = "form-control";
		$this->unitPrice1->EditCustomAttributes = "";
		$this->unitPrice1->EditValue = $this->unitPrice1->CurrentValue;
		$this->unitPrice1->PlaceHolder = ew_RemoveHtml($this->unitPrice1->FldCaption());
		if (strval($this->unitPrice1->EditValue) <> "" && is_numeric($this->unitPrice1->EditValue)) $this->unitPrice1->EditValue = ew_FormatNumber($this->unitPrice1->EditValue, -2, -1, -2, 0);

		// unitPrice2
		$this->unitPrice2->EditAttrs["class"] = "form-control";
		$this->unitPrice2->EditCustomAttributes = "";
		$this->unitPrice2->EditValue = $this->unitPrice2->CurrentValue;
		$this->unitPrice2->PlaceHolder = ew_RemoveHtml($this->unitPrice2->FldCaption());
		if (strval($this->unitPrice2->EditValue) <> "" && is_numeric($this->unitPrice2->EditValue)) $this->unitPrice2->EditValue = ew_FormatNumber($this->unitPrice2->EditValue, -2, -1, -2, 0);

		// unitPrice3
		$this->unitPrice3->EditAttrs["class"] = "form-control";
		$this->unitPrice3->EditCustomAttributes = "";
		$this->unitPrice3->EditValue = $this->unitPrice3->CurrentValue;
		$this->unitPrice3->PlaceHolder = ew_RemoveHtml($this->unitPrice3->FldCaption());
		if (strval($this->unitPrice3->EditValue) <> "" && is_numeric($this->unitPrice3->EditValue)) $this->unitPrice3->EditValue = ew_FormatNumber($this->unitPrice3->EditValue, -2, -1, -2, 0);

		// hpp
		$this->hpp->EditAttrs["class"] = "form-control";
		$this->hpp->EditCustomAttributes = "";
		$this->hpp->EditValue = $this->hpp->CurrentValue;
		$this->hpp->PlaceHolder = ew_RemoveHtml($this->hpp->FldCaption());
		if (strval($this->hpp->EditValue) <> "" && is_numeric($this->hpp->EditValue)) $this->hpp->EditValue = ew_FormatNumber($this->hpp->EditValue, -2, -1, -2, 0);

		// purchasePrice
		$this->purchasePrice->EditAttrs["class"] = "form-control";
		$this->purchasePrice->EditCustomAttributes = "";
		$this->purchasePrice->EditValue = $this->purchasePrice->CurrentValue;
		$this->purchasePrice->PlaceHolder = ew_RemoveHtml($this->purchasePrice->FldCaption());
		if (strval($this->purchasePrice->EditValue) <> "" && is_numeric($this->purchasePrice->EditValue)) $this->purchasePrice->EditValue = ew_FormatNumber($this->purchasePrice->EditValue, -2, -1, -2, 0);

		// note
		$this->note->EditAttrs["class"] = "form-control";
		$this->note->EditCustomAttributes = "";
		$this->note->EditValue = $this->note->CurrentValue;
		$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

		// stockAmount
		$this->stockAmount->EditAttrs["class"] = "form-control";
		$this->stockAmount->EditCustomAttributes = "";
		$this->stockAmount->EditValue = $this->stockAmount->CurrentValue;
		$this->stockAmount->PlaceHolder = ew_RemoveHtml($this->stockAmount->FldCaption());

		// image
		$this->image->EditAttrs["class"] = "form-control";
		$this->image->EditCustomAttributes = "";
		$this->image->EditValue = $this->image->CurrentValue;
		$this->image->PlaceHolder = ew_RemoveHtml($this->image->FldCaption());

		// minimumStock
		$this->minimumStock->EditAttrs["class"] = "form-control";
		$this->minimumStock->EditCustomAttributes = "";
		$this->minimumStock->EditValue = $this->minimumStock->CurrentValue;
		$this->minimumStock->PlaceHolder = ew_RemoveHtml($this->minimumStock->FldCaption());

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
					if ($this->productID->Exportable) $Doc->ExportCaption($this->productID);
					if ($this->productCode->Exportable) $Doc->ExportCaption($this->productCode);
					if ($this->productName->Exportable) $Doc->ExportCaption($this->productName);
					if ($this->categoryID->Exportable) $Doc->ExportCaption($this->categoryID);
					if ($this->brandID->Exportable) $Doc->ExportCaption($this->brandID);
					if ($this->unit->Exportable) $Doc->ExportCaption($this->unit);
					if ($this->unitPrice1->Exportable) $Doc->ExportCaption($this->unitPrice1);
					if ($this->unitPrice2->Exportable) $Doc->ExportCaption($this->unitPrice2);
					if ($this->unitPrice3->Exportable) $Doc->ExportCaption($this->unitPrice3);
					if ($this->hpp->Exportable) $Doc->ExportCaption($this->hpp);
					if ($this->purchasePrice->Exportable) $Doc->ExportCaption($this->purchasePrice);
					if ($this->note->Exportable) $Doc->ExportCaption($this->note);
					if ($this->stockAmount->Exportable) $Doc->ExportCaption($this->stockAmount);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->minimumStock->Exportable) $Doc->ExportCaption($this->minimumStock);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				} else {
					if ($this->productID->Exportable) $Doc->ExportCaption($this->productID);
					if ($this->productCode->Exportable) $Doc->ExportCaption($this->productCode);
					if ($this->productName->Exportable) $Doc->ExportCaption($this->productName);
					if ($this->categoryID->Exportable) $Doc->ExportCaption($this->categoryID);
					if ($this->brandID->Exportable) $Doc->ExportCaption($this->brandID);
					if ($this->unit->Exportable) $Doc->ExportCaption($this->unit);
					if ($this->unitPrice1->Exportable) $Doc->ExportCaption($this->unitPrice1);
					if ($this->unitPrice2->Exportable) $Doc->ExportCaption($this->unitPrice2);
					if ($this->unitPrice3->Exportable) $Doc->ExportCaption($this->unitPrice3);
					if ($this->hpp->Exportable) $Doc->ExportCaption($this->hpp);
					if ($this->purchasePrice->Exportable) $Doc->ExportCaption($this->purchasePrice);
					if ($this->stockAmount->Exportable) $Doc->ExportCaption($this->stockAmount);
					if ($this->minimumStock->Exportable) $Doc->ExportCaption($this->minimumStock);
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
						if ($this->productID->Exportable) $Doc->ExportField($this->productID);
						if ($this->productCode->Exportable) $Doc->ExportField($this->productCode);
						if ($this->productName->Exportable) $Doc->ExportField($this->productName);
						if ($this->categoryID->Exportable) $Doc->ExportField($this->categoryID);
						if ($this->brandID->Exportable) $Doc->ExportField($this->brandID);
						if ($this->unit->Exportable) $Doc->ExportField($this->unit);
						if ($this->unitPrice1->Exportable) $Doc->ExportField($this->unitPrice1);
						if ($this->unitPrice2->Exportable) $Doc->ExportField($this->unitPrice2);
						if ($this->unitPrice3->Exportable) $Doc->ExportField($this->unitPrice3);
						if ($this->hpp->Exportable) $Doc->ExportField($this->hpp);
						if ($this->purchasePrice->Exportable) $Doc->ExportField($this->purchasePrice);
						if ($this->note->Exportable) $Doc->ExportField($this->note);
						if ($this->stockAmount->Exportable) $Doc->ExportField($this->stockAmount);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->minimumStock->Exportable) $Doc->ExportField($this->minimumStock);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					} else {
						if ($this->productID->Exportable) $Doc->ExportField($this->productID);
						if ($this->productCode->Exportable) $Doc->ExportField($this->productCode);
						if ($this->productName->Exportable) $Doc->ExportField($this->productName);
						if ($this->categoryID->Exportable) $Doc->ExportField($this->categoryID);
						if ($this->brandID->Exportable) $Doc->ExportField($this->brandID);
						if ($this->unit->Exportable) $Doc->ExportField($this->unit);
						if ($this->unitPrice1->Exportable) $Doc->ExportField($this->unitPrice1);
						if ($this->unitPrice2->Exportable) $Doc->ExportField($this->unitPrice2);
						if ($this->unitPrice3->Exportable) $Doc->ExportField($this->unitPrice3);
						if ($this->hpp->Exportable) $Doc->ExportField($this->hpp);
						if ($this->purchasePrice->Exportable) $Doc->ExportField($this->purchasePrice);
						if ($this->stockAmount->Exportable) $Doc->ExportField($this->stockAmount);
						if ($this->minimumStock->Exportable) $Doc->ExportField($this->minimumStock);
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
