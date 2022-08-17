//<button id="testButton">Click</button>
//<div id="testResult"></div>


var count = 1;
$('#testButton').on('click', function(){
	count++;
	$('#testResult').text(count);
	$(document).trigger('testBtnIcn', count);
})

$(document).on('testBtnIcn', function(e, count){
	console.log(count);
})
