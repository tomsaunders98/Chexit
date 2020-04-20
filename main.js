function myFunction(result) {
	var nums = result.split(":");
	var party = nums[0];
	var vals = nums.shifts();
}

$(document).ready(function(){	
	if($(window).width() < 768){
		var viewport = "mb";
	}
	if($(window).width() >= 768 && $(window).width() <= 992){
		var viewport = "mb";
	}
	if($(window).width() > 992){
		var viewport = "nm";
	}
	$('.header').height($(window).height());
	$('.header1').height($(window).height());
	var txt1 = ""; 
	$(".tiny").hide();
  	$(".hidebut").click(function(e){
		e.preventDefault();
		var id = this.id;
		var id = id.substring(1, id.length);
		
		var DeetLoc = "#" + id + "deet";
		var Hidloc = "#" + id + "hid";
		$(DeetLoc).fadeOut(400);
		$(Hidloc).fadeOut(400);
  	});
	$('.hoverhelp').each(function(){
		var id = this.id;
		var classList = document.getElementById(id).className.split(/\s+/);
		var id1 = classList[1] + "U";
		var HidLoc = "#" + classList[1] + "hid";
		$(HidLoc).hide();
	});
	$(".hoverhelp").hover(function(){
		var id = this.id;
		var tinyid = '#' + 'tiny' + id;
    	$(tinyid).show(); 
  	});
	$(".hoverhelp").mouseleave(function(){
		var id = this.id;
		var tinyid = '#' + 'tiny' + id;
    	$(tinyid).hide();
  	});
  	$("#linkbutton").click(function(e){
  		e.preventDefault();
  		var link = $("#linkbutton").attr("href");
  		link.select();
  		document.execCommand("copy");
  	});

	$(".hoverhelp").click(function(e){
		e.preventDefault();
		var id = this.id;
		var classList = document.getElementById(id).className.split(/\s+/);
		var HidLoc = "#" + classList[1] + "hid";
		var DeetLoc = "#" + classList[1] + "deet";
		var Headloc = "#" + classList[1] + "head";
		var id1 = id + "d";
		var id2 = id + "W";
		var isvisible = $(HidLoc).is(":visible"); 
		var loc = "details.html #" + id1;
		var chartrequest = "char.php?q=" +id + "#" + id1;
		console.log(HidLoc);
		try {
			if (isvisible){
					$.ajax({
    					type: "GET",
    					url: "chart.php",
   						data: {
       					q: id,
       					vp: viewport
    					},
    					success: function (data) {
    						if (data === "NoTable"){
    							$(".deets").fadeOut(400);
    							$(".deets").remove();
    							$(Headloc).fadeOut(400);
    							$(Headloc).remove();
    							$(".chartWrapper").fadeOut(400);
    							$(".chartWrapper").remove();
    							$(HidLoc).fadeOut(400);
    							$(DeetLoc).fadeOut(400, function(){
									$(DeetLoc).load(loc, function(){ 
										$(DeetLoc).fadeIn(400);
									}); 
								});

    						}else{
    							$(".deets").fadeOut(400);
    							$(".deets").remove();
    							$(Headloc).fadeOut(400);
    							$(Headloc).remove();
    							$(".chartWrapper").fadeOut(400);
    							$(".chartWrapper").remove();
	        					$(HidLoc).append(data);
	        					$(HidLoc).fadeIn(400);
	        					$(DeetLoc).fadeOut(400, function(){
									$(DeetLoc).load(loc, function(){ 
										$(DeetLoc).fadeIn(400);
									}); 
								});
	        				}
						}
					//$(HidLoc).load(chartrequest, function(){     				
    				//});
					});
				
			}else{
	  			//$(HidLoc).load(chartrequest, function(){
				$.ajax({
					type: "GET",
					url: "chart.php",
					data: {
   					q: id,
   					vp: viewport
					},
					success: function (data) {
						if (data === "NoTable"){
							$(".deets").fadeOut(400);
    						$(".deets").remove();
							$(HidLoc).fadeOut(400);
							$(DeetLoc).load(loc, function(){ 
								$(DeetLoc).fadeIn(400);
							});
						}else{
							$(".deets").fadeOut(400);
    						$(".deets").remove();
        					$(HidLoc).append(data);
        					$(HidLoc).fadeIn(400);
        					$(DeetLoc).load(loc, function(){ 
								$(DeetLoc).fadeIn(400);
							});
        				}
					}
	    		});
	    		
  			}	
  		}catch(err){
  			console.log(err);
  		}
	}); 
})