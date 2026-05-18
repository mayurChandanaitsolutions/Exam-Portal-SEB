// ── Rating Page JS ─ Average Main Stars ──
const ratingLabels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' };

function updateMainAverage() {
  const ratings = ['difficulty', 'timing', 'quality', 'overall'];
  let total = 0, count = 0;
  ratings.forEach(group => {
    const checked = document.querySelector(`input[name="${group}"]:checked`);
    if (checked) {
      total += parseInt(checked.value);
      count++;
    }
  });
  const average = count > 0 ? Math.round(total / count) : 0;
  const mainStars = document.getElementById('mainStars');
  mainStars.querySelectorAll('input').forEach(r => r.checked = false);
  if (average > 0) {
    mainStars.querySelector(`#s${average}`).checked = true;
  }
}

// Listen to detailed ratings
['difficulty', 'timing', 'quality', 'overall'].forEach(group => {
  document.querySelectorAll(`input[name="${group}"]`).forEach(radio => {
    radio.addEventListener('change', updateMainAverage);
  });
});

function submitRating() {
  // Check all 4 detailed ratings are given
  const ratings = ['difficulty', 'timing', 'quality', 'overall'];
  let completeCount = 0;
  ratings.forEach(group => {
    if (document.querySelector(`input[name="${group}"]:checked`)) completeCount++;
  });
  if (completeCount < 4) {
    alert(`Please complete all ${4 - completeCount} remaining rating(s).`);
    return;
  }
  const stars     = parseInt(document.querySelector('#mainStars input:checked').value);
  const starsText = '★'.repeat(stars) + '☆'.repeat(5 - stars);
  document.getElementById('tyStars').textContent = starsText;
  document.getElementById('ratingForm').style.display = 'none';
  document.getElementById('thankYouBlock').style.display = 'block';
  
// Manual redirect only on View Results button click - no auto
  // setTimeout removed per feedback
}

