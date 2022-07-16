// Remove falsy values from any array
let miscellaneous = ['🍎', false, '🍊', NaN, 0, undefined, '🌶️', null, '', '🥭'];

// passing Boolean to array.filter() will remove falsy values from array
let fruits = miscellaneous.filter(Boolean);

console.log(fruits); // ['🍎', '🍊', '🌶️', '🥭']

// Boolean(expression) in JS returns true/false
Boolean(5 < 6); //  true
Boolean(100 > 200); // false
Boolean('JavaScript'); //true
Boolean(''); //false

// array example
let miscellaneous = ['🍎', false, '🍊', NaN];
let fruits = miscellaneous.filter(Boolean);

console.log(fruits); // ['🍎', '🍊']