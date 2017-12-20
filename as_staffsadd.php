<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_staffsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_staffs_add = NULL; // Initialize page object first

class cas_staffs_add extends cas_staffs {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_staffs';

	// Page object name
	var $PageObjName = 'as_staffs_add';

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

		// Table object (as_staffs)
		if (!isset($GLOBALS["as_staffs"]) || get_class($GLOBALS["as_staffs"]) == "cas_staffs") {
			$GLOBALS["as_staffs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_staffs"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_staffs', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_staffslist.php"));
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
		$this->staffCode->SetVisibility();
		$this->staffName->SetVisibility();
		$this->address->SetVisibility();
		$this->address2->SetVisibility();
		$this->village->SetVisibility();
		$this->district->SetVisibility();
		$this->city->SetVisibility();
		$this->zipCode->SetVisibility();
		$this->province->SetVisibility();
		$this->phone->SetVisibility();
		$this->position->SetVisibility();
		$this->part->SetVisibility();
		$this->status->SetVisibility();
		$this->level->SetVisibility();
		$this->photo->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->lastLogin->SetVisibility();
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
		global $EW_EXPORT, $as_staffs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_staffs);
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
			if (@$_GET["staffID"] != "") {
				$this->staffID->setQueryStringValue($_GET["staffID"]);
				$this->setKey("staffID", $this->staffID->CurrentValue); // Set up key
			} else {
				$this->setKey("staffID", ""); // Clear key
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
					$this->Page_Terminate("as_staffslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_staffslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_staffsview.php")
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
		$this->staffCode->CurrentValue = NULL;
		$this->staffCode->OldValue = $this->staffCode->CurrentValue;
		$this->staffName->CurrentValue = NULL;
		$this->staffName->OldValue = $this->staffName->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->address2->CurrentValue = NULL;
		$this->address2->OldValue = $this->address2->CurrentValue;
		$this->village->CurrentValue = NULL;
		$this->village->OldValue = $this->village->CurrentValue;
		$this->district->CurrentValue = NULL;
		$this->district->OldValue = $this->district->CurrentValue;
		$this->city->CurrentValue = NULL;
		$this->city->OldValue = $this->city->CurrentValue;
		$this->zipCode->CurrentValue = NULL;
		$this->zipCode->OldValue = $this->zipCode->CurrentValue;
		$this->province->CurrentValue = NULL;
		$this->province->OldValue = $this->province->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->position->CurrentValue = NULL;
		$this->position->OldValue = $this->position->CurrentValue;
		$this->part->CurrentValue = NULL;
		$this->part->OldValue = $this->part->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->level->CurrentValue = NULL;
		$this->level->OldValue = $this->level->CurrentValue;
		$this->photo->CurrentValue = NULL;
		$this->photo->OldValue = $this->photo->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->lastLogin->CurrentValue = NULL;
		$this->lastLogin->OldValue = $this->lastLogin->CurrentValue;
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
		if (!$this->staffCode->FldIsDetailKey) {
			$this->staffCode->setFormValue($objForm->GetValue("x_staffCode"));
		}
		if (!$this->staffName->FldIsDetailKey) {
			$this->staffName->setFormValue($objForm->GetValue("x_staffName"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->address2->FldIsDetailKey) {
			$this->address2->setFormValue($objForm->GetValue("x_address2"));
		}
		if (!$this->village->FldIsDetailKey) {
			$this->village->setFormValue($objForm->GetValue("x_village"));
		}
		if (!$this->district->FldIsDetailKey) {
			$this->district->setFormValue($objForm->GetValue("x_district"));
		}
		if (!$this->city->FldIsDetailKey) {
			$this->city->setFormValue($objForm->GetValue("x_city"));
		}
		if (!$this->zipCode->FldIsDetailKey) {
			$this->zipCode->setFormValue($objForm->GetValue("x_zipCode"));
		}
		if (!$this->province->FldIsDetailKey) {
			$this->province->setFormValue($objForm->GetValue("x_province"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->position->FldIsDetailKey) {
			$this->position->setFormValue($objForm->GetValue("x_position"));
		}
		if (!$this->part->FldIsDetailKey) {
			$this->part->setFormValue($objForm->GetValue("x_part"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->level->FldIsDetailKey) {
			$this->level->setFormValue($objForm->GetValue("x_level"));
		}
		if (!$this->photo->FldIsDetailKey) {
			$this->photo->setFormValue($objForm->GetValue("x_photo"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->lastLogin->FldIsDetailKey) {
			$this->lastLogin->setFormValue($objForm->GetValue("x_lastLogin"));
			$this->lastLogin->CurrentValue = ew_UnFormatDateTime($this->lastLogin->CurrentValue, 0);
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
		$this->staffCode->CurrentValue = $this->staffCode->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->address2->CurrentValue = $this->address2->FormValue;
		$this->village->CurrentValue = $this->village->FormValue;
		$this->district->CurrentValue = $this->district->FormValue;
		$this->city->CurrentValue = $this->city->FormValue;
		$this->zipCode->CurrentValue = $this->zipCode->FormValue;
		$this->province->CurrentValue = $this->province->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->position->CurrentValue = $this->position->FormValue;
		$this->part->CurrentValue = $this->part->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->level->CurrentValue = $this->level->FormValue;
		$this->photo->CurrentValue = $this->photo->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->lastLogin->CurrentValue = $this->lastLogin->FormValue;
		$this->lastLogin->CurrentValue = ew_UnFormatDateTime($this->lastLogin->CurrentValue, 0);
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
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffCode->setDbValue($rs->fields('staffCode'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->address->setDbValue($rs->fields('address'));
		$this->address2->setDbValue($rs->fields('address2'));
		$this->village->setDbValue($rs->fields('village'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city->setDbValue($rs->fields('city'));
		$this->zipCode->setDbValue($rs->fields('zipCode'));
		$this->province->setDbValue($rs->fields('province'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->position->setDbValue($rs->fields('position'));
		$this->part->setDbValue($rs->fields('part'));
		$this->status->setDbValue($rs->fields('status'));
		$this->level->setDbValue($rs->fields('level'));
		$this->photo->setDbValue($rs->fields('photo'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->lastLogin->setDbValue($rs->fields('lastLogin'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->staffID->DbValue = $row['staffID'];
		$this->staffCode->DbValue = $row['staffCode'];
		$this->staffName->DbValue = $row['staffName'];
		$this->address->DbValue = $row['address'];
		$this->address2->DbValue = $row['address2'];
		$this->village->DbValue = $row['village'];
		$this->district->DbValue = $row['district'];
		$this->city->DbValue = $row['city'];
		$this->zipCode->DbValue = $row['zipCode'];
		$this->province->DbValue = $row['province'];
		$this->phone->DbValue = $row['phone'];
		$this->position->DbValue = $row['position'];
		$this->part->DbValue = $row['part'];
		$this->status->DbValue = $row['status'];
		$this->level->DbValue = $row['level'];
		$this->photo->DbValue = $row['photo'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->lastLogin->DbValue = $row['lastLogin'];
		$this->createdDate->DbValue = $row['createdDate'];
		$this->createdUserID->DbValue = $row['createdUserID'];
		$this->modifiedDate->DbValue = $row['modifiedDate'];
		$this->modifiedUserID->DbValue = $row['modifiedUserID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("staffID")) <> "")
			$this->staffID->CurrentValue = $this->getKey("staffID"); // staffID
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
		// staffID
		// staffCode
		// staffName
		// address
		// address2
		// village
		// district
		// city
		// zipCode
		// province
		// phone
		// position
		// part
		// status
		// level
		// photo
		// email
		// password
		// lastLogin
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->ViewValue = $this->staffCode->CurrentValue;
		$this->staffCode->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// address2
		$this->address2->ViewValue = $this->address2->CurrentValue;
		$this->address2->ViewCustomAttributes = "";

		// village
		$this->village->ViewValue = $this->village->CurrentValue;
		$this->village->ViewCustomAttributes = "";

		// district
		$this->district->ViewValue = $this->district->CurrentValue;
		$this->district->ViewCustomAttributes = "";

		// city
		$this->city->ViewValue = $this->city->CurrentValue;
		$this->city->ViewCustomAttributes = "";

		// zipCode
		$this->zipCode->ViewValue = $this->zipCode->CurrentValue;
		$this->zipCode->ViewCustomAttributes = "";

		// province
		$this->province->ViewValue = $this->province->CurrentValue;
		$this->province->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// position
		$this->position->ViewValue = $this->position->CurrentValue;
		$this->position->ViewCustomAttributes = "";

		// part
		$this->part->ViewValue = $this->part->CurrentValue;
		$this->part->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// level
		$this->level->ViewValue = $this->level->CurrentValue;
		$this->level->ViewCustomAttributes = "";

		// photo
		$this->photo->ViewValue = $this->photo->CurrentValue;
		$this->photo->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// lastLogin
		$this->lastLogin->ViewValue = $this->lastLogin->CurrentValue;
		$this->lastLogin->ViewValue = ew_FormatDateTime($this->lastLogin->ViewValue, 0);
		$this->lastLogin->ViewCustomAttributes = "";

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

			// staffCode
			$this->staffCode->LinkCustomAttributes = "";
			$this->staffCode->HrefValue = "";
			$this->staffCode->TooltipValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";
			$this->staffName->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// address2
			$this->address2->LinkCustomAttributes = "";
			$this->address2->HrefValue = "";
			$this->address2->TooltipValue = "";

			// village
			$this->village->LinkCustomAttributes = "";
			$this->village->HrefValue = "";
			$this->village->TooltipValue = "";

			// district
			$this->district->LinkCustomAttributes = "";
			$this->district->HrefValue = "";
			$this->district->TooltipValue = "";

			// city
			$this->city->LinkCustomAttributes = "";
			$this->city->HrefValue = "";
			$this->city->TooltipValue = "";

			// zipCode
			$this->zipCode->LinkCustomAttributes = "";
			$this->zipCode->HrefValue = "";
			$this->zipCode->TooltipValue = "";

			// province
			$this->province->LinkCustomAttributes = "";
			$this->province->HrefValue = "";
			$this->province->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// position
			$this->position->LinkCustomAttributes = "";
			$this->position->HrefValue = "";
			$this->position->TooltipValue = "";

			// part
			$this->part->LinkCustomAttributes = "";
			$this->part->HrefValue = "";
			$this->part->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";
			$this->level->TooltipValue = "";

			// photo
			$this->photo->LinkCustomAttributes = "";
			$this->photo->HrefValue = "";
			$this->photo->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// lastLogin
			$this->lastLogin->LinkCustomAttributes = "";
			$this->lastLogin->HrefValue = "";
			$this->lastLogin->TooltipValue = "";

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

			// staffCode
			$this->staffCode->EditAttrs["class"] = "form-control";
			$this->staffCode->EditCustomAttributes = "";
			$this->staffCode->EditValue = ew_HtmlEncode($this->staffCode->CurrentValue);
			$this->staffCode->PlaceHolder = ew_RemoveHtml($this->staffCode->FldCaption());

			// staffName
			$this->staffName->EditAttrs["class"] = "form-control";
			$this->staffName->EditCustomAttributes = "";
			$this->staffName->EditValue = ew_HtmlEncode($this->staffName->CurrentValue);
			$this->staffName->PlaceHolder = ew_RemoveHtml($this->staffName->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// address2
			$this->address2->EditAttrs["class"] = "form-control";
			$this->address2->EditCustomAttributes = "";
			$this->address2->EditValue = ew_HtmlEncode($this->address2->CurrentValue);
			$this->address2->PlaceHolder = ew_RemoveHtml($this->address2->FldCaption());

			// village
			$this->village->EditAttrs["class"] = "form-control";
			$this->village->EditCustomAttributes = "";
			$this->village->EditValue = ew_HtmlEncode($this->village->CurrentValue);
			$this->village->PlaceHolder = ew_RemoveHtml($this->village->FldCaption());

			// district
			$this->district->EditAttrs["class"] = "form-control";
			$this->district->EditCustomAttributes = "";
			$this->district->EditValue = ew_HtmlEncode($this->district->CurrentValue);
			$this->district->PlaceHolder = ew_RemoveHtml($this->district->FldCaption());

			// city
			$this->city->EditAttrs["class"] = "form-control";
			$this->city->EditCustomAttributes = "";
			$this->city->EditValue = ew_HtmlEncode($this->city->CurrentValue);
			$this->city->PlaceHolder = ew_RemoveHtml($this->city->FldCaption());

			// zipCode
			$this->zipCode->EditAttrs["class"] = "form-control";
			$this->zipCode->EditCustomAttributes = "";
			$this->zipCode->EditValue = ew_HtmlEncode($this->zipCode->CurrentValue);
			$this->zipCode->PlaceHolder = ew_RemoveHtml($this->zipCode->FldCaption());

			// province
			$this->province->EditAttrs["class"] = "form-control";
			$this->province->EditCustomAttributes = "";
			$this->province->EditValue = ew_HtmlEncode($this->province->CurrentValue);
			$this->province->PlaceHolder = ew_RemoveHtml($this->province->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// position
			$this->position->EditAttrs["class"] = "form-control";
			$this->position->EditCustomAttributes = "";
			$this->position->EditValue = ew_HtmlEncode($this->position->CurrentValue);
			$this->position->PlaceHolder = ew_RemoveHtml($this->position->FldCaption());

			// part
			$this->part->EditAttrs["class"] = "form-control";
			$this->part->EditCustomAttributes = "";
			$this->part->EditValue = ew_HtmlEncode($this->part->CurrentValue);
			$this->part->PlaceHolder = ew_RemoveHtml($this->part->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// level
			$this->level->EditAttrs["class"] = "form-control";
			$this->level->EditCustomAttributes = "";
			$this->level->EditValue = ew_HtmlEncode($this->level->CurrentValue);
			$this->level->PlaceHolder = ew_RemoveHtml($this->level->FldCaption());

			// photo
			$this->photo->EditAttrs["class"] = "form-control";
			$this->photo->EditCustomAttributes = "";
			$this->photo->EditValue = ew_HtmlEncode($this->photo->CurrentValue);
			$this->photo->PlaceHolder = ew_RemoveHtml($this->photo->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// lastLogin
			$this->lastLogin->EditAttrs["class"] = "form-control";
			$this->lastLogin->EditCustomAttributes = "";
			$this->lastLogin->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lastLogin->CurrentValue, 8));
			$this->lastLogin->PlaceHolder = ew_RemoveHtml($this->lastLogin->FldCaption());

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
			// staffCode

			$this->staffCode->LinkCustomAttributes = "";
			$this->staffCode->HrefValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// address2
			$this->address2->LinkCustomAttributes = "";
			$this->address2->HrefValue = "";

			// village
			$this->village->LinkCustomAttributes = "";
			$this->village->HrefValue = "";

			// district
			$this->district->LinkCustomAttributes = "";
			$this->district->HrefValue = "";

			// city
			$this->city->LinkCustomAttributes = "";
			$this->city->HrefValue = "";

			// zipCode
			$this->zipCode->LinkCustomAttributes = "";
			$this->zipCode->HrefValue = "";

			// province
			$this->province->LinkCustomAttributes = "";
			$this->province->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// position
			$this->position->LinkCustomAttributes = "";
			$this->position->HrefValue = "";

			// part
			$this->part->LinkCustomAttributes = "";
			$this->part->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";

			// photo
			$this->photo->LinkCustomAttributes = "";
			$this->photo->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// lastLogin
			$this->lastLogin->LinkCustomAttributes = "";
			$this->lastLogin->HrefValue = "";

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
		if (!$this->staffCode->FldIsDetailKey && !is_null($this->staffCode->FormValue) && $this->staffCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffCode->FldCaption(), $this->staffCode->ReqErrMsg));
		}
		if (!$this->staffName->FldIsDetailKey && !is_null($this->staffName->FormValue) && $this->staffName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffName->FldCaption(), $this->staffName->ReqErrMsg));
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address->FldCaption(), $this->address->ReqErrMsg));
		}
		if (!$this->address2->FldIsDetailKey && !is_null($this->address2->FormValue) && $this->address2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address2->FldCaption(), $this->address2->ReqErrMsg));
		}
		if (!$this->village->FldIsDetailKey && !is_null($this->village->FormValue) && $this->village->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->village->FldCaption(), $this->village->ReqErrMsg));
		}
		if (!$this->district->FldIsDetailKey && !is_null($this->district->FormValue) && $this->district->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->district->FldCaption(), $this->district->ReqErrMsg));
		}
		if (!$this->city->FldIsDetailKey && !is_null($this->city->FormValue) && $this->city->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->city->FldCaption(), $this->city->ReqErrMsg));
		}
		if (!$this->zipCode->FldIsDetailKey && !is_null($this->zipCode->FormValue) && $this->zipCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zipCode->FldCaption(), $this->zipCode->ReqErrMsg));
		}
		if (!$this->province->FldIsDetailKey && !is_null($this->province->FormValue) && $this->province->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province->FldCaption(), $this->province->ReqErrMsg));
		}
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone->FldCaption(), $this->phone->ReqErrMsg));
		}
		if (!$this->position->FldIsDetailKey && !is_null($this->position->FormValue) && $this->position->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->position->FldCaption(), $this->position->ReqErrMsg));
		}
		if (!$this->part->FldIsDetailKey && !is_null($this->part->FormValue) && $this->part->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->part->FldCaption(), $this->part->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->level->FldIsDetailKey && !is_null($this->level->FormValue) && $this->level->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->level->FldCaption(), $this->level->ReqErrMsg));
		}
		if (!$this->photo->FldIsDetailKey && !is_null($this->photo->FormValue) && $this->photo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->photo->FldCaption(), $this->photo->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->password->FldIsDetailKey && !is_null($this->password->FormValue) && $this->password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->password->FldCaption(), $this->password->ReqErrMsg));
		}
		if (!$this->lastLogin->FldIsDetailKey && !is_null($this->lastLogin->FormValue) && $this->lastLogin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastLogin->FldCaption(), $this->lastLogin->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lastLogin->FormValue)) {
			ew_AddMessage($gsFormError, $this->lastLogin->FldErrMsg());
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

		// staffCode
		$this->staffCode->SetDbValueDef($rsnew, $this->staffCode->CurrentValue, "", FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", FALSE);

		// address2
		$this->address2->SetDbValueDef($rsnew, $this->address2->CurrentValue, "", FALSE);

		// village
		$this->village->SetDbValueDef($rsnew, $this->village->CurrentValue, "", FALSE);

		// district
		$this->district->SetDbValueDef($rsnew, $this->district->CurrentValue, "", FALSE);

		// city
		$this->city->SetDbValueDef($rsnew, $this->city->CurrentValue, "", FALSE);

		// zipCode
		$this->zipCode->SetDbValueDef($rsnew, $this->zipCode->CurrentValue, "", FALSE);

		// province
		$this->province->SetDbValueDef($rsnew, $this->province->CurrentValue, "", FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", FALSE);

		// position
		$this->position->SetDbValueDef($rsnew, $this->position->CurrentValue, "", FALSE);

		// part
		$this->part->SetDbValueDef($rsnew, $this->part->CurrentValue, "", FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", FALSE);

		// level
		$this->level->SetDbValueDef($rsnew, $this->level->CurrentValue, "", FALSE);

		// photo
		$this->photo->SetDbValueDef($rsnew, $this->photo->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, "", FALSE);

		// lastLogin
		$this->lastLogin->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastLogin->CurrentValue, 0), ew_CurrentDate(), FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_staffslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_staffs_add)) $as_staffs_add = new cas_staffs_add();

// Page init
$as_staffs_add->Page_Init();

// Page main
$as_staffs_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_staffs_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_staffsadd = new ew_Form("fas_staffsadd", "add");

// Validate form
fas_staffsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_staffCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->staffCode->FldCaption(), $as_staffs->staffCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->staffName->FldCaption(), $as_staffs->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->address->FldCaption(), $as_staffs->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->address2->FldCaption(), $as_staffs->address2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_village");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->village->FldCaption(), $as_staffs->village->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_district");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->district->FldCaption(), $as_staffs->district->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->city->FldCaption(), $as_staffs->city->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zipCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->zipCode->FldCaption(), $as_staffs->zipCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_province");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->province->FldCaption(), $as_staffs->province->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->phone->FldCaption(), $as_staffs->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_position");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->position->FldCaption(), $as_staffs->position->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_part");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->part->FldCaption(), $as_staffs->part->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->status->FldCaption(), $as_staffs->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_level");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->level->FldCaption(), $as_staffs->level->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_photo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->photo->FldCaption(), $as_staffs->photo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->_email->FldCaption(), $as_staffs->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->password->FldCaption(), $as_staffs->password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastLogin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->lastLogin->FldCaption(), $as_staffs->lastLogin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastLogin");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_staffs->lastLogin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->createdDate->FldCaption(), $as_staffs->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_staffs->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->createdUserID->FldCaption(), $as_staffs->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_staffs->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->modifiedDate->FldCaption(), $as_staffs->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_staffs->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_staffs->modifiedUserID->FldCaption(), $as_staffs->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_staffs->modifiedUserID->FldErrMsg()) ?>");

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
fas_staffsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_staffsadd.ValidateRequired = true;
<?php } else { ?>
fas_staffsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_staffs_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_staffs_add->ShowPageHeader(); ?>
<?php
$as_staffs_add->ShowMessage();
?>
<form name="fas_staffsadd" id="fas_staffsadd" class="<?php echo $as_staffs_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_staffs_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_staffs_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_staffs">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_staffs_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_staffs->staffCode->Visible) { // staffCode ?>
	<div id="r_staffCode" class="form-group">
		<label id="elh_as_staffs_staffCode" for="x_staffCode" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->staffCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->staffCode->CellAttributes() ?>>
<span id="el_as_staffs_staffCode">
<input type="text" data-table="as_staffs" data-field="x_staffCode" name="x_staffCode" id="x_staffCode" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($as_staffs->staffCode->getPlaceHolder()) ?>" value="<?php echo $as_staffs->staffCode->EditValue ?>"<?php echo $as_staffs->staffCode->EditAttributes() ?>>
</span>
<?php echo $as_staffs->staffCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_staffs_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->staffName->CellAttributes() ?>>
<span id="el_as_staffs_staffName">
<input type="text" data-table="as_staffs" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->staffName->getPlaceHolder()) ?>" value="<?php echo $as_staffs->staffName->EditValue ?>"<?php echo $as_staffs->staffName->EditAttributes() ?>>
</span>
<?php echo $as_staffs->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_as_staffs_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->address->CellAttributes() ?>>
<span id="el_as_staffs_address">
<textarea data-table="as_staffs" data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_staffs->address->getPlaceHolder()) ?>"<?php echo $as_staffs->address->EditAttributes() ?>><?php echo $as_staffs->address->EditValue ?></textarea>
</span>
<?php echo $as_staffs->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->address2->Visible) { // address2 ?>
	<div id="r_address2" class="form-group">
		<label id="elh_as_staffs_address2" for="x_address2" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->address2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->address2->CellAttributes() ?>>
<span id="el_as_staffs_address2">
<textarea data-table="as_staffs" data-field="x_address2" name="x_address2" id="x_address2" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_staffs->address2->getPlaceHolder()) ?>"<?php echo $as_staffs->address2->EditAttributes() ?>><?php echo $as_staffs->address2->EditValue ?></textarea>
</span>
<?php echo $as_staffs->address2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->village->Visible) { // village ?>
	<div id="r_village" class="form-group">
		<label id="elh_as_staffs_village" for="x_village" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->village->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->village->CellAttributes() ?>>
<span id="el_as_staffs_village">
<input type="text" data-table="as_staffs" data-field="x_village" name="x_village" id="x_village" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->village->getPlaceHolder()) ?>" value="<?php echo $as_staffs->village->EditValue ?>"<?php echo $as_staffs->village->EditAttributes() ?>>
</span>
<?php echo $as_staffs->village->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->district->Visible) { // district ?>
	<div id="r_district" class="form-group">
		<label id="elh_as_staffs_district" for="x_district" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->district->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->district->CellAttributes() ?>>
<span id="el_as_staffs_district">
<input type="text" data-table="as_staffs" data-field="x_district" name="x_district" id="x_district" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->district->getPlaceHolder()) ?>" value="<?php echo $as_staffs->district->EditValue ?>"<?php echo $as_staffs->district->EditAttributes() ?>>
</span>
<?php echo $as_staffs->district->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->city->Visible) { // city ?>
	<div id="r_city" class="form-group">
		<label id="elh_as_staffs_city" for="x_city" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->city->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->city->CellAttributes() ?>>
<span id="el_as_staffs_city">
<input type="text" data-table="as_staffs" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->city->getPlaceHolder()) ?>" value="<?php echo $as_staffs->city->EditValue ?>"<?php echo $as_staffs->city->EditAttributes() ?>>
</span>
<?php echo $as_staffs->city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->zipCode->Visible) { // zipCode ?>
	<div id="r_zipCode" class="form-group">
		<label id="elh_as_staffs_zipCode" for="x_zipCode" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->zipCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->zipCode->CellAttributes() ?>>
<span id="el_as_staffs_zipCode">
<input type="text" data-table="as_staffs" data-field="x_zipCode" name="x_zipCode" id="x_zipCode" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($as_staffs->zipCode->getPlaceHolder()) ?>" value="<?php echo $as_staffs->zipCode->EditValue ?>"<?php echo $as_staffs->zipCode->EditAttributes() ?>>
</span>
<?php echo $as_staffs->zipCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->province->Visible) { // province ?>
	<div id="r_province" class="form-group">
		<label id="elh_as_staffs_province" for="x_province" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->province->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->province->CellAttributes() ?>>
<span id="el_as_staffs_province">
<input type="text" data-table="as_staffs" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->province->getPlaceHolder()) ?>" value="<?php echo $as_staffs->province->EditValue ?>"<?php echo $as_staffs->province->EditAttributes() ?>>
</span>
<?php echo $as_staffs->province->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_as_staffs_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->phone->CellAttributes() ?>>
<span id="el_as_staffs_phone">
<input type="text" data-table="as_staffs" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($as_staffs->phone->getPlaceHolder()) ?>" value="<?php echo $as_staffs->phone->EditValue ?>"<?php echo $as_staffs->phone->EditAttributes() ?>>
</span>
<?php echo $as_staffs->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->position->Visible) { // position ?>
	<div id="r_position" class="form-group">
		<label id="elh_as_staffs_position" for="x_position" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->position->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->position->CellAttributes() ?>>
<span id="el_as_staffs_position">
<input type="text" data-table="as_staffs" data-field="x_position" name="x_position" id="x_position" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->position->getPlaceHolder()) ?>" value="<?php echo $as_staffs->position->EditValue ?>"<?php echo $as_staffs->position->EditAttributes() ?>>
</span>
<?php echo $as_staffs->position->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->part->Visible) { // part ?>
	<div id="r_part" class="form-group">
		<label id="elh_as_staffs_part" for="x_part" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->part->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->part->CellAttributes() ?>>
<span id="el_as_staffs_part">
<input type="text" data-table="as_staffs" data-field="x_part" name="x_part" id="x_part" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_staffs->part->getPlaceHolder()) ?>" value="<?php echo $as_staffs->part->EditValue ?>"<?php echo $as_staffs->part->EditAttributes() ?>>
</span>
<?php echo $as_staffs->part->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_as_staffs_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->status->CellAttributes() ?>>
<span id="el_as_staffs_status">
<input type="text" data-table="as_staffs" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_staffs->status->getPlaceHolder()) ?>" value="<?php echo $as_staffs->status->EditValue ?>"<?php echo $as_staffs->status->EditAttributes() ?>>
</span>
<?php echo $as_staffs->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->level->Visible) { // level ?>
	<div id="r_level" class="form-group">
		<label id="elh_as_staffs_level" for="x_level" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->level->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->level->CellAttributes() ?>>
<span id="el_as_staffs_level">
<input type="text" data-table="as_staffs" data-field="x_level" name="x_level" id="x_level" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($as_staffs->level->getPlaceHolder()) ?>" value="<?php echo $as_staffs->level->EditValue ?>"<?php echo $as_staffs->level->EditAttributes() ?>>
</span>
<?php echo $as_staffs->level->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->photo->Visible) { // photo ?>
	<div id="r_photo" class="form-group">
		<label id="elh_as_staffs_photo" for="x_photo" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->photo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->photo->CellAttributes() ?>>
<span id="el_as_staffs_photo">
<textarea data-table="as_staffs" data-field="x_photo" name="x_photo" id="x_photo" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_staffs->photo->getPlaceHolder()) ?>"<?php echo $as_staffs->photo->EditAttributes() ?>><?php echo $as_staffs->photo->EditValue ?></textarea>
</span>
<?php echo $as_staffs->photo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_as_staffs__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->_email->CellAttributes() ?>>
<span id="el_as_staffs__email">
<input type="text" data-table="as_staffs" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="120" placeholder="<?php echo ew_HtmlEncode($as_staffs->_email->getPlaceHolder()) ?>" value="<?php echo $as_staffs->_email->EditValue ?>"<?php echo $as_staffs->_email->EditAttributes() ?>>
</span>
<?php echo $as_staffs->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_as_staffs_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->password->CellAttributes() ?>>
<span id="el_as_staffs_password">
<input type="text" data-table="as_staffs" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($as_staffs->password->getPlaceHolder()) ?>" value="<?php echo $as_staffs->password->EditValue ?>"<?php echo $as_staffs->password->EditAttributes() ?>>
</span>
<?php echo $as_staffs->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->lastLogin->Visible) { // lastLogin ?>
	<div id="r_lastLogin" class="form-group">
		<label id="elh_as_staffs_lastLogin" for="x_lastLogin" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->lastLogin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->lastLogin->CellAttributes() ?>>
<span id="el_as_staffs_lastLogin">
<input type="text" data-table="as_staffs" data-field="x_lastLogin" name="x_lastLogin" id="x_lastLogin" placeholder="<?php echo ew_HtmlEncode($as_staffs->lastLogin->getPlaceHolder()) ?>" value="<?php echo $as_staffs->lastLogin->EditValue ?>"<?php echo $as_staffs->lastLogin->EditAttributes() ?>>
</span>
<?php echo $as_staffs->lastLogin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_staffs_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->createdDate->CellAttributes() ?>>
<span id="el_as_staffs_createdDate">
<input type="text" data-table="as_staffs" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_staffs->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_staffs->createdDate->EditValue ?>"<?php echo $as_staffs->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_staffs->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_staffs_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->createdUserID->CellAttributes() ?>>
<span id="el_as_staffs_createdUserID">
<input type="text" data-table="as_staffs" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_staffs->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_staffs->createdUserID->EditValue ?>"<?php echo $as_staffs->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_staffs->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_staffs_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->modifiedDate->CellAttributes() ?>>
<span id="el_as_staffs_modifiedDate">
<input type="text" data-table="as_staffs" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_staffs->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_staffs->modifiedDate->EditValue ?>"<?php echo $as_staffs->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_staffs->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_staffs->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_staffs_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_staffs->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_staffs->modifiedUserID->CellAttributes() ?>>
<span id="el_as_staffs_modifiedUserID">
<input type="text" data-table="as_staffs" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_staffs->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_staffs->modifiedUserID->EditValue ?>"<?php echo $as_staffs->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_staffs->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_staffs_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_staffs_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_staffsadd.Init();
</script>
<?php
$as_staffs_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_staffs_add->Page_Terminate();
?>
