// JavaScript Document
///// funcoes pra cookies ////////
///para um help destas funcoes, olhe pagina 392 da biblia do javascript
function getCookieVal (offset) {
  var endstr = document.cookie.indexOf (";", offset);
  if (endstr == -1)
    endstr = document.cookie.length;
  return unescape(document.cookie.substring(offset, endstr));
}
function FixCookieDate (date) {
  var base = new Date(0);
  var skew = base.getTime(); // dawn of (Unix) time - should be 0
  if (skew > 0)  // Except on the Mac - ahead of its time
    date.setTime (date.getTime() - skew);
}
function GetCookie (name) {
  var arg = name + "=";
  var alen = arg.length;
  var clen = document.cookie.length;
  var i = 0;
  while (i < clen) {
    var j = i + alen;
    if (document.cookie.substring(i, j) == arg)
      return getCookieVal (j);
	i = document.cookie.indexOf(" ", i) + 1;
    if (i == 0) break; 
  }
return null;
}
function SetCookie (name,value,expires,path,domain,secure) {
  document.cookie = name + "=" + escape (value) +
    ((expires) ? "; expires=" + expires.toGMTString() : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
function DeleteCookie (name,path,domain) {
  if (GetCookie(name)) {
    document.cookie = name + "=" +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}
////////////////
    var engaged = false
    var offsetX = 0
    var offsetY = 0
    var Zindex = 5;
    
    function js_dragIt(obj,evt) {
      evt = (evt) ? evt : (window.event) ? window.event : "";
      if (engaged) {
        if (evt.pageX) {
          obj.style.left = evt.pageX - offsetX + "px";
          obj.style.top = evt.pageY - offsetY + "px";
        } else {
          obj.style.left = evt.clientX - offsetX + "px";
          obj.style.top = evt.clientY - offsetY + "px";
        }
        return false;
      }
    }
    function js_engage(obj,evt) {	
      evt = (evt) ? evt : (window.event) ? window.event : "SEM EVENTO";	  

	  if(JANS.isModal == 1)
	    return false;
	  SetCookie("modulo",obj.id);
	//  alert(GetCookie("modulo"));

      engaged = true;
      obj.style.zIndex = Zindex++;
	  
  	  empilhaJanelas(obj.id.substr(3));	  
  	  for(var i = 0;i < JANS.length - 1;i++) {
		  JANS[i].guardaCorFundoTitulo = JANS[i].corFundoTitulo;
          JANS[i].setCorFundoTitulo("#A6A6A6");
	  }
	  JANS[obj.id.substr(3)].setCorFundoTitulo("#2C7AFE");
 
      if (evt.pageX) {
        offsetX = evt.pageX - obj.offsetLeft;
        offsetY = evt.pageY - obj.offsetTop;		
      } else {
        offsetX = evt.offsetX - document.body.scrollLeft;
        offsetY = evt.offsetY - document.body.scrollTop;
        if (navigator.userAgent.indexOf("Win") == -1) {
          offsetX += document.body.scrollLeft;
          offsetY += document.body.scrollTop;
        }		
      }	 
      return false;
    }
    function js_release(obj,evt) {
      evt = (evt) ? evt : (window.event) ? window.event : "";
      engaged = false;
    }
function empilhaJanelas(nomeJan) {
  for(var i = 0;i < JANS.length;i++)
    if(JANS[i].nomeJanela == nomeJan) {
	  var indice = i;
	  aux = JANS[i];
	  break;
	}
  for(i = indice;i < JANS.length - 1;i++)
    JANS[i] = JANS[i+1];  
  JANS[i] = aux;
}	
	function js_MaximizarJan(img,cod) {
	  if(JANS.isModal == 1)
	    return false;
	  var str = new String(img.src);
	  if(str.indexOf("on") == -1)
	    return false;
	  else {
	    alert("Not implemented Yet");
//        var fr = cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0];
//		for( i in cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0])
//        document.getElementById('ff').innerHTML += i + ' <==> ' + cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0][i] +  '<br>';
	  }
	}
	
	function js_MinimizarJan(img,JanElA) {	  
      var janela = eval(JanElA);
	  if(JANS.isModal == 1)
	    return false;
	  var str = new String(img.src);
	  if(str.indexOf("on") == -1)
	    return false;
	  else {
	    if(janela.nomeJanela == JANS[JANS.length-1].nomeJanela) {
	      JANS[JANS.length-1].guardaCorFundoTitulo = JANS[JANS.length-1].corFundoTitulo;
          JANS[JANS.length-1].setCorFundoTitulo("#A6A6A6");
		  var aux = JANS[JANS.length-1];
		  for(var i = JANS.length-1;i > 0;i--) {
		    JANS[i] = JANS[i - 1];
		  }
		  JANS[0] = aux;
		  JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");  
		  /*
		  aux = "";
		  for(var i = 0;i < JANS.length;i++)
		    aux += " MI "+JANS[i].nomeJanela;
			alert(aux);
		  */
		}
		str = new String(img.src);
		if(str.indexOf("_2_") == -1) {
          JanPosX = (typeof(JanPosX)=="undefined" || JanPosX=="")?1:JanPosX;
          JanPosY = (typeof(JanPosY)=="undefined" || JanPosY=="")?400:JanPosY;		
	  	  if(JanPosX >= 600) {
		    JanPosX = 1;
		    JanPosY = JanPosY - 27;
  		  }
		  if(typeof(janela.px) == "undefined" && typeof(janela.py) == "undefined") {
		    janela.px = JanPosX;
			janela.py = JanPosY;
			janela.Wi = janela.moldura.style.width;
			janela.Hi = janela.moldura.style.height;
			janela.Pl = janela.moldura.style.left;
			janela.Pt = janela.moldura.style.top;
            JanPosX += (janela.titulo.length * 5) + 52;
		  }
          janela.setAltura(1);
	      janela.setLargura(1);		  
  		  janela.setPosX(janela.px);
		  janela.setPosY(janela.py);
		  img.src = 'imagens/jan_mini_2_on.gif';
		} else {
		  img.src = 'imagens/jan_mini_on.gif';		
		  janela.setAltura(janela.Hi);
	      janela.setLargura(janela.Wi);
		  janela.setPosX(janela.Pl);
		  janela.setPosY(janela.Pt);
		  janela.focus();
		  SetCookie("modulo",janela.nomeJanela);
		  /****/
		  var aux = JANS[janela.nomeJanela];
		  var j = 0;
		  for(var i = 0;i < JANS.length;i++) {
		    if(aux.nomeJanela != JANS[i].nomeJanela)
		      JANS[j++] = JANS[i];
		  }
	      JANS[JANS.length-2].guardaCorFundoTitulo = JANS[JANS.length-2].corFundoTitulo;
          JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
		  JANS[JANS.length-1] = aux;
		  JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");  
		  /*		  		  		  
		  aux = "";
		  for(var i = 0;i < JANS.length;i++)
		    aux += " MA "+JANS[i].nomeJanela;
			alert(aux);
		  */
		}
	  }
	}
	
	function js_FecharJan(img,JanElA) {
	  var str = new String(img.src);
	  if(str.indexOf("on") == -1)
	    return false;
	  else {
	    var janela = eval(JanElA);
		if(JANS.isModal == 1) {
				  /*aux = "";
       		      for(var i = 0;i < JANS.length;i++)
		          aux += " MA "+JANS[i].nomeJanela;
			      alert(aux);*/
          if(JANS[JANS.length-1].nomeJanela != janela.nomeJanela)
            return false;
	    }
        janela.hide();
		return true;
	  }
	}
	
function criaJanela(nomeJan,arquivo,cabecalho) {
  if(document.getElementById("Jan" + nomeJan)) {
    document.getElementById("Jan" + nomeJan).style.visibility = 'visible';
    return false;
  }
  var camada = document.createElement("DIV");
  var tabela1 = document.createElement("TABLE");
  var tabela2 = document.createElement("TABLE");
  var quadro = document.createElement("IFRAME");
  var img1 = document.createElement("IMG");
  var img2 = document.createElement("IMG");
  var img3 = document.createElement("IMG");    

  img3.setAttribute("src","imagens/jan_fechar_on.gif");
  img3.setAttribute("title","Fechar");
  img3.setAttribute("border","0");
  img3.style.cursor = "hand";
  img3.onclick = function() { js_FecharJan(this,nomeJan); };

  img2.setAttribute("src","imagens/jan_max_off.gif");
  img2.setAttribute("title","Maximizar");
  img2.setAttribute("border","0");
  img2.onclick = function() { js_MaximizarJan(this,nomeJan); };

  img1.setAttribute("src","imagens/jan_mini_on.gif");
  img1.setAttribute("title","Minimizar");
  img1.setAttribute("border","0");
  img1.style.cursor = "hand";
  img1.onclick = function() { js_MinimizarJan(this,nomeJan); };
  
  camada.setAttribute("id","Jan" + nomeJan);
  tabela1.setAttribute("cellSpacing",0);
  tabela1.setAttribute("cellPadding",2);
  tabela1.setAttribute("border",0);
  tabela1.setAttribute("width","100%");
  tabela1.setAttribute("height","100%");
  
  tabela1.style.borderColor = "#f0f0f0 #606060 #404040 #d0d0d0";
  tabela1.style.borderStyle = "solid";
  tabela1.style.borderWidth = "2px";
  
  tabela2.setAttribute("cellSpacing",0);
  tabela2.setAttribute("cellPadding",0);
  tabela2.setAttribute("border",0);
  tabela2.setAttribute("width","100%");

  quadro.setAttribute("frameBorder","1");
  quadro.setAttribute("height","100%");
  quadro.setAttribute("width","100%");
  quadro.setAttribute("id","IF" + nomeJan);
  quadro.setAttribute("name","IF" + nomeJan);
  quadro.setAttribute("scrolling","auto");
  //quadro.setAttribute("src",arquivo);
  
  var tab1Linha1 = tabela1.insertRow(0);
  var tab1Linha2 = tabela1.insertRow(1);
  var tab2Linha1 = tabela2.insertRow(0);
  
  var tab1Coluna1 = tab1Linha1.insertCell(0);
  var tab1Coluna2 = tab1Linha2.insertCell(0);
  var tab2Coluna1 = tab2Linha1.insertCell(0);
  var tab2Coluna2 = tab2Linha1.insertCell(1);
 
  tab2Linha1.setAttribute("id","CF" + nomeJan);
  tab2Linha1.style.backgroundColor = '#2C7AFE';
  tab1Linha1.style.backgroundColor = '#c0c0c0';
  tab2Coluna1.style.whiteSpace = "nowrap";
  tab2Coluna1.onmousedown = function(event) { js_engage(document.getElementById('Jan' + nomeJan),event); };
  tab2Coluna1.onmouseup = function(event) { js_release(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.onmousemove = function(event) { js_dragIt(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.onmouseout = function(event) { js_release(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.setAttribute("width","80%");
  tab2Coluna1.style.cursor = 'hand';
  tab2Coluna1.style.fontWeight = 'bold';
  tab2Coluna1.style.color = 'white';
  tab2Coluna1.style.fontFamily = 'Arial, Helvetica, sans-serif';
  tab2Coluna1.style.fontSize = '11px';
  tab2Coluna1.innerHTML =  (typeof(cabecalho)=="undefined" || cabecalho=="")?'&nbsp; DBSeller Inform�tica Ltda':('&nbsp;' + cabecalho);
//  tab2Coluna1.innerHTML =  (typeof(cabecalho)=="undefined" || cabecalho=="")?'&nbsp;' + nomeJan:('&nbsp;' + cabecalho);
  tab2Coluna1.setAttribute("nowrap","1");
  tab2Coluna2.setAttribute("nowrap","1");
  tab2Coluna2.setAttribute("width","20%");
  tab2Coluna2.setAttribute("align","right");
  tab2Coluna2.setAttribute("valign","middle");
  
  tab1Coluna2.setAttribute("width","100%");
  tab1Coluna2.setAttribute("height","100%");  
  camada.style.backgroundColor = "#c0c0c0";
  camada.style.layerBackgroundColor = "#c0c0c0";
  camada.style.border = "0px outset #666666";
  camada.style.position = "absolute";
  camada.style.left = "100px";
  camada.style.top = "100px";
  camada.style.zIndex = "1";
  camada.style.visibility = 'visible';
  camada.style.width = "400px";
  camada.style.height = "400px";
  tab2Coluna2.appendChild(img1);
  tab2Coluna2.appendChild(img2);
  tab2Coluna2.appendChild(img3);        
  tab1Coluna1.appendChild(tabela2);
  tab1Coluna2.appendChild(quadro);
  camada.appendChild(tabela1);
  document.body.appendChild(camada);
  eval(nomeJan + " = new janela(document.getElementById('Jan" + nomeJan + "'),document.getElementById('CF" + nomeJan + "'),IF" + nomeJan + ")");
  document.getElementById('IF' + nomeJan).src = arquivo;
  eval(nomeJan + ".focus()");
  return eval(nomeJan);
}
	///////////////////////////////////////////////////////////////////////////////////////
	function janela(janP,cFt,Iframe) {
	  this.moldura = janP;
	  this.jan = Iframe;
	  this.janFrame = Iframe.frameElement;
	  this.nomeJanela = janP.id.substr(3);
	  this.titulo = new String(cFt.firstChild.innerHTML);
	  document.cookie = "modulo=" + janP.id;
	  netscape = navigator.appName == "Netscape"?1:0;

	  if(typeof(JANS)=="undefined") {
	    JANS = new Array();	
		JANS.isModal = 0;  		
	  }	else {	  
  	    for(var i = 0;i < JANS.length;i++) {
		  JANS[i].guardaCorFundoTitulo = JANS[i].corFundoTitulo;
          JANS[i].setCorFundoTitulo("#A6A6A6");
		}
	  }	  
	  JANS[this.nomeJanela] = this;
	  JANS.push(this);      

	  function setTarget() {
	    var args = setTarget.arguments;
        var F = (typeof(args[0])=="undefined" || args[0]=="")?"form1":args[0];
	    document.forms[F].target = Iframe.name;
	  }
	  this.setTarget = setTarget;
	  
      function setTitulo(t) {
        cFt.firstChild.innerHTML = '&nbsp;' + t;
		this.titulo = new String('&nbsp;' + t);
	  }
	  this.setTitulo = setTitulo;
	  this.titulo = new String(cFt.firstChild.innerHTML);
	  
	  function setCorFundoTitulo(cor) {
	    cFt.style.backgroundColor = cor;
		this.corFundoTitulo = cor;
	  }
	  this.setCorFundoTitulo = setCorFundoTitulo;
	  this.corFundoTitulo = cFt.style.backgroundColor;

      function setCorTitulo(cor) {
        cFt.firstChild.style.color = cor;
		this.corTitulo = cor;
	  }
	  this.setCorTitulo = setCorTitulo;
	  this.corTitulo = cFt.firstChild.style.color;
	  
	  function setFonteTitulo(f) {
        cFt.firstChild.style.fontFamily = f;
		this.fonteTitulo = f;
	  }
	  this.setFonteTitulo = setFonteTitulo;
	  this.fonteTitulo = cFt.firstChild.style.fontFamily;

      function setTamTitulo(t) {
        cFt.firstChild.style.fontSize = t;
		this.tamTitulo = t;
	  }
	  this.setTamTitulo = setTamTitulo;
	  this.tamTitulo = cFt.firstChild.style.fontSize;
	  
	  function setPosX(pos) {
	    janP.style.left = pos;
		this.posX = pos;
	  }
	  this.setPosX = setPosX;
	  this.posX = janP.style.left;
	  function setPosY(pos) {
	    janP.style.top = pos;
		this.posY = pos;
	  }
	  this.setPosY = setPosY;
	  this.posY = janP.style.top;
	  function setLargura(l) {
	    janP.style.width = l;
        //Iframe.frameElement.style.width = l;	   		
		this.largura = l;
	  }
	  this.setLargura = setLargura;	  
	  this.largura = janP.style.width;
	  function setAltura(a) {
	    janP.style.height = a;	 
        //Iframe.frameElement.style.height = a;
		this.altura = a;
	  }
	  this.setAltura = setAltura;
	  this.altura = janP.style.height;
	  function focus() {
	    janP.style.zIndex = Zindex++;
	  }
	  this.focus = focus;
	  function show() {
	    empilhaJanelas(this.nomeJanela);
		// estava assim
		//JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
		if(JANS.length > 1)
          JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
	    janP.style.visibility = 'visible';
	  }
	  this.show = show;	  
	  function hide() {
	    var aux = JANS[JANS.length-1];
	    for(var i = JANS.length-1;i > 0;i--)
          JANS[i] = JANS[i - 1];		
	    JANS[0] = aux;
        JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");  					  
	    janP.style.visibility = 'hidden';			
	  }
	  this.hide = hide;	  	  
	  /******/
	  function procuraNo(obj,nome) {
	    nome = nome.toUpperCase();
		//alert(obj.childNodes.length);
        if(obj.childNodes.length > 0) {
          for(var i = 0;i < obj.childNodes.length;i++) {
            if(obj.childNodes[i].nodeName == nome) {
              //alert(obj.childNodes[i].nodeName + ' ' + i + ' = ' + nome);
			  ObjRet = obj.childNodes[i];
			  return true;
			}			
	        procuraNo(obj.childNodes[i],nome);
	      }
        }
	  }
	  /******/
	  function limpaTela() {	    
        procuraNo(Iframe.document,"body");
	    corpo = ObjRet;//vem da funcao procuraNo
        var documento = corpo.childNodes;		
        do {
          for(var i = 0;i < documento.length;i++) {
            corpo.removeChild(documento[i]);
          }
        } while(documento.length != 0);
	  }
	  this.limpaTela = limpaTela;
	  
	  function mostraMsg() {
        var args = mostraMsg.arguments;
        var msg = (typeof(args[0])=="undefined" || args[0]=="")?"Processando...":args[0];
		var cor = (typeof(args[1])=="undefined" || args[1]=="")?"white":args[1];
		var Larg = (typeof(args[2])=="undefined" || args[2]=="")?this.moldura.style.width:args[2];		
		var Alt = (typeof(args[3])=="undefined" || args[3]=="")?this.moldura.style.height:args[3];		
		var PosX = (typeof(args[4])=="undefined" || args[4]=="")?"0":args[4];		
		var PosY = (typeof(args[5])=="undefined" || args[5]=="")?"0":args[5];
		
  	    if(elem = document.getElementById("mensagem") )
	      elem.parentNode.removeChild(elem);
	    var camada = Iframe.document.createElement("DIV");
	    camada.setAttribute("id","mensagem");
				    
		procuraNo(Iframe.document,"body");
	    ObjRet.appendChild(camada);
        var elem = Iframe.document.getElementById("mensagem");
		elem.innerHTML = "<table border='0' cellpadding='0' cellspacing='0'><tr><td width='" + Larg + "' height='" + Alt + "' align='center' valign='middle'><strong>" + msg + "</strong></td></tr></table>";
	    elem.style.backgroundColor = cor;
	    elem.style.layerBackgroundColor = cor;
	    elem.style.position = "absolute";
	    elem.style.left = "0px";
	    elem.style.top = "0px";
	    elem.style.zIndex = "100";		
	    elem.style.visibility = 'visible';
	    elem.style.width = Larg;
	    elem.style.height = Alt;
	  }
	  this.mostraMsg = mostraMsg;
	  function setJanBotoes(str) {
	    var s = new String(str);	   
	    var img1 = cFt.childNodes[1].childNodes[0];
	    var img2 = cFt.childNodes[1].childNodes[1];
	    var img3 = cFt.childNodes[1].childNodes[2];		  

	    kp = 0x4;
	    m = kp & s;
	    kp >>= 1;
	    img1.src = m?"imagens/jan_mini_on.gif":"imagens/jan_mini_off.gif";
	    img1.style.cursor = m?"hand":"";
	    m = kp & s;
	    kp >>= 1;
	    img2.src = m?"imagens/jan_max_on.gif":"imagens/jan_max_off.gif";
	    img2.style.cursor = m?"hand":"";		
	    m = kp & s;
	    kp >>= 1;
	    img3.src = m?"imagens/jan_fechar_on.gif":"imagens/jan_fechar_off.gif";
	    img3.style.cursor = m?"hand":"";		
	  }
	  this.setJanBotoes = setJanBotoes;
  	  function setModal() {
	    JANS.isModal = 1;
	  /*
        cFt.firstChild.onmousedown = null;
        cFt.firstChild.onmouseup = null;
        cFt.firstChild.onmousemove = null;
        cFt.firstChild.onmouseout = null;
		*/
	  }
	  this.setModal = setModal;
	  function setNoModal() {
	    JANS.isModal = 0;
	  /*
        cFt.firstChild.onmousedown = function (event) { js_engage(janP, event); };
        cFt.firstChild.onmouseup = function (event) { js_release(janP, event); };
        cFt.firstChild.onmousemove = function (event) { js_dragIt(janP, event); };
        cFt.firstChild.onmouseout = function (event) { js_release(janP, event); };
		*/
	  }
	  this.setNoModal = setNoModal;
	}//fim da classe janela		



// FUNCAO DE VALIDACAOM USADO PELA FUNCAO db_text() do php
//variavel tipo:
// 1 s� pode numeros
// 2 s� pode letras
// 3 pode numeros, letras, espa�o, virgula
// 4 s� pode n�mero do tip� ponto flutuante
function js_ValidaCamposText(obj,tipo) {
  if(tipo == 4) {
    var expr = new RegExp("[^0-9\.]+");
    if(obj.value.match(expr)) {
	  alert("Este campo deve ser preenchido s�mente com n�meros decimais!");
	  obj.select();	
	}
  } else if(tipo == 1) {
    var expr = new RegExp("[^0-9]+");
    if(obj.value.match(expr)) {
	  alert("Este campo deve ser preenchido s�mente com n�meros!");
	  obj.select();	
	}
  } else if(tipo == 2) {
    var expr = new RegExp("[^A-Za-z�-��-���]+");
    if(obj.value.match(expr)) {
	  alert("Este campo deve ser preenchido s�mente com Letras!");
	  obj.select();	
	}  
  } else if(tipo == 3) {
    var expr = new RegExp("[^A-Za-z0-9�-��-��� \.,;:@&%-\_]+");
	if(obj.value.match(expr)) {
	  alert("Este campo deve ser preenchido s�mente com Letras, n�meros, espa�o, virgula, ponto-e-virgula, h�fen,2 pontos,arroba,sublinhado!");
	  obj.select();	
	}  
  }
}

////////////////////////////////////
function js_ValidaMaiusculo(obj,maiusculo,evt) {
  evt = (evt)?evt:(event)?event:'';
  if(evt.keyCode < 37 || evt.keyCode > 40){
    if(maiusculo =='t'){
      var maiusc = new String(obj.value);
  	  obj.value = maiusc.toUpperCase();
    }
  }
}
////////////////////////////////////
function js_ValidaCampos(obj,tipo,nome,aceitanulo,maiusculo,evt) {
  evt = (evt)?evt:(event)?event:'';
  if(evt.keyCode < 37 || evt.keyCode > 40){
  
  if(maiusculo =='t'){
    var maiusc = new String(obj.value);
	obj.value = maiusc.toUpperCase();
  }
  if(obj.value ==''){
    if(aceitanulo!='t'){
	  alert(nome+' dever� ser preenchido');
	  obj.select();	
	  obj.focus();	
	}
  }
  if(tipo == 1) {
    var expr = new RegExp("[^0-9]+");
    if(obj.value.match(expr)) {
	  alert(nome+" deve ser preenchido s�mente com n�meros!");
	  obj.select();	
	  obj.focus();	
	}
  } else if(tipo == 2) {
    var expr = new RegExp("[^A-Za-z�-��-���]+");
    if(obj.value.match(expr)) {
	  alert(nome+" deve ser preenchido s�mente com Letras!");
	  obj.select();	
	  obj.focus();	
	}  
  } else if(tipo == 3) {
    var expr = new RegExp("[^A-Za-z0-9�-��-��� \.,;:@&%-\_]+");
	if(obj.value.match(expr)) {
	  alert(nome+" deve ser preenchido s�mente com Letras, n�meros, espa�o, virgula, ponto-e-virgula, h�fen,2 pontos,arroba,sublinhado!");
	  obj.select();	
	  obj.focus();	
	}  
  } else  if(tipo == 4) {
    var expr = new RegExp("[^0-9\.]+");
    if(obj.value.match(expr)) {
	  alert(nome+" deve ser preenchido s�mente com n�meros decimais!");
	  obj.select();	
	  obj.focus();	
	}
  } else  if(tipo == 5) {
    var expr = new RegExp("fmFM");
    if(obj.value.match(expr)) {
	  alert(nome+" deve ser preenchido s�mente com falso ou verdadeiro!");
	  obj.select();	
	  obj.focus();	
	}
  }
  
  }
}

////////////////////////////////////
///////////////////////////////////
//FUNCOES PARA A FUNCAO DB_DATA DO PHP. VALIDAM A DATA E PASSA O FOCO PRO OUTRO CAMPO
function js_VerDaTa(nome,Dia,Mes,Ano) {
  var data = new Date(Ano,Mes,Dia);
  var F = document.form1;
  var str = new String(F.elements[nome].value);  
  if(nome.indexOf("dia") != -1) {	    	   
    var expr = new RegExp("[0-"+(data.getMonth()==1?2:3)+"][0-9]");
    var dia = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
    if(str.match(expr) == null || str > dia[data.getMonth()] || str == "00") {
      alert("Dia inv�lido!");
      F.elements[nome].select();
      return false;
    } else
      return true;
  } else if(nome.indexOf("mes") != -1) {
    var expr = new RegExp("[01][0-9]");	  
    if(str.match(expr) == null || str > 12 || str == 00) {
      alert("Mes inv�lido");
      F.elements[nome].select();
      return false;
    } else
      return true;
  } else if(nome.indexOf("ano") != -1) {
    var expr = new RegExp("[12][0-9][0-9][0-9]");
    if(str.match(expr) == null) {
      alert("Ano inv�lido");
      F.elements[nome].select();
      return false;
    } else
      return true;
  } else
    alert("Erro fatal na fun��o de verifica��o de datas!!!!");
}
ContrlDigitos = 0;
function js_getIndex(F,nome) {
  for(var i = 0;i < F.elements.length;i++)		  
    if(F.elements[i].name == nome) {
      var index = i;
      break;
    }		    
  return index;
}
function js_Passa(nome,Dia,Mes,Ano,evt) {
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  var F = document.form1;
  var index = -1;
  if(evt.keyCode == 37) {  
    index = js_getIndex(F,nome) - 1;	

  } else if(evt.keyCode == 39) {
    index = js_getIndex(F,nome) + 1;
  } else if(++ContrlDigitos >= F.elements[nome].size && F.elements[nome].value.length == F.elements[nome].size && js_VerDaTa(nome,Dia,Mes,Ano) == true) {
    ContrlDigitos = 0;
    index = js_getIndex(F,nome) + 1;
  }

  if(index != -1) {
    try {	
      F.elements[index].select();
    } catch(e) {
      F.elements[index].focus();
    }
  }
}
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////


//verifica se o elemento existe no array
function js_in_array(elem,vetor) {
  for(var i = 0;i < vetor.length;i++) {
    if(vetor[i] == elem)
	  return true;
  }
  return false;
}

//tipo o parse int, s� que pega o numero se tiver na final da straing tb!!
function js_parse_int(str) {
  var num = new Array("0","1","2","3","4","5","6","7","8","9");
  var tam = str.length;
  var aux = "";
  for(var i = 0;i < tam;i++) {
    if(js_in_array(str.substr(i,1),num))
	  aux += str.substr(i,1);
  }
  return aux;
}

//funcao para a funcao db_getfile() do php.
//pega o caminho na variavel nome e bota o seu basename no campo seguinte.
function js_preencheCampo(nome,campo) {
  var ind = (nome.lastIndexOf('\\') == -1?nome.lastIndexOf('/'):nome.lastIndexOf('\\'));
  ind += 1;
  var valor = "";
  var aux = new String(nome.substr(ind));
  for(var i = 0; i < aux.length;i++)
    if(aux.substr(i,1) == " ")
	  valor += "_";
	else
	  valor += aux.substr(i,1);
  document.form1.elements[campo].value = valor;
}

//Abre uma janela com valores, e retorna o selecionado
//Parametros:
//0 nome da lista(arquivo)
//1 algum parametro opcional
//2 posicao X da janela
//3 posicao Y da janela
//4 largura da janela
//5 altura da janela
function js_lista() {
  var args = js_lista.arguments;
  var X = (typeof(args[3])=="undefined" || args[3]=="")?100:args[3];
  var Y = (typeof(args[4])=="undefined" || args[4]=="")?100:args[4];
  var W = (typeof(args[5])=="undefined" || args[5]=="")?400:args[5];
  var E = (typeof(args[6])=="undefined" || args[6]=="")?420:args[6];
  jan = window.open(args[0] + '?arg=' + args[1] + '&campo=' + args[2],'','width='+W+',height='+E+',location=0,scrollbars=1,resizable=1');
  jan.moveTo(X,Y);
}
function js_lista_blur() {
  var args = js_lista_blur.arguments;
  if(document.form1.elements['db_' + args[2]].value != '' && document.form1.elements['db_' + args[8]].value != '' ){
    document.form1.elements['db_' + args[2]].value = '';
	document.form1.elements['db_' + args[8]].value = '';
	return false;
  }  
  var X = (typeof(args[3])=="undefined" || args[3]=="")?100:args[3];
  var Y = (typeof(args[4])=="undefined" || args[4]=="")?100:args[4];
  var W = (typeof(args[5])=="undefined" || args[5]=="")?400:args[5];
  var E = (typeof(args[6])=="undefined" || args[6]=="")?420:args[6];
  var L = (typeof(args[9])=="undefined")?"":args[9];
  jan = window.open(args[0] + '?arg=' + document.form1.elements['db_' + args[2]].value + '&campo=' + args[2] +'&argaux=' + document.form1.elements['db_' + args[8]].value + '&campoaux=' + args[8] + '&lista=' + L ,'','width='+W+',height='+E+',location=0,scrollbars=1,resizable=1');
  jan.moveTo(X,Y); 
}

///Pega o indice do campo e passa o foco pro campo seguinte
function js_Ipassacampo() {
  if(document.forms[0]) {
    for(var i = 0;i < document.forms[0].elements.length;i++)
      document.forms[0].elements[i].onkeyup = js_passacampo;
  }
}
function js_passacampo(evt) {
  evt = (evt) ? evt : (window.event) ? window.event : "";
  if(evt.keyCode == 13) {  
    var campo = (evt.srcElement)?evt.srcElement.name:evt.target.name;	
    for(var i = 0;i < document.forms[0].elements.length;i++) {
	  if(document.forms[0].elements[i].name == campo) {
	    var indice = i + 1;
		break;
	  }
	}
	document.forms[0].elements[indice].focus();    
  }
}
//window.onload=js_Ipassacampo;
///////////////////////////////////////////////

//Cria uma mensagem na barra de status.
function js_msg_status(msg) {
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;' + msg;    
}
//Limpa a barra de status.
function js_lmp_status() {
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;';
}

//Cria uma mensagem na barra de status.
function js_msg_status_data(msg) {
  parent.bstatus.document.getElementById('dthr').innerHTML = '&nbsp;&nbsp;' + msg;    
}
//Limpa a barra de status.
function js_lmp_status_data() {
  parent.bstatus.document.getElementById('dthr').innerHTML = '&nbsp;&nbsp;';
}



//pesquisa uma string dentro de um campo select
//para usar, use:
//<input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.itens)" size="35">
function js_pesquisa(arg,obj,evt) {
  evt = (evt) ? evt : (window.event) ? window.event : "";
  var tecla = evt.keyCode;
  var F = obj;
  if(tecla == 38 || tecla == 40)
    F.focus();
  else {    
    var tamvet = F.length;
    var tamarg = arg.length;
    for(var i = 0;i < tamvet;i++) {
      var texto = F.options[i].text.toLowerCase();
  	  var ajuda = new String(F.options[i].value.substr(F.options[i].value.search('##') + 2));
	  ///// gambiarra pra pegar uma substring, porque o search n�o acha o ||
	  aux = "";
	  for(var j = 0;j < ajuda.length;j++) {
	    if(ajuda.substr(j,1) != '|')
		  aux += ajuda.substr(j,1);
		else
		  break;
	  }
	  ajuda = aux;
	  /////
  	  if(arg.substr(0,tamarg) == texto.substr(0,tamarg)) {
	    F.options[i].selected = true;
	    js_msg_status(ajuda);
	    break;
	  }
    }
  }
}

function js_trocacordeselect() {
  if(document.form1) {
    var CorF1 = "#F8EC07";
    for(i = 0;i < document.form1.elements.length;i++) {
	  var str = new String(document.form1.elements[i].type);
      if(str.indexOf("select-") != -1) {
	    for(j = 0;j < document.form1.elements[i].length;j++) {
	      document.form1.elements[i].options[j].style.backgroundColor = CorF1 = (CorF1=="#D7CC06"?"#F8EC07":"#D7CC06");
  	    }
	  }
    }
  }
}


function js_Calcular_Peso(iPosicao, iPeso) {
return (iPosicao % (iPeso - 1)) + 2;
}


function js_CalculaDV(sCampo, iPeso){
	
	var iTamCampo;
	var iPosicao, iDigito;
	var iSoma1 = 0;
	var iSoma2=0;
	var iDV1, iDV2;
		
	iTamCampo = sCampo.length;

	for (iPosicao=1; iPosicao<=iTamCampo; iPosicao++){
		iDigito = sCampo.substr(iPosicao-1, 1);
		iSoma1 = parseInt(iSoma1,10) + parseInt((iDigito * js_Calcular_Peso(iTamCampo - iPosicao, iPeso)),10);
		iSoma2 = parseInt(iSoma2,10) + parseInt((iDigito * js_Calcular_Peso(iTamCampo - iPosicao + 1, iPeso)),10);
		}

	iDV1 = 11 - (iSoma1 % 11);
	if (iDV1 > 9)
		iDV1 = 0;

	iSoma2 = iSoma2 + (iDV1 * 2);
	iDV2 = 11 - (iSoma2 % 11);
	if (iDV2 > 9)
		iDV2 = 0;

	Ret = (parseInt(iDV1 * 10,10) + parseInt(iDV2));

	Ret = "0" + Ret;
	Ret = Ret.substr(Ret.length - 2,Ret.length);
		
	return(Ret);
}

// funcao de data
// onclick="show_calendar('form1.calend1')"


var weekend = [0,6];
var weekendColor = "#e0e0e0";
var fontface = "Verdana";
var fontsize = 1;


var img_esq = "/workflow/images/seta_esq.gif";
var img_dir = "/workflow/images/seta_dir.gif";

var gNow = new Date();
var ggWinCal;
isNav = (navigator.appName.indexOf("Netscape") != -1) ? true : false;
isIE = (navigator.appName.indexOf("Microsoft") != -1) ? true : false;

Calendar.Months = ["Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho",
"Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

//dias finais de cada mes 
Calendar.DOMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
// anos bissestos
Calendar.lDOMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

function Calendar(p_item, p_WinCal, p_month, p_year, p_format) {
	if ((p_month == null) && (p_year == null))	return;

	if (p_WinCal == null)
		this.gWinCal = ggWinCal;
	else
		this.gWinCal = p_WinCal;

	if (p_month == null) {
		this.gMonthName = null;
		this.gMonth = null;
		this.gYearly = true;
	} else {
		this.gMonthName = Calendar.get_month(p_month);
		this.gMonth = new Number(p_month);
		this.gYearly = false;
	}

	this.gYear = p_year;
	this.gFormat = p_format;
	this.gBGColor = "white";
	this.gFGColor = "black";
	this.gTextColor = "black";
	this.gHeaderColor = "black";
	this.gReturnItem = p_item;
}

Calendar.get_month = Calendar_get_month;
Calendar.get_daysofmonth = Calendar_get_daysofmonth;
Calendar.calc_month_year = Calendar_calc_month_year;
Calendar.print = Calendar_print;

function Calendar_get_month(monthNo) {
	return Calendar.Months[monthNo];
}

function Calendar_get_daysofmonth(monthNo, p_year) {
	if ((p_year % 4) == 0) {
		if ((p_year % 100) == 0 && (p_year % 400) != 0)
			return Calendar.DOMonth[monthNo];

		return Calendar.lDOMonth[monthNo];
	} else
		return Calendar.DOMonth[monthNo];
}

function Calendar_calc_month_year(p_Month, p_Year, incr) {
	var ret_arr = new Array();

	if (incr == -1) {
		// B A C K W A R D
		if (p_Month == 0) {
			ret_arr[0] = 11;
			ret_arr[1] = parseInt(p_Year) - 1;
		}
		else {
			ret_arr[0] = parseInt(p_Month) - 1;
			ret_arr[1] = parseInt(p_Year);
		}
	} else if (incr == 1) {
		// F O R W A R D
		if (p_Month == 11) {
			ret_arr[0] = 0;
			ret_arr[1] = parseInt(p_Year) + 1;
		}
		else {
			ret_arr[0] = parseInt(p_Month) + 1;
			ret_arr[1] = parseInt(p_Year);
		}
	}

	return ret_arr;
}

function Calendar_print() {
	ggWinCal.print();
}

function Calendar_calc_month_year(p_Month, p_Year, incr) {
	var ret_arr = new Array();

	if (incr == -1) {
		// B A C K W A R D
		if (p_Month == 0) {
			ret_arr[0] = 11;
			ret_arr[1] = parseInt(p_Year) - 1;
		}
		else {
			ret_arr[0] = parseInt(p_Month) - 1;
			ret_arr[1] = parseInt(p_Year);
		}
	} else if (incr == 1) {
		// F O R W A R D
		if (p_Month == 11) {
			ret_arr[0] = 0;
			ret_arr[1] = parseInt(p_Year) + 1;
		}
		else {
			ret_arr[0] = parseInt(p_Month) + 1;
			ret_arr[1] = parseInt(p_Year);
		}
	}

	return ret_arr;
}

new Calendar();

Calendar.prototype.getMonthlyCalendarCode = function() {
	var vCode = "";
	var vHeader_Code = "";
	var vData_Code = "";

	// Begin Table Drawing code here..
	vCode = vCode + "<TABLE BORDER=0 BGCOLOR=\"" + this.gBGColor + "\">";

	vHeader_Code = this.cal_header();
	vData_Code = this.cal_data();
	vCode = vCode + vHeader_Code + vData_Code;

	vCode = vCode + "</TABLE>";

	return vCode;
}

Calendar.prototype.show = function() {
	var vCode = "";

	this.gWinCal.document.open();



	this.wwrite("<html>");
	this.wwrite("<head><title>Calendar</title>");
	this.wwrite("</head>");

	this.wwrite("<body marginwidth=0 marginheight=0 topmargin=0 leftmargin=0 " +
		"link=\"" + this.gLinkColor + "\" " +
		"vlink=\"" + this.gLinkColor + "\" " +
		"alink=\"" + this.gLinkColor + "\" " +
		"text=\"" + this.gTextColor + "\">");
	this.wwriteA("<FONT FACE='" + fontface + "' size=1><B>");
	//this.wwriteA(this.gMonthName + " " + this.gYear);
	this.wwriteA("</B>");

	// Show navigation buttons
	var prevMMYYYY = Calendar.calc_month_year(this.gMonth, this.gYear, -1);
	var prevMM = prevMMYYYY[0];
	var prevYYYY = prevMMYYYY[1];

	var nextMMYYYY = Calendar.calc_month_year(this.gMonth, this.gYear, 1);
	var nextMM = nextMMYYYY[0];
	var nextYYYY = nextMMYYYY[1];

	this.wwrite("<TABLE WIDTH='100%' BORDER=0 CELLSPACING=2 CELLPADDING=0 ><TR><TD ALIGN=center>");

	this.wwrite("<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', '" + this.gMonth + "', '" + (parseInt(this.gYear)-1) + "', '" + this.gFormat + "'" +
		");" +  
		"\"> &lt;&lt; ");
	this.wwrite("<FONT FACE='" + fontface + "' size=1><B>" + this.gYear);
	this.wwrite("<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', '" + this.gMonth + "', '" + (parseInt(this.gYear)+1) + "', '" + this.gFormat + "'" +
		");" +
		"\"> &gt;&gt; <\/A></TD></TR><tr><td align=center>");

	this.wwrite("<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', '" + prevMM + "', '" + prevYYYY + "', '" + this.gFormat + "'" +
		");" +
		"\"> &lt;&lt; <\/A>");
	this.wwrite("<FONT FACE='" + fontface + "' size=1><B>" +this.gMonthName);
	this.wwrite("<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', '" + nextMM + "', '" + nextYYYY + "', '" + this.gFormat + "'" +
		");" +
		"\"> &gt;&gt; <\/A></TD></tr><table>");


	// Get the complete calendar code for the month..
	vCode = this.getMonthlyCalendarCode();
	this.wwrite(vCode);

	this.wwrite("</font>");	
	
	this.wwrite("</body></html>");

	this.gWinCal.document.close();
}

Calendar.prototype.showY = function() {
	var vCode = "";
	var i;
	var vr, vc, vx, vy;		// Row, Column, X-coord, Y-coord
	var vxf = 285;			// X-Factor
	var vyf = 200;			// Y-Factor
	var vxm = 10;			// X-margin
	var vym;				// Y-margin
	if (isIE)	vym = 75;
	else if (isNav)	vym = 25;

	this.gWinCal.document.open();

	this.wwrite("<html>");
	this.wwrite("<head><title>Calendar</title>");
	this.wwrite("<style type='text/css'>\n<!--");
	for (i=0; i<12; i++) {
		vc = i % 3;
		if (i>=0 && i<= 2)	vr = 0;
		if (i>=3 && i<= 5)	vr = 1;
		if (i>=6 && i<= 8)	vr = 2;
		if (i>=9 && i<= 11)	vr = 3;

		vx = parseInt(vxf * vc) + vxm;
		vy = parseInt(vyf * vr) + vym;

		this.wwrite(".lclass" + i + " {position:absolute;top:" + vy + ";left:" + vx + ";}");
	}
	this.wwrite("-->\n</style>");
	this.wwrite("</head>");

	this.wwrite("<body " +
		"link=\"" + this.gLinkColor + "\" " +
		"vlink=\"" + this.gLinkColor + "\" " +
		"alink=\"" + this.gLinkColor + "\" " +
		"text=\"" + this.gTextColor + "\">");
	this.wwrite("<FONT FACE='" + fontface + "' SIZE=2><B>");
	this.wwrite("Year : " + this.gYear);
	this.wwrite("</B><BR>");

	// Show navigation buttons
	var prevYYYY = parseInt(this.gYear) - 1;
	var nextYYYY = parseInt(this.gYear) + 1;

	this.wwrite("<TABLE WIDTH='100%' BORDER=1 CELLSPACING=0 CELLPADDING=0 BGCOLOR='#e0e0e0'><TR><TD ALIGN=center>");
	this.wwrite("[<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', null, '" + prevYYYY + "', '" + this.gFormat + "'" +
		");" +
		"\" alt='Prev Year'><<<\/A>]</TD><TD ALIGN=center>");
	this.wwrite("[<A HREF=\"javascript:window.print();\">Print</A>]</TD><TD ALIGN=center>");
	this.wwrite("[<A HREF=\"" +
		"javascript:parent.Build(" +
		"'" + this.gReturnItem + "', null, '" + nextYYYY + "', '" + this.gFormat + "'" +
		");" +
		"\">>><\/A>]</TD></TR></TABLE><BR>");

	// Get the complete calendar code for each month..
	var j;
	for (i=11; i>=0; i--) {
		if (isIE)
			this.wwrite("<DIV ID=\"layer" + i + "\" CLASS=\"lclass" + i + "\">");
		else if (isNav)
			this.wwrite("<LAYER ID=\"layer" + i + "\" CLASS=\"lclass" + i + "\">");

		this.gMonth = i;
		this.gMonthName = Calendar.get_month(this.gMonth);
		vCode = this.getMonthlyCalendarCode();
		this.wwrite(this.gMonthName + "/" + this.gYear + "<BR>");
		this.wwrite(vCode);

		if (isIE)
			this.wwrite("</DIV>");
		else if (isNav)
			this.wwrite("</LAYER>");
	}

	this.wwrite("</font><BR></body></html>");
	this.gWinCal.document.close();
}

Calendar.prototype.wwrite = function(wtext) {
	this.gWinCal.document.writeln(wtext);
}

Calendar.prototype.wwriteA = function(wtext) {
	this.gWinCal.document.write(wtext);
}

Calendar.prototype.cal_header = function() {
	var vCode = "";

	vCode = vCode + "<TR><CENTER>";

    vCode = vCode + 
	            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT SIZE='1' FACE='" + fontface + "'>" +
				"<A HREF='#' " +
					"onClick=\"parent.document." + 
					this.gReturnItem + 
					"_dia.value='';parent.document." + 
					this.gReturnItem + 
					"_mes.value='';parent.document." + 
					this.gReturnItem +
					"_ano.value='';parent.DataJavaScript.hide();return false\">" +'Zera Data' +
				"</A>" +
				"</FONT>";

	vCode = vCode + "</CENTER></TR>";
	
	vCode = vCode + "<TR>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>D</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>S</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>T</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>Q</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>Q</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='14%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>S</B></FONT></TD>";
	vCode = vCode + "<TD WIDTH='16%'><FONT SIZE='1' FACE='" + fontface + "' COLOR='" + this.gHeaderColor + "'><B>S</B></FONT></TD>";
	vCode = vCode + "</TR>";

	return vCode;
}

Calendar.prototype.cal_data = function() {
	var vDate = new Date();
	vDate.setDate(1);
	vDate.setMonth(this.gMonth);
	vDate.setFullYear(this.gYear);

	var vFirstDay=vDate.getDay();
	var vDay=1;
	var vLastDay=Calendar.get_daysofmonth(this.gMonth, this.gYear);
	var vOnLastDay=0;
	var vCode = "";

	/*
	Get day for the 1st of the requested month/year..
	Place as many blank cells before the 1st day of the month as necessary.
	*/

	vCode = vCode + "<TR>";
	for (i=0; i<vFirstDay; i++) {
		vCode = vCode + "<TD WIDTH='14%'" + this.write_weekend_string(i) + "><FONT SIZE='1' FACE='" + fontface + "'> </FONT></TD>";
	}

	// Write rest of the 1st week
	for (j=vFirstDay; j<7; j++) {
		vCode = vCode + "<TD WIDTH='14%'" + this.write_weekend_string(j) + "><FONT SIZE='1' FACE='" + fontface + "'>" +
			"<A HREF='#' " +
					"onClick=\"parent.document." + this.gReturnItem + "_dia.value='" +	this.format_data(vDay,'d') +					"';parent.document." + this.gReturnItem + "_mes.value='" + this.format_data(vDay,'m') +					"';parent.document." + this.gReturnItem + "_ano.value='" +	this.format_data(vDay,'y') +					"';parent.DataJavaScript.hide();return false\">" +				this.format_day(vDay) +
			"</A>" +
			"</FONT></TD>";
		vDay=vDay + 1;
	}
	vCode = vCode + "</TR>";

	// Write the rest of the weeks
	for (k=2; k<7; k++) {
		vCode = vCode + "<TR>";

		for (j=0; j<7; j++) {
			vCode = vCode + "<TD WIDTH='14%'" + this.write_weekend_string(j) + "><FONT SIZE='1' FACE='" + fontface + "'>" +
				"<A HREF='#' " +
					"onClick=\"parent.document." + this.gReturnItem + "_dia.value='" +	this.format_data(vDay,'d') +					"';parent.document." + this.gReturnItem + "_mes.value='" + this.format_data(vDay,'m') +					"';parent.document." + this.gReturnItem + "_ano.value='" +	this.format_data(vDay,'y') +					"';parent.DataJavaScript.hide();return false\">" +				this.format_day(vDay) +
				"</A>" +
				"</FONT></TD>";
			vDay=vDay + 1;

			if (vDay > vLastDay) {
				vOnLastDay = 1;
				break;
			}
		}

		if (j == 6)
			vCode = vCode + "</TR>";
		if (vOnLastDay == 1)
			break;
	}

	// Fill up the rest of last week with proper blanks, so that we get proper square blocks
	for (m=1; m<(7-j); m++) {
		if (this.gYearly)
			vCode = vCode + "<TD WIDTH='14%'" + this.write_weekend_string(j+m) +
			"><FONT SIZE='1' FACE='" + fontface + "' COLOR='gray'> </FONT></TD>";
		else
			vCode = vCode + "<TD WIDTH='14%'" + this.write_weekend_string(j+m) +
			"><FONT SIZE='1' FACE='" + fontface + "' COLOR='gray'>" + m + "</FONT></TD>";
	}

     
	return vCode;
}

Calendar.prototype.format_day = function(vday) {
	var vNowDay = gNow.getDate();
	var vNowMonth = gNow.getMonth();
	var vNowYear = gNow.getFullYear();

	if (vday == vNowDay && this.gMonth == vNowMonth && this.gYear == vNowYear)
		return ("<FONT COLOR=\"RED\"><B>" + vday + "</B></FONT>");
	else
		return (vday);
}

Calendar.prototype.write_weekend_string = function(vday) {
	var i;

	// Return special formatting for the weekend day.
	for (i=0; i<weekend.length; i++) {
		if (vday == weekend[i])
			return (" BGCOLOR=\"" + weekendColor + "\"");
	}

	return "";
}

Calendar.prototype.format_data = function(p_day,qual) {
	var vData;
	var vMonth = 1 + this.gMonth;
	vMonth = (vMonth.toString().length < 2) ? "0" + vMonth : vMonth;
	var vMon = Calendar.get_month(this.gMonth).substr(0,3).toUpperCase();
	var vFMon = Calendar.get_month(this.gMonth).toUpperCase();
	var vY4 = new String(this.gYear);
	var vY2 = new String(this.gYear.substr(2,2));
	var vDD = (p_day.toString().length < 2) ? "0" + p_day : p_day;

    if(qual =='d'){
	  return vDD;
	}else if(qual == 'm'){
	  return vMonth	;
	}else if(qual == 'y'){
	  return vY4;	
	}else{
	switch (this.gFormat) {
		case "MM\/DD\/YYYY" :
			vData = vMonth + "\/" + vDD + "\/" + vY4;
			break;
		case "MM\/DD\/YY" :
			vData = vMonth + "\/" + vDD + "\/" + vY2;
			break;
		case "MM-DD-YYYY" :
			vData = vMonth + "-" + vDD + "-" + vY4;
			break;
		case "MM-DD-YY" :
			vData = vMonth + "-" + vDD + "-" + vY2;
			break;

		case "DD\/MON\/YYYY" :
			vData = vDD + "\/" + vMon + "\/" + vY4;
			break;
		case "DD\/MON\/YY" :
			vData = vDD + "\/" + vMon + "\/" + vY2;
			break;
		case "DD-MON-YYYY" :
			vData = vDD + "-" + vMon + "-" + vY4;
			break;
		case "DD-MON-YY" :
			vData = vDD + "-" + vMon + "-" + vY2;
			break;

		case "DD\/MONTH\/YYYY" :
			vData = vDD + "\/" + vFMon + "\/" + vY4;
			break;
		case "DD\/MONTH\/YY" :
			vData = vDD + "\/" + vFMon + "\/" + vY2;
			break;
		case "DD-MONTH-YYYY" :
			vData = vDD + "-" + vFMon + "-" + vY4;
			break;
		case "DD-MONTH-YY" :
			vData = vDD + "-" + vFMon + "-" + vY2;
			break;

		case "DD\/MM\/YYYY" :
			vData = vDD + "\/" + vMonth + "\/" + vY4;
			break;
		case "DD\/MM\/YY" :
			vData = vDD + "\/" + vMonth + "\/" + vY2;
			break;
		case "DD-MM-YYYY" :
			vData = vDD + "-" + vMonth + "-" + vY4;
			break;
		case "YYYY-MM-DD" :
			vData = vY4 + "-" + vMonth + "-" + vDD;
			break;
		case "DD-MM-YY" :
			vData = vDD + "-" + vMonth + "-" + vY2;
			break;

		default :
			vData = vMonth + "\/" + vDD + "\/" + vY4;
	 }
    }

	return vData;
}

function Build(p_item, p_month, p_year, p_format) {
	var p_WinCal = ggWinCal;
	gCal = new Calendar(p_item, p_WinCal, p_month, p_year, p_format);

	// Customize your Calendar here..
	gCal.gBGColor="white";
	gCal.gLinkColor="black";
	gCal.gTextColor="black";
	gCal.gHeaderColor="darkgreen";

	// Choose appropriate show function
	if (gCal.gYearly)	gCal.showY();
	else	gCal.show();
}

function pegaPosMouse(evt) {
//  evt = (evt) ? evt : (window.event) ? window.event : "NAO DEU CERTO O EVENTO";
  if( typeof(event) != "object" ) {
    PosMouseX = evt.layerX;
    PosMouseY = evt.layerY;
  } else {
    PosMouseX = event.x;
    PosMouseY = event.y;	  
  }
}
function show_calendar() {

  	/*
		p_month : 0-11 for Jan-Dec; 12 for All Months.
		p_year	: 4-digit year
		p_format: Date format (mm/dd/yyyy, dd/mm/yy, ...)
		p_item	: Return Item.
	*/
	if(PosMouseY >= 270)
	  PosMouseY = 270;
	if(PosMouseX >= 600)
	  PosMouseX = 600;
	DataJavaScript.setPosX(PosMouseX + 20);
	DataJavaScript.setPosY(PosMouseY - 30);
	
    DataJavaScript.show();
	p_item = arguments[0];
	if (arguments[1] == null)
		p_month = new String(gNow.getMonth());
	else
		p_month = arguments[1];
	if (arguments[2] == "" || arguments[2] == null)
		p_year = new String(gNow.getFullYear().toString());
	else
		p_year = arguments[2];
	if (arguments[3] == null)
		p_format = "DD/MM/YYYY";
	else
		p_format = arguments[3];
/*
	vWinCal = window.open("", "DataJavaScript.",
		"width=130,height=150,status=no,resizable=no,top=200,left=200");
*/
    DataJavaScript.jan.target = "DataJavaScript";
//	vWinCal.opener = self;
//	ggWinCal = vWinCal;
    ggWinCal = DataJavaScript.jan;

	Build(p_item, p_month, p_year, p_format);
}
/*
Yearly Calendar Code Starts here
*/
function show_yearly_calendar(p_item, p_year, p_format) {
	// Load the defaults..
	if (p_year == null || p_year == "")
		p_year = new String(gNow.getFullYear().toString());
	if (p_format == null || p_format == "")
		p_format = "DD/MM/YYYY";

	var vWinCal = window.open("", "Calendar", "scrollbars=yes");
	vWinCal.opener = self;
	ggWinCal = vWinCal;

	Build(p_item, null, p_year, p_format);
}




//*****************************************************************************
// INI Do not remove this notice.
//
// Copyright 2000 by Mike Hall.
// See http://www.brainjar.com for terms of use.
//*****************************************************************************

//----------------------------------------------------------------------------
// Code to determine the browser and version.
//----------------------------------------------------------------------------
function js_hideshowselect(v) {
  if(document.forms.length > 0) {
    for(var i = 0;i < document.forms.length;i++) {
      var tam = document.forms[i].elements.length;
      for(var j = 0;j < tam;j++) {
  	    try {	
          var str = new String(document.forms[i].elements[j].type);
        } catch(e) {
          var str = "";
        }
		if(str.indexOf("select") != -1) {
          document.forms[i].elements[j].style.visibility = v;
	    }
	  }
    }
  }
  var fram = (frames.length==0)?1:frames.length;
  for(var x = 0;x < fram;x++) {
    var F = (frames.length > 0)?(frames[x].document.forms):(document.forms);
    var qf = F.length;
    for(var i = 0;i < qf;i++) {
      var tam = F[i].elements.length;
      for(var j = 0;j < tam;j++) {
  	    try {	
          var str = new String(F[i].elements[j].type);
        } catch(e) {
          var str = "";
        }
		if(str.indexOf("select") != -1) {
          F[i].elements[j].style.visibility = v;
	    }
	  }
    }
  }
}
function Browser() {

  var ua, s, i;

  this.isIE    = false;  // Internet Explorer
  this.isNS    = false;  // Netscape
  this.version = null;

  ua = navigator.userAgent;

  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isIE = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  s = "Netscape6/";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  // Treat any other "Gecko" browser as NS 6.1.

  s = "Gecko";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = 6.1;
    return;
  }
}

var browser = new Browser();

//----------------------------------------------------------------------------
// Code for handling the menu bar and active button.
//----------------------------------------------------------------------------

var activeButton = null;

// Capture mouse clicks on the page so any active button can be
// deactivated.

if (browser.isIE)
  document.onmousedown = pageMousedown;
else
  document.addEventListener("mousedown", pageMousedown, true);

function pageMousedown(event) {

  var el;

  // If there is no active button, exit.

  if (activeButton == null)
    return;

  // Find the element that was clicked on.

  if (browser.isIE)
    el = window.event.srcElement;
  else
    el = (event.target.tagName ? event.target : event.target.parentNode);

  // If the active button was clicked on, exit.

  if (el == activeButton)
    return;

  // If the element is not part of a menu, reset and clear the active
  // button.

  if (getContainerWith(el, "DIV", "menu") == null) {
    resetButton(activeButton,event);
    activeButton = null;
  }
}

function buttonClick(event, menuId) {
  if(!document.getElementById(menuId))
    return false;
  var button;
  js_hideshowselect('hidden');
  // Get the target button element.

  if (browser.isIE)
    button = window.event.srcElement;
  else
    button = event.currentTarget;

  // Blur focus from the link to remove that annoying outline.

  button.blur();

  // Associate the named menu to this button if not already done.
  // Additionally, initialize menu display.

  if (button.menu == null) {
    button.menu = document.getElementById(menuId);
    if (button.menu.isInitialized == null)
      menuInit(button.menu);
  }

  // Reset the currently active button, if any.

  if (activeButton != null)
    resetButton(activeButton,event);

  // Activate this button, unless it was the currently active one.

  if (button != activeButton) {
    depressButton(button);
    activeButton = button;
  }
  else
    activeButton = null;

  return false;
}

function buttonMouseover(event, menuId) {
  var button;

  // Find the target button element.

  if (browser.isIE)
    button = window.event.srcElement;
  else
    button = event.currentTarget;

  // If any other button menu is active, make this one active instead.

  if (activeButton != null && activeButton != button)
    buttonClick(event, menuId);
}

function depressButton(button) {

  var x, y;

  // Update the button's style class to make it look like it's
  // depressed.

  button.className += " menuButtonActive";

  // Position the associated drop down menu under the button and
  // show it.

  x = getPageOffsetLeft(button);
  y = getPageOffsetTop(button) + button.offsetHeight;

  // For IE, adjust position.

  if (browser.isIE) {
    x += button.offsetParent.clientLeft;
    y += button.offsetParent.clientTop;
  }

  button.menu.style.left = x + "px";
  button.menu.style.top  = y + "px";
  button.menu.style.visibility = "visible";
}

function resetButton(button,evt) {
  var evt = (evt) ? evt : (window.event) ? window.event : "sem evento";

  // Restore the button's style class.

  removeClassName(button, "menuButtonActive");

  // Hide the button's menu, first closing any sub menus.

  if (button.menu != null) {
    closeSubMenu(button.menu);
    button.menu.style.visibility = "hidden";
	if(evt.type != "mouseover")
      js_hideshowselect('visible');
  }
}

//----------------------------------------------------------------------------
// Code to handle the menus and sub menus.
//----------------------------------------------------------------------------

function menuMouseover(event) {
  var menu;
  // Find the target menu element.

  if (browser.isIE)
    menu = getContainerWith(window.event.srcElement, "DIV", "menu");
  else
    menu = event.currentTarget;

  // Close any active sub menu.

  if (menu.activeItem != null)
    closeSubMenu(menu);
}

function menuItemMouseover(event, menuId) {
  var item, menu, x, y;

  // Find the target item element and its parent menu element.

  if (browser.isIE)
    item = getContainerWith(window.event.srcElement, "A", "menuItem");
  else
    item = event.currentTarget;
  menu = getContainerWith(item, "DIV", "menu");

  // Close any active sub menu and mark this one as active.

  if (menu.activeItem != null)
    closeSubMenu(menu);
  menu.activeItem = item;

  // Highlight the item element.

  item.className += " menuItemHighlight";

  // Initialize the sub menu, if not already done.

  if (item.subMenu == null) {
    item.subMenu = document.getElementById(menuId);
    if (item.subMenu.isInitialized == null)
      menuInit(item.subMenu);
  }

  // Get position for submenu based on the menu item.

  x = getPageOffsetLeft(item) + item.offsetWidth;
  y = getPageOffsetTop(item);

  // Adjust position to fit in view.

  var maxX, maxY;

  if (browser.isNS) {
    maxX = window.scrollX + window.innerWidth;
    maxY = window.scrollY + window.innerHeight;
  }
  if (browser.isIE) {
    maxX = (document.documentElement.scrollLeft   != 0 ? document.documentElement.scrollLeft    : document.body.scrollLeft)
         + (document.documentElement.clientWidth  != 0 ? document.documentElement.clientWidth   : document.body.clientWidth);
    maxY = (document.documentElement.scrollTop    != 0 ? document.documentElement.scrollTop    : document.body.scrollTop)
         + (document.documentElement.clientHeight != 0 ? document.documentElement.clientHeight : document.body.clientHeight);
  }
  maxX -= item.subMenu.offsetWidth;
  maxY -= item.subMenu.offsetHeight;

  if (x > maxX)
    x = Math.max(0, x - item.offsetWidth - item.subMenu.offsetWidth
      + (menu.offsetWidth - item.offsetWidth));
  y = Math.max(0, Math.min(y, maxY));

  // Position and show it.

  item.subMenu.style.left = x + "px";
  item.subMenu.style.top  = y + "px";
  item.subMenu.style.visibility = "visible";

  // Stop the event from bubbling.

  if (browser.isIE)
    window.event.cancelBubble = true;
  else
    event.stopPropagation();
}

function closeSubMenu(menu) {

  if (menu == null || menu.activeItem == null)
    return;

  // Recursively close any sub menus.

  if (menu.activeItem.subMenu != null) {
    closeSubMenu(menu.activeItem.subMenu);
    menu.activeItem.subMenu.style.visibility = "hidden";
    menu.activeItem.subMenu = null;
  }
  removeClassName(menu.activeItem, "menuItemHighlight");
  menu.activeItem = null;
}

//----------------------------------------------------------------------------
// Code to initialize menus.
//----------------------------------------------------------------------------

function menuInit(menu) {

  var itemList, spanList;
  var textEl, arrowEl;
  var itemWidth;
  var w, dw;
  var i, j;

  // For IE, replace arrow characters.

  if (browser.isIE) {
    menu.style.lineHeight = "2.5ex";
    spanList = menu.getElementsByTagName("SPAN");
    for (i = 0; i < spanList.length; i++)
      if (hasClassName(spanList[i], "menuItemArrow")) {
        spanList[i].style.fontFamily = "Webdings";
        spanList[i].firstChild.nodeValue = "4";
      }
  }

  // Find the width of a menu item.

  itemList = menu.getElementsByTagName("A");
  if (itemList.length > 0)
    itemWidth = itemList[0].offsetWidth;
  else
    return;

  // For items with arrows, add padding to item text to make the
  // arrows flush right.

  for (i = 0; i < itemList.length; i++) {
    spanList = itemList[i].getElementsByTagName("SPAN");
    textEl  = null;
    arrowEl = null;
    for (j = 0; j < spanList.length; j++) {
      if (hasClassName(spanList[j], "menuItemText"))
        textEl = spanList[j];
      if (hasClassName(spanList[j], "menuItemArrow"))
        arrowEl = spanList[j];
    }
    if (textEl != null && arrowEl != null)
      textEl.style.paddingRight = (itemWidth 
        - (textEl.offsetWidth + arrowEl.offsetWidth)) + "px";
  }

  // Fix IE hover problem by setting an explicit width on first item of
  // the menu.

  if (browser.isIE) {
    w = itemList[0].offsetWidth;
    itemList[0].style.width = w + "px";
    dw = itemList[0].offsetWidth - w;
    w -= dw;
    itemList[0].style.width = w + "px";
  }

  // Mark menu as initialized.

  menu.isInitialized = true;
}

//----------------------------------------------------------------------------
// General utility functions.
//----------------------------------------------------------------------------

function getContainerWith(node, tagName, className) {

  // Starting with the given node, find the nearest containing element
  // with the specified tag name and style class.

  while (node != null) {
    if (node.tagName != null && node.tagName == tagName &&
        hasClassName(node, className))
      return node;
    node = node.parentNode;
  }

  return node;
}

function hasClassName(el, name) {

  var i, list;

  // Return true if the given element currently has the given class
  // name.

  list = el.className.split(" ");
  for (i = 0; i < list.length; i++)
    if (list[i] == name)
      return true;

  return false;
}

function removeClassName(el, name) {

  var i, curList, newList;

  if (el.className == null)
    return;

  // Remove the given class name from the element's className property.

  newList = new Array();
  curList = el.className.split(" ");
  for (i = 0; i < curList.length; i++)
    if (curList[i] != name)
      newList.push(curList[i]);
  el.className = newList.join(" ");  
}

function getPageOffsetLeft(el) {

  var x;

  // Return the x coordinate of an element relative to the page.

  x = el.offsetLeft;
  if (el.offsetParent != null)
    x += getPageOffsetLeft(el.offsetParent);

  return x;
}

function getPageOffsetTop(el) {

  var y;

  // Return the x coordinate of an element relative to the page.

  y = el.offsetTop;
  if (el.offsetParent != null)
    y += getPageOffsetTop(el.offsetParent);

  return y;
}
