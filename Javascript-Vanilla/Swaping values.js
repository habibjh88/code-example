// 1. Swaping values 
// Note: swap 1st (1) and last (5) value from array

let array = [1, 2, 3, 4, 5];

// temp variable
let temp = array[0];
array[0] = array[4];
array[4] = temp;

console.log(array); // Output = [5, 2, 3, 4, 1]

// array destructuring
[array[0], array[4]] = [array[4], array[0]];
console.log(array);

let a = 1;
let b = 2;
[a, b] = [b, a];
console.log(a, b); // Output = 2 1

// math
b = a + (a = b) - b;
console.log(a, b); // Output = 2 1
