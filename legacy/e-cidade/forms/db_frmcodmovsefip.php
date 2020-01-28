<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: pessoal
$clrotulo = new rotulocampo;
$clcodmovsefip->rotulo->label();
$clrotulo->label("z01_nome");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>RECOLHIMENTO</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="Código do recolhimento">
              <b>Código:</b>
            </td>
            <td> 
              <?
              $codrec = "115";
              db_input('codrec',10,1,true,'text',3,"")
              ?>
            </td>
            <td nowrap align="right" title="Ano / Mês de competência">
              <b>Ano / Mês:</b>
            </td>
            <td nowrap>
              <?
              if(!isset($anousu)){
                $anousu = db_anofolha();
              }
              db_input('r66_anousu',4,$Ir66_anousu,true,'text',1,"onchange='js_controla_anomes(\"a\");'","anousu");
              ?>
              <b>/</b>
              <?
              if(!isset($mesusu)){
                $mesusu = db_mesfolha();
              }
              db_input('r66_mesusu',2,$Ir66_mesusu,true,'text',1,"onchange='js_controla_anomes(\"m\");'","mesusu");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Índice recolhimento FGTS">
              <b>Índice FGTS:</b>
            </td>
            <td> 
              <?
              $indrecfgts = 1;
              $arr_indrecfgts = array("0"=>"Nenhum","1"=>"GFIP no prazo","2"=>"GFIP em atraso");
              db_select('indrecfgts',$arr_indrecfgts,true,1,"onchange='js_verindices(\"dtrecfgts\",this.value, false);'");
              ?>
            </td>
            <td nowrap align="right" title="Data recolhimento FGTS">
              <b>Data FGTS:</b>
            </td>
            <td> 
              <?
              db_inputdata("dtrecfgts", @$dtrecfgts_dia, @$dtrecfgts_mes, @$dtrecfgts_ano, true, 'text',1); 
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Índice recolhimento INSS">
              <b>Índice INSS:</b>
            </td>
            <td> 
              <?
              $indrecinss = 1;
              $arr_indrecinss = array("0"=>"Não gera GPS","1"=>"GPS no prazo","2"=>"GPS em atraso");
              db_select('indrecinss',$arr_indrecinss,true,1,"onchange='js_verindices(\"dtrecinss\",this.value, true);'");
              ?>
            </td>
            <td nowrap align="right" title="Data recolhimento INSS">
              <b>Data INSS:</b>
            </td>
            <td> 
              <?
              db_inputdata("dtrecinss", @$dtrecinss_dia, @$dtrecinss_mes, @$dtrecinss_ano, true, 'text',1); 
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Índice recolhimento atraso INSS">
              <b>Atraso INSS:</b>
            </td>
            <td> 
              <?
              db_input('indatrasoinss',10,1,true,'text',1,"","")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>CONTATO</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="Nome do contato">
              <b>Nome:</b>
            </td>
            <td> 
              <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',1,"","contato")
              ?>
            </td>
            <td nowrap align="right" title="Fone">
              <b>Fone:</b>
            </td>
            <td> 
              <?
              db_input('fone',10,1,true,'text',1,"","")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>MAIS DADOS</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="Alteração de endereço">
              <b>Alteração de endereço:</b>
            </td>
            <td> 
              <?
              $alteraender = "N";
              $arr_alteraender = array("S"=>"Sim","N"=>"Não");
              db_select('alteraender',$arr_alteraender,true,1,"");
              ?>
            </td>
            <td nowrap align="right" title="Alteração de CNAE">
              <b>Alteração de CNAE:</b>
            </td>
            <td> 
              <?
              $alteracnae = "P";
              $arr_alteracnae = array("S"=>"Sim","N"=>"Não","A"=>"Alt. Preponderante","P"=>"Não Alt. Preponderante");
              db_select('alteracnae',$arr_alteracnae,true,1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Código de terceiros">
              <b>Código de terceiros:</b>
            </td>
            <td> 
              <?
              $codterceiro = "0000";
              db_input('codterceiro',10,1,true,'text',1,"","")
              ?>
            </td>
            <td nowrap align="right" title="Código CNAE fiscal">
              <b>Código CNAE fiscal:</b>
            </td>
            <td> 
              <?
              $cnae = "8411600";
              db_input('cnae',10,1,true,'text',1,"","")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Aliquota SAT">
              <b>Aliquota SAT:</b>
            </td>
            <td> 
              <?
              db_input('aliqsat',2,1,true,'text',1,"","")
              ?>
            </td>
            <td nowrap align="right" title="Código GPS">
              <b>Código GPS:</b>
            </td>
            <td> 
              <?
              $codgps = "2402";
              db_input('codgps',10,1,true,'text',1,"","")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>TABELAS DE PREVIDÊNCIA</b></legend>
        <table width="100%">
          <?
	  db_sel_cfpess(db_anofolha(), db_mesfolha(), "r11_tbprev , r11_mes13 ");
	  $mesant = $mesusu;
	  if($mesusu > 12){
	     $mesusu =  $r11_mes13;
	  }
          $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null,null," distinct cast(r33_codtab as integer)-2 as r33_codtab,r33_nome","r33_codtab","r33_codtab between 3 and 6 and r33_mesusu=$mesusu and r33_anousu=$anousu and r33_instit = ".db_getsession('DB_instit')));
	  $mesusu = $mesant; 
	  for($i=0, $cont = 1; $i<$clinssirf->numrows; $i++){
	    db_fieldsmemory($result_tbprev, $i);
	    if(($i % 2) == 0 || $i == 0){
              echo "<tr>";
	    }

            echo "
	          <td nowrap align='center' title='".$r33_nome."' width='10%'>
                    <input name='tab_".$r33_codtab."' value='".$r33_codtab."' type='checkbox' ".(($r33_codtab == $r11_tbprev)?" checked ":"").">
		  </td>
	          <td nowrap align='left' title='".$r33_nome."' width='40%'>
		    <b>".$r33_nome."</b>
		  </td>
	         ";

	    if($cont == 2 || ($i + 1) == $clinssirf->numrows){
              echo "</tr>";
	      $cont = 0;
	    }
	    $cont ++;
	  }
          db_input('checkboxes',10,1,true,'hidden',1,"","");
          ?>
	</table>
      </fieldset>
    </td>
  </tr>
<tr>
    <td align="center">
      <fieldset>
        <legend><b>CNPJ</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="CNPJ">
              <b>CNPJ:</b>
            </td>
            <td>
              <?
$instit=db_getsession("DB_instit");
$sql = "select distinct z01_numcgm,z01_cgccpf||'-'||z01_nome as z01_nome from rhlota inner join cgm on rhlota.r70_numcgm=cgm.z01_numcgm  where r70_instit=$instit;";
$result= pg_query($sql);
db_selectrecord("r70_numcgm", $result, true     , @$db_opcao, "",           "",          "",       "0", "","2");

              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>



</table>
<input name="gerar" type="submit" id="gerar" value="Gerar SEFIP" onblur='js_tabulacaoforms("form1","anousu",true,1,"anousu",true);' onclick='return js_verificacampos();'>
</center>
</form>
<script>
function js_verificacampos(){

  if(document.form1.anousu.value == ""){
    alert("Informe o ano de competência.");
    document.form1.anousu.focus();
  }else if(document.form1.mesusu.value == ""){
    alert("Informe o mês de competência.");
    document.form1.mesusu.focus();
  }else if(document.form1.contato.value == ""){
    alert("Informe o nome do contato.");
    document.form1.contato.focus();
  }else if(document.form1.fone.value == ""){
    alert("Informe o fone de contato.");
    document.form1.fone.focus();
  }else{
    virgula = "";
    document.form1.checkboxes.value = "";
    for(i=0; i<document.form1.length; i++){
      if(document.form1.elements[i].type == 'checkbox'){
	if(document.form1.elements[i].checked == true){
	  document.form1.checkboxes.value += virgula + document.form1.elements[i].value;
          virgula = ",";
	}
      }
    }
    if(document.form1.checkboxes.value != ""){
      return true;
    }
    alert("Selecione uma tabela de previdência.");
  }

  return false;
}
function js_verindices(campo,valor,indatrasoinss){
  if(valor == 1){
    eval("document.form1."+campo+"_dia.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_mes.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_ano.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_dia.readOnly = true;");
    eval("document.form1."+campo+"_mes.readOnly = true;");
    eval("document.form1."+campo+"_ano.readOnly = true;");
    eval("document.form1.dtjs_"+campo+".disabled = true;");
    if(indatrasoinss){
      document.form1.indatrasoinss.readOnly = true;
      document.form1.indatrasoinss.style.backgroundColor='#DEB887';
    }
    js_tabulacaoforms("form1","anousu",false,1,"anousu",false);
  }else{
    eval("document.form1."+campo+"_dia.style.backgroundColor='';");
    eval("document.form1."+campo+"_mes.style.backgroundColor='';");
    eval("document.form1."+campo+"_ano.style.backgroundColor='';");
    eval("document.form1."+campo+"_dia.readOnly = false;");
    eval("document.form1."+campo+"_mes.readOnly = false;");
    eval("document.form1."+campo+"_ano.readOnly = false;");
    eval("document.form1.dtjs_"+campo+".disabled = false;");
    if(indatrasoinss){
      document.form1.indatrasoinss.readOnly = false;
      document.form1.indatrasoinss.style.backgroundColor='';
    }
    js_tabulacaoforms("form1",campo+"_dia",true,1,campo+"_dia",true);
  }
}
function js_controla_anomes(opcao){
  anodig = new Number(document.form1.anousu.value);
  mesdig = new Number(document.form1.mesusu.value);
  anofol = new Number("<?=db_anofolha()?>");
  mesfol = new Number("<?=db_mesfolha()?>");
  erro = 0;
  if(mesdig > 13){
    alert("Usuário:\n\nMês inválido. Verifique.");
    erro ++;
  }else{
    if((anodig.valueOf() > anofol.valueOf()) || (anodig.valueOf() == anofol.valueOf() && mesdig.valueOf() > mesfol.valueOf() && mesdig != 13)){
      alert("Usuário:\n\nAno/Mês digitado maior que o corrente da folha. Verifique.");
      erro ++;
    }
  }
  if(erro > 0){
    if(opcao == "a"){
      document.form1.anousu.value = "";
      document.form1.anousu.focus();
    }else{
      document.form1.mesusu.value = "";
      document.form1.mesusu.focus();
    }
  }
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_codmovsefip','func_codmovsefip.php?funcao_js=parent.js_preenchepesquisa|r66_anousu|r66_mesusu|r66_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_codmovsefip.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
js_verindices("dtrecfgts",1, false);
js_verindices("dtrecinss",1, true);
</script>