<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_materialestoquegrupo_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmaterialestoquegrupo = new cl_materialestoquegrupo();
$clrotulo               = new rotulocampo();
$clrotulo->label("m65_sequencial");
$clrotulo->label("db121_estrutural");
$oGet = db_utils::postMemory($_GET);
$iAnousu = db_getsession("DB_anousu");


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
      <td height="63" align="center" valign="top">
        <table width="200px" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
            <tr> 
              <td width="4%" align="right" nowrap title="<?=$Tm65_sequencial?>">
                <?=$Lm65_sequencial?>
              </td>
              <td width="96%" align="left" nowrap> 
                <?php
                  db_input("m65_sequencial",10,$Im65_sequencial,true,"text",4,"","chave_m65_sequencial");
                ?>
              </td>
            </tr>
            <tr> 
              <td width="4%" align="right" nowrap title="<?=$Tdb121_estrutural?>">
                <b>Grupo:</br>
              </td>
              <td width="96%" align="left" nowrap> 
                <?php
                  db_input("db121_estrutural",10,$Idb121_estrutural,true,"text",4,"","chave_db121_estrutural");
                ?>
              </td>
            </tr>
            <tr> 
              <td colspan="2" align="center"> 
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_materialestoquegrupo.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr> 
      <td align="center" valign="top"> 
      <?php
      
      
      $sWhere = "1=1";
      $sWhere .= " and m66_anousu = {$iAnousu} ";
      
      if (isset($iTipoConta) && trim($iTipoConta) != '') {
      	$sWhere .= " and db_estruturavalor.db121_tipoconta = {$iTipoConta}"; 
      }

      if ( !empty($oGet->lGruposAtivos) ) {
        $sWhere .= " and materialestoquegrupo.m65_ativo is {$oGet->lGruposAtivos} "; 
      }
      
      if(!isset($pesquisa_chave)){
      	
        /**
         * Os nomes dos campos das tabelas foram mascarados para aparecem no lookup
         */
        if(isset($campos)==false){

           if(file_exists("funcoes/db_func_materialestoquegrupo.php")==true){
             include(modification("funcoes/db_func_materialestoquegrupo.php"));
           } else {
             $campos = "materialestoquegrupo.*";
           }
        }
        $campos .= ",c60_estrut as dl_Conta, c60_descr as dl_Descrição_da_Conta";
        if(isset($chave_m65_sequencial) && (trim($chave_m65_sequencial)!="") ){
          $sql = $clmaterialestoquegrupo->sql_query_conta(null,"distinct ".$campos,"dl_Grupo","m65_sequencial = {$chave_m65_sequencial} and {$sWhere}");
        } else if(isset($chave_db121_estrutural) && (trim($chave_db121_estrutural)!="") ){
          $sql = $clmaterialestoquegrupo->sql_query_conta("","distinct ".$campos,"dl_Grupo"," db121_estrutural like '$chave_db121_estrutural%' and {$sWhere}");
        } else {
          $sql = $clmaterialestoquegrupo->sql_query_conta("","distinct ".$campos,"dl_Grupo",$sWhere);
        }

        $repassa = array();
        
        if(isset($chave_db121_estrutural)){
          $repassa = array("chave_m65_sequencial"=>$chave_m65_sequencial,"chave_db121_estrutural"=>$chave_db121_estrutural);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	
          $result = $clmaterialestoquegrupo->sql_record($clmaterialestoquegrupo->sql_query_conta(null,"*",null,"m65_sequencial = {$pesquisa_chave} and {$sWhere}"));

          if($clmaterialestoquegrupo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."($pesquisa_chave,'$db121_descricao',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
js_tabulacaoforms("form2","chave_db121_estrutural",true,1,"chave_db121_estrutural",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
