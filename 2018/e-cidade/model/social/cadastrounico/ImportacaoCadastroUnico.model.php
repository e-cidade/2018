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


/**
 * Realiza a importacao dos dados do cadastro único
 * @package social
 * @subpackage cadastrounico
 * @Version $Revision: 1.12 $
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 */
class ImportacaoCadastroUnico {

  /**
   * Nome do arquivo a ser Importado
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * Codigo do layout do arquivo do cadastro unico
   * @var integer
   */
  const CODIGO_LAYOUT = 188;

  /**
   * Instancia da classe DBLayoutReader
   * @var DBLayoutReader
   */
  protected $oLayoutReader;

  /**
   * Dados do Header do arquivo
   * @var StdClass
   */
  protected $oHeader;

  /**
   * Dados das Familias
   * @var Familia
   */
  protected $oFamilia;

  /**
   * Data da extracao dos dados
   */
  protected $dtExtracaoDados;


  /**
   * Cidadao sendo processado
   * @var CadastroUnico
   */
  protected $oCidadao;

  /**
   * Data da posicao dos dos dados
   */
  protected $dtPosicaoDados;


  /**
   * Total de FAmilias;
   */
  protected $iTotalFamilias = 0;

  /**
   * Quantidade de familias procesadas
   * @param integer
   */
  protected $iQuantidadeProcessada = 0;

  /**
   * Cache com os telefones de contato da familia que esta sendo importadas
   * @var array
   */
  protected $aTelefonesContatoFamilia = array();
  public function __construct() {


  }

  /**
   * Retorna a Data de Extração dos dados
   * @return string no formato 'dd/mm/YYYY'
   */
  public function getDataExtracao() {
    return db_formatar($this->dtExtracaoDados, "d");
  }

  /**
   * Retorna a Data da posicao dos dados do cadastro unico
   * @return string no formato 'dd/mm/YYYY'
   */
  public function getPosicaoCadastro() {
    return db_formatar($this->dtPosicaoDados, "d");
  }

  /**
   * define os dados do cabelho do arquivo
   * @param DBLayoutLinha Dados da linha do arquivo
   */
  protected function setDadosHeader(DBLayoutLinha $oLinha) {

     $this->dtPosicaoDados  = substr($oLinha->dta_posicao_cadastro_hdr, 4, 4)."-".
                              substr($oLinha->dta_posicao_cadastro_hdr, 2, 2)."-".
                              substr($oLinha->dta_posicao_cadastro_hdr, 0, 2);

     $this->dtExtracaoDados = substr($oLinha->dta_extracao_dados_hdr, 4, 4)."-".
                              substr($oLinha->dta_extracao_dados_hdr, 2, 2)."-".
                              substr($oLinha->dta_extracao_dados_hdr, 0, 2);
  }

  /**
   * Valida os dados do arquivo antes de iniciarmos o processamento
   * @param string $sArquivo Caminho do arquivo
   * @throws FileException
   */
  public function validarArquivo($sArquivo) {

    if (!file_exists($sArquivo)) {
      throw new FileException("Arquivo {$sArquivo} não existe.", 1);
    }
    if (!is_readable($sArquivo)) {
      throw new FileException("Arquivo {$sArquivo} sem permissão de leitura", 2);
    }
    $sNomeArquivo = strtolower(basename($sArquivo));
    $sExtensao   = substr($sNomeArquivo,  -3);
    if ($sExtensao != "txt") {
      throw new FileException("Arquivo {$sArquivo} invalido, Deve ser um arquivo TXT.", 1);
    }
  }

  /**
   * Carrega os dados do arquivo dentro de cada grupo
   * e realiza o processamento dos Dados do cadastro unico
   * @param string $sArquivo Caminho do arquivo
   * @throws BusinessException, FileException
   */
  public function processarArquivo ($sArquivo) {

    $this->validarArquivo($sArquivo);
    $this->sNomeArquivo  = $sArquivo;
    $this->oLayoutReader = new DBLayoutReader(ImportacaoCadastroUnico::CODIGO_LAYOUT, $this->sNomeArquivo, false, false);

    $_SESSION["DB_usaAccount"]    = "1";
    $rArquivo                     = fopen($this->sNomeArquivo, 'r');
    $iLinha                       = 0;
    $this->iCodigoFamiliaAnterior = null;
    $oDaoBaseMunicipal            = db_utils::getDao("cadastrounicobasemunicipal");
    /**
     * Excluimos todos os registros da tabela da base municipal.
     */
    $rsDeleteBaseMunicipal = $oDaoBaseMunicipal->excluir(null, '1=1');
    $rsRestartSequence     = db_query("alter sequence cadastrounicobasemunicipal_as09_sequencial_seq restart with 1;");
    db_query("analyse cadastrounicobasemunicipal;");
    while (!feof($rArquivo)) {

      $sLinha  = fgets($rArquivo);
      $oLinha  = $this->oLayoutReader->processarLinha($sLinha, '', false, true, false);
      if (!$oLinha) {
        continue;
      }

      $sChaveRegistro = '';
      $iCodigoLinha   = $oLinha->num_reg_arquivo;
      switch ($oLinha->num_reg_arquivo) {

        case '00':

          $this->setDadosHeader($oLinha);
          break;

        case '01':

          $this->iTotalFamilias++;
          $this->adicionarFamilia($oLinha);
          $this->aTelefonesContatoFamilia = array();
          $sChaveRegistro                 = $oLinha->cod_familiar_fam;
          break;

        case '02':

          $sChaveRegistro = $oLinha->cod_familiar_fam;
          break;

        case '03':

          $this->iCodigoFamiliaAnterior = $this->oFamilia->getCodigoFamiliarCadastroUnico();
          $sChaveRegistro  = $oLinha->cod_familiar_fam;
          break;

        case '04':

          $this->adicionarCidadao($oLinha);
          $sChaveRegistro = $oLinha->num_membro_fmla;
          break;


        case '05':

          /**
           * BLoco de Documentos
           */
          $sChaveRegistro = $oLinha->num_membro_fmla;
          $this->setDocumentosCidadao($oLinha);
          break;

        case '06':

          /**
           * Deficiencias Fisicas
           */
          $sChaveRegistro = $oLinha->num_membro_fmla;
          break;

        case '07':

          /**
           * Escolaridade
           */
         $sChaveRegistro = $oLinha->num_membro_fmla;
          break;

        case '08':

          /**
           * Trabalho e Remuneracao
           */
          $sChaveRegistro = $oLinha->num_membro_fmla;
          break;


        /**
         * Importacao do dados do telefone do cidadao
         */
        case '09':

          $this->importarTelefones($oLinha);
          break;
        case '11':

          /**
           * Formulario suplementar F1.01
           * Vinculacao a programas e servicos
           */
          $sChaveRegistro = $oLinha->cod_familiar_fam;
          break;

       case '12':

          /**
           * Formulario suplementar F1.02
           * Morador de rua
           */
          $sChaveRegistro = $oLinha->num_membro_fmla;
          break;

      }

      $iLinha++;
      unset($oLinha);

      /**
       * Salvamos os dados do arquivo no ecidade.
       * Esses dadso são utilizados para criar as avaliacoes do cidada/Familia
       */
      if (trim($sChaveRegistro != "") && $iCodigoLinha != "") {

        $oDaoBaseMunicipal->as09_tiporegistro  = $iCodigoLinha;
        $oDaoBaseMunicipal->as09_chaveregistro = $sChaveRegistro;
        $oDaoBaseMunicipal->as09_conteudolinha = addslashes($sLinha);
        $oDaoBaseMunicipal->incluir(null);
        if ($oDaoBaseMunicipal->erro_status == 0) {

          $sErroMsg = "Erro ao salvar dados da base municipal.";
          throw new Exception($sErroMsg);
        }
      }
    }
    if ($this->oFamilia != null) {
      $this->salvarDadosBloco();
    }
    unset($_SESSION["DB_usaAccount"]);
  }

  /**
   * Adicionar uma familia a lista de familias do Arquivo.
   * @param DBLayoutLinha $oLinha instancia da linha
   */
  protected function adicionarFamilia(DBLayoutLinha $oLinha) {

    if (!$oFamilia = FamiliaRepository::getFamiliaPorCodigoFamiliar($oLinha->cod_familiar_fam)) {

      $oFamilia = new Familia();
      $oFamilia->setCodigoFamiliarCadastroUnico($oLinha->cod_familiar_fam);
    }
    if ($this->oFamilia != "") {
      $this->salvarDadosBloco();
    }

    $oFamilia->endereco        = $oLinha->nom_logradouro_fam;
    $oFamilia->numero_endereco = $oLinha->num_logradouro_fam;
    $oFamilia->complemento     = $oLinha->des_complemento_fam;
    $oFamilia->uf              = $oLinha->cod_munic_ibge_2_fam;
    $oFamilia->localidade      = $oLinha->nom_localidade_fam;
    $oFamilia->cep             = $oLinha->num_cep_logradouro_fam;
    $oFamilia->municipio       = $oLinha->cod_munic_ibge_2_fam.$oLinha->cod_munic_ibge_5_fam;
    $dtEntrevista              = substr($oLinha->dta_entrevista_fam, 0, 2)."/".
                                 substr($oLinha->dta_entrevista_fam, 2, 2)."/".
                                 substr($oLinha->dta_entrevista_fam, 4, 4);
    $oFamilia->setDataEntrevista($dtEntrevista);
    $oFamilia->setDataAtualizacao(new DBDate($this->parseDateCadastroUnico($oLinha->dat_atual_fam)));
    $oFamilia->setRendaPerCapita($oLinha->vlr_renda_media_fam/100);
    $this->oFamilia = $oFamilia;
  }

  /**
   * Define os dados das avaliacoes
   * @param Avaliacao $oAvaliacao Instancia de Avaliacao
   * @param DBLayoutLinha $oLinha linha com os dados de identificacao
   * @param integer $iGrupo Codigo do grupo de perguntas
   * @throws Exception
   */
  protected function setDadosAvaliacao(Avaliacao $oAvaliacao, DBLayoutLinha $oLinha, $iGrupo) {

    foreach ($oAvaliacao->getGruposPerguntas() as $oGrupo) {

      if ($oGrupo->getGrupo() == $iGrupo) {

        foreach ($oGrupo->getPerguntas() as $oPergunta) {

          $oPergunta->getRespostas();
          $oPergunta->setRespostasPorLayout($oLinha);

        }
      }
    }

  }

  /**
   * Cria/Atualiza um cadastrounico. Recebe como parametro uma instaciua de DBLayoutLinha
   * @param DBLayoutLinha $oLinha Instancia da linha
   */
  protected function adicionarCidadao(DBLayoutLinha $oLinha) {

    if (trim($oLinha->num_nis_pessoa_atual) == "") {
      $oLinha->num_nis_pessoa_atual = "S{$oLinha->cod_familiar_fam}{$oLinha->num_ordem_pessoa}";
    }

    $oDaoCadastroUnico = db_utils::getDao("cidadaocadastrounico");
    $sWhere            = "as02_codigounicocidadao = '{$oLinha->num_membro_fmla}'";
    $sSqlDadosCidadao  = $oDaoCadastroUnico->sql_query_file(null, "as02_sequencial, as02_cidadao", null, $sWhere);
    $rsDadosCidadao    = $oDaoCadastroUnico->sql_record($sSqlDadosCidadao);

    if ($oDaoCadastroUnico->numrows == 0) {

      $oCidadao = new CadastroUnico();
    } else {
      $oCidadao = new CadastroUnico(db_utils::fieldsMemory($rsDadosCidadao, 0)->as02_sequencial);
    }

    $oCidadao->setNis($oLinha->num_nis_pessoa_atual);
    $oCidadao->setNome($oLinha->nom_pessoa);
    $oCidadao->setAtivo(true);
    $oCidadao->setBairro($this->oFamilia->localidade);
    $oCidadao->setCEP($this->oFamilia->cep);
    $oCidadao->setComplemento($this->oFamilia->complemento);
    $dtNascimento = $this->parseDateCadastroUnico($oLinha->dta_nasc_pessoa);
    $oCidadao->setDataNascimento($dtNascimento);
    $oCidadao->setEndereco($this->oFamilia->endereco);
    $oCidadao->setMunicipio('');
    $oCidadao->setNumero($this->oFamilia->numero_endereco);
    $oCidadao->setApelido($oLinha->nom_apelido_pessoa);
    $oCidadao->setSexo($oLinha->cod_sexo_pessoa ==1?'M':'F');
    $oCidadao->setSituacaoCidadao(2);
    $oCidadao->setCodigoCadastroUnico($oLinha->num_membro_fmla);
    $oCidadao->setUF(CensoUFRepository::getEstadoPorCodigo($this->oFamilia->uf)->getUF());
    $iTipoFamiliar = 14;
    /**
     * Realizamos um de para dos tipo do parentesco do cadastro unico para o e-cidade.
     * os dados para verificacao, sao so dados da tabela tipofamiliar.
     */
    switch ($oLinha->cod_parentesco_rf_pessoa) {

      case 1:

        $iTipoFamiliar = 0;
        break;

      case 2:

        $iTipoFamiliar = 15;
        break;

      case 3:

        $iTipoFamiliar = 1;
        break;

      case 4:

        $iTipoFamiliar = 2;
        break;

      case 5:

        $iTipoFamiliar = 10;
        break;

      case 6:

        $iTipoFamiliar = 5;
        break;

     case 7:

        $iTipoFamiliar = 6;
        break;

     case 8 :
        $iTipoFamiliar = 14;
        break;

      case 9:
        $iTipoFamiliar = 14;
        break;

      case 10:
        $iTipoFamiliar = 14;
        break;

      case 11:
        $iTipoFamiliar = 14;
        break;

      default:
        $iTipoFamiliar = 14;
        break;
    }

    $lCidadaoJaAtualizado = false;
    if ($oCidadao->getDataAtualizacaoCadastroUnico() != "") {

      $sDataAtualizacaoCidadao = implode("-", array_reverse(explode("/", $oCidadao->getDataAtualizacaoCadastroUnico())));
      $sDataArquivo            = implode("-", array_reverse(explode("/", $this->parseDateCadastroUnico($oLinha->dta_atual_memb))));
      if (db_strtotime($sDataArquivo) <= db_strtotime($sDataAtualizacaoCidadao)) {
        $lCidadaoJaAtualizado = true;
      }
    }

    $oCidadao->isAtualizado = $lCidadaoJaAtualizado;
    $oCidadao->setCodigoTipoFamilia($iTipoFamiliar);
    $oCidadao->iSituacaoCadastro = $oLinha->cod_est_cadastral_memb;
    $this->oCidadao              = $oCidadao;
    if ($oCidadao->iSituacaoCadastro != 4) {

      if (!$oCidadao->isAtualizado) {
        $oCidadao->setDataAtualizacaoCadastroUnico($this->parseDateCadastroUnico($oLinha->dta_atual_memb));
      }

      /**
       * Caso o cidadao for o principal, passamos os telefones de contato para o mesmo
       */
      if ($iTipoFamiliar == 0) {

        foreach ($this->aTelefonesContatoFamilia as $oTelefone) {
          $oCidadao->adicionarTelefone($oTelefone->numero, $oTelefone->tipo, $oTelefone->principal, $oTelefone->ddd);
        }
      }
      $this->oFamilia->adicionarCidadao($oCidadao);
    }
  }


  /**
   * Define os documentos para o cidadao
   * @param $oLinha;
   */
  protected function setDocumentosCidadao($oLinha) {

    if ($oLinha->num_identidade_pessoa == '') {
      $oLinha->num_identidade_pessoa = '0';
    }
    if (trim($oLinha->num_cpf_pessoa) == '') {
      $oLinha->num_cpf_pessoa = '00000000000';
    }
    $this->oCidadao->setIdentidade($oLinha->num_identidade_pessoa);
    $this->oCidadao->setCpfCnpj($oLinha->num_cpf_pessoa);
  }

  /**
   * Retorna o total de familias processadas
   * @return integer
   */
  public function getTotalDeFamilias() {
    return $this->iTotalFamilias;
  }

  /**
   * Formata a data do cadastro unico apra o fotrmado dd/mm/YYYY
   * @return string
   */
  protected function parseDateCadastroUnico($sData) {

    return substr($sData, 0, 2)."/".
           substr($sData, 2, 2)."/".
           substr($sData, 4, 4);
  }

  /**
   * Salva os dados da Familia Atual, assim como seus componentes familiares
   */
  protected function salvarDadosBloco() {

    db_inicio_transacao();
    /**
     * Persistimos os dados dos componentes familiares
     */
    foreach ($this->oFamilia->getComposicaoFamiliar() as $oCidadao) {

      $sDataAtualizacaoCidadao         = implode("-", array_reverse(explode("/", $oCidadao->getDataAtualizacaoCadastroUnico())));
      $sDataAtualizacaoCidadaoAnterior = implode("-", array_reverse(explode("/", $oCidadao->getDataAtualizacaoAnterior())));
      if ($oCidadao->getDataAtualizacaoAnterior() == "" ||
         db_strtotime($sDataAtualizacaoCidadaoAnterior) < db_strtotime($sDataAtualizacaoCidadao)) {
        $oCidadao->salvar();
      }
    }

    $this->oFamilia->salvar();
    FamiliaRepository::removerFamilia($this->oFamilia);
    $this->iQuantidadeProcessada++;
    $this->oFamilia = null;
    $this->oCidadao = null;
    db_fim_transacao(false);
    /**
     * Rodamos o vaccum nas tabelas utilizadas no processamento.
     */
    if ($this->iQuantidadeProcessada >= 1000) {

      $this->iQuantidadeProcessada = 0;
      $this->vaccum();
    }
  }

  /**
   * Realizado um vaccum nas tabelas no processamento.
   * Método é chamadno internamente no processamento dos dados
   */
  protected function vaccum() {

    $aListaTabelas = array("cidadaoavaliacao",
                           "cidadao",
                           "cidadaocadastrounico",
                           "cidadaofamilia",
                           "cidadaofamiliaavaliacao",
                           "cidadaocomposicaofamiliar",
                           "cadastrounicobasemunicipal"
                         );
    foreach ($aListaTabelas as $sTabela) {
      db_query("ANALYZE {$sTabela}");
    }
  }

  /**
   * Atualiza dos dados da avalicao socio-economica da familia, atravez da base Municipal
   * @param Familia $oFamilia instacia da Familia
   */
  public function atualizarFamilia(Familia $oFamilia) {

    db_inicio_transacao();
    if ($oFamilia->getCodigoFamiliarCadastroUnico() == "") {
      return false;
    }

    /**
     * Desligamos o Account do sistema
     */
    db_app::import("Avaliacao");
    db_app::import("AvaliacaoGrupo");
    db_app::import("AvaliacaoPergunta");
    db_app::import("dbLayoutReader");
    db_app::import("dbLayoutLinha");
    $_SESSION["DB_usaAccount"] = "1";
    $oDaoBaseMunicipal         = db_Utils::getDao("cadastrounicobasemunicipal");
    $oAvaliacao                = $oFamilia->adicionarAvaliacao();
    /**
     * Pesquisamos apenas os dados referentes a familia.
     */
    $aDadosBaseMunicipalExcluir = array();
    $sWhere                    = "as09_chaveregistro = '{$oFamilia->getCodigoFamiliarCadastroUnico()}'";
    $sWhere           .= "and as09_tiporegistro in (1, 2, 3, 11)";
    $sSqlDadosFamilia  = $oDaoBaseMunicipal->sql_query_file(null,
                                                            "as09_sequencial, as09_tiporegistro, as09_conteudolinha",
                                                            "as09_tiporegistro",
                                                            $sWhere);
    $rsDadosFamilia = $oDaoBaseMunicipal->sql_record($sSqlDadosFamilia);
    $oLayoutReader  = new DBLayoutReader(ImportacaoCadastroUnico::CODIGO_LAYOUT, 'null', false, false);
    $aLinhasArquivo = db_utils::getCollectionByRecord($rsDadosFamilia);

    /**
     * Caso nao exista linhas, os dados da avaliacao já foram processados.
     */
    if (count($aLinhasArquivo) == 0) {
      return true;
    }
    $aCodigoGrupoAvaliacao = array("01" => array(3000016, 3000017, 3000018),
                                   "02" => array(3000019),
                                   "03" => array(3000020),
                                   "11" => array(3000028)
                                  );
    foreach ($aLinhasArquivo as $oLinha) {

      $aDadosBaseMunicipalExcluir[] = $oLinha->as09_sequencial;
      $oLinhaProcessada             = $oLayoutReader->processarLinha($oLinha->as09_conteudolinha, 0, false, true);
      foreach ($aCodigoGrupoAvaliacao[$oLinhaProcessada->num_reg_arquivo] as $iCodigoGrupo) {
        $this->setDadosAvaliacao($oAvaliacao, $oLinhaProcessada, $iCodigoGrupo);
      }
    }

    /**
     * Atualizamos os dados da Avaliacao
     */
    if ($oFamilia->getAvaliacao() != null) {

      if ($oFamilia->getCodigoLancamentoAvaliacao() == "") {

        $oDaoAvalicaoFamilia                              = db_utils::getDao("cidadaofamiliaavaliacao");
        $oDaoAvalicaoFamilia->as06_avaliacaogruporesposta = $oFamilia->getCodigoGrupoResposta();
        $oDaoAvalicaoFamilia->as06_cidadaofamilia         = $oFamilia->getCodigoSequencial();
        $oDaoAvalicaoFamilia->incluir(null);
        if ($oDaoAvalicaoFamilia->erro_status == 0) {
          throw new BusinessException("Erro ao salvar dados da avaliacao da familia.\n{$oDaoAvalicaoFamilia->erro_msg}");
        }
      }

      foreach ($oFamilia->getAvaliacao()->getGruposPerguntas() as $oGrupoPergunta) {

        foreach ($oGrupoPergunta->getPerguntas() as $oPergunta) {

          $oPergunta->setAvaliacao($oFamilia->getCodigoGrupoResposta());
          $oPergunta->salvarRespostas();
        }
      }
    }

    /**
     * Excluimos os dados base municipal
     */
    if (count($aDadosBaseMunicipalExcluir) > 0) {

      $sWhereExclusao = implode(",", $aDadosBaseMunicipalExcluir);
      $oDaoBaseMunicipal->excluir(null, "as09_sequencial in({$sWhereExclusao})");
    }
    db_fim_transacao(false);
    unset($_SESSION["DB_usaAccount"]);
  }

  /**
   * Atualiza dos dados da familiar, atravez da base Municipal
   */
  public function atualizarCidadao(Cidadao $oCidadao) {

    db_inicio_transacao();
    if ($oCidadao->getCodigoCadastroUnico() == "") {
      return false;
    }

    /**
     * Desligamos o Account do sistema
     */
    db_app::import("Avaliacao");
    db_app::import("AvaliacaoGrupo");
    db_app::import("AvaliacaoPergunta");
    db_app::import("dbLayoutReader");
    db_app::import("dbLayoutLinha");
    $_SESSION["DB_usaAccount"] = "1";
    $oDaoBaseMunicipal         = db_Utils::getDao("cadastrounicobasemunicipal");
    $oAvaliacao                = $oCidadao->getAvaliacao();
    /**
     * Pesquisamos apenas os dados referentes a familia.
     */
    $aDadosBaseMunicipalExcluir = array();
    $sWhere                    = "as09_chaveregistro = '{$oCidadao->getCodigoCadastroUnico()}'";
    $sWhere           .= "and as09_tiporegistro in (4, 5, 6, 7, 8, 12)";
    $sSqlDadosCidadao  = $oDaoBaseMunicipal->sql_query_file(null,
                                                            "as09_sequencial, as09_tiporegistro, as09_conteudolinha",
                                                            "as09_tiporegistro",
                                                            $sWhere);
    $rsDadosCidadao        = $oDaoBaseMunicipal->sql_record($sSqlDadosCidadao);
    $aLinhasArquivo        = db_utils::getCollectionByRecord($rsDadosCidadao);

    /**
     * Caso nao exista linhas, os dados da avaliacao já foram processados.
     */
    if (count($aLinhasArquivo) == 0) {
      return true;
    }

    $oLayoutReader         = new DBLayoutReader(ImportacaoCadastroUnico::CODIGO_LAYOUT, 'null', false, false);
    $aCodigoGrupoAvaliacao = array("04" => array(3000022),
                                   "05" => array(3000023),
                                   "06" => array(3000024),
                                   "07" => array(3000025),
                                   "08" => array(3000026),
                                   "12" => array(3000029)
                                  );

    foreach ($aLinhasArquivo as $oLinha) {

      $aDadosBaseMunicipalExcluir[] = $oLinha->as09_sequencial;
      $oLinhaProcessada             = $oLayoutReader->processarLinha($oLinha->as09_conteudolinha, 0, false, true);
      foreach ($aCodigoGrupoAvaliacao[$oLinhaProcessada->num_reg_arquivo] as $iCodigoGrupo) {
        $this->setDadosAvaliacao($oAvaliacao, $oLinhaProcessada, $iCodigoGrupo);
      }
    }

    /**
     * Atualizamos os dados da Avaliacao
     */
    if ($oCidadao->getAvaliacao() != null) {

      if ($oCidadao->getCodigoLancamentoAvaliacao() == "") {

        $oDaoAvaliacaoCidadao                              = db_utils::getDao("cidadaoavaliacao");
        $oDaoAvaliacaoCidadao->as01_avaliacaogruporesposta = $oCidadao->getCodigoGrupoResposta();
        $oDaoAvaliacaoCidadao->as01_cidadao                = $oCidadao->getCodigo();
        $oDaoAvaliacaoCidadao->as01_cidadao_seq            = $oCidadao->getSequencialInterno();
        $oDaoAvaliacaoCidadao->incluir(null);
        if ($oDaoAvaliacaoCidadao->erro_status == 0) {
          throw new BusinessException("Erro ao salvar dados da avaliacao do cidadao.\n{$oDaoAvaliacaoCidadao->erro_msg}");
        }
      }

      foreach ($oCidadao->getAvaliacao()->getGruposPerguntas() as $oGrupoPergunta) {

        foreach ($oGrupoPergunta->getPerguntas() as $oPergunta) {

          $oPergunta->setAvaliacao($oCidadao->getCodigoGrupoResposta());
          $oPergunta->salvarRespostas();
        }
      }
    }

    /**
     * Excluimos os dados base municipal
     */
    if (count($aDadosBaseMunicipalExcluir) > 0) {

      $sWhereExclusao = implode(",", $aDadosBaseMunicipalExcluir);
      $oDaoBaseMunicipal->excluir(null, "as09_sequencial in({$sWhereExclusao})");
    }
    db_fim_transacao(false);
    unset($_SESSION["DB_usaAccount"]);
  }

  /**
   * Importa os numeros de telefone para a familia.
   * @param  $oLinha
   */
  protected function importarTelefones($oLinha) {

    $aCamposTelefone = array(
                             "num_tel_contato_1_fam",
                             "num_tel_contato_2_fam",
                             "num_tel_contato_3_fam",
                             "num_tel_contato_4_fam",
                            );

    $this->aTelefonesContatoFamilia = array();
    foreach ($aCamposTelefone as $iIndice => $sCampoTelefone) {

      if (isset($oLinha->{$sCampoTelefone}) && $oLinha->{$sCampoTelefone} != "") {

        $sNumeroTelefone = $oLinha->{$sCampoTelefone};

        $oNumeroTelefone = new stdClass();
        $oNumeroTelefone->numero = substr($sNumeroTelefone, 2);
        $oNumeroTelefone->ddd    = substr($sNumeroTelefone, 0, 2);
        /**
         * Utilizamos o primeiro telefone como principal;
         */
        $oNumeroTelefone->principal = $iIndice == 0 ? true : false;

        /**
         * o tipo do telefone é definido pelo primeiro digito do numero,
         * telefones iniciando com 7 até 9, são numeros de celulares.
         * os demais, são numeros fixos. Usaremos os seguintes tipos:
         * 1 = Residencial
         * 2 = Celular
         */
        $iTipoCelular = 1;
        if (substr($oNumeroTelefone->numero, 0, 1) >= '7') {
          $iTipoCelular = 2;
        }

        $oNumeroTelefone->tipo  = $iTipoCelular;
        array_push($this->aTelefonesContatoFamilia, $oNumeroTelefone);
      }
    }
  }
}
?>