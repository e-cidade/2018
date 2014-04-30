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
include("dbforms/db_funcoes.php");
include("classes/db_editalserv_classe.php");
include("classes/db_contlot_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_editalrua_classe.php");
$cleditalserv = new cl_editalserv;
$cleditalrua = new cl_editalrua;
$clcontlot = new cl_contlot;
$cliptubase = new cl_iptubase;
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("d04_tipos");
$clrotulo->label("d03_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_opcao = 1;
   if(isset($d04_tipos) && $d04_tipos!=""){
     $result01 = $cleditalserv->sql_record($cleditalserv->sql_query("",$d04_tipos,"d04_contri as d02_contri,d03_descr"));
     $numrows01=$cleditalserv->numrows;
     if($numrows01>0){
    	 db_fieldsmemory($result01,0);
     }else{
         db_redireciona("con3_conscontri001.php?tipos=false&d04_tipos=$d04_tipos"); 
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
  <script>
  function js_consultar(){
    obj=document.form1;
    if(obj.j01_matric.value!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe','con3_conscontri011.php?contri='+obj.d02_contri.value+'&cod_matricula='+obj.j01_matric.value,'Pesquisa',true);
    }else{
      alert("Selecione a matrícula.");
    }  
  }  
  function js_voltar(){
    location.href="con3_conscontri001.php";  
  }
  </script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	  <br>
      <center>
      <form name="form1" method="post" action="">
      <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td nowrap title="<?=@$Td02_contri?>">
      <?=$Ld02_contri?>
      </td>
      <td> 
  <?
  if(isset($contribs)){
    $d02_contri=$contribs;
    $j01_matric="";
    $z01_nome="";
  } 
  db_input('d02_contri',8,$Id02_contri,true,'text',3);
  ?>
      </td>
    </tr>
        <tr> 
          <td>     
<?=$Lj01_matric?>
          </td>
	  <td>
<?
  db_input('j01_matric',8,$Ij01_matric,true,'text',3);
  db_input('z01_nome',50,$Iz01_nome,true,'text',3);
?>
          </td>
        </tr>
    <tr>
      <td colspan="2" align="left" valign="top" >
      <?
       db_input("d04_tipos",6,$Id04_tipos,true,'hidden',$db_opcao)
      ?>
      <br>
      <?
       $legenda="Contribuições do serviço $d03_descr";
       include("con3_conscontri008.php");
       ?> 
      <br>
      </td>
    <tr>
      <tr>
        <td colspan="2"   height="25" align="center">
  <?
  $consultar="Consultar";
  db_input("consultar",6,0,true,'button',$db_opcao,"onClick='js_consultar();'");
  $voltar="Voltar";
  db_input("voltar",6,0,true,'button',$db_opcao,"onClick='js_voltar();'");
  ?>
	</td>
      </tr>
    </table>
    </form>
    </center>
    </td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>