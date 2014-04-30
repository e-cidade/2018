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


require_once 'model/dbLayoutReader.model.php';
require_once 'model/dbLayoutLinha.model.php';

/**
 * Processa arquivo de importação de DMS 
 */
class processamentoArquivoDMSWebservice {
  
  private $sArquivoBase64;
  private $iInscricaoMunicipal;
  
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
   * Seta a inscrição municipal
   * 
   * @param integer $iInscricaoMunicipal
   */
  public function setInscricaoMunicipal($iInscricaoMunicipal) {
    $this->iInscricaoMunicipal = $iInscricaoMunicipal;
  }
  
  /**
   * Processa arquivo de importação
   *
   * @return object
   */
  public function processar() {
  
    try {
  
      $sNomeArquivo = 'tmp/' . date('Ymd_His');
      $oAquivo      = file_put_contents($sNomeArquivo, base64_decode($this->sArquivoBase64));
      $oLayout      = new DBLayoutReader(213, $sNomeArquivo, true);
  
      db_inicio_transacao(); // DB Begin
  
      // Varre as linhas do arquivo
      foreach ($oLayout->getLines() as $oLinha) {
  
        // Processa os dados conforme o tipo de dado da linha
        switch ($oLinha->identificador_linha) {
  
          case '01': // Contadores
  
            $oDadosContador     = self::preparaDadosContador($oLinha);
            $iContador          = $oDadosContador->iInscricaoMunicipal;
  
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
              
              $oParam->iCgmEmpresa                       = $oDadosContribuinte->iCgmEmpresa;
              $oParam->iAnoCompetencia                   = $oDadosContribuinte->iAnoCompetencia;
              $oParam->iMesCompetencia                   = $oDadosContribuinte->iMesCompetencia;
              $oParam->iInscricaoMunicipal               = $oDadosContribuinte->iInscricaoMunicipal;
              
              $aPlanilhaRetencao[$oDadosNota->sOperacao] = self::gerarPlanilhaRetencao($oParam);
            }
            
            // Recupera o código da planilha
            $iCodigoPlanilhaRetencao = $aPlanilhaRetencao[$oDadosNota->sOperacao]->getCodigoPlanilha();
            
            // Insere as notas na planilha de retenção (issplanit)
            $oNotaRetencao = new NotaPlanilhaRetencao();
            $oNotaRetencao->setCodigoPlanilha  ($iCodigoPlanilhaRetencao);
            $oNotaRetencao->setDataOperacao    ($oDadosNota->sDataEmissao);
            $oNotaRetencao->setHoraOperacao    ($oDadosNota->sHora);
            $oNotaRetencao->setNome            ($oDadosNota->sNomeRazaoSocial);
            $oNotaRetencao->setCNPJ            ($oDadosNota->iCpfCnpjTomador);
            
            // Inverte os dados para saida e entrada
            if ($oDadosNota->iTipoServico == NotaPlanilhaRetencao::SERVICO_TOMADO) {
              $oNotaRetencao->setCNPJ = $oDadosContribuinte->iCpfCnpj;
            }
            
            $oNotaRetencao->setTipoLancamento  ($oDadosNota->iTipoServico);
            $oNotaRetencao->setRetido          ((bool)$oDadosNota->bRetido);
            $oNotaRetencao->setStatus          ($oDadosNota->iStatus);
            $oNotaRetencao->setSituacao        ((string)$oDadosNota->iSituacao);
            $oNotaRetencao->setDataNota        ($oDadosNota->sDataPrestacao);
            $oNotaRetencao->setSerie           ($oDadosNota->sSerie);
            $oNotaRetencao->setNumeroNota      ($oDadosNota->sNumeroNota);
            $oNotaRetencao->setValorServico    ($oDadosNota->fValorServico);
            $oNotaRetencao->setValorRetencao   ($oDadosNota->fValorIssqn);
            $oNotaRetencao->setAliquota        ($oDadosNota->fValorAliquota);
            $oNotaRetencao->setValorDeducao    ($oDadosNota->fValorDeducao);
            $oNotaRetencao->setValorBase       ($oDadosNota->fValorBaseCalculo);
            $oNotaRetencao->setValorImposto    ($oDadosNota->fValorIssqn);
            $oNotaRetencao->setDescricaoServico($oDadosNota->sDescricaoServico);
            $oNotaRetencao->setObservacoes     ('Importado via arquivo no eCidadeOnline2');
            
            // Adiciona a nota na planilha de retenção
            $oDadosNota->iCodigoNota = $aPlanilhaRetencao[$oDadosNota->sOperacao]->adicionarNota($oNotaRetencao);
            
            // Indice para o array de retorno
            $sIndiceNota = "{$oDadosNota->iCodigoTipoNota}.{$oDadosNota->sNumeroNota}.{$oDadosNota->sSerie}";
            
            // Incrementa o array de retorno com os dados da nota
            $aRetorno['contadores'][$iContador]['contribuintes'][$iContribuinte]['notas'][$oDadosNota->sOperacao]
                     [$sIndiceNota] = $oDadosNota;
            
            // Incrementa o array de retorno com os dados da planilha
            $aRetorno['contadores'][$iContador]['contribuintes'][$iContribuinte]['planilha'] = $aPlanilhaRetencao;
             
            break;
        }
      }
      
      // Configura o retorno
      $oRetorno->bStatus   = true;
      $oRetorno->sMensagem = utf8_encode('Arquivo importado com sucesso');
      $oRetorno->aDados    = $aRetorno;
      
      db_fim_transacao(false); // DB Commit
    } catch(Exception $oErro) {
      
      db_fim_transacao(true); // DB Rollback
      
      // Configura o retorno
      $oRetorno->bStatus   = false;
      $oRetorno->sMensagem = utf8_encode('<b>O arquivo possui inconsistências:</b><br>' . trim($oErro->getMessage()));
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
      throw new Exception('O Contribuinte não é prestador de serviço');
    }
    
    if (!in_array(trim(strtoupper($oLinha->situacao_nota)), array('T', 'R', 'C', 'E', 'IS', 'IM', 'N', 'S'))) {
      throw new Exception('Situação da Nota inválida');
    }
    
    if (trim($oLinha->situacao_nota) == 'R') {
      
      $oDadosNota->bRetido = true;
      
      if (strlen(trim($oLinha->nome_razao_social_tomador)) == 0) {
        throw new Exception('Nome/Razão Social não informado');
      }
      
      if (strlen(trim($oLinha->cpf_cnpj_tomador)) == 0 || !ctype_digit(trim($oLinha->cpf_cnpj_tomador))) {
        throw new Exception("CPF/CNPJ do Tomador ({$oLinha->nome_razao_social_tomador}) não informado");
      }
      
      $oDaoCgm  = new cl_cgm;
      $sSqlCgm  = $oDaoCgm->sql_query_file(null, '1', null, "z01_cgccpf = '{$oLinha->cpf_cnpj_tomador}'");
      $rsRecord = $oDaoCgm->sql_record($sSqlCgm);
      
      if (pg_numrows($rsRecord) == 0) { 
        throw new Exception("Tomador ({$oLinha->nome_razao_social_tomador}) não cadastrado na prefeitura");
      }
      
      if ((strlen(trim($oLinha->inscricao_municipal)) == 0) && (trim(strtoupper($oLinha->situacao_nota)) == 'E')) { 
        throw new Exception('Inscrição Municipal não informada para nota com situação de Retida');
      }
    }
    
    if (trim($oLinha->tipo_nota) == '') {
      throw new Exception('Tipo de Nota não informado ou incorreto.');
    } else {
      
      //$oLinha->tipo_nota
      $oDaoTipoNota = new cl_notasiss();
      
      $sSqlNotas = $oDaoTipoNota->sql_query_file($oLinha->tipo_nota);
      $rsNota    = pg_query($sSqlNotas);

      if (pg_numrows($rsNota) == 0) {
        throw new Exception("Tipo de Nota Código \"{$oLinha->tipo_nota}\" não encontrado.");
      }
    }
    
    if (strlen(trim($oLinha->data_emissao)) < 8 || strlen(trim($oLinha->data_prestacao)) < 8) {
      throw new Exception('Data de Emissão/Prestação inválidas');
    }
    
    $sAnoMesCompetencia = $oContribuinte->iAnoCompetencia . $oContribuinte->iMesCompetencia;
    
    if (substr(trim($oLinha->data_emissao), 0, 6) != $sAnoMesCompetencia) {
      throw new Exception('Data de Emissão da nota difere da competência sendo importada.');
    }
    
    if (trim($oLinha->codigo_servico) == '') {
      throw new Exception('Código de Serviço não informado.');
    } else {
      
      $oDaoGrpServAtivid = new cl_issgruposervicoativid;
      $sWhere            = "db121_tipoconta = 2 and db_estruturavalor.db121_estrutural = '{$oLinha->codigo_servico}'";
      $sSqlGrpServAtiv   = $oDaoGrpServAtivid->sql_query('', 'q03_ativ', '', $sWhere);
      $rsGrpServAtiv     = $oDaoGrpServAtivid->sql_record($sSqlGrpServAtiv);
      $aCodigoAtividade  = db_utils::getCollectionByRecord($rsGrpServAtiv);
      
      // Valida se existe o grupo de serviço / atividade
      if (count($aCodigoAtividade) <= 0) {
        throw new Exception("Grupo Atividade/Serviço \"{$oLinha->codigo_servico}\" não encontrado.");
      }
      
      // Recupera a lista de atividades do grupo de serviço
      foreach ($aCodigoAtividade as $oAtividade) {
        $aAtividades[]   = $oAtividade->q03_ativ;
      }
      
      // Se for DMS de saída pega a inscrição do prestador do serviço, caso contrário pega do contribuinte
      if (strtoupper(trim($oLinha->operacao)) == 'S') {
        $iInscricaoMunicipal = $oContribuinte->iInscricaoMunicipal;
      } else {
        $iInscricaoMunicipal = $oLinha->inscricao_municipal;
      }
      
      $sListaAtividades  = implode(',', $aAtividades);
      $oDaoAtividade     = new cl_tabativ();
      $sWhereAtividade   = "q07_inscr = {$iInscricaoMunicipal} and q03_ativ in ({$sListaAtividades})"; 
      $sSqlAtividade     = $oDaoAtividade->sql_query_atividade_inscr(null, '1', '', $sWhereAtividade);
      $rsAtividade       = $oDaoAtividade->sql_record($sSqlAtividade);
      $aAtividades       = db_utils::getCollectionByRecord($rsAtividade);
      
      // Valida se existe alguma atividade com o grupo informado
      if (count($aAtividades) <= 0) {
        throw new Exception("Grupo Atividade/Serviço \"{$oLinha->codigo_servico}\" não vinculado.");
      }
    }
    
    // Valida o tipo de operacao do documento (E=Entrada | S=Saída)
    $oDadosNota->sOperacao = trim(strtolower($oLinha->operacao));
    
    switch ($oDadosNota->sOperacao) {
      
      case 'e': 
        $oDadosNota->iTipoServico = NotaPlanilhaRetencao::SERVICO_TOMADO;
        break;
        
      case 's': 
        $oDadosNota->iTipoServico = NotaPlanilhaRetencao::SERVICO_PRESTADO;
        break;
        
      default : 
        throw new Exception('O Tipo de Operação do documento é inválido.');
    }
    
    // Formatação das datas
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
     * Situação do Documento
     * 
     *   T  = tributada
     *   R  = retido 
     *   C  = cancelada 
     *   E  = extraviada 
     *   Is = isento 
     *   Im = imune 
     *   N  = não tributada 
     *   S  = Tributação suspensa
     */
    switch (trim(strtoupper($oLinha->situacao_nota))) {
      
      case 'T':
        $oDadosNota->lEmiteGuia = true;
        $oDadosNota->bRetido    = ($oDadosNota->iTipoServico == NotaPlanilhaRetencao::SERVICO_TOMADO)   ? true : false;
        break;
        
      case 'R':
        $oDadosNota->lEmiteGuia = true;
        $oDadosNota->bRetido    = ($oDadosNota->iTipoServico == NotaPlanilhaRetencao::SERVICO_PRESTADO) ? true : false;
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
     * Natureza da operação
     * 
     * @tutorial 
     *   1 (Tributação dentro do município)
     *   2 (Tributação fora do município)
     *   
     *   * Deverá emitir a guia somente se for tributado dentro do município
     */
    switch (trim($oLinha->natureza_operacao)) {
      
      case 1:
        // Mantém a validação da situação do documento
        break;
        
      case 2:
        $oDadosNota->lEmiteGuia = false;
        break;
        
      default:
        
        throw new Exception('<pre>'.print_r($oLinha, true));
        throw new Exception("A Natureza da Operação \"{$oLinha->natureza_operacao}\" inválida.");
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
    $oDadosNota->fValorDescontoIncondicional = trim($oLinha->valor_desconto_incondicional);
    $oDadosNota->fValorDescontoCondicional   = trim($oLinha->valor_desconto_condicional);
    $oDadosNota->fValorNota                  = trim($oLinha->valor_nota);
    $oDadosNota->iCodigoCidadeTomador        = trim($oLinha->cidade_tomador);
    $oDadosNota->iCodigoTipoNota             = trim($oLinha->tipo_nota);
    $oDadosNota->sSituacao                   = trim($oLinha->situacao_nota);
    
    // Campos novos
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
      throw new Exception("O CPF/CNPJ \"{$oLinha->cpf_cnpj}\" do contador inválido.");
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
      throw new Exception('Nenhuma Inscrição Municipal foi encontrada para o CNPJ/CPF.');
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
      throw new Exception('Informe a Inscrição Municipal da Empresa.');
    } else {
      
      $oEmpresa = new Empresa($oLinha->inscricao_municipal);
      
      // Verifica se a empresa está ativa
      if (!$oEmpresa->isAtiva()) {
        throw new Exception('Empresa Informada não está ativa.');
      }
      
      if (!empty($oContador->iNumeroCgm)) {
        
        // Verifica se o contibuinte está vinculado ao escritório
        $lVinculado = EscritorioContabil::getInscricaoVinculadaEscritorio($oContador->iNumeroCgm, 
                                                                          $oLinha->inscricao_municipal);
        if (!$lVinculado) {
          throw new Exception("O Contribuinte com inscrição \"{$oLinha->inscricao_municipal}\" não está vinculado ao escritório.");
        }
      } else { 
        
        if ($oLinha->inscricao_municipal != $this->iInscricaoMunicipal) {
          throw new Exception("Não é permitido importar um contribuinte diferente do logado.");
        }
      }
    }
    
    if (strlen(trim($oLinha->cpf_cnpj)) < 11) {
      throw new Exception('Informe o CPF/CNPF da Empresa.');
    }
    
    if (trim($oLinha->mes_competencia) == '') {
      throw new Exception('Mês de Competência não informado.');
    }
    
    if (trim($oLinha->ano_competencia) == '') {
      throw new Exception('Ano de Competência não informado.');
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
   * Gera a planilha de retenção
   * 
   * @throws Exception
   * @return integer codigo planilha
   */
  private function gerarPlanilhaRetencao($oDados) {
    
    
    db_app::import('CgmFactory');
    db_app::import('issqn.Empresa');
    
    if (empty($oDados->iInscricaoMunicipal)) {

      
      if (!empty($oDados->iCgmEmpresa)) {
        $oCgm = CgmFactory::getInstanceByCgm($oDados->iCgmEmpresa);
      }
      
      if (!$oCgm && $oDados->iCpfCnpj) {
        $oCgm = CgmFactory::getInstanceByCnpjCpf($oDados->iCpfCnpj);
      }
      
      if (!$oCgm) {
        throw new BusinessException('gerarPlanilhaRetencao: Empresa não cadastrada');
      }
    } else {

      $oEmpresa = new Empresa($oDados->iInscricaoMunicipal);
      $oCgm     = $oEmpresa->getCgmEmpresa();
    }
    
    if (!$oCgm) {
      throw new Exception("gerarPlanilhaRetencao: Empresa com Inscrição Municipal \"{$oDados->iInscricaoMunicipal}\" não cadastrada");
    }
    
    
    // Gera planilha
    $oPlanilhaRetencao = new planilhaRetencao(
      null,
      $oCgm->getCodigo(), 
      $oDados->iAnoCompetencia, 
      $oDados->iMesCompetencia, 
      $oDados->iInscricaoMunicipal 
    );

    return $oPlanilhaRetencao;
  }
}