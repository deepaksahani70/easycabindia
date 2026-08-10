document.addEventListener("DOMContentLoaded", () => {
 
    const navbar = document.querySelector(".eci-navbar");
    const toggle = document.querySelector(".eci-toggle");
    const mobileMenu = document.querySelector(".eci-mobile");
    const overlay = document.querySelector(".eci-overlay");
    const closeBtn = document.querySelector(".eci-close");
    const mobileItems = document.querySelectorAll(".eci-mobile-item");
    const mobileLinks = document.querySelectorAll(".eci-mobile a");
 
    /* Sticky Navbar Scroll Effect */
 
    window.addEventListener("scroll", () => {
 
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
 
    });
 
 
    /* Open Mobile Menu */
 
    function openMenu() {
        mobileMenu.classList.add("active");
        overlay.classList.add("active");
        toggle.classList.add("active");
        document.body.classList.add("menu-open");
    }
 
 
    /* Close Mobile Menu */
 
    function closeMenu() {
        mobileMenu.classList.remove("active");
        overlay.classList.remove("active");
        toggle.classList.remove("active");
        document.body.classList.remove("menu-open");
    }
 
 
    /* Toggle Button */
    if (toggle) {
        toggle.addEventListener("click", () => {
 
            if (mobileMenu.classList.contains("active")) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }
 
 
    /* Close Button */
    if (closeBtn) {
        closeBtn.addEventListener("click", closeMenu);
    }
 
 
    /* Overlay Click Close */
    if (overlay) {
        overlay.addEventListener("click", closeMenu);
    }
    /* ESC Key Close */
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeMenu();
        }
    });
 
 
    /* Mobile Accordion */
    mobileItems.forEach(item => {
        const button = item.querySelector(".eci-mobile-btn");
        if (button) {
            button.addEventListener("click", () => {
                const isActive = item.classList.contains("active");
                mobileItems.forEach(other => {
                    other.classList.remove("active");
                });
                if (!isActive) {
                    item.classList.add("active");
                }
            });
        }
 
    });
    /* =========================================================
   MOBILE CITY STATE ACCORDION
   ========================================================= */
 
    const mobileStates = document.querySelectorAll(".eci-mobile-state");
 
    mobileStates.forEach(state => {
 
        const button = state.querySelector(".eci-mobile-state-btn");
 
        if (!button) return;
 
        button.addEventListener("click", () => {
 
            const isActive = state.classList.contains("active");
 
            /* Close other states */
            mobileStates.forEach(otherState => {
                otherState.classList.remove("active");
            });
 
            /* Open clicked state */
            if (!isActive) {
                state.classList.add("active");
            }
 
        });
 
    });
    /* Close Menu After Navigation */
    mobileLinks.forEach(link => {
        link.addEventListener("click", () => {
            closeMenu();
        });
    });
    /* Active Menu */
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".eci-nav a,.eci-mobile-nav a").forEach(link => {
        const linkPage = link.getAttribute("href");
        if (linkPage === currentPage) {
            link.classList.add("active");
        }
    });
 
 
 
});