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
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhrubricas_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label("rh27_rubric");
$clrhrubricas->rotulo->label("rh27_instit");
$clrhrubricas->rotulo->label("rh27_descr");

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<style type="text/css">
  #chave_rh27_rubric, #opcao{
    width: 80px;
  }

</style>

  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lrh27_rubric?></label></td>
          <td><? db_input("rh27_rubric",4,$Irh27_rubric,true,"text",4,"","chave_rh27_rubric"); ?></td>
        </tr>
        <tr>
          <td><b>Seleção por:</b></td>
          <td>
          <?
          if (!isset($opcao)) {
            $opcao = "t";
          }

          if (!isset($opcao_bloq)) {
          	$opcao_bloq = 1;
          }

          $arr_opcao = array(
              "i" => "Todos",
              "t" => "Ativos",
              "f" => "Inativos"
            );

          db_select('opcao',$arr_opcao,true,$opcao_bloq); 
          ?>
          </td>
        </tr>
        <tr>
          <td>
            <label><?=$Lrh27_descr?></label>
          </td>
          <td> 
            <? db_input("rh27_descr",30,$Irh27_descr,true,"text",4,"","chave_rh27_descr"); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="button" id="limpar" value="Limpar" onClick="window.location.href = window.location.href +' '" />
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhrubricas.hide();" />
  </form>
      <?
      
      $dbwhere = " and rh27_instit = " . db_getsession("DB_instit");
      $where_ativo = "";
      if (isset($opcao) && trim($opcao) != "i") {
        $where_ativo = " and rh27_ativo='$opcao' ";
      }

      if (isset($datlimit)) {
      	if ($datlimit == 'true') {
      	  $dbwhere .= " and rh27_limdat is true  ";	
      	} else  if ($datlimit == 'false') {
          $dbwhere .= " and rh27_limdat is false ";      		
      	}
      }
      
      if (isset($fixas)) {
        if ($fixas == 'true') {
          $dbwhere .= " and rh27_tipo = 1 "; 
        } else  if ($fixas == 'false') {
          $dbwhere .= " and rh27_tipo = 2 ";         
        }      	
      }

      if (isset($tipo_rubrica) && $tipo_rubrica != '') {
        $dbwhere .= " and rh27_pd = ".$tipo_rubrica;
      }

      if (!empty($_GET["somentcomformula"])) {
        $dbwhere .= " and (trim(rh27_form) <> '' or trim(rh27_form2) <> '' or trim(rh27_form3) <> '') ";
      }

      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhrubricas.php")==true){
           include(modification("funcoes/db_func_rhrubricas.php"));
           }else{
           $campos = "rhrubricas.*";
           }
        }
        if(isset($chave_rh27_rubric) && (trim($chave_rh27_rubric)!="") ){
	         $sql = $clrhrubricas->sql_query(null,null,$campos,"rh27_rubric"," rh27_rubric = '$chave_rh27_rubric' ".$dbwhere.$where_ativo );
        }else if(isset($chave_rh27_descr) && (trim($chave_rh27_descr)!="") ){
	         $sql = $clrhrubricas->sql_query("",null,$campos,"rh27_descr"," rh27_descr like '$chave_rh27_descr%' ".$dbwhere.$where_ativo );
        }else{
           $sql = $clrhrubricas->sql_query("",null,$campos,"rh27_rubric"," 1=1 ".$dbwhere.$where_ativo );
        }
        echo "<div class='container'>";
        echo "  <fieldset>";
        echo "    <legend>Resultado da Pesquisa</legend>";


        db_lovrot($sql,15,"()","",$funcao_js);

        echo "  </fieldset>";
        echo "</div>";

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhrubricas->sql_record($clrhrubricas->sql_query(null,null,"*",""," rh27_rubric = '$pesquisa_chave' ".$dbwhere));
          if($clrhrubricas->numrows!=0){

            db_fieldsmemory($result,0);

            $sRetorno = "<script>".$funcao_js."('$rh27_descr',false);</script>";

            if ( isset($oGet->campos_adicionais) ) {

              $rh27_obs    = preg_replace('[\n\r]+', ' ', $rh27_obs);
              $sParametros = "{ 'rh27_descr'            : '$rh27_descr',"; 
              $sParametros.= "  'rh27_limdat'           : '$rh27_limdat',";
              $sParametros.= "  'rh27_presta'           : '$rh27_presta',";
              $sParametros.= "  'rh27_obs'              : '$rh27_obs',";
              $sParametros.= "  'rh27_valorlimite'      :  $rh27_valorlimite,";
              $sParametros.= "  'rh27_quantidadelimite' :  $rh27_quantidadelimite,";
              $sParametros.= "  'rh27_tipobloqueio'     : '$rh27_tipobloqueio'}";
           
              $sRetorno = "<script>{$funcao_js}({$sParametros} ,false)</script>";
            }

            echo $sRetorno;
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
         echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
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
js_tabulacaoforms("form2","chave_rh27_descr",true,1,"chave_rh27_descr",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
