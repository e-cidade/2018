<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lab_requisicao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllab_requisicao = new cl_lab_requisicao;
$cllab_requisicao->rotulo->label("la22_i_codigo");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_v_nome");
$clrotulo->label("la22_d_data");
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
        <table width="35%" border="0" align="center" cellspacing="0" border="1" >
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla22_i_codigo?>">
              <?=$Lla22_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la22_i_codigo",10,$Ila22_i_codigo,true,"text",4,"","la22_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("z01_v_nome",40,$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
		          ?>
            </td>
          </tr>
          
          <tr>
            <td width="4%" align="right" nowrap >
                <b>Data inicial:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_inputdata('la22_d_data_ini',@$la22_d_data_ini_dia,@$la22_d_data_ini_mes,@$la22_d_data_ini_ano,true,'text',1,"")
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap >
                <b>Data fim:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_inputdata('la22_d_data_fim',@$la22_d_data_fim_dia,@$la22_d_data_fim_mes,@$la22_d_data_fim_ano,true,'text',1,"")
              ?>
            </td>
          </tr>
          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_requisicao.hide();">
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
           //if(file_exists("funcoes/db_func_lab_requisicao.php")==true){
           //  include("funcoes/db_func_lab_requisicao.php");
           //}else{
           $campos = "la22_i_codigo,z01_v_nome,la22_d_data,la22_c_hora";
           //}
        }
        $where="";
        $sep="";
        $lCarrega = true;
        if(isset($la22_d_data_ini)&&($la22_d_data_ini!="")){
            $aDat=explode("/",$la22_d_data_ini);
            $where=" la22_d_data >= '".$aDat[2]."-".$aDat[1]."-".$aDat[0]."' ";
            $sep=" and ";
        }
        if(isset($autoriza)){
          $where .= "$sep la22_i_autoriza=$autoriza ";
          $sep = " and ";
        }
        if(isset($la22_d_data_fim)&&($la22_d_data_fim!="")){
            if($where!=""){
               $aDat=explode("/",$la22_d_data_fim);
               $where .= " and la22_d_data <= '".$aDat[2]."-".$aDat[1]."-".$aDat[0]."' ";
            }else{
               $aDat=explode("/",$la22_d_data_fim);
               $where  = " la22_d_data <= '".$aDat[2]."-".$aDat[1]."-".$aDat[0]."' ";
            }
            $sep=" and ";
        }
        if(isset($iLaboratorioLogado)){
           $where .= " $sep EXISTS( select 1 from lab_requiitem ";
           $where .= "  inner join lab_setorexame on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
           $where .= "  inner join lab_labsetor   on lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor";
           $where .= " where lab_requiitem.la21_i_requisicao=lab_requisicao.la22_i_codigo and la24_i_laboratorio=$iLaboratorioLogado ) ";
           $sep=" and ";  
        }
        if(isset($iDepResitante)){
           $where .= " $sep la22_i_departamento=$iDepResitante ";
           $sep    = " and ";  
           $lCarrega = false;
        }
        if(isset($la22_i_codigo) && (trim($la22_i_codigo)!="") ){
           $sql = $cllab_requisicao->sql_query($la22_i_codigo,$campos,"la22_i_codigo","la22_i_codigo=$la22_i_codigo $sep$where");
        }else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
           $sql = $cllab_requisicao->sql_query("",$campos,"z01_v_nome"," z01_v_nome like '$chave_z01_v_nome%' $sep$where ");
        }else{
        	if ($lCarrega == true) {
            $sql = $cllab_requisicao->sql_query("",$campos,"la22_i_codigo","$where");
        	}
        }
        $repassa = array();
        if(isset($la22_i_codigo)){
          $repassa = array("la22_i_codigo"=>$la22_i_codigo,"chave_z01_v_nome"=>$chave_z01_v_nome);
        }
        
        if( isset( $sql ) ){
            db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllab_requisicao->sql_record($cllab_requisicao->sql_query($pesquisa_chave));
          if($cllab_requisicao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_v_nome',false);</script>";
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
js_tabulacaoforms("form2","la22_i_codigo",true,1,"la22_i_codigo",true);
</script>