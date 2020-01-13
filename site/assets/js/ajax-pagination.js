!function($){

var request;
	
var initPagination = function() {
	
	var pag = $('#djleague .djl_pagination').first();
	if(!pag.length) return;
	
	var btn = pag.find('.pagination-ajax button').first();
	if(!btn.length) return;
	
	btn.data();
	
	btn.click(function(e){
		e.preventDefault();			
		loadMore(pag, btn);
	});
};

var loadMore = function(pag, btn) {
	
	var url = btn.data('href');
	if(!url) return;
	
	btn.prop('disabled', true);
	btn.find('.loader').css('display', '');
	
	if(request) {
		request.abort();
		//console.log('aborted');
	}
	
	request = $.ajax({
		url: url,
		type: 'get'
	}).done(function(rsp){
		
		var djleague = $('#djleague');
		var response = $('<div style="display: none; visibility: hidden;" />');
		djleague.after(response);
		
		var bdhtml = rsp.match(/<body[^>]*>([\w|\W]*)<\/body>/im);
        if(bdhtml){
			bdhtml = bdhtml[1].replace(new RegExp('<script[^>]*>([\\S\\s]*?)<\/script\\s*>', 'img'), '');
		}
		response.html(bdhtml);
		
		var row = response.find('table.djl_schedule_list tbody tr').first();
		var group = row.attr('data-group');
		if(group && djleague.find('table.djl_schedule_list tbody tr[data-group="'+group+'"]').length) {
			row.remove();
		}
		
		djleague.find('table.djl_schedule_list tbody')
			.append(response.find('table.djl_schedule_list tbody tr'));
		
		var new_pag = response.find('.djl_pagination');
		if(new_pag.length) {
			pag.replaceWith(new_pag);
		} else {
			pag.remove();
		}
		
		response.remove();
		
		initPagination();
		
	}).fail(function(){
		
		btn.prop('disabled', false);
		btn.find('.loader').css('display', '');
	});
};

$(document).ready(function(){
	
	initPagination();
	
});

}(jQuery);