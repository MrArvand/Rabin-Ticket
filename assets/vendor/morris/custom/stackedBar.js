// Stacked Bar Chart
Morris.Bar({
	element: "stackedBar",
	data: [
		{ x: "1فروردین401", y: 3, z: 2, a: 3 },
		{ x: "1فروردین402", y: 2, z: null, a: 1 },
		{ x: "مرداد 1402", y: 0, z: 2, a: 1 },
		{ x: "اردیبهشت 1403", y: 2, z: 3, a: 3 },
		{ x: "خرداد 1403", y: 3, z: 2, a: 3 },
		{ x: "بهمن 14003", y: 2, z: null, a: 1 },
		{ x: "اسفند1403", y: 0, z: 2, a: 4 },
		{ x: "اسفند 1403", y: 2, z: 3, a: 3 },
	],
	xkey: "x",
	ykeys: ["y", "z", "a"],
	labels: ["دهه سوم", "دهه دوم", "دهه اول"],
	stacked: true,
	hideHover: "auto",
	resize: true,
	gridLineColor: "#575e6d",
	barColors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
});

setTimeout(function() {
    var labels = document.querySelectorAll('#stackedBar svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);
