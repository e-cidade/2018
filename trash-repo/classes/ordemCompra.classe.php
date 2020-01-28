<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Ordem de compra
 * 
 *
 */
class ordemCompra {

  public $iCodOrdem = null;

  private $daoOrdemCompra = null;

  public $lSqlErro = false;

  public $sErroMsg = null;

  private $aNotas = array (
     
  );

  private $lEncode = false;

  private $isRestoPagar = false;
  
  function ordemCompra($iCodOrdem) {

    $this->setOrdem((int) $iCodOrdem);
    //instanciamos as classes do db_portal referentes a ordem de compra
    $this->usarDao("matordem");
    $this->daoOrdemCompra = new cl_matordem();
  }
  //setters e getters
  function setOrdem($iCodOrdem) {

    (int) $this->iCodOrdem = (int) $iCodOrdem;
  }
  
  function getOrdem() {

    return $this->iCodOrdem;
  }
  
  function setEncodeON() {

    $this->lEncode = true;
  }
  function setEncodeOff() {

    $this->lEncode = false;
  }
  
  function getEncode() {

    return $this->lEncode;
  }
  
  /**
   * @desc  método para retornar os dados da ordem. (retorna um objeto db_utils)
   * @return Object db_utils
   * 
   */
  
  function getDados() {

    $sSQLOrdem = "select * ";
    $sSQLOrdem .= "  from matordem  ";
    $sSQLOrdem .= "      inner join cgm         on  cgm.z01_numcgm          = matordem.m51_numcgm";
    $sSQLOrdem .= "      inner join db_depart   on db_depart.coddepto       = matordem.m51_depto";
    $sSQLOrdem .= "      left  join matordemanu on matordemanu.m53_codordem = matordem.m51_codordem";
    $sSQLOrdem .= " where m51_codordem = " . $this->getOrdem();
    $rsOrdemCompra = $this->daoOrdemCompra->sql_record($sSQLOrdem);
    if ($this->daoOrdemCompra->numrows != 1) {
      
      $this->sErroMsg = "Não Foi possível consultar dados da Ordem ({$this->iCodOrdem}). ";
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

    $sSqlEmpenho = "select e91_anousu,";
    $sSqlEmpenho .= "       e62_numemp ";
    $sSqlEmpenho .= "  from matordemitem ";
    $sSqlEmpenho .= "        inner join empempitem on e62_numemp = m52_numemp ";
    $sSqlEmpenho .= "                             and e62_sequen = m52_sequen ";
    $sSqlEmpenho .= "        inner join empresto   on e62_numemp = e91_numemp ";
    $sSqlEmpenho .= "                             and e91_anousu = " . db_getsession("DB_anousu");
    $sSqlEmpenho .= "  where m52_codordem = {$this->iCodOrdem}";
    $rsEmpenho = $this->daoOrdemCompra->sql_record($sSqlEmpenho);
    // die($sSqlEmpenho);
    if ($this->daoOrdemCompra->numrows > 0) {
      $this->isRestoPagar = true;
    }
    return $this->isRestoPagar;
  }
  
  /**
   * @desc Metodo para retornar as notas da ordem;
   * @param integer [$iCodNota  Código da nota]
   * @return mixed           
   */
  
  function getNotasOrdem($iCodNota = null) {

    $this->usarDao("empnota");
    $sWhere = null;
    if ($iCodNota != null) {
      $sWhere = " and e69_codnota = {$iCodNota}";
    }
    /*
          * Verificamos se o empenho é um RP.
          * caso for, o usario podera anular a entrada dessa nota.
          */
    $this->daoEmpNota = new cl_empnota();
    $sCamposNota = "e69_codnota,e69_anousu,e69_numero,e69_dtnota,e69_dtrecebe,coalesce(e70_valor,0) as e70_valor, ";
    $sCamposNota .= " coalesce(e70_vlrliq,0) as e70_vlrliq ,coalesce(e70_vlranu,0) as e70_vlranu,e60_numemp,m72_codordem,";
    $sCamposNota .= "id_usuario,nome,coalesce(e53_vlrpag,0) as e53_vlrpag";
    $rsNotasOrdem = $this->daoEmpNota->sql_record($this->daoEmpNota->sql_query_nota(null, "$sCamposNota", "e69_codnota", "m72_codordem = {$this->iCodOrdem} {$sWhere}"));
    
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
          $fieldName = pg_field_name($rsNotasOrdem, $iFlds);
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
    $sCampos = "pc01_descrmater,pc01_servico,pc01_fraciona, e60_anousu,e60_codemp,e60_numemp,m52_codlanc, m52_vlruni,m71_valor, m71_codlanc,";
    $sCampos .= "e69_codnota,m71_quant, m75_quant,m71_quantatend,m52_valor,m75_quantmult,m75_codmatunid, m71_codmatestoque";
    $rsItensEstoque = $daoItensEstoque->sql_record($daoItensEstoque->sql_query_itensunid(null, null, $sCampos, "m71_codlanc", "m74_codempnota={$iCodNota}"));
    //die ($daoItensEstoque->sql_query_itensunid(null,null,$sCampos,"m71_codlanc","m74_codempnota={$iCodNota}"));                            
    if ($daoItensEstoque->numrows > 0) {
      
      for($iItens = 0; $iItens < $daoItensEstoque->numrows; $iItens ++) {
        
        $oItens = db_utils::fieldsMemory($rsItensEstoque, $iItens, false, false, $this->getEncode());
        $this->aItensNota [] = array (
          
                        "pc01_descrmater" => $oItens->pc01_descrmater, 
                        "pc01_fraciona" => $oItens->pc01_fraciona, 
                        "e60_numemp" => $oItens->e60_numemp, 
                        "e60_codemp" => $oItens->e60_codemp, 
                        "e60_anousu" => $oItens->e60_anousu, 
                        "m52_vlruni" => $oItens->m52_vlruni, 
                        "m52_valor" => $oItens->m52_valor, 
                        "m52_codlanc" => $oItens->m52_codlanc, 
                        "m75_quant" => $oItens->m75_quant, 
                        "m71_quant" => $oItens->m71_quant, 
                        "m71_valor" => $oItens->m71_valor, 
                        "m75_quantmult" => $oItens->m75_quantmult, 
                        "m71_quantatend" => $oItens->m71_quantatend, 
                        "m71_codlanc" => $oItens->m71_codlanc, 
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
      $sJson ["m51_numcgm"] = $this->dadosOrdem->m51_numcgm;
      $sJson ["z01_nome"] = $this->dadosOrdem->z01_nome;
      $sJson ["m51_tipo"] = $this->dadosOrdem->m51_tipo;
      $sJson ["totalItens"] = 0;
      $sJson ["itens"] = array (
         
      );
      if ($this->getNotasOrdem($iCodNota)) {
        
        $sJson ["e69_codnota"] = $this->aNotas [$iCodNota] ["e69_codnota"];
        $sJson ["e69_numero"] = $this->aNotas [$iCodNota] ["e69_numero"];
        $sJson ["e69_dtnota"] = db_formatar($this->aNotas [$iCodNota] ["e69_dtnota"], "d");
        $sJson ["e69_dtrecebe"] = db_formatar($this->aNotas [$iCodNota] ["e69_dtrecebe"], "d");
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["e70_valor"] = $this->aNotas [$iCodNota] ["e70_valor"];
        $sJson ["id_usuario"] = $this->aNotas [$iCodNota] ["id_usuario"];
        $sJson ["situacaonota"] = $this->aNotas [$iCodNota] ["situacao"];
        $sJson ["nome"] = $this->aNotas [$iCodNota] ["nome"];
        if ($this->getItensOrdemEmEstoque($iCodNota)) {
          
          $sJson ["totalItens"] = count($this->aItensNota);
          $sJson ["itens"] = $this->aItensNota;
        }
      }
    }
    if (! $this->lSqlErro) {
      $sJson ["status"] = 1;
      $sJson ["mensagem"] = null;
    } else {
      
      $sJson ["status"] = 2;
      $sJson ["mensagem"] = "Erro: " . urlencode($this->sErroMsg);
    }
    $jsonEncoded = $oJson->encode($sJson);
    return $jsonEncoded;
  }
  
  /**
   * @description Metodo para anular entrada da nota ordem no almox;
   * @param  integer  iCodNota - codigo da nota a ser anulada
   * 
   */
  
  function anularEntradaNota($iCodNota) {

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
    (float) $nTotalNota = 0;
    $clmatestoque = new cl_matestoque();
    $clmatestoqueini = new cl_matestoqueini();
    $clmatestoqueinil = new cl_matestoqueinil();
    $clmatestoqueinill = new cl_matestoqueinill();
    $clmatestoqueinimei = new cl_matestoqueinimei();
    $clmatestoqueitem = new cl_matestoqueitem();
    $clmatestoqueitemoc = new cl_matestoqueitemoc();
    $clmatestoqueitemnota = new cl_matestoqueitemnota();
    $clmatestoqueitemunid = new cl_matestoqueitemunid();
    //traz os dados da nota.
    $this->getItensOrdemEmEstoque($iCodNota);
    $this->getDados();
    db_inicio_transacao();
    $clmatestoqueini->m80_data = date('Y-m-d', db_getsession("DB_datausu"));
    $clmatestoqueini->m80_hora = db_hora();
    $clmatestoqueini->m80_coddepto = db_getsession("DB_coddepto");
    $clmatestoqueini->m80_login = db_getsession("DB_id_usuario");
    $clmatestoqueini->m80_codtipo = 19;
    $clmatestoqueini->m80_obs = '';
    $clmatestoqueini->incluir(null);
    if ($clmatestoqueini->erro_status == 0) {
      $this->lSqlErro = true;
      $this->sErroMsg = $clmatestoqueini->erro_msg;
    }
    //percorremos os itens da nota, e anulamos a entrada. 
    if (! $this->lSqlErro) {
      
      $iCodigoIni = $clmatestoqueini->m80_codigo;
      for($iItens = 0; $iItens < count($this->aItensNota); $iItens ++) {
        
        $m71_codlanc = $this->aItensNota [$iItens] ["m71_codlanc"];
        $nQtdItens = $this->aItensNota [$iItens] ["m71_quant"];
        $nValorItem = $this->aItensNota [$iItens] ["m71_valor"];
        $m75_quantmult = $this->aItensNota [$iItens] ["m75_quantmult"];
        $m75_codmatunid = $this->aItensNota [$iItens] ["m75_codmatunid"];
        $m71_codmatestoque = $this->aItensNota [$iItens] ["m71_codmatestoque"];
        $nTotalNota += $this->aItensNota [$iItens] ["m71_valor"];
        $result_iniant = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query(null, "m82_matestoqueini", null, "m82_matestoqueitem={$m71_codlanc}
                                                                                                  and m80_codtipo=12"));
        if ($clmatestoqueinimei->numrows > 0) {
          //como ja existe item no estoque para essa ordem, lançamos na tabelas abaixo o codigo do matestoqueini.
          $oIniAnt = db_utils::fieldsMemory($result_iniant, 0); //codigo anterior do item no estoque;
          if ($this->lSqlErro == false) {
            
            $clmatestoqueinil->m86_matestoqueini = $oIniAnt->m82_matestoqueini;
            $clmatestoqueinil->incluir(null);
            if ($clmatestoqueinil->erro_status == 0) {
              
              $this->lSqlErro = true;
              $this->sErroMsg = $clmatestoqueinil->erro_msg;
            }
          }
          if ($this->lSqlErro == false) {
            
            $iCodInil = $clmatestoqueinil->m86_codigo; //
            $clmatestoqueinill->m87_matestoqueini = $iCodigoIni;
            $clmatestoqueinill->incluir($iCodInil);
            if ($clmatestoqueinill->erro_status == 0) {
              
              $this->lSqlErro = true;
              $this->sErroMsg = $clmatestoqueinill->erro_msg;
            }
          }
        }
        //Iniciamos lancamentos de estorno dos itens,
        if (! $this->lSqlErro) {
          
          $clmatestoqueitem->m71_valor = "0"; #"$nValorItem";
          $clmatestoqueitem->m71_quant = "0"; #"$nQtdItens";
          $clmatestoqueitem->m71_codlanc = $m71_codlanc;
          $clmatestoqueitem->alterar($m71_codlanc);
          if ($clmatestoqueitem->erro_status == 0) {
            
            $this->sErroMsg = $clmatestoqueitem->erro_msg;
            $this->lSqlErro = true;
          }
        }
        //Unidades do item 
        if (! $this->lSqlErro) {
          
          $clmatestoqueitemunid->m75_codmatestoqueitem = $m71_codlanc;
          $clmatestoqueitemunid->m75_quantmult = "$m75_quantmult";
          $clmatestoqueitemunid->m75_quant = "$nQtdItens";
          $clmatestoqueitemunid->m75_codmatunid = $m75_codmatunid;
          $clmatestoqueitemunid->alterar($m71_codlanc);
          if ($clmatestoqueitemunid->erro_status == 0) {
            
            $this->sErroMsg = $clmatestoqueitemunid->erro_msg;
            $this->lSqlErro = true;
          }
        }
        //tabela de relação entre matestoqueini e matestoqueitem
        if (! $this->lSqlErro) {
          
          $clmatestoqueinimei->m82_matestoqueini = $clmatestoqueini->m80_codigo;
          $clmatestoqueinimei->m82_matestoqueitem = $m71_codlanc;
          $clmatestoqueinimei->m82_quant = "$nQtdItens";
          $clmatestoqueinimei->incluir(null);
          if ($clmatestoqueinimei->erro_status == 0) {
            
            $this->lSqlErro = true;
            $this->sErroMsg = $clmatestoqueinimei->erro_msg;
          }
        }
        //excluimos o controle da entrada da ordem; (devemos criar uma situacao para esse registroi em vez de excluir)
        if (! $this->lSqlErro) {
          
          $rsOc = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query_OC_Nota(null, null, "m73_codmatestoqueitem,m73_codmatordemitem", null, "m52_codordem={$this->iCodOrdem} and m74_codempnota = {$iCodNota}"));
          $iNumRows = $clmatestoqueitemoc->numrows;
          echo pg_last_error();
          for($iTot = 0; $iTot < $iNumRows; $iTot ++) {
            
            $oItemOC = db_utils::fieldsMemory($rsOc, $iTot);
            $clmatestoqueitemoc->excluir($oItemOC->m73_codmatestoqueitem, $oItemOC->m73_codmatordemitem);
            if ($clmatestoqueitemoc->erro_status == 0) {
              
              $this->lSqlErro = true;
              $this->sErromsg = $clmatestoqueitemoc->erro_msg;
              break;
            }
          }
        }
        if (! $this->lSqlErro) {
          
          $clmatestoqueitemnota->excluir(null, null, "m74_codempnota=$iCodNota");
          if ($clmatestoqueitemnota->erro_status == 0) {
            
            $this->lSqlErro = true;
            $this->sErroMsg = $clmatestoqueitemnota->erro_msg;
          }
        }
      }
      //caso a ordem de compra seje normal, lançamos o valor da nota como anulado.
      $this->verificarEmpenho();
      if (! $this->lSqlErro && $this->dadosOrdem->m51_tipo == 1) {
        
        $clempnotaele = $this->usarDao("empnotaele", true);
        $clempnotaele->e70_codnota = $iCodNota;
        $clempnotaele->e70_vlranu = $nTotalNota;
        $clempnotaele->alterar($iCodNota);
        if ($clempnotaele->erro_status == 0) {
          
          $this->lSqlErro = true;
          $this->sErroMsg = $clempnotaele->erro_msg;
        }
      }
    }
    db_fim_transacao($this->lSqlErro);
  } //end function
  

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
    $sSQlSaldo .= "       m52_codlanc";
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
                                                                                               1 = Anulacao de Item
                                                                                               2 = Anulacao de valores 
   * @returns   void; 
   */
  
  function anularOrdem($aItens, $sMotivo = '', $iSolicitaAnulEmpenho = 0) {

    if (! is_array($aItens)) {
      
      $this->lSqlErro = true;
      $this->sErroMsg = "Erro [1]: Parametro aItens não e um array valido!\nContate Suporte";
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
    //incluimos a anulação da ordem na tablea matordemanul 
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
          $this->sErroMsg = "Erro [4]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
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
              $this->sErroMsg = "Erro [5]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
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
            $this->sErroMsg = "Erro [6]:\n Não foi possível Anular item ({$itensAnulados->iCodItemOrdem})";
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
   * @desc Metodo para carregar o arquivo de definição da classe requerida;
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
   * Retorna informações da ordem de compra para dar entrada no estoque
   *
   * @return object
   */
  function getInfoEntrada() {
    
    //carregamos os dados da ordem     
    if ($this->getDados()) {
      
      /*
       * trazemos items da ordem, com seus saldos,
       * e acrescentamos informações sobre o item, com suas
       * ligações com o item do almoxarifado.
       */ 
      if ($this->getItensSaldo()) {
        
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
           * Buscamos os dados da solicitação para ver se o item possui informações 
           * de unidade cadastradas.
           */
          $rsSolicitem = $oDaoSolicitem->sql_record($oDaoSolicitem->sql_query_solunid(
                                   null, ",pc17_quant", null, $this->dadosOrdem->itens[$iInd]->e62_sequencial));
          if ($oDaoSolicitem->numrows == 1) {

             $this->dadosOrdem->itens[$iInd]->unidade      = db_utils::fieldsMemory($rsSolicitem,0)->pc17_unid;
             $this->dadosOrdem->itens[$iInd]->quantunidade = db_utils::fieldsMemory($rsSolicitem,0)->pc17_quant;
          }
          
          /*
           * Buscamos todos os itens vinculados ao material do compras
           * no cadastro de materias do almoxarifados, para o usuário escolher um .
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
          $oMaterial->fraciona          = false; //se o o item é fracionado.
          $oMaterial->iTotalFracionados = 0;//Total de itens Fracionados
          $oMaterial->iIndiceEntrada    = 0;
          $this->saveMaterial($this->dadosOrdem->itens[$iInd]->m52_codlanc,$oMaterial);
          unset ($oMaterial);
           
        }                                               
      } else {
        
        throw  new Exception("Não foi possível Encontrar itens da ordem ({$this->iCodOrdem}).");
        return false;
      
      }
    } else {
      
      throw  new Exception("Não foi possível Encontrar dados da ordem ({$this->iCodOrdem}).");
      return false;
      
    }
    return true;
  }
  /**
   * Inicializa a sessão para a ordem
   *
   */
  function initSession() {

    if (!isset($_SESSION["matordem{$this->iCodOrdem}"])) {
      
      $_SESSION["matordem{$this->iCodOrdem}"]= array();
    }
    return $_SESSION["matordem{$this->iCodOrdem}"];
    /**
     * Esquema das sessões para a ordem de compra
     * {matordem<codigo_da_ordem>[codigo_item][sequencial](codigo_do_material,
     *                                                     quant,
     *                                                     valor,
     *                                                     unidade,
     *                                                     qdteunidade,
     *                                                     lote,
     *                                                     validade
     *                                                     id_fornecedor
     *                                                     }
     */
  }
  
  /**
   * Salva as modificações da entrada na sessao
   *
   * @param integer $iCodLanc codigo do item da ordem de compra
   * @param  object $oMaterial objeto com informações da entrada
   * @return unknown
   */
  function saveMaterial($iCodLanc, $oMaterial) {
    
    $oOrdemSession = $this->initSession();
    if (!isset($oOrdemSession[$iCodLanc])) {
      
      $oOrdemSession[$iCodLanc] = array();
    }
    
    //verificamos se o material do estoque ja foi incluido.
    foreach ($oOrdemSession[$iCodLanc] as $oLancamento) {
      
      if ($oMaterial->iIndiceEntrada != $oLancamento->iIndiceEntrada) {
        if ($oLancamento->m63_codmatmater ==  $oMaterial->m63_codmatmater 
           && $oLancamento->m77_lote == $oMaterial->m77_lote) {
          throw  new Exception("Material/lote já cadastrado.");   
        }
      } 
    }
    
    $oMaterial->pc01_descrmater = urlencode(urldecode($oMaterial->pc01_descrmater));
    $oMaterial->e62_descr       = urlencode(urldecode($oMaterial->e62_descr));
    $oMaterial->m76_nome        = urlencode(urldecode($oMaterial->m76_nome));
    if ($oMaterial->iIndiceEntrada != "") {
      
      
      $oOrdemSession[$iCodLanc][$oMaterial->iIndiceEntrada] = $oMaterial;
      
    } else {
      
      if ($oMaterial->fraciona) {

       /*if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_quant + $oMaterial->m52_quant
             > $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldoitens) {
               
           throw  new Exception("Fracionamento com valor maior que o saldo.");
           return false;
                   
        }
        if ($oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->m52_valor + $oMaterial->m52_valor
             > $oOrdemSession[$iCodLanc][$oMaterial->iIndiceDebitar]->saldovalor) {
               
           throw  new Exception("Fracionamento com valor maior que o saldo.");
           return false;
                   
        }*/
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
        //print_r($oOrdemSession[$iCodLanc]);
        //echo $this->nextVal($iCodLanc);
        //exit;
        
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
  function destroySession() {
    
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
  function getDadosEntrada () {
    
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
   * Retorna as informações sobre item escolhido
   *
   * @param integer $iCodLanc Código do item da ordem de compra; 
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
    
    $oItemAtivo->aMateriaisEstoque = $aItensMaterial;
    return $oItemAtivo;
  }
  /**
   * Cancela o fracionamento do Item passado;
   *
   * @param integer $iCodLanc código do Lançamento do item
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
  
  function confirmaEntrada($iNumNota, $dtDataNota, $dtDataRecebe, $nValorNota, $aItens) {
    
    //Devemos estar dentro de uma transação.
    
    if (!db_utils::inTransaction()) {

      throw new Exception("Não existe uma transação ativa.\nProcedimento Cancelado");
      return false;
    
    }
    
    if (!is_array($aItens)) {
      
      throw new Exception("Parametro aItens deve ser um Array.\nProcedimento Cancelado");
      return false;
      
    }
    //primeiro, descobrimos a quantidade de empenhos que a ordem de compra possui.
    $aEmpenhos     = array();
    $iTotItens     = count($aItens);
    $aEntradas     = $_SESSION["matordem{$this->iCodOrdem}"];
    /**
     * valor total da entrada.
     */
    $nTotalEntrada = 0; 
    for ($iEmp = 0; $iEmp < $iTotItens; $iEmp++) {
      
      if ($aEntradas[$aItens[$iEmp]->iCodLanc][$aItens[$iEmp]->iIndiceEntrada] ) {
        
        
        //Pegamos o item ativo, pelo codigo do lançamento e do seu indice.
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
      return false;
      
    }
    $this->getDados();
    $oDaoMatestoqueIni               = db_utils::getDao("matestoqueini");
    $oDaoMatestoqueIni->m80_data     = date('Y-m-d',db_getsession("DB_datausu"));
    $oDaoMatestoqueIni->m80_hora     = db_hora();
    $oDaoMatestoqueIni->m80_coddepto = $this->dadosOrdem->m51_depto;
    $oDaoMatestoqueIni->m80_login    = db_getsession("DB_id_usuario");
    $oDaoMatestoqueIni->m80_codtipo  = 12;
    $oDaoMatestoqueIni->m80_obs      = "";
    $oDaoMatestoqueIni->incluir(null);
    if ($oDaoMatestoqueIni->erro_status == 0) {
      
      $sErroMsg  = "Erro [1]- Não foi possivel Iniciar Movimento no Estoque.\n";
      $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueIni->erro_msg}";
      throw new Exception($sErroMsg);
      return false;
      
    }
    $iCodMov = $oDaoMatestoqueIni->m80_codigo;
    for ($iItem = 0; $iItem < $iTotItens; $iItem++) {

      if ($aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada] ) {
        
        //Pegamos o item ativo, pelo codigo do lançamento e do seu indice.
        $oItemAtivo = $aEntradas[$aItens[$iItem]->iCodLanc][$aItens[$iItem]->iIndiceEntrada];
        /*
         * Verificamos se o usuário escolheu um item de entrada para o item.
         * caso nao, devemos incluir o tem , com a descrição do mpdulo material.
         */
        if ($oItemAtivo->m63_codmatmater == "") {
          
          $oDaoMatMater = db_utils::getDao("matmater");
          $oDaoMatMater->m60_codmatunid       = 1;
          $oDaoMatMater->m60_quantent         = 1;
          $oDaoMatMater->m60_descr            = urldecode($oItemAtivo->pc01_descrmater);
          $oDaoMatMater->m60_controlavalidade = 3;
          $oDaoMatMater->m60_ativo            = "t";
          $oDaoMatMater->m60_codant           = "";
          $oDaoMatMater->incluir(null);
          if ($oDaoMatMater->erro_status == 0) {

            $sErroMsg  = "Erro [6]- Item ({$oItemAtivo->pc01_descrmater}) nao possui item de Entrada.\n";
            $sErroMsg .= "Operação Cancelada.";
            throw new Exception($sErroMsg);
            return false;
            
          }
          $oItemAtivo->m63_codmatmater = $oDaoMatMater->m60_codmater;
          $oDaoTransMater = db_utils::getDao("transmater");
          $oDaoTransMater->m63_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoTransMater->m63_codpcmater  = $oItemAtivo->pc01_codmater;
          $oDaoTransMater->incluir();
          if ($oDaoTransMater->erro_status == 0) {
            
            $sErroMsg  = "Erro [13]- Item ({$oItemAtivo->pc01_descrmater}) Não foi possível incluir material.\n";
            $sErroMsg .= "Operação Cancelada.";
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
           throw new Exception("item ({$oItemAtivo->pc01_descrmater} com valores inválidos.\Verifique)");
         }
        /*
         * verificamos se a ordem não é uma ordem automatica, caso verdadeiro, 
         * devemos criar uma nota para o empenho.
         * marcamos o elemento iCodNota do array aEmpenhos com o codigo da nota, 
         * e passamos a  usar essa nota para todas as entradas desse empenho.
         */
        if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == "" 
            && $this->dadosOrdem->m51_tipo == 1) {
              
          $oDaoEmpNota                 = db_utils::getDao("empnota");
          $oDaoEmpNota->e69_anousu     = db_getsession("DB_anousu");
          $oDaoEmpNota->e69_dtnota     = implode("-", array_reverse(explode("/", $dtDataNota)));
          $oDaoEmpNota->e69_dtrecebe   = implode("-", array_reverse(explode("/", $dtDataRecebe)));
          $oDaoEmpNota->e69_id_usuario = db_getsession("DB_id_usuario");
          $oDaoEmpNota->e69_numemp     = $oItemAtivo->e60_numemp;
          $oDaoEmpNota->e69_numero     = $iNumNota;
          $oDaoEmpNota->incluir(null);
          if ($oDaoEmpNota->erro_status == 0 ) {
            
            $sErroMsg  = "Erro [2]- Não foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoEmpNota->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
            
          }
          $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = $oDaoEmpNota->e69_codnota;
          //incluimos o elemento da nota;
          $oDaoElemento = db_utils::getDao("empelemento");
          $rsElemento   = $oDaoElemento->sql_record($oDaoElemento->sql_query_file($oItemAtivo->e60_numemp));
          if ($oDaoElemento->numrows == 1) {
            $oElemento = db_utils::fieldsMemory($rsElemento, 0);
          } else {
            throw new Exception("Erro[3]- Empenho sem elementos, ou com mais de um elemento.Procedimento cancelado");
          }
          
          $oDaoEmpNotaEle              = db_utils::getDao("empnotaele");
          $oDaoEmpNotaEle->e70_codele  = $oElemento->e64_codele;
          $oDaoEmpNotaEle->e70_codnota = $oDaoEmpNota->e69_codnota;
          $oDaoEmpNotaEle->e70_valor   = $aEmpenhos[$oItemAtivo->e60_numemp]["valor"];
          $oDaoEmpNotaEle->e70_vlrliq  = "0";
          $oDaoEmpNotaEle->e70_vlranu  = "0";
          $oDaoEmpNotaEle->incluir($oDaoEmpNota->e69_codnota, $oElemento->e64_codele);
          if ($oDaoEmpNotaEle->erro_status == 0) {
            
            $sErroMsg  = "Erro [4]- Não foi possivel incluir nota fiscal.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoEmpNotaEle->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
                       
          }
          $oDaoEmpNotaOrd = db_utils::getDao("empnotaord");
          $oDaoEmpNotaOrd->incluir($oDaoEmpNota->e69_codnota, $this->dadosOrdem->m51_codordem);
          
        } else if ($aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] == "" 
                   && $this->dadosOrdem->m51_tipo == 2) {
          
          /*
           * a nota é virtual, entao apenas pegamos o número da nota gerada .
           */
          $oDaoEmpNota = db_utils::getDao("empnota");
          $rsNota      = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null,
                                                                               "e69_codnota",
                                                                                null,
                                                                                "m72_codordem = {$this->dadosOrdem->m51_codordem}"));
          if ($oDaoEmpNota->numrows == 1) {                                                                                
             
            $oNotas   = db_utils::fieldsMemory($rsNota,0);
            $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"] = $oNotas->e69_codnota;
             
          } else {
            
            throw new Exception("Erro[5]- Ordem de Compra automática sem Nota fiscal. Procedimento cancelado");
            return false;
            
          }
        }
        
        /**
         * Verificamos se o item escolhido já possui estoque (matestoque)
         * cadastrado no departamento da ordem de compra.
         * caso nao exista, fazemos o cadastro
         */
        $oDaoMatestoque = db_utils::getDao("matestoque");
        $rsMatestoque   = $oDaoMatestoque->sql_record(
                          $oDaoMatestoque->sql_query_file(null,
                                                          "*",
                                                          null,
                                                          "m70_codmatmater  = {$oItemAtivo->m63_codmatmater}
                                                           and m70_coddepto = {$this->dadosOrdem->m51_depto}"));
        if ($oDaoMatestoque->numrows == 0) {

          $oDaoMatestoque->m70_coddepto    = $this->dadosOrdem->m51_depto;
          $oDaoMatestoque->m70_codmatmater = $oItemAtivo->m63_codmatmater;
          $oDaoMatestoque->m70_valor       = "0";
          $oDaoMatestoque->m70_quant       = "0";
          $oDaoMatestoque->incluir(null);
          $iCodEstoque = $oDaoMatestoque->m70_codigo;
          if ($oDaoMatestoque->erro_status == 0) {
          
            $sErroMsg  = "Erro [7]- Não foi possível iniciar estoque.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoque->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
             
          }
        } else {
          $iCodEstoque = db_utils::fieldsMemory($rsMatestoque, 0)->m70_codigo; 
        }
        
        //incluimos na matestoqueitem
        $oDaoMatestoqueItem = db_utils::getDao("matestoqueitem");
        $oDaoMatestoqueItem->m71_codmatestoque = $iCodEstoque;
        $oDaoMatestoqueItem->m71_data          = implode("-", array_reverse(explode("/", $dtDataRecebe)));
        $oDaoMatestoqueItem->m71_quant         = $oItemAtivo->m52_quant * $oItemAtivo->quantunidade;
        $oDaoMatestoqueItem->m71_quantatend    = "0";
        $oDaoMatestoqueItem->m71_valor         = $oItemAtivo->m52_valor;
        $oDaoMatestoqueItem->incluir(null);
        if ($oDaoMatestoqueItem->erro_status == 0){
          
          $sErroMsg  = "Erro [8]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueItem->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
        }
        
        //incluimos matestoqueitemunid
        $oDaoMatUnid                        = db_utils::getDao("matestoqueitemunid");
        $oDaoMatUnid->m75_codmatestoqueitem = $oDaoMatestoqueItem->m71_codlanc;	
        $oDaoMatUnid->m75_codmatunid        = $oItemAtivo->unidade;	
        $oDaoMatUnid->m75_quant             = $oItemAtivo->m52_quant;	
        $oDaoMatUnid->m75_quantmult         = $oItemAtivo->quantunidade;	
        $oDaoMatUnid->incluir($oDaoMatestoqueItem->m71_codlanc);
        if ($oDaoMatUnid->erro_status==0){

          $sErroMsg  = "Erro [8]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatUnid->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
          
        }
        /**
         * incluimos a ligação da entrada da ordem de compra com o item do estoque
         */
        $oDaoMatItemOC = db_utils::getDao("matestoqueitemoc");
        $oDaoMatItemOC->incluir($oDaoMatestoqueItem->m71_codlanc, $oItemAtivo->m52_codlanc);
        if ($oDaoMatItemOC->erro_status == 0) {
          
          $sErroMsg  = "Erro [9]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatItemOC->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
          
        }
        
        /**
         * incluimos ligacao do itemn com a nota fiscal
         */
        $oDaoMatItemNota  = db_utils::getDao("matestoqueitemnota");
        $oDaoMatItemNota->incluir($oDaoMatestoqueItem->m71_codlanc, $aEmpenhos[$oItemAtivo->e60_numemp]["iCodNota"]);
        if ($oDaoMatItemNota->erro_status == 0) {
          
          $sErroMsg  = "Erro [10]- Não foi possível iniciar estoque.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatItemNota->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
          
        }
        /*
         * caso o usuário deu informações sobre o lote, salvamos na tabela matestoqueitemlote
         */
        if (trim($oItemAtivo->m77_lote) != "") {
          
          $oDaoMatestoqueItemLote = db_utils::getDao("matestoqueitemlote");
          $oDaoMatestoqueItemLote->m77_dtvalidade     =  implode("-", array_reverse(explode("/", $oItemAtivo->m77_dtvalidade)));
          $oDaoMatestoqueItemLote->m77_lote           = $oItemAtivo->m77_lote;
          $oDaoMatestoqueItemLote->m77_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatestoqueItemLote->incluir(null);
          if ($oDaoMatestoqueItemLote->erro_status == 0) {
            
            $sErroMsg  = "Erro [13]- Não foi possível Salvar informações do lote.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueItemLote->erro_msg}";
            throw new Exception($sErroMsg);
            return false;
            
          }
        }
        /*
         * Caso o usuário informou o fabricante do material, 
         * gravamos na matestoqueitemfabricante.
         */
        
        if (trim($oItemAtivo->m78_matfabricante) != '') {
          
          $oDaoMatFabricante = db_utils::getDao("matestoqueitemfabric");
          $oDaoMatFabricante->m78_matestoqueitem = $oDaoMatestoqueItem->m71_codlanc;
          $oDaoMatFabricante->m78_matfabricante  = $oItemAtivo->m78_matfabricante;
          $oDaoMatFabricante->incluir(null);
          if ($oDaoMatFabricante->erro_status == 0) {
            
            $sErroMsg  = "Erro [17] - Não foi possível Salvar informações do fabricante.\n";
            $sErroMsg .= "[Erro Técnico] - {$oDaoMatFabricante->erro_msg}";
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
        $oDaoMatestoqueIniMei->m82_quant          = $oItemAtivo->m52_quant;
        $oDaoMatestoqueIniMei->incluir(null);
        if ($oDaoMatestoqueIniMei->erro_status == 0) {
          
          $sErroMsg  = "Erro [11]- Não foi possível finalizar a inclusao da Ordem.\n";
          $sErroMsg .= "[Erro Técnico] - {$oDaoMatestoqueIniMei->erro_msg}";
          throw new Exception($sErroMsg);
          return false;
          
        }
      }
    }
  
    /*
     * Incluimos os itens de cada nota, conforme a entrada dos mesmos no estoque
     */
    if ($this->dadosOrdem->m51_tipo == 1) {
       foreach ($aEmpenhos as $oNota) { 
         
         $iCodNota      = $oNota["iCodNota"];
         $clempnotaitem = db_utils::getDao("empnotaitem");
         $sSQlItens     = "SELECT sum(m71_quant) as m71_quant,";
         $sSQlItens    .= "       sum(m71_valor) as m71_valor,";
         $sSQlItens    .= "       e62_sequencial";
         $sSQlItens    .= "  from matestoqueitem ";
         $sSQlItens    .= "            inner join matestoqueitemnota on m71_codlanc = m74_codmatestoqueitem";
         $sSQlItens    .= "            inner join matestoqueitemoc   on m71_codlanc = m73_codmatestoqueitem";
         $sSQlItens    .= "            inner join matordemitem       on m52_codlanc = m73_codmatordemitem";
         $sSQlItens    .= "            inner join empempitem         on m52_numemp  = e62_numemp";
         $sSQlItens    .= "                                         and m52_sequen  = e62_sequen";
         $sSQlItens    .= "  where m74_codempnota = {$iCodNota}";
         $sSQlItens    .= "  group by  e62_sequencial";
         $rsItens       = pg_query($sSQlItens);
      
      
         for ($iInd = 0; $iInd < pg_num_rows($rsItens); $iInd++){
      
           $oItens = db_utils::fieldsMemory($rsItens, $iInd);
           $clempnotaitem->e72_codnota    = $iCodNota;
           $clempnotaitem->e72_empempitem = $oItens->e62_sequencial;
           $clempnotaitem->e72_qtd        = $oItens->m71_quant;
           $clempnotaitem->e72_valor      = $oItens->m71_valor;
           $clempnotaitem->incluir(null);
           if ($clempnotaitem->erro_status == 0){
      
             $sErroMsg  = "Erro[12] - Não foi possível incluir itens da nota.\n";
             $sErroMsg .= "[Erro Técnico] - {$clempnotaitem->erro_msg}";
             throw new Exception($sErroMsg);
             break;
           }
         }
       }
    }
     $this->destroySession();
     return true;
     
  } //End Function 
}
?>