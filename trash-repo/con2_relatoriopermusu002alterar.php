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
include("classes/db_db_permherda_classe.php");
include("classes/db_db_depart_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldb_permherda = new cl_db_permherda;
$cldb_depart = new cl_db_depart;

$id_usuarios_selecionados = "";
$id_perfilus_selecionados = "";
$dbcoddeptos_selecionados = "";

$dbwhereid_usuariospermis = "";
$dbwhereid_usuariosperfil = "";
$dbwhereperfilusuarios = "";
if(isset($usuariossel) && count($usuariossel) > 0){

  $virgula = "";
  foreach ($usuariossel as $indexArray => $id_usuario){
    $id_usuarios_selecionados .= $virgula.$id_usuario;
    $virgula = ",";
  }

  $dbwhereid_usuariospermis = " and p.id_usuario in (".$id_usuarios_selecionados.")";

  $sql_buscar_perfis = "
                        select distinct id_perfil,id_usuario
			from db_permherda
			where id_usuario in (".$id_usuarios_selecionados.")
                       ";
  $result_buscar_perfis = pg_exec($sql_buscar_perfis);
  $numrows_buscar_perfis = pg_numrows($result_buscar_perfis);

  $virgula = "";
  if($numrows_buscar_perfis > 0){
    for($i=0; $i<$numrows_buscar_perfis; $i++){
      db_fieldsmemory($result_buscar_perfis, $i);
      $id_perfilus_selecionados .= $virgula.$id_perfil;
      $virgula = ",";
    }
  }else{
    $id_perfilus_selecionados = $id_usuarios_selecionados;
  }
  $dbwhereid_usuariosperfil = " and h.id_perfil  in (".$id_perfilus_selecionados.")";

}

if(!isset($codigo) || (isset($codigo) && trim($codigo) == "")){
  $codigo = db_getsession("DB_instit");
}

$dbwhere_permiss = "";
if(isset($id_item) && trim($id_item) != ""){
  $dbwhere_permiss = " and m.id_item = ".$id_item;
}

$ambiente = "'1'";

//// SQL VERIFICA PERMISSÕES DE USUÁRIOS
$sql_verifica_permissoesusu = "
                               select u.id_modulo, m.descr_modulo, u.id_usuario, d.nome, d.login, 0 as perm
                               from db_modulos m
                                    inner join db_usermod   u  on u.id_modulo  = m.id_item
                                    inner join db_permissao p  on p.anousu     = $anousu
                                                              and p.id_usuario = u.id_usuario
                                                              and p.id_instit  = u.id_instit
                                                              and p.id_item    = m.id_item
                                    inner join db_usuarios  d  on d.id_usuario = p.id_usuario
                               where u.id_instit = $codigo
                                     ".$dbwhereid_usuariospermis."
                                     ".$dbwhere_permiss."
			       order by u.id_usuario, m.descr_modulo
			      ";
/////////////////////////////////////////

//// SQL VERIFICA PERMISSÕES DE PERFIS
$sql_verifica_permissoesper = "
                               select distinct u.id_modulo, m.descr_modulo, h.id_perfil as id_usuario, d.nome, d.login, 1 as perm
                               from db_permherda h
                                    inner join db_usermod   u  on u.id_usuario = h.id_usuario
                                                              and u.id_instit  = $codigo

                                    inner join db_permissao p  on p.anousu     = $anousu
                                                              and p.id_usuario = h.id_perfil
                                                              and p.id_instit  = u.id_instit
                                                              and p.id_item    = u.id_modulo

                                    inner join db_modulos   m  on m.id_item    = p.id_item
                                    inner join db_usuarios  d  on d.id_usuario = h.id_perfil
                               where u.id_instit = $codigo
                                     ".$dbwhereid_usuariosperfil."
                                     ".$dbwhere_permiss."
			       order by h.id_perfil, m.descr_modulo
                              ";

// die(" $sql_verifica_permissoesusu <BR><BR> $sql_verifica_permissoesper ");

/////////////////////////////////////////
//// EXECUTA SQL PERMISSÕES USUÁRIOS
$result_verifica_permissoesusu = pg_exec($sql_verifica_permissoesusu);
$numrows_verifica_permissoesusu = pg_numrows($result_verifica_permissoesusu);
/////////////////////////////////////////

//// EXECUTA SQL PERMISSÕES PERFIS
$result_verifica_permissoesper = pg_exec($sql_verifica_permissoesper);
$numrows_verifica_permissoesper = pg_numrows($result_verifica_permissoesper);
/////////////////////////////////////////

if($numrows_verifica_permissoesusu==0 && $numrows_verifica_permissoesper==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Verifique os dados digitados ou não existem permissões para este período.");
}

db_sel_instit($codigo);

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$alt = 4;

$head3 = "RELATÓRIO DE USUÁRIOS";
$head5 = strtoupper($nomeinst);

// $head5 = strtoupper($nome);
// $head6 = strtoupper($login);

$usuant = 0;
$modant = 0;

function imprime_usumod ($opcao, $id_usuario, $usuario, $login, $id_modulo, $modulo, $addPagina = false){

  global $pdf;
  global $alt;
  global $insquebra;

  $pdf->ln(4);
  if($addPagina == true){
    $pdf->addpage();
  }

  $pdf->setfont('arial','b',8);

  $login1 = "";
  if(trim($login) != ""){
    $login1 = " (".$login.")";
  }
  if($opcao == "u"){
    $pdf->cell(0,$alt,$usuario.$login1,"TB",1,"L",1);
  }else if($opcao == "m"){
    $pdf->cell(120,$alt,$modulo,"TB",0,"L",1);
    $pdf->setfont('arial','b',5);
    $pdf->cell(0,$alt," (".$usuario." - ".$login.")","TB",1,"L",1);
  }else{
    $pdf->cell(0,$alt,$usuario.$login1,"T",1,"L",1);
    $pdf->cell(0,$alt,$modulo,"B",1,"L",1);
  }
  $pdf->ln(2);

  return true;

}

function imprime_submenus($descricao, $help, $pass){

  global $pdf;
  global $alt;
  global $id_usuario;
  global $nome; 
  global $login;
  global $id_modulo;
  global $descr_modulo;

  if($pdf->gety() > $pdf->h - 35){
    imprime_usumod("m",$id_usuario, $nome, $login, $id_modulo, $descr_modulo, true);
  }

  $imprimenegrito = "b";
  $cordefundocell = 1;
  if($pass > 0){
    $imprimenegrito = "";
    $cordefundocell = 0;
    $pdf->cell((4 * $pass),$alt,"",0,0,"L",0);
  }

  $imp = (trim($help) != "") ? $imp = true : $imp = false;

  $pdf->setfont('arial',$imprimenegrito,7);
  $pdf->cell((120 - (4 * $pass)),$alt,$descricao,0,($imp == true ? 0 : 1),"L",$cordefundocell);
  $pdf->setfont('arial','',5);
  if($imp == true){
    $pdf->cell(0,$alt," (".$help.")",0,1,"L",$cordefundocell);
  }

}

function relsubmenus($id_item, $id_mod, $id_usu, $pass){

  global $ambiente;
  global $anousu;
  global $codigo;

  $sql_submenus = "select distinct 
                          i.descricao,
                          i.help,
                          m.id_item_filho,
			  m.menusequencia
                   from db_menu m
                        inner join db_itensmenu i on i.id_item = m.id_item_filho
                        inner join db_permissao p on p.id_item = m.id_item_filho
                                                 and p.permissaoativa = '1'
                                                 and p.anousu         = ".$anousu."
                                                 and p.id_instit      = ".$codigo."
                                                 and p.id_modulo      = ".$id_mod."
                   where     m.modulo     = ".$id_mod."
                         and m.id_item    = ".$id_item."
                         and i.itemativo  = ".$ambiente."
                         and p.id_usuario = ".$id_usu." 
                   order by m.menusequencia";

  // echo "<BR><BR>$sql_submenus;";

  $result_submenus = pg_exec($sql_submenus);
  $numrows_submenus = pg_numrows($result_submenus);
  if($numrows_submenus > 0){
    for($index=0; $index<$numrows_submenus; $index ++){

      global $id_item_filho;
      global $descricao;
      global $help;

      db_fieldsmemory($result_submenus, $index);

      imprime_submenus($descricao, $help, $pass);

      $pass ++;
      relsubmenus($id_item_filho, $id_mod, $id_usu, $pass);
      $pass --;

    }
  }
}

function imprime_usuarios_perfil($perfil){

  global $alt;
  global $pdf;

  if(trim($perfil) != ""){

    $pdf->ln(2);
    $sql_usuarios_no_perfil = "select u.nome as nomeusu, u.login as loginusu, f.nome as nomeperfil
                               from db_permherda h 
        		            inner join db_usuarios u on u.id_usuario = h.id_usuario
        			    inner join db_usuarios f on f.id_usuario = h.id_perfil
        		       where h.id_perfil = ".$perfil."
			       order by u.nome
			      ";
 
    $result_usuarios_no_perfil = pg_exec($sql_usuarios_no_perfil);
    $numrows_usuarios_no_perfil = pg_numrows($result_usuarios_no_perfil);
    for($iu=0; $iu<$numrows_usuarios_no_perfil; $iu++){
      global $nomeperfil, $nomeusu, $loginusu;
      db_fieldsmemory($result_usuarios_no_perfil, $iu);
      if($iu==0 || $pdf->gety() > $pdf->h - 35){
        if($pdf->gety() > $pdf->h - 35){
          $pdf->addpage();
        }
        $pdf->setfont('arial','b',7);
        $pdf->cell(130,$alt,"Usuários no perfil: ".$nomeperfil,1,1,"L",1);
      }
      $pdf->setfont('arial','',7);
      $pdf->cell(100,$alt,$nomeusu,"L".($iu+1 == $numrows_usuarios_no_perfil?"B":""),0,"L",0);
      $pdf->cell(30,$alt,"(".$loginusu.")","R".($iu+1 == $numrows_usuarios_no_perfil?"B":""),1,"L",0);
    }
    $pdf->ln(2);
  }
}

$adicionapagina = true;

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','B',8);

$usuarioant = "";
for($a=0;$a<$numrows_verifica_permissoesusu;$a++) {
  db_fieldsmemory($result_verifica_permissoesusu,$a);
  if($pdf->gety() > $pdf->h - 35 || ($usuarioant != $id_usuario && $insquebra == "t")){
    $adicionapagina = true;
  }

  $opcao = "m";
  if($usuarioant != $id_usuario){
    $usuarioant = $id_usuario;
    $opcao = null;
  }

  imprime_usumod($opcao,$id_usuario, $nome, $login, $id_modulo, $descr_modulo, $adicionapagina);
  $adicionapagina = false;

  $sql_itens_pai = "
                    select i.id_item as pai,
                           i.descricao,
                           i.help,
                           0 as permpai,
			   m.menusequencia
                    from db_itensmenu i
                         inner join db_menu      m  on m.id_item_filho  = i.id_item
                         inner join db_permissao p  on p.id_item        = m.id_item_filho
                                                   and p.permissaoativa = '1'
                                                   and p.anousu         = ".$anousu."
                                                   and p.id_instit      = ".$codigo."
                                                   and p.id_modulo      = ".$id_modulo."
                    where     m.modulo     = ".$id_modulo."
                          and m.id_item    = ".$id_modulo."
                          and i.itemativo  = ".$ambiente."
                          and p.id_usuario = ".$id_usuario."
		    order by m.menusequencia
                   ";

  // echo "<BR><BR>$sql_itens_pai;";

  $result_itens_pai = pg_exec($sql_itens_pai);
  $numrows_itens_pai = pg_numrows($result_itens_pai);
  for($ip=0; $ip<$numrows_itens_pai; $ip++){
    db_fieldsmemory($result_itens_pai, $ip);

    imprime_submenus($descricao, $help, 0);
    relsubmenus($pai, $id_modulo, $id_usuario, 1);
  }

}

$usuarioant = "";
for($a=0;$a<$numrows_verifica_permissoesper;$a++) {
  db_fieldsmemory($result_verifica_permissoesper,$a);
  if($pdf->gety() > $pdf->h - 35 || ($usuarioant != $id_usuario && $insquebra == "t")){
    $adicionapagina = true;
  }

  $opcao = "m";
  if($usuarioant != $id_usuario){
    $opcao = null;
  }

  if($opcao == null){
    imprime_usuarios_perfil($usuarioant);
  }

  $usuarioant = $id_usuario;

  imprime_usumod($opcao,$id_usuario, $nome, $login, $id_modulo, $descr_modulo, $adicionapagina);
  $adicionapagina = false;

  $sql_itens_pai = "
                    select distinct
		           i.id_item as pai,
                           i.descricao,
                           i.help,
                           1 as permpai,
			   m.menusequencia
                    from db_itensmenu i
                         inner join db_menu      m  on m.id_item_filho  = i.id_item
                         inner join db_permissao p  on p.id_item        = m.id_item_filho
                                                   and p.permissaoativa = '1'
                                                   and p.anousu         = ".$anousu."
                                                   and p.id_instit      = ".$codigo."
                                                   and p.id_modulo      = ".$id_modulo."
                         inner join db_permherda h  on h.id_perfil      = p.id_usuario
                    where     m.modulo     = ".$id_modulo."
                          and m.id_item    = ".$id_modulo."
                          and i.itemativo  = ".$ambiente."
                          and h.id_perfil  = ".$id_usuario."
		    order by m.menusequencia
                   ";

  // echo "<BR><BR>$sql_itens_pai;";

  $result_itens_pai = pg_exec($sql_itens_pai);
  $numrows_itens_pai = pg_numrows($result_itens_pai);
  for($ip=0; $ip<$numrows_itens_pai; $ip++){
    db_fieldsmemory($result_itens_pai, $ip);

    imprime_submenus($descricao, $help, 0);
    relsubmenus($pai, $id_modulo, $id_usuario, 1);

  }

}

imprime_usuarios_perfil($usuarioant);

$pdf->SetFont('Arial','B',7);
$pdf->Output();
?>