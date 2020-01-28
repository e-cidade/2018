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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("classes/db_calend_classe.php");

echo "<html>\n";
echo "<head>\n";
echo "<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
echo "<meta http-equiv=\"Expires\" CONTENT=\"0\">\n";
echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script>\n";
echo "<link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\">\n";
echo "</head>\n";
echo "<body align=\"center\" bgcolor=#CCCCCC leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"a=1\" >\n";
echo "<center>";  

echo "</center>";  

$sql = " 
  select at40_sequencial,at01_nomecli,at40_diafim  as dl_datacalend,at40_diaini,at40_obs,at46_descr,nome::text,
             sum( to_timestamp(at43_diafim||' '||at43_horafim, 'YYYY-MM-DD HH24:MI') -
                  to_timestamp(at43_diaini||' '||at43_horainidia, 'YYYY-MM-DD HH24:MI')) as dl_tempo,
              at40_progresso
  from tarefalog
     inner join tarefa           on at43_tarefa = at40_sequencial
     inner join db_usuarios      on id_usuario = at43_usuario
     left join tarefasituacao    on at47_tarefa = at40_sequencial 
     left join tarefaclientes    on at70_tarefa = at40_sequencial 
     left join clientes          on at01_codcli = at70_cliente 
     left join tarefaproced      on at41_tarefa = at40_sequencial
     left join tarefacadsituacao on at46_codigo = at47_situacao
    

  
    where at43_usuario = $usuario and
          at43_diaini = '$data'
     
    group by at40_sequencial,at01_nomecli,at40_diafim,at40_diaini,at40_obs,at46_descr,nome,at40_progresso
 ";  
  
db_lovrot($sql,15,"()",'','js_pesquisa_tarefa|at40_sequencial');

echo "</body>";
echo "</html>";
?>
<script>
function js_pesquisa_tarefa (tarefa){
  parent.js_OpenJanelaIframe('','db_iframe_tarefa_cons2','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true);
}
</script>