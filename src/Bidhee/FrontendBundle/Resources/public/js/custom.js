$(document).ready(function() {

    // Responsive Menu
        $('.btn_nav').click(function(){
            $(this).find('i').toggleClass('fa-bars fa-close');
            $('body').toggleClass('scroll-hide');
            $('nav').toggleClass('show');
        });
        $('.btn_search').click(function(){
            $(this).find('i').toggleClass('fa-search fa-close');
            $('body').toggleClass('scroll-hide');
            $('#respHeader .search').toggleClass('show');
        });


    // Tabber
        $(function () {
            var tabContainers = $('.tabber > div');
            
            $('ul.options li a').click(function () {
                tabContainers.hide().filter(this.hash).fadeIn();
                
                $('ul.options li').removeClass('selected');
                $(this).parent().addClass('selected');
                
                return false;
            }).filter('[href=#tab-1]').click();
        });

        

    // Date Time picker
        $("#dtBox").DateTimePicker({
            maxDate: '0'
        }); 
    



}); // END OF - DOCUMENT READY FUNCTION

       

