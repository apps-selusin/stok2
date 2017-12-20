<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_detail_doinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_detail_do_edit = NULL; // Initialize page object first

class cas_detail_do_edit extends cas_detail_do {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_detail_do';

	// Page object name
	var $PageObjName = 'as_detail_do_edit';

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

		// Table object (as_detail_do)
		if (!isset($GLOBALS["as_detail_do"]) || get_class($GLOBALS["as_detail_do"]) == "cas_detail_do") {
			$GLOBALS["as_detail_do"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_detail_do"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_detail_do', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_detail_dolist.php"));
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
		$this->doID->SetVisibility();
		$this->doID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->doNo->SetVisibility();
		$this->doFaktur->SetVisibility();
		$this->productID->SetVisibility();
		$this->productName->SetVisibility();
		$this->price->SetVisibility();
		$this->qty->SetVisibility();
		$this->deliveredQty->SetVisibility();
		$this->deliveredStatus->SetVisibility();
		$this->factoryID->SetVisibility();
		$this->factoryName->SetVisibility();
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
		global $EW_EXPORT, $as_detail_do;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_detail_do);
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
		if (@$_GET["doID"] <> "") {
			$this->doID->setQueryStringValue($_GET["doID"]);
			$this->RecKey["doID"] = $this->doID->QueryStringValue;
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
			$this->Page_Terminate("as_detail_dolist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->doID->CurrentValue) == strval($this->Recordset->fields('doID'))) {
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
					$this->Page_Terminate("as_detail_dolist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_detail_dolist.php")
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
		if (!$this->doID->FldIsDetailKey)
			$this->doID->setFormValue($objForm->GetValue("x_doID"));
		if (!$this->doNo->FldIsDetailKey) {
			$this->doNo->setFormValue($objForm->GetValue("x_doNo"));
		}
		if (!$this->doFaktur->FldIsDetailKey) {
			$this->doFaktur->setFormValue($objForm->GetValue("x_doFaktur"));
		}
		if (!$this->productID->FldIsDetailKey) {
			$this->productID->setFormValue($objForm->GetValue("x_productID"));
		}
		if (!$this->productName->FldIsDetailKey) {
			$this->productName->setFormValue($objForm->GetValue("x_productName"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->qty->FldIsDetailKey) {
			$this->qty->setFormValue($objForm->GetValue("x_qty"));
		}
		if (!$this->deliveredQty->FldIsDetailKey) {
			$this->deliveredQty->setFormValue($objForm->GetValue("x_deliveredQty"));
		}
		if (!$this->deliveredStatus->FldIsDetailKey) {
			$this->deliveredStatus->setFormValue($objForm->GetValue("x_deliveredStatus"));
		}
		if (!$this->factoryID->FldIsDetailKey) {
			$this->factoryID->setFormValue($objForm->GetValue("x_factoryID"));
		}
		if (!$this->factoryName->FldIsDetailKey) {
			$this->factoryName->setFormValue($objForm->GetValue("x_factoryName"));
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
		$this->LoadRow();
		$this->doID->CurrentValue = $this->doID->FormValue;
		$this->doNo->CurrentValue = $this->doNo->FormValue;
		$this->doFaktur->CurrentValue = $this->doFaktur->FormValue;
		$this->productID->CurrentValue = $this->productID->FormValue;
		$this->productName->CurrentValue = $this->productName->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->qty->CurrentValue = $this->qty->FormValue;
		$this->deliveredQty->CurrentValue = $this->deliveredQty->FormValue;
		$this->deliveredStatus->CurrentValue = $this->deliveredStatus->FormValue;
		$this->factoryID->CurrentValue = $this->factoryID->FormValue;
		$this->factoryName->CurrentValue = $this->factoryName->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
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
		$this->doID->setDbValue($rs->fields('doID'));
		$this->doNo->setDbValue($rs->fields('doNo'));
		$this->doFaktur->setDbValue($rs->fields('doFaktur'));
		$this->productID->setDbValue($rs->fields('productID'));
		$this->productName->setDbValue($rs->fields('productName'));
		$this->price->setDbValue($rs->fields('price'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->deliveredQty->setDbValue($rs->fields('deliveredQty'));
		$this->deliveredStatus->setDbValue($rs->fields('deliveredStatus'));
		$this->factoryID->setDbValue($rs->fields('factoryID'));
		$this->factoryName->setDbValue($rs->fields('factoryName'));
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
		$this->doID->DbValue = $row['doID'];
		$this->doNo->DbValue = $row['doNo'];
		$this->doFaktur->DbValue = $row['doFaktur'];
		$this->productID->DbValue = $row['productID'];
		$this->productName->DbValue = $row['productName'];
		$this->price->DbValue = $row['price'];
		$this->qty->DbValue = $row['qty'];
		$this->deliveredQty->DbValue = $row['deliveredQty'];
		$this->deliveredStatus->DbValue = $row['deliveredStatus'];
		$this->factoryID->DbValue = $row['factoryID'];
		$this->factoryName->DbValue = $row['factoryName'];
		$this->note->DbValue = $row['note'];
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

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// doID
		// doNo
		// doFaktur
		// productID
		// productName
		// price
		// qty
		// deliveredQty
		// deliveredStatus
		// factoryID
		// factoryName
		// note
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// doID
		$this->doID->ViewValue = $this->doID->CurrentValue;
		$this->doID->ViewCustomAttributes = "";

		// doNo
		$this->doNo->ViewValue = $this->doNo->CurrentValue;
		$this->doNo->ViewCustomAttributes = "";

		// doFaktur
		$this->doFaktur->ViewValue = $this->doFaktur->CurrentValue;
		$this->doFaktur->ViewCustomAttributes = "";

		// productID
		$this->productID->ViewValue = $this->productID->CurrentValue;
		$this->productID->ViewCustomAttributes = "";

		// productName
		$this->productName->ViewValue = $this->productName->CurrentValue;
		$this->productName->ViewCustomAttributes = "";

		// price
		$this->price->ViewValue = $this->price->CurrentValue;
		$this->price->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewCustomAttributes = "";

		// deliveredQty
		$this->deliveredQty->ViewValue = $this->deliveredQty->CurrentValue;
		$this->deliveredQty->ViewCustomAttributes = "";

		// deliveredStatus
		$this->deliveredStatus->ViewValue = $this->deliveredStatus->CurrentValue;
		$this->deliveredStatus->ViewCustomAttributes = "";

		// factoryID
		$this->factoryID->ViewValue = $this->factoryID->CurrentValue;
		$this->factoryID->ViewCustomAttributes = "";

		// factoryName
		$this->factoryName->ViewValue = $this->factoryName->CurrentValue;
		$this->factoryName->ViewCustomAttributes = "";

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

			// doID
			$this->doID->LinkCustomAttributes = "";
			$this->doID->HrefValue = "";
			$this->doID->TooltipValue = "";

			// doNo
			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";
			$this->doNo->TooltipValue = "";

			// doFaktur
			$this->doFaktur->LinkCustomAttributes = "";
			$this->doFaktur->HrefValue = "";
			$this->doFaktur->TooltipValue = "";

			// productID
			$this->productID->LinkCustomAttributes = "";
			$this->productID->HrefValue = "";
			$this->productID->TooltipValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";
			$this->productName->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";
			$this->qty->TooltipValue = "";

			// deliveredQty
			$this->deliveredQty->LinkCustomAttributes = "";
			$this->deliveredQty->HrefValue = "";
			$this->deliveredQty->TooltipValue = "";

			// deliveredStatus
			$this->deliveredStatus->LinkCustomAttributes = "";
			$this->deliveredStatus->HrefValue = "";
			$this->deliveredStatus->TooltipValue = "";

			// factoryID
			$this->factoryID->LinkCustomAttributes = "";
			$this->factoryID->HrefValue = "";
			$this->factoryID->TooltipValue = "";

			// factoryName
			$this->factoryName->LinkCustomAttributes = "";
			$this->factoryName->HrefValue = "";
			$this->factoryName->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// doID
			$this->doID->EditAttrs["class"] = "form-control";
			$this->doID->EditCustomAttributes = "";
			$this->doID->EditValue = $this->doID->CurrentValue;
			$this->doID->ViewCustomAttributes = "";

			// doNo
			$this->doNo->EditAttrs["class"] = "form-control";
			$this->doNo->EditCustomAttributes = "";
			$this->doNo->EditValue = ew_HtmlEncode($this->doNo->CurrentValue);
			$this->doNo->PlaceHolder = ew_RemoveHtml($this->doNo->FldCaption());

			// doFaktur
			$this->doFaktur->EditAttrs["class"] = "form-control";
			$this->doFaktur->EditCustomAttributes = "";
			$this->doFaktur->EditValue = ew_HtmlEncode($this->doFaktur->CurrentValue);
			$this->doFaktur->PlaceHolder = ew_RemoveHtml($this->doFaktur->FldCaption());

			// productID
			$this->productID->EditAttrs["class"] = "form-control";
			$this->productID->EditCustomAttributes = "";
			$this->productID->EditValue = ew_HtmlEncode($this->productID->CurrentValue);
			$this->productID->PlaceHolder = ew_RemoveHtml($this->productID->FldCaption());

			// productName
			$this->productName->EditAttrs["class"] = "form-control";
			$this->productName->EditCustomAttributes = "";
			$this->productName->EditValue = ew_HtmlEncode($this->productName->CurrentValue);
			$this->productName->PlaceHolder = ew_RemoveHtml($this->productName->FldCaption());

			// price
			$this->price->EditAttrs["class"] = "form-control";
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());

			// deliveredQty
			$this->deliveredQty->EditAttrs["class"] = "form-control";
			$this->deliveredQty->EditCustomAttributes = "";
			$this->deliveredQty->EditValue = ew_HtmlEncode($this->deliveredQty->CurrentValue);
			$this->deliveredQty->PlaceHolder = ew_RemoveHtml($this->deliveredQty->FldCaption());

			// deliveredStatus
			$this->deliveredStatus->EditAttrs["class"] = "form-control";
			$this->deliveredStatus->EditCustomAttributes = "";
			$this->deliveredStatus->EditValue = ew_HtmlEncode($this->deliveredStatus->CurrentValue);
			$this->deliveredStatus->PlaceHolder = ew_RemoveHtml($this->deliveredStatus->FldCaption());

			// factoryID
			$this->factoryID->EditAttrs["class"] = "form-control";
			$this->factoryID->EditCustomAttributes = "";
			$this->factoryID->EditValue = ew_HtmlEncode($this->factoryID->CurrentValue);
			$this->factoryID->PlaceHolder = ew_RemoveHtml($this->factoryID->FldCaption());

			// factoryName
			$this->factoryName->EditAttrs["class"] = "form-control";
			$this->factoryName->EditCustomAttributes = "";
			$this->factoryName->EditValue = ew_HtmlEncode($this->factoryName->CurrentValue);
			$this->factoryName->PlaceHolder = ew_RemoveHtml($this->factoryName->FldCaption());

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

			// Edit refer script
			// doID

			$this->doID->LinkCustomAttributes = "";
			$this->doID->HrefValue = "";

			// doNo
			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";

			// doFaktur
			$this->doFaktur->LinkCustomAttributes = "";
			$this->doFaktur->HrefValue = "";

			// productID
			$this->productID->LinkCustomAttributes = "";
			$this->productID->HrefValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

			// deliveredQty
			$this->deliveredQty->LinkCustomAttributes = "";
			$this->deliveredQty->HrefValue = "";

			// deliveredStatus
			$this->deliveredStatus->LinkCustomAttributes = "";
			$this->deliveredStatus->HrefValue = "";

			// factoryID
			$this->factoryID->LinkCustomAttributes = "";
			$this->factoryID->HrefValue = "";

			// factoryName
			$this->factoryName->LinkCustomAttributes = "";
			$this->factoryName->HrefValue = "";

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
		if (!$this->doNo->FldIsDetailKey && !is_null($this->doNo->FormValue) && $this->doNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->doNo->FldCaption(), $this->doNo->ReqErrMsg));
		}
		if (!$this->doFaktur->FldIsDetailKey && !is_null($this->doFaktur->FormValue) && $this->doFaktur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->doFaktur->FldCaption(), $this->doFaktur->ReqErrMsg));
		}
		if (!$this->productID->FldIsDetailKey && !is_null($this->productID->FormValue) && $this->productID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->productID->FldCaption(), $this->productID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->productID->FormValue)) {
			ew_AddMessage($gsFormError, $this->productID->FldErrMsg());
		}
		if (!$this->productName->FldIsDetailKey && !is_null($this->productName->FormValue) && $this->productName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->productName->FldCaption(), $this->productName->ReqErrMsg));
		}
		if (!$this->price->FldIsDetailKey && !is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->price->FldCaption(), $this->price->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!$this->qty->FldIsDetailKey && !is_null($this->qty->FormValue) && $this->qty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->qty->FldCaption(), $this->qty->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
		}
		if (!$this->deliveredQty->FldIsDetailKey && !is_null($this->deliveredQty->FormValue) && $this->deliveredQty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->deliveredQty->FldCaption(), $this->deliveredQty->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->deliveredQty->FormValue)) {
			ew_AddMessage($gsFormError, $this->deliveredQty->FldErrMsg());
		}
		if (!$this->deliveredStatus->FldIsDetailKey && !is_null($this->deliveredStatus->FormValue) && $this->deliveredStatus->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->deliveredStatus->FldCaption(), $this->deliveredStatus->ReqErrMsg));
		}
		if (!$this->factoryID->FldIsDetailKey && !is_null($this->factoryID->FormValue) && $this->factoryID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->factoryID->FldCaption(), $this->factoryID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->factoryID->FormValue)) {
			ew_AddMessage($gsFormError, $this->factoryID->FldErrMsg());
		}
		if (!$this->factoryName->FldIsDetailKey && !is_null($this->factoryName->FormValue) && $this->factoryName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->factoryName->FldCaption(), $this->factoryName->ReqErrMsg));
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

			// doNo
			$this->doNo->SetDbValueDef($rsnew, $this->doNo->CurrentValue, "", $this->doNo->ReadOnly);

			// doFaktur
			$this->doFaktur->SetDbValueDef($rsnew, $this->doFaktur->CurrentValue, "", $this->doFaktur->ReadOnly);

			// productID
			$this->productID->SetDbValueDef($rsnew, $this->productID->CurrentValue, 0, $this->productID->ReadOnly);

			// productName
			$this->productName->SetDbValueDef($rsnew, $this->productName->CurrentValue, "", $this->productName->ReadOnly);

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, $this->price->ReadOnly);

			// qty
			$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, $this->qty->ReadOnly);

			// deliveredQty
			$this->deliveredQty->SetDbValueDef($rsnew, $this->deliveredQty->CurrentValue, 0, $this->deliveredQty->ReadOnly);

			// deliveredStatus
			$this->deliveredStatus->SetDbValueDef($rsnew, $this->deliveredStatus->CurrentValue, "", $this->deliveredStatus->ReadOnly);

			// factoryID
			$this->factoryID->SetDbValueDef($rsnew, $this->factoryID->CurrentValue, 0, $this->factoryID->ReadOnly);

			// factoryName
			$this->factoryName->SetDbValueDef($rsnew, $this->factoryName->CurrentValue, "", $this->factoryName->ReadOnly);

			// note
			$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", $this->note->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_detail_dolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_detail_do_edit)) $as_detail_do_edit = new cas_detail_do_edit();

// Page init
$as_detail_do_edit->Page_Init();

// Page main
$as_detail_do_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_detail_do_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_detail_doedit = new ew_Form("fas_detail_doedit", "edit");

// Validate form
fas_detail_doedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_doNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->doNo->FldCaption(), $as_detail_do->doNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_doFaktur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->doFaktur->FldCaption(), $as_detail_do->doFaktur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->productID->FldCaption(), $as_detail_do->productID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->productID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_productName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->productName->FldCaption(), $as_detail_do->productName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->price->FldCaption(), $as_detail_do->price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->qty->FldCaption(), $as_detail_do->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_deliveredQty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->deliveredQty->FldCaption(), $as_detail_do->deliveredQty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_deliveredQty");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->deliveredQty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_deliveredStatus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->deliveredStatus->FldCaption(), $as_detail_do->deliveredStatus->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factoryID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->factoryID->FldCaption(), $as_detail_do->factoryID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factoryID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->factoryID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_factoryName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->factoryName->FldCaption(), $as_detail_do->factoryName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->note->FldCaption(), $as_detail_do->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->createdDate->FldCaption(), $as_detail_do->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->createdUserID->FldCaption(), $as_detail_do->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->modifiedDate->FldCaption(), $as_detail_do->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_detail_do->modifiedUserID->FldCaption(), $as_detail_do->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_detail_do->modifiedUserID->FldErrMsg()) ?>");

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
fas_detail_doedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_detail_doedit.ValidateRequired = true;
<?php } else { ?>
fas_detail_doedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_detail_do_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_detail_do_edit->ShowPageHeader(); ?>
<?php
$as_detail_do_edit->ShowMessage();
?>
<?php if (!$as_detail_do_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_detail_do_edit->Pager)) $as_detail_do_edit->Pager = new cPrevNextPager($as_detail_do_edit->StartRec, $as_detail_do_edit->DisplayRecs, $as_detail_do_edit->TotalRecs) ?>
<?php if ($as_detail_do_edit->Pager->RecordCount > 0 && $as_detail_do_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_detail_do_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_detail_do_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_detail_do_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_detail_do_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_detail_do_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_detail_do_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_detail_doedit" id="fas_detail_doedit" class="<?php echo $as_detail_do_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_detail_do_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_detail_do_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_detail_do">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_detail_do_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_detail_do->doID->Visible) { // doID ?>
	<div id="r_doID" class="form-group">
		<label id="elh_as_detail_do_doID" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->doID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->doID->CellAttributes() ?>>
<span id="el_as_detail_do_doID">
<span<?php echo $as_detail_do->doID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_detail_do->doID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_detail_do" data-field="x_doID" name="x_doID" id="x_doID" value="<?php echo ew_HtmlEncode($as_detail_do->doID->CurrentValue) ?>">
<?php echo $as_detail_do->doID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->doNo->Visible) { // doNo ?>
	<div id="r_doNo" class="form-group">
		<label id="elh_as_detail_do_doNo" for="x_doNo" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->doNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->doNo->CellAttributes() ?>>
<span id="el_as_detail_do_doNo">
<input type="text" data-table="as_detail_do" data-field="x_doNo" name="x_doNo" id="x_doNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_detail_do->doNo->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->doNo->EditValue ?>"<?php echo $as_detail_do->doNo->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->doNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->doFaktur->Visible) { // doFaktur ?>
	<div id="r_doFaktur" class="form-group">
		<label id="elh_as_detail_do_doFaktur" for="x_doFaktur" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->doFaktur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->doFaktur->CellAttributes() ?>>
<span id="el_as_detail_do_doFaktur">
<input type="text" data-table="as_detail_do" data-field="x_doFaktur" name="x_doFaktur" id="x_doFaktur" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_detail_do->doFaktur->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->doFaktur->EditValue ?>"<?php echo $as_detail_do->doFaktur->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->doFaktur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->productID->Visible) { // productID ?>
	<div id="r_productID" class="form-group">
		<label id="elh_as_detail_do_productID" for="x_productID" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->productID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->productID->CellAttributes() ?>>
<span id="el_as_detail_do_productID">
<input type="text" data-table="as_detail_do" data-field="x_productID" name="x_productID" id="x_productID" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->productID->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->productID->EditValue ?>"<?php echo $as_detail_do->productID->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->productID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->productName->Visible) { // productName ?>
	<div id="r_productName" class="form-group">
		<label id="elh_as_detail_do_productName" for="x_productName" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->productName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->productName->CellAttributes() ?>>
<span id="el_as_detail_do_productName">
<input type="text" data-table="as_detail_do" data-field="x_productName" name="x_productName" id="x_productName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_detail_do->productName->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->productName->EditValue ?>"<?php echo $as_detail_do->productName->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->productName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->price->Visible) { // price ?>
	<div id="r_price" class="form-group">
		<label id="elh_as_detail_do_price" for="x_price" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->price->CellAttributes() ?>>
<span id="el_as_detail_do_price">
<input type="text" data-table="as_detail_do" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->price->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->price->EditValue ?>"<?php echo $as_detail_do->price->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->qty->Visible) { // qty ?>
	<div id="r_qty" class="form-group">
		<label id="elh_as_detail_do_qty" for="x_qty" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->qty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->qty->CellAttributes() ?>>
<span id="el_as_detail_do_qty">
<input type="text" data-table="as_detail_do" data-field="x_qty" name="x_qty" id="x_qty" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->qty->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->qty->EditValue ?>"<?php echo $as_detail_do->qty->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->deliveredQty->Visible) { // deliveredQty ?>
	<div id="r_deliveredQty" class="form-group">
		<label id="elh_as_detail_do_deliveredQty" for="x_deliveredQty" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->deliveredQty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->deliveredQty->CellAttributes() ?>>
<span id="el_as_detail_do_deliveredQty">
<input type="text" data-table="as_detail_do" data-field="x_deliveredQty" name="x_deliveredQty" id="x_deliveredQty" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->deliveredQty->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->deliveredQty->EditValue ?>"<?php echo $as_detail_do->deliveredQty->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->deliveredQty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->deliveredStatus->Visible) { // deliveredStatus ?>
	<div id="r_deliveredStatus" class="form-group">
		<label id="elh_as_detail_do_deliveredStatus" for="x_deliveredStatus" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->deliveredStatus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->deliveredStatus->CellAttributes() ?>>
<span id="el_as_detail_do_deliveredStatus">
<input type="text" data-table="as_detail_do" data-field="x_deliveredStatus" name="x_deliveredStatus" id="x_deliveredStatus" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_detail_do->deliveredStatus->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->deliveredStatus->EditValue ?>"<?php echo $as_detail_do->deliveredStatus->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->deliveredStatus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->factoryID->Visible) { // factoryID ?>
	<div id="r_factoryID" class="form-group">
		<label id="elh_as_detail_do_factoryID" for="x_factoryID" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->factoryID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->factoryID->CellAttributes() ?>>
<span id="el_as_detail_do_factoryID">
<input type="text" data-table="as_detail_do" data-field="x_factoryID" name="x_factoryID" id="x_factoryID" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->factoryID->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->factoryID->EditValue ?>"<?php echo $as_detail_do->factoryID->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->factoryID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->factoryName->Visible) { // factoryName ?>
	<div id="r_factoryName" class="form-group">
		<label id="elh_as_detail_do_factoryName" for="x_factoryName" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->factoryName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->factoryName->CellAttributes() ?>>
<span id="el_as_detail_do_factoryName">
<input type="text" data-table="as_detail_do" data-field="x_factoryName" name="x_factoryName" id="x_factoryName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_detail_do->factoryName->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->factoryName->EditValue ?>"<?php echo $as_detail_do->factoryName->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->factoryName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_detail_do_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->note->CellAttributes() ?>>
<span id="el_as_detail_do_note">
<textarea data-table="as_detail_do" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_detail_do->note->getPlaceHolder()) ?>"<?php echo $as_detail_do->note->EditAttributes() ?>><?php echo $as_detail_do->note->EditValue ?></textarea>
</span>
<?php echo $as_detail_do->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_detail_do_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->createdDate->CellAttributes() ?>>
<span id="el_as_detail_do_createdDate">
<input type="text" data-table="as_detail_do" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_detail_do->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->createdDate->EditValue ?>"<?php echo $as_detail_do->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_detail_do_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->createdUserID->CellAttributes() ?>>
<span id="el_as_detail_do_createdUserID">
<input type="text" data-table="as_detail_do" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->createdUserID->EditValue ?>"<?php echo $as_detail_do->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_detail_do_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->modifiedDate->CellAttributes() ?>>
<span id="el_as_detail_do_modifiedDate">
<input type="text" data-table="as_detail_do" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_detail_do->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->modifiedDate->EditValue ?>"<?php echo $as_detail_do->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_detail_do->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_detail_do_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_detail_do->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_detail_do->modifiedUserID->CellAttributes() ?>>
<span id="el_as_detail_do_modifiedUserID">
<input type="text" data-table="as_detail_do" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_detail_do->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_detail_do->modifiedUserID->EditValue ?>"<?php echo $as_detail_do->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_detail_do->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_detail_do_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_detail_do_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_detail_do_edit->Pager)) $as_detail_do_edit->Pager = new cPrevNextPager($as_detail_do_edit->StartRec, $as_detail_do_edit->DisplayRecs, $as_detail_do_edit->TotalRecs) ?>
<?php if ($as_detail_do_edit->Pager->RecordCount > 0 && $as_detail_do_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_detail_do_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_detail_do_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_detail_do_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_detail_do_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_detail_do_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_detail_do_edit->PageUrl() ?>start=<?php echo $as_detail_do_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_detail_do_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_detail_doedit.Init();
</script>
<?php
$as_detail_do_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_detail_do_edit->Page_Terminate();
?>
