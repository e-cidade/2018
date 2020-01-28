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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_processa(tarefa){
  js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true,'30');
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?



$sql = "
  select * from (
  select at02_codatend,login,case when t.at40_sequencial is null then ta.at40_sequencial else t.at40_sequencial end as at40_sequencial ,
                    descrproced,at05_data,at05_horaini,at05_horafim, 
                             case when t.at40_progresso is null then ta.at40_progresso else t.at40_progresso end as at40_progresso, at05_solicitado,at05_feito,at54_sequencial,
         db_sysmodulo.codmod,
         db_usuarios.id_usuario,
         db_depart.coddepto,db_syscadproced.codproced,at01_codcli
                             
  from atenditem
       left join atendimento         on at02_codatend   = at05_codatend
       left join clientes on clientes.at01_codcli = at02_codcli
       
       left  join tarefaitem         on at44_atenditem  = at05_seq
       left  join tarefa  t       on at44_tarefa        = t.at40_sequencial

       left  join atenditemtarefa      on at18_atenditem  = at05_seq
       left  join tarefa  ta       on at18_tarefa        = ta.at40_sequencial

       left  join tecnico         on at03_codatend   = at05_codatend
       left  join db_usuarios         on id_usuario        = at03_id_usuario
       
       left  join db_depusu           on db_usuarios.id_usuario = db_depusu.id_usuario
       left  join db_depart           on db_depusu.coddepto = db_depart.coddepto
       
       left join atenditemmotivo     on at34_atenditem  = at05_seq
       left join tarefacadmotivo     on at54_sequencial   = at34_tarefacadmotivo
       left join atenditemmod         on at22_atenditem    = at05_seq
       left join db_sysmodulo          on at22_modulo       = db_sysmodulo.codmod
       left join atenditemsyscadproced on at29_atenditem    = at05_seq
       left join db_syscadproced       on at29_syscadproced = db_syscadproced.codproced
       
         left  join atendarea             on at02_codatend = at28_atendimento
         left  join atendcadarea          on at28_atendcadarea = at26_sequencial
  Where at01_codcli not in (25) and ";
if($mes>0){
   $sql .= " date_part('month',at02_datafim) = $mes and "; 
}
$sql .= "
    at02_datafim >= '$dataini'
    and  at02_datafim <= '$datafim'
    and  at26_sequencial not in (6,7)
";
if($codigo != ""){
  $sql .= "  and at28_atendcadarea = $codigo ";
}
$sql .= "
  ) as x 
  where 1 = 1 
    ";


if($tipo!=0){
  if($tipo == 1 ){
    $sql .= " and at54_sequencial = 13";
  }else if($tipo == 2 ){
    $sql .= " and at54_sequencial = 13 and at40_progresso = 100";
  }else if($tipo == 3 ){
    $sql .= " and at54_sequencial = 1";
  }else if($tipo == 4 ){
    $sql .= " and at54_sequencial = 1 and at40_progresso = 100";
  }else if($tipo == 5 ){
    $sql .= " and at54_sequencial = 2";
  }else if($tipo == 6 ){
    $sql .= " and at54_sequencial = 2 and at40_progresso = 100";
  }else if($tipo == 7 ){
    $sql .= " and at54_sequencial = 6";
  }else if($tipo == 8 ){
    $sql .= " and at54_sequencial = 6 and at40_progresso = 100";
  }
}
if($scodigo!=""){
  $campos = split("-",$scodigo);
  $sql .= " and ".$campos[0]." = ".$campos[1];
}

$result = pg_query("select count(*) from (select distinct at40_sequencial from ($sql) as x ) as x ");
echo "<strong>Tarefas Envolvidas:</strong>".pg_result($result,0,0)."<br>";


db_lovrot($sql,50,"()","","js_processa|at40_sequencial");


?>
</body>
</html>