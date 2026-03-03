const extraInput = document.getElementById("extraInput");
const extraSelect = document.getElementById("extraSelect");

if (extraInput && extraSelect) {
    if (extraSelect.value === "option1") {
        extraInput.classList.remove("hidden");
    } else {
        extraInput.classList.add("hidden");
    }

    extraSelect.addEventListener("change", () => {
        if (extraSelect.value === "option1") {
            extraInput.classList.remove("hidden");
        } else {
            extraInput.classList.add("hidden");
        }
    });
}
