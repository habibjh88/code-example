// Body should have a button with "button" id
// HTML Example: <button id='button'>Click Here</button>

const button = document.getElementById("button");
function debounce(fn, delay) {
    let timeoutId;
    return function () {
        if (!timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
            fn();
        }, delay);
    }
}

button.addEventListener("click",
    debounce(function () {
        console.log("clicked");
    }, 500)
); 
