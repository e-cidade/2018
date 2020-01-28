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

include("fpdf151/pdf2.php");
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
//db_postmemory($HTTP_POST_VARS,2);exit;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

$xwhere = '';
if(trim($matric != '')){
  $xwhere = " and r30_regist = $matric ";
}

$sql = "
select cadferia.*,
      case when r30_per2i is null 
                       then r30_per1i - 30
		  else r30_per2i - 30 end as data, 
      case when r30_per2i is null 
                       then r30_per1i
		  else r30_per2i end as gozoi, 
      case when r30_per2f is null 
                       then r30_per1f
		  else r30_per2f end as gozof, 
		  z01_nome, 
      rh37_descr,
      r70_estrut,
      r70_descr,
      rh01_admiss
from cadferia 
     inner join rhpessoalmov on rh02_regist = r30_regist
                            and rh02_anousu = r30_anousu
            		            and rh02_mesusu = r30_mesusu
										        and rh02_instit = ".db_getsession("DB_instit")." 
     inner join rhpessoal    on rh01_regist = r30_regist
     inner join cgm     on rh01_numcgm = z01_numcgm
     inner join rhfuncao  on rh37_funcao = rh01_funcao
                       and rh37_instit = rh02_instit
     inner join rhlota   on r70_codigo = rh02_lota
		                    and r70_instit = rh02_instit 
     inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc) as rhlotavinc on rh25_codigo = r70_codigo 
where r30_anousu = $ano
  and r30_mesusu = $mes
  $xwhere
  and (r30_proc1 = '$ano/$mes' or r30_proc2 = '$ano/$mes')
order by r70_estrut, z01_nome ;
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result); exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem dados no per�odo de '.$mes.' / '.$ano);

}

$pdf = new PDF2(); 
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
$head1 = 'DEPARTAMENTO DE RECURSOS HUMANOS';


for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $parag1 = ' Pelo presente comunicamos-lhe que, de acordo com a lei, ser-lhe-�o concedidas f�rias relativas ao per�odo aquisitivo de '.substr($r30_perai,8,2).' de '.db_mes(substr($r30_perai,5,2)).' de '.substr($r30_perai,0,4).' � '.substr($r30_peraf,8,2).' de '.db_mes(substr($r30_peraf,5,2)).' de '.substr($r30_peraf,0,4).' a serem gozadas a partir de '.substr($gozoi,8,2).' de '.db_mes(substr($gozoi,5,2)).' de '.substr($gozoi,0,4).' � '.substr($gozof,8,2).' de '.db_mes(substr($gozof,5,2)).' de '.substr($gozof,0,4).'.';




      $parag2 = $z01_nome.', abaixo assinado, servidor desta Prefeitura Municipal, exercendo o cargo de '.$r37_descr.' vem mui respeitosamente, requerer a V. Sa., as f�rias referentes ao per�odo de '.substr($r30_perai,8,2).' de '.db_mes(substr($r30_perai,5,2)).' de '.substr($r30_perai,0,4).' � '.substr($r30_peraf,8,2).' de '.db_mes(substr($r30_peraf,5,2)).' de '.substr($r30_peraf,0,4).' a serem gozadas a partir de '.substr($gozoi,8,2).' de '.db_mes(substr($gozoi,5,2)).' de '.substr($gozoi,0,4).' � '.substr($gozof,8,2).' de '.db_mes(substr($gozof,5,2)).' de '.substr($gozof,0,4).'.';
      $pdf->setfont('arial','',11);
      $pdf->ln(10);
      $pdf->cell(20,5,'Servidor ',0,0,"L",0);
      $pdf->cell(120,5,": $z01_nome",0,0,"L",0);
      $pdf->cell(20,5,'Matr�cula',0,0,"L",0);
      $pdf->cell(60,5,": $r30_regist",0,1,"L",0);
      $pdf->cell(20,5,'Cargo',0,0,"L",0);
      $pdf->cell(120,5,": $rh37_descr",0,0,"L",0);
      $pdf->cell(20,5,'Admiss�o',0,0,"L",0);
      $pdf->cell(60,5,": ".db_formatar($rh01_admiss,'d'),0,1,"L",0);
      $pdf->cell(20,5,'Lota��o ',0,0,"L",0);
      $pdf->cell(120,5,": $r70_estrut - $r70_descr",0,1,"L",0);
      $pdf->ln(30);
      
      $pdf->multicell(0,5,$parag1,0,"J",0,25);
      $pdf->ln(20);
      $pdf->setx(35);
      $pdf->cell(100,5,strtoupper($munic).'-'.strtoupper($uf).', '.date('d').' de '.db_mes(date('m')).' de '.date('Y').'.',0,1,"L",0);

      $pdf->ln(15);
      $pdf->setx(90);
      $pdf->multicell(0,5,str_repeat('_', 50),0,1,"C",0);
      $pdf->setx(90);
      $pdf->multicell(0,5,'PREFEITURA MUNICIPAL DE CANELA',0,1,"C",0);
      $pdf->ln(20);
      $pdf->setx(90);
      $pdf->multicell(0,5,'Ciente em : _____/ _____/ _______',0,1,"C",0);
      $pdf->ln(15);
      $pdf->setx(90);
      $pdf->multicell(0,5,str_repeat('_', 50),0,1,"C",0);
      $pdf->setx(90);
      $pdf->multicell(0,5,$z01_nome,0,1,"C",0);
      
      
   }
}
$pdf->Output();
   
?>