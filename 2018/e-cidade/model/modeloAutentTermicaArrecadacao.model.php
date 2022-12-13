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

require_once modification("model/modeloAutentTermicaBasica.php");

class modeloAutentTermicaArrecadacao extends modeloAutentTermicaBasica {

  private $sBuffer = '';

  private $sTipoAutent = '';

  /**
   *
   */
  function __construct($sIp, $sPorta, $iId, $sData, $iAutent) {

    parent::__construct($sIp, $sPorta, $iId, $sData, $iAutent);

    $sSql  = "select case ";
    $sSql .= "  when itbinumpre.it15_numpre is not null then 'ITBI'         ";
    $sSql .= "  when recibo.k00_numpre      is not null then 'RECIBOAVULSO' ";
    $sSql .= "  when recibopaga.k00_numpre  is not null then 'RECIBOCGF'    ";
    $sSql .= "  when corcla.k12_codcla      is not null then 'BAIXABANCO'   ";
    $sSql .= "  else 'CARNE' ";
    $sSql .= "  end as tipo ";
    $sSql .= "from cornump ";
    $sSql .= "       left join recibo     on recibo.k00_numpre      = cornump.k12_numpre ";
    $sSql .= "       left join recibopaga on recibopaga.k00_numnov  = cornump.k12_numnov ";
    $sSql .= "       left join itbinumpre on itbinumpre.it15_numpre = cornump.k12_numpre ";
    $sSql .= "       left join corcla     on corcla.k12_id          = cornump.k12_id     ";
    $sSql .= "                           and corcla.k12_data        = cornump.k12_data   ";
    $sSql .= "                           and corcla.k12_autent      = cornump.k12_autent ";
    $sSql .= " where cornump.k12_id     = {$this->iId} ";
    $sSql .= "   and cornump.k12_data   = '{$this->sData}' ";
    $sSql .= "   and cornump.k12_autent = {$this->iAutent} limit 1";

    $rsTipo = db_query($sSql);
    $oTipo = db_utils::fieldsMemory($rsTipo, 0);

    $this->sTipoAutent = $oTipo->tipo;

  }

  function imprimir() {

    $this->sBuffer .= $this->getResumo();

    $this->sBuffer .= $this->getConteudo();

    $this->oImpressora->inicializa();
    $this->oImpressora->setLarguraPadrao();
    parent::imprimir($this->sBuffer);
    $this->oImpressora->cortarPapel();
    $this->oImpressora->finaliza();
    $this->oImpressora->rodarComandos();

  }

  function getConteudo() {

    switch ( $this->sTipoAutent) {

    	case 'RECIBOAVULSO' :
	       return $this->getReciboAvulso();
	    break;

	    case 'RECIBOCGF' :
	       return $this->getReciboCgf();
	    break;

	    case 'CARNE' :
	       return $this->getCarne();
	    break;

	    case 'ITBI' :
        return $this->getReciboITBI();
      break;

      case 'BAIXABANCO' :
        return $this->getReciboBaixaBanco();
      break;

      default :
	       throw new Exception("Erro procurando tipo de autenticao.");
	    break;
    }

  }

  function getReciboCgf() {

    $sBuffer = "\n<b>" . str_pad("Tipo Documento", 18, " ", STR_PAD_RIGHT)
      . str_pad("Pagamento Recibo CGF", 30, " ", STR_PAD_BOTH) . "</b>";

    $aDebitos = $this->getDebitosArrecadacao();
    $aValores = array();

    $sVirgula    = "";
    $nTotalGeral = 0;

    $sBuffer  = "\n<b>" . chr(15). str_pad("Tipo Documento", 18, " ", STR_PAD_RIGHT) . str_pad("Pagamento Recibo CGF", 30, " ", STR_PAD_BOTH) . "</b>";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Numpre/Parcela" , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Receita", 16, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Valor"  , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    foreach ( $aDebitos as $oDebito ) {

      $nTotal   = ($oDebito->corrigido + $oDebito->juros + $oDebito->multa) - $oDebito->desconto;

      $sBuffer .= "\n" . str_pad("{$oDebito->k00_numpre}/".str_pad("{$oDebito->k00_numpar}",3,0,STR_PAD_LEFT) , 15, " ", STR_PAD_BOTH)." | ";
      $sBuffer .=        str_pad(substr("{$oDebito->k00_receit} - {$oDebito->k02_descr}",0,16)                , 16, " ", STR_PAD_RIGHT)." | ";
      $sBuffer .=        str_pad(trim(db_formatar($nTotal, 'f'))                                              , 15, " ", STR_PAD_LEFT)." | ";

      $nTotalGeral += $nTotal;

    }

    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Total:" , 34 ,                                     " ", STR_PAD_LEFT)." | ";
    $sBuffer .=        str_pad(trim(db_formatar($nTotalGeral, 'f')) , 15, " ", STR_PAD_LEFT)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "" . chr(18);

    return $sBuffer;

  }

  function getCarne() {

    if ($this->isEstorno()) {
      $sBuffer = "\n<b>".chr(15).str_pad("Tipo Documento", 18, " ", STR_PAD_RIGHT).str_pad("Estorno Carne", 30, " ", STR_PAD_BOTH)."</b>";
    }else{
      $sBuffer = "\n<b>".chr(15).str_pad("Tipo Documento", 18, " ", STR_PAD_RIGHT).str_pad("Pagamento Carne", 30, " ", STR_PAD_BOTH)."</b>";
    }

    $nTotalGeral = 0;

    $aDebitos = $this->getDebitosArrecadacao();
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Numpre/Parcela" , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Receita", 16, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Valor"  , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);

    foreach ( $aDebitos as $oDebito ) {

      $nTotal   = ($oDebito->corrigido + $oDebito->juros + $oDebito->multa) - $oDebito->desconto;

      $sBuffer .= "\n" . str_pad("{$oDebito->k00_numpre}/".str_pad("{$oDebito->k00_numpar}",3,0,STR_PAD_LEFT) , 15, " ", STR_PAD_BOTH)." | ";
      $sBuffer .=        str_pad(substr("{$oDebito->k00_receit} - {$oDebito->k02_descr}",0,16)                , 16, " ", STR_PAD_RIGHT)." | ";
      $sBuffer .=        str_pad(trim(db_formatar($nTotal, 'f'))                                              , 15, " ", STR_PAD_LEFT)." | ";

      $nTotalGeral += $nTotal;

    }

    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Total:" , 34 ,                                     " ", STR_PAD_LEFT)." | ";
    $sBuffer .=        str_pad(trim(db_formatar($nTotalGeral, 'f')) , 15, " ", STR_PAD_LEFT)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "" . chr(18);

    return $sBuffer;

  }

  function getReciboITBI(){

    $sSql  = "select distinct itbinumpre.it15_guia ";
    $sSql .= "  from cornump ";
    $sSql .= "       inner join itbinumpre on itbinumpre.it15_numpre = cornump.k12_numpre ";
    $sSql .= " where k12_id     = {$this->iId}             ";
    $sSql .= "   and k12_data   = '{$this->sData}'         ";
    $sSql .= "   and k12_autent = {$this->iAutent} limit 1 ";

    $rsITBI    = db_query($sSql);
    $iGuiaITBI = db_utils::fieldsMemory($rsITBI, 0)->it15_guia;

  	$sBuffer  = $this->getReciboAvulso();
  	$sBuffer .= "\n Guia de ITBI nº : {$iGuiaITBI} ";

    return $sBuffer;
  }

  function getReciboBaixaBanco(){


    /*
     *  Consulta código da classificação
     */
    $sSqlClassificacao  = " select k12_codcla   ";
    $sSqlClassificacao .= "   from cornump ";
    $sSqlClassificacao .= "        inner join corcla on corcla.k12_id     = cornump.k12_id     ";
    $sSqlClassificacao .= "                         and corcla.k12_data   = cornump.k12_data   ";
    $sSqlClassificacao .= "                         and corcla.k12_autent = cornump.k12_autent ";
    $sSqlClassificacao .= "  where cornump.k12_id     = {$this->iId}      ";
    $sSqlClassificacao .= "    and cornump.k12_data   = '{$this->sData}'  ";
    $sSqlClassificacao .= "    and cornump.k12_autent = {$this->iAutent} limit 1";

    $rsClassificacao    = db_query($sSqlClassificacao);
    $iCodClassificacao  = db_utils::fieldsMemory($rsClassificacao,0)->k12_codcla;


    /*
  	 * monta cabecalho do recibo
  	 */
  	$sBuffer  = "\n\n";
    $sBuffer .= str_pad("Autenticação"      ,14," ",STR_PAD_BOTH);
    $sBuffer .= str_pad("Receita"           , 9," ",STR_PAD_BOTH);
    $sBuffer .= str_pad("Descr Receita"     ,14," ",STR_PAD_LEFT);
    $sBuffer .= str_pad("Valor"             , 7," ",STR_PAD_LEFT);
    $sBuffer .= "\n";


  	/*
  	 * busca dados para o recibo
  	 */
    $sSqlArquivoRet  = " select discla.*,                                                  ";
    $sSqlArquivoRet .= "			  disrec.k00_receit,                                         ";
    $sSqlArquivoRet .= "			  tabrec.k02_drecei,                                         ";
    $sSqlArquivoRet .= "        disrec.vlrrec,                                             ";
    $sSqlArquivoRet .= "			  disarq.k00_conta,                                          ";
    $sSqlArquivoRet .= "			  disarq.arqret,                                             ";
    $sSqlArquivoRet .= "			  disarq.dtretorno,                                          ";
    $sSqlArquivoRet .= "			  disarq.dtarquivo,                                          ";
    $sSqlArquivoRet .= "			  saltes.k13_descr                                           ";
    $sSqlArquivoRet .= "   from discla                                                     ";
    $sSqlArquivoRet .= "			  inner join disrec on discla.codcla     = disrec.codcla     ";
    $sSqlArquivoRet .= "			  inner join tabrec on tabrec.k02_codigo = disrec.k00_receit ";
    $sSqlArquivoRet .= "			  left  join disarq on disarq.codret     = discla.codret     ";
    $sSqlArquivoRet .= "			  left  join saltes on saltes.k13_conta  = disarq.k00_conta  ";
    $sSqlArquivoRet .= "  where discla.codcla = {$iCodClassificacao}                       ";
    $sSqlArquivoRet .= "	      and discla.instit = ".db_getsession('DB_instit')."         ";
    $sSqlArquivoRet .= "	      order by disrec.k00_receit                                 ";

    $rsDados = db_query($sSqlArquivoRet);
    $iNum    = pg_numrows($rsDados);


    //echo 'num = ' . $iNum;
    //die($sSqlArquivoRet);

    //$oDados = db_utils::fieldsMemory($result, 0);

    /*
     * insere dados no recibo
     */
    $ntotalrec = 0;
    for ($i = 0; $i < $iNum; $i++ ) {

     $oDados  = db_utils::fieldsMemory($rsDados, $i);

	    $sBuffer .= str_pad(db_formatar($oDados->dtaute, "d")        ,14," ",STR_PAD_BOTH);
	    $sBuffer .= str_pad($oDados->k00_receit                      , 9," ",STR_PAD_BOTH);
	    $sBuffer .= str_pad($oDados->k02_drecei                      ,14," ",STR_PAD_LEFT);
	    $sBuffer .= str_pad( trim(db_formatar($oDados->vlrrec, "f")) , 7," ",STR_PAD_LEFT);
	    $sBuffer .= "\n";
	    $ntotalrec += $oDados->vlrrec;

    }

    $sBuffer .= " --------------------------------------------";
    $sBuffer .= "\n";
    $sBuffer .= " Total Geral";
    $sBuffer .= str_pad( trim(db_formatar($ntotalrec, "f")) ,32," ",STR_PAD_LEFT);
    $sBuffer .= "\n";

    return $sBuffer;

  }

  function getReciboAvulso() {

    $nTotalGeral = 0;
    $aDebitos = $this->getDadosReciboAvulso();
    $sBuffer  = "\n<b>" . chr(15). str_pad("Tipo Documento", 18, " ", STR_PAD_RIGHT) . str_pad("Pagamento Recibo Avulso", 30, " ", STR_PAD_BOTH) . "</b>";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Numpre/Parcela" , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Receita", 16, " ", STR_PAD_BOTH)." | ";
    $sBuffer .=        str_pad("Valor"  , 15, " ", STR_PAD_BOTH)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    foreach ( $aDebitos as $oDebito ) {

      $sBuffer .= "\n" . str_pad("{$oDebito->k00_numpre}/".str_pad("{$oDebito->k00_numpar}",3,0,STR_PAD_LEFT) , 15, " ", STR_PAD_BOTH)." | ";
      $sBuffer .=        str_pad(substr("{$oDebito->k00_receit} - {$oDebito->k02_descr}",0,16)                , 16, " ", STR_PAD_RIGHT)." | ";
      $sBuffer .=        str_pad(trim(db_formatar($oDebito->k00_valor, 'f'))                                  , 15, " ", STR_PAD_LEFT)." | ";

      $nTotalGeral += $oDebito->k00_valor;

    }

    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "\n" . str_pad("Total:" , 34 ,                                     " ", STR_PAD_LEFT)." | ";
    $sBuffer .=        str_pad(trim(db_formatar($nTotalGeral, 'f')) , 15, " ", STR_PAD_LEFT)." | ";
    $sBuffer .= "\n" . str_pad("", 55, "-", STR_PAD_BOTH);
    $sBuffer .= "" . chr(18);

    return $sBuffer;

  }

  function getResumo() {

    $sBuffer = '';

    $aDevedores = $this->getDadosDevedor();

    if (count($aDevedores) > 0) {
      $oDevedor = $aDevedores [0];
      if ($oDevedor->qtdmatric > 1 || $oDevedor->qtdinscr > 1) {
	$sStringTipo = "Débito com mais de uma matrícula/inscrição";
      } else {
	if ($oDevedor->qtdmatric >0 ) {
	  $sStringTipo = " Matricula:{$oDevedor->matricula}";
	} else if ($oDevedor->qtdinscr > 0) {
	  $sStringTipo = " Inscrição:{$oDevedor->inscricao}";
	} else {
	  $sStringTipo = "Numcgm:{$oDevedor->k00_numcgm}";
	}
      }
      $sBuffer = "\n<b>" . str_pad("Contrib.:", 9, " ", STR_PAD_RIGHT) . " </b>";
      $sBuffer .= str_pad("{$oDevedor->z01_numcgm} - {$oDevedor->z01_nome}", 24, " ", STR_PAD_LEFT);
      $sBuffer .= "\n<b>" . str_pad("Origem:", 7, " ", STR_PAD_RIGHT) . " </b>";
      $sBuffer .= str_pad("{$oDevedor->origem}", 20, " ", STR_PAD_LEFT)." ";
      $sBuffer .= "<b>" . str_pad("{$sStringTipo}", 15, " ", STR_PAD_LEFT) . " </b>";
    }

    if ($this->sTipoAutent == 'RECIBOCGF' || $this->sTipoAutent == 'RECIBOAVULSO') {
      $sSqlLinhaDigitavel = "select recibocodbar.* ";
      $sSqlLinhaDigitavel .= "  from recibocodbar ";
      $sSqlLinhaDigitavel .= "       inner join cornump on cornump.k12_numnov = recibocodbar.k00_numpre";
      $sSqlLinhaDigitavel .= " where k12_id     = {$this->iId} ";
      $sSqlLinhaDigitavel .= "   and k12_data   = '{$this->sData}' ";
      $sSqlLinhaDigitavel .= "   and k12_autent = {$this->iAutent} limit 1";
      $rsLinhaDigitavel = db_query($sSqlLinhaDigitavel);
      if (pg_num_rows($rsLinhaDigitavel) > 0) {
	$oLinhaDigitavel = db_utils::fieldsMemory($rsLinhaDigitavel, 0);
      }
    }

    if (isset($oLinhaDigitavel)) {
      $sBuffer .= "\n" . str_pad("", 46, "-", STR_PAD_BOTH);
      $sBuffer .= "\n" . chr(15)."<b>".str_pad("C. Barras:", 8, " ", STR_PAD_RIGHT)  . "</b>".
	str_pad("{$oLinhaDigitavel->k00_codbar}", 52, " ", STR_PAD_LEFT) . chr(18);
      $sBuffer .= "\n" . chr(15)."<b>".str_pad("L. Digit.:", 8, " ", STR_PAD_RIGHT)  . "</b>".
	str_pad("{$oLinhaDigitavel->k00_linhadigitavel}", 52, " ", STR_PAD_LEFT) . chr(18);
      $sBuffer .= "\n" . str_pad("", 46, "-", STR_PAD_BOTH);
      $sBuffer .= "\n<b>" . str_pad("Código de Arrecadação:", 25, " ", STR_PAD_RIGHT) . "</b>";
      $sBuffer .= str_pad($oLinhaDigitavel->k00_numpre, 23, " ", STR_PAD_LEFT);

    }

    $sBuffer .= "\n" . str_pad("", 46, "-", STR_PAD_BOTH);
    $sBuffer .= "\n<b>" . str_pad("Data Pagamento:", 15, " ", STR_PAD_RIGHT) . "</b>";
    $sBuffer .= str_pad(db_formatar($this->sData, 'd'), 33, " ", STR_PAD_LEFT);

    if ($this->isEstorno()) {
      $sTipoAutent = 'Estorno';
      $sDescrValor = 'Valor Estornado';
    } else {
      $sTipoAutent = 'Pagamento';
      $sDescrValor = 'Valor Pago';
    }

    $sBuffer .= "\n<b>" . str_pad("Tipo Autent:", 15, " ", STR_PAD_RIGHT) . "</b>";
    $sBuffer .= str_pad($sTipoAutent, 15, " ", STR_PAD_BOTH);
    $sBuffer .= str_pad($this->iAutent, 18, " ", STR_PAD_LEFT);

    $sBuffer .= "\n<b>" . str_pad($sDescrValor.":", 15, " ", STR_PAD_RIGHT) . "</b>";
    $sBuffer .= str_pad("R$ " . trim(db_formatar($this->getValorTotal(), 'f')), 32, " ", STR_PAD_LEFT);
    $sBuffer .= "\n" . str_pad("", 48, '=', STR_PAD_BOTH);
    return $sBuffer;

  }

  function getDebitosArrecadacao() {

    $sSqlDebitos  = " select k12_numpre as k00_numpre, ";
    $sSqlDebitos .= "        k12_numpar as k00_numpar, ";
    $sSqlDebitos .= "        k12_receit as k00_receit, ";
    $sSqlDebitos .= "        k12_id, ";
    $sSqlDebitos .= "        k12_data, ";
    $sSqlDebitos .= "        k12_autent, ";
    $sSqlDebitos .= "        k12_numpre, ";
    $sSqlDebitos .= "        tabrec.k02_codigo, ";
    $sSqlDebitos .= "        tabrec.k02_descr, ";
    $sSqlDebitos .= "        coalesce( k12_valor, 0) as corrigido, ";
    $sSqlDebitos .= "        coalesce( (select sum(k12_valor)  ";
    $sSqlDebitos .= "           from cornump n  ";
    $sSqlDebitos .= "          where n.k12_data   = cornump.k12_data    ";
    $sSqlDebitos .= "            and n.k12_id     = cornump.k12_id      ";
    $sSqlDebitos .= "            and n.k12_autent = cornump.k12_autent  ";
    $sSqlDebitos .= " 		 and n.k12_numpre = cornump.k12_numpre  ";
    $sSqlDebitos .= "  		 and n.k12_numpar = cornump.k12_numpar  ";
    $sSqlDebitos .= "            and n.k12_receit = ( select k02_recjur ";
    $sSqlDebitos .= "                                   from tabrec  ";
    $sSqlDebitos .= "                                  where k02_codigo = cornump.k12_receit ) ";
    $sSqlDebitos .= "        ),0) as juros, ";
    $sSqlDebitos .= "        0 as multa, ";
    $sSqlDebitos .= "       coalesce( (select sum(k00_valor) ";
    $sSqlDebitos .= "          from arrepaga ";
    $sSqlDebitos .= "         where k00_numpre = k12_numpre ";
    $sSqlDebitos .= "           and k00_numpar = k12_numpar ";
    $sSqlDebitos .= "           and k00_receit = k12_receit ";
    $sSqlDebitos .= "           and k00_hist   = 1018 ),0) as desconto ";
    $sSqlDebitos .= "   from cornump  ";
    $sSqlDebitos .= "        inner join tabrec   on tabrec.k02_codigo = cornump.k12_receit  ";
    $sSqlDebitos .= "        left  join tabrec j on j.k02_recjur = cornump.k12_receit  ";
    $sSqlDebitos .= " where k12_id     = {$this->iId} ";
    $sSqlDebitos .= "   and k12_data   = '{$this->sData}' ";
    $sSqlDebitos .= "   and k12_autent = {$this->iAutent}";
    $sSqlDebitos .= "   and j.k02_recjur is null ";

    $sSqlDebitos .= " order by k12_data,k12_id,k12_autent; ";

    $rsDebitos = db_query($sSqlDebitos);

    if ($rsDebitos === false || pg_num_rows($rsDebitos) == 0) {
      throw new Exception("Debitos nao econtrado para autenticacao. Term:{$this->iId} Data:{$this->sData} Autent:{$this->iAutent} SQL : {$sSqlDebitos}");
    }

    return db_utils::getCollectionByRecord($rsDebitos);

  }

  function getDadosDevedor() {

    $sSqlDebitos  = " select cgm.*,                                                                                 ";
    $sSqlDebitos .= "        case                                                                                   ";
    $sSqlDebitos .= "          when termo.v07_numpre is not null then 'Parcelamento : '||v07_parcel                 ";
    $sSqlDebitos .= "          else ''                                                                              ";
    $sSqlDebitos .= "        end as origem,                                                                         ";
    $sSqlDebitos .= "        cgm.z01_numcgm as k00_numcgm,                                                          ";
    $sSqlDebitos .= "        arreinscr.k00_inscr as inscricao,                                                      ";
    $sSqlDebitos .= "        arrematric.k00_matric as matricula,                                                    ";
    $sSqlDebitos .= "        (select count(*) from arrematric where k00_numpre = origem.k00_numpre ) as qtdmatric , ";
    $sSqlDebitos .= "        (select count(*) from arreinscr  where k00_numpre = origem.k00_numpre ) as qtdinscr ,  ";
    $sSqlDebitos .= "        (select count(*) from arrenumcgm where k00_numpre = origem.k00_numpre ) as qtdcgm      ";
    $sSqlDebitos .= "   from cornump                                                                                ";

    if ($this->sTipoAutent != 'RECIBOAVULSO') {

      if ($this->isEstorno()) {
	      $sTableJoin = 'arrecad';
      } else {
	      $sTableJoin = 'arrecant';
      }

      $sSqlDebitos .= "      inner join {$sTableJoin} origem on origem.k00_numpre = cornump.k12_numpre              ";
      $sSqlDebitos .= "                                     and origem.k00_numpar = cornump.k12_numpar              ";
      $sSqlDebitos .= "                                     and origem.k00_receit = cornump.k12_receit              ";

    } else {

      $sTableJoin   = 'recibo';
      $sSqlDebitos .= "      inner join {$sTableJoin} origem on origem.k00_numpre = cornump.k12_numpre              ";
    }

    $sSqlDebitos .= "      left  join termo      on termo.v07_numpre      = origem.k00_numpre                       ";
    $sSqlDebitos .= "      left  join arrematric on arrematric.k00_numpre = origem.k00_numpre                       ";
    $sSqlDebitos .= "      left  join arreinscr  on arreinscr.k00_numpre  = origem.k00_numpre                       ";
    $sSqlDebitos .= "      inner join arrenumcgm on arrenumcgm.k00_numpre = origem.k00_numpre                       ";
    $sSqlDebitos .= "                           and (select case when k00_matric is not null then fc_busca_envolvidos (true, ( select coalesce(fc_regrasconfig(2), 1) ), 'M', k00_matric ) ";
    $sSqlDebitos .= "                                            when k00_inscr  is not null then fc_busca_envolvidos (true, ( select coalesce(fc_regrasconfig(1), 1) ), 'I', k00_inscr  ) ";
    $sSqlDebitos .= "                                  else                                       fc_busca_envolvidos (true, 1, 'C', origem.k00_numcgm )                                   ";
    $sSqlDebitos .= "                                   end limit 1).riNumcgm = arrenumcgm.k00_numcgm               ";
    $sSqlDebitos .= "      inner join cgm        on cgm.z01_numcgm        = arrenumcgm.k00_numcgm                   ";
    $sSqlDebitos .= " where k12_id     = {$this->iId}                                                               ";
    $sSqlDebitos .= "   and k12_data   = '{$this->sData}'                                                           ";
    $sSqlDebitos .= "   and k12_autent = {$this->iAutent}                                                           ";

    $rsDebitos = db_query($sSqlDebitos);

    return db_utils::getCollectionByRecord($rsDebitos);

  }

  function getDadosReciboAvulso() {

    $sSqlDebitos = "select *, ";
    $sSqlDebitos .= "       ( select k00_tipo ";
    $sSqlDebitos .= "           from arretipo ";
    $sSqlDebitos .= "          where k00_tipo = (select k03_reciboprot ";
    $sSqlDebitos .= "                              from numpref ";
    $sSqlDebitos .= "                             where k03_instit = " . db_getsession('DB_instit');
    $sSqlDebitos .= "                               and k03_anousu = " . db_getsession('DB_anousu') . ") ) as k00_tipo,";
    $sSqlDebitos .= "       ( select k00_descr ";
    $sSqlDebitos .= "           from arretipo ";
    $sSqlDebitos .= "          where k00_tipo = (select k03_reciboprot ";
    $sSqlDebitos .= "                              from numpref ";
    $sSqlDebitos .= "                             where k03_instit = " . db_getsession('DB_instit');
    $sSqlDebitos .= "                               and k03_anousu = " . db_getsession('DB_anousu') . ") ) as k00_descr";
    $sSqlDebitos .= "  from cornump ";
    $sSqlDebitos .= "       inner join recibo   on recibo.k00_numpre  = cornump.k12_numpre   ";
    $sSqlDebitos .= "                          and recibo.k00_numpar  = cornump.k12_numpar   ";
    $sSqlDebitos .= "                          and recibo.k00_receit  = cornump.k12_receit   ";
    $sSqlDebitos .= "       inner join cgm      on cgm.z01_numcgm     = recibo.k00_numcgm    ";
    $sSqlDebitos .= "       inner join tabrec   on tabrec.k02_codigo  = recibo.k00_receit    ";
    $sSqlDebitos .= "       left  join tabdesc  on tabdesc.codsubrec  = recibo.k00_codsubrec ";
    $sSqlDebitos .= " where k12_id     = {$this->iId} ";
    $sSqlDebitos .= "   and k12_data   = '{$this->sData}' ";
    $sSqlDebitos .= "   and k12_autent = {$this->iAutent}";
    $rsDebitos = db_query($sSqlDebitos);
    if ($rsDebitos === false || pg_num_rows($rsDebitos) == 0) {
      throw new Exception("Erro ao buscar a origem da autenticacao. Term:{$this->iId} Data:{$this->sData} Autent:{$this->iAutent} SQL : {$sSqlDebitos}");
    }
    return db_utils::getCollectionByRecord($rsDebitos);
  }

}