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
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

/*
baixadas = s
datai    = 2006-05-01
dataf    = 2006-05-01
grupof   = 36
grupol   = 1
*/

$xordem   = "";
$where    = "";
$wheresq  = "";

if(isset($grupol) && $grupol != ""){
    $wheregrupol = " and cargrup.j32_grupo = $grupol "; 
}
if(isset($grupof) && $grupof != ""){
    $wheregrupof = " and cargrup.j32_grupo = $grupof "; 
}

$rslote = pg_query("select j32_descr as grlote from cargrup where j32_grupo = $grupol");
db_fieldsmemory($rslote,0);

$rsface = pg_query("select j32_descr as grface from cargrup where j32_grupo = $grupof");
db_fieldsmemory($rsface,0);

$rsrua = pg_query("select j14_nome as nomerua from ruas where j14_codigo = $rua");
db_fieldsmemory($rsrua,0);
$head5 = "";
//die($baixadas);
if($baixadas == 's'){
  $where  .= " and iptubase.j01_baixa is not null ";
  if(isset($datai) && $datai != "" && isset($dataf) && $dataf != ""  ){
      $where  .= " and iptubase.j01_baixa between '$datai' and '$dataf' ";
  }else if (isset($datai) && $datai != ""){
      $where  .= " and iptubase.j01_baixa >= '$datai' ";
  }else if (isset($dataf) && $dataf != ""){
      $where  .= " and iptubase.j01_baixa <= '$dataf' ";
  }
//die($where);
}else if($baixadas == 'n'){
    $where  .= " and iptubase.j01_baixa is null";
}
if(isset($setor) && $setor != ""){ 
    $wheresq .= " and face.j37_setor = '".$setor."'";  
    $head5 .= " Setor : $setor ";
}
if(isset($quadra) && $quadra != ""){  
    $wheresq .= " and face.j37_quadra = '".$quadra."'"; 
    $head5 .= " Quadra : $quadra";
}

$head1 = " COMPARATIVO ENTRE CARAC. DE LOTE E FACE ";
$head2 = " Grupo do lote: $grlote ";
$head3 = " Grupo da face: $grface "; 
$head4 = " Listando matrículas da rua : $rua - $nomerua ";


$sql = "
    select iptubase.j01_matric,
	       proprietario,
               (select carface.j38_caract || '-' || caracter.j31_descr
                from   testada
                inner join carface  on carface.j38_face    = testada.j36_face
                inner join caracter on caracter.j31_codigo = carface.j38_caract
                inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo
                where  testada.j36_idbql = iptubase.j01_idbql
                and    cargrup.j32_tipo = 'F'
				and    testada.j36_codigo = $rua
		        $wheregrupof 
                limit 1) as j38_caract,
               (select carlote.j35_caract || '-' || caracter.j31_descr
                from   carlote
                inner join caracter on caracter.j31_codigo = carlote.j35_caract
                inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo
                where  carlote.j35_idbql = iptubase.j01_idbql
                and    cargrup.j32_tipo = 'L'
		        $wheregrupol 
                limit 1) as j35_caract
        from iptubase
	            inner join proprietario_nome on iptubase.j01_matric = proprietario_nome.j01_matric	
  	            inner join testada           on testada.j36_idbql   = proprietario_nome.j01_idbql
       	        inner join face              on face.j37_face       = testada.j36_face
	  where testada.j36_codigo = $rua 
	        $wheresq						  
	        $where	
			";
//e($sql);
$result = pg_query($sql);
//db_criatabela($result);exit; 

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros para o filtro selecionado.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$w = $pdf->w-10;
$b = 0;
$matricaux = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->SetFont('Arial','B',8);
      $pdf->SetFillColor(210);
      $pdf->cell(10,$alt,"Matr",1,0,"C",1);
      $pdf->cell(60,$alt,"Proprietário",1,0,"C",1);
      $pdf->cell(60,$alt,"Caracteristicas da Face ",1,0,"C",1);
      $pdf->cell(60,$alt,"Caracteristicas do Lote ",1,1,"C",1);
      $pdf->cell($w,1,"",0,1,"R",0);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if($x % 2 == 0){
	    $corfundo = 233;
   }else{
	    $corfundo = 245;
   }
   $pdf->SetFillColor($corfundo);
   $pdf->cell(10,$alt,$j01_matric,$b,0,"C",1);
   $pdf->cell(60,$alt,$proprietario,$b,0,"L",1);
   $pdf->cell(60,$alt,$j38_caract,$b,0,"L",1);
   $pdf->cell(60,$alt,$j35_caract,$b,1,"L",1);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"R",0);
$pdf->output();
/*

$sql ="
select * from (
		select iptubase.j01_matric,
		       proprietario,
			   carface.j38_caract as caraface,
			   0                  as caralote,    
			   ''                 as grupolote,
			   cargrup.j32_tipo   as grupoface,
			   caracter.j31_descr,
			   cargrup.j32_grupo as codgrupoface,
			   0                 as codgrupolote,
			   cargrup.j32_descr as descrgrupoface,
			   ''                as descrgrupolote
		from iptubase 
		   inner join proprietario_nome  on proprietario_nome.j01_matric  = iptubase.j01_matric
		   inner join testpri            on testpri.j49_idbql  = iptubase.j01_idbql
		   inner join carface            on carface.j38_face   = testpri.j49_face
		   inner join caracter           on carface.j38_caract = caracter.j31_codigo
		   inner join cargrup            on cargrup.j32_grupo  = caracter.j31_grupo 
		where cargrup.j32_tipo = 'F'
		      $wheregrupof 
		      $where
		group by iptubase.j01_matric,
			     carface.j38_caract,
                 cargrup.j32_tipo,
			     caracter.j31_descr,
				 proprietario, 
			     cargrup.j32_grupo,
			     cargrup.j32_descr
	union
		select iptubase.j01_matric,
		       proprietario,
		       0                  as caraface,		
			   carlote.j35_caract as caralote,
			   cargrup.j32_tipo   as grupolote,
			   ''                 as grupoface,
			   caracter.j31_descr,
			   0                 as codgrupoface,
			   cargrup.j32_grupo as codgrupolote,
			   ''                as descrgrupoface,
			   cargrup.j32_descr as descrgrupolote
		from iptubase 
		   inner join proprietario_nome  on proprietario_nome.j01_matric  = iptubase.j01_matric
		   inner join carlote            on carlote.j35_idbql  = iptubase.j01_idbql
		   inner join caracter           on carlote.j35_caract = caracter.j31_codigo
		   inner join cargrup            on cargrup.j32_grupo  = caracter.j31_grupo 
		where cargrup.j32_tipo = 'L'		      
		      $wheregrupol 
		      $where
		group by iptubase.j01_matric,
		         carlote.j35_caract,
                 cargrup.j32_tipo,
                 caracter.j31_descr,
				 proprietario,
			     cargrup.j32_grupo,
			     cargrup.j32_descr
) as zzz 
limit 90 ";


*/

?>