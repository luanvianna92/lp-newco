// Newco Brazil — App component (React + Babel) — v2
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
  const t = window.NEWCO_I18N[lang];

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
      <Hero t={t} variant={tweaks.heroVariant} />
      <Quem t={t} />
      <Spray t={t} />
      <Oque t={t} />
      <Produtos t={t} />
      <Logistica t={t} />
      <ESG t={t} />
      <Certs t={t} />
      <Contato t={t} />
      <Footer t={t} />
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
  ["quem", "quem"],
  ["spray", "spray"],
  ["oque", "oque"],
  ["produtos", "produtos"],
  ["logistica", "logistica"],
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
        <div className="lang-switch" role="group" aria-label="Language">
          <button className={lang === "pt" ? "active" : ""} onClick={() => setLang("pt")}>PT</button>
          <button className={lang === "en" ? "active" : ""} onClick={() => setLang("en")}>EN</button>
        </div>
        <a className="cta-pill" href="#contact">
          {t.header.cta}
          <svg className="arr" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
        </a>
      </div>
    </header>);

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
function Hero({ t, variant }) {
  return (
    <section id="intro" className="hero" data-variant={variant} data-screen-label="01 Intro">
      <div className="hero-bg" />
      <div className="hero-grain" />
      <Particles count={28} />
      <div className="container">
        <div className="hero-grid">
          <div className="hero-titles">
            <div className="hero-eyebrow eyebrow">{t.hero.eyebrow}</div>
            <h1 className="display">
              <span className="ln">{t.hero.title_line1}</span>
              <span className="ln">{t.hero.title_line2}</span>
              <span className="ln">{t.hero.title_line3}</span>
            </h1>
          </div>
          <div className="hero-side">
            <p className="lede">{t.hero.subtitle}</p>
            <div className="hero-actions">
              <a href="#produtos" className="cta-pill">
                {t.hero.cta_primary}
                <svg className="arr" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
              </a>
              <a href="#spray" className="cta-pill ghost">{t.hero.cta_secondary}</a>
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

// ─────── Quem somos ───────
function Quem({ t }) {
  return (
    <section id="quem" className="section quem has-photo-bg quem-bg" data-screen-label="02 Quem somos">
      <div className="photo-bg quem-photo" style={{ backgroundImage: "url('assets/one-original.jpg')" }} />
      <div className="photo-bg-overlay quem-overlay" />
      <div className="container">
        <div className="quem-grid">
          <div className="quem-text reveal">
            <div className="eyebrow">{t.quem.eyebrow}</div>
            <h2 className="display tight">{t.quem.title}</h2>
            <p>{t.quem.body_1}</p>
            <p>{t.quem.body_2}</p>
            <div className="stats">
              {t.quem.stats.map((s, i) =>
              <div className="stat" key={i}>
                  <div className="num">{s.num}</div>
                  <div className="lab">{s.label}</div>
                </div>
              )}
            </div>
          </div>
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

// ─────── Icons ───────
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

// ─────── O que fazemos (capabilities — vertical labeled cards) ───────
function Oque({ t }) {
  return (
    <section id="oque" className="section oque has-photo-bg" data-screen-label="04 O que fazemos">
      <div className="photo-bg" style={{ backgroundImage: "url('assets/morango-bg.jpg')" }} />
      <div className="photo-bg-overlay" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.oque.eyebrow}</div>
            <h2 className="display tight">{t.oque.title}</h2>
          </div>
          <p className="lede reveal">{t.oque.body}</p>
        </div>
        <div className="capabilities stagger">
          {t.oque.capabilities.map((c, i) =>
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

// ─────── Produtos (Nossas linhas) ───────
function Produtos({ t }) {
  return (
    <section id="produtos" className="section produtos" data-screen-label="05 Nossas linhas">
      <div className="tex gradient-deep" />
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.produtos.eyebrow}</div>
            <h2 className="display tight">{t.produtos.title}</h2>
          </div>
          <p className="lede reveal">{t.produtos.subtitle}</p>
        </div>
        <div className="produtos-grid stagger">
          {t.produtos.categories.map((c, i) =>
          <div className="produto-card" key={i}>
              <div className="img" style={{ backgroundImage: `url('${c.img}')` }} />
              <div className="meta">
                <div className="ti">{c.name}</div>
                <div className="de">{c.desc}</div>
                <div className="arrow">
                  <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </section>);

}

// ─────── Logística ───────
function Logistica({ t }) {
  return (
    <section id="logistica" className="section logistica" data-screen-label="06 Localização">
      <div className="tex dust"><DustField /></div>
      <div className="container">
        <div className="section-head">
          <div className="reveal">
            <div className="eyebrow">{t.logistica.eyebrow}</div>
            <h2 className="display tight">{t.logistica.title}</h2>
          </div>
          <p className="lede reveal">{t.logistica.subtitle}</p>
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
              {t.logistica.routes.map((r, i) =>
              <span className="r" key={i}><b>{r.city}</b> · {r.km}</span>
              )}
            </div>
          </div>
          <div className="logistica-stats stagger">
            {t.logistica.stats.map((s, i) =>
            <div className="lstat" key={i}>
                <div className="num">{s.num}</div>
                <div className="lab">{s.label}</div>
              </div>
            )}
          </div>
        </div>
        <div className="logistica-bullets reveal">
          <div className="logistica-bullets-head">{t.logistica.bullets_title}</div>
          <div>
            <div className="logistica-bullets-list">
              {t.logistica.bullets.map((b, i) =>
              <div className="lb" key={i}>
                  <h5>{b.title}</h5>
                  <p>{b.desc}</p>
                </div>
              )}
            </div>
            <div className="source">{t.logistica.source}</div>
          </div>
        </div>
      </div>
    </section>);

}

function DustField() {
  // Particles for "dust" texture variant
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

// ─────── ESG ───────
function ESG({ t }) {
  return (
    <section id="esg" className="section esg" data-screen-label="07 ESG">
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
              {t.esg.col1_items.map((it, i) =>
              <div className="ei" key={i}>
                  <h4>{it.title}</h4>
                  <p>{it.desc}</p>
                </div>
              )}
            </div>
          </div>
          <div className="esg-col">
            <h3><span className="ic">◉</span>{t.esg.col2_title}</h3>
            <div className="esg-list">
              {t.esg.col2_items.map((it, i) =>
              <div className="ei" key={i}>
                  <h4>{it.title}</h4>
                  <p>{it.desc}</p>
                </div>
              )}
            </div>
          </div>
        </div>
        <div className="esg-closing reveal">{t.esg.closing}</div>
      </div>
    </section>);

}

// ─────── Certifications (animated marquee) ───────
function Certs({ t }) {
  const list = t.cert.items;
  // Duplicate for infinite marquee
  const doubled = [...list, ...list];
  return (
    <section id="cert" className="section certs-section" data-screen-label="08 Certificações">
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
          <div className="cert-badge" key={i}>
              <div className="seal" />
              <div className="cn">{c.name}</div>
              <div className="cd">{c.desc}</div>
            </div>
          )}
        </div>
      </div>
    </section>);

}

// ─────── Contato ───────
function Contato({ t }) {
  // Same endpoint as legacy site
  return (
    <section id="contact" className="section contato" data-screen-label="09 Contato">
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
            <a href="#quem">{t.nav.quem}</a>
            <a href="#spray">{t.nav.spray}</a>
            <a href="#produtos">{t.nav.produtos}</a>
          </div>
          <div className="col">
            <h6>Industry</h6>
            <a href="#logistica">{t.nav.logistica}</a>
            <a href="#esg">{t.nav.esg}</a>
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