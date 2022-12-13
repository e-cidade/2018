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

require_once modification("model/dbLayoutReader.model.php");

/**
 * Reponsável por gerar o arquivo BPA
 * @package ambulatorial
 * @author  Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.22 $
 */
class BPAMagnetico {

  const BPA_CONSOLIDADO = 214;
  const BPA_INDIVIDUAL  = 215;

  /**
   * Se esta gerando arquivo consolidado
   * @var boolean
   */
  private $lConsolidado = false;

  /**
   * Fechamento da Competência
   * @var ICompetenciaSaude
   */
  private $oCompetenciaFechada;

  /**
   * Layout do arquivo que será gerado
   * @var db_layouttxt
   */
  private $oLayout;

  /**
   * Nome do arquivo
   * @var string
   */
  private $sNomeArquivo;

  /**
   * Coleção de Unidades de Pronto Socorro selecionadas
   * Este array é indexado pelo código da unidade
   * @var Array::UnidadeProntoSocorro
   */
  private $aUnidades = array();

  /**
   * Dados do cabeçalho do arquivo
   * @var stdClass
   */
  private $oDadosCabecalho;

  /**
   * Array com os dados do corpo do arquivo BPA
   * @var array
   */
  private $aDados = array();

  /**
   * Log de inconsistencia
   * @var DBLogJSON
   */
  private $oLogger = null;

  /**
   *
   * @var boolean
   */
  private $lTemInconsistencia = false;

  /**
   * Configuracao
   * @var stdClass
   */
  private $oConfigParamentros = null;

  /**
   * Array com as raças e seus código para uso no arquivo
   * @var array
   */
  private $aRaca = array("BRANCA"         => "01",
                         "PRETA"          => "02",
                         "PARDA"          => "03",
                         "AMARELA"        => "04",
                         "INDÍGENA"       => "05",
                         "NÃO DECLARADA"  => "99",
                         "SEM INFORMACAO" => "99"
                        );

  /**
   * Instituição que esta gerando o BPA
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Numero de página do arquivo
   * @var integer
   */
  private $iNumeroPaginas = 1;

  /**
   * versão do ecidade que esta gerando o arquivo
   * @var integer
   */
  private $iVersaoSistema = 0;

  /**
   * Variável
   * @var boolean
   */
  private $lValidarCID = true;

  /**
   * Array com os laboratorios selecionados
   * So valido quando gerado BPA pelo módulo laboratorio
   * @var Laboratorio[]
   */
  private $aLaboratorios = array();

  /**
   * Lista dos procedimentos que foram gerados no arquivo atual
   * @var array
   */
  private $aProcedimentosInclusosArquivo = array();

  /**
   * construtor da classe
   * @param string $sTipo
   * @param string $sNomeArqivo
   */
  public function __construct($iLayout, $sNomeArquivo, ICompetenciaSaude $oCompetenciaFechada, DBLogJSON $oDBLog) {

    $this->sNomeArquivo = $sNomeArquivo;

    if (!in_array($iLayout, array(self::BPA_CONSOLIDADO, self::BPA_INDIVIDUAL))) {
      throw new ParameterException(_M("saude.ambulatorial.BPAMagnetico.layout_invalido"));
    }

    $this->oLayout             = new db_layouttxt($iLayout, $sNomeArquivo);
    $this->lConsolidado        = $iLayout == self::BPA_CONSOLIDADO;
    $this->oCompetenciaFechada = $oCompetenciaFechada;

    if (empty($oDBLog)) {
      throw new ParameterException(_M("saude.ambulatorial.BPAMagnetico.arquivo_de_log_nao_instaciado"));
    }

    switch (get_class($oCompetenciaFechada)) {

    	case 'CompetenciaTFD':
      case 'CompetenciaLaboratorio':
      case 'CompetenciaAtendimento':

        $this->lValidarCID = false;
        break;
    }

    $this->oLogger = $oDBLog;
  }


  /**
   * Gera o cabeçalho do arquivo com base no array dos dados
   * @return stdClass
   */
  private function gerarCabecalho() {

    $nLinhas = count($this->aDados);

    $aControleProcedimentos = array();

    $this->getParametros();

    /**
     * Para calcular o controle de domíni (cbc_smt_vrf) é somado:
     * todos procedimentos + a quantidade de cada linha
     */
    foreach ($this->aDados as $oDados) {

      $oProcedimento               = new stdClass();
      $oProcedimento->procedimento = $oDados->prd_pa;
      $oProcedimento->quantidade   = $oDados->prd_qt;
      $aControleProcedimentos[]    = $oProcedimento;
    }

    /**
     * Após somar os dados aplicamos a fórmula informada pelo BPA ((Somatorio % 1111)+1111)
     */
    $nSomatorio = 0;
    foreach ($aControleProcedimentos as $oControleProcedimentos) {
      $nSomatorio += ($oControleProcedimentos->procedimento + $oControleProcedimentos->quantidade);
    }
    $nResultado = (($nSomatorio % 1111) + 1111);

    /**
     * Competencia sem formatacao
     */
    $iCompetencia = $this->oCompetenciaFechada->getCompetencia()->getCompetencia(DBCompetencia::FORMATO_AAAAMM, false);

    $oDadosCabecalho              = new stdClass();
    $oDadosCabecalho->cbc_ident   = '01';
    $oDadosCabecalho->cbc_hdr     = "#BPA#";
    $oDadosCabecalho->cbc_mvm     = $iCompetencia;
    $oDadosCabecalho->cbc_lin     = str_pad($nLinhas, 6, 0, STR_PAD_LEFT);
    $oDadosCabecalho->cbc_flh     = str_pad($this->iNumeroPaginas, 6, 0, STR_PAD_LEFT);
    $oDadosCabecalho->cbc_smt_vrf = str_pad($nResultado, 4, 0, STR_PAD_LEFT);
    $oDadosCabecalho->cbc_rsp     = $this->oInstituicao->getDescricao();
    $oDadosCabecalho->cbc_sgl     = $this->oConfigParamentros->sBPASigla;
    $oDadosCabecalho->cbc_cgccpf  = $this->oInstituicao->getCNPJ();
    $oDadosCabecalho->cbc_dst     = $this->oConfigParamentros->sBPADestino;
    $oDadosCabecalho->cbc_dst_in  = 'M';
    $oDadosCabecalho->cbc_versao  = $this->iVersaoSistema;
    $oDadosCabecalho->cbc_fim     = " ";

    $this->oDadosCabecalho = $oDadosCabecalho;
    return $this->oDadosCabecalho;
  }


  /**
   * Agrupa os procedimentos de forma Consolidada
   * cnes_unidade, cbo, procedimento, codigo_procedimento, idade_atendimento
   * No BPA consolidado realizamos nenhuma validação
   * Tipo de registro do procedimento tem que ser igual ao tipo:
   *   01 - Consolidado
   */
  private function gerarDadosConsolidado() {

    $iLinha  = 0;
    $iPagina = 1;

    /**
     * Competencia sem formatação
     */
    $iCompetencia = $this->oCompetenciaFechada->getCompetencia()->getCompetencia(DBCompetencia::FORMATO_AAAAMM, false);

    /**
     * Filtramos os registros consolidados
     */
    $sFiltroRegistro  = " exists ( select *                                                                     ";
    $sFiltroRegistro .= "            from sau_procregistro                                                      ";
    $sFiltroRegistro .= "          inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
    $sFiltroRegistro .= "                                   and sau_registro.sd84_c_registro = '01'             ";
    $sFiltroRegistro .= "           where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
    $sFiltroRegistro .= "        ) ";
    $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos($sFiltroRegistro);

    /**
     * Filtramos as unidades selecionadas
     */
    /*PLUGIN ESF - Inicializar variável sWhereProcedimentosESF*/
    if (count($this->aUnidades) > 0) {

      $aKeys = array_keys($this->aUnidades);
      $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos("unidades.sd02_i_codigo in (".implode(", ", $aKeys).")");
      /*PLUGIN ESF - Definindo a varável sWhereProcedimentosESF*/
    }

    /**
     * Filtra os laboratórios selecionados
     * (somente para módulo Laboratorio)
     */
    if (count($this->aLaboratorios) > 0) {

      $aKeys = array_keys($this->aLaboratorios);
      $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos("lab_laboratorio.la02_i_codigo in (". implode(", ", $aKeys).")");
    }

    $aDadosAgrupados = array();
    $aProcedimentos  = $this->oCompetenciaFechada->getProcedimentos();

    /*PLUGIN ESF - Merge Procedimentos BPA com Procedimentos ESF*/

    foreach ($aProcedimentos as $iIndice => $oProcedimento) {

      if (strpos($oProcedimento->tipo_registro, '01') === false) {
        continue;
      }

      if ( get_class($this->oCompetenciaFechada) == 'CompetenciaLaboratorio' )  {
        $this->aProcedimentosInclusosArquivo[] = $oProcedimento->codigo_procedimento_fechado;
      }

      /**
       * Variáveis para agrupar dados
       */
      $iCNESUnidade        = $oProcedimento->cnes_unidade;
      $iCBO                = $oProcedimento->cbo;
      $iProcedimento       = $oProcedimento->procedimento;
      $iCodigoProcedimento = $oProcedimento->codigo_procedimento;
      $iIdadeAtendimento   = $oProcedimento->idade_atendimento;

      $sUniqueKey = $iCNESUnidade."#".$iCBO."#".$iProcedimento."#".$iCodigoProcedimento."#".$iIdadeAtendimento;

      $oDadosProcedimento = new stdClass();

      if (!isset($aDadosAgrupados[$sUniqueKey])) {

        $iLinha ++;

        if ($iLinha > 20) {

          $iLinha = 1;
          $iPagina ++;
        }

        $oDadosProcedimento->prd_ident = "02";
        $oDadosProcedimento->prd_cnes  = str_pad($oProcedimento->cnes_unidade, 7, 0, STR_PAD_LEFT);
        $oDadosProcedimento->prd_cmp   = $iCompetencia;
        $oDadosProcedimento->prd_cbo   = str_pad($oProcedimento->cbo, 6, '0', STR_PAD_LEFT);
        $oDadosProcedimento->prd_flh   = str_pad($iPagina, 3, 0, STR_PAD_LEFT);
        $oDadosProcedimento->prd_seq   = str_pad($iLinha,  2, 0, STR_PAD_LEFT);
        $oDadosProcedimento->prd_pa    = $oProcedimento->procedimento;
        $oDadosProcedimento->prd_idade = str_pad($oProcedimento->idade_atendimento, 3, 0, STR_PAD_LEFT);
		    $oDadosProcedimento->prd_qt    = 1;
        /*PLUGIN ESF - Alterado para buscar quantidade retornada da query dos procedimentos*/
        $oDadosProcedimento->prd_org   = 'BPA';
        $oDadosProcedimento->prd_fim   = " ";

        $aDadosAgrupados[$sUniqueKey]  = $oDadosProcedimento;

      } else {
        /*PLUGIN ESF - Alterado contador para adicionar o quantidade de procedimentos*/
		    $aDadosAgrupados[$sUniqueKey]->prd_qt++;
      }
    }

    foreach ($aDadosAgrupados as $oDados) {

      $oDados->prd_qt = str_pad($oDados->prd_qt, 6, 0, STR_PAD_LEFT);
      $this->aDados[] = $oDados;
    }
    $this->iNumeroPaginas = $iPagina;
  }

  /**
   * Agrupa todos procedimentos de forma individual
   * Tipo de registro do procedimento tem que ser igual ao tipo:
   *   02 - Individual
   */
  private function gerarDadosIndividual() {

    $iLinha  = 0;
    $iPagina = 0;
    /**
     * Maior numero de páginas geradas no BPA
     */
    $iMaiorNumeroPagina = 0;

    /**
     * Competencia sem formatacao
     */
    $iCompetencia = $this->oCompetenciaFechada->getCompetencia()->getCompetencia(DBCompetencia::FORMATO_AAAAMM, false);

   /**
    * Filtramos os registros individuais
    */
    $sFiltroRegistro  = " exists ( select *                                                                     ";
    $sFiltroRegistro .= "            from sau_procregistro                                                      ";
    $sFiltroRegistro .= "          inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
    $sFiltroRegistro .= "                                   and sau_registro.sd84_c_registro = '02'             ";
    $sFiltroRegistro .= "           where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
    $sFiltroRegistro .= "        ) ";
    $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos($sFiltroRegistro);

    /**
     * Filtramos as unidades selecionadas
     */
    if (count($this->aUnidades) > 0) {

      $aKeys = array_keys($this->aUnidades);
      $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos("unidades.sd02_i_codigo in (".implode(", ", $aKeys).")");
    }

    /**
     * Filtra os laboratórios selecionados
     * (somente para módulo Laboratorio)
     */
    if (count($this->aLaboratorios) > 0) {

      $aKeys = array_keys($this->aLaboratorios);
      $this->oCompetenciaFechada->adicionaFiltroBuscaProcedimentos("lab_laboratorio.la02_i_codigo in (". implode(", ", $aKeys).")");
    }

    $this->getParametros();

    $aProcedimentos = $this->oCompetenciaFechada->getProcedimentos();
    $iCodigoMedico  = 0;

    foreach ($aProcedimentos as $iIndice => $oProcedimento) {

      if (strpos($oProcedimento->tipo_registro, '02') === false) {
        continue;
      }
      if (!$this->validaInformacoes($oProcedimento)) {
      	continue;
      }

      /**
       * Regra válida para BPA do TFD.
       * Nenhum TFD vai faturar se a quantidade for = 0
       */
      if ($oProcedimento->quantidade <= 0) {
      	continue;
      }

      if ($iCodigoMedico != $oProcedimento->codigo_medico) {

        if ($iPagina > $iMaiorNumeroPagina) {
          $iMaiorNumeroPagina = $iPagina;
        }
        $iLinha  = 0;
        $iPagina = 1;
      }


      $iCNESUnidade    = $oProcedimento->cnes_unidade;
      $oDataNascimento = new DBDate($oProcedimento->data_nascimento);
      $iDataNascimetno = $oDataNascimento->getAno().$oDataNascimento->getMes().$oDataNascimento->getDia();

      $oDataAtendimento = new DBDate($oProcedimento->data_atendimento);
      $iDataAtendimento = $oDataAtendimento->getAno().$oDataAtendimento->getMes().$oDataAtendimento->getDia();

      $iCodigoRaca = 99;
      if (array_key_exists($oProcedimento->raca, $this->aRaca)) {
        $iCodigoRaca = $this->aRaca[$oProcedimento->raca];
      }

      $iLinha ++;
      if ($iLinha > 20 && $iCodigoMedico == $oProcedimento->codigo_medico) {

        $iLinha = 1;
        $iPagina ++;
        $this->iNumeroPaginas = $iPagina;
      }

      $iTelefonePaciente = preg_replace('/[^0-9]/', '', $oProcedimento->telefone_paciente);
      $iTelefonePaciente = str_pad($iTelefonePaciente, 11, ' ', STR_PAD_LEFT);

      $oDadosProcedimento = new stdClass();

      $oDadosProcedimento->prd_ident        = '03';
      $oDadosProcedimento->prd_cnes         = $oProcedimento->cnes_unidade;
      $oDadosProcedimento->prd_cmp          = $iCompetencia;
      $oDadosProcedimento->prd_cnsmed       = $oProcedimento->cnsmedico;
      $oDadosProcedimento->prd_cbo          = str_pad($oProcedimento->cbo, 6, '0', STR_PAD_LEFT);
      $oDadosProcedimento->prd_dtaten       = $iDataAtendimento;
      $oDadosProcedimento->prd_flh          = str_pad($iPagina, 3, 0, STR_PAD_LEFT);
      $oDadosProcedimento->prd_seq          = str_pad($iLinha,  2, 0, STR_PAD_LEFT);
      $oDadosProcedimento->prd_pa           = $oProcedimento->procedimento;
      $oDadosProcedimento->prd_cnspac       = $oProcedimento->cartao_sus;
      $oDadosProcedimento->prd_sexo         = str_pad($oProcedimento->sexo, 1, ' ', STR_PAD_LEFT);
      $oDadosProcedimento->prd_ibge         = $this->oConfigParamentros->iIBGE;
      $oDadosProcedimento->prd_cid          = $oProcedimento->cid;
      $oDadosProcedimento->prd_idade        = str_pad($oProcedimento->idade_atendimento, 3, 0, STR_PAD_LEFT);
      $oDadosProcedimento->prd_qt           = str_pad($oProcedimento->quantidade, 6, 0, STR_PAD_LEFT);
      $oDadosProcedimento->prd_caten        = '01';
      $oDadosProcedimento->prd_naut         = str_repeat(" ", 13);
      $oDadosProcedimento->prd_org          = 'BPA';
      $oDadosProcedimento->prd_nmpac        = str_pad(substr(trim($oProcedimento->nome_paciente), 0, 30), 30, " ", STR_PAD_RIGHT);
      $oDadosProcedimento->prd_dtnasc       = $iDataNascimetno;
      $oDadosProcedimento->prd_raca         = $iCodigoRaca;
      $oDadosProcedimento->prd_etnia        = $oProcedimento->etinia;
      $oDadosProcedimento->prd_nac          = "010"; //Brasileira;
      $oDadosProcedimento->prd_srv          = str_repeat(" ", 3);
      $oDadosProcedimento->prd_clf          = str_repeat(" ", 3);
      $oDadosProcedimento->prd_equipe_seq   = str_repeat(" ", 8);
      $oDadosProcedimento->prd_equipe_area  = str_repeat(" ", 4);
      $oDadosProcedimento->prd_cnpj         = str_repeat(" ", 14);
      $oDadosProcedimento->prd_cep_pcnte    = str_pad(preg_replace('/[^0-9]/', '', $oProcedimento->cep_paciente), 8, " ", STR_PAD_LEFT);
      $oDadosProcedimento->prd_lograd_pcnte = "081"; //Rua
      $oDadosProcedimento->prd_end_pcnte    = str_pad(substr($oProcedimento->endereco_paciente, 0, 30), 30, " ", STR_PAD_RIGHT);
      $oDadosProcedimento->prd_compl_pcnte  = str_pad(substr($oProcedimento->complemento_end_paciente, 0, 10), 10, " ", STR_PAD_RIGHT);
      $oDadosProcedimento->prd_num_pcnte    = str_pad($oProcedimento->numero_end_paciente, 5, " ", STR_PAD_LEFT);
      $oDadosProcedimento->prd_bairro_pcnte = str_pad(substr($oProcedimento->bairro_end_paciente, 0, 30), 30, " ", STR_PAD_RIGHT);
      $oDadosProcedimento->prd_ddtel_pcnte  = str_pad($iTelefonePaciente, 11, " ",STR_PAD_RIGHT);
      $oDadosProcedimento->prd_email_pcnte  = str_pad(substr($oProcedimento->email, 0, 40), 40, " ", STR_PAD_RIGHT);
      $oDadosProcedimento->prd_fim          = " ";

      $this->aDados[] = $oDadosProcedimento;
      $iCodigoMedico  = $oProcedimento->codigo_medico;

      if ( get_class($this->oCompetenciaFechada) == 'CompetenciaLaboratorio' )  {
        $this->aProcedimentosInclusosArquivo[] = $oProcedimento->codigo_procedimento_fechado;
      }
    }

    $this->iNumeroPaginas     = $iMaiorNumeroPagina;
  }

  /**
   * Recebe uma stdClass com os dados dos procedimentos realizados
   * Realiza uma série de validações
   * @param stdClass $oDadosProcedimento
   * @return boolean  true se validou e false se não validou
   */
  private function validaInformacoes($oDadosProcedimento) {

    $lRegistroValido = true;

    if (empty($oDadosProcedimento->cartao_sus)) {

      $this->escreverLog(1, $oDadosProcedimento, "CNS não informado");
      $this->lTemInconsistencia = true;
      $lRegistroValido          = false;
    }

    if( empty( $oDadosProcedimento->cnsmedico ) && $oDadosProcedimento->tipo_profissional == 1 ) {

      $this->escreverLog(2, $oDadosProcedimento, "CNS não informado");
      $this->lTemInconsistencia = true;
      $lRegistroValido          = false;
    }

    if (!empty($oDadosProcedimento->cartao_sus)) {

      $lCNSValido = validaCnsDefinitivo($oDadosProcedimento->cartao_sus);
      if (!$lCNSValido && !validaCnsProvisorio($oDadosProcedimento->cartao_sus)) {

        $this->escreverLog(1, $oDadosProcedimento, "CNS: {$oDadosProcedimento->cartao_sus} inválido");
        $this->lTemInconsistencia = true;
        $lRegistroValido          = false;
      }
    }

    if ($this->lValidarCID && empty($oDadosProcedimento->cid)) {

      $this->escreverLog(3, $oDadosProcedimento, "Cid não informado");
      $this->lTemInconsistencia = true;
      $lRegistroValido          = false;
    }

    /**
     * Se tem informação inconsistente devemos retornar false
     */
    return $lRegistroValido;
  }


  private function validaUnidades($oDadosProcedimento) {

    if (count($this->aUnidades) > 0 && !array_key_exists($oDadosProcedimento->unidade, $this->aUnidades)) {
      return false;
    }
    return true;
  }


  /**
   * Retorna os parâmetros configurados
   * @return stdClass Configuracoes do BPA da Instituicao
   */
  private function getParametros() {

    if (empty($this->oConfigParamentros)) {

      $this->oConfigParamentros = new stdClass();

      $oSauConfig = loadConfig("sau_config");
      if ($oSauConfig != false) {

        $this->oConfigParamentros->sBPASigla   = $oSauConfig->s103_c_bpasigla;
        $this->oConfigParamentros->sBPADestino = $oSauConfig->s103_c_bpasecrdestino;
        $this->oConfigParamentros->iIBGE       = $oSauConfig->s103_c_bpaibge;
      }
    }

    return $this->oConfigParamentros;
  }

  /**
   * Escreve o arquivo BPA de acordo com o tipo escolhido e os filtros informados
   * @param ICompetenciaSaude $oCompetenciaFechada
   */
  public function escreverArquivo() {

    if ($this->lConsolidado) {
      $this->gerarDadosConsolidado();
    } else {
      $this->gerarDadosIndividual();
    }

    /**
     * Se arquivo não tiver inconsistencia e o array de dados estiver vaziu, não temos procedimentos para competência
     */
    if (!$this->lTemInconsistencia && count($this->aDados) == 0) {
    	throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.procedimentos_nao_encontrados"));
    }

    $this->oLayout->setByLineOfDBUtils($this->gerarCabecalho(), 1, "01");

    $iIdentificadorLinha = "03";
    if ($this->lConsolidado) {
      $iIdentificadorLinha = "02";
    }

    foreach ($this->aDados as $oDados) {
      $this->oLayout->setByLineOfDBUtils($oDados, 3, $iIdentificadorLinha);
    }

    if (!$this->lTemInconsistencia) {
      $this->salvar();
    }
  }

  /**
   * Salva o arquivo no banco de dados
   * @throws BusinessException
   * @return boolean
   */
  private function salvar() {

    if(!db_utils::inTransaction()) {
      throw new DBException("Sem transação ativa com banco de dados.");
    }

    $iOid     = DBLargeObject::criaOID(true);
    $mEscrita = DBLargeObject::escrita($this->sNomeArquivo, $iOid);

    if (!$mEscrita) {
      throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.nao_foi_possivel_gerar_oid"));
    }

    switch (get_class($this->oCompetenciaFechada)) {

      case "CompetenciaAtendimento":

        $this->salvarFechamentoAmbulatorial($iOid);
        break;
      case "CompetenciaTFD":

        $this->salvarFechamentoTFD($iOid);
        break;
      case 'CompetenciaLaboratorio':

        $this->salvarFechamentoLaboratorio($iOid);
        break;
    }
    return true;
  }

  /**
   * Retorna uma stdClass com as informações de dados do cabeçalho do arquivo
   * @return stdClass|NULL
   */
  public function getInformacoesCabecalho() {

    if (!empty($this->oDadosCabecalho)) {

      $oInformacoes = new stdClass();

      $oInformacoes->iFolhas   = $this->oDadosCabecalho->cbc_flh;
      $oInformacoes->iLinhas   = $this->oDadosCabecalho->cbc_lin;
      $oInformacoes->nControle = $this->oDadosCabecalho->cbc_smt_vrf;

      return $oInformacoes;
    }

    return null;
  }

  /**
   * Adiciona as unidades que serão filtradas
   * @param UnidadeProntoSocorro $oUPS
   */
  public function adicionarUnidades(UnidadeProntoSocorro $oUPS) {

    $this->aUnidades[$oUPS->getDepartamento()->getCodigo()] = $oUPS;
  }

  /**
   * Seta a Instituição
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {

    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Seta a versão do sistema
   * @param integer $iVersao
   */
  public function setVersaoSistema($iVersao) {

    $this->iVersaoSistema = $iVersao;
  }

  /**
   * Retorna true quando encontrado inconsitencia no processamento do BPA
   * @return boolean
   */
  public function temInconsistencia() {

    return $this->lTemInconsistencia;
  }

  /**
   * Escreve o arquivo de log com base no tipo passado
   *  1 - erro no paciente
   *  2 - erro no médico
   *  3 - erro na ficha de atendimento
   * @param integer  $iTipo
   * @param stdClass $oDadosProcedimento
   * @param string   $sMensagem
   */
  protected function escreverLog($iTipo, $oDadosProcedimento, $sMensagem) {

    $oLog = new stdClass();
    switch ($iTipo) {

      case 1 :

        $oLog->paciente = $oDadosProcedimento->codigo_paciente;
        $oLog->nome     = utf8_encode($oDadosProcedimento->nome_paciente);
        break;

     case 2:

        $oLog->medico = $oDadosProcedimento->codigo_medico;
        $oLog->nome   = utf8_encode($oDadosProcedimento->nome_medico);
        break;
     case 3:

       $oLog->faa          = $oDadosProcedimento->faa;
       $oLog->procedimento = $oDadosProcedimento->procedimento;
       break;
    }
    $oLog->erro     = utf8_encode($sMensagem);
    $this->oLogger->log($oLog, DBLog::LOG_ERROR);

  }


  /**
   * Persite os dados do fechamento do BPA
   * @param unknown $iOidArquivo
   * @throws BusinessException
   */
  protected function salvarFechamentoAmbulatorial($iOidArquivo) {

    $oDaoSauFecharquivo = new cl_sau_fecharquivo();
    $oDaoSauFecharquivo->sd99_i_codigo     = "";
    $oDaoSauFecharquivo->sd99_i_login      = db_getsession ( "DB_id_usuario" );;
    $oDaoSauFecharquivo->sd99_i_fechamento = $this->oCompetenciaFechada->getCodigo();
    $oDaoSauFecharquivo->sd99_d_data       = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoSauFecharquivo->sd99_c_hora       = date("H:i");
    $oDaoSauFecharquivo->sd99_t_arquivo    = $this->sNomeArquivo;
    $oDaoSauFecharquivo->sd99_objarquivo   = $iOidArquivo;
    $oDaoSauFecharquivo->incluir ("");

    if ($oDaoSauFecharquivo->erro_status == 0) {
      throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.erro_ao_salvar_arquivo"));
    }
  }

  protected function salvarFechamentoTFD($iOidArquivo) {

    $oDaoTfdFecharquivo = new cl_tfd_bpamagnetico();
    $oDaoTfdFecharquivo->tf33_i_codigo      = null;
    $oDaoTfdFecharquivo->tf33_i_login       = db_getsession ( "DB_id_usuario" );
    $oDaoTfdFecharquivo->tf33_i_fechamento  = $this->oCompetenciaFechada->getCodigo();
    $oDaoTfdFecharquivo->tf33_d_datasistema = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoTfdFecharquivo->tf33_c_horasistema = date("H:i");
    $oDaoTfdFecharquivo->tf33_c_nomearquivo = $this->sNomeArquivo;
    $oDaoTfdFecharquivo->tf33_o_arquivo     = $iOidArquivo;
    $oDaoTfdFecharquivo->incluir (null);

    if ($oDaoTfdFecharquivo->erro_status == 0) {
      throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.erro_ao_salvar_arquivo"));
    }
  }

  /**
   * Salva o arquivo do BPA Magnético do Laboratorio
   * @param $iOidArquivo
   *
   * @throws BusinessException
   */
  protected function salvarFechamentoLaboratorio ($iOidArquivo) {

    $oDaoLabFechamento = new cl_lab_bpamagnetico();
    $oDaoLabFechamento->la55_i_codigo     = null;
    $oDaoLabFechamento->la55_i_fechamento = $this->oCompetenciaFechada->getCodigo();
    $oDaoLabFechamento->la55_i_usuario    = db_getsession ( "DB_id_usuario" );
    $oDaoLabFechamento->la55_d_data       = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoLabFechamento->la55_c_hora       = date("H:i");
    $oDaoLabFechamento->la55_t_arquivo    = $this->sNomeArquivo;
    $oDaoLabFechamento->la55_o_arquivo    = $iOidArquivo;

    $oDaoLabFechamento->incluir (null);

    if ($oDaoLabFechamento->erro_status == 0) {
      throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.erro_ao_salvar_arquivo"));
    }

    $oDaoFechaConferencia = new cl_lab_fechaconferencia();
    foreach ($this->aProcedimentosInclusosArquivo as $iCodigoFechaConferencia ) {

      $oDaoFechaConferencia->la58_i_codigo = $iCodigoFechaConferencia;
      $oDaoFechaConferencia->la58_gerado   = 'true';
      $oDaoFechaConferencia->alterar($iCodigoFechaConferencia);

      if ($oDaoFechaConferencia->erro_status == 0) {
        throw new BusinessException(_M("saude.ambulatorial.BPAMagnetico.erro_ao_salvar_arquivo"));
      }
    }
  }

  /**
   * Adiciona um laboratorio
   * @param Laboratorio $oLaboratorio
   */
  public function adicionarLaboratorio(Laboratorio $oLaboratorio) {

    $this->aLaboratorios[$oLaboratorio->getCodigo()] = $oLaboratorio;
  }

}