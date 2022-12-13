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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tipoproc_classe.php");
require_once("classes/db_tipoprocdepto_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cltipoproc = new cl_tipoproc;
$cltipoproc->rotulo->label("p51_codigo");
$cltipoproc->rotulo->label("p51_descr");

$oDaoTipoProcDpto = new cl_tipoprocdepto();
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
            <td width="4%" align="right" nowrap title="<?=$Tp51_codigo?>">
              <?=$Lp51_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p51_codigo",8,$Ip51_codigo,true,"text",4,"","chave_p51_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp51_descr?>">
              <?=$Lp51_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p51_descr",60,$Ip51_descr,true,"text",4,"","chave_p51_descr");
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
      $where = " p51_instit=".db_getsession("DB_instit");
      if ((isset($grupo) && $grupo == 1) || (isset($grupo) && $grupo == 2)) {
      	$where .= " and p51_tipoprocgrupo = $grupo";
      }
      if (!isset($pesquisa_chave)) {
        if (isset($campos) == false) {
          if (file_exists("funcoes/db_func_tipoproc.php")==true) {
            include("funcoes/db_func_tipoproc.php");
          } else {
            $campos = "tipoproc.*";
          }
        }
        if(isset($chave_p51_codigo) && (trim($chave_p51_codigo) != "") ){
        	
        	$sWhereBuscaTipos  = " p51_codigo=$chave_p51_codigo                                         ";
        	$sWhereBuscaTipos .= " and (p51_dtlimite is null                                            ";
        	$sWhereBuscaTipos .= "  or p51_dtlimite >= '".date("Y-m-d",db_getsession("DB_datausu"))."') ";
        	$sWhereBuscaTipos .= " and {$where}                                                         ";
	        $sSqlBuscaTipos    = $cltipoproc->sql_query(null,$campos,"p51_codigo", $sWhereBuscaTipos);
        } else if (isset($chave_p51_descr) && (trim($chave_p51_descr) != "")) {
        	
        	$sWhereBuscaTipos  = " p51_descr like '$chave_p51_descr%'                                   ";
        	$sWhereBuscaTipos .= " and (p51_dtlimite is null                                            ";
        	$sWhereBuscaTipos .= "  or p51_dtlimite >= '".date("Y-m-d",db_getsession("DB_datausu"))."') ";
        	$sWhereBuscaTipos .= " and {$where}                                                         ";
	        $sSqlBuscaTipos    = $cltipoproc->sql_query("", $campos, "p51_codigo", $sWhereBuscaTipos);
        } else {
        	 
        	$sWhereBuscaTipos  = " (p51_dtlimite is null                                                ";
        	$sWhereBuscaTipos .= "  or p51_dtlimite >= '".date("Y-m-d",db_getsession("DB_datausu"))."') ";
        	$sWhereBuscaTipos .= " and {$where}                                                         ";
          $sSqlBuscaTipos    = $cltipoproc->sql_query("", $campos, "p51_codigo", $sWhereBuscaTipos);
        }
        
        if (isset($sDepartamentos) && $sDepartamentos != "") {
          
          $sWhereDepart   = "db_depart.coddepto in ({$sDepartamentos})";
          $sCamposDepart  = "distinct {$campos}";
          $sSqlBuscaTipos = $oDaoTipoProcDpto->sql_query_depto(null, $sCamposDepart, "p51_codigo", $sWhereDepart);
        }

        db_lovrot($sSqlBuscaTipos, 15, "()", "", $funcao_js);
      } else {
      	
        if ((isset($grupo) && $grupo == 1) || (isset($grupo) && $grupo == 2)) {
      	  $where .= " and p51_tipoprocgrupo = $grupo";
     	  }
     	  
     	  $sWhere  = "p51_codigo=$pesquisa_chave                                                    ";
     	  $sWhere .= "and (p51_dtlimite is null or p51_dtlimite >= '".date("Y-m-d",db_getsession("DB_datausu"))."') ";
     	  $sWhere .= "and {$where}                                                                  ";
     	  
     	  if (isset($sDepartamentos) && $sDepartamentos != "") {
          
     	    $sWhereDepart   = " and db_depart.coddepto in ({$sDepartamentos})";
          $sCamposDepart  = "distinct {$campos}";
          $sSqlBuscaChave = $oDaoTipoProcDpto->sql_query_depto(null, "*", "p51_codigo", $sWhere. $sWhereDepart);
          
     	  } else {
     	    $sSqlBuscaChave = $cltipoproc->sql_query(null,"tipoproc.*","p51_codigo", $sWhere);
     	  }

     	  $result  = $cltipoproc->sql_record($sSqlBuscaChave);
        if ($cltipoproc->numrows != 0) {
        	
          db_fieldsmemory($result, 0);
          echo "<script>".$funcao_js."('$p51_descr',false);</script>";
        } else {
	        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
document.form2.chave_p51_descr.focus();
document.form2.chave_p51_descr.select();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_p51_descr",true,1,"chave_p51_descr",true);
</script>