<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_buy_transactionsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_buy_transactions_delete = NULL; // Initialize page object first

class cas_buy_transactions_delete extends cas_buy_transactions {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_buy_transactions';

	// Page object name
	var $PageObjName = 'as_buy_transactions_delete';

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

		// Table object (as_buy_transactions)
		if (!isset($GLOBALS["as_buy_transactions"]) || get_class($GLOBALS["as_buy_transactions"]) == "cas_buy_transactions") {
			$GLOBALS["as_buy_transactions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_buy_transactions"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_buy_transactions', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_buy_transactionslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->invoiceID->SetVisibility();
		$this->invoiceID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->invoiceNo->SetVisibility();
		$this->invoiceDate->SetVisibility();
		$this->bbmNo->SetVisibility();
		$this->spbNo->SetVisibility();
		$this->paymentType->SetVisibility();
		$this->expiredPayment->SetVisibility();
		$this->ppnType->SetVisibility();
		$this->ppn->SetVisibility();
		$this->total->SetVisibility();
		$this->basic->SetVisibility();
		$this->discount->SetVisibility();
		$this->grandtotal->SetVisibility();
		$this->supplierID->SetVisibility();
		$this->supplierName->SetVisibility();
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
		global $EW_EXPORT, $as_buy_transactions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_buy_transactions);
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
			$this->Page_Terminate("as_buy_transactionslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_buy_transactions class, as_buy_transactionsinfo.php

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
				$this->Page_Terminate("as_buy_transactionslist.php"); // Return to list
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
		$this->invoiceID->setDbValue($rs->fields('invoiceID'));
		$this->invoiceNo->setDbValue($rs->fields('invoiceNo'));
		$this->invoiceDate->setDbValue($rs->fields('invoiceDate'));
		$this->bbmNo->setDbValue($rs->fields('bbmNo'));
		$this->spbNo->setDbValue($rs->fields('spbNo'));
		$this->paymentType->setDbValue($rs->fields('paymentType'));
		$this->expiredPayment->setDbValue($rs->fields('expiredPayment'));
		$this->ppnType->setDbValue($rs->fields('ppnType'));
		$this->ppn->setDbValue($rs->fields('ppn'));
		$this->total->setDbValue($rs->fields('total'));
		$this->basic->setDbValue($rs->fields('basic'));
		$this->discount->setDbValue($rs->fields('discount'));
		$this->grandtotal->setDbValue($rs->fields('grandtotal'));
		$this->supplierID->setDbValue($rs->fields('supplierID'));
		$this->supplierName->setDbValue($rs->fields('supplierName'));
		$this->supplierAddress->setDbValue($rs->fields('supplierAddress'));
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
		$this->invoiceID->DbValue = $row['invoiceID'];
		$this->invoiceNo->DbValue = $row['invoiceNo'];
		$this->invoiceDate->DbValue = $row['invoiceDate'];
		$this->bbmNo->DbValue = $row['bbmNo'];
		$this->spbNo->DbValue = $row['spbNo'];
		$this->paymentType->DbValue = $row['paymentType'];
		$this->expiredPayment->DbValue = $row['expiredPayment'];
		$this->ppnType->DbValue = $row['ppnType'];
		$this->ppn->DbValue = $row['ppn'];
		$this->total->DbValue = $row['total'];
		$this->basic->DbValue = $row['basic'];
		$this->discount->DbValue = $row['discount'];
		$this->grandtotal->DbValue = $row['grandtotal'];
		$this->supplierID->DbValue = $row['supplierID'];
		$this->supplierName->DbValue = $row['supplierName'];
		$this->supplierAddress->DbValue = $row['supplierAddress'];
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

		if ($this->ppn->FormValue == $this->ppn->CurrentValue && is_numeric(ew_StrToFloat($this->ppn->CurrentValue)))
			$this->ppn->CurrentValue = ew_StrToFloat($this->ppn->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->basic->FormValue == $this->basic->CurrentValue && is_numeric(ew_StrToFloat($this->basic->CurrentValue)))
			$this->basic->CurrentValue = ew_StrToFloat($this->basic->CurrentValue);

		// Convert decimal values if posted back
		if ($this->discount->FormValue == $this->discount->CurrentValue && is_numeric(ew_StrToFloat($this->discount->CurrentValue)))
			$this->discount->CurrentValue = ew_StrToFloat($this->discount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->grandtotal->FormValue == $this->grandtotal->CurrentValue && is_numeric(ew_StrToFloat($this->grandtotal->CurrentValue)))
			$this->grandtotal->CurrentValue = ew_StrToFloat($this->grandtotal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// invoiceID
		// invoiceNo
		// invoiceDate
		// bbmNo
		// spbNo
		// paymentType
		// expiredPayment
		// ppnType
		// ppn
		// total
		// basic
		// discount
		// grandtotal
		// supplierID
		// supplierName
		// supplierAddress
		// staffID
		// staffName
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// invoiceID
		$this->invoiceID->ViewValue = $this->invoiceID->CurrentValue;
		$this->invoiceID->ViewCustomAttributes = "";

		// invoiceNo
		$this->invoiceNo->ViewValue = $this->invoiceNo->CurrentValue;
		$this->invoiceNo->ViewCustomAttributes = "";

		// invoiceDate
		$this->invoiceDate->ViewValue = $this->invoiceDate->CurrentValue;
		$this->invoiceDate->ViewValue = ew_FormatDateTime($this->invoiceDate->ViewValue, 0);
		$this->invoiceDate->ViewCustomAttributes = "";

		// bbmNo
		$this->bbmNo->ViewValue = $this->bbmNo->CurrentValue;
		$this->bbmNo->ViewCustomAttributes = "";

		// spbNo
		$this->spbNo->ViewValue = $this->spbNo->CurrentValue;
		$this->spbNo->ViewCustomAttributes = "";

		// paymentType
		$this->paymentType->ViewValue = $this->paymentType->CurrentValue;
		$this->paymentType->ViewCustomAttributes = "";

		// expiredPayment
		$this->expiredPayment->ViewValue = $this->expiredPayment->CurrentValue;
		$this->expiredPayment->ViewValue = ew_FormatDateTime($this->expiredPayment->ViewValue, 0);
		$this->expiredPayment->ViewCustomAttributes = "";

		// ppnType
		$this->ppnType->ViewValue = $this->ppnType->CurrentValue;
		$this->ppnType->ViewCustomAttributes = "";

		// ppn
		$this->ppn->ViewValue = $this->ppn->CurrentValue;
		$this->ppn->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// basic
		$this->basic->ViewValue = $this->basic->CurrentValue;
		$this->basic->ViewCustomAttributes = "";

		// discount
		$this->discount->ViewValue = $this->discount->CurrentValue;
		$this->discount->ViewCustomAttributes = "";

		// grandtotal
		$this->grandtotal->ViewValue = $this->grandtotal->CurrentValue;
		$this->grandtotal->ViewCustomAttributes = "";

		// supplierID
		$this->supplierID->ViewValue = $this->supplierID->CurrentValue;
		$this->supplierID->ViewCustomAttributes = "";

		// supplierName
		$this->supplierName->ViewValue = $this->supplierName->CurrentValue;
		$this->supplierName->ViewCustomAttributes = "";

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

			// invoiceID
			$this->invoiceID->LinkCustomAttributes = "";
			$this->invoiceID->HrefValue = "";
			$this->invoiceID->TooltipValue = "";

			// invoiceNo
			$this->invoiceNo->LinkCustomAttributes = "";
			$this->invoiceNo->HrefValue = "";
			$this->invoiceNo->TooltipValue = "";

			// invoiceDate
			$this->invoiceDate->LinkCustomAttributes = "";
			$this->invoiceDate->HrefValue = "";
			$this->invoiceDate->TooltipValue = "";

			// bbmNo
			$this->bbmNo->LinkCustomAttributes = "";
			$this->bbmNo->HrefValue = "";
			$this->bbmNo->TooltipValue = "";

			// spbNo
			$this->spbNo->LinkCustomAttributes = "";
			$this->spbNo->HrefValue = "";
			$this->spbNo->TooltipValue = "";

			// paymentType
			$this->paymentType->LinkCustomAttributes = "";
			$this->paymentType->HrefValue = "";
			$this->paymentType->TooltipValue = "";

			// expiredPayment
			$this->expiredPayment->LinkCustomAttributes = "";
			$this->expiredPayment->HrefValue = "";
			$this->expiredPayment->TooltipValue = "";

			// ppnType
			$this->ppnType->LinkCustomAttributes = "";
			$this->ppnType->HrefValue = "";
			$this->ppnType->TooltipValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";
			$this->ppn->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// basic
			$this->basic->LinkCustomAttributes = "";
			$this->basic->HrefValue = "";
			$this->basic->TooltipValue = "";

			// discount
			$this->discount->LinkCustomAttributes = "";
			$this->discount->HrefValue = "";
			$this->discount->TooltipValue = "";

			// grandtotal
			$this->grandtotal->LinkCustomAttributes = "";
			$this->grandtotal->HrefValue = "";
			$this->grandtotal->TooltipValue = "";

			// supplierID
			$this->supplierID->LinkCustomAttributes = "";
			$this->supplierID->HrefValue = "";
			$this->supplierID->TooltipValue = "";

			// supplierName
			$this->supplierName->LinkCustomAttributes = "";
			$this->supplierName->HrefValue = "";
			$this->supplierName->TooltipValue = "";

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
				$sThisKey .= $row['invoiceID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_buy_transactionslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_buy_transactions_delete)) $as_buy_transactions_delete = new cas_buy_transactions_delete();

// Page init
$as_buy_transactions_delete->Page_Init();

// Page main
$as_buy_transactions_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_buy_transactions_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_buy_transactionsdelete = new ew_Form("fas_buy_transactionsdelete", "delete");

// Form_CustomValidate event
fas_buy_transactionsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_buy_transactionsdelete.ValidateRequired = true;
<?php } else { ?>
fas_buy_transactionsdelete.ValidateRequired = false; 
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
<?php $as_buy_transactions_delete->ShowPageHeader(); ?>
<?php
$as_buy_transactions_delete->ShowMessage();
?>
<form name="fas_buy_transactionsdelete" id="fas_buy_transactionsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_buy_transactions_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_buy_transactions_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_buy_transactions">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_buy_transactions_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_buy_transactions->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_buy_transactions->invoiceID->Visible) { // invoiceID ?>
		<th><span id="elh_as_buy_transactions_invoiceID" class="as_buy_transactions_invoiceID"><?php echo $as_buy_transactions->invoiceID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->invoiceNo->Visible) { // invoiceNo ?>
		<th><span id="elh_as_buy_transactions_invoiceNo" class="as_buy_transactions_invoiceNo"><?php echo $as_buy_transactions->invoiceNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->invoiceDate->Visible) { // invoiceDate ?>
		<th><span id="elh_as_buy_transactions_invoiceDate" class="as_buy_transactions_invoiceDate"><?php echo $as_buy_transactions->invoiceDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->bbmNo->Visible) { // bbmNo ?>
		<th><span id="elh_as_buy_transactions_bbmNo" class="as_buy_transactions_bbmNo"><?php echo $as_buy_transactions->bbmNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->spbNo->Visible) { // spbNo ?>
		<th><span id="elh_as_buy_transactions_spbNo" class="as_buy_transactions_spbNo"><?php echo $as_buy_transactions->spbNo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->paymentType->Visible) { // paymentType ?>
		<th><span id="elh_as_buy_transactions_paymentType" class="as_buy_transactions_paymentType"><?php echo $as_buy_transactions->paymentType->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->expiredPayment->Visible) { // expiredPayment ?>
		<th><span id="elh_as_buy_transactions_expiredPayment" class="as_buy_transactions_expiredPayment"><?php echo $as_buy_transactions->expiredPayment->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->ppnType->Visible) { // ppnType ?>
		<th><span id="elh_as_buy_transactions_ppnType" class="as_buy_transactions_ppnType"><?php echo $as_buy_transactions->ppnType->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->ppn->Visible) { // ppn ?>
		<th><span id="elh_as_buy_transactions_ppn" class="as_buy_transactions_ppn"><?php echo $as_buy_transactions->ppn->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->total->Visible) { // total ?>
		<th><span id="elh_as_buy_transactions_total" class="as_buy_transactions_total"><?php echo $as_buy_transactions->total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->basic->Visible) { // basic ?>
		<th><span id="elh_as_buy_transactions_basic" class="as_buy_transactions_basic"><?php echo $as_buy_transactions->basic->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->discount->Visible) { // discount ?>
		<th><span id="elh_as_buy_transactions_discount" class="as_buy_transactions_discount"><?php echo $as_buy_transactions->discount->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->grandtotal->Visible) { // grandtotal ?>
		<th><span id="elh_as_buy_transactions_grandtotal" class="as_buy_transactions_grandtotal"><?php echo $as_buy_transactions->grandtotal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->supplierID->Visible) { // supplierID ?>
		<th><span id="elh_as_buy_transactions_supplierID" class="as_buy_transactions_supplierID"><?php echo $as_buy_transactions->supplierID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->supplierName->Visible) { // supplierName ?>
		<th><span id="elh_as_buy_transactions_supplierName" class="as_buy_transactions_supplierName"><?php echo $as_buy_transactions->supplierName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->staffID->Visible) { // staffID ?>
		<th><span id="elh_as_buy_transactions_staffID" class="as_buy_transactions_staffID"><?php echo $as_buy_transactions->staffID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->staffName->Visible) { // staffName ?>
		<th><span id="elh_as_buy_transactions_staffName" class="as_buy_transactions_staffName"><?php echo $as_buy_transactions->staffName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_buy_transactions_createdDate" class="as_buy_transactions_createdDate"><?php echo $as_buy_transactions->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_buy_transactions_createdUserID" class="as_buy_transactions_createdUserID"><?php echo $as_buy_transactions->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_buy_transactions_modifiedDate" class="as_buy_transactions_modifiedDate"><?php echo $as_buy_transactions->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_buy_transactions->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_buy_transactions_modifiedUserID" class="as_buy_transactions_modifiedUserID"><?php echo $as_buy_transactions->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_buy_transactions_delete->RecCnt = 0;
$i = 0;
while (!$as_buy_transactions_delete->Recordset->EOF) {
	$as_buy_transactions_delete->RecCnt++;
	$as_buy_transactions_delete->RowCnt++;

	// Set row properties
	$as_buy_transactions->ResetAttrs();
	$as_buy_transactions->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_buy_transactions_delete->LoadRowValues($as_buy_transactions_delete->Recordset);

	// Render row
	$as_buy_transactions_delete->RenderRow();
?>
	<tr<?php echo $as_buy_transactions->RowAttributes() ?>>
<?php if ($as_buy_transactions->invoiceID->Visible) { // invoiceID ?>
		<td<?php echo $as_buy_transactions->invoiceID->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_invoiceID" class="as_buy_transactions_invoiceID">
<span<?php echo $as_buy_transactions->invoiceID->ViewAttributes() ?>>
<?php echo $as_buy_transactions->invoiceID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->invoiceNo->Visible) { // invoiceNo ?>
		<td<?php echo $as_buy_transactions->invoiceNo->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_invoiceNo" class="as_buy_transactions_invoiceNo">
<span<?php echo $as_buy_transactions->invoiceNo->ViewAttributes() ?>>
<?php echo $as_buy_transactions->invoiceNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->invoiceDate->Visible) { // invoiceDate ?>
		<td<?php echo $as_buy_transactions->invoiceDate->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_invoiceDate" class="as_buy_transactions_invoiceDate">
<span<?php echo $as_buy_transactions->invoiceDate->ViewAttributes() ?>>
<?php echo $as_buy_transactions->invoiceDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->bbmNo->Visible) { // bbmNo ?>
		<td<?php echo $as_buy_transactions->bbmNo->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_bbmNo" class="as_buy_transactions_bbmNo">
<span<?php echo $as_buy_transactions->bbmNo->ViewAttributes() ?>>
<?php echo $as_buy_transactions->bbmNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->spbNo->Visible) { // spbNo ?>
		<td<?php echo $as_buy_transactions->spbNo->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_spbNo" class="as_buy_transactions_spbNo">
<span<?php echo $as_buy_transactions->spbNo->ViewAttributes() ?>>
<?php echo $as_buy_transactions->spbNo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->paymentType->Visible) { // paymentType ?>
		<td<?php echo $as_buy_transactions->paymentType->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_paymentType" class="as_buy_transactions_paymentType">
<span<?php echo $as_buy_transactions->paymentType->ViewAttributes() ?>>
<?php echo $as_buy_transactions->paymentType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->expiredPayment->Visible) { // expiredPayment ?>
		<td<?php echo $as_buy_transactions->expiredPayment->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_expiredPayment" class="as_buy_transactions_expiredPayment">
<span<?php echo $as_buy_transactions->expiredPayment->ViewAttributes() ?>>
<?php echo $as_buy_transactions->expiredPayment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->ppnType->Visible) { // ppnType ?>
		<td<?php echo $as_buy_transactions->ppnType->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_ppnType" class="as_buy_transactions_ppnType">
<span<?php echo $as_buy_transactions->ppnType->ViewAttributes() ?>>
<?php echo $as_buy_transactions->ppnType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->ppn->Visible) { // ppn ?>
		<td<?php echo $as_buy_transactions->ppn->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_ppn" class="as_buy_transactions_ppn">
<span<?php echo $as_buy_transactions->ppn->ViewAttributes() ?>>
<?php echo $as_buy_transactions->ppn->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->total->Visible) { // total ?>
		<td<?php echo $as_buy_transactions->total->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_total" class="as_buy_transactions_total">
<span<?php echo $as_buy_transactions->total->ViewAttributes() ?>>
<?php echo $as_buy_transactions->total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->basic->Visible) { // basic ?>
		<td<?php echo $as_buy_transactions->basic->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_basic" class="as_buy_transactions_basic">
<span<?php echo $as_buy_transactions->basic->ViewAttributes() ?>>
<?php echo $as_buy_transactions->basic->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->discount->Visible) { // discount ?>
		<td<?php echo $as_buy_transactions->discount->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_discount" class="as_buy_transactions_discount">
<span<?php echo $as_buy_transactions->discount->ViewAttributes() ?>>
<?php echo $as_buy_transactions->discount->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->grandtotal->Visible) { // grandtotal ?>
		<td<?php echo $as_buy_transactions->grandtotal->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_grandtotal" class="as_buy_transactions_grandtotal">
<span<?php echo $as_buy_transactions->grandtotal->ViewAttributes() ?>>
<?php echo $as_buy_transactions->grandtotal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->supplierID->Visible) { // supplierID ?>
		<td<?php echo $as_buy_transactions->supplierID->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_supplierID" class="as_buy_transactions_supplierID">
<span<?php echo $as_buy_transactions->supplierID->ViewAttributes() ?>>
<?php echo $as_buy_transactions->supplierID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->supplierName->Visible) { // supplierName ?>
		<td<?php echo $as_buy_transactions->supplierName->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_supplierName" class="as_buy_transactions_supplierName">
<span<?php echo $as_buy_transactions->supplierName->ViewAttributes() ?>>
<?php echo $as_buy_transactions->supplierName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->staffID->Visible) { // staffID ?>
		<td<?php echo $as_buy_transactions->staffID->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_staffID" class="as_buy_transactions_staffID">
<span<?php echo $as_buy_transactions->staffID->ViewAttributes() ?>>
<?php echo $as_buy_transactions->staffID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->staffName->Visible) { // staffName ?>
		<td<?php echo $as_buy_transactions->staffName->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_staffName" class="as_buy_transactions_staffName">
<span<?php echo $as_buy_transactions->staffName->ViewAttributes() ?>>
<?php echo $as_buy_transactions->staffName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_buy_transactions->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_createdDate" class="as_buy_transactions_createdDate">
<span<?php echo $as_buy_transactions->createdDate->ViewAttributes() ?>>
<?php echo $as_buy_transactions->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_buy_transactions->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_createdUserID" class="as_buy_transactions_createdUserID">
<span<?php echo $as_buy_transactions->createdUserID->ViewAttributes() ?>>
<?php echo $as_buy_transactions->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_buy_transactions->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_modifiedDate" class="as_buy_transactions_modifiedDate">
<span<?php echo $as_buy_transactions->modifiedDate->ViewAttributes() ?>>
<?php echo $as_buy_transactions->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_buy_transactions->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_buy_transactions->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_buy_transactions_delete->RowCnt ?>_as_buy_transactions_modifiedUserID" class="as_buy_transactions_modifiedUserID">
<span<?php echo $as_buy_transactions->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_buy_transactions->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_buy_transactions_delete->Recordset->MoveNext();
}
$as_buy_transactions_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_buy_transactions_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_buy_transactionsdelete.Init();
</script>
<?php
$as_buy_transactions_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_buy_transactions_delete->Page_Terminate();
?>
