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

//MODULO: compras
$clpcparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc50_descr");
$clrotulo->label("m61_descr");

if (isset($pc30_instit) && $pc30_instit !="") {
  $oDbConf  = new cl_db_config();
  $sSql     = $oDbConf->sql_query($pc30_instit, "nomeinst");
  $rsDbConf = $oDbConf->sql_record($sSql);
  db_fieldsmemory($rsDbConf, 0);
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td>
		<table border="0">
			<tr>
				<td>
				<fieldset>
				  <legend><b>Parâmetros</b></legend>
					<table border="0" align="left">
						<tr>
							<td nowrap title="<?=@$Tpc30_instit?>"><?=@$Lpc30_instit?></td>
							<td>
							<?
							  db_input('pc30_instit',10,$Ipc30_instit,true,'text',3,"");
							  db_input('nomeinst'   ,40,"",true,'text',3,"");
							?>
							</td>
						</tr>
					</table>
          <table border="0">
            <tr>
              <td valign="top">
                <table border="0">
                  <tr>
                    <td>
							        <fieldset>
							          <legend><b>Solicitação de Compra</b></legend>
							        <table border="0">
							          <tr>
							            <td nowrap title="<?=@$Tpc30_tipcom?>" style="width: 275px;">
							            <?
							              db_ancora(@$Lpc30_tipcom,"js_pesquisapc30_tipcom(true);",$db_opcao);
							            ?>
							            </td>
							            <td>
							            <?
							              db_input('pc30_tipcom',4,$Ipc30_tipcom,true,'text',$db_opcao," onchange='js_pesquisapc30_tipcom(false);'");
							              db_input('pc50_descr',40,$Ipc50_descr,true,'text',3,'');
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_unid?>">
							            <?
							              db_ancora(@$Lpc30_unid,"js_pesquisapc30_unid(true);",$db_opcao);
							            ?>
							            </td>
							            <td>
							            <?
							              db_input('pc30_unid',4,$Ipc30_unid,true,'text',$db_opcao," onchange='js_pesquisapc30_unid(false);'");
							              db_input('m61_descr',40,$Im61_descr,true,'text',3,'');
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_obrigajust?>"><?=@$Lpc30_obrigajust?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_obrigajust',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_obrigamat?>"><?=@$Lpc30_obrigamat?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_obrigamat',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_gerareserva?>"><?=@$Lpc30_gerareserva?> </td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_gerareserva',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_seltipo?>"><?=@$Lpc30_seltipo?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_seltipo',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_sugforn?>"><?=@$Lpc30_sugforn?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_sugforn',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_mincar?>"><?=@$Lpc30_mincar?></td>
							            <td>
							            <?
							              db_input('pc30_mincar',6,$Ipc30_mincar,true,'text',$db_opcao,"")
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_permsemdotac?>"><?=@$Lpc30_permsemdotac?> </td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_permsemdotac',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_passadepart?>"><?=@$Lpc30_passadepart?> </td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_passadepart',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_digval?>"><?=@$Lpc30_digval?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_digval',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_tipoemiss?>"><?=@$Lpc30_tipoemiss?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_tipoemiss',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_comsaldo?>"><?=@$Lpc30_comsaldo?></td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_comsaldo',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_contrandsol?>"><?=@$Lpc30_contrandsol?> </td>
							            <td>
							            <?
							              $x = array('f'=>'Não','t'=>'Sim');
							              db_select('pc30_contrandsol',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_ultdotac?>"><?=@$Lpc30_ultdotac?></td>
							            <td>
							            <?
							              $x = array('f'=>'NÂO','t'=>'SIM');
							              db_select('pc30_ultdotac',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_dotacaopordepartamento?>"><?=@$Lpc30_dotacaopordepartamento?> </td>
							            <td>
							            <?
							              $x = array("f"=>"NAO","t"=>"SIM");
							              db_select('pc30_dotacaopordepartamento',$x,true,$db_opcao,"");
							            ?>
							            </td>
							          </tr>
							          <tr>
							            <td nowrap title="<?=@$Tpc30_tipoprocsol?>"><?=@$Lpc30_tipoprocsol?> </td>
							            <td>
							            <?
							              db_input('pc30_tipoprocsol',6,$Ipc30_tipoprocsol,true,'text',$db_opcao,"");
							            ?>
							            </td>
							          </tr>
                        <tr>
                          <td nowrap title="<?=@$Tpc30_consultarelatoriodepartamento?>">
							              <?=@$Lpc30_consultarelatoriodepartamento?> 
                          </td>
                          <td>
                          <?
                            $x = array("0"=>"Todos departamentos", 
                            					 "1"=>"Departamentos do usuário",
                                       "2"=>"Departamento logado");
                            db_select('pc30_consultarelatoriodepartamento',$x,true,$db_opcao,"");
                          ?>
                          </td>
                        </tr>
							        </table>
							        </fieldset>
                    </td>
                  </tr>
                  <tr>
                    <td>
		                  <fieldset>
		                      <legend><b>Orçamentos</b></legend>
		                    <table border="0">
		                      <tr>
		                        <td nowrap title="<?=@$Tpc30_horas?>" style="width: 275px;"><?=@$Lpc30_horas?> </td>
		                        <td>
		                        <?
		                          db_input('pc30_horas',5,$Ipc30_horas,true,'text',$db_opcao,"");
		                        ?>
		                        </td>
		                      </tr>
		                      <tr>
		                        <td nowrap title="<?=@$Tpc30_dias?>"><?=@$Lpc30_dias?></td>
		                        <td>
		                        <?
		                          db_input('pc30_dias',8,$Ipc30_dias,true,'text',$db_opcao,"");
		                        ?>
		                        </td>
		                      </tr>
		                      <tr>
		                        <td nowrap title="<?=@$Tpc30_modeloorcsol?>"><?=@$Lpc30_modeloorcsol?>
		                        </td>
		                        <td>
		                        <?
		                          $x = array('13'=>'Modelo 1','58'=>'Modelo 2','61'=>'Modelo 3');
		                          db_select('pc30_modeloorcsol',$x,true,$db_opcao,"");
		                        ?>
		                        </td>
		                      </tr>
		                      <tr>
		                        <td nowrap title="<?=@$Tpc30_modeloorc?>"><?=@$Lpc30_modeloorc?></td>
		                        <td>
		                        <?
		                          $x = array('13'=>'Modelo 1','54'=>'Modelo 2','62'=>'Modelo 3');
		                          db_select('pc30_modeloorc',$x,true,$db_opcao,"");
		                        ?>
		                        </td>
		                      </tr>
		                    </table>
		                  </fieldset>
                    </td>
                  </tr>
                </table>
              </td>
              <td valign="top">
					      <table>
					        <tr>
					          <td>
					            <fieldset>
					              <Legend><b>Cotação de Preços</b></Legend>
					            <table border="0">
					              <tr>
					                <td title="<?=@$Tpc30_valoraproximadoautomatico?>"><?=@$Lpc30_valoraproximadoautomatico?> </td>
					                <td>
					                <?
					                  $x = array("f"=>"NAO","t"=>"SIM");
					                  db_select('pc30_valoraproximadoautomatico',$x,true,$db_opcao,"style='width:100%'");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_basesolicitacao?>"><?=@$Lpc30_basesolicitacao?> </td>
					                <td>
					                <?
					                  db_input('pc30_basesolicitacao', 10,$Ipc30_basesolicitacao,true,'text',$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_baseprocessocompras?>"><?=@$Lpc30_baseprocessocompras?> </td>
					                <td>
					                <?
					                  db_input('pc30_baseprocessocompras', 10,$Ipc30_baseprocessocompras,true,'text',$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_baseempenhos?>"><?=@$Lpc30_baseempenhos?> </td>
					                <td>
					                <?
					                  db_input('pc30_baseempenhos', 10,$Ipc30_baseempenhos,true,'text',$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_maximodiasorcamento?>"><?=@$Lpc30_maximodiasorcamento?> </td>
					                <td>
					                <?
					                  db_input('pc30_maximodiasorcamento', 10,$Ipc30_maximodiasorcamento,true,'text',$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					            </table>
					            </fieldset>
					          </td>
					        </tr>
					        <tr>
					          <td>
					            <fieldset>
					              <Legend><b>Liberação/Bloqueio de Fornecedores em Débito</b></Legend>
					              <table border="0">
					                <tr>
					                  <td title="<?=@$Tpc30_fornecdeb?>">
					                    <?=@$Lpc30_fornecdeb?>
					                  </td>
					                  <td>
					                  <?
					                    $aFornecDeb = array('1' => 'Permitir sem avisar',
					                                        '2' => 'Permitir com aviso',
					                                        '3' => 'Não permitir com aviso');
					                    db_select('pc30_fornecdeb', $aFornecDeb, true, $db_opcao, " onchange='js_vericaparamfornecdeb();'");
					                  ?>
					                  </td>
					                </tr>
					                <tr>
					                  <td title="<?=@$Tpc30_permitirgerarnotifdebitos?>">
					                    <?=@$Lpc30_permitirgerarnotifdebitos?>
					                  </td>
					                  <td>
					                  <?
					                    $aPermitirGerarNotifDebitos = array('t' => 'SIM',
					                                                        'f' => 'NAO');
					                    db_select('pc30_permitirgerarnotifdebitos', $aPermitirGerarNotifDebitos, true, $db_opcao, "style='width:45%'");
					                  ?>
					                  </td>
					                </tr>
					                <tr>
					                  <td title="<?=@$Tpc30_diasdebitosvencidos?>">
					                    <?=@$Lpc30_diasdebitosvencidos?> 
					                  </td>
					                  <td>
					                    <?
					                      db_input('pc30_diasdebitosvencidos', 10,$Ipc30_diasdebitosvencidos,true,'text',$db_opcao,"");
					                    ?>
					                  </td>
					                </tr>
					                <tr>
					                  <td title="<?=@$Tpc30_notificaemail?>">
					                    <?=@$Lpc30_notificaemail?> 
					                  </td>
					                  <td>
					                    <?
					                      db_input('pc30_notificaemail', 10, $Ipc30_notificaemail, false, 'checkbox', $db_opcao, "pc30_notificaemail");
					                    ?>
					                  </td>
					                </tr>
					                <tr>
					                  <td title="<?=@$Tpc30_notificacarta?>">
					                    <?=@$Lpc30_notificacarta?> 
					                  </td>
					                  <td>
					                    <?
					                      db_input('pc30_notificacarta', 10, $Ipc30_notificacarta, false, 'checkbox', $db_opcao, "pc30_notificacarta");
					                    ?>
					                  </td>
					                </tr>
					              </table>
					            </fieldset>
					          </td>
					        </tr>
					        <tr>
					          <td>
					            <fieldset><legend><b>Liberação</b></legend>
					            <table border="0">
					              <tr>
					                <td nowrap title="<?=@$Tpc30_liberaitem?>" style="width: 275px;"><?=@$Lpc30_liberaitem?></td>
					                <td>
					                <?
					                  $x = array("f"=>"NAO","t"=>"SIM");
					                  db_select('pc30_liberaitem',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_liberado?>"><?=@$Lpc30_liberado?></td>
					                <td>
					                <?
					                  $x = array("f"=>"NAO","t"=>"SIM");
					                  db_select('pc30_liberado',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_libdotac?>"><?=@$Lpc30_libdotac?></td>
					                <td>
					                <?
					                  $x = array("f"=>"NAO","t"=>"SIM");
					                  db_select('pc30_libdotac',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					            </table>
					            </fieldset>
					          </td>
					        </tr>
					        <tr>
					          <td>
					            <fieldset><legend><b>Geral</b></legend>
					            <table border="0">
					              <tr>
					                <td nowrap title="<?=@$Tpc30_itenslibaut?>" style="width: 275px;"><?=@$Lpc30_itenslibaut?></td>
					                <td>
					                <?
					                  $x = array('f'=>'Não','t'=>'Sim');
					                  db_select('pc30_itenslibaut',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_comobs?>"><?=@$Lpc30_comobs?></td>
					                <td>
					                <?
					                  $x = array('f'=>'Não','t'=>'Sim');
					                  db_select('pc30_comobs',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_emiteemail?>"><?=@$Lpc30_emiteemail?> </td>
					                <td>
					                <?
					                  $x = array("f"=>"NAO","t"=>"SIM");
					                  db_select('pc30_emiteemail',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_modeloordemcompra?>"><?=@$Lpc30_modeloordemcompra?> </td>
					                <td>
					                <?
					                  $y = array('10'=>'Modelo 1','57'=>'Modelo 2','60'=>'Modelo 3');
					                  db_select('pc30_modeloordemcompra',$y,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_validadepadraocertificado?>"><?=@$Lpc30_validadepadraocertificado?> </td>
					                <td>
					                <?
					                  db_input('pc30_validadepadraocertificado',6,$Ipc30_validadepadraocertificado,true,'text',$db_opcao,"");
					    
					                  $x = array(0=>"Selecione...",
					                             1=>"Dias",
					                             2=>"Meses",
					                             3=>"Anos");
					                  db_select('pc30_tipovalidade',$x,true,$db_opcao,"");
					                ?>
					                </td>
					              </tr>
					              <tr>
					                <td nowrap title="<?=@$Tpc30_importaresumoemp?>"><?=@$Lpc30_importaresumoemp?> </td>
					                <td>
					                <?
					                  $z = array('t'=>"Sim", 'f'=>'N&atilde;o');
					                  db_select('pc30_importaresumoemp', $z, true, $db_opcao);
					                ?>
					                </td>
					              </tr>
					            </table>
					            </fieldset>
					           </fieldset>
					          </td>
					        </tr>
					      </table>
              </td>
            </tr>
          </table>
        </tr>
      </table>
		</td>
	</tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?>>
</center>
</form>
<script>
function js_pesquisapc30_tipcom(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pctipocompra','func_pctipocompra.php?funcao_js=parent.js_mostrapctipocompra1|pc50_codcom|pc50_descr','Pesquisa',true);
  }else{
     if(document.form1.pc30_tipcom.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pctipocompra','func_pctipocompra.php?pesquisa_chave='+document.form1.pc30_tipcom.value+'&funcao_js=parent.js_mostrapctipocompra','Pesquisa',false);
     }else{
       document.form1.pc50_descr.value = ''; 
     }
  }
}

function js_mostrapctipocompra(chave,erro){
  document.form1.pc50_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc30_tipcom.focus(); 
    document.form1.pc30_tipcom.value = ''; 
  }
}

function js_mostrapctipocompra1(chave1,chave2){
  document.form1.pc30_tipcom.value = chave1;
  document.form1.pc50_descr.value = chave2;
  db_iframe_pctipocompra.hide();
}

function js_pesquisapc30_unid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true);
  }else{
     if(document.form1.pc30_unid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.pc30_unid.value+'&funcao_js=parent.js_mostramatunid','Pesquisa',false);
     }else{
       document.form1.m61_descr.value = ''; 
     }
  }
}

function js_mostramatunid(chave,erro){
  document.form1.m61_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc30_unid.focus(); 
    document.form1.pc30_unid.value = ''; 
  }
}

function js_mostramatunid1(chave1,chave2){
  document.form1.pc30_unid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
}

function js_vericaparamfornecdeb() {
  
  var iFornecDeb = document.form1.pc30_fornecdeb.value;
  if (iFornecDeb == 1) {
    
    document.form1.pc30_permitirgerarnotifdebitos.value           = 'f';
    document.form1.pc30_diasdebitosvencidos.style.backgroundColor = '#DEB887';
    document.form1.pc30_permitirgerarnotifdebitos.disabled        = true;
    document.form1.pc30_diasdebitosvencidos.readOnly              = true;
    document.form1.pc30_notificaemail.checked                     = false;
    document.form1.pc30_notificacarta.checked                     = false;
    document.form1.pc30_notificaemail.disabled                    = true;
    document.form1.pc30_notificacarta.disabled                    = true;
  } else {

    document.form1.pc30_diasdebitosvencidos.style.backgroundColor = '';
    document.form1.pc30_permitirgerarnotifdebitos.disabled        = false;
    document.form1.pc30_diasdebitosvencidos.readOnly              = false;
    document.form1.pc30_notificaemail.disabled                    = false;
    document.form1.pc30_notificacarta.disabled                    = false;
  }
}
</script>