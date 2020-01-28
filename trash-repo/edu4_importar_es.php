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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
//db_postmemory($HTTP_POST_VARS,2);
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Importar dados da secretaria para a escola</b></legend>
    <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table border="0">
     <tr>
      <td>
       <?db_input('arquivo',80,"",true,'file',$db_opcao,'')?><br><br>
       <input type="button" value="Importar" name="processar" onclick="js_processar();">
      </td>
     </tr>
    </table>
    <br>
    <table id ="aviso" style="visibility:hidden;">
     <tr align="center">
      <td bgcolor="#DBDBDB" style="border:2px solid #000000;text-decoration:blink;">
       <table cellpadding="5" cellspacing="2">
        <tr align="center">
         <td bgcolor="#f3f3f3" style="border:2px solid #888888;text-decoration:blink;">
          <b>Escola: <?=db_getsession("DB_coddepto")?>-<?=db_getsession("DB_nomedepto")?></b><br>
          <b>Iniciando importação dos dados...Aguarde</b>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
    <br><br>
    </form>
    <?
    if(isset($GLOBALS["_FILES"]["arquivo"]) && $GLOBALS["_FILES"]["arquivo"]!=""){
     set_time_limit(0);
     db_postmemory($GLOBALS["_FILES"]["arquivo"]);
     $caminho_psql = "/usr/local/pgsql/bin/psql"; //webseller
     //$caminho_psql = "/usr/bin/psql"; //bage
     //$caminho_psql = "/usr/bin/psql"; //guaiba
     //$caminho_psql = "/usr/bin/psql"; //tarefas/dbportal2_gua_20071105
     $baseatual = db_base_ativa(); ///DB_BASE_ATIVA();*******
     $escola = db_getsession("DB_coddepto");
     $sql = "select ed129_i_ultatualizse,ed129_c_ulttransacao
             from escola_sequencias
             where ed129_i_escola = $escola
            ";
     $result = pg_query($sql);
     $ultima_atualizacaose = trim(pg_result($result,0,'ed129_i_ultatualizse'));
     $ultima_transacao = trim(pg_result($result,0,'ed129_c_ulttransacao'));
     $array_arquivo = explode("_",$name);
     $escola_arquivo = trim($array_arquivo[0]);
     $base_arquivo = trim($array_arquivo[1]);
     $base_destino = trim($array_arquivo[1]).date("dnY").date("Hi");
     $data_arquivo = trim($array_arquivo[2]);
     $tipo_arquivo = trim(substr($array_arquivo[3],0,2));
     $nome_arquivo = $escola_arquivo."_".$base_arquivo."_".$data_arquivo."_".$tipo_arquivo;
     $arquivo_sql = $nome_arquivo.".sql";
     $arquivo_tar = $nome_arquivo.".tar";
     if($tipo_arquivo!="SE"){
      db_msgbox("Arquivo inválido!");
      db_redireciona("edu4_importar_es.php");
      exit;
     }
     if($ultima_transacao=="SE"){
      db_msgbox("Atenção! Não foi realizada exportação dos últimos dados registrados.\\nPrimeiro realize a exportação, para depois atualizar a importação!");
      db_redireciona("edu4_importar_es.php");
      exit;
     }
     if($data_arquivo<=$ultima_atualizacaose){
      db_msgbox("Arquivo já importado! Selecione um arquivo mais recente!");
      db_redireciona("edu4_importar_es.php");
      exit;
     }
     if($escola!=$escola_arquivo){
      db_msgbox("Arquivo inválido! Este arquivo pertence a outra escola!");
      db_redireciona("edu4_importar_es.php");
      exit;
     }
     if(!@copy($tmp_name,"tmp/".$name)){
      db_msgbox("ERRO copiando arquivo tmp/$name!");
      db_redireciona("edu4_importar_es.php");
      exit;
     }
     ?>
     <table>
      <tr align="center">
       <td bgcolor="#DBDBDB" style="border:2px solid #000000;text-decoration:blink;">
        <table cellpadding="5" cellspacing="2">
         <tr align="center">
          <td bgcolor="#f3f3f3" style="border:2px solid #888888;text-decoration:blink;">
           <b>Escola: <?=db_getsession("DB_coddepto")?>-<?=db_getsession("DB_nomedepto")?></b><br>
           <b>Iniciando importação dos dados...Aguarde</b>
          </td>
         </tr>
         </table>
       </td>
       </tr>
     </table>
     <br><br>
     <?
     echo "Arquivo tmp/".$name." copiado com sucesso!<br>";
     system("bunzip2 tmp/".$name);
     system("tar -xvf tmp/".$arquivo_tar);
     $sql1 = "create database $base_destino";
     $result1 = pg_query($sql1);
     if(!$result1){
      echo "Erro criando base de dados!";
      system("rm tmp/".$arquivo_tar);
      system("rm tmp/".$arquivo_sql);
      exit;
     }
     echo "<br>Iniciando criação da base de dados!<br>";
     system($caminho_psql." $base_destino -U postgres -h $DB_SERVIDOR -f tmp/".$arquivo_sql);
     //atualizar código fonte
     system("bunzip2 tmp/_educodigofonte.tar.bz2");
     echo "<br>Atualizando código fonte!<br>";
     system("tar -xvf tmp/_educodigofonte.tar");
     //apagar arquivos
     system("rm tmp/".$arquivo_tar);
     system("rm tmp/".$arquivo_sql);
     system("rm tmp/_educodigofonte.tar");
     //include("edu4_update_dbconn.php");
     //////////////////////////////////////////
     pg_close($conn);
     if(!($conn1=pg_connect("host=$DB_SERVIDOR dbname=$base_destino port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))){
      echo "[1] Erro ao conectar ...\n";
      exit;
     }
     $sql1 = "drop database $baseatual";
     $result1 = pg_query($conn1,$sql1);
     if(!$result1){
      echo "Erro apagando base de dados!";
      exit;
     }
     //////////////////////////////////////////
     db_redireciona("encerrar.php");
    }
    ?>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_processar(){
 if(document.form1.arquivo.value==""){
  alert("Informe o arquivo de importação!");
  document.form1.arquivo.focus();
 }else{
  document.getElementById("aviso").style.visibility = "visible";
  document.form1.submit();
 }
}
</script>