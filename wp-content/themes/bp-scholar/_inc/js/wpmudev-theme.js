jQuery(document).ready(function($){
	
	// Fix video ratio
	function fix_video_ratio(){
		var providers = ['youtube.com', 'vimeo.com', 'dailymotion.com', 'viddler.com'];
		for ( var p = 0; p < providers.length; p++ ){
			var videos = $('iframe[src*="'+providers[p]+'"]');
			if ( videos.size() > 0 )
				videos.each(function(){
					if ( !$(this).parent().is('.video-keep-ratio-wrap') && parseInt($(this).attr('width')) > $(this).parent().width() ){
						var ratio = parseInt($(this).attr('height')) / parseInt($(this).attr('width'));
						$(this).wrap('<div class="video-keep-ratio-wrap"></div>');
						$(this).parent().css('padding-bottom', (ratio*100)+'%');
					}
				});
		}
	}
	fix_video_ratio();

	$(document).ajaxComplete(function(){
		fix_video_ratio();
	});
	
	$(window).resize(function(){
		fix_video_ratio();
	});
	
	
	/* Facebook parse */
	$(document).ajaxComplete(function(){
		try{
			FB.XFBML.parse(); 
		}catch(ex){}
	});
	
});

