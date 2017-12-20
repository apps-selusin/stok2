<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "as_productsinfo.php" ?>
<?php include_once "t_96_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$as_products_add = NULL; // Initialize page object first

class cas_products_add extends cas_products {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_products';

	// Page object name
	var $PageObjName = 'as_products_add';

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

		// Table object (as_products)
		if (!isset($GLOBALS["as_products"]) || get_class($GLOBALS["as_products"]) == "cas_products") {
			$GLOBALS["as_products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["as_products"];
		}

		// Table object (t_96_user)
		if (!isset($GLOBALS['t_96_user'])) $GLOBALS['t_96_user'] = new ct_96_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'as_products', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("as_productslist.php"));
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
		$this->productCode->SetVisibility();
		$this->productName->SetVisibility();
		$this->categoryID->SetVisibility();
		$this->brandID->SetVisibility();
		$this->unit->SetVisibility();
		$this->unitPrice1->SetVisibility();
		$this->unitPrice2->SetVisibility();
		$this->unitPrice3->SetVisibility();
		$this->hpp->SetVisibility();
		$this->purchasePrice->SetVisibility();
		$this->note->SetVisibility();
		$this->stockAmount->SetVisibility();
		$this->image->SetVisibility();
		$this->minimumStock->SetVisibility();
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
		global $EW_EXPORT, $as_products;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($as_products);
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
			if (@$_GET["productID"] != "") {
				$this->productID->setQueryStringValue($_GET["productID"]);
				$this->setKey("productID", $this->productID->CurrentValue); // Set up key
			} else {
				$this->setKey("productID", ""); // Clear key
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
					$this->Page_Terminate("as_productslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "as_productslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "as_productsview.php")
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
		$this->productCode->CurrentValue = NULL;
		$this->productCode->OldValue = $this->productCode->CurrentValue;
		$this->productName->CurrentValue = NULL;
		$this->productName->OldValue = $this->productName->CurrentValue;
		$this->categoryID->CurrentValue = NULL;
		$this->categoryID->OldValue = $this->categoryID->CurrentValue;
		$this->brandID->CurrentValue = NULL;
		$this->brandID->OldValue = $this->brandID->CurrentValue;
		$this->unit->CurrentValue = NULL;
		$this->unit->OldValue = $this->unit->CurrentValue;
		$this->unitPrice1->CurrentValue = NULL;
		$this->unitPrice1->OldValue = $this->unitPrice1->CurrentValue;
		$this->unitPrice2->CurrentValue = NULL;
		$this->unitPrice2->OldValue = $this->unitPrice2->CurrentValue;
		$this->unitPrice3->CurrentValue = NULL;
		$this->unitPrice3->OldValue = $this->unitPrice3->CurrentValue;
		$this->hpp->CurrentValue = NULL;
		$this->hpp->OldValue = $this->hpp->CurrentValue;
		$this->purchasePrice->CurrentValue = NULL;
		$this->purchasePrice->OldValue = $this->purchasePrice->CurrentValue;
		$this->note->CurrentValue = NULL;
		$this->note->OldValue = $this->note->CurrentValue;
		$this->stockAmount->CurrentValue = NULL;
		$this->stockAmount->OldValue = $this->stockAmount->CurrentValue;
		$this->image->CurrentValue = NULL;
		$this->image->OldValue = $this->image->CurrentValue;
		$this->minimumStock->CurrentValue = NULL;
		$this->minimumStock->OldValue = $this->minimumStock->CurrentValue;
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
		if (!$this->productCode->FldIsDetailKey) {
			$this->productCode->setFormValue($objForm->GetValue("x_productCode"));
		}
		if (!$this->productName->FldIsDetailKey) {
			$this->productName->setFormValue($objForm->GetValue("x_productName"));
		}
		if (!$this->categoryID->FldIsDetailKey) {
			$this->categoryID->setFormValue($objForm->GetValue("x_categoryID"));
		}
		if (!$this->brandID->FldIsDetailKey) {
			$this->brandID->setFormValue($objForm->GetValue("x_brandID"));
		}
		if (!$this->unit->FldIsDetailKey) {
			$this->unit->setFormValue($objForm->GetValue("x_unit"));
		}
		if (!$this->unitPrice1->FldIsDetailKey) {
			$this->unitPrice1->setFormValue($objForm->GetValue("x_unitPrice1"));
		}
		if (!$this->unitPrice2->FldIsDetailKey) {
			$this->unitPrice2->setFormValue($objForm->GetValue("x_unitPrice2"));
		}
		if (!$this->unitPrice3->FldIsDetailKey) {
			$this->unitPrice3->setFormValue($objForm->GetValue("x_unitPrice3"));
		}
		if (!$this->hpp->FldIsDetailKey) {
			$this->hpp->setFormValue($objForm->GetValue("x_hpp"));
		}
		if (!$this->purchasePrice->FldIsDetailKey) {
			$this->purchasePrice->setFormValue($objForm->GetValue("x_purchasePrice"));
		}
		if (!$this->note->FldIsDetailKey) {
			$this->note->setFormValue($objForm->GetValue("x_note"));
		}
		if (!$this->stockAmount->FldIsDetailKey) {
			$this->stockAmount->setFormValue($objForm->GetValue("x_stockAmount"));
		}
		if (!$this->image->FldIsDetailKey) {
			$this->image->setFormValue($objForm->GetValue("x_image"));
		}
		if (!$this->minimumStock->FldIsDetailKey) {
			$this->minimumStock->setFormValue($objForm->GetValue("x_minimumStock"));
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
		$this->productCode->CurrentValue = $this->productCode->FormValue;
		$this->productName->CurrentValue = $this->productName->FormValue;
		$this->categoryID->CurrentValue = $this->categoryID->FormValue;
		$this->brandID->CurrentValue = $this->brandID->FormValue;
		$this->unit->CurrentValue = $this->unit->FormValue;
		$this->unitPrice1->CurrentValue = $this->unitPrice1->FormValue;
		$this->unitPrice2->CurrentValue = $this->unitPrice2->FormValue;
		$this->unitPrice3->CurrentValue = $this->unitPrice3->FormValue;
		$this->hpp->CurrentValue = $this->hpp->FormValue;
		$this->purchasePrice->CurrentValue = $this->purchasePrice->FormValue;
		$this->note->CurrentValue = $this->note->FormValue;
		$this->stockAmount->CurrentValue = $this->stockAmount->FormValue;
		$this->image->CurrentValue = $this->image->FormValue;
		$this->minimumStock->CurrentValue = $this->minimumStock->FormValue;
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
		$this->productID->setDbValue($rs->fields('productID'));
		$this->productCode->setDbValue($rs->fields('productCode'));
		$this->productName->setDbValue($rs->fields('productName'));
		$this->categoryID->setDbValue($rs->fields('categoryID'));
		$this->brandID->setDbValue($rs->fields('brandID'));
		$this->unit->setDbValue($rs->fields('unit'));
		$this->unitPrice1->setDbValue($rs->fields('unitPrice1'));
		$this->unitPrice2->setDbValue($rs->fields('unitPrice2'));
		$this->unitPrice3->setDbValue($rs->fields('unitPrice3'));
		$this->hpp->setDbValue($rs->fields('hpp'));
		$this->purchasePrice->setDbValue($rs->fields('purchasePrice'));
		$this->note->setDbValue($rs->fields('note'));
		$this->stockAmount->setDbValue($rs->fields('stockAmount'));
		$this->image->setDbValue($rs->fields('image'));
		$this->minimumStock->setDbValue($rs->fields('minimumStock'));
		$this->createdDate->setDbValue($rs->fields('createdDate'));
		$this->createdUserID->setDbValue($rs->fields('createdUserID'));
		$this->modifiedDate->setDbValue($rs->fields('modifiedDate'));
		$this->modifiedUserID->setDbValue($rs->fields('modifiedUserID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->productID->DbValue = $row['productID'];
		$this->productCode->DbValue = $row['productCode'];
		$this->productName->DbValue = $row['productName'];
		$this->categoryID->DbValue = $row['categoryID'];
		$this->brandID->DbValue = $row['brandID'];
		$this->unit->DbValue = $row['unit'];
		$this->unitPrice1->DbValue = $row['unitPrice1'];
		$this->unitPrice2->DbValue = $row['unitPrice2'];
		$this->unitPrice3->DbValue = $row['unitPrice3'];
		$this->hpp->DbValue = $row['hpp'];
		$this->purchasePrice->DbValue = $row['purchasePrice'];
		$this->note->DbValue = $row['note'];
		$this->stockAmount->DbValue = $row['stockAmount'];
		$this->image->DbValue = $row['image'];
		$this->minimumStock->DbValue = $row['minimumStock'];
		$this->createdDate->DbValue = $row['createdDate'];
		$this->createdUserID->DbValue = $row['createdUserID'];
		$this->modifiedDate->DbValue = $row['modifiedDate'];
		$this->modifiedUserID->DbValue = $row['modifiedUserID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("productID")) <> "")
			$this->productID->CurrentValue = $this->getKey("productID"); // productID
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

		if ($this->unitPrice1->FormValue == $this->unitPrice1->CurrentValue && is_numeric(ew_StrToFloat($this->unitPrice1->CurrentValue)))
			$this->unitPrice1->CurrentValue = ew_StrToFloat($this->unitPrice1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->unitPrice2->FormValue == $this->unitPrice2->CurrentValue && is_numeric(ew_StrToFloat($this->unitPrice2->CurrentValue)))
			$this->unitPrice2->CurrentValue = ew_StrToFloat($this->unitPrice2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->unitPrice3->FormValue == $this->unitPrice3->CurrentValue && is_numeric(ew_StrToFloat($this->unitPrice3->CurrentValue)))
			$this->unitPrice3->CurrentValue = ew_StrToFloat($this->unitPrice3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->hpp->FormValue == $this->hpp->CurrentValue && is_numeric(ew_StrToFloat($this->hpp->CurrentValue)))
			$this->hpp->CurrentValue = ew_StrToFloat($this->hpp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->purchasePrice->FormValue == $this->purchasePrice->CurrentValue && is_numeric(ew_StrToFloat($this->purchasePrice->CurrentValue)))
			$this->purchasePrice->CurrentValue = ew_StrToFloat($this->purchasePrice->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// productID
		// productCode
		// productName
		// categoryID
		// brandID
		// unit
		// unitPrice1
		// unitPrice2
		// unitPrice3
		// hpp
		// purchasePrice
		// note
		// stockAmount
		// image
		// minimumStock
		// createdDate
		// createdUserID
		// modifiedDate
		// modifiedUserID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// productID
		$this->productID->ViewValue = $this->productID->CurrentValue;
		$this->productID->ViewCustomAttributes = "";

		// productCode
		$this->productCode->ViewValue = $this->productCode->CurrentValue;
		$this->productCode->ViewCustomAttributes = "";

		// productName
		$this->productName->ViewValue = $this->productName->CurrentValue;
		$this->productName->ViewCustomAttributes = "";

		// categoryID
		$this->categoryID->ViewValue = $this->categoryID->CurrentValue;
		$this->categoryID->ViewCustomAttributes = "";

		// brandID
		$this->brandID->ViewValue = $this->brandID->CurrentValue;
		$this->brandID->ViewCustomAttributes = "";

		// unit
		$this->unit->ViewValue = $this->unit->CurrentValue;
		$this->unit->ViewCustomAttributes = "";

		// unitPrice1
		$this->unitPrice1->ViewValue = $this->unitPrice1->CurrentValue;
		$this->unitPrice1->ViewCustomAttributes = "";

		// unitPrice2
		$this->unitPrice2->ViewValue = $this->unitPrice2->CurrentValue;
		$this->unitPrice2->ViewCustomAttributes = "";

		// unitPrice3
		$this->unitPrice3->ViewValue = $this->unitPrice3->CurrentValue;
		$this->unitPrice3->ViewCustomAttributes = "";

		// hpp
		$this->hpp->ViewValue = $this->hpp->CurrentValue;
		$this->hpp->ViewCustomAttributes = "";

		// purchasePrice
		$this->purchasePrice->ViewValue = $this->purchasePrice->CurrentValue;
		$this->purchasePrice->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// stockAmount
		$this->stockAmount->ViewValue = $this->stockAmount->CurrentValue;
		$this->stockAmount->ViewCustomAttributes = "";

		// image
		$this->image->ViewValue = $this->image->CurrentValue;
		$this->image->ViewCustomAttributes = "";

		// minimumStock
		$this->minimumStock->ViewValue = $this->minimumStock->CurrentValue;
		$this->minimumStock->ViewCustomAttributes = "";

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

			// productCode
			$this->productCode->LinkCustomAttributes = "";
			$this->productCode->HrefValue = "";
			$this->productCode->TooltipValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";
			$this->productName->TooltipValue = "";

			// categoryID
			$this->categoryID->LinkCustomAttributes = "";
			$this->categoryID->HrefValue = "";
			$this->categoryID->TooltipValue = "";

			// brandID
			$this->brandID->LinkCustomAttributes = "";
			$this->brandID->HrefValue = "";
			$this->brandID->TooltipValue = "";

			// unit
			$this->unit->LinkCustomAttributes = "";
			$this->unit->HrefValue = "";
			$this->unit->TooltipValue = "";

			// unitPrice1
			$this->unitPrice1->LinkCustomAttributes = "";
			$this->unitPrice1->HrefValue = "";
			$this->unitPrice1->TooltipValue = "";

			// unitPrice2
			$this->unitPrice2->LinkCustomAttributes = "";
			$this->unitPrice2->HrefValue = "";
			$this->unitPrice2->TooltipValue = "";

			// unitPrice3
			$this->unitPrice3->LinkCustomAttributes = "";
			$this->unitPrice3->HrefValue = "";
			$this->unitPrice3->TooltipValue = "";

			// hpp
			$this->hpp->LinkCustomAttributes = "";
			$this->hpp->HrefValue = "";
			$this->hpp->TooltipValue = "";

			// purchasePrice
			$this->purchasePrice->LinkCustomAttributes = "";
			$this->purchasePrice->HrefValue = "";
			$this->purchasePrice->TooltipValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";
			$this->note->TooltipValue = "";

			// stockAmount
			$this->stockAmount->LinkCustomAttributes = "";
			$this->stockAmount->HrefValue = "";
			$this->stockAmount->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";
			$this->image->TooltipValue = "";

			// minimumStock
			$this->minimumStock->LinkCustomAttributes = "";
			$this->minimumStock->HrefValue = "";
			$this->minimumStock->TooltipValue = "";

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

			// productCode
			$this->productCode->EditAttrs["class"] = "form-control";
			$this->productCode->EditCustomAttributes = "";
			$this->productCode->EditValue = ew_HtmlEncode($this->productCode->CurrentValue);
			$this->productCode->PlaceHolder = ew_RemoveHtml($this->productCode->FldCaption());

			// productName
			$this->productName->EditAttrs["class"] = "form-control";
			$this->productName->EditCustomAttributes = "";
			$this->productName->EditValue = ew_HtmlEncode($this->productName->CurrentValue);
			$this->productName->PlaceHolder = ew_RemoveHtml($this->productName->FldCaption());

			// categoryID
			$this->categoryID->EditAttrs["class"] = "form-control";
			$this->categoryID->EditCustomAttributes = "";
			$this->categoryID->EditValue = ew_HtmlEncode($this->categoryID->CurrentValue);
			$this->categoryID->PlaceHolder = ew_RemoveHtml($this->categoryID->FldCaption());

			// brandID
			$this->brandID->EditAttrs["class"] = "form-control";
			$this->brandID->EditCustomAttributes = "";
			$this->brandID->EditValue = ew_HtmlEncode($this->brandID->CurrentValue);
			$this->brandID->PlaceHolder = ew_RemoveHtml($this->brandID->FldCaption());

			// unit
			$this->unit->EditAttrs["class"] = "form-control";
			$this->unit->EditCustomAttributes = "";
			$this->unit->EditValue = ew_HtmlEncode($this->unit->CurrentValue);
			$this->unit->PlaceHolder = ew_RemoveHtml($this->unit->FldCaption());

			// unitPrice1
			$this->unitPrice1->EditAttrs["class"] = "form-control";
			$this->unitPrice1->EditCustomAttributes = "";
			$this->unitPrice1->EditValue = ew_HtmlEncode($this->unitPrice1->CurrentValue);
			$this->unitPrice1->PlaceHolder = ew_RemoveHtml($this->unitPrice1->FldCaption());
			if (strval($this->unitPrice1->EditValue) <> "" && is_numeric($this->unitPrice1->EditValue)) $this->unitPrice1->EditValue = ew_FormatNumber($this->unitPrice1->EditValue, -2, -1, -2, 0);

			// unitPrice2
			$this->unitPrice2->EditAttrs["class"] = "form-control";
			$this->unitPrice2->EditCustomAttributes = "";
			$this->unitPrice2->EditValue = ew_HtmlEncode($this->unitPrice2->CurrentValue);
			$this->unitPrice2->PlaceHolder = ew_RemoveHtml($this->unitPrice2->FldCaption());
			if (strval($this->unitPrice2->EditValue) <> "" && is_numeric($this->unitPrice2->EditValue)) $this->unitPrice2->EditValue = ew_FormatNumber($this->unitPrice2->EditValue, -2, -1, -2, 0);

			// unitPrice3
			$this->unitPrice3->EditAttrs["class"] = "form-control";
			$this->unitPrice3->EditCustomAttributes = "";
			$this->unitPrice3->EditValue = ew_HtmlEncode($this->unitPrice3->CurrentValue);
			$this->unitPrice3->PlaceHolder = ew_RemoveHtml($this->unitPrice3->FldCaption());
			if (strval($this->unitPrice3->EditValue) <> "" && is_numeric($this->unitPrice3->EditValue)) $this->unitPrice3->EditValue = ew_FormatNumber($this->unitPrice3->EditValue, -2, -1, -2, 0);

			// hpp
			$this->hpp->EditAttrs["class"] = "form-control";
			$this->hpp->EditCustomAttributes = "";
			$this->hpp->EditValue = ew_HtmlEncode($this->hpp->CurrentValue);
			$this->hpp->PlaceHolder = ew_RemoveHtml($this->hpp->FldCaption());
			if (strval($this->hpp->EditValue) <> "" && is_numeric($this->hpp->EditValue)) $this->hpp->EditValue = ew_FormatNumber($this->hpp->EditValue, -2, -1, -2, 0);

			// purchasePrice
			$this->purchasePrice->EditAttrs["class"] = "form-control";
			$this->purchasePrice->EditCustomAttributes = "";
			$this->purchasePrice->EditValue = ew_HtmlEncode($this->purchasePrice->CurrentValue);
			$this->purchasePrice->PlaceHolder = ew_RemoveHtml($this->purchasePrice->FldCaption());
			if (strval($this->purchasePrice->EditValue) <> "" && is_numeric($this->purchasePrice->EditValue)) $this->purchasePrice->EditValue = ew_FormatNumber($this->purchasePrice->EditValue, -2, -1, -2, 0);

			// note
			$this->note->EditAttrs["class"] = "form-control";
			$this->note->EditCustomAttributes = "";
			$this->note->EditValue = ew_HtmlEncode($this->note->CurrentValue);
			$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

			// stockAmount
			$this->stockAmount->EditAttrs["class"] = "form-control";
			$this->stockAmount->EditCustomAttributes = "";
			$this->stockAmount->EditValue = ew_HtmlEncode($this->stockAmount->CurrentValue);
			$this->stockAmount->PlaceHolder = ew_RemoveHtml($this->stockAmount->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->EditValue = ew_HtmlEncode($this->image->CurrentValue);
			$this->image->PlaceHolder = ew_RemoveHtml($this->image->FldCaption());

			// minimumStock
			$this->minimumStock->EditAttrs["class"] = "form-control";
			$this->minimumStock->EditCustomAttributes = "";
			$this->minimumStock->EditValue = ew_HtmlEncode($this->minimumStock->CurrentValue);
			$this->minimumStock->PlaceHolder = ew_RemoveHtml($this->minimumStock->FldCaption());

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
			// productCode

			$this->productCode->LinkCustomAttributes = "";
			$this->productCode->HrefValue = "";

			// productName
			$this->productName->LinkCustomAttributes = "";
			$this->productName->HrefValue = "";

			// categoryID
			$this->categoryID->LinkCustomAttributes = "";
			$this->categoryID->HrefValue = "";

			// brandID
			$this->brandID->LinkCustomAttributes = "";
			$this->brandID->HrefValue = "";

			// unit
			$this->unit->LinkCustomAttributes = "";
			$this->unit->HrefValue = "";

			// unitPrice1
			$this->unitPrice1->LinkCustomAttributes = "";
			$this->unitPrice1->HrefValue = "";

			// unitPrice2
			$this->unitPrice2->LinkCustomAttributes = "";
			$this->unitPrice2->HrefValue = "";

			// unitPrice3
			$this->unitPrice3->LinkCustomAttributes = "";
			$this->unitPrice3->HrefValue = "";

			// hpp
			$this->hpp->LinkCustomAttributes = "";
			$this->hpp->HrefValue = "";

			// purchasePrice
			$this->purchasePrice->LinkCustomAttributes = "";
			$this->purchasePrice->HrefValue = "";

			// note
			$this->note->LinkCustomAttributes = "";
			$this->note->HrefValue = "";

			// stockAmount
			$this->stockAmount->LinkCustomAttributes = "";
			$this->stockAmount->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->HrefValue = "";

			// minimumStock
			$this->minimumStock->LinkCustomAttributes = "";
			$this->minimumStock->HrefValue = "";

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
		if (!$this->productCode->FldIsDetailKey && !is_null($this->productCode->FormValue) && $this->productCode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->productCode->FldCaption(), $this->productCode->ReqErrMsg));
		}
		if (!$this->productName->FldIsDetailKey && !is_null($this->productName->FormValue) && $this->productName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->productName->FldCaption(), $this->productName->ReqErrMsg));
		}
		if (!$this->categoryID->FldIsDetailKey && !is_null($this->categoryID->FormValue) && $this->categoryID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->categoryID->FldCaption(), $this->categoryID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->categoryID->FormValue)) {
			ew_AddMessage($gsFormError, $this->categoryID->FldErrMsg());
		}
		if (!$this->brandID->FldIsDetailKey && !is_null($this->brandID->FormValue) && $this->brandID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->brandID->FldCaption(), $this->brandID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->brandID->FormValue)) {
			ew_AddMessage($gsFormError, $this->brandID->FldErrMsg());
		}
		if (!$this->unit->FldIsDetailKey && !is_null($this->unit->FormValue) && $this->unit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unit->FldCaption(), $this->unit->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->unit->FormValue)) {
			ew_AddMessage($gsFormError, $this->unit->FldErrMsg());
		}
		if (!$this->unitPrice1->FldIsDetailKey && !is_null($this->unitPrice1->FormValue) && $this->unitPrice1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unitPrice1->FldCaption(), $this->unitPrice1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->unitPrice1->FormValue)) {
			ew_AddMessage($gsFormError, $this->unitPrice1->FldErrMsg());
		}
		if (!$this->unitPrice2->FldIsDetailKey && !is_null($this->unitPrice2->FormValue) && $this->unitPrice2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unitPrice2->FldCaption(), $this->unitPrice2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->unitPrice2->FormValue)) {
			ew_AddMessage($gsFormError, $this->unitPrice2->FldErrMsg());
		}
		if (!$this->unitPrice3->FldIsDetailKey && !is_null($this->unitPrice3->FormValue) && $this->unitPrice3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unitPrice3->FldCaption(), $this->unitPrice3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->unitPrice3->FormValue)) {
			ew_AddMessage($gsFormError, $this->unitPrice3->FldErrMsg());
		}
		if (!$this->hpp->FldIsDetailKey && !is_null($this->hpp->FormValue) && $this->hpp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hpp->FldCaption(), $this->hpp->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->hpp->FormValue)) {
			ew_AddMessage($gsFormError, $this->hpp->FldErrMsg());
		}
		if (!$this->purchasePrice->FldIsDetailKey && !is_null($this->purchasePrice->FormValue) && $this->purchasePrice->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->purchasePrice->FldCaption(), $this->purchasePrice->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->purchasePrice->FormValue)) {
			ew_AddMessage($gsFormError, $this->purchasePrice->FldErrMsg());
		}
		if (!$this->note->FldIsDetailKey && !is_null($this->note->FormValue) && $this->note->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->note->FldCaption(), $this->note->ReqErrMsg));
		}
		if (!$this->stockAmount->FldIsDetailKey && !is_null($this->stockAmount->FormValue) && $this->stockAmount->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stockAmount->FldCaption(), $this->stockAmount->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->stockAmount->FormValue)) {
			ew_AddMessage($gsFormError, $this->stockAmount->FldErrMsg());
		}
		if (!$this->image->FldIsDetailKey && !is_null($this->image->FormValue) && $this->image->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
		}
		if (!$this->minimumStock->FldIsDetailKey && !is_null($this->minimumStock->FormValue) && $this->minimumStock->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->minimumStock->FldCaption(), $this->minimumStock->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->minimumStock->FormValue)) {
			ew_AddMessage($gsFormError, $this->minimumStock->FldErrMsg());
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

		// productCode
		$this->productCode->SetDbValueDef($rsnew, $this->productCode->CurrentValue, "", FALSE);

		// productName
		$this->productName->SetDbValueDef($rsnew, $this->productName->CurrentValue, "", FALSE);

		// categoryID
		$this->categoryID->SetDbValueDef($rsnew, $this->categoryID->CurrentValue, 0, FALSE);

		// brandID
		$this->brandID->SetDbValueDef($rsnew, $this->brandID->CurrentValue, 0, FALSE);

		// unit
		$this->unit->SetDbValueDef($rsnew, $this->unit->CurrentValue, 0, FALSE);

		// unitPrice1
		$this->unitPrice1->SetDbValueDef($rsnew, $this->unitPrice1->CurrentValue, 0, FALSE);

		// unitPrice2
		$this->unitPrice2->SetDbValueDef($rsnew, $this->unitPrice2->CurrentValue, 0, FALSE);

		// unitPrice3
		$this->unitPrice3->SetDbValueDef($rsnew, $this->unitPrice3->CurrentValue, 0, FALSE);

		// hpp
		$this->hpp->SetDbValueDef($rsnew, $this->hpp->CurrentValue, 0, FALSE);

		// purchasePrice
		$this->purchasePrice->SetDbValueDef($rsnew, $this->purchasePrice->CurrentValue, 0, FALSE);

		// note
		$this->note->SetDbValueDef($rsnew, $this->note->CurrentValue, "", FALSE);

		// stockAmount
		$this->stockAmount->SetDbValueDef($rsnew, $this->stockAmount->CurrentValue, 0, FALSE);

		// image
		$this->image->SetDbValueDef($rsnew, $this->image->CurrentValue, "", FALSE);

		// minimumStock
		$this->minimumStock->SetDbValueDef($rsnew, $this->minimumStock->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_productslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_products_add)) $as_products_add = new cas_products_add();

// Page init
$as_products_add->Page_Init();

// Page main
$as_products_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_products_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fas_productsadd = new ew_Form("fas_productsadd", "add");

// Validate form
fas_productsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_productCode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->productCode->FldCaption(), $as_products->productCode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_productName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->productName->FldCaption(), $as_products->productName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->categoryID->FldCaption(), $as_products->categoryID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->categoryID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_brandID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->brandID->FldCaption(), $as_products->brandID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_brandID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->brandID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->unit->FldCaption(), $as_products->unit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unit");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->unit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->unitPrice1->FldCaption(), $as_products->unitPrice1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->unitPrice1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->unitPrice2->FldCaption(), $as_products->unitPrice2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->unitPrice2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->unitPrice3->FldCaption(), $as_products->unitPrice3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unitPrice3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->unitPrice3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hpp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->hpp->FldCaption(), $as_products->hpp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hpp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->hpp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_purchasePrice");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->purchasePrice->FldCaption(), $as_products->purchasePrice->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_purchasePrice");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->purchasePrice->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_note");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->note->FldCaption(), $as_products->note->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stockAmount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->stockAmount->FldCaption(), $as_products->stockAmount->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stockAmount");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->stockAmount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_image");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->image->FldCaption(), $as_products->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_minimumStock");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->minimumStock->FldCaption(), $as_products->minimumStock->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_minimumStock");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->minimumStock->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->createdDate->FldCaption(), $as_products->createdDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->createdDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->createdUserID->FldCaption(), $as_products->createdUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_createdUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->createdUserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->modifiedDate->FldCaption(), $as_products->modifiedDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->modifiedDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $as_products->modifiedUserID->FldCaption(), $as_products->modifiedUserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modifiedUserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($as_products->modifiedUserID->FldErrMsg()) ?>");

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
fas_productsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_productsadd.ValidateRequired = true;
<?php } else { ?>
fas_productsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$as_products_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $as_products_add->ShowPageHeader(); ?>
<?php
$as_products_add->ShowMessage();
?>
<form name="fas_productsadd" id="fas_productsadd" class="<?php echo $as_products_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_products_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_products_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_products">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($as_products_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($as_products->productCode->Visible) { // productCode ?>
	<div id="r_productCode" class="form-group">
		<label id="elh_as_products_productCode" for="x_productCode" class="col-sm-2 control-label ewLabel"><?php echo $as_products->productCode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->productCode->CellAttributes() ?>>
<span id="el_as_products_productCode">
<input type="text" data-table="as_products" data-field="x_productCode" name="x_productCode" id="x_productCode" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($as_products->productCode->getPlaceHolder()) ?>" value="<?php echo $as_products->productCode->EditValue ?>"<?php echo $as_products->productCode->EditAttributes() ?>>
</span>
<?php echo $as_products->productCode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->productName->Visible) { // productName ?>
	<div id="r_productName" class="form-group">
		<label id="elh_as_products_productName" for="x_productName" class="col-sm-2 control-label ewLabel"><?php echo $as_products->productName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->productName->CellAttributes() ?>>
<span id="el_as_products_productName">
<input type="text" data-table="as_products" data-field="x_productName" name="x_productName" id="x_productName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($as_products->productName->getPlaceHolder()) ?>" value="<?php echo $as_products->productName->EditValue ?>"<?php echo $as_products->productName->EditAttributes() ?>>
</span>
<?php echo $as_products->productName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->categoryID->Visible) { // categoryID ?>
	<div id="r_categoryID" class="form-group">
		<label id="elh_as_products_categoryID" for="x_categoryID" class="col-sm-2 control-label ewLabel"><?php echo $as_products->categoryID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->categoryID->CellAttributes() ?>>
<span id="el_as_products_categoryID">
<input type="text" data-table="as_products" data-field="x_categoryID" name="x_categoryID" id="x_categoryID" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->categoryID->getPlaceHolder()) ?>" value="<?php echo $as_products->categoryID->EditValue ?>"<?php echo $as_products->categoryID->EditAttributes() ?>>
</span>
<?php echo $as_products->categoryID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->brandID->Visible) { // brandID ?>
	<div id="r_brandID" class="form-group">
		<label id="elh_as_products_brandID" for="x_brandID" class="col-sm-2 control-label ewLabel"><?php echo $as_products->brandID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->brandID->CellAttributes() ?>>
<span id="el_as_products_brandID">
<input type="text" data-table="as_products" data-field="x_brandID" name="x_brandID" id="x_brandID" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->brandID->getPlaceHolder()) ?>" value="<?php echo $as_products->brandID->EditValue ?>"<?php echo $as_products->brandID->EditAttributes() ?>>
</span>
<?php echo $as_products->brandID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->unit->Visible) { // unit ?>
	<div id="r_unit" class="form-group">
		<label id="elh_as_products_unit" for="x_unit" class="col-sm-2 control-label ewLabel"><?php echo $as_products->unit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->unit->CellAttributes() ?>>
<span id="el_as_products_unit">
<input type="text" data-table="as_products" data-field="x_unit" name="x_unit" id="x_unit" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->unit->getPlaceHolder()) ?>" value="<?php echo $as_products->unit->EditValue ?>"<?php echo $as_products->unit->EditAttributes() ?>>
</span>
<?php echo $as_products->unit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->unitPrice1->Visible) { // unitPrice1 ?>
	<div id="r_unitPrice1" class="form-group">
		<label id="elh_as_products_unitPrice1" for="x_unitPrice1" class="col-sm-2 control-label ewLabel"><?php echo $as_products->unitPrice1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->unitPrice1->CellAttributes() ?>>
<span id="el_as_products_unitPrice1">
<input type="text" data-table="as_products" data-field="x_unitPrice1" name="x_unitPrice1" id="x_unitPrice1" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->unitPrice1->getPlaceHolder()) ?>" value="<?php echo $as_products->unitPrice1->EditValue ?>"<?php echo $as_products->unitPrice1->EditAttributes() ?>>
</span>
<?php echo $as_products->unitPrice1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->unitPrice2->Visible) { // unitPrice2 ?>
	<div id="r_unitPrice2" class="form-group">
		<label id="elh_as_products_unitPrice2" for="x_unitPrice2" class="col-sm-2 control-label ewLabel"><?php echo $as_products->unitPrice2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->unitPrice2->CellAttributes() ?>>
<span id="el_as_products_unitPrice2">
<input type="text" data-table="as_products" data-field="x_unitPrice2" name="x_unitPrice2" id="x_unitPrice2" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->unitPrice2->getPlaceHolder()) ?>" value="<?php echo $as_products->unitPrice2->EditValue ?>"<?php echo $as_products->unitPrice2->EditAttributes() ?>>
</span>
<?php echo $as_products->unitPrice2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->unitPrice3->Visible) { // unitPrice3 ?>
	<div id="r_unitPrice3" class="form-group">
		<label id="elh_as_products_unitPrice3" for="x_unitPrice3" class="col-sm-2 control-label ewLabel"><?php echo $as_products->unitPrice3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->unitPrice3->CellAttributes() ?>>
<span id="el_as_products_unitPrice3">
<input type="text" data-table="as_products" data-field="x_unitPrice3" name="x_unitPrice3" id="x_unitPrice3" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->unitPrice3->getPlaceHolder()) ?>" value="<?php echo $as_products->unitPrice3->EditValue ?>"<?php echo $as_products->unitPrice3->EditAttributes() ?>>
</span>
<?php echo $as_products->unitPrice3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->hpp->Visible) { // hpp ?>
	<div id="r_hpp" class="form-group">
		<label id="elh_as_products_hpp" for="x_hpp" class="col-sm-2 control-label ewLabel"><?php echo $as_products->hpp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->hpp->CellAttributes() ?>>
<span id="el_as_products_hpp">
<input type="text" data-table="as_products" data-field="x_hpp" name="x_hpp" id="x_hpp" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->hpp->getPlaceHolder()) ?>" value="<?php echo $as_products->hpp->EditValue ?>"<?php echo $as_products->hpp->EditAttributes() ?>>
</span>
<?php echo $as_products->hpp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->purchasePrice->Visible) { // purchasePrice ?>
	<div id="r_purchasePrice" class="form-group">
		<label id="elh_as_products_purchasePrice" for="x_purchasePrice" class="col-sm-2 control-label ewLabel"><?php echo $as_products->purchasePrice->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->purchasePrice->CellAttributes() ?>>
<span id="el_as_products_purchasePrice">
<input type="text" data-table="as_products" data-field="x_purchasePrice" name="x_purchasePrice" id="x_purchasePrice" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->purchasePrice->getPlaceHolder()) ?>" value="<?php echo $as_products->purchasePrice->EditValue ?>"<?php echo $as_products->purchasePrice->EditAttributes() ?>>
</span>
<?php echo $as_products->purchasePrice->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->note->Visible) { // note ?>
	<div id="r_note" class="form-group">
		<label id="elh_as_products_note" for="x_note" class="col-sm-2 control-label ewLabel"><?php echo $as_products->note->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->note->CellAttributes() ?>>
<span id="el_as_products_note">
<textarea data-table="as_products" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_products->note->getPlaceHolder()) ?>"<?php echo $as_products->note->EditAttributes() ?>><?php echo $as_products->note->EditValue ?></textarea>
</span>
<?php echo $as_products->note->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->stockAmount->Visible) { // stockAmount ?>
	<div id="r_stockAmount" class="form-group">
		<label id="elh_as_products_stockAmount" for="x_stockAmount" class="col-sm-2 control-label ewLabel"><?php echo $as_products->stockAmount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->stockAmount->CellAttributes() ?>>
<span id="el_as_products_stockAmount">
<input type="text" data-table="as_products" data-field="x_stockAmount" name="x_stockAmount" id="x_stockAmount" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->stockAmount->getPlaceHolder()) ?>" value="<?php echo $as_products->stockAmount->EditValue ?>"<?php echo $as_products->stockAmount->EditAttributes() ?>>
</span>
<?php echo $as_products->stockAmount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_as_products_image" for="x_image" class="col-sm-2 control-label ewLabel"><?php echo $as_products->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->image->CellAttributes() ?>>
<span id="el_as_products_image">
<textarea data-table="as_products" data-field="x_image" name="x_image" id="x_image" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($as_products->image->getPlaceHolder()) ?>"<?php echo $as_products->image->EditAttributes() ?>><?php echo $as_products->image->EditValue ?></textarea>
</span>
<?php echo $as_products->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->minimumStock->Visible) { // minimumStock ?>
	<div id="r_minimumStock" class="form-group">
		<label id="elh_as_products_minimumStock" for="x_minimumStock" class="col-sm-2 control-label ewLabel"><?php echo $as_products->minimumStock->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->minimumStock->CellAttributes() ?>>
<span id="el_as_products_minimumStock">
<input type="text" data-table="as_products" data-field="x_minimumStock" name="x_minimumStock" id="x_minimumStock" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->minimumStock->getPlaceHolder()) ?>" value="<?php echo $as_products->minimumStock->EditValue ?>"<?php echo $as_products->minimumStock->EditAttributes() ?>>
</span>
<?php echo $as_products->minimumStock->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->createdDate->Visible) { // createdDate ?>
	<div id="r_createdDate" class="form-group">
		<label id="elh_as_products_createdDate" for="x_createdDate" class="col-sm-2 control-label ewLabel"><?php echo $as_products->createdDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->createdDate->CellAttributes() ?>>
<span id="el_as_products_createdDate">
<input type="text" data-table="as_products" data-field="x_createdDate" name="x_createdDate" id="x_createdDate" placeholder="<?php echo ew_HtmlEncode($as_products->createdDate->getPlaceHolder()) ?>" value="<?php echo $as_products->createdDate->EditValue ?>"<?php echo $as_products->createdDate->EditAttributes() ?>>
</span>
<?php echo $as_products->createdDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->createdUserID->Visible) { // createdUserID ?>
	<div id="r_createdUserID" class="form-group">
		<label id="elh_as_products_createdUserID" for="x_createdUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_products->createdUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->createdUserID->CellAttributes() ?>>
<span id="el_as_products_createdUserID">
<input type="text" data-table="as_products" data-field="x_createdUserID" name="x_createdUserID" id="x_createdUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->createdUserID->getPlaceHolder()) ?>" value="<?php echo $as_products->createdUserID->EditValue ?>"<?php echo $as_products->createdUserID->EditAttributes() ?>>
</span>
<?php echo $as_products->createdUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->modifiedDate->Visible) { // modifiedDate ?>
	<div id="r_modifiedDate" class="form-group">
		<label id="elh_as_products_modifiedDate" for="x_modifiedDate" class="col-sm-2 control-label ewLabel"><?php echo $as_products->modifiedDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->modifiedDate->CellAttributes() ?>>
<span id="el_as_products_modifiedDate">
<input type="text" data-table="as_products" data-field="x_modifiedDate" name="x_modifiedDate" id="x_modifiedDate" placeholder="<?php echo ew_HtmlEncode($as_products->modifiedDate->getPlaceHolder()) ?>" value="<?php echo $as_products->modifiedDate->EditValue ?>"<?php echo $as_products->modifiedDate->EditAttributes() ?>>
</span>
<?php echo $as_products->modifiedDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($as_products->modifiedUserID->Visible) { // modifiedUserID ?>
	<div id="r_modifiedUserID" class="form-group">
		<label id="elh_as_products_modifiedUserID" for="x_modifiedUserID" class="col-sm-2 control-label ewLabel"><?php echo $as_products->modifiedUserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $as_products->modifiedUserID->CellAttributes() ?>>
<span id="el_as_products_modifiedUserID">
<input type="text" data-table="as_products" data-field="x_modifiedUserID" name="x_modifiedUserID" id="x_modifiedUserID" size="30" placeholder="<?php echo ew_HtmlEncode($as_products->modifiedUserID->getPlaceHolder()) ?>" value="<?php echo $as_products->modifiedUserID->EditValue ?>"<?php echo $as_products->modifiedUserID->EditAttributes() ?>>
</span>
<?php echo $as_products->modifiedUserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$as_products_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_products_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fas_productsadd.Init();
</script>
<?php
$as_products_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_products_add->Page_Terminate();
?>
