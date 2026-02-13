/**
 * CCS admin dashboard: init Chart.js line chart for enquiries over time.
 * Expects window.ccsDashboard: { chartLabels, chartCounts }
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

(function() {
	'use strict';

	var config = window.ccsDashboard || {};
	var labels = config.chartLabels || [];
	var counts = config.chartCounts || [];

	var canvas = document.getElementById('ccs-enquiries-chart');
	if (!canvas || typeof Chart === 'undefined') return;

	var ctx = canvas.getContext('2d');
	new Chart(ctx, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [{
				label: 'Enquiries',
				data: counts,
				borderColor: '#564299',
				backgroundColor: 'rgba(86, 66, 153, 0.1)',
				borderWidth: 2,
				fill: true,
				tension: 0.2,
				pointRadius: 2,
				pointHoverRadius: 4
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: true,
			aspectRatio: 2.5,
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						stepSize: 1,
						precision: 0
					}
				},
				x: {
					ticks: {
						maxTicksLimit: 15,
						maxRotation: 45
					}
				}
			}
		}
	});
})();
