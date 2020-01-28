<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("fpdf151/pdfwebseller.php");
include("classes/db_escola_classe.php");
include("classes/db_sala_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_telefoneescola_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_relacaotrabalho_classe.php");
include("classes/db_ensino_classe.php");
$clescola = new cl_escola;
$clsala = new cl_sala;
$clturma = new cl_turma;
$cltelefoneescola = new cl_telefoneescola;
$clrechumanoescola = new cl_rechumanoescola;
$clregenciahorario = new cl_regenciahorario;
$clrelacaotrabalho = new cl_relacaotrabalho;
$clensino = new cl_ensino;
$ensino_medio = 0;
$result = $clescola->sql_record($clescola->sql_query($censo_escola));
db_fieldsmemory($result,0);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(190);
$head1 = "CENSO ESCOLAR $censo_ano";
$pdf->addpage('P');
$pdf->ln(5);
//////////////////////////////////////////////////////inicio bloco 1
$pdf->setfont('arial','B',13);
$pdf->cell(30,7,"  BLOCO 1 - ","LBT",0,"R",0);
$pdf->setfont('arial','B',10);
$pdf->cell(160,7,"Cadastro da escola","RBT",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  1 - Nome da Escola",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,6,"   ".$ed18_c_nome,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(160,4,"  2 - Endereço",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(28,4,"  3 - Numero",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(160,6,"   ".$j14_nome,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(28,6,"   ".$ed18_i_numero,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(88,4,"  4 - Complemento",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(100,4,"  5 - Bairro",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(88,6,"   ".$ed18_c_compl,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(100,6,"   ".$j13_descr,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(88,4,"  6 - Distrito",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(100,4,"  7 - Município",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(88,6,"   ",0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(100,6,"   ".$cp05_localidades,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(20,4,"  8 - UF",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(20,4,"  9 - CEP",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(40,4,"  10 - Caixa Postal",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(20,4,"  11 - DDD",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(40,4,"  12 - Telefone da Escola",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(40,4,"  12A - Telefone Público",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(20,6,"  ".$cp05_sigla,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(20,6,"  ".$cp06_cep,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(40,6,"  ",0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(20,6,"  ",0,0,"L",0);
//telefone comercial da escola
$result1 = $cltelefoneescola->sql_record($cltelefoneescola->sql_query("","ed26_i_numero as comercial",""," ed26_i_escola = $censo_escola AND ed13_c_descr = 'COMERCIAL' LIMIT 1"));
if($cltelefoneescola->numrows>0){
 db_fieldsmemory($result1,0);
}else{
 $comercial = "";
}
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(40,6,"  ".$comercial,0,0,"L",0);
//telefone público 1 da escola
$result1 = $cltelefoneescola->sql_record($cltelefoneescola->sql_query("","ed26_i_numero as publico1",""," ed26_i_escola = $censo_escola AND ed13_c_descr = 'PÚBLICO' LIMIT 1"));
if($cltelefoneescola->numrows>0){
 db_fieldsmemory($result1,0);
}else{
 $publico1 = "";
}
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(40,6,"  ".$publico1,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(40,4,"  12B - Telefone Público",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(40,4,"  13 - FAX",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(106,4,"  14 - Endereço Eletrônico (e-mail)",0,1,"L",1);
$pdf->setfont('arial','',9);
//telefone público 2 da escola
$result1 = $cltelefoneescola->sql_record($cltelefoneescola->sql_query("","ed26_i_numero as publico2",""," ed26_i_escola = $censo_escola AND ed13_c_descr = 'PÚBLICO' LIMIT 2"));
if($cltelefoneescola->numrows>1){
 db_fieldsmemory($result1,1);
}else{
 $publico2 = "";
}
$pdf->cell(40,6,"  ".$publico2,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
//fax da escola
$result1 = $cltelefoneescola->sql_record($cltelefoneescola->sql_query("","ed26_i_numero as fax",""," ed26_i_escola = $censo_escola AND ed13_c_descr = 'FAX' LIMIT 1"));
if($cltelefoneescola->numrows>0){
 db_fieldsmemory($result1,0);
}else{
 $fax = "";
}
$pdf->cell(40,6,"  ".$fax,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(106,6,"  ".$ed18_c_email,0,1,"L",0);
$pdf->setfont('arial','B',8);
$pdf->cell(62,4,"  15 - Situação de funcionamento",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(62,4,"  16 - Dependência administrativa",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
$pdf->cell(62,4,"  17 - Localização/Zona da escola",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(62,6,"  EM ATIVIDADE",0,0,"L",0);
if($ed18_c_mantenedora==1) $ed18_c_mantenedora = "MUNICIPAL";
if($ed18_c_mantenedora==2) $ed18_c_mantenedora = "ESTADUAL";
if($ed18_c_mantenedora==3) $ed18_c_mantenedora = "FEDERAL";
if($ed18_c_mantenedora==4) $ed18_c_mantenedora = "PRIVADA";
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(62,6,"  ".$ed18_c_mantenedora,0,0,"L",0);
$pdf->cell(2,6,"",0,0,"L",0);
$ed18_c_local = @$ed18_c_local=="R"?"RURAL":"URBANA";
$pdf->cell(62,6,"  ".$ed18_c_local,0,1,"L",0);
//////////////////////////////////////////////////////fim bloco 1
$pdf->cell(190,5,"",0,1,"L",0);
//////////////////////////////////////////////////////inicio bloco 3
$pdf->setfont('arial','B',13);
$pdf->cell(30,7,"  BLOCO 3 - ","LBT",0,"R",0);
$pdf->setfont('arial','B',10);
$pdf->cell(160,7,"Salas de aula e recursos humanos","RBT",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 1
$pdf->setfont('arial','B',8);
$pdf->cell(94,4,"  1 - Número de salas de aula existentes - Em $censo_ano",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
///////////////////////////////////////////////CAMPO 2
$pdf->cell(94,4,"  2 - Número de salas de aula utlilizadas - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
//salas de aula existentes
$result1 = $clsala->sql_record($clsala->sql_query("","count(*) as sl_existente",""," ed16_i_escola = $censo_escola AND ed14_c_aula = 'S'"));
if($clsala->numrows>0){
 db_fieldsmemory($result1,0);
}else{
 $sl_existente = "";
}
$pdf->cell(94,6,"  PERMANENTES:   ".$sl_existente."                PROVISÒRIAS:   0",0,0,"L",0);
//salas de aula utilizadas
$result1 = $clturma->sql_record($clturma->sql_query(""," DISTINCT ed16_c_descr",""," ed57_i_escola = $censo_escola AND ed16_c_pertence = 'S' AND ed52_i_ano = $censo_ano"));
$sl_util_propria = $clturma->numrows;
$result1 = $clturma->sql_record($clturma->sql_query(""," DISTINCT ed16_c_descr",""," ed57_i_escola = $censo_escola AND ed16_c_pertence = 'N' AND ed52_i_ano = $censo_ano"));
$sl_util_naopropria = $clturma->numrows;
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(94,6,"  NO PRÉDIO:   ".$sl_util_propria."                FORA DO PRÉDIO:   ".$sl_util_naopropria,0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 3
$pdf->cell(94,4,"  3 - Total de funcionários da escola - Em $censo_ano (inclusive professores)",0,0,"L",1);
$pdf->cell(2,4,"",0,0,"L",0);
///////////////////////////////////////////////CAMPO 4
$pdf->cell(94,4,"  4 - Total de professores em exercício (em sala de aula) - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
//total de funcionários
$result1 = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("","count(*) as total_func",""," ed75_i_escola = $censo_escola"));
if($clrechumanoescola->numrows>0){
 db_fieldsmemory($result1,0);
}else{
 $total_func = "";
}
$pdf->cell(94,6,"  ".$total_func,0,0,"L",0);
//total professores em exercício
$result1 = $clregenciahorario->sql_record($clregenciahorario->sql_query(""," DISTINCT ed47_v_nome",""," ed57_i_escola = $censo_escola AND ed52_i_ano = $censo_ano and ed58_ativo is true  "));
$total_exercicio = $clregenciahorario->numrows;
$pdf->cell(2,6,"",0,0,"L",0);
$pdf->cell(94,6,"  ".$total_exercicio,0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 6
$pdf->cell(190,4,"  6 - Número de professores por etapa/modalidade de atuação - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
//professores por ensino
$result1 = $clensino->sql_record($clensino->sql_query("","ed10_c_descr,ed10_i_codigo","ed10_c_abrev",""));
if($clensino->numrows>0){
 for($x=0;$x<$clensino->numrows;$x++){
  db_fieldsmemory($result1,$x);
  $result2 = $clrelacaotrabalho->sql_record($clrelacaotrabalho->sql_query(""," count(ed75_i_codigo) as prof_ensino",""," ed75_i_escola = $censo_escola AND ed10_i_codigo = $ed10_i_codigo"));
  if($clrelacaotrabalho->numrows>0){
   db_fieldsmemory($result2,0);
  }else{
   $prof_ensino = "0";
  }
  $pdf->cell(60,6,"  ".$ed10_c_descr.":",0,0,"L",0);
  $pdf->cell(130,6,"  ".($prof_ensino==0?"--":$prof_ensino),0,1,"L",0);
 }
}
//////////////////////////////////////////////////////fim bloco 3
$pdf->cell(190,5,"",0,1,"L",0);
$pdf->addpage('P');
$pdf->ln(5);
//////////////////////////////////////////////////////inicio bloco 4
$pdf->setfont('arial','B',13);
$pdf->cell(30,7,"  BLOCO 4 - ","LBT",0,"R",0);
$pdf->setfont('arial','B',10);
$pdf->cell(160,7,"Educação Infantil","RBT",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 1
$pdf->cell(190,4,"  1- Número de turmas e matrícula inicial na educação infantil - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$sql1 = "SELECT min(ed17_h_inicio)||'|'||max(ed17_h_fim) as horario,
                min(ed17_h_inicio) as inicio,
                max(ed17_h_fim) as termino,
                ed57_i_codigo as turma,
                ed10_i_codigo as ensino
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join calendario on ed52_i_codigo = ed57_i_calendario
          inner join regencia on ed57_i_codigo = ed59_i_turma
          inner join regenciahorario on ed58_i_regencia = ed59_i_codigo
          inner join periodoescola on ed17_i_codigo = ed58_i_periodo
          inner join serie on ed11_i_codigo = ed57_i_serie
          inner join ensino on ed10_i_codigo = ed11_i_ensino
         WHERE ed11_i_ensino in ($ensino_inf_cre,$ensino_inf_pre)
         AND ed52_i_ano = $censo_ano
         AND ed57_i_escola = $censo_escola and ed58_ativo is true  
         GROUP BY ed57_i_codigo,ed10_i_codigo,ed11_c_descr,ed10_c_abrev
         ORDER BY inicio,termino
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
$pdf->cell(50,4,"  Horário de Funcionamento","BR",0,"L",0);
$pdf->cell(50,4,"  Educação Infantil Creche","BR",0,"L",0);
$pdf->cell(50,4,"  Educação Infantil Pré-escola","BR",0,"L",0);
$pdf->cell(40,4,"  Turma Unificada","B",1,"L",0);
$pdf->line(10,$pdf->getY(),200,$pdf->getY());
$primeiro = "";
$cont_ens1 = 0;
$cont_ens6 = 0;
$cont_qtdmat1 = 0;
$cont_qtdmat6 = 0;
$s_cont_ens1 = 0;
$s_cont_ens6 = 0;
$s_cont_qtdmat1 = 0;
$s_cont_qtdmat6 = 0;
for($x=0;$x<$linhas1;$x++){
 db_fieldsmemory($result1,$x);
 if($primeiro!=$horario){
  if($x!=0){
   $pdf->cell(25,4,"      ".($cont_ens6==0?"--":$cont_ens6),"L",0,"L",0);
   $pdf->cell(25,4,"      ".($cont_qtdmat6==0?"--":$cont_qtdmat6),0,0,"L",0);
   $pdf->cell(25,4,"      ".($cont_ens1==0?"--":$cont_ens1),"L",0,"L",0);
   $pdf->cell(25,4,"      ".($cont_qtdmat1==0?"--":$cont_qtdmat1),0,0,"L",0);
   $pdf->cell(25,4,"      --","L",1,"L",0);
   $pdf->line(10,$pdf->getY(),200,$pdf->getY());
   $cont_ens1 = 0;
   $cont_ens6 = 0;
   $cont_qtdmat1 = 0;
   $cont_qtdmat6 = 0;
  }
  $pdf->cell(25,4,"  Início:",0,0,"L",0);
  $pdf->cell(25,4,"  Término:",0,0,"L",0);
  $pdf->cell(25,4,"  Turmas:",0,0,"L",0);
  $pdf->cell(25,4,"  Matrículas:",0,0,"L",0);
  $pdf->cell(25,4,"  Turmas:",0,0,"L",0);
  $pdf->cell(25,4,"  Matrículas:",0,0,"L",0);
  $pdf->cell(40,4,"  Turmas:",0,1,"L",0);
  $pdf->cell(25,4,"  ".$inicio,0,0,"L",0);
  $pdf->cell(25,4,"  ".$termino,0,0,"L",0);
  $primeiro = $horario;
 }
 if($ensino==$ensino_inf_cre){
  $cont_ens1++;
  $s_cont_ens1++;
  $sql2 = "SELECT count(ed60_i_codigo) as qtdmat1 from matricula where ed60_i_turma = $turma";
  $result2 = pg_query($sql2);
  db_fieldsmemory($result2,0);
  $cont_qtdmat1 += $qtdmat1;
  $s_cont_qtdmat1 += $qtdmat1;
 }elseif($ensino==$ensino_inf_pre){
  $cont_ens6++;
  $s_cont_ens6++;
  $sql2 = "SELECT count(ed60_i_codigo) as qtdmat6 from matricula where ed60_i_turma = $turma";
  $result2 = pg_query($sql2);
  db_fieldsmemory($result2,0);
  $cont_qtdmat6 += $qtdmat6;
  $s_cont_qtdmat6 += $qtdmat6;
 }
 if($x==($linhas1-1)){
  $pdf->cell(25,4,"      ".($cont_ens6==0?"--":$cont_ens6),"L",0,"L",0);
  $pdf->cell(25,4,"      ".($cont_qtdmat6==0?"--":$cont_qtdmat6),0,0,"L",0);
  $pdf->cell(25,4,"      ".($cont_ens1==0?"--":$cont_ens1),"L",0,"L",0);
  $pdf->cell(25,4,"      ".($cont_qtdmat1==0?"--":$cont_qtdmat1),0,0,"L",0);
  $pdf->cell(25,4,"      --","L",1,"L",0);
  $pdf->line(10,$pdf->getY(),200,$pdf->getY());
  $cont_ens1 = 0;
  $cont_ens6 = 0;
  $cont_qtdmat1 = 0;
  $cont_qtdmat6 = 0;
 }
}
$pdf->cell(50,4,"",0,0,"L",0);
$pdf->cell(25,4,"  Turmas: ",0,0,"L",0);
$pdf->cell(25,4,"  Matrículas: ",0,0,"L",0);
$pdf->cell(25,4,"  Turmas: ",0,0,"L",0);
$pdf->cell(25,4,"  Matrículas: ",0,0,"L",0);
$pdf->cell(40,4,"  Turmas:",0,1,"L",0);
$pdf->cell(50,4,"  TOTAL","B",0,"C",0);
$pdf->cell(25,4,"      ".($s_cont_ens6==0?"--":$s_cont_ens6),"BL",0,"L",0);
$pdf->cell(25,4,"      ".($s_cont_qtdmat6==0?"--":$s_cont_qtdmat6),"B",0,"L",0);
$pdf->cell(25,4,"      ".($s_cont_ens1==0?"--":$s_cont_ens1),"LB",0,"L",0);
$pdf->cell(25,4,"      ".($s_cont_qtdmat1==0?"--":$s_cont_qtdmat1),"B",0,"L",0);
$pdf->cell(40,4,"      --","LB",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 2
$pdf->cell(190,4,"  2- Matrícula inicial na educação infantil por ano de nascimento - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(50,4,"  Ano de Nascimento","BR",0,"L",0);
$pdf->cell(70,4,"  Educação Infantil Creche","BR",0,"L",0);
$pdf->cell(70,4,"  Educação Infantil Pré-escola","B",1,"L",0);
$pdf->line(10,$pdf->getY(),200,$pdf->getY());
$pdf->cell(190,3,"",0,1,"L",0);
//$primeiro = "";
$s_cont_ens1 = 0;
$s_cont_ens6 = 0;
$ano_inicio = $censo_ano-3;
$ano_fim = $censo_ano-9;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(50,4,"  Após $ano_inicio","BR",0,"L",0);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmais
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_inf_cre)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  $pdf->cell(70,4,"      ".($qtdmais==0?"--":$qtdmais),"BR",0,"L",0);
  $s_cont_ens6 += $qtdmais;
  $sql2 = "SELECT count(ed60_i_codigo) as qtdmais
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_inf_pre)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  db_fieldsmemory($result2,0);
  $pdf->cell(70,4,"      ".($qtdmais==0?"--":$qtdmais),"B",1,"L",0);
  $s_cont_ens1 += $qtdmais;
  $pdf->cell(190,3,"",0,1,"L",0);
 }
 $pdf->cell(50,4,"  $x","BR",0,"L",0);
 $sql3 = "SELECT count(ed60_i_codigo) as qtdigual
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_inf_cre)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
         ";
 $result3 = pg_query($sql3);
 $linhas3 = pg_num_rows($result3);
 db_fieldsmemory($result3,0);
 $pdf->cell(70,4,"      ".($qtdigual==0?"--":$qtdigual),"BR",0,"L",0);
 $s_cont_ens6 += $qtdigual;
 $sql4 = "SELECT count(ed60_i_codigo) as qtdigual
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_inf_pre)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
         ";
 $result4 = pg_query($sql4);
 $linhas4 = pg_num_rows($result4);
 db_fieldsmemory($result4,0);
 $pdf->cell(70,4,"      ".($qtdigual==0?"--":$qtdigual),"B",1,"L",0);
 $s_cont_ens1 += $qtdigual;
 $pdf->cell(190,3,"",0,1,"L",0);
 if($x==$ano_fim){
  $pdf->cell(50,4,"  Antes de $x","BR",0,"L",0);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmenos
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_inf_cre)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) < $x
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  $pdf->cell(70,4,"      ".($qtdmenos==0?"--":$qtdmenos),"BR",0,"L",0);
  $s_cont_ens6 += $qtdmenos;
  $sql2 = "SELECT count(ed60_i_codigo) as qtdmenos
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_inf_pre)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) < $x
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  db_fieldsmemory($result2,0);
  $pdf->cell(70,4,"      ".($qtdmenos==0?"--":$qtdmenos),"B",1,"L",0);
  $s_cont_ens1 += $qtdmenos;
  $pdf->cell(190,3,"",0,1,"L",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(50,4,"  TOTAL","BR",0,"L",0);
$pdf->cell(70,4,"      ".($s_cont_ens6==0?"--":$s_cont_ens6),"BR",0,"L",0);
$pdf->cell(70,4,"      ".($s_cont_ens1==0?"--":$s_cont_ens1),"B",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',9);
$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 3
$pdf->cell(190,4,"  3- Matrícula inicial na educação infantil, por sexo e cor/raça - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(30,4,"  Sexo","BR",0,"L",0);
$pdf->cell(40,4,"  Cor/Raça","BR",0,"L",0);
$pdf->cell(60,4,"  Educação Infantil Creche","BR",0,"L",0);
$pdf->cell(60,4,"  Educação Infantil Pré-escola","B",1,"L",0);
$s_cont_ens1 = 0;
$s_cont_ens6 = 0;
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"  Masculino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc,
                 ed11_i_ensino as ensinomasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_inf_cre,$ensino_inf_pre)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND trim(ed47_c_raca) = '$racas[$x]'
          AND ed47_v_sexo = 'M'
          GROUP BY ed11_i_ensino
          ORDER BY ed11_i_ensino DESC
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"      ","R",0,"L",0);
  $pdf->cell(40,4,"      $racas[$x]","BR",0,"L",0);
  $pdf->cell(60,4,"      --","BR",0,"L",0);
  $pdf->cell(60,4,"      --","B",1,"L",0);
 }else{
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,"      ","R",0,"L",0);
  $pdf->cell(40,4,"      $racas[$x]","BR",0,"L",0);
  if($linhas1==1){
   $s_cont_ens1 += $ensinomasc==1?$qtdmasc:0;
   $s_cont_ens6 += $ensinomasc==6?$qtdmasc:0;
   $pdf->cell(60,4,"      ".($ensinomasc==6?$qtdmasc:"--"),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".($ensinomasc==1?$qtdmasc:"--"),"B",1,"L",0);
  }else{
   $s_cont_ens1 += pg_result($result1,1,'qtdmasc');
   $s_cont_ens6 += pg_result($result1,0,'qtdmasc');
   $pdf->cell(60,4,"      ".pg_result($result1,0,'qtdmasc'),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".pg_result($result1,1,'qtdmasc'),"B",1,"L",0);
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"  Feminino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc,
                 ed11_i_ensino as ensinomasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_inf_cre,$ensino_inf_pre)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND trim(ed47_c_raca) = '$racas[$x]'
          AND ed47_v_sexo = 'F'
          GROUP BY ed11_i_ensino
          ORDER BY ed11_i_ensino DESC
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"      ","R",0,"L",0);
  $pdf->cell(40,4,"      $racas[$x]","BR",0,"L",0);
  $pdf->cell(60,4,"      --","BR",0,"L",0);
  $pdf->cell(60,4,"      --","B",1,"L",0);
 }else{
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,"      ","R",0,"L",0);
  $pdf->cell(40,4,"      $racas[$x]","BR",0,"L",0);
  if($linhas1==1){
   $s_cont_ens1 += $ensinomasc==1?$qtdmasc:0;
   $s_cont_ens6 += $ensinomasc==6?$qtdmasc:0;
   $pdf->cell(60,4,"      ".($ensinomasc==6?$qtdmasc:"--"),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".($ensinomasc==1?$qtdmasc:"--"),"B",1,"L",0);
  }else{
   $s_cont_ens1 += pg_result($result1,1,'qtdmasc');
   $s_cont_ens6 += pg_result($result1,0,'qtdmasc');
   $pdf->cell(60,4,"      ".pg_result($result1,0,'qtdmasc'),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".pg_result($result1,1,'qtdmasc'),"B",1,"L",0);
  }
 }
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','B',9);
$pdf->cell(30,4,"      ","R",0,"L",0);
$pdf->cell(40,4,"      TOTAL","BR",0,"L",0);
$pdf->cell(60,4,"      ".($s_cont_ens6==0?"--":$s_cont_ens6),"BR",0,"L",0);
$pdf->cell(60,4,"      ".($s_cont_ens1==0?"--":$s_cont_ens1),"B",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);

$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 4
$pdf->cell(190,4,"  4- Movimento e matrícula final na educação infantil - Em $anoant",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(70,4,"  ","BR",0,"L",0);
$pdf->cell(60,4,"  Educação Infantil Creche","BR",0,"L",0);
$pdf->cell(60,4,"  Educação Infantil Pré-escola","B",1,"L",0);
$pdf->cell(190,3,"",0,1,"L",0);
$s_cont_ens1 = 0;
$s_cont_ens6 = 0;
$situacao = array("'MATRICULADO'","'CANCELADO','EVADIDO','FALECIDO'","'TRANSFERIDO'");
$descrsit = array("Admitidos após 30/03/$anoant","Afastados por abandono após 30/03/$anoant","Afastados por transferência após 30/03/$anoant");
for($x=0;$x<count($situacao);$x++){
 $sql1 = "SELECT count(ed60_i_codigo) as qtdsit,
                 ed11_i_ensino as ensinosit
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_inf_cre,$ensino_inf_pre)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND trim(ed60_c_situacao) in ($situacao[$x])
          AND ed60_d_datamatricula >= '$anoant/03/30'
          GROUP BY ed11_i_ensino
          ORDER BY ed11_i_ensino DESC
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(70,4,"  $descrsit[$x]","BR",0,"L",0);
  $pdf->cell(60,4,"      --","BR",0,"L",0);
  $pdf->cell(60,4,"      --","B",1,"L",0);
  $pdf->cell(190,3,"",0,1,"L",0);
 }else{
  $pdf->cell(70,4,"  $descrsit[$x]","BR",0,"L",0);
  if($linhas1==1){
   $s_cont_ens1 += $ensinosit==1?$qtdsit:0;
   $s_cont_ens6 += $ensinosit==6?$qtdsit:0;
   $pdf->cell(60,4,"      ".($ensinosit==6?$qtdsit:"--"),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".($ensinosit==1?$qtdsit:"--"),"B",1,"L",0);
  }else{
   $s_cont_ens1 += pg_result($result1,1,'qtdsit');
   $s_cont_ens6 += pg_result($result1,0,'qtdsit');
   $pdf->cell(60,4,"      ".pg_result($result1,0,'qtdsit'),"BR",0,"L",0);
   $pdf->cell(60,4,"      ".pg_result($result1,1,'qtdsit'),"B",1,"L",0);
  }
  $pdf->cell(190,3,"",0,1,"L",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(70,4,"  Matrícula Final","BR",0,"L",0);
$pdf->cell(60,4,"      ".($s_cont_ens6==0?"--":$s_cont_ens6),"BR",0,"L",0);
$pdf->cell(60,4,"      ".($s_cont_ens1==0?"--":$s_cont_ens1),"B",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',9);
//////////////////////////////////////////////////////fim bloco 4
$pdf->cell(190,5,"",0,1,"L",0);
$pdf->addpage('P');
$pdf->ln(5);
//////////////////////////////////////////////////////inicio bloco 5
$pdf->setfont('arial','B',13);
$pdf->cell(30,7,"  BLOCO 5 - ","LBT",0,"R",0);
$pdf->setfont('arial','B',10);
$pdf->cell(160,7,"Ensino Fundamental (Regular) 8 anos","RBT",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 3
$pdf->cell(190,4,"  3- Número de turmas (T) e matrícula inicial (MI) no ensino fundamental (regular) - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$sql1 = "SELECT min(ed17_h_inicio) as inicio,
                max(ed17_h_fim) as termino,
                ed17_i_turno
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join calendario on ed52_i_codigo = ed57_i_calendario
          inner join regencia on ed57_i_codigo = ed59_i_turma
          inner join regenciahorario on ed58_i_regencia = ed59_i_codigo
          inner join periodoescola on ed17_i_codigo = ed58_i_periodo
          inner join serie on ed11_i_codigo = ed57_i_serie
         WHERE ed11_i_ensino in ($ensino_fun_oito)
         AND ed52_i_ano = $censo_ano
         AND ed57_i_escola = $censo_escola and ed58_ativo is true  
         GROUP BY ed17_i_turno
         ORDER BY inicio,termino
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
$pdf->setfont('arial','',8);
$inic = $pdf->getY();
$pdf->cell(30,2,"  Horário de","R",2,"L",0);
$pdf->cell(30,2,"  Funcionamento","BR",2,"L",0);
$pdf->setY($inic);
$pdf->setX(40);
$pdf->setfont('arial','',9);
$sql2 = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_oito)
         ORDER BY ed11_i_sequencia
         LIMIT 8
        ";
$result2 = pg_query($sql2);
$linhas2 = pg_num_rows($result2);
for($x=0;$x<$linhas2;$x++){
 db_fieldsmemory($result2,$x);
 if($x==($linhas2-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
for($x=0;$x<$linhas1;$x++){
 db_fieldsmemory($result1,$x);
 $pdf->cell(190,2,"",0,1,"L",0);
 $inicY = $pdf->getY();
 $inicX = 40;
 $pdf->cell(10,4,"Início",0,0,"C",0);
 $pdf->cell(13,4,"Término",0,0,"C",0);
 $pdf->setfont('arial','B',9);
 $pdf->cell(7,4,"T","R",1,"C",0);
 $pdf->cell(30,2,"",0,1,"C",0);
 $pdf->setfont('arial','',9);
 $pdf->cell(10,4,"$inicio","B",0,"C",0);
 $pdf->cell(13,4,"$termino","B",0,"C",0);
 $pdf->setfont('arial','B',9);
 $pdf->cell(7,4,"MI","BR",1,"C",0);
 $pdf->setfont('arial','',9);
 for($z=0;$z<$linhas2;$z++){
  db_fieldsmemory($result2,$z);
  if($z==($linhas2-1)){
   $qb = 1;
  }else{
   $qb = 2;
  }
  $sql3 = "SELECT ed57_i_codigo,count(ed60_i_codigo)/2 as matr
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join regencia on ed57_i_codigo = ed59_i_turma
            inner join regenciahorario on ed58_i_regencia = ed59_i_codigo
            inner join periodoescola on ed17_i_codigo = ed58_i_periodo
            inner join serie on ed11_i_codigo = ed57_i_serie
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND (ed17_h_inicio = '$inicio' OR ed17_h_fim = '$termino')
           AND ed57_i_serie = $codserie and ed58_ativo is true  
           GROUP BY ed57_i_codigo
          ";
  $result3 = pg_query($sql3);
  $linhas3 = pg_num_rows($result3);
  $pdf->setY($inicY);
  $pdf->setX($inicX);
  $pdf->cell(20,4,($linhas3==0?"--":$linhas3),"BL",2,"C",0);
  $pdf->cell(20,2,"",0,2,"C",0);
  $soma_mat = 0;
  for($y=0;$y<$linhas3;$y++){
   db_fieldsmemory($result3,$y);
   $soma_mat += $matr;
  }
  $pdf->cell(20,4,($soma_mat==0?"--":$soma_mat),"BL",$qb,"C",0);
  $inicX += 20;
 }
}
$pdf->cell(190,2,"",0,1,"L",0);
$inicY = $pdf->getY();
$inicX = 40;
$pdf->cell(23,4,"",0,0,"C",0);
$pdf->setfont('arial','B',9);
$pdf->cell(7,4,"T","R",1,"C",0);
$pdf->cell(30,2,"",0,1,"C",0);
$pdf->setfont('arial','',9);
$pdf->cell(23,4,"TOTAL","B",0,"C",0);
$pdf->setfont('arial','B',9);
$pdf->cell(7,4,"MI","BR",1,"C",0);
$pdf->setfont('arial','',9);
for($z=0;$z<$linhas2;$z++){
 db_fieldsmemory($result2,$z);
 if($z==($linhas2-1)){
  $qb = 1;
 }else{
  $qb = 2;
 }
 $sql3 = "SELECT ed57_i_codigo,count(ed60_i_codigo) as matr
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join serie on ed11_i_codigo = ed57_i_serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          GROUP BY ed57_i_codigo
         ";
 $result3 = pg_query($sql3);
 $linhas3 = pg_num_rows($result3);
 $pdf->setY($inicY);
 $pdf->setX($inicX);
 $pdf->setfont('arial','B',9);
 $pdf->cell(20,4,($linhas3==0?"--":$linhas3),"BL",2,"C",0);
 $pdf->cell(20,2,"",0,2,"C",0);
 $soma_mat = 0;
 for($y=0;$y<$linhas3;$y++){
  db_fieldsmemory($result3,$y);
  $soma_mat += $matr;
 }
 $pdf->cell(20,4,($soma_mat==0?"--":$soma_mat),"BL",$qb,"C",0);
 $inicX += 20;
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 4
$pdf->cell(190,4,"  4- Matrícula inicial no ensino fundamental (regular), por etapa/ano e ano de nascimento - Em $censo_ano - Diurno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(30,4," Ano de nascimento","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_oito)
         ORDER BY ed11_i_sequencia
         LIMIT 8
        ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$ano_inicio = $censo_ano-6;
$ano_fim = $censo_ano-19;
$pula = 0;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(30,4,"  Após $ano_inicio","BR",0,"L",0);
  for($z=0;$z<$linhas_s;$z++){
   db_fieldsmemory($result_s,$z);
   $sql1 = "SELECT count(ed60_i_codigo) as qtdmais
            FROM matricula
             inner join turma on ed57_i_codigo = ed60_i_turma
             inner join serie on ed11_i_codigo = ed57_i_serie
             inner join calendario on ed52_i_codigo = ed57_i_calendario
             inner join aluno on ed47_i_codigo = ed60_i_aluno
            WHERE ed11_i_ensino in ($ensino_fun_oito)
            AND ed52_i_ano = $censo_ano
            AND ed57_i_escola = $censo_escola
            AND extract(year from ed47_d_nasc) > $x
            AND ed57_i_serie = $codserie
            AND ed57_i_turno in (1,2)
           ";
   $result1 = pg_query($sql1);
   db_fieldsmemory($result1,0);
   if($z==($linhas_s-1)){
    $qb = 1;
   }else{
    $qb = 0;
   }
   if($z>$pula){
    $qtdmais = "--";
    $fill = 1;
   }else{
    $qtdmais = $qtdmais;
    $fill = 0;
   }
   $pdf->cell(20,4,($qtdmais==0?"--":$qtdmais),"BL",$qb,"C",$fill);
  }
  $pula++;
  $pdf->cell(190,3,"",0,1,"L",0);
 }
 $pdf->cell(30,4,"  $x","BR",0,"L",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql3 = "SELECT count(ed60_i_codigo) as qtdigual
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) = $x
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  if($z>$pula){
   $qtdigual = "--";
   $fill = 1;
  }else{
   $qtdigual = $qtdigual;
   $fill = 0;
  }
  $pdf->cell(20,4,($qtdigual==0?"--":$qtdigual),"BL",$qb,"C",$fill);
 }
 $pdf->cell(190,3,"",0,1,"L",0);
 $pula++;
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(30,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(30,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    for($z=0;$z<$linhas_s;$z++){
     db_fieldsmemory($result_s,$z);
     $sql1 = "SELECT count(ed60_i_codigo) as qtdmenos
              FROM matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
               inner join serie on ed11_i_codigo = ed57_i_serie
               inner join calendario on ed52_i_codigo = ed57_i_calendario
               inner join aluno on ed47_i_codigo = ed60_i_aluno
              WHERE ed11_i_ensino in ($ensino_fun_oito)
              AND ed52_i_ano = $censo_ano
              AND ed57_i_escola = $censo_escola
              AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
              AND ed57_i_serie = $codserie
              AND ed57_i_turno in (1,2)
             ";
     $result1 = pg_query($sql1);
     db_fieldsmemory($result1,0);
     if($z==($linhas_s-1)){
      $qb = 1;
     }else{
      $qb = 0;
     }
     if($z>$pula){
      $qtdmenos = "--";
      $fill = 1;
     }else{
      $qtdmenos = $qtdmenos;
      $fill = 0;
     }
     $pdf->cell(20,4,($qtdmenos==0?"--":$qtdmenos),"BL",$qb,"C",$fill);
    }
    $pdf->cell(190,3,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(30,4,"  TOTAL","BR",0,"L",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as total
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,($total==0?"--":$total),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 5
$pdf->cell(190,4,"  5- Matrícula inicial no ensino fundamental (regular), por etapa/ano e ano de nascimento - Em $censo_ano - Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(30,4," Ano de nascimento","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_oito)
         ORDER BY ed11_i_sequencia
         LIMIT 8
        ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$ano_dis = $censo_ano - 11;
$ano_inicio = $censo_ano-6;
$ano_fim = $censo_ano-19;
$pula = 0;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(30,4,"  Após $ano_inicio","BR",0,"L",0);
  for($z=0;$z<$linhas_s;$z++){
   db_fieldsmemory($result_s,$z);
   $sql1 = "SELECT count(ed60_i_codigo) as qtdmais
            FROM matricula
             inner join turma on ed57_i_codigo = ed60_i_turma
             inner join serie on ed11_i_codigo = ed57_i_serie
             inner join calendario on ed52_i_codigo = ed57_i_calendario
             inner join aluno on ed47_i_codigo = ed60_i_aluno
            WHERE ed11_i_ensino in ($ensino_fun_oito)
            AND ed52_i_ano = $censo_ano
            AND ed57_i_escola = $censo_escola
            AND extract(year from ed47_d_nasc) > $x
            AND ed57_i_serie = $codserie
            AND ed57_i_turno in (3)
           ";
   $result1 = pg_query($sql1);
   db_fieldsmemory($result1,0);
   if($z==($linhas_s-1)){
    $qb = 1;
   }else{
    $qb = 0;
   }
   if($x>$ano_dis){
    $qtdmais = "--";
    $fill = 1;
   }else{
    $qtdmais = $qtdmais;
    $fill = 0;
   }
   $pdf->cell(20,4,($qtdmais==0?"--":$qtdmais),"BL",$qb,"C",$fill);
  }
  $pdf->cell(190,3,"",0,1,"L",0);
 }
 $pdf->cell(30,4,"  $x","BR",0,"L",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql3 = "SELECT count(ed60_i_codigo) as qtdigual
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) = $x
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  if($x>$ano_dis){
   $qtdigual = "--";
   $fill = 1;
  }else{
   if($pula==7){
    $qtdigual = "--";
    $fill = 1;
   }else{
    $qtdigual = $qtdigual;
    $fill = 0;
   }
   $pula++;
  }
  $pdf->cell(20,4,($qtdigual==0?"--":$qtdigual),"BL",$qb,"C",$fill);
 }
 $pdf->cell(190,3,"",0,1,"L",0);
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(30,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(30,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    for($z=0;$z<$linhas_s;$z++){
     db_fieldsmemory($result_s,$z);
     $sql1 = "SELECT count(ed60_i_codigo) as qtdmenos
              FROM matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
               inner join serie on ed11_i_codigo = ed57_i_serie
               inner join calendario on ed52_i_codigo = ed57_i_calendario
               inner join aluno on ed47_i_codigo = ed60_i_aluno
              WHERE ed11_i_ensino in ($ensino_fun_oito)
              AND ed52_i_ano = $censo_ano
              AND ed57_i_escola = $censo_escola
              AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
              AND ed57_i_serie = $codserie
              AND ed57_i_turno in (3)
             ";
     $result1 = pg_query($sql1);
     db_fieldsmemory($result1,0);
     if($z==($linhas_s-1)){
      $qb = 1;
     }else{
      $qb = 0;
     }
     if($x>$ano_dis){
      $qtdmenos = "--";
      $fill = 1;
     }else{
      $qtdmenos = $qtdmenos;
      $fill = 0;
     }
     $pdf->cell(20,4,($qtdmenos==0?"--":$qtdmenos),"BL",$qb,"C",$fill);
    }
    $pdf->cell(190,3,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(30,4,"  TOTAL","BR",0,"L",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as total
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,($total==0?"--":$total),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 6
$pdf->cell(190,4,"  6- Matrícula inicial no ensino fundamental (regular), por etapa/ano, sexo e cor/raça - Em $censo_ano - Diurno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(15,4,"Sexo","BR",0,"C",0);
$pdf->cell(15,4,"Cor/Raça","BR",0,"C",0);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Masculino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');

for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(30,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND trim(ed47_v_sexo) = 'M'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Feminino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(30,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND trim(ed47_v_sexo) = 'F'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(30,4,"TOTAL ","BR",0,"R",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 7
$pdf->cell(190,4,"  7- Matrícula Inicial no ensino fundamental (regular), por etapa/ano, sexo e cor/raça - Em $censo_ano - Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(15,4,"Sexo","BR",0,"C",0);
$pdf->cell(15,4,"Cor/Raça","BR",0,"C",0);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Masculino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');

for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(30,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'M'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Feminino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(30,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'F'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(30,4,"TOTAL ","BR",0,"R",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(20,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 9
$pdf->cell(190,4,"  9- Matrícula inicial, em $censo_ano, no ensino fundamental (regular), por etapa/ano, de alunos promovidos, repetentes, provenientes.",0,1,"L",1);
$pdf->cell(190,4,"  da Educação de Jovens e Adultos e que não freqüentaram escola em ".($censo_ano-1).".",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Número de alunos promovidos","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND (ed60_c_rfanterior = 'A' OR ed60_c_rfanterior = '')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Número de alunos repetentes","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed60_c_rfanterior = 'R'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Matrícula Inicial em $censo_ano, de alunos","R",2,"L",0);
$pdf->cell(46,3," que freqüentaram a EJA em ".($censo_ano-1),"BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma
           inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
           inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
           inner join aluno on aluno.ed47_i_codigo = matricula.ed60_i_aluno
           left join turma as turmaant on turmaant.ed57_i_codigo = matricula.ed60_i_turmaant
           left join serie as serieant on serieant.ed11_i_codigo = turmaant.ed57_i_serie
          WHERE serie.ed11_i_ensino in ($ensino_fun_oito)
          AND calendario.ed52_i_ano = $censo_ano
          AND turma.ed57_i_escola = $censo_escola
          AND turma.ed57_i_serie = $codserie
          AND serieant.ed11_i_ensino in ($ensino_fun_eja)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Matrícula Inicial em $censo_ano, de alunos","R",2,"L",0);
$pdf->cell(46,3," que não freqüentaram escola em ".($censo_ano-1),"BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,"--","BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 10
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  10- Movimento e rendimento escolar no ensino fundamental (regular) - Turno diurno - Em $anoant",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Matrícula inicial em $anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Admitidos após 30/03/$anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_d_datamatricula > '$anoant/03/30'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Afastados por abandono","R",2,"L",0);
$pdf->cell(46,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_c_situacao in ('CANCELADO','EVADIDO','FALECIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Afastados por transferência","R",2,"L",0);
$pdf->cell(46,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_c_situacao in ('TRANSFERIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Aprovados sem dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno
             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4==0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Aprovados com dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4>0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Reprovados","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $reprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal = 'R'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2>0){
    $reprov++;
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($reprov==0?"--":$reprov),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 11
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  11- Alunos reclassificados após 30/3/$anoant - Turno diurno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  ",0,0,"L",0);
$pdf->cell(144,4,"Etapa de destino em $anoant",0,1,"L",0);
$pdf->cell(46,1,"  ",0,0,"L",0);
$pdf->cell(144,1,"  ",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  Etapa de ingresso","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed11_i_sequencia > 1
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $pdf->cell(18,4,"  $ed11_c_descr","BL",0,"L",0);
}
$pdf->cell(18,4,"  Médio","BL",1,"L",0);
$sql_si = "SELECT ed11_i_codigo as codsi,ed11_c_descr as descrsi,ed11_i_sequencia as seqsi
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_si = pg_query($sql_si);
$linhas_si = pg_num_rows($result_si);
for($x=0;$x<$linhas_si;$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 db_fieldsmemory($result_si,$x);
 $pdf->cell(46,6,"  $descrsi","BR",0,"L",0);
 $sql_sd = "SELECT ed11_i_codigo as codsd,ed11_i_sequencia as seqsd
            FROM serie
            WHERE ed11_i_ensino in ($ensino_fun_oito)
            AND ed11_i_sequencia > 1
            ORDER BY ed11_i_sequencia
            LIMIT 8
           ";
 $result_sd = pg_query($sql_sd);
 $linhas_sd = pg_num_rows($result_sd);
 for($z=0;$z<$linhas_sd;$z++){
  db_fieldsmemory($result_sd,$z);
  if($seqsi>=$seqsd){
   $pdf->cell(18,6,"--","BL",0,"C",1);
  }else{
   $sql3 = "SELECT count(*) as qtd
            FROM alunotransfturma
             inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
             inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
             inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
             inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
             inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
            WHERE serie.ed11_i_ensino in ($ensino_fun_oito)
            AND calendario.ed52_i_ano = $anoant
            AND turma.ed57_i_escola = $censo_escola
            AND turma.ed57_i_serie = $codsi
            AND turmad.ed57_i_serie = $codsd
            AND turma.ed57_i_turno in (1,2)
           ";
   $result3 = pg_query($sql3);
   db_fieldsmemory($result3,0);
   $pdf->cell(18,6,($qtd==0?"--":$qtd),"BL",0,"C",0);
  }
 }
 if($seqsi>4){
  $sql3 = "SELECT count(*) as qtdmed
           FROM alunotransfturma
            inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
            inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
            inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
            inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
            inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
            inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
           WHERE serie.ed11_i_ensino in ($ensino_fun_oito)
           AND seried.ed11_i_ensino in ($ensino_medio)
           AND calendario.ed52_i_ano = $anoant
           AND turma.ed57_i_escola = $censo_escola
           AND turma.ed57_i_turno in (1,2)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $pdf->cell(18,6,($qtdmed==0?"--":$qtdmed),"BL",1,"C",0);
 }else{
  $pdf->cell(18,6,"--","BL",1,"C",1);
 }
}
$pdf->cell(190,2,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

///////////////////////////////////////////////CAMPO 12
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  12- Movimento e rendimento escolar no ensino fundamental (regular) - Turno noturno - Em $anoant",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Matrícula inicial em $anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno

          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Admitidos após 30/03/$anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno

          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_d_datamatricula > '$anoant/03/30'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Afastados por abandono","R",2,"L",0);
$pdf->cell(46,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_c_situacao in ('CANCELADO','EVADIDO','FALECIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(46,3," Afastados por transferência","R",2,"L",0);
$pdf->cell(46,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(56);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_c_situacao in ('TRANSFERIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Aprovados sem dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4==0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Aprovados com dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4>0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(46,6," Reprovados","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $reprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal = 'R'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2>0){
    $reprov++;
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(18,6,($reprov==0?"--":$reprov),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 13
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  13- Alunos reclassificados após 30/3/$anoant - Turno noturno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  ",0,0,"L",0);
$pdf->cell(144,4,"  Etapa de destino em $anoant",0,1,"L",0);
$pdf->cell(46,1,"  ",0,0,"L",0);
$pdf->cell(144,1,"  ",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(46,4,"  Etapa de ingresso","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_oito)
          AND ed11_i_sequencia > 1
          ORDER BY ed11_i_sequencia
          LIMIT 8
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $pdf->cell(18,4,"  $ed11_c_descr","BL",0,"L",0);
}
$pdf->cell(18,4,"  Médio","BL",1,"L",0);
$sql_si = "SELECT ed11_i_codigo as codsi,ed11_c_descr as descrsi,ed11_i_sequencia as seqsi
           FROM serie
           WHERE ed11_i_ensino in ($ensino_fun_oito)
           ORDER BY ed11_i_sequencia
           LIMIT 8
         ";
$result_si = pg_query($sql_si);
$linhas_si = pg_num_rows($result_si);
for($x=0;$x<$linhas_si;$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 db_fieldsmemory($result_si,$x);
 $pdf->cell(46,6,"  $descrsi","BR",0,"L",0);
 $sql_sd = "SELECT ed11_i_codigo as codsd,ed11_i_sequencia as seqsd
            FROM serie
            WHERE ed11_i_ensino in ($ensino_fun_oito)
            AND ed11_i_sequencia > 1
            ORDER BY ed11_i_sequencia
            LIMIT 8
           ";
 $result_sd = pg_query($sql_sd);
 $linhas_sd = pg_num_rows($result_sd);
 for($z=0;$z<$linhas_sd;$z++){
  db_fieldsmemory($result_sd,$z);
  if($seqsi>=$seqsd){
   $pdf->cell(18,6,"--","BL",0,"C",1);
  }else{
   $sql3 = "SELECT count(*) as qtd
            FROM alunotransfturma
             inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
             inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
             inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
             inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
             inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
            WHERE serie.ed11_i_ensino in ($ensino_fun_oito)
            AND calendario.ed52_i_ano = $anoant
            AND turma.ed57_i_escola = $censo_escola
            AND turma.ed57_i_serie = $codsi
            AND turmad.ed57_i_serie = $codsd
            AND turma.ed57_i_turno in (3)
           ";
   $result3 = pg_query($sql3);
   db_fieldsmemory($result3,0);
   $pdf->cell(18,6,($qtd==0?"--":$qtd),"BL",0,"C",0);
  }
 }
 if($seqsi>4){
  $sql3 = "SELECT count(*) as qtdmed
           FROM alunotransfturma
            inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
            inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
            inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
            inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
            inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
            inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
           WHERE serie.ed11_i_ensino in ($ensino_fun_oito)
           AND seried.ed11_i_ensino in ($ensino_medio)
           AND calendario.ed52_i_ano = $anoant
           AND turma.ed57_i_escola = $censo_escola
           AND turma.ed57_i_turno in (3)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $pdf->cell(18,6,($qtdmed==0?"--":$qtdmed),"BL",1,"C",0);
 }else{
  $pdf->cell(18,6,"--","BL",1,"C",1);
 }
}
$pdf->cell(190,2,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

///////////////////////////////////////////////CAMPO 14
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  14- Total de concluintes no ensino fundamental (regular) por ano de nascimento e sexo - Em $anoant - Diurno e Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(40,4,"",0,0,"L",0);
$pdf->cell(60,4,"Concluintes diurno",0,0,"L",0);
$pdf->cell(20,4,"",0,0,"L",0);
$pdf->cell(60,4,"Concluintes noturno",0,1,"L",0);
$pdf->cell(40,1,"",0,0,"L",0);
$pdf->cell(60,1,"",0,0,"L",1);
$pdf->cell(20,1,"",0,0,"L",0);
$pdf->cell(60,1,"",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(40,4,"  Ano de nascimento","BR",0,"L",0);
$pdf->cell(30,4,"Masculino","BL",0,"C",0);
$pdf->cell(30,4,"Feminino","BLR",0,"C",0);
$pdf->cell(20,4,"",0,0,"L",0);
$pdf->cell(30,4,"Masculino","BL",0,"C",0);
$pdf->cell(30,4,"Feminino","BLR",1,"C",0);
$pdf->cell(190,2,"",0,1,"L",0);
$anoant = $censo_ano-1;
$ano_inicio = $censo_ano-14;
$ano_fim = $censo_ano-19;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(40,4,"  Após $ano_inicio","BR",0,"L",0);
  $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
           FROM historicomps
            inner join historico on ed61_i_codigo = ed62_i_historico
            inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
            inner join cursoedu on ed29_i_codigo = ed61_i_curso
            inner join aluno on ed47_i_codigo = ed61_i_aluno
           WHERE ed29_i_ensino in ($ensino_fun_oito)
           AND ed61_i_anoconc = $anoant
           AND ed62_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
           AND ed57_i_turno in (1,2)
           GROUP BY ed47_v_sexo
           ORDER BY ed47_v_sexo desc
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if($linhas1==0){
   $pdf->cell(30,4,"--","BL",0,"C",0);
   $pdf->cell(30,4,"--","BLR",0,"C",0);
  }else{
   if($linhas1==1){
    db_fieldsmemory($result1,0);
    $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
    $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
   }else{
    $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
    $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
   }
  }
  $pdf->cell(20,4,"",0,0,"L",0);
  $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
           FROM historicomps
            inner join historico on ed61_i_codigo = ed62_i_historico
            inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
            inner join cursoedu on ed29_i_codigo = ed61_i_curso
            inner join aluno on ed47_i_codigo = ed61_i_aluno
           WHERE ed29_i_ensino in ($ensino_fun_oito)
           AND ed61_i_anoconc = $anoant
           AND ed62_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
           AND ed57_i_turno in (3)
           GROUP BY ed47_v_sexo
           ORDER BY ed47_v_sexo desc
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if($linhas1==0){
   $pdf->cell(30,4,"--","BL",0,"C",0);
   $pdf->cell(30,4,"--","BLR",1,"C",0);
  }elseif($linhas1==1){
    db_fieldsmemory($result1,0);
    $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
    $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
  }else{
    $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
    $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
  }
  $pdf->cell(190,2,"",0,1,"L",0);
 }
 $pdf->cell(40,4,"  $x","BR",0,"L",0);
 $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
          FROM historicomps
           inner join historico on ed61_i_codigo = ed62_i_historico
           inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
           inner join cursoedu on ed29_i_codigo = ed61_i_curso
           inner join aluno on ed47_i_codigo = ed61_i_aluno
          WHERE ed29_i_ensino in ($ensino_fun_oito)
          AND ed61_i_anoconc = $anoant
          AND ed62_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
          AND ed57_i_turno in (1,2)
          GROUP BY ed47_v_sexo
          ORDER BY ed47_v_sexo desc
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"--","BL",0,"C",0);
  $pdf->cell(30,4,"--","BLR",0,"C",0);
 }else{
  if($linhas1==1){
   db_fieldsmemory($result1,0);
   $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
   $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
  }else{
   $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
   $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
  }
 }
 $pdf->cell(20,4,"",0,0,"L",0);
 $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
          FROM historicomps
           inner join historico on ed61_i_codigo = ed62_i_historico
           inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
           inner join cursoedu on ed29_i_codigo = ed61_i_curso
           inner join aluno on ed47_i_codigo = ed61_i_aluno
          WHERE ed29_i_ensino in ($ensino_fun_oito)
          AND ed61_i_anoconc = $anoant
          AND ed62_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
          AND ed57_i_turno in (3)
          GROUP BY ed47_v_sexo
          ORDER BY ed47_v_sexo desc
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"--","BL",0,"C",0);
  $pdf->cell(30,4,"--","BLR",1,"C",0);
 }elseif($linhas1==1){
   db_fieldsmemory($result1,0);
   $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
   $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
 }else{
   $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
   $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
 }
 $pdf->cell(190,2,"",0,1,"L",0);
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(40,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(40,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
             FROM historicomps
              inner join historico on ed61_i_codigo = ed62_i_historico
              inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
              inner join cursoedu on ed29_i_codigo = ed61_i_curso
              inner join aluno on ed47_i_codigo = ed61_i_aluno
             WHERE ed29_i_ensino in ($ensino_fun_oito)
             AND ed61_i_anoconc = $anoant
             AND ed62_i_escola = $censo_escola
             AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
             AND ed57_i_turno in (1,2)
             GROUP BY ed47_v_sexo
             ORDER BY ed47_v_sexo desc
            ";
    $result1 = pg_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if($linhas1==0){
     $pdf->cell(30,4,"--","BL",0,"C",0);
     $pdf->cell(30,4,"--","BLR",0,"C",0);
    }else{
     if($linhas1==1){
      db_fieldsmemory($result1,0);
      $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
      $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
     }else{
      $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
      $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
     }
    }
    $pdf->cell(20,4,"",0,0,"L",0);
    $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
             FROM historicomps
              inner join historico on ed61_i_codigo = ed62_i_historico
              inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
              inner join cursoedu on ed29_i_codigo = ed61_i_curso
              inner join aluno on ed47_i_codigo = ed61_i_aluno
             WHERE ed29_i_ensino in ($ensino_fun_oito)
             AND ed61_i_anoconc = $anoant
             AND ed62_i_escola = $censo_escola
             AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
             AND ed57_i_turno in (3)
             GROUP BY ed47_v_sexo
             ORDER BY ed47_v_sexo desc
            ";
    $result1 = pg_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if($linhas1==0){
     $pdf->cell(30,4,"--","BL",0,"C",0);
     $pdf->cell(30,4,"--","BLR",1,"C",0);
    }elseif($linhas1==1){
      db_fieldsmemory($result1,0);
      $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
      $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
    }else{
      $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
      $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
    }
    $pdf->cell(190,2,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(40,4,"  TOTAL","BR",0,"L",0);
$sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
         FROM historicomps
          inner join historico on ed61_i_codigo = ed62_i_historico
          inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
          inner join cursoedu on ed29_i_codigo = ed61_i_curso
          inner join aluno on ed47_i_codigo = ed61_i_aluno
         WHERE ed29_i_ensino in ($ensino_fun_oito)
         AND ed61_i_anoconc = $anoant
         AND ed62_i_escola = $censo_escola
         AND ed57_i_turno in (1,2)
         GROUP BY ed47_v_sexo
         ORDER BY ed47_v_sexo desc
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
if($linhas1==0){
 $pdf->cell(30,4,"--","BL",0,"C",0);
 $pdf->cell(30,4,"--","BLR",0,"C",0);
}else{
 if($linhas1==1){
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
  $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
 }else{
  $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
  $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
 }
}
$pdf->cell(20,4,"",0,0,"L",0);
$sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
         FROM historicomps
          inner join historico on ed61_i_codigo = ed62_i_historico
          inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
          inner join cursoedu on ed29_i_codigo = ed61_i_curso
          inner join aluno on ed47_i_codigo = ed61_i_aluno
         WHERE ed29_i_ensino in ($ensino_fun_oito)
         AND ed61_i_anoconc = $anoant
         AND ed62_i_escola = $censo_escola
         AND ed57_i_turno in (3)
         GROUP BY ed47_v_sexo
         ORDER BY ed47_v_sexo desc
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
if($linhas1==0){
 $pdf->cell(30,4,"--","BL",0,"C",0);
 $pdf->cell(30,4,"--","BLR",1,"C",0);
}elseif($linhas1==1){
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
  $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
}else{
  $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
  $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);

/////////////////////////////////////////////////////////////////ENSINO FUNDAMENTAL 9 ANOS

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',13);
$pdf->cell(30,7,"  BLOCO 5 - ","LBT",0,"R",0);
$pdf->setfont('arial','B',10);
$pdf->cell(160,7,"Ensino Fundamental (Regular) 9 anos","RBT",1,"L",0);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 15
$pdf->cell(190,4,"  15- Número de turmas (T) e matrícula inicial (MI) no ensino fundamental (regular) em nove anos, por ano de escolarização - Em $censo_ano",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$sql1 = "SELECT min(ed17_h_inicio) as inicio,
                max(ed17_h_fim) as termino,
                ed17_i_turno
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join calendario on ed52_i_codigo = ed57_i_calendario
          inner join regencia on ed57_i_codigo = ed59_i_turma
          inner join regenciahorario on ed58_i_regencia = ed59_i_codigo
          inner join periodoescola on ed17_i_codigo = ed58_i_periodo
          inner join serie on ed11_i_codigo = ed57_i_serie
         WHERE ed11_i_ensino in ($ensino_fun_nove)
         AND ed52_i_ano = $censo_ano
         AND ed57_i_escola = $censo_escola and ed58_ativo is true  
         GROUP BY ed17_i_turno
         ORDER BY inicio,termino
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
$pdf->setfont('arial','',8);
$pdf->cell(55,4,"  Horário de Funcionamento","BR",0,"L",0);
$pdf->setfont('arial','',9);
$sql2 = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_nove)
         ORDER BY ed11_i_sequencia
         LIMIT 9
        ";
$result2 = pg_query($sql2);
$linhas2 = pg_num_rows($result2);
for($x=0;$x<$linhas2;$x++){
 db_fieldsmemory($result2,$x);
 if($x==($linhas2-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
for($x=0;$x<$linhas1;$x++){
 db_fieldsmemory($result1,$x);
 $pdf->cell(190,2,"",0,1,"L",0);
 $inicY = $pdf->getY();
 $inicX = 65;
 $pdf->cell(22,4,"Início",0,0,"C",0);
 $pdf->cell(23,4,"Término",0,0,"C",0);
 $pdf->setfont('arial','B',9);
 $pdf->cell(10,4,"T","R",1,"C",0);
 $pdf->cell(30,2,"",0,1,"C",0);
 $pdf->setfont('arial','',9);
 $pdf->cell(22,4,"$inicio","B",0,"C",0);
 $pdf->cell(23,4,"$termino","B",0,"C",0);
 $pdf->setfont('arial','B',9);
 $pdf->cell(10,4,"MI","BR",1,"C",0);
 $pdf->setfont('arial','',9);
 for($z=0;$z<$linhas2;$z++){
  db_fieldsmemory($result2,$z);
  if($z==($linhas2-1)){
   $qb = 1;
  }else{
   $qb = 2;
  }
  $sql3 = "SELECT ed57_i_codigo,count(ed60_i_codigo)/2 as matr
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join regencia on ed57_i_codigo = ed59_i_turma
            inner join regenciahorario on ed58_i_regencia = ed59_i_codigo
            inner join periodoescola on ed17_i_codigo = ed58_i_periodo
            inner join serie on ed11_i_codigo = ed57_i_serie
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND (ed17_h_inicio = '$inicio' OR ed17_h_fim = '$termino')
           AND ed57_i_serie = $codserie and ed58_ativo is true  
           GROUP BY ed57_i_codigo
          ";
  $result3 = pg_query($sql3);
  $linhas3 = pg_num_rows($result3);
  $pdf->setY($inicY);
  $pdf->setX($inicX);
  $pdf->cell(15,4,($linhas3==0?"--":$linhas3),"BL",2,"C",0);
  $pdf->cell(15,2,"",0,2,"C",0);
  $soma_mat = 0;
  for($y=0;$y<$linhas3;$y++){
   db_fieldsmemory($result3,$y);
   $soma_mat += $matr;
  }
  $pdf->cell(15,4,($soma_mat==0?"--":$soma_mat),"BL",$qb,"C",0);
  $inicX += 15;
 }
}
$pdf->cell(190,2,"",0,1,"L",0);
$inicY = $pdf->getY();
$inicX = 65;
$pdf->cell(45,4,"",0,0,"C",0);
$pdf->setfont('arial','B',9);
$pdf->cell(10,4,"T","R",1,"C",0);
$pdf->cell(55,2,"",0,1,"C",0);
$pdf->setfont('arial','',9);
$pdf->cell(45,4,"TOTAL","B",0,"C",0);
$pdf->setfont('arial','B',9);
$pdf->cell(10,4,"MI","BR",1,"C",0);
$pdf->setfont('arial','',9);
for($z=0;$z<$linhas2;$z++){
 db_fieldsmemory($result2,$z);
 if($z==($linhas2-1)){
  $qb = 1;
 }else{
  $qb = 2;
 }
 $sql3 = "SELECT ed57_i_codigo,count(ed60_i_codigo) as matr
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join serie on ed11_i_codigo = ed57_i_serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          GROUP BY ed57_i_codigo
         ";
 $result3 = pg_query($sql3);
 $linhas3 = pg_num_rows($result3);
 $pdf->setY($inicY);
 $pdf->setX($inicX);
 $pdf->setfont('arial','B',9);
 $pdf->cell(15,4,($linhas3==0?"--":$linhas3),"BL",2,"C",0);
 $pdf->cell(15,2,"",0,2,"C",0);
 $soma_mat = 0;
 for($y=0;$y<$linhas3;$y++){
  db_fieldsmemory($result3,$y);
  $soma_mat += $matr;
 }
 $pdf->cell(15,4,($soma_mat==0?"--":$soma_mat),"BL",$qb,"C",0);
 $inicX += 15;
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 16
$pdf->cell(190,4,"  16- Matrícula inicial no ensino fundamental (regular) em nove anos, por ano de escolarização e ano de nascimento - Em $censo_ano - Diurno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4," Ano de nascimento","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_nove)
         ORDER BY ed11_i_sequencia
         LIMIT 9
        ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$ano_inicio = $censo_ano-6;
$ano_fim = $censo_ano-19;
$pula = 1;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(55,4,"  Após $ano_inicio","BR",0,"L",0);
  for($z=0;$z<$linhas_s;$z++){
   db_fieldsmemory($result_s,$z);
   $sql1 = "SELECT count(ed60_i_codigo) as qtdmais
            FROM matricula
             inner join turma on ed57_i_codigo = ed60_i_turma
             inner join serie on ed11_i_codigo = ed57_i_serie
             inner join calendario on ed52_i_codigo = ed57_i_calendario
             inner join aluno on ed47_i_codigo = ed60_i_aluno

            WHERE ed11_i_ensino in ($ensino_fun_nove)
            AND ed52_i_ano = $censo_ano
            AND ed57_i_escola = $censo_escola
            AND extract(year from ed47_d_nasc) > $x
            AND ed57_i_serie = $codserie
            AND ed57_i_turno in (1,2)
           ";
   $result1 = pg_query($sql1);
   db_fieldsmemory($result1,0);
   if($z==($linhas_s-1)){
    $qb = 1;
   }else{
    $qb = 0;
   }
   if($z>$pula){
    $qtdmais = "--";
    $fill = 1;
   }else{
    $qtdmais = $qtdmais;
    $fill = 0;
   }
   $pdf->cell(15,4,($qtdmais==0?"--":$qtdmais),"BL",$qb,"C",$fill);
  }
  $pula++;
  $pdf->cell(190,3,"",0,1,"L",0);
 }
 $pdf->cell(55,4,"  $x","BR",0,"L",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql3 = "SELECT count(ed60_i_codigo) as qtdigual
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) = $x
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  if($z>$pula){
   $qtdigual = "--";
   $fill = 1;
  }else{
   $qtdigual = $qtdigual;
   $fill = 0;
  }
  $pdf->cell(15,4,($qtdigual==0?"--":$qtdigual),"BL",$qb,"C",$fill);
 }
 $pdf->cell(190,3,"",0,1,"L",0);
 $pula++;
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(55,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(55,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    for($z=0;$z<$linhas_s;$z++){
     db_fieldsmemory($result_s,$z);
     $sql1 = "SELECT count(ed60_i_codigo) as qtdmenos
              FROM matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
               inner join serie on ed11_i_codigo = ed57_i_serie
               inner join calendario on ed52_i_codigo = ed57_i_calendario
               inner join aluno on ed47_i_codigo = ed60_i_aluno
              WHERE ed11_i_ensino in ($ensino_fun_nove)
              AND ed52_i_ano = $censo_ano
              AND ed57_i_escola = $censo_escola
              AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
              AND ed57_i_serie = $codserie
              AND ed57_i_turno in (1,2)
             ";
     $result1 = pg_query($sql1);
     db_fieldsmemory($result1,0);
     if($z==($linhas_s-1)){
      $qb = 1;
     }else{
      $qb = 0;
     }
     if($z>$pula){
      $qtdmenos = "--";
      $fill = 1;
     }else{
      $qtdmenos = $qtdmenos;
      $fill = 0;
     }
     $pdf->cell(15,4,($qtdmenos==0?"--":$qtdmenos),"BL",$qb,"C",$fill);
    }
    $pdf->cell(190,3,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(55,4,"  TOTAL","BR",0,"L",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as total
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,($total==0?"--":$total),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 17
        $pdf->cell(190,4,"  17- Matrícula inicial no ensino fundamental (regular) em nove anos, por ano de escolarização e ano de nascimento - Em $censo_ano - Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4," Ano de nascimento","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
         FROM serie
         WHERE ed11_i_ensino in ($ensino_fun_nove)
         ORDER BY ed11_i_sequencia
         LIMIT 9
        ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$ano_dis = $censo_ano - 11;
$ano_inicio = $censo_ano-6;
$ano_fim = $censo_ano-19;
$pula = 0;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(55,4,"  Após $ano_inicio","BR",0,"L",0);
  for($z=0;$z<$linhas_s;$z++){
   db_fieldsmemory($result_s,$z);
   $sql1 = "SELECT count(ed60_i_codigo) as qtdmais
            FROM matricula
             inner join turma on ed57_i_codigo = ed60_i_turma
             inner join serie on ed11_i_codigo = ed57_i_serie
             inner join calendario on ed52_i_codigo = ed57_i_calendario
             inner join aluno on ed47_i_codigo = ed60_i_aluno
            WHERE ed11_i_ensino in ($ensino_fun_nove)
            AND ed52_i_ano = $censo_ano
            AND ed57_i_escola = $censo_escola
            AND extract(year from ed47_d_nasc) > $x
            AND ed57_i_serie = $codserie
            AND ed57_i_turno in (3)
           ";
   $result1 = pg_query($sql1);
   db_fieldsmemory($result1,0);
   if($z==($linhas_s-1)){
    $qb = 1;
   }else{
    $qb = 0;
   }
   if($x>$ano_dis){
    $qtdmais = "--";
    $fill = 1;
   }else{
    $qtdmais = $qtdmais;
    $fill = 0;
   }
   $pdf->cell(15,4,($qtdmais==0?"--":$qtdmais),"BL",$qb,"C",$fill);
  }
  $pdf->cell(190,3,"",0,1,"L",0);
 }
 $pdf->cell(55,4,"  $x","BR",0,"L",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql3 = "SELECT count(ed60_i_codigo) as qtdigual
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) = $x
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  if($x>$ano_dis){
   $qtdigual = "--";
   $fill = 1;
  }else{
   if($pula==8){
    $qtdigual = "--";
    $fill = 1;
   }else{
    $qtdigual = $qtdigual;
    $fill = 0;
   }
   $pula++;
  }
  $pdf->cell(15,4,($qtdigual==0?"--":$qtdigual),"BL",$qb,"C",$fill);
 }
 $pdf->cell(190,3,"",0,1,"L",0);
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(55,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(55,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    for($z=0;$z<$linhas_s;$z++){
     db_fieldsmemory($result_s,$z);
     $sql1 = "SELECT count(ed60_i_codigo) as qtdmenos
              FROM matricula
               inner join turma on ed57_i_codigo = ed60_i_turma
               inner join serie on ed11_i_codigo = ed57_i_serie
               inner join calendario on ed52_i_codigo = ed57_i_calendario
               inner join aluno on ed47_i_codigo = ed60_i_aluno
              WHERE ed11_i_ensino in ($ensino_fun_nove)
              AND ed52_i_ano = $censo_ano
              AND ed57_i_escola = $censo_escola
              AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
              AND ed57_i_serie = $codserie
              AND ed57_i_turno in (3)
             ";
     $result1 = pg_query($sql1);
     db_fieldsmemory($result1,0);
     if($z==($linhas_s-1)){
      $qb = 1;
     }else{
      $qb = 0;
     }
     if($x>$ano_dis){
      $qtdmenos = "--";
      $fill = 1;
     }else{
      $qtdmenos = $qtdmenos;
      $fill = 0;
     }
     $pdf->cell(15,4,($qtdmenos==0?"--":$qtdmenos),"BL",$qb,"C",$fill);
    }
    $pdf->cell(190,3,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(55,4,"  TOTAL","BR",0,"L",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as total
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,($total==0?"--":$total),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 18
$pdf->cell(190,4,"  18- Matrícula inicial no ensino fundamental (regular) em nove anos, por ano de escolarização, sexo e cor/raça - Em $censo_ano - Diurno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(20,4,"Sexo","BR",0,"C",0);
$pdf->cell(35,4,"Cor/Raça","BR",0,"C",0);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Masculino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');

for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(55,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'M'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Feminino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(55,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'F'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (1,2)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"TOTAL ","BR",0,"R",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 19
$pdf->cell(190,4,"  19- Matrícula Inicial no ensino fundamental (regular) em nove anos, por ano de escolarização, sexo e cor/raça - Em $censo_ano - Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(20,4,"Sexo","BR",0,"C",0);
$pdf->cell(35,4,"Cor/Raça","BR",0,"C",0);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Masculino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');

for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(55,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'M'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,4,"Feminino","BT",1,"L",0);
$pdf->setfont('arial','',9);
$racas = array('BRANCA','PRETA','PARDA','AMARELA','INDÍGENA','NÃO DECLARADA');
for($x=0;$x<count($racas);$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 $pdf->cell(55,4,"$racas[$x] ","BR",0,"R",0);
 for($z=0;$z<$linhas_s;$z++){
  db_fieldsmemory($result_s,$z);
  $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
           FROM matricula
            inner join turma on ed57_i_codigo = ed60_i_turma
            inner join serie on ed11_i_codigo = ed57_i_serie
            inner join calendario on ed52_i_codigo = ed57_i_calendario
            inner join aluno on ed47_i_codigo = ed60_i_aluno
           WHERE ed11_i_ensino in ($ensino_fun_nove)
           AND ed52_i_ano = $censo_ano
           AND ed57_i_escola = $censo_escola
           AND trim(ed47_c_raca) = '$racas[$x]'
           AND ed47_v_sexo = 'F'
           AND ed57_i_serie = $codserie
           AND ed57_i_turno in (3)
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  db_fieldsmemory($result1,0);
  if($z==($linhas_s-1)){
   $qb = 1;
  }else{
   $qb = 0;
  }
  $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"TOTAL ","BR",0,"R",0);
for($z=0;$z<$linhas_s;$z++){
 db_fieldsmemory($result_s,$z);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($z==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

$pdf->setfont('arial','B',8);
///////////////////////////////////////////////CAMPO 21
$pdf->cell(190,4,"  21- Matrícula inicial, em $censo_ano, no ensino fundamental (regular) em nove anos, de alunos promovidos, repetentes, provenientes.",0,1,"L",1);
$pdf->cell(190,4,"  da Educação de Jovens e Adultos e que não freqüentaram escola em ".($censo_ano-1).".",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Número de alunos promovidos","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND (ed60_c_rfanterior = 'A' OR ed60_c_rfanterior = '')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Número de alunos repetentes","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $censo_ano
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed60_c_rfanterior = 'R'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Matrícula Inicial em $censo_ano, de alunos","R",2,"L",0);
$pdf->cell(55,3," que freqüentaram a EJA em ".($censo_ano-1),"BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma
           inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
           inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
           inner join aluno on aluno.ed47_i_codigo = matricula.ed60_i_aluno
           left join turma as turmaant on turmaant.ed57_i_codigo = matricula.ed60_i_turmaant
           left join serie as serieant on serieant.ed11_i_codigo = turmaant.ed57_i_serie
          WHERE serie.ed11_i_ensino in ($ensino_fun_nove)
          AND calendario.ed52_i_ano = $censo_ano
          AND turma.ed57_i_escola = $censo_escola
          AND turma.ed57_i_serie = $codserie
          AND serieant.ed11_i_ensino in ($ensino_fun_eja)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Matrícula Inicial em $censo_ano, de alunos","R",2,"L",0);
$pdf->cell(55,3," que não freqüentaram escola em ".($censo_ano-1),"BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,"--","BL",$qb,"C",0);
}
$pdf->cell(190,3,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 22
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  22- Movimento e rendimento escolar no ensino fundamental (regular) em nove anos por turno - Em $anoant - Diurno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Matrícula inicial em $anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Admitidos após 30/03/$anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_d_datamatricula > '$anoant/03/30'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Afastados por abandono","R",2,"L",0);
$pdf->cell(55,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_c_situacao in ('CANCELADO','EVADIDO','FALECIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Afastados por transferência","R",2,"L",0);
$pdf->cell(55,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
          AND ed60_c_situacao in ('TRANSFERIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Aprovados sem dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4==0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Aprovados com dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4>0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Reprovados","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $reprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (1,2)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal = 'R'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2>0){
    $reprov++;
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($reprov==0?"--":$reprov),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 23
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  23- Alunos reclassificados após 30/3/$anoant em nove anos por turno - Turno diurno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  ",0,0,"L",0);
$pdf->cell(135,4,"  Etapa de destino em $anoant",0,1,"L",0);
$pdf->cell(55,1,"  ",0,0,"L",0);
$pdf->cell(135,1,"  ",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  Ano de ingresso","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed11_i_sequencia > 1
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $pdf->cell(15,4,"  $ed11_c_descr","BL",0,"L",0);
}
$pdf->cell(15,4,"  Médio","BL",1,"L",0);
$sql_si = "SELECT ed11_i_codigo as codsi,ed11_c_descr as descrsi,ed11_i_sequencia as seqsi
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_si = pg_query($sql_si);
$linhas_si = pg_num_rows($result_si);
for($x=0;$x<$linhas_si;$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 db_fieldsmemory($result_si,$x);
 $pdf->cell(55,6,"  $descrsi","BR",0,"L",0);
 $sql_sd = "SELECT ed11_i_codigo as codsd,ed11_i_sequencia as seqsd
            FROM serie
            WHERE ed11_i_ensino in ($ensino_fun_nove)
            AND ed11_i_sequencia > 1
            ORDER BY ed11_i_sequencia
            LIMIT 9
           ";
 $result_sd = pg_query($sql_sd);
 $linhas_sd = pg_num_rows($result_sd);
 for($z=0;$z<$linhas_sd;$z++){
  db_fieldsmemory($result_sd,$z);
  if($seqsi>=$seqsd){
   $pdf->cell(15,6,"--","BL",0,"C",1);
  }else{
   $sql3 = "SELECT count(*) as qtd
            FROM alunotransfturma
             inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
             inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
             inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
             inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
             inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
            WHERE serie.ed11_i_ensino in ($ensino_fun_nove)
            AND calendario.ed52_i_ano = $anoant
            AND turma.ed57_i_escola = $censo_escola
            AND turma.ed57_i_serie = $codsi
            AND turmad.ed57_i_serie = $codsd
            AND turma.ed57_i_turno in (1,2)
           ";
   $result3 = pg_query($sql3);
   db_fieldsmemory($result3,0);
   $pdf->cell(15,6,($qtd==0?"--":$qtd),"BL",0,"C",0);
  }
 }
 if($seqsi>4){
  $sql3 = "SELECT count(*) as qtdmed
           FROM alunotransfturma
            inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
            inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
            inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
            inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
            inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
            inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
           WHERE serie.ed11_i_ensino in ($ensino_fun_nove)
           AND seried.ed11_i_ensino in ($ensino_medio)
           AND calendario.ed52_i_ano = $anoant
           AND turma.ed57_i_escola = $censo_escola
           AND turma.ed57_i_turno in (1,2)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $pdf->cell(15,6,($qtdmed==0?"--":$qtdmed),"BL",1,"C",0);
 }else{
  $pdf->cell(15,6,"--","BL",1,"C",1);
 }
}
$pdf->cell(190,2,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

///////////////////////////////////////////////CAMPO 24
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  24- Movimento e rendimento escolar no ensino fundamental (regular) em nove anos por turno - Em $anoant - Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  ","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo as codserie,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,4,"  $ed11_c_descr","BL",$qb,"L",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Matrícula inicial em $anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->SetFillColor(0);
$pdf->cell(190,1,"",0,1,"L",1);
$pdf->SetFillColor(190);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Admitidos após 30/03/$anoant","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_d_datamatricula > '$anoant/03/30'
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Afastados por abandono","R",2,"L",0);
$pdf->cell(55,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_c_situacao in ('CANCELADO','EVADIDO','FALECIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$posy = $pdf->getY();
$pdf->cell(55,3," Afastados por transferência","R",2,"L",0);
$pdf->cell(55,3," após 30/03/$anoant","BR",2,"L",0);
$pdf->setfont('arial','',9);
$pdf->sety($posy);
$pdf->setX(65);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $sql1 = "SELECT count(ed60_i_codigo) as qtdmasc
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
          AND ed60_c_situacao in ('TRANSFERIDO')
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 db_fieldsmemory($result1,0);
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($qtdmasc==0?"--":$qtdmasc),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Aprovados sem dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4==0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Aprovados com dependência","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $aprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql3 = "SELECT min(ed43_i_sequencia) as seq
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
            inner join diarioresultado on ed73_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed73_i_procresultado
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed95_i_escola = $censo_escola
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $procseq = $seq==""?0:$seq;
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal != 'A'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2==0){
   $sql4 = "SELECT ed73_c_aprovmin
            FROM diario
             inner join calendario on ed52_i_codigo = ed95_i_calendario
             inner join aluno on ed47_i_codigo = ed95_i_aluno

             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join diarioresultado on ed73_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed73_i_procresultado
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed52_i_ano = $anoant
            AND ed95_i_serie = $codserie
            AND ed73_c_aprovmin != 'S'
            AND ed95_i_escola = $censo_escola
            AND ed43_i_sequencia = $procseq
           ";
   $result4 = pg_query($sql4);
   $linhas4 = pg_num_rows($result4);
   if($linhas4>0){
    $aprov++;
   }
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($aprov==0?"--":$aprov),"BL",$qb,"C",0);
}

$pdf->cell(190,2,"",0,1,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(55,6," Reprovados","BR",0,"L",0);
$pdf->setfont('arial','',9);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $reprov = 0;
 $sql1 = "SELECT ed60_i_aluno
          FROM matricula
           inner join turma on ed57_i_codigo = ed60_i_turma
           inner join serie on ed11_i_codigo = ed57_i_serie
           inner join calendario on ed52_i_codigo = ed57_i_calendario
           inner join aluno on ed47_i_codigo = ed60_i_aluno
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed52_i_ano = $anoant
          AND ed57_i_escola = $censo_escola
          AND ed57_i_serie = $codserie
          AND ed57_i_turno in (3)
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 for($z=0;$z<$linhas1;$z++){
  db_fieldsmemory($result1,$z);
  $sql2 = "SELECT ed95_i_codigo
           FROM diario
            inner join calendario on ed52_i_codigo = ed95_i_calendario
            inner join aluno on ed47_i_codigo = ed95_i_aluno

            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed52_i_ano = $anoant
           AND ed95_i_serie = $codserie
           AND ed74_c_resultadofinal = 'R'
           AND ed95_i_escola = $censo_escola
          ";
  $result2 = pg_query($sql2);
  $linhas2 = pg_num_rows($result2);
  if($linhas2>0){
    $reprov++;
  }
 }
 if($x==($linhas_s-1)){
  $qb = 1;
 }else{
  $qb = 0;
 }
 $pdf->cell(15,6,($reprov==0?"--":$reprov),"BL",$qb,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
///////////////////////////////////////////////CAMPO 25
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  25- Alunos reclassificados após 30/3/$anoant em nove anos por turno - Turno Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  ",0,0,"L",0);
$pdf->cell(135,4,"  Etapa de destino em $anoant",0,1,"L",0);
$pdf->cell(55,1,"  ",0,0,"L",0);
$pdf->cell(135,1,"  ",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(55,4,"  Etapa de ingresso","BR",0,"L",0);
$sql_s = "SELECT ed11_i_codigo,ed11_c_descr
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          AND ed11_i_sequencia > 1
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_s = pg_query($sql_s);
$linhas_s = pg_num_rows($result_s);
for($x=0;$x<$linhas_s;$x++){
 db_fieldsmemory($result_s,$x);
 $pdf->cell(15,4,"  $ed11_c_descr","BL",0,"L",0);
}
$pdf->cell(15,4,"  Médio","BL",1,"L",0);
$sql_si = "SELECT ed11_i_codigo as codsi,ed11_c_descr as descrsi,ed11_i_sequencia as seqsi
          FROM serie
          WHERE ed11_i_ensino in ($ensino_fun_nove)
          ORDER BY ed11_i_sequencia
          LIMIT 9
         ";
$result_si = pg_query($sql_si);
$linhas_si = pg_num_rows($result_si);
for($x=0;$x<$linhas_si;$x++){
 $pdf->cell(190,2,"",0,1,"L",0);
 db_fieldsmemory($result_si,$x);
 $pdf->cell(55,6,"  $descrsi","BR",0,"L",0);
 $sql_sd = "SELECT ed11_i_codigo as codsd,ed11_i_sequencia as seqsd
            FROM serie
            WHERE ed11_i_ensino in ($ensino_fun_nove)
            AND ed11_i_sequencia > 1
            ORDER BY ed11_i_sequencia
            LIMIT 8
           ";
 $result_sd = pg_query($sql_sd);
 $linhas_sd = pg_num_rows($result_sd);
 for($z=0;$z<$linhas_sd;$z++){
  db_fieldsmemory($result_sd,$z);
  if($seqsi>=$seqsd){
   $pdf->cell(15,6,"--","BL",0,"C",1);
  }else{
   $sql3 = "SELECT count(*) as qtd
            FROM alunotransfturma
             inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
             inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
             inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
             inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
             inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
            WHERE serie.ed11_i_ensino in ($ensino_fun_nove)
            AND calendario.ed52_i_ano = $anoant
            AND turma.ed57_i_escola = $censo_escola
            AND turma.ed57_i_serie = $codsi
            AND turmad.ed57_i_serie = $codsd
            AND turma.ed57_i_turno in (3)
           ";
   $result3 = pg_query($sql3);
   db_fieldsmemory($result3,0);
   $pdf->cell(15,6,($qtd==0?"--":$qtd),"BL",0,"C",0);
  }
 }
 if($seqsi>4){
  $sql3 = "SELECT count(*) as qtdmed
           FROM alunotransfturma
            inner join turma on turma.ed57_i_codigo = alunotransfturma.ed69_i_turmaorigem
            inner join turma as turmad on turmad.ed57_i_codigo = alunotransfturma.ed69_i_turmadestino
            inner join serie on serie.ed11_i_codigo = turma.ed57_i_serie
            inner join serie as seried on seried.ed11_i_codigo = turmad.ed57_i_serie
            inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
            inner join calendario as calendariod on calendariod.ed52_i_codigo = turmad.ed57_i_calendario
           WHERE serie.ed11_i_ensino in ($ensino_fun_nove)
           AND seried.ed11_i_ensino in ($ensino_medio)
           AND calendario.ed52_i_ano = $anoant
           AND turma.ed57_i_escola = $censo_escola
           AND turma.ed57_i_turno in (3)
          ";
  $result3 = pg_query($sql3);
  db_fieldsmemory($result3,0);
  $pdf->cell(15,6,($qtdmed==0?"--":$qtdmed),"BL",1,"C",0);
 }else{
  $pdf->cell(15,6,"--","BL",1,"C",1);
 }
}
$pdf->cell(190,2,"",0,1,"L",0);

$pdf->addpage('P');
$pdf->ln(5);

///////////////////////////////////////////////CAMPO 26
$anoant = $censo_ano-1;
$pdf->setfont('arial','B',8);
$pdf->cell(190,4,"  26- Total de concluintes no ensino fundamental (regular) em nove anos por ano de nascimento e sexo - Em $anoant - Diurno e Noturno",0,1,"L",1);
$pdf->setfont('arial','',9);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(40,4,"",0,0,"L",0);
$pdf->cell(60,4,"Concluintes diurno",0,0,"L",0);
$pdf->cell(20,4,"",0,0,"L",0);
$pdf->cell(60,4,"Concluintes noturno",0,1,"L",0);
$pdf->cell(40,1,"",0,0,"L",0);
$pdf->cell(60,1,"",0,0,"L",1);
$pdf->cell(20,1,"",0,0,"L",0);
$pdf->cell(60,1,"",0,1,"L",1);
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->cell(40,4,"  Ano de nascimento","BR",0,"L",0);
$pdf->cell(30,4,"Masculino","BL",0,"C",0);
$pdf->cell(30,4,"Feminino","BLR",0,"C",0);
$pdf->cell(20,4,"",0,0,"L",0);
$pdf->cell(30,4,"Masculino","BL",0,"C",0);
$pdf->cell(30,4,"Feminino","BLR",1,"C",0);
$pdf->cell(190,2,"",0,1,"L",0);
$anoant = $censo_ano-1;
$ano_inicio = $censo_ano-14;
$ano_fim = $censo_ano-19;
for($x=$ano_inicio;$x>=$ano_fim;$x--){
 if($x==$ano_inicio){
  $pdf->cell(40,4,"  Após $ano_inicio","BR",0,"L",0);
  $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
           FROM historicomps
            inner join historico on ed61_i_codigo = ed62_i_historico
            inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
            inner join cursoedu on ed29_i_codigo = ed61_i_curso
            inner join aluno on ed47_i_codigo = ed61_i_aluno
           WHERE ed29_i_ensino in ($ensino_fun_nove)
           AND ed61_i_anoconc = $anoant
           AND ed62_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
           AND ed57_i_turno in (1,2)
           GROUP BY ed47_v_sexo
           ORDER BY ed47_v_sexo desc
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if($linhas1==0){
   $pdf->cell(30,4,"--","BL",0,"C",0);
   $pdf->cell(30,4,"--","BLR",0,"C",0);
  }else{
   if($linhas1==1){
    db_fieldsmemory($result1,0);
    $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
    $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
   }else{
    $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
    $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
   }
  }
  $pdf->cell(20,4,"",0,0,"L",0);
  $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
           FROM historicomps
            inner join historico on ed61_i_codigo = ed62_i_historico
            inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
            inner join cursoedu on ed29_i_codigo = ed61_i_curso
            inner join aluno on ed47_i_codigo = ed61_i_aluno
           WHERE ed29_i_ensino in ($ensino_fun_nove)
           AND ed61_i_anoconc = $anoant
           AND ed62_i_escola = $censo_escola
           AND extract(year from ed47_d_nasc) > $x
           AND ed57_i_turno in (3)
           GROUP BY ed47_v_sexo
           ORDER BY ed47_v_sexo desc
          ";
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  if($linhas1==0){
   $pdf->cell(30,4,"--","BL",0,"C",0);
   $pdf->cell(30,4,"--","BLR",1,"C",0);
  }elseif($linhas1==1){
    db_fieldsmemory($result1,0);
    $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
    $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
  }else{
    $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
    $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
  }
  $pdf->cell(190,2,"",0,1,"L",0);
 }
 $pdf->cell(40,4,"  $x","BR",0,"L",0);
 $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
          FROM historicomps
           inner join historico on ed61_i_codigo = ed62_i_historico
           inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
           inner join cursoedu on ed29_i_codigo = ed61_i_curso
           inner join aluno on ed47_i_codigo = ed61_i_aluno
          WHERE ed29_i_ensino in ($ensino_fun_nove)
          AND ed61_i_anoconc = $anoant
          AND ed62_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
          AND ed57_i_turno in (1,2)
          GROUP BY ed47_v_sexo
          ORDER BY ed47_v_sexo desc
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"--","BL",0,"C",0);
  $pdf->cell(30,4,"--","BLR",0,"C",0);
 }else{
  if($linhas1==1){
   db_fieldsmemory($result1,0);
   $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
   $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
  }else{
   $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
   $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
  }
 }
 $pdf->cell(20,4,"",0,0,"L",0);
 $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
          FROM historicomps
           inner join historico on ed61_i_codigo = ed62_i_historico
           inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
           inner join cursoedu on ed29_i_codigo = ed61_i_curso
           inner join aluno on ed47_i_codigo = ed61_i_aluno
          WHERE ed29_i_ensino in ($ensino_fun_nove)
          AND ed61_i_anoconc = $anoant
          AND ed62_i_escola = $censo_escola
          AND extract(year from ed47_d_nasc) = $x
          AND ed57_i_turno in (3)
          GROUP BY ed47_v_sexo
          ORDER BY ed47_v_sexo desc
         ";
 $result1 = pg_query($sql1);
 $linhas1 = pg_num_rows($result1);
 if($linhas1==0){
  $pdf->cell(30,4,"--","BL",0,"C",0);
  $pdf->cell(30,4,"--","BLR",1,"C",0);
 }elseif($linhas1==1){
   db_fieldsmemory($result1,0);
   $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
   $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
 }else{
   $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
   $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
 }
 $pdf->cell(190,2,"",0,1,"L",0);
 if($x==$ano_fim){
  $ano_base = $ano_fim;
  for($t=0;$t<5;$t++){
    if($t==4){
     $ano_comeco = $ano_base;
     $ano_final = $ano_base-100;
     $pdf->cell(40,4,"  Antes de $ano_comeco","BR",0,"L",0);
    }else{
     $ano_comeco = $ano_base-1;
     $ano_final = $ano_base-5;
     $pdf->cell(40,4,"  De $ano_comeco a $ano_final","BR",0,"L",0);
    }
    $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
             FROM historicomps
              inner join historico on ed61_i_codigo = ed62_i_historico
              inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
              inner join cursoedu on ed29_i_codigo = ed61_i_curso
              inner join aluno on ed47_i_codigo = ed61_i_aluno
             WHERE ed29_i_ensino in ($ensino_fun_nove)
             AND ed61_i_anoconc = $anoant
             AND ed62_i_escola = $censo_escola
             AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
             AND ed57_i_turno in (1,2)
             GROUP BY ed47_v_sexo
             ORDER BY ed47_v_sexo desc
            ";
    $result1 = pg_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if($linhas1==0){
     $pdf->cell(30,4,"--","BL",0,"C",0);
     $pdf->cell(30,4,"--","BLR",0,"C",0);
    }else{
     if($linhas1==1){
      db_fieldsmemory($result1,0);
      $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
      $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
     }else{
      $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
      $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
     }
    }
    $pdf->cell(20,4,"",0,0,"L",0);
    $sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
             FROM historicomps
              inner join historico on ed61_i_codigo = ed62_i_historico
              inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
              inner join cursoedu on ed29_i_codigo = ed61_i_curso
              inner join aluno on ed47_i_codigo = ed61_i_aluno
             WHERE ed29_i_ensino in ($ensino_fun_nove)
             AND ed61_i_anoconc = $anoant
             AND ed62_i_escola = $censo_escola
             AND extract(year from ed47_d_nasc) between $ano_final AND $ano_comeco
             AND ed57_i_turno in (3)
             GROUP BY ed47_v_sexo
             ORDER BY ed47_v_sexo desc
            ";
    $result1 = pg_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if($linhas1==0){
     $pdf->cell(30,4,"--","BL",0,"C",0);
     $pdf->cell(30,4,"--","BLR",1,"C",0);
    }elseif($linhas1==1){
      db_fieldsmemory($result1,0);
      $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
      $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
    }else{
      $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
      $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
    }
    $pdf->cell(190,2,"",0,1,"L",0);
    $ano_base -= 5;
  }
 }
}
$pdf->setfont('arial','B',9);
$pdf->cell(40,4,"  TOTAL","BR",0,"L",0);
$sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
         FROM historicomps
          inner join historico on ed61_i_codigo = ed62_i_historico
          inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
          inner join cursoedu on ed29_i_codigo = ed61_i_curso
          inner join aluno on ed47_i_codigo = ed61_i_aluno
         WHERE ed29_i_ensino in ($ensino_fun_nove)
         AND ed61_i_anoconc = $anoant
         AND ed62_i_escola = $censo_escola
         AND ed57_i_turno in (1,2)
         GROUP BY ed47_v_sexo
         ORDER BY ed47_v_sexo desc
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
if($linhas1==0){
 $pdf->cell(30,4,"--","BL",0,"C",0);
 $pdf->cell(30,4,"--","BLR",0,"C",0);
}else{
 if($linhas1==1){
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
  $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",0,"C",0);
 }else{
  $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
  $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",0,"C",0);
 }
}
$pdf->cell(20,4,"",0,0,"L",0);
$sql1 = "SELECT count(ed61_i_codigo) as qtd,ed47_v_sexo
         FROM historicomps
          inner join historico on ed61_i_codigo = ed62_i_historico
          inner join turma on trim(ed57_c_descr) = trim(ed62_i_turma)
          inner join cursoedu on ed29_i_codigo = ed61_i_curso
          inner join aluno on ed47_i_codigo = ed61_i_aluno
         WHERE ed29_i_ensino in ($ensino_fun_nove)
         AND ed61_i_anoconc = $anoant
         AND ed62_i_escola = $censo_escola
         AND ed57_i_turno in (3)
         GROUP BY ed47_v_sexo
         ORDER BY ed47_v_sexo desc
        ";
$result1 = pg_query($sql1);
$linhas1 = pg_num_rows($result1);
if($linhas1==0){
 $pdf->cell(30,4,"--","BL",0,"C",0);
 $pdf->cell(30,4,"--","BLR",1,"C",0);
}elseif($linhas1==1){
  db_fieldsmemory($result1,0);
  $pdf->cell(30,4,($ed47_v_sexo=="M"?$qtd:"--"),"BL",0,"C",0);
  $pdf->cell(30,4,($ed47_v_sexo=="F"?$qtd:"--"),"BLR",1,"C",0);
}else{
  $pdf->cell(30,4,(pg_result($result1,0,'qtd')),"BL",0,"C",0);
  $pdf->cell(30,4,(pg_result($result1,1,'qtd')),"BLR",1,"C",0);
}
$pdf->cell(190,2,"",0,1,"L",0);
$pdf->Output();
?>