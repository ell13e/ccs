/**
 * Weekly cost estimator on the Costs and funding page
 * (page-templates/template-costs.php).
 *
 * Multiplies the hours a family enters by the published daytime rates, which
 * the page prints into data-rates. An estimate only: nights, bank holidays and
 * the one-off set-up fee are left out, and the page says so beside the result.
 * Without JavaScript the estimator stays hidden and the rate table above
 * still answers the question.
 *
 * @package CCS_WP_Theme
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-ccs-estimator]');
	if (!root) return;

	var rates;
	try {
		rates = JSON.parse(root.getAttribute('data-rates') || '[]');
	} catch (e) {
		return;
	}
	if (!rates.length) return;

	var money = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' });
	var round = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP', maximumFractionDigits: 0 });

	var weekdayIn = root.querySelector('[name="est_weekday"]');
	var weekendIn = root.querySelector('[name="est_weekend"]');
	var out = {
		week: root.querySelector('[data-est="week"]'),
		month: root.querySelector('[data-est="month"]'),
		weekdayLine: root.querySelector('[data-est="weekday-line"]'),
		weekendLine: root.querySelector('[data-est="weekend-line"]')
	};

	function hours(input) {
		var v = parseFloat(input.value);
		if (isNaN(v) || v < 0) return 0;
		return Math.min(v, Number(input.max) || 98);
	}

	function kind() {
		var checked = root.querySelector('[name="est_kind"]:checked');
		var i = checked ? parseInt(checked.value, 10) : 0;
		return rates[i] || rates[0];
	}

	function update() {
		var r = kind();
		var wd = hours(weekdayIn);
		var we = hours(weekendIn);
		var weekdayCost = wd * r.weekday;
		var weekendCost = we * r.weekend;
		var week = weekdayCost + weekendCost;

		out.week.textContent = money.format(Math.round(week * 100) / 100);
		out.month.textContent = round.format(Math.round(week * 52 / 12));
		out.weekdayLine.textContent = wd + (wd === 1 ? ' hour' : ' hours') + ' at ' + money.format(r.weekday) + ' = ' + money.format(Math.round(weekdayCost * 100) / 100);
		out.weekendLine.textContent = we + (we === 1 ? ' hour' : ' hours') + ' at ' + money.format(r.weekend) + ' = ' + money.format(Math.round(weekendCost * 100) / 100);
	}

	// The − and + buttons step a field by one hour, within its own limits.
	root.addEventListener('click', function (e) {
		var btn = e.target.closest('[data-step]');
		if (!btn) return;
		var input = root.querySelector('#' + btn.getAttribute('aria-controls'));
		if (!input) return;
		var next = hours(input) + parseInt(btn.getAttribute('data-step'), 10);
		input.value = Math.max(0, Math.min(next, Number(input.max) || 98));
		update();
	});

	root.addEventListener('input', update);
	root.addEventListener('change', update);

	root.hidden = false;
	update();
})();
