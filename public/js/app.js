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

    document.querySelectorAll("[data-password-toggle]").forEach((toggle) => {
        const input = document.getElementById(toggle.dataset.passwordToggle);

        if (!input) {
            return;
        }

        const icon = toggle.querySelector("i");

        const syncPasswordToggle = () => {
            const isVisible = input.type === "text";

            toggle.setAttribute("aria-label", isVisible ? "Hide password" : "Show password");
            toggle.setAttribute("aria-pressed", isVisible ? "true" : "false");

            if (icon) {
                icon.className = isVisible ? "ph ph-eye-slash" : "ph ph-eye";
            }
        };

        toggle.addEventListener("click", () => {
            input.type = input.type === "password" ? "text" : "password";
            syncPasswordToggle();
        });

        syncPasswordToggle();
    });

    const registerRoleField = document.querySelector("[data-register-role]");

    if (registerRoleField) {
        const registerStepper = document.querySelector("[data-register-stepper]");
        const roleSections = document.querySelectorAll("[data-role-section]");
        const roleCopy = document.querySelector("[data-role-copy]");
        const roleDescriptions = {
            alumni: "Alumni accounts are used for graduate tracing, profile completion, and job matching.",
            employer: "Employer accounts are reviewed by the portal team before job posting access is granted.",
        };

        const syncRegisterRole = () => {
            const activeRole = registerRoleField.value;

            roleSections.forEach((section) => {
                const isActive = section.dataset.roleSection === activeRole;
                section.hidden = !isActive;
                section.setAttribute("aria-hidden", (!isActive).toString());

                section.querySelectorAll("input, select, textarea").forEach((field) => {
                    field.disabled = !isActive;

                    if (field.dataset.requiredForRole) {
                        field.required = isActive && field.dataset.requiredForRole === activeRole;
                    }
                });
            });

            if (roleCopy) {
                roleCopy.textContent = roleDescriptions[activeRole] ?? "";
            }
        };

        registerRoleField.addEventListener("change", syncRegisterRole);
        syncRegisterRole();

        if (registerStepper) {
            const stepPanels = Array.from(registerStepper.querySelectorAll("[data-register-step-panel]"));
            const stepIndicators = Array.from(registerStepper.querySelectorAll("[data-register-step-indicator]"));
            const totalSteps = stepPanels.length;
            let currentStep = Number(registerStepper.dataset.registerStartStep || 1);

            const validateStep = (step) => {
                const panel = stepPanels.find((candidate) => Number(candidate.dataset.registerStepPanel) === step);

                if (!panel) {
                    return true;
                }

                const validatableFields = Array.from(panel.querySelectorAll("input, select, textarea")).filter((field) => {
                    return !field.disabled && field.type !== "hidden" && field.willValidate;
                });

                for (const field of validatableFields) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return false;
                    }
                }

                return true;
            };

            const syncRegisterStep = () => {
                stepPanels.forEach((panel) => {
                    const panelStep = Number(panel.dataset.registerStepPanel);
                    const isActive = panelStep === currentStep;

                    panel.hidden = !isActive;
                    panel.setAttribute("aria-hidden", (!isActive).toString());
                });

                stepIndicators.forEach((indicator) => {
                    const indicatorStep = Number(indicator.dataset.registerStepIndicator);
                    indicator.classList.toggle("is-active", indicatorStep === currentStep);
                    indicator.classList.toggle("is-complete", indicatorStep < currentStep);
                    indicator.setAttribute("aria-current", indicatorStep === currentStep ? "step" : "false");
                });
            };

            registerStepper.querySelectorAll("[data-register-next]").forEach((button) => {
                button.addEventListener("click", () => {
                    if (!validateStep(currentStep)) {
                        return;
                    }

                    currentStep = Math.min(currentStep + 1, totalSteps);
                    syncRegisterStep();
                });
            });

            registerStepper.querySelectorAll("[data-register-prev]").forEach((button) => {
                button.addEventListener("click", () => {
                    currentStep = Math.max(currentStep - 1, 1);
                    syncRegisterStep();
                });
            });

            syncRegisterStep();
        }
    }
});
