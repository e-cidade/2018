<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: caixa
$clrotulo = new rotulocampo;
$cltabrec->rotulo->label();
$clrotulo->label("k02_corr");
$clrotulo->label("k79_arretipo");
$clrotulo->label("k00_descr");

$instit = db_getsession("DB_instit");
$anousu=db_getsession("DB_anousu");

if($db_opcao == 1) {
  //rotina que traz os dados da tabela numpref
  $sSql = " select numpref.k03_recjur as k02_recjur,
                   numpref.k03_recmul as k02_recmul,
                   numpref.k03_recjur as recjurerecmul,
                   mul.k02_descr   as descr_mul,
                   jur.k02_descr   as descr_jur,
                   jur.k02_descr   as descr_juremul
              from numpref
                   left join tabrec mul on mul.k02_codigo  = k03_recmul
                   left join tabrec jur on jur.k02_codigo  = k03_recjur
             where numpref.k03_anousu = {$anousu}
               and numpref.k03_instit = {$instit} ";

  $result = $clnumpref->sql_record($sSql);

  if($clnumpref->numrows == 0){
    db_msgbox("Atualizar os parâmetros numpref.");
  }else{
    db_fieldsmemory($result,0);
  }

}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk02_codigo?>">
      <?=@$Lk02_codigo?>
    </td>
    <td>
      <?
      db_input('k02_codigo',6,$Ik02_codigo,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk02_tabrectipo?>">
      <?=@$Lk02_tabrectipo?>
    </td>
    <td>
      <?
        $rsTabRecTipo = $cltabtiporec->sql_record($cltabtiporec->sql_query(null,"*","k116_sequencial",null));
        $iTabRecTipo  = pg_num_rows($rsTabRecTipo);
        $aTabRecTipo  = array("0"=>"Nenhum");
        if ($iTabRecTipo > 0){
          for ($x = 0; $x < $iTabRecTipo; $x++){
              db_fieldsmemory($rsTabRecTipo,$x);
              $aTabRecTipo[$k116_sequencial] = $k116_descricao;
          }
        }
        db_select('k02_tabrectipo',$aTabRecTipo,'true',$db_opcao," onchange='js_param_tipgruprec(true);'");
      ?>
    </td>
  </tr>
  <tr id="gruporeceita">
    <td nowrap title="<?=@$Tk02_tipo?>">
      <?=@$Lk02_tipo?>
    </td>
    <td>
      <?
      $arr =  array("N"=>"Nenhum","O"=>"Orçamentária","E"=>"Extra-orçamentária");
      db_select('k02_tipo',$arr,true,$db_opcao," onchange='js_param_tipgruprec(false);'");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk02_drecei?>">
      <?
      db_ancora(@$Lk02_drecei,"js_receita(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('codigo',15,0,true,'hidden',3);
      db_input('estrut',18,0,true,'text',3);
      ?>
      <?
      db_input('k02_drecei',50,$Ik02_drecei,true,'text',2);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk02_descr?>">
      <?=@$Lk02_descr?>
    </td>
    <td>
      <?
      db_input('k02_descr',20,$Lk02_descr,true,'text',$db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk02_codjm?>">
      <?
      db_ancora(@$Lk02_codjm,"js_pesquisak02_codjm(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('k02_codjm',4,$Ik02_codjm,true,'text',$db_opcao,"onchange='js_pesquisak02_codjm(false)'");
      db_input('k02_corr',40,$Ik02_corr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr id="receitajuros">
    <td nowrap title="<?=@$Tk02_recjur?>">
      <?
        db_ancora(@$Lk02_recjur,"js_pesquisak02_recjur(true);",$db_opcao);
      ?>

    </td>
    <td>
      <?
        db_input('k02_recjur',4,@$Ik02_recjur,true,'text',$db_opcao," onchange='js_pesquisak02_recjur(false);'");
        db_input('descr_jur',40,@$Ik02_descr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr id="receitamulta">
    <td nowrap title="<?=@$Tk02_recmul?>">
      <?
        db_ancora(@$Lk02_recmul,"js_pesquisak02_recmul(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('k02_recmul',4,@$Ik02_recmul,true,'text',$db_opcao," onchange='js_pesquisak02_recmul(false);'");
        db_input('k02_descr',40,@$Ik02_descr,true,'text',3,"","descr_mul");
      ?>
    </td>
  </tr>
  <tr id="receitadesconto" style='display: none'>
    <td nowrap title="<?=@$Tk02_recdes?>">
      <?
        db_ancora(@$Lk02_recdes,"js_pesquisak02_recdes(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('k02_recdes',10,@$Ik02_recdes,true,'text',$db_opcao," onchange='js_pesquisak02_recdes(false);'");
        db_input('k02_descr',57,@$Ik02_descr,true,'text',3,"","descr_des");
      ?>
    </td>
  </tr>
  <tr id="receitajuroemulta" style="display: none;">
    <td nowrap title="Receita de Juros e Receita de Multa">
      <?
        db_ancora("<b>Receita de Juros e Multa</b>","js_pesquisajurosemulta(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('recjurerecmul',4,@$recjurerecmul,true,'text',$db_opcao," onchange='js_pesquisajurosemulta(false);'");
        db_input('k02_descr',40,@$descr_juremul,true,'text',3,"","descr_juremul");
      ?>
    </td>
  </tr>
  <tr>
    <td title=<?=@$Tk02_limite?>>
      <b>Data Limite: </b>
    </td>
    <td>
      <?

      if(($db_opcao==2)||($db_opcao==22)){

				if(@$k02_limite!=""){
	        // Note o uso de ===.  Simples == não funcionaria como esperado
	        // por causa da posição de 'a' é 0 (primeiro) caractere.
					$pos = strpos($k02_limite, "-");
	        if ($pos == true) {

						$limite=split('-',$k02_limite);
		        $k02_limite_dia=$limite[2];
		        $k02_limite_mes=$limite[1];
		        $k02_limite_ano=$limite[0];
		      }else{

					  $limite=split('/',$k02_limite);
	          $k02_limite_dia=$limite[0];
	          $k02_limite_mes=$limite[1];
	          $k02_limite_ano=$limite[2];

					}
				}
      }
      db_inputdata('k02_limite',@$k02_limite_dia,@$k02_limite_mes,@$k02_limite_ano,true,'text',$db_opcao);
      ?>
    </td>
  </tr>
	<tr>
    <td nowrap title="<?=@$Tk79_arretipo?>">
    	 <b>
       <?
       db_ancora("Tipo para Recibo Protocolo","js_pesquisa_tipo(true);",$db_opcao);
       ?>
			 </b>
    </td>
    <td>
		<?
		  db_input('k79_arretipo',10,$Ik79_arretipo,true,'text',$db_opcao," onchange='js_pesquisa_tipo(false);'")
		?>
    <?
      db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
    ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?> onclick="return js_validar();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_onload() {

  js_param_tipgruprec(false);
  js_param_tipgruprec(true);
}

function js_pesquisajurosemulta(mostra) {
  var sFuncao = 'funcao_js=parent.CurrentWindow.corpo.iframe_tabrec';
  var recjurosemulta = document.form1.recjurerecmul.value;
  var sUrl1 = 'func_tabrec_todas.php?'+sFuncao+'.js_mostrajurosemulta1|k02_codigo|k02_descr&k02_tabrectipo=5&chave_k02_codigo='+recjurosemulta;
  var sUrl2 = 'func_tabrec_todas.php?pesquisa_chave='+recjurosemulta+'&k02_tabrectipo=5&'+sFuncao+'.js_mostrajurosemulta';

  if(mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjurmul',sUrl1,'Pesquisa',true,'0');
  }else{
    if (recjurosemulta != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjurmul',sUrl2,'Pesquisa',false,'0');
    } else {
      document.form1.recjurerecmul.value = '';
      document.form1.descr_juremul.value = '';
    }
  }
}

function js_mostrajurosemulta(chave,erro){
  if(erro==true){
    document.form1.recjurerecmul.focus();
    document.form1.recjurerecmul.value  = '';
    document.form1.descr_juremul.value  = '';
    alert(chave);
  } else {
    document.form1.descr_juremul.value          = chave;
    document.getElementById('k02_recjur').value = document.form1.recjurerecmul.value;
    document.getElementById('k02_recmul').value = document.form1.recjurerecmul.value;
    document.getElementById('descr_jur').value  = chave;
    document.getElementById('descr_mul').value  = chave;
  }
}

function js_mostrajurosemulta1(chave1,chave2){
  document.form1.recjurerecmul.value          = chave1;
  document.form1.descr_juremul.value          = chave2;
  document.getElementById('k02_recjur').value = chave1;
  document.getElementById('k02_recmul').value = chave1;
  document.getElementById('descr_jur').value  = chave2;
  document.getElementById('descr_mul').value  = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrecjurmul.hide();
}

function js_pesquisak02_codjm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjm','func_tabrecjm.php?funcao_js=parent.CurrentWindow.corpo.iframe_tabrec.js_mostratabrecjm1|k02_codjm|k02_corr','Pesquisa',true,'0');
  }else{
    if(document.form1.k02_codjm.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjm','func_tabrecjm.php?pesquisa_chave='+document.form1.k02_codjm.value+'&funcao_js=parent.CurrentWindow.corpo.iframe_tabrec.js_mostratabrecjm','Pesquisa',false);
    }
  }
}

function js_mostratabrecjm(chave,erro){
  if(erro==true){
    document.form1.k02_codjm.focus();
    document.form1.k02_codjm.value = '';
    alert(chave);
  }else{
  document.form1.k02_corr.value = chave;
  }
}

function js_mostratabrecjm1(chave1,chave2){
  document.form1.k02_codjm.value = chave1;
  document.form1.k02_corr.value = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrecjm.hide();
}

function js_receita(mostra){
  if(mostra==true){
    tipo =  document.form1.k02_tipo.value;
    if(tipo=="O"){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.CurrentWindow.corpo.iframe_tabrec.js_mostra|o70_codrec|o57_fonte|o57_descr','Pesquisa',true,'0');
    }else{
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_conplano','func_conplano_extra.php?tipo_sql=sql_query_reduz&funcao_js=parent.CurrentWindow.corpo.iframe_tabrec.js_mostra|c61_reduz|c60_estrut|c60_descr','Pesquisa',true);
    }
  }
}

function js_mostra(codigo,estrut,descr){
  document.form1.codigo.value = codigo;
  document.form1.estrut.value = estrut;
  document.form1.k02_descr.value = descr.substring(0,15);
  document.form1.k02_drecei.value = descr.substring(0,40);

  if((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_conplano){
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_conplano.hide();
  }

  if((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_orcreceita){
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_orcreceita.hide();
  }
}

function js_pesquisa_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr&k03_tipo=14','Pesquisa',true);
  }else{
     if(document.form1.k79_arretipo.value != ''){
        js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k79_arretipo.value+'&funcao_js=parent.js_mostraarretipo&k03_tipo=14','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = '';
     }
  }
}

function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.k79_arretipo.focus();
    document.form1.k79_arretipo.value = '';
  }
}

function js_mostraarretipo1(chave1,chave2){
  document.form1.k79_arretipo.value = chave1;
  document.form1.k00_descr.value    = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisak02_recjur(mostra){
  var sFuncao = 'funcao_js=parent.CurrentWindow.corpo.iframe_tabrec';
  var k02_recjur = document.form1.k02_recjur.value;
  var sUrl1 = 'func_tabrec_todas.php?'+sFuncao+'.js_mostrak02_recjur1|k02_codigo|k02_descr&k02_tabrectipo=2,5&chave_k02_codigo='+k02_recjur;
  var sUrl2 = 'func_tabrec_todas.php?pesquisa_chave='+k02_recjur+'&k02_tabrectipo=2,5&'+sFuncao+'.js_mostrak02_recjur';

  if(mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjur',sUrl1,'Pesquisa',true,'0');
  }else{
    if(k02_recjur != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecjur',sUrl2,'Pesquisa',false,'0');
    }else{
      document.form1.k02_recjur.value = '';
      document.form1.descr_jur.value  = '';
    }
  }
}

function js_mostrak02_recjur(chave,erro){
  if(erro==true){
    document.form1.k02_recjur.focus();
    document.form1.k02_recjur.value = '';
    document.form1.descr_jur.value  = '';
    alert(chave);
  }else{
    document.form1.descr_jur.value  = chave;
  }
}

function js_mostrak02_recjur1(chave1,chave2){
  document.form1.k02_recjur.value = chave1;
  document.form1.descr_jur.value  = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrecjur.hide();
}

function js_pesquisak02_recmul(mostra){
  var sFuncao = 'funcao_js=parent.CurrentWindow.corpo.iframe_tabrec';
  var k02_recmul = document.form1.k02_recmul.value;
  var sUrl1 = 'func_tabrec_todas.php?'+sFuncao+'.js_mostrak02_recmul1|k02_codigo|k02_descr&k02_tabrectipo=3,5&chave_k02_codigo='+k02_recmul;
  var sUrl2 = 'func_tabrec_todas.php?pesquisa_chave='+k02_recmul+'&k02_tabrectipo=3,5&'+sFuncao+'.js_mostrak02_recmul';

  if(mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecmul',sUrl1,'Pesquisa',true,'0');
  }else{
    if(k02_recmul != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecmul',sUrl2,'Pesquisa',false,'0');
    }else{
      document.form1.k02_recmul.value = '';
      document.form1.descr_mul.value  = '';
    }
  }
}

function js_mostrak02_recmul(chave,erro){

  if(erro==true){
    document.form1.k02_recmul.focus();
    document.form1.k02_recmul.value = '';
    document.form1.descr_mul.value  = '';
    alert(chave);
  } else {
    document.form1.descr_mul.value = chave;
  }
}

function js_mostrak02_recmul1(chave1,chave2){
  document.form1.k02_recmul.value = chave1;
  document.form1.descr_mul.value  = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrecmul.hide();
}


function js_pesquisak02_recdes(mostra){
  var sFuncao = 'funcao_js=parent.CurrentWindow.corpo.iframe_tabrec';
  var k02_recdes = document.form1.k02_recdes.value;
  var sUrl1 = 'func_tabrec_todas.php?'+sFuncao+'.js_mostrak02_recdes1|k02_codigo|k02_descr&k02_tabrectipo=3,5&chave_k02_codigo='+k02_recdes;
  var sUrl2 = 'func_tabrec_todas.php?pesquisa_chave='+k02_recdes+'&k02_tabrectipo=3,5&'+sFuncao+'.js_mostrak02_recdes';

  if(mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecdes',sUrl1,'Pesquisa',true,'0');
  }else{
    if(k02_recdes != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrecdes',sUrl2,'Pesquisa',false,'0');
    }else{
      document.form1.k02_recdes.value = '';
      document.form1.descr_des.value  = '';
    }
  }
}

function js_mostrak02_recdes(chave,erro){

  if(erro==true){
    document.form1.k02_recdes.focus();
    document.form1.k02_recdes.value = '';
    document.form1.descr_des.value  = '';
    alert(chave);
  } else {
    document.form1.descr_des.value = chave;
  }
}

function js_mostrak02_recdes1(chave1,chave2){
  document.form1.k02_recdes.value = chave1;
  document.form1.descr_des.value  = chave2;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrecdes.hide();
}



function js_param_tipgruprec(chave){
  var parametro      = chave;
  var k02_tabrectipo = document.form1.k02_tabrectipo.value;
  var k02_tipo       = document.form1.k02_tipo.value;

  if (parametro == true) {
	  if (k02_tabrectipo != 0 && k02_tabrectipo != 1) {
	     document.getElementById('gruporeceita').style.display      = 'none';
       document.getElementById('receitajuros').style.display      = '';
       document.getElementById('receitamulta').style.display      = '';
       document.getElementById('receitadesconto').style.display   = 'none';
       document.getElementById('receitajuroemulta').style.display = 'none';
	     document.form1.k02_tipo.value = 'O';
	  } else {
	     document.getElementById('gruporeceita').style.display      = '';
	     document.getElementById('receitajuros').style.display      = '';
       document.getElementById('receitamulta').style.display      = '';
       document.getElementById('receitadesconto').style.display   = 'none';
       document.getElementById('receitajuroemulta').style.display = 'none';
	  }

    if (k02_tabrectipo == 5) {
       document.getElementById('receitajuros').style.display      = 'none';
       document.getElementById('receitamulta').style.display      = 'none';
       document.getElementById('receitadesconto').style.display   = 'none';
       document.getElementById('receitajuroemulta').style.display = '';
    }

  } else {
    if (k02_tipo == 'E') {
      document.getElementById('receitajuros').style.display      = 'none';
      document.getElementById('receitamulta').style.display      = 'none';
      document.getElementById('receitadesconto').style.display   = 'none';
      document.getElementById('receitajuroemulta').style.display = 'none';
    } else {
      document.getElementById('receitajuros').style.display      = '';
      document.getElementById('receitamulta').style.display      = '';
      document.getElementById('receitadesconto').style.display   = 'none';
      document.getElementById('receitajuroemulta').style.display = 'none';
    }
  }
}

function js_validar(){
 var k02_tabrectipo = document.form1.k02_tabrectipo.value;
 var k02_tipo       = document.form1.k02_tipo.value;

	 if (k02_tabrectipo == 0) {
	  alert("Nenhum Tipo de Receita Informado!");
	  return false;
	 }

	 if (k02_tipo == 'N') {
	  alert("Nenhum Grupo de Receita Informado!");
	  return false;
	 }

  if (k02_tipo == 'O') {
     var k02_recjur = document.form1.k02_recjur.value;
     var k02_recmul = document.form1.k02_recmul.value;

		 if (k02_recjur == '') {
		  alert("Nenhuma Receita de Juros Informada!");
		  return false;
		 }

		 if (k02_recmul == '') {
		  alert("Nenhuma Receita de Multa Informada!");
		  return false;
		 }
  }
}

function js_pesquisa(){

  var sUrl = 'func_tabrec_todas.php?funcao_js=parent.CurrentWindow.corpo.iframe_tabrec.js_preenchepesquisa|k02_codigo|k02_estorc|k02_descr';
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_tabrec','db_iframe_tabrec',sUrl,'Pesquisa',true,'0');
}

function js_preenchepesquisa(codigo,estrut,descr) {

  document.form1.codigo.value     = codigo;
  document.form1.estrut.value     = estrut;
  document.form1.k02_descr.value  = descr.substring(0,15);
  document.form1.k02_drecei.value = descr.substring(0,40);

  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_tabrec.db_iframe_tabrec.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+codigo";
    }
  ?>
}

document.getElementById('k02_codigo').style.width     = 92;
document.getElementById('k02_tabrectipo').style.width = 147;
document.getElementById('k02_tipo').style.width       = 147;
document.getElementById('estrut').style.width         = 146;
document.getElementById('k02_drecei').style.width     = 370;
document.getElementById('k02_descr').style.width      = 519;
document.getElementById('k02_codjm').style.width      = 92;
document.getElementById('k02_corr').style.width       = 424;
document.getElementById('k02_recjur').style.width     = 92;
document.getElementById('descr_jur').style.width      = 424;
document.getElementById('k02_recmul').style.width     = 92;
document.getElementById('descr_mul').style.width      = 424;
document.getElementById('recjurerecmul').style.width  = 92;
document.getElementById('descr_juremul').style.width  = 424;
document.getElementById('k79_arretipo').style.width   = 92;
document.getElementById('k00_descr').style.width      = 424;
document.getElementById('k02_recdes').style.width     = 92;
document.getElementById('descr_des').style.width      = 424
</script>
