/**
 * Form success confetti: canvas burst in brand colours.
 * Exposes window.triggerFormSuccessConfetti(). Enqueued with consultation form.
 *
 * @package CCS_WP_Theme
 */

(function () {
	'use strict';

	var COLORS = ['#564298', '#7B63B8', '#3F2F70', '#fff'];
	var PARTICLE_COUNT = 60;
	var DURATION_MS = 2800;
	var GRAVITY = 0.35;
	var DRAG = 0.98;

	function randomIn(min, max) {
		return min + Math.random() * (max - min);
	}

	function createParticle() {
		var angle = randomIn(0, Math.PI * 2);
		var velocity = randomIn(8, 18);
		return {
			x: 0,
			y: 0,
			vx: Math.cos(angle) * velocity,
			vy: Math.sin(angle) * velocity - 4,
			color: COLORS[Math.floor(Math.random() * COLORS.length)],
			size: randomIn(4, 10),
			life: 1,
		};
	}

	function triggerFormSuccessConfetti() {
		var canvas = document.createElement('canvas');
		canvas.setAttribute('aria-hidden', 'true');
		canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999;';
		document.body.appendChild(canvas);

		var ctx = canvas.getContext('2d');
		var w = (canvas.width = window.innerWidth);
		var h = (canvas.height = window.innerHeight);
		var centerX = w / 2;
		var centerY = h * 0.4;
		var particles = [];
		for (var i = 0; i < PARTICLE_COUNT; i++) {
			var p = createParticle();
			p.x = centerX;
			p.y = centerY;
			particles.push(p);
		}

		var start = performance.now();
		function tick(now) {
			var elapsed = now - start;
			if (elapsed > DURATION_MS) {
				canvas.remove();
				return;
			}
			ctx.clearRect(0, 0, w, h);
			particles.forEach(function (p) {
				p.vx *= DRAG;
				p.vy += GRAVITY;
				p.vy *= DRAG;
				p.x += p.vx;
				p.y += p.vy;
				p.life = 1 - elapsed / DURATION_MS;
				if (p.life <= 0) return;
				ctx.globalAlpha = p.life;
				ctx.fillStyle = p.color;
				ctx.fillRect(p.x - p.size / 2, p.y - p.size / 2, p.size, p.size);
			});
			ctx.globalAlpha = 1;
			requestAnimationFrame(tick);
		}
		requestAnimationFrame(tick);
	}

	window.triggerFormSuccessConfetti = triggerFormSuccessConfetti;
})();
