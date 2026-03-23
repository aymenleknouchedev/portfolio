import './bootstrap';
import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import AOS from 'aos';

// Alpine.js
window.Alpine = Alpine;
Alpine.start();

// AOS - Animate On Scroll
AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
});

// Custom Cursor
const cursor = document.getElementById('cursor');
const cursorDot = document.getElementById('cursor-dot');

if (cursor && cursorDot) {
    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        cursorDot.style.left = mouseX + 'px';
        cursorDot.style.top = mouseY + 'px';
    });

    function animateCursor() {
        cursorX += (mouseX - cursorX) * 0.15;
        cursorY += (mouseY - cursorY) * 0.15;
        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';
        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Scale on hover over interactive elements
    document.querySelectorAll('a, button, input, textarea, select, [role="button"]').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
            cursor.style.borderColor = 'rgb(168 85 247)';
        });
        el.addEventListener('mouseleave', () => {
            cursor.style.transform = 'translate(-50%, -50%) scale(1)';
            cursor.style.borderColor = 'rgb(168 85 247)';
        });
    });
}

// GSAP Hero Animation
const heroContent = document.getElementById('hero-content');
if (heroContent) {
    gsap.fromTo('#hero-content > *',
        { opacity: 0, y: 40 },
        {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.15,
            ease: 'power3.out',
            delay: 0.3,
        }
    );
}

// Navbar background on scroll
const mainNav = document.getElementById('main-nav');
if (mainNav) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            mainNav.classList.add('backdrop-blur-xl');
        }
    });
}
