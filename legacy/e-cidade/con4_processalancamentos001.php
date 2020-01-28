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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$aTiposReprocessamento = array(1 => 'Passivo sem suporte orçamentário',
                               2 => 'Acordos',
                               3 => 'Suprimento de fundos',
                               4 => 'Movimentação patrimonial',
                               5 => 'Reconhecimento contábil',
                               6 => 'Despesas e receitas',
                               7 => 'Operações extra-orçamentárias');
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="expires" content="0">
  <link href="estilos.css" rel="stylesheet" type="text/css" />
  <?php db_app::load('scripts.js, strings.js, prototype.js'); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

  <center> 

    <fieldset class="container" style="width:450px;">

      <legend id="lgdReprocessarLancamento">Reprocessar Lançamentos</legend>

      <table class="form-container">

        <tr>   
          <td>
            <strong id="lTipoReprocessamento">Tipo de Reprocessamento: </strong>
          </td>
          <td> 
            <?php db_select('iTipoReprocessamento', $aTiposReprocessamento, true, 1); ?>
          </td>
        </tr>

      </table>

    </fieldset>

    <br />
    <input type="button" id="proximo" value="Próximo" onClick="js_proximo();" />

  </center>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

const DESPESAS_RECEITAS = 6;

/**
 * Redireciona para proxima tela
 * - passando parametro com tipo de processamento
 * - caso for despesas e receitas redireciona para o fonte antigo 
 *
 * @access public
 * @return void
 */
function js_proximo() {

  var iTipoReprocessamento = $('iTipoReprocessamento').value;
  var sPrograma            = 'con4_processalancamentos002.php';

  switch (iTipoReprocessamento) {

    case "4" : // movimentacao patrimonial
  
      sPrograma = 'con4_movimentacaopatrimonial001.php';
    break;  

    case "6" :  //Despesas e receitas
      sPrograma = 'con4_processatrans001.php';
    break;

    case "7" : //Operações Extra-Orçamentárias
      sPrograma = 'con4_operacoesextraorcamentarias001.php';
    break;
  } 

  document.location.href = sPrograma + '?iTipoReprocessamento=' + iTipoReprocessamento;
}
</script>