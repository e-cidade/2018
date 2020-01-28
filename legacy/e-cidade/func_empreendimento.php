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
require_once("classes/db_empreendimento_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempreendimento = db_utils::getDao('empreendimento');

$clrotulo = new rotulocampo;
$clrotulo->label("am05_nome");

?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label>Código:</label></td>
          <td><?php db_input("am05_sequencial",10,1,true,"text",4,"","chave_am05_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label>Nome:</label></td>
          <td><?php db_input("am05_nome",40,$Iam05_nome,true,"text",4,"","chave_am05_nome"); ?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empreendimento.hide();">
  </form>
      <?php

      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_empreendimento.php")==true){
             include("funcoes/db_func_empreendimento.php");
           }else{
             $campos = "empreendimento.*";
           }
        }
        if(isset($chave_am05_sequencial) && (trim($chave_am05_sequencial)!="") ){
	         $sql = $clempreendimento->sql_query_empreendimento_atividade($chave_am05_sequencial,$campos,"am05_sequencial");
        }else if(isset($chave_am05_nome) && (trim($chave_am05_nome)!="") ){
	         $sql = $clempreendimento->sql_query_empreendimento_atividade("",$campos,"am05_nome"," am05_nome ilike '$chave_am05_nome%' ");
        }else{
           $sql = $clempreendimento->sql_query_empreendimento_atividade("",$campos,"am05_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am05_nome)){
          $repassa = array("chave_am05_sequencial"=>$chave_am05_sequencial,"chave_am05_nome"=>$chave_am05_nome);
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clempreendimento->sql_record($clempreendimento->sql_query_empreendimento_atividade($pesquisa_chave));
          if($clempreendimento->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am05_nome',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_am05_nome",true,1,"chave_am05_nome",true);
</script>
