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
include("classes/db_pcorcam_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcorcam = new cl_pcorcam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc20_codorc");
$clrotulo->label("pc10_numero");
$clrotulo->label("pc80_codproc");
$clrotulo->label("l20_codigo");

if (!isset($pesquisar)) {
	
  $iDia = date("d", db_getsession("DB_datausu"));
  $iMes = date("m", db_getsession("DB_datausu"));
  $iAno = date("Y", db_getsession("DB_datausu"));
  
  $pc20_dtatef_dia = $iDia;
  $pc20_dtatef_mes = $iMes;
  $pc20_dtatef_ano = $iAno;
  
  $pc20_dtatei_dia = $iDia;
  $pc20_dtatei_mes = $iMes;
  $pc20_dtatei_ano = $iAno;  

  $sql= "select cast('{$pc20_dtatei_ano}-{$pc20_dtatei_mes}-{$pc20_dtatei_dia}'::varchar as date)+(select cast(pc30_dias:: bigint as integer) from pcparam where pc30_instit = ".db_getsession('DB_instit').")  as datafinal";
  $result = pg_query($sql);
  $linhas=pg_num_rows($result);
  if($linhas>0){
    db_fieldsmemory($result,0);
  }
}

  $pc20_dtatei = "{$pc20_dtatei_ano}-{$pc20_dtatei_mes}-{$pc20_dtatei_dia}";
  $pc20_dtatef = "{$pc20_dtatef_ano}-{$pc20_dtatef_mes}-{$pc20_dtatef_dia}";

if (isset($datafinal)){
  $pc20_dtatef=$datafinal;
  $datafinal=mktime(0,0,0,substr($pc20_dtatef,5,2),substr($pc20_dtatef,8,2),substr($pc20_dtatef,0,4));
  $iDia = date("d", $datafinal);
  $iMes = date("m", $datafinal);
  $iAno = date("Y", $datafinal);
  $pc20_dtatef_dia = $iDia;
  $pc20_dtatef_mes = $iMes;
  $pc20_dtatef_ano = $iAno;

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
        <table  border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td  align="left"  title="<?=$Tpc20_codorc?>">
              <?=$Lpc20_codorc?>
            </td>
            <td align="left" > 
              <?
		       db_input("pc20_codorc",10,$Ipc20_codorc,true,"text",4,"","chave_pc20_codorc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td align="left"  title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td align="left" > 
              <?
		       db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		       ?>
            </td>
          </tr>
          <tr> 
            <td>
	          <table>
	             <tr>
	               <td>
	              <b>Data Entrega Inicial</b>
	            </td>
	            <td> 
	              <?
					 db_inputdata("pc20_dtate",@$pc20_dtatei_dia,@$pc20_dtatei_mes,@$pc20_dtatei_ano,true,"text",1,"","pc20_dtatei");
	              ?>
	            </td>
	          </tr>
	          <tr>             
	            <td>
	              <b>Data Entrega Final</b>
	            </td>
	            <td> 
	              <?
					 db_inputdata("pc20_dtate",@$pc20_dtatef_dia,@$pc20_dtatef_mes,@$pc20_dtatef_ano,true,"text",1,"","pc20_dtatef");
	              ?>
	            </td>            
	          </tr>
             </table>
            </td>
          </tr>      
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcorcam.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($campos)==false){
        if(file_exists("funcoes/db_func_pcorcam.php")==true){
          include("funcoes/db_func_pcorcam.php");
        }else{
          $campos = "pcorcam.*";
        }
      }
      
      $where_sol = "";
      
      if(isset($sol) && $sol=='true'){
      	
        $where_sol  = " and pc29_orcamitem is not null "; 
        $where_sol .= " and c.pc10_depto  =".db_getsession('DB_coddepto');
        $where_sol .= " and c.pc10_instit =".db_getsession('DB_instit');
        
        $campos     = "distinct c.pc10_numero,".$campos.",c.pc10_resumo";
            
      }

      if(!isset($pesquisa_chave)){
      	
        if(isset($chave_pc20_codorc) && (trim($chave_pc20_codorc)!="") ){
          $sql = $clpcorcam->sql_query_solproc(null,$campos,"pc20_codorc desc","pc20_codorc=$chave_pc20_codorc ".$where_sol);
        }else if(isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ){
          $sql = $clpcorcam->sql_query_solproc(null,$campos,"pc20_codorc desc"," c.pc10_numero=$chave_pc10_numero ".$where_sol);
        }else if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
          $sql = $clpcorcam->sql_query_solproc(null,$campos,"l20_codigo"," l20_codigo=$chave_l20_codigo ".$where_sol);
        }else if(isset($exc)){
          $sql = $clpcorcam->sql_query_proc(null,$campos,"pc20_codorc desc "," 1=1 ".$where_sol);	      
        }else{
          $sql = $clpcorcam->sql_query_solproc(null,$campos,"pc20_codorc desc",' 1=1 '.$where_sol);
        }

        db_lovrot($sql,15,"()","",$funcao_js, "", "NoMe", array(), false);
        
      }else{
      	
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpcorcam->sql_record($clpcorcam->sql_query_solproc(null,$campos,"","pc20_codorc=$pesquisa_chave ".$where_sol));
          if($clpcorcam->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc20_codorc',false);</script>";
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