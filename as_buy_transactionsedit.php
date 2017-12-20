<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_buy_transactionsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_buy_transactions_edit = NULL; // Initialize page object first

class cas_buy_transactions_edit extends cas_buy_transactions {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_buy_transactions';

	// Page object name
	var $PageObjName = 'as_buy_transactions_edit';

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

		// Table object (as_buy_transactions)
		if (!isset($GLOBALS["as_buy_transactions"]) || get_class($GLOBALS["as_buy_transactions"]) == "cas_buy_transactions") {
			$GLOBALS["as_buy_transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_buy_transactions"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_buy_transactions', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("as_buy_transactionslist.php"));
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
		$this->invoiceID->SetVisibility();
		$this->invoiceID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->invoiceNo->SetVisibility();
		$this->invoiceDate->SetVisibility();
		$this->bbmNo->SetVisibility();
		$this->spbNo->SetVisibility();
		$this->paymentType->SetVisibility();
		$this->expiredPayment->SetVisibility();
		$this->ppnType->SetVisibility();
		$this->ppn->SetVisibility();
		$this->total->SetVisibility();
		$this->basic->SetVisibility();
		$this->discount->SetVisibility();
		$this->grandtotal->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
		$this->supplierAddress->SetVisibility();
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
		global $EW_EXPORT, $as_buy_transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_buy_transactions);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

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

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["invoiceID"] <> "") {
			$this->invoiceID->setQueryStringValue($_GET["invoiceID"]);
			$this->RecKey["invoiceID"] = $this->invoiceID->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("as_buy_transactionslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->invoiceID->CurrentValue) == strval($this->Recordset->fields('invoiceID'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("as_buy_transactionslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_buy_transactionslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->invoiceID->FldIsDetailKey)
			$this->invoiceID->setFormValue($objForm->GetValue("x_invoiceID"));
		if (!$this->invoiceNo->FldIsDetailKey) {
			$this->invoiceNo->setFormValue($objForm->GetValue("x_invoiceNo"));
		}
		if (!$this->invoiceDate->FldIsDetailKey) {
			$this->invoiceDate->setFormValue($objForm->GetValue("x_invoiceDate"));
			$this->invoiceDate->CurrentValue = ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0);
		}
		if (!$this->bbmNo->FldIsDetailKey) {
			$this->bbmNo->setFormValue($objForm->GetValue("x_bbmNo"));
		}
		if (!$this->spbNo->FldIsDetailKey) {
			$this->spbNo->setFormValue($objForm->GetValue("x_spbNo"));
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
		if (!$this->supplierID->FldIsDetailKey) {
			$this->supplierID->setFormValue($objForm->GetValue("x_supplierID"));
		}
		if (!$this->supplierName->FldIsDetailKey) {
			$this->supplierName->setFormValue($objForm->GetValue("x_supplierName"));
		}
		if (!$this->supplierAddress->FldIsDetailKey) {
			$this->supplierAddress->setFormValue($objForm->GetValue("x_supplierAddress"));
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
		$this->LoadRow();
		$this->invoiceID->CurrentValue = $this->invoiceID->FormValue;
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->invoiceDate->CurrentValue = $this->invoiceDate->FormValue;
		$this->invoiceDate->CurrentValue = ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0);
		$this->bbmNo->CurrentValue = $this->bbmNo->FormValue;
		$this->spbNo->CurrentValue = $this->spbNo->FormValue;
		$this->paymentType->CurrentValue = $this->paymentType->FormValue;
		$this->expiredPayment->CurrentValue = $this->expiredPayment->FormValue;
		$this->expiredPayment->CurrentValue = ew_UnFormatDateTime($this->expiredPayment->CurrentValue, 0);
		$this->ppnType->CurrentValue = $this->ppnType->FormValue;
		$this->ppn->CurrentValue = $this->ppn->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->basic->CurrentValue = $this->basic->FormValue;
		$this->discount->CurrentValue = $this->discount->FormValue;
		$this->grandtotal->CurrentValue = $this->grandtotal->FormValue;
		$this->supplierID->CurrentValue = $this->supplierID->FormValue;
		$this->supplierName->CurrentValue = $this->supplierName->FormValue;
		$this->supplierAddress->CurrentValue = $this->supplierAddress->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->createdDate->CurrentValue = $this->createdDate->FormValue;
		$this->createdDate->CurrentValue = ew_UnFormatDateTime($this->createdDate->CurrentValue, 0);
		$this->createdUserID->CurrentValue = $this->createdUserID->FormValue;
		$this->modifiedDate->CurrentValue = $this->modifiedDate->FormValue;
		$this->modifiedDate->CurrentValue = ew_UnFormatDateTime($this->modifiedDate->CurrentValue, 0);
		$this->modifiedUserID->CurrentValue = $this->modifiedUserID->FormValue;
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
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->invoiceDate->setDbValue($rs->fields('invoiceDate'));
		$this->bbmNo->setDbValue($rs->fields('bbmNo'));
		$this->spbNo->setDbValue($rs->fields('spbNo'));
		$this->paymentType->setDbValue($rs->fields('paymentType'));
		$this->expiredPayment->setDbValue($rs->fields('expiredPayment'));
		$this->ppnType->setDbValue($rs->fields('ppnType'));
		$this->ppn->setDbValue($rs->fields('ppn'));
		$this->total->setDbValue($rs->fields('total'));
		$this->basic->setDbValue($rs->fields('basic'));
		$this->discount->setDbValue($rs->fields('discount'));
		$this->grandtotal->setDbValue($rs->fields('grandtotal'));
		$this->supplierID->setDbValue($rs->fields('supplierID'));
		$this->supplierName->setDbValue($rs->fields('supplierName'));
		$this->supplierAddress->setDbValue($rs->fields('supplierAddress'));
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
		$this->bbmNo->DbValue = $row['bbmNo'];
		$this->spbNo->DbValue = $row['spbNo'];
		$this->paymentType->DbValue = $row['paymentType'];
		$this->expiredPayment->DbValue = $row['expiredPayment'];
		$this->ppnType->DbValue = $row['ppnType'];
		$this->ppn->DbValue = $row['ppn'];
		$this->total->DbValue = $row['total'];
		$this->basic->DbValue = $row['basic'];
		$this->discount->DbValue = $row['discount'];
		$this->grandtotal->DbValue = $row['grandtotal'];
		$this->supplierID->DbValue = $row['supplierID'];
		$this->supplierName->DbValue = $row['supplierName'];
		$this->supplierAddress->DbValue = $row['supplierAddress'];
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
		// bbmNo
		// spbNo
		// paymentType
		// expiredPayment
		// ppnType
		// ppn
		// total
		// basic
		// discount
		// grandtotal
		// supplierID
		// supplierName
		// supplierAddress
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

		// bbmNo
		$this->bbmNo->ViewValue = $this->bbmNo->CurrentValue;
		$this->bbmNo->ViewCustomAttributes = "";

		// spbNo
		$this->spbNo->ViewValue = $this->spbNo->CurrentValue;
		$this->spbNo->ViewCustomAttributes = "";

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

		// supplierID
		$this->supplierID->ViewValue = $this->supplierID->CurrentValue;
		$this->supplierID->ViewCustomAttributes = "";

		// supplierName
		$this->supplierName->ViewValue = $this->supplierName->CurrentValue;
		$this->supplierName->ViewCustomAttributes = "";

		// supplierAddress
		$this->supplierAddress->ViewValue = $this->supplierAddress->CurrentValue;
		$this->supplierAddress->ViewCustomAttributes = "";

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

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";
			$this->invoiceID->TooltipValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";
			$this->invoiceNo->TooltipValue = "";

			// invoiceDate
			$this->invoiceDate->LinkCustomAttributes = "";
			$this->invoiceDate->HrefValue = "";
			$this->invoiceDate->TooltipValue = "";

			// bbmNo
			$this->bbmNo->LinkCustomAttributes = "";
			$this->bbmNo->HrefValue = "";
			$this->bbmNo->TooltipValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";
			$this->spbNo->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// invoiceID
			$this->invoiceID->EditAttrs["class"] = "form-control";
			$this->invoiceID->EditCustomAttributes = "";
			$this->invoiceID->EditValue = $this->invoiceID->CurrentValue;
			$this->invoiceID->ViewCustomAttributes = "";

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

			// bbmNo
			$this->bbmNo->EditAttrs["class"] = "form-control";
			$this->bbmNo->EditCustomAttributes = "";
			$this->bbmNo->EditValue = ew_HtmlEncode($this->bbmNo->CurrentValue);
			$this->bbmNo->PlaceHolder = ew_RemoveHtml($this->bbmNo->FldCaption());

			// spbNo
			$this->spbNo->EditAttrs["class"] = "form-control";
			$this->spbNo->EditCustomAttributes = "";
			$this->spbNo->EditValue = ew_HtmlEncode($this->spbNo->CurrentValue);
			$this->spbNo->PlaceHolder = ew_RemoveHtml($this->spbNo->FldCaption());

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

			// Edit refer script
			// invoiceID

			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";

			// invoiceDate
			$this->invoiceDate->LinkCustomAttributes = "";
			$this->invoiceDate->HrefValue = "";

			// bbmNo
			$this->bbmNo->LinkCustomAttributes = "";
			$this->bbmNo->HrefValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";

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

			// supplierID
			$this->supplierID->LinkCustomAttributes = "";
			$this->supplierID->HrefValue = "";

			// supplierName
			$this->supplierName->LinkCustomAttributes = "";
			$this->supplierName->HrefValue = "";

			// supplierAddress
			$this->supplierAddress->LinkCustomAttributes = "";
			$this->supplierAddress->HrefValue = "";

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
		if (!$this->bbmNo->FldIsDetailKey && !is_null($this->bbmNo->FormValue) && $this->bbmNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bbmNo->FldCaption(), $this->bbmNo->ReqErrMsg));
		}
		if (!$this->spbNo->FldIsDetailKey && !is_null($this->spbNo->FormValue) && $this->spbNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbNo->FldCaption(), $this->spbNo->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// invoiceNo
			$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", $this->invoiceNo->ReadOnly);

			// invoiceDate
			$this->invoiceDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->invoiceDate->CurrentValue, 0), ew_CurrentDate(), $this->invoiceDate->ReadOnly);

			// bbmNo
			$this->bbmNo->SetDbValueDef($rsnew, $this->bbmNo->CurrentValue, "", $this->bbmNo->ReadOnly);

			// spbNo
			$this->spbNo->SetDbValueDef($rsnew, $this->spbNo->CurrentValue, "", $this->spbNo->ReadOnly);

			// paymentType
			$this->paymentType->SetDbValueDef($rsnew, $this->paymentType->CurrentValue, 0, $this->paymentType->ReadOnly);

			// expiredPayment
			$this->expiredPayment->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->expiredPayment->CurrentValue, 0), ew_CurrentDate(), $this->expiredPayment->ReadOnly);

			// ppnType
			$this->ppnType->SetDbValueDef($rsnew, $this->ppnType->CurrentValue, 0, $this->ppnType->ReadOnly);

			// ppn
			$this->ppn->SetDbValueDef($rsnew, $this->ppn->CurrentValue, 0, $this->ppn->ReadOnly);

			// total
			$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, 0, $this->total->ReadOnly);

			// basic
			$this->basic->SetDbValueDef($rsnew, $this->basic->CurrentValue, 0, $this->basic->ReadOnly);

			// discount
			$this->discount->SetDbValueDef($rsnew, $this->discount->CurrentValue, 0, $this->discount->ReadOnly);

			// grandtotal
			$this->grandtotal->SetDbValueDef($rsnew, $this->grandtotal->CurrentValue, 0, $this->grandtotal->ReadOnly);

			// supplierID
			$this->supplierID->SetDbValueDef($rsnew, $this->supplierID->CurrentValue, 0, $this->supplierID->ReadOnly);

			// supplierName
			$this->supplierName->SetDbValueDef($rsnew, $this->supplierName->CurrentValue, "", $this->supplierName->ReadOnly);

			// supplierAddress
			$this->supplierAddress->SetDbValueDef($rsnew, $this->supplierAddress->CurrentValue, "", $this->supplierAddress->ReadOnly);

			// staffID
			$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, $this->staffID->ReadOnly);

			// staffName
			$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", $this->staffName->ReadOnly);

			// createdDate
			$this->createdDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->createdDate->CurrentValue, 0), ew_CurrentDate(), $this->createdDate->ReadOnly);

			// createdUserID
			$this->createdUserID->SetDbValueDef($rsnew, $this->createdUserID->CurrentValue, 0, $this->createdUserID->ReadOnly);

			// modifiedDate
			$this->modifiedDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modifiedDate->CurrentValue, 0), ew_CurrentDate(), $this->modifiedDate->ReadOnly);

			// modifiedUserID
			$this->modifiedUserID->SetDbValueDef($rsnew, $this->modifiedUserID->CurrentValue, 0, $this->modifiedUserID->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_buy_transactionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($as_buy_transactions_edit)) $as_buy_transactions_edit = new cas_buy_transactions_edit();

// Page init
$as_buy_transactions_edit->Page_Init();

// Page main
$as_buy_transactions_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_buy_transactions_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_buy_transactionsedit = new ew_Form("fas_buy_transactionsedit", "edit");

// Validate form
fas_buy_transactionsedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->invoiceNo->FldCaption(), $as_buy_transactions->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->invoiceDate->FldCaption(), $as_buy_transactions->invoiceDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->invoiceDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bbmNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->bbmNo->FldCaption(), $as_buy_transactions->bbmNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spbNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->spbNo->FldCaption(), $as_buy_transactions->spbNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->paymentType->FldCaption(), $as_buy_transactions->paymentType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentType");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->paymentType->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_expiredPayment");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->expiredPayment->FldCaption(), $as_buy_transactions->expiredPayment->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_expiredPayment");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->expiredPayment->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->ppnType->FldCaption(), $as_buy_transactions->ppnType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppnType");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->ppnType->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->ppn->FldCaption(), $as_buy_transactions->ppn->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->ppn->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->total->FldCaption(), $as_buy_transactions->total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_basic");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->basic->FldCaption(), $as_buy_transactions->basic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_basic");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->basic->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_discount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->discount->FldCaption(), $as_buy_transactions->discount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_discount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->discount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->grandtotal->FldCaption(), $as_buy_transactions->grandtotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grandtotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->grandtotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->supplierID->FldCaption(), $as_buy_transactions->supplierID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->supplierID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->supplierName->FldCaption(), $as_buy_transactions->supplierName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->supplierAddress->FldCaption(), $as_buy_transactions->supplierAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->staffID->FldCaption(), $as_buy_transactions->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->staffName->FldCaption(), $as_buy_transactions->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->createdDate->FldCaption(), $as_buy_transactions->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->createdUserID->FldCaption(), $as_buy_transactions->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->modifiedDate->FldCaption(), $as_buy_transactions->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_transactions->modifiedUserID->FldCaption(), $as_buy_transactions->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_transactions->modifiedUserID->FldErrMsg()) ?>");

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
fas_buy_transactionsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_buy_transactionsedit.ValidateRequired = true;
<?php } else { ?>
fas_buy_transactionsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_buy_transactions_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_buy_transactions_edit->ShowPageHeader(); ?>
<?php
$as_buy_transactions_edit->ShowMessage();
?>
<?php if (!$as_buy_transactions_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_buy_transactions_edit->Pager)) $as_buy_transactions_edit->Pager = new cPrevNextPager($as_buy_transactions_edit->StartRec, $as_buy_transactions_edit->DisplayRecs, $as_buy_transactions_edit->TotalRecs) ?>
<?php if ($as_buy_transactions_edit->Pager->RecordCount > 0 && $as_buy_transactions_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_buy_transactions_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_buy_transactions_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_buy_transactions_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_buy_transactions_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_buy_transactions_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_buy_transactions_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_buy_transactionsedit" id="fas_buy_transactionsedit" class="<?php echo $as_buy_transactions_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_buy_transactions_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_buy_transactions_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_buy_transactions">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_buy_transactions_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_buy_transactions->invoiceID->Visible) { // invoiceID ?>
	<div id="r_invoiceID" class="form-group">
		<label id="elh_as_buy_transactions_invoiceID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->invoiceID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->invoiceID->CellAttributes() ?>>
<span id="el_as_buy_transactions_invoiceID">
<span<?php echo $as_buy_transactions->invoiceID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_buy_transactions->invoiceID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_buy_transactions" data-field="x_invoiceID" name="x_invoiceID" id="x_invoiceID" value="<?php echo ew_HtmlEncode($as_buy_transactions->invoiceID->CurrentValue) ?>">
<?php echo $as_buy_transactions->invoiceID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_buy_transactions_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->invoiceNo->CellAttributes() ?>>
<span id="el_as_buy_transactions_invoiceNo">
<input type="text" data-table="as_buy_transactions" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->invoiceNo->EditValue ?>"<?php echo $as_buy_transactions->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->invoiceDate->Visible) { // invoiceDate ?>
	<div id="r_invoiceDate" class="form-group">
		<label id="elh_as_buy_transactions_invoiceDate" for="x_invoiceDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->invoiceDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->invoiceDate->CellAttributes() ?>>
<span id="el_as_buy_transactions_invoiceDate">
<input type="text" data-table="as_buy_transactions" data-field="x_invoiceDate" name="x_invoiceDate" id="x_invoiceDate" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->invoiceDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->invoiceDate->EditValue ?>"<?php echo $as_buy_transactions->invoiceDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->invoiceDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->bbmNo->Visible) { // bbmNo ?>
	<div id="r_bbmNo" class="form-group">
		<label id="elh_as_buy_transactions_bbmNo" for="x_bbmNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->bbmNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->bbmNo->CellAttributes() ?>>
<span id="el_as_buy_transactions_bbmNo">
<input type="text" data-table="as_buy_transactions" data-field="x_bbmNo" name="x_bbmNo" id="x_bbmNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->bbmNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->bbmNo->EditValue ?>"<?php echo $as_buy_transactions->bbmNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->bbmNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->spbNo->Visible) { // spbNo ?>
	<div id="r_spbNo" class="form-group">
		<label id="elh_as_buy_transactions_spbNo" for="x_spbNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->spbNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->spbNo->CellAttributes() ?>>
<span id="el_as_buy_transactions_spbNo">
<input type="text" data-table="as_buy_transactions" data-field="x_spbNo" name="x_spbNo" id="x_spbNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->spbNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->spbNo->EditValue ?>"<?php echo $as_buy_transactions->spbNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->spbNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->paymentType->Visible) { // paymentType ?>
	<div id="r_paymentType" class="form-group">
		<label id="elh_as_buy_transactions_paymentType" for="x_paymentType" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->paymentType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->paymentType->CellAttributes() ?>>
<span id="el_as_buy_transactions_paymentType">
<input type="text" data-table="as_buy_transactions" data-field="x_paymentType" name="x_paymentType" id="x_paymentType" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->paymentType->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->paymentType->EditValue ?>"<?php echo $as_buy_transactions->paymentType->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->paymentType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->expiredPayment->Visible) { // expiredPayment ?>
	<div id="r_expiredPayment" class="form-group">
		<label id="elh_as_buy_transactions_expiredPayment" for="x_expiredPayment" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->expiredPayment->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->expiredPayment->CellAttributes() ?>>
<span id="el_as_buy_transactions_expiredPayment">
<input type="text" data-table="as_buy_transactions" data-field="x_expiredPayment" name="x_expiredPayment" id="x_expiredPayment" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->expiredPayment->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->expiredPayment->EditValue ?>"<?php echo $as_buy_transactions->expiredPayment->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->expiredPayment->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->ppnType->Visible) { // ppnType ?>
	<div id="r_ppnType" class="form-group">
		<label id="elh_as_buy_transactions_ppnType" for="x_ppnType" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->ppnType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->ppnType->CellAttributes() ?>>
<span id="el_as_buy_transactions_ppnType">
<input type="text" data-table="as_buy_transactions" data-field="x_ppnType" name="x_ppnType" id="x_ppnType" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->ppnType->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->ppnType->EditValue ?>"<?php echo $as_buy_transactions->ppnType->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->ppnType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->ppn->Visible) { // ppn ?>
	<div id="r_ppn" class="form-group">
		<label id="elh_as_buy_transactions_ppn" for="x_ppn" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->ppn->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->ppn->CellAttributes() ?>>
<span id="el_as_buy_transactions_ppn">
<input type="text" data-table="as_buy_transactions" data-field="x_ppn" name="x_ppn" id="x_ppn" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->ppn->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->ppn->EditValue ?>"<?php echo $as_buy_transactions->ppn->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->ppn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_as_buy_transactions_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->total->CellAttributes() ?>>
<span id="el_as_buy_transactions_total">
<input type="text" data-table="as_buy_transactions" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->total->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->total->EditValue ?>"<?php echo $as_buy_transactions->total->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->basic->Visible) { // basic ?>
	<div id="r_basic" class="form-group">
		<label id="elh_as_buy_transactions_basic" for="x_basic" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->basic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->basic->CellAttributes() ?>>
<span id="el_as_buy_transactions_basic">
<input type="text" data-table="as_buy_transactions" data-field="x_basic" name="x_basic" id="x_basic" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->basic->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->basic->EditValue ?>"<?php echo $as_buy_transactions->basic->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->basic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->discount->Visible) { // discount ?>
	<div id="r_discount" class="form-group">
		<label id="elh_as_buy_transactions_discount" for="x_discount" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->discount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->discount->CellAttributes() ?>>
<span id="el_as_buy_transactions_discount">
<input type="text" data-table="as_buy_transactions" data-field="x_discount" name="x_discount" id="x_discount" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->discount->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->discount->EditValue ?>"<?php echo $as_buy_transactions->discount->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->discount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->grandtotal->Visible) { // grandtotal ?>
	<div id="r_grandtotal" class="form-group">
		<label id="elh_as_buy_transactions_grandtotal" for="x_grandtotal" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->grandtotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->grandtotal->CellAttributes() ?>>
<span id="el_as_buy_transactions_grandtotal">
<input type="text" data-table="as_buy_transactions" data-field="x_grandtotal" name="x_grandtotal" id="x_grandtotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->grandtotal->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->grandtotal->EditValue ?>"<?php echo $as_buy_transactions->grandtotal->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->grandtotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->supplierID->Visible) { // supplierID ?>
	<div id="r_supplierID" class="form-group">
		<label id="elh_as_buy_transactions_supplierID" for="x_supplierID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->supplierID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->supplierID->CellAttributes() ?>>
<span id="el_as_buy_transactions_supplierID">
<input type="text" data-table="as_buy_transactions" data-field="x_supplierID" name="x_supplierID" id="x_supplierID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->supplierID->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->supplierID->EditValue ?>"<?php echo $as_buy_transactions->supplierID->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->supplierID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->supplierName->Visible) { // supplierName ?>
	<div id="r_supplierName" class="form-group">
		<label id="elh_as_buy_transactions_supplierName" for="x_supplierName" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->supplierName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->supplierName->CellAttributes() ?>>
<span id="el_as_buy_transactions_supplierName">
<input type="text" data-table="as_buy_transactions" data-field="x_supplierName" name="x_supplierName" id="x_supplierName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->supplierName->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->supplierName->EditValue ?>"<?php echo $as_buy_transactions->supplierName->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->supplierName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->supplierAddress->Visible) { // supplierAddress ?>
	<div id="r_supplierAddress" class="form-group">
		<label id="elh_as_buy_transactions_supplierAddress" for="x_supplierAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->supplierAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->supplierAddress->CellAttributes() ?>>
<span id="el_as_buy_transactions_supplierAddress">
<textarea data-table="as_buy_transactions" data-field="x_supplierAddress" name="x_supplierAddress" id="x_supplierAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->supplierAddress->getPlaceHolder()) ?>"<?php echo $as_buy_transactions->supplierAddress->EditAttributes() ?>><?php echo $as_buy_transactions->supplierAddress->EditValue ?></textarea>
</span>
<?php echo $as_buy_transactions->supplierAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_buy_transactions_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->staffID->CellAttributes() ?>>
<span id="el_as_buy_transactions_staffID">
<input type="text" data-table="as_buy_transactions" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->staffID->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->staffID->EditValue ?>"<?php echo $as_buy_transactions->staffID->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_buy_transactions_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->staffName->CellAttributes() ?>>
<span id="el_as_buy_transactions_staffName">
<input type="text" data-table="as_buy_transactions" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->staffName->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->staffName->EditValue ?>"<?php echo $as_buy_transactions->staffName->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_buy_transactions_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->createdDate->CellAttributes() ?>>
<span id="el_as_buy_transactions_createdDate">
<input type="text" data-table="as_buy_transactions" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->createdDate->EditValue ?>"<?php echo $as_buy_transactions->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_buy_transactions_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->createdUserID->CellAttributes() ?>>
<span id="el_as_buy_transactions_createdUserID">
<input type="text" data-table="as_buy_transactions" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->createdUserID->EditValue ?>"<?php echo $as_buy_transactions->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_buy_transactions_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->modifiedDate->CellAttributes() ?>>
<span id="el_as_buy_transactions_modifiedDate">
<input type="text" data-table="as_buy_transactions" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->modifiedDate->EditValue ?>"<?php echo $as_buy_transactions->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_transactions->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_buy_transactions_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_transactions->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_transactions->modifiedUserID->CellAttributes() ?>>
<span id="el_as_buy_transactions_modifiedUserID">
<input type="text" data-table="as_buy_transactions" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_transactions->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_buy_transactions->modifiedUserID->EditValue ?>"<?php echo $as_buy_transactions->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_buy_transactions->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_buy_transactions_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_buy_transactions_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_buy_transactions_edit->Pager)) $as_buy_transactions_edit->Pager = new cPrevNextPager($as_buy_transactions_edit->StartRec, $as_buy_transactions_edit->DisplayRecs, $as_buy_transactions_edit->TotalRecs) ?>
<?php if ($as_buy_transactions_edit->Pager->RecordCount > 0 && $as_buy_transactions_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_buy_transactions_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_buy_transactions_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_buy_transactions_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_buy_transactions_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_buy_transactions_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_buy_transactions_edit->PageUrl() ?>start=<?php echo $as_buy_transactions_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_buy_transactions_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_buy_transactionsedit.Init();
</script>
<?php
$as_buy_transactions_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_buy_transactions_edit->Page_Terminate();
?>
