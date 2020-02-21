$(document).ready(function () {
    $(".progress").hide();
    $("#emsg").hide();
    var _url = $("#_url").val();
    //$('#tags').select2({
    //    tags: true,
    //    tokenSeparators: [','],
    //    createSearchChoice: function (term) {
    //        return {
    //            id: $.trim(term),
    //            text: $.trim(term) + ' (new tag)'
    //        };
    //    },
    //    ajax: {
    //        url: _url+'tags/contacts/',
    //        dataType: 'json',
    //        data: function(term, page) {
    //            return {
    //                q: term
    //            };
    //        },
    //        results: function(data, page) {
    //            return {
    //                results: data
    //            };
    //        }
    //    },
    //
    //    // Take default tags from the input value
    //    initSelection: function (element, callback) {
    //        var data = [];
    //
    //        function splitVal(string, separator) {
    //            var val, i, l;
    //            if (string === null || string.length < 1) return [];
    //            val = string.split(separator);
    //            for (i = 0, l = val.length; i < l; i = i + 1) val[i] = $.trim(val[i]);
    //            return val;
    //        }
    //
    //        $(splitVal(element.val(), ",")).each(function () {
    //            data.push({
    //                id: this,
    //                text: this
    //            });
    //        });
    //
    //        callback(data);
    //    },
    //
    //    // Some nice improvements:
    //
    //    // max tags is 3
    //    maximumSelectionSize: 15,
    //
    //    // override message for max tags
    //    formatSelectionTooBig: function (limit) {
    //        return "Max tags is " + limit;
    //    }
    //});

    // @ from v 2.4


    $('#tags').select2({
        tags: true,
        tokenSeparators: [','],
        theme: "bootstrap"
    });


    //
    $("#submit").click(function (e) {
        e.preventDefault();
        $('#ibox_form').block({ message: null });
        var _url = $("#_url").val();
        $.post(_url + 'contacts/add-post/', $( "#rform" ).serialize())
            .done(function (data) {

                setTimeout(function () {
                    var sbutton = $("#submit");
                    var _url = $("#_url").val();
                    if ($.isNumeric(data)) {

                        window.location = _url + 'contacts/view/' + data;
                    }
                    else {
                        $('#ibox_form').unblock();
                        var body = $("html, body");
                        body.animate({scrollTop:0}, '1000', 'swing');
                        $("#emsgbody").html(data);
                        $("#emsg").show("slow");
                    }
                }, 2000);
            });
    });
});