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
require_once("classes/db_sau_encaminhamentos_classe.php");
require_once("classes/db_cgs_und_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_encaminhamentos = new cl_sau_encaminhamentos;
$oClcgs_und = new cl_cgs_und;
$clsau_encaminhamentos->rotulo->label("s142_i_codigo");
$oClcgs_und->rotulo->label("z01_v_nome");

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
            <td width="4%" align="right" nowrap title="<?=$Ts142_i_codigo?>">
              <?=$Ls142_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("s142_i_codigo",5,$Is142_i_codigo,true,"text",4,"","chave_s142_i_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts142_i_codigo?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	            db_input("z01_v_nome",55,$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_encaminhamentos.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_sau_encaminhamentos.php")==true){
             require_once("funcoes/db_func_sau_encaminhamentos.php");
           }else{
           $campos = "sau_encaminhamentos.*";
           }
        }
        $sWhere = '';
        $sSep = '';
        if(isset($lFiltraTfd)) {

          $sSep = ' and ';
          $sWhere = ' s142_i_codigo not in (select tf30_i_encaminhamento from tfd_encaminpedidotfd) ';

        }
        if(!isset($chave_profissional) || empty($chave_profissional) || !isset($chave_unidade) || empty($chave_unidade)) {

          if(isset($chave_s142_i_codigo) && (trim($chave_s142_i_codigo)!="") ){
	          $sql = $clsau_encaminhamentos->sql_query2('','',''," s142_i_codigo = $chave_s142_i_codigo $sSep$sWhere");
          }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
	          $sql = $clsau_encaminhamentos->sql_query2("",''," z01_v_nome"," z01_v_nome ilike '$chave_z01_v_nome%' $sSep$sWhere");
          }else{
            $sql = $clsau_encaminhamentos->sql_query2("",'',"s142_i_codigo", $sWhere);
          }

        } else {

          if(isset($chave_s142_i_codigo) && (trim($chave_s142_i_codigo)!="") ){
	          $sql = $clsau_encaminhamentos->sql_query_encaminhamentos_profissional($chave_s142_i_codigo,'',$chave_profissional,$chave_unidade,'');
          }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
	          $sql = $clsau_encaminhamentos->sql_query_encaminhamentos_profissional("",'',$chave_profissional,$chave_unidade," z01_v_nome"," z01_v_nome ilike '$chave_z01_v_nome%' ");
          }else{
            $sql = $clsau_encaminhamentos->sql_query_encaminhamentos_profissional("",'',$chave_profissional,$chave_unidade,"s142_i_codigo","");
          }

        }
        $repassa = array();
        if(isset($chave_s142_i_codigo)){
          $repassa = array("chave_s142_i_codigo"=>$chave_s142_i_codigo);
        }
        //echo $sql;

        if(isset($nao_mostra)) {
          
          $sSep = '';
          $aFuncao = explode('|', $funcao_js);

          $rs = $clsau_encaminhamentos->sql_record($sql);
           if($clsau_encaminhamentos->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_s142_i_codigo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {
               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep = ', ';

             }
             $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die('<script>'.$sFuncao.'</script>');

          }
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,true);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          if(!isset($chave_profissional) || empty($chave_profissional) || !isset($chave_unidade) || empty($chave_unidade)) {
            $result = $clsau_encaminhamentos->sql_record($clsau_encaminhamentos->sql_query2($pesquisa_chave));
          } else {

	          $sql = $clsau_encaminhamentos->sql_query_encaminhamentos_profissional($pesquisa_chave,'',
                                                                                  $chave_profissional,$chave_unidade);
            $result = $clsau_encaminhamentos->sql_record($sql);

          }

          if($clsau_encaminhamentos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$s142_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_s142_i_codigo",true,1,"chave_s142_i_codigo",true);
</script>