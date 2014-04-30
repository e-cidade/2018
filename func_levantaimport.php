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
include("classes/db_levanta_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllevanta = new cl_levanta;
$clrotulo = new rotulocampo();
$cllevanta->rotulo->label("y60_codlev");
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxttipo_origem");
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
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ty60_codlev?>">
              <?=$Ly60_codlev?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("y60_codlev",10,$Iy60_codlev,true,"text",4,"","chave_y60_codlev");
		       ?>
            </td>
          </tr>
	 <tr>   
	   <td title="<?=$Tq02_inscr?>">
	  <?
	   db_ancora($Lq02_inscr,' js_inscr(true); ',1);
	  ?>
	   </td>
	   <td nowrap> 
	  <?
	   db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
	  db_input('z01_nome',30,0,true,'text',3,"","z01_nomeinscr");
	  ?>
	   </td>
	 </tr>
	 <tr>   
	  <td title="<?=$Tz01_numcgm?>" nowrap>
	  <?
	   db_ancora($Lz01_nome,' js_cgm(true); ',1);
	  ?>
	   </td>
	   <td nowrap> 
	  <?
	   db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
	   db_input('z01_nome',30,0,true,'text',3,"","z01_nomecgm");
	  ?>
	   </td>
	 </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $dbwhere = " y60_importado is true ";

      
      if(!isset($pesquisa_chave)){
	$campos ="y60_codlev,q02_inscr,z01_nome,y60_importado" ;
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_levanta.php")==true){
             include("funcoes/db_func_levanta.php");
           }else{
           $campos = "levanta.*";
           }
        }
        if(isset($chave_y60_codlev) && (trim($chave_y60_codlev)!="") ){
	         $sql = $cllevanta->sql_query_inscr($chave_y60_codlev,$campos,"y60_codlev","$dbwhere");
        }else if(isset($chave_y60_data) && (trim($chave_y60_data)!="") ){
	         $sql = $cllevanta->sql_query_inscr("",$campos,"y60_data"," y60_data like '$chave_y60_data%'  ".($dbwhere==""?"":" and $dbwhere "));
        }else{
           $sql = $cllevanta->sql_query_inscr(null,"$campos","",$dbwhere);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
	
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllevanta->sql_record($cllevanta->sql_query_inscr($pesquisa_chave,"*","","y60_codlev=$pesquisa_chave and $dbwhere "));
          if($cllevanta->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
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
  <script>
function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true,0,6,750,360);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nomeinscr.value = "";
    } 
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_numcgm','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true,0,11,750,360);
  }else{
    if(cgm!=""){
      js_OpenJanelaIframe('','db_iframe_numcgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
    }else{
      document.form1.z01_nomecgm.value = '';
    }  
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe_numcgm.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
  </script>