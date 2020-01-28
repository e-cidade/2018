//verifica se o elemento existe no array
function js_in_array(elem,vetor) {
  for(var i = 0;i < vetor.length;i++) {
    if(vetor[i] == elem)
	  return true;
  }
  return false;
}

//tipo o parse int, só que pega o numero se tiver na final da straing tb!!
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

//document.oncontextmenu = new Function("return false");
function js_maiuscula(dbobjeto){
  var vstring = new String(dbobjeto.value);
  dbobjeto.value = vstring.toUpperCase();
}

function js_minuscula(dbobjeto){
  var vstring = new String(dbobjeto.value);
  dbobjeto.value = vstring.toLowerCase();
}

terminar = "";

function js_voltacor(valor) {

  window.clearInterval(terminar);

  document.form1[valor].style.backgroundColor = 'white';

  document.form1[valor].style.borderStyle = 'inset';

}

f = 0;

function js_trocacor(valor) {

    if(f == 0) {

      document.form1[valor].style.backgroundColor = 'black';

	  document.form1[valor].borderStyle = 'none';

	  f = 1;

    } else {

	  document.form1[valor].style.backgroundColor = 'green';

	  document.form1[valor].style.borderStyle = 'solid';

	  f = 0;

  }

}



function js_verificapagina(pagina){
  var pag = pagina.split(",");
  var existe = 0;
  var loc = new String(document.location);
  loc = loc.substring(0,loc.lastIndexOf("/")+1);
  var ref = new String(document.referrer);
//  alert(pagina + " <==> " + ref);
  for(i = 0;i < pag.length;i++) {

    if( ref.indexOf(loc+pag[i]) == 0 ) {
	  existe = 1;
	}
  }
  if(existe == 0) {
    //alert("Você esta acessando a página de uma URL inválida e será redirecionado.");
    //top.location.href = "index.php";
  }
}



function js_emiteboleto(alias,pagredirect) {
  var x = "";
  for ( i = 0; i < document.form1.totalregistros.value;i++ ){
    if (document.form1.elements[i].checked == true ) {
      if ( x != "" ){
        x = x + "+" ;
	  } 
	  x = x + document.form1.elements[i].value ;
    }
  }
  if(x == "")
    alert("Você deverá Selecionar os valores a emitir");
  else
    window.open("emiteboleto.php?alias="+alias+"&numpres="+ x,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
	document.href = pagredirect;
}

function js_selecionavar(alias,cod_inscr) {
  var x = "";
  var vx = "";
  if (document.form1.somahistorico.value != "" ) {
    for ( i = 0;i < document.form1.totalregistros.value;i ++ ){
      if ( document.form1.elements[i].value != "" ) {
	    if ( x != "" ) {    
		  x = x + "+" ;
		  vx = vx + "+";
		}
		x = x + document.form1.elements[i].name ;
        vx = vx + document.form1.elements[i].value ;
      }
	}
  }
  if( x == "")
    alert("Você deverá Selecionar Digitar os valores a Pagar");
  else {
    location.href = "pagaissvarsel.php?inscricao="+cod_inscr+"&alias="+alias+"&issvar="+ x + "&issvarvlr=" + vx;
  }
}

function js_emiteboletovar(alias) {
  var x = "";
  var xx = "";
  for ( i = 0; i < document.form1.totalregistros.value;i++ ){
    if (document.form1.elements[i].checked == true ) {
      if ( x != "" ){
        x = x + "+" ;
        xx = xx + "+" ;
	  } 
	  x = x + document.form1.elements[i].name ;
	  xx = xx + document.form1.elements[i].value ;
    }
  }
  if(x == "")
    alert("Você deverá Selecionar os valores a emitir");
  else
    window.open("emiteboleto.php?alias="+alias+"&issvar="+ x + "&issvarvlr=" + xx,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
}

function js_emiteboletovarold(alias) {

  var x = "";

  var vx = "";

  if (document.form1.somahistorico.value != "" ) {

    for ( i = 0;i < document.form1.totalregistros.value;i ++ ){

      if ( document.form1.elements[i].value != "" ) {

	    if ( x != "" ) {    

		  x = x + "+" ;

		  vx = vx + "+";

		}

		x = x + document.form1.elements[i].name ;

        vx = vx + document.form1.elements[i].value ;

      }

	}

  }

  

//  alert(x);

//  alert(vx);

  

  if( x == "")

    alert("Você deverá Selecionar Digitar os valores a Pagar");

  else {

    window.open("emiteboleto.php?alias="+alias+"&issvar="+ x + "&issvarvlr=" + vx,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));

  }

}
function js_seltodos(sn) {

  for ( i = 0; i < document.form1.totalregistros.value;i++ ){

    if ( document.form1.elements[i].checked != sn ){

	   document.form1.elements[i].click();

	}

  }

}





<!-- Funcoes para acesso da Layer de processando

<!--

function MM_reloadPage(init) {  //reloads the window if Nav4 resized

  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {

    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}

  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();

}

MM_reloadPage(true);





function MM_findObj(n, d) { //v4.0

  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {

    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);

  if(!x && document.getElementById) x=document.getElementById(n); return x;

}



function MM_showHideLayers() { //v3.0

  var i,p,v,obj,args=MM_showHideLayers.arguments;

  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];

    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
	
    obj.visibility=v;
	}

}
function MM_showHideLayersValor() { //v3.0

  var i,p,v,obj,args=MM_showHideLayersValor.arguments;

  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];

    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
	
    obj.left=document.body.scrollLeft+event.clientX;
	obj.top=document.body.scrollTop+event.clientY-80;

    obj.visibility=v;
	}

}


// Funcoes de verificacao do CNPJ

/////////////////////////////////////////////////////////////////
function FormataCPF(Campo, teclapres){
	var tecla = teclapres.keyCode;
	
	var vr = new String(Campo.value);
	vr = vr.replace(".", "");
	vr = vr.replace(".", "");
	vr = vr.replace("-", "");

	tam = vr.length + 1;
	
	if (tecla != 9 && tecla != 8){
		if (tam > 3 && tam < 7)
			Campo.value = vr.substr(0, 3) + '.' + vr.substr(3, tam);
		if (tam >= 7 && tam <10)
			Campo.value = vr.substr(0,3) + '.' + vr.substr(3,3) + '.' + vr.substr(6,tam-6);
		if (tam >= 10 && tam < 12)
			Campo.value = vr.substr(0,3) + '.' + vr.substr(3,3) + '.' + vr.substr(6,3) + '-' + vr.substr(9,tam-9);
		}
}
/////////////////////////////////////////////////////////////////
function FormataCNPJ(Campo, teclapres){

	var tecla = teclapres.keyCode;

	var vr = new String(Campo.value);
	vr = vr.replace(".", "");
	vr = vr.replace(".", "");
	vr = vr.replace("/", "");
	vr = vr.replace("-", "");

	tam = vr.length + 1 ;

	
	if (tecla != 9 && tecla != 8){
		if (tam > 2 && tam < 6)
			Campo.value = vr.substr(0, 2) + '.' + vr.substr(2, tam);
		if (tam >= 6 && tam < 9)
			Campo.value = vr.substr(0,2) + '.' + vr.substr(2,3) + '.' + vr.substr(5,tam-5);
		if (tam >= 9 && tam < 13)
			Campo.value = vr.substr(0,2) + '.' + vr.substr(2,3) + '.' + vr.substr(5,3) + '/' + vr.substr(8,tam-8);
		if (tam >= 13 && tam < 15)
			Campo.value = vr.substr(0,2) + '.' + vr.substr(2,3) + '.' + vr.substr(5,3) + '/' + vr.substr(8,4)+ '-' + vr.substr(12,tam-12);
		}
}
/////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
function VerAlfaNumerico(pInd){
	var pValor = document.forms[0].elements[pInd].value
	var AuxTam = pValor.length  
	for(var j=0;j<AuxTam;j++)
		if ((!IndAlfaNumerico(pValor.charAt(j))) || (pValor.charAt(j) == " ")){
			document.forms[0].elements[pInd].focus();  
			document.forms[0].elements[pInd].value = pValor = pValor.substring(0,j)           
			} 
	}
////////////////////////////////////////////////////////////////////
function IndAlfaNumerico(N){
	for(var i=0;i<10;i++)
	if(N == i)
		return true;
	return false;    
	}
//////////////////////////////////////////////////////////////////
function CalcularDV(sCampo, iPeso){
	
	var iTamCampo;
	var iPosicao, iDigito;
	var iSoma1 = 0;
	var iSoma2=0;
	var iDV1, iDV2;
		
	iTamCampo = sCampo.length;

	for (iPosicao=1; iPosicao<=iTamCampo; iPosicao++){
		iDigito = sCampo.substr(iPosicao-1, 1);
		iSoma1 = parseInt(iSoma1,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao, iPeso)),10);
		iSoma2 = parseInt(iSoma2,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao + 1, iPeso)),10);
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

//////////////////////////////////////////////////////////////////		
function Calcular_Peso(iPosicao, iPeso){

	//Pesos
	//CPF 11
	//CNPJ 9
	return (iPosicao % (iPeso - 1)) + 2;
	}
	
/////////////////////////////////////////////////////////////////
function LimpaCampo(sValor,iBase){
	var tam = sValor.length
	var saida = new String
	for (i=0;i<tam;i++)
		if (!isNaN(parseInt(sValor.substr(i,1),iBase)))
			saida = saida + String(sValor.substr(i,1));
	return (saida);		
	}
////////////////////////////////////////////////////////////////////
function TestaNI(cNI,iTipo){
	var NI 
	NI = LimpaCampo(cNI.value,10);
	switch (iTipo) {
		case 1:
			if (NI.length != 14){
				alert('O número do CNPJ informado está incorreto');
				cNI.value = "";
				cNI.focus();
				return(false);
				}

			if (NI.substr(12,2) != CalcularDV(NI.substr(0,12), 9)){
				alert('O número do CNPJ informado está incorreto');
				cNI.value = "";
				cNI.focus();
				return(false);
				}
			break;

		case 2:
			if (NI.length != 11){
				alert('O número do CPF informado está incorreto');
				cNI.value = "";
				cNI.focus();
				return(false);
				}

			if (NI.substr(9,2) != CalcularDV(NI.substr(0,9), 11)){
				alert('O número do CPF informado está incorreto');
				cNI.value = "";
				cNI.focus();
				return(false);
				}
			break;

		default:
			return(false);
		}
	return (true);	
	}  
/////////////////////////////////////////////////////////////////
function js_verificaCGCCPF(obcgc,obcpf){
  if (obcgc.value != ""){
     return TestaNI(obcgc,1);
  }
  if (obcpf.value != ""){
     return TestaNI(obcpf,2);
  }
//  return false;
  return true;
}

function CalculaDV(sCampo, iPeso)

{

	

	var iTamCampo;

	var iPosicao, iDigito;

	var iSoma1 = 0;

	var iSoma2=0;

	var iDV1, iDV2;

		

	iTamCampo = sCampo.length;

		

	for (iPosicao=1; iPosicao<=iTamCampo; iPosicao++){

		iDigito = sCampo.substr(iPosicao-1, 1);

		iSoma1 = parseInt(iSoma1,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao, iPeso)),10);

		iSoma2 = parseInt(iSoma2,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao + 1, iPeso)),10);

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

		

	return(iDV1);

	

}



//////////////////////////////////////////////////////////////////		



function Calcular_Peso(iPosicao, iPeso)

{



//Pesos

//CPF 11

//CNPJ 9



return (iPosicao % (iPeso - 1)) + 2;

}
/////////////////////////////////////////////////////////////////
function FormataValor(Campo, teclapres){
	var tecla = teclapres.keyCode;
 	var vr = new String(Campo.value);
	vr = vr.replace(".", "");
	vr = vr.replace(".", "");
	vr = vr.replace(".", "");
	vr = vr.replace(".", "");
	vr = vr.replace(",", "");
	tam = vr.length - 1  ;
  if (tecla != 9 && tecla != 8){
    if ( tecla >= 48 && tecla <= 57 ){
        var pree = "";
        for (contador = 0; contador < vr.length; contador ++){
		   if ( contador == 2 ){
			  pree = pree + vr.substr(contador-3,3) + "." + vr.substr(contador+1,2) ;
		   }
		   if ( contador == 5 ){
			  pree = pree + vr.substr(contador,1) + "." + vr.substr(contador+1,2) ;
		   }
		   if ( contador == 8 ){
			  pree = pree + vr.substr(contador,1) + "," + vr.substr(contador+1,2) ;
		   }
		}
		if (  pree != "" ){
		   Campo.value = pree;
		}
	}
  }
}
/////////////////////////////////////////////////////////////////
function js_validaAlfaNumerico(obvalida){
	var pValor = new String(obvalida.value)
	var AuxTam = pValor.length  
	pValor = pValor.replace('.','');
	for(var j=0;j<AuxTam;j++){
		if ((!IndAlfaNumerico(pValor.charAt(j))) || (pValor.charAt(j) == " ")){
            alert("Voce deverá digitar o valor separando os centavos com PONTO");
			obvalida.value = "";          
			obvalida.focus();  
		} 
	}
}
// ********************************************************
// funcoes do help
// ********************************************************
NoMe = new String(location.href);
NoMe = NoMe.split("/");
NoMe = NoMe[NoMe.length - 1];
NoMe = NoMe.substr(0,NoMe.search(".php"));
NoMe = NoMe + '_help';
var ob;
function js_moverDiv() {
  if(ob) {
    ob.pixelLeft = event.clientX - AntesX + document.body.scrollLeft - 2;
    ob.pixelTop = event.clientY - AntesY + document.body.scrollTop - 2;
    if(document.form1)
	  if(document.form1.x_div) {
  	  document.form1.x_div.value = parseInt(document.getElementById(NoMe).style.left);
	  document.form1.y_div.value = parseInt(document.getElementById(NoMe).style.top);	
    }	  
    return false;
  }
}

function js_MD_Div() {
//  ob=event.srcElement.parentNode.style;
  ob = document.getElementById(NoMe).style;
  AntesY=event.offsetY;
  AntesX=event.offsetX;
}
function js_MU_Div() {
  ob = null;
}

