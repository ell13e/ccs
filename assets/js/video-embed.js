/**
 * Click-to-load YouTube embed: swaps a static poster for the real iframe only
 * once someone actually presses play, using youtube-nocookie.com so no
 * tracking cookie is set before then.
 *
 * @package CCS_WP_Theme
 */

(function () {
	'use strict';

	function run() {
		var embeds = document.querySelectorAll('.video-embed');
		embeds.forEach(function (embed) {
			var trigger = embed.querySelector('.video-embed__trigger');
			if (!trigger) return;
			trigger.addEventListener('click', function () {
				load(embed);
			});
		});
	}

	function load(embed) {
		var videoId = embed.getAttribute('data-video-id');
		var title = embed.getAttribute('data-video-title') || 'Video';
		if (!videoId) return;

		var iframe = document.createElement('iframe');
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&rel=0';
		iframe.title = title;
		iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
		iframe.setAttribute('allowfullscreen', '');
		iframe.className = 'video-embed__iframe';

		embed.innerHTML = '';
		embed.appendChild(iframe);
		iframe.focus();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', run);
	} else {
		run();
	}
})();
