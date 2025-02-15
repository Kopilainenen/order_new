<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["DELIVERY"]))
{
	?>
	<b><?=GetMessage("SOA_TEMPL_DELIVERY")?></b>
	<table class="sale_order_full_table">
		<?
		foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
		{
			if ($delivery_id !== 0 && intval($delivery_id) <= 0)
			{
				?>
				<tr>
					<td colspan="2">
						<b><?=$arDelivery["TITLE"]?></b><?if (strlen($arDelivery["DESCRIPTION"]) > 0):?><br />
						<?=nl2br($arDelivery["DESCRIPTION"])?><br /><?endif;?>
						<table border="0" cellspacing="0" cellpadding="3">
						<?
						foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
						{
							?>
							<tr>
								<td width="20" nowrap="nowrap">&nbsp;</td>
								<td width="0%" valign="top"><input type="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=$arProfile["FIELD_NAME"]?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onClick="submitForm();" /></td>
								<td width="50%" valign="top">
									<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
										<small><b><?=$arProfile["TITLE"]?></b><?if (strlen($arProfile["DESCRIPTION"]) > 0):?><br />
										<?=nl2br($arProfile["DESCRIPTION"])?><?endif;?></small>
									</label>
								</td>
								
							</tr>
							<?
						} // endforeach
						?>
						</table>
					</td>
				</tr>
				<?
			}	
			else
			{
				?>
				<tr>
					<td valign="top" width="0%">
						<input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?=$arDelivery["FIELD_NAME"]?>" value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?> >
					</td>
					<td valign="top" width="100%">
						<label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">
						<b><?= $arDelivery["NAME"] ?></b><br />
						<?
						if (strlen($arDelivery["PERIOD_TEXT"])>0)
						{
							echo $arDelivery["PERIOD_TEXT"];
							?><br /><?
						}
						?>
						
						</label>
					</td>
				</tr>
				<?
			}
		}
		?>
	</table>
	<br /><br />
	<?
}
?>