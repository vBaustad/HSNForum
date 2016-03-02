$(function(){
	$(document).on('submit', '#chatForm', function(){
		var text = $.trim($("#text").val());
		var name = $.trim($("#name").val());

		if(text != "" && name != ""){
			$.post('chatPoster.php',{text: text, name: name}, function(data){
				$(".chatMessages").append(data);
		});

		}else{
			alert("data missing");
		}

	});
});