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
include("classes/db_proctransfer_classe.php");
include("classes/db_proctransferproc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Cancelar"){
  db_inicio_transacao();
  $sql = "select p62_codtran,nome,descrdepto
          from   proctransfer inner join db_depart on p62_coddeptorec = coddepto 
                 left outer join db_usuarios on p62_id_usorec = id_usuario
          where  (p62_coddepto = ".db_getsession("DB_coddepto")." ) 
          and    p62_codtran not in (select p64_codtran from proctransand)
          and    p62_codtran = $txtcodtran";
  $rs1 = pg_exec($sql);
  if (pg_num_rows($rs1) > 0){       
     $sql1 = "delete from proctransferproc where p63_codtran = $txtcodtran";
     $rs1  = pg_exec($sql1);
     $sql2 = "delete from proctransfer where p62_codtran = $txtcodtran"; 
     $rs2  = pg_exec($sql2);
     if (!$rs2 or !$rs1){
        pg_exec("ROLLBACK");
     }else{
       echo "<script>alert('Transferencia excluida com sucesso!');</script>";
     } 
  }else{
    echo "<script>alert('A tranferencia foi recebida ou já excluída');</script>";
  }   
  db_fim_transacao();
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
.dono {background-color:#FFFFFF;
       color:red 
      }
      
</style>
<script>
 function valida(form){
   if (confirm ('A transferência sera Excluída...deseja excluir?')==true){
      return true;
   }else{
     return false;
   } 
 
}
   function envia(processo){
       window.db_iframe.jan.location.href='pro4_mostraprocesso.php?codtran='+processo;
       document.form1.txtcodtran.value = processo;
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
 }

 </script>
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <form method="post" action="" name="form1" onsubmit="return valida(this)">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <table>
        <tr>
           <td><b>Código da Transferencia</b></td>
           <td><input type="text" readonly name="txtcodtran" size=20></td>
           <td><input type="submit" name="db_opcao" value="Cancelar"></td>
     </form>
       </tr>
       </table>
       <?
         $sql = "select p62_codtran,nome,descrdepto
                 from   proctransfer inner join db_depart on p62_coddeptorec = coddepto 
                        left outer join db_usuarios on p62_id_usorec = id_usuario
                 where  (p62_coddepto = ".db_getsession("DB_coddepto")." 
                 or     p62_id_usuario = ".db_getsession("DB_id_usuario").") 
                 and    p62_codtran not in (select p64_codtran from proctransand)";
	   //echo $sql;
        db_lovrot($sql,10,"()","","envia|p62_codtran");
       ?>
    </center>
	</td> 
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=750;
$func_iframe->altura=400;
$func_iframe->titulo='Processos da transferência';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
</body>
</html>