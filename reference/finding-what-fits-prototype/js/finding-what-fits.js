/* ---------- state ---------- */
let S;
function freshState() {
  return { screen: "q1", q1: null, q2: [], q3: null, days: null, hours: null,
           nights: null, sleep: null, brk: null, q6: null,
           routeInfo: null, viaBrowse: false, contactMode: null };
}
S = freshState();

function flagsFrom(s) {
  const q2 = new Set(s.q2);
  return {
    GENERAL: s.q1 === "GENERAL", BREAK: s.q1 === "BREAK", LD: s.q1 === "LD",
    MEDICAL: s.q1 === "MEDICAL" || q2.has("MEDICAL"), PLAN: s.q1 === "PLAN",
    COMPANY: q2.has("COMPANY"), EVERYDAY: q2.has("EVERYDAY"), PERSONAL: q2.has("PERSONAL"),
    MEMORY: q2.has("MEMORY"), UNSURE: q2.has("UNSURE"),
    OCCASIONAL: s.q3 === "OCCASIONAL", DAILY: s.q3 === "DAILY",
    NIGHTONLY: s.q3 === "NIGHTONLY", FULL: s.q3 === "FULL", OPEN: s.q3 === "OPEN"
  };
}

/* ---------- progress ---------- */
let progressStep = 1;

function stage2Screens() {
  if (!S.routeInfo) return [];
  const t = CCS.trackFor(S.routeInfo.route, S.routeInfo.showFigures);
  const screens = [];
  if (t === "visit") {
    if (!flagsFrom(S).DAILY) screens.push("q4days");
    screens.push("q5hours");
  } else if (t === "overnight") {
    screens.push("q4nights", "q5sleep");
  } else if (t === "rtc") {
    screens.push("q5sleep");
  } else if (t === "respite") {
    screens.push("q4break");
  }
  screens.push("q6");
  return screens;
}

function totalSteps() {
  // Until the route is known, only the three care-type steps exist.
  if (!S.routeInfo) return 3;
  return 3 + stage2Screens().length;
}

function screenForStep(step) {
  if (step === 1) return "q1";
  if (step === 2) return "q2";
  if (step === 3) {
    if (S.routeInfo && S.routeInfo.route) return "checkpoint";
    return "q3";
  }
  return stage2Screens()[step - 4] || null;
}

function canGoToStep(step) {
  if (step >= progressStep) return false;
  if (step === 1) return true;
  if (step === 2) return !!S.q1;
  if (step === 3) {
    if (S.routeInfo && S.routeInfo.route) return true;
    return S.q2.length > 0;
  }
  return !!S.routeInfo;
}

function goToStep(step) {
  if (!canGoToStep(step)) return;
  const screen = screenForStep(step);
  if (!screen) return;
  go(screen);
}

function setProgress(step, stageLabel, chipText) {
  progressStep = step;
  const N = totalSteps();
  document.getElementById("stepChip").textContent = chipText || ("Step " + step + " of " + N);
  document.getElementById("stageLabel").textContent = stageLabel || "";
  const row = document.getElementById("progressRow");
  row.innerHTML = "";
  for (let i = 1; i <= N; i++) {
    const clickable = canGoToStep(i);
    const seg = document.createElement("button");
    seg.type = "button";
    seg.className = "progress-segment" +
      (i <= step ? " active" : "") +
      (i === step ? " is-current" : "") +
      (clickable ? " is-clickable" : "");
    if (clickable) {
      seg.setAttribute("aria-label", "Go back to step " + i);
      seg.addEventListener("click", (e) => {
        e.preventDefault();
        goToStep(i);
      });
    } else {
      seg.disabled = true;
      seg.setAttribute("aria-current", i === step ? "step" : "false");
      if (i === step) seg.setAttribute("aria-label", "Current step " + i);
      else seg.setAttribute("aria-label", "Step " + i + " not yet reached");
    }
    row.appendChild(seg);
  }
}

function careResultTitle(name) {
  return '<h2 class="q-title q-title--result">' +
    '<span class="q-title-lead">From your answers, it sounds like you\'re looking for —</span>' +
    '<span class="care-result">' + name + "</span></h2>";
}

/* ---------- tiny helpers ---------- */
const screenEl = () => document.getElementById("screen");
function esc(s) { return s.replace(/&/g, "&amp;").replace(/</g, "&lt;"); }
function toast(msg) {
  const t = document.getElementById("toast");
  t.textContent = msg; t.classList.add("show");
  setTimeout(() => t.classList.remove("show"), 2600);
}

/* Font Awesome icons — one clear meaning per option, no repeats in a set */
function iconSvg(name) {
  const map = {
    home: "fa-solid fa-house",
    heart: "fa-solid fa-heart",
    chat: "fa-solid fa-comments",
    leaf: "fa-solid fa-mug-hot",
    moon: "fa-solid fa-moon",
    clock: "fa-solid fa-clock",
    clocks: "fa-solid fa-hourglass-half",
    medical: "fa-solid fa-kit-medical",
    people: "fa-solid fa-users",
    hands: "fa-solid fa-handshake-angle",
    map: "fa-solid fa-road",
    basket: "fa-solid fa-basket-shopping",
    shower: "fa-solid fa-soap",
    thought: "fa-solid fa-brain",
    unsure: "fa-solid fa-circle-question",
    calendar: "fa-solid fa-calendar-days",
    calendarDay: "fa-solid fa-calendar-day",
    calendarWeek: "fa-solid fa-calendar-week",
    calendarCheck: "fa-solid fa-calendar-check",
    daynight: "fa-solid fa-cloud-moon",
    sun: "fa-solid fa-sun",
    bed: "fa-solid fa-bed",
    walk: "fa-solid fa-person-walking",
    suitcase: "fa-solid fa-suitcase",
    check: "fa-solid fa-circle-check",
    notyet: "fa-solid fa-circle-xmark",
    help: "fa-solid fa-circle-info",
    thumb: "fa-solid fa-thumbs-up",
    browse: "fa-solid fa-list",
    guide: "fa-solid fa-book-open",
    phone: "fa-solid fa-phone",
    mail: "fa-solid fa-envelope",
    tag: "fa-solid fa-tag",
    cost: "fa-solid fa-sterling-sign"
  };
  const cls = map[name];
  if (!cls) return "";
  return '<i class="' + cls + '" aria-hidden="true"></i>';
}

function answerBtn(icon, label, onclick, extra) {
  return '<button type="button" class="answer-btn' + (extra && extra.selected ? " selected" : "") + '" onclick="' + onclick + '"' +
         (extra && extra.pressed !== undefined ? ' aria-pressed="' + extra.pressed + '"' : "") + ">" +
         '<span class="answer-main"><span class="icon-box" aria-hidden="true">' + iconSvg(icon) + '</span><span class="label">' + label + "</span></span>" +
         '<span class="arrow" aria-hidden="true">' + (extra && extra.check ? "✓" : "→") + "</span></button>";
}
function backBtn(target) { return '<button type="button" class="back-link" onclick="go(\'' + target + '\')">← Back</button>'; }
function scrollToCard() {
  const run = () => {
    const el = document.getElementById("check");
    if (!el) return;
    const header = document.querySelector(".site-header");
    const headerH = header ? header.offsetHeight : 0;
    // Sit the tool card just under the sticky header with a little breathing room
    const gap = 20;
    const y = el.getBoundingClientRect().top + window.scrollY - headerH - gap;
    window.scrollTo({ top: Math.max(0, Math.round(y)), behavior: "smooth" });
  };
  // Double rAF so quiz screen DOM/layout has settled after render()
  requestAnimationFrame(() => requestAnimationFrame(run));
}

/* ---------- screens ---------- */
function startCheck() { go("q1"); scrollToCard(); }

// Spec §2: overview inline link pre-sets the route and skips to stage 2.
function jumpToRoute(route) {
  if (!CCS.DEFS[route]) return;
  S = freshState();
  S.viaBrowse = true;
  const medical = route === "complex";
  S.routeInfo = { route, dest: "checkpoint", mods: [], showFigures: !medical };
  startStage2();
  scrollToCard();
}

function renderOverviews() {
  const leadEl = document.getElementById("seoLead");
  if (!leadEl) return;

  const icons = {
    visiting: "clock", personal: "shower", companionship: "chat", respite: "leaf",
    overnight: "moon", rtc: "daynight", complex: "medical", ld: "people"
  };
  // One grid for every care type — same Start CTA treatment throughout
  const order = ["visiting", "personal", "respite", "rtc", "companionship", "overnight", "complex", "ld"];
  const byKey = Object.fromEntries(CCS.OVERVIEWS.map(o => [o.key, o]));

  leadEl.innerHTML = order.map(key => {
    const o = byKey[key];
    if (!o) return "";
    const name = CCS.DEFS[key].name;
    return '<li class="seo-tile" id="seo-' + key + '">' +
      '<button type="button" class="seo-tile-link" onclick="jumpToRoute(\'' + key + '\')" aria-label="Start with ' + name + '">' +
      '<span class="seo-tile-icon" aria-hidden="true">' + iconSvg(icons[key] || "hands") + "</span>" +
      '<span class="seo-tile-name">' + name + "</span>" +
      '<span class="seo-tile-blurb">' + o.seo + "</span>" +
      '<span class="seo-tile-cta">Start →</span>' +
      "</button></li>";
  }).join("");
}

function go(screen) {
  S.screen = screen;
  const card = document.getElementById("check");
  if (card && screen !== "results") card.classList.remove("tool-card--outcome");
  render();
}

function render() {
  const el = screenEl();
  const F = flagsFrom(S);
  switch (S.screen) {

    case "q1":
      setProgress(1, "Finding your care type");
      el.innerHTML =
        '<h2 class="q-title">What’s brought you here today?</h2><p class="q-sub">Pick one.</p><div class="answers">' +
        answerBtn("hands", "Someone we love needs more help than we can give alone", "pick1('GENERAL')") +
        answerBtn("leaf", "I need a proper break from caring", "pick1('BREAK')") +
        answerBtn("people", "Support for an adult with a learning disability in our family", "pick1('LD')") +
        answerBtn("medical", "They have health needs that need trained carers at home", "pick1('MEDICAL')") +
        answerBtn("map", "Nothing urgent, just planning ahead", "pick1('PLAN')") + "</div>";
      break;

    case "q2": {
      setProgress(2, "Finding your care type");
      const sel = k => S.q2.includes(k);
      const tile = (icon, label, k) => answerBtn(icon, label, "toggle2('" + k + "')", { selected: sel(k), pressed: sel(k), check: sel(k) });
      el.innerHTML = backBtn("q1") +
        '<h2 class="q-title">What kind of help would make the biggest difference?</h2><p class="q-sub">Tap all that fit.</p><div class="answers">' +
        tile("chat", "Company and conversation, someone to spend time with them", "COMPANY") +
        tile("basket", "Everyday help at home, meals, housework, shopping", "EVERYDAY") +
        tile("shower", "Personal care, washing, dressing, medicines", "PERSONAL") +
        tile("medical", "Health needs at home, like a catheter, oxygen, or injections", "MEDICAL") +
        tile("thought", "Memory is becoming a worry for them", "MEMORY") +
        tile("unsure", "I’m not sure yet", "UNSURE") + "</div>" +
        '<button class="btn btn-primary" style="margin-top:10px' + (S.q2.length ? '' : ';opacity:.45;cursor:not-allowed') + '"' + (S.q2.length ? '' : ' disabled') + ' onclick="go(\'q3\')">Continue →</button>';
      break;
    }

    case "q3":
      setProgress(3, "Finding your care type");
      el.innerHTML = backBtn("q2") +
        '<h2 class="q-title">How much of the day needs covering?</h2><p class="q-sub">Tap one.</p><div class="answers">' +
        answerBtn("clock", "The odd visit here and there", "pick3('OCCASIONAL')") +
        answerBtn("calendar", "Some time every day", "pick3('DAILY')") +
        answerBtn("moon", "It’s mainly nights — days are covered", "pick3('NIGHTONLY')") +
        answerBtn("daynight", "Someone there day and night", "pick3('FULL')") +
        answerBtn("unsure", "I honestly don’t know yet", "pick3('OPEN')") + "</div>";
      break;

    case "finding":
      setProgress(3, "Finding your care type", "One moment");
      el.innerHTML = '<div class="interstitial"><div class="spinner"></div><h2 class="q-title">Finding your care type…</h2></div>';
      setTimeout(() => {
        S.routeInfo = CCS.resolveRoute(flagsFrom(S));
        go(S.routeInfo.dest === "checkpoint" ? "checkpoint" : S.routeInfo.dest);
      }, 950);
      break;

    case "checkpoint": {
      const d = CCS.DEFS[S.routeInfo.route];
      setProgress(3, "Finding your care type", "Care type found");
      el.innerHTML =
        careResultTitle(d.name) +
        '<p class="checkpoint-def">' + d.def + "</p>" +
        '<div class="answers">' +
        answerBtn("cost", "Good to know — want a rough idea of what that costs?", "confirmRoute()") +
        answerBtn("guide", "Skip the costs — send me the " + d.name.toLowerCase() + " guide", "go('guide')") +
        answerBtn("browse", "Not quite — show me the options", "go('browse')") + "</div>";
      break;
    }

    case "guide": {
      const d = CCS.DEFS[S.routeInfo.route];
      setProgress(3, "Finding your care type", "Care type found");
      el.innerHTML =
        backBtn("checkpoint") +
        careResultTitle(d.name) +
        '<p class="checkpoint-def">' + d.def + "</p>" +
        '<p class="guide-lead">Want the free guide for this care type? Add your details below — no cost questions needed.</p>' +
        captureBoxHtml(d.name, true) +
        '<div class="answers" style="margin-top:8px">' +
        answerBtn("cost", "Actually — show me a rough idea of cost too", "confirmRoute()") +
        "</div>" +
        '<div class="secondary-row"><button class="text-link" onclick="restart()">Start again</button></div>';
      break;
    }

    case "browse": {
      setProgress(3, "Finding your care type", "All the options");
      const tiles = Object.keys(CCS.DEFS).map(k =>
        '<button class="browse-tile" onclick="pickBrowse(\'' + k + '\')"><p class="t-name">' + CCS.DEFS[k].name + '</p><p class="t-def">' + CCS.DEFS[k].def + "</p></button>").join("");
      el.innerHTML =
        '<h2 class="q-title">The eight kinds of care we provide</h2><p class="q-sub">Tap the one that sounds closest — or just have a read.</p>' + tiles +
        '<aside class="flagged-line">' +
          '<span class="flag-tag">End of life care</span>' +
          '<p>If you’re looking for comfort-focused care at the end of life, <a href="tel:01622809881">call us</a> — that conversation deserves a person, not a calculator.</p>' +
        "</aside>" +
        forkBlock("planner");
      break;
    }

    case "handoff":
      setProgress(3, "", "Let’s talk instead");
      el.innerHTML =
        '<div style="padding-top:20px"><h2 class="q-title">From what you’ve told us, a person would do better than a calculator here.</h2>' +
        '<p style="font-size:15px;color:#555;line-height:1.7">That’s the free chat — a cuppa, no pressure.</p></div>' +
        forkBlock("standard") +
        '<div class="secondary-row"><button class="text-link" onclick="restart()">Start again</button></div>';
      break;

    case "q4days":
      setProgress(3 + stage2Screens().indexOf("q4days") + 1, "Working out the cost");
      el.innerHTML = backBtn("checkpoint") +
        '<p class="q-sub" style="margin-bottom:6px">Nearly there. A few more taps and we’ll show you a rough idea of the weekly cost.</p>' +
        '<h2 class="q-title">How many days a week, roughly?</h2><div class="answers">' +
        answerBtn("calendarDay", "1–2 days", "pickDays('d12')") +
        answerBtn("calendarWeek", "3–4 days", "pickDays('d34')") +
        answerBtn("calendarCheck", "5–7 days", "pickDays('d57')") + "</div>";
      break;

    case "q5hours":
      setProgress(3 + stage2Screens().indexOf("q5hours") + 1, "Working out the cost");
      el.innerHTML = backBtn(flagsFrom(S).DAILY ? "checkpoint" : "q4days") +
        (flagsFrom(S).DAILY ? '<p class="q-sub" style="margin-bottom:6px">Nearly there. A few more taps and we’ll show you a rough idea of the weekly cost.</p>' : "") +
        '<h2 class="q-title">How long would each visit need to be?</h2><div class="answers">' +
        answerBtn("clock", "About an hour", "pickHours('h1')") +
        answerBtn("clocks", "A few hours", "pickHours('hFew')") +
        answerBtn("sun", "Most of the day", "pickHours('hMost')") + "</div>" +
        '<p class="small-print">Visits start at an hour — it’s how we make sure there’s never a rushed call.</p>';
      break;

    case "q4nights":
      setProgress(3 + stage2Screens().indexOf("q4nights") + 1, "Working out the cost");
      el.innerHTML = backBtn("checkpoint") +
        '<p class="q-sub" style="margin-bottom:6px">Nearly there. A few more taps and we’ll show you a rough idea of the weekly cost.</p>' +
        '<h2 class="q-title">How many nights a week?</h2><div class="answers">' +
        answerBtn("moon", "1–2 nights", "pickNights('n12')") +
        answerBtn("daynight", "3–4 nights", "pickNights('n34')") +
        answerBtn("bed", "Most nights, or every night", "pickNights('nMost')") + "</div>";
      break;

    case "q5sleep": {
      const isRtc = S.routeInfo.route === "rtc";
      setProgress(3 + stage2Screens().indexOf("q5sleep") + 1, "Working out the cost");
      el.innerHTML = backBtn(isRtc ? "checkpoint" : "q4nights") +
        (isRtc ? '<p class="q-sub" style="margin-bottom:6px">Round-the-clock means every day — so we only need to ask about the nights.</p>' : "") +
        '<h2 class="q-title">Do they usually sleep through the night?</h2><div class="answers">' +
        answerBtn("bed", "Mostly — they just need someone there", "pickSleep('mostly')") +
        answerBtn("walk", "No — they’re often up in the night", "pickSleep('often')") +
        answerBtn("unsure", "I’m not sure", "pickSleep('notsure')") + "</div>" +
        '<p class="small-print">If someone’s up three or more times, a night works like a waking night — that’s how we price it, so you’re never surprised.</p>';
      break;
    }

    case "q4break":
      setProgress(3 + stage2Screens().indexOf("q4break") + 1, "Working out the cost");
      el.innerHTML = backBtn("checkpoint") +
        '<p class="q-sub" style="margin-bottom:6px">Nearly there. A few more taps and we’ll show you a rough idea of the weekly cost.</p>' +
        '<h2 class="q-title">What kind of break are you looking for?</h2><div class="answers">' +
        answerBtn("chat", "A few hours here and there", "pickBreak('hours')") +
        answerBtn("sun", "A regular day or two each week", "pickBreak('days')") +
        answerBtn("suitcase", "A week or more, all at once", "pickBreak('week')") + "</div>";
      break;

    case "q6": {
      const stepNow = totalSteps();
      const isComplexPath = !S.routeInfo.showFigures;
      setProgress(stepNow, isComplexPath ? "Finding your care type" : "Working out the cost");
      el.innerHTML = backBtn(q6BackTarget()) +
        (isComplexPath ? '<p class="q-sub" style="margin-bottom:6px">This one’s better as a conversation — but there’s one thing worth checking first.</p>' : "") +
        '<h2 class="q-title">Has your local council looked at what care they need?</h2><div class="answers">' +
        answerBtn("check", "Yes", "pickQ6('yes')") +
        answerBtn("notyet", "Not yet", "pickQ6('notyet')") +
        answerBtn("help", "I’m not sure what that is", "pickQ6('notsure')") + "</div>" +
        '<p class="small-print">This will not change your estimate — it just helps us show the most useful information about paying for care.</p>';
      break;
    }

    case "working":
      setProgress(totalSteps(), "Working out the cost", "One moment");
      el.innerHTML = '<div class="interstitial"><div class="spinner"></div><h2 class="q-title">Working out the cost…</h2></div>';
      setTimeout(() => go("results"), 950);
      break;

    case "results":
      renderResults();
      break;

  }
}

/* ---------- answer handlers ---------- */
function pick1(v) { S.q1 = v; go("q2"); }
function toggle2(v) {
  const i = S.q2.indexOf(v);
  if (i >= 0) S.q2.splice(i, 1); else S.q2.push(v);
  render();
}
function pick3(v) { S.q3 = v; go("finding"); }
function confirmRoute() { startStage2(); }
function pickBrowse(route) {
  S.viaBrowse = true;
  const F = flagsFrom(S);
  const mods = [];
  if (F.MEMORY) mods.push("dementia");
  const medical = F.MEDICAL || route === "complex";
  if (medical && route === "respite") mods.push("trained-medical");
  S.routeInfo = { route, dest: "checkpoint", mods, showFigures: !medical };
  startStage2();
}
function startStage2() {
  const t = CCS.trackFor(S.routeInfo.route, S.routeInfo.showFigures);
  if (t === "visit") {
    if (flagsFrom(S).DAILY) { S.days = "d7"; go("q5hours"); }
    else go("q4days");
  }
  else if (t === "overnight") go("q4nights");
  else if (t === "rtc") go("q5sleep");
  else if (t === "respite") go("q4break");
  else go("q6");
}
function pickDays(v) { S.days = v; go("q5hours"); }
function pickHours(v) { S.hours = v; go("q6"); }
function pickNights(v) { S.nights = v; go("q5sleep"); }
function pickSleep(v) { S.sleep = v; go("q6"); }
function pickBreak(v) { S.brk = v; go("q6"); }
function pickQ6(v) { S.q6 = v; go(S.routeInfo.showFigures ? "working" : "results"); }
function q6BackTarget() {
  const t = CCS.trackFor(S.routeInfo.route, S.routeInfo.showFigures);
  if (t === "visit") return "q5hours";
  if (t === "overnight" || t === "rtc") return "q5sleep";
  if (t === "respite") return "q4break";
  return "checkpoint";
}
function restart() {
  S = freshState();
  const card = document.getElementById("check");
  if (card) card.classList.remove("tool-card--outcome");
  render();
  scrollToCard();
}

/* ---------- results ---------- */
function daysPhrase() {
  if (flagsFrom(S).DAILY || S.days === "d7") return "every day";
  return { d12: "a couple of days a week", d34: "a few days a week", d57: "most days" }[S.days];
}
function hoursPhrase() { return { h1: "about an hour", hFew: "around three hours", hMost: "most of the day (around eight hours)" }[S.hours]; }
function nightsPhrase() { return { n12: "one or two nights a week", n34: "three or four nights a week", nMost: "most nights" }[S.nights]; }
function sleepPhrase() { return { mostly: "with someone sleeping nearby", often: "with someone awake through the night", notsure: "with sleeping or waking cover" }[S.sleep]; }

function mirrorLine(track) {
  if (track === "visit") return "Visits " + daysPhrase() + ", " + hoursPhrase() + " each.";
  if (track === "overnight") return "Cover " + nightsPhrase() + ", " + sleepPhrase() + ".";
  if (track === "rtc") return "Someone there through the day and the night, every day, working in shifts.";
  if (track === "respite") return { hours: "A breather of a few hours, here and there.", days: "A proper break — a regular day or two each week.", week: "A real rest — a week or more of full cover." }[S.brk];
  return "Care shaped around their health needs, worked out together.";
}
function provenanceLine(track) {
  if (track === "visit") return "Based on what you tapped — visits " + daysPhrase() + ", " + hoursPhrase() + " each.";
  if (track === "overnight") return "Based on what you tapped — " + nightsPhrase() + ", " + sleepPhrase() + ".";
  if (track === "rtc") return "Based on what you tapped — round-the-clock cover, every day.";
  if (track === "respite") return "Based on what you tapped — " + mirrorLine(track).toLowerCase();
  return "Based on what you tapped.";
}
const ROUGH_IDEA = "A rough idea to think with — not a price. The real number is worked out together once we’ve met your loved one.";
const BRIDGE = "The free chat is where this becomes your number.";

function estimateBlockHtml(track) {
  if (!S.routeInfo.showFigures) return "";
  const e = CCS.estimate(track, { days: S.days, hours: S.hours, nights: S.nights, sleep: S.sleep, break: S.brk });
  let figure = "";
  if (e.kind === "both") {
    figure = '<p class="estimate-figure">If they mostly sleep: ' + CCS.money(e.sleep.lo) + "–" + CCS.money(e.sleep.hi) + ' <span class="per">a week</span></p>' +
             '<p class="estimate-figure">If they’re often up: ' + CCS.money(e.awake.lo) + "–" + CCS.money(e.awake.hi) + ' <span class="per">a week</span></p>';
  } else if (e.kind === "hourly") {
    figure = '<p class="estimate-figure">' + e.text + ' <span class="per">' + e.per + "</span></p>";
  } else {
    figure = '<p class="estimate-figure">' + e.text + ' <span class="per">' + e.per + "</span></p>" + (e.note ? '<p class="estimate-note">' + e.note + "</p>" : "");
  }
  return figure +
         '<p class="estimate-line">' + provenanceLine(track) + "</p>" +
         '<p class="estimate-line">' + ROUGH_IDEA + "</p>";
}

function breakdownHtml(track) {
  let items = [];
  if (track === "visit") {
    items = [
      (flagsFrom(S).DAILY || S.days === "d7" ? "Every day" : { d12: "1–2 days", d34: "3–4 days", d57: "5–7 days" }[S.days] + " a week") +
        ", visits of " + hoursPhrase() +
        ", at £34.65–£41.25 an hour",
      "Travel and planning included"
    ];
  } else if (track === "overnight") {
    items = [
      { n12: "1–2 nights", n34: "3–4 nights", nMost: "5–7 nights" }[S.nights] + " a week",
      "£230.94 sleeping nearby · £307.62 awake through the night"
    ];
  } else if (track === "rtc") {
    items = [
      "Day shifts at £34.65–£41.25 an hour, plus a sleep-in or waking night every night",
      "Every day of the week"
    ];
  } else if (track === "respite") {
    items = [{
      hours: "Visit-style cover from £34.65–£41.25 an hour",
      days: "Around £580 for a full day and night · £580–£1,160 a week",
      week: "Around £4,000 for a week of full day-and-night cover"
    }[S.brk]];
  }
  if (!items.length) return "";
  return '<div class="breakdown">' +
         "<h4>How we worked that out</h4>" +
         "<ul>" + items.map(i => "<li>" + i + "</li>").join("") + "</ul>" +
         "</div>";
}

function fundingPanelHtml() {
  const medical = !S.routeInfo.showFigures;
  let text;
  if (medical) {
    text = "Numbers come after a proper conversation — and this care can be NHS-funded (Continuing Healthcare), sometimes in full. We’ll help you check.";
  } else if (S.q6 === "yes") {
    text = "Your council assessment may already point to financial support — we can help you read what it means for cost.";
  } else if (S.q6 === "notyet") {
    text = "A free council care needs assessment is usually the first step toward help with cost — we can explain how to arrange one.";
  } else {
    text = "A care needs assessment is a free council check of what support is needed — and whether help with cost is available.";
  }
  return '<section class="results-section">' +
    '<h3>' + (medical ? "What it costs" : "Paying for care") + "</h3>" +
    '<p class="funding-note">' + text + "</p>" +
    "</section>";
}

function forkBlock(mode) {
  const leadins = {
    standard: "The free chat is where these numbers become yours — a cuppa, no pressure.",
    complex: "This one’s better as a conversation than a calculator.",
    planner: "No rush — some families book a chat just to have a name ready."
  };
  return '<section class="results-cta" aria-labelledby="next-step-title">' +
    '<h3 id="next-step-title">Book a free chat</h3>' +
    '<p class="fork-leadin">' + leadins[mode] + "</p>" +
    '<div class="fork-row">' +
    '<button class="btn btn-primary" onclick="openContact(\'call\')">Call me back</button>' +
    '<button class="btn btn-ghost" onclick="openContact(\'time\')">I’ll pick a time</button>' +
    '<button class="btn btn-ghost" onclick="openContact(\'text\')">Text me first</button></div>' +
    '<div id="contactSlot"></div></section>';
}

function renderResults() {
  const info = S.routeInfo;
  const track = CCS.trackFor(info.route, info.showFigures);
  const d = CCS.DEFS[info.route];
  setProgress(totalSteps(), "", "Your starting point");
  const card = document.getElementById("check");
  if (card) card.classList.add("tool-card--outcome");
  const modLines = info.mods.map(m =>
    m === "dementia" ? '<p class="modifier">With carers trained in dementia support.</p>' :
    m === "trained-medical" ? '<p class="modifier">With carers trained for their health needs.</p>' : "").join("");
  const forkMode = !info.showFigures ? "complex" : (S.viaBrowse || flagsFrom(S).PLAN) ? "planner" : "standard";
  const showFigures = info.showFigures;
  const costBlock = showFigures
    ? '<section class="results-section">' +
        "<h3>What it may cost each week</h3>" +
        estimateBlockHtml(track) +
        breakdownHtml(track) +
      "</section>"
    : "";
  screenEl().innerHTML =
    '<div class="results">' +
      '<header class="results-intro">' +
        '<p class="results-kicker">Here’s where we’d suggest starting</p>' +
        '<h2 class="care-name">' + d.name + "</h2>" + modLines +
        '<p class="result-copy">' + d.def + "</p>" +
        '<p class="result-mirror">' + mirrorLine(track) + "</p>" +
      "</header>" +
      costBlock +
      fundingPanelHtml() +
      forkBlock(forkMode) +
      captureBoxHtml(d.name) +
      '<footer class="results-foot">' +
        '<button class="text-link" onclick="emailEstimate()">Email me this estimate</button>' +
        '<span class="dot-sep" aria-hidden="true">·</span>' +
        '<button class="text-link" onclick="restart()">Start again</button>' +
      "</footer>" +
    "</div>";
  scrollToCard();
}

function captureBoxHtml(routeName, forceOpen) {
  const care = esc(routeName.toLowerCase());
  if (forceOpen) {
    return '<div class="capture-box" id="free-guide">' +
      "<h3>Get your free " + care + " guide</h3>" +
      '<p class="cap-note">We’ll email your matched guide, usually within a minute.</p>' +
      '<div class="field-row">' +
        '<label class="field"><span>First name</span><input type="text" name="first_name" autocomplete="given-name" placeholder="First name" required></label>' +
        '<label class="field"><span>Last name</span><input type="text" name="last_name" autocomplete="family-name" placeholder="Last name" required></label>' +
      "</div>" +
      '<label class="field"><span>Email</span><input type="email" name="email" autocomplete="email" placeholder="email@example.co.uk" required></label>' +
      '<label class="field"><span>Phone <em class="opt">(optional)</em></span><input type="tel" name="phone" autocomplete="tel" placeholder="07123 456789"></label>' +
      '<label class="consent"><input type="checkbox" name="guide_consent" required> Yes, send me the free guide.</label>' +
      '<label class="consent"><input type="checkbox" name="followup_consent"> It’s OK for CCS to follow up about home care.</label>' +
      '<p class="cap-privacy">Unsubscribe any time. <a href="#">Privacy notice</a></p>' +
      '<button type="button" class="btn btn-primary" style="width:100%" onclick="toast(\'Prototype — the live build sends the ' + care + ' guide here.\')">Send me the ' + care + " guide →</button>" +
      "</div>";
  }
  return '<details class="guide-aside" id="free-guide">' +
    "<summary>Prefer a guide by email instead?</summary>" +
    '<div class="capture-box capture-box--aside">' +
      "<h3>Get your free " + care + " guide</h3>" +
      '<p class="cap-note">We’ll email your matched guide, usually within a minute.</p>' +
      '<div class="field-row">' +
        '<label class="field"><span>First name</span><input type="text" name="first_name" autocomplete="given-name" placeholder="First name" required></label>' +
        '<label class="field"><span>Last name</span><input type="text" name="last_name" autocomplete="family-name" placeholder="Last name" required></label>' +
      "</div>" +
      '<label class="field"><span>Email</span><input type="email" name="email" autocomplete="email" placeholder="email@example.co.uk" required></label>' +
      '<label class="field"><span>Phone <em class="opt">(optional)</em></span><input type="tel" name="phone" autocomplete="tel" placeholder="07123 456789"></label>' +
      '<label class="consent"><input type="checkbox" name="guide_consent" required> Yes, send me the free guide.</label>' +
      '<label class="consent"><input type="checkbox" name="followup_consent"> It’s OK for CCS to follow up about home care.</label>' +
      '<p class="cap-privacy">Unsubscribe any time. <a href="#">Privacy notice</a></p>' +
      '<button type="button" class="btn btn-ghost" style="width:100%" onclick="toast(\'Prototype — the live build sends the ' + care + ' guide here.\')">Send me the ' + care + " guide →</button>" +
    "</div></details>";
}

function openContact(mode) {
  const slot = document.getElementById("contactSlot");
  if (!slot) return;

  if (mode === "call") {
    slot.innerHTML =
      '<div class="contact-panel">' +
        "<h4>We’ll call you back</h4>" +
        '<p>Leave a number — in the live build this reaches the team. For now, you can also ring us.</p>' +
        '<label class="field"><span>Your name</span><input type="text" name="cb_name" autocomplete="name" placeholder="Your name"></label>' +
        '<label class="field"><span>Phone</span><input type="tel" name="cb_phone" autocomplete="tel" placeholder="07123 456789"></label>' +
        '<div class="contact-big">' +
          '<button type="button" class="btn btn-primary" onclick="toast(\'Prototype — a callback request would go to the team here.\')">Request a call</button>' +
          '<a class="btn btn-ghost" href="tel:01622809881">' + iconSvg("phone") + " 01622 809881</a>" +
        "</div></div>";
  } else if (mode === "time") {
    slot.innerHTML =
      '<div class="contact-panel">' +
        "<h4>Book a free chat</h4>" +
        "<p>A diary picker lands here in the live build. Until then, call or email and we’ll find a time.</p>" +
        '<div class="contact-big">' +
          '<a class="btn btn-primary" href="tel:01622809881">' + iconSvg("phone") + " 01622 809881</a>" +
          '<a class="btn btn-ghost" href="mailto:office@continuitycareservices.co.uk?subject=' +
            encodeURIComponent("I’d like to book a free chat") + '">' +
            iconSvg("mail") + " Email us</a>" +
        "</div></div>";
  } else {
    slot.innerHTML =
      '<div class="contact-panel">' +
        "<h4>We’ll text first</h4>" +
        "<p>Prefer a text before a call? Leave a mobile — or message us now.</p>" +
        '<label class="field"><span>Mobile</span><input type="tel" name="tx_phone" autocomplete="tel" placeholder="07123 456789"></label>' +
        '<div class="contact-big">' +
          '<button type="button" class="btn btn-primary" onclick="toast(\'Prototype — a text follow-up would start here.\')">Text me</button>' +
          '<a class="btn btn-ghost" href="sms:01622809881">' + iconSvg("phone") + " Message now</a>" +
        "</div></div>";
  }
  slot.scrollIntoView({ behavior: "smooth", block: "nearest" });
}

/* ---------- email estimate (prototype: opens a pre-filled draft) ---------- */
/* One-visual-block rule (spec §6): the email carries the whole block or none of it. */
function emailEstimate() {
  const info = S.routeInfo;
  const track = CCS.trackFor(info.route, info.showFigures);
  const d = CCS.DEFS[info.route];
  const lines = ["Here’s where we’d suggest starting: " + d.name, "", d.def, ""];
  if (info.showFigures) {
    const e = CCS.estimate(track, { days: S.days, hours: S.hours, nights: S.nights, sleep: S.sleep, break: S.brk });
    if (e.kind === "both") {
      lines.push("If they mostly sleep: " + CCS.money(e.sleep.lo) + "–" + CCS.money(e.sleep.hi) + " a week.");
      lines.push("If they’re often up: " + CCS.money(e.awake.lo) + "–" + CCS.money(e.awake.hi) + " a week.");
    } else {
      lines.push("What it may cost each week: " + e.text + " " + e.per + ".");
    }
    lines.push("", provenanceLine(track), "", ROUGH_IDEA, "", BRIDGE, "");
  } else {
    lines.push("Care like this is shaped around the person, so numbers come after a proper conversation — and it can be NHS-funded, sometimes in full. We’ll help you check.", "", BRIDGE, "");
  }
  lines.push("Call us: 01622 809881", "Email us: office@continuitycareservices.co.uk", "",
    "This tool is here to help you get your bearings. It is not a formal care assessment. The right care always starts with a conversation with our team.");
  const subject = "Your rough idea from Finding what fits — Continuity of Care Services";
  window.location.href = "mailto:?subject=" + encodeURIComponent(subject) + "&body=" + encodeURIComponent(lines.join("\n"));
}

/* ---------- preview states (QA + handout screenshots) ---------- */
function applyPreview() {
  const m = (location.hash || "").match(/preview=([\w-]+)/);
  if (!m) return false;
  const p = m[1];
  const demo = {
    "q1": () => go("q1"),
    "checkpoint": () => { S.q1 = "GENERAL"; S.q2 = ["EVERYDAY"]; S.q3 = "DAILY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); go("checkpoint"); },
    "guide": () => { S.q1 = "GENERAL"; S.q2 = ["PERSONAL"]; S.q3 = "DAILY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); go("guide"); },
    "browse": () => { S.q1 = "PLAN"; S.q2 = ["UNSURE"]; S.q3 = "OPEN"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); go("browse"); },
    "handoff": () => { S.q1 = "GENERAL"; S.q2 = ["UNSURE"]; S.q3 = "OPEN"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); go("handoff"); },
    "results-visiting": () => { S.q1 = "GENERAL"; S.q2 = ["EVERYDAY"]; S.q3 = "DAILY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); S.days = "d7"; S.hours = "h1"; S.q6 = "notyet"; go("results"); },
    "results-overnight": () => { S.q1 = "GENERAL"; S.q2 = ["PERSONAL"]; S.q3 = "NIGHTONLY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); S.nights = "n34"; S.sleep = "notsure"; S.q6 = "notsure"; go("results"); },
    "results-rtc": () => { S.q1 = "GENERAL"; S.q2 = ["PERSONAL"]; S.q3 = "FULL"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); S.sleep = "notsure"; S.q6 = "yes"; go("results"); },
    "results-respite": () => { S.q1 = "BREAK"; S.q2 = ["EVERYDAY"]; S.q3 = "DAILY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); S.brk = "days"; S.q6 = "notyet"; go("results"); },
    "results-complex": () => { S.q1 = "MEDICAL"; S.q2 = ["MEDICAL", "PERSONAL"]; S.q3 = "DAILY"; S.routeInfo = CCS.resolveRoute(flagsFrom(S)); S.q6 = "notsure"; go("results"); }
  };
  if (demo[p]) { demo[p](); return true; }
  return false;
}

renderOverviews();
if (!applyPreview()) render();
