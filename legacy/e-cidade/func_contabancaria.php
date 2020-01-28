<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clcontabancaria = new cl_contabancaria;
$clcontabancaria->rotulo->label("db83_sequencial");
$clcontabancaria->rotulo->label("db83_descricao");
$clcontabancaria->rotulo->label("db83_conta");

if ( !isset($chave_tipo_conta) ) {
  $chave_tipo_conta = "1";
}

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body>
<?php

require_once("forms/db_frmPesquisaContaBancaria.php");


$sWhere = '1=1';

if ( isset($bancoagencia) && trim($bancoagencia) != '' )  {
  $sWhere .= " and db83_bancoagencia = {$bancoagencia} ";
}

if ( isset($mostra_tipo_conta) && trim($mostra_tipo_conta) != '' )  {

  switch ( $mostra_tipo_conta ) {
  case "1":
    $sWhere .= " and (not exists ( select 1 from rhpessoalmovcontabancaria where rh138_contabancaria = db83_sequencial )";
    $sWhere .= "      and ";
    $sWhere .= "      not exists ( select 1 from pensaocontabancaria       where rh139_contabancaria = db83_sequencial )";
    $sWhere .= "      )";
    break;
  case "2":
    $sWhere .= " and (exists ( select 1 from rhpessoalmovcontabancaria where rh138_contabancaria = db83_sequencial )";
    $sWhere .= "      or ";
    $sWhere .= "      exists ( select 1 from pensaocontabancaria       where rh139_contabancaria = db83_sequencial )";
    $sWhere .= "      )";
    break;
  } 
}

if(!isset($pesquisa_chave)){

  if(isset($campos)==false){

    $campos = "contabancaria.*";

    if(file_exists("funcoes/db_func_contabancaria.php")==true){
      include("funcoes/db_func_contabancaria.php");
    }
  }

  if (isset($chave_db83_sequencial) && (trim($chave_db83_sequencial)!="") ) {
    $sql = $clcontabancaria->sql_query(null,$campos,"db83_sequencial",$sWhere." and db83_sequencial = ".$chave_db83_sequencial);
  } else if (isset($chave_db83_descricao) && (trim($chave_db83_descricao)!="") ) {
    $sql = $clcontabancaria->sql_query("",$campos,"db83_descricao",$sWhere." and db83_descricao like '$chave_db83_descricao%' ");
  } else if ( isset($chave_db83_conta) && (trim($chave_db83_conta) != "") ) {
    $sql = $clcontabancaria->sql_query("",$campos,"db83_conta",$sWhere." and db83_conta like '$chave_db83_conta%' ");
  } else {
    $sql = $clcontabancaria->sql_query("",$campos,"db83_sequencial",$sWhere);
  }
  $repassa = array();

  if(isset($chave_db83_descricao)){
    $repassa = array("chave_db83_sequencial"=>$chave_db83_sequencial,"chave_db83_descricao"=>$chave_db83_descricao);
  }

  echo "<div class='container'>";
  echo "  <fieldset>";
  echo "    <legend>Resultado da Pesquisa</legend>";
  db_lovrot($sql,15,"()","",@$funcao_js,"","NoMe",$repassa);
  echo "  </fieldset>";
  echo "</div>";

}else{

  if($pesquisa_chave != null && $pesquisa_chave != ""){

    if(isset($tp) && $tp == 1){
      $sWhere .= " and db83_sequencial = '".$pesquisa_chave."'";
    } else {
      $sWhere .= " and db83_conta = '".$pesquisa_chave."'";
    }

    $sSql   = $clcontabancaria->sql_query(null,"*",null,$sWhere);
    $result = $clcontabancaria->sql_record($sSql);

    if($clcontabancaria->numrows != 0){

      db_fieldsmemory($result,0);
      if (isset($lImplantacao) && $lImplantacao == 1) {
        echo "<script>".$funcao_js."('$db83_descricao',false);</script>";
      } else {
        echo "<script>".$funcao_js."(false,'$db83_conta','$db83_dvconta','$db83_identificador','$db83_codigooperacao','$db83_tipoconta','$db83_bancoagencia','$db83_sequencial');</script>";
      }
    }else{
      echo "<script>".$funcao_js."(true,'','','','','','','');</script>";
    }
  } else {
    echo "<script>".$funcao_js."(false,'','','','','','','');</script>";
  }
}
?>
    <script>
      js_tabulacaoforms("form2","chave_db83_descricao",true,1,"chave_db83_descricao",true);
    </script>
  </body>
</html>
