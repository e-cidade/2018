<?php
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
require_once("model/configuracao/Instituicao.model.php");
require_once("model/CgmFactory.model.php");
require_once("model/contabilidade/EventoContabil.model.php");
require_once("model/contabilidade/EventoContabilLancamento.model.php");
require_once("model/contabilidade/RegraLancamentoContabil.model.php");
$oGet = db_utils::postMemory($_GET);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php 
    db_app::load("scripts.js, strings.js, estilos.css, prototype.js, datagrid.widget.js, grid.style.css");
    db_app::load("classes/DBViewSlipPagamento.classe.js, widgets/dbtextField.widget.js, widgets/dbcomboBox.widget.js");
  ?>
</head>
<body style="background-color: #cccccc; margin-top: 25px;">
  <div align="center">
    <div id="ctnSlipPagamento">
    </div>
  </div>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
  var oDBViewSlipPagamento = new DBViewSlipPagamento("oDBViewSlipPagamento", 11, 1, $('ctnSlipPagamento'));
  oDBViewSlipPagamento.show();
  oDBViewSlipPagamento.setPCASPAtivo('<?php echo db_getsession('DB_use_pcasp');?>');
  oDBViewSlipPagamento.start();
</script>