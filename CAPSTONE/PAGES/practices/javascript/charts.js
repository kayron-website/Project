const barChartOptions = {
  series: [
    {
      data: [0, 0, 1, 0, 0, 0, 0],
      name: 'Schedules',
    },
  ],
  chart: {
    type: 'bar',
    background: 'transparent',
    height: 250,
    toolbar: {
      show: false,
    },
  },
  colors: ['#ED5564', '#AC92EB', '#4FC1E8', '#A0D568', '#FFCE54', '#ED5564', '#4FC1E8'],
  plotOptions: {
    bar: {
      distributed: true,
      borderRadius: 4,
      horizontal: false,
      columnWidth: '60%',
    },
  },
  dataLabels: {
    enabled: true,
  },
  fill: {
    opacity: 1,
  },
  grid: {
    borderColor: '#000',
    yaxis: {
      lines: {
        show: true,
      },
    },
    xaxis: {
      lines: {
        show: true,
      },
    },
  },
  legend: {
    labels: {
      colors: '#000',
    },
    show: true,
    position: 'top',
  },
  stroke: {
    colors: ['transparent'],
    show: true,
    width: 2,
  },
  tooltip: {
    shared: true,
    intersect: false,
    theme: 'dark',
  },
  xaxis: {
    categories: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    title: {
      style: {
        color: '#000',
      },
    },
    axisBorder: {
      show: true,
      color: '#000',
    },
    axisTicks: {
      show: true,
      color: '#000',
    },
    labels: {
      style: {
        colors: '#000',
      },
    },
  },
  yaxis: {
    title: {
      style: {
        color: '#000',
      },
    },
    axisBorder: {
      color: '#000',
      show: true,
    },
    axisTicks: {
      color: '#000',
      show: true,
    },
    labels: {
      style: {
        colors: '#000',
      },
    },
  },
};

const barChart = new ApexCharts(
  document.querySelector('#bar-chart'),
  barChartOptions
);
barChart.render();

const areaChart = new ApexCharts(
  document.querySelector('#area-chart'),
  areaChartOptions
);
areaChart.render();