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
$this->setFrameMode(true);
?>

<div class="recrm_detail">
	<div class="item">

		<?if($arParams["TEMPLATES_COL"]):?>
		<div class="col_l">

			<?if($arParams['DETAIL_PHOTOS'] == "Y"):?>
				<div class="photos">
					<?
					if(is_array($arResult['PHOTOS_URL']) AND count($arResult['PHOTOS_URL']) > 0):
						$i = 1;
						foreach($arResult['PHOTOS_URL'] AS $PHOTO):
							if($i == 1):
								echo '<div class="photo_main"><a href="'.$PHOTO.'" class="recrm_gallery" rel="gallery"><img src="'.$PHOTO.'" alt=""/></a></div>';
							endif;

							echo '<div class="photo_child'.(($i == 1)?' active' : '').'"><a href="'.$PHOTO.'" class="recrm_gallery" rel="gallery"><img src="'.$PHOTO.'" alt=""/></a></div>';
							$i++;
						endforeach;
					else:
						echo '<div class="photo_main"><img src="'.$arParams['PHOTOS_EMPTY'].'" alt=""/></div>';
					endif;
					?>
				</div>
			<?endif?>

			<?if($arParams['DETAIL_VIDEO'] == "Y" AND $arResult['VIDEO_URL']):?>
				<div class="video">
					<iframe width="100%" height="200" src="<?=$arResult['VIDEO_URL']?>" frameborder="0" allowfullscreen></iframe>
				</div>
			<?endif?>

			<?if($arParams['DETAIL_AGENT'] == "Y" AND $arResult['AGENT'] != false):?>
				<div class="agent">
					<div class="photo">
						<img src="<?=$arResult['AGENT']['PHOTO'] == '' ? $arParams["AGENT_EMPTY"] : $arResult['AGENT']['PHOTO']?>">
					</div>
					<div class="info">
						<div class="name"><?=$arResult['AGENT']['NAME']?></div>
						<?
						if($arResult['AGENT']['MOBILE'] != "")
							echo '<div class="mobile">'.$arResult['AGENT']['MOBILE'].'</div>';

						if($arResult['AGENT']['PHONE'] != "")
							echo '<div class="phone">'.$arResult['AGENT']['PHONE'].'</div>';

						if($arResult['AGENT']['EMAIL'] != "")
							echo '<div class="email"><a href="mailto:'.$arResult['AGENT']['EMAIL'].'">'.$arResult['AGENT']['EMAIL'].'</a></div>';
						?>
					</div>
				</div>
			<?endif?>

		</div>

		<div class="col_r" style="width: calc(98% - 250px);">
		<?endif?>

			<div class="title"><?=$arResult['NAME']?></div>

			<?if(count($arResult['PROPERTIES_RECRM']) > 0):?>
			<div class="props">
				<?foreach($arResult['PROPERTIES_RECRM'] AS $prop):?>
				<div class="prop prop_<?=$prop['CODE']?>">
					<div class="p_name"><?=$prop['NAME']?></div>
					<div class="p_val"><?=$prop['VALUE']?> <?=$prop['HINT']?></div>
					<div class="clearfix"></div>
				</div>
				<?endforeach?>
			</div>
			<?endif?>

			<?if($arParams['DETAIL_MAP'] == "Y"):?>
				<script type="text/javascript">
					jQuery(document).ready( function() {
						var ReCrmData = <?=json_encode($arResult["MAP"])?>;
						$("#recrm_map").recrm_map(ReCrmData);
					});
				</script>
				<div id="recrm_map" style="width: 100%; height: 200px;"></div>
			<?endif?>

			<?if($arParams['DETAIL_TEXT'] == "Y"):?>
				<div class="desc"><?=$arResult['DETAIL_TEXT']?></div>
			<?endif?>

		<?if($arParams["TEMPLATES_COL"]):?>
		</div>
		<?endif?>
		<div class="clearfix"></div>
	</div>
</div>