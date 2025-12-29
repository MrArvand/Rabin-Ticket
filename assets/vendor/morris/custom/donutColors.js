// Morris Donut
Morris.Donut({
	element: "donutColors",
	data: [
		{ value: 30, label: "خرید" },
		{ value: 15, label: "درخواست" },
		{ value: 10, label: "ارسال شده" },
		{ value: 5, label: "این یک برچسب طولانی است" },
	],
	backgroundColor: "#2a3039",
	labelColor: "#95a0b1",
	colors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
	resize: true,
	hideHover: "auto",
	gridLineColor: "#575e6d",
	formatter: function (x) {
		return x + "%";
	},
});

setTimeout(function() {
    var labels = document.querySelectorAll('#donutColors svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);