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

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    setInterval(showNextSlide, 4000);
    window.addEventListener('scroll', handleScroll);
});
