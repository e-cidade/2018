<?php
/**
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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
?>
<html>
  <head>
    <title>Cálculo Financeiro - Testes</title>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("estilos.css");
db_app::load("scripts.js");
db_app::load("strings.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
?>
  </head>
  <body>
    <div class="container">
      <fieldset>
        <legend>Processando do Cálculo</legend>
        <div      id="label_processamento" style="text-align:left">Processando</div>
         <!-- background-color: #E1DEDE;">50 -->
        <progress id="barra_progresso" value="0"  style="width: 100%; height: 25px;);">Calculando</progress>
        <div      id="label_andamento" style="text-align:left"></div>

      </fieldset>
      <fieldset>
        <legend>Executados com sucesso</legend>
        <div id="gridResultados"></div>

      </fieldset>
      <fieldset>
        <legend>Executados com erro</legend>
        <div id="gridErros"></div>

      </fieldset>
      <input type="button" id="pausar"        value="Pausar" />
      <input type="button" id="continuar"     value="Continuar" style="display:none;" />
      <input type="button" id="procesar_erro" value="Processar itens com erro" />

      <!-- <input type="button" id="reprocessar" value="Reprocessar Todos" /> -->
      <input type="button" id="fechar" value="Fechar" />

    </div>


</body>
</html>
<?php
flush();
try {

  $oRequest     = db_utils::postMemory($_REQUEST);
  $aMockRequest = (array)$oRequest;
  $aMatriculas  = explode(",", $oRequest->faixa_regis);
  $aPessoas     = array();
  $oCompetencia = DBPessoal::getCompetenciaFolha();


  if ( $oRequest->opcao_gml == "g") {
    $aMatriculasProcessamento = buscarMatriculasCalculoGeral($oRequest->opcao_geral);
  } else {
    /**
     * percorre as Matriculas selecionasdas acumuladno os CgmBase
     */
    foreach ($aMatriculas as $iMatricula) {

      $oServidor   = ServidorRepository::getInstanciaByCodigo($iMatricula, $oCompetencia->getAno(),$oCompetencia->getMes());
      $aPessoas[$oServidor->getCgm()->getCodigo()]  = $oServidor->getCgm();
    }

    /**
     * Percorre as pessoas encontradas e busca suas matriculas
     */
    $aMatriculasProcessamento = array();

    foreach ( $aPessoas as $iCgm => $oCgm ) {

      $oDados              = new stdClass();
      $oDados->oCgm        = $oCgm;
      $oDados->sNome       = $oCgm->getNome();
      $oDados->iCodigo     = $oCgm->getCodigo();
      $oDados->aMatriculas = array();

      foreach ( ServidorRepository::getServidoresByCgm($oCgm) as $oServidor ) {
        $oDados->aMatriculas[] = $oServidor->getMatricula();
      }
      $aMatriculasProcessamento[] = $oDados;
    }
  }
} catch( Exception $erro ) {
  db_msgbox($erro->getMessage());
  echo "<script>                                                              \n";
  echo " var fCallBack = parent.db_iframe_ponto || parent.db_calculo || null; \n";
  echo " if ( fCallBack ) {                                                   \n";
  echo "   fCallBack.hide();                                                  \n";
  echo " }                                                                    \n";
  echo "</script>                                                             \n";
}

function buscarMatriculasCalculoGeral( $iTipoCalculo ) {

  define("PONTO_SALARIO",             1);
  define("PONTO_ADIANTAMENTO",        2);
  define("PONTO_FERIAS",              3);
  define("PONTO_RESCISAO",            4);
  define("PONTO_13_SALARIO",          5);
  define("PONTO_COMPLEMENTAR",        8);
  define("PONTO_FIXO",               10);
  define("PONTO_PROVISAO_FERIAS",    11);
  define("PONTO_PROVISAO_13_SALARIO",12);

  switch( $iTipoCalculo ) {

  case PONTO_COMPLEMENTAR:
  case PONTO_FIXO:
  case PONTO_SALARIO:
  default:
    $sSqlAdicional = "";
    break;
  case PONTO_ADIANTAMENTO:
    $sSqlAdicional = " inner join pontofa on r21_anousu = " . DBPessoal::getAnoFolha();
    $sSqlAdicional.= "                   and r21_mesusu = " . DBPessoal::getMesFolha();
    $sSqlAdicional.= "                   and r21_instit = " . db_getsession("DB_instit");
    $sSqlAdicional.= "                   and r21_regist = regist ";
    break;
  case PONTO_FERIAS:
    $sSqlAdicional = " inner join pontofe on r29_anousu = " . DBPessoal::getAnoFolha();
    $sSqlAdicional.= "                   and r29_mesusu = " . DBPessoal::getMesFolha();
    $sSqlAdicional.= "                   and r29_instit = " . db_getsession("DB_instit");
    $sSqlAdicional.= "                   and r29_regist = regist ";
    break;
  case PONTO_RESCISAO:
    $sSqlAdicional = " inner join pontofr on r19_anousu = " . DBPessoal::getAnoFolha();
    $sSqlAdicional.= "                   and r19_mesusu = " . DBPessoal::getMesFolha();
    $sSqlAdicional.= "                   and r19_instit = " . db_getsession("DB_instit");
    $sSqlAdicional.= "                   and r19_regist = regist ";
    break;
  case PONTO_13_SALARIO:
    $sSqlAdicional = " inner join pontof13 on r34_anousu = " . DBPessoal::getAnoFolha();
    $sSqlAdicional.= "                    and r34_mesusu = " . DBPessoal::getMesFolha();
    $sSqlAdicional.= "                    and r34_instit = " . db_getsession("DB_instit");
    $sSqlAdicional.= "                    and r34_regist = regist ";
    break;
  }

  $oDaoRhPessoalMov = new cl_rhpessoalmov();
  $sSqlBase         = $oDaoRhPessoalMov->sql_queryFinanceiroPeloCodigo( DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), "pontofx", "r90_", "", "order by rh01_regist");

  $sSqlServidores   = "select distinct             ";
  $sSqlServidores  .= "       regist as matricula, ";
  $sSqlServidores  .= "       z01_numcgm as cgm,   ";
  $sSqlServidores  .= "       z01_nome as nome     ";
  $sSqlServidores  .= "  from ($sSqlBase) as x     ";
  $sSqlServidores  .= $sSqlAdicional;
  $rsServidores     = db_query($sSqlServidores);

  if (!$rsServidores) {
    throw new BusinessException("Erro ao Buscar Servidores.");
  }

  $aMatriculas = array();


  for ($iServidores = 0; $iServidores < pg_num_rows($rsServidores); $iServidores++) {

    $oDados = db_utils::fieldsMemory($rsServidores, $iServidores);

    if (!array_key_exists($oDados->cgm,$aMatriculas) ) {

      $oRetorno = new stdClass();
      $oRetorno->iCodigo     = $oDados->cgm;
      $oRetorno->sNome       = utf8_encode($oDados->nome);
      $oRetorno->aMatriculas = array();
      $aMatriculas[$oDados->cgm] = $oRetorno;
    } else {
      $oRetorno = $aMatriculas[$oDados->cgm];
    }
    $oRetorno->aMatriculas[] = $oDados->matricula;
  }

  if ( count($aMatriculas) == 0 ) {
    throw new Exception("Nenhum servidor encontrado.");
  }
  sort($aMatriculas);
  return $aMatriculas;
}
flush();

?>
<script src="scripts/classes/pessoal/CalculoFolha.classe.js"></script>
<script type="text/javascript">
var aDados = <?php echo json_encode($aMatriculasProcessamento).PHP_EOL;?>;
var oPost  = <?php echo json_encode($oRequest).PHP_EOL;?>;


var oProcesso = new ProcessamentoCalculo(aDados, oPost);
console.log("Inicio", new Date());
oProcesso.executar();
</script>
