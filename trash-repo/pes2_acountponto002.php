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

if($ponto == 's'){
  $arquivo = '579'; 
  $head7   = 'PONTO DE SALARIO';
  $dcodcam1 = '4318';  /// r10_rubric
  $kcodcam1 = '4317';  /// r10_regist
  $dcodcamnot = '4315, 7466, 4321, 4316, 4317, 4318';
}elseif($ponto == 'f'){
  $arquivo = '580'; 
  $head7   = 'PONTO FIXO'; 
  $dcodcam1 = '4326';  /// r90_rubric
  $kcodcam1 = '4325';  /// r90_regist
  $dcodcamnot = '4323, 4324, 4325, 7467, 4329, 4326';
}

$where_usuarios = '';
if(trim($colunas != '')){
  $where_usuarios = " and d.id_usuario in ($colunas)";
}

$where_func = '';
if(isset($mat)){
  $where_func = " and case when trim(campotext) = '' then '0' else campotext end::int = $mat";
}

$where_rub = '';
if(isset($rub)){
  $where_rub = " and trim(contatu) = '$rub'";
}
//$dataini = '2009-05-01';
//$datafin = '2009-05-19';



$head3 = "RELATÓRIO DAS ALTERAÇÕES DA FOLHA";
$head5 = "PERIODO : ".db_formatar($dataini,'d').' a '.db_formatar($datafin,'d');

pg_query("create temporary table 
          ww_acount(
                    regist int,
                    nomefunc char(40),
                    rubrica char(4),
                    tipo char(1),
                    campoalt char(20),
                    data date,
                    hora char(5),
                    anterior char(20),
                    atual char(20),
                    usuario char(40)
                   )");


$sql1 =
"
 select distinct
        actipo,
        d.id_acount,
        campotext as matricula,
        z01_nome,
        c.rotulo,
        translate(translate(contatu,'round\(',''),'\ ,\2\)','') as rubrica
from db_acount d
     inner join db_acountkey k    on k.id_acount = d.id_acount
     inner join db_syscampo c     on c.codcam = d.codcam
     inner join db_usuarios u     on u.id_usuario = d.id_usuario
     inner join db_syscampo campo on campo.codcam = id_codcam
     inner join rhpessoal         on case when trim(campotext) = ''
                                          then '0'
                                          else campotext::int
                                     end = rh01_regist
     inner join cgm               on rh01_numcgm = z01_numcgm
where d.codarq in ($arquivo)
  $where_rub
  $where_func
  and d.datahr between ".mktime(0,0,0,substr($dataini,5,2), substr($dataini,8,2),substr($dataini,0,4))." 
                   and ".mktime(23,59,59,substr($datafin,5,2), substr($datafin,8,2),substr($datafin,0,4))." 
  and d.codcam     = $dcodcam1 
  and k.id_codcam  = $kcodcam1
  $where_usuarios
order by d.id_acount;
";

/// d.codcam    = 4318    = r10_rubric
/// k.id_codcam = 4317    = r10_regist

//echo $sql1 ; exit;

$result1 = pg_exec($sql1);
//db_criatabela($result1);exit;
$xxnum1  = pg_numrows($result1);

for($xx = 0; $xx < pg_numrows($result1);$xx++){
   db_fieldsmemory($result1,$xx);
   $sql2= "
   select distinct
          actipo,
          c.rotulo ,
          z01_nome,
          trim(campo.nomecam) as nomecam,
          campotext,
          d.*,
          translate(
                      translate(contatu,'round\(','') ,'\,\2\)' 
                      ,''
                   ) as atual1,
          contatu as atual,
          c.nomecam,
          u.nome
   from db_acount d
        inner join db_acountkey k on k.id_acount = d.id_acount
        inner join db_syscampo c  on c.codcam = d.codcam
        inner join db_usuarios u  on u.id_usuario = d.id_usuario
        inner join db_syscampo campo on campo.codcam       = id_codcam
        inner join rhpessoal      on campotext::int = rh01_regist
        inner join cgm            on rh01_numcgm = z01_numcgm
   where d.id_acount = $id_acount          
         and k.id_codcam = $kcodcam1
         and d.codcam not in ($dcodcamnot)
         and trim(contant) <> trim(contatu) 
       ";
   $result2 = pg_exec($sql2);
//   db_criatabela($result2);
   $xxnum2  = pg_numrows($result2);

   for($y = 0; $y < pg_numrows($result2);$y++){
      db_fieldsmemory($result2,$y);
      // echo "<br>   contant --> $contant   atual --> $atual     ";
      if(trim( addslashes($contant) ) ==  str_replace(",2)",'', str_replace("round(",'',addslashes($atual) ) )       ){
        continue;
      } 
 
      $sql_ins2 = "insert into ww_acount values
                     (
                      $campotext,
                      '$z01_nome',
                      '$rubrica',
                      '$actipo',
                      substr('$rotulo',1,20),
                      '".date("Y-m-d",$datahr)."',
                      '".date("H:i",$datahr)."',
                      substr('".addslashes($contant)."',1,20),
                      substr('".addslashes($atual)."',1,20),
                      substr('$nome',1,40)
                     )";
      $res_ins2 = pg_query($sql_ins2);
      if($res_ins2 == false){
        echo "Erro ao inserir na tabela temporária : $sql_ins2 ";exit;
      }
   }
}


$xordem = ' order by ';
if($ordem == 'a'){
  $xordem .= ' nomefunc'; 
}elseif($ordem == 'n'){
  $xordem .= ' regist'; 
}elseif($ordem == 'ra'){
  $xordem .= ' rubrica, nomefunc'; 
}elseif($ordem == 'rn'){
  $xordem .= ' rubrica, regist'; 
}elseif($ordem == 'rh'){
  $xordem .= ' rubrica, data, hora'; 
}else{
  $xordem .= ' data, hora'; 
}

$xtipo = '';
if($tipo_alt == 'a'){
  $xtipo = " where tipo = 'A'"; 
}elseif($tipo_alt == 'e'){
  $xtipo = " where tipo = 'E'"; 
}elseif($tipo_alt == 'i'){
  $xtipo = " where tipo = 'I'"; 
}

$sql_temp = "select * from ww_acount $xtipo $xordem";
//echo $sql_temp;
$res_temp = pg_query($sql_temp);
$xxnum = pg_numrows($res_temp);
//db_criatabela($res_temp);exit;

if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$troca_func = '';
for($x = 0; $x < $xxnum;$x++){
   db_fieldsmemory($res_temp,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'Rubrica',1,0,"C",1);
      $pdf->cell(15,$alt,'Matricula',1,0,"C",1);
      $pdf->cell(70,$alt,'Nome do Funcionário',1,1,"C",1);
      $pdf->cell(15,$alt,'Tipo',1,0,"C",1);
      $pdf->cell(30,$alt,'Campo',1,0,"C",1);
      $pdf->cell(40,$alt,'Conteudo Anterior',1,0,"C",1);
      $pdf->cell(40,$alt,'Conteudo Atual',1,0,"C",1);
      $pdf->cell(25,$alt,'Alteração',1,0,"C",1);
      $pdf->cell(70,$alt,'Usuário',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   if($troca_func != $rubrica.$nomefunc){
     $pdf->setfont('arial','B',8);
     $pdf->cell(15,$alt,$rubrica,0,0,"C",$pre);
     $pdf->cell(15,$alt,$regist,0,0,"C",$pre);
     $pdf->cell(70,$alt,$nomefunc,0,1,"L",$pre);
     $troca_func = $rubrica.$nomefunc;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$tipo,0,0,"L",$pre);
   $pdf->cell(30,$alt,$campoalt,0,0,"L",$pre);
   $pdf->cell(40,$alt,$anterior,0,0,"L",$pre);
   $pdf->cell(40,$alt,str_replace(",2)",'', str_replace("round(",'',$atual)),0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($data,'d').' - '.$hora,0,0,"L",$pre);
   $pdf->cell(70,$alt,$usuario,0,1,"L",$pre);
   $total += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();

?>