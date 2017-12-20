<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_debtsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_debts_delete = NULL; // Initialize page object first

class cas_debts_delete extends cas_debts {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_debts';

	// Page object name
	var $PageObjName = 'as_debts_delete';

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

		// Table object (as_debts)
		if (!isset($GLOBALS["as_debts"]) || get_class($GLOBALS["as_debts"]) == "cas_debts") {
			$GLOBALS["as_debts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_debts"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_debts', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_debtslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->debtID->SetVisibility();
		$this->debtID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->debtNo->SetVisibility();
		$this->invoiceID->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
		$this->debtTotal->SetVisibility();
		$this->incomingTotal->SetVisibility();
		$this->reductionTotal->SetVisibility();
		$this->status->SetVisibility();
		$this->staffID->SetVisibility();
		$this->staffName->SetVisibility();
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
		global $EW_EXPORT, $as_debts;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_debts);
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
			$this->Page_Terminate("as_debtslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_debts class, as_debtsinfo.php

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
				$this->Page_Terminate("as_debtslist.php"); // Return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->debtID->DbValue = $row['debtID'];
		$this->debtNo->DbValue = $row['debtNo'];
		$this->invoiceID->DbValue = $row['invoiceID'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->supplierID->DbValue = $row['supplierID'];
		$this->supplierName->DbValue = $row['supplierName'];
		$this->supplierAddress->DbValue = $row['supplierAddress'];
		$this->debtTotal->DbValue = $row['debtTotal'];
		$this->incomingTotal->DbValue = $row['incomingTotal'];
		$this->reductionTotal->DbValue = $row['reductionTotal'];
		$this->status->DbValue = $row['status'];
		$this->staffID->DbValue = $row['staffID'];
		$this->staffName->DbValue = $row['staffName'];
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

		if ($this->debtTotal->FormValue == $this->debtTotal->CurrentValue && is_numeric(ew_StrToFloat($this->debtTotal->CurrentValue)))
			$this->debtTotal->CurrentValue = ew_StrToFloat($this->debtTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->incomingTotal->FormValue == $this->incomingTotal->CurrentValue && is_numeric(ew_StrToFloat($this->incomingTotal->CurrentValue)))
			$this->incomingTotal->CurrentValue = ew_StrToFloat($this->incomingTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->reductionTotal->FormValue == $this->reductionTotal->CurrentValue && is_numeric(ew_StrToFloat($this->reductionTotal->CurrentValue)))
			$this->reductionTotal->CurrentValue = ew_StrToFloat($this->reductionTotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
				$sThisKey .= $row['debtID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_debtslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_debts_delete)) $as_debts_delete = new cas_debts_delete();

// Page init
$as_debts_delete->Page_Init();

// Page main
$as_debts_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_debts_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_debtsdelete = new ew_Form("fas_debtsdelete", "delete");

// Form_CustomValidate event
fas_debtsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_debtsdelete.ValidateRequired = true;
<?php } else { ?>
fas_debtsdelete.ValidateRequired = false; 
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
<?php $as_debts_delete->ShowPageHeader(); ?>
<?php
$as_debts_delete->ShowMessage();
?>
<form name="fas_debtsdelete" id="fas_debtsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_debts_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_debts_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_debts">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_debts_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_debts->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_debts->debtID->Visible) { // debtID ?>
		<th><span id="elh_as_debts_debtID" class="as_debts_debtID"><?php echo $as_debts->debtID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->debtNo->Visible) { // debtNo ?>
		<th><span id="elh_as_debts_debtNo" class="as_debts_debtNo"><?php echo $as_debts->debtNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->invoiceID->Visible) { // invoiceID ?>
		<th><span id="elh_as_debts_invoiceID" class="as_debts_invoiceID"><?php echo $as_debts->invoiceID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->invoiceNo->Visible) { // invoiceNo ?>
		<th><span id="elh_as_debts_invoiceNo" class="as_debts_invoiceNo"><?php echo $as_debts->invoiceNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->supplierID->Visible) { // supplierID ?>
		<th><span id="elh_as_debts_supplierID" class="as_debts_supplierID"><?php echo $as_debts->supplierID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->supplierName->Visible) { // supplierName ?>
		<th><span id="elh_as_debts_supplierName" class="as_debts_supplierName"><?php echo $as_debts->supplierName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->debtTotal->Visible) { // debtTotal ?>
		<th><span id="elh_as_debts_debtTotal" class="as_debts_debtTotal"><?php echo $as_debts->debtTotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->incomingTotal->Visible) { // incomingTotal ?>
		<th><span id="elh_as_debts_incomingTotal" class="as_debts_incomingTotal"><?php echo $as_debts->incomingTotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->reductionTotal->Visible) { // reductionTotal ?>
		<th><span id="elh_as_debts_reductionTotal" class="as_debts_reductionTotal"><?php echo $as_debts->reductionTotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->status->Visible) { // status ?>
		<th><span id="elh_as_debts_status" class="as_debts_status"><?php echo $as_debts->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->staffID->Visible) { // staffID ?>
		<th><span id="elh_as_debts_staffID" class="as_debts_staffID"><?php echo $as_debts->staffID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->staffName->Visible) { // staffName ?>
		<th><span id="elh_as_debts_staffName" class="as_debts_staffName"><?php echo $as_debts->staffName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_debts_createdDate" class="as_debts_createdDate"><?php echo $as_debts->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_debts_createdUserID" class="as_debts_createdUserID"><?php echo $as_debts->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_debts_modifiedDate" class="as_debts_modifiedDate"><?php echo $as_debts->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_debts->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_debts_modifiedUserID" class="as_debts_modifiedUserID"><?php echo $as_debts->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_debts_delete->RecCnt = 0;
$i = 0;
while (!$as_debts_delete->Recordset->EOF) {
	$as_debts_delete->RecCnt++;
	$as_debts_delete->RowCnt++;

	// Set row properties
	$as_debts->ResetAttrs();
	$as_debts->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_debts_delete->LoadRowValues($as_debts_delete->Recordset);

	// Render row
	$as_debts_delete->RenderRow();
?>
	<tr<?php echo $as_debts->RowAttributes() ?>>
<?php if ($as_debts->debtID->Visible) { // debtID ?>
		<td<?php echo $as_debts->debtID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_debtID" class="as_debts_debtID">
<span<?php echo $as_debts->debtID->ViewAttributes() ?>>
<?php echo $as_debts->debtID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->debtNo->Visible) { // debtNo ?>
		<td<?php echo $as_debts->debtNo->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_debtNo" class="as_debts_debtNo">
<span<?php echo $as_debts->debtNo->ViewAttributes() ?>>
<?php echo $as_debts->debtNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->invoiceID->Visible) { // invoiceID ?>
		<td<?php echo $as_debts->invoiceID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_invoiceID" class="as_debts_invoiceID">
<span<?php echo $as_debts->invoiceID->ViewAttributes() ?>>
<?php echo $as_debts->invoiceID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->invoiceNo->Visible) { // invoiceNo ?>
		<td<?php echo $as_debts->invoiceNo->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_invoiceNo" class="as_debts_invoiceNo">
<span<?php echo $as_debts->invoiceNo->ViewAttributes() ?>>
<?php echo $as_debts->invoiceNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->supplierID->Visible) { // supplierID ?>
		<td<?php echo $as_debts->supplierID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_supplierID" class="as_debts_supplierID">
<span<?php echo $as_debts->supplierID->ViewAttributes() ?>>
<?php echo $as_debts->supplierID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->supplierName->Visible) { // supplierName ?>
		<td<?php echo $as_debts->supplierName->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_supplierName" class="as_debts_supplierName">
<span<?php echo $as_debts->supplierName->ViewAttributes() ?>>
<?php echo $as_debts->supplierName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->debtTotal->Visible) { // debtTotal ?>
		<td<?php echo $as_debts->debtTotal->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_debtTotal" class="as_debts_debtTotal">
<span<?php echo $as_debts->debtTotal->ViewAttributes() ?>>
<?php echo $as_debts->debtTotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->incomingTotal->Visible) { // incomingTotal ?>
		<td<?php echo $as_debts->incomingTotal->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_incomingTotal" class="as_debts_incomingTotal">
<span<?php echo $as_debts->incomingTotal->ViewAttributes() ?>>
<?php echo $as_debts->incomingTotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->reductionTotal->Visible) { // reductionTotal ?>
		<td<?php echo $as_debts->reductionTotal->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_reductionTotal" class="as_debts_reductionTotal">
<span<?php echo $as_debts->reductionTotal->ViewAttributes() ?>>
<?php echo $as_debts->reductionTotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->status->Visible) { // status ?>
		<td<?php echo $as_debts->status->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_status" class="as_debts_status">
<span<?php echo $as_debts->status->ViewAttributes() ?>>
<?php echo $as_debts->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->staffID->Visible) { // staffID ?>
		<td<?php echo $as_debts->staffID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_staffID" class="as_debts_staffID">
<span<?php echo $as_debts->staffID->ViewAttributes() ?>>
<?php echo $as_debts->staffID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->staffName->Visible) { // staffName ?>
		<td<?php echo $as_debts->staffName->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_staffName" class="as_debts_staffName">
<span<?php echo $as_debts->staffName->ViewAttributes() ?>>
<?php echo $as_debts->staffName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_debts->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_createdDate" class="as_debts_createdDate">
<span<?php echo $as_debts->createdDate->ViewAttributes() ?>>
<?php echo $as_debts->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_debts->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_createdUserID" class="as_debts_createdUserID">
<span<?php echo $as_debts->createdUserID->ViewAttributes() ?>>
<?php echo $as_debts->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_debts->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_modifiedDate" class="as_debts_modifiedDate">
<span<?php echo $as_debts->modifiedDate->ViewAttributes() ?>>
<?php echo $as_debts->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_debts->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_debts->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_debts_delete->RowCnt ?>_as_debts_modifiedUserID" class="as_debts_modifiedUserID">
<span<?php echo $as_debts->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_debts->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_debts_delete->Recordset->MoveNext();
}
$as_debts_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_debts_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_debtsdelete.Init();
</script>
<?php
$as_debts_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_debts_delete->Page_Terminate();
?>
