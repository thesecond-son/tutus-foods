<style>
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-bg: #fff8f9;
    --header-h: 80px;
  }

  header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: var(--header-h);
    background: #fff;
    z-index: 50;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }

  main {
    padding-top: var(--header-h);
  }

  html {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
  }

  /* Slides */
  .slide {
    position: absolute; inset: 0;
    opacity: 0;
    transition: opacity 1.2s ease-in-out;
    width: 100%; height: 100%;
    object-fit: cover;
  }
  .slide.active { opacity: 1; z-index: 1; }

  .circle-card img {
    transition: transform .4s ease, box-shadow .4s ease;
  }
  .circle-card:hover img {
    transform: scale(1.05);
    box-shadow: 0 6px 25px rgba(255,88,135,0.25);
  }

  .section-title {
    font-family: 'Playfair Display', serif;
  }

  @media (max-width: 768px) {
    .slide-text {
      padding: 1.5rem;
    }
  }

  /* Fade-in used originally */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fadeIn {
    animation: fadeIn 1.2s ease-out forwards;
  }

  /* NEW: soft card style used for Order Cut-off, Newsletter, and footer echoes */
  .soft-card {
    background: linear-gradient(180deg, rgba(255, 227, 234, 0.9), rgba(255, 244, 246, 0.95));
    border-radius: 1rem;
    border: 1px solid rgba(255,88,135,0.08);
    box-shadow: 0 10px 30px rgba(15,15,15,0.04);
  }

  /* NEW: signature brand overlay style (edible + luxe) */
  .brand-tile {
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
    transition: transform .55s cubic-bezier(.2,.9,.3,1), box-shadow .55s;
    box-shadow: 0 6px 18px rgba(15,15,15,0.06);
    background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(0,0,0,0.02));
  }
  .brand-tile:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 18px 40px rgba(15,15,15,0.10);
  }
  .brand-tile .brand-caption {
    background: linear-gradient(180deg, rgba(0,0,0,0.55), rgba(0,0,0,0.22));
    padding: 1.25rem;
  }
  .brand-badge {
    display:inline-block;
    padding: .35rem .7rem;
    border-radius:999px;
    background: linear-gradient(90deg, rgba(255,88,135,0.95), rgba(255,136,166,0.9));
    color: white;
    font-weight: 700;
    letter-spacing: .04em;
    font-size: .72rem;
  }

  /* Scroll reveal (fade-up) */
  .fade-up {
    opacity: 0;
    transform: translateY(18px);
    transition: opacity .7s ease-out, transform .7s ease-out;
    will-change: opacity, transform;
  }
  .fade-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* small utility for Countdown numbers styling */
  #christmas-countdown > div > div {
    font-weight: 700;
    font-size: 1.6rem;
    color: #b91c1c; /* red-700 */
  }

  /* minor responsive tweaks */
  @media (min-width: 1024px) {
    .brand-tile { height: 440px; }
  }

  @media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }
}

</style>