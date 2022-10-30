(function() {
	'use strict';

	/**
	 * @namespace BX.Custom.Form
	 */
	BX.namespace('BX.Custom.Form');

	BX.Custom.Form.Edit = function(options)
	{
		this.params = options.params || {};
		this.signedParameters = options.signedParameters;
		this.componentName = options.componentName;
		this.bindEvents();
	};

	BX.Custom.Form.Edit.prototype =
		{
			saveClickHandler: function(event)
			{
				// отправка формы
				event.preventDefault();

				var form = BX('custom_form');

				BX.ajax.runComponentAction(
					this.componentName,
					'saveFormAjax',
					{
						mode: 'class',
						signedParameters: this.signedParameters,
						data: new FormData(form)
					}
				)
					.then(function (response) {
						console.log(response)
						if(response.data.hasOwnProperty('error')) {
							alert("Ошибка:" + response.data.error)
						} else {
							alert("Данные сохранены")
							window.location.reload()
						}
					}.bind(this))
					.catch(function (response) {
						console.log(response)
					}.bind(this));
			},
			// добавление строки
			addHandler: function(event)
			{
				let sample = $("#ROW-SAMPLE")
				let header = $("#ROW-LINE")
				let lines = $(".samplecopy").length + 2;
				let clone = sample.clone();
				clone.addClass('samplecopy');
				clone.children().last().remove();
				clone.find('.specline').each(function() {
					let name = $(this).attr("name");
					let newname = name.replace(/[^a-zA-Z]+/g, '');
					$(this).attr("name", newname+'-'+lines);
				});
				clone.appendTo(header)
			},
			// удаление строки
			delHandler: function(event) {
				$('.samplecopy:last').remove();
			},

			bindEvents: function()
			{
				BX.bind(BX('SUBMIT_BUTTON'), 'click', BX.proxy(this.saveClickHandler, this));
				BX.bind(BX('ADD_LINE'), 'click', BX.proxy(this.addHandler, this));
				BX.bind(BX('DEL_LINE'), 'click', BX.proxy(this.delHandler, this));
			}
		};
})();
