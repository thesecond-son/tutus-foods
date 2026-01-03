<style>
  :root {
    --brand-pink: #ff5887;
    --brand-pink-light: #ff88a6;
    --brand-light-bg: #fff8f9;
  }

  #productModalContent {
    scroll-behavior: smooth;
  }

  #productModalWrapper {
    transition: opacity 0.3s ease;
  }
  #productModalWrapper.hidden {
    opacity: 0;
    pointer-events: none;
  }
  #productModalWrapper.flex {
    opacity: 1;
  }
</style>
