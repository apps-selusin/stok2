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

$as_debts_add = NULL; // Initialize page object first

class cas_debts_add extends cas_debts {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_debts';

	// Page object name
	var $PageObjName = 'as_debts_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->debtNo->SetVisibility();
		$this->invoiceID->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
		$this->supplierAddress->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["debtID"] != "") {
				$this->debtID->setQueryStringValue($_GET["debtID"]);
				$this->setKey("debtID", $this->debtID->CurrentValue); // Set up key
			} else {
				$this->setKey("debtID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("as_debtslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_debtslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_debtsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->debtNo->CurrentValue = NULL;
		$this->debtNo->OldValue = $this->debtNo->CurrentValue;
		$this->invoiceID->CurrentValue = NULL;
		$this->invoiceID->OldValue = $this->invoiceID->CurrentValue;
		$this->invoiceNo->CurrentValue = NULL;
		$this->invoiceNo->OldValue = $this->invoiceNo->CurrentValue;
		$this->supplierID->CurrentValue = NULL;
		$this->supplierID->OldValue = $this->supplierID->CurrentValue;
		$this->supplierName->CurrentValue = NULL;
		$this->supplierName->OldValue = $this->supplierName->CurrentValue;
		$this->supplierAddress->CurrentValue = NULL;
		$this->supplierAddress->OldValue = $this->supplierAddress->CurrentValue;
		$this->debtTotal->CurrentValue = NULL;
		$this->debtTotal->OldValue = $this->debtTotal->CurrentValue;
		$this->incomingTotal->CurrentValue = NULL;
		$this->incomingTotal->OldValue = $this->incomingTotal->CurrentValue;
		$this->reductionTotal->CurrentValue = NULL;
		$this->reductionTotal->OldValue = $this->reductionTotal->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->staffID->CurrentValue = NULL;
		$this->staffID->OldValue = $this->staffID->CurrentValue;
		$this->staffName->CurrentValue = NULL;
		$this->staffName->OldValue = $this->staffName->CurrentValue;
		$this->createdDate->CurrentValue = NULL;
		$this->createdDate->OldValue = $this->createdDate->CurrentValue;
		$this->createdUserID->CurrentValue = NULL;
		$this->createdUserID->OldValue = $this->createdUserID->CurrentValue;
		$this->modifiedDate->CurrentValue = NULL;
		$this->modifiedDate->OldValue = $this->modifiedDate->CurrentValue;
		$this->modifiedUserID->CurrentValue = NULL;
		$this->modifiedUserID->OldValue = $this->modifiedUserID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->debtNo->FldIsDetailKey) {
			$this->debtNo->setFormValue($objForm->GetValue("x_debtNo"));
		}
		if (!$this->invoiceID->FldIsDetailKey) {
			$this->invoiceID->setFormValue($objForm->GetValue("x_invoiceID"));
		}
		if (!$this->invoiceNo->FldIsDetailKey) {
			$this->invoiceNo->setFormValue($objForm->GetValue("x_invoiceNo"));
		}
		if (!$this->supplierID->FldIsDetailKey) {
			$this->supplierID->setFormValue($objForm->GetValue("x_supplierID"));
		}
		if (!$this->supplierName->FldIsDetailKey) {
			$this->supplierName->setFormValue($objForm->GetValue("x_supplierName"));
		}
		if (!$this->supplierAddress->FldIsDetailKey) {
			$this->supplierAddress->setFormValue($objForm->GetValue("x_supplierAddress"));
		}
		if (!$this->debtTotal->FldIsDetailKey) {
			$this->debtTotal->setFormValue($objForm->GetValue("x_debtTotal"));
		}
		if (!$this->incomingTotal->FldIsDetailKey) {
			$this->incomingTotal->setFormValue($objForm->GetValue("x_incomingTotal"));
		}
		if (!$this->reductionTotal->FldIsDetailKey) {
			$this->reductionTotal->setFormValue($objForm->GetValue("x_reductionTotal"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->staffID->FldIsDetailKey) {
			$this->staffID->setFormValue($objForm->GetValue("x_staffID"));
		}
		if (!$this->staffName->FldIsDetailKey) {
			$this->staffName->setFormValue($objForm->GetValue("x_staffName"));
		}
		if (!$this->createdDate->FldIsDetailKey) {
			$this->createdDate->setFormValue($objForm->GetValue("x_createdDate"));
			$this->createdDate->CurrentValue = ew_UnFormatDateTime($this->createdDate->CurrentValue, 0);
		}
		if (!$this->createdUserID->FldIsDetailKey) {
			$this->createdUserID->setFormValue($objForm->GetValue("x_createdUserID"));
		}
		if (!$this->modifiedDate->FldIsDetailKey) {
			$this->modifiedDate->setFormValue($objForm->GetValue("x_modifiedDate"));
			$this->modifiedDate->CurrentValue = ew_UnFormatDateTime($this->modifiedDate->CurrentValue, 0);
		}
		if (!$this->modifiedUserID->FldIsDetailKey) {
			$this->modifiedUserID->setFormValue($objForm->GetValue("x_modifiedUserID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->debtNo->CurrentValue = $this->debtNo->FormValue;
		$this->invoiceID->CurrentValue = $this->invoiceID->FormValue;
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->supplierID->CurrentValue = $this->supplierID->FormValue;
		$this->supplierName->CurrentValue = $this->supplierName->FormValue;
		$this->supplierAddress->CurrentValue = $this->supplierAddress->FormValue;
		$this->debtTotal->CurrentValue = $this->debtTotal->FormValue;
		$this->incomingTotal->CurrentValue = $this->incomingTotal->FormValue;
		$this->reductionTotal->CurrentValue = $this->reductionTotal->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->createdDate->CurrentValue = $this->createdDate->FormValue;
		$this->createdDate->CurrentValue = ew_UnFormatDateTime($this->createdDate->CurrentValue, 0);
		$this->createdUserID->CurrentValue = $this->createdUserID->FormValue;
		$this->modifiedDate->CurrentValue = $this->modifiedDate->FormValue;
		$this->modifiedDate->CurrentValue = ew_UnFormatDateTime($this->modifiedDate->CurrentValue, 0);
		$this->modifiedUserID->CurrentValue = $this->modifiedUserID->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("debtID")) <> "")
			$this->debtID->CurrentValue = $this->getKey("debtID"); // debtID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// debtNo
			$this->debtNo->EditAttrs["class"] = "form-control";
			$this->debtNo->EditCustomAttributes = "";
			$this->debtNo->EditValue = ew_HtmlEncode($this->debtNo->CurrentValue);
			$this->debtNo->PlaceHolder = ew_RemoveHtml($this->debtNo->FldCaption());

			// invoiceID
			$this->invoiceID->EditAttrs["class"] = "form-control";
			$this->invoiceID->EditCustomAttributes = "";
			$this->invoiceID->EditValue = ew_HtmlEncode($this->invoiceID->CurrentValue);
			$this->invoiceID->PlaceHolder = ew_RemoveHtml($this->invoiceID->FldCaption());

			// invoiceNo
			$this->invoiceNo->EditAttrs["class"] = "form-control";
			$this->invoiceNo->EditCustomAttributes = "";
			$this->invoiceNo->EditValue = ew_HtmlEncode($this->invoiceNo->CurrentValue);
			$this->invoiceNo->PlaceHolder = ew_RemoveHtml($this->invoiceNo->FldCaption());

			// supplierID
			$this->supplierID->EditAttrs["class"] = "form-control";
			$this->supplierID->EditCustomAttributes = "";
			$this->supplierID->EditValue = ew_HtmlEncode($this->supplierID->CurrentValue);
			$this->supplierID->PlaceHolder = ew_RemoveHtml($this->supplierID->FldCaption());

			// supplierName
			$this->supplierName->EditAttrs["class"] = "form-control";
			$this->supplierName->EditCustomAttributes = "";
			$this->supplierName->EditValue = ew_HtmlEncode($this->supplierName->CurrentValue);
			$this->supplierName->PlaceHolder = ew_RemoveHtml($this->supplierName->FldCaption());

			// supplierAddress
			$this->supplierAddress->EditAttrs["class"] = "form-control";
			$this->supplierAddress->EditCustomAttributes = "";
			$this->supplierAddress->EditValue = ew_HtmlEncode($this->supplierAddress->CurrentValue);
			$this->supplierAddress->PlaceHolder = ew_RemoveHtml($this->supplierAddress->FldCaption());

			// debtTotal
			$this->debtTotal->EditAttrs["class"] = "form-control";
			$this->debtTotal->EditCustomAttributes = "";
			$this->debtTotal->EditValue = ew_HtmlEncode($this->debtTotal->CurrentValue);
			$this->debtTotal->PlaceHolder = ew_RemoveHtml($this->debtTotal->FldCaption());
			if (strval($this->debtTotal->EditValue) <> "" && is_numeric($this->debtTotal->EditValue)) $this->debtTotal->EditValue = ew_FormatNumber($this->debtTotal->EditValue, -2, -1, -2, 0);

			// incomingTotal
			$this->incomingTotal->EditAttrs["class"] = "form-control";
			$this->incomingTotal->EditCustomAttributes = "";
			$this->incomingTotal->EditValue = ew_HtmlEncode($this->incomingTotal->CurrentValue);
			$this->incomingTotal->PlaceHolder = ew_RemoveHtml($this->incomingTotal->FldCaption());
			if (strval($this->incomingTotal->EditValue) <> "" && is_numeric($this->incomingTotal->EditValue)) $this->incomingTotal->EditValue = ew_FormatNumber($this->incomingTotal->EditValue, -2, -1, -2, 0);

			// reductionTotal
			$this->reductionTotal->EditAttrs["class"] = "form-control";
			$this->reductionTotal->EditCustomAttributes = "";
			$this->reductionTotal->EditValue = ew_HtmlEncode($this->reductionTotal->CurrentValue);
			$this->reductionTotal->PlaceHolder = ew_RemoveHtml($this->reductionTotal->FldCaption());
			if (strval($this->reductionTotal->EditValue) <> "" && is_numeric($this->reductionTotal->EditValue)) $this->reductionTotal->EditValue = ew_FormatNumber($this->reductionTotal->EditValue, -2, -1, -2, 0);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// staffID
			$this->staffID->EditAttrs["class"] = "form-control";
			$this->staffID->EditCustomAttributes = "";
			$this->staffID->EditValue = ew_HtmlEncode($this->staffID->CurrentValue);
			$this->staffID->PlaceHolder = ew_RemoveHtml($this->staffID->FldCaption());

			// staffName
			$this->staffName->EditAttrs["class"] = "form-control";
			$this->staffName->EditCustomAttributes = "";
			$this->staffName->EditValue = ew_HtmlEncode($this->staffName->CurrentValue);
			$this->staffName->PlaceHolder = ew_RemoveHtml($this->staffName->FldCaption());

			// createdDate
			$this->createdDate->EditAttrs["class"] = "form-control";
			$this->createdDate->EditCustomAttributes = "";
			$this->createdDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->createdDate->CurrentValue, 8));
			$this->createdDate->PlaceHolder = ew_RemoveHtml($this->createdDate->FldCaption());

			// createdUserID
			$this->createdUserID->EditAttrs["class"] = "form-control";
			$this->createdUserID->EditCustomAttributes = "";
			$this->createdUserID->EditValue = ew_HtmlEncode($this->createdUserID->CurrentValue);
			$this->createdUserID->PlaceHolder = ew_RemoveHtml($this->createdUserID->FldCaption());

			// modifiedDate
			$this->modifiedDate->EditAttrs["class"] = "form-control";
			$this->modifiedDate->EditCustomAttributes = "";
			$this->modifiedDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->modifiedDate->CurrentValue, 8));
			$this->modifiedDate->PlaceHolder = ew_RemoveHtml($this->modifiedDate->FldCaption());

			// modifiedUserID
			$this->modifiedUserID->EditAttrs["class"] = "form-control";
			$this->modifiedUserID->EditCustomAttributes = "";
			$this->modifiedUserID->EditValue = ew_HtmlEncode($this->modifiedUserID->CurrentValue);
			$this->modifiedUserID->PlaceHolder = ew_RemoveHtml($this->modifiedUserID->FldCaption());

			// Add refer script
			// debtNo

			$this->debtNo->LinkCustomAttributes = "";
			$this->debtNo->HrefValue = "";

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";

			// supplierID
			$this->supplierID->LinkCustomAttributes = "";
			$this->supplierID->HrefValue = "";

			// supplierName
			$this->supplierName->LinkCustomAttributes = "";
			$this->supplierName->HrefValue = "";

			// supplierAddress
			$this->supplierAddress->LinkCustomAttributes = "";
			$this->supplierAddress->HrefValue = "";

			// debtTotal
			$this->debtTotal->LinkCustomAttributes = "";
			$this->debtTotal->HrefValue = "";

			// incomingTotal
			$this->incomingTotal->LinkCustomAttributes = "";
			$this->incomingTotal->HrefValue = "";

			// reductionTotal
			$this->reductionTotal->LinkCustomAttributes = "";
			$this->reductionTotal->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// staffID
			$this->staffID->LinkCustomAttributes = "";
			$this->staffID->HrefValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";

			// createdDate
			$this->createdDate->LinkCustomAttributes = "";
			$this->createdDate->HrefValue = "";

			// createdUserID
			$this->createdUserID->LinkCustomAttributes = "";
			$this->createdUserID->HrefValue = "";

			// modifiedDate
			$this->modifiedDate->LinkCustomAttributes = "";
			$this->modifiedDate->HrefValue = "";

			// modifiedUserID
			$this->modifiedUserID->LinkCustomAttributes = "";
			$this->modifiedUserID->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->debtNo->FldIsDetailKey && !is_null($this->debtNo->FormValue) && $this->debtNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debtNo->FldCaption(), $this->debtNo->ReqErrMsg));
		}
		if (!$this->invoiceID->FldIsDetailKey && !is_null($this->invoiceID->FormValue) && $this->invoiceID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoiceID->FldCaption(), $this->invoiceID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->invoiceID->FormValue)) {
			ew_AddMessage($gsFormError, $this->invoiceID->FldErrMsg());
		}
		if (!$this->invoiceNo->FldIsDetailKey && !is_null($this->invoiceNo->FormValue) && $this->invoiceNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoiceNo->FldCaption(), $this->invoiceNo->ReqErrMsg));
		}
		if (!$this->supplierID->FldIsDetailKey && !is_null($this->supplierID->FormValue) && $this->supplierID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->supplierID->FldCaption(), $this->supplierID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->supplierID->FormValue)) {
			ew_AddMessage($gsFormError, $this->supplierID->FldErrMsg());
		}
		if (!$this->supplierName->FldIsDetailKey && !is_null($this->supplierName->FormValue) && $this->supplierName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->supplierName->FldCaption(), $this->supplierName->ReqErrMsg));
		}
		if (!$this->supplierAddress->FldIsDetailKey && !is_null($this->supplierAddress->FormValue) && $this->supplierAddress->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->supplierAddress->FldCaption(), $this->supplierAddress->ReqErrMsg));
		}
		if (!$this->debtTotal->FldIsDetailKey && !is_null($this->debtTotal->FormValue) && $this->debtTotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debtTotal->FldCaption(), $this->debtTotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debtTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->debtTotal->FldErrMsg());
		}
		if (!$this->incomingTotal->FldIsDetailKey && !is_null($this->incomingTotal->FormValue) && $this->incomingTotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->incomingTotal->FldCaption(), $this->incomingTotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->incomingTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->incomingTotal->FldErrMsg());
		}
		if (!$this->reductionTotal->FldIsDetailKey && !is_null($this->reductionTotal->FormValue) && $this->reductionTotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reductionTotal->FldCaption(), $this->reductionTotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->reductionTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->reductionTotal->FldErrMsg());
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->staffID->FldIsDetailKey && !is_null($this->staffID->FormValue) && $this->staffID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffID->FldCaption(), $this->staffID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staffID->FormValue)) {
			ew_AddMessage($gsFormError, $this->staffID->FldErrMsg());
		}
		if (!$this->staffName->FldIsDetailKey && !is_null($this->staffName->FormValue) && $this->staffName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffName->FldCaption(), $this->staffName->ReqErrMsg));
		}
		if (!$this->createdDate->FldIsDetailKey && !is_null($this->createdDate->FormValue) && $this->createdDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->createdDate->FldCaption(), $this->createdDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->createdDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->createdDate->FldErrMsg());
		}
		if (!$this->createdUserID->FldIsDetailKey && !is_null($this->createdUserID->FormValue) && $this->createdUserID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->createdUserID->FldCaption(), $this->createdUserID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->createdUserID->FormValue)) {
			ew_AddMessage($gsFormError, $this->createdUserID->FldErrMsg());
		}
		if (!$this->modifiedDate->FldIsDetailKey && !is_null($this->modifiedDate->FormValue) && $this->modifiedDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->modifiedDate->FldCaption(), $this->modifiedDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->modifiedDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->modifiedDate->FldErrMsg());
		}
		if (!$this->modifiedUserID->FldIsDetailKey && !is_null($this->modifiedUserID->FormValue) && $this->modifiedUserID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->modifiedUserID->FldCaption(), $this->modifiedUserID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->modifiedUserID->FormValue)) {
			ew_AddMessage($gsFormError, $this->modifiedUserID->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// debtNo
		$this->debtNo->SetDbValueDef($rsnew, $this->debtNo->CurrentValue, "", FALSE);

		// invoiceID
		$this->invoiceID->SetDbValueDef($rsnew, $this->invoiceID->CurrentValue, 0, FALSE);

		// invoiceNo
		$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", FALSE);

		// supplierID
		$this->supplierID->SetDbValueDef($rsnew, $this->supplierID->CurrentValue, 0, FALSE);

		// supplierName
		$this->supplierName->SetDbValueDef($rsnew, $this->supplierName->CurrentValue, "", FALSE);

		// supplierAddress
		$this->supplierAddress->SetDbValueDef($rsnew, $this->supplierAddress->CurrentValue, "", FALSE);

		// debtTotal
		$this->debtTotal->SetDbValueDef($rsnew, $this->debtTotal->CurrentValue, 0, FALSE);

		// incomingTotal
		$this->incomingTotal->SetDbValueDef($rsnew, $this->incomingTotal->CurrentValue, 0, FALSE);

		// reductionTotal
		$this->reductionTotal->SetDbValueDef($rsnew, $this->reductionTotal->CurrentValue, 0, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", FALSE);

		// staffID
		$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// createdDate
		$this->createdDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->createdDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// createdUserID
		$this->createdUserID->SetDbValueDef($rsnew, $this->createdUserID->CurrentValue, 0, FALSE);

		// modifiedDate
		$this->modifiedDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modifiedDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// modifiedUserID
		$this->modifiedUserID->SetDbValueDef($rsnew, $this->modifiedUserID->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_debtslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($as_debts_add)) $as_debts_add = new cas_debts_add();

// Page init
$as_debts_add->Page_Init();

// Page main
$as_debts_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_debts_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_debtsadd = new ew_Form("fas_debtsadd", "add");

// Validate form
fas_debtsadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_debtNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->debtNo->FldCaption(), $as_debts->debtNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->invoiceID->FldCaption(), $as_debts->invoiceID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->invoiceID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_invoiceNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->invoiceNo->FldCaption(), $as_debts->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->supplierID->FldCaption(), $as_debts->supplierID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->supplierID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->supplierName->FldCaption(), $as_debts->supplierName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->supplierAddress->FldCaption(), $as_debts->supplierAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debtTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->debtTotal->FldCaption(), $as_debts->debtTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debtTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->debtTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incomingTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->incomingTotal->FldCaption(), $as_debts->incomingTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incomingTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->incomingTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reductionTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->reductionTotal->FldCaption(), $as_debts->reductionTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reductionTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->reductionTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->status->FldCaption(), $as_debts->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->staffID->FldCaption(), $as_debts->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->staffName->FldCaption(), $as_debts->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->createdDate->FldCaption(), $as_debts->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->createdUserID->FldCaption(), $as_debts->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->modifiedDate->FldCaption(), $as_debts->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_debts->modifiedUserID->FldCaption(), $as_debts->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_debts->modifiedUserID->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fas_debtsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_debtsadd.ValidateRequired = true;
<?php } else { ?>
fas_debtsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_debts_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_debts_add->ShowPageHeader(); ?>
<?php
$as_debts_add->ShowMessage();
?>
<form name="fas_debtsadd" id="fas_debtsadd" class="<?php echo $as_debts_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_debts_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_debts_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_debts">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_debts_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_debts->debtNo->Visible) { // debtNo ?>
	<div id="r_debtNo" class="form-group">
		<label id="elh_as_debts_debtNo" for="x_debtNo" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->debtNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->debtNo->CellAttributes() ?>>
<span id="el_as_debts_debtNo">
<input type="text" data-table="as_debts" data-field="x_debtNo" name="x_debtNo" id="x_debtNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_debts->debtNo->getPlaceHolder()) ?>" value="<?php echo $as_debts->debtNo->EditValue ?>"<?php echo $as_debts->debtNo->EditAttributes() ?>>
</span>
<?php echo $as_debts->debtNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->invoiceID->Visible) { // invoiceID ?>
	<div id="r_invoiceID" class="form-group">
		<label id="elh_as_debts_invoiceID" for="x_invoiceID" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->invoiceID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->invoiceID->CellAttributes() ?>>
<span id="el_as_debts_invoiceID">
<input type="text" data-table="as_debts" data-field="x_invoiceID" name="x_invoiceID" id="x_invoiceID" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->invoiceID->getPlaceHolder()) ?>" value="<?php echo $as_debts->invoiceID->EditValue ?>"<?php echo $as_debts->invoiceID->EditAttributes() ?>>
</span>
<?php echo $as_debts->invoiceID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_debts_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->invoiceNo->CellAttributes() ?>>
<span id="el_as_debts_invoiceNo">
<input type="text" data-table="as_debts" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_debts->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_debts->invoiceNo->EditValue ?>"<?php echo $as_debts->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_debts->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->supplierID->Visible) { // supplierID ?>
	<div id="r_supplierID" class="form-group">
		<label id="elh_as_debts_supplierID" for="x_supplierID" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->supplierID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->supplierID->CellAttributes() ?>>
<span id="el_as_debts_supplierID">
<input type="text" data-table="as_debts" data-field="x_supplierID" name="x_supplierID" id="x_supplierID" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->supplierID->getPlaceHolder()) ?>" value="<?php echo $as_debts->supplierID->EditValue ?>"<?php echo $as_debts->supplierID->EditAttributes() ?>>
</span>
<?php echo $as_debts->supplierID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->supplierName->Visible) { // supplierName ?>
	<div id="r_supplierName" class="form-group">
		<label id="elh_as_debts_supplierName" for="x_supplierName" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->supplierName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->supplierName->CellAttributes() ?>>
<span id="el_as_debts_supplierName">
<input type="text" data-table="as_debts" data-field="x_supplierName" name="x_supplierName" id="x_supplierName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_debts->supplierName->getPlaceHolder()) ?>" value="<?php echo $as_debts->supplierName->EditValue ?>"<?php echo $as_debts->supplierName->EditAttributes() ?>>
</span>
<?php echo $as_debts->supplierName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->supplierAddress->Visible) { // supplierAddress ?>
	<div id="r_supplierAddress" class="form-group">
		<label id="elh_as_debts_supplierAddress" for="x_supplierAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->supplierAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->supplierAddress->CellAttributes() ?>>
<span id="el_as_debts_supplierAddress">
<textarea data-table="as_debts" data-field="x_supplierAddress" name="x_supplierAddress" id="x_supplierAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_debts->supplierAddress->getPlaceHolder()) ?>"<?php echo $as_debts->supplierAddress->EditAttributes() ?>><?php echo $as_debts->supplierAddress->EditValue ?></textarea>
</span>
<?php echo $as_debts->supplierAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->debtTotal->Visible) { // debtTotal ?>
	<div id="r_debtTotal" class="form-group">
		<label id="elh_as_debts_debtTotal" for="x_debtTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->debtTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->debtTotal->CellAttributes() ?>>
<span id="el_as_debts_debtTotal">
<input type="text" data-table="as_debts" data-field="x_debtTotal" name="x_debtTotal" id="x_debtTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->debtTotal->getPlaceHolder()) ?>" value="<?php echo $as_debts->debtTotal->EditValue ?>"<?php echo $as_debts->debtTotal->EditAttributes() ?>>
</span>
<?php echo $as_debts->debtTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->incomingTotal->Visible) { // incomingTotal ?>
	<div id="r_incomingTotal" class="form-group">
		<label id="elh_as_debts_incomingTotal" for="x_incomingTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->incomingTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->incomingTotal->CellAttributes() ?>>
<span id="el_as_debts_incomingTotal">
<input type="text" data-table="as_debts" data-field="x_incomingTotal" name="x_incomingTotal" id="x_incomingTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->incomingTotal->getPlaceHolder()) ?>" value="<?php echo $as_debts->incomingTotal->EditValue ?>"<?php echo $as_debts->incomingTotal->EditAttributes() ?>>
</span>
<?php echo $as_debts->incomingTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->reductionTotal->Visible) { // reductionTotal ?>
	<div id="r_reductionTotal" class="form-group">
		<label id="elh_as_debts_reductionTotal" for="x_reductionTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->reductionTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->reductionTotal->CellAttributes() ?>>
<span id="el_as_debts_reductionTotal">
<input type="text" data-table="as_debts" data-field="x_reductionTotal" name="x_reductionTotal" id="x_reductionTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->reductionTotal->getPlaceHolder()) ?>" value="<?php echo $as_debts->reductionTotal->EditValue ?>"<?php echo $as_debts->reductionTotal->EditAttributes() ?>>
</span>
<?php echo $as_debts->reductionTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_as_debts_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->status->CellAttributes() ?>>
<span id="el_as_debts_status">
<input type="text" data-table="as_debts" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_debts->status->getPlaceHolder()) ?>" value="<?php echo $as_debts->status->EditValue ?>"<?php echo $as_debts->status->EditAttributes() ?>>
</span>
<?php echo $as_debts->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_debts_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->staffID->CellAttributes() ?>>
<span id="el_as_debts_staffID">
<input type="text" data-table="as_debts" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->staffID->getPlaceHolder()) ?>" value="<?php echo $as_debts->staffID->EditValue ?>"<?php echo $as_debts->staffID->EditAttributes() ?>>
</span>
<?php echo $as_debts->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_debts_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->staffName->CellAttributes() ?>>
<span id="el_as_debts_staffName">
<input type="text" data-table="as_debts" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_debts->staffName->getPlaceHolder()) ?>" value="<?php echo $as_debts->staffName->EditValue ?>"<?php echo $as_debts->staffName->EditAttributes() ?>>
</span>
<?php echo $as_debts->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_debts_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->createdDate->CellAttributes() ?>>
<span id="el_as_debts_createdDate">
<input type="text" data-table="as_debts" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_debts->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_debts->createdDate->EditValue ?>"<?php echo $as_debts->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_debts->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_debts_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->createdUserID->CellAttributes() ?>>
<span id="el_as_debts_createdUserID">
<input type="text" data-table="as_debts" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_debts->createdUserID->EditValue ?>"<?php echo $as_debts->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_debts->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_debts_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->modifiedDate->CellAttributes() ?>>
<span id="el_as_debts_modifiedDate">
<input type="text" data-table="as_debts" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_debts->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_debts->modifiedDate->EditValue ?>"<?php echo $as_debts->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_debts->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_debts->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_debts_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_debts->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_debts->modifiedUserID->CellAttributes() ?>>
<span id="el_as_debts_modifiedUserID">
<input type="text" data-table="as_debts" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_debts->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_debts->modifiedUserID->EditValue ?>"<?php echo $as_debts->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_debts->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_debts_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_debts_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_debtsadd.Init();
</script>
<?php
$as_debts_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_debts_add->Page_Terminate();
?>
