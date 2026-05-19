// ── Exam JS ─────────────────────────────────────────────────
const questions = [
  {
    id: 1, section: 'General Intelligence & Reasoning',
    eng: 'Which one set of letters when sequentially placed at the gaps in the given letter series shall complete it?\n_ a _ aaaba _ _ ba _ ab _',
    hindi: 'दी गई अक्षर श्रृंखला में अंतराल पर क्रमशः रखने पर कौन सा अक्षर सेट इसे पूरा करेगा?\n_ a _ aaaba _ _ ba _ ab _',
    options: ['abaaaa', 'abababa', 'aababa', 'ababaa'],
    answer: null
  },
  {
    id: 2, section: 'General Intelligence & Reasoning',
    eng: "In a certain code language, 'ROAD' is written as 'DNWP'. How is 'SWAN' written in that code?",
    hindi: "किसी निश्चित कोड भाषा में 'ROAD' को 'DNWP' लिखा जाता है। उस कोड में 'SWAN' को कैसे लिखा जाएगा?",
    options: ['HVDM', 'HVDZ', 'FJDM', 'FJZL'],
    answer: null
  },
  {
    id: 3, section: 'Quantitative Aptitude',
    eng: 'Select the number that will replace the question mark (?) in the following series:\n11, 12, 18, 31, ?, 106',
    hindi: 'निम्नलिखित श्रृंखला में प्रश्न चिह्न (?) के स्थान पर कौन सी संख्या आएगी?\n11, 12, 18, 31, ?, 106',
    options: ['55', '57', '56', '54'],
    answer: null
  },
  {
    id: 4, section: 'Quantitative Aptitude',
    eng: 'What is the simple interest on Rs. 5000 for 2 years at 5% per annum?',
    hindi: '5% प्रतिवर्ष 2 वर्ष के लिए 5000 रुपये पर साधारण ब्याज कितना होगा?',
    options: ['Rs. 500', 'Rs. 450', 'Rs. 550', 'Rs. 400'],
    answer: null
  }
];

let currentQ   = 0;
const answered = new Set();
const letters  = ['A', 'B', 'C', 'D'];

// ── Render Question ─────────────────────────────────────────
function renderQuestion(idx) {
  const q = questions[idx];
  document.getElementById('sectionLabel').innerHTML =
    `<i class="fas fa-layer-group"></i> Section: ${q.section}`;
  document.getElementById('qNumber').innerHTML =
    `<i class="fas fa-question-circle"></i> Question ${q.id} of ${questions.length}`;
  document.getElementById('qTextEng').innerHTML  = q.eng.replace(/\n/g, '<br>');
  document.getElementById('qTextHindi').innerHTML = q.hindi.replace(/\n/g, '<br>');

  const container = document.getElementById('optionsContainer');
  container.innerHTML = '';
  q.options.forEach((opt, i) => {
    const lbl = document.createElement('label');
    lbl.className = 'option-label';
    lbl.innerHTML = `
      <input type="radio" name="qOpt" value="${i}" ${q.answer === i ? 'checked' : ''}>
      <span class="option-letter">${letters[i]}</span>
      <span class="option-text">${opt}</span>
    `;
    lbl.querySelector('input').addEventListener('change', function () {
      questions[idx].answer = i;
      answered.add(idx);
      renderPalette();
      updateSubmitBtn();
    });
    container.appendChild(lbl);
  });

  // Nav visibility
  document.getElementById('prevBtn').style.visibility = idx === 0 ? 'hidden' : 'visible';
  document.getElementById('nextBtn').style.visibility = idx === questions.length - 1 ? 'hidden' : 'visible';

  currentQ = idx;
  renderPalette();
}

// ── Palette ─────────────────────────────────────────────────
function renderPalette() {
  const grid = document.getElementById('paletteGrid');
  grid.innerHTML = '';
  for (let i = 0; i < 35; i++) {
    const cell = document.createElement('div');
    cell.className = 'palette-cell';
    cell.textContent = 'Q' + (i + 1);
    if (i === currentQ) cell.classList.add('current');
    else if (answered.has(i)) cell.classList.add('answered');
    cell.addEventListener('click', () => {
      if (i < questions.length) renderQuestion(i);
    });
    grid.appendChild(cell);
  }
}

// ── Navigation ───────────────────────────────────────────────
function goQuestion(delta) {
  const next = currentQ + delta;
  if (next >= 0 && next < questions.length) renderQuestion(next);
}

// ── Submit ───────────────────────────────────────────────────
function updateSubmitBtn() {
  const btn = document.getElementById('submitBtn');
  btn.disabled = false;
  btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Submit';
}

function submitExam() {
  if (answered.size < questions.length) {
    if (!confirm(`You have only answered ${answered.size} of ${questions.length} questions. Submit anyway?`)) return;
  }
  window.location.href = 'result.html';
}

// ── Theme ────────────────────────────────────────────────────
function toggleTheme() {
  const body = document.body;
  const btn  = document.getElementById('themeBtn');
  body.classList.toggle('dark-mode');
  if (body.classList.contains('dark-mode')) {
    btn.innerHTML = '<i class="fas fa-sun me-1"></i> Light';
  } else {
    btn.innerHTML = '<i class="fas fa-moon me-1"></i> Dark';
  }
}

function adjustBrightness(val) {
  const b = 0.3 + (val / 100) * 0.7;
  document.body.style.filter = `brightness(${b})`;
}

// ── Section switch ───────────────────────────────────────────
function selectSection(name, el) {
  document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
  if (el) el.classList.add('active');
  document.getElementById('sectionLabel').innerHTML =
    `<i class="fas fa-layer-group"></i> Section: ${name}`;
}

// ── Zoom (range slider + +/- buttons) ────────────────────────
let zoomLevel = 100;
const ZOOM_MIN = 70;
const ZOOM_MAX = 150;
const ZOOM_STEP = 10;

function applyZoom() {
  const qBody = document.getElementById('qBody');
  if (qBody) {
    const scale = zoomLevel / 100;
    qBody.style.transform = `scale(${scale})`;
    qBody.style.transformOrigin = 'top left';
    qBody.style.width = (100 / scale) + '%';
  }
  // Sync slider
  const slider = document.getElementById('zoomRange');
  if (slider) slider.value = zoomLevel;
  // Update button disabled states
  document.getElementById('zoomOutBtn').disabled = zoomLevel <= ZOOM_MIN;
  document.getElementById('zoomInBtn').disabled  = zoomLevel >= ZOOM_MAX;
}

function changeZoom(direction) {
  zoomLevel = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, zoomLevel + (direction * ZOOM_STEP)));
  applyZoom();
}

function applyZoomFromSlider(val) {
  zoomLevel = parseInt(val);
  applyZoom();
}

// ── Mobile Palette Toggle ─────────────────────────────────────
function toggleMobilePalette() {
  const sidebar = document.querySelector('.exam-sidebar');
  const icon    = document.getElementById('paletteToggleIcon');
  sidebar.classList.toggle('mobile-visible');
  icon.className = sidebar.classList.contains('mobile-visible')
    ? 'fas fa-times'
    : 'fas fa-th';
}

// ── Exam Timer (1 hour) ──────────────────────────────────────
var examSeconds = 3594;
function updateExamTimer() {
  if (examSeconds <= 0) {
    document.getElementById('examTimer').textContent = '00:00';
    submitExam();
    return;
  }
  examSeconds--;
  const h = Math.floor(examSeconds / 3600);
  const m = Math.floor((examSeconds % 3600) / 60);
  const s = examSeconds % 60;
  const pad = n => n < 10 ? '0' + n : '' + n;
  document.getElementById('examTimer').textContent = pad(h) + ':' + pad(m) + ':' + pad(s);

  // Turn red when < 5 mins
  if (examSeconds < 300) {
    document.getElementById('timerChip').style.background = 'linear-gradient(135deg,#dc2626,#b91c1c)';
  }
}
setInterval(updateExamTimer, 1000);

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  renderQuestion(0);
});
