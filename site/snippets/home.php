<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>mi-data-backend</title>
  <style>
    :root { color-scheme: light dark; --fg: #1a1a1a; --muted: #666; --bg: #fafafa; --line: #ddd; --accent: #c81e0f; }
    @media (prefers-color-scheme: dark) { :root { --fg: #eee; --muted: #aaa; --bg: #161616; --line: #333; --accent: #ff6b5b; } }
    body { margin: 0; background: var(--bg); color: var(--fg); font: 16px/1.6 system-ui, -apple-system, sans-serif; }
    main { max-width: 40rem; margin: 0 auto; padding: 4rem 1rem; }
    h1 { margin: 0 0 .25rem; font-size: 1.75rem; }
    .sub { margin: 0 0 2rem; color: var(--muted); }
    h2 { margin-top: 2rem; font-size: 1.1rem; }
    a { color: var(--accent); }
    code { font-size: .9em; }
    ul { padding-left: 1.2rem; }
    footer { margin-top: 3rem; padding-top: 1rem; border-top: 1px solid var(--line); color: var(--muted); font-size: .875rem; }
  </style>
</head>
<body>
  <main>
    <h1>mi-data-backend</h1>
    <p class="sub">Medieninformatik · TH Köln</p>

    <p>
      Dies ist das Daten-Backend für die
      <a href="https://www.medieninformatik.th-koeln.de/">Website des Studiengangs Medieninformatik</a>.
      Es hat selbst kein Frontend, sondern stellt Inhalte als JSON-API bereit, die von der Website abgerufen werden.
    </p>

    <h2>API-Endpunkte</h2>
    <ul>
      <li><a href="<?= url('abschlussarbeiten') ?>"><code>GET /abschlussarbeiten</code></a> – alle veröffentlichten Abschlussarbeiten</li>
      <li><code>GET /abschlussarbeiten/{slug}</code> – eine einzelne Abschlussarbeit</li>
    </ul>

    <h2>Inhaltspflege</h2>
    <p>Redaktionelle Inhalte werden im <a href="<?= url('panel') ?>">Kirby Panel</a> gepflegt (Login mit TH-Köln-Account).</p>

    <h2>Fragen?</h2>
    <p>
      Bei Fragen zum Backend oder zur API wenden Sie sich bitte an
      Christian Noss: <a href="mailto:christian.noss@th-koeln.de">christian.noss@th-koeln.de</a>
    </p>

    <footer>mi-data-backend · <a href="https://www.medieninformatik.th-koeln.de/">medieninformatik.th-koeln.de</a></footer>
  </main>
</body>
</html>
