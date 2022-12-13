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


require_once(modification('model/tceEstruturaBasica.php'));

class tceLivroDiarioGeral extends tceEstruturaBasica {
  
  const  NOME_ARQUIVO   = 'TCE_4111.TXT';
  const  CODIGO_ARQUIVO = 33;
  
  public $iInstit       = "";
  public $sDataIni      = "";
  public $sDataFim      = "";
  public $sCodRemessa   = "";
  public $sInstituicoes = "";
  
  private $oLeiaute     = null;
  
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData,$oLeiaute = null, $sInstituicoes) {
  	
    try {
      parent::__construct(self::CODIGO_ARQUIVO,self::NOME_ARQUIVO);
    } catch (Exception $e) {
    	throw $e->getMessage();
    }
    
    $this->iInstit       = $iInstit;
    $this->sInstituicoes = $sInstituicoes;
    $this->sDataIni      = $sDataIni;
    $this->sDataFim      = $sDataFim;
    $this->sCodRemessa   = $sCodRemessa;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
    
    
  }
  
  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
    
  function geraArquivo() {

    db_criatermometro('terTCE4111', 'Arquivo TCE4111...', 'blue', 1);
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);
    $sWhere = "where lan.c69_data between '{$this->sDataIni}' and '{$this->sDataFim}' ";
    $rsLancamentos = db_query($this->sqlDiarioGeral($this->iInstit, $sWhere));
    $iNumRows = pg_num_rows($rsLancamentos);
    $iTotalRegistros = 0;
    $iQuant          = 0;

    for($i = 0; $i < $iNumRows; $i ++) {

      $iNew = intval($i*100/$iNumRows);
      if ($iNew > $iQuant) {

        $iQuant = $iNew;
        db_atutermometro($i, $iNumRows, "terTCE4111");
      }

      $oLancamento = db_utils::fieldsMemory($rsLancamentos, $i);
      $this->oTxtLayout->setByLineOfDBUtils($oLancamento, 3);
      $iTotalRegistros ++;
    
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsLancamentos);
  
  }
  
  function sqlDiarioGeral($iInstit, $sWhere) {
    
    $iAnoUsu = db_getsession('DB_anousu');
    /**
     * Sub-select que descobre a origem da arrecadação da receita: planilha de receita ou arrecadação
     */
    $sSqlDadosTesouraria  = " select lpad(case when ";
    $sSqlDadosTesouraria .= "             k82_seqpla::varchar is null ";
    $sSqlDadosTesouraria .= "             then k12_numpre::varchar ";
    $sSqlDadosTesouraria .= "        end, 10, '0')";
    $sSqlDadosTesouraria .= "   from conlancambol";
    $sSqlDadosTesouraria .= "        inner join conlancamdoc  on c71_codlan = c77_codlan";
    $sSqlDadosTesouraria .= "        inner join corrente      on corrente.k12_id = c77_id";
    $sSqlDadosTesouraria .= "                                and corrente.k12_autent = c77_autent";
    $sSqlDadosTesouraria .= "                                and c77_databol = corrente.k12_data";
    $sSqlDadosTesouraria .= "        left  join corlanc       on corrente.k12_id = corlanc.k12_id";
    $sSqlDadosTesouraria .= "                                and corrente.k12_autent = corlanc.k12_autent";
    $sSqlDadosTesouraria .= "                                and corrente.k12_data= corlanc.k12_data";
    $sSqlDadosTesouraria .= "        left  join cornump       on cornump.k12_id = corrente.k12_id";
    $sSqlDadosTesouraria .= "                                and cornump.k12_autent = corrente.k12_autent";
    $sSqlDadosTesouraria .= "                                and cornump.k12_data = corrente.k12_data";
    $sSqlDadosTesouraria .= "        left  join corplacaixa   on corplacaixa.k82_id = corrente.k12_id";
    $sSqlDadosTesouraria .= "                                and corplacaixa.k82_data = corrente.k12_data";
    $sSqlDadosTesouraria .= "                                and corplacaixa.k82_autent = corrente.k12_autent";
    $sSqlDadosTesouraria .= "  where c77_codlan = c69_codlan limit 1  "; 

    
    $sSqlLancamentos  = "select c69_sequen   as sequencial_lancamento, ";
    $sSqlLancamentos .= "       c60_estrut   as codigocontabalanceteverificasg, ";
    $sSqlLancamentos .= "       codtrib      as codigoorgunidorcavinccodsg, ";
    $sSqlLancamentos .= "       c78_chave    as numerolote, ";
    $sSqlLancamentos .= "       case  ";
    $sSqlLancamentos .= "         when c53_tipo is null   ";
    $sSqlLancamentos .= "           then null ";
    $sSqlLancamentos .= "         when c53_tipo in(10, 11) ";
    $sSqlLancamentos .= "           then (select empempenho.e60_anousu||'0'||lpad(e60_codemp,6,'0')::varchar ";
    $sSqlLancamentos .= "                   from conlancamemp ";
    $sSqlLancamentos .= "                        inner join empempenho on empempenho.e60_numemp = conlancamemp.c75_numemp";
    $sSqlLancamentos .= "                  where c75_codlan = c69_codlan limit 1)";
    $sSqlLancamentos .= "         when c53_tipo in(20, 21) ";
    $sSqlLancamentos .= "           then (select lpad(c66_codnota::varchar, 10,'0')::varchar ";
    $sSqlLancamentos .= "                   from conlancamnota ";
    $sSqlLancamentos .= "                  where c66_codlan = c69_codlan limit 1) ";
    $sSqlLancamentos .= "         when c53_tipo in(30, 31) ";
    $sSqlLancamentos .= "           then (select lpad(c80_codord::varchar, 10,'0')::varchar ";
    $sSqlLancamentos .= "                   from conlancamord ";
    $sSqlLancamentos .= "                  where c80_codlan = c69_codlan limit 1) ";
    $sSqlLancamentos .= "           when c53_tipo in (40,41,50,51,60,61,70,71) ";
    $sSqlLancamentos .= "           then (select lpad(o46_codlei::varchar, 10, '0')::varchar ";
    $sSqlLancamentos .= "                   from conlancamsup ";
    $sSqlLancamentos .= "                        inner join orcsuplem on orcsuplem.o46_codsup = conlancamsup.c79_codsup limit 1)";
    $sSqlLancamentos .= "         when c53_tipo in(100, 101)";
    $sSqlLancamentos .= "           then ({$sSqlDadosTesouraria}) ";
    $sSqlLancamentos .= "        end          as numerodocumento, ";
    $sSqlLancamentos .= "       c69_codlan   as numerolancamento, ";
    $sSqlLancamentos .= "       c69_data     as datalancamento, ";
    $sSqlLancamentos .= "       c69_valor    as valor, ";
    $sSqlLancamentos .= "       tipo         as tipolancamento, ";
    $sSqlLancamentos .= "       ''           as numeroarquivamento, ";
    $sSqlLancamentos .= "       ''           as reservadofuturo, ";
    $sSqlLancamentos .= "       substr(replace(replace(c72_complem,'\\n',''), '\\r', ''), 1, 150) as historico, ";
    $sSqlLancamentos .= "       (case when c53_tipo in(10, 11) then 1";
    $sSqlLancamentos .= "             when c53_tipo in (20,21) then 3 ";
    $sSqlLancamentos .= "             when c53_tipo in (30,11) then 2 ";
    $sSqlLancamentos .= "             when c53_tipo is null  then 0 ";
    $sSqlLancamentos .= "             else 9 end ) as tipodocumento ";
    $sSqlLancamentos .= "  from (select c69_sequen, ";
    $sSqlLancamentos .= "               c69_codlan, ";
    $sSqlLancamentos .= "               c69_data, ";
    $sSqlLancamentos .= "               c69_valor, ";
    $sSqlLancamentos .= "               c69_credito as reduz, ";
    $sSqlLancamentos .= "               'C' as tipo, ";
    $sSqlLancamentos .= "               codtrib, ";
    $sSqlLancamentos .= "               substr(coalesce(c78_chave,''),1,12) as c78_chave, ";
    $sSqlLancamentos .= "               conhistdoc.c53_coddoc, ";
    $sSqlLancamentos .= "               substr(c72_complem,1,150) as c72_complem, ";
    $sSqlLancamentos .= "               pcr.c60_estrut, ";
    $sSqlLancamentos .= "               c53_tipo ";
    $sSqlLancamentos .= "          from conlancamval lan ";
    $sSqlLancamentos .= "               inner join conplanoreduz cre  on cre.c61_anousu            = c69_anousu ";
    $sSqlLancamentos .= "                                            and cre.c61_anousu            = {$iAnoUsu} ";
    $sSqlLancamentos .= "                                            and cre.c61_instit            in ({$this->sInstituicoes}) ";
    $sSqlLancamentos .= "                                            and cre.c61_reduz             = c69_credito ";
    $sSqlLancamentos .= "               inner join conplano pcr       on pcr.c60_anousu            = cre.c61_anousu ";
    $sSqlLancamentos .= "                                            and pcr.c60_codcon            = cre.c61_codcon ";
    $sSqlLancamentos .= "               inner join db_config          on db_config.codigo          = cre.c61_instit ";
    $sSqlLancamentos .= "               left  join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan ";
    $sSqlLancamentos .= "               left  join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan ";
    $sSqlLancamentos .= "               left  join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc ";
    $sSqlLancamentos .= "               left  join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan ";
    $sSqlLancamentos .= "     {$sWhere} ";
    $sSqlLancamentos .= "        union all ";
    $sSqlLancamentos .= "        select c69_sequen, ";
    $sSqlLancamentos .= "               c69_codlan, ";
    $sSqlLancamentos .= "               c69_data, ";
    $sSqlLancamentos .= "               c69_valor, ";
    $sSqlLancamentos .= "               c69_debito as reduz, ";
    $sSqlLancamentos .= "               'D' as tipo, ";
    $sSqlLancamentos .= "               codtrib, ";
    $sSqlLancamentos .= "               substr(coalesce(c78_chave,''),1,12) as c78_chave, ";
    $sSqlLancamentos .= "               conhistdoc.c53_coddoc, ";
    $sSqlLancamentos .= "               substr(c72_complem,1,150) as c72_complem, ";
    $sSqlLancamentos .= "               pdb.c60_estrut, ";
    $sSqlLancamentos .= "               c53_tipo ";
    $sSqlLancamentos .= "          from conlancamval lan ";
    $sSqlLancamentos .= "               inner join conplanoreduz deb  on deb.c61_anousu            = c69_anousu ";
    $sSqlLancamentos .= "                                            and deb.c61_anousu            = {$iAnoUsu} ";
    $sSqlLancamentos .= "                                            and deb.c61_instit            in ({$this->sInstituicoes}) ";
    $sSqlLancamentos .= "                                            and deb.c61_reduz             = c69_debito ";
    $sSqlLancamentos .= "               inner join conplano pdb       on pdb.c60_anousu            = deb.c61_anousu ";
    $sSqlLancamentos .= "                                            and pdb.c60_codcon            = deb.c61_codcon ";
    $sSqlLancamentos .= "               inner join db_config          on db_config.codigo          = deb.c61_instit ";
    $sSqlLancamentos .= "               left  join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan ";
    $sSqlLancamentos .= "               left  join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan ";
    $sSqlLancamentos .= "               left  join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc ";
    $sSqlLancamentos .= "               left  join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan ";
    $sSqlLancamentos .= "     {$sWhere} ";
    $sSqlLancamentos .= "     ) as x ";
    $sSqlLancamentos .= " order by c69_sequen ";
    
//    $sSqlLancamentos .= " limit 1000 ";
    return $sSqlLancamentos;
  }
  
}