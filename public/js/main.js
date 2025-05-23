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




  window.danmaku = {
    back  : ["💖💖💖
        ♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡
        SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡　SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡　SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡","東京 TOKYO 东京 도쿄 टोक्यो 東京 TOKYO 东京 도쿄 टोक्यो 東京 TOKYO 东京 도쿄 टोक्यो","🔐🎮 vault room 🎮🔐　🔐🎮 vault room 🎮🔐　🔐🎮 vault room 🎮🔐","🌸🌺🌿🍉🍊☮️ COSMOS JUICE TOMIGAYA ☮️🍊🍉🌿🌺🌸","🥃🍸亀レON 🍺🍶　🥃🍸亀レON 🍺🍶　🥃🍸亀レON 🍺🍶","☯️☯️☯️ QR81V ☯️☯️☯️　☯️☯️☯️ QR81V ☯️☯️☯️　☯️☯️☯️ QR81V ☯️☯️☯️","🧡 HTML 💙 CSS 💛 JavaScript 💙 Typescript ☕️ Java 🐘 PHP 💎 Ruby 🐍 Python 🩶 C 💙 C++ 💜 C# 🤖 Kotlin 🏦 COBOL 🐧 Swift 🧗 Scala","🧊 Unity 🎮 Unreal Engine 👁️‍🗨️ Blender 🏃 ZBrush ","❤️ Adobe 💙 Photoshop 🧡 Illustrator 💜 After Effects 💜 Premiere Pro 🩷 Dreamweaver","💚 Figma 🩵 WordPress 🧡 Live2D 💙 Visual Studio Code 💛 AWS","HTML CSS JavaScript Typescript Java PHP Ruby Python C C++ C# Kotlin COBOL Swift Scala","Unity Unreal Engine Blender ZBrush ","Adobe Photoshop Illustrator After Effects Premiere Pro Dreamweaver","Figma WordPress Live2D Visual Studio Code AWS","🧡💫⚔️","🩷💫⚔️","❤️💫⚔️","💛💫⚔️","🩵💫⚔️","💚💫⚔️","💜💫⚔️","🖤💫⚔️","🩶💫⚔️","🤍💫⚔️","🤎💫⚔️","💙💫⚔️","♦♫.。･*゜*･。.♦♫.。･*゜*･。.♦♫♦。･*゜*･。.♦♫♦♫.。･*゜*･。.♦♫♦♫.。･*゜*･。.♦","⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨","⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨","　▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂","８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８","★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―","♫♬.*..｡o♡ﾟ･:,｡♬o｡*:..:♬.*..｡o♡ﾟ･:,｡♬o｡*:..♬♫♬.*..｡o♡ﾟ･:,｡♬o｡*:..:♬.*..｡o♡ﾟ･:,｡♬o｡*:..♬",".。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬"],
    front : ["UNLOCK YOUR FANTASY.","可能性を、解き放て。","CAREER JOURNEYS","CREATIVE STORIES","CULTURES","CREATORS","ENTRY","DIVERSE CLIENTS AND PROJECTS","DIVERSE WORK STYLES","CHALLENGES OF CAREER CHANGE","SKILLS BACKED UP BY PROFESSIONALS","BOOST THE WHOLE FROM NEO TOKYO.","世界をもっと楽しく、おもしろくセットアップする。","DO MORE, NEW THEORY.","新しい理論をガンガン想像していく。","変化を楽しみ、進化する。","ウォンツの中のニーズを見つける。","共に歩む。","探究心と挑戦。","思いやりと遊び心を忘れない。","誰が何を言おうが関係ない。","愛と勇気と誇りを持って創る。","チャンスは常にある。","超はやい。","全力でやる。","きっとうまくいく。","ENJOY CHANGE AND EVOLVE.","FIND (THE) NEEDS IN WANTS.","YOU ARE NOT ALONE.","CURIOSITY &amp; CHALLENGE.","NEVER FORGET COMPASSION AND PLAYFULNESS.","WE DON’T CARE WHO SAYS.","CHANCE IN EVERYWHERE,EVERYTIME.","CREATE WITH LOVE,COURAGE AND PRIDE.","WILD SPEED. ","STOIC FOR EVERYTHING.","ALL IS WELL.","ただ漠然と波に飲みこまれるにはもったいなさすぎる。","人生はとても有限だ。","偽物の正義に振り回されている場合じゃない。","正論だけじゃ心は動かないんだ。","さあ一緒に何をしようか？","何をやる？こんなおもしろい時代に。"],
    emoji : ["⚔️","🔮","🍙","🔥","😈","👻","🦋","🐶","🐭","🦄","🐬","🦫","🌿","🌸","🌺","💫","⚡️","🍉","🍊","🍤","🍣","🥃","🎱","🛼","🎲","🎮","🎧","☮️","☯️","👽","🏎️","💽","💻","🖥️","💣","🔫","🪩","🩷","❤️","🧡","💛","💚","🩵","💜","🖤","🩶","🤍","🤎","💙","🌐","🔊","🧚‍♀️","🫶"]  }


    "💖💖💖
    ♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡♥♡♥♡♥♡♥TOMIGAYA♡♥♡♥♡♥♡♥♡♥♡
    SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡　SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡　SHIBUYA☆彡･｡･ﾟ･｡　☆彡HARAJYUKU☆彡
    東京 TOKYO 东京 도쿄 टोक्यो 東京 TOKYO 东京 도쿄 टोक्यो 東京 TOKYO 东京 도쿄 टोक्यो
    🔐🎮 vault room 🎮🔐　🔐🎮 vault room 🎮🔐　🔐🎮 vault room 🎮🔐
    🌸🌺🌿🍉🍊☮️ COSMOS JUICE TOMIGAYA ☮️🍊🍉🌿🌺🌸
    🥃🍸亀レON 🍺🍶　🥃🍸亀レON 🍺🍶　🥃🍸亀レON 🍺🍶
    ☯️☯️☯️ QR81V ☯️☯️☯️　☯️☯️☯️ QR81V ☯️☯️☯️　☯️☯️☯️ QR81V ☯️☯️☯️
    🧡 HTML 💙 CSS 💛 JavaScript 💙 Typescript ☕️ Java 🐘 PHP 💎 Ruby 🐍 Python 🩶 C 💙 C++ 💜 C# 🤖 Kotlin 🏦 COBOL 🐧 Swift 🧗 Scala
    🧊 Unity 🎮 Unreal Engine 👁️‍🗨️ Blender 🏃 ZBrush
    ❤️ Adobe 💙 Photoshop 🧡 Illustrator 💜 After Effects 💜 Premiere Pro 🩷 Dreamweaver
    💚 Figma 🩵 WordPress 🧡 Live2D 💙 Visual Studio Code 💛 AWS
    HTML CSS JavaScript Typescript Java PHP Ruby Python C C++ C# Kotlin COBOL Swift Scala
    Unity Unreal Engine Blender ZBrush
    Adobe Photoshop Illustrator After Effects Premiere Pro Dreamweaver
    Figma WordPress Live2D Visual Studio Code AWS
    🧡💫⚔️
    🩷💫⚔️
    ❤️💫⚔️
    💛💫⚔️
    🩵💫⚔️
    💚💫⚔️
    💜💫⚔️
    🖤💫⚔️
    🩶💫⚔️
    🤍💫⚔️
    🤎💫⚔️
    💙💫⚔️
    ♦♫.。･*゜*･。.♦♫.。･*゜*･。.♦♫♦。･*゜*･。.♦♫♦♫.。･*゜*･。.♦♫♦♫.。･*゜*･。.♦
    ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨✽.｡.:*・ﾟ⭐️💫✨
    ⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨⭐️💜✨⭐️🧡✨
    　▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂▂▃▅▆▇██▇▆▅▃▂
    ８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８✨⭐️＊*¨*•.¸¸⋆*＊８８８
    ★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―　★≡＝―
    ♫♬.*..｡o♡ﾟ･:,｡♬o｡*:..:♬.*..｡o♡ﾟ･:,｡♬o｡*:..♬♫♬.*..｡o♡ﾟ･:,｡♬o｡*:..:♬.*..｡o♡ﾟ･:,｡♬o｡*:..♬
    .。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬　.。.:*ヾ(´︶`*)ﾉ♬"
