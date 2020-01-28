<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($HTTP_GET_VARS);

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  
  <?
    db_app::load('scripts.js,estilos.css,prototype.js, dbmessageBoard.widget.js, windowAux.widget.js');
    db_app::load('dbtextField.widget.js, dbcomboBox.widget.js, DBViewGeracaoAutorizacao.classe.js, grid.style.css');
    db_app::load('datagrid.widget.js, strings.js, arrays.js');
  ?>
  
<style type="">
td.linhagrid {padding: 1px}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<br />
<br />
<center>
  <div id="cntAutorizacao">
  </div>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
  var oViewGeracaoAutorizacao = new DBViewGeracaoAutorizacao('oViewGeracaoAutorizacao',$('cntAutorizacao'));
  oViewGeracaoAutorizacao.setOrigem(<?=$oGet->l20_codigo;?>);
  oViewGeracaoAutorizacao.setInstituicao(<?=db_getsession('DB_instit');?>);
  oViewGeracaoAutorizacao.setAno(<?=db_getsession("DB_anousu");?>);
  oViewGeracaoAutorizacao.show();


  function js_preencheCaracteristica(iCodigo, sDescricao) {
  
    $('oTxtCaractPeculiarCod').value  = iCodigo;
    $('oTxtCaractPeculiarDesc').value = sDescricao
    db_iframe_concarpeculiar.hide();  
  }
  
  function js_completaCaracteristica (sDescricao, lErro) {
  
    if (lErro) {
    
      $('oTxtCaractPeculiarCod').value  = '';
      $('oTxtCaractPeculiarDesc').value = sDescricao
    } else {
      $('oTxtCaractPeculiarDesc').value = sDescricao
    }
  }
  
</script>