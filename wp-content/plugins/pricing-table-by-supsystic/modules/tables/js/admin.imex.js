!(function($){$(function () {

	var $root = jQuery('.supsystic-panel.import-export-panel')
	,	$exportTable = $root.find('#ptsPagesTbl')
	,	startPageSelector = $root.find('.supsystic-imex-tab-header-item.active').attr('data-to-page')
	,	$startPage = jQuery(startPageSelector);

	if ($startPage.length)
		$startPage.show();

	$root.on('click', '.imex-page-nav[data-to-page]', function () {
		var $this = jQuery(this)
		,	openPageSelector = $this.attr('data-to-page');

		$root.find('.supsystic-imex-tab-header-item.active').removeClass('active');

		$this.addClass('active');

		navigateTo(openPageSelector);
	});

	$root.find('#imex-export').click(function () {
		var selectedExportTable = [];

		$exportTable.find('.jqgrow[aria-selected="true"]').each(function () {
			var $this = jQuery(this)
			,	tableID = parseInt($this.find('[aria-describedby="ptsPagesTbl_id"]').text());

			if (tableID)
				selectedExportTable.push(tableID);
		});

		if (selectedExportTable.length) {
			jQuery.sendFormPts({
				data: {
					mod: 'tables'
				, 	action: 'getJSONExportTable'
            ,  pts_nonce: PTS_NONCE['pts_nonce']
				, 	tables: selectedExportTable
				}
			,	onSuccess: function(res) {
					var data = res.data.exportData
					,	$page = $root.find('.supsystic-imex-export-json-page');

					if (data) {
						$page.find('textarea').val(
							JSON.stringify(data)
						);

						navigateTo($page);

						$page.find('textarea').select();
					}
				}
			});
		}
	});

	$root.find('#imex-import').click(function () {
		var importJSON = $root.find('#imex-import-json').val()
		,	importDATA = null
		,	cbParentUpdateWithSameId = jQuery("#ptsUpdateWithSameId").parent()
		,	showIncorrectMessage = function () {
				var $message = $root.find('.supsystic-imex-import-page .message.errorFormat');

				$message.show(200);

				setTimeout(function() {
					$message.hide(200);
				}, 5000);
			}
		,	showSuccessMessage = function () {
				var $message = $root.find('.supsystic-imex-import-page .message.successAddedTable');

				$message.show(200);

				setTimeout(function() {
					$message.hide(200);
				}, 5000);
			};

		if (! importJSON.length) return;

		try {
			importDATA = JSON.parse(importJSON);
		} catch (ex) {
			showIncorrectMessage();

			return;
		}

		if (typeof importDATA != 'object' || !Array.isArray(importDATA)) {
			showIncorrectMessage();

			return;
		}

		jQuery.sendFormPts({
			data: {
				mod: 'tables'
			, 	action: 'importJSONTable'
         ,  pts_nonce: PTS_NONCE['pts_nonce']
			, 	data: importDATA
			,	update_with_same_id: jQuery('#ptsUpdateWithSameId').prop("checked") ? 1 : 0
			}
		,	onSuccess: function(res) {
				if (res.error) {
					showIncorrectMessage();

					return;
				}

				if (res.data.success) {
					showSuccessMessage();

					$root.find('#imex-import-json').val('');
				}
			}
		});

	});

	function navigateTo(element) {
		var $openPage = null;

		if (element instanceof jQuery)
			$openPage = element;
		else
			$openPage = jQuery(element);

		$root.find('.supsystic-imex-page').hide();

		$openPage.show();
	}
});})(jQuery);
