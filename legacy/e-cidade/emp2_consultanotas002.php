<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empnota_classe.php");
require_once("classes/db_empnotaitem_classe.php");

$oGet            = db_utils::postMemory($_GET);
$oDaoEmpNota     = new cl_empnota();
$oDaoEmpNotaItem = new cl_empnotaitem();

$clrotulo        = new rotulocampo;
$clrotulo->label("e69_numero");
$clrotulo->label("e69_codnota");
$clrotulo->label("e50_codord");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_nome");
$clrotulo->label("e70_valor");
$clrotulo->label("e70_vlrliq");
$clrotulo->label("e70_vlranu");
$clrotulo->label("e53_vlrpag");
if (isset($oGet->e69_codnota)) {

  $sSqlNota  = "select e69_codnota,";
  $sSqlNota .= "       z01_nome,";
  $sSqlNota .= "       e69_dtnota,";
  $sSqlNota .= "       e69_numero,";
  $sSqlNota .= "       e60_codemp||'/'||e60_anousu as codemp,";
  $sSqlNota .= "       e70_valor,";
  $sSqlNota .= "       e70_vlrliq,";
  $sSqlNota .= "       e70_vlranu,";
  $sSqlNota .= "       e50_codord,";
  $sSqlNota .= "       e53_vlrpag";
  $sSqlNota .= "  from empnota ";
  $sSqlNota .= "          inner join empempenho   on e69_numemp  = e60_numemp";
  $sSqlNota .= "          inner join cgm          on e60_numcgm  = z01_numcgm";
  $sSqlNota .= "          inner join empnotaele   on e69_codnota = e70_codnota";
  $sSqlNota .= "          left  join pagordemnota on e71_codnota = e69_codnota";
  $sSqlNota .= "                                 and e71_anulado is false";
  $sSqlNota .= "          left  join pagordem    on  e71_codord = e50_codord";
  $sSqlNota .= "          left  join pagordemele  on e53_codord = e50_codord";
  $sSqlNota .= "  where e69_codnota = {$oGet->e69_codnota}";
  $rsNota    = $oDaoEmpNota->sql_record($sSqlNota);
  if ($oDaoEmpNota->numrows > 0 ) {

    $oNotas      = db_utils::FieldsMemory($rsNota, 0);
    $e69_codnota = $oNotas->e69_codnota;
    $e69_numero  = $oNotas->e69_numero;
    $codemp      = $oNotas->codemp;
    $z01_nome    = $oNotas->z01_nome;
    $e70_valor   = $oNotas->e70_valor;
    $e70_vlrliq  = $oNotas->e70_vlrliq;
    $e70_vlranu  = $oNotas->e70_vlranu;
    $e53_vlrpag  = $oNotas->e53_vlrpag;
    $e50_codord  = $oNotas->e50_codord;

  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" >
   <fieldset>
     <legend><b>Dados da nota</b></legend>
       <table>
         <tr>
           <td>
             <b>Código da Nota:</b>
           </td>
           <td>
              <?
              db_input('e69_codnota', 13, $Ie69_codnota, true, 'text', 3);
              ?>
           </td>
           <td>
             <b>Número:</b>
           </td>
           <td>
              <?
              db_input('e69_numero', 13, $Ie69_numero, true, 'text', 3);
              ?>
           </td>
            <td>
             <b><?php echo @$Le60_codemp;?></b>
           </td>
           <td>
              <?
              db_input('codemp', 13, $Ie60_codemp, true, 'text', 3);
              ?>
           </td>
            <td>
             <b>Nota de Liquidação:</b>
           </td>
           <td>
              <?
              db_input('e50_codord', 13, $Ie50_codord, true, 'text', 3);
              ?>
           </td>
         </tr>
         <tr>
           <td>
             <b><?=$Lz01_nome?></b>
           </td>
           <td colspan='8'>
              <?
              db_input('z01_nome', 70, $Lz01_nome, true, 'text', 3);
              ?>
           </td>
         </tr>
         <tr>
            <td>
             <b>Valor:</b>
           </td>
           <td>
              <?
              db_input('e70_valor', 13, $Ie70_valor, true, 'text', 3);
              ?>
           </td>
           <td>
             <b>Valor Liquidado:</b>
           </td>
           <td>
              <?
              db_input('e70_vlrliq', 13, $Ie70_vlrliq, true, 'text', 3);
              ?>
           </td>
           <td>
             <b>Valor Anulado: </b>
           </td>
           <td>
              <?
              db_input('e70_vlranu', 13, $Ie70_vlranu, true, 'text', 3);
              ?>
           </td>
           <td>
             <b>Valor Pago:</b>
           </td>
           <td>
              <?
              db_input('e53_vlrpag', 13, $Ie53_vlrpag, true, 'text', 3);
              ?>
           </td>
         </tr>
         </tr>
       </table>
   </fieldset>
   <fieldset>
     <legend>
       <b>Itens da Nota</b>
     </legend>
     <table>
     <form1 method='post' name='itens'>
       <?
       if ($oDaoEmpNota->numrows > 0) {

         $sWhere       = "e72_codnota = {$oNotas->e69_codnota}";
         $sCampos      = "e62_sequen, pc01_descrmater, e72_valor, e72_qtd,e72_vlrliq,e72_vlranu";
         $sSqltensNota = $oDaoEmpNotaItem->sql_query(null,$sCampos,"e62_sequen",$sWhere);
         db_lovrot($sSqltensNota, 15,'','',"","","Itens");
       }
       ?>
     </table>
     </form>
     <form name='form2' method='post'>
   </fieldset>
   <?

     if ($oNotas->e50_codord != null) {

      $oDaoRetencao  = db_utils::getDao("retencaoreceitas");
      $sSqlRetencoes = $oDaoRetencao->sql_query_consulta(null,
                                                      "e21_sequencial,
                                                       e21_descricao,
                                                       e23_dtcalculo,
                                                       e23_valor,
                                                       e23_valorbase,
                                                       e23_deducao,
                                                       e23_valorretencao,
                                                       e23_aliquota,
                                                       case when e23_recolhido is true then 'Sim'
                                                       else 'Não' end as
                                                       e23_recolhido,
                                                       k105_data,
                                                       numpre.k12_numpre,
                                                       q32_planilha",
                                                       "e23_sequencial",
                                                       "e20_pagordem  = {$oNotas->e50_codord}
                                                        and e27_principal is true
                                                        and e23_ativo is true"
                                                      );

      $rsRetencoes = $oDaoRetencao->sql_record($sSqlRetencoes);
      $iNumRows    = $oDaoRetencao->numrows;
      if ($iNumRows > 0) {

        $aRetencoes = db_utils::getCollectionByRecord($rsRetencoes, true);

        echo "<fieldset>";
        echo "  <legend><b>Retenções</b></legend>";
        echo "<table style='border: 2px inset white;' cellspacing='0'>";
        echo "  <tr>";
        echo "    <th class='table_header'>Código</th>";
        echo "    <th class='table_header'>Retenção</th>";
        echo "    <th class='table_header'>Data do Cálculo</th>";
        echo "    <th class='table_header'>Base de Calculo</th>";
        echo "    <th class='table_header'>Dedução</th>";
        echo "    <th class='table_header'>Valor</th>";
        echo "    <th class='table_header'>Aliquota</th>";
        echo "    <th class='table_header'>Recolhido</th>";
        echo "    <th class='table_header'>Data Autent.</th>";
        echo "    <th class='table_header'>Cod. Arrec</th>";
        echo "    <th class='table_header'>Planilha</th>";
        echo "    <th class='table_header' width='17px'>&nbsp;</th>";
        echo "  </tr>";
        echo "<tbody style='height:150px;width:100%;overflow:scroll;overflow-x:hidden;background-color:white'>";
        foreach ($aRetencoes as $oRetencao) {

          echo "<tr style='height:1em'>";
          echo "  <td class='linhagrid' style='text-align:right'>{$oRetencao->e21_sequencial}</td>\n";
          echo "  <td class='linhagrid' style='text-align:left'>{$oRetencao->e21_descricao}</td>\n";
          echo "  <td class='linhagrid' style='text-align:center'>{$oRetencao->e23_dtcalculo}</td>\n";
          echo "  <td class='linhagrid' style='text-align:right'>".db_formatar($oRetencao->e23_valorbase,"f")."</td>\n";
          echo "  <td class='linhagrid' style='text-align:right'>".db_formatar($oRetencao->e23_deducao,"f")."</td>\n";
          echo "  <td class='linhagrid' style='text-align:right'>".db_formatar($oRetencao->e23_valorretencao,"f")."</td>\n";
          echo "  <td class='linhagrid' style='text-align:right'>{$oRetencao->e23_aliquota}%</td>\n";
          echo "  <td class='linhagrid' style='text-align:left'>{$oRetencao->e23_recolhido}</td>\n";
          echo "  <td class='linhagrid' style='text-align:center'>{$oRetencao->k105_data}&nbsp;</td>\n";
          echo "  <td class='linhagrid' style='text-align:center'>{$oRetencao->k12_numpre}&nbsp;</td>\n";
          echo "  <td class='linhagrid' style='text-align:center'>{$oRetencao->q32_planilha}&nbsp;</td>\n";
          echo "  <td >&nbsp;</td>\n";
          echo "</tr>";

        }
        echo "<tr style='height:auto'><td>&nbsp;</td></tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</fieldset>";

      }
     }
   ?>
   </form>
</body>
</html>