// Mobile menu toggle
const mobileMenuBtn = document.getElementById("mobileMenuBtn");
const mobileMenu = document.getElementById("mobileMenu");

mobileMenuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");

    // Change icon between menu and close
    const icon = mobileMenuBtn.querySelector("i");
    if (mobileMenu.classList.contains("hidden")) {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
        mobileMenuBtn.setAttribute("aria-label", "Open menu");
    } else {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
        mobileMenuBtn.setAttribute("aria-label", "Close menu");
    }
});

// Header scroll effect
const header = document.getElementById("header");

window.addEventListener("scroll", () => {
    if (window.scrollY > 20) {
        header.classList.remove("py-4");
        header.classList.add("py-3", "shadow-md");
    } else {
        header.classList.add("py-4");
        header.classList.remove("py-3", "shadow-md");
    }
});

// Set current year in footer
document.getElementById("currentYear").textContent = new Date().getFullYear();

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const targetId = this.getAttribute("href");
        if (targetId === "#") return;

        const target = document.querySelector(targetId);
        if (target) {
            window.scrollTo({
                top: target.offsetTop - 80, // Adjust for header height
                behavior: "smooth",
            });

            // Close mobile menu if open
            if (!mobileMenu.classList.contains("hidden")) {
                mobileMenu.classList.add("hidden");
                const icon = mobileMenuBtn.querySelector("i");
                icon.classList.remove("fa-times");
                icon.classList.add("fa-bars");
                mobileMenuBtn.setAttribute("aria-label", "Open menu");
            }
        }
    });
});

// Add animation on scroll
const animateOnScroll = () => {
    const elements = document.querySelectorAll(
        ".animate-fade-in, .animate-scale-in, .animate-slide-in"
    );

    elements.forEach((element) => {
        const elementPosition = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementPosition < windowHeight - 50) {
            element.style.opacity = "1";
            element.style.transform = "translateY(0)";
        }
    });
};

// Initial check and event listener
window.addEventListener("load", animateOnScroll);
window.addEventListener("scroll", animateOnScroll);
// js for login and signup
//   <!-- Mobile Menu JavaScript -->

$(document).ready(function () {
    // Initialize mobile menu functionality
    const mobileMenuButton = $("#mobile-menu-button");
    const mobileMenu = $("#mobile-menu");

    mobileMenuButton.on("click", function () {
        const isOpen = mobileMenu.hasClass("block");
        if (isOpen) {
            mobileMenu.removeClass("block animate-fade-in").addClass("hidden");
        } else {
            mobileMenu.removeClass("hidden").addClass("block animate-fade-in");
        }
    });

    // Handle scroll for navbar
    const handleScroll = () => {
        const header = $("header");
        if (header.length) {
            if ($(window).scrollTop() > 20) {
                header
                    .addClass("py-3 bg-white/95 backdrop-blur-md shadow-sm")
                    .removeClass("py-5 bg-transparent");
            } else {
                header
                    .addClass("py-5 bg-transparent")
                    .removeClass("py-3 bg-white/95 backdrop-blur-md shadow-sm");
            }
        }
    };

    $(window).on("scroll", handleScroll);
    // Initialize on page load
    handleScroll();
});
console.log("hi");
