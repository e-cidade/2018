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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");

$oGet = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;

$iAnoUso        = db_getsession('DB_anousu');
$sDataUsu       = db_getsession("DB_datausu");
$sLogin         = db_getsession("DB_login");
$iInstit        = db_getsession("DB_instit");

$sSqlPrefeitura = "select * from db_config where codigo = {$iInstit} ";
$rsPrefeitura   = pg_exec($sSqlPrefeitura);
db_fieldsmemory($rsPrefeitura, 0);

$aWhere = array();

if (isset($oGet->e50_codord) && !empty($oGet->e50_codord)) {
  $aWhere[] = "e50_codord in({$oGet->e50_codord})";
}

if (isset($oGet->e81_codmov) && !empty($oGet->e81_codmov)) {
  $aWhere[] = "e81_codmov in({$oGet->e81_codmov})";
}

$sWhere = implode(" and ", $aWhere);

$pdf  = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'79'); // mod_imprime79.php
$pdf1->objpdf->SetTextColor(0,0,0);

//rotina que pega as ordens e os movimentos a serem impressas 
$sSqlPagOrdem = $clpagordem->sql_query_movimento('',' e50_codord, e81_codmov ',' e50_codord ', $sWhere);
$rsPagOrdem   = $clpagordem->sql_record($sSqlPagOrdem);

if ($clpagordem->numrows > 0) {
  db_fieldsmemory($rsPagOrdem, 0);
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não a Ordem de Pagamento. Verifique!');
}

$rsBuscaNVias = pg_query("select * from empparametro where e39_anousu = {$iAnoUso} ");

if (pg_numrows($rsBuscaNVias) > 0) {
  
  db_fieldsmemory($rsBuscaNVias, 0);
  $pdf1->nvias= $e30_nroviaord;
}     	 

for ($i = 0; $i < $clpagordem->numrows; $i++) {
  
   db_fieldsmemory($rsPagOrdem ,$i);
   $sSqlDadosOrdem  = "select *                                                                                            ";
   $sSqlDadosOrdem .= "       from                                                                                         ";
   $sSqlDadosOrdem .= "           (select *,fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural,                     ";
   $sSqlDadosOrdem .= "            			 case when e49_numcgm is null then e60_numcgm else e49_numcgm end as _numcgm       ";
   $sSqlDadosOrdem .= "                   from pagordem                                                                    ";
   $sSqlDadosOrdem .= "                   			inner join empord       on empord.e82_codord     = pagordem.e50_codord     ";
   $sSqlDadosOrdem .= "   				      inner join empagemov            on empagemov.e81_codmov  = empord.e82_codmov       ";
   $sSqlDadosOrdem .= "              	        inner join empempenho 		on empempenho.e60_numemp = pagordem.e50_numemp     ";
   $sSqlDadosOrdem .= "              		      inner join db_config 		  on db_config.codigo      = empempenho.e60_instit   ";
   $sSqlDadosOrdem .= "              					inner join orcdotacao 		on orcdotacao.o58_anousu = empempenho.e60_anousu   ";
   $sSqlDadosOrdem .= "              				              						 and orcdotacao.o58_coddot = empempenho.e60_coddot   ";
   $sSqlDadosOrdem .= "              	                                 and o58_instit            = {$iInstit}              ";
   $sSqlDadosOrdem .= "              	        inner join orcorgao   		on o58_orgao             = o40_orgao               ";
   $sSqlDadosOrdem .= "              	                                 and o40_anousu            = empempenho.e60_anousu   ";
   $sSqlDadosOrdem .= "              	        inner join orcunidade 		on o58_unidade           = o41_unidade             ";
   $sSqlDadosOrdem .= "              	                                 and o58_orgao             = o41_orgao               ";
   $sSqlDadosOrdem .= "              	                                 and o58_anousu            = o41_anousu              ";
   $sSqlDadosOrdem .= "              	        inner join orcfuncao  		on o58_funcao            = o52_funcao              ";
   $sSqlDadosOrdem .= "              	        inner join orcsubfuncao  	on o58_subfuncao         = o53_subfuncao           ";
   $sSqlDadosOrdem .= "              	        inner join orcprograma  	on o58_programa          = o54_programa            ";
   $sSqlDadosOrdem .= "              	                                 and o54_anousu            = o58_anousu              ";
   $sSqlDadosOrdem .= "              	        inner join orcprojativ  	on o58_projativ          = o55_projativ            ";
   $sSqlDadosOrdem .= "              	                                 and o55_anousu            = o58_anousu              ";
   $sSqlDadosOrdem .= "              	        inner join orcelemento   	on o58_codele            = o56_codele              ";
   $sSqlDadosOrdem .= "              		                               and o58_anousu            = o56_anousu              ";
   $sSqlDadosOrdem .= "              	        inner join orctiporec  		on o58_codigo            = o15_codigo              ";
   $sSqlDadosOrdem .= "              	        inner join emptipo 		    on emptipo.e41_codtipo   = empempenho.e60_codtipo  ";
   $sSqlDadosOrdem .= "              					left join  pagordemconta	on e50_codord            = e49_codord              ";
   $sSqlDadosOrdem .= "              					                                                                             ";
   $sSqlDadosOrdem .= "              					/* Conta Complano */                                                         ";
   $sSqlDadosOrdem .= "              					left join empagepag       on empagepag.e85_codmov   = empagemov.e81_codmov   ";
   $sSqlDadosOrdem .= "              					left join empagetipo      on empagetipo.e83_codtipo = empagepag.e85_codtipo  ";
   $sSqlDadosOrdem .= "              					left join saltes          on saltes.k13_conta       = empagetipo.e83_conta   ";
   $sSqlDadosOrdem .= "              					left join conplanoreduz   on conplanoreduz.c61_reduz = saltes.k13_reduz               ";
   $sSqlDadosOrdem .= "              																	 and conplanoreduz.c61_anousu = ". db_getsession("DB_anousu");  
   $sSqlDadosOrdem .= "              					left join conplano        on conplano.c60_codcon = conplanoreduz.c61_codcon           ";
   $sSqlDadosOrdem .= "               					                       and conplano.c60_anousu = conplanoreduz.c61_anousu           ";
   $sSqlDadosOrdem .= "               				left join conplanoconta   on conplanoconta.c63_codcon = conplano.c60_codcon           ";
   $sSqlDadosOrdem .= "                                       			   and conplanoconta.c63_anousu = conplano.c60_anousu           ";
   
   																							/* Pagamento por Cheque */                                                            
   $sSqlDadosOrdem .= "                       left join empageconfche   on empageconfche.e91_codmov  = empagemov.e81_codmov         ";

   																						/* Forma Pagamento */                                                                 
   $sSqlDadosOrdem .= "                       left join empagemovforma  on empagemovforma.e97_codmov = empagemov.e81_codmov         ";
   $sSqlDadosOrdem .= "                       left join empageforma     on empageforma.e96_codigo    = empagemovforma.e97_codforma  ";

     
   $sSqlDadosOrdem .= "    		     where pagordem.e50_codord  = {$e50_codord}    ";
   $sSqlDadosOrdem .= "    		     	 and empagemov.e81_codmov = {$e81_codmov})   ";
   $sSqlDadosOrdem .= "    		   as x                                            ";
   $sSqlDadosOrdem .= "       inner join cgm 			on cgm.z01_numcgm = _numcgm    ";
   $sSqlDadosOrdem .= "   		 left  join pcfornecon on pc63_numcgm = _numcgm    ";
   $rsDadosOrdem = pg_query($sSqlDadosOrdem);
   
   /* Autenticação */
   $sSqlAutenticacoa  = " select k12_valor, k12_codautent  "; 
   $sSqlAutenticacoa .= " from corempagemov ";                       
   $sSqlAutenticacoa .= "      inner join corrente         on corrente.k12_id          = corempagemov.k12_id     ";
   $sSqlAutenticacoa .= "                                 and corrente.k12_data        = corempagemov.k12_data   ";
   $sSqlAutenticacoa .= "                                 and corrente.k12_autent      = corempagemov.k12_autent ";
   $sSqlAutenticacoa .= "      inner join corgrupocorrente on corgrupocorrente.k105_id = corrente.k12_id         ";
   $sSqlAutenticacoa .= "                                 and k105_data                = corrente.k12_data       ";
   $sSqlAutenticacoa .= "                                 and k105_autent              = corrente.k12_autent     ";
   $sSqlAutenticacoa .= "                                 and k105_corgrupotipo        = 1                       ";
   $sSqlAutenticacoa .= "      inner join corautent        on corautent.k12_id         = corrente.k12_id         ";
   $sSqlAutenticacoa .= "                                 and corautent.k12_data       = corrente.k12_data       ";
   $sSqlAutenticacoa .= "                                 and corautent.k12_autent     = corrente.k12_autent     ";
   $sSqlAutenticacoa .= " where corempagemov.k12_codmov = {$e81_codmov} ";
   
   $rsDadosAutenticacoa = pg_query($sSqlAutenticacoa);
   
   if ($rsDadosAutenticacoa) { 
     db_fieldsmemory($rsDadosAutenticacoa, 0);
   }
  /**
   * coloquei a linha abaixo porque emitindo por data em charqueadas dava erro
   */
  if (pg_numrows($rsDadosOrdem)==0) continue;

  db_fieldsmemory($rsDadosOrdem,0);

  $sSqlItem  = " select *,e53_valor - e53_vlranu as saldo,                             ";
  $sSqlItem .= "        e53_valor - e53_vlranu - e53_vlrpag as saldo_final                       ";
  $sSqlItem .= "        from pagordemele                                                           ";
  $sSqlItem .= "        inner join pagordem    on pagordem.e50_codord = pagordemele.e53_codord     ";
  $sSqlItem .= "        inner join empempenho  on empempenho.e60_numemp = pagordem.e50_numemp      ";
  $sSqlItem .= "        inner join orcelemento on orcelemento.o56_codele = pagordemele.e53_codele  ";
  $sSqlItem .= "                              and orcelemento.o56_anousu = empempenho.e60_anousu   ";
  $sSqlItem .= "	 	    inner join empelemento on empelemento.e64_numemp = empempenho.e60_numemp   ";
  $sSqlItem .= " 			                        and orcelemento.o56_codele = empelemento.e64_codele  ";
  $sSqlItem .= " where pagordemele.e53_codord = $e50_codord ";
  $rsItem = pg_query($sSqlItem);
		   
  $sSqlOutrasOrdens  = " select sum(saldo) as outrasordens                                                                    ";
  $sSqlOutrasOrdens .= "        from                                                                                          ";
  $sSqlOutrasOrdens .= "        		 (select *,e53_valor - e53_vlranu as saldo                                                ";
  $sSqlOutrasOrdens .= "        						 from pagordemele                                                                 ";
  $sSqlOutrasOrdens .= "       										inner join pagordem    on pagordem.e50_codord = pagordemele.e53_codord      ";
  $sSqlOutrasOrdens .= "                          inner join empempenho  on empempenho.e60_numemp = pagordem.e50_numemp       ";
  $sSqlOutrasOrdens .= "                          inner join orcelemento on orcelemento.o56_codele = pagordemele.e53_codele   ";
  $sSqlOutrasOrdens .= "                          											and orcelemento.o56_anousu = empempenho.e60_anousu    ";
  $sSqlOutrasOrdens .= "                          inner join empelemento on empelemento.e64_numemp = empempenho.e60_numemp    ";
  $sSqlOutrasOrdens .= "                          			 and orcelemento.o56_codele = empelemento.e64_codele                  ";
  $sSqlOutrasOrdens .= "               where pagordem.e50_codord <> $e50_codord                                               ";
  $sSqlOutrasOrdens .= "                 and pagordem.e50_numemp = $e50_numemp) as x";
  $rsOutrasOrdens    = pg_query($sSqlOutrasOrdens);
  
  db_fieldsmemory($rsOutrasOrdens,0);                                                                                      
                                                                                                                           
  $sSqlRetencoes  = " select e20_pagordem as e52_codord, ";
  $sSqlRetencoes .= "        k02_codigo,                 ";
  $sSqlRetencoes .= "        k02_drecei,                 ";
  $sSqlRetencoes .= "        e23_recolhido,              ";
  $sSqlRetencoes .= "        e23_valorretencao           ";
  $sSqlRetencoes .= "   from retencaopagordem            ";
  $sSqlRetencoes .= "        inner join retencaoreceitas  rr on rr.e23_retencaopagordem = retencaopagordem.e20_sequencial  ";
  $sSqlRetencoes .= "        inner join retencaoempagemov rm on rm.e27_retencaoreceitas = rr.e23_sequencial      ";
  $sSqlRetencoes .= "        inner join retencaotiporec   rt on rt.e21_sequencial       = rr.e23_retencaotiporec ";
  $sSqlRetencoes .= "        inner join tabrec            tr on tr.k02_codigo           = rt.e21_receita         ";
  $sSqlRetencoes .= "  where e20_pagordem     = {$e50_codord} ";
  $sSqlRetencoes .= "    and rm.e27_empagemov = {$e81_codmov} ";
  $sSqlRetencoes .= "    and rm.e27_principal is true         ";
  $sSqlRetencoes .= "    and rr.e23_ativo     is true ";
  
  $rsRetencoes    = pg_query($sSqlRetencoes);
  $aRetencoes     = db_utils::getColectionByRecord($rsRetencoes);

  $sSqlPcFornecedor  = " select *,                                                                  ";
  $sSqlPcFornecedor .= "        case when pc63_cnpjcpf is not null and trim(pc63_cnpjcpf) <> ''     ";
  $sSqlPcFornecedor .= "                                           and pc63_cnpjcpf::text::int8 > 0 ";
  $sSqlPcFornecedor .= "             then pc63_cnpjcpf                                              ";
  $sSqlPcFornecedor .= "        else '".$z01_cgccpf."' end as z01_cgccpf                            ";
  $sSqlPcFornecedor .= "        from pcfornecon                                                     ";
  $sSqlPcFornecedor .= "             inner join pcforneconpad on pc64_contabanco = pc63_contabanco  ";
  $sSqlPcFornecedor .= "  where pc63_numcgm = {$z01_numcgm}";
  $rsPcfornecon = pg_query($sSqlPcFornecedor);
   
   if(pg_numrows($rsPcfornecon) > 0) {
     db_fieldsmemory($rsPcfornecon,0);
   }

   /**
    * Verificamos o cnpj da unidade. caso diferente de null, e diferente do xcnpj da instituição, 
    * mostramso a descrição e o cnpj da unidade
    */
   if ($o41_cnpj != "" && $o41_cnpj!= $cgc) {
     
     $nomeinst = $o41_descr;
     $cgc      = $o41_cnpj;
   }
   $pdf1->logo             = $logo;
   $pdf1->prefeitura       = $nomeinst;
   $pdf1->enderpref        = trim($ender).",".$numero;
   $pdf1->municpref        = $munic;
   $pdf1->cgcpref          = $cgc;
   $pdf1->telefpref        = $telef;
   $pdf1->banco            = $pc63_banco;
   $pdf1->agencia          = $pc63_agencia;
   $pdf1->agenciadv        = $pc63_agencia_dig;
   $pdf1->conta            = $pc63_conta;
   $pdf1->contadv          = $pc63_conta_dig;
   $pdf1->emailpref        = $email;
   $pdf1->bancorec         = null;
   $pdf1->agenciarec       = null;
   $pdf1->contarec         = null;
	 $sSqlConta   = " select c61_codcon, c61_codigo  ";
   $sSqlConta  .= "        from conplanoreduz      ";
   $sSqlConta  .= "               inner join conplano on c60_codcon      = c61_codcon and c60_anousu=c61_anousu  ";
   $sSqlConta  .= "               inner join consistema on c52_codsis    = c60_codsis ";
	 $sSqlConta  .= " 						 inner join conplanoconta on c63_codcon = c61_codcon  ";
	 $sSqlConta  .= " 						                         and c63_anousu = c61_anousu  ";
   $sSqlConta  .= "       where c61_instit   = {$iInstit}    ";
   $sSqlConta  .= "         and c61_anousu   = {$iAnoUso}    ";
   $sSqlConta  .= "         and c61_codigo   = {$o58_codigo} ";
   $sSqlConta  .= "         and c52_descrred = 'F' ";
   $rsConta     = pg_exec(analiseQueryPlanoOrcamento($sSqlConta));
   
   if ($rsConta != false && (pg_numrows($rsConta) == 1)) {
     
     db_fieldsmemory($rsConta,0);
     $sSqlConplanoConta = "select * from conplanoconta where c63_codcon = $c61_codcon and c63_anousu = {$iAnoUso} ";
     $rsConta           = pg_exec($sSqlConplanoConta);
		 if (pg_num_rows($rsConta) == 1) {
    
				db_fieldsmemory($rsConta,0);
        $pdf1->bancorec    = $c63_banco;
        $pdf1->agenciarec  = $c63_agencia;
        $pdf1->contarec    = $c63_conta;
	   }
	 }
	 $pdf1->sMovimento       = $e81_codmov;
   $pdf1->numcgm           = $z01_numcgm;
   $pdf1->nome             = $z01_nome;
   $pdf1->cnpj             = $z01_cgccpf;
   $pdf1->ender            = $z01_ender;
   $pdf1->munic            = $z01_munic;
   $pdf1->ordpag           = $e50_codord;
   $pdf1->ufFornecedor     = $z01_uf;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->dotacao          = $estrutural;
   $pdf1->outrasordens     = $outrasordens;
   $pdf1->recorddositens   = $rsItem;
   $pdf1->ano		           = $e60_anousu;
   $pdf1->linhasdositens   = pg_numrows($rsItem);
   $pdf1->elementoitem     = "o56_elemento";
   $pdf1->descr_elementoitem = "o56_descr";
   $pdf1->vlremp           = "e53_valor";
   $pdf1->vlranu           = "e53_vlranu";
   $pdf1->vlrpag           = "e53_vlrpag";
   $pdf1->vlrsaldo         = "saldo";
   $pdf1->saldo_final      = "saldo_final";
   $pdf1->recordretencoes  = $rsRetencoes;
   $pdf1->linhasretencoes  = pg_numrows($rsRetencoes);
   $pdf1->receita          = "e52_receit";
   $pdf1->dreceita         = "k02_drecei";
   $pdf1->vlrrec           = "e52_valor";
   $pdf1->aRetencoes       = $aRetencoes;
   $pdf1->orcado	       = $e60_vlrorc; 
   $pdf1->saldo_ant        = $e60_salant;
   $pdf1->empenhado        = $e60_vlremp ;
   $pdf1->empenho_anulado  = $e60_vlranu ;
   $pdf1->numemp           = $e60_codemp.'/'.$e60_anousu;
   $pdf1->orgao            = $o58_orgao;
   $pdf1->descr_orgao      = $o40_descr;
   $pdf1->unidade          = $o58_unidade;
   $pdf1->descr_unidade    = $o41_descr;
   $pdf1->funcao           = $o58_funcao;
   $pdf1->descr_funcao     = $o52_descr;
   $pdf1->subfuncao        = $o58_subfuncao;
   $pdf1->descr_subfuncao  = $o53_descr;
   $pdf1->programa         = $o58_programa;
   $pdf1->descr_programa   = $o54_descr;
   $pdf1->projativ         = $o58_projativ;
   $pdf1->descr_projativ   = $o55_descr;
   $pdf1->recurso          = $o58_codigo;
   $pdf1->descr_recurso    = $o15_descr;
   $pdf1->elemento     	   = $o56_elemento;
   $pdf1->descr_elemento   = $o56_descr;
   
   $pdf1->emissao      = db_formatar($e50_data,'d');
   $pdf1->texto		     = $sLogin.'  -  '.date("d-m-Y",$sDataUsu).'    '.db_hora($sDataUsu);
   
   $pdf1->telef            = $z01_telef;
   $pdf1->fax              = $z01_fax;
   
   /* Conta Complano */
   $pdf1->iReduzido         = $c61_reduz;
   $pdf1->sContaContabil    = $c60_descr;
   $pdf1->sBanco            = $c63_banco;
   $pdf1->sAgencia          = $c63_agencia;
   $pdf1->sDigtoAgencia     = $c63_dvagencia;
   $pdf1->sContaBanco       = $c63_conta;
   $pdf1->sDigitoContaBanco = $c63_dvconta; 
   
   /* Tipo Pagamento*/
   $pdf1->iTipoPagamento    = $e96_codigo;
   
   /* Se pago com cheque */
   $pdf1->sCheque           = $e91_cheque;
   
   /* Autenticação*/
   $pdf1->sAutenticacao     = $k12_codautent;
   
   /* Valor do Movimento*/
   $pdf1->nValorMovimento   = $e81_valor;
   
   
   if($clpagordem->numrows == 1 && isset($valor_ordem)) {
     
   	if( $valor_ordem > pg_result($rsItem,0,"saldo") ){
       $valor_ordem = pg_result($rsItem,0,"saldo");
     }
     
     $pdf1->valor_ordem  = "$valor_ordem";
     $pdf1->obs = "$e50_obs";
     
   } else {
   	 $pdf1->valor_ordem = "";
   	 $pdf1->obs 		= "$e50_obs";
   }
   if (isset($oGet->sObservacao) && !empty($oGet->sObservacao)) {
     $pdf1->obs = $oGet->sObservacao;
   }
   
   $pdf1->imprime();
}
//include("fpdf151/geraarquivo.php");
$pdf1->objpdf->Output();
?>