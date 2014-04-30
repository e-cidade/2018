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
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/verticalTab.widget.php");

$oGet              = db_utils::postMemory($_GET);
$oCidadao          = new Cidadao($oGet->codigoCidadao);
$lAveriguacao      = false;
$lBpc              = false;
$lRevisaoCadastral = false;

if ($oCidadao->hasCadastroUnico()) {

  $oCadastroUnico = CadastroUnicoRepository::getCadastroUnicoByCodigo($oCidadao->getSequencialCadastroUnico());
  if (count($oCadastroUnico->getSituacoes()) > 0) {

    foreach ($oCadastroUnico->getSituacoes() as $iIndice => $oTipoSituacao) {

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
  CadastroUnicoRepository::removerCadastroUnico($oCadastroUnico);
}

$sSituacaoCadastral = $oCidadao->isAtivo()?"Ativo":"Cancelado";

$sCidadao        = $oCidadao->getCodigo();
$sCidadao       .= " - " . $oCidadao->getNome();
$iCodigoFamilia  = $oCidadao->getFamilia()->getCodigoSequencial();
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
      width: 150px;
    }
    input:disabled {
      color:#000;
    }
  </style>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <fieldset>
    <legend><b>Dados do Cidadão</b></legend>
    <table>
      <tr>
        <td nowrap="nowrap" class="col1">Código - Nome: </td>
        <td nowrap="nowrap" class="col2" colspan="3"><?php echo $sCidadao;?></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3" colspan="2">Situações existentes para o cidadão</td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Identidade:</td>
        <td nowrap="nowrap" class="col2"><?php echo $oCidadao->getIdentidade();?></td>
        <td nowrap="nowrap" class="col3">CPF/CNPJ:</td>
        <td nowrap="nowrap" class="col4"><?php echo $oCidadao->getCpfCnpj();?></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3">Averiguação:</td>
	      <td nowrap="nowrap" class="col4"><?php echo $lAveriguacao == true ? "Sim" : "Não";?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Endereço:</td>
        <td nowrap="nowrap" class="col2"><?php echo $oCidadao->getEndereco() . ", ". $oCidadao->getNumero();?></td>
        <td nowrap="nowrap" class="col3">Complemento:</td>
        <td nowrap="nowrap" class="col4"><?php echo $oCidadao->getComplemento();?> </td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3">BPC:</td>
	      <td nowrap="nowrap" class="col4"><?php echo $lBpc == true ? "Sim" : "Não";?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Bairro:</td>
        <td nowrap="nowrap" class="col2"><?php echo $oCidadao->getBairro();?></td>
        <td nowrap="nowrap" class="col3">CEP:</td>
        <td nowrap="nowrap" class="col4"><?php echo $oCidadao->getCEP();?> </td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3" colspan="2"></td>
        <td nowrap="nowrap" class="col3">Revisão Cadastral:</td>
	      <td nowrap="nowrap" class="col4"><?php echo $lRevisaoCadastral == true ? "Sim" : "Não";?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Cidade:</td>
        <td nowrap="nowrap" class="col2"><?php echo  $oCidadao->getMunicipio();?> </td>
        <td nowrap="nowrap" class="col3">Estado:</td>
        <td nowrap="nowrap" class="col4"><?php echo $oCidadao->getUF();?> </td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Cadastro:</td>
        <td nowrap="nowrap" class="col2"><?php echo $sSituacaoCadastral;?></td>
        <td nowrap="nowrap" class="col3">Manutenção:</td>
        <td nowrap="nowrap" class="col4"><?php echo $oCidadao->getDataManutencao();?></td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="col1">Responsável da Família:</td>
        <td nowrap="nowrap" class="col2" colspan="3">
          <a href="#" id="linkFamilia">
            <?php echo $oCidadao->getFamilia()->getResponsavel()->getNome();?>
          </a>
        </td>
      </tr>
    </table>
  </fieldset>

  <?php
    /**
     * Configuramos e exibimos as "abas verticais" (componente verticalTab)
     */
    $oVerticalTab = new verticalTab('detalhesCidadao', 350);
    $sGetUrl      = "cidadao={$oGet->codigoCidadao}";

    $oVerticalTab->add('dadosTelefone', 'Telefone(s)',
                       "soc3_consultatelefonecidadao001.php?{$sGetUrl}");

    $oVerticalTab->add('dadosFormaRetorno', 'Forma(s) de Retorno',
                       "soc3_consultaretornocidadao001.php?{$sGetUrl}");

    $oVerticalTab->add('dadosCadastroUnico', 'Dados do Cadastro Único',
                       "soc3_consultacidadaocadastrounico001.php?{$sGetUrl}");

    $oVerticalTab->add('dadosAvaliacaoCidadao', 'Avaliação Sócio Econômica',
                       "soc3_consultacidadaoavaliacao001.php?{$sGetUrl}");

    $oVerticalTab->add('dadosBeneficio', 'Benefícios',
    									 "soc3_consultacidadaobeneficio001.php?{$sGetUrl}");

    $oVerticalTab->add('dadosBeneficio', 'Cursos/Oficinas',
                       "soc3_consultacidadaocursosoficinas001.php?{$sGetUrl}");

    $oVerticalTab->show();
  ?>
</body>
</html>
<script>
$('linkFamilia').observe("click", function(event) {

  var sUrlPesquisa = 'soc3_consultafamilia003.php?codigoFamilia=<?php echo $iCodigoFamilia?>';
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_consulta_familia',
                      sUrlPesquisa,
                      'Consulta Família',
                      true);
});
</script>