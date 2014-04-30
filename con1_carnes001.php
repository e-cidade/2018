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
if(isset($retorno)) {
  $sql = "select codmodelo,nomemodelo,orientacao
          from db_carnesimg
		  where codmodelo = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}
//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($_FILES["arq"]);
  $result = pg_exec("select max(codmodelo) + 1 from db_carnesimg");
  $codigo = pg_result($result,0,0);
  $codigo = $codigo == ""?"1":$codigo;
  
  pg_exec("begin");
  if($formulario==0){
    $oid = pg_loimport($tmp_name) or die("Erro(15) importando imagem");
    pg_exec("insert into db_carnesimg values($codigo,'$nome',$oid,'$orientacao')") or die("Erro(16) inserindo em tabimagens"); // insere um codigo de controle mais o codigo da imagem(oid)
  }else{
    $result = pg_exec("select * from db_carnesimg where codmodelo = $formulario");
	if(pg_numrows($result)!=0){	  
      pg_exec("insert into db_carnesimg values($codigo,'$nome',".pg_result($result,0,"imgmodelo").",'".pg_result($result,0,"orientacao")."')") or die("Erro(16) inserindo em tabimagens"); // insere um codigo de controle mais o codigo da imagem(oid)
      pg_exec("insert into db_carnescampos 
	               select nomecam,".$codigo.",posxmodelo,posymodelo,tipocampo
				   from db_carnescampos where codmodelo = $formulario") or die("Erro(16) inserindo em db_carnescampos"); // insere um codigo de controle mais o codigo da imagem(oid)
	}
  }
  pg_exec("end");
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($_FILES["arq"]);
  if($error != 0) {
    pg_exec("update db_carnesimg set nomemodelo = '$nome',	
								   orientacao = '$orientacao'
				where codmodelo = $codmodelo") or die("Erro(36) alterando em tabimagens"); 
  } else {
    pg_exec("begin");
    $oid = pg_loimport($tmp_name) or die("Erro(15) importando imagem");
    pg_exec("update db_carnesimg set nomemodelo = '$nome',
								   imgmodelo = $oid,
								   orientacao = '$orientacao'
				where codmodelo = $codmodelo") or die("Erro(36) alterando em tabimagens"); 
    pg_exec("end");
  }
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;		     
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("BEGIN");
  pg_exec("delete from db_carnescampos where codmodelo = ".$HTTP_POST_VARS["codmodelo"]) or die("Erro(49) excluindo db_carnescampos: ".pg_errormessage());
  pg_exec("delete from db_carnesimg where codmodelo = ".$HTTP_POST_VARS["codmodelo"]) or die("Erro(50) excluindo db_carnesimgs: ".pg_errormessage());
  pg_exec("COMMIT");  
  db_redireciona($HTTP_SERVER_VARS['PHP_SELF']);
  exit;  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"> 
	<?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
	     $sql = "SELECT codmodelo as Código, nomemodelo as nome,orientacao
              FROM db_carnesimg
			  WHERE nomemodelo like '".$HTTP_POST_VARS["nomemodelo"]."%'
              ORDER BY codmodelo";
		db_lov($sql,15,"con1_carnes001.php"); 
	  } else {
	?>
	<form method="post" enctype="multipart/form-data" name="form1">
	<input type="hidden" name="codmodelo" value="<?=@$codmodelo?>">
        <table width="39%" border="1" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="13%"><strong>Nome:</strong></td>
            <td width="87%"><input name="nome" type="text" value="<?=@$nomemodelo?>" size="50" maxlength="100"></td>
          </tr>
          <tr> 
            <td><strong>Imagem:</strong></td>
            <td><input name="arq" type="file" id="arq" size="40" maxlength="200"></td>
          </tr>
          <tr> 
            <td><strong>Orienta&ccedil;&atilde;o:&nbsp;</strong></td>
            <td> <label for="ori1">paisagem</label> <input type="radio" name="orientacao" value="P" id="ori1" <? echo isset($orientacao)?($orientacao=="P"?"checked":""):"checked" ?>> 
              <label for="ori2">retrato</label> <input name="orientacao" type="radio" id="ori2" value="R"  <? echo isset($orientacao)?($orientacao=="R"?"checked":""):"" ?>> 
            </td>
          </tr>
          <tr>
            <td>Importar:</td>
            <td><select name="formulario" id="formulario" >
            <option value="0">Nenhum...</option>
            <?
			    $result = pg_exec("select codmodelo,nomemodelo from db_carnesimg");
				$numrows = pg_numrows($result);
				for($i = 0;$i < $numrows;$i++) {
				  echo "<option value=\"".pg_result($result,$i,"codmodelo")."\" ".(isset($HTTP_POST_VARS["formulario"])?($HTTP_POST_VARS["formulario"]==pg_result($result,$i,"codmodelo")?"selected":""):"").">".pg_result($result,$i,"nomemodelo")."</option>\n";
				}
			  ?>
          </select></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar"> 
            </td>
          </tr>
        </table>
      </form>
	  <?
	  }
	  ?>
      <br>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>