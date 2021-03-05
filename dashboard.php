<?php
require "go.php";

?>
<!DOCTYPE html>
<html lang='en_US'>

<head>
    <title><?php echo "Your Dashboard - Portal"; ?></title>
    <link rel="stylesheet" href="files/css/font-awesome.css" type="text/css" media="all">
    <link rel="stylesheet" href="files/css/themify-icons.css" type="text/css" media="all">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="files/js/jquery-3.js"></script>
    <script src="files/js/core.js"></script>
    <script src="files/js/charts.js"></script>
    <script src="files/js/animated.js"></script>
    <link rel="stylesheet" href="files/css/mprogress.min.css">
    <script src="files/js/mprogress.min.js"></script>

    <script type="text/javascript">
        var begin = new Mprogress({
            template: 3,
            parent: '.lloader'
        });

        function bardi(p) {
            $(p).parent().removeClass('istay');

            $(p).parent().parent().siblings().children().removeClass('istay');
            $('.more_bar').removeClass('more_bar_show');
        }

        function bard(p) {
            $(p).parent().toggleClass('istay');

            $(p).parent().parent().siblings().children().removeClass('istay');

            $(p).parent().parent().siblings().find('.more_bar').removeClass('more_bar_show');
            $(p).siblings('.more_bar').toggleClass('more_bar_show');
        }

        function gg(t) {
            $('.updaterp').addClass('show');
            $('.updater').html(t);
            setTimeout(function() {
                $('.updaterp').removeClass('show');
                setTimeout(function() {
                    $('.updater').text("");
                }, 1000)
            }, 6000);
        }
        $(document).ready(function() {

            $('.input100').each(function() {
                $(this).focus(function() {
                    hideValidate(this);
                    $(this).parent().removeClass('true-validate');
                });
            });

            $('.input100').each(function() {
                $(this).on('blur', function() {
                    if (validate(this) == false) {
                        showValidate(this);
                    } else {
                        $(this).parent().addClass('true-validate');
                    }
                })
            })

            $('.input100').each(function() {
                $(this).on('blur', function() {
                    if ($(this).val().trim() != "") {
                        $(this).addClass('has-val');
                    } else {
                        $(this).removeClass('has-val');
                    }
                })
            });
        })

        function chec(x) {
            if ($(x).val() == "0" && $(x).siblings('div').length < 1) {
                return false;
            } else if ($(x).val() != "all" && $(x).val() != "lifestyle" && $(x).val() != "culture") {
                if ($(x).siblings('div').length < 1) {
                    return false;
                }
            }
        }


        function validate(input) {
            if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
                if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null /*regex bla bla*/ ) {
                    return false;
                }
            } else if ($(input).attr('name') == "select" && chec(input) == false) {
                return false;
            } else if ($(input).attr('type') == "password" && $(input).attr('name') == "repeat-pass" && $(input).val() != $('.mainpass').val()) {
                $(input).attr('data-validate', 'Repeat Password Is Not Equal With Password');
                return false;

            } else if ($(input).attr('name') == "pass" && validatePass(input)) {
                return false;
            } else if ($(input).attr('name') == "bio" && ($(input).val().length < 150 || $(input).val().length > 500)) {
                return false;
            } else {
                if ($(input).val().trim() == '') {
                    return false;
                }
            }
        }

        function validatePass(p) {
            //return true if error
            var noNum = noChar = noSpec = false,
                pass = $(p).val();
            if (pass != "") {
                if (/[\d]/g.test(pass)) {
                    noNum = true;
                }
                if (/[\W]/g.test(pass)) {
                    noSpec = true;
                }
                if (pass.length >= 8) {
                    noChar = true;
                }
                if ((noChar && noNum) || (noChar && noSpec)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        };

        //hides or shows the validation box
        function showValidate(input) {
            var thisAlert = $(input).parent();
            $(thisAlert).addClass('alert-validate');
        }

        function hideValidate(input) {
            var thisAlert = $(input).parent();
            $(thisAlert).removeClass('alert-validate');
            $('.error_note').css('display', 'none');
        }

        function show_pass(e, pass) {
            if ($(pass).attr('type') == "password") {
                $(pass).attr('type', 'text');
                $(e).addClass('fa-eye-slash');
                $(e).removeClass('fa-eye');
            } else {
                $(pass).attr('type', 'password');
                $(e).removeClass('fa-eye-slash');
                $(e).addClass('fa-eye');
            }
        }

        function sendImg(myFile /*the file div*/ ) {
            // Create a FormData object
            var formData = new FormData(),
                col, val;
            clearInterval(bv);

            if (!myFile.type.match('image.*')) {
                gg('The file selected is not an accepted Image.');

            } else if (myFile.size > 10048576) {
                gg("You Have Exceeded The Maximum File Limit Of 10mb");
            } else {
                formData.append('file', myFile, myFile.name);

                var h;

                if (window.XMLHttpRequest) {
                    h = new XMLHttpRequest();
                } else {
                    h = new ActiveXObject("Microsoft.XMLHTTP");
                };

                h.open('POST', 'uplp.php', true);

                h.upload.addEventListener('progress', function(q) {
                    col = (q.loaded / q.total) * 100;
                    val = col / 100;
                    gg("Uploaded... " + col.toFixed(2) + "%");
                });

                h.onreadystatechange = function() {
                    if (h.status == 200 && h.readyState == 4) {
                        if (h.responseText.indexOf('ppp') != -1) {
                            gg("Upload Complete");

                            var img = h.responseText.substr(h.responseText.indexOf('ppp') + 3);
                            $('.profile_pic').css('background-image', 'url(\'' + img + '\')');

                        } else {
                            gg(h.responseText);
                        }
                    } else if (h.status != 200) {
                        var num = 10000,
                            bv = setInterval(function() {
                                num = num - 1000;
                                gg("Trying Again In " + num / 1000 + "sec(s)...<u onclick='sendImg(" + myFile + ")'>Try Now</u>");
                            }, 1000)
                        setTimeout(function() {
                            clearInterval(bv);
                            gg("reconnecting...");
                            sendImg(myFile);
                        }, 10000)
                    }
                };
                h.send(formData);
            }
            return false;
        }


        function sendVid(myFile /*the file div*/ ) {
            // Create a FormData object
            var formData = new FormData(),
                col, val;
            clearInterval(bv);
            if (!myFile.type.match('video.*')) {
                gg('The file selected is not an accepted video.');

            } else if (myFile.size > 104857600) {
                gg("You Have Exceeded The Maximum File Limit Of 100mb");
            } else {

                formData.append('file', myFile, myFile.name);

                var h;

                if (window.XMLHttpRequest) {
                    h = new XMLHttpRequest();
                } else {
                    h = new ActiveXObject("Microsoft.XMLHTTP");
                };

                h.open('POST', 'uplv.php', true);

                h.upload.addEventListener('progress', function(q) {
                    col = (q.loaded / q.total) * 100;
                    $('.lloader').fadeIn(100);
                    val = col / 100;
                    gg("Uploaded... " + col.toFixed(2) + "%");
                    $('.minidl').css('width', col + '%');
                    if (val == 1) {
                        $('.lloader').fadeOut(300)
                    }
                });

                h.onreadystatechange = function() {
                    if (h.status == 200 && h.readyState == 4) {
                        if (h.responseText == "done") {
                            gg("Upload Complete");
                            begin.end(true);
                            refresh("vcontent");
                        } else {
                            gg(h.responseText);
                        }
                    } else if (h.status != 200) {
                        var num = 10000,
                            bv = setInterval(function() {
                                num = num - 1000;
                                gg("Trying Again In " + num / 1000 + "sec(s)...<u onclick='senVid(" + myFile + ")'>Try Now</u>");

                            }, 1000)
                        setTimeout(function() {
                            clearInterval(bv);
                            gg("reconnecting...");
                            sendVid(myFile);
                        }, 10000)
                    }
                };

                begin.start();
                h.send(formData);
            }
        }

        function conB(v, b) {
            var ee;
            if (window.XMLHttpRequest) {
                ee = new XMLHttpRequest();
            } else {
                ee = new ActiveXObject("Microsoft.XMLHTTP");
            };
            ee.open("POST", 'dash.php', true);
            ee.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
            ee.onreadystatechange = function() {
                if (ee.status == 200 && ee.readyState == 4) {
                    $('.updaterp').removeClass('show');
                    if (typeof b == "function") {
                        if (ee.responseText == "unauth") {
                            window.location = "login";
                        } else {
                            b(ee.responseText);
                        }
                    };
                } else {
                    //gg('reconnecting...')
                }
            }
            $('.updater').text('Loading...');
            $('.updaterp').addClass('show');
            ee.send(v);
            return true;
        };

        function changeSides(t, c) { //this is for the switch feature(off and on)
            var ec = document.querySelector(c);
            if (ec.style.left == "-7%") {
                $(ec).css({
                    'left': '51.3%'
                }, 300);
                $(t).css('background', 'var(--main)');
                $(ec).attr('checked', '')
            } else {
                $(ec).animate({
                    'left': '-7%'
                }, 300);
                $(ec).removeAttr('checked');
                $(t).css('background', 'var(--border)');
            }
        };

        function can(g) {
            for (i = 0; i < g.length; i++) {
                if ($(g[i]).val().length != 0 || $(g[i]).val() != "") {
                    $(g[i]).val("");
                }
            }
            $('.save_x').fadeOut(200)
        }

        $(document).ready(function() {
            $('.textbio').on('keydown keyup keypress click blur change', function() {
                var len = $(this).val();
                if (len.length > 500) {
                    $(this).siblings('.label-input100').text('Biography(500/500)');
                    var main = len.substr(0, 500);
                    $(this).val(main);
                } else {
                    $(this).siblings('.label-input100').text('Biography(' + len.length + '/500)');
                }
            })
        })

        function vv(d, l) {
            if ($(d).attr('checked')) {
                $(l).slideDown(200);

            } else {
                $(l).slideUp(200);
            }
        }

        function cc(c) {
            if ($(c).attr('checked')) {
                conB('what=locktime&t=10mins');
                $('.lock_con').val('10mins');
            } else {
                conB('what=locktime&t=off');
            }
        }


        var tab = "";

        function activity(a) {
            conB("what=act&act=" + a, function(s) {
                /* if (s.responseText == "done") {
                     
                 }*/
            })
        }

        function doStuff(t, e) {
            tab = $(t).parent().parent().parent().attr('id');
            switch (e) {
                case "trash":

                    conB("what=trash&art=" + tab, function(s) {
                        if (s == "done") {
                            gg("Article Has Been Moved To Trash");

                            refresh('dcontent');
                            //activity("Moved An Article To Trash");
                        } else {
                            gg(s);
                        }
                    })
                    break;
                case "edit":
                    window.open("editor?t=" + tab, "_blank")
                    break;
                case "aedit":
                    var tt = $(t).parent().parent().parent(),
                        ee = $(tt).position().top,
                        ef = $(tt).position().left;
                    $('.save_x').css('left', ef);
                    $('.save_x').css('top', ee);
                    $('.save_x').fadeIn(200);
                    $('.save_x').attr('edit', tab);
                    break;
                case "publish":
                    conB("what=publish&id=" + tab, function(c) {
                        if (c == "done") {
                            gg("Pending To Be Published!!!");
                            refresh('dcontent');
                        } else {
                            gg(c);
                        }
                    })
                    break;
                case "editH":
                    var tt = $(t).parent().parent().parent(),
                        ee = $(tt).position().top,
                        ef = $(tt).position().left;
                    $('.save_x').css('left', ef);
                    $('.save_x').css('top', ee);
                    $('.save_x').fadeIn(200);
                    $('.save_x').attr('edit', tab);
                    conB('what=editx&tab=' + tab, function(x) {
                        if (x.indexOf(',') >= 0) {
                            $('._name_').val(x.substring(0, x.indexOf(',')));

                            for (v = 1; v <= $('._cate_').children('option').length; v++) {

                                if ($('._cate_').children('option:nth-child(' + v + ')').text().toUpperCase() == x.substring(x.indexOf(',') + 1).toUpperCase()) {

                                    document.querySelector('._cate_').selectedIndex = v - 1;
                                }
                            }
                        } else {
                            gg(x);
                        }
                    })
                    break;
                case "stat":
                    conB("what=numbers&id=" + tab, function(c) {

                        document.querySelector('.mainB').scroll(0, 0)
                        $('.stat_fr span').text(c.substring(0, c.indexOf('~~')));

                        $('.tnumbers').html(c.substring(c.indexOf('~~') + 2));

                    });
                    break;
                case "restore":
                    var ee = $(t).parent().parent().attr('id');
                    conB("what=restore&art=" + ee, function(s) {
                        refresh('tcontent');
                        gg(s);
                    })
                    break;
                case "copy":
                    tab = $(t).parent().parent();
                    $('.stuffi').val($(tab).attr('id')).select();
                    document.execCommand('copy');
                    gg("Link Copied");
                    break;
                case "draft":
                    tab = $(t).parent().parent().attr('id');

                    conB("what=draft&art=" + tab, function(s) {
                        if (s == "done") {
                            refresh("pcontent");
                            gg("Your Article Has Been Moved To Draft");
                        } else {
                            gg(s)
                        }
                    })
                    break;
                case "delete":
                    tab = $(t).parent().parent();
                    var ee = $(tab).position().top,
                        ef = $(tab).position().left;
                    $('.save_x').css('left', ef);
                    $('.save_x').css('top', ee);
                    $('.video_player').addClass('overlay_show');
                    $('.save_x').fadeIn(200);
                    $('.save_x').attr('edit', $(tab).attr('id'));
                    break;
            }
        };

        function selectC(p) {
            var main = $(p).val();
            if (main == "all" || main == "lifestyle" || main == "culture") {
                $(p).siblings().remove();
            } else if (main != "all" && main != "0") {
                var v = "<div onclick='addB(this)'>" + main + "</div>",
                    dd = $(p).children().length;
                $(p).before(v);
                for (g = 1; g <= dd; g++) {
                    if (main == $(p).children("option:nth-child(" + g + ")").text()) {
                        $(p).children("option:nth-child(" + g + ")").remove();
                    }
                }
                $(p).val("0");
            }
        }

        function addB(vb) {
            $(vb).siblings('select').children('option:last-child').after('<option>' + $(vb).text() + '</option>');
            $(vb).remove();
        }
    </script>
    <style>
        @import "files/css/main.css";

        body,
        html {
            margin: 0px;
            height: 100%;
            min-width: 300px;
            min-height: 500px;
            font-family: Poppins;
            width: 100%;
            padding: 0px;
            background-color: #fff;

        }

        .mainHeader {
            display: block;
            height: 64px;
            width: 100%;
            padding: 8px;
            display: flex;
            align-items: center;
        }

        .mbody {
            display: flex;
            width: 100%;
            height: calc(100% - 64px);
            position: relative;
            align-items: center;
        }

        .mbody>* {
            position: relative;
        }

        .sidebar {
            width: 300px;
            overflow: hidden;
            background-color: #fff;

        }

        .mainB,
        .sidebar,
        .settings {
            height: 100%;
        }

        .settings.close {
            width: 0px;
            overflow: hidden;
        }

        .settings {
            width: 430px;
            border-color: rgb(236, 239, 241);
        }

        .sideC {
            color: rgb(236, 239, 241);

        }

        .sett_head h2 {
            margin: 0px;
            color: rgb(20, 21, 24);

        }

        .sett_head {
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .sett_content {
            height: calc(100% - 55px);
            background-color: #fff;

            overflow-y: scroll;
        }

        .sett_content>div {
            padding: 10px 12px;
        }

        .theme_sett>div {
            display: flex;
            align-items: center;
        }

        .theme_sett>div {
            width: 100%;
            padding: 5px 0px;
            overflow-y: hidden;
        }

        .theme_sett {
            display: none;
            display: var(--block);
        }

        .theme_sett span {
            text-transform: uppercase;
            font-weight: 500;

        }

        .empty img {
            opacity: .8;
        }

        .theme_sett>div div {
            height: 50px;
            width: 50px;
            margin: 0px 4px;
            border-radius: 3px;
            border-radius: 50%;
            border: 1px solid;
            border-color: rgb(236, 239, 241);
            border-color: rgb(236, 239, 241);
        }

        .theme_sett>div div.selected {
            border-color: #5995fd;
            border: 2px solid;
        }

        .theme_sett>div div:hover {
            border: 2px solid;
            border-color: rgb(236, 239, 241);
        }

        .sett_content>div,
        .mainHeader,
        .sett_head {
            border-bottom: 1px solid;
            border-color: rgb(236, 239, 241);
        }

        .sett_head i {
            font-size: 20px !important;
        }

        .getimg {
            display: none !important;
            visibility: none;
            opacity: 0 !important;
        }

        .mainHeader>div {
            height: 100%;
            margin: 0px 4px;
        }

        .mbody .main {
            width: 100%;
        }

        .mainB {
            overflow-y: scroll;
            width: 0px;
        }

        .llogo {
            width: 170px;
            margin: 6px 3pc 6px 8px !important;
            background-size: 100%;
        }

        .user,
        .llogo {
            background-repeat: no-repeat;
            background-position: center;
        }

        .user {
            background-image: url('files/css/avatar.png');
            width: 56px;
            background-size: cover;
            height: 100%;
            border-radius: 50%;
        }

        .pointer {
            cursor: pointer;
        }

        .circle {
            position: absolute;
            z-index: 101;
            height: 96px;
            width: 96px;
            border-radius: 50%;
            opacity: 0.99;
            transition-delay: .4s;
            overflow: hidden;
            background-repeat: no-repeat;
            background-position: center;
            top: -2px;
            background: #e0e0e0;
            height: 23px;
            width: 25px !important;
            border-radius: 50%;
        }

        .toggleBackground {
            background: rgb(236, 239, 241);
            background: var(--border);
            width: 50px !important;
            height: 19px;
            border-radius: 1rem;
            margin-left: 9px;
            display: inline-block;
        }

        i {
            font-size: 22px !important;
            color: #777;

        }

        .fa-search:hover {
            color: #777;
            color: var(--text2) !important;
        }

        i:hover {
            color: #5995fd;
        }

        .user_settings {
            padding: 10px;
            width: 100%;
            color: #777;

        }

        .user_settings .sideu_sett {
            display: flex;
            align-items: start;
        }

        .user_settings>* {
            margin: 10px;
            cursor: default;
        }

        .sideu_sett>div:first-child {
            margin-right: 1.5pc;
            width: 100%;
        }

        .edit>button {
            border-radius: 4rem;
        }

        .sideu_sett>div:nth-child(2) {
            margin-left: 1.5pc;
        }

        .user_settings>div {
            width: calc(100% - 10px);
        }

        .lock_con {
            display: none;
        }

        .profile_min .profile_pic {
            width: 299px;
            height: 200px;
            display: flex;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            background-color: rgb(224, 224, 224);

        }

        .button_sett button:nth-child(2) {
            width: 80px;
            margin-left: 3px;
        }

        .button_sett button:nth-child(1) {
            width: 100%;
        }

        .profile_min button {
            color: rgb(20, 21, 24);
            border: 2px solid rgb(20, 21, 24);
            border-radius: 3px;
            padding: 5px;
            margin: .2pc 0px;
        }

        .profile_min button:hover {
            color: #fff;
            background-color: rgb(20, 21, 24);
            background-color: var(--layerb);
            color: var(--text1);
        }

        .user_settings .bottom,
        .pass_settings .bottom {
            padding: 20px 10px;
            display: flex;
            justify-content: right;
            border-top: 1px solid rgb(236, 239, 241);
            border-top: 1px solid var(--border);
        }

        .user_settings .bottom input,
        .pass_settings .bottom input {
            padding: 9px;
            width: 80px;
            border-radius: 4px;
            margin: 0px 10px;
            border: 2px solid #777;

            font-weight: bold;
        }

        .confirm_set {
            background-color:
                #5995fd;
            padding: 20px;
            font-weight: bolder;
            text-align: center;
        }

        .blist {
            display: flex;
            align-items: start;
            flex-wrap: wrap;
            padding: 0px 10px;
        }

        .user_settings .bottom input,
        .pass_settings .bottom input {
            color: #fff;
            background-color: #5995fd;
        }

        .embed_video {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pass_settings,
        .user_settings {
            margin: 15px 0px;
        }

        .pass_settings,
        .user_settings,
        .confirm_set {
            display: none;
        }

        .search input {
            padding-left: 2.4pc;
            padding-right: 5px;
        }

        .changeM,
        .search form,
        .search input {
            border-radius: 4px;
        }

        .changeM {
            height: 40px;
            font-size: 15px;
            background-color: transparent;
            width: 95px;
            float: right;
        }

        .search form,
        .search input {
            height: 100%;
            width: 100%;
            font-size: 18px;
            background-color: #eaeaea;
            color: #777;


        }

        .viewChart {
            color: #777;

            margin: 15px;
        }

        .viewChart,
        .search {
            display: flex;
            align-items: center;
        }

        .search {
            width: 60%;
            border-radius: 4px;
            background-color: var(--lightgray);
        }

        .search input,
        .search input:focus,
        .search input:hover {
            outline: none;
            border: none;
        }

        .search>div:nth-child(2n+1) {
            width: 24px;
            margin: 0px 8px;
            text-align: center;
            position: absolute;
        }

        .search i {
            z-index: 10;
        }

        .animation {
            transition: all .5s;
            -moz-transition: all .5s;
            -webkit-transition: all .5s;
            -ms-transition: all .5s;
            -o-transition: all .5s;
        }

        .mainHeader .right {
            width: 20%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .mainHeader .right>* {
            margin: 0px 6px;
        }

        .search>div:first-child,
        .hamb,
        .help,
        .sett {
            height: auto !important;
        }

        .sidebar .out {
            position: absolute;
            bottom: 0px;
            overflow: hidden;
            border-top: 1px solid rgb(236, 239, 241);
            border-top: 1px solid var(--border);
            width: 100%;
            padding: 16px 0px;
        }

        .sidebar>div>div {
            min-width: 150px;
            overflow: hidden;
            padding: 10px;
            border-radius: 0px 4rem 4rem 0%;
            color: #777;

        }

        .sidebar>div>div i {
            vertical-align: sub;
            margin-right: 25px;
        }

        .sidebar>div>div:hover {
            background-color: rgba(244, 244, 244);

        }

        .sidebar>div>div.view {
            font-weight: 500;
            margin: 0px;
            color: #5995fd;
            background-color: #f5f5f5;
            color: #5995fd;

        }

        .updater u {
            cursor: pointer;
        }

        .sidebar.close {
            width: 40px;
            margin-right: 8px;
        }

        .close button {
            box-shadow: none;
        }

        .sidebar button i {
            vertical-align: text-bottom;
            margin-right: 1.5pc;
            color: #5995fd;
        }

        .min_addicle {
            width: 100%;
            padding: 10px 6px;
            margin: 15px 0px;
        }

        .sidebar.close .add_article,
        .min_addicle {
            display: none;
        }

        .sidebar.close .min_addicle {
            display: block;
        }

        .sidebar button {
            text-align: left;
            font-size: 15px;
            font-weight: 700;
            background: transparent;
            border: 2px solid rgb(236, 239, 241);
            border: 2px solid var(--border);
            width: 98%;
            border-radius: 4rem;
            color: #777;

            box-shadow: 0px 5px 5px rgba(0, 0, 0, .1);
        }

        .add_article {
            padding: 10px 15px;
            width: 200px;
            margin: 15px 3px;
        }

        .overlay_show {
            display: none !important;
        }

        .overlay_lock {
            background-image: url('../sls/backpic.png');
            background-blend-mode: overlay;
            background-size: 30%;
            background-color: rgba(255, 255, 255, .7);
        }

        .overlay_lock,
        .overlay_show {
            bottom: 0;
            left: 0;
            display: flex;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 100000;
            flex: 1;
            -ms-flex-preferred-size: auto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 1.3;
            min-width: 0;
            text-align: left;
            text-transform: none;
            border: 0;
            float: none;
            height: auto;
            margin: 0;
            max-width: none;
            outline: 0;
            padding: 0;
        }

        .overlay {
            background-color: rgba(255, 255, 255, .75);
            background-color: var(--layer4) !important;
            bottom: 0;
            left: 0;
            display: flex;
            position: absolute;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            right: 0;
            top: 0;
            z-index: 10000;
            background: 0 0;
            color: rgba(34, 47, 62, .96);

            flex: 1;
            -ms-flex-preferred-size: auto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 1.3;
            min-width: 0;
            text-align: left;
            text-transform: none;
            border: 0;
            float: none;
            height: auto;
            margin: 0;
            max-width: none;
            outline: 0;
            padding: 0;
        }

        .up {
            padding: 16px 16px;
        }

        .save_x,
        .main_viewer {
            box-sizing: border-box;
            -ms-flex-preferred-size: auto;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            background-color: #fff;
            border-color: rgb(224, 224, 224);

            border-color: var(--text3);
            border-radius: 3px;
            border-style: solid;
            height: fit-content;
            margin: auto;
            border-width: 1px;
            box-shadow: 0 16px 16px -10px rgba(34, 47, 62, .15), 0 0 40px 1px rgba(34, 47, 62, .15);
            display: flex;
            flex-direction: column;
            max-height: 100%;
            max-width: 480px;
            position: relative;
            width: 95vw;
        }

        .save_x {
            position: absolute;
            display: none;
            z-index: 100;
        }

        .main_viewer {
            z-index: 2;
            width: 95%;
            max-width: unset;
            height: 90%;
        }

        .topb span {
            text-transform: capitalize;
            padding: 0px 8px;
        }

        .topb {
            display: flex;
            justify-content: space-between;
            padding: 0px 15px;
        }

        .main_viewer>div {
            margin: 10px 0px;
        }

        .overlay input,
        .overlay select,
        .save_x input,
        .save_x select {
            -webkit-appearance: none;
            background-color: #fff;
            border-color: rgb(224, 224, 224);
            -moz-appearance: none;
            appearance: none;

            border-color: var(--text3);
            border-radius: 5px;
            border-style: solid;
            font-family: inherit;
            border-width: 1px;
            box-shadow: none;
            box-sizing: border-box;
            color: rgba(34, 47, 62, .96);

            font-size: 16px;
            line-height: 24px;
            margin: 0;
            min-height: 34px;
            outline: 0;
            padding: 5px 5.25px;
            resize: none;
            width: 100%;
        }

        .up select {
            cursor: pointer;
        }

        .overlay label,
        .save_x label {
            color: rgba(34, 47, 62, .7);

            display: block;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 1.3;
            font-family: inherit;
            padding: 10px 8px 10px 0;
            text-transform: none;
            white-space: nowrap;
        }

        .bottom {
            align-items: center;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: center;
            padding: 8px 16px;
        }

        .bottom button {
            background-color: #5995fd;
            background-image: none;
            margin: 0px 8px;
            background-position: 0 0;
            background-repeat: repeat;
            border-color: #5995fd;
            border-radius: 5px;
            border-style: solid;
            border-width: 1px;
            box-shadow: none;
            box-sizing: border-box;
            color: #fff;
            color: var(--btext);
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-family: inherit;
            font-style: normal;
            font-weight: 700;
            letter-spacing: normal;
            line-height: 24px;
            outline: 0;
            padding: 4px 16px;
            text-align: center;
            text-decoration: none;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .stat_fr,
        .edit_y,
        .stat_fe {
            color: #777;

            margin: auto 10px;
        }

        .pass_settings .edit_y {
            margin: 15px 0px;
        }

        .article {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            border-bottom: rgb(236, 239, 241);
            border-bottom: 1px solid var(--border);
            color: #777;

            height: auto;
            font-size: 18px;
            padding: 12px 7px;
        }

        .article.bold * {
            font-weight: 700;
        }

        .article * {
            margin: 0px 5px;
        }

        .stuffi {
            display: block !important;
            position: absolute !important;
            opacity: 0 !important;
            z-index: -1 !important;
            border: none !important;
            outline: none !important;
        }

        .add_it i {
            color: #fff;
            color: var(--text1);
            font-weight: bolder;
        }

        .add_it {
            height: 60px;
            background: #5995fd;
            width: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--main);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            opacity: var(--opacity);
            cursor: pointer;
            right: 26px;
            bottom: 7px;
            position: fixed;
        }

        .article span {
            display: block;
        }

        .article i {
            color: #777;

            font-size: 22px;
        }

        .istay i,
        .article i:hover {
            color: #5995fd;
        }

        .title_s {
            width: 90%;
        }

        .cate_s {
            width: 50%;
        }


        .checkbox {
            height: 20px;
            width: 34px;
            border-radius: 3px;
            border: 2px solid;
            border-color: rgb(224, 224, 224);
            border-color: var(--text3);
        }

        .checked:after {
            content: "\f00c";
            font-family: FontAwesome;
            font-size: 15px;
            height: 16px;
            width: 16px;
            color: #5995fd;
            position: absolute;
            bottom: 0px;
            top: 0px;
            right: 0px;
            left: 0px;
            margin: auto;
        }

        .more_bar {
            position: absolute;
            right: 5px;
            top: 80%;
            z-index: -1;
            opacity: 0;
            border-radius: 3px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
            width: 164px;
            padding: 10px 0px;
            height: fit-content;
            background-color: #fff;
            background-color: var(--whited);
        }

        .more_bar_show {
            top: 45px;
            z-index: 10;
            opacity: 1;
        }

        .more_bar div {
            padding: 7px 5px;
            color: #777;

            font-size: 15px;
            text-transform: capitalize;
            border-radius: 3rem;
        }

        .rcontent {
            width: 100%;
            align-items: start;
            overflow-x: scroll;
            overflow-y: hidden;
            flex-wrap: nowrap;
            flex-flow: row;
            margin-bottom: 3pc;
        }

        .article_2 {
            display: flex;
            padding: 10px;
            min-width: 300px;
            max-width: 300px;
            position: relative;
            cursor: pointer;
            margin: 10px 0px 10px 10px;
            flex-direction: column;
            border-radius: 3px;
            border: 1px solid rgb(236, 239, 241);
            border: 1px solid var(--border);
            color: #777;

        }

        .empty {
            align-items: center;
            height: 100%;
            justify-content: center;
            flex-direction: column;
        }

        .empty * {
            margin: 1.1pc 0px;
        }

        .article_2>* {
            margin: 5px 0px;
        }


        .article_2>div:first-child {
            display: flex;
        }

        .article_2:hover {
            background-color: rgb(224, 224, 224);
            background-color: var(--hover);
        }

        .article_2.ggg {
            background-color: rgb(236, 239, 241);
            background-color: var(--hover2);
        }

        .more_bar i {
            margin-right: 3px;
            color: #5995fd;
            vertical-align: sub;
        }

        .more_bar div:hover {
            background-color: var(--whitee);
        }

        .sidebar button:hover {
            border: 2px solid #5995fd;
            border: 2px solid var(--main);
        }

        .notifics:before {
            height: 22px;
            color: #fff;
            background-color: #5995fd;
            width: 22px;
            text-align: center;
            border-radius: 50%;
            position: absolute;
            content: attr(val);
            z-index: 9;
            left: 9px;
            bottom: 12px;
            padding: 2px;
        }

        .updaterp.show {
            top: -6px;
            z-index: 100000;
            opacity: 1;
        }

        .updaterp {
            position: absolute;
            display: flex;
            opacity: 0;
            z-index: -1;
            justify-content: center;
            top: -10px;
            width: 100%;
            z-index: 100000;
        }

        @media only screen and (max-width: 860px) {
            .user_settings .sideu_sett {
                flex-direction: column-reverse;
                align-items: center;
            }

            .user_settings .sideu_sett>div {
                margin: 16px auto;
            }
        }

        @media only screen and (max-width: 740px) {
            .close.minx.sidebar {
                width: 230px !important;
            }

            .sidebar.close .min_addicle {
                display: none !important;
            }

            .sidebar.close .add_article,
            .min_addicle {
                display: block !important;
            }

            .minx.sidebar {
                width: 0px;
            }

            .sidebar {
                position: absolute;
                z-index: 100;
                max-width: 230px;
            }
        }

        @media only screen and (max-width: 662px) {
            .llogo {
                width: 80px;
                margin: 6px 9px 6px 8px !important;
            }
        }

        @media only screen and (max-width: 652px) {

            .stat_fr,
            .changeM {
                display: inline-block;
                float: none;
                vertical-align: middle;
            }

            .tnumbers>div {
                width: 100%;
            }
        }

        @media only screen and (max-width: 415px) {
            .blist {
                padding: 0px;
            }

            .llogo {
                background-size: contain;
                width: 19px;
            }

            .user {
                display: none;
                background-image: unset;
            }

            .mainHeader .right {
                width: auto;
            }

            .search {
                width: 80%;
            }
        }

        .updater,
        .updaterp {
            transition: all .8s;
            -o-transition: all .8s;
            -moz-transition: all .8s;
            -webkit-transition: all .8s;
            -ms-transition: all .8s;
        }

        .updater {
            color: #fff;
            background-color: #5995fd;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
            padding: 10px;
            position: fixed;
            margin: auto;
        }

        .lockscreen {
            width: 299px;
            border-radius: 10px;
            background-color: #fff;

            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            height: min-content;
            margin: auto;
        }

        .lockscreen .container-login100-form-btn {
            justify-content: center;
        }

        .videoF {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .lockscreen .wrap-input100 {
            margin-bottom: 10px;
        }

        .video_player .main_viewer {
            height: auto;
            width: auto;

        }

        .lockscreen h2 {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 50px;
            background-color: #5995fd;
            color: #fff;
            font-weight: 700;
            text-align: center;
        }

        .article_2 .high {
            color: #fff;
            background-color: #5995fd;
        }

        .lockscreen>div {
            padding: 10px
        }
    </style>
</head>

<body>

    <noscript>
        <div class="noscript">
            <h2>Javascript Disabled</h2>
            <span>It seems like your Internet Browsers Javascript feature has been disabled in order to access our site please enable javascript as this is important for the proper functioning of our site, you can disable your browsers javascript feature when you are done with our site.</span>
        </div>
    </noscript>
    <div class="updaterp">
        <div class="updater">saving...</div>
    </div>
    <div class="overlay_lock overlay_show">
        <form class="lockscreen" onsubmit="return false" action="#" method="POST" enctype="multipart/form-data">
            <h2>Screenlock</h2>
            <div>
                <div class="wrap-input100 validate-input alert-validate" data-validate="Your Password is required">
                    <span class="label-input100">Password <i class="fa fa-eye view" onclick="show_pass(this,$(this).parent().siblings('.input100'))"></i></span>
                    <input class="input100 pass_lock" type="password" name="pass_lock" placeholder="********">
                    <span class="focus-input100"></span>
                </div>
            </div>
            <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn">
                    <input type="submit" value="Unlock" class="login100-form-btn">
                </div>
            </div>
        </form>
    </div>
    <div class="overlay viewme overlay_show">
        <div class="main_viewer">
            <div class="topb"><i class="ti-close pointer" onclick="$(this).parent().parent().parent().addClass('overlay_show')"></i><span>Nothing Here</span><i onclick="$(this).parent().toggleClass('istay'),$(this).siblings('.more_bar').toggleClass('more_bar_show')" class="pointer ti-more"></i>

                <i onclick="$(this).parent().parent().parent().addClass('overlay_show'),doStuff(this,'restore')" class="more pointer ti-reload" title="restore"></i>

                <i onclick="$(this).parent().parent().parent().addClass('overlay_show'),doStuff(this,'draft')" class="more pointer ti-pencil-alt" title="Back To Draft"></i>

                <div onclick="$(this).parent().parent().parent().addClass('overlay_show'),$(this).removeClass('more_bar_show')" class="more_bar animation">
                    <div class="animation pointer" onclick="doStuff(this,'edit')"><i class="ti-write"></i>Edit Article</div>
                    <div class="animation pointer" onclick="doStuff(this,'publish')"><i class="ti-agenda"></i>Publish</div>
                    <div class="animation pointer" onclick="doStuff(this,'trash')"><i class="ti-trash"></i>Trash</div>
                </div>
            </div>
            <div class="a_content"></div>
        </div>
    </div>

    <div class="overlay overlay_show video_player">
        <div class="main_viewer">
            <div class="topb"><i class="ti-close pointer" onclick="ccv(this)"></i><span></span><i onclick="doStuff(this,'copy')" class="pointer fa fa-clone"></i>
            </div>
            <input type="text" class="stuffi">
            <div class="embed_video">
                <video id="vid1" class="video-js vjs-default-skin">
                    <p class="vjs-no-js">

                    </p>
                </video>
            </div>
        </div>
    </div>
    <header class="mainHeader">
        <div class="hamb pointer" onclick="$('.sidebar').toggleClass('close')"><i class="fa fa-bars animation"></i></div>
        <div class="llogo pointer"></div>
        <div class="search">
            <div>
                <i class="fa fa-search"></i>
            </div>
            <form method="POST" onsubmit="return false" action="#" enctype="multipart/form-data"><input type="search" class="search_box" placeholder="Search Articles"></form>

        </div>
        <div class="right">
            <!--<div class="help animation pointer">
<i class="ti-help"></i>
    </div>-->
            <div onclick="$('.settings').toggleClass('close'),$('.settings').removeClass('main');$('.mainB').addClass('main');$('.user_settings,.pass_settings').fadeOut(200)" class="sett animation pointer">
                <i class="ti-settings animation"></i>
            </div>
            <div val="0" style="display:none !important;" class="notifics animation pointer">
                <i class="fa fa-bell animation"></i>
            </div>
            <div class="user pointer"></div>
        </div>
    </header>
    <div class="mbody">
        <div class="sidebar animation minx">

            <div>
                <div onclick="viewM(this,'acontent')" class="pointer animation acont view"><i class="fa fa-font"></i>Option</div>
                <div onclick="viewM(this,'dcontent')" class="pointer dcont animation"><i class="ti-file"></i>Option</div>
                <div onclick="viewM(this,'pcontent')" class="pcont pointer animation"><i class="fa fa-spinner"></i>Option</div>
                <div onclick="viewM(this,'vcontent')" class="vcont pointer animation"><i class="ti-control-play"></i>Option</div>
                <div onclick="viewM(this,'tcontent')" class="tcont pointer animation"><i class="ti-trash"></i>Option</div>
                <!--<div class="acont pointer animation"><i class="fa fa-history"></i>Activity</div>-->
            </div>

            <div class="out">
                <div href="out" onclick="window.location = this.getAttribute('href')" class="pointer animation"><i class="fa fa-power-off"></i>Sign Out</div>
            </div>
        </div>
        <style type="text/css">
            .metrics {
                border: 1px solid rgb(236, 239, 241);
                border-radius: 6px;
                border: 1px solid var(--border);
                padding: 10px;
                margin: 5px 15px;
                background-color: transparent;
            }

            .ill {
                color: #5995fd;
                font-size: 40px !important;
            }

            .theme_sett>div div {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .tnumbers {
                border: none;
                flex-wrap: wrap;
                display: flex;
                align-items: center;
            }

            .up h3,
            .up h2 {
                color: #777;

            }

            .tnumbers>div {
                border-radius: 3px;
                box-shadow: 0px 3px 6px rgba(0, 0, 0, .1);
                border: 1px solid rgb(236, 239, 241);
                margin: 3px;
                padding: 10px;
                border: 1px solid var(--border);
            }

            .tnumbers>div>div {
                margin-bottom: 6px;
            }

            .tnumbers>div span {
                text-align: right;
                display: block;
                color: rgb(20, 21, 24);

            }

            .metrics i {
                font-size: 20px;
                color: inherit;
                padding: 0px 10px;
            }

            .charts {
                height: 600px;
                display: none;
            }

            .button_sett {
                display: flex;
            }

            .a_content {
                padding: 10px;
                word-break: break-all;
            }
        </style>
        <?php
        if (isset($_GET['t'])) {
            echo "<script>
            window.location = 'editor?t=" . $_GET['t'] . "';
    </script>";
        }
        ?>

        <div class="mainB main animation">
        </div>
        <div class="settings close animation">
            <div class="sett_head">
                <h2>Settings</h2>
                <i class="ti-close pointer animation" onclick="$('.settings').addClass('close');$('.settings').removeClass('main');$('.mainB').addClass('main');$('.user_settings,.pass_settings').fadeOut(200)"></i>
            </div>
            <div class="sett_content">
                <div class="screen_lock" style="display:none;">
                    <span>Enable Screenlock <div class="toggleBackground pointer animation" style="vertical-align:middle;" onclick="changeSides(this,'.sceenl'),cc($(this).children('.circle')),vv($(this).children('.circle'),'.lock_con')">
                            <div class="sceenl circle pointer" style="<?php
                                                                        /*if (isset($gg)) {
    if ($gg['autolock'] != "off") {
echo "left:51.3%;";
    } else {
echo 'left:-7%;';
    }
} else {
    echo 'left:-7%;';
}*/
                                                                        ?>"></div>
                        </div></span>
                    <div <?php /*if (isset($gg)) {
if ($gg['autolock'] != "off") {
    echo "style='display:block;'";
} else {
    echo "style='display:none;'";
}
    } else {
echo "style='display:none;'";
    }*/ ?> class="lock_con">
                        <div class="wrap-input100 validate-input">
                            <span style="margin-bottom:10px;display:block;" class="label-input100">Lock After </span>
                            <select class="timeout pointer input100 has-val" onchange="conB('what=locktime&t='+this.value)">
                                <option>5mins</option>
                                <option>10mins</option>
                                <option>15mins</option>
                                <option>25mins</option>
                                <option>40mins</option>
                                <option>50mins</option>
                                <option>1hr</option>
                                <option>2hrs</option>
                                <option>4hrs</option>
                                <option>5hrs</option>
                            </select>
                            <span class="focus-input100"></span>
                            <span class="label-input100">Of Inactivity</span>
                        </div>
                    </div>
                </div>
                <script>
                    function llc(x, v) {
                        if ($('.settings').hasClass('main')) {
                            $(x).fadeToggle(200);
                            if ($(v).css('display') != "block") {
                                $('.settings').toggleClass('main');
                                $('.mainB').toggleClass('main');
                            }
                        } else {
                            $('.settings').toggleClass('main');
                            $('.mainB').toggleClass('main');
                            $(x).fadeToggle(200)
                        }
                    }
                </script>
                <div class="edit editPro">
                    <button onclick="llc('.user_settings','.pass_settings')" class="login100-form-btn">Edit Profile</button>
                    <form method="POST" enctype="multipart/form-data" class="user_settings">
                        <h2 class="edit_y">Edit Your Profile</h2>
                        <div class="error_note"></div>
                        <div class="sideu_sett">
                            <div>
                                <div class="wrap-input100 validate-input alert-validate" data-validate="Full Name Or Username is required">
                                    <span class="label-input100">Full Name or Username</span>
                                    <input <?php echo "value=" . $_SESSION['author']; ?> class="input100" type="text" name="name" placeholder="Name...">
                                    <span class="focus-input100"></span>
                                </div>

                                <div class="wrap-input100 validate-input alert-validate" data-validate="Valid email is required: ex@abc.xyz">
                                    <span class="label-input100">Email</span>
                                    <input class="input100" type="text" name="email" <?php echo "value=" . $_SESSION['username']; ?> placeholder="Email address...">
                                    <span class="focus-input100"></span>
                                </div>
                                <div class="wrap-input100 validate-input alert-validate" data-validate="Biography of 150min and 500max characters is Required">
                                    <span class="label-input100">Biography (0/500)</span>
                                    <textarea class="input100 textbio" name="bio" placeholder="Tell Us A Little Bit About You"><?php echo (isset($xc['biography'])) ? $xc['biography'] : ""; ?></textarea>
                                    <span class="focus-input100"></span>
                                </div>
                                <div class="wrap-input100 validate-input">
                                    <span class="label-input100">Category</span>
                                    <div class="_cates_">
                                        <?php
                                        echo isset($vars) ? $vars : "";
                                        ?>
                                        <select class="input100 has-val" onchange="selectC(this)" name="select">
                                            <option <?php echo isset($vars) ? "selected=''" : ""; ?> value="0">Select Categories...</option>
                                            <option value="all">All Categories</option>
                                            <option value="lifestyle">All Lifestyle</option>
                                            <option value="culture">All Culture</option>
                                            <option parent="culture">Art</option>
                                            <option parent="lifestyle">Adulting Hacks</option>
                                            <option>Interviews</option>
                                            <option parent="lifestyle">Food & Drinks</option>
                                            <option>Features</option>
                                            <option parent="lifestyle">Health & Wellness</option>
                                            <option>Events</option>
                                            <option parent="culture">Photography</option>
                                            <option parent="lifestyle">Money, Business & Career</option>
                                            <option>Fashion</option>
                                            <option parent="lifestyle">Travel</option>
                                            <option parent="culture">Sports</option>
                                            <option>Love, Sex & Relationships</option>
                                            <option parent="culture">Music</option>
                                        </select><span class="focus-input100"></span>
                                    </div>
                                </div>


                            </div>
                            <div>
                                <div class="profile_min">
                                    <span class="label-input100">Profile Photo</span>
                                    <div class="profile_pic" style="<?php echo isset($fprof) ? "background-image:url('$fprof');" : ""; ?>"></div>
                                    <input type="file" class="getimg" onchange="sendImg(this.files[0])">
                                    <div class="button_sett">
                                        <button onclick="document.querySelector('.getimg').click();return false" class="animation pointer set_pic">Upload An Image</button><button style="display:none;" class="animation pointer set_ppic">Done</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom">
                            <input type="submit" class="button pointer" value="Save">
                        </div>
                    </form>
                    <h2 class="confirm_set">To Confirm Settings Click On The Link We Sent You In Your Mail</h2>
                </div>
                <div class="edit editPass">
                    <button onclick="llc('.pass_settings','.user_settings')" class="login100-form-btn">Change Password</button>

                    <form method="post" action="#" enctype="multipart/form-data" class="pass_settings">
                        <h2 class="edit_y">Change Your Password</h2>
                        <div class="wrap-input100 validate-input alert-validate" data-validate="Old Password Required">
                            <span class="label-input100">Old Password <i class="fa fa-eye view" onclick=" show_pass(this,$(this).parent().siblings('.input100'))"></i></span>
                            <input class="input100" type="password" name="pass" placeholder="********">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input alert-validate" data-validate="Password with minimum of 8 characters including either a number or special characters">
                            <span class="label-input100">New Password <i class="fa fa-eye view" onclick=" show_pass(this,$(this).parent().siblings('.input100'))"></i></span>
                            <input class="input100 mainpass" type="password" name="pass" placeholder="********">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="wrap-input100 validate-input alert-validate" data-validate="Repeat Password is required">
                            <span class="label-input100">Repeat Password <i class="fa fa-eye view" onclick=" show_pass(this,$(this).parent().siblings('.input100'))"></i></span>
                            <input class="input100" type="password" name="repeat-pass" placeholder="********">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="bottom">
                            <input type="submit" class="button pointer" value="Save">
                        </div>
                    </form>
                    <h2 class="confirm_set">To Confirm Settings Click On The Link We Sent You In Your Mail</h2>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var data1 = [{
            "date": new Date(2018, 0, 1),
            "Facebook": 450,
            "Twitter": 8002,
            "pinterest": 699,
            "Telegram": 504,
            "Whatsapp": 900,
            "Mail": 32,
            "Views": 80
        }, {
            "date": new Date(2018, 0, 2),
            "Facebook": 269,
            "Twitter": 450,
            "pinterest": 841,
            "Telegram": 403,
            "Whatsapp": 402,
            "Mail": 504,
            "Views": 594
        }, {
            "date": new Date(2018, 0, 3),
            "Facebook": 700,
            "Twitter": 358,
            "pinterest": 699,
            "Telegram": 300,
            "Whatsapp": 334,
            "Mail": 103,
            "Views": 403
        }, {
            "date": new Date(2018, 0, 4),
            "Facebook": 490,
            "Twitter": 367,
            "pinterest": 500,
            "Telegram": 432,
            "Whatsapp": 304,
            "Mail": 290,
            "Views": 800
        }, {
            "date": new Date(2018, 0, 5),
            "Facebook": 500,
            "Twitter": 485,
            "pinterest": 369,
            "Telegram": 554,
            "Whatsapp": 240,
            "Mail": 203,
            "Views": 844
        }, {
            "date": new Date(2018, 0, 6),
            "Facebook": 550,
            "Twitter": 354,
            "pinterest": 250,
            "Telegram": 222,
            "Whatsapp": 342,
            "Mail": 392,
            "Views": 302
        }, {
            "date": new Date(2018, 0, 7),
            "Facebook": 420,
            "Twitter": 350,
            "pinterest": 600,
            "Telegram": 300,
            "Whatsapp": 200,
            "Mail": 290,
            "Views": 800
        }];


        /*      if (data1.length) {
          am4core.useTheme(am4themes_animated);
          // Create chart instance
          var chart = am4core.create('charts', am4charts.XYChart);
          chart.data = data1;

          // Create axes
          var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
          dateAxis.renderer.grid.template.location = 0;
          dateAxis.renderer.minGridDistance = 30;

          var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

          // Create series
          function createSeries(field, name, cc) {
  var series = chart.series.push(new am4charts.LineSeries());
  series.dataFields.valueY = field;
  series.dataFields.dateX = "date";
  series.name = name;
  series.tooltipText = "{dateX}: [b]{valueY}[/]";
  series.tooltip.getFillFromObject = false;
  series.tooltip.background.fill = am4core.color(cc);
  series.stroke = am4core.color(cc);
  series.smoothing = "monotoneX";
  series.strokeWidth = 2;
  valueAxis.renderer.labels.template.fill = am4core.color("#999");
  valueAxis.renderer.grid.template.stroke = am4core.color("#999");

  var bullet = series.bullets.push(new am4charts.CircleBullet());
  bullet.circle.stroke = am4core.color("#fff");
  bullet.circle.strokeWidth = 2;
  $('#id-66-title').parent().css('opacity', '0')
          }

          function cs() {
  createSeries("Facebook", "Facebook", "#3b65c2");
  createSeries("Twitter", "Twitter", "rgb(8, 160, 233)");
  createSeries("pinterest", "pinterest", "rgb(189,8,28)");
  createSeries("Telegram", "Telegram", "#0088cc");
  createSeries("Whatsapp", "Whatsapp", "#4fce5d");
  createSeries("Mail", "Mail", "var(--text2)");
  createSeries("Views", "Views", "var(--main)");
          }
          cs();
          chart.cursor = new am4charts.XYCursor();
  }

  function lxl(g) {
          for (i = 1; i <= $(g).children().length; i++) {
  if ($(g).val() == $(g).children('option:nth-child(' + i + ')').text()) {
          $(g).attr("p", $(g).children('option:nth-child(' + i + ')').attr('pr'));
  }
          }
  }*/


        function viewM(d, p) {
            $('.settings').addClass('close');
            $('.settings').removeClass('main');
            $('.mainB').addClass('main');
            $('.user_settings,.pass_settings').fadeOut(200);
            $(d).siblings().removeClass('view')
            $(d).addClass('view');

        }
        var input = $('.user_settings .validate-input .input100'),
            passput = $('.pass_settings .validate-input .input100');
        $('.user_settings').on('submit', function() {
            var check = true;
            for (var i = 0; i < input.length; i++) {
                $('.updaterp').removeClass('show');
                if (validate(input[i]) == false) {
                    showValidate(input[i]);
                    check = false;
                }
            }
            if (check) {
                var cate = "";
                if ($('._cates_').children('div').length >= 1) {
                    for (i = 1; i <= $('._cates_').children().length - 1; i++) {
                        cate += ((i > 1) ? '-' : '') + $('._cates_').children('div:nth-child(' + i + ')').text();
                    }
                } else {
                    cate = $(input[3]).val();
                }
                conB('what=profile&cate=' + encodeURIComponent(cate) + '&user=' + encodeURIComponent($(input[0]).val()) + '&email=' + encodeURIComponent($(input[1]).val()) + '&bio=' + encodeURIComponent($(input[2]).val()), function(x) {
                    if (x == "done") {
                        gg("You Have 30mins To Open The Link We Sent Your In Your Mail And Confirm Settings");
                    } else {
                        gg(x)
                    }
                });
            }
            return false;
        });
        var lockput = $('.unlock .input100');
        $('.unlock').on('submit', function() {
            {
                var check = true;
                for (var i = 0; i < lockput.length; i++) {
                    $('.updaterp').removeClass('show');
                    if (validate(lockput[i]) == false) {
                        showValidate(lockput[i]);
                        check = false;
                    }
                }
                if (check) {
                    conB('what=unlock&pass=' + encodeURIComponent($(lockput[0]).val()), function(x) {
                        if (x == "done") {
                            window.location = "?unlock"
                        } else {
                            gg(x);
                        }
                    });
                }
            }
            return false;
        })
        $('.pass_settings').on('submit', function() {
            var check = true;
            for (var i = 0; i < passput.length; i++) {
                $('.updaterp').removeClass('show');
                if (validate(passput[i]) == false) {
                    showValidate(passput[i]);
                    check = false;
                }
            }
            if (check) {
                conB('what=password&old=' + encodeURIComponent($(passput[0]).val()) + '&new=' + encodeURIComponent($(passput[1]).val()), function(x) {
                    if (x == "done") {
                        gg("You Have 30mins To Open The Link We Sent Your In Your Mail And Confirm Settings");
                    } else {
                        gg(x);
                    }
                });
            }
            return false;
        });
        conB('what=acontent', function(x) {
            $('.mainB').html(x);
        })

        function delS(l) {
            conB("what=delS&it=" + encodeURIComponent($(l).text()), function(g) {
                if (g == "done") {
                    $(l).remove();
                }
            })
        }

        function gx(x, c, b) {
            switch (x) {
                case "pcontent":
                    $('.viewme').removeClass('overlay_show');
                    $('.viewme .ti-more').fadeOut(100);
                    $('.viewme .more').fadeOut(100);
                    $('.viewme .ti-reload').fadeOut(100);
                    $('.viewme .ti-pencil-alt').fadeIn(100);
                    break;
                case "tcontent":
                    $('.viewme').removeClass('overlay_show');
                    $('.viewme .ti-more').fadeOut(100);
                    $('.viewme .more').fadeOut(100);
                    $('.viewme .ti-reload').fadeIn(100);
                    $('.viewme .ti-pencil-alt').fadeOut(100);
                    break;
                case "dcontent":
                    $('.viewme').removeClass('overlay_show');
                    $('.viewme .ti-more').fadeIn(100);
                    $('.viewme .ti-reload').fadeOut(100);
                    $('.viewme .ti-pencil-alt').fadeOut(100);
                    break;
            }
            $('.viewme>div').attr('id', c);

            $('.topb span').text(b.substring(0, b.indexOf('~<~>~')));
            $('.a_content').html(b.substring(b.indexOf('~<~>~') + 5));
        }
        setTimeout(function() {
            $('.content').click(function() {

            })
        }, 100)
        var last;
        $('.search_box').keyup(function() {
            var dd = $(this).val(),
                g = ['vcontent', 'acontent', 'dcontent', 'tcontent', 'pcontent'];
            for (i = 0; i < g.length; i++) {
                if ($('.' + g[i]).length) {
                    last = g[i];
                }
            }
            if (dd.length >= 1) {
                conB("what=result&query=" + encodeURIComponent(dd), function(c) {
                    if (c.length > 1) {
                        $('.mainB').html("<h2 class='stat_fe'>Search Results For " + dd + "</h2>" + c);
                    }
                })
            } else {
                refresh(last);
            }
        });
        var von;

        function showIt(me) {
            clearInterval(von);
            von = setInterval(function() {
                $(me).toggleClass('ggg');
            }, 200)
            setTimeout(function() {
                clearInterval(von);
                $(me).removeClass('ggg');
            }, 1000)
        }

        function openL(t, f) {
            var vv = $(t).attr("id");

            if (refresh(f.substring(1))) {
                setTimeout(function() {
                    for (g = 1; g <= $(f).children().length; g++) {
                        if ($(f).children("div:nth-child(" + g + ")").attr('id') == vv) {
                            document.querySelector(f + ">div:nth-child(" + g + ")").scrollIntoView()
                            showIt($(f).children("div:nth-child(" + g + ")"));
                        }
                    }
                }, 100)
            }
        }

        function video_player(l, src) {
            $('.video_player').removeClass('overlay_show');
            $('.video_player .main_viewer .topb>span').text(l);

            var gg = JSON.parse(src),
                player = videojs(document.querySelector('.video-js'), {
                    preload: 'auto',
                    controls: true,
                    loop: true,
                    fluid: false,
                    poster: "",
                    liveui: true,
                    playbackRates: [0.5, 1, 1.5, 2, 3],
                    sources: [{
                        src: gg.src,
                        type: 'video/' + gg.type
                    }],
                    userActions: {
                        hotkeys: true
                    }
                });
            player.landscapeFullscreen();

            player.logo({
                image: 'dash/main.png',
                url: 'up',
                width: 120,
                position: "top-right",
                opacity: 1
            });
        }


        function refresh(t) {
            if ($(t).hasClass('acontent') || t == 'acontent') {
                viewM('.acont', 'acontent')
            } else if ($(t).hasClass('pcontent') || t == 'pcontent') {

                viewM('.pcont', 'pcontent')
            } else if ($(t).hasClass('tcontent') || t == 'tcontent') {
                viewM(".tcont", 'tcontent')
            } else if ($(t).hasClass('dcontent') || t == 'dcontent') {
                viewM(".dcont", 'dcontent')
            } else if ($(t).hasClass('vcontent') || t == 'vcontent') {
                viewM(".vcont", 'vcontent')
            }
            return true;
        }
    </script>
    <?php
    if (isset($_GET['qp']) || isset($_SESSION['tz'])) {
        if (isset($_SESSION['tz'])) {
            $tz = $_SESSION['tz'];
        } else {
            $tz = $_SESSION['tz'] = $_GET['qp'];
        }
        $dd = timezone_identifiers_list();
        for ($i = 0; $i < count($dd); $i++) {
            if (hash_equals(md5($dd[$i]), $tz)) {
                date_default_timezone_set("$dd[$i]");
            }
        }
    } else {
        $stuff = file_get_contents('files/js/jstz.js', true);

        echo "<script>" . $stuff . "
        if(window.location.search.length){
        window.location = window.location + '&qp='+llq;
        }else{
window.location = window.location + '?qp='+llq;
        }
    </script>";
    }
    ?>
</body>

</html>