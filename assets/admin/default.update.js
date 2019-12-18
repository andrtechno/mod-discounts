

// Process checked categories
$("#discount-form").submit(function(){
    var checked = $("#CategoryTree li a.jstree-checked");
    checked.each(function(i, el){
        var id = $(el).attr("id").replace('node_', '').replace('_anchor', '');

        $("#discount-form").append('<input type="hidden" name="Discount[categories][]" value="' + id + '" />');
    });

});

// Check node
;(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('check_node','node_' + id);
        });
    };
})(jQuery);