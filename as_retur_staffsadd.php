<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_retur_staffsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_retur_staffs_add = NULL; // Initialize page object first

class cas_retur_staffs_add extends cas_retur_staffs {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_retur_staffs';

	// Page object name
	var $PageObjName = 'as_retur_staffs_add';

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

		// Table object (as_retur_staffs)
		if (!isset($GLOBALS["as_retur_staffs"]) || get_class($GLOBALS["as_retur_staffs"]) == "cas_retur_staffs") {
			$GLOBALS["as_retur_staffs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_retur_staffs"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_retur_staffs', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_retur_staffslist.php"));
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
		$this->returNo->SetVisibility();
		$this->returDate->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->customerID->SetVisibility();
		$this->customerName->SetVisibility();
		$this->customerAddress->SetVisibility();
		$this->returType->SetVisibility();
		$this->subtotal->SetVisibility();
		$this->ppnType->SetVisibility();
		$this->ppn->SetVisibility();
		$this->grandtotal->SetVisibility();
		$this->staffID->SetVisibility();
		$this->staffName->SetVisibility();
		$this->ref->SetVisibility();
		$this->note->SetVisibility();
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
		global $EW_EXPORT, $as_retur_staffs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_retur_staffs);
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
			if (@$_GET["returID"] != "") {
				$this->returID->setQueryStringValue($_GET["returID"]);
				$this->setKey("returID", $this->returID->CurrentValue); // Set up key
			} else {
				$this->setKey("returID", ""); // Clear key
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
					$this->Page_Terminate("as_retur_staffslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_retur_staffslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_retur_staffsview.php")
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
		$this->returNo->CurrentValue = NULL;
		$this->returNo->OldValue = $this->returNo->CurrentValue;
		$this->returDate->CurrentValue = NULL;
		$this->returDate->OldValue = $this->returDate->CurrentValue;
		$this->invoiceNo->CurrentValue = NULL;
		$this->invoiceNo->OldValue = $this->invoiceNo->CurrentValue;
		$this->customerID->CurrentValue = NULL;
		$this->customerID->OldValue = $this->customerID->CurrentValue;
		$this->customerName->CurrentValue = NULL;
		$this->customerName->OldValue = $this->customerName->CurrentValue;
		$this->customerAddress->CurrentValue = NULL;
		$this->customerAddress->OldValue = $this->customerAddress->CurrentValue;
		$this->returType->CurrentValue = NULL;
		$this->returType->OldValue = $this->returType->CurrentValue;
		$this->subtotal->CurrentValue = NULL;
		$this->subtotal->OldValue = $this->subtotal->CurrentValue;
		$this->ppnType->CurrentValue = NULL;
		$this->ppnType->OldValue = $this->ppnType->CurrentValue;
		$this->ppn->CurrentValue = NULL;
		$this->ppn->OldValue = $this->ppn->CurrentValue;
		$this->grandtotal->CurrentValue = NULL;
		$this->grandtotal->OldValue = $this->grandtotal->CurrentValue;
		$this->staffID->CurrentValue = NULL;
		$this->staffID->OldValue = $this->staffID->CurrentValue;
		$this->staffName->CurrentValue = NULL;
		$this->staffName->OldValue = $this->staffName->CurrentValue;
		$this->ref->CurrentValue = NULL;
		$this->ref->OldValue = $this->ref->CurrentValue;
		$this->note->CurrentValue = NULL;
		$this->note->OldValue = $this->note->CurrentValue;
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
		if (!$this->returNo->FldIsDetailKey) {
			$this->returNo->setFormValue($objForm->GetValue("x_returNo"));
		}
		if (!$this->returDate->FldIsDetailKey) {
			$this->returDate->setFormValue($objForm->GetValue("x_returDate"));
			$this->returDate->CurrentValue = ew_UnFormatDateTime($this->returDate->CurrentValue, 0);
		}
		if (!$this->invoiceNo->FldIsDetailKey) {
			$this->invoiceNo->setFormValue($objForm->GetValue("x_invoiceNo"));
		}
		if (!$this->customerID->FldIsDetailKey) {
			$this->customerID->setFormValue($objForm->GetValue("x_customerID"));
		}
		if (!$this->customerName->FldIsDetailKey) {
			$this->customerName->setFormValue($objForm->GetValue("x_customerName"));
		}
		if (!$this->customerAddress->FldIsDetailKey) {
			$this->customerAddress->setFormValue($objForm->GetValue("x_customerAddress"));
		}
		if (!$this->returType->FldIsDetailKey) {
			$this->returType->setFormValue($objForm->GetValue("x_returType"));
		}
		if (!$this->subtotal->FldIsDetailKey) {
			$this->subtotal->setFormValue($objForm->GetValue("x_subtotal"));
		}
		if (!$this->ppnType->FldIsDetailKey) {
			$this->ppnType->setFormValue($objForm->GetValue("x_ppnType"));
		}
		if (!$this->ppn->FldIsDetailKey) {
			$this->ppn->setFormValue($objForm->GetValue("x_ppn"));
		}
		if (!$this->grandtotal->FldIsDetailKey) {
			$this->grandtotal->setFormValue($objForm->GetValue("x_grandtotal"));
		}
		if (!$this->staffID->FldIsDetailKey) {
			$this->staffID->setFormValue($objForm->GetValue("x_staffID"));
		}
		if (!$this->staffName->FldIsDetailKey) {
			$this->staffName->setFormValue($objForm->GetValue("x_staffName"));
		}
		if (!$this->ref->FldIsDetailKey) {
			$this->ref->setFormValue($objForm->GetValue("x_ref"));
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
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
		$this->returNo->CurrentValue = $this->returNo->FormValue;
		$this->returDate->CurrentValue = $this->returDate->FormValue;
		$this->returDate->CurrentValue = ew_UnFormatDateTime($this->returDate->CurrentValue, 0);
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->customerID->CurrentValue = $this->customerID->FormValue;
		$this->customerName->CurrentValue = $this->customerName->FormValue;
		$this->customerAddress->CurrentValue = $this->customerAddress->FormValue;
		$this->returType->CurrentValue = $this->returType->FormValue;
		$this->subtotal->CurrentValue = $this->subtotal->FormValue;
		$this->ppnType->CurrentValue = $this->ppnType->FormValue;
		$this->ppn->CurrentValue = $this->ppn->FormValue;
		$this->grandtotal->CurrentValue = $this->grandtotal->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->ref->CurrentValue = $this->ref->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
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
		$this->returID->setDbValue($rs->fields('returID'));
		$this->returNo->setDbValue($rs->fields('returNo'));
		$this->returDate->setDbValue($rs->fields('returDate'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
		$this->returType->setDbValue($rs->fields('returType'));
		$this->subtotal->setDbValue($rs->fields('subtotal'));
		$this->ppnType->setDbValue($rs->fields('ppnType'));
		$this->ppn->setDbValue($rs->fields('ppn'));
		$this->grandtotal->setDbValue($rs->fields('grandtotal'));
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->ref->setDbValue($rs->fields('ref'));
		$this->note->setDbValue($rs->fields('note'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->returID->DbValue = $row['returID'];
		$this->returNo->DbValue = $row['returNo'];
		$this->returDate->DbValue = $row['returDate'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->customerID->DbValue = $row['customerID'];
		$this->customerName->DbValue = $row['customerName'];
		$this->customerAddress->DbValue = $row['customerAddress'];
		$this->returType->DbValue = $row['returType'];
		$this->subtotal->DbValue = $row['subtotal'];
		$this->ppnType->DbValue = $row['ppnType'];
		$this->ppn->DbValue = $row['ppn'];
		$this->grandtotal->DbValue = $row['grandtotal'];
		$this->staffID->DbValue = $row['staffID'];
		$this->staffName->DbValue = $row['staffName'];
		$this->ref->DbValue = $row['ref'];
		$this->note->DbValue = $row['note'];
		$this->createdDate->DbValue = $row['createdDate'];
		$this->createdUserID->DbValue = $row['createdUserID'];
		$this->modifiedDate->DbValue = $row['modifiedDate'];
		$this->modifiedUserID->DbValue = $row['modifiedUserID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("returID")) <> "")
			$this->returID->CurrentValue = $this->getKey("returID"); // returID
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

		if ($this->subtotal->FormValue == $this->subtotal->CurrentValue && is_numeric(ew_StrToFloat($this->subtotal->CurrentValue)))
			$this->subtotal->CurrentValue = ew_StrToFloat($this->subtotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->ppn->FormValue == $this->ppn->CurrentValue && is_numeric(ew_StrToFloat($this->ppn->CurrentValue)))
			$this->ppn->CurrentValue = ew_StrToFloat($this->ppn->CurrentValue);

		// Convert decimal values if posted back
		if ($this->grandtotal->FormValue == $this->grandtotal->CurrentValue && is_numeric(ew_StrToFloat($this->grandtotal->CurrentValue)))
			$this->grandtotal->CurrentValue = ew_StrToFloat($this->grandtotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// returID
		// returNo
		// returDate
		// invoiceNo
		// customerID
		// customerName
		// customerAddress
		// returType
		// subtotal
		// ppnType
		// ppn
		// grandtotal
		// staffID
		// staffName
		// ref
		// note
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// returID
		$this->returID->ViewValue = $this->returID->CurrentValue;
		$this->returID->ViewCustomAttributes = "";

		// returNo
		$this->returNo->ViewValue = $this->returNo->CurrentValue;
		$this->returNo->ViewCustomAttributes = "";

		// returDate
		$this->returDate->ViewValue = $this->returDate->CurrentValue;
		$this->returDate->ViewValue = ew_FormatDateTime($this->returDate->ViewValue, 0);
		$this->returDate->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// customerAddress
		$this->customerAddress->ViewValue = $this->customerAddress->CurrentValue;
		$this->customerAddress->ViewCustomAttributes = "";

		// returType
		$this->returType->ViewValue = $this->returType->CurrentValue;
		$this->returType->ViewCustomAttributes = "";

		// subtotal
		$this->subtotal->ViewValue = $this->subtotal->CurrentValue;
		$this->subtotal->ViewCustomAttributes = "";

		// ppnType
		$this->ppnType->ViewValue = $this->ppnType->CurrentValue;
		$this->ppnType->ViewCustomAttributes = "";

		// ppn
		$this->ppn->ViewValue = $this->ppn->CurrentValue;
		$this->ppn->ViewCustomAttributes = "";

		// grandtotal
		$this->grandtotal->ViewValue = $this->grandtotal->CurrentValue;
		$this->grandtotal->ViewCustomAttributes = "";

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// ref
		$this->ref->ViewValue = $this->ref->CurrentValue;
		$this->ref->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

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

			// returNo
			$this->returNo->LinkCustomAttributes = "";
			$this->returNo->HrefValue = "";
			$this->returNo->TooltipValue = "";

			// returDate
			$this->returDate->LinkCustomAttributes = "";
			$this->returDate->HrefValue = "";
			$this->returDate->TooltipValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";
			$this->invoiceNo->TooltipValue = "";

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

			// returType
			$this->returType->LinkCustomAttributes = "";
			$this->returType->HrefValue = "";
			$this->returType->TooltipValue = "";

			// subtotal
			$this->subtotal->LinkCustomAttributes = "";
			$this->subtotal->HrefValue = "";
			$this->subtotal->TooltipValue = "";

			// ppnType
			$this->ppnType->LinkCustomAttributes = "";
			$this->ppnType->HrefValue = "";
			$this->ppnType->TooltipValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";
			$this->ppn->TooltipValue = "";

			// grandtotal
			$this->grandtotal->LinkCustomAttributes = "";
			$this->grandtotal->HrefValue = "";
			$this->grandtotal->TooltipValue = "";

			// staffID
			$this->staffID->LinkCustomAttributes = "";
			$this->staffID->HrefValue = "";
			$this->staffID->TooltipValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";
			$this->staffName->TooltipValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";
			$this->ref->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

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

			// returNo
			$this->returNo->EditAttrs["class"] = "form-control";
			$this->returNo->EditCustomAttributes = "";
			$this->returNo->EditValue = ew_HtmlEncode($this->returNo->CurrentValue);
			$this->returNo->PlaceHolder = ew_RemoveHtml($this->returNo->FldCaption());

			// returDate
			$this->returDate->EditAttrs["class"] = "form-control";
			$this->returDate->EditCustomAttributes = "";
			$this->returDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->returDate->CurrentValue, 8));
			$this->returDate->PlaceHolder = ew_RemoveHtml($this->returDate->FldCaption());

			// invoiceNo
			$this->invoiceNo->EditAttrs["class"] = "form-control";
			$this->invoiceNo->EditCustomAttributes = "";
			$this->invoiceNo->EditValue = ew_HtmlEncode($this->invoiceNo->CurrentValue);
			$this->invoiceNo->PlaceHolder = ew_RemoveHtml($this->invoiceNo->FldCaption());

			// customerID
			$this->customerID->EditAttrs["class"] = "form-control";
			$this->customerID->EditCustomAttributes = "";
			$this->customerID->EditValue = ew_HtmlEncode($this->customerID->CurrentValue);
			$this->customerID->PlaceHolder = ew_RemoveHtml($this->customerID->FldCaption());

			// customerName
			$this->customerName->EditAttrs["class"] = "form-control";
			$this->customerName->EditCustomAttributes = "";
			$this->customerName->EditValue = ew_HtmlEncode($this->customerName->CurrentValue);
			$this->customerName->PlaceHolder = ew_RemoveHtml($this->customerName->FldCaption());

			// customerAddress
			$this->customerAddress->EditAttrs["class"] = "form-control";
			$this->customerAddress->EditCustomAttributes = "";
			$this->customerAddress->EditValue = ew_HtmlEncode($this->customerAddress->CurrentValue);
			$this->customerAddress->PlaceHolder = ew_RemoveHtml($this->customerAddress->FldCaption());

			// returType
			$this->returType->EditAttrs["class"] = "form-control";
			$this->returType->EditCustomAttributes = "";
			$this->returType->EditValue = ew_HtmlEncode($this->returType->CurrentValue);
			$this->returType->PlaceHolder = ew_RemoveHtml($this->returType->FldCaption());

			// subtotal
			$this->subtotal->EditAttrs["class"] = "form-control";
			$this->subtotal->EditCustomAttributes = "";
			$this->subtotal->EditValue = ew_HtmlEncode($this->subtotal->CurrentValue);
			$this->subtotal->PlaceHolder = ew_RemoveHtml($this->subtotal->FldCaption());
			if (strval($this->subtotal->EditValue) <> "" && is_numeric($this->subtotal->EditValue)) $this->subtotal->EditValue = ew_FormatNumber($this->subtotal->EditValue, -2, -1, -2, 0);

			// ppnType
			$this->ppnType->EditAttrs["class"] = "form-control";
			$this->ppnType->EditCustomAttributes = "";
			$this->ppnType->EditValue = ew_HtmlEncode($this->ppnType->CurrentValue);
			$this->ppnType->PlaceHolder = ew_RemoveHtml($this->ppnType->FldCaption());

			// ppn
			$this->ppn->EditAttrs["class"] = "form-control";
			$this->ppn->EditCustomAttributes = "";
			$this->ppn->EditValue = ew_HtmlEncode($this->ppn->CurrentValue);
			$this->ppn->PlaceHolder = ew_RemoveHtml($this->ppn->FldCaption());
			if (strval($this->ppn->EditValue) <> "" && is_numeric($this->ppn->EditValue)) $this->ppn->EditValue = ew_FormatNumber($this->ppn->EditValue, -2, -1, -2, 0);

			// grandtotal
			$this->grandtotal->EditAttrs["class"] = "form-control";
			$this->grandtotal->EditCustomAttributes = "";
			$this->grandtotal->EditValue = ew_HtmlEncode($this->grandtotal->CurrentValue);
			$this->grandtotal->PlaceHolder = ew_RemoveHtml($this->grandtotal->FldCaption());
			if (strval($this->grandtotal->EditValue) <> "" && is_numeric($this->grandtotal->EditValue)) $this->grandtotal->EditValue = ew_FormatNumber($this->grandtotal->EditValue, -2, -1, -2, 0);

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

			// ref
			$this->ref->EditAttrs["class"] = "form-control";
			$this->ref->EditCustomAttributes = "";
			$this->ref->EditValue = ew_HtmlEncode($this->ref->CurrentValue);
			$this->ref->PlaceHolder = ew_RemoveHtml($this->ref->FldCaption());

			// note
			$this->note->EditAttrs["class"] = "form-control";
			$this->note->EditCustomAttributes = "";
			$this->note->EditValue = ew_HtmlEncode($this->note->CurrentValue);
			$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

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
			// returNo

			$this->returNo->LinkCustomAttributes = "";
			$this->returNo->HrefValue = "";

			// returDate
			$this->returDate->LinkCustomAttributes = "";
			$this->returDate->HrefValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";

			// customerID
			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";

			// customerAddress
			$this->customerAddress->LinkCustomAttributes = "";
			$this->customerAddress->HrefValue = "";

			// returType
			$this->returType->LinkCustomAttributes = "";
			$this->returType->HrefValue = "";

			// subtotal
			$this->subtotal->LinkCustomAttributes = "";
			$this->subtotal->HrefValue = "";

			// ppnType
			$this->ppnType->LinkCustomAttributes = "";
			$this->ppnType->HrefValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";

			// grandtotal
			$this->grandtotal->LinkCustomAttributes = "";
			$this->grandtotal->HrefValue = "";

			// staffID
			$this->staffID->LinkCustomAttributes = "";
			$this->staffID->HrefValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

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
		if (!$this->returNo->FldIsDetailKey && !is_null($this->returNo->FormValue) && $this->returNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->returNo->FldCaption(), $this->returNo->ReqErrMsg));
		}
		if (!$this->returDate->FldIsDetailKey && !is_null($this->returDate->FormValue) && $this->returDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->returDate->FldCaption(), $this->returDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->returDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->returDate->FldErrMsg());
		}
		if (!$this->invoiceNo->FldIsDetailKey && !is_null($this->invoiceNo->FormValue) && $this->invoiceNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoiceNo->FldCaption(), $this->invoiceNo->ReqErrMsg));
		}
		if (!$this->customerID->FldIsDetailKey && !is_null($this->customerID->FormValue) && $this->customerID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customerID->FldCaption(), $this->customerID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->customerID->FormValue)) {
			ew_AddMessage($gsFormError, $this->customerID->FldErrMsg());
		}
		if (!$this->customerName->FldIsDetailKey && !is_null($this->customerName->FormValue) && $this->customerName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customerName->FldCaption(), $this->customerName->ReqErrMsg));
		}
		if (!$this->customerAddress->FldIsDetailKey && !is_null($this->customerAddress->FormValue) && $this->customerAddress->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customerAddress->FldCaption(), $this->customerAddress->ReqErrMsg));
		}
		if (!$this->returType->FldIsDetailKey && !is_null($this->returType->FormValue) && $this->returType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->returType->FldCaption(), $this->returType->ReqErrMsg));
		}
		if (!$this->subtotal->FldIsDetailKey && !is_null($this->subtotal->FormValue) && $this->subtotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subtotal->FldCaption(), $this->subtotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->subtotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->subtotal->FldErrMsg());
		}
		if (!$this->ppnType->FldIsDetailKey && !is_null($this->ppnType->FormValue) && $this->ppnType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ppnType->FldCaption(), $this->ppnType->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ppnType->FormValue)) {
			ew_AddMessage($gsFormError, $this->ppnType->FldErrMsg());
		}
		if (!$this->ppn->FldIsDetailKey && !is_null($this->ppn->FormValue) && $this->ppn->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ppn->FldCaption(), $this->ppn->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->ppn->FormValue)) {
			ew_AddMessage($gsFormError, $this->ppn->FldErrMsg());
		}
		if (!$this->grandtotal->FldIsDetailKey && !is_null($this->grandtotal->FormValue) && $this->grandtotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grandtotal->FldCaption(), $this->grandtotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->grandtotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->grandtotal->FldErrMsg());
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
		if (!$this->ref->FldIsDetailKey && !is_null($this->ref->FormValue) && $this->ref->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ref->FldCaption(), $this->ref->ReqErrMsg));
		}
		if (!$this->note->FldIsDetailKey && !is_null($this->note->FormValue) && $this->note->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->note->FldCaption(), $this->note->ReqErrMsg));
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

		// returNo
		$this->returNo->SetDbValueDef($rsnew, $this->returNo->CurrentValue, "", FALSE);

		// returDate
		$this->returDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->returDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// invoiceNo
		$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", FALSE);

		// customerID
		$this->customerID->SetDbValueDef($rsnew, $this->customerID->CurrentValue, 0, FALSE);

		// customerName
		$this->customerName->SetDbValueDef($rsnew, $this->customerName->CurrentValue, "", FALSE);

		// customerAddress
		$this->customerAddress->SetDbValueDef($rsnew, $this->customerAddress->CurrentValue, "", FALSE);

		// returType
		$this->returType->SetDbValueDef($rsnew, $this->returType->CurrentValue, "", FALSE);

		// subtotal
		$this->subtotal->SetDbValueDef($rsnew, $this->subtotal->CurrentValue, 0, FALSE);

		// ppnType
		$this->ppnType->SetDbValueDef($rsnew, $this->ppnType->CurrentValue, 0, FALSE);

		// ppn
		$this->ppn->SetDbValueDef($rsnew, $this->ppn->CurrentValue, 0, FALSE);

		// grandtotal
		$this->grandtotal->SetDbValueDef($rsnew, $this->grandtotal->CurrentValue, 0, FALSE);

		// staffID
		$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// ref
		$this->ref->SetDbValueDef($rsnew, $this->ref->CurrentValue, "", FALSE);

		// note
		$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_retur_staffslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_retur_staffs_add)) $as_retur_staffs_add = new cas_retur_staffs_add();

// Page init
$as_retur_staffs_add->Page_Init();

// Page main
$as_retur_staffs_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_retur_staffs_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_retur_staffsadd = new ew_Form("fas_retur_staffsadd", "add");

// Validate form
fas_retur_staffsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_returNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->returNo->FldCaption(), $as_retur_staffs->returNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_returDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->returDate->FldCaption(), $as_retur_staffs->returDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_returDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->returDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_invoiceNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->invoiceNo->FldCaption(), $as_retur_staffs->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->customerID->FldCaption(), $as_retur_staffs->customerID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->customerID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->customerName->FldCaption(), $as_retur_staffs->customerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->customerAddress->FldCaption(), $as_retur_staffs->customerAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_returType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->returType->FldCaption(), $as_retur_staffs->returType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subtotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->subtotal->FldCaption(), $as_retur_staffs->subtotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subtotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->subtotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->ppnType->FldCaption(), $as_retur_staffs->ppnType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->ppnType->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->ppn->FldCaption(), $as_retur_staffs->ppn->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->ppn->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->grandtotal->FldCaption(), $as_retur_staffs->grandtotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->grandtotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->staffID->FldCaption(), $as_retur_staffs->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->staffName->FldCaption(), $as_retur_staffs->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ref");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->ref->FldCaption(), $as_retur_staffs->ref->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->note->FldCaption(), $as_retur_staffs->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->createdDate->FldCaption(), $as_retur_staffs->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->createdUserID->FldCaption(), $as_retur_staffs->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->modifiedDate->FldCaption(), $as_retur_staffs->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_retur_staffs->modifiedUserID->FldCaption(), $as_retur_staffs->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_retur_staffs->modifiedUserID->FldErrMsg()) ?>");

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
fas_retur_staffsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_retur_staffsadd.ValidateRequired = true;
<?php } else { ?>
fas_retur_staffsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_retur_staffs_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_retur_staffs_add->ShowPageHeader(); ?>
<?php
$as_retur_staffs_add->ShowMessage();
?>
<form name="fas_retur_staffsadd" id="fas_retur_staffsadd" class="<?php echo $as_retur_staffs_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_retur_staffs_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_retur_staffs_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_retur_staffs">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_retur_staffs_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_retur_staffs->returNo->Visible) { // returNo ?>
	<div id="r_returNo" class="form-group">
		<label id="elh_as_retur_staffs_returNo" for="x_returNo" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->returNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->returNo->CellAttributes() ?>>
<span id="el_as_retur_staffs_returNo">
<input type="text" data-table="as_retur_staffs" data-field="x_returNo" name="x_returNo" id="x_returNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->returNo->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->returNo->EditValue ?>"<?php echo $as_retur_staffs->returNo->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->returNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->returDate->Visible) { // returDate ?>
	<div id="r_returDate" class="form-group">
		<label id="elh_as_retur_staffs_returDate" for="x_returDate" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->returDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->returDate->CellAttributes() ?>>
<span id="el_as_retur_staffs_returDate">
<input type="text" data-table="as_retur_staffs" data-field="x_returDate" name="x_returDate" id="x_returDate" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->returDate->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->returDate->EditValue ?>"<?php echo $as_retur_staffs->returDate->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->returDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_retur_staffs_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->invoiceNo->CellAttributes() ?>>
<span id="el_as_retur_staffs_invoiceNo">
<input type="text" data-table="as_retur_staffs" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->invoiceNo->EditValue ?>"<?php echo $as_retur_staffs->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->customerID->Visible) { // customerID ?>
	<div id="r_customerID" class="form-group">
		<label id="elh_as_retur_staffs_customerID" for="x_customerID" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->customerID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->customerID->CellAttributes() ?>>
<span id="el_as_retur_staffs_customerID">
<input type="text" data-table="as_retur_staffs" data-field="x_customerID" name="x_customerID" id="x_customerID" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->customerID->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->customerID->EditValue ?>"<?php echo $as_retur_staffs->customerID->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->customerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->customerName->Visible) { // customerName ?>
	<div id="r_customerName" class="form-group">
		<label id="elh_as_retur_staffs_customerName" for="x_customerName" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->customerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->customerName->CellAttributes() ?>>
<span id="el_as_retur_staffs_customerName">
<input type="text" data-table="as_retur_staffs" data-field="x_customerName" name="x_customerName" id="x_customerName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->customerName->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->customerName->EditValue ?>"<?php echo $as_retur_staffs->customerName->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->customerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->customerAddress->Visible) { // customerAddress ?>
	<div id="r_customerAddress" class="form-group">
		<label id="elh_as_retur_staffs_customerAddress" for="x_customerAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->customerAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->customerAddress->CellAttributes() ?>>
<span id="el_as_retur_staffs_customerAddress">
<textarea data-table="as_retur_staffs" data-field="x_customerAddress" name="x_customerAddress" id="x_customerAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->customerAddress->getPlaceHolder()) ?>"<?php echo $as_retur_staffs->customerAddress->EditAttributes() ?>><?php echo $as_retur_staffs->customerAddress->EditValue ?></textarea>
</span>
<?php echo $as_retur_staffs->customerAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->returType->Visible) { // returType ?>
	<div id="r_returType" class="form-group">
		<label id="elh_as_retur_staffs_returType" for="x_returType" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->returType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->returType->CellAttributes() ?>>
<span id="el_as_retur_staffs_returType">
<input type="text" data-table="as_retur_staffs" data-field="x_returType" name="x_returType" id="x_returType" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->returType->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->returType->EditValue ?>"<?php echo $as_retur_staffs->returType->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->returType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->subtotal->Visible) { // subtotal ?>
	<div id="r_subtotal" class="form-group">
		<label id="elh_as_retur_staffs_subtotal" for="x_subtotal" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->subtotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->subtotal->CellAttributes() ?>>
<span id="el_as_retur_staffs_subtotal">
<input type="text" data-table="as_retur_staffs" data-field="x_subtotal" name="x_subtotal" id="x_subtotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->subtotal->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->subtotal->EditValue ?>"<?php echo $as_retur_staffs->subtotal->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->subtotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->ppnType->Visible) { // ppnType ?>
	<div id="r_ppnType" class="form-group">
		<label id="elh_as_retur_staffs_ppnType" for="x_ppnType" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->ppnType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->ppnType->CellAttributes() ?>>
<span id="el_as_retur_staffs_ppnType">
<input type="text" data-table="as_retur_staffs" data-field="x_ppnType" name="x_ppnType" id="x_ppnType" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->ppnType->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->ppnType->EditValue ?>"<?php echo $as_retur_staffs->ppnType->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->ppnType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->ppn->Visible) { // ppn ?>
	<div id="r_ppn" class="form-group">
		<label id="elh_as_retur_staffs_ppn" for="x_ppn" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->ppn->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->ppn->CellAttributes() ?>>
<span id="el_as_retur_staffs_ppn">
<input type="text" data-table="as_retur_staffs" data-field="x_ppn" name="x_ppn" id="x_ppn" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->ppn->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->ppn->EditValue ?>"<?php echo $as_retur_staffs->ppn->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->ppn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->grandtotal->Visible) { // grandtotal ?>
	<div id="r_grandtotal" class="form-group">
		<label id="elh_as_retur_staffs_grandtotal" for="x_grandtotal" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->grandtotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->grandtotal->CellAttributes() ?>>
<span id="el_as_retur_staffs_grandtotal">
<input type="text" data-table="as_retur_staffs" data-field="x_grandtotal" name="x_grandtotal" id="x_grandtotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->grandtotal->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->grandtotal->EditValue ?>"<?php echo $as_retur_staffs->grandtotal->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->grandtotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_retur_staffs_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->staffID->CellAttributes() ?>>
<span id="el_as_retur_staffs_staffID">
<input type="text" data-table="as_retur_staffs" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->staffID->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->staffID->EditValue ?>"<?php echo $as_retur_staffs->staffID->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_retur_staffs_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->staffName->CellAttributes() ?>>
<span id="el_as_retur_staffs_staffName">
<input type="text" data-table="as_retur_staffs" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->staffName->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->staffName->EditValue ?>"<?php echo $as_retur_staffs->staffName->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->ref->Visible) { // ref ?>
	<div id="r_ref" class="form-group">
		<label id="elh_as_retur_staffs_ref" for="x_ref" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->ref->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->ref->CellAttributes() ?>>
<span id="el_as_retur_staffs_ref">
<input type="text" data-table="as_retur_staffs" data-field="x_ref" name="x_ref" id="x_ref" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->ref->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->ref->EditValue ?>"<?php echo $as_retur_staffs->ref->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->ref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_retur_staffs_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->note->CellAttributes() ?>>
<span id="el_as_retur_staffs_note">
<textarea data-table="as_retur_staffs" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->note->getPlaceHolder()) ?>"<?php echo $as_retur_staffs->note->EditAttributes() ?>><?php echo $as_retur_staffs->note->EditValue ?></textarea>
</span>
<?php echo $as_retur_staffs->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_retur_staffs_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->createdDate->CellAttributes() ?>>
<span id="el_as_retur_staffs_createdDate">
<input type="text" data-table="as_retur_staffs" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->createdDate->EditValue ?>"<?php echo $as_retur_staffs->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_retur_staffs_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->createdUserID->CellAttributes() ?>>
<span id="el_as_retur_staffs_createdUserID">
<input type="text" data-table="as_retur_staffs" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->createdUserID->EditValue ?>"<?php echo $as_retur_staffs->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_retur_staffs_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->modifiedDate->CellAttributes() ?>>
<span id="el_as_retur_staffs_modifiedDate">
<input type="text" data-table="as_retur_staffs" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->modifiedDate->EditValue ?>"<?php echo $as_retur_staffs->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_retur_staffs->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_retur_staffs_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_retur_staffs->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_retur_staffs->modifiedUserID->CellAttributes() ?>>
<span id="el_as_retur_staffs_modifiedUserID">
<input type="text" data-table="as_retur_staffs" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_retur_staffs->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_retur_staffs->modifiedUserID->EditValue ?>"<?php echo $as_retur_staffs->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_retur_staffs->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_retur_staffs_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_retur_staffs_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_retur_staffsadd.Init();
</script>
<?php
$as_retur_staffs_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_retur_staffs_add->Page_Terminate();
?>
