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
include("dbforms/db_funcoes.php");

//@ 08/09/2005
//@ DBSeller Informática LTDA
//@

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani
echo "
<html>
<head>
</head>
<body>
<table>
";

$sql = "select * from (
       select  at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, (select count(*) from tarefaagenda where at77_tarefa = w.at77_tarefa) as quantas

       from tarefaagenda w
            inner join tarefa on at40_sequencial = at77_tarefa

              inner join tarefasyscadproced on at40_sequencial = at37_tarefa
              inner join db_syscadproced on codproced = at37_syscadproced
              inner join atendcadarea on at26_sequencial = codarea

           inner join db_usuarios on id_usuario = at77_id_usuario
       where at77_id_usuario = ".(isset($tecnico_solicitado)?$tecnico_solicitado:db_getsession("DB_id_usuario"))." and at77_dataagenda = '".$data."'
       group by at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome, login, at77_dataagenda, at25_descr
        ) as x order by at25_descr";


if ( $tipo_relatorio == "agendaoutros" ){

 $sql = "select * from (
         select at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, count(*) as quantas

         from tarefaagenda 
              inner join tarefa on at40_sequencial = at77_tarefa

              inner join tarefasyscadproced on at40_sequencial = at37_tarefa
              inner join db_syscadproced on codproced = at37_syscadproced
              inner join atendcadarea on at26_sequencial = codarea

             inner join db_usuarios on id_usuario = at77_id_usuario
         where at77_usuenvolvido = ".(isset($tecnico_solicitado)?$tecnico_solicitado:db_getsession("DB_id_usuario"))." 
           and at77_dataagenda = '".$data."'
           and at77_datavalidade is null 
           and at77_id_usuario != ".db_getsession("DB_id_usuario")."
         group by at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome, login, at77_dataagenda, at25_descr
        ) as x order by at25_descr";

 $str .= "<tr><td width='100%' align='left'><strong>Agenda Gerada para Acompanhamento (Tarefas à realizar) </strong></td></tr>";

} else if ( $tipo_relatorio == "agendavencida" ){

  $sql = "select * from (
         select at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome as nomeusuario, login, at77_dataagenda, at25_descr, count(*) as quantas
         from tarefaagenda 
              inner join tarefa on at40_sequencial = at77_tarefa

              inner join tarefasyscadproced on at40_sequencial = at37_tarefa
              inner join db_syscadproced on codproced = at37_syscadproced
              inner join atendcadarea on at26_sequencial = codarea

              inner join db_usuarios on id_usuario = at77_id_usuario
         where at77_id_usuario = ".(isset($tecnico_solicitado)?$tecnico_solicitado:db_getsession("DB_id_usuario"))." 
           and at77_dataagenda < '".$data."'
           and at77_datavalidade is null 
           and at77_usuenvolvido = ".db_getsession("DB_id_usuario")."
         group by at77_tarefa,at40_descr,at77_datainclusao, at77_observacao, nome, login, at77_dataagenda, at25_descr
        ) as x order by at25_descr,at77_dataagenda";
//die($sql);
  $str .= "<tr><td width='100%' align='left'><strong>Tarefas com Agenda Vencida à realizar</strong></td></tr>";

} else{

 $str .= "<tr><td width='100%' align='left'><strong>Tarefas à realizar</strong></td></tr>";

}

$result = pg_exec($sql);

if (pg_numrows($result)>0){
 $arealista = "lista";
 for($i=0;$i<pg_numrows($result);$i++){


   $codtarefa     = pg_result($result,$i,'at77_tarefa');
   $descr         = pg_result($result,$i,'at40_descr');
   $datainclusao  = db_formatar(pg_result($result,$i,'at77_datainclusao'),'d');
   $observacao    = pg_result($result,$i,'at77_observacao');
   $nomeusuario   = pg_result($result,$i,'nomeusuario');
   $loginusuario   = pg_result($result,$i,'login');
   $dataagenda= pg_result($result,$i,'at77_dataagenda');
   $area= pg_result($result,$i,'at25_descr');
   $quantas= pg_result($result,$i,'quantas');

   if ($area != $arealista){
     $arealista = $area;
     $str .= "<tr><td ><font size='1' color='red'><strong>$arealista<strong></font></td></tr>";
   }
   if ( $tipo_relatorio == "agendaoutros" ){
     $str .= "<tr><td ><font size='1' color='red'><strong><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a> <strong title='$nomeusuario'>$loginusuario</strong> - $quantas -  <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
   }else if ( $tipo_relatorio == "agendavencida" ){
     $str .= "<tr><td ><font size='1' ><strong><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a> $dataagenda <strong title='$nomeusuario'>$loginusuario</strong> -  $quantas -  <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
   }else{
     $str .= "<tr><td ><font size='1' color='darkblue'><strong><a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a> <strong title='$nomeusuario'>$loginusuario</strong></strong> - $quantas - <strong>$observacao</strong> - $descr - $datainclusao </font></td></tr>";
   }
 }

}else{
 $str .= "<tr><td><font size='1'>&nbsp</font></td></tr>";
}
echo $str;
echo "
<input name='printer' type='button' value='Imprimir Agenda de ".db_formatar($data,'d')."' onclick='window.print()'>
</table>
</body>
</html>
";