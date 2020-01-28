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
include("classes/db_protprocesso_classe.php");
include("classes/db_procvar_classe.php");
include("classes/db_proctipovar_classe.php");
include("classes/db_db_syscampo_classe.php");
include("dbforms/db_funcoes.php");
$clprotprocesso = new cl_protprocesso;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
   function js_processo(valor){
      document.form2.p58_codproc.value=valor;
      document.form2.submit();
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
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form method="post" name="form2" action="pro4_mandespacho002.php">
    <table>
    <tr>
       <td title="<?=$Tp58_codproc?>">
           <?=$Lp58_codproc;?>
       </td>
       <td>
          <?db_input("p58_codproc",5,$Ip58_codproc,true,"text",2);?>
       </td>
       <td> <input type="button" value="Pesquisar" onclick="document.form2.submit()">
    </tr>
    </table>
    </form>
    <?
    $sql = "select * from (
            select      p58_codproc,
                        p58_requer,
                        z01_nome,
                        p61_id_usuario,
                        arqproc.p68_codproc
                    from   protprocesso
                        inner join cgm                  on p58_numcgm                   = z01_numcgm
                        inner join procandam            on p58_codandam                 = p61_codandam
                        left join arqproc               on arqproc.p68_codproc          = protprocesso.p58_codproc
                where ( p61_coddepto = ".db_getsession("DB_coddepto").")) as x
                where   x.p68_codproc is null"; 
/*     $sql = "select p58_codproc,p58_dtproc,p58_requer,z01_nome 
             from   protprocesso 
                    inner join procandam on p58_codandam = p61_codandam 
                    inner join cgm on p58_numcgm = z01_numcgm
             where  (p61_id_usuario = ".db_getsession("DB_id_usuario")." 
             or     p61_coddepto = ".db_getsession("DB_coddepto").") 
             and    p58_codproc not in (select p68_codproc from arqproc)";

echo $sql;
exit;*/
    db_lovrot($sql,20,"()","","js_processo|p58_codproc");

  ?>
 </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>