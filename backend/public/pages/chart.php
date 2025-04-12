<?hh // strict

namespace Pages;

function render_chart(): void {
  \header('Content-Type: text/html');

  echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hack Chart</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h1>Chart Dashboard</h1>
  <canvas id="myChart" width="400" height="200"></canvas>

  <script>
    fetch('/api/data')
      .then(res => res.json())
      .then(data => {
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Example Values',
              data: data.values,
              backgroundColor: 'rgba(54, 162, 235, 0.5)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      });
  </script>
</body>
</html>
HTML;
}
