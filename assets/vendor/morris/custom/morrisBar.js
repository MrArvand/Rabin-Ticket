// Morris Bar Chart
Morris.Bar({
	element: "morrisBar",
	data: [
		{ x: "1فروردین401", y: 2, z: 4, a: 2 },
		{ x: "مرداد 1402", y: 5, z: 3, a: 1 },
		{ x: "خرداد 1403", y: 2, z: 7, a: 4 },
		{ x: "اسفند 1403", y: 5, z: 6, a: 3 },
	],
	xkey: "x",
	ykeys: ["y", "z", "a"],
	labels: ["دهه سوم", "دهه دوم", "دهه اول"],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#575e6d",
	barColors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
});

setTimeout(function() {
    var labels = document.querySelectorAll('#morrisBar svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);