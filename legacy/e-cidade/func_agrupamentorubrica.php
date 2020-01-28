<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_agrupamentorubrica_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clagrupamentorubrica = new cl_agrupamentorubrica;
$clagrupamentorubrica->rotulo->label("rh113_sequencial");
$clagrupamentorubrica->rotulo->label("rh113_codigo");
$clagrupamentorubrica->rotulo->label("rh113_descricao");
$clagrupamentorubrica->rotulo->label("rh113_tipo");

$aTipo    = array(''  => 'Todos',
                  '1' => 'Provento', 
                  '2' => 'Desconto');
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
        <table width="35%" border="0" align="center" cellspacing="0">
       <form name="form2" method="post" action="" >

          <tr style="display:none;"> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh113_sequencial; ?>">
              <?php echo $Lrh113_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_input("rh113_sequencial", 10, $Irh113_sequencial, true, "text", 4, "", "chave_rh113_sequencial"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh113_codigo; ?>">
              <?php echo $Lrh113_codigo; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_input("rh113_codigo", 10, $Irh113_codigo, true, "text", 4, "", "chave_rh113_codigo"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh113_descricao; ?>">
              <?php echo $Lrh113_descricao; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_input("rh113_descricao", 10, $Irh113_descricao, true, "text", 4, "", "chave_rh113_descricao"); ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="<?php echo $Trh113_tipo; ?>">
              <?php echo $Lrh113_tipo; ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php db_select("chave_rh113_tipo", $aTipo, true, 1, null); ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_agrupamentorubrica.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php
      if ( !isset($pesquisa_chave) ) {

        if ( isset($campos) == false ) {

          if ( file_exists("funcoes/db_func_agrupamentorubrica.php") == true ) {
            include("funcoes/db_func_agrupamentorubrica.php");
          } else {
            $campos = "agrupamentorubrica.*";
          }
        }

        $aWhere = array();

        if ( !empty($chave_rh113_codigo) ) {
          $aWhere[] = "rh113_codigo like '%$chave_rh113_codigo%'";
        }

        if ( !empty($chave_rh113_descricao) ) {
          $aWhere[] = "rh113_descricao like '%$chave_rh113_descricao%'";
        }

        if ( !empty($chave_rh113_tipo) ) {
          $aWhere[] = "rh113_tipo = $chave_rh113_tipo";
        }

        $sWhere = implode(' and ', $aWhere);

        if ( !empty($chave_rh113_sequencial) ) {
          $sql = $clagrupamentorubrica->sql_query($chave_rh113_sequencial, $campos, "rh113_sequencial");
        } else {
          $sql = $clagrupamentorubrica->sql_query(null,  $campos, "rh113_sequencial", $sWhere);
        }

        $repassa = array();

        if ( isset($chave_rh113_sequencial) ) {
          $repassa = array(
            "chave_rh113_sequencial" => $chave_rh113_sequencial
          );
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clagrupamentorubrica->sql_record($clagrupamentorubrica->sql_query($pesquisa_chave));
          if($clagrupamentorubrica->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh113_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_rh113_sequencial",true,1,"chave_rh113_sequencial",true);
</script>