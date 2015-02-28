var openDiv, $;
function toggleDiv(divID) {
	$("#" + divID).fadeToggle(150, function() {
		openDiv = $(this).is(':visible') ? divID : null;
	});
}

$(document).click(function(e) {
	if (!$(e.target).closest('#'+openDiv).length) {
		toggleDiv(openDiv);
	}
});

function is_touch_device() {
	return !!('ontouchstart' in window);
}
$(document).ready(function() {
	if(is_touch_device()) {
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
  $('#usermenu > div').toggleClass('no-js js');
  $('#usermenu .js div').hide();
  $('#usermenu .js').click(function(e) {
	$('#usermenu .js div').fadeToggle(150);
	$('#usermenu').toggleClass('active');
	e.stopPropagation();
  });
  $(document).click(function() {
	if ($('#usermenu .js div').is(':visible')) {
	  $('#usermenu .js div', this).fadeOut(150);
	  $('#usermenu').removeClass('active');
	}
  });
}
}
});

$(function () {
	if(is_touch_device()) {
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
  $('.actionmenu > div').toggleClass('no-js js');
  $('.actionmenu .js div').hide();
  $('.actionmenu .js').click(function(e) {
	$('.actionmenu .js div').fadeToggle(150);
	$('.clicker').toggleClass('active');
	e.stopPropagation();
  });
  $(document).click(function() {
	if ($('.actionmenu .js div').is(':visible')) {
	  $('.actionmenu .js div', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
}
}
});

$(function () {
  $('#hamburgerIcon').click(function(e) {
	$('#mw-panel').fadeToggle(150);
	$('.clicker').toggleClass('active');
	e.stopPropagation();
  });
  $('#hamburgerIcon').click(function() {
	if ($('#mw-panel').is(':visible')) {
	  $('#mw-panel', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
});

$(function () {
  $('#hamburgerIcon').click(function(e) {
	$('#mw-panel-custom').fadeToggle(150);
	$('.clicker').toggleClass('active');
	e.stopPropagation();
  });
  $('#hamburgerIcon').click(function() {
	if ($('#mw-panel-custom').is(':visible')) {
	  $('#mw-panel-custom', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
});

$(function () {
if(is_touch_device()) {
	if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	$('#p-search').hide();
  $('img.searchbar').click(function(e) {
	$('#p-search').fadeToggle(150);
	$('.clicker').toggleClass('active');
	e.stopPropagation();
  });
  $('img.searchbar').click(function() {
	if ($('#p-search').is(':visible')) {
	  $('#p-search', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
}
}
});

$(function () {
  $('img.editbutton').click(function(e) {
	$('#left-navigation').fadeToggle(150);
	$('.clicker').toggleClass('active');
	e.stopPropagation();
  });
  $('img.editbutton').click(function() {
	if ($('#left-navigation').is(':visible')) {
	  $('#left-navigation', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
});

$(function () {
  $('img.downarrow').click(function(e) {
	  $( 'img.custom3' ).on( 'click', toggleDiv( 'bartile' ) );
	e.stopPropagation();
  });
  $('img.downarrow').click(function() {
	if ($('#bartile').is(':visible')) {
	  $('#bartile', this).fadeOut(150);
	  $('.clicker').removeClass('active');
	}
  });
});
