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
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaotfd_ajudacusto = db_utils::getdao('tfd_ajudacusto');
$oDaotfd_ajudacusto->rotulo->label('tf12_i_codigo');
$oRotulo = new rotulocampo;
$oRotulo->label('sd63_c_nome');
$oRotulo->label('sd63_c_procedimento');

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
            <td align="right" nowrap title="<?=$Ttf12_i_codigo?>">
              <?=$Ltf12_i_codigo?>
            </td>
            <td align="left" nowrap colspan="3">
              <?
  	          db_input("tf12_i_codigo",10,$Itf12_i_codigo,true,"text",4,"","chave_tf12_i_codigo");
		          ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=$Tsd63_c_procedimento?>">
              <?=$Lsd63_c_procedimento?>
            </td>
            <td align="left" nowrap>
              <?
		          db_input("sd63_c_procedimento",10,$Isd63_c_procedimento,true,"text",4,"","chave_sd63_c_procedimento");
		          ?>
            </td>
            <td align="right" nowrap title="<?=$Tsd63_c_nome?>">
              <?=$Lsd63_c_nome?>
            </td>
            <td align="left" nowrap>
              <?
		          db_input("sd63_c_nome",50,$Isd63_c_nome,true,"text",4,"","chave_sd63_c_nome");
		          ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_ajudacusto.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sValidade = '';
      $sSepVal = '';
      if(isset($chave_validade)) {

        $dDataAtual = date('Y-m-d', db_getsession('DB_datausu'));
        $sValidade = " tf12_d_validadeini <= '$dDataAtual' and ".
                     "(tf12_d_validadefim is null or tf12_d_validadefim >= '$dDataAtual')";
        $sSepVal = ' and ';

      }

      $sWhere = '';
      if (isset($lTrazAjudaAutomatico)) {
        $sWhere .= " and tf12_faturabpa is $lTrazAjudaAutomatico";
      }
      
      if ( isset($lAcompanhante)) {
        $sWhere .= " and tf12_acompanhente is {$lAcompanhante} "; 
      }
      
      if(!isset($pesquisa_chave)) {

        if(isset($campos) == false) {

           if(file_exists("funcoes/db_func_tfd_ajudacusto.php")==true) {
             require_once("funcoes/db_func_tfd_ajudacusto.php");
           } else {
             $campos = "tfd_ajudacusto.*";
           }

        }

        $sOrderBy =
          ' tf12_i_codigo, sau_procedimento.sd63_c_procedimento,
            sau_procedimento.sd63_i_anocomp desc,
            sau_procedimento.sd63_i_mescomp desc ';

        if(isset($chave_tf12_i_codigo) && (trim($chave_tf12_i_codigo)!="") ) {

	        $sql = $oDaotfd_ajudacusto->sql_query_valor_unitario(null, $campos, $sOrderBy,
                                              " tf12_i_codigo = $chave_tf12_i_codigo $sSepVal $sValidade".$sWhere);

        } else if(isset($chave_sd63_c_procedimento) && (trim($chave_sd63_c_procedimento) != '') ) {

	        $sql = $oDaotfd_ajudacusto->sql_query_valor_unitario(null, $campos, $sOrderBy,
                                              " sau_procedimento.sd63_c_procedimento = '$chave_sd63_c_procedimento'".
                                              " $sSepVal $sValidade".$sWhere);

        } else if(isset($chave_sd63_c_nome) && (trim($chave_sd63_c_nome) != '') ) {

	        $sql = $oDaotfd_ajudacusto->sql_query_valor_unitario(null, $campos, $sOrderBy,
                                              " sau_procedimento.sd63_c_nome like '$chave_sd63_c_nome%'".
                                              "$sSepVal $sValidade".$sWhere);

        } else {
          $sql = $oDaotfd_ajudacusto->sql_query_valor_unitario(null, $campos, $sOrderBy, $sValidade.$sWhere);
        }
        
        if(isset($nao_mostra)) {

          $sSep = '';
          $aFuncao = explode('|', $funcao_js);
          $rs = $oDaotfd_ajudacusto->sql_record($sql);
           if($oDaotfd_ajudacusto->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_tf12_i_codigo.") não Encontrado');</script>");
           } else {

             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {
               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep = ', ';

             }
             $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }
        }

        $repassa = array();
        if(isset($chave_tf12_i_codigo)) {
          $repassa = array('chave_tf12_i_codigo'=>$chave_tf12_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $oDaotfd_ajudacusto->sql_record($cltfd_ajudacusto->sql_query(null, '*' , null , "tfd_ajudacusto.tf12_i_codigo = {$pesquisa_chave} {$sWhere}"));
          if($oDaotfd_ajudacusto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf12_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_tf12_i_codigo",true,1,"chave_tf12_i_codigo",true);
</script>