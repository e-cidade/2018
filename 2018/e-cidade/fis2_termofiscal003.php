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
include("classes/db_parfiscal_classe.php");
include("classes/db_termovist_classe.php");
include("dbforms/db_funcoes.php");
//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);
$clparfiscal     = new cl_parfiscal;
$cltermovist     = new cl_termovist;
//----------------------- Data atual ----------------------------
 $dia = date ('d',db_getsession("DB_datausu"));
 $mes = date ('m',db_getsession("DB_datausu"));
 $ano = date ('Y',db_getsession("DB_datausu"));
 $mes = db_mes($mes);
//----------------------------------------------------------------

//Monta SQL
$int_seq = 0;
$and ="";
$where = " where ";
$sqlerro=false;
$termovist="";
$tipoori="";
$inscrnaoger="";
$vir = "";  
$descr = "";

if(isset($inscricao) && $inscricao != ""){
    $where .= " $and issbase.q02_inscr = $inscricao ";
    $and = " and ";
}
if((isset($logradouro) && $logradouro != "")){
    $where .= " $and issruas.j14_codigo = $logradouro ";
    $and = " and ";
    $tipoori = "r";
}

if(isset($inscricao) && $inscricao != ""){
   $tipoori = "i";
}

if(isset($classes) && $classes != ""){
    if(isset($tipo) && $tipo == "c"){
        $in = " in ";
    }else{
        $in = " not in ";
    }
    $where .= " $and q82_classe $in ($classes) ";
    $and = " and ";
}
$where .= " $and q02_dtbaix is null and y91_exerc = '".db_getsession("DB_anousu")."' 
            group by classe.q12_descr, 
	             classe.q12_classe,
	             cgm.z01_nome, 
		     termovist.y91_datatermo, 
		     termovist.y91_inscr, 
		     termovist.y91_termovist 
            order by classe.q12_descr, 
	             cgm.z01_nome
		     ";

$str_sql =
"
select classe.q12_descr,
       classe.q12_classe,
       termovist.y91_termovist,
       termovist.y91_inscr,
       cgm.z01_nome,
       termovist.y91_datatermo
from issbase
      inner join termovist on termovist.y91_inscr = issbase.q02_inscr
      left join issruas    on issruas.q02_inscr   = issbase.q02_inscr
      left join ruas       on ruas.j14_codigo     = issruas.j14_codigo
      left join cgm        on cgm.z01_numcgm      = issbase.q02_numcgm
      left join ativprinc  on q88_inscr           = issbase.q02_inscr
      left join tabativ    on q07_inscr           = issbase.q02_inscr
                          and q07_seq             = q88_seq
      left join ativid     on q03_ativ            = tabativ.q07_ativ
      left join clasativ   on q82_ativ            = tabativ.q07_ativ
      left join classe     on q12_classe          = clasativ.q82_classe
      $where 
";
//echo($str_sql."<br><br>");exit;
$head2 = "Termos gerados anteriormente";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$result = pg_query($str_sql);// or die ( "FALHA: $str_sql");
$int_linhas = pg_num_rows($result);
if($int_linhas == 0){
//  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro para o filtro selecionado!');

  echo "<script>
                alert ('Nenhum registro com termo de vistoria ja lançado para o filtro selecionado!');
                window.close;
        </script>";
  exit;
}
for($cont=0;$cont<$int_linhas;$cont++){
    db_fieldsmemory($result,$cont);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
       $pdf->setfillcolor(215);
       $pdf->addpage();
       $pdf->setfont('arial','b',9);
       $pdf->cell(30,$alt,"Termo",1,0,"C",1);
       $pdf->cell(40,$alt,"Inscrição",1,0,"C",1);
       $pdf->cell(90,$alt,"Nome/Razão Social",1,0,"C",1);
       $pdf->cell(30,$alt,"Data do termo",1,1,"C",1);
       $pdf->cell(30,2,"",0,1,"R",0);
       $troca = 0;
   }
    if($cont % 2 == 0){
       $corfundo = 236;
    }else{
       $corfundo = 245;	
    }
   $pdf->setfillcolor($corfundo);
   $pdf->setfont('arial','',7);
   if($descr != $q12_descr){
       $pdf->setfont('arial','B',8);
       $pdf->cell(190,$alt,$q12_classe." - ".$q12_descr,"B",1,"L",0);
       $pdf->cell(190,1,"",0,1,"L",0);
       $pdf->setfont('arial','',7);
       $descr = $q12_descr;
   }
   $pdf->cell(30,$alt,$y91_termovist,0,0,"C",1);
   $pdf->cell(40,$alt,$y91_inscr,0,0,"C",1);
   $pdf->cell(90,$alt,$z01_nome,0,0,"L",1);
   $pdf->cell(30,$alt,db_formatar($y91_datatermo,'d'),0,1,"C",1);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>