$(document).ready(function () {

    openAnimation();
    gears();

    $.ajaxSetup({
        cache: false,
        dataType: 'html',
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
        },
        timeout: 60000, // Timeout of 60 seconds
        type: 'POST',
        url: 'cult.php' 
    }); 

    $('#loadingIndicator')
        .bind('ajaxStart', function() { $(this).show(); })
        .bind('ajaxComplete', function() { $(this).hide(); });

    $('.prompt').keydown(function(event) {
        if (event.keyCode == 13) {
            $.ajax({ 
                data : { 
                    action : 'eval',
                    args   : $('.prompt').val() },
                success : function (comm) { 
                    alert(comm); 
                    $('.prompt').val() = ''; 
                }
            });
            return false;
        }
    });

});


function openAnimation() {

    var navbar = $('nav').hide();
    var sidebar = $('.sidebar').hide();

    navbar.slideDown(600, sidebarIn(200));
    $('.cultphetus').addClass('here');

}

function sidebarIn(time) {
    $('.main').animate({ marginRight: "200px" }, time, function() {
        $('.sidebar').fadeIn('slow');
    });
}

function sidebarOut(time) {

    $('.sidebar').fadeOut('slow', function() { 
        $('.main').animate({ marginRight: "20px" }, time);
    });
}

function gears() {
    var tgears = $('.tgears');
    var tgears_hidden=true;

    $('#gears').click(function () {
        if(tgears_hidden) {
            tgears.css("border", "2px solid #777");
            tgears.animate({ width: "98%" }, 900);
            tgears_hidden=false;
        } else {
            tgears.animate({ width: "55px" }, 900);
            tgears.css("border", "2px solid transparent");
            tgears_hidden=true;
        }
    });
}

function loadImage(image) {
   $('.content').html("<img src=\"" + image + "\" alt=img />" ); 
}

function loadSidebar(page) {
    $.ajax({
        data: { action : 'gencron', 
                args   : (page == 'cultphetus') ? 'blog' : page}, 
        success: function (crono) {
            $('.sidebar').html(crono);}
    });
}

function loadFrame(name) {
    var main = $('.main');
    main.fadeOut(400, function () {
         $.ajax({
             data: { page : (name == 'cultphetus') ? 'blog' : name },
            success: function(data) {
                $('.content').html(data);
            }
        });
        $('.mainTitle').text(name);
        $('.here').removeClass('here');
        current = $('.' + name)
        current.addClass('here');
        main.fadeIn(400, function () { 
            sidebar =  $('.sidebar');
            if(current.hasClass('sb')){
                if(sidebar.is(':hidden')) {
                    loadSidebar(name);
                    sidebarIn(400);
                } else {
                    sidebar.fadeOut(200, function () {
                        loadSidebar(name);
                        sidebar.fadeIn();
                    }); 
                }
            } else {
                sidebarOut(200);
            }
        }); // --end main.fadeIn
    }); // --end fadeOut
}

function moveTo(id) {
    var elem = $("#ar" + id);
    $("html, body").animate({scrollTop : elem.offset().top  }, 800);
//    elem.css("background", "red");
//    elem.animate( { backgroundColor : "black" }, 3000);
}
