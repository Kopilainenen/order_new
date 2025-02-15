<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?
foreach ($arResult["PAY_SYSTEM"] as $k => $arPaySystem) {
    if ($arPaySystem['ID'] == 13) {
        $secondPaySystem = $arPaySystem;
        unset($arResult["PAY_SYSTEM"][$k]);
    }
}
?>


<b><?= GetMessage("SOA_TEMPL_PAY_SYSTEM") ?></b><br><br>
<b>1. Для физических лиц</b>
<table class="sale_order_full_table">
    <?
    if ($arResult["PAY_FROM_ACCOUNT"] == "Y") {
        ?>
        <tr>
            <td colspan="2">
                <input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
                <input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT"
                       value="Y"<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")
                    echo " checked=\"checked\""; ?> onChange="submitForm()"> <label
                        for="PAY_CURRENT_ACCOUNT"><b><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT") ?></b></label><br/>
                <?= GetMessage("SOA_TEMPL_PAY_ACCOUNT1") ?>
                <b><?= $arResult["CURRENT_BUDGET_FORMATED"] ?></b>, <?= GetMessage("SOA_TEMPL_PAY_ACCOUNT2") ?>
                <br/><br/>
            </td>
        </tr>
        <?
    }
    ?>
    <?
    foreach ($arResult["PAY_SYSTEM"] as $arPaySystem) {
        if (count($arResult["PAY_SYSTEM"]) == 1) {
            ?>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>">
                    <b><?= $arPaySystem["NAME"]; ?></b>
                    <?
                    if (strlen($arPaySystem["DESCRIPTION"]) > 0) {
                        ?>
                        <?= $arPaySystem["DESCRIPTION"] ?>
                        <br/>
                        <?
                    }
                    ?>
                </td>
            </tr>
            <?
        } else {
            if (!isset($_POST['PAY_CURRENT_ACCOUNT']) OR $_POST['PAY_CURRENT_ACCOUNT'] == "N") {
                ?>
                <tr>
                    <td valign="top" width="0%">
                        <input type="radio" id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>" name="PAY_SYSTEM_ID"
                               value="<?= $arPaySystem["ID"] ?>"<? if ($arPaySystem["CHECKED"] == "Y")
                            echo " checked=\"checked\""; ?>>
                    </td>
                    <td valign="top" width="100%">
                        <label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>">
                            <b><?= $arPaySystem["PSA_NAME"] ?></b><br/>
                            <?
                            if (strlen($arPaySystem["DESCRIPTION"]) > 0) {
                                ?>
                                <?= $arPaySystem["DESCRIPTION"] ?>
                                <br/>
                                <?
                            }
                            ?>
                        </label>

                    </td>
                </tr>
                <?
            }
        }
    }
    ?>
</table>
<br>
<b>2. Для Юридических лиц </b>
<table class="sale_order_full_table">

    <tr>
        <td valign="top" width="0%">
            <input type="radio" id="ID_PAY_SYSTEM_ID_<?= $secondPaySystem["ID"] ?>" name="PAY_SYSTEM_ID"
                   value="<?= $secondPaySystem["ID"] ?>"<? if ($secondPaySystem["CHECKED"] == "Y")
                echo " checked=\"checked\""; ?>>
        </td>
        <td valign="top" width="100%">
            <label for="ID_PAY_SYSTEM_ID_<?= $secondPaySystem["ID"] ?>">
                <b><?= $secondPaySystem["PSA_NAME"] ?></b><br/>
                <?
                if (strlen($secondPaySystem["DESCRIPTION"]) > 0) {
                    ?>
                    <?= $secondPaySystem["DESCRIPTION"] ?>
                    <br/>
                    <?
                }
                ?>
            </label>

        </td>
    </tr>

</table>

