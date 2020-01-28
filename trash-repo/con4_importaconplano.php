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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("dbforms/db_funcoes.php");
include("classes/db_conplanoexe_classe.php");

db_postmemory($HTTP_POST_VARS);

$clconplanoexe = new cl_conplanoexe;
$clconplanoexen = new cl_conplanoexe;

$erro = false;
$erro_msg = '';

if(isset($processar)){   
  db_inicio_transacao();
  // verificar quais contas a serem migradas cfe o que veio do http_post
  reset($HTTP_POST_VARS);
  for($i=0;$i<count($HTTP_POST_VARS);$i++){
    if(substr(key($HTTP_POST_VARS),0,9) == "importar_"){
      $codcon = split("_",key($HTTP_POST_VARS));
      $codcon = $codcon[1];
      $reduz  = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
      $sql = "insert into conplano 
              select c60_codcon, ".db_getsession("DB_anousu").",c60_estrut,c60_descr,c60_finali,c60_codsis,c60_codcla
              from conplano where c60_anousu = ".(db_getsession("DB_anousu")-1)." and c60_codcon = $codcon";

      $res = pg_exec($sql);
      if($res==false){
      	$erro = true;
      	$erro_msg ="Não incluido no conplano."; 
        break;
      }
      if($erro==false){
        $sql = "insert into conplanoconta 
                select c63_codcon , ".db_getsession("DB_anousu").", c63_banco , c63_agencia , c63_conta , c63_dvconta , c63_dvagencia
                from conplanoconta where c63_anousu = ".(db_getsession("DB_anousu")-1)." and c63_codcon = $codcon";

        $res = pg_exec($sql);
        if($res==false){
      	  $erro = true;
      	  $erro_msg ="Não incluido no conplanoconta."; 
      	  break;
        }      
      }         
      if($erro==false){
        $sql = "insert into conplanoreduz
                select  c61_codcon ,".db_getsession("DB_anousu").", c61_reduz , c61_instit , c61_codigo , c61_contrapartida
                from conplanoreduz where c61_anousu = ".(db_getsession("DB_anousu")-1)." and c61_reduz = $reduz";

        $res = pg_exec($sql);
        if($res==false){
      	  $erro = true;
      	  $erro_msg ="Não incluido no conplanoreduz."; 
      	  break;
        }      
      }         
      if($erro==false){
        $sql = "insert into conplanoexe
                select ".db_getsession("DB_anousu").",c62_reduz ,c62_codrec ,0 ,0
                from conplanoexe where c62_anousu = ".(db_getsession("DB_anousu")-1)." and c62_reduz = $reduz";

        $res = pg_exec($sql);
        if($res==false){
      	  $erro = true;
      	  $erro_msg ="Não incluido no conplanoexe."; 
      	  break;
        }      
      }         
      next($HTTP_POST_VARS);
    }
  }
  if($erro==false){
    $erro_msg = "Processo Concluído.";  	 
  }else{
    $erro_msg = $clconplanoexe->erro_msg;
  }
  db_fim_transacao($erro);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
    <table>
    <tr>
    <?
    if(!isset($processar)){
      echo "<td colspan='4'><br><br><strong>Importar Contas do exercício anterior: (".(db_getsession("DB_anousu")-1).")</strong></td></tr>";
      echo "<tr><td colspan='4'></td></tr>";
      $result = $clconplanoexe->sql_record($clconplanoexe->sql_descr(null,null,"*","c62_reduz"," c62_anousu = ".(db_getsession("DB_anousu")-1)));
      //db_criatabela($result);
      $montatb=true;
      if($clconplanoexe->numrows>0){
        for($i=0;$i<$clconplanoexe->numrows;$i++){
        	db_fieldsmemory($result,$i);
          $resultn = $clconplanoexen->sql_record($clconplanoexen->sql_descr(null,null,"c62_reduz as nreduz","c62_reduz"," c60_anousu = ".db_getsession("DB_anousu")." and c60_estrut = '$c60_estrut' "));
      	  if($clconplanoexen->numrows==0){
      	    if($montatb==true){
      	    	$montatb = false;
      	    	echo "<table border='0'>";
      	    }
      	    echo "<tr><td width='5%' ><input name='importar_$c60_codcon' value='$c62_reduz' type='checkbox' checked ></td><td>$c62_reduz</td><td width='30%' >$c60_estrut</td><td width='50%' >$c60_descr</td></tr>";
      	  }else{
      	    db_fieldsmemory($resultn,0);
      	    if($c62_reduz!=$nreduz){
      	      echo "<tr><td width='20%' colspan='2'>$c62_reduz -> $nreduz</td><td width='30%' >$c60_estrut</td><td width='50%' >$c60_descr</td></tr>";
      	    }
        	}
        }    
        if($montatb==false){
        	  echo "<tr><td colpan='3'><input name='processar' value='processar' type='submit' ></td></tr>";
         	echo "</table>";
        }else{
          echo "<tr><td colspan='3'>Não existem contas a serem importadas do exercício anterior para este exercício.</td></tr>";
        }    
      }else{
        echo "<tr><td colspan='3'>Não existem contas lançadas para o exercício anterior.</td></tr>";
      }   
    }
    ?>
    </table>
    </center>
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if( $erro_msg != "")
  db_msgbox($erro_msg);