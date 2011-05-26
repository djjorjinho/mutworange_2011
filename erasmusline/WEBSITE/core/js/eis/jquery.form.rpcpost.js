(function($) {
    $.extend({

        postGo: function(url, method, params) {
            var $form = $("<form>")
                .attr("method", "post")
                .attr("action", url)
                .attr('target','_blank');
            	
            $("<input type='hidden'>")
            .attr("name", 'method')
            .attr("value", method)
            .appendTo($form);
            
            $("<input type='hidden'>")
                .attr("name", 'params')
                .attr("value", jQuery.toJSON(params))
                .appendTo($form);
            
            $form.appendTo("body");
            $form.submit();
        }
    });
})(jQuery);