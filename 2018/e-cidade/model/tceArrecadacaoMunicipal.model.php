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

class tceArrecadacaoMunicipal extends tceEstruturaBasica {
  
  const NOME_ARQUIVO = 'TCE_4010.TXT';
  const CODIGO_ARQUIVO = 36;
  /**
   * Codigo da instituicao
   *
   * @var integer
   */
  public $iInstit = "";
  /**
   * Data do periodo inicial de geracao do arquivo
   *
   * @var string  string no formato do banco (AAAA-MM-DD) com a data inicial do periodo a ser gerado o arquivo
   */
  public $sDataIni = "";
  public $sDataFim = "";
  public $sCodRemessa = "";
  
  private $oLeiaute   = null;
  /**
   * Metodo construtor
   *
   * @param integer           $iInstit
   * @param string            $sCodRemessa
   * @param string            $sDataIni
   * @param string            $sDataFim
   * @param db_utils/stdClass $oData
   */ 
  function __construct($iInstit, $sCodRemessa, $sDataIni, $sDataFim, $oData, $oLeiaute=null) {

    try {
      parent::__construct(self::CODIGO_ARQUIVO, self::NOME_ARQUIVO);
    } catch ( Exception $e ) {
      throw $e->getMessage();
    }
    
    $this->iInstit = $iInstit;
    $this->sDataIni = $sDataIni;
    $this->sDataFim = $sDataFim;
    $this->sCodRemessa = $sCodRemessa;
    if ($oLeiaute != null) {
      $this->oLeiaute =$oLeiaute;
    }
  }
  
  function getNomeArquivo() {

    return self::NOME_ARQUIVO;
  }
  
  /**
   * Medodo que gera o arquivo TCE_4010
   *
   * @return unknown
   */
  function geraArquivo() {

    db_criatermometro('terTCE4010', 'Arquivo TCE4010...', 'blue', 1);
    
    $this->oTxtLayout->setByLineOfDBUtils($this->cabecalhoPadrao($this->iInstit, 
                                                                 $this->sDataIni, 
                                                                 $this->sDataFim, 
                                                                 $this->sCodRemessa), 1);

    $rsArrecadacao = db_query($this->sqlArrecadacao($this->iInstit, $this->sDataIni, $this->sDataFim));
    $iNumRows = pg_num_rows($rsArrecadacao);
    $iTotalRegistros = 0;
    $iQuant          = 0;

    for($i = 0; $i < $iNumRows; $i ++) {

      $iNew = intval($i*100/$iNumRows);
      if ($iNew > $iQuant) {

        $iQuant = $iNew;
        db_atutermometro($i, $iNumRows, "terTCE4010");
      }

      // guardar sessao notificando o usuario do estado do processamento
      $oArrecadacao = db_utils::fieldsMemory($rsArrecadacao, $i);
      
      $oAcrescimosDescontos = $this->getAcrescimosDescontos($oArrecadacao->k00_numpre, 
                                                            $oArrecadacao->k00_receit, 
                                                            $oArrecadacao->k00_valor, 
                                                            $oArrecadacao->k00_dtvenc, 
                                                            $oArrecadacao->k00_dtoper);

      $oArrecadacao->valormultajurosdescontos  = ( $oAcrescimosDescontos->juros + 
                                                   $oAcrescimosDescontos->multa -
                                                   $oAcrescimosDescontos->desconto );
      ///// sandro - verificar depois
      $oArrecadacao->valormultajurosdescontos = 0;     

      $formatar_valormoeda = ( $oAcrescimosDescontos->valorcorrigido );

      //if($formatar_valormoeda < 0){
        $formatar_valormoeda = abs($formatar_valormoeda);
     // }else{
     //   $formatar_valormoeda = db_formatar(abs($formatar_valormoeda), 's', '0', 17, 'e');
     // }

      $oArrecadacao->valormoeda = $formatar_valormoeda;
      
      $oArrecadacao->dataregistrocontabilidade = "";
      
      $oArrecadacao->datacriacaoreceita = db_getsession("DB_anousu") . "-01-01";

      $oArrecadacao->estruturareceita = $this->getEstrutural($oArrecadacao->k00_receit);

      $oArrecadacao->numeroincricaoestadual += 0;
      
      $oArrecadacao->codigoidentificador = $oArrecadacao->ano_codigoidentificador.str_pad($oArrecadacao->codigoidentificador,26,"0",STR_PAD_LEFT);

      switch ($oArrecadacao->tipooperacao) {
        
        // Cadastro Inicial
        case 1:
          
          $oArrecadacao->estruturareceita                  = str_repeat('0', 20);
          $oArrecadacao->codorgunidorcavinccodcontblvsg    = '0';
          $oArrecadacao->orgaotributario                   = '0000';
          $oArrecadacao->codigocontabalanceteverificacaosg = str_repeat('0', 20);

        break;
        
        // Recebimento em Espécie
        case 2:
         
        	$oArrecadacao->dataregistrocontabilidade = $this->getDataBoletim( $oArrecadacao->k00_numpre, $oArrecadacao->k00_numpar, $oArrecadacao->k00_receit );
        	
          //Se o tipo de operação for 2 e o estrutural da receita iniciar com 4, retiramos o 4 do estrutural da receita.	
          if(substr($oArrecadacao->estruturareceita,0,1) == "4") {
        	$oArrecadacao->estruturareceita = substr($oArrecadacao->estruturareceita,1);
          }	
          $oArrecadacao->codigocontabalanceteverificacaosg = $this->getEstruturalBalancete($oArrecadacao->codigocontapagadora);
        break;
        
        // Dívida Ativa
        case 4:
          
          $oArrecadacao->estruturareceita                  = '623171001000000';
          $oArrecadacao->codigocontabalanceteverificacaosg = '122110200000000';
        break;
        // Outras Operacoes
        case 99:
          
          $oArrecadacao->estruturareceita                  = str_repeat('0', 20);
          $oArrecadacao->codorgunidorcavinccodcontblvsg    = '0';
          $oArrecadacao->orgaotributario                   = '0000';
          $oArrecadacao->codigocontabalanceteverificacaosg = str_repeat('0', 20);
          $oArrecadacao->historico                        .= ' Debito cancelado.';  

        break;
        
      }
      
      $this->oTxtLayout->setByLineOfDBUtils($oArrecadacao, 3);
      $iTotalRegistros ++;
    }
    
    $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
    unset($rsArrecadacao);
    
    return true;
  
  }
  
  /**
   * Metodo para calcular descontos e acrescimos de um numpre
   *
   * @param  integer             $iNumpre       Numpre do debito
   * @param  integer             $iReceita      Codigo da receita
   * @param  float               $nValor        valor para calculo
   * @param  string              $sVencimento   String no formato de data do banco, com a data de vencimento do debito
   * @param  string              $sOperacao     String no formato de data do banco, com a data de operacao do debito
   * @return db_utils/stdClass  
   */
  
  function getAcrescimosDescontos($iNumpre = 0, $iReceita = 0, $nValor = 0, $sVencimento = null, $sOperacao = null) {

    $sqlAcrescimosDescontos = " select coalesce(fc_juros({$iReceita}, ";
    $sqlAcrescimosDescontos .= "                         '{$sVencimento}', ";
    $sqlAcrescimosDescontos .= "                          '" . date('Y-m-d', db_getsession('DB_datausu')) . "', ";
    $sqlAcrescimosDescontos .= "                          '" . date('Y-m-d', db_getsession('DB_datausu')) . "', ";
    $sqlAcrescimosDescontos .= "                          false, ";
    $sqlAcrescimosDescontos .= "                          " . db_getsession('DB_anousu') . "),0) as juros, ";
    $sqlAcrescimosDescontos .= "        coalesce(fc_multa('{$iReceita}', ";
    $sqlAcrescimosDescontos .= "                          '{$sVencimento}',";
    $sqlAcrescimosDescontos .= "                          '" . date('Y-m-d', db_getsession('DB_datausu')) . "',";
    $sqlAcrescimosDescontos .= "                          '{$sOperacao}',";
    $sqlAcrescimosDescontos .= "                          " . db_getsession('DB_anousu') . "),0) as multa,";
    $sqlAcrescimosDescontos .= "        coalesce(fc_desconto({$iReceita},";
    $sqlAcrescimosDescontos .= "                             '{$sVencimento}',";
    $sqlAcrescimosDescontos .= "                             {$nValor},";
    $sqlAcrescimosDescontos .= "                             0,";
    $sqlAcrescimosDescontos .= "                             false,";
    $sqlAcrescimosDescontos .= "                             '{$sVencimento}',";
    $sqlAcrescimosDescontos .= "                             " . db_getsession('DB_anousu') . ",";
    $sqlAcrescimosDescontos .= "                             {$iNumpre}),0) as desconto,";
    $sqlAcrescimosDescontos .= "        coalesce(fc_corre({$iReceita},";
    $sqlAcrescimosDescontos .= "                             '{$sOperacao}',";
    $sqlAcrescimosDescontos .= "                             {$nValor},";
    $sqlAcrescimosDescontos .= "                             '{$sVencimento}',";
    $sqlAcrescimosDescontos .= "                             " . db_getsession('DB_anousu') . ",";
    $sqlAcrescimosDescontos .= "                             '{$sVencimento}'),0) as valorcorrigido";
    
    $rsAcrescimosDescontos = db_query($sqlAcrescimosDescontos);
    if (pg_num_rows($rsAcrescimosDescontos) > 0) {
      $oAcrescimosDescontos = db_utils::fieldsMemory($rsAcrescimosDescontos, 0);
      // Calculando o total dos acrescimos     
      $oAcrescimosDescontos->nTotalAcrescimos = round((($nValor * $oAcrescimosDescontos->juros) + ($nValor * $oAcrescimosDescontos->multa) - ($oAcrescimosDescontos->desconto)), 2);
      if ($oAcrescimosDescontos->nTotalAcrescimos < 0) {
        $oAcrescimosDescontos->nTotalAcrescimos = 0;
      }
      return $oAcrescimosDescontos;
    } else {
      return false;
    }
  }
  
  /**
   * Metodo para buscar a data do boletim
   *
   * @param  integer      $iNumpre     Numpre do debito
   * @param  integer      $iNumpar     Parcela do debito
   * @param  integer      $iReceita    Receita do debito
   * @return string                    Retorna string com a data no formato do banco
   */
  function getDataBoletim($iNumpre,$iNumpar,$iReceita) {
  	
    $sSqlDataBoletim  = " select c77_databol as databoletim, ";
    $sSqlDataBoletim .= "        x.k12_id, ";
    $sSqlDataBoletim .= "        x.k12_data, ";
    $sSqlDataBoletim .= "        x.k12_autent ";
    $sSqlDataBoletim .= "   from (  select k12_id,k12_data,k12_autent ";
    $sSqlDataBoletim .= "             from cornump ";
    $sSqlDataBoletim .= "            where cornump.k12_numpre = {$iNumpre}";
    $sSqlDataBoletim .= "              and cornump.k12_numpar = {$iNumpar}";
    $sSqlDataBoletim .= "              and cornump.k12_receit = {$iReceita}";
    $sSqlDataBoletim .= "         union  ";
    $sSqlDataBoletim .= "           select k12_id,k12_data,k12_autent ";
    $sSqlDataBoletim .= "             from arreidret ";
    $sSqlDataBoletim .= "                  inner join disrec    on disrec.idret         = arreidret.idret ";
    $sSqlDataBoletim .= "                  inner join corcla    on corcla.k12_codcla    = disrec.codcla ";
    $sSqlDataBoletim .= "            where arreidret.k00_numpre = {$iNumpre}";
    $sSqlDataBoletim .= "              and arreidret.k00_numpar = {$iNumpar}";
    $sSqlDataBoletim .= "              and disrec.k00_receit    = {$iReceita}";
    $sSqlDataBoletim .= " ) as x  ";
    $sSqlDataBoletim .= "        inner join corrente      on corrente.k12_id           = x.k12_id ";
    $sSqlDataBoletim .= "                                and corrente.k12_data         = x.k12_data ";
    $sSqlDataBoletim .= "                                and corrente.k12_autent       = x.k12_autent ";
    $sSqlDataBoletim .= "        inner join conlancambol  on conlancambol.c77_id       = x.k12_id ";
    $sSqlDataBoletim .= "                                and conlancambol.c77_dataproc = x.k12_data ";
    $sSqlDataBoletim .= "                                and conlancambol.c77_autent   = x.k12_autent ";
    $rsDataBoletim    = db_query($sSqlDataBoletim);
    
    if (pg_num_rows($rsDataBoletim) > 0) {
      $oDataBoletim     = db_utils::fieldsMemory($rsDataBoletim,0); 
      return $oDataBoletim->databoletim;
    } else {
    	return false;
    }
        
  }
  
  /**
   * Metodo para buscar o codigo estrutural de 
   *
   * @param   integer    $iReceita  Codigo da receita tabrec.k02_codigo
   * @return  string                estrutural
   */
  function getEstrutural($iReceita) {
   	
    $sSqlEstrut  = " select k02_codigo, ";
    $sSqlEstrut .= "        k02_estorc as estrutural"; 
    $sSqlEstrut .= "   from taborc "; 
    $sSqlEstrut .= "  where k02_codigo = {$iReceita} and k02_anousu = ".db_getsession('DB_anousu');
    $sSqlEstrut .= " union ";
    $sSqlEstrut .= " select k02_codigo, ";
    $sSqlEstrut .= "        k02_estpla as estrutural";
    $sSqlEstrut .= "   from tabplan ";
    $sSqlEstrut .= "  where k02_codigo = {$iReceita} and k02_anousu = ".db_getsession('DB_anousu');
    $rsEstrut    = db_query($sSqlEstrut);
    if (pg_num_rows($rsEstrut) > 0) {
      $oEstrut = db_utils::fieldsMemory($rsEstrut,0);
      return $oEstrut->estrutural;
    } else {
    	return false;
    }
    
    
    
  }
  
  /**
   * retorna o estrutura da conta pagadora do débito
   *
   * @param integer $iReduz código reduzido da conta pagadora
   * @return string
   */
  function getEstruturalBalancete($iReduz) {
    
    $sEstrutural = str_repeat('0', 15);
    $sSqlEstrut  = " select c60_estrut ";
    $sSqlEstrut .= "   from conplanoreduz "; 
    $sSqlEstrut .= "        inner join conplano on c61_codcon = c60_codcon "; 
    $sSqlEstrut .= "                           and c61_anousu = c60_anousu"; 
    $sSqlEstrut .= "  where c61_reduz  = {$iReduz} ";
    $sSqlEstrut .= "    and c61_anousu = ".db_getsession("DB_anousu");
    $rsEstrut    = db_query($sSqlEstrut);
    if (pg_num_rows($rsEstrut) > 0) {
      $sEstrutural = db_utils::fieldsMemory($rsEstrut,0)->c60_estrut;
    }
    return $sEstrutural;
  }
  
  /**
   * Metodo para construcao do select para busca dos dados
   *
   * @param  integer   $iInstit     Codigo da instituicao
   * @param  string    $sDataini    string no formato do banco (AAAA-MM-DD) com a data inicial do periodo a ser gerado o arquivo
   * @param  string    $sDatafim    string no formato do banco (AAAA-MM-DD) com a data final do periodo a ser gerado o arquivo
   * @return string                 string sql com o select para busca dos registros
   */
  
  function sqlArrecadacao($iInstit, $sDataini, $sDatafim) {

    $sSqlArrecadacao  = "select k00_numpre||lpad(k00_numpar,3,0)||k00_receit as codigobarras,";
    $sSqlArrecadacao .= "       min(k00_dtpaga)    as datapagamento, ";
    $sSqlArrecadacao .= "       '0000-00-00'       as dataregistrocontabilidade, ";
    $sSqlArrecadacao .= "       sum(valor)         as valororiginal, ";
    $sSqlArrecadacao .= "       0                  as valormultajurosdescontos, ";
    $sSqlArrecadacao .= "       sum(valor_pago)    as valorrecibo, ";
    $sSqlArrecadacao .= "       x.k00_tipo         as codigotributoarrecadado, ";
    $sSqlArrecadacao .= "       arretipo.k00_descr as nometributoarrecadado, ";
    $sSqlArrecadacao .= "       0                  as codigocontabalanceteverificacaosg, ";
    $sSqlArrecadacao .= "       codtrib            as codorgunidorcavinccodcontblvsg, ";
    $sSqlArrecadacao .= "       0                  as reservadofuturo, ";
    $sSqlArrecadacao .= "       k00_numcgm         as codigocontribuinte, ";
    $sSqlArrecadacao .= "       z01_nome           as nomecontribuinte, ";
    $sSqlArrecadacao .= "       min(z01_cgccpf)    as cnpjcpfcontribuinte, ";
    $sSqlArrecadacao .= "       min(translate(z01_incest, '/-.',''))    as numeroincricaoestadual, ";
    $sSqlArrecadacao .= "       0                  as estruturareceita, ";
    $sSqlArrecadacao .= "       codtrib            as orgaotributario, "; 
    $sSqlArrecadacao .= "       min(k00_dtoper)    as dataoperacao, ";
    $sSqlArrecadacao .= "       min(k00_dtvenc)    as vencimento, ";
    $sSqlArrecadacao .= "       tipo_oper          as tipooperacao, ";
    $sSqlArrecadacao .= "       0                  as modalidade, ";
    $sSqlArrecadacao .= "       6                  as tipomoeda, ";
    $sSqlArrecadacao .= "       2                  as numerocasasdecimais, ";
    $sSqlArrecadacao .= "       0                  as valormoeda, ";
    $sSqlArrecadacao .= "       codigocontapagadora,";
    $sSqlArrecadacao .= "       codigobanco, ";
    $sSqlArrecadacao .= "       codigoagencia, ";
    $sSqlArrecadacao .= "       codigocontacorrente,";
    $sSqlArrecadacao .= "       extract(year from min(k00_dtoper)) as ano_codigoidentificador, ";
    $sSqlArrecadacao .= "       k00_numpre||lpad(k00_numpar,3,0)||k00_receit as codigoidentificador, ";
    $sSqlArrecadacao .= "       ( select replace(replace(k00_histtxt,'\\n',''),'\\r','') ";
    $sSqlArrecadacao .= "           from arrehist ";
    $sSqlArrecadacao .= "          where k00_numpre = x.k00_numpre ";
    $sSqlArrecadacao .= "            and k00_numpar = x.k00_numpar ";
    $sSqlArrecadacao .= "          order by k00_idhist desc limit 1 ) as historico, ";
    $sSqlArrecadacao .= "       k00_numpre,";
    $sSqlArrecadacao .= "       k00_numpar,";
    $sSqlArrecadacao .= "       min(k00_dtvenc) as k00_dtvenc,";
    $sSqlArrecadacao .= "       min(k00_dtoper) as k00_dtoper,";
    $sSqlArrecadacao .= "       k00_receit,";
    $sSqlArrecadacao .= "       sum(valor)         as k00_valor";
    $sSqlArrecadacao .= "  from ( select a.k00_numpre,";
    $sSqlArrecadacao .= "                a.k00_numpar,";
    $sSqlArrecadacao .= "                a.k00_receit,";
    $sSqlArrecadacao .= "                a.k00_tipo, ";
    $sSqlArrecadacao .= "                a.k00_numcgm, ";
    $sSqlArrecadacao .= "                a.k00_dtoper, ";
    $sSqlArrecadacao .= "                a.k00_dtvenc, ";
    $sSqlArrecadacao .= "                '          ' as k00_dtpaga,";
    $sSqlArrecadacao .= "                coalesce(a.k00_valor,0) as valor,";
    $sSqlArrecadacao .= "                0                       as valor_pago,";
    $sSqlArrecadacao .= "                case ";
    $sSqlArrecadacao .= "                  when (select k03_tipo from arretipo where arretipo.k00_tipo = a.k00_tipo) = 5 ";
    $sSqlArrecadacao .= "                    then '4' ";
    $sSqlArrecadacao .= "                  else '1' ";
    $sSqlArrecadacao .= "                end as tipo_oper,";
    $sSqlArrecadacao .= "                lpad('0', 5, '0') as codigobanco, ";
    $sSqlArrecadacao .= "                lpad('0', 5, '0') as codigoagencia, ";
    $sSqlArrecadacao .= "                lpad('0', 20, '0') as codigocontacorrente, ";
    $sSqlArrecadacao .= "                null as codigocontapagadora";
    $sSqlArrecadacao .= "           from arrecad a";
    $sSqlArrecadacao .= "                inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre";
    $sSqlArrecadacao .= "                                     and k00_instit = {$iInstit}";
    $sSqlArrecadacao .= "          where k00_dtoper between '{$sDataini}' and '{$sDatafim}'";
    $sSqlArrecadacao .= "        union all";
    $sSqlArrecadacao .= "         select a.k00_numpre,";
    $sSqlArrecadacao .= "                a.k00_numpar,";
    $sSqlArrecadacao .= "                a.k00_receit,";
    $sSqlArrecadacao .= "                arrecant.k00_tipo,";
    $sSqlArrecadacao .= "                a.k00_numcgm, ";
    $sSqlArrecadacao .= "                arrecant.k00_dtoper, ";
    $sSqlArrecadacao .= "                arrecant.k00_dtvenc, ";
    $sSqlArrecadacao .= "                k00_dtpaga::varchar,";
    $sSqlArrecadacao .= "                coalesce( arrecant.k00_valor,0) as valor,";
    $sSqlArrecadacao .= "                coalesce( a.k00_valor,0)        as valor_pago, ";
    $sSqlArrecadacao .= "                '2' as tipo_oper, ";
    $sSqlArrecadacao .= "                lpad(coalesce(db90_codban::varchar, '0'), 5, '0') as codigobanco, ";
    $sSqlArrecadacao .= "                lpad(coalesce(db89_codagencia::varchar, '0'), 5, '0') as codigoagencia, ";
    $sSqlArrecadacao .= "                lpad(coalesce(db83_conta::varchar, '0'), 20, '0') as codigocontacorrente, ";
    $sSqlArrecadacao .= "                a.k00_conta as codigocontapagadora";
    $sSqlArrecadacao .= "           from arrepaga a ";
    $sSqlArrecadacao .= "                inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre";
    $sSqlArrecadacao .= "                                     and k00_instit = {$iInstit}";
    $sSqlArrecadacao .= "                inner join arrecant   on arrecant.k00_numpre = a.k00_numpre";
    $sSqlArrecadacao .= "                                     and arrecant.k00_numpar = a.k00_numpar";
    $sSqlArrecadacao .= "                                     and arrecant.k00_receit = a.k00_receit";
    $sSqlArrecadacao .= "                inner join saltes          on a.k00_conta              = saltes.k13_conta ";
    $sSqlArrecadacao .= "                inner join conplanoreduz   on conplanoreduz.c61_reduz  = saltes.k13_reduz "; 
    $sSqlArrecadacao .= "                                          and conplanoreduz.c61_anousu = extract(year from a.k00_dtpaga) ";
    $sSqlArrecadacao .= "                 left join conplanocontabancaria on  conplanocontabancaria.c56_codcon = conplanoreduz.c61_codcon "; 
    $sSqlArrecadacao .= "                                                and c56_anousu = c61_anousu "; 
    $sSqlArrecadacao .= "                 left join contabancaria on  contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria" ;
    $sSqlArrecadacao .= "                 left join bancoagencia  on db83_bancoagencia              = db89_sequencial " ;
    $sSqlArrecadacao .= "                 left join db_bancos     on db90_codban   = db89_db_bancos" ;
    $sSqlArrecadacao .= "          where a.k00_dtpaga between '{$sDataini}' and '{$sDatafim}'";
    $sSqlArrecadacao .= "         union all";
    $sSqlArrecadacao .= "          select a.k00_numpre,";
    $sSqlArrecadacao .= "                 a.k00_numpar,";
    $sSqlArrecadacao .= "                 a.k00_receit,";
    $sSqlArrecadacao .= "                 a.k00_tipo,";
    $sSqlArrecadacao .= "                 a.k00_numcgm, ";
    $sSqlArrecadacao .= "                 a.k00_dtoper, ";
    $sSqlArrecadacao .= "                 a.k00_dtvenc, ";
    $sSqlArrecadacao .= "                 '          ' as k00_dtpaga,";
    $sSqlArrecadacao .= "                 coalesce( a.k00_valor,0) as valor,";
    $sSqlArrecadacao .= "                 0                        as valor_pago, ";
    $sSqlArrecadacao .= "                '99' as tipo_oper, ";
    $sSqlArrecadacao .= "                lpad('0', 5, '0') as codigobanco, ";
    $sSqlArrecadacao .= "                lpad('0', 5, '0') as codigoagencia, ";
    $sSqlArrecadacao .= "                lpad('0', 20, '0') as codigocontacorrente,"; 
    $sSqlArrecadacao .= "                null as codigocontapagadora";
    $sSqlArrecadacao .= "            from arrecant a";
    $sSqlArrecadacao .= "                 inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre";
    $sSqlArrecadacao .= "                                      and k00_instit = {$iInstit} ";
    $sSqlArrecadacao .= "                 left join arrepaga    on arrepaga.k00_numpre = a.k00_numpre";
    $sSqlArrecadacao .= "                                      and arrepaga.k00_numpar = a.k00_numpar";
    $sSqlArrecadacao .= "                                      and arrepaga.k00_receit = a.k00_receit";
    $sSqlArrecadacao .= "           where arrepaga.k00_numpre is null";
    $sSqlArrecadacao .= "             and a.k00_dtoper between '{$sDataini}' and '{$sDatafim}'";
    $sSqlArrecadacao .= " ) as x ";
    $sSqlArrecadacao .= "   inner join arretipo  on arretipo.k00_tipo = x.k00_tipo ";
    $sSqlArrecadacao .= "   inner join cgm       on cgm.z01_numcgm    = k00_numcgm ";
    $sSqlArrecadacao .= "   inner join db_config on db_config.codigo  = {$iInstit} ";
    
    //$sSqlArrecadacao .= "   where k00_numpre = 9017257 ";

    $sSqlArrecadacao .= " group by  k00_numpre, ";
    $sSqlArrecadacao .= "           k00_numpar, ";
    $sSqlArrecadacao .= "           k00_receit, ";
    $sSqlArrecadacao .= "           x.k00_tipo, ";
    $sSqlArrecadacao .= "           k00_descr,  ";
    $sSqlArrecadacao .= "           k00_numcgm, ";
    $sSqlArrecadacao .= "           z01_nome,   ";
    $sSqlArrecadacao .= "           z01_cgccpf, ";
    $sSqlArrecadacao .= "           z01_incest, ";
    $sSqlArrecadacao .= "           k00_dtoper, ";
    $sSqlArrecadacao .= "           codtrib, ";
    $sSqlArrecadacao .= "           tipo_oper,";
    $sSqlArrecadacao .= "           k00_dtvenc,  ";
    $sSqlArrecadacao .= "           codigobanco, ";
    $sSqlArrecadacao .= "           codigocontapagadora, ";
    $sSqlArrecadacao .= "           codigoagencia, ";
    $sSqlArrecadacao .= "           codigocontacorrente";
    
    return $sSqlArrecadacao;
  
  }

}