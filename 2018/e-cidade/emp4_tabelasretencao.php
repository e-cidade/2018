<?php
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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$oDaoInssIrf     = db_utils::getDao("inssirf");
$sSqlTabelaIRRF  = "select r33_inic,                                               ";
$sSqlTabelaIRRF .= "       r33_fim,                                                ";
$sSqlTabelaIRRF .= "       r33_deduzi,                                             ";
$sSqlTabelaIRRF .= "       r33_perc                                                ";
$sSqlTabelaIRRF .= "  from inssirf                                                 ";
$sSqlTabelaIRRF .= " where r33_codtab = 1                                          ";
$sSqlTabelaIRRF .= "   and r33_anousu = ".db_getsession("DB_anousu")." ";
$sSqlTabelaIRRF .= "   and r33_mesusu = (select max(r33_mesusu) ";
$sSqlTabelaIRRF .= "                       from inssirf where r33_codtab = 1 and r33_anousu = ".db_getsession("DB_anousu").") ";
$sSqlTabelaIRRF .= "   and r33_instit = ".db_getsession("DB_instit");
$sSqlTabelaIRRF .= " order by r33_inic                                     ";
$rsTabelaIrrf    = $oDaoInssIrf->sql_record($sSqlTabelaIRRF);
$aTabelaIrrf     = db_utils::getColectionByRecord($rsTabelaIrrf);



$oDaoCfPess    = db_utils::getDao("cfpess");
$sSqlCodTabela = $oDaoCfPess->sql_query(null, null, null, "r11_tbprev", "r11_anousu desc,
                                             r11_mesusu desc
                                             limit 1", "r11_instit = " . db_getsession("DB_instit"));
$rsCodTabela = $oDaoCfPess->sql_record($sSqlCodTabela);
if ($oDaoCfPess->numrows == 0) {
  
  throw new Exception("Não há nenhuma configuracao de tabela de Inss!\nConfira.");
}

$iCodigoTabelaInss = (db_utils::fieldsMemory($rsCodTabela, 0)->r11_tbprev + 2);
/*
     * Retorna a tabela do imposto de renda que devemos usar.
     */
$oDaoInssIrf = db_utils::getDao("inssirf");
$sSqlTabelaINSS = "select r33_inic,                                               ";
$sSqlTabelaINSS .= "       r33_fim,                                                ";
$sSqlTabelaINSS .= "       r33_deduzi,                                             ";
$sSqlTabelaINSS .= "       r33_perc,                                               ";
$sSqlTabelaINSS .= "       (select max(r33_fim)                                    ";
$sSqlTabelaINSS .= "          from inssirf  a                                      ";
$sSqlTabelaINSS .= "         where a.r33_anousu = inssirf.r33_anousu               ";
$sSqlTabelaINSS .= "           and a.r33_mesusu = inssirf.r33_mesusu               ";
$sSqlTabelaINSS .= "           and a.r33_instit = inssirf.r33_instit               ";
$sSqlTabelaINSS .= "           and a.r33_codtab = {$iCodigoTabelaInss}) as teto    ";
$sSqlTabelaINSS .= "  from inssirf                                                 ";
$sSqlTabelaINSS .= " where r33_codtab = {$iCodigoTabelaInss}                       ";
$sSqlTabelaINSS .= "   and r33_anousu = ".db_getsession("DB_anousu")." ";
$sSqlTabelaINSS .= "   and r33_mesusu = (select max(r33_mesusu) ";
$sSqlTabelaINSS .= "                       from inssirf where r33_codtab = 1 and r33_anousu = ".db_getsession("DB_anousu").") ";
$sSqlTabelaINSS .= "   and r33_instit = " . db_getsession("DB_instit");
$sSqlTabelaINSS .= " order by r33_inic                                     ";
$rsTabelaInss = $oDaoInssIrf->sql_record($sSqlTabelaINSS);
$aTabelaINSS  = db_utils::getColectionByRecord($rsTabelaInss);
echo pg_last_error();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
  src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"
  src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript"
  src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript"
  src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top: 10px">
<center>
<table>
  <tr>
    <td>
    <fieldset><legend><b>Tabela do IRRF</legend>
    <table cellspacing="0" style='background-color: white'>
      <tr>
        <th class='table_header'>Inicio</th>
        <th class='table_header'>Fim</th>
        <th class='table_header'>Dedução</th>
        <th class='table_header'>Aliquota</th>
      </tr>  
               <?
               foreach ($aTabelaIrrf as $oRetencao) {

                 echo "<tr>";
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_inic}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_fim}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_deduzi}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_perc}</td>";
                 echo "</tr>";   
                }
                ?>
             </table>
    </fieldset>
    </td>
  </tr>
  
   <tr>
    <td>
    <fieldset><legend><b>Tabela do INSS</legend>
    <table cellspacing="0" style='background-color: white' width="100%">
      <tr>
        <th class='table_header'>Inicio</th>
        <th class='table_header'>Fim</th>
        <th class='table_header'>Dedução</th>
        <th class='table_header'>Aliquota</th>
      </tr>  
               <?
               foreach ($aTabelaINSS as $oRetencao) {

                 echo "<tr>";
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_inic}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_fim}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_deduzi}</td>"; 
                 echo "  <td style='text-align:right' class='linhagrid'>{$oRetencao->r33_perc}</td>";
                 echo "</tr>";   
                }
                ?>
             </table>
    </fieldset>
    </td>
  </tr>
</table>
</center>
</body>