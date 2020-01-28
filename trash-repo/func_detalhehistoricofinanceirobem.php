<?php
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

$oGet = db_utils::postMemory($_GET, false);

$oDaoBensHistoricoCalculoBem      = db_utils::getDao("benshistoricocalculobem");
$sCamposBuscaHistoricoFinanceiro  = "t57_datacalculo,";
$sCamposBuscaHistoricoFinanceiro .= "t58_valoranterior,";
$sCamposBuscaHistoricoFinanceiro .= "t58_valorcalculado,";
$sCamposBuscaHistoricoFinanceiro .= "t58_valoratual as dl_Valor_Depreciável,";
$sCamposBuscaHistoricoFinanceiro .= "case when t57_tipoprocessamento = 1 ";
$sCamposBuscaHistoricoFinanceiro .= "     then 'Automático' ";
$sCamposBuscaHistoricoFinanceiro .= "     else 'Manual' end as t57_tipoprocessamento ,";
$sCamposBuscaHistoricoFinanceiro .= "case when t57_tipocalculo = 1 ";
$sCamposBuscaHistoricoFinanceiro .= "     then 'Depreciação' ";
$sCamposBuscaHistoricoFinanceiro .= "     else 'Reavaliação' end as t57_tipocalculo ,";
$sCamposBuscaHistoricoFinanceiro .= "CASE WHEN t57_processado IS FALSE ";
$sCamposBuscaHistoricoFinanceiro .= "     THEN 'Desprocessado' ";
$sCamposBuscaHistoricoFinanceiro .= "     ELSE 'Processado' END as t57_processado, ";
$sCamposBuscaHistoricoFinanceiro .= "fc_mesextenso(t57_mes, 'sigla') || '/' || t57_ano AS dl_Competencia";
$sWhereBuscaHistoricoFinanceiro   = " t58_bens = {$oGet->t52_bem} ";
$sOrder                           =  "t57_ano desc, t57_mes desc";
$sSqlBuscaHistoricoFinanceiro     = $oDaoBensHistoricoCalculoBem->sql_query(null, $sCamposBuscaHistoricoFinanceiro,
                                                                            $sOrder, $sWhereBuscaHistoricoFinanceiro);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
  <body>
    <center>
      <fieldset>
        <legend style='font-weight:bold'>Historico Financeiro</legend>
        <?php
          db_lovrot($sSqlBuscaHistoricoFinanceiro, 15, "", "");
        ?>
      </fieldset>
    </center>
  </body>
</html>