$(document).ready(function () {

    // Добавление категории
    $(document).on('click', '.js-add-category', function (e) {
        e.preventDefault();
        var $current = $(this);

        $.ajax({
            url: ".",
            method: "POST",
            data: {
                action: 'add_cat',
                parent: $current.data('parent')
            },
            success: function(result) {
                $(".js-categories").html(result);
            },
            error: function () {
                alert('Error!');
            }
        });

    });

    // Удаление категории
    $(document).on('click', '.js-category', function (e) {
        e.preventDefault();

        if (!confirm('Подтвердите удаление!')) return false;

        var $current = $(this);

        $.ajax({
            url: ".",
            method: "POST",
            data: {
                action: 'del_cat',
                id: $current.text()
            },
            success: function(result) {
                if (!result) {
                    result = '<li><a href="#" data-parent="0" class="js-add-category">+</a></li>';
                }
                $(".js-categories").html(result);
            },
            error: function () {
                alert('Error!');
            }
        });

    });

});
