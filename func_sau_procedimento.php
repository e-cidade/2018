<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_sau_procedimento_ext_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_procedimento = new cl_sau_procedimento_ext;
$clsau_procedimento->rotulo->label("sd63_i_codigo");
$clsau_procedimento->rotulo->label("sd63_c_nome");
$clsau_procedimento->rotulo->label("sd63_c_procedimento");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd63_i_codigo?>">
              <?=$Lsd63_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd63_i_codigo",5,$Isd63_i_codigo,true,"text",4,"","chave_sd63_i_codigo");
                 ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd63_c_procedimento?>">
              <?=$Lsd63_c_procedimento?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd63_c_procedimento",10,$Isd63_c_procedimento,true,"text",4,"","chave_sd63_c_procedimento");
                 ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd63_c_nome?>">
              <?=$Lsd63_c_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd63_c_nome",60,$Isd63_c_nome,true,"text",4,"","chave_sd63_c_nome");
                 ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_procedimento.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $where='';
      if( isset($chave_sd04_i_cbo)){

        $where  = " and sd96_i_codigo = $chave_sd04_i_cbo";
        $where .= " or (select count(*) from proccbo where sd96_i_procedimento = sd63_i_codigo) = 0";

      }
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_sau_procedimento.php")==true){
             include("funcoes/db_func_sau_procedimento.php");
           }else{
           $campos = "sau_procedimento.*";
           }
        }
        
        if (isset($chave_sd62_c_formaorganizacao) && $chave_sd62_c_formaorganizacao != '') {
          
          $where  = " sd63_c_procedimento like '";
          $where .= $chave_sd60_c_grupo.$chave_sd61_c_subgrupo.$chave_sd62_c_formaorganizacao;
          $where .= "%' ";
          
          
        } elseif (isset($chave_sd61_c_subgrupo) && $chave_sd61_c_subgrupo != '') {
          
          $where  = " sd63_c_procedimento like '";
          $where .= $chave_sd60_c_grupo.$chave_sd61_c_subgrupo;
          $where .= "%' ";
          
        } elseif (isset($chave_sd60_c_grupo) && $chave_sd60_c_grupo != '') {
          
          $where  = " sd63_c_procedimento like '";
          $where .= $chave_sd60_c_grupo;
          $where .= "%' ";
          
        }
        
        if(isset($chave_sd63_i_codigo) && (trim($chave_sd63_i_codigo)!="") ){
              $sql = $clsau_procedimento->sql_query_ext($chave_sd63_i_codigo,$campos,"sd63_i_anocomp desc,sd63_i_mescomp desc,sd63_c_nome ");
        }else if(isset($chave_sd63_c_procedimento) && (trim($chave_sd63_c_procedimento)!="") ){
              $sql = $clsau_procedimento->sql_query_ext("",$campos,"sd63_i_anocomp desc,sd63_i_mescomp desc,sd63_c_nome "," sd63_c_procedimento = '$chave_sd63_c_procedimento' $where ");
        }else if(isset($chave_sd63_c_nome) && (trim($chave_sd63_c_nome)!="") ){
              $sql = $clsau_procedimento->sql_query_ext("",$campos,"sd63_i_anocomp desc,sd63_i_mescomp desc,sd63_c_nome "," sd63_c_nome like '$chave_sd63_c_nome%' $where ");
        } elseif (isset($chave_sd60_c_grupo) && $chave_sd60_c_grupo != '') {
              $sql = $clsau_procedimento->sql_query_ext("", $campos, "sd63_i_codigo", $where);
        }else{
           $sql = $clsau_procedimento->sql_query_ext("",$campos,"sd63_i_codigo",substr( $where, 4 ));
        }
        
        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      =  $clsau_procedimento->sql_record($sql);
          if ($clsau_procedimento->numrows == 0) {
	          die('<script>'.$aFuncao[0]."(true,'Chave(".$chave_sd63_c_procedimento.") não Encontrado');</script>");
          } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';

             }
             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');
          }

        }
        
        
        $repassa = array();
        if(isset($chave_sd63_c_nome)){
          $repassa = array("chave_sd63_i_codigo"=>$chave_sd63_i_codigo,"chave_sd63_c_nome"=>$chave_sd63_c_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          //$result = $clsau_procedimento->sql_record($clsau_procedimento->sql_query_ext($pesquisa_chave));
          $result = $clsau_procedimento->sql_record($clsau_procedimento->sql_query_ext("","*","sd63_i_anocomp desc,sd63_i_mescomp desc,sd63_c_nome "," sd63_c_procedimento = '$pesquisa_chave' $where "));
          if($clsau_procedimento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd63_c_nome',false,$sd63_i_codigo);</script>";
          }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,'');</script>";
          }
        }else{
            echo "<script>".$funcao_js."('',false,'');</script>";
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
function js_limpar(){
document.form2.chave_sd63_i_codigo.value="";
document.form2.chave_sd63_c_procedimento.value="";	
document.form2.chave_sd63_c_nome.value="";
}

js_tabulacaoforms("form2","chave_sd63_c_nome",true,1,"chave_sd63_c_nome",true);
</script>