let text = "Mr Blue has a blue house and a blue car.";
let result = text.replace(/blue|house|car/gi, function (x) {
    return x.toUpperCase();
});
console.log(result) // Output: Mr BLUE has a BLUE HOUSE and a BLUE CAR.