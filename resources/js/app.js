import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { gsap } from "gsap";

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

const prefersReducedMotion = window.matchMedia(
  '(prefers-reduced-motion: reduce)'
).matches;

document.addEventListener('DOMContentLoaded', () => {
  if (prefersReducedMotion) {
    document.querySelectorAll('.reveal-item, .progress-fill').forEach((el) => {
      el.style.opacity = '1';
      el.style.transform = 'none';
      if (el.classList.contains('progress-fill')) {
        el.style.width = el.dataset.fill || el.style.width;
      }
    });
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in-up');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  document.querySelectorAll('.reveal-item').forEach((el) => {
    observer.observe(el);
  });
});

const tl = gsap.timeline({
    defaults:{
        ease:"power3.out"
    }
});

tl.from(".hero-badge",{
    opacity:0,
    y:-20,
    duration:.5
});

tl.from(".hero-title",{
    opacity:0,
    y:60,
    duration:.8
},"-=0.2");

tl.from(".hero-sub",{
    opacity:0,
    y:30,
    duration:.7
},"-=0.5");

tl.from(".hero-cta",{
    opacity:0,
    y:20,
    stagger:.15,
    duration:.5
},"-=0.4");

tl.from(".hero-stat",{
    opacity:0,
    y:20,
    stagger:.15
},"-=0.3");

tl.from(".hero-dashboard",{
    opacity:0,
    x:80,
    scale:.95,
    duration:1
},"-=0.8");

tl.from(".floating-card",{
    opacity:0,
    scale:.8,
    stagger:.15,
    duration:.5
},"-=0.6");

gsap.to(".hero-dashboard",{

    y:-10,

    duration:2.5,

    repeat:-1,

    yoyo:true,

    ease:"sine.inOut"

});

gsap.to(".floating-card",{

    y:8,

    stagger:.4,

    repeat:-1,

    yoyo:true,

    duration:2,

    ease:"sine.inOut"

});