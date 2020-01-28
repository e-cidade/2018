<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DSeller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_propri_classe.php");
require_once("classes/db_promitente_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libpessoal.php");
$oPost     = db_utils::postMemory($_POST);
$oGet      = db_utils::postMemory($_GET);
$xtipo     = "'x'";

$iAnoFolha    = $ano;
$iMesFolha    = $mes;
$iInstituicao = db_getsession("DB_instit");

if ($opcao == 'suplementar') {

  $sigla          = 'r14_';
  $arquivo        = 'gerfsal';
  $sTituloCalculo = 'Suplementar';
  $sTituloTabela  = 'suplementar';
  $iTipoFolha     = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
} 

if ($opcao == 'salario') {

  $sigla          = 'r14_';
  $arquivo        = 'gerfsal';
  $sTituloCalculo = 'Salário';
  $sTituloTabela  = 'salario';
  $iTipoFolha     = FolhaPagamento::TIPO_FOLHA_SALARIO;
}

if ($opcao == 'complementar') {

  $sigla          = 'r48_';
  $arquivo        = 'gerfcom';
  $sTituloCalculo = 'Complementar';
  $sTituloTabela  = 'complementar';
  $iTipoFolha     = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;

} 

  
$oDaoFolhaPagamento = new cl_rhhistoricocalculo();
$sWhere             = " rh141_anousu        = $iAnoFolha";
$sWhere            .= " and rh141_mesusu    = $iMesFolha";
$sWhere            .= " and rh141_instit    = $iInstituicao";
$sWhere            .= " and rh143_regist    = $matricula";
$sWhere            .= " and rh141_tipofolha = $iTipoFolha";
$sSqlFolhas         = $oDaoFolhaPagamento->sql_query(null,  "distinct rh143_folhapagamento, rh141_codigo, rh141_aberto", "rh141_codigo desc", $sWhere);
$rsFolhas         = db_query($sSqlFolhas);
$aFolhas          = db_utils::getCollectionByRecord($rsFolhas);

foreach ( $aFolhas as $oFolha ) {

  $sSqlRegistros         = $oDaoFolhaPagamento->sql_query_registros_consulta_complementar($oFolha->rh143_folhapagamento, $bases, $matricula);
  $rsRegistros           = db_query($sSqlRegistros);
  $oFolha->aDadosValores = array();
  $oFolha->aDadosBases   = array();
  foreach ( db_utils::getCollectionByRecord($rsRegistros) as $oDados ) {

    if ( $oDados->ordem == 1 ) {
      $oFolha->aDadosValores[] = $oDados;
    } else {
      $oFolha->aDadosBases[]   = $oDados;
    }
  }

}

?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <style type="text/css">
      html, body, table {
        overflow: hidden;
      }
  
      #tabela-calculos, #tabela-calculos tr, #tabela-calculos td, #tabela-calculos th{
        border: 1px solid #bbb;
      }
     
      #tabela-calculos a { 
      }
  
  
      #tabela-calculos tr:nth-child(odd) {
        background-color: #EEEEEE;
      }
  
      #tabela-calculos tr:nth-child(even) {
        background-color: #FFFFFF;
      }
  
      #tabela-calculos tr:first-child {
        border-right:1px outset #D3D3D3;  
        padding:0;
        margin:0;
        overflow: hidden;
      }
  
      #tabela-calculos tr td {
        text-align: left;
        padding-left: 5px;
        padding-right: 5px;
      }
  
     #tabela-calculos tr.totais td[colspan='4'] { 
       text-align: center;
     }
      #tabela-calculos tr td:nth-child(2),
      #tabela-calculos tr td:nth-child(4),
      #tabela-calculos tr td:nth-child(5),
      #tabela-calculos tr td:nth-child(6) {
        text-align: right;
      }
      #tabela-calculos tr td:nth-child(2) {
        font-weight:bold; 
        padding-left: 0px;
        padding-right: 14px;
      }
      
     #tabela-calculos tr td:nth-child(1) {
        text-align: center;
      }
  
     #tabela-calculos tr.totais {
       background-color: #DDDDDD;
        text-align: right;
     }
     #tabela-calculos tr.totais td  {
       font-weight:bold;
        text-align: right;
        padding-right: 5px;
     }
    </style>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  
  <body onload="js_alteraHeightCalculoComplementar(); " >
  
    <form name="form1" method="post">


     <?php 

       foreach ( $aFolhas as $oFolha ) {

          if ($opcao == 'suplementar' || $opcao == 'complementar') {
            echo "<fieldset class='folha-{$sTituloTabela}' id='folha-{$oFolha->rh141_codigo}'> \n";
            echo "  <Legend>{$sTituloCalculo} nº {$oFolha->rh141_codigo}</Legend>              \n";
          }
          echo "  <table width='100%' id='tabela-calculos' cellspacing='0' />                \n";
          echo "    <tr>                                                                     \n";
          echo "      <th width='25'>Fórmula(*)</th>                                         \n";
          echo "      <th width='70'>Código</th>                                             \n";
          echo "      <th>Descrição</th>                                                     \n";
          echo "      <th width='80'>Quantidade</th>                                         \n";
          echo "      <th width='80'>Proventos</th>                                          \n";
          echo "      <th width='80'>Descontos</th>                                          \n";
          echo "      <th width='90'>Prov/Desc</th>                                          \n";
          echo "    </tr>                                                                    \n";
   
          $nTotalProventos = 0;
          $nTotalDescontos = 0;

          foreach ( $oFolha->aDadosValores as $oValores ) {
          
            $sRubrica = "<a href='#' id='{$oValores->rubrica}'>{$oValores->rubrica}</a>";
          
            if ( $oValores->tem_base_formula == 't' ) {
              $sRubrica = "B " . $sRubrica;
            }
          
            if ( $oValores->compoe_base == 't' ) {
              $sRubrica = "# " . $sRubrica;
            }
            
            echo "<tr>                                                   \n";
            echo "  <td> 1 </td>                                         \n";
            echo "  <td>" . $sRubrica                            . "</td>\n";
            echo "  <td>" . $oValores->rh27_descr                . "</td>\n";
            echo "  <td>" . db_formatar($oValores->quant,'f')    . "</td>\n";
            echo "  <td>" . db_formatar($oValores->provento,'f') . "</td>\n";
            echo "  <td>" . db_formatar($oValores->desconto,'f') . "</td>\n";
            echo "  <td>" . $oValores->provdesc                  . "</td>\n";
            echo "</tr>";
          
            $nTotalProventos += $oValores->provento;
            $nTotalDescontos += $oValores->desconto;
          }
          
          echo "  <tr class='totais'>                                     \n";
          echo "    <td colspan='4'>TOTAL</td>                            \n";
          echo "    <td>" . db_formatar($nTotalProventos,'f')    . "</td> \n";
          echo "    <td>" . db_formatar($nTotalDescontos,'f')    . "</td> \n";
          echo "    <td>&nbsp;</td>                                       \n";
          echo "  </tr>";
          
          echo "  <tr class='totais'>\n";
          echo "    <td colspan='4'>LÍQUIDO</td>\n";
          echo "    <td colspan='2'>" . db_formatar($nTotalProventos - $nTotalDescontos,'f')."</td>\n";
          echo "    <td>&nbsp;</td>\n";
          echo "  </tr>";
          
          foreach ( $oFolha->aDadosBases as $oBase ) {
          
            $sRubrica = "<a href='#' id='$oBase->rubrica'>{$oBase->rubrica}</a>";
          
            if ( $oBase->tem_base_formula == 't' ) {
              $sRubrica = "B " . $sRubrica;
            }
          
            if ( $oBase->compoe_base == 't' ) {
              $sRubrica = "# " . $sRubrica;
            }
            echo "  <tr>\n";
            echo "    <td> 1 </td>\n";
            echo "    <td>" . $sRubrica                         . "</td>\n";
            echo "    <td>" . $oBase->rh27_descr                . "</td>\n";
            echo "    <td>" . db_formatar($oBase->quant,'f')    . "</td>\n";
            echo "    <td>" . db_formatar($oBase->provento,'f') . "</td>\n";
            echo "    <td>" . db_formatar($oBase->desconto,'f') . "</td>\n";
            echo "    <td>" . $oBase->provdesc                  . "</td>\n";
            echo "  </tr>";
          }
          echo "  </table>";
          if ($opcao == 'suplementar' || $opcao == 'complementar') {
            echo "</fieldset>";
          }
       }
          ?>

        <input type="hidden" name="opcao" value="<?=@$opcao?>">  
        <input type="hidden" name="matricula" value="<?=@$matricula?>">
        <input type="hidden" name="numcgm" value="<?=@$numcgm?>">
    </form>
  </body>
</html>
<script>

require_once('scripts/strings.js');
require_once("scripts/widgets/DBToogle.widget.js");

var sOpcao = js_urlToObject().opcao;
 
 if ( sOpcao == 'complementar' ) {
  $$('.folha-complementar').each(function( oElemento, iIndice ){
    var oToggle = new DBToogle(oElemento.id, iIndice == 0 );
    oToggle.afterClick = function() {
      js_alteraHeightCalculoComplementar();
    };
  });
 }

 if ( sOpcao == 'suplementar' ) {
  $$('.folha-suplementar').each(function( oElemento, iIndice ){
    var oToggle = new DBToogle(oElemento.id, iIndice == 0 );
    oToggle.afterClick = function() {
      js_alteraHeightCalculoComplementar();
    };
  });
 }

  parent.document.formatu.opcao.value                     = "<?= $opcao; ?>";
  parent.document.getElementById('tituloFolha').innerHTML = "<?= $sTituloCalculo; ?>";

  

  function js_relatorio(){
    jan = window.open('pes3_gerfinanc017.php?opcao=<?=$opcao?>&numcgm='+document.form1.numcgm.value+'&matricula='+document.form1.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?=$tbprev?>','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  	  
  }
  
  function js_Pesquisarubrica( rubrica ) {
    
   var janela = js_OpenJanelaIframe('top.corpo','db_iframe_pesquisarubrica','pes1_rhrubricas006.php?tela_pesquisa=true&chavepesquisa='+rubrica,'Pesquisa',true,'20');
   janela.moldura.style.zIndex = 9999;
  }
  

  function js_alteraHeightCalculoComplementar() {

    var
      html = document.documentElement,
      fieldset = parent.document.getElementById('calculoFolha');

    fieldset.style.height = html.scrollHeight + 7 + 'px';
    parent.iframeLoaded();
  }

  var aLinks = document.querySelectorAll("#tabela-calculos tr td:nth-child(2) a");

  for( var iLink = 0; iLink < aLinks.length; iLink++ ) {
    aLinks[iLink].setAttribute("onClick", "js_Pesquisarubrica(this.id)");
  }
</script>
