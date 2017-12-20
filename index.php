<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'cf_01_home.php'))
		$this->Page_Terminate("cf_01_home.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'as_bbm'))
			$this->Page_Terminate("as_bbmlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_brands'))
			$this->Page_Terminate("as_brandslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_buy_payments'))
			$this->Page_Terminate("as_buy_paymentslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_buy_transactions'))
			$this->Page_Terminate("as_buy_transactionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_categories'))
			$this->Page_Terminate("as_categorieslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_customers'))
			$this->Page_Terminate("as_customerslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_debts'))
			$this->Page_Terminate("as_debtslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_delivery_order'))
			$this->Page_Terminate("as_delivery_orderlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_bbm'))
			$this->Page_Terminate("as_detail_bbmlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_do'))
			$this->Page_Terminate("as_detail_dolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_retur_staffs'))
			$this->Page_Terminate("as_detail_retur_staffslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_retur_suppliers'))
			$this->Page_Terminate("as_detail_retur_supplierslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_so'))
			$this->Page_Terminate("as_detail_solist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_spb'))
			$this->Page_Terminate("as_detail_spblist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_detail_transfers'))
			$this->Page_Terminate("as_detail_transferslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_factories'))
			$this->Page_Terminate("as_factorieslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_modules'))
			$this->Page_Terminate("as_moduleslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_products'))
			$this->Page_Terminate("as_productslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_receivables'))
			$this->Page_Terminate("as_receivableslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_retur_staffs'))
			$this->Page_Terminate("as_retur_staffslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_retur_suppliers'))
			$this->Page_Terminate("as_retur_supplierslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_sales_order'))
			$this->Page_Terminate("as_sales_orderlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_sales_payments'))
			$this->Page_Terminate("as_sales_paymentslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_sales_transactions'))
			$this->Page_Terminate("as_sales_transactionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_spb'))
			$this->Page_Terminate("as_spblist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_staffs'))
			$this->Page_Terminate("as_staffslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_stock_opname'))
			$this->Page_Terminate("as_stock_opnamelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_stock_products'))
			$this->Page_Terminate("as_stock_productslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_suppliers'))
			$this->Page_Terminate("as_supplierslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_temp_detail_so'))
			$this->Page_Terminate("as_temp_detail_solist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_temp_detail_spb'))
			$this->Page_Terminate("as_temp_detail_spblist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_temp_detail_transfers'))
			$this->Page_Terminate("as_temp_detail_transferslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'as_transfers'))
			$this->Page_Terminate("as_transferslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_96_user'))
			$this->Page_Terminate("t_96_userlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_97_level'))
			$this->Page_Terminate("t_97_levellist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_98_permission'))
			$this->Page_Terminate("t_98_permissionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_99_audittrail'))
			$this->Page_Terminate("t_99_audittraillist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage(ew_DeniedMsg() . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
