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
include("libs/db_sql.php");
include("classes/db_rhregime_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label("rh37_funcao");
$clrotulo->label("rh37_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrhregime = new cl_rhregime;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.rh37_funcao)document.form1.rh37_funcao.focus();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <form name="form1" method="post">
	  <table border="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="left" nowrap title="Digite o Ano / Mes de competência" >
            <strong>Ano / Mês :&nbsp;&nbsp;</strong>
          </td>
          <td>
            <?
            $ano = db_anofolha();
            db_input('ano',4,$IDBtxt23,true,'text',2,'')
            ?>
            &nbsp;/&nbsp;
            <?
            $mes = db_mesfolha();
            db_input('mes',2,$IDBtxt25,true,'text',2,'')
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" title="<?=$Trh37_funcao?>">
            <?
            db_ancora(@ $Lrh37_funcao, "js_pesquisarfuncao(true);", 1);
    		?>
          </td>
          <td>
            <?
            db_input('rh37_funcao', 8, $Irh37_funcao, true, 'text', 1, " onchange='js_pesquisarfuncao(false);'")
            ?>
            <?
            db_input('rh37_descr', 30, $Irh37_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
  <tr>
    <td colspan="2" >
            <tr>
              <td align="center" colspan="2">
                <?
                $result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_codreg, rh30_codreg||'-'||rh30_descr as rh30_descr", "rh30_descr" , " rh30_instit = ".db_getsession('DB_instit') ));
                db_multiploselect("rh30_codreg", "rh30_descr", "nselecionados", "sselecionados", $result_regime, array(), 5, 250);
                ?>
              </td>
            </tr>
    </td>
  </tr>
        <tr>
          <td height="25" colspan="2" align="center">
            <input type="button" value="Consultar" name="pesquisar" onclick="js_abrejan();">
          </td>
        </tr>
      </table>
      </form>
      </center>
    </td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_abrejan(){
  qry = "";
  rog = "?";
  if(document.form1.rh37_funcao.value!=""){
    qry = rog+"funcao="+document.form1.rh37_funcao.value;
    rog = "&";
  }
  if(document.form1.mes.value!=""){
    qry += rog+"mes="+document.form1.mes.value;
    rog = "&";
  }
  if(document.form1.ano.value!=""){
    qry += rog+"ano="+document.form1.ano.value;

  }
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }

  if (selecionados == "") {
    alert('Selecione ao menos um regime para impressão do relatório');
    return false;
  }
  qry += "&colunas1="+selecionados;
  location.href = 'pes3_consrhfuncao002.php'+qry;
}
function js_pesquisarfuncao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhfuncao','func_rhfuncao.php?funcao_js=parent.js_mostrafuncao1|rh37_funcao|rh37_descr','Pesquisa',true);
  }else{
     if(document.form1.rh37_funcao.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_rhfuncao','func_rhfuncao.php?pesquisa_chave='+document.form1.rh37_funcao.value+'&funcao_js=parent.js_mostrafuncao','Pesquisa',false);
     }else{
       document.form1.rh37_descr.value = '';
     }
  }
}
function js_mostrafuncao(chave,erro){
  document.form1.rh37_descr.value  = chave;
  if(erro==true){
    document.form1.rh37_funcao.value = '';
    document.form1.rh37_funcao.focus();
  }
}
function js_mostrafuncao1(chave1,chave2){
  document.form1.rh37_funcao.value  = chave1;
  document.form1.rh37_descr.value  = chave2;
  db_iframe_rhfuncao.hide();
}
</script>