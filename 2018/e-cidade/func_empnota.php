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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empnota_classe.php"));
db_postmemory($_POST);
db_postmemory($_GET);
parse_str($_SERVER["QUERY_STRING"]);
$clempnota = new cl_empnota;
$clempnota->rotulo->label("e69_numero");
$clempnota->rotulo->label("e69_dtnota");
$rotulo = new rotulocampo;
$rotulo->label("z01_nome");
$rotulo->label("e60_codemp");
$rotulo->label("e60_numemp");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
    function js_mascara(evt){
      var evt = (evt) ? evt : (window.event) ? window.event : "";

      if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:.
        return true;
      }else{
        return false;
      }
    }
  </script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Te60_numemp?>"><?=$Le60_codemp?> </td>
            <td width="96%" align="left" nowrap>

              <input name="chave_e60_codemp" size="10" type='text'  onKeyPress="return js_mascara(event);" >
              <?=$Le60_numemp?>
              <? db_input("e60_numemp",10,$Ie60_numemp,true,"text",4,"","chave_e60_numemp"); ?>
            </td>
          </tr>
          <tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Te69_numero?>"><?=$Le69_numero?> </td>
            <td width="96%" align="left" nowrap>
              <?=db_input("e69_numero",10,$Ie69_numero,true,"text",4,"","chave_e69_numero"); ?>
              <?=$Le69_dtnota?>
              <?=db_inputdata('e69_dtnota','','','',false,'text','','','chave_e69_dtnota');?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>"><?=$Lz01_nome?></td>
            <td width="96%" align="left" nowrap>
              <? db_input("z01_nome",45,"",true,"text",4,"","chave_z01_nome"); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empnota.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere = "";
      $sAnd   = "";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
          if(file_exists("funcoes/db_func_empnota.php")==true){
            include(modification("funcoes/db_func_empnota.php"));
          }else{
            $campos = "empnota.*";
          }
        }
        if (isset($chave_e69_numero) && (trim($chave_e69_numero)!="") ) {
          $sWhere .= $sAnd . "e69_numero = '".trim($chave_e69_numero)."'";
          $sAnd    = " and ";
        }
        if (isset($chave_e69_dtnota) && (trim($chave_e69_dtnota)!="") ) {

          $e69_dtnota       =  explode("/", $chave_e69_dtnota);
          $e69_dtnota_ano   =  $e69_dtnota[2];
          $e69_dtnota_mes   =  $e69_dtnota[1];
          $e69_dtnota_dia   =  $e69_dtnota[0];
          $sWhere .= $sAnd . "e69_dtnota = '".$e69_dtnota_ano."-".$e69_dtnota_mes."-".$e69_dtnota_dia."'";
          $sAnd    = " and ";

        }
        if (isset($chave_e60_numemp) && (trim($chave_e60_numemp)!="") ){

          $sWhere .= $sAnd . "e60_numemp  = '".trim($chave_e60_numemp)."'";
          $sAnd    = " and ";

        }
        if (isset($lNaoTrazerAnuladas) && isset($lNaoTrazerAnuladas)){
          $sWhere .= $sAnd . "e70_vlranu  != e70_valor";
          $sAnd    = " and ";
        }
        if (isset($lm72_codordem) && isset($lm72_codordem)){
          $sWhere .= $sAnd . "m72_codordem is not null";
          $sAnd    = " and ";
        }
        if (isset($chave_e60_codemp) && (trim($chave_e60_codemp)!="") ){

          $arr = split("/",$chave_e60_codemp);
          if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
            $dbwhere_ano = " and e60_anousu = ".$arr[1];
          }else{
            $dbwhere_ano = " and e60_anousu =".db_getsession("DB_anousu");
          }
          $sWhere .= $sAnd . " e60_codemp ='".$arr[0]."'$dbwhere_ano";
          $sAnd    = " and ";
        }
        if (isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ) {

          $sWhere .= $sAnd . " z01_nome like '$chave_z01_nome%'";
          $sAnd    = " and ";

        }

        if (isset($suspensao) && $suspensao) {

          $campos .= ", case ";
          $campos .= " when not exists (select cc36_empnota from empnotasuspensao where e69_codnota =  cc36_empnota) ";
          $campos .= "  then 'Liberada' ";
          $campos .= " else ";
          $campos .= "   case when ( ";
          $campos .= "               select cc36_dataretorno ";
          $campos .= "               from empnotasuspensao ";
          $campos .= "               where e69_codnota =  cc36_empnota ";
          $campos .= "               order by cc36_sequencial desc ";
          $campos .= "               limit 1 ";
          $campos .= "             ) is null ";
          $campos .= "             then 'Suspensa' ";
          $campos .= "             else 'Liberada' ";
          $campos .= "   end ";
          $campos .= " end as \"dl_Situação da Nota\"";

          $sWhere .= " and e60_instit = " . db_getsession("DB_instit");
          $sWhere .= " and e70_vlrliq - e70_vlranu - e53_vlrpag > 0 ";
          $sWhere .= " and exists (select cc31_empempenho from classificacaocredoresempenho where cc31_empempenho = e60_numemp) ";
        }

        $sql = $clempnota->sql_query_nota("","distinct ".$campos,"e69_codnota",$sWhere);
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sql = $clempnota->sql_query_nota("",$campos,"e69_codnota","e69_codnota = $pesquisa_chave");
          $result = $clempnota->sql_record($sql);
          if($clempnota->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e69_codnota','$e69_numero',false);</script>";
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
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
