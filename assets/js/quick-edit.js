/**
 * Pré-remplit les champs ACF dans la modification rapide des évènements.
 *
 * Les valeurs brutes sont injectées par ligne dans #stunfest-qe-<ID> (JSON),
 * cf. EvenementQuickEdit::renderRowData().
 */
( function ( $ ) {
	'use strict';

	if ( ! window.inlineEditPost || typeof window.inlineEditPost.edit !== 'function' ) {
		return;
	}

	const inlineEdit = window.inlineEditPost;
	const originalEdit = inlineEdit.edit;

	inlineEdit.edit = function ( id ) {
		originalEdit.apply( this, arguments );

		let postId = 0;

		if ( typeof id === 'object' ) {
			postId = parseInt( this.getId( id ), 10 );
		}

		if ( ! postId ) {
			return;
		}

		const dataEl = document.getElementById( 'stunfest-qe-' + postId );

		if ( ! dataEl ) {
			return;
		}

		let data;

		try {
			data = JSON.parse( dataEl.textContent );
		} catch ( e ) {
			return;
		}

		const $row = $( '#edit-' + postId );

		Object.keys( data ).forEach( function ( name ) {
			const $field = $row.find( '[name="stunfest_qe_' + name + '"]' );

			if ( ! $field.length ) {
				return;
			}

			if ( $field.attr( 'type' ) === 'checkbox' ) {
				$field.prop( 'checked', parseInt( data[ name ], 10 ) === 1 );
			} else {
				$field.val( data[ name ] );
			}
		} );
	};
} )( jQuery );
