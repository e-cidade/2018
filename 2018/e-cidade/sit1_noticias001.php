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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

//retorno da funcao db_lov
if(isset($retorno)) {
  $result = pg_exec("select s_codigo as codigo,s_tit as tit,to_char(s_data,'YYYY') as data_ano,to_char(s_data,'MM') as data_mes,to_char(s_data,'DD') as data_dia,s_texto as texto,s_im_men as im_men,s_im_mai as im_mai from db_noticias where s_codigo = $retorno");
  db_fieldsmemory($result,0);
}

//$caminho = "/var/www/default/imagens/noticias";
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($_FILES["im_menA"]);
 pg_exec("begin");
  if($name != "") {
    if($size == 0) {
      echo "O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.<Br>";
  	  echo "<a href=\"noticias.php\">Voltar para cadastro de noticias</a>\n";
	  exit;
    }
    $oid1 = pg_loimport($tmp_name);
    //copy($tmp_name,"$caminho/$im_men");   
  } else
    $oid1 = "null";
  db_postmemory($_FILES["im_maiA"]);
  if($name != "") {
    if($size == 0) {
      echo "O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.<Br>";
  	  echo "<a href=\"noticias.php\">Voltar para cadastro de noticias</a>\n";
	  exit;
    }
   $oid2 = pg_loimport($tmp_name);
   //copy($tmp_name,"$caminho/$im_mai");
  } else
    $oid2 = "null";
  $result = pg_exec("select max(s_codigo) from db_noticias");
  $codigo = pg_result($result,0,0)==""?0:(integer)pg_result($result,0,0) + 1;
  pg_exec("insert into db_noticias(s_codigo,s_tit,s_data,s_texto,s_im_men,s_im_mai) 
  values($codigo,'$tit','$data_ano-$data_mes-$data_dia','$texto',$oid1,$oid2)") or die(pg_errormessage($result));
  pg_exec("commit");
  db_redireciona();
  exit;
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  if($not_destaque == 1) {    
    pg_exec("begin");
	$result = pg_exec("select max(s_codigo) + 1 from db_noticias");
	$aux = pg_result($result,0,0);
	pg_exec("update db_noticias set s_codigo = $aux where s_codigo = 0") or die(pg_errormessage());	
    pg_exec("update db_noticias set s_codigo = 0 where s_codigo = $codigo") or die(pg_errormessage());
	pg_exec("update db_noticias set s_codigo = $codigo where s_codigo = $aux") or die(pg_errormessage());
	pg_exec("COMMIT");
    $codigo = 0;
  }  
  //$result = pg_exec("select s_im_men,s_im_mai from db_noticias where s_codigo = $codigo");
  pg_exec("begin");
  db_postmemory($_FILES["im_menA"]);
  if($name != "") {
    //system("rm -f $caminho/".pg_result($result,0,"s_im_men"));
    //copy($tmp_name,"$caminho/$im_men");
    $oid1 = pg_loimport($tmp_name);
    pg_exec("update db_noticias set s_im_men = $oid1 where s_codigo = $codigo") or die(pg_errormessage($result));
  } 
  /*
  else if($im_men != "") {
    if($im_men != pg_result($result,0,"s_im_men"))
	  system("mv $caminho/".pg_result($result,0,"s_im_men")." $caminho/$im_men");
  }*/
  db_postmemory($_FILES["im_maiA"]);
  if($name != "") {
    //system("rm -f $caminho/".pg_result($result,0,"s_im_mai"));  
    //copy($tmp_name,"$caminho/$im_mai"); 
    $oid2 = pg_loimport($tmp_name);
	pg_exec("update db_noticias set s_im_mai = $oid2 where s_codigo = $codigo") or die(pg_errormessage($result));
  } 
  /*
  else if($im_mai != "") {
    if($im_mai != pg_result($result,0,"s_im_mai"))
	  system("mv $caminho/".pg_result($result,0,"s_im_mai")." $caminho/$im_mai");
  }*/
  pg_exec("update db_noticias set 
             s_tit = '$tit',
			 s_data = '$data_ano-$data_mes-$data_dia',
			 s_texto = '$texto'
		   where s_codigo = $codigo") or die(pg_errormessage($result));		   
  pg_exec("commit");		   
  db_redireciona();
  exit;
} else if(isset($HTTP_POST_VARS["excluir"])) {
//  $result = pg_exec("select s_im_men,s_im_mai from db_noticias where s_codigo = ".$HTTP_POST_VARS["codigo"]);
  pg_exec("delete from db_noticias where s_codigo = ".$HTTP_POST_VARS["codigo"])  or die(pg_errormessage($result));
  //system("rm -f $caminho/".pg_result($result,0,"s_im_men"));
  //system("rm -f $caminho/".pg_result($result,0,"s_im_mai"));  
  db_redireciona();
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_destaque() {
  var F = document.form1;
    document.form1.incluir.disabled=true;
	document.form1.excluir.disabled=true;
}
function js_iniciar() {
  if(document.form1)
    document.form1.tit.focus();
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" onLoad="js_iniciar()">
<? if(!isset($HTTP_POST_VARS["consultar"]) && !isset($HTTP_POST_VARS["priNoMe"]) && !isset($HTTP_POST_VARS["antNoMe"]) && !isset($HTTP_POST_VARS["proxNoMe"]) && !isset($HTTP_POST_VARS["ultNoMe"])) { ?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
  <form method="post" enctype="multipart/form-data" name="form1">
    <table border="0" cellspacing="2" cellpadding="0">
      <tr> 
        <td><strong>T&iacute;tulo:</strong></td>
        <td><input name="codigo" type="hidden" value="<?=@$codigo?>"> 
          <input name="tit" type="text" value="<?=@$tit?>" size="70"></td>
      </tr>
      <tr> 
        <td><strong>Data:</strong></td>
        <td>
		<?
		  include("dbforms/db_funcoes.php");
		  db_data("data",@$data_dia,@$data_mes,@$data_ano);
		?>
		  <!--input name="data_dia" type="text" id="data_dia" value="<?=@$data_dia?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="data_mes" type="text" id="data_mes" value="<?=@$data_mes?>" size="2" maxlength="2"> 
          <strong>/</strong> <input name="data_ano" type="text" id="data_ano" value="<?=@$data_ano?>" size="4" maxlength="4"-->
		</td>
      </tr>
      <tr> 
            <td><strong>Foto Menor:</strong></td>
        <td><input name="im_menA" type="file" id="im_menA" size="50" onChange="js_preencheCampo(this.value,this.name)"> 
          <br>
            </td>
      </tr>
      <tr> 
        <td><p><strong>Foto Maior:<br>
                </strong></p></td>
        <td><input name="im_maiA" type="file" id="im_maiA" size="50" onChange="js_preencheCampo(this.value,this.name)"> 
          <br>
            </td>
      </tr>
      <tr> 
        <td><strong>Texto:</strong></td>
        <td><textarea name="texto" cols="60" rows="5"><?=@$texto?></textarea></td>
      </tr>
      <tr>
        <td><strong>Destaque:</strong></td>
        <td><input name="not_destaque" type="checkbox" value="1" onClick="js_destaque()" <? echo (@$codigo==0 && isset($codigo)?"checked":"") ?>></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><input name="incluir" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
          <input name="alterar" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
          <input name="excluir" type="submit" id="excluir" value="Excluir" onClick="return confirm('Voce realmente quer excluir esta notícia?')" <? echo !isset($retorno)?"disabled":($codigo==0?"disabled":"") ?>>
                <input name="consultar" type="submit" onClick="this.form.target = 'consulta'" id="consultar" value="Procurar"></td>
      </tr>
    </table>
  </form>
    <iframe name="consulta" src="" width="750" height="160"></iframe>
  </td>
</tr>
</table>	
	</td>
  </tr>
</table>
<? } else { ?>
<?
  db_postmemory($HTTP_POST_VARS);
  if(!empty($tit))
    $filtro = "and upper(s_tit) like upper('$tit%')";
  $sql = "select s_codigo as db_codigo,s_tit as título,s_texto 
          from db_noticias 
		  where 2 > 1
		  ".@$filtro."
		  order by db_codigo";
  if(isset($HTTP_POST_VARS["filtro"])) {
    $filtro = base64_decode($HTTP_POST_VARS["filtro"]);
  }
		  
  echo "<center>\n";
  db_lov($sql,100,"sit1_noticias001.php",base64_encode(@$filtro),"corpo");
  //db_lov($query,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe") { 
  echo "</center>\n";
?>
<? } ?>
	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>