<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       FJK NAVBAR — prefixed with "fjk-" to avoid collisions
       ============================================================ */

    :root {
        --fjk-accent:      #ffffff;
        --fjk-accent-dark: #f0f0f0;
        --fjk-red:         #b9252e;
        --fjk-red-dark:    #941c24;
        --fjk-red-light:   #d42b36;
        --fjk-dark:        #1a0608;
        --fjk-dark-2:      #b9252e;
        --fjk-dark-3:      #941c24;
        --fjk-text:        #ffffff;
        --fjk-muted:       rgba(255,255,255,0.75);
        --fjk-border:      rgba(255,255,255,0.15);
        --fjk-nav-h:       72px;
        --fjk-bar-h:       40px;
    }

    /* ── TOP BAR ── */
    .fjk-topbar {
        /*background: #1a0608;*/
        height: var(--fjk-bar-h);
        display: flex;
        align-items: center;
        padding: 0 48px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .fjk-topbar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }
    .fjk-topbar-left {
        display: flex;
        gap: 28px;
        align-items: center;
    }
    .fjk-topbar-left span {
        font-size: 12px;
        color: black;
        display: flex;
        align-items: center;
        gap: 7px;
        letter-spacing: 0.02em;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
    }
    .fjk-topbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .fjk-social-link {
        width: 26px; height: 26px;
        border-radius: 6px;
        background: var(--fjk-dark-3);
        display: flex; align-items: center; justify-content: center;
        color: var(--fjk-muted);
        text-decoration: none;
        font-size: 11px;
        transition: all 0.2s;
        border: 1px solid var(--fjk-border);
    }
    .fjk-social-link:hover {
        background: rgba(255,255,255,0.2);
        color: #ffffff;
        border-color: rgba(255,255,255,0.3);
    }

    /* ── MAIN NAVBAR ── */
    .fjk-navbar {
        background: #b9252e;
        height: var(--fjk-nav-h);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid #941c24;
        box-shadow: 0 4px 24px rgba(0,0,0,0.35);
        font-family: 'DM Sans', sans-serif;
        overflow: visible;
    }
    .fjk-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 48px;
        position: relative;
    }

    /* Logo */
    .fjk-logo {
        display: flex;
        align-items: center;
        text-decoration: none;
        flex-shrink: 0;
    }
    .fjk-logo img {
        height: 48px;
        width: auto;
        display: block;
        /* invert to white since logo is likely dark on transparent */
        filter: brightness(0) invert(1);
    }

    /* Nav links */
    .fjk-nav-links {
        display: flex;
        align-items: center;
        gap: 4px;
        list-style: none;
        height: 100%;
        margin: 0; padding: 0;
    }
    .fjk-nav-links > li {
        height: 100%;
        display: flex;
        align-items: center;
        position: relative;
    }
    .fjk-nav-links > li > a {
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        font-size: 13.5px;
        color: rgba(255,255,255,0.82);
        text-decoration: none;
        padding: 0 16px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 5px;
        letter-spacing: 0.03em;
        transition: color 0.2s;
        position: relative;
    }
    .fjk-nav-links > li > a::after {
        content: '';
        position: absolute;
        bottom: 0; left: 16px; right: 16px;
        height: 3px;
        background: #ffffff;
        border-radius: 2px 2px 0 0;
        transform: scaleX(0);
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform-origin: center;
    }
    .fjk-nav-links > li > a:hover,
    .fjk-nav-links > li > a.fjk-active {
        color: #ffffff;
    }
    .fjk-nav-links > li > a:hover::after,
    .fjk-nav-links > li > a.fjk-active::after {
        transform: scaleX(1);
    }
    .fjk-nav-links > li.fjk-has-mega:hover > a {
        color: #ffffff;
    }
    .fjk-nav-links > li.fjk-has-mega:hover > a::after {
        transform: scaleX(1);
    }

    .fjk-chevron {
        width: 14px; height: 14px;
        transition: transform 0.25s;
        opacity: 0.5;
        flex-shrink: 0;
    }
    .fjk-nav-links > li.fjk-has-mega:hover .fjk-chevron {
        transform: rotate(180deg);
        opacity: 1;
    }

    /* ── MEGA MENU ── */
    .fjk-has-mega {
        position: static !important;
    }
    .fjk-mega-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-6px);
        width: 100vw;
        background: #1e1e1e;
        border-top: 3px solid #b9252e;
        border-bottom: 2px solid #941c24;
        box-shadow: 0 24px 60px rgba(0,0,0,0.55);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.22s ease, transform 0.22s ease;
        z-index: 9999;
    }
    .fjk-nav-links > li.fjk-has-mega:hover .fjk-mega-menu {
        opacity: 1;
        pointer-events: all;
        transform: translateX(-50%) translateY(0);
    }
    /* bridge gap so hover doesn't drop when moving cursor into menu */
    .fjk-nav-links > li.fjk-has-mega::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0; right: 0;
        height: 4px;
    }
    .fjk-mega-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 48px;
        display: flex;
        align-items: stretch;
        min-height: 56px;
    }

    /* Each column */
    .fjk-mega-col {
        min-width: 220px;
        flex-shrink: 0;
        padding: 12px 0;
        border-right: 1px solid rgba(255,255,255,0.07);
    }
    .fjk-mega-col:last-child { border-right: none; }
    .fjk-mega-col-label {
        font-family: 'Syne', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #b9252e;
        padding: 10px 20px 8px;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        margin-bottom: 4px;
    }

    /* Category items */
    .fjk-cat-list { list-style: none; margin: 0; padding: 0; }
    .fjk-cat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        color: #cccccc;
        text-decoration: none;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.15s;
        white-space: nowrap;
        border-left: 3px solid transparent;
    }
    .fjk-cat-item:hover,
    .fjk-cat-item.fjk-active {
        color: #ffffff;
        background: rgba(185,37,46,0.2);
        border-left-color: #b9252e;
        padding-left: 17px;
    }
    .fjk-cat-icon {
        width: 28px; height: 28px;
        border-radius: 7px;
        background: rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .fjk-cat-item:hover .fjk-cat-icon,
    .fjk-cat-item.fjk-active .fjk-cat-icon { background: #b9252e; }
    .fjk-sub-arrow {
        width: 13px; height: 13px;
        opacity: 0.35;
        margin-left: auto;
        flex-shrink: 0;
    }
    .fjk-cat-item.fjk-active .fjk-sub-arrow { opacity: 1; }

    /* Child column items */
    .fjk-col-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        color: rgba(255,255,255,0.65);
        text-decoration: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        transition: all 0.15s;
        white-space: nowrap;
        border-left: 3px solid transparent;
        cursor: pointer;
    }
    .fjk-col-item:hover,
    .fjk-col-item.fjk-active {
        color: #ffffff;
        background: rgba(185,37,46,0.15);
        border-left-color: #b9252e;
        padding-left: 17px;
    }
    .fjk-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: rgba(255,255,255,0.25);
        flex-shrink: 0;
        transition: background 0.15s;
    }
    .fjk-col-item:hover .fjk-dot,
    .fjk-col-item.fjk-active .fjk-dot { background: #b9252e; }

    /* Nav right */
    .fjk-nav-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .fjk-search-btn {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        display: flex; align-items: center; justify-content: center;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
    }
    .fjk-search-btn:hover {
        background: rgba(255,255,255,0.25);
        color: #ffffff;
        border-color: rgba(255,255,255,0.4);
    }
    .fjk-quote-btn {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 12.5px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        background: #ffffff;
        color: #b9252e;
        border: none;
        padding: 0 20px;
        height: 38px;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        display: flex; align-items: center; gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .fjk-quote-btn:hover {
        background: #f0f0f0;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        color: #b9252e;
    }

    /* ── SEARCH OVERLAY ── */
    .fjk-search-overlay {
        position: fixed;
        inset: 0;
        background: rgba(10,14,23,0.95);
        backdrop-filter: blur(12px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s;
    }
    .fjk-search-overlay.fjk-active {
        opacity: 1;
        pointer-events: all;
    }
    .fjk-search-box {
        width: 640px;
        max-width: 90vw;
        transform: translateY(16px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .fjk-search-overlay.fjk-active .fjk-search-box {
        transform: translateY(0);
    }
    .fjk-search-label {
        font-family: 'Syne', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--fjk-accent);
        margin-bottom: 16px;
    }
    .fjk-search-input-wrap {
        display: flex;
        align-items: center;
        background: var(--fjk-dark-3);
        border: 1px solid var(--fjk-border);
        border-radius: 14px;
        padding: 0 20px;
        gap: 12px;
        transition: border-color 0.2s;
    }
    .fjk-search-input-wrap:focus-within { border-color: var(--fjk-accent); }
    .fjk-search-input-wrap svg { color: var(--fjk-muted); flex-shrink: 0; }
    .fjk-search-input-wrap input {
        background: transparent;
        border: none;
        outline: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 22px;
        color: var(--fjk-text);
        width: 100%;
        height: 64px;
    }
    .fjk-search-input-wrap input::placeholder { color: var(--fjk-muted); }
    .fjk-search-hint {
        margin-top: 14px;
        font-size: 12px;
        color: var(--fjk-muted);
        display: flex;
        gap: 20px;
        font-family: 'DM Sans', sans-serif;
    }
    .fjk-search-hint kbd {
        background: var(--fjk-dark-3);
        border: 1px solid var(--fjk-border);
        border-radius: 5px;
        padding: 2px 7px;
        font-family: monospace;
        font-size: 11px;
    }
    .fjk-search-close {
        position: absolute;
        top: 32px; right: 32px;
        width: 40px; height: 40px;
        border-radius: 10px;
        background: var(--fjk-dark-3);
        border: 1px solid var(--fjk-border);
        color: var(--fjk-muted);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        transition: all 0.2s;
    }
    .fjk-search-close:hover { color: var(--fjk-text); border-color: var(--fjk-muted); }

    /* ── HAMBURGER ── */
    .fjk-hamburger {
        display: none;
        flex-direction: column;
        gap: 5px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.15);
    }
    .fjk-hamburger span {
        display: block;
        width: 20px; height: 2px;
        background: #ffffff;
        border-radius: 2px;
        transition: all 0.25s;
    }

    /* ── MOBILE DRAWER ── */
    .fjk-drawer-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 2999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .fjk-drawer-overlay.fjk-open { opacity: 1; pointer-events: all; }

    .fjk-mobile-drawer {
        position: fixed;
        top: 0; right: 0;
        width: 320px;
        height: 100vh;
        background: var(--fjk-dark-2);
        border-left: 1px solid var(--fjk-border);
        z-index: 3000;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        padding: 80px 24px 40px;
    }
    .fjk-mobile-drawer.fjk-open { transform: translateX(0); }

    .fjk-drawer-close {
        position: absolute;
        top: 20px; right: 20px;
        width: 36px; height: 36px;
        border-radius: 8px;
        background: var(--fjk-dark-3);
        border: 1px solid var(--fjk-border);
        color: var(--fjk-muted);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }
    .fjk-mobile-nav { list-style: none; margin: 0; padding: 0; }
    .fjk-mobile-nav > li { border-bottom: 1px solid var(--fjk-border); }
    .fjk-mobile-nav > li > a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 4px;
        color: var(--fjk-text);
        text-decoration: none;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        font-size: 15px;
    }
    .fjk-mobile-sub { list-style: none; padding: 0 0 12px 16px; display: none; margin: 0; background: #1e1e1e; }
    .fjk-mobile-sub.fjk-open { display: block; }
    .fjk-mobile-sub li a {
        display: block;
        padding: 9px 0;
        color: var(--fjk-muted);
        text-decoration: none;
        font-size: 13px;
        border-bottom: 1px solid var(--fjk-border);
        font-family: 'DM Sans', sans-serif;
    }
    .fjk-mobile-sub li a:hover { color: var(--fjk-accent); }
    .fjk-mobile-sub li a.fjk-view-all {
        color: var(--fjk-accent);
        font-weight: 600;
        font-family: 'Syne', sans-serif;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .fjk-nav-links { display: none; }
        .fjk-hamburger { display: flex; }
        .fjk-topbar { display: none; }
        .fjk-nav-inner { padding: 0 24px; }
        .fjk-quote-btn span { display: none; }
        .fjk-mega-menu { display: none; }
    }
</style>

{{-- ========================================================
     SCRIPTS  (place fill/fillvolt/etc. functions below —
     they are unchanged from original)
     ======================================================== --}}
<script>
    function fill(atts)
    {
        $name = atts.name;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);
                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }

                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Category";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].english_name;
                    option.value = data.data[i].id;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/getcategory/")}}';
        //   url = url.replace('id', atts.name);
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function fillvolt(atts)
    {
        $name = atts.name;
        $.ajaxSetup({ cache: false });
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);
                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }
                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Volt";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].v;
                    option.value = data.data[i].v;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/getvolt/")}}';
        //   url = url.replace('id', url.replace('id', atts.name);
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function fillhertz(atts)
    {
        $name = atts.name;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);
                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }
                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Hertz";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].Hertz;
                    option.value = data.data[i].Hertz;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/gethertz/")}}';
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function filldm(atts)
    {
        $name = atts.name;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);

                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }
                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Discharge Diameter";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].Discharge_diameter;
                    option.value = data.data[i].Discharge_diameter;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/getdm/")}}';
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function fillmaterial(atts)
    {
        $name = atts.name;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);

                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }
                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Material";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].Material;
                    option.value = data.data[i].Material;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/getmaterial/")}}';
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function fillrpm(atts)
    {
        $name = atts.name;
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);
                var Epop = document.getElementById('search-popup');

                if(Epop.classList.contains('active'))
                {
                    var selectpop = document.getElementById($name);
                }
                else {
                    var selectpop = document.getElementById($name+'1');
                }
                let options = selectpop.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectpop.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select RPM";
                option.value ="";
                selectpop.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].RPM;
                    option.value = data.data[i].RPM;
                    selectpop.appendChild(option);
                }

            }
        }
        url='{{url("web/getrpm/")}}';
        xmlhttp.open("GET", url , true);
        xmlhttp.send();

    }
    function fillsubcategory(atts)
    {
        $name=atts.name;
        var selectpop = document.getElementById($name+'1');
        var selectsub= document.getElementById("sub_cat_id11");
        $value=selectpop.value;

        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);
                let options = selectsub.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectsub.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Sub Category";
                option.value ="";
                selectsub.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].english_name;
                    option.value = data.data[i].id;
                    selectsub.appendChild(option);
                }
                selectsub.removeAttribute('disabled');

            }
        }
        url='{{url("sub_categories/by-category/id")}}';
        url = url.replace('id', $value);
        xmlhttp.open("GET", url , true);
        xmlhttp.send();
    }
    function fillsubcategory1(atts)
    {
        $name=atts.name;
        var Epop = document.getElementById('search-popup');

        if(Epop.classList.contains('active'))
        {
            var selectpop = document.getElementById($name);
            var selectsub=document.getElementById("sub_cat_id1");
        }
        else {
            var selectpop = document.getElementById($name+'11');
            var selectsub= document.getElementById("sub_cat_id111")
        }
        $value=selectpop.value;

        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);

                let options = selectsub.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectsub.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Sub Category";
                option.value ="";
                selectsub.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].english_name;
                    option.value = data.data[i].id;
                    selectsub.appendChild(option);
                }
                selectsub.removeAttribute('disabled');

            }
        }
        url='{{url("sub_categories1/by-parent/id")}}';
        url = url.replace('id', $value);
        xmlhttp.open("GET", url , true);
        xmlhttp.send();
    }
    function fillfamily(atts)
    {
        $name=atts.name;
        var Epop = document.getElementById('search-popup');

        if(Epop.classList.contains('active'))
        {
            var selectpop = document.getElementById($name);
            var selectsub=document.getElementById("family");
        }
        else {
            var selectpop = document.getElementById($name+'11');
            var selectsub= document.getElementById("family1")
        }
        $value=selectpop.value;

        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var data = JSON.parse(xmlhttp.responseText);
                console.log(data);

                let options = selectsub.getElementsByTagName('option');
                for (var i=options.length; i--;) {
                    selectsub.removeChild(options[i]);

                }
                var option = document.createElement("option");
                option.text = "Select Family";
                option.value ="";
                selectsub.appendChild(option);
                for (var i = 0; i < data.data.length; i++)
                {
                    var option = document.createElement("option");
                    option.text = data.data[i].english_name;
                    option.value = data.data[i].id;
                    selectsub.appendChild(option);
                }
                selectsub.removeAttribute('disabled');

            }
        }
        url='{{url("families/by-subcategory/id")}}';
        url = url.replace('id', $value);
        xmlhttp.open("GET", url , true);
        xmlhttp.send();
    }
</script>


{{-- ============================================================
     SEARCH POPUP  (original markup kept intact — only style block removed above)
     ============================================================ --}}
<div class="body-overlay" id="body-overlay"></div>
<div class="search-popup" id="search-popup">
    <form action="{{url('filterpop')}}" method="GET" class="search-form">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" style="color:black;" class="k-input k-textbox selectbox select2" name="keyword" placeholder="Enter Search word">
                    <input type="checkbox" id="commercial" name="commercial" value="1" style="float:right;margin-right:1%;margin-top:2%;">
                    <label for="commercial" style="float:right;margin-top:1.5%;margin-right:2%;"> Search by commercial name</label>
                </div>
            </div>
            <br>
        </div>
        <button type="submit" name="action" value="2" class="submit-btn" style="font-size:14px;">
            <i class="fa fa-search"></i> Search
        </button>
    </form>
</div>

{{-- ============================================================
     TOP BAR  (new fjk- design)
     ============================================================ --}}
<div class="fjk-topbar">
    <div class="fjk-topbar-inner">
        <div class="fjk-topbar-left">
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{$contact->address}}
            </span>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.69h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l.74-.74a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 17z"/></svg>
                KSA: {{$contact->phone1}}
            </span>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Sun – Fri: 8am – 6pm
            </span>
        </div>
        <div class="fjk-topbar-right">
            <a class="fjk-social-link" href="{{$contact->Facebook}}" title="Facebook">
                <i class="fa fa-facebook"></i>
            </a>
            <a class="fjk-social-link" href="{{$contact->Twitter}}" title="Twitter">
                <i class="fa fa-twitter"></i>
            </a>
            <a class="fjk-social-link" href="{{$contact->instagram}}" title="Instagram">
                <i class="fa fa-instagram"></i>
            </a>
            <a class="fjk-social-link" href="{{$contact->whatsapp}}" title="WhatsApp">
                <i class="fa fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>

{{-- ============================================================
     MAIN NAVBAR
     ============================================================ --}}
<nav class="fjk-navbar">
    <div class="fjk-nav-inner">

        {{-- Logo --}}
        <a href="{{url('/')}}" class="fjk-logo">
            <img src="{{asset('assets/images/Fujika-Logo-(1).png')}}" alt="Fujika Logo">
        </a>

        {{-- Desktop Nav Links --}}
        <ul class="fjk-nav-links">

            <li><a href="{{url('/')}}" class="fjk-active">Home</a></li>
            <li><a href="{{url('/about')}}">About</a></li>

            {{-- Products — Mega Menu --}}
            <li class="fjk-has-mega">
                <a href="{{url('/1/1/products')}}">
                    Products
                    <svg class="fjk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </a>

                <div class="fjk-mega-menu">
                    <div class="fjk-mega-inner" id="fjkMegaInner">

                        {{-- Column 1: Categories. JS appends sibling columns on hover. --}}
                        <div class="fjk-mega-col" id="fjkCol0">
                            <div class="fjk-mega-col-label">Category</div>
                            <ul class="fjk-cat-list">
                                @foreach($headerCategories as $cat)
                                    @if ($cat->english_name == 'Accessories')
                                        <li>
                                            <a class="fjk-cat-item"
                                               href="{{url('/'.$cat->id.'/7/'.$cat->english_name)}}">
                                                <span class="fjk-cat-icon">🔩</span>
                                                {{$cat->english_name}}
                                            </a>
                                        </li>
                                    @else
                                        @php
                                            $sub = $cat->subCategories;
                                        @endphp
                                        <li>
                                            <a class="fjk-cat-item"
                                               href="{{url('/'.$cat->id.'/2/'.$cat->english_name)}}"
                                               @if(count($sub) > 0) data-cat-id="{{$cat->id}}" @endif>
                                                <span class="fjk-cat-icon">⚙️</span>
                                                {{$cat->english_name}}
                                                @if(count($sub) > 0)
                                                    <svg class="fjk-sub-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                    </div>{{-- /.fjk-mega-inner --}}
                </div>{{-- /.fjk-mega-menu --}}
            </li>

            <li><a href="{{url('/contactus')}}">Contact</a></li>

        </ul>{{-- /.fjk-nav-links --}}

        {{-- Right Actions --}}
        <div class="fjk-nav-right">
            <button class="fjk-search-btn" id="fjkSearchToggle" title="Search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
            <a href="{{url('/contactus')}}" class="fjk-quote-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span>Get a Quote</span>
            </a>
            <div class="fjk-hamburger" id="fjkHamburger">
                <span></span><span></span><span></span>
            </div>
        </div>

    </div>{{-- /.fjk-nav-inner --}}
</nav>

{{-- ============================================================
     SEARCH OVERLAY  (new fjk- powered)
     ============================================================ --}}
<div class="fjk-search-overlay" id="fjkSearchOverlay">
    <button class="fjk-search-close" id="fjkSearchClose">✕</button>
    <div class="fjk-search-box">
        <div class="fjk-search-label">Search Products</div>
        <form action="{{url('filterpop')}}" method="GET">
            <div class="fjk-search-input-wrap">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="keyword" placeholder="Search pumps, HVAC, accessories…" id="fjkSearchInput">
            </div>
            <div class="fjk-search-hint">
                <span><kbd>↵</kbd> to search</span>
                <span><kbd>Esc</kbd> to close</span>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================
     MOBILE DRAWER
     ============================================================ --}}
<div class="fjk-drawer-overlay" id="fjkDrawerOverlay"></div>
<div class="fjk-mobile-drawer" id="fjkMobileDrawer">
    <button class="fjk-drawer-close" id="fjkDrawerClose">✕</button>
    <ul class="fjk-mobile-nav">
        <li><a href="{{url('/')}}">Home</a></li>
        <li><a href="{{url('/about')}}">About</a></li>
        <li>
            <a href="#" onclick="fjkToggleMobileSub(event,'fjkMobileProducts')">
                Products
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <ul class="fjk-mobile-sub" id="fjkMobileProducts">
                @foreach($headerCategories as $cat)
                    @if ($cat->english_name == 'Accessories')
                        <li><a href="{{url('/'.$cat->id.'/7/'.$cat->english_name)}}">{{$cat->english_name}}</a></li>
                    @else
                        @php
                            $sub = $cat->subCategories;
                        @endphp
                        @if(count($sub) == 0)
                            <li><a href="{{url('/'.$cat->id.'/2/'.$cat->english_name)}}">{{$cat->english_name}}</a></li>
                        @else
                            <li>
                                <a href="#" onclick="fjkToggleMobileSub(event,'fjkMobileCat{{$cat->id}}')">
                                    {{$cat->english_name}}
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </a>
                                <ul class="fjk-mobile-sub" id="fjkMobileCat{{$cat->id}}">
                                    @foreach($sub as $subcat)
                                        @php
                                            $sub1 = $subcat->subCategory1s;
                                        @endphp
                                        @if(count($sub1) == 0)
                                            <li><a href="{{url('/'.$subcat->id.'/3/'.$subcat->english_name)}}">{{$subcat->english_name}}</a></li>
                                        @else
                                            <li>
                                                <a href="#" onclick="fjkToggleMobileSub(event,'fjkMobileSub{{$subcat->id}}')">
                                                    {{$subcat->english_name}}
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                                </a>
                                                <ul class="fjk-mobile-sub" id="fjkMobileSub{{$subcat->id}}">
                                                    @foreach($sub1 as $subcat1)
                                                        @php
                                                            $family = $subcat1->families;
                                                        @endphp
                                                        @if(count($family) == 0)
                                                            <li><a href="{{url('/'.$subcat1->id.'/4/'.$subcat1->english_name)}}">{{$subcat1->english_name}}</a></li>
                                                        @else
                                                            <li>
                                                                <a href="#" onclick="fjkToggleMobileSub(event,'fjkMobileType{{$subcat1->id}}')">
                                                                    {{$subcat1->english_name}}
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                                                </a>
                                                                <ul class="fjk-mobile-sub" id="fjkMobileType{{$subcat1->id}}">
                                                                    @foreach($family as $fam)
                                                                        <li><a href="{{url('/'.$fam->id.'/5/'.$fam->english_name)}}">{{$fam->english_name}}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif
                @endforeach
                <li><a href="{{url('/1/1/products')}}" class="fjk-view-all">View All Products →</a></li>
            </ul>
        </li>
        <li><a href="{{url('/contactus')}}">Contact</a></li>
    </ul>
</div>

{{-- ============================================================
     NAV SCRIPTS
     ============================================================ --}}

@php
    $fjkTree = [];
    foreach($headerCategories as $cat) {
        $subs = $cat->subCategories;

        $subsArr = [];
        foreach($subs as $s) {
            $types = $s->subCategory1s;

            $typesArr = [];
            foreach($types as $t) {
                $fams = $t->families;

                $famsArr = [];
                foreach($fams as $f) {
                    $famsArr[] = ['id' => $f->id, 'name' => $f->english_name, 'url' => url('/'.$f->id.'/5/'.$f->english_name)];
                }
                $typesArr[] = ['id' => $t->id, 'name' => $t->english_name, 'url' => url('/'.$t->id.'/4/'.$t->english_name), 'families' => $famsArr];
            }
            $subsArr[] = ['id' => $s->id, 'name' => $s->english_name, 'url' => url('/'.$s->id.'/3/'.$s->english_name), 'types' => $typesArr];
        }

        $fjkTree[] = [
            'id'   => $cat->id,
            'name' => $cat->english_name,
            'url'  => $cat->english_name == 'Accessories' ? url('/'.$cat->id.'/7/'.$cat->english_name) : url('/'.$cat->id.'/2/'.$cat->english_name),
            'subs' => $subsArr
        ];
    }
@endphp

<script>
    var fjkTree = {!! json_encode($fjkTree) !!};
</script>

<script>
    function fjkToggleMobileSub(e, id) {
        e.preventDefault();
        var el = document.getElementById(id);
        if (el) el.classList.toggle('fjk-open');
    }
    document.addEventListener('DOMContentLoaded', function () {
        console.log('=== FJK NAV DEBUG ===');
        (function () {

        /* ── Mega menu column builder ── */
        var megaInner   = document.getElementById('fjkMegaInner');
        var productsLi  = document.querySelector('.fjk-has-mega');

        // Reset to just col0 when menu closes
        productsLi.addEventListener('mouseleave', function () {
            removeColsAfter(0);
            clearActive(document.getElementById('fjkCol0'));
        });

        function removeColsAfter(keepIndex) {
            var cols = megaInner.querySelectorAll('.fjk-mega-col');
            cols.forEach(function(col, i) { if (i > keepIndex) col.remove(); });
        }

        function clearActive(col) {
            col.querySelectorAll('.fjk-active').forEach(function(el) { el.classList.remove('fjk-active'); });
        }

        function makeCol(label, items, level, childKey, childLabel) {
            var col = document.createElement('div');
            col.className = 'fjk-mega-col';
            col.dataset.level = level;

            var lbl = document.createElement('div');
            lbl.className = 'fjk-mega-col-label';
            lbl.textContent = label;
            col.appendChild(lbl);

            items.forEach(function(item) {
                var a = document.createElement('a');
                a.className = 'fjk-col-item';
                a.href = item.url;

                var dot = document.createElement('span');
                dot.className = 'fjk-dot';
                a.appendChild(dot);
                a.appendChild(document.createTextNode(item.name));

                var children = item[childKey];
                if (children && children.length) {
                    var arr = document.createElementNS('http://www.w3.org/2000/svg','svg');
                    arr.setAttribute('viewBox','0 0 24 24');
                    arr.setAttribute('fill','none');
                    arr.setAttribute('stroke','currentColor');
                    arr.setAttribute('stroke-width','2.5');
                    arr.setAttribute('class','fjk-sub-arrow');
                    var poly = document.createElementNS('http://www.w3.org/2000/svg','polyline');
                    poly.setAttribute('points','9 18 15 12 9 6');
                    arr.appendChild(poly);
                    a.appendChild(arr);

                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        removeColsAfter(level);
                        clearActive(col);
                        a.classList.add('fjk-active');
                        var nextChildKey = childKey === 'subs' ? 'types' : 'families';
                        var nextLabel    = childKey === 'subs' ? 'Type' : 'Family';
                        var nextCol = makeCol(childLabel, children, level + 1, nextChildKey, nextLabel);
                        megaInner.appendChild(nextCol);
                    });
                } else {
                    a.addEventListener('click', function (e) {
                        // no children — let the link navigate normally
                        removeColsAfter(level);
                        clearActive(col);
                        a.classList.add('fjk-active');
                    });
                }

                col.appendChild(a);
            });

            return col;
        }

        // Hook category items in col0
        document.getElementById('fjkCol0').querySelectorAll('[data-cat-id]').forEach(function(catLink) {
            console.log('Listener attached to:', catLink.dataset.catId);
            catLink.addEventListener('click', function (e) {
                console.log('Click fired on catId:', catLink.dataset.catId);
                var catId   = parseInt(catLink.dataset.catId);
                var catData = fjkTree.find(function(c) { return c.id == catId; });
                console.log('catId:', catId, 'type:', typeof catId);
                console.log('catData found:', catData);
                console.log('fjkTree ids:', fjkTree.map(function(c){ return c.id + ' (' + typeof c.id + ')'; }));

                if (!catData || !catData.subs.length) {
                    console.log('EARLY RETURN — catData:', catData);
                    return;
                }

                e.preventDefault();
                removeColsAfter(0);
                clearActive(document.getElementById('fjkCol0'));
                catLink.classList.add('fjk-active');

                var col1 = makeCol('Sub Category', catData.subs, 1, 'types', 'Type');
                megaInner.appendChild(col1);
            });
        });

        /* ── Search overlay ── */
        var searchToggle  = document.getElementById('fjkSearchToggle');
        var searchOverlay = document.getElementById('fjkSearchOverlay');
        var searchClose   = document.getElementById('fjkSearchClose');
        var searchInput   = document.getElementById('fjkSearchInput');
        searchToggle.addEventListener('click', function () {
            searchOverlay.classList.add('fjk-active');
            setTimeout(function () { searchInput.focus(); }, 300);
        });
        searchClose.addEventListener('click', function () { searchOverlay.classList.remove('fjk-active'); });
        searchOverlay.addEventListener('click', function (e) {
            if (e.target === searchOverlay) searchOverlay.classList.remove('fjk-active');
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') searchOverlay.classList.remove('fjk-active');
        });

        /* ── Mobile drawer ── */
        var hamburger     = document.getElementById('fjkHamburger');
        var mobileDrawer  = document.getElementById('fjkMobileDrawer');
        var drawerOverlay = document.getElementById('fjkDrawerOverlay');
        var drawerClose   = document.getElementById('fjkDrawerClose');
        function openDrawer()  { mobileDrawer.classList.add('fjk-open'); drawerOverlay.classList.add('fjk-open'); }
        function closeDrawer() { mobileDrawer.classList.remove('fjk-open'); drawerOverlay.classList.remove('fjk-open'); }
        hamburger.addEventListener('click', openDrawer);
        drawerClose.addEventListener('click', closeDrawer);
        drawerOverlay.addEventListener('click', closeDrawer);
        })();
    });
</script>
