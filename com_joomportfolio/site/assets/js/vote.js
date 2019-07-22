function JoomportfRate(id,i,total,total_count,counter){
	var jpAjax;
	var live_site = window.location.protocol+'//'+window.location.host+sfolder;
	var div = document.getElementById('jportfrate_'+id);
	div.innerHTML='<img src="'+live_site+'/components/com_joomportfolio/assets/images/loading.gif" border="0" align="absmiddle" /> '+'<small>'+jportfrate_text[1]+'</small>';
	try	{
		jpAjax=new XMLHttpRequest();
	} catch (e) {
		try	{ jpAjax=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try { jpAjax=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert(jportfrate_text[0]);
				return false;
			}
		}
	}
	jpAjax.onreadystatechange=function() {
		var response;
		if(jpAjax.readyState==4){
			setTimeout(function(){ 
				response = jpAjax.responseText; 
				if(response=='thanks') div.innerHTML='<small>'+jportfrate_text[2]+'</small>';
				if(response=='login') div.innerHTML='<small>'+jportfrate_text[3]+'</small>';
				if(response=='voted') div.innerHTML='<small>'+jportfrate_text[4]+'</small>';
			},500);
			setTimeout(function(){
				if(response=='thanks'){
					var newtotal = total_count+1;
					var percentage = ((total + i)/(newtotal));
					document.getElementById('rating_'+id).style.width=parseInt(percentage*20)+'%';
				}
				if(counter!=0){
					if(response=='thanks'){
						if(newtotal!=1)	
							var newvotes=newtotal+' '+jportfrate_text[5];
						else
							var newvotes=newtotal+' '+jportfrate_text[6];
						div.innerHTML='<small>( '+newvotes+' )</small>';
					} else {
						if(total_count!=0 || counter!=-1) {
							if(total_count!=1)
								var votes=total_count+' '+jportfrate_text[5];
							else
								var votes=total_count+' '+jportfrate_text[6];
							div.innerHTML='<small>( '+votes+' )</small>';
						} else {
							div.innerHTML='';
						}
					}
				} else {
					div.innerHTML='';
				}
			},2000);
		}
	}
	jpAjax.open("GET",live_site+"/index.php?option=com_joomportfolio&task=item.getRateAjax&user_rating="+i+"&id="+id,true);
	jpAjax.send(null);
}