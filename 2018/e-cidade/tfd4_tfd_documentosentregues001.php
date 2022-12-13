<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");


function formataData($dData, $iTipo = 1) {

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

db_postmemory($HTTP_POST_VARS);

$oDaotfd_documentosentregues = db_utils::getdao('tfd_documentosentregues');
$oDaotfd_tipotratamentodoc= db_utils::getdao('tfd_tipotratamentodoc');

$db_opcao = 1;
$db_botao = true;

if(isset($confirmar)) {

  db_inicio_transacao();
  $sSql = $oDaotfd_documentosentregues->sql_query(null, ' tf22_i_codigo ', null,
                                                  " tf22_i_pedidotfd = $tf01_i_codigo ");
  $rs = $oDaotfd_documentosentregues->sql_record($sSql);
  $iLinhas = $oDaotfd_documentosentregues->numrows;
  $oDaotfd_documentosentregues->erro_status = null; // faço isso porque se a busca não retornar resultado,
                                                    // o erro_status é setado para 0, com a mensagem de record vazio
  /*
  * Laco que exclui todos os documentos já entregues
  */
  for($iCont = 0; $iCont < $iLinhas; $iCont++) {
     
    $oDados = db_utils::fieldsmemory($rs, $iCont);
    $oDaotfd_documentosentregues->tf22_i_codigo = $oDados->tf22_i_codigo;
    $oDaotfd_documentosentregues->excluir($oDados->tf22_i_codigo);
    if($oDaotfd_documentosentregues->erro_status == '0') {
      break;
    }

  }

  /* Insere os documentos */
  if($oDaotfd_documentosentregues->erro_status != '0') {

    $aDocumentos = explode(' ## ', $entregues);
    $iTam = count($aDocumentos) - 1;
    for($iCont = 0; $iCont < $iTam; $iCont++) {

      $aInfo = explode(',', $aDocumentos[$iCont]);
      $oDaotfd_documentosentregues->tf22_i_pedidotfd = $tf01_i_codigo;
      $oDaotfd_documentosentregues->tf22_i_documento = $aInfo[0];
      $oDaotfd_documentosentregues->tf22_d_dataentrega = formataData($aInfo[1]);
      $oDaotfd_documentosentregues->tf22_c_horaentrega = date('H:i');
      $oDaotfd_documentosentregues->tf22_c_numdoc = $aInfo[2];
      $oDaotfd_documentosentregues->incluir(null);
      if($oDaotfd_documentosentregues->erro_status == '0') {
        break;
      }

    }

  }

  db_fim_transacao($oDaotfd_documentosentregues->erro_status == '0' ? true : false);

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load(" grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
</table>
<table width="850" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="530" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset style='width: 98%;'> <legend><b>Indique os Documentos</b></legend>
     	  <?
	      require_once("forms/db_frmtfd_documentosentregues.php");
	      ?>
      </center>
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
js_tabulacaoforms("form1","tf22_i_pedidotfd",true,1,"tf22_i_pedidotfd",true);
</script>
<?
if(isset($confirmar)) {

  if($oDaotfd_documentosentregues->erro_status=="0") {

    $oDaotfd_documentosentregues->erro(true, false);
    $db_botao=true;
    echo "<script>$('entregues').value = '-1';</script>";

  } else {

    $oDaotfd_documentosentregues->erro(true, false);
    echo '<script>parent.db_iframe_documentos.hide();</script>';

  }

}
?>