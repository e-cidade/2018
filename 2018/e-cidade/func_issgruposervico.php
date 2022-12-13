<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

db_postmemory($_POST);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissgruposervico = new cl_issgruposervico;
$clissgruposervico->rotulo->label("q126_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tq126_sequencial?>">
              <?=$Lq126_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("q126_sequencial",10,$Iq126_sequencial,true,"text",4,"","chave_q126_sequencial");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issgruposervico.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issgruposervico.php")==true){
             include("funcoes/db_func_issgruposervico.php");
           }else{
           $campos = "issgruposervico.*";
           }
        }

        $sWhere = " db121_tipoconta = 2 and q136_exercicio = " . db_getsession('DB_anousu');

        $campos = "issgruposervico.q126_sequencial, db_estruturavalor.db121_estrutural, db_estruturavalor.db121_descricao::varchar(90), q136_exercicio";
        if(isset($chave_q126_sequencial) && (trim($chave_q126_sequencial)!="") ){

           $sWhere .= " and issgruposervico.q126_sequencial = {$chave_q126_sequencial} ";
	         $sql = $clissgruposervico->sql_query_EstruturalExercicio("", $campos, "q136_exercicio, db121_estrutural", $sWhere);
        }else if(isset($chave_q126_sequencial) && (trim($chave_q126_sequencial)!="") ){
	         $sql = $clissgruposervico->sql_query_EstruturalExercicio("",$campos,"q136_exercicio, db121_estrutural"," q126_sequencial like '$chave_q126_sequencial%' and {$sWhere} ");
        }else{
           $sql = $clissgruposervico->sql_query_EstruturalExercicio("",$campos,"q136_exercicio, db121_estrutural",$sWhere );
        }

        $repassa = array();
        if(isset($chave_q126_sequencial)){
          $repassa = array("chave_q126_sequencial"=>$chave_q126_sequencial,"chave_q126_sequencial"=>$chave_q126_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $result = $clissgruposervico->sql_record($clissgruposervico->sql_query($pesquisa_chave));
          if ($clissgruposervico->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db121_descricao',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_q126_sequencial",true,1,"chave_q126_sequencial",true);
</script>