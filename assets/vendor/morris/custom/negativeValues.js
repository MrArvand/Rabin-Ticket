// Morris Negative values
var neg_data = [
	{ period: "1402-02-12", a: 100 },
	{ period: "1402-01-03", a: 75 },
	{ period: "1403-08-08", a: 50 },
	{ period: "1403-05-10", a: 25 },
	{ period: "1403-03-14", a: 0 },
	{ period: "1403-01-10", a: -25 },
	{ period: "1400-12-10", a: -50 },
	{ period: "1400-10-07", a: -75 },
	{ period: "1400-09-25", a: -100 },
];
Morris.Line({
	element: "negativeValues",
	data: neg_data,
	xkey: "period",
	ykeys: ["a"],
	labels: ["بخش اول"],
	units: "%",
	resize: true,
	hideHover: "auto",
	gridLineColor: "#575e6d",
	pointFillColors: ["#6fb4ce"],
	pointStrokeColors: [
		"#30758e",
		"#3c92b1",
		"#63a8c1",
		"#8abed0",
		"#b1d3e0",
		"#c5dee8",
	],
	lineColors: [
		"#30758e",
		"#3c92b1",
		"#63a8c1",
		"#8abed0",
		"#b1d3e0",
		"#c5dee8",
	],
});
setTimeout(function() {
    var labels = document.querySelectorAll('#negativeValues svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);
