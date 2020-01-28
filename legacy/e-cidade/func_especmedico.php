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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_especmedico_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clespecmedico = new cl_especmedico;
$clespecmedico->rotulo->label("sd27_i_codigo");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");


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
            <td width="4%" align="right" nowrap title="<?=$Tsd04_i_medico?>">
              <?=$Lsd04_i_medico?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
   		         db_input("sd04_i_medico",10,$Isd04_i_medico,true,"text",4,"","chave_sd04_i_medico");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",60,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh70_estrutural?>">
              <?=$Lrh70_estrutural?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh70_estrutural",10,$Irh70_estrutural,true,"text",4,"","chave_rh70_estrutural");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd27_i_codigo?>">
              <?=$Lsd27_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd27_i_codigo",10,$Isd27_i_codigo,true,"text",4,"","chave_sd27_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="js_fechar('<?=@$campoFoco ?>');">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $where = " sd27_c_situacao = 'A' ";
      if( isset($chave_sd04_i_unidade)){
         $where .= " and sd04_i_unidade = ".(int)$chave_sd04_i_unidade;
      }
      if( isset($chave_sd04_i_medico)){
         $where .= " and sd04_i_medico = ".(int)$chave_sd04_i_medico;
      }
      if(isset($iFiltroHorario)){
        $where .= " and exists (select * from undmedhorario where sd30_i_undmed = sd27_i_codigo ) ";
      }
      if (isset($chave_pedido_tfd)) {

        $sInner    = " inner join tfd_procpedidotfd on sd96_i_procedimento = tf23_i_procedimento";
        $sSubWhere = " sd96_i_cbo=rh70_sequencial  and tf23_i_pedidotfd=$chave_pedido_tfd";
        $where    .= " and exists (select * from sau_proccbo $sInner where $sSubWhere)";

      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_especmedico.php")==true){
             include("funcoes/db_func_especmedico.php");
           }else{
             $campos = "especmedico.*";
           }
        }

        if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
           $where = ( !empty($where)?' and ':'').$where;
	         $sql = $clespecmedico->sql_query("",$campos,"cgm.z01_nome"," cgm.z01_nome like '$chave_z01_nome%' $where");
        }else if(isset($chave_rh70_estrutural) && (trim($chave_rh70_estrutural)!="") ){
           $where = (!empty($where)?' and ':'').$where;         
	         $sql = $clespecmedico->sql_query("",$campos,"rh70_estrutural"," rh70_estrutural = '$chave_rh70_estrutural' $where ");          
        }else if(isset($chave_sd27_i_codigo) && (trim($chave_sd27_i_codigo)!="") ){
           $where = (!empty($where)?' and ':'').$where;         
	         $sql = $clespecmedico->sql_query("",$campos,"rh70_estrutural"," sd27_i_codigo = $chave_sd27_i_codigo $where ");          
        }else{
           $sql = $clespecmedico->sql_query("",$campos,"sd04_i_codigo","$where");
        }
/*
        if(isset($chave_sd27_i_codigo) && (trim($chave_sd27_i_codigo)!="") ){
	         $sql = $clespecmedico->sql_query($chave_sd27_i_codigo,$campos,"sd27_i_codigo");
        }else if(isset($chave_sd27_i_codigo) && (trim($chave_sd27_i_codigo)!="") ){
	         $sql = $clespecmedico->sql_query("",$campos,"sd27_i_codigo"," sd27_i_codigo like '$chave_sd27_i_codigo%' ");
        }else{
           $sql = $clespecmedico->sql_query("",$campos,"sd27_i_codigo","");
        }
*/

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $clespecmedico->sql_record($sql);
           if($clespecmedico->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd27_i_codigo.") não Encontrado');</script>");
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
        if(isset($chave_sd27_i_codigo)){
          $repassa = array("chave_sd27_i_codigo"=>$chave_sd27_i_codigo,"chave_sd27_i_codigo"=>$chave_sd27_i_codigo);
        }
        if (isset($chave_pedido_tfd)) {

          $clespecmedico->sql_record($sql);
          if ($clespecmedico->numrows) {

            echo "<script>";
            $sMsg = "Nenhuma especialidade do profissional regulador engloba todos os procedimentos do pedido de TFD.";
            echo "alert('$sMsg');";
            echo "</script>";

          }

        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $where = !empty($where)?' and '.$where:'';

          $sWhere = "sd27_i_codigo = {$pesquisa_chave}";
          if ( isset( $lPesquisaCodigoRhCBO) ) {
            $sWhere = "sd27_i_rhcbo = {$pesquisa_chave}";
          }

          $result = $clespecmedico->sql_record($clespecmedico->sql_query(null, "*", null, $sWhere . $where));
          if($clespecmedico->numrows!=0){
            db_fieldsmemory($result,0);

            $iCampo = $sd27_i_codigo;
            if ( isset( $lPesquisaCodigoRhCBO) ) {
              $iCampo = $rh70_descr;
            }

            echo "<script>".$funcao_js."('$iCampo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
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
function js_fechar( campoFoco ){
	if( campoFoco != undefined && campoFoco != '' ){
		eval( "parent.document.getElementById('"+campoFoco+"').focus(); " );
		eval( "parent.document.getElementById('"+campoFoco+"').select(); " );
	}
	parent.db_iframe_especmedico.hide();
} 

function js_limpar(){
document.form2.chave_sd04_i_medico.value="";
document.form2.chave_z01_nome.value="";	
document.form2.chave_rh70_estrutural.value="";
}
js_tabulacaoforms("form2","chave_sd27_i_codigo",true,1,"chave_sd27_i_codigo",true);
</script>