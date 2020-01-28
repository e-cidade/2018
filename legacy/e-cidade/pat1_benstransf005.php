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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_benstransf_classe.php");
require_once("classes/db_benstransfdes_classe.php");
require_once("classes/db_db_depart_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_depusu_classe.php");
require_once("classes/db_benstransfcodigo_classe.php");
$cldb_depusu        = new cl_db_depusu;
$clbenstransf       = new cl_benstransf;
$clbenstransfdes    = new cl_benstransfdes;
$cldb_depart        = new cl_db_depart;
$cldb_usuarios      = new cl_db_usuarios;
$clbenstransfcodigo = new cl_benstransfcodigo;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
  $sqlerro = false;
  if ($db_param=="int"){
       $sql_depto_dest = $cldb_depart->sql_query_div(null,"descrdepto as depto_destino",""," coddepto=$t94_depart and coddepto<>$t93_depart and db_depart.instit = ".db_getsession("DB_instit"));
  } else {
       $sql_depto_dest = $cldb_depart->sql_query_file(null,"descrdepto as depto_destino",""," coddepto=$t94_depart and coddepto<>$t93_depart");
  }

  $dep_destino = $cldb_depart->sql_record($sql_depto_dest);

    if($cldb_depart->numrows>0){
      db_fieldsmemory($dep_destino,0);
    }else{
      $sqlerro=true;
      $erro_msg = _M("patrimonial.patrimonio.db_frmbenstransf.alteracao_nao_efetuada");
    }  
  db_inicio_transacao();
  if($t93_data_dia!="" && $t93_data_mes!="" && $t93_data_ano!=""){
    $data = $t93_data_ano."-".$t93_data_mes."-".$t93_data_dia;
//    db_msgbox($data.'-------------'.date("Y-m-d"));
    if($data<date("Y-m-d")){
      $sqlerro = true;
      $erro_msg = _M("patrimonial.patrimonio.db_frmbenstransf.data_invalida");
      $clapolice->erro_campo = "t93_data_dia";
    }
  }
  if($sqlerro == false){  
    $clbenstransf->alterar($t93_codtran);
    if($clbenstransf->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenstransf->erro_msg; 
  }
  if($sqlerro==false){
    $clbenstransfdes->t94_codtran = $t93_codtran;
    $clbenstransfdes->t94_depart  = $t94_depart;
    $clbenstransfdes->alterar($t93_codtran,null);
    if($clbenstransfdes->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenstransfdes->erro_msg; 
  }
  db_fim_transacao($sqlerro);
}

if(isset($chavepesquisa) || isset($alterar)){
  if(isset($chavepesquisa)){
    $t93_codtran=$chavepesquisa;
  }
  //rotina q traz data da transferência, descrição do depto de destino e o código da transferência depois de atualizado.
  $result = $clbenstransfdes->sql_record($clbenstransfdes->sql_query($t93_codtran,null,"t94_codtran as t93_codtran,t94_depart,db_depart.descrdepto as depto_destino,t93_data,t93_obs"));
  if($clbenstransfdes->numrows>0){
    db_fieldsmemory($result,0);
  }
  $db_opcao = 2;
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

	<?
	include("forms/db_frmbenstransf.php");
	?>
    
</body>
</html>
<?
if(isset($alterar)){
    db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clbenstransf->erro_campo!=""){
      echo "<script> document.form1.".$clbenstransf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransf->erro_campo.".focus();</script>";
    };
  }
}
if(isset($chavepesquisa) || (isset($alterar) && $sqlerro == false)){
 echo "
  <script>     
      function js_db_libera(){
         parent.document.formaba.benstransfcodigo.disabled=false;
         top.corpo.iframe_benstransfcodigo.location.href='pat1_benstransfcodigo001.php?t95_codtran=".@$t93_codtran."&t93_depart=$t93_depart&db_param=$db_param&depto='+document.form1.t94_depart.value;
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('benstransfcodigo');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>