<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhferiasperiodo_classe.php"));
db_postmemory($_POST);
db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhferiasperiodo = new cl_rhferiasperiodo;
$clrhferiasperiodo->rotulo->label("rh110_sequencial");
$clrhferiasperiodo->rotulo->label("rh110_sequencial");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top"> 
      <?php

      $sWhere = ' 1=1 ';

      if (!empty($situacao)) {
        $sWhere .= " and rh110_situacao = $situacao ";
      }

      if (!empty($matricula)) {
        $sWhere .= " and rh01_regist = $matricula ";
      }

      if (!isset($pesquisa_chave)) {

        $campos  = "rh109_sequencial, rh110_sequencial, rh01_regist, z01_nome, rh109_periodoaquisitivoinicial, rh109_periodoaquisitivofinal, rh110_datainicial, rh110_datafinal, rh110_dias";
        $sql     = $clrhferiasperiodo->sql_query_dados("", $campos, "rh110_sequencial desc", $sWhere);
        $repassa = array();
        if(isset($chave_rh110_sequencial)){
          $repassa = array("chave_rh110_sequencial"=>$chave_rh110_sequencial,"chave_rh110_sequencial"=>$chave_rh110_sequencial);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sWhere .= ' and rh110_sequencial = ' . $pesquisa_chave;
          $result = $clrhferiasperiodo->sql_record($clrhferiasperiodo->sql_query("", null, null, $sWhere));
          if($clrhferiasperiodo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh110_sequencial',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_rh110_sequencial",true,1,"chave_rh110_sequencial",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
