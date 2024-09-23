// By : Codicode.com
// Source : http: //www.codicode.com/art/jquery_endless_div_scroll.aspx
// Licence : Creative Commons Attribution license (http://creativecommons.org/licenses/by/3.0/)

// You can use this plugin for commercial and personal projects.
// You can distribute, transform and use them into your work,
// but please always give credit to www.codicode.com

// The above copyright notice and this permission This notice shall be included in
// all copies or substantial portions of the Software.

(function(e) {
    e.fn.endlessScrollHorizontal = function(t) {
        function a(e, t, n) {
            e.css({ left: n ? "0px" : e.width() + "px" });
            t.css({ left: n ? -1 * e.width() + "px" : "0px" })
        }
        var t = e.extend({ width: "400px", height: "100px", steps: -2, speed: 40, mousestop: true }, t);
        var n = e(this);
        var r = e(this).attr("id");
        var i = t.steps;
        n.css({ overflow: "hidden", width: t.width, height: t.height, position: "relative", left: "0px", top: "0px" });
        n.wrapInner("<nobr />");
        n.mouseover(function() { if (t.mousestop) { i = 0 } });
        n.mouseout(function() { i = t.steps });
        n.wrapInner("<div id='" + r + "1' />");
        var s = e("#" + r + "1");
        s.css({ position: "absolute" }).clone().attr("id", r + "2").insertAfter(s);
        var o = e("#" + r + "2");
        a(s, o, t.steps > 0);
        var u = setInterval(function() {
            s.css({ left: parseInt(s.css("left")) + i + "px" });
            o.css({ left: parseInt(o.css("left")) + i + "px" });
            if (parseInt(s.css("left")) < 0 || parseInt(s.css("left")) > s.width()) { a(s, o, t.steps > 0) }
        }, t.speed);
        return n
    }
})(jQuery);

(function($) {
    $.fn.endlessScrollVertical = function(options) {
        var options = $.extend({ width: "400px", height: "100px", steps: -2, speed: 40, mousestop: true }, options);
        var elem = $(this);
        var elemId = $(this).attr("id");
        var istep = options.steps;
        elem.css({ "overflow": "hidden", "width": options.width, "height": options.height, "position": "relative", "bottom": "0px", "right": "0px" });
        elem.mouseover(function() { if (options.mousestop) { istep = 0; } });
        elem.mouseout(function() { istep = options.steps; });
        elem.wrapInner("<div id='" + elemId + "1' />");
        var e1 = $('#' + elemId + "1");
        e1.css({ "position": "absolute" }).clone().attr('id', elemId + "2").insertAfter(e1);
        var e2 = $('#' + elemId + "2");
        Repos(e1, e2, options.steps > 0);
        var refreshId = setInterval(function() {
            e1.css({ "bottom": (parseInt(e1.css("bottom")) + istep) + "px" });
            e2.css({ "bottom": (parseInt(e2.css("bottom")) + istep) + "px" });
            if ((parseInt(e1.css("bottom")) < 0) || (parseInt(e1.css("bottom")) > e1.height())) { Repos(e1, e2, options.steps > 0); }
        }, options.speed);

        function Repos(e1, e2, fwd) {
            e1.css({ "bottom": (fwd) ? "0px" : e1.height() + "px" });
            e2.css({ "bottom": (fwd) ? (-1 * e1.height()) + "px" : "0px" });
        }
        return elem;
    }
})(jQuery);