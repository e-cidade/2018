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
include("libs/db_liborcamento.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

db_postmemory($HTTP_POST_VARS);

$anousu = db_getsession("DB_anousu");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voce nao escolheu nenhuma Instituicao. Verifique!');
    return false;
  }else{
    obj = document.form1;
    if (obj.emite_balorc_rec.value==0   &&
        obj.emite_balorc_desp.value==0  &&
        obj.emite_desp_funcsub.value==0 &&
        obj.emite_rcl.value==0          &&
        obj.emite_rec_desp.value==0     &&
        obj.emite_resultado.value==0    &&
        obj.emite_rp.value==0           &&
        obj.emite_oper.value==0         &&
        obj.emite_mde.value==0          &&
        obj.emite_alienacao.value==0    &&
        obj.emite_proj.value==0         &&
        obj.emite_ppp.value==0          &&
        obj.emite_saude.value==0){
        alert("Selecione pelo menos um relatorio para ser impresso!");
	      return false;
    }
    
    env  = '&emite_balorc_rec='  + obj.emite_balorc_rec.value; 
    env += '&emite_balorc_desp=' + obj.emite_balorc_desp.value;
    env += '&emite_desp_funcsub='+ obj.emite_desp_funcsub.value;
    env += '&emite_rcl='         + obj.emite_rcl.value;
    env += '&emite_rec_desp='    + obj.emite_rec_desp.value;
    env += '&emite_resultado='   + obj.emite_resultado.value;
    env += '&emite_rp='          + obj.emite_rp.value;
    env += '&emite_mde='         + obj.emite_mde.value;
    env += '&emite_oper='        + obj.emite_oper.value;
    env += '&emite_aplicacao_recursos='+ obj.emite_alienacao.value;
    env += '&emite_proj='        + obj.emite_proj.value;
    //env += '&emite_alienacao='   + obj.emite_alienacao.value;
    env += '&emite_alienacao='   + '0';
    env += '&emite_saude='       + obj.emite_saude.value;
    env += '&emite_ppp='         + obj.emite_ppp.value;

    <?
    if ($anousu < 2007){
      $executar = "con2_lrfresumido002.php";
    } else if ($anousu == 2008){
      $executar = "con2_lrfresumido002_2008.php";
    } else if ($anousu == 2009) {
      $executar = "con2_lrfresumido002_2009.php";
    }
    ?>

    jan = window.open('<?=$executar?>?db_selinstit='+obj.db_selinstit.value+'&bimestre='+obj.bimestre.value+env,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" border=0>
    <form name="form1" method="post" action="" >
    <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td align="center" colspan="3">
        <? db_selinstit('',300,100); ?>
	    </td>
    </tr>
    <tr>
      <td colspan=2 nowrap>
        <b>Periodo :&nbsp;&nbsp;</b>
        <select name=bimestre> 
	        <option value="1B">Primeiro Bimestre</option>
	        <option value="2B">Segundo  Bimestre</option>
	        <option value="3B">Terceiro Bimestre</option>
	        <option value="4B">Quarto   Bimestre</option>
	        <option value="5B">Quinto   Bimestre</option>
	        <option value="6B">Sexto    Bimestre</option>
	        <option value="1S">Primeiro Semestre</option>
	        <option value="2S">Segundo  Semestre</option>
        </select>
      </td> 
    </tr>
    <tr>
      <td colspan=2>
        <fieldset>
          <legend>Opções de Impresso</legend>
          <table border=0>
            <tr>
              <td colspan>
                <b>BALANÇO ORCAM. - RECEITAS:</b>
              </td>
              <td>
                <select name=emite_balorc_rec>
 					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
              </td>
            </tr>    
					  <tr>
					    <td colspan>
					      <b>BALANÇO ORCAM. - DESPESAS:</b>
					    </td>
						  <td>
						    <select name=emite_balorc_desp>
						      <option          value="0">Nao</option>
							    <option selected value="1">Sim</option>
							  </select>     
				      </td>
					  </tr>    
					  <tr>
					    <td colspan>
					      <b>DESPESAS POR FUNÇÃO/SUBFUNÇÃO:</b>
					    </td>
						  <td>
						    <select name=emite_desp_funcsub>
						      <option          value="0">Nao</option>
						      <option selected value="1">Sim</option>
						    </select>     
					    </td>
					  </tr>    
					  <tr>
 			        <td colspan>
					      <b>RECEITA CORRENTE LÍQUIDA:</b>
					    </td>
						  <td>
						    <select name=emite_rcl>
						      <option          value="0">Nao</option>
						      <option selected value="1">Sim</option>
						    </select>     
					    </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>REC/DESP DO RPPS:</b>
				      </td>
					    <td>
					      <select name=emite_rec_desp>
					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
				      </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>RESULTADOS NOMINAL/PRIMÁRIO:</b>
				      </td>
					    <td>
					      <select name=emite_resultado>
					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
				      </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>RESTOS À PAGAR:</b>
				      </td>
					    <td>
					      <select name=emite_rp>
					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
				      </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>DESPESAS COM MDE:</b>
				      </td>
					    <td>
					      <select name=emite_mde>
					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
				      </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>DESPESAS COM SAÚDE:</b>
				      </td>
					    <td>
					      <select name=emite_saude>
					        <option          value="0">Nao</option>
					        <option selected value="1">Sim</option>
					      </select>     
				      </td>
				    </tr>    
				    <tr>
				      <td colspan>
				        <b>OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL:</b>
				      </td>
				      <td>
				        <select name=emite_oper>
				           <option          value="0">Nao</option>
				           <option selected value="1">Sim</option>
				        </select>     
				      </td>
				    </tr>
				    <tr>
			        <td colspan>
		            <b>PROJEÇÃO ATUARIAL DOS REGIMES DE PREVIDÊNCIA:</b>
		          </td>
 			        <td>
				        <select name=emite_proj>
				          <option          value="0">Nao</option>
				          <option selected value="1">Sim</option>
				        </select>     
			        </td>
				    </tr>    
				    <tr>
			        <td colspan>
			          <b>RECEITA DA ALIENAÇÃO DE ATIVOS /APLICAÇÃO DOS RECURSOS:</b>
			        </td>
				      <td>
				        <select name=emite_alienacao>
				          <option          value="0">Nao</option>
				          <option selected value="1">Sim</option>
				        </select>     
			        </td>
				    </tr>
            <tr>
              <td colspan>
                <b>DESPESAS DE CARÁTER CONTINUADO DERIVADAS DE  PPP:</b>
              </td>
              <td>
                <select name=emite_ppp>
                  <option          value="0">Nao</option>
                  <option selected value="1">Sim</option>
                </select>     
              </td>
            </tr>  				        
          </table>
        </fieldset>
      </td>
    </tr>
      <tr>
        <td align="center" colspan="2">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
    </form>
  </table>
</body>
</html>