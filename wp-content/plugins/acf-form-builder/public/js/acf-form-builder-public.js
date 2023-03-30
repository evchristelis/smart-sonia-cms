(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).on('ready', function () {
		// Config toggle column
		$('#table-control').append('<div id="toggle-column">Toggle column: |</div>');
		$('.table_datas tfoot th').each(function (i, e) {
			var title = $(this).text();
			if (title == '') {
				return true;
			}

			if (this.id != 'nosearch') {
				$(this).html('<input type="text" placeholder="Search ' + title + '" />');
			} else {
				$(this).html('');
			}
			$('#toggle-column').append(' <a class="toggle-vis" data-column="' + i + '">' + title + '</a> |');
		});

		// Init table
		var table = $('.table_datas').DataTable({
			ordering: true,
			"columnDefs": [{
				"searchable": false,
				"orderable": false,
				"targets": 0
			}],
			"order": [
				[1, 'asc']
			],
			"length": 10,
			"initComplete": function () {
				var api = this.api();			

				api.$('.delete-post').on('click', function (e) {
					var tr = $(this).closest('tr');
					var row = api.row(tr);
					
					e.preventDefault();
					var datas = {
						post_id: $(this).data('id'),
					};

					$('#dialog-confirm').attr('title', acfbpData.translated.delete_confirm_title);
					$('#dialog-confirm p').text(acfbpData.translated.delete_confirm_content);
					$('#dialog-confirm').dialog({
						resizable: false,
						height: "auto",
						width: 400,
						modal: true,
						buttons: {
							'Delete!': function () {
								$('#dialog-confirm p').html(acfbpData.translated.deleting);
								$.post(
									acfbpData.ajax_url, {
										action: 'acf_fb_ajax_delete_post',
										data: datas
									},
									function (response) {
										if (response.status == 'success') {
											row.remove().draw( false );
											$('#dialog-confirm').dialog('close');
										} else {
											$('#dialog-confirm p').html(acfbpData.translated.delete_fail);
											$('#dialog-confirm').effect('shake');
										}
									},
									'json'
								);
							},
							Cancel: function () {
								$(this).dialog('close');
							}
						}
					});
				});
			}
		});

		// Highlighting rows and columns
		$('.table_datas tbody').on('mouseenter', 'td', function () {
			var colIdx = table.cell(this).index().column;

			$(table.cells().nodes()).removeClass('highlight');
			$(table.column(colIdx).nodes()).addClass('highlight');
		});

		// Index column
		table.on('order.dt search.dt', function () {
			table.column(0, {
				search: 'applied',
				order: 'applied'
			}).nodes().each(function (cell, i) {
				$(cell).css('width', '20px');
				cell.innerHTML = i + 1;
			});
		}).draw();

		// Search column
		table.columns().every(function () {
			var that = this;
			$('input', this.footer()).on('keyup change', function () {
				if (that.search() !== this.value) {
					that
						.search(this.value)
						.draw();
				}
			});
		});

		// Toggle column
		$('a.toggle-vis').on('click', function (e) {
			e.preventDefault();

			// Get the column API object
			var column = table.column($(this).attr('data-column'));

			// Toggle the visibility
			column.visible(!column.visible());
		});
	});
})(jQuery);