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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verifica(){
  var val1 = new Number(document.form1.DBtxt10.value);
  var val2 = new Number(document.form1.DBtxt11.value);
  if(val1.valueOf() >= val2.valueOf()){
    alert('Valor máximo menor que o valor mínimo.');
    return false;
  } 
  return true;
}

</script>

<?
if(isset($ordem)){
?>
<script>

function js_emite(){
  jan = window.open('cai2_devedores1_002.php?ordemtipo=<?=$ordemtipo?>&data=<?=$data_ano.'-'.$data_mes.'-'.$data_dia?>&ordem=<?=$ordem?>&numerolista=<?=$numerolista2?>&valormaximo=<?=$DBtxt11?>&valorminimo=<?=$DBtxt10?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<?
}
?>
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
        <td><font size="3"><strong>Data:</strong></td>
        <td>
        <?=db_inputdata('data','','','',true,'text',4)?>
        </td>
      </tr>
      <tr>    
     <tr>
        <td colspan=2><hr></td>
     
     </tr>	
         <td width="200"><font size="3"><strong>Filtro:</strong></font>
         </td>
      </tr>
      <tr> 
        <td>Número a Listar:</td> 
        <td> 
          <input name="numerolista2" type="text" id="numerolista22" size="12">
        </td>
        
      </tr>
     <tr>
        <td colspan=2><hr>
	</td>
     </tr>	
      <tr> 
        <td height="23"><font size="3"><strong>Débitos Entre:</strong></font></td>
      </tr>
      <tr> 
        <td width="46%"><font size="2">De: 
          <?
	    db_input('DBtxt10',15,$IDBtxt10,true,'text',2);
	  ?>
        </font>
        </td>
        <td width="54%"><font size="2">Até 
          <?
	    db_input('DBtxt11',15,$IDBtxt11,true,'text',2);
	  ?>
        </font>
        </td>
      </tr>
     <tr>
        <td colspan=2><hr>
	</td>
      </tr>	
      <tr> 
        <td><font size="3"><strong>Ordem:</strong></font> </td>
      </tr>
      <tr> 
        <td id="radios"><font size="2"> 
          <label for="ordem_valor1" id="lordem1"><input id="ordem_valor1" type="radio" name="ordem" value="z01_nome">Alfabética&nbsp;&nbsp;</label>
          <label for="ordem_valor3" id="lordem4"><input id="ordem_valor4" type="radio" name="ordem" value="numcgm">Numérica&nbsp;&nbsp;</label>
          <label for="ordem_valor"  id='lordem3'><input type="radio" id="ordem_valor" name="ordem" value="valor" checked>Valor&nbsp;&nbsp;</label>
	  </font>
        </td>
      </tr>
      <tr>
         <td colspan="2">&nbsp;
	 </td>
      </tr>
      <tr> 
        <td width="50%" height="18"><font size="2"> 
          <input type="radio" name="ordemtipo" value="asc">Ascendente</font> 
        </td>
        <td width="50%"><font size="2"> 
          <input type="radio" name="ordemtipo" value="desc" checked>Descendente</font> 
        </td>
      </tr>
      <tr>
         <td colspan="2">&nbsp;
	 </td>
      </tr>
      <tr>
         <td align="center" colspan="2"> 
           <input  name="emite2" id="emite2" type="submit" value="Processar">
	 </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
?>