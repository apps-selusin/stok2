<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_staffsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_staffs_delete = NULL; // Initialize page object first

class cas_staffs_delete extends cas_staffs {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_staffs';

	// Page object name
	var $PageObjName = 'as_staffs_delete';

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

		// Table object (as_staffs)
		if (!isset($GLOBALS["as_staffs"]) || get_class($GLOBALS["as_staffs"]) == "cas_staffs") {
			$GLOBALS["as_staffs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_staffs"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_staffs', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_staffslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->staffID->SetVisibility();
		$this->staffID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->staffCode->SetVisibility();
		$this->staffName->SetVisibility();
		$this->village->SetVisibility();
		$this->district->SetVisibility();
		$this->city->SetVisibility();
		$this->zipCode->SetVisibility();
		$this->province->SetVisibility();
		$this->phone->SetVisibility();
		$this->position->SetVisibility();
		$this->part->SetVisibility();
		$this->status->SetVisibility();
		$this->level->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->lastLogin->SetVisibility();
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
		global $EW_EXPORT, $as_staffs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_staffs);
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
			$this->Page_Terminate("as_staffslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_staffs class, as_staffsinfo.php

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
				$this->Page_Terminate("as_staffslist.php"); // Return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->staffID->DbValue = $row['staffID'];
		$this->staffCode->DbValue = $row['staffCode'];
		$this->staffName->DbValue = $row['staffName'];
		$this->address->DbValue = $row['address'];
		$this->address2->DbValue = $row['address2'];
		$this->village->DbValue = $row['village'];
		$this->district->DbValue = $row['district'];
		$this->city->DbValue = $row['city'];
		$this->zipCode->DbValue = $row['zipCode'];
		$this->province->DbValue = $row['province'];
		$this->phone->DbValue = $row['phone'];
		$this->position->DbValue = $row['position'];
		$this->part->DbValue = $row['part'];
		$this->status->DbValue = $row['status'];
		$this->level->DbValue = $row['level'];
		$this->photo->DbValue = $row['photo'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->lastLogin->DbValue = $row['lastLogin'];
		$this->createdDate->DbValue = $row['createdDate'];
		$this->createdUserID->DbValue = $row['createdUserID'];
		$this->modifiedDate->DbValue = $row['modifiedDate'];
		$this->modifiedUserID->DbValue = $row['modifiedUserID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->ViewValue = $this->staffCode->CurrentValue;
		$this->staffCode->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

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
				$sThisKey .= $row['staffID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_staffslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_staffs_delete)) $as_staffs_delete = new cas_staffs_delete();

// Page init
$as_staffs_delete->Page_Init();

// Page main
$as_staffs_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_staffs_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_staffsdelete = new ew_Form("fas_staffsdelete", "delete");

// Form_CustomValidate event
fas_staffsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_staffsdelete.ValidateRequired = true;
<?php } else { ?>
fas_staffsdelete.ValidateRequired = false; 
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
<?php $as_staffs_delete->ShowPageHeader(); ?>
<?php
$as_staffs_delete->ShowMessage();
?>
<form name="fas_staffsdelete" id="fas_staffsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_staffs_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_staffs_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_staffs">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_staffs_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_staffs->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_staffs->staffID->Visible) { // staffID ?>
		<th><span id="elh_as_staffs_staffID" class="as_staffs_staffID"><?php echo $as_staffs->staffID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->staffCode->Visible) { // staffCode ?>
		<th><span id="elh_as_staffs_staffCode" class="as_staffs_staffCode"><?php echo $as_staffs->staffCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->staffName->Visible) { // staffName ?>
		<th><span id="elh_as_staffs_staffName" class="as_staffs_staffName"><?php echo $as_staffs->staffName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->village->Visible) { // village ?>
		<th><span id="elh_as_staffs_village" class="as_staffs_village"><?php echo $as_staffs->village->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->district->Visible) { // district ?>
		<th><span id="elh_as_staffs_district" class="as_staffs_district"><?php echo $as_staffs->district->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->city->Visible) { // city ?>
		<th><span id="elh_as_staffs_city" class="as_staffs_city"><?php echo $as_staffs->city->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->zipCode->Visible) { // zipCode ?>
		<th><span id="elh_as_staffs_zipCode" class="as_staffs_zipCode"><?php echo $as_staffs->zipCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->province->Visible) { // province ?>
		<th><span id="elh_as_staffs_province" class="as_staffs_province"><?php echo $as_staffs->province->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->phone->Visible) { // phone ?>
		<th><span id="elh_as_staffs_phone" class="as_staffs_phone"><?php echo $as_staffs->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->position->Visible) { // position ?>
		<th><span id="elh_as_staffs_position" class="as_staffs_position"><?php echo $as_staffs->position->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->part->Visible) { // part ?>
		<th><span id="elh_as_staffs_part" class="as_staffs_part"><?php echo $as_staffs->part->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->status->Visible) { // status ?>
		<th><span id="elh_as_staffs_status" class="as_staffs_status"><?php echo $as_staffs->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->level->Visible) { // level ?>
		<th><span id="elh_as_staffs_level" class="as_staffs_level"><?php echo $as_staffs->level->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->_email->Visible) { // email ?>
		<th><span id="elh_as_staffs__email" class="as_staffs__email"><?php echo $as_staffs->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->password->Visible) { // password ?>
		<th><span id="elh_as_staffs_password" class="as_staffs_password"><?php echo $as_staffs->password->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->lastLogin->Visible) { // lastLogin ?>
		<th><span id="elh_as_staffs_lastLogin" class="as_staffs_lastLogin"><?php echo $as_staffs->lastLogin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_staffs_createdDate" class="as_staffs_createdDate"><?php echo $as_staffs->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_staffs_createdUserID" class="as_staffs_createdUserID"><?php echo $as_staffs->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_staffs_modifiedDate" class="as_staffs_modifiedDate"><?php echo $as_staffs->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_staffs->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_staffs_modifiedUserID" class="as_staffs_modifiedUserID"><?php echo $as_staffs->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_staffs_delete->RecCnt = 0;
$i = 0;
while (!$as_staffs_delete->Recordset->EOF) {
	$as_staffs_delete->RecCnt++;
	$as_staffs_delete->RowCnt++;

	// Set row properties
	$as_staffs->ResetAttrs();
	$as_staffs->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_staffs_delete->LoadRowValues($as_staffs_delete->Recordset);

	// Render row
	$as_staffs_delete->RenderRow();
?>
	<tr<?php echo $as_staffs->RowAttributes() ?>>
<?php if ($as_staffs->staffID->Visible) { // staffID ?>
		<td<?php echo $as_staffs->staffID->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_staffID" class="as_staffs_staffID">
<span<?php echo $as_staffs->staffID->ViewAttributes() ?>>
<?php echo $as_staffs->staffID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->staffCode->Visible) { // staffCode ?>
		<td<?php echo $as_staffs->staffCode->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_staffCode" class="as_staffs_staffCode">
<span<?php echo $as_staffs->staffCode->ViewAttributes() ?>>
<?php echo $as_staffs->staffCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->staffName->Visible) { // staffName ?>
		<td<?php echo $as_staffs->staffName->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_staffName" class="as_staffs_staffName">
<span<?php echo $as_staffs->staffName->ViewAttributes() ?>>
<?php echo $as_staffs->staffName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->village->Visible) { // village ?>
		<td<?php echo $as_staffs->village->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_village" class="as_staffs_village">
<span<?php echo $as_staffs->village->ViewAttributes() ?>>
<?php echo $as_staffs->village->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->district->Visible) { // district ?>
		<td<?php echo $as_staffs->district->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_district" class="as_staffs_district">
<span<?php echo $as_staffs->district->ViewAttributes() ?>>
<?php echo $as_staffs->district->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->city->Visible) { // city ?>
		<td<?php echo $as_staffs->city->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_city" class="as_staffs_city">
<span<?php echo $as_staffs->city->ViewAttributes() ?>>
<?php echo $as_staffs->city->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->zipCode->Visible) { // zipCode ?>
		<td<?php echo $as_staffs->zipCode->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_zipCode" class="as_staffs_zipCode">
<span<?php echo $as_staffs->zipCode->ViewAttributes() ?>>
<?php echo $as_staffs->zipCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->province->Visible) { // province ?>
		<td<?php echo $as_staffs->province->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_province" class="as_staffs_province">
<span<?php echo $as_staffs->province->ViewAttributes() ?>>
<?php echo $as_staffs->province->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->phone->Visible) { // phone ?>
		<td<?php echo $as_staffs->phone->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_phone" class="as_staffs_phone">
<span<?php echo $as_staffs->phone->ViewAttributes() ?>>
<?php echo $as_staffs->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->position->Visible) { // position ?>
		<td<?php echo $as_staffs->position->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_position" class="as_staffs_position">
<span<?php echo $as_staffs->position->ViewAttributes() ?>>
<?php echo $as_staffs->position->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->part->Visible) { // part ?>
		<td<?php echo $as_staffs->part->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_part" class="as_staffs_part">
<span<?php echo $as_staffs->part->ViewAttributes() ?>>
<?php echo $as_staffs->part->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->status->Visible) { // status ?>
		<td<?php echo $as_staffs->status->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_status" class="as_staffs_status">
<span<?php echo $as_staffs->status->ViewAttributes() ?>>
<?php echo $as_staffs->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->level->Visible) { // level ?>
		<td<?php echo $as_staffs->level->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_level" class="as_staffs_level">
<span<?php echo $as_staffs->level->ViewAttributes() ?>>
<?php echo $as_staffs->level->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->_email->Visible) { // email ?>
		<td<?php echo $as_staffs->_email->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs__email" class="as_staffs__email">
<span<?php echo $as_staffs->_email->ViewAttributes() ?>>
<?php echo $as_staffs->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->password->Visible) { // password ?>
		<td<?php echo $as_staffs->password->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_password" class="as_staffs_password">
<span<?php echo $as_staffs->password->ViewAttributes() ?>>
<?php echo $as_staffs->password->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->lastLogin->Visible) { // lastLogin ?>
		<td<?php echo $as_staffs->lastLogin->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_lastLogin" class="as_staffs_lastLogin">
<span<?php echo $as_staffs->lastLogin->ViewAttributes() ?>>
<?php echo $as_staffs->lastLogin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_staffs->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_createdDate" class="as_staffs_createdDate">
<span<?php echo $as_staffs->createdDate->ViewAttributes() ?>>
<?php echo $as_staffs->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_staffs->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_createdUserID" class="as_staffs_createdUserID">
<span<?php echo $as_staffs->createdUserID->ViewAttributes() ?>>
<?php echo $as_staffs->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_staffs->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_modifiedDate" class="as_staffs_modifiedDate">
<span<?php echo $as_staffs->modifiedDate->ViewAttributes() ?>>
<?php echo $as_staffs->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_staffs->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_staffs->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_staffs_delete->RowCnt ?>_as_staffs_modifiedUserID" class="as_staffs_modifiedUserID">
<span<?php echo $as_staffs->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_staffs->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_staffs_delete->Recordset->MoveNext();
}
$as_staffs_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_staffs_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_staffsdelete.Init();
</script>
<?php
$as_staffs_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_staffs_delete->Page_Terminate();
?>
