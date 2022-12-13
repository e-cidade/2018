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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
require ("classes/db_orctiporec_classe.php");
require ("classes/db_orcdotacaocontr_classe.php");
require ("classes/db_orcdotacao_classe.php");

$oGet             = db_utils::postMemory($_GET);
$oDaoTipoRec      = new cl_orctiporec();
$oDaoDotacaocontr = new cl_orcdotacaocontr();
$oDaoDotacao      = new cl_orcdotacao();

/*
 * Procuramos contrapartidas cadastradas que estão ativas para a dotacao, caso nao encontramos nenhuma,
 * trazemos todos os recursos cadastrados.
 */
$rsContrapartidas = $oDaoDotacaocontr->sql_record($oDaoDotacaocontr->sql_query_convenios ($oGet->iCodDot, db_getsession("DB_anousu"),
                                                                                          date("Y-m-d",db_getsession("DB_datausu"))));
                                                                                          
$iNumRows         = $oDaoDotacaocontr->numrows; 
if ($oDaoDotacaocontr->numrows == 0) {
  
  $rsContrapartidas = $oDaoTipoRec->sql_record($oDaoTipoRec->sql_query_convenios(date("Y-m-d",db_getsession("DB_datausu"))));
  $iNumRows         = $oDaoTipoRec->numrows;
  
}
/*
 * Buscamos algumas informações sobre a dotação
 */
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>

<table width="70%">
  <tr>
    <td>
      <b>Dotação <?=$oGet->iCodDot?></b>
    </td>
    </tr>
    <tr>
      <td>
        <b>Valor Solicitado: <?=db_formatar($oGet->nValor,"f")?></b>
      </td>
        
  </tr>
  <tr> 
    <td colspan='2'>
    <fieldset><legend><b>Contrapartidas</b></legend>
    <table width="100%" cellspacing="0" style="border: 2px inset black;">
      <thead>
        <tr>
          <th class='table_header'>&nbsp;</th>
          <th class='table_header'>Recurso</th>
          <th class='table_header'>Descrição</th>
          <th class='table_header'>Percentual</th>
          <th class='table_header'>Valor do Convênio</th>
          <th class='table_header'>Valor Utilizado</th>
          <th style="width: 17px" class='table_header'>&nbsp;</th>
        </tr>
      </thead>
      <tbody style="background-color: white;height: 200px; overflow: scroll;overflow-x: hidden">
      <?
        for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

          $oContrapartidas = db_utils::fieldsMemory($rsContrapartidas, $iInd); 
          echo "<tr>";
          echo "  <td class='linhagrid'>";
          echo "     <input type='checkbox' id='rec{$oContrapartidas->o15_codigo}' value='{$oContrapartidas->o15_codigo}'>";
          echo "  </td>";
          echo "  <td class='linhagrid' style='text-align: right'>";
          echo "     {$oContrapartidas->o15_codigo}";
          echo "  </td>";
          echo "  <td class='linhagrid' style='text-align:left'>";
          echo "     {$oContrapartidas->o15_descr}";
          echo "  </td>";
          echo "  <td class='linhagrid' style='text-align:right'>";
          echo "     {$oContrapartidas->o16_percentual}%";
          echo "  </td>";
          echo "  <td class='linhagrid'style='text-align:right'>";
          echo    db_formatar($oContrapartidas->o16_valor,'f')  ;
          echo "  </td>";
          echo "  <td class='linhagrid' style='width:15%'>";
          echo "     <input type='text' size='15' id='valor{$oContrapartidas->o15_codigo}' value=''";
          echo "            onkeypress='return js_teclas(event)' style='width:100%'>";
          echo "  </td>";
          echo "</tr>";
        }
      ?>
        

      </tbody>
    </table>
    </fieldset>
    </td>
  </tr>
  
</table>
<input type="button" value='Confirmar' onclick='js_sendSession()'>
</center>
</body>
</html>