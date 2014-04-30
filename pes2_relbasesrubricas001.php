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
   if(document.form1.tipo.value == 'b' && document.form1.base01.value == ''){
     alert('Se a opção escolhida foi \'Base em Fórmulas\', você tem que escolher uma Base.');
     return false;

   }
   qry  =     '?ativos='+document.form1.ativos.value;
   qry +=        '&ano='+document.form1.DBtxt23.value;
	 qry +=       '&base='+document.form1.base01.value;
	 qry +=       '&tipo='+document.form1.tipo.value;
	 qry += '&descr_base='+document.form1.descr_base01.value;
   qry +=        '&mes='+document.form1.DBtxt25.value;
   jan = window.open('pes2_relbasesrubricas002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}


function js_pesquisabase01(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase011|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base01.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base01','func_bases.php?pesquisa_chave='+document.form1.base01.value+'&funcao_js=parent.js_mostrabase01','Pesquisa',false);
    }else{
      document.form1.descr_base01.value = ''; 
    }
  }
}
function js_mostrabase01(chave,erro){
  document.form1.descr_base01.value = chave; 
  if(erro==true){ 
    document.form1.base01.focus(); 
    document.form1.base01.value = ''; 
  }
}
function js_mostrabase011(chave1,chave2){
  document.form1.base01.value = chave1;
  document.form1.descr_base01.value = chave2;
  db_iframe_bases.hide();
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
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="Digite a base desejada ou deixe em branco para todas." ><b>
          <?
          db_ancora('Base :',"js_pesquisabase01(true)",@$db_opcao);
          ?>
	      &nbsp;</b>
        </td>
        <td nowrap> 
          <?
          db_input('base01',4,@$base01,true,'text',@$db_opcao,"onchange='js_pesquisabase01(false)'");
          db_input("r08_descr",50,@$Ir08_descr,true,"text",3,"","descr_base01");
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" ><strong>Tipo :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_tipo = array("r"=>"Rubricas Marcadas na Base","b"=>"Base em Fórmulas");
            db_select('tipo',$arr_tipo,true,4,"");
	        ?>
	      </td>
      </tr>
      <tr >
        <td align="right" ><strong>Imprime Rubricas :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_ativos = array("t"=>"Ativas","f"=>"Inativas","i"=>"Todas");
            db_select('ativos',$arr_ativos,true,4,"");
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