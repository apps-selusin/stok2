<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_buy_paymentsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_buy_payments_edit = NULL; // Initialize page object first

class cas_buy_payments_edit extends cas_buy_payments {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_buy_payments';

	// Page object name
	var $PageObjName = 'as_buy_payments_edit';

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

		// Table object (as_buy_payments)
		if (!isset($GLOBALS["as_buy_payments"]) || get_class($GLOBALS["as_buy_payments"]) == "cas_buy_payments") {
			$GLOBALS["as_buy_payments"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_buy_payments"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_buy_payments', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_buy_paymentslist.php"));
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
		$this->paymentID->SetVisibility();
		$this->paymentID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->paymentNo->SetVisibility();
		$this->invoiceID->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->spbNo->SetVisibility();
		$this->paymentDate->SetVisibility();
		$this->payType->SetVisibility();
		$this->bankNo->SetVisibility();
		$this->bankName->SetVisibility();
		$this->bankAC->SetVisibility();
		$this->effectiveDate->SetVisibility();
		$this->total->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
		$this->supplierAddress->SetVisibility();
		$this->ref->SetVisibility();
		$this->note->SetVisibility();
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
		global $EW_EXPORT, $as_buy_payments;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_buy_payments);
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
		if (@$_GET["paymentID"] <> "") {
			$this->paymentID->setQueryStringValue($_GET["paymentID"]);
			$this->RecKey["paymentID"] = $this->paymentID->QueryStringValue;
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
			$this->Page_Terminate("as_buy_paymentslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->paymentID->CurrentValue) == strval($this->Recordset->fields('paymentID'))) {
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
					$this->Page_Terminate("as_buy_paymentslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_buy_paymentslist.php")
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
		if (!$this->paymentID->FldIsDetailKey)
			$this->paymentID->setFormValue($objForm->GetValue("x_paymentID"));
		if (!$this->paymentNo->FldIsDetailKey) {
			$this->paymentNo->setFormValue($objForm->GetValue("x_paymentNo"));
		}
		if (!$this->invoiceID->FldIsDetailKey) {
			$this->invoiceID->setFormValue($objForm->GetValue("x_invoiceID"));
		}
		if (!$this->invoiceNo->FldIsDetailKey) {
			$this->invoiceNo->setFormValue($objForm->GetValue("x_invoiceNo"));
		}
		if (!$this->spbNo->FldIsDetailKey) {
			$this->spbNo->setFormValue($objForm->GetValue("x_spbNo"));
		}
		if (!$this->paymentDate->FldIsDetailKey) {
			$this->paymentDate->setFormValue($objForm->GetValue("x_paymentDate"));
			$this->paymentDate->CurrentValue = ew_UnFormatDateTime($this->paymentDate->CurrentValue, 0);
		}
		if (!$this->payType->FldIsDetailKey) {
			$this->payType->setFormValue($objForm->GetValue("x_payType"));
		}
		if (!$this->bankNo->FldIsDetailKey) {
			$this->bankNo->setFormValue($objForm->GetValue("x_bankNo"));
		}
		if (!$this->bankName->FldIsDetailKey) {
			$this->bankName->setFormValue($objForm->GetValue("x_bankName"));
		}
		if (!$this->bankAC->FldIsDetailKey) {
			$this->bankAC->setFormValue($objForm->GetValue("x_bankAC"));
		}
		if (!$this->effectiveDate->FldIsDetailKey) {
			$this->effectiveDate->setFormValue($objForm->GetValue("x_effectiveDate"));
			$this->effectiveDate->CurrentValue = ew_UnFormatDateTime($this->effectiveDate->CurrentValue, 0);
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
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
		if (!$this->ref->FldIsDetailKey) {
			$this->ref->setFormValue($objForm->GetValue("x_ref"));
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
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
		$this->paymentID->CurrentValue = $this->paymentID->FormValue;
		$this->paymentNo->CurrentValue = $this->paymentNo->FormValue;
		$this->invoiceID->CurrentValue = $this->invoiceID->FormValue;
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->spbNo->CurrentValue = $this->spbNo->FormValue;
		$this->paymentDate->CurrentValue = $this->paymentDate->FormValue;
		$this->paymentDate->CurrentValue = ew_UnFormatDateTime($this->paymentDate->CurrentValue, 0);
		$this->payType->CurrentValue = $this->payType->FormValue;
		$this->bankNo->CurrentValue = $this->bankNo->FormValue;
		$this->bankName->CurrentValue = $this->bankName->FormValue;
		$this->bankAC->CurrentValue = $this->bankAC->FormValue;
		$this->effectiveDate->CurrentValue = $this->effectiveDate->FormValue;
		$this->effectiveDate->CurrentValue = ew_UnFormatDateTime($this->effectiveDate->CurrentValue, 0);
		$this->total->CurrentValue = $this->total->FormValue;
		$this->supplierID->CurrentValue = $this->supplierID->FormValue;
		$this->supplierName->CurrentValue = $this->supplierName->FormValue;
		$this->supplierAddress->CurrentValue = $this->supplierAddress->FormValue;
		$this->ref->CurrentValue = $this->ref->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
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
		$this->paymentID->setDbValue($rs->fields('paymentID'));
		$this->paymentNo->setDbValue($rs->fields('paymentNo'));
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->spbNo->setDbValue($rs->fields('spbNo'));
		$this->paymentDate->setDbValue($rs->fields('paymentDate'));
		$this->payType->setDbValue($rs->fields('payType'));
		$this->bankNo->setDbValue($rs->fields('bankNo'));
		$this->bankName->setDbValue($rs->fields('bankName'));
		$this->bankAC->setDbValue($rs->fields('bankAC'));
		$this->effectiveDate->setDbValue($rs->fields('effectiveDate'));
		$this->total->setDbValue($rs->fields('total'));
		$this->supplierID->setDbValue($rs->fields('supplierID'));
		$this->supplierName->setDbValue($rs->fields('supplierName'));
		$this->supplierAddress->setDbValue($rs->fields('supplierAddress'));
		$this->ref->setDbValue($rs->fields('ref'));
		$this->note->setDbValue($rs->fields('note'));
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
		$this->paymentID->DbValue = $row['paymentID'];
		$this->paymentNo->DbValue = $row['paymentNo'];
		$this->invoiceID->DbValue = $row['invoiceID'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->spbNo->DbValue = $row['spbNo'];
		$this->paymentDate->DbValue = $row['paymentDate'];
		$this->payType->DbValue = $row['payType'];
		$this->bankNo->DbValue = $row['bankNo'];
		$this->bankName->DbValue = $row['bankName'];
		$this->bankAC->DbValue = $row['bankAC'];
		$this->effectiveDate->DbValue = $row['effectiveDate'];
		$this->total->DbValue = $row['total'];
		$this->supplierID->DbValue = $row['supplierID'];
		$this->supplierName->DbValue = $row['supplierName'];
		$this->supplierAddress->DbValue = $row['supplierAddress'];
		$this->ref->DbValue = $row['ref'];
		$this->note->DbValue = $row['note'];
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

		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// paymentID
		// paymentNo
		// invoiceID
		// invoiceNo
		// spbNo
		// paymentDate
		// payType
		// bankNo
		// bankName
		// bankAC
		// effectiveDate
		// total
		// supplierID
		// supplierName
		// supplierAddress
		// ref
		// note
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// paymentID
		$this->paymentID->ViewValue = $this->paymentID->CurrentValue;
		$this->paymentID->ViewCustomAttributes = "";

		// paymentNo
		$this->paymentNo->ViewValue = $this->paymentNo->CurrentValue;
		$this->paymentNo->ViewCustomAttributes = "";

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// spbNo
		$this->spbNo->ViewValue = $this->spbNo->CurrentValue;
		$this->spbNo->ViewCustomAttributes = "";

		// paymentDate
		$this->paymentDate->ViewValue = $this->paymentDate->CurrentValue;
		$this->paymentDate->ViewValue = ew_FormatDateTime($this->paymentDate->ViewValue, 0);
		$this->paymentDate->ViewCustomAttributes = "";

		// payType
		$this->payType->ViewValue = $this->payType->CurrentValue;
		$this->payType->ViewCustomAttributes = "";

		// bankNo
		$this->bankNo->ViewValue = $this->bankNo->CurrentValue;
		$this->bankNo->ViewCustomAttributes = "";

		// bankName
		$this->bankName->ViewValue = $this->bankName->CurrentValue;
		$this->bankName->ViewCustomAttributes = "";

		// bankAC
		$this->bankAC->ViewValue = $this->bankAC->CurrentValue;
		$this->bankAC->ViewCustomAttributes = "";

		// effectiveDate
		$this->effectiveDate->ViewValue = $this->effectiveDate->CurrentValue;
		$this->effectiveDate->ViewValue = ew_FormatDateTime($this->effectiveDate->ViewValue, 0);
		$this->effectiveDate->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// supplierID
		$this->supplierID->ViewValue = $this->supplierID->CurrentValue;
		$this->supplierID->ViewCustomAttributes = "";

		// supplierName
		$this->supplierName->ViewValue = $this->supplierName->CurrentValue;
		$this->supplierName->ViewCustomAttributes = "";

		// supplierAddress
		$this->supplierAddress->ViewValue = $this->supplierAddress->CurrentValue;
		$this->supplierAddress->ViewCustomAttributes = "";

		// ref
		$this->ref->ViewValue = $this->ref->CurrentValue;
		$this->ref->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

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

			// paymentID
			$this->paymentID->LinkCustomAttributes = "";
			$this->paymentID->HrefValue = "";
			$this->paymentID->TooltipValue = "";

			// paymentNo
			$this->paymentNo->LinkCustomAttributes = "";
			$this->paymentNo->HrefValue = "";
			$this->paymentNo->TooltipValue = "";

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";
			$this->invoiceID->TooltipValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";
			$this->invoiceNo->TooltipValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";
			$this->spbNo->TooltipValue = "";

			// paymentDate
			$this->paymentDate->LinkCustomAttributes = "";
			$this->paymentDate->HrefValue = "";
			$this->paymentDate->TooltipValue = "";

			// payType
			$this->payType->LinkCustomAttributes = "";
			$this->payType->HrefValue = "";
			$this->payType->TooltipValue = "";

			// bankNo
			$this->bankNo->LinkCustomAttributes = "";
			$this->bankNo->HrefValue = "";
			$this->bankNo->TooltipValue = "";

			// bankName
			$this->bankName->LinkCustomAttributes = "";
			$this->bankName->HrefValue = "";
			$this->bankName->TooltipValue = "";

			// bankAC
			$this->bankAC->LinkCustomAttributes = "";
			$this->bankAC->HrefValue = "";
			$this->bankAC->TooltipValue = "";

			// effectiveDate
			$this->effectiveDate->LinkCustomAttributes = "";
			$this->effectiveDate->HrefValue = "";
			$this->effectiveDate->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

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

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";
			$this->ref->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

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

			// paymentID
			$this->paymentID->EditAttrs["class"] = "form-control";
			$this->paymentID->EditCustomAttributes = "";
			$this->paymentID->EditValue = $this->paymentID->CurrentValue;
			$this->paymentID->ViewCustomAttributes = "";

			// paymentNo
			$this->paymentNo->EditAttrs["class"] = "form-control";
			$this->paymentNo->EditCustomAttributes = "";
			$this->paymentNo->EditValue = ew_HtmlEncode($this->paymentNo->CurrentValue);
			$this->paymentNo->PlaceHolder = ew_RemoveHtml($this->paymentNo->FldCaption());

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

			// spbNo
			$this->spbNo->EditAttrs["class"] = "form-control";
			$this->spbNo->EditCustomAttributes = "";
			$this->spbNo->EditValue = ew_HtmlEncode($this->spbNo->CurrentValue);
			$this->spbNo->PlaceHolder = ew_RemoveHtml($this->spbNo->FldCaption());

			// paymentDate
			$this->paymentDate->EditAttrs["class"] = "form-control";
			$this->paymentDate->EditCustomAttributes = "";
			$this->paymentDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->paymentDate->CurrentValue, 8));
			$this->paymentDate->PlaceHolder = ew_RemoveHtml($this->paymentDate->FldCaption());

			// payType
			$this->payType->EditAttrs["class"] = "form-control";
			$this->payType->EditCustomAttributes = "";
			$this->payType->EditValue = ew_HtmlEncode($this->payType->CurrentValue);
			$this->payType->PlaceHolder = ew_RemoveHtml($this->payType->FldCaption());

			// bankNo
			$this->bankNo->EditAttrs["class"] = "form-control";
			$this->bankNo->EditCustomAttributes = "";
			$this->bankNo->EditValue = ew_HtmlEncode($this->bankNo->CurrentValue);
			$this->bankNo->PlaceHolder = ew_RemoveHtml($this->bankNo->FldCaption());

			// bankName
			$this->bankName->EditAttrs["class"] = "form-control";
			$this->bankName->EditCustomAttributes = "";
			$this->bankName->EditValue = ew_HtmlEncode($this->bankName->CurrentValue);
			$this->bankName->PlaceHolder = ew_RemoveHtml($this->bankName->FldCaption());

			// bankAC
			$this->bankAC->EditAttrs["class"] = "form-control";
			$this->bankAC->EditCustomAttributes = "";
			$this->bankAC->EditValue = ew_HtmlEncode($this->bankAC->CurrentValue);
			$this->bankAC->PlaceHolder = ew_RemoveHtml($this->bankAC->FldCaption());

			// effectiveDate
			$this->effectiveDate->EditAttrs["class"] = "form-control";
			$this->effectiveDate->EditCustomAttributes = "";
			$this->effectiveDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->effectiveDate->CurrentValue, 8));
			$this->effectiveDate->PlaceHolder = ew_RemoveHtml($this->effectiveDate->FldCaption());

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

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
			// paymentID

			$this->paymentID->LinkCustomAttributes = "";
			$this->paymentID->HrefValue = "";

			// paymentNo
			$this->paymentNo->LinkCustomAttributes = "";
			$this->paymentNo->HrefValue = "";

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";

			// paymentDate
			$this->paymentDate->LinkCustomAttributes = "";
			$this->paymentDate->HrefValue = "";

			// payType
			$this->payType->LinkCustomAttributes = "";
			$this->payType->HrefValue = "";

			// bankNo
			$this->bankNo->LinkCustomAttributes = "";
			$this->bankNo->HrefValue = "";

			// bankName
			$this->bankName->LinkCustomAttributes = "";
			$this->bankName->HrefValue = "";

			// bankAC
			$this->bankAC->LinkCustomAttributes = "";
			$this->bankAC->HrefValue = "";

			// effectiveDate
			$this->effectiveDate->LinkCustomAttributes = "";
			$this->effectiveDate->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

			// supplierID
			$this->supplierID->LinkCustomAttributes = "";
			$this->supplierID->HrefValue = "";

			// supplierName
			$this->supplierName->LinkCustomAttributes = "";
			$this->supplierName->HrefValue = "";

			// supplierAddress
			$this->supplierAddress->LinkCustomAttributes = "";
			$this->supplierAddress->HrefValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

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
		if (!$this->paymentNo->FldIsDetailKey && !is_null($this->paymentNo->FormValue) && $this->paymentNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->paymentNo->FldCaption(), $this->paymentNo->ReqErrMsg));
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
		if (!$this->spbNo->FldIsDetailKey && !is_null($this->spbNo->FormValue) && $this->spbNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbNo->FldCaption(), $this->spbNo->ReqErrMsg));
		}
		if (!$this->paymentDate->FldIsDetailKey && !is_null($this->paymentDate->FormValue) && $this->paymentDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->paymentDate->FldCaption(), $this->paymentDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->paymentDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->paymentDate->FldErrMsg());
		}
		if (!$this->payType->FldIsDetailKey && !is_null($this->payType->FormValue) && $this->payType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->payType->FldCaption(), $this->payType->ReqErrMsg));
		}
		if (!$this->bankNo->FldIsDetailKey && !is_null($this->bankNo->FormValue) && $this->bankNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bankNo->FldCaption(), $this->bankNo->ReqErrMsg));
		}
		if (!$this->bankName->FldIsDetailKey && !is_null($this->bankName->FormValue) && $this->bankName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bankName->FldCaption(), $this->bankName->ReqErrMsg));
		}
		if (!$this->bankAC->FldIsDetailKey && !is_null($this->bankAC->FormValue) && $this->bankAC->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bankAC->FldCaption(), $this->bankAC->ReqErrMsg));
		}
		if (!$this->effectiveDate->FldIsDetailKey && !is_null($this->effectiveDate->FormValue) && $this->effectiveDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->effectiveDate->FldCaption(), $this->effectiveDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->effectiveDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->effectiveDate->FldErrMsg());
		}
		if (!$this->total->FldIsDetailKey && !is_null($this->total->FormValue) && $this->total->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total->FldCaption(), $this->total->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
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
		if (!$this->ref->FldIsDetailKey && !is_null($this->ref->FormValue) && $this->ref->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ref->FldCaption(), $this->ref->ReqErrMsg));
		}
		if (!$this->note->FldIsDetailKey && !is_null($this->note->FormValue) && $this->note->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->note->FldCaption(), $this->note->ReqErrMsg));
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

			// paymentNo
			$this->paymentNo->SetDbValueDef($rsnew, $this->paymentNo->CurrentValue, "", $this->paymentNo->ReadOnly);

			// invoiceID
			$this->invoiceID->SetDbValueDef($rsnew, $this->invoiceID->CurrentValue, 0, $this->invoiceID->ReadOnly);

			// invoiceNo
			$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", $this->invoiceNo->ReadOnly);

			// spbNo
			$this->spbNo->SetDbValueDef($rsnew, $this->spbNo->CurrentValue, "", $this->spbNo->ReadOnly);

			// paymentDate
			$this->paymentDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->paymentDate->CurrentValue, 0), ew_CurrentDate(), $this->paymentDate->ReadOnly);

			// payType
			$this->payType->SetDbValueDef($rsnew, $this->payType->CurrentValue, "", $this->payType->ReadOnly);

			// bankNo
			$this->bankNo->SetDbValueDef($rsnew, $this->bankNo->CurrentValue, "", $this->bankNo->ReadOnly);

			// bankName
			$this->bankName->SetDbValueDef($rsnew, $this->bankName->CurrentValue, "", $this->bankName->ReadOnly);

			// bankAC
			$this->bankAC->SetDbValueDef($rsnew, $this->bankAC->CurrentValue, "", $this->bankAC->ReadOnly);

			// effectiveDate
			$this->effectiveDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->effectiveDate->CurrentValue, 0), ew_CurrentDate(), $this->effectiveDate->ReadOnly);

			// total
			$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, 0, $this->total->ReadOnly);

			// supplierID
			$this->supplierID->SetDbValueDef($rsnew, $this->supplierID->CurrentValue, 0, $this->supplierID->ReadOnly);

			// supplierName
			$this->supplierName->SetDbValueDef($rsnew, $this->supplierName->CurrentValue, "", $this->supplierName->ReadOnly);

			// supplierAddress
			$this->supplierAddress->SetDbValueDef($rsnew, $this->supplierAddress->CurrentValue, "", $this->supplierAddress->ReadOnly);

			// ref
			$this->ref->SetDbValueDef($rsnew, $this->ref->CurrentValue, "", $this->ref->ReadOnly);

			// note
			$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", $this->note->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_buy_paymentslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_buy_payments_edit)) $as_buy_payments_edit = new cas_buy_payments_edit();

// Page init
$as_buy_payments_edit->Page_Init();

// Page main
$as_buy_payments_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_buy_payments_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_buy_paymentsedit = new ew_Form("fas_buy_paymentsedit", "edit");

// Validate form
fas_buy_paymentsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_paymentNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->paymentNo->FldCaption(), $as_buy_payments->paymentNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->invoiceID->FldCaption(), $as_buy_payments->invoiceID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->invoiceID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_invoiceNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->invoiceNo->FldCaption(), $as_buy_payments->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spbNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->spbNo->FldCaption(), $as_buy_payments->spbNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->paymentDate->FldCaption(), $as_buy_payments->paymentDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paymentDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->paymentDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_payType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->payType->FldCaption(), $as_buy_payments->payType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bankNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->bankNo->FldCaption(), $as_buy_payments->bankNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bankName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->bankName->FldCaption(), $as_buy_payments->bankName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bankAC");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->bankAC->FldCaption(), $as_buy_payments->bankAC->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_effectiveDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->effectiveDate->FldCaption(), $as_buy_payments->effectiveDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_effectiveDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->effectiveDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->total->FldCaption(), $as_buy_payments->total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->supplierID->FldCaption(), $as_buy_payments->supplierID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->supplierID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->supplierName->FldCaption(), $as_buy_payments->supplierName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->supplierAddress->FldCaption(), $as_buy_payments->supplierAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ref");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->ref->FldCaption(), $as_buy_payments->ref->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->note->FldCaption(), $as_buy_payments->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->staffID->FldCaption(), $as_buy_payments->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->staffName->FldCaption(), $as_buy_payments->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->createdDate->FldCaption(), $as_buy_payments->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->createdUserID->FldCaption(), $as_buy_payments->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->modifiedDate->FldCaption(), $as_buy_payments->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_buy_payments->modifiedUserID->FldCaption(), $as_buy_payments->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_buy_payments->modifiedUserID->FldErrMsg()) ?>");

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
fas_buy_paymentsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_buy_paymentsedit.ValidateRequired = true;
<?php } else { ?>
fas_buy_paymentsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_buy_payments_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_buy_payments_edit->ShowPageHeader(); ?>
<?php
$as_buy_payments_edit->ShowMessage();
?>
<?php if (!$as_buy_payments_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_buy_payments_edit->Pager)) $as_buy_payments_edit->Pager = new cPrevNextPager($as_buy_payments_edit->StartRec, $as_buy_payments_edit->DisplayRecs, $as_buy_payments_edit->TotalRecs) ?>
<?php if ($as_buy_payments_edit->Pager->RecordCount > 0 && $as_buy_payments_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_buy_payments_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_buy_payments_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_buy_payments_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_buy_payments_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_buy_payments_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_buy_payments_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_buy_paymentsedit" id="fas_buy_paymentsedit" class="<?php echo $as_buy_payments_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_buy_payments_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_buy_payments_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_buy_payments">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_buy_payments_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_buy_payments->paymentID->Visible) { // paymentID ?>
	<div id="r_paymentID" class="form-group">
		<label id="elh_as_buy_payments_paymentID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->paymentID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->paymentID->CellAttributes() ?>>
<span id="el_as_buy_payments_paymentID">
<span<?php echo $as_buy_payments->paymentID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_buy_payments->paymentID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_buy_payments" data-field="x_paymentID" name="x_paymentID" id="x_paymentID" value="<?php echo ew_HtmlEncode($as_buy_payments->paymentID->CurrentValue) ?>">
<?php echo $as_buy_payments->paymentID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->paymentNo->Visible) { // paymentNo ?>
	<div id="r_paymentNo" class="form-group">
		<label id="elh_as_buy_payments_paymentNo" for="x_paymentNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->paymentNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->paymentNo->CellAttributes() ?>>
<span id="el_as_buy_payments_paymentNo">
<input type="text" data-table="as_buy_payments" data-field="x_paymentNo" name="x_paymentNo" id="x_paymentNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->paymentNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->paymentNo->EditValue ?>"<?php echo $as_buy_payments->paymentNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->paymentNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->invoiceID->Visible) { // invoiceID ?>
	<div id="r_invoiceID" class="form-group">
		<label id="elh_as_buy_payments_invoiceID" for="x_invoiceID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->invoiceID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->invoiceID->CellAttributes() ?>>
<span id="el_as_buy_payments_invoiceID">
<input type="text" data-table="as_buy_payments" data-field="x_invoiceID" name="x_invoiceID" id="x_invoiceID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->invoiceID->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->invoiceID->EditValue ?>"<?php echo $as_buy_payments->invoiceID->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->invoiceID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_buy_payments_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->invoiceNo->CellAttributes() ?>>
<span id="el_as_buy_payments_invoiceNo">
<input type="text" data-table="as_buy_payments" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->invoiceNo->EditValue ?>"<?php echo $as_buy_payments->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->spbNo->Visible) { // spbNo ?>
	<div id="r_spbNo" class="form-group">
		<label id="elh_as_buy_payments_spbNo" for="x_spbNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->spbNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->spbNo->CellAttributes() ?>>
<span id="el_as_buy_payments_spbNo">
<input type="text" data-table="as_buy_payments" data-field="x_spbNo" name="x_spbNo" id="x_spbNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->spbNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->spbNo->EditValue ?>"<?php echo $as_buy_payments->spbNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->spbNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->paymentDate->Visible) { // paymentDate ?>
	<div id="r_paymentDate" class="form-group">
		<label id="elh_as_buy_payments_paymentDate" for="x_paymentDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->paymentDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->paymentDate->CellAttributes() ?>>
<span id="el_as_buy_payments_paymentDate">
<input type="text" data-table="as_buy_payments" data-field="x_paymentDate" name="x_paymentDate" id="x_paymentDate" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->paymentDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->paymentDate->EditValue ?>"<?php echo $as_buy_payments->paymentDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->paymentDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->payType->Visible) { // payType ?>
	<div id="r_payType" class="form-group">
		<label id="elh_as_buy_payments_payType" for="x_payType" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->payType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->payType->CellAttributes() ?>>
<span id="el_as_buy_payments_payType">
<input type="text" data-table="as_buy_payments" data-field="x_payType" name="x_payType" id="x_payType" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->payType->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->payType->EditValue ?>"<?php echo $as_buy_payments->payType->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->payType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->bankNo->Visible) { // bankNo ?>
	<div id="r_bankNo" class="form-group">
		<label id="elh_as_buy_payments_bankNo" for="x_bankNo" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->bankNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->bankNo->CellAttributes() ?>>
<span id="el_as_buy_payments_bankNo">
<input type="text" data-table="as_buy_payments" data-field="x_bankNo" name="x_bankNo" id="x_bankNo" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->bankNo->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->bankNo->EditValue ?>"<?php echo $as_buy_payments->bankNo->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->bankNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->bankName->Visible) { // bankName ?>
	<div id="r_bankName" class="form-group">
		<label id="elh_as_buy_payments_bankName" for="x_bankName" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->bankName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->bankName->CellAttributes() ?>>
<span id="el_as_buy_payments_bankName">
<input type="text" data-table="as_buy_payments" data-field="x_bankName" name="x_bankName" id="x_bankName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->bankName->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->bankName->EditValue ?>"<?php echo $as_buy_payments->bankName->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->bankName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->bankAC->Visible) { // bankAC ?>
	<div id="r_bankAC" class="form-group">
		<label id="elh_as_buy_payments_bankAC" for="x_bankAC" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->bankAC->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->bankAC->CellAttributes() ?>>
<span id="el_as_buy_payments_bankAC">
<input type="text" data-table="as_buy_payments" data-field="x_bankAC" name="x_bankAC" id="x_bankAC" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->bankAC->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->bankAC->EditValue ?>"<?php echo $as_buy_payments->bankAC->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->bankAC->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->effectiveDate->Visible) { // effectiveDate ?>
	<div id="r_effectiveDate" class="form-group">
		<label id="elh_as_buy_payments_effectiveDate" for="x_effectiveDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->effectiveDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->effectiveDate->CellAttributes() ?>>
<span id="el_as_buy_payments_effectiveDate">
<input type="text" data-table="as_buy_payments" data-field="x_effectiveDate" name="x_effectiveDate" id="x_effectiveDate" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->effectiveDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->effectiveDate->EditValue ?>"<?php echo $as_buy_payments->effectiveDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->effectiveDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_as_buy_payments_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->total->CellAttributes() ?>>
<span id="el_as_buy_payments_total">
<input type="text" data-table="as_buy_payments" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->total->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->total->EditValue ?>"<?php echo $as_buy_payments->total->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->supplierID->Visible) { // supplierID ?>
	<div id="r_supplierID" class="form-group">
		<label id="elh_as_buy_payments_supplierID" for="x_supplierID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->supplierID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->supplierID->CellAttributes() ?>>
<span id="el_as_buy_payments_supplierID">
<input type="text" data-table="as_buy_payments" data-field="x_supplierID" name="x_supplierID" id="x_supplierID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->supplierID->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->supplierID->EditValue ?>"<?php echo $as_buy_payments->supplierID->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->supplierID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->supplierName->Visible) { // supplierName ?>
	<div id="r_supplierName" class="form-group">
		<label id="elh_as_buy_payments_supplierName" for="x_supplierName" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->supplierName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->supplierName->CellAttributes() ?>>
<span id="el_as_buy_payments_supplierName">
<input type="text" data-table="as_buy_payments" data-field="x_supplierName" name="x_supplierName" id="x_supplierName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->supplierName->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->supplierName->EditValue ?>"<?php echo $as_buy_payments->supplierName->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->supplierName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->supplierAddress->Visible) { // supplierAddress ?>
	<div id="r_supplierAddress" class="form-group">
		<label id="elh_as_buy_payments_supplierAddress" for="x_supplierAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->supplierAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->supplierAddress->CellAttributes() ?>>
<span id="el_as_buy_payments_supplierAddress">
<textarea data-table="as_buy_payments" data-field="x_supplierAddress" name="x_supplierAddress" id="x_supplierAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->supplierAddress->getPlaceHolder()) ?>"<?php echo $as_buy_payments->supplierAddress->EditAttributes() ?>><?php echo $as_buy_payments->supplierAddress->EditValue ?></textarea>
</span>
<?php echo $as_buy_payments->supplierAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->ref->Visible) { // ref ?>
	<div id="r_ref" class="form-group">
		<label id="elh_as_buy_payments_ref" for="x_ref" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->ref->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->ref->CellAttributes() ?>>
<span id="el_as_buy_payments_ref">
<input type="text" data-table="as_buy_payments" data-field="x_ref" name="x_ref" id="x_ref" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->ref->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->ref->EditValue ?>"<?php echo $as_buy_payments->ref->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->ref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_buy_payments_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->note->CellAttributes() ?>>
<span id="el_as_buy_payments_note">
<textarea data-table="as_buy_payments" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->note->getPlaceHolder()) ?>"<?php echo $as_buy_payments->note->EditAttributes() ?>><?php echo $as_buy_payments->note->EditValue ?></textarea>
</span>
<?php echo $as_buy_payments->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_buy_payments_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->staffID->CellAttributes() ?>>
<span id="el_as_buy_payments_staffID">
<input type="text" data-table="as_buy_payments" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->staffID->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->staffID->EditValue ?>"<?php echo $as_buy_payments->staffID->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_buy_payments_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->staffName->CellAttributes() ?>>
<span id="el_as_buy_payments_staffName">
<input type="text" data-table="as_buy_payments" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->staffName->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->staffName->EditValue ?>"<?php echo $as_buy_payments->staffName->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_buy_payments_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->createdDate->CellAttributes() ?>>
<span id="el_as_buy_payments_createdDate">
<input type="text" data-table="as_buy_payments" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->createdDate->EditValue ?>"<?php echo $as_buy_payments->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_buy_payments_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->createdUserID->CellAttributes() ?>>
<span id="el_as_buy_payments_createdUserID">
<input type="text" data-table="as_buy_payments" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->createdUserID->EditValue ?>"<?php echo $as_buy_payments->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_buy_payments_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->modifiedDate->CellAttributes() ?>>
<span id="el_as_buy_payments_modifiedDate">
<input type="text" data-table="as_buy_payments" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->modifiedDate->EditValue ?>"<?php echo $as_buy_payments->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_buy_payments->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_buy_payments_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_buy_payments->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_buy_payments->modifiedUserID->CellAttributes() ?>>
<span id="el_as_buy_payments_modifiedUserID">
<input type="text" data-table="as_buy_payments" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_buy_payments->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_buy_payments->modifiedUserID->EditValue ?>"<?php echo $as_buy_payments->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_buy_payments->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_buy_payments_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_buy_payments_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_buy_payments_edit->Pager)) $as_buy_payments_edit->Pager = new cPrevNextPager($as_buy_payments_edit->StartRec, $as_buy_payments_edit->DisplayRecs, $as_buy_payments_edit->TotalRecs) ?>
<?php if ($as_buy_payments_edit->Pager->RecordCount > 0 && $as_buy_payments_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_buy_payments_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_buy_payments_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_buy_payments_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_buy_payments_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_buy_payments_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_buy_payments_edit->PageUrl() ?>start=<?php echo $as_buy_payments_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_buy_payments_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_buy_paymentsedit.Init();
</script>
<?php
$as_buy_payments_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_buy_payments_edit->Page_Terminate();
?>
