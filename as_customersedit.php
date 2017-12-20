<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_customersinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_customers_edit = NULL; // Initialize page object first

class cas_customers_edit extends cas_customers {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_customers';

	// Page object name
	var $PageObjName = 'as_customers_edit';

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

		// Table object (as_customers)
		if (!isset($GLOBALS["as_customers"]) || get_class($GLOBALS["as_customers"]) == "cas_customers") {
			$GLOBALS["as_customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_customers"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_customers', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_customerslist.php"));
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
		$this->customerID->SetVisibility();
		$this->customerID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->customerCode->SetVisibility();
		$this->customerName->SetVisibility();
		$this->contactPerson->SetVisibility();
		$this->address->SetVisibility();
		$this->address2->SetVisibility();
		$this->village->SetVisibility();
		$this->district->SetVisibility();
		$this->city->SetVisibility();
		$this->zipCode->SetVisibility();
		$this->province->SetVisibility();
		$this->phone1->SetVisibility();
		$this->phone2->SetVisibility();
		$this->phone3->SetVisibility();
		$this->fax1->SetVisibility();
		$this->fax2->SetVisibility();
		$this->fax3->SetVisibility();
		$this->phonecp1->SetVisibility();
		$this->phonecp2->SetVisibility();
		$this->_email->SetVisibility();
		$this->limitBalance->SetVisibility();
		$this->balance->SetVisibility();
		$this->disc1->SetVisibility();
		$this->disc2->SetVisibility();
		$this->disc3->SetVisibility();
		$this->note->SetVisibility();
		$this->npwp->SetVisibility();
		$this->pkpName->SetVisibility();
		$this->staffCode->SetVisibility();
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
		global $EW_EXPORT, $as_customers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_customers);
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
		if (@$_GET["customerID"] <> "") {
			$this->customerID->setQueryStringValue($_GET["customerID"]);
			$this->RecKey["customerID"] = $this->customerID->QueryStringValue;
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
			$this->Page_Terminate("as_customerslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->customerID->CurrentValue) == strval($this->Recordset->fields('customerID'))) {
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
					$this->Page_Terminate("as_customerslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "as_customerslist.php")
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
		if (!$this->customerID->FldIsDetailKey)
			$this->customerID->setFormValue($objForm->GetValue("x_customerID"));
		if (!$this->customerCode->FldIsDetailKey) {
			$this->customerCode->setFormValue($objForm->GetValue("x_customerCode"));
		}
		if (!$this->customerName->FldIsDetailKey) {
			$this->customerName->setFormValue($objForm->GetValue("x_customerName"));
		}
		if (!$this->contactPerson->FldIsDetailKey) {
			$this->contactPerson->setFormValue($objForm->GetValue("x_contactPerson"));
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
		if (!$this->phone1->FldIsDetailKey) {
			$this->phone1->setFormValue($objForm->GetValue("x_phone1"));
		}
		if (!$this->phone2->FldIsDetailKey) {
			$this->phone2->setFormValue($objForm->GetValue("x_phone2"));
		}
		if (!$this->phone3->FldIsDetailKey) {
			$this->phone3->setFormValue($objForm->GetValue("x_phone3"));
		}
		if (!$this->fax1->FldIsDetailKey) {
			$this->fax1->setFormValue($objForm->GetValue("x_fax1"));
		}
		if (!$this->fax2->FldIsDetailKey) {
			$this->fax2->setFormValue($objForm->GetValue("x_fax2"));
		}
		if (!$this->fax3->FldIsDetailKey) {
			$this->fax3->setFormValue($objForm->GetValue("x_fax3"));
		}
		if (!$this->phonecp1->FldIsDetailKey) {
			$this->phonecp1->setFormValue($objForm->GetValue("x_phonecp1"));
		}
		if (!$this->phonecp2->FldIsDetailKey) {
			$this->phonecp2->setFormValue($objForm->GetValue("x_phonecp2"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->limitBalance->FldIsDetailKey) {
			$this->limitBalance->setFormValue($objForm->GetValue("x_limitBalance"));
		}
		if (!$this->balance->FldIsDetailKey) {
			$this->balance->setFormValue($objForm->GetValue("x_balance"));
		}
		if (!$this->disc1->FldIsDetailKey) {
			$this->disc1->setFormValue($objForm->GetValue("x_disc1"));
		}
		if (!$this->disc2->FldIsDetailKey) {
			$this->disc2->setFormValue($objForm->GetValue("x_disc2"));
		}
		if (!$this->disc3->FldIsDetailKey) {
			$this->disc3->setFormValue($objForm->GetValue("x_disc3"));
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
		}
		if (!$this->npwp->FldIsDetailKey) {
			$this->npwp->setFormValue($objForm->GetValue("x_npwp"));
		}
		if (!$this->pkpName->FldIsDetailKey) {
			$this->pkpName->setFormValue($objForm->GetValue("x_pkpName"));
		}
		if (!$this->staffCode->FldIsDetailKey) {
			$this->staffCode->setFormValue($objForm->GetValue("x_staffCode"));
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
		$this->customerID->CurrentValue = $this->customerID->FormValue;
		$this->customerCode->CurrentValue = $this->customerCode->FormValue;
		$this->customerName->CurrentValue = $this->customerName->FormValue;
		$this->contactPerson->CurrentValue = $this->contactPerson->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->address2->CurrentValue = $this->address2->FormValue;
		$this->village->CurrentValue = $this->village->FormValue;
		$this->district->CurrentValue = $this->district->FormValue;
		$this->city->CurrentValue = $this->city->FormValue;
		$this->zipCode->CurrentValue = $this->zipCode->FormValue;
		$this->province->CurrentValue = $this->province->FormValue;
		$this->phone1->CurrentValue = $this->phone1->FormValue;
		$this->phone2->CurrentValue = $this->phone2->FormValue;
		$this->phone3->CurrentValue = $this->phone3->FormValue;
		$this->fax1->CurrentValue = $this->fax1->FormValue;
		$this->fax2->CurrentValue = $this->fax2->FormValue;
		$this->fax3->CurrentValue = $this->fax3->FormValue;
		$this->phonecp1->CurrentValue = $this->phonecp1->FormValue;
		$this->phonecp2->CurrentValue = $this->phonecp2->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->limitBalance->CurrentValue = $this->limitBalance->FormValue;
		$this->balance->CurrentValue = $this->balance->FormValue;
		$this->disc1->CurrentValue = $this->disc1->FormValue;
		$this->disc2->CurrentValue = $this->disc2->FormValue;
		$this->disc3->CurrentValue = $this->disc3->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
		$this->npwp->CurrentValue = $this->npwp->FormValue;
		$this->pkpName->CurrentValue = $this->pkpName->FormValue;
		$this->staffCode->CurrentValue = $this->staffCode->FormValue;
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
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerCode->setDbValue($rs->fields('customerCode'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->contactPerson->setDbValue($rs->fields('contactPerson'));
		$this->address->setDbValue($rs->fields('address'));
		$this->address2->setDbValue($rs->fields('address2'));
		$this->village->setDbValue($rs->fields('village'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city->setDbValue($rs->fields('city'));
		$this->zipCode->setDbValue($rs->fields('zipCode'));
		$this->province->setDbValue($rs->fields('province'));
		$this->phone1->setDbValue($rs->fields('phone1'));
		$this->phone2->setDbValue($rs->fields('phone2'));
		$this->phone3->setDbValue($rs->fields('phone3'));
		$this->fax1->setDbValue($rs->fields('fax1'));
		$this->fax2->setDbValue($rs->fields('fax2'));
		$this->fax3->setDbValue($rs->fields('fax3'));
		$this->phonecp1->setDbValue($rs->fields('phonecp1'));
		$this->phonecp2->setDbValue($rs->fields('phonecp2'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->limitBalance->setDbValue($rs->fields('limitBalance'));
		$this->balance->setDbValue($rs->fields('balance'));
		$this->disc1->setDbValue($rs->fields('disc1'));
		$this->disc2->setDbValue($rs->fields('disc2'));
		$this->disc3->setDbValue($rs->fields('disc3'));
		$this->note->setDbValue($rs->fields('note'));
		$this->npwp->setDbValue($rs->fields('npwp'));
		$this->pkpName->setDbValue($rs->fields('pkpName'));
		$this->staffCode->setDbValue($rs->fields('staffCode'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->customerID->DbValue = $row['customerID'];
		$this->customerCode->DbValue = $row['customerCode'];
		$this->customerName->DbValue = $row['customerName'];
		$this->contactPerson->DbValue = $row['contactPerson'];
		$this->address->DbValue = $row['address'];
		$this->address2->DbValue = $row['address2'];
		$this->village->DbValue = $row['village'];
		$this->district->DbValue = $row['district'];
		$this->city->DbValue = $row['city'];
		$this->zipCode->DbValue = $row['zipCode'];
		$this->province->DbValue = $row['province'];
		$this->phone1->DbValue = $row['phone1'];
		$this->phone2->DbValue = $row['phone2'];
		$this->phone3->DbValue = $row['phone3'];
		$this->fax1->DbValue = $row['fax1'];
		$this->fax2->DbValue = $row['fax2'];
		$this->fax3->DbValue = $row['fax3'];
		$this->phonecp1->DbValue = $row['phonecp1'];
		$this->phonecp2->DbValue = $row['phonecp2'];
		$this->_email->DbValue = $row['email'];
		$this->limitBalance->DbValue = $row['limitBalance'];
		$this->balance->DbValue = $row['balance'];
		$this->disc1->DbValue = $row['disc1'];
		$this->disc2->DbValue = $row['disc2'];
		$this->disc3->DbValue = $row['disc3'];
		$this->note->DbValue = $row['note'];
		$this->npwp->DbValue = $row['npwp'];
		$this->pkpName->DbValue = $row['pkpName'];
		$this->staffCode->DbValue = $row['staffCode'];
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

		if ($this->balance->FormValue == $this->balance->CurrentValue && is_numeric(ew_StrToFloat($this->balance->CurrentValue)))
			$this->balance->CurrentValue = ew_StrToFloat($this->balance->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// customerID
		// customerCode
		// customerName
		// contactPerson
		// address
		// address2
		// village
		// district
		// city
		// zipCode
		// province
		// phone1
		// phone2
		// phone3
		// fax1
		// fax2
		// fax3
		// phonecp1
		// phonecp2
		// email
		// limitBalance
		// balance
		// disc1
		// disc2
		// disc3
		// note
		// npwp
		// pkpName
		// staffCode
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerCode
		$this->customerCode->ViewValue = $this->customerCode->CurrentValue;
		$this->customerCode->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// contactPerson
		$this->contactPerson->ViewValue = $this->contactPerson->CurrentValue;
		$this->contactPerson->ViewCustomAttributes = "";

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

		// phone1
		$this->phone1->ViewValue = $this->phone1->CurrentValue;
		$this->phone1->ViewCustomAttributes = "";

		// phone2
		$this->phone2->ViewValue = $this->phone2->CurrentValue;
		$this->phone2->ViewCustomAttributes = "";

		// phone3
		$this->phone3->ViewValue = $this->phone3->CurrentValue;
		$this->phone3->ViewCustomAttributes = "";

		// fax1
		$this->fax1->ViewValue = $this->fax1->CurrentValue;
		$this->fax1->ViewCustomAttributes = "";

		// fax2
		$this->fax2->ViewValue = $this->fax2->CurrentValue;
		$this->fax2->ViewCustomAttributes = "";

		// fax3
		$this->fax3->ViewValue = $this->fax3->CurrentValue;
		$this->fax3->ViewCustomAttributes = "";

		// phonecp1
		$this->phonecp1->ViewValue = $this->phonecp1->CurrentValue;
		$this->phonecp1->ViewCustomAttributes = "";

		// phonecp2
		$this->phonecp2->ViewValue = $this->phonecp2->CurrentValue;
		$this->phonecp2->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// limitBalance
		$this->limitBalance->ViewValue = $this->limitBalance->CurrentValue;
		$this->limitBalance->ViewCustomAttributes = "";

		// balance
		$this->balance->ViewValue = $this->balance->CurrentValue;
		$this->balance->ViewCustomAttributes = "";

		// disc1
		$this->disc1->ViewValue = $this->disc1->CurrentValue;
		$this->disc1->ViewCustomAttributes = "";

		// disc2
		$this->disc2->ViewValue = $this->disc2->CurrentValue;
		$this->disc2->ViewCustomAttributes = "";

		// disc3
		$this->disc3->ViewValue = $this->disc3->CurrentValue;
		$this->disc3->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// npwp
		$this->npwp->ViewValue = $this->npwp->CurrentValue;
		$this->npwp->ViewCustomAttributes = "";

		// pkpName
		$this->pkpName->ViewValue = $this->pkpName->CurrentValue;
		$this->pkpName->ViewCustomAttributes = "";

		// staffCode
		$this->staffCode->ViewValue = $this->staffCode->CurrentValue;
		$this->staffCode->ViewCustomAttributes = "";

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

			// customerID
			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";
			$this->customerID->TooltipValue = "";

			// customerCode
			$this->customerCode->LinkCustomAttributes = "";
			$this->customerCode->HrefValue = "";
			$this->customerCode->TooltipValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";
			$this->customerName->TooltipValue = "";

			// contactPerson
			$this->contactPerson->LinkCustomAttributes = "";
			$this->contactPerson->HrefValue = "";
			$this->contactPerson->TooltipValue = "";

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

			// phone1
			$this->phone1->LinkCustomAttributes = "";
			$this->phone1->HrefValue = "";
			$this->phone1->TooltipValue = "";

			// phone2
			$this->phone2->LinkCustomAttributes = "";
			$this->phone2->HrefValue = "";
			$this->phone2->TooltipValue = "";

			// phone3
			$this->phone3->LinkCustomAttributes = "";
			$this->phone3->HrefValue = "";
			$this->phone3->TooltipValue = "";

			// fax1
			$this->fax1->LinkCustomAttributes = "";
			$this->fax1->HrefValue = "";
			$this->fax1->TooltipValue = "";

			// fax2
			$this->fax2->LinkCustomAttributes = "";
			$this->fax2->HrefValue = "";
			$this->fax2->TooltipValue = "";

			// fax3
			$this->fax3->LinkCustomAttributes = "";
			$this->fax3->HrefValue = "";
			$this->fax3->TooltipValue = "";

			// phonecp1
			$this->phonecp1->LinkCustomAttributes = "";
			$this->phonecp1->HrefValue = "";
			$this->phonecp1->TooltipValue = "";

			// phonecp2
			$this->phonecp2->LinkCustomAttributes = "";
			$this->phonecp2->HrefValue = "";
			$this->phonecp2->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// limitBalance
			$this->limitBalance->LinkCustomAttributes = "";
			$this->limitBalance->HrefValue = "";
			$this->limitBalance->TooltipValue = "";

			// balance
			$this->balance->LinkCustomAttributes = "";
			$this->balance->HrefValue = "";
			$this->balance->TooltipValue = "";

			// disc1
			$this->disc1->LinkCustomAttributes = "";
			$this->disc1->HrefValue = "";
			$this->disc1->TooltipValue = "";

			// disc2
			$this->disc2->LinkCustomAttributes = "";
			$this->disc2->HrefValue = "";
			$this->disc2->TooltipValue = "";

			// disc3
			$this->disc3->LinkCustomAttributes = "";
			$this->disc3->HrefValue = "";
			$this->disc3->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

			// npwp
			$this->npwp->LinkCustomAttributes = "";
			$this->npwp->HrefValue = "";
			$this->npwp->TooltipValue = "";

			// pkpName
			$this->pkpName->LinkCustomAttributes = "";
			$this->pkpName->HrefValue = "";
			$this->pkpName->TooltipValue = "";

			// staffCode
			$this->staffCode->LinkCustomAttributes = "";
			$this->staffCode->HrefValue = "";
			$this->staffCode->TooltipValue = "";

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

			// customerID
			$this->customerID->EditAttrs["class"] = "form-control";
			$this->customerID->EditCustomAttributes = "";
			$this->customerID->EditValue = $this->customerID->CurrentValue;
			$this->customerID->ViewCustomAttributes = "";

			// customerCode
			$this->customerCode->EditAttrs["class"] = "form-control";
			$this->customerCode->EditCustomAttributes = "";
			$this->customerCode->EditValue = ew_HtmlEncode($this->customerCode->CurrentValue);
			$this->customerCode->PlaceHolder = ew_RemoveHtml($this->customerCode->FldCaption());

			// customerName
			$this->customerName->EditAttrs["class"] = "form-control";
			$this->customerName->EditCustomAttributes = "";
			$this->customerName->EditValue = ew_HtmlEncode($this->customerName->CurrentValue);
			$this->customerName->PlaceHolder = ew_RemoveHtml($this->customerName->FldCaption());

			// contactPerson
			$this->contactPerson->EditAttrs["class"] = "form-control";
			$this->contactPerson->EditCustomAttributes = "";
			$this->contactPerson->EditValue = ew_HtmlEncode($this->contactPerson->CurrentValue);
			$this->contactPerson->PlaceHolder = ew_RemoveHtml($this->contactPerson->FldCaption());

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

			// phone1
			$this->phone1->EditAttrs["class"] = "form-control";
			$this->phone1->EditCustomAttributes = "";
			$this->phone1->EditValue = ew_HtmlEncode($this->phone1->CurrentValue);
			$this->phone1->PlaceHolder = ew_RemoveHtml($this->phone1->FldCaption());

			// phone2
			$this->phone2->EditAttrs["class"] = "form-control";
			$this->phone2->EditCustomAttributes = "";
			$this->phone2->EditValue = ew_HtmlEncode($this->phone2->CurrentValue);
			$this->phone2->PlaceHolder = ew_RemoveHtml($this->phone2->FldCaption());

			// phone3
			$this->phone3->EditAttrs["class"] = "form-control";
			$this->phone3->EditCustomAttributes = "";
			$this->phone3->EditValue = ew_HtmlEncode($this->phone3->CurrentValue);
			$this->phone3->PlaceHolder = ew_RemoveHtml($this->phone3->FldCaption());

			// fax1
			$this->fax1->EditAttrs["class"] = "form-control";
			$this->fax1->EditCustomAttributes = "";
			$this->fax1->EditValue = ew_HtmlEncode($this->fax1->CurrentValue);
			$this->fax1->PlaceHolder = ew_RemoveHtml($this->fax1->FldCaption());

			// fax2
			$this->fax2->EditAttrs["class"] = "form-control";
			$this->fax2->EditCustomAttributes = "";
			$this->fax2->EditValue = ew_HtmlEncode($this->fax2->CurrentValue);
			$this->fax2->PlaceHolder = ew_RemoveHtml($this->fax2->FldCaption());

			// fax3
			$this->fax3->EditAttrs["class"] = "form-control";
			$this->fax3->EditCustomAttributes = "";
			$this->fax3->EditValue = ew_HtmlEncode($this->fax3->CurrentValue);
			$this->fax3->PlaceHolder = ew_RemoveHtml($this->fax3->FldCaption());

			// phonecp1
			$this->phonecp1->EditAttrs["class"] = "form-control";
			$this->phonecp1->EditCustomAttributes = "";
			$this->phonecp1->EditValue = ew_HtmlEncode($this->phonecp1->CurrentValue);
			$this->phonecp1->PlaceHolder = ew_RemoveHtml($this->phonecp1->FldCaption());

			// phonecp2
			$this->phonecp2->EditAttrs["class"] = "form-control";
			$this->phonecp2->EditCustomAttributes = "";
			$this->phonecp2->EditValue = ew_HtmlEncode($this->phonecp2->CurrentValue);
			$this->phonecp2->PlaceHolder = ew_RemoveHtml($this->phonecp2->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// limitBalance
			$this->limitBalance->EditAttrs["class"] = "form-control";
			$this->limitBalance->EditCustomAttributes = "";
			$this->limitBalance->EditValue = ew_HtmlEncode($this->limitBalance->CurrentValue);
			$this->limitBalance->PlaceHolder = ew_RemoveHtml($this->limitBalance->FldCaption());

			// balance
			$this->balance->EditAttrs["class"] = "form-control";
			$this->balance->EditCustomAttributes = "";
			$this->balance->EditValue = ew_HtmlEncode($this->balance->CurrentValue);
			$this->balance->PlaceHolder = ew_RemoveHtml($this->balance->FldCaption());
			if (strval($this->balance->EditValue) <> "" && is_numeric($this->balance->EditValue)) $this->balance->EditValue = ew_FormatNumber($this->balance->EditValue, -2, -1, -2, 0);

			// disc1
			$this->disc1->EditAttrs["class"] = "form-control";
			$this->disc1->EditCustomAttributes = "";
			$this->disc1->EditValue = ew_HtmlEncode($this->disc1->CurrentValue);
			$this->disc1->PlaceHolder = ew_RemoveHtml($this->disc1->FldCaption());

			// disc2
			$this->disc2->EditAttrs["class"] = "form-control";
			$this->disc2->EditCustomAttributes = "";
			$this->disc2->EditValue = ew_HtmlEncode($this->disc2->CurrentValue);
			$this->disc2->PlaceHolder = ew_RemoveHtml($this->disc2->FldCaption());

			// disc3
			$this->disc3->EditAttrs["class"] = "form-control";
			$this->disc3->EditCustomAttributes = "";
			$this->disc3->EditValue = ew_HtmlEncode($this->disc3->CurrentValue);
			$this->disc3->PlaceHolder = ew_RemoveHtml($this->disc3->FldCaption());

			// note
			$this->note->EditAttrs["class"] = "form-control";
			$this->note->EditCustomAttributes = "";
			$this->note->EditValue = ew_HtmlEncode($this->note->CurrentValue);
			$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

			// npwp
			$this->npwp->EditAttrs["class"] = "form-control";
			$this->npwp->EditCustomAttributes = "";
			$this->npwp->EditValue = ew_HtmlEncode($this->npwp->CurrentValue);
			$this->npwp->PlaceHolder = ew_RemoveHtml($this->npwp->FldCaption());

			// pkpName
			$this->pkpName->EditAttrs["class"] = "form-control";
			$this->pkpName->EditCustomAttributes = "";
			$this->pkpName->EditValue = ew_HtmlEncode($this->pkpName->CurrentValue);
			$this->pkpName->PlaceHolder = ew_RemoveHtml($this->pkpName->FldCaption());

			// staffCode
			$this->staffCode->EditAttrs["class"] = "form-control";
			$this->staffCode->EditCustomAttributes = "";
			$this->staffCode->EditValue = ew_HtmlEncode($this->staffCode->CurrentValue);
			$this->staffCode->PlaceHolder = ew_RemoveHtml($this->staffCode->FldCaption());

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
			// customerID

			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";

			// customerCode
			$this->customerCode->LinkCustomAttributes = "";
			$this->customerCode->HrefValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";

			// contactPerson
			$this->contactPerson->LinkCustomAttributes = "";
			$this->contactPerson->HrefValue = "";

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

			// phone1
			$this->phone1->LinkCustomAttributes = "";
			$this->phone1->HrefValue = "";

			// phone2
			$this->phone2->LinkCustomAttributes = "";
			$this->phone2->HrefValue = "";

			// phone3
			$this->phone3->LinkCustomAttributes = "";
			$this->phone3->HrefValue = "";

			// fax1
			$this->fax1->LinkCustomAttributes = "";
			$this->fax1->HrefValue = "";

			// fax2
			$this->fax2->LinkCustomAttributes = "";
			$this->fax2->HrefValue = "";

			// fax3
			$this->fax3->LinkCustomAttributes = "";
			$this->fax3->HrefValue = "";

			// phonecp1
			$this->phonecp1->LinkCustomAttributes = "";
			$this->phonecp1->HrefValue = "";

			// phonecp2
			$this->phonecp2->LinkCustomAttributes = "";
			$this->phonecp2->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// limitBalance
			$this->limitBalance->LinkCustomAttributes = "";
			$this->limitBalance->HrefValue = "";

			// balance
			$this->balance->LinkCustomAttributes = "";
			$this->balance->HrefValue = "";

			// disc1
			$this->disc1->LinkCustomAttributes = "";
			$this->disc1->HrefValue = "";

			// disc2
			$this->disc2->LinkCustomAttributes = "";
			$this->disc2->HrefValue = "";

			// disc3
			$this->disc3->LinkCustomAttributes = "";
			$this->disc3->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

			// npwp
			$this->npwp->LinkCustomAttributes = "";
			$this->npwp->HrefValue = "";

			// pkpName
			$this->pkpName->LinkCustomAttributes = "";
			$this->pkpName->HrefValue = "";

			// staffCode
			$this->staffCode->LinkCustomAttributes = "";
			$this->staffCode->HrefValue = "";

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
		if (!$this->customerCode->FldIsDetailKey && !is_null($this->customerCode->FormValue) && $this->customerCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customerCode->FldCaption(), $this->customerCode->ReqErrMsg));
		}
		if (!$this->customerName->FldIsDetailKey && !is_null($this->customerName->FormValue) && $this->customerName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customerName->FldCaption(), $this->customerName->ReqErrMsg));
		}
		if (!$this->contactPerson->FldIsDetailKey && !is_null($this->contactPerson->FormValue) && $this->contactPerson->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->contactPerson->FldCaption(), $this->contactPerson->ReqErrMsg));
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
		if (!ew_CheckInteger($this->zipCode->FormValue)) {
			ew_AddMessage($gsFormError, $this->zipCode->FldErrMsg());
		}
		if (!$this->province->FldIsDetailKey && !is_null($this->province->FormValue) && $this->province->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province->FldCaption(), $this->province->ReqErrMsg));
		}
		if (!$this->phone1->FldIsDetailKey && !is_null($this->phone1->FormValue) && $this->phone1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone1->FldCaption(), $this->phone1->ReqErrMsg));
		}
		if (!$this->phone2->FldIsDetailKey && !is_null($this->phone2->FormValue) && $this->phone2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone2->FldCaption(), $this->phone2->ReqErrMsg));
		}
		if (!$this->phone3->FldIsDetailKey && !is_null($this->phone3->FormValue) && $this->phone3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone3->FldCaption(), $this->phone3->ReqErrMsg));
		}
		if (!$this->fax1->FldIsDetailKey && !is_null($this->fax1->FormValue) && $this->fax1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fax1->FldCaption(), $this->fax1->ReqErrMsg));
		}
		if (!$this->fax2->FldIsDetailKey && !is_null($this->fax2->FormValue) && $this->fax2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fax2->FldCaption(), $this->fax2->ReqErrMsg));
		}
		if (!$this->fax3->FldIsDetailKey && !is_null($this->fax3->FormValue) && $this->fax3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fax3->FldCaption(), $this->fax3->ReqErrMsg));
		}
		if (!$this->phonecp1->FldIsDetailKey && !is_null($this->phonecp1->FormValue) && $this->phonecp1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phonecp1->FldCaption(), $this->phonecp1->ReqErrMsg));
		}
		if (!$this->phonecp2->FldIsDetailKey && !is_null($this->phonecp2->FormValue) && $this->phonecp2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phonecp2->FldCaption(), $this->phonecp2->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->limitBalance->FldIsDetailKey && !is_null($this->limitBalance->FormValue) && $this->limitBalance->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->limitBalance->FldCaption(), $this->limitBalance->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->limitBalance->FormValue)) {
			ew_AddMessage($gsFormError, $this->limitBalance->FldErrMsg());
		}
		if (!$this->balance->FldIsDetailKey && !is_null($this->balance->FormValue) && $this->balance->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->balance->FldCaption(), $this->balance->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->balance->FormValue)) {
			ew_AddMessage($gsFormError, $this->balance->FldErrMsg());
		}
		if (!$this->disc1->FldIsDetailKey && !is_null($this->disc1->FormValue) && $this->disc1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->disc1->FldCaption(), $this->disc1->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->disc1->FormValue)) {
			ew_AddMessage($gsFormError, $this->disc1->FldErrMsg());
		}
		if (!$this->disc2->FldIsDetailKey && !is_null($this->disc2->FormValue) && $this->disc2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->disc2->FldCaption(), $this->disc2->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->disc2->FormValue)) {
			ew_AddMessage($gsFormError, $this->disc2->FldErrMsg());
		}
		if (!$this->disc3->FldIsDetailKey && !is_null($this->disc3->FormValue) && $this->disc3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->disc3->FldCaption(), $this->disc3->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->disc3->FormValue)) {
			ew_AddMessage($gsFormError, $this->disc3->FldErrMsg());
		}
		if (!$this->note->FldIsDetailKey && !is_null($this->note->FormValue) && $this->note->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->note->FldCaption(), $this->note->ReqErrMsg));
		}
		if (!$this->npwp->FldIsDetailKey && !is_null($this->npwp->FormValue) && $this->npwp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->npwp->FldCaption(), $this->npwp->ReqErrMsg));
		}
		if (!$this->pkpName->FldIsDetailKey && !is_null($this->pkpName->FormValue) && $this->pkpName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pkpName->FldCaption(), $this->pkpName->ReqErrMsg));
		}
		if (!$this->staffCode->FldIsDetailKey && !is_null($this->staffCode->FormValue) && $this->staffCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffCode->FldCaption(), $this->staffCode->ReqErrMsg));
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

			// customerCode
			$this->customerCode->SetDbValueDef($rsnew, $this->customerCode->CurrentValue, "", $this->customerCode->ReadOnly);

			// customerName
			$this->customerName->SetDbValueDef($rsnew, $this->customerName->CurrentValue, "", $this->customerName->ReadOnly);

			// contactPerson
			$this->contactPerson->SetDbValueDef($rsnew, $this->contactPerson->CurrentValue, "", $this->contactPerson->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly);

			// address2
			$this->address2->SetDbValueDef($rsnew, $this->address2->CurrentValue, "", $this->address2->ReadOnly);

			// village
			$this->village->SetDbValueDef($rsnew, $this->village->CurrentValue, "", $this->village->ReadOnly);

			// district
			$this->district->SetDbValueDef($rsnew, $this->district->CurrentValue, "", $this->district->ReadOnly);

			// city
			$this->city->SetDbValueDef($rsnew, $this->city->CurrentValue, "", $this->city->ReadOnly);

			// zipCode
			$this->zipCode->SetDbValueDef($rsnew, $this->zipCode->CurrentValue, 0, $this->zipCode->ReadOnly);

			// province
			$this->province->SetDbValueDef($rsnew, $this->province->CurrentValue, "", $this->province->ReadOnly);

			// phone1
			$this->phone1->SetDbValueDef($rsnew, $this->phone1->CurrentValue, "", $this->phone1->ReadOnly);

			// phone2
			$this->phone2->SetDbValueDef($rsnew, $this->phone2->CurrentValue, "", $this->phone2->ReadOnly);

			// phone3
			$this->phone3->SetDbValueDef($rsnew, $this->phone3->CurrentValue, "", $this->phone3->ReadOnly);

			// fax1
			$this->fax1->SetDbValueDef($rsnew, $this->fax1->CurrentValue, "", $this->fax1->ReadOnly);

			// fax2
			$this->fax2->SetDbValueDef($rsnew, $this->fax2->CurrentValue, "", $this->fax2->ReadOnly);

			// fax3
			$this->fax3->SetDbValueDef($rsnew, $this->fax3->CurrentValue, "", $this->fax3->ReadOnly);

			// phonecp1
			$this->phonecp1->SetDbValueDef($rsnew, $this->phonecp1->CurrentValue, "", $this->phonecp1->ReadOnly);

			// phonecp2
			$this->phonecp2->SetDbValueDef($rsnew, $this->phonecp2->CurrentValue, "", $this->phonecp2->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// limitBalance
			$this->limitBalance->SetDbValueDef($rsnew, $this->limitBalance->CurrentValue, 0, $this->limitBalance->ReadOnly);

			// balance
			$this->balance->SetDbValueDef($rsnew, $this->balance->CurrentValue, 0, $this->balance->ReadOnly);

			// disc1
			$this->disc1->SetDbValueDef($rsnew, $this->disc1->CurrentValue, 0, $this->disc1->ReadOnly);

			// disc2
			$this->disc2->SetDbValueDef($rsnew, $this->disc2->CurrentValue, 0, $this->disc2->ReadOnly);

			// disc3
			$this->disc3->SetDbValueDef($rsnew, $this->disc3->CurrentValue, 0, $this->disc3->ReadOnly);

			// note
			$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", $this->note->ReadOnly);

			// npwp
			$this->npwp->SetDbValueDef($rsnew, $this->npwp->CurrentValue, "", $this->npwp->ReadOnly);

			// pkpName
			$this->pkpName->SetDbValueDef($rsnew, $this->pkpName->CurrentValue, "", $this->pkpName->ReadOnly);

			// staffCode
			$this->staffCode->SetDbValueDef($rsnew, $this->staffCode->CurrentValue, "", $this->staffCode->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_customerslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_customers_edit)) $as_customers_edit = new cas_customers_edit();

// Page init
$as_customers_edit->Page_Init();

// Page main
$as_customers_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_customers_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fas_customersedit = new ew_Form("fas_customersedit", "edit");

// Validate form
fas_customersedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_customerCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->customerCode->FldCaption(), $as_customers->customerCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->customerName->FldCaption(), $as_customers->customerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_contactPerson");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->contactPerson->FldCaption(), $as_customers->contactPerson->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->address->FldCaption(), $as_customers->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->address2->FldCaption(), $as_customers->address2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_village");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->village->FldCaption(), $as_customers->village->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_district");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->district->FldCaption(), $as_customers->district->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_city");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->city->FldCaption(), $as_customers->city->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zipCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->zipCode->FldCaption(), $as_customers->zipCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zipCode");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->zipCode->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_province");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->province->FldCaption(), $as_customers->province->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->phone1->FldCaption(), $as_customers->phone1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->phone2->FldCaption(), $as_customers->phone2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->phone3->FldCaption(), $as_customers->phone3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->fax1->FldCaption(), $as_customers->fax1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->fax2->FldCaption(), $as_customers->fax2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->fax3->FldCaption(), $as_customers->fax3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phonecp1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->phonecp1->FldCaption(), $as_customers->phonecp1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phonecp2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->phonecp2->FldCaption(), $as_customers->phonecp2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->_email->FldCaption(), $as_customers->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_limitBalance");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->limitBalance->FldCaption(), $as_customers->limitBalance->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_limitBalance");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->limitBalance->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_balance");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->balance->FldCaption(), $as_customers->balance->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_balance");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->balance->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_disc1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->disc1->FldCaption(), $as_customers->disc1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_disc1");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->disc1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_disc2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->disc2->FldCaption(), $as_customers->disc2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_disc2");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->disc2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_disc3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->disc3->FldCaption(), $as_customers->disc3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_disc3");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->disc3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->note->FldCaption(), $as_customers->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_npwp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->npwp->FldCaption(), $as_customers->npwp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pkpName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->pkpName->FldCaption(), $as_customers->pkpName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->staffCode->FldCaption(), $as_customers->staffCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->createdDate->FldCaption(), $as_customers->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->createdUserID->FldCaption(), $as_customers->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->modifiedDate->FldCaption(), $as_customers->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_customers->modifiedUserID->FldCaption(), $as_customers->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_customers->modifiedUserID->FldErrMsg()) ?>");

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
fas_customersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_customersedit.ValidateRequired = true;
<?php } else { ?>
fas_customersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_customers_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_customers_edit->ShowPageHeader(); ?>
<?php
$as_customers_edit->ShowMessage();
?>
<?php if (!$as_customers_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_customers_edit->Pager)) $as_customers_edit->Pager = new cPrevNextPager($as_customers_edit->StartRec, $as_customers_edit->DisplayRecs, $as_customers_edit->TotalRecs) ?>
<?php if ($as_customers_edit->Pager->RecordCount > 0 && $as_customers_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fas_customersedit" id="fas_customersedit" class="<?php echo $as_customers_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_customers_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_customers_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_customers">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($as_customers_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_customers->customerID->Visible) { // customerID ?>
	<div id="r_customerID" class="form-group">
		<label id="elh_as_customers_customerID" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->customerID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->customerID->CellAttributes() ?>>
<span id="el_as_customers_customerID">
<span<?php echo $as_customers->customerID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $as_customers->customerID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="as_customers" data-field="x_customerID" name="x_customerID" id="x_customerID" value="<?php echo ew_HtmlEncode($as_customers->customerID->CurrentValue) ?>">
<?php echo $as_customers->customerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
	<div id="r_customerCode" class="form-group">
		<label id="elh_as_customers_customerCode" for="x_customerCode" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->customerCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->customerCode->CellAttributes() ?>>
<span id="el_as_customers_customerCode">
<input type="text" data-table="as_customers" data-field="x_customerCode" name="x_customerCode" id="x_customerCode" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($as_customers->customerCode->getPlaceHolder()) ?>" value="<?php echo $as_customers->customerCode->EditValue ?>"<?php echo $as_customers->customerCode->EditAttributes() ?>>
</span>
<?php echo $as_customers->customerCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->customerName->Visible) { // customerName ?>
	<div id="r_customerName" class="form-group">
		<label id="elh_as_customers_customerName" for="x_customerName" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->customerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->customerName->CellAttributes() ?>>
<span id="el_as_customers_customerName">
<input type="text" data-table="as_customers" data-field="x_customerName" name="x_customerName" id="x_customerName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->customerName->getPlaceHolder()) ?>" value="<?php echo $as_customers->customerName->EditValue ?>"<?php echo $as_customers->customerName->EditAttributes() ?>>
</span>
<?php echo $as_customers->customerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
	<div id="r_contactPerson" class="form-group">
		<label id="elh_as_customers_contactPerson" for="x_contactPerson" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->contactPerson->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->contactPerson->CellAttributes() ?>>
<span id="el_as_customers_contactPerson">
<input type="text" data-table="as_customers" data-field="x_contactPerson" name="x_contactPerson" id="x_contactPerson" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->contactPerson->getPlaceHolder()) ?>" value="<?php echo $as_customers->contactPerson->EditValue ?>"<?php echo $as_customers->contactPerson->EditAttributes() ?>>
</span>
<?php echo $as_customers->contactPerson->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_as_customers_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->address->CellAttributes() ?>>
<span id="el_as_customers_address">
<input type="text" data-table="as_customers" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="160" placeholder="<?php echo ew_HtmlEncode($as_customers->address->getPlaceHolder()) ?>" value="<?php echo $as_customers->address->EditValue ?>"<?php echo $as_customers->address->EditAttributes() ?>>
</span>
<?php echo $as_customers->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->address2->Visible) { // address2 ?>
	<div id="r_address2" class="form-group">
		<label id="elh_as_customers_address2" for="x_address2" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->address2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->address2->CellAttributes() ?>>
<span id="el_as_customers_address2">
<textarea data-table="as_customers" data-field="x_address2" name="x_address2" id="x_address2" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_customers->address2->getPlaceHolder()) ?>"<?php echo $as_customers->address2->EditAttributes() ?>><?php echo $as_customers->address2->EditValue ?></textarea>
</span>
<?php echo $as_customers->address2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->village->Visible) { // village ?>
	<div id="r_village" class="form-group">
		<label id="elh_as_customers_village" for="x_village" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->village->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->village->CellAttributes() ?>>
<span id="el_as_customers_village">
<input type="text" data-table="as_customers" data-field="x_village" name="x_village" id="x_village" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->village->getPlaceHolder()) ?>" value="<?php echo $as_customers->village->EditValue ?>"<?php echo $as_customers->village->EditAttributes() ?>>
</span>
<?php echo $as_customers->village->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->district->Visible) { // district ?>
	<div id="r_district" class="form-group">
		<label id="elh_as_customers_district" for="x_district" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->district->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->district->CellAttributes() ?>>
<span id="el_as_customers_district">
<input type="text" data-table="as_customers" data-field="x_district" name="x_district" id="x_district" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->district->getPlaceHolder()) ?>" value="<?php echo $as_customers->district->EditValue ?>"<?php echo $as_customers->district->EditAttributes() ?>>
</span>
<?php echo $as_customers->district->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->city->Visible) { // city ?>
	<div id="r_city" class="form-group">
		<label id="elh_as_customers_city" for="x_city" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->city->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->city->CellAttributes() ?>>
<span id="el_as_customers_city">
<input type="text" data-table="as_customers" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->city->getPlaceHolder()) ?>" value="<?php echo $as_customers->city->EditValue ?>"<?php echo $as_customers->city->EditAttributes() ?>>
</span>
<?php echo $as_customers->city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
	<div id="r_zipCode" class="form-group">
		<label id="elh_as_customers_zipCode" for="x_zipCode" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->zipCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->zipCode->CellAttributes() ?>>
<span id="el_as_customers_zipCode">
<input type="text" data-table="as_customers" data-field="x_zipCode" name="x_zipCode" id="x_zipCode" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->zipCode->getPlaceHolder()) ?>" value="<?php echo $as_customers->zipCode->EditValue ?>"<?php echo $as_customers->zipCode->EditAttributes() ?>>
</span>
<?php echo $as_customers->zipCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->province->Visible) { // province ?>
	<div id="r_province" class="form-group">
		<label id="elh_as_customers_province" for="x_province" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->province->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->province->CellAttributes() ?>>
<span id="el_as_customers_province">
<input type="text" data-table="as_customers" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->province->getPlaceHolder()) ?>" value="<?php echo $as_customers->province->EditValue ?>"<?php echo $as_customers->province->EditAttributes() ?>>
</span>
<?php echo $as_customers->province->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->phone1->Visible) { // phone1 ?>
	<div id="r_phone1" class="form-group">
		<label id="elh_as_customers_phone1" for="x_phone1" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->phone1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->phone1->CellAttributes() ?>>
<span id="el_as_customers_phone1">
<input type="text" data-table="as_customers" data-field="x_phone1" name="x_phone1" id="x_phone1" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($as_customers->phone1->getPlaceHolder()) ?>" value="<?php echo $as_customers->phone1->EditValue ?>"<?php echo $as_customers->phone1->EditAttributes() ?>>
</span>
<?php echo $as_customers->phone1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->phone2->Visible) { // phone2 ?>
	<div id="r_phone2" class="form-group">
		<label id="elh_as_customers_phone2" for="x_phone2" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->phone2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->phone2->CellAttributes() ?>>
<span id="el_as_customers_phone2">
<input type="text" data-table="as_customers" data-field="x_phone2" name="x_phone2" id="x_phone2" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->phone2->getPlaceHolder()) ?>" value="<?php echo $as_customers->phone2->EditValue ?>"<?php echo $as_customers->phone2->EditAttributes() ?>>
</span>
<?php echo $as_customers->phone2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->phone3->Visible) { // phone3 ?>
	<div id="r_phone3" class="form-group">
		<label id="elh_as_customers_phone3" for="x_phone3" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->phone3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->phone3->CellAttributes() ?>>
<span id="el_as_customers_phone3">
<input type="text" data-table="as_customers" data-field="x_phone3" name="x_phone3" id="x_phone3" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->phone3->getPlaceHolder()) ?>" value="<?php echo $as_customers->phone3->EditValue ?>"<?php echo $as_customers->phone3->EditAttributes() ?>>
</span>
<?php echo $as_customers->phone3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->fax1->Visible) { // fax1 ?>
	<div id="r_fax1" class="form-group">
		<label id="elh_as_customers_fax1" for="x_fax1" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->fax1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->fax1->CellAttributes() ?>>
<span id="el_as_customers_fax1">
<input type="text" data-table="as_customers" data-field="x_fax1" name="x_fax1" id="x_fax1" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->fax1->getPlaceHolder()) ?>" value="<?php echo $as_customers->fax1->EditValue ?>"<?php echo $as_customers->fax1->EditAttributes() ?>>
</span>
<?php echo $as_customers->fax1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->fax2->Visible) { // fax2 ?>
	<div id="r_fax2" class="form-group">
		<label id="elh_as_customers_fax2" for="x_fax2" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->fax2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->fax2->CellAttributes() ?>>
<span id="el_as_customers_fax2">
<input type="text" data-table="as_customers" data-field="x_fax2" name="x_fax2" id="x_fax2" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->fax2->getPlaceHolder()) ?>" value="<?php echo $as_customers->fax2->EditValue ?>"<?php echo $as_customers->fax2->EditAttributes() ?>>
</span>
<?php echo $as_customers->fax2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->fax3->Visible) { // fax3 ?>
	<div id="r_fax3" class="form-group">
		<label id="elh_as_customers_fax3" for="x_fax3" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->fax3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->fax3->CellAttributes() ?>>
<span id="el_as_customers_fax3">
<input type="text" data-table="as_customers" data-field="x_fax3" name="x_fax3" id="x_fax3" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->fax3->getPlaceHolder()) ?>" value="<?php echo $as_customers->fax3->EditValue ?>"<?php echo $as_customers->fax3->EditAttributes() ?>>
</span>
<?php echo $as_customers->fax3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
	<div id="r_phonecp1" class="form-group">
		<label id="elh_as_customers_phonecp1" for="x_phonecp1" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->phonecp1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->phonecp1->CellAttributes() ?>>
<span id="el_as_customers_phonecp1">
<input type="text" data-table="as_customers" data-field="x_phonecp1" name="x_phonecp1" id="x_phonecp1" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->phonecp1->getPlaceHolder()) ?>" value="<?php echo $as_customers->phonecp1->EditValue ?>"<?php echo $as_customers->phonecp1->EditAttributes() ?>>
</span>
<?php echo $as_customers->phonecp1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
	<div id="r_phonecp2" class="form-group">
		<label id="elh_as_customers_phonecp2" for="x_phonecp2" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->phonecp2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->phonecp2->CellAttributes() ?>>
<span id="el_as_customers_phonecp2">
<input type="text" data-table="as_customers" data-field="x_phonecp2" name="x_phonecp2" id="x_phonecp2" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->phonecp2->getPlaceHolder()) ?>" value="<?php echo $as_customers->phonecp2->EditValue ?>"<?php echo $as_customers->phonecp2->EditAttributes() ?>>
</span>
<?php echo $as_customers->phonecp2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_as_customers__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->_email->CellAttributes() ?>>
<span id="el_as_customers__email">
<input type="text" data-table="as_customers" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->_email->getPlaceHolder()) ?>" value="<?php echo $as_customers->_email->EditValue ?>"<?php echo $as_customers->_email->EditAttributes() ?>>
</span>
<?php echo $as_customers->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
	<div id="r_limitBalance" class="form-group">
		<label id="elh_as_customers_limitBalance" for="x_limitBalance" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->limitBalance->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->limitBalance->CellAttributes() ?>>
<span id="el_as_customers_limitBalance">
<input type="text" data-table="as_customers" data-field="x_limitBalance" name="x_limitBalance" id="x_limitBalance" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->limitBalance->getPlaceHolder()) ?>" value="<?php echo $as_customers->limitBalance->EditValue ?>"<?php echo $as_customers->limitBalance->EditAttributes() ?>>
</span>
<?php echo $as_customers->limitBalance->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->balance->Visible) { // balance ?>
	<div id="r_balance" class="form-group">
		<label id="elh_as_customers_balance" for="x_balance" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->balance->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->balance->CellAttributes() ?>>
<span id="el_as_customers_balance">
<input type="text" data-table="as_customers" data-field="x_balance" name="x_balance" id="x_balance" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->balance->getPlaceHolder()) ?>" value="<?php echo $as_customers->balance->EditValue ?>"<?php echo $as_customers->balance->EditAttributes() ?>>
</span>
<?php echo $as_customers->balance->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->disc1->Visible) { // disc1 ?>
	<div id="r_disc1" class="form-group">
		<label id="elh_as_customers_disc1" for="x_disc1" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->disc1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->disc1->CellAttributes() ?>>
<span id="el_as_customers_disc1">
<input type="text" data-table="as_customers" data-field="x_disc1" name="x_disc1" id="x_disc1" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->disc1->getPlaceHolder()) ?>" value="<?php echo $as_customers->disc1->EditValue ?>"<?php echo $as_customers->disc1->EditAttributes() ?>>
</span>
<?php echo $as_customers->disc1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->disc2->Visible) { // disc2 ?>
	<div id="r_disc2" class="form-group">
		<label id="elh_as_customers_disc2" for="x_disc2" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->disc2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->disc2->CellAttributes() ?>>
<span id="el_as_customers_disc2">
<input type="text" data-table="as_customers" data-field="x_disc2" name="x_disc2" id="x_disc2" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->disc2->getPlaceHolder()) ?>" value="<?php echo $as_customers->disc2->EditValue ?>"<?php echo $as_customers->disc2->EditAttributes() ?>>
</span>
<?php echo $as_customers->disc2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->disc3->Visible) { // disc3 ?>
	<div id="r_disc3" class="form-group">
		<label id="elh_as_customers_disc3" for="x_disc3" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->disc3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->disc3->CellAttributes() ?>>
<span id="el_as_customers_disc3">
<input type="text" data-table="as_customers" data-field="x_disc3" name="x_disc3" id="x_disc3" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->disc3->getPlaceHolder()) ?>" value="<?php echo $as_customers->disc3->EditValue ?>"<?php echo $as_customers->disc3->EditAttributes() ?>>
</span>
<?php echo $as_customers->disc3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_customers_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->note->CellAttributes() ?>>
<span id="el_as_customers_note">
<textarea data-table="as_customers" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_customers->note->getPlaceHolder()) ?>"<?php echo $as_customers->note->EditAttributes() ?>><?php echo $as_customers->note->EditValue ?></textarea>
</span>
<?php echo $as_customers->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->npwp->Visible) { // npwp ?>
	<div id="r_npwp" class="form-group">
		<label id="elh_as_customers_npwp" for="x_npwp" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->npwp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->npwp->CellAttributes() ?>>
<span id="el_as_customers_npwp">
<input type="text" data-table="as_customers" data-field="x_npwp" name="x_npwp" id="x_npwp" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->npwp->getPlaceHolder()) ?>" value="<?php echo $as_customers->npwp->EditValue ?>"<?php echo $as_customers->npwp->EditAttributes() ?>>
</span>
<?php echo $as_customers->npwp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
	<div id="r_pkpName" class="form-group">
		<label id="elh_as_customers_pkpName" for="x_pkpName" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->pkpName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->pkpName->CellAttributes() ?>>
<span id="el_as_customers_pkpName">
<input type="text" data-table="as_customers" data-field="x_pkpName" name="x_pkpName" id="x_pkpName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_customers->pkpName->getPlaceHolder()) ?>" value="<?php echo $as_customers->pkpName->EditValue ?>"<?php echo $as_customers->pkpName->EditAttributes() ?>>
</span>
<?php echo $as_customers->pkpName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
	<div id="r_staffCode" class="form-group">
		<label id="elh_as_customers_staffCode" for="x_staffCode" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->staffCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->staffCode->CellAttributes() ?>>
<span id="el_as_customers_staffCode">
<input type="text" data-table="as_customers" data-field="x_staffCode" name="x_staffCode" id="x_staffCode" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_customers->staffCode->getPlaceHolder()) ?>" value="<?php echo $as_customers->staffCode->EditValue ?>"<?php echo $as_customers->staffCode->EditAttributes() ?>>
</span>
<?php echo $as_customers->staffCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_customers_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->createdDate->CellAttributes() ?>>
<span id="el_as_customers_createdDate">
<input type="text" data-table="as_customers" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_customers->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_customers->createdDate->EditValue ?>"<?php echo $as_customers->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_customers->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_customers_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->createdUserID->CellAttributes() ?>>
<span id="el_as_customers_createdUserID">
<input type="text" data-table="as_customers" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_customers->createdUserID->EditValue ?>"<?php echo $as_customers->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_customers->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_customers_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->modifiedDate->CellAttributes() ?>>
<span id="el_as_customers_modifiedDate">
<input type="text" data-table="as_customers" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_customers->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_customers->modifiedDate->EditValue ?>"<?php echo $as_customers->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_customers->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_customers_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_customers->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_customers->modifiedUserID->CellAttributes() ?>>
<span id="el_as_customers_modifiedUserID">
<input type="text" data-table="as_customers" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_customers->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_customers->modifiedUserID->EditValue ?>"<?php echo $as_customers->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_customers->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_customers_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_customers_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($as_customers_edit->Pager)) $as_customers_edit->Pager = new cPrevNextPager($as_customers_edit->StartRec, $as_customers_edit->DisplayRecs, $as_customers_edit->TotalRecs) ?>
<?php if ($as_customers_edit->Pager->RecordCount > 0 && $as_customers_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_edit->PageUrl() ?>start=<?php echo $as_customers_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fas_customersedit.Init();
</script>
<?php
$as_customers_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_customers_edit->Page_Terminate();
?>
