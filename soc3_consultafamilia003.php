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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/verticalTab.widget.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");

$oGet              = db_utils::postMemory($_GET);
$oFamilia          = new Familia($oGet->codigoFamilia);
$sNis              = "";
$dtNacimento       = "";
$lAveriguacao      = false;
$lBpc              = false;
$lRevisaoCadastral = false;
$sCodigoFamiliar   = '';

if ($oFamilia->isCadastroUnico()) {

	$sNis            = $oFamilia->getResponsavel()->getNis();
	$dtNacimento     = $oFamilia->getResponsavel()->getDataNascimento();
	$sCodigoFamiliar = $oFamilia->getCodigoFamiliarCadastroUnico();
}

foreach ($oFamilia->getComposicaoFamiliar() as $oCidadaoFamilia) {

 if ($oCidadaoFamilia instanceof CadastroUnico && count($oCidadaoFamilia->getSituacoes()) > 0) {

    foreach ($oCidadaoFamilia->getSituacoes() as $iIndice => $oTipoSituacao) {

      switch ($iIndice) {

        case 1:

          $lAveriguacao = true;
          break;

        case 2:

          $lBpc = true;
          break;

        case 3:

          $lRevisaoCadastral = true;
          break;
      }
    }
  }
}

$sResponsavel  = $oFamilia->getResponsavel()->getCodigo();
$sResponsavel .= " - " . $oFamilia->getResponsavel()->getNome();

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css, tab.style.css");
  ?>
  <style type="text/css">
    .col1, .col3{
      font-weight: bold;
      width: 100px;
    }
    .col2, .col4 {
      background-color: #FFF;
      width: 250px;
    }
  </style>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px; " onload="">
	<fieldset	>
		<legend><b>Dados do Responsável da Família</b></legend>
	    <table>
	      <tr>
	        <td nowrap="nowrap" class="col1">Código - Responsável: </td>
	        <td nowrap="nowrap" class="col2" colspan="3"><?php echo $sResponsavel;?></td>
	        <td nowrap="nowrap" class="col3" colspan="2"></td>
          <td nowrap="nowrap" class="col3" colspan="2"></td>
	        <td nowrap="nowrap" class="col3" colspan="2">Situações existentes para membros da família</td>
	      </tr>
	      <tr>
	        <td nowrap="nowrap" class="col1">Código Familiar:</td>
	        <td nowrap="nowrap" class="col2"><?php echo $sCodigoFamiliar;?></td>
	        <td nowrap="nowrap" class="col3">NIS:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $sNis;?></td>
	        <td nowrap="nowrap" class="col3" colspan="2"></td>
          <td nowrap="nowrap" class="col3" colspan="2"></td>
	        <td nowrap="nowrap" class="col3">Averiguação:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $lAveriguacao == true ? "Sim" : "Não";?></td>
	      </tr>
	      <tr>
	        <td nowrap="nowrap" class="col1">Endereço:</td>
	        <td nowrap="nowrap" class="col2"><?php echo $oFamilia->getResponsavel()->getEndereco();?></td>
	        <td nowrap="nowrap" class="col3">Complemento:</td>
        	<td nowrap="nowrap" class="col4"><?php echo $oFamilia->getResponsavel()->getComplemento();?> </td>
        	<td nowrap="nowrap" class="col3" colspan="2"></td>
          <td nowrap="nowrap" class="col3" colspan="2"></td>
        	<td nowrap="nowrap" class="col3">BPC:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $lBpc == true ? "Sim" : "Não";?></td>
      	</tr>
      	<tr>
	        <td nowrap="nowrap" class="col1">Bairro:</td>
	        <td nowrap="nowrap" class="col2"><?php echo $oFamilia->getResponsavel()->getBairro();?></td>
	        <td nowrap="nowrap" class="col3">CEP:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $oFamilia->getResponsavel()->getCEP();?> </td>
	        <td nowrap="nowrap" class="col3" colspan="2"></td>
          <td nowrap="nowrap" class="col3" colspan="2"></td>
	        <td nowrap="nowrap" class="col3">Revisão Cadastral:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $lRevisaoCadastral == true ? "Sim" : "Não";?></td>
	      </tr>
	      <tr>
	        <td nowrap="nowrap" class="col1">Cidade:</td>
	        <td nowrap="nowrap" class="col2"><?php echo  $oFamilia->getResponsavel()->getMunicipio();?> </td>
	        <td nowrap="nowrap" class="col3">Estado:</td>
	        <td nowrap="nowrap" class="col4"><?php echo $oFamilia->getResponsavel()->getUF();?> </td>
	      </tr>
	      <tr>
	        <td nowrap="nowrap" class="col1">Renda per capita:</td>
	        <td nowrap="nowrap" class="col2" align="right"><?php echo  db_formatar($oFamilia->getRendaPerCapita(), 'f');?> </td>
	        <td nowrap="nowrap" ></td>
	        <td nowrap="nowrap" ></td>
	      </tr>
	    </table>
	</fieldset>
	<?php
		  /**
		   * Configuramos e exibimos as "abas verticais" (componente verticalTab)
		   */
		  $oVerticalTab = new verticalTab('detalhesFamilia', 350);
		  $sGetUrl      = "familia={$oGet->codigoFamilia}";

		  $oVerticalTab->add('dadosComposicaoFamiliar', 'Composição Familiar',
		                     "soc3_consultafamiliacomposicaofamiliar001.php?{$sGetUrl}");

		  $oVerticalTab->add('dadosBeneficio', 'Benefícios',
		                     "soc3_consultafamiliabeneficios001.php?{$sGetUrl}");

		  $oVerticalTab->add('dadosAvaliacao', 'Avaliação Sócio Econômica',
		                     "soc3_consultafamiliaavaliacao001.php?{$sGetUrl}");

      $oVerticalTab->add('dadosVisitas', 'Visitas',
                         "soc3_consultafamiliavisitas001.php?{$sGetUrl}");

      $oVerticalTab->add('dadosVisitas', 'CRAS/CREAS',
                         "soc3_consultafamiliacrascreas001.php?{$sGetUrl}");

		  $oVerticalTab->show();
		?>
</body>