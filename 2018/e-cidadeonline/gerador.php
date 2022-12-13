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
//require("libs/db_conecta.php");
require("libs/db_stdlib.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitainscricao.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
include("classes/db_db_certidaoweb_classe.php");
$clcertidao = new cl_db_certidaoweb;
$result = $clcertidao->sql_record($clcertidao->sql_query("","*","","ceracesso = '".$cod."'"));
if($clcertidao->numrows == 0){
  echo"<script>window.opener.alert('CNPJ ou CPF inválidos')</script>";
  echo"<script>window.close()</script>";
  db_redireciona("certidaoautentica.php");
}  
db_fieldsmemory($result,0);
$dtvenc = str_replace("-","",$cerdtvenc);
$data = getdate();
$dia = $data['mday'];
$mes = $data['mon'];
$ano = $data['year'];
if (strlen($mes)=="1")
 $mes = "0".$mes;
if (strlen($dia)=="1")
 $dia = "0".$dia;
$dtat = $ano.$mes.$dia;

if(strcmp($dtat, $dtvenc)<"0"){
  if($cerweb == 't'){
    //echo pg_result($result,0,'cerhtml');
    ?>
    <html>
     <head>
      <title><?=$w01_titulo?></title>
     </head>
     <link href="config/estilos.css" rel="stylesheet" type="text/css">
    <body leftmargin="0" topmargin="50" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
    <div align="center" class="bold2">
     Certidão válida!<br><br><br>
     <span class="verde"><?=$cod?>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     </span><br>
     <center><img src="boleto/int25.php?text=<?=$cod?>"></center>
     <br><br>
     <?=$cernomecontr?><br><br>
     Vencimento: <?=db_formatar($cerdtvenc,'d')?>
    </div>
    <?
  }else{
    Header('Content-Type: application/pdf');
    header("Expires: Mon, 26 Jul 2001 05:00:00 GMT");              // Date in the pas
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modifie
    header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");                                    // HTTP/1.0
    header("Cache-control: private");
    db_fieldsmemory($result,0);
    db_query("begin");
    $loid = pg_lo_open($cercertidao, "r");
    pg_lo_read_all ($loid);
    pg_lo_close ($loid);
    db_query("commit"); //OR END
  }
}elseif(strcmp($dtat, $dtvenc)>"0"){
//passou 90 dias
  include("certidaovencida.php");
}
?>