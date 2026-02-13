/**
 * Enquiry list: Quick View modal, Quick Status dropdown (AJAX update).
 * Expects window.ccsEnquiryManager: { ajaxUrl, nonce, strings }
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function($) {
	'use strict';

	var config = window.ccsEnquiryManager || {};

	// Quick View modal
	$(document).on('click', '.ccs-quick-view', function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		if (!id) return;
		var $modal = $('#ccs-quick-view-modal');
		$modal.removeAttr('hidden').show();
		$modal.find('.ccs-qv-body').html('<p>Loading…</p>');
		$.get(config.ajaxUrl, {
			action: 'ccs_enquiry_quick_view',
			nonce: config.nonce,
			id: id
		}).done(function(res) {
			if (res && res.success && res.data && res.data.html) {
				$modal.find('.ccs-qv-body').html(res.data.html);
			} else {
				$modal.find('.ccs-qv-body').html('<p>Could not load.</p>');
			}
		}).fail(function() {
			$modal.find('.ccs-qv-body').html('<p>Error loading.</p>');
		});
	});

	$(document).on('click', '.ccs-qv-close, .ccs-qv-backdrop', function() {
		$('#ccs-quick-view-modal').attr('hidden', true).hide();
	});

	$(document).on('keydown', function(e) {
		if (e.key === 'Escape') {
			$('#ccs-quick-view-modal').attr('hidden', true).hide();
		}
	});

	// Quick Status: click badge to show select, change triggers AJAX
	$(document).on('click', '.ccs-status-badge', function(e) {
		e.preventDefault();
		var $wrap = $(this).closest('.ccs-status-wrap');
		var $sel = $wrap.find('.ccs-status-select');
		if ($sel.hasClass('hide')) {
			$('.ccs-status-select').addClass('hide');
			$sel.removeClass('hide');
		} else {
			$sel.addClass('hide');
		}
	});

	$(document).on('change', '.ccs-status-select', function() {
		var $sel = $(this);
		var $wrap = $sel.closest('.ccs-status-wrap');
		var id = $wrap.data('id');
		var status = $sel.val();
		var $badge = $wrap.find('.ccs-status-badge');
		$sel.addClass('hide');
		$badge.prop('disabled', true).text('…');

		$.post(config.ajaxUrl, {
			action: 'ccs_enquiry_quick_status',
			nonce: config.nonce,
			id: id,
			status: status
		}).done(function(res) {
			if (res && res.success && res.data && res.data.label) {
				$badge.removeClass().addClass('ccs-status-badge ccs-status-' + status).text(res.data.label);
			}
		}).always(function() {
			$badge.prop('disabled', false);
		}).fail(function() {
			$badge.text($badge.data('prev') || '—');
		});
	});

	// Store previous label before change for fail fallback
	$(document).on('focus', '.ccs-status-select', function() {
		$(this).closest('.ccs-status-wrap').find('.ccs-status-badge').data('prev',
			$(this).closest('.ccs-status-wrap').find('.ccs-status-badge').text());
	});
})(jQuery);
