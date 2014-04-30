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
include("classes/db_lote_classe.php");
db_postmemory($HTTP_POST_VARS);

if(!isset($pesquisar)){
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
}
$cllote = new cl_lote;
$cllote->rotulo->label();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="30%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td align="right" nowrap title="<?=$Tj34_idbql?>">
              <?=$Lj34_idbql?>
            </td>
            <td colspan="5" align="left" nowrap>
              <?
                db_input("j34_idbql",10,$Ij34_idbql,true,"text",4,"","chave_j34_idbql");
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=$Tj34_setor?>">
              <?=$Lj34_setor?>
            </td>
            <td align="left" nowrap>
              <?
                db_input("j34_setor",10,$Ij34_setor,true,"text",4,"","chave_j34_setor");
              ?>
            </td>
            <td align="left" nowrap>
              <?=$Lj34_quadra?>
            </td>
            <td align="left" nowrap>
              <?
                db_input("j34_quadra",10,$Ij34_quadra,true,"text",4,"","chave_j34_quadra");
              ?>
            </td>
            <td align="left" nowrap>
              <?=$Lj34_lote?>
            </td>
            <td align="left" nowrap>
              <?
                db_input("j34_lote",10,$Ij34_lote,true,"text",4,"","chave_j34_lote");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"> <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" > </td>
          </tr>
        </form>
      </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $sNumero  = " case                                                                        ";
      $sNumero .= "   when length(trim(cast(j15_numero as varchar))) > 0 and j39_matric is null ";
      $sNumero .= "     then j15_numero                                                         ";
      $sNumero .= "   else j39_numero                                                           ";
      $sNumero .= " end as j39_numero ";

      $sql="";
      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {
          $campos = "lote.*";
        }
        if( !isset($pesquisar) ) {

          $chave = "";
          if(isset($setor) && !empty($setor)) {
            $setor = str_pad($setor,4,"0",STR_PAD_LEFT);
          }
          $chave .= "lote.j34_setor = '$setor' ";
          if(isset($quadra) && !empty($quadra)){

            if(!empty($chave)) {
              $chave .= " and ";
            }
            $quadra = str_pad($quadra,4,"0",STR_PAD_LEFT);
            $chave .= " lote.j34_quadra = '$quadra' ";
          }
          if(isset($lote) && !empty($lote)) {

            if (!empty($chave)) {
              $chave .= " and ";
            }
            $lote   = str_pad($lote,4,"0",STR_PAD_LEFT);
            $chave .= " lote.j34_lote = '$lote' ";
          }
          if ($chave!="") {

             $sCampos  = "distinct on (iptubase.j01_matric) iptubase.j01_matric, iptuant.j40_refant, ";
             $sCampos .= "(select rvnome as z01_nome ";
             $sCampos .= "   from fc_busca_envolvidos(false, (select fc_regrasconfig ";
             $sCampos .= "                                      from fc_regrasconfig(1)), 'M', iptubase.j01_matric) ";
             $sCampos .= "                                     limit 1)#";
             $sCampos .= "j34_idbql#lote.j34_setor#lote.j34_quadra#lote.j34_lote#loteloc.j06_setorloc#";
             $sCampos .= "loteloc.j06_quadraloc#loteloc.j06_lote#lote.j34_area#lote.j34_bairro#lote.j34_areal#";
             $sCampos .= "lote.j34_totcon#lote.j34_zona#lote.j34_quamat#lote.j34_areapreservada#j14_codigo,j14_tipo,";
             $sCampos .= "j14_nome,$sNumero,j39_compl";

             $sql = $cllote->sql_query_dados_lote("",$sCampos,"iptubase.j01_matric, lote.j34_setor#lote.j34_quadra#lote.j34_lote",$chave);
          }
         } else {

           if (isset($chave_j34_idbql) && (trim($chave_j34_idbql)!="") ) {

             $sCampos  = "distinct on (iptubase.j01_matric) iptubase.j01_matric, iptuant.j40_refant,";
             $sCampos .= "(select rvnome as z01_nome";
             $sCampos .= "   from fc_busca_envolvidos(false, (select fc_regrasconfig ";
             $sCampos .= "                                      from fc_regrasconfig(1)), 'M', iptubase.j01_matric)";
             $sCampos .= "                                     limit 1)#";
             $sCampos .= "lote.*,j14_codigo,j14_tipo,j14_nome,$sNumero,j39_compl";

             $sql = $cllote->sql_query_dados_lote($chave_j34_idbql,$sCampos,"iptubase.j01_matric, lote.j34_idbql");
           } else {

             $wx      ="";
             $wlote   ="";
             $wquadra ="";
             $wsetor  ="";
             if (isset($chave_j34_setor) && ($chave_j34_setor!="")) {

               $chave_j34_setor = str_pad($chave_j34_setor,4,"0",STR_PAD_LEFT);
               $wsetor          = "lote.j34_setor='$chave_j34_setor'";
               $wx              = " and ";
            }
            if(isset($chave_j34_quadra) && ($chave_j34_quadra!="") ) {

              $chave_j34_quadra = str_pad($chave_j34_quadra,4,"0",STR_PAD_LEFT);
              $wquadra          = $wx."lote.j34_quadra='$chave_j34_quadra'";
              $wx               = " and ";
            }
            if (isset($chave_j34_lote) && ($chave_j34_lote!="") ) {

              $chave_j34_lote = str_pad($chave_j34_lote,4,"0",STR_PAD_LEFT);
              $wlote          = $wx."lote.j34_lote='$chave_j34_lote'";
              $wx             = " and ";
            }

            $sCampos  = "distinct on (iptubase.j01_matric), iptubase.j01_matric, iptuant.j40_refant,";
            $sCampos .= "(select rvnome as z01_nome";
            $sCampos .= "   from fc_busca_envolvidos(false, (select fc_regrasconfig ";
            $sCampos .= "                                      from fc_regrasconfig(1)), 'M', iptubase.j01_matric) ";
            $sCampos .= "                                     limit 1)#";
            $sCampos .= "lote.*,j14_codigo,j14_tipo,j14_nome,{$sNumero},j39_compl";

            if($wx!="") {
            	$sOrdem = "iptubase.j01_matric";
            	$sSetorQuadraLote = $wsetor.$wquadra.$wlote;
            	if (!empty($sSetorQuadraLote)) {
            		$sOrdem .= ", {$sSetorQuadraLote}";
            	}
            	echo $sql;
              $sql = $cllote->sql_query_dados_lote("", $sCampos,"lote.j34_setor",$sOrdem);
            } else  if($wx == "" && isset($pesquisar) || isset($filtroquery)) {
              $sql = $cllote->sql_query_dados_lote("", $sCampos,"lote.j34_idbql","");
            }
          }
        }
        
        if ($sql!="") {
        	db_lovrot($sql,100,"()","",$funcao_js);
        }
      } else {

        $result = $cllote->sql_record($cllote->sql_query_file($pesquisa_chave));
        if ($cllote->numrows!=0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$j34_setor',false);</script>";
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
document.form2.chave_j34_idbql.focus();
document.form2.chave_j34_idbql.select();
  </script>
  <?
}
?>