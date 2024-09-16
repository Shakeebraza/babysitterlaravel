jQuery(document).ready(function($) {
	jQuery('.stellarnav').stellarNav({
		breakpoint: 992,
		menuLabel: 'Menu',
		closeBtn: true,
		closeLabel: 'Close'
	});

    $('.stellarnav').find('li>a').on('click', function() {
        // Prüfe, ob das Menü in der mobilen Ansicht geöffnet ist
        var menuOpened = $('.stellarnav').hasClass('active');

        if (menuOpened && $(window).width() < 960) { // 960 sollte dein Breakpoint sein
            $('.stellarnav').find('.menu-toggle').click(); // Simuliere einen Klick auf den Menü-Toggle
        }
    });
});

/*jQuery(function() {
    jQuery('.tb-search-modal-btn').on('click',function(){
        jQuery('.tb-search-modal').toggleClass('tb-active');
    });
    jQuery('.tb-search-modal-cross, .tb-search-modal-overlay').on('click',function(){
        jQuery('.tb-search-modal').removeClass('tb-active');
    });
});*/

jQuery('select').niceSelect();

// fix-header-start
// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('header').outerHeight();
$(window).scroll(function(event){
    didScroll = true;
});
setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);
function hasScrolled() {
    var st = $(this).scrollTop();
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('header').removeClass('nav-down').addClass('nav-up');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('header').removeClass('nav-up').addClass('nav-down');
        }
    }
    lastScrollTop = st;
}

$(window).scroll(function() {
	if ($(this).scrollTop() > 1){
    	$('header').addClass("sticky");
  	}
  	else{
    	$('header').removeClass("sticky");
  	}
});
// fix-header-end

$('.testimonial-slider-main').slick({
  dots: false,
  infinite: true,
  autoplay: true,
  /*centerMode: true,*/
  speed: 600,
  arrows: true,
  prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></button>',
  nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></button>',
  slidesToShow: 4.5,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1100,
      settings: {
        centerMode: false,
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 992,
      settings: {
        centerMode: false,
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 768,
      settings: {
        centerMode: false,
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 576,
      settings: {
        centerMode: false,
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
  ]
});
