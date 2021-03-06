$(document).ready(function () {
    $(".archive_year").click(function () {
        var year_val = $(this).text();
        var container = $(this).closest("li");
        if (container.hasClass('unfold')) {
            fold_month();
        } else {
            $.ajax({
                type: "POST",
                url: "/get_months",
                data: {year: year_val}
            }).done(function (html) {
                fold_month();
                container.append(html);
                $(".archive_month_list").slideDown();
            });
        }
        container.toggleClass('unfold');
    });

    function fold_month() {
        if ($(".archive_month_list")) {
            $(".archive_month_list").slideUp();
            $(".archive_month_list").remove();
        }
    }

    $(".like_link").click(function () {
        var art_id = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: "/like-" + art_id
        }).done(function (html) {
            $('#likes-' + art_id).html(html)
        });
        return false;
    });
});
