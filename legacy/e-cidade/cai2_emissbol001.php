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
include("classes/db_cfautent_classe.php");
include("classes/db_saltes_classe.php");
include("classes/db_caiparametro_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");
$rotulocampo->label("k29_contassemmovimento");

$k00_dtoper     = date('Y-m-d',db_getsession("DB_datausu"));
$k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
$k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
$k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));
$k29_instit     = db_getsession("DB_instit");

$clcaiparametro  = new cl_caiparametro;
$rsCaiParametro  = $clcaiparametro->sql_record($clcaiparametro->sql_query($k29_instit,"k29_contassemmovimento",null,""));
$iCaiParametro   = @pg_num_rows($rsCaiParametro);

if ( $iCaiParametro > 0 ) {
  db_fieldsmemory($rsCaiParametro,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {

  var obj                  = document.form1;
  var datai                = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf                = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var contasnegativas      = obj.contasnegativas.value;
  var imprimeinterferencia = obj.imprime_interferencia.value;
  var ordemconta           = obj.ordem_conta.value;
  var caixa                = obj.k11_id.value;
  var conta                = obj.k13_conta.value;
  var quebrarpag           = obj.quebrarpag.value;
  var contassemmov         = obj.k29_contassemmovimento.value;
  
  jan = window.open('cai2_emissbol002.php?contasnegativas='+contasnegativas
                                                           +'&imprime_interferencia='+imprimeinterferencia
                                                           +'&ordem_conta='+ordemconta
                                                           +'&datai='+datai
                                                           +'&dataf='+dataf
                                                           +'&caixa='+caixa
                                                           +'&conta='+conta
                                                           +'&quebrarpag='+quebrarpag
                                                           +'&contassemmov='+contassemmov,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_relatorio1(tipo) {

  var tipo         = tipo;
  var obj          = document.form1;
  var datai        = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf        = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var caixa        = obj.k11_id.value;
  var conta        = obj.k13_conta.value;
  var quebrarpag   = obj.quebrarpag.value;
  var contassemmov = obj.k29_contassemmovimento.value;
  
  jan = window.open('cai2_emissbol003.php?datai='+datai
                                                 +'&dataf='+dataf
                                                 +'&caixa='+caixa
                                                 +'&conta='+conta
                                                 +'&quebrarpag='+quebrarpag
                                                 +'&contassemmov='+contassemmov
                                                 +'&tiporel='+tipo,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_relatorio2() {
  var obj          = document.form1;
  var datai        = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf        = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  var caixa        = obj.k11_id.value;
  var conta        = obj.k13_conta.value;
  var quebrarpag   = obj.quebrarpag.value;
  var contassemmov = obj.k29_contassemmovimento.value;
  
  jan = window.open('cai2_emissbol004.php?datai='+datai
                                                 +'&dataf='+dataf
                                                 +'&caixa='+caixa
                                                 +'&conta='+conta
                                                 +'&quebrarpag='+quebrarpag
                                                 +'&contassemmov='+contassemmov,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>
            <tr> 
              <td align="right" nowrap><strong>Data inicial:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp; 
                <?=db_inputdata("datai",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,'text',2)?>
              </td>
            </tr>


            <tr> 
              <td align="right" nowrap><strong>Data final:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp; 
                <?=db_inputdata("dataf",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano,true,'text',2)?>
              </td>
            </tr>


	    
<!--            <tr> 
              <td height="25" nowrap><strong>Data Final:</strong></td>
              <td height="25" nowrap>&nbsp;&nbsp; 
              </td>
            </tr>-->
            <tr>
              <td align="right" nowrap title="<?=$Tk11_id?>"><?=$Lk11_id?></td>
              <td align="left"  nowrap>&nbsp; &nbsp;
                <?
				$clcfautent = new cl_cfautent;
				$result = $clcfautent->sql_record($clcfautent->sql_query("","k11_id#k11_local","k11_local")); 
				db_selectrecord("k11_id",$result,true,2,"","","","0");
				?>
              </td>
            </tr>

            <tr>
              <td align="right" nowrap title="<?=$Tk13_conta?>"><?=$Lk13_conta?></td>
              <td align="left" nowrap>&nbsp; &nbsp;
                <?
				$clsaltes = new cl_saltes;
				$result = $clsaltes->sql_record($clsaltes->sql_query("","saltes.k13_conta#k13_descr","k13_descr")); 
				db_selectrecord("k13_conta",$result,true,2,"","","","0");
				?>
              </td>
            </tr>

		  <tr>
		    <td align="right" nowrap title="<?=@$Tk29_contassemmovimento?>">
		       <strong>Traz Contas sem Movimento:</strong>
		    </td>
		    <td align="left" nowrap>&nbsp; &nbsp; 
		      <?
		         $x = array('f'=>'Não','t'=>'Sim');
		         db_select('k29_contassemmovimento',$x,true,2,"");
		      ?>
		    </td>
		  </tr>
      
     <tr>
        <td align="right"><strong>Imprimir em cinza as contas negativas:</strong> 
	 </td>
        <td>
	  &nbsp; &nbsp;
          <select name="contasnegativas">
            <option value = 'S'>Sim</option>
            <option value = 'N'>Não</option>
        </td>
      </tr>
     <tr>
        <td align="right"><strong>Imprimir Interferências:</strong> 
	 </td>
        <td>
	  &nbsp; &nbsp;
          <select name="imprime_interferencia">            
            <option value = 'N'>Não</option>
            <option value = 'S'>Sim</option>
        </td>
      </tr>
     
     
      
      
      
      <tr>
        <td align="right"><strong>Quebrar páginas:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="quebrarpag">
            <option value = 'S'>Sim</option>
            <option value = 'N'>Não</option>
        </td>
      </tr>	    
     <tr>
        <td align="right"><strong>Ordem:</strong></td>
        <td> &nbsp; &nbsp;
          <select name="ordem_conta">
            <option value = '1'>Código Banco/Reduzido</option>
            <option value = '2'>Nome Banco/Reduzido</option>
            <option value = '3'>Estrutural</option>
            <option value = '4'>Descrição</option>
        </td>
      </tr>

	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>	    
	    
            <tr> 
              <td colspan = "2" align="center" > 
	          <input name="boletim" type="button" id="boletim" onClick="js_relatorio()" value="Boletim"> &nbsp; &nbsp;&nbsp; &nbsp;
	          <input name="autentica" type="button" id="autentica" onClick="js_relatorio1('c')" value="Autenticação Completo">
	          <input name="autenticaresum" type="button" id="autenticaresum" onClick="js_relatorio1('r')" value="Autenticação Resumido">
	          <input name="autent_conta" type="button" id="autent_conta" onClick="js_relatorio2()" value="Autenticação por Conta">
	      <br>
	      <br>
	      * Para emissão do boletim apenas a data inicial é utilizada. Mesmo preenchendo a data final, ela será desconsiderada.
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