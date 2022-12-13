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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_veiculos_classe.php"));
include(modification("classes/db_veiccadmodelo_classe.php"));
include(modification("classes/db_veiccadmarca_classe.php"));
include(modification("classes/db_veiccadtipo_classe.php"));

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculos      = new cl_veiculos;
$clveiccadmodelo = new cl_veiccadmodelo;
$clveiccadmarca  = new cl_veiccadmarca;
$clveiccadtipo   = new cl_veiccadtipo;

$clveiculos->rotulo->label("ve01_codigo");
$clveiculos->rotulo->label("ve01_placa");
$clveiculos->rotulo->label("ve01_veiccadmodelo");
$clveiculos->rotulo->label("ve01_veiccadmarca");
$clveiculos->rotulo->label("ve01_veiccadtipo");
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
        <table width="50%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tve01_codigo?>">
              <?=$Lve01_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
            <?
    		       db_input("ve01_codigo",10,$Ive01_codigo,true,"text",4,"","chave_ve01_codigo");
		        ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tve01_placa?>">
              <?=$Lve01_placa?>
            </td>
            <td width="96%" align="left" nowrap>
            <?
		           db_input("ve01_placa",7,$Ive01_placa,true,"text",4,"","chave_ve01_placa");
  	        ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tve01_veiccadmodelo?>">
              <?=$Lve01_veiccadmodelo?>
            </td>
            <td width="96%" align="left" nowrap>
            <?
               $result = $clveiccadmodelo->sql_record($clveiccadmodelo->sql_query_file());
            ?>
              <select name="chave_ve01_veiccadmodelo" id="chave_ve01_veiccadmodelo">
                <option value="-1">Nenhum</option>
            <?
                for($i = 0; $i < $clveiccadmodelo->numrows; $i++){
                  db_fieldsmemory($result,$i);
            ?>
                <option value="<?=$ve22_codigo?>"><? echo $ve22_codigo." - ".$ve22_descr; ?></option>
            <?
                }
            ?>
              </select>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tve01_veiccadmarca?>">
              <?=$Lve01_veiccadmarca?>
            </td>
            <td width="96%" align="left" nowrap>
            <?
               $result = $clveiccadmarca->sql_record($clveiccadmarca->sql_query_file());
            ?>
              <select name="chave_ve01_veiccadmarca" id="chave_ve01_veiccadmarca">
                <option value="-1">Nenhum</option>
            <?
                for($i = 0; $i < $clveiccadmarca->numrows; $i++){
                  db_fieldsmemory($result,$i);
            ?>
                <option value="<?=$ve21_codigo?>"><? echo $ve21_codigo." - ".$ve21_descr; ?></option>
            <?
                }
            ?>
              </select>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tve01_veiccadtipo?>">
              <?=$Lve01_veiccadtipo?>
            </td>
            <td width="96%" align="left" nowrap>
            <?
               $result = $clveiccadtipo->sql_record($clveiccadtipo->sql_query_file());
            ?>
              <select name="chave_ve01_veiccadtipo" id="chave_ve01_veiccadtipo">
                <option value="-1">Nenhum</option>
            <?
                for($i = 0; $i < $clveiccadtipo->numrows; $i++){
                  db_fieldsmemory($result,$i);
            ?>
                <option value="<?=$ve20_codigo?>"><? echo $ve20_codigo." - ".$ve20_descr; ?></option>
            <?
                }
            ?>
              </select>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veiculos.hide();">
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
           if(file_exists("funcoes/db_func_veiculos.php")==true){
             include(modification("funcoes/db_func_veiculos.php"));
           }else{
           $campos = "veiculos.*";
           }
        }

        $sWhereInstituicao = null;

        if (isset($instit)) {
          $sWhereInstituicao = 'and instit = ' . db_getsession("DB_instit");

          if (isset($lVeiculosSemCentral)) {
            $sWhereInstituicao .= " or instit is null";
          }
        }

        $campos .= ",veiculos.ve01_quantcapacidad";

      //$dbwhere = " (ve36_coddepto = ".db_getsession("DB_coddepto")." or  ve37_coddepto = ".db_getsession("DB_coddepto").") ";

        if(isset($chave_ve01_codigo) && trim($chave_ve01_codigo)!=""){
	         $sql = $clveiculos->sql_query(null,$campos,"ve01_codigo","ve01_codigo = $chave_ve01_codigo $sWhereInstituicao ");

        }else if (isset($chave_ve01_placa) && trim($chave_ve01_placa)!=""){
           $sql = $clveiculos->sql_query("",$campos,"ve01_placa","ve01_placa = '$chave_ve01_placa' $sWhereInstituicao ");
        }else if (isset($chave_ve01_veiccadmodelo)  && trim($chave_ve01_veiccadmodelo)!="" && $chave_ve01_veiccadmodelo > 0){
           $sql = $clveiculos->sql_query("",$campos,"veiccadmodelo.ve22_descr","ve01_veiccadmodelo = $chave_ve01_veiccadmodelo $sWhereInstituicao ");
        }else if (isset($chave_ve01_veiccadmarca) && trim($chave_ve01_veiccadmarca)!=""  && $chave_ve01_veiccadmarca > 0){
           $sql = $clveiculos->sql_query("",$campos,"veiccadmarca.ve21_descr","ve01_veiccadmarca = $chave_ve01_veiccadmarca $sWhereInstituicao ");
        }else if (isset($chave_ve01_veiccadtipo) && trim($chave_ve01_veiccadtipo)!="" && $chave_ve01_veiccadtipo > 0){
           $sql = $clveiculos->sql_query("",$campos,"veiccadtipo.ve20_descr","ve01_veiccadtipo = $chave_ve01_veiccadtipo $sWhereInstituicao ");
        }else {
           $sql = $clveiculos->sql_query("",$campos,"ve01_codigo","1=1 $sWhereInstituicao ");
        }
        $repassa = array();
        if(isset($chave_ve01_codigo)        || isset($chave_ve01_placa)        ||
           isset($chave_ve01_veiccadmodelo) || isset($chave_ve01_veiccadmarca) ||
           isset($chave_ve01_veiccadtipo)){
          $repassa = array("chave_ve01_codigo"=>$chave_ve01_codigo,
                           "chave_ve01_placa"=>$chave_ve01_placa,
                           "chave_ve01_veiccadmodelo"=>$chave_ve01_veiccadmodelo,
                           "chave_ve01_veiccadmarca"=>$chave_ve01_veiccadmarca,
                           "chave_ve01_veiccadtipo"=>$chave_ve01_veiccadtipo);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clveiculos->sql_record($clveiculos->sql_query($pesquisa_chave));
          if (isset($sigla) && $sigla == true){
            if($clveiculos->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$ve07_sigla',false);</script>";
            }
          } else {
            if($clveiculos->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."(false,$ve01_codigo,'$ve01_placa','$ve22_descr', '$ve01_quantcapacidad');</script>";

            }else{
	            echo "<script>".$funcao_js."(true,'Chave(".$pesquisa_chave.") não Encontrado');</script>";
            }
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
js_tabulacaoforms("form2","chave_ve01_codigo",true,1,"chave_ve01_placa",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
