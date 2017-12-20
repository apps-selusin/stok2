<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_customersinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_customers_delete = NULL; // Initialize page object first

class cas_customers_delete extends cas_customers {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_customers';

	// Page object name
	var $PageObjName = 'as_customers_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (as_customers)
		if (!isset($GLOBALS["as_customers"]) || get_class($GLOBALS["as_customers"]) == "cas_customers") {
			$GLOBALS["as_customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_customers"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_customers', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_96_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_96_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("as_customerslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->customerID->SetVisibility();
		$this->customerID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->customerCode->SetVisibility();
		$this->customerName->SetVisibility();
		$this->contactPerson->SetVisibility();
		$this->address->SetVisibility();
		$this->village->SetVisibility();
		$this->district->SetVisibility();
		$this->city->SetVisibility();
		$this->zipCode->SetVisibility();
		$this->province->SetVisibility();
		$this->phone1->SetVisibility();
		$this->phone2->SetVisibility();
		$this->phone3->SetVisibility();
		$this->fax1->SetVisibility();
		$this->fax2->SetVisibility();
		$this->fax3->SetVisibility();
		$this->phonecp1->SetVisibility();
		$this->phonecp2->SetVisibility();
		$this->_email->SetVisibility();
		$this->limitBalance->SetVisibility();
		$this->balance->SetVisibility();
		$this->disc1->SetVisibility();
		$this->disc2->SetVisibility();
		$this->disc3->SetVisibility();
		$this->npwp->SetVisibility();
		$this->pkpName->SetVisibility();
		$this->staffCode->SetVisibility();
		$this->createdDate->SetVisibility();
		$this->createdUserID->SetVisibility();
		$this->modifiedDate->SetVisibility();
		$this->modifiedUserID->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $as_customers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_customers);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("as_customerslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_customers class, as_customersinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("as_customerslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->customerID->DbValue = $row['customerID'];
		$this->customerCode->DbValue = $row['customerCode'];
		$this->customerName->DbValue = $row['customerName'];
		$this->contactPerson->DbValue = $row['contactPerson'];
		$this->address->DbValue = $row['address'];
		$this->address2->DbValue = $row['address2'];
		$this->village->DbValue = $row['village'];
		$this->district->DbValue = $row['district'];
		$this->city->DbValue = $row['city'];
		$this->zipCode->DbValue = $row['zipCode'];
		$this->province->DbValue = $row['province'];
		$this->phone1->DbValue = $row['phone1'];
		$this->phone2->DbValue = $row['phone2'];
		$this->phone3->DbValue = $row['phone3'];
		$this->fax1->DbValue = $row['fax1'];
		$this->fax2->DbValue = $row['fax2'];
		$this->fax3->DbValue = $row['fax3'];
		$this->phonecp1->DbValue = $row['phonecp1'];
		$this->phonecp2->DbValue = $row['phonecp2'];
		$this->_email->DbValue = $row['email'];
		$this->limitBalance->DbValue = $row['limitBalance'];
		$this->balance->DbValue = $row['balance'];
		$this->disc1->DbValue = $row['disc1'];
		$this->disc2->DbValue = $row['disc2'];
		$this->disc3->DbValue = $row['disc3'];
		$this->note->DbValue = $row['note'];
		$this->npwp->DbValue = $row['npwp'];
		$this->pkpName->DbValue = $row['pkpName'];
		$this->staffCode->DbValue = $row['staffCode'];
		$this->createdDate->DbValue = $row['createdDate'];
		$this->createdUserID->DbValue = $row['createdUserID'];
		$this->modifiedDate->DbValue = $row['modifiedDate'];
		$this->modifiedUserID->DbValue = $row['modifiedUserID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->balance->FormValue == $this->balance->CurrentValue && is_numeric(ew_StrToFloat($this->balance->CurrentValue)))
			$this->balance->CurrentValue = ew_StrToFloat($this->balance->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['customerID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_customerslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($as_customers_delete)) $as_customers_delete = new cas_customers_delete();

// Page init
$as_customers_delete->Page_Init();

// Page main
$as_customers_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_customers_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_customersdelete = new ew_Form("fas_customersdelete", "delete");

// Form_CustomValidate event
fas_customersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_customersdelete.ValidateRequired = true;
<?php } else { ?>
fas_customersdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $as_customers_delete->ShowPageHeader(); ?>
<?php
$as_customers_delete->ShowMessage();
?>
<form name="fas_customersdelete" id="fas_customersdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_customers_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_customers_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_customers">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_customers_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_customers->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_customers->customerID->Visible) { // customerID ?>
		<th><span id="elh_as_customers_customerID" class="as_customers_customerID"><?php echo $as_customers->customerID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
		<th><span id="elh_as_customers_customerCode" class="as_customers_customerCode"><?php echo $as_customers->customerCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->customerName->Visible) { // customerName ?>
		<th><span id="elh_as_customers_customerName" class="as_customers_customerName"><?php echo $as_customers->customerName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
		<th><span id="elh_as_customers_contactPerson" class="as_customers_contactPerson"><?php echo $as_customers->contactPerson->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->address->Visible) { // address ?>
		<th><span id="elh_as_customers_address" class="as_customers_address"><?php echo $as_customers->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->village->Visible) { // village ?>
		<th><span id="elh_as_customers_village" class="as_customers_village"><?php echo $as_customers->village->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->district->Visible) { // district ?>
		<th><span id="elh_as_customers_district" class="as_customers_district"><?php echo $as_customers->district->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->city->Visible) { // city ?>
		<th><span id="elh_as_customers_city" class="as_customers_city"><?php echo $as_customers->city->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
		<th><span id="elh_as_customers_zipCode" class="as_customers_zipCode"><?php echo $as_customers->zipCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->province->Visible) { // province ?>
		<th><span id="elh_as_customers_province" class="as_customers_province"><?php echo $as_customers->province->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->phone1->Visible) { // phone1 ?>
		<th><span id="elh_as_customers_phone1" class="as_customers_phone1"><?php echo $as_customers->phone1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->phone2->Visible) { // phone2 ?>
		<th><span id="elh_as_customers_phone2" class="as_customers_phone2"><?php echo $as_customers->phone2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->phone3->Visible) { // phone3 ?>
		<th><span id="elh_as_customers_phone3" class="as_customers_phone3"><?php echo $as_customers->phone3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->fax1->Visible) { // fax1 ?>
		<th><span id="elh_as_customers_fax1" class="as_customers_fax1"><?php echo $as_customers->fax1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->fax2->Visible) { // fax2 ?>
		<th><span id="elh_as_customers_fax2" class="as_customers_fax2"><?php echo $as_customers->fax2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->fax3->Visible) { // fax3 ?>
		<th><span id="elh_as_customers_fax3" class="as_customers_fax3"><?php echo $as_customers->fax3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
		<th><span id="elh_as_customers_phonecp1" class="as_customers_phonecp1"><?php echo $as_customers->phonecp1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
		<th><span id="elh_as_customers_phonecp2" class="as_customers_phonecp2"><?php echo $as_customers->phonecp2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->_email->Visible) { // email ?>
		<th><span id="elh_as_customers__email" class="as_customers__email"><?php echo $as_customers->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
		<th><span id="elh_as_customers_limitBalance" class="as_customers_limitBalance"><?php echo $as_customers->limitBalance->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->balance->Visible) { // balance ?>
		<th><span id="elh_as_customers_balance" class="as_customers_balance"><?php echo $as_customers->balance->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->disc1->Visible) { // disc1 ?>
		<th><span id="elh_as_customers_disc1" class="as_customers_disc1"><?php echo $as_customers->disc1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->disc2->Visible) { // disc2 ?>
		<th><span id="elh_as_customers_disc2" class="as_customers_disc2"><?php echo $as_customers->disc2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->disc3->Visible) { // disc3 ?>
		<th><span id="elh_as_customers_disc3" class="as_customers_disc3"><?php echo $as_customers->disc3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->npwp->Visible) { // npwp ?>
		<th><span id="elh_as_customers_npwp" class="as_customers_npwp"><?php echo $as_customers->npwp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
		<th><span id="elh_as_customers_pkpName" class="as_customers_pkpName"><?php echo $as_customers->pkpName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
		<th><span id="elh_as_customers_staffCode" class="as_customers_staffCode"><?php echo $as_customers->staffCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_customers_createdDate" class="as_customers_createdDate"><?php echo $as_customers->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_customers_createdUserID" class="as_customers_createdUserID"><?php echo $as_customers->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_customers_modifiedDate" class="as_customers_modifiedDate"><?php echo $as_customers->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_customers_modifiedUserID" class="as_customers_modifiedUserID"><?php echo $as_customers->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_customers_delete->RecCnt = 0;
$i = 0;
while (!$as_customers_delete->Recordset->EOF) {
	$as_customers_delete->RecCnt++;
	$as_customers_delete->RowCnt++;

	// Set row properties
	$as_customers->ResetAttrs();
	$as_customers->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_customers_delete->LoadRowValues($as_customers_delete->Recordset);

	// Render row
	$as_customers_delete->RenderRow();
?>
	<tr<?php echo $as_customers->RowAttributes() ?>>
<?php if ($as_customers->customerID->Visible) { // customerID ?>
		<td<?php echo $as_customers->customerID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_customerID" class="as_customers_customerID">
<span<?php echo $as_customers->customerID->ViewAttributes() ?>>
<?php echo $as_customers->customerID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
		<td<?php echo $as_customers->customerCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_customerCode" class="as_customers_customerCode">
<span<?php echo $as_customers->customerCode->ViewAttributes() ?>>
<?php echo $as_customers->customerCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->customerName->Visible) { // customerName ?>
		<td<?php echo $as_customers->customerName->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_customerName" class="as_customers_customerName">
<span<?php echo $as_customers->customerName->ViewAttributes() ?>>
<?php echo $as_customers->customerName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
		<td<?php echo $as_customers->contactPerson->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_contactPerson" class="as_customers_contactPerson">
<span<?php echo $as_customers->contactPerson->ViewAttributes() ?>>
<?php echo $as_customers->contactPerson->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->address->Visible) { // address ?>
		<td<?php echo $as_customers->address->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_address" class="as_customers_address">
<span<?php echo $as_customers->address->ViewAttributes() ?>>
<?php echo $as_customers->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->village->Visible) { // village ?>
		<td<?php echo $as_customers->village->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_village" class="as_customers_village">
<span<?php echo $as_customers->village->ViewAttributes() ?>>
<?php echo $as_customers->village->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->district->Visible) { // district ?>
		<td<?php echo $as_customers->district->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_district" class="as_customers_district">
<span<?php echo $as_customers->district->ViewAttributes() ?>>
<?php echo $as_customers->district->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->city->Visible) { // city ?>
		<td<?php echo $as_customers->city->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_city" class="as_customers_city">
<span<?php echo $as_customers->city->ViewAttributes() ?>>
<?php echo $as_customers->city->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
		<td<?php echo $as_customers->zipCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_zipCode" class="as_customers_zipCode">
<span<?php echo $as_customers->zipCode->ViewAttributes() ?>>
<?php echo $as_customers->zipCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->province->Visible) { // province ?>
		<td<?php echo $as_customers->province->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_province" class="as_customers_province">
<span<?php echo $as_customers->province->ViewAttributes() ?>>
<?php echo $as_customers->province->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->phone1->Visible) { // phone1 ?>
		<td<?php echo $as_customers->phone1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_phone1" class="as_customers_phone1">
<span<?php echo $as_customers->phone1->ViewAttributes() ?>>
<?php echo $as_customers->phone1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->phone2->Visible) { // phone2 ?>
		<td<?php echo $as_customers->phone2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_phone2" class="as_customers_phone2">
<span<?php echo $as_customers->phone2->ViewAttributes() ?>>
<?php echo $as_customers->phone2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->phone3->Visible) { // phone3 ?>
		<td<?php echo $as_customers->phone3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_phone3" class="as_customers_phone3">
<span<?php echo $as_customers->phone3->ViewAttributes() ?>>
<?php echo $as_customers->phone3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->fax1->Visible) { // fax1 ?>
		<td<?php echo $as_customers->fax1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_fax1" class="as_customers_fax1">
<span<?php echo $as_customers->fax1->ViewAttributes() ?>>
<?php echo $as_customers->fax1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->fax2->Visible) { // fax2 ?>
		<td<?php echo $as_customers->fax2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_fax2" class="as_customers_fax2">
<span<?php echo $as_customers->fax2->ViewAttributes() ?>>
<?php echo $as_customers->fax2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->fax3->Visible) { // fax3 ?>
		<td<?php echo $as_customers->fax3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_fax3" class="as_customers_fax3">
<span<?php echo $as_customers->fax3->ViewAttributes() ?>>
<?php echo $as_customers->fax3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
		<td<?php echo $as_customers->phonecp1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_phonecp1" class="as_customers_phonecp1">
<span<?php echo $as_customers->phonecp1->ViewAttributes() ?>>
<?php echo $as_customers->phonecp1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
		<td<?php echo $as_customers->phonecp2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_phonecp2" class="as_customers_phonecp2">
<span<?php echo $as_customers->phonecp2->ViewAttributes() ?>>
<?php echo $as_customers->phonecp2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->_email->Visible) { // email ?>
		<td<?php echo $as_customers->_email->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers__email" class="as_customers__email">
<span<?php echo $as_customers->_email->ViewAttributes() ?>>
<?php echo $as_customers->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
		<td<?php echo $as_customers->limitBalance->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_limitBalance" class="as_customers_limitBalance">
<span<?php echo $as_customers->limitBalance->ViewAttributes() ?>>
<?php echo $as_customers->limitBalance->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->balance->Visible) { // balance ?>
		<td<?php echo $as_customers->balance->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_balance" class="as_customers_balance">
<span<?php echo $as_customers->balance->ViewAttributes() ?>>
<?php echo $as_customers->balance->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->disc1->Visible) { // disc1 ?>
		<td<?php echo $as_customers->disc1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_disc1" class="as_customers_disc1">
<span<?php echo $as_customers->disc1->ViewAttributes() ?>>
<?php echo $as_customers->disc1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->disc2->Visible) { // disc2 ?>
		<td<?php echo $as_customers->disc2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_disc2" class="as_customers_disc2">
<span<?php echo $as_customers->disc2->ViewAttributes() ?>>
<?php echo $as_customers->disc2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->disc3->Visible) { // disc3 ?>
		<td<?php echo $as_customers->disc3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_disc3" class="as_customers_disc3">
<span<?php echo $as_customers->disc3->ViewAttributes() ?>>
<?php echo $as_customers->disc3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->npwp->Visible) { // npwp ?>
		<td<?php echo $as_customers->npwp->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_npwp" class="as_customers_npwp">
<span<?php echo $as_customers->npwp->ViewAttributes() ?>>
<?php echo $as_customers->npwp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
		<td<?php echo $as_customers->pkpName->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_pkpName" class="as_customers_pkpName">
<span<?php echo $as_customers->pkpName->ViewAttributes() ?>>
<?php echo $as_customers->pkpName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
		<td<?php echo $as_customers->staffCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_staffCode" class="as_customers_staffCode">
<span<?php echo $as_customers->staffCode->ViewAttributes() ?>>
<?php echo $as_customers->staffCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_customers->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_createdDate" class="as_customers_createdDate">
<span<?php echo $as_customers->createdDate->ViewAttributes() ?>>
<?php echo $as_customers->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_customers->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_createdUserID" class="as_customers_createdUserID">
<span<?php echo $as_customers->createdUserID->ViewAttributes() ?>>
<?php echo $as_customers->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_customers->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_modifiedDate" class="as_customers_modifiedDate">
<span<?php echo $as_customers->modifiedDate->ViewAttributes() ?>>
<?php echo $as_customers->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_customers->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_delete->RowCnt ?>_as_customers_modifiedUserID" class="as_customers_modifiedUserID">
<span<?php echo $as_customers->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_customers->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_customers_delete->Recordset->MoveNext();
}
$as_customers_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_customers_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_customersdelete.Init();
</script>
<?php
$as_customers_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_customers_delete->Page_Terminate();
?>
