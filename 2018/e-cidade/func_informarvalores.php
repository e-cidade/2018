<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label("e81_valor");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="1" align="center" cellspacing="2" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
  <tr>
    <td nowrap class='bordas02' align='center' colspan='2'>
      <strong>Valor dos cheques</strong>
    </td>
  </tr>
    <?
    $arr_cheqsel = Array();
    $arr_cheques = Array();
    $mostraquant = true;
    if(isset($ch) && trim($ch) != ""){
    	$arr_cheqsel = split("-",$ch);
    	$novaquant = $quantidade-count($arr_cheqsel);
  		// if($novaquant == 0 || $novaquant > 0){
		    $mostraquant = false;
    		$arr_cheques = $arr_cheqsel;
	    /*
	    	if($novaquant > 0){
		    	for($i=0; $i<$novaquant; $i++){
		    		$arr_cheques[($quantidade+$i)] = "0.00";
		    	}
	    	}
  		}else{
	 	    $valor = trim(db_formatar(($total / $quantidade), 'p', '', 2));
	      $valtt = 0;
  		}
  		*/
    }else{
 	    $valor = trim(db_formatar(($total / $quantidade), 'p', '', 2));
      $valtt = 0;
    }
    if($mostraquant == true){
	    for($i=0; $i<$quantidade; $i++){
	      $valtt += $valor;
	      if(($i+1) == $quantidade){
			    if($total > $valtt){
			      $resto = $total - $valtt;
			      $valor+= $resto;
			    }else if($total < $valtt){
			      $resto = $valtt - $total;
			      $valor-= $resto;
	        }
	      }
	      $arr_cheques[$i] = $valor;
	    }
    }

    $db_opcao = 1;
    $contador = count($arr_cheques);
    for($i=0; $i<$contador; $i++){
      if(($i + 1) == $contador){
    	$db_opcao = 3;
      }
      $valorcheque  = "valche_".$i;
      $$valorcheque = "0.00";
      if(isset($arr_cheques[$i])){
        $$valorcheque = db_formatar($arr_cheques[$i],'p', '', 2);
      }
      echo "
            <tr>
              <td nowrap class='bordas' align='center'>
                <strong>Valor cheque ".($i + 1).": </strong>
              </td>
              <td nowrap class='bordas' align='center'>
                &nbsp;
           ";

      db_input("valche_".$i,10,$Ie81_valor,true,"text",$db_opcao,"onchange='js_calculaproximo(this.name);'");

      echo "
                &nbsp;
              </td>
            </tr>
           ";
    }
    /*
    echo "
          <tr>
            <td colspan='2'>
              <!--&nbsp;-->
            </td>
          </tr>
         ";
    */
    echo "
          <tr>
            <td nowrap class='bordas' align='center'>
              <strong>Valor total: </strong>
            </td>
            <td nowrap class='bordas' align='center'>
                &nbsp;
         ";

    db_input("total",10,"",true,"text",3);

    echo "
                &nbsp;
            </td>
          </tr>
         ";
    ?>

  <tr>
    <td class='bordas' colspan='2' align='center'>
			<input name="Enviar" type="button" id="enviar" value="Enviar" onclick="js_enviarvalores();">
			<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_verificardados();">
    </td>
  </tr>
  </form>
</table>
</center>
</body>
</html>
<script>
function js_calculaproximo(campo){
  x = document.form1;
  total = new Number(x.total.value);
  setados = false;
  indice = 0;
  valorcampo = eval("x."+campo+".value");
  valorcampo = new Number(valorcampo);
  valortotal = new Number(total);
  if(valorcampo.toFixed(2) > valortotal.toFixed(2)){
  	alert("Valor do cheque deve ser menor que o valor total.");
  	x.elements[0].value = valortotal;
  	campo = x.elements[0].name;
  }
  for(i=0; i<x.length; i++){
    if(x.elements[i].type == "text" && x.elements[i].name != "total"){
	    if(setados == false){
	      valor = new Number(x.elements[i].value);
	      // valor = valor.toFixes(2);
	      //total-= valor.toFixed(2);
	      total-= valor;
	      total = total.toFixed(2);
	    }
      arr_cheques = new Array();
	    if(x.elements[i].name == campo && setados == false){
	      setados = true;
	      valtt   = 0;
        analisaval = new Number((total/(x.length-i-4)));
        analisaval = analisaval.toFixed(2);
	      quantidade = (x.length-i-4);
	      for(wx=0; wx<quantidade; wx++){
	        valtt = new Number(valtt + analisaval);
	        if((wx+1) == quantidade){
	          if(total > valtt){
	          	analisares = total - valtt;
	          	analisares = new Number(analisares);
	          	analisaval+= analisares;
	          }else if(total < valtt){
	          	analisares = valtt - total;
	          	analisares = new Number(analisares);
	          	analisaval-= analisares;
	          }
	        }
	        analisaval = new Number(analisaval);
	        arr_cheques[wx] = analisaval.toFixed(2);
	      }

	      valorcorrente = new Number(x.elements[i].value);
	      x.elements[i].value = valorcorrente.toFixed(2);
	      if(arr_cheques.length > 0){
			    indice = i+1;
		      for(wx=0; wx<arr_cheques.length;wx++){
		        setarvalor = new Number(arr_cheques[wx]);
		        x.elements[indice].value = setarvalor.toFixed(2);
		        indice ++;
		      }
		      break;
	      }
	    }
    }
  }

  var valorconfere = 0;
  for(i=0; i<x.length; i++){

    if(x.elements[i].type == "text" && x.elements[i].name != "total"){

      valorinserir = Number(x.elements[i].value).toFixed(2);
      valorconfere += valorinserir;
			if(valorconfere.toFixed(2) > valortotal){

				alert("Somatório dos valores dos cheques superior ao valor total. Confira. 1");
				x.elements[0].value = x.total.value;
				js_calculaproximo(x.elements[0].name);
				break;
			}
    }
  }
}
function js_enviarvalores(){
	x = document.form1;
	valorconfere = 0;
	erro = false;
	valores_cheques = "";
	vir = "";
	con = 0;
  valortotal = new Number(document.form1.total.value);
  for(i=0; i<x.length; i++){
    if(x.elements[i].type == "text" && x.elements[i].name != "total" && x.elements[i].value > 0){
      valorinserir = new Number(x.elements[i].value);
      valorconfere+= valorinserir;
			if(js_round(valorconfere,2) > js_round(valortotal,2)){
				alert("Somatório dos valores dos cheques superior ao valor total. Confira. 2");
				x.elements[0].value = x.total.value;
				js_calculaproximo(x.elements[0].name);
				erro = true;
				break;
			}
			valores_cheques+= vir+valorinserir;
			vir = "-";
			con++;
    }
  }
  if(erro == false && valores_cheques != ""){
  	<?if(!isset($forma)){?>
  	top.corpo.db_iframe_cheque.jan.js_recebeval(con,valores_cheques);
  	<?}else{?>
  	parent.js_recebeval(con,valores_cheques);
  	<?}?>
  }
}
function js_verificardados(){
	con = 1;
	<?
	if(isset($ch) && trim($ch)){
		$arr_cheqsel = split("-",$ch);
		echo "con = ".count($arr_cheqsel).";";
	}
	?>
	<?if(!isset($forma)){?>
	top.corpo.db_iframe_cheque.jan.js_fechariframe(con);
	<?}else{?>
	parent.js_fechariframe(con);
	<?}?>
}
document.form1.elements[0].select();
document.form1.elements[0].focus();
</script>