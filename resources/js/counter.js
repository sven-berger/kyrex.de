let counter = 0;

function updateCounterDisplay() {
    const counterDisplay = document.querySelector("#counter");
    if (!counterDisplay) {
        return;
    }
    counterDisplay.textContent = counter;
}

const increaseButton = document.querySelector("#counterIncrease");
const decreaseButton = document.querySelector("#counterDecrease");
const resetButton = document.querySelector("#counterReset");

function handleLimitReached() {
    if (counter !== 100 && counter !== -100) {
        return false;
    }

    const counterDisplay = document.querySelector("#counter");
    if (counterDisplay) {
        counterDisplay.innerHTML = `
        <p class="text-red-500 font-bold text-[13px]">So, genug gezählt.</p>
        <p class="text-red-500 font-bold text-[13px]">Es ist Zeit, etwas zu tun!</p>`;
    }

    counter = 0;
    return true;
}

if (increaseButton) {
    increaseButton.addEventListener("click", (event) => {
        event.preventDefault();
        counter++;

        if (!handleLimitReached()) {
            updateCounterDisplay();
        }
    });
}

if (decreaseButton) {
    decreaseButton.addEventListener("click", (event) => {
        event.preventDefault();
        counter--;

        if (!handleLimitReached()) {
            updateCounterDisplay();
        }
    });
}

if (resetButton) {
    resetButton.addEventListener("click", (event) => {
        event.preventDefault();
        counter = 0;
        updateCounterDisplay();
    });
}

updateCounterDisplay();
