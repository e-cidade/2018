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


class ProcessoCompras {

  const ARQUIVO_MENSAGEM = 'patrimonial.compras.ProcessoCompras.';

  /**
   * Tipos de processo de compra
   */
  const TIPO_ITEM        = 1;
  const TIPO_LOTE        = 2;

  /**
   * Tipos de Sitaução do processo
   */
  const EM_ANALISE     = 1;
  const AUTORIZADO     = 2;
  const NAO_AUTORIZADO = 3;

  /**
   * Codigo de um processo de compras
   * @var integer
   */
  protected $iCodigo;


  /**
   * Data de emissão do processo de compras
   */
  protected $dtDataEmissao;

  /**
   * Resumo do Processo de Compras
   */
  protected $sResumo;


  /**
   * Departamento que incluiu o departamento
   */
  protected $iCodigoDepartamento;


  /**
   * Descricao do departamento
   */
  protected $sDescricaoDepartamento;

  /**
   * Situaao do processo de compras
   */
  protected $iSituacao = 2;

  /**
   * Código do usuário que incluiu o processo de compras
   */
  protected $iUsuario;

  /**
   * NOme do usuario que emitiu o processo de compras
   * @var string
   */
  protected $sNomeUsuario;
  /**
   * Array de itens de um processo de compras
   * @var array
   */
  protected $aItens = array();

  /**
   * Lotes Pertencentes ao processo de Compras
   * @var LoteProcessoCompra[]
   */
  protected $aLotes = array();

  /**
   * Tipo de processo de compra
   * @var integer
   */
  protected $iTipoProcesso = 1;

  /**
   *
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoPcProc = db_utils::getDao("pcproc");
      $sSqlPcProc = $oDaoPcProc->sql_query($iCodigo);
      $rsPcProc   = $oDaoPcProc->sql_record($sSqlPcProc);
      if ($oDaoPcProc->numrows > 0) {

        $oDadosProcesso = db_utils::fieldsMemory($rsPcProc, 0);
        $this->setCodigo($iCodigo);
        $this->setDataEmissao(db_formatar($oDadosProcesso->pc80_data, 'd'));
        $this->setCodigoDepartamento($oDadosProcesso->pc80_depto);
        $this->setDescricaoDepartamento($oDadosProcesso->descrdepto);
        $this->setResumo($oDadosProcesso->pc80_resumo);
        $this->setSituacao($oDadosProcesso->pc80_situacao);
        $this->setUsuario($oDadosProcesso->pc80_usuario);
        $this->setNomeUsuario($oDadosProcesso->nome);
        $this->setTipoProcesso($oDadosProcesso->pc80_tipoprocesso);
        unset($oDadosProcesso);
      }
    }
  }

  /**
   * Seta valor na propriedade iCodigo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  /**
   * Retorna o valor da propriedade iCodigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a data da emissao do Processo de Compras
   * Retorno data no formato DD/MM/YYYY
   * @return string
   */
  public function getDataEmissao() {
    return $this->dtDataEmissao;
  }

  /**
   * Define a data da emissao do processo de compras
   * @param string $dtDataEmissao data no formado dd/mm/YYYY
   */
  public function setDataEmissao($dtDataEmissao) {
    $this->dtDataEmissao = $dtDataEmissao;
  }

  /**
   * Retorna o codigo do departamento que pertence o process de compras
   * @return integer
   */
  public function getCodigoDepartamento() {
    return $this->iCodigoDepartamento;
  }

  /**
   * Define o departamento que pertence o processo de compras
   * @param integer $iCodigoDepartamento códigfo do departamento (db_Depart.coddeto)
   */
  public function setCodigoDepartamento($iCodigoDepartamento) {
    $this->iCodigoDepartamento = $iCodigoDepartamento;
  }

  /**
   * Retorna a Situação do processo de compras.
   * Os valores que o método retorna são 1 - Em Analise 2 - Autorizado 3 - Não Autorizado
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Define a situação do Processo de compras
   * Os valores validos para $iSituacao são:
   * 1 - Em Analise 2 - Autorizado 3 - Não Autorizado
   * Aonde: 1 - O processo de compras nao pode ser utilizado em Licitações, ou orcamentos
   *        2 - O processo estpá liberado para gerar licitação e gerar Orçamentos.
   *        3 - O processo não foi autorizado, e não poderá ser mais utilizado.
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna o usuário responsável pelo processo de compras
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }

  /**
   * Define o usuário responsável pelo processo de compras
   * @param integer $iUsuario Código do usuário db_usuarios.id_usuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Retorna o nome do usuario responsável pelo processo de compras
   * @return string
   */
  public function getNomeUsuario() {
    return $this->sNomeUsuario;
  }

  /**
   * Define o nome do usuario
   * @param string $sNomeUsuario nome do usuario
   */
  protected function setNomeUsuario($sNomeUsuario) {
    $this->sNomeUsuario = $sNomeUsuario;
  }
  /**
   * Retorna a descrição do departamento do processo de compras
   * @return string
   */
  public function getDescricaoDepartamento() {
    return $this->sDescricaoDepartamento;
  }

  /**
   * define a descrição do departamento do processo de compras
   * @param unknown_type $sDescricaoDepartamento
   */
  protected function setDescricaoDepartamento($sDescricaoDepartamento) {
    $this->sDescricaoDepartamento = $sDescricaoDepartamento;
  }

  /**
   * Retorna o resumo do processo de compras
   * @return string
   */
  public function getResumo() {
    return $this->sResumo;
  }

  /**
   * define o resumo do processo de comprsa
   * @param string $sResumo
   */
  public function setResumo($sResumo) {
    $this->sResumo = $sResumo;
  }

  /**
   * Adiciona um item no array que armazena
   * @param ItemProcessoCompra $oItem
   */
  public function adicionarItem($oItem) {
    $this->aItens[] = $oItem;
  }

  /**
   * Retorna os itens do processo de compras
   * @return ItemProcessoCompra[]
   * @acess public
   */
  public function getItens() {

    if (count($this->aItens) == 0 && !empty($this->iCodigo)) {

      $oDaoProcessoCompraItem      = new cl_pcprocitem();
      $sWhere                      = "pc81_codproc = {$this->getCodigo()}";
      $sSqlDadosProcessoCompraItem = $oDaoProcessoCompraItem->sql_query_file(null, "*", "pc81_codprocitem", $sWhere);
      $rsDadosProcessoCompraItem   = $oDaoProcessoCompraItem->sql_record($sSqlDadosProcessoCompraItem);
      if ($oDaoProcessoCompraItem->numrows > 0) {

        for ($iProcessoCompraItem = 0; $iProcessoCompraItem < $oDaoProcessoCompraItem->numrows; $iProcessoCompraItem++) {

          $iCodigoItem          = db_utils::fieldsMemory($rsDadosProcessoCompraItem, $iProcessoCompraItem)->pc81_codprocitem;
          $oItemProcessoCompras = ItemProcessoCompraRepository::getItemByCodigo($iCodigoItem);
          $this->aItens[]       = $oItemProcessoCompras;
        }
      }
    }

    return $this->aItens;
  }

  static function getItensPorFornecedor($aProcessos, $iFornecedor, $lTipo) {

     $oDaoPCprocItem  = db_utils::getDao("pcprocitem");

     $sVirgula   = "";
     $sProcessos = "";

     foreach ($aProcessos as $oProcesso){

       if ($oProcesso != null || $oProcesso != '') {

         $sProcessos .= $sVirgula . $oProcesso;
         $sVirgula = ', ';
       }
     }

    if (empty($sProcessos)) {
      return array();
    }

     $sCampos          = "pc81_codprocitem as codigo, pc01_codmater as codigomaterial,";
     $sCampos         .= "pc01_descrmater as material, pc23_vlrun as valorunitario,";
     $sCampos         .= "pc01_servico as servico, 1 as origem, pc18_codele as elemento,";
     $sCampos         .= "pc23_quant as quantidade, pc23_valor as valortotal,pc81_codproc as numero";
     $sSqlProcessos   = $oDaoPCprocItem->sql_query_soljulg(null, $sCampos, "pc81_codproc, pc11_seq",
                                                   "pc21_numcgm= {$iFornecedor}
                                                    and ac23_sequencial is null
                                                   and pc81_codproc in({$sProcessos})");

     $rsProcessos    = $oDaoPCprocItem->sql_record($sSqlProcessos);
     return db_utils::getCollectionByRecord($rsProcessos, false, false, true);
  }

  /**
   * retorna todas os processos de compras que possuem um item ganho pelo credor.
   *
   * @param integer $iFornecedor codigo do fornecedor
   * @return array
   */
  static function getProcessosByFornecedor($iFornecedor, $lValidaAutorizadas=false) {

    $oDaoPCprocItem = db_utils::getDao("pcprocitem");
    $sWhere = '';
    if ($lValidaAutorizadas) {

      $sWhere .= " and not exists (";
      $sWhere .= "                 select 1 ";
      $sWhere .= "                   from empautoriza  ";
      $sWhere .= "                        inner join empautitem           on e55_autori                      = e54_autori";
      $sWhere .= "                        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
      $sWhere .= "                                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori";
      $sWhere .= "                        inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
      $sWhere .= "                  where pc81_codproc = pc80_codproc";
      $sWhere .= "                    and e54_anulad is null";
      $sWhere .= " )";
    }
    $sCampos        = "distinct pc81_codproc as licitacao, pc10_resumo as objeto, '' as numero, pc21_numcgm as cgm";
    $sCampos       .= ", pc11_numero as numero_exercicio, pc80_data as data";
    $sSqlProcessos  = $oDaoPCprocItem->sql_query_soljulg(null, $sCampos, "1",
                                                   "pc21_numcgm= {$iFornecedor} and ac23_sequencial is null  {$sWhere}");

    $rsProcessos    = $oDaoPCprocItem->sql_record($sSqlProcessos);
    return db_utils::getCollectionByRecord($rsProcessos, false, false, true);
  }

  /**
   * Salva um processo de compras para uma solicitação
   * @throws Exception
   */
  public function salvar() {

    $oDaoPcProc                    = new cl_pcproc();
    $oDaoPcProc->pc80_codproc      = null;
    $oDaoPcProc->pc80_data         = implode("-", array_reverse(explode("/", $this->getDataEmissao())));
    $oDaoPcProc->pc80_depto        = $this->getCodigoDepartamento();
    $oDaoPcProc->pc80_usuario      = $this->getUsuario();
    $oDaoPcProc->pc80_resumo       = $this->sResumo;
    $oDaoPcProc->pc80_situacao     = $this->getSituacao();
    $oDaoPcProc->pc80_tipoprocesso = $this->getTipoProcesso();

    if (empty($this->iCodigo)) {

      $oDaoPcProc->incluir(null);
      $this->iCodigo = $oDaoPcProc->pc80_codproc;

    } else {

      $oDaoPcProc->pc80_codproc = $this->getCodigo();
      $oDaoPcProc->alterar($oDaoPcProc->pc80_codproc);
    }

    if ($oDaoPcProc->erro_status == 0) {

      $sMsgErro  = "Não foi possível salvar o processo de compras.\n\n";
      $sMsgErro .= str_replace("\n", "\\n", $oDaoPcProc->erro_msg);
      throw new Exception($sMsgErro);
    }

    $aItemProcessoCompra = $this->getItens();
    foreach ($aItemProcessoCompra as $oItemProcessoCompra) {

      $oDaoPcProcItem                   = new cl_pcprocitem();
      $oDaoPcProcItem->pc81_codproc     = $this->getCodigo();

      if ($oItemProcessoCompra instanceof ItemProcessoCompra) {

        $oDaoPcProcItem->pc81_solicitem   = $oItemProcessoCompra->getItemSolicitacao()->getCodigoItemSolicitacao();
        $oDaoPcProcItem->pc81_codprocitem = $oItemProcessoCompra->getCodigo();
      } else {
        $oDaoPcProcItem->pc81_codprocitem = '';
        $oDaoPcProcItem->pc81_solicitem = $oItemProcessoCompra->pc81_solicitem;
      }

      if (empty($oDaoPcProcItem->pc81_codprocitem)) {

        $oDaoPcProcItem->incluir(null);
        if ($oItemProcessoCompra instanceof ItemProcessoCompra) {
          $oItemProcessoCompra->setCodigo($oDaoPcProcItem->pc81_codprocitem);
        } else {
          $oItemProcessoCompra->pc81_codprocitem = $oDaoPcProcItem->pc81_codprocitem;
        }

      } else {
        $oDaoPcProcItem->alterar($oItemProcessoCompra->getCodigo());
      }

      if ($oDaoPcProcItem->erro_status == 0) {
        throw new Exception($oDaoPcProcItem->erro_msg);
      }
    }

    /**
     * Salva os lotes no processo compra informado
     */
    if ($this->getTipoProcesso() == self::TIPO_LOTE){

      foreach ($this->getLotes() as $oLote) {
        $oLote->salvar();
      }
    }

    return true;
  }

  public function getItensParaAutorizacao() {

    $oDaoPcOrcamJulg      = db_utils::getDao("pcorcamjulg");
    $oDaoOrcReservaSol    = db_utils::getDao("orcreservasol");
    $this->oDaoParametros = db_utils::getDao("empparametro");
    $oDaoPcProc           = db_utils::getDao("pcproc");

    $sCampos  = "pc11_seq,";
    $sCampos .= "pc01_codmater as codigomaterial,";
    $sCampos .= "pc01_descrmater as descricaomaterial,";
    $sCampos .= "pc01_servico as servico,";
    $sCampos .= "pc11_quant as quanttotalitem,";
    $sCampos .= "pc11_vlrun as valorunitario,";
    $sCampos .= "pc11_numero,";
    $sCampos .= "pc11_codigo as codigoitemsolicitacao,";
    $sCampos .= "pc13_coddot as codigodotacao,";
    $sCampos .= "pc13_sequencial as codigodotacaoitem,";
    $sCampos .= "pc13_quant as quanttotaldotacao,";
    $sCampos .= "pc13_anousu as anodotacao,";
    $sCampos .= "pc13_valor as valordotacao,";
    $sCampos .= "pc17_unid,";
    $sCampos .= "pc17_quant,";
    $sCampos .= "pc23_orcamforne,";
    $sCampos .= "pc23_valor as valorfornecedor,";
    $sCampos .= "case when trim(pc11_resum) <> '' then pc11_resum";
    $sCampos .= "     else pc10_resumo ";
    $sCampos .= " end as observacao,";
    $sCampos .= "pc10_resumo as observacao_solicita,";
    $sCampos .= "pc23_vlrun as valorunitariofornecedor,";
    $sCampos .= "pc23_quant as quantfornecedor,";
    $sCampos .= "z01_numcgm as codigofornecedor,";
    $sCampos .= "z01_nome as fornecedor,";
    $sCampos .= "m61_descr,";
    $sCampos .= "m61_usaquant,";
    $sCampos .= "pc10_numero as codigosolicitacao,";
    $sCampos .= "pc19_orctiporec as contrapartida,";
    $sCampos .= "pc81_codprocitem as codigoitemprocesso,";
    $sCampos .= "pc22_orcamitem,";
    $sCampos .= "pc18_codele as codigoelemento,";
    $sCampos .= "o56_descr as descricaoelemento,";
    $sCampos .= "o56_elemento as elemento, pc11_servicoquantidade as servicoquantidade";

    $sOrder = "z01_numcgm,pc13_coddot,pc18_codele, pc19_sequencial, pc19_orctiporec,pc13_sequencial";
    $sWhere = "pc80_codproc = {$this->getCodigo()} and pcorcamjulg.pc24_pontuacao = 1 and pc10_instit = ".db_getsession("DB_instit");

    $sSqlProcCompras    = $oDaoPcProc->sql_query_gerautproc(null, $sCampos, $sOrder, $sWhere);
    $rsProcessoCompra   = $oDaoPcProc->sql_record($sSqlProcCompras);
    $iRowProcessoCompra = $oDaoPcProc->numrows;
    $aItens          = array();
    if ($iRowProcessoCompra > 0) {

      for ($i = 0; $i < $iRowProcessoCompra; $i++) {

        $oDados = db_utils::fieldsMemory($rsProcessoCompra, $i, false, false, true);

        if ($oDados->codigofornecedor == "") {
          throw new Exception("Não existe orçamento julgado para este processo de compras.");
        }
        /*
         * calcula o percentual da dotação em relacao ao valor total
         */

        $nPercentualDotacao = 100;
        if ( $oDados->valorunitario > 0 ) {
          $nPercentualDotacao = ($oDados->valordotacao*100)/($oDados->quanttotalitem*$oDados->valorunitario);
          $oDados->percentual = $nPercentualDotacao;
        }
        /**
         * retorna o valor novo da dotacao; (pode ter um aumento/diminuição do valor)
         */
        $nValorDotacao          = round(($oDados->valorfornecedor * $nPercentualDotacao)/100, 2);
        $oDados->valordiferenca = $nValorDotacao;

        /**
         * Verificamos o valor reservado para o item
         */
        $sSqlReservaDotacao    = $oDaoOrcReservaSol->sql_query_orcreserva(
                                                                       null,
                                                                       null,
                                                                       "o80_codres,o80_valor",
                                                                       "",
                                                                       "o82_pcdotac = {$oDados->codigodotacaoitem}");
        $rsReservaDotacao          = $oDaoOrcReservaSol->sql_record($sSqlReservaDotacao);
        $oDados->valorreserva      = 0;
        $oDados->dotacaocomsaldo   = true;
        $oDados->saldofinaldotacao = 0;

        if ($oDaoOrcReservaSol->numrows == 1) {
          $oDados->valorreserva = db_utils::fieldsMemory($rsReservaDotacao, 0)->o80_valor;
        }

        $oDados->quantidadeautorizada = 0;
        $oDados->valorautorizado      = 0;
        $oDados->saldoautorizar       = $oDados->valordotacao;
        if (!empty($oDados->codigoitemprocesso)) {

          $oValoresAutorizados          = $this->getValoresParciais($oDados->codigoitemprocesso,
                                                                    $oDados->codigodotacao,
                                                                    $oDados->contrapartida);

          $oDados->quantidadeautorizada = $oValoresAutorizados->iQuantidadeAutorizacao;
          $oDados->valorautorizado      = $oValoresAutorizados->nValorAutorizacao;
          $oDados->saldoautorizar       = $oValoresAutorizados->nValorSaldoTotal;
        }
        $oDotacao                     = new Dotacao($oDados->codigodotacao, $oDados->anodotacao);
        $oDados->saldofinaldotacao    = $oDotacao->getSaldoAtualMenosReservado();
        $oDados->servico              = $oDados->servico=='t'?true:false;

        /**
         * Verifica se a dotação tem saldo para poder autorizar o item
         */
        $nSaldoAtualReserva = $oDotacao->getSaldoAtualMenosReservado() + $oDados->valorreserva;
        if ($nSaldoAtualReserva <= 0 && $oDados->valorreserva == 0) {
          $oDados->dotacaocomsaldo = false;
        }

        if (($nSaldoAtualReserva) < $oDados->valorunitario && $oDados->servico == false) {
          $oDados->dotacaocomsaldo = false;
          if ($oDados->valorreserva > $oDados->valorunitario) {
            $oDados->dotacaocomsaldo = true;
          }
        }

        /**
         * Verificamos as quantidades executadas do item
         */
        $oDados->saldoquantidade      = $oDados->quanttotaldotacao - $oDados->quantidadeautorizada;
        $oDados->saldovalor           = $oDados->valordiferenca    - $oDados->valorautorizado;
        if ($oDados->servico && $oDados->servicoquantidade != "t") {
          $oDados->saldoquantidade = 1;
        }
        $oDados->autorizacaogeradas = array();
        if (!empty($oDados->codigoitemprocesso)) {
          $oDados->autorizacaogeradas  = licitacao::getAutorizacoes($oDados->codigoitemprocesso, $oDados->codigodotacao);
        }
        /**
         * busca o parametro de casas decimais para formatar o valor jogado na grid
         */
        $iAnoSessao             = db_getsession("DB_anousu");
        $sWherePeriodoParametro = " e39_anousu = {$iAnoSessao} ";
        $sSqlPeriodoParametro   = $this->oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
        $rsPeriodoParametro     = $this->oDaoParametros->sql_record($sSqlPeriodoParametro);

        $iNumDec = 2;
        if ($this->oDaoParametros->numrows > 0) {

          $iNumDec =  (int)db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec;

        }
        $oDados->valorunitariofornecedor = number_format((float)$oDados->valorunitariofornecedor,
                                                        $iNumDec,
                                                         '.','');
        $aItens[] = $oDados;

      }
    }
    return $aItens;
  }



 /**
   * Retorna o valor total parcial da licitacao
   *
   * @param integer_type $iCodigoItemProcesso
   * @param integer_type $iCodigoDotacao
   * @param integer_type $iOrcTipoRec
   * @return $oDadoValorParcial
   */
  public function getValoresParciais($iCodigoItemProcesso, $iCodigoDotacao, $iOrcTipoRec=null) {

    if (empty($iCodigoItemProcesso)) {
      throw new Exception("Código do item do processo não informado!");
    }

    if (empty($iCodigoDotacao)) {
      throw new Exception("Código da dotação não informado!");
    }

    /**
     * Retorna somentes as autorizacoes das contrapartidas
     */
    $sWhereContrapartida = " and e56_orctiporec is null";
    if (!empty($iOrcTipoRec)) {
      $sWhereContrapartida = " and e56_orctiporec = {$iOrcTipoRec}";
    }

    $oDaoEmpAutItem    = db_utils::getDao("empautitem");
  	$oDaoPcOrcam       = db_utils::getDao("pcorcam");

  	$oDadoValorParcial = new stdClass();
  	$oDadoValorParcial->nValorAutorizacao      = 0;
    $oDadoValorParcial->iQuantidadeAutorizacao = 0;
    $oDadoValorParcial->nValorItemJulgado      = 0;
    $oDadoValorParcial->iQuantidadeItemJulgado = 0;

    /**
     * Retorna o valor total da autorizacao de empenho da licitacao
     */
    $sCampos           = "sum(e55_vltot) as valorautorizacao,               ";
    $sCampos          .= "sum(e55_quant) as quantidadeautorizacao           ";
    $sWhere            = "          e73_pcprocitem = {$iCodigoItemProcesso} ";
    $sWhere           .= "      and e56_coddot     = {$iCodigoDotacao}      ";
    $sWhere           .= "      and e54_anulad is null                      ";
    $sWhere           .= "      {$sWhereContrapartida}                      ";
    $sWhere           .= " group by e55_vltot,                              ";
    $sWhere           .= "          e55_quant                               ";
    $sSqlAutorizacao   = $oDaoEmpAutItem->sql_query_itemdot(null, null, $sCampos, null, $sWhere);

    $rsSqlAutorizacao  = $oDaoEmpAutItem->sql_record($sSqlAutorizacao);
    if ($oDaoEmpAutItem->numrows > 0) {

    	for ($iIndEmpAutItem = 0; $iIndEmpAutItem < $oDaoEmpAutItem->numrows; $iIndEmpAutItem++) {

	  	  $oAutorizacao                               = db_utils::fieldsMemory($rsSqlAutorizacao, $iIndEmpAutItem);
	  	  $oDadoValorParcial->nValorAutorizacao      += $oAutorizacao->valorautorizacao;
	      $oDadoValorParcial->iQuantidadeAutorizacao += $oAutorizacao->quantidadeautorizacao;
    	}
    }

    /**
     * Retorna o valor do item julgado na licitacao
     */
  	$sCampos              = "pc23_quant, pc23_valor, pc13_valor, pc13_quant, pc11_vlrun, pc11_quant";
  	$sWhere               = "pc81_codprocitem = {$iCodigoItemProcesso} and pc24_pontuacao = 1";
  	$sWhere              .= " and pc13_coddot  = {$iCodigoDotacao} ";
  	$sWhereContrapartida  = " and pc19_orctiporec is null ";
    if ($iOrcTipoRec > 0) {
      $sWhereContrapartida = "  and pc19_orctiporec = {$iOrcTipoRec} ";
    }
    $sWhere .= $sWhereContrapartida;
    $sSqlPcOrcam       = $oDaoPcOrcam->sql_query_valor_item_julgado_processocompra(null, $sCampos, null, $sWhere);
  	$rsSqlPcOrcam      = $oDaoPcOrcam->sql_record($sSqlPcOrcam);
  	if ($oDaoPcOrcam->numrows > 0) {

  		for ($iIndPcOrcam = 0; $iIndPcOrcam < $oDaoPcOrcam->numrows; $iIndPcOrcam++) {

	  	  $oItemJulgado                               = db_utils::fieldsMemory($rsSqlPcOrcam, $iIndPcOrcam);
	  	  $nPercentualDotacao = 100;
	  	  if ($oItemJulgado->pc11_vlrun > 0) {
	  	    $nPercentualDotacao = ($oItemJulgado->pc13_valor * 100) /
	  	                          ($oItemJulgado->pc11_quant * $oItemJulgado->pc11_vlrun);
	  	  }
        /**
         * retorna o valor novo da dotacao; (pode ter um aumento/diminuição do valor)
         */
        $nValorDotacao          = round(($oItemJulgado->pc23_valor * $nPercentualDotacao) / 100, 2);
        $oDados->valordiferenca = $nValorDotacao;
		    $oDadoValorParcial->nValorItemJulgado      += $nValorDotacao;
		    $oDadoValorParcial->iQuantidadeItemJulgado += $oItemJulgado->pc23_quant;
  		}
  	}
    $oDadoValorParcial->nValorSaldoTotal = ( $oDadoValorParcial->nValorItemJulgado
                                           - $oDadoValorParcial->nValorAutorizacao);
    return $oDadoValorParcial;
  }



/**
   * Gera a autorização de empenho para uma solicitação de compras
   * @param array $aDadosAutorizacao
   */
  public function gerarAutorizacoes($aDadosAutorizacao) {

    $aAutorizacoes     = array();
    $oDaoOrcReservaSol = db_utils::getDao("orcreservasol");
    $oDaoOrcReserva    = db_utils::getDao("orcreserva");
    $oDaoPcdotac       = db_utils::getDao("pcdotac");
    $oDaoSolicitem     = db_utils::getDao("solicitem");

    /**
     * Criamos um orcamento para os itens que nao possuem orcamento lançado
     *
     */

    foreach ($aDadosAutorizacao as $oDados) {

      $nValorTotal = 0;
      foreach ($oDados->itens as $oItem) {

        $nValorTotal += $oItem->valortotal;
        /**
         * verificamos se exite reserva de saldo para a solicitacao;
         * caso exista, devemos calcular a diferença entre o que deve ser gerado para a autorizacao e a solictacao
         */

        $aReservas = itemSolicitacao::getReservasSaldoDotacao($oItem->pcdotac);
        if (count($aReservas)  > 0) {

          $nNovoValorReserva   = $aReservas[0]->valor - $oItem->valortotal;
          if ($nNovoValorReserva < 0) {
            $nNovoValorReserva = 0;
          }

          /**
           * excluirmos a reserva e incluimos uma nova
           */
          $oDaoOrcReservaSol->excluir(null, "o82_codres = {$aReservas[0]->codigoreserva}");
          if ($oDaoOrcReservaSol->erro_status == 0) {
            throw new Exception($oDaoOrcReservaSol->erro_msg);
          }

          /**
           * Excluir OrcReserva
           */
          $oDaoOrcReserva->excluir($aReservas[0]->codigoreserva);
          if ($oDaoOrcReserva->erro_status == 0) {
            throw new Exception($oDaoOrcReserva->erro_msg);
          }

          /**
           * Incluímos os dados na OrcReserva, caso o item ainda tenha valor dispo
           */
          $oSaldo = $this->getValoresParciais($oItem->codigoprocesso,
                                              $oDados->dotacao,
                                              $oDados->contrapartida
                                             );

            // print_r($oSaldo);
          if ($nNovoValorReserva > 0 && ($oSaldo->nValorAutorizacao + $oItem->valortotal < $oSaldo->nValorItemJulgado)) {

            $oDaoOrcReserva->o80_anousu = db_getsession("DB_anousu");
            $oDaoOrcReserva->o80_coddot = $oDados->dotacao;
            $oDaoOrcReserva->o80_dtfim  = db_getsession("DB_anousu")."-12-31";
            $oDaoOrcReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
            $oDaoOrcReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
            $oDaoOrcReserva->o80_valor  = $nNovoValorReserva;
            $oDaoOrcReserva->o80_descr  = "Reserva item Solicitacao";
            $oDaoOrcReserva->incluir(null);

            if ($oDaoOrcReserva->erro_status == 0) {

              $sMsgErro  = "Não foi possivel gerar reserva para a dotação: {$oDados->dotacao}.\n";
              $sMsgErro .= $oDaoOrcReserva->erro_msg;
              throw new Exception($sMsgErro);
            }

            $oDaoOrcReservaSol->o82_codres    = $oDaoOrcReserva->o80_codres;
            $oDaoOrcReservaSol->o82_pcdotac   = $oDados->pcdotac;
            $oDaoOrcReservaSol->o82_solicitem = $oItem->solicitem;
            $oDaoOrcReservaSol->incluir(null);
            if ($oDaoOrcReservaSol->erro_status == 0) {

              $sMsgErro  = "Não foi possivel gerar reserva para a dotação: {$oDados->dotacao}.\n";
              $sMsgErro .= $oDaoOrcReservaSol->erro_msg;
              throw new Exception($sMsgErro);
            }
          }
        }
      }
      /**
       * Salvamos a Autorizacao;
       * Resumo da autorização
       */
      $rsPcdotac = $oDaoPcdotac->sql_record($oDaoPcdotac->sql_query_solicita(null,
      																																			 null,
      																																			 null,
      																																			 "pc10_resumo",
      																																			 null,
      																																			 "pc13_sequencial = {$oItem->pcdotac}"));

      $sResumo   = $oDaoPcdotac->numrows > 0 ? db_utils::fieldsMemory($rsPcdotac, 0)->pc10_resumo : $oDados->resumo;

      $oAutorizacao = new AutorizacaoEmpenho();
      $oFornecedor  = CgmFactory::getInstanceByCgm($oDados->cgm);

      $oAutorizacao->setValor($nValorTotal);
      $oAutorizacao->setFornecedor($oFornecedor);
      $oAutorizacao->setDotacao($oDados->dotacao);
      $oAutorizacao->setDesdobramento($oDados->elemento);
      $oAutorizacao->setTipoEmpenho($oDados->tipoempenho);
      $oAutorizacao->setContraPartida($oDados->contrapartida);
      $oAutorizacao->setCaracteristicaPeculiar($oDados->concarpeculiar);

      $aItemSolcitem = array();
      foreach ($oDados->itens as $oItem) {

        $oAutorizacao->addItem($oItem);
        $aItemSolcitem[] = $oItem->solicitem;
      }

      $oAutorizacao->setDestino($oDados->destino);
      $oAutorizacao->setContato($oDados->sContato);
      $oAutorizacao->setResumo(addslashes($sResumo));
      $oAutorizacao->setTelefone($oDados->sTelefone);
      $oAutorizacao->setTipoCompra($oDados->tipocompra);
      $oAutorizacao->setPrazoEntrega($oDados->prazoentrega);
      $oAutorizacao->setTipoLicitacao($oDados->sTipoLicitacao);
      $oAutorizacao->setNumeroLicitacao($oDados->iNumeroLicitacao);
      $oAutorizacao->setOutrasCondicoes($oDados->sOutrasCondicoes);
      $oAutorizacao->setCondicaoPagamento($oDados->condicaopagamento);
      $oAutorizacao->salvar();

      $sProcessoAdministrativo = null;

      if ( isset($oDados->e150_numeroprocesso) && !empty($oDados->e150_numeroprocesso) ){
        $sProcessoAdministrativo = db_stdClass::normalizeStringJsonEscapeString($oDados->e150_numeroprocesso);
      }

      /**
       * Buscar o código do processo da tabela solicitaprotprocesso e incluir na empautorizaprotprocesso caso tenha
       */
      $oDaoSolicitem             = db_utils::getDao("solicitem");
      $sCodigosItens             = implode(",", $aItemSolcitem);
      $sSqlBuscaSolicitem        = $oDaoSolicitem->sql_query_solicitaprotprocesso(null,
                                                                                  "solicitaprotprocesso.*",
                                                                                  null,
      																																						"pc11_codigo in ({$sCodigosItens})");
      $rsBuscaSolicitem          = $oDaoSolicitem->sql_record($sSqlBuscaSolicitem);
      $oDadoSolicitaProtProcesso = db_utils::fieldsMemory($rsBuscaSolicitem, 0);

      if ( empty($sProcessoAdministrativo) &&  !empty($oDadoSolicitaProtProcesso->pc90_numeroprocesso) ) {
        $sProcessoAdministrativo = $oDadoSolicitaProtProcesso->pc90_numeroprocesso;
      }

      if (!empty($sProcessoAdministrativo)) {

        $oDaoEmpAutorizaProcesso                      = db_utils::getDao("empautorizaprocesso");
        $oDaoEmpAutorizaProcesso->e150_sequencial     = null;
        $oDaoEmpAutorizaProcesso->e150_empautoriza    = $oAutorizacao->getAutorizacao();
        $oDaoEmpAutorizaProcesso->e150_numeroprocesso = $sProcessoAdministrativo;
        $oDaoEmpAutorizaProcesso->incluir(null);
        if ($oDaoEmpAutorizaProcesso->erro_status == 0) {

          $sMensagemProcessoAdministrativo  = "Ocorreu um erro para incluir o número do processo administrativo ";
          $sMensagemProcessoAdministrativo .= "na autorização de empenho.\n\n{$oDaoEmpAutorizaProcesso->erro_msg}";
          throw new Exception($sMensagemProcessoAdministrativo);
        }
      }
      $aAutorizacoes[] = $oAutorizacao->getAutorizacao();
    }
    return $aAutorizacoes;
  }

  /**
   * Busca as solicitações que tem dotação do ano anterior.
   * @return mixed
   */
  public function getSolicitacoesDotacaoAnoAnterior() {

    $oDaoPcProcItem   = db_utils::getDao("pcprocitem");
    $sWhereDotacao    = "pc81_codproc = {$this->getCodigo()} and pc13_anousu < ".db_getsession("DB_anousu");
    $sCamposDotacao   = "distinct pc11_numero as solicita";
    $sSqlBuscaDotacao = $oDaoPcProcItem->sql_query_dotac(null, $sCamposDotacao, null, $sWhereDotacao);
    $rsBuscaDotacao   = $oDaoPcProcItem->sql_record($sSqlBuscaDotacao);
    $iRowDotacao      = $oDaoPcProcItem->numrows;
    $aSolicitacao     = array();

    if ($iRowDotacao > 0) {

      for ($iRow = 0; $iRow < $iRowDotacao; $iRow++) {

        $iSolicita      = db_utils::fieldsMemory($rsBuscaDotacao, $iRow)->solicita;
        $aSolicitacao[] = $iSolicita;
      }
    }
    return $aSolicitacao;
  }

  /**
   * Adiciona lote no processo de compras
   *
   * @param String $sNomeLote
   * @return LoteProcessoCompra
   * @throws BusinessException
   * @access public
   */
  public function adicionarLote($sNomeLote) {

    if (empty($sNomeLote)) {
      throw new BusinessException(_M(ProcessoCompras::ARQUIVO_MENSAGEM . "descricao_nao_informado"));
    }

    foreach ($this->getLotes() as $oLotes) {

      if ($oLotes->getNome() == $sNomeLote) {

        $oVariaveis            = new stdClass();
        $oVariaveis->nome_lote = $sNomeLote;
        throw new BusinessException(_M(ProcessoCompras::ARQUIVO_MENSAGEM . "lote_cadastrado", $oVariaveis));
      }
    }

    $oLoteProcessoCompra = new LoteProcessoCompra();
    $oLoteProcessoCompra->setNome($sNomeLote);
    $oLoteProcessoCompra->setProcessoCompra($this);
    $this->aLotes[] = $oLoteProcessoCompra;
    return $oLoteProcessoCompra;
  }

  /**
   * Retorna os lotes do processo de compras
   *
   * @return LoteProcessoCompra[]
   * @acess public
   */
  public function getLotes() {

    if (count($this->aLotes) == 0) {

      $oDaoProcessCompraLote = new cl_processocompralote();
      $sWhere                = "pc68_pcproc = {$this->getCodigo()}";
      $sSqlDadosLotes        = $oDaoProcessCompraLote->sql_query_file(null, "*", "pc68_nome", $sWhere);
      $rsDadosLote           = $oDaoProcessCompraLote->sql_record($sSqlDadosLotes);
      if ($oDaoProcessCompraLote->numrows > 0) {

        for ($iLote = 0; $iLote < $oDaoProcessCompraLote->numrows; $iLote++) {
          $this->aLotes[] = new LoteProcessoCompra(db_utils::fieldsMemory($rsDadosLote, $iLote)->pc68_sequencial);
        }
      }
    }

    return $this->aLotes;
  }

 /**
  * Retorna um lote através do código
  *
  * @access public
  * @param Integer $iCodigoLote
  * @return LoteProcessaCompra|boolean
  */
  public function getLotePorCodigo($iCodigoLote) {

    foreach ($this->getLotes() as $oLote) {

      if ($oLote->getCodigo() == $iCodigoLote) {
        return $oLote;
      }
    }

    return false;
  }

 /**
  * Retorna um item através do código
  *
  * @access public
  * @param Integer $iCodigoItem
  * @return boolean | ItemProcessoCompra
  */
  public function getItemPorCodigo($iCodigoItem) {

    foreach ($this->getItens() as $oItemProcessoCompra) {

      if ($oItemProcessoCompra->getCodigo() == $iCodigoItem){
        return $oItemProcessoCompra;
      }
    }

    return false;
  }

  /**
   * Retorna o tipo de processo de compra
   * @return integer
   */
  public function getTipoProcesso() {
    return $this->iTipoProcesso;
  }

  /**
   * Retorna todos os orcamentos do processo de compras
   * @return OrcamentoCompra[]
   */
  public function getOrcamentos() {

    $aOrcamentos     = array();
    $oDaoPcOrcamItem = new cl_pcorcamitemproc();
    $sWhere          = "pc81_codproc = {$this->getCodigo()}";
    $sSqlOrcamento   = $oDaoPcOrcamItem->sql_query(null, null, "distinct pc20_codorc", null, $sWhere);
    $rsOrcamentos    = $oDaoPcOrcamItem->sql_record($sSqlOrcamento);
    if (!$rsOrcamentos) {
      return $aOrcamentos;
    }
    for ($iOrcamento = 0; $iOrcamento < $oDaoPcOrcamItem->numrows; $iOrcamento++) {

      $oOrcamento = new OrcamentoCompra(db_utils::fieldsMemory($rsOrcamentos, $iOrcamento)->pc20_codorc);
      $aOrcamentos[] = $oOrcamento;
    }
    return $aOrcamentos;
  }

  /**
   * Seta o tipo de processo de compra
   * @param integer $iTipoProcesso
   */
  public function setTipoProcesso($iTipoProcesso) {
    $this->iTipoProcesso = $iTipoProcesso;
  }

  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "sem_transacao_ativa"));
    }


    foreach ($this->getLotes() as $oLote) {
      $oLote->remover();
    }

    foreach ($this->getOrcamentos() as $oOrcamento) {
      $oOrcamento->remover();
    }

    /**
     * Deletamos o todos os vinculos do Processo de compras com as autorizacoes Geradas
     */
    $oDaoEmpautitemPcProc = new cl_empautitempcprocitem();
    $sWhereEmpautitem  = "e73_pcprocitem in (";
    $sWhereEmpautitem .= "                   select distinct pc81_codprocitem ";
    $sWhereEmpautitem .= "                     from pcprocitem ";
    $sWhereEmpautitem .= "                    where pc81_codproc={$this->getCodigo()}";
    $sWhereEmpautitem .= "                   )";
    $oDaoEmpautitemPcProc->excluir(null, $sWhereEmpautitem);

    $oDaoPcProcItem = new cl_pcprocitem();
    $oDaoPcProcItem->excluir(null, "pc81_codproc = {$this->getCodigo()}");

    if ($oDaoPcProcItem->erro_status == "0") {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "erro_excluir_item"));
    }

    $oDaoPcProc = new cl_pcproc();
    $oDaoPcProc->excluir($this->getCodigo());

    if ($oDaoPcProcItem->erro_status == "0") {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "erro_excluir_processo_compra"));
    }

  }

}