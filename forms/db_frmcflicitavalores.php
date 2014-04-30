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

require_once("dbforms/db_classesgenericas.php");
 
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcflicitavalores->rotulo->label();

if (isset($db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && !$lSqlErro)) {
    	
    $l40_valorminimo     = "";
    $l40_valormaximo     = "";
    $l40_datainicial_dia = "";
    $l40_datainicial_mes = "";
    $l40_datainicial_ano = "";
    $l40_datafinal_dia   = "";
    $l40_datafinal_mes   = "";
    $l40_datafinal_ano   = "";
   }
} 
?>
<style>
td {
  white-space: nowrap
}
fieldset table td:first-child {
              width: 100px;
              white-space: nowrap
}
</style>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>
      <b>Cadastro da Faixa de Valores</b>
    </legend>
		<table align="left" border="0">
      <tr>
        <td nowrap title="<?=@$Tl40_valorminimo?>" align="left" width="25%">
          <?=@$Ll40_valorminimo?>
        </td>
        <td width="30%">
          <?
            db_input('l40_sequencial',10,$Il40_sequencial,true,'hidden',$db_opcao,"");
            db_input('l40_valorminimo',10,$Il40_valorminimo,true,'text',$db_opcao,"");
          ?>
        </td>
        <td nowrap title="<?=@$Tl40_valormaximo?>" align="left" width="22%">
          <?=@$Ll40_valormaximo?>
        </td>
        <td>
          <?
            db_input('l40_valormaximo',10,$Il40_valormaximo,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
		  <tr>
		    <td nowrap title="<?=@$Tl40_datainicial?>">
		       <?=@$Ll40_datainicial?>
		    </td>
		    <td> 
					<?
					  db_inputdata('l40_datainicial',@$l40_datainicial_dia,@$l40_datainicial_mes,@$l40_datainicial_ano,true,
					               'text',$db_opcao);
					?>
		    </td>
        <td nowrap title="<?=@$Tl40_datafinal?>">
           <?=@$Ll40_datafinal?>
        </td>
        <td> 
          <?
            db_inputdata('l40_datafinal',@$l40_datafinal_dia,@$l40_datafinal_mes,@$l40_datafinal_ano,true,
                         'text',$db_opcao);
          ?>
        </td>
		  </tr>
	  </table>
  </fieldset>
	 <table cellpadding="0" cellspacing="0" align="center">
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="2" align="center">
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="submit" id="db_opcao" onclick="return js_validarCampos();"
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>  >
        <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
               <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
	    </td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	  </tr>
	 </table>
	<table>
	  <tr>
	    <td valign="top"  align="center">  
		    <?
           $chavepri  = array("l40_sequencial" => @$l40_sequencial);
           $sCampos   = "l40_sequencial,l40_codfclicita,l40_valorminimo,l40_valormaximo,l40_datainicial,l40_datafinal";
           $sWhere    = "l40_codfclicita = {$l37_cflicita}";
           $sSql      = $clcflicitavalores->sql_query(null,$sCampos,"l40_sequencial",$sWhere);
           
           $cliframe_alterar_excluir->chavepri      = $chavepri;
           $cliframe_alterar_excluir->sql           = $sSql;
           $cliframe_alterar_excluir->campos        = $sCampos;
           $cliframe_alterar_excluir->legenda       = "Faixa de Valores Lançados";
           $cliframe_alterar_excluir->iframe_height = "160";
           $cliframe_alterar_excluir->iframe_width  = "550";
           $cliframe_alterar_excluir->opcoes        = 1;
           $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    ?>
	    </td>
	  </tr>
	</table>
</form>
<script>
function js_validarCampos() {
  
  var valorminimo = new Number(document.form1.l40_valorminimo.value);
  var valormaximo = new Number(document.form1.l40_valormaximo.value);
  var dtInicial   = document.form1.l40_datainicial.value;
  var dtFinal     = document.form1.l40_datafinal.value;  
  
  if (valorminimo == '' || valormaximo == '') {

    var sMsgErro  = "Usuario:\n\n";
        sMsgErro += " Informe valor minímo e valor maxímo!\n\n";
    alert(sMsgErro);
    return false;
  }
  
  if (valorminimo > valormaximo) {
  
    var sMsgErro  = "Usuario:\n\n";
        sMsgErro += " Valor minímo é maior que o valor máxímo!\n\n";
    alert(sMsgErro);
    return false;
  }
  
  if (dtInicial == '' || dtFinal == '') {
  
    var sMsgErro  = "Usuario:\n\n";
        sMsgErro += " Informe data inicial e data final!\n\n";
    alert(sMsgErro);
    return false;
  }

  if (js_comparadata(dtInicial,dtFinal,'>')) {
    
    var sMsgErro  = "Usuario:\n\n";
        sMsgErro += " Data inicial maior que a final!\n\n";
    alert(sMsgErro);
    return false;
  }
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>