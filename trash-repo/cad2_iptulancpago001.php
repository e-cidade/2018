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
include("classes/db_iptucalc_classe.php");
$cliptucalc = new cl_iptucalc;
$cliptucalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j23_anousu");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
 
function js_emite(){
  vir="";
  listazona="";
  for(x=0;x<document.form1.ssel1.length;x++){
    listazona+=vir+document.form1.ssel1.options[x].value;
    vir=",";
  }
  
 // descomentar se for utilizar o filtro por valor 
//  if(document.form1.valor_ini.value=="" && document.form1.valor_fim.value==""){
//  	document.form1.valor_ini.value = 0;
// 	document.form1.valor_fim.value = 99999999999;
//  }
//  js_OpenJanelaIframe('','db_iframe_relatorio','cad2_iptulancpago002.php?anousu='+document.form1.anousu.value+'&zona='+listazona+'&valor_ini='+document.form1.valor_ini.value+'&valor_fim='+document.form1.valor_fim.value,'',true);
  js_OpenJanelaIframe('','db_iframe_relatorio','cad2_iptulancpago002.php?anousu='+document.form1.anousu.value+'&zona='+listazona+'&considerar='+document.form1.considerar.value,'',true);
  //  js_OpenJanelaIframe('','db_iframe_relatorio','cad2_iptulancpago002.php?anousu='+document.form1.anousu.value+'&zona='+listazona,'',true);
}
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

  <table border="0" width="600" align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

      <tr>
        <td ><b>Exercicio:</b></td>
        <td >
        <select name="anousu" >
			  	<?
			  	$sqlano = "select distinct j23_anousu from iptucalc order by j23_anousu desc";
                $resultano = pg_query($sqlano);
                $linhasano = pg_num_rows($resultano);
                for($i = 0;$i < $linhasano;$i++){
	              db_fieldsmemory($resultano,$i);
	              echo "<option value=$j23_anousu>$j23_anousu</option>\n";
	            }
	            ?>
        </select>
             
        </td>
      </tr>
      
			<?
			if (1==2) {
			?>
      <tr>
         <td ><b>Faixa de valor lançado:</b></td>
         <td > 
         <?
         //usado $Iz01_numcg somente para validação
         $valor_ini=0;
         $valor_fim=99999999999;
         db_input("valor_ini", 15, "$Ij23_anousu", true, 'text', 1,"","","","");
         ?>
         até
         <?
         db_input("valor_fim", 15, "$Ij23_anousu", true, 'text', 1,"","","","");
         ?></td>
      </tr> 
			<?
			}
			?>



						<tr>
							<td> <b>Considerar:&nbsp;</b> </td>
							<td> 
							 <?
									$x = array("a"=>"Ambos","p"=>"Predial","t"=>"Territorial");
									db_select("considerar",$x,false,2,"");
								?>
						  </td>
            </tr>





      <tr>
         <td colspan ="2" ><b>Zona fiscal:</b></td>
      </tr>
      <tr>
         <td colspan ="2" >
         	<?
			  	$sqlzona    = "select * from zonas";
                $resultzona = pg_query($sqlzona);
                db_multiploselect("j50_zona", "j50_descr", "nsel1", "ssel1", $resultzona, array(), 4, 250);
	       ?> 
         </td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <br><input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>