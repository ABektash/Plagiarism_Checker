const plagiarismCtx = document
  .getElementById("plagiarismChart")
  .getContext("2d");
const plagiarismChart = new Chart(plagiarismCtx, {
  type: "line",
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        data: [15, 10, 18, 20, 22, 25, 19, 18, 23, 26, 20, 24],
        borderColor: "rgba(255, 99, 132, 1)",
        backgroundColor: "rgba(255, 99, 132, 0.2)",
        fill: true,
        tension: 0.3,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
    },
    gridline: {},
    scales: {
      x: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
      },
      y: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
        beginAtZero: true,
      },
    },
  },
});

const engagementCtx = document
  .getElementById("engagementChart")
  .getContext("2d");
const engagementChart = new Chart(engagementCtx, {
  type: "line",
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        data: [300, 320, 310, 330, 340, 350, 360, 370, 380, 390, 400, 450],
        borderColor: "rgba(54, 162, 235, 1)",
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        fill: true,
        tension: 0.3,
      },
    ],
  },
  options: {
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    scales: {
      x: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
      },
      y: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
        beginAtZero: true,
      },
    },
  },
});

const submissionsCtx = document
  .getElementById("submissionsChart")
  .getContext("2d");
const submissionsChart = new Chart(submissionsCtx, {
  type: "line",
  data: {
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    datasets: [
      {
        data: [200, 220, 210, 240, 250, 260, 270, 280, 290, 300, 310, 300],
        borderColor: "rgba(255, 206, 86, 1)",
        backgroundColor: "rgba(255, 206, 86, 0.2)",
        fill: true,
        tension: 0.3,
      },
    ],
  },
  options: {
    plugins: {
      legend: {
        display: false,
      },
    },
    responsive: true,
    scales: {
      x: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
      },
      y: {
        grid: {
          color: getComputedStyle(document.documentElement).getPropertyValue(
            "--grid-line-color"
          ),
        },
        beginAtZero: true,
      },
    },
  },
});
