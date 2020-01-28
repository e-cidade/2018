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
include("libs/db_liborcamento.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($_POST);
db_postmemory($_GET);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){
  sel_instit  = document.form1.db_selinstit.value;
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }else{
    obj = document.form1;
    mesfin = obj.mesfin.value;
    if (mesfin==0){
       mesfin=12;
    }
    jan = window.open('con2_anexo15lrf_002.php?&mesini='+obj.mesini.value+'&mesfin='+mesfin+'&db_selinstit='+sel_instit+"&iCodRel="+<?=$codrel?>,'safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<!--<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>-->

  <table  align="center">
    <form name="form1" method="post" action="con2_anexo15lrf_002.php" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="3">
	<?
	db_selinstit('',300,100);
	?>
	</td>
      </tr>
      <tr>
        <td align="center">
         <table border="0" width="220"  height="100" style="border: 1px solid black" cellpadding="0" cellspacing="1" >
       <tr>
         <td align="center" colspan="2" title="Gera o saldo em um intervalo de meses"><strong>Saldo Por Mês</strong></td>
        </tr>
        <tr>
        <td align="right" ><strong>Mês Início :</strong> </td>
        <td>
         <script>
          function js_criames(obj){
             for(i=1;i<document.form1.mesfin.length;i){
               document.form1.mesfin.options[i] = null;
             }
             var dth = new Date(<?=db_getsession('DB_anousu')?>,document.form1.mesini.value,1);
             var nummes = dth.getMonth();
             if(nummes == 0){
	       nummes = 12;
	     }
             var teste = 0;
             for(j=nummes;j<13;j++){
	       if(teste > 12){
	         teste = 1;
	       }
               var dt = new Date(<?=db_getsession("DB_anousu")?>,j,1);
               document.form1.mesfin.options[teste] = new Option(db_mes(j),dt.getMonth());
	       teste += 1;
             }
             document.form1.mesfin.options[0].selected = true;
           }
          </script>
              <?
	      $result1=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
              db_select("mesini",$result1,true,2,'onchange="js_criames(this)"',"","","","");
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" ><strong>Mês Fim :</strong></td>
            <td>
             <select  name="mesfin" id="mes" >
                 <option value="mes">Mês Final</option>
               <script>
                   js_criames(document.form1.mesini);
               </script>
             </select>
            </td>
           </tr>
	  </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;
	</td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
<?
  //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>