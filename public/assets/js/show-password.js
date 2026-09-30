"use strict"

function showPassword(inputId, button) {
    const passwordInput = document.getElementById(inputId)
    const icon = button.querySelector("i")
    const isVisible = passwordInput.type === "password"

    passwordInput.type = isVisible ? "text" : "password"
    button.setAttribute("aria-pressed", String(isVisible))
    button.setAttribute("aria-label", isVisible ? button.dataset.hideLabel : button.dataset.showLabel)
    icon.classList.toggle("ri-eye-line", isVisible)
    icon.classList.toggle("ri-eye-off-line", !isVisible)
}
