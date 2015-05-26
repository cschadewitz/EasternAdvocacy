// Carousel Auto-Cycle
  $(document).ready(function() {
    $('.carousel').carousel({
      interval: 6000
    })
  });

$("#zipcode").keyup(function()
{
	if (/(^\d{5}$)|(^\d{5}-\d{4}$)/.test($(this).val())) {
        $("#reps-div").html("Loading <i class='icon-spinner icon-spin icon-large'></i>");
		$.get("lookupreps/html/"+$(this).val(), function( data ) {
			$("#reps-div").html(data);
		});
	}
});

$("#action-form").on("success", function() {
    $('#action-form').fadeOut(1000);
});