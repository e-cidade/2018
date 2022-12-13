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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhlota_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhlota = new cl_rhlota();
$clrhlota->rotulo->label("r70_codigo"); 
$clrhlota->rotulo->label("r70_estrut");
$clrhlota->rotulo->label("r70_descr");

if (isset($chave_r70_codigo) && !DBNumber::isInteger($chave_r70_codigo)) {
  $chave_r70_codigo = '';
}

$chave_r70_estrut = isset($chave_r70_estrut) ? stripslashes($chave_r70_estrut) : '';

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
      <form name="form2" method="post" action="" >
        <fieldset>
          <legend>Pesquisa de Lotação</legend>
          <table width="35%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="left" nowrap title="<?php echo $Tr70_codigo; ?>">
                <?php echo $Lr70_codigo; ?>
              </td>
              <td width="96%" align="left" nowrap> 
                <?php
                  db_input("r70_codigo", 4, $Ir70_codigo, true, "text", 4, "", "chave_r70_codigo");
                ?>
              </td>
            </tr>
            <tr> 
              <td width="4%" align="left" nowrap title="<?php echo $Tr70_estrut; ?>">
                <?php echo $Lr70_estrut; ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                  db_input("r70_estrut", 20, $Ir70_estrut, true, "text", 4, "", "chave_r70_estrut");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tr70_descr?>">
                <?=$Lr70_descr?>
              </td>
              <td width="96%" align="left" nowrap> 
                <?
             db_input("r70_descr",40,$Ir70_descr,true,"text",4,"","chave_r70_descr");
             ?>
              </td>
            </tr>
            <tr> 
               <td width="4%" align="left" nowrap title="Selecionar todos, ativos ou inativos"><b>Seleção por:</b></td>
               <td width="96%" align="left" nowrap>
               <?
               if(!isset($opcao)){
               $opcao = "t";
               }
               if(!isset($opcao_bloq)){
                $opcao_bloq = 1;
               }
               $arr_opcao = array("i"=>"Todos","t"=>"Ativos","f"=>"Inativos");
               db_select('opcao',$arr_opcao,true,$opcao_bloq,"onchange='js_reload();'"); 
               ?>
               </td>
            </tr>
          </table>
        </fieldset>
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onClick="return js_valida(arguments[0])"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlota.hide();">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
        <?
        $where_ativo = "";
        if(isset($opcao) && trim($opcao)!="i"){
          $where_ativo = " and r70_ativo='$opcao' ";
        }

        $dbwhere = "";
        if(isset($instit)){
          $dbwhere = " and r70_instit = $instit ";
        }
        $chave_r70_estrut = addslashes($chave_r70_estrut);

        if (!isset($pesquisa_chave)) {

          if (!isset($campos)) {

            if (file_exists("funcoes/db_func_rhlota.php")) {
              include("funcoes/db_func_rhlota.php");
            } else {
              $campos = "rhlota.*";
            }
          }

          if (isset($chave_r70_codigo) && !empty($chave_r70_codigo)) {
  	         $sql = $clrhlota->sql_query(null,$campos,"r70_codigo"," r70_codigo = $chave_r70_codigo $dbwhere $where_ativo ");

          } elseif(isset($chave_r70_descr) && !empty($chave_r70_descr) ){
  	         $sql = $clrhlota->sql_query(null,$campos,"r70_descr"," r70_descr like '$chave_r70_descr%' $dbwhere $where_ativo ");

          } elseif (isset($chave_r70_estrut) && !empty($chave_r70_descr)){
  	         $sql = $clrhlota->sql_query(null,$campos,"r70_estrut"," r70_estrut like '$chave_r70_estrut%' $dbwhere $where_ativo ");

          } else {
             $sql = $clrhlota->sql_query(null,$campos,"r70_codigo"," 1=1 $dbwhere $where_ativo ");
          }

          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe");
        } else {

          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $result = $clrhlota->sql_record($clrhlota->sql_query(null,"*","r70_codigo"," r70_codigo = $pesquisa_chave $dbwhere "));

            if ($clrhlota->numrows != 0) {
              db_fieldsmemory($result, 0);
              echo "<script>" . $funcao_js . "('$r70_descr', false);</script>";

            } else {
  	          echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado', true);</script>";
            }
          } else {
  	        echo "<script>" . $funcao_js . "('', false);</script>";
          }
        }
        ?>
      </fieldset>
    </td>
  </tr>
</table>
</body>
</html>
<?php if (!isset($pesquisa_chave)) { ?>
  <script>

    function js_valida(event) {
      document.getElementById('chave_r70_codigo').onkeyup = event;
      return true;
    }

  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_r70_estrut", true, 1, "chave_r70_estrut", true);
</script>