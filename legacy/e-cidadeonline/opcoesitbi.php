<?
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_iptubase_classe.php");
$cliptubase  = new cl_iptubase;
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaitbi.php'
                   ORDER BY m_descricao
                  ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode("erroscripts=3")."'</script>";
}
mens_help();
db_mensagem("opcoesitbi_cab","opcoesitbi_rod");
$db_verifica_ip = db_verifica_ip();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
if( !isset($matricula) ) {
  if (!isset($matricula1) or !isset($cgc) or !isset($cpf)){
    db_logs("","",0,"Acesso a Rotina Inválido.");
    db_redireciona("digitatbi.php?".base64_encode("erroscripts=Os dados informados não conferem, verifique!"));
  }
$matricula = $matricula1;
  $cgc = $cgc;
  $cpf = $cpf;
  if ( !empty($cgc) ){
    $cgccpf = $cgc;
  }else{
     if ( !empty($cpf) ){
       $cgccpf = $cpf;
         }else{
           $cgccpf = "";
         }
  }
if($db_verifica_ip=="0"){
  if($cgccpf==""){
    db_redireciona('digitaitbi.php?'.base64_encode('erroscripts=Informe o CNPJ/CPF do contribuinte.'));
    exit;
  }
}
if (!isset($matricula) or empty($matricula) or !is_int(0 + $matricula)){
     db_logs("","",0,"Variavel Matricula Invalida.");
    db_redireciona("digitatbi.php?".base64_encode("erroscripts=Os dados informados não conferem, verifique!"));
  }

  $cgccpf = str_replace(".","",$cgccpf);
  $cgccpf = str_replace("/","",$cgccpf);
  $cgccpf = str_replace("-","",$cgccpf);  

  $sql_exe = "select ident from db_config";
  $result = db_query($sql_exe) or die("Erro: ".pg_ErrorMessage($conn));                                         
  db_fieldsmemory($result,0);
/*
  $sql_exe = "select * from iptubase,cgm
                     where j01_matric = $matricula and 
                               j01_numcgm = z01_numcgm ";
    if ( $db_verifica_ip == "0" ) {
      $sql_exe = $sql_exe . " and trim(z01_cgccpf) = '$cgccpf' and trim(z01_cgccpf) != ''";
    }
  $result = db_query($sql_exe) or die("Erro: ".pg_ErrorMessage($conn));
*/
  $result  = $cliptubase->sql_record($cliptubase->sql_query("","*","","iptubase.j01_matric = $matricula and j01_numcgm = z01_numcgm"));
}else{
/*
$result = db_query("select * from iptubase,cgm
                     where j01_matric = $matricula and 
                               j01_numcgm = z01_numcgm") or die("Erro: ".pg_ErrorMessage($conn));
  $cgccpf = trim(pg_result($result,0,'z01_cgccpf'));
*/
  $result  = $cliptubase->sql_record($cliptubase->sql_query("","*","","iptubase.j01_matric = $matricula and cgm.j01_numcgm = z01_numcgm"));
}
db_fieldsmemory($result,0);
include("libs/db_mens.php");
if($cliptubase->numrows == 0 ){
  db_logs("$matricula","",0,"Dados Inconsistentes. Numero : $matricula");
  db_redireciona("digitaitbi.php?".base64_encode("erroscripts=Os dados informados não conferem, verifique!"));
  $script = false; 
} else if($z01_cgccpf == "00000000000000" || $z01_cgccpf == "              ") {
  $script = true; 
}
if(!isset($DB_LOGADO)  && $m_publico !='t'){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricao)";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    db_redireciona("digitaissqn.php?".base64_encode("erroscripts=Acesso não Permitido. Contate a Prefeitura."));
    exit;
  }
  $result = pg_result($result,0,0);
  if($result=="0"){
    db_redireciona("digitaissqn.php?".base64_encode("erroscripts=Acesso não Permitido. Contate a Prefeitura."));
    exit;
  }
} 
db_logs("$matricula","",0,"Matricula Pesquisada. Numero : $matricula");
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("digitaitbi.php,listabicimovel.php,listaitbisolicitacao.php,listaitbiverifica.php");
</script>
<style type="text/css">
<? db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
mens_div();
?>
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="60" align="<?=$DB_align1?>">
   <?=$DB_mens1?>
  </td>
 </tr>
 <tr>
  <td height="200" align="center" valign="middle">
   <div align="center">
   <form name="form1" action="">
   <table width="50%" cellpadding="0" cellspacing="0" border="1" style="border-color: transparent">
    <tr>
     <td align="center" height="28">
      <a class="links" href="listaitbisolicitacao.php?<?=base64_encode('matricula='.$matricula)?>">Solicita ITBI</a>
     </td>
    </tr>
    <tr>
     <td align="center" height="28">
     <a class="links" href="listaitbiverifica.php?<?=base64_encode('matricula='.$matricula)?>">Verifica Libera&ccedil;&atilde;o da Guia</a>
     </td>
    </tr>
    <tr>
     <td align="center" height="28">
      <a class="links" href="listabicimovel.php?<?=base64_encode('matricula='.$matricula.'&pagina=itbi')?>"  onClick="MM_showHideLayers('Layer1','','show')">Informa&ccedil;&otilde;es do Im&oacute;vel</a>
     </td>
    </tr>
  </table>
  </div>
    </td>
  </tr>
  <tr>
    <td height="60" align="<?=$DB_align2?>">
      <?=$DB_mens2?>
    </td>
  </tr>
</table>
</center>
<?
if(@$script == true)
echo "<script>alert('$MensCgcCpf')</script>\n";
?>