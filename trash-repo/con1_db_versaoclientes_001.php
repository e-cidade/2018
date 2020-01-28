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
include("classes/db_db_versaoclientes_classe.php");
include("classes/db_clientes_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_versaoclientes = new cl_db_versaoclientes;
$clclientes = new cl_clientes;


$sql = " select clientes.*,db30_codver , '2.'||db30_codversao ||'.'|| db30_codrelease as versao 
               from clientes 
                    left join db_versao on at01_codver = db30_codver 
               where at01_status = true and at01_base = true
               order by at01_cidade,at01_nomecli";
$result = pg_query($sql);


if(isset($atualiza)){
   
  $dberro = false;

  for($i=0;$i<pg_numrows($result);$i++){
  
     db_fieldsmemory($result,$i);
     
     $data = "data_".$at01_codcli."_dia";
     
     if ( isset($$data) && $$data > 0){

       $data_ano = "data_".$at01_codcli."_ano";
       $data_mes = "data_".$at01_codcli."_mes";
       $data_dia = "data_".$at01_codcli."_dia";
  

       $obs  = "obs_".$at01_codcli;
       
       $resu = $cldb_versaoclientes->sql_record($cldb_versaoclientes->sql_query_file(null,"db19_sequen",null," db19_codver = $db29_codver and db19_codcli = $at01_codcli "));
       if( $cldb_versaoclientes->numrows > 0 ){
         db_fieldsmemory($resu,0);
         $cldb_versaoclientes->db19_sequen = $db19_sequen;
         $cldb_versaoclientes->db19_codver = $db29_codver;
         $cldb_versaoclientes->db19_codcli = $at01_codcli;
         $cldb_versaoclientes->db19_data   = $$data_ano."-".$$data_mes."-".$$data_dia;
         $HTTP_POST_VARS["db19_obs"] = "";
         $cldb_versaoclientes->db19_obs    = $$obs;
         $cldb_versaoclientes->alterar($db19_sequen);

         if($cldb_versaoclientes->erro_status == "0"){
           $dberro = true;
           $msg = $cldb_versaoclientes->erro_msg;
           break;
         }
         
       }else{
         $cldb_versaoclientes->db19_sequen = 0;
         $cldb_versaoclientes->db19_codver = $db29_codver;
         $cldb_versaoclientes->db19_codcli = $at01_codcli;
         $cldb_versaoclientes->db19_data   = $$data_ano."-".$$data_mes."-".$$data_dia;
         $cldb_versaoclientes->db19_obs    = $$obs;
         $cldb_versaoclientes->incluir(0);

         if($cldb_versaoclientes->erro_status == "0"){
           $dberro = true;
           $msg = $cldb_versaoclientes->erro_msg;
           break;
         }
       
       }
       
       // atualiza o cadastro dos clientes
       
       $clclientes->at01_codver = $db29_codver;
       $clclientes->at01_codcli = $at01_codcli;
       $clclientes->alterar($at01_codcli);

       if($clclientes->erro_status == "0"){
         $dberro = true;
         $msg = $clclientes->erro_msg;
         break;
       }
       
       
     }

  }  
  if($dberro==true){
    db_msgbox($msg);
  }

}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_mostratarefa(codigo){
  
 js_OpenJanelaIframe('','db_iframe_tarefa','ate2_contarefa001.php?menu=false&chavepesquisa='+codigo);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<form name='form1' method='post'>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
<tr>
<td align='center' colspan='7'><input name='atualiza' value='Atualiza' type='submit'><input name='db29_codver' type='hidden' value='<?=$db29_codver?>'></td>
</tr>
<tr>
<td>Código</td>
<td>Nome do Cliente</td>
<td>Cidade</td>
<td>Atualiza</td>
<td>Última Versão</td>
<td>Data Atualização</td>
<td>Observação</td>
</tr>
   <?

$sql = " select clientes.*,db30_codver , '2.'||db30_codversao ||'.'|| db30_codrelease as versao 
               from clientes 
                    left join db_versao on at01_codver = db30_codver 
               where at01_status = true and at01_base = true
               order by at01_cidade,at01_nomecli";
$result = pg_query($sql);
  
      //$result = $clclientes->sql_record($clclientes->sql_query(null,"at01_codcli,at01_nomecli,at01_cidade,at01_base,at01_obs","at01_nomecli"," at01_status = true"));
     //db_criatabela($result);
      for($i=0;$i<pg_numrows($result);$i++){
        db_fieldsmemory($result,$i); 
        echo "<tr>";
        echo "<td align=\"left\" valign=\"top\">$at01_codcli</td>"; 
        echo "<td align=\"left\" valign=\"top\">$at01_nomecli</td>"; 
        echo "<td align=\"left\" valign=\"top\">$at01_cidade</td>"; 
        echo "<td align=\"center\" valign=\"top\">".db_formatar($at01_base,'b')."</td>"; 
        echo "<td align=\"center\" valign=\"top\" title='$db30_codver'>$versao &nbsp";
        echo "</td>"; 
        echo "<td align=\left\" valign=\"top\">";

        $sql = "select db19_data,db19_obs from db_versaoclientes where db19_codver = $db29_codver and db19_codcli = $at01_codcli";
        $resu = pg_query($sql);
        if(pg_numrows($resu)>0){
          db_fieldsmemory($resu,0);
          $data_ano = substr($db19_data,0,4);
          $data_mes = substr($db19_data,5,2);
          $data_dia = substr($db19_data,8,2);
          $obs = "db19_obs".$at01_codcli;
          global $$obs;
        }else{
          $data_ano = "";
          $data_mes = "";
          $data_dia = "";
        }

        db_inputdata("data_".$at01_codcli,$data_dia,$data_mes,$data_ano,true,'text',2);
        echo "</td>"; 
        echo "<td align=\left\" valign=\"top\">";
           db_input("obs_".$at01_codcli,50,0,true,'text',2);
        echo "</td>"; 
        echo "</tr>";      
      }
    ?>
</table>
</form>
</body>
</html>