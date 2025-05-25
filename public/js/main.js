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

    const menu = new MenuController("#menu-btn", ".close-menu", ".menu-overlay");
});


class MenuController {
    constructor(menuButtonSelector, closeButtonSelector, overlaySelector) {
        this.menuButton = document.querySelector(menuButtonSelector);
        this.closeButton = document.querySelector(closeButtonSelector);
        this.overlaySelector = overlaySelector;
        this.animation = null;
        this.isOpen = false; // Trạng thái menu

        this.initEvents();
    }

    initEvents() {
        this.menuButton.addEventListener("click", () => this.openMenu());

        $(this.closeButton).on("click", () => this.closeMenu());

        $(document).on("keydown", (event) => {
            if (event.key === "Escape") {
                this.closeMenu();
            }
        });
    }

    openMenu() {
        if (!this.isOpen) {
            this.animation = gsap.to(this.overlaySelector, {
                duration: 1.25,
                clipPath: "polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)",
                ease: "power4.inOut",
            });
            this.isOpen = true;
        }
    }

    closeMenu() {
        if (this.isOpen) {
            gsap.to(this.overlaySelector, {
                duration: 1.25,
                clipPath: "polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)",
                ease: "power4.inOut",
            });
            this.isOpen = false;
        }
    }
}



class Popup {
    constructor() {
        this.popupWrap = null;
    }

    createPopup(message) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('popup-wrap');

        wrapper.innerHTML = `
        <div class="popup">
            <div class="text-wrap">
                <p class="popup__title">Notification</p>
                <p class="popup__text">${message}</p>
            </div>
            <div class="popup__btn-wrap">
                <button class="popup__btn-primary" role="button" tabindex="0">OK</button>
            </div>
        </div>
        `;

        wrapper.querySelector('.popup__btn-primary').addEventListener('click', () => {
            this.closePopup();
        });

        return wrapper;
    }

    showPopup(message) {
        if (this.popupWrap) {
            this.closePopup();
        }

        this.popupWrap = this.createPopup(message);

        const main = document.querySelector('main');
        if (main) {
            main.insertAdjacentElement('afterend', this.popupWrap);
        } else {
            document.body.appendChild(this.popupWrap);
        }
    }

    closePopup() {
        if (this.popupWrap) {
            this.popupWrap.remove();
            this.popupWrap = null;
        }
    }
}

