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

$as_customers_view = NULL; // Initialize page object first

class cas_customers_view extends cas_customers {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_customers';

	// Page object name
	var $PageObjName = 'as_customers_view';

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
		$KeyUrl = "";
		if (@$_GET["customerID"] <> "") {
			$this->RecKey["customerID"] = $_GET["customerID"];
			$KeyUrl .= "&amp;customerID=" . urlencode($this->RecKey["customerID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
		if (@$_GET["customerID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["customerID"]);
		}

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

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["customerID"] <> "") {
				$this->customerID->setQueryStringValue($_GET["customerID"]);
				$this->RecKey["customerID"] = $this->customerID->QueryStringValue;
			} elseif (@$_POST["customerID"] <> "") {
				$this->customerID->setFormValue($_POST["customerID"]);
				$this->RecKey["customerID"] = $this->customerID->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
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
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "as_customerslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "as_customerslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$item->Body = "<button id=\"emf_as_customers\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_as_customers',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fas_customersview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

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
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
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

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_customerslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($as_customers_view)) $as_customers_view = new cas_customers_view();

// Page init
$as_customers_view->Page_Init();

// Page main
$as_customers_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_customers_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($as_customers->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fas_customersview = new ew_Form("fas_customersview", "view");

// Form_CustomValidate event
fas_customersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_customersview.ValidateRequired = true;
<?php } else { ?>
fas_customersview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($as_customers->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$as_customers_view->IsModal) { ?>
<?php if ($as_customers->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $as_customers_view->ExportOptions->Render("body") ?>
<?php
	foreach ($as_customers_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$as_customers_view->IsModal) { ?>
<?php if ($as_customers->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_customers_view->ShowPageHeader(); ?>
<?php
$as_customers_view->ShowMessage();
?>
<?php if (!$as_customers_view->IsModal) { ?>
<?php if ($as_customers->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($as_customers_view->Pager)) $as_customers_view->Pager = new cPrevNextPager($as_customers_view->StartRec, $as_customers_view->DisplayRecs, $as_customers_view->TotalRecs) ?>
<?php if ($as_customers_view->Pager->RecordCount > 0 && $as_customers_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fas_customersview" id="fas_customersview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_customers_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_customers_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_customers">
<?php if ($as_customers_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($as_customers->customerID->Visible) { // customerID ?>
	<tr id="r_customerID">
		<td><span id="elh_as_customers_customerID"><?php echo $as_customers->customerID->FldCaption() ?></span></td>
		<td data-name="customerID"<?php echo $as_customers->customerID->CellAttributes() ?>>
<span id="el_as_customers_customerID">
<span<?php echo $as_customers->customerID->ViewAttributes() ?>>
<?php echo $as_customers->customerID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->customerCode->Visible) { // customerCode ?>
	<tr id="r_customerCode">
		<td><span id="elh_as_customers_customerCode"><?php echo $as_customers->customerCode->FldCaption() ?></span></td>
		<td data-name="customerCode"<?php echo $as_customers->customerCode->CellAttributes() ?>>
<span id="el_as_customers_customerCode">
<span<?php echo $as_customers->customerCode->ViewAttributes() ?>>
<?php echo $as_customers->customerCode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->customerName->Visible) { // customerName ?>
	<tr id="r_customerName">
		<td><span id="elh_as_customers_customerName"><?php echo $as_customers->customerName->FldCaption() ?></span></td>
		<td data-name="customerName"<?php echo $as_customers->customerName->CellAttributes() ?>>
<span id="el_as_customers_customerName">
<span<?php echo $as_customers->customerName->ViewAttributes() ?>>
<?php echo $as_customers->customerName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->contactPerson->Visible) { // contactPerson ?>
	<tr id="r_contactPerson">
		<td><span id="elh_as_customers_contactPerson"><?php echo $as_customers->contactPerson->FldCaption() ?></span></td>
		<td data-name="contactPerson"<?php echo $as_customers->contactPerson->CellAttributes() ?>>
<span id="el_as_customers_contactPerson">
<span<?php echo $as_customers->contactPerson->ViewAttributes() ?>>
<?php echo $as_customers->contactPerson->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_as_customers_address"><?php echo $as_customers->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $as_customers->address->CellAttributes() ?>>
<span id="el_as_customers_address">
<span<?php echo $as_customers->address->ViewAttributes() ?>>
<?php echo $as_customers->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->address2->Visible) { // address2 ?>
	<tr id="r_address2">
		<td><span id="elh_as_customers_address2"><?php echo $as_customers->address2->FldCaption() ?></span></td>
		<td data-name="address2"<?php echo $as_customers->address2->CellAttributes() ?>>
<span id="el_as_customers_address2">
<span<?php echo $as_customers->address2->ViewAttributes() ?>>
<?php echo $as_customers->address2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->village->Visible) { // village ?>
	<tr id="r_village">
		<td><span id="elh_as_customers_village"><?php echo $as_customers->village->FldCaption() ?></span></td>
		<td data-name="village"<?php echo $as_customers->village->CellAttributes() ?>>
<span id="el_as_customers_village">
<span<?php echo $as_customers->village->ViewAttributes() ?>>
<?php echo $as_customers->village->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->district->Visible) { // district ?>
	<tr id="r_district">
		<td><span id="elh_as_customers_district"><?php echo $as_customers->district->FldCaption() ?></span></td>
		<td data-name="district"<?php echo $as_customers->district->CellAttributes() ?>>
<span id="el_as_customers_district">
<span<?php echo $as_customers->district->ViewAttributes() ?>>
<?php echo $as_customers->district->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->city->Visible) { // city ?>
	<tr id="r_city">
		<td><span id="elh_as_customers_city"><?php echo $as_customers->city->FldCaption() ?></span></td>
		<td data-name="city"<?php echo $as_customers->city->CellAttributes() ?>>
<span id="el_as_customers_city">
<span<?php echo $as_customers->city->ViewAttributes() ?>>
<?php echo $as_customers->city->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->zipCode->Visible) { // zipCode ?>
	<tr id="r_zipCode">
		<td><span id="elh_as_customers_zipCode"><?php echo $as_customers->zipCode->FldCaption() ?></span></td>
		<td data-name="zipCode"<?php echo $as_customers->zipCode->CellAttributes() ?>>
<span id="el_as_customers_zipCode">
<span<?php echo $as_customers->zipCode->ViewAttributes() ?>>
<?php echo $as_customers->zipCode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->province->Visible) { // province ?>
	<tr id="r_province">
		<td><span id="elh_as_customers_province"><?php echo $as_customers->province->FldCaption() ?></span></td>
		<td data-name="province"<?php echo $as_customers->province->CellAttributes() ?>>
<span id="el_as_customers_province">
<span<?php echo $as_customers->province->ViewAttributes() ?>>
<?php echo $as_customers->province->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->phone1->Visible) { // phone1 ?>
	<tr id="r_phone1">
		<td><span id="elh_as_customers_phone1"><?php echo $as_customers->phone1->FldCaption() ?></span></td>
		<td data-name="phone1"<?php echo $as_customers->phone1->CellAttributes() ?>>
<span id="el_as_customers_phone1">
<span<?php echo $as_customers->phone1->ViewAttributes() ?>>
<?php echo $as_customers->phone1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->phone2->Visible) { // phone2 ?>
	<tr id="r_phone2">
		<td><span id="elh_as_customers_phone2"><?php echo $as_customers->phone2->FldCaption() ?></span></td>
		<td data-name="phone2"<?php echo $as_customers->phone2->CellAttributes() ?>>
<span id="el_as_customers_phone2">
<span<?php echo $as_customers->phone2->ViewAttributes() ?>>
<?php echo $as_customers->phone2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->phone3->Visible) { // phone3 ?>
	<tr id="r_phone3">
		<td><span id="elh_as_customers_phone3"><?php echo $as_customers->phone3->FldCaption() ?></span></td>
		<td data-name="phone3"<?php echo $as_customers->phone3->CellAttributes() ?>>
<span id="el_as_customers_phone3">
<span<?php echo $as_customers->phone3->ViewAttributes() ?>>
<?php echo $as_customers->phone3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->fax1->Visible) { // fax1 ?>
	<tr id="r_fax1">
		<td><span id="elh_as_customers_fax1"><?php echo $as_customers->fax1->FldCaption() ?></span></td>
		<td data-name="fax1"<?php echo $as_customers->fax1->CellAttributes() ?>>
<span id="el_as_customers_fax1">
<span<?php echo $as_customers->fax1->ViewAttributes() ?>>
<?php echo $as_customers->fax1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->fax2->Visible) { // fax2 ?>
	<tr id="r_fax2">
		<td><span id="elh_as_customers_fax2"><?php echo $as_customers->fax2->FldCaption() ?></span></td>
		<td data-name="fax2"<?php echo $as_customers->fax2->CellAttributes() ?>>
<span id="el_as_customers_fax2">
<span<?php echo $as_customers->fax2->ViewAttributes() ?>>
<?php echo $as_customers->fax2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->fax3->Visible) { // fax3 ?>
	<tr id="r_fax3">
		<td><span id="elh_as_customers_fax3"><?php echo $as_customers->fax3->FldCaption() ?></span></td>
		<td data-name="fax3"<?php echo $as_customers->fax3->CellAttributes() ?>>
<span id="el_as_customers_fax3">
<span<?php echo $as_customers->fax3->ViewAttributes() ?>>
<?php echo $as_customers->fax3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->phonecp1->Visible) { // phonecp1 ?>
	<tr id="r_phonecp1">
		<td><span id="elh_as_customers_phonecp1"><?php echo $as_customers->phonecp1->FldCaption() ?></span></td>
		<td data-name="phonecp1"<?php echo $as_customers->phonecp1->CellAttributes() ?>>
<span id="el_as_customers_phonecp1">
<span<?php echo $as_customers->phonecp1->ViewAttributes() ?>>
<?php echo $as_customers->phonecp1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->phonecp2->Visible) { // phonecp2 ?>
	<tr id="r_phonecp2">
		<td><span id="elh_as_customers_phonecp2"><?php echo $as_customers->phonecp2->FldCaption() ?></span></td>
		<td data-name="phonecp2"<?php echo $as_customers->phonecp2->CellAttributes() ?>>
<span id="el_as_customers_phonecp2">
<span<?php echo $as_customers->phonecp2->ViewAttributes() ?>>
<?php echo $as_customers->phonecp2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_as_customers__email"><?php echo $as_customers->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $as_customers->_email->CellAttributes() ?>>
<span id="el_as_customers__email">
<span<?php echo $as_customers->_email->ViewAttributes() ?>>
<?php echo $as_customers->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->limitBalance->Visible) { // limitBalance ?>
	<tr id="r_limitBalance">
		<td><span id="elh_as_customers_limitBalance"><?php echo $as_customers->limitBalance->FldCaption() ?></span></td>
		<td data-name="limitBalance"<?php echo $as_customers->limitBalance->CellAttributes() ?>>
<span id="el_as_customers_limitBalance">
<span<?php echo $as_customers->limitBalance->ViewAttributes() ?>>
<?php echo $as_customers->limitBalance->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->balance->Visible) { // balance ?>
	<tr id="r_balance">
		<td><span id="elh_as_customers_balance"><?php echo $as_customers->balance->FldCaption() ?></span></td>
		<td data-name="balance"<?php echo $as_customers->balance->CellAttributes() ?>>
<span id="el_as_customers_balance">
<span<?php echo $as_customers->balance->ViewAttributes() ?>>
<?php echo $as_customers->balance->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->disc1->Visible) { // disc1 ?>
	<tr id="r_disc1">
		<td><span id="elh_as_customers_disc1"><?php echo $as_customers->disc1->FldCaption() ?></span></td>
		<td data-name="disc1"<?php echo $as_customers->disc1->CellAttributes() ?>>
<span id="el_as_customers_disc1">
<span<?php echo $as_customers->disc1->ViewAttributes() ?>>
<?php echo $as_customers->disc1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->disc2->Visible) { // disc2 ?>
	<tr id="r_disc2">
		<td><span id="elh_as_customers_disc2"><?php echo $as_customers->disc2->FldCaption() ?></span></td>
		<td data-name="disc2"<?php echo $as_customers->disc2->CellAttributes() ?>>
<span id="el_as_customers_disc2">
<span<?php echo $as_customers->disc2->ViewAttributes() ?>>
<?php echo $as_customers->disc2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->disc3->Visible) { // disc3 ?>
	<tr id="r_disc3">
		<td><span id="elh_as_customers_disc3"><?php echo $as_customers->disc3->FldCaption() ?></span></td>
		<td data-name="disc3"<?php echo $as_customers->disc3->CellAttributes() ?>>
<span id="el_as_customers_disc3">
<span<?php echo $as_customers->disc3->ViewAttributes() ?>>
<?php echo $as_customers->disc3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->note->Visible) { // note ?>
	<tr id="r_note">
		<td><span id="elh_as_customers_note"><?php echo $as_customers->note->FldCaption() ?></span></td>
		<td data-name="note"<?php echo $as_customers->note->CellAttributes() ?>>
<span id="el_as_customers_note">
<span<?php echo $as_customers->note->ViewAttributes() ?>>
<?php echo $as_customers->note->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->npwp->Visible) { // npwp ?>
	<tr id="r_npwp">
		<td><span id="elh_as_customers_npwp"><?php echo $as_customers->npwp->FldCaption() ?></span></td>
		<td data-name="npwp"<?php echo $as_customers->npwp->CellAttributes() ?>>
<span id="el_as_customers_npwp">
<span<?php echo $as_customers->npwp->ViewAttributes() ?>>
<?php echo $as_customers->npwp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->pkpName->Visible) { // pkpName ?>
	<tr id="r_pkpName">
		<td><span id="elh_as_customers_pkpName"><?php echo $as_customers->pkpName->FldCaption() ?></span></td>
		<td data-name="pkpName"<?php echo $as_customers->pkpName->CellAttributes() ?>>
<span id="el_as_customers_pkpName">
<span<?php echo $as_customers->pkpName->ViewAttributes() ?>>
<?php echo $as_customers->pkpName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->staffCode->Visible) { // staffCode ?>
	<tr id="r_staffCode">
		<td><span id="elh_as_customers_staffCode"><?php echo $as_customers->staffCode->FldCaption() ?></span></td>
		<td data-name="staffCode"<?php echo $as_customers->staffCode->CellAttributes() ?>>
<span id="el_as_customers_staffCode">
<span<?php echo $as_customers->staffCode->ViewAttributes() ?>>
<?php echo $as_customers->staffCode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->createdDate->Visible) { // createdDate ?>
	<tr id="r_createdDate">
		<td><span id="elh_as_customers_createdDate"><?php echo $as_customers->createdDate->FldCaption() ?></span></td>
		<td data-name="createdDate"<?php echo $as_customers->createdDate->CellAttributes() ?>>
<span id="el_as_customers_createdDate">
<span<?php echo $as_customers->createdDate->ViewAttributes() ?>>
<?php echo $as_customers->createdDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->createdUserID->Visible) { // createdUserID ?>
	<tr id="r_createdUserID">
		<td><span id="elh_as_customers_createdUserID"><?php echo $as_customers->createdUserID->FldCaption() ?></span></td>
		<td data-name="createdUserID"<?php echo $as_customers->createdUserID->CellAttributes() ?>>
<span id="el_as_customers_createdUserID">
<span<?php echo $as_customers->createdUserID->ViewAttributes() ?>>
<?php echo $as_customers->createdUserID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->modifiedDate->Visible) { // modifiedDate ?>
	<tr id="r_modifiedDate">
		<td><span id="elh_as_customers_modifiedDate"><?php echo $as_customers->modifiedDate->FldCaption() ?></span></td>
		<td data-name="modifiedDate"<?php echo $as_customers->modifiedDate->CellAttributes() ?>>
<span id="el_as_customers_modifiedDate">
<span<?php echo $as_customers->modifiedDate->ViewAttributes() ?>>
<?php echo $as_customers->modifiedDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($as_customers->modifiedUserID->Visible) { // modifiedUserID ?>
	<tr id="r_modifiedUserID">
		<td><span id="elh_as_customers_modifiedUserID"><?php echo $as_customers->modifiedUserID->FldCaption() ?></span></td>
		<td data-name="modifiedUserID"<?php echo $as_customers->modifiedUserID->CellAttributes() ?>>
<span id="el_as_customers_modifiedUserID">
<span<?php echo $as_customers->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_customers->modifiedUserID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if (!$as_customers_view->IsModal) { ?>
<?php if ($as_customers->Export == "") { ?>
<?php if (!isset($as_customers_view->Pager)) $as_customers_view->Pager = new cPrevNextPager($as_customers_view->StartRec, $as_customers_view->DisplayRecs, $as_customers_view->TotalRecs) ?>
<?php if ($as_customers_view->Pager->RecordCount > 0 && $as_customers_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($as_customers_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($as_customers_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $as_customers_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($as_customers_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($as_customers_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $as_customers_view->PageUrl() ?>start=<?php echo $as_customers_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $as_customers_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
<?php } ?>
</form>
<?php if ($as_customers->Export == "") { ?>
<script type="text/javascript">
fas_customersview.Init();
</script>
<?php } ?>
<?php
$as_customers_view->ShowPageFooter();
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
$as_customers_view->Page_Terminate();
?>
