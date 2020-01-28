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

$DocHome = "http://".$_SERVER["SERVER_ADDR"].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"));
$DocRoot = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],"/"));

if(isset($HTTP_POST_VARS["confirmar"])) {
  $imagem = $HTTP_POST_VARS["imagem"];
  $aux = split("-",$imagem);
  $TestaMatricula = 0;
  if($HTTP_POST_VARS["matricula"] != $aux[0] && $HTTP_POST_VARS["matricula"] != "") {
    $matricula = trim($HTTP_POST_VARS["matricula"]);
	$result = pg_exec("select j01_matric from iptubase where j01_matric = $matricula");
	if(pg_numrows($result) == 0)
	  $TestaMatricula = 1;
  } else
    $matricula = $aux[0];
  if($TestaMatricula == 0) {
    $data = @$aux[1]."-".@$aux[2]."-".substr(@$aux[3],0,strpos(@$aux[3],"."));
    $data_input = $HTTP_POST_VARS["data_ano"]."-".$HTTP_POST_VARS["data_mes"]."-".$HTTP_POST_VARS["data_dia"];  
    if($data != $data_input) {
      if(checkdate($HTTP_POST_VARS["data_mes"],$HTTP_POST_VARS["data_dia"],$HTTP_POST_VARS["data_ano"])) {  
        $data = $data_input;
	  } else {
        db_msgbox("Data do formulário inválida");
	    db_redireciona();
	  }
    } else {
      if(!checkdate($aux[2],substr($aux[3],0,strpos($aux[3],".")),$aux[1])) {
        db_msgbox("Data do arquivo inválida");   	    
	    db_redireciona();
	    exit;
	  }
    }
    pg_exec("begin");
    $oid = pg_loimport($DocRoot."/tmp/".$imagem) or die("Erro(21) importando imagem");
    pg_exec("insert into db_imgsitbi(matricula,data,arq) values('$matricula','$data',$oid)") or die("Erro(22) inserindo em db_imgsitbi");
    pg_exec("commit");  
  } else {
    db_msgbox("A matricula ($matricula) informada não existe");
    $HTTP_POST_VARS["procura"] = "procura";
	$name = $imagem;
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
table {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<br><br><Br>
	<center>
  <form method="post" enctype="multipart/form-data" name="form1">
    <?
	if(isset($HTTP_POST_VARS["procura"])) {
	  if(isset($_FILES["arq"]))
        db_postmemory($_FILES["arq"]);	  
	  $aux = split("-",$name);
      $matricula = @$aux[0];
      $data_ano = @$aux[1];
	  $data_mes = @$aux[2];
	  $data_dia = substr(@$aux[3],0,strpos(@$aux[3],"."));	  
	  $cod_matricula = $matricula;	  
	  if(is_integer($matricula)) {
        $result = pg_exec("select proprietario.*,o.z01_nome as promitente, i.z01_nome as imobiliaria from 
                           from proprietario p
	  					 left outer join cgm o on o.j41_numcgm = p.j41_numcgm
		  				 left outer join cgm i on i.j44_numcgm = p.j44_numcgm
			  			 where p.j01_matric = $cod_matricula");
      }						 
						 
	  if(pg_numrows($result) > 0) {
	    db_fieldsmemory($result,0);
	    if(isset($_FILES["arq"]))
		  copy($tmp_name,$DocRoot."/tmp/".$name);
	  } else {
	    db_msgbox("Antenção!! Esta matricula (".$cod_matricula.") não existe.");
	  }
    ?>
	<table width="100%" border="1" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="11%">Matr&iacute;cula:</td>
      <td id="Digito">&nbsp;<?=@$v11_matric?>- </td>
      <td width="25%">Refer&ecirc;ncia Anterior:</td>
      <td width="39%">&nbsp;<?=@$v11_refant?></td>
    </tr>
    <tr> 
      <td width="11%">Propriet&aacute;rio:</td>
      <td height="22">&nbsp;<?=@$z01_nome?></td>
      <td width="11%">Imobili&aacute;ria:</td>
      <td>&nbsp;<?=@$imobiliaria?></td>
	</tr>
    <tr> 
      <td colspan="4"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="11%">Setor:</td>
          <td width="20%"><div align="center"><?=@$v04_codbai?></div></td>
          <td width="16%"><div align="right">Quadra:</div></td>
          <td width="16%"><div align="center"><?=@$v04_codqua?></div></td>
          <td width="16%"><div align="right">Lote:</div></td>
          <td width="21%"><div align="center"><?=@$v04_codlot?></div></td>
        </tr>
        </table>
      </td>
    </tr>
    <tr align="center"> 
      <td colspan="4" height="22"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="11%">Logradouro:</td>
          <td width="11%"><div align="center"><?=@$j01_codigo?></div></td>
          <td width="51%"><div align="center"><?=@$j14_nome?></div></td>
          <td width="27%"><div align="center"><?=@$j39_numero?>/<?=@$j39_compl?></div></td>
         </tr>
         </table>
       </td>
     </tr>
     <tr align="center"> 
       <td colspan="4" height="22"> 
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr> 
           <td width="11%">Zona:</td>
           <td width="11%"><div align="center"><?=@$v07_zona?></div></td>
           <td width="9%"><div align="right">Setor:</div></td>
           <td width="12%"><div align="center"><?=@$v07_setor?></div></td>
           <td width="57%"><div align="center">Descri&ccedil;&atilde;o:<?=@$v07_descr?></div></td>
         </tr>
         </table>
       </td>
     </tr>
	 <tr>
	    <td colspan="4"><input name="confirmar" type="submit" id="confirmar" value="Confirmar">
          &nbsp;Matr&iacute;cula:&nbsp;
          <input name="matricula" type="text" value="<?=@$matricula?>">
          &nbsp;&nbsp;&nbsp;Data:&nbsp;     
          <input name="data_dia" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="data_dia" value="<?=@$data_dia?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
          <input name="data_mes" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="data_mes" value="<?=@$data_mes?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
          <input name="data_ano" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="data_ano" value="<?=@$data_ano?>" size="4" maxlength="4" autocomplete="off">
		</td>
	 </tr>
     </table>
	 <img src="<? echo $DocHome."/tmp/".$name ?>" border="0">
     <input name="imagem" type="hidden" value="<?=$name?>">
	 <script>
       var x = js_CalculaDV("<?=@$v11_matric?>",11);
       document.getElementById("Digito").innerHTML += x;
     </script>
    <?
	} else {
	?>
    <table width="60%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="26%" height="30"><strong>Imagem:&nbsp;&nbsp;</strong></td>
        <td width="74%" height="30">&nbsp;
          <input name="arq" type="file" id="arq" size="40"></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
        <td height="30">&nbsp; 
          <input name="procura" type="submit" id="procura" value="Enviar"></td>
      </tr>
    </table>
	<?
	}
	?>
  </form>
</center>
	
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
	</td>
  </tr>
</table>
</body>
</html>