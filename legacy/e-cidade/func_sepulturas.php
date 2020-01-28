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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsepulturas = new cl_sepulturas;
$clsepulturas->rotulo->label("cm05_c_numero");
$clsepulturas->rotulo->label("cm05_i_lotecemit");
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

      <tr>
       <td width="4%" align="right" nowrap title="<?php echo $Tcm05_c_numero; ?>">
        <?php echo $Lcm05_c_numero; ?>
       </td>
       <td width="96%" align="left" nowrap>
        <?php db_input("cm05_c_numero",10,$Icm05_c_numero,true,"text",4,"","chave_cm05_c_numero"); ?>
       </td>
      </tr>

     <tr>
      <td width="4%" align="right" nowrap>
       <strong>Quadra:</strong>
      </td>
      <td width="96%" align="left" nowrap>
       <?php db_input("cm05_c_quadra",10,(isset($cm05_c_quadra) ? $cm05_c_quadra : null) ,true,"text",4,"","chave_cm05_c_quadra"); ?>
      </td>
     </tr>

     <tr>
      <td width="4%" align="right" nowrap title="<?php echo $Tcm05_i_lotecemit; ?>">
       <strong>Lote</strong>
      </td>
      <td width="96%" align="left" nowrap>
       <?php db_input("cm05_i_lotecemit",10,$Icm05_i_lotecemit,true,"text",4,"","chave_cm05_i_lotecemit"); ?>
      </td>
     </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
              <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_sepulturas.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
     <?php

     if(!isset($pesquisa_chave)){

      $where = "";
      $and = "";
      if(isset($campos)==false){
         if(file_exists("funcoes/db_func_sepulturas.php")==true){
           include(modification("funcoes/db_func_sepulturas.php"));
         }else{
         $campos = "sepulturas.oid,sepulturas.*";
         }
      }

      if( !empty($cemiterio)){

       $where = " cm22_i_cemiterio = $cemiterio";
       $and = " and ";
      }


      if(isset($chave_cm05_c_numero) && (trim($chave_cm05_c_numero)!="") ){
        $sql = $clsepulturas->sql_query("",$campos,"cm05_i_codigo desc","cm05_c_numero = '$chave_cm05_c_numero'". $and . $where);
      }else if(isset($chave_cm05_c_quadra) && (trim($chave_cm05_c_quadra)!="") ){
        $sql = $clsepulturas->sql_query("",$campos,"cm05_i_codigo desc","cm22_c_quadra = '".strtoupper($chave_cm05_c_quadra)."' ". $and. $where);
      }else if(isset($chave_cm05_i_lotecemit) && (trim($chave_cm05_i_lotecemit)!="") ){
        $sql = $clsepulturas->sql_query("",$campos,"cm05_i_codigo desc","cm23_i_lotecemit = $chave_cm05_i_lotecemit ". $and. $where);
      }else {
        $sql = $clsepulturas->sql_query(null,$campos,"cm05_i_codigo desc", $where);
      }

      if( (isset($chave_cm05_i_lotecemit) && (trim($chave_cm05_i_lotecemit)!="")) &&
          (isset($chave_cm05_c_quadra)    && (trim($chave_cm05_c_quadra)!=""))       ){


        $sWhere = "    cm22_c_quadra = '".strtoupper($chave_cm05_c_quadra)."'
                   and cm23_i_lotecemit = $chave_cm05_i_lotecemit          ";

        if( isset($chave_cm05_c_numero) && trim($chave_cm05_c_numero)!="" ){
          $sWhere .= " and cm05_c_numero = '$chave_cm05_c_numero' ";
        }
        $sql = $clsepulturas->sql_query("",$campos,"cm05_i_codigo desc", $sWhere);
      }

        $repassa = array();
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clsepulturas->sql_record($clsepulturas->sql_query($pesquisa_chave,"*","", $where));
          if($clsepulturas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$cm05_i_codigo',false);</script>";
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
<script type="text/javascript">

(function() {

  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
