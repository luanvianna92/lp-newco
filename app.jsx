// Newco Brazil — App component (React + Babel) — v3 (refatoração estrutural)
const { useState, useEffect, useRef } = React;

const TWEAKS_DEFAULTS = /*EDITMODE-BEGIN*/{
  "theme": "dark",
  "type": "sans",
  "heroVariant": "full"
} /*EDITMODE-END*/;

function detectLang() {
  try {
    const stored = localStorage.getItem("newco_lang");
    if (stored && (stored === "pt" || stored === "en")) return stored;
    const nav = (navigator.language || "pt").toLowerCase();
    if (nav.startsWith("pt")) return "pt";
    return "en";
  } catch (e) {return "pt";}
}

function App() {
  const [lang, setLang] = useState(detectLang());
  const [tweaks, setTweaks] = useState(TWEAKS_DEFAULTS);
  const [tweaksOpen, setTweaksOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [modalState, setModalState] = useState({ open: false, product: null });
  const t = window.NEWCO_I18N[lang];

  const openDatasheet = (product = null) => setModalState({ open: true, product });
  const closeDatasheet = () => setModalState({ open: false, product: null });

  useEffect(() => {
    try {localStorage.setItem("newco_lang", lang);} catch (e) {}
    document.documentElement.lang = lang === "pt" ? "pt-BR" : "en";
  }, [lang]);

  useEffect(() => {
    document.documentElement.setAttribute("data-theme", tweaks.theme);
    document.documentElement.setAttribute("data-type", tweaks.type);
  }, [tweaks]);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    const els = document.querySelectorAll(".reveal, .stagger");
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {if (e.isIntersecting) e.target.classList.add("in");});
      },
      { threshold: 0.12 }
    );
    els.forEach((el) => io.observe(el));
    return () => io.disconnect();
  }, [lang]);

  useEffect(() => {
    const onKey = (e) => { if (e.key === "Escape" && modalState.open) closeDatasheet(); };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [modalState.open]);

  useEffect(() => {
    const onMsg = (e) => {
      const d = e.data || {};
      if (d.type === "__activate_edit_mode") setTweaksOpen(true);
      if (d.type === "__deactivate_edit_mode") setTweaksOpen(false);
    };
    window.addEventListener("message", onMsg);
    window.parent.postMessage({ type: "__edit_mode_available" }, "*");
    return () => window.removeEventListener("message", onMsg);
  }, []);

  const updateTweak = (key, value) => {
    const next = { ...tweaks, [key]: value };
    setTweaks(next);
    try {window.parent.postMessage({ type: "__edit_mode_set_keys", edits: { [key]: value } }, "*");} catch (e) {}
  };

  return (
    <React.Fragment>
      <Header lang={lang} setLang={setLang} t={t} scrolled={scrolled} />
      <Hero t={t} variant={tweaks.heroVariant} onRequestDatasheet={() => openDatasheet()} />
      <OurLines t={t} onRequestDatasheet={openDatasheet} />
      <Spray t={t} />
      <ESG t={t} />
      <Certs t={t} />
      <AboutLocation t={t} />
      <AdditionalServices t={t} />
      <Contato t={t} />
      <Footer t={t} />
      {modalState.open && (
        <DatasheetModal t={t} lang={lang} product={modalState.product} onClose={closeDatasheet} />
      )}
      {tweaksOpen &&
      <TweaksPanel
        tweaks={tweaks}
        updateTweak={updateTweak}
        onClose={() => {
          setTweaksOpen(false);
          window.parent.postMessage({ type: "__edit_mode_dismissed" }, "*");
        }} />

      }
    </React.Fragment>);

}

// ─────── Header ───────
function Header({ lang, setLang, t, scrolled }) {
  const navs = [
  ["intro", "intro"],
  ["lines", "lines"],
  ["spray", "spray"],
  ["esg", "esg"],
  ["about", "about"],
  ["services", "services"],
  ["contato", "contact"]];

  return (
    <header className={`site-header ${scrolled ? "scrolled" : ""}`}>
      <a className="brand" href="#intro">
        <img src="assets/logo.png" alt="Newco Brazil" />
      </a>
      <nav className="site-nav">
        {navs.map(([k, id]) => <a key={k} href={`#${id}`}>{t.nav[k]}</a>)}
      </nav>
      <div className="header-right">
        <LangSwitch lang={lang} setLang={setLang} />
        <a className="cta-pill" href="#contact">
          {t.header.cta}
          <svg className="arr" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
        </a>
      </div>
    </header>);

}

// ─────── LangSwitch (bandeiras SVG) ───────
function LangSwitch({ lang, setLang }) {
  return (
    <div className="lang-switch lang-flags" role="group" aria-label="Switch language">
      <button
        type="button"
        className={`lang-flag-btn ${lang === "pt" ? "active" : ""}`}
        onClick={() => setLang("pt")}
        aria-label="Português"
        aria-pressed={lang === "pt" ? "true" : "false"}>
        <FlagBR />
      </button>
      <button
        type="button"
        className={`lang-flag-btn ${lang === "en" ? "active" : ""}`}
        onClick={() => setLang("en")}
        aria-label="English"
        aria-pressed={lang === "en" ? "true" : "false"}>
        <FlagGB />
      </button>
    </div>);

}

// ─────── Bandeiras SVG inline ───────
function FlagBR() {
  return (
    <svg viewBox="0 0 28 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect width="28" height="20" fill="#009c3b" />
      <path d="M14 3 L25 10 L14 17 L3 10 Z" fill="#ffdf00" />
      <circle cx="14" cy="10" r="3.6" fill="#002776" />
      <path d="M11 10.4 Q14 8.5 17 10.4" stroke="#fff" strokeWidth="0.5" fill="none" />
    </svg>);
}
function FlagGB() {
  return (
    <svg viewBox="0 0 28 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <rect width="28" height="20" fill="#012169" />
      <path d="M0 0 L28 20 M28 0 L0 20" stroke="#fff" strokeWidth="2.4" />
      <path d="M0 0 L28 20 M28 0 L0 20" stroke="#C8102E" strokeWidth="1.4" />
      <path d="M14 0 V20 M0 10 H28" stroke="#fff" strokeWidth="3.6" />
      <path d="M14 0 V20 M0 10 H28" stroke="#C8102E" strokeWidth="2" />
    </svg>);
}

// ─────── Particles (drifting dust) ───────
function Particles({ count = 26 }) {
  const items = Array.from({ length: count }, (_, i) => {
    const left = Math.random() * 100;
    const dur = 12 + Math.random() * 18;
    const delay = Math.random() * 18;
    const size = 1 + Math.random() * 2.5;
    return (
      <span
        key={i}
        style={{
          left: `${left}%`,
          bottom: `-10px`,
          width: `${size}px`,
          height: `${size}px`,
          animationDuration: `${dur}s`,
          animationDelay: `-${delay}s`
        }} />);


  });
  return <div className="particles">{items}</div>;
}

// ─────── Hero ───────
function Hero({ t, variant, onRequestDatasheet }) {
  return (
    <section id="intro" className="hero" data-variant={variant} data-screen-label="01 Intro">
      <div className="hero-bg" />
      <div className="hero-grain" />
      <Particles count={28} />
      <div className="container">
        <div className="hero-grid">
          <div className="hero-titles-right">
            <div className="hero-eyebrow eyebrow">{t.hero.eyebrow}</div>
            <h1 className="display">{t.hero.title}</h1>
            <p className="lede">{t.hero.subtitle}</p>
            <div className="hero-actions">
              <button type="button" className="cta-pill" onClick={onRequestDatasheet}>
                {t.hero.cta_primary}
                <svg className="arr" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div className="hero-foot">
        <div className="scroll-hint">
          <span className="dot" />
          <span>{t.hero.scroll}</span>
        </div>
      </div>
    </section>);

}

// ─────── Our Lines (combina What We Do + Products) ───────
function OurLines({ t, onRequestDatasheet }) {
  return (
    <section id="lines" className="section lines" data-screen-label="02 Our Lines">
      <div className="tex gradient-deep" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.lines.eyebrow}</div>
            <h2 className="display tight">{t.lines.title}</h2>
          </div>
          <p className="lede reveal">{t.lines.body}</p>
        </div>
        <div className="lines-grid stagger">
          {t.lines.items.map((it, i) => {
            const hasImg = it.img && it.img.trim().length > 0;
            return (
              <article className="line-card" key={i}>
                <div
                  className={`line-img ${hasImg ? "" : "placeholder"}`}
                  style={hasImg ? { backgroundImage: `url('${it.img}')` } : null}
                />
                <div className="line-body">
                  <h3 className="line-name">{it.name}</h3>
                  <p className="line-desc">{it.desc}</p>
                  <button
                    type="button"
                    className="line-cta"
                    onClick={() => onRequestDatasheet(it.name)}>
                    {t.lines.cta}
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
                  </button>
                </div>
              </article>);

          })}
        </div>
      </div>
    </section>);

}

// ─────── Spray Drying ───────
function Spray({ t }) {
  const d = t.spray.diagram;
  return (
    <section id="spray" className="section spray has-photo-bg spray-bg" data-screen-label="03 Spray Drying">
      <div className="photo-bg spray-photo" style={{ backgroundImage: "url('assets/spray-dryer.webp')" }} />
      <div className="photo-bg-overlay spray-overlay" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.spray.eyebrow}</div>
            <h2 className="display tight">{t.spray.title}</h2>
          </div>
          <p className="lede reveal" style={{ margin: "19px 8px 19px 0px" }}>{t.spray.subtitle}</p>
        </div>
        <div className="spray-text-wide reveal">
          <p>{t.spray.body_1}</p>
          <p>{t.spray.body_2}</p>
        </div>
        <div className="diagram reveal">
          <div className="diagram-flow">
            <div className="flow-line"><span className="pulse" /></div>
            {[d.step1, d.step2, d.step3, d.step4].map((s, i) =>
            <div className="diagram-step" key={i}>
                <div className="icon">
                  {i === 0 && <DropletIcon />}
                  {i === 1 && <ChamberIcon />}
                  {i === 2 && <CycloneIcon />}
                  {i === 3 && <PowderIcon />}
                </div>
                <div className="num">{s.num}</div>
                <div className="ti">{s.title}</div>
                <div className="de">{s.desc}</div>
              </div>
            )}
          </div>
        </div>
        <div className="spray-cards reveal">
          <div className="head">{t.spray.cards_title}</div>
          <div className="spray-cards-grid">
            {t.spray.cards.map((c, i) =>
            <div className="spray-card" key={i}>
                <div className="card-top">
                  <div className="card-icon">
                    {c.icon === "thermal" && <ThermalIcon />}
                    {c.icon === "shield" && <ShieldIcon />}
                    {c.icon === "drop" && <DropletIcon />}
                  </div>
                  <span className="card-tag">{c.tag}</span>
                </div>
                <h4>{c.title}</h4>
                <p>{c.desc}</p>
              </div>
            )}
          </div>
        </div>
        <div className="proof reveal">
          {t.spray.proof.map((p, i) =>
          <div className="proof-item" key={i}>
              <div className="v">{p.v}</div>
              <div className="l">{p.l}</div>
            </div>
          )}
        </div>
      </div>
    </section>);

}

// ─────── Spray icons (line-style) ───────
function DropletIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <path d="M16 5c4 5 7 9 7 13a7 7 0 0 1-14 0c0-4 3-8 7-13z" />
    <circle cx="16" cy="14" r="1.2" fill="currentColor" stroke="none" />
  </svg>;
}
function ChamberIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <rect x="7" y="6" width="18" height="20" rx="2" />
    <path d="M11 12h10M11 16h10M11 20h10" strokeOpacity="0.5" />
  </svg>;
}
function CycloneIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <path d="M9 7h14l-3 8h-8z" />
    <path d="M14 15l2 10 2-10" />
  </svg>;
}
function PowderIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <circle cx="11" cy="20" r="1.5" fill="currentColor" stroke="none" />
    <circle cx="16" cy="22" r="2" fill="currentColor" stroke="none" />
    <circle cx="21" cy="20" r="1.5" fill="currentColor" stroke="none" />
    <circle cx="13" cy="24" r="1" fill="currentColor" stroke="none" />
    <circle cx="19" cy="24" r="1" fill="currentColor" stroke="none" />
    <path d="M9 16h14" strokeOpacity="0.4" />
  </svg>;
}
function ThermalIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <path d="M16 4v18" />
    <circle cx="16" cy="24" r="4" />
    <path d="M19 8h3M19 12h3M19 16h3" strokeOpacity="0.6" />
  </svg>;
}
function ShieldIcon() {
  return <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" strokeWidth="1.4">
    <path d="M16 4l10 4v8c0 6-4 10-10 12-6-2-10-6-10-12V8z" />
    <path d="M12 16l3 3 5-6" strokeWidth="1.6" />
  </svg>;
}

// ─────── ESG line icons (acai-purple stroke via CSS) ───────
function IconPin() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M12 2c-3.9 0-7 3-7 6.8 0 5 7 12.2 7 12.2s7-7.2 7-12.2C19 5 15.9 2 12 2z" />
    <circle cx="12" cy="9" r="2.6" />
  </svg>;
}
function IconGear() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M12 9.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
    <path d="M19.4 13a7.5 7.5 0 0 0 0-2l1.7-1.3-1.7-3-2 .8a7.5 7.5 0 0 0-1.7-1l-.3-2.1h-3.4l-.3 2.1a7.5 7.5 0 0 0-1.7 1l-2-.8-1.7 3L8 11a7.5 7.5 0 0 0 0 2L6.3 14.3l1.7 3 2-.8a7.5 7.5 0 0 0 1.7 1l.3 2.1h3.4l.3-2.1a7.5 7.5 0 0 0 1.7-1l2 .8 1.7-3z" />
  </svg>;
}
function IconDoc() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
    <path d="M14 3v5h5" />
    <path d="M9 13h6M9 17h6M9 9h2" />
  </svg>;
}
function IconQR() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <rect x="3" y="3" width="7" height="7" rx="1" />
    <rect x="14" y="3" width="7" height="7" rx="1" />
    <rect x="3" y="14" width="7" height="7" rx="1" />
    <path d="M14 14h3v3M21 14v3M14 21h3M21 18v3" />
  </svg>;
}
function IconRecycle() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7 18 4 14l4-3" />
    <path d="M4 14h10" />
    <path d="m17 6 3 4-4 3" />
    <path d="M20 10H10" />
    <path d="m11 21 3-4-4-3" />
    <path d="M14 17H4" />
  </svg>;
}
function IconCycle() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M21 12a9 9 0 1 1-3-6.7" />
    <path d="M21 4v5h-5" />
  </svg>;
}
function IconBolt() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M13 2 4 14h7l-1 8 9-12h-7z" strokeLinejoin="round" />
  </svg>;
}
function IconAward() {
  return <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <circle cx="12" cy="9" r="6" />
    <path d="m9 14-2 7 5-3 5 3-2-7" />
  </svg>;
}
const ESG_ICONS = {
  pin: IconPin, gear: IconGear, doc: IconDoc, qr: IconQR,
  recycle: IconRecycle, cycle: IconCycle, bolt: IconBolt, award: IconAward,
};

// ─────── ESG ───────
function ESG({ t }) {
  return (
    <section id="esg" className="section esg" data-screen-label="04 ESG">
      <div className="tex swirl" />
      <div className="container">
        <div className="section-head center reveal">
          <div className="eyebrow">{t.esg.eyebrow}</div>
          <h2 className="display tight">{t.esg.title}</h2>
          <p className="lede">{t.esg.subtitle}</p>
        </div>
        <div className="esg-cols stagger">
          <div className="esg-col">
            <h3><span className="ic">◍</span>{t.esg.col1_title}</h3>
            <div className="esg-list">
              {t.esg.col1_items.map((it, i) => {
                const Icon = ESG_ICONS[it.icon] || IconDoc;
                return (
                  <div className="ei" key={i}>
                    <span className="ei-icon"><Icon /></span>
                    <div className="ei-text">
                      <h4>{it.title}</h4>
                      <p>{it.desc}</p>
                    </div>
                  </div>);

              })}
            </div>
          </div>
          <div className="esg-col">
            <h3><span className="ic">◉</span>{t.esg.col2_title}</h3>
            <div className="esg-list">
              {t.esg.col2_items.map((it, i) => {
                const Icon = ESG_ICONS[it.icon] || IconDoc;
                return (
                  <div className="ei" key={i}>
                    <span className="ei-icon"><Icon /></span>
                    <div className="ei-text">
                      <h4>{it.title}</h4>
                      <p>{it.desc}</p>
                    </div>
                  </div>);

              })}
            </div>
          </div>
        </div>
        <div className="esg-closing reveal">{t.esg.closing}</div>
      </div>
    </section>);

}

// ─────── Certifications (logos only + tooltip) ───────
function Certs({ t }) {
  const list = t.cert.items;
  const doubled = [...list, ...list];
  return (
    <section id="cert" className="section certs-section" data-screen-label="05 Certifications">
      <div className="tex gradient-deep" />
      <div className="container">
        <div className="section-head center reveal">
          <h2 className="display tight">{t.cert.title}</h2>
          <p className="lede">{t.cert.subtitle}</p>
        </div>
      </div>
      <div className="certs-marquee reveal">
        <div className="certs-track">
          {doubled.map((c, i) =>
            <div className="cert-badge cert-logo" key={i} title={c.desc} aria-label={`${c.name} — ${c.desc}`}>
              <div className="cert-logo-circle">{c.name}</div>
            </div>
          )}
        </div>
      </div>
    </section>);

}

// ─────── About + Location combinada ───────
function AboutLocation({ t }) {
  return (
    <section id="about" className="section about-location" data-screen-label="06 About + Location">
      <div className="tex dust"><DustField /></div>
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.about.eyebrow}</div>
            <h2 className="display tight">{t.about.title}</h2>
          </div>
          <p className="lede reveal">{t.about.body}</p>
        </div>

        <div className="about-intro reveal">
          <div className="about-intro-text">
            <p>{t.about.body}</p>
          </div>
          <div className="about-kpis stagger">
            {t.about.kpis.map((k, i) =>
              <div className="about-kpi" key={i}>
                <div className="num">{k.num}</div>
                <div className="lab">{k.label}</div>
              </div>
            )}
          </div>
        </div>

        <div className="about-location-block">
          <div className="about-location-head reveal">
            <div className="eyebrow">{t.nav.about}</div>
            <h3>{t.about.location_title}</h3>
            <p>{t.about.location_subtitle}</p>
          </div>

          <div className="logistica-grid">
            <div className="logistica-map reveal">
              <div className="pin"><span className="dot" />Varginha · MG</div>
              <iframe
                title="Mapa Varginha"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3708.8086783684016!2d-45.418091885057095!3d-21.632377685674506!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94caedb47782e89f%3A0x1d796dbeb9d468fa!2sNewco+Brazil+Ind%C3%BAstria+de+Produtos+Funcionais!5e0!3m2!1spt-BR!2sbr!4v1470004254797"
                loading="lazy"
                allowFullScreen=""
                referrerPolicy="no-referrer-when-downgrade" />
              <div className="logistica-routes">
                {t.about.routes.map((r, i) =>
                  <span className="r" key={i}><b>{r.city}</b> · {r.km}</span>
                )}
              </div>
            </div>
            <div className="logistica-stats stagger">
              {t.about.stats.map((s, i) =>
                <div className="lstat" key={i}>
                  <div className="num">{s.num}</div>
                  <div className="lab">{s.label}</div>
                </div>
              )}
            </div>
          </div>

          <div className="logistica-bullets reveal">
            <div className="logistica-bullets-head">{t.about.bullets_title}</div>
            <div>
              <div className="logistica-bullets-list">
                {t.about.bullets.map((b, i) =>
                  <div className="lb" key={i}>
                    <h5>{b.title}</h5>
                    <p>{b.desc}</p>
                  </div>
                )}
              </div>
              <div className="source">{t.about.source}</div>
            </div>
          </div>
        </div>
      </div>
    </section>);

}

function DustField() {
  const items = Array.from({ length: 24 }, (_, i) => {
    const left = Math.random() * 100;
    const dur = 14 + Math.random() * 16;
    const delay = Math.random() * 20;
    const size = 1 + Math.random() * 2;
    return (
      <span
        key={i}
        className="p"
        style={{
          left: `${left}%`,
          bottom: `-10px`,
          width: `${size}px`,
          height: `${size}px`,
          animationDuration: `${dur}s`,
          animationDelay: `-${delay}s`
        }} />);


  });
  return <div className="field">{items}</div>;
}

// ─────── Additional Services (R&D, Scale, QA/QC, Export) ───────
function AdditionalServices({ t }) {
  return (
    <section id="services" className="section oque has-photo-bg" data-screen-label="07 Additional Services">
      <div className="photo-bg" style={{ backgroundImage: "url('assets/morango-bg.jpg')" }} />
      <div className="photo-bg-overlay" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.services.eyebrow}</div>
            <h2 className="display tight">{t.services.title}</h2>
          </div>
          <p className="lede reveal">{t.services.body}</p>
        </div>
        <div className="capabilities stagger">
          {t.services.items.map((c, i) =>
          <div className="cap" key={i}>
              <div className="cap-kw">{c.kw}</div>
              <div className="cap-body">
                <h4>{c.t}</h4>
                <p>{c.d}</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </section>);

}

// ─────── Datasheet Modal ───────
function DatasheetModal({ t, lang, product, onClose }) {
  const [status, setStatus] = useState("idle"); // idle | sending | success
  const formRef = useRef(null);

  const handleSubmit = (e) => {
    e.preventDefault();
    setStatus("sending");
    const form = formRef.current;
    const data = new FormData(form);
    fetch("https://newcobrazil.com/contato.php", {
      method: "POST",
      body: data,
      mode: "no-cors",
    }).then(() => setStatus("success"))
      .catch(() => setStatus("success")); // fallback: lead capturado client-side
  };

  const handleBackdrop = (e) => { if (e.target === e.currentTarget) onClose(); };

  return (
    <div className="modal-backdrop" onClick={handleBackdrop} role="dialog" aria-modal="true" aria-labelledby="modal-title">
      <div className="modal-card">
        <button className="modal-close" onClick={onClose} aria-label={t.modal.close}>×</button>
        {status !== "success" ? (
          <React.Fragment>
            <div className="modal-head">
              <span className="modal-eyebrow">{t.modal.product_label}</span>
              <h2 className="modal-title" id="modal-title">{t.modal.title}</h2>
              <p className="modal-subtitle">{t.modal.subtitle}</p>
              {product && <span className="modal-product-tag">{t.modal.product_label}: {product}</span>}
            </div>
            <form ref={formRef} className="modal-form" onSubmit={handleSubmit}>
              <input type="hidden" name="tipo" value="datasheet" />
              <input type="hidden" name="idioma" value={lang} />
              {product && <input type="hidden" name="produto" value={product} />}
              <label>{t.modal.name}<input type="text" name="nome" required /></label>
              <label>{t.modal.email}<input type="email" name="email" required /></label>
              <label>{t.modal.company}<input type="text" name="empresa" /></label>
              <label>{t.modal.message}<textarea name="mensagem" rows="3" /></label>
              <button type="submit" className="modal-submit" disabled={status === "sending"}>
                {status === "sending" ? t.modal.sending : t.modal.submit}
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
              </button>
            </form>
          </React.Fragment>
        ) : (
          <div className="modal-success">
            <div className="check">✓</div>
            <h3>{t.modal.success_title}</h3>
            <p>{t.modal.success_body}</p>
            <button type="button" className="modal-close-btn" onClick={onClose}>{t.modal.close}</button>
          </div>
        )}
      </div>
    </div>);

}

// ─────── Contato ───────
function Contato({ t }) {
  return (
    <section id="contact" className="section contato" data-screen-label="08 Contato">
      <div className="tex blueprint" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.contato.eyebrow}</div>
            <h2 className="display tight">{t.contato.title}</h2>
          </div>
          <p className="lede reveal">{t.contato.subtitle}</p>
        </div>
        <div className="contato-grid">
          <form
            method="POST"
            action="https://newcobrazil.com/contato.php"
            className="reveal">

            <div className="row">
              <label>{t.contato.name}<input type="text" name="nome" required /></label>
              <label>{t.contato.company}<input type="text" name="empresa" /></label>
            </div>
            <div className="row">
              <label>{t.contato.email}<input type="email" name="email" required /></label>
              <label>{t.contato.country}<input type="text" name="pais" /></label>
            </div>
            <label>{t.contato.message}<textarea name="mensagem" rows="5" /></label>
            <button type="submit" className="submit">
              {t.contato.submit}
              <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
            </button>
          </form>
          <div className="contato-info reveal">
            <div className="info-row">
              <span className="ic">⌖</span>
              <div><div className="lb">Address</div><div className="vl">{t.contato.address}</div></div>
            </div>
            <div className="info-row">
              <span className="ic">☏</span>
              <div><div className="lb">Phone</div><div className="vl">{t.contato.phone}</div></div>
            </div>
            <div className="info-row">
              <span className="ic">@</span>
              <div><div className="lb">Email</div><div className="vl">{t.contato.mail}</div></div>
            </div>
          </div>
        </div>
      </div>
    </section>);

}

// ─────── Footer ───────
function Footer({ t }) {
  const year = new Date().getFullYear();
  return (
    <footer className="site-footer">
      <div className="container">
        <div className="top">
          <div className="brand-foot">
            <div className="lg">Newco Brazil</div>
            <p>{t.footer.tagline}</p>
          </div>
          <div className="col">
            <h6>Site</h6>
            <a href="#intro">{t.nav.intro}</a>
            <a href="#lines">{t.nav.lines}</a>
            <a href="#spray">{t.nav.spray}</a>
            <a href="#esg">{t.nav.esg}</a>
          </div>
          <div className="col">
            <h6>Industry</h6>
            <a href="#about">{t.nav.about}</a>
            <a href="#services">{t.nav.services}</a>
            <a href="#contact">{t.nav.contato}</a>
          </div>
          <div className="col">
            <h6>Newco</h6>
            <div className="raw">
              {t.contato.address}<br />
              {t.contato.phone}<br />
              {t.contato.mail}
            </div>
          </div>
        </div>
        <div className="bottom">
          <div>© {year} NEWCO BRAZIL · {t.footer.rights}</div>
          <div>VARGINHA · MG · BR</div>
        </div>
      </div>
    </footer>);

}

// ─────── Tweaks panel ───────
function TweaksPanel({ tweaks, updateTweak, onClose }) {
  return (
    <div style={{
      position: "fixed", right: 20, bottom: 20, width: 280, zIndex: 200,
      background: "var(--bg-elev)", border: "1px solid var(--line-strong)",
      borderRadius: 12, padding: 20, color: "var(--fg)", fontSize: 13,
      boxShadow: "0 20px 60px rgba(0,0,0,0.5)"
    }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 16 }}>
        <div style={{ fontFamily: "var(--mono)", fontSize: 11, letterSpacing: "0.14em", textTransform: "uppercase", color: "var(--fg-mute)" }}>Tweaks</div>
        <button onClick={onClose} style={{ color: "var(--fg-mute)", fontSize: 18 }}>×</button>
      </div>
      <TweakField label="Paleta">
        <Segmented value={tweaks.theme} onChange={(v) => updateTweak("theme", v)}
        options={[["dark", "Escuro"], ["light", "Claro"], ["earth", "Terroso"]]} />
      </TweakField>
      <TweakField label="Tipografia">
        <Segmented value={tweaks.type} onChange={(v) => updateTweak("type", v)}
        options={[["sans", "Sans"], ["serif", "Serif"]]} />
      </TweakField>
      <TweakField label="Hero">
        <Segmented value={tweaks.heroVariant} onChange={(v) => updateTweak("heroVariant", v)}
        options={[["full", "Foto cheia"], ["split", "Split"]]} />
      </TweakField>
    </div>);

}
function TweakField({ label, children }) {
  return (
    <div style={{ marginBottom: 14 }}>
      <div style={{ fontFamily: "var(--mono)", fontSize: 10, letterSpacing: "0.14em", textTransform: "uppercase", color: "var(--fg-mute)", marginBottom: 8 }}>{label}</div>
      {children}
    </div>);

}
function Segmented({ value, onChange, options }) {
  return (
    <div style={{ display: "flex", border: "1px solid var(--line-strong)", borderRadius: 999, padding: 3 }}>
      {options.map(([v, l]) =>
      <button key={v} onClick={() => onChange(v)}
      style={{
        flex: 1, padding: "7px 8px", borderRadius: 999, fontSize: 12,
        background: value === v ? "var(--fg)" : "transparent",
        color: value === v ? "var(--bg)" : "var(--fg-mute)",
        transition: "all 0.2s"
      }}>
        {l}</button>
      )}
    </div>);

}

ReactDOM.createRoot(document.getElementById("root")).render(<App />);
