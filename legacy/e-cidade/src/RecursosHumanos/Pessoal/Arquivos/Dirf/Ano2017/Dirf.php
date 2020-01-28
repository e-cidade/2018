<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
namespace ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2017;


class Dirf extends \Dirf2015 {

  /**
   * Registro tipo pensao
   */
  const REGISTRO_PENSAO = 5;

  const REGISTRO_PENSAO_RRA = 19;

  const REGISTRO_PREVIDENCIA_PRIVADA = 3;

  protected $aListaPrevidenciasPrivadas = array();

  protected $aListaPessoasGeradasDirf   = array();

  protected $aListaPessoasPensaoRRA  = array();

  protected $aListaPessoasPensao   = array();

  /**
   * Construtor da classe
   *
   * @param integer $iAno
   * @param string $sCnpj
   */
  public function __construct($iAno,  $sCnpj) {

    parent::__construct($iAno, $sCnpj);
    $this->aGruposRRA["RIP65"] = 24;
    $this->setCodigoLayout(279);
  }

  public function processarDadosFolha() {

    parent::processarDadosFolha();
    /**
     * Incluir dados de pensionistas
     */
    $this->processarPensao();
    $this->processarPensaoRRA();
    $this->processarPrevidenciaPrivada();
  }

  /**
   * o Processamento das pensoes alimenticias sera processado separadamente
   * @param $mrubr
   * @param $oPessoa
   * @param $sigla
   * @param $Iarq
   * @param $arq
   */
  protected function processarDadosPensaoAlimenticia($mrubr, $oPessoa, $sigla, $Iarq, $arq) {


    //Apenas calculamos uma vez
    if (!empty($oPessoa->processado_pensao[$oPessoa->matricula_corrente])) {
      return;
    }
    
    $iMes = $arq[$Iarq][$sigla."mesusu"];
    $oDaoDirfPensionista = new \cl_rhdirfgeracaopessoalpensionista();
    $aCampos = array(
      "rh96_sequencial",
      "r52_regist",
      "r52_mesusu",
      "r52_numcgm",
      "sum(r52_valor + r52_valcom + r52_valfer + r52_valres + r52_valorsuplementar) as valor_pago",
      "sum(case when rh141_tipofolha <> 5 then rh145_valor else 0 end) as valor_pensao_estrutura_suplementar",
      "sum(case when rh141_tipofolha = 5 then rh145_valor else 0 end) as valor_pensao_estrutura_suplementar_13",
      "sum(r52_val13) as valor_pago_13"
    );
    $aWhere = array(
      "r52_anousu         = {$this->iAno}",      
      "rh95_fontepagadora ='{$this->sCnpj}'",
      "rh95_ano           = {$this->iAno}",
      "rh01_regist        = {$oPessoa->matricula_corrente}",
    );
    $aGroup = array(
      "r52_regist",
      "r52_numcgm",
      "r52_mesusu",
      "rh96_sequencial"
    );
    $sSqlPensionistas  =  $oDaoDirfPensionista->sql_query_valor_pensao(implode(", ", $aCampos),
      implode(" and ", $aWhere),
      implode(", ", $aGroup),
      implode(", ", $aGroup)
    );
    
    $rsPensionistas = db_query($sSqlPensionistas);
    if (!$rsPensionistas) {
      throw new \BusinessException('Erro ao pesquisar dados de Pensionistas');
    }
    $iTotalPensionistas = pg_num_rows($rsPensionistas);    
    $iCodigoRegistro    = '0561';
    if ($oPessoa->inativo) {
      $iCodigoRegistro    = '3533';  
    }  
    for ($iPensao = 0; $iPensao < $iTotalPensionistas; $iPensao++) {
    
      $oDadosPensao = \db_utils::fieldsMemory($rsPensionistas, $iPensao);
      if (empty($this->aListaPessoasPensao[$iCodigoRegistro][$oDadosPensao->rh96_sequencial])) {
        $this->aListaPessoasPensao[$iCodigoRegistro][$oDadosPensao->rh96_sequencial] = array();
      }

      if (empty($this->aListaPessoasPensao[$iCodigoRegistro][$oDadosPensao->rh96_sequencial][$oDadosPensao->r52_numcgm])) {

        $oDadosPensionista              = new \stdClass();
        $oDadosPensionista->numcgm      = $oDadosPensao->r52_numcgm;
        $oDadosPensionista->matricula   = $oDadosPensao->r52_regist;
        $oDadosPensionista->codigo_dirf = $oDadosPensao->r52_regist;
        $oDadosPensionista->valores     = array(13 => 0);
        $this->aListaPessoasPensao[$iCodigoRegistro][$oDadosPensao->rh96_sequencial][$oDadosPensao->r52_numcgm] = $oDadosPensionista;
      }
      $oPensionista = $this->aListaPessoasPensao[$iCodigoRegistro][$oDadosPensao->rh96_sequencial][$oDadosPensao->r52_numcgm];
      $nValorMes    = $oDadosPensao->valor_pago;
      $nValor13     = $oDadosPensao->valor_pago_13;
      if ($oDadosPensao->valor_pensao_estrutura_suplementar > 0) {
        $nValorMes = $oDadosPensao->valor_pensao_estrutura_suplementar;
      }

      if ($oDadosPensao->valor_pensao_estrutura_suplementar_13 > 0) {
        $nValor13 = $oDadosPensao->valor_pensao_estrutura_suplementar_13;
      }

      if (!isset($oPensionista->valores[$oDadosPensao->r52_mesusu])) {
        $oPensionista->valores[$oDadosPensao->r52_mesusu] = 0;
      }
      $oPensionista->valores[$oDadosPensao->r52_mesusu] += $nValorMes;
      $oPensionista->valores[13] += $nValor13;
    }
    $oPessoa->processado_pensao[$oPessoa->matricula_corrente] = true;
  }

  /**
   * @param $oPessoa
   * @param $sRubrica
   * @param $iLinhaProcessamento
   * @param $sSiglaTabela
   * @param $aRegistros
   * @throws \BusinessException
   */
  protected function processarDadosPrevidenciaPrivada($oPessoa, $sRubrica, $iLinhaProcessamento, $sSiglaTabela, $aRegistros) {

    if (!in_array($sRubrica, $this->aRubricasPrevidenciaPrivada)) {
      return;
    }
    $oRubrica = \RubricaRepository::getInstanciaByCodigo($sRubrica);
    $oEmpresa = $oRubrica->getEmpresaPrevidenciaPrivada();
    if (empty($oEmpresa)) {

      $sMensagem = "Rubrica {$sRubrica} está na base B910, mas não foram informados os dados da previdência complementar ";
      $sMensagem .= "cadastro da rubrica. Para realizar o ajuste, acessa a rotina 'Pessoal > Cadastro > Tabelas > Rubricas/Códigos'  para ajustar.";
      throw new \BusinessException($sMensagem);
    }

    if (empty($this->aListaPrevidenciasPrivadas[$oPessoa->rh01_numcgm])) {

      $oDadosPrevidencia              = new \stdClass();
      $oDadosPrevidencia->previdencia = array();
      $oDadosPrevidencia->cgm         = $oPessoa->rh01_numcgm;
      $oDadosPrevidencia->codigo_dirf = $oPessoa->codigodirf;
      $oDadosPrevidencia->matricula   = $aRegistros[$iLinhaProcessamento][$sSiglaTabela."regist"];
      $oDadosPrevidencia->pessoa      = $oPessoa;
      $this->aListaPrevidenciasPrivadas[$oPessoa->rh01_numcgm] = $oDadosPrevidencia;
    }
    $oDadosPrevidencia = $this->aListaPrevidenciasPrivadas[$oPessoa->rh01_numcgm];

    if (empty($oDadosPrevidencia->previdencia[$oEmpresa->getCodigo()])) {
      $oDadosPrevidencia->previdencia[$oEmpresa->getCodigo()] = array();
    }
    $iMes = $aRegistros[$iLinhaProcessamento][$sSiglaTabela."mesusu"];
    if (!isset($oDadosPrevidencia->previdencia[$oEmpresa->getCodigo()][$iMes])) {
      $oDadosPrevidencia->previdencia[$oEmpresa->getCodigo()][$iMes] = 0;
    }    
    $oDadosPrevidencia->previdencia[$oEmpresa->getCodigo()][$iMes] += $aRegistros[$iLinhaProcessamento][$sSiglaTabela."valor"];
  }

  /**
   * Processa os dados de Pensao dos servidores
   * @throws \BusinessException
   * @throws \DBException
   */
  protected function processarPensao() {
    foreach ($this->aListaPessoasPensao as $tipo => $aPensionistas) {
      $this->salvarDadosPensao($aPensionistas, $tipo);
    };
  }

  private function processarPrevidenciaPrivada() {

    $iInstituicao                      = db_getsession("DB_instit");
    $oDaoDirfValor                     = new \cl_rhdirfgeracaodadospessoalvalor();
    $oDaoDirfValorPrevidencia          = new \cl_rhdirfgeracaopessoalvalorprevidencia;
    $oDaoRhDirfGeracaoPessoalMatricula = new \cl_rhdirfgeracaopessoalregist;

    foreach ($this->aListaPrevidenciasPrivadas as $iCgm => $oPessoa) {

      foreach ($oPessoa->previdencia as $iCgmEmpresa => $aMeses) {

        foreach ($aMeses as $iMes => $valor) {

          $iTipoIrrf = 0561;
          if (isset($oPessoa->pessoa->tipoDirf[$iMes])) {
            $iTipoIrrf = $oPessoa->pessoa->tipoDirf[$iMes];
          }
          if ($valor == 0) {
            continue;
          }
          $oDaoDirfValor->rh98_rhdirfgeracaodadospessoal = $oPessoa->codigo_dirf;
          $oDaoDirfValor->rh98_rhdirftipovalor = Dirf::REGISTRO_PREVIDENCIA_PRIVADA;
          $oDaoDirfValor->rh98_instit          = $iInstituicao;
          $oDaoDirfValor->rh98_mes             = $iMes;
          $oDaoDirfValor->rh98_tipoirrf        = $iTipoIrrf;
          $oDaoDirfValor->rh98_valor           = "{$valor}";
          $oDaoDirfValor->incluir(null);
          if ($oDaoDirfValor->erro_status == 0) {
            throw new \DBException("Erro ao incluir valores da previdencia privada.\n" . $oDaoDirfValor->erro_msg);
          }

          $oDaoDirfValorPrevidencia->rh204_rhdirfgeracaodadospessoalvalor = $oDaoDirfValor->rh98_sequencial;
          $oDaoDirfValorPrevidencia->rh204_numcgm                         = $iCgmEmpresa;
          $oDaoDirfValorPrevidencia->incluir(null);
          if ($oDaoDirfValorPrevidencia->erro_status == 0) {
            throw new \DBException("Erro ao incluir vinculo das previdencia privada com valor da dirf.\n" . $oDaoDirfValorPrevidencia->erro_msg);
          }

          /**
           * vincula as matriculas ao valor calculado para o cpf.
           */
          $oDaoRhDirfGeracaoPessoalMatricula->rh99_regist =  $oPessoa->matricula;
          $oDaoRhDirfGeracaoPessoalMatricula->rh99_rhdirfgeracaodadospessoalvalor = $oDaoDirfValor->rh98_sequencial;
          $oDaoRhDirfGeracaoPessoalMatricula->incluir(null);
          if ($oDaoRhDirfGeracaoPessoalMatricula->erro_status == 0) {

            $sMsg  = "Erro[21] - Erro ao incluir matriculas para calculo da DIRF.";
            $sMsg .= "\n{$oDaoRhDirfGeracaoPessoalMatricula->erro_msg}";
            throw new \DBException($sMsg);
          }
        }
      }
    }
  }

  /**
   * @param \Servidor $oServidor
   * @param           $lPortadorMolestia
   * @param           $oPessoa
   * @return mixed
   * @throws \DBException
   */
  public function processarRRA(\Servidor $oServidor, $lPortadorMolestia, $oPessoa) {

    parent::processarRRA($oServidor, $lPortadorMolestia, $oPessoa);
    $iAno         = $oServidor->getAnoCompetencia();
    $iMes         = $oServidor->getMesCompetencia();
    $nValorPensao = $oPessoa->aValorGrupo[19];
    $oPessoa->aValorGrupo[19] = 0;    
    if (empty($this->aListaPessoasPensaoRRA[$oPessoa->codigodirf])) {
      $this->aListaPessoasPensaoRRA[$oPessoa->codigodirf] = array();
    }
    $oDaoLancamentoRRAPensionista = new \cl_lancamentorrapensionista();
    $sSqlPensionistasRRA = $oDaoLancamentoRRAPensionista->sql_query_dirf($oServidor->getMatricula(), $iMes, $iAno);    
    $rsDadosPensionistas = db_query($sSqlPensionistasRRA);
    if (!$rsDadosPensionistas) {
      throw new \DBException("Erro ao pesquisar pensionistas no RRA");
    }
    $aPensionistasRRA = \db_utils::getCollectionByRecord($rsDadosPensionistas);
    foreach ($aPensionistasRRA as $oDadosPensionista) {

      if (empty($this->aListaPessoasPensaoRRA[$oPessoa->codigodirf][$oDadosPensionista->rh201_numcgm])) {

        $oPensionista = new \stdClass();
        $oPensionista->numcgm    = $oDadosPensionista->rh201_numcgm;
        $oPensionista->matricula = $oServidor->getMatricula();
        $oPensionista->valores   = array();
        $this->aListaPessoasPensaoRRA[$oPessoa->codigodirf][$oDadosPensionista->rh201_numcgm] = $oPensionista;
      }
      $oPensionista = $this->aListaPessoasPensaoRRA[$oPessoa->codigodirf][$oDadosPensionista->rh201_numcgm];
      $nValorMes    = $oDadosPensionista->rh201_valor;
      if (!isset($oPensionista->valores[$iMes])) {
        $oPensionista->valores[$iMes] = 0;
      }
      $oPensionista->valores[$iMes] += $nValorMes;
     }
    return;
  }

  /**
   * Processa os dados da pensao
   */
  private function processarPensaoRRA() {
    $this->salvarDadosPensao($this->aListaPessoasPensaoRRA, '1889', Dirf::REGISTRO_PENSAO_RRA);
  }


  /**
   * REaliza o persistencia dos dados da pensao do RRA
   * @param  $aDadosPensionistas
   * @param string $tipoDirf
   * @throws \DBException
   */
  private function salvarDadosPensao($aDadosPensionistas, $tipoDirf='0561', $tipoRegistro = Dirf::REGISTRO_PENSAO) {

    /**
     * Persistir os valores de pensao, para cada pensionistas
     */
    $oDaoDirfPensionista  = new \cl_rhdirfgeracaopessoalpensionista();
    $oDaoDirfValor        = new \cl_rhdirfgeracaodadospessoalvalor();
    $oDaoPensionistaValor = new \cl_rhdirfgeracaopessoalpensionistavalor();
    $oDaoRhDirfGeracaoPessoalMatricula = new \cl_rhdirfgeracaopessoalregist;
    $Instituicao          = db_getsession("DB_instit");
    foreach ($aDadosPensionistas as $iCodigoDirfServidor => $aPensionistas) {

      foreach ($aPensionistas as $oPensionista) {
        
        $sWhere = "rh202_rhdirfgeracaopessoal = {$iCodigoDirfServidor} and rh202_numcgm = {$oPensionista->numcgm}";
        $sSqlPensionista = $oDaoDirfPensionista->sql_query_file(null, "rh202_sequencial", null, $sWhere);
        $rsPensionista   = db_query($sSqlPensionista);
        if (!$rsPensionista) {
          throw new \DBException("Erro ao pesquisar daodos do pensionista.");
        }
        if (pg_num_rows($rsPensionista) == 0) {
         
          $oDaoDirfPensionista->rh202_numcgm = $oPensionista->numcgm;
          $oDaoDirfPensionista->rh202_rhdirfgeracaopessoal = $iCodigoDirfServidor;
          $oDaoDirfPensionista->incluir(null);
          
          if ($oDaoDirfPensionista->erro_status == 0) {
            throw new \DBException("Erro ao incluir dados do pensionista.\n" . $oDaoDirfPensionista->erro_msg);
          }
          $iCodigoPensionista = $oDaoDirfPensionista->rh202_sequencial;
        }  else {
          $iCodigoPensionista = \db_utils::fieldsMemory($rsPensionista, 0)->rh202_sequencial;
        }
        
        foreach ($oPensionista->valores as $iMes => $valor) {

          if ($valor == 0) {
            continue;
          }
          $oDaoDirfValor->rh98_rhdirfgeracaodadospessoal = $iCodigoDirfServidor;
          $oDaoDirfValor->rh98_rhdirftipovalor = $tipoRegistro;
          $oDaoDirfValor->rh98_instit          = $Instituicao;
          $oDaoDirfValor->rh98_mes             = $iMes;
          $oDaoDirfValor->rh98_tipoirrf        = $tipoDirf;
          $oDaoDirfValor->rh98_valor           = "{$valor}";
          $oDaoDirfValor->incluir(null);
          if ($oDaoDirfValor->erro_status == 0) {
            throw new \DBException("Erro ao incluir valores do pensionista.\n" . $oDaoDirfValor->erro_msg);
          }

          $oDaoPensionistaValor->rh203_rhdirfgeracaodadospessoalvalor = $oDaoDirfValor->rh98_sequencial;
          $oDaoPensionistaValor->rh203_rhdirfgeracaopessoalpensionista = $iCodigoPensionista;
          $oDaoPensionistaValor->incluir(null);
          if ($oDaoPensionistaValor->erro_status == 0) {
            throw new \DBException("Erro ao incluir vinculo do pensionista com valor da dirf.\n" . $oDaoPensionistaValor->erro_msg);
          }
          /**
           * vincula as matriculas ao valor calculado para o cpf.
           */
          $oDaoRhDirfGeracaoPessoalMatricula->rh99_regist                         = $oPensionista->matricula;
          $oDaoRhDirfGeracaoPessoalMatricula->rh99_rhdirfgeracaodadospessoalvalor = $oDaoDirfValor->rh98_sequencial;
          $oDaoRhDirfGeracaoPessoalMatricula->incluir(null);
          if ($oDaoRhDirfGeracaoPessoalMatricula->erro_status == 0) {

            $sMsg  = "Erro[21] - Erro ao incluir matriculas para calculo da DIRF.";
            $sMsg .= "\n{$oDaoRhDirfGeracaoPessoalMatricula->erro_msg}";
            throw new \DBException($sMsg);
          }
        }
      }
    }
  }
}