$(document).ready(function () {
    let token = "c9a16f7f6b541566d3657c8458983070ccc0db99";
    $("#address").suggestions({
        token: token,
        type: "ADDRESS",
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function (suggestion) {
            //console.log(suggestion);
            $('#lat').val(suggestion.data.geo_lat);
            $('#long').val(suggestion.data.geo_lon);
            $('#city').val(suggestion.data.city);
            $('#village').val(suggestion.data.settlement);
            $('#kladr').val(suggestion.data.kladr_id);
        }
    });
});