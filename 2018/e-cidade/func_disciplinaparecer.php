<?php
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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_disciplina_classe.php");
include("classes/db_caddisciplina_classe.php");
include("classes/db_cursoescola_classe.php");
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldisciplina     = new cl_disciplina;
$clcaddisciplina  = new cl_caddisciplina;
$clcursoescola    = new cl_cursoescola();
$cldisciplina->rotulo->label("ed12_i_codigo");
$clcaddisciplina->rotulo->label("ed232_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
  <div style="margin-top: 25px; width: 500px">
    <form name="form2" method="post" action="" >
      <table width="35%" border="0" cellspacing="0">
        <tr>
          <td width="4%"  nowrap title="<?=$Ted12_i_codigo?>">
            <?=$Led12_i_codigo?>
          </td>
          <td width="96%"  nowrap>
            <?db_input("ed12_i_codigo",10,$Ied12_i_codigo,true,"text",4,"","chave_ed12_i_codigo");?>
          </td>
        </tr>
        <tr>
          <td width="4%"  nowrap title="<?=$Ted232_c_descr?>">
            <b>Descrição da Disciplina:</b>
          </td>
          <td width="96%" nowrap>
            <?db_input("ed232_c_descr",40,$Ied232_c_descr,true,"text",4,"","chave_ed232_c_descr");?>
          </td>
        </tr>
        <tr>
          <td width="4%"  nowrap >
            <b>Nível de Ensino:</b>
          </td>
          <td width="96%" nowrap>
            <?
             $sSqlEnsino = $clcursoescola->sql_query(null,
                                                     "distinct ed10_i_codigo, ed10_c_descr",
                                                     "ed10_c_descr",
                                                     "ed71_i_escola=".db_getsession("DB_coddepto")
                                                    );
             $rsEnsino  = $clcursoescola->sql_record($sSqlEnsino);
             $aEnsino   = array("0" => "");
             for ($i = 0; $i < $clcursoescola->numrows; $i++) {

                $oEnsino = db_utils::fieldsMemory($rsEnsino, $i);
                $aEnsino[$oEnsino->ed10_i_codigo] = $oEnsino->ed10_c_descr;
             }
             db_select("ed10_i_codigo", $aEnsino, true, 1);
            ?>
          </td>
        </tr>
      </table>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_disciplinaparecer.hide();">
    </form>
    <div style="margin-top: 10px;">
      <?
        $sCampos  = "distinct ed12_i_codigo, ed10_c_descr,";
        $sCampos .= "trim(ed10_c_abrev)::varchar as ed10_c_abrev, trim(ed232_c_descr)::varchar as ed232_c_descr ";
        $aWhere   = array();

        if(isset($chave_ed12_i_codigo) && !empty($chave_ed12_i_codigo)) {
          $aWhere[] = " ed12_i_codigo = {$chave_ed12_i_codigo}";
        }
        if(isset($chave_ed232_c_descr) && !empty($chave_ed232_c_descr)) {
          $aWhere[] = "ed232_c_descr ilike '%{$chave_ed232_c_descr}%'";
        }
        if(isset($ed10_i_codigo) && !empty($ed10_i_codigo)) {
          $aWhere[] = "ed29_i_ensino =  $ed10_i_codigo";
        }
        $aWhere[] = "ed71_i_escola = ".db_getsession("DB_coddepto");
        $sWhere   = implode(' and ', $aWhere);
        $repassa  = array();
        if (isset($chave_ed265_i_codigo)) {

          $repassa = array("chave_ed12_i_codigo" => $chave_ed12_i_codigo,
                           "chave_ed232_c_descr" => $chave_ed232_c_descr,
                           "ed10_i_codigo"       => $ed10_i_codigo
                          );
        }

        $sql = $cldisciplina->sql_query_disciplinas_na_escola(null, $sCampos, " ed10_c_abrev ", $sWhere);
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      ?>
     </div>
   </div>
</center>
</body>
</html>
<script>
//js_tabulacaoforms("form2","chave_ed12_i_codigo",true,1,"chave_ed232_c_descr",true);
</script>