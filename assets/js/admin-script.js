window.lang = "";

$(document).ready(function(){

    $(".flagsWrap img").click(function(){
        $.post("changeLang.php", {"lang" : $(this).attr("id")}, function(){
            location.reload();
        });
    });

    $.post("lang/"+$("#langHolder").text()+".php", {"lang" : "true"}, function(data){
        lang = data;
    }, "json");

    $('.page').css('width', '100%').css('width', '-=150px');

    $(window).on('resize', function(){
        $('.page').css('width', '100%').css('width', '-=150px');
    });

    $(".admin-login-errors").hide();

    $("#admin-submit-button").click(function(){

        $(".admin-login-form").submit(function(){
            return false;
        });

        $.post("admin-login-data.php", {
            'username' : $("#admin-input-username").val(),
            'password' : $("#admin-input-password").val(),
            'remember-me' : $("#admin-input-remember").val()
        }, function(data){
            if(data == 'ok'){
                window.location.href = "admin-main.php";
            }else{
                $(".admin-login-errors").fadeIn(300);
                $(".admin-login-errors").html(data);
            }

        });
    });

    $("#admin-logout").click(function(){
        window.location.href = "admin-logout.php";
    });

    $(".admin-menu li").click(function(){
        $(".admin-menu li").removeClass("active");
        $(this).addClass("active");
    });

    $("#dashboard-button").click(function(){
        $(".page").hide();
        $(".dashboard").fadeIn(300);
    });

    $("#snippets-button").click(function(){
        $(".page").hide();
        $(".snippets").fadeIn(300);
    });

    $("#users-button").click(function(){
        $(".page").hide();
        $(".users").fadeIn(300);
    });

    $("#banned-users-button").click(function(){
        $(".page").hide();
        $(".banned-users").fadeIn(300);
    });

    $("#settings-button").click(function(){
        $(".page").hide();
        $(".settings").fadeIn(300);
    });

    $("#edit-activate").click(function(){
        if($("#edit-activate").text() == lang.activate){
            $("#edit-activate").text(lang.deactivate);
            $("#edit-activate-holder").text("1");
        }else{
            $("#edit-activate").text(lang.activate);
            $("#edit-activate-holder").text("0");
        }
    });

    $("#edit-save").click(function(){
        $.post("editUser.php",{
            "username" : $("#edit-username").val(),
            "password" : $("#edit-password").val(),
            "email"	   : $("#edit-email").val(),
            "activate" : $("#edit-activate-holder").html(),
            "id"	   : $("#edit-id-holder").html()
        }, function(data){
            $("#edit-error").css("opacity", "1");
            $("#edit-error").html(data);
        });
    });

    var rows = [];
    var rows1 = [];

    var editFunction = function(e){
        $("#edit-id-holder").html(e.data.id);
        $("#edit-activate-holder").html(e.data.activated);
        $("#edit-username").val(e.data.username);
        $("#edit-email").val(e.data.email);
        if(e.data.activated == 0)
            $("#edit-activate").html(lang.activate);
        else
            $("#edit-activate").html(lang.deactivate);
        $(".edit-user-window").fadeIn(300);
        $("#admin-blur").fadeIn(300);
    }

    var editSnippetFunction = function(){
        var id;
        $("#snippet-error").html("");
        $("#snippet-error").hide();
        $('.snippets-table-wrap table tr').filter(':has(:checkbox:checked)').find('.snippet-id-holder').each(function() {
            id = $(this).data("snippet-id");
        });

        $("#name").val("");
        $("#description").val("");
        $("#snippetArea").val("");
        $("#myTags").tagit("removeAll");

        $.post("get-snippet-admin.php", {'id' : id}, function(data){
            $("#name").val(data.title);
            $("#description").val(data.description);
            $("#snippetArea").val(data.snippet);
        }, "json");

        $.post("get-tags-admin.php", {id : id}, function(tags){

            for(var i in tags){
                $("#myTags").tagit("createTag", tags[i].toString());
            }

            $(".id-holder").val(id);
            $("#save-snippet").html(lang.update);
        }, "json");

        $(".edit-snippet-window").fadeIn(300);
    }

    var deleteSnippetFunction = function(){
        rows = [];
        $('.snippets-table-wrap table tr').filter(':has(:checkbox:checked)').find('.snippet-id-holder').each(function() {
            rows.push($(this).data("snippet-id"));
        });
        $.post("deleteSnippet.php", {'ids' : rows}, function(data){
            if(data == 'ok'){
                location.reload();
            }
        });
    }

    var banFunction = function(){
        rows = [];
        $('.table-wrap table tr').filter(':has(:checkbox:checked)').find('.userId').each(function() {
            rows.push($(this).data("id"));
        });
        $.post("ban-user.php", {'ids' : rows, 'flag' : 'ban'}, function(data){
            if(data == 'ok'){
                location.reload();
            }
        });
    }

    var unbanFunction = function(){
        rows1 = [];
        $('.banned-table-wrap table tr').filter(':has(:checkbox:checked)').find('.userId').each(function() {
            rows.push($(this).data("id"));
        });
        $.post("ban-user.php", {'ids' : rows, 'flag' : 'unban'}, function(data){
            if(data == 'ok'){
                location.reload();
            }
        });
    }

    var deleteFunction = function(){
        rows = [];
        $('.table-wrap table tr').filter(':has(:checkbox:checked)').find('.userId').each(function() {
            rows.push($(this).data("id"));
        });
        $.post("delete-user.php", {'ids' : rows}, function(data){
            if(data == 'ok'){
                location.reload();
            }
        });
    }

    $(".edit-snippet-window").hide();

    $("#snippet-cancel").click(function(){
        $(".edit-snippet-window").fadeOut(300);
    });

    $("#save-snippet").click(function(){
        $.post("input-snippet-admin.php", {
            'name' : $("#name").val(), 'description' : $("#description").val(),
            'snippet' : $("#snippetArea").val(),
            'tags' : JSON.stringify($("#myTags").tagit("assignedTags")),
            'id' : $(".id-holder").val()
        }, function(data){
            if(data == 'ok'){
                window.location.reload();
            }else{
                $("#snippet-error").html(data);
                $("#snippet-error").fadeIn(300);
            }
        });
    });

    $("#snippet-error").hide();

    $(document).on("click", ".checker", function(){
        if($('.table-wrap :checkbox:checked').length == 1){
            $("#edit-user").on("click", {
                "id" : $(this).parent().siblings(".userId").text(),
                "activated" : $(this).parent().siblings(".activeStatus").data("activestatus"),
                "username" : $(this).parent().siblings(".username-holder").text(),
                "email" : $(this).parent().siblings(".email-holder").text()
            }, editFunction);

            $("#edit-user").animate({'opacity' : 1}, 500);
            $("#edit-user").addClass("visible");
        }
        if($('.table-wrap :checkbox:checked').length != 1){
            $("#edit-user").off("click", editFunction);

            $("#edit-user").animate({'opacity' : 0.1}, 200);
            $("#edit-user").removeClass("visible");
        }
        if($('.table-wrap :checkbox:checked').length > 0){
            $("#ban").on("click", banFunction);
            $("#delete-user").on("click", deleteFunction);

            $("#delete-user").animate({'opacity' : 1}, 500);
            $("#ban").animate({'opacity' : 1}, 500);

            $("#delete-user").addClass("visible");
            $("#ban").addClass("visible");
        }else{
            $("#ban").off("click", banFunction);
            $("#delete-user").off("click", deleteFunction);

            $("#delete-user").animate({'opacity' : 0.1}, 200);
            $("#ban").animate({'opacity' : 0.1}, 200);

            $("#delete-user").removeClass("visible");
            $("#ban").removeClass("visible");
        }
    });

    $(document).on("click", ".snippet-checker", function(e){
        e.stopPropagation();
        if($('.snippets-table-wrap :checkbox:checked').length == 1){
            $("#edit-snippet").on("click", editSnippetFunction);
            $("#edit-snippet").animate({'opacity' : 1}, 500);
            $("#edit-snippet").addClass("visible");
        }
        if($('.snippets-table-wrap :checkbox:checked').length != 1){
            $("#edit-snippet").off("click", editSnippetFunction);
            $("#edit-snippet").animate({'opacity' : 0.1}, 200);
            $("#edit-snippet").removeClass("visible");
        }
        if($('.snippets-table-wrap :checkbox:checked').length > 0){
            $("#delete-snippet").on("click", deleteSnippetFunction);
            $("#delete-snippet").animate({'opacity' : 1}, 500);
            $("#delete-snippet").addClass("visible");
        }else{
            $("#delete-snippet").off("click", deleteSnippetFunction);
            $("#delete-snippet").animate({'opacity' : 0.1}, 200);
            $("#delete-snippet").removeClass("visible");
        }
    });

    $(document).on("click", ".banned-checker", function(){
        if($('.banned-table-wrap :checkbox:checked').length > 0){
            $("#unban").on("click", unbanFunction);
            $("#unban").animate({'opacity' : 1}, 500);
            $("#unban").addClass("visible");
        }else{
            $("#unban").off("click", unbanFunction);
            $("#unban").animate({'opacity' : 0.1}, 200);
            $("#unban").removeClass("visible");
        }
    });

    $(document).on("click", "#select-all", function(){
        $("#edit-user").off("click", editFunction);$("#edit-user").off("click", editFunction);

        $("#edit-user").animate({'opacity' : 0.1}, 200);
        $("#edit-user").removeClass("visible");

        if($("#select-all").text() == lang.selectAll){
            $("#ban").on("click", banFunction);
            $("#delete-user").on("click", deleteFunction);

            $('.checker').prop('checked', true);

            $("#select-all").text(lang.deselectAll);

            $("#delete-user").animate({'opacity' : 1}, 500);
            $("#ban").animate({'opacity' : 1}, 500);

            $("#delete-user").addClass("visible");
            $("#ban").addClass("visible");
        }else{
            $("#ban").off("click", banFunction);
            $("#delete-user").off("click", deleteFunction);

            $('.checker').prop('checked', false);

            $("#select-all").text(lang.selectAll);

            $("#delete-user").animate({'opacity' : 0.1}, 200);
            $("#ban").animate({'opacity' : 0.1}, 200);

            $("#delete-user").removeClass("visible");
            $("#ban").removeClass("visible");
        }
    });

    $(document).on("click", "#select-all-snippets", function(){

        if($("#select-all-snippets").text() == lang.selectAll){
            $("#edit-snippet").off("click", editSnippetFunction);
            $("#edit-snippet").animate({'opacity' : 0.1}, 200);
            $("#edit-snippet").removeClass("visible");

            $("#delete-snippet").on("click", deleteSnippetFunction);
            $('.snippet-checker').prop('checked', true);
            $("#select-all-snippets").text(lang.deselectAll);

            $("#delete-snippet").animate({'opacity' : 1}, 500);
            $("#delete-snippet").addClass("visible");
        }else{
            $("#delete-snippet").off("click", deleteSnippetFunction);
            $('.snippet-checker').prop('checked', false);

            $("#select-all-snippets").text(lang.selectAll);
            $("#delete-snippet").animate({'opacity' : 0.1}, 200);
            $("#delete-snippet").removeClass("visible");

            $("#edit-snippet").off("click", editSnippetFunction);
            $("#edit-snippet").animate({'opacity' : 0.1}, 200);
            $("#edit-snippet").removeClass("visible");
        }
    });

    $(document).on("click", "#banned-select-all", function(){

        if($("#banned-select-all").text() == lang.selectAll){
            $("#unban").on("click", unbanFunction);

            $('.banned-checker').prop('checked', true);

            $("#banned-select-all").text(lang.deselectAll);

            $("#unban").animate({'opacity' : 1}, 500);
            $("#unban").addClass("visible");
        }else{
            $("#unban").off("click", unbanFunction);

            $('.banned-checker').prop('checked', false);

            $("#banned-select-all").text(lang.selectAll);
            $("#unban").animate({'opacity' : 0.1}, 200);
            $("#unban").removeClass("visible");
        }
    });

    $("#searchBox").keyup(function(){
        var text = this.value;

        if(text == ""){
            $(".pagination").show();
            $(".pagination label").removeClass("activePage");
            $(".pagination label:nth-child(1)").addClass("activePage");
        }
        if(text != "") $(".pagination").hide();

        $.post("searchUser.php", {"user" : text, "flag" : "no"}, function(data){
            $(".table-wrap table").html("<tr id='table-header'>\
                <td>"+lang.id+"</td>\
                <td>"+lang.username+"</td>\
                <td>"+lang.email+"</td>\
                <td>"+lang.joined+"</td>\
                <td>"+lang.active+"</td>\
                <td>"+lang.select+"</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.active[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".table-wrap table").append("<tr class='user-row'>\
                    <td data-id="+data.user_id[i]+" class='userId'>"+data.user_id[i]+"</td>\
                    <td class='username-holder' data-username="+data.username[i]+">"+data.username[i]+"</td>\
                    <td class='email-holder'>"+data.email[i]+"</td>\
                    <td>"+data.joined[i]+"</td>\
                    <td class='activeStatus' data-activeStatus='"+data.active[i]+"'>"+act+"</td>\
                    <td><input type='checkbox' class='checker'></td>\
                </tr>");
            }
        }, "json");
    });

    $("#banned-searchBox").keyup(function(){
        var text = this.value;

        if(text == ""){
            $(".banned-pagination").show();
            $(".banned-pagination label").removeClass("activePage");
            $(".banned-pagination label:nth-child(1)").addClass("activePage");
        }
        if(text != "") $(".banned-pagination").hide();

        $.post("searchUser.php", {"user" : text, "flag" : "yes"}, function(data){
            $(".banned-table-wrap table").html("<tr id='banned-table-header'>\
                <td>"+lang.id+"</td>\
                <td>"+lang.username+"</td>\
                <td>"+lang.email+"</td>\
                <td>"+lang.joined+"</td>\
                <td>"+lang.active+"</td>\
                <td>"+lang.select+"</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.active[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".banned-table-wrap table").append("<tr class='banned-user-row'>\
                    <td data-id="+data.user_id[i]+" class='userId'>"+data.user_id[i]+"</td>\
                    <td data-busername="+data.username[i]+">"+data.username[i]+"</td>\
                    <td>"+data.email[i]+"</td>\
                    <td>"+data.joined[i]+"</td>\
                    <td class='activeStatus' data-activeStatus='"+data.active[i]+"'>"+act+"</td>\
                    <td><input type='checkbox' class='banned-checker'></td>\
                </tr>");
            }
        }, "json");
    });

    $("#searchSnippet").keyup(function(){
        var text = this.value;

        if(text == ""){
            $(".snippet-pagination").show();
            $(".snippet-pagination label").removeClass("activePage");
            $(".snippet-pagination label:nth-child(1)").addClass("activePage");
        }
        if(text != "") $(".snippet-pagination").hide();

        $.post("searchSnippet.php", {"title" : text}, function(data){
            $(".snippets-table-wrap table").html("<tr id='snippet-table-header'>\
                <td>Owner</td>\
                <td>Title</td>\
                <td>Description</td>\
                <td>Public</td>\
                <td>Select</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.public[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".snippets-table-wrap table").append("<tr class='snippet-row'>\
                    <td class='owner-holder' data-username="+data.username[i]+">"+data.username[i]+"</td>\
                    <td class='title-holder'>"+data.title[i]+"</td>\
                    <td>"+data.description[i]+"</td>\
                    <td class='publicStatus' data-publicStatus='"+data.public[i]+"'>"+act+"</td>\
                    <td><input type='checkbox' class='snippet-checker'><label hidden data-snippet-id = "+data.snippet_id[i]+" class='snippet-id-holder'>"+data.snippet_id[i]+"</label></td>\
                </tr>");
            }
        }, "json");
    });

    $(".add-user-window").hide();
    $(".edit-user-window").hide();
    $("#admin-blur").hide();

    $("#add-user").click(function(){
        $(".add-user-window").fadeIn(300)
        $("#admin-blur").fadeIn(300);
    });

    $("#manual-save").click(function(){
        $.post("manual-add.php", {
            "username" : $("#manual-username").val(),
            "email" : $("#manual-email").val(),
            "password" : $("#manual-password").val()
        }, function(data){
            $("#manual-error").html(data);
            $("#manual-error").css("opacity", 1);
        });
    });

    $("#manual-close").click(function(){
        location.reload();
    });

    $("#edit-close").click(function(){
        location.reload();
    });

    $("#setting-error").hide();
    $("#setting-error-mail").hide();

    $("#setting-submit").click(function(){
        $.post("setting-pass.php", {
            "oldpass" : $("#setting-oldpass").val(),
            "newpass" : $("#setting-newpass").val(),
            "reppass" : $("#setting-reppass").val()
        }, function(data){
            $("#setting-error").html(data);
            $("#setting-error").fadeIn(300);
        });
    });

    $("#setting-submit-mail").click(function(){
        $.post("setting-mail.php", {
            "newmail" : $("#setting-newmail").val(),
            "repmail" : $("#setting-repmail").val()
        }, function(data){
            $("#setting-error-mail").html(data);
            $("#setting-error-mail").fadeIn(300);
        });
    });

    $("#setting-pass-button").click(function(){
        $("#setting-error").hide();
        $("#setting-error-mail").hide();
        $(".setting-pass-form").animate({"top" : "40px", "opacity" : 1}, 300);
        $(".setting-mail-form").animate({"top" : "-160px", "opacity" : 0}, 300);
    });

    $("#setting-close").click(function(){
        $("#setting-error").fadeOut(300);
        $("#setting-error-mail").fadeOut(300);
        $(".setting-pass-form").animate({"top" : "10px", "opacity" : 0}, 300);
        $("#setting-error").fadeOut(300);
    });

    $("#setting-mail-button").click(function(){
        $("#setting-error").hide();
        $("#setting-error-mail").hide();
        $(".setting-mail-form").animate({"top" : "-140px", "opacity" : 1}, 300);
        $(".setting-pass-form").animate({"top" : "10px", "opacity" : 0}, 300);
    });

    $("#setting-close-mail").click(function(){
        $("#setting-error").fadeOut(300);
        $("#setting-error-mail").fadeOut(300);
        $(".setting-mail-form").animate({"top" : "-160px", "opacity" : 0}, 300);
        $("#setting-error-mail").fadeOut(300);
    });
    $(".admin-reset-errors").hide();
    $("#admin-reset-submit").click(function(){
        $(".reset-form").submit(function(){
            return false;
        });

        $.post("reset-password.php", {'email' : $("#admin-reset-email").val(), 'flag' : 'admin'}, function(data){
            $(".admin-reset-errors").fadeIn(300);
            $(".admin-reset-errors").html("<label>"+data+"</label>");
        });
    });

    $("#admin-forgot-pass-link").click(function(){
        $(".admin-wrap").hide();
        $(".reset-wrap").show(300);
    });

    $(".pagination label").click(function(){
        $(".pagination label").removeClass("activePage");
        $(this).addClass("activePage");
        $.post("loadUsers.php", {'page' : $(this).text(), 'flag' : 'valid'}, function(data){
            $(".table-wrap table").html("<tr id='table-header'>\
                <td>"+lang.id+"</td>\
                <td>"+lang.username+"</td>\
                <td>"+lang.email+"</td>\
                <td>"+lang.joined+"</td>\
                <td>"+lang.active+"</td>\
                <td>"+lang.select+"</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.active[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".table-wrap table").append("<tr class='user-row'>\
                    <td data-id="+data.user_id[i]+" class='userId'>"+data.user_id[i]+"</td>\
                    <td data-username="+data.username[i]+">"+data.username[i]+"</td>\
                    <td>"+data.email[i]+"</td>\
                    <td>"+data.joined[i]+"</td>\
                    <td class='activeStatus' data-activeStatus='"+data.active[i]+"'>"+act+"</td>\
                    <td><input type='checkbox' class='checker'></td>\
                </tr>");
            }
        }, "json");
    });

    $(".snippet-pagination label").click(function(){
        $(".snippet-pagination label").removeClass("activePage");
        $(this).addClass("activePage");
        $.post("loadSnippets.php", {'page' : $(this).text()}, function(data){
            $(".snippets-table-wrap table").html("<tr id='snippet-table-header'>\
                <td>Owner</td>\
                <td>Title</td>\
                <td>Description</td>\
                <td>Public</td>\
                <td>Select</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.public[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".snippets-table-wrap table").append("<tr class='snippet-row'>\
                    <td class='owner-holder' data-username="+data.username[i]+">"+data.username[i]+"</td>\
                    <td class='title-holder'>"+data.title[i]+"</td>\
                    <td>"+data.description[i]+"</td>\
                    <td class='publicStatus' data-publicStatus='"+data.public[i]+"'>"+act+"</td>\
                    <td><input type='checkbox' class='snippet-checker'><label hidden data-snippet-id = "+data.snippet_id[i]+" class='snippet-id-holder'>"+data.snippet_id[i]+"</label></td>\
                </tr>");
            }
        }, "json");
    });

    $(".banned-pagination label").click(function(){
        $(".banned-pagination label").removeClass("activePage");
        $(this).addClass("activePage");
        $.post("loadUsers.php", {'page' : $(this).text(), 'flag' : 'banned'}, function(data){
            $(".banned-table-wrap table").html("<tr id='banned-table-header'>\
                <td>"+lang.id+"</td>\
                <td>"+lang.username+"</td>\
                <td>"+lang.email+"</td>\
                <td>"+lang.joined+"</td>\
                <td>"+lang.active+"</td>\
                <td>"+lang.select+"</td>\
            </tr>");

            for(var i in data.user_id){
                if(data.active[i] == 0) var act = lang.no; else var act = lang.yes;
                $(".banned-table-wrap table").append("<tr class='banned-user-row'>\
                    <td data-id="+data.user_id[i]+" class='userId'>"+data.user_id[i]+"</td>\
                    <td data-busername="+data.username[i]+">"+data.username[i]+"</td>\
                    <td>"+data.email[i]+"</td>\
                    <td>"+data.joined[i]+"</td>\
                    <td>"+act+"</td>\
                    <td><input type='checkbox' class='banned-checker'></td>\
                </tr>");
            }
        }, "json");
    });
});

function previewSnippet(id){
    window.location.href = $("#siteHolder").html() + "/preview.php?id=" + id;
}
