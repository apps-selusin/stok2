<?php

// Global variable for table object
$as_staffs = NULL;

//
// Table class for as_staffs
//
class cas_staffs extends cTable {
	var $staffID;
	var $staffCode;
	var $staffName;
	var $address;
	var $address2;
	var $village;
	var $district;
	var $city;
	var $zipCode;
	var $province;
	var $phone;
	var $position;
	var $part;
	var $status;
	var $level;
	var $photo;
	var $_email;
	var $password;
	var $lastLogin;
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
		$this->TableVar = 'as_staffs';
		$this->TableName = 'as_staffs';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`as_staffs`";
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

		// staffID
		$this->staffID = new cField('as_staffs', 'as_staffs', 'x_staffID', 'staffID', '`staffID`', '`staffID`', 3, -1, FALSE, '`staffID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->staffID->Sortable = TRUE; // Allow sort
		$this->staffID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staffID'] = &$this->staffID;

		// staffCode
		$this->staffCode = new cField('as_staffs', 'as_staffs', 'x_staffCode', 'staffCode', '`staffCode`', '`staffCode`', 200, -1, FALSE, '`staffCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffCode->Sortable = TRUE; // Allow sort
		$this->fields['staffCode'] = &$this->staffCode;

		// staffName
		$this->staffName = new cField('as_staffs', 'as_staffs', 'x_staffName', 'staffName', '`staffName`', '`staffName`', 200, -1, FALSE, '`staffName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffName->Sortable = TRUE; // Allow sort
		$this->fields['staffName'] = &$this->staffName;

		// address
		$this->address = new cField('as_staffs', 'as_staffs', 'x_address', 'address', '`address`', '`address`', 201, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// address2
		$this->address2 = new cField('as_staffs', 'as_staffs', 'x_address2', 'address2', '`address2`', '`address2`', 201, -1, FALSE, '`address2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->address2->Sortable = TRUE; // Allow sort
		$this->fields['address2'] = &$this->address2;

		// village
		$this->village = new cField('as_staffs', 'as_staffs', 'x_village', 'village', '`village`', '`village`', 200, -1, FALSE, '`village`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->village->Sortable = TRUE; // Allow sort
		$this->fields['village'] = &$this->village;

		// district
		$this->district = new cField('as_staffs', 'as_staffs', 'x_district', 'district', '`district`', '`district`', 200, -1, FALSE, '`district`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->district->Sortable = TRUE; // Allow sort
		$this->fields['district'] = &$this->district;

		// city
		$this->city = new cField('as_staffs', 'as_staffs', 'x_city', 'city', '`city`', '`city`', 200, -1, FALSE, '`city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->city->Sortable = TRUE; // Allow sort
		$this->fields['city'] = &$this->city;

		// zipCode
		$this->zipCode = new cField('as_staffs', 'as_staffs', 'x_zipCode', 'zipCode', '`zipCode`', '`zipCode`', 200, -1, FALSE, '`zipCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->zipCode->Sortable = TRUE; // Allow sort
		$this->fields['zipCode'] = &$this->zipCode;

		// province
		$this->province = new cField('as_staffs', 'as_staffs', 'x_province', 'province', '`province`', '`province`', 200, -1, FALSE, '`province`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->province->Sortable = TRUE; // Allow sort
		$this->fields['province'] = &$this->province;

		// phone
		$this->phone = new cField('as_staffs', 'as_staffs', 'x_phone', 'phone', '`phone`', '`phone`', 200, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone->Sortable = TRUE; // Allow sort
		$this->fields['phone'] = &$this->phone;

		// position
		$this->position = new cField('as_staffs', 'as_staffs', 'x_position', 'position', '`position`', '`position`', 200, -1, FALSE, '`position`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->position->Sortable = TRUE; // Allow sort
		$this->fields['position'] = &$this->position;

		// part
		$this->part = new cField('as_staffs', 'as_staffs', 'x_part', 'part', '`part`', '`part`', 200, -1, FALSE, '`part`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->part->Sortable = TRUE; // Allow sort
		$this->fields['part'] = &$this->part;

		// status
		$this->status = new cField('as_staffs', 'as_staffs', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// level
		$this->level = new cField('as_staffs', 'as_staffs', 'x_level', 'level', '`level`', '`level`', 200, -1, FALSE, '`level`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->level->Sortable = TRUE; // Allow sort
		$this->fields['level'] = &$this->level;

		// photo
		$this->photo = new cField('as_staffs', 'as_staffs', 'x_photo', 'photo', '`photo`', '`photo`', 201, -1, FALSE, '`photo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->photo->Sortable = TRUE; // Allow sort
		$this->fields['photo'] = &$this->photo;

		// email
		$this->_email = new cField('as_staffs', 'as_staffs', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// password
		$this->password = new cField('as_staffs', 'as_staffs', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// lastLogin
		$this->lastLogin = new cField('as_staffs', 'as_staffs', 'x_lastLogin', 'lastLogin', '`lastLogin`', ew_CastDateFieldForLike('`lastLogin`', 0, "DB"), 135, 0, FALSE, '`lastLogin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastLogin->Sortable = TRUE; // Allow sort
		$this->lastLogin->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lastLogin'] = &$this->lastLogin;

		// createdDate
		$this->createdDate = new cField('as_staffs', 'as_staffs', 'x_createdDate', 'createdDate', '`createdDate`', ew_CastDateFieldForLike('`createdDate`', 0, "DB"), 135, 0, FALSE, '`createdDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdDate->Sortable = TRUE; // Allow sort
		$this->createdDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['createdDate'] = &$this->createdDate;

		// createdUserID
		$this->createdUserID = new cField('as_staffs', 'as_staffs', 'x_createdUserID', 'createdUserID', '`createdUserID`', '`createdUserID`', 3, -1, FALSE, '`createdUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdUserID->Sortable = TRUE; // Allow sort
		$this->createdUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['createdUserID'] = &$this->createdUserID;

		// modifiedDate
		$this->modifiedDate = new cField('as_staffs', 'as_staffs', 'x_modifiedDate', 'modifiedDate', '`modifiedDate`', ew_CastDateFieldForLike('`modifiedDate`', 0, "DB"), 135, 0, FALSE, '`modifiedDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedDate->Sortable = TRUE; // Allow sort
		$this->modifiedDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modifiedDate'] = &$this->modifiedDate;

		// modifiedUserID
		$this->modifiedUserID = new cField('as_staffs', 'as_staffs', 'x_modifiedUserID', 'modifiedUserID', '`modifiedUserID`', '`modifiedUserID`', 3, -1, FALSE, '`modifiedUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`as_staffs`";
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
			$this->staffID->setDbValue($conn->Insert_ID());
			$rs['staffID'] = $this->staffID->DbValue;
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
			if (array_key_exists('staffID', $rs))
				ew_AddFilter($where, ew_QuotedName('staffID', $this->DBID) . '=' . ew_QuotedValue($rs['staffID'], $this->staffID->FldDataType, $this->DBID));
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
		return "`staffID` = @staffID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->staffID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@staffID@", ew_AdjustSql($this->staffID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "as_staffslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "as_staffslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("as_staffsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("as_staffsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "as_staffsadd.php?" . $this->UrlParm($parm);
		else
			$url = "as_staffsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("as_staffsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("as_staffsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("as_staffsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "staffID:" . ew_VarToJson($this->staffID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->staffID->CurrentValue)) {
			$sUrl .= "staffID=" . urlencode($this->staffID->CurrentValue);
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
			if ($isPost && isset($_POST["staffID"]))
				$arKeys[] = ew_StripSlashes($_POST["staffID"]);
			elseif (isset($_GET["staffID"]))
				$arKeys[] = ew_StripSlashes($_GET["staffID"]);
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
			$this->staffID->CurrentValue = $key;
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
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffCode->setDbValue($rs->fields('staffCode'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->address->setDbValue($rs->fields('address'));
		$this->address2->setDbValue($rs->fields('address2'));
		$this->village->setDbValue($rs->fields('village'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city->setDbValue($rs->fields('city'));
		$this->zipCode->setDbValue($rs->fields('zipCode'));
		$this->province->setDbValue($rs->fields('province'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->position->setDbValue($rs->fields('position'));
		$this->part->setDbValue($rs->fields('part'));
		$this->status->setDbValue($rs->fields('status'));
		$this->level->setDbValue($rs->fields('level'));
		$this->photo->setDbValue($rs->fields('photo'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->lastLogin->setDbValue($rs->fields('lastLogin'));
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
		// staffID
		// staffCode
		// staffName
		// address
		// address2
		// village
		// district
		// city
		// zipCode
		// province
		// phone
		// position
		// part
		// status
		// level
		// photo
		// email
		// password
		// lastLogin
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID
		// staffID

		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->ViewValue = $this->staffCode->CurrentValue;
		$this->staffCode->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// address2
		$this->address2->ViewValue = $this->address2->CurrentValue;
		$this->address2->ViewCustomAttributes = "";

		// village
		$this->village->ViewValue = $this->village->CurrentValue;
		$this->village->ViewCustomAttributes = "";

		// district
		$this->district->ViewValue = $this->district->CurrentValue;
		$this->district->ViewCustomAttributes = "";

		// city
		$this->city->ViewValue = $this->city->CurrentValue;
		$this->city->ViewCustomAttributes = "";

		// zipCode
		$this->zipCode->ViewValue = $this->zipCode->CurrentValue;
		$this->zipCode->ViewCustomAttributes = "";

		// province
		$this->province->ViewValue = $this->province->CurrentValue;
		$this->province->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// position
		$this->position->ViewValue = $this->position->CurrentValue;
		$this->position->ViewCustomAttributes = "";

		// part
		$this->part->ViewValue = $this->part->CurrentValue;
		$this->part->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// level
		$this->level->ViewValue = $this->level->CurrentValue;
		$this->level->ViewCustomAttributes = "";

		// photo
		$this->photo->ViewValue = $this->photo->CurrentValue;
		$this->photo->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// lastLogin
		$this->lastLogin->ViewValue = $this->lastLogin->CurrentValue;
		$this->lastLogin->ViewValue = ew_FormatDateTime($this->lastLogin->ViewValue, 0);
		$this->lastLogin->ViewCustomAttributes = "";

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

		// staffID
		$this->staffID->LinkCustomAttributes = "";
		$this->staffID->HrefValue = "";
		$this->staffID->TooltipValue = "";

		// staffCode
		$this->staffCode->LinkCustomAttributes = "";
		$this->staffCode->HrefValue = "";
		$this->staffCode->TooltipValue = "";

		// staffName
		$this->staffName->LinkCustomAttributes = "";
		$this->staffName->HrefValue = "";
		$this->staffName->TooltipValue = "";

		// address
		$this->address->LinkCustomAttributes = "";
		$this->address->HrefValue = "";
		$this->address->TooltipValue = "";

		// address2
		$this->address2->LinkCustomAttributes = "";
		$this->address2->HrefValue = "";
		$this->address2->TooltipValue = "";

		// village
		$this->village->LinkCustomAttributes = "";
		$this->village->HrefValue = "";
		$this->village->TooltipValue = "";

		// district
		$this->district->LinkCustomAttributes = "";
		$this->district->HrefValue = "";
		$this->district->TooltipValue = "";

		// city
		$this->city->LinkCustomAttributes = "";
		$this->city->HrefValue = "";
		$this->city->TooltipValue = "";

		// zipCode
		$this->zipCode->LinkCustomAttributes = "";
		$this->zipCode->HrefValue = "";
		$this->zipCode->TooltipValue = "";

		// province
		$this->province->LinkCustomAttributes = "";
		$this->province->HrefValue = "";
		$this->province->TooltipValue = "";

		// phone
		$this->phone->LinkCustomAttributes = "";
		$this->phone->HrefValue = "";
		$this->phone->TooltipValue = "";

		// position
		$this->position->LinkCustomAttributes = "";
		$this->position->HrefValue = "";
		$this->position->TooltipValue = "";

		// part
		$this->part->LinkCustomAttributes = "";
		$this->part->HrefValue = "";
		$this->part->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// level
		$this->level->LinkCustomAttributes = "";
		$this->level->HrefValue = "";
		$this->level->TooltipValue = "";

		// photo
		$this->photo->LinkCustomAttributes = "";
		$this->photo->HrefValue = "";
		$this->photo->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

		// lastLogin
		$this->lastLogin->LinkCustomAttributes = "";
		$this->lastLogin->HrefValue = "";
		$this->lastLogin->TooltipValue = "";

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

		// staffID
		$this->staffID->EditAttrs["class"] = "form-control";
		$this->staffID->EditCustomAttributes = "";
		$this->staffID->EditValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->EditAttrs["class"] = "form-control";
		$this->staffCode->EditCustomAttributes = "";
		$this->staffCode->EditValue = $this->staffCode->CurrentValue;
		$this->staffCode->PlaceHolder = ew_RemoveHtml($this->staffCode->FldCaption());

		// staffName
		$this->staffName->EditAttrs["class"] = "form-control";
		$this->staffName->EditCustomAttributes = "";
		$this->staffName->EditValue = $this->staffName->CurrentValue;
		$this->staffName->PlaceHolder = ew_RemoveHtml($this->staffName->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// address2
		$this->address2->EditAttrs["class"] = "form-control";
		$this->address2->EditCustomAttributes = "";
		$this->address2->EditValue = $this->address2->CurrentValue;
		$this->address2->PlaceHolder = ew_RemoveHtml($this->address2->FldCaption());

		// village
		$this->village->EditAttrs["class"] = "form-control";
		$this->village->EditCustomAttributes = "";
		$this->village->EditValue = $this->village->CurrentValue;
		$this->village->PlaceHolder = ew_RemoveHtml($this->village->FldCaption());

		// district
		$this->district->EditAttrs["class"] = "form-control";
		$this->district->EditCustomAttributes = "";
		$this->district->EditValue = $this->district->CurrentValue;
		$this->district->PlaceHolder = ew_RemoveHtml($this->district->FldCaption());

		// city
		$this->city->EditAttrs["class"] = "form-control";
		$this->city->EditCustomAttributes = "";
		$this->city->EditValue = $this->city->CurrentValue;
		$this->city->PlaceHolder = ew_RemoveHtml($this->city->FldCaption());

		// zipCode
		$this->zipCode->EditAttrs["class"] = "form-control";
		$this->zipCode->EditCustomAttributes = "";
		$this->zipCode->EditValue = $this->zipCode->CurrentValue;
		$this->zipCode->PlaceHolder = ew_RemoveHtml($this->zipCode->FldCaption());

		// province
		$this->province->EditAttrs["class"] = "form-control";
		$this->province->EditCustomAttributes = "";
		$this->province->EditValue = $this->province->CurrentValue;
		$this->province->PlaceHolder = ew_RemoveHtml($this->province->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = $this->phone->CurrentValue;
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// position
		$this->position->EditAttrs["class"] = "form-control";
		$this->position->EditCustomAttributes = "";
		$this->position->EditValue = $this->position->CurrentValue;
		$this->position->PlaceHolder = ew_RemoveHtml($this->position->FldCaption());

		// part
		$this->part->EditAttrs["class"] = "form-control";
		$this->part->EditCustomAttributes = "";
		$this->part->EditValue = $this->part->CurrentValue;
		$this->part->PlaceHolder = ew_RemoveHtml($this->part->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// level
		$this->level->EditAttrs["class"] = "form-control";
		$this->level->EditCustomAttributes = "";
		$this->level->EditValue = $this->level->CurrentValue;
		$this->level->PlaceHolder = ew_RemoveHtml($this->level->FldCaption());

		// photo
		$this->photo->EditAttrs["class"] = "form-control";
		$this->photo->EditCustomAttributes = "";
		$this->photo->EditValue = $this->photo->CurrentValue;
		$this->photo->PlaceHolder = ew_RemoveHtml($this->photo->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// lastLogin
		$this->lastLogin->EditAttrs["class"] = "form-control";
		$this->lastLogin->EditCustomAttributes = "";
		$this->lastLogin->EditValue = ew_FormatDateTime($this->lastLogin->CurrentValue, 8);
		$this->lastLogin->PlaceHolder = ew_RemoveHtml($this->lastLogin->FldCaption());

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
					if ($this->staffID->Exportable) $Doc->ExportCaption($this->staffID);
					if ($this->staffCode->Exportable) $Doc->ExportCaption($this->staffCode);
					if ($this->staffName->Exportable) $Doc->ExportCaption($this->staffName);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->address2->Exportable) $Doc->ExportCaption($this->address2);
					if ($this->village->Exportable) $Doc->ExportCaption($this->village);
					if ($this->district->Exportable) $Doc->ExportCaption($this->district);
					if ($this->city->Exportable) $Doc->ExportCaption($this->city);
					if ($this->zipCode->Exportable) $Doc->ExportCaption($this->zipCode);
					if ($this->province->Exportable) $Doc->ExportCaption($this->province);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->position->Exportable) $Doc->ExportCaption($this->position);
					if ($this->part->Exportable) $Doc->ExportCaption($this->part);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->level->Exportable) $Doc->ExportCaption($this->level);
					if ($this->photo->Exportable) $Doc->ExportCaption($this->photo);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->lastLogin->Exportable) $Doc->ExportCaption($this->lastLogin);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				} else {
					if ($this->staffID->Exportable) $Doc->ExportCaption($this->staffID);
					if ($this->staffCode->Exportable) $Doc->ExportCaption($this->staffCode);
					if ($this->staffName->Exportable) $Doc->ExportCaption($this->staffName);
					if ($this->village->Exportable) $Doc->ExportCaption($this->village);
					if ($this->district->Exportable) $Doc->ExportCaption($this->district);
					if ($this->city->Exportable) $Doc->ExportCaption($this->city);
					if ($this->zipCode->Exportable) $Doc->ExportCaption($this->zipCode);
					if ($this->province->Exportable) $Doc->ExportCaption($this->province);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->position->Exportable) $Doc->ExportCaption($this->position);
					if ($this->part->Exportable) $Doc->ExportCaption($this->part);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->level->Exportable) $Doc->ExportCaption($this->level);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->lastLogin->Exportable) $Doc->ExportCaption($this->lastLogin);
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
						if ($this->staffID->Exportable) $Doc->ExportField($this->staffID);
						if ($this->staffCode->Exportable) $Doc->ExportField($this->staffCode);
						if ($this->staffName->Exportable) $Doc->ExportField($this->staffName);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->address2->Exportable) $Doc->ExportField($this->address2);
						if ($this->village->Exportable) $Doc->ExportField($this->village);
						if ($this->district->Exportable) $Doc->ExportField($this->district);
						if ($this->city->Exportable) $Doc->ExportField($this->city);
						if ($this->zipCode->Exportable) $Doc->ExportField($this->zipCode);
						if ($this->province->Exportable) $Doc->ExportField($this->province);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->position->Exportable) $Doc->ExportField($this->position);
						if ($this->part->Exportable) $Doc->ExportField($this->part);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->level->Exportable) $Doc->ExportField($this->level);
						if ($this->photo->Exportable) $Doc->ExportField($this->photo);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->lastLogin->Exportable) $Doc->ExportField($this->lastLogin);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					} else {
						if ($this->staffID->Exportable) $Doc->ExportField($this->staffID);
						if ($this->staffCode->Exportable) $Doc->ExportField($this->staffCode);
						if ($this->staffName->Exportable) $Doc->ExportField($this->staffName);
						if ($this->village->Exportable) $Doc->ExportField($this->village);
						if ($this->district->Exportable) $Doc->ExportField($this->district);
						if ($this->city->Exportable) $Doc->ExportField($this->city);
						if ($this->zipCode->Exportable) $Doc->ExportField($this->zipCode);
						if ($this->province->Exportable) $Doc->ExportField($this->province);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->position->Exportable) $Doc->ExportField($this->position);
						if ($this->part->Exportable) $Doc->ExportField($this->part);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->level->Exportable) $Doc->ExportField($this->level);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->lastLogin->Exportable) $Doc->ExportField($this->lastLogin);
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
