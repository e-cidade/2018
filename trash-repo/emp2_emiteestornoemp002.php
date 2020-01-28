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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include("classes/db_empparametro_classe.php");
include("classes/db_db_config_classe.php");


$clempparametro	 = new  cl_empparametro;
$cltipoinstit    = new  cl_db_config;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$result_instit = $cltipoinstit->sql_record($cltipoinstit->sql_query_tipoinstit(db_getsession("DB_instit"),"db21_tipoinstit",null,""));
db_fieldsmemory($result_instit,0);


$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

if(isset($codlan)){
  $dbwhere = " where c75_codlan in($codlan) ";
}else if(isset($codemp)){
  $posicao = strpos($codemp,"/");

  if ($posicao === false){
    $e60_anousu = db_getsession("DB_anousu");
  } else {
    $e60_anousu = substr($codemp,($posicao+1),4);
    $codemp     = substr($codemp,0,$posicao);
  }

  $dbwhere = " where e60_codemp = '$codemp' and e60_anousu = $e60_anousu";
}else if(isset($numemp)){
  $dbwhere = " where e60_numemp = $numemp";
}

$sqlemp = "
	select distinct empempenho.*,
	       z01_numcgm ,
		   z01_nome,
		   z01_telef,
		   z01_ender,
		   z01_numero,
		   z01_munic,
		   z01_cgccpf,
	       o58_orgao,
	       o40_descr,
	       o58_unidade,
	       o41_descr,
	       o58_funcao,
	       o52_descr,
	       o58_subfuncao,
	       o53_descr,
	       o58_programa,
	       o54_descr,
	       o58_projativ,
	       o55_descr,
	       o58_coddot,
	       o56_elemento as sintetico,
	       o56_descr as descr_sintetico,
	       o58_codigo,
     	   o15_descr,
	       e61_autori,
	       o41_instit,
	       o40_instit,
	       o55_instit,
	       l03_descr,
	       c75_codlan,
	       c70_data,
	       c70_valor,
	       c72_complem,
	       fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural
   	  from empempenho
     inner join conlancamemp  on c75_numemp     = e60_numemp
     inner join conlancam	  on c70_codlan     = c75_codlan
     inner join conlancamdoc  on c71_codlan     = c70_codlan 
     inner join conhistdoc    on c53_coddoc     = c71_coddoc  and c53_tipo = 31
     left  join cflicita	  on l03_tipo       = e60_tipol
                             and l03_instit     = e60_instit
	                         and e60_codcom     = l03_codcom
     inner join orcdotacao 	  on o58_coddot     = e60_coddot
	                         and o58_instit     = ".db_getsession("DB_instit")."
				             and o58_anousu     = e60_anousu
	 inner join orcorgao   	  on o58_orgao      = o40_orgao
	                         and o40_anousu     = ".db_getsession("DB_anousu")."
     inner join orcunidade 	  on o58_unidade    = o41_unidade
	                         and o58_orgao      = o41_orgao
                             and o41_anousu     = o58_anousu
	                         and o41_instit     = o58_instit
     inner join orcfuncao  	  on o58_funcao     = o52_funcao
     inner join orcsubfuncao  on o58_subfuncao  = o53_subfuncao
     inner join orcprograma   on o58_programa   = o54_programa
	                         and o54_anousu     = o58_anousu
	 inner join orcprojativ   on o58_projativ   = o55_projativ
	                         and o55_anousu     = o58_anousu
     inner join orcelemento a on o58_codele     = o56_codele AND
                                 o58_anousu     = o56_anousu
     inner join orctiporec    on o58_codigo     = o15_codigo
     inner join cgm 		  on z01_numcgm     = e60_numcgm
     left  outer join empempaut	     on e60_numemp = e61_numemp	     
     left  outer join conlancamcompl on c72_codlan = c70_codlan
         
    $dbwhere
	";

$result = pg_exec($sqlemp);	
//db_criatabela($result);exit;

if (pg_numrows($result)==0){
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado !  ");
}



$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'15');
//$pdf1->modelo = 15;
$pdf1->objpdf->SetTextColor(0,0,0);

//   $pdf1->imprime();

//$pdf1->objpdf->Output();

//exit;

//rotina que pega o numero de vias
$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_nroviaemp"));
//echo $clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_nroviaemp");
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
}     	 

$pdf1->nvias= $e30_nroviaemp;

$pdf1->db21_instit = $db21_tipoinstit;


for($i = 0;$i < pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   
   if ($c72_complem != "") {
     $matrizdados= split("Motivo:",$c72_complem);
   }
   
   $pdf1->prefeitura       = $nomeinst;
   $pdf1->logo		       = $logo;
   $pdf1->enderpref        = $ender.', '.$z01_numero;
   $pdf1->cgcpref          = $cgc;
   $pdf1->municpref        = $munic;
   $pdf1->telefpref        = $telef;
   $pdf1->emailpref        = $email;
   $pdf1->numcgm           = $z01_numcgm;
   $pdf1->nome             = $z01_nome;
   $pdf1->telefone         = $z01_telef;
   $pdf1->ender            = $z01_ender.', '.$z01_numero;
   $pdf1->munic            = $z01_munic;
   $pdf1->cnpj             = $z01_cgccpf;
   $pdf1->dotacao          = $estrutural;
   $pdf1->descr_licitacao  = $l03_descr;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->destino          = $e60_destin;
   $pdf1->resumo           = $e60_resumo;
   $pdf1->licitacao        = $e60_codtipo;

   $pdf1->orcado	   = $e60_vlrorc; 
   $pdf1->saldo_ant        = $e60_salant;
   $pdf1->empenhado        = $e60_vlremp;
   $pdf1->numemp           = $e60_numemp;
   $pdf1->codemp           = $e60_codemp;
   $pdf1->numaut           = $e61_autori;
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
   $pdf1->sintetico        = $sintetico;
   $pdf1->descr_sintetico  = $descr_sintetico;
   $pdf1->recurso          = $o58_codigo;
   $pdf1->descr_recurso    = $o15_descr;
   $pdf1->banco            = null;
   $pdf1->agencia          = null;
   $pdf1->conta            = null;
   $pdf1->anulado          = $c75_codlan;
   $pdf1->vlr_anul         = $c70_valor;
   $pdf1->data_est         = $c70_data;
   $pdf1->descr_anu        = @$matrizdados[0];

   $sQueryConta  = "select c61_codcon ";
   $sQueryConta .= "  from conplanoreduz ";
   $sQueryConta .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu  "; 
   $sQueryConta .= "       inner join consistema on c52_codsis = c60_codsis ";
   $sQueryConta .= " where c61_instit = ".db_getsession("DB_instit");
   $sQueryConta .= "   and c61_anousu = ".db_getsession("DB_anousu");
   $sQueryConta .= "   and c61_codigo = $o58_codigo and c52_descrred = 'F'";
   $result_conta = db_query(analiseQueryPlanoOrcamento($sQueryConta));
   if ($result_conta != false && (pg_numrows($result_conta) > 0 && pg_numrows($result_conta) <= 2)) {
     db_fieldsmemory($result_conta,0);
     $result_conta = db_query("select * from conplanoconta where c63_codcon = $c61_codcon and c63_anousu = ".db_getsession("DB_anousu"));
     if (pg_result($result_conta,0) > 0) {
       db_fieldsmemory($result_conta,0);
       $pdf1->banco            = $c63_banco;
       $pdf1->agencia          = $c63_agencia;
       $pdf1->conta            = $c63_conta;
     }
   }

   $pdf1->emissao          = db_formatar($e60_emiss,'d');
   $pdf1->texto            = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));
/*
   // assinatura 1
   $sqlparag = "select db02_texto as assinatura1
                from db_documento
                     inner join db_docparag on db03_docum = db04_docum
                     inner join db_paragrafo on db04_idparag = db02_idparag
                where db03_descr like '%ASSINATURAS EMPENHO%' and db02_descr like '%ASSINATURA 1%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);
//   db_criatabela($resparag);
   if ( pg_numrows($resparag) > 0 ) {
      db_fieldsmemory($resparag,0);
      $pdf1->assinatura1 = $assinatura1;
   }

   // assinatura 2

   $sqlparag = "select db02_texto as assinatura2
                from db_documento
                     inner join db_docparag on db03_docum = db04_docum
                     inner join db_paragrafo on db04_idparag = db02_idparag
                where db03_descr like '%ASSINATURAS EMPENHO%' and db02_descr like '%ASSINATURA 2%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);

   if ( pg_numrows($resparag) > 0 ) {
      db_fieldsmemory($resparag,0);
      $pdf1->assinatura2 = $assinatura2;
   }


   // assinatura 3

   $sqlparag = "select db02_texto as assinatura3
                from db_documento
                     inner join db_docparag on db03_docum = db04_docum
                     inner join db_paragrafo on db04_idparag = db02_idparag
                where db03_descr like '%ASSINATURAS EMPENHO%' and db02_descr like '%ASSINATURA 3%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);

   if ( pg_numrows($resparag) > 0 ) {
      db_fieldsmemory($resparag,0);
      $pdf1->assinatura3 = $assinatura3;
   }
																								  
   // assinatura prefeito

   $sqlparag = "select db02_texto as assinaturaprefeito
                from db_documento
                     inner join db_docparag on db03_docum = db04_docum
                     inner join db_paragrafo on db04_idparag = db02_idparag
                where db03_descr like '%ASSINATURA PREFEITO%' and db02_descr like '%PREFEITO%' and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);

   if ( pg_numrows($resparag) > 0 ) {
      db_fieldsmemory($resparag,0);
      $pdf1->assinaturaprefeito = $assinaturaprefeito;
   }
*/
			  
   $pdf1->imprime();
}
//include("fpdf151/geraarquivo.php");
$pdf1->objpdf->Output();

   
?>