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
include("dbforms/db_funcoes.php");
include("classes/db_tfd_pedidotfd_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaotfd_pedidotfd = new cl_tfd_pedidotfd;
$oRotulo = new rotulocampo;
$oDaotfd_pedidotfd->rotulo->label('tf01_i_codigo');
$oDaotfd_pedidotfd->rotulo->label('tf01_i_cgsund');
$oRotulo->label('z01_v_nome');
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
            <td  nowrap title="<?=$Ttf01_i_codigo?>">
              <?=$Ltf01_i_codigo?>
            </td>
            <td nowrap colspan="3"> 
              <?
		          db_input("tf01_i_codigo",10,$Itf01_i_codigo,true,"text",4,"","chave_tf01_i_codigo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=$Ttf01_i_codigo?>">
              <?=$Ltf01_i_cgsund?>
            </td>
            <td nowrap> 
              <?
		          db_input("tf01_i_cgsund",10,$Itf01_i_cgsund,true,"text",4,"","chave_tf01_i_cgsund");
		          ?>
            </td>
            <td nowrap title="<?=$Ttf01_i_codigo?>">
              <?=$Lz01_v_nome?>
            </td>
            <td nowrap> 
              <?
		          db_input("z01_v_nome",50,$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_pedidotfd.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {

           if(file_exists("funcoes/db_func_tfd_pedidotfd.php")==true) {
             include("funcoes/db_func_tfd_pedidotfd.php");
           } else {
             $campos = "tfd_pedidotfd.*";
           }

        }

        $sSep = '';
        $sDesistencia = '';
        if(isset($chave_desistencia)) {

          if($chave_desistencia == 2) { // traz só os pedidos em que a situação está marcada como 4 - Desistência
            
            $sSep = ' and ';
            $sDesistencia = ' tf01_i_situacao = 4 ';

          } else { // traz só os pedidos que não estão marcados como desistência

            $sSep = ' and ';
            $sDesistencia = ' tf01_i_situacao != 4 ';

          }

        }
        if(isset($chave_tf01_i_codigo) && (trim($chave_tf01_i_codigo) != '') ) {

	        $sql = $oDaotfd_pedidotfd->sql_query(null, $campos, 'tf01_i_codigo', 
                                             " tf01_i_codigo = $chave_tf01_i_codigo $sSep $sDesistencia");

        } else if(isset($chave_tf01_i_cgsund) && (trim($chave_tf01_i_cgsund) != '')) {

	        $sql = $oDaotfd_pedidotfd->sql_query(null, $campos, 'tf01_i_cgsund', 
                                             " tf01_i_cgsund = $chave_tf01_i_cgsund $sSep $sDesistencia");

        } else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome) != '')) {

	        $sql = $oDaotfd_pedidotfd->sql_query(null, $campos, 'z01_v_nome', 
                                             " z01_v_nome like '$chave_z01_v_nome%' $sSep $sDesistencia");

        } else {
          $sql = $oDaotfd_pedidotfd->sql_query(null, $campos, 'tf01_i_codigo desc', $sDesistencia);
        }

        if(isset($nao_mostra)) {
          
          $sSep = '';
          $aFuncao = explode('|', $funcao_js);
          $rs = $oDaotfd_pedidotfd->sql_record($sql);
           if($oDaotfd_pedidotfd->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_tf01_i_codigo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {
               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep = ', ';

             }
             $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }
        }


        $repassa = array();
        if(isset($chave_tf01_i_codigo)) {
          $repassa = array('chave_tf01_i_codigo'=>$chave_tf01_i_codigo);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if($pesquisa_chave != null && $pesquisa_chave != '') {

          $result = $oDaotfd_pedidotfd->sql_record($oDaotfd_pedidotfd->sql_query($pesquisa_chave));
          if($oDaotfd_pedidotfd->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf01_i_codigo',false);</script>";
            
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
if(!isset($pesquisa_chave)){
?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_tf01_i_codigo",true,1,"chave_tf01_i_codigo",true);
</script>