<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

if(!isset($iCodigoAssentamento)) {
  if(isset($_GET['iCodigoAssentamento'])) {
    $iCodigoAssentamento = $_GET['iCodigoAssentamento'];
  }
}

$oDaoAssentamentoRRA = new cl_assentamentorra();
$oDaoAssentamentoRRA->rotulo->label();

$oDaoRhpessoal = new cl_rhpessoal();
$oDaoRhpessoal->rotulo->label();

$sMessage = null;
$aFolhas  = array(
  0                                       => 'Selecione',
  FolhaPagamento::TIPO_FOLHA_SALARIO      => 'Salário',
  FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR => 'Complementar'
);

try {
  
  $oRRA            = RRARepository::getInstanciaByAssentamento(AssentamentoFactory::getByCodigo($iCodigoAssentamento));

  $h83_assenta     = $iCodigoAssentamento;
  $h12_assentdescr = $oRRA->getAssentamento()->getHistorico();
  $h16_regist      = $oRRA->getAssentamento()->getMatricula();
  $z01_nome        = ServidorRepository::getInstanciaByCodigo($oRRA->getAssentamento()->getMatricula())->getCgm()->getNome();
  $h83_valor       = $oRRA->getAssentamento()->getValorTotalDevido();
  $h83_meses       = $oRRA->getAssentamento()->getNumeroDeMeses();
  $h83_encargos    = $oRRA->getAssentamento()->getValorDosEncargosJudiciais();
  $sCompetenciaFolha = DBPessoal::getCompetenciaFolha()->getCompetencia();

  if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    
    if(!FolhaPagamentoSalario::hasFolhaAberta()) {
      unset($aFolhas[FolhaPagamento::TIPO_FOLHA_SALARIO]);
    }

    
    if(!FolhaPagamentoComplementar::hasFolhaAberta()) {
      unset($aFolhas[FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR]);
    }

    if(FolhaPagamentoSuplementar::hasFolhaAberta()) {
      $aFolhas[FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR] = 'Suplementar';
    }
  }
} catch (Exception $e) {
  $sMessage = $e->getMessage();
}
?>

<div class="container" style="margin: 0 auto">

  <fieldset class="container" style="margin-top: 10">
    <legend>Dados RRA</legend>

    <table class="form-container">
      <tr>
        <td>
          <label for="h83_assenta">Assentamento:</label>  
        </td>
        <td>
          <input type="text" id="h83_assenta" name="h83_assenta" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($h83_assenta) ? $h83_assenta : '' ?>" />
        </td>
        <td>
          <input type="text" id="h12_assentdescr" name="h12_assentdescr" size="30" maxlength="30" readonly style="background-color:#DEB887" value="<?php echo isset($h12_assentdescr) ? $h12_assentdescr : '' ?>" />
        </td>
      </tr>
      <tr>
        <td width="145">
          <label for="z01_nome"><?php echo $Lrh01_regist?></label>  
        </td>
        <td width="8">
          <input type="text" id="h16_regist" name="h16_regist" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($h16_regist) ? $h16_regist : '' ?>" />
        </td>
        <td width="40">
          <input type="text" id="z01_nome" name="z01_nome" size="30" maxlength="30" readonly style="background-color:#DEB887" value="<?php echo isset($z01_nome) ? $z01_nome : '' ?>" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="h83_valor"><?php echo $Lh83_valor?></label>    
        </td>
        <td>
          <input type="text" id="h83_valor" name="h83_valor" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($h83_valor) ? $h83_valor : '' ?>" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="h83_meses"><?php echo $Lh83_meses?></label>    
        </td>
        <td>
          <input type="text" id="h83_meses" name="h83_meses" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($h83_meses) ? $h83_meses : '' ?>" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="h83_encargos"><?php echo $Lh83_encargos?></label> 
        </td>
        <td>
          <input type="text" id="h83_encargos" name="h83_encargos" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($h83_encargos) ? $h83_encargos : '' ?>" />
          <input type="hidden" id="sCompetenciaFolha" name="sCompetenciaFolha" size="8" maxlength="8" readonly style="background-color:#DEB887" value="<?php echo isset($sCompetenciaFolha) ? $sCompetenciaFolha : '' ?>" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="iTipoFolha">Folha de Pagamento:</label> 
        </td>
        <td colspan="2">
          <?php db_select('iTipoFolha', $aFolhas, '', 1);//$js_script = "", $nomevar = "", $bgcolor = "") ?>
        </td>
      </tr>
    </table>
  </fieldset>
</div>

<fieldset>
  <legend>Lançamentos</legend>
  <div id="ctn_gridLancamentos"></div>
</fieldset>