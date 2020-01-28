<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_procedimento_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clprocedimento = new cl_procedimento;
$clprocedimento->rotulo->label("ed40_i_codigo");
$clprocedimento->rotulo->label("ed40_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <div class="container">

    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>

        <table width="55%" border="0" align="center" cellspacing="0">
          <tr>
            <td title="<?=$Ted40_i_codigo?>">
              <label for="chave_ed40_i_codigo"><?=$Led40_i_codigo?> </label>
            </td>
            <td  nowrap>
              <?php db_input("ed40_i_codigo",10,$Ied40_i_codigo,true,"text",4,"","chave_ed40_i_codigo");?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Ted40_c_descr?>">
              <label for="chave_ed40_c_descr"><?=$Led40_c_descr?></label>
            </td>
            <td >
              <?php db_input("ed40_c_descr",30,$Ied40_c_descr,true,"text",4,"","chave_ed40_c_descr");?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procedimento.hide();">
    </form>
  </div>

  <?php

    $aWhere   = array();
    // só deve filtrar escola quando acessado do módulo escola

    $iEscola       = db_getsession("DB_coddepto");
    $sWhereEscola = " ed86_i_escola = {$iEscola}";

    if ( isModuloSecretariaEducacao() || (isset($lProcedimentosSecretaria) && $lProcedimentosSecretaria) ) {
      $sWhereEscola = " ed86_i_codigo is null";
    }

    $aWhere[] = $sWhereEscola;
    $aWhere[] = " ed40_desativado is false ";
    if(!isset($pesquisa_chave)) {

      if(isset($campos)==false) {
        if(file_exists("funcoes/db_func_procedimento.php")==true){
          include(modification("funcoes/db_func_procedimento.php"));
        }else{
         $campos = "procedimento.*";
        }
      }
      if(isset($chave_ed40_i_codigo) && (trim($chave_ed40_i_codigo)!="") ){
        $aWhere[] = " ed40_i_codigo = {$chave_ed40_i_codigo} ";
      }else if(isset($chave_ed40_c_descr) && (trim($chave_ed40_c_descr)!="") ){
        $aWhere[] = " ed40_c_descr like '{$chave_ed40_c_descr}%'";
      }

      $sWhere = implode(' and ', $aWhere);
      $sql    = $clprocedimento->sql_query_origem_procedimento("",$campos,"ed40_c_descr", $sWhere);

      $repassa = array();
      if(isset($chave_ed40_c_descr)){
        $repassa = array("chave_ed40_i_codigo"=>$chave_ed40_i_codigo,"chave_ed40_c_descr"=>$chave_ed40_c_descr);
      }

      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql,15,"()","",$funcao_js, "", "NoMe", $repassa);
      echo '  </fieldset>';
      echo '</div>';

    } else {

      if($pesquisa_chave!=null && $pesquisa_chave!=""){

        $aWhere[] = " ed40_i_codigo = {$pesquisa_chave} ";
        $sWhere   = implode(' and ', $aWhere);
        $result   = $clprocedimento->sql_record($clprocedimento->sql_query_origem_procedimento("","*","", $sWhere));
        if($clprocedimento->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ed40_c_descr',false);</script>";
        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
  ?>

</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
