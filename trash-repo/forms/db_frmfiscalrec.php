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

//MODULO: fiscal
$clfiscalrec->rotulo->label();
$clfiscalrec1 = new cl_fiscalrec;
$clrotulo = new rotulocampo;
$clrotulo->label("y30_data");
$clrotulo->label("k02_descr");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($receita) && $receita != "" && isset($valor) && $valor != ""){
  echo "<script>parent.iframe_receitas.location.href = 'fis1_fiscalrec002.php?y42_codnoti=$y42_codnoti&y42_receit=$receita&valor=$valor&y42_descr=$descr&abas=1&db_opcao=Alterar'</script>";
  exit;
} else if(isset($excluir)) {
  echo "<script>parent.iframe_receitas.location.href = 'fis1_fiscalrec003.php?y42_codnoti=$y42_codnoti&y42_receit=$receita&abas=1&db_opcao=Excluir'</script>";
  exit;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty42_codnoti?>">
       <?=@$Ly42_codnoti?>
    </td>
    <td> 
<?
db_input('y42_codnoti',20,$Iy42_codnoti,true,'text',3,"")
?>
       <?
db_input('y30_data',10,$Iy30_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty42_receit?>">
       <?
       db_ancora(@$Ly42_receit,"js_pesquisay42_receit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y42_receit',4,$Iy42_receit,true,'text',$db_opcao," onchange='js_pesquisay42_receit(false);'")
?>
       <?
db_input('k02_descr',15,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty42_descr?>">
       <?=@$Ly42_descr?>
    </td>
    <td> 
<?
db_input('y42_descr',50,$Iy42_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty42_valor?>">
       <?=@$Ly42_valor?>
    </td>
    <td> 
<?
db_input('y42_valor',10,$Iy42_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <?
    if(isset($chavepesquisa1) && $chavepesquisa1 != ""){
      $where = " y42_receit <> $chavepesquisa1";
    }else{
      $where = "";
    }
    
    $result = $clfiscalrec1->sql_record($clfiscalrec1->sql_query($y42_codnoti,"","y42_codnoti,y42_receit,y42_descr,y42_valor","","$where"));
    if($clfiscalrec1->numrows > 0){
      echo "<script>
            function js_ponto(obj){
	      valor = new String(obj.value);
	      if(valor.indexOf(',') != -1){
	        alert('As casas decimais do valor devem ser separadas por ponto');
		obj.select();
	      }
	    }
            </script>";
      echo "<table cellpadding='0' cellspacing='2'  width='700'>";
        echo "<input type='hidden' name='descr' value=''>";
        echo "<input type='hidden' name='receita' value=''>";
        echo "<input type='hidden' name='valor' value=''>";
      for($x=0;$x<$clfiscalrec1->numrows;$x++){
	if($x == 0){
	  echo "<tr bgcolor='#AACCCC' nowrap>
	          <td align='center'><strong>RECEITA</strong></td>
	          <td align='center'><strong>DESCRIÇÃO</strong></td>
	          <td align='center' width='100'><strong>VALOR</strong></td>
	          <td align='center' width='100'><strong>OPÇÕES</strong></td>
	       </tr>";	
	}   
	db_fieldsmemory($result,$x);
	echo "<tr bgcolor='#CCDDCC' nowrap>
	        <td align='center'>$y42_receit</td>
	        <td align='center'>$y42_descr</td>
	        <td align='center' width='100'><input type='text' style='text-align:right' name='valores$x' value='$y42_valor' size='10' maxlength='10' onChange=\"document.form1.valor.value=this.value\" onKeyUp='js_ponto(this)'></td>
	        <td align='center' width='100' nowrap><input style='font-size:9px;height:15px;width:40px;' type='submit' name='alterar$x' value='Alterar' onClick=\"document.form1.receita.value='$y42_receit';document.form1.descr.value='$y42_descr'\">&nbsp;&nbsp;<input type='submit' name='excluir' value='Excluir' onClick=\"document.form1.receita.value='$y42_receit'\" style='font-size:9px;height:15px;width:40px;' ></td>
	      </tr>";	
      }
      echo "</table>";
    }else{
      echo "<script>parent.document.formaba.receitas.disabled=true;</script>";
    }
    ?>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y42_receit",true,1,"y42_receit",true);
}
function js_pesquisay42_codnoti(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?funcao_js=parent.js_mostrafiscal1|y30_codnoti|y30_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_fiscal','func_fiscal.php?pesquisa_chave='+document.form1.y42_codnoti.value+'&funcao_js=parent.js_mostrafiscal','Pesquisa',false);
  }
}
function js_mostrafiscal(chave,erro){
  document.form1.y30_data.value = chave; 
  if(erro==true){ 
    document.form1.y42_codnoti.focus(); 
    document.form1.y42_codnoti.value = ''; 
  }
}
function js_mostrafiscal1(chave1,chave2){
  document.form1.y42_codnoti.value = chave1;
  document.form1.y30_data.value = chave2;
  db_iframe_fiscal.hide();
}
function js_pesquisay42_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y42_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.y42_receit.focus(); 
    document.form1.y42_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y42_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_fiscalrec','func_fiscalrec.php?funcao_js=parent.js_preenchepesquisa|y42_codnoti|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_fiscalrec.hide();
}
</script>