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

$as_detail_do_list = NULL; // Initialize page object first

class cas_detail_do_list extends cas_detail_do {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_detail_do';

	// Page object name
	var $PageObjName = 'as_detail_do_list';

	// Grid form hidden field names
	var $FormName = 'fas_detail_dolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "as_detail_doadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "as_detail_dodelete.php";
		$this->MultiUpdateUrl = "as_detail_doupdate.php";

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fas_detail_dolistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->doID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->doID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fas_detail_dolistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->doID->AdvancedSearch->ToJSON(), ","); // Field doID
		$sFilterList = ew_Concat($sFilterList, $this->doNo->AdvancedSearch->ToJSON(), ","); // Field doNo
		$sFilterList = ew_Concat($sFilterList, $this->doFaktur->AdvancedSearch->ToJSON(), ","); // Field doFaktur
		$sFilterList = ew_Concat($sFilterList, $this->productID->AdvancedSearch->ToJSON(), ","); // Field productID
		$sFilterList = ew_Concat($sFilterList, $this->productName->AdvancedSearch->ToJSON(), ","); // Field productName
		$sFilterList = ew_Concat($sFilterList, $this->price->AdvancedSearch->ToJSON(), ","); // Field price
		$sFilterList = ew_Concat($sFilterList, $this->qty->AdvancedSearch->ToJSON(), ","); // Field qty
		$sFilterList = ew_Concat($sFilterList, $this->deliveredQty->AdvancedSearch->ToJSON(), ","); // Field deliveredQty
		$sFilterList = ew_Concat($sFilterList, $this->deliveredStatus->AdvancedSearch->ToJSON(), ","); // Field deliveredStatus
		$sFilterList = ew_Concat($sFilterList, $this->factoryID->AdvancedSearch->ToJSON(), ","); // Field factoryID
		$sFilterList = ew_Concat($sFilterList, $this->factoryName->AdvancedSearch->ToJSON(), ","); // Field factoryName
		$sFilterList = ew_Concat($sFilterList, $this->note->AdvancedSearch->ToJSON(), ","); // Field note
		$sFilterList = ew_Concat($sFilterList, $this->createdDate->AdvancedSearch->ToJSON(), ","); // Field createdDate
		$sFilterList = ew_Concat($sFilterList, $this->createdUserID->AdvancedSearch->ToJSON(), ","); // Field createdUserID
		$sFilterList = ew_Concat($sFilterList, $this->modifiedDate->AdvancedSearch->ToJSON(), ","); // Field modifiedDate
		$sFilterList = ew_Concat($sFilterList, $this->modifiedUserID->AdvancedSearch->ToJSON(), ","); // Field modifiedUserID
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fas_detail_dolistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field doID
		$this->doID->AdvancedSearch->SearchValue = @$filter["x_doID"];
		$this->doID->AdvancedSearch->SearchOperator = @$filter["z_doID"];
		$this->doID->AdvancedSearch->SearchCondition = @$filter["v_doID"];
		$this->doID->AdvancedSearch->SearchValue2 = @$filter["y_doID"];
		$this->doID->AdvancedSearch->SearchOperator2 = @$filter["w_doID"];
		$this->doID->AdvancedSearch->Save();

		// Field doNo
		$this->doNo->AdvancedSearch->SearchValue = @$filter["x_doNo"];
		$this->doNo->AdvancedSearch->SearchOperator = @$filter["z_doNo"];
		$this->doNo->AdvancedSearch->SearchCondition = @$filter["v_doNo"];
		$this->doNo->AdvancedSearch->SearchValue2 = @$filter["y_doNo"];
		$this->doNo->AdvancedSearch->SearchOperator2 = @$filter["w_doNo"];
		$this->doNo->AdvancedSearch->Save();

		// Field doFaktur
		$this->doFaktur->AdvancedSearch->SearchValue = @$filter["x_doFaktur"];
		$this->doFaktur->AdvancedSearch->SearchOperator = @$filter["z_doFaktur"];
		$this->doFaktur->AdvancedSearch->SearchCondition = @$filter["v_doFaktur"];
		$this->doFaktur->AdvancedSearch->SearchValue2 = @$filter["y_doFaktur"];
		$this->doFaktur->AdvancedSearch->SearchOperator2 = @$filter["w_doFaktur"];
		$this->doFaktur->AdvancedSearch->Save();

		// Field productID
		$this->productID->AdvancedSearch->SearchValue = @$filter["x_productID"];
		$this->productID->AdvancedSearch->SearchOperator = @$filter["z_productID"];
		$this->productID->AdvancedSearch->SearchCondition = @$filter["v_productID"];
		$this->productID->AdvancedSearch->SearchValue2 = @$filter["y_productID"];
		$this->productID->AdvancedSearch->SearchOperator2 = @$filter["w_productID"];
		$this->productID->AdvancedSearch->Save();

		// Field productName
		$this->productName->AdvancedSearch->SearchValue = @$filter["x_productName"];
		$this->productName->AdvancedSearch->SearchOperator = @$filter["z_productName"];
		$this->productName->AdvancedSearch->SearchCondition = @$filter["v_productName"];
		$this->productName->AdvancedSearch->SearchValue2 = @$filter["y_productName"];
		$this->productName->AdvancedSearch->SearchOperator2 = @$filter["w_productName"];
		$this->productName->AdvancedSearch->Save();

		// Field price
		$this->price->AdvancedSearch->SearchValue = @$filter["x_price"];
		$this->price->AdvancedSearch->SearchOperator = @$filter["z_price"];
		$this->price->AdvancedSearch->SearchCondition = @$filter["v_price"];
		$this->price->AdvancedSearch->SearchValue2 = @$filter["y_price"];
		$this->price->AdvancedSearch->SearchOperator2 = @$filter["w_price"];
		$this->price->AdvancedSearch->Save();

		// Field qty
		$this->qty->AdvancedSearch->SearchValue = @$filter["x_qty"];
		$this->qty->AdvancedSearch->SearchOperator = @$filter["z_qty"];
		$this->qty->AdvancedSearch->SearchCondition = @$filter["v_qty"];
		$this->qty->AdvancedSearch->SearchValue2 = @$filter["y_qty"];
		$this->qty->AdvancedSearch->SearchOperator2 = @$filter["w_qty"];
		$this->qty->AdvancedSearch->Save();

		// Field deliveredQty
		$this->deliveredQty->AdvancedSearch->SearchValue = @$filter["x_deliveredQty"];
		$this->deliveredQty->AdvancedSearch->SearchOperator = @$filter["z_deliveredQty"];
		$this->deliveredQty->AdvancedSearch->SearchCondition = @$filter["v_deliveredQty"];
		$this->deliveredQty->AdvancedSearch->SearchValue2 = @$filter["y_deliveredQty"];
		$this->deliveredQty->AdvancedSearch->SearchOperator2 = @$filter["w_deliveredQty"];
		$this->deliveredQty->AdvancedSearch->Save();

		// Field deliveredStatus
		$this->deliveredStatus->AdvancedSearch->SearchValue = @$filter["x_deliveredStatus"];
		$this->deliveredStatus->AdvancedSearch->SearchOperator = @$filter["z_deliveredStatus"];
		$this->deliveredStatus->AdvancedSearch->SearchCondition = @$filter["v_deliveredStatus"];
		$this->deliveredStatus->AdvancedSearch->SearchValue2 = @$filter["y_deliveredStatus"];
		$this->deliveredStatus->AdvancedSearch->SearchOperator2 = @$filter["w_deliveredStatus"];
		$this->deliveredStatus->AdvancedSearch->Save();

		// Field factoryID
		$this->factoryID->AdvancedSearch->SearchValue = @$filter["x_factoryID"];
		$this->factoryID->AdvancedSearch->SearchOperator = @$filter["z_factoryID"];
		$this->factoryID->AdvancedSearch->SearchCondition = @$filter["v_factoryID"];
		$this->factoryID->AdvancedSearch->SearchValue2 = @$filter["y_factoryID"];
		$this->factoryID->AdvancedSearch->SearchOperator2 = @$filter["w_factoryID"];
		$this->factoryID->AdvancedSearch->Save();

		// Field factoryName
		$this->factoryName->AdvancedSearch->SearchValue = @$filter["x_factoryName"];
		$this->factoryName->AdvancedSearch->SearchOperator = @$filter["z_factoryName"];
		$this->factoryName->AdvancedSearch->SearchCondition = @$filter["v_factoryName"];
		$this->factoryName->AdvancedSearch->SearchValue2 = @$filter["y_factoryName"];
		$this->factoryName->AdvancedSearch->SearchOperator2 = @$filter["w_factoryName"];
		$this->factoryName->AdvancedSearch->Save();

		// Field note
		$this->note->AdvancedSearch->SearchValue = @$filter["x_note"];
		$this->note->AdvancedSearch->SearchOperator = @$filter["z_note"];
		$this->note->AdvancedSearch->SearchCondition = @$filter["v_note"];
		$this->note->AdvancedSearch->SearchValue2 = @$filter["y_note"];
		$this->note->AdvancedSearch->SearchOperator2 = @$filter["w_note"];
		$this->note->AdvancedSearch->Save();

		// Field createdDate
		$this->createdDate->AdvancedSearch->SearchValue = @$filter["x_createdDate"];
		$this->createdDate->AdvancedSearch->SearchOperator = @$filter["z_createdDate"];
		$this->createdDate->AdvancedSearch->SearchCondition = @$filter["v_createdDate"];
		$this->createdDate->AdvancedSearch->SearchValue2 = @$filter["y_createdDate"];
		$this->createdDate->AdvancedSearch->SearchOperator2 = @$filter["w_createdDate"];
		$this->createdDate->AdvancedSearch->Save();

		// Field createdUserID
		$this->createdUserID->AdvancedSearch->SearchValue = @$filter["x_createdUserID"];
		$this->createdUserID->AdvancedSearch->SearchOperator = @$filter["z_createdUserID"];
		$this->createdUserID->AdvancedSearch->SearchCondition = @$filter["v_createdUserID"];
		$this->createdUserID->AdvancedSearch->SearchValue2 = @$filter["y_createdUserID"];
		$this->createdUserID->AdvancedSearch->SearchOperator2 = @$filter["w_createdUserID"];
		$this->createdUserID->AdvancedSearch->Save();

		// Field modifiedDate
		$this->modifiedDate->AdvancedSearch->SearchValue = @$filter["x_modifiedDate"];
		$this->modifiedDate->AdvancedSearch->SearchOperator = @$filter["z_modifiedDate"];
		$this->modifiedDate->AdvancedSearch->SearchCondition = @$filter["v_modifiedDate"];
		$this->modifiedDate->AdvancedSearch->SearchValue2 = @$filter["y_modifiedDate"];
		$this->modifiedDate->AdvancedSearch->SearchOperator2 = @$filter["w_modifiedDate"];
		$this->modifiedDate->AdvancedSearch->Save();

		// Field modifiedUserID
		$this->modifiedUserID->AdvancedSearch->SearchValue = @$filter["x_modifiedUserID"];
		$this->modifiedUserID->AdvancedSearch->SearchOperator = @$filter["z_modifiedUserID"];
		$this->modifiedUserID->AdvancedSearch->SearchCondition = @$filter["v_modifiedUserID"];
		$this->modifiedUserID->AdvancedSearch->SearchValue2 = @$filter["y_modifiedUserID"];
		$this->modifiedUserID->AdvancedSearch->SearchOperator2 = @$filter["w_modifiedUserID"];
		$this->modifiedUserID->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->doNo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->doFaktur, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->productName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->deliveredStatus, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->factoryName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->note, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->doID, $bCtrl); // doID
			$this->UpdateSort($this->doNo, $bCtrl); // doNo
			$this->UpdateSort($this->doFaktur, $bCtrl); // doFaktur
			$this->UpdateSort($this->productID, $bCtrl); // productID
			$this->UpdateSort($this->productName, $bCtrl); // productName
			$this->UpdateSort($this->price, $bCtrl); // price
			$this->UpdateSort($this->qty, $bCtrl); // qty
			$this->UpdateSort($this->deliveredQty, $bCtrl); // deliveredQty
			$this->UpdateSort($this->deliveredStatus, $bCtrl); // deliveredStatus
			$this->UpdateSort($this->factoryID, $bCtrl); // factoryID
			$this->UpdateSort($this->factoryName, $bCtrl); // factoryName
			$this->UpdateSort($this->createdDate, $bCtrl); // createdDate
			$this->UpdateSort($this->createdUserID, $bCtrl); // createdUserID
			$this->UpdateSort($this->modifiedDate, $bCtrl); // modifiedDate
			$this->UpdateSort($this->modifiedUserID, $bCtrl); // modifiedUserID
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->doID->setSort("");
				$this->doNo->setSort("");
				$this->doFaktur->setSort("");
				$this->productID->setSort("");
				$this->productName->setSort("");
				$this->price->setSort("");
				$this->qty->setSort("");
				$this->deliveredQty->setSort("");
				$this->deliveredStatus->setSort("");
				$this->factoryID->setSort("");
				$this->factoryName->setSort("");
				$this->createdDate->setSort("");
				$this->createdUserID->setSort("");
				$this->modifiedDate->setSort("");
				$this->modifiedUserID->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->doID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fas_detail_dolist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fas_detail_dolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fas_detail_dolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fas_detail_dolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fas_detail_dolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_as_detail_do\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_as_detail_do',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fas_detail_dolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($as_detail_do_list)) $as_detail_do_list = new cas_detail_do_list();

// Page init
$as_detail_do_list->Page_Init();

// Page main
$as_detail_do_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_detail_do_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($as_detail_do->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fas_detail_dolist = new ew_Form("fas_detail_dolist", "list");
fas_detail_dolist.FormKeyCountName = '<?php echo $as_detail_do_list->FormKeyCountName ?>';

// Form_CustomValidate event
fas_detail_dolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_detail_dolist.ValidateRequired = true;
<?php } else { ?>
fas_detail_dolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fas_detail_dolistsrch = new ew_Form("fas_detail_dolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($as_detail_do->Export == "") { ?>
<div class="ewToolbar">
<?php if ($as_detail_do->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($as_detail_do_list->TotalRecs > 0 && $as_detail_do_list->ExportOptions->Visible()) { ?>
<?php $as_detail_do_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($as_detail_do_list->SearchOptions->Visible()) { ?>
<?php $as_detail_do_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($as_detail_do_list->FilterOptions->Visible()) { ?>
<?php $as_detail_do_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($as_detail_do->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $as_detail_do_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($as_detail_do_list->TotalRecs <= 0)
			$as_detail_do_list->TotalRecs = $as_detail_do->SelectRecordCount();
	} else {
		if (!$as_detail_do_list->Recordset && ($as_detail_do_list->Recordset = $as_detail_do_list->LoadRecordset()))
			$as_detail_do_list->TotalRecs = $as_detail_do_list->Recordset->RecordCount();
	}
	$as_detail_do_list->StartRec = 1;
	if ($as_detail_do_list->DisplayRecs <= 0 || ($as_detail_do->Export <> "" && $as_detail_do->ExportAll)) // Display all records
		$as_detail_do_list->DisplayRecs = $as_detail_do_list->TotalRecs;
	if (!($as_detail_do->Export <> "" && $as_detail_do->ExportAll))
		$as_detail_do_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$as_detail_do_list->Recordset = $as_detail_do_list->LoadRecordset($as_detail_do_list->StartRec-1, $as_detail_do_list->DisplayRecs);

	// Set no record found message
	if ($as_detail_do->CurrentAction == "" && $as_detail_do_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$as_detail_do_list->setWarningMessage(ew_DeniedMsg());
		if ($as_detail_do_list->SearchWhere == "0=101")
			$as_detail_do_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$as_detail_do_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$as_detail_do_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($as_detail_do->Export == "" && $as_detail_do->CurrentAction == "") { ?>
<form name="fas_detail_dolistsrch" id="fas_detail_dolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($as_detail_do_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fas_detail_dolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="as_detail_do">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($as_detail_do_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($as_detail_do_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $as_detail_do_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($as_detail_do_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($as_detail_do_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($as_detail_do_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($as_detail_do_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $as_detail_do_list->ShowPageHeader(); ?>
<?php
$as_detail_do_list->ShowMessage();
?>
<?php if ($as_detail_do_list->TotalRecs > 0 || $as_detail_do->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid as_detail_do">
<?php if ($as_detail_do->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($as_detail_do->CurrentAction <> "gridadd" && $as_detail_do->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_detail_do_list->Pager)) $as_detail_do_list->Pager = new cPrevNextPager($as_detail_do_list->StartRec, $as_detail_do_list->DisplayRecs, $as_detail_do_list->TotalRecs) ?>
<?php if ($as_detail_do_list->Pager->RecordCount > 0 && $as_detail_do_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_detail_do_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_detail_do_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_detail_do_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_detail_do_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_detail_do_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_detail_do_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $as_detail_do_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $as_detail_do_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $as_detail_do_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($as_detail_do_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $as_detail_do_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="as_detail_do">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($as_detail_do_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($as_detail_do_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($as_detail_do_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($as_detail_do->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_detail_do_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fas_detail_dolist" id="fas_detail_dolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_detail_do_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_detail_do_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_detail_do">
<div id="gmp_as_detail_do" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($as_detail_do_list->TotalRecs > 0 || $as_detail_do->CurrentAction == "gridedit") { ?>
<table id="tbl_as_detail_dolist" class="table ewTable">
<?php echo $as_detail_do->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$as_detail_do_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$as_detail_do_list->RenderListOptions();

// Render list options (header, left)
$as_detail_do_list->ListOptions->Render("header", "left");
?>
<?php if ($as_detail_do->doID->Visible) { // doID ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->doID) == "") { ?>
		<th data-name="doID"><div id="elh_as_detail_do_doID" class="as_detail_do_doID"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->doID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="doID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->doID) ?>',2);"><div id="elh_as_detail_do_doID" class="as_detail_do_doID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->doID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->doID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->doID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->doNo->Visible) { // doNo ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->doNo) == "") { ?>
		<th data-name="doNo"><div id="elh_as_detail_do_doNo" class="as_detail_do_doNo"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->doNo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="doNo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->doNo) ?>',2);"><div id="elh_as_detail_do_doNo" class="as_detail_do_doNo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->doNo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->doNo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->doNo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->doFaktur->Visible) { // doFaktur ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->doFaktur) == "") { ?>
		<th data-name="doFaktur"><div id="elh_as_detail_do_doFaktur" class="as_detail_do_doFaktur"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->doFaktur->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="doFaktur"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->doFaktur) ?>',2);"><div id="elh_as_detail_do_doFaktur" class="as_detail_do_doFaktur">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->doFaktur->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->doFaktur->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->doFaktur->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->productID->Visible) { // productID ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->productID) == "") { ?>
		<th data-name="productID"><div id="elh_as_detail_do_productID" class="as_detail_do_productID"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->productID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="productID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->productID) ?>',2);"><div id="elh_as_detail_do_productID" class="as_detail_do_productID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->productID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->productID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->productID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->productName->Visible) { // productName ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->productName) == "") { ?>
		<th data-name="productName"><div id="elh_as_detail_do_productName" class="as_detail_do_productName"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->productName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="productName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->productName) ?>',2);"><div id="elh_as_detail_do_productName" class="as_detail_do_productName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->productName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->productName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->productName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->price->Visible) { // price ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->price) == "") { ?>
		<th data-name="price"><div id="elh_as_detail_do_price" class="as_detail_do_price"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="price"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->price) ?>',2);"><div id="elh_as_detail_do_price" class="as_detail_do_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->qty->Visible) { // qty ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->qty) == "") { ?>
		<th data-name="qty"><div id="elh_as_detail_do_qty" class="as_detail_do_qty"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->qty) ?>',2);"><div id="elh_as_detail_do_qty" class="as_detail_do_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->deliveredQty->Visible) { // deliveredQty ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->deliveredQty) == "") { ?>
		<th data-name="deliveredQty"><div id="elh_as_detail_do_deliveredQty" class="as_detail_do_deliveredQty"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->deliveredQty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="deliveredQty"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->deliveredQty) ?>',2);"><div id="elh_as_detail_do_deliveredQty" class="as_detail_do_deliveredQty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->deliveredQty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->deliveredQty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->deliveredQty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->deliveredStatus->Visible) { // deliveredStatus ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->deliveredStatus) == "") { ?>
		<th data-name="deliveredStatus"><div id="elh_as_detail_do_deliveredStatus" class="as_detail_do_deliveredStatus"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->deliveredStatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="deliveredStatus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->deliveredStatus) ?>',2);"><div id="elh_as_detail_do_deliveredStatus" class="as_detail_do_deliveredStatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->deliveredStatus->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->deliveredStatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->deliveredStatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->factoryID->Visible) { // factoryID ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->factoryID) == "") { ?>
		<th data-name="factoryID"><div id="elh_as_detail_do_factoryID" class="as_detail_do_factoryID"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->factoryID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="factoryID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->factoryID) ?>',2);"><div id="elh_as_detail_do_factoryID" class="as_detail_do_factoryID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->factoryID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->factoryID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->factoryID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->factoryName->Visible) { // factoryName ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->factoryName) == "") { ?>
		<th data-name="factoryName"><div id="elh_as_detail_do_factoryName" class="as_detail_do_factoryName"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->factoryName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="factoryName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->factoryName) ?>',2);"><div id="elh_as_detail_do_factoryName" class="as_detail_do_factoryName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->factoryName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->factoryName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->factoryName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->createdDate->Visible) { // createdDate ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->createdDate) == "") { ?>
		<th data-name="createdDate"><div id="elh_as_detail_do_createdDate" class="as_detail_do_createdDate"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->createdDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="createdDate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->createdDate) ?>',2);"><div id="elh_as_detail_do_createdDate" class="as_detail_do_createdDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->createdDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->createdDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->createdDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->createdUserID->Visible) { // createdUserID ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->createdUserID) == "") { ?>
		<th data-name="createdUserID"><div id="elh_as_detail_do_createdUserID" class="as_detail_do_createdUserID"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->createdUserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="createdUserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->createdUserID) ?>',2);"><div id="elh_as_detail_do_createdUserID" class="as_detail_do_createdUserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->createdUserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->createdUserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->createdUserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->modifiedDate->Visible) { // modifiedDate ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->modifiedDate) == "") { ?>
		<th data-name="modifiedDate"><div id="elh_as_detail_do_modifiedDate" class="as_detail_do_modifiedDate"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->modifiedDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modifiedDate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->modifiedDate) ?>',2);"><div id="elh_as_detail_do_modifiedDate" class="as_detail_do_modifiedDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->modifiedDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->modifiedDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->modifiedDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_detail_do->modifiedUserID->Visible) { // modifiedUserID ?>
	<?php if ($as_detail_do->SortUrl($as_detail_do->modifiedUserID) == "") { ?>
		<th data-name="modifiedUserID"><div id="elh_as_detail_do_modifiedUserID" class="as_detail_do_modifiedUserID"><div class="ewTableHeaderCaption"><?php echo $as_detail_do->modifiedUserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modifiedUserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_detail_do->SortUrl($as_detail_do->modifiedUserID) ?>',2);"><div id="elh_as_detail_do_modifiedUserID" class="as_detail_do_modifiedUserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_detail_do->modifiedUserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_detail_do->modifiedUserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_detail_do->modifiedUserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$as_detail_do_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($as_detail_do->ExportAll && $as_detail_do->Export <> "") {
	$as_detail_do_list->StopRec = $as_detail_do_list->TotalRecs;
} else {

	// Set the last record to display
	if ($as_detail_do_list->TotalRecs > $as_detail_do_list->StartRec + $as_detail_do_list->DisplayRecs - 1)
		$as_detail_do_list->StopRec = $as_detail_do_list->StartRec + $as_detail_do_list->DisplayRecs - 1;
	else
		$as_detail_do_list->StopRec = $as_detail_do_list->TotalRecs;
}
$as_detail_do_list->RecCnt = $as_detail_do_list->StartRec - 1;
if ($as_detail_do_list->Recordset && !$as_detail_do_list->Recordset->EOF) {
	$as_detail_do_list->Recordset->MoveFirst();
	$bSelectLimit = $as_detail_do_list->UseSelectLimit;
	if (!$bSelectLimit && $as_detail_do_list->StartRec > 1)
		$as_detail_do_list->Recordset->Move($as_detail_do_list->StartRec - 1);
} elseif (!$as_detail_do->AllowAddDeleteRow && $as_detail_do_list->StopRec == 0) {
	$as_detail_do_list->StopRec = $as_detail_do->GridAddRowCount;
}

// Initialize aggregate
$as_detail_do->RowType = EW_ROWTYPE_AGGREGATEINIT;
$as_detail_do->ResetAttrs();
$as_detail_do_list->RenderRow();
while ($as_detail_do_list->RecCnt < $as_detail_do_list->StopRec) {
	$as_detail_do_list->RecCnt++;
	if (intval($as_detail_do_list->RecCnt) >= intval($as_detail_do_list->StartRec)) {
		$as_detail_do_list->RowCnt++;

		// Set up key count
		$as_detail_do_list->KeyCount = $as_detail_do_list->RowIndex;

		// Init row class and style
		$as_detail_do->ResetAttrs();
		$as_detail_do->CssClass = "";
		if ($as_detail_do->CurrentAction == "gridadd") {
		} else {
			$as_detail_do_list->LoadRowValues($as_detail_do_list->Recordset); // Load row values
		}
		$as_detail_do->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$as_detail_do->RowAttrs = array_merge($as_detail_do->RowAttrs, array('data-rowindex'=>$as_detail_do_list->RowCnt, 'id'=>'r' . $as_detail_do_list->RowCnt . '_as_detail_do', 'data-rowtype'=>$as_detail_do->RowType));

		// Render row
		$as_detail_do_list->RenderRow();

		// Render list options
		$as_detail_do_list->RenderListOptions();
?>
	<tr<?php echo $as_detail_do->RowAttributes() ?>>
<?php

// Render list options (body, left)
$as_detail_do_list->ListOptions->Render("body", "left", $as_detail_do_list->RowCnt);
?>
	<?php if ($as_detail_do->doID->Visible) { // doID ?>
		<td data-name="doID"<?php echo $as_detail_do->doID->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_doID" class="as_detail_do_doID">
<span<?php echo $as_detail_do->doID->ViewAttributes() ?>>
<?php echo $as_detail_do->doID->ListViewValue() ?></span>
</span>
<a id="<?php echo $as_detail_do_list->PageObjName . "_row_" . $as_detail_do_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($as_detail_do->doNo->Visible) { // doNo ?>
		<td data-name="doNo"<?php echo $as_detail_do->doNo->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_doNo" class="as_detail_do_doNo">
<span<?php echo $as_detail_do->doNo->ViewAttributes() ?>>
<?php echo $as_detail_do->doNo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->doFaktur->Visible) { // doFaktur ?>
		<td data-name="doFaktur"<?php echo $as_detail_do->doFaktur->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_doFaktur" class="as_detail_do_doFaktur">
<span<?php echo $as_detail_do->doFaktur->ViewAttributes() ?>>
<?php echo $as_detail_do->doFaktur->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->productID->Visible) { // productID ?>
		<td data-name="productID"<?php echo $as_detail_do->productID->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_productID" class="as_detail_do_productID">
<span<?php echo $as_detail_do->productID->ViewAttributes() ?>>
<?php echo $as_detail_do->productID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->productName->Visible) { // productName ?>
		<td data-name="productName"<?php echo $as_detail_do->productName->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_productName" class="as_detail_do_productName">
<span<?php echo $as_detail_do->productName->ViewAttributes() ?>>
<?php echo $as_detail_do->productName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->price->Visible) { // price ?>
		<td data-name="price"<?php echo $as_detail_do->price->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_price" class="as_detail_do_price">
<span<?php echo $as_detail_do->price->ViewAttributes() ?>>
<?php echo $as_detail_do->price->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $as_detail_do->qty->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_qty" class="as_detail_do_qty">
<span<?php echo $as_detail_do->qty->ViewAttributes() ?>>
<?php echo $as_detail_do->qty->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->deliveredQty->Visible) { // deliveredQty ?>
		<td data-name="deliveredQty"<?php echo $as_detail_do->deliveredQty->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_deliveredQty" class="as_detail_do_deliveredQty">
<span<?php echo $as_detail_do->deliveredQty->ViewAttributes() ?>>
<?php echo $as_detail_do->deliveredQty->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->deliveredStatus->Visible) { // deliveredStatus ?>
		<td data-name="deliveredStatus"<?php echo $as_detail_do->deliveredStatus->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_deliveredStatus" class="as_detail_do_deliveredStatus">
<span<?php echo $as_detail_do->deliveredStatus->ViewAttributes() ?>>
<?php echo $as_detail_do->deliveredStatus->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->factoryID->Visible) { // factoryID ?>
		<td data-name="factoryID"<?php echo $as_detail_do->factoryID->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_factoryID" class="as_detail_do_factoryID">
<span<?php echo $as_detail_do->factoryID->ViewAttributes() ?>>
<?php echo $as_detail_do->factoryID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->factoryName->Visible) { // factoryName ?>
		<td data-name="factoryName"<?php echo $as_detail_do->factoryName->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_factoryName" class="as_detail_do_factoryName">
<span<?php echo $as_detail_do->factoryName->ViewAttributes() ?>>
<?php echo $as_detail_do->factoryName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->createdDate->Visible) { // createdDate ?>
		<td data-name="createdDate"<?php echo $as_detail_do->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_createdDate" class="as_detail_do_createdDate">
<span<?php echo $as_detail_do->createdDate->ViewAttributes() ?>>
<?php echo $as_detail_do->createdDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->createdUserID->Visible) { // createdUserID ?>
		<td data-name="createdUserID"<?php echo $as_detail_do->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_createdUserID" class="as_detail_do_createdUserID">
<span<?php echo $as_detail_do->createdUserID->ViewAttributes() ?>>
<?php echo $as_detail_do->createdUserID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->modifiedDate->Visible) { // modifiedDate ?>
		<td data-name="modifiedDate"<?php echo $as_detail_do->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_modifiedDate" class="as_detail_do_modifiedDate">
<span<?php echo $as_detail_do->modifiedDate->ViewAttributes() ?>>
<?php echo $as_detail_do->modifiedDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_detail_do->modifiedUserID->Visible) { // modifiedUserID ?>
		<td data-name="modifiedUserID"<?php echo $as_detail_do->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_detail_do_list->RowCnt ?>_as_detail_do_modifiedUserID" class="as_detail_do_modifiedUserID">
<span<?php echo $as_detail_do->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_detail_do->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$as_detail_do_list->ListOptions->Render("body", "right", $as_detail_do_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($as_detail_do->CurrentAction <> "gridadd")
		$as_detail_do_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($as_detail_do->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($as_detail_do_list->Recordset)
	$as_detail_do_list->Recordset->Close();
?>
<?php if ($as_detail_do->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($as_detail_do->CurrentAction <> "gridadd" && $as_detail_do->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_detail_do_list->Pager)) $as_detail_do_list->Pager = new cPrevNextPager($as_detail_do_list->StartRec, $as_detail_do_list->DisplayRecs, $as_detail_do_list->TotalRecs) ?>
<?php if ($as_detail_do_list->Pager->RecordCount > 0 && $as_detail_do_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_detail_do_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_detail_do_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_detail_do_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_detail_do_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_detail_do_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_detail_do_list->PageUrl() ?>start=<?php echo $as_detail_do_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_detail_do_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $as_detail_do_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $as_detail_do_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $as_detail_do_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($as_detail_do_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $as_detail_do_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="as_detail_do">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($as_detail_do_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($as_detail_do_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($as_detail_do_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($as_detail_do->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_detail_do_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($as_detail_do_list->TotalRecs == 0 && $as_detail_do->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_detail_do_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($as_detail_do->Export == "") { ?>
<script type="text/javascript">
fas_detail_dolistsrch.FilterList = <?php echo $as_detail_do_list->GetFilterList() ?>;
fas_detail_dolistsrch.Init();
fas_detail_dolist.Init();
</script>
<?php } ?>
<?php
$as_detail_do_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($as_detail_do->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$as_detail_do_list->Page_Terminate();
?>
