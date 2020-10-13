$(document).ready(function() {

	$('#search_text_input').focus(function() {
		if(window.matchMedia( "(min-width: 800px)" ).matches) {
			$(this).animate({width: '250px'}, 500);
		}
	});

	$('.button_holder').on('click', function() {
		document.search_form.submit();
	})

	//Button for profile post
	$('#submit_profile_post').click(function(){
		var formData = new FormData($("form.profile_post")[0]);

		$.ajax({
			type: "POST",
			url: "includes/handlers/ajax_submit_profile_post.php",
			data: formData,
			processData: false,
			contentType: false,
			success: function(msg) {
				$("#post_form").modal('hide');
				location.reload();
			},
			error: function() {
				alert('Failure');
			}
		});

	});


});


$(document).click(function(e){

	if(e.target.className != "search_results" && e.target.id != "search_text_input") {

		$(".search_results").html("");
		$('.search_results_footer').html("");
		$('.search_results_footer').toggleClass("search_results_footer_empty");
		$('.search_results_footer').toggleClass("search_results_footer");
	}

	if(e.target.className != "dropdown_data_window") {

		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding" : "0px", "height" : "0px"});
	}


});

function deleteMessage(messageId, element) {

	$.post("includes/handlers/ajax_delete_message.php", {id:messageId}, function(data) {
		$(element).closest(".message").text("Message deleted");
	});	
}

function getUsers(value, user) {
	$.post("includes/handlers/ajax_friend_search.php", {query:value, userLoggedIn:user}, function(data) {
		$(".results").html(data);
	});
}

function getDropdownData(user, type) {

	if($(".dropdown_data_window").css("height") == "0px") {

		var pageName;

		if(type == 'notification') {
			pageName = "ajax_load_notifications.php";
			$("span").remove("#unread_notification");
		}
		else if (type == 'message') {
			pageName = "ajax_load_messages.php";
			$("span").remove("#unread_message");
		}

		var ajaxreq = $.ajax({
			url: "includes/handlers/" + pageName,
			type: "POST",
			data: "page=1&userLoggedIn=" + user,
			cache: false,

			success: function(response) {
				$(".dropdown_data_window").html(response);
				$(".dropdown_data_window").css({"padding" : "0px", "height": "280px", "border" : "1px solid #DADADA"});
				$("#dropdown_data_type").val(type);
			}

		});

	}
	else {
		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding" : "0px", "height": "0px", "border" : "none"});
	}

}


function getLiveSearchUsers(value, user) {

	$.post("includes/handlers/ajax_search.php", {query:value, userLoggedIn: user}, function(data) {

		if($(".search_results_footer_empty")[0]) {
			$(".search_results_footer_empty").toggleClass("search_results_footer");
			$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
		}

		$('.search_results').html(data);
		$('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");

		if(data == "") {
			$('.search_results_footer').html("");
			$('.search_results_footer').toggleClass("search_results_footer_empty");
			$('.search_results_footer').toggleClass("search_results_footer");
		}

	});

}

$(function(){
    var textArea = $('#post_text'),
    hiddenDiv = $(document.createElement('div')),
    post_text = null;
    
    textArea.addClass('noscroll');
    hiddenDiv.addClass('hiddendiv');
    
    $(textArea).after(hiddenDiv);
    
    textArea.on('keyup', function(){
        post_text = $(this).val();
        post_text = post_text.replace(/\n/g, '<br>');
        hiddenDiv.html(post_text + '<br class="lbr">');
        $(this).css('height', hiddenDiv.height());
    });
});

$(function(){
    var textArea = $('#message_textarea'),
    hiddenDiv = $(document.createElement('div')),
    message_textarea = null;
    
    textArea.addClass('noscroll');
    hiddenDiv.addClass('hiddendiv');
    
    $(textArea).after(hiddenDiv);
    
    textArea.on('keyup', function(){
        message_textarea = $(this).val();
        message_textarea = message_textarea.replace(/\n/g, '<br>');
        hiddenDiv.html(message_textarea + '<br class="lbr">');
        $(this).css('height', hiddenDiv.height());
    });
});

function sendComment(id) {
 
	const commentText = $("#comment" + id).val();
	
	if(commentText === "") {
 
		alert("Please enter some text first");
		return;
	}
 
	const sendComment = $.post("includes/handlers/send_comment.php", 
		{userLoggedIn:userLoggedIn, commentText:commentText, id:id}, 
		function(response){
 
		if(response !== "No text") {
 
			const loadComment = $.post("includes/handlers/load_comment.php", 
				{id:id, userLoggedIn:userLoggedIn}, 
				function(newComment) {
 
				$("#comment" + id).val("");
				const noComment = $("#toggleComment" + id).find("#noComment" + id);
				
				if(noComment.length !== 0) {
					noComment.remove();
				}
 
				$("#toggleComment" + id + " .comment_section").append(newComment);
 
			});
		}
 
		else {
 
			alert("Something went wrong. Please try again");
		} 
 
	});
}




