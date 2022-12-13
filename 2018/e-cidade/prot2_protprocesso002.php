<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_sql.php");
include("classes/db_protprocesso_classe.php");
$clrotulo = new rotulocampo;
$clprotprocesso= new cl_protprocesso;
$clrotulo->label('p61_coddepto'); //metodo que pega o label do campo da tabela indicado
$clrotulo->label('descrdepto');
$clrotulo->label('p58_codproc');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$head3 = "RELATÓRIO DE PROCESSOS POR DEPARTAMENTO";
$periodo ='';
$and="and";
if($datai!='--' || $dataf!='--'){
   $periodo = "p58_dtproc between '$datai' and '$dataf'";
}elseif($datai='--' || $dataf!='--'){
   $periodo = "p58_dtproc => '$datai' ";   
}elseif($datai!='--' || $dataf='--'){
   $periodo = "p58_dtproc =< '$dataf' ";
}elseif($datai=='--' || $dataf=='--'){
   $periodo = "";
   $and = "";
}
$orderby = "descrdepto";
$head5 = "ORDENADO POR DEPARTAMENTO "; 
$periodo .= " $and p68_codproc is null ";
$periodo .= " and instit in (select id_instit from db_userinst where id_usuario =  ".db_getsession("DB_id_usuario").") ";
/*
select extract(year from p58_dtproc), p61_coddepto, descrdepto, count(p58_codproc) from protprocesso
inner join cgm on cgm.z01_numcgm = protprocesso.p58_numcgm 
inner join db_usuarios on db_usuarios.id_usuario = protprocesso.p58_id_usuario 
inner join tipoproc on tipoproc.p51_codigo = protprocesso.p58_codigo 
inner join procandam on procandam.p61_codandam = protprocesso.p58_codandam 
inner join db_depart on db_depart.coddepto = procandam.p61_coddepto 
left join arqproc on p68_codproc = p58_codproc 
where p58_dtproc between '2000-01-01' and '2005-01-01' 
and p68_codproc is null
group by p61_coddepto,descrdepto 
order by extract(year from p58_dtproc)
*/
//die($clprotprocesso->sql_query_deptand(null,"extract(year from p58_dtproc), p61_coddepto,(select nomeinstabrev from db_config where codigo = instit) as nomeabrev, descrdepto, count(p58_codproc)",$orderby,$periodo."group by extract(year from p58_dtproc), p61_coddepto,descrdepto,nomeabrev"));
$result = $clprotprocesso->sql_record($clprotprocesso->sql_query_deptand(null,"extract(year from p58_dtproc), p61_coddepto,(select nomeinstabrev from db_config where codigo = instit) as nomeabrev, descrdepto, count(p58_codproc)",$orderby,$periodo."group by extract(year from p58_dtproc), p61_coddepto,descrdepto,nomeabrev"));
//db_criatabela($result);exit;
$xxnum = $clprotprocesso->numrows;
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem unidades cadastrados.');
}
//db_criatabela($result);exit;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$imprimecod='';
$imprimedepto='';
$subtotal=0;
$totalproc=0;
$totaldepart=0;
for($x = 0; $x < $xxnum;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Cód. Dep. ",1,0,"C",1);
      $pdf->cell(60,$alt,"Departamento Origem ",1,0,"C",1);
      $pdf->cell(55,$alt,"Instituição ",1,0,"C",1);
      $pdf->cell(15,$alt,"Ano ",1,0,"C",1);
      $pdf->cell(20,$alt,"Processos ",1,0,"C",1);
      $pdf->cell(25,$alt,"Subtotal ",1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if($imprimecod != $p61_coddepto){
     if ($x != 0){
       $pdf->setfont('arial','b',8);
       $pdf->cell(25,$alt,$subtotal,"0",1,"C",0);
       $pdf->setfont('arial','',7);
     }
     $pdf->cell(190,0,"","T",1,"C",0);
     $pdf->cell(15,$alt,$p61_coddepto,0,0,"C",0);
     $pdf->cell(60,$alt,$descrdepto,0,0,"L",0);
     $pdf->cell(55,$alt,$nomeabrev,0,0,"C",0);
     $imprimecod = $p61_coddepto;
     $totaldepart ++;
     $totalproc = $totalproc+$subtotal;
     $subtotal = 0;
   }
   else{
       $pdf->cell(15,$alt,"",0,0,"C",0);              
       $pdf->cell(60,$alt,"",0,0,"C",0);         
   }
   if($x < ($xxnum -1)){
     $p61_coddeptoprox = pg_result($result,$x+1,"p61_coddepto");
   } else {
     $p61_coddeptoprox = 0;
   }
   $pdf->cell(15,$alt,$date_part,0,0,"C",0);
   $pdf->cell(20,$alt,$count,0,($p61_coddeptoprox == $p61_coddepto?1:0),"C",0);
   $subtotal = $subtotal+$count;
  // $totalproc = $totalproc+$subtotal;
   $bordatabela = "0";
   $total ++;
   if($x == ($xxnum -1)){
     $pdf->setfont('arial','b',8);
     $pdf->cell(25,$alt,$subtotal,"B",1,"C",0);
     $pdf->setfont('arial','',7);
   }
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,'TOTAL DE DEPARTAMENTOS  :  '.$totaldepart,"T",1,"L",0);
$pdf->cell(190,$alt,'TOTAL DE PROCESSOS  :  '.$totalproc,"0",1,"L",0);
$pdf->cell(190,$alt,'TOTAL DE REGISTROS  :  '.$total,0,0,"L",0);
$pdf->Output();
?>