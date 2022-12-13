<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_bensmedida_classe.php");
require_once("classes/db_bensmodelo_classe.php");
require_once("classes/db_bensmarca_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clBensMedida     = new cl_bensmedida;
$clBensMarca      = new cl_bensmarca;
$clBensModelo     = new cl_bensmodelo;

$db_opcao = 2;
$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js");
  db_app::load("estilos.css");
?>
<style type="text/css">
  .bold {
    font-weight: bold;
  }
  table {
    border-collapse: collapse;
  }
  
  table tr td{
    padding-top:4px;
    white-space:nowrap;
  }
  table tr td:first-child{
    text-align: left;
    width: 130px;
  }
  
  /* pega a segunda td */
  table tr td + td{
    
  }
  
  /* pega a terceira td */
  table tr td + td + td{
    text-align: right;
    padding-left: 5px;
    width: 100px;
  }
  
  table tr td + td + td + td{
    text-align: left;
    width: 150px;
  }
  .ancora {
    font-weight: bold;
  }
  
  
</style>
</head>
<body bgcolor="#CCCCCC" onload="js_carregaDadosForm(<?=$db_opcao?>);" >
<div style="margin-top: 25px;" ></div>
<center>
  <div align="center" style="width: 720px; display: block;">
    <?
      include ("forms/db_frm_bensnovo.php");
    ?>
  </div>
</center>
</body>
</html>

<?
if(isset($incluir)){

  if (trim(@$erro_msg)!=""){
       db_msgbox($erro_msg);
  }
  if($sqlerro==true){
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    }
  } else {
    db_redireciona("pat1_bensglobal001.php?".$parametros."liberaaba=true&chavepesquisa=$t52_bem");
  }
}
?>