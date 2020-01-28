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

//MODULO: configuracoes
$cldb_depart->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o40_orgao");
$clrotulo->label("o41_unidade");
$clrotulo->label("id_usuarioresp");
$clrotulo->label("nome");
?>
<script>
  function js_troca(){
  
      obj = document.createElement('input');
      obj.setAttribute('name', 'troca');
      obj.setAttribute('type', 'hidden');
      obj.setAttribute('value', 'ok');
      document.form1.appendChild(obj);
      document.form1.submit();
  }
</script>
<?
  if($db_opcao==1){
    $pg="con1_db_depart001.php";
  }else if($db_opcao==2 || $db_opcao==22){
    $pg="con1_db_depart002.php";
  }else if($db_opcao==3 || $db_opcao==33){
    $pg="con1_db_depart003.php";
  }  
	  
	  
?>
<script>
  function js_usu(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_db_usuario','func_db_usuarios.php?funcao_js=parent.js_mostrausu1|id_usuario|nome','Pesquisa',true , 0);
    }else{
      usu= document.form1.id_usuarioresp.value;
      if(usu!=""){
        js_OpenJanelaIframe('','db_iframe_db_usuario','func_db_usuarios.php?pesquisa_chave='+usu+'&funcao_js=parent.js_mostrausu','Pesquisa',false, 0);
      }else{  
  document.form1.nome.value='';
      }   
    }
  }
  function js_mostrausu1(iUsuario,sNome){
//    alert('132121');
    $('id_usuarioresp').value = iUsuario;
    $('nome').value           = sNome;
    db_iframe_db_usuario.hide();
  }
  function js_mostrausu(chave,erro){
    document.getElementById('nome').value = chave; 
    if(erro==true){ 
      document.getElementById('id_usuarioresp').focus(); 
      document.getElementById('id_usuarioresp').value = ''; 
    }
  }
</script>
<form name="form1" method="post" action="<?=$pg?>">
<center>
<table border="0">
	<tr>
		<td nowrap title="<?=@$Tcoddepto?>">
      <?=@$Lcoddepto?>
    </td>
    <td colspan="3">
    <?
      db_input('coddepto',5,$Icoddepto,true,'text',3)
    ?>
    </td>
</tr>
<tr>
	<td nowrap title="<?=@$Tdescrdepto?>">
    <?=@$Ldescrdepto?>
  </td>
  <td colspan="3">
  <?
    db_input('descrdepto',40,$Idescrdepto,true,'text',$db_opcao,"")
  ?>
  </td>
</tr>
<tr>
	<td nowrap title="<?=@$Tid_usuarioresp?>">
  <?
    db_ancora(@$Lnome,"js_usu(true);",$db_opcao);
  ?>
  </td>
  <td> 
	<?
	  db_input('id_usuarioresp',7,$Iid_usuarioresp, true, 'text', $db_opcao, " onchange='js_usu(false);'" );
	  db_input ('nome', 40, $Inome, true, 'text', 3, '' );
  ?>
	<td>
</tr>
	<tr>
		<td nowrap title="<?=@$Tinstit?>">
      <?=@$Linstit?>
    </td>
		<td colspan="3">
      <?
      if ( ($db_opcao==1||$db_opcao==11)||trim(@$instit)=="" ){
        $instit = db_getsession("DB_instit");
      }
      /**
       * verificamos a instituição em que o usuário está logado
       * caso seje a prefeitura prefeitura = true, trazemos todos as instituicoes.
       * senao, trazemos somente a instituição emque o usuário está logado.;
       */
      $sSQlInstit  = "select codigo             ";
      $sSQlInstit .= "  from db_config          ";
      $sSQlInstit .= " where prefeitura is true;";
      $rsInstit    = $cldb_config->sql_record($sSQlInstit);
      db_fieldsmemory($rsInstit,0);
      if ($codigo == db_getsession("DB_instit")){
         $iInstit = null;
      }else{
         $iInstit = db_getsession("DB_instit"); 
      } 
      $result = $cldb_config->sql_record($cldb_config->sql_query_file($iInstit,"codigo,nomeinst","codigo"));
      db_selectrecord("instit",$result,true,$db_opcao,'','','','',"js_getOrgaos(this.value);");
      ?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$To40_orgao?>">
          <?=@$Lo40_orgao?>
        </td>
		<td colspan="3">
          <?
          $sWhere  = " o40_anousu     = ".db_getSession("DB_anousu");
          $sWhere .= " and o41_instit = {$instit}";
          //echo $clorcunidade->sql_query(null,null,null,"distinct o40_orgao,o40_descr","o40_descr",$sWhere);
          $result = $clorcunidade->sql_record($clorcunidade->sql_query(
                                             null,null,null,"distinct o40_orgao,o40_descr","o40_descr",$sWhere));
          if (@pg_numrows($result) == 0) {
            echo "<strong>Sistema não localizou nenhum orgão com unidades vinculadas na instituição selecionada!</strong>";
          } else { 
            db_selectrecord("o40_orgao",@$result,true,$db_opcao,'','','','',"js_getUnidades(this.value);");
          }
          ?>
        </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$To41_unidade?>">
          <?=@$Lo41_unidade?>
        </td>
		<td colspan="3">
        <?
          if (empty($o40_orgao)){
         	  @db_fieldsmemory($result,0);
          }
          if (isset($o40_orgao)) {
            $result = $clorcunidade->sql_record($clorcunidade->sql_query_file(null,
                                                                              null,
                                                                              null,
                                                                              "o41_unidade,o41_descr","o41_descr",
                                                                              "o41_anousu = " . db_getsession("DB_anousu") . " and o41_orgao=$o40_orgao"));
            db_selectrecord("o41_unidade",@$result,true,$db_opcao);
          }  
        ?>
        </td>
	</tr>
      <?
      if ($db_opcao != 1){
        
        echo " <tr> ";
        echo "   <td nowrap title='{$Tlimite}'>";
        echo  $Llimite;
        echo "   </td>";
        echo "   <td colspan='3'>";
        db_inputdata('limite',@$limite_dia,@$limite_mes,@$limite_ano,true,'text',$db_opcao,"","","E6E4F1");
        echo "   </td>";
        echo " </tr>";
      }
      ?>
       <tr>
        <td>
          <label class="bold" id="lbl_grupo_subgrupo" for="btn_grupo_subgrupo">Organograma:</label>
        </td>
        <td>
            <input type="button" value="Configurar" id="btn_grupo_subgrupo">
        </td>
      </tr>
      <tr>
		<td colspan='4'>
		<fieldset><Legend align="left"> <b>Contato</b> </Legend>
		<center>
		<table>
			<tr>
				<td nowrap title="<?=@$Temaildepto?>">
                    <?=@$Lemaildepto?>
                  </td>
				<td colspan="5">
                    <?
                     db_input('emaildepto',50,$Iemaildepto,true,'text',$db_opcao,"")
                    ?>
                  </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tfonedepto?>">
                    <?=@$Lfonedepto?>
                  </td>
				<td>
                    <?
                    db_input('fonedepto',12,$Ifonedepto,true,'text',$db_opcao,"")
                    ?>
                  </td>
				<td nowrap title="<?=@$Tramaldepto?>" align="right">
                    <?=@$Lramaldepto?>
                  </td>
				<td align="right">
                    <?
                     db_input('ramaldepto',5,$Iramaldepto,true,'text',$db_opcao,"")
                    ?>
                  </td>
				<td nowrap title="<?=@$Tfaxdepto?>">
                    <?=@$Lfaxdepto?>
                  </td>
				<td colspan="3">
                    <?
                     db_input('faxdepto',12,$Ifaxdepto,true,'text',$db_opcao,"")
                    ?>
                  </td>
			</tr>
		</table>
		</center>
		</fieldset>
		</td>
	</tr>
</table>
</center>
<input name="db_opcao" type="submit" id="db_opcao"value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
	
	<?
   if ($db_opcao==2||$db_opcao==22){
  ?>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick='js_pesquisaalt();'><input name="novo" type="button" id="novo" value="Novo" onclick='js_novo_reg();'>
  <?
  }else{
  ?>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick='js_pesquisa();'>
  <?
  }
  ?>
</form>
<script>
    function js_novo_reg(){
         parent.iframe_g2.location.href='con1_db_departender001.php';
         parent.iframe_g1.location.href='con1_db_depart001.php';
    }
    function js_pesquisa(){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_g1','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_preenchepesquisa|coddepto','Pesquisa',true);
    }
    function js_preenchepesquisa(chave){
      <?
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
        if($db_opcao==2||$db_opcao==22){
        ?>
         parent.iframe_g2.location.href='con1_db_departender002.php?chavepesquisa='+chave+'&coddepto='+chave;
        <?}else{?> 
         parent.iframe_g2.location.href='con1_db_departender003.php?chavepesquisa='+chave+'&coddepto='+chave;
         <?}?>
        db_iframe_db_depart.hide();
       <?
      }
      ?>
    }
    function js_pesquisaalt(){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_g1','db_iframe_db_depart','func_db_departalt.php?funcao_js=parent.js_preenchepesquisa|coddepto','Pesquisa',true,1,0);
    }
    /*
     * Funcao para pegar os orgaos de determinada instituicao;
     */
    function js_getOrgaos(iInstit){
      
     strJson = '{"method":"getOrgaos","iInstit":"'+iInstit+'"}';
     sUrl    = 'con4_db_departRPC.php';
     oAjax   = new Ajax.Request(
                              sUrl, 
                                {
                                 method: 'post', 
                                 parameters: 'json='+strJson, 
                                 onComplete: js_retornoOrgaos
                                }
                               );
    }
    
    function js_getUnidades(iOrgao){
      
      strJson = '{"method":"getUnidades","iOrgao":"'+iOrgao+'"}';
      sUrl    = 'con4_db_departRPC.php';
      oAjax   = new Ajax.Request(
                              sUrl, 
                                {
                                 method: 'post', 
                                 parameters: 'json='+strJson, 
                                 onComplete: js_retornoUnidades
                                }
                               );
    }
    
    function js_retornoOrgaos(oAjax){
      
      oOrgaos = eval("("+oAjax.responseText+")");
      $('o40_orgao').options.length        = 0;
      $('o40_orgaodescr').options.length   = 0;
      $('o41_unidade').options.length      = 0;
      $('o41_unidadedescr').options.length = 0;
      if (oOrgaos.length > 0){
        
        $('o41_unidadedescr').disabled = false;
        $('o41_unidade').disabled      = false;
        js_getUnidades(oOrgaos[0].o40_orgao);
        for (iInd = 0; iInd < oOrgaos.length; iInd++){
         
          oOptionId    = new Option(oOrgaos[iInd].o40_orgao, oOrgaos[iInd].o40_orgao);
          $('o40_orgao').add(oOptionId,null);
          oOptionDescr = new Option(js_urldecode(oOrgaos[iInd].o40_descr), oOrgaos[iInd].o40_orgao);
          $('o40_orgaodescr').add(oOptionDescr,null);
       }
     }
      
    }
    
    function js_retornoUnidades(oAjax){
      
      oUnidades = eval("("+oAjax.responseText+")");
      $('o41_unidade').options.length      = 0;
      $('o41_unidadedescr').options.length = 0;
      $('o41_unidadedescr').disabled = false;
      $('o41_unidade').disabled      = false;
      for (iInd = 0; iInd < oUnidades.length; iInd++){
         
        oOptionId    = new Option(oUnidades[iInd].o41_unidade, oUnidades[iInd].o41_unidade);
        $('o41_unidade').add(oOptionId,null);
        oOptionDescr = new Option(js_urldecode(oUnidades[iInd].o41_descr), oUnidades[iInd].o41_unidade);
        $('o41_unidadedescr').add(oOptionDescr,null);
      }
      
    }
    
    function js_urldecode(sString){
      
      sString = sString.replace(/\+/g," ");
      sString = unescape(sString);
      return sString;  
      
    }
    
    $('instit').style.width           = '60px';
    $('institdescr').style.width      = '430px';
    $('o40_orgao').style.width        = '60px';
    $('o40_orgaodescr').style.width   = '430px';
    $('o41_unidade').style.width      = '60px';
    $('o41_unidadedescr').style.width = '430px';


  var oFiltroOrganograma;
  var iCod = $('coddepto').getAttribute("value");

  document.observe('dom:loaded', function () {

    oFiltroOrganograma = new FiltroOrganograma('filtroOrganograma', true, iCod);
    $('btn_grupo_subgrupo').observe('click', function(){
      oFiltroOrganograma.show();
    });
  });

</script>
<?php
  if ($db_opcao == 1){
   
    echo "<script>\n";
    //echo "\$('o41_unidadedescr').disabled = true;\n";
    //echo "\$('o41_unidade').disabled = true;\n";
    echo "</script>\n";
  }
 ?>