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
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona_grupo    = new cl_iframe_seleciona;
$cliframe_seleciona_subgrupo = new cl_iframe_seleciona;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table align="center">
    <form name="form2" method="post" action="">
      <?
         db_input("pc03_codgrupo",100,"",false,"hidden",4);
      ?>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td><?
	   $sql_marca = "";
	   if (isset($pc03_codgrupo) && trim($pc03_codgrupo)!=""){
	        $sql_marca = "select pc03_codgrupo, pc03_descrgrupo 
	                      from pcgrupo 
		              where pc03_codgrupo in ($pc03_codgrupo) --and pc03_ativo = 't'
		              order by pc03_codgrupo";
	   }

	   $sql = "select pc03_codgrupo, pc03_descrgrupo 
	           from pcgrupo 
		   --where pc03_ativo = 't'
		   order by pc03_codgrupo";

           $cliframe_seleciona_grupo->campos  = "pc03_codgrupo,pc03_descrgrupo";
           $cliframe_seleciona_grupo->legenda="Grupos";
           $cliframe_seleciona_grupo->sql=$sql;	   
           $cliframe_seleciona_grupo->sql_marca=$sql_marca;
           $cliframe_seleciona_grupo->iframe_height ="250";
           $cliframe_seleciona_grupo->iframe_width ="380";
           $cliframe_seleciona_grupo->iframe_nome ="grupos"; 
           $cliframe_seleciona_grupo->chaves ="pc03_codgrupo";
	   $cliframe_seleciona_grupo->js_marcador = "parent.js_enviar()";
	   $cliframe_seleciona_grupo->dbscript = "onClick='parent.js_enviar();'";
           $cliframe_seleciona_grupo->iframe_seleciona(4);    
         ?>
       </td>
        <td><?
	   if (isset($pc03_codgrupo) && trim($pc03_codgrupo)!=""){
            	$sql       = "select pc04_codsubgrupo, pc04_descrsubgrupo 
	                      from pcsubgrupo 
	  	              where pc04_codgrupo in ($pc03_codgrupo) and pc04_ativo = 't'
		              order by pc04_codsubgrupo";

            	$sql_marca = "select pc04_codsubgrupo, pc04_descrsubgrupo 
	                      from pcsubgrupo 
	  	              where pc04_codgrupo in ($pc03_codgrupo) and pc04_ativo = 't'
		              order by pc04_codsubgrupo";
           
	        $cliframe_seleciona_subgrupo->campos  = "pc04_codsubgrupo,pc04_descrsubgrupo";
                $cliframe_seleciona_subgrupo->legenda="Subgrupos";
                $cliframe_seleciona_subgrupo->sql=$sql;	   
                $cliframe_seleciona_subgrupo->sql_marca=$sql_marca;
                $cliframe_seleciona_subgrupo->iframe_height ="250";
                $cliframe_seleciona_subgrupo->iframe_width ="380";
                $cliframe_seleciona_subgrupo->iframe_nome ="subgrupos"; 
                $cliframe_seleciona_subgrupo->chaves ="pc04_codsubgrupo";
                $cliframe_seleciona_subgrupo->iframe_seleciona(4);    
	   }
        	?>
       </td>
      </tr>
      <?
	   if (isset($pc03_codgrupo) && trim($pc03_codgrupo)!=""){
      ?>
      <tr>
        <td colspan="2" align = "center" height="50"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
        </td>
      </tr>
      <?
           }
      ?>
  </form>
    </table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_enviar(){
   var tabela = grupos.document.getElementById('tabela_seleciona');

   var coluna = "";
   var sep    = ""; 

   for(i=1; i < tabela.rows.length; i++){
        id = tabela.rows[i].id.substr(6);
        if(grupos.document.getElementById("CHECK_"+id).checked==true){
	    coluna+=sep;
	    colu    = '' + grupos.document.getElementById("pc03_codgrupo_"+i).innerHTML;
            coluna += colu.replace("&nbsp;","");
            sep = ","; 
	}
   }

   document.form2.pc03_codgrupo.value = coluna;
   document.form2.submit(); 
}
function js_mandadados(){
 
 var query         = "";
 var listasubgrupo = "";
 var tabela        = subgrupos.document.getElementById('tabela_seleciona');

 var coluna        = "";
 var sep           = ""; 

 for(i=1; i < tabela.rows.length; i++){
      id = tabela.rows[i].id.substr(6);
      if(subgrupos.document.getElementById('CHECK_'+id).checked==true){
          listasubgrupo += sep;
          colu           = '' + subgrupos.document.getElementById('pc04_codsubgrupo_'+i).innerHTML;
          listasubgrupo += colu.replace('&nbsp;','');
          sep = ','; 
      }
 }

 if (listasubgrupo == ""){
      alert("Informe pelo menos um Subgrupo.");
      return false;
 }

 query +='&listasubgrupo='+listasubgrupo;
 
 jan = window.open('mat2_relestoqatual002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}
</script>