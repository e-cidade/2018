<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($HTTP_POST_VARS["codigo"])) {	
  $codigo = $HTTP_POST_VARS["codigo"];	
  pg_exec("begin");
  pg_exec("delete from db_carnescampos where codmodelo = $codigo") or die("Erro excluindo db_carnescampos");
  $tam_vetor = sizeof($HTTP_POST_VARS);
  reset($HTTP_POST_VARS);
  next($HTTP_POST_VARS);
  //$aux[0] posx
  //$aux[1] posy
  //$aux[2] nomcam
  for($i = 1;$i < $tam_vetor;$i++) {
    $aux = split(";",$HTTP_POST_VARS[key($HTTP_POST_VARS)]);
	$result = pg_exec("select 0 from db_syscampo where nomecam = '".trim($aux[2])."'");
	if(pg_numrows($result)==0) 
	   $var = 'f';
	else
	   $var = 't';
	 
echo    $sql = "insert into db_carnescampos values(".$codigo.",".$aux[0].",".$aux[1].",'".$aux[2]."','".$var."')";
	$result = pg_exec($sql) or die("Erro inserindo em db_carnescampos");
    next($HTTP_POST_VARS);
  }
  pg_exec("commit");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Contador = 0;
function js_posicao() {
  if(parent.cadpos.document.form1.dados.selectedIndex == 0 && parent.cadpos.document.form1.texto.selectedIndex == 0 ) {
    alert('Selecione algum campos primeiro!');
	parent.cadpos.document.form1.dados.focus();
	return false;
  }
  parent.cadpos.document.form1.posx.value = event.offsetX;
  parent.cadpos.document.form1.posy.value = event.offsetY; 

  if (parent.cadpos.document.form1.dados.options[parent.cadpos.document.form1.dados.selectedIndex].value!=0){
     var nomecam = parent.cadpos.document.form1.dados.options[parent.cadpos.document.form1.dados.selectedIndex].text;
  }else{
     var x  = new String(parent.cadpos.document.form1.texto.options[parent.cadpos.document.form1.texto.selectedIndex].text);
     var xx = x.split("->");
     var nomecam = xx[0];
  }
  var str = "";

  if (parent.cadpos.document.form1.dados.options[parent.cadpos.document.form1.dados.selectedIndex].value!=0){
     var tot = parent.cadpos.document.form1.dados.options[parent.cadpos.document.form1.dados.selectedIndex].value;
     for(var i = 0;i < tot;i++)
       str += "X";
  }else{
     var tot = parent.cadpos.document.form1.texto.options[parent.cadpos.document.form1.texto.selectedIndex].value;
     var x  = new String(parent.cadpos.document.form1.texto.options[parent.cadpos.document.form1.texto.selectedIndex].text);
     var xx = x.split("->");
     str = xx[1];  
  }

  //cria hidden
  var chi = document.createElement("INPUT"); 
  chi.setAttribute("id","hid" + Contador);
  chi.setAttribute("name","hid" + Contador);  
  chi.setAttribute("type","hidden");  
  chi.setAttribute("value",event.offsetX + ";" + event.offsetY + ';' + nomecam);  
  document.getElementById('idform').appendChild(chi);  
  //cria div
  var marcacao = document.createElement("DIV"); 
  marcacao.setAttribute("id","cod" + Contador);
  marcacao.setAttribute("title",nomecam);  
//position:absolute;layer-background-color: #993366;z-index:1; left:" + event.offsetX + "px; top:" + event.offsetY + "px;width:135px; height:90px;visibility: visible;
  marcacao.appendChild(document.createTextNode(str));
  document.body.appendChild(marcacao);  
  document.getElementById('cod' + Contador).style.position = 'absolute';
  document.getElementById('cod' + Contador).style.left = event.offsetX;  
  document.getElementById('cod' + Contador).style.top = event.offsetY - 10;  
  document.getElementById('cod' + Contador).style.visibility = 'visible';
  document.getElementById('cod' + Contador).style.layerBackgroundColor = '#EABFD5';
  document.getElementById('cod' + Contador).style.color = 'red';   
  document.getElementById('cod' + Contador).style.cursor = 'hand';   
  document.getElementById('cod' + Contador).onmousedown = function() {js_inserirPos(js_parse_int(this.id));js_engage(this);};
  document.getElementById('cod' + Contador).onmouseup = function() {js_release(this);};
  document.getElementById('cod' + Contador).onmousemove = function() {js_moverPos(js_parse_int(this.id));js_dragIt(this);};
  document.getElementById('cod' + Contador).onmouseout = function() {js_release(this);};
  document.getElementById('cod' + Contador++).ondblclick = function() { removeElem(js_parse_int(this.id));};
}
function js_moverPos(cod) {
  var posx = document.getElementById('cod' + cod).style.pixelLeft;
  var posy = document.getElementById('cod' + cod).style.pixelTop;
  var nomecam = document.getElementById('cod' + cod).title;  
  parent.cadpos.document.form1.posx.value = posx;
  parent.cadpos.document.form1.posy.value = posy;
  document.getElementById('hid' + cod).value = posx + ';' + (posy + 10) + ';' + nomecam;
}
function js_inserirPos(cod) {
  var str = new String(document.getElementById("hid" + cod).value);
  str = str.split(";");
  parent.cadpos.document.form1.posx.value = str[0];
  parent.cadpos.document.form1.posy.value = str[1];
  parent.cadpos.document.form1.nomecam.value = str[2];  
  var sel = parent.cadpos.document.form1.dados;
  for(var i = 0;i < sel.length;i++) {
    if(sel.options[i].text == str[2]) {
	  sel.options[i].selected = true;
	  break;
	}
  }
}
function removeElem(cod) {
  var elem = document.getElementById("cod" + cod);
  elem.parentNode.removeChild(elem);	
  var elem = document.getElementById("hid" + cod);
  elem.parentNode.removeChild(elem);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<?

		if(isset($codigo)) {//var vem do parse_str
          $result = pg_exec("select nomemodelo,imgmodelo from db_carnesimg where codmodelo = $codigo");
		  if(pg_numrows($result) > 0) {
		  //escreve a imagem
	        $arquivo = "tmp/".str_replace(" ","_",pg_result($result,0,"nomemodelo")).".jpg";
   		    pg_exec("begin");
            $oid = pg_result($result,0,"imgmodelo");
            pg_loexport($oid,$arquivo);
            pg_exec("end");
            echo "<img style=\"position:absolute;left:0px; top:0px\" src=\"".$arquivo."\" border=\"0\" onclick=\"js_posicao()\">\n";			  
		    ///escreve os campos///
			$reccampos = pg_exec("select nomecam,posxmodelo,posymodelo from db_carnescampos where codmodelo = $codigo");
			$numcampos = pg_numrows($reccampos);
//			if($numcampos > 0) {			  
			  //escreve as div
			  for($i = 0;$i < $numcampos;$i++) {
			    db_fieldsmemory($reccampos,$i);
				$comprimento = pg_exec("select tamanho
		                                from db_syscampo							            
							            where nomecam = '".$nomecam."'");
				if(pg_numrows($comprimento) == 0){
				   $xx = split("->",$nomecam);
				   $comprimento = pg_exec("select length(txcampo),txcampo
		                                   from db_carnesdados							            
							               where idtx = '".trim($xx[0])."'::integer");
				   if(pg_numrows($comprimento) == 0){
				     db_msgbox("Erro: campo $nomecam não encontrado em db_syscampo ou db_carnesdados.");
				   }   
				   $comp = pg_result($comprimento,0,1);
			       $comprimento = pg_result($comprimento,0,0);
				}else{
			       $comprimento = pg_result($comprimento,0,0);
			   	   $comp = "";
				   for($j = 0;$j < $comprimento;$j++)
				     $comp .= "X";
			    }
			    echo "<div  onmousedown=\"js_inserirPos(js_parse_int(this.id));js_engage(this)\" onmouseup=\"js_release(this)\" onmousemove=\"js_moverPos(js_parse_int(this.id));js_dragIt(this)\" onmouseout=\"js_release(this)\" ondblclick=\"removeElem('".($i + 1000)."')\" title=\"".$nomecam."\" id=\"cod".($i + 1000)."\" style=\"position:absolute;color:black;cursor:hand; left:".$posxmodelo."px; top:".($posymodelo - 10)."px; z-index:1; visibility: visible;\">".$comp."</div>\n";
			  }
			  //escreve os hidden
			  echo "<form id=\"idform\" name=\"form1\" method=\"post\">\n";
			    echo "<input type=\"hidden\" name=\"codigo\" value=\"$codigo\">\n";
			  for($i = 0;$i < $numcampos;$i++) {
			    db_fieldsmemory($reccampos,$i);
			    echo "<input type=\"hidden\" id=\"hid".($i + 1000)."\" name=\"hid".($i + 1000)."\" value=\"".$posxmodelo.";".$posymodelo.";".$nomecam."\">\n";
			  }
			  echo "</form>\n";
//			}
			/////////////////////// 	        			
		  } else {
		    db_erro("Imagem não encontrada");
		  }
		}
		?>
</body>
</html>