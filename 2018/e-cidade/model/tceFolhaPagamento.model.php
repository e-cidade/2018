<?php

/**
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

class tceFolhaPagamento extends tceEstruturaBasica {
  
  const  NOME_ARQUIVO   = 'TCE_4810.TXT';
  const  CODIGO_ARQUIVO = 34;
  
  public  $iInstit       = "";
  public  $sInstituicoes = "";
  public  $sDataIni      = "";
  public  $sDataFim      = "";
  public  $sCodRemessa   = "";
  public  $iDiaPagamento = "";
  
  private $oLeiaute      = null;
  
  function __construct($iInstit,$sCodRemessa,$sDataIni,$sDataFim,$oData,$oLeiaute=null, $sInstituicoes) {
  	
    try {    
      parent::__construct(self::CODIGO_ARQUIVO,self::NOME_ARQUIVO);
    } catch (Exception $e) {
    	//throw $e->getMessage();
    }

    $this->oLeiaute      = $oLeiaute;    
    $this->iInstit       = $iInstit;
    $this->sInstituicoes = $sInstituicoes;
    $this->sDataIni      = $sDataIni;
    $this->sDataFim      = $sDataFim;
    $this->sCodRemessa   = $sCodRemessa;
    $this->iDiaPagamento = $oData->diapagfolha;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
        
  }
  
  function getNomeArquivo(){
    return self::NOME_ARQUIVO;
  }
  
  function geraArquivo() {

    db_criatermometro('terTCE4810', 'Arquivo TCE4810...', 'blue', 1);
    
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);

    $rsFolhaPagamento = db_query($this->sqlFolhaPagamento($this->sInstituicoes,
                                                          $this->sDataIni, 
                                                          $this->sDataFim, 
                                                          $this->iDiaPagamento));
    $iNumRows = pg_num_rows($rsFolhaPagamento);
    $iTotalRegistros = 0;

    if ($this->oLeiaute) {
	    /**
	     * Setando as propriedades do campo a ser inserido no leiaute
	     */    
	    $this->oLeiaute->setNomeArquivo($this->getNomeArquivo());
	    $this->oLeiaute->setNomeArqTce($this->getNomeArquivo());
	    $this->oLeiaute->setNomeCampo("RUBRICA");
	    $this->oLeiaute->setNumCasasDecimais(0);
	    $this->oLeiaute->setVersaoLeiaute("1");
	    $this->oLeiaute->setObs("CODIGO DA VANTAGEM DESCONTO TOTALIZADOR");
	    $this->oLeiaute->setTamanho(4);
	    $this->oLeiaute->setTipo("C");
	    /**
	     * Metodo que adiciona uma linha no leiaute,
	     * com base nas propriedades setadas
	     */
	    $this->oLeiaute->addLinha();
	  }

    $iQuant = 0;
    for($i = 0; $i < $iNumRows; $i ++) {

      $iNew = intval($i*100/$iNumRows);
      if ($iNew > $iQuant) {

        $iQuant = $iNew;
        db_atutermometro($i, $iNumRows, "terTCE4810");
      }

      $oFolhaPagamento = db_utils::fieldsMemory($rsFolhaPagamento, $i);
      $this->oTxtLayout->setByLineOfDBUtils($oFolhaPagamento, 3);
      $iTotalRegistros ++;
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsFolhaPagamento);
  
  }
  
  function sqlFolhaPagamento($iInstit, $sDataini, $sDatafim, $iDiaPagamento) {

    list ( $iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDatafim);
    list ( $iAnoUsuIni, $iMesUsuIni, $iDiaUsuIni ) = explode("-", $sDataini);
    
    $sSqlFolhaPagamento  = " select * from ( ";
    $sSqlFolhaPagamento .= " select 1          as codigotipofolha, ";
    $sSqlFolhaPagamento .= "        r14_regist as codigoregistrofuncionario, ";
    $sSqlFolhaPagamento .= "        (cast(r14_anousu::varchar||'-'||r14_mesusu::varchar||'-'||(select fc_ultimodiames(r14_anousu,r14_mesusu))::varchar as date)) as datacompetenciafolha, ";
    $sSqlFolhaPagamento .= "        (cast(r14_anousu::varchar||'-'||r14_mesusu::varchar||'-'||(select fc_ultimodiames(r14_anousu,r14_mesusu))::varchar as date)) as datapagamentofolha, ";
    $sSqlFolhaPagamento .= "        '000'      as codigovantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        r14_valor  as valorvantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        case ";
    $sSqlFolhaPagamento .= "          when r14_pd = 1 then 'V' ";
    $sSqlFolhaPagamento .= "          when r14_pd = 2 then 'D'  ";
    $sSqlFolhaPagamento .= "          else 'O'  ";
    $sSqlFolhaPagamento .= "        end as identificacaooperacao, ";
    $sSqlFolhaPagamento .= "        case  ";
    $sSqlFolhaPagamento .= "          when ( select r09_rubric  ";
    $sSqlFolhaPagamento .= "                   from basesr ";
    $sSqlFolhaPagamento .= "                  where r09_anousu = gerfsal.r14_anousu  ";
    $sSqlFolhaPagamento .= "                    and r09_mesusu = gerfsal.r14_mesusu  ";
    $sSqlFolhaPagamento .= "                    and r09_rubric = gerfsal.r14_rubric ";
    $sSqlFolhaPagamento .= "                    and r09_instit = gerfsal.r14_instit ";
    $sSqlFolhaPagamento .= "                    and r09_base in ('B004','B005') limit 1 ) is not null  ";
    $sSqlFolhaPagamento .= "            then 'S' ";
    $sSqlFolhaPagamento .= "          else 'N' ";
    $sSqlFolhaPagamento .= "        end                      as indicadorincidenciairrf, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_banco   as codigobancodepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_agencia as codigoagencdepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_conta   as codcontacorrbancodepfolhapagent, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_codban,'') as codigobancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_agencia,'') as codigoagenciabancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_conta,'')||coalesce(rh44_dvconta,'') as codigocontacorrentebancofuncionario, ";
    $sSqlFolhaPagamento .= "        case when r14_pd = 3 then rh27_descr else '' end as observacoes, ";
    $sSqlFolhaPagamento .= "        r14_instit||r14_rubric    as rubrica ";
    $sSqlFolhaPagamento .= "   from gerfsal ";
    $sSqlFolhaPagamento .= "        inner join rhpessoalmov on rhpessoalmov.rh02_anousu = gerfsal.r14_anousu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_mesusu = gerfsal.r14_mesusu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_regist = gerfsal.r14_regist ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_instit = gerfsal.r14_instit ";
    $sSqlFolhaPagamento .= "        left  join rhpesbanco   on rhpesbanco.rh44_seqpes   = rhpessoalmov.rh02_seqpes ";
    $sSqlFolhaPagamento .= "        inner join rhrubricas   on rhrubricas.rh27_rubric   = gerfsal.r14_rubric ";
    $sSqlFolhaPagamento .= "                               and rhrubricas.rh27_instit   = gerfsal.r14_instit ";
    $sSqlFolhaPagamento .= "        left  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
    $sSqlFolhaPagamento .= "        left  join rhlotavinc on rhlotavinc.rh25_codigo = rhlota.r70_codigo and rh25_anousu = {$iAnoUsuFim}";
    $sSqlFolhaPagamento .= "        left  join orctiporec on orctiporec.o15_codigo = rhlotavinc.rh25_recurso ";
    $sSqlFolhaPagamento .= "        left  join rhcontasrec on rhcontasrec.rh41_codigo = orctiporec.o15_codigo ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_instit = gerfsal.r14_instit ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_anousu = gerfsal.r14_anousu ";
    $sSqlFolhaPagamento .= "        left  join saltes on saltes.k13_conta = rhcontasrec.rh41_conta ";
    $sSqlFolhaPagamento .= "        left  join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz ";
    $sSqlFolhaPagamento .= "                                and conplanoreduz.c61_anousu = gerfsal.r14_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoexe on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sSqlFolhaPagamento .= "                              and conplanoreduz.c61_anousu = conplanoexe.c62_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon ";
    $sSqlFolhaPagamento .= "                           and conplanoreduz.c61_anousu = conplano.c60_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon ";
    $sSqlFolhaPagamento .= "                                and conplanoconta.c63_anousu = conplanoreduz.c61_anousu ";
    $sSqlFolhaPagamento .= "  where gerfsal.r14_instit in ({$this->sInstituicoes}) ";
    $sSqlFolhaPagamento .= "    and gerfsal.r14_anousu = {$iAnoUsuFim} ";
    $sSqlFolhaPagamento .= "    and gerfsal.r14_mesusu between {$iMesUsuIni} and {$iMesUsuFim} ";
   
    $sSqlFolhaPagamento .= " union all";
    $sSqlFolhaPagamento .= " select 2          as codigotipofolha, ";
    $sSqlFolhaPagamento .= "        r35_regist as codigoregistrofuncionario, ";
    $sSqlFolhaPagamento .= "        (cast(r35_anousu::varchar||'-'||r35_mesusu::varchar||'-'||(select fc_ultimodiames(r35_anousu,r35_mesusu))::varchar as date)) as datacompetenciafolha, ";
    $sSqlFolhaPagamento .= "        (cast(r35_anousu::varchar||'-'||r35_mesusu::varchar||'-'||(select fc_ultimodiames(r35_anousu,r35_mesusu))::varchar as date)) as datapagamentofolha, ";
    $sSqlFolhaPagamento .= "        '000'      as codigovantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        r35_valor  as valorvantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        case ";
    $sSqlFolhaPagamento .= "          when r35_pd = 1 then 'V' ";
    $sSqlFolhaPagamento .= "          when r35_pd = 2 then 'D' "; //-- os codigos  > R950 especificar como outros nas observaçoes
    $sSqlFolhaPagamento .= "          else 'O' ";
    $sSqlFolhaPagamento .= "        end as identificacaooperacao, ";
    $sSqlFolhaPagamento .= "        case  ";
    $sSqlFolhaPagamento .= "          when ( select r09_rubric  ";
    $sSqlFolhaPagamento .= "                   from basesr ";
    $sSqlFolhaPagamento .= "                  where r09_anousu = gerfs13.r35_anousu  ";
    $sSqlFolhaPagamento .= "                    and r09_mesusu = gerfs13.r35_mesusu  ";
    $sSqlFolhaPagamento .= "                    and r09_rubric = gerfs13.r35_rubric ";
    $sSqlFolhaPagamento .= "                    and r09_instit = gerfs13.r35_instit ";
    $sSqlFolhaPagamento .= "                    and r09_base in ('B004','B005') limit 1 ) is not null  ";
    $sSqlFolhaPagamento .= "            then 'S' ";
    $sSqlFolhaPagamento .= "          else 'N' ";
    $sSqlFolhaPagamento .= "        end                      as indicadorincidenciairrf, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_banco   as codigobancodepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_agencia as codigoagencdepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_conta   as codcontacorrbancodepfolhapagent, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_codban,'') as codigobancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_agencia,'') as codigoagenciabancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_conta,'')||coalesce(rh44_dvconta,'') as codigocontacorrentebancofuncionario, ";
    $sSqlFolhaPagamento .= "        case when r35_pd = 3 then rh27_descr else '' end as observacoes, ";
    $sSqlFolhaPagamento .= "        gerfs13.r35_instit||r35_rubric  as rubrica ";
    $sSqlFolhaPagamento .= "   from gerfs13 ";
    $sSqlFolhaPagamento .= "        inner join rhpessoalmov on rhpessoalmov.rh02_anousu = gerfs13.r35_anousu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_mesusu = gerfs13.r35_mesusu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_regist = gerfs13.r35_regist ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_instit = gerfs13.r35_instit ";
    $sSqlFolhaPagamento .= "        left  join rhpesbanco   on rhpesbanco.rh44_seqpes   = rhpessoalmov.rh02_seqpes ";
    $sSqlFolhaPagamento .= "        inner join rhrubricas   on rhrubricas.rh27_rubric   = gerfs13.r35_rubric ";
    $sSqlFolhaPagamento .= "                               and rhrubricas.rh27_instit   = gerfs13.r35_instit ";
    $sSqlFolhaPagamento .= "        left  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
    $sSqlFolhaPagamento .= "        left  join rhlotavinc on rhlotavinc.rh25_codigo = rhlota.r70_codigo and rh25_anousu = {$iAnoUsuFim}";
    $sSqlFolhaPagamento .= "        left  join orctiporec on orctiporec.o15_codigo = rhlotavinc.rh25_recurso ";
    $sSqlFolhaPagamento .= "        left  join rhcontasrec on rhcontasrec.rh41_codigo = orctiporec.o15_codigo ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_instit = gerfs13.r35_instit ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_anousu = gerfs13.r35_anousu ";
    $sSqlFolhaPagamento .= "        left  join saltes on saltes.k13_conta = rhcontasrec.rh41_conta ";
    $sSqlFolhaPagamento .= "        left  join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz ";
    $sSqlFolhaPagamento .= "                                and conplanoreduz.c61_anousu = gerfs13.r35_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoexe on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sSqlFolhaPagamento .= "                              and conplanoreduz.c61_anousu = conplanoexe.c62_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon ";
    $sSqlFolhaPagamento .= "                           and conplanoreduz.c61_anousu = conplano.c60_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon ";
    $sSqlFolhaPagamento .= "                                and conplanoconta.c63_anousu = conplanoreduz.c61_anousu ";
    $sSqlFolhaPagamento .= "  where gerfs13.r35_instit in ({$this->sInstituicoes}) ";
    $sSqlFolhaPagamento .= "    and gerfs13.r35_anousu = {$iAnoUsuFim} ";
    $sSqlFolhaPagamento .= "    and gerfs13.r35_mesusu between {$iMesUsuIni} and {$iMesUsuFim} ";
    $sSqlFolhaPagamento .= " union all ";
    $sSqlFolhaPagamento .= " select 4 as codigotipofolha, ";
    $sSqlFolhaPagamento .= "        r20_regist as codigoregistrofuncionario, ";
    $sSqlFolhaPagamento .= "        (cast(r20_anousu::varchar||'-'||r20_mesusu::varchar||'-'||(select fc_ultimodiames(r20_anousu,r20_mesusu))::varchar as date)) as datacompetenciafolha, ";
    $sSqlFolhaPagamento .= "        (cast(r20_anousu::varchar||'-'||r20_mesusu::varchar||'-'||(select fc_ultimodiames(r20_anousu,r20_mesusu))::varchar as date)) as datapagamentofolha, ";
    $sSqlFolhaPagamento .= "        '000'      as codigovantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        r20_valor  as valorvantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        case ";
    $sSqlFolhaPagamento .= "          when r20_pd = 1 then 'V' ";
    $sSqlFolhaPagamento .= "          when r20_pd = 2 then 'D' ";
    $sSqlFolhaPagamento .= "          else 'O'";
    $sSqlFolhaPagamento .= "        end as identificacaooperacao, ";
    $sSqlFolhaPagamento .= "        case  ";
    $sSqlFolhaPagamento .= "          when ( select r09_rubric  ";
    $sSqlFolhaPagamento .= "                   from basesr ";
    $sSqlFolhaPagamento .= "                  where r09_anousu = gerfres.r20_anousu  ";
    $sSqlFolhaPagamento .= "                    and r09_mesusu = gerfres.r20_mesusu  ";
    $sSqlFolhaPagamento .= "                    and r09_rubric = gerfres.r20_rubric ";
    $sSqlFolhaPagamento .= "                    and r09_instit = gerfres.r20_instit ";
    $sSqlFolhaPagamento .= "                    and r09_base in ('B004','B005') limit 1 ) is not null  ";
    $sSqlFolhaPagamento .= "            then 'S' ";
    $sSqlFolhaPagamento .= "          else 'N' ";
    $sSqlFolhaPagamento .= "        end                      as indicadorincidenciairrf, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_banco   as codigobancodepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_agencia as codigoagencdepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_conta   as codcontacorrbancodepfolhapagent, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_codban,'') as codigobancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_agencia,'') as codigoagenciabancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_conta,'')||coalesce(rh44_dvconta,'') as codigocontacorrentebancofuncionario, ";
    $sSqlFolhaPagamento .= "        case when r20_pd = 3 then rh27_descr else '' end as observacoes, ";
    $sSqlFolhaPagamento .= "        r20_instit||r20_rubric as rubrica ";
    $sSqlFolhaPagamento .= "   from gerfres ";
    $sSqlFolhaPagamento .= "        inner join rhpessoalmov on rhpessoalmov.rh02_anousu = gerfres.r20_anousu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_mesusu = gerfres.r20_mesusu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_regist = gerfres.r20_regist ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_instit = gerfres.r20_instit ";
    $sSqlFolhaPagamento .= "        left  join rhpesbanco   on rhpesbanco.rh44_seqpes   = rhpessoalmov.rh02_seqpes ";
    $sSqlFolhaPagamento .= "        inner join rhrubricas   on rhrubricas.rh27_rubric   = gerfres.r20_rubric ";
    $sSqlFolhaPagamento .= "                               and rhrubricas.rh27_instit   = gerfres.r20_instit ";
    $sSqlFolhaPagamento .= "        left  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
    $sSqlFolhaPagamento .= "        left  join rhlotavinc on rhlotavinc.rh25_codigo = rhlota.r70_codigo and rh25_anousu = {$iAnoUsuFim}";
    $sSqlFolhaPagamento .= "        left  join orctiporec on orctiporec.o15_codigo = rhlotavinc.rh25_recurso ";
    $sSqlFolhaPagamento .= "        left  join rhcontasrec on rhcontasrec.rh41_codigo = orctiporec.o15_codigo ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_instit          = gerfres.r20_instit ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_anousu          = gerfres.r20_anousu ";
    $sSqlFolhaPagamento .= "        left  join saltes on saltes.k13_conta = rhcontasrec.rh41_conta ";
    $sSqlFolhaPagamento .= "        left  join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz ";
    $sSqlFolhaPagamento .= "                                and conplanoreduz.c61_anousu = gerfres.r20_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoexe on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sSqlFolhaPagamento .= "                              and conplanoreduz.c61_anousu = conplanoexe.c62_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon ";
    $sSqlFolhaPagamento .= "                           and conplanoreduz.c61_anousu = conplano.c60_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon ";
    $sSqlFolhaPagamento .= "                                and conplanoconta.c63_anousu = conplanoreduz.c61_anousu ";
    $sSqlFolhaPagamento .= "  where gerfres.r20_instit in ({$this->sInstituicoes}) ";
    $sSqlFolhaPagamento .= "    and gerfres.r20_anousu = {$iAnoUsuFim} ";
    $sSqlFolhaPagamento .= "    and gerfres.r20_mesusu between {$iMesUsuIni} and {$iMesUsuFim} ";
    $sSqlFolhaPagamento .= " union all ";
    $sSqlFolhaPagamento .= " select 5 as codigotipofolha, ";
    $sSqlFolhaPagamento .= "        r48_regist as codigoregistrofuncionario, ";
    $sSqlFolhaPagamento .= "        (cast(r48_anousu::varchar||'-'||r48_mesusu::varchar||'-'||(select fc_ultimodiames(r48_anousu,r48_mesusu))::varchar as date)) as datacompetenciafolha, ";
    $sSqlFolhaPagamento .= "        (cast(r48_anousu::varchar||'-'||r48_mesusu::varchar||'-'||(select fc_ultimodiames(r48_anousu,r48_mesusu))::varchar as date)) as datapagamentofolha, ";
    $sSqlFolhaPagamento .= "        '000'      as codigovantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        r48_valor  as valorvantagemdescontototalizador, ";
    $sSqlFolhaPagamento .= "        case ";
    $sSqlFolhaPagamento .= "          when r48_pd = 1 then 'V' ";
    $sSqlFolhaPagamento .= "          when r48_pd = 2 then 'D' ";
    $sSqlFolhaPagamento .= "          else 'O'";
    $sSqlFolhaPagamento .= "        end as identificacaooperacao, ";
    $sSqlFolhaPagamento .= "        case  ";
    $sSqlFolhaPagamento .= "          when ( select r09_rubric  ";
    $sSqlFolhaPagamento .= "                   from basesr ";
    $sSqlFolhaPagamento .= "                  where r09_anousu = gerfcom.r48_anousu  ";
    $sSqlFolhaPagamento .= "                    and r09_mesusu = gerfcom.r48_mesusu  ";
    $sSqlFolhaPagamento .= "                    and r09_rubric = gerfcom.r48_rubric ";
    $sSqlFolhaPagamento .= "                    and r09_instit = gerfcom.r48_instit ";
    $sSqlFolhaPagamento .= "                    and r09_base in ('B004','B005') limit 1 ) is not null  ";
    $sSqlFolhaPagamento .= "            then 'S' ";
    $sSqlFolhaPagamento .= "          else 'N' ";
    $sSqlFolhaPagamento .= "        end                      as indicadorincidenciairrf, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_banco   as codigobancodepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_agencia as codigoagencdepositofolhapagentidad, ";
    $sSqlFolhaPagamento .= "        conplanoconta.c63_conta   as codcontacorrbancodepfolhapagent, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_codban,'') as codigobancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_agencia,'') as codigoagenciabancofuncionario, ";
    $sSqlFolhaPagamento .= "        coalesce(rh44_conta,'')||coalesce(rh44_dvconta,'') as codigocontacorrentebancofuncionario, ";
    $sSqlFolhaPagamento .= "        case when r48_pd = 3 then rh27_descr else '' end as observacoes, ";
    $sSqlFolhaPagamento .= "        r48_instit||r48_rubric  as rubrica ";
    $sSqlFolhaPagamento .= "   from gerfcom ";
    $sSqlFolhaPagamento .= "        inner join rhpessoalmov on rhpessoalmov.rh02_anousu = gerfcom.r48_anousu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_mesusu = gerfcom.r48_mesusu ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_regist = gerfcom.r48_regist ";
    $sSqlFolhaPagamento .= "                               and rhpessoalmov.rh02_instit = gerfcom.r48_instit ";
    $sSqlFolhaPagamento .= "        left  join rhpesbanco   on rhpesbanco.rh44_seqpes   = rhpessoalmov.rh02_seqpes ";
    $sSqlFolhaPagamento .= "        inner join rhrubricas   on rhrubricas.rh27_rubric   = gerfcom.r48_rubric ";
    $sSqlFolhaPagamento .= "                               and rhrubricas.rh27_instit   = gerfcom.r48_instit ";
    $sSqlFolhaPagamento .= "        left  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
    $sSqlFolhaPagamento .= "        left  join rhlotavinc on rhlotavinc.rh25_codigo = rhlota.r70_codigo and rh25_anousu = {$iAnoUsuFim}";
    $sSqlFolhaPagamento .= "        left  join orctiporec on orctiporec.o15_codigo = rhlotavinc.rh25_recurso ";
    $sSqlFolhaPagamento .= "        left  join rhcontasrec on rhcontasrec.rh41_codigo = orctiporec.o15_codigo ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_instit = gerfcom.r48_instit ";
    $sSqlFolhaPagamento .= "                              and rhcontasrec.rh41_anousu = gerfcom.r48_anousu ";
    $sSqlFolhaPagamento .= "        left  join saltes on saltes.k13_conta = rhcontasrec.rh41_conta ";
    $sSqlFolhaPagamento .= "        left  join conplanoreduz on conplanoreduz.c61_reduz  = saltes.k13_reduz ";
    $sSqlFolhaPagamento .= "                                and conplanoreduz.c61_anousu = gerfcom.r48_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoexe on conplanoexe.c62_reduz    = conplanoreduz.c61_reduz ";
    $sSqlFolhaPagamento .= "                              and conplanoreduz.c61_anousu = conplanoexe.c62_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon ";
    $sSqlFolhaPagamento .= "                           and conplanoreduz.c61_anousu = conplano.c60_anousu ";
    $sSqlFolhaPagamento .= "        left  join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon ";
    $sSqlFolhaPagamento .= "                                and conplanoconta.c63_anousu = conplanoreduz.c61_anousu ";
    $sSqlFolhaPagamento .= "  where gerfcom.r48_instit in ({$this->sInstituicoes}) ";
    $sSqlFolhaPagamento .= "    and gerfcom.r48_anousu = {$iAnoUsuFim} ";
    $sSqlFolhaPagamento .= "    and gerfcom.r48_mesusu between {$iMesUsuIni} and {$iMesUsuFim} ";
    $sSqlFolhaPagamento .= " ) as x order by codigoregistrofuncionario, datacompetenciafolha, codigotipofolha, rubrica ";
    
    
    return $sSqlFolhaPagamento;
  }
}
