function testEmail(){
	console.log($("#emailTemplate").val())
	console.log(link)
	$.ajax({
	type: 'POST',
	url: link,
	cache: false,
	data: {
		method : 'test',
		ajax: true
	},
	success: function (result) {
		console.log(result);
	}
});
}