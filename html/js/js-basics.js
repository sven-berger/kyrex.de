// Hello World Button
function helloWorld() {
  alert("Hello World!");
  console.log("'Hello World!' wurde erfolgreich in der Konsole ausgegeben.");
}

// Counter Buttons
const counterValue = document.getElementById("counterValue");
const increaseButton = document.getElementById("increase");
const decreaseButton = document.getElementById("decrease");
const resetButton = document.getElementById("reset");
let counter = null;

increaseButton.style.backgroundColor = "#27ae60";
increaseButton.addEventListener("click", () => {
  counter++;
  counterValue.textContent = counter;
});

decreaseButton.style.backgroundColor = "#c0392b";
decreaseButton.addEventListener("click", () => {
  counter--;
  counterValue.textContent = counter;
});

resetButton.style.backgroundColor = "#f1c40f";
resetButton.addEventListener("click", () => {
  counter = 0;
  counterValue.textContent = counter;
});
