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
include("classes/db_lab_laboratorio_classe.php");
include("classes/db_lab_labdepart_classe.php");
include("classes/db_lab_labcgm_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllab_laboratorio = new cl_lab_laboratorio;
$oDaoLabLabDepart  = new cl_lab_labdepart;
$oDaoLabLabCgm     = new cl_lab_labcgm;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){

  db_inicio_transacao();

  $db_opcao = 3;

  // Excluo a ligacao do laboratorio com um departamento, caso exista
  $oDaoLabLabDepart->excluir(null, 'la03_i_laboratorio = '.$la02_i_codigo);
  if ($oDaoLabLabDepart->erro_status == '0') {

    $cllab_laboratorio->erro_status = '0';
    $cllab_laboratorio->erro_msg    = $oDaoLabLabDepart->erro_msg;

  }

  if ($cllab_laboratorio->erro_status != '0') {

   // Excluo a ligacao do laboratorio com um CGM, caso exista
    $oDaoLabLabCgm->excluir(null, 'la04_i_laboratorio = '.$la02_i_codigo);
    if ($oDaoLabLabCgm->erro_status == '0') {
  
      $cllab_laboratorio->erro_status = '0';
      $cllab_laboratorio->erro_msg    = $oDaoLabLabCgm->erro_msg;
  
    }

  }

  if ($cllab_laboratorio->erro_status != '0') {
    $cllab_laboratorio->excluir($la02_i_codigo);
  }
  db_fim_transacao($cllab_laboratorio->erro_status == '0');
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cllab_laboratorio->sql_record($cllab_laboratorio->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <br><br>
    <fieldset><legend><b> Laboratorio </b><legend>
	<?
	include("forms/db_frmlab_laboratorio.php");
	?>
    </fieldset>
    </center>
	</td>
  </tr>
</table>
<center>
</body>
</html>
<?
if(isset($excluir)){
  if($cllab_laboratorio->erro_status=="0"){
    $cllab_laboratorio->erro(true,false);
  }else{
    $cllab_laboratorio->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>