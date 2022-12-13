<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_proctransferproc_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clproctransfer     = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$db_opcao           = 1;
$db_botao           = true;
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
function envia(valor){
  
      document.form1.txtcodtran.value = valor;
      
  if (valor != ""){
      
      url = 'pro4_termorecebimento.php?codtran=' + valor;
      window.open(url,'Termo de Recebimento','location=0');
  }else{
     alert('O número da Transferencia deve ser Preenchido!');
  }
}
 </script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 25px;" >

<center>
<fieldset style="width: 700px;">
<legend><strong>Reimpressão do Termo de Transferência</strong></legend>
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <form method="post" action="" name="form1">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <table align="center">
        <tr>
           <td><b>Código da Transferencia</b></td>
           <td><input type="text" name="txtcodtran" size=20></td>
           <td><input type="button" name="db_opcao" value="Imprimir" onclick="envia(document.form1.txtcodtran.value)"></td>
     </form>
       </tr>
       </table>
       <?php
         $sql = "select p62_codtran,nome,descrdepto
                 from   proctransfer inner join db_depart on p62_coddeptorec = coddepto 
                        left outer join db_usuarios on p62_id_usorec = id_usuario
			left join proctransand on p62_codtran = p64_codtran
                 where  (p62_coddepto = ".db_getsession("DB_coddepto")." 
                 or     p62_id_usuario = ".db_getsession("DB_id_usuario").") 
                 and    p64_codtran is null";
        db_lovrot($sql,10,"()","","envia|p62_codtran");
       ?>
    </center>
	</td> 
  </tr>
</table>
</fieldset>
</center>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>