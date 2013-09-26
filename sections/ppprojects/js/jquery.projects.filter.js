jQuery(window).load(function() {
    // Filter
    jQuery('.pp-projects-filter li').on('click', function(e){
        e.preventDefault();
        jQuery(this).toggleClass('active').siblings().removeClass('active');
        var gender = jQuery('.pp-projects-filter').eq(0).find('.active').data('filter')||'',
            height = jQuery('.pp-projects-filter').eq(1).find('.active').data('filter')||'',
            sel = '.pp-project-single'+(gender!=''?'.'+gender:'')+(height!=''?'.'+height:'');
        jQuery(sel).delay(500).fadeIn(500);
        jQuery('.pp-project-single').not(sel).fadeOut(400);
    });
});