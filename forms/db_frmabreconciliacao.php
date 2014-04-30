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

/* variaveis de configuração do formulario  */
$borda = 0;
/*  */
?>
<form name="form1" enctype="multipart/form-data" method="post" action="">

<table width='30%' border=<?=$borda?>>
	<tr>
		<td colspan=2 bgcolor='#000099'>
		   <center><b><font color='#FFFFFF'> Conciliação Bancária</font></b></center>
    </td>
	</tr>
	<tr>
		<td align='left' width='50%'>
			<!-- dados do extrato bancario -->
			<fieldset>
			<Legend align="left"><b> Dados da conciliacao : </b></Legend>
				<table border=<?=$borda?> width='100%'>
					<tr>
						<td colspan=2 nowrap title="">
							 <b> Conta : </b> 
						</td>
						<td colspan=2 nowrap title="">
              <?
                
                $sqlConta    = " select db83_sequencial,                                                                                 ";
                $sqlConta   .= "        db83_descricao,                                                                                  ";
                $sqlConta   .= "        k68_data                                                                                         ";
                $sqlConta   .= "   from (                                                                                                ";
                $sqlConta   .= " select db83_sequencial,                                                                                 ";
                $sqlConta   .= "        db83_descricao,                                                                                  ";
                $sqlConta   .= "        min(k83_conciliatipo) as conciliatipo,                                                           ";
                $sqlConta   .= "        max(k68_data)         as k68_data                                                                ";
                $sqlConta   .= "   from contabancaria                                                                                    ";
                $sqlConta   .= "        inner join concilia       on concilia.k68_contabancaria    = db83_sequencial                     ";
                $sqlConta   .= "        inner join conciliastatus on conciliastatus.k95_sequencial = concilia.k68_conciliastatus         ";
                $sqlConta   .= "        left  join conciliaitem   on k83_concilia                  = concilia.k68_sequencial             ";
                $sqlConta   .= "  group by db83_sequencial,                                                                              ";
                $sqlConta   .= "           db83_descricao                                                                                "; 
                $sqlConta   .= " ) as x                                                                                                  ";
                $sqlConta   .= "  where exists(select 1                                                                                  ";
                $sqlConta   .= "                 from concilia                                                                           ";
                $sqlConta   .= "                inner join conciliastatus on conciliastatus.k95_sequencial = concilia.k68_conciliastatus ";
                $sqlConta   .= "                where k68_contabancaria = x.db83_sequencial                                              ";
                $sqlConta   .= "                  and k68_data = x.k68_data                                                              ";
                $sqlConta   .= "                  and k95_fechada is true )                                                              ";
                
                $rsContas    = $clsaltes->sql_record($sqlConta);
                $numrows     = $clsaltes->numrows;
                $arrayContas = array( 0 => " Selecione a conta para conciliacao ");
                for($i=0;$i<$numrows;$i++){
                   db_fieldsmemory($rsContas,$i);
                   $arrayContas[$db83_sequencial] = $db83_sequencial." - ".$db83_descricao;
                 }
                 db_select('conta',$arrayContas,'',1,"style='width:400px' onchange='js_enabled();js_ajaxRequest(this);'");
              ?>
						</td>
					</tr>
					<tr>
						<td colspan=2 nowrap title="">
							 <b> Datas disponiveis para conciliacao : </b> 
						</td>
						<td colspan=2 nowrap title="">
              <?
                $arrayDatas = array(0 => " Selecione a data para conciliacao ");
                db_select('data',$arrayDatas,'',1,"style='width:400px' onchange='js_enabled()'; ","","");
              ?>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>

<table border=0 width='90%' >
  <tr>
	  <td align='center'>
      <input name="continuar" type="Button" id="continuar" value="Continuar" disabled onClick='js_abreConciliacao();' >
	  </td>
	</tr>
</table>
</form>
<script>

function js_ajaxRequest(obj){

	js_divCarregando("Aguarde, buscando datas das contas...","msg");
  var url       = 'cai4_carregadatascorrente.php';
  var parametro = 'conta='+obj.value;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:carregaDadosSelect});
	document.form1.data.disabled = true;
	
}

function js_enabled() {
	
	var data  = document.form1.data.value;
  var conta = document.form1.conta.value;
  if (data != '0' && conta != '0') {
    $('continuar').disabled = false;
  } else {
    $('continuar').disabled = true;
  }
  
}

function carregaDadosSelect(resposta) {

	js_removeObj("msg");
	 
	document.form1.data.disabled = false;
	js_limpaSelect(document.form1.data);  
	js_addSelectFromStr(resposta.responseText,document.form1.data);
  js_enabled();
  
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

function js_abreConciliacao(){
	var data      = document.form1.data.value;
  var conta     = document.form1.conta.value;
  var url       = 'cai4_abreconciliacao.php';
  var parametro = 'data='+data+'&conta='+conta;
  var dataFormatada = data.substr(8,2)+'/'+data.substr(5,2)+'/'+data.substr(0,4);
  var confirmacao = confirm('Deseja realmente abrir conciliacao para : \n Conta ; '+conta+'\n Data : '+dataFormatada );
  if (confirmacao){
    var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:js_continuar});
	  document.form1.data.disabled = true;
  	document.form1.conta.disabled = true;
  }
}

function js_continuar(resposta){
//	alert(resposta.responseText);
	var data  = document.form1.data.value;
	var conta = document.form1.conta.value;
  var retorno = resposta.responseText.split('|||');
  if (retorno[0] == '1') {
    alert(retorno[1]);
	  document.location.href = 'cai4_concbanc001.php?conta='+conta+'&data='+data+'&concilia='+retorno[2];
  }else{
    alert(retorno[1]);
	  document.location.href = 'cai4_abreconciliacao001.php';
  }
}


</script>