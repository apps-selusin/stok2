<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_temp_detail_spbinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_temp_detail_spb_add = NULL; // Initialize page object first

class cas_temp_detail_spb_add extends cas_temp_detail_spb {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_temp_detail_spb';

	// Page object name
	var $PageObjName = 'as_temp_detail_spb_add';

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

		// Table object (as_temp_detail_spb)
		if (!isset($GLOBALS["as_temp_detail_spb"]) || get_class($GLOBALS["as_temp_detail_spb"]) == "cas_temp_detail_spb") {
			$GLOBALS["as_temp_detail_spb"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_temp_detail_spb"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_temp_detail_spb', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_temp_detail_spblist.php"));
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
		$this->spbNo->SetVisibility();
		$this->spbFaktur->SetVisibility();
		$this->productID->SetVisibility();
		$this->productName->SetVisibility();
		$this->price->SetVisibility();
		$this->qty->SetVisibility();
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
		global $EW_EXPORT, $as_temp_detail_spb;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_temp_detail_spb);
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
			if (@$_GET["detailID"] != "") {
				$this->detailID->setQueryStringValue($_GET["detailID"]);
				$this->setKey("detailID", $this->detailID->CurrentValue); // Set up key
			} else {
				$this->setKey("detailID", ""); // Clear key
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
					$this->Page_Terminate("as_temp_detail_spblist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_temp_detail_spblist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_temp_detail_spbview.php")
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
		$this->spbNo->CurrentValue = NULL;
		$this->spbNo->OldValue = $this->spbNo->CurrentValue;
		$this->spbFaktur->CurrentValue = NULL;
		$this->spbFaktur->OldValue = $this->spbFaktur->CurrentValue;
		$this->productID->CurrentValue = NULL;
		$this->productID->OldValue = $this->productID->CurrentValue;
		$this->productName->CurrentValue = NULL;
		$this->productName->OldValue = $this->productName->CurrentValue;
		$this->price->CurrentValue = NULL;
		$this->price->OldValue = $this->price->CurrentValue;
		$this->qty->CurrentValue = NULL;
		$this->qty->OldValue = $this->qty->CurrentValue;
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
		if (!$this->spbNo->FldIsDetailKey) {
			$this->spbNo->setFormValue($objForm->GetValue("x_spbNo"));
		}
		if (!$this->spbFaktur->FldIsDetailKey) {
			$this->spbFaktur->setFormValue($objForm->GetValue("x_spbFaktur"));
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
		$this->spbNo->CurrentValue = $this->spbNo->FormValue;
		$this->spbFaktur->CurrentValue = $this->spbFaktur->FormValue;
		$this->productID->CurrentValue = $this->productID->FormValue;
		$this->productName->CurrentValue = $this->productName->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->qty->CurrentValue = $this->qty->FormValue;
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
		$this->detailID->setDbValue($rs->fields('detailID'));
		$this->spbNo->setDbValue($rs->fields('spbNo'));
		$this->spbFaktur->setDbValue($rs->fields('spbFaktur'));
		$this->productID->setDbValue($rs->fields('productID'));
		$this->productName->setDbValue($rs->fields('productName'));
		$this->price->setDbValue($rs->fields('price'));
		$this->qty->setDbValue($rs->fields('qty'));
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
		$this->detailID->DbValue = $row['detailID'];
		$this->spbNo->DbValue = $row['spbNo'];
		$this->spbFaktur->DbValue = $row['spbFaktur'];
		$this->productID->DbValue = $row['productID'];
		$this->productName->DbValue = $row['productName'];
		$this->price->DbValue = $row['price'];
		$this->qty->DbValue = $row['qty'];
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
		if (strval($this->getKey("detailID")) <> "")
			$this->detailID->CurrentValue = $this->getKey("detailID"); // detailID
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
		// detailID
		// spbNo
		// spbFaktur
		// productID
		// productName
		// price
		// qty
		// note
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// detailID
		$this->detailID->ViewValue = $this->detailID->CurrentValue;
		$this->detailID->ViewCustomAttributes = "";

		// spbNo
		$this->spbNo->ViewValue = $this->spbNo->CurrentValue;
		$this->spbNo->ViewCustomAttributes = "";

		// spbFaktur
		$this->spbFaktur->ViewValue = $this->spbFaktur->CurrentValue;
		$this->spbFaktur->ViewCustomAttributes = "";

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

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";
			$this->spbNo->TooltipValue = "";

			// spbFaktur
			$this->spbFaktur->LinkCustomAttributes = "";
			$this->spbFaktur->HrefValue = "";
			$this->spbFaktur->TooltipValue = "";

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

			// spbNo
			$this->spbNo->EditAttrs["class"] = "form-control";
			$this->spbNo->EditCustomAttributes = "";
			$this->spbNo->EditValue = ew_HtmlEncode($this->spbNo->CurrentValue);
			$this->spbNo->PlaceHolder = ew_RemoveHtml($this->spbNo->FldCaption());

			// spbFaktur
			$this->spbFaktur->EditAttrs["class"] = "form-control";
			$this->spbFaktur->EditCustomAttributes = "";
			$this->spbFaktur->EditValue = ew_HtmlEncode($this->spbFaktur->CurrentValue);
			$this->spbFaktur->PlaceHolder = ew_RemoveHtml($this->spbFaktur->FldCaption());

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

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());

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
			// spbNo

			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";

			// spbFaktur
			$this->spbFaktur->LinkCustomAttributes = "";
			$this->spbFaktur->HrefValue = "";

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
		if (!$this->spbNo->FldIsDetailKey && !is_null($this->spbNo->FormValue) && $this->spbNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbNo->FldCaption(), $this->spbNo->ReqErrMsg));
		}
		if (!$this->spbFaktur->FldIsDetailKey && !is_null($this->spbFaktur->FormValue) && $this->spbFaktur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->spbFaktur->FldCaption(), $this->spbFaktur->ReqErrMsg));
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
		if (!ew_CheckInteger($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!$this->qty->FldIsDetailKey && !is_null($this->qty->FormValue) && $this->qty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->qty->FldCaption(), $this->qty->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
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

		// spbNo
		$this->spbNo->SetDbValueDef($rsnew, $this->spbNo->CurrentValue, "", FALSE);

		// spbFaktur
		$this->spbFaktur->SetDbValueDef($rsnew, $this->spbFaktur->CurrentValue, "", FALSE);

		// productID
		$this->productID->SetDbValueDef($rsnew, $this->productID->CurrentValue, 0, FALSE);

		// productName
		$this->productName->SetDbValueDef($rsnew, $this->productName->CurrentValue, "", FALSE);

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, FALSE);

		// qty
		$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_temp_detail_spblist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_temp_detail_spb_add)) $as_temp_detail_spb_add = new cas_temp_detail_spb_add();

// Page init
$as_temp_detail_spb_add->Page_Init();

// Page main
$as_temp_detail_spb_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_temp_detail_spb_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_temp_detail_spbadd = new ew_Form("fas_temp_detail_spbadd", "add");

// Validate form
fas_temp_detail_spbadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_spbNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->spbNo->FldCaption(), $as_temp_detail_spb->spbNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_spbFaktur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->spbFaktur->FldCaption(), $as_temp_detail_spb->spbFaktur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->productID->FldCaption(), $as_temp_detail_spb->productID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->productID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_productName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->productName->FldCaption(), $as_temp_detail_spb->productName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->price->FldCaption(), $as_temp_detail_spb->price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->qty->FldCaption(), $as_temp_detail_spb->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->note->FldCaption(), $as_temp_detail_spb->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->createdDate->FldCaption(), $as_temp_detail_spb->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->createdUserID->FldCaption(), $as_temp_detail_spb->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->modifiedDate->FldCaption(), $as_temp_detail_spb->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_temp_detail_spb->modifiedUserID->FldCaption(), $as_temp_detail_spb->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_temp_detail_spb->modifiedUserID->FldErrMsg()) ?>");

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
fas_temp_detail_spbadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_temp_detail_spbadd.ValidateRequired = true;
<?php } else { ?>
fas_temp_detail_spbadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_temp_detail_spb_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_temp_detail_spb_add->ShowPageHeader(); ?>
<?php
$as_temp_detail_spb_add->ShowMessage();
?>
<form name="fas_temp_detail_spbadd" id="fas_temp_detail_spbadd" class="<?php echo $as_temp_detail_spb_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_temp_detail_spb_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_temp_detail_spb_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_temp_detail_spb">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_temp_detail_spb_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_temp_detail_spb->spbNo->Visible) { // spbNo ?>
	<div id="r_spbNo" class="form-group">
		<label id="elh_as_temp_detail_spb_spbNo" for="x_spbNo" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->spbNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->spbNo->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_spbNo">
<input type="text" data-table="as_temp_detail_spb" data-field="x_spbNo" name="x_spbNo" id="x_spbNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->spbNo->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->spbNo->EditValue ?>"<?php echo $as_temp_detail_spb->spbNo->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->spbNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->spbFaktur->Visible) { // spbFaktur ?>
	<div id="r_spbFaktur" class="form-group">
		<label id="elh_as_temp_detail_spb_spbFaktur" for="x_spbFaktur" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->spbFaktur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->spbFaktur->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_spbFaktur">
<input type="text" data-table="as_temp_detail_spb" data-field="x_spbFaktur" name="x_spbFaktur" id="x_spbFaktur" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->spbFaktur->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->spbFaktur->EditValue ?>"<?php echo $as_temp_detail_spb->spbFaktur->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->spbFaktur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->productID->Visible) { // productID ?>
	<div id="r_productID" class="form-group">
		<label id="elh_as_temp_detail_spb_productID" for="x_productID" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->productID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->productID->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_productID">
<input type="text" data-table="as_temp_detail_spb" data-field="x_productID" name="x_productID" id="x_productID" size="30" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->productID->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->productID->EditValue ?>"<?php echo $as_temp_detail_spb->productID->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->productID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->productName->Visible) { // productName ?>
	<div id="r_productName" class="form-group">
		<label id="elh_as_temp_detail_spb_productName" for="x_productName" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->productName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->productName->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_productName">
<input type="text" data-table="as_temp_detail_spb" data-field="x_productName" name="x_productName" id="x_productName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->productName->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->productName->EditValue ?>"<?php echo $as_temp_detail_spb->productName->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->productName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->price->Visible) { // price ?>
	<div id="r_price" class="form-group">
		<label id="elh_as_temp_detail_spb_price" for="x_price" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->price->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_price">
<input type="text" data-table="as_temp_detail_spb" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->price->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->price->EditValue ?>"<?php echo $as_temp_detail_spb->price->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->qty->Visible) { // qty ?>
	<div id="r_qty" class="form-group">
		<label id="elh_as_temp_detail_spb_qty" for="x_qty" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->qty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->qty->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_qty">
<input type="text" data-table="as_temp_detail_spb" data-field="x_qty" name="x_qty" id="x_qty" size="30" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->qty->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->qty->EditValue ?>"<?php echo $as_temp_detail_spb->qty->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_temp_detail_spb_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->note->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_note">
<textarea data-table="as_temp_detail_spb" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->note->getPlaceHolder()) ?>"<?php echo $as_temp_detail_spb->note->EditAttributes() ?>><?php echo $as_temp_detail_spb->note->EditValue ?></textarea>
</span>
<?php echo $as_temp_detail_spb->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_temp_detail_spb_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->createdDate->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_createdDate">
<input type="text" data-table="as_temp_detail_spb" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->createdDate->EditValue ?>"<?php echo $as_temp_detail_spb->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_temp_detail_spb_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->createdUserID->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_createdUserID">
<input type="text" data-table="as_temp_detail_spb" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->createdUserID->EditValue ?>"<?php echo $as_temp_detail_spb->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_temp_detail_spb_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->modifiedDate->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_modifiedDate">
<input type="text" data-table="as_temp_detail_spb" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->modifiedDate->EditValue ?>"<?php echo $as_temp_detail_spb->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_temp_detail_spb->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_temp_detail_spb_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_temp_detail_spb->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_temp_detail_spb->modifiedUserID->CellAttributes() ?>>
<span id="el_as_temp_detail_spb_modifiedUserID">
<input type="text" data-table="as_temp_detail_spb" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_temp_detail_spb->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_temp_detail_spb->modifiedUserID->EditValue ?>"<?php echo $as_temp_detail_spb->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_temp_detail_spb->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_temp_detail_spb_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_temp_detail_spb_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_temp_detail_spbadd.Init();
</script>
<?php
$as_temp_detail_spb_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_temp_detail_spb_add->Page_Terminate();
?>
