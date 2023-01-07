
<script>
$.ajax({
	url: 'api/url',
	method: 'POST',
	data: {var:'val'},
	// data: JSON.stringify({var:'val'}), // send data in the request body
	// contentType: "application/json; charset=utf-8",  // if sending in the request body
	dataType: 'json'
}).done(function(data, textStatus, jqXHR) {
	// because dataType is json 'data' is guaranteed to be an object
	console.log('done');
}).fail(function(jqXHR, textStatus, errorThrown) {
	// the response is not guaranteed to be json
	if (jqXHR.responseJSON) {
		// jqXHR.reseponseJSON is an object
		console.log('failed with json data');
	}
	else {
		// jqXHR.responseText is not JSON data
		console.log('failed with unknown data'); 
	}
}).always(function(dataOrjqXHR, textStatus, jqXHRorErrorThrown) {
	console.log('always');
});
</script>