/*!
 Javascript du theme personnel
 */
(document).ready
$('.dropdown-toggle').dropdown();
$(document).ready(function(){
    //Handles menu drop down
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
    });
});
$(document).ready(function(){ 
    $('#characterLeft').text('250 caractères restants');
    $('#message').keydown(function () {
        var max = 250;
        var len = $(this).val().length;
        if (len >= max) {
            $('#characterLeft').text('Vous avez atteins la limite');
            $('#characterLeft').addClass('red');
            $('#btnSubmit').addClass('disabled');            
        } 
        else {
            var ch = max - len;
            $('#characterLeft').text(ch + ' caractères restants');
            $('#btnSubmit').removeClass('disabled');
            $('#characterLeft').removeClass('red');            
        }
    });    
});
$(function(){
	$('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
		$(this).parent().parent().find('div.product-chooser-item').removeClass('selected');
		$(this).addClass('selected');
		$(this).find('input[type="radio"]').prop("checked", true);
		//alert("Hello! I am an alert box!!");
		//var essai = $("#idProd").val();
        //alert(essai);
		
	});
});

function redirection(id){
  document.location.href="produit.php?id="+id; 
}

function supp(id){
  document.location.href="?suppPanier&id="+id; 
}