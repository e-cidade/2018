<?php
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

require_once "libs/db_utils.php";
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpcorcam         = new cl_pcorcam;
$clpcparam         = new cl_pcparam;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamval      = new cl_pcorcamval;
$clpcorcamjulg     = new cl_pcorcamjulg;
$clpcorcamtroca    = new cl_pcorcamtroca;

$db_opcao = 33;
$db_botao = false;
$db_open  = false;
$instit   = db_getsession("DB_instit");

if(isset($excluir)  || isset($retornoexcluival)){
  $sqlerro = false;
  db_inicio_transacao();
  $excluival = true;
  if(!isset($retornoexcluival)){
    $result_item = $clpcorcam->sql_record($clpcorcam->sql_query_vallancados($pc20_codorc,"count(*) as valoreslancados"));
    db_fieldsmemory($result_item,0);
    if($valoreslancados>0){
      $excluival = false;
    }
  }else{
    $pc20_codorc=$retornoexcluival;
  }
  if(isset($excluival) && $excluival==true){
    if($sqlerro==false && isset($retornoexcluival)){
      $clpcorcamtroca->excluir(null,"pc25_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.")");
      if($clpcorcamtroca->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clpcorcamtroca->erro_msg;
      }
      if($sqlerro==false){
        $clpcorcamjulg->excluir(null,null,"pc24_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc24_orcamforne in (select pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
        if($clpcorcamjulg->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clpcorcamjulg->erro_msg;
        }
      }
      if($sqlerro==false){
        $clpcorcamval->excluir(null,null,"pc23_orcamitem in (select distinct pc22_orcamitem from pcorcamitem where pc22_codorc=".$pc20_codorc.") and pc23_orcamforne in (select distinct pc21_orcamforne from pcorcamforne where pc21_codorc=".$pc20_codorc.")");
        if($clpcorcamval->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clpcorcamval->erro_msg;
        }
      }
    }
    if($sqlerro==false){
      $clpcorcamforne->excluir(null,"pc21_codorc=".$pc20_codorc);
      if($clpcorcamforne->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamforne->erro_msg;
      }
    }
    if($sqlerro==false){
      $clpcorcamitemproc->excluir(null,null,"pc31_orcamitem in(".$clpcorcamitem->sql_query_file(null,"pc22_orcamitem","","pc22_codorc=".$pc20_codorc).")");
      if($clpcorcamitemproc->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamitemproc->erro_msg;
      }
    }
    if($sqlerro==false){
      $clpcorcamitem->excluir(null,"pc22_codorc=".$pc20_codorc);
      if($clpcorcamitem->erro_status==0){
	$sqlerro=true;
	$erro_msg = $clpcorcamitem->erro_msg;
      }
    }
    if($sqlerro==false){
      $clpcorcam->excluir(null,"pc20_codorc=".$pc20_codorc);
      if($clpcorcam->erro_status==0){
	$sqlerro=true;
      }
      $erro_msg = $clpcorcam->erro_msg;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($retorno)){
  $db_opcao = 3;
  $db_botao = true;
  $result_clpcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($retorno,"pc20_codorc,pc20_hrate,pc20_dtate,pc20_obs"));
  if(isset($pc10_numero)){
    $resultado = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null,"pc81_codproc as pc80_codproc",""," pc10_numero=$pc10_numero and pc22_codorc=$retorno "));
    if($clpcorcamitemproc->numrows>0){
      db_fieldsmemory($resultado,0);
    }
  }
  $numrows_clpcorcam= $clpcorcam->numrows;
  if($numrows_clpcorcam > 0){
    db_fieldsmemory($result_clpcorcam,0);
  }
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $db_open  = true;
}
$db_chama = 'excluir';

/**
 * verifica se o processo de compras está vinculado a algum contrato.
 * caso esteja vinculado, não permite a exclusão
 */
$iNumeroAcordo = '';
if (isset($pc80_codproc)) {

  $oDaoAcordoPcprocitem = db_utils::getDao("acordopcprocitem");
  $sSqlDadosAcordo      = $oDaoAcordoPcprocitem->sql_query_acordo(null,
                                                          "ac26_acordo",
                                                           null,
                                                          "pc80_codproc = {$pc80_codproc}
                                                           and (ac16_acordosituacao  not in (2,3))"
                                                           );
  $rsDadosAcordo = $oDaoAcordoPcprocitem->sql_record($sSqlDadosAcordo);
  if ($oDaoAcordoPcprocitem->numrows > 0) {

    $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
    $db_botao      = false;
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" >
    <?php
      include "forms/db_frmprocesso.php";
    ?>
  </body>
</html>
<?php

if(isset($excluir)  || isset($retornoexcluival)){
  if($excluival==false){
    echo "
    <script>
      if(confirm('ATENÇÃO: \\nValor de um ou mais itens deste orçamento foi lançado! \\n\\nDeseja excluir valores lançados?')){
        document.location.href = 'com1_processo006.php?retornoexcluival=$pc20_codorc';
      }
    </script>
    ";
  }
  if($sqlerro==true){
    db_msgbox(str_replace("\n","\\n",$erro_msg));
    if($clpcorcam->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcam->erro_campo.".focus();</script>";
    };
  }else{
    echo "
     <script>
       function js_db_tranca(){
         top.corpo.iframe_orcam.location.href='com1_processo006.php';
       }\n
       js_db_tranca();
     </script>\n
    ";
  }
}
if(isset($retorno)){
 echo "
  <script>
      function js_db_libera(){
        parent.document.formaba.fornec.disabled=false;
	      top.corpo.iframe_fornec.location.href='com1_fornec001.php?solic=false&pc21_codorc=$retorno&db_opcaoal=33';

        parent.document.formaba.item.disabled = false;
        top.corpo.iframe_item.location.href = 'com1_itensproc001.php?pc22_codorc=" . @$pc20_codorc
                                                                  . "&pc80_codproc=" . @$pc80_codproc
                                                                  . "&db_opcaoal=33"
                                                                  . "&db_chama=" . @$db_chama
                                                                  . "&pc20_validadeorcamento=" . @$pc20_validadeorcamento
                                                                  . "&pc20_prazoentrega=" . @$pc20_prazoentrega . "';
      }\n
    js_db_libera();
  </script>\n
 ";
}
if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
}
if($db_open==true){
  $result_solic = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null," distinct pc81_codproc ","","pc22_codorc=".@$chavepesquisa." and (e54_autori is null or (e54_autori is not null and e54_anulad is not null))"));
  if($clpcorcamitemproc->numrows>0){
    db_fieldsmemory($result_solic,0);
    echo "<script>
              top.corpo.iframe_orcam.location.href = 'com1_processo006.php?retorno=$chavepesquisa&pc80_codproc=$pc81_codproc';
          </script>
            ";
  }else{
    $result_pcorcamitem = $clpcorcam->sql_record($clpcorcam->sql_query_solproc(null,"pc20_codorc","","pc20_codorc=$chavepesquisa and pc22_codorc is null"));
    if($clpcorcam->numrows!=0){
    echo "<script>
            top.corpo.iframe_orcam.location.href = 'com1_selsolicproc001.php?pc22_codorc=$chavepesquisa&op=excluir';
	  </script>
	  ";
    }else{
    echo "<script>
            alert('Usuário: \\n\\nOrçamento inexistente ou foi gerada autorização de empenho para este processo de compras.\\n\\nAdministrador:');
            top.corpo.iframe_orcam.location.href = 'com1_processo006.php';
	  </script>";
    }
  }
}
?>