// 1. what will be the output of the below codes?
//=========================================
console.log([] + []); // "" blank (coz: empty array = '')

console.log({} + []); // [object Object] (coz: empty {} = object Object)


// 2. what will be the output of below code?
//=========================================
function myFunction(){
    return 'Bangladesh';
}

const string = myFunction `hello `;
console.log(string); // Bangladesh  (coz: if a tag literal sit after any function then it will be an argument of the function. And hello go in arguments object of this function)

// 3. how to make all text contents of a website editable?
//=========================================
document.body.contentEditable = true;

// 4. what will be the output of below code?
//=========================================
function b(){
    console.log(`the length is ${this.length}`);
}

let a = {
    length: 10,
    method: function(b) {
        arguments[0]();
    }
};

a.method(b, 5); // the length is 2

// 5. what will be the output of below code?
//=========================================
const a = 'constructor';
console.log(a[a](01)); // "1"


// 6. what will be the output?
//=========================================
console.log(0.1 + 0.2); // 0.30000000000000004


// 7. what will be the output of below code?
//=========================================
console.log(("Bangladesh").__proto__.__proto__.__proto__); // null

// 8. make a function that sorts its arguments without using loops
//=========================================
const myFunction = function () {
    return [].slice.call(arguments).sort();
  };
  
  console.log(myFunction(2, 1, 4, 3));


