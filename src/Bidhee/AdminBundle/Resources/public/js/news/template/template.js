/**
 * Created by Danepliz on 2/4/16.
 */

;
(function(ns){

    'use strict';


    function Template(){

        this.toggleStatus = function(obj, response){
            var label = 'YES',labelClass = 'label-success';

            if( ! response.status )
            {
                label = 'NO';
                labelClass = 'label-warning';
            }

            var html = '<span class="label '+labelClass+'">'+label+'</span>';
            obj.html(html);

        };

        this.loading = function(obj, response, isLoading){
            if( isLoading == false ){
                this.toggleStatus(obj, response);
            }else{
                obj.html('<span class="label label-info"><i class="fa fa-cog fa-spin"></i></span>');
            }
        }

    };

    ns.template = new Template();

})(news);
