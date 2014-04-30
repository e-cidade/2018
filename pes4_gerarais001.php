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
  //js_controlarodape(true);
  qry  = 'ano_base='+ document.form1.ano_base.value;
  qry += '&mes_base='+ document.form1.mes_base.value;
  qry += '&obs=' + document.form1.obs.value;
  qry += '&cpfr=' + document.form1.cpfr.value;
  qry += '&cnpj_sind=' + document.form1.cnpj_sind.value;
  qry += '&w_sind=' + document.form1.w_sind.value;
  qry += '&cnpj_asso=' + document.form1.cnpj_asso.value;
  qry += '&w_asso=' + document.form1.w_asso.value;
  qry += '&datan=' + document.form1.datan_dia.value+document.form1.datan_mes.value+document.form1.datan_ano.value ;
  qry += '&w_extras=' + document.form1.w_extras.value;
  qry += '&codmun=' + document.form1.codmun.value;
  qry += '&nome_resp=' + document.form1.nome_resp.value;
  qry += '&r70_numcgm=' + document.form1.r70_numcgm.value;
  qry += '&retificacao=' + document.form1.retificacao.value;
  qry += '&dataretificacao=' + document.form1.dataretificacao.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_gerarais','pes4_gerarais002.php?'+qry,'Gerando Arquivo',true);
}

function js_erro(msg){
  //js_controlarodape(false);
  top.corpo.db_iframe_gerarais.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_gerarais.hide();
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}

function js_detectaarquivo(arquivo,pdf){
  //js_controlarodape(false);
  top.corpo.db_iframe_gerarais.hide();
  listagem = arquivo+"#Download Arquivo TXT |";
  listagem+= pdf+"#Download Relatório";
  js_montarlista(listagem,"form1");
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
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano Base / Mes Base" >
        <strong>Ano / Mês (Base):&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
          $ano_base = db_anofolha() - 1;
            db_input('ano_base',4,'',true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
          $mes_base = 12;
            db_input('mes_base',2,'',true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Nome do Responsavel" >
        <strong>Nome do Responsavel:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('nome_resp',35,'',true,'text',2,'')
	  ?>
      </tr>
      <tr>
        <td align="right"  >
        <strong>CPF do Responsável:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('cpfr',11,'',true,'text',2,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ")
	  ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap>
          <strong>Data de Nascimento:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?=db_inputdata("datan",'','','',true,'text',2)?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="RAIS retificadora" ><strong>Tipo:&nbsp;&nbsp; </strong>
        </td>
        <td align="left">
        <?
         $xx = array("2"=>"Primeira Entrega","1"=>"Retificadora");
         db_select('retificacao',$xx,true,4,"");
        ?>
        </td>
      </tr>
      <tr>
        <td align="right" nowrap>
          <strong>Data da Retificacao:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?=db_inputdata("dataretificacao",'','','',true,'text',2)?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Código Municipio" >
        <strong>Código Municipio:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('codmun',7,'',true,'text',2,'')
	  ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Código Municipio" >
        <strong>Observação:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('obs',15,'',true,'text',2,'')
	  ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Rubrica de Horas Extras " >
        <strong>Rubrica Hora-Extras:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('w_extras',16,'',true,'text',2,'')
	        ?>
        </td>
      </tr>
    <td>
      <fieldset>
        <legend><strong>Sindical</strong></legend>
        <table width="100%">
      <tr >
        <td align="right" nowrap title="Código Nacional de Pessoal Jurídica" >
        <strong>CNPJ:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('cnpj_sind',14,'',true,'text',2,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ")
	  ?>
        </td>
        <td align="right" nowrap title="Rubricas de Descontos " >
        <strong>Rubricas:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('w_sind',14,'',true,'text',2,'')
	  ?>
        </td>
      </tr>
	
        </table>
      </fieldset>
    </td>
    <td>
      <fieldset>
        <legend><strong>Associativa</strong></legend>
        <table width="100%">
      <tr >
        <td align="right" nowrap title="Código Nacional de Pessoal Jurídica" >
        <strong>CNPJ:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('cnpj_asso',14,'',true,'text',2,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ")
	  ?>
        </td>
        <td align="right" nowrap title="Rubricas de Descontos " >
        <strong>Rubricas:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            db_input('w_asso',16,'',true,'text',2,'')
	  ?>
        </td>
      </tr>
	
        </table>
      </fieldset>
    </td>
  <tr>
  </tr>
<tr>
    <td colspan="2"  align="center">
      <fieldset>
        <legend><b>CNPJ</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="CNPJ">
              <b>CNPJ:</b>
            </td>
            <td>
              <?
$instit=db_getsession("DB_instit");
$sql = "select distinct z01_numcgm,z01_cgccpf||'-'||z01_nome as z01_nome from rhlota inner join cgm on rhlota.r70_numcgm=cgm.z01_numcgm  where r70_instit=$instit;";
$result= pg_query($sql);
db_selectrecord("r70_numcgm", $result, true     , @$db_opcao, "",           "",          "",       "0", "","2");

              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>


      <tr>
	<td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="button" value="Gera" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>