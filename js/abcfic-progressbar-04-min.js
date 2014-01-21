/*
 * Progress bar
 */
(function($) {
    abcficPBar = {
        settings: {
            id:	'progressbar',
            maxStep: 100,
            wait: false,
            header: '',
            init:false
        },

        init: function( param ) {
                pbsit = this.settings = $.extend( {}, this.settings, {}, param || {} );
                //this.settings = $.extend( {}, this.settings, {}, param || {} );
                //pbsit = this.settings;

                width = Math.round( ( 100 / pbsit.maxStep ) * 100 ) /100;
                // add the initial progressbar
                if ( $( "#" + pbsit.id + "_dialog" ).length == 0) {
                        pbsit.header = (pbsit.header.length > 0) ? pbsit.header : '' ;
                        $("body").append('<div id="' + pbsit.id + '_dialog"><div id="' + pbsit.id + '" class="abcficPBCntr"><div class="' + pbsit.id + '"><span>0%</span></div></div></div>');
                     $('html,body').scrollTop(0); // works only in IE, FF
                    // we open the dialog
                    $( "#" + pbsit.id + "_dialog" ).dialog({
                            width: 640,
                            height: 'auto',
                            resizable : true,
                            modal: true,
                            title: pbsit.header
                    });
                }
        // get the pointer to the dialog
        div = $('#' + pbsit.id + '_dialog');
        pbsit.init = true;
        },
        addNote: function( note, detail ) {
                pbsan = this.settings;
                pbsan.wait = true;
                if ( div.find("#" + pbsan.id + "_note").length == 0)
                        div.append('<ul id="' + pbsan.id + '_note">&nbsp;</ul>');
                if (detail)
                    $("#" + pbsan.id + "_note").append("<li>" + note + "<div class='abcficPBMsg'><span>[more]</span><br />" + detail + "</div></li>");
                else
                    $("#" + pbsan.id + "_note").append("<li>" + note + "</li>");
                // increase H to show the note
                div.dialog("option", "height", 300);
        },
        increase: function( step ) {
                pbsic = this.settings;
                var value = step * width + "%";
                var rvalue = Math.round (step * width) + "%" ;
                $("#" + pbsic.id + " div").width( value );
                $("#" + pbsic.id + " span").html( rvalue );
        },
        finished: function() {
            pbsfd = this.settings;
            $("#" + pbsfd.id + " div").width( '100%' );
            $("#" + pbsfd.id + " span").html( '100%' );
            // in the case we add a note , we should wait for a click
            if (pbsfd.wait) {
                //$("#" + pbsfd.id).delay(1000).hide("slow");
                div.click(function () {
                        $("#" + pbsfd.id + "_dialog").delay(2000).fadeTo('slow', 0, function() {
                        $("#" + pbsfd.id + "_dialog" ).dialog('destroy').remove();
                    });
                });
           }
           else {
                window.setTimeout(function() {
                    $("#" + pbsfd.id + "_dialog").delay(2000).fadeTo('slow', 0, function() {
                    $("#" + pbsfd.id + "_dialog" ).dialog('destroy').remove();
                    });
                }, 100);
            }
        }
    };
})(jQuery);