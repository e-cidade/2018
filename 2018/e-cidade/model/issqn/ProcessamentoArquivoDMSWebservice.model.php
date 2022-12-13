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
require_once modification("model/dbLayoutLinha.model.php");

/**
 * Processa arquivo de importa��o de DMS
 */
class ProcessamentoArquivoDMSWebservice {

  private $sArquivoBase64;
  private $sCpfCnpjContador;
  private $iInscricaoMunicipalContribuinte;

  /**
   * Construtor
   */
  public function __construct(){

  }

  /**
   * Seta o arquivo
   *
   * @param string $sArquivo (base64)
   */
  public function setArquivo($sArquivo) {
    $this->sArquivoBase64 = $sArquivo;
  }

  /**
   * Seta o CPF/CNPJ do contador
   *
   * @param integer $sCpfCnpjContador
   */
  public function setCpfCnpjContador($sCpfCnpjContador) {
    $this->sCpfCnpjContador = $sCpfCnpjContador;
  }

  /**
   * Seta a inscri��o municipal
   *
   * @param integer $iInscricaoMunicipal
   */
  public function setInscricaoMunicipalContribuinte($iInscricaoMunicipalContribuinte) {
    $this->iInscricaoMunicipalContribuinte = $iInscricaoMunicipalContribuinte;
  }

  /**
   * Processa arquivo de importa��o
   *
   * @return object
   */
  public function processar() {

    try {

      $sNomeArquivo = 'tmp/' . date('Ymd_His');
      $oAquivo      = file_put_contents($sNomeArquivo, base64_decode($this->sArquivoBase64));
      $oLayout      = new DBLayoutReader(213, $sNomeArquivo, true);

      if (!db_utils::inTransaction()) {
        throw new Exception("Sem transa��o ativa");
      }

      // Varre as linhas do arquivo
      foreach ($oLayout->getLines() as $oLinha) {

        // Processa os dados conforme o tipo de dado da linha
        switch ($oLinha->identificador_linha) {

          case '01': // Contadores

            $oDadosContador = self::preparaDadosContador($oLinha);
            $iContador      = $oDadosContador->iInscricaoMunicipal;

            // Incrementa o array de retorno com os dados do contador
            $aRetorno['contadores'][$iContador]['dados'] = $oDadosContador;
            break;

          case '02': // Contribuintes

            // Recria o array de planilhas
            $aPlanilhaRetencao  = array();

            $oDadosContribuinte = self::preparaDadosContribuinte($oLinha, $oDadosContador);
            $iContribuinte      = $oDadosContribuinte->iInscricaoMunicipal;

            // Incrementa o array de retorno com os dados do contribuinte e a planilha
            $aRetorno['contadores'][$iContador]['contribuintes'][$iContribuinte]['dados'] = $oDadosContribuinte;
            break;

          case '03': // Notas

            $oDadosNota = self::preparaDadosNota($oLinha, $oDadosContribuinte);

            // Cria planilha de retencao (issplan)
            if (!isset($aPlanilhaRetencao[$oDadosNota->sOperacao])) {

              $oParam->iCgmEmpresa         = $oDadosContribuinte->iCgmEmpresa;
              $oParam->iAnoCompetencia     = $oDadosContribuinte->iAnoCompetencia;
              $oParam->iMesCompetencia     = $oDadosContribuinte->iMesCompetencia;
              $oParam->iInscricao          = $oDadosContribuinte->iInscricaoMunicipal;
              $oParam->dtDatausu           = date("Y-m-d", db_getsession("DB_datausu"));
              $oParam->iAnoUsu             = !empty($iAnoUsu) ? $iAnoUsu : db_getsession("DB_anousu");
              $oParam->iMes                = !empty($iMesUsu) ? $iMesUsu : date("m", db_getsession("DB_datausu"));

              $aPlanilhaRetencao[$oDadosNota->sOperacao] = $oParam;
            }

            // Indice para o array de retorno
            $sIndiceNota = "{$oDadosNota->iCodigoTipoNota}.{$oDadosNota->sNumeroNota}.{$oDadosNota->sSerie}";

            // Incrementa o array de retorno com os dados da nota
            $aRetorno['contadores'][$iContador]['contribuintes'][$iContribuinte]['notas'][$oDadosNota->sOperacao]
                     [$sIndiceNota] = $oDadosNota;

            // Incrementa o array de retorno com os dados da planilha
            $aRetorno['contadores'][$iContador]['contribuintes'][$iContribuinte]['planilha'] = $aPlanilhaRetencao;

            $aPlanilhaRetencao[$oDadosNota->sOperacao]->aNotas[] = $oDadosNota;

            break;
        }
      }

      // Configura o retorno
      $oRetorno->bStatus   = true;
      $oRetorno->sMensagem = utf8_encode('Arquivo importado com sucesso');
      $oRetorno->aDados    = $aRetorno;

    } catch(Exception $oErro) {

      // Configura o retorno
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = utf8_encode('<b>O arquivo possui inconsist�ncias:</b><br>' . trim($oErro->getMessage()));
      $oRetorno->oDados    = null;
    }

    return $oRetorno;
  }

  /**
   * Prepara os dados do documento
   *
   * @param DBLayoutLinha $oLinha
   * @throws Exception
   * @return object
   */
  private function preparaDadosNota(DBLayoutLinha $oLinha, $oContribuinte) {

    $oDaoEmpresaPretServico = db_utils::getDao('issbase');

    $sSql     = $oDaoEmpresaPretServico->sql_queryAtividadeServico($oContribuinte->iInscricaoMunicipal);
    $rsResult = db_query($sSql);

    if (strtoupper(trim($oLinha->operacao)) == 'S' && (pg_numrows($rsResult) == 0)) {
      throw new Exception('O Contribuinte n�o � prestador de servi�o');
    }

    if (!in_array(trim(strtoupper($oLinha->situacao_nota)), array('T', 'R', 'C', 'E', 'IS', 'IM', 'N', 'S'))) {
      throw new Exception('Situa��o da Nota inv�lida');
    }

    if (trim($oLinha->situacao_nota) == 'R') {

      $oDadosNota->bRetido = true;

      if (strlen(trim($oLinha->nome_razao_social_tomador)) == 0) {
        throw new Exception('Nome/Raz�o Social n�o informado');
      }

      if (strlen(trim($oLinha->cpf_cnpj_tomador)) == 0 || !ctype_digit(trim($oLinha->cpf_cnpj_tomador))) {
        throw new Exception("CPF/CNPJ do Tomador ({$oLinha->nome_razao_social_tomador}) n�o informado");
      }

      $oDaoCgm  = new cl_cgm;
      $sSqlCgm  = $oDaoCgm->sql_query_file(null, '1', null, "z01_cgccpf = '{$oLinha->cpf_cnpj_tomador}'");
      $rsRecord = $oDaoCgm->sql_record($sSqlCgm);

      if (pg_numrows($rsRecord) == 0) {
        throw new Exception("Tomador ({$oLinha->nome_razao_social_tomador}) n�o cadastrado na prefeitura");
      }

      if ((strlen(trim($oLinha->inscricao_municipal)) == 0) && (trim(strtoupper($oLinha->situacao_nota)) == 'E')) {
        throw new Exception('Inscri��o Municipal n�o informada para nota com situa��o de Retida');
      }
    }

    if (strlen($oLinha->serie_nota) > 5) {
      throw new Exception('Tamanho do Campo S�rie da Nota est� maior do que o permitido. (Nota: '.$oLinha->numero_nota.'. S�rie: '.$oLinha->serie_nota.')');
    }


    if (trim($oLinha->tipo_nota) == '') {

      $sMensagemErro = "Tipo de Documento n�o informado ou incorreto para o documento n� \"{$oLinha->numero_nota}\".";

      throw new Exception($sMensagemErro);
    } else {

      $oDaoTipoNota = new cl_notasiss();
      $sSqlNotas    = $oDaoTipoNota->sql_query_file($oLinha->tipo_nota);
      $rsNota       = pg_query($sSqlNotas);

      if (pg_numrows($rsNota) == 0) {

        $sMensagemErro = "Tipo de Documento \"{$oLinha->tipo_nota}\" � inv�lido no ";
        $sMensagemErro.= "documento n� \"{$oLinha->numero_nota}\".";

        throw new Exception($sMensagemErro);
      }
    }

    // Valida a data de emiss�o do documento
    if (!self::dataValida(trim($oLinha->data_emissao))) {

      $sMensagemErro = "Data de Emiss�o \"{$oLinha->data_emissao}\" inv�lida no ";
      $sMensagemErro.= "documento n� \"{$oLinha->numero_nota}\".";

      throw new Exception($sMensagemErro);
    }

    // Valida a data de presta��o do servi�o do documento
    if (!self::dataValida(trim($oLinha->data_prestacao))) {

      $sMensagemErro = "Data de Presta��o do Servi�o \"{$oLinha->data_prestacao}\" inv�lida no ";
      $sMensagemErro.= "documento n� \"{$oLinha->numero_nota}\".";

      throw new Exception($sMensagemErro);
    }

    $sAnoMesCompetencia = $oContribuinte->iAnoCompetencia . $oContribuinte->iMesCompetencia;

    if (substr(trim($oLinha->data_emissao), 0, 6) != $sAnoMesCompetencia) {

      $sMensagemErro = "Data de Emiss�o da Documento \"{$oLinha->data_emissao}\" difere da compet�ncia sendo ";
      $sMensagemErro.= "importada no documento n� \"{$oLinha->numero_nota}\".";

      throw new Exception($sMensagemErro);
    }

    if (strtoupper(trim($oLinha->operacao)) == 'S') {
      if (trim($oLinha->codigo_servico) == '') {
        throw new Exception('C�digo de Servi�o n�o informado.');
      } else {

        $oDaoGrpServAtivid = new cl_issgruposervicoativid;
        $sWhere            = "db121_tipoconta = 2 and db_estruturavalor.db121_estrutural = '{$oLinha->codigo_servico}'";
        $sSqlGrpServAtiv   = $oDaoGrpServAtivid->sql_query('', 'q03_ativ', '', $sWhere);
        $rsGrpServAtiv     = $oDaoGrpServAtivid->sql_record($sSqlGrpServAtiv);
        $aCodigoAtividade  = db_utils::getCollectionByRecord($rsGrpServAtiv);

        // Valida se existe o grupo de servi�o / atividade
        if (count($aCodigoAtividade) <= 0) {
          throw new Exception("Grupo Atividade/Servi�o \"{$oLinha->codigo_servico}\" n�o encontrado.");
        }

        // Recupera a lista de atividades do grupo de servi�o
        foreach ($aCodigoAtividade as $oAtividade) {
          $aAtividades[]   = $oAtividade->q03_ativ;
        }

        // Se for DMS de sa�da pega a inscri��o do prestador do servi�o, caso contr�rio pega do contribuinte
        if (strtoupper(trim($oLinha->operacao)) == 'S') {
          $iInscricaoMunicipal = $oContribuinte->iInscricaoMunicipal;
        } else {
          $iInscricaoMunicipal = $oLinha->inscricao_municipal;
        }

        $lExisteServicoEAtividadeEmpresa = self::existeServicoEAtividadeEmpresa($iInscricaoMunicipal,
                                                                                $oLinha->codigo_servico,
                                                                                $oLinha->codigo_atividade);

        // Valida se existe alguma atividade com o grupo informado, somente para servi�os prestados (sa�da)
        if (!$lExisteServicoEAtividadeEmpresa) {

          $sMensagemErro = "O C�digo do Servi�o \"{$oLinha->codigo_servico}\" ou Atividade ";
          $sMensagemErro.= "\"{$oLinha->codigo_atividade}\" n�o est� vinculado ao Contribuinte de inscri��o ";
          $sMensagemErro.= " municipal \"{$iInscricaoMunicipal}\".";

          throw new Exception($sMensagemErro);
        }
      }
    }
    // Valida o tipo de operacao do documento (E=Entrada | S=Sa�da)
    $oDadosNota->sOperacao = trim(strtolower($oLinha->operacao));

    switch ($oDadosNota->sOperacao) {

      case 'e':
        $oDadosNota->iTipoServico = NotaPlanilhaRetencao::SERVICO_TOMADO;
        break;

      case 's':
        $oDadosNota->iTipoServico = NotaPlanilhaRetencao::SERVICO_PRESTADO;
        break;

      default :
        throw new Exception('O Tipo de Opera��o do documento � inv�lido.');
    }

    // Formata��o das datas
    $iDiaEmissao                  = substr($oLinha->data_emissao,    6,  8);
    $iMesEmissao                  = substr($oLinha->data_emissao,   -4, -2);
    $iAnoEmissao                  = substr($oLinha->data_emissao,   -8, -4);
    $iDiaPrestacao                = substr($oLinha->data_prestacao,  6,  8);
    $iMesPrestacao                = substr($oLinha->data_prestacao, -4, -2);
    $iAnoPrestacao                = substr($oLinha->data_prestacao, -8, -4);
    $iDataEmissao                 = strtotime($iAnoEmissao  .'/'.$iMesEmissao  .'/'.$iDiaEmissao);
    $iDataPrestacao               = strtotime($iAnoPrestacao.'/'.$iMesPrestacao.'/'.$iDiaPrestacao);

    // Dados da nota
    $oDadosNota->sDataEmissao     = new DBDate(date('Y-m-d', $iDataEmissao));
    $oDadosNota->sHora            = db_hora();
    $oDadosNota->sNomeRazaoSocial = trim($oLinha->nome_razao_social_tomador);
    $oDadosNota->iStatus          = NotaPlanilhaRetencao::STATUS_ATIVO;
    $oDadosNota->iSituacao        = 1;

    /*
     * Situa��o do Documento
     *
     *   T  = tributada
     *   R  = retido
     *   C  = cancelada
     *   E  = extraviada
     *   Is = isento
     *   Im = imune
     *   N  = n�o tributada
     *   S  = Tributa��o suspensa
     */
    switch (trim(strtoupper($oLinha->situacao_nota))) {

      case 'T':
        $oDadosNota->lEmiteGuia = true;
        $oDadosNota->bRetido    = ($oDadosNota->iTipoServico == NotaPlanilhaRetencao::SERVICO_TOMADO);
        break;

      case 'R':
        $oDadosNota->lEmiteGuia = true;
        $oDadosNota->bRetido    = ($oDadosNota->iTipoServico == NotaPlanilhaRetencao::SERVICO_PRESTADO);
        break;

      case 'C':
        $oDadosNota->lEmiteGuia = false;
        $oDadosNota->iSituacao  = 0;
        $oDadosNota->iStatus    = NotaPlanilhaRetencao::STATUS_INATIVO_EXCLUSAO;
        break;

      case 'E':
        $oDadosNota->lEmiteGuia = false;
        $oDadosNota->iSituacao  = 0;
        $oDadosNota->iStatus    = NotaPlanilhaRetencao::STATUS_INATIVO_ALTERACAO;
        break;

      default:
        $oDadosNota->lEmiteGuia = false;
    }

    /**
     * Natureza da opera��o
     *
     * @tutorial
     *   1 (Tributa��o dentro do munic�pio)
     *   2 (Tributa��o fora do munic�pio)
     *
     *   * Dever� emitir a guia somente se for tributado dentro do munic�pio
     */
    switch (trim($oLinha->natureza_operacao)) {

      case 1:
        // Mant�m a valida��o da situa��o do documento
        break;

      case 2:
        $oDadosNota->lEmiteGuia = false;
        break;

      default:
        throw new Exception("A Natureza da Opera��o \"{$oLinha->natureza_operacao}\" inv�lida.");
    }

    $oDadosNota->sDataPrestacao              = new DBDate(date('Y-m-d', $iDataPrestacao));
    $oDadosNota->iCpfCnpjTomador             = trim($oLinha->cpf_cnpj_tomador);
    $oDadosNota->sSerie                      = trim($oLinha->serie_nota);
    $oDadosNota->sNumeroNota                 = trim($oLinha->numero_nota);
    $oDadosNota->fValorServico               = trim($oLinha->valor_servico);
    $oDadosNota->fValorIssqn                 = trim($oLinha->valor_issqn);
    $oDadosNota->fValorAliquota              = trim($oLinha->valor_aliquota);
    $oDadosNota->fValorDeducao               = trim($oLinha->valor_deducao);
    $oDadosNota->fValorBaseCalculo           = trim($oLinha->valor_base_calculo);
    $oDadosNota->sDescricaoServico           = trim($oLinha->descricao_servico);
    $oDadosNota->iCodigoServico              = trim($oLinha->codigo_servico);
    $oDadosNota->iCodigoAtividade            = trim($oLinha->codigo_atividade);
    $oDadosNota->fValorDescontoIncondicional = trim($oLinha->valor_desconto_incondicional);
    $oDadosNota->fValorDescontoCondicional   = trim($oLinha->valor_desconto_condicional);
    $oDadosNota->fValorNota                  = trim($oLinha->valor_nota);
    $oDadosNota->iCodigoCidadeTomador        = trim($oLinha->cidade_tomador);
    $oDadosNota->iCodigoTipoNota             = trim($oLinha->tipo_nota);
    $oDadosNota->sSituacao                   = trim($oLinha->situacao_nota);
    $oDadosNota->iNaturezaOperacao           = trim($oLinha->natureza_operacao);
    $oDadosNota->sCodigoObra                 = trim($oLinha->codigo_obra);
    $oDadosNota->sArt                        = trim($oLinha->art);
    $oDadosNota->sInformacoesComplementares  = trim($oLinha->outras_informacoes);

    return $oDadosNota;
  }

  /**
   * Prepara os dados do contador
   *
   * @param DBLayoutLinha $oLinha
   * @throws Exception
   * @return object
   */
  private function preparaDadosContador(DBLayoutLinha $oLinha) {

    $oLinha->cpf_cnpj = trim($oLinha->cpf_cnpj);

    if (strlen($oLinha->cpf_cnpj) == 0) {

      $oDadosContador->iInscricaoMunicipal = 0;
      $oDadosContador->sCpfCnpj            = null;
      $oDadosContador->sNomeRazaoSocial    = null;
      $oDadosContador->iNumeroCgm          = null;

      return $oDadosContador;

    } else if (strlen($oLinha->cpf_cnpj) < 11 || strlen($oLinha->cpf_cnpj) > 14) {
      throw new Exception("O CPF/CNPJ \"{$oLinha->cpf_cnpj}\" do Contador/Escrit�rio � inv�lido.");
    }

    // Valida se o contador informado no arquivo e o mesmo do logado
    if ($oLinha->cpf_cnpj != $this->sCpfCnpjContador) {
      throw new Exception("Contador/Escrit�rio sem permiss�o para importar os documentos.");
    }

    $sSqlValidaDados  = 'select                                            ';
    $sSqlValidaDados .= '       issbase.q02_inscr,                         ';
    $sSqlValidaDados .= '       cgm.z01_nome,                              ';
    $sSqlValidaDados .= '       z01_cgccpf,                                ';
    $sSqlValidaDados .= '       z01_numcgm                                 ';
    $sSqlValidaDados .= '  from issbase                                    ';
    $sSqlValidaDados .= '       inner join cgm on z01_numcgm = q02_numcgm  ';
    $sSqlValidaDados .= " where z01_cgccpf = '{$oLinha->cpf_cnpj}'         ";
    $sSqlValidaDados .= ' limit 1                                          ';
    $rsValidaDados    = db_query($sSqlValidaDados);

    if (pg_numrows($rsValidaDados) == 0) {
      throw new Exception('Nenhuma Inscri��o Municipal foi encontrada para o CNPJ/CPF.');
    }

    $oDadosContador->iInscricaoMunicipal = db_utils::fieldsMemory($rsValidaDados, 0)->q02_inscr;
    $oDadosContador->sCpfCnpj            = db_utils::fieldsMemory($rsValidaDados, 0)->z01_cgccpf;
    $oDadosContador->sNomeRazaoSocial    = db_utils::fieldsMemory($rsValidaDados, 0)->z01_nome;
    $oDadosContador->iNumeroCgm          = db_utils::fieldsMemory($rsValidaDados, 0)->z01_numcgm;

    return $oDadosContador;
  }

  /**
   * Prepara os dados do contribuinte
   *
   * @param DBLayoutLinha $oLinha
   * @throws Exception
   * @return object
   */
  private function preparaDadosContribuinte(DBLayoutLinha $oLinha, $oContador) {

    if (trim($oLinha->inscricao_municipal) == '') {
      throw new Exception('Informe a Inscri��o Municipal da Empresa.');
    } else {

      $oEmpresa = new Empresa($oLinha->inscricao_municipal);

      // Verifica se a empresa est� ativa
      if (!$oEmpresa->isAtiva()) {
        throw new Exception("A Empresa com inscri��o municipal \"{$oLinha->inscricao_municipal}\" n�o est� ativa.");
      }

      if (!empty($oContador->iNumeroCgm)) {

        // Verifica se o contibuinte est� vinculado ao escrit�rio
        $lVinculado = EscritorioContabil::getInscricaoVinculadaEscritorio($oContador->iNumeroCgm,
                                                                          $oLinha->inscricao_municipal);
        if (!$lVinculado) {

          $sMensagemErro  = "O Contribuinte com inscri��o \"{$oLinha->inscricao_municipal}\" n�o ";
          $sMensagemErro .= "est� vinculado ao escrit�rio.";

          throw new Exception($sMensagemErro);
        }
      } else {

        if ($oLinha->inscricao_municipal != $this->iInscricaoMunicipalContribuinte) {
          throw new Exception("N�o � permitido importar documentos do contribuinte informado no arquivo.");
        }
      }
    }

    if (strlen(trim($oLinha->cpf_cnpj)) < 11) {
      throw new Exception('Informe o CPF/CNPF da Empresa.');
    }

    if (trim($oLinha->mes_competencia) == '') {
      throw new Exception('M�s de Compet�ncia n�o informado.');
    }

    if (trim($oLinha->ano_competencia) == '') {
      throw new Exception('Ano de Compet�ncia n�o informado.');
    }

    $oDadosContribuinte->iCgmEmpresa         = trim($oEmpresa->getCgmEmpresa()->getCodigo());
    $oDadosContribuinte->sCpfCnpj            = trim($oLinha->cpf_cnpj);
    $oDadosContribuinte->iInscricaoMunicipal = trim($oEmpresa->getInscricao());
    $oDadosContribuinte->sNomeRazaoSocial    = trim($oLinha->nome_razao_social);
    $oDadosContribuinte->iMesCompetencia     = trim($oLinha->mes_competencia);
    $oDadosContribuinte->iAnoCompetencia     = trim($oLinha->ano_competencia);

    return $oDadosContribuinte;
  }

  /**
   * Verifica se o servi�o e a atividade est� vinculada a empresa
   *
   * @todo   Analisar e criar m�todo para retornar a lista de servi�os/atividades no modelo "Empresa"
   * @param  integer $iInscricaoMunicipal
   * @param  integer $iCodigoServico
   * @param  integer $iCodigoAtividade
   * @return boolean
   */
  private static function existeServicoEAtividadeEmpresa($iInscricaoMunicipal, $iCodigoServico, $iCodigoAtividade) {

    $sSql = "select distinct(q02_inscr, q03_ativ, db121_estrutural) as servico_e_atividade
               from issbase
         inner join tabativ                     on issbase.q02_inscr = tabativ.q07_inscr
         inner join ativid                      on q03_ativ          = q07_ativ
         inner join clasativ                    on q82_ativ          = q03_ativ
         inner join classe                      on q12_classe        = q82_classe
         inner join issgruposervicoativid       on q03_ativ          = q127_ativid
         inner join issgruposervico             on q126_sequencial   = q127_issgruposerviso
         inner join db_estruturavalor           on db121_sequencial  = q126_db_estruturavalor
          left join issconfiguracaogruposervico on q126_sequencial   = q136_issgruposervico
          left join atividcnae                  on q07_ativ          = q74_ativid
          left join cnaeanalitica               on q72_sequencial    = q74_cnaeanalitica
          left join cnae                        on q71_sequencial    = q72_cnae
          left join atividcbo                   on q07_ativ          = q75_ativid
          left join rhcbo                       on rh70_sequencial   = q75_rhcbo
              where q12_calciss
                and q02_inscr        = '{$iInscricaoMunicipal}'
                and q03_ativ         = '{$iCodigoAtividade}'
                and db121_estrutural = '{$iCodigoServico}' ";
    $rsResultado = db_utils::getCollectionByRecord(db_query($sSql));

    return (count($rsResultado) > 0);
  }

  /**
   * M�todo para valida��o de datas
   *
   * @param string $sData Informe a data a ser validada
   * @param string $sFormato Informe o formato da data a ser validada, formato pradr�o: "AAAAMMDD"
   * @throws Exception
   * @return boolean
   */
  private static function dataValida($sData, $sFormato = 'AAAAMMDD'){

    if (strlen($sData) >= 8 && strlen($sFormato) >= 8 && strlen($sFormato) <= 10) {

      switch (strtoupper($sFormato)) {

        case 'DD-MM-AAAA':
        case 'DD/MM/AAAA':

          list($sDia, $sMes, $sAno) = preg_split('/[-./ ]/', $sData);
          break;

        case 'AAAA/MM/DD':
        case 'AAAA-MM-DD':

          list($sAno, $sMes, $sDia) = preg_split('/[-./ ]/', $sData);
          break;

        case 'AAAA/DD/MM':
        case 'AAAA-DD-MM':

          list($sAno, $sDia, $sMes) = preg_split('/[-./ ]/', $sData);
          break;

        case 'MM-DD-AAAA':
        case 'MM/DD/AAAA':

          list($sMes, $sDia, $sAno) = preg_split('/[-./ ]/', $sData);
          break;

        case 'AAAAMMDD':

          $sAno = substr($sData, 0, 4);
          $sMes = substr($sData, 4, 2);
          $sDia = substr($sData, 6, 2);
          break;

        case 'AAAADDMM':

          $sAno = substr($sData, 0, 4);
          $sDia = substr($sData, 4, 2);
          $sMes = substr($sData, 6, 2);
          break;

        default:

          throw new Exception("Formato \"{$sFormato}\" para valida��o de data inv�lido.");
          break;
      }

      return (bool) @checkdate($sMes, $sDia, $sAno);
    }

    return false;
  }
}