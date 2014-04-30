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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_arretipo_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clarretipo = new cl_arretipo;
$clrotulo   = new rotulocampo;
$clrotulo->label("k00_tipo");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>

<script>
function js_mostrauni(){
   //alert('entrou!!!');
   if (document.form1.unifica.checked == true){
       document.getElementById('mostrauni').style.visibility='visible';  
       document.getElementById('mostralabel').style.visibility='visible';  
   }else{
       document.getElementById('mostrauni').style.visibility='hidden';  
       document.getElementById('mostralabel').style.visibility='hidden'; 
        
   }
}
function js_habilita(){

  sDebimporta = document.getElementById('parc').value;

  if (sDebimporta == 'a') {
    document.getElementById('data_vencidas').style.display = 'none';
  }else{
    document.getElementById('data_vencidas').style.display = '';
  }

  if(document.form1.tipor.value==0 || document.form1.tipdes.value==0){
    document.form1.pesquisa.disabled=true;
  }else{
    document.form1.pesquisa.disabled=false;
  }
} 
function js_abre(){
  
  var data = '';
  var uni  = '';
  
  if (document.form1.unifica.checked == true){
     uni = 'p';
  }
  
  if (document.form1.datavenc_dia.value == '' || 
      document.form1.datavenc_mes.value == '' || document.form1.datavenc_ano.value == ''){
	  alert("Informe corretamente a data do vencimento.");
  } else {
       data = document.form1.datavenc_ano.value+'-'+document.form1.datavenc_mes.value+'-'+document.form1.datavenc_dia.value;
  }
  
  if (confirm('Tem certeza de que quer importar mesmo todos os registros do tipo de debitos escolhido?') == true) {
    
    if (confirm('Tem certeza mesmo?') == true) {
      if (document.form1.tipor.value==0 || document.form1.tipdes.value==0) {
	      alert("Informe corretamente o tipo origem e o tipo destino.");
      } else {

        var lProcessoSistema = parseInt($F('lProcessoSistema'));
        var iProcesso        = "";
        var sTitular         = "";
        var dDataProcesso    = "";
        
        if (lProcessoSistema == 1) {
          
          iProcesso     = $F('v01_processo');
          sTitular      = "";
          dDataProcesso = "";
        } else {
          
          iProcesso     = $F('v01_processoExterno');
          sTitular      = $F("v01_titular");
          dDataProcesso = $F("v01_dtprocesso");
        }
        
        var oProcesso = new Object();
            oProcesso.lProcessoSistema = lProcessoSistema;
            oProcesso.iProcesso        = iProcesso;
            oProcesso.sTitular         = sTitular;
            oProcesso.dDataProcesso    = dDataProcesso;
        
	      js_OpenJanelaIframe('top.corpo','db_iframe','div4_importadivida002.php?oProcesso='+Object.toJSON(oProcesso)+'&k00_tipo_or='+document.form1.tipor.value+'&k00_tipo_des='+document.form1.tipdes.value+'&tipoparc='+document.form1.parc.value+'&uni='+uni+'&datavenc='+data,'Pesquisa',true);
      }
    }
  }
}
</script>
<style type="text/css">
select {
  width: 50%;
}
</style>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onload="alert('Esta rotina importa todos os débitos escolhidos para dívida ativa, e em hipótese alguma deve ser utilizada na importação de uma única matrícula, inscrição ou CGM! Utilize-a com cuidado! Se você não tem certeza do que está fazendo, não faça sem entrar em contato com o suporte da DBSeller ou com o responsável pelo CPD da prefeitura!!!')">
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<form class="container" name="form1" method="post">
  <fieldset>
  <legend>Importar Dívidas (Geral)</legend>
    <table class="form-container">
      <tr> 
        <td nowrap title="<?=$Tk00_tipo?>">Tipo de origem:</td>
        <td nowrap>
          <select name="tipor" id="tipor" onchange='js_habilita();' >
            <option value="0" >Escolha origem</option>
              <?
                 $sql = " select x.k00_tipo,x.k00_descr,x.tem_arrecad 
    			    	            from (select arretipo.k00_tipo,
    			    									         arretipo.k00_descr,
    			    											     ( select arrecad.k00_numpre from arrecad where arrecad.k00_tipo = arretipo.k00_tipo limit 1) as tem_arrecad
    			    										  from arretipo
    			    										       inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo
    			    										 where cadtipo.k03_parcano is true and arretipo.k00_instit = ".db_getsession('DB_instit')."
    			    										 order by k00_tipo ) as x 
    			    						 where x.tem_arrecad is not null	";
                 //$sql20 = $sql;
                 $result = $clarretipo->sql_record($sql);
                 $numrows=$clarretipo->numrows;
    			    	 for($i=0;$i<$numrows;$i++){
    			    	   db_fieldsmemory($result,$i,true);
    			    	   //for para colocar os selects
    			         echo " <option value=\"$k00_tipo\" >$k00_descr</option> ";
    			    	 }
              ?>
          </select>
        </td>
      </tr>
      <tr> 
        <td nowrap title="Tipo de destino para novos dados que serao gerados">Tipo de destino:</td>
        <td nowrap>
          <select name="tipdes" id="tipdes" onchange='js_habilita();' >
            <option value="0" >Escolha destino</option>
          <?
             $sql1 = "select k00_tipo, 
    				                 k00_descr 
    				            from arretipo 
    				           where k03_tipo = 5 
    									   and k00_instit = ".db_getsession('DB_instit') ."";
    
             $result1 = $clarretipo->sql_record($sql1);
             $numrows1=$clarretipo->numrows;
          	 for($i=0;$i<$numrows1;$i++){
          	   db_fieldsmemory($result1,$i,true);
          	   //for para colocar os selects
          	     echo " <option value=\"$k00_tipo\" >$k00_descr</option> ";
          	 }
          ?>
          </select>
        </td>
      </tr>
      <tr> 
        <td  nowrap title="">Débitos a serem importados : </td>
        <td  nowrap>
          <select name="parc" id="parc" onchange='js_habilita();' >
            <option value="t" >Totalmente vencidas</option>
            <option value="s" >Somente vencidas</option>
            <option value="a" >Todas parcelas</option>
          </select>
        </td>
      </tr>
      <tr id='data_vencidas'>
          <td nowrap>Parcelas vencidas até : </td>
          <td nowrap>
           <?
              $datavenc_dia = date('d',db_getsession("DB_datausu"));
              $datavenc_mes = date('m',db_getsession("DB_datausu"));
              $datavenc_ano = date('Y',db_getsession("DB_datausu"));
              db_inputdata('datavenc',$datavenc_dia,$datavenc_mes,$datavenc_ano,true,'text',1);
           ?> 
          </td>
      </tr>
      <tr>
         <td>
            Unificar debitos por numpre e receita :
         </td>
         <td nowrap>
         	<input name="unifica" type="checkbox" onchange='js_mostrauni();'>
         </td>
      </tr> 
      <tr>
    	<td nowrap title="Processos registrado no sistema?">
    	  Processo do Sistema:
    	</td>
    	<td nowrap>
    	  <?
    		$lProcessoSistema = true;
    		db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, 1, "onchange='js_processoSistema()'")
    	  ?>
    	</td>
      </tr>
      <tr id="processoSistema">
    	<td nowrap title="<?=@$Tp58_codproc?>">					  
          <?
    		db_ancora('Processo:', 'js_pesquisaProcesso(true)', 1);
    	  ?>					  
    	</td>
    	<td nowrap>
    	  <? 
    		db_input('v01_processo', 10, false, true, 'text', 1, 'onchange="js_pesquisaProcesso(false)"') ;	
    		db_input('p58_requer', 40, false, true, 'text', 3);
    	  ?>
    	</td>
      </tr>
      <tr id="processoExterno1" style="display: none;">
    	<td nowrap title="Número do processo externo">
    	  Processo:
    	</td>
    	<td nowrap>
    	  <? 
    		db_input('v01_processoExterno', 10, "", true, 'text', 1, null, null, null, "background-color: rgb(230, 228, 241);") ;
    	  ?>
    	</td>
      </tr>
      <tr id="processoExterno2" style="display: none;">
        <td nowrap title="Número do processo externo">						
      	  Titular do Processo:						
    	</td>
        <td nowrap>
          <? 
    		db_input('v01_titular', 54, 'false', true, 'text', 1) ;
    	  ?>
    	</td>
      </tr>
      <tr id="processoExterno3" style="display: none;">
    	<td nowrap title="Número do processo externo">					  
    	  Data do Processo:					  
    	</td>
    	<td nowrap>
    	  <? 
    		db_inputdata('v01_dtprocesso', @$v01_dtprocesso_dia, @$v01_dtprocesso_mes, @$v01_dtprocesso_ano, true, 'text', 1);
    	  ?>
    	</td>
      </tr> 
    </table>
  </fieldset>
  <input name="pesquisa" type="button" onclick='js_abre();' disabled  value="Pesquisa" style="margin-top: 10px;">
</form>

</body>
</html>
<script>
/*
 * FUNCOES DE PESQUISA
 */

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.v01_processo.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}
function js_mostraProcesso(iCodProcesso, sRequerente) {

  //alert('iCodProcesso -> ' + iCodProcesso + '  sRequerente -> ' + sRequerente);
  document.form1.v01_processo.value = iCodProcesso;
  document.form1.p58_requer.value  = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  //alert('iCodProcesso -> ' + iCodProcesso + '  sNome -> ' + sNome + "  erro -> " + lErro);
  if(lErro == true) {
    document.form1.v01_processo.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }

}    
/*
  funcao que trata se o processo é externo ou interno
*/


function js_processoSistema() {

var lProcessoSistema = $F('lProcessoSistema');

  if (lProcessoSistema == 1) {
    
    document.getElementById('processoExterno1').style.display = 'none';
    document.getElementById('processoExterno2').style.display = 'none';
    document.getElementById('processoExterno3').style.display = 'none';
    document.getElementById('processoSistema').style.display  = '';
    $('v01_processo').value = "";
    $('p58_requer').value = "";
  }	else {
    
    document.getElementById('processoExterno1').style.display = '';
    document.getElementById('processoExterno2').style.display = '';
    document.getElementById('processoExterno3').style.display = '';
    document.getElementById('processoSistema').style.display  = 'none';
  }

}
</script>
<script>

$("v01_processo").addClassName("field-size2");
$("p58_requer").addClassName("field-size9");
$("lProcessoSistema").setAttribute("rel","ignore-css");
$("lProcessoSistema").addClassName("field-size2");
$("v01_processoExterno").addClassName("field-size2");
$("v01_titular").addClassName("field-size9");
$("v01_dtprocesso").addClassName("field-size2");
$("datavenc").addClassName("field-size2");
$("parc").setAttribute("rel","ignore-css");
$("parc").addClassName("field-size5");
$("tipdes").setAttribute("rel","ignore-css");
$("tipdes").addClassName("field-size5");
$("tipor").setAttribute("rel","ignore-css");
$("tipor").addClassName("field-size5");

</script>