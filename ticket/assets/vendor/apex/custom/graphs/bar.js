var options = {
	chart: {
		height: 300,
		type: "bar",
		fontFamily: 'IranYekanNum',
		toolbar: {
			show: false,
		},
		rtl:true,
	},
	dataLabels: {
		enabled: false,
	},
	stroke: {
		curve: "smooth",
		width: 3,
	},
	series: [
		{
			name: "فروش",
			data: [10, 40, 15, 40, 20, 35, 20, 10, 31, 43, 56, 29],
		},
		{
			name: "درآمد",
			data: [2, 8, 25, 7, 20, 20, 51, 35, 42, 20, 33, 67],
		},
	],
	grid: {
		borderColor: "#575e6d",
		strokeDashArray: 5,
		xaxis: {
			lines: {
				show: true,
			},
		},
		yaxis: {
			lines: {
				show: false,
			},
		},
		padding: {
			top: 0,
			right: 0,
			bottom: 10,
			left: 0,
		},
	},
	xaxis: {
		categories: [
			"فروردین",
			"اردیبهشت",
			"خرداد",
			"تیر",
			"مرداد",
			"شهریور",
			"مهر",
			"آبان",
			"آذر",
			"دی",
			"بهمن",
			"اسفند",
		],
		labels: {
            style: {
                fontSize: '12px',
            },
            offsetY: 30,  // اضافه کردن فاصله بین نام ماه‌ها و ستون‌ها
        },
	},
	yaxis: {
		labels: {
			show: false,
		},
	},
	colors: ["#6fb4ce", "#bf7a6a"],
	markers: {
		size: 0,
		opacity: 0.3,
		colors: ["#6fb4ce", "#bf7a6a"],
		strokeColor: "#ffffff",
		strokeWidth: 2,
		hover: {
			size: 7,
		},
	},
	tooltip: {
        theme: 'dark', // تنظیم تم راست‌چین
        x: {
            show: true
        },
        y: {
            formatter: function (value) {
                return value;
            }
        }
    },
	legend: {
        horizontalAlign: 'right', // تنظیم راست‌چین بودن افقی
        position: 'top', // تنظیم موقعیت افقی
        markers: {
            width: 10,
            height: 10,
            radius: 12,
            offsetX: 5,
            offsetY: 0
        },
        itemMargin: {
            horizontal: 10, // فاصله بین کلمات "فروش" و "درآمد"
            vertical: 0
        },
        fontSize: '14px',
    }
};

var chart = new ApexCharts(document.querySelector("#barGraph"), options);

chart.render();
