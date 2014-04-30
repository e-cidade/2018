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

  $clcgm->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("DBtxt1");
?>
<form name="form1" method="post" >
<table width="790" border="1" cellspacing="0" cellpadding="0">
  <tr align="center" valign="middle"> 
    <td height="30" colspan="2"><u>Cadastro Geral do CGM</u></td>
  </tr>
  <tr align="left" valign="top"> 
    <td><table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="27%" title='<?=$Tz01_numcgm?>' nowrap>
            <?=$Lz01_numcgm?>
          </td>
          <td width="73%" nowrap> <input type="hidden" name="z01_cadast" id="z01_cadast" value="<?=date('Y-m-d',db_getsession("DB_datausu")) ?>"> 
            <input name="numcgm" type="text" id="numcgm" value="<?=@$z01_numcgm?>" title="<?=@$Tz01_numcgm?>" size="6" onblur="js_ValidaCampos(this,4,'<?=$Sz01_numcgm?>')"> 
            <input name="z01_numcgm" type="hidden" id="z01_numcgm" value="<?=@$z01_numcgm?>"> 
          </td>
        </tr>
        <tr> 
          <td nowrap title=<?=@$Tz01_nome?>>
            <?=@$Lz01_nome?>
          </td>
          <td nowrap><input name="z01_nome" type="text" id="$z01_nome" value="<?=@$z01_nome?>" title="<?=@$Tz01_nome?>" size="40" maxlength="40" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td width="27%" title="<?=$Tz01_cgccpf?>" nowrap>
            <?=$Lz01_cgccpf?>
          </td>
          <td nowrap> <input name="z01_cgccpf" type="text" id="z01_cgccpf" value="<?=@$z01_cgccpf?>" title="<?=@$z01_cgccpf?>" size="14" maxlength="14" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=$Tz01_tipcre?>">
            <?=$Lz01_tipcre?>
          </td>
          <td nowrap > <select  title="<?=$Tz01_tipcre?>" name="z01_tipcre" id="z01_tipcre" <? if ($opcao=="Excluir") {echo " disabled";}?>>
              <option value="1" <? echo @$z01_tipcre=="1"?"selected":"" ?>>Administra&ccedil;&atilde;o 
              P&uacute;blica</option>
              <option value="2" <? echo @$z01_tipcre=="2"?"selected":"" ?>>N&atilde;o</option>
            </select></td>
        </tr>
        <input name="z01_login" type="hidden" id="z01_login" value="<? if ($db_opcao=="Inserir") {echo $DB_id_usuario;} else {echo @$z01_login;} ?>">
        <tr> 
          <td nowrap title=<?=$Tz01_nacion?>>
            <?=$Lz01_nacion?>
          </td>
          <td nowrap> <select  title="<?=$Tz01_nacion?>" name="z01_nacion" id="z01_nacion" <? if ($opcao=="Excluir") {echo " disabled";}?>>
              <option value="1" <? echo @$z01_nacion=="1"?"selected":"" ?>>Brasileira</option>
              <option value="2" <? echo @$z01_nacion=="2"?"selected":"" ?>>Estrangeira</option>
            </select></td>
        </tr>
      </table></td>
    <td><table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td nowrap title="<?=@$Tz01_incest?>">
            <?=@$Lz01_incest?>
          </td>
          <td nowrap><input name="z01_incest"  title="<?=@$Tz01_incest?>" type="text" id="z01_incest" value="<?=@$z01_incest?>" size="15" maxlength="15" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_ident?>">
            <?=@$Lz01_ident?>
          </td>
          <td nowrap><input   title="<?=@$Tz01_ident?>" name="z01_ident" type="text" id="z01_ident" value="<?=@$z01_ident?>" size="15" maxlength="15" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td width="27%" title="<?=$Tz01_profis?>" nowrap>
            <?=$Lz01_profis?>
          </td>
          <td nowrap> <input name="z01_profis" title="<?=$Tz01_profis?>" type="text" id="z01_profis" value="<?=@$z01_profis?>" size="40" maxlength="40" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=$Tz01_estciv?>">
            <?=$Lz01_estciv?>
          </td>
          <td nowrap title="<?=$Tz01_estciv?>">
		  <?
		  
		  $x = array("1"=>"Solteiro","2"=>"Casado");
		  db_select('z01_estciv',$x,true);
		  ?> 
		  <!--select name="z01_estciv" id="z01_estciv" <? if ($opcao=="Excluir") {echo " disabled";}?>>
              <option value="1" <? echo @$z01_estciv=="1"?"selected":"" ?>>Solteiro(a)</option>
              <option value="2" <? echo @$z01_estciv=="2"?"selected":"" ?>>Casado(a)</option>
              <option value="3" <? echo @$z01_estciv=="2"?"selected":"" ?>>Viúvo(a)</option>
              <option value="4" <? echo @$z01_estciv=="2"?"selected":"" ?>>Divorciado(a)</option>
            </select-->
		  </td>
        </tr>
      </table></tr>
  <tr> 
    <td width="50%" align="center" title="<?=$TDBtxt1?>" valign="middle"><?=$LDBtxt1?></td>
    <td width="50%" align="center" valign="middle"><u>Endere&ccedil;o pra Contato</u></td>
  </tr>
  <tr align="center" valign="middle"> 
    <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td nowrap title="<?=@$Tz01_ender?>" onClick="javascript: js_abreJanelaLogradouro();">
            <?=@$Lz01_ender?>
          </td>
          <td nowrap><input title="<?=@$Tz01_ender?>" onBlur="javascript: js_abreJanelaCODLogradouro();" type="text" name="codrua" id="codrua" size="5" maxlength="4" <? if ($db_opcao=="Excluir") {echo " disabled";}?>>
            &nbsp;
            <input name="z01_ender" type="text" id="z01_ender" value="<?=@$z01_ender?>" size="40" maxlength="40" <? if ($opcao=="Excluir") {echo " disabled";}?>> 
          </td>
        </tr>
        <tr> 
          <td width="29%" nowrap title="<?=@$Tz01_numero?>">
            <?=@$Lz01_numero?>
          </td>
          <td width="71%" nowrap  ><a name="AN3"> 
            <input  title="<?=@$Tz01_numero?>" name="z01_numero" type="text" id="z01_numero" value="<?=@$z01_numero?>" size="8" maxlength="7" <? if ($opcao=="Excluir") {echo " disabled";}?>>
            &nbsp;
            <?=@$Lz01_compl?>
            <input title="<?=@$Tz01_compl?>" name="z01_compl" type="text" id="z01_compl" value="<?=@$z01_compl?>" size="20" maxlength="20">
            </a> </td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_munic?>">
            <?=@$Lz01_munic?>
          </td>
          <td nowrap><input  title="<?=@$Tz01_munic?>" name="z01_munic" type="text" id="z01_munic" value="<?=@$z01_munic?>" size="20" maxlength="20" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_uf?>">
            <?=@$Lz01_uf?>
          </td>
          <td nowrap><input title="<?=@$Tz01_uf?>" name="z01_uf" type="text" id="z01_uf" value="<?=@$z01_uf?>" size="2" maxlength="2" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_bairro?>" onClick="javascript: js_abreJanelaBairros();">
            <?=@$Lz01_bairro?>
          </td>
          <td nowrap><input  title="<?=@$Tz01_bairro?>"  onBlur="javascript: js_abreJanelaCODBairro();" type="text" name="codbairro" id="codbairro" size="5" maxlength="4" <? if ($db_opcao=="Excluir") {echo " disabled";}?>>
            &nbsp; <input title="<?=@$Tj13_nome?>" name="z01_bairro" type="text" id="z01_bairro" value="<?=@$z01_bairro?>" size="40" maxlength="20" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_cep?>">
            <?=@$Lz01_cep?>
          </td>
          <td nowrap><input name="z01_cep" title="<?=@$Tz01_cep?>" type="text" id="z01_cep" value="<?=@$z01_cep?>" size="9" maxlength="9" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_telef?>">
            <?=@$Lz01_telef?>
          </td>
          <td nowrap><input name="z01_telef" title="<?=@$Tz01_telef?>" type="text" id="z01_telef" value="<?=@$z01_telef?>" size="12" maxlength="12" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_telcel?>">
            <?=@$Lz01_telcel?>
          </td>
          <td nowrap><input name="z01_telcel" title="<?=@$Tz01_telcel?>" type="text" id="z01_telcel" value="<?=@$z01_telcel?>" size="12" maxlength="12" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_email?>">
            <?=@$Lz01_email?>
          </td>
          <td nowrap><input name="z01_email" title="<?=@$Tz01_email?>" type="text" id="z01_email" value="<?=@$z01_email?>" size="30" maxlength="30" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_cxpostal?>">
            <?=@$Lz01_cxpostal?>
          </td>
          <td nowrap><input name="z01_cxpostal" title="<?=@$Tz01_cxpostal?>" type="text" id="z01_cxpostal" value="<?=@$z01_cxpostal?>" size="10" maxlength="10" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
      </table></td>
    <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td nowrap title="<?=@$Tz01_endcon?>"  onClick="javascript: js_abreJanelaLogradouroCON();">
            <?=@$Lz01_endcon?>
          </td>
          <td nowrap><input title="<?=@$Tz01_endcon?>" onBlur="javascript: js_abreJanelaCODLogradouroCON();" type="text" name="codruacon" id="codruacon" size="5" maxlength="4" <? if ($db_opcao=="Excluir") {echo " disabled";}?>>
            &nbsp;
            <input name="z01_endcon" type="text" id="z01_endcon" value="<?=@$z01_endcon?>" size="40" maxlength="40"<? if ($opcao=="Excluir") {echo " disabled";}?>> 
          </td>
        </tr>
        <tr> 
          <td width="29%" nowrap title="<?=@$Tz01_numcon?>">
            <?=@$Lz01_numcon?>
          </td>
          <td width="71%" nowrap > <a name="AN3"> 
            <input title="<?=@$Tz01_numcon?>" name="z01_numcon" type="text" id="z01_numcon" value="<?=@$z01_numcon?>" size="8" maxlength="7" <? if ($opcao=="Excluir") {echo " disabled";}?>>
            <?=@$Lz01_comcon?>
            <input name="z01_comcon" title="<?=@$Tz01_comcon?>" type="text" id="z01_comcon" value="<?=@$z01_comcon?>" size="20" maxlength="20">
            </a> </td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_muncon?>">
            <?=@$Lz01_muncon?>
          </td>
          <td nowrap><input name="z01_muncon" title="<?=@$Tz01_muncon?>" type="text" id="z01_muncon" value="<?=@$z01_muncon?>" size="20" maxlength="20" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=$Tz01_ufcon?>">
            <?=@$Lz01_ufcon?>
          </td>
          <td nowrap><input name="z01_ufcon" title="<?=$Tz01_ufcon?>" type="text" id="z01_ufcon" value="<?=@$z01_ufcon?>" size="2" maxlength="2" <? if ($db_opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_baicon?>" onClick="javascript: js_abreJanelaBairrosCON();">
            <?=@$Lz01_baicon?>
          </td>
          <td nowrap><input title="<?=@$Tz01_baicon?>" onBlur="javascript: js_abreJanelaCODBairroCON();" type="text" name="codbaicon" id="codbaicon" size="5" maxlength="4" <? if ($db_opcao=="Excluir") {echo " disabled";}?>>
            &nbsp;
            <input name="z01_baicon" type="text" id="z01_baicon" value="<?=@$z01_baicon?>" size="40" maxlength="20" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_cepcon?>">
            <?=@$Lz01_cepcon?>
          </td>
          <td nowrap><input name="z01_cepcon" title="<?=@$Tz01_cepcon?>" type="text" id="z01_cepcon" value="<?=@$z01_cepcon?>" size="9" maxlength="9" <? if ($db_opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_telcon?>">
            <?=@$Lz01_telcon?>
          </td>
          <td nowrap><input name="z01_telcon" title="<?=@$Tz01_telcon?>" type="text" id="z01_telcon" value="<?=@$z01_telcon?>" size="12" maxlength="12" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_celcon?>">
            <?=@$Lz01_celcon?>
          </td>
          <td nowrap><input name="z01_celcon" title="<?=@$Tz01_celcon?>" type="text" id="z01_celcon" value="<?=@$z01_celcon?>" size="12" maxlength="12" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_emailc?>">
            <?=@$Lz01_emailc?>
          </td>
          <td nowrap><input name="z01_emailc" title="<?=@$Tz01_emailc?>" type="text" id="z01_emailc" value="<?=@$z01_emailc?>" size="30" maxlength="30" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
        <tr> 
          <td nowrap title="<?=@$Tz01_cxposcon?>">
            <?=@$Lz01_cxposcon?>
          </td>
          <td nowrap><input name="z01_cxposcon" title="<?=@$Tz01_cxposcon?>" type="text" id="z01_cxposcon" value="<?=@$z01_cxposcon?>" size="10" maxlength="10" <? if ($opcao=="Excluir") {echo " disabled";}?>></td>
        </tr>
      </table></td>
  </tr>
	  <tr align="center" valign="middle"> 
    <td height="30" colspan="2" nowrap> <input name="db_opcao" type="submit" id="db_opcao" value="<?=$db_opcao?>" <?=($db_botao==false?"disabled":"")?>>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_nome();"> 
    </tr>
</table>
</form>
<script>
function js_preenche(chave){
  func_nome.hide();
//  alert('<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave);
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_func_nome(){
  func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenche|0';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}
</script>
<?
  $listaFuncbairros = new janela("func_nome","");
  $listaFuncbairros->posX=1;
  $listaFuncbairros->posY=20;
  $listaFuncbairros->largura=785;
  $listaFuncbairros->altura=430;
  $listaFuncbairros->titulo="Pesquisa Nomes";
  $listaFuncbairros->iniciarVisivel = false;
  $listaFuncbairros->mostrar();
?>