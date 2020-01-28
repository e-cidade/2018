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


require_once ("classes/materialestoque.model.php");

/**
 * controle de  Ordem de compra
 * @author Iuri Guntchnigg
 * @version $Revision: 1.75 $
 * @package material
 */
class ordemCompra {

  /**
   * Codigo da ordem de compra
   *
   * @var integer
   */
  public $iCodOrdem = null;

  /**
   * Dao da Ordem de compra (cl_matordem_classe.php)
   *
   * @var object
   */
  private $daoOrdemCompra = null;

  /**
   * Erro no procedimento
   *
   * @var boolean
   */
  public $lSqlErro = false;

  /**
   * Mensagem de erro
   *
   * @var string
   */
  public $sErroMsg = null;

  /**
   * notas fiscas envolvidas
   *
   * @var array
   */
  private $aNotas = array ();

  /**
   * Codifica strings com urlencode
   *
   * @var boolean
   */
  private $lEncode = false;

  /**
   * define se empenhjo da OC � resto a pagar
   *
   * @var booelan
   */
  private $isRestoPagar = false;

  /**
   * construtor
   *
   * @param  integer $iCodOrdem Codigo da ordem de compra
   * @return void
   */
  function __construct($iCodOrdem) {

    $this->setOrdem((int) $iCodOrdem);
    //instanciamos as classes do db_portal referentes a ordem de compra
    $this->usarDao("matordem");
    $this->daoOrdemCompra = new cl_matordem();
  }

  /**
   * Define o codigo da ordem de compra
   *
   * @param integer $iCodOrdem codigo da ordem de compta (matordem.m51_codord)
   */
  function setOrdem($iCodOrdem) {

    (int) $this->iCodOrdem = (int) $iCodOrdem;
  }

  /**
   * Retorna o codigo da ordem de compra
   *
   * @return integer
   */
  function getOrdem() {

    return $this->iCodOrdem;
  }

  /**
   * habilita a codifica��o de strings
   *
   */
  function setEncodeON() {

    $this->lEncode = true;
  }
  /**
   * Cancela a codifica��o de strings
   *
   */
  function setEncodeOff() {

    $this->lEncode = false;
  }

  /**
   * Verifica o tipo do retorno da sstrings
   *
   * @return unknown
   */
  function getEncode() {

    return $this->lEncode;
  }

  /**
   * @desc  m�todo para retornar os dados da ordem. (retorna um objeto db_utils)
   * @return Object db_utils
   *
   */

  function getDados() {

    $sSQLOrdem  = "select * ";
    $sSQLOrdem .= "  from matordem  ";
    $sSQLOrdem .= "      inner join cgm         on  cgm.z01_numcgm          = matordem.m51_numcgm";
    $sSQLOrdem .= "      inner join db_depart   on db_depart.coddepto       = matordem.m51_depto";
    $sSQLOrdem .= "      left  join matordemanu on matordemanu.m53_codordem = matordem.m51_codordem";
    $sSQLOrdem .= " where m51_codordem = " . $this->getOrdem();

    $rsOrdemCompra = $this->daoOrdemCompra->sql_record($sSQLOrdem);
    if ($this->daoOrdemCompra->numrows != 1) {

      $this->sErroMsg = "N�o Foi poss�vel consultar dados da Ordem ({$this->iCodOrdem}). ";
      $this->lSqlErro = true;
      return false;

    } else {
      $this->dadosOrdem = db_utils::fieldsMemory($rsOrdemCompra, 0, false, false, $this->getEncode());
      $this->verificarEmpenho();
      $this->dadosOrdem->isRestoPagar = $this->isRestoPagar;
      return true;
    }
  }

  /**
   * verifica a situacao do empenho no ano (restos a pagar)
   * @return boolean;
   */
  function verificarEmpenho() {

    $sSqlEmpenho  = "select e91_anousu,";
    $sSqlEmpenho .= "       e62_numemp ";
    $sSqlEmpenho .= "  from matordemitem ";
    $sSqlEmpenho .= "        inner join empempitem on e62_numemp = m52_numemp ";
    $sSqlEmpenho .= "                             and e62_sequen = m52_sequen ";
    $sSqlEmpenho .= "        inner join empresto   on e62_numemp = e91_numemp ";
    $sSqlEmpenho .= "                             and e91_anousu = " . db_getsession("DB_anousu");
    $sSqlEmpenho .= "  where m52_codordem = {$this->iCodOrdem}";
    $rsEmpenho    = $this->daoOrdemCompra->sql_record($sSqlEmpenho);
    if ($this->daoOrdemCompra->numrows > 0) {
      $this->isRestoPagar = true;
    }
    return $this->isRestoPagar;
  }

  /**
   * @desc Metodo para retornar as notas da ordem;
   * @param integer [$iCodNota  C�digo da nota]
   * @return mixed
   */

  function getNotasOrdem($iCodNota = null) {

    $this->usarDao("empnota");
    $sWhere = null;
    if ($iCodNota != null) {
      $sWhere = " and e69_codnota = {$iCodNota}";
    }
    /*
     * Verificamos se o empenho � um RP.
     * caso for, o usario podera anular a entrada dessa nota.
     */
    $this->daoEmpNota = new cl_empnota();

    $sCamposNota  = "e69_codnota,e69_anousu,e69_numero,e69_dtnota,e69_dtrecebe,coalesce(e70_valor,0) as e70_valor, ";
    $sCamposNota .= " coalesce(e70_vlrliq,0) as e70_vlrliq ,coalesce(e70_vlranu,0) as e70_vlranu,e60_numemp,";
    $sCamposNota .= "m72_codordem,id_usuario,nome,coalesce(e53_vlrpag,0) as e53_vlrpag";

    $sSqlDadosNota = $this->daoEmpNota->sql_query_nota(null,
                                                       "$sCamposNota",
                                                       "e69_codnota",
                                                       "m72_codordem = {$this->iCodOrdem} {$sWhere}"
                                                      );
    $rsNotasOrdem  = $this->daoEmpNota->sql_record($sSqlDadosNota);

    $this->verificarEmpenho();
    /*
         As notas pode ter as seguintes situacoes (apenas usadas nesse metodo):
         1 = Liquidada - tem algum valor liquidado;
         2 = Anulada   - tem algum valor anulado;
         3 = paga      - tem alguma parte do valro pago;
         4 = normal    - nao possui nenhum valor pago, ou anulado.
         */
    if ($this->daoEmpNota->numrows > 0) {

      for($iNota = 0; $iNota < $this->daoEmpNota->numrows; $iNota ++) {

        $objNotas = db_utils::fieldsMemory($rsNotasOrdem, $iNota, false, false, $this->getEncode());
        for($iFlds = 0; $iFlds < pg_num_fields($rsNotasOrdem); $iFlds ++) {

          $fieldName                                          = pg_field_name($rsNotasOrdem, $iFlds);
          $this->aNotas [$objNotas->e69_codnota] [$fieldName] = $objNotas->$fieldName;

        }
        (int) $iSituacaoNota = 4;
        //verificamos os valores da nota e decidimos a situacao
        if ($objNotas->e53_vlrpag != 0) {
          $iSituacaoNota = 3; //Ha algum valor pago.
        } else if ($objNotas->e70_vlrliq != 0) {
          $iSituacaoNota = 1; // ha algum valor liquidado
        } else if ($objNotas->e70_vlranu != 0) {
          $iSituacaoNota = 2; // ha algum valor anulado
        }
        $this->aNotas [$objNotas->e69_codnota] ["resto"] = $this->isRestoPagar;
        if ($objNotas->e69_anousu < db_getsession("DB_anousu") && $this->isRestoPagar) {
          $iSituacaoNota = 4;
        }
        $this->aNotas [$objNotas->e69_codnota] ["situacao"] = $iSituacaoNota;
      }
      return true;
    } else {
      $this->lSqlErro = true;
      $this->sErroMsg = pg_last_error();
      return false;
    }
  }

  /**
   * @desc Metodo para retornar os itens da ordem;
   * @param integer $iCodNota Codigo da nota;
   * @return mixed
   */

  function getItensOrdemEmEstoque($iCodNota) {

    $this->usarDao("matestoqueitemnota");
    $daoItensEstoque = new cl_matestoqueitemnota();

    $sCampos  = "pc01_descrmater,pc01_servico,pc01_fraciona, e60_anousu,e60_codemp,e60_numemp,m52_codlanc,";
    $sCampos .= "m52_vlruni,m71_valor, m71_codlanc,";
    $sCampos .= "e69_codnota,m71_quant, m60_codmater, m60_descr, m75_quant,m71_quantatend,";
    $sCampos .= "m52_valor,m75_quantmult,m75_codmatunid, m71_codmatestoque,m70_quant,m70_valor";

    $sSqlItens = $daoItensEstoque->sql_query_itensunid(null,
                                                       null,
                                                       $sCampos,
                                                       "m71_codlanc",
                                                       "m74_codempnota={$iCodNota}"
                                                       );

    $rsItensEstoque = $daoItensEstoque->sql_record($sSqlItens);
    if ($daoItensEstoque->numrows > 0) {

      for($iItens = 0; $iItens < $daoItensEstoque->numrows; $iItens ++) {

        $oItens = db_utils::fieldsMemory($rsItensEstoque, $iItens, false, false, $this->getEncode());
        if ($oItens->pc01_servico == "t") {
          $oItens->m70_quant = $oItens->m71_quant;
        }
        $this->aItensNota [] = array (

                        "pc01_descrmater" => $oItens->pc01_descrmater,
                        "pc01_fraciona" => $oItens->pc01_fraciona,
                        "pc01_servico" => $oItens->pc01_servico,
                        "e60_numemp" => $oItens->e60_numemp,
                        "e60_codemp" => $oItens->e60_codemp,
                        "e60_anousu" => $oItens->e60_anousu,
                        "m52_vlruni" => $oItens->m52_vlruni,
                        "m60_codmater" => $oItens->m60_codmater,
                        "m60_descr" => $oItens->m60_descr,
                        "m52_valor" => $oItens->m52_valor,
                        "m52_codlanc" => $oItens->m52_codlanc,
                        "m75_quant" => $oItens->m75_quant,
                        "m71_quant" => $oItens->m71_quant,
                        "m71_valor" => $oItens->m71_valor,
                        "m70_quant" => $oItens->m70_quant,
                        "m70_valor" => $oItens->m70_valor,
                        "m75_quantmult" => $oItens->m75_quantmult,
                        "m71_quantatend" => $oItens->m71_quantatend,
                        "m71_codlanc"    => $oItens->m71_codlanc,
                        "m75_codmatunid" => $oItens->m75_codmatunid,
                        "m71_codmatestoque" => $oItens->m71_codmatestoque
        );
      }
      return true;
    }
  }

  /**
   *  @desc   Metodo para converter consulta de ordens para uma string json;
   *  @param  integer iCodNota   - codigo da nota .
   */
  function ordem2Json($iCodNota) {

    $oJson = new services_json();
    if ($this->getDados()) {

      $sJson ["m51_codordem"] = $this->dadosOrdem->m51_codordem;
      $sJson ["m51_numcgm"]   = $this->dadosOrdem->m51_numcgm;
      $sJson ["z01_nome"]     = $this->dadosOrdem->z01_nome;
      $sJson ["m51_tipo"]     = $this->dadosOrdem->m51_tipo;
      $sJson ["totalItens"]   = 0;
      $sJson ["itens"]        = array (

      );
      if ($this->getNotasOrdem($iCodNota)) {

        $sJson ["e69_codnota"]  = $this->aNotas [$iCodNota] ["e69_codnota"];
        $sJson ["e69_numero"]   = $this->aNotas [$iCodNota] ["e69_numero"];
        $sJson ["e69_dtnota"]   = db_formatar($this->aNotas [$iCodNota] ["e69_dtnota"], "d");
        $sJson ["e69_dtrecebe"] = db_formatar($this->aNotas [$iCodNota] ["e69_dtrecebe"], "d");
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["e70_valor"]    = $this->aNotas [$iCodNota] ["e70_valor"];
        $sJson ["id_usuario"]   = $this->aNotas [$iCodNota] ["id_usuario"];
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["nome"]         = $this->aNotas [$iCodNota] ["nome"];
        if ($this->getItensOrdemEmEstoque($iCodNota)) {

          $sJson ["totalItens"] = count($this->aItensNota);
          $sJson ["itens"]      = $this->aItensNota;
        }
      }
    }
    if (! $this->lSqlErro) {

      $sJson ["status"]   = 1;
      $sJson ["mensagem"] = null;
    } else {

      $sJson ["status"]   = 2;
      $sJson ["mensagem"] = "Erro: " . urlencode($this->sErroMsg);
    }
    $jsonEncoded = $oJson->encode($sJson);
    return $jsonEncoded;
  }

  /**
   * M�todo respons�vel por anular a entrada de uma ordem de compra no almoxarifado
   * @param integer  iCodNota - codigo da nota a ser anulada
   *
   */
  public function anularEntradaNota($iCodNota) {

    $this->sErroMsg = '';
    $this->usarDao("matestoque");
    $this->usarDao("matestoqueitem");
    $this->usarDao("matestoqueitemunid");
    $this->usarDao("matestoqueitemnota");
    $this->usarDao("matestoqueitemoc");
    $this->usarDao("matestoqueini");
    $this->usarDao("matestoqueinil");
    $this->usarDao("matestoqueinill");
    $this->usarDao("matestoqueinimei");

    $clmatestoque         = new cl_matestoque();
    $clmatestoqueini      = new cl_matestoqueini();
    $clmatestoqueinil     = new cl_matestoqueinil();
    $clmatestoqueinill    = new cl_matestoqueinill();
    $clmatestoqueinimei   = new cl_matestoqueinimei();
    $clmatestoqueitem     = new cl_matestoqueitem();
    $clmatestoqueitemoc   = new cl_matestoqueitemoc();
    $clmatestoqueitemnota = new cl_matestoqueitemnota();
    $clmatestoqueitemunid = new cl_matestoqueitemunid();

    (float) $nTotalNota = 0;

    try {

      db_inicio_transacao();

      //traz os dados da nota.
      $this->getItensOrdemEmEstoque($iCodNota);
      $this->getDados();
      if ($this->getBensAtivoNota($iCodNota) != false) {
        throw new BusinessException("H� bens referentes � nota de empenho ativos no patrim�nio. Favor verificar.");
      }

      $clmatestoqueini->m80_data     = date('Y-m-d', db_getsession("DB_datausu"));
      $clmatestoqueini->m80_hora     = date('H:i:s');
      $clmatestoqueini->m80_coddepto = db_getsession("DB_coddepto");
      $clmatestoqueini->m80_login    = db_getsession("DB_id_usuario");
      $clmatestoqueini->m80_codtipo  = 19;
      $clmatestoqueini->m80_obs      = "Anula��o da entrada de material.";
      $clmatestoqueini->incluir(null);
      if ($clmatestoqueini->erro_status == 0) {
        throw new Exception($clmatestoqueini->erro_msg);
      }

      $aItensVerificar   = array();
      $iCodigoIni        = $clmatestoqueini->m80_codigo;
      $iCodigoItem       = null;
      $nSaldoItemEstoque = 0;
      $nSaldoAtendido    = 0;
      $aEmpenhoGrupoItem = array();
      $iTotalItensNota   = count($this->aItensNota);

      for($iItens = 0; $iItens < $iTotalItensNota; $iItens ++) {

        $oItemAtivo = $this->aItensNota[$iItens];
        if ($oItemAtivo["pc01_servico"] == "t" && $oItemAtivo["m71_quant"] == $oItemAtivo["m71_quantatend"]) {


          $oMaterial         = new materialEstoque($oItemAtivo["m60_codmater"]);
          $sSqlMatestoqueini = $clmatestoqueini->sql_query_mater(null,
                                                                 "*",
                                                                 null,
                                                                 "    m82_matestoqueitem={$oItemAtivo["m71_codlanc"]}
                                                                  and matestoqueini.m80_codtipo=20
                                                                  and (b.m80_codtipo<>6
                                                                   or b.m80_codigo is null) ");

          $rsSaldoItens = $clmatestoqueinimei->sql_record($sSqlMatestoqueini);
          if ($clmatestoqueinimei->numrows  > 0) {

            $oSaidaMaterial = db_utils::fieldsMemory($rsSaldoItens, 0);
            try {
               $oMaterial->cancelarSaidaMaterial($oItemAtivo["m71_quant"],
                                                $oSaidaMaterial->m82_codigo, 'Anula��o de sa�da de servi�o autom�tico');
              $oItemAtivo["m71_quantatend"] = 0;
            } catch (Exception $eErro)  {
              throw new Exception($eErro->getMessage());
            }
          }
        }

        if ($iCodigoItem     != $oItemAtivo["m60_codmater"]) {

          $aItensVerificar[$oItemAtivo["m60_codmater"]]->iCodigoItem       = $oItemAtivo["m60_codmater"];
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoItemEstoque = $oItemAtivo["m71_quant"];
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoAtendido    = $oItemAtivo["m71_quantatend"];
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoABater      = 0;
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->iNumemp           = $oItemAtivo["e60_numemp"];
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nValorTotal       = $oItemAtivo["m70_valor"];

        } else {

          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoItemEstoque += $oItemAtivo["m71_quant"];
          $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoAtendido    += $oItemAtivo["m71_quantatend"];

        }
        $m71_codlanc       = $oItemAtivo["m71_codlanc"];
        $nQtdItens         = $oItemAtivo["m71_quant"];
        $nValorItem        = $oItemAtivo["m71_valor"];
        $m75_quantmult     = $oItemAtivo["m75_quantmult"];
        $m75_codmatunid    = $oItemAtivo["m75_codmatunid"];
        $m71_codmatestoque = $oItemAtivo["m71_codmatestoque"];
        $nTotalNota        += $oItemAtivo["m71_valor"];
        $sSqlIni           =  $clmatestoqueinimei->sql_query(null,
                                                             "m82_matestoqueini",
                                                             null,
                                                             "m82_matestoqueitem={$m71_codlanc}
                                                             and m80_codtipo=12");
        $result_iniant = $clmatestoqueinimei->sql_record($sSqlIni);
        if ($clmatestoqueinimei->numrows > 0) {
          //como ja existe item no estoque para essa ordem, lan�amos na tabelas abaixo o codigo do matestoqueini.
          $oIniAnt = db_utils::fieldsMemory($result_iniant, 0); //codigo anterior do item no estoque;

          $clmatestoqueinil->m86_matestoqueini = $oIniAnt->m82_matestoqueini;
          $clmatestoqueinil->incluir(null);
          if ($clmatestoqueinil->erro_status == 0) {
            throw new Exception($clmatestoqueinil->erro_msg);
          }

          $iCodInil = $clmatestoqueinil->m86_codigo; //
          $clmatestoqueinill->m87_matestoqueini = $iCodigoIni;
          $clmatestoqueinill->incluir($iCodInil);
          if ($clmatestoqueinill->erro_status == 0) {
            throw new Exception($clmatestoqueinill->erro_msg);
          }

        }

        //Iniciamos lancamentos de estorno dos itens,
        $clmatestoqueitem->m71_quantatend = $oItemAtivo["m71_quant"];
        $clmatestoqueitem->m71_codlanc    = $m71_codlanc;
        $clmatestoqueitem->alterar($m71_codlanc);
        $aItensVerificar[$oItemAtivo["m60_codmater"]]->nSaldoABater += ($oItemAtivo["m71_quant"]
                                                                        - $oItemAtivo["m71_quantatend"]);
        if ($clmatestoqueitem->erro_status == 0) {
          throw new Exception($clmatestoqueitem->erro_msg);
        }

        //Unidades do item
        $clmatestoqueitemunid->m75_codmatestoqueitem = $m71_codlanc;
        $clmatestoqueitemunid->m75_quantmult         = "$m75_quantmult";
        $clmatestoqueitemunid->m75_quant             = "$nQtdItens";
        $clmatestoqueitemunid->m75_codmatunid        = $m75_codmatunid;
        $clmatestoqueitemunid->alterar($m71_codlanc);
        if ($clmatestoqueitemunid->erro_status == 0) {
          throw new Exception($clmatestoqueitemunid->erro_msg);
        }

        //tabela de rela��o entre matestoqueini e matestoqueitem
        $clmatestoqueinimei->m82_matestoqueini  = $clmatestoqueini->m80_codigo;
        $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
        $clmatestoqueinimei->m82_quant          = "$nQtdItens";
        $clmatestoqueinimei->incluir(null);
        if ($clmatestoqueinimei->erro_status == 0) {
          throw new Exception($clmatestoqueinimei->erro_msg);
        }

        $iCodigoMatEstoqueIniMei = $clmatestoqueinimei->m82_codigo;

        //excluimos o controle da entrada da ordem; (devemos criar uma situacao para esse registroi em vez de excluir)
        $rsOc = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query_OC_Nota(null, null, "m73_codmatestoqueitem,m73_codmatordemitem", null, "m52_codordem={$this->iCodOrdem} and m74_codempnota = {$iCodNota}"));
        //echo pg_last_error();
        $iNumRows = $clmatestoqueitemoc->numrows;
        for($iTot = 0; $iTot < $iNumRows; $iTot ++) {

          $oItemOC                                   = db_utils::fieldsMemory($rsOc, $iTot);
          $clmatestoqueitemoc->m73_codmatordemitem   = $oItemOC->m73_codmatordemitem;
          $clmatestoqueitemoc->m73_codmatestoqueitem = $oItemOC->m73_codmatestoqueitem;
          $clmatestoqueitemoc->m73_cancelado         = "true";
          $clmatestoqueitemoc->alterar($oItemOC->m73_codmatestoqueitem, $oItemOC->m73_codmatordemitem);
          if ($clmatestoqueitemoc->erro_status == 0) {
            throw new Exception($clmatestoqueitemoc->erro_msg);
          }
        }


        $clmatestoqueitemnota->excluir(null, null, "m74_codempnota=$iCodNota");
        if ($clmatestoqueitemnota->erro_status == 0) {
          throw new Exception($clmatestoqueitemnota->erro_msg);
        }

        $iCodigoItem = $oItemAtivo["m60_codmater"];

        if (USE_PCASP) {

          $oEmpenhoFinanceiro      = new EmpenhoFinanceiro($oItemAtivo['e60_numemp']);
          $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();
          $iCodigoDesdobramento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
          $oGrupoContaOrcamento    = GrupoContaOrcamento::getGrupoConta($iCodigoDesdobramento, db_getsession("DB_anousu"));
          $iGrupoContaOrcamento = "";
          if ($oGrupoContaOrcamento) {
            $iGrupoContaOrcamento  = $oGrupoContaOrcamento->getCodigo();
          }

          if (in_array($iGrupoContaOrcamento, array(7, 8, 10)) &&
              !$oEmpenhoFinanceiro->isRestoAPagar(db_getsession("DB_anousu"))) {

            $oDaoMatEstoqueIniMeiPM = db_utils::getDao('matestoqueinimeipm');
            $sWherePrecoMedio       = "m89_matestoqueinimei = {$iCodigoMatEstoqueIniMei}";
            $sSqlBuscaPrecoMedio    = $oDaoMatEstoqueIniMeiPM->sql_query_file(null,
                                                                              "m89_valorfinanceiro",
                                                                              null,
                                                                              $sWherePrecoMedio);
            $rsBuscaPrecoMedio      = $oDaoMatEstoqueIniMeiPM->sql_record($sSqlBuscaPrecoMedio);

            if ($oDaoMatEstoqueIniMeiPM->numrows == 0) {
              throw new BusinessException("N�o foi localizado o pre�o m�dio para o material.");
            }

            $nPrecoMedio                  = db_utils::fieldsMemory($rsBuscaPrecoMedio, 0)->m89_valorfinanceiro;

            // Transformo o antigo array em objeto para facilitar o tratamento dos dados
            $oItemAtivo                   = db_utils::postMemory($oItemAtivo);
            $oMaterialEstoque             = new materialEstoque($iCodigoItem);
            $oGrupoMaterial               = $oMaterialEstoque->getGrupo();
            $oItemAtivo->iCodigoDaNota    = $iCodNota;
            $oItemAtivo->m52_valor        = $nPrecoMedio;
            $oItemAtivo->nValorLancamento = $nPrecoMedio;
            if ($oItemAtivo->m71_quantatend == 0) {
              $oItemAtivo->nValorLancamento = $oItemAtivo->m71_valor;
            }

            /**
             * adicionamos em um array agrupado por empenho e de acordo com o grupo a qual o material pertence
             */
            $aEmpenhoGrupoItem[$oItemAtivo->e60_numemp][$oGrupoMaterial->getCodigo()][] = $oItemAtivo;
          }
        }
      }
      //caso a ordem de compra seje normal, lan�amos o valor da nota como anulado.
      $this->verificarEmpenho();
      if ($this->dadosOrdem->m51_tipo == 1) {

        $clempnotaele = $this->usarDao("empnotaele", true);
        $clempnotaele->e70_codnota = $iCodNota;
        $clempnotaele->e70_vlranu = $nTotalNota;
        $clempnotaele->alterar($iCodNota);
        if ($clempnotaele->erro_status == 0) {
          throw new Exception($clempnotaele->erro_msg);
        }
      }

      /**
       * Acerta os saldos dos itens
       */
      foreach ($aItensVerificar as $oItemVerificar) {

        if ($oItemVerificar->nSaldoItemEstoque > $oItemVerificar->nSaldoAtendido) {

          $nDiferenca   = $oItemVerificar->nSaldoItemEstoque - $oItemVerificar->nSaldoABater;
          //$oMaterial   = new materialEstoque($this->aItensNota [$iItens] ["m60_codmater"]);
          $sSql          = "select * ";
          $sSql         .= " from matestoqueitem ";
          $sSql         .= "      inner join matestoque on m71_codmatestoque = m70_codigo ";
          $sSql         .= " where m70_coddepto    = ".db_getsession("DB_coddepto");
          $sSql         .= "   and m70_codmatmater = {$oItemVerificar->iCodigoItem}";
          $sSql         .= "   and m71_quant > m71_quantatend";
          $rsSaldoItens  = db_query($sSql);

          $aItensSaldo   = db_utils::getColectionByRecord($rsSaldoItens);
          $nValorRateio  = $nDiferenca;
          foreach ($aItensSaldo as $oItem) {

            if ($nValorRateio > 0) {

              $nSaldoItem    = $oItem->m71_quant - $oItem->m71_quantatend;

              if ($nValorRateio > $nSaldoItem) {

                $nValorAbater  = $nSaldoItem;
                $nValorRateio -= $nSaldoItem;

              } else {

                $nValorAbater = $nValorRateio;
                $nValorRateio = 0;
              }
              $clmatestoqueitem->m71_quantatend = $oItem->m71_quantatend+$nValorAbater;
              $clmatestoqueitem->m71_codlanc    = $oItem->m71_codlanc;
              $clmatestoqueitem->alterar($oItem->m71_codlanc);
              if ($clmatestoqueitem->erro_status == 0) {

                 throw new Exception($clmatestoqueitem->erro_msg);

              }

              $clmatestoqueinimei->m82_matestoqueini   = $clmatestoqueini->m80_codigo;
              $clmatestoqueinimei->m82_matestoqueitem  = $oItem->m71_codlanc;
              $clmatestoqueinimei->m82_quant           = $nValorAbater;
              $clmatestoqueinimei->incluir(null);
              if ($clmatestoqueinimei->erro_status == 0) {
                throw new Exception($clmatestoqueinimei->erro_msg);
              }

            }
          }

          if (round($nValorRateio,2) > 0) {
             throw new BusinessException("Nao foi possivel cancelar ordem.\nSem Saldo no estoque $nValorRateio");
          }

        }
      }

      $dtAtual          = date("Y-m-d", db_getsession( 'DB_datausu' ));
      $oDataImplantacao = new DBDate($dtAtual);
      $oInstituicao     = new Instituicao(db_getsession('DB_instit'));
      
      if (USE_PCASP && count($aEmpenhoGrupoItem) > 0  && (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataImplantacao, $oInstituicao)) ) {
        $this->processarLancamentosOrdemCompra($aEmpenhoGrupoItem, true);
      }

      db_fim_transacao(false);

    } catch (BusinessException $eErro) {

      db_fim_transacao(true);
      throw new BusinessException($eErro->getMessage());

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      throw new Exception($eErro->getMessage());

    }

  }


  /**
   * @desc Metodo para retornar o saldo dos itens da ordem de compra.
   * @return   mixed ;
   */
  function getItensSaldo() {

    $sSQlSaldo = "select riseqitem     as e62_sequen,";
    $sSQlSaldo .= "       rinumemp     as e62_numemp,";
    $sSQlSaldo .= "       ricodemp     as e60_codemp,";
    $sSQlSaldo .= "       ricodmater   as e62_item,";
    $sSQlSaldo .= "       ricoditem    as e62_sequencial,";
    $sSQlSaldo .= "       rnvaloruni   as e62_vlun,";
    $sSQlSaldo .= "       rsdescr      as pc01_descrmater,";
    $sSQlSaldo .= "       rsdescremp   as e62_descr,";
    $sSQlSaldo .= "       rnsaldoordem as saldoitens,";
    $sSQlSaldo .= "       rnvalorordem as saldovalor,";
    $sSQlSaldo .= "       rianoemp     as e60_anousu,";
    $sSQlSaldo .= "       m52_quant,";
    $sSQlSaldo .= "       pc01_servico,";
    $sSQlSaldo .= "       pc01_fraciona,";
    $sSQlSaldo .= "       m52_valor,";
    $sSQlSaldo .= "       m52_vlruni,";
    $sSQlSaldo .= "       m52_codlanc,";
    $sSQlSaldo .= "       rlcontrolaquantidade as lcontrolaquantidade";
    $sSQlSaldo .= "  from fc_saldoitensordem({$this->iCodOrdem})";
    $sSQlSaldo .= "       inner join  matordemitem on ricoditemordem = m52_codlanc";
    $sSQlSaldo .= "       inner join  pcmater on ricodmater          = pc01_codmater";
    $sSQlSaldo .= " where m52_codordem = {$this->iCodOrdem}";
    $sSQlSaldo .= " order by rinumemp, riseqitem";
    $rsSaldo = $this->daoOrdemCompra->sql_record($sSQlSaldo);
    if ($rsSaldo) {
      //criamos uma colection com os objetos dos itens da ordem de compra (saldos)
      if ($this->daoOrdemCompra->numrows > 0) {

        for($iLinha = 0; $iLinha < $this->daoOrdemCompra->numrows; $iLinha ++) {
          $this->aItensOrdem [] = db_utils::fieldsMemory($rsSaldo, $iLinha, false, false, $this->getEncode());
        }
      }
      $this->dadosOrdem->itens = $this->aItensOrdem;
      return true;
    } else {
      return false;
    }
  }

  /**
   * @desc Metodo para anular itens da ordem de compra.
   * @param  array $aItens array de itens que devem ser anulados - {[CodItemOrdem, CodItemEmp, Qtdem ,Valor]}
   * @param  integer $lSolicitaAnulEmpenho se deve gerar uma solicitacao de anulacao de empenho - 0 = nao solicita,
   *                                                                                            1 = Anulacao de Item
   *                                                                                            2 = Anulacao de valores
   * @return   void;
   */
  function anularOrdem($aItens, $sMotivo = '', $iSolicitaAnulEmpenho = 0) {

    if (! is_array($aItens)) {

      $this->lSqlErro = true;
      $this->sErroMsg = "Erro [1]: Parametro aItens n�o e um array valido!\nContate Suporte";
      return false;
    }
    //carregamos as daos necessarias
    $this->usarDao("matordemanul");
    $this->usarDao("matordemitemanu");
    $this->usarDao("empsolicitaanul");
    $this->usarDao("empsolicitaanulitem");
    $this->lSqlErro = false;
    $this->sErroMsg = null;
    $iNumEmpAnt = null;
    $clmatordemanul = new cl_matordemanul();
    $clmatordemitemanu = new cl_matordemitemanu();
    $clempsolicitaanul = new cl_empsolicitaanul();
    $clempsolicitaanulitem = new cl_empsolicitaanulitem();
    //incluimos a anula��o da ordem na tablea matordemanul
    //percorremos os itens do array
    db_inicio_transacao();
    $clmatordemanul->m37_hora = db_hora();
    $clmatordemanul->m37_data = date("Y-m-d", db_getsession("DB_datausu"));
    $clmatordemanul->m37_usuario = db_getsession("DB_id_usuario");
    $clmatordemanul->m37_motivo = $sMotivo;
    $clmatordemanul->m37_empanul = "$iSolicitaAnulEmpenho";
    $clmatordemanul->m37_tipo = 2; //anulacao parcial;
    $clmatordemanul->incluir(null);
    if ($clmatordemanul->erro_status == 0) {

      $this->lSqlErro = true;
      $this->sErroMsg = "Erro[2]: \n{$clmatordemanul->erro_msg}";
    }

    //foi solicitado a anulacao do empenho.incluimos a requisicao na tabela empsolicitaanul.
    if (! $this->lSqlErro) {

      foreach ( $aItens as $itensAnulados ) {

        $clmatordemitemanu->m36_matordemanul = $clmatordemanul->m37_sequencial;
        $clmatordemitemanu->m36_matordemitem = $itensAnulados->iCodItemOrdem;
        $clmatordemitemanu->m36_vrlanu = $itensAnulados->nVlrAnu;
        $clmatordemitemanu->m36_qtd = $itensAnulados->nQtdeAnu;
        $clmatordemitemanu->incluir(null);
        if ($clmatordemitemanu->erro_status == 0) {

          $this->lSqlErro = true;
          $this->sErroMsg = "Erro [4]:\n N�o foi poss�vel Anular item ({$itensAnulados->iCodItemOrdem})";
          $this->sErroMsg .= "\nErro Sistema:{$clmatordemitemanu->erro_msg}";
          return false;
        }
        //caso tenha foi solicitado a anulacao do empenho, lancamos mas seguintes tabelas
        if ($iSolicitaAnulEmpenho != 0 && ! $this->lSqlErro) {

          if ($iNumEmpAnt != $itensAnulados->iNumEmp) {

            $clempsolicitaanul->e35_numemp = $itensAnulados->iNumEmp;
            $clempsolicitaanul->e35_usuario = db_getsession("DB_id_usuario");
            $clempsolicitaanul->e35_hora = db_hora();
            $clempsolicitaanul->e35_data = date("Y-m-d", db_getSession("DB_datausu"));
            $clempsolicitaanul->e35_tipo = $iSolicitaAnulEmpenho;
            $clempsolicitaanul->e35_situacao = 1; //1-Solicitada 2 -Realizada 3 - Cancelada
            $clempsolicitaanul->incluir(null);
            if ($clempsolicitaanul->erro_status == 0) {

              $this->lSqlErro = true;
              $this->sErroMsg = "Erro [5]:\n N�o foi poss�vel Anular item ({$itensAnulados->iCodItemOrdem})";
              $this->sErroMsg .= "\nErro Sistema:{$clempsolicitaanul->erro_msg}";
              return false;
            }
          }
          $clempsolicitaanulitem->e36_empempitem = $itensAnulados->iCodItem;
          $clempsolicitaanulitem->e36_empsolicitaanul = $clempsolicitaanul->e35_sequencial;
          $clempsolicitaanulitem->e36_vrlanu = $itensAnulados->nVlrAnu;
          $clempsolicitaanulitem->e36_qtdanu = $itensAnulados->nQtdeAnu;
          $clempsolicitaanulitem->incluir(null);
          if ($clempsolicitaanulitem->erro_status == 0) {

            $this->lSqlErro = true;
            $this->sErroMsg = "Erro [6]:\n N�o foi poss�vel Anular item ({$itensAnulados->iCodItemOrdem})";
            $this->sErroMsg .= "\nErro Sistema:{$clempsolicitaanulitem->erro_msg}";
            return false;
          }
        }
        $iNumEmpAnt = $itensAnulados->iNumEmp;
      }
    }
    db_fim_transacao($this->lSqlErro);
  }

  /**
   * @desc Metodo para carregar o arquivo de defini��o da classe requerida;
   * @param  string sClasse - nome da classe a ser carregada
   */
  function usarDao($sClasse, $rInstance = false) {

    if (! class_exists("cl_{$sClasse}")) {
      require_once "classes/db_{$sClasse}_classe.php";
    }

    if ($rInstance) {

      eval("\$objRet = new cl_{$sClasse};");
      return $objRet;
    }
  }

  /**
   * Retorna informa��es da ordem de compra para dar entrada no estoque
   *
   * @return object
   */
  public function getInfoEntrada() {

    if ( ! $this->getDados()) {
      throw new Exception("N�o foi poss�vel Encontrar dados da ordem ({$this->iCodOrdem}).");
    }

      /*
       * trazemos items da ordem, com seus saldos,
       * e acrescentamos informa��es sobre o item, com suas
       * liga��es com o item do almoxarifado.
       */
    if ( ! $this->getItensSaldo()) {
      throw new Exception("N�o foi poss�vel Encontrar itens da ordem ({$this->iCodOrdem}).");
    }

    if ($this->dadosOrdem->m51_tipo == 2) {

      $oDaoEmpNota = db_utils::getDao("empnota");
      $rsNota      = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null,
                                                                           "empnota.*,
                                                                            e70_valor",
                                                                            null,
                                                                            "m72_codordem = {$this->dadosOrdem->m51_codordem}"));
      $oEmpNota = db_utils::fieldsMemory($rsNota, 0,false,false, $this->getEncode());
      $this->dadosOrdem->e69_dtnota   = db_formatar($oEmpNota->e69_dtnota,"d");
      $this->dadosOrdem->e69_dtrecebe = db_formatar($oEmpNota->e69_dtrecebe,"d");
      $this->dadosOrdem->e69_numero   = $oEmpNota->e69_numero;
      $this->dadosOrdem->e70_valor    = $oEmpNota->e70_valor;

    }
    $oDaoSolicitem  = db_utils::getDao("solicitem");
    $oDaoTransMater = db_utils::getDao("transmater");
    $iTotItens = count($this->aItensOrdem);
    for ($iInd = 0; $iInd < $iTotItens; $iInd++) {

      $this->dadosOrdem->itens[$iInd]->unidade        = 1;
      $this->dadosOrdem->itens[$iInd]->quantunidade   = 1;
      $this->dadosOrdem->itens[$iInd]->iIndiceEntrada = 0;
      /*
       * Buscamos os dados da solicita��o para ver se o item possui informa��es
       * de unidade cadastradas.
       */
      $rsSolicitem = $oDaoSolicitem->sql_record($oDaoSolicitem->sql_query_solunid(
                               null, "pc17_quant,pc17_unid", null, 'e62_sequencial = '.$this->dadosOrdem->itens[$iInd]->e62_sequencial));

      if ($oDaoSolicitem->numrows == 1) {

         $this->dadosOrdem->itens[$iInd]->unidade      = db_utils::fieldsMemory($rsSolicitem,0)->pc17_unid;
         $this->dadosOrdem->itens[$iInd]->quantunidade = db_utils::fieldsMemory($rsSolicitem,0)->pc17_quant;
      }

      /*
       * Buscamos todos os itens vinculados ao material do compras
       * no cadastro de materias do almoxarifados, para o usu�rio escolher um .
       */
      $this->dadosOrdem->itens[$iInd]->matmater = array();
      $this->dadosOrdem->itens[$iInd]->matmater[0]->m63_codmatmater = "";
      $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr       = "";
      //echo $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr;

      $sSqlTransMater = $oDaoTransMater->sql_query(null,
                                                   "m63_codmatmater,m60_descr,m60_controlavalidade",
                                                    null,
                                                    "m60_ativo is true
                                                    and m63_codpcmater={$this->dadosOrdem->itens[$iInd]->e62_item}");
      $rsTransMater = $oDaoTransMater->sql_record($sSqlTransMater);
      if ($oDaoTransMater->numrows > 0) {

        unset($this->dadosOrdem->itens[$iInd]->matmater);
        for($iItens = 0; $iItens < $oDaoTransMater->numrows; $iItens++) {
          $this->dadosOrdem->itens[$iInd]->matmater[] = db_utils::fieldsMemory($rsTransMater, $iItens,false,false,$this->getEncode());
        }
        $oDaoTransMater->numrows = 0;
      }

      $oMaterial->m63_codmatmater   = $this->dadosOrdem->itens[$iInd]->matmater[0]->m63_codmatmater;
      $oMaterial->m60_descr         = $this->dadosOrdem->itens[$iInd]->matmater[0]->m60_descr;
      $oMaterial->pc01_descrmater   = $this->dadosOrdem->itens[$iInd]->pc01_descrmater;
      $oMaterial->pc01_codmater     = $this->dadosOrdem->itens[$iInd]->e62_item;
      $oMaterial->e62_descr         = $this->dadosOrdem->itens[$iInd]->e62_descr;
      $oMaterial->e62_vlun          = $this->dadosOrdem->itens[$iInd]->e62_vlun;
      $oMaterial->e62_sequencial    = $this->dadosOrdem->itens[$iInd]->e62_sequencial;
      $oMaterial->e60_codemp        = $this->dadosOrdem->itens[$iInd]->e60_codemp;
      $oMaterial->e60_numemp        = $this->dadosOrdem->itens[$iInd]->e62_numemp;
      $oMaterial->e60_anousu        = $this->dadosOrdem->itens[$iInd]->e60_anousu;
      $oMaterial->unidade           = $this->dadosOrdem->itens[$iInd]->unidade;
      $oMaterial->quantunidade      = $this->dadosOrdem->itens[$iInd]->quantunidade;
      $oMaterial->m52_quant         = $this->dadosOrdem->itens[$iInd]->saldoitens;
      $oMaterial->m52_valor         = $this->dadosOrdem->itens[$iInd]->saldovalor;
      $oMaterial->m52_vlruni        = $this->dadosOrdem->itens[$iInd]->m52_vlruni;
      $oMaterial->m52_codlanc       = $this->dadosOrdem->itens[$iInd]->m52_codlanc;
      $oMaterial->m77_lote          = "";
      $oMaterial->pc01_servico      = $this->dadosOrdem->itens[$iInd]->pc01_servico;
      $oMaterial->m77_dtvalidade    = "";
      $oMaterial->m78_matfabricante = "";
      $oMaterial->m76_nome          = "";
      $oMaterial->checked           = "checked";
      $oMaterial->saldoitens        = $this->dadosOrdem->itens[$iInd]->saldoitens;
      $oMaterial->saldovalor        = $this->dadosOrdem->itens[$iInd]->saldovalor;
      $oMaterial->fraciona          = false; //se o o item � fracionado.
      $oMaterial->iTotalFracionados = 0;//Total de itens Fracionados
      $oMaterial->iIndiceEntrada    = 0;
      $oMaterial->cc08_sequencial   = "";
      $oMaterial->cc08_descricao    = "";
      $this->saveMaterial($this->dadosOrdem->itens[$iInd]->m52_codlanc,$oMaterial);
      unset ($oMaterial);

    }
    return true;
  }
  /**
   * Inicializa a sess�o para a ordem
   *
   */
  public function initSession() {

    if (!isset($_SESSION["matordem{$this->iCodOrdem}"])) {

      $_SESSION["matordem{$this->iCodOrdem}"]= array();
    }
    return $_SESSION["matordem{$this->iCodOrdem}"];
  }

  /**
   * Salva as modifica��es da entrada na sessao
   *
   * @param integer $iCodLanc codigo do item da ordem de compra
   * @param  object $oMaterial objeto com informa��es da entrada
   * @return unknown
   */
  public function saveMaterial($iCodLanc, $oMaterial) {

    $oOrdemSession = $this->initSession();
    if (!isset($oOrdemSession[$iCodLanc])) {

      $oOrdemSession[$iCodLanc] = array();
    }

    //verificamos se o material do estoque ja foi incluido.
    foreach ($oOrdemSession[$iCodLanc] as $oLancamento) {

      if ($oMaterial->iIndiceEntrada != $oLancamento->iIndiceEntrada) {
        if ($oLancamento->m63_codmatmater ==  $oMaterial->m63_codmatmater
           && $oLancamento->m77_lote == $oMaterial->m77_lote) {
          throw  new Exception("Material/lote j� cadastrado.");
        }
      }
    }

    $oMaterial->pc01_descrmater = urlencode(urldecode($oMaterial->pc01_descrmater));
    $oMaterial->e62_descr       = urlencode(urldecode($oMaterial->e62_descr));
    $oMaterial->m76_nome        = urlencode(urldecode($oMaterial->m76_nome));
    $oMaterial->m60_descr       = urlencode(urldecode($oMaterial->m60_descr));
    if ($oMaterial->iIndiceEntrada != "") {


      $oOrdemSession[$iCodLanc][$oMaterial->iIndiceEntrada] = $oMaterial;

    } else {

      if ($oMaterial->fraciona) {

        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldoitens -= $oMaterial->quantidadeDebitar;
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldovalor -= $oMaterial->valorDebitar;
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->checked = " checked ";
        if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_quant >
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldoitens ) {
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_quant -= $oMaterial->quantidadeDebitar;
        }

        if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_valor >
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldovalor ) {
            $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_valor -= $oMaterial->valorDebitar;
        }
        $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->iTotalFracionados++;
        $oMaterial->iIndiceEntrada = $this->nextVal($iCodLanc);

      }

      $oMaterial->pc01_descrmater = urlencode(urldecode($oMaterial->pc01_descrmater));
      $oOrdemSession[$iCodLanc][] = $oMaterial;
    }
    $_SESSION["matordem{$this->iCodOrdem}"] = $oOrdemSession;
    return true;
  }

  /**
   * Destroi a sessao atual para a ordem de compra;
   *
   * @return boolean
   */
  public function destroySession() {

    if (isset($_SESSION["matordem{$this->iCodOrdem}"])) {
      unset ($_SESSION["matordem{$this->iCodOrdem}"]);
    }
    return true;
  }

  /**
   * retorna a lista dos itens incluidos no estoque. conforme rateio realizado pelo usuario;
   *
   * @return unknown
   */
  public function getDadosEntrada () {

    if (isset($_SESSION["matordem{$this->iCodOrdem}"])) {

      $aItensCadastrados = array();
      foreach ($_SESSION["matordem{$this->iCodOrdem}"] as $oItemOrdem) {

        foreach ($oItemOrdem as $iCodLanc => $oItemLancado) {
           $aItensCadastrados[] = $oItemLancado;
        }
      }
    }
    return $aItensCadastrados;

  }

  /**
   * funcao estatica para retornar se o item servico pode ser controlado
   * por quantidade
   * @param integer $iSequencial (e62_sequencial)
   * @return string
   */
  public static function getServicoQuantidade($iSequencial) {

    $oDaoEmpEmpItem = db_utils::getDao("empempitem");

    $sSqlEmpEmpItem = $oDaoEmpEmpItem->sql_query_file(null, null,
                                                     "e62_servicoquantidade",
                                                      null,
                                                      "e62_sequencial = {$iSequencial}"
                                                     );
    $rsServicoQuantidade = $oDaoEmpEmpItem->sql_record($sSqlEmpEmpItem);
    if ($oDaoEmpEmpItem->numrows <= 0) {

      throw new Exception("ERRO [ 1 ] - erro ao pesquisar se o item pode ser controlado por quantidade.");
    }

    $sServicoQuantidade = db_utils::fieldsMemory($rsServicoQuantidade, 0)->e62_servicoquantidade;

    return $sServicoQuantidade;

  }

  /**
   * Retorna as informa��es sobre item escolhido
   *
   * @param integer $iCodLanc C�digo do item da ordem de compra;
   * @param integer $iIndice indice do fracionamento do item. todos os itens tem ao minimo  fracionamento
   * @return object
   */
  function getInfoItem($iCodLanc,  $iIndice) {

    $oItemAtivo = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice];



    $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice]->checked = " checked ";
    $oDaoTransMater = db_utils::getDao("transmater");
    $aItensMaterial = array();
    $sSqlTransMater = $oDaoTransMater->sql_query(null,
                                                 "m63_codmatmater,m60_descr,m60_controlavalidade",
                                                  null,
                                                  "m60_ativo is true
                                                   and m63_codpcmater={$oItemAtivo->pc01_codmater}");
    $rsTransMater = $oDaoTransMater->sql_record($sSqlTransMater);
    if ($oDaoTransMater->numrows > 0) {

      for($iItens = 0; $iItens < $oDaoTransMater->numrows; $iItens++) {
         $aItensMaterial[] = db_utils::fieldsMemory($rsTransMater, $iItens,false,false,$this->getEncode());
      }
    }

    $iSequencialEmpEmpItem = $oItemAtivo->e62_sequencial;

    $oItemAtivo->aMateriaisEstoque = $aItensMaterial;
    $oItemAtivo->sServicoQuantidade = ordemCompra::getServicoQuantidade($iSequencialEmpEmpItem);
    return $oItemAtivo;
  }
  /**
   * Cancela o fracionamento do Item passado;
   *
   * @param integer $iCodLanc c�digo do Lan�amento do item
   * @param integer $iIndice indice do item
   * @return boolean
   */
  function cancelarFracionamento($iCodLanc, $iIndice) {

    $oItemAtivo = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice];
    $oItemPai   = $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][0];
    $oItemPai->saldoitens += $oItemAtivo->m52_quant;
    $oItemPai->saldovalor += $oItemAtivo->m52_valor;
    $_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][0] = $oItemPai;
    unset($oItemAtivo);
    unset($_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc][$iIndice]);
    return true;
  }

  function nextVal($iCodLanc) {

    $iIndiceNovo = 0;
    foreach ($_SESSION["matordem{$this->iCodOrdem}"][$iCodLanc] as $iIndice => $aItens) {

      if ($iIndice > $iIndiceNovo) {
        $iIndiceNovo = $iIndice;
      }

    }
    return $iIndiceNovo+1;
  }

  function confirmaEntrada($iNumNota, $dtDataNota, $dtDataRecebe, $nValorNota, $aItens, $oInfoNota = null, $sObservacao) {

    //Devemos estar dentro de uma transa��o.
    if (!db_utils::inTransaction()) {
      throw new Exception("N�o existe uma transa��o ativa.\nProcedimento Cancelado");
    }

    if (!is_array($aItens)) {
      throw new Exception("Parametro aItens deve ser um Array.\nProcedimento Cancelado");
    }

    $aElementosConfiguradosVerificacaoPatrimonio = array();
    if (!USE_PCASP) {

      $oDaoConfiguracaoDesdobramentoPatrimonio = db_utils::getDao('configuracaodesdobramentopatrimonio');

      $sWhere             = "o56_anousu = ".db_getsession("DB_anousu");
      $sSqlDesdobramentos = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_query(null, "o56_codele", null, $sWhere);
      $rsDesdobramentos   = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_record($sSqlDesdobramentos);
      if ($rsDesdobramentos && $oDaoConfiguracaoDesdobramentoPatrimonio->numrows > 0) {

        $aDesdobramentos = db_Utils::getCollectionByRecord($rsDesdobramentos);
        foreach ($aDesdobramentos as $oDesdobramento) {
          $aElementosConfiguradosVerificacaoPatrimonio[] = $oDesdobramento->o56_codele;
        }
      }
    }
    $aParamKeys = array(db_getsession("DB_anousu"));

    $aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
    $iTipoControleCustos = 0;

    if (count($aParametrosCustos) > 0) {
      $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
    }
    //primeiro, descobrimos a quantidade de empenhos que a ordem de compra possui.
    $aEmpenhos = array();
    $iTotItens = count($aItens);
    $aEntradas = $_SESSION["matordem{$this->iCodOrdem}"];
    /**
     * valor total da entrada.
     */
    $nTotalEntrada = 0;
    for ($iEmp = 0; $iEmp < $iTotItens; $iEmp++) {

      if ($aEntradas[$aItens[$iEmp]->iCodLanc][$aItens[$iEmp]->iIndiceEntrada] ) {

        //Pegamos o item ativo, pelo codigo do lan�amento e do seu indice.
        $oItemAtivo = $aEntradas[$aItens[$iEmp]->iCodLanc][$aItens[$iEmp]->iIndiceEntrada];
        if ($oItemAtivo->iTotalFracionados > 0) {
          continue;
        }
        //Agrupamos o valor da entrada por empenho, e definos o codigo na nota fiscal.
        if (!isset($aEmpenhos[$oItemAtivo->e60_numemp])) {

          $aEmpenhos[$oItemAtivo->e60_numemp]["valor"]    = $oItemAtivo->m52_valor;
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = '';
        } else {

          $aEmpenhos[$oItemAtivo->e60_numemp]["valor"]    += $oItemAtivo->m52_valor;
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]  = '';
        }
        $nTotalEntrada += $oItemAtivo->m52_valor;
      }
    }

    if (round($nTotalEntrada,2) != round($nValorNota,2)) {
      throw new Exception("o Valor total da Entrada($nTotalEntrada) da nota diferente do valor da nota ($nValorNota).");
    }

    $this->getDados();


    /**
     * Verificamos se existe controloe do pit, e incluimos as informa��es extras das notas;
     */
    $iControlaPit = 2;
    $aParamKeys   = array(
                         db_getsession("DB_instit")
                        );

    $aParametrosPit = db_stdClass::getParametro("matparaminstit",$aParamKeys);
    if (count($aParametrosPit) > 0) {
      $iControlaPit = $aParametrosPit[0]->m10_controlapit;
    }

    if($this->dadosOrdem->z01_incest == '' && ($iControlaPit == 1 && $oInfoNota->iTipoDocumentoFiscal == 50) ) {
      $sMsg  = "O cgm (".urldecode($this->dadosOrdem->z01_numcgm)." - ".urldecode($this->dadosOrdem->z01_nome).") ";
      $sMsg .= "n�o possui inscri��o estadual cadastrada. para continuar essa rotina, informe a ";
      $sMsg .= "inscri��o estadual do fornecedor";
      throw new Exception($sMsg);
    }

    $iDepto                          = $this->dadosOrdem->m51_depto;
    $oDaoMatestoqueIni               = db_utils::getDao("matestoqueini");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d',db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = date('H:i:s');
    $oDaoMatestoqueIni->m80_coddepto = $iDepto;
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_codtipo  = 12;
    $oDaoMatestoqueIni->m80_obs      = $sObservacao;
    $oDaoMatestoqueIni->incluir(null);
    if ($oDaoMatestoqueIni->erro_status == 0) {

      $sErroMsg  = "Erro [1] - N�o foi possivel Iniciar Movimento no Estoque.\n";
      $sErroMsg .= "[Erro T�cnico] - {$oDaoMatestoqueIni->erro_msg}";
      throw new Exception($sErroMsg);
      return false;
    }
    $iCodMov = $oDaoMatestoqueIni->m80_codigo;

    $clEmpEmpenho = db_utils::getDao('empempenho');

    $aEmpenhoGrupoItem = array();
    for ($iItem = 0; $iItem < $iTotItens; $iItem++) {

      if ($aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada] ) {

        /**
         * Pegamos o item ativo, pelo codigo do lan�amento e do seu indice.
         */
        $oItemAtivo = $aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada];


        /**
         * Verifica se a data da nota � inferior a do empenho
         * caso seja ent�o retorna erro
         */
        $sSqlValidaEmp   = $clEmpEmpenho->sql_query_file($oItemAtivo->e60_numemp,"e60_emiss");
        $rsValidaDataEmp = $clEmpEmpenho->sql_record($sSqlValidaEmp);

        if ( pg_num_rows($rsValidaDataEmp) > 0 ) {
          $oDataEmpenho = db_utils::fieldsMemory($rsValidaDataEmp,0);
          if ( implode("-",array_reverse(explode("/",$dtDataNota))) < $oDataEmpenho->e60_emiss ) {
            throw new Exception("Data da nota inferior a data do empenho!");
          }
        }

        /**
         * Verificamos o tipo do controle do custos. caso seje obrigatorio parcustos.cc09_tipocontrole = 3
         * e o material for servi�o, e nao foi informado o custo, devemos cancelar a entrada da ordem
         */
        if ($oItemAtivo->cc08_sequencial == "" && $iTipoControleCustos == 3 && $oItemAtivo->pc01_servico == "t") {

          $sErroMsg  = "Erro [5] - Item ({$oItemAtivo->pc01_descrmater}) sem centro de custo .\n";
          $sErroMsg .= "Opera��o Cancelada.";
          throw new Exception($sErroMsg);
        }
        /**
         * Verificamos se o usu�rio escolheu um item de entrada para o item.
         * caso nao, devemos incluir o �tem , com a descri��o do m�dulo material.
         */
        if ($oItemAtivo->m63_codmatmater == "") {

          $oDaoMatMater                       = db_utils::getDao("matmater");
          $oDaoMatMater->m60_codmatunid       = 1;
          $oDaoMatMater->m60_quantent         = 1;
          $oDaoMatMater->m60_descr            = urldecode($oItemAtivo->pc01_descrmater);
          $oDaoMatMater->m60_controlavalidade = 3;
          $oDaoMatMater->m60_ativo            = "t";
          $oDaoMatMater->m60_codant           = "";
          $oDaoMatMater->incluir(null);
          if ($oDaoMatMater->erro_status == 0) {

            $sErroMsg  = "Erro [6] - Item ({$oItemAtivo->pc01_descrmater}) nao possui item de Entrada.\n";
            $sErroMsg .= "Opera��o Cancelada.";
            throw new Exception($sErroMsg);
            return false;
          }
          $oItemAtivo->m63_codmatmater = $oDaoMatMater->m60_codmater;

          $oDaoMatUnid                  = db_utils::getDao("matmaterunisai");
          $oDaoMatUnid->m62_codmatmater = $oDaoMatMater->m60_codmater;
          $oDaoMatUnid->m62_codmatunid  = 1;
          $oDaoMatUnid->incluir($oDaoMatMater->m60_codmater, 1);
          if ($oDaoMatUnid->erro_status == 0) {

            $sErroMsg  = "Erro [7] - Item ({$oItemAtivo->pc01_descrmater}) unidade de saida.\n";
            $sErroMsg .= "Opera��o Cancelada.";
            throw new Exception($sErroMsg);
            return false;
          }
          $oDaoTransMater = db_utils::getDao("transmater");
          $oDaoTransMater->m63_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoTransMater->m63_codpcmater  = $oItemAtivo->pc01_codmater;
          $oDaoTransMater->incluir();
          if ($oDaoTransMater->erro_status == 0) {

            $sErroMsg  = "Erro [13] - Item ({$oItemAtivo->pc01_descrmater}) N�o foi poss�vel incluir material.\n";
            $sErroMsg .= "Opera��o Cancelada.";
            throw new Exception($sErroMsg);
            return false;
          }
        }

        /*
         * Caso o item for tiver quantidade fracionado maior que 0,
         * e o valor do mesmo for 0;
         * igonoramos o item na inclusao;
         */
         if ($oItemAtivo->iTotalFracionados > 0) {
           continue;
         } else if ($oItemAtivo->iTotalFracionados == 0 && $oItemAtivo->m52_valor == 0) {
           throw new Exception("Item ({$oItemAtivo->pc01_descrmater} com valores inv�lidos.\Verifique)");
         }
        /*
         * verificamos se a ordem n�o � uma ordem automatica, caso verdadeiro,
         * devemos criar uma nota para o empenho.
         * marcamos o elemento iCodNota do array aEmpenhos com o codigo da nota,
         * e passamos a  usar essa nota para todas as entradas desse empenho.
         */

        if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == ""
            && $this->dadosOrdem->m51_tipo == 1) {

          $oDaoEmpNota                           = db_utils::getDao("empnota");
          $oDaoEmpNota->e69_anousu               = db_getsession("DB_anousu");
          $oDaoEmpNota->e69_dtnota               = implode("-", array_reverse(explode("/", $dtDataNota)));
          $oDaoEmpNota->e69_dtrecebe             = implode("-", array_reverse(explode("/", $dtDataRecebe)));
          $oDaoEmpNota->e69_id_usuario           = db_getsession("DB_id_usuario");
          $oDaoEmpNota->e69_numemp               = $oItemAtivo->e60_numemp;
          $oDaoEmpNota->e69_numero               = $iNumNota;
          $oDaoEmpNota->e69_dtservidor           = date('Y-m-d');
          $oDaoEmpNota->e69_dtinclusao           = date('Y-m-d',db_getsession("DB_datausu"));
          $oDaoEmpNota->e69_tipodocumentosfiscal = $oInfoNota->iTipoDocumentoFiscal;
          $oDaoEmpNota->incluir(null);
          if ($oDaoEmpNota->erro_status == 0 ) {

            $sErroMsg  = "Erro [2] - N�o foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro T�cnico] - {$oDaoEmpNota->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
          }
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = $oDaoEmpNota->e69_codnota;
          //incluimos o elemento da nota;
          $oDaoElemento = db_utils::getDao("empelemento");

          $sSqlElemento = $oDaoElemento->sql_query($oItemAtivo->e60_numemp);
          $rsElemento   = $oDaoElemento->sql_record($sSqlElemento);
          if ($oDaoElemento->numrows == 1) {
            $oElemento = db_utils::fieldsMemory($rsElemento, 0);
          } else {
            throw new Exception("Erro[3] - Empenho sem elementos, ou com mais de um elemento.Procedimento cancelado");
          }

          $aEmpenhos[$oItemAtivo->e60_numemp]['elemento']        =  $oElemento->o56_elemento;
          $aEmpenhos[$oItemAtivo->e60_numemp]['codigo_elemento'] =  $oElemento->o56_codele;

          $oDaoEmpNotaEle              = db_utils::getDao("empnotaele");
          $oDaoEmpNotaEle->e70_codele  = $oElemento->e64_codele;
          $oDaoEmpNotaEle->e70_codnota = $oDaoEmpNota->e69_codnota;
          $oDaoEmpNotaEle->e70_valor   = round($aEmpenhos[$oItemAtivo->e60_numemp]["valor"],2);
          $oDaoEmpNotaEle->e70_vlrliq  = "0";
          $oDaoEmpNotaEle->e70_vlranu  = "0";
          $oDaoEmpNotaEle->incluir($oDaoEmpNota->e69_codnota, $oElemento->e64_codele);
          if ($oDaoEmpNotaEle->erro_status == 0) {

            $sErroMsg  = "Erro [4] - N�o foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro T�cnico] - {$oDaoEmpNotaEle->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
          }

          $oDaoEmpNotaOrd = db_utils::getDao("empnotaord");
          $oDaoEmpNotaOrd->incluir($oDaoEmpNota->e69_codnota, $this->dadosOrdem->m51_codordem);
        } else if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == ""
                   && $this->dadosOrdem->m51_tipo == 2) {

          /**
           * a nota � virtual, entao apenas pegamos o n�mero da nota gerada .
           */
          $oDaoEmpNota = db_utils::getDao("empnota");
          $rsNota      = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null,
                                                                               "e69_codnota, e70_codele",
                                                                                null,
                                                                                "m72_codordem = {$this->dadosOrdem->m51_codordem}"));
          if ($oDaoEmpNota->numrows == 1) {

            $oNotas   = db_utils::fieldsMemory($rsNota,0);
            $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]        = $oNotas->e69_codnota;
            $aEmpenhos[$oItemAtivo->e60_numemp]['codigo_elemento'] =  $oNotas->e70_codele;
          } else {

            throw new Exception("Erro[5] - Ordem de Compra autom�tica sem Nota fiscal. Procedimento cancelado");
            return false;
          }
        }


        /**
         * Validadamos o departamento da OC com o departamento que  usu�rio est� logado.
         * Anteriormente caso o material fosse servi�o, apenas davamos entrada no mesmo depto da OC.
         * Anteriormente caso fosse material normal, o depto que o usuario est� logado(DB_coddepto )
         *               deveria ser igual ao depto da OC;
         *
         * Agora: se o departamento da ordem for diferente do departamento logado,
         *        jogamos uma exce��o e cancelamos a entrada da OC
         */
        if (db_getsession("DB_coddepto") != $this->dadosOrdem->m51_depto) {
          throw new Exception("Erro [5] - Ordem de compra deve ser lan�ada no seu dep�sito de destino.\nOpera��o Cancelada");
        }

        /**
         * Verificamos se o item escolhido j� possui estoque (matestoque)
         * cadastrado no departamento da ordem de compra.
         * caso nao exista, fazemos o cadastro
         */
        $oDaoMatestoque = db_utils::getDao("matestoque");
        $sSqlMaterialEstoque = $oDaoMatestoque->sql_query_file(null,
                                                               "*",
                                                               null,
                                                               "m70_codmatmater  = {$oItemAtivo->m63_codmatmater}
                                                                and m70_coddepto = {$iDepto}");

        $rsMatestoque   = $oDaoMatestoque->sql_record($sSqlMaterialEstoque);
        if ($oDaoMatestoque->numrows == 0) {

          $oDaoMatestoque->m70_coddepto    = $iDepto;
          $oDaoMatestoque->m70_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoMatestoque->m70_valor       = "0";
          $oDaoMatestoque->m70_quant       = "0";
          $oDaoMatestoque->incluir(null);
          $iCodEstoque = $oDaoMatestoque->m70_codigo;
          if ($oDaoMatestoque->erro_status == 0) {

            $sErroMsg  = "Erro [7] - N�o foi poss�vel iniciar estoque.\n";
            $sErroMsg .= "[Erro T�cnico] - {$oDaoMatestoque->erro_msg}";
            throw new Exception($sErroMsg);
          }
        } else {
          $iCodEstoque = db_utils::fieldsMemory($rsMatestoque, 0)->m70_codigo;
        }

        $iQuantUnidade = $oItemAtivo->quantunidade;
        if ($oItemAtivo->quantunidade <= 0) {

        	$iQuantUnidade = 1;
        }
        //incluimos na matestoqueitem

        $oDaoMatestoqueItem = db_utils::getDao("matestoqueitem");
        $oDaoMatestoqueItem->m71_codmatestoque = $iCodEstoque;
        $oDaoMatestoqueItem->m71_data          = implode("-", array_reverse(explode("/", $dtDataRecebe)));
        $oDaoMatestoqueItem->m71_quant         = $oItemAtivo->m52_quant * $iQuantUnidade;
        $oDaoMatestoqueItem->m71_quantatend    = "0";
        $oDaoMatestoqueItem->m71_valor         = $oItemAtivo->m52_valor;
        $oDaoMatestoqueItem->m71_servico       = $oItemAtivo->pc01_servico == "t" ? "true" : "false";
        $oDaoMatestoqueItem->incluir(null);
        if ($oDaoMatestoqueItem->erro_status == 0){

          $sErroMsg  = "Erro [8] - N�o foi poss�vel iniciar estoque.\n";
          $sErroMsg .= "[Erro T�cnico] - {$oDaoMatestoqueItem->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
        }

        //incluimos matestoqueitemunid
        $oDaoMatUnid                        = db_utils::getDao("matestoqueitemunid");
        $oDaoMatUnid->m75_codmatestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
        $oDaoMatUnid->m75_codmatunid        = $oItemAtivo->unidade;
        //$oDaoMatUnid->m75_quant             = $oItemAtivo->m52_quant;
        $oDaoMatUnid->m75_quant             = $oItemAtivo->m52_quant * $iQuantUnidade;

        $oDaoMatUnid->m75_quantmult         = $iQuantUnidade;
        $oDaoMatUnid->incluir($oDaoMatestoqueItem->m71_codlanc);
        if ($oDaoMatUnid->erro_status==0){

          $sErroMsg  = "Erro [8]- N�o foi poss�vel iniciar estoque.\n";
          $sErroMsg .= "[Erro T�cnico] - {$oDaoMatUnid->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
        }
        /**
         * incluimos a liga��o da entrada da ordem de compra com o item do estoque
         */
        $oDaoMatItemOC = db_utils::getDao("matestoqueitemoc");
        $oDaoMatItemOC->incluir($oDaoMatestoqueItem->m71_codlanc, $oItemAtivo->m52_codlanc);
        if ($oDaoMatItemOC->erro_status == 0) {

          $sErroMsg  = "Erro [9]- N�o foi poss�vel iniciar estoque.\n";
          $sErroMsg .= "[Erro T�cnico] - {$oDaoMatItemOC->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
        }
        /**
         * Verificamos se foi definido uma apropriacao para o item da OC.
         * Como foi realizado, devemos anular essa apropria��o
         */
        $oDaoMatordemItemCustoCriterio = db_utils::getdao("matordemitemcustocriterio");
        $sSqlApropria  = $oDaoMatordemItemCustoCriterio->sql_query_file(null,"cc11_sequencial",
                                                                        "cc11_matordemitem = {$oItemAtivo->m52_codlanc}");
        $rsApropria    = $oDaoMatordemItemCustoCriterio->sql_record($sSqlApropria);

        /*
         * Caso o usu�rio informou o fabricante do material,
         * gravamos na matestoqueitemfabricante.
         */
        if (trim($oItemAtivo->m78_matfabricante) != '') {

          $oDaoMatFabricante = db_utils::getDao("matestoqueitemfabric");
          $oDaoMatFabricante->m78_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatFabricante->m78_matfabricante  = $oItemAtivo->m78_matfabricante;
          $oDaoMatFabricante->incluir(null);
          if ($oDaoMatFabricante->erro_status == 0) {

            $sErroMsg  = "Erro [17] - N�o foi poss�vel Salvar informa��es do fabricante.\n";
            $sErroMsg .= "[Erro T�cnico] - {$oDaoMatFabricante->erro_msg}";
            throw new Exception($sErroMsg);
            return false;

          }
        }
        /*
         * Gravamos matestoqueinimei
         */
        $oDaoMatestoqueIniMei = db_utils::getDao("matestoqueinimei");
        $oDaoMatestoqueIniMei->m82_matestoqueini  = $iCodMov;
        $oDaoMatestoqueIniMei->m82_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
        $oDaoMatestoqueIniMei->m82_quant          = ($oItemAtivo->m52_quant * $iQuantUnidade);
        $oDaoMatestoqueIniMei->incluir(null);
        if ($oDaoMatestoqueIniMei->erro_status == 0) {

          $sErroMsg  = "Erro [11] - N�o foi poss�vel finalizar a aaainclusao da Ordem.\n";
          $sErroMsg .= "[Erro T�cnico] - ".str_replace("\\n", "\n", $oDaoMatestoqueIniMei->erro_msg);
          throw new Exception($sErroMsg);
          return false;
        }
        /**
         * Caso o material seje servico, j� fizemos a saida automatica para esse material
         */
        if ($oItemAtivo->pc01_servico == "t") {

          $oMaterialEstoque = new materialEstoque($oItemAtivo->m63_codmatmater);
          if ($oItemAtivo->cc08_sequencial != "") {
            $oMaterialEstoque->setCriterioRateioCusto($oItemAtivo->cc08_sequencial);
          }
          $oMaterialEstoque->setCodDepto($this->dadosOrdem->m51_depto);
          $oMaterialEstoque->saidaMaterial($oItemAtivo->m52_quant*$iQuantUnidade,null, true);
        }
        /**
         * incluimos ligacao do itemn com a nota fiscal
         */
        $oDaoMatItemNota  = db_utils::getDao("matestoqueitemnota");
        $oDaoMatItemNota->incluir($oDaoMatestoqueItem->m71_codlanc, $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]);
        if ($oDaoMatItemNota->erro_status == 0) {

          $sErroMsg  = "Erro [10] - N�o foi poss�vel iniciar estoque.\n";
          $sErroMsg .= "[Erro T�cnico] - {$oDaoMatItemNota->erro_msg}";
          throw new Exception($sErroMsg);
          return false;

        }
        /**
         * caso o usu�rio deu informa��es sobre o lote, salvamos na tabela matestoqueitemlote
         */
        if (trim($oItemAtivo->m77_lote) != "") {

          $oDaoMatestoqueItemLote = db_utils::getDao("matestoqueitemlote");
          $oDaoMatestoqueItemLote->m77_dtvalidade     =  implode("-", array_reverse(explode("/", $oItemAtivo->m77_dtvalidade)));
          $oDaoMatestoqueItemLote->m77_lote           = $oItemAtivo->m77_lote;
          $oDaoMatestoqueItemLote->m77_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatestoqueItemLote->incluir(null);
          if ($oDaoMatestoqueItemLote->erro_status == 0) {

            $sErroMsg  = "Erro [13]- N�o foi poss�vel Salvar informa��es do lote.\n";
            $sErroMsg .= "[Erro T�cnico] - {$oDaoMatestoqueItemLote->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
          }
        }
      }


      if (USE_PCASP) {

        $oEmpenhoFinanceiro      = new EmpenhoFinanceiro($oItemAtivo->e60_numemp);
        $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();

        $iCodigoContaElemento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
        $oGrupoContaOrcamento    = GrupoContaOrcamento::getGrupoConta($iCodigoContaElemento, db_getsession("DB_anousu"));
        if ($oGrupoContaOrcamento && !$oEmpenhoFinanceiro->isRestoAPagar(db_getsession("DB_anousu"))) {

          $iGrupoContaOrcamento = $oGrupoContaOrcamento->getCodigo();
          if (in_array($iGrupoContaOrcamento, array(7, 8, 10))) {

            $oMaterialEstoque             = new materialEstoque($oItemAtivo->m63_codmatmater);
            $oGrupoMaterial               = $oMaterialEstoque->getGrupo();
            $oItemAtivo->iCodigoDaNota    = $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"];
            $oItemAtivo->nValorLancamento = $oItemAtivo->m52_valor;

            /**
             * adicionamos em um array agrupado por empenho e de acordo com o grupo a qual o material pertence
             */
            $aEmpenhoGrupoItem[$oItemAtivo->e60_numemp][$oGrupoMaterial->getCodigo()][] = $oItemAtivo;
          }
        }
      }
    }

    $dtAtual      = date("Y-m-d", db_getsession( 'DB_datausu' ));
    $oDataAtual   = new DBDate($dtAtual);
    $oInstituicao = new Instituicao(db_getsession('DB_instit'));

    if (USE_PCASP && count($aEmpenhoGrupoItem) > 0 &&  (ParametroIntegracaoPatrimonial::possuiIntegracaoMaterial($oDataAtual, $oInstituicao)) ) {
      $this->processarLancamentosOrdemCompra($aEmpenhoGrupoItem);
    }

    /**
     * Verificamos se existe controloe do pit, e incluimos as informa��es extras das notas;
     */
     $iControlaPit = 2;
     $aParamKeys = array(
                         db_getsession("DB_instit")
                        );
     $aParametrosPit   = db_stdClass::getParametro("matparaminstit",$aParamKeys);
     if (count($aParametrosPit) > 0) {
       $iControlaPit = $aParametrosPit[0]->m10_controlapit;
     }
    /**
     * Incluimos os itens de cada nota, conforme a entrada dos mesmos no estoque
     */
    if ($iControlaPit == 1) {

      if ($oInfoNota->iTipoDocumentoFiscal == "") {

        $sErroMsg  = "Erro [14]- Tipo de documento fiscal n�o informado.\n";
        throw new Exception($sErroMsg);
        return false;

      }

      if ($oInfoNota->iTipoDocumentoFiscal == 50) {

        if ($oInfoNota->iCfop == "") {

          $sErroMsg  = "Erro [15] - CFOP n�o informada.\n";
          throw new Exception($sErroMsg);
        }

        $oDaoEmpnotaDadosPit                                = db_utils::getDao("empnotadadospit");
        $oDaoEmpnotaDadosPit->e11_cfop                      = $oInfoNota->iCfop;
        $oDaoEmpnotaDadosPit->e11_seriefiscal               = $oInfoNota->sSerieFiscal;
        $oDaoEmpnotaDadosPit->e11_inscricaosubstitutofiscal = $oInfoNota->iInscrSubstituto;
        $oDaoEmpnotaDadosPit->e11_basecalculoicms           = "$oInfoNota->nBaseCalculoICMS";
        $oDaoEmpnotaDadosPit->e11_valoricms                 = "$oInfoNota->nValorICMS";
        $oDaoEmpnotaDadosPit->e11_basecalculosubstitutotrib = "$oInfoNota->nBaseCalculoSubst";
        $oDaoEmpnotaDadosPit->e11_valoricmssubstitutotrib   = "$oInfoNota->nValorICMSSubst";
        $oDaoEmpnotaDadosPit->incluir(null);
        if ($oDaoEmpnotaDadosPit->erro_status == 0) {

           $sErroMsg  = "Erro [16] - N�o foi poss�vel Salvar informa��es da nota Fiscal.\n";
           $sErroMsg .= "[Erro T�cnico] - {$oDaoEmpnotaDadosPit->erro_msg}";
           throw new Exception($sErroMsg);
           return false;
        }
      }
    }

    if ($this->dadosOrdem->m51_tipo == 1) {

      foreach ($aEmpenhos as $iEmpenho => $oNota) {

        $iCodNota      = $oNota["iCodNota"];
        $clempnotaitem = db_utils::getDao("empnotaitem");
        $sSQlItens     = "SELECT sum(m71_quant) as m71_quant,";
        $sSQlItens    .= "       sum(m71_valor) as m71_valor,";
        $sSQlItens    .= "       e62_sequencial, ";
        $sSQlItens    .= "       e62_codele, ";
        $sSQlItens    .= "       array_to_string(array_accum(m71_codlanc), '#') as m71_codlanc";
        $sSQlItens    .= "  from matestoqueitem ";
        $sSQlItens    .= "            inner join matestoqueitemnota on m71_codlanc = m74_codmatestoqueitem";
        $sSQlItens    .= "            inner join matestoqueitemoc   on m71_codlanc = m73_codmatestoqueitem";
        $sSQlItens    .= "            inner join matordemitem       on m52_codlanc = m73_codmatordemitem";
        $sSQlItens    .= "            inner join empempitem         on m52_numemp  = e62_numemp";
        $sSQlItens    .= "                                         and m52_sequen  = e62_sequen";
        $sSQlItens    .= "  where m74_codempnota = {$iCodNota}";
        $sSQlItens    .= "  group by  e62_sequencial,e62_codele";
        $rsItens       = db_query($sSQlItens);
        if (! $rsItens ) {

          $sErroMsg  = "Erro[14] - Erro ao buscar os itens da entrada no estoque. \n";
          $sErroMsg .= "[Erro T�cnico] - ".pg_last_error();
          throw new Exception($sErroMsg);
        }

        for ($iInd = 0; $iInd < pg_num_rows($rsItens); $iInd++) {

          $oItens = db_utils::fieldsMemory($rsItens, $iInd);

          $clempnotaitem->e72_codnota    = $iCodNota;
          $clempnotaitem->e72_empempitem = $oItens->e62_sequencial;
          $clempnotaitem->e72_qtd        = $oItens->m71_quant*$iQuantUnidade;
          $clempnotaitem->e72_valor      = $oItens->m71_valor;
          $clempnotaitem->incluir(null);
          if ($clempnotaitem->erro_status == 0) {

            $sErroMsg  = "Erro[12] - N�o foi poss�vel incluir itens da nota.\n";
            $sErroMsg .= "[Erro T�cnico] - {$clempnotaitem->erro_msg}";
            throw new Exception($sErroMsg);
            break;
          }
          $oGrupo = GrupoContaOrcamento::getGrupoConta($oNota['codigo_elemento'], db_getsession("DB_anousu"));

          if ((!USE_PCASP  && in_array($oNota['codigo_elemento'], $aElementosConfiguradosVerificacaoPatrimonio))
              || (USE_PCASP && $oGrupo instanceof GrupoContaOrcamento && $oGrupo->getCodigo() == 9)) {

            $aLancamentos = explode("#", $oItens->m71_codlanc);

            for ($i = 0; $i < count($aLancamentos); $i++) {

              $oDaoBensPendente                      = db_utils::getDao('empnotaitembenspendente');
              $oDaoBensPendente->e137_sequencial     = null;
              $oDaoBensPendente->e137_empnotaitem    = $clempnotaitem->e72_sequencial;
              $oDaoBensPendente->e137_matestoqueitem = $aLancamentos[$i];
              $oDaoBensPendente->incluir(null);
              if ($oDaoBensPendente->erro_status == 0) {

                $sErroMsg  = "Erro[13] - N�o foi poss�vel incluir v�nculo do empenho com o patrim�nio.\n";
                $sErroMsg .= "[Erro T�cnico] - {$oDaoBensPendente->erro_msg}";
                throw new Exception($sErroMsg);
                break;
              }
            }
          }
        }
      }
      /**
       * Vinculamos as notas ao empnotadadospit
       */
      if ($oInfoNota->iTipoDocumentoFiscal == 50) {

        $oDaoEmpnotaDadosPitNota                      = db_utils::getDao("empnotadadospitnotas");
        $oDaoEmpnotaDadosPitNota->e13_empnota         = $oNota["iCodNota"];
        $oDaoEmpnotaDadosPitNota->e13_empnotadadospit = $oDaoEmpnotaDadosPit->e11_sequencial;
        $oDaoEmpnotaDadosPitNota->incluir(null);
        if ($oDaoEmpnotaDadosPitNota->erro_status == 0) {

          $sErroMsg  = "Erro[17] - N�o foi poss�vel incluir itens da nota.\n";
          $sErroMsg .= "[Erro T�cnico] - {$oDaoEmpnotaDadosPitNota->erro_msg}";
          throw new Exception($sErroMsg);
          break;
        }
      }
    }
    $this->destroySession();
    return true;
  }

  /**
   * Funcao para pesquisar os desdobramentos
   *
   * @param integer $iEstrutural
   * @return $aItens
   */

  public function getDesdobramentosLiberados($iEstrutural) {

    $iAnoUso         = db_getsession("DB_anousu");
    $oDaoOrcElemento = db_utils::getDao("orcelemento");
    $aItens          = array();

    $sCampos  = " desdobramentosliberadosordemcompra.pc33_sequencial,                                                ";
    $sCampos .= " orcelemento.o56_codele,                                                                            ";
    $sCampos .= " orcelemento.o56_elemento,                                                                          ";
    $sCampos .= " orcelemento.o56_descr                                                                              ";

    $sWhere  = " o56_anousu = {$iAnoUso}";
    if (!empty($iEstrutural)) {
      $sWhere .= " and o56_elemento like '{$iEstrutural}%'";
    }

    $sSqlOrcElemento  = $oDaoOrcElemento->sql_query_desdobramento_liberados(null, null,$sCampos,null,$sWhere);
    $rsSqlOrcElemento = $oDaoOrcElemento->sql_record($sSqlOrcElemento);
    $aItens           = db_utils::getColectionByRecord($rsSqlOrcElemento, true, false, true);

    return $aItens;
  }

  /**
   * Funcao para liberar os desdobramentos
   *
   * @param  array $aDesdobramentos lista desdobramentos
   * @return ordemCompra
   */
  public function liberarDesdobramentos($aDesdobramentos) {

    $oDaoDesdobramentoLiberado = db_utils::getDao("desdobramentosliberadosordemcompra");
    $iAnoUsu                   = db_getsession('DB_anousu');
    if (!db_utils::inTransaction()) {
      throw new Exception('Nao existe transa��o com o banco de dados ativa.');
    }

    foreach ($aDesdobramentos as $oDesdobramento) {

      $sCampos = "desdobramentosliberadosordemcompra.*";
      $sWhere  = "pc33_codele = {$oDesdobramento->iNumele}";

      $sSqlDesdobramentosLiberados  = $oDaoDesdobramentoLiberado->sql_query( null,$sCampos,null, $sWhere);
      $rsSqlDesdobramentosLiberados = $oDaoDesdobramentoLiberado->sql_record($sSqlDesdobramentosLiberados);
      if ($oDesdobramento->lLiberar) {

        if ($oDaoDesdobramentoLiberado->numrows == 0) {

          $oDaoDesdobramentoLiberado->pc33_codele = $oDesdobramento->iNumele;
          $oDaoDesdobramentoLiberado->pc33_anousu = $iAnoUsu;
          $oDaoDesdobramentoLiberado->incluir(null);
          if ( $oDaoDesdobramentoLiberado->erro_status == 0 ){
            throw new Exception($oDaoDesdobramentoLiberado->erro_msg);
          }

        }

      } else {
        if ($oDaoDesdobramentoLiberado->numrows > 0) {

          $oDesdobramentoLiberados                    = db_utils::fieldsMemory($rsSqlDesdobramentosLiberados, 0);
          $oDaoDesdobramentoLiberado->pc33_sequencial = $oDesdobramentoLiberados->pc33_sequencial;
          $oDaoDesdobramentoLiberado->excluir($oDaoDesdobramentoLiberado->pc33_sequencial);
          if ( $oDaoDesdobramentoLiberado->erro_status == 0 ){
            throw new Exception($oDaoDesdobramentoLiberado->erro_msg);
          }
        }

      }

    }
    return $this;
  }

  /**
   * Verifica se h� bens ativos para a nota de empenho informada no par�metro
   * @param integer $iCodigoNota C�digo da nota que deve ser usada na pesquisa de bens ativos por nota
   * @return mixed
   */
  public function getBensAtivoNota($iCodigoNota) {

    $oDaoBensEmpNotaItem     = db_utils::getDao('bensempnotaitem');
    $sCamposBuscaItensAtivos = "*";
    $sWhereBuscaItensAtivos  = "     empnotaitem.e72_codnota  = {$iCodigoNota} ";
    $sWhereBuscaItensAtivos .= " and bensbaix.t55_codbem is null ";
    $sSqlBuscaItensAtivos    = $oDaoBensEmpNotaItem->sql_query_bens_ativos(null, $sCamposBuscaItensAtivos,
                                                                           null, $sWhereBuscaItensAtivos);
    $rsBuscaItensAtivos      = $oDaoBensEmpNotaItem->sql_record($sSqlBuscaItensAtivos);
    $aItensAtivos            = db_utils::getCollectionByRecord($rsBuscaItensAtivos);
    $iLinhas                 = $oDaoBensEmpNotaItem->numrows;
    $mRetorno                = array();
    for($i = 0; $i < $iLinhas; $i++) {

      $oItemAtivo                     = db_utils::fieldsMemory($rsBuscaItensAtivos, $i);
      $oDadosItemAtivo                = new stdClass();
      $oDadosItemAtivo->iCodigoBem    = $oItemAtivo->t52_bem;
      $oDadosItemAtivo->sDescricaoBem = $oItemAtivo->t52_descr;
      $oDadosItemAtivo->sPlaca        = $oItemAtivo->t41_placa;
      $oDadosItemAtivo->iPlacaSeq     = $oItemAtivo->t41_placaseq;
      $oDadosItemAtivo->sEmpenho      = $oItemAtivo->e60_codemp;
      $oDadosItemAtivo->iAnoEmpenho   = $oItemAtivo->e60_anousu;
      $mRetorno[]                     = $oDadosItemAtivo;
    }
    if (count($mRetorno) == 0) {
      $mRetorno = false;
    }

    return $mRetorno;
  }

  /**
   * Verifica se houve dispensa de tombamento do bem no patrimonio
   * @param integer $iCodigoNota 
   * @return boolean
   */
  public function houveDispensaTombamentoNoPatrimonio($iCodigoNota) {

    /* Verificamos se houve dispensa de tombamento para os itens da nota */
    $oDaoDispensaTombamento = db_utils::getDao("bensdispensatombamento");
    $sSqlBuscaDispensa      = $oDaoDispensaTombamento->sql_query(null, "1", null, "empnotaitem.e72_codnota = {$iCodigoNota}");
    $rsBuscaDispensa        = $oDaoDispensaTombamento->sql_record($sSqlBuscaDispensa);
    if ($oDaoDispensaTombamento->numrows > 0) {
      return true;
    }
    return false;
  }




  /**
   * Processamos o lan�amento cont�bil de acordo com os itens da entrada percorrendo os grupos dos materiais
   * de cada empenho
   * @param array $aEmpenhoGrupoItem
   */
  private function processarLancamentosOrdemCompra($aEmpenhoGrupoItem, $lEstorno = false) {

    foreach ($aEmpenhoGrupoItem as $iSequencialEmpenho => $aGrupo) {

      foreach ($aGrupo as $iCodigoGrupo => $aItens) {

        $nValorLancamentoGrupo = 0;
        foreach ($aItens as $oItem) {

          $nValorLancamentoGrupo += $oItem->nValorLancamento;
          $iCodigoNotaDoEmpenho   = $oItem->iCodigoDaNota;
        }

        $oEmpenhoFinanceiro      = new EmpenhoFinanceiro($iSequencialEmpenho);
        $aItensEmpenhoFinanceiro = $oEmpenhoFinanceiro->getItens();
        $iCodigoContaElemento    = $aItensEmpenhoFinanceiro[0]->getCodigoElemento();
        $sObservacaoLancamento   = "Lan�amento em liquida��o da ordem de compra {$this->getOrdem()}";
        $oProcessarLancamento = new stdClass();
        $oProcessarLancamento->iCodigoDotacao        = $oEmpenhoFinanceiro->getDotacao()->getCodigo();
        $oProcessarLancamento->iCodigoElemento       = $iCodigoContaElemento;
        $oProcessarLancamento->iCodigoNotaLiquidacao = $iCodigoNotaDoEmpenho;
        $oProcessarLancamento->iFavorecido           = $oEmpenhoFinanceiro->getCgm()->getCodigo();
        $oProcessarLancamento->iNumeroEmpenho        = $oEmpenhoFinanceiro->getNumero();
        $oProcessarLancamento->sObservacaoHistorico  = $sObservacaoLancamento;
        $oProcessarLancamento->iCodigoGrupo          = $iCodigoGrupo;
        $oProcessarLancamento->nValorTotal           = round($nValorLancamentoGrupo, 2);

        if ($lEstorno) {
          LancamentoEmpenhoEmLiquidacao::estornarLancamento($oProcessarLancamento);
        } else {
          LancamentoEmpenhoEmLiquidacao::processaLancamento($oProcessarLancamento);
        }
      }
    }
    return true;
  }
}
?>