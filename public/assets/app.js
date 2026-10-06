"use strict";

// These interactions enhance the PHP forms; PHP always validates submissions too.
document.querySelectorAll("[data-toggle-password]").forEach((button) => {
    button.addEventListener("click", () => {
        const input = document.getElementById(button.dataset.togglePassword);
        const show = input.type === "password";
        input.type = show ? "text" : "password";
        button.setAttribute("aria-pressed", String(show));
        const label = input.name === "confirm_password" ? "confirmation password" : "password";
        button.setAttribute("aria-label", `${show ? "Hide" : "Show"} ${label}`);
        button.querySelectorAll(".icon").forEach((icon) => icon.classList.toggle("hidden"));
    });
});

const form = document.querySelector("[data-auth-form]");
if (form) {
    const isRegister = form.dataset.authForm === "register";
    const fields = [...form.querySelectorAll(".field-group input")];
    const password = form.elements.password;
    const confirmation = form.elements.confirm_password;
    const touched = new Set();

    function validate(input) {
        const value = input.type === "password" || input.name === "confirm_password" || input.name === "password" ? input.value : input.value.trim();
        if (!value) {
            return {
                name: "Please enter your full name.",
                email: "Please enter your email address.",
                password: isRegister ? "Please create a password." : "Please enter your password.",
                confirm_password: "Please confirm your password.",
            }[input.name];
        }
        if (input.name === "name" && (value.length < 2 || value.length > 80 || /[\x00-\x1f\x7f]/.test(value))) {
            return "Use a name between 2 and 80 characters long.";
        }
        if (input.name === "email" && (value.length > 254 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))) {
            return "Enter a valid email address, like you@example.com.";
        }
        if (isRegister && input.name === "password") {
            const length = new TextEncoder().encode(value).length;
            if (length < 8 || length > 72) return "Use a password between 8 and 72 bytes long.";
            if (!/[a-z]/.test(value) || !/[A-Z]/.test(value) || !/[0-9]/.test(value)) {
                return "Include an uppercase letter, a lowercase letter, and a number.";
            }
        }
        if (input.name === "confirm_password" && value !== password.value) {
            return "Your passwords don’t match yet.";
        }
        return "";
    }

    function showError(input, message) {
        input.closest(".field-group").classList.toggle("has-error", Boolean(message));
        input.setAttribute("aria-invalid", String(Boolean(message)));
        document.getElementById(`${input.name}-error`).textContent = message;
    }

    fields.forEach((input) => {
        if (input.getAttribute("aria-invalid") === "true") touched.add(input.name);
        input.addEventListener("blur", () => {
            touched.add(input.name);
            showError(input, validate(input));
        });
        input.addEventListener("input", () => {
            if (touched.has(input.name)) showError(input, validate(input));
            if (input === password && confirmation && touched.has("confirm_password")) {
                showError(confirmation, validate(confirmation));
            }
        });
    });

    if (isRegister) {
        password.addEventListener("input", () => {
            const value = password.value;
            const length = new TextEncoder().encode(value).length;
            const rules = {
                length: length >= 8 && length <= 72,
                case: /[a-z]/.test(value) && /[A-Z]/.test(value),
                number: /[0-9]/.test(value),
            };
            Object.entries(rules).forEach(([rule, met]) => {
                document.querySelector(`[data-password-rule="${rule}"]`).classList.toggle("met", met);
            });
        });
    }

    const remember = document.getElementById("remember-email");
    if (remember) {
        try {
            const saved = localStorage.getItem("pawspace-email");
            if (saved) {
                remember.checked = true;
                if (!form.elements.email.value) form.elements.email.value = saved;
            }
        } catch (_) { /* The form still works if browser storage is disabled. */ }
        remember.addEventListener("change", () => {
            if (!remember.checked) {
                try { localStorage.removeItem("pawspace-email"); } catch (_) { /* Optional storage. */ }
            }
        });
    }

    form.addEventListener("submit", (event) => {
        let firstInvalid;
        fields.forEach((input) => {
            touched.add(input.name);
            const message = validate(input);
            showError(input, message);
            if (message && !firstInvalid) firstInvalid = input;
        });
        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.focus();
            return;
        }
        if (remember) {
            try {
                if (remember.checked) localStorage.setItem("pawspace-email", form.elements.email.value.trim());
                else localStorage.removeItem("pawspace-email");
            } catch (_) { /* Optional storage. */ }
        }
        const submit = form.querySelector("[type=submit]");
        submit.disabled = true;
        submit.classList.add("is-submitting");
    });

    // Restore the button when the browser brings a form back from its history cache.
    window.addEventListener("pageshow", () => {
        const submit = form.querySelector("[type=submit]");
        submit.disabled = false;
        submit.classList.remove("is-submitting");
    });
}

