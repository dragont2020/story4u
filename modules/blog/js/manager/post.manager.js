function IsJson(str) {
    try {
        JSON.parse(str)
    } catch (e) {
        return false
    }
    return true
}

function get_link(a = "") {
    if ("" == a && (a = urls.val()), checku.test(a)) {
        var b = "";
        $("#get_icon").is(":checked") && (b = "ok"), $("div.col-md-8").prepend('<div id="loader" style="position: absolute;top: 50%;left: 50%;z-index: 999"><div class="loader"></div><div class="load cssload-whirlpool"></div></div>'), $("#result").show(), $.ajax({
            type: "post",
            dataType: "text",
            data: {
                get_link: "get",
                get_icon: b,
                url: a
            },
            success: function(b) {
                urls.val(urls.val().replace(a + "\n", ""));
                countLine();
                if (IsJson(get_val(b))) {
                    var c = JSON.parse(get_val(b));
                    checku.test(c.info.icon) ? icon = c.info.icon : icon = "/files/posts/images/" + c.info.icon, $(".item").find("img").attr("src", icon), uicon.val(c.info.icon), title.html(c.info.title), $("div#parent").html(parent.find("option:selected").html()), reup.attr('data', c.info.icons);, des.html(c.info.des);
                    var d = '<ul class="w3-ul w3-hoverable">',
                        e = 0;
                    $.each(c.link, function(a, b) {
                        d += '<li class="w3-grey w3-round" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;-webkit-transition: 2s; transition: 2s;"><input type="checkbox" checked/> <code>' + a + "</code> <span>" + b + "</span></li>", e += 1
                    }), d += "</ul>", $("#num").html('<i class="fa fa-list-ol"></i> ' + e + " link(s)"), $("#link").html(d).animate({
                        scrollTop: 0
                    }, 1e3), $("#loader").remove(), ca() && create()
                } else {
                    get_link(a)
                }
            }
        }).fail(function() {
            get_link(a)
        })
    }
}

function create() {
    $("#get").attr("disabled", !0), $("#tool").attr("disabled", !0), "Post Title" != title.html() && $.ajax({
        type: "post",
        dataType: "text",
        data: {
            create: "create",
            title: title.html(),
            parent: parent.val(),
            icon: uicon.val(),
            des: des.html()
        },
        success: function(a) {
            if (IsJson(get_val(a))) {
                var b = JSON.parse(get_val(a));
                title.html(b.url), uicon.attr("type", "button").val(b.id), ca() && posts()
            } else {
                create()
            }
        }
    }).fail(function() {
        create()
    })
}

function posts() {
    var a = $("ul.w3-ul li.w3-grey:first"),
        b = a.find("span"),
        c = a.find("code");
    a.length && (a.find("input").is(":checked") ? $.ajax({
        type: "post",
        dataType: "text",
        data: {
            post: "post",
            url: b.html(),
            parent: uicon.val(),
            weight: c.html()
        },
        success: function(d) {
            d = get_val(d), checka.test(d) && (b.slideUp(function() {
                b.html(d).slideDown()
            }), a.removeClass("w3-grey")), posts(), c.html() >= 7 && $("#link").animate({
                scrollTop: 45 * (c.html() - 7)
            }, 1e3)
        }
    }).fail(function() {
        posts()
    }) : (a.removeClass("w3-grey"), posts())), pt()
}

function pt() {
    num = $("ul.w3-ul li").length, done = $("ul.w3-ul li:not(.w3-grey)").length, done += $("ul.w3-ul li.w3-khaki").length;
    var a = (100 * done / num).toFixed(2) + "%";
    100 * done / num < 100 ? ($("#main_bar").slideDown(), $("#main_bar").find(".progress-bar").css("width", a).html(a)) : ($("#get").attr("disabled", !1), $("#tool").attr("disabled", !1), $("#main_bar").slideUp(), ca() && auto_run())
}

function auto_run() {
    if ("disabled" != $("#get").attr("disabled") && "disabled" != $("#tool").attr("disabled")) {
        var a = urls.val().split("\n");
        a = a.filter(function(a) {
            return checku.test(a)
        }), a.length > 0 && get_link(a[0])
    }
}

function ca() {
    return $("#auto_get").is(":checked")
}

function tool() {
    $("div.col-md-4").prepend('<div class="cssload-container" style="position: absolute;top: 50%;left: 50%;z-index: 999"><div class="cssload-whirlpool"></div></div>'), urls.attr("disabled", !0), $.ajax({
        type: "post",
        dataType: "text",
        data: {
            urltool: uto.val(),
            fromtool: fto.val(),
            totool: tto.val()
        },
        success: function(a) {
            "" != a && (urls.val(urls.val() + get_val(a)).attr("disabled", !1), $(".cssload-container").remove(), countLine(), ca() && auto_run())
        }
    }).fail(function() {
        tool()
    })
}

function get_val(a) {
    return s = a.split("</textarea>"), s[0].replace("<textarea>", "")
}

function countLine() {
    var cou = urls.val().split('\n');
    cou = cou.filter(function(v) {
        return (checku.test(v))
    }).length;
    if (cou == 0) {
        len = ''
    } else {
        len = '<code>' + cou + '</code> link(s)'
    }
    $('#comma').html(len)
}

function re_up() {
    if (checku.test(uicon.val())) {
        $.ajax({
            type: "post",
            dataType: "text",
            data: {
                upimg: "up",
                url: reup.attr('data'),
                title: title.text()
            },
            success: function(d) {
                $(".item").find("img").attr("src", get_val(d))
            }
        }).fail(function() {
            re_up()
        })
    }
}
var urls = $("#urls"),
    uicon = $("#icon_url"),
    title = $("#title"),
    parent = $("select#parent"),
    des = $("#des"),
    checka = /<a href=\"http.+\".+>.+<\/a>/,
    checku = /http:\/\/.+\..+\/./,
    reup = $('#re_up'),
    ptv = "0px",
    uto = $("#urltool"),
    fto = $("#fromtool"),
    tto = $("#totool"),
    tt = new Array;
tt[1] = ["http://thichtruyen.vn/danh-muc/truyen-ngon-tinh", "http://tieuthuyethay.com/ngon-tinh-trung-quoc"], tt[2] = ["http://thichtruyen.vn/danh-muc/truyen-teen", "http://tieuthuyethay.com/truyen-teen"], tt[3] = ["http://thichtruyen.vn/danh-muc/xuyen-khong", "http://tieuthuyethay.com/xuyen-khong"], tt[4] = ["http://thichtruyen.vn/danh-muc/truyen-kiem-hiep", "http://tieuthuyethay.com/kiem-hiep"], tt[5] = ["http://thichtruyen.vn/danh-muc/trinh-tham", "http://tieuthuyethay.com/trinh-tham"], tt[6] = ["http://thichtruyen.vn/danh-muc/truyen-kinh-di"], $("#all").click(function() {
    $("li input:checkbox").each(function() {
        "checked" == $(this).attr("checked") ? $(this).attr("checked", !1) : $(this).attr("checked", !0)
    })
}), fto.change(function() {
    tto.val() < fto.val() && tto.val(fto.val())
}), tto.change(function() {
    fto.val() > tto.val() && fto.val(tto.val())
}), urls.on("input", function() {
    countLine();
    ca() && auto_run()
}), urls.dblclick(function() {
    $(this).select()
});

function change_pa(a) {
    b = "";
    $.each(tt[a], function(a, c) {
        b += '<option value="' + c + '">' + c + "</option>"
    }), $("#urltool").html(b)
}
$(function() {
    $(window).keyup(function(e) {
        key = e.keyCode;
        if (key >= 49 && key <= 54) {
            p = (key - 48);
            parent.val(p);
            change_pa(p)
        }
    })
})