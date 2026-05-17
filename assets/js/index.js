document.addEventListener('DOMContentLoaded', function () {
    console.log('CareConnect homepage loaded successfully.');

    // Smooth scrolling for navbar links
    const navLinks = document.querySelectorAll('a.nav-link[href^="#"]');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});