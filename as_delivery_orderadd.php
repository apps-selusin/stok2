<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_delivery_orderinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_delivery_order_add = NULL; // Initialize page object first

class cas_delivery_order_add extends cas_delivery_order {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_delivery_order';

	// Page object name
	var $PageObjName = 'as_delivery_order_add';

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

		// Table object (as_delivery_order)
		if (!isset($GLOBALS["as_delivery_order"]) || get_class($GLOBALS["as_delivery_order"]) == "cas_delivery_order") {
			$GLOBALS["as_delivery_order"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_delivery_order"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_delivery_order', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_delivery_orderlist.php"));
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
		$this->doNo->SetVisibility();
		$this->doFaktur->SetVisibility();
		$this->soID->SetVisibility();
		$this->soNo->SetVisibility();
		$this->customerID->SetVisibility();
		$this->customerName->SetVisibility();
		$this->customerAddress->SetVisibility();
		$this->staffID->SetVisibility();
		$this->staffName->SetVisibility();
		$this->deliveredDate->SetVisibility();
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
		global $EW_EXPORT, $as_delivery_order;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_delivery_order);
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
			if (@$_GET["doID"] != "") {
				$this->doID->setQueryStringValue($_GET["doID"]);
				$this->setKey("doID", $this->doID->CurrentValue); // Set up key
			} else {
				$this->setKey("doID", ""); // Clear key
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
					$this->Page_Terminate("as_delivery_orderlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_delivery_orderlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_delivery_orderview.php")
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
		$this->doNo->CurrentValue = NULL;
		$this->doNo->OldValue = $this->doNo->CurrentValue;
		$this->doFaktur->CurrentValue = NULL;
		$this->doFaktur->OldValue = $this->doFaktur->CurrentValue;
		$this->soID->CurrentValue = NULL;
		$this->soID->OldValue = $this->soID->CurrentValue;
		$this->soNo->CurrentValue = NULL;
		$this->soNo->OldValue = $this->soNo->CurrentValue;
		$this->customerID->CurrentValue = NULL;
		$this->customerID->OldValue = $this->customerID->CurrentValue;
		$this->customerName->CurrentValue = NULL;
		$this->customerName->OldValue = $this->customerName->CurrentValue;
		$this->customerAddress->CurrentValue = NULL;
		$this->customerAddress->OldValue = $this->customerAddress->CurrentValue;
		$this->staffID->CurrentValue = NULL;
		$this->staffID->OldValue = $this->staffID->CurrentValue;
		$this->staffName->CurrentValue = NULL;
		$this->staffName->OldValue = $this->staffName->CurrentValue;
		$this->deliveredDate->CurrentValue = NULL;
		$this->deliveredDate->OldValue = $this->deliveredDate->CurrentValue;
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
		if (!$this->doNo->FldIsDetailKey) {
			$this->doNo->setFormValue($objForm->GetValue("x_doNo"));
		}
		if (!$this->doFaktur->FldIsDetailKey) {
			$this->doFaktur->setFormValue($objForm->GetValue("x_doFaktur"));
		}
		if (!$this->soID->FldIsDetailKey) {
			$this->soID->setFormValue($objForm->GetValue("x_soID"));
		}
		if (!$this->soNo->FldIsDetailKey) {
			$this->soNo->setFormValue($objForm->GetValue("x_soNo"));
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
		if (!$this->staffID->FldIsDetailKey) {
			$this->staffID->setFormValue($objForm->GetValue("x_staffID"));
		}
		if (!$this->staffName->FldIsDetailKey) {
			$this->staffName->setFormValue($objForm->GetValue("x_staffName"));
		}
		if (!$this->deliveredDate->FldIsDetailKey) {
			$this->deliveredDate->setFormValue($objForm->GetValue("x_deliveredDate"));
			$this->deliveredDate->CurrentValue = ew_UnFormatDateTime($this->deliveredDate->CurrentValue, 0);
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
		$this->doNo->CurrentValue = $this->doNo->FormValue;
		$this->doFaktur->CurrentValue = $this->doFaktur->FormValue;
		$this->soID->CurrentValue = $this->soID->FormValue;
		$this->soNo->CurrentValue = $this->soNo->FormValue;
		$this->customerID->CurrentValue = $this->customerID->FormValue;
		$this->customerName->CurrentValue = $this->customerName->FormValue;
		$this->customerAddress->CurrentValue = $this->customerAddress->FormValue;
		$this->staffID->CurrentValue = $this->staffID->FormValue;
		$this->staffName->CurrentValue = $this->staffName->FormValue;
		$this->deliveredDate->CurrentValue = $this->deliveredDate->FormValue;
		$this->deliveredDate->CurrentValue = ew_UnFormatDateTime($this->deliveredDate->CurrentValue, 0);
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
		$this->doID->setDbValue($rs->fields('doID'));
		$this->doNo->setDbValue($rs->fields('doNo'));
		$this->doFaktur->setDbValue($rs->fields('doFaktur'));
		$this->soID->setDbValue($rs->fields('soID'));
		$this->soNo->setDbValue($rs->fields('soNo'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
		$this->staffID->setDbValue($rs->fields('staffID'));
		$this->staffName->setDbValue($rs->fields('staffName'));
		$this->deliveredDate->setDbValue($rs->fields('deliveredDate'));
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
		$this->doID->DbValue = $row['doID'];
		$this->doNo->DbValue = $row['doNo'];
		$this->doFaktur->DbValue = $row['doFaktur'];
		$this->soID->DbValue = $row['soID'];
		$this->soNo->DbValue = $row['soNo'];
		$this->customerID->DbValue = $row['customerID'];
		$this->customerName->DbValue = $row['customerName'];
		$this->customerAddress->DbValue = $row['customerAddress'];
		$this->staffID->DbValue = $row['staffID'];
		$this->staffName->DbValue = $row['staffName'];
		$this->deliveredDate->DbValue = $row['deliveredDate'];
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
		if (strval($this->getKey("doID")) <> "")
			$this->doID->CurrentValue = $this->getKey("doID"); // doID
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
		// doID
		// doNo
		// doFaktur
		// soID
		// soNo
		// customerID
		// customerName
		// customerAddress
		// staffID
		// staffName
		// deliveredDate
		// orderDate
		// needDate
		// total
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

		// soID
		$this->soID->ViewValue = $this->soID->CurrentValue;
		$this->soID->ViewCustomAttributes = "";

		// soNo
		$this->soNo->ViewValue = $this->soNo->CurrentValue;
		$this->soNo->ViewCustomAttributes = "";

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// customerAddress
		$this->customerAddress->ViewValue = $this->customerAddress->CurrentValue;
		$this->customerAddress->ViewCustomAttributes = "";

		// staffID
		$this->staffID->ViewValue = $this->staffID->CurrentValue;
		$this->staffID->ViewCustomAttributes = "";

		// staffName
		$this->staffName->ViewValue = $this->staffName->CurrentValue;
		$this->staffName->ViewCustomAttributes = "";

		// deliveredDate
		$this->deliveredDate->ViewValue = $this->deliveredDate->CurrentValue;
		$this->deliveredDate->ViewValue = ew_FormatDateTime($this->deliveredDate->ViewValue, 0);
		$this->deliveredDate->ViewCustomAttributes = "";

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

			// doNo
			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";
			$this->doNo->TooltipValue = "";

			// doFaktur
			$this->doFaktur->LinkCustomAttributes = "";
			$this->doFaktur->HrefValue = "";
			$this->doFaktur->TooltipValue = "";

			// soID
			$this->soID->LinkCustomAttributes = "";
			$this->soID->HrefValue = "";
			$this->soID->TooltipValue = "";

			// soNo
			$this->soNo->LinkCustomAttributes = "";
			$this->soNo->HrefValue = "";
			$this->soNo->TooltipValue = "";

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

			// staffID
			$this->staffID->LinkCustomAttributes = "";
			$this->staffID->HrefValue = "";
			$this->staffID->TooltipValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";
			$this->staffName->TooltipValue = "";

			// deliveredDate
			$this->deliveredDate->LinkCustomAttributes = "";
			$this->deliveredDate->HrefValue = "";
			$this->deliveredDate->TooltipValue = "";

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

			// soID
			$this->soID->EditAttrs["class"] = "form-control";
			$this->soID->EditCustomAttributes = "";
			$this->soID->EditValue = ew_HtmlEncode($this->soID->CurrentValue);
			$this->soID->PlaceHolder = ew_RemoveHtml($this->soID->FldCaption());

			// soNo
			$this->soNo->EditAttrs["class"] = "form-control";
			$this->soNo->EditCustomAttributes = "";
			$this->soNo->EditValue = ew_HtmlEncode($this->soNo->CurrentValue);
			$this->soNo->PlaceHolder = ew_RemoveHtml($this->soNo->FldCaption());

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

			// deliveredDate
			$this->deliveredDate->EditAttrs["class"] = "form-control";
			$this->deliveredDate->EditCustomAttributes = "";
			$this->deliveredDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->deliveredDate->CurrentValue, 8));
			$this->deliveredDate->PlaceHolder = ew_RemoveHtml($this->deliveredDate->FldCaption());

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
			// doNo

			$this->doNo->LinkCustomAttributes = "";
			$this->doNo->HrefValue = "";

			// doFaktur
			$this->doFaktur->LinkCustomAttributes = "";
			$this->doFaktur->HrefValue = "";

			// soID
			$this->soID->LinkCustomAttributes = "";
			$this->soID->HrefValue = "";

			// soNo
			$this->soNo->LinkCustomAttributes = "";
			$this->soNo->HrefValue = "";

			// customerID
			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";

			// customerAddress
			$this->customerAddress->LinkCustomAttributes = "";
			$this->customerAddress->HrefValue = "";

			// staffID
			$this->staffID->LinkCustomAttributes = "";
			$this->staffID->HrefValue = "";

			// staffName
			$this->staffName->LinkCustomAttributes = "";
			$this->staffName->HrefValue = "";

			// deliveredDate
			$this->deliveredDate->LinkCustomAttributes = "";
			$this->deliveredDate->HrefValue = "";

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
		if (!$this->doNo->FldIsDetailKey && !is_null($this->doNo->FormValue) && $this->doNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->doNo->FldCaption(), $this->doNo->ReqErrMsg));
		}
		if (!$this->doFaktur->FldIsDetailKey && !is_null($this->doFaktur->FormValue) && $this->doFaktur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->doFaktur->FldCaption(), $this->doFaktur->ReqErrMsg));
		}
		if (!$this->soID->FldIsDetailKey && !is_null($this->soID->FormValue) && $this->soID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->soID->FldCaption(), $this->soID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->soID->FormValue)) {
			ew_AddMessage($gsFormError, $this->soID->FldErrMsg());
		}
		if (!$this->soNo->FldIsDetailKey && !is_null($this->soNo->FormValue) && $this->soNo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->soNo->FldCaption(), $this->soNo->ReqErrMsg));
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
		if (!$this->staffID->FldIsDetailKey && !is_null($this->staffID->FormValue) && $this->staffID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffID->FldCaption(), $this->staffID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->staffID->FormValue)) {
			ew_AddMessage($gsFormError, $this->staffID->FldErrMsg());
		}
		if (!$this->staffName->FldIsDetailKey && !is_null($this->staffName->FormValue) && $this->staffName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->staffName->FldCaption(), $this->staffName->ReqErrMsg));
		}
		if (!$this->deliveredDate->FldIsDetailKey && !is_null($this->deliveredDate->FormValue) && $this->deliveredDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->deliveredDate->FldCaption(), $this->deliveredDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->deliveredDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->deliveredDate->FldErrMsg());
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

		// doNo
		$this->doNo->SetDbValueDef($rsnew, $this->doNo->CurrentValue, "", FALSE);

		// doFaktur
		$this->doFaktur->SetDbValueDef($rsnew, $this->doFaktur->CurrentValue, "", FALSE);

		// soID
		$this->soID->SetDbValueDef($rsnew, $this->soID->CurrentValue, 0, FALSE);

		// soNo
		$this->soNo->SetDbValueDef($rsnew, $this->soNo->CurrentValue, "", FALSE);

		// customerID
		$this->customerID->SetDbValueDef($rsnew, $this->customerID->CurrentValue, 0, FALSE);

		// customerName
		$this->customerName->SetDbValueDef($rsnew, $this->customerName->CurrentValue, "", FALSE);

		// customerAddress
		$this->customerAddress->SetDbValueDef($rsnew, $this->customerAddress->CurrentValue, "", FALSE);

		// staffID
		$this->staffID->SetDbValueDef($rsnew, $this->staffID->CurrentValue, 0, FALSE);

		// staffName
		$this->staffName->SetDbValueDef($rsnew, $this->staffName->CurrentValue, "", FALSE);

		// deliveredDate
		$this->deliveredDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->deliveredDate->CurrentValue, 0), ew_CurrentDate(), FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_delivery_orderlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_delivery_order_add)) $as_delivery_order_add = new cas_delivery_order_add();

// Page init
$as_delivery_order_add->Page_Init();

// Page main
$as_delivery_order_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_delivery_order_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_delivery_orderadd = new ew_Form("fas_delivery_orderadd", "add");

// Validate form
fas_delivery_orderadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->doNo->FldCaption(), $as_delivery_order->doNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_doFaktur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->doFaktur->FldCaption(), $as_delivery_order->doFaktur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_soID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->soID->FldCaption(), $as_delivery_order->soID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_soID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->soID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_soNo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->soNo->FldCaption(), $as_delivery_order->soNo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->customerID->FldCaption(), $as_delivery_order->customerID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->customerID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_customerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->customerName->FldCaption(), $as_delivery_order->customerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customerAddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->customerAddress->FldCaption(), $as_delivery_order->customerAddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->staffID->FldCaption(), $as_delivery_order->staffID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_staffID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->staffID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_staffName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->staffName->FldCaption(), $as_delivery_order->staffName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_deliveredDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->deliveredDate->FldCaption(), $as_delivery_order->deliveredDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_deliveredDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->deliveredDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_orderDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->orderDate->FldCaption(), $as_delivery_order->orderDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_orderDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->orderDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_needDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->needDate->FldCaption(), $as_delivery_order->needDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_needDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->needDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->total->FldCaption(), $as_delivery_order->total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->note->FldCaption(), $as_delivery_order->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->createdDate->FldCaption(), $as_delivery_order->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->createdUserID->FldCaption(), $as_delivery_order->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->modifiedDate->FldCaption(), $as_delivery_order->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_delivery_order->modifiedUserID->FldCaption(), $as_delivery_order->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_delivery_order->modifiedUserID->FldErrMsg()) ?>");

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
fas_delivery_orderadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_delivery_orderadd.ValidateRequired = true;
<?php } else { ?>
fas_delivery_orderadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_delivery_order_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_delivery_order_add->ShowPageHeader(); ?>
<?php
$as_delivery_order_add->ShowMessage();
?>
<form name="fas_delivery_orderadd" id="fas_delivery_orderadd" class="<?php echo $as_delivery_order_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_delivery_order_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_delivery_order_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_delivery_order">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_delivery_order_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_delivery_order->doNo->Visible) { // doNo ?>
	<div id="r_doNo" class="form-group">
		<label id="elh_as_delivery_order_doNo" for="x_doNo" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->doNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->doNo->CellAttributes() ?>>
<span id="el_as_delivery_order_doNo">
<input type="text" data-table="as_delivery_order" data-field="x_doNo" name="x_doNo" id="x_doNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->doNo->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->doNo->EditValue ?>"<?php echo $as_delivery_order->doNo->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->doNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->doFaktur->Visible) { // doFaktur ?>
	<div id="r_doFaktur" class="form-group">
		<label id="elh_as_delivery_order_doFaktur" for="x_doFaktur" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->doFaktur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->doFaktur->CellAttributes() ?>>
<span id="el_as_delivery_order_doFaktur">
<input type="text" data-table="as_delivery_order" data-field="x_doFaktur" name="x_doFaktur" id="x_doFaktur" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->doFaktur->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->doFaktur->EditValue ?>"<?php echo $as_delivery_order->doFaktur->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->doFaktur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->soID->Visible) { // soID ?>
	<div id="r_soID" class="form-group">
		<label id="elh_as_delivery_order_soID" for="x_soID" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->soID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->soID->CellAttributes() ?>>
<span id="el_as_delivery_order_soID">
<input type="text" data-table="as_delivery_order" data-field="x_soID" name="x_soID" id="x_soID" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->soID->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->soID->EditValue ?>"<?php echo $as_delivery_order->soID->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->soID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->soNo->Visible) { // soNo ?>
	<div id="r_soNo" class="form-group">
		<label id="elh_as_delivery_order_soNo" for="x_soNo" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->soNo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->soNo->CellAttributes() ?>>
<span id="el_as_delivery_order_soNo">
<input type="text" data-table="as_delivery_order" data-field="x_soNo" name="x_soNo" id="x_soNo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->soNo->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->soNo->EditValue ?>"<?php echo $as_delivery_order->soNo->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->soNo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->customerID->Visible) { // customerID ?>
	<div id="r_customerID" class="form-group">
		<label id="elh_as_delivery_order_customerID" for="x_customerID" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->customerID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->customerID->CellAttributes() ?>>
<span id="el_as_delivery_order_customerID">
<input type="text" data-table="as_delivery_order" data-field="x_customerID" name="x_customerID" id="x_customerID" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->customerID->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->customerID->EditValue ?>"<?php echo $as_delivery_order->customerID->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->customerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->customerName->Visible) { // customerName ?>
	<div id="r_customerName" class="form-group">
		<label id="elh_as_delivery_order_customerName" for="x_customerName" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->customerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->customerName->CellAttributes() ?>>
<span id="el_as_delivery_order_customerName">
<input type="text" data-table="as_delivery_order" data-field="x_customerName" name="x_customerName" id="x_customerName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->customerName->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->customerName->EditValue ?>"<?php echo $as_delivery_order->customerName->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->customerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->customerAddress->Visible) { // customerAddress ?>
	<div id="r_customerAddress" class="form-group">
		<label id="elh_as_delivery_order_customerAddress" for="x_customerAddress" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->customerAddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->customerAddress->CellAttributes() ?>>
<span id="el_as_delivery_order_customerAddress">
<textarea data-table="as_delivery_order" data-field="x_customerAddress" name="x_customerAddress" id="x_customerAddress" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->customerAddress->getPlaceHolder()) ?>"<?php echo $as_delivery_order->customerAddress->EditAttributes() ?>><?php echo $as_delivery_order->customerAddress->EditValue ?></textarea>
</span>
<?php echo $as_delivery_order->customerAddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->staffID->Visible) { // staffID ?>
	<div id="r_staffID" class="form-group">
		<label id="elh_as_delivery_order_staffID" for="x_staffID" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->staffID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->staffID->CellAttributes() ?>>
<span id="el_as_delivery_order_staffID">
<input type="text" data-table="as_delivery_order" data-field="x_staffID" name="x_staffID" id="x_staffID" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->staffID->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->staffID->EditValue ?>"<?php echo $as_delivery_order->staffID->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->staffID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->staffName->Visible) { // staffName ?>
	<div id="r_staffName" class="form-group">
		<label id="elh_as_delivery_order_staffName" for="x_staffName" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->staffName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->staffName->CellAttributes() ?>>
<span id="el_as_delivery_order_staffName">
<input type="text" data-table="as_delivery_order" data-field="x_staffName" name="x_staffName" id="x_staffName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->staffName->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->staffName->EditValue ?>"<?php echo $as_delivery_order->staffName->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->staffName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->deliveredDate->Visible) { // deliveredDate ?>
	<div id="r_deliveredDate" class="form-group">
		<label id="elh_as_delivery_order_deliveredDate" for="x_deliveredDate" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->deliveredDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->deliveredDate->CellAttributes() ?>>
<span id="el_as_delivery_order_deliveredDate">
<input type="text" data-table="as_delivery_order" data-field="x_deliveredDate" name="x_deliveredDate" id="x_deliveredDate" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->deliveredDate->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->deliveredDate->EditValue ?>"<?php echo $as_delivery_order->deliveredDate->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->deliveredDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->orderDate->Visible) { // orderDate ?>
	<div id="r_orderDate" class="form-group">
		<label id="elh_as_delivery_order_orderDate" for="x_orderDate" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->orderDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->orderDate->CellAttributes() ?>>
<span id="el_as_delivery_order_orderDate">
<input type="text" data-table="as_delivery_order" data-field="x_orderDate" name="x_orderDate" id="x_orderDate" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->orderDate->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->orderDate->EditValue ?>"<?php echo $as_delivery_order->orderDate->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->orderDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->needDate->Visible) { // needDate ?>
	<div id="r_needDate" class="form-group">
		<label id="elh_as_delivery_order_needDate" for="x_needDate" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->needDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->needDate->CellAttributes() ?>>
<span id="el_as_delivery_order_needDate">
<input type="text" data-table="as_delivery_order" data-field="x_needDate" name="x_needDate" id="x_needDate" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->needDate->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->needDate->EditValue ?>"<?php echo $as_delivery_order->needDate->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->needDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_as_delivery_order_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->total->CellAttributes() ?>>
<span id="el_as_delivery_order_total">
<input type="text" data-table="as_delivery_order" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->total->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->total->EditValue ?>"<?php echo $as_delivery_order->total->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_delivery_order_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->note->CellAttributes() ?>>
<span id="el_as_delivery_order_note">
<textarea data-table="as_delivery_order" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->note->getPlaceHolder()) ?>"<?php echo $as_delivery_order->note->EditAttributes() ?>><?php echo $as_delivery_order->note->EditValue ?></textarea>
</span>
<?php echo $as_delivery_order->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_delivery_order_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->createdDate->CellAttributes() ?>>
<span id="el_as_delivery_order_createdDate">
<input type="text" data-table="as_delivery_order" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->createdDate->EditValue ?>"<?php echo $as_delivery_order->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_delivery_order_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->createdUserID->CellAttributes() ?>>
<span id="el_as_delivery_order_createdUserID">
<input type="text" data-table="as_delivery_order" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->createdUserID->EditValue ?>"<?php echo $as_delivery_order->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_delivery_order_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->modifiedDate->CellAttributes() ?>>
<span id="el_as_delivery_order_modifiedDate">
<input type="text" data-table="as_delivery_order" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->modifiedDate->EditValue ?>"<?php echo $as_delivery_order->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_delivery_order->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_delivery_order_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_delivery_order->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_delivery_order->modifiedUserID->CellAttributes() ?>>
<span id="el_as_delivery_order_modifiedUserID">
<input type="text" data-table="as_delivery_order" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_delivery_order->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_delivery_order->modifiedUserID->EditValue ?>"<?php echo $as_delivery_order->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_delivery_order->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_delivery_order_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_delivery_order_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_delivery_orderadd.Init();
</script>
<?php
$as_delivery_order_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_delivery_order_add->Page_Terminate();
?>
