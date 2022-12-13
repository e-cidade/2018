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


/**
 * Model para controle agenda de pagamentos
 * @package caixa
 * @author Iuri Guntchnigg
 * @revision $Author: dbmatheus.felini $
 * @version $Revision: 1.147 $
 */
class agendaPagamento {

  private $iCodAgenda = null;
  /**
   * aplica urlencode ao tipo string nos retornos
   *
   * @var boolean
   */
  private $lUrlEncode = false;

  /**
   * Código da ordem auxiliar de pagamento
   *
   * @var integer
   */
  private $iCodigoOrdemAuxiliar;

  private $orderBy = null;


  const FORMA_PAGAMENTO_DINHEIRO = 1;

  /**
   *
   */
  function __construct() {

  }

  /**
   * Seta a ultima forma de pagamento do credor
   *
   * @param integer $iNumCgm codigo do CGM
   * @param integer $iFormaPagamento forma de pagamento.
   */
  function setFormaPagamentoCGM($iNumCgm, $iFormaPagamento) {

    if ($iFormaPagamento != 0) {

      $oDaoEmpAgeCgm = db_utils::getDao("empageformacgm");
      $sSqlEmpageCgm = $oDaoEmpAgeCgm->sql_query_file(null, "*", null, "e28_numcgm={$iNumCgm}");
      $rsEmpAgeCgm = $oDaoEmpAgeCgm->sql_record($sSqlEmpageCgm);
      if ($oDaoEmpAgeCgm->numrows == 0) {

        $oDaoEmpAgeCgm->e28_numcgm      = $iNumCgm;
        $oDaoEmpAgeCgm->e28_empageforma = $iFormaPagamento;
        $oDaoEmpAgeCgm->e28_empagetipo  = "null";
        $oDaoEmpAgeCgm->incluir(null);
        if ($oDaoEmpAgeCgm->erro_status == 0) {
          throw new Exception($oDaoEmpAgeCgm->erro_msg);
        }
      } else if ($oDaoEmpAgeCgm->numrows == 1) {

        $oDaoEmpAgeCgm->e28_numcgm      = $iNumCgm;
        $oDaoEmpAgeCgm->e28_empageforma = $iFormaPagamento;
        $oDaoEmpAgeCgm->e28_sequencial  = db_utils::fieldsMemory($rsEmpAgeCgm, 0)->e28_sequencial;
        $oDaoEmpAgeCgm->alterar(db_utils::fieldsMemory($rsEmpAgeCgm, 0)->e28_sequencial);
        if ($oDaoEmpAgeCgm->erro_status == 0) {
          throw new Exception($oDaoEmpAgeCgm->erro_msg);
        }
      }
    }
  }

  /**
   * Retorna a ultima forma de pagamento usada para o credor
   *
   * @param integer $iNumCgm Código do Cgm
   * @return integer
   */
  function getformaPagamentoCGM($iNumCgm) {

    $oDaoEmpAgeCgm = db_utils::getDao("empageformacgm");
    $sSqlEmpageCgm = $oDaoEmpAgeCgm->sql_query_file(null, "*", null, "e28_numcgm={$iNumCgm}");
    $rsEmpAgeCgm   = $oDaoEmpAgeCgm->sql_record($sSqlEmpageCgm);
    if ($oDaoEmpAgeCgm->numrows == 1) {
      return db_utils::fieldsMemory($rsEmpAgeCgm, 0)->e28_empageforma;
    }
  }

  /**
   * Seta a ultima conta escolhida para o fornecedor
   *
   * @param integer $iNumCgm Numero do CGM
   * @param integer $iCodTipo Codigo da conta pagadora
   */
  function setContaPagadoraCgm($iNumCgm, $iCodTipo) {

    if ($iCodTipo != 0) {

      $oDaoEmpAgeCgm = db_utils::getDao("empageformacgm");
      $sSqlEmpageCgm = $oDaoEmpAgeCgm->sql_query_file(null, "*", null, "e28_numcgm={$iNumCgm}");
      $rsEmpAgeCgm = $oDaoEmpAgeCgm->sql_record($sSqlEmpageCgm);
      if ($oDaoEmpAgeCgm->numrows == 0) {

        $oDaoEmpAgeCgm->e28_numcgm     = $iNumCgm;
        $oDaoEmpAgeCgm->e28_empagetipo = $iCodTipo;
        $oDaoEmpAgeCgm->incluir(null);
        if ($oDaoEmpAgeCgm->erro_status == 0) {
          throw new Exception($oDaoEmpAgeCgm->erro_msg);
        }
      } else if ($oDaoEmpAgeCgm->numrows == 1) {


        $oDaoEmpAgeCgm->e28_numcgm      = $iNumCgm;
        $oDaoEmpAgeCgm->e28_empagetipo  = $iCodTipo;
        $oDaoEmpAgeCgm->e28_sequencial  = db_utils::fieldsMemory($rsEmpAgeCgm, 0)->e28_sequencial;
        $oDaoEmpAgeCgm->alterar(db_utils::fieldsMemory($rsEmpAgeCgm, 0)->e28_sequencial);
        if ($oDaoEmpAgeCgm->erro_status == 0) {
          throw new Exception("$iCodTipo-".$oDaoEmpAgeCgm->erro_msg);
        }
      }
    }
  }

  /**
   * retorna as Ordens cadastradas
   *
   * @param string $sWhere clausula where
   * @param boolean $lRetornaContasVinculadas se deve retorna as contas vinculadas ao recurso do empenho.
   * @return array
   */
  function getNotasLiquidacao($sWhere = null, $lRetornaContasVinculadas = false) {

    $oDaoPagOrdem = db_utils::getDao("pagordem");
    $sCampos      = "e60_numemp,e60_numcgm,e60_codemp,e60_anousu,";
    $sCampos     .= "pagordemele.*, pagordem.*,cgm.z01_nome,'' as e80_codage,fc_valorretencao(e50_codord) as valorretencao,";
    $sCampos     .= "(SELECT coalesce(sum(case when k12_id is not null then 0 else e91_valor end),0) ";
    $sCampos     .= "   from empagemov                                                             ";
    $sCampos     .= "        inner join empord on empord.e82_codmov = empagemov.e81_codmov         ";
    $sCampos     .= "        inner join empageconf on empageconf.e86_codmov = empagemov.e81_codmov ";
    $sCampos     .= "        inner join empageconfche on e91_codmov = e81_codmov and e91_ativo is true     ";
    $sCampos     .= "        left join corconf on e91_codcheque = k12_codmov and corconf.k12_ativo is true ";
    $sCampos     .= "  where  e82_codord = e50_codord) as totalCheques                             ";
    $sSqlOrdens   = $oDaoPagOrdem->sql_query_pagordemele(null, $sCampos, "e50_codord",$sWhere);
    $rsOrdens     = $oDaoPagOrdem->sql_record($sSqlOrdens);
    $aNotas       = array();
    if ($oDaoPagOrdem->numrows > 0) {

      for ($iOrdens = 0; $iOrdens < $oDaoPagOrdem->numrows; $iOrdens++) {

        $oNotaLiquidacao = db_utils::fieldsMemory($rsOrdens, $iOrdens,false, false, $this->getUrlEncode());
        if ($lRetornaContasVinculadas) {

          $aContasVinculadas = $this->getContasRecurso($oNotaLiquidacao->e50_codord, $lRetornaContasVinculadas);
          $oNotaLiquidacao->aContasVinculadas = $aContasVinculadas;

        }
        $aNotas[] = $oNotaLiquidacao;
      }
    }
    return $aNotas;
  }

  /**
   * @param integer $iCodigoOrdem código da ordem
   * @param bool $lRetornaContasVinculadas
   *
   * @return array|\stdClass[]
   * @throws \Exception
   */
  public function getContasRecurso($iCodigoOrdem, $lRetornaContasVinculadas = true) {

    if (empty($iCodigoOrdem)) {
      throw new Exception("Metodo GetContasRecurso - Código do Empenho não informado.");
    }
    $sDataAtual     = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
    $sWhere         = " e50_codord = {$iCodigoOrdem} AND (k13_limite is null or k13_limite >= '{$sDataAtual}') ";
    $sCampos        = " distinct e83_conta, e83_descr,e83_codtipo,c61_codigo ";
    $sSqlContas     = $oDaoEmpAgeTipo->sql_query_contas_vinculadas(null, $sCampos, "e83_conta", $sWhere,$lRetornaContasVinculadas);
    /* [Extensão] - Filtro da Despesa - getContasRecurso */


    $rsContas       = $oDaoEmpAgeTipo->sql_record($sSqlContas);
    $aContas        = array();
    if ($oDaoEmpAgeTipo->numrows > 0 ) {
      $aContas = db_utils::getCollectionByRecord($rsContas,false,false, $this->getUrlEncode());
    }
    return $aContas;
  }

  /**
   * Define o encode das propriedades do tipo string
   *
   * @param unknown_type $lEncode
   */
  function setUrlEncode($lEncode) {

    if (is_bool($lEncode)) {
      $this->lUrlEncode = $lEncode;
    } else {
      throw new Exception("Metodo setUrlEncode - parametro deve ser do tipo boolean.");
    }
  }

  /**
   * Retorna o tipo do retorno das strings
   *
   * @return unknown
   */
  function getUrlEncode() {
    return $this->lUrlEncode;
  }

  /**
   * Retorna a string que deve ser impressa no verso dos cheques
   *
   * @param mixed $aMovimentos movimentos da agenda
   * @param string $sInformacaoExtra outras informações
   * @return  string verso do cheque
   */
  function getVersoCheque($aMovimentos = "", $sInformacaoExtra='') {

    //Buscamos a informação do nome do prefeito,
    $oDaoConfig = db_utils::getDao("db_config");
    $rsPref     = $oDaoConfig->sql_record($oDaoConfig->sql_query_file(db_getsession("DB_instit"),
                                                                      "pref as prefeito,munic as municipio"));
    $oPref        = db_utils::fieldsMemory($rsPref, 0);
    $oDaoCfAutent = db_utils::getDao("cfautent");
    $sSqlCfAutent = $oDaoCfAutent->sql_query_file(null,
                                                  "k11_tipoimpcheque,
                                                   k11_portaimpcheque,
                                                   k11_tesoureiro as tesoureiro",
                                                  "",
                                                  "k11_ipterm='".db_getsession("DB_ip")."'
                                                   and k11_instit=".db_getsession("DB_instit"));
    $rsCfAutent   = $oDaoCfAutent->sql_record($sSqlCfAutent);
    if ($oDaoCfAutent->numrows > 0) {
      $oCfAutent = db_utils::fieldsMemory($rsCfAutent, 0);
    } else {
      throw new Exception("Erro [1] - Cadastre seu IP com Autenticadora.");
    }
    $sCredor   = $sInformacaoExtra;
    $sStrVerso = "";
    //$sBanco =
    $aCgms    = array();
    if (count($aMovimentos) > 0) {

      $sIn      = "";
      $sVirgula = "";
      foreach ($aMovimentos as $oMovimento) {

        $sIn     .= $sVirgula.$oMovimento->iCodMov;
        $sVirgula  = ",";

      }
      $sSqlCgm  = "select  distinct case when a.z01_numcgm is not null then a.z01_numcgm ";
      $sSqlCgm .= "       when cgmslip.z01_numcgm is not null then cgmslip.z01_numcgm";
      $sSqlCgm .= "         else cgm.z01_numcgm end as z01_numcgm,";
      $sSqlCgm .= "        case when trim(a.z01_nome) is not null then a.z01_nome ";
      $sSqlCgm .= "             when trim(cgmslip.z01_nome) is not null then cgmslip.z01_nome";
      $sSqlCgm .= "        else cgm.z01_nome end as z01_nome";
      $sSqlCgm .= "  from empagemov ";
      $sSqlCgm .= "       left join empempenho  on  empempenho.e60_numemp = empagemov.e81_numemp ";
      $sSqlCgm .= "       left join empage  on  empage.e80_codage = empagemov.e81_codage ";
      $sSqlCgm .= "       left join empord  on  empord.e82_codmov = empagemov.e81_codmov ";
      $sSqlCgm .= "       left join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm ";
      $sSqlCgm .= "       left  join pagordemconta on pagordemconta.e49_codord = empord.e82_codord ";
      $sSqlCgm .= "       left  join cgm a on a.z01_numcgm = pagordemconta.e49_numcgm ";
      $sSqlCgm .= "       left  join empageslip on empageslip.e89_codmov = empagemov.e81_codmov ";
      $sSqlCgm .= "       left  join slip on empageslip.e89_codigo = slip.k17_codigo ";
      $sSqlCgm .= "       left  join slipnum on slipnum.k17_codigo = slip.k17_codigo ";
      $sSqlCgm .= "       left  join cgm cgmslip on slipnum.k17_numcgm = cgmslip.z01_numcgm ";
      $sSqlCgm .= " where e81_codmov in ({$sIn})";
      $rsNome   = db_query($sSqlCgm);
      if (pg_num_rows($rsNome) == 1) {

        $oCgm     = db_utils::fieldsMemory($rsNome,0);
        if ($oCgm->z01_numcgm != "") {

          $sCredor   .= "  ".$oCgm->z01_nome;
          $sSqlConta  = "select * from pcfornecon ";
          $sSqlConta .= " inner join pcforneconpad on pc64_contabanco = pc63_contabanco where pc63_numcgm={$oCgm->z01_numcgm}";
          $rsConta    = db_query($sSqlConta);
          $oConta     = db_utils::fieldsMemory($rsConta,0);
          $k13_descr  = "";
          if ($oConta->pc63_banco != "") {

            if(isset($oConta->pc63_banco) && trim($oConta->pc63_banco) == '001'){
              $k13_descr = 'BANCO DO BRASIL S/A';
            } else if(isset($oConta->pc63_banco) && trim($oConta->pc63_banco) == '041'){
              $k13_descr = 'BANRISUL S/A';
            } else if(isset($oConta->pc63_banco) && trim($oConta->pc63_banco) == '104'){
              $k13_descr = 'CAIXA ECONÔMICA FEDERAL';
            } else if(isset($oConta->pc63_banco) && trim($oConta->pc63_banco) == '008'){
              $k13_descr = 'SANTANDER S/A';
            } else if(isset($oConta->pc63_banco) && trim($oConta->pc63_banco) == '237'){
              $k13_descr = 'BRADESCO S/A';
            } else{
              $k13_descr = '.';
            }
            $oConta->pc63_agencia = str_replace("\n", '', $oConta->pc63_agencia);
            $oConta->pc63_agencia = str_replace("\r", '', $oConta->pc63_agencia);
            $sStrVerso .= '  Agencia:'.$oConta->pc63_agencia." - ".$oConta->pc63_agencia_dig;
            $sStrVerso .= '  Conta:'.$oConta->pc63_conta." - ".$oConta->pc63_conta_dig.' Banco:'.$k13_descr." \n  ";

          }
        }
      }
    }
    if (strlen($sCredor) > 65) {

      $sParteCredor  = $sCredor;
      $sStrVerso    .= '      ';

      while (strlen($sParteCredor) > 0) {

        $sStrVerso    .= substr($sParteCredor, 0, 65).' \n';
        $sParteCredor  = substr($sParteCredor, 65);

      }
    }else{
      $sStrVerso .= '      '.$sCredor;
    }

    $sStrVerso .= "\n";
    $sStrVerso .= "\n";
    $sStrVerso .= "             Prefeito:{$oPref->prefeito}  {$oCfAutent->tesoureiro}\n";
    return $sStrVerso;

  }

  /**
   * Retorna o saldo da conta na tesouraria
   *
   * @param integer $iCodTipo Conta codigo do tipo (empagetipo.e83_codtipo)
   * @param date    $dtBase data base para o calculo
   * @return float
   */
  function getSaldoConta($iCodTipoConta, $dtBase) {

    $dtBase         = implode("-",array_reverse(explode("/",$dtBase)));
    $clempagetipo   = db_utils::getdao("empagetipo");
    $sSqlConta      = $clempagetipo->sql_query_file($iCodTipoConta,"e83_conta");
    $rsConta        = $clempagetipo->sql_record($sSqlConta);
    $iConta         = db_utils::fieldsMemory($rsConta, 0)->e83_conta;
    $sSqlSaldoConta = "select * from fc_saldotesouraria('{$dtBase}'::date,'{$dtBase}'::date,{$iConta},null)";
    $rsSaldoConta = $clempagetipo->sql_record($sSqlSaldoConta);
    $oSaldoConta = db_utils::fieldsMemory($rsSaldoConta,0);
    return $oSaldoConta;

  }

  /**
   * Retorna o total de cheques emitidos.
   *
   * @param  integer $iCodTipoConta Conta codigo do tipo (empagetipo.e83_codtipo)
   * @return float
   */
  function getTotalCheques($iCodTipoConta) {

    $dtBase         = date("d/m/Y",db_getsession("DB_datausu"));
    $oSaldoTesouraria = $this->getSaldoConta($iCodTipoConta, $dtBase);
    return $oSaldoTesouraria->rnvalorcheques;

  }

  function setCodigoOrdemAuxiliar() {

  }
  /**
   * Verifica se a Nota de liquidação está agendada
   *
   * @param  integer $iCodNota código da nota de liquidação;
   * @return integer
   */
  function verificaAgendaNota($iCodNota) {

    $oAgenda             = new stdClass;
    $oAgenda->iCodAgenda = null;
    $oAgenda->iCodMov    = null;
    $oDaoAgenda          = db_utils::getDao("empage");
    $sWhere              = "e82_codord = {$iCodNota} and e81_cancelado is null and e80_instit = ".db_getsession("DB_instit");
    $sWhere             .= " and e91_codmov is null";
    $sSqlAgenda          = "select e80_codage, ";
    $sSqlAgenda         .= "       e82_codmov  ";
    $sSqlAgenda         .= "  from empage        ";
    $sSqlAgenda         .= "       inner join empagemov  on  empage.e80_codage    = empagemov.e81_codage ";
    $sSqlAgenda         .= "       left join empageconf  on empageconf.e86_codmov = empagemov.e81_codmov ";
    $sSqlAgenda         .= "       left join empord      on  empord.e82_codmov    = empagemov.e81_codmov ";
    $sSqlAgenda         .= "left join empageconfche on e91_codmov = e81_codmov and e91_ativo is true     ";
    $sSqlAgenda         .= " where {$sWhere}";
    $rsAgenda           = $oDaoAgenda->sql_record($sSqlAgenda);
    if ($oDaoAgenda->numrows > 0) {

      $oAgendaUtils = db_utils::fieldsMemory($rsAgenda, 0);
      $oAgenda->iCodAgenda = $oAgendaUtils->e80_codage;
      $oAgenda->iCodMov    = $oAgendaUtils->e82_codmov;
    }
    return $oAgenda;
  }

  /**
   * Adiciona um movimento a agenda.
   *
   * @param  integer $iTipo tipo do movimento 1 - nota de liquidacao 2 -slip
   * @param  object  $oObjeto objeto com informações da nota/slip que sera incluso;
   * @return integer
   */
  function addMovimentoAgenda($iTipo, $oObjeto) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }
    if (empty($iTipo)) {
      throw new Exception("Erro [1] Parametro iTipo nao pode ser nulo");
    }

    if (!is_object($oObjeto)) {
      throw new Exception("Erro [2] Parametro  oObjeto nao pode ser nulo");
    }
    if ($this->getiCodAgenda() == null) {

      $this->setCodigoAgenda($this->newAgenda());
    }


    if ($iTipo == 1) {

      /**
       * Descobrimos se o usuário passou o numemp ou o codemp,
       * caso passou o codemp, devemos procurar o número do do empenho.
       */
      if (strpos($oObjeto->iNumEmp,"/",0)) {

        $aEmpenho   = explode("/",$oObjeto->iNumEmp);
        $oDaoEmpenho = db_utils::getDao("empempenho");
        $sSqlEmpenho = $oDaoEmpenho->sql_query_file(null, "e60_numemp",
                                                    null,
                                                    " e60_codemp='{$aEmpenho[0]}'
                                                      and e60_anousu = '{$aEmpenho[1]}'");
        $rsEmpenho = $oDaoEmpenho->sql_record($sSqlEmpenho);
        if ($oDaoEmpenho->numrows > 0) {
          $oObjeto->iNumEmp = db_utils::fieldsMemory($rsEmpenho,0)->e60_numemp;
        } else {
          throw new Exception("Erro [7] - Não foi encontrado o empenho!");
        }
      }

      /*
       * incluimos na tabela empagemov.
       */
      $oDaoEmpAgeMov = db_utils::getDao("empagemov");
      $oDaoEmpAgeMov->e81_codage = $this->getiCodAgenda();
      $oDaoEmpAgeMov->e81_numemp = $oObjeto->iNumEmp;
      $oDaoEmpAgeMov->e81_valor  = $oObjeto->nValor;
      $oDaoEmpAgeMov->incluir(null);
      if ($oDaoEmpAgeMov->erro_status == 0) {

        $sMsg  = "Erro [4] - Movimento para Nota de liquidacao ({$oObjeto->iCodNota}) não incluido.\n";
        $sMsg .= "[Erro Técnico] - {$oDaoEmpAgeMov->erro_msg}";
        throw new Exception($sMsg);

      }

      $iCodMovimento = $oDaoEmpAgeMov->e81_codmov;
      /*
       * Incluimos na tabela empord, onde dizemos que esse movimento
       * e proviniente de uma nota de liquidação.
       */
      $oDaoEmpOrd             = db_Utils::getdao("empord");
      $oDaoEmpOrd->e82_codmov = $iCodMovimento;
      $oDaoEmpOrd->e82_codord = $oObjeto->iCodNota;
      $oDaoEmpOrd->incluir($iCodMovimento,$oObjeto->iCodNota);
      if ($oDaoEmpOrd->erro_status == 0) {

        $sMsg  = "Erro [5] - Movimento para Ordem de Pagamento ({$oObjeto->iCodNota}) não incluido.\n";
        $sMsg .= "[Erro Técnico] - {$oDaoEmpOrd->erro_msg}";
        throw new Exception($sMsg);

      }
      /*
       * Informamos a conta pagadora desse movimento, caso o usuário informou alguma conta.
       */
      if (isset($oObjeto->iCodTipo) && $oObjeto->iCodTipo != null) {

        $oDaoEmpAgePag = db_utils::getdao("empagepag");
        $oDaoEmpAgePag->incluir($iCodMovimento, $oObjeto->iCodTipo);
        if ($oDaoEmpAgePag->erro_status == 0) {

          $sMsg  = "Erro [6] - não foi possivel setar a conta pagadora para a";
          $sMsg .= "Nota de liquidacao ({$oObjeto->iCodNota}) não incluido.\n";
          $sMsg .= "[Erro Técnico] - {$oDaoEmpAgePag->erro_msg} sim erro aqui";
          throw new Exception($sMsg);

        }
      }
      /*
       * Caso foi informado , adicionamos a forma de pagamento ao movimento
       */
      if (isset($oObjeto->iForma) && (!empty($oObjeto->iForma))) {

        $oDaoEmpAgeMovForma = db_utils::getdao("empagemovforma");
        $oDaoEmpAgeMovForma->e97_codforma =  $oObjeto->iForma;
        $oDaoEmpAgeMovForma->incluir($iCodMovimento);
        if ($oDaoEmpAgeMovForma->erro_status == 0) {

          $sMsg  = "Erro [7] - não foi possivel setar a forma de pagamento a para a";
          $sMsg .= "Nota de liquidacao ({$oObjeto->iCodNota}) não incluido.\n";
          $sMsg .= "[Erro Técnico] - {$oDaoEmpAgeMovForma->erro_msg}";
          throw new Exception($sMsg);

        }
      }

      if (isset($oObjeto->iConcarPeculiar) && !empty($oObjeto->iConcarPeculiar)) {

        $oDaoEmpageConcar = db_utils::getDao("empageconcarpeculiar");
        $oDaoEmpageConcar->e79_concarpeculiar = $oObjeto->iConcarPeculiar;
        $oDaoEmpageConcar->e79_empagemov      = $iCodMovimento;
        $oDaoEmpageConcar->incluir(null);
        if ($oDaoEmpageConcar->erro_status == 0) {

          $sErroMsg = "Erro [8] - Erro ao vincular Caracteristica Peculiar (Movimento {$iCodMovimento})";
          throw new Exception($sErroMsg);
        }
      }

    } else if ($iTipo == 2) {

      /**
       * Movimentos de slip.
       * - Cadastramos empagemov, com o campo e81_numep = 0;
       * - cadastramos empageslip , com o codigo do slip,
       * - caso nao seje vaio, cadastramos  conta pagadora.
       */
      $oDaoEmpAgeMov = db_utils::getDao("empagemov");
      $oDaoEmpAgeMov->e81_codage = $this->getiCodAgenda();
      $oDaoEmpAgeMov->e81_numemp = "0";
      $oDaoEmpAgeMov->e81_valor  = $oObjeto->nValor;
      $oDaoEmpAgeMov->incluir(null);
      if ($oDaoEmpAgeMov->erro_status == 0) {

        $sMsg  = "Erro [4] - Movimento para SLIP ({$oObjeto->iCodigoSlip}) não incluido.\n";
        $sMsg .= "[Erro Técnico] - {$oDaoEmpAgeMov->erro_msg}";
        throw new Exception($sMsg);

      }

      $iCodMovimento  = $oDaoEmpAgeMov->e81_codmov;

      $oDaoEmpAgeSlip = db_utils::getDao("empageslip");
      $oDaoEmpAgeSlip->e89_codigo = $oObjeto->iCodigoSlip;
      $oDaoEmpAgeSlip->e89_codmov = $iCodMovimento;
      $oDaoEmpAgeSlip->incluir($iCodMovimento, $oObjeto->iCodigoSlip);
      if ($oDaoEmpAgeSlip->erro_status == 0) {

        $sMsg  = "Erro [5] - Movimento para SLIP ({$oObjeto->iCodNota}) não incluido.\n";
        $sMsg .= "[Erro Técnico] - {$oDaoEmpAgeSlip->erro_msg}";
        throw new Exception($sMsg);

      }

      if (isset($oObjeto->iCodTipo) && $oObjeto->iCodTipo != null) {

        $oDaoEmpAgePag = db_utils::getdao("empagepag");
        $oDaoEmpAgePag->incluir($iCodMovimento, $oObjeto->iCodTipo);
        if ($oDaoEmpAgePag->erro_status == 0) {

          $sMsg  = "Erro [6] - não foi possivel setar a conta pagadora para a";
          $sMsg .= "Nota de liquidacao ({$oObjeto->iCodNota}) não incluido.\n";
          $sMsg .= "[Erro Técnico] - {$oDaoEmpAgePag->erro_msg}";
          throw new Exception($sMsg);

        }
      }
      if (isset($oObjeto->iForma) && (!empty($oObjeto->iForma))) {

        $oDaoEmpAgeMovForma = db_utils::getdao("empagemovforma");
        $oDaoEmpAgeMovForma->e97_codforma =  $oObjeto->iForma;
        $oDaoEmpAgeMovForma->incluir($iCodMovimento);
        if ($oDaoEmpAgeMovForma->erro_status == 0) {

          $sMsg  = "Erro [7] - não foi possivel setar a forma de pagamento a para a";
          $sMsg .= "Nota de liquidacao ({$oObjeto->iCodNota}) não incluido.\n";
          $sMsg .= "[Erro Técnico] - {$oDaoEmpAgeMovForma->erro_msg}";
          throw new Exception($sMsg);

        }
      }
    }
    return $iCodMovimento;
  }

  /**
   * Adiciona uma nova agenda ;
   * @return void;
   */
  function newAgenda() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }
    /*
     * pesquisamos por uma agenda no dia.
     * caso houver, a usamos como nova agenda,
     * senao, incluimos uma nova;
     */

    $oDaoEmpAge = db_utils::getDao("empage");
    $dtDataDia  = date("Y-m-d",db_getsession("DB_datausu"));
    $sSqlAgenda = $oDaoEmpAge->sql_query_file(null, "*",
                                              "e80_codage desc limit 1",
                                              "e80_data = '{$dtDataDia}'
                                               and e80_instit = ".db_getsession("DB_instit"));
    $rsAgenda   = $oDaoEmpAge->sql_record($sSqlAgenda);
    if ($oDaoEmpAge->numrows > 0) {

      $iCodAgenda = db_utils::fieldsMemory($rsAgenda, 0)->e80_codage;
    } else {

      $oDaoEmpAge->e80_data   = $dtDataDia;
      $oDaoEmpAge->e80_instit = db_getsession("DB_instit");
      $oDaoEmpAge->incluir(null);
      if ($oDaoEmpAge->erro_status == 0) {
        throw new Exception("Erro [1] - Não foi possivel inclui agenda;\n{$oDaoEmpAge->erro_msg}");
      }
      $iCodAgenda = $oDaoEmpAge->e80_codage;
    }
    return $iCodAgenda;
  }

  /**
   * Retorna o codigo da Agenda
   *
   * @return integer Codigo da agenda
   */
  function getiCodAgenda() {
    return $this->iCodAgenda;
  }

  /**
   * Seta o codigo da Agenda;
   *
   * @param integer $iCodigoAgenda
   */
  function setCodigoAgenda($iCodigoAgenda) {
    $this->iCodAgenda = $iCodigoAgenda;
  }

  /**
   * Realizar a emissao de cheques.
   *
   * @param array   $aNotasLiquidacao notas de liquidacao a incluir no cheque.
   * @param sttring $dtData           data da emissao do cheque
   * @param string  $sCredor          nome do credor do cheque
   * @param array   $aCheques         quantidade de cheques a serem gerados.
   * @return array caso existir mais de um cheque, integer caso houver apenas um cheque
   */
  function emiteCheque($aNotasLiquidacao, $dtData, $sCredor='', $aCheques = null) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }
    if (!is_array($aNotasLiquidacao)) {
      throw new Exception("Erro [1] Parametro aNotaLiquidacao deve ser um Array");
    }

    if (empty($dtData)) {
      throw new Exception("Erro [2] Parametro dtNota não pode ser nulo");
    }

    $aSaldoMov = array();
    $dtEmissao = implode("-", array_reverse(explode("/", $dtData)));

    /*
     * Percorremos as notas selecionadas, e verificamos se elas possuem agenda.
     * caso sim, verificamos as outras notas para ter certeza que todas elas sao da mesma agenda,
     * ou nao possuam agenda.
     * as notas que nao possuem agenda, incluimos numa agenda de uma ordem selecionada , ou caso
     * nao há agenda em nenhuma nota, incluimos uma nova.
     */
    $iCodAgenda = null;
    $nValorCheque = 0;
    for ($iNotas = 0; $iNotas < count($aNotasLiquidacao); $iNotas++) {

      $oInfoAgendaNota  = $this->verificaAgendaNota($aNotasLiquidacao[$iNotas]->iCodNota);
      $iCodTipo         = $aNotasLiquidacao[$iNotas]->iCodTipo;
      $nValorCheque    += $aNotasLiquidacao[$iNotas]->nValor;
      $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo = $aNotasLiquidacao[$iNotas]->nValor;
      $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew  = false;

    }
    if (empty($iCodAgenda)) {
      $this->iCodAgenda = $this->newAgenda();
    } else {
      $this->iCodAgenda = $iCodAgenda;
    }

    /*
     * Comecamos a lancar os registros
     *  - Buscamos o próximo numero do cheque
     *  - Validamos o saldo da conta, com o valor total do cheque;
     */
    $oDaoEmpAgeTipo       = db_utils::getDao("empagetipo");
    $sSqlCodigoContaReduz = $oDaoEmpAgeTipo->sql_query_file($iCodTipo);
    $rsCodigoContaReduz   = $oDaoEmpAgeTipo->sql_record($sSqlCodigoContaReduz);

    if ($oDaoEmpAgeTipo->numrows == 0 ) {
      throw new Exception("Erro [4] - Conta Pagadora Inválida.\nOperação cancelada");
    }
    $sCredor = "";
    $oSaldoConta   = $this->getSaldoConta($iCodTipo, $dtEmissao);

    /*
     * Verifica parametro de controle do saldo das contas (caiparametro->k29_saldoemitechq)
     */
    $iControlaSaldoConta = 1; // O padrao é "Controlar Saldo da Conta"
    $aParametrosCaixa    = db_stdClass::getParametro("caiparametro",array(db_getsession("DB_instit")));
    if (count($aParametrosCaixa) > 0) {
      $iControlaSaldoConta = $aParametrosCaixa[0]->k29_saldoemitechq;
    }

    /*
     * Se for para controlar Saldo da Conta...
     */
    if ($iControlaSaldoConta == 1) {
      /*
       * Verificamos se há saldo na conta para emitir o cheque.
       */
      if ($oSaldoConta->rnsaldofinal < $nValorCheque) {
        throw new Exception("Erro [5] - Conta Pagadora sem saldo para efetuar a emissão do cheque.\nOperação cancelada");
      }
    }

    /*
     * caso nao houver numero de cheques definidos, incluimos apenas um
     *
     */
    $iCodConta        = db_utils::fieldsMemory($rsCodigoContaReduz, 0)->e83_conta;
    $NumeroCheque     = $oDaoEmpAgeTipo->getMaxCheque($iCodConta);
    if (count($aCheques) == 0 || $aCheques == null) {

      $aChequesLancar[] = array(
        "nValorCheque" => $nValorCheque,
        "iSeqCheque"   => $NumeroCheque,
      );
      $sDescrCheque = $NumeroCheque;

    } else {
      $sVirgula     = "";
      $sDescrCheque = "";
      for ($iNumCheques =0 ; $iNumCheques < count($aCheques); $iNumCheques++) {


        $sDescrCheque .= $sVirgula . $NumeroCheque;
        $aChequesLancar[] = array(
          "nValorCheque" => $aCheques[$iNumCheques],
          "iSeqCheque"   => $NumeroCheque,
        );
        $NumeroCheque++;
        $sVirgula = ", ";
      }
    }

    /**
     * geramos um arquivo com o cheque.
     */

    $odaoEmpageGera               = db_utils::getdao("empagegera");
    $odaoEmpageGera->e87_data     = $dtEmissao;
    $odaoEmpageGera->e87_dataproc = $dtEmissao;
    $odaoEmpageGera->e87_hora     = db_hora();
    $odaoEmpageGera->e87_descgera = "Arquivo  de cheque gerado";
    $odaoEmpageGera->incluir(null);
    if ($odaoEmpageGera->erro_status == 0) {

      $sErroMsg = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
      $sErroMsg = "[Erro Técnico] - $odaoEmpageGera->erro_msg";
      throw new Exception($sErroMsg);

    }

    $aMovimentosValidar = array();

    for ($iNotas = 0; $iNotas < count($aNotasLiquidacao); $iNotas++) {

      if (empty($aNotasLiquidacao[$iNotas]->iCodMov)) {

        $sMsgValidacao  = 'Não foi possível gravar o número do cheque! \n';
        $sMsgValidacao .= 'Tente repetir a operação e se o erro persistir contate suporte técnico.';
        throw new Exception($sMsgValidacao);
      } else {
        $aMovimentosValidar[] = $aNotasLiquidacao[$iNotas]->iCodMov;
      }
    }

    /*
     * verificamos se a nota possui movimento. se não houver
     * calculamos tbm o valor total dos itens, e setamos como o valor do cheque.
     */
    $lSemConf = true;

    for ($iNumCheques = 0; $iNumCheques < count($aChequesLancar); $iNumCheques++) {

      /*
       * definos a variavel que controla se já foi incluido empageconf
       *  (deve ter somente um por movimento. como podemos ter mais de um cheque,
       * devemos colocar no campo e86_cheque, a descrição que definimos no array acima.)
       * - Verificamos também, se o movimento da agenda já possui cheque.
       *   se possuir, devemos criar um movimento novo para essa ordem.
       */
      $nSaldoCheque      = $aChequesLancar[$iNumCheques]["nValorCheque"];
      $oDaoEmpageConfche = db_utils::getdao("empageconfche");
      while (round($nSaldoCheque,2) > 0) {

        for ($iNotas = 0; $iNotas < count($aNotasLiquidacao); $iNotas++) {

          if (!$aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew && $nSaldoCheque > 0
            && $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo > 0) {

            /*
             * O valor do movimento é igual ao da ordem. apenas incluimos o cheque,
             * e empageconfgera para o cheque.
             */
            if ($nSaldoCheque == $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo) {

              $oDaoEmpageConfche->e91_cheque = $aChequesLancar[$iNumCheques]["iSeqCheque"];
              $oDaoEmpageConfche->e91_codmov = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfche->e91_ativo  = "true";
              $oDaoEmpageConfche->e91_valor  = $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo;
              $oDaoEmpageConfche->incluir(null);
              if ($oDaoEmpageConfche->erro_status == 0 ) {

                $sErroMsg  = "Erro [6] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfche->erro_msg";
                throw new Exception($sErroMsg);

              }
              $oDaoEmpageConf = db_utils::getdao("empageconf");
              $oDaoEmpageConf->e86_cheque  = "{$aChequesLancar[$iNumCheques]["iSeqCheque"]}";
              $oDaoEmpageConf->e86_codmov  = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConf->e86_data    = $dtEmissao;
              $oDaoEmpageConf->e86_correto = "true";
              $oDaoEmpageConf->incluir($aNotasLiquidacao[$iNotas]->iCodMov);
              if ($oDaoEmpageConf->erro_status == 0) {

                $sErroMsg  = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConf->erro_msg";
                throw new Exception($sErroMsg);

              }
              $oDaoEmpageConfGera = db_utils::getdao("empageconfgera");
              $oDaoEmpageConfGera->e90_codgera   = $odaoEmpageGera->e87_codgera;
              $oDaoEmpageConfGera->e90_codmov    = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfGera->e90_correto   = "true";
              $oDaoEmpageConfGera->e90_cancelado = "false";
              $oDaoEmpageConfGera->incluir($aNotasLiquidacao[$iNotas]->iCodMov, $odaoEmpageGera->e87_codgera);
              if ($oDaoEmpageConfGera->erro_status == 0) {

                $sErroMsg  = "Erro [8] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfGera->erro_msg";
                throw new Exception($sErroMsg);

              }
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo -= $nSaldoCheque;
              $nSaldoCheque = 0;

            } else if (($nSaldoCheque > $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo)
              &&   $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo > 0) {

              /*
               * O valor do Cheque e maior, apenas incluimos o cheque e o empageconfgera
               */
              $oDaoEmpageConfche->e91_cheque = $aChequesLancar[$iNumCheques]["iSeqCheque"];
              $oDaoEmpageConfche->e91_codmov = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfche->e91_valor  = $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo;
              $oDaoEmpageConfche->e91_ativo  = "true";
              $oDaoEmpageConfche->incluir(null);
              if ($oDaoEmpageConfche->erro_status == 0 ) {

                $sErroMsg  = "Erro [6] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfche->erro_msg";
                throw new Exception($sErroMsg);

              }

              $oDaoEmpageConfGera = db_utils::getdao("empageconfgera");
              $oDaoEmpageConfGera->e90_codgera   = $odaoEmpageGera->e87_codgera;
              $oDaoEmpageConfGera->e90_codmov    = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfGera->e90_correto   = "true";
              $oDaoEmpageConfGera->e90_cancelado = "false";
              $oDaoEmpageConfGera->incluir($aNotasLiquidacao[$iNotas]->iCodMov, $odaoEmpageGera->e87_codgera);
              if ($oDaoEmpageConfGera->erro_status == 0) {

                $sErroMsg  = "Erro [8] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfGera->erro_msg";
                throw new Exception($sErroMsg);

              }
              $oDaoEmpageConf = db_utils::getdao("empageconf");
              $oDaoEmpageConf->e86_cheque  = "{$aChequesLancar[$iNumCheques]["iSeqCheque"]}";
              $oDaoEmpageConf->e86_codmov  = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConf->e86_data    = $dtEmissao;
              $oDaoEmpageConf->e86_correto = "true";
              $oDaoEmpageConf->incluir($aNotasLiquidacao[$iNotas]->iCodMov);
              if ($oDaoEmpageConf->erro_status == 0) {

                $sErroMsg  = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConf->erro_msg";
                throw new Exception($sErroMsg);

              }
              $nSaldoCheque         -= $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo;
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo = 0;

            } else if ($nSaldoCheque < $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo &&
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo > 0) {

              /*
               * Valor do Cheque é menor. Devemos modificar o movimento com  valor do cheque,
               * e marcar comecar a criar movimentos novos, com as mesmas informações
               * do movimento original  para o restante do cheque.
               */
              $oDaoEmpageConf = db_utils::getdao("empageconf");
              $oDaoEmpageConf->e86_cheque  = "{$aChequesLancar[$iNumCheques]["iSeqCheque"]}";
              $oDaoEmpageConf->e86_codmov  = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConf->e86_data    = $dtEmissao;
              $oDaoEmpageConf->e86_correto = "true";
              $oDaoEmpageConf->incluir($aNotasLiquidacao[$iNotas]->iCodMov);
              if ($oDaoEmpageConf->erro_status == 0) {

                $sErroMsg  = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConf->erro_msg";
                throw new Exception($sErroMsg);

              }
              $oDaoEmpageConfche->e91_cheque = $aChequesLancar[$iNumCheques]["iSeqCheque"];
              $oDaoEmpageConfche->e91_codmov = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfche->e91_valor  = $nSaldoCheque;
              $oDaoEmpageConfche->e91_ativo  = 't';
              $oDaoEmpageConfche->incluir(null);
              if ($oDaoEmpageConfche->erro_status == 0 ) {

                $sErroMsg  = "Erro [6] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfche->erro_msg";
                throw new Exception($sErroMsg);

              }

              $oDaoEmpageConfGera = db_utils::getdao("empageconfgera");
              $oDaoEmpageConfGera->e90_codgera   = $odaoEmpageGera->e87_codgera;
              $oDaoEmpageConfGera->e90_codmov    = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageConfGera->e90_correto   = "true";
              $oDaoEmpageConfGera->e90_cancelado = "false";
              $oDaoEmpageConfGera->incluir($aNotasLiquidacao[$iNotas]->iCodMov, $odaoEmpageGera->e87_codgera);
              if ($oDaoEmpageConfGera->erro_status == 0) {

                $sErroMsg  = "Erro [8] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfGera->erro_msg";
                throw new Exception($sErroMsg);

              }
              /*
               * alteramos o valor do movimento para o valor do cheque.
               * devemos somar junto os valores de retencao do movimento
               */

              $sSqlValorRetencao         = "select fc_valorretencaomov({$aNotasLiquidacao[$iNotas]->iCodMov},false) as valorretencao";
              $rsValorRetencao           = db_query($sSqlValorRetencao);
              $nValorRetencao            = db_utils::fieldsMemory($rsValorRetencao, 0)->valorretencao;
              $oDaoEmpageMov             = db_utils::getdao("empagemov");
              $oDaoEmpageMov->e81_valor  = $nSaldoCheque+$nValorRetencao;
              $oDaoEmpageMov->e81_codmov = $aNotasLiquidacao[$iNotas]->iCodMov;
              $oDaoEmpageMov->Alterar($aNotasLiquidacao[$iNotas]->iCodMov);
              if ($oDaoEmpageMov->erro_status == 0) {

                $sErroMsg  = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
                $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConf->erro_msg";
                throw new Exception($sErroMsg);

              }
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo -= $nSaldoCheque;
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew   = true;
              $nSaldoCheque = 0;

            }
          } else if ($aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew &&
            $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo > 0) {

            /**
             * Devemos criar um movimento novo com o valor do cheque, com as informações da  nota;
             */
            if ($aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo > $nSaldoCheque) {
              $nValorMovimentoNovo = $nSaldoCheque;
            } else {
              $nValorMovimentoNovo = $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo;
            }
            $oNovoMovimento = new stdClass();
            $oNovoMovimento->iCodTipo = $aNotasLiquidacao[$iNotas]->iCodTipo;
            $oNovoMovimento->iNumEmp  = $aNotasLiquidacao[$iNotas]->iNumEmp;
            $oNovoMovimento->nValor   = $nValorMovimentoNovo;
            $oNovoMovimento->iCodNota = $aNotasLiquidacao[$iNotas]->iCodNota;
            $oNovoMovimento->iForma   = 2;
            $iTipo = 1;
            if ($aNotasLiquidacao[$iNotas]->iNumEmp == 0) {

              $iTipo = 2;
              /*
               * Setamos o codigo do slip;
               */
              $sSqlSlip = "select e89_codigo from empageslip where e89_codmov = {$aNotasLiquidacao[$iNotas]->iCodMov}";
              $rsSlip   = db_query($sSqlSlip);
              $oNovoMovimento->iCodigoSlip = db_utils::fieldsMemory($rsSlip,0)->e89_codigo;

            }
            $iCodigoNovoMovimento     = $this->addMovimentoAgenda($iTipo, $oNovoMovimento);
            $oDaoEmpageConf = db_utils::getdao("empageconf");
            $oDaoEmpageConf->e86_cheque  = "{$aChequesLancar[$iNumCheques]["iSeqCheque"]}";
            $oDaoEmpageConf->e86_codmov  = $iCodigoNovoMovimento;
            $oDaoEmpageConf->e86_data    = $dtEmissao;
            $oDaoEmpageConf->e86_correto = "true";
            $oDaoEmpageConf->incluir($iCodigoNovoMovimento);
            if ($oDaoEmpageConf->erro_status == 0) {

              $sErroMsg  = "Erro [7] - Cheque não pode ser Emitido.\nOperação cancelada";
              $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConf->erro_msg";
              throw new Exception($sErroMsg);

            }
            $oDaoEmpageConfche->e91_cheque = $aChequesLancar[$iNumCheques]["iSeqCheque"];
            $oDaoEmpageConfche->e91_codmov = $iCodigoNovoMovimento;
            $oDaoEmpageConfche->e91_valor  = $nValorMovimentoNovo;
            $oDaoEmpageConfche->e91_ativo  = 'true' ;
            $oDaoEmpageConfche->incluir(null);
            if ($oDaoEmpageConfche->erro_status == 0 ) {

              $sErroMsg  = "Erro [6] - Cheque não pode ser Emitido.\nOperação cancelada";
              $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfche->erro_msg";
              throw new Exception($sErroMsg);

            }

            $oDaoEmpageConfGera = db_utils::getdao("empageconfgera");
            $oDaoEmpageConfGera->e90_codgera   = $odaoEmpageGera->e87_codgera;
            $oDaoEmpageConfGera->e90_codmov    = $iCodigoNovoMovimento;
            $oDaoEmpageConfGera->e90_correto   = "true";
            $oDaoEmpageConfGera->e90_cancelado = "false";
            $oDaoEmpageConfGera->incluir($iCodigoNovoMovimento, $odaoEmpageGera->e87_codgera);
            if ($oDaoEmpageConfGera->erro_status == 0) {

              $sErroMsg  = "Erro [8] - Cheque não pode ser Emitido.\nOperação cancelada";
              $sErroMsg .= "[Erro Técnico] - $oDaoEmpageConfGera->erro_msg";
              throw new Exception($sErroMsg);

            }
            if ($aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo <= $nSaldoCheque) {

              $nSaldoCheque  -= $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo;
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo = 0 ;
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew   = false;

            } else {

              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->nSaldo -= $nSaldoCheque;
              $nSaldoCheque      = 0;
              $aSaldoMov[$aNotasLiquidacao[$iNotas]->iCodMov]->isNew   = true;

            }

          }
          //if (round($nSaldoCheque,2) == 0) {
          //   Continue;
          //}
        }
      }
      /**
       * Atualizamos o valor da sequencia da conta
       */
      if (count($aCheques) == 0) {
        $NumeroCheque++;
      }
      $oDaoEmpAgeTipo->e83_codtipo   = $iCodTipo;
      $oDaoEmpAgeTipo->e83_sequencia = $NumeroCheque;
      $oDaoEmpAgeTipo->alterar($iCodTipo);
      if ($oDaoEmpAgeTipo->erro_status == 0) {

        $sErroMsg  = "Erro [9] - Cheque não pode ser Emitido.\nOperação cancelada";
        $sErroMsg .= "[Erro Técnico] - $oDaoEmpAgeTipo->erro_msg";
        throw new Exception($sErroMsg);

      }
    }

    $oDaoEmpageConf = db_utils::getdao("empageconf");
    foreach ($aMovimentosValidar as $iMovimentoValidar) {

      $sSqlEmpageConf  = $oDaoEmpageConf->sql_query_file(null,"*",null,"e86_codmov = {$iMovimentoValidar}");
      $rsSqlEmpageConf = $oDaoEmpageConf->sql_record($sSqlEmpageConf);
      if ($oDaoEmpageConf->numrows == 0) {

        $sMsgValidacao  = 'Não foi possível gravar o número do cheque! \n';
        $sMsgValidacao .= 'Tente repetir a operação e se o erro persistir contate suporte técnico.';
        throw new Exception($sMsgValidacao);
      }
    }

    return $aChequesLancar;
  }

  /**
   * cancela um cheque.
   *
   * @param integer $iCodMov Código do Movimento.
   * @return void
   *
   */
  function cancelarCheque($iCodMov) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }

    if (empty($iCodMov)) {
      throw new Exception("Erro [1] - Parametro oCheque deve ser um Objeto. Processo cancelado");
    }

    /**
     * Verificamos se o o cheque realmente foi emitido.
     */
    $oDaoEmpageconfChe = db_utils::getDao("empageconfche");
    $sSqlCheque        = $oDaoEmpageconfChe->sql_query_file(null,"*", null,"e91_codmov = {$iCodMov} and e91_ativo is true");
    $rsCheque          = $oDaoEmpageconfChe->sql_record($sSqlCheque);
    if ($oDaoEmpageconfChe->numrows == 0) {
      throw new Exception("Erro [2] - Cheque para o Movimento ({$iCodMov} não encontrado).");
    }

    $oDadosCheque = db_utils::fieldsMemory($rsCheque, 0);

    /*
     * Incluimos o cheque na tabela empageconfchecanc, e excluimos o cheque.
     */
    $oDaoEmpageconfCheCanc                = db_utils::getDao("empageconfchecanc");
    $oDaoEmpageconfCheCanc->e93_codcheque = $oDadosCheque->e91_codcheque;
    $oDaoEmpageconfCheCanc->e93_codmov    = $oDadosCheque->e91_codmov;
    $oDaoEmpageconfCheCanc->e93_cheque    = $oDadosCheque->e91_cheque;
    $oDaoEmpageconfCheCanc->e93_valor     = $oDadosCheque->e91_valor;
    $oDaoEmpageconfCheCanc->incluir($oDadosCheque->e91_codcheque);
    if ($oDaoEmpageconfCheCanc->erro_status==0) {
      throw new Exception("Erro [3] - não foi possível cancelar cheque ({$oDadosCheque->e91_cheque}).");
    }
    $oDaoEmpageconfChe->e91_ativo     = 'false';
    $oDaoEmpageconfChe->e91_codcheque = $oDadosCheque->e91_codcheque;
    $oDaoEmpageconfChe->alterar($oDadosCheque->e91_codcheque);
    //$oDaoEmpageconfChe->excluir($oDadosCheque->e91_codcheque);

    /*
     * Incluimos na empageconfcanc;
     */
    $oDaoEmpAgeConf = db_utils::getDao("empageconf");
    $sSqlConf       = $oDaoEmpAgeConf->sql_query_file($iCodMov);
    $rsConf         = $oDaoEmpAgeConf->sql_record($sSqlConf);
    if ($oDaoEmpAgeConf->numrows == 0) {
      throw new Exception("Erro [4] - Cheque para o Movimento ({$iCodMov} não encontrado).");
    }

    $oEmpAgeConf= db_utils::fieldsMemory($rsConf, 0);
    $oDaoEmpAgeConfCanc              = db_utils::getDao("empageconfcanc");
    $oDaoEmpAgeConfCanc->e88_codmov  = $iCodMov;
    $oDaoEmpAgeConfCanc->e88_data    = date("Y/m/d",db_getsession("DB_datausu"));
    $oDaoEmpAgeConfCanc->e88_cheque  = $oEmpAgeConf->e86_cheque;
    $oDaoEmpAgeConfCanc->e88_codgera = "0";
    $oDaoEmpAgeConfCanc->e88_seqerro = '0';
    $oDaoEmpAgeConfCanc->incluir($iCodMov);
    if ($oDaoEmpAgeConfCanc->erro_status == 0) {
      throw new Exception("Erro [5] - não foi possível cancelar cheque ({$oDadosCheque->e91_cheque}).");
    }
    $oDaoEmpAgeConf->excluir($iCodMov);
    if ($oDaoEmpAgeConf->erro_status == 0) {
      throw new Exception("Erro [5] - não foi possível cancelar cheque ({$oDadosCheque->e91_cheque}).");
    }

    /**
     * Deletamos da tabela empageconfgera
     */
    $oDaoEmpageConfGera = db_utils::getDao("empageconfgera");
    $sSqlGera           = $oDaoEmpageConfGera->sql_query_file(null,null,"*",null,"e90_codmov={$iCodMov} and e90_correto='t' and e90_cancelado is false ");
    //echo $sSqlGera; die();
    $rsGera             = $oDaoEmpageConfGera->sql_record($sSqlGera);
    if ($oDaoEmpageConfGera->numrows > 0) {

      $oGera = db_utils::fieldsMemory($rsGera, 0);

      $oDaoEmpageConfGera->e90_codmov    = $oGera->e90_codmov;
      $oDaoEmpageConfGera->e90_codgera   = $oGera->e90_codgera;
      $oDaoEmpageConfGera->e90_cancelado = 'true';
      $oDaoEmpageConfGera->alterar($oGera->e90_codmov, $oGera->e90_codgera);

      if ($oDaoEmpageConfGera->erro_status == 0) {
        throw new Exception("Erro [6] - não foi possível cancelar cheque ({$oDadosCheque->e91_cheque}).");
      }
    }
    $oDaoEmpAgeConf->excluir($iCodMov);
    return true;
  }
  /**
   * Autoriza os movimentos escolhidos para serem pagos.
   * Gera uma Ordem de pagamento.
   *
   * @param string $dtPagamento data para qual o pagamento foi autorizado
   * @param array  $aMovimentos array com os movimentos a serem autorizados deve ser no padrao chave/valor {codmov, valor}
   * @return codigo da autorização do pagamento
   */
  function autorizarPagamento($dtPagamento, $aMovimentos=null, $iTipoOperacao = 1, $iCodigoOPauxiliar = null) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }

    $aChavesObjetos = array("iCodMov","iCodNota","nValor");
    $dtPagamento  = implode("-",array_reverse(explode("/", $dtPagamento)));
    $oDaoOrdemPag = db_utils::getDao("empageordem");
    if ($iCodigoOPauxiliar == null) {

      $oDaoOrdemPag->e42_dtpagamento = $dtPagamento;
      $oDaoOrdemPag->incluir(null);
      $iCodigoAutorizacao = $oDaoOrdemPag->e42_sequencial;
      if ($oDaoOrdemPag->erro_status == 0) {

        $sErroMsg = "Erro [1] - Não foi possivel incluir agendamento.";
        throw new Exception($sErroMsg);

      }
    } else {

      $sSqlOPAuxiliar = $oDaoOrdemPag->sql_query_file($iCodigoOPauxiliar);
      $rsOpauxiliar   = $oDaoOrdemPag->sql_record($sSqlOPAuxiliar);
      if ($oDaoOrdemPag->numrows == 0) {

        $sErroMsg  = "Erro [2] - Ordem de pagamento Auxiliar ({$iCodigoOPauxiliar}) nao encontrada!\n";
        $sErroMsg .= "Procedimento cancelado.";
        throw new Exception($sErroMsg);

      }

      $iCodigoAutorizacao = $iCodigoOPauxiliar;
    }
    $iTotalNotas = count($aMovimentos);
    for ($i = 0; $i < $iTotalNotas; $i++) {

      /**
       * Verificamos se o movimento já nao está autorizado para pagamento.
       * Caso já esteje modificamos o valor da autorizacao  e do movimento para o novo.
       */
      $oDaoNotaOrdem = new cl_empagenotasordem;
      $sSqlOrdem     = $oDaoNotaOrdem->sql_query_file(null,
                                                      "*",
                                                      null,
                                                      "e43_ordempagamento = {$iCodigoAutorizacao}
                                                      and e43_empagemov   = {$aMovimentos[$i]->iCodMov}"
      );
      $rsOrdemPagamento = $oDaoNotaOrdem->sql_record($sSqlOrdem);
      $oDaoNotaOrdem->e43_ordempagamento = $iCodigoAutorizacao;
      $oDaoNotaOrdem->e43_empagemov      = $aMovimentos[$i]->iCodMov;
      $oDaoNotaOrdem->e43_autorizado     = "true";
      $oDaoNotaOrdem->e43_valor          = $aMovimentos[$i]->nValor;
      if ($oDaoNotaOrdem->numrows > 0) {

        $oDadosAutorizacao = db_utils::fieldsMemory($rsOrdemPagamento, 0);
        $oDaoNotaOrdem->e43_sequencial = $oDadosAutorizacao->e43_sequencial;
        $oDaoNotaOrdem->alterar($oDadosAutorizacao->e43_sequencial);
        if ($oDaoNotaOrdem->erro_status == 0) {

          $sErroMsg = "Erro [2] - Não foi possivel incluir agendamento.{$oDaoNotaOrdem->erro_msg}";
          throw new Exception($sErroMsg);

        }
        //        $oDaoEmpageMov = db_utils::getDao("empagemov");
        //        $sSqlMovimento = $oDaoEmpageMov->sql_query_file($aMovimentos[$i]->iCodMov);
        //        $rsMovimento   = $oDaoEmpageMov->sql_record($sSqlMovimento);
        //        $oMovimento    = db_utils::fieldsMemory($rsMovimento, 0);
        //        $oDaoEmpageMov->e81_valor  = $aMovimentos[$i]->nValor;
        //        $oDaoEmpageMov->e81_codmov = $aMovimentos[$i]->iCodMov;
        //        $oDaoEmpageMov->alterar($aMovimentos[$i]->iCodMov);
        //        if ($oDaoEmpageMov->erro_status == 0) {
        //
        //          $sErroMsg = "Erro [2] - Não foi possivel incluir agendamento.";
        //          throw new Exception($sErroMsg);
        //
        //        }
      } else {

        $oDaoNotaOrdem->incluir(null);
        if ($oDaoNotaOrdem->erro_status == 0) {

          $sErroMsg = "Erro [2] - Não foi possivel incluir agendamento.\n{$oDaoNotaOrdem->erro_msg}";
          throw new Exception($sErroMsg);

        }
      }

      /*
       * Consultamos o movimento para verificar so o usuário autorizou todo o valor,
       * caso nao tenha autorizado, é atualizado o movimento atual, para o valor autorizado,
       * e entao é criado um novo movimento, com o valor restante.
       */
      $oDaoEmpageMov = db_utils::getDao("empagemov");
      $sSqlMovimento = $oDaoEmpageMov->sql_query_file($aMovimentos[$i]->iCodMov);
      $rsMovimento   = $oDaoEmpageMov->sql_record($sSqlMovimento);
      $oMovimento    = db_utils::fieldsMemory($rsMovimento, 0);

      /*
       * Verificamos o valor autorizado com o a ordem..
       */
      if ($aMovimentos[$i]->nValor < $oMovimento->e81_valor) {

        $oDaoEmpageMov->e81_valor  = $aMovimentos[$i]->nValor;
        $oDaoEmpageMov->e81_codmov = $aMovimentos[$i]->iCodMov;
        $oDaoEmpageMov->alterar($aMovimentos[$i]->iCodMov);
        if ($oDaoEmpageMov->erro_status == 0) {

          $sErroMsg = "Erro [3] - Não foi possivel incluir agendamento.";
          throw new Exception($sErroMsg);

        }
        $this->setCodigoAgenda($oMovimento->e81_codage);
        $oNovoMovimento = new stdClass();
        $oNovoMovimento->iCodTipo = null;
        $oNovoMovimento->iNumEmp  = $oMovimento->e81_numemp;
        $oNovoMovimento->nValor   = round($oMovimento->e81_valor - $aMovimentos[$i]->nValor, 2);
        $oNovoMovimento->iCodNota = $aMovimentos[$i]->iCodNota;
        $this->addMovimentoAgenda($iTipoOperacao, $oNovoMovimento);
      }
    }
    return $iCodigoAutorizacao;
  }

  /**
   * Retorna os Movimentos da Agenda
   *
   * @param string $sWhere clausula where
   * @param string $sJoin joins extras
   * @return array com movimentos
   */
  function getMovimentosAgenda($sWhere = null, $sJoin, $lTrazfornecedor = true, $lTrazContaPagadora = true,$sCamposAdicionais='', $lVinculadas=true, $sCredorCgm = null) {

    $sOrderBy = "e81_codmov, e50_codord";
    if ($this->orderBy != null) {
      $sOrderBy = $this->orderBy;
    }

    $sWhereFiltroCgm = '';
    if (!empty($sCredorCgm)) {
      $sWhereFiltroCgm = " and empempenho.e60_numcgm = {$sCredorCgm} ";
    }
    $oDaoEmpAgeMov  = new cl_pagordem;

    /* [Extensão] - Filtro da Despesa */

    /*[Extensao] - Controle Interno*/

    /* [Extensao] - Solicitacao Repasse */

    $sSqlMovimentos = $oDaoEmpAgeMov->sql_query_empagemovforma(
      null,
      "
      empagemov.e81_codmov,
      (select e25_empagetipotransmissao from empagemovtipotransmissao where e25_empagemov = e81_codmov limit 1) as e25_empagetipotransmissao,
      e80_codage,
      case when a.z01_numcgm       is not null then a.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,
      case when trim(a.z01_nome)   is not null then a.z01_nome   else cgm.z01_nome   end as z01_nome,
      case when trim(a.z01_cgccpf) is not null then a.z01_cgccpf else cgm.z01_cgccpf end as z01_cgccpf,
      e50_data,
      e80_data,
      e60_anousu,
      e60_numemp,
      e60_codemp,
      o15_codigo,
      e86_data,
      e50_codord,
      e53_valor,
      o58_codigo,
      o58_orgao,
      o58_unidade,
      o58_localizadorgastos,
      e53_vlranu,
      k12_data,
      e91_cheque,
      e91_codmov,
      e71_codnota,
      e79_concarpeculiar,
      e60_concarpeculiar,
      e69_dtvencimento,
      (case when e85_codmov is null then
           (select e28_empagetipo
         from empageformacgm
        where e28_numcgm = e60_numcgm)
        else e85_codtipo end) as e85_codtipo,
      e97_codmov,
      e90_cancelado,
      case
        when e90_cancelado is true
          then null
        else e90_codmov
      end as e90_codmov,
      e98_contabanco,
      (case when e97_codforma is null then
       (select e97_codforma
              from empage
             INNER JOIN empagemov      ON empagemov.e81_codage = empage.e80_codage
             INNER JOIN empord         ON empord.e82_codmov = empagemov.e81_codmov
             INNER JOIN pagordem       ON pagordem.e50_codord = empord.e82_codord
             INNER JOIN pagordemele    ON pagordemele.e53_codord = pagordem.e50_codord
             INNER JOIN empempenho     ON empempenho.e60_numemp = pagordem.e50_numemp
             inner join empagemovforma on empagemovforma.e97_codmov   = empagemov.e81_codmov
             where empempenho.e60_instit = ".db_getsession("DB_instit") ."
                   {$sWhereFiltroCgm}
             order by e81_codmov desc limit 1)
        else e97_codforma end) as e97_codforma ,
      e42_dtpagamento,
      e53_vlrpag,
      round(e81_valor + (select coalesce(sum(e34_valordesconto), 0) from pagordemdesconto where e34_codord = e50_codord), 2) as e81_valor,
      e86_codmov,
      e43_sequencial,
      e42_sequencial,
      fc_validaretencoesmesanterior(e81_codmov,null) as validaretencao,
      fc_valorretencaomov(e81_codmov,false) as valorretencao,
      coalesce(e43_valor,0)  as e43_valor {$sCamposAdicionais}",
      $sOrderBy,
      $sWhere,
      $sJoin
    );

    $rsMovimento  = $oDaoEmpAgeMov->sql_record($sSqlMovimentos);
    $aNotas       = array();



    if ($oDaoEmpAgeMov->numrows > 0) {

      for ($iMovimentos = 0; $iMovimentos < $oDaoEmpAgeMov->numrows; $iMovimentos++) {

        $oMovimento        = db_utils::fieldsMemory($rsMovimento, $iMovimentos,false, false, $this->getUrlEncode());
        $oMovimento->validaretencao = $oMovimento->validaretencao=="t"?true:false;
        if ($lTrazContaPagadora) {
          $aContasVinculadas = $this->getContasRecurso($oMovimento->e50_codord, $lVinculadas);
          $oMovimento->aContasVinculadas = $aContasVinculadas;
        }
        if ($lTrazfornecedor) {
          $oMovimento->aContasFornecedor = $this->getContasFornecedor($oMovimento->z01_numcgm);
        }
        $aNotas[] = $oMovimento;
      }
    }


    return $aNotas;
  }


  function getMovimentosPagos($sWhere, $lTrazContaPagadora = true) {

    if (!empty($sWhere)) {
      $sWhere = " and {$sWhere} ";
    }
    $sSqlMovimentos  = "SELECT k105_data,";
    $sSqlMovimentos .= "       k105_autent,";
    $sSqlMovimentos .= "       fc_valorretencaomov(e81_codmov, true) as valorRetencao,";
    $sSqlMovimentos .= "       e50_codord,";
    $sSqlMovimentos .= "       e81_valor,";
    $sSqlMovimentos .= "       e81_codmov,";
    $sSqlMovimentos .= "       corrente.k12_valor,";
    $sSqlMovimentos .= "       k105_corgrupo,";
    $sSqlMovimentos .= "       e83_conta,";
    $sSqlMovimentos .= "       e85_codtipo,";
    $sSqlMovimentos .= "       e60_numemp,";
    $sSqlMovimentos .= "       e60_codemp,";
    $sSqlMovimentos .= "       e60_anousu,";
    $sSqlMovimentos .= "       z01_nome ";
    $sSqlMovimentos .= "  from corempagemov ";
    $sSqlMovimentos .= "         inner join corgrupocorrente on k105_data   = corempagemov.k12_data ";
    $sSqlMovimentos .= "                                    and k105_id     = corempagemov.k12_id ";
    $sSqlMovimentos .= "                                    and k105_autent = corempagemov.k12_autent ";
    $sSqlMovimentos .= "         inner join corrente         on k105_data   = corrente.k12_data ";
    $sSqlMovimentos .= "                                    and k105_id     = corrente.k12_id ";
    $sSqlMovimentos .= "                                     and k105_autent = corrente.k12_autent ";
    $sSqlMovimentos .= "          inner join empagemov       on k12_codmov  = e81_codmov ";
    $sSqlMovimentos .= "          inner join empord          on e82_codmov  = e81_codmov ";
    $sSqlMovimentos .= "          inner join pagordem        on e82_codord  = e50_codord ";
    $sSqlMovimentos .= "          inner join empempenho      on e60_numemp  = e50_numemp ";
    $sSqlMovimentos .= "          inner join cgm             on z01_numcgm  = e60_numcgm ";
    $sSqlMovimentos .= "          inner join empagepag       on e81_codmov  = e85_codmov ";
    $sSqlMovimentos .= "          inner join empagetipo      on e85_codtipo = e83_codtipo ";
    $sSqlMovimentos .= "          inner join empagemovforma  on e81_codmov  = e97_codmov ";
    $sSqlMovimentos .= "  where k105_corgrupotipo = 1 ";
    $sSqlMovimentos .= "    and e97_codforma <> 3  {$sWhere} ";
    $sSqlMovimentos .= "  order by k105_data ,k105_autent";
    $oDaoEmpAgeMov   = db_utils::getDao("pagordem");
    $rsMovimento = $oDaoEmpAgeMov->sql_record($sSqlMovimentos);
    $aNotas       = array();
    if ($oDaoEmpAgeMov->numrows > 0) {

      for ($iMovimentos = 0; $iMovimentos < $oDaoEmpAgeMov->numrows; $iMovimentos++) {

        $oMovimento        = db_utils::fieldsMemory($rsMovimento, $iMovimentos,false, false, $this->getUrlEncode());
        $aContasVinculadas = $this->getContasRecurso($oMovimento->e50_codord);
        if ($lTrazContaPagadora) {
          $oMovimento->aContasVinculadas = $aContasVinculadas;
        }
        $aNotas[] = $oMovimento;
      }
    }
    return $aNotas;
  }
  /**
   * Retorna as contas bancarias dos fornecedores
   *
   * @param  integer $iNumCgm cgm do fornecedor
   * @return array com as contas
   */
  function getContasFornecedor($iNumCgm) {

    $oDaoPcForne = db_utils::getDao("pcfornecon");
    $iAnoUsu     = db_getsession("DB_anousu");
    $sSqlConta   = $oDaoPcForne->sql_query_lefpadrao(null,
                                                     "
                                                      pc63_agencia,
                                                      pc63_agencia_dig,
                                                      pc63_banco,
                                                      pc63_conta,
                                                      pc63_conta_dig,
                                                      pc63_contabanco,
                                                      (select e98_contabanco
                                                         from empempenho
                                                              inner join empagemov on empempenho.e60_numemp = empagemov.e81_numemp
                                                              inner join empagemovconta on e98_codmov = e81_codmov
                                                         where  empempenho.e60_numcgm = pc63_numcgm
                                                            and e60_anousu = {$iAnoUsu}
                                                         order by e60_numemp desc limit 1) as conta_historico_fornecedor,
                                                     case when
                                                       pc64_contabanco is not null
                                                         then true
                                                       else false
                                                       end as padrao,
                                                     pc63_dataconf
                                                      ",'',"pc63_numcgm={$iNumCgm}");

    $rsContas  = $oDaoPcForne->sql_record($sSqlConta);
    if ($oDaoPcForne->numrows > 0) {
      return db_utils::getCollectionByRecord($rsContas,false,false, $this->getUrlEncode());
    }
  }

  /**
   * Cria a configuração do pagamento para o movimento.
   *
   * @param string   $dtPagamento data do pagamento
   * @param stdClass $oMovimento objeto com os dados do movimento.
   * @param integer  $iCodigoOrdemAuxiliar  define qual ordem auxilar o movimento ira compor.
   *                                        caso nulo ira procurar a ultima ordem do dia e ira por na ordme encontrada
   */
  public function configurarPagamentos($dtPagamento, stdClass $oMovimento, $iCodigoOrdemAuxiliar = null, $lGerarOpAuxiliar=true) {


    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }

    if (!is_object($oMovimento)) {
      throw new Exception("Erro [1] - Parametro oMovimento deve ser um Objeto. Processo cancelado");
    }

    $dtPagamento     = implode("-",array_reverse(explode("/", $dtPagamento)));
    /**
     * Verificamos a forma do pagamento. caso seja 0 - NDA,
     * devemos cancelar as configurações para esse movimento.
     *  excluir das tabelas (empageconf, empagepag, empageconta, empagemovforma)
     */

    if ($oMovimento->iCodForma == 0) {

      $oDaoEmpAgePag = db_utils::getDao("empagepag");
      $sSqlSlip      = "select 1 from empageslip where e89_codmov = {$oMovimento->iCodMov}";
      $rsSlip        = db_query($sSqlSlip);
      if (pg_num_rows($rsSlip) == 0) {

        $oDaoEmpAgePag->excluir($oMovimento->iCodMov);
        if ($oDaoEmpAgePag->erro_status == 0) {

          $sErroMsg = "Erro [1] - Erro ao cancelar conta pagadora (Movimento {$oMovimento->iCodMov})";
          throw new Exception($sErroMsg);

        }
      }

      $oDaoEmpageMovConta = db_utils::getDao("empagemovconta");
      $oDaoEmpageMovConta->excluir($oMovimento->iCodMov);
      if ($oDaoEmpageMovConta->erro_status == 0) {

        $sErroMsg = "Erro [2] - Erro ao cancelar conta recebedora (Movimento {$oMovimento->iCodMov})";
        throw new Exception($sErroMsg);

      }

      $oDaoEmpageConf              = db_utils::getDao("empageconf");
      $oDaoEmpageConf->excluir($oMovimento->iCodMov);
      if ($oDaoEmpageConf->erro_status == 0) {

        $sErroMsg = "Erro [3] - Erro ao cancelar Confirmação de Pagamento  (Movimento {$oMovimento->iCodMov})";
        throw new Exception($sErroMsg);
      }


      $oDaoEmpagemovForma = db_utils::getDao("empagemovforma")  ;
      $oDaoEmpagemovForma->excluir($oMovimento->iCodMov);
      if ($oDaoEmpagemovForma->erro_status == 0) {

        $sErroMsg = "Erro [4] - Erro ao cancelar Forma de Pagamento (Movimento {$oMovimento->iCodMov})";
        throw new Exception($sErroMsg);
      }

    } else {

      if (!empty($oMovimento->iContaSaltes)) {

        if ($oMovimento->iCodForma <> agendaPagamento::FORMA_PAGAMENTO_DINHEIRO) {

          $oContaPlano = ContaPlanoPCASPRepository::getContaPorReduzido($oMovimento->iContaSaltes, db_getsession('DB_anousu'), new Instituicao(db_getsession('DB_instit')));
          if ($oContaPlano->getSistemaConta()->getCodigoSistemaConta() == 5) {

            $sMensagem = "Não é possível atualizar o movimento selecionando uma conta CAIXA e forma de pagamento diferente de DIN.";
            throw new Exception($sMensagem);
          }
        }
      }

      /**
       * Caso a forma de pagamento for dinheiro, o usuario pode apenas escolher contas
       * do sistema FINANCEIRO - CAIXA  (conplano.c60_codsis = 5)
       */
      if ($oMovimento->iCodForma == agendaPagamento::FORMA_PAGAMENTO_DINHEIRO && $oMovimento->iContaPagadora != "") {

        $oDaoEmpageTipo = new cl_empagetipo();
        $sSqlConplano   = $oDaoEmpageTipo->sql_query_conplano($oMovimento->iContaPagadora,"c60_codsis");
        $rsConplano     = $oDaoEmpageTipo->sql_record($sSqlConplano);
        if ($oDaoEmpageTipo->numrows > 0) {

          $oPlanoContas = db_utils::fieldsMemory($rsConplano,0);
          if ($oPlanoContas->c60_codsis != 5) {

            $sMsg  = "Para pagamentos em dinheiro, deve ser usada uma\nconta classificada ";
            $sMsg .= "como FINANCEIRO CAIXA.\nMovimento {$oMovimento->iCodMov} ";
            $sMsg .= "da Ordem {$oMovimento->iCodNota} com conta inconsistente." ;
            throw new Exception($sMsg);
          }
        } else {
          throw new Exception("Conta não encontrada no plano de contas");
        }
      }
      /*
       * Criamos a ordem de pagamento auxiliar caso a ordem nao exista ou o parametro lCriarOrdemAuxiliar
       * for true,caso seje o contrario, incluimos na ordem já existente.
       */
      if ($lGerarOpAuxiliar) {

        if (empty($iCodigoOrdemAuxiliar)) {

          /*
           * Pesquisamos a ordem do dia.
           */
          $oDaoOrdemAgenda = db_utils::getDao("empageordem");
          $sSqlAgendaDoDia = $oDaoOrdemAgenda->sql_query_file(null,"*",null,"e42_dtpagamento = '{$dtPagamento}'");
          $rsAgendaDoDia   = $oDaoOrdemAgenda->sql_record($sSqlAgendaDoDia);

          if ($oDaoOrdemAgenda->numrows > 0) {
            $oDaoOrdemAgenda->e42_sequencial = db_utils::fieldsMemory($rsAgendaDoDia, 0)->e42_sequencial;
          } else {

            $oDaoOrdemAgenda->e42_dtpagamento = $dtPagamento;
            $oDaoOrdemAgenda->incluir(null);
            if ($oDaoOrdemAgenda->erro_status == 0) {

              $sErroMsg = $oDaoOrdemAgenda->erro_msg;
              throw  new Exception($sErroMsg);

            }
          }

        } else {
          $oDaoOrdemAgenda->e42_sequencial = $iCodigoOrdemAuxiliar;
        }
      }

      /**
       * Verifica se o andamento é de slip
       */
      $oDaoEmpAgeSlip = db_utils::getDao("empageslip");
      $sSqlEmpageSlip = $oDaoEmpAgeSlip->sql_query_file($oMovimento->iCodMov);
      $rsEmpageSlip   = $oDaoEmpAgeSlip->sql_record($sSqlEmpageSlip);
      $lMovimentoSlip = false;
      if ($oDaoEmpAgeSlip->numrows > 0) {
        $iCodigoSlip  =  db_utils::fieldsMemory($rsEmpageSlip, 0)->e89_codigo;
        $lMovimentoSlip = true;
      }


      /*
       * Consultamos o movimento para verificar so o usuário autorizou todo o valor,
       * caso nao tenha autorizado, é atualizado o movimento atual, para o valor autorizado,
       * e entao é criado um novo movimento, com o valor restante.
       */

      $oDaoEmpageMov     =  db_utils::getDao("empagemov");
      $sSqlMovimento     = $oDaoEmpageMov->sql_query_file($oMovimento->iCodMov);
      $rsMovimento       = $oDaoEmpageMov->sql_record($sSqlMovimento);
      $oMovimentoOriginal = db_utils::fieldsMemory($rsMovimento, 0);
      if (round(($oMovimento->nValor+$oMovimento->nValorRetencao),2) < $oMovimentoOriginal->e81_valor) {

        $oDaoEmpageMov->e81_valor  = $oMovimento->nValor;
        $oDaoEmpageMov->e81_codmov = $oMovimento->iCodMov;
        $oDaoEmpageMov->alterar($oMovimento->iCodMov);
        if ($oDaoEmpageMov->erro_status == 0) {

          $sErroMsg = "Erro [2] - Não foi possivel incluir agendamento.";
          throw new Exception($sErroMsg);

        }
        /*
         * modificamos o valor autorizado para o valor definido pelo usuário
         */
        $oDaoEmpageNotas = db_utils::getDao("empagenotasordem");
        $sSqlAutoriza    = $oDaoEmpageNotas->sql_query_file(null,"e43_sequencial",null,"e43_empagemov={$oMovimento->iCodMov}");
        $rsAutoriza      = $oDaoEmpageNotas->sql_record($sSqlAutoriza);
        if ($oDaoEmpageNotas->numrows > 0) {

          $oAutorizacao = db_utils::fieldsMemory($rsAutoriza, 0);
          $oDaoEmpageNotas->e43_valor      = round(($oMovimento->nValor+$oMovimento->nValorRetencao),2);
          $oDaoEmpageNotas->e43_sequencial = $oAutorizacao->e43_sequencial;
          $oDaoEmpageNotas->alterar($oAutorizacao->e43_sequencial);

        }
        $this->setCodigoAgenda($oMovimentoOriginal->e81_codage);
        $oNovoMovimento = new stdClass();
        $oNovoMovimento->iCodTipo = null;
        $oNovoMovimento->iNumEmp  = $oMovimentoOriginal->e81_numemp;
        $oNovoMovimento->nValor   = round($oMovimentoOriginal->e81_valor - $oMovimento->nValor, 2);
        $oNovoMovimento->iCodNota = $oMovimento->iCodNota;

        if ( $lMovimentoSlip ){
          $oNovoMovimento->iCodigoSlip = $iCodigoSlip;
          $iCodigoNovoMovimento = $this->addMovimentoAgenda(2, $oNovoMovimento);
        } else {
          $iCodigoNovoMovimento = $this->addMovimentoAgenda(1, $oNovoMovimento);
        }
      }

      /*
       * Definimos  conta pagadora do Movimento
       */
      if ($oMovimento->iCodForma != 1 && $oMovimento->iContaPagadora == "") {
        throw new Exception("Movimento ({$oMovimento->iCodMov}) da Nota {$oMovimento->iCodNota} sem conta pagadora");
      } else {

        /**
         * Devemos pesquisar o tipo da conta.
         * para pagamentos em dinheiro , apenas podemos pegar contas do codigo
         */
        $oDaoEmpAgePag = db_utils::getDao("empagepag");
        $oDaoEmpAgePag->excluir($oMovimento->iCodMov);
        $oDaoEmpAgePag->incluir($oMovimento->iCodMov, $oMovimento->iContaPagadora);
        if ($oDaoEmpAgePag->erro_status == 0) {

          $sErroMsg = "Erro [3] - Erro Ao Definir conta pagadora (Movimento {$oMovimento->iCodMov})";
          throw new Exception($sErroMsg);

        }
      }
      /*
       * incluimos conta do fornecedor, caso a forma de pagamento seja transmissao
       *  - ** caso a conta do fornecedor seja a string con, nao devemos usar
       *       a conta do fornecedor, pois essa conta é de um slip da prefeitura.
       */
      if (!empty($oMovimento->iContaFornecedor) && $oMovimento->iContaFornecedor != "con") {

        /*
         * alterada a validação para que seja possivel cadastrar TRA sem conta
         */
        $oDaoEmpageMovConta = db_utils::getDao("empagemovconta");
        $oDaoEmpageMovConta->excluir($oMovimento->iCodMov);
        $oDaoEmpageMovConta->e98_contabanco = $oMovimento->iContaFornecedor;
        $oDaoEmpageMovConta->incluir($oMovimento->iCodMov);
        if ($oDaoEmpageMovConta->erro_status == 0) {

          $sErroMsg = "Erro [4] - Erro Ao Definir conta do fornecedor (Movimento {$oMovimento->iCodMov})";
          throw new Exception($sErroMsg);

        }
      }

      /*
       * exlcuimos, e depois incluimos na tabela empageconf,
       */
      $oDaoEmpageConf              = db_utils::getDao("empageconf");
      $oDaoEmpageConf->excluir($oMovimento->iCodMov);
      if ($oMovimento->iCodForma == 3 || $oMovimento->iCodForma == 1 || $oMovimento->iCodForma == 4) {

        $oDaoEmpageConf->e86_cheque  = "0";
        $oDaoEmpageConf->e86_data    = $dtPagamento;
        $oDaoEmpageConf->e86_codmov  = $oMovimento->iCodMov;
        $oDaoEmpageConf->e86_correto = "true";
        $oDaoEmpageConf->incluir($oMovimento->iCodMov);
        if ($oDaoEmpageConf->erro_status == 0) {

          $sErroMsg = "Erro [5] - Erro Ao Definir configuração da conta (Movimento {$oMovimento->iCodMov}.{$oDaoEmpageConf->erro_msg})";
          throw new Exception($sErroMsg);

        }
      }
      /*
       * Incluimos a forma de pagamento
       */
      $oDaoEmpagemovForma = db_utils::getDao("empagemovforma")  ;
      $oDaoEmpagemovForma->excluir($oMovimento->iCodMov);
      $oDaoEmpagemovForma->e97_codforma = $oMovimento->iCodForma;
      $oDaoEmpagemovForma->incluir($oMovimento->iCodMov);
      if ($oDaoEmpagemovForma->erro_status == 0) {

        $sErroMsg = "Erro [6] - Erro Ao Definir forma de pagamento  (Movimento {$oMovimento->iCodMov})";
        throw new Exception($sErroMsg);

      }

      $e42_sequencial = null;
      if (isset($oDaoOrdemAgenda)) {
        $e42_sequencial = $oDaoOrdemAgenda->e42_sequencial;
      }
      if ($lGerarOpAuxiliar || $e42_sequencial != null) {

        $oDaoNotasOrdem = db_utils::getDao("empagenotasordem");
        $sSqlAgenda  = $oDaoNotasOrdem->sql_query(null,"e43_sequencial",
                                                  null,
                                                  "e43_empagemov = {$oMovimento->iCodMov}"
        );
        $rsAgenda  = $oDaoNotasOrdem->sql_record($sSqlAgenda);
        if ($oDaoNotasOrdem->numrows == 0) {

          $oDaoNotasOrdem->e43_autorizado     = "true";
          $oDaoNotasOrdem->e43_valor          = $oMovimento->nValor+$oMovimento->nValorRetencao;
          $oDaoNotasOrdem->e43_ordempagamento = $oDaoOrdemAgenda->e42_sequencial;
          $oDaoNotasOrdem->e43_empagemov      = $oMovimento->iCodMov;
          $oDaoNotasOrdem->incluir(null);
          if ($oDaoNotasOrdem->erro_status == 0) {

            throw  new Exception($oDaoNotasOrdem->erro_msg);
          }
        }
      }
      $oDaoPagOrdem     = db_utils::getDao("pagordem");
      $rsNumCgmOrdem    = $oDaoPagOrdem->sql_record($oDaoPagOrdem->sql_query($oMovimento->iCodNota,"e60_numcgm"));
      if ($oDaoPagOrdem->numrows > 0) {

        $iNumCgm          = db_utils::fieldsMemory($rsNumCgmOrdem,0)->e60_numcgm;
        $this->setFormaPagamentoCGM($iNumCgm, $oMovimento->iCodForma);
        $this->setContaPagadoraCgm($iNumCgm, $oMovimento->iContaPagadora);

      }

      /**
       * Verifica se foi criado um novo movimento ou se apenas foi editado o movimento.
       */
      $iCodigoMovimento = $oMovimento->iCodMov;
      if ( isset($iCodigoNovoMovimento) ){
        $iCodigoMovimento = $iCodigoNovoMovimento;
      }

      /**
       * Cria uma nova instancia do objeto empageconcarpeculiar e salva os dados na respectiva tabela.
       */
      $oDaoEmpageConcar                     = db_utils::getDao("empageconcarpeculiar");
      $oDaoEmpageConcar->excluir(null, "e79_empagemov      = {$iCodigoMovimento}");

      if (!$lMovimentoSlip) {
        if (trim($oMovimento->sConCarPeculiar) == "") {

          $sMsg  = "C. Peculiar/Cod. de Aplicação para o ";
          $sMsg .= "Movimento {$oMovimento->iCodMov} ";
          $sMsg .= "da Ordem {$oMovimento->iCodNota} não informado." ;
          throw new Exception($sMsg);
        }
        $oDaoEmpageConcar->e79_concarpeculiar = $oMovimento->sConCarPeculiar;
        $oDaoEmpageConcar->e79_empagemov      = $iCodigoMovimento;
        $oDaoEmpageConcar->incluir(null);

        if ( $oDaoEmpageConcar->erro_status == "0" ) {
          throw new Exception("Erro [7] - Erro ao configurar dados em empageconcarpeculiar.");
        }
      }
    }
  }

  /**
   * Retorna todas as movimentos que possui transferencias a fazer
   *
   * @param string $dtInicial data inicial no formato "dd/mm/yyyy"
   * @param string $dtFinal data final no formato "dd/mm/yyyy";
   * @param integer $iAgrupar Agrupa o valor por tipo 1 - apenas conta debito/credito
   *                                                  2 - Op/credito/debito
   *                                                  3 - nao agrupa
   * @param boolean $lComSlip se já foi gerado slip para esse movimento.
   * @return array
   */

  public function getMovimentosSlip($dtInicial, $dtFinal, $iAgrupar = 1, $iOrdemPagamento = null,
                                    $lComSlip = false,  $iCtaCredito= null, $iCtaDebito = null, $sWhere= null) {

    $sCampoValor = "k107_valor,k107_empagemov, k107_sequencial";
    $sGroup      = "";
    $sDataIni    = implode("-", array_reverse(explode("/", $dtInicial)));
    $sDataFim    = implode("-", array_reverse(explode("/", $dtFinal)));
    $sInner      = "";
    $aMovimentos = array();
    if ($iAgrupar == 1) {

      $sCampoValor = "sum(k107_valor) as k107_valor";
      $sGroup      = "group by k107_ctacredito,k107_ctadebito, credito.k13_descr,debito.k13_descr";

    } else if ($iAgrupar == 2) {

      $sCampoValor = "e82_codord, sum(k107_valor) as k107_valor";
      $sGroup      = "group by e82_codord,k107_ctacredito,k107_ctadebito, credito.k13_descr,debito.k13_descr";

    }
    $oDaoEmpagemovslip = db_utils::getDao("empagemovslips");
    $sSqlMovimentos    = "select k107_ctacredito,               ";
    $sSqlMovimentos   .= "       credito.k13_descr as creditar, ";
    $sSqlMovimentos   .= "       k107_ctadebito,                ";
    $sSqlMovimentos   .= "       debito.k13_descr as debitar,   ";
    $sSqlMovimentos   .= "       {$sCampoValor}                 ";
    $sSqlMovimentos   .= "  from empagemovslips                 ";
    $sSqlMovimentos   .= "       inner join saltes  debito      on debito.k13_conta    = k107_ctadebito   ";
    $sSqlMovimentos   .= "       inner join saltes  credito     on credito.k13_conta   = k107_ctacredito  ";
    $sSqlMovimentos   .= "       left  join slipempagemovslips  on k108_empagemovslips =  k107_sequencial ";
    $sSqlMovimentos   .= "       inner join empord              on k107_empagemov      = e82_codmov       ";
    $sSqlMovimentos   .= "       inner join empagemov           on k107_empagemov      = e81_codmov       ";
    $sSqlMovimentos   .= "       inner join empempenho          on e60_numemp          = e81_numemp       ";
    $sSqlMovimentos   .= "       inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
    $sSqlMovimentos   .= " where e60_instit = ".db_getsession("DB_instit");
    $sSqlMovimentos   .= "   and k107_data between '{$sDataIni}' and '{$sDataFim}'";
    if ($lComSlip) {
      $sSqlMovimentos .= "   and k108_sequencial is not null ";
    } else {
      $sSqlMovimentos .= "   and k108_sequencial is null ";
    }
    if ($iCtaCredito != null) {
      $sSqlMovimentos .= " and k107_ctacredito = {$iCtaCredito}";
    }
    if ($iCtaDebito != null) {
      $sSqlMovimentos .= " and k107_ctaDebito = {$iCtaDebito}";
    }
    if ($iOrdemPagamento != null) {
      $sSqlMovimentos .= " and e82_codord = {$iOrdemPagamento}";
    }
    if ($sWhere != null) {
      $sSqlMovimentos  .= "and {$sWhere}";
    }

    /* [Extensão] - Filtro da Despesa getMovimentosSlip */


    $sSqlMovimentos   .= " {$sGroup} ";
    $rsMovimentos     = $oDaoEmpagemovslip->sql_record($sSqlMovimentos);
    if ($oDaoEmpagemovslip->numrows > 0) {
      $aMovimentos = db_utils::getCollectionByRecord($rsMovimentos,false,false, $this->getUrlEncode());
    }
    return $aMovimentos;
  }

  /**
   * Gera um slip com os valores a reter;
   * retorna o codigo do slip gerado.
   *
   * @param integer $iCtaDebito Código da conta a ser debitada
   * @param integer $iCtaCredito Código da conta a ser creditada
   * @param float   $nValor Valor da transferencia
   *
   * @return integer
   */
  public function gerarSlip($iCtaDebito, $iCtaCredito, $nValor, $dtIni, $dtFim, $lAgendar = false, $iAgrupar=1,
                            $iCodigoOrdem = null) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }

    if (empty($iCtaDebito)) {
      throw new Exception("parametro iCtaDebito não pode ser nulo");
    }
    if (empty($iCtaCredito)) {
      throw new Exception("parametro iCtaCredito não pode ser nulo");
    }
    if (($nValor <= 0 )) {
      throw new Exception("parametro nValor Deve ser um valor válido");
    }
    /**
     * Incluimos o slip
     */
    $sStringOP   = "";
    if (!empty($iCodigoOrdem) && $iAgrupar == 2) {

      $oDaoPagOrdem = db_utils::getDao("pagordem");
      $sSqlOrdem    = $oDaoPagOrdem->sql_query($iCodigoOrdem);
      $rsOrdem      = $oDaoPagOrdem->sql_record($sSqlOrdem);
      if ($oDaoPagOrdem->numrows  > 0) {

        $oOrdem    = db_utils::fieldsMemory($rsOrdem, 0);
        $sStringOP = " da OP {$iCodigoOrdem}, Empenho {$oOrdem->e60_codemp}/{$oOrdem->e60_anousu}";

      }
    }
    $sTextoSlip  = "Referente valores transferidos oriundos de retenção ";
    $sTextoSlip .= "sobre pagamento {$sStringOP} de recurso vinculados no período de {$dtIni} a {$dtFim}";
    if (isset($oOrdem)){
      $sTextoSlip .= " CGM: ".$oOrdem->z01_numcgm." - ".$oOrdem->z01_nome;
    }

    $sTextoSlip = addslashes($sTextoSlip);
    $oDaoSlip = db_utils::getDao("slip");
    $oDaoSlip->k17_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoSlip->k17_debito   = $iCtaDebito;
    $oDaoSlip->k17_credito  = $iCtaCredito;
    $oDaoSlip->k17_valor    = $nValor;
    $oDaoSlip->k17_hist     = 9700;
    $oDaoSlip->k17_texto    = date("d/m/Y",db_getsession("DB_datausu"))."\n{$sTextoSlip}";
    $oDaoSlip->k17_instit   = db_getsession("DB_instit");
    $oDaoSlip->k17_dtanu    = "null";
    $oDaoSlip->k17_situacao = 1;
    $oDaoSlip->incluir(null);
    if ($oDaoSlip->erro_status == 0){

      $sErroMsg  = "Erro [1] Não foi Possivel incluir slip ";
      $sErroMsg .= "{$oDaoSlip->erro_msg}";
      throw new Exception($sErroMsg);

    }
    $iCodigoSlip = $oDaoSlip->k17_codigo;
    /*
     * Incluimos o cgm no slip.
     */
    $sSqlInstit  = "select numcgm from db_config where codigo = ".db_getsession("DB_instit");
    $rsInstit    = db_query($sSqlInstit);
    $iCgmInstit  = db_utils::fieldsMemory($rsInstit, 0)->numcgm;
    $oDaoslipNum = db_utils::getDao("slipnum");
    $oDaoslipNum->k17_codigo = $iCodigoSlip;
    $oDaoslipNum->k17_numcgm = $iCgmInstit;
    $oDaoslipNum->incluir($iCodigoSlip);
    if ($oDaoslipNum->erro_status == 0) {
      throw new Exception("Erro[3] - Não foi possivel incluir slip\nErro ao Definir CGM do slip");
    }

    /**
     * Agendamos o slip.
     */
    $oSlipAgenda = new stdClass();
    $oSlipAgenda->iCodigoSlip = $iCodigoSlip;
    $oSlipAgenda->nValor      = $nValor;
    /**
     * Procuramos se a conta credito do slip é uma conta pagadora no caixa.
     * caso for. setamos essa conta como conta pagadora na agenda.
     */
    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
    $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,"e83_codtipo", null,"e83_conta = {$iCtaCredito}");
    $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
    if ($oDaoEmpAgeTipo->numrows > 0 ) {
      $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
    }
    $this->addMovimentoAgenda(2, $oSlipAgenda);

    /**
     * Procuramos todos os movimentos a serem incluidos no slip,
     * incluimos na tabela slipempagemovslip,
     */
    $oDaoSlipMov = db_utils::getDao("slipempagemovslips");
    $sWhere = null;
    if ($iAgrupar == 2) {
      $sWhere = " e82_codord = {$iCodigoOrdem}";
    }

    $oDaoConCarPeculiarDebito = db_utils::getDao('slipconcarpeculiar');
    $oDaoConCarPeculiarDebito->k131_sequencial     = null;
    $oDaoConCarPeculiarDebito->k131_slip           = $iCodigoSlip;
    $oDaoConCarPeculiarDebito->k131_tipo           = 1;
    $oDaoConCarPeculiarDebito->k131_concarpeculiar = "000";
    $oDaoConCarPeculiarDebito->incluir(null);
    if ($oDaoConCarPeculiarDebito->erro_status == "0") {
      throw new Exception("Não foi possível incluir a característica peculiar para a conta débito");
    }


    $oDaoConCarPeculiarDebito = db_utils::getDao('slipconcarpeculiar');
    $oDaoConCarPeculiarDebito->k131_sequencial     = null;
    $oDaoConCarPeculiarDebito->k131_slip           = $iCodigoSlip;
    $oDaoConCarPeculiarDebito->k131_tipo           = 2;
    $oDaoConCarPeculiarDebito->k131_concarpeculiar = "000";
    $oDaoConCarPeculiarDebito->incluir(null);
    if ($oDaoConCarPeculiarDebito->erro_status == "0") {
      throw new Exception("Não foi possível incluir a característica peculiar para a conta Crédito");
    }

    $aMovimentos = $this->getMovimentosSlip($dtIni, $dtFim,3, $iCodigoOrdem, false, $iCtaCredito, $iCtaDebito, $sWhere);
    foreach ($aMovimentos as $oMovimento) {

      /*
       * consu
       */
      $oDaoSlipMov->k108_empagemovslips = $oMovimento->k107_sequencial;
      $oDaoSlipMov->k108_slip           = $iCodigoSlip;
      $oDaoSlipMov->incluir(null);
      if ($oDaoSlipMov->erro_status == 0) {
        throw new Exception("Erro[2] - Não foi possivel incluir slip");
      }

    }
    return $iCodigoSlip;
  }

  /**
   * Retorna os slips para agendar/agendados
   *
   * @param string $sWhere clausula where
   * @param boolean $lTrazContaFornecedor retorna as contas vinculadas do credor
   * @return array
   */
  function getSlips($sWhere, $lTrazContaFornecedor = false) {

    $oInstit   = db_stdClass::getDadosInstit();
    $oDaoSlip  = new cl_empageslip();
    // @todo -- verificar ultima conta do fornecedor etc...
    $sCampos   = "e90_cancelado,                                          ";
    $sCampos  .= "e81_codmov,                                             ";
    // comentado FUNDEB 73195
    $sCampos  .= "(select e25_empagetipotransmissao                       ";
    $sCampos  .= "  from empagemovtipotransmissao                         ";
    $sCampos  .= " where e25_empagemov = e81_codmov                       ";
    $sCampos  .= "  limit 1 ) as e25_empagetipotransmissao ,              ";

    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte1] */
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte1] */

    $sCampos  .= "s.k17_codigo,                                           ";
    $sCampos  .= "k17_data,                                               ";
    $sCampos  .= "k17_valor,                                              ";
    $sCampos  .= "e91_codmov,                                             ";
    $sCampos  .= "e90_codmov,                                             ";
    $sCampos  .= "e86_codmov,                                             ";
    $sCampos  .= "e83_codtipo,                                            ";
    $sCampos  .= "z.c60_descr as descricaodebito,                         ";
    $sCampos  .= "k17_debito,                                             ";
    $sCampos  .= "k17_credito,                                            ";
    $sCampos  .= "e83_descr,                                              ";
    $sCampos  .= "e85_codtipo,                                            ";
    $sCampos  .= "k152_sequencial, ";
    $sCampos  .= "k152_descricao, ";
    $sCampos  .= " (case                                                   ";
    $sCampos  .= "    when e97_codforma is null                           ";
    $sCampos  .= "      then                                              ";
    $sCampos  .= "      (  select e97_codforma                                                             ";
    $sCampos  .= "           from empageslip                                                               ";
    $sCampos  .= "          inner join empagemov      on empagemov.e81_codmov      = empageslip.e89_codmov ";
    $sCampos  .= "          inner join slip           on slip.k17_codigo           = empageslip.e89_codigo ";
    $sCampos  .= "          inner join empagemovforma on empagemovforma.e97_codmov = empagemov.e81_codmov  ";
    $sCampos  .= "          inner join empagepag      on empagepag.e85_codmov      = empagemov.e81_codmov  ";
    $sCampos  .= "          inner join slipnum        on slipnum.k17_codigo        = slip.k17_codigo       ";
    $sCampos  .= "          where slip.k17_instit    = {$oInstit->codigo}                                  ";
    $sCampos  .= "            and slip.k17_situacao in(1,3)                                                ";
    $sCampos  .= "            and slipnum.k17_numcgm = (case                                               ";
    $sCampos  .= "                                       when z01_numcgm is  not null                      ";
    $sCampos  .= "                                         then z01_numcgm                                 ";
    $sCampos  .= "                                       else {$oInstit->z01_numcgm}                       ";
    $sCampos  .= "                                     end)                                                ";
    $sCampos  .= "          order by e89_codmov desc limit 1)                                              ";
    $sCampos  .= "      else e97_codforma end) as e97_codforma,                                            ";

    $sCampos  .= "e97_codmov,                                             ";
    $sCampos  .= "e83_conta,                                              ";
    $sCampos  .= "ctapag.c61_codigo,                                      ";
    $sCampos  .= "(case when z01_nome is  not null then z01_nome          ";
    $sCampos  .= " else '{$oInstit->z01_nome}' end) as z01_nome,          ";
    $sCampos  .= "(case when z01_numcgm is  not null then z01_numcgm      ";
    $sCampos  .= " else {$oInstit->z01_numcgm} end)  as z01_numcgm,       ";
    $sCampos  .= "(case when z01_numcgm <> {$oInstit->z01_numcgm} then 2  ";
    $sCampos  .= " else 1 end)  as tiposlip,                              ";
    /* [Inicio plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */
    $sCampos  .= " e98_contabanco as conta_configurada,                   ";
    /* [Fim plugin GeracaoArquivoOBN  - Geracao Arquivo OBN - parte2] */
    $sCampos  .= " ( select e98_contabanco                                ";
    $sCampos  .= "     from empagemovconta                                                                        ";
    $sCampos  .= "          inner join pcfornecon  on  pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco ";
    $sCampos  .= "    where pc63_numcgm = (case                                                                   ";
    $sCampos  .= "                           when z01_numcgm is  not null                                         ";
    $sCampos  .= "                             then z01_numcgm                                                    ";
    $sCampos  .= "                           else {$oInstit->z01_numcgm}                                          ";
    $sCampos  .= "                         end)                                                                   ";
    $sCampos  .= "    order by e98_codmov desc limit 1) as ultima_conta_utilizada                                 ";

    $sSqlSlips = $oDaoSlip->sql_query_configura(null,
                                                null,
                                                $sCampos,
                                                "e81_codmov, s.k17_codigo",
                                                $sWhere
    );

    $rsSlips = $oDaoSlip->sql_record($sSqlSlips);

    $aSlips  = array();
    if ($oDaoSlip->numrows > 0 ) {

      for ($i = 0; $i < $oDaoSlip->numrows; $i++) {

        $oSlip    = db_utils::fieldsMemory($rsSlips, $i, false, false, $this->getUrlEncode());

        if ($lTrazContaFornecedor) {

          $oSlip->aContasFornecedor     = $this->getContasFornecedor($oSlip->z01_numcgm);
          $oSlip->iUltimaContaUtilisada = $oSlip->ultima_conta_utilizada;
        }

        /* [Extensão] - Filtro da Despesa - getSlips */


        $aSlips[] = $oSlip;
      }
    }

    return $aSlips;
  }

  function getMovimentosCheque($sWhereOrdens, $sWhereSlips, $sJoin ='') {

    $oInstit   = db_stdClass::getDadosInstit();
    $oDaoSlip  = db_utils::getDao("empageslip");
    $sCampos   = "e80_codage, e81_codmov, ";
    $sCampos  .= "(case when z01_numcgm is  not null then z01_numcgm";
    $sCampos  .= "      else {$oInstit->z01_numcgm} end)  as z01_numcgm,";
    $sCampos  .= "(case when z01_nome is  not null then z01_nome";
    $sCampos  .= "       else '{$oInstit->z01_nome}' end) as z01_nome,";
    $sCampos  .= "k17_data, 2008,0,'0',s.k17_codigo,ctapag.c61_codigo,null,e91_cheque,";
    $sCampos  .= "case when e90_cancelado is false then e91_codmov else null end as e91_codmov,";
    $sCampos  .= "e85_codtipo,e97_codmov,";
    $sCampos  .= "case when e90_cancelado is false then e90_codmov else null end as e90_codmov,";
    $sCampos  .= "e81_valor,e83_descr, e86_codmov,0,";
    $sCampos  .= "1,e83_conta,'f' as validarerencao, e43_valor,k17_valor as e53_valor,e42_sequencial ";
    $sSqlSlips = $oDaoSlip->sql_query_configura(null,
                                                null,
                                                "$sCampos",
                                                null,
                                                $sWhereSlips
    );

    $oDaoEmpAgeMov  = db_utils::getDao("pagordem");
    $sSqlMovimentos = $oDaoEmpAgeMov->sql_query_empagemovforma(
      null,
      "    e80_codage,
      empagemov.e81_codmov,
              case when a.z01_numcgm       is not null then a.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,
              case when trim(a.z01_nome)   is not null then a.z01_nome   else cgm.z01_nome   end as z01_nome,
              e50_data,
              e60_anousu,
              e60_numemp,
              e60_codemp,
              e50_codord,
              o15_codigo,
              k12_data,
              e91_cheque,
              case when e90_cancelado is false then e91_codmov else null end as e91_codmov,
              e85_codtipo,
              e97_codmov,
              case when e90_cancelado is false then e90_codmov else null end as e90_codmov,
              e81_valor,
              e83_descr,
              e86_codmov,
              fc_valorretencaomov(e81_codmov,false) as valorretencao,2 as tipo,e83_conta,
              fc_validaretencoesmesanterior(e81_codmov,null) as validaretencao, e43_valor,e53_valor,e42_sequencial ",
      "",
      $sWhereOrdens, $sJoin
    );
    $sSqlMovimentosCheques = $sSqlMovimentos . " union ".$sSqlSlips;

    if ($this->orderBy != "") {

      $sSqlMovimentosCheques .= " order by {$this->orderBy}";
    }
    $rsMovimentos  = $oDaoSlip->sql_record($sSqlMovimentosCheques);
    $aMovimentos   = array();
    if ($oDaoSlip->numrows > 0 ) {
      $aMovimentos = db_utils::getCollectionByRecord($rsMovimentos,false,false,$this->getUrlEncode());
    }
    return $aMovimentos;
  }

  /**
   * Define a ordem dos dados das consultas feitas na agenda
   * @param string $sOrdem string com a ordem ;
   */
  function setOrdemConsultas($sOrdem) {
    $this->orderBy = $sOrdem;
  }

  /**
   * Retira um determinado movimento de uma op auxiliar
   *
   * @param integer $iOrdemAuxiliar codigo da ordem auxiliar
   * @param integer  $iMovimento codigo do movimento de agenda
   * @return void
   */
  function cancelaMovimentoOrdemAuxiliar($iOrdemAuxiliar, $iMovimento) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não há nenhuma transação ativa. Processo cancelado");
    }

    if (empty($iOrdemAuxiliar)) {
      throw new Exception('Erro [1::AgendaPagamento] - Código da Ordem Auxiliar não Informado');
    }

    if (empty($iMovimento)) {
      throw new Exception('Erro [2::AgendaPagamento] - Código do Movimento não Informado');
    }

    $oDaoempAgeNotasOrdem = db_utils::getDao("empagenotasordem");

    /*
     * Consultamos a agenda para verificar se a ordem realmente faz parte da Ordem auxiliar.
     */
    $sSqlOrdemAuxiliar = $oDaoempAgeNotasOrdem->sql_query_file(null,
                                                               "e43_sequencial",
                                                               null,
                                                               "e43_ordempagamento = {$iOrdemAuxiliar}
                                                                and e43_empagemov  = {$iMovimento}"
    );
    $rsOrdemAuxiliar = $oDaoempAgeNotasOrdem->sql_record($sSqlOrdemAuxiliar);
    if ($oDaoempAgeNotasOrdem->numrows  == 1) {

      $oMovimentoOrdem  = db_utils::fieldsMemory($rsOrdemAuxiliar, 0);
      $oDaoempAgeNotasOrdem->excluir($oMovimentoOrdem->e43_sequencial);
      if ($oDaoempAgeNotasOrdem->erro_status == 0) {

        $sErroMsg  = "Erro [2::AgendaPagamento] - Erro ao Cancelar Movimento.\nErro Ténico:";
        $sErroMsg .= $oDaoempAgeNotasOrdem->erro_msg;
        throw new Exception($sErroMsg);

      }
    }
    return true;
  }

  /**
   * Retorna os valores totais da agenda, conforme filtros passados
   *
   * @param string $sWhere
   * @return array
   */
  function getTotaisAgenda($sWhere = null) {

    $sSqlTotais = "select e96_descr as tipo, ";
    $sSqlTotais .= "       sum(case when (e90_codmov is null and  e97_codforma   = 3)  ";
    $sSqlTotais .= "                      or (e91_codmov is null and e97_codforma = 2) ";
    $sSqlTotais .= "                      or (e97_codforma not in(3,2) or e97_codforma is null) ";
    $sSqlTotais .= "                 then  (e81_valor - valorretencao)  else 0 end) as valor, ";
    $sSqlTotais .= "       coalesce(sum(e91_valor),0) as cheques,";
    $sSqlTotais .= "       coalesce(sum(case when e97_codforma = 3 and e90_codmov is not null";
    $sSqlTotais .= "               then e81_valor else 0 end),0) as transmissao,";
    $sSqlTotais .= "      count(*) as linhas ";
    $sSqlTotais .= "      from (select ";
    $sSqlTotais .= "                empagemov.e81_codmov, ";
    $sSqlTotais .= "                e97_codforma, ";
    $sSqlTotais .= "               case when e97_codforma is null then 'NDA' else e96_descr end as e96_descr, ";
    $sSqlTotais .= "               e53_vlrpag, ";
    $sSqlTotais .= "               e81_valor, ";
    $sSqlTotais .= "               e86_codmov, ";
    $sSqlTotais .= "               e90_codmov, ";
    $sSqlTotais .= "               e91_codmov, ";
    $sSqlTotais .= "               e91_valor, ";
    $sSqlTotais .= "               fc_valorretencaomov(e81_codmov,false) as valorretencao,  ";
    $sSqlTotais .= "               coalesce(e43_valor,0)  as e43_valor ";
    $sSqlTotais .= "          from empage ";
    $sSqlTotais .= "               inner join empagemov       on empagemov.e81_codage      = empage.e80_codage ";
    $sSqlTotais .= "               inner join empord          on empord.e82_codmov         = empagemov.e81_codmov  ";
    $sSqlTotais .= "               inner join pagordem        on pagordem.e50_codord       = empord.e82_codord ";
    $sSqlTotais .= "               left join pagordemnota     on pagordem.e50_codord       = pagordemnota.e71_codord ";
    $sSqlTotais .= "               left join empnota          on empnota.e69_codnota       = pagordemnota.e71_codnota ";
    $sSqlTotais .= "               left join classificacaocredoresempenho on cc31_empempenho = e69_numemp ";
    $sSqlTotais .= "               inner join pagordemele     on pagordemele.e53_codord    = pagordem.e50_codord ";
    $sSqlTotais .= "               inner join empempenho      on empempenho.e60_numemp     = pagordem.e50_numemp ";
    $sSqlTotais .= "               inner join cgm             on cgm.z01_numcgm            = empempenho.e60_numcgm ";
    $sSqlTotais .= "               inner join db_config       on db_config.codigo          = empempenho.e60_instit ";
    $sSqlTotais .= "               inner join orcdotacao      on orcdotacao.o58_anousu     = empempenho.e60_anousu ";
    $sSqlTotais .= "                                         and orcdotacao.o58_coddot     = empempenho.e60_coddot ";
    $sSqlTotais .= "               inner join orctiporec      on orctiporec.o15_codigo     = orcdotacao.o58_codigo ";
    $sSqlTotais .= "               inner join emptipo         on emptipo.e41_codtipo       = empempenho.e60_codtipo ";
    $sSqlTotais .= "               left join corempagemov     on corempagemov.k12_codmov   = empagemov.e81_codmov ";
    $sSqlTotais .= "               left join empageconf       on  empageconf.e86_codmov    = empord.e82_codmov ";
    $sSqlTotais .= "               left join empageconfgera   on  empageconf.e86_codmov    = e90_codmov        ";
    $sSqlTotais .= "               left join empageconfche    on  empageconf.e86_codmov    = e91_codmov        ";
    $sSqlTotais .= "                                          and e91_ativo is true                            ";
    $sSqlTotais .= "               left join empagemovforma   on e97_codmov                = e81_codmov        ";
    $sSqlTotais .= "               left join empageforma       on e96_codigo               = e97_codforma      ";
    $sSqlTotais .= "               left join empagenotasordem  on e81_codmov               = e43_empagemov     ";
    $sSqlTotais .= "               left join empageordem       on e43_ordempagamento       = e42_sequencial    ";
    $sSqlTotais .= "               left join pagordemprocesso  on e50_codord = e03_pagordem";
    if ($sWhere != "") {
      $sSqlTotais .= " where {$sWhere}";
    }
    $sSqlTotais .= " ) as x group by e96_descr";
    $rsTotais    = db_query($sSqlTotais);
    $aTotais     = array();
    if ($rsTotais) {
      $aTotais   = db_utils::getCollectionByRecord($rsTotais);
    }
    return $aTotais;
  }

  /**
   * Agrupa os movimentos de uma mesma ordem e um unico movimento
   *
   * @param array $aMovimentos
   */
  function agruparMovimentos($aMovimentos) {

    if (count($aMovimentos) < 2) {
      throw new Exception("Deve ser selecionado no minino dois movimentos.");
    }
    /**
     * verificamos se todos os movimentos sao da mesma op
     */
    $iOPAnterior  = 0;
    $iCPAnterior  = 0;
    $iCPCA        = 0;
    $nValorOriginalDosMovimentos = 0;
    foreach ($aMovimentos as $oMovimento) {

      if ($iOPAnterior != 0 && $oMovimento->e82_codord != $iOPAnterior) {
        throw new Exception("Foram Selecionados Movimentos de OP diferentes!\nProcedimento Cancelado.");
      }

      if ( $iCPAnterior != 0 && $oMovimento->sConCarPeculiar != $iCPAnterior ) {
        throw new Exception("Foram Selecionados Movimentos de CP/CA diferentes!\nProcedimento Cancelado.", 1);
      }

      $iOPAnterior = $oMovimento->e82_codord;
      $iCPAnterior = $oMovimento->sConCarPeculiar;
      $iCPCA       = $oMovimento->sConCarPeculiar;
      $nValorOriginalDosMovimentos += $oMovimento->nValor;

    }
    $nValorNovoMovimento = 0;
    foreach ($aMovimentos as $oMovimento) {

      /**
       * Verificamos se o movimento nao esta configurado, ou possui valor agendado
       */
      $sWhere         = "e81_codmov = {$oMovimento->e81_codmov}";
      $sJoin          = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
      $sJoin         .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";

      $aMovimentoVerificar = $this->getMovimentosAgenda($sWhere,$sJoin,false,false);
      if (count($aMovimentoVerificar) == 1) {

        if (isset($iNumeroEmpenho) && $iNumeroEmpenho != $aMovimentoVerificar[0]->e60_numemp) {

          $sMessage  = "Movimento selecionados possuem numeros de empenhos diferente.\n";
          $sMessage .= "Operação cancelada.";
          throw new Exception($sMessage);

        }

        $iNumeroEmpenho       = $aMovimentoVerificar[0]->e60_numemp;
        $nValorNovoMovimento += $aMovimentoVerificar[0]->e81_valor;

        /**
         * Fizemos algumas validacoes;
         */
        if ($aMovimentoVerificar[0]->e97_codmov != "") {

          $sMessage = "Movimento ({$oMovimento->e81_codmov}) da OP {$oMovimento->e82_codord} está configurada.";
          throw new Exception();
        }
        if ($aMovimentoVerificar[0]->valorretencao > 0) {
          throw new Exception("Movimento ({$oMovimento->e81_codmov}) da OP {$oMovimento->e82_codord} possui retenções.");
        }

        $oDaoEmpAgeMov = db_utils::getDao("empagemov");
        $oDaoEmpAgeMov->e81_cancelado = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoEmpAgeMov->e81_codmov    =  $oMovimento->e81_codmov;
        $oDaoEmpAgeMov->alterar($oMovimento->e81_codmov);
        if ($oDaoEmpAgeMov->erro_status == 0) {

          $sMessage  = "Não foi possível agrupar movimentos.\n";
          $sMessage .= "Erro Técnico:{$oDaoEmpAgeMov->erro_msg}.";
          throw new Exception($sMessage);

        }
      } else {
        throw new Exception("Movimento ({$oMovimento->e81_codmov}) Não encontrado na base de dados\nSolicite Suporte.");
      }
    }
    /**
     * Verificamos se o valor final do movimento confere com o valor original dos movimentos
     */
    if ($nValorNovoMovimento != $nValorOriginalDosMovimentos) {

      $sMessage  = "Valor final dos movimentos nao conferem.\n";
      $sMessage .= "Valor Novo:{$nValorNovoMovimento}.\nValor Original: {$nValorOriginalDosMovimentos}.\n";
      $sMessage .= "Solicite Suporte.";
      throw new Exception($sMessage);
    }

    $oNovoMovimento->iCodTipo = null;
    $oNovoMovimento->iNumEmp  = $iNumeroEmpenho;
    $oNovoMovimento->nValor   = $nValorNovoMovimento;
    $oNovoMovimento->iCodNota = $oMovimento->e82_codord;
    $iNovoCodigoMovimento     = $this->addMovimentoAgenda(1, $oNovoMovimento);

    /**
     * Insere na tabela empageconcarpeculiar
     */
    $oDaoEmpageConcar                     = db_utils::getDao("empageconcarpeculiar");
    $oDaoEmpageConcar->e79_concarpeculiar = $iCPCA;
    $oDaoEmpageConcar->e79_empagemov      = $iNovoCodigoMovimento;
    $oDaoEmpageConcar->incluir(null);

    if ( $oDaoEmpageConcar->erro_status == "0" ) {
      throw new Exception("Erro [7] - Erro ao configurar dados em empageconcarpeculiar." );
    }

    return true;
  }
}
