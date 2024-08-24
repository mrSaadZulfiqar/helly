$(document).ready(function () {
    'use strict';
    /*============================================
        Read more toggle button
    ============================================*/
    $(".read-more-btn").on("click", function () {
        $(this).prev().toggleClass('show');

        if ($(this).prev().hasClass("show")) {
            $(this).text(ReadLess);
        } else {
            $(this).text(ReadMore);
        }
    })

    /*============================================
        Toggle List
    ============================================*/
    $("#toggleList").each(function (i) {
        var list = $(this).children();
        var listShow = $(this).data("toggle-show");
        var listShowBtn = $(this).next("[data-toggle-btn]");

        if (list.length > listShow) {
            listShowBtn.show()
            list.slice(listShow).toggle(300);

            listShowBtn.on("click", function () {
                list.slice(listShow).slideToggle(300);
                $(this).text($(this).text() === "Show More" ? "Show Less" : "Show More")
            })
        } else {
            listShowBtn.hide()
        }
    })

    /*============================================
        Sidebar category toggle
    ============================================*/
    $(".category-toggle").on("click", function (t) {
        var i = $(this).closest("li"),
            o = i.find("ul").eq(0);

        if (i.hasClass("open")) {
            o.slideUp(300, function () {
                i.removeClass("open")
            })
        } else {
            o.slideDown(300, function () {
                i.addClass("open")
            })
        }
        t.stopPropagation(), t.preventDefault()
    })

    $('.nice-select1').niceSelect();
})
