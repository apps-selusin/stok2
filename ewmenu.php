<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(38, "mi_cf_01_home_php", $Language->MenuPhrase("38", "MenuText"), "cf_01_home.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}cf_01_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(39, "mci_Master_Data", $Language->MenuPhrase("39", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(29, "mi_as_suppliers", $Language->MenuPhrase("29", "MenuText"), "as_supplierslist.php", 39, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_suppliers'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_as_categories", $Language->MenuPhrase("5", "MenuText"), "as_categorieslist.php", 39, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_categories'), FALSE, FALSE);
$RootMenu->AddMenuItem(18, "mi_as_products", $Language->MenuPhrase("18", "MenuText"), "as_productslist.php", 39, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_products'), FALSE, FALSE);
$RootMenu->AddMenuItem(77, "mci_Pembelian", $Language->MenuPhrase("77", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(25, "mi_as_spb", $Language->MenuPhrase("25", "MenuText"), "as_spblist.php", 77, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_spb'), FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mi_as_detail_spb", $Language->MenuPhrase("14", "MenuText"), "as_detail_spblist.php", 77, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_spb'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_as_bbm", $Language->MenuPhrase("1", "MenuText"), "as_bbmlist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_bbm'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_as_brands", $Language->MenuPhrase("2", "MenuText"), "as_brandslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_brands'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_as_buy_payments", $Language->MenuPhrase("3", "MenuText"), "as_buy_paymentslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_buy_payments'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_as_buy_transactions", $Language->MenuPhrase("4", "MenuText"), "as_buy_transactionslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_buy_transactions'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_as_customers", $Language->MenuPhrase("6", "MenuText"), "as_customerslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_customers'), FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_as_debts", $Language->MenuPhrase("7", "MenuText"), "as_debtslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_debts'), FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mi_as_delivery_order", $Language->MenuPhrase("8", "MenuText"), "as_delivery_orderlist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_delivery_order'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mi_as_detail_bbm", $Language->MenuPhrase("9", "MenuText"), "as_detail_bbmlist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_bbm'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mi_as_detail_do", $Language->MenuPhrase("10", "MenuText"), "as_detail_dolist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_do'), FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_as_detail_retur_staffs", $Language->MenuPhrase("11", "MenuText"), "as_detail_retur_staffslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_retur_staffs'), FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mi_as_detail_retur_suppliers", $Language->MenuPhrase("12", "MenuText"), "as_detail_retur_supplierslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_retur_suppliers'), FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mi_as_detail_so", $Language->MenuPhrase("13", "MenuText"), "as_detail_solist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_so'), FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mi_as_detail_transfers", $Language->MenuPhrase("15", "MenuText"), "as_detail_transferslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_detail_transfers'), FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mi_as_factories", $Language->MenuPhrase("16", "MenuText"), "as_factorieslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_factories'), FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mi_as_modules", $Language->MenuPhrase("17", "MenuText"), "as_moduleslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_modules'), FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mi_as_receivables", $Language->MenuPhrase("19", "MenuText"), "as_receivableslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_receivables'), FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mi_as_retur_staffs", $Language->MenuPhrase("20", "MenuText"), "as_retur_staffslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_retur_staffs'), FALSE, FALSE);
$RootMenu->AddMenuItem(21, "mi_as_retur_suppliers", $Language->MenuPhrase("21", "MenuText"), "as_retur_supplierslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_retur_suppliers'), FALSE, FALSE);
$RootMenu->AddMenuItem(22, "mi_as_sales_order", $Language->MenuPhrase("22", "MenuText"), "as_sales_orderlist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_sales_order'), FALSE, FALSE);
$RootMenu->AddMenuItem(23, "mi_as_sales_payments", $Language->MenuPhrase("23", "MenuText"), "as_sales_paymentslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_sales_payments'), FALSE, FALSE);
$RootMenu->AddMenuItem(24, "mi_as_sales_transactions", $Language->MenuPhrase("24", "MenuText"), "as_sales_transactionslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_sales_transactions'), FALSE, FALSE);
$RootMenu->AddMenuItem(26, "mi_as_staffs", $Language->MenuPhrase("26", "MenuText"), "as_staffslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_staffs'), FALSE, FALSE);
$RootMenu->AddMenuItem(27, "mi_as_stock_opname", $Language->MenuPhrase("27", "MenuText"), "as_stock_opnamelist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_stock_opname'), FALSE, FALSE);
$RootMenu->AddMenuItem(28, "mi_as_stock_products", $Language->MenuPhrase("28", "MenuText"), "as_stock_productslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_stock_products'), FALSE, FALSE);
$RootMenu->AddMenuItem(30, "mi_as_temp_detail_so", $Language->MenuPhrase("30", "MenuText"), "as_temp_detail_solist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_temp_detail_so'), FALSE, FALSE);
$RootMenu->AddMenuItem(31, "mi_as_temp_detail_spb", $Language->MenuPhrase("31", "MenuText"), "as_temp_detail_spblist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_temp_detail_spb'), FALSE, FALSE);
$RootMenu->AddMenuItem(32, "mi_as_temp_detail_transfers", $Language->MenuPhrase("32", "MenuText"), "as_temp_detail_transferslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_temp_detail_transfers'), FALSE, FALSE);
$RootMenu->AddMenuItem(33, "mi_as_transfers", $Language->MenuPhrase("33", "MenuText"), "as_transferslist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}as_transfers'), FALSE, FALSE);
$RootMenu->AddMenuItem(34, "mi_t_99_audittrail", $Language->MenuPhrase("34", "MenuText"), "t_99_audittraillist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}t_99_audittrail'), FALSE, FALSE);
$RootMenu->AddMenuItem(35, "mi_t_96_user", $Language->MenuPhrase("35", "MenuText"), "t_96_userlist.php", -1, "", AllowListMenu('{9435B223-C5A2-406D-9811-AE3FF94021AF}t_96_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(36, "mi_t_97_level", $Language->MenuPhrase("36", "MenuText"), "t_97_levellist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(37, "mi_t_98_permission", $Language->MenuPhrase("37", "MenuText"), "t_98_permissionlist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
