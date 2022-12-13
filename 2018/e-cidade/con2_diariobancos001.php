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
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
  }

  var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
  var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
  perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
  perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;

  jan = window.open('con2_diariobancos002.php?&db_selinstit='+document.form1.db_selinstit.value+'&movimento='+document.form1.movimento.value+'&tipo='+document.form1.tipo.value+'&perini='+perini+'&perfin='+perfin,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
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

<table  align="center">
  <form name="form1" method="post" action="" onsubmit="return js_verifica();">
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
       <td >&nbsp;</td>
       <td >&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
        <table border="0"  width="250" height="100" style="border: 1px solid black" cellpadding="0" cellspacing="1" >
         <tr>
           <td align="center" colspan="2" title="Gera o saldo em um intervalo de datas"><strong>Saldo Por Datas</strong></td>
         </tr>
         <tr>
           <td nowrap align="right" title="<?=$TDBtxt21?>">
             <?=$LDBtxt21?>
           </td>
           <td>
	   <?
             $DBtxt21_ano = db_getsession("DB_anousu");
             $DBtxt21_mes = '01';
             $DBtxt21_dia = '01';
             db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4);
	   ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=$TDBtxt22?>">
          <?=$LDBtxt22?>
        </td>
        <td>
	  <?
            $DBtxt22_ano = date("Y",db_getsession("DB_datausu"));
            $DBtxt22_mes = date("m",db_getsession("DB_datausu"));
            $DBtxt22_dia = date("d",db_getsession("DB_datausu"));
            db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano ,true,'text',4);
	  ?>
        </td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
       <td >&nbsp;</td>
       <td >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align = "center"><strong>Tipo : </strong>
        <?
       	  $result1=array("A"=>"Anal�tico","S"=>"Sint�tico");
	  db_select("tipo",$result1,true,2);
	?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align = "center"><strong>Somente contas com Movimento : </strong>
        <?
       	  $result2=array("S"=>"SIM","N"=>"N�O");
	  db_select("movimento",$result2,true,2);
	?>
      </td>
    </tr>
    <tr>
       <td >&nbsp;</td>
       <td >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align = "center">
        <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
      </td>
    </tr>
  </form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>