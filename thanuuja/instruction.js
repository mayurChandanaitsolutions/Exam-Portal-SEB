// ── Instructions Page JS ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  const checkbox   = document.getElementById('confirmCheck');
  const proceedBtn = document.getElementById('proceedBtn');

  checkbox.addEventListener('change', function () {
    proceedBtn.disabled = !this.checked;
  });

  proceedBtn.addEventListener('click', function () {
    if (checkbox.checked) {
      window.location.href = 'exam-start.html';
    }
  });
});
