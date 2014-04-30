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
include("classes/db_rharqbanco_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrharqbanco = new cl_rharqbanco;
$clrotulo = new rotulocampo;
$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_valores(){
  if(document.form1.datagera_dia.value == "" || document.form1.datagera_mes.value == "" || document.form1.datagera_ano.value == ""){
    alert("Data da geração do arquivo não informada");
    document.form1.datagera_dia.select();
  	return false;
  }else if(document.form1.datapagto_dia.value == "" || document.form1.datapagto_mes.value == "" || document.form1.datapagto_ano.value == ""){
    alert("Data de depósito não informada");
    document.form1.datapagto_dia.select();
  	return false;
  }else{
    qry  = "fopag_dtpago="+document.form1.datapagto_ano.value+'-'+document.form1.datapagto_mes.value+'-'+document.form1.datapagto_dia.value;
    qry += "&fopag_geracao="+document.form1.datagera_ano.value+'-'+document.form1.datagera_mes.value+'-'+document.form1.datagera_dia.value;
    qry += "&fopag_convenio="+document.form1.rh34_convenio.value;
    qry += "&fopag_agencia_controle="+document.form1.rh34_agencia1.value;
    qry += "&fopag_dv_agencia_controle="+document.form1.rh34_dvagencia1.value;
    qry += "&fopag_agencia_deposito="+document.form1.rh34_agencia.value;
    qry += "&fopag_dv_agencia_deposito="+document.form1.rh34_dvagencia.value;
    qry += "&fopag_cc_deposito="+document.form1.rh34_conta.value;
    qry += "&fopag_dv_cc_deposito="+document.form1.rh34_dvconta.value;
    qry += "&fopag_sequen="+document.form1.rh34_sequencial.value;
    js_OpenJanelaIframe('top.corpo','db_iframe_gerarhpasep','pes4_rhfopag90002.php?'+qry,'Gerando Arquivo',false);
    return true;
  }


}

function js_detectaarquivo(arquivo){
  top.corpo.db_iframe_gerarhpasep.hide();
  listagem = arquivo+"#Download arquivo TXT";
  js_montarlista(listagem,"form1");
}

function js_erro(msg){
  top.corpo.db_iframe_gerarhpasep.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_gerarhpasep.hide();
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
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td><b>Data da Geração:</b></td>
    <td colspan="3">
      <?
      if((!isset($datagera_dia) || (isset($datagera_dia) && trim($datagera_dia) == "")) && (!isset($datagera_mes) || (isset($datagera_mes) && trim($datagera_mes) == "")) && (!isset($datagera_ano) || (isset($datagera_ano) && trim($datagera_ano) == ""))){
	    $datagera_dia=date('d',db_getsession('DB_datausu'));
	    $datagera_mes=date('m',db_getsession('DB_datausu'));
	    $datagera_ano=date('Y',db_getsession('DB_datausu'));
      }
	  db_inputdata('datagera',@$datagera_dia,@$datagera_mes,@$datagera_ano,true,'text',1,"");
	  ?>
	</td>
  </tr>
  <tr>
    <td><b>Data do Pagamento:</b></td>
    <td colspan="3">
      <?
      if((!isset($datapagto_dia) || (isset($datapagto_dia) && trim($datapagto_dia) == "")) && (!isset($datapagto_mes) || (isset($datapagto_mes) && trim($datapagto_mes) == "")) && (!isset($datapagto_ano) || (isset($datapagto_ano) && trim($datapagto_ano) == ""))){
        $datapagto_dia = "";
        $datapagto_mes = "";
        $datapagto_ano = "";
      }
      db_inputdata('datapagto',@$datapagto_dia,@$datapagto_mes,@$datapagto_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_convenio',15,$Irh34_convenio,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Agencia de Controle :">
      <b>Agencia de Controle:
    </td>
    <td> 
      <?
      db_input('rh34_agencia1',5,$Irh34_agencia,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td> 
      <?
      db_input('rh34_dvagencia1',2,$Irh34_dvagencia,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Agencia p/deposito">
     <b>Agencia p/deposito:
    </td>
    <td> 
      <?
      db_input('rh34_agencia',5,$Irh34_agencia,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td> 
      <?
      db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Conta p/deposito">
      <b>Conta p/deposito:
    </td>
    <td> 
      <?
      db_input('rh34_conta',15,$Irh34_conta,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td> 
      <?
      db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align = "center"> 
    <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_valores();" >
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>