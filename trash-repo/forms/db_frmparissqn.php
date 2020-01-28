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

//MODULO: issqn
$clparissqn->rotulo->label();
$clTipoAlvara->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("q92_descr");
$clrotulo->label("q60_tiponumcertbaixa");
$clrotulo->label("db82_descricao");
$clrotulo->label("k01_descr");  
?>
<style>
fieldset {
 margin:  10px auto;
 width:   700px;
}
fieldset table tr td:first-child {
  left:0;
  width: 250px;
}
fieldset fieldset table tr td:first-child {
  left:0;
  width: 235px;
}


#fieldSetImpressaoAlvara select {
 width: 250px;
}

#q60_campoutilcalc,
#q60_integrasani,
#q60_tipopermalvara,
#q60_tiponumcertbaixa,
#q60_bloqemiscertbaixa{
 width: 368px;
}
</style>
<form name="form1" id="form1" method="post" action="">
  <fieldset style="margin: 25px auto 0 auto;">
    <legend><strong>Par�metros do C�lculo</strong></legend>
    
    <table>
      <tr id="receita">
        <td>
        
          <input name="oid" type="hidden" value="<?=@$oid?>">
          
		 	    <?
			      db_ancora(@$Lq60_receit,"js_pesquisaq60_receit(true);",$db_opcao);
			    ?>
			    
        </td>
        
        <td>
          <?php 
            db_input('q60_receit', 10, $Iq60_receit, true, 'text', $db_opcao, " onchange='js_pesquisaq60_receit(false);'");
            
            db_input('k02_descr', 40, $Ik02_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      
      <tr id="tipodebito">
        <td>
          <?
            db_ancora(@$Lq60_tipo,"js_pesquisaq60_tipo(true);",$db_opcao);
          ?>
        </td>
        <td> 
        	<?
          	db_input('q60_tipo', 10, $Iq60_tipo, true, 'text', $db_opcao, " onchange='js_pesquisaq60_tipo(false);'");
            db_input('k00_descr', 40, $Ik00_descr, true, 'text', 3, '');
          ?>
        </td>
      </tr>
					  
      <tr id="aliquota">
        <td>
          <?=@$Lq60_aliq?>
        </td>
        <td> 
          <?
      	    db_input('q60_aliq', 10, $Iq60_aliq, true, 'text', $db_opcao)
          ?>
        </td>
      </tr>		  
      
      <tr id="vencimento">
        <td>
          <?
            db_ancora(@$Lq60_codvencvar,"js_pesquisaq60_codvencvar(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('q60_codvencvar',10,$Iq60_codvencvar,true,'text',$db_opcao," onchange='js_pesquisaq60_codvencvar(false);'");
            db_input('q92_descr',40,$Iq92_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      
      <tr id="historicocalculo">
        <td>
          <?php
            db_ancora($Lq60_histsemmov, "js_pesquisaHistoricoCalculo(true)", $db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('q60_histsemmov', 10, $Iq60_histsemmov, true, 'text', $db_opcao, "");
            db_input('k01_descr',      40, $Ik01_descr,      true, 'text', 3        , "");
          ?>
        </td>
      </tr>
      
      <tr id="parcelamento">
        <td>
          <?=@$Lq60_parcelasalvara?>
        </td>
        <td>
          <?php
            db_input('q60_parcelasalvara', 10, $Iq60_parcelasalvara, true, 'text', $db_opcao, "onchange='js_validaParcelas(this.value)'"); 
          ?>
        </td>
      </tr>
					 
			<tr id="variavelcalculo">
        <td>
          <?=@$Lq60_campoutilcalc?>
        </td>
        <td> 
          <?
     				$aCampoUtilCalc = array('1' => 'Area', '2' => 'Quantidade de funcionarios', '3' => 'Pontua��o');
            db_select('q60_campoutilcalc',$aCampoUtilCalc, true, $db_opcao);
            
          ?>
        </td>
      </tr>		  
      
    </table>
    
  </fieldset> <!-- Par�metros c�lculo -->

  <fieldset>
    <legend><strong>Par�metros do Alvar�</strong></legend>
  
    <table>
      <tr id="integracaosanitario">
        <td>
          <?=@$Lq60_integrasani?>
        </td>
        <td> 
  	      <?
    	      $aIntegracaoSanitario = array('0'=>'Nenhuma','1'=>'Inscri��es com porte para pessoa juridica','2'=>'Integra��o por classe');
    	      db_select('q60_integrasani', $aIntegracaoSanitario, true, $db_opcao);
          ?>
        </td>
      </tr>
      
      <tr id="permitealteracao">
        <td nowrap>
          <?=@$Lq60_tipopermalvara?>
        </td>
        <td>
          <?
          	$aPermissao = array('0'=>'N�o utiliza controle de permiss�o', '1'=>'Liberado com permiss�o para alterar alvar� com cnpj.');
          	db_select('q60_tipopermalvara', $aPermissao, true, $db_opcao);
          ?>
        </td>
      </tr>
      
			<tr id="alvarapermanente">
				<td>
				  <input name="oid" type="hidden" value="<?=@$oid?>"> 
  				  <?
  					  db_ancora(@$Lq60_isstipoalvaraper,"js_pesquisaq60_isstipoalvaraper(true);",$db_opcao);
  					?>
				</td>
				<td>
  				<?
    				db_input('q60_isstipoalvaraper',10,$Iq60_isstipoalvaraper,true,'text',$db_opcao," onchange='js_pesquisaq60_isstipoalvaraper(false);'");
    				db_input('q98_descricaoper',40,$Iq98_descricao,true,'text',3,'');
  				?>
				</td>
			</tr>
			
			<tr id="alvaraprovisorio">
				<td>
				<input name="oid" type="hidden" value="<?=@$oid?>"> 
				  <?
					  db_ancora(@$Lq60_isstipoalvaraprov,"js_pesquisaq60_isstipoalvaraprov(true);",$db_opcao);
					?>
				</td>
				<td nowrap>
				  <?
            db_input('q60_isstipoalvaraprov',10,$Iq60_isstipoalvaraprov,true,'text',$db_opcao," onchange='js_pesquisaq60_isstipoalvaraprov(false);'");
            db_input('q98_descricaoprov',40,$Iq98_descricao,true,'text',3,'');
          ?>
				</td>
			</tr>

			<tr id="permitebaixaalvaradivida">
				<td>
				  <?=@$Lq60_alvbaixadiv?>
				</td>
				<td>
				  <?
				    $aAlvaraDivida = array('0'=>'N�o','1'=>'Sim');
				    db_select('q60_alvbaixadiv',$aAlvaraDivida, true, $db_opcao);
				  ?>
				</td>
			</tr>
			
			<tr>
			  <td colspan="2">
			  
			    <fieldset style="margin: 0 auto; width: 600px" id="fieldSetImpressaoAlvara">
			      <legend><strong>Par�metros de Impress�o do Alvar�</strong></legend>

			      <table>
			        <tr id="imprimecodigoatividade">
					      <td>
					        <?=@$Lq60_impcodativ?>
					      </td>
					      <td> 
								  <?
									  $aImprimeCodigoAtividade = array('f'=>'N�O','t'=>'SIM');
									  db_select('q60_impcodativ',$aImprimeCodigoAtividade, true, $db_opcao);
								  ?>
					      </td>
					    </tr>
					    
  					  <tr id="imprimeobservacaoatividade">
  					    <td nowrap>
  					      <?=@$Lq60_impobsativ?>
  					    </td>
  					    <td> 
  								<?  
  									$aImprimeObservacoesAtividade = array('f'=>'N�O','t'=>'SIM');
  									db_select('q60_impobsativ',$aImprimeObservacoesAtividade, true, $db_opcao);
  								?>
  					    </td>
  					  </tr>
  					  
							<tr id="imprimedatas">
								<td>
								  <?=@$Lq60_impdatas?>
								</td>
								<td>
								  <?
								    $aImprimeDatas = array('f'=>'N�O','t'=>'SIM');
								    db_select('q60_impdatas', $aImprimeDatas, true, $db_opcao);
								  ?>
								</td>
							</tr>

							<tr id="imprimeobservacoesissqn">
								<td nowrap>
								  <?=@$Lq60_impobsissqn?>
								</td>
								<td>
								  <?
    								$aImprimeObservacoesIssqn = array('f'=>'N�O','t'=>'SIM');
    								db_select('q60_impobsissqn',$aImprimeObservacoesIssqn, true, $db_opcao);
  								?>
								</td>
							</tr>

							<tr id="modeloalvara">
								<td nowrap>
								  <?=@$Lq60_modalvara?>
								</td>
								<td>
								  <?
    								$aModeloAlvara = array('1'=>'A5',
                      								     '2'=>'A4',
                      								     '3'=>'Pr�-impresso',
                      								     '4'=>'A4 fonte reduzida',
                      								     '5'=>'Pr�-impresso tamanho A4',
                      								     '6'=> 'Pr�-impresso A4 com c�digo cnae',
                      								     '7'=> 'A4 Frente/Verso',
                      								     '8'=> 'A4 Processo/�rea',
                      								     '9'=> 'Documento Alvar�');
    								
    								db_select('q60_modalvara', $aModeloAlvara, true, $db_opcao, "onchange='js_trtemplatealvara();'");
  								?>
								</td>
							</tr>

							<tr style="display: none" id="lab_templatealvara">
								<td>
								  <?
								    $q60_templatealvara =1;
								    db_ancora(@$Lq60_templatealvara,"js_pesquisaq60_templatealvara(true);",$db_opcao);
								  ?>
								</td>
								<td>
								  <?
								    db_input('q60_templatealvara',10,$Iq60_templatealvara,true,'text',$db_opcao," onchange='js_pesquisaq60_templatealvara(false);'");
								    db_input('db82_descricao',40,$Idb82_descricao,true,'text',3,'');
								  ?>
								</td>
							</tr>
																										
						</table>
						
			    </fieldset>
			  </td>
			</tr>
		</table>
  </fieldset><!-- Par�metros Alvar� -->
  
  <fieldset>
    <legend><strong>Nota Fiscal Avulsa</strong></legend>
    
    <table>
			<tr id="notaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsapesjur?>
				</td>
				<td>
				  <?
				    $aNotaFiscalAvulsaPerjur = array("f"=>"NAO","t"=>"SIM");
				    db_select('q60_notaavulsapesjur', $aNotaFiscalAvulsaPerjur, true, $db_opcao);
				  ?>
				</td>
			</tr>
			
			<tr id="numeroviasnotaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsavias?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsavias', 10, $Iq60_notaavulsavias, true, 'text', $db_opcao);
				  ?>
				</td>
			</tr>

			<tr id="notaavulsavalorminimo">
				<td nowrap>
				  <?=@$Lq60_notaavulsavlrmin?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsavlrmin',10,$Iq60_notaavulsavlrmin,true,'text',$db_opcao,"");
				  ?>
				</td>
			</tr>
			
			<tr id="numeromaximonotasavulsas">
				<td>
				  <?=@$Lq60_notaavulsamax?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsamax',10,$Iq60_notaavulsamax,true,'text',$db_opcao,"")
				  ?>
				</td>
			</tr>

			<tr id="numeroultimanotaavulsa">
				<td>
				  <?=@$Lq60_notaavulsaultimanota?>
				</td>
				<td>
				  <?
    				if (!isset($q60_notaavulsaultimanota) || trim($q60_notaavulsaultimanota)=="") {
    				  $q60_notaavulsaultimanota = 1;
    				}
    				db_input('q60_notaavulsaultimanota',10,$Iq60_notaavulsaultimanota,true,'text',3,"");
          ?>
				</td>
			</tr>

			<tr id="diasprazonotaavulsa">
				<td nowrap>
				  <?=@$Lq60_notaavulsadiasprazo?>
				</td>
				<td>
				  <?
				    db_input('q60_notaavulsadiasprazo',10,$Iq60_notaavulsadiasprazo,true,'text',$db_opcao,"");
				  ?>
				</td>
			</tr>
		</table>
  </fieldset>
  
  <fieldset>
    <legend><strong>Outros Dados</strong></legend>
    
    <table>
			<tr id="tiponumeracaocertidao">
				<td nowrap >
				  <?=@$Lq60_tiponumcertbaixa?>
				</td>
				<td>
				  <?
    				$aNumeracaoCertidaoBaixa = array('1'=>'Utiliza n�mero do processo','2'=>'Sequencial','3'=>'Sequencial por exerc�cio');
    				db_select('q60_tiponumcertbaixa', $aNumeracaoCertidaoBaixa, true, $db_opcao);
          ?>
				</td>
			</tr>
			
			<tr id="bloqueiaemissaocertidao">
				<td nowrap>
				  <?=@$Lq60_bloqemiscertbaixa?>
				</td>
				<td>
				  <?  
				    $aBloqueioEmissaoCertidao = array(1 => 'Nunca bloqueia',
				                                      2 => 'Avisa que tem d�bito e n�o bloqueia',
				                                      3 => 'Avisa que tem debito e bloqueia');
				    db_select('q60_bloqemiscertbaixa', $aBloqueioEmissaoCertidao, true, $db_opcao);
				  ?>
				</td>
			</tr>			
			
			<tr id="dataimplantacaomei">
				<td nowrap>
				  <?=@$Lq60_dataimpmei?>
				</td>
				<td>
				  <?
				    db_inputdata('q60_dataimpmei',@$q60_dataimpmei_dia,@$q60_dataimpmei_mes,@$q60_dataimpmei_ano,true,'text',$db_opcao,'');
				  ?>
				</td>
			</tr>

		</table>
  </fieldset>
  
  <center>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>

<script>
function js_validaParcelas(iParcelas) {

  if (iParcelas == '' || iParcelas == '0') {
    alert('N�mero de parcelas informado � inv�lido.');
    return false;
  }
  
}
function js_pesquisaq60_isstipoalvaraper(mostra){
  
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_grupotipo',
                        'func_isstipoalvara.php?cadastro=t&tipo=1&funcao_js=parent.js_mostratipoper|q98_sequencial|q98_descricao',
                        'Pesquisa',
                        true
                       );
  }else{
     if(document.form1.q60_isstipoalvaraper.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_grupotipo',
                            'func_isstipoalvara.php?cadastro=t&tipo=1&pesquisa_chave='+document.form1.q60_isstipoalvaraper.value+'&funcao_js=parent.js_mostratipoper1',
                            'Pesquisa',
                            false
                           );
     }else{
       document.form1.q98_descricaoper.value = ''; 
     }
  }
}
function js_mostratipoper(chave,chave2, erro) {

  document.form1.q60_isstipoalvaraper.value = chave;
  document.form1.q98_descricaoper.value          = chave2; 
  if (erro==true) {
   
    document.form1.q60_isstipoalvaraper.focus(); 
    document.form1.q60_isstipoalvaraper.value = ''; 
  }
  db_iframe_grupotipo.hide();
}
function js_mostratipoper1(chave1,chave2) {

  //document.form1.q60_isstipoalvaraper.value = chave1;
  document.form1.q98_descricaoper.value     = chave1;
  db_iframe_grupotipo.hide();
}

function js_pesquisaq60_isstipoalvaraprov(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_grupotipo',
                        'func_isstipoalvara.php?cadastro=t&tipo=2&funcao_js=parent.js_mostratipoprov|q98_sequencial|q98_descricao',
                        'Pesquisa',
                        true
                       );
  }else{
     if(document.form1.q60_isstipoalvaraprov.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_grupotipo',
                            'func_isstipoalvara.php?cadastro=t&tipo=2&pesquisa_chave='+document.form1.q60_isstipoalvaraprov.value+'&funcao_js=parent.js_mostratipoprov1',
                            'Pesquisa',
                            false
                           );
     }else{
       document.form1.q98_descricaoprov.value = ''; 
     }
  }
}
function js_mostratipoprov(chave,chave2, erro) {

  document.form1.q60_isstipoalvaraprov.value = chave;
  document.form1.q98_descricaoprov.value = chave2; 
  if(erro==true){ 
    document.form1.q60_isstipoalvaraprov.focus(); 
    document.form1.q60_isstipoalvaraprov.value = ''; 
  }
  db_iframe_grupotipo.hide();
}
function js_mostratipoprov1(chave1,chave2) {

  if (chave2 == false) {
    document.form1.q98_descricaoprov.value     =  chave1;//   = chave2;
  } else {
  
     document.form1.q98_descricaoprov.value = "Chave("+document.form1.q60_isstipoalvaraprov.value+") n�o Encontrado"
     document.form1.q60_isstipoalvaraprov.value = "";
  }
  db_iframe_grupotipo.hide();
}


function js_pesquisaq60_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_receit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.q60_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_receit.focus(); 
    document.form1.q60_receit.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.q60_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaq60_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.q60_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_tipo.focus(); 
    document.form1.q60_tipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.q60_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
}

function js_pesquisaq60_templatealvara(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documentotemplate','func_db_documentotemplate.php?funcao_js=parent.js_mostratemplatealvara1|db82_sequencial|db82_descricao&tipo=6','Pesquisa',true);
  }else{
     if(document.form1.q60_templatealvara.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documentotemplate','func_db_documentotemplate.php?pesquisa_chave='+document.form1.q60_templatealvara.value+'&funcao_js=parent.js_mostratemplatealvara&tipo=6','Pesquisa',false);
     }else{
       document.form1.db82_descricao.value = ''; 
     }
  }
}
function js_mostratemplatealvara(chave,erro){
  document.form1.db82_descricao.value = chave; 
  if(erro==true){ 
    document.form1.q60_templatealvara.focus(); 
    document.form1.q60_templatealvara.value = ''; 
  }
}
function js_mostratemplatealvara1(chave1,chave2){
  document.form1.q60_templatealvara.value = chave1;
  document.form1.db82_descricao.value = chave2;
  db_iframe_db_documentotemplate.hide();
}
function js_trtemplatealvara(){
  if( document.form1.q60_modalvara.value == '9' ) {
    //document.getElementById('lab_templatealvara').style.display = '';        
  } else  {    
    document.getElementById('lab_templatealvara').style.display = 'none';    
  }
 }
function js_pesquisaq60_codvencvar(mostra){ 
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?funcao_js=parent.js_mostracadvencdesc1|q92_codigo|q92_descr','Pesquisa',true);
  }else{
     if(document.form1.q60_codvencvar.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadvencdesc','func_cadvencdesc.php?pesquisa_chave='+document.form1.q60_codvencvar.value+'&funcao_js=parent.js_mostracadvencdesc','Pesquisa',false);
     }else{
       document.form1.q92_descr.value = ''; 
     }
  }
}
function js_mostracadvencdesc(chave,erro){
  document.form1.q92_descr.value = chave; 
  if(erro==true){ 
    document.form1.q60_codvencvar.focus(); 
    document.form1.q60_codvencvar.value = ''; 
  }
}
function js_mostracadvencdesc1(chave1,chave2){
  document.form1.q60_codvencvar.value = chave1;
  document.form1.q92_descr.value = chave2;
  db_iframe_cadvencdesc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_parissqn','func_parissqn.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_parissqn.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

js_trtemplatealvara();
function js_pesquisaHistoricoCalculo( lMostraJanela ){ 

  if ( lMostraJanela ) {
    js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?funcao_js=parent.js_mostraHistoricoCalculoLookUp|k01_codigo|k01_descr','Pesquisa Hist�rico de Calculo', true);
  } else {

     if ( $F('q60_histsemmov') != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_histcalc','func_histcalc.php?pesquisa_chave=' + $F('q60_histsemmov') + '&funcao_js=parent.js_mostraHistoricoCalculoDigitacao', 'Pesquisa', false);
     }else{
       $('k01_descr').value = ''; 
     }
  }
}
function js_mostraHistoricoCalculoDigitacao(chave, lErro) {

  $('k01_descr').value = chave; 
  if( lErro ) { 
    $('q60_histsemmov').focus(); 
    $('q60_histsemmov').value = ''; 
  }
}

function js_mostraHistoricoCalculoLookUp( iCodigoHistorico, sDescricaoHistorico) {

  $('q60_histsemmov').value = iCodigoHistorico;
  $('k01_descr').value      = sDescricaoHistorico;
  db_iframe_histcalc.hide();
}


/**
 * Hints do formulario
 */

var aEventoShow = new Array('onMouseover','onFocus');
var aEventoHide = new Array('onMouseout' ,'onBlur');

var oDbHintReceita = new DBHint('oDbHintReceita');
    oDbHintReceita.setText('Receita padr�o para o c�lculo geral de ISSQN. ');
    oDbHintReceita.setShowEvents(aEventoShow);
    oDbHintReceita.setHideEvents(aEventoHide);
    oDbHintReceita.make($('receita')); 
     
var oDbHintTipoDebito = new DBHint('oDbHintTipoDebito');
    oDbHintTipoDebito.setText('Podera informar qual o tipo de d�bito que ser� lan�ado o valor calculado no c�lculo geral de ISSQN. ');
    oDbHintTipoDebito.setShowEvents(aEventoShow);
    oDbHintTipoDebito.setHideEvents(aEventoHide);
    oDbHintTipoDebito.make($('tipodebito')); 

var oDbHintAliquota = new DBHint('oDbHintAliquota');
    oDbHintAliquota.setText('Nesse campo ir� informar a Aliquota Padr�o para o c�lculo de ISSQN complementar.');
    oDbHintAliquota.setShowEvents(aEventoShow);
    oDbHintAliquota.setHideEvents(aEventoHide);
    oDbHintAliquota.make($('aliquota')); 

var oDbHintVencimento = new DBHint('oDbHintVencimento');
    oDbHintVencimento.setText('Nesse campo ir� informar o C�digo do Vencimento que o sistema utilizar� no c�lculo de ISSQN.');
    oDbHintVencimento.setShowEvents(aEventoShow);
    oDbHintVencimento.setHideEvents(aEventoHide);
    oDbHintVencimento.make($('vencimento')); 
   
var oDbHintHistoricoCalculo = new DBHint('oDbHintHistoricoCalculo');
    oDbHintHistoricoCalculo.setText('Nesse campo ir� informar o Hist�rico de C�lculo que ser� vinculado ao valor c�lculado de ISSQN.');
    oDbHintHistoricoCalculo.setShowEvents(aEventoShow);
    oDbHintHistoricoCalculo.setHideEvents(aEventoHide);
    oDbHintHistoricoCalculo.make($('historicocalculo')); 

var oDbHintParcelamento = new DBHint('oDbHintParcelamento');
    oDbHintParcelamento.setText('Nesse campo ir� informar o n�mero m�ximo de parcelas do c�lculo de alvar�.');
    oDbHintParcelamento.setShowEvents(aEventoShow);
    oDbHintParcelamento.setHideEvents(aEventoHide);
    oDbHintParcelamento.make($('parcelamento'));  

var oDbHintVariavelCalculo = new DBHint('oDbHintVariavelCalculo');
    oDbHintVariavelCalculo.setText('Poder� selecionar qual a vari�vel que ser� usada no c�lculo de Alvar� e Vistoria. Pode ser quantidade de funcion�rios , �rea ou pontua��o.');
    oDbHintVariavelCalculo.setShowEvents(aEventoShow);
    oDbHintVariavelCalculo.setHideEvents(aEventoHide);
    oDbHintVariavelCalculo.make($('variavelcalculo'));    

var oDbHintIntegracaoSanitario = new DBHint('oDbHintIntegracaoSanitario');
    oDbHintIntegracaoSanitario.setText('Poder� selecionar se ao incluir uma inscri��o ser� gerado automaticamente um alvar� sanit�rio.');
    oDbHintIntegracaoSanitario.setShowEvents(aEventoShow);
    oDbHintIntegracaoSanitario.setHideEvents(aEventoHide);
    oDbHintIntegracaoSanitario.make($('integracaosanitario')); 
    
var oDbHintPermiteAlteracao = new DBHint('oDbHintPermiteAlteracao');
    oDbHintPermiteAlteracao.setText('Poder� definir se permite alterar o CGM vinculado a inscri��o.');
    oDbHintPermiteAlteracao.setShowEvents(aEventoShow);
    oDbHintPermiteAlteracao.setHideEvents(aEventoHide);
    oDbHintPermiteAlteracao.make($('permitealteracao'));
     
var oDbHintAlvaraPermanente = new DBHint('oDbHintAlvaraPermanente');
    oDbHintAlvaraPermanente.setText('Define qual � o tipo de alvar� padr�o quando for inclu�do alvar� autom�tico.');
    oDbHintAlvaraPermanente.setShowEvents(aEventoShow);
    oDbHintAlvaraPermanente.setHideEvents(aEventoHide);
    oDbHintAlvaraPermanente.make($('alvarapermanente'));
     
var oDbHintAlvaraProvisorio = new DBHint('oDbHintAlvaraProvisorio');
    oDbHintAlvaraProvisorio.setText('Define qual � o tipo de alvar� padr�o quando for inclu�do alvar� autom�tico.');
    oDbHintAlvaraProvisorio.setShowEvents(aEventoShow);
    oDbHintAlvaraProvisorio.setHideEvents(aEventoHide);
    oDbHintAlvaraProvisorio.make($('alvaraprovisorio'));   

var oDbHintPermiteBaixaAlvaraDivida = new DBHint('oDbHintPermiteBaixaAlvaraDivida');
    oDbHintPermiteBaixaAlvaraDivida.setText('Permite baixar inscri��es com d�vidas no sistema.');
    oDbHintPermiteBaixaAlvaraDivida.setShowEvents(aEventoShow);
    oDbHintPermiteBaixaAlvaraDivida.setHideEvents(aEventoHide);
    oDbHintPermiteBaixaAlvaraDivida.make($('permitebaixaalvaradivida'));       

var oDbHintImprimeCodigoAtividade = new DBHint('oDbHintImprimeCodigoAtividade');
    oDbHintImprimeCodigoAtividade.setText('Exibe o campo \'C�digo das Atividades\' no Alvar�.');
    oDbHintImprimeCodigoAtividade.setShowEvents(aEventoShow);
    oDbHintImprimeCodigoAtividade.setHideEvents(aEventoHide);
    oDbHintImprimeCodigoAtividade.make($('imprimecodigoatividade'));  
    
var oDbHintImprimeObservacoesAtividade = new DBHint('oDbHintImprimeObservacoesAtividade');
    oDbHintImprimeObservacoesAtividade.setText('Exibe o campo \'Observa��es das Atividades\' no Alvar�.');
    oDbHintImprimeObservacoesAtividade.setShowEvents(aEventoShow);
    oDbHintImprimeObservacoesAtividade.setHideEvents(aEventoHide);
    oDbHintImprimeObservacoesAtividade.make($('imprimeobservacaoatividade'));

var oDbHintImprimeDatas = new DBHint('oDbHintImprimeDatas');
    oDbHintImprimeDatas.setText('Exibe as datas de vencimento do Alvar�.');
    oDbHintImprimeDatas.setShowEvents(aEventoShow);
    oDbHintImprimeDatas.setHideEvents(aEventoHide);
    oDbHintImprimeDatas.make($('imprimedatas'));
    
var oDbHintImprimeObservacoesIssqn = new DBHint('oDbHintImprimeObservacoesIssqn');
    oDbHintImprimeObservacoesIssqn.setText('Exibe as datas de vencimento do Alvar�.');
    oDbHintImprimeObservacoesIssqn.setShowEvents(aEventoShow);
    oDbHintImprimeObservacoesIssqn.setHideEvents(aEventoHide);
    oDbHintImprimeObservacoesIssqn.make($('imprimeobservacoesissqn'));
	
var oDbHintModeloAlvara = new DBHint('oDbHintModeloAlvara');
    oDbHintModeloAlvara.setText('Nesse campo poder� ser selecionado o modelo que ser� impresso o Alvar�.');
    oDbHintModeloAlvara.setShowEvents(aEventoShow);
    oDbHintModeloAlvara.setHideEvents(aEventoHide);
    oDbHintModeloAlvara.make($('modeloalvara'));
    
var oDbHintModeloTemplateAlvara = new DBHint('oDbHintModeloTemplateAlvara');
    oDbHintModeloTemplateAlvara.setText('Nesse campo poder� ser selecionado o template que ser� impresso o Alvar�.');
    oDbHintModeloTemplateAlvara.setShowEvents(aEventoShow);
    oDbHintModeloTemplateAlvara.setHideEvents(aEventoHide);
    oDbHintModeloTemplateAlvara.make($('lab_templatealvara'));

var oDbHintNotaAvulsa = new DBHint('oDbHintNotaAvulsa');
    oDbHintNotaAvulsa.setText('Permite emitir  Nota Avulsa mesmo que o cadastro seja de pessoa jur�dica.');
    oDbHintNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNotaAvulsa.make($('notaavulsa')); 

var oDbHintNumeroViasNotaAvulsa = new DBHint('oDbHintNumeroViasNotaAvulsa');
    oDbHintNumeroViasNotaAvulsa.setText('Informar quantidade de vias que ser�o emitidas da Nota Avulsa.');
    oDbHintNumeroViasNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNumeroViasNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNumeroViasNotaAvulsa.make($('numeroviasnotaavulsa'));
			
var oDbHintNotaAvulsaValorMinimo = new DBHint('oDbHintNotaAvulsaValorMinimo');
    oDbHintNotaAvulsaValorMinimo.setText('Valor m�nimo da nota avulsa, caso n�o tenha, basta informar zero.');
    oDbHintNotaAvulsaValorMinimo.setShowEvents(aEventoShow);
    oDbHintNotaAvulsaValorMinimo.setHideEvents(aEventoHide);
    oDbHintNotaAvulsaValorMinimo.make($('notaavulsavalorminimo'));
    
var oDbHintNumeroMaximoNotasAvulsas = new DBHint('oDbHintNumeroMaximoNotasAvulsas');
    oDbHintNumeroMaximoNotasAvulsas.setText('Poder� definir o n�mero m�ximo de notas avulsas que cada CGM ou Inscri��o poder� gerar.');
    oDbHintNumeroMaximoNotasAvulsas.setShowEvents(aEventoShow);
    oDbHintNumeroMaximoNotasAvulsas.setHideEvents(aEventoHide);
    oDbHintNumeroMaximoNotasAvulsas.make($('numeromaximonotasavulsas'));
    
var oDbHintNumeroUltimaNotaAvulsa = new DBHint('oDbHintNumeroUltimaNotaAvulsa');
    oDbHintNumeroUltimaNotaAvulsa.setText('Nesse campo ser� informado o n�mero da Nota Avulsa gerada.');
    oDbHintNumeroUltimaNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintNumeroUltimaNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintNumeroUltimaNotaAvulsa.make($('numeroultimanotaavulsa'));
    
var oDbHintDiasPrazoNotaAvulsa = new DBHint('oDbHintDiasPrazoNotaAvulsa');
    oDbHintDiasPrazoNotaAvulsa.setText('Poder�  informar a quantidade de dias que ser� o vencimento da Nota Avulsa emitida.');
    oDbHintDiasPrazoNotaAvulsa.setShowEvents(aEventoShow);
    oDbHintDiasPrazoNotaAvulsa.setHideEvents(aEventoHide);
    oDbHintDiasPrazoNotaAvulsa.make($('diasprazonotaavulsa'));

var oDbHintTipoNumeracaoCertidao = new DBHint('oDbHintTipoNumeracaoCertidao');
    oDbHintTipoNumeracaoCertidao.setText('Define a numera��o que ser� impresso na certid�o de baixa.');
    oDbHintTipoNumeracaoCertidao.setShowEvents(aEventoShow);
    oDbHintTipoNumeracaoCertidao.setHideEvents(aEventoHide);
    oDbHintTipoNumeracaoCertidao.make($('tiponumeracaocertidao'));
    
var oDbHintBloqueiaEmissaoCertidao = new DBHint('oDbHintBloqueiaEmissaoCertidao');
    oDbHintBloqueiaEmissaoCertidao.setText('Poder� definir se permite a gera��o de certid�o de baixa quando a inscri��o possuir d�bitos.');
    oDbHintBloqueiaEmissaoCertidao.setShowEvents(aEventoShow);
    oDbHintBloqueiaEmissaoCertidao.setHideEvents(aEventoHide);
    oDbHintBloqueiaEmissaoCertidao.make($('bloqueiaemissaocertidao'));
          
var oDbHintDataImplantacaoMei = new DBHint('oDbHintDataImplantacaoMei');
    oDbHintDataImplantacaoMei.setText('Data em que foi feita a implanta��o da rotina do MEI.');
    oDbHintDataImplantacaoMei.setShowEvents(aEventoShow);
    oDbHintDataImplantacaoMei.setHideEvents(aEventoHide);
    oDbHintDataImplantacaoMei.make($('dataimplantacaomei'));   
 
/**
 * Fim hints
 */


</script>