document.addEventListener("DOMContentLoaded", () => {
    const inputs = document.querySelectorAll('.inputs-grid input');
    if (!inputs.length) return;

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && inputs[index + 1]) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && input.value === "" && inputs[index - 1]) {
                inputs[index - 1].focus();
            }
        });
    });
});