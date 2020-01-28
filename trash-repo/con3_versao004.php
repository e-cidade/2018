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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_cadhelp_classe.php");
include("classes/db_db_itenshelp_classe.php");
include("classes/db_db_tipohelp_classe.php");
include("classes/db_db_modulos_classe.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_versaoant_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


if(isset($grava_leitura)){

  $sql = "select db35_codver ,db30_codver,fc_versao(db30_codversao, db30_codrelease) as versaolido
          from db_versao 
               left join db_versaolidousuario on db30_codver = db35_codver  and db35_id_usuario = ".db_getsession("DB_id_usuario")."
          where db35_codver is null
          order by db30_codver
         ";
  $result = db_query($sql);
  if(pg_numrows($result)>0){

    $sql = "insert into db_versaolidousuario
            select nextval('db_versaolidousuario_db35_sequencial_seq'),db30_codver,".db_getsession("DB_id_usuario").",current_date
            from db_versao 
                 left join db_versaolidousuario on db30_codver = db35_codver  and db35_id_usuario = ".db_getsession("DB_id_usuario")."
            where db35_codver is null
            order by db30_codver
         ";

    $result = db_query($sql);

  }
  exit;
}



$cldb_versao = new cl_db_versao;
$cldb_versaoant = new cl_db_versaoant;

$result = $cldb_versaoant->sql_record($cldb_versaoant->sql_query(null," db31_codver, fc_versao(db30_codversao, db30_codrelease) as versao",' db31_codver desc limit 1 '));

$versao_inicial = 0;
$versao_ini = '';
if($cldb_versaoant->numrows > 0){
  db_fieldsmemory($result,0,0);
  $versao_inicial = $db31_codver;
  $versao_ini = $versao;
}
$mensagem = "";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprimir_versoes(){
  if( document.form1.tipo_consulta.value == 'M' ){
     jan = window.open('con3_versao005.php?id_item='+document.form1.nome_modulo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }else{
     jan = window.open('con3_versao006.php?id_item='+document.form1.nome_modulo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}
function js_muda_consulta(execucao){
  location.href = execucao;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" action"" method="POST">
<table width="100%" height="100%" border="1">
<tr>
  <td height="5%" width="40%" valign="top" align='center'>
  <?
  if(  isset($registra_atualizacao) ){
    $versao_inicial = $versao_lida;
  }
  
    echo "Módulo:";
    global $nome_modulo;
    $nome_modulo = @$id_item;
    $cldb_modulos = new cl_db_modulos;

    $sql = "select 0 as modulo, 'Todos' as nome_modulo
            union ";

  if( ! isset($registra_atualizacao) ){

    $sql_modulo = "select * from ( select modulo,nome_modulo 
            from db_versaousu 
                 inner join db_menu on db32_id_item = db_menu.id_item 
                 inner join db_modulos on modulo = db_modulos.id_item
            where db32_codver >= $versao_inicial 
            union 
            select modulo,nome_modulo 
            from db_versaousu 
                 inner join db_menu on db32_id_item = id_item_filho 
                 inner join db_modulos on modulo = db_modulos.id_item
            where db32_codver >= $versao_inicial 
            order by nome_modulo
            ) as x
            ";

    }else{
    
    $sql_modulo = "
       select distinct modulo,nome_modulo 
       from (select modulo,nome_modulo 
            from db_versaousu 
                 inner join db_menu on db32_id_item = db_menu.id_item_filho
                 inner join db_modulos on modulo = db_modulos.id_item 
                 inner join db_permherda h on h.id_usuario = ".db_getsession("DB_id_usuario")."
                 inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
                 inner join db_permissao p on db_modulos.id_item = p.id_modulo and  p.id_usuario = u.id_usuario 
    
            where db32_codver >= $versao_lida

            union 

            select modulo,nome_modulo 
            from db_versaousu 
                 inner join db_menu on db32_id_item = id_item_filho 
                 inner join db_modulos on modulo = db_modulos.id_item ";
    if ( db_getsession("DB_administrador") != 1 ){           
      $sql_modulo .= " inner join db_permissao p on db_modulos.id_item = p.id_modulo and  p.id_usuario = ".db_getsession("DB_id_usuario");
    }
    $sql_modulo .= "
            where db32_codver >= $versao_lida
            order by nome_modulo
            ) as x
            ";
    
    
    }

    $result_modulo = $cldb_modulos->sql_record($sql.$sql_modulo);
    if($result_modulo!=false && $cldb_modulos->numrows>0){
      db_selectrecord('nome_modulo',$result_modulo,true,2,'','','','',"location.href='con3_versao004.php?id_item='+document.form1.nome_modulo.value+'&tipo_consulta='+document.form1.tipo_consulta.value".(isset($registra_atualizacao)?"+'&registra_atualizacao=&versao_lida=".$versao_lida."'":""),1);
    }else{
      echo
      $mensagem = "Nao ha atualizacoes a serem consultadas.";
      exit;
    }
    if(!isset($tipo_consulta)){
      $tipo_consulta = 'M';
    }
    global $tipo_consulta;
    $tipo_consulta_array = array("M"=>"Modulo","P"=>"Procedimento");
    echo "Tipo Consulta:";
    db_select("tipo_consulta",$tipo_consulta_array,true,2,"onchange='js_muda_consulta(\"con3_versao004.php?id_item=\"+document.form1.nome_modulo.value+\"&tipo_consulta=\"+document.form1.tipo_consulta.value".(isset($registra_atualizacao)?"+\"&registra_atualizacao=&versao_lida=".$versao_lida."\"":"").")'");

  if( ! isset($registra_atualizacao) ){
    if( !isset($nao_imprimir) ){
      global $todosmodulos,$imprimir;
      //$todosmodulos=' Fechar ';
      $imprimir='Imprimir';
      //db_input('todosmodulos',10,0,true,'button',2,'onclick="parent.dbiframe_atualiza.hide();"');
      db_input('imprimir',10,0,true,'button',2,'onclick="js_imprimir_versoes();"');
    }


  }else{

    echo "<strong>Leia as atualizações e confirme a leitura no final.</strong>";

  }

  /* 
  $result = $cldb_versao->sql_record($cldb_versao->sql_query_file(null," db30_codver,'2.'||db30_codversao||'.'||db30_codrelease ",' db30_codver desc'," db30_codver < $versao_inicial"));
  echo "Versoes Anteriores: ";   
  db_selectrecord('versoes_anteriores',$result,true,2,'','','','',"location.href='con3_versao004.php?id_item='+document.form1.nome_modulo.value+'&tipo_consulta='+document.form1.tipo_consulta.value",1);
  */
?>
</td>
</tr>
<tr>
<td valign="top">
<table>
<tr>
<td width="20%" valign="top">
<?

$sql = "select * from ($sql_modulo) as x ";

if(isset($id_item) && $id_item != 0){
 $sql .= "  where modulo = $id_item ";
}
$sql .= " order by nome_modulo";

$res = pg_exec($sql);

$numrows = pg_numrows($res);

if( $numrows > 0 ) {

  for($i=0;$i<$numrows;$i++){
        
    db_fieldsmemory($res,$i);
    
    if($tipo_consulta == 'M'){
    
      $espacos = $modulo;

      $matriz_item = array();
      $matriz_item_seleciona = array();
      
      $sSqldbVersao  = "  select distinct db30_codversao, db30_codrelease,db32_id_item                            ";
      $sSqldbVersao .= "    from db_versao                                                                        ";
      $sSqldbVersao .= "         left outer join db_versaocpd on db_versao.db30_codver = db_versaocpd.db33_codver ";
      $sSqldbVersao .= "         left outer join db_versaousu on db_versao.db30_codver = db_versaousu.db32_codver ";
      $sSqldbVersao .= "   where not db32_obs is null                                                             ";
      $sSqldbVersao .= "     and db32_id_item                                                                     ";
      $sSqldbVersao .= "      in (select distinct id_item                                                         ";
      $sSqldbVersao .= "            from db_menu                                                                  ";
      $sSqldbVersao .= "           where modulo = {$modulo}                                                       ";
      $sSqldbVersao .= "           union                                                                          ";
      $sSqldbVersao .= "          select distinct id_item_filho                                                   ";
      $sSqldbVersao .= "            from db_menu                                                                  ";
      $sSqldbVersao .= "           where modulo = {$modulo})                                                      ";
      $sSqldbVersao .= "     and not exists  ( select db35_codver                                                 ";
      $sSqldbVersao .= "                         from db_versaolidousuario                                        ";
      $sSqldbVersao .= "                        where db35_codver = db_versao.db30_codver                         ";
      $sSqldbVersao .= "                          and db35_id_usuario = ".db_getsession("DB_id_usuario").")       ";
      $sSqldbVersao .= "   order by db30_codversao, db30_codrelease                                               ";
      
      $result = $cldb_versao->sql_record($sSqldbVersao);

      if( $cldb_versao->numrows > 0 ) {

        for($ii=0;$ii<pg_numrows($result);$ii++){
          $x = pg_result($result,$ii,2);
          $lista[$x]= $x;
        }

        $matriz_item_seleciona = array();

        monta_menu($modulo,$modulo,$espacos,$lista);
        
        // lista as descricoes
        
        $itens_listados = array();
        for($x=0;$x<count($matriz_item_seleciona);$x++){
          $contador = 0;
          $impmat = split("-",$matriz_item_seleciona[$x]);
          for($imp=0;$imp<count($impmat);$imp++){
            $contador += 1;
            if( ! isset($itens_listados[$impmat[$imp]])){
              
              $itens_listados[$impmat[$imp]] = $impmat[$imp] ;
              $sql = "select descricao 
                      from db_itensmenu
                      where id_item = ".$impmat[$imp];
              $resi = pg_exec($sql);
              $descr = pg_result($resi,0,0);

              for($xx=1;$xx<$contador*2;$xx++){
                echo "&nbsp ";
              }
     
              echo "<strong>$descr</strong><br>";
              $sql = "select distinct db30_codversao,db30_codrelease,trim(db32_obs) as db32_obs
                      from db_versaousu
                           inner join db_versao on db32_codver = db30_codver
                      where db30_codver >= $versao_inicial
                        and db32_id_item = ".$impmat[$imp];
              $resi = pg_exec($sql);
              for($o=0;$o<pg_numrows($resi);$o++){
                
                db_fieldsmemory($resi,$o);
     
                for($xx=1;$xx<($contador+1)*2;$xx++){
                  echo "&nbsp ";
                }
              
                echo "<strong>2.".$db30_codversao.".".$db30_codrelease."</strong>";
                echo "&nbsp $db32_obs<br>";

              }

            }
          }    
        }

        echo "<br><br>";

      }else{
        echo "Nao ha atualizacoes para o modulo de <strong>$nome_modulo</strong>.";
      }

    }else{
      
      $sql = "select distinct  i.codproced,descrproced
              from ( select id_item,modulo from (
                     select id_item,modulo
                     from db_menu
                     union
                     select id_item_filho,modulo
                     from db_menu
                     ) as x where id_item in ( 
                                             select distinct db32_id_item 
                                             from db_versaousu
                                             where db32_codver >= $versao_inicial
                                             ) 

                   ) as x
                        inner join db_syscadproceditem i on i.id_item = x.id_item
                        inner join db_syscadproced c on c.codproced = i.codproced
                        inner join db_modulos m on m.id_item = x.modulo
              where modulo = $modulo
              order by i.codproced";

      $result = pg_query($sql);

      if(pg_numrows($result)>0){
        
        echo "<strong>$nome_modulo</strong><br>";

        for($m=0;$m<pg_numrows($result);$m++){
          db_fieldsmemory($result,$m);
          echo "&nbsp&nbsp<strong>$descrproced </strong><br>";
          $sql = "select distinct db30_codversao,db30_codrelease,trim(db32_obs) as db32_obs
                  from db_versaousu
                       inner join db_versao on db30_codver = db32_codver
                       inner join db_syscadproceditem i on i.id_item = db32_id_item
                       inner join db_syscadproced c on c.codproced = i.codproced
                  where db32_codver >= $versao_inicial and c.codproced = $codproced
                  and db32_id_item in 
                  (
                    select id_item
                    from db_menu
                    where modulo = $modulo 
                    union
                    select id_item_filho
                    from db_menu
                    where modulo = $modulo
                  )
                  ";

          $resitem = pg_query($sql);
          for($mi=0;$mi<pg_numrows($resitem);$mi++){
            db_fieldsmemory($resitem,$mi);
            echo "&nbsp&nbsp&nbsp<strong>2.$db30_codversao.$db30_codrelease</strong> $db32_obs<br>";
          }
        }
        echo "<br><br>";
      }
    }
  }

}else{
  echo $mensagem;
}

?>
</td>
</tr>
</table>


</td>
</tr>
</table>

</form>
<?
if ( isset($registra_atualizacao) ){
?>
<form name='form2' method='post'>
<script>
function js_confirma_leitura(){
  top.corpo.db_iframe_confirma_atualizacoes.hide();
  document.form2.submit();
}
</script>
<input name='grava_leitura' value='leitura_confirmada' type='hidden' >
<table width='100%'><tr><td align='center'>
<input name='confirme' value='Confirme Leitura' type='button' onclick='js_confirma_leitura()'>
</td></tr></table>
</form>
<?
}
?>
</body>
</html>