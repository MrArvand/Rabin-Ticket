options = {
	series: [
		{
			name: "آمریکا",
			data: [
				{
					x: "سطح 1",
					y: 27,
				},
				{
					x: "سطح 2",
					y: 36,
				},
				{
					x: "سطح 3",
					y: 25,
				},
				{
					x: "سطح 4",
					y: 32,
				},
			],
		},
		{
			name: "هند",
			data: [
				{
					x: "سطح 1",
					y: 43,
				},
				{
					x: "سطح 2",
					y: 35,
				},
				{
					x: "سطح 3",
					y: 26,
				},
				{
					x: "سطح 4",
					y: 55,
				},
			],
		},
		{
			name: "برزیل",
			data: [
				{
					x: "سطح 1",
					y: 28,
				},
				{
					x: "سطح 2",
					y: 32,
				},
				{
					x: "سطح 3",
					y: 16,
				},
				{
					x: "سطح 4",
					y: 22,
				},
			],
		},
		{
			name: "اندونزی",
			data: [
				{
					x: "سطح 1",
					y: 32,
				},
				{
					x: "سطح 2",
					y: 21,
				},
				{
					x: "سطح 3",
					y: 20,
				},
				{
					x: "سطح 4",
					y: 46,
				},
			],
		},
	],
	legend: {
		show: false,
	},
	chart: {
		height: 300,
		type: "heatmap",
		fontFamily: 'IranYekanNum',
		toolbar: {
			show: false,
		},
	},
	stroke: {
		width: 0,
	},
	colors: [
		"#3c92b1",
		"#50596a",
		"#bf7a6a",
		"#a9bd7a",
		"#6fb4ce",
		"#d2a968",
		"#9dabbb",
	],
	tooltip: {
		y: {
			formatter: function (val) {
				return "$" + val + " Million";
			},
		},
	},
};

var chart = new ApexCharts(document.querySelector("#heatmap"), options);
chart.render();
