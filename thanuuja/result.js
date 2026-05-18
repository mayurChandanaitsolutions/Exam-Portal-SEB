// ── Result Page JS ─ Enhanced ──
document.addEventListener('DOMContentLoaded', function () {
  // Animate donut
  const donut = document.getElementById('scoreDonut');
  const pct   = 75;
  let current = 0;
  const step  = () => {
    if (current >= pct) return;
    current += 1;
    donut.style.background = `conic-gradient(var(--emerald-500) 0% ${current}%, var(--gray-100) ${current}% 100%)`;
    requestAnimationFrame(step);
  };
  setTimeout(step, 300);

  window.viewAnalysis = function() {
    window.location.href = 'test-summary.html';
  };

  window.downloadResult = function() {
    alert('Download feature coming soon!');
  };
});

