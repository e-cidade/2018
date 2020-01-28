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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_pagordemele_classe.php"));

$oGet = db_utils::postMemory($_GET);

db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;

$iAnoUso  = db_getsession('DB_anousu');
$sDataUsu = db_getsession("DB_datausu");
$sLogin   = db_getsession("DB_login");
$iInstit  = db_getsession("DB_instit");

$sqlpref    = "select * from db_config where codigo = {$iInstit} ";
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref, 0);


$sFornecedor = null;
if ( isset($oGet->aFornecedor) && !empty($oGet->aFornecedor) ) {
  $sFornecedor = $oGet->aFornecedor;
}

$dbwhere = "";
if ((isset($e60_codemp_ini) && !empty($e60_codemp_ini)) && !isset($e60_codemp_fim)) {

  $aEmpenhoIni = explode("/", $e60_codemp_ini);
  $dbwhere    .= " e60_codemp  = ";
  $dbwhere    .= " '{$aEmpenhoIni[0]}'";
  $dbwhere    .= " and e60_anousu = ";
  $dbwhere    .= " {$aEmpenhoIni[1]} ";

}

if ((isset($e60_codemp_ini) && !empty($e60_codemp_ini)) && (isset($e60_codemp_fim) && !empty($e60_codemp_fim))) {

  $aEmpenhoIni = explode("/", $e60_codemp_ini);
  $aEmpenhoFim = explode("/", $e60_codemp_fim);
  $dbwhere    .= " cast(e60_codemp as integer) between ";
  $dbwhere    .= " {$aEmpenhoIni[0]} and {$aEmpenhoFim[0]} ";
  $dbwhere    .= " and e60_anousu between";
  $dbwhere    .= " {$aEmpenhoIni[1]} and {$aEmpenhoFim[1]} ";
}

if(isset($codordem) && $codordem != ''){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= " e50_codord=$codordem ";
}elseif(isset($e50_codord_ini) && $e50_codord_ini != ''){
  if(isset($e50_codord_fim) && $e50_codord_fim != ''){
    if(strlen($dbwhere) > 0) {
  	  $dbwhere .= " and ";
    }
    $dbwhere .= " e50_codord between $e50_codord_ini and $e50_codord_fim ";
  }else{
    if(strlen($dbwhere) > 0) {
  	  $dbwhere .= " and ";
    }
    $dbwhere .= " e50_codord=$e50_codord_ini ";
  }

} else if (isset($listaordem)) {

  if (strlen($dbwhere) > 0) {
    $dbwhere .= " and ";
  }
  $dbwhere .= " e50_codord in($listaordem)";

}else{
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dbwhere .= "1=1 ";
}

if(isset($dtini) && $dtini!=""){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dtini=str_replace("X","-",$dtini);
  $dbwhere.=" e50_data >= '$dtini'";
}

if(isset($dtfim) && $dtfim!=""){
  if(strlen($dbwhere) > 0) {
	  $dbwhere .= " and ";
  }
  $dtfim=str_replace("X","-",$dtfim);
  $dbwhere.=" e50_data <= '$dtfim'";
}

if (!empty($sFornecedor)) {

  if(strlen($dbwhere) > 0) {
    $dbwhere .= " and ";
  }
  $dbwhere.= " z01_numcgm in ({$sFornecedor}) ";
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'7');
$pdf1->objpdf->SetTextColor(0,0,0);

//rotina que pega as ordens a serem impressas
$sSqlPagOrdem = $clpagordem->sql_query('',' e50_codord ',' e50_codord ', $dbwhere);
$result       = $clpagordem->sql_record($sSqlPagOrdem);

if ($clpagordem->numrows>0) {
  db_fieldsmemory($result,0);
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não a Ordem de Pagamento No. '.$codordem.'. Verifique!');
}

$result2 = db_query("select * from empparametro where e39_anousu = {$iAnoUso} ");

if (pg_numrows($result2)>0) {
  db_fieldsmemory($result2,0);
  $pdf1->nvias= $e30_nroviaord;
}

for ($i = 0;$i < $clpagordem->numrows;$i++) {

   db_fieldsmemory($result,$i);
   $sql = "select *, e03_numeroprocesso as processo
           from
           (select *,fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural,
	           case when e49_numcgm is null then e60_numcgm else e49_numcgm end as _numcgm
           from pagordem
	        inner join empempenho 		on empempenho.e60_numemp = pagordem.e50_numemp
		inner join db_config 		on db_config.codigo = empempenho.e60_instit
		inner join orcdotacao 		on orcdotacao.o58_anousu = empempenho.e60_anousu
				               and orcdotacao.o58_coddot = empempenho.e60_coddot
	                                       and o58_instit = {$iInstit}
	        inner join orcorgao   		on o58_orgao = o40_orgao
	                                       and o40_anousu = empempenho.e60_anousu
	        inner join orcunidade 		on o58_unidade = o41_unidade
	                                       and o58_orgao = o41_orgao
	                                       and o58_anousu = o41_anousu
	        inner join orcfuncao  		on o58_funcao = o52_funcao
	        inner join orcsubfuncao  	on o58_subfuncao = o53_subfuncao
	        inner join orcprograma  	on o58_programa = o54_programa
	                                       and o54_anousu = o58_anousu
	        inner join orcprojativ  	on o58_projativ = o55_projativ
	                                       and o55_anousu = o58_anousu
	        inner join orcelemento a	on o58_codele = o56_codele
		                               and o58_anousu = o56_anousu
	        inner join orctiporec  		on o58_codigo = o15_codigo
	         left join pagordemprocesso on pagordem.e50_codord = pagordemprocesso.e03_pagordem

	        inner join emptipo 		on emptipo.e41_codtipo = empempenho.e60_codtipo
		left join pagordemconta		on e50_codord = e49_codord
		where pagordem.e50_codord = $e50_codord ) as x
           inner join cgm 			on cgm.z01_numcgm = _numcgm
           left  join pcfornecon on pc63_numcgm = _numcgm
	   ";

  $resultord = db_query($sql);

  //
  // coloquei a linha abaixo porque emitindo por data em charqueadas dava erro
  //
  if (pg_numrows($resultord)==0) continue;

  db_fieldsmemory($resultord,0);

  $sqlitem = "select *,e53_valor - e53_vlranu  as saldo , e53_valor - e53_vlranu - e53_vlrpag as saldo_final
              from pagordemele

	           inner join pagordem on pagordem.e50_codord = pagordemele.e53_codord
		   inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp

		   inner join orcelemento on orcelemento.o56_codele = pagordemele.e53_codele and
		                             orcelemento.o56_anousu = empempenho.e60_anousu

		   inner join empelemento on empelemento.e64_numemp = empempenho.e60_numemp
		   			 and orcelemento.o56_codele = empelemento.e64_codele

		   where pagordemele.e53_codord = $e50_codord ";
  $resultitem = db_query($sqlitem);

  $sqloutrasordens = " select sum(saldo) as outrasordens
              from
              (select *,e53_valor - e53_vlranu as saldo
              from pagordemele
	           inner join pagordem on pagordem.e50_codord = pagordemele.e53_codord
		   inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp
		   inner join orcelemento on orcelemento.o56_codele = pagordemele.e53_codele and
		                             orcelemento.o56_anousu = empempenho.e60_anousu
		   inner join empelemento on empelemento.e64_numemp = empempenho.e60_numemp
		   			 and orcelemento.o56_codele = empelemento.e64_codele

		   where pagordem.e50_codord <> $e50_codord
		     and pagordem.e50_numemp = $e50_numemp) as x";
//  echo $clpagordemele->sql_query($e50_codord);
  $resultoutrasordens = db_query($sqloutrasordens);
  db_fieldsmemory($resultoutrasordens,0);
//   db_criatabela($resultoutrasordens);exit;
   $sqlretencoes = "select e52_codord,
   			                    k02_codigo,
			                      k02_drecei,
			                      e52_valor as e23_valorretencao
		                        from pagordemrec
		    	                  inner join tabrec on k02_codigo = e52_receit
	                    where e52_codord = $e50_codord ";
   $resultretencoes = db_query($sqlretencoes);
   $aRetencoes      = db_utils::getCollectionByRecord($resultretencoes);
   $result_pcfornecon = db_query("select *,
                                       case when pc63_cnpjcpf is not null and trim(pc63_cnpjcpf) <> ''
                                                                          and pc63_cnpjcpf::text::int8 > 0
                                            then pc63_cnpjcpf
                                       else '".$z01_cgccpf."' end as z01_cgccpf
                                    from pcfornecon
                                         inner join pcforneconpad on pc64_contabanco = pc63_contabanco
                                   where pc63_numcgm = ".$z01_numcgm);

   if(pg_numrows($result_pcfornecon) > 0){
     db_fieldsmemory($result_pcfornecon,0);
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
	 $sql                    = "select c61_codcon,c61_codigo
                                from conplanoreduz
                                       inner join conplano on c60_codcon      = c61_codcon and c60_anousu=c61_anousu
                                       inner join consistema on c52_codsis    = c60_codsis
																			 inner join conplanoconta on c63_codcon = c61_codcon
																			                         and c63_anousu = c61_anousu
                               where c61_instit   = {$iInstit}
                                 and c61_anousu   = {$iAnoUso}
                                 and c61_codigo   = {$o58_codigo}
                                 and c52_descrred = 'F' ";
   $result_conta = db_query(analiseQueryPlanoOrcamento($sql));
//	 echo $sql;
//   die("<br><br>".$sqlConta);
   if ($result_conta != false && (pg_numrows($result_conta) == 1)) {
     db_fieldsmemory($result_conta,0);
     $sqlConta = "select * from conplanoconta where c63_codcon = $c61_codcon and c63_anousu = {$iAnoUso} ";
     $result_conta = db_query($sqlConta);
		 if (pg_num_rows($result_conta) == 1){

				db_fieldsmemory($result_conta,0);
        $pdf1->bancorec    = $c63_banco;
        $pdf1->agenciarec  = $c63_agencia;
        $pdf1->contarec    = $c63_conta;
	   }
	 }

   $pdf1->numcgm           = $z01_numcgm;
   $pdf1->processo         = $processo;
   $pdf1->nome             = $z01_nome;
   $pdf1->cnpj             = $z01_cgccpf;
   $pdf1->ender            = $z01_ender;
   $pdf1->munic            = $z01_munic;
   $pdf1->ordpag           = $e50_codord;
   $pdf1->ufFornecedor     = $z01_uf;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->dotacao          = $estrutural;
   $pdf1->outrasordens     = $outrasordens;
   $pdf1->recorddositens   = $resultitem;
   $pdf1->ano		           = $e60_anousu;
   $pdf1->linhasdositens   = pg_numrows($resultitem);
   $pdf1->elementoitem     = "o56_elemento";
   $pdf1->descr_elementoitem = "o56_descr";
   $pdf1->vlremp           = "e53_valor";
   $pdf1->vlranu           = "e53_vlranu";
   $pdf1->vlrpag           = "e53_vlrpag";
   $pdf1->vlrsaldo         = "saldo";
   $pdf1->saldo_final      = "saldo_final";
   $pdf1->recordretencoes  = $resultretencoes;
   $pdf1->linhasretencoes  = pg_numrows($resultretencoes);
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

   /**
    * Variáveis utilizadas na assinatura. Sómente utilizada na impressão por movimento
    */
   $pdf1->iReduzido         = "";
   $pdf1->sContaContabil    = "";
   $pdf1->sBanco            = "";
   $pdf1->sAgencia          = "";
   $pdf1->sDigtoAgencia     = "";
   $pdf1->sContaBanco       = "";
   $pdf1->sDigitoContaBanco = "";
   $pdf1->iTipoPagamento    = "";
   $pdf1->sCheque           = "";
   $pdf1->sAutenticacao     = "";
   $pdf1->nValorMovimento   = "";


   if($clpagordem->numrows == 1 && isset($valor_ordem)){

   	if( $valor_ordem > pg_result($resultitem,0,"saldo") ){
       $valor_ordem = pg_result($resultitem,0,"saldo");
     }

     $pdf1->valor_ordem  = "$valor_ordem";
     if (isset($historico) && trim($historico)!= ""){
       $pdf1->obs = "$historico";
     }else{
       $pdf1->obs = "$e50_obs";
     }
   } else {
   	 $pdf1->valor_ordem = "";
   	 $pdf1->obs 		= "$e50_obs";
   }
   $pdf1->imprime();
}
//include(modification("fpdf151/geraarquivo.php"));
require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php"));

if (GerenciadorEletronicoDocumentoConfiguracao::getInstance()->utilizaGED()) {

  try {

    require_once(modification("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php"));
    require_once(modification("libs/exceptions/BusinessException.php"));
    if (isset($e50_codord_ini) && isset($e50_codord_fim) && $e50_codord_ini != $e50_codord_fim) {

      $sMsgErro  = "A configuração para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>Neste ";
      $sMsgErro .= "caso não é possível somente um documento referente a ordem de compra por vez. <br><br>";
      $sMsgErro .= "Por favor, informe informe um único código de ordem de compra.";
      throw new BusinessException($sMsgErro);
    }

    if (!empty($codordem)) {
      $e50_codord_ini = $codordem;
    }

    $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::ORDEM_PAGAMENTO;

    $oGerenciador = new GerenciadorEletronicoDocumento();
    $oGerenciador->setLocalizacaoOrigem("tmp/");
    $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$e50_codord_ini}.pdf");

    $oStdDadosGED        = new stdClass();
    $oStdDadosGED->nome  = $sTipoDocumento;
    $oStdDadosGED->tipo  = "NUMERO";
    $oStdDadosGED->valor = $e50_codord_ini;
    $pdf1->objpdf->Output("tmp/{$sTipoDocumento}_{$e50_codord_ini}.pdf");
    $oGerenciador->moverArquivo(array($oStdDadosGED));


  } catch (Exception $eErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
  }
} else {
  $pdf1->objpdf->Output();
}
