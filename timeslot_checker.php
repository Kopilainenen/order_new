<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
?>
<?
\Bitrix\Main\Loader::includeModule('sale');
global $USER_FIELD_MANAGER;

$ufEntity = "CUSTOM_TIMESLOT"; // мой тип пользовательского свойства
$ufName = "UF_TIMESLOT"; // мое пользовательское свойство
$time_end = "CUSTOM_END_TIMESLOT"; // Время закрытия временного слота

$tmSlotId = "CUSTOM_TS_E_END";
$tmSlotName = "UF_TS_E_END";

//global $USER;
//if ($USER->isAdmin()){
//    $_REQUEST['time_slot'] = iconv('windows-1251', 'UTF-8', $_REQUEST['time_slot']);
//    $_REQUEST['time_slot'] = iconv('UTF-8', 'windows-1251', $_REQUEST['time_slot']);
//    echo $_REQUEST['time_slot'];
//    die;
//}
//$_REQUEST['time_slot'] = iconv('windows-1251', 'UTF-8', $_REQUEST['time_slot']);
if ($_REQUEST['site_id'] == 's1') {
    $slot = SlotsTable::getList(array('filter' => array('TT' => $_COOKIE['BITRIX_TT'], 'TYPE' => 1, 'SLOT' => $_REQUEST['time_slot']), 'order' => ['SLOT' => 'ASC']))->fetch();
    $curDate = new DateTime();
    $slotEnd = strtotime(date('d.m.Y ' . $slot['SLOT_END']));
    if ($curDate->getTimestamp() > $slotEnd) {
        echo $_REQUEST['time_slot'];
        die;
    } else {
        echo 0;
        die;
    }
} elseif ($_REQUEST['site_id'] == 's2') {
    $arTimeSlotSevsk = array();
    $dbRes = CSaleOrderPropsVariant::GetList(false, array("ORDER_PROPS_ID" => 16));
    while ($res = $dbRes->Fetch())
        $arTimeSlotSevsk[$res["VALUE"]] = $res;

    foreach ($arTimeSlotSevsk as $key => $value) {
        $arTimeSlotSevsk[$key]["USER_FIELD_VALUE"] = $USER_FIELD_MANAGER->GetUserFieldValue($ufEntity, $ufName, $value["VALUE"]);
        $arTimeSlotSevsk[$key]["SLOT_CLOSE"] = $USER_FIELD_MANAGER->GetUserFieldValue($tmSlotId, $tmSlotName, $value["VALUE"]);
    }
    $slot = $arTimeSlotSevsk[$_REQUEST['time_slot']];
    $curDate = new DateTime();
    $slotEnd = strtotime(date('d.m.Y ' . $slot['SLOT_CLOSE']));
    if ($curDate->getTimestamp() > $slotEnd) {
        echo $_REQUEST['time_slot'];
        die;
    } else {
        echo 0;
        die;
    }
}
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>