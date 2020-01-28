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
include("classes/db_gerfcom_classe.php");
$clrotulo = new rotulocampo;
$clgerfcom = new cl_gerfcom;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
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
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  qry = "";
  if(document.form1.r48_semest){
    qry = "&semest="+document.form1.r48_semest.value;
  }
  qry += '&db_desp_ext='+document.form1.db_desp_ext.value;
  qry += '&db_ded_rec='+document.form1.db_ded_rec.value;
	qry += '&db_rec='+document.form1.db_rec.value;
	qry += '&ano='+document.form1.ano.value;
	qry += '&folha='+document.form1.folha.value;
	qry += '&mes='+document.form1.mes.value;

  jan = window.open('pes2_sapconfcaixa002.php?'+qry,
					    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
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
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           if(!isset($ano)){
             $ano = db_anofolha();
           }
	         $anosqlcom = $ano;
           db_input('ano',4,$ano,true,'text',2,'');
          ?>
          &nbsp;/&nbsp;
          <?
           if(!isset($mes)){
             $mes = db_mesfolha();
           }
	         $messqlcom = $mes;
           db_input('mes',2,$mes,true,'text',2,'');
          ?>
        </td>
      </tr>
      <tr>
        <td><strong>Tipo de Folha :</strong>
        </td>
        <td>
          <?
          $arr_folha = array("r14"=>"Salario",
                             "r48"=>"Complementar", 
                             "r35"=>"13o. Salario", 
                             "r22"=>"Adiantamento");
          db_select('folha',$arr_folha,true,4,"onchange='document.form1.submit();'");
          ?>
       </td>
     </tr>
     <?
     if(isset($folha) && $folha == "r48"){
//       echo ($clgerfcom->sql_query_file($anosqlcom,$messqlcom,null,null,"distinct r48_semest"));
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($anosqlcom,$messqlcom,null,null,"distinct r48_semest"));
       if($clgerfcom->numrows > 0){
	 echo "
	  <tr>
	    <td align='left' title='Nro. Complementar'><strong>Nro. Complementar:</strong></td>
            <td>
	      <select name='r48_semest'>
		<option value = '0'>Todos
	      ";
	      for($i=0; $i<$clgerfcom->numrows; $i++){
		db_fieldsmemory($result_semest, $i);
		echo "<option value = '$r48_semest'>$r48_semest";
	      }
	 echo "
	    </td>
	  </tr>
	      ";
       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
     </tr>
      <tr>
        <td align="left" nowrap  >
        <strong>Receitas :&nbsp;&nbsp;</strong>
        </td>
      <td>
      <?
      $db_rec = "1030-1250-1252-1350-1360-1400-1501-1502-1503-1506-1508-1600-1602-1700-1705-1710-1715-1720-1730-1755-R907-R901-R904-R913-R916-R903-R906-R915-3400";
      db_textarea('db_rec',1,90,$db_rec,true,'text',2,"");
      ?>
      </td>
      </tr>
      <tr>
        <td align="left" nowrap  >
        <strong>Deduções das Receitas :&nbsp;&nbsp;</strong>
        </td>
      <td>
      <?
      $db_ded_rec = '0501-0505-0510-0532';
      db_textarea('db_ded_rec',1,90,$db_ded_rec,true,'text',2,"");
      ?>
      </td>
      <tr>
        <td align="left" nowrap  >
        <strong>Despesas Extras :&nbsp;&nbsp;</strong>
        </td>
      <td>
      <?
      $db_desp_ext = 'R919-0255-';
      db_textarea('db_desp_ext',1,90,$db_desp_ext,true,'text',2,"");
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
<script>
function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}
</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>