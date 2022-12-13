<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoVIII as Emissao;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * Class AnexoVIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017
 */
class AnexoVIII extends \RelatoriosLegaisBase {

  /**
   * Código Padrão do Relatório
   * @var integer
   */
  const CODIGO_RELATORIO = 165;

  /**
   * AnexoVII constructor.
   *
   * @param int $iAnoUsu
   * @param int $iCodigoRelatorio
   * @param int $iCodigoPeriodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    $this->iCodigoRelatorio = $iCodigoRelatorio;
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * Retorna um array contendo as linhas do relatório já processadas.
   * @return \stdClass[]
   */
  public function getLinhas() {

    if (count($this->aLinhasConsistencia) == 0) {
      $this->processar();
    }
    return $this->aLinhasConsistencia;
  }

  /**
   * Processa a busca e cálculo necessários para emissão do relatório
   */
  private function processar() {

    $this->getDados();

    /**
     * Array associativo onde o indice é o código da linha e os valores em 'inclusao' e 'exclusao' são os documentos
     */
    $aLinhasOrcamento= array(
      110 => array(
        'inclusao' => array(5),
        'exclusao' => array(6)
      ),
      121 => array(
        'inclusao' => array(5),
        'exclusao' => array(6)
      ),
      111 => array(
        'inclusao' => array(35, 37),
        'exclusao' => array(36, 38)
      ),

      115 => array(
        'inclusao' => array(130, 150, 160),
        'exclusao' => array(131, 152, 162)
      ),

      126 => array(
        'inclusao' => array(130, 150, 160),
        'exclusao' => array(131, 152, 162)
      ),

      122 => array(
        'inclusao' => array(35, 37),
        'exclusao' => array(36, 38)
      ),
      108 => array(
        'inclusao' => array(130, 150, 100),
        'exclusao' => array(131, 152, 101)
      ),
      119 => array(
        'inclusao' => array(130, 150, 100),
        'exclusao' => array(131, 152, 101)
      ),
    );

    // Caso ultimo bimestre, sao alteradas algumas colunas de formulas
    if ($this->verificaUltimoBimestre()) {

      $this->aLinhasConsistencia[96]->colunas[0]->o116_formula = "(L[73]->empenhado_atebim+L[80]->empenhado_atebim)-L[95]->valor";
      $this->aLinhasConsistencia[67]->colunas[0]->o116_formula = "L[59]->empenhado_atebim - L[66]->valor";
      $this->aLinhasConsistencia[68]->colunas[0]->o116_formula = "((L[53]->empenhado_atebim-(L[61]->valor+L[64]->valor))/L[48]->recatebim)*100";
      $this->aLinhasConsistencia[69]->colunas[0]->o116_formula = "((L[56]->empenhado_atebim-(L[62]->valor+L[65]->valor))/L[48]->recatebim)*100";

      $this->processarFormasDasLinhas(array(96, 67, 68, 69));
    }

    foreach ($aLinhasOrcamento as $linha => $aConfiguracao){
      $this->processarOrcamentoExercicio($linha, $aConfiguracao);
    }

    $this->aLinhasConsistencia[108]->valor -= $this->aLinhasConsistencia[112]->valor;
    $this->aLinhasConsistencia[119]->valor -= $this->aLinhasConsistencia[123]->valor;

    $aLinhasProcessar = array(52, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 109, 113, 114, 117, 120, 124, 125, 128);
    $this->processarFormasDasLinhas($aLinhasProcessar);

    $this->arredondarValores();
  }

  private function processarOrcamentoExercicio($iLinha, $aConfiguracao) {

    $iCodigoRecurso = '';

    // Verifica se existe recurso configurado
    if(!empty($this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor)){
      $iCodigoRecurso = $this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor[0];
    }

    $sInclusao = implode(",", $aConfiguracao['inclusao']);
    $sDocumentos = implode(",", array_merge($aConfiguracao['inclusao'], $aConfiguracao['exclusao']));
    $aContas = array();


    foreach ($this->aLinhasConsistencia[$iLinha]->parametros->contas as $oStdConta) {
      $aContas[$oStdConta->estrutural] = $oStdConta->nivel;
    }

    $oDaoLancamento = new \cl_conlancam();

    $sCampos = "sum(case when c71_coddoc in(" . $sInclusao . ") then c70_valor else c70_valor * -1 end) as valor";

    $aWhere  = array(
      "c70_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
      "c71_coddoc in (" . $sDocumentos . ")",
      "c60_estrut ilike '11111%'"
    );
    // Caso exista recurso, adiciona na busca o recurso configurado
    if(!empty($iCodigoRecurso)){
      $aWhere[] = "c61_codigo = {$iCodigoRecurso}";
    }

    $sWhere = implode(" and ", $aWhere);

    $sSql = $oDaoLancamento->sql_query_conta($sCampos, null, $sWhere);
    $rsValor = db_query($sSql);

    if(!$rsValor){
      throw new \DBException("Ocorreu algum erro na consulta da linha {$iLinha}");
    }

    if(pg_num_rows($rsValor) <= 0){
      throw new \DBException("Ocorreu algum erro ao buscar informações da linha {$iLinha}");
    }

    $oValor = \db_utils::fieldsMemory($rsValor, 0);
    //Busca configuracao de valor manual e soma na linha
    $aValorManual = $this->aLinhasConsistencia[$iLinha]->oLinhaRelatorio->getValoresColunas();

    if($aValorManual > 0){
      foreach ($aValorManual as $oValorManual) {
        $oValor->valor += $oValorManual->colunas[0]->o117_valor;
      }
    }


    if (in_array($iLinha, array(110, 121))) {

      $aWhere = array();
      $aWhere[] = "c70_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'";
      $aWhere[] = "c71_coddoc in (140,141)";
      $aWhere[] = "c60_estrut ilike '11111%'";
      if(!empty($iCodigoRecurso)){
        $aWhere[] = "c61_codigo = {$iCodigoRecurso}";
      }

      $sCampos = "sum(case when c61_reduz = c69_credito then c70_valor else c70_valor *-1 end) as valor_credito";
      $sSqlCredito = $oDaoLancamento->sql_query_conta($sCampos, null, implode(' and ', $aWhere) );
      $rsBuscaCredito = db_query($sSqlCredito);
      if (!$rsBuscaCredito || pg_num_rows($rsBuscaCredito) === 0) {
        throw new Exception("Ocorreu um erro ao buscar o valor a débito para o documento 140.");
      }
      $oValor->valor += \db_utils::fieldsMemory($rsBuscaCredito, 0)->valor_credito;

    }

    $this->aLinhasConsistencia[$iLinha]->valor = $oValor->valor;
  }

  /**
   * Retorna os dados para Demonstrativo Simplificado
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    $aDados = $this->getLinhas();
    $oDados = new \stdClass();
    $oDados->nMinimoAtualMDEAteBimestre    = $aDados[96]->valor;
    $oDados->nPercentualAplicadoComMDE     = $aDados[97]->valor;

    // Caso ultimo bimestre altera a coluna da formula
    if ($this->verificaUltimoBimestre()) {
      $oDados->nMinimoAtualFUNDEBAteBimestre = $aDados[53]->empenhado_atebim - ($aDados[61]->valor + $aDados[64]->valor);
    } else {
      $oDados->nMinimoAtualFUNDEBAteBimestre = $aDados[53]->liquidado_atebim - ($aDados[61]->valor + $aDados[64]->valor);
    }
    $oDados->nPercentualAplicadoComFUNDEB  = $aDados[68]->valor;

    return $oDados;
  }

  /**
   * verifica se é 6 bimestre retorna true se sim, caso contrario retorna false
   * @return bool
   */
  public function verificaUltimoBimestre(){

    if($this->oPeriodo->getCodigo() == 11) {
      return true;
    }
    return false;
  }


}