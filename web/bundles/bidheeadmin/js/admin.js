/**
 * Created by Danepliz on 2/4/16.
 */


(function () {

    $('label.required').append('<em class="required">*</em>');

    $(".my-switch").bootstrapSwitch({
        size: 'mini',
        onColor: 'success',
        offColor: 'danger',
        onText: 'YES',
        offText: 'NO'
    });

    $('.select2').select2();

    $('[data-toggle="myAjax"]').click(function () {

        var self = $(this),
            entity = self.data('entity'),
            property = self.data('property'),
            id = self.data('id'),
            status = self.data('status');

        if (entity == '' || entity == 'undefined' || property == '' || property == 'undefined' || id == '' || id == 'undefined') {
            return false;
        }

        bootbox.confirm('Are you sure change?', function (result) {
            if (result == true) {
                var http = news.core.http;
                var template = news.template;

                template.loading(self, null, true);

                var data = {entity: entity, property: property, id: id, status: status};

                var url = news.baseUrl + 'ajax/news/toggle';

                http.post(url, data)
                    .done(function (response) {
                        template.toggleStatus(self, response);

                    })
                    .error(function (response) {
                        template.loading(self, response, false);
                    })
                    .always(function (response) {
                        template.loading(self, response, false);
                    });
            }
        });

    });

    var featuredImageInput = $('.featuredImage');
    var featuredImageWrapper = $('.featuredImageHolder');

    featuredImageInput.on('change', function () {

        var html = '',
            value = $(this).val(),
            message = '';

        if (value != '') {
            html = html + '<div>';
            html = html + '<img src="' + value + '" width="100%" />';
            html = html + '<a class="pull-right removeImage" title="Remove Image">';
            html = html + '<i class="fa fa-trash" onclick="removeFeaturedImage(\'.featuredImage\')"></i>';
            html = html + '</a>';
            html = html + '</div>';
            html = html + '<div class="clearfix"></div>';
            message = 'Click below to change image.';
        }
        featuredImageWrapper.html(html);
        featuredImageInput.prev('cite').remove();
        featuredImageInput.prev('label').after('<cite class="info"> ' + message + '</cite>');
    });
    featuredImageInput.trigger('change');

    var categoryFeaturedImageHolder = $('.categoryFeaturedImage');
    var categoryFeaturedImageWrapper = $('.categoryFeaturedImageHolder');

    categoryFeaturedImageHolder.on('change', function () {

        var html = '',
            value = $(this).val(),
            message = '';

        if (value != '') {
            html = html + '<div>';
            html = html + '<img src="' + value + '" width="100%" />';
            html = html + '<a class="pull-right removeImage" title="Remove Image">';
            html = html + '<i class="fa fa-trash" onclick="removeFeaturedImage(\'.categoryFeaturedImage\')"></i>';
            html = html + '</a>';
            html = html + '</div>';
            html = html + '<div class="clearfix"></div>';
            message = 'Click below to change image.';
        }
        categoryFeaturedImageWrapper.html(html);
        categoryFeaturedImageHolder.prev('cite').remove();
        categoryFeaturedImageHolder.prev('label').after('<cite class="info"> ' + message + '</cite>');
    });
    categoryFeaturedImageHolder.trigger('change');


    /* Collection Type Multiple Add Form */
    var $collectionHolder;

    var $addTagLink = $('<a href="#" class="add_tag_link"><i class="fa fa-plus"></i>Add Another</a>');
    var $newLinkLi = $('<li></li>').append($addTagLink);


    jQuery(document).ready(function () {
        $collectionHolder = $('ul.tags');
        $collectionHolder.append($newLinkLi);
        var inputLength = $collectionHolder.find(':input').length;
        $collectionHolder.data('index', inputLength);
        $addTagLink.on('click', function (e) {
            e.preventDefault();
            addTagForm($collectionHolder, $newLinkLi);
        });

        if (inputLength == 0) {
            $addTagLink.trigger('click');
        }

    });


})();

function removeFeaturedImage(obj) {
    if (confirm('Are you sure to remove feature image? Image will be removed only after save.')) {
        $(obj).val('').trigger('change');
    }
    return false;
}

function removeImage(obj) {

    var formLen = $('ul.tags').find(':input').length;

    if (confirm('Are you sure to delete?')) {
        var btn = $(obj);
        var imageId = btn.data('id');
        if (imageId == 0) {
            btn.parent('li').remove();
        } else {
            btn.parent('li').remove();
        }


    }
}
function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    if (index !== undefined) {
        var newForm = prototype.replace(/__name__/g, index);

        $collectionHolder.data('index', index + 1);

        var removeLink = $('<span onclick="removeImage(this)" data-id="0" class="removeImage cursor" ><i class="fa fa-trash"></i></span>');

        var $newFormLi = $('<li></li>').append(newForm);
        $newFormLi.append(removeLink);
        $newLinkLi.before($newFormLi);
    }


}


