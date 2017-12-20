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

$as_customers_list = NULL; // Initialize page object first

class cas_customers_list extends cas_customers {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_customers';

	// Page object name
	var $PageObjName = 'as_customers_list';

	// Grid form hidden field names
	var $FormName = 'fas_customerslist';
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

		// Table object (as_customers)
		if (!isset($GLOBALS["as_customers"]) || get_class($GLOBALS["as_customers"]) == "cas_customers") {
			$GLOBALS["as_customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_customers"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "as_customersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "as_customersdelete.php";
		$this->MultiUpdateUrl = "as_customersupdate.php";

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fas_customerslistsrch";

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
		$this->customerID->SetVisibility();
		$this->customerID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->customerCode->SetVisibility();
		$this->customerName->SetVisibility();
		$this->contactPerson->SetVisibility();
		$this->address->SetVisibility();
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
			$this->customerID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->customerID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fas_customerslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->customerID->AdvancedSearch->ToJSON(), ","); // Field customerID
		$sFilterList = ew_Concat($sFilterList, $this->customerCode->AdvancedSearch->ToJSON(), ","); // Field customerCode
		$sFilterList = ew_Concat($sFilterList, $this->customerName->AdvancedSearch->ToJSON(), ","); // Field customerName
		$sFilterList = ew_Concat($sFilterList, $this->contactPerson->AdvancedSearch->ToJSON(), ","); // Field contactPerson
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJSON(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->address2->AdvancedSearch->ToJSON(), ","); // Field address2
		$sFilterList = ew_Concat($sFilterList, $this->village->AdvancedSearch->ToJSON(), ","); // Field village
		$sFilterList = ew_Concat($sFilterList, $this->district->AdvancedSearch->ToJSON(), ","); // Field district
		$sFilterList = ew_Concat($sFilterList, $this->city->AdvancedSearch->ToJSON(), ","); // Field city
		$sFilterList = ew_Concat($sFilterList, $this->zipCode->AdvancedSearch->ToJSON(), ","); // Field zipCode
		$sFilterList = ew_Concat($sFilterList, $this->province->AdvancedSearch->ToJSON(), ","); // Field province
		$sFilterList = ew_Concat($sFilterList, $this->phone1->AdvancedSearch->ToJSON(), ","); // Field phone1
		$sFilterList = ew_Concat($sFilterList, $this->phone2->AdvancedSearch->ToJSON(), ","); // Field phone2
		$sFilterList = ew_Concat($sFilterList, $this->phone3->AdvancedSearch->ToJSON(), ","); // Field phone3
		$sFilterList = ew_Concat($sFilterList, $this->fax1->AdvancedSearch->ToJSON(), ","); // Field fax1
		$sFilterList = ew_Concat($sFilterList, $this->fax2->AdvancedSearch->ToJSON(), ","); // Field fax2
		$sFilterList = ew_Concat($sFilterList, $this->fax3->AdvancedSearch->ToJSON(), ","); // Field fax3
		$sFilterList = ew_Concat($sFilterList, $this->phonecp1->AdvancedSearch->ToJSON(), ","); // Field phonecp1
		$sFilterList = ew_Concat($sFilterList, $this->phonecp2->AdvancedSearch->ToJSON(), ","); // Field phonecp2
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->limitBalance->AdvancedSearch->ToJSON(), ","); // Field limitBalance
		$sFilterList = ew_Concat($sFilterList, $this->balance->AdvancedSearch->ToJSON(), ","); // Field balance
		$sFilterList = ew_Concat($sFilterList, $this->disc1->AdvancedSearch->ToJSON(), ","); // Field disc1
		$sFilterList = ew_Concat($sFilterList, $this->disc2->AdvancedSearch->ToJSON(), ","); // Field disc2
		$sFilterList = ew_Concat($sFilterList, $this->disc3->AdvancedSearch->ToJSON(), ","); // Field disc3
		$sFilterList = ew_Concat($sFilterList, $this->note->AdvancedSearch->ToJSON(), ","); // Field note
		$sFilterList = ew_Concat($sFilterList, $this->npwp->AdvancedSearch->ToJSON(), ","); // Field npwp
		$sFilterList = ew_Concat($sFilterList, $this->pkpName->AdvancedSearch->ToJSON(), ","); // Field pkpName
		$sFilterList = ew_Concat($sFilterList, $this->staffCode->AdvancedSearch->ToJSON(), ","); // Field staffCode
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fas_customerslistsrch", $filters);

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

		// Field customerID
		$this->customerID->AdvancedSearch->SearchValue = @$filter["x_customerID"];
		$this->customerID->AdvancedSearch->SearchOperator = @$filter["z_customerID"];
		$this->customerID->AdvancedSearch->SearchCondition = @$filter["v_customerID"];
		$this->customerID->AdvancedSearch->SearchValue2 = @$filter["y_customerID"];
		$this->customerID->AdvancedSearch->SearchOperator2 = @$filter["w_customerID"];
		$this->customerID->AdvancedSearch->Save();

		// Field customerCode
		$this->customerCode->AdvancedSearch->SearchValue = @$filter["x_customerCode"];
		$this->customerCode->AdvancedSearch->SearchOperator = @$filter["z_customerCode"];
		$this->customerCode->AdvancedSearch->SearchCondition = @$filter["v_customerCode"];
		$this->customerCode->AdvancedSearch->SearchValue2 = @$filter["y_customerCode"];
		$this->customerCode->AdvancedSearch->SearchOperator2 = @$filter["w_customerCode"];
		$this->customerCode->AdvancedSearch->Save();

		// Field customerName
		$this->customerName->AdvancedSearch->SearchValue = @$filter["x_customerName"];
		$this->customerName->AdvancedSearch->SearchOperator = @$filter["z_customerName"];
		$this->customerName->AdvancedSearch->SearchCondition = @$filter["v_customerName"];
		$this->customerName->AdvancedSearch->SearchValue2 = @$filter["y_customerName"];
		$this->customerName->AdvancedSearch->SearchOperator2 = @$filter["w_customerName"];
		$this->customerName->AdvancedSearch->Save();

		// Field contactPerson
		$this->contactPerson->AdvancedSearch->SearchValue = @$filter["x_contactPerson"];
		$this->contactPerson->AdvancedSearch->SearchOperator = @$filter["z_contactPerson"];
		$this->contactPerson->AdvancedSearch->SearchCondition = @$filter["v_contactPerson"];
		$this->contactPerson->AdvancedSearch->SearchValue2 = @$filter["y_contactPerson"];
		$this->contactPerson->AdvancedSearch->SearchOperator2 = @$filter["w_contactPerson"];
		$this->contactPerson->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field address2
		$this->address2->AdvancedSearch->SearchValue = @$filter["x_address2"];
		$this->address2->AdvancedSearch->SearchOperator = @$filter["z_address2"];
		$this->address2->AdvancedSearch->SearchCondition = @$filter["v_address2"];
		$this->address2->AdvancedSearch->SearchValue2 = @$filter["y_address2"];
		$this->address2->AdvancedSearch->SearchOperator2 = @$filter["w_address2"];
		$this->address2->AdvancedSearch->Save();

		// Field village
		$this->village->AdvancedSearch->SearchValue = @$filter["x_village"];
		$this->village->AdvancedSearch->SearchOperator = @$filter["z_village"];
		$this->village->AdvancedSearch->SearchCondition = @$filter["v_village"];
		$this->village->AdvancedSearch->SearchValue2 = @$filter["y_village"];
		$this->village->AdvancedSearch->SearchOperator2 = @$filter["w_village"];
		$this->village->AdvancedSearch->Save();

		// Field district
		$this->district->AdvancedSearch->SearchValue = @$filter["x_district"];
		$this->district->AdvancedSearch->SearchOperator = @$filter["z_district"];
		$this->district->AdvancedSearch->SearchCondition = @$filter["v_district"];
		$this->district->AdvancedSearch->SearchValue2 = @$filter["y_district"];
		$this->district->AdvancedSearch->SearchOperator2 = @$filter["w_district"];
		$this->district->AdvancedSearch->Save();

		// Field city
		$this->city->AdvancedSearch->SearchValue = @$filter["x_city"];
		$this->city->AdvancedSearch->SearchOperator = @$filter["z_city"];
		$this->city->AdvancedSearch->SearchCondition = @$filter["v_city"];
		$this->city->AdvancedSearch->SearchValue2 = @$filter["y_city"];
		$this->city->AdvancedSearch->SearchOperator2 = @$filter["w_city"];
		$this->city->AdvancedSearch->Save();

		// Field zipCode
		$this->zipCode->AdvancedSearch->SearchValue = @$filter["x_zipCode"];
		$this->zipCode->AdvancedSearch->SearchOperator = @$filter["z_zipCode"];
		$this->zipCode->AdvancedSearch->SearchCondition = @$filter["v_zipCode"];
		$this->zipCode->AdvancedSearch->SearchValue2 = @$filter["y_zipCode"];
		$this->zipCode->AdvancedSearch->SearchOperator2 = @$filter["w_zipCode"];
		$this->zipCode->AdvancedSearch->Save();

		// Field province
		$this->province->AdvancedSearch->SearchValue = @$filter["x_province"];
		$this->province->AdvancedSearch->SearchOperator = @$filter["z_province"];
		$this->province->AdvancedSearch->SearchCondition = @$filter["v_province"];
		$this->province->AdvancedSearch->SearchValue2 = @$filter["y_province"];
		$this->province->AdvancedSearch->SearchOperator2 = @$filter["w_province"];
		$this->province->AdvancedSearch->Save();

		// Field phone1
		$this->phone1->AdvancedSearch->SearchValue = @$filter["x_phone1"];
		$this->phone1->AdvancedSearch->SearchOperator = @$filter["z_phone1"];
		$this->phone1->AdvancedSearch->SearchCondition = @$filter["v_phone1"];
		$this->phone1->AdvancedSearch->SearchValue2 = @$filter["y_phone1"];
		$this->phone1->AdvancedSearch->SearchOperator2 = @$filter["w_phone1"];
		$this->phone1->AdvancedSearch->Save();

		// Field phone2
		$this->phone2->AdvancedSearch->SearchValue = @$filter["x_phone2"];
		$this->phone2->AdvancedSearch->SearchOperator = @$filter["z_phone2"];
		$this->phone2->AdvancedSearch->SearchCondition = @$filter["v_phone2"];
		$this->phone2->AdvancedSearch->SearchValue2 = @$filter["y_phone2"];
		$this->phone2->AdvancedSearch->SearchOperator2 = @$filter["w_phone2"];
		$this->phone2->AdvancedSearch->Save();

		// Field phone3
		$this->phone3->AdvancedSearch->SearchValue = @$filter["x_phone3"];
		$this->phone3->AdvancedSearch->SearchOperator = @$filter["z_phone3"];
		$this->phone3->AdvancedSearch->SearchCondition = @$filter["v_phone3"];
		$this->phone3->AdvancedSearch->SearchValue2 = @$filter["y_phone3"];
		$this->phone3->AdvancedSearch->SearchOperator2 = @$filter["w_phone3"];
		$this->phone3->AdvancedSearch->Save();

		// Field fax1
		$this->fax1->AdvancedSearch->SearchValue = @$filter["x_fax1"];
		$this->fax1->AdvancedSearch->SearchOperator = @$filter["z_fax1"];
		$this->fax1->AdvancedSearch->SearchCondition = @$filter["v_fax1"];
		$this->fax1->AdvancedSearch->SearchValue2 = @$filter["y_fax1"];
		$this->fax1->AdvancedSearch->SearchOperator2 = @$filter["w_fax1"];
		$this->fax1->AdvancedSearch->Save();

		// Field fax2
		$this->fax2->AdvancedSearch->SearchValue = @$filter["x_fax2"];
		$this->fax2->AdvancedSearch->SearchOperator = @$filter["z_fax2"];
		$this->fax2->AdvancedSearch->SearchCondition = @$filter["v_fax2"];
		$this->fax2->AdvancedSearch->SearchValue2 = @$filter["y_fax2"];
		$this->fax2->AdvancedSearch->SearchOperator2 = @$filter["w_fax2"];
		$this->fax2->AdvancedSearch->Save();

		// Field fax3
		$this->fax3->AdvancedSearch->SearchValue = @$filter["x_fax3"];
		$this->fax3->AdvancedSearch->SearchOperator = @$filter["z_fax3"];
		$this->fax3->AdvancedSearch->SearchCondition = @$filter["v_fax3"];
		$this->fax3->AdvancedSearch->SearchValue2 = @$filter["y_fax3"];
		$this->fax3->AdvancedSearch->SearchOperator2 = @$filter["w_fax3"];
		$this->fax3->AdvancedSearch->Save();

		// Field phonecp1
		$this->phonecp1->AdvancedSearch->SearchValue = @$filter["x_phonecp1"];
		$this->phonecp1->AdvancedSearch->SearchOperator = @$filter["z_phonecp1"];
		$this->phonecp1->AdvancedSearch->SearchCondition = @$filter["v_phonecp1"];
		$this->phonecp1->AdvancedSearch->SearchValue2 = @$filter["y_phonecp1"];
		$this->phonecp1->AdvancedSearch->SearchOperator2 = @$filter["w_phonecp1"];
		$this->phonecp1->AdvancedSearch->Save();

		// Field phonecp2
		$this->phonecp2->AdvancedSearch->SearchValue = @$filter["x_phonecp2"];
		$this->phonecp2->AdvancedSearch->SearchOperator = @$filter["z_phonecp2"];
		$this->phonecp2->AdvancedSearch->SearchCondition = @$filter["v_phonecp2"];
		$this->phonecp2->AdvancedSearch->SearchValue2 = @$filter["y_phonecp2"];
		$this->phonecp2->AdvancedSearch->SearchOperator2 = @$filter["w_phonecp2"];
		$this->phonecp2->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field limitBalance
		$this->limitBalance->AdvancedSearch->SearchValue = @$filter["x_limitBalance"];
		$this->limitBalance->AdvancedSearch->SearchOperator = @$filter["z_limitBalance"];
		$this->limitBalance->AdvancedSearch->SearchCondition = @$filter["v_limitBalance"];
		$this->limitBalance->AdvancedSearch->SearchValue2 = @$filter["y_limitBalance"];
		$this->limitBalance->AdvancedSearch->SearchOperator2 = @$filter["w_limitBalance"];
		$this->limitBalance->AdvancedSearch->Save();

		// Field balance
		$this->balance->AdvancedSearch->SearchValue = @$filter["x_balance"];
		$this->balance->AdvancedSearch->SearchOperator = @$filter["z_balance"];
		$this->balance->AdvancedSearch->SearchCondition = @$filter["v_balance"];
		$this->balance->AdvancedSearch->SearchValue2 = @$filter["y_balance"];
		$this->balance->AdvancedSearch->SearchOperator2 = @$filter["w_balance"];
		$this->balance->AdvancedSearch->Save();

		// Field disc1
		$this->disc1->AdvancedSearch->SearchValue = @$filter["x_disc1"];
		$this->disc1->AdvancedSearch->SearchOperator = @$filter["z_disc1"];
		$this->disc1->AdvancedSearch->SearchCondition = @$filter["v_disc1"];
		$this->disc1->AdvancedSearch->SearchValue2 = @$filter["y_disc1"];
		$this->disc1->AdvancedSearch->SearchOperator2 = @$filter["w_disc1"];
		$this->disc1->AdvancedSearch->Save();

		// Field disc2
		$this->disc2->AdvancedSearch->SearchValue = @$filter["x_disc2"];
		$this->disc2->AdvancedSearch->SearchOperator = @$filter["z_disc2"];
		$this->disc2->AdvancedSearch->SearchCondition = @$filter["v_disc2"];
		$this->disc2->AdvancedSearch->SearchValue2 = @$filter["y_disc2"];
		$this->disc2->AdvancedSearch->SearchOperator2 = @$filter["w_disc2"];
		$this->disc2->AdvancedSearch->Save();

		// Field disc3
		$this->disc3->AdvancedSearch->SearchValue = @$filter["x_disc3"];
		$this->disc3->AdvancedSearch->SearchOperator = @$filter["z_disc3"];
		$this->disc3->AdvancedSearch->SearchCondition = @$filter["v_disc3"];
		$this->disc3->AdvancedSearch->SearchValue2 = @$filter["y_disc3"];
		$this->disc3->AdvancedSearch->SearchOperator2 = @$filter["w_disc3"];
		$this->disc3->AdvancedSearch->Save();

		// Field note
		$this->note->AdvancedSearch->SearchValue = @$filter["x_note"];
		$this->note->AdvancedSearch->SearchOperator = @$filter["z_note"];
		$this->note->AdvancedSearch->SearchCondition = @$filter["v_note"];
		$this->note->AdvancedSearch->SearchValue2 = @$filter["y_note"];
		$this->note->AdvancedSearch->SearchOperator2 = @$filter["w_note"];
		$this->note->AdvancedSearch->Save();

		// Field npwp
		$this->npwp->AdvancedSearch->SearchValue = @$filter["x_npwp"];
		$this->npwp->AdvancedSearch->SearchOperator = @$filter["z_npwp"];
		$this->npwp->AdvancedSearch->SearchCondition = @$filter["v_npwp"];
		$this->npwp->AdvancedSearch->SearchValue2 = @$filter["y_npwp"];
		$this->npwp->AdvancedSearch->SearchOperator2 = @$filter["w_npwp"];
		$this->npwp->AdvancedSearch->Save();

		// Field pkpName
		$this->pkpName->AdvancedSearch->SearchValue = @$filter["x_pkpName"];
		$this->pkpName->AdvancedSearch->SearchOperator = @$filter["z_pkpName"];
		$this->pkpName->AdvancedSearch->SearchCondition = @$filter["v_pkpName"];
		$this->pkpName->AdvancedSearch->SearchValue2 = @$filter["y_pkpName"];
		$this->pkpName->AdvancedSearch->SearchOperator2 = @$filter["w_pkpName"];
		$this->pkpName->AdvancedSearch->Save();

		// Field staffCode
		$this->staffCode->AdvancedSearch->SearchValue = @$filter["x_staffCode"];
		$this->staffCode->AdvancedSearch->SearchOperator = @$filter["z_staffCode"];
		$this->staffCode->AdvancedSearch->SearchCondition = @$filter["v_staffCode"];
		$this->staffCode->AdvancedSearch->SearchValue2 = @$filter["y_staffCode"];
		$this->staffCode->AdvancedSearch->SearchOperator2 = @$filter["w_staffCode"];
		$this->staffCode->AdvancedSearch->Save();

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
		$this->BuildBasicSearchSQL($sWhere, $this->customerCode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->customerName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contactPerson, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->village, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->district, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->city, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->province, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phonecp1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phonecp2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->note, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->npwp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pkpName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staffCode, $arKeywords, $type);
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
			$this->UpdateSort($this->customerID, $bCtrl); // customerID
			$this->UpdateSort($this->customerCode, $bCtrl); // customerCode
			$this->UpdateSort($this->customerName, $bCtrl); // customerName
			$this->UpdateSort($this->contactPerson, $bCtrl); // contactPerson
			$this->UpdateSort($this->address, $bCtrl); // address
			$this->UpdateSort($this->village, $bCtrl); // village
			$this->UpdateSort($this->district, $bCtrl); // district
			$this->UpdateSort($this->city, $bCtrl); // city
			$this->UpdateSort($this->zipCode, $bCtrl); // zipCode
			$this->UpdateSort($this->province, $bCtrl); // province
			$this->UpdateSort($this->phone1, $bCtrl); // phone1
			$this->UpdateSort($this->phone2, $bCtrl); // phone2
			$this->UpdateSort($this->phone3, $bCtrl); // phone3
			$this->UpdateSort($this->fax1, $bCtrl); // fax1
			$this->UpdateSort($this->fax2, $bCtrl); // fax2
			$this->UpdateSort($this->fax3, $bCtrl); // fax3
			$this->UpdateSort($this->phonecp1, $bCtrl); // phonecp1
			$this->UpdateSort($this->phonecp2, $bCtrl); // phonecp2
			$this->UpdateSort($this->_email, $bCtrl); // email
			$this->UpdateSort($this->limitBalance, $bCtrl); // limitBalance
			$this->UpdateSort($this->balance, $bCtrl); // balance
			$this->UpdateSort($this->disc1, $bCtrl); // disc1
			$this->UpdateSort($this->disc2, $bCtrl); // disc2
			$this->UpdateSort($this->disc3, $bCtrl); // disc3
			$this->UpdateSort($this->npwp, $bCtrl); // npwp
			$this->UpdateSort($this->pkpName, $bCtrl); // pkpName
			$this->UpdateSort($this->staffCode, $bCtrl); // staffCode
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
				$this->customerID->setSort("");
				$this->customerCode->setSort("");
				$this->customerName->setSort("");
				$this->contactPerson->setSort("");
				$this->address->setSort("");
				$this->village->setSort("");
				$this->district->setSort("");
				$this->city->setSort("");
				$this->zipCode->setSort("");
				$this->province->setSort("");
				$this->phone1->setSort("");
				$this->phone2->setSort("");
				$this->phone3->setSort("");
				$this->fax1->setSort("");
				$this->fax2->setSort("");
				$this->fax3->setSort("");
				$this->phonecp1->setSort("");
				$this->phonecp2->setSort("");
				$this->_email->setSort("");
				$this->limitBalance->setSort("");
				$this->balance->setSort("");
				$this->disc1->setSort("");
				$this->disc2->setSort("");
				$this->disc3->setSort("");
				$this->npwp->setSort("");
				$this->pkpName->setSort("");
				$this->staffCode->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->customerID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fas_customerslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fas_customerslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fas_customerslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fas_customerslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fas_customerslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("customerID")) <> "")
			$this->customerID->CurrentValue = $this->getKey("customerID"); // customerID
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
		$item->Body = "<button id=\"emf_as_customers\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_as_customers',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fas_customerslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($as_customers_list)) $as_customers_list = new cas_customers_list();

// Page init
$as_customers_list->Page_Init();

// Page main
$as_customers_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_customers_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($as_customers->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fas_customerslist = new ew_Form("fas_customerslist", "list");
fas_customerslist.FormKeyCountName = '<?php echo $as_customers_list->FormKeyCountName ?>';

// Form_CustomValidate event
fas_customerslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_customerslist.ValidateRequired = true;
<?php } else { ?>
fas_customerslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fas_customerslistsrch = new ew_Form("fas_customerslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($as_customers->Export == "") { ?>
<div class="ewToolbar">
<?php if ($as_customers->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($as_customers_list->TotalRecs > 0 && $as_customers_list->ExportOptions->Visible()) { ?>
<?php $as_customers_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($as_customers_list->SearchOptions->Visible()) { ?>
<?php $as_customers_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($as_customers_list->FilterOptions->Visible()) { ?>
<?php $as_customers_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($as_customers->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $as_customers_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($as_customers_list->TotalRecs <= 0)
			$as_customers_list->TotalRecs = $as_customers->SelectRecordCount();
	} else {
		if (!$as_customers_list->Recordset && ($as_customers_list->Recordset = $as_customers_list->LoadRecordset()))
			$as_customers_list->TotalRecs = $as_customers_list->Recordset->RecordCount();
	}
	$as_customers_list->StartRec = 1;
	if ($as_customers_list->DisplayRecs <= 0 || ($as_customers->Export <> "" && $as_customers->ExportAll)) // Display all records
		$as_customers_list->DisplayRecs = $as_customers_list->TotalRecs;
	if (!($as_customers->Export <> "" && $as_customers->ExportAll))
		$as_customers_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$as_customers_list->Recordset = $as_customers_list->LoadRecordset($as_customers_list->StartRec-1, $as_customers_list->DisplayRecs);

	// Set no record found message
	if ($as_customers->CurrentAction == "" && $as_customers_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$as_customers_list->setWarningMessage(ew_DeniedMsg());
		if ($as_customers_list->SearchWhere == "0=101")
			$as_customers_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$as_customers_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$as_customers_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($as_customers->Export == "" && $as_customers->CurrentAction == "") { ?>
<form name="fas_customerslistsrch" id="fas_customerslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($as_customers_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fas_customerslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="as_customers">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($as_customers_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($as_customers_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $as_customers_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($as_customers_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($as_customers_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($as_customers_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($as_customers_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $as_customers_list->ShowPageHeader(); ?>
<?php
$as_customers_list->ShowMessage();
?>
<?php if ($as_customers_list->TotalRecs > 0 || $as_customers->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid as_customers">
<?php if ($as_customers->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($as_customers->CurrentAction <> "gridadd" && $as_customers->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_customers_list->Pager)) $as_customers_list->Pager = new cPrevNextPager($as_customers_list->StartRec, $as_customers_list->DisplayRecs, $as_customers_list->TotalRecs) ?>
<?php if ($as_customers_list->Pager->RecordCount > 0 && $as_customers_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $as_customers_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $as_customers_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $as_customers_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($as_customers_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $as_customers_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="as_customers">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($as_customers_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($as_customers_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($as_customers_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($as_customers->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_customers_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fas_customerslist" id="fas_customerslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_customers_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_customers_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_customers">
<div id="gmp_as_customers" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($as_customers_list->TotalRecs > 0 || $as_customers->CurrentAction == "gridedit") { ?>
<table id="tbl_as_customerslist" class="table ewTable">
<?php echo $as_customers->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$as_customers_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$as_customers_list->RenderListOptions();

// Render list options (header, left)
$as_customers_list->ListOptions->Render("header", "left");
?>
<?php if ($as_customers->customerID->Visible) { // customerID ?>
	<?php if ($as_customers->SortUrl($as_customers->customerID) == "") { ?>
		<th data-name="customerID"><div id="elh_as_customers_customerID" class="as_customers_customerID"><div class="ewTableHeaderCaption"><?php echo $as_customers->customerID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="customerID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->customerID) ?>',2);"><div id="elh_as_customers_customerID" class="as_customers_customerID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->customerID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->customerID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->customerID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
	<?php if ($as_customers->SortUrl($as_customers->customerCode) == "") { ?>
		<th data-name="customerCode"><div id="elh_as_customers_customerCode" class="as_customers_customerCode"><div class="ewTableHeaderCaption"><?php echo $as_customers->customerCode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="customerCode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->customerCode) ?>',2);"><div id="elh_as_customers_customerCode" class="as_customers_customerCode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->customerCode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->customerCode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->customerCode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->customerName->Visible) { // customerName ?>
	<?php if ($as_customers->SortUrl($as_customers->customerName) == "") { ?>
		<th data-name="customerName"><div id="elh_as_customers_customerName" class="as_customers_customerName"><div class="ewTableHeaderCaption"><?php echo $as_customers->customerName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="customerName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->customerName) ?>',2);"><div id="elh_as_customers_customerName" class="as_customers_customerName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->customerName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->customerName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->customerName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
	<?php if ($as_customers->SortUrl($as_customers->contactPerson) == "") { ?>
		<th data-name="contactPerson"><div id="elh_as_customers_contactPerson" class="as_customers_contactPerson"><div class="ewTableHeaderCaption"><?php echo $as_customers->contactPerson->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contactPerson"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->contactPerson) ?>',2);"><div id="elh_as_customers_contactPerson" class="as_customers_contactPerson">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->contactPerson->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->contactPerson->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->contactPerson->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->address->Visible) { // address ?>
	<?php if ($as_customers->SortUrl($as_customers->address) == "") { ?>
		<th data-name="address"><div id="elh_as_customers_address" class="as_customers_address"><div class="ewTableHeaderCaption"><?php echo $as_customers->address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->address) ?>',2);"><div id="elh_as_customers_address" class="as_customers_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->village->Visible) { // village ?>
	<?php if ($as_customers->SortUrl($as_customers->village) == "") { ?>
		<th data-name="village"><div id="elh_as_customers_village" class="as_customers_village"><div class="ewTableHeaderCaption"><?php echo $as_customers->village->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="village"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->village) ?>',2);"><div id="elh_as_customers_village" class="as_customers_village">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->village->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->village->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->village->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->district->Visible) { // district ?>
	<?php if ($as_customers->SortUrl($as_customers->district) == "") { ?>
		<th data-name="district"><div id="elh_as_customers_district" class="as_customers_district"><div class="ewTableHeaderCaption"><?php echo $as_customers->district->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="district"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->district) ?>',2);"><div id="elh_as_customers_district" class="as_customers_district">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->district->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->district->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->district->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->city->Visible) { // city ?>
	<?php if ($as_customers->SortUrl($as_customers->city) == "") { ?>
		<th data-name="city"><div id="elh_as_customers_city" class="as_customers_city"><div class="ewTableHeaderCaption"><?php echo $as_customers->city->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="city"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->city) ?>',2);"><div id="elh_as_customers_city" class="as_customers_city">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->city->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->city->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->city->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
	<?php if ($as_customers->SortUrl($as_customers->zipCode) == "") { ?>
		<th data-name="zipCode"><div id="elh_as_customers_zipCode" class="as_customers_zipCode"><div class="ewTableHeaderCaption"><?php echo $as_customers->zipCode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zipCode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->zipCode) ?>',2);"><div id="elh_as_customers_zipCode" class="as_customers_zipCode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->zipCode->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->zipCode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->zipCode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->province->Visible) { // province ?>
	<?php if ($as_customers->SortUrl($as_customers->province) == "") { ?>
		<th data-name="province"><div id="elh_as_customers_province" class="as_customers_province"><div class="ewTableHeaderCaption"><?php echo $as_customers->province->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="province"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->province) ?>',2);"><div id="elh_as_customers_province" class="as_customers_province">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->province->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->province->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->province->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->phone1->Visible) { // phone1 ?>
	<?php if ($as_customers->SortUrl($as_customers->phone1) == "") { ?>
		<th data-name="phone1"><div id="elh_as_customers_phone1" class="as_customers_phone1"><div class="ewTableHeaderCaption"><?php echo $as_customers->phone1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->phone1) ?>',2);"><div id="elh_as_customers_phone1" class="as_customers_phone1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->phone1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->phone1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->phone1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->phone2->Visible) { // phone2 ?>
	<?php if ($as_customers->SortUrl($as_customers->phone2) == "") { ?>
		<th data-name="phone2"><div id="elh_as_customers_phone2" class="as_customers_phone2"><div class="ewTableHeaderCaption"><?php echo $as_customers->phone2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->phone2) ?>',2);"><div id="elh_as_customers_phone2" class="as_customers_phone2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->phone2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->phone2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->phone2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->phone3->Visible) { // phone3 ?>
	<?php if ($as_customers->SortUrl($as_customers->phone3) == "") { ?>
		<th data-name="phone3"><div id="elh_as_customers_phone3" class="as_customers_phone3"><div class="ewTableHeaderCaption"><?php echo $as_customers->phone3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->phone3) ?>',2);"><div id="elh_as_customers_phone3" class="as_customers_phone3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->phone3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->phone3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->phone3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->fax1->Visible) { // fax1 ?>
	<?php if ($as_customers->SortUrl($as_customers->fax1) == "") { ?>
		<th data-name="fax1"><div id="elh_as_customers_fax1" class="as_customers_fax1"><div class="ewTableHeaderCaption"><?php echo $as_customers->fax1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->fax1) ?>',2);"><div id="elh_as_customers_fax1" class="as_customers_fax1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->fax1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->fax1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->fax1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->fax2->Visible) { // fax2 ?>
	<?php if ($as_customers->SortUrl($as_customers->fax2) == "") { ?>
		<th data-name="fax2"><div id="elh_as_customers_fax2" class="as_customers_fax2"><div class="ewTableHeaderCaption"><?php echo $as_customers->fax2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->fax2) ?>',2);"><div id="elh_as_customers_fax2" class="as_customers_fax2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->fax2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->fax2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->fax2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->fax3->Visible) { // fax3 ?>
	<?php if ($as_customers->SortUrl($as_customers->fax3) == "") { ?>
		<th data-name="fax3"><div id="elh_as_customers_fax3" class="as_customers_fax3"><div class="ewTableHeaderCaption"><?php echo $as_customers->fax3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->fax3) ?>',2);"><div id="elh_as_customers_fax3" class="as_customers_fax3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->fax3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->fax3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->fax3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
	<?php if ($as_customers->SortUrl($as_customers->phonecp1) == "") { ?>
		<th data-name="phonecp1"><div id="elh_as_customers_phonecp1" class="as_customers_phonecp1"><div class="ewTableHeaderCaption"><?php echo $as_customers->phonecp1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phonecp1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->phonecp1) ?>',2);"><div id="elh_as_customers_phonecp1" class="as_customers_phonecp1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->phonecp1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->phonecp1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->phonecp1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
	<?php if ($as_customers->SortUrl($as_customers->phonecp2) == "") { ?>
		<th data-name="phonecp2"><div id="elh_as_customers_phonecp2" class="as_customers_phonecp2"><div class="ewTableHeaderCaption"><?php echo $as_customers->phonecp2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phonecp2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->phonecp2) ?>',2);"><div id="elh_as_customers_phonecp2" class="as_customers_phonecp2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->phonecp2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->phonecp2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->phonecp2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->_email->Visible) { // email ?>
	<?php if ($as_customers->SortUrl($as_customers->_email) == "") { ?>
		<th data-name="_email"><div id="elh_as_customers__email" class="as_customers__email"><div class="ewTableHeaderCaption"><?php echo $as_customers->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->_email) ?>',2);"><div id="elh_as_customers__email" class="as_customers__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
	<?php if ($as_customers->SortUrl($as_customers->limitBalance) == "") { ?>
		<th data-name="limitBalance"><div id="elh_as_customers_limitBalance" class="as_customers_limitBalance"><div class="ewTableHeaderCaption"><?php echo $as_customers->limitBalance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="limitBalance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->limitBalance) ?>',2);"><div id="elh_as_customers_limitBalance" class="as_customers_limitBalance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->limitBalance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->limitBalance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->limitBalance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->balance->Visible) { // balance ?>
	<?php if ($as_customers->SortUrl($as_customers->balance) == "") { ?>
		<th data-name="balance"><div id="elh_as_customers_balance" class="as_customers_balance"><div class="ewTableHeaderCaption"><?php echo $as_customers->balance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="balance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->balance) ?>',2);"><div id="elh_as_customers_balance" class="as_customers_balance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->balance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->balance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->balance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->disc1->Visible) { // disc1 ?>
	<?php if ($as_customers->SortUrl($as_customers->disc1) == "") { ?>
		<th data-name="disc1"><div id="elh_as_customers_disc1" class="as_customers_disc1"><div class="ewTableHeaderCaption"><?php echo $as_customers->disc1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="disc1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->disc1) ?>',2);"><div id="elh_as_customers_disc1" class="as_customers_disc1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->disc1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->disc1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->disc1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->disc2->Visible) { // disc2 ?>
	<?php if ($as_customers->SortUrl($as_customers->disc2) == "") { ?>
		<th data-name="disc2"><div id="elh_as_customers_disc2" class="as_customers_disc2"><div class="ewTableHeaderCaption"><?php echo $as_customers->disc2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="disc2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->disc2) ?>',2);"><div id="elh_as_customers_disc2" class="as_customers_disc2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->disc2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->disc2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->disc2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->disc3->Visible) { // disc3 ?>
	<?php if ($as_customers->SortUrl($as_customers->disc3) == "") { ?>
		<th data-name="disc3"><div id="elh_as_customers_disc3" class="as_customers_disc3"><div class="ewTableHeaderCaption"><?php echo $as_customers->disc3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="disc3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->disc3) ?>',2);"><div id="elh_as_customers_disc3" class="as_customers_disc3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->disc3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->disc3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->disc3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->npwp->Visible) { // npwp ?>
	<?php if ($as_customers->SortUrl($as_customers->npwp) == "") { ?>
		<th data-name="npwp"><div id="elh_as_customers_npwp" class="as_customers_npwp"><div class="ewTableHeaderCaption"><?php echo $as_customers->npwp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="npwp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->npwp) ?>',2);"><div id="elh_as_customers_npwp" class="as_customers_npwp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->npwp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->npwp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->npwp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
	<?php if ($as_customers->SortUrl($as_customers->pkpName) == "") { ?>
		<th data-name="pkpName"><div id="elh_as_customers_pkpName" class="as_customers_pkpName"><div class="ewTableHeaderCaption"><?php echo $as_customers->pkpName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pkpName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->pkpName) ?>',2);"><div id="elh_as_customers_pkpName" class="as_customers_pkpName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->pkpName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->pkpName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->pkpName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
	<?php if ($as_customers->SortUrl($as_customers->staffCode) == "") { ?>
		<th data-name="staffCode"><div id="elh_as_customers_staffCode" class="as_customers_staffCode"><div class="ewTableHeaderCaption"><?php echo $as_customers->staffCode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staffCode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->staffCode) ?>',2);"><div id="elh_as_customers_staffCode" class="as_customers_staffCode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->staffCode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->staffCode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->staffCode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
	<?php if ($as_customers->SortUrl($as_customers->createdDate) == "") { ?>
		<th data-name="createdDate"><div id="elh_as_customers_createdDate" class="as_customers_createdDate"><div class="ewTableHeaderCaption"><?php echo $as_customers->createdDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="createdDate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->createdDate) ?>',2);"><div id="elh_as_customers_createdDate" class="as_customers_createdDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->createdDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->createdDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->createdDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
	<?php if ($as_customers->SortUrl($as_customers->createdUserID) == "") { ?>
		<th data-name="createdUserID"><div id="elh_as_customers_createdUserID" class="as_customers_createdUserID"><div class="ewTableHeaderCaption"><?php echo $as_customers->createdUserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="createdUserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->createdUserID) ?>',2);"><div id="elh_as_customers_createdUserID" class="as_customers_createdUserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->createdUserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->createdUserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->createdUserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
	<?php if ($as_customers->SortUrl($as_customers->modifiedDate) == "") { ?>
		<th data-name="modifiedDate"><div id="elh_as_customers_modifiedDate" class="as_customers_modifiedDate"><div class="ewTableHeaderCaption"><?php echo $as_customers->modifiedDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modifiedDate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->modifiedDate) ?>',2);"><div id="elh_as_customers_modifiedDate" class="as_customers_modifiedDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->modifiedDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->modifiedDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->modifiedDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
	<?php if ($as_customers->SortUrl($as_customers->modifiedUserID) == "") { ?>
		<th data-name="modifiedUserID"><div id="elh_as_customers_modifiedUserID" class="as_customers_modifiedUserID"><div class="ewTableHeaderCaption"><?php echo $as_customers->modifiedUserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modifiedUserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $as_customers->SortUrl($as_customers->modifiedUserID) ?>',2);"><div id="elh_as_customers_modifiedUserID" class="as_customers_modifiedUserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $as_customers->modifiedUserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($as_customers->modifiedUserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($as_customers->modifiedUserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$as_customers_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($as_customers->ExportAll && $as_customers->Export <> "") {
	$as_customers_list->StopRec = $as_customers_list->TotalRecs;
} else {

	// Set the last record to display
	if ($as_customers_list->TotalRecs > $as_customers_list->StartRec + $as_customers_list->DisplayRecs - 1)
		$as_customers_list->StopRec = $as_customers_list->StartRec + $as_customers_list->DisplayRecs - 1;
	else
		$as_customers_list->StopRec = $as_customers_list->TotalRecs;
}
$as_customers_list->RecCnt = $as_customers_list->StartRec - 1;
if ($as_customers_list->Recordset && !$as_customers_list->Recordset->EOF) {
	$as_customers_list->Recordset->MoveFirst();
	$bSelectLimit = $as_customers_list->UseSelectLimit;
	if (!$bSelectLimit && $as_customers_list->StartRec > 1)
		$as_customers_list->Recordset->Move($as_customers_list->StartRec - 1);
} elseif (!$as_customers->AllowAddDeleteRow && $as_customers_list->StopRec == 0) {
	$as_customers_list->StopRec = $as_customers->GridAddRowCount;
}

// Initialize aggregate
$as_customers->RowType = EW_ROWTYPE_AGGREGATEINIT;
$as_customers->ResetAttrs();
$as_customers_list->RenderRow();
while ($as_customers_list->RecCnt < $as_customers_list->StopRec) {
	$as_customers_list->RecCnt++;
	if (intval($as_customers_list->RecCnt) >= intval($as_customers_list->StartRec)) {
		$as_customers_list->RowCnt++;

		// Set up key count
		$as_customers_list->KeyCount = $as_customers_list->RowIndex;

		// Init row class and style
		$as_customers->ResetAttrs();
		$as_customers->CssClass = "";
		if ($as_customers->CurrentAction == "gridadd") {
		} else {
			$as_customers_list->LoadRowValues($as_customers_list->Recordset); // Load row values
		}
		$as_customers->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$as_customers->RowAttrs = array_merge($as_customers->RowAttrs, array('data-rowindex'=>$as_customers_list->RowCnt, 'id'=>'r' . $as_customers_list->RowCnt . '_as_customers', 'data-rowtype'=>$as_customers->RowType));

		// Render row
		$as_customers_list->RenderRow();

		// Render list options
		$as_customers_list->RenderListOptions();
?>
	<tr<?php echo $as_customers->RowAttributes() ?>>
<?php

// Render list options (body, left)
$as_customers_list->ListOptions->Render("body", "left", $as_customers_list->RowCnt);
?>
	<?php if ($as_customers->customerID->Visible) { // customerID ?>
		<td data-name="customerID"<?php echo $as_customers->customerID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_customerID" class="as_customers_customerID">
<span<?php echo $as_customers->customerID->ViewAttributes() ?>>
<?php echo $as_customers->customerID->ListViewValue() ?></span>
</span>
<a id="<?php echo $as_customers_list->PageObjName . "_row_" . $as_customers_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
		<td data-name="customerCode"<?php echo $as_customers->customerCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_customerCode" class="as_customers_customerCode">
<span<?php echo $as_customers->customerCode->ViewAttributes() ?>>
<?php echo $as_customers->customerCode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->customerName->Visible) { // customerName ?>
		<td data-name="customerName"<?php echo $as_customers->customerName->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_customerName" class="as_customers_customerName">
<span<?php echo $as_customers->customerName->ViewAttributes() ?>>
<?php echo $as_customers->customerName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
		<td data-name="contactPerson"<?php echo $as_customers->contactPerson->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_contactPerson" class="as_customers_contactPerson">
<span<?php echo $as_customers->contactPerson->ViewAttributes() ?>>
<?php echo $as_customers->contactPerson->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->address->Visible) { // address ?>
		<td data-name="address"<?php echo $as_customers->address->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_address" class="as_customers_address">
<span<?php echo $as_customers->address->ViewAttributes() ?>>
<?php echo $as_customers->address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->village->Visible) { // village ?>
		<td data-name="village"<?php echo $as_customers->village->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_village" class="as_customers_village">
<span<?php echo $as_customers->village->ViewAttributes() ?>>
<?php echo $as_customers->village->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->district->Visible) { // district ?>
		<td data-name="district"<?php echo $as_customers->district->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_district" class="as_customers_district">
<span<?php echo $as_customers->district->ViewAttributes() ?>>
<?php echo $as_customers->district->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->city->Visible) { // city ?>
		<td data-name="city"<?php echo $as_customers->city->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_city" class="as_customers_city">
<span<?php echo $as_customers->city->ViewAttributes() ?>>
<?php echo $as_customers->city->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
		<td data-name="zipCode"<?php echo $as_customers->zipCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_zipCode" class="as_customers_zipCode">
<span<?php echo $as_customers->zipCode->ViewAttributes() ?>>
<?php echo $as_customers->zipCode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->province->Visible) { // province ?>
		<td data-name="province"<?php echo $as_customers->province->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_province" class="as_customers_province">
<span<?php echo $as_customers->province->ViewAttributes() ?>>
<?php echo $as_customers->province->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->phone1->Visible) { // phone1 ?>
		<td data-name="phone1"<?php echo $as_customers->phone1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_phone1" class="as_customers_phone1">
<span<?php echo $as_customers->phone1->ViewAttributes() ?>>
<?php echo $as_customers->phone1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->phone2->Visible) { // phone2 ?>
		<td data-name="phone2"<?php echo $as_customers->phone2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_phone2" class="as_customers_phone2">
<span<?php echo $as_customers->phone2->ViewAttributes() ?>>
<?php echo $as_customers->phone2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->phone3->Visible) { // phone3 ?>
		<td data-name="phone3"<?php echo $as_customers->phone3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_phone3" class="as_customers_phone3">
<span<?php echo $as_customers->phone3->ViewAttributes() ?>>
<?php echo $as_customers->phone3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->fax1->Visible) { // fax1 ?>
		<td data-name="fax1"<?php echo $as_customers->fax1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_fax1" class="as_customers_fax1">
<span<?php echo $as_customers->fax1->ViewAttributes() ?>>
<?php echo $as_customers->fax1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->fax2->Visible) { // fax2 ?>
		<td data-name="fax2"<?php echo $as_customers->fax2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_fax2" class="as_customers_fax2">
<span<?php echo $as_customers->fax2->ViewAttributes() ?>>
<?php echo $as_customers->fax2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->fax3->Visible) { // fax3 ?>
		<td data-name="fax3"<?php echo $as_customers->fax3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_fax3" class="as_customers_fax3">
<span<?php echo $as_customers->fax3->ViewAttributes() ?>>
<?php echo $as_customers->fax3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
		<td data-name="phonecp1"<?php echo $as_customers->phonecp1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_phonecp1" class="as_customers_phonecp1">
<span<?php echo $as_customers->phonecp1->ViewAttributes() ?>>
<?php echo $as_customers->phonecp1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
		<td data-name="phonecp2"<?php echo $as_customers->phonecp2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_phonecp2" class="as_customers_phonecp2">
<span<?php echo $as_customers->phonecp2->ViewAttributes() ?>>
<?php echo $as_customers->phonecp2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $as_customers->_email->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers__email" class="as_customers__email">
<span<?php echo $as_customers->_email->ViewAttributes() ?>>
<?php echo $as_customers->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
		<td data-name="limitBalance"<?php echo $as_customers->limitBalance->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_limitBalance" class="as_customers_limitBalance">
<span<?php echo $as_customers->limitBalance->ViewAttributes() ?>>
<?php echo $as_customers->limitBalance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->balance->Visible) { // balance ?>
		<td data-name="balance"<?php echo $as_customers->balance->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_balance" class="as_customers_balance">
<span<?php echo $as_customers->balance->ViewAttributes() ?>>
<?php echo $as_customers->balance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->disc1->Visible) { // disc1 ?>
		<td data-name="disc1"<?php echo $as_customers->disc1->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_disc1" class="as_customers_disc1">
<span<?php echo $as_customers->disc1->ViewAttributes() ?>>
<?php echo $as_customers->disc1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->disc2->Visible) { // disc2 ?>
		<td data-name="disc2"<?php echo $as_customers->disc2->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_disc2" class="as_customers_disc2">
<span<?php echo $as_customers->disc2->ViewAttributes() ?>>
<?php echo $as_customers->disc2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->disc3->Visible) { // disc3 ?>
		<td data-name="disc3"<?php echo $as_customers->disc3->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_disc3" class="as_customers_disc3">
<span<?php echo $as_customers->disc3->ViewAttributes() ?>>
<?php echo $as_customers->disc3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->npwp->Visible) { // npwp ?>
		<td data-name="npwp"<?php echo $as_customers->npwp->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_npwp" class="as_customers_npwp">
<span<?php echo $as_customers->npwp->ViewAttributes() ?>>
<?php echo $as_customers->npwp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
		<td data-name="pkpName"<?php echo $as_customers->pkpName->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_pkpName" class="as_customers_pkpName">
<span<?php echo $as_customers->pkpName->ViewAttributes() ?>>
<?php echo $as_customers->pkpName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
		<td data-name="staffCode"<?php echo $as_customers->staffCode->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_staffCode" class="as_customers_staffCode">
<span<?php echo $as_customers->staffCode->ViewAttributes() ?>>
<?php echo $as_customers->staffCode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
		<td data-name="createdDate"<?php echo $as_customers->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_createdDate" class="as_customers_createdDate">
<span<?php echo $as_customers->createdDate->ViewAttributes() ?>>
<?php echo $as_customers->createdDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
		<td data-name="createdUserID"<?php echo $as_customers->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_createdUserID" class="as_customers_createdUserID">
<span<?php echo $as_customers->createdUserID->ViewAttributes() ?>>
<?php echo $as_customers->createdUserID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
		<td data-name="modifiedDate"<?php echo $as_customers->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_modifiedDate" class="as_customers_modifiedDate">
<span<?php echo $as_customers->modifiedDate->ViewAttributes() ?>>
<?php echo $as_customers->modifiedDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
		<td data-name="modifiedUserID"<?php echo $as_customers->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_customers_list->RowCnt ?>_as_customers_modifiedUserID" class="as_customers_modifiedUserID">
<span<?php echo $as_customers->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_customers->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$as_customers_list->ListOptions->Render("body", "right", $as_customers_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($as_customers->CurrentAction <> "gridadd")
		$as_customers_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($as_customers->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($as_customers_list->Recordset)
	$as_customers_list->Recordset->Close();
?>
<?php if ($as_customers->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($as_customers->CurrentAction <> "gridadd" && $as_customers->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_customers_list->Pager)) $as_customers_list->Pager = new cPrevNextPager($as_customers_list->StartRec, $as_customers_list->DisplayRecs, $as_customers_list->TotalRecs) ?>
<?php if ($as_customers_list->Pager->RecordCount > 0 && $as_customers_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_list->PageUrl() ?>start=<?php echo $as_customers_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $as_customers_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $as_customers_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $as_customers_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($as_customers_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $as_customers_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="as_customers">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($as_customers_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($as_customers_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($as_customers_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($as_customers->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_customers_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($as_customers_list->TotalRecs == 0 && $as_customers->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($as_customers_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($as_customers->Export == "") { ?>
<script type="text/javascript">
fas_customerslistsrch.FilterList = <?php echo $as_customers_list->GetFilterList() ?>;
fas_customerslistsrch.Init();
fas_customerslist.Init();
</script>
<?php } ?>
<?php
$as_customers_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($as_customers->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$as_customers_list->Page_Terminate();
?>
