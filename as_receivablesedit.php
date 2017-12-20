<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_receivablesinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_receivables_edit = NULL; // Initialize page object first

class cas_receivables_edit extends cas_receivables {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_receivables';

	// Page object name
	var $PageObjName = 'as_receivables_edit';

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

		// Table object (as_receivables)
		if (!isset($GLOBALS["as_receivables"]) || get_class($GLOBALS["as_receivables"]) == "cas_receivables") {
			$GLOBALS["as_receivables"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_receivables"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_receivables', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_receivableslist.php"));
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
		$this->receiveID->SetVisibility();
		$this->receiveID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->receiveNo->SetVisibility();
		$this->invoiceID->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->customerID->SetVisibility();
		$this->customerName->SetVisibility();
		$this->customerAddress->SetVisibility();
		$this->receiveTotal->SetVisibility();
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
		global $EW_EXPORT, $as_receivables;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_receivables);
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
		if (@$_GET["receiveID"] <> "") {
			$this->receiveID->setQueryStringValue($_GET["receiveID"]);
			$this->RecKey["receiveID"] = $this->receiveID->QueryStringValue;
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
			$this->Page_Terminate("as_receivableslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->receiveID->CurrentValue) == strval($this->Recordset->fields('receiveID'))) {
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
					$this->Page_Terminate("as_receivableslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_receivableslist.php")
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
		if (!$this->receiveID->FldIsDetailKey)
			$this->receiveID->setFormValue($objForm->GetValue("x_receiveID"));
		if (!$this->receiveNo->FldIsDetailKey) {
			$this->receiveNo->setFormValue($objForm->GetValue("x_receiveNo"));
		}
		if (!$this->invoiceID->FldIsDetailKey) {
			$this->invoiceID->setFormValue($objForm->GetValue("x_invoiceID"));
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
		if (!$this->receiveTotal->FldIsDetailKey) {
			$this->receiveTotal->setFormValue($objForm->GetValue("x_receiveTotal"));
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
		$this->LoadRow();
		$this->receiveID->CurrentValue = $this->receiveID->FormValue;
		$this->receiveNo->CurrentValue = $this->receiveNo->FormValue;
		$this->invoiceID->CurrentValue = $this->invoiceID->FormValue;
		$this->invoiceNo->CurrentValue = $this->invoiceNo->FormValue;
		$this->customerID->CurrentValue = $this->customerID->FormValue;
		$this->customerName->CurrentValue = $this->customerName->FormValue;
		$this->customerAddress->CurrentValue = $this->customerAddress->FormValue;
		$this->receiveTotal->CurrentValue = $this->receiveTotal->FormValue;
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
		$this->receiveID->setDbValue($rs->fields('receiveID'));
		$this->receiveNo->setDbValue($rs->fields('receiveNo'));
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
		$this->receiveTotal->setDbValue($rs->fields('receiveTotal'));
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
		$this->receiveID->DbValue = $row['receiveID'];
		$this->receiveNo->DbValue = $row['receiveNo'];
		$this->invoiceID->DbValue = $row['invoiceID'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->customerID->DbValue = $row['customerID'];
		$this->customerName->DbValue = $row['customerName'];
		$this->customerAddress->DbValue = $row['customerAddress'];
		$this->receiveTotal->DbValue = $row['receiveTotal'];
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

		if ($this->receiveTotal->FormValue == $this->receiveTotal->CurrentValue && is_numeric(ew_StrToFloat($this->receiveTotal->CurrentValue)))
			$this->receiveTotal->CurrentValue = ew_StrToFloat($this->receiveTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->incomingTotal->FormValue == $this->incomingTotal->CurrentValue && is_numeric(ew_StrToFloat($this->incomingTotal->CurrentValue)))
			$this->incomingTotal->CurrentValue = ew_StrToFloat($this->incomingTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->reductionTotal->FormValue == $this->reductionTotal->CurrentValue && is_numeric(ew_StrToFloat($this->reductionTotal->CurrentValue)))
			$this->reductionTotal->CurrentValue = ew_StrToFloat($this->reductionTotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// receiveID
		// receiveNo
		// invoiceID
		// invoiceNo
		// customerID
		// customerName
		// customerAddress
		// receiveTotal
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

		// receiveID
		$this->receiveID->ViewValue = $this->receiveID->CurrentValue;
		$this->receiveID->ViewCustomAttributes = "";

		// receiveNo
		$this->receiveNo->ViewValue = $this->receiveNo->CurrentValue;
		$this->receiveNo->ViewCustomAttributes = "";

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

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

		// receiveTotal
		$this->receiveTotal->ViewValue = $this->receiveTotal->CurrentValue;
		$this->receiveTotal->ViewCustomAttributes = "";

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

			// receiveID
			$this->receiveID->LinkCustomAttributes = "";
			$this->receiveID->HrefValue = "";
			$this->receiveID->TooltipValue = "";

			// receiveNo
			$this->receiveNo->LinkCustomAttributes = "";
			$this->receiveNo->HrefValue = "";
			$this->receiveNo->TooltipValue = "";

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";
			$this->invoiceID->TooltipValue = "";

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

			// receiveTotal
			$this->receiveTotal->LinkCustomAttributes = "";
			$this->receiveTotal->HrefValue = "";
			$this->receiveTotal->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// receiveID
			$this->receiveID->EditAttrs["class"] = "form-control";
			$this->receiveID->EditCustomAttributes = "";
			$this->receiveID->EditValue = $this->receiveID->CurrentValue;
			$this->receiveID->ViewCustomAttributes = "";

			// receiveNo
			$this->receiveNo->EditAttrs["class"] = "form-control";
			$this->receiveNo->EditCustomAttributes = "";
			$this->receiveNo->EditValue = ew_HtmlEncode($this->receiveNo->CurrentValue);
			$this->receiveNo->PlaceHolder = ew_RemoveHtml($this->receiveNo->FldCaption());

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

			// receiveTotal
			$this->receiveTotal->EditAttrs["class"] = "form-control";
			$this->receiveTotal->EditCustomAttributes = "";
			$this->receiveTotal->EditValue = ew_HtmlEncode($this->receiveTotal->CurrentValue);
			$this->receiveTotal->PlaceHolder = ew_RemoveHtml($this->receiveTotal->FldCaption());
			if (strval($this->receiveTotal->EditValue) <> "" && is_numeric($this->receiveTotal->EditValue)) $this->receiveTotal->EditValue = ew_FormatNumber($this->receiveTotal->EditValue, -2, -1, -2, 0);

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

			// Edit refer script
			// receiveID

			$this->receiveID->LinkCustomAttributes = "";
			$this->receiveID->HrefValue = "";

			// receiveNo
			$this->receiveNo->LinkCustomAttributes = "";
			$this->receiveNo->HrefValue = "";

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";

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

			// receiveTotal
			$this->receiveTotal->LinkCustomAttributes = "";
			$this->receiveTotal->HrefValue = "";

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
		if (!$this->receiveNo->FldIsDetailKey && !is_null($this->receiveNo->FormValue) && $this->receiveNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->receiveNo->FldCaption(), $this->receiveNo->ReqErrMsg));
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
		if (!$this->receiveTotal->FldIsDetailKey && !is_null($this->receiveTotal->FormValue) && $this->receiveTotal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->receiveTotal->FldCaption(), $this->receiveTotal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->receiveTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->receiveTotal->FldErrMsg());
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

			// receiveNo
			$this->receiveNo->SetDbValueDef($rsnew, $this->receiveNo->CurrentValue, "", $this->receiveNo->ReadOnly);

			// invoiceID
			$this->invoiceID->SetDbValueDef($rsnew, $this->invoiceID->CurrentValue, 0, $this->invoiceID->ReadOnly);

			// invoiceNo
			$this->invoiceNo->SetDbValueDef($rsnew, $this->invoiceNo->CurrentValue, "", $this->invoiceNo->ReadOnly);

			// customerID
			$this->customerID->SetDbValueDef($rsnew, $this->customerID->CurrentValue, 0, $this->customerID->ReadOnly);

			// customerName
			$this->customerName->SetDbValueDef($rsnew, $this->customerName->CurrentValue, "", $this->customerName->ReadOnly);

			// customerAddress
			$this->customerAddress->SetDbValueDef($rsnew, $this->customerAddress->CurrentValue, "", $this->customerAddress->ReadOnly);

			// receiveTotal
			$this->receiveTotal->SetDbValueDef($rsnew, $this->receiveTotal->CurrentValue, 0, $this->receiveTotal->ReadOnly);

			// incomingTotal
			$this->incomingTotal->SetDbValueDef($rsnew, $this->incomingTotal->CurrentValue, 0, $this->incomingTotal->ReadOnly);

			// reductionTotal
			$this->reductionTotal->SetDbValueDef($rsnew, $this->reductionTotal->CurrentValue, 0, $this->reductionTotal->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", $this->status->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_receivableslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_receivables_edit)) $as_receivables_edit = new cas_receivables_edit();

// Page init
$as_receivables_edit->Page_Init();

// Page main
$as_receivables_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_receivables_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_receivablesedit = new ew_Form("fas_receivablesedit", "edit");

// Validate form
fas_receivablesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_receiveNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->receiveNo->FldCaption(), $as_receivables->receiveNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->invoiceID->FldCaption(), $as_receivables->invoiceID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_invoiceID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->invoiceID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_invoiceNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->invoiceNo->FldCaption(), $as_receivables->invoiceNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->customerID->FldCaption(), $as_receivables->customerID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->customerID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->customerName->FldCaption(), $as_receivables->customerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->customerAddress->FldCaption(), $as_receivables->customerAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_receiveTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->receiveTotal->FldCaption(), $as_receivables->receiveTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_receiveTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->receiveTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_incomingTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->incomingTotal->FldCaption(), $as_receivables->incomingTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_incomingTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->incomingTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reductionTotal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->reductionTotal->FldCaption(), $as_receivables->reductionTotal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reductionTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->reductionTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->status->FldCaption(), $as_receivables->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->staffID->FldCaption(), $as_receivables->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->staffName->FldCaption(), $as_receivables->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->createdDate->FldCaption(), $as_receivables->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->createdUserID->FldCaption(), $as_receivables->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->modifiedDate->FldCaption(), $as_receivables->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_receivables->modifiedUserID->FldCaption(), $as_receivables->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_receivables->modifiedUserID->FldErrMsg()) ?>");

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
fas_receivablesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_receivablesedit.ValidateRequired = true;
<?php } else { ?>
fas_receivablesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_receivables_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_receivables_edit->ShowPageHeader(); ?>
<?php
$as_receivables_edit->ShowMessage();
?>
<?php if (!$as_receivables_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_receivables_edit->Pager)) $as_receivables_edit->Pager = new cPrevNextPager($as_receivables_edit->StartRec, $as_receivables_edit->DisplayRecs, $as_receivables_edit->TotalRecs) ?>
<?php if ($as_receivables_edit->Pager->RecordCount > 0 && $as_receivables_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_receivables_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_receivables_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_receivables_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_receivables_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_receivables_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_receivables_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_receivablesedit" id="fas_receivablesedit" class="<?php echo $as_receivables_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_receivables_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_receivables_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_receivables">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_receivables_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_receivables->receiveID->Visible) { // receiveID ?>
	<div id="r_receiveID" class="form-group">
		<label id="elh_as_receivables_receiveID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->receiveID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->receiveID->CellAttributes() ?>>
<span id="el_as_receivables_receiveID">
<span<?php echo $as_receivables->receiveID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_receivables->receiveID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_receivables" data-field="x_receiveID" name="x_receiveID" id="x_receiveID" value="<?php echo ew_HtmlEncode($as_receivables->receiveID->CurrentValue) ?>">
<?php echo $as_receivables->receiveID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->receiveNo->Visible) { // receiveNo ?>
	<div id="r_receiveNo" class="form-group">
		<label id="elh_as_receivables_receiveNo" for="x_receiveNo" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->receiveNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->receiveNo->CellAttributes() ?>>
<span id="el_as_receivables_receiveNo">
<input type="text" data-table="as_receivables" data-field="x_receiveNo" name="x_receiveNo" id="x_receiveNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_receivables->receiveNo->getPlaceHolder()) ?>" value="<?php echo $as_receivables->receiveNo->EditValue ?>"<?php echo $as_receivables->receiveNo->EditAttributes() ?>>
</span>
<?php echo $as_receivables->receiveNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->invoiceID->Visible) { // invoiceID ?>
	<div id="r_invoiceID" class="form-group">
		<label id="elh_as_receivables_invoiceID" for="x_invoiceID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->invoiceID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->invoiceID->CellAttributes() ?>>
<span id="el_as_receivables_invoiceID">
<input type="text" data-table="as_receivables" data-field="x_invoiceID" name="x_invoiceID" id="x_invoiceID" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->invoiceID->getPlaceHolder()) ?>" value="<?php echo $as_receivables->invoiceID->EditValue ?>"<?php echo $as_receivables->invoiceID->EditAttributes() ?>>
</span>
<?php echo $as_receivables->invoiceID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->invoiceNo->Visible) { // invoiceNo ?>
	<div id="r_invoiceNo" class="form-group">
		<label id="elh_as_receivables_invoiceNo" for="x_invoiceNo" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->invoiceNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->invoiceNo->CellAttributes() ?>>
<span id="el_as_receivables_invoiceNo">
<input type="text" data-table="as_receivables" data-field="x_invoiceNo" name="x_invoiceNo" id="x_invoiceNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_receivables->invoiceNo->getPlaceHolder()) ?>" value="<?php echo $as_receivables->invoiceNo->EditValue ?>"<?php echo $as_receivables->invoiceNo->EditAttributes() ?>>
</span>
<?php echo $as_receivables->invoiceNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->customerID->Visible) { // customerID ?>
	<div id="r_customerID" class="form-group">
		<label id="elh_as_receivables_customerID" for="x_customerID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->customerID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->customerID->CellAttributes() ?>>
<span id="el_as_receivables_customerID">
<input type="text" data-table="as_receivables" data-field="x_customerID" name="x_customerID" id="x_customerID" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->customerID->getPlaceHolder()) ?>" value="<?php echo $as_receivables->customerID->EditValue ?>"<?php echo $as_receivables->customerID->EditAttributes() ?>>
</span>
<?php echo $as_receivables->customerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->customerName->Visible) { // customerName ?>
	<div id="r_customerName" class="form-group">
		<label id="elh_as_receivables_customerName" for="x_customerName" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->customerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->customerName->CellAttributes() ?>>
<span id="el_as_receivables_customerName">
<input type="text" data-table="as_receivables" data-field="x_customerName" name="x_customerName" id="x_customerName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_receivables->customerName->getPlaceHolder()) ?>" value="<?php echo $as_receivables->customerName->EditValue ?>"<?php echo $as_receivables->customerName->EditAttributes() ?>>
</span>
<?php echo $as_receivables->customerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->customerAddress->Visible) { // customerAddress ?>
	<div id="r_customerAddress" class="form-group">
		<label id="elh_as_receivables_customerAddress" for="x_customerAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->customerAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->customerAddress->CellAttributes() ?>>
<span id="el_as_receivables_customerAddress">
<textarea data-table="as_receivables" data-field="x_customerAddress" name="x_customerAddress" id="x_customerAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_receivables->customerAddress->getPlaceHolder()) ?>"<?php echo $as_receivables->customerAddress->EditAttributes() ?>><?php echo $as_receivables->customerAddress->EditValue ?></textarea>
</span>
<?php echo $as_receivables->customerAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->receiveTotal->Visible) { // receiveTotal ?>
	<div id="r_receiveTotal" class="form-group">
		<label id="elh_as_receivables_receiveTotal" for="x_receiveTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->receiveTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->receiveTotal->CellAttributes() ?>>
<span id="el_as_receivables_receiveTotal">
<input type="text" data-table="as_receivables" data-field="x_receiveTotal" name="x_receiveTotal" id="x_receiveTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->receiveTotal->getPlaceHolder()) ?>" value="<?php echo $as_receivables->receiveTotal->EditValue ?>"<?php echo $as_receivables->receiveTotal->EditAttributes() ?>>
</span>
<?php echo $as_receivables->receiveTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->incomingTotal->Visible) { // incomingTotal ?>
	<div id="r_incomingTotal" class="form-group">
		<label id="elh_as_receivables_incomingTotal" for="x_incomingTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->incomingTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->incomingTotal->CellAttributes() ?>>
<span id="el_as_receivables_incomingTotal">
<input type="text" data-table="as_receivables" data-field="x_incomingTotal" name="x_incomingTotal" id="x_incomingTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->incomingTotal->getPlaceHolder()) ?>" value="<?php echo $as_receivables->incomingTotal->EditValue ?>"<?php echo $as_receivables->incomingTotal->EditAttributes() ?>>
</span>
<?php echo $as_receivables->incomingTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->reductionTotal->Visible) { // reductionTotal ?>
	<div id="r_reductionTotal" class="form-group">
		<label id="elh_as_receivables_reductionTotal" for="x_reductionTotal" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->reductionTotal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->reductionTotal->CellAttributes() ?>>
<span id="el_as_receivables_reductionTotal">
<input type="text" data-table="as_receivables" data-field="x_reductionTotal" name="x_reductionTotal" id="x_reductionTotal" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->reductionTotal->getPlaceHolder()) ?>" value="<?php echo $as_receivables->reductionTotal->EditValue ?>"<?php echo $as_receivables->reductionTotal->EditAttributes() ?>>
</span>
<?php echo $as_receivables->reductionTotal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_as_receivables_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->status->CellAttributes() ?>>
<span id="el_as_receivables_status">
<input type="text" data-table="as_receivables" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_receivables->status->getPlaceHolder()) ?>" value="<?php echo $as_receivables->status->EditValue ?>"<?php echo $as_receivables->status->EditAttributes() ?>>
</span>
<?php echo $as_receivables->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_receivables_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->staffID->CellAttributes() ?>>
<span id="el_as_receivables_staffID">
<input type="text" data-table="as_receivables" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->staffID->getPlaceHolder()) ?>" value="<?php echo $as_receivables->staffID->EditValue ?>"<?php echo $as_receivables->staffID->EditAttributes() ?>>
</span>
<?php echo $as_receivables->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_receivables_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->staffName->CellAttributes() ?>>
<span id="el_as_receivables_staffName">
<input type="text" data-table="as_receivables" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_receivables->staffName->getPlaceHolder()) ?>" value="<?php echo $as_receivables->staffName->EditValue ?>"<?php echo $as_receivables->staffName->EditAttributes() ?>>
</span>
<?php echo $as_receivables->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_receivables_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->createdDate->CellAttributes() ?>>
<span id="el_as_receivables_createdDate">
<input type="text" data-table="as_receivables" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_receivables->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_receivables->createdDate->EditValue ?>"<?php echo $as_receivables->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_receivables->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_receivables_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->createdUserID->CellAttributes() ?>>
<span id="el_as_receivables_createdUserID">
<input type="text" data-table="as_receivables" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_receivables->createdUserID->EditValue ?>"<?php echo $as_receivables->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_receivables->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_receivables_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->modifiedDate->CellAttributes() ?>>
<span id="el_as_receivables_modifiedDate">
<input type="text" data-table="as_receivables" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_receivables->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_receivables->modifiedDate->EditValue ?>"<?php echo $as_receivables->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_receivables->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_receivables->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_receivables_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_receivables->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_receivables->modifiedUserID->CellAttributes() ?>>
<span id="el_as_receivables_modifiedUserID">
<input type="text" data-table="as_receivables" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_receivables->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_receivables->modifiedUserID->EditValue ?>"<?php echo $as_receivables->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_receivables->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_receivables_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_receivables_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_receivables_edit->Pager)) $as_receivables_edit->Pager = new cPrevNextPager($as_receivables_edit->StartRec, $as_receivables_edit->DisplayRecs, $as_receivables_edit->TotalRecs) ?>
<?php if ($as_receivables_edit->Pager->RecordCount > 0 && $as_receivables_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_receivables_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_receivables_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_receivables_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_receivables_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_receivables_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_receivables_edit->PageUrl() ?>start=<?php echo $as_receivables_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_receivables_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_receivablesedit.Init();
</script>
<?php
$as_receivables_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_receivables_edit->Page_Terminate();
?>
