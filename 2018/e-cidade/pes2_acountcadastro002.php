<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once modification("fpdf151/pdf.php");
require_once modification("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

db_postmemory($_REQUEST);

if ( !isset($datai) || empty($datai)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Data Inicial deve ser informada."));
}

if ( !isset($dataf) || empty($dataf)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Data Final deve ser informada."));
}

$where_usuarios = '';
if(trim($colunas != '')){
  $where_usuarios = " and d.id_usuario in ($colunas)";
}

$head3   = "RELATÓRIO DAS ALTERAÇÕES CADASTRAIS DA FOLHA";
$head5   = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');

$rsQuery = db_query("create temporary table
          ww_acount(
                    regist int,
                    nomefunc char(40),
                    tipo char(1),
                    campoalt char(20),
                    data date,
                    hora char(5),
                    anterior char(20),
                    atual char(20),
                    usuario char(40)
                   )");
if (!$rsQuery) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao criar estrutura para o relatório."));
}

$sDataInicial = mktime( 00, 00, 00, substr($dataini,5,2), substr($dataini,8,2), substr($dataini,0,4) );
$sDataFinal   = mktime( 23, 59, 59, substr($datafin,5,2), substr($datafin,8,2), substr($datafin,0,4) );

$sql1         =" select distinct                                                           ".PHP_EOL;
$sql1        .="        actipo,                                                            ".PHP_EOL;
$sql1        .="        c.rotulo,                                                          ".PHP_EOL;
$sql1        .="        z01_nome,                                                          ".PHP_EOL;
$sql1        .="        trim(campo.nomecam) as nomecam,                                    ".PHP_EOL;
$sql1        .="        campotext,                                                         ".PHP_EOL;
$sql1        .="        d.*,                                                               ".PHP_EOL;
$sql1        .="        c.nomecam,                                                         ".PHP_EOL;
$sql1        .="        u.nome                                                             ".PHP_EOL;
$sql1        .="   from db_acount d                                                        ".PHP_EOL;
$sql1        .="        inner join db_acountkey k    on k.id_acount    = d.id_acount       ".PHP_EOL;
$sql1        .="        inner join db_syscampo  c    on c.codcam       = d.codcam          ".PHP_EOL;
$sql1        .="        inner join db_usuarios  u    on u.id_usuario   = d.id_usuario      ".PHP_EOL;
$sql1        .="        inner join db_syscampo campo on campo.codcam   = id_codcam         ".PHP_EOL;
$sql1        .="        inner join rhpessoal         on campotext      = rh01_regist::text ".PHP_EOL;
$sql1        .="        inner join cgm               on rh01_numcgm    = z01_numcgm        ".PHP_EOL;
$sql1        .="  where d.codarq in (1153, 1168)                                           ".PHP_EOL;
$sql1        .="    $where_usuarios                                                        ".PHP_EOL;
$sql1        .="    and d.datahr between {$sDataInicial} and {$sDataFinal}                 ".PHP_EOL;
$sql1        .="    and trim(contant) <> trim(contatu)                                     ".PHP_EOL;
$sql1        .=" order by id_acount                                                        ".PHP_EOL;

$result1 = db_query($sql1);

if (!$result1) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao buscar os dados do account."));
}

$xxnum1  = pg_num_rows($result1);

for($xx = 0; $xx < pg_num_rows($result1);$xx++) {

  db_fieldsmemory($result1,$xx);

  $sql_ins1 = "insert into ww_acount values
                  (
                   $campotext,
                   '$z01_nome',
                   '$actipo',
                   substr('$rotulo',1,20),
                   '".date("Y-m-d",$datahr)."',
                   '".date("H:i",$datahr)."',
                   substr('".addslashes($contant)."',1,20),
                   substr('".addslashes($contatu)."',1,20),
                   substr('$nome',1,40)
                  )";
  $res_ins1 = db_query($sql_ins1);
  if (!$res_ins1) {
    db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao criar estrutura para o relatório."));
  }
}

$sDataInicial = mktime( 00, 00, 00, substr($dataini,5,2), substr($dataini,8,2), substr($dataini,0,4) );
$sDataFinal   = mktime( 23, 59, 59, substr($datafin,5,2), substr($datafin,8,2), substr($datafin,0,4) );

$sql2         = " select distinct                                                             ".PHP_EOL;
$sql2        .= "        actipo,                                                              ".PHP_EOL;
$sql2        .= "        c.rotulo ,                                                           ".PHP_EOL;
$sql2        .= "        z01_nome,                                                            ".PHP_EOL;
$sql2        .= "        trim(campo.nomecam) as nomecam,                                      ".PHP_EOL;
$sql2        .= "        rh01_regist as campotext,                                            ".PHP_EOL;
$sql2        .= "        d.*,                                                                 ".PHP_EOL;
$sql2        .= "        c.nomecam,                                                           ".PHP_EOL;
$sql2        .= "        u.nome                                                               ".PHP_EOL;
$sql2        .= " from db_acount d                                                            ".PHP_EOL;
$sql2        .= "      inner join db_acountkey k     on k.id_acount      = d.id_acount        ".PHP_EOL;
$sql2        .= "      inner join db_syscampo  c     on c.codcam         = d.codcam           ".PHP_EOL;
$sql2        .= "      inner join db_usuarios  u     on u.id_usuario     = d.id_usuario       ".PHP_EOL;
$sql2        .= "      inner join db_syscampo  campo on campo.codcam     = id_codcam          ".PHP_EOL;
$sql2        .= "      inner join rhpessoalmov       on campotext        = rh02_seqpes::text  ".PHP_EOL;
$sql2        .= "      inner join rhpessoal          on rh02_regist      = rh01_regist        ".PHP_EOL;
$sql2        .= "      inner join cgm                on rh01_numcgm      = z01_numcgm         ".PHP_EOL;
$sql2        .= " where d.codarq in (1158, 1238, 1161)                                        ".PHP_EOL;
$sql2        .= "   $where_usuarios                                                           ".PHP_EOL;
$sql2        .= "   and k.id_codcam <> 9913                                                   ".PHP_EOL;
$sql2        .= "   and d.datahr between {$sDataInicial} and {$sDataFinal}                    ".PHP_EOL;
$sql2        .= "   and  trim(contant) <> trim(contatu)                                       ".PHP_EOL;
$sql2        .= " order by id_acount                                                          ".PHP_EOL;

$result2 = db_query($sql2);
if (!$result2) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao buscar os dados do account."));
}
$xxnum2 = pg_num_rows($result2);

for($xx = 0; $xx < pg_num_rows($result2);$xx++){
   db_fieldsmemory($result2,$xx);
   $sql_ins2 = "insert into ww_acount values
                  (
                   $campotext,
                   '$z01_nome',
                   '$actipo',
                   substr('$rotulo',1,20),
                   '".date("Y-m-d",$datahr)."',
                   '".date("H:i",$datahr)."',
                   substr('".addslashes($contant)."',1,20),
                   substr('".addslashes($contatu)."',1,20),
                   substr('$nome',1,40)
                  )";
   $res_ins2 = db_query($sql_ins2);
   if(!$res_ins2){
     db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao criar estrutura para o relatório."));
   }

}

$xordem = ' order by ';
if($ordem == 'a'){
  $xordem .= ' nomefunc';
}elseif($ordem == 'n'){
  $xordem .= ' regist';
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
$res_temp = db_query($sql_temp);

if (!$res_temp) {
  db_redireciona("db_erros.php?fechar=true&db_erro=".urlencode("Erro ao criar estrutura para o relatório."));
}

$xxnum = pg_num_rows($res_temp);

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
   if($troca_func != $nomefunc){
     $pdf->setfont('arial','B',8);
     $pdf->cell(15,$alt,$regist,0,0,"C",$pre);
     $pdf->cell(70,$alt,$nomefunc,0,1,"L",$pre);
     $troca_func = $nomefunc;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$tipo,0,0,"L",$pre);
   $pdf->cell(30,$alt,$campoalt,0,0,"L",$pre);
   $pdf->cell(40,$alt,$anterior,0,0,"L",$pre);
   $pdf->cell(40,$alt,$atual,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($data,'d').' - '.$hora,0,0,"L",$pre);
   $pdf->cell(70,$alt,$usuario,0,1,"L",$pre);
   $total += 1;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
$pdf->Output();
