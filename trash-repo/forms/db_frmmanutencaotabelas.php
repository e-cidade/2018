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

$clrotulo = new rotulocampo;
$clrotulo->label("j121_codarq");
?>
<form name="form1" method="post" action="">
  <fieldset class="fildsetprincipal">
    <legend>
      <b>Manutenção da Tabela <?=@$oDadoManutensaoTabela->sNomeTabela?></b>
    </legend>
    <table border="0" align="center" class="tabelaprincipal">
      <?      
        foreach ($aDadoManutensaoTabela as $oManutensaoTabela) {
        	
        	$sTipoCampo = substr(trim($oManutensaoTabela->sTipoCampo), 0, 4);
          if ($sTipoCampo == 'floa') {

          	if (isset(${$oManutensaoTabela->sNomeCampo}) && !empty(${$oManutensaoTabela->sNomeCampo})) {
          		eval("$".$oManutensaoTabela->sNomeCampo." = trim(db_formatar($".$oManutensaoTabela->sNomeCampo.", 'p',' ',15,'e',2));");
          	}
          }
        	
        	if ($sTipoCampo == 'text') {        		
      ?>
      <tr>
        <td title="<?=@${'T'.$oManutensaoTabela->sNomeCampo}?>" colspan="2">
	        <fieldset class="fildsetcampotextarea">
	          <legend>
	            <a  id='<?=@$oManutensaoTabela->sNomeCampo?>' onClick="js_escondeToggle('<?=@$oManutensaoTabela->sNomeCampo?>');">               
	              <?=@${'L'.$oManutensaoTabela->sNomeCampo}?>
	              <img src='imagens/seta.gif' id='toggle<?=@$oManutensaoTabela->sNomeCampo?>' border='0'>
	            </a>
	          </legend>
					  <table border="0" align="center" id="tab<?=@$oManutensaoTabela->sNomeCampo?>" class="tabelatextarea" style="display: none">
					    <tr>
					      <td> 
					        <?
                    montarCampos($oManutensaoTabela->sNomeCampo, 
                                 "",
                                 $sTipoCampo, 
                                 $oManutensaoTabela->iTamanhoCampo, 
                                 $oManutensaoTabela->iOpcao);
					        ?>
					      </td>
					    </tr>
					  </table>
	        </fieldset>
        </td>
      </tr>
      <?
        	} else {
      ?>  		
      <tr>
        <td nowrap title="<?=@${'T'.$oManutensaoTabela->sNomeCampo}?>">
          <?=@${'L'.$oManutensaoTabela->sNomeCampo}?>
        </td>
        <td>
          <?
            $sValor = "";
            if ($sTipoCampo == 'date') {
            	
              if (isset(${$oManutensaoTabela->sNomeCampo}) && trim(${$oManutensaoTabela->sNomeCampo}) != '' ) {
              	$sValor = ${$oManutensaoTabela->sNomeCampo};
              }
            }

            montarCampos($oManutensaoTabela->sNomeCampo,
        	               $sValor, 
                         $sTipoCampo, 
                         $oManutensaoTabela->iTamanhoCampo, 
                         $oManutensaoTabela->iOpcao);
          ?>
        </td>
      </tr>	
      <?
        	}
        }
        
        db_input('j121_codarq', 10, $Ij121_codarq, true, "hidden", 3);
      ?>
    </table>
  </fieldset>
  <table align="center">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>      
      <td>
        <?
          /* Verifica se existe o arquivo da lockup de pesquisa */
          if (!file_exists("func_{$oDadoManutensaoTabela->sNomeTabela}.php")) {
          	
            $sMensagem = "Arquivo de pesquisa func_{$oDadoManutensaoTabela->sNomeTabela}.php não encontrado! Contate o suporte.";
            db_msgbox($sMensagem);
            db_redireciona("cad4_manutencaotabelas001.php");
          }
        
          if ($db_botao) {
        ?>
            <input name="incluir" type="submit" id="incluir" value="Incluir" onclick="return js_validar();">
        <?
          } else {
        ?>
            <input name="alterar" type="submit" id="alterar" value="Alterar" onclick="return js_validar();">
            <input name="excluir" type="submit" id="excluir" value="Excluir" onclick="return js_validar();">
        <?
          }
        ?>
        <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar();">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="return js_pesquisar();">
      </td> 
    </tr>
  </table>
</form>
<?
  /* Verifica se existe chave primaria na tabela */
	if (isset($sListaPriKeyChave) && empty($sListaPriKeyChave)) {
		
	  $sMensagem = "Tabela {$oDadoManutensaoTabela->sNomeTabela} não possui chave primaria! Contate o suporte.";
    db_msgbox($sMensagem);
    db_redireciona("cad4_manutencaotabelas001.php");
	}
?>
<script>
function js_voltar() {
  document.location.href = "cad4_manutencaotabelas001.php";
}

function js_validar() {

}

function js_escondeToggle(sNomeCampo) {

  var sDisplay = $('tab'+sNomeCampo).style.display;
  if (sDisplay == 'none') {
  
    $('tab'+sNomeCampo).style.display = '';
    $('toggle'+sNomeCampo).src = 'imagens/setabaixo.gif';
  } else {
  
    $('tab'+sNomeCampo).style.display = 'none';
    $('toggle'+sNomeCampo).src = 'imagens/seta.gif';
  }
}

<?
  echo "function js_pesquisar() {

				  var sNomeTabela = '".@$oDadoManutensaoTabela->sNomeTabela."';
				  var sUrl        = 'func_'+sNomeTabela+'.php?funcao_js=parent.js_preenchepesquisa|".$sListaPriKeyLockup."';
				  js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', true);
				  
				} \n";

  echo "function js_preenchepesquisa(".$sListaChave.") {

				  db_iframe_iptutabelas.hide();
				  var j121_codarq = $('j121_codarq').value;
				  ";
  
				   if (!empty($oDadoManutensaoTabela->sNomeTabela)) {
				      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?".$sListaPriKeyChave."&j121_codarq='+j121_codarq";
				   }
				   
	echo 	"} \n"; 
?>
</script>
<?
function montarCampos($sNomeCampo, $sValor="", $sTipoCampo, $iTamanho=10, $iOpcao) {
	
	$sInput = '';
	if (!empty($sNomeCampo) && !empty($sTipoCampo)) {

		switch ($sTipoCampo) {
			
			case 'date':
				
		    if (isset($sValor) && trim($sValor) != '' ) {
                
          $aDate = explode("-",$sValor);
          eval('$'.$sNomeCampo.'_ano = "'.$aDate[0].'";');
          eval('$'.$sNomeCampo.'_mes = "'.str_pad($aDate[1],2,'0',STR_PAD_LEFT).'";');
          eval('$'.$sNomeCampo.'_dia = "'.str_pad($aDate[2],2,'0',STR_PAD_LEFT).'";');
        }
				
			  $sInput = db_inputdata($sNomeCampo, @${$sNomeCampo.'_dia'}, @${$sNomeCampo.'_mes'}, @${$sNomeCampo.'_ano'}, 
			                         true, 'text', $iOpcao);
			  break;
			
      case 'text':
      	
        $sInput = db_textarea($sNomeCampo, 5, 60, @${'I'.$sNomeCampo}, true, 'text', $iOpcao);
        break;
			  
      case 'bool':
      	
      	$aOpcao = array ('t' => "Sim", 
      	                 'f' => "Nao");
        $sInput = db_select($sNomeCampo, $aOpcao, true, $iOpcao);
        break;
        
			default:
				
				$sInput = db_input($sNomeCampo, $iTamanho, @${'I'.$sNomeCampo}, true, "text", $iOpcao, "");
			  break;
		}
		
	}
	
	return $sInput;
}
?>