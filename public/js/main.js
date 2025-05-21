document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    gsap.registerPlugin(ScrollTrigger);
    const lenis = new Lenis();
    lenis.on('scroll', () => {
        ScrollTrigger.update();
    });
    gsap.ticker.add((time) => {
        lenis.raf(time * 1000)
    });
    gsap.ticker.lagSmoothing(0);
    window.scrollTo(0, 0);

    const menu_btn = document.getElementById("menu-btn");
    var animation;

    menu_btn.addEventListener("click", () => {
        animation = gsap.to(".menu-overlay", {
            duration: 1.25,
            clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
            ease: "power4.inOut",
        });
    });

});


