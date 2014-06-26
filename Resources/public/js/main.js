$(document).ready(function(e){
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).data('service');
        $('.input-group #search_param').val(param);
        alert(concept);
    });
});