// ── Exam-Start Countdown JS ───────────────────────────────────
var targetTime = new Date();
targetTime.setHours(9, 0, 0, 0);
if (new Date() >= targetTime) targetTime.setDate(targetTime.getDate() + 1);

function pad(n) { return n < 10 ? '0' + n : '' + n; }

function updateCountdown() {
  var now  = Date.now();
  var diff = targetTime - now;

  if (diff <= 0) {
    ['cd-hours','cd-mins','cd-secs'].forEach(id => document.getElementById(id).textContent = '00');
    document.getElementById('headerTimer').textContent = 'STARTING!';
    setTimeout(() => window.location.href = 'stuexam.html', 1500);
    return;
  }

  var h = Math.floor(diff / 3600000);
  var m = Math.floor((diff % 3600000) / 60000);
  var s = Math.floor((diff % 60000) / 1000);

  document.getElementById('cd-hours').textContent = pad(h);
  document.getElementById('cd-mins').textContent  = pad(m);
  document.getElementById('cd-secs').textContent  = pad(s);
  document.getElementById('headerTimer').textContent = pad(h)+':'+pad(m)+':'+pad(s);
}

updateCountdown();
setInterval(updateCountdown, 1000);

function startExamNow() {
  window.location.href = 'stuexam.html';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') window.location.href = 'instuction.html';
});
