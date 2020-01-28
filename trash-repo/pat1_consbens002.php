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
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

require_once("model/patrimonio/Bem.model.php");
require_once("model/patrimonio/BemCedente.model.php");
require_once("model/patrimonio/BemClassificacao.model.php");
require_once("model/patrimonio/PlacaBem.model.php");
require_once("model/patrimonio/BemHistoricoMovimentacao.model.php");
require_once("model/patrimonio/BemDadosMaterial.model.php");
require_once("model/patrimonio/BemDadosImovel.model.php");
require_once("model/patrimonio/BemTipoAquisicao.php");
require_once("model/patrimonio/BemTipoDepreciacao.php");
require_once("model/CgmFactory.model.php");

/**
 * Carregamos o bem e os objetos que ele retorna
 */
$oGet = db_utils::postMemory($_GET, false);

if (trim($oGet->t52_ident != "")) {

  $oDaoBensPlaca           = db_utils::getDao('bensplaca');
  $sCamposBuscaBemPorPlaca = " t52_bem ";
  $sWhereBuscaBemPorPlaca  = "     t52_ident  = '{$oGet->t52_ident}' ";
  $sWhereBuscaBemPorPlaca .= " and t52_instit = " . db_getsession('DB_instit');

  $sSqlBuscaBemPorPlaca = $oDaoBensPlaca->sql_query(null, $sCamposBuscaBemPorPlaca, null, $sWhereBuscaBemPorPlaca);
  $rsBuscaBemPorPlaca   = $oDaoBensPlaca->sql_record($sSqlBuscaBemPorPlaca);

  if ( $oDaoBensPlaca->numrows > 0 ) {

    $oBemPorPlaca  = db_utils::fieldsMemory($rsBuscaBemPorPlaca, 0);
    $oGet->t52_bem = $oBemPorPlaca->t52_bem;
  }
}

if ( empty($oGet->t52_bem) ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Bem%20%20n%E3o%20encontrado.');
}

$oBem           = new Bem($oGet->t52_bem);
$oClassificacao = $oBem->getClassificacao();
$oFornecedor    = $oBem->getFornecedor();
$oPlaca         = $oBem->getPlaca();
$oImovel        = $oBem->getDadosImovel();
$oTipoAquisicao = $oBem->getTipoDepreciacao();

/**
 * Carregamos a DAO e efetuamos a consulta necess�ria de Org�o e Unidade
 */
$oDaoDbDepartOrg          = db_utils::getDao('db_departorg');
$sCamposBuscaOrgaoUnidade = " db01_orgao, o40_descr, db01_unidade, o41_descr ";
$sWhereBuscaOrgaoUnidade  = "     db01_anousu = ".db_getsession("DB_anousu");
$sWhereBuscaOrgaoUnidade .= " AND db01_coddepto = {$oBem->getDepartamento()} ";
$sSqlBuscaOrgaoUnidade    = $oDaoDbDepartOrg->sql_query_orgunid(null, null, $sCamposBuscaOrgaoUnidade, null, $sWhereBuscaOrgaoUnidade);
$rsBuscaOrgaoUnidade      = $oDaoDbDepartOrg->sql_record($sSqlBuscaOrgaoUnidade);

if ($rsBuscaOrgaoUnidade) {
	$oOrgaoUnidade = db_utils::fieldsMemory($rsBuscaOrgaoUnidade, 0);
}

/**
 * Carregamos a DAO e efetuamos a consulta necess�ria de Descricao de departamento
 */
$oDaoDbDepart             = db_utils::getDao('db_depart');
$sCamposBuscaDepartamento = " descrdepto, t30_codigo, t30_descr ";
$sWhereBuscaDepartamento  = " coddepto = {$oBem->getDepartamento()} ";

if ($oBem->getDivisao() != '') {
  $sWhereBuscaDepartamento .= " AND t30_codigo = {$oBem->getDivisao()}";
}

$sSqlBuscaDepartamento    = $oDaoDbDepart->sql_query_div(null, $sCamposBuscaDepartamento, null, $sWhereBuscaDepartamento);

$rsBuscaDepartameto       = $oDaoDbDepart->sql_record($sSqlBuscaDepartamento);
if ($rsBuscaDepartameto) {
	$oDepartamento            = db_utils::fieldsMemory($rsBuscaDepartameto, 0);
}

/**
 * Carregamos a DAO e efetuamos a consulta necess�ria de Conv�nios
 */
if ($oBem->getCedente() != null) {

  $oDaoConvenio         = db_utils::getDao('benscadcedente');
  $sCamposBuscaConvenio = " z01_nome ";
  $sWhereBuscaConvenio  = " t04_sequencial = {$oBem->getCedente()->getCodigo()} ";
  $sSqlBuscaConvenio    = $oDaoConvenio->sql_query(null, $sCamposBuscaConvenio, null, $sWhereBuscaConvenio);
  $rsBuscaConvenio      = $oDaoConvenio->sql_record($sSqlBuscaConvenio);
  $oConvenio            = db_utils::fieldsMemory($rsBuscaConvenio, 0);
}

?>
<html>
<head>
<title>Dados do Cadastro de Ve�culos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.valores {background-color:#FFFFFF}
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <fieldset>

    <legend>
      <strong>Dados do Bem:</strong>
    </legend>

    <table>

      <tr>
        <td>
          <strong>Bem: </strong>
        </td>
        <td class="valores" width="30" align="right">
          <?php echo $oBem->getCodigoBem(); ?>
        </td>
        <td class="valores" width="250">
          <?php echo $oBem->getDescricao(); ?>
        </td>
        <td>
          <strong>Classifica��o: </strong>
        </td>
        <td class="valores" width="30" align="right">
          <?php
            if ($oClassificacao != null) {
              echo $oClassificacao->getCodigo();
            }
          ?>
        </td>
        <td class="valores" width="250">
          <?php
            if ($oClassificacao != null) {
              echo $oClassificacao->getDescricao();
            }
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <strong>�rg�o: </strong>
        </td>
        <td class="valores" align="right">
					<? echo !empty($oOrgaoUnidade->db01_orgao)?$oOrgaoUnidade->db01_orgao:''; ?>
        </td>
        <td class="valores">
          <?php echo !empty($oOrgaoUnidade->o40_descr)?$oOrgaoUnidade->o40_descr:''; ?>
        </td>
        <td>
          <strong>Unidade: </strong>
        </td>
        <td class="valores" align="right">
          <?php echo !empty($oOrgaoUnidade->db01_unidade)?$oOrgaoUnidade->db01_unidade:''; ?>
        </td>
        <td class="valores">
          <?php echo !empty($oOrgaoUnidade->o41_descr)?$oOrgaoUnidade->o41_descr:''; ?>
        </td>
      </tr>

      <tr>
        <td>
          <strong>Departamento: </strong>
        </td>
        <td class="valores" align="right">
          <?php echo $oBem->getDepartamento(); ?>
        </td>
        <td class="valores">
          <?php echo $oDepartamento->descrdepto; ?>
        </td>
        <td>
          <strong>Divis�o Depart.: </strong>
        </td>
        <td class="valores" align="right">
          <?php echo $oBem->getDivisao(); ?>
        </td>
        <td class="valores">
          <?php echo $oDepartamento->t30_descr; ?>
        </td>
      </tr>

      <tr>
        <td>
          <strong>Fornecedor: </strong>
        </td>
        <td class="valores" align="right">
          <?php
            if ($oFornecedor != null) {
              echo $oFornecedor->getCodigo();
            }
          ?>
        </td>
        <td class="valores">
          <?php
            if ($oFornecedor != null) {
              echo $oFornecedor->getNome();
            }
          ?>
        </td>
        <td>
          <strong>Conv�nio: </strong>
        </td>
        <td class="valores" align="right">
          <?php
            if ($oBem->getCedente() != null) {
              echo $oBem->getCedente()->getCodigo();
            }
          ?>
        </td>
        <td class="valores">
          <?php
            if ($oBem->getCedente() != null) {
              echo $oConvenio->z01_nome;
            }
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <strong>Placa ident.: </strong>
        </td>

        <td class="valores"colspan="2" align="right">
          <?php echo $oBem->getIdentificacao(); ?>
        </td>
        <td>
          <strong>C�digo do lote: </strong>
        </td>
        <td class="valores" colspan="2" align="right">
          <?php
            if ($oImovel != null) {
              $oImovel->getIdBql();
            }
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <strong>Aquisi��o: </strong>
        </td>

        <td class="valores" colspan="2">
          <?php echo db_formatar($oBem->getDataAquisicao(), "d"); ?>
        </td>
        <td>
          <strong>Tipo de Deprecia��o: </strong>
        </td>
        <td class="valores" colspan="2">
          <?php
            if ($oTipoAquisicao != null) {
              echo $oTipoAquisicao->getDescricao();
            }
          ?>
        </td>
      </tr>
      <tr>
      	<td>
      		<strong>Valor Aquisi��o: </strong>
      	</td>
      	<td class="valores" colspan="2" align="right">
      		<?php echo db_formatar($oBem->getValorAquisicao(), "f"); ?>
      	</td>
      	<td>
          <strong>Valor Residual: </strong>
        </td>

        <td class="valores" colspan="2" align="right">
          <?php echo db_formatar($oBem->getValorResidual(), "f");?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Valor Depreci�vel:</strong>
        </td>
        <td class="valores" colspan="2" align="right">
          <?php echo db_formatar($oBem->getValorDepreciavel(), "f");?>
        </td>
        <td>
          <strong>Deprecia��o: </strong>
        </td>
        <td class="valores" colspan="2">
          <?php
            if ($oTipoAquisicao != null) {
              echo $oTipoAquisicao->getDescricao();
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>
           <b>Valor Atual:</b>
        </td>
        <td class="valores" colspan="2" align="right">
          <?php echo db_formatar($oBem->getValorAtual(), "f");?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Observa��es: </strong>
        </td>
        <td class="valores" colspan="5">
           <?php echo $oBem->getObservacao(); ?>
        </td>
      </tr>
    </table>

  </fieldset>

  <?php
    $oTabDetalhes = new verticalTab('detalhesBem', 300);

    $sGetUrl = "?t52_bem={$oGet->t52_bem}";
    $oTabDetalhes->add('dadosMaterial', 'Dados Material', "func_detalhesdadosmaterialbens.php{$sGetUrl}");
    $oTabDetalhes->add('dadosImovel', 'Dados Imovel', "func_detalhesdadosimovelbens.php{$sGetUrl}");
    $oTabDetalhes->add('historicomovimentacao', 'Hist�rico Movimenta��o', "func_detalheshistoricomovimentacaobens.php{$sGetUrl}");
    $oTabDetalhes->add('historicofinanceiro', 'Hist�rico Financeiro', "func_detalhehistoricofinanceirobem.php{$sGetUrl}");
    $oTabDetalhes->add('placa', 'Placa', "func_detalhesplacabens.php{$sGetUrl}");
    $oTabDetalhes->add('impressao', 'Impress�o', "func_impressaodetalhesbem.php{$sGetUrl}");
    $oTabDetalhes->add('inventario', 'Invent�rio', "func_detalheinventariobem.php{$sGetUrl}");
    $oTabDetalhes->show();
  ?>
</body>
</html>