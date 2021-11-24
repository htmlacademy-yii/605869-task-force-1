$(function () {
    $('span#dropzone').dropzone({
        uploadMultiple: true,
        createImageThumbnails: true,
        url: "/account/upload",
        error: function (file, message, xhr) {
           if (xhr.status === 400) {
               alert('максимум может быть загружено 6 фото');
           }
        },
        success: function () {
            $.plax.reload({container: '#photoList'});
        }
    });
});