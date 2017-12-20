<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_stock_opnameinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_stock_opname_add = NULL; // Initialize page object first

class cas_stock_opname_add extends cas_stock_opname {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_stock_opname';

	// Page object name
	var $PageObjName = 'as_stock_opname_add';

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

		// Table object (as_stock_opname)
		if (!isset($GLOBALS["as_stock_opname"]) || get_class($GLOBALS["as_stock_opname"]) == "cas_stock_opname") {
			$GLOBALS["as_stock_opname"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_stock_opname"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_stock_opname', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_stock_opnamelist.php"));
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
		$this->soDate->SetVisibility();
		$this->productID->SetVisibility();
		$this->productName->SetVisibility();
		$this->factoryID->SetVisibility();
		$this->factoryName->SetVisibility();
		$this->productStock->SetVisibility();
		$this->realStock->SetVisibility();
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
		global $EW_EXPORT, $as_stock_opname;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_stock_opname);
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
			if (@$_GET["soID"] != "") {
				$this->soID->setQueryStringValue($_GET["soID"]);
				$this->setKey("soID", $this->soID->CurrentValue); // Set up key
			} else {
				$this->setKey("soID", ""); // Clear key
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
					$this->Page_Terminate("as_stock_opnamelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_stock_opnamelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_stock_opnameview.php")
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
		$this->soDate->CurrentValue = NULL;
		$this->soDate->OldValue = $this->soDate->CurrentValue;
		$this->productID->CurrentValue = NULL;
		$this->productID->OldValue = $this->productID->CurrentValue;
		$this->productName->CurrentValue = NULL;
		$this->productName->OldValue = $this->productName->CurrentValue;
		$this->factoryID->CurrentValue = NULL;
		$this->factoryID->OldValue = $this->factoryID->CurrentValue;
		$this->factoryName->CurrentValue = NULL;
		$this->factoryName->OldValue = $this->factoryName->CurrentValue;
		$this->productStock->CurrentValue = NULL;
		$this->productStock->OldValue = $this->productStock->CurrentValue;
		$this->realStock->CurrentValue = NULL;
		$this->realStock->OldValue = $this->realStock->CurrentValue;
		$this->note->CurrentValue = NULL;
		$this->note->OldValue = $this->note->CurrentValue;
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
		if (!$this->soDate->FldIsDetailKey) {
			$this->soDate->setFormValue($objForm->GetValue("x_soDate"));
			$this->soDate->CurrentValue = ew_UnFormatDateTime($this->soDate->CurrentValue, 0);
		}
		if (!$this->productID->FldIsDetailKey) {
			$this->productID->setFormValue($objForm->GetValue("x_productID"));
		}
		if (!$this->productName->FldIsDetailKey) {
			$this->productName->setFormValue($objForm->GetValue("x_productName"));
		}
		if (!$this->factoryID->FldIsDetailKey) {
			$this->factoryID->setFormValue($objForm->GetValue("x_factoryID"));
		}
		if (!$this->factoryName->FldIsDetailKey) {
			$this->factoryName->setFormValue($objForm->GetValue("x_factoryName"));
		}
		if (!$this->productStock->FldIsDetailKey) {
			$this->productStock->setFormValue($objForm->GetValue("x_productStock"));
		}
		if (!$this->realStock->FldIsDetailKey) {
			$this->realStock->setFormValue($objForm->GetValue("x_realStock"));
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
		$this->LoadOldRecord();
		$this->soDate->CurrentValue = $this->soDate->FormValue;
		$this->soDate->CurrentValue = ew_UnFormatDateTime($this->soDate->CurrentValue, 0);
		$this->productID->CurrentValue = $this->productID->FormValue;
		$this->productName->CurrentValue = $this->productName->FormValue;
		$this->factoryID->CurrentValue = $this->factoryID->FormValue;
		$this->factoryName->CurrentValue = $this->factoryName->FormValue;
		$this->productStock->CurrentValue = $this->productStock->FormValue;
		$this->realStock->CurrentValue = $this->realStock->FormValue;
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
		$this->soID->setDbValue($rs->fields('soID'));
		$this->soDate->setDbValue($rs->fields('soDate'));
		$this->productID->setDbValue($rs->fields('productID'));
		$this->productName->setDbValue($rs->fields('productName'));
		$this->factoryID->setDbValue($rs->fields('factoryID'));
		$this->factoryName->setDbValue($rs->fields('factoryName'));
		$this->productStock->setDbValue($rs->fields('productStock'));
		$this->realStock->setDbValue($rs->fields('realStock'));
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
		$this->soID->DbValue = $row['soID'];
		$this->soDate->DbValue = $row['soDate'];
		$this->productID->DbValue = $row['productID'];
		$this->productName->DbValue = $row['productName'];
		$this->factoryID->DbValue = $row['factoryID'];
		$this->factoryName->DbValue = $row['factoryName'];
		$this->productStock->DbValue = $row['productStock'];
		$this->realStock->DbValue = $row['realStock'];
		$this->note->DbValue = $row['note'];
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
		if (strval($this->getKey("soID")) <> "")
			$this->soID->CurrentValue = $this->getKey("soID"); // soID
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// soID
		// soDate
		// productID
		// productName
		// factoryID
		// factoryName
		// productStock
		// realStock
		// note
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// soID
		$this->soID->ViewValue = $this->soID->CurrentValue;
		$this->soID->ViewCustomAttributes = "";

		// soDate
		$this->soDate->ViewValue = $this->soDate->CurrentValue;
		$this->soDate->ViewValue = ew_FormatDateTime($this->soDate->ViewValue, 0);
		$this->soDate->ViewCustomAttributes = "";

		// productID
		$this->productID->ViewValue = $this->productID->CurrentValue;
		$this->productID->ViewCustomAttributes = "";

		// productName
		$this->productName->ViewValue = $this->productName->CurrentValue;
		$this->productName->ViewCustomAttributes = "";

		// factoryID
		$this->factoryID->ViewValue = $this->factoryID->CurrentValue;
		$this->factoryID->ViewCustomAttributes = "";

		// factoryName
		$this->factoryName->ViewValue = $this->factoryName->CurrentValue;
		$this->factoryName->ViewCustomAttributes = "";

		// productStock
		$this->productStock->ViewValue = $this->productStock->CurrentValue;
		$this->productStock->ViewCustomAttributes = "";

		// realStock
		$this->realStock->ViewValue = $this->realStock->CurrentValue;
		$this->realStock->ViewCustomAttributes = "";

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

			// soDate
			$this->soDate->LinkCustomAttributes = "";
			$this->soDate->HrefValue = "";
			$this->soDate->TooltipValue = "";

			// productID
			$this->productID->LinkCustomAttributes = "";
			$this->productID->HrefValue = "";
			$this->productID->TooltipValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";
			$this->productName->TooltipValue = "";

			// factoryID
			$this->factoryID->LinkCustomAttributes = "";
			$this->factoryID->HrefValue = "";
			$this->factoryID->TooltipValue = "";

			// factoryName
			$this->factoryName->LinkCustomAttributes = "";
			$this->factoryName->HrefValue = "";
			$this->factoryName->TooltipValue = "";

			// productStock
			$this->productStock->LinkCustomAttributes = "";
			$this->productStock->HrefValue = "";
			$this->productStock->TooltipValue = "";

			// realStock
			$this->realStock->LinkCustomAttributes = "";
			$this->realStock->HrefValue = "";
			$this->realStock->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// soDate
			$this->soDate->EditAttrs["class"] = "form-control";
			$this->soDate->EditCustomAttributes = "";
			$this->soDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->soDate->CurrentValue, 8));
			$this->soDate->PlaceHolder = ew_RemoveHtml($this->soDate->FldCaption());

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

			// productStock
			$this->productStock->EditAttrs["class"] = "form-control";
			$this->productStock->EditCustomAttributes = "";
			$this->productStock->EditValue = ew_HtmlEncode($this->productStock->CurrentValue);
			$this->productStock->PlaceHolder = ew_RemoveHtml($this->productStock->FldCaption());

			// realStock
			$this->realStock->EditAttrs["class"] = "form-control";
			$this->realStock->EditCustomAttributes = "";
			$this->realStock->EditValue = ew_HtmlEncode($this->realStock->CurrentValue);
			$this->realStock->PlaceHolder = ew_RemoveHtml($this->realStock->FldCaption());

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

			// Add refer script
			// soDate

			$this->soDate->LinkCustomAttributes = "";
			$this->soDate->HrefValue = "";

			// productID
			$this->productID->LinkCustomAttributes = "";
			$this->productID->HrefValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";

			// factoryID
			$this->factoryID->LinkCustomAttributes = "";
			$this->factoryID->HrefValue = "";

			// factoryName
			$this->factoryName->LinkCustomAttributes = "";
			$this->factoryName->HrefValue = "";

			// productStock
			$this->productStock->LinkCustomAttributes = "";
			$this->productStock->HrefValue = "";

			// realStock
			$this->realStock->LinkCustomAttributes = "";
			$this->realStock->HrefValue = "";

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
		if (!$this->soDate->FldIsDetailKey && !is_null($this->soDate->FormValue) && $this->soDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->soDate->FldCaption(), $this->soDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->soDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->soDate->FldErrMsg());
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
		if (!$this->factoryID->FldIsDetailKey && !is_null($this->factoryID->FormValue) && $this->factoryID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->factoryID->FldCaption(), $this->factoryID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->factoryID->FormValue)) {
			ew_AddMessage($gsFormError, $this->factoryID->FldErrMsg());
		}
		if (!$this->factoryName->FldIsDetailKey && !is_null($this->factoryName->FormValue) && $this->factoryName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->factoryName->FldCaption(), $this->factoryName->ReqErrMsg));
		}
		if (!$this->productStock->FldIsDetailKey && !is_null($this->productStock->FormValue) && $this->productStock->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->productStock->FldCaption(), $this->productStock->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->productStock->FormValue)) {
			ew_AddMessage($gsFormError, $this->productStock->FldErrMsg());
		}
		if (!$this->realStock->FldIsDetailKey && !is_null($this->realStock->FormValue) && $this->realStock->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->realStock->FldCaption(), $this->realStock->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->realStock->FormValue)) {
			ew_AddMessage($gsFormError, $this->realStock->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// soDate
		$this->soDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->soDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// productID
		$this->productID->SetDbValueDef($rsnew, $this->productID->CurrentValue, 0, FALSE);

		// productName
		$this->productName->SetDbValueDef($rsnew, $this->productName->CurrentValue, "", FALSE);

		// factoryID
		$this->factoryID->SetDbValueDef($rsnew, $this->factoryID->CurrentValue, 0, FALSE);

		// factoryName
		$this->factoryName->SetDbValueDef($rsnew, $this->factoryName->CurrentValue, "", FALSE);

		// productStock
		$this->productStock->SetDbValueDef($rsnew, $this->productStock->CurrentValue, 0, FALSE);

		// realStock
		$this->realStock->SetDbValueDef($rsnew, $this->realStock->CurrentValue, 0, FALSE);

		// note
		$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_stock_opnamelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_stock_opname_add)) $as_stock_opname_add = new cas_stock_opname_add();

// Page init
$as_stock_opname_add->Page_Init();

// Page main
$as_stock_opname_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_stock_opname_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_stock_opnameadd = new ew_Form("fas_stock_opnameadd", "add");

// Validate form
fas_stock_opnameadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_soDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->soDate->FldCaption(), $as_stock_opname->soDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_soDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->soDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->productID->FldCaption(), $as_stock_opname->productID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->productID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_productName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->productName->FldCaption(), $as_stock_opname->productName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factoryID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->factoryID->FldCaption(), $as_stock_opname->factoryID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_factoryID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->factoryID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_factoryName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->factoryName->FldCaption(), $as_stock_opname->factoryName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productStock");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->productStock->FldCaption(), $as_stock_opname->productStock->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productStock");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->productStock->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_realStock");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->realStock->FldCaption(), $as_stock_opname->realStock->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_realStock");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->realStock->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->note->FldCaption(), $as_stock_opname->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->staffID->FldCaption(), $as_stock_opname->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->staffName->FldCaption(), $as_stock_opname->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->createdDate->FldCaption(), $as_stock_opname->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->createdUserID->FldCaption(), $as_stock_opname->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->modifiedDate->FldCaption(), $as_stock_opname->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_stock_opname->modifiedUserID->FldCaption(), $as_stock_opname->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_stock_opname->modifiedUserID->FldErrMsg()) ?>");

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
fas_stock_opnameadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_stock_opnameadd.ValidateRequired = true;
<?php } else { ?>
fas_stock_opnameadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_stock_opname_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_stock_opname_add->ShowPageHeader(); ?>
<?php
$as_stock_opname_add->ShowMessage();
?>
<form name="fas_stock_opnameadd" id="fas_stock_opnameadd" class="<?php echo $as_stock_opname_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_stock_opname_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_stock_opname_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_stock_opname">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_stock_opname_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_stock_opname->soDate->Visible) { // soDate ?>
	<div id="r_soDate" class="form-group">
		<label id="elh_as_stock_opname_soDate" for="x_soDate" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->soDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->soDate->CellAttributes() ?>>
<span id="el_as_stock_opname_soDate">
<input type="text" data-table="as_stock_opname" data-field="x_soDate" name="x_soDate" id="x_soDate" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->soDate->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->soDate->EditValue ?>"<?php echo $as_stock_opname->soDate->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->soDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->productID->Visible) { // productID ?>
	<div id="r_productID" class="form-group">
		<label id="elh_as_stock_opname_productID" for="x_productID" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->productID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->productID->CellAttributes() ?>>
<span id="el_as_stock_opname_productID">
<input type="text" data-table="as_stock_opname" data-field="x_productID" name="x_productID" id="x_productID" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->productID->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->productID->EditValue ?>"<?php echo $as_stock_opname->productID->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->productID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->productName->Visible) { // productName ?>
	<div id="r_productName" class="form-group">
		<label id="elh_as_stock_opname_productName" for="x_productName" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->productName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->productName->CellAttributes() ?>>
<span id="el_as_stock_opname_productName">
<input type="text" data-table="as_stock_opname" data-field="x_productName" name="x_productName" id="x_productName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->productName->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->productName->EditValue ?>"<?php echo $as_stock_opname->productName->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->productName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->factoryID->Visible) { // factoryID ?>
	<div id="r_factoryID" class="form-group">
		<label id="elh_as_stock_opname_factoryID" for="x_factoryID" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->factoryID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->factoryID->CellAttributes() ?>>
<span id="el_as_stock_opname_factoryID">
<input type="text" data-table="as_stock_opname" data-field="x_factoryID" name="x_factoryID" id="x_factoryID" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->factoryID->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->factoryID->EditValue ?>"<?php echo $as_stock_opname->factoryID->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->factoryID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->factoryName->Visible) { // factoryName ?>
	<div id="r_factoryName" class="form-group">
		<label id="elh_as_stock_opname_factoryName" for="x_factoryName" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->factoryName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->factoryName->CellAttributes() ?>>
<span id="el_as_stock_opname_factoryName">
<input type="text" data-table="as_stock_opname" data-field="x_factoryName" name="x_factoryName" id="x_factoryName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->factoryName->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->factoryName->EditValue ?>"<?php echo $as_stock_opname->factoryName->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->factoryName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->productStock->Visible) { // productStock ?>
	<div id="r_productStock" class="form-group">
		<label id="elh_as_stock_opname_productStock" for="x_productStock" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->productStock->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->productStock->CellAttributes() ?>>
<span id="el_as_stock_opname_productStock">
<input type="text" data-table="as_stock_opname" data-field="x_productStock" name="x_productStock" id="x_productStock" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->productStock->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->productStock->EditValue ?>"<?php echo $as_stock_opname->productStock->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->productStock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->realStock->Visible) { // realStock ?>
	<div id="r_realStock" class="form-group">
		<label id="elh_as_stock_opname_realStock" for="x_realStock" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->realStock->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->realStock->CellAttributes() ?>>
<span id="el_as_stock_opname_realStock">
<input type="text" data-table="as_stock_opname" data-field="x_realStock" name="x_realStock" id="x_realStock" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->realStock->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->realStock->EditValue ?>"<?php echo $as_stock_opname->realStock->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->realStock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_stock_opname_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->note->CellAttributes() ?>>
<span id="el_as_stock_opname_note">
<textarea data-table="as_stock_opname" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->note->getPlaceHolder()) ?>"<?php echo $as_stock_opname->note->EditAttributes() ?>><?php echo $as_stock_opname->note->EditValue ?></textarea>
</span>
<?php echo $as_stock_opname->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_stock_opname_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->staffID->CellAttributes() ?>>
<span id="el_as_stock_opname_staffID">
<input type="text" data-table="as_stock_opname" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->staffID->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->staffID->EditValue ?>"<?php echo $as_stock_opname->staffID->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_stock_opname_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->staffName->CellAttributes() ?>>
<span id="el_as_stock_opname_staffName">
<input type="text" data-table="as_stock_opname" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->staffName->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->staffName->EditValue ?>"<?php echo $as_stock_opname->staffName->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_stock_opname_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->createdDate->CellAttributes() ?>>
<span id="el_as_stock_opname_createdDate">
<input type="text" data-table="as_stock_opname" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->createdDate->EditValue ?>"<?php echo $as_stock_opname->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_stock_opname_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->createdUserID->CellAttributes() ?>>
<span id="el_as_stock_opname_createdUserID">
<input type="text" data-table="as_stock_opname" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->createdUserID->EditValue ?>"<?php echo $as_stock_opname->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_stock_opname_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->modifiedDate->CellAttributes() ?>>
<span id="el_as_stock_opname_modifiedDate">
<input type="text" data-table="as_stock_opname" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->modifiedDate->EditValue ?>"<?php echo $as_stock_opname->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_stock_opname->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_stock_opname_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_stock_opname->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_stock_opname->modifiedUserID->CellAttributes() ?>>
<span id="el_as_stock_opname_modifiedUserID">
<input type="text" data-table="as_stock_opname" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_stock_opname->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_stock_opname->modifiedUserID->EditValue ?>"<?php echo $as_stock_opname->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_stock_opname->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_stock_opname_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_stock_opname_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_stock_opnameadd.Init();
</script>
<?php
$as_stock_opname_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_stock_opname_add->Page_Terminate();
?>
