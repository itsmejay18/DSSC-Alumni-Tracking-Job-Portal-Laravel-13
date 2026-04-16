document.addEventListener("DOMContentLoaded", () => {
    // SECURE: Trix attachments are disabled so job posts stay text-only and easier to sanitize safely.
    document.addEventListener("trix-file-accept", (event) => {
        event.preventDefault();
    });

    document.querySelectorAll("trix-editor[data-portal-editor]").forEach((editor) => {
        editor.classList.add("portal-trix-ready");
    });

    const closeUserMenus = () => {
        document.querySelectorAll("[data-user-menu]").forEach((menu) => {
            const trigger = menu.querySelector("[data-user-menu-trigger]");
            const panel = menu.querySelector("[data-user-menu-panel]");

            if (trigger) {
                trigger.setAttribute("aria-expanded", "false");
            }

            if (panel) {
                panel.hidden = true;
            }
        });
    };

    document.querySelectorAll("[data-user-menu]").forEach((menu) => {
        const trigger = menu.querySelector("[data-user-menu-trigger]");
        const panel = menu.querySelector("[data-user-menu-panel]");

        if (!trigger || !panel) {
            return;
        }

        trigger.addEventListener("click", (event) => {
            event.stopPropagation();
            const willOpen = panel.hidden;

            closeUserMenus();

            panel.hidden = !willOpen;
            trigger.setAttribute("aria-expanded", willOpen ? "true" : "false");
        });

        panel.addEventListener("click", (event) => {
            event.stopPropagation();
        });
    });

    document.addEventListener("click", closeUserMenus);

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeUserMenus();
        }
    });
});
