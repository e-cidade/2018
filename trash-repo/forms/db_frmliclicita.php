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


$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc50_descr");
$clrotulo->label("l34_protprocesso");
$clrotulo->label("nome");
$clrotulo->label("l03_usaregistropreco");
$clrotulo->label("p58_numero");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
if ($db_opcao == 1) {
	
	/*
	 * verifica na tabela licitaparam se deve utilizar processo do sistema
	 */
  $oParamLicicita = db_stdClass::getParametro('licitaparam', array(db_getsession("DB_instit")));
 
  if(isset($oParamLicicita[0]->l12_escolheprotocolo) && $oParamLicicita[0]->l12_escolheprotocolo == 't') {
  	$lprocsis = 's';  	
  } else {
  	$lprocsis = 'n';
  }
  
  /*
   * verifica se existe apenas 1 cl_liclocal
   */
  $oLicLocal = new cl_liclocal();
  $rsLicLocal = $oLicLocal->sql_record($oLicLocal->sql_query_file());  
  if( $oLicLocal->numrows == 1 ) {
  	db_fieldsmemory($rsLicLocal,0);
  	$l20_liclocal = $l26_codigo;
  }

  /*
   * verifica se existe apenas 1 cl_liccomissao
   */  
  $oLicComissao = new cl_liccomissao();
  $rsLicComissao = db_query($oLicComissao->sql_query_file());
  if( pg_num_rows($rsLicComissao) == 1 ) {
  	db_fieldsmemory($rsLicComissao,0);
  	$l20_liccomissao = $l30_codigo;
  }  
  
}

?>

<style type="text/css">
.fieldsetinterno {
		border:0px;
		border-top:2px groove white;
		margin-top:10px;
		
}
fieldset table tr > td {
		width: 180px;
		white-space: nowrap
 }  
</style>


<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top:25px;">
<tr><td> 

<fieldset>
<legend><strong>Licitação</strong></legend>

<fieldset style="border:0px;">

<table border="0">
 <tr>
   <td nowrap title="<?=@$Tl20_codigo?>">
     <?=@$Ll20_codigo?>
   </td>
   <td> 
     <?
       db_input('l20_codigo',10,$Il20_codigo,true,'text',3,"");
       if ($db_opcao == 1 || $db_opcao == 11){
          $l20_correto = 'f';
       }
       db_input("l20_correto",1,"",true,"hidden",3);
       if ($db_botao == false && @$l20_correto == 't'){
     ?>
    &nbsp;&nbsp;<font color="#FF0000"><b>Licitação já julgada</b></font>
     <?
       }
     ?>
   </td>
 </tr>
 <tr>
   <td nowrap title="<?=@$Tl20_edital?>">
     <?=@$Ll20_edital?>
   </td>
   <td>
     <?
       db_input('l20_edital',10,$Il20_edital,true,'text',3,"");
     ?>
   </td>
 </tr>
 <tr>
    <td nowrap title="<?=@$Tl20_codtipocom?>">
      <b>
       <?
         db_ancora("Modalidade :","js_pesquisal20_codtipocom(true);",3);
       ?>
      </b>
    </td>
    <td> 
      <?
        $result_tipo=$clcflicita->sql_record($clcflicita->sql_query_numeracao(null,"l03_codigo,l03_descr", null, "l03_instit = " . db_getsession("DB_instit")));
        if ($clcflicita->numrows==0){
		      db_msgbox("Nenhuma Modalidade cadastrada!!");
		      $result_tipo="";
		      $db_opcao=3;
		      $db_botao = false;
		      db_input("l20_codtipocom",10,"",true,"text");
		      db_input("l20_codtipocom",40,"",true,"text");
        } else {
          db_selectrecord("l20_codtipocom",@$result_tipo,true,$db_opcao,"js_mostraRegistroPreco()");
          if (isset($l20_codtipocom)&&$l20_codtipocom!=""){
            echo "<script>document.form1.l20_codtipocom.selected=$l20_codtipocom;</script>";
          }
        }
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl20_numero?>">
        <?=@$Ll20_numero?>
    </td>
    <td>
        <?
          db_input('l20_numero',10,$Il20_numero,true,'text',3,"");
        ?>
   </td>
 </tr>

 <tr>
    <td nowrap title="<?=@$Tl20_id_usucria?>">
       <?
       db_ancora(@$Ll20_id_usucria,"js_pesquisal20_id_usucria(true);",3);
       ?>
    </td>
    <td> 
      <?
        $usuario=db_getsession("DB_id_usuario");
        $result_usuario=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($usuario));
        if ($cldb_usuarios->numrows>0){
          	db_fieldsmemory($result_usuario,0);
        }
        $l20_id_usucria=$id_usuario;
        db_input('l20_id_usucria',10,$Il20_id_usucria,true,'text',3," onchange='js_pesquisal20_id_usucria(false);'")
      ?>
      <?
       db_input('nome',45,$Inome,true,'text',3,'')
      ?>
   </td>
 </tr>
</table>
</fieldset>

<fieldset class="fieldsetinterno">
<legend><strong>Datas</strong></legend>
<table>
 <tr>
    <td nowrap title="<?=@$Tl20_datacria?>">
       <?=@$Ll20_datacria?>
    </td>
    <td> 
       <?
         if(!isset($l20_datacria)) { 
           $l20_datacria_dia=date('d',db_getsession("DB_datausu"));
           $l20_datacria_mes=date('m',db_getsession("DB_datausu"));
           $l20_datacria_ano=date('Y',db_getsession("DB_datausu"));
         }
         db_inputdata("l20_datacria",@$l20_datacria_dia,@$l20_datacria_mes,@$l20_datacria_ano,true,'text',$db_opcao);
       ?>
       <?=@$Ll20_horacria?>
       <?
         if ($db_opcao == 1 || $db_opcao == 11){
             $l20_horacria=db_hora();
         }
         db_input('l20_horacria',5,$Il20_horacria,true,'text',$db_opcao,"");
       ?>
    </td>
 </tr>
 
  <tr>
    <td nowrap title="<?=@$Tl20_dtpublic?>">
       <?=@$Ll20_dtpublic?>
    </td>
    <td> 
       <?
         db_inputdata('l20_dtpublic',@$l20_dtpublic_dia,@$l20_dtpublic_mes,@$l20_dtpublic_ano,true,'text',$db_opcao,"");
       ?>
    </td>
 </tr>

 <tr>
    <td nowrap title="<?=@$Tl20_dataaber?>">
       <?=@$Ll20_dataaber?>
    </td>
    <td> 
       <?
         db_inputdata('l20_dataaber',@$l20_dataaber_dia,@$l20_dataaber_mes,@$l20_dataaber_ano,true,'text',$db_opcao,"");
       ?>
       <?=@$Ll20_horaaber?>
       <?
        db_input('l20_horaaber',5,$Il20_horaaber,true,'text',$db_opcao,"");
       ?>
   </td>
 </tr>

</table>

</fieldset>

<fieldset class="fieldsetinterno">
<legend><b>Outras Informações</b></legend>

<table>
 <tr>
    <td nowrap title="<?=@$Tl20_local?>">
       <?=@$Ll20_local?>
    </td>
    <td> 
       <?
        db_textarea('l20_local',0,57,$Il20_local,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>

 <tr>
    <td nowrap title="<?=@$Tl20_objeto?>">
       <?=@$Ll20_objeto?>
    </td>
    <td> 
       <?
        db_textarea('l20_objeto',0,57,$Il20_objeto,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>
 
 <tr>
    <td nowrap title="<?=@$Tl20_localentrega?>">
       <?=@$Ll20_localentrega?>
    </td>
    <td> 
       <?
        db_textarea('l20_localentrega',0,57,$Il20_localentrega,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>
 <tr>
    <td nowrap title="<?=@$Tl20_prazoentrega?>">
       <?=@$Ll20_prazoentrega?>
    </td>
    <td> 
       <?
        db_textarea('l20_prazoentrega',0,57,$Il20_prazoentrega,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>
  <tr>
    <td nowrap title="<?=@$Tl20_condicoespag?>">
       <?=@$Ll20_condicoespag?>
    </td>
    <td> 
       <?
        db_textarea('l20_condicoespag',0,57,$Il20_condicoespag,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>
 
 <tr>
    <td nowrap title="<?=@$Tl20_validadeproposta?>">
       <?=@$Ll20_validadeproposta?>
    </td>
    <td> 
       <?
        db_textarea('l20_validadeproposta',0,57,$Il20_validadeproposta,true,'text',$db_opcao,"")
       ?>
    </td>
 </tr>

 <tr>
    <td nowrap title="<?=@$Tl20_tipojulg?>">
       <?=@$Ll20_tipojulg?>
    </td>
    <td> 
       <?
        $arr_tipo = array("1"=>"Por item","2"=>"Global","3"=>"Por lote");
        db_select("l20_tipojulg",$arr_tipo,true,$db_opcao);
        db_input("tipojulg",1,"",true,"hidden",3,"");
        db_input("confirmado",1,"",true,"hidden",3,"");
       ?>
    </td>
 </tr>

 <tr>
    <td nowrap title="<?=@$Tl20_liclocal?>">
       <?
       db_ancora(@$Ll20_liclocal,"js_pesquisal20_liclocal(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
        db_input('l20_liclocal',10,$Il20_liclocal,true,'text',$db_opcao," onchange='js_pesquisal20_liclocal(false);'")
       ?>  
    </td>
 </tr>
 <tr>
    <td nowrap title="<?=@$Tl20_liccomissao?>">
       <?
        db_ancora(@$Ll20_liccomissao,"js_pesquisal20_liccomissao(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
        db_input('l20_liccomissao',10,$Il20_liccomissao,true,'text',$db_opcao," onchange='js_pesquisal20_liccomissao(false);'")
       ?>  
    </td>
  </tr>
  <tr>
    <td>
      <b>Processo do Sistema:</b>
    </td>
    <td>
      <?
         $aProcSistema = array("s"=>"Sim", 
                               "n"=>"Não");
         db_select('lprocsis',$aProcSistema,true,$db_opcao,"onChange='js_mudaProc(this.value);'");
      ?>
    </td>
  </tr>
  <tr id="procAdm" style="display:none">
    <td nowrap title="<?=@$Tl20_procadmin?>"> 
       <?=@$Ll20_procadmin?>
    </td>
    <td> 
       <?
        db_input('l20_procadmin',59,$Il20_procadmin,true,'text',$db_opcao,"")
       ?>
    </td>
  </tr>  
  <tr id="procSis">
    <td nowrap title="<?=@$Tl34_protprocesso?>">
       <? 
         db_ancora($Ll34_protprocesso,"js_pesquisal34_protprocesso(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
         db_input('p58_numero', 15, $Ip58_numero, true, 'text', $db_opcao,"onChange='js_pesquisal34_protprocesso(false);'");
         db_input('l34_protprocesso', 15, $Il34_protprocesso, true, 'hidden', $db_opcao);
         db_input('l34_protprocessodescr',45,"",  true,'text',3,"");
       ?>
    </td>
  </tr>  
  
  <tr>
    <td nowrap title="<?=@$Tl03_usaregistropreco?>">
      <?=@$Ll03_usaregistropreco?>
    </td>
    <td>
    <?
      if (!isset($l20_usaregistropreco)) {
        $l20_usaregistropreco = "f";
      }
      db_select("l20_usaregistropreco",array("t"=>"Sim", "f"=>"Não"),true,$db_opcao);
    ?>
    </td>
  </tr> 
  </table>
  </fieldset>
  
</fieldset>

</td></tr>
</table> 
  
  </center>
  
 <?/*
   if ($db_opcao==2 || $db_opcao==22){
        $jscript = "onClick='return js_confirmar();'";
   } else {
        $jscript = "";
   }<?=$jscript?>*/
 ?>


<input name="<?=($db_opcao==1?'incluir':($db_opcao==2||$db_opcao==22?'alterar':'excluir'))?>" type="submit" id="db_opcao"
       value="<?=($db_opcao==1?'Incluir':($db_opcao==2||$db_opcao==22?'Alterar':'Excluir'))?>"
       <?=($db_botao==false?'disabled':'') ?>  onClick="return js_confirmadatas()"> 
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_mudaProc(sTipoProc){

  if ( sTipoProc == 's') {
    $('procSis').style.display = '';
    $('procAdm').style.display = 'none';
  } else {
    $('procSis').style.display = 'none';
    $('procAdm').style.display = '';  
  }

}

function js_pesquisal20_codtipocom(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pctipocompra','func_pctipocompra.php?funcao_js=parent.js_mostrapctipocompra1|pc50_codcom|pc50_descr','Pesquisa',true,0);
  }else{
     if(document.form1.l20_codtipocom.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pctipocompra','func_pctipocompra.php?pesquisa_chave='+document.form1.l20_codtipocom.value+'&funcao_js=parent.js_mostrapctipocompra','Pesquisa',false);
     }else{
       document.form1.pc50_descr.value = ''; 
     }
  }
}
function js_mostrapctipocompra(chave,erro){
  document.form1.pc50_descr.value = chave; 
  if(erro==true){ 
    document.form1.l20_codtipocom.focus(); 
    document.form1.l20_codtipocom.value = ''; 
  }
}
function js_mostrapctipocompra1(chave1,chave2){
  document.form1.l20_codtipocom.value = chave1;
  document.form1.pc50_descr.value = chave2;
  db_iframe_pctipocompra.hide();
}
function js_pesquisal20_id_usucria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,0);
  }else{
     if(document.form1.l20_id_usucria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.l20_id_usucria.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.l20_id_usucria.focus(); 
    document.form1.l20_id_usucria.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.l20_id_usucria.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?tipo=1&funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true,"0");
}
function js_preenchepesquisa(chave){
  db_iframe_liclicita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
    ?>
   parent.iframe_liclicitem.location.href='lic1_liclicitemalt001.php?licitacao='+chave; 		 
   <?    		
  }
  ?>
}
function js_pesquisal20_liclocal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_local','func_liclocal.php?funcao_js=parent.js_mostralocal1|l26_codigo','Pesquisa',true,"0");
  }else{
     if(document.form1.l20_liclocal.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_local','func_liclocal.php?pesquisa_chave='+document.form1.l20_liclocal.value+'&funcao_js=parent.js_mostralocal','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostralocal(chave,erro){
  if(erro==true){ 
    document.form1.l20_liclocal.focus(); 
    document.form1.l20_liclocal.value = ''; 
  }
}
function js_mostralocal1(chave1){
  document.form1.l20_liclocal.value = chave1;
  db_iframe_local.hide();
}
function js_pesquisal20_liccomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_comissao','func_liccomissao.php?funcao_js=parent.js_mostracomissao1|l30_codigo','Pesquisa',true,"0");
  }else{
     if(document.form1.l20_liccomissao.value != ''){ 
        js_OpenJanelaIfrasme('top.corpo','db_iframe_comissao','func_liccomissao.php?pesquisa_chave='+document.form1.l20_liccomissao.value+'&funcao_js=parent.js_mostracomissao','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostracomissao(chave,erro){
  if(erro==true){ 
    document.form1.l20_liccomissao.focus(); 
    document.form1.l20_liccomissao.value = ''; 
  }
}
function js_mostracomissao1(chave1){
  document.form1.l20_liccomissao.value = chave1;
  db_iframe_comissao.hide();
}

function js_pesquisal34_protprocesso(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso_protocolo.php?funcao_js=parent.js_mostraprocesso1|p58_numero|dl_código_processo|dl_nome_ou_razão_social','Pesquisa',true,"0");
  } else {

    if(document.form1.p58_numero.value != ''){
      js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso_protocolo.php?pesquisa_chave='+document.form1.p58_numero.value+'&funcao_js=parent.js_mostraprocesso&sCampoRetorno=p58_codproc','Pesquisa',false);
    } else {
      document.form1.l34_protprocessodescr.value = ''; 
    }
  }
}

function js_mostraprocesso(iCodigoProcesso, sNome, lErro){
 
  document.form1.l34_protprocessodescr.value = sNome;
  
  if ( lErro ){ 

    document.form1.p58_numero.focus(); 
    document.form1.p58_numero.value = '';
    document.form1.l34_protprocesso.value = '';
    return false; 
  } 

  document.form1.l34_protprocesso.value = iCodigoProcesso;
  
  db_iframe_proc.hide();  
}

function js_mostraprocesso1(iNumeroProcesso, iCodigoProcesso, sNome) {

  document.form1.p58_numero.value            = iNumeroProcesso;
  document.form1.l34_protprocesso.value      = iCodigoProcesso;
  document.form1.l34_protprocessodescr.value = sNome;
  db_iframe_proc.hide();
}

var sUrl = "lic4_licitacao.RPC.php";
function js_mostraRegistroPreco() {

  js_divCarregando("Aguarde, pesquisando parametros","msgBox");
  var oParam            = new Object();
  oParam.exec           = "verificaParametros";
  oParam.itipoLicitacao = $F('l20_codtipocom');
  db_iframe_estimativaregistropreco.hide();
  var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                         method: "post",
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete: js_retornoRegistroPreco
                                        });

}
function js_retornoRegistroPreco(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    //$()
  }
}

function js_confirmadatas() {
  
  var dataCriacao    = $F('l20_datacria');
  var dataPublicacao = $F('l20_dtpublic');
  var dataAbertura   = $F('l20_dataaber');
  
  if( js_CompararDatas(dataCriacao, dataPublicacao, '<=') ) {
    if( js_CompararDatas(dataPublicacao, dataAbertura, '<=') ) {
      <?
        if($db_opcao==2 || $db_opcao==22) {
        	echo 'return js_confirmar();';        	
        } else {
        	echo 'return true;';
        }
      ?>    
    } else {
    
      alert("A Data de Abertura deve ser maior ou igual a Data de Publicação.");    
      return false;
    }    
  } else {
  
    alert("A Data de Publicação deve ser maior ou igual a Data de Criação.");    
    return false;
  }  
  
}

function js_CompararDatas(data1,data2,comparar){

  if (data1.indexOf('/') != -1){
    datepart = data1.split('/');
    pYear    = datepart[2];
    pMonth   = datepart[1];
    pDay     = datepart[0];
  }
    data1 = pYear+pMonth+pDay;

  if (data2.indexOf('/') != -1){
    datepart = data2.split('/');
    pYear    = datepart[2];
    pMonth   = datepart[1];
    pDay     = datepart[0];
  }
    data2 = pYear+pMonth+pDay;
    if (eval(data1+" "+comparar+" "+data2)) {

       return true;

     }else{
      return false;
     }
}
  

<?
  if($db_opcao == 1) {
  	echo "js_mudaProc('{$lprocsis}');";	  
  } else {
    if ( (isset($l34_protprocesso) && trim($l34_protprocesso) != '') ) {
      echo "js_mudaProc('s');";   
    }
  }
?>

</script>
<?
if ( empty($l34_liclicita)) {
  echo "<script>
         document.form1.lprocsis.value = 'n';     
         $('procSis').style.display = 'none';
         $('procAdm').style.display = ''; 
        </script>";
}
?>