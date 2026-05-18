@include('web.Layout.head')
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

<style>
    /* ── RESET / BASE ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --red:      #b9252e;
        --red-dark: #941c24;
        --dark:     #1a1a1a;
        --mid:      #4a4a4a;
        --muted:    #888;
        --border:   #e8e8e8;
        --bg:       #fafafa;
        --white:    #ffffff;
    }

    body { background: var(--white); color: var(--dark); font-family: 'DM Sans', sans-serif; }

    /* ── BREADCRUMB ── */
    .fjk-breadcrumb {
        background: var(--dark);
        padding: 18px 0;
    }
    .fjk-breadcrumb-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .fjk-breadcrumb-path {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: rgba(255,255,255,0.5);
        font-family: 'DM Sans', sans-serif;
    }
    .fjk-breadcrumb-path a {
        color: rgba(255,255,255,0.5);
        text-decoration: none;
        transition: color 0.2s;
    }
    .fjk-breadcrumb-path a:hover { color: #fff; }
    .fjk-breadcrumb-path span { color: rgba(255,255,255,0.25); }
    .fjk-breadcrumb-path strong { color: var(--white); }
    .fjk-breadcrumb-title {
        font-family: 'Syne', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--red);
    }

    /* ── HERO VIDEO ── */
    .fjk-hero {
        position: relative;
        width: 100%;
        height: 45vh;
        overflow: hidden;
        background: var(--dark);
    }
    .fjk-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s ease;
        filter: brightness(0.65);
    }
    .fjk-hero:hover .fjk-hero-img {
        transform: scale(1.03);
    }
    .fjk-hero-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .fjk-play-btn {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--red);
        border: 3px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 0 0 0 rgba(185,37,46,0.5);
        animation: pulse 2.5s infinite;
    }
    .fjk-play-btn:hover {
        background: var(--red-dark);
        transform: scale(1.1);
        border-color: rgba(255,255,255,0.6);
    }
    .fjk-play-btn svg {
        width: 28px;
        height: 28px;
        fill: white;
        margin-left: 4px;
    }
    @keyframes pulse {
        0%   { box-shadow: 0 0 0 0 rgba(185,37,46,0.5); }
        70%  { box-shadow: 0 0 0 20px rgba(185,37,46,0); }
        100% { box-shadow: 0 0 0 0 rgba(185,37,46,0); }
    }
    .fjk-hero-label {
        font-family: 'Syne', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.7);
    }

    /* ── SECTION WRAPPER ── */
    .fjk-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 48px;
    }

    /* ── ABOUT INTRO ── */
    .fjk-about-intro {
        padding: 80px 0 64px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: start;
    }
    .fjk-about-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Syne', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 20px;
    }
    .fjk-about-tag::before {
        content: '';
        width: 28px;
        height: 2px;
        background: var(--red);
        border-radius: 2px;
    }
    .fjk-about-title {
        /*font-family: 'Syne', sans-serif;*/
        font-size: 42px;
        font-weight: 800;
        line-height: 1.12;
        color: var(--dark);
        margin-bottom: 24px;
    }
    .fjk-about-title em {
        font-style: normal;
        color: var(--red);
    }
    .fjk-about-desc {
        font-size: 15px;
        line-height: 1.8;
        color: var(--mid);
        font-weight: 300;
    }
    .fjk-about-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 40px;
    }
    .fjk-stat-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
    }
    .fjk-stat-number {
        font-family: 'Poppins';
        font-size: 36px;
        font-weight: 800;
        color: var(--red);
        line-height: 1;
        margin-bottom: 6px;
    }
    .fjk-stat-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 400;
    }

    /* ── FAQ ACCORDION ── */
    .fjk-faq {
        align-self: start;
        padding-top: 8px;
    }
    .fjk-faq-item {
        border-bottom: 1px solid var(--border);
    }
    .fjk-faq-item:first-child {
        border-top: 1px solid var(--border);
    }
    .fjk-faq-question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 0;
        cursor: pointer;
        user-select: none;
        list-style: none;
    }
    .fjk-faq-question::-webkit-details-marker { display: none; }
    .fjk-faq-question h4 {
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
        line-height: 1.4;
        transition: color 0.2s;
    }
    details[open] .fjk-faq-question h4 { color: var(--red); }
    .fjk-faq-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
        color: var(--muted);
    }
    details[open] .fjk-faq-icon {
        background: var(--red);
        border-color: var(--red);
        color: white;
        transform: rotate(45deg);
    }
    .fjk-faq-answer {
        font-size: 14px;
        line-height: 1.8;
        color: var(--mid);
        padding-bottom: 20px;
        font-weight: 300;
    }

    /* ── DIVIDER ── */
    .fjk-divider {
        height: 1px;
        background: var(--border);
        max-width: 1200px;
        margin: 0 auto 0;
        padding: 0 48px;
    }
    .fjk-divider-line {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border), transparent);
    }

    /* ── GALLERY ── */
    .fjk-gallery-section {
        background: var(--bg);
        padding: 80px 0;
    }
    .fjk-section-header {
        text-align: center;
        margin-bottom: 48px;
    }
    .fjk-section-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Syne', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--red);
        margin-bottom: 14px;
    }
    .fjk-section-tag::before,
    .fjk-section-tag::after {
        content: '';
        width: 20px;
        height: 2px;
        background: var(--red);
        border-radius: 2px;
    }
    .fjk-section-title {
        font-family: 'Syne', sans-serif;
        font-size: 36px;
        font-weight: 800;
        color: var(--dark);
    }

    /* Gallery grid */
    .fjk-gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    .fjk-gallery-grid .fjk-gitem:first-child {
        grid-column: span 2;
        grid-row: span 2;
    }
    .fjk-gitem {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background: var(--border);
        aspect-ratio: 1;
        cursor: pointer;
    }
    .fjk-gitem:first-child {
        aspect-ratio: auto;
    }
    .fjk-gitem img {
        width: 100%;
        height: 100%;
        object-fit: fill;
        display: block;
        transition: transform 0.5s ease;
    }
    .fjk-gitem:hover img { transform: scale(1.07); }
    .fjk-gitem-overlay {
        position: absolute;
        inset: 0;
        background: rgba(185,37,46,0);
        transition: background 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fjk-gitem:hover .fjk-gitem-overlay {
        background: rgba(185,37,46,0.3);
    }
    .fjk-gitem-overlay svg {
        width: 32px;
        height: 32px;
        color: white;
        opacity: 0;
        transform: scale(0.7);
        transition: all 0.3s;
    }
    .fjk-gitem:hover .fjk-gitem-overlay svg {
        opacity: 1;
        transform: scale(1);
    }

    /* ── MAP ── */
    .fjk-map-section {
        padding: 80px 0 0;
    }
    .fjk-map-wrap {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
    }
    .fjk-map-wrap iframe {
        display: block;
        width: 100%;
        height: 420px;
        border: none;
    }

    /* ── LIGHTBOX ── */
    /*.fjk-lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.92);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }*/
    .fjk-lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .fjk-lightbox.fjk-open {
        opacity: 1;
        pointer-events: all;
    }
    .fjk-lightbox img {
        max-width: 90vw;
        max-height: 85vh;
        border-radius: 8px;
        object-fit: contain;
    }
    .fjk-lightbox-close {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .fjk-about-intro {
            grid-template-columns: 1fr;
            gap: 48px;
            padding: 48px 0;
        }
        .fjk-about-title { font-size: 30px; }
        .fjk-section { padding: 0 24px; }
        .fjk-breadcrumb-inner { padding: 0 24px; flex-direction: column; align-items: flex-start; gap: 6px; }
        .fjk-hero { height: 17vh; }
        .fjk-gallery-grid { grid-template-columns: repeat(2, 1fr); }
        .fjk-gallery-grid .fjk-gitem:first-child { grid-column: span 2; }
        .fjk-section-title { font-size: 26px; }
        .fjk-map-section { padding: 48px 0 0; }
    }
    @media (max-width: 480px) {
        .fjk-gallery-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .fjk-about-stats { grid-template-columns: 1fr 1fr; }
    }
</style>

<body>
@include('web.Layout.header')

{{-- BREADCRUMB --}}
<div class="fjk-breadcrumb">
    <div class="fjk-breadcrumb-inner">
        <div class="fjk-breadcrumb-path">
            <a href="{{url('/')}}">Home</a>
            <span>›</span>
            <strong>About Us</strong>
        </div>
        <div class="fjk-breadcrumb-title">Fujika Company</div>
    </div>
</div>

{{-- HERO --}}
@php
    $ytUrl = $about[0]->linkyoutube;
    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $ytUrl, $matches);
    $ytEmbedUrl = isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1' : $ytUrl;
@endphp
<div class="fjk-hero">
    <img class="fjk-hero-img" src="{{$about[0]->photo}}" alt="Fujika Company">
    <div class="fjk-hero-overlay">
<!--        <a href="{{$about[0]->linkyoutube}}" target="_blank" class="fjk-play-btn mfp-iframe video">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </a>-->
    <button class="fjk-play-btn" onclick="fjkOpenVideo('{{$ytEmbedUrl}}')">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 5v14l11-7z"/>
        </svg>
    </button>
        <span class="fjk-hero-label">Watch Our Story</span>
    </div>
</div>

{{-- ABOUT + FAQ --}}
<div class="fjk-section">
    <div class="fjk-about-intro">

        {{-- Left: title + stats --}}
        <div>
            <div class="fjk-about-tag">About Our Company</div>
            <h1 class="fjk-about-title">18 Years of <em>Excellence</em> in Pumps & Equipment</h1>
            <p class="fjk-about-desc">Fujika has been a trusted name in pumps and industrial equipment across the Kingdom of Saudi Arabia for nearly two decades — delivering quality products and expert solutions to clients in every sector.</p>

            <div class="fjk-about-stats">
                <div class="fjk-stat-card">
                    <div class="fjk-stat-number">18+</div>
                    <div class="fjk-stat-label">Years of Experience</div>
                </div>
                <div class="fjk-stat-card">
                    <div class="fjk-stat-number">500+</div>
                    <div class="fjk-stat-label">Products Available</div>
                </div>
                <div class="fjk-stat-card">
                    <div class="fjk-stat-number">MENA</div>
                    <div class="fjk-stat-label">Based & Serving</div>
                </div>
                <div class="fjk-stat-card">
                    <div class="fjk-stat-number">24/7</div>
                    <div class="fjk-stat-label">Customer Support</div>
                </div>
            </div>
        </div>

        {{-- Right: FAQ accordion --}}
        <div class="fjk-faq">
            <details class="fjk-faq-item" open>
                <summary class="fjk-faq-question">
                    <h4>{{$about[0]->title1}}</h4>
                    <span class="fjk-faq-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                </summary>
                <div class="fjk-faq-answer">{{$about[0]->desc1}}</div>
            </details>
            <details class="fjk-faq-item">
                <summary class="fjk-faq-question">
                    <h4>{{$about[0]->title2}}</h4>
                    <span class="fjk-faq-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                </summary>
                <div class="fjk-faq-answer">{{$about[0]->desc2}}</div>
            </details>
            <details class="fjk-faq-item">
                <summary class="fjk-faq-question">
                    <h4>{{$about[0]->title3}}</h4>
                    <span class="fjk-faq-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                </summary>
                <div class="fjk-faq-answer">{{$about[0]->desc3}}</div>
            </details>
            <details class="fjk-faq-item">
                <summary class="fjk-faq-question">
                    <h4>{{$about[0]->title4}}</h4>
                    <span class="fjk-faq-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                </summary>
                <div class="fjk-faq-answer">{{$about[0]->desc4}}</div>
            </details>
        </div>

    </div>
</div>

{{-- GALLERY --}}
<div class="fjk-gallery-section">
    <div class="fjk-section">
        <div class="fjk-section-header">
            <div class="fjk-section-tag">Our Work</div>
            <h2 class="fjk-section-title">Gallery</h2>
        </div>
        <div class="fjk-gallery-grid">
            @foreach($gallery as $i => $g)
                <div class="fjk-gitem" onclick="fjkOpenLightbox('{{$g->path}}')">
                    <img src="{{$g->path}}" alt="Gallery image {{$i+1}}" loading="lazy">
                    <div class="fjk-gitem-overlay">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MAP --}}
<div class="fjk-map-section">
    <div class="fjk-section">
        <div class="fjk-section-header">
            <div class="fjk-section-tag">Find Us</div>
            <h2 class="fjk-section-title">Our Location</h2>
        </div>
        <div class="fjk-map-wrap">
            <iframe src="{{$about[0]->map}}" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<div style="height: 80px;"></div>

{{-- LIGHTBOX --}}
<!--<div class="fjk-lightbox" id="fjkLightbox" onclick="fjkCloseLightbox()">
    <button class="fjk-lightbox-close" onclick="fjkCloseLightbox()">✕</button>
    <img id="fjkLightboxImg" src="" alt="">
</div>-->
<div class="fjk-lightbox" id="fjkVideoLightbox" onclick="fjkCloseVideo(event)">
    <button class="fjk-lightbox-close" onclick="fjkCloseVideo()">✕</button>
<!--    <div style="position:relative; width:90vw; max-width:960px; aspect-ratio:16/9; border-radius:12px; overflow:hidden; background:#000;">
        <iframe id="fjkVideoFrame" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                style="width:100%; height:100%; display:block;"></iframe>
    </div>-->
    <div style="position:relative; width:680px; max-width:90vw; aspect-ratio:16/9; border-radius:12px; overflow:hidden; background:#000; box-shadow: 0 24px 60px rgba(0,0,0,0.6);">
        <iframe id="fjkVideoFrame" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                style="width:100%; height:100%; display:block;"></iframe>
    </div>
</div>

<script>
    function fjkOpenVideo(embedUrl) {
        document.getElementById('fjkVideoFrame').src = embedUrl;
        document.getElementById('fjkVideoLightbox').classList.add('fjk-open');
        document.body.style.overflow = 'hidden';
    }
    function fjkCloseVideo(e) {
        if (e && e.target !== document.getElementById('fjkVideoLightbox') && !e.target.classList.contains('fjk-lightbox-close')) return;
        document.getElementById('fjkVideoFrame').src = '';
        document.getElementById('fjkVideoLightbox').classList.remove('fjk-open');
        document.body.style.overflow = '';
    }
    function fjkOpenLightbox(src) {
        document.getElementById('fjkLightboxImg').src = src;
        document.getElementById('fjkLightbox').classList.add('fjk-open');
        document.body.style.overflow = 'hidden';
    }
    function fjkCloseLightbox() {
        document.getElementById('fjkLightbox').classList.remove('fjk-open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { fjkCloseVideo(); fjkCloseLightbox(); }
    });
</script>

@include('web.Layout.footer')
</body>
</html>