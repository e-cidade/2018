<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
require("libs/db_sql.php");
require("libs/db_utils.php");

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));

$sTiposDebitos            = $tipos;
$aTipoDebitosSelecionados = split(",",$tipos);
$aTipoDebitos             = split(",",$tipostodos);

$sInfo  = "";
$sInfo1 = "";
$sAnd = " and ";
$sWhere = $sAnd." arrecad.k00_tipo in ($tipos)"; 

if (($dtini != "--") && ($dtfim != "--")) {
  
	$sWhere .= " and k00_dtoper  between '$dtini' and '$dtfim'  ";
	$sInfo = "De ".db_formatar($dtini, "d")." até ".db_formatar($dtfim, "d");
	
} else if ($dtini != "--") {
  
	$sWhere .= " and k00_dtoper >= '$dtini'  ";
	$sInfo = "Apartir de ".db_formatar($dtini, "d");
		
} else if ($dtfim != "--") {
  
	$sWhere .= $sAnd." k00_dtoper <= '$dtfim'   ";
	$sInfo = "Até ".db_formatar($dtfim, "d");
		
}

if (($exercini != "") && ($exercfim != "")) {
  
	$sWhere .= " and fc_arrecexerc(y.k00_numpre,y.k00_numpar)  between '$exercini' and '$exercfim'  ";	
	$sInfo1 = "Do exercício $exercini até $exercfim.";
		
} else if ($exercini != "") {
  
	$sWhere .= " and fc_arrecexerc(y.k00_numpre,y.k00_numpar) >= '$exercini'  ";	
	$sInfo1 = "Apartir do exercício $exercini.";
	
} else if ($exercfim != "") {
  
	$sWhere .= " and fc_arrecexerc(y.k00_numpre,y.k00_numpar) <= '$exercfim'   ";	
	$sInfo1 = "Até o exercício $exercfim.";	
	
}

if ($parReceit != ''){
	$sWhere .= $sAnd." y.k00_receit in($parReceit)";
}

/*
 * Buscamos os dados de acordo com o acesso a CGF
 * Numcgm
 * Matricula
 * Inscrição
 * Parcelamento ou Numpre 
 * 
 */
$oDaoCgm                  = db_utils::getDao("cgm");
$oDaoDivida               = db_utils::getDao("divida");
$oDaoTermo                = db_utils::getDao("termo");
$oDaoArreTipo             = db_utils::getDao("arretipo");
$oDaoProcessoForoPartilha = db_utils::getDao("processoforopartilhacusta");

if (!empty($numcgm)) {
   
  $rsOutrosDebitos = debitos_numcgm($numcgm,0,0,$DB_DATACALC,$DB_anousu,'','k00_origem,k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $rsDebitos       = debitos_numcgm($numcgm,0,0,$DB_DATACALC,$DB_anousu,'','k00_origem,k00_tipo,k00_numpre,k00_numpar,k00_receit', $sWhere);  
  
  $sOrigemConsulta = "Numcgm"; 
  $iNumcgm       = $numcgm;
  
} else if (!empty($matric)) {
  
	$rsOutrosDebitos = debitos_matricula($matric,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $rsDebitos       = debitos_matricula($matric,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $sWhere);
  
  $sOrigemConsulta = "Matrícula: ".$matric;
 
  $oDaoIptuBase        = db_utils::getDao("iptubase");
  $sCamposProprietario = "j34_setor,j34_quadra,j34_lote,tipopri,j39_numero,j39_compl,nomepri,z01_nome,z01_numcgm,z01_cgmpri,pql_localizacao";
  $rsDadosProprietario = $oDaoIptuBase->sql_record($oDaoIptuBase->proprietario_query($matric,$sCamposProprietario));
  
  //echo $oDaoIptuBase->proprietario_query($matric,$sCamposProprietario) . "<br>"; 
  
  if ($oDaoIptuBase->numrows > 0) {
    $oDadosProprietario = db_utils::fieldsMemory($rsDadosProprietario,0);
    
    $sOrigemConsulta .= " - SQL: {$oDadosProprietario->j34_setor}/{$oDadosProprietario->j34_quadra}/{$oDadosProprietario->j34_lote} PQL: {$oDadosProprietario->pql_localizacao}";
    $sOrigemConsulta .= " - Logradouro: {$oDadosProprietario->tipopri} {$oDadosProprietario->nomepri}, {$oDadosProprietario->j39_numero} {$oDadosProprietario->j39_compl}";
    
   //$iNumcgm       = $oDadosProprietario->z01_numcgm;
     $iNumcgm       = $oDadosProprietario->z01_cgmpri;
    
   // echo "[ 1 ] " . $iNumcgm ."<br>";
    //die();
  }
  
} else if (!empty($inscr)) {
  
	$rsOutrosDebitos = debitos_inscricao($inscr,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $rsDebitos       = debitos_inscricao($inscr,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $sWhere);
  
  $sOrigemConsulta = "Inscrição: ".$inscr;
  
  $oDaoIssBase         = db_utils::getDao("issbase"); 
  $rsDadosProprietario = $oDaoIssBase->sql_record($oDaoIssBase->empresa_query($inscr));
  if ($oDaoIssBase->numrows > 0) {
    $oDadosProprietario = db_utils::fieldsMemory($rsDadosProprietario,0);
    $iNumcgm = $oDadosProprietario->q02_numcgm;
      //echo "[ 2 ] " . $iNumcgm ."<br>";
  }
  
} else if (!empty($numpre)) {
  
	$rsOutrosDebitos = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');  
	$rsDebitos       = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $sWhere);
	
	$sOrigemConsulta = "Código Arrecadação: ".$numpre;
	$iNumcgm         = db_utils::fieldsMemory($rsDebitos,0)->k00_numcgm;
	  //echo "[ 3 ] " . $iNumcgm ."<br>";
  
}
$oDadosDebitos = db_utils::getColectionByRecord($rsDebitos);

$rsDadosCgm    = $oDaoCgm->sql_record($oDaoCgm->sql_query($iNumcgm));

//echo $oDaoCgm->sql_query($iNumcgm) . "<br>"; 

$oDadosCgm     = db_utils::getColectionByRecord($rsDadosCgm);
$oDadosCgm[0]->complemento = $sOrigemConsulta;

//echo "<pre>";
//print_r($oDadosCgm);

//die();

$aCadTipoDivida       = array(5,12,18);
$aCadTipoInicialForo  = array(12,13,18);
$aCadTipoParcelamento = array(6,13,18);

$aDadosRelatorio                = array();
$aDadosNumpre                   = array();
$aDadosOrigem                   = array();
$aDadosTipo                     = array();
$aExercDivida                   = array();                

$oDadosTotal                    = new stdClass();
$oDadosTotal->total_historico = 0;
$oDadosTotal->total_corrigido = 0;
$oDadosTotal->total_juros     = 0;
$oDadosTotal->total_multa     = 0;
$oDadosTotal->total_desconto  = 0;
$oDadosTotal->total_geral     = 0;
$oDadosTotal->custas_geral    = 0;    
$lOutrosDebitos       = false;
$lOutrosTipos         = false;
if ( pg_num_rows($rsOutrosDebitos) > pg_numrows($rsDebitos)) {
  $lOutrosDebitos = true;
}

if (count($aTipoDebitos) != count($aTipoDebitosSelecionados)) {
  $lOutrosTipos = true;
}

foreach ($oDadosDebitos as $aDados) {
  
  $aDadosProcessado = new stdClass();
  $aDadosProcessado->numpre                    = $aDados->k00_numpre;
  $aDadosProcessado->numpar                    = $aDados->k00_numpar;
  $aDadosProcessado->tipo                      = $aDados->k00_tipo;
  $aDadosProcessado->receita                   = $aDados->k00_receit;
  $aDadosProcessado->receita_descricao         = $aDados->k02_descr; 
  $aDadosProcessado->histcalc_descricao        = $aDados->k01_descr;
  $aDadosProcessado->numcgm                    = $aDados->k00_numcgm;  
  $aDadosProcessado->numtot                    = $aDados->k00_numtot;  
  $aDadosProcessado->numdig                    = $aDados->k00_numdig;  
  $aDadosProcessado->dtvenc                    = $aDados->k00_dtvenc;  
  $aDadosProcessado->dtoper                    = $aDados->k00_dtoper;  
  $aDadosProcessado->total_historico           = $aDados->vlrhis;
  $aDadosProcessado->total_corrigido           = $aDados->vlrcor;
  $aDadosProcessado->total_juros               = $aDados->vlrjuros;
  $aDadosProcessado->total_multa               = $aDados->vlrmulta;
  $aDadosProcessado->total_desconto            = $aDados->vlrdesconto;
  $aDadosProcessado->total_geral               = $aDados->total;  
  
  $aDadosProcessado->complemento_origem       = "";
  $aDadosProcessado->complemento_tipo         = "";
  $aDadosProcessado->complemento_processoforo = "";
  $aDadosProcessado->complemento_numpre       = "";
  
  /* Verificar de acordo com o tipo de débito as informações complementares
   * Se Dívida : Exercicios
   * Se Parcelamento, Reparcelamento ou Parcelamento do Foro: Exercicios
   * Se Inicial do Foro ou Parcelamento do Foro: Inicial e Processo do Foro, se Utiliza Partilha, o total das custas do processo
   */
  
  /*
   * Caso a origem de acesso a CGF não seja por CGM, verificamos a que origem o débitos está ligado: Inscrição ou Matricula
   */
  if (empty($numcgm)) {

    $sSqlOrigem  = " select distinct                                                                    "; 
    $sSqlOrigem .= "        case                                                                        ";
    $sSqlOrigem .= "          when b.k00_matric is null then                                            "; 
    $sSqlOrigem .= "            'I-'||c.k00_inscr                                                       ";
    $sSqlOrigem .= "        else                                                                        ";
    $sSqlOrigem .= "            'M-'||b.k00_matric                                                      "; 
    $sSqlOrigem .= "        end as origem                                                               ";
    $sSqlOrigem .= "   from arrecad a                                                                   ";
		$sSqlOrigem .= "        inner join arreinstit on arreinstit.k00_numpre =  a.k00_numpre              "; 
    $sSqlOrigem .= "  			          					 and arreinstit.k00_instit = ".db_getsession('DB_instit'); 
    $sSqlOrigem .= "         left join arrematric b on a.k00_numpre = b.k00_numpre                      ";
    $sSqlOrigem .= "         left join arreinscr  c on a.k00_numpre = c.k00_numpre                      ";
    $sSqlOrigem .= "  where a.k00_numpre = {$aDados->k00_numpre} limit 1                                ";
    $rsOirgem = db_query($sSqlOrigem);
    $sOrigem  = db_utils::fieldsMemory($rsOirgem,0)->origem;
  } else {
    $sOrigem = $aDados->k00_origem; 
  }
  $aDadosProcessado->origem                    = $sOrigem;
  
  $sSqlArreTipo    = $oDaoArreTipo->sql_query_file($aDados->k00_tipo, "k00_descr, k03_tipo");
  $rsDadosArreTipo = $oDaoArreTipo->sql_record($sSqlArreTipo);
  $oDadosArreTipo  = db_utils::fieldsMemory($rsDadosArreTipo, 0);
  $aDadosProcessado->tipo_descricao = $oDadosArreTipo->k00_descr; 
  
  $aDadosProcessado->divida_exercicios = "";  
  
  if (in_array($oDadosArreTipo->k03_tipo, $aCadTipoDivida)) {
    $sSqlDivida    = $oDaoDivida->sql_query_file(null,"array_accum(distinct (v01_exerc)) as exercicios", "", "v01_numpre = $aDados->k00_numpre");
    $rsDadosDivida = $oDaoDivida->sql_record($sSqlDivida);
    if ($oDaoDivida->numrows > 0 ) {
      $sExercicios = db_utils::fieldsMemory($rsDadosDivida,0)->exercicios;
      
      $aDadosProcessado->divida_exercicios   = ereg_replace("[{}]", "",$sExercicios);
      
      $aDadosProcessado->complemento_numpre  = " - Exercício: ".$aDadosProcessado->divida_exercicios;
        
    }  
  }
   
  
  $aDadosProcessado->termo = "";
  if (in_array($oDadosArreTipo->k03_tipo, $aCadTipoParcelamento)) {

    $sSqlTermo  = "select * from (                                                                      ";
    $sSqlTermo .= "select v07_parcel,                                                                   ";
    $sSqlTermo .= "       ( select array_to_string(array_accum(distinct v01_exerc),',') as exercicios   ";
    $sSqlTermo .= "           from fc_parc_origem_completo({$aDados->k00_numpre}) as origemparcelamento ";
    $sSqlTermo .= "                inner join termodiv    on termodiv.parcel       = riparcel           ";             
    $sSqlTermo .= "                inner join divida      on divida.v01_coddiv     = termodiv.coddiv    ";            
    $sSqlTermo .= "                                       and v01_instit           = " . db_getsession("DB_instit");
    $sSqlTermo .= "       ) as exercicios                                                               ";                                                     
    $sSqlTermo .= "   from termo                                                                        ";
    $sSqlTermo .= " where v07_numpre =  {$aDados->k00_numpre}                                           ";
    $sSqlTermo .= " union                                                                               ";
    $sSqlTermo .= " select v07_parcel,                                                                                        ";                                         
    $sSqlTermo .= "        ( select array_to_string(array_accum(distinct case                                                 ";
    $sSqlTermo .= "                                                        when divcertdiv.v01_exerc is null                  ";
    $sSqlTermo .= "                                                          then divcertter.v01_exerc                        ";
    $sSqlTermo .= "                                                          else divcertdiv.v01_exerc                        ";
    $sSqlTermo .= "                                                      end ),',') as exercicios                             ";
    $sSqlTermo .= "            from fc_parc_origem_completo({$aDados->k00_numpre}) as origemparcelamento                      ";
    $sSqlTermo .= "           inner join termoini                  on termoini.parcel       = riparcel                        ";
    $sSqlTermo .= "           inner join inicialcert               on inicial               = v51_inicial                     ";
    $sSqlTermo .= "            left join certdiv                   on certdiv.v14_certid    = inicialcert.v51_certidao        ";
    $sSqlTermo .= "            left join divida as divcertdiv      on divcertdiv.v01_coddiv = certdiv.v14_coddiv              ";
    $sSqlTermo .= "                                               and divcertdiv.v01_instit = " . db_getsession("DB_instit");
    $sSqlTermo .= "            left join certter                   on certter.v14_certid    = inicialcert.v51_certidao        ";
    $sSqlTermo .= "            left join termodiv                  on termodiv.parcel       = certter.v14_parcel              ";
    $sSqlTermo .= "            left join divida as divcertter      on divcertter.v01_coddiv = termodiv.coddiv) as exercicios  ";
    $sSqlTermo .= "   from termo where v07_numpre = {$aDados->k00_numpre}                                                     ";    
    $sSqlTermo .= " ) as x where exercicios != '' ";
    $rsDadosTermo    = $oDaoTermo->sql_record($sSqlTermo);
    
    if ($oDaoTermo->numrows > 0) {
      $aTermo   = db_utils::fieldsMemory($rsDadosTermo,0);

      $oDadosTermo = new stdClass();
      $oDadosTermo->parcel       = $aTermo->v07_parcel;
      $oDadosTermo->parcel_exerc = ereg_replace("[{}]","",$aTermo->exercicios);
       
      $aDadosProcessado->termo               = $oDadosTermo;
      $aDadosProcessado->complemento_numpre  = " - Parcelamento : {$aTermo->v07_parcel} - Exercício(s): {$oDadosTermo->parcel_exerc}";
      
    }
    
  }
  
  
  $aDadosProcessado->processo_foro = array();
  $aDadosProcessado->custas_situacao = "";
  $aDadosProcessado->custas_total    = "";  
  $iProcessoForo = 0;
  if (in_array($oDadosArreTipo->k03_tipo, $aCadTipoInicialForo)) {
    
    $sSqlProcessoForo  = "select distinct                                                                             ";
    $sSqlProcessoForo .= "       inicial        as inicial,                                                           ";
    $sSqlProcessoForo .= "       v70_sequencial as sequencial_processo_foro,                                          ";
    $sSqlProcessoForo .= "       v70_codforo    as processo_foro                                                      ";
    $sSqlProcessoForo .= "  from ( select inicial as inicial                                                          ";
    $sSqlProcessoForo .= "           from fc_parc_origem_completo({$aDados->k00_numpre})                              "; 
    $sSqlProcessoForo .= "          inner join termoini on termoini.parcel = riparcel                                 "; 
    $sSqlProcessoForo .= "          union                                                                             ";
    $sSqlProcessoForo .= "         select v59_inicial as inicial                                                      ";
    $sSqlProcessoForo .= "           from inicialnumpre                                                               ";
    $sSqlProcessoForo .= "          where inicialnumpre.v59_numpre = {$aDados->k00_numpre} ) as inicial               ";
    $sSqlProcessoForo .= " inner join processoforoinicial  on processoforoinicial.v71_inicial = inicial.inicial       ";
    $sSqlProcessoForo .= " inner join processoforo         on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo ";
    $rsProcessoForo = db_query($sSqlProcessoForo);
    if (pg_num_rows($rsProcessoForo) > 0) {
      $oDadosProcessoForo = db_utils::getColectionByRecord($rsProcessoForo);
      
      $aDadosProcessado->processo_foro = $oDadosProcessoForo;
      if ($aDadosProcessado->complemento_numpre == "") { 
        $aDadosProcessado->complemento_numpre  = " - Inicial: {$oDadosProcessoForo[0]->inicial}"; 
        $aDadosProcessado->complemento_numpre .= " - Processo do Foro : {$oDadosProcessoForo[0]->processo_foro}";
      }
      $iProcessoForo = $oDadosProcessoForo[0]->sequencial_processo_foro;

      /*
       * Verificamos a situação das custas para o Processo do Foro
       */
     $sSqlDadosPartilha = " select distinct
														       v76_sequencial,
														       v76_tipolancamento,
														       v76_dtpagamento,
														       ( select sum(v77_valor)
														           from processoforopartilhacusta
														          where processoforopartilhacusta.v77_processoforopartilha = processoforopartilha.v76_sequencial
														       ) as total
														  from processoforopartilha
														       inner join processoforopartilhacusta pfpc on pfpc.v77_processoforopartilha = processoforopartilha.v76_sequencial
														 where case when v76_tipolancamento in (2,3)
														            then true
														            else v77_processoforopartilha is not null
														        end
														   and v76_processoforo = {$iProcessoForo}
														 order by v76_sequencial desc;
														     \n";
      $rsDadosPartilha = db_query($sSqlDadosPartilha);
      if (pg_num_rows($rsDadosPartilha) > 0) {
        
          for ($iInd=0; $iInd < $rsDadosPartilha; $iInd++) {
             $aDadosPartilha = db_utils::fieldsmemory($rsDadosPartilha, $iInd);
             
             $oPartilha = new stdClass();
             
             //Custas emitidas e não pagas
             if ($aDadosPartilha->v76_tipolancamento == 1 && $aDadosPartilha->v76_dtpagamento == "") {
               
              $aDadosProcessado->custas_situacao = "Emitidas";
              $aDadosProcessado->custas_total    = $aDadosPartilha->total;  
             
             //Custas emitidas e pagas
             } else if ($aDadosPartilha->v76_tipolancamento == 1 && $aDadosPartilha->v76_dtpagamento != "") {
    
              $aDadosProcessado->custas_situacao = "Pagas";                
              $aDadosProcessado->custas_total    = '0';
             
             //Custas lancadas manualmente           
             } else if ($aDadosPartilha->v76_tipolancamento == 2) {
               
              $aDadosProcessado->custas_situacao = "Pagas";              
              $aDadosProcessado->custas_total    = '0';
             
             //Custas isentas           
             } else if ($aDadosPartilha->v76_tipolancamento == 3) {
    
              $aDadosProcessado->custas_situacao = "Isento";                       
              $aDadosProcessado->custas_total    = '0';
               
             } 
             
             break;
          }
			} else {				

				$oDaoProcessoForoPartilhaCusta = db_utils::getDao('processoforopartilhacusta');				
				$oDaoConvenio                  = db_utils::getDao('cadconvenio');				
				
				$sSqlTaxas                     = $oDaoConvenio->sql_queryTaxasConvenio();
				$rsSqlTaxas                    = db_query($sSqlTaxas);
				$aTaxas                        = db_utils::getCollectionByRecord($rsSqlTaxas);
				
				$dtVencimento                  = date("Y-m-d", db_getsession('DB_datausu'));
				$nTotalDebito                  = $oDaoProcessoForoPartilhaCusta->getCustasProcesso('',   
																																			                  	 $iProcessoForo,
																																													 $dtVencimento,
																																													 $oDadosArreTipo->k03_tipo);
				
				$nValorTotalCustas = 0;

				foreach ($aTaxas as $oTaxa) {

					if ($oTaxa->ar36_perc == 0) {
						$nValorCusta = $oTaxa->ar36_valor;
					} else {

						$nVlrPercentualDebito = round($nTotalDebito * round( ($oTaxa->ar36_perc / 100) , 2), 2);

						/**
						 * Verifica se valor do percentual do débito é maior que maximo ou minimo permitido
						 * caso ele ultrapasse um dos limites o valor da taxa será o limite
						 * caso contrario sera o resultado da operaçao
						 */
						if ($nVlrPercentualDebito > $oTaxa->ar36_valormax) {
							$nValorCusta = $oTaxa->ar36_valormax;
						} elseif ($nVlrPercentualDebito < $oTaxa->ar36_valormin) {
							$nValorCusta = $oTaxa->ar36_valormin;
						} else {
							$nValorCusta = $nVlrPercentualDebito;
						}

					}

					$nValorTotalCustas += $nValorCusta;
				}

				$aDadosProcessado->custas_situacao = "Não emitidas e não pagas";
				$aDadosProcessado->custas_total    = $nValorTotalCustas;
			} 
    }
  }

  /*
   * Verificamos se a data de vencimento do débito é menor que a data de calculo utilizada para emissão do relatório
   * 
   * Caso a condição seja positiva, ou seja, o débito esteja vencido no momento da emissão do relatório, 
   * setamos o caracter ascii char(253) para o campo sSinal do objeto aDados do contrário o valor deste objeto é nulo.
   *    
   */
  $dDtVenc = mktime(0,0,0,substr($aDados->k00_dtvenc,5,2),substr($aDados->k00_dtvenc,8,2),substr($aDados->k00_dtvenc,0,4));
  if ($dDtVenc < $DB_DATACALC) {
    $aDadosProcessado->sSinal = chr(253);
  } else {
    $aDadosProcessado->sSinal = "";
  }
    
  /*
   * Somamos os valores retornados das query's no objeto $oDadosTotal
   */
  $oDadosTotal->total_historico += $aDados->vlrhis;
  $oDadosTotal->total_corrigido += $aDados->vlrcor;
  $oDadosTotal->total_juros     += $aDados->vlrjuros;
  $oDadosTotal->total_multa     += $aDados->vlrmulta;
  $oDadosTotal->total_desconto  += $aDados->vlrdesconto;
  $oDadosTotal->total_geral     += $aDados->total;   
  
  $aDadosRelatorio[$sOrigem][$aDados->k00_tipo][$iProcessoForo][$aDados->k00_numpre][] = $aDadosProcessado;  
}    

$head2 = "SECRETARIA DA FAZENDA";
$head3 = "Relatório Total dos Débitos Analítico";
$head5 = $sInfo;
$head6 = $sInfo1;
$head7 = "Débitos Calculados até: ".db_formatar($db_datausu,'d');

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->AddPage();
$oPdf->SetFillColor(235);

fc_dadosContribuinte($oPdf, $oDadosCgm);
fc_cabecalhoDebitos($oPdf);


foreach ($aDadosRelatorio as $aDadosOrigem) {
   
   $oTotalOrigem   = new stdClass();
   $oTotalTipo     = new stdClass();
   $oTotalProcesso = new stdClass();
   $oTotalNumpre   = new stdClass();
   
   foreach ($aDadosOrigem as $aDadosTipo) {

   	foreach ($aDadosTipo as $aDadosProcessoForo) {
       
       foreach ($aDadosProcessoForo as $aDados) {
  
         foreach ($aDados as $aDadosNumpre) {
         	
             /*
              * Montamos os objetos utilizados para totalização dos valores de acordo com o grupode registros
              * - Origem
              *   - Tipo
              *     - Processo do Foro
              *       - Numpre
              *     - Numpre 
              */
             $oTotalNumpre->numpre             = $aDadosNumpre->numpre;
             $oTotalNumpre->numpre_complemento = $aDadosNumpre->complemento_numpre;
             $oTotalNumpre->total_historico   += $aDadosNumpre->total_historico;
             $oTotalNumpre->total_corrigido   += $aDadosNumpre->total_corrigido;
             $oTotalNumpre->total_juros       += $aDadosNumpre->total_juros;
             $oTotalNumpre->total_multa       += $aDadosNumpre->total_multa;
             $oTotalNumpre->total_desconto    += $aDadosNumpre->total_desconto;
             $oTotalNumpre->total_geral       += $aDadosNumpre->total_geral;
              
              
             $oTotalTipo->tipo                 = $aDadosNumpre->tipo;
             $oTotalTipo->tipo_descricao       = $aDadosNumpre->tipo_descricao;
             $oTotalTipo->tipo_complemento     = $aDadosNumpre->complemento_tipo;
             $oTotalTipo->total_historico     += $aDadosNumpre->total_historico;
             $oTotalTipo->total_corrigido     += $aDadosNumpre->total_corrigido;
             $oTotalTipo->total_juros         += $aDadosNumpre->total_juros;
             $oTotalTipo->total_multa         += $aDadosNumpre->total_multa;
             $oTotalTipo->total_desconto      += $aDadosNumpre->total_desconto;
             $oTotalTipo->total_geral         += $aDadosNumpre->total_geral;
             $oTotalTipo->cutas_total          = $aDadosNumpre->custas_total;
                          
             $oTotalOrigem->origem             = $aDadosNumpre->origem;
             $oTotalOrigem->total_historico   += $aDadosNumpre->total_historico;
             $oTotalOrigem->total_corrigido   += $aDadosNumpre->total_corrigido;
             $oTotalOrigem->total_juros       += $aDadosNumpre->total_juros;
             $oTotalOrigem->total_multa       += $aDadosNumpre->total_multa;
             $oTotalOrigem->total_desconto    += $aDadosNumpre->total_desconto;
             $oTotalOrigem->total_geral       += $aDadosNumpre->total_geral;
             $oTotalOrigem->cutas_total       += $aDadosNumpre->custas_total;
              
             $oTotal->total_historico         += $aDadosNumpre->total_historico;
             $oTotal->total_corrigido         += $aDadosNumpre->total_corrigido;
             $oTotal->total_juros             += $aDadosNumpre->total_juros;
             $oTotal->total_multa             += $aDadosNumpre->total_multa;
             $oTotal->total_desconto          += $aDadosNumpre->total_desconto;
             $oTotal->total_geral             += $aDadosNumpre->total_geral;
             $oTotal->cutas_total             += $aDadosNumpre->custas_total;
              
             
             if ( count($aDadosNumpre->processo_foro) > 0) {

               $oTotalProcesso->processo_foro      = $aDadosNumpre->processo_foro;
               $oTotalProcesso->custas_complemento = $aDadosNumpre->custas_situacao;
               $oTotalProcesso->total_historico   += $aDadosNumpre->total_historico;
               $oTotalProcesso->total_corrigido   += $aDadosNumpre->total_corrigido;
               $oTotalProcesso->total_juros       += $aDadosNumpre->total_juros;
               $oTotalProcesso->total_multa       += $aDadosNumpre->total_multa;
               $oTotalProcesso->total_desconto    += $aDadosNumpre->total_desconto;
               $oTotalProcesso->total_geral       += $aDadosNumpre->total_geral;							 
               $oTotalProcesso->custas_total       = $aDadosNumpre->custas_total;
             }             
             
             fc_quebraPagina($oPdf, $oDadosCgm);
             
             $oPdf->SetX(5);
             $oPdf->SetFont('zapfdingbats','',6);
             $oPdf->Cell( 2 , 4 , $aDadosNumpre->sSinal,"",0,"C",0);
             
             $oPdf->SetFont('Arial','',6);
             $oPdf->Cell( 4 , 4 , $aDadosNumpre->numpar                                  ,"LR"  ,0, "C", 0);
             $oPdf->Cell( 4 , 4 , $aDadosNumpre->numtot                                  ,"R"   ,0, "C", 0);
             $oPdf->Cell( 13, 4 , db_formatar($aDadosNumpre->dtoper, "d")                ,"R"   ,0, "C", 0);
             $oPdf->Cell( 13, 4 , db_formatar($aDadosNumpre->dtvenc, "d")                ,"R"   ,0, "C", 0);
             $oPdf->cell( 13, 4 , $aDadosNumpre->origem                                  ,"R"   ,0, "L", 0);
             $oPdf->Cell( 30, 4 , substr(trim($aDadosNumpre->histcalc_descricao) ,0,20)  ,"R"   ,0, "L", 0);
             $oPdf->Cell( 6 , 4 , $aDadosNumpre->receita                                 ,"R"   ,0, "C", 0);
             $oPdf->Cell( 23, 4 , substr(trim($aDadosNumpre->receita_descricao) ,0,15)   ,"R"   ,0, "L", 0);
             
             $oPdf->SetFont('arial','',6);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_historico,'f')      ,"R"   ,0, "R", 0);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_corrigido,'f')      ,"R"   ,0, "R", 0);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_juros,'f')          ,"R"   ,0, "R", 0);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_multa,'f')          ,"R"   ,0, "R", 0);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_desconto,'f')       ,"R"   ,0, "R", 0);
             $oPdf->Cell( 15 , 4 , db_formatar($aDadosNumpre->total_geral,'f')          ,"R"   ,0, "R", 0);
             $oPdf->Cell( 1  , 4 , ""                                                   , 0    ,1,  0 , 0);       
             
         }
         
         fc_totalNumpre($oPdf, $oTotalNumpre);
         $oTotalNumpre->total_historico  = 0;
         $oTotalNumpre->total_corrigido  = 0;
         $oTotalNumpre->total_juros      = 0;
         $oTotalNumpre->total_multa      = 0;
         $oTotalNumpre->total_desconto   = 0;
         $oTotalNumpre->total_geral      = 0;
          
         
       }
       
       if ( count($aDadosNumpre->processo_foro) > 0) {
       	
       	
         $oDadosTotal->custas_geral    += $oTotalProcesso->custas_total;
         
         fc_totalProcessoForo($oPdf, $oTotalProcesso);
         $oTotalProcesso->total_historico  = 0;
         $oTotalProcesso->total_corrigido  = 0;
         $oTotalProcesso->total_juros      = 0;
         $oTotalProcesso->total_multa      = 0;
         $oTotalProcesso->total_desconto   = 0;
         $oTotalProcesso->total_geral      = 0; 
         $oTotalProcesso->custas_total     = 0;
         $oPdf->Ln();
         
       }
       
     }
     
     fc_totalTipo($oPdf, $oTotalTipo);
     $oTotalTipo->total_historico   = 0;
     $oTotalTipo->total_corrigido   = 0;
     $oTotalTipo->total_juros       = 0;
     $oTotalTipo->total_multa       = 0;
     $oTotalTipo->total_desconto    = 0;
     $oTotalTipo->total_geral       = 0;
     $oTotalTipo->cutas_total       = 0;
     
     $oPdf->Ln();
   }
   
   fc_totalOrigem($oPdf, $oTotalOrigem);
   $oTotalOrigem->total_historico   = 0;
   $oTotalOrigem->total_corrigido   = 0;
   $oTotalOrigem->total_juros       = 0;
   $oTotalOrigem->total_multa       = 0;
   $oTotalOrigem->total_desconto    = 0;
   $oTotalOrigem->total_geral       = 0;
   $oTotalOrigem->cutas_total       = 0;
    
   $oPdf->Ln();
}

fc_totalCusta($oPdf, $oDadosTotal);
$oPdf->Ln(5);

fc_totalGeral($oPdf, $oDadosTotal);

fc_msgOutrosDebitos($oPdf, $lOutrosTipos, $lOutrosDebitos);
$oPdf->Ln(2);
   
/**********************************************************************************************************************
 * 
 * Buscamos as informações referentes a suspensões de débitos de acordo com a origem da Consulta
 * 
 *********************************************************************************************************************/
if (isset ($matric)) {
	
	$sSqlInner  = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
	$sSqlInner .= " left  join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
	$sSqlWhere  = " arrematric.k00_matric = $matric ";
	
} else if (isset ($inscr)) {
	
	$sSqlInner  = " inner join arreinscr  on arreinscr.k00_numpre = arresusp.k00_numpre ";
	$sSqlInner .= " left  join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
	$sSqlWhere  = " arreinscr.k00_inscr  = $inscr ";
	   	
} else if (isset ($numcgm)) {
	
	$sSqlInner  = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
	$sSqlInner .= " left  join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
	$sSqlInner .= " left  join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
	$sSqlWhere  = " arrenumcgm.k00_numcgm = $numcgm ";
	   	
} else if (isset ($numpre)) {
	
	$sSqlInner  = " left join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
	$sSqlInner .= " left join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
	$sSqlWhere  = " arresusp.k00_numpre   = $numpre ";
	   	
}   

$sSqlWhere .= " and suspensao.ar18_situacao = 1 ";
$sSqlWhere .= " and arresusp.k00_tipo in ({$sTiposDebitos}) ";
$sSqlWhere .= " and arretipo.k00_instit = ".db_getsession('DB_instit'); 
if (trim($parReceit) != "") {                                                                     
  $sSqlWhere .= " and arresusp.k00_receit in ({$parReceit}) ";   	
}

$sSqlSuspensao  = " select arresusp.*,		  								 	                                    ";
$sSqlSuspensao .= " 		  arretipo.k00_descr,	  							 	                                  ";
$sSqlSuspensao .= " 		  tabrec.k02_descr,		  							 	                                  ";
$sSqlSuspensao .= " 		  case	  											 	                                        ";
$sSqlSuspensao .= " 		    when k00_matric is not null then 'M-'||k00_matric  	                  ";
$sSqlSuspensao .= " 		    else 'I-'||k00_inscr  							 	                                ";
$sSqlSuspensao .= " 		  end as matinscr   					 	 			                                    ";
$sSqlSuspensao .= " 	 from arresusp  		  								 	                                    ";
$sSqlSuspensao .= " 	 inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao ";   
$sSqlSuspensao .= " 	 inner join arretipo on arretipo.k00_tipo = arresusp.k00_tipo               ";
$sSqlSuspensao .= " 	 inner join tabrec   on tabrec.k02_codigo = arresusp.k00_receit             ";   
$sSqlSuspensao .= " 	 {$sSqlInner}		 								 	                                          ";  
$sSqlSuspensao .= " 	 where {$sSqlWhere}          							 	 	                              ";
$sSqlSuspensao .= " 	 order by arresusp.k00_tipo,									                              ";
$sSqlSuspensao .= " 			      arresusp.k00_numpre,									                            ";
$sSqlSuspensao .= " 			      arresusp.k00_numpar,									                            ";
$sSqlSuspensao .= " 			      arresusp.k00_receit 									                            ";
$rsSuspensao      = pg_query($sSqlSuspensao);
$iLinhasSuspensao = pg_num_rows($rsSuspensao);
$aSuspensao		 = array();

if ( $iLinhasSuspensao > 0 ) {

  $nTotNumprehis  = 0;
  $nTotNumprecor  = 0;
  $nTotNumprejur  = 0;
  $nTotNumpremul  = 0;
  $nTotNumpredes  = 0;
  $nTotNumpretot  = 0;
     	
  $nTotTipohis  	= 0;
  $nTotTipocor    = 0;
  $nTotTipojur    = 0;
  $nTotTipomul    = 0;
  $nTotTipodes    = 0;
  $nTotTipotot    = 0;
  	 
  $nTotSusphis  	= 0;
  $nTotSuspcor    = 0;
  $nTotSuspjur    = 0;
  $nTotSuspmul    = 0;
  $nTotSuspdes    = 0;
  $nTotSusptot    = 0;	 
  
  $oPdf->SetFont('Arial', 'BI', 12);
  $oPdf->Cell(0,5,'Débitos Suspensos',0,1,"C",0);
  $oPdf->Ln();
  $oPdf->SetFont('arial','B',6);
  $oPdf->setx(5);
  
  fc_cabecalhodebitos($oPdf);     
     
  $iNumpre = null;
  $iTipo   = null;
  
  for ($i=0; $i < $iLinhasSuspensao; $i++) {
  	
    $oSuspensao = db_utils::fieldsMemory($rsSuspensao,$i);
  
    $oPdf->setx(5);
    $oPdf->SetFont('arial','',6);
    $nTotal = ( $oSuspensao->k00_vlrcor + $oSuspensao->k00_vlrjur + $oSuspensao->k00_vlrmul ) - $oSuspensao->k00_vlrdes;
    
    $oPdf->Cell(2 ,4," "								 	          	,   0,0,"C",0);
    $oPdf->Cell(4 ,4,$oSuspensao->k00_numpar 					,"LR",0,"C",0);
    $oPdf->Cell(4 ,4,$oSuspensao->k00_numtot 					, "R",0,"C",0);
    $oPdf->Cell(13,4,$oSuspensao->k00_dtoper 					, "R",0,"C",0);
    $oPdf->Cell(13,4,$oSuspensao->k00_dtvenc 					, "R",0,"C",0);
    $oPdf->cell(13,4,$oSuspensao->matinscr   					, "R",0,"L",0);
    $oPdf->Cell(30,4,substr(trim($oSuspensao->k00_descr),0,20), "R",0,"L",0);
    $oPdf->Cell(6 ,4,$oSuspensao->k00_receit					, "R",0,"C",0);
    $oPdf->Cell(23,4,substr(trim($oSuspensao->k02_descr),0,15), "R",0,"L",0);
    $oPdf->Cell(15,4,db_formatar($oSuspensao->k00_valor,'f')	, "R",0,"R",0);
    $oPdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrcor,'f')	, "R",0,"R",0);
    $oPdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrjur,'f')	, "R",0,"R",0);
    $oPdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrmul,'f')	, "R",0,"R",0);
    $oPdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrdes,'f') , "R",0,"R",0);
    $oPdf->Cell(15,4,db_formatar($nTotal,'f')					, "R",0,"R",0);
    $oPdf->Cell(1,4,"",0,1,0,0);
    
    $nTotNumprehis  += $oSuspensao->k00_valor;
    $nTotNumprecor  += $oSuspensao->k00_vlrcor;
    $nTotNumprejur  += $oSuspensao->k00_vlrjur;
    $nTotNumpremul  += $oSuspensao->k00_vlrmul;
    $nTotNumpredes  += $oSuspensao->k00_vlrdes;
    $nTotNumpretot  += $nTotal;
    
    $nTotTipohis    += $oSuspensao->k00_valor;
    $nTotTipocor    += $oSuspensao->k00_vlrcor;
    $nTotTipojur    += $oSuspensao->k00_vlrjur;
    $nTotTipomul    += $oSuspensao->k00_vlrmul;
    $nTotTipodes    += $oSuspensao->k00_vlrdes;
    $nTotTipotot    += $nTotal;
    
    $nTotSusphis    += $oSuspensao->k00_valor;
    $nTotSuspcor    += $oSuspensao->k00_vlrcor;
    $nTotSuspjur    += $oSuspensao->k00_vlrjur;
    $nTotSuspmul    += $oSuspensao->k00_vlrmul;
    $nTotSuspdes    += $oSuspensao->k00_vlrdes;
    $nTotSusptot    += $nTotal;	   

    if ( $oSuspensao->k00_numpre != $iNumpre ) {

    	if ( $i == 0 ) {
    		$iNumpre = $oSuspensao->k00_numpre;
    		$iTipo   = $oSuspensao->k00_tipo;
    		continue;
    	}

    	$oPdf->setx(7);
    	$oPdf->SetFont('arial','B',6);
    	$oPdf->Cell(106,5,"TOTAL DO NUMPRE ".$oSuspensao->k00_numpre,"T",0,"L",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumprehis,'f'),1,0,"R",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumprecor,'f'),1,0,"R",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumprejur,'f'),1,0,"R",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumpremul,'f'),1,0,"R",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumpredes,'f'),1,0,"R",1);
    	$oPdf->Cell(15 ,5,db_formatar($nTotNumpretot,'f'),1,1,"R",1);
    	$oPdf->SetFont('arial','',6);

    	$nTotNumprehis  = 0;
    	$nTotNumprecor  = 0;
    	$nTotNumprejur  = 0;
    	$nTotNumpremul  = 0;
    	$nTotNumpredes  = 0;
    	$nTotNumpretot  = 0;

    	$iNumpre 		 = $oSuspensao->k00_numpre;

    }
  
    if ( $oSuspensao->k00_tipo != $iTipo ) {
  	 
    	 $oPdf->setx(7);
       $oPdf->SetFont('arial','B',6);
       $oPdf->Cell(106,5,"TOTAL DO TIPO ".$oSuspensao->k00_tipo,"T",0,"L",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipohis,'f'),1,0,"R",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipocor,'f'),1,0,"R",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipojur,'f'),1,0,"R",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipomul,'f'),1,0,"R",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipodes,'f'),1,0,"R",1);
       $oPdf->Cell(15 ,5,db_formatar($nTotTipotot,'f'),1,1,"R",1);
       $oPdf->SetFont('arial','',6);
      
       $nTotTipohis  = 0;
    	 $nTotTipocor  = 0;
    	 $nTotTipojur  = 0;
    	 $nTotTipomul  = 0;
    	 $nTotTipodes  = 0;
       $nTotTipotot  = 0;	     
  
       $iTipo   	   = $oSuspensao->k00_tipo;
    }
  
  }
  
  $oPdf->SetFont('arial','B',6);
  
  $oPdf->setx(7);
  $oPdf->Cell(106,5,"TOTAL DO NUMPRE ".$oSuspensao->k00_numpre,"T",0,"L",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumprehis,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumprecor,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumprejur,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumpremul,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumpredes,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotNumpretot,'f'),1,1,"R",1);
  
  $oPdf->setx(7);
  $oPdf->Cell(106,5,"TOTAL DO TIPO ".$oSuspensao->k00_tipo,"T",0,"L",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipohis,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipocor,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipojur,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipomul,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipodes,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotTipotot,'f'),1,1,"R",1);
  
  $oPdf->Ln(3);
   
  $oPdf->setx(7);
  $oPdf->Cell(106,5,"TOTAL GERAL :"			 ,"T",0,"L",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSusphis,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSuspcor,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSuspjur,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSuspmul,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSuspdes,'f'),1,0,"R",1);
  $oPdf->Cell(15 ,5,db_formatar($nTotSusptot,'f'),1,1,"R",1);
  
}

$oPdf->Output();

/*
 * 
 * Funções para agilizar e organizar a geração do relatório 
 * 
 */

function fc_dadosContribuinte($oPdf, $oDadosCgm) {
  
  $oPdf->SetX(5);
  
  $oPdf->SetFont('Arial','B',8);
  $oPdf->Cell( 0  , 21 ,'',"TB",0,'C');
  $oPdf->Text( 5  , 38 ,"Numcgm:");
  $oPdf->Text( 45 , 38 ,$oDadosCgm[0]->complemento);
  $oPdf->Text( 5  , 42 ,"Nome:");
  $oPdf->Text( 5  , 46 ,"CNPJ/CPF:");
  $oPdf->Text( 50 , 46 ,"Identidade:");
  $oPdf->Text( 5  , 50 ,"Endereço:");
  $oPdf->Text( 115, 50 ,"Número:");
  $oPdf->Text( 160, 50 ,"Complemento:");
  $oPdf->Text( 5  , 54 ,"Município:");
  $oPdf->Text( 60 , 54 ,"UF:");
  
  $oPdf->SetFont('Arial','I',8);
  $oPdf->Text( 23 , 38 ,$oDadosCgm[0]->z01_numcgm);
  $oPdf->Text( 23 , 42 ,$oDadosCgm[0]->z01_nome);
  $oPdf->Text( 23 , 46 ,db_cgccpf($oDadosCgm[0]->z01_cgccpf));
  $oPdf->Text( 68 , 46 ,$oDadosCgm[0]->z01_ident);
  $oPdf->Text( 23 , 50 ,$oDadosCgm[0]->z01_ender);
  $oPdf->Text( 135, 50 ,$oDadosCgm[0]->z01_numero);
  $oPdf->Text( 185, 50 ,$oDadosCgm[0]->z01_compl);
  $oPdf->Text( 23 , 54 ,$oDadosCgm[0]->z01_munic);
  $oPdf->Text( 68 , 54 ,$oDadosCgm[0]->z01_uf);
  $oPdf->SetFont('Arial','',6);
  
  $oPdf->SetXY(5,60);
  
}

function fc_cabecalhoDebitos($oPdf) {
  
  $oPdf->SetFont('Arial','B',6);
  $oPdf->Cell( 2  , 5 ," "        ,0,0,"C",0);
  $oPdf->Cell( 4  , 5 ,"P"        ,1,0,"C",0);
  $oPdf->Cell( 4  , 5 ,"T"        ,1,0,"C",0);
  $oPdf->Cell( 13 , 5 ,"OPER."    ,1,0,"C",0);
  $oPdf->Cell( 13 , 5 ,"VENC."    ,1,0,"C",0);
  $oPdf->Cell( 13 , 5 ,"ORIGEM"   ,1,0,"C",0);
  $oPdf->Cell( 30 , 5 ,"DESCRIÇÃO",1,0,"C",0);
  $oPdf->Cell( 6  , 5 ,"REC"      ,1,0,"C",0);
  $oPdf->Cell( 23 , 5 ,"DESCRIÇÃO",1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"VALOR"    ,1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"CORRIGIDO",1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"JUROS"    ,1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"MULTA"    ,1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"DESCONTO" ,1,0,"C",0);
  $oPdf->Cell( 15 , 5 ,"TOTAL"    ,1,1,"C",0);
  $oPdf->SetFont('Arial','',6);
  $oPdf->SetX(5);
  
}

function fc_quebraPagina($oPdf, $oDadosCgm){
  
   if ( $oPdf->GetY() > ( $oPdf->h - 30 ) ) {
     fc_dadosContribuinte($oPdf, $oDadosCgm);
     fc_cabecalhoDebitos($oPdf);  
   }
   
}

function fc_totalTipo($oPdf, $oDadosTipo) {

  $oPdf->setx(5);
  $oPdf->SetFont('arial','B',6);
  
  $oPdf->Cell( 108, 5 , "TOTAL DO TIPO : {$oDadosTipo->tipo} - {$oDadosTipo->tipo_descricao} {$oDadosTipo->tipo_complemento}","T",0,"L",1);
  
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_historico,'f')    ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_corrigido,'f')    ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_juros,'f')        ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_multa,'f')        ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_desconto,'f')     ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTipo->total_geral + $oDadosTipo->cutas_total ,'f')        ,1   , 1 , "R", 1);
  
  $aCadTipoInicialForo = array(12,13,18);
  
  if (in_array($oDadosTipo->tipo, $aCadTipoInicialForo)) {
  	
  	$custas_total = '0';
  	if( isset($oDadosTipo->custas_total) ) {
  		$custas_total = db_formatar($oDadosTipo->custas_total,'f');
  	}
  	
	  $oPdf->setx(5);
	  $oPdf->Cell(183, 5, "TOTAL DAS CUSTAS: Não isentas e não pagas: ", "T", 0, "L", 1);
		$oPdf->Cell(15 , 5 , $custas_total, 1, 1, "R", 1);  	
  }
  
  $oPdf->SetFont('arial','',6);
}


function fc_totalNumpre($oPdf, $oDadosNumpre) {

  $oPdf->setx(5);
  $oPdf->SetFont('Arial','B',6);
  
  $oPdf->Cell(108,5,"TOTAL DO NUMPRE {$oDadosNumpre->numpre} {$oDadosNumpre->numpre_complemento}","T",0,"L",1);
 
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_historico,'f')  ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_corrigido,'f')  ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_juros,'f')      ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_multa,'f')      ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_desconto,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosNumpre->total_geral,'f')      ,1   , 1 , "R", 1);
 
  $oPdf->SetFont('arial','',6);
    
}

function fc_totalProcessoForo($oPdf, $oDadosProcessoForo) {
  
  $oPdf->setx(5);
  $oPdf->SetFont('arial','B',6);
  $oPdf->Cell(108 , 5 , "INICIAL : {$oDadosProcessoForo->processo_foro[0]->inicial} - PROCESSO DO FORO: {$oDadosProcessoForo->processo_foro[0]->processo_foro}" ,"T",0,"L",1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_historico,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_corrigido,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_juros,'f')       ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_multa,'f')       ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_desconto,'f')    ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosProcessoForo->total_geral,'f')       ,1   , 1 , "R", 1);
  
  $oPdf->setx(5);
  if ($oDadosProcessoForo->custas_total != "") {
  	
    $oPdf->Cell(183, 5 , "TOTAL DAS CUSTAS : {$oDadosProcessoForo->custas_complemento}" ,"T",0,"L",1); 
    $oPdf->Cell(15 , 5 , db_formatar($oDadosProcessoForo->custas_total,'f'), 1, 1, "R", 1);
  } 
    
  $oPdf->SetFont('arial','',6);
  
}


function fc_totalOrigem($oPdf, $oDadosOrigem) {
  
  $oPdf->setx(5);
  $oPdf->SetFont('arial','B',6);
  
  $oPdf->Cell(108 , 5 , "TOTAL DA ORIGEM {$oDadosOrigem->origem}" ,"T",0,"L",1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_historico,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_corrigido,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_juros,'f')       ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_multa,'f')       ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_desconto,'f')    ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosOrigem->total_geral,'f')       ,1   , 1 , "R", 1);
  
  $oPdf->SetFont('arial','',6);  
}


function fc_totalCusta($oPdf, $oDadosTotal) {
	
	$oPdf->SetX(5);
	$oPdf->SetFont('arial','B',6);

	$oPdf->Cell(183 , 5 , "TOTAL DAS CUSTAS : ","T",0,"L",1);
	$oPdf->Cell(15, 5 , db_formatar($oDadosTotal->custas_geral,'f'), 1, 1, "R", 1);

	$oPdf->SetFont('arial','',6);
}

function fc_totalGeral($oPdf, $oDadosTotal) {
  
  $oPdf->SetX(5);
  $oPdf->SetFont('arial','B',6);
  
  $oPdf->Cell(108 , 5 , "TOTAL GERAL : ","T",0,"L",1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_historico,'f')  ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_corrigido,'f')  ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_juros,'f')      ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_multa,'f')      ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_desconto,'f')   ,1   , 0 , "R", 1);
  $oPdf->Cell( 15 , 5 , db_formatar($oDadosTotal->total_geral + $oDadosTotal->custas_geral,'f')      ,1   , 1 , "R", 1);
  
  $oPdf->SetFont('arial','',6);  
}


function fc_msgOutrosDebitos($oPdf, $lOutrosTipos=false, $lOutrosDebitos=false) {

  $oPdf->ln();
  
  if ($lOutrosTipos) {
    $oPdf->SetFont('arial','B',11);
    $oPdf->setx(7);
    $oPdf->Cell(195,5,"*** EXISTEM MAIS TIPOS DE DÉBITOS LANÇADOS QUE NÃO FORAM LISTADOS NESTE RELATÓRIO ***",0,1,"L",1);
    
  }
  
  if ($lOutrosDebitos) {
  	$oPdf->SetFont('arial', 'B', 11);
  	$oPdf->setx(7);
  	$oPdf->Cell(195, 5, "*** EXISTEM MAIS DÉBITOS LANÇADOS QUE NÃO FORAM LISTADOS NESTE RELATÓRIO ***", 0, 1, "L", 1);    
  }
  
}
?>