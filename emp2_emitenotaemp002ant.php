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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include("classes/db_empparametro_classe.php");

$clempparametro	  = new cl_empparametro;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$head3 = "CADASTRO DE CÓDIGOS";
//$head5 = "PERÍODO : ".$mes." / ".$ano;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

if(isset($e60_numemp) && $e60_numemp != ''){
  $dbwhere = " e60_numemp=$e60_numemp ";
}else{
  if( isset($dtini_dia) ){
    $dbwhere = " e60_emiss >= '$dtini_ano-$dtini_mes-$dtini_dia'";
   
    if( isset($dtfim_dia) ){
      $dbwhere .= " and e60_emiss <= '$dtfim_ano-$dtfim_mes-$dtfim_dia'";
    }
  }  
  
}
//echo $dbwhere;
$sqlemp = "
	select empempenho.*,
	       cgm.* ,
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
	       l03_descr,
	       fc_estruturaldotacao(o58_anousu,o58_coddot) as estrutural
	from empempenho 
	     left join cflicita	on l03_tipo = e60_tipol
	     inner join orcdotacao 	on o58_coddot = e60_coddot
	                               and o58_instit = ".db_getsession("DB_instit")."
				       and o58_anousu = e60_anousu
	     inner join orcorgao   	on o58_orgao = o40_orgao
	                               and o40_anousu = ".db_getsession("DB_anousu")."
	     inner join orcunidade 	on o58_unidade = o41_unidade
	                               and o58_orgao = o41_orgao
	                               and o41_anousu = o58_anousu
	     inner join orcfuncao  	on o58_funcao = o52_funcao
	     inner join orcsubfuncao  	on o58_subfuncao = o53_subfuncao
	     inner join orcprograma  	on o58_programa = o54_programa
	                               and o54_anousu = o58_anousu
	     inner join orcprojativ  	on o58_projativ = o55_projativ
	                               and o55_anousu = o58_anousu
	     inner join orcelemento a	on o58_codele = o56_codele and 
	                                   o58_anousu = o56_anousu
	     inner join orctiporec  	on o58_codigo = o15_codigo
	     inner join cgm 		on z01_numcgm = e60_numcgm
	     inner join empempaut	on e60_numemp = e61_numemp
	where  $dbwhere 
	";

$result = pg_exec($sqlemp);	
//db_criatabela($result);exit;

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'6');
//$pdf1->modelo = 6;
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

for($i = 0;$i < pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   $sqlitem = "
           select
	        case when e62_descr = '' 
		     then pc01_descrmater
		     else pc01_descrmater||' ('||e62_descr||')' end as pc01_descrmater, 
              	e62_numemp,
		e62_quant,
		e62_vltot,
		e62_codele,
		o56_elemento,
	        o56_descr	
	   from empempitem
		inner join empempenho 	on e62_numemp = e60_numemp
		inner join pcmater 	on e62_item = pc01_codmater
	        inner join orcelemento	on e62_codele = o56_codele
		                       and o56_anousu = e60_anousu
	   where e62_numemp = '$e60_numemp'
	   order by o56_elemento,pc01_descrmater
	   ";
		  
   $resultitem = pg_exec($sqlitem);
//   db_criatabela($resultitem);exit;

   $pdf1->prefeitura       = $nomeinst;
   $pdf1->logo			       = $logo;
   $pdf1->enderpref        = $ender;
   $pdf1->municpref        = $munic;
   $pdf1->telefpref        = $telef;
   $pdf1->emailpref        = $email;
   $pdf1->numcgm           = $z01_numcgm;
   $pdf1->nome             = $z01_nome;
   $pdf1->ender            = $z01_ender;
   $pdf1->munic            = $z01_munic;
   $pdf1->dotacao          = $estrutural;
   $pdf1->descr_licitacao  = $l03_descr;
   $pdf1->coddot           = $o58_coddot;
   $pdf1->destino          = $e60_destin;

   $e60_resumo = str_replace("\n",'   -   ',$e60_resumo);
   $e60_resumo = str_replace("\r",'',$e60_resumo);
   
   $pdf1->resumo           =    $e60_resumo;
   $pdf1->licitacao        = $e60_codtipo;
   $pdf1->recorddositens   = $resultitem;
   $pdf1->linhasdositens   = pg_numrows($resultitem);
   $pdf1->quantitem        = "e62_quant";
   $pdf1->valoritem        = "e62_vltot";
   $pdf1->descricaoitem    = "pc01_descrmater";

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
   $pdf1->analitico        = "o56_elemento";
   $pdf1->descr_analitico  = "o56_descr";
   $pdf1->sintetico        = $sintetico;
   $pdf1->descr_sintetico  = $descr_sintetico;
   $pdf1->recurso          = $o58_codigo;
   $pdf1->descr_recurso    = $o15_descr;
   $pdf1->emissao          = db_formatar($e60_emiss,'d');
   $pdf1->texto		   = db_getsession("DB_login").'  -  '.date("d-m-Y",db_getsession("DB_datausu")).'    '.db_hora(db_getsession("DB_datausu"));
			  
   $pdf1->imprime();
}
//include("fpdf151/geraarquivo.php");
$pdf1->objpdf->Output();

   
?>