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

//include("classes/db_recibocanc_classe.php");
//$clrecibocanc   = new cl_recibocanc;

//include("classes/db_recibo_classe.php");
//$clrecibo   = new cl_recibo;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 3;



$clrotulo = new rotulocampo;
$clrotulo->label("k00_numpre");
$clrotulo->label("k00_numcgm");
$clrotulo->label("k00_receit");
$clrotulo->label("k00_valor");
$db_botao=false;

if(isset($excluir)){
  db_inicio_transacao();
	$sqlerro = false;
  $sql     = "select * from recibo where k00_numpre=$k00_numpre and k00_receit=$k00_receit and k00_numcgm=$k00_numcgm";
  $result  = pg_query($sql);
  $numrows = pg_numrows($result);
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
		 $k00_codsubrec =  $k00_codsubrec == ''?'0':$k00_codsubrec;
     $sql = "insert into recibocanc(
                                      p99_numcgm 
                                      ,p99_dtoper 
                                      ,p99_receit 
                                      ,p99_hist 
                                      ,p99_valor 
                                      ,p99_dtvenc 
                                      ,p99_numpre 
                                      ,p99_numpar 
                                      ,p99_numtot 
                                      ,p99_numdig 
                                      ,p99_tipo 
                                      ,p99_tipojm 
                                      ,p99_codsubrec 
                       )
                values (
                                $k00_numcgm 
                               ,'$k00_dtoper' 
                               ,$k00_receit 
                               ,$k00_hist 
                               ,$k00_valor 
                               ,'$k00_dtvenc' 
                               ,$k00_numpre 
                               ,$k00_numpar 
                               ,$k00_numtot 
                               ,$k00_numdig 
                               ,$k00_tipo 
                               ,$k00_tipojm 
                               ,$k00_codsubrec
                      )";
     $result = @pg_query($sql);		      
		 echo pg_last_error();
     if($result==false){
		      echo $sql;die();
       $sqlerro=true;
       $erro_msg='Erro ao incluir em recibocanc...';
     }
     
  }
  if($sqlerro==false){
    $sql = "delete from recibo where k00_numpre=$k00_numpre and k00_receit=$k00_receit and k00_numcgm=$k00_numcgm";
    $result = @pg_query($sql);		      
    if($result==false){
       $sqlerro=true;
       $erro_msg='Erro ao excluir do recibo..';
    }
  }  
  
  
 db_fim_transacao($sqlerro);
  
}

if(isset($k00_numpre)){
  $result =  pg_query("select k00_numpre from arrepaga where k00_numcgm=$k00_numpre and k00_numcgm=$k00_numcgm");
  if(pg_numrows($result) > 0){
    $erro_msg02 = 'Recibo já foi pago!'; 
    $db_botao=false;
    
  }else{
    $db_botao=true;
  }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1' action=''>
    <center>
      <table>
		<tr>
		  <td nowrap title="<?=@$Tk00_numcgm?>" align='right'>
		     <?=$Lk00_numcgm?>
		  </td>
		  <td> 
		     <? db_input('k00_numcgm',8,$Ik00_numcgm,true,'text',$db_opcao)     ?>
		  </td>
		</tr>
		<tr>
		  <td nowrap title="<?=@$Tk00_receit?>" align='right'>
		     <?=$Lk00_receit?>
		  </td>
		  <td> 
		     <? db_input('k00_receit',8,$Ik00_receit,true,'text',$db_opcao)     ?>
		  </td>
		</tr>
		<tr>
		  <td nowrap title="<?=@$Tk00_numpre?>" align='right'>
		     <?=$Lk00_numpre?>
		  </td>
		  <td> 
		     <? db_input('k00_numpre',8,$Ik00_numpre,true,'text',$db_opcao)     ?>
		  </td>
		</tr>
		<tr>
		  <td nowrap title="<?=@$Tk00_valor?>" align='right'>
		     <?=$Lk00_valor?>
		  </td>
		  <td> 
		     <? db_input('k00_valor',8,$Ik00_valor,true,'text',$db_opcao)     ?>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' align='center'>
		    <input name="excluir" type="submit" id="pesquisar" value="Excluir"  <?=($db_botao==false?"disabled":"")?> >
		    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar recibo" onclick="js_pes();" >
		  </td>
		</tr>
      </table>
      </form>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
if(isset($erro_msg02)){
  db_msgbox($erro_msg);

}
?>
</body>
</html>
<script>

function js_pes(){
    js_OpenJanelaIframe('top.corpo','db_iframe_recibo','func_recibo.php?funcao_js=parent.js_vai|k00_numcgm|k00_receit|k00_valor|k00_numpre','Pesquisa',true);
}
function js_vai(c1,c2,c3,c4){
  obj= document.form1;
  obj.k00_numcgm.value = c1;
  obj.k00_receit.value = c2;
  obj.k00_valor.value = c3;
  obj.k00_numpre.value = c4;
  document.form1.submit();
  db_iframe_recibo.hide();
}
<?
if(empty($k00_numpre)){
  echo 'js_pes();';
}
?>
</script>
<?
if(isset($excluir)){
  if($sqlerro==false){
    db_msgbox('Exclusão efetuada com sucesso!');
    db_redireciona('cai4_cancelarecibo001.php');
  }else{
    db_msgbox($erro_msg);  
  }
}


?>