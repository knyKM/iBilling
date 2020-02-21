
function updateDiv(action,_url,cid,cb){
    var pbar = $('#progressbar');
    $('#ibox_form').block({ message: null });
    var body = $("html, body");
    body.animate({scrollTop:0}, '1000', 'swing');
    $('#progressbar').show();

    var timer = setInterval(function () {
        pbar.progressbar('stepIt');

    }, 250);

    $.post(_url +  action + '/', {
        cid: cid

    })
        .done(function (data) {

            setTimeout(function () {
                clearInterval(timer);
                $("#sysfrm_ajaxrender").html(data);
                $('#ibox_form').unblock();
                $('#progressbar').progressbar('reset');
                $('#progressbar').hide();
                $('.sysedit').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['style']], // no style button
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link']], // no insert buttons
                        ['table', ['table']], // no table button
                        ['view', ['codeview']], // no table button
                        //['help', ['help']] //no help button
                    ],
                    focus: true
                });
                cb();
                //var _df = $("#_df").val();
                //$( ".sdate" ).each(function() {
                //    //   alert($( this ).html());
                //    var ut = $( this ).html();
                //    $( this ).html(moment.unix(ut).format(_df));
                //});

                $( ".mmnt" ).each(function() {
                    //   alert($( this ).html());
                    var ut = $( this ).html();
                    $( this ).html(moment.unix(ut).fromNow());
                });
            }, 2000);
        });

}

$(document).ready(function () {

    var pbar = $('#progressbar');
    pbar.hide();

    pbar.progressbar({
        warningMarker: 100,
        dangerMarker: 100,
        maximum: 100,
        step: 15
    });






    $("#emsg").hide();

    $(".cdelete").click(function (e) {
        e.preventDefault();
        var id = this.id;
        var lan_msg = $("#_lan_are_you_sure").val();
        bootbox.confirm(lan_msg, function(result) {
            if(result){
                var _url = $("#_url").val();
                window.location.href = _url + "delete/user/" + id + '/';
            }
        });
    });

















    $("#note_update").click(function (e) {
        e.preventDefault();
        $('#ibox_panel').block({ message: null });
        var _url = $("#_url").val();
        $.post(_url + 'contacts/edit-notes/', {
            cid: $('#cid').val(),

            notes: $('#notes').val()

        })
            .done(function () {
                //bootbox.alert("Notes Saved", function() {
                //    $("#note_update").html("Save");
                //});
                $('#ibox_panel').unblock();

            });



    });
    var cid = $('#cid').val();
    var _url = $("#_url").val();
    var cb = function cb (){
     //  return;
    };
    updateDiv('contacts/activity',_url,cid,cb);
    $("#summary").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        updateDiv('contacts/summary',_url,cid,cb);
    });



    $("#invoices").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        updateDiv('contacts/invoices',_url,cid,cb);
    });

    $("#transactions").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        updateDiv('contacts/transactions',_url,cid,cb);
    });

    $("#email").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        updateDiv('contacts/email',_url,cid,cb);


    });

    $("#edit").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");
        var cb = function cb (){
            var _url = $("#_url").val();
            $("#country").select2({
                theme: "bootstrap"
            });
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
            //    maximumSelectionSize: 20,
            //
            //    // override message for max tags
            //    formatSelectionTooBig: function (limit) {
            //        return "Max tags is " + limit;
            //    }
            //});

            $('#tags').select2({
                tags: true,
                tokenSeparators: [','],
                theme: "bootstrap"
            });

        };

        updateDiv('contacts/edit',_url,cid,cb);
    });

    $("#more").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        var cb = function cb (){
            var _url = $("#_url").val();
            var croppicHeaderOptions = {

                uploadUrl: _url + 'sys_imgcrop/save/',
                cropData:{
                    "email":1,
                    "rnd":"rnd"
                },
                cropUrl:  _url + 'sys_imgcrop/crop/',
                outputUrlId:'picture',
                customUploadButtonId:'cropContainerHeaderButton',
                modal:false,
                loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
                onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
                onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
                onImgDrag: function(){ console.log('onImgDrag') },
                onImgZoom: function(){ console.log('onImgZoom') },
                onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
                onAfterImgCrop:function(){ console.log('onAfterImgCrop') }
            }
            var croppic = new Croppic('croppic', croppicHeaderOptions);
        }
        updateDiv('contacts/more',_url,cid,cb);
    });


    $("#activity").click(function (e) {
        e.preventDefault();
        $('.list-group a.active').removeClass('active');
        $(this).addClass("active");


        updateDiv('contacts/activity',_url,cid,cb);
    });

var sysrender = $('#sysfrm_ajaxrender');
    sysrender.on('click', '#acf-post', function(e){
        e.preventDefault();
        $('#ibox_form').block({ message: null });
        var _url = $("#_url").val();
        $.post(_url + 'contacts/add-activity-post/', {

            cid: $('#cid').val(),
            msg: $('#msg').val(),
            icon: $('#activity-type').val()

        })
            .done(function (data) {

                setTimeout(function () {
                    var sbutton = $("#acf-post");
                    var _url = $("#_url").val();
                    if ($.isNumeric(data)) {

                        window.location = _url + 'contacts/view/' + data + '/';
                    }
                    else {
                        $('#ibox_form').unblock();

                        $("#emsgbody").html(data);
                        $("#emsg").show("slow");
                    }
                }, 2000);
            });
    });


    sysrender.on('click', '#submit', function(e){
        e.preventDefault();
        $('#ibox_form').block({ message: null });
        var _url = $("#_url").val();
        $.post(_url + 'contacts/edit-post/', $( "#rform" ).serialize())
            .done(function (data) {

                setTimeout(function () {
                    var sbutton = $("#submit");
                    var _url = $("#_url").val();

                    if ($.isNumeric(data)) {

                        window.location = _url + 'contacts/view/' + data + '/';
                    }
                    else {
                        $('#ibox_form').unblock();

                        $("#emsgbody").html(data);
                        $("#emsg").show("slow");
                    }
                }, 2000);
            });
    });


    sysrender.on('click', '#send_email', function(e){
        e.preventDefault();
        $('#ibox_form').block({ message: null });
        var _url = $("#_url").val();

        $.post(_url + 'contacts/send_email/', {
            cid: $('#cid').val(),

            subject: $('#subject').val(),
            message: $('.sysedit').code()


        })
            .done(function (data) {

                setTimeout(function () {
                    var sbutton = $("#send_email");
                    var _url = $("#_url").val();
                    if ($.isNumeric(data)) {

                        window.location = _url + 'contacts/view/' + data + '/';
                    }
                    else {
                        $('#ibox_form').unblock();

                        $("#emsgbody").html(data);
                        $("#emsg").show("slow");
                    }
                }, 2000);
            });
    });

    sysrender.on('click', '#no_image', function(e){
        e.preventDefault();
        $('#picture').val('');

    });


    sysrender.on('click', '#opt_gravatar', function(e){
        e.preventDefault();

        $('.picture').val('gravatar');

    });

    sysrender.on('click', '#more_submit', function(e){
        e.preventDefault();


        $('#ibox_form').block({ message: null });
        var _url = $("#_url").val();
        $.post(_url + 'contacts/edit-more/', {
            cid: $('#cid').val(),
            picture: $('#picture').val(),
            facebook: $('#facebook').val(),
            google: $('#google').val(),
            linkedin: $('#linkedin').val()

        })
            .done(function (data) {

                setTimeout(function () {
                    var sbutton = $("#more_submit");
                    var _url = $("#_url").val();
                    if ($.isNumeric(data)) {

                        window.location = _url + 'contacts/view/' + data + '/';
                    }
                    else {
                        $('#ibox_form').unblock();

                        $("#emsgbody").html(data);
                        $("#emsg").show("slow");
                    }
                }, 2000);
            });

    });

    sysrender.on('click', '.clickable', function(e){
        e.preventDefault();
        $(".compose-toolbar li").removeClass("action-active");
        $(this).addClass("action-active");
        var atype = $(this).html();

        $('#activity-type').val(atype);
    });



    function update_time(){
        $( ".sdate" ).each(function() {
            //   alert($( this ).html());
            var ut = $( this ).html();
            $( this ).html(moment.unix(ut).format(_df));
        });

        $( ".mmnt" ).each(function() {
            //   alert($( this ).html());
            var ut = $( this ).html();
            $( this ).html(moment.unix(ut).fromNow());
        });
    }

});