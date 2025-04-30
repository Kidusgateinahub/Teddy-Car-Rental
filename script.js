// Slideshow
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');

function showNextSlide() {
    slides[slideIndex].classList.remove('active');
    slideIndex = (slideIndex + 1) % slides.length;
    slides[slideIndex].classList.add('active');
}

// Navbar scroll effect
let lastScroll = window.pageYOffset;
const navbar = document.querySelector('.navbar');
const scrollThreshold = 10; // smaller value for smoother detection

function handleScroll() {
    const currentScroll = window.pageYOffset;

    if (currentScroll > lastScroll && currentScroll > scrollThreshold) {
        // Scrolling down
        navbar.classList.add('hidden');
    } else if (currentScroll < lastScroll) {
        // Scrolling up
        navbar.classList.remove('hidden');
    }

    lastScroll = currentScroll;
}

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuToggle && navLinks) {
        mobileMenuToggle.addEventListener('click', function () {
            navLinks.classList.toggle('active');
            const icon = mobileMenuToggle.querySelector('i');
            if (navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (event) {
            if (!mobileMenuToggle.contains(event.target) && !navLinks.contains(event.target)) {
                navLinks.classList.remove('active');
                const icon = mobileMenuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    setInterval(showNextSlide, 4000);
    window.addEventListener('scroll', handleScroll);
});
