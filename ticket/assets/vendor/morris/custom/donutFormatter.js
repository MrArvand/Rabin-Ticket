Morris.Donut({
	element: "donutFormatter",
	data: [
		{ value: 155, label: "خرید", formatted: "حداقل 70%" },
		{ value: 12, label: "درخواست", formatted: "تقریبا 15%" },
		{ value: 10, label: "ارسال شده", formatted: "تقریبا 10%" },
		{ value: 5, label: "این یک لیبل طولانی است", formatted: "حداکثر 5%" },
	],
	resize: true,
	hideHover: "auto",
	formatter: function (x, data) {
		return data.formatted;
	},
	backgroundColor: "#2a3039",
	labelColor: "#95a0b1",
	colors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
});

setTimeout(function() {
    var labels = document.querySelectorAll('#donutFormatter svg text');
    labels.forEach(function(label) {
        label.classList.add('morris-chart-month-label');
    });
}, 100);