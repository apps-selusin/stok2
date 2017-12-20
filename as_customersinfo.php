<?php

// Global variable for table object
$as_customers = NULL;

//
// Table class for as_customers
//
class cas_customers extends cTable {
	var $customerID;
	var $customerCode;
	var $customerName;
	var $contactPerson;
	var $address;
	var $address2;
	var $village;
	var $district;
	var $city;
	var $zipCode;
	var $province;
	var $phone1;
	var $phone2;
	var $phone3;
	var $fax1;
	var $fax2;
	var $fax3;
	var $phonecp1;
	var $phonecp2;
	var $_email;
	var $limitBalance;
	var $balance;
	var $disc1;
	var $disc2;
	var $disc3;
	var $note;
	var $npwp;
	var $pkpName;
	var $staffCode;
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
		$this->TableVar = 'as_customers';
		$this->TableName = 'as_customers';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`as_customers`";
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

		// customerID
		$this->customerID = new cField('as_customers', 'as_customers', 'x_customerID', 'customerID', '`customerID`', '`customerID`', 3, -1, FALSE, '`customerID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->customerID->Sortable = TRUE; // Allow sort
		$this->customerID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['customerID'] = &$this->customerID;

		// customerCode
		$this->customerCode = new cField('as_customers', 'as_customers', 'x_customerCode', 'customerCode', '`customerCode`', '`customerCode`', 200, -1, FALSE, '`customerCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->customerCode->Sortable = TRUE; // Allow sort
		$this->fields['customerCode'] = &$this->customerCode;

		// customerName
		$this->customerName = new cField('as_customers', 'as_customers', 'x_customerName', 'customerName', '`customerName`', '`customerName`', 200, -1, FALSE, '`customerName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->customerName->Sortable = TRUE; // Allow sort
		$this->fields['customerName'] = &$this->customerName;

		// contactPerson
		$this->contactPerson = new cField('as_customers', 'as_customers', 'x_contactPerson', 'contactPerson', '`contactPerson`', '`contactPerson`', 200, -1, FALSE, '`contactPerson`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->contactPerson->Sortable = TRUE; // Allow sort
		$this->fields['contactPerson'] = &$this->contactPerson;

		// address
		$this->address = new cField('as_customers', 'as_customers', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// address2
		$this->address2 = new cField('as_customers', 'as_customers', 'x_address2', 'address2', '`address2`', '`address2`', 201, -1, FALSE, '`address2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->address2->Sortable = TRUE; // Allow sort
		$this->fields['address2'] = &$this->address2;

		// village
		$this->village = new cField('as_customers', 'as_customers', 'x_village', 'village', '`village`', '`village`', 200, -1, FALSE, '`village`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->village->Sortable = TRUE; // Allow sort
		$this->fields['village'] = &$this->village;

		// district
		$this->district = new cField('as_customers', 'as_customers', 'x_district', 'district', '`district`', '`district`', 200, -1, FALSE, '`district`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->district->Sortable = TRUE; // Allow sort
		$this->fields['district'] = &$this->district;

		// city
		$this->city = new cField('as_customers', 'as_customers', 'x_city', 'city', '`city`', '`city`', 200, -1, FALSE, '`city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->city->Sortable = TRUE; // Allow sort
		$this->fields['city'] = &$this->city;

		// zipCode
		$this->zipCode = new cField('as_customers', 'as_customers', 'x_zipCode', 'zipCode', '`zipCode`', '`zipCode`', 3, -1, FALSE, '`zipCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->zipCode->Sortable = TRUE; // Allow sort
		$this->zipCode->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['zipCode'] = &$this->zipCode;

		// province
		$this->province = new cField('as_customers', 'as_customers', 'x_province', 'province', '`province`', '`province`', 200, -1, FALSE, '`province`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->province->Sortable = TRUE; // Allow sort
		$this->fields['province'] = &$this->province;

		// phone1
		$this->phone1 = new cField('as_customers', 'as_customers', 'x_phone1', 'phone1', '`phone1`', '`phone1`', 200, -1, FALSE, '`phone1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone1->Sortable = TRUE; // Allow sort
		$this->fields['phone1'] = &$this->phone1;

		// phone2
		$this->phone2 = new cField('as_customers', 'as_customers', 'x_phone2', 'phone2', '`phone2`', '`phone2`', 200, -1, FALSE, '`phone2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone2->Sortable = TRUE; // Allow sort
		$this->fields['phone2'] = &$this->phone2;

		// phone3
		$this->phone3 = new cField('as_customers', 'as_customers', 'x_phone3', 'phone3', '`phone3`', '`phone3`', 200, -1, FALSE, '`phone3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone3->Sortable = TRUE; // Allow sort
		$this->fields['phone3'] = &$this->phone3;

		// fax1
		$this->fax1 = new cField('as_customers', 'as_customers', 'x_fax1', 'fax1', '`fax1`', '`fax1`', 200, -1, FALSE, '`fax1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax1->Sortable = TRUE; // Allow sort
		$this->fields['fax1'] = &$this->fax1;

		// fax2
		$this->fax2 = new cField('as_customers', 'as_customers', 'x_fax2', 'fax2', '`fax2`', '`fax2`', 200, -1, FALSE, '`fax2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax2->Sortable = TRUE; // Allow sort
		$this->fields['fax2'] = &$this->fax2;

		// fax3
		$this->fax3 = new cField('as_customers', 'as_customers', 'x_fax3', 'fax3', '`fax3`', '`fax3`', 200, -1, FALSE, '`fax3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax3->Sortable = TRUE; // Allow sort
		$this->fields['fax3'] = &$this->fax3;

		// phonecp1
		$this->phonecp1 = new cField('as_customers', 'as_customers', 'x_phonecp1', 'phonecp1', '`phonecp1`', '`phonecp1`', 200, -1, FALSE, '`phonecp1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phonecp1->Sortable = TRUE; // Allow sort
		$this->fields['phonecp1'] = &$this->phonecp1;

		// phonecp2
		$this->phonecp2 = new cField('as_customers', 'as_customers', 'x_phonecp2', 'phonecp2', '`phonecp2`', '`phonecp2`', 200, -1, FALSE, '`phonecp2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phonecp2->Sortable = TRUE; // Allow sort
		$this->fields['phonecp2'] = &$this->phonecp2;

		// email
		$this->_email = new cField('as_customers', 'as_customers', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// limitBalance
		$this->limitBalance = new cField('as_customers', 'as_customers', 'x_limitBalance', 'limitBalance', '`limitBalance`', '`limitBalance`', 3, -1, FALSE, '`limitBalance`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->limitBalance->Sortable = TRUE; // Allow sort
		$this->limitBalance->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['limitBalance'] = &$this->limitBalance;

		// balance
		$this->balance = new cField('as_customers', 'as_customers', 'x_balance', 'balance', '`balance`', '`balance`', 5, -1, FALSE, '`balance`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->balance->Sortable = TRUE; // Allow sort
		$this->balance->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['balance'] = &$this->balance;

		// disc1
		$this->disc1 = new cField('as_customers', 'as_customers', 'x_disc1', 'disc1', '`disc1`', '`disc1`', 3, -1, FALSE, '`disc1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->disc1->Sortable = TRUE; // Allow sort
		$this->disc1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['disc1'] = &$this->disc1;

		// disc2
		$this->disc2 = new cField('as_customers', 'as_customers', 'x_disc2', 'disc2', '`disc2`', '`disc2`', 3, -1, FALSE, '`disc2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->disc2->Sortable = TRUE; // Allow sort
		$this->disc2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['disc2'] = &$this->disc2;

		// disc3
		$this->disc3 = new cField('as_customers', 'as_customers', 'x_disc3', 'disc3', '`disc3`', '`disc3`', 3, -1, FALSE, '`disc3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->disc3->Sortable = TRUE; // Allow sort
		$this->disc3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['disc3'] = &$this->disc3;

		// note
		$this->note = new cField('as_customers', 'as_customers', 'x_note', 'note', '`note`', '`note`', 201, -1, FALSE, '`note`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->note->Sortable = TRUE; // Allow sort
		$this->fields['note'] = &$this->note;

		// npwp
		$this->npwp = new cField('as_customers', 'as_customers', 'x_npwp', 'npwp', '`npwp`', '`npwp`', 200, -1, FALSE, '`npwp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->npwp->Sortable = TRUE; // Allow sort
		$this->fields['npwp'] = &$this->npwp;

		// pkpName
		$this->pkpName = new cField('as_customers', 'as_customers', 'x_pkpName', 'pkpName', '`pkpName`', '`pkpName`', 200, -1, FALSE, '`pkpName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pkpName->Sortable = TRUE; // Allow sort
		$this->fields['pkpName'] = &$this->pkpName;

		// staffCode
		$this->staffCode = new cField('as_customers', 'as_customers', 'x_staffCode', 'staffCode', '`staffCode`', '`staffCode`', 200, -1, FALSE, '`staffCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffCode->Sortable = TRUE; // Allow sort
		$this->fields['staffCode'] = &$this->staffCode;

		// createdDate
		$this->createdDate = new cField('as_customers', 'as_customers', 'x_createdDate', 'createdDate', '`createdDate`', ew_CastDateFieldForLike('`createdDate`', 0, "DB"), 135, 0, FALSE, '`createdDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdDate->Sortable = TRUE; // Allow sort
		$this->createdDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['createdDate'] = &$this->createdDate;

		// createdUserID
		$this->createdUserID = new cField('as_customers', 'as_customers', 'x_createdUserID', 'createdUserID', '`createdUserID`', '`createdUserID`', 3, -1, FALSE, '`createdUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->createdUserID->Sortable = TRUE; // Allow sort
		$this->createdUserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['createdUserID'] = &$this->createdUserID;

		// modifiedDate
		$this->modifiedDate = new cField('as_customers', 'as_customers', 'x_modifiedDate', 'modifiedDate', '`modifiedDate`', ew_CastDateFieldForLike('`modifiedDate`', 0, "DB"), 135, 0, FALSE, '`modifiedDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modifiedDate->Sortable = TRUE; // Allow sort
		$this->modifiedDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modifiedDate'] = &$this->modifiedDate;

		// modifiedUserID
		$this->modifiedUserID = new cField('as_customers', 'as_customers', 'x_modifiedUserID', 'modifiedUserID', '`modifiedUserID`', '`modifiedUserID`', 3, -1, FALSE, '`modifiedUserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`as_customers`";
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
			$this->customerID->setDbValue($conn->Insert_ID());
			$rs['customerID'] = $this->customerID->DbValue;
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
			if (array_key_exists('customerID', $rs))
				ew_AddFilter($where, ew_QuotedName('customerID', $this->DBID) . '=' . ew_QuotedValue($rs['customerID'], $this->customerID->FldDataType, $this->DBID));
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
		return "`customerID` = @customerID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->customerID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@customerID@", ew_AdjustSql($this->customerID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "as_customerslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "as_customerslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("as_customersview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("as_customersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "as_customersadd.php?" . $this->UrlParm($parm);
		else
			$url = "as_customersadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("as_customersedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("as_customersadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("as_customersdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "customerID:" . ew_VarToJson($this->customerID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->customerID->CurrentValue)) {
			$sUrl .= "customerID=" . urlencode($this->customerID->CurrentValue);
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
			if ($isPost && isset($_POST["customerID"]))
				$arKeys[] = ew_StripSlashes($_POST["customerID"]);
			elseif (isset($_GET["customerID"]))
				$arKeys[] = ew_StripSlashes($_GET["customerID"]);
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
			$this->customerID->CurrentValue = $key;
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
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerCode->setDbValue($rs->fields('customerCode'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->contactPerson->setDbValue($rs->fields('contactPerson'));
		$this->address->setDbValue($rs->fields('address'));
		$this->address2->setDbValue($rs->fields('address2'));
		$this->village->setDbValue($rs->fields('village'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city->setDbValue($rs->fields('city'));
		$this->zipCode->setDbValue($rs->fields('zipCode'));
		$this->province->setDbValue($rs->fields('province'));
		$this->phone1->setDbValue($rs->fields('phone1'));
		$this->phone2->setDbValue($rs->fields('phone2'));
		$this->phone3->setDbValue($rs->fields('phone3'));
		$this->fax1->setDbValue($rs->fields('fax1'));
		$this->fax2->setDbValue($rs->fields('fax2'));
		$this->fax3->setDbValue($rs->fields('fax3'));
		$this->phonecp1->setDbValue($rs->fields('phonecp1'));
		$this->phonecp2->setDbValue($rs->fields('phonecp2'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->limitBalance->setDbValue($rs->fields('limitBalance'));
		$this->balance->setDbValue($rs->fields('balance'));
		$this->disc1->setDbValue($rs->fields('disc1'));
		$this->disc2->setDbValue($rs->fields('disc2'));
		$this->disc3->setDbValue($rs->fields('disc3'));
		$this->note->setDbValue($rs->fields('note'));
		$this->npwp->setDbValue($rs->fields('npwp'));
		$this->pkpName->setDbValue($rs->fields('pkpName'));
		$this->staffCode->setDbValue($rs->fields('staffCode'));
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
		// customerID
		// customerCode
		// customerName
		// contactPerson
		// address
		// address2
		// village
		// district
		// city
		// zipCode
		// province
		// phone1
		// phone2
		// phone3
		// fax1
		// fax2
		// fax3
		// phonecp1
		// phonecp2
		// email
		// limitBalance
		// balance
		// disc1
		// disc2
		// disc3
		// note
		// npwp
		// pkpName
		// staffCode
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID
		// customerID

		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerCode
		$this->customerCode->ViewValue = $this->customerCode->CurrentValue;
		$this->customerCode->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// contactPerson
		$this->contactPerson->ViewValue = $this->contactPerson->CurrentValue;
		$this->contactPerson->ViewCustomAttributes = "";

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

		// phone1
		$this->phone1->ViewValue = $this->phone1->CurrentValue;
		$this->phone1->ViewCustomAttributes = "";

		// phone2
		$this->phone2->ViewValue = $this->phone2->CurrentValue;
		$this->phone2->ViewCustomAttributes = "";

		// phone3
		$this->phone3->ViewValue = $this->phone3->CurrentValue;
		$this->phone3->ViewCustomAttributes = "";

		// fax1
		$this->fax1->ViewValue = $this->fax1->CurrentValue;
		$this->fax1->ViewCustomAttributes = "";

		// fax2
		$this->fax2->ViewValue = $this->fax2->CurrentValue;
		$this->fax2->ViewCustomAttributes = "";

		// fax3
		$this->fax3->ViewValue = $this->fax3->CurrentValue;
		$this->fax3->ViewCustomAttributes = "";

		// phonecp1
		$this->phonecp1->ViewValue = $this->phonecp1->CurrentValue;
		$this->phonecp1->ViewCustomAttributes = "";

		// phonecp2
		$this->phonecp2->ViewValue = $this->phonecp2->CurrentValue;
		$this->phonecp2->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// limitBalance
		$this->limitBalance->ViewValue = $this->limitBalance->CurrentValue;
		$this->limitBalance->ViewCustomAttributes = "";

		// balance
		$this->balance->ViewValue = $this->balance->CurrentValue;
		$this->balance->ViewCustomAttributes = "";

		// disc1
		$this->disc1->ViewValue = $this->disc1->CurrentValue;
		$this->disc1->ViewCustomAttributes = "";

		// disc2
		$this->disc2->ViewValue = $this->disc2->CurrentValue;
		$this->disc2->ViewCustomAttributes = "";

		// disc3
		$this->disc3->ViewValue = $this->disc3->CurrentValue;
		$this->disc3->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// npwp
		$this->npwp->ViewValue = $this->npwp->CurrentValue;
		$this->npwp->ViewCustomAttributes = "";

		// pkpName
		$this->pkpName->ViewValue = $this->pkpName->CurrentValue;
		$this->pkpName->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->ViewValue = $this->staffCode->CurrentValue;
		$this->staffCode->ViewCustomAttributes = "";

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

		// customerID
		$this->customerID->LinkCustomAttributes = "";
		$this->customerID->HrefValue = "";
		$this->customerID->TooltipValue = "";

		// customerCode
		$this->customerCode->LinkCustomAttributes = "";
		$this->customerCode->HrefValue = "";
		$this->customerCode->TooltipValue = "";

		// customerName
		$this->customerName->LinkCustomAttributes = "";
		$this->customerName->HrefValue = "";
		$this->customerName->TooltipValue = "";

		// contactPerson
		$this->contactPerson->LinkCustomAttributes = "";
		$this->contactPerson->HrefValue = "";
		$this->contactPerson->TooltipValue = "";

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

		// phone1
		$this->phone1->LinkCustomAttributes = "";
		$this->phone1->HrefValue = "";
		$this->phone1->TooltipValue = "";

		// phone2
		$this->phone2->LinkCustomAttributes = "";
		$this->phone2->HrefValue = "";
		$this->phone2->TooltipValue = "";

		// phone3
		$this->phone3->LinkCustomAttributes = "";
		$this->phone3->HrefValue = "";
		$this->phone3->TooltipValue = "";

		// fax1
		$this->fax1->LinkCustomAttributes = "";
		$this->fax1->HrefValue = "";
		$this->fax1->TooltipValue = "";

		// fax2
		$this->fax2->LinkCustomAttributes = "";
		$this->fax2->HrefValue = "";
		$this->fax2->TooltipValue = "";

		// fax3
		$this->fax3->LinkCustomAttributes = "";
		$this->fax3->HrefValue = "";
		$this->fax3->TooltipValue = "";

		// phonecp1
		$this->phonecp1->LinkCustomAttributes = "";
		$this->phonecp1->HrefValue = "";
		$this->phonecp1->TooltipValue = "";

		// phonecp2
		$this->phonecp2->LinkCustomAttributes = "";
		$this->phonecp2->HrefValue = "";
		$this->phonecp2->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// limitBalance
		$this->limitBalance->LinkCustomAttributes = "";
		$this->limitBalance->HrefValue = "";
		$this->limitBalance->TooltipValue = "";

		// balance
		$this->balance->LinkCustomAttributes = "";
		$this->balance->HrefValue = "";
		$this->balance->TooltipValue = "";

		// disc1
		$this->disc1->LinkCustomAttributes = "";
		$this->disc1->HrefValue = "";
		$this->disc1->TooltipValue = "";

		// disc2
		$this->disc2->LinkCustomAttributes = "";
		$this->disc2->HrefValue = "";
		$this->disc2->TooltipValue = "";

		// disc3
		$this->disc3->LinkCustomAttributes = "";
		$this->disc3->HrefValue = "";
		$this->disc3->TooltipValue = "";

		// note
		$this->note->LinkCustomAttributes = "";
		$this->note->HrefValue = "";
		$this->note->TooltipValue = "";

		// npwp
		$this->npwp->LinkCustomAttributes = "";
		$this->npwp->HrefValue = "";
		$this->npwp->TooltipValue = "";

		// pkpName
		$this->pkpName->LinkCustomAttributes = "";
		$this->pkpName->HrefValue = "";
		$this->pkpName->TooltipValue = "";

		// staffCode
		$this->staffCode->LinkCustomAttributes = "";
		$this->staffCode->HrefValue = "";
		$this->staffCode->TooltipValue = "";

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

		// customerID
		$this->customerID->EditAttrs["class"] = "form-control";
		$this->customerID->EditCustomAttributes = "";
		$this->customerID->EditValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerCode
		$this->customerCode->EditAttrs["class"] = "form-control";
		$this->customerCode->EditCustomAttributes = "";
		$this->customerCode->EditValue = $this->customerCode->CurrentValue;
		$this->customerCode->PlaceHolder = ew_RemoveHtml($this->customerCode->FldCaption());

		// customerName
		$this->customerName->EditAttrs["class"] = "form-control";
		$this->customerName->EditCustomAttributes = "";
		$this->customerName->EditValue = $this->customerName->CurrentValue;
		$this->customerName->PlaceHolder = ew_RemoveHtml($this->customerName->FldCaption());

		// contactPerson
		$this->contactPerson->EditAttrs["class"] = "form-control";
		$this->contactPerson->EditCustomAttributes = "";
		$this->contactPerson->EditValue = $this->contactPerson->CurrentValue;
		$this->contactPerson->PlaceHolder = ew_RemoveHtml($this->contactPerson->FldCaption());

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

		// phone1
		$this->phone1->EditAttrs["class"] = "form-control";
		$this->phone1->EditCustomAttributes = "";
		$this->phone1->EditValue = $this->phone1->CurrentValue;
		$this->phone1->PlaceHolder = ew_RemoveHtml($this->phone1->FldCaption());

		// phone2
		$this->phone2->EditAttrs["class"] = "form-control";
		$this->phone2->EditCustomAttributes = "";
		$this->phone2->EditValue = $this->phone2->CurrentValue;
		$this->phone2->PlaceHolder = ew_RemoveHtml($this->phone2->FldCaption());

		// phone3
		$this->phone3->EditAttrs["class"] = "form-control";
		$this->phone3->EditCustomAttributes = "";
		$this->phone3->EditValue = $this->phone3->CurrentValue;
		$this->phone3->PlaceHolder = ew_RemoveHtml($this->phone3->FldCaption());

		// fax1
		$this->fax1->EditAttrs["class"] = "form-control";
		$this->fax1->EditCustomAttributes = "";
		$this->fax1->EditValue = $this->fax1->CurrentValue;
		$this->fax1->PlaceHolder = ew_RemoveHtml($this->fax1->FldCaption());

		// fax2
		$this->fax2->EditAttrs["class"] = "form-control";
		$this->fax2->EditCustomAttributes = "";
		$this->fax2->EditValue = $this->fax2->CurrentValue;
		$this->fax2->PlaceHolder = ew_RemoveHtml($this->fax2->FldCaption());

		// fax3
		$this->fax3->EditAttrs["class"] = "form-control";
		$this->fax3->EditCustomAttributes = "";
		$this->fax3->EditValue = $this->fax3->CurrentValue;
		$this->fax3->PlaceHolder = ew_RemoveHtml($this->fax3->FldCaption());

		// phonecp1
		$this->phonecp1->EditAttrs["class"] = "form-control";
		$this->phonecp1->EditCustomAttributes = "";
		$this->phonecp1->EditValue = $this->phonecp1->CurrentValue;
		$this->phonecp1->PlaceHolder = ew_RemoveHtml($this->phonecp1->FldCaption());

		// phonecp2
		$this->phonecp2->EditAttrs["class"] = "form-control";
		$this->phonecp2->EditCustomAttributes = "";
		$this->phonecp2->EditValue = $this->phonecp2->CurrentValue;
		$this->phonecp2->PlaceHolder = ew_RemoveHtml($this->phonecp2->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// limitBalance
		$this->limitBalance->EditAttrs["class"] = "form-control";
		$this->limitBalance->EditCustomAttributes = "";
		$this->limitBalance->EditValue = $this->limitBalance->CurrentValue;
		$this->limitBalance->PlaceHolder = ew_RemoveHtml($this->limitBalance->FldCaption());

		// balance
		$this->balance->EditAttrs["class"] = "form-control";
		$this->balance->EditCustomAttributes = "";
		$this->balance->EditValue = $this->balance->CurrentValue;
		$this->balance->PlaceHolder = ew_RemoveHtml($this->balance->FldCaption());
		if (strval($this->balance->EditValue) <> "" && is_numeric($this->balance->EditValue)) $this->balance->EditValue = ew_FormatNumber($this->balance->EditValue, -2, -1, -2, 0);

		// disc1
		$this->disc1->EditAttrs["class"] = "form-control";
		$this->disc1->EditCustomAttributes = "";
		$this->disc1->EditValue = $this->disc1->CurrentValue;
		$this->disc1->PlaceHolder = ew_RemoveHtml($this->disc1->FldCaption());

		// disc2
		$this->disc2->EditAttrs["class"] = "form-control";
		$this->disc2->EditCustomAttributes = "";
		$this->disc2->EditValue = $this->disc2->CurrentValue;
		$this->disc2->PlaceHolder = ew_RemoveHtml($this->disc2->FldCaption());

		// disc3
		$this->disc3->EditAttrs["class"] = "form-control";
		$this->disc3->EditCustomAttributes = "";
		$this->disc3->EditValue = $this->disc3->CurrentValue;
		$this->disc3->PlaceHolder = ew_RemoveHtml($this->disc3->FldCaption());

		// note
		$this->note->EditAttrs["class"] = "form-control";
		$this->note->EditCustomAttributes = "";
		$this->note->EditValue = $this->note->CurrentValue;
		$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

		// npwp
		$this->npwp->EditAttrs["class"] = "form-control";
		$this->npwp->EditCustomAttributes = "";
		$this->npwp->EditValue = $this->npwp->CurrentValue;
		$this->npwp->PlaceHolder = ew_RemoveHtml($this->npwp->FldCaption());

		// pkpName
		$this->pkpName->EditAttrs["class"] = "form-control";
		$this->pkpName->EditCustomAttributes = "";
		$this->pkpName->EditValue = $this->pkpName->CurrentValue;
		$this->pkpName->PlaceHolder = ew_RemoveHtml($this->pkpName->FldCaption());

		// staffCode
		$this->staffCode->EditAttrs["class"] = "form-control";
		$this->staffCode->EditCustomAttributes = "";
		$this->staffCode->EditValue = $this->staffCode->CurrentValue;
		$this->staffCode->PlaceHolder = ew_RemoveHtml($this->staffCode->FldCaption());

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
					if ($this->customerID->Exportable) $Doc->ExportCaption($this->customerID);
					if ($this->customerCode->Exportable) $Doc->ExportCaption($this->customerCode);
					if ($this->customerName->Exportable) $Doc->ExportCaption($this->customerName);
					if ($this->contactPerson->Exportable) $Doc->ExportCaption($this->contactPerson);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->address2->Exportable) $Doc->ExportCaption($this->address2);
					if ($this->village->Exportable) $Doc->ExportCaption($this->village);
					if ($this->district->Exportable) $Doc->ExportCaption($this->district);
					if ($this->city->Exportable) $Doc->ExportCaption($this->city);
					if ($this->zipCode->Exportable) $Doc->ExportCaption($this->zipCode);
					if ($this->province->Exportable) $Doc->ExportCaption($this->province);
					if ($this->phone1->Exportable) $Doc->ExportCaption($this->phone1);
					if ($this->phone2->Exportable) $Doc->ExportCaption($this->phone2);
					if ($this->phone3->Exportable) $Doc->ExportCaption($this->phone3);
					if ($this->fax1->Exportable) $Doc->ExportCaption($this->fax1);
					if ($this->fax2->Exportable) $Doc->ExportCaption($this->fax2);
					if ($this->fax3->Exportable) $Doc->ExportCaption($this->fax3);
					if ($this->phonecp1->Exportable) $Doc->ExportCaption($this->phonecp1);
					if ($this->phonecp2->Exportable) $Doc->ExportCaption($this->phonecp2);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->limitBalance->Exportable) $Doc->ExportCaption($this->limitBalance);
					if ($this->balance->Exportable) $Doc->ExportCaption($this->balance);
					if ($this->disc1->Exportable) $Doc->ExportCaption($this->disc1);
					if ($this->disc2->Exportable) $Doc->ExportCaption($this->disc2);
					if ($this->disc3->Exportable) $Doc->ExportCaption($this->disc3);
					if ($this->note->Exportable) $Doc->ExportCaption($this->note);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->pkpName->Exportable) $Doc->ExportCaption($this->pkpName);
					if ($this->staffCode->Exportable) $Doc->ExportCaption($this->staffCode);
					if ($this->createdDate->Exportable) $Doc->ExportCaption($this->createdDate);
					if ($this->createdUserID->Exportable) $Doc->ExportCaption($this->createdUserID);
					if ($this->modifiedDate->Exportable) $Doc->ExportCaption($this->modifiedDate);
					if ($this->modifiedUserID->Exportable) $Doc->ExportCaption($this->modifiedUserID);
				} else {
					if ($this->customerID->Exportable) $Doc->ExportCaption($this->customerID);
					if ($this->customerCode->Exportable) $Doc->ExportCaption($this->customerCode);
					if ($this->customerName->Exportable) $Doc->ExportCaption($this->customerName);
					if ($this->contactPerson->Exportable) $Doc->ExportCaption($this->contactPerson);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->village->Exportable) $Doc->ExportCaption($this->village);
					if ($this->district->Exportable) $Doc->ExportCaption($this->district);
					if ($this->city->Exportable) $Doc->ExportCaption($this->city);
					if ($this->zipCode->Exportable) $Doc->ExportCaption($this->zipCode);
					if ($this->province->Exportable) $Doc->ExportCaption($this->province);
					if ($this->phone1->Exportable) $Doc->ExportCaption($this->phone1);
					if ($this->phone2->Exportable) $Doc->ExportCaption($this->phone2);
					if ($this->phone3->Exportable) $Doc->ExportCaption($this->phone3);
					if ($this->fax1->Exportable) $Doc->ExportCaption($this->fax1);
					if ($this->fax2->Exportable) $Doc->ExportCaption($this->fax2);
					if ($this->fax3->Exportable) $Doc->ExportCaption($this->fax3);
					if ($this->phonecp1->Exportable) $Doc->ExportCaption($this->phonecp1);
					if ($this->phonecp2->Exportable) $Doc->ExportCaption($this->phonecp2);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->limitBalance->Exportable) $Doc->ExportCaption($this->limitBalance);
					if ($this->balance->Exportable) $Doc->ExportCaption($this->balance);
					if ($this->disc1->Exportable) $Doc->ExportCaption($this->disc1);
					if ($this->disc2->Exportable) $Doc->ExportCaption($this->disc2);
					if ($this->disc3->Exportable) $Doc->ExportCaption($this->disc3);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->pkpName->Exportable) $Doc->ExportCaption($this->pkpName);
					if ($this->staffCode->Exportable) $Doc->ExportCaption($this->staffCode);
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
						if ($this->customerID->Exportable) $Doc->ExportField($this->customerID);
						if ($this->customerCode->Exportable) $Doc->ExportField($this->customerCode);
						if ($this->customerName->Exportable) $Doc->ExportField($this->customerName);
						if ($this->contactPerson->Exportable) $Doc->ExportField($this->contactPerson);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->address2->Exportable) $Doc->ExportField($this->address2);
						if ($this->village->Exportable) $Doc->ExportField($this->village);
						if ($this->district->Exportable) $Doc->ExportField($this->district);
						if ($this->city->Exportable) $Doc->ExportField($this->city);
						if ($this->zipCode->Exportable) $Doc->ExportField($this->zipCode);
						if ($this->province->Exportable) $Doc->ExportField($this->province);
						if ($this->phone1->Exportable) $Doc->ExportField($this->phone1);
						if ($this->phone2->Exportable) $Doc->ExportField($this->phone2);
						if ($this->phone3->Exportable) $Doc->ExportField($this->phone3);
						if ($this->fax1->Exportable) $Doc->ExportField($this->fax1);
						if ($this->fax2->Exportable) $Doc->ExportField($this->fax2);
						if ($this->fax3->Exportable) $Doc->ExportField($this->fax3);
						if ($this->phonecp1->Exportable) $Doc->ExportField($this->phonecp1);
						if ($this->phonecp2->Exportable) $Doc->ExportField($this->phonecp2);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->limitBalance->Exportable) $Doc->ExportField($this->limitBalance);
						if ($this->balance->Exportable) $Doc->ExportField($this->balance);
						if ($this->disc1->Exportable) $Doc->ExportField($this->disc1);
						if ($this->disc2->Exportable) $Doc->ExportField($this->disc2);
						if ($this->disc3->Exportable) $Doc->ExportField($this->disc3);
						if ($this->note->Exportable) $Doc->ExportField($this->note);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->pkpName->Exportable) $Doc->ExportField($this->pkpName);
						if ($this->staffCode->Exportable) $Doc->ExportField($this->staffCode);
						if ($this->createdDate->Exportable) $Doc->ExportField($this->createdDate);
						if ($this->createdUserID->Exportable) $Doc->ExportField($this->createdUserID);
						if ($this->modifiedDate->Exportable) $Doc->ExportField($this->modifiedDate);
						if ($this->modifiedUserID->Exportable) $Doc->ExportField($this->modifiedUserID);
					} else {
						if ($this->customerID->Exportable) $Doc->ExportField($this->customerID);
						if ($this->customerCode->Exportable) $Doc->ExportField($this->customerCode);
						if ($this->customerName->Exportable) $Doc->ExportField($this->customerName);
						if ($this->contactPerson->Exportable) $Doc->ExportField($this->contactPerson);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->village->Exportable) $Doc->ExportField($this->village);
						if ($this->district->Exportable) $Doc->ExportField($this->district);
						if ($this->city->Exportable) $Doc->ExportField($this->city);
						if ($this->zipCode->Exportable) $Doc->ExportField($this->zipCode);
						if ($this->province->Exportable) $Doc->ExportField($this->province);
						if ($this->phone1->Exportable) $Doc->ExportField($this->phone1);
						if ($this->phone2->Exportable) $Doc->ExportField($this->phone2);
						if ($this->phone3->Exportable) $Doc->ExportField($this->phone3);
						if ($this->fax1->Exportable) $Doc->ExportField($this->fax1);
						if ($this->fax2->Exportable) $Doc->ExportField($this->fax2);
						if ($this->fax3->Exportable) $Doc->ExportField($this->fax3);
						if ($this->phonecp1->Exportable) $Doc->ExportField($this->phonecp1);
						if ($this->phonecp2->Exportable) $Doc->ExportField($this->phonecp2);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->limitBalance->Exportable) $Doc->ExportField($this->limitBalance);
						if ($this->balance->Exportable) $Doc->ExportField($this->balance);
						if ($this->disc1->Exportable) $Doc->ExportField($this->disc1);
						if ($this->disc2->Exportable) $Doc->ExportField($this->disc2);
						if ($this->disc3->Exportable) $Doc->ExportField($this->disc3);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->pkpName->Exportable) $Doc->ExportField($this->pkpName);
						if ($this->staffCode->Exportable) $Doc->ExportField($this->staffCode);
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
