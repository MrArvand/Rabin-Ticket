

// Morris Bar Colors
Morris.Bar({
	element: "barColors",
	data: [
		{ x: "ژانویه", Sales: 6 },
		{ x: "فوریه", Sales: 1 },
		{ x: "مارس", Sales: 2 },
		{ x: "آوریل", Sales: 3 },
		{ x: "می", Sales: 2 },
		{ x: "ژوئن", Sales: 4 },
		{ x: "جولای", Sales: 7 },
		{ x: "آگوست", Sales: 2 },
		{ x: "سپتامبر", Sales: 5 },
		{ x: "اکتبر", Sales: 9 },
		{ x: "موامبر", Sales: 3 },
		{ x: "دسامبر", Sales: 5 },
	],
	xkey: "x",
	ykeys: ["Sales"],
	labels: ["Sales"],
	resize: true,
	gridLineColor: "#575e6d",
	hideHover: "auto",
	labelFontFamily: 'IranYekanNum',
	barColors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
});

setTimeout(function() {
    var labels = document.querySelectorAll('#barColors svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);
