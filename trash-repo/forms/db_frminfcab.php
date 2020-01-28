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

//MODULO: inflatores
$clinfcab->rotulo->label();
$clinfcor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("i01_descr");
?>
<form name="form1" method="post" action="">
    <table border="0">
      <tr> 
        <td nowrap title="<?=@$Ti03_codigo?>"> 
          <?=@$Li03_codigo?>
        </td>
        <td> 
          <?
db_input('i03_codigo',6,$Ii03_codigo,true,'text',3,"")
?>
        <td>
         <td nowrap title="<?=@$Ti04_dtoper?>"> 
          <?=@$Li04_dtoper?>
        </td>
        <td> 
          <?
$i04_dtoper_dia = date("d",db_getsession("DB_datausu")); 
$i04_dtoper_mes = date("m",db_getsession("DB_datausu"));
$i04_dtoper_ano = date("Y",db_getsession("DB_datausu"));
db_inputdata('i04_dtoper',@$i04_dtoper_dia,@$i04_dtoper_mes,@$i04_dtoper_ano,true,'text',$db_opcao)
?>
        <td>
      </tr> 
      <tr> 
        <td nowrap title="<?=@$Ti03_descr?>"> 
          <?=@$Li03_descr?>
        </td>
        <td> 
          <?
db_input('i03_descr',40,$Ii03_descr,true,'text',$db_opcao,"")
?>
        <td> 
         <td nowrap title="<?=@$Ti04_dtvenc?>"> 
          <?=@$Li04_dtvenc?>
        </td>
        <td>
          <?
$i04_dtvenc_dia = date("d",db_getsession("DB_datausu")); 
$i04_dtvenc_mes = date("m",db_getsession("DB_datausu"));
$i04_dtvenc_ano = date("Y",db_getsession("DB_datausu"));
db_inputdata('i04_dtvenc',@$i04_dtvenc_dia,@$i04_dtvenc_mes,@$i04_dtvenc_ano,true,'text',$db_opcao)
?>
        <td>

      </tr> 
      <tr> 
        <td nowrap title="<?=@$Ti03_numcgm?>"> 
          <?
       db_ancora(@$Li03_numcgm,"js_pesquisai03_numcgm(true);",$db_opcao);
       ?>
        </td>
        <td> 
          <?
db_input('i03_numcgm',8,$Ii03_numcgm,true,'text',$db_opcao," onchange='js_pesquisai03_numcgm(false);'")
?>
          <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
        <td> 
        <td nowrap title="<?=@$Ti04_valor?>"> 
          <?
       echo $Li04_valor;
       ?>
        </td>
        <td> 
          <?
$i04_valor = 1;
db_input('i04_valor',15,$Ii04_valor,true,'text',$db_opcao,'')
       ?>
        <td> 
      </tr> 
      <tr> 
        <td nowrap title="<?=@$Ti03_dtbase?>"> 
          <?=@$Li03_dtbase?>
        </td>
        <td> 
          <?
$i03_dtbase_dia = date("d",db_getsession("DB_datausu")); 
$i03_dtbase_mes = date("m",db_getsession("DB_datausu"));
$i03_dtbase_ano = date("Y",db_getsession("DB_datausu"));
db_inputdata('i03_dtbase',@$i03_dtbase_dia,@$i03_dtbase_mes,@$i03_dtbase_ano,true,'text',$db_opcao,"")
?>
        <td> 

        <td nowrap title="<?=@$Ti04_obs?>"> 
          <?=@$Li04_obs?>
        </td>
        <td> 
          <?
db_input('i04_obs',20,$Ii04_obs,true,'text',$db_opcao,'')
?>
        <td> 
      </tr> 


      <tr>
        <td nowrap>
        </td>
        <td>
        <td>

        <td nowrap title="<?=@$Ti04_receit?>">
          <?
       echo @$Li04_receit;
       ?>
        </td>
	
        <td>
          <?
		  $cltabrec = new cl_tabrec;
          db_selectrecord('i04_receit',$cltabrec->sql_record($cltabrec->sql_query("","k02_codigo#k02_drecei","k02_codigo","")),true,4,"","","","","",1);
          ?>
	
	<td>
     </tr>  
      
      <tr>
        <td nowrap>
          <input type="button" name="gravar"    id="gravar"    value="Gravar"    onclick="js_atualizadados();">
          <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisadados();">
        </td>
        <td>
          <input type="button" name="imprimir"  id="imprimir"  value="Imprimir" onclick="js_imprimir('inf2_atuavalores001.php?i03_codigo=<?=$i03_codigo?>');">
        <td>
        <td nowrap >
        </td>
        <td>
          <input type="button" name="calcula" id="calcula" value="Calcular" onclick="js_atualizavalores();">
        <td>
     </tr>  
    </table>
    <table width="100%" border="0" cellspacing="0">
      <tr> 
        <td>
		<iframe name="valores" id="valores" src="inf4_atuavalores002.php?<?=(isset($i03_codigo)?"i03_codigo=".$i03_codigo:"")?>" height="400" width="750"></iframe>
	</td>
      </tr>
    </table>
</form>
<script>

function js_atualizavalores(){
  db_iframe.jan.location.href = 'inf4_atuavalores003.php?dtoper='+document.form1.i04_dtoper_ano.value+'-'+document.form1.i04_dtoper_mes.value+'-'+document.form1.i04_dtoper_dia.value+'&dtvenc='+document.form1.i04_dtvenc_ano.value+'-'+document.form1.i04_dtvenc_mes.value+'-'+document.form1.i04_dtvenc_dia.value+'&valor='+document.form1.i04_valor.value+'&receit='+document.form1.i04_receit.value+'&dtbase='+document.form1.i03_dtbase_ano.value+'-'+document.form1.i03_dtbase_mes.value+'-'+document.form1.i03_dtbase_dia.value+'&receit='+document.form1.i04_receit.value;
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
} 


function js_pesquisadados(){
  js_OpenJanelaIframe("top.corpo","db_iframe_pesquisa","func_infcab.php?funcao_js=parent.js_pesquisadadosretorno|i03_codigo","Pesquisa Valores Atualizados");
}

function js_imprimir(programa){
//  js_OpenJanelaIframe("top.corpo","db_iframe_pesquisa","func_infcab.php?funcao_js=parent.js_pesquisadadosretorno|i03_codigo","Pesquisa Valores Atualizados");
    jandb = window.open(programa,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jandb.moveTo(0,0);
}

function js_pesquisadadosretorno(chave){
   db_iframe_pesquisa.hide();
   location.href = "inf4_atuavalores001.php?i03_codigo="+chave;
}

function js_atualizadados(){

  if ( document.form1.i03_descr.value == "" ) {
     alert('Voce deve preencher o campo Descricao');
     document.form1.i03_descr.focus();
     return false;
  }

  if ( document.form1.i03_numcgm.value == "" ) {
     alert('Voce deve preencher o campo Número do CGM');
     document.form1.i03_numcgm.focus();
     return false;
  }
  
  var tab = valores.document.getElementById('tab');

  if ( tab.rows.length == 1 ) {
    alert('Voce deve incluir algum lancamento de valores!');
    return false;
  }

  if (document.form1.i03_codigo.value != "" ) {
     valores.document.form1.i03_codigo.value  = document.form1.i03_codigo.value;
  }
  
  valores.document.form1.i03_descr.value   = document.form1.i03_descr.value;
  valores.document.form1.i03_numcgm.value  = document.form1.i03_numcgm.value;
  valores.document.form1.i03_dtbase.value  = document.form1.i03_dtbase_ano.value + '-' + document.form1.i03_dtbase_mes.value + '-' + document.form1.i03_dtbase_dia.value;

  for(i=1;i<tab.rows.length;i++){
     obj = valores.document.createElement("input");
     obj.setAttribute("name","i04_dados"+i);
     obj.setAttribute("type","text");
     obj.setAttribute("value",tab.rows[i].cells[0].innerHTML + "#" + tab.rows[i].cells[1].innerHTML + "#" + tab.rows[i].cells[2].innerHTML + "#" + tab.rows[i].cells[3].innerHTML + "#" + tab.rows[i].cells[4].innerHTML + "#" + tab.rows[i].cells[5].innerHTML + "#" + tab.rows[i].cells[6].innerHTML + "#" + tab.rows[i].cells[7].innerHTML + "#" + tab.rows[i].cells[8].innerHTML);
     valores.document.form1.appendChild(obj);
  }

  obj = valores.document.createElement("input");
  obj.setAttribute("name","i04_linhas");
  obj.setAttribute("type","text");
  obj.setAttribute("value",tab.rows.length-1);
  valores.document.form1.appendChild(obj);
  
  valores.document.form1.submit();

}

function js_pesquisai03_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.i03_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.i03_numcgm.focus(); 
    document.form1.i03_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.i03_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_infcab.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>