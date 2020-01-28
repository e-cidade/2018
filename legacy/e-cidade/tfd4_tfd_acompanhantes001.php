<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_tfd_acompanhantes_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory( $_POST );

$oIframeAE                    = new cl_iframe_alterar_excluir();
$oDaotfd_acompanhantes        = db_utils::getdao('tfd_acompanhantes');
$oDaotfd_motivoacompanhamento = db_utils::getdao('tfd_motivoacompanhamento');
$oDaocgs_und                  = db_utils::getdao('cgs_und');

$db_opcao = 1;
$db_botao = true;

if( isset( $incluir ) ) {

  if( $tf01_i_cgsund == $tf13_i_cgsund ) {

    db_msgbox( "Inclusão não permitida. CGS do acompanhante é o mesmo do paciente." );
    db_redireciona( "tfd4_tfd_acompanhantes001.php?iPedido=" . $tf13_i_pedidotfd );
  }

  db_inicio_transacao();
  $oDaotfd_acompanhantes->tf13_i_anulado = 2;
  $oDaotfd_acompanhantes->incluir($tf13_i_codigo);
  db_fim_transacao($oDaotfd_acompanhantes->erro_status == '0' ? true : false);
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
<body class="body-container">
  <?php
  require_once(modification("forms/db_frmtfd_acompanhantes.php"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf13_i_cgsund",true,1,"tf13_i_cgsund",true);
</script>
<?php
if(isset($incluir)) {

  if($oDaotfd_acompanhantes->erro_status=="0") {

    $oDaotfd_acompanhantes->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  } else {

    $oDaotfd_acompanhantes->erro(true, false);
    db_redireciona('tfd4_tfd_acompanhantes001.php?tf13_i_pedidotfd='.
                   $tf13_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome);
  }
}