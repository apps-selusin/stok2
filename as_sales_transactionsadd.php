<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_sales_transactionsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_sales_transactions_add = NULL; // Initialize page object first

class cas_sales_transactions_add extends cas_sales_transactions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_sales_transactions';

	// Page object name
	var $PageObjName = 'as_sales_transactions_add';

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

		// Table object (as_sales_transactions)
		if (!isset($GLOBALS["as_sales_transactions"]) || get_class($GLOBALS["as_sales_transactions"]) == "cas_sales_transactions") {
			$GLOBALS["as_sales_transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_sales_transactions"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_sales_transactions', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_sales_transactionslist.php"));
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
		$this->invoiceNo->SetVisibility();
		$this->invoiceDate->SetVisibility();
		$this->doNo->SetVisibility();
		$this->soNo->SetVisibility();
		$this->paymentType->SetVisibility();
		$this->expiredPayment->SetVisibility();
		$this->ppnType->SetVisibility();
		$this->ppn->SetVisibility();
		$this->total->SetVisibility();
		$this->basic->SetVisibility();
		$this->discount->SetVisibility();
		$this->grandtotal->SetVisibility();
		$this->customerID->SetVisibility();
		$this->customerName->SetVisibility();
		$this->customerAddress->SetVisibility();
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
		global $EW_EXPORT, $as_sales_transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_sales_transactions);
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
			if (@$_GET["invoiceID"] != "") {
				$this->invoiceID->setQueryStringValue($_GET["invoiceID"]);
				$this->setKey("invoiceID", $this->invoiceID->CurrentValue); // Set up key
			} else {
				$this->setKey("invoiceID", ""); // Clear key
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
					$this->Page_Terminate("as_sales_transactionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_sales_transactionslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_sales_transactionsview.php")
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
		$this->invoiceNo->CurrentValue = NULL;
		$this->invoiceNo->OldValue = $this->invoiceNo->CurrentValue;
		$this->invoiceDate->CurrentValue = NULL;
		$this->invoiceDate->OldValue = $this->invoiceDate->CurrentValue;
		$this->doNo->CurrentValue = NULL;
		$this->doNo->OldValue = $this->doNo->CurrentValue;
		$this->soNo->CurrentValue = NULL;
		$this->soNo->OldValue = $this->soNo->CurrentValue;
		$this->paymentType->CurrentValue = NULL;
		$this->paymentType->OldValue = $this->paymentType->CurrentValue;
		$this->expiredPayment->CurrentValue = NULL;
		$this->expiredPayment->OldValue = $this->expiredPayment->CurrentValue;
		$this->ppnType->CurrentValue = NULL;
		$this->ppnType->OldValue = $this->ppnType->CurrentValue;
		$this->ppn->CurrentValue = NULL;
		$this->ppn->OldValue = $this->ppn->CurrentValue;
		$this->total->CurrentValue = NULL;
		$this->total->OldValue = $this->total->CurrentValue;
		$this->basic->CurrentValue = NULL;
		$this->basic->OldValue = $this->basic->CurrentValue;
		$this->discount->CurrentValue = NULL;
		$this->discount->OldValue = $this->discount->CurrentValue;
		$this->grandtotal->CurrentValue = NULL;
		$this->grandtotal->OldValue = $this->grandtotal->CurrentValue;
		$this->customerID->CurrentValue = NULL;
		$this->customerID->OldValue = $this->customerID->CurrentValue;
		$this->customerName->CurrentValue = NULL;
		$this->customerName->OldValue = $this->customerName->CurrentValue;
		$this->customerAddress->CurrentValue = NULL;
		$this->customerAddress->OldValue = $this->customerAddress->CurrentValue;
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
		if (!$this->invoiceNo->FldIsDetailKey) {
			$this->invoiceNo->setFormValue($objForm->GetValue("x_invoiceNo"));
		}
		if (!$this->invoiceDate->FldIsDetailKey) {
			$this->invoiceDate->setFormValue($objForm->GetValue("x_invoiceDate"));
			$this->invoiceDate->CurrentValue = ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0);
		}
		if (!$this->doNo->FldIsDetailKey) {
			$this->doNo->setFormValue($objForm->GetValue("x_doNo"));
		}
		if (!$this->soNo->FldIsDetailKey) {
			$this->soNo->setFormValue($objForm->GetValue("x_soNo"));
		}
		if (!$this->paymentType->FldIsDetailKey) {
			$this->paymentType->setFormValue($objForm->GetValue("x_paymentType"));
		}
		if (!$this->expiredPayment->FldIsDetailKey) {
			$this->expiredPayment->setFormValue($objForm->GetValue("x_expiredPayment"));
			$this->expiredPayment->CurrentValue = ew_UnFormatDateTime($this->expiredPayment->CurrentValue, 0);
		}
		if (!$this->ppnType->FldIsDetailKey) {
			$this->ppnType->setFormValue($objForm->GetValue("x_ppnType"));
		}
		if (!$this->ppn->FldIsDetailKey) {
			$this->ppn->setFormValue($objForm->GetValue("x_ppn"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
		}
		if (!$this->basic->FldIsDetailKey) {
			$this->basic->setFormValue($objForm->GetValue("x_basic"));
		}
		if (!$this->discount->FldIsDetailKey) {
			$this->discount->setFormValue($objForm->GetValue("x_discount"));
		}
		if (!$this->grandtotal->FldIsDetailKey) {
			$this->grandtotal->setFormValue($objForm->GetValue("x_grandtotal"));
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
		if (!$this->staffID->FldIsDetailKey) {
			$this->staffID->setFormValue($objForm->GetValue("x_staffID"));
		}
		if (!$this->staffName->FldIsDetailKey) {
			$this->staffName->setFormValue($objForm->GetValue("x_staffName"));
		}
		if (!$this->createdDate->FldIsDetailKey) {
			$this->createdDate->setFormValue($objForm->GetValue("x_createdDate"));
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
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->invoiceDate->CurrentValue = $this->invoiceDate->FormValue;
		$this->invoiceDate->CurrentValue = ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0);
		$this->doNo->CurrentValue = $this->doNo->FormValue;
		$this->soNo->CurrentValue = $this->soNo->FormValue;
		$this->paymentType->CurrentValue = $this->paymentType->FormValue;
		$this->expiredPayment->CurrentValue = $this->expiredPayment->FormValue;
		$this->expiredPayment->CurrentValue = ew_UnFormatDateTime($this->expiredPayment->CurrentValue, 0);
		$this->ppnType->CurrentValue = $this->ppnType->FormValue;
		$this->ppn->CurrentValue = $this->ppn->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->basic->CurrentValue = $this->basic->FormValue;
		$this->discount->CurrentValue = $this->discount->FormValue;
		$this->grandtotal->CurrentValue = $this->grandtotal->FormValue;
		$this->customerID->CurrentValue = $this->customerID->FormValue;
		$this->customerName->CurrentValue = $this->customerName->FormValue;
		$this->customerAddress->CurrentValue = $this->customerAddress->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->createdDate->CurrentValue = $this->createdDate->FormValue;
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
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->invoiceDate->setDbValue($rs->fields('invoiceDate'));
		$this->doNo->setDbValue($rs->fields('doNo'));
		$this->soNo->setDbValue($rs->fields('soNo'));
		$this->paymentType->setDbValue($rs->fields('paymentType'));
		$this->expiredPayment->setDbValue($rs->fields('expiredPayment'));
		$this->ppnType->setDbValue($rs->fields('ppnType'));
		$this->ppn->setDbValue($rs->fields('ppn'));
		$this->total->setDbValue($rs->fields('total'));
		$this->basic->setDbValue($rs->fields('basic'));
		$this->discount->setDbValue($rs->fields('discount'));
		$this->grandtotal->setDbValue($rs->fields('grandtotal'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
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
		$this->invoiceID->DbValue = $row['invoiceID'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->invoiceDate->DbValue = $row['invoiceDate'];
		$this->doNo->DbValue = $row['doNo'];
		$this->soNo->DbValue = $row['soNo'];
		$this->paymentType->DbValue = $row['paymentType'];
		$this->expiredPayment->DbValue = $row['expiredPayment'];
		$this->ppnType->DbValue = $row['ppnType'];
		$this->ppn->DbValue = $row['ppn'];
		$this->total->DbValue = $row['total'];
		$this->basic->DbValue = $row['basic'];
		$this->discount->DbValue = $row['discount'];
		$this->grandtotal->DbValue = $row['grandtotal'];
		$this->customerID->DbValue = $row['customerID'];
		$this->customerName->DbValue = $row['customerName'];
		$this->customerAddress->DbValue = $row['customerAddress'];
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
		if (strval($this->getKey("invoiceID")) <> "")
			$this->invoiceID->CurrentValue = $this->getKey("invoiceID"); // invoiceID
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

		if ($this->ppn->FormValue == $this->ppn->CurrentValue && is_numeric(ew_StrToFloat($this->ppn->CurrentValue)))
			$this->ppn->CurrentValue = ew_StrToFloat($this->ppn->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->basic->FormValue == $this->basic->CurrentValue && is_numeric(ew_StrToFloat($this->basic->CurrentValue)))
			$this->basic->CurrentValue = ew_StrToFloat($this->basic->CurrentValue);

		// Convert decimal values if posted back
		if ($this->discount->FormValue == $this->discount->CurrentValue && is_numeric(ew_StrToFloat($this->discount->CurrentValue)))
			$this->discount->CurrentValue = ew_StrToFloat($this->discount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->grandtotal->FormValue == $this->grandtotal->CurrentValue && is_numeric(ew_StrToFloat($this->grandtotal->CurrentValue)))
			$this->grandtotal->CurrentValue = ew_StrToFloat($this->grandtotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// invoiceID
		// invoiceNo
		// invoiceDate
		// doNo
		// soNo
		// paymentType
		// expiredPayment
		// ppnType
		// ppn
		// total
		// basic
		// discount
		// grandtotal
		// customerID
		// customerName
		// customerAddress
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// invoiceDate
		$this->invoiceDate->ViewValue = $this->invoiceDate->CurrentValue;
		$this->invoiceDate->ViewValue = ew_FormatDateTime($this->invoiceDate->ViewValue, 0);
		$this->invoiceDate->ViewCustomAttributes = "";

		// doNo
		$this->doNo->ViewValue = $this->doNo->CurrentValue;
		$this->doNo->ViewCustomAttributes = "";

		// soNo
		$this->soNo->ViewValue = $this->soNo->CurrentValue;
		$this->soNo->ViewCustomAttributes = "";

		// paymentType
		$this->paymentType->ViewValue = $this->paymentType->CurrentValue;
		$this->paymentType->ViewCustomAttributes = "";

		// expiredPayment
		$this->expiredPayment->ViewValue = $this->expiredPayment->CurrentValue;
		$this->expiredPayment->ViewValue = ew_FormatDateTime($this->expiredPayment->ViewValue, 0);
		$this->expiredPayment->ViewCustomAttributes = "";

		// ppnType
		$this->ppnType->ViewValue = $this->ppnType->CurrentValue;
		$this->ppnType->ViewCustomAttributes = "";

		// ppn
		$this->ppn->ViewValue = $this->ppn->CurrentValue;
		$this->ppn->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// basic
		$this->basic->ViewValue = $this->basic->CurrentValue;
		$this->basic->ViewCustomAttributes = "";

		// discount
		$this->discount->ViewValue = $this->discount->CurrentValue;
		$this->discount->ViewCustomAttributes = "";

		// grandtotal
		$this->grandtotal->ViewValue = $this->grandtotal->CurrentValue;
		$this->grandtotal->ViewCustomAttributes = "";

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// customerAddress
		$this->customerAddress->ViewValue = $this->customerAddress->CurrentValue;
		$this->customerAddress->ViewCustomAttributes = "";

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// createdDate
		$this->createdDate->ViewValue = $this->createdDate->CurrentValue;
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

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";
			$this->invoiceNo->TooltipValue = "";

			// invoiceDate
			$this->invoiceDate->LinkCustomAttributes = "";
			$this->invoiceDate->HrefValue = "";
			$this->invoiceDate->TooltipValue = "";

			// doNo
			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";
			$this->doNo->TooltipValue = "";

			// soNo
			$this->soNo->LinkCustomAttributes = "";
			$this->soNo->HrefValue = "";
			$this->soNo->TooltipValue = "";

			// paymentType
			$this->paymentType->LinkCustomAttributes = "";
			$this->paymentType->HrefValue = "";
			$this->paymentType->TooltipValue = "";

			// expiredPayment
			$this->expiredPayment->LinkCustomAttributes = "";
			$this->expiredPayment->HrefValue = "";
			$this->expiredPayment->TooltipValue = "";

			// ppnType
			$this->ppnType->LinkCustomAttributes = "";
			$this->ppnType->HrefValue = "";
			$this->ppnType->TooltipValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";
			$this->ppn->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// basic
			$this->basic->LinkCustomAttributes = "";
			$this->basic->HrefValue = "";
			$this->basic->TooltipValue = "";

			// discount
			$this->discount->LinkCustomAttributes = "";
			$this->discount->HrefValue = "";
			$this->discount->TooltipValue = "";

			// grandtotal
			$this->grandtotal->LinkCustomAttributes = "";
			$this->grandtotal->HrefValue = "";
			$this->grandtotal->TooltipValue = "";

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

			// invoiceNo
			$this->invoiceNo->EditAttrs["class"] = "form-control";
			$this->invoiceNo->EditCustomAttributes = "";
			$this->invoiceNo->EditValue = ew_HtmlEncode($this->invoiceNo->CurrentValue);
			$this->invoiceNo->PlaceHolder = ew_RemoveHtml($this->invoiceNo->FldCaption());

			// invoiceDate
			$this->invoiceDate->EditAttrs["class"] = "form-control";
			$this->invoiceDate->EditCustomAttributes = "";
			$this->invoiceDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->invoiceDate->CurrentValue, 8));
			$this->invoiceDate->PlaceHolder = ew_RemoveHtml($this->invoiceDate->FldCaption());

			// doNo
			$this->doNo->EditAttrs["class"] = "form-control";
			$this->doNo->EditCustomAttributes = "";
			$this->doNo->EditValue = ew_HtmlEncode($this->doNo->CurrentValue);
			$this->doNo->PlaceHolder = ew_RemoveHtml($this->doNo->FldCaption());

			// soNo
			$this->soNo->EditAttrs["class"] = "form-control";
			$this->soNo->EditCustomAttributes = "";
			$this->soNo->EditValue = ew_HtmlEncode($this->soNo->CurrentValue);
			$this->soNo->PlaceHolder = ew_RemoveHtml($this->soNo->FldCaption());

			// paymentType
			$this->paymentType->EditAttrs["class"] = "form-control";
			$this->paymentType->EditCustomAttributes = "";
			$this->paymentType->EditValue = ew_HtmlEncode($this->paymentType->CurrentValue);
			$this->paymentType->PlaceHolder = ew_RemoveHtml($this->paymentType->FldCaption());

			// expiredPayment
			$this->expiredPayment->EditAttrs["class"] = "form-control";
			$this->expiredPayment->EditCustomAttributes = "";
			$this->expiredPayment->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->expiredPayment->CurrentValue, 8));
			$this->expiredPayment->PlaceHolder = ew_RemoveHtml($this->expiredPayment->FldCaption());

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

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

			// basic
			$this->basic->EditAttrs["class"] = "form-control";
			$this->basic->EditCustomAttributes = "";
			$this->basic->EditValue = ew_HtmlEncode($this->basic->CurrentValue);
			$this->basic->PlaceHolder = ew_RemoveHtml($this->basic->FldCaption());
			if (strval($this->basic->EditValue) <> "" && is_numeric($this->basic->EditValue)) $this->basic->EditValue = ew_FormatNumber($this->basic->EditValue, -2, -1, -2, 0);

			// discount
			$this->discount->EditAttrs["class"] = "form-control";
			$this->discount->EditCustomAttributes = "";
			$this->discount->EditValue = ew_HtmlEncode($this->discount->CurrentValue);
			$this->discount->PlaceHolder = ew_RemoveHtml($this->discount->FldCaption());
			if (strval($this->discount->EditValue) <> "" && is_numeric($this->discount->EditValue)) $this->discount->EditValue = ew_FormatNumber($this->discount->EditValue, -2, -1, -2, 0);

			// grandtotal
			$this->grandtotal->EditAttrs["class"] = "form-control";
			$this->grandtotal->EditCustomAttributes = "";
			$this->grandtotal->EditValue = ew_HtmlEncode($this->grandtotal->CurrentValue);
			$this->grandtotal->PlaceHolder = ew_RemoveHtml($this->grandtotal->FldCaption());
			if (strval($this->grandtotal->EditValue) <> "" && is_numeric($this->grandtotal->EditValue)) $this->grandtotal->EditValue = ew_FormatNumber($this->grandtotal->EditValue, -2, -1, -2, 0);

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
			$this->createdDate->EditValue = ew_HtmlEncode($this->createdDate->CurrentValue);
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
			// invoiceNo

			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";

			// invoiceDate
			$this->invoiceDate->LinkCustomAttributes = "";
			$this->invoiceDate->HrefValue = "";

			// doNo
			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";

			// soNo
			$this->soNo->LinkCustomAttributes = "";
			$this->soNo->HrefValue = "";

			// paymentType
			$this->paymentType->LinkCustomAttributes = "";
			$this->paymentType->HrefValue = "";

			// expiredPayment
			$this->expiredPayment->LinkCustomAttributes = "";
			$this->expiredPayment->HrefValue = "";

			// ppnType
			$this->ppnType->LinkCustomAttributes = "";
			$this->ppnType->HrefValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

			// basic
			$this->basic->LinkCustomAttributes = "";
			$this->basic->HrefValue = "";

			// discount
			$this->discount->LinkCustomAttributes = "";
			$this->discount->HrefValue = "";

			// grandtotal
			$this->grandtotal->LinkCustomAttributes = "";
			$this->grandtotal->HrefValue = "";

			// customerID
			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";

			// customerAddress
			$this->customerAddress->LinkCustomAttributes = "";
			$this->customerAddress->HrefValue = "";

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
		if (!$this->invoiceNo->FldIsDetailKey && !is_null($this->invoiceNo->FormValue) && $this->invoiceNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoiceNo->FldCaption(), $this->invoiceNo->ReqErrMsg));
		}
		if (!$this->invoiceDate->FldIsDetailKey && !is_null($this->invoiceDate->FormValue) && $this->invoiceDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->invoiceDate->FldCaption(), $this->invoiceDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->invoiceDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->invoiceDate->FldErrMsg());
		}
		if (!$this->doNo->FldIsDetailKey && !is_null($this->doNo->FormValue) && $this->doNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->doNo->FldCaption(), $this->doNo->ReqErrMsg));
		}
		if (!$this->soNo->FldIsDetailKey && !is_null($this->soNo->FormValue) && $this->soNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->soNo->FldCaption(), $this->soNo->ReqErrMsg));
		}
		if (!$this->paymentType->FldIsDetailKey && !is_null($this->paymentType->FormValue) && $this->paymentType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->paymentType->FldCaption(), $this->paymentType->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->paymentType->FormValue)) {
			ew_AddMessage($gsFormError, $this->paymentType->FldErrMsg());
		}
		if (!$this->expiredPayment->FldIsDetailKey && !is_null($this->expiredPayment->FormValue) && $this->expiredPayment->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->expiredPayment->FldCaption(), $this->expiredPayment->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->expiredPayment->FormValue)) {
			ew_AddMessage($gsFormError, $this->expiredPayment->FldErrMsg());
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
		if (!$this->total->FldIsDetailKey && !is_null($this->total->FormValue) && $this->total->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total->FldCaption(), $this->total->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
		}
		if (!$this->basic->FldIsDetailKey && !is_null($this->basic->FormValue) && $this->basic->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->basic->FldCaption(), $this->basic->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->basic->FormValue)) {
			ew_AddMessage($gsFormError, $this->basic->FldErrMsg());
		}
		if (!$this->discount->FldIsDetailKey && !is_null($this->discount->FormValue) && $this->discount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->discount->FldCaption(), $this->discount->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->discount->FormValue)) {
			ew_AddMessage($gsFormError, $this->discount->FldErrMsg());
		}
		if (!$this->grandtotal->FldIsDetailKey && !is_null($this->grandtotal->FormValue) && $this->grandtotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grandtotal->FldCaption(), $this->grandtotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->grandtotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->grandtotal->FldErrMsg());
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
		if (!ew_CheckInteger($this->createdDate->FormValue)) {
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

		// invoiceNo
		$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", FALSE);

		// invoiceDate
		$this->invoiceDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// doNo
		$this->doNo->SetDbValueDef($rsnew, $this->doNo->CurrentValue, "", FALSE);

		// soNo
		$this->soNo->SetDbValueDef($rsnew, $this->soNo->CurrentValue, "", FALSE);

		// paymentType
		$this->paymentType->SetDbValueDef($rsnew, $this->paymentType->CurrentValue, 0, FALSE);

		// expiredPayment
		$this->expiredPayment->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->expiredPayment->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// ppnType
		$this->ppnType->SetDbValueDef($rsnew, $this->ppnType->CurrentValue, 0, FALSE);

		// ppn
		$this->ppn->SetDbValueDef($rsnew, $this->ppn->CurrentValue, 0, FALSE);

		// total
		$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, 0, FALSE);

		// basic
		$this->basic->SetDbValueDef($rsnew, $this->basic->CurrentValue, 0, FALSE);

		// discount
		$this->discount->SetDbValueDef($rsnew, $this->discount->CurrentValue, 0, FALSE);

		// grandtotal
		$this->grandtotal->SetDbValueDef($rsnew, $this->grandtotal->CurrentValue, 0, FALSE);

		// customerID
		$this->customerID->SetDbValueDef($rsnew, $this->customerID->CurrentValue, 0, FALSE);

		// customerName
		$this->customerName->SetDbValueDef($rsnew, $this->customerName->CurrentValue, "", FALSE);

		// customerAddress
		$this->customerAddress->SetDbValueDef($rsnew, $this->customerAddress->CurrentValue, "", FALSE);

		// staffID
		$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// createdDate
		$this->createdDate->SetDbValueDef($rsnew, $this->createdDate->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_sales_transactionslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_sales_transactions_add)) $as_sales_transactions_add = new cas_sales_transactions_add();

// Page init
$as_sales_transactions_add->Page_Init();

// Page main
$as_sales_transactions_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_sales_transactions_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_sales_transactionsadd = new ew_Form("fas_sales_transactionsadd", "add");

// Validate form
fas_sales_transactionsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_invoiceNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->invoiceNo->FldCaption(), $as_sales_transactions->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->invoiceDate->FldCaption(), $as_sales_transactions->invoiceDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->invoiceDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_doNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->doNo->FldCaption(), $as_sales_transactions->doNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_soNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->soNo->FldCaption(), $as_sales_transactions->soNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->paymentType->FldCaption(), $as_sales_transactions->paymentType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentType");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->paymentType->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_expiredPayment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->expiredPayment->FldCaption(), $as_sales_transactions->expiredPayment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_expiredPayment");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->expiredPayment->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->ppnType->FldCaption(), $as_sales_transactions->ppnType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->ppnType->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->ppn->FldCaption(), $as_sales_transactions->ppn->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->ppn->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->total->FldCaption(), $as_sales_transactions->total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_basic");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->basic->FldCaption(), $as_sales_transactions->basic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_basic");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->basic->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_discount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->discount->FldCaption(), $as_sales_transactions->discount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_discount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->discount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->grandtotal->FldCaption(), $as_sales_transactions->grandtotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->grandtotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->customerID->FldCaption(), $as_sales_transactions->customerID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->customerID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->customerName->FldCaption(), $as_sales_transactions->customerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->customerAddress->FldCaption(), $as_sales_transactions->customerAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->staffID->FldCaption(), $as_sales_transactions->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->staffName->FldCaption(), $as_sales_transactions->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->createdDate->FldCaption(), $as_sales_transactions->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->createdUserID->FldCaption(), $as_sales_transactions->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->modifiedDate->FldCaption(), $as_sales_transactions->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_sales_transactions->modifiedUserID->FldCaption(), $as_sales_transactions->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_sales_transactions->modifiedUserID->FldErrMsg()) ?>");

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
fas_sales_transactionsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_sales_transactionsadd.ValidateRequired = true;
<?php } else { ?>
fas_sales_transactionsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_sales_transactions_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_sales_transactions_add->ShowPageHeader(); ?>
<?php
$as_sales_transactions_add->ShowMessage();
?>
<form name="fas_sales_transactionsadd" id="fas_sales_transactionsadd" class="<?php echo $as_sales_transactions_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_sales_transactions_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_sales_transactions_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_sales_transactions">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_sales_transactions_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_sales_transactions->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_sales_transactions_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->invoiceNo->CellAttributes() ?>>
<span id="el_as_sales_transactions_invoiceNo">
<input type="text" data-table="as_sales_transactions" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->invoiceNo->EditValue ?>"<?php echo $as_sales_transactions->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->invoiceDate->Visible) { // invoiceDate ?>
	<div id="r_invoiceDate" class="form-group">
		<label id="elh_as_sales_transactions_invoiceDate" for="x_invoiceDate" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->invoiceDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->invoiceDate->CellAttributes() ?>>
<span id="el_as_sales_transactions_invoiceDate">
<input type="text" data-table="as_sales_transactions" data-field="x_invoiceDate" name="x_invoiceDate" id="x_invoiceDate" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->invoiceDate->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->invoiceDate->EditValue ?>"<?php echo $as_sales_transactions->invoiceDate->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->invoiceDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->doNo->Visible) { // doNo ?>
	<div id="r_doNo" class="form-group">
		<label id="elh_as_sales_transactions_doNo" for="x_doNo" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->doNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->doNo->CellAttributes() ?>>
<span id="el_as_sales_transactions_doNo">
<input type="text" data-table="as_sales_transactions" data-field="x_doNo" name="x_doNo" id="x_doNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->doNo->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->doNo->EditValue ?>"<?php echo $as_sales_transactions->doNo->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->doNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->soNo->Visible) { // soNo ?>
	<div id="r_soNo" class="form-group">
		<label id="elh_as_sales_transactions_soNo" for="x_soNo" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->soNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->soNo->CellAttributes() ?>>
<span id="el_as_sales_transactions_soNo">
<input type="text" data-table="as_sales_transactions" data-field="x_soNo" name="x_soNo" id="x_soNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->soNo->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->soNo->EditValue ?>"<?php echo $as_sales_transactions->soNo->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->soNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->paymentType->Visible) { // paymentType ?>
	<div id="r_paymentType" class="form-group">
		<label id="elh_as_sales_transactions_paymentType" for="x_paymentType" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->paymentType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->paymentType->CellAttributes() ?>>
<span id="el_as_sales_transactions_paymentType">
<input type="text" data-table="as_sales_transactions" data-field="x_paymentType" name="x_paymentType" id="x_paymentType" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->paymentType->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->paymentType->EditValue ?>"<?php echo $as_sales_transactions->paymentType->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->paymentType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->expiredPayment->Visible) { // expiredPayment ?>
	<div id="r_expiredPayment" class="form-group">
		<label id="elh_as_sales_transactions_expiredPayment" for="x_expiredPayment" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->expiredPayment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->expiredPayment->CellAttributes() ?>>
<span id="el_as_sales_transactions_expiredPayment">
<input type="text" data-table="as_sales_transactions" data-field="x_expiredPayment" name="x_expiredPayment" id="x_expiredPayment" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->expiredPayment->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->expiredPayment->EditValue ?>"<?php echo $as_sales_transactions->expiredPayment->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->expiredPayment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->ppnType->Visible) { // ppnType ?>
	<div id="r_ppnType" class="form-group">
		<label id="elh_as_sales_transactions_ppnType" for="x_ppnType" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->ppnType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->ppnType->CellAttributes() ?>>
<span id="el_as_sales_transactions_ppnType">
<input type="text" data-table="as_sales_transactions" data-field="x_ppnType" name="x_ppnType" id="x_ppnType" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->ppnType->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->ppnType->EditValue ?>"<?php echo $as_sales_transactions->ppnType->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->ppnType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->ppn->Visible) { // ppn ?>
	<div id="r_ppn" class="form-group">
		<label id="elh_as_sales_transactions_ppn" for="x_ppn" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->ppn->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->ppn->CellAttributes() ?>>
<span id="el_as_sales_transactions_ppn">
<input type="text" data-table="as_sales_transactions" data-field="x_ppn" name="x_ppn" id="x_ppn" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->ppn->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->ppn->EditValue ?>"<?php echo $as_sales_transactions->ppn->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->ppn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_as_sales_transactions_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->total->CellAttributes() ?>>
<span id="el_as_sales_transactions_total">
<input type="text" data-table="as_sales_transactions" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->total->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->total->EditValue ?>"<?php echo $as_sales_transactions->total->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->basic->Visible) { // basic ?>
	<div id="r_basic" class="form-group">
		<label id="elh_as_sales_transactions_basic" for="x_basic" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->basic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->basic->CellAttributes() ?>>
<span id="el_as_sales_transactions_basic">
<input type="text" data-table="as_sales_transactions" data-field="x_basic" name="x_basic" id="x_basic" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->basic->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->basic->EditValue ?>"<?php echo $as_sales_transactions->basic->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->basic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->discount->Visible) { // discount ?>
	<div id="r_discount" class="form-group">
		<label id="elh_as_sales_transactions_discount" for="x_discount" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->discount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->discount->CellAttributes() ?>>
<span id="el_as_sales_transactions_discount">
<input type="text" data-table="as_sales_transactions" data-field="x_discount" name="x_discount" id="x_discount" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->discount->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->discount->EditValue ?>"<?php echo $as_sales_transactions->discount->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->discount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->grandtotal->Visible) { // grandtotal ?>
	<div id="r_grandtotal" class="form-group">
		<label id="elh_as_sales_transactions_grandtotal" for="x_grandtotal" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->grandtotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->grandtotal->CellAttributes() ?>>
<span id="el_as_sales_transactions_grandtotal">
<input type="text" data-table="as_sales_transactions" data-field="x_grandtotal" name="x_grandtotal" id="x_grandtotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->grandtotal->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->grandtotal->EditValue ?>"<?php echo $as_sales_transactions->grandtotal->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->grandtotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->customerID->Visible) { // customerID ?>
	<div id="r_customerID" class="form-group">
		<label id="elh_as_sales_transactions_customerID" for="x_customerID" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->customerID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->customerID->CellAttributes() ?>>
<span id="el_as_sales_transactions_customerID">
<input type="text" data-table="as_sales_transactions" data-field="x_customerID" name="x_customerID" id="x_customerID" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->customerID->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->customerID->EditValue ?>"<?php echo $as_sales_transactions->customerID->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->customerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->customerName->Visible) { // customerName ?>
	<div id="r_customerName" class="form-group">
		<label id="elh_as_sales_transactions_customerName" for="x_customerName" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->customerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->customerName->CellAttributes() ?>>
<span id="el_as_sales_transactions_customerName">
<input type="text" data-table="as_sales_transactions" data-field="x_customerName" name="x_customerName" id="x_customerName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->customerName->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->customerName->EditValue ?>"<?php echo $as_sales_transactions->customerName->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->customerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->customerAddress->Visible) { // customerAddress ?>
	<div id="r_customerAddress" class="form-group">
		<label id="elh_as_sales_transactions_customerAddress" for="x_customerAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->customerAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->customerAddress->CellAttributes() ?>>
<span id="el_as_sales_transactions_customerAddress">
<textarea data-table="as_sales_transactions" data-field="x_customerAddress" name="x_customerAddress" id="x_customerAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->customerAddress->getPlaceHolder()) ?>"<?php echo $as_sales_transactions->customerAddress->EditAttributes() ?>><?php echo $as_sales_transactions->customerAddress->EditValue ?></textarea>
</span>
<?php echo $as_sales_transactions->customerAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_sales_transactions_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->staffID->CellAttributes() ?>>
<span id="el_as_sales_transactions_staffID">
<input type="text" data-table="as_sales_transactions" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->staffID->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->staffID->EditValue ?>"<?php echo $as_sales_transactions->staffID->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_sales_transactions_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->staffName->CellAttributes() ?>>
<span id="el_as_sales_transactions_staffName">
<input type="text" data-table="as_sales_transactions" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->staffName->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->staffName->EditValue ?>"<?php echo $as_sales_transactions->staffName->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_sales_transactions_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->createdDate->CellAttributes() ?>>
<span id="el_as_sales_transactions_createdDate">
<input type="text" data-table="as_sales_transactions" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->createdDate->EditValue ?>"<?php echo $as_sales_transactions->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_sales_transactions_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->createdUserID->CellAttributes() ?>>
<span id="el_as_sales_transactions_createdUserID">
<input type="text" data-table="as_sales_transactions" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->createdUserID->EditValue ?>"<?php echo $as_sales_transactions->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_sales_transactions_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->modifiedDate->CellAttributes() ?>>
<span id="el_as_sales_transactions_modifiedDate">
<input type="text" data-table="as_sales_transactions" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->modifiedDate->EditValue ?>"<?php echo $as_sales_transactions->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_sales_transactions->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_sales_transactions_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_sales_transactions->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_sales_transactions->modifiedUserID->CellAttributes() ?>>
<span id="el_as_sales_transactions_modifiedUserID">
<input type="text" data-table="as_sales_transactions" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_sales_transactions->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_sales_transactions->modifiedUserID->EditValue ?>"<?php echo $as_sales_transactions->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_sales_transactions->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_sales_transactions_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_sales_transactions_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_sales_transactionsadd.Init();
</script>
<?php
$as_sales_transactions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_sales_transactions_add->Page_Terminate();
?>
