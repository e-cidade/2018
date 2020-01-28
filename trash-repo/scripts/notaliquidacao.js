/**
 * função para valida teclas digitadas.
 * @param event e event javascript
 * @param string teclas lista de teclas validas, 
 */
function js_pressKey(e,teclas){
   ini  =''; 	
   fim  = '';
   aval = '';
   or   ='';
   and  = ''
   t    = document.all ? event.keyCode : e.which;
   ta   = teclas.split("|");
   
   for (i = 0;i < ta.length;i++){
   	
   	    if (ta[i].indexOf("-") != "-1"){
   	    	
  	       vchars = ta[i].split("-");
  	        or = i > 0?'|| ':'';
  	        
  	       if (vchars.length > 1){
  	       	
  	          ini = vchars[0].charCodeAt();
   	          fim = vchars[1].charCodeAt();	
   	         
  	       	  aval += or+' (t >='+ini+' && t <='+fim+')';
  	       	  or = " ||";
  	       	  
  	       }else{
  	       	  aval += ' && t ='+vchars[0]
  	       }

  	    }else{
  	    	
  	    	and = i > 0?' ||  ':'';
  	    	aval += and+' t == '+ta[i].charCodeAt();
  	    	and = ' ||';
  	    	
  	    }
  	    
    }
    
   if (eval(aval)){
       return true;
   }else{
       if (t != 8 && t != 0 && t != 13 && t != 32){ // backspace
          return false;
     }else{
          return true;
     }
     
  }
  
}

function js_validaFracionamento(evt, lFraciona,obj ) {
  
  t = document.all ? event.keyCode : evt.which;
  if (obj.value.indexOf(".") != -1 && t == 46) {
    return false;
  }
  if (lFraciona) {
     sMask = "0-9|.";
  } else  {
    sMask = "0-9";
  }
  return js_pressKey(evt, sMask);
}
