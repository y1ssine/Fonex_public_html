jQuery( function ( $ ) {
	'use strict';

	$( '.theme-picker' ).on( 'click', function () {
		var themeid = $( this ).data( 'themeid' );
		$( '.wpss-theme-picker-wrap .theme .button-wrap' ).removeClass( 'selected' );
		$( this ).parent().addClass( 'selected' );
		if ( themeid === 'all-themes' ) {
			$( '.demo-preview' ).removeClass( 'hidden' );
		} else {
			$( '.demo-preview' ).removeClass( 'hidden' );
			$( '.demo-preview:not(.' + themeid + ')' ).addClass( 'hidden' );
		}
	});

	$( '.js-wpss-import-data' ).on( 'click', function () {

		// Reset response div content.
		$( '.js-wpss-ajax-response' ).empty();

		var siteid = $( this ).data( 'siteid' );

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'WPSS_import_demo_data' );
		data.append( 'security', wpss.ajax_nonce );
		data.append( 'selected', siteid );

		// AJAX call to import everything (content, widgets, before/after setup)
		ajaxCall( data );

	});

	function ajaxCall( data ) {
		$.ajax({
			method:     'POST',
			url:        wpss.ajax_url,
			data:       data,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$( '.js-wpss-ajax-loader' ).show();
			}
		})
		.done( function( response ) {

			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				ajaxCall( data );
			}
			else if ( 'undefined' !== typeof response.message ) {
				$( '.js-wpss-ajax-response' ).append( '<p>' + response.message + '</p>' );
				$( '.js-wpss-ajax-loader' ).hide();
				$( '.WPSS__response' ).addClass( 'display' );
				$( document ).scrollTop( $( '#wpwrap' ).offset().top );
			}
			else {
				$( '.js-wpss-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>' + response + '</p></div>' );
				$( '.js-wpss-ajax-loader' ).hide();
				$( '.WPSS__response' ).addClass( 'display' );
				$( document ).scrollTop( $( '#wpwrap' ).offset().top );
			}
		})
		.fail( function( error ) {
			$( '.js-wpss-ajax-response' ).append( '<div class="notice  notice-error  is-dismissible"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
			$( '.js-wpss-ajax-loader' ).hide();
			$( '.WPSS__response' ).addClass( 'display' );
			$( document ).scrollTop( $( '#wpwrap' ).offset().top );
		});
	}

	// Switch preview images on select change event, but only if the img element .js-wpss-preview-image exists.
	// Also switch the import notice (if it exists).
	$( '#WPSS__demo-import-files' ).on( 'change', function(){
		if ( $( '.js-wpss-preview-image' ).length ) {

			// Attempt to change the image, else display message for missing image.
			var currentFilePreviewImage = wpss.import_files[ this.value ]['import_preview_image_url'] || '';
			$( '.js-wpss-preview-image' ).prop( 'src', currentFilePreviewImage );
			$( '.js-wpss-preview-image-message' ).html( '' );

			if ( '' === currentFilePreviewImage ) {
				$( '.js-wpss-preview-image-message' ).html( wpss.texts.missing_preview_image );
			}
		}

		// Update import notice.
		var currentImportNotice = wpss.import_files[ this.value ]['import_notice'] || '';
		$( '.js-wpss-demo-import-notice' ).html( currentImportNotice );
	});

});
