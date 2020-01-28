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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tfd_veiculodestino_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaotfd_veiculodestino = new cl_tfd_veiculodestino;
$oDaotfd_veiculodestino->rotulo->label('tf18_i_codigo');
$oDaotfd_veiculodestino->rotulo->label('tf18_i_destino');
$oRotulo = new rotulocampo();
$oRotulo->label('tf03_c_descr');
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
            <td width="4%" align="right" nowrap title="<?=$Ttf18_i_codigo?>">
              <?=$Ltf18_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	            db_input("tf18_i_codigo",10,$Itf18_i_codigo,true,"text",4,"","chave_tf18_i_codigo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ttf18_i_destino?>">
              <?=$Ltf18_i_destino?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("tf18_i_destino",10,$Itf18_i_destino,true,"text",4,"","chave_tf18_i_destino");
		          db_input("tf03_c_descr",50,$Itf03_c_descr,true,"text",4,"","chave_tf03_c_descr");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_veiculodestino.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

           if (file_exists("funcoes/db_func_tfd_veiculodestino.php") == true) {
             require_once("funcoes/db_func_tfd_veiculodestino.php");
           } else {
             $campos = "tfd_veiculodestino.*";
           }

        }

        if (isset($chave_tf18_i_codigo) && (trim($chave_tf18_i_codigo) != "")) {

	        $sSql = $oDaotfd_veiculodestino->sql_query2(null, $campos, 'tf18_d_datasaida desc, tf18_c_horasaida desc', 
                                                      " tf18_i_codigo = $chave_tf18_i_codigo "
                                                     );

        } elseif (isset($chave_tf18_i_destino) && (trim($chave_tf18_i_destino) != '') ) {

	        $sSql = $oDaotfd_veiculodestino->sql_query2(null, $campos, 'tf18_d_datasaida desc, tf18_c_horasaida desc',
                                                      " tf18_i_destino = $chave_tf18_i_destino "
                                                     );

        } elseif (isset($chave_tf03_c_descr) && (trim($chave_tf03_c_descr) != '')) {

	        $sSql = $oDaotfd_veiculodestino->sql_query2(null, $campos, 'tf18_d_datasaida desc, tf18_c_horasaida desc',
                                                      " tf03_c_descr like '$chave_tf03_c_descr%' "
                                                     );

        } else {

          $sSql = $oDaotfd_veiculodestino->sql_query2(null, $campos, 'tf18_d_datasaida desc, tf18_c_horasaida desc',
                                                      ''
                                                     );

        }
        
        $repassa = array();
        if (isset($chave_tf18_i_codigo)) {
          $repassa = array("chave_tf18_i_codigo"=>$chave_tf18_i_codigo,"chave_tf18_i_codigo"=>$chave_tf18_i_codigo);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != '') {

          $sSql = $oDaotfd_veiculodestino->sql_query($pesquisa_chave);
          $result = $oDaotfd_veiculodestino->sql_record($sSql);
          if ($oDaotfd_veiculodestino->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf18_i_codigo',false);</script>";

          } else {
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }

        } else {
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
if (!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_tf18_i_codigo",true,1,"chave_tf18_i_codigo",true);
</script>