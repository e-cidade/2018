<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_db_permherda_classe.php");
include("classes/db_db_depart_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldb_permherda = new cl_db_permherda;
$cldb_depart = new cl_db_depart;

$totalpormodulo = array();
$listausuarios_interno = array();
$listausuarios_externo  = array();
$listausuarios_perfil  = array();
$listausuarios  = array();
$total_usuarios = 0;

if(!isset($codigo) || (isset($codigo) && trim($codigo) == "")){
  $codigo = db_getsession("DB_instit");
}

$dbwhere_permiss = "";
if(isset($id_item) && trim($id_item) != ""){
  $dbwhere_permiss = " and m.id_item = ".$id_item;
}

$dbwhere_tipo_usuario = "";
if (isset($tipo_usuario) && trim(@$tipo_usuario) != "" && trim(@$tipo_usuario) != "T"){
  $dbwhere_tipo_usuario = " and d.usuarioativo = '".$tipo_usuario."' ";
}

$ambiente = "'1'";

$dbwhereid_usuariospermis = "";
$dbwhereid_usuariosperfil = "";

$id_usuarios_selecionados = "";
if(isset($usuariossel) && count($usuariossel) > 0){
  $virgula = "";
  foreach ($usuariossel as $indexArray => $id_usuario){
    $id_usuarios_selecionados .= $virgula.$id_usuario;
    $virgula = ",";
  }
  $dbwhereid_usuariospermis = " and p.id_usuario in (".$id_usuarios_selecionados.") and d.usuext = 0";
  $dbwhereid_usuariosperfil = " and h.id_usuario in (".$id_usuarios_selecionados.") and d.usuext = 0";
}

if(isset($tipo_principal) && trim($tipo_principal) == "0"){
} elseif(isset($tipo_principal) && trim($tipo_principal) == "1"){
  $dbwhereid_usuariospermis .= " and d.usuext = 0";
  $dbwhereid_usuariosperfil .= " and d.usuext = 0";
} elseif(isset($tipo_principal) && trim($tipo_principal) == "2"){
  $dbwhereid_usuariospermis .= " and d.usuext = 1";
  $dbwhereid_usuariosperfil .= " and d.usuext = 1";
} elseif(isset($tipo_principal) && trim($tipo_principal) == "3"){
  $dbwhereid_usuariospermis .= " and d.usuext = 2";
  $dbwhereid_usuariosperfil .= " and d.usuext = 2";
} elseif(isset($tipo_principal) && trim($tipo_principal) == "4"){
  $dbwhereid_usuariospermis .= " and d.usuext in (0,2)";
  $dbwhereid_usuariosperfil .= " and d.usuext in (0,2)";
}

//// SQL VERIFICA PERMISSÕES DE USUÁRIOS --                                     inner join db_usermod   u  on u.id_modulo  = m.id_item
$sql_verifica_permissoesusu = "
                               select distinct p.id_modulo, m.descr_modulo, d.id_usuario, d.nome, d.login, d.usuext,
                                               case
                                                 when d.usuarioativo = '1' then 'ATIVO'
                                                 when d.usuarioativo = '2' then 'BLOQUEADO'
                                                 when d.usuarioativo = '3' then 'AGUARDANDO ATIVAÇÃO'
                                                 else 'INATIVO'
                                               end as status
                               from db_modulos m
                                    inner join db_permissao p  on p.anousu     = $anousu
                                                              and p.id_instit  = $codigo
                                                              and p.id_item    = m.id_item
                                    inner join db_usuarios  d  on d.id_usuario = p.id_usuario
                               where p.id_instit = $codigo
                                     ".$dbwhereid_usuariospermis."
                                     ".$dbwhere_permiss.
                                       $dbwhere_tipo_usuario;
/////////////////////////////////////////

//// SQL VERIFICA PERMISSÕES DE PERFIS
$sql_verifica_permissoesper = "
                               select distinct p.id_modulo, m.descr_modulo, h.id_usuario, d.nome, d.login, d.usuext,
                                               case
                                                 when d.usuarioativo = '1' then 'ATIVO'
                                                 when d.usuarioativo = '2' then 'BLOQUEADO'
                                                 when d.usuarioativo = '3' then 'AGUARDANDO ATIVAÇÃO'
                                                 else 'INATIVO'
                                               end as status
                               from db_permherda h
                                    inner join db_permissao p  on p.anousu     = $anousu
                                                              and p.id_usuario = h.id_perfil
                                                              and p.id_instit  = $codigo
                                    inner join db_modulos   m  on m.id_item    = p.id_item
                                    inner join db_usuarios  d  on d.id_usuario = h.id_usuario
                               where p.id_instit = $codigo
                                     ".$dbwhereid_usuariosperfil."
                                     ".$dbwhere_permiss.
                                       $dbwhere_tipo_usuario;

$sql_executa = " select distinct * from (($sql_verifica_permissoesusu) union ($sql_verifica_permissoesper)) as x order by nome, descr_modulo ";

/////////////////////////////////////////
$result_executa = db_query($sql_executa);
$numrows_executa = pg_numrows($result_executa);
/////////////////////////////////////////

if($numrows_executa == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Verifique os dados digitados ou não existem permissões para este exercício.");
}

db_sel_instit($codigo);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt = 4;

$head3 = "RELATÓRIO DE USUÁRIOS";
$head4 = strtoupper($nomeinst);

$cabec_usuarios_tipo = "";
if (isset($tipo_principal) && trim(@$tipo_principal) != ""){
     if (trim(@$tipo_principal) == "0"){
          $cabec_usuarios_tipo = "TIPO DE USUARIO: TODOS";
     }

     if (trim(@$tipo_principal) == "1"){
          $cabec_usuarios_tipo = "TIPO DE USUARIO: SOMENTE INTERNOS";
     }

     if (trim(@$tipo_principal) == "2"){
          $cabec_usuarios_tipo = "TIPO DE USUARIO: SOMENTE EXTERNOS";
     }
     
     if (trim(@$tipo_principal) == "3"){
          $cabec_usuarios_tipo = "TIPO DE USUARIO: SOMENTE PERFIS";
     }

     if (trim(@$tipo_principal) == "4" ){
          $cabec_usuarios_tipo = "TIPO DE USUARIO: USUARIOS INTERNOS + PERFIS";
     }
}
$head5 = $cabec_usuarios_tipo;

$cabec_usuarios_situacao = "";
if (isset($tipo_usuario) && trim(@$tipo_usuario) != ""){

  switch (trim(@$tipo_usuario)) {
    case "0":
      $cabec_usuarios_situacao = "SITUAÇÃO: INATIVOS";
      break;
    case "1":
      $cabec_usuarios_situacao = "SITUAÇÃO: ATIVOS";
      break;
    case "2":
      $cabec_usuarios_situacao = "SITUAÇÃO: BLOQUEADOS";
      break;
    case "3":
      $cabec_usuarios_situacao = "SITUAÇÃO: AGUARDANDO ATIVAÇÃO";
      break;
    default:
      $cabec_usuarios_situacao = "SITUAÇÃO: TODOS";
      
  }
}
$head6 = $cabec_usuarios_situacao;

$adicionapagina = true;

$pass = 0;
$testaUsuario = "";
$indexImprime = 0;

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);

for($a=0; $a<$numrows_executa; $a++){
  db_fieldsmemory($result_executa,$a);

  $descr_tipo_usuario = "";
  if ($usuext == 0) {
    $descr_tipo_usuario = "Interno";
  } elseif ($usuext == 1) {
    $descr_tipo_usuario = "Externo";
  } elseif ($usuext == 2) {
    $descr_tipo_usuario = "Perfil";
  }

  $login_busca = $login;

  if ($usuext == 0) {
    if (!isset($listausuarios_interno[$login_busca])) {
      $listausuarios_interno[$login_busca] = $nome;
    }
  } elseif ($usuext == 1) {
    if (!isset($listausuarios_externo[$login_busca])) {
      $listausuarios_externo[$login_busca] = $nome;
    }
  } elseif ($usuext == 2) {
    if (!isset($listausuarios_perfil[$login_busca])) {
      $listausuarios_perfil[$login_busca] = $nome;
    }
  }

  $pdf->setfont("arial", "B", 8);
  if($pdf->gety() > $pdf->h - 35 || $a == 0 || $testaUsuario != $id_usuario){
    $pdf->addpage();
    $pdf->cell(10,$alt,$id_usuario,"T",0,"L",1);
    $pdf->cell(0,$alt,$nome . " [Login: " . $login . "] - [Situação: ".trim($status)."]" . " - [Tipo de usuario: $descr_tipo_usuario]","T",1,"L",1);
  }

  $testaUsuario = $id_usuario;

  $pdf->cell(10,$alt,$id_modulo,"T",0,"L",1);
  $pdf->cell(0,$alt,$descr_modulo,"T",1,"L",1);

  $pass ++;
  recGravaMenus($id_usuario, $id_modulo, $id_modulo);
  $pass --;

  if (!isset($totalpormodulo[strtoupper($descr_modulo)])) {
    $totalpormodulo[strtoupper($descr_modulo)] = 1;
  } else {
    $totalpormodulo[strtoupper($descr_modulo)] ++;
  }

}

$pdf->addpage();
$total_quant=0;

$pdf->setfont("arial","B",8);
$pdf->cell(80,5,"TOTALIZAÇÃO POR MODULO",1,1,"C",1);
$pdf->cell(60,5,"MODULO",1,0,"L",1);
$pdf->cell(20,5,"QUANTIDADE",1,1,"C",1);

ksort($totalpormodulo);

$pdf->setfont("arial","",8);
foreach ($totalpormodulo as $k => $v) {
  $pdf->cell(60,5,$k,0,0,"L",0);
  $pdf->cell(20,5,$v,0,1,"R",0);
  $total_quant++;
}
$pdf->setfont("arial","B",8);
$pdf->cell(60,5,"TOTAL DE MODULOS",1,0,"L",1);
$pdf->cell(20,5,$total_quant,1,1,"R",1);

$pdf->addpage();

ksort($listausuarios_interno);
ksort($listausuarios_externo);
ksort($listausuarios_perfil);

$pdf->setfont("arial","B",8);
if (sizeof($listausuarios_interno) > 0) {
  $pdf->cell(110,5,"LISTA DE USUARIOS - INTERNOS",1,1,"C",1);
  $pdf->cell(80,5,"NOME", 1,0,"C",1);
  $pdf->cell(30,5,"LOGIN",1,1,"C",1);

  $pdf->setfont("arial","",8);
  $total_usuarios=0;
  foreach ($listausuarios_interno as $k => $v) {
    $pdf->cell(80,5,$v,0,0,"L",0);
    $pdf->cell(30,5,$k,0,1,"L",0);
    $total_usuarios++;
  }
  $pdf->setfont("arial","b",8);
  $pdf->cell(80,5,"TOTAL DE USUARIOS - INTERNOS",1,0,"L",1);
  $pdf->cell(30,5,$total_usuarios,1,1,"R",1);
  $pdf->ln(5);
}

if (sizeof($listausuarios_externo) > 0) {
  $pdf->cell(110,5,"LISTA DE USUARIOS - EXTERNOS",1,1,"C",1);
  $pdf->cell(80,5,"NOME", 1,0,"C",1);
  $pdf->cell(30,5,"LOGIN",1,1,"C",1);

  $pdf->setfont("arial","",8);
  $total_usuarios=0;
  foreach ($listausuarios_externo as $k => $v) {
    $pdf->cell(80,5,$v,0,0,"L",0);
    $pdf->cell(30,5,$k,0,1,"L",0);
    $total_usuarios++;
  }
  $pdf->setfont("arial","b",8);
  $pdf->cell(80,5,"TOTAL DE USUARIOS - EXTERNOS",1,0,"L",1);
  $pdf->cell(30,5,$total_usuarios,1,1,"R",1);
  $pdf->ln(5);
}

if (sizeof($listausuarios_perfil) > 0) {
  $pdf->setfont("arial","B",8);
  $pdf->cell(110,5,"LISTA DE USUARIOS - PERFIL",1,1,"C",1);
  $pdf->cell(80,5,"NOME", 1,0,"C",1);
  $pdf->cell(30,5,"LOGIN",1,1,"C",1);

  ksort($listausuarios_interno);
  ksort($listausuarios_perfil);

  $pdf->setfont("arial","",8);
  $total_usuarios=0;
  foreach ($listausuarios_perfil as $k => $v) {
    $pdf->cell(80,5,$v,0,0,"L",0);
    $pdf->cell(30,5,$k,0,1,"L",0);
    $total_usuarios++;
  }
  $pdf->setfont("arial","b",8);
  $pdf->cell(80,5,"TOTAL DE USUARIOS - PERFIL",1,0,"L",1);
  $pdf->cell(30,5,$total_usuarios,1,1,"R",1);
}

function recGravaMenus($id_usuario, $id_modulo, $id_item){
  global $anousu, $codigo, $pdf, $alt, $pass, $ambiente;
  global $pai, $id_item_filho, $menusequencia, $descricao, $help;

  $sql_itens_pai_usu = "
                        select m.id_item as pai, m.id_item, m.id_item_filho, m.menusequencia, i.descricao, i.help
                        from db_menu m
                             inner join db_permissao p on p.id_item = m.id_item_filho
                             inner join db_itensmenu i on i.id_item = m.id_item_filho
                                                      and p.permissaoativa = '1'
                                                      and p.anousu = $anousu
                                                      and p.id_instit = $codigo
                                                      and p.id_modulo = $id_modulo
                        where p.id_usuario = $id_usuario
                          and m.modulo 		 = $id_modulo
                          and p.id_instit  = $codigo
                          and m.id_item 	 = $id_item
                          and i.itemativo  = $ambiente
                       "; 
  
  $sql_itens_pai_per = "
                        select m.id_item as pai, m.id_item, m.id_item_filho, m.menusequencia, i.descricao, i.help
                        from db_menu m
                             inner join db_permherda h on h.id_usuario = $id_usuario
                             inner join db_usuarios  u on u.id_usuario = h.id_perfil 
                                                      and u.usuarioativo = '1'
                             inner join db_permissao p on p.id_item = m.id_item_filho
                             inner join db_itensmenu i on i.id_item = m.id_item_filho
                                                      and p.permissaoativa = '1'
                                                      and p.anousu = $anousu
                                                      and p.id_instit = $codigo
                                                      and p.id_modulo = $id_modulo
                        where p.id_usuario = h.id_perfil
                          and m.modulo 		 = $id_modulo
                          and p.id_instit  = $codigo 
                          and m.id_item 	 = $id_item
                          and i.itemativo = '1' ";

  $sql_executa_item = " select distinct * from (($sql_itens_pai_usu) union ($sql_itens_pai_per)) as x order by pai, menusequencia, id_item_filho";
  /////////////////////////////////////////
  $result_executa_item = db_query($sql_executa_item);
  $numrows_executa_item = pg_numrows($result_executa_item);
  /////////////////////////////////////////
  
  for($b=0; $b<$numrows_executa_item; $b++){
    db_fieldsmemory($result_executa_item, $b);
    $conta=1;
    $pdf->setfont("arial","",8);
    $pdf->cell(($pass * 3),$alt,""         , 0, 0, "L", 0);
    $pdf->cell(80,$alt,substr($descricao,0,80) , 0, 0, "L", 0);
    $pdf->setfont('arial','',5);

    verificaPerfilMenu($id_usuario, $id_modulo, $id_item, $id_item_filho, false, ($pass * 3));

    $pass ++;
    recGravaMenus($id_usuario, $id_modulo, $id_item_filho);
    $pass --;

  }

}

function verificaPerfilMenu($id_usuario, $id_modulo, $id_item, $id_item_filho, $borda=true, $width=0, $preenche="0"){
  global $anousu, $codigo, $pdf, $alt, $logper, $nomper, $usuper;

  $sql_itens_per = "
                    select distinct u.id_usuario as usuper, u.nome as nomper, u.login as logper
                    from db_menu m
                         inner join db_permherda h on h.id_usuario = $id_usuario
                         inner join db_usuarios  u on u.id_usuario = h.id_perfil
                                                  and u.usuarioativo = '1'
                         inner join db_permissao p on p.id_item = m.id_item_filho
                                                  and p.permissaoativa = '1'
                                                  and p.anousu = $anousu
                                                  and p.id_instit = $codigo
                                                  and p.id_modulo = $id_modulo
                         inner join db_itensmenu i on i.id_item = m.id_item_filho
                    where p.id_usuario    = h.id_perfil
                      and m.modulo	 	    = $id_modulo 
                      and m.id_item 	    = $id_item
                      and p.id_instit     = $codigo
                      and m.id_item_filho = $id_item_filho 
                      and i.itemativo 	  = '1' 
                      and u.usuext <> 0 ";

$result_itens_per = db_query($sql_itens_per);
  $numrows_itens_per = pg_num_rows($result_itens_per);
if ($numrows_itens_per == 0){
$pdf->cell(70         ,$alt,"" , 0, 1, "L", 0);
}
 if($numrows_itens_per > 0){
    $pdf->setfont("arial", "I", 7);
    $sql_itens_usu = "
                      select distinct u.id_usuario as usuper, u.nome as nomper, u.login as logper
                      from db_menu m
                           inner join db_permissao p on p.id_item = m.id_item_filho
                                                    and p.permissaoativa = '1'
                                                    and p.anousu = $anousu
                                                    and p.id_instit = $codigo
                                                    and p.id_modulo = $id_modulo
                           inner join db_usuarios  u on u.id_usuario = p.id_usuario
                                                    and u.usuarioativo = '1'
                           inner join db_itensmenu i on i.id_item = m.id_item_filho
                      where p.id_usuario     = $id_usuario
                        and m.modulo 		     = $id_modulo
                        and p.id_instit      = $codigo 
                        and m.id_item 	     = $id_item
                        and m.id_item_filho  = $id_item_filho 
                        and i.itemativo 		 = '1' ";
    $result_itens_usu = db_query($sql_itens_usu);
    $numrows_itens_usu = pg_num_rows($result_itens_usu);
   $pdf->setx(120);
    if($numrows_itens_usu > 0){
      db_fieldsmemory($result_itens_usu, 0);
      $pdf->cell(30,$alt,"Login: $logper","0","L",$preenche);
    }
$vNomper=null;
    for($i=0; $i<$numrows_itens_per; $i++){
      db_fieldsmemory($result_itens_per, $i);
        
      if ($i > 0) {
       $vNomper.=",".$nomper;
      } else {
       $vNomper.=$nomper;
      }
      if ($i==$numrows_itens_per-1){
          $pdf->multiCell(60,$alt,"Perfil: $vNomper","0","L",$preenche);
         }
   }

  }
}

$pdf->SetFont('Arial','B',7);
$pdf->Output();