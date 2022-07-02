
var data = {
	action: 'textdomain_templeate_builder',
	post_id: post_id ? post_id : null,
	rttpg_nonce: textdomain_tb.rttpg_nonce
};

$.ajax({
	url: textdomain_tb.ajaxurl,
	data: data,
	type: "POST",
	beforeSend: function () {
		modal.addModal().addLoading();
	},
	success: function (response) {
		modal.removeLoading();
		//console.log( response )
		modal.addTitle(response.title);
		if (response.success) {
			modal.content(response.content);
		}
	},
	error: function (e) {

	}
});
