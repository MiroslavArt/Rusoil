<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Loader;

class CustomForm extends \CBitrixComponent
	implements \Bitrix\Main\Engine\Contract\Controllerable, \Bitrix\Main\Errorable
{
	protected $action = null;
	protected $errorCollection = null;

	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->errorCollection = new \Bitrix\Main\ErrorCollection();
	}

	public function getErrors()
	{
		return $this->errorCollection->toArray();
	}

	public function getErrorByCode($code)
	{
		return $this->errorCollection->getErrorByCode($code);
	}

	public function configureActions()
	{
		return [];
	}

	protected function listKeysSignedParameters()
	{
		return ['USER_ID'];
	}

	public function onPrepareComponentParams($params)
	{
		$params['TITLE'] = isset($params['TITLE']) ? $params['TITLE'] : 'Новая заявка';

		return $params;
	}

	protected function getFields()
	{
		// подготовка списка полей
        return [
			[
				'ID' => 'ORDER_TITLE',
				'NAME' => GetMessage("ORDER_TITLE"),
				'TYPE' => 'text',
                'REQUIRED' => false,
                'COL_INDEX' => 2
			],
			[
				'ID' => 'CATEGORY',
				'NAME' => GetMessage('CATEGORY'),
				'TYPE' => 'radio',
                'VALUES' => [
                    ['value'=>'oil', 'label'=>GetMessage('CATEGORY1')],
                    ['value'=>'tyre', 'label'=>GetMessage('CATEGORY2')]
                ],
                'REQUIRED' => true,
                'COL_INDEX' => 8
			],
            [
                'ID' => 'ORDER_TYPE',
                'NAME' => GetMessage('ORDER_TYPE'),
                'TYPE' => 'radio',
                'VALUES' => [
                    ['value'=>'zapros', 'label'=>GetMessage('TYPE1')],
                    ['value'=>'popoln', 'label'=>GetMessage('TYPE2')],
                    ['value'=>'spez', 'label'=>GetMessage('TYPE3')]
                ],
                'REQUIRED' => true,
                'COL_INDEX' => 8
            ],
            [
                'ID' => 'WAREHOUSE',
                'NAME' => GetMessage('WAREHOUSE'),
                'TYPE' => 'select',
                'VALUES' => [
                    ['value'=>'w1', 'label'=>GetMessage('WAREHOUSE1')],
                    ['value'=>'w2', 'label'=>GetMessage('WAREHOUSE2')],
                ],
                'REQUIRED' => false,
                'COL_INDEX' => 2
            ],
            [
                'ID' => 'FILE',
                'NAME' => '',
                'TYPE' => 'file',
                'REQUIRED' => false,
                'COL_INDEX' => 4
            ],
            [
                'ID' => 'LINE',
                'NAME' => '',
                'TYPE' => 'line',
                'ITEMS' => [
                    [
                        'ID' => 'BRAND',
                        'NAME' => GetMessage('BRAND'),
                        'TYPE' => 'select',
                        'VALUES' => [
                            ['value'=>'b1', 'label'=>GetMessage('BRAND1')],
                            ['value'=>'b2', 'label'=>GetMessage('BRAND2')],
                        ],
                        'REQUIRED' => false,
                        'COL_INDEX' => 2
                    ],
                    [
                        'ID' => 'PRODUCTTITLE',
                        'NAME' => GetMessage('PRODUCT_TITLE'),
                        'TYPE' => 'text',
                        'REQUIRED' => false,
                        'COL_INDEX' => 2
                    ],
                    [
                        'ID' => 'QTY',
                        'NAME' => GetMessage('QTY'),
                        'TYPE' => 'text',
                        'REQUIRED' => false,
                        'COL_INDEX' => 2
                    ],
                    [
                        'ID' => 'FASHION',
                        'NAME' => GetMessage('FASHION'),
                        'TYPE' => 'text',
                        'REQUIRED' => false,
                        'COL_INDEX' => 2
                    ],
                    [
                        'ID' => 'CLIENT',
                        'NAME' => GetMessage('CLIENT'),
                        'TYPE' => 'text',
                        'REQUIRED' => false,
                        'COL_INDEX' => 2
                    ]
                ],
                'REQUIRED' => false,
                'COL_INDEX' => 12
            ],
            [
                'ID' => 'COMMENT',
                'NAME' => GetMessage('COMMENT'),
                'TYPE' => 'textarea',
                'REQUIRED' => false,
                'COL_INDEX' => 4
            ],
		];
	}

    public function saveFormAjaxAction()
	{
		// парсинг результата и отправка сообщения
        $response = [];
        $post = $this->request->getPostList()->toArray();

        if(!$post['CATEGORY']) {
            $response['error'] = 'категория ';
        }
        if(!$post['ORDER_TYPE']) {
            $response['error'] .= 'вид заявки ';
        }
        if($response['error']) {
            $response['error'] = "не заполнено поле:".$response['error'];
        }
        // так как по условиям задачи письмо идет в произвольном виде, не перечислял все параметры
        if(!$response) {
            \CEvent::Send(
                "ENQUIRY",
                SITE_ID,
                [
                    "ORDER_TITLE" => $_POST['ORDER_TITLE']
                ]
            );
        }


        return $response;
	}

	public function executeComponent()
	{
        global $APPLICATION;
        $this->arResult['FIELDS'] = $this->getFields();
        $APPLICATION->SetTitle( $this->arParams['TITLE']);
        $this->includeComponentTemplate();
    }
}