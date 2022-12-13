<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
//$clrotulo->label('o08_reduz');
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
  obj = document.form1;
  sel_instit  = obj.db_selinstit.value;
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  dataini   = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  datafinal = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  jan = window.open('cai2_boletim002.php?status='+obj.status.value+'&registrossemmov='+obj.registrossemmov.value+'&datai='+dataini+'&dataf='+datafinal+'&db_selinstit='+sel_instit,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center" border=0>
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
        <td align="left" nowrap title="<?=@$Tk02_codigo?>" > </td>
        <td> </td>
      </tr>
      <tr>
        <td align="left" ><strong>Data Inicial :</strong></td>
        <td><? db_inputdata('datai','01','01',db_getsession("DB_anousu"),true,'text',4)?></td>
      </tr>
      
      <tr>
        <td align="rigth" ><strong>Data Final :</strong></td>
        <td><?
               $datausu = date("Y/m/d",db_getsession("DB_datausu"));
               $dataf_ano = substr($datausu,0,4);
               $dataf_mes = substr($datausu,5,2);
               $dataf_dia = substr($datausu,8,2);
            ?>
           <? db_inputdata('dataf',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4)?>
        </td>
      </tr>
      <tr>         
         <td></td>
      </tr>
      
      <tr align="rigth">
        <td><strong>Status:</strong></td>
        <td><?
              $xx = array("t"=>"Todos","p"=>"Processados","n"=>"Não processados");
              db_select('status',$xx,true,4,"");
            ?>
        </td>
      </tr>

      <tr align="rigth">
        <td><strong>Demonstrar registros sem movimento:</strong></td>
        <td><?
              $yy = array("n"=>"Nao","s"=>"Sim");
              db_select('registrossemmov',$yy,true,4,"");
            ?>
        </td>
      </tr>

      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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
function js_pesquisatabrec(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|0|3';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabrec_todas.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec';
     }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
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