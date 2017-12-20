<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_sales_paymentsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_sales_payments_delete = NULL; // Initialize page object first

class cas_sales_payments_delete extends cas_sales_payments {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_sales_payments';

	// Page object name
	var $PageObjName = 'as_sales_payments_delete';

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

		// Table object (as_sales_payments)
		if (!isset($GLOBALS["as_sales_payments"]) || get_class($GLOBALS["as_sales_payments"]) == "cas_sales_payments") {
			$GLOBALS["as_sales_payments"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_sales_payments"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_sales_payments', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("as_sales_paymentslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->paymentID->SetVisibility();
		$this->paymentID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->paymentNo->SetVisibility();
		$this->invoiceID->SetVisibility();
		$this->invoiceNo->SetVisibility();
		$this->soNo->SetVisibility();
		$this->paymentDate->SetVisibility();
		$this->payType->SetVisibility();
		$this->bankNo->SetVisibility();
		$this->bankName->SetVisibility();
		$this->bankAC->SetVisibility();
		$this->effectiveDate->SetVisibility();
		$this->total->SetVisibility();
		$this->customerID->SetVisibility();
		$this->customerName->SetVisibility();
		$this->ref->SetVisibility();
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
		global $EW_EXPORT, $as_sales_payments;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_sales_payments);
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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("as_sales_paymentslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_sales_payments class, as_sales_paymentsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("as_sales_paymentslist.php"); // Return to list
			}
		}
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
		$this->soNo->setDbValue($rs->fields('soNo'));
		$this->paymentDate->setDbValue($rs->fields('paymentDate'));
		$this->payType->setDbValue($rs->fields('payType'));
		$this->bankNo->setDbValue($rs->fields('bankNo'));
		$this->bankName->setDbValue($rs->fields('bankName'));
		$this->bankAC->setDbValue($rs->fields('bankAC'));
		$this->effectiveDate->setDbValue($rs->fields('effectiveDate'));
		$this->total->setDbValue($rs->fields('total'));
		$this->customerID->setDbValue($rs->fields('customerID'));
		$this->customerName->setDbValue($rs->fields('customerName'));
		$this->customerAddress->setDbValue($rs->fields('customerAddress'));
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
		$this->soNo->DbValue = $row['soNo'];
		$this->paymentDate->DbValue = $row['paymentDate'];
		$this->payType->DbValue = $row['payType'];
		$this->bankNo->DbValue = $row['bankNo'];
		$this->bankName->DbValue = $row['bankName'];
		$this->bankAC->DbValue = $row['bankAC'];
		$this->effectiveDate->DbValue = $row['effectiveDate'];
		$this->total->DbValue = $row['total'];
		$this->customerID->DbValue = $row['customerID'];
		$this->customerName->DbValue = $row['customerName'];
		$this->customerAddress->DbValue = $row['customerAddress'];
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
		// soNo
		// paymentDate
		// payType
		// bankNo
		// bankName
		// bankAC
		// effectiveDate
		// total
		// customerID
		// customerName
		// customerAddress
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

		// soNo
		$this->soNo->ViewValue = $this->soNo->CurrentValue;
		$this->soNo->ViewCustomAttributes = "";

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

		// customerID
		$this->customerID->ViewValue = $this->customerID->CurrentValue;
		$this->customerID->ViewCustomAttributes = "";

		// customerName
		$this->customerName->ViewValue = $this->customerName->CurrentValue;
		$this->customerName->ViewCustomAttributes = "";

		// ref
		$this->ref->ViewValue = $this->ref->CurrentValue;
		$this->ref->ViewCustomAttributes = "";

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

			// soNo
			$this->soNo->LinkCustomAttributes = "";
			$this->soNo->HrefValue = "";
			$this->soNo->TooltipValue = "";

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

			// customerID
			$this->customerID->LinkCustomAttributes = "";
			$this->customerID->HrefValue = "";
			$this->customerID->TooltipValue = "";

			// customerName
			$this->customerName->LinkCustomAttributes = "";
			$this->customerName->HrefValue = "";
			$this->customerName->TooltipValue = "";

			// ref
			$this->ref->LinkCustomAttributes = "";
			$this->ref->HrefValue = "";
			$this->ref->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['paymentID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_sales_paymentslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($as_sales_payments_delete)) $as_sales_payments_delete = new cas_sales_payments_delete();

// Page init
$as_sales_payments_delete->Page_Init();

// Page main
$as_sales_payments_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_sales_payments_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_sales_paymentsdelete = new ew_Form("fas_sales_paymentsdelete", "delete");

// Form_CustomValidate event
fas_sales_paymentsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_sales_paymentsdelete.ValidateRequired = true;
<?php } else { ?>
fas_sales_paymentsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $as_sales_payments_delete->ShowPageHeader(); ?>
<?php
$as_sales_payments_delete->ShowMessage();
?>
<form name="fas_sales_paymentsdelete" id="fas_sales_paymentsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_sales_payments_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_sales_payments_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_sales_payments">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_sales_payments_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_sales_payments->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_sales_payments->paymentID->Visible) { // paymentID ?>
		<th><span id="elh_as_sales_payments_paymentID" class="as_sales_payments_paymentID"><?php echo $as_sales_payments->paymentID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->paymentNo->Visible) { // paymentNo ?>
		<th><span id="elh_as_sales_payments_paymentNo" class="as_sales_payments_paymentNo"><?php echo $as_sales_payments->paymentNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->invoiceID->Visible) { // invoiceID ?>
		<th><span id="elh_as_sales_payments_invoiceID" class="as_sales_payments_invoiceID"><?php echo $as_sales_payments->invoiceID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->invoiceNo->Visible) { // invoiceNo ?>
		<th><span id="elh_as_sales_payments_invoiceNo" class="as_sales_payments_invoiceNo"><?php echo $as_sales_payments->invoiceNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->soNo->Visible) { // soNo ?>
		<th><span id="elh_as_sales_payments_soNo" class="as_sales_payments_soNo"><?php echo $as_sales_payments->soNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->paymentDate->Visible) { // paymentDate ?>
		<th><span id="elh_as_sales_payments_paymentDate" class="as_sales_payments_paymentDate"><?php echo $as_sales_payments->paymentDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->payType->Visible) { // payType ?>
		<th><span id="elh_as_sales_payments_payType" class="as_sales_payments_payType"><?php echo $as_sales_payments->payType->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->bankNo->Visible) { // bankNo ?>
		<th><span id="elh_as_sales_payments_bankNo" class="as_sales_payments_bankNo"><?php echo $as_sales_payments->bankNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->bankName->Visible) { // bankName ?>
		<th><span id="elh_as_sales_payments_bankName" class="as_sales_payments_bankName"><?php echo $as_sales_payments->bankName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->bankAC->Visible) { // bankAC ?>
		<th><span id="elh_as_sales_payments_bankAC" class="as_sales_payments_bankAC"><?php echo $as_sales_payments->bankAC->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->effectiveDate->Visible) { // effectiveDate ?>
		<th><span id="elh_as_sales_payments_effectiveDate" class="as_sales_payments_effectiveDate"><?php echo $as_sales_payments->effectiveDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->total->Visible) { // total ?>
		<th><span id="elh_as_sales_payments_total" class="as_sales_payments_total"><?php echo $as_sales_payments->total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->customerID->Visible) { // customerID ?>
		<th><span id="elh_as_sales_payments_customerID" class="as_sales_payments_customerID"><?php echo $as_sales_payments->customerID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->customerName->Visible) { // customerName ?>
		<th><span id="elh_as_sales_payments_customerName" class="as_sales_payments_customerName"><?php echo $as_sales_payments->customerName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->ref->Visible) { // ref ?>
		<th><span id="elh_as_sales_payments_ref" class="as_sales_payments_ref"><?php echo $as_sales_payments->ref->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->staffID->Visible) { // staffID ?>
		<th><span id="elh_as_sales_payments_staffID" class="as_sales_payments_staffID"><?php echo $as_sales_payments->staffID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->staffName->Visible) { // staffName ?>
		<th><span id="elh_as_sales_payments_staffName" class="as_sales_payments_staffName"><?php echo $as_sales_payments->staffName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_sales_payments_createdDate" class="as_sales_payments_createdDate"><?php echo $as_sales_payments->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_sales_payments_createdUserID" class="as_sales_payments_createdUserID"><?php echo $as_sales_payments->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_sales_payments_modifiedDate" class="as_sales_payments_modifiedDate"><?php echo $as_sales_payments->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_sales_payments->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_sales_payments_modifiedUserID" class="as_sales_payments_modifiedUserID"><?php echo $as_sales_payments->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_sales_payments_delete->RecCnt = 0;
$i = 0;
while (!$as_sales_payments_delete->Recordset->EOF) {
	$as_sales_payments_delete->RecCnt++;
	$as_sales_payments_delete->RowCnt++;

	// Set row properties
	$as_sales_payments->ResetAttrs();
	$as_sales_payments->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_sales_payments_delete->LoadRowValues($as_sales_payments_delete->Recordset);

	// Render row
	$as_sales_payments_delete->RenderRow();
?>
	<tr<?php echo $as_sales_payments->RowAttributes() ?>>
<?php if ($as_sales_payments->paymentID->Visible) { // paymentID ?>
		<td<?php echo $as_sales_payments->paymentID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_paymentID" class="as_sales_payments_paymentID">
<span<?php echo $as_sales_payments->paymentID->ViewAttributes() ?>>
<?php echo $as_sales_payments->paymentID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->paymentNo->Visible) { // paymentNo ?>
		<td<?php echo $as_sales_payments->paymentNo->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_paymentNo" class="as_sales_payments_paymentNo">
<span<?php echo $as_sales_payments->paymentNo->ViewAttributes() ?>>
<?php echo $as_sales_payments->paymentNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->invoiceID->Visible) { // invoiceID ?>
		<td<?php echo $as_sales_payments->invoiceID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_invoiceID" class="as_sales_payments_invoiceID">
<span<?php echo $as_sales_payments->invoiceID->ViewAttributes() ?>>
<?php echo $as_sales_payments->invoiceID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->invoiceNo->Visible) { // invoiceNo ?>
		<td<?php echo $as_sales_payments->invoiceNo->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_invoiceNo" class="as_sales_payments_invoiceNo">
<span<?php echo $as_sales_payments->invoiceNo->ViewAttributes() ?>>
<?php echo $as_sales_payments->invoiceNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->soNo->Visible) { // soNo ?>
		<td<?php echo $as_sales_payments->soNo->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_soNo" class="as_sales_payments_soNo">
<span<?php echo $as_sales_payments->soNo->ViewAttributes() ?>>
<?php echo $as_sales_payments->soNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->paymentDate->Visible) { // paymentDate ?>
		<td<?php echo $as_sales_payments->paymentDate->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_paymentDate" class="as_sales_payments_paymentDate">
<span<?php echo $as_sales_payments->paymentDate->ViewAttributes() ?>>
<?php echo $as_sales_payments->paymentDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->payType->Visible) { // payType ?>
		<td<?php echo $as_sales_payments->payType->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_payType" class="as_sales_payments_payType">
<span<?php echo $as_sales_payments->payType->ViewAttributes() ?>>
<?php echo $as_sales_payments->payType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->bankNo->Visible) { // bankNo ?>
		<td<?php echo $as_sales_payments->bankNo->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_bankNo" class="as_sales_payments_bankNo">
<span<?php echo $as_sales_payments->bankNo->ViewAttributes() ?>>
<?php echo $as_sales_payments->bankNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->bankName->Visible) { // bankName ?>
		<td<?php echo $as_sales_payments->bankName->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_bankName" class="as_sales_payments_bankName">
<span<?php echo $as_sales_payments->bankName->ViewAttributes() ?>>
<?php echo $as_sales_payments->bankName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->bankAC->Visible) { // bankAC ?>
		<td<?php echo $as_sales_payments->bankAC->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_bankAC" class="as_sales_payments_bankAC">
<span<?php echo $as_sales_payments->bankAC->ViewAttributes() ?>>
<?php echo $as_sales_payments->bankAC->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->effectiveDate->Visible) { // effectiveDate ?>
		<td<?php echo $as_sales_payments->effectiveDate->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_effectiveDate" class="as_sales_payments_effectiveDate">
<span<?php echo $as_sales_payments->effectiveDate->ViewAttributes() ?>>
<?php echo $as_sales_payments->effectiveDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->total->Visible) { // total ?>
		<td<?php echo $as_sales_payments->total->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_total" class="as_sales_payments_total">
<span<?php echo $as_sales_payments->total->ViewAttributes() ?>>
<?php echo $as_sales_payments->total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->customerID->Visible) { // customerID ?>
		<td<?php echo $as_sales_payments->customerID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_customerID" class="as_sales_payments_customerID">
<span<?php echo $as_sales_payments->customerID->ViewAttributes() ?>>
<?php echo $as_sales_payments->customerID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->customerName->Visible) { // customerName ?>
		<td<?php echo $as_sales_payments->customerName->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_customerName" class="as_sales_payments_customerName">
<span<?php echo $as_sales_payments->customerName->ViewAttributes() ?>>
<?php echo $as_sales_payments->customerName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->ref->Visible) { // ref ?>
		<td<?php echo $as_sales_payments->ref->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_ref" class="as_sales_payments_ref">
<span<?php echo $as_sales_payments->ref->ViewAttributes() ?>>
<?php echo $as_sales_payments->ref->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->staffID->Visible) { // staffID ?>
		<td<?php echo $as_sales_payments->staffID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_staffID" class="as_sales_payments_staffID">
<span<?php echo $as_sales_payments->staffID->ViewAttributes() ?>>
<?php echo $as_sales_payments->staffID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->staffName->Visible) { // staffName ?>
		<td<?php echo $as_sales_payments->staffName->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_staffName" class="as_sales_payments_staffName">
<span<?php echo $as_sales_payments->staffName->ViewAttributes() ?>>
<?php echo $as_sales_payments->staffName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_sales_payments->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_createdDate" class="as_sales_payments_createdDate">
<span<?php echo $as_sales_payments->createdDate->ViewAttributes() ?>>
<?php echo $as_sales_payments->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_sales_payments->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_createdUserID" class="as_sales_payments_createdUserID">
<span<?php echo $as_sales_payments->createdUserID->ViewAttributes() ?>>
<?php echo $as_sales_payments->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_sales_payments->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_modifiedDate" class="as_sales_payments_modifiedDate">
<span<?php echo $as_sales_payments->modifiedDate->ViewAttributes() ?>>
<?php echo $as_sales_payments->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_sales_payments->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_sales_payments->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_sales_payments_delete->RowCnt ?>_as_sales_payments_modifiedUserID" class="as_sales_payments_modifiedUserID">
<span<?php echo $as_sales_payments->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_sales_payments->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_sales_payments_delete->Recordset->MoveNext();
}
$as_sales_payments_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_sales_payments_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_sales_paymentsdelete.Init();
</script>
<?php
$as_sales_payments_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_sales_payments_delete->Page_Terminate();
?>
