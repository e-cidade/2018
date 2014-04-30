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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tfd_avisopaciente_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE = new cl_iframe_alterar_excluir();
$oDaotfd_avisopaciente = db_utils::getdao('tfd_avisopaciente');
$oDaotfd_formaaviso = db_utils::getdao('tfd_formaaviso');

$db_opcao = 1;
$db_botao = true;

if(isset($opcao)) {
  
  if($opcao == 'alterar') {
    $db_opcao = 2;
  } else {
    $db_opcao = 3;
  }

}

if(isset($incluir)) {

  db_inicio_transacao();
  $oDaotfd_avisopaciente->tf21_d_dataaviso = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaotfd_avisopaciente->tf21_c_horaaviso = date('H:i');
  $oDaotfd_avisopaciente->tf21_i_login = db_getsession('DB_id_usuario');
  $oDaotfd_avisopaciente->incluir(null);
  db_fim_transacao($oDaotfd_avisopaciente->erro_status == '0' ? true : false);

}
if(isset($alterar)) {

  $db_opcao = 2;
  $opcao = 'alterar';
  db_inicio_transacao();
  $oDaotfd_avisopaciente->alterar($tf21_i_codigo);
  db_fim_transacao($oDaotfd_avisopaciente->erro_status == '0' ? true : false);

}
if(isset($excluir)) {

  $db_opcao = 3;
  $opcao = 'excluir';
  db_inicio_transacao();
  $oDaotfd_avisopaciente->excluir($tf21_i_codigo);
  db_fim_transacao($oDaotfd_avisopaciente->erro_status == '0' ? true : false);

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
<br><br>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 80%;'> <legend><b>Aviso de Agendamento</b></legend>
	      <?
	      require_once("forms/db_frmtfd_avisopaciente.php");
	      ?>
    </center>
	</td>
  </tr>
</table>
</center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf21_i_pedidotfd",true,1,"tf21_i_pedidotfd",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)) {

  if($oDaotfd_avisopaciente->erro_status == '0') {

    $oDaotfd_avisopaciente->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script> ";

  } else {

    $oDaotfd_avisopaciente->erro(true, false);
    db_redireciona('tfd4_tfd_avisopaciente001.php?tf21_i_pedidotfd='.
                   $tf21_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome);

  }

}
?>