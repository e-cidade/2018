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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_vac_vacinamaterial_classe.php");
require_once("libs/db_stdlibwebseller.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoVacVaccinaMaterial = new cl_vac_vacinamaterial;
$oRotulo            = new rotulocampo;
$iDepartamento      = db_getsession("DB_coddepto");
$oRotulo->label('m77_lote');
$oRotulo->label('m77_sequencial');

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
            <td width="4%" align="right" nowrap title="<?=$Tm77_sequencial?>">
              <?=$Lm77_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("m77_sequencial",10,$Im77_sequencial,true,"text",4,"","chave_m77_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tm77_lote?>">
              <?=$Lm77_lote?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	            db_input("m77_lote",10,$Im77_lote,true,"text",4,"","chave_m77_lote");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_vac_vacinalote.hide();">
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

        if (isset($sCampos) == false) {

           if (file_exists("funcoes/db_func_vac_vacinalote.php") == true) {
             require_once("funcoes/db_func_vac_vacinalote.php");
           } else {
             $sCampos = "matestoqueitemlote.*";
           }

        }
       
        $sVacina    = '';
        $sSepVacina = '';
        if(isset($chave_vacina)) {

          $sVacina    = ' vc06_i_codigo = '.$chave_vacina;
          $sSepVacina = ' and ';

        }
        if (isset($chave_m77_sequencial) && (trim($chave_m77_sequencial) != "")) {

	        $sSql = $oDaoVacVaccinaMaterial->sql_query_vacina(null, $sCampos, 'm77_sequencial', 
                                                           " m77_sequencial =  $chave_m77_sequencial".
                                                           " and m70_coddepto = $iDepartamento".
                                                           " $sSepVacina $sVacina "
                                                          );

        }else if (isset($chave_m77_lote) && (trim($chave_m77_lote) != "")) {

	         $sSql = $oDaoVacVaccinaMaterial->sql_query_vacina(null, $sCampos, 'm77_lote', 
                                                            " m77_lote like '$chave_m77_lote%' and ".
                                                            " m70_coddepto=$iDepartamento ".
                                                            " $sSepVacina $sVacina "
                                                           );

        } else {

           $sSql = $oDaoVacVaccinaMaterial->sql_query_vacina(null, $sCampos, 'm77_sequencial', 
                                                            " m70_coddepto = $iDepartamento ".
                                                            "$sSepVacina $sVacina "
                                                           );

        }
        if (isset($nao_mostra)) {
              
          $sSep = '';
          $aFuncao = explode('|', $funcao_js);
          $rs = $oDaoVacVaccinaMaterial->sql_record($sSql);
          if ($oDaoVacVaccinaMaterial->numrows == 0) {
            die('<script>'.$aFuncao[0]."('','Chave(".@$chave_m77_lote.") não Encontrado');</script>");
          } else {
                
            db_fieldsmemory($rs, 0);
            $sFuncao = $aFuncao[0].'(';
            for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

              $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
              $sSep = ', ';

            }
            $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
            $sFuncao .= ');';
            die("<script>".$sFuncao.'</script>');
   
          }
   
        }

        $repassa = array();
        if (isset($chave_m77_sequencial)) {
          $repassa = array("chave_m77_sequencial"=>$chave_m77_sequencial,"chave_m77_sequencial"=>$chave_m77_sequencial);
        }

        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave != "") {
        	
        	$sWhere = " m77_sequencial=$pesquisa_chave and m70_coddepto=$iDepartamento ";
          $result = $oDaoVacVaccinaMaterial->sql_record($oDaoVacVaccinaMaterial->sql_query_matestoque("",
                                                                                              "*",
                                                                                              "",
                                                                                              $sWhere
                                                                                             )
                                                   );
          if ($oDaoVacVaccinaMaterial->numrows != 0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m77_dtvalidade',false,$m77_sequencial,$m61_descr);</script>";
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
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_m77_sequencial",true,1,"chave_m77_sequencial",true);
</script>