<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_bbminfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_bbm_add = NULL; // Initialize page object first

class cas_bbm_add extends cas_bbm {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_bbm';

	// Page object name
	var $PageObjName = 'as_bbm_add';

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

		// Table object (as_bbm)
		if (!isset($GLOBALS["as_bbm"]) || get_class($GLOBALS["as_bbm"]) == "cas_bbm") {
			$GLOBALS["as_bbm"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_bbm"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_bbm', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_bbmlist.php"));
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
		$this->bbmFaktur->SetVisibility();
		$this->bbmNo->SetVisibility();
		$this->spbID->SetVisibility();
		$this->spbNo->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
		$this->supplierAddress->SetVisibility();
		$this->staffID->SetVisibility();
		$this->staffName->SetVisibility();
		$this->receiveDate->SetVisibility();
		$this->orderDate->SetVisibility();
		$this->needDate->SetVisibility();
		$this->total->SetVisibility();
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
		global $EW_EXPORT, $as_bbm;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_bbm);
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
			if (@$_GET["bbmID"] != "") {
				$this->bbmID->setQueryStringValue($_GET["bbmID"]);
				$this->setKey("bbmID", $this->bbmID->CurrentValue); // Set up key
			} else {
				$this->setKey("bbmID", ""); // Clear key
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
					$this->Page_Terminate("as_bbmlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_bbmlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_bbmview.php")
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
		$this->bbmFaktur->CurrentValue = NULL;
		$this->bbmFaktur->OldValue = $this->bbmFaktur->CurrentValue;
		$this->bbmNo->CurrentValue = NULL;
		$this->bbmNo->OldValue = $this->bbmNo->CurrentValue;
		$this->spbID->CurrentValue = NULL;
		$this->spbID->OldValue = $this->spbID->CurrentValue;
		$this->spbNo->CurrentValue = NULL;
		$this->spbNo->OldValue = $this->spbNo->CurrentValue;
		$this->supplierID->CurrentValue = NULL;
		$this->supplierID->OldValue = $this->supplierID->CurrentValue;
		$this->supplierName->CurrentValue = NULL;
		$this->supplierName->OldValue = $this->supplierName->CurrentValue;
		$this->supplierAddress->CurrentValue = NULL;
		$this->supplierAddress->OldValue = $this->supplierAddress->CurrentValue;
		$this->staffID->CurrentValue = NULL;
		$this->staffID->OldValue = $this->staffID->CurrentValue;
		$this->staffName->CurrentValue = NULL;
		$this->staffName->OldValue = $this->staffName->CurrentValue;
		$this->receiveDate->CurrentValue = NULL;
		$this->receiveDate->OldValue = $this->receiveDate->CurrentValue;
		$this->orderDate->CurrentValue = NULL;
		$this->orderDate->OldValue = $this->orderDate->CurrentValue;
		$this->needDate->CurrentValue = NULL;
		$this->needDate->OldValue = $this->needDate->CurrentValue;
		$this->total->CurrentValue = NULL;
		$this->total->OldValue = $this->total->CurrentValue;
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
		if (!$this->bbmFaktur->FldIsDetailKey) {
			$this->bbmFaktur->setFormValue($objForm->GetValue("x_bbmFaktur"));
		}
		if (!$this->bbmNo->FldIsDetailKey) {
			$this->bbmNo->setFormValue($objForm->GetValue("x_bbmNo"));
		}
		if (!$this->spbID->FldIsDetailKey) {
			$this->spbID->setFormValue($objForm->GetValue("x_spbID"));
		}
		if (!$this->spbNo->FldIsDetailKey) {
			$this->spbNo->setFormValue($objForm->GetValue("x_spbNo"));
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
		if (!$this->receiveDate->FldIsDetailKey) {
			$this->receiveDate->setFormValue($objForm->GetValue("x_receiveDate"));
			$this->receiveDate->CurrentValue = ew_UnFormatDateTime($this->receiveDate->CurrentValue, 0);
		}
		if (!$this->orderDate->FldIsDetailKey) {
			$this->orderDate->setFormValue($objForm->GetValue("x_orderDate"));
			$this->orderDate->CurrentValue = ew_UnFormatDateTime($this->orderDate->CurrentValue, 0);
		}
		if (!$this->needDate->FldIsDetailKey) {
			$this->needDate->setFormValue($objForm->GetValue("x_needDate"));
			$this->needDate->CurrentValue = ew_UnFormatDateTime($this->needDate->CurrentValue, 0);
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
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
		$this->bbmFaktur->CurrentValue = $this->bbmFaktur->FormValue;
		$this->bbmNo->CurrentValue = $this->bbmNo->FormValue;
		$this->spbID->CurrentValue = $this->spbID->FormValue;
		$this->spbNo->CurrentValue = $this->spbNo->FormValue;
		$this->supplierID->CurrentValue = $this->supplierID->FormValue;
		$this->supplierName->CurrentValue = $this->supplierName->FormValue;
		$this->supplierAddress->CurrentValue = $this->supplierAddress->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->receiveDate->CurrentValue = $this->receiveDate->FormValue;
		$this->receiveDate->CurrentValue = ew_UnFormatDateTime($this->receiveDate->CurrentValue, 0);
		$this->orderDate->CurrentValue = $this->orderDate->FormValue;
		$this->orderDate->CurrentValue = ew_UnFormatDateTime($this->orderDate->CurrentValue, 0);
		$this->needDate->CurrentValue = $this->needDate->FormValue;
		$this->needDate->CurrentValue = ew_UnFormatDateTime($this->needDate->CurrentValue, 0);
		$this->total->CurrentValue = $this->total->FormValue;
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
		$this->bbmID->setDbValue($rs->fields('bbmID'));
		$this->bbmFaktur->setDbValue($rs->fields('bbmFaktur'));
		$this->bbmNo->setDbValue($rs->fields('bbmNo'));
		$this->spbID->setDbValue($rs->fields('spbID'));
		$this->spbNo->setDbValue($rs->fields('spbNo'));
		$this->supplierID->setDbValue($rs->fields('supplierID'));
		$this->supplierName->setDbValue($rs->fields('supplierName'));
		$this->supplierAddress->setDbValue($rs->fields('supplierAddress'));
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->receiveDate->setDbValue($rs->fields('receiveDate'));
		$this->orderDate->setDbValue($rs->fields('orderDate'));
		$this->needDate->setDbValue($rs->fields('needDate'));
		$this->total->setDbValue($rs->fields('total'));
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
		$this->bbmID->DbValue = $row['bbmID'];
		$this->bbmFaktur->DbValue = $row['bbmFaktur'];
		$this->bbmNo->DbValue = $row['bbmNo'];
		$this->spbID->DbValue = $row['spbID'];
		$this->spbNo->DbValue = $row['spbNo'];
		$this->supplierID->DbValue = $row['supplierID'];
		$this->supplierName->DbValue = $row['supplierName'];
		$this->supplierAddress->DbValue = $row['supplierAddress'];
		$this->staffID->DbValue = $row['staffID'];
		$this->staffName->DbValue = $row['staffName'];
		$this->receiveDate->DbValue = $row['receiveDate'];
		$this->orderDate->DbValue = $row['orderDate'];
		$this->needDate->DbValue = $row['needDate'];
		$this->total->DbValue = $row['total'];
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
		if (strval($this->getKey("bbmID")) <> "")
			$this->bbmID->CurrentValue = $this->getKey("bbmID"); // bbmID
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

		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// bbmID
		// bbmFaktur
		// bbmNo
		// spbID
		// spbNo
		// supplierID
		// supplierName
		// supplierAddress
		// staffID
		// staffName
		// receiveDate
		// orderDate
		// needDate
		// total
		// note
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// bbmID
		$this->bbmID->ViewValue = $this->bbmID->CurrentValue;
		$this->bbmID->ViewCustomAttributes = "";

		// bbmFaktur
		$this->bbmFaktur->ViewValue = $this->bbmFaktur->CurrentValue;
		$this->bbmFaktur->ViewCustomAttributes = "";

		// bbmNo
		$this->bbmNo->ViewValue = $this->bbmNo->CurrentValue;
		$this->bbmNo->ViewCustomAttributes = "";

		// spbID
		$this->spbID->ViewValue = $this->spbID->CurrentValue;
		$this->spbID->ViewCustomAttributes = "";

		// spbNo
		$this->spbNo->ViewValue = $this->spbNo->CurrentValue;
		$this->spbNo->ViewCustomAttributes = "";

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

		// receiveDate
		$this->receiveDate->ViewValue = $this->receiveDate->CurrentValue;
		$this->receiveDate->ViewValue = ew_FormatDateTime($this->receiveDate->ViewValue, 0);
		$this->receiveDate->ViewCustomAttributes = "";

		// orderDate
		$this->orderDate->ViewValue = $this->orderDate->CurrentValue;
		$this->orderDate->ViewValue = ew_FormatDateTime($this->orderDate->ViewValue, 0);
		$this->orderDate->ViewCustomAttributes = "";

		// needDate
		$this->needDate->ViewValue = $this->needDate->CurrentValue;
		$this->needDate->ViewValue = ew_FormatDateTime($this->needDate->ViewValue, 0);
		$this->needDate->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

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

			// bbmFaktur
			$this->bbmFaktur->LinkCustomAttributes = "";
			$this->bbmFaktur->HrefValue = "";
			$this->bbmFaktur->TooltipValue = "";

			// bbmNo
			$this->bbmNo->LinkCustomAttributes = "";
			$this->bbmNo->HrefValue = "";
			$this->bbmNo->TooltipValue = "";

			// spbID
			$this->spbID->LinkCustomAttributes = "";
			$this->spbID->HrefValue = "";
			$this->spbID->TooltipValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";
			$this->spbNo->TooltipValue = "";

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

			// receiveDate
			$this->receiveDate->LinkCustomAttributes = "";
			$this->receiveDate->HrefValue = "";
			$this->receiveDate->TooltipValue = "";

			// orderDate
			$this->orderDate->LinkCustomAttributes = "";
			$this->orderDate->HrefValue = "";
			$this->orderDate->TooltipValue = "";

			// needDate
			$this->needDate->LinkCustomAttributes = "";
			$this->needDate->HrefValue = "";
			$this->needDate->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

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

			// bbmFaktur
			$this->bbmFaktur->EditAttrs["class"] = "form-control";
			$this->bbmFaktur->EditCustomAttributes = "";
			$this->bbmFaktur->EditValue = ew_HtmlEncode($this->bbmFaktur->CurrentValue);
			$this->bbmFaktur->PlaceHolder = ew_RemoveHtml($this->bbmFaktur->FldCaption());

			// bbmNo
			$this->bbmNo->EditAttrs["class"] = "form-control";
			$this->bbmNo->EditCustomAttributes = "";
			$this->bbmNo->EditValue = ew_HtmlEncode($this->bbmNo->CurrentValue);
			$this->bbmNo->PlaceHolder = ew_RemoveHtml($this->bbmNo->FldCaption());

			// spbID
			$this->spbID->EditAttrs["class"] = "form-control";
			$this->spbID->EditCustomAttributes = "";
			$this->spbID->EditValue = ew_HtmlEncode($this->spbID->CurrentValue);
			$this->spbID->PlaceHolder = ew_RemoveHtml($this->spbID->FldCaption());

			// spbNo
			$this->spbNo->EditAttrs["class"] = "form-control";
			$this->spbNo->EditCustomAttributes = "";
			$this->spbNo->EditValue = ew_HtmlEncode($this->spbNo->CurrentValue);
			$this->spbNo->PlaceHolder = ew_RemoveHtml($this->spbNo->FldCaption());

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

			// receiveDate
			$this->receiveDate->EditAttrs["class"] = "form-control";
			$this->receiveDate->EditCustomAttributes = "";
			$this->receiveDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->receiveDate->CurrentValue, 8));
			$this->receiveDate->PlaceHolder = ew_RemoveHtml($this->receiveDate->FldCaption());

			// orderDate
			$this->orderDate->EditAttrs["class"] = "form-control";
			$this->orderDate->EditCustomAttributes = "";
			$this->orderDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->orderDate->CurrentValue, 8));
			$this->orderDate->PlaceHolder = ew_RemoveHtml($this->orderDate->FldCaption());

			// needDate
			$this->needDate->EditAttrs["class"] = "form-control";
			$this->needDate->EditCustomAttributes = "";
			$this->needDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->needDate->CurrentValue, 8));
			$this->needDate->PlaceHolder = ew_RemoveHtml($this->needDate->FldCaption());

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

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
			// bbmFaktur

			$this->bbmFaktur->LinkCustomAttributes = "";
			$this->bbmFaktur->HrefValue = "";

			// bbmNo
			$this->bbmNo->LinkCustomAttributes = "";
			$this->bbmNo->HrefValue = "";

			// spbID
			$this->spbID->LinkCustomAttributes = "";
			$this->spbID->HrefValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";

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

			// receiveDate
			$this->receiveDate->LinkCustomAttributes = "";
			$this->receiveDate->HrefValue = "";

			// orderDate
			$this->orderDate->LinkCustomAttributes = "";
			$this->orderDate->HrefValue = "";

			// needDate
			$this->needDate->LinkCustomAttributes = "";
			$this->needDate->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

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
		if (!$this->bbmFaktur->FldIsDetailKey && !is_null($this->bbmFaktur->FormValue) && $this->bbmFaktur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bbmFaktur->FldCaption(), $this->bbmFaktur->ReqErrMsg));
		}
		if (!$this->bbmNo->FldIsDetailKey && !is_null($this->bbmNo->FormValue) && $this->bbmNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bbmNo->FldCaption(), $this->bbmNo->ReqErrMsg));
		}
		if (!$this->spbID->FldIsDetailKey && !is_null($this->spbID->FormValue) && $this->spbID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbID->FldCaption(), $this->spbID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->spbID->FormValue)) {
			ew_AddMessage($gsFormError, $this->spbID->FldErrMsg());
		}
		if (!$this->spbNo->FldIsDetailKey && !is_null($this->spbNo->FormValue) && $this->spbNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbNo->FldCaption(), $this->spbNo->ReqErrMsg));
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
		if (!$this->receiveDate->FldIsDetailKey && !is_null($this->receiveDate->FormValue) && $this->receiveDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->receiveDate->FldCaption(), $this->receiveDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->receiveDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->receiveDate->FldErrMsg());
		}
		if (!$this->orderDate->FldIsDetailKey && !is_null($this->orderDate->FormValue) && $this->orderDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->orderDate->FldCaption(), $this->orderDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->orderDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->orderDate->FldErrMsg());
		}
		if (!$this->needDate->FldIsDetailKey && !is_null($this->needDate->FormValue) && $this->needDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->needDate->FldCaption(), $this->needDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->needDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->needDate->FldErrMsg());
		}
		if (!$this->total->FldIsDetailKey && !is_null($this->total->FormValue) && $this->total->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->total->FldCaption(), $this->total->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
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

		// bbmFaktur
		$this->bbmFaktur->SetDbValueDef($rsnew, $this->bbmFaktur->CurrentValue, "", FALSE);

		// bbmNo
		$this->bbmNo->SetDbValueDef($rsnew, $this->bbmNo->CurrentValue, "", FALSE);

		// spbID
		$this->spbID->SetDbValueDef($rsnew, $this->spbID->CurrentValue, 0, FALSE);

		// spbNo
		$this->spbNo->SetDbValueDef($rsnew, $this->spbNo->CurrentValue, "", FALSE);

		// supplierID
		$this->supplierID->SetDbValueDef($rsnew, $this->supplierID->CurrentValue, 0, FALSE);

		// supplierName
		$this->supplierName->SetDbValueDef($rsnew, $this->supplierName->CurrentValue, "", FALSE);

		// supplierAddress
		$this->supplierAddress->SetDbValueDef($rsnew, $this->supplierAddress->CurrentValue, "", FALSE);

		// staffID
		$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// receiveDate
		$this->receiveDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->receiveDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// orderDate
		$this->orderDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->orderDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// needDate
		$this->needDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->needDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// total
		$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_bbmlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_bbm_add)) $as_bbm_add = new cas_bbm_add();

// Page init
$as_bbm_add->Page_Init();

// Page main
$as_bbm_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_bbm_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_bbmadd = new ew_Form("fas_bbmadd", "add");

// Validate form
fas_bbmadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_bbmFaktur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->bbmFaktur->FldCaption(), $as_bbm->bbmFaktur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bbmNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->bbmNo->FldCaption(), $as_bbm->bbmNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spbID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->spbID->FldCaption(), $as_bbm->spbID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spbID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->spbID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_spbNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->spbNo->FldCaption(), $as_bbm->spbNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->supplierID->FldCaption(), $as_bbm->supplierID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->supplierID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplierName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->supplierName->FldCaption(), $as_bbm->supplierName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_supplierAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->supplierAddress->FldCaption(), $as_bbm->supplierAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->staffID->FldCaption(), $as_bbm->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->staffName->FldCaption(), $as_bbm->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_receiveDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->receiveDate->FldCaption(), $as_bbm->receiveDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_receiveDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->receiveDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_orderDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->orderDate->FldCaption(), $as_bbm->orderDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_orderDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->orderDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_needDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->needDate->FldCaption(), $as_bbm->needDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_needDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->needDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->total->FldCaption(), $as_bbm->total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->note->FldCaption(), $as_bbm->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->createdDate->FldCaption(), $as_bbm->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->createdUserID->FldCaption(), $as_bbm->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->modifiedDate->FldCaption(), $as_bbm->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_bbm->modifiedUserID->FldCaption(), $as_bbm->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_bbm->modifiedUserID->FldErrMsg()) ?>");

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
fas_bbmadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_bbmadd.ValidateRequired = true;
<?php } else { ?>
fas_bbmadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_bbm_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_bbm_add->ShowPageHeader(); ?>
<?php
$as_bbm_add->ShowMessage();
?>
<form name="fas_bbmadd" id="fas_bbmadd" class="<?php echo $as_bbm_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_bbm_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_bbm_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_bbm">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_bbm_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_bbm->bbmFaktur->Visible) { // bbmFaktur ?>
	<div id="r_bbmFaktur" class="form-group">
		<label id="elh_as_bbm_bbmFaktur" for="x_bbmFaktur" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->bbmFaktur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->bbmFaktur->CellAttributes() ?>>
<span id="el_as_bbm_bbmFaktur">
<input type="text" data-table="as_bbm" data-field="x_bbmFaktur" name="x_bbmFaktur" id="x_bbmFaktur" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_bbm->bbmFaktur->getPlaceHolder()) ?>" value="<?php echo $as_bbm->bbmFaktur->EditValue ?>"<?php echo $as_bbm->bbmFaktur->EditAttributes() ?>>
</span>
<?php echo $as_bbm->bbmFaktur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->bbmNo->Visible) { // bbmNo ?>
	<div id="r_bbmNo" class="form-group">
		<label id="elh_as_bbm_bbmNo" for="x_bbmNo" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->bbmNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->bbmNo->CellAttributes() ?>>
<span id="el_as_bbm_bbmNo">
<input type="text" data-table="as_bbm" data-field="x_bbmNo" name="x_bbmNo" id="x_bbmNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_bbm->bbmNo->getPlaceHolder()) ?>" value="<?php echo $as_bbm->bbmNo->EditValue ?>"<?php echo $as_bbm->bbmNo->EditAttributes() ?>>
</span>
<?php echo $as_bbm->bbmNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->spbID->Visible) { // spbID ?>
	<div id="r_spbID" class="form-group">
		<label id="elh_as_bbm_spbID" for="x_spbID" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->spbID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->spbID->CellAttributes() ?>>
<span id="el_as_bbm_spbID">
<input type="text" data-table="as_bbm" data-field="x_spbID" name="x_spbID" id="x_spbID" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->spbID->getPlaceHolder()) ?>" value="<?php echo $as_bbm->spbID->EditValue ?>"<?php echo $as_bbm->spbID->EditAttributes() ?>>
</span>
<?php echo $as_bbm->spbID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->spbNo->Visible) { // spbNo ?>
	<div id="r_spbNo" class="form-group">
		<label id="elh_as_bbm_spbNo" for="x_spbNo" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->spbNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->spbNo->CellAttributes() ?>>
<span id="el_as_bbm_spbNo">
<input type="text" data-table="as_bbm" data-field="x_spbNo" name="x_spbNo" id="x_spbNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_bbm->spbNo->getPlaceHolder()) ?>" value="<?php echo $as_bbm->spbNo->EditValue ?>"<?php echo $as_bbm->spbNo->EditAttributes() ?>>
</span>
<?php echo $as_bbm->spbNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->supplierID->Visible) { // supplierID ?>
	<div id="r_supplierID" class="form-group">
		<label id="elh_as_bbm_supplierID" for="x_supplierID" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->supplierID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->supplierID->CellAttributes() ?>>
<span id="el_as_bbm_supplierID">
<input type="text" data-table="as_bbm" data-field="x_supplierID" name="x_supplierID" id="x_supplierID" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->supplierID->getPlaceHolder()) ?>" value="<?php echo $as_bbm->supplierID->EditValue ?>"<?php echo $as_bbm->supplierID->EditAttributes() ?>>
</span>
<?php echo $as_bbm->supplierID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->supplierName->Visible) { // supplierName ?>
	<div id="r_supplierName" class="form-group">
		<label id="elh_as_bbm_supplierName" for="x_supplierName" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->supplierName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->supplierName->CellAttributes() ?>>
<span id="el_as_bbm_supplierName">
<input type="text" data-table="as_bbm" data-field="x_supplierName" name="x_supplierName" id="x_supplierName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_bbm->supplierName->getPlaceHolder()) ?>" value="<?php echo $as_bbm->supplierName->EditValue ?>"<?php echo $as_bbm->supplierName->EditAttributes() ?>>
</span>
<?php echo $as_bbm->supplierName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->supplierAddress->Visible) { // supplierAddress ?>
	<div id="r_supplierAddress" class="form-group">
		<label id="elh_as_bbm_supplierAddress" for="x_supplierAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->supplierAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->supplierAddress->CellAttributes() ?>>
<span id="el_as_bbm_supplierAddress">
<textarea data-table="as_bbm" data-field="x_supplierAddress" name="x_supplierAddress" id="x_supplierAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_bbm->supplierAddress->getPlaceHolder()) ?>"<?php echo $as_bbm->supplierAddress->EditAttributes() ?>><?php echo $as_bbm->supplierAddress->EditValue ?></textarea>
</span>
<?php echo $as_bbm->supplierAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_bbm_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->staffID->CellAttributes() ?>>
<span id="el_as_bbm_staffID">
<input type="text" data-table="as_bbm" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->staffID->getPlaceHolder()) ?>" value="<?php echo $as_bbm->staffID->EditValue ?>"<?php echo $as_bbm->staffID->EditAttributes() ?>>
</span>
<?php echo $as_bbm->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_bbm_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->staffName->CellAttributes() ?>>
<span id="el_as_bbm_staffName">
<input type="text" data-table="as_bbm" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_bbm->staffName->getPlaceHolder()) ?>" value="<?php echo $as_bbm->staffName->EditValue ?>"<?php echo $as_bbm->staffName->EditAttributes() ?>>
</span>
<?php echo $as_bbm->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->receiveDate->Visible) { // receiveDate ?>
	<div id="r_receiveDate" class="form-group">
		<label id="elh_as_bbm_receiveDate" for="x_receiveDate" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->receiveDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->receiveDate->CellAttributes() ?>>
<span id="el_as_bbm_receiveDate">
<input type="text" data-table="as_bbm" data-field="x_receiveDate" name="x_receiveDate" id="x_receiveDate" placeholder="<?php echo ew_HtmlEncode($as_bbm->receiveDate->getPlaceHolder()) ?>" value="<?php echo $as_bbm->receiveDate->EditValue ?>"<?php echo $as_bbm->receiveDate->EditAttributes() ?>>
</span>
<?php echo $as_bbm->receiveDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->orderDate->Visible) { // orderDate ?>
	<div id="r_orderDate" class="form-group">
		<label id="elh_as_bbm_orderDate" for="x_orderDate" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->orderDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->orderDate->CellAttributes() ?>>
<span id="el_as_bbm_orderDate">
<input type="text" data-table="as_bbm" data-field="x_orderDate" name="x_orderDate" id="x_orderDate" placeholder="<?php echo ew_HtmlEncode($as_bbm->orderDate->getPlaceHolder()) ?>" value="<?php echo $as_bbm->orderDate->EditValue ?>"<?php echo $as_bbm->orderDate->EditAttributes() ?>>
</span>
<?php echo $as_bbm->orderDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->needDate->Visible) { // needDate ?>
	<div id="r_needDate" class="form-group">
		<label id="elh_as_bbm_needDate" for="x_needDate" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->needDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->needDate->CellAttributes() ?>>
<span id="el_as_bbm_needDate">
<input type="text" data-table="as_bbm" data-field="x_needDate" name="x_needDate" id="x_needDate" placeholder="<?php echo ew_HtmlEncode($as_bbm->needDate->getPlaceHolder()) ?>" value="<?php echo $as_bbm->needDate->EditValue ?>"<?php echo $as_bbm->needDate->EditAttributes() ?>>
</span>
<?php echo $as_bbm->needDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_as_bbm_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->total->CellAttributes() ?>>
<span id="el_as_bbm_total">
<input type="text" data-table="as_bbm" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->total->getPlaceHolder()) ?>" value="<?php echo $as_bbm->total->EditValue ?>"<?php echo $as_bbm->total->EditAttributes() ?>>
</span>
<?php echo $as_bbm->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_bbm_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->note->CellAttributes() ?>>
<span id="el_as_bbm_note">
<textarea data-table="as_bbm" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_bbm->note->getPlaceHolder()) ?>"<?php echo $as_bbm->note->EditAttributes() ?>><?php echo $as_bbm->note->EditValue ?></textarea>
</span>
<?php echo $as_bbm->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_bbm_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->createdDate->CellAttributes() ?>>
<span id="el_as_bbm_createdDate">
<input type="text" data-table="as_bbm" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_bbm->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_bbm->createdDate->EditValue ?>"<?php echo $as_bbm->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_bbm->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_bbm_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->createdUserID->CellAttributes() ?>>
<span id="el_as_bbm_createdUserID">
<input type="text" data-table="as_bbm" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_bbm->createdUserID->EditValue ?>"<?php echo $as_bbm->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_bbm->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_bbm_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->modifiedDate->CellAttributes() ?>>
<span id="el_as_bbm_modifiedDate">
<input type="text" data-table="as_bbm" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_bbm->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_bbm->modifiedDate->EditValue ?>"<?php echo $as_bbm->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_bbm->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_bbm->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_bbm_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_bbm->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_bbm->modifiedUserID->CellAttributes() ?>>
<span id="el_as_bbm_modifiedUserID">
<input type="text" data-table="as_bbm" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_bbm->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_bbm->modifiedUserID->EditValue ?>"<?php echo $as_bbm->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_bbm->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_bbm_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_bbm_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_bbmadd.Init();
</script>
<?php
$as_bbm_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_bbm_add->Page_Terminate();
?>
