<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
$clrotulo->label('o08_reduz');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_verifica() {
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if (anoi.valueOf() > anof.valueOf()) {
    
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite() {
  
  jan = window.open('cai2_relreceitas002.php?tipo='+document.form1.tipo.value+'&estrutural='+document.form1.o08_reduz.value+'&agrupar='+document.form1.agrupar.value+'&datai='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value+'&dataf='+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value+'&codrec='+document.form1.k02_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>
<div style="margin-top: 25px; width: 510px">
  <form name="form1" method="post" action="" onsubmit="return js_verifica();">
  <fieldset>
    <legend><strong>Relatório por período</strong></legend>
    <table  align="center">
      <tr >
        <td align="left" nowrap title="<?=@$Tk02_codigo?>" >
          <?
             db_ancora(@$Lk02_codigo,"js_pesquisatabrec(true);",4)
          ?>
        </td>
        <td nowrap="nowrap">
          <?
            db_input('k02_codigo', 10, $Ik02_codigo, true, 'text', 4, "onchange='js_pesquisatabrec(false);'");
            db_input('k02_drecei', 35, $Ik02_drecei, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Data Inicial :</strong></td>
        <td>
          <?=db_inputdata('datai','01','01',db_getsession("DB_anousu"),true,'text',4)?>
        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Data Final :</strong></td>
        <td>
          <?
           $datausu = date("Y/m/d",db_getsession("DB_datausu"));
           $dataf_ano = substr($datausu,0,4);
           $dataf_mes = substr($datausu,5,2);
           $dataf_dia = substr($datausu,8,2);
          ?>
          <?=db_inputdata('dataf',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4)?>
        </td>
      </tr>
      <tr>
        <td><strong>Agrupar por Receita:</strong> 
	      </td>
        <td>
          <select name="agrupar" onchange="js_valor();" style="width: 95px;">
            <option value = 'f'>Não
            <option value = 't'>Sim
        </td>
      </tr>
      <tr>
         <td height="25" nowrap title="<?=$To08_reduz?>"><?=$Lo08_reduz?></td>
         <td >
           <?
            db_input('o08_reduz',10,$Io08_reduz,true,'text',2,'')
           ?>
         </td>
      </tr>
      <tr >
        <td><strong>Tipo</strong></td>
        <td>
          <?
            $xx = array("g"=>"Geral","o"=>"Orçamentária","e"=>"Extra-Orçamentária");
            db_select('tipo',$xx,true,4,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
  </form>
</div>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisatabrec(mostra) {
  
  if (mostra) {
    
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec',
                        'func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei',
                        'Pesquisa',true);
  } else {
    
    if (document.form1.k02_codigo.value != '') {
      
    	js_OpenJanelaIframe('top.corpo',
                          'db_iframe_tabrec',
                          'func_tabrec_todas.php?pesquisa_chave='+$F('k02_codigo')+'&funcao_js=parent.js_mostratabrec',
                          'Pesquisa',false);
    } else {
      document.form1.k02_codigo.value = ''; 
    }
  }
}
function js_mostratabrec(chave,erro) {
  
  document.form1.k02_drecei.value = chave;
  if (erro) {
    document.form1.k02_codigo.focus();
    document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2) {
  
  document.form1.k02_codigo.value = chave1;
  document.form1.k02_drecei.value = chave2;
  db_iframe_tabrec.hide();
}
</script>
<?
if(isset($ordem)) {
  echo "<script>
       js_emite();
       </script>";  
}
?>