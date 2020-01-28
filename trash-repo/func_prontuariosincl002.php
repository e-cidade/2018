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
include("classes/db_prontuarios_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("sd04_i_cbo");
$db_opcao=1;
$unidade=db_getsession("DB_coddepto");
$usuario=db_getsession("DB_id_usuario");
$todos="";

//echo "<BR> $pesquisa_chave ";exit;

$sql1 = "select z01_nome as profissional,db_usuacgm.id_usuario as sd24_i_codigo,z01_numcgm,sd03_i_codigo
                  from cgm 
                  inner join db_usuacgm on cgmlogin= z01_numcgm
                  inner join db_usuarios on db_usuarios.id_usuario= db_usuacgm.id_usuario
                  inner join medicos on medicos.sd03_i_cgm= cgm.z01_numcgm	
                  inner join unidademedicos on unidademedicos.sd04_i_medico= medicos.sd03_i_codigo
                  inner join unidades on unidades.sd02_i_codigo= unidademedicos.sd04_i_unidade		               
                  where sd02_i_codigo = $unidade and db_usuacgm.id_usuario= $usuario                                
                  ";
 $query1 = pg_query($sql1) or die(pg_errormessage());
 $linhas1 = pg_num_rows($query1);
if($linhas1>0){
db_fieldsmemory($query1,0);
} 
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
            <td width="4%" align="right" nowrap title="<?=$Tsd24_i_codigo?>">
              <?=$Lsd24_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?db_input("sd24_i_codigo",11,@$Isd24_i_codigo,true,"text",4,"","chave_sd24_i_codigo");?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan="2">
              <?db_input("z01_v_nome",40,@$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");?>
            </td>
          </tr>
          <tr> 
            <td colspan="3" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
    <?
    if($pesquisa_chave){
        $pesquisa_chave='';
        if(isset($campos)==false){
             include("funcoes/db_func_prontuarios.php");
        }
        $repassa = array();
        
        $sql = "select distinct sd24_i_codigo,sd24_i_numcgs, z01_v_nome, z01_d_nasc
                  from prontuarios 
                  inner join cgs_und on cgs_und.z01_i_cgsund= prontuarios.sd24_i_numcgs
                 where prontuarios.sd24_c_digitada = 'N'
                   ";
//        $repassa = array("todos2"=>$todos2);
          //db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
           if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
                 $sql = $clprontuarios->sql_query("",$campos,"cgs_und.z01_v_nome, sd24_i_codigo","cgs_und.z01_v_nome like '$chave_z01_v_nome%' ");
                 $repassa = array("chave_z01_v_nome"=>$chave_z01_v_nome);                 
           }else if(isset($chave_sd24_i_codigo) && (trim($chave_sd24_i_codigo)!="") ){
                 $sql = $clprontuarios->sql_query(null,$campos,"sd24_i_codigo","sd24_c_digitada = 'N' and sd24_i_codigo = $chave_sd24_i_codigo");
           }
        if( isset( $sql ) ){
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clprontuarios->sql_record($clprontuarios->sql_query($pesquisa_chave));
          if($clprontuarios->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd24_i_codigo',false);</script>";
          }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") nao Encontrado',true);</script>";
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

<script>
function js_limpar(){
  document.form2.chave_sd04_i_codigo.value="";
  document.form2.chave_z01_nome.value="";
}

js_tabulacaoforms("form2","chave_sd24_i_codigo",true,1,"chave_sd24_i_codigo",true);


</script>