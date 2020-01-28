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
?>
<form name="form1" method="post" action="" >
<table width="45%" height="" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
		<td colspan=2 bgcolor='#000099'>
		   <center><b><font color='#FFFFFF'> Gerar pendencias antigas </font></b></center>
    </td>
	</tr>

  <tr> 
    <td width="" height="" align="center" valign="top">
	    <center>
       <table border="0"> 
          <tr> 
            <td colspan=2>
              <fieldset>
              <Legend align="left"> <b>  Dados para pesquisa : </b> </Legend>
              <table border="0">
                <tr>
                  <td>
									  <b> Conta : </b>
                  </td>
                  <td>
									<?
										$sqlConta    = " select distinct db83_sequencial,db83_descricao ";
										$sqlConta   .= "   from conplano  ";
                    $sqlConta   .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_codcon = conplano.c60_codcon ";
                    $sqlConta   .= "                                        and conplanocontabancaria.c56_anousu = conplano.c60_anousu ";
                    $sqlConta   .= "                                        and conplano.c60_anousu = ".db_getsession('DB_anousu'); 
                    $sqlConta   .= "        inner join contabancaria         on contabancaria.db83_sequencial    = conplanocontabancaria.c56_contabancaria ";
										$sqlConta   .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
										$sqlConta   .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
										$sqlConta   .= "                                        and c61_anousu       = ".db_getsession('DB_anousu');
										$sqlConta   .= "                                        and c61_instit       = ".db_getsession('DB_instit');
										$sqlConta   .= "        inner join saltes                on k13_reduz        = c61_reduz ";
										$sqlConta   .= "        inner join corrente              on k12_conta        = k13_conta ";
										$sqlConta   .= "        inner join conciliacor           on k84_id           = k12_id ";
										$sqlConta   .= "                                        and k84_data         = k12_data ";
										$sqlConta   .= "                                        and k84_autent       = k12_autent ";
										$sqlConta   .= "        inner join conciliaitem          on k83_sequencial   = k84_conciliaitem ";
										$sqlConta   .= "                                        and k83_conciliatipo = 3 "; 

										$rsContas    = $clsaltes->sql_record($sqlConta);
										$numrows     = $clsaltes->numrows;
										$arrayContas = array( 0 => " Selecione a conta para o processamento ");
										for($i=0;$i<$numrows;$i++){
											 db_fieldsmemory($rsContas,$i);
											 $arrayContas[$db83_sequencial] = $db83_sequencial." - ".$db83_descricao;
									  }
									  db_select('conta',$arrayContas,'',1,"style='width:400px' onchange='js_enabled();js_ajaxRequest(this);'");
									?>
                  </td>
								</tr>
								<tr>
					       	<td nowrap>
							      <b> Datas disponiveis : </b> 
						      </td>
					       	<td>
                   <?
                    $arrayDatas = array(0 => " Selecione a data para o processamento ");
                    db_select('data',$arrayDatas,'',1,"style='width:400px' onchange='js_enabled()'; ","","");
                  ?>
    						  </td>
                </tr>
              </table>
            </fieldset>
          </tr>
          <tr>
            <td> <input name="filtrar" type="button" id="filtrar" disabled value=" Filtrar com os dados selecionados "  onclick="return js_filtrar();"> </td>
          </tr>
       </table> 
    </table> 

       <table width="70%" border="0" align="center" cellspacing="0">
          <tr>
          <td colspan = 2>
          <?
					   if (isset($data) && $data != '0') {
							 // monta o iframeseleciona

							 $sqlAutentica  = " select distinct ";
							 $sqlAutentica .= "        ricaixa             as k12_id, ";
							 $sqlAutentica .= "        riautent            as k12_autent, ";
							 $sqlAutentica .= "	 		   ridata              as k12_data, ";
							 $sqlAutentica .= "  			 sum(rnvalordebito)  as db_valor_debito, ";
							 $sqlAutentica .= "  			 sum(rivalorcredito) as db_valor_credito, ";
							 $sqlAutentica .= "			   richeque            as db_cheque, ";
							 $sqlAutentica .= "			   rtcredor            as db_credor ";
							 $sqlAutentica .= "  from fc_extratocaixa(".db_getsession('DB_instit').",".$conta.",'".$data."','".$data."',false ) ";
							 $sqlAutentica .= "       left  join conciliacor  on ricaixa          = k84_id ";
							 $sqlAutentica .= "                              and riautent         = k84_autent ";
							 $sqlAutentica .= "                              and ridata           = k84_data ";
							 $sqlAutentica .= "       left  join conciliaitem on k84_conciliaitem = k83_sequencial ";
							 $sqlAutentica .= "                              and k83_conciliatipo = 3 ";
							 $sqlAutentica .= " group by ricaixa,riautent,ridata,richeque,rtcredor "; 

//                echo $sqlAutentica	;

							 $cliframe_seleciona->sql           =  $sqlAutentica;
							 $cliframe_seleciona->campos        = "k12_id,k12_autent,k12_data,db_Valor_debito,db_Valor_credito,db_cheque,db_Credor ";
							 $cliframe_seleciona->legenda       = "Selecione as autenticações : ";
							 $cliframe_seleciona->textocabec    = "darkblue";
							 $cliframe_seleciona->textocorpo    = "black";
							 $cliframe_seleciona->fundocabec    = "#aacccc";
							 $cliframe_seleciona->fundocorpo    = "#ccddcc";
							 $cliframe_seleciona->iframe_height = '325px';
							 $cliframe_seleciona->iframe_width  = '100%';
							 $cliframe_seleciona->iframe_nome   = "autent";
							 $cliframe_seleciona->chaves        = "k12_id,k12_data,k12_autent";
							 $cliframe_seleciona->marcador      = true;
							 
							 $cliframe_seleciona->dbscript      = "onClick='parent.js_selecionados();'";
							 $cliframe_seleciona->js_marcador   = 'parent.js_selecionados();';
							 $cliframe_seleciona->iframe_seleciona(1);
						 }
          
          ?>
          </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="processar"    type="button" id="processar"    value="Processar" disabled onclick="js_processar()" > 
              <input name="selecionados" type="hidden" id="selecionados" value="" > 
						</td>
          </tr>
        </table>
      </center>
      </td>
 </tr>
</table>
</form>

<script>

function js_selecionados(){

  var dados     = '';
	var virgula   = '';
  var elementos = autent.document.form1.getElementsByTagName('input');
  for(i = 0;i < elementos.length;i++){
    if(elementos[i].type == "checkbox" &&  elementos[i].checked){
      dados = dados + virgula + elementos[i].value;
      virgula = ', ';
    }
  }
	document.form1.selecionados.value = dados;
	if(dados != ''){
		$('processar').disabled = false;		
	}else{
		$('processar').disabled = true;	
	}

}

function js_filtrar(){
	document.form1.submit();
}

function js_processar(){
	var dados = document.form1.selecionados.value;
	var conta = document.form1.conta.value; 
  js_OpenJanelaIframe('top.corpo','db_iframe_processar','cai4_gerarpendenciasantigas.php?dados='+dados+'&conta='+conta,'Processando',true);

}

function js_ajaxRequest(obj){
  var url       = 'cai4_carregadatasconciliadosimplantacao.php';
  var parametro = 'conta='+obj.value;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:carregaDadosSelect});
  js_divCarregando('CARREGANDO ... ','divcarregando');
	document.form1.data.disabled = true;
}

function js_enabled(){
	var data  = document.form1.data.value;
  var conta = document.form1.conta.value;
  if (data != '0' && conta != '0') {
    $('filtrar').disabled = false;
  }else{
    $('filtrar').disabled = true;
  }
}

function carregaDadosSelect(resposta){
//  alert(resposta.responseText);
	document.form1.data.disabled = false;
	js_limpaSelect(document.form1.data);  
	js_addSelectFromStr(resposta.responseText,document.form1.data);
  js_enabled();
  js_removeObj('divcarregando');
}

function js_limpaSelect(obj){
  obj.length  = 0;	
}

function js_addSelectFromStr(str,obj){
  var linhas  = str.split("|");
  for(i=0;i<linhas.length;i++){
    if(linhas[i] != ''){
      colunas = linhas[i].split(";");
      obj.options[i] = new Option();
      obj.options[i].value = colunas[0];
      obj.options[i].text  = colunas[1];
    }
  }	
}

</script>