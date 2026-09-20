document.addEventListener('DOMContentLoaded', () => {
  animateCounters();

  const ctxSales = document.getElementById('salesChart');
  const ctxDoughnut = document.getElementById('cashChart');

  const monthlyValues = [1200, 1550, 1380, 1680, 2100, 1980, 2250, 2470, 2320, 2710, 2650, 2990];
  const cashValues = [72, 28];

  if (ctxSales) {
    new Chart(ctxSales, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
          label: 'Comissão',
          data: monthlyValues,
          backgroundColor: ['#0d6efd', '#2c7be5', '#5a8dee', '#7da6f7', '#4f9ef7', '#8dd3ff', '#5bc0de', '#3ab0e6', '#7ec8e3', '#4ca6ff', '#6ea8fe', '#0d6efd'],
          borderRadius: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => 'R$ ' + Number(value).toLocaleString('pt-BR')
            }
          }
        }
      }
    });
  }

  if (ctxDoughnut) {
    new Chart(ctxDoughnut, {
      type: 'doughnut',
      data: {
        labels: ['Entradas', 'Saídas'],
        datasets: [{
          data: cashValues,
          backgroundColor: ['#198754', '#dc3545'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }
});

function animateCounters() {
  document.querySelectorAll('[data-counter]').forEach((element) => {
    const target = Number(element.dataset.counter || 0);
    const decimals = Number(element.dataset.decimals || 0);
    const prefix = element.dataset.prefix || '';
    const suffix = element.dataset.suffix || '';

    const start = performance.now();

    function update(now) {
      const progress = Math.min((now - start) / 700, 1);
      const current = target * progress;
      element.textContent = prefix + formatValue(current, decimals) + suffix;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        element.textContent = prefix + formatValue(target, decimals) + suffix;
      }
    }

    requestAnimationFrame(update);
  });
}

function formatValue(value, decimals) {
  return Number(value).toLocaleString('pt-BR', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  });
}
