// __CCS_LOGIC_START__
// Pure logic — no DOM. Mirrors spec §4 (stamped 6 Aug) exactly.
const CCS = {
  RATES: { dayLo: 34.65, dayHi: 41.25, nightVisitLo: 40.15, nightVisitHi: 46.75,
           sleepIn: 230.94, awake: 307.62, rtcSleepWk: 5450, rtcAwakeWk: 5990 },
  DEFS: {
    visiting:      { name: "Visiting Care",            def: "Carers come to the home for set visits, from an hour to most of the day, helping with whatever that day needs." },
    personal:      { name: "Personal Care",            def: "Unhurried help with personal things, like washing, dressing, medicines and moving around safely." },
    companionship: { name: "Companionship Care",       def: "Someone to spend time with — a cuppa, a chat, a walk, a hand with errands — so the days feel less long." },
    respite:       { name: "Carer Break Support",      def: "Care that steps in so the person who usually does the caring can take a proper break, for a few hours or a few weeks." },
    overnight:     { name: "Overnight Care",           def: "A carer there through the night — either sleeping nearby and up if needed, or awake all night — so everyone else can sleep." },
    rtc:           { name: "Round-the-Clock Care",     def: "Carers covering the day and the night in shifts, so someone’s always there. It’s not a care home, and it’s not giving up the house." },
    complex:       { name: "Complex Care",             def: "Care from carers with extra clinical training — for health needs like a catheter, oxygen, injections or a feeding tube — at home." },
    ld:            { name: "Learning Disability Care", def: "Support built around an adult with a learning disability — daily living, routines, and doing more of what they enjoy." }
  },
  // Crawlable SEO blurbs — page copy (stamped with Piece 4 revise).
  OVERVIEWS: [
    { key: "visiting", seo: "Short, planned visits at home for help with meals, housework, shopping, medication reminders, or general support during the day. Visits start from one hour." },
    { key: "personal", seo: "Help with washing, dressing, medication, and moving safely around the home, while keeping the person’s routine, dignity, and independence in mind." },
    { key: "companionship", seo: "A friendly visit for conversation, a cup of tea, a walk, errands, or appointments. This can help when loneliness or isolation has become a concern." },
    { key: "respite", seo: "Short-term cover so you can rest, work, or take time away, knowing the person you care for is safe at home with a familiar face." },
    { key: "overnight", seo: "Support through the night — either with a carer sleeping nearby and getting up when needed, or staying awake throughout the night." },
    { key: "rtc", seo: "Care during the day and night from carers working in shifts — so someone is always there while your loved one stays at home." },
    { key: "complex", seo: "Support for people with more specific health needs — catheters, oxygen, injections, or feeding tubes. Some complex care may be NHS-funded, and we can help you understand what to check." },
    { key: "ld", seo: "Support for adults with learning disabilities, built around daily routines, independence, interests, and quality of life." }
  ],
  floor10: x => Math.floor(x / 10) * 10,
  ceil5:  x => Math.ceil(x / 5) * 5,
  money:  x => "£" + x.toLocaleString("en-GB"),
  // The precedence ladder — spec §4, first match wins. Outranked flags demote
  // to modifier lines + tags, never discarded. Returns null route = rung 0.
  resolveRoute(f) {
    const mods = [];
    let route = null;
    if (f.BREAK)          route = "respite";
    else if (f.MEDICAL)   route = "complex";
    else if (f.LD)        route = "ld";
    else if (f.FULL)      route = "rtc";
    else if (f.NIGHTONLY) route = "overnight";
    else if (f.PERSONAL)  route = "personal";
    else if (f.EVERYDAY)  route = "visiting";
    else if (f.COMPANY)   route = "companionship";
    else if (f.MEMORY)    route = "visiting";           // rung 9 — memory alone
    // Modifier lines (deck §5): outranked flags come back as lines, not routes.
    if (route && f.MEMORY) mods.push("dementia");
    if (route === "respite" && f.MEDICAL) mods.push("trained-medical");
    // No figures whenever health needs were tapped — deck §4 complex track rule.
    const showFigures = !!route && !f.MEDICAL && route !== "complex";
    if (!route) return { route: null, dest: f.PLAN ? "browse" : "handoff", mods: [], showFigures: false };
    return { route, dest: "checkpoint", mods, showFigures };
  },
  // Which stage-2 track a route uses — spec §4 stage-2 table.
  trackFor(route, showFigures) {
    if (!showFigures) return "none";                     // complex / any MEDICAL: straight to Q6
    if (route === "overnight") return "overnight";
    if (route === "rtc") return "rtc";
    if (route === "respite") return "respite";
    if (["visiting", "personal", "companionship", "ld"].includes(route)) return "visit";
    return "none";
  },
  // Hour assumptions — single point per tile so bands reflect weekday→weekend
  // rate span (and day-count span), not hour-range × day-range compounding.
  // Spec stamped example: 5 × 1h × £34.65–£41.25 → £170–£210.
  DAYS:  { d12: [1, 2], d34: [3, 4], d57: [5, 7], d7: [7, 7] },
  HOURS: { h1: [1, 1], hFew: [3, 3], hMost: [8, 8] },
  NIGHTS:{ n12: [1, 2], n34: [3, 4], nMost: [5, 7] },
  // Estimate maths — spec §4/§5. Band low floored to £10, high ceiled to £5
  // (matches the stamped examples: 173.25→170 · 206.25→210 · 692.82→690 · 922.86→925).
  estimate(track, a) {
    const R = this.RATES, f10 = this.floor10, c5 = this.ceil5, M = this.money;
    if (track === "visit") {
      const d = this.DAYS[a.days], h = this.HOURS[a.hours];
      const hrs = h[0]; // point assumption per tile
      // Day-count span × weekday→weekend rate on the same visit length
      const lo = f10(d[0] * hrs * R.dayLo);
      const hi = c5(d[1] * hrs * R.dayHi);
      return { kind: "band", lo, hi, text: M(lo) + "–" + M(hi), per: "a week" };
    }
    if (track === "overnight") {
      const n = this.NIGHTS[a.nights];
      const sleep = { lo: f10(n[0] * R.sleepIn), hi: c5(n[1] * R.sleepIn) };
      const awake = { lo: f10(n[0] * R.awake),  hi: c5(n[1] * R.awake) };
      if (a.sleep === "mostly") return { kind: "band", ...sleep, text: M(sleep.lo) + "–" + M(sleep.hi), per: "a week" };
      if (a.sleep === "often")  return { kind: "band", ...awake, text: M(awake.lo) + "–" + M(awake.hi), per: "a week" };
      return { kind: "both", sleep, awake };             // “I'm not sure” → show both lines
    }
    if (track === "rtc") {
      if (a.sleep === "mostly") return { kind: "band", lo: R.rtcSleepWk, hi: R.rtcSleepWk, text: "roughly " + M(R.rtcSleepWk), per: "a week" };
      if (a.sleep === "often")  return { kind: "band", lo: R.rtcAwakeWk, hi: R.rtcAwakeWk, text: "roughly " + M(R.rtcAwakeWk), per: "a week" };
      return { kind: "band", lo: 5400, hi: 6000, text: "£5,400–£6,000", per: "a week, depending on how the nights go" };
    }
    if (track === "respite") {
      if (a.break === "hours") return { kind: "hourly", text: "around £35–£41 an hour", per: "depending on the day" };
      if (a.break === "days")  return { kind: "band", lo: 580, hi: 1160, text: "£580–£1,160", per: "a week", note: "A full day and night of cover is around £580." };
      return { kind: "band", lo: 4000, hi: 4000, text: "around £4,000", per: "a week of full day-and-night cover" };
    }
    return { kind: "none" };
  }
};
// __CCS_LOGIC_END__
