<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_rhestagioagenda_classe.php");
include("classes/db_rhestagioagendadata_classe.php");
include("classes/db_rhestagioperiodo_classe.php");
include("classes/db_rhestagioperiodomes_classe.php");
$clrhestagioagenda     = new cl_rhestagioagenda;
$clrhestagioagendadata = new cl_rhestagioagendadata;
$clrhestagioperiodo    = new cl_rhestagioperiodo;
$clrhestagioperiodomes = new cl_rhestagioperiodomes;
$post                  = db_utils::postMemory($_POST);
$db_opcao = 1;
$db_botao = true;
if (isset($incluir)){
  $lSqlErro = false;
  $dataUsu  = db_getsession("DB_datausu");
  db_inicio_transacao();
  //verificamos se ja existe um estagio cadastrado para essa matricula.caso houver, nao podemos cadastrar outro;
  $rsEstagio = $clrhestagioagenda->sql_record($clrhestagioagenda->sql_query_file(null,"h57_sequencial",null,"h57_regist={$post->h57_regist}"));
  if ($clrhestagioagenda->numrows > 0){

      $lSqlErro = true;
      $erro_msg = "Matrícula {$post->h57_regist} já possui um estagio cadastrado.";
  }
  if (!$lSqlErro){
     $clrhestagioagenda->h57_instit = db_getsession("DB_instit");
     $clrhestagioagenda->incluir($h57_sequencial);
     if($clrhestagioagenda->erro_status==0){

       $lSqlErro = true;
       $erro_msg = $clrhestagioagenda->erro_msg; 
     } 
  }
  if (!$lSqlErro){

    $rsPeriodo = $clrhestagioperiodomes->sql_record($clrhestagioperiodomes->sql_query(null,"h66_mes", "h66_mes",
                                                 "h55_rhestagio = ".$post->h57_rhestagio));    
    $iNumRows  = $clrhestagioperiodomes->numrows;
    if ($iNumRows > 0){
       for ($i = 0; $i < $iNumRows; $i++){
          $oPeriodo = db_utils::fieldsMemory($rsPeriodo,$i);
          $iMeses   = 0;
          $dataAdmAux = explode("/",$post->rh01_admiss); 
          $data = mktime(0,0,0,$dataAdmAux[1]+$oPeriodo->h66_mes,$dataAdmAux[0],$dataAdmAux[2]);
          $clrhestagioagendadata->h64_data          = date("Y-m-d",$data);
          $clrhestagioagendadata->h64_estagioagenda = $clrhestagioagenda->h57_sequencial;
          $clrhestagioagendadata->h64_seqaval       = ($i+1);
          $clrhestagioagendadata->incluir(null);
          if ($clrhestagioagendadata->erro_status == 0){
        
             $lSqlErro = true;
             $erro_msg = " Data:". $clrhestagioagendadata->erro_msg; 
         }
       }
    }else{
       $lSqlErro = TRUE;
       $erro_msg = "Estágio sem período de Avaliação cadastrado.\\nVerifique";
    }
  }
  db_fim_transacao($lSqlErro);
  if (!$lSqlErro){
     $h57_sequencial= $clrhestagioagenda->h57_sequencial;
     $erro_msg = $clrhestagioagenda->erro_msg; 
     $db_opcao = 1;
     $db_botao = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhestagioagenda.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($post->incluir)){
  if($lSqlErro){
    db_msgbox($erro_msg);
    if($clrhestagioagenda->erro_campo!=""){
      echo "<script> document.form1.".$clrhestagioagenda->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhestagioagenda->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("rec1_rhestagioagenda005.php?liberaaba=true&chavepesquisa=$h57_sequencial");
  }
}
?>