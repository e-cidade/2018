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

include("classes/db_conplanoexe_classe.php");
include("classes/db_saltes_classe.php");
$clsaltes = new cl_saltes;
$clconplanoexe = new cl_conplanoexe;

// seleciona conta a debitar

$sql_conta_debitar = $clconplanoexe->sql_conta_debitar(date('Y-m-d',db_getsession("DB_datausu")),null,"c62_reduz,  c60_estrut ||'-'|| c60_descr","c60_estrut",
  "c62_anousu = ".db_getsession("DB_anousu")."and c60_codsis in (1,5,6,7,8) and substr(c60_estrut,1,1) not in ('3','4') and c61_instit = ".db_getsession("DB_instit").
        "and ( case
                 when (     ( t1.k02_codigo     is not null and t1.k02_limite     is not null and t1.k02_limite     < '".date('Y-m-d',db_getsession("DB_datausu"))."' )
                         or ( t2.k02_codigo     is not null and t2.k02_limite     is not null and t2.k02_limite     < '".date('Y-m-d',db_getsession("DB_datausu"))."' )
                         or ( saltes.k13_reduz  is not null and saltes.k13_limite is not null and saltes.k13_limite < '".date('Y-m-d',db_getsession("DB_datausu"))."' )
                      ) then false
                   else
                     true
               end ) ");


//  echo "<br>" . $sql_conta_debitar . "<br>";
$result_conta_debitar = $clconplanoexe->sql_record($sql_conta_debitar);
// seleciona conta a creditar

$sqlsaltes = $clsaltes->sql_query_anousu(null, "k13_conta, k13_descr", null, "c61_instit = ".db_getsession("DB_instit") . " and k13_limite is null or k13_limite > '".date("Y-m-d",db_getsession("DB_datausu"))."'");
//echo "<br>" . $sqlsaltes . "<br>";

$result_conta_creditar = $clsaltes->sql_record($sqlsaltes);
$clrotulo = new rotulocampo;
$clrotulo->label("k17_hist");
$clrotulo->label("k17_codigo");
$clrotulo->label("k18_motivo");
//$clrotulo->label("k17_dtanu");
// $clrotulo->label("k17_valor");
$clrotulo->label("c50_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
?>
<script>
function js_atualiza1(qual){
  if(qual=='debito')
    document.form1.descr_debito.options[document.form1.debito.selectedIndex].selected = true;
  if(qual=='descr_debito')
    document.form1.debito.options[document.form1.descr_debito.selectedIndex].selected = true;
}
function js_atualiza2(qual){
  if(qual=='credito')
    document.form1.descr_credito.options[document.form1.credito.selectedIndex].selected = true;
  if(qual=='descr_credito')
    document.form1.credito.options[document.form1.descr_credito.selectedIndex].selected = true;
}
</script>
<form name="form1" method="post" onsubmit="return js_gravar();">
<input type="hidden" name="chaves" value="">

<center>
<table border=0>  
  <tr>
    <td align="right">    <strong>Código do Slip:</strong>  </td>
    <td><?  db_input("k17_codigo", 10, $Ik17_codigo, true, 'text', 3, "", "numslip");  ?>   </td>
  </tr>
  <tr>
    <td align="right"><strong><? db_ancora('Conta a Debitar (Receber): ',"js_pesquisac01_reduz(true);",2);   ?></strong></td>
    <td nowrap><? db_selectrecord("debito", $result_conta_debitar, true, (isset($read_only) && trim($read_only) != "" ? 3 : 1), "", "", "", " -(Selecione)","js_atuRecursoConta(this);");   ?></td>
  </tr>
  <tr>
    <td align="right"><strong><? db_ancora('Conta a Creditar (Pagar): ',"js_pesquisac01_reduz1(true);",2); ?></strong></td>
    <td nowrap><? db_selectrecord("credito", $result_conta_creditar, true, (isset($read_only) && trim($read_only) != "" ? 3 : 1), "", "", "", " -(Selecione)");   ?></td>
  </tr>
  <tr>
    <td align="right"><? db_ancora(@$Lk17_hist,"js_pesquisac50_codhist(true);",2);  ?></td>
    <td><?
                  db_input('k17_hist',5,$Ik17_hist,true,'text',1," onchange='js_pesquisac50_codhist(false);'");
                  db_input('c50_descr',40,$Ic50_descr,true,'text',3);
            ?>
    </td>
  </tr>
  <tr>
    <td align="right">
      <? 
        db_ancora("<b>CGM do Favorecido:</b>","js_pesquisaz01_numcgm(true);",2); 
      
      ?>
    </td>
    <td><?
                  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
                  db_input('z01_nome',40,$Iz01_nome,true,'text',3);
             ?>
    </td>
  </tr>
  <tr>
    <td align="right"><strong>Valor da Transação:</strong></td>
    <td><?               
              // db_input("k17_valor", 15, 4, true,'text',1," onBlur=js_atuValor(this.value);");
             ?>
	     <script>
		// onKeyUp="js_ValidaCampos(this,4,'Valor');"
		// onblur="js_atuValor(this.value);" 

     
	     </script>
             <input type="text" size="15" 
	            name="k17_valor" 
	            id="k17_valor" 
		    value="<?=@trim(db_formatar(($k17_valor),'p'));?>"
		     
		    onblur="js_atuValor(this.value);"    
		    autocomplete="off"
            onKeyPress="js_ValidaCampos(this,4,'Valor','','',event); js_atuValor(this.value);" 
		    style="text-align:right;font-weight:bold;background-color:#FFFFFF"> 
	     	    
    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong>Observações:</strong></td>
    <td align="left" valign="top">
      <?
            if((!isset($texto) || (isset($texto) && trim($texto) == "")) && isset($k17_texto) && trim($k17_texto) != ""){
                  $texto = $k17_texto;
           }
           db_textarea("texto", 4, 60, 0, true, 'text', (isset($read_only) && trim($read_only) != "" ? 3 : 1), "onDblClick='document.form1.texto.value=\"\"'");
      ?>
    </td>
  </tr>  
  <tr>
      <td valign="top">  
            <input type=button name=btn value="ADICIONAR RECURSOS" onclick="js_adiciona_linha(true);">
      </td>
      <td >
           <div id=tbl style="overflow:auto; height:150px;max-height:150px">
              <table id=tabRecursos width=100% border=0 style="border:1px solid #000000">
                 <tr stye="font-weight:bold">
                      <td width=10%> RECURSO</td>
                      <td width=75%> DESCRIÇÃO </td>
                      <td width=10%> VALOR </td>
                      <td width=5%> CANCELAR </TD>
                 </tr>
              </table>  
           </div>
      </td>
  </tr>
  <tr>
    <td colspan=2 align=center>
         <!--<input type="button"  onclick="js_gravar();"  name="confirma" value="Emitir Slip">
         -->
    </td>  
  </tr>
</table>
<? if ($desabilitabotao==false){
       echo "<input type='submit'  name='confirma' value='Emitir' >";
}
  else{
echo "<input type='submit'  name='confirma' value='Emitir Slip' disabled>";
  }
?>
         <?
          $db_opcao = isset($db_opcao)?$db_opcao:1;
          echo "<input name=\"pesquisar\" type=\"button\" id=\"pesquisar\" value='".($db_opcao==1?"Importar":"Pesquisar")."' onclick=\"js_pesquisa();\">" ;
         ?>
</center>
</form>
<script>

function primeiroRecurso(){
  var tab = document.getElementById("tabRecursos");
  if (tab.rows.length > 1 ){
        id_tr = tab.rows[1].id;
        id = id_tr.split('_');
        id  = id[1];                                
        receita = eval('document.form1.rec_'+id+'.value');
	return receita;
  }
  return false;
  
}

function js_adiciona_linha(mostra,chave){	
    var sem_rec ='';
    var tab = document.getElementById("tabRecursos");
    var sep = '';
    for(var x=1; x< tab.rows.length;x++){                
          recurso = tab.rows[x].id.split('_');
          sem_rec += sep + recurso[1];
	  sep =',';
    }
    
    if (mostra==true){
	    if (sem_rec==''){
        	  js_OpenJanelaIframe('top.corpo','db_iframe','func_orctiporec.php?funcao_js=parent.js_mostratiporec|o15_codigo|o15_descr','Pesquisa',true);
	    } else {
        	  js_OpenJanelaIframe('top.corpo','db_iframe','func_orctiporec.php?sem_recurso='+sem_rec+'&funcao_js=parent.js_mostratiporec|o15_codigo|o15_descr','Pesquisa');
	    }
    } else {      
           js_OpenJanelaIframe('top.corpo','db_iframe','func_orctiporec.php?pesquisa_conta='+chave+'&funcao_js=parent.js_mostratiporec','Pesquisa',false);
	   
    }  
}


function js_mostratiporec(chave1, chave2){
  db_iframe.hide();	
  // se adicionar pelo menos um recurso a mais que o primeiro adicionado, bloqueia a alteração de valores
  if (primeiroRecurso()!=false){
       document.form1.k17_valor.disabled=true;
       document.form1.k17_valor.style.color='#000000';
       document.form1.k17_valor.style.backgroundColor='#DEB887';
  }   

  bloqueia_linha=false
  if  (primeiroRecurso()==false){
      bloqueia_linha=true

  }  
   // adiciona o recurso ao final da tabela com o campo valor aberto para preenchimento
  var tab = document.getElementById("tabRecursos");

  var row =   tab.insertRow(tab.rows.length)  ;
  row.style.backgroundColor='white';
  row.setAttribute("id","rec_"+chave1);
  
  var col_A = row.insertCell(0);
  col_A.innerHTML ='<input type="text" name="rec_'+chave1+'" value='+chave1+' size="10" readonly style="background-color:#DEB887">';
  
  var col_B = row.insertCell(1);
  col_B.innerHTML = chave2;
  
  var col_C = row.insertCell(2);
  if (bloqueia_linha){
      // o primeiro recurso não pode ter o valor digitado manualmente
      col_C.innerHTML = '<input type="text" name="rec_val_'+chave1+'" readonly  value=\'\' size="12" style=\'text-align:right;\' >';
  } else {
      col_C.innerHTML = '<input type="text" name="rec_val_'+chave1+'" value=\'\' size="12" style=\'text-align:right\' onKeyUp=\"this.value=this.value.replace(\',\',\'.\')\";  onblur=\'js_ajustaValor(this);\'>';
  }  
  col_D = row.insertCell(3);
  col_D.setAttribute("align","center");
  col_D.innerHTML = '<input type="button" name="btn_E"  value="E" onclick="js_excluir('+chave1+')";>';
    
  // tab.appendChild(row);
  // não precisa executar o apend acima porque a linha criada já esta vinculada a tabela.
  
}

function js_excluir(linha){    
    var tab = document.getElementById("tabRecursos");
    for(var x=2; x< tab.rows.length;x++){
         // começa na linha 1 para nao permitir excluir a primeiro recurso
         if (tab.rows[x].id  == 'rec_'+linha  ){
               valor = eval('document.form1.rec_val_'+linha+'.value');

	       var cRec = eval('document.form1.rec_val_'+primeiroRecurso()+'.value');
	       
	       cRec = parseFloat(cRec) + parseFloat(valor);
		
               eval('document.form1.rec_val_'+primeiroRecurso()+'.value = cRec.toFixed(2)');
	       tab.deleteRow(x);
               break;
         }
    }
    // se só ficar a linha do recurso então libera o campo pra alterar o valor do slip
    if ( tab.rows.length < 3  ){ 
        document.form1.k17_valor.disabled=false;
        document.form1.k17_valor.style.backgroundColor='#FFFFFF';
    }
}
function js_atuValor(valor){    
    eval('document.form1.rec_val_'+primeiroRecurso()+'.value=valor');
}
function js_ajustaValor(obj){     
  
    var valor_livre = parseFloat(eval('document.form1.rec_val_'+primeiroRecurso()+'.value'));
    if (valor_livre >= parseFloat(obj.value) ){

           var vRec = eval('document.form1.rec_val_'+primeiroRecurso()+'.value');
           vRec = parseFloat(valor_livre) - parseFloat(obj.value);
	   eval('document.form1.rec_val_'+primeiroRecurso()+'.value=vRec.toFixed(2)');
           obj.disabled='true'; // funciona desabilitando o objeto para edição
           obj.style.color='#000000';               
    }else {
    	alert('Valor incorreto :  Recurso Livre: '+valor_livre+', Digitado: '+obj.value);
    	obj.value = 0;    	
    } 	
    
}


function js_atuRecursoConta(obj){
    // primeiro apaga todos os recursos já lançados ( aqui o usuario esta trocando a conta recebedora )
    var tab = document.getElementById("tabRecursos");
    for(var x=1; x< tab.rows.length;x++){
         // começa na linha 1 para nao permitir excluir a primeiro recurso
        tab.deleteRow(x);
    }
    document.form1.k17_valor.value='';
    js_adiciona_linha(false,obj.value);
}


function js_pesquisac50_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?funcao_js=parent.js_mostrahist1|c50_codhist|c50_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?pesquisa_chave='+document.form1.k17_hist.value+'&funcao_js=parent.js_mostrahist','Pesquisa',false);
  }
}
function js_mostrahist(chave,erro){
  document.form1.c50_descr.value = chave;
  if(erro==true){
    document.form1.k17_hist.focus();
    document.form1.k17_hist.value = '';
  }
}
function js_mostrahist1(chave1,chave2){
  document.form1.k17_hist.value = chave1;
  document.form1.c50_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
    db_iframe.focus();
  }else{
  	if (document.form1.z01_numcgm.value == '') {
 	 document.form1.z01_nome.value = '';
	}
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}

function js_pesquisac01_reduz(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conplanoexe_financeiro.php?ver_datalimite=1&funcao_js=parent.js_mostrareduz1|c62_reduz|c60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conplanoexe_financeiro.php?ver_datalimite=1&pesquisa_chave='+document.form1.c62_reduz.value+'&funcao_js=parent.js_mostrareduz','Pesquisa',false);
  }
}
function js_mostrareduz(chave,erro){
  document.form1.c60_descr.value = chave;
  if(erro==true){
    document.form1.c62_reduz.focus();
    document.form1.c62_reduz.value = '';
  }
}
function js_mostrareduz1(chave1,chave2){
  document.form1.debito.value = chave1;
  document.form1.debito.onchange();
  db_iframe.hide();
}
function js_pesquisac01_reduz1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?ver_datalimite=1&funcao_js=parent.js_mostrareduz2|k13_conta|k13_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?ver_datalimite=1&pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrareduz','Pesquisa',false);
  }
}
function js_mostrareduz2(chav1,chav2){
  document.form1.credito.value = chav1;
  document.form1.credito.onchange();
  db_iframe_saltes.hide();
}
</script>