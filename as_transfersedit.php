<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_transfersinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_transfers_edit = NULL; // Initialize page object first

class cas_transfers_edit extends cas_transfers {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_transfers';

	// Page object name
	var $PageObjName = 'as_transfers_edit';

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

		// Table object (as_transfers)
		if (!isset($GLOBALS["as_transfers"]) || get_class($GLOBALS["as_transfers"]) == "cas_transfers") {
			$GLOBALS["as_transfers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_transfers"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_transfers', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_transferslist.php"));
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
		$this->transferID->SetVisibility();
		$this->transferID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->transferCode->SetVisibility();
		$this->transferFaktur->SetVisibility();
		$this->ref->SetVisibility();
		$this->trxDate->SetVisibility();
		$this->note->SetVisibility();
		$this->transferFrom->SetVisibility();
		$this->transferTo->SetVisibility();
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
		global $EW_EXPORT, $as_transfers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_transfers);
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
		if (@$_GET["transferID"] <> "") {
			$this->transferID->setQueryStringValue($_GET["transferID"]);
			$this->RecKey["transferID"] = $this->transferID->QueryStringValue;
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
			$this->Page_Terminate("as_transferslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->transferID->CurrentValue) == strval($this->Recordset->fields('transferID'))) {
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
					$this->Page_Terminate("as_transferslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_transferslist.php")
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
		if (!$this->transferID->FldIsDetailKey)
			$this->transferID->setFormValue($objForm->GetValue("x_transferID"));
		if (!$this->transferCode->FldIsDetailKey) {
			$this->transferCode->setFormValue($objForm->GetValue("x_transferCode"));
		}
		if (!$this->transferFaktur->FldIsDetailKey) {
			$this->transferFaktur->setFormValue($objForm->GetValue("x_transferFaktur"));
		}
		if (!$this->ref->FldIsDetailKey) {
			$this->ref->setFormValue($objForm->GetValue("x_ref"));
		}
		if (!$this->trxDate->FldIsDetailKey) {
			$this->trxDate->setFormValue($objForm->GetValue("x_trxDate"));
			$this->trxDate->CurrentValue = ew_UnFormatDateTime($this->trxDate->CurrentValue, 0);
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
		}
		if (!$this->transferFrom->FldIsDetailKey) {
			$this->transferFrom->setFormValue($objForm->GetValue("x_transferFrom"));
		}
		if (!$this->transferTo->FldIsDetailKey) {
			$this->transferTo->setFormValue($objForm->GetValue("x_transferTo"));
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
		$this->transferID->CurrentValue = $this->transferID->FormValue;
		$this->transferCode->CurrentValue = $this->transferCode->FormValue;
		$this->transferFaktur->CurrentValue = $this->transferFaktur->FormValue;
		$this->ref->CurrentValue = $this->ref->FormValue;
		$this->trxDate->CurrentValue = $this->trxDate->FormValue;
		$this->trxDate->CurrentValue = ew_UnFormatDateTime($this->trxDate->CurrentValue, 0);
		$this->note->CurrentValue = $this->note->FormValue;
		$this->transferFrom->CurrentValue = $this->transferFrom->FormValue;
		$this->transferTo->CurrentValue = $this->transferTo->FormValue;
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
		$this->transferID->setDbValue($rs->fields('transferID'));
		$this->transferCode->setDbValue($rs->fields('transferCode'));
		$this->transferFaktur->setDbValue($rs->fields('transferFaktur'));
		$this->ref->setDbValue($rs->fields('ref'));
		$this->trxDate->setDbValue($rs->fields('trxDate'));
		$this->note->setDbValue($rs->fields('note'));
		$this->transferFrom->setDbValue($rs->fields('transferFrom'));
		$this->transferTo->setDbValue($rs->fields('transferTo'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->transferID->DbValue = $row['transferID'];
		$this->transferCode->DbValue = $row['transferCode'];
		$this->transferFaktur->DbValue = $row['transferFaktur'];
		$this->ref->DbValue = $row['ref'];
		$this->trxDate->DbValue = $row['trxDate'];
		$this->note->DbValue = $row['note'];
		$this->transferFrom->DbValue = $row['transferFrom'];
		$this->transferTo->DbValue = $row['transferTo'];
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
		// transferID
		// transferCode
		// transferFaktur
		// ref
		// trxDate
		// note
		// transferFrom
		// transferTo
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// transferID
		$this->transferID->ViewValue = $this->transferID->CurrentValue;
		$this->transferID->ViewCustomAttributes = "";

		// transferCode
		$this->transferCode->ViewValue = $this->transferCode->CurrentValue;
		$this->transferCode->ViewCustomAttributes = "";

		// transferFaktur
		$this->transferFaktur->ViewValue = $this->transferFaktur->CurrentValue;
		$this->transferFaktur->ViewCustomAttributes = "";

		// ref
		$this->ref->ViewValue = $this->ref->CurrentValue;
		$this->ref->ViewCustomAttributes = "";

		// trxDate
		$this->trxDate->ViewValue = $this->trxDate->CurrentValue;
		$this->trxDate->ViewValue = ew_FormatDateTime($this->trxDate->ViewValue, 0);
		$this->trxDate->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// transferFrom
		$this->transferFrom->ViewValue = $this->transferFrom->CurrentValue;
		$this->transferFrom->ViewCustomAttributes = "";

		// transferTo
		$this->transferTo->ViewValue = $this->transferTo->CurrentValue;
		$this->transferTo->ViewCustomAttributes = "";

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

			// transferID
			$this->transferID->LinkCustomAttributes = "";
			$this->transferID->HrefValue = "";
			$this->transferID->TooltipValue = "";

			// transferCode
			$this->transferCode->LinkCustomAttributes = "";
			$this->transferCode->HrefValue = "";
			$this->transferCode->TooltipValue = "";

			// transferFaktur
			$this->transferFaktur->LinkCustomAttributes = "";
			$this->transferFaktur->HrefValue = "";
			$this->transferFaktur->TooltipValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";
			$this->ref->TooltipValue = "";

			// trxDate
			$this->trxDate->LinkCustomAttributes = "";
			$this->trxDate->HrefValue = "";
			$this->trxDate->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

			// transferFrom
			$this->transferFrom->LinkCustomAttributes = "";
			$this->transferFrom->HrefValue = "";
			$this->transferFrom->TooltipValue = "";

			// transferTo
			$this->transferTo->LinkCustomAttributes = "";
			$this->transferTo->HrefValue = "";
			$this->transferTo->TooltipValue = "";

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

			// transferID
			$this->transferID->EditAttrs["class"] = "form-control";
			$this->transferID->EditCustomAttributes = "";
			$this->transferID->EditValue = $this->transferID->CurrentValue;
			$this->transferID->ViewCustomAttributes = "";

			// transferCode
			$this->transferCode->EditAttrs["class"] = "form-control";
			$this->transferCode->EditCustomAttributes = "";
			$this->transferCode->EditValue = ew_HtmlEncode($this->transferCode->CurrentValue);
			$this->transferCode->PlaceHolder = ew_RemoveHtml($this->transferCode->FldCaption());

			// transferFaktur
			$this->transferFaktur->EditAttrs["class"] = "form-control";
			$this->transferFaktur->EditCustomAttributes = "";
			$this->transferFaktur->EditValue = ew_HtmlEncode($this->transferFaktur->CurrentValue);
			$this->transferFaktur->PlaceHolder = ew_RemoveHtml($this->transferFaktur->FldCaption());

			// ref
			$this->ref->EditAttrs["class"] = "form-control";
			$this->ref->EditCustomAttributes = "";
			$this->ref->EditValue = ew_HtmlEncode($this->ref->CurrentValue);
			$this->ref->PlaceHolder = ew_RemoveHtml($this->ref->FldCaption());

			// trxDate
			$this->trxDate->EditAttrs["class"] = "form-control";
			$this->trxDate->EditCustomAttributes = "";
			$this->trxDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->trxDate->CurrentValue, 8));
			$this->trxDate->PlaceHolder = ew_RemoveHtml($this->trxDate->FldCaption());

			// note
			$this->note->EditAttrs["class"] = "form-control";
			$this->note->EditCustomAttributes = "";
			$this->note->EditValue = ew_HtmlEncode($this->note->CurrentValue);
			$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

			// transferFrom
			$this->transferFrom->EditAttrs["class"] = "form-control";
			$this->transferFrom->EditCustomAttributes = "";
			$this->transferFrom->EditValue = ew_HtmlEncode($this->transferFrom->CurrentValue);
			$this->transferFrom->PlaceHolder = ew_RemoveHtml($this->transferFrom->FldCaption());

			// transferTo
			$this->transferTo->EditAttrs["class"] = "form-control";
			$this->transferTo->EditCustomAttributes = "";
			$this->transferTo->EditValue = ew_HtmlEncode($this->transferTo->CurrentValue);
			$this->transferTo->PlaceHolder = ew_RemoveHtml($this->transferTo->FldCaption());

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
			// transferID

			$this->transferID->LinkCustomAttributes = "";
			$this->transferID->HrefValue = "";

			// transferCode
			$this->transferCode->LinkCustomAttributes = "";
			$this->transferCode->HrefValue = "";

			// transferFaktur
			$this->transferFaktur->LinkCustomAttributes = "";
			$this->transferFaktur->HrefValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";

			// trxDate
			$this->trxDate->LinkCustomAttributes = "";
			$this->trxDate->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

			// transferFrom
			$this->transferFrom->LinkCustomAttributes = "";
			$this->transferFrom->HrefValue = "";

			// transferTo
			$this->transferTo->LinkCustomAttributes = "";
			$this->transferTo->HrefValue = "";

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
		if (!$this->transferCode->FldIsDetailKey && !is_null($this->transferCode->FormValue) && $this->transferCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transferCode->FldCaption(), $this->transferCode->ReqErrMsg));
		}
		if (!$this->transferFaktur->FldIsDetailKey && !is_null($this->transferFaktur->FormValue) && $this->transferFaktur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transferFaktur->FldCaption(), $this->transferFaktur->ReqErrMsg));
		}
		if (!$this->ref->FldIsDetailKey && !is_null($this->ref->FormValue) && $this->ref->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ref->FldCaption(), $this->ref->ReqErrMsg));
		}
		if (!$this->trxDate->FldIsDetailKey && !is_null($this->trxDate->FormValue) && $this->trxDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->trxDate->FldCaption(), $this->trxDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->trxDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->trxDate->FldErrMsg());
		}
		if (!$this->note->FldIsDetailKey && !is_null($this->note->FormValue) && $this->note->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->note->FldCaption(), $this->note->ReqErrMsg));
		}
		if (!$this->transferFrom->FldIsDetailKey && !is_null($this->transferFrom->FormValue) && $this->transferFrom->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transferFrom->FldCaption(), $this->transferFrom->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->transferFrom->FormValue)) {
			ew_AddMessage($gsFormError, $this->transferFrom->FldErrMsg());
		}
		if (!$this->transferTo->FldIsDetailKey && !is_null($this->transferTo->FormValue) && $this->transferTo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transferTo->FldCaption(), $this->transferTo->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->transferTo->FormValue)) {
			ew_AddMessage($gsFormError, $this->transferTo->FldErrMsg());
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

			// transferCode
			$this->transferCode->SetDbValueDef($rsnew, $this->transferCode->CurrentValue, "", $this->transferCode->ReadOnly);

			// transferFaktur
			$this->transferFaktur->SetDbValueDef($rsnew, $this->transferFaktur->CurrentValue, "", $this->transferFaktur->ReadOnly);

			// ref
			$this->ref->SetDbValueDef($rsnew, $this->ref->CurrentValue, "", $this->ref->ReadOnly);

			// trxDate
			$this->trxDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->trxDate->CurrentValue, 0), ew_CurrentDate(), $this->trxDate->ReadOnly);

			// note
			$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", $this->note->ReadOnly);

			// transferFrom
			$this->transferFrom->SetDbValueDef($rsnew, $this->transferFrom->CurrentValue, 0, $this->transferFrom->ReadOnly);

			// transferTo
			$this->transferTo->SetDbValueDef($rsnew, $this->transferTo->CurrentValue, 0, $this->transferTo->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_transferslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_transfers_edit)) $as_transfers_edit = new cas_transfers_edit();

// Page init
$as_transfers_edit->Page_Init();

// Page main
$as_transfers_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_transfers_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_transfersedit = new ew_Form("fas_transfersedit", "edit");

// Validate form
fas_transfersedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_transferCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->transferCode->FldCaption(), $as_transfers->transferCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transferFaktur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->transferFaktur->FldCaption(), $as_transfers->transferFaktur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ref");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->ref->FldCaption(), $as_transfers->ref->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_trxDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->trxDate->FldCaption(), $as_transfers->trxDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_trxDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->trxDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->note->FldCaption(), $as_transfers->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transferFrom");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->transferFrom->FldCaption(), $as_transfers->transferFrom->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transferFrom");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->transferFrom->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_transferTo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->transferTo->FldCaption(), $as_transfers->transferTo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transferTo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->transferTo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->createdDate->FldCaption(), $as_transfers->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->createdUserID->FldCaption(), $as_transfers->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->modifiedDate->FldCaption(), $as_transfers->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_transfers->modifiedUserID->FldCaption(), $as_transfers->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_transfers->modifiedUserID->FldErrMsg()) ?>");

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
fas_transfersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_transfersedit.ValidateRequired = true;
<?php } else { ?>
fas_transfersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_transfers_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_transfers_edit->ShowPageHeader(); ?>
<?php
$as_transfers_edit->ShowMessage();
?>
<?php if (!$as_transfers_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_transfers_edit->Pager)) $as_transfers_edit->Pager = new cPrevNextPager($as_transfers_edit->StartRec, $as_transfers_edit->DisplayRecs, $as_transfers_edit->TotalRecs) ?>
<?php if ($as_transfers_edit->Pager->RecordCount > 0 && $as_transfers_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_transfers_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_transfers_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_transfers_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_transfers_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_transfers_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_transfers_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_transfersedit" id="fas_transfersedit" class="<?php echo $as_transfers_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_transfers_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_transfers_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_transfers">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_transfers_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_transfers->transferID->Visible) { // transferID ?>
	<div id="r_transferID" class="form-group">
		<label id="elh_as_transfers_transferID" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->transferID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->transferID->CellAttributes() ?>>
<span id="el_as_transfers_transferID">
<span<?php echo $as_transfers->transferID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_transfers->transferID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_transfers" data-field="x_transferID" name="x_transferID" id="x_transferID" value="<?php echo ew_HtmlEncode($as_transfers->transferID->CurrentValue) ?>">
<?php echo $as_transfers->transferID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->transferCode->Visible) { // transferCode ?>
	<div id="r_transferCode" class="form-group">
		<label id="elh_as_transfers_transferCode" for="x_transferCode" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->transferCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->transferCode->CellAttributes() ?>>
<span id="el_as_transfers_transferCode">
<input type="text" data-table="as_transfers" data-field="x_transferCode" name="x_transferCode" id="x_transferCode" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($as_transfers->transferCode->getPlaceHolder()) ?>" value="<?php echo $as_transfers->transferCode->EditValue ?>"<?php echo $as_transfers->transferCode->EditAttributes() ?>>
</span>
<?php echo $as_transfers->transferCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->transferFaktur->Visible) { // transferFaktur ?>
	<div id="r_transferFaktur" class="form-group">
		<label id="elh_as_transfers_transferFaktur" for="x_transferFaktur" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->transferFaktur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->transferFaktur->CellAttributes() ?>>
<span id="el_as_transfers_transferFaktur">
<input type="text" data-table="as_transfers" data-field="x_transferFaktur" name="x_transferFaktur" id="x_transferFaktur" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_transfers->transferFaktur->getPlaceHolder()) ?>" value="<?php echo $as_transfers->transferFaktur->EditValue ?>"<?php echo $as_transfers->transferFaktur->EditAttributes() ?>>
</span>
<?php echo $as_transfers->transferFaktur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->ref->Visible) { // ref ?>
	<div id="r_ref" class="form-group">
		<label id="elh_as_transfers_ref" for="x_ref" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->ref->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->ref->CellAttributes() ?>>
<span id="el_as_transfers_ref">
<input type="text" data-table="as_transfers" data-field="x_ref" name="x_ref" id="x_ref" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_transfers->ref->getPlaceHolder()) ?>" value="<?php echo $as_transfers->ref->EditValue ?>"<?php echo $as_transfers->ref->EditAttributes() ?>>
</span>
<?php echo $as_transfers->ref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->trxDate->Visible) { // trxDate ?>
	<div id="r_trxDate" class="form-group">
		<label id="elh_as_transfers_trxDate" for="x_trxDate" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->trxDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->trxDate->CellAttributes() ?>>
<span id="el_as_transfers_trxDate">
<input type="text" data-table="as_transfers" data-field="x_trxDate" name="x_trxDate" id="x_trxDate" placeholder="<?php echo ew_HtmlEncode($as_transfers->trxDate->getPlaceHolder()) ?>" value="<?php echo $as_transfers->trxDate->EditValue ?>"<?php echo $as_transfers->trxDate->EditAttributes() ?>>
</span>
<?php echo $as_transfers->trxDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_transfers_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->note->CellAttributes() ?>>
<span id="el_as_transfers_note">
<textarea data-table="as_transfers" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_transfers->note->getPlaceHolder()) ?>"<?php echo $as_transfers->note->EditAttributes() ?>><?php echo $as_transfers->note->EditValue ?></textarea>
</span>
<?php echo $as_transfers->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->transferFrom->Visible) { // transferFrom ?>
	<div id="r_transferFrom" class="form-group">
		<label id="elh_as_transfers_transferFrom" for="x_transferFrom" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->transferFrom->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->transferFrom->CellAttributes() ?>>
<span id="el_as_transfers_transferFrom">
<input type="text" data-table="as_transfers" data-field="x_transferFrom" name="x_transferFrom" id="x_transferFrom" size="30" placeholder="<?php echo ew_HtmlEncode($as_transfers->transferFrom->getPlaceHolder()) ?>" value="<?php echo $as_transfers->transferFrom->EditValue ?>"<?php echo $as_transfers->transferFrom->EditAttributes() ?>>
</span>
<?php echo $as_transfers->transferFrom->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->transferTo->Visible) { // transferTo ?>
	<div id="r_transferTo" class="form-group">
		<label id="elh_as_transfers_transferTo" for="x_transferTo" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->transferTo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->transferTo->CellAttributes() ?>>
<span id="el_as_transfers_transferTo">
<input type="text" data-table="as_transfers" data-field="x_transferTo" name="x_transferTo" id="x_transferTo" size="30" placeholder="<?php echo ew_HtmlEncode($as_transfers->transferTo->getPlaceHolder()) ?>" value="<?php echo $as_transfers->transferTo->EditValue ?>"<?php echo $as_transfers->transferTo->EditAttributes() ?>>
</span>
<?php echo $as_transfers->transferTo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_transfers_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->createdDate->CellAttributes() ?>>
<span id="el_as_transfers_createdDate">
<input type="text" data-table="as_transfers" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_transfers->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_transfers->createdDate->EditValue ?>"<?php echo $as_transfers->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_transfers->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_transfers_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->createdUserID->CellAttributes() ?>>
<span id="el_as_transfers_createdUserID">
<input type="text" data-table="as_transfers" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_transfers->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_transfers->createdUserID->EditValue ?>"<?php echo $as_transfers->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_transfers->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_transfers_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->modifiedDate->CellAttributes() ?>>
<span id="el_as_transfers_modifiedDate">
<input type="text" data-table="as_transfers" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_transfers->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_transfers->modifiedDate->EditValue ?>"<?php echo $as_transfers->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_transfers->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_transfers->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_transfers_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_transfers->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_transfers->modifiedUserID->CellAttributes() ?>>
<span id="el_as_transfers_modifiedUserID">
<input type="text" data-table="as_transfers" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_transfers->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_transfers->modifiedUserID->EditValue ?>"<?php echo $as_transfers->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_transfers->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_transfers_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_transfers_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_transfers_edit->Pager)) $as_transfers_edit->Pager = new cPrevNextPager($as_transfers_edit->StartRec, $as_transfers_edit->DisplayRecs, $as_transfers_edit->TotalRecs) ?>
<?php if ($as_transfers_edit->Pager->RecordCount > 0 && $as_transfers_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_transfers_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_transfers_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_transfers_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_transfers_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_transfers_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_transfers_edit->PageUrl() ?>start=<?php echo $as_transfers_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_transfers_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_transfersedit.Init();
</script>
<?php
$as_transfers_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_transfers_edit->Page_Terminate();
?>
