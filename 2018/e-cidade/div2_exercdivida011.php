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
include("classes/db_proced_classe.php");

$clproced = new cl_proced;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_marca() {
   var ID = document.getElementById('marca');
  //var BT = document.getElementById('btmarca');
  if(!ID)
     return false;
     
  if(ID.innerHTML == 'D') {
     var dis = false;
     ID.innerHTML = 'M';
  } else {
     var dis = true;
     ID.innerHTML = 'D';
  }
  for(i = 0;i < document.form1.elements.length;i++) {
     if(document.form1.elements[i].type == "checkbox"){
        document.form1.elements[i].checked = dis;
     }
  }
  js_verifica();
}

function js_verifica(){
  var marcas = false;
  for(i = 0;i < document.form1.elements.length;i++) {
     if(document.form1.elements[i].type == "checkbox" &&  document.form1.elements[i].checked ){
        marcas = true;
     }
  }
}


function js_emite(){
  var exerc = '';
  var xvirg = '';
  for(i = 0;i < document.form1.elements.length;i++) {
     if(document.form1.elements[i].type == "checkbox" &&  document.form1.elements[i].checked ){
        exerc += xvirg+document.form1.elements[i].value;
	xvirg  = '-';
     }
  }
  if (exerc != ''){
    exerc = 'exerc='+exerc;
  } 


   if (exerc == ''){
     alert('Nenhum exercicio foi selecionado.Verifique!');
     return false
   }
  
 vir="";
 listaproced="";
 for(x=0;x<parent.iframe_g2.document.form1.procedencias.length;x++){
  listaproced+=vir+parent.iframe_g2.document.form1.procedencias.options[x].value;
  vir=",";
 }
 vir="";
 listacgm="";
 for(x=0;x<parent.iframe_g3.document.form1.cgm.length;x++){
  listacgm+=vir+parent.iframe_g3.document.form1.cgm.options[x].value;
  vir=",";
 }
 vir="";
 listainscr="";
 for(x=0;x<parent.iframe_g4.document.form1.inscricao.length;x++){
  listainscr+=vir+parent.iframe_g4.document.form1.inscricao.options[x].value;
  vir=",";
 }
 vir="";
 listamatric="";
 for(x=0;x<parent.iframe_g5.document.form1.matricula.length;x++){
  listamatric+=vir+parent.iframe_g5.document.form1.matricula.options[x].value;
  vir=",";
 }
 
 query="listaproced="+listaproced+"&listacgm="+listacgm+"&listainscr="+listainscr+"&listamatric="+listamatric+"&verp="+parent.iframe_g2.document.form1.ver.value+"&verc="+parent.iframe_g3.document.form1.ver.value+"&veri="+parent.iframe_g4.document.form1.ver.value+"&verm="+parent.iframe_g5.document.form1.ver.value;
  
    
 jan = window.open('div2_exercdivida022.php?'+query+'&'+exerc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table><form  name="form1" method="post" action="" >
         <table  align="center" border="0" cellspacing="1" >
          <tr>
            <td colspan="3" align="center"><strong>Exercícios</strong>
	    </td>
	  </tr>
          <tr height="20" bgcolor="#FFCC66">
             <th class="borda" align="center" style="font-size:12px" nowrap><a id="marca" href="#" style="color:black" onclick="js_marca();return false">D</a></th>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
          <tr>
         </tr>
         <?
	 $cor = '#E4F471';
	 $exercicio = db_getsession("DB_anousu") - 15;
	 for($x = 0;$x < 5;$x++){
            if ($cor == '#E4F471'){
                $cor = '#EFE029';
            }elseif ($cor == '#EFE029'){
                $cor = '#E4F471';
            }								    
	 ?>
         <tr style="cursor: hand; height: 20px" bgcolor="<?=$cor?>">
           <td height="20px" width="33%" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
              <input type="checkbox" value="<?=$x+1+$exercicio?>"  name="check<?=$i?>" checked onclick="js_verifica()"><?=$x+1+$exercicio?>
           </td>
           <td height="20px" width="33%" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
              <input type="checkbox" value="<?=$x+6+$exercicio?>"  name="check<?=$i?>" checked onclick="js_verifica1()"><?=$x+6+$exercicio?>
           </td>
           <td height="20px" width="33%" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
              <input type="checkbox" value="<?=$x+11+$exercicio?>"  name="check<?=$i?>" checked onclick="js_verifica2()"><?=$x+11+$exercicio?>
           </td>
	 </tr>						   
	 <?
	 }
	 ?>
	 
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>


      <tr>
        <td colspan="4" align = "center"> 
         <input name="db_opcao" type="button" id="db_opcao" value="Imprimir" onClick="js_emite();">
        </td>
      </tr>

  
    </table>
  </form>
<?
  //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>