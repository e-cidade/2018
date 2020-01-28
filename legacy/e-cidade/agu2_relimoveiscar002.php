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
 

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('x01_matric');
$clrotulo->label('z01_nome');
$clrotulo->label('j14_nome');
$clrotulo->label('x01_numero');
$clrotulo->label('j31_descr');


$oGet = db_utils::postMemory($_GET);

$sSql = "
         select x01_matric, 
                cgm1.z01_nome, 
                cgm2.z01_nome as x01_promit, 
                j14_nome, 
                x01_numero, 
                j31_descr   
           from aguabase 
          inner join cgm as cgm1   on cgm1.z01_numcgm = x01_numcgm 
           left join cgm as cgm2   on cgm2.z01_numcgm = x01_promit 
           left join aguabasebaixa on x08_matric      = x01_matric 
          inner join aguaconstr    on x11_matric      = x01_matric 
          inner join aguaconstrcar on x12_codconstr   = x11_codconstr 
          inner join caracter      on j31_codigo      = x12_codigo          
          inner join ruas          on j14_codigo      = x01_codrua
          where 
                x12_codigo in ($oGet->lista) 
                
       order by j31_descr, cgm1.z01_nome, x01_promit";


$result = pg_exec($sSql);

if(pg_numrows($result) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  exit;
}

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNBPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',9);
$oPdf->DefOrientation = "L";

$head1 = "Relatório de Imóveis por Caracteristica: ";

$sSql2   = "select j31_descr from caracter where j31_codigo in ($oGet->lista)";
$result2 = pg_exec($sSql2);

$head2 = "";
for($h = 0; $h < pg_numrows($result2); $h++) {
  if($h > 7) {
    continue;
  }
  
  $objResult2 = db_utils::fieldsMemory($result2, $h);
  
  $head2 .= $objResult2->j31_descr."\n";
  
  
}

$total = 0;
$troca = 1;
$alt   = 4;
$total = 0;

for($i = 0; $i < pg_numrows($result); $i++) {
  $objResult = db_utils::fieldsMemory($result, $i);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20, $alt, $RLx01_matric , 1, 0, "C", 1);
      $oPdf->cell(45, $alt, $RLj31_descr  , 1, 0, "C", 1);
      $oPdf->cell(65, $alt, 'Proprietário', 1, 0, "C", 1); 
      $oPdf->cell(65, $alt, 'Promitente'  , 1, 0, "C", 1);
      $oPdf->cell(68, $alt, $RLj14_nome   , 1, 0, "C", 1); 
      $oPdf->cell(15, $alt, $RLx01_numero , 1, 1, "C", 1);
      $troca = 0;
      $p=0;
   }
   $oPdf->setfont('arial','',7);
   $oPdf->cell(20, $alt, $objResult->x01_matric, 0, 0, "C", $p);
   $oPdf->cell(45, $alt, substr($objResult->j31_descr, 0, 24) , 0, 0, "C", $p);
   $oPdf->cell(65, $alt, $objResult->z01_nome  , 0, 0, "L", $p);
   $oPdf->cell(65, $alt, $objResult->x01_promit, 0, 0, "L", $p);
   $oPdf->cell(68, $alt, $objResult->j14_nome  , 0, 0, "L", $p);
   $oPdf->cell(15, $alt, $objResult->x01_numero, 0, 1, "L", $p);
   if($p == 0) 
     $p = 1;
   else 
     $p = 0;
   
   $total++;
      
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$oPdf->Output();


?>