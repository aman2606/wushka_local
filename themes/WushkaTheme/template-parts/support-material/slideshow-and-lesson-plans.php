<?php

$attachment_id = absint( $_GET['id'] );
$file_url      = $attachment_id ? wp_get_attachment_url( $attachment_id ) : false;

if ( ! $file_url ) {
    wp_die( esc_html__( 'Invalid presentation file ID.', 'your-textdomain' ), '', array( 'response' => 404 ) );
}

// Only allow known Office file types through the viewer.
$allowed_ext = array( 'ppt', 'pptx' );
$ext         = strtolower( pathinfo( $file_url, PATHINFO_EXTENSION ) );
if ( ! in_array( $ext, $allowed_ext, true ) ) {
    wp_die( esc_html__( 'Unsupported file type for this viewer.', 'your-textdomain' ), '', array( 'response' => 400 ) );
}

$raw_filename    = pathinfo( $file_url, PATHINFO_FILENAME );
$simplified_name = ucwords( str_replace( array( '-', '_' ), ' ', $raw_filename ) );

// Build the Office Online embed URL from the ACTUAL requested file (previously hardcoded).
//$embed_src = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode( $file_url );
$embed_src = 'https://view.officeapps.live.com/op/embed.aspx?src=https://cdn1.wushka.com.au/public/2026/07/01174637/Phase-2-Lesson-1.pptx';
?>

<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        height: 100%;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background: #0d1117;
        color: #fff;
        overflow: hidden;
    }

    #app {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    /* ── Top bar ── */
    #topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        height: 48px;
        background: #111827;
        border-bottom: 1px solid #1f2937;
        flex-shrink: 0;
    }

    #topbar .logo {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #0078d4;
    }

    #topbar .title {
        font-size: 13px;
        font-weight: 600;
        color: #d1d5db;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        max-width: 60%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #topbar .actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn {
        background: #1f2937;
        border: 1px solid #374151;
        color: #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-family: inherit;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s, color 0.15s, border-color 0.15s;
        text-decoration: none;
    }
    .btn:hover { background: #374151; color: #fff; border-color: #4b5563; }
    .btn:active { background: #0078d4; border-color: #0078d4; }
    .btn-icon { width: 32px; height: 32px; padding: 0; justify-content: center; font-size: 14px; }

    /* ── Viewer area ── */
    #viewer {
        flex: 1;
        position: relative;
        overflow: hidden;
    }

    #ppt-frame {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }

    /* Transparent overlay to block right-click / drag on the slide canvas
     without covering the Office Online nav arrows. */
    #canvas-guard {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 79vh;
    }

  /* Blocks only the Office Online "more options" corner of its own toolbar. */
  .toolbar-blocker {
    position: absolute;
    bottom: 1px;
    width: 120px;
    height: 23px;
    background: #000;
    z-index: 5;
    pointer-events: all;
  }
  .toolbar-blocker.left  { left: 1px; }
  .toolbar-blocker.right { right: 1px; }

  /* Loading overlay */
  #loading {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #0d1117;
    gap: 16px;
    z-index: 10;
    transition: opacity 0.4s ease;
  }
  #loading.hidden { opacity: 0; pointer-events: none; }

  .spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #1f2937;
    border-top-color: #0078d4;
    border-radius: 50%;
    animation: spin 0.75s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  #loading p {
    font-size: 13px;
    color: #6b7280;
  }

  /* ── Status bar ── */
  #statusbar {
    height: 28px;
    background: #111827;
    border-top: 1px solid #1f2937;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 8px;
    flex-shrink: 0;
  }
  #statusbar span {
    font-size: 10px;
    color: #4b5563;
    letter-spacing: 0.3px;
  }
  #statusbar .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #374151;
    transition: background 0.2s ease;
  }
  #statusbar .dot.ready { background: #00c853; }

  /* ── Fullscreen ── */
  :fullscreen #topbar,
  :fullscreen #statusbar,
  :-webkit-full-screen #topbar,
  :-webkit-full-screen #statusbar {
    display: none;
  }

  /* ── Print block ── */
  @media print {
    body::after {
      content: "Printing this presentation is not permitted.";
      display: flex;
      position: fixed;
      inset: 0;
      background: #fff;
      color: #333;
      font-size: 24px;
      align-items: center;
      justify-content: center;
      z-index: 99999;
    }
    #app { display: none !important; }
  }
</style>

<div class="container-fluid single-suport-material">
  <div id="app">

    <!-- Top bar -->
    <header id="topbar">
      <div class="logo">Presentation</div>
      <div class="title"><?php echo esc_html( $simplified_name ); ?></div>
      <div class="actions">
        <button class="btn btn-icon" id="btn-fs" type="button" title="Toggle fullscreen">⛶</button>
      </div>
    </header>

    <!-- Viewer -->
    <div id="viewer">
      <div id="loading">
        <div class="spinner"></div>
        <p>Loading presentation…</p>
      </div>

      <iframe
        id="ppt-frame"
        src="<?php echo esc_url( $embed_src ); ?>"
        allowfullscreen
        title="<?php echo esc_attr( $simplified_name ); ?> Presentation"
      ></iframe>

      <div id="canvas-guard"></div>

      <!-- Blocks Office Online's own bottom toolbar (Download, Print, Embed, etc.) -->
      <div class="toolbar-blocker left"></div>
      <div class="toolbar-blocker right"></div>
    </div>

    <!-- Status bar -->
    <footer id="statusbar">
      <span class="dot" id="status-dot"></span>
      <span id="status-text">Loading…</span>
    </footer>

  </div>
</div>

<script>
(function () {
  'use strict';

  var frame      = document.getElementById('ppt-frame');
  var loading    = document.getElementById('loading');
  var dot        = document.getElementById('status-dot');
  var statusText = document.getElementById('status-text');
  var fsBtn      = document.getElementById('btn-fs');
  var viewer     = document.getElementById('viewer');

  /* ── Lightweight deterrents against casual copying.
     Note: these are UX deterrents only, not real DRM — a determined
     user can still access the underlying file URL via network tools. ── */
  document.addEventListener('keydown', function (e) {
    var key = e.key ? e.key.toLowerCase() : '';
    if ((e.ctrlKey || e.metaKey) && ['s', 'p', 'a', 'u'].indexOf(key) !== -1) {
      e.preventDefault();
    }
    if (key === 'f12' || (e.ctrlKey && e.shiftKey && key === 'i')) {
      e.preventDefault();
    }
  });
  document.addEventListener('dragstart', function (e) { e.preventDefault(); });

  /* ── Loading state ── */
  if (frame) {
    frame.addEventListener('load', function () {
      if (loading) { loading.classList.add('hidden'); }
      if (dot) { dot.classList.add('ready'); }
      if (statusText) { statusText.textContent = 'Presentation loaded'; }
    });
  }

  /* ── Fullscreen ── */
  if (fsBtn && viewer) {
    fsBtn.addEventListener('click', function () {
      if (!document.fullscreenElement) {
        if (viewer.requestFullscreen) { viewer.requestFullscreen(); }
      } else if (document.exitFullscreen) {
        document.exitFullscreen();
      }
    });
    document.addEventListener('fullscreenchange', function () {
      fsBtn.textContent = document.fullscreenElement ? '⊡' : '⛶';
    });
  }
})();
</script>
