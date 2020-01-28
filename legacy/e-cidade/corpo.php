<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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

session_start();
$_SESSION["DB_itemmenu_acessado"] = "0";

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_conn.php");
require_once('model/configuracao/SkinService.service.php');

if(session_is_registered("DB_uol_hora")) {

  db_query("update db_usuariosonline
            set uol_arquivo = '',
      uol_modulo = 'Selecionando Módulo' ,
      uol_inativo = ".time()."
            where uol_id = ".db_getsession("DB_id_usuario")."
      and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."'
      and uol_hora = ".db_getsession("DB_uol_hora"))
   or die("Erro(26) atualizando db_usuariosonline");
}

if(isset($DB_SELLER)){
  if(!session_is_registered("DB_SELLER")) {
     session_register("DB_SELLER");
     db_putsession("DB_SELLER","on");
  }
  if(!session_is_registered("DB_NBASE")) {
    session_register("DB_NBASE");
    db_putsession("DB_NBASE",$DB_BASE);
  }
}else if(session_is_registered("DB_NBASE")) {
  session_unregister("DB_NBASE");
}

if(!session_is_registered("DB_instit")) {
  session_register("DB_instit");
  if(isset($HTTP_POST_VARS["instit"])){
    db_putsession("DB_instit",$HTTP_POST_VARS["instit"]);
  }else{
     parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

     if(isset($instit)){
      db_putsession("DB_instit",$instit);
     }else{
       echo "<script>
               location.href='instit.php';
            </script>";
       exit;
     }
  }

  db_logsmanual_demais("Acesso instituição - Login: ".db_getsession("DB_login"),db_getsession("DB_id_usuario"),0,0,0,db_getsession("DB_instit"));
}else{
  parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
  if(isset($area_de_acesso)){
    db_putsession("DB_Area",$area_de_acesso);

  }
}

if(db_getsession("DB_instit") == "") {

  db_erro("Instituição não selecionada.",0);
}

$rsInstituicao = db_query("select nomeinst as nome,ender,telef,cep,email,url from db_config where codigo = ".db_getsession("DB_instit"));

 if(session_is_registered("DB_Area")){
  $area_de_acesso = db_getsession("DB_Area");
}

if (db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1) {
  $sSqlmodulos = "select distinct  db_modulos.id_item,
                           db_modulos.descr_modulo,
                           db_itensmenu.help,
                           db_itensmenu.funcao,
                           db_modulos.imagem,
                           db_modulos.nome_modulo,
                           extract (year from current_date) as anousu
                      from db_itensmenu
                           inner join db_menu on   db_itensmenu.id_item = db_menu.id_item
                           inner join db_modulos   on db_itensmenu.id_item = db_modulos.id_item
                           ";

  if ( isset($area_de_acesso) ){
     $sSqlmodulos .= "            inner join atendcadareamod on db_modulos.id_item = at26_id_item
                    where libcliente is true and at26_codarea = $area_de_acesso
             ";
  } else {
    $sSqlmodulos .= "       where libcliente is true ";
  }

  $sSqlmodulos .= "         order by db_modulos.nome_modulo";
} else {
  $sSqlmodulos = "select * from (
      select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                    case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                    from
        (
          select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
          from db_permissao p
          inner join db_itensmenu i
          on p.id_item = i.id_item
          where i.itemativo = 1
          and p.id_usuario = ".db_getsession("DB_id_usuario")."
          and p.id_instit = ".db_getsession("DB_instit")."
          and (p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."
           or  p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."+1)
        ) as i
                    inner join db_modulos m
                    on m.id_item = i.id_modulo
        inner join db_itensmenu it
        on it.id_item = i.id_modulo
                    left outer join db_usumod u
                    on u.id_item = i.id_modulo
        and u.id_usuario = i.id_usuario
                    where i.id_usuario = ".db_getsession("DB_id_usuario")."
        and i.id_instit = ".db_getsession("DB_instit")
                                ."    and libcliente is true
      union
    select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                   case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                   from
       (
         select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit
         from db_permissao p
               inner join db_permherda h on h.id_perfil = p.id_usuario
         inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
         inner join db_itensmenu i
         on p.id_item = i.id_item
         where i.itemativo = 1
         and h.id_usuario = ".db_getsession("DB_id_usuario")."
         and p.id_instit = ".db_getsession("DB_instit")."
         and (p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."
          or  p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."+1)
       ) as i
                   inner join db_modulos m
                   on m.id_item = i.id_modulo
       inner join db_itensmenu it
       on it.id_item = i.id_modulo
                   left outer join db_usumod u
                   on u.id_item = i.id_modulo
       and u.id_usuario = i.id_usuario
                   where i.id_usuario = ".db_getsession("DB_id_usuario")."
                                             and libcliente is true
       and i.id_instit = ".db_getsession("DB_instit") . "
       )  as yyy ";

  $iNumModulos = isset($_SESSION["DB_totalmodulos"]) == true ? $_SESSION["DB_totalmodulos"] : 0;

  if( (isset($area_de_acesso) && $iNumModulos > 20) || (!isset($_GET["link"]) && isset($area_de_acesso))){
    $sSqlmodulos .= "
            inner join atendcadareamod on yyy.id_item = at26_id_item
            where at26_codarea = $area_de_acesso
             ";
  }

  $sSqlmodulos .= " order by nome_modulo ";
}

$rsModulos = db_query($sSqlmodulos) or die($sSqlmodulos);
$iNumRowsModulos = pg_numrows($rsModulos);

/**
 * Declara a estrutura da folha de pagamento conforme 
 * a instituição e competência da folha de pagamento.
 */
try{
  
  $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
  $oCompetencia = DBPessoal::getCompetenciaFolha();
  DBPessoal::declararEstruturaFolhaPagamento($oInstituicao, $oCompetencia);
  
} catch (Exception $ex) {
  DBPessoal::setEstruturaFolhaPagamento(false);
}


?>

<html>
  <?php

    if ( $iNumRowsModulos == 0 ) {

       db_erro("Usuário sem nenhuma permissao de acesso! Contate suporte!",0);
       exit;
    } else {

      if ( !isset($area_de_acesso) && !session_is_registered("DB_Area") ) {

        if ( $iNumRowsModulos > 20  ) {
          db_putsession("DB_totalmodulos",$iNumRowsModulos);

          echo "<script>location.href='area.php?instit=".db_getsession("DB_instit")."';</script>";
          exit;
        }
      }
    }

    $oSkin = new SkinService();

    include( $oSkin->getPathFile("corpo.php") );

  ?>
</html>