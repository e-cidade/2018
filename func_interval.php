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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_syscampo_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_syscampo = new cl_db_syscampo;
$clrotulo = new rotulocampo;
$result_campo = $cldb_syscampo->sql_record($cldb_syscampo->sql_query($param,"nomecam, conteudo, rotulo, descricao"));
if($cldb_syscampo->numrows > 0){
  db_fieldsmemory($result_campo,0);
  $clrotulo->label($nomecam);
  $Inomecam = "I".$nomecam;
  $Lnomecam = "L".$nomecam;
  $Tnomecam = "T".$nomecam;

  $campofoco = $nomecam."1";
  $camporecb = $nomecam."2";
  if($conteudo == "date"){
	  $campofoco = $nomecam."1_dia";
  }
}
if(isset($valorvariavel) && trim($valorvariavel) != ""){
	$arr_valorvariavel = split("[|]",$valorvariavel);
	$camporecebe = $nomecam;
	for($i=1;$i<count($arr_valorvariavel);$i++){
		$qualcampo  = $camporecebe.$i;
	  $$qualcampo = $arr_valorvariavel[$i];
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_enviarvalor(){
	erro = 0;
<?
if($conteudo != "text" && strpos($conteudo,"char") == "" && strpos($conteudo,"bool") == "" && $conteudo != "date"){
?>
	if(document.form1.<?=$campofoco?>.value != "" || document.form1.<?=$camporecb?>.value != ""){
		valor = "<?=$param?>#";
		valor+= document.form1.<?=$campofoco?>.value+"#";
		valor+= document.form1.<?=$camporecb?>.value;
	  parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = valor;
	}else{
		erro++;
		parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = '';
	}
<?
}else if($conteudo == "date"){
?>
	if(document.form1.<?=$campofoco?>.value != ""){
		valor = "<?=$param?>#";
		valor+= document.form1.<?=$nomecam?>1_ano.value+"-"+document.form1.<?=$nomecam?>1_mes.value+"-"+document.form1.<?=$nomecam?>1_dia.value+"#";
		valor+= document.form1.<?=$nomecam?>2_ano.value+"-"+document.form1.<?=$nomecam?>2_mes.value+"-"+document.form1.<?=$nomecam?>2_dia.value;
	  parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = valor;
	}else{
		erro++;
		parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = '';
	}
<?
}else{
?>
	if(document.form1.<?=$campofoco?>.value != ""){
		valor = "<?=$param?>#";
		valor+= document.form1.<?=$campofoco?>.value;
	  parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = valor;
	}else{
		erro++;
		parent.document.form1.campo_camporecb_filtro<?=$campo?>.value = '';
	}
<?
}
?>

  if(erro == 0){
    parent.document.form1.mudar<?=$campo?>.style.visibility = 'visible';
    parent.js_setarvalor('filtro<?=$campo?>','<?=$param?>');
  }else{
    parent.document.form1.mudar<?=$campo?>.style.visibility = 'hidden';
    parent.js_setarvalor('filtro<?=$campo?>','0');
  }
	parent.db_iframe_interval.hide();
}
function js_retornavalor(){
	if(parent.document.form1.campo_camporecb_filtro<?=$campo?>.value == ""){
		parent.document.form1.mudar<?=$campo?>.style.visibility = 'hidden';
    parent.document.form1.filtro<?=$campo?>.options[0].selected=true;
	}
}
function js_fechar(){
  js_retornavalor();
  parent.db_iframe_interval.hide();
}
<?
flush();
if($cldb_syscampo->numrows == 0){
	echo "
				alert('Campo não encontrado.');
				js_fechar();
       ";
}
?>
js_retornavalor();
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <form name="form1" method="post" action="">
  <tr>
    <td nowrap title="<?=@$$Tnomecam?>">
      <?=@$$Lnomecam?>
    </td>
    <td> 
			<?
			if($conteudo != "text" && strpos($conteudo,"char") == "" && strpos($conteudo,"bool") == "" && $conteudo != "date"){
			  db_input($nomecam."1",10,$$Inomecam,true,'text',1,"onchange='js_passaval();'");
			  echo "&nbsp;<b>a</b>&nbsp;";
			  db_input($nomecam."2",10,$$Inomecam,true,'text',1);
			}else if($conteudo == "date"){
				$teste1 = $nomecam."1";
				$dia = $nomecam."_dia";
				$mes = $nomecam."_mes";
				$ano = $nomecam."_ano";
				if(isset($$teste1)){
					$arr_teste1 = split("-",$$teste1);
					$$dia = $arr_teste1[2];
					$$mes = $arr_teste1[1];
					$$ano = $arr_teste1[0];
				}else{
					$$dia = date("d",db_getsession("DB_datausu"));
					$$mes = date("m",db_getsession("DB_datausu"));
					$$ano = date("Y",db_getsession("DB_datausu"));
				}
				db_inputdata($nomecam."1",$$dia,$$mes,$$ano,true,'text',1);
			  echo "&nbsp;<b>a</b>&nbsp;";
				$teste2 = $nomecam."2";
				if(isset($$teste2)){
					$arr_teste2 = split("-",$$teste2);
					$$dia = $arr_teste2[2];
					$$mes = $arr_teste2[1];
					$$ano = $arr_teste2[0];
				}
				db_inputdata($nomecam."2",$$dia,$$mes,$$ano,true,'text',1);
			}else{
			  db_input($nomecam."1",30,$$Inomecam,true,'text',1);
			}
			?>
    </td>
  </tr>
  </form>
</table>
<input name="Enviar" type="button" id="enviar" value="Enviar" onclick="js_enviarvalor();"> 
<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar();">
</center>
</body>
</html>
<script>
function js_passaval(){
  if(document.form1.<?=$camporecb?>.value == ""){
	  document.form1.<?=$camporecb?>.value = document.form1.<?=$campofoco?>.value;
	  document.form1.<?=$camporecb?>.select();
    document.form1.<?=$camporecb?>.focus();
	}
}
document.form1.<?=$campofoco?>.select();
document.form1.<?=$campofoco?>.focus();
</script>