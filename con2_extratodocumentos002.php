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

include("fpdf151/pdf.php");
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_conlancamrec_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdig_classe.php");
include("libs/db_libcontabilidade.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clconlancamval = new cl_conlancamval;
$clconlancamcgm = new cl_conlancamcgm;
$clconlancam  = new cl_conlancam;
$auxiliar     = new cl_conlancam;
$clorcsuplem = new cl_orcsuplem;
$clconlancamrec = new cl_conlancamrec;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdot  = new cl_conlancamdot;
$clconlancamdig  = new cl_conlancamdig;

$clconlancamcgm->rotulo->label();
$clconlancamval->rotulo->label();
$clconlancam->rotulo->label();
$clorcsuplem->rotulo->label();

$clrotulo->label("c60_descr");
$clrotulo->label("c53_descr");
$clrotulo->label("c53_coddoc");


///////////////////////////////////////////////////////////////////////
 $data1="";
 $data2="";
 $instit = db_getsession("DB_instit");

 @$data1="$data1_ano-$data1_mes-$data1_dia"; 
 @$data2="$data2_ano-$data2_mes-$data2_dia"; 
 if (strlen($data1) < 7){
    $data1= db_getsession("DB_anousu")."-01-31";
 }  
 if (strlen($data2) < 7){
    $data2= db_getsession("DB_anousu")."-12-31";
 }  

//---------
if (isset($lista)){
   $w="("; 
   $tamanho= sizeof($lista);
   for ($x=0;$x < sizeof($lista);$x++){
       $w = $w."$lista[$x]";
       if ($x < $tamanho-1) {
         $w= $w.",";
       }	
   }  
   $w = $w.")";
}
//--  monta sql
   $txt_where="1=1";
   if (isset($lista)){
       if (isset($ver) and $ver=="com"){
           $txt_where= $txt_where." and c61_reduz in  $w  and c61_instit = $instit ";
       } else {
           $txt_where= $txt_where." and c61_reduz  not in  $w and c61_instit = $instit ";
       }	 
   }  
   $txt_where = $txt_where." and c69_data between '$data1' and '$data2' "; 
//-----------------------------------------------------------------------------     

$sql =" select
            c70_data,
            c71_coddoc,
            case when (c71_coddoc is null) then 
               'SEM DOCUMENTO'
            else 
               c53_descr
            end as c53_descr,  
            sum(c70_valor) as c70_valor
        from conlancam 
            left outer join conlancamdoc on c71_codlan  = c70_codlan 
            left outer join conhistdoc on c53_coddoc = c71_coddoc
        where
            c70_data between '$data1' and '$data2'
        group by 
            c70_data,
            c71_coddoc,
            c53_descr
       ";		  

  $sql_analitico = "select
			                        c61_codcon,
			                        c61_reduz, 
						            c60_estrut,
			                        c60_descr as conta_descr,
				                    c69_codlan,
			                        c69_sequen,
			                        c69_data, 
			                        c69_codhist, 
			                        c53_coddoc,
			                        c53_descr, 
			                        c69_debito,
			  ( select c60_descr 
                from conplano 
			         inner join conplanoreduz on c61_codcon=c60_codcon and 
                                                                  c61_anousu=c60_anousu and
                                                                  c61_reduz=c69_debito  
				     where c60_anousu = ".db_getsession("DB_anousu")." ) as debito_descr,

                        c69_credito,
              ( select c60_descr
                 from conplano 
			         inner join conplanoreduz on c61_codcon=c60_codcon
                                                                  c61_anousu=c60_anousu and
                                                                  c61_reduz=c69_credito
				     where c60_anousu = ".db_getsession("DB_anousu")." ) as credito_descr,
                        c69_valor,
                        case when c69_debito = c61_reduz then 
                              'D' 
                        else 'C' end  as tipo,                      
						c50_codhist,
						c50_descr,
						c74_codrec,
						c79_codsup,
						c75_numemp,
						e60_codemp,
						e60_anousu,
						c73_coddot,
						c76_numcgm,
						c78_chave,
						c72_complem  
                from conplanoreduz 
                     inner join conlancamval on ( c69_debito=c61_reduz or c69_credito = c61_reduz)
                     inner join conplano     on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu
                     inner join conhist          on c50_codhist = c69_codhist
                     left outer join conlancamdoc on c71_codlan  = c69_codlan 
                     left outer join conhistdoc   on c53_coddoc  = conlancamdoc.c71_coddoc 
                     left outer join conlancamrec on c74_codlan = c69_codlan 
                                                 and c74_anousu = c69_anousu
                     left outer join conlancamsup on c79_codlan = c69_codlan
				     left outer join conlancamemp on c75_codlan = c69_codlan
				     left outer join empempenho   on c75_numemp = e60_numemp
				     left outer join conlancamdot on c73_codlan = c69_codlan
		                                                 and c73_anousu = c69_anousu
				     left outer join conlancamcgm on c76_codlan = c69_codlan
				     left outer join conlancamdig on c78_codlan = c69_codlan
				     left outer join conlancamcompl on c72_codlan = c69_codlan
         where c61_anousu = ".db_getsession("DB_anousu")." and $txt_where
         order by c61_codcon,
	          c69_data
           	  ";

//----------------------------------------------------------------------------
$res=$clconlancam->sql_record($sql);
//  db_criatabela($res);exit;
if ($clconlancam->numrows > 0 ){
  $rows=$clconlancam->numrows; 
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta ! ');  
}

$head2 = "EXTRATO POR DOCUMENTOS";
$head5 = "PERÍODO : ".db_formatar($data1,'d')." à ".db_formatar($data2,'d');

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','',7);
$tam = '4';
$th='B';

$pdf->Cell(30,$tam,'DATA',$th,0,"C",0);
$pdf->cell(30,$tam,'DOCUMENTO',$th,0,"C",0);
$pdf->Cell(80,$tam,'DESCRIÇÃO',$th,0,"L",0);
$pdf->Cell(30,$tam,'VALOR',$th,0,"R",0);
$pdf->ln();

for ($x=0; $x < $rows;$x++){
   db_fieldsmemory($res,$x,true);
   $pdf->Cell(30,$tam,$c70_data,'0',0,"C",0);
   $pdf->cell(30,$tam,$c71_coddoc,'0',0,"C",0);
   $pdf->Cell(80,$tam,$c53_descr,'0',0,"L",0);
   $pdf->Cell(30,$tam,$c70_valor,'0',0,"R",0);
   $pdf->ln();


} // end for



//include("fpdf151/geraarquivo.php");
$pdf->output();

?>