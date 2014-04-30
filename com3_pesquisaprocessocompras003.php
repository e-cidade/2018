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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");
require_once("model/ProcessoCompras.model.php");
require_once("dbforms/verticalTab.widget.php");
$oGet = db_utils::postMemory($_GET);
$oDaoPcProc = db_utils::getDao("pcproc");
$oDaoPcProc->rotulo->label();
$oRotulo    = new rotulocampo;
$oRotulo->label("pc80_codproc");
db_postmemory($HTTP_POST_VARS);
$oProcessoCompras = new ProcessoCompras($oGet->pc80_codproc);
$sSituacaoProcesso = '';
switch ($oProcessoCompras->getSituacao()) {
  
  case 1:
    
    $sSituacaoProcesso = 'EM ANÁLISE';
    break;
    
  case 2:
    
    $sSituacaoProcesso = 'AUTORIZADO';
    break;

  case 3:
    
    $sSituacaoProcesso = 'NÃO AUTORIZADO';
    break;  
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
    <style>
      table.valores {
        width: 50%
      }
     td.valores {
       background-color: #FFFFFF;
       width: 200px;
     }
     td.label {
     
       width: 100px;
       white-space: nowrap;
     } 
    </style>
  </head>
  <body background="#cccccc">
  
  <fieldset>
    <legend>
      <b>Dados do Processo de Compras</b> 
    </legend>
    <table class='valores' cellpadding="2" cellspacing="1">
       <tr>
          <td class="label"><?=$Lpc80_codproc ?> </td>
          <td class="valores" style="text-align: right"><?=$oProcessoCompras->getCodigo()?></td>
          <td class="label"><?=$Lpc80_data?> </td>
          <td class="valores"><?=$oProcessoCompras->getDataEmissao()?></td>
       </tr>
       <tr>
          <td class="label"><?=$Lpc80_depto ?> </td>
          <td colspan="3" class="valores">
            <?=$oProcessoCompras->getCodigoDepartamento()." - ".$oProcessoCompras->getDescricaoDepartamento()?>
          </td>  
       </tr>
       <tr>
          <td class="label"><?=$Lpc80_usuario ?> </td>
          <td colspan="3" class="valores">
            <?=$oProcessoCompras->getUsuario()." - ".$oProcessoCompras->getNomeUsuario()?>
          </td>  
       </tr>
       <tr>
          <td class="label"><?=$Lpc80_situacao ?> </td>
          <td colspan="3" class="valores">
            <?=$oProcessoCompras->getSituacao(). " - {$sSituacaoProcesso}"?>
          </td>  
       </tr>
       <tr>
          <td class="label"><?=$Lpc80_resumo ?> </td>
          <td colspan="3" class="valores">
            <?=nl2br($oProcessoCompras->getResumo())?>
          </td>  
       </tr>
    </table>
  </fieldset>
  <fieldset>
    <legend>
      <b>Outros Dados</b>
    </legend>
    <?
    $oTabDetalhes = new verticalTab("detalhesProcessoCompras",300);
    
    $oTabDetalhes->add("processoItens", "Itens", 
                       "com3_pesquisaprocessocomprasitens.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->add("processoLicitacoes", "Licitações",
                       "com3_pesquisaprocessocompraslicitacao.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->add("processoAutorizacoes", "Autorizações de Empenho", 
                       "com3_pesquisaprocessocompraautorizacoes.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->add("processoEmpenho", "Empenhos",
                       "com3_pesquisaprocessocompraempenho.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->add("processoContratos", "Acordos",
        "com3_pesquisaprocessocompracontrato.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->add("processoContratos", "Registro de Preço",
        "com3_pesquisaregistropreco.php?iProcesso={$oGet->pc80_codproc}");
    
    $oTabDetalhes->show();
    ?>
  </fieldset>
  </body>
</html>