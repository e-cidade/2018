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
require_once('model/configuracao/SkinService.service.php');

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if( !session_is_registered("DB_instit")) {

  session_register("DB_instit");

  if(isset($HTTP_POST_VARS["instit"])){

    db_putsession("DB_instit",$HTTP_POST_VARS["instit"]);
  } else {

    parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

    if(isset($instit)){
       db_putsession("DB_instit",$instit);
    } else {
      echo "<script>
      location.href='instit.php';
      </script>";
      exit;
    }
  }
}

db_query("update db_usuariosonline 
       set uol_arquivo = '', 
   uol_modulo = 'Selecionando Área' ,
   uol_inativo = ".time()."
   where uol_id = ".db_getsession("DB_id_usuario")."
   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
   and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline"); 

$rsInstituicao = db_query("select nomeinst as nome,ender,telef,cep,email,url from db_config where codigo = ".db_getsession("DB_instit"));

if(db_getsession("DB_id_usuario") == "1" || db_getsession("DB_administrador") == 1 ) {
        
  $rsArea = db_query( "select distinct at26_sequencial,at25_descr, at25_figura 
                         from atendcadarea 
                              inner join atendcadareamod on at26_sequencial=at26_codarea
                        order by at25_descr" );
  
} else {

  $rsArea = db_query("select distinct at26_sequencial,at25_descr, at25_figura
                     from atendcadarea 
                          inner join atendcadareamod on at26_sequencial=at26_codarea
                     where at26_id_item in (
             
                     select id_item from (
                            select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                                   case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                            from ( select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
                                   from db_permissao p 
                                        inner join db_itensmenu i on p.id_item = i.id_item 
                                   where i.itemativo = 1
                                     and p.id_usuario = ".db_getsession("DB_id_usuario")."
                                     and p.id_instit = ".db_getsession("DB_instit")." 
                                     and p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
                                 ) as i           
                                 inner join db_modulos m on m.id_item = i.id_modulo
                                 inner join db_itensmenu it on it.id_item = i.id_modulo
                                 left outer join db_usumod u on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario
                            where i.id_usuario = ".db_getsession("DB_id_usuario")."
                              and i.id_instit = ".db_getsession("DB_instit")."    and libcliente is true 
                         
                           union
                  
                           select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                                 case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                           from  (
                                   select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit
        from db_permissao p 
              inner join db_permherda h on h.id_perfil = p.id_usuario
        inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
        inner join db_itensmenu i 
        on p.id_item = i.id_item 
        where i.itemativo = 1
        and h.id_usuario = ".db_getsession("DB_id_usuario")."
        and p.id_instit = ".db_getsession("DB_instit")." 
        and p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
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
      )  as yyy " . ( isset($area_de_acesso) ? " 
               inner join atendcadareamod on yyy.id_item = at26_id_item
              where at26_codarea = $area_de_acesso
               ": "" )." order by nome_modulo 

                  )   order by at25_descr");
}

?>

<html>
  <?php

    $oSkin = new SkinService();

    include( $oSkin->getPathFile("area.php") );

  ?>
</html>
