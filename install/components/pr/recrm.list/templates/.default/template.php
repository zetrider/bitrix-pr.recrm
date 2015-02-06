<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

/** .item #recrm_el_{id} необходим для работы карты */

$this->setFrameMode(true);
?>

<?if($arParams["LIST_MAP_BIG"] == "Y"):?>
<script type="text/javascript">
	jQuery(document).ready( function() {
		var ReCrmData = <?=json_encode($arResult["MAP"])?>;
		$("#recrm_map").recrm_map(ReCrmData);
	});
</script>
<div id="recrm_map" style="width: 100%; height: 300px;"></div>
<?endif?>

<div class="recrm_list">

	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?><br />
	<?endif;?>
	
	<div class="items">
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="item<?=$arParams['LIST_COVER_PHOTO'] == "Y" ? " item_photo" : ""?>" id="recrm_el_<?=$arItem['ID']?>">

			<?if($arParams['LIST_COVER_PHOTO'] == "Y"):?>
				<?if($arItem['COVER_PHOTO']):?>
				<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="photo" style="width: <?=$arParams['LIST_COVER_PHOTO_SIZE']['width']?>px; max-height: <?=$arParams['LIST_COVER_PHOTO_SIZE']['height']?>px;" title="<?=$arItem['COVER_PHOTO']['TITLE']?>">
					<img src="<?=$arItem['COVER_PHOTO']['URL']?>" alt="<?=$arItem['COVER_PHOTO']['ALT']?>" title="<?=$arItem['COVER_PHOTO']['TITLE']?>"/>
				</a>
				<?else:?>
				<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="photo" style="width: <?=$arParams['LIST_COVER_PHOTO_SIZE']['width']?>px; max-height: <?=$arParams['LIST_COVER_PHOTO_SIZE']['height']?>px;">
					<img src="<?=$arParams['COVER_PHOTO_EMPTY']?>" alt=""/>
				</a>
				<?endif?>
				<div class="info" style="width: calc(98% - <?=$arParams['LIST_COVER_PHOTO_SIZE']['width']?>px);">
			<?endif?>

			<div class="title"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></div>

			<?if($arParams['LIST_DESC'] == "Y"):?>
				<div class="desc"><?=$arItem['DETAIL_TEXT']?></div>
			<?endif?>

			<?if(count($arItem['PROPERTIES_RECRM']) > 0):?>

			<div class="props">
				<?foreach($arItem['PROPERTIES_RECRM'] AS $prop):?>
				<div class="prop prop_<?=$prop['CODE']?>">
					<div class="p_name"><?=$prop['NAME']?></div>
					<div class="p_val"><?=$prop['VALUE']?> <?=$prop['HINT']?></div>
					<div class="clearfix"></div>
				</div>
				<?endforeach?>
			</div>

			<?endif?>

			<?if($arParams['LIST_COVER_PHOTO'] == "Y"):?>
				</div>
				<div class="clearfix"></div>
			<?endif?>

			<div class="more"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=GetMessage("MORE")?></a></div>

		</div>
		<?endforeach;?>
		<div class="clearfix"></div>
	</div>

	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>

</div>