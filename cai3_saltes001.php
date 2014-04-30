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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
  function js_relatorio(){
    var F = document.form1;

    ano = F.datai_ano.value;
    mes = F.datai_mes.value;
    dia = F.datai_dia.value;

    if (dia == "" || mes == "" || ano == "") {
      alert("Preencha a data!");
      return false;
    }
    
    tipo='null';
    for(x=0; x < F.length ;x++){
       if (F[x].type  == 'radio') {
           if (F[x].checked == true){
              tipo = F[x].value;
	   } 
       }
    }
    jan = window.open('cai2_saltes003.php?datai_dia='+dia+'&datai_mes='+mes+'&datai_ano='+ano+'&tipo='+tipo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="20">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" >
<table width="100%" height="100%" border="2" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
    <?
    if(!isset($tipo)){
      $tipo = "conta";
      $datai_dia = date('d',db_getsession("DB_datausu"));
      $datai_mes = date('m',db_getsession("DB_datausu"));
      $datai_ano = date('Y',db_getsession("DB_datausu"));
    }
    ?>
  <tr height="5%" > 
    <td align="left" valign="top" >
        <table border="0" cellspacing="0" cellpadding="0">
          <tr><td colspan='7'></td> 
          </tr> 
                 <tr> 
                    <td nowrap><strong>Data Inicial:</strong></td>
                    <td nowrap>&nbsp;&nbsp; 
                      <?=db_inputdata("datai",@$datai_dia,@$datai_mes,@$datai_ano,true,'text',2)?>  
                    </td>
		    <td>
		    </td>
		    <td nowrap>
		    <strong>Soma por: </strong>
		    </td>
		    <td>
		    <input name='tipo' value='conta' type='radio' <?=(isset($tipo)&&$tipo=='conta'?"checked":(!isset($tipo)?"checked":""))?> onclick='document.form1.submit()'>
		    Conta
		    </td>
		    <td>
		    </td>
		    <td>
		    <input name='tipo' value='recurso' type='radio' onclick='document.form1.submit()' <?=(isset($tipo)&&$tipo=='recurso'?"checked":"")?>>
		    Recurso
            </td>
		    <td>
		    </td>
		    <td>
		    <input name='tipo' value='recurso_conta' type='radio' onclick='document.form1.submit()' <?=(isset($tipo)&&$tipo=='recurso_conta'?"checked":"")?>>
		    Recurso/Conta
            </td>
            <td>
		    <input name='tipo' value='instituicao' type='radio' onclick='document.form1.submit()' <?=(isset($tipo)&&$tipo=='instituicao'?"checked":"")?>>
		    Instituição bancária
            </td>
        
            <td width="50px">
		       &nbsp; 
		    </td>
		    <td>
		    <input type=button name=relatorio value='Emitir Relatorio' onClick='js_relatorio();'>
                    </td>
                 </tr>
      </table>
    </td>
    </tr>
    <tr height="80%"> 
    <td align="center">
    <?
    if(!isset($tipo)){
      $tipo = "conta";
      $datai_dia = date('d',db_getsession("DB_datausu"));
      $datai_mes = date('m',db_getsession("DB_datausu"));
      $datai_ano = date('Y',db_getsession("DB_datausu"));
    }
    ?>
    <iframe id="saldos" frameborder="0" name="iframe_saldos" leftmargin="0" topmargin="0"
          src="cai3_saltes002.php?datai_dia=<?=$datai_dia?>&datai_mes=<?=$datai_mes?>&datai_ano=<?=$datai_ano?>&tipo=<?=($tipo)?>" 
	  height="100%" width="100%">
    </iframe>	
    </td>
   </tr>
   <tr>
    <td valign="top" colspan=2 align="center">
    <table bordercolor="#000000" style="font-size:12px" border="0" cellspacing="0" cellpadding="3">
    <td align="right"><b>Totais  </b></td>
	<td align="right">Saldo.Ant   <input align=right size=15 type=text name=tot_ant></td>
	<td align="right">Vlr.Deb     <input align=right size=15 type=text name=tot_deb></td>
	<td align="right">Vlr.Cred    <input align=right size=15  type=text name=tot_cred></td>
	<td align="right">Saldo atual <input align=right size=15 type=text name=tot_atual></td> </td>
      </table> 
    </td>
  </tr>
</table>
</form>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>