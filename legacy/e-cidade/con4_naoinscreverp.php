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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conencerramento_classe.php");
include("classes/db_conencerramentolancam_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_empelemento_classe.php");
include("libs/db_libcontabilidade.php");

$cltranslan           = new cl_translan();
$clencerramentolancam = new cl_conencerramentolancam();
$clconencerramento    = new cl_conencerramento();
$clempelemento        = new cl_empelemento();
$clconlancam          = new cl_conlancam();
$clconlancamemp       = new cl_conlancamemp();
$clconlancamdoc       = new cl_conlancamdoc();
$clconlancamval       = new cl_conlancamval();
$clconlancamlr        = new cl_conlancamlr();
$get                  = db_utils::postmemory($_GET);
$post                 = db_utils::postmemory($_POST);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cab  {font-weight:bold;text-align:center;
       padding:2px;
			 border-bottom:1px outset black;
			 border-left:1px outset black;           
       background-color:#EEEFF2;          
	
	}
.linhagrid{ border:collapse;
            border-right:1px inset black;
            border-bottom:1px inset black;
            cursor:normal;
 }
.marcado{ background-color:#EFEFEF}
.normal{background-color:#FFFFFF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<div class='cab'>
Não Inscrição de RPS
</div>
<br>
<br>
<center>
<fieldset><legend>Opções</legend>
<form name='form1' method='post'>
<input type='submit' name='confirma' value='Confirmar' onclick="return confirm('Essa Rotina Ira Confirmar a não inscrição dos RPS no sistema.\nClique em OK para prosseguir')"; />
<input type='submit' name='cancela' value='Cancelar' onclick="return confirm('Essa Rotina Ira Confirmar o cancelamento da não inscrição dos RPS no sistema.\nClique em OK para prosseguir')"; />

<?php
(boolean)$lSqlErro     = false;
(string) $sErroMsg     = null;
(float)  $nValorLancar = 0;
(integer)$iAnoUsu      = db_getsession("DB_anousu");
(integer)$iInstit      = db_getsession("DB_instit");
$data                  = explode("/",$get->datalanc);
$dataIni               = $iAnoUsu.'-01-01';
$dataUsu               = $data[2]."-".$data[1]."-".$data[0];
if (isset($post->confirma)){
    $rsEnce   = $clconencerramento->sql_record($clconencerramento->sql_query(null,"*",null," c42_instit       = $iInstit 
                                                               and  c42_anousu           = $iAnoUsu
                                                               and  c42_encerramentotipo = 5")); 
    if ($clconencerramento->numrows == 0){
    
       db_inicio_transacao();
       $clconencerramento->c42_instit           = $iInstit;
       $clconencerramento->c42_anousu           = $iAnoUsu;
       $clconencerramento->c42_encerramentotipo = 5;
       $clconencerramento->c42_usuario          = db_getsession("DB_id_usuario");
       $clconencerramento->c42_hora             = date("H:i");
       $clconencerramento->c42_data             = $dataUsu;
       $clconencerramento->incluir(null);
       db_fim_transacao();
       if ($clconencerramento->erro_status == "0"){
          db_msgbox("Não foi possivel concluir a operação.");
          echo "<script>parent.location.reload();</script>";

       }else{
          db_msgbox("Operação realizada com sucesso.");
          echo "<script>parent.location.reload();</script>";
       }
    }else{
      
      db_msgbox("Procedimento já executado!\\nPara executa-lo novamente, primeiro  cancele o existente.");
   }
}
if (isset($post->cancela)){

   db_inicio_transacao();
   $clconencerramento->excluir(null,"c42_instit           = $iInstit 
                              and  c42_anousu           = $iAnoUsu
                              and  c42_encerramentotipo = 5"); 
  if ($clconencerramento->erro_status == "0"){
     
      db_msgbox("houve  um erro ao cancelar encerramento");

  }else{
      db_fim_transacao();
      db_msgbox("Cancelamento efetuado com sucesso");
      echo "<script>parent.location.reload();</script>";
  }
}
?>
</center>
</body>
</html>