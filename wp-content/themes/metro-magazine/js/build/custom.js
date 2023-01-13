jQuery(document).ready(function($) {

    /* Equal Height */
    $('.section-two.top-news .post').matchHeight({
        byRow: true,
        property: 'height',
        target: null,
        remove: false
    });


    // Get the modal
    var modal = document.getElementById('formModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    };

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    //mobile-menu
    $('.menu-opener').click(function() {
        $('body').addClass('menu-open');
    });

    $('.mobile-menu').prepend('<div class="btn-menu-close">Close</div>');

    $('.btn-menu-close').click(function() {
        $('body').removeClass('menu-open');
    });

    $('.overlay').click(function() {
        $('body').removeClass('menu-open');
    });

    $('.mobile-main-navigation ul .menu-item-has-children').append('<div class="angle-down"></div>');
    $('.mobile-secondary-menu ul .menu-item-has-children').append('<div class="angle-down"></div>');

    $('.mobile-main-navigation ul li .angle-down').click(function() {
        $(this).prev().slideToggle();
        $(this).toggleClass('active');
    });

    $('.mobile-secondary-menu ul li .angle-down').click(function() {
        $(this).prev().slideToggle();
        $(this).toggleClass('active');
    });

    //accessible menu in IE
    $("#site-navigation ul li a").focus(function() {
        $(this).parents("li").addClass("focus");
    }).blur(function() {
        $(this).parents("li").removeClass("focus");
    });

    $(".secondary-menu ul li a").focus(function() {
        $(this).parents("li").addClass("focus");
    }).blur(function() {
        $(this).parents("li").removeClass("focus");
    });

});