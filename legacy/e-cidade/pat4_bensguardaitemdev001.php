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
require_once("libs/db_utils.php");
require_once("classes/db_bensguardaitemdev_classe.php");
require_once("classes/db_bensguardaitem_classe.php");
require_once("classes/db_bensguarda_classe.php");
require_once("classes/db_situabens_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clbensguardaitemdev = new cl_bensguardaitemdev;
$clbensguardaitem    = new cl_bensguardaitem;
$clbensguarda        = new cl_bensguarda;
$clsituabens         = new cl_situabens;
$db_opcao            = 22;
$db_botao            = false;
$sqlerro             = false;

$oDaoDocumento = db_utils::getDao('db_documentotemplate');
$oDaoDocumento = new cl_db_documentotemplate();
$sCampos       = " db82_sequencial, db82_descricao";

$sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, "db82_sequencial desc", "db82_templatetipo = 32");
$rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);
$iCodigoTemplate       = db_utils::fieldsMemory($rsDocumentoTemplate, 0)->db82_sequencial;


if (isset($incluir)) {
  
  if ($chaves != "") {
    
    db_inicio_transacao();
    $dados = split("#", $chaves);
    for ($w = 0; $w < count($dados); $w++) {
      
      if ($sqlerro == false) {
        
        $clbensguardaitemdev->t23_situacao = $t23_situacao;
        $clbensguardaitemdev->t23_data = $t23_data_ano . "-" . $t23_data_mes . "-" . $t23_data_dia;
        $clbensguardaitemdev->t23_obs = "$t23_obs";
        $clbensguardaitemdev->t23_usuario = db_getsession("DB_id_usuario");
        $clbensguardaitemdev->incluir($dados[$w]);
        $erro_msg = $clbensguardaitemdev->erro_msg;
        if ($clbensguardaitemdev->erro_status == 0) {
          $sqlerro = true;
        }
      }
    }
    db_fim_transacao($sqlerro);
  } else {
    
    $sqlerro = true;
    $erro_mgs = _M("patrimonial.patrimonio.db_frmbensguardaitemdev.devolucao_cancelada");
  }
} else if (isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $db_botao = true;
  $result = $clbensguarda->sql_record($clbensguarda->sql_query($chavepesquisa));
  
  if ($result != false && $clbensguarda->numrows > 0) {
    
    db_fieldsmemory($result, 0);
    $t22_bensguarda = $t21_codigo;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

  <?php
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC >
    	<?
    	  include("forms/db_frmbensguardaitemdev.php");
      ?>
	
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
        db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($incluir)) {
  db_msgbox($erro_msg);
  if ($clbensguardaitemdev->erro_campo != "" || $sqlerro == true) {
    echo "<script> document.form1." . $clbensguardaitemdev->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1." . $clbensguardaitemdev->erro_campo . ".focus();</script>";
  }
  //$sMsg = _M('patrimonial.patrimonio.db_frmbensguardaitemdev.deseja_imprimir');
  echo "<script>";
  echo "if (confirm(_M('patrimonial.patrimonio.db_frmbensguardaitemdev.deseja_imprimir'))) {";
  echo "  jan = window.open('pat2_emitedevolucaotermodeguarda002.php?iModeloImpressao={$iCodigoTemplate}&devolucao=true&iCodigoTermo={$t22_bensguarda}', '',
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1');";
  echo "}";
  echo "</script>";
  
  if ($sqlerro == false) {
    echo "<script>location.href='pat4_bensguardaitemdev001.php';</script>";
  }
}
if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>js_pesquisa();</script>";
}
?>