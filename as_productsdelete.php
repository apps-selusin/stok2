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

$as_products_delete = NULL; // Initialize page object first

class cas_products_delete extends cas_products {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9435B223-C5A2-406D-9811-AE3FF94021AF}";

	// Table name
	var $TableName = 'as_products';

	// Page object name
	var $PageObjName = 'as_products_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->productID->SetVisibility();
		$this->productID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
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
		$this->stockAmount->SetVisibility();
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
			$this->Page_Terminate("as_productslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in as_products class, as_productsinfo.php

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
				$this->Page_Terminate("as_productslist.php"); // Return to list
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

		// stockAmount
		$this->stockAmount->ViewValue = $this->stockAmount->CurrentValue;
		$this->stockAmount->ViewCustomAttributes = "";

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

			// productID
			$this->productID->LinkCustomAttributes = "";
			$this->productID->HrefValue = "";
			$this->productID->TooltipValue = "";

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

			// stockAmount
			$this->stockAmount->LinkCustomAttributes = "";
			$this->stockAmount->HrefValue = "";
			$this->stockAmount->TooltipValue = "";

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
				$sThisKey .= $row['productID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("as_productslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($as_products_delete)) $as_products_delete = new cas_products_delete();

// Page init
$as_products_delete->Page_Init();

// Page main
$as_products_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$as_products_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fas_productsdelete = new ew_Form("fas_productsdelete", "delete");

// Form_CustomValidate event
fas_productsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fas_productsdelete.ValidateRequired = true;
<?php } else { ?>
fas_productsdelete.ValidateRequired = false; 
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
<?php $as_products_delete->ShowPageHeader(); ?>
<?php
$as_products_delete->ShowMessage();
?>
<form name="fas_productsdelete" id="fas_productsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($as_products_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $as_products_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="as_products">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($as_products_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $as_products->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($as_products->productID->Visible) { // productID ?>
		<th><span id="elh_as_products_productID" class="as_products_productID"><?php echo $as_products->productID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->productCode->Visible) { // productCode ?>
		<th><span id="elh_as_products_productCode" class="as_products_productCode"><?php echo $as_products->productCode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->productName->Visible) { // productName ?>
		<th><span id="elh_as_products_productName" class="as_products_productName"><?php echo $as_products->productName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->categoryID->Visible) { // categoryID ?>
		<th><span id="elh_as_products_categoryID" class="as_products_categoryID"><?php echo $as_products->categoryID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->brandID->Visible) { // brandID ?>
		<th><span id="elh_as_products_brandID" class="as_products_brandID"><?php echo $as_products->brandID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->unit->Visible) { // unit ?>
		<th><span id="elh_as_products_unit" class="as_products_unit"><?php echo $as_products->unit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->unitPrice1->Visible) { // unitPrice1 ?>
		<th><span id="elh_as_products_unitPrice1" class="as_products_unitPrice1"><?php echo $as_products->unitPrice1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->unitPrice2->Visible) { // unitPrice2 ?>
		<th><span id="elh_as_products_unitPrice2" class="as_products_unitPrice2"><?php echo $as_products->unitPrice2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->unitPrice3->Visible) { // unitPrice3 ?>
		<th><span id="elh_as_products_unitPrice3" class="as_products_unitPrice3"><?php echo $as_products->unitPrice3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->hpp->Visible) { // hpp ?>
		<th><span id="elh_as_products_hpp" class="as_products_hpp"><?php echo $as_products->hpp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->purchasePrice->Visible) { // purchasePrice ?>
		<th><span id="elh_as_products_purchasePrice" class="as_products_purchasePrice"><?php echo $as_products->purchasePrice->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->stockAmount->Visible) { // stockAmount ?>
		<th><span id="elh_as_products_stockAmount" class="as_products_stockAmount"><?php echo $as_products->stockAmount->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->minimumStock->Visible) { // minimumStock ?>
		<th><span id="elh_as_products_minimumStock" class="as_products_minimumStock"><?php echo $as_products->minimumStock->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->createdDate->Visible) { // createdDate ?>
		<th><span id="elh_as_products_createdDate" class="as_products_createdDate"><?php echo $as_products->createdDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->createdUserID->Visible) { // createdUserID ?>
		<th><span id="elh_as_products_createdUserID" class="as_products_createdUserID"><?php echo $as_products->createdUserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->modifiedDate->Visible) { // modifiedDate ?>
		<th><span id="elh_as_products_modifiedDate" class="as_products_modifiedDate"><?php echo $as_products->modifiedDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($as_products->modifiedUserID->Visible) { // modifiedUserID ?>
		<th><span id="elh_as_products_modifiedUserID" class="as_products_modifiedUserID"><?php echo $as_products->modifiedUserID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$as_products_delete->RecCnt = 0;
$i = 0;
while (!$as_products_delete->Recordset->EOF) {
	$as_products_delete->RecCnt++;
	$as_products_delete->RowCnt++;

	// Set row properties
	$as_products->ResetAttrs();
	$as_products->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$as_products_delete->LoadRowValues($as_products_delete->Recordset);

	// Render row
	$as_products_delete->RenderRow();
?>
	<tr<?php echo $as_products->RowAttributes() ?>>
<?php if ($as_products->productID->Visible) { // productID ?>
		<td<?php echo $as_products->productID->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_productID" class="as_products_productID">
<span<?php echo $as_products->productID->ViewAttributes() ?>>
<?php echo $as_products->productID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->productCode->Visible) { // productCode ?>
		<td<?php echo $as_products->productCode->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_productCode" class="as_products_productCode">
<span<?php echo $as_products->productCode->ViewAttributes() ?>>
<?php echo $as_products->productCode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->productName->Visible) { // productName ?>
		<td<?php echo $as_products->productName->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_productName" class="as_products_productName">
<span<?php echo $as_products->productName->ViewAttributes() ?>>
<?php echo $as_products->productName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->categoryID->Visible) { // categoryID ?>
		<td<?php echo $as_products->categoryID->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_categoryID" class="as_products_categoryID">
<span<?php echo $as_products->categoryID->ViewAttributes() ?>>
<?php echo $as_products->categoryID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->brandID->Visible) { // brandID ?>
		<td<?php echo $as_products->brandID->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_brandID" class="as_products_brandID">
<span<?php echo $as_products->brandID->ViewAttributes() ?>>
<?php echo $as_products->brandID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->unit->Visible) { // unit ?>
		<td<?php echo $as_products->unit->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_unit" class="as_products_unit">
<span<?php echo $as_products->unit->ViewAttributes() ?>>
<?php echo $as_products->unit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->unitPrice1->Visible) { // unitPrice1 ?>
		<td<?php echo $as_products->unitPrice1->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_unitPrice1" class="as_products_unitPrice1">
<span<?php echo $as_products->unitPrice1->ViewAttributes() ?>>
<?php echo $as_products->unitPrice1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->unitPrice2->Visible) { // unitPrice2 ?>
		<td<?php echo $as_products->unitPrice2->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_unitPrice2" class="as_products_unitPrice2">
<span<?php echo $as_products->unitPrice2->ViewAttributes() ?>>
<?php echo $as_products->unitPrice2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->unitPrice3->Visible) { // unitPrice3 ?>
		<td<?php echo $as_products->unitPrice3->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_unitPrice3" class="as_products_unitPrice3">
<span<?php echo $as_products->unitPrice3->ViewAttributes() ?>>
<?php echo $as_products->unitPrice3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->hpp->Visible) { // hpp ?>
		<td<?php echo $as_products->hpp->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_hpp" class="as_products_hpp">
<span<?php echo $as_products->hpp->ViewAttributes() ?>>
<?php echo $as_products->hpp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->purchasePrice->Visible) { // purchasePrice ?>
		<td<?php echo $as_products->purchasePrice->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_purchasePrice" class="as_products_purchasePrice">
<span<?php echo $as_products->purchasePrice->ViewAttributes() ?>>
<?php echo $as_products->purchasePrice->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->stockAmount->Visible) { // stockAmount ?>
		<td<?php echo $as_products->stockAmount->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_stockAmount" class="as_products_stockAmount">
<span<?php echo $as_products->stockAmount->ViewAttributes() ?>>
<?php echo $as_products->stockAmount->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->minimumStock->Visible) { // minimumStock ?>
		<td<?php echo $as_products->minimumStock->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_minimumStock" class="as_products_minimumStock">
<span<?php echo $as_products->minimumStock->ViewAttributes() ?>>
<?php echo $as_products->minimumStock->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->createdDate->Visible) { // createdDate ?>
		<td<?php echo $as_products->createdDate->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_createdDate" class="as_products_createdDate">
<span<?php echo $as_products->createdDate->ViewAttributes() ?>>
<?php echo $as_products->createdDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->createdUserID->Visible) { // createdUserID ?>
		<td<?php echo $as_products->createdUserID->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_createdUserID" class="as_products_createdUserID">
<span<?php echo $as_products->createdUserID->ViewAttributes() ?>>
<?php echo $as_products->createdUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->modifiedDate->Visible) { // modifiedDate ?>
		<td<?php echo $as_products->modifiedDate->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_modifiedDate" class="as_products_modifiedDate">
<span<?php echo $as_products->modifiedDate->ViewAttributes() ?>>
<?php echo $as_products->modifiedDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($as_products->modifiedUserID->Visible) { // modifiedUserID ?>
		<td<?php echo $as_products->modifiedUserID->CellAttributes() ?>>
<span id="el<?php echo $as_products_delete->RowCnt ?>_as_products_modifiedUserID" class="as_products_modifiedUserID">
<span<?php echo $as_products->modifiedUserID->ViewAttributes() ?>>
<?php echo $as_products->modifiedUserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$as_products_delete->Recordset->MoveNext();
}
$as_products_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $as_products_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fas_productsdelete.Init();
</script>
<?php
$as_products_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$as_products_delete->Page_Terminate();
?>
