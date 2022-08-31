//convert 5 items array from a big array.
const posts = [1,2,3,4,5,6,7,8,9,10]
let chunkPost = [];
const splitPoint = 5;
for (let i = 0; i < posts.length; i += splitPoint) {
	chunkPost.push(posts.slice(i, i + splitPoint));
}

console.log(chunkPost) //output [0]=[1,2,3,4,5] and [1] = [6,7,8,9,10]