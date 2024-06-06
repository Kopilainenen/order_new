<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<b><?= GetMessage("SOA_TEMPL_SUM_TITLE") ?></b><br />
<?//print_var($arResult["BASKET_ITEMS"], $USER->IsAdmin());?>
<table class="sale_order_full data-table">
    <tr>
        <th><?= GetMessage("SOA_TEMPL_SUM_NAME") ?></th>
<!--		<th><?= GetMessage("SOA_TEMPL_SUM_PROPS") ?></th>-->
<!--		<th><?= GetMessage("SOA_TEMPL_SUM_PRICE_TYPE") ?></th>-->
        <th><?= GetMessage("SOA_TEMPL_SUM_DISCOUNT") ?></th>
<!--		<th><?= GetMessage("SOA_TEMPL_SUM_WEIGHT") ?></th>-->
        <th><?= GetMessage("SOA_TEMPL_SUM_QUANTITY") ?></th>
        <th><?= GetMessage("SOA_TEMPL_SUM_PRICE") ?></th>
    </tr>
    <?
	$cafe_separator = true;
    $farma_separator = true;
    foreach ($arResult["BASKET_ITEMS"] as $arBasketItems) {
        ?>
		<?if($arBasketItems["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_VALUE"] == "Y" and $cafe_separator): $cafe_separator = false;?>
			<tr class="cafe-separator"><td colspan="4" style="text-align: center;">Кафе Петромост</td></tr>
		<?endif;?>
        <?if($arBasketItems["PROPS"]["PROPERTY_SEPARATE_IN_REPORT_FARMA_VALUE"] == "Y" and $farma_separator): $farma_separator = false;?>
            <tr class="cafe-separator"><td colspan="4" style="text-align: center;">Аптека</td></tr>
        <?endif;?>
        <tr>
            <td><?= $arBasketItems["NAME"] ?>1</td>
    <!--			<td>
            <?
            foreach ($arBasketItems["PROPS"] as $val) {
                echo $val["NAME"] . ": " . $val["VALUE"] . "<br />";
            }
            ?>
            </td>-->
    <!--			<td><?= $arBasketItems["NOTES"] ?></td>-->
            <td><?= $arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"] ?></td>
    <!--			<td><?= $arBasketItems["WEIGHT_FORMATED"] ?></td>-->
            <td><?= intval($arBasketItems['we']) ? $arBasketItems["QUANTITY"] * 1000 : $arBasketItems["QUANTITY"] ?> <? if (intval($arBasketItems['we'])): ?> гр. <? else: ?> шт.<? endif; ?></td>
            <td align="right"><?= round((($arBasketItems["QUANTITY"]) * $arBasketItems["PRICE"]), 2) ?> руб.</td>
        </tr>
        <? $price = $arBasketItems["QUANTITY"] * $arBasketItems["PRICE"]; ?>
        <?
        $sum += $price;
    }
    ?>

    <!--	<tr>
<td align="right"><b><?= GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM") ?></b></td>
<td align="right" colspan="6"><?= $arResult["ORDER_WEIGHT_FORMATED"] ?></td>
</tr>-->
    <tr>
        <td align="right"><b><?= GetMessage("SOA_TEMPL_SUM_SUMMARY") ?></b></td>
        <td align="right" colspan="6"><?= round($sum, 2) ?> руб.</td>
    </tr>
    <?
    if (doubleval($arResult["DISCOUNT_PRICE"]) > 0) {
        ?>
        <tr>
            <td align="right"><b><?= GetMessage("SOA_TEMPL_SUM_DISCOUNT") ?><? if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"]) > 0): ?> (<? echo $arResult["DISCOUNT_PERCENT_FORMATED"]; ?>)<? endif; ?>:</b></td>
            <td align="right" colspan="6"><? echo $arResult["DISCOUNT_PRICE_FORMATED"] ?>
            </td>
        </tr>
        <?
    }
    /*
      if (doubleval($arResult["VAT_SUM_FORMATED"]) > 0)
      {
      ?>
      <tr>
      <td align="right">
      <b><?=GetMessage("SOA_TEMPL_SUM_VAT")?></b>
      </td>
      <td align="right" colspan="6"><?=$arResult["VAT_SUM_FORMATED"]?></td>
      </tr>
      <?
      }
     */
    if (!empty($arResult["arTaxList"])) {
        foreach ($arResult["arTaxList"] as $val) {
            ?>
            <tr>
                <td align="right"><?= $val["NAME"] ?> <?= $val["VALUE_FORMATED"] ?>:</td>
                <td align="right" colspan="6"><?= $val["VALUE_MONEY_FORMATED"] ?></td>
            </tr>
            <?
        }
    }
    if (doubleval($arResult["DELIVERY_PRICE"]) > 0) {
        ?>
        <tr>
            <td align="right">
                <b><?= GetMessage("SOA_TEMPL_SUM_DELIVERY") ?></b>
            </td>
            <td align="right" colspan="6"><?= $arResult["DELIVERY_PRICE_FORMATED"] ?></td>
        </tr>
        <?
    }
    ?>
    <tr>
        <td align="right"><b><?= GetMessage("SOA_TEMPL_SUM_IT") ?></b></td>
        <td align="right" colspan="6"><b><?= round($sum, 2) ?> руб.</b>
        </td>
    </tr>
    <?
    if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0) {
        ?>
        <tr>
            <td align="right"><b><?= GetMessage("SOA_TEMPL_SUM_PAYED") ?></b></td>
            <td align="right" colspan="6"><?= $sum ?></td>
        </tr>
        <?
    }
    ?>
</table>

<br /><br />
<b><?= GetMessage("SOA_TEMPL_SUM_ADIT_INFO") ?></b><br /><br />

<table class="sale_order_full_table">
    <tr>
        <td width="50%" align="left" valign="top"><?= GetMessage("SOA_TEMPL_SUM_COMMENTS") ?><br />
            <textarea rows="4" cols="40" name="ORDER_DESCRIPTION"><?= $arResult["USER_VALS"]["ORDER_DESCRIPTION"] ?></textarea>
        </td>
    </tr>
</table>

