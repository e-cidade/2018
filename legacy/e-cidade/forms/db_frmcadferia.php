<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: pessoal
$clcadferia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="pes4_cadferia004.php"class="container">
  <fieldset style="width: 700px">
    <legend>Cadastro de F�rias</legend>

    <table cellpadding="0" cellspacing="0" class="form-container">
      <tr>
        <td align="right" nowrap title="<?=@$Tr30_regist?>" width="100">
          <?
          $mensagemlote='n';
          db_input('mensagemlote', 4, 0,'', 'hidden', 3);
          db_ancora(@$Lr30_regist, "js_pesquisar30_regist(true);", $db_opcao);
          ?>
        </td>
        <td width="500">
          <?
          db_input('r30_regist', 8, $Ir30_regist, true, 'text', $db_opcao, " onchange='js_pesquisar30_regist(false);'")
          ?>
          <?
          db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>

  </fieldset>
  <input name="enviar" value="Enviar" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.r30_regist.focus();" >
</form>

<script type="text/javascript">

function js_pesquisar30_regist(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalafasta.php?lativos=true&testarescisao=raf&afasta=true&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome|r45_dtafas|r45_dtreto&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r30_regist.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalafasta.php?lativos=true&testarescisao=raf&afasta=true&pesquisa_chave='+document.form1.r30_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostrapessoal(chave,chave2,chave3,erro){

	mostrar = false;
	if(erro == false){
		mostrar = true;
	}

  document.form1.z01_nome.value = chave;
  if(mostrar == false){
	  if(erro != true){
	    document.form1.z01_nome.value   = '';
	  }
    document.form1.r30_regist.focus();
    document.form1.r30_regist.value = '';
  }
}

function js_mostrapessoal1(chave1,chave2,chave3,chave4){

  db_iframe_rhpessoal.hide();
	mostrar = true;

  if(mostrar == true){
	  document.form1.r30_regist.value = chave1;
	  document.form1.z01_nome.value   = chave2;
  }else{
    document.form1.r30_regist.focus();
    document.form1.r30_regist.value = '';
    document.form1.z01_nome.value   = '';
  }
}

function js_compara_datas(dataafast,dataretor){
  dataatual = "<?=date("Y-m-d",db_getsession("DB_datausu"))?>";

  afast = new Date(dataafast.substring(0,4),(dataafast.substring(5,7) - 1),dataafast.substring(8,10));
  retor = new Date(dataretor.substring(0,4),(dataretor.substring(5,7) - 1),dataretor.substring(8,10));
  atual = new Date(dataatual.substring(0,4),(dataatual.substring(5,7) - 1),dataatual.substring(8,10));

  if(atual > afast && atual < retor){
  	alert("Funcion�rio afastado.");
  	return false;
  }
  return true;
}
</script>