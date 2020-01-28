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

$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('r01_admiss');
$clrotulo->label('r37_descr');
$clrotulo->label('z01_nome');
$clrotulo->label('r70_descr');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head5 = "PER�ODO : ".$mes." / ".$ano;

$where = "";

if($secretaria != '00'){
  $where = " and substr(r70_estrut,2,2) = '".$secretaria."' ";
}

if($quebrar == 'n'){
  $head3 = "RELAT�RIO DE FUNCIONARIOS";
  $ordem = ' z01_nome';
}else{
  $head3 = "RELAT�RIO DE FUNCION�RIOS POR SECRETARIA";
  $ordem = ' order by substr(r70_estrut,2,2),z01_nome ';
}

$sql = "
select rh02_regist as r01_regist,
       z01_nome,
       rh01_admiss as r01_admiss,
       rh37_descr as r37_descr,
       r70_descr,
case substr(r70_estrut,2,2)
            when '01' then 'GABINETE'                                
            when '02' then 'SECRETARIA DA ADMINISTRA��O'                                
            when '03' then 'SECRETARIA DA FAZENDA'                                
            when '04' then 'SECRETARIA DE PLANEJAMENTO URBANO'                                
            when '05' then 'SECRETARIA DE OBRAS'                                
            when '06' then 'SECRETARIA DE EDUCA��O E DESPORTO'                                
            when '07' then 'SECRETARIA DE SA�DE'                                
            when '08' then 'SECRETARIA DA ASSIST�NCIA SOCIAL'                                
            when '09' then 'SECRETARIA DE TRANSPORTES E TR�NSITO'                                
            when '10' then 'SECRETARIA DA IND�STRIA E COM�RCIO'
            when '11' then 'SECRETARIA DA AGRICULTURA'
            when '12' then 'SECRETARIA DA CULTURA E TURISMO'
        end as sec
from rhpessoalmov
   inner join rhpessoal    on rh01_regist = rh02_regist
   left join rhpesrescisao on rh05_seqpes = rh02_seqpes
   inner join rhlota       on rh02_lota   = r70_codigo 
	                        and r70_instit  = rh01_instit 
   inner join cgm          on rh01_numcgm = z01_numcgm
   inner join rhfuncao     on rh37_funcao = rh01_funcao
                          and rh37_instit = rh02_instit
where rh02_anousu = $ano
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null
  $where
$ordem 
";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem funcion�rios no per�odo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$func   = 0;
$func_c = 0;
$tot_c  = 0;
$total  = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$secret = '';

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ( $secret != $sec && $quebrar == 's' ){
      if($secret != ''){
        $pdf->ln(1);
        $pdf->cell(210,$alt,'Total da Secretaria  :  '.$func_c,"T",1,"L",0);
	$func_c = 0;
	$tot_c  = 0;
      }
      $troca = 1;
      $secret = $sec;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      if($quebrar == 's'){
         $pdf->setfont('arial','b',9);
         $pdf->cell(50,$alt,$sec,0,1,"L",0);
      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,$RLr01_regist,1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(15,$alt,$RLr01_admiss,1,0,"C",1);
      $pdf->cell(60,$alt,$RLr70_descr,1,0,"C",1);
      $pdf->cell(60,$alt,'CARGO',1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r01_regist,0,0,"C",0);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(15,$alt,db_formatar($r01_admiss,'d'),0,0,"C",0);
   $pdf->cell(60,$alt,$r70_descr,0,0,"L",0);
   $pdf->cell(60,$alt,$r37_descr,0,1,"L",0);
   $func_c += 1;
   $func   += 1;
}
if($quebrar == 's'){
  $pdf->ln(1);
  $pdf->cell(210,$alt,'Total da Secretaria  :  '.$func_c,"T",1,"L",0);
}
$pdf->ln(3);
$pdf->cell(210,$alt,'Total da Geral  :  '.$func,"T",0,"L",0);

$pdf->Output();
   
?>