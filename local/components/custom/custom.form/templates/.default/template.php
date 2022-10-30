<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 */
\Bitrix\Main\UI\Extension::load('ui.bootstrap4');
\Bitrix\Main\UI\Extension::load('main.jquery');
?>
<form id="custom_form">
    <?
    foreach ($arResult['FIELDS'] as $field)
    {
        ?>
        <div class="row" id="ROW-<?=$field['ID']?>">
        <?
            if($field['TYPE']=='line') { ?>
                <div class="row justify-content-start">
                <?
                foreach ($field['ITEMS'] as $item) {
                ?>
                    <div class="form-group col-2 text-center column-label">
                        <label for="<?=$item['ID']?>"><?=$item['NAME']?></label>
                    </div>
                <?
                } ?>
                </div>
                <div class="row justify-content-start" id="ROW-SAMPLE">
                <?
                foreach ($field['ITEMS'] as $item) {
                    ?>
                    <div class="form-group col-2">
                        <? if($item['TYPE']=='text')
                        {
                            ?>
                            <input type="<?=$item['TYPE']?>" class="specline form-control" name="<?=$item['ID']?>-1">
                            <?
                        } elseif($item['TYPE']=='select')  {
                            ?>
                            <select class="specline form-control" name="<?=$item['ID']?>-1">
                                <?
                                foreach ($item['VALUES'] as $key=>$sel)
                                {
                                    ?>
                                    <option value="<?=$sel['value']?>"><?=$sel['label']?></option>
                                    <?
                                } ?>
                            </select>
                        <? } ?>
                    </div>
                    <?
                } ?>
                    <div class="row justify-content-start align-items-center col">
                        <div id='ADD_LINE' class="col-1 hover-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                        <div id='DEL_LINE' class="col-1 hover-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            <?
            } else {
            ?>
                <div class="form-group col-<?=$field['COL_INDEX']?>">
                    <label class="main-label" for="<?=$field['ID']?>"><?=$field['NAME']?><?=($field['REQUIRED'] ? '<span style="color: rgb(255, 0, 0);">*</span>' : '')?></label>
                    <? if($field['TYPE']=='text' || $field['TYPE']=='file')
                    {
                        ?>
                        <input type="<?=$field['TYPE']?>" name="<?=$field['ID']?>" class="form-control" id="<?=$field['ID']?>">
                        <?
                    } elseif($field['TYPE']=='radio') {
                        foreach ($field['VALUES'] as $key=>$item)
                        {
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="<?=$field['ID']?>" id="<?=$item['value']?>" value="<?=$item['value']?>"
                                >
                                <label class="form-check-label" for="<?=$item['value']?>">
                                    <?=$item['label']?>
                                </label>
                            </div>
                            <?
                        }
                    } elseif($field['TYPE']=='select') {
                        ?>
                        <select class="form-control" name="<?=$field['ID']?>" id="<?=$field['ID']?>">
                            <?
                            foreach ($field['VALUES'] as $key=>$item)
                            {
                                ?>
                                <option value="<?=$item['value']?>"><?=$item['label']?></option>
                                <?
                            } ?>
                        </select>
                        <?
                    } elseif($field['TYPE']=='textarea') {
                        ?>
                        <textarea class="form-control" name="<?=$field['ID']?>" rows="3"></textarea>
                        <?
                    } elseif($field['TYPE']=='line') {
                        ?>

                        <?
                    }
                    ?>
                </div>
            <?
            }
        ?>
        </div>
        <?
    }
    ?>
    <button id="SUBMIT_BUTTON" class=""><?=GetMessage("SEND_FORM")?></button>
</form>

<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'custom.form');
?>
<script>
    $( document ).ready(function() {
        new BX.Custom.Form.Edit({
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            signedParameters: '<?=CUtil::JSEscape($this->getComponent()->getSignedParameters())?>',
            componentName: '<?=CUtil::JSEscape($this->getComponent()->getName())?>'
        });
    });

</script>

