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

//MODULO: educação
$oDaoAluno->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");

?>
<form name="form1" method="post" action="">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr valign="top">
      <td>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" >
                <tr>
                  <td nowrap title="<?=@$Ted47_v_cpf?>">
                    <?=@$Led47_v_cpf?>
                    <?
                      db_input('ed47_v_cpf', 15, @$Ied47_v_cpf, true, 'text', $db_opcao, 
                               "onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");
                    ?>
                  </td>
                  <td>
                    <?=@$Led47_v_ident?>
                    <?db_input('ed47_v_ident', 15, $Ied47_v_ident, true, 'text', $db_opcao);?>
                    
                    <input type="button" value="+" name="ident" 
                           onclick="document.getElementById('identadic').style.visibility='visible'" 
                           style="width:10px;">

                    <table id="identadic" style="visibility:hidden;position:absolute;border:2px outset #000000;" 
                           bgcolor="#CCCCCC" cellspacing="2" cellpading="2">
                      <tr>
                        <td colspan="2">
                          <table width="100%" cellspacing="0" cellpading="0" style="border:2px outset #000000;">
                            <tr bgcolor="blue" >
                              <td style="color:#FFFFFF;font-weight:bold;">
                                &nbsp;&nbsp;Dados adicionais da identidade:
                              </td>
                              <td width="10%" align="right" style="color:#FFFFFF;font-weight:bold;">
                                <img src="imagens/jan_fechar_off.jpg" align="center" 
                                     onclick="document.getElementById('identadic').style.visibility='hidden'">
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=@$Led47_i_censoorgemissrg?>
                        </td>
                        <td>
                          <?
                            db_input('ed47_i_censoorgemissrg', 20, @$Ied47_i_censoorgemissrg, true, 
                                     'text', $db_opcao);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=@$Led47_i_censoufident?>
                        </td>
                        <td>
                          <?
                            db_input('ed47_i_censoufident', 2, @$Ied47_i_censoufident, true, 
                                     'text', $db_opcao);
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=@$Led47_v_identcompl?>
                        </td>
                        <td>
                          <?db_input('ed47_v_identcompl', 20, @$Ied47_v_identcompl, true, 'text', $db_opcao);?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?=@$Led47_d_identdtexp?>
                        </td>
                        <td>
                          <?
                            db_inputdata('ed47_d_identdtexp', @$ed47_d_identdtexp_dia, @$ed47_d_identdtexp_mes,
                                         @$ed47_d_identdtexp_ano, true, 'text', $db_opcao
                                        );
                          ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
            <td align="left">
              <b>Libera Endereço:</b>
              <?
                $aOptionsSelect = array("N" => "NÃO", "S" => "SIM");
                db_select('liberaendereco', $aOptionsSelect, true, $db_opcao, 
                          " onchange='LiberaEndereco(this.value);'"
                         );
              ?>
            </td>
          </tr>
          <tr align="left" valign="top">
            <td>
              <table width="100%%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="27%" title='<?=$Ted47_i_codigo?>' nowrap>
                    <?=$Led47_i_codigo?>
                  </td>
                  <td width="73%" nowrap>
                    <?db_input('ed47_i_codigo', 20, $Ied47_i_codigo, true, 'text', 3);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_v_nome?>>
                    <?=@$Led47_v_nome?>
                  </td>
                  <td nowrap title="<?=@$Ted47_v_nome?>">
                    <?db_input('ed47_v_nome', 40, $Ied47_v_nome, true, 'text', $db_opcao, "");?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_v_pai?>>
                    <?=@$Led47_v_pai?>
                  </td>
                  <td nowrap title="<?=@$Ted47_v_pai?>">
                    <?db_input('ed47_v_pai', 40, $Ied47_v_pai, true, 'text', $db_opcao, "");?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_v_mae?>>
                    <?=@$Led47_v_mae?>
                  </td>
                  <td nowrap title="<?=@$Ted47_v_mae?>">
                    <?db_input('ed47_v_mae', 40, $Ied47_v_mae, true, 'text', $db_opcao, "");?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Ted47_d_nasc?>">
                    <?=$Led47_d_nasc?>
                  </td>
                  <td nowrap title="<?=$Ted47_d_nasc?>">
                    <?db_inputdata('ed47_d_nasc', @$ed47_d_nasc_dia, @$ed47_d_nasc_mes, 
                                   @$ed47_d_nasc_ano, true, 'text', $db_opcao
                                  );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Ted47_i_estciv?>">
                    <?=$Led47_i_estciv?>
                  </td>
                  <td nowrap title="<?=$Ted47_i_estciv?>">
                    <?
                      $aSelect = array("1" => "Solteiro", 
                                       "2" => "Casado", 
                                       "3" => "Viúvo", 
                                       "4" => "Divorciado"
                                      );
                      db_select('ed47_i_estciv', $aSelect, true, $db_opcao);
                    ?>
                    <?=$Led47_v_sexo?>
                    <?
                      $aSexos = array("M" => "Masculino", "F" => "Feminino");
                      db_select('ed47_v_sexo', $aSexos, true, $db_opcao);
                    ?>
                  </td>
                </tr>
              </table>
            </td>
            
            <?/*Muda lado da tela*/ ?>
      
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="27%" title="<?=$Ted47_v_profis?>" nowrap>
                    <?=$Led47_v_profis?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_profis', 40, $Ied47_v_profis, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Ted47_i_nacion?>">
                    <?=$Led47_i_nacion?>
                  </td>
                  <td nowrap title="<?=$Ted47_i_nacion?>">
                    <?
                      $aNacao = array("1" => "Brasileiro",
                                      "3" => "Brasileiro Nascido no Exterior ou Naturalizado",
                                      "2" => "Estrangeiro"
                                     );
                      db_select('ed47_i_nacion', $aNacao, true, $db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Ted47_i_pais?>">
                    <?=$Led47_i_pais?>
                  </td>
                  <td nowrap title="<?=$Ted47_i_pais?>">
                    <?
                      if (!isset($ed47_i_pais)) {
                        $ed47_i_pais = 10;
                      }
                      
                      $sSqlPais    = $oDaoPais->sql_query_file("", "ed228_i_codigo,ed228_c_descr", "ed228_c_descr", "");
                      $result_pais = $oDaoPais->sql_record($sSqlPais);
                      
                      if ($oDaoPais->numrows == 0) {
                        
                        $aSelect = array('' => 'NENHUM REGISTRO');
                        db_select('ed47_i_pais', $aSelect, true, $db_opcao, "");
                      
                      } else {
                        db_selectrecord("ed47_i_pais", $result_pais, "", $db_opcao, "", "", "", "  ", "", "");
                      }
                    ?>
                  </td>
                <tr>
                  <td nowrap title=<?=@$Ted47_v_cnh?>>
                    <?=@$Led47_v_cnh?>
                  </td>
                  <td nowrap title="<?=@$Ted47_v_cnh?>">
                    <?db_input('ed47_v_cnh', 15, $Ied47_v_cnh, true, 'text', $db_opcao, "");?>
                    <?=@$Led47_v_categoria?>
                    <?
                      $y = array("" => "", "A" => "A", "B" => "B", "C" => "C", "D" => "D", "E" => "E");
                      db_select('ed47_v_categoria', $y, true, $db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_d_dtemissao?>>
                    <?=@$Led47_d_dtemissao?>
                  </td>
                  <td nowrap title="<?=@$Ted47_d_dtemissao?>">
                    <?
                      db_inputdata('ed47_d_dtemissao', @$ed47_d_dtemissao_dia, @$ed47_d_dtemissao_mes,
                                   @$ed47_d_dtemissao_ano, true, 'text', $db_opcao
                                  );
                    ?>
                    <?=@$Led47_d_dthabilitacao?>
                    <?
                      db_inputdata('ed47_d_dthabilitacao', @$ed47_d_dthabilitacao_dia, @$ed47_d_dthabilitacao_mes,
                                   @$ed47_d_dthabilitacao_ano, true, 'text', $db_opcao
                                  );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_d_dthabilitacao?>>
                  
                  </td>
                  <td nowrap title="<?=@$Ted47_d_dthabilitacao?>">
                  
                  </td>
                </tr>
                <tr>
                  <td nowrap title=<?=@$Ted47_d_dtvencimento?>>
                    <?=@$Led47_d_dtvencimento?>
                  </td>
                  <td nowrap title="<?=@$Ted47_d_dtvencimento?>">
                    <?
                      db_inputdata('ed47_d_dtvencimento', @$ed47_d_dtvencimento_dia, @$ed47_d_dtvencimento_mes,
                                   @$ed47_d_dtvencimento_ano, true, 'text', $db_opcao
                                  );
                    ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td width="50%" align="center" title="<?=$TDBtxt1?>" valign="middle">
              <?=@$LDBtxt1?>
            </td>
            <td width="50%" align="center" valign="middle" title="<?=$TDBtxt5?>">
              <?=@$LDBtxt5?>
            </td>
          </tr>
          <tr align="center" valign="middle">
            <td width="50%">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap title="<?=@$Ted47_v_ender?>">
                    <?db_ancora(@$Led47_v_ender, "js_ruas();", $db_opcao);?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_ender', 40, $Ied47_v_ender, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td width="29%" nowrap title="<?=@$Ted47_c_numero?>">
                    <?=@$Led47_c_numero?>
                  </td>
                  <td width="71%" nowrap>
                    <a name="AN3">
                      <?db_input('ed47_c_numero', 8, $Ied47_c_numero, true, 'text', $db_opcao);?>
                      &nbsp;
                      <?=@$Led47_v_compl?>
                      <?db_input('ed47_v_compl', 10, $Ied47_v_compl, true, 'text', $db_opcao);?>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_i_censomunicend?>">
                    <?=@$Led47_i_censomunicend?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_i_censomunicend', 20, $Ied47_i_censomunicend, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_i_censoufend?>">
                    <?=@$Led47_i_censoufend?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_i_censoufend', 2, $Ied47_i_censoufend, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_bairro?>">
                    <?db_ancora(@$Led47_v_bairro, "js_bairro();", $db_opcao);?>
                  </td>
                  <td nowrap>
                    <?db_input('j13_codi', 10, $Ij13_codi, true, 'text', 3);?>
                    <?db_input('ed47_v_bairro', 25, $Ied47_v_bairro, true, 'text', 3);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_cep?>">
                    <?=@$Led47_v_cep?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_cep', 9, $Ied47_v_cep, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_telef?>">
                    <?=@$Led47_v_telef?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_telef', 12, $Ied47_v_telef, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_telcel?>">
                    <?=@$Led47_v_telcel?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_telcel', 12, $Ied47_v_telcel, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_email?>">
                    <?=@$Led47_v_email?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_email', 30, $Ied47_v_email, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_cxpostal?>">
                    <?=@$Led47_v_cxpostal?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_cxpostal', 10, $Ied47_v_cxpostal, true, 'text', $db_opcao);?>
                  </td>
                </tr>
              </table>
            </td>
            <td width="50%">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap title="<?=@$Ted47_v_endcon?>">
                    <?db_ancora(@$Led47_v_endcon, "js_ruas1();", $db_opcao);?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_endcon', 40, $Ied47_v_endcon, true, 'text', 3);?>
                  </td>
                </tr>
                <tr>
                  <td width="29%" nowrap title="<?=@$Ted47_i_numcon?>">
                    <?=@$Led47_i_numcon?>
                  </td>
                  <td width="71%" nowrap >
                    <?db_input('ed47_i_numcon', 8, $Ied47_i_numcon, true, 'text', $db_opcao);?>
                    <?=@$Led47_v_comcon?>
                    <?db_input('ed47_v_comcon', 10, $Ied47_v_comcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_muncon?>">
                    <?=@$Led47_v_muncon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_muncon', 20, $Ied47_v_muncon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=$Ted47_v_ufcon?>">
                    <?=@$Led47_v_ufcon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_ufcon', 2, $Ied47_v_ufcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_baicon?>">
                    <?db_ancora(@$Led47_v_baicon, "js_bairro1();", $db_opcao);?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_baicon', 25, $Ied47_v_baicon, true, 'text', 3);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_cepcon?>">
                    <?=@$Led47_v_cepcon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_cepcon', 9, $Ied47_v_cepcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_telcon?>">
                    <?=@$Led47_v_telcon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_telcon', 12, $Ied47_v_telcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_celcon?>">
                    <?=@$Led47_v_celcon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_celcon', 12, $Ied47_v_celcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_emailc?>">
                    <?=@$Led47_v_emailc?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_emailc', 30, $Ied47_v_emailc, true, 'text', $db_opcao);?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$Ted47_v_cxposcon?>">
                    <?=@$Led47_v_cxposcon?>
                  </td>
                  <td nowrap>
                    <?db_input('ed47_v_cxposcon', 10, $Ied47_v_cxposcon, true, 'text', $db_opcao);?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr align="left" valign="middle">
            <td>
              <?=@$Led47_d_cadast?>
              <?
                db_inputdata('ed47_d_cadast', @$ed47_d_cadast_dia, @$ed47_d_cadast_mes,
                             @$ed47_d_cadast_ano, true, 'text', 3
                            );
              ?>
            </td>
            <td>
              <?=@$Led47_d_ultalt?>
              <?
                db_inputdata('ed47_d_ultalt', @$ed47_d_ultalt_dia, @$ed47_d_ultalt_mes,
                             @$ed47_d_ultalt_ano, true, 'text', 3
                            );
              ?>
            </td>
          </tr>
          <tr align="center" valign="middle">
            <td height="30" colspan="2" nowrap>

              <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
                     type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 
                                                            || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
                     <?=($db_botao == false ? "disabled" : "")?> >
              
              <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" 
                     onclick="js_pesquisa();">
              
              <input name="novo" type="button" id="novo" value="Novo Registro" 
                     onclick="js_novo()" <?=$db_opcao == 1 ? "disabled" : ""?>>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<script>

function js_ruas(){
  
  js_OpenJanelaIframe('','db_iframe_ruas',
                      'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome',
                      'Pesquisa',true);

}

function js_preenchepesquisaruas(chave, chave1) {
  
  document.form1.ed47_v_ender.value = chave1;
  db_iframe_ruas.hide();

}

function js_bairro() {
  
  js_OpenJanelaIframe('','db_iframe_bairro',
                      'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr',
                      'Pesquisa',true);

}

function js_preenchebairro(chave, chave1) {
  
  document.form1.j13_codi.value      = chave;
  document.form1.ed47_v_bairro.value = chave1;
  db_iframe_bairro.hide();

}

function js_ruas1() {
  
  js_OpenJanelaIframe('','db_iframe_ruas1',
                      'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome',
                      'Pesquisa',true);

}

function js_preenchepesquisaruas1(chave, chave1) {
   
   document.form1.ed47_v_endcon.value = chave1;
   db_iframe_ruas1.hide();

}

function js_bairro1() {
  
  js_OpenJanelaIframe('','db_iframe_bairro1',
                      'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr',
                      'Pesquisa',true);

}

function js_preenchebairro1(chave, chave1) {
  
  document.form1.ed47_v_baicon.value = chave1;
  db_iframe_bairro1.hide();

}

function js_pesquisa() {
  
  js_OpenJanelaIframe('','db_iframe_alunofora',
                      'func_alunofora.php?funcao_js=parent.js_preenchepesquisa|ed47_i_codigo',
                      'Pesquisa Alunos',true);

}
 
function LiberaEndereco(valor) {

  if(valor == "S") {
    
    document.form1.ed47_v_ender.readOnly         = false;
    document.form1.ed47_v_ender.style.background = "#FFFFFF";
    document.links[0].style.color                = "#000000";
    document.links[0].style.textDecoration       = "none";
    document.links[0].href                       = "";
  
  } else if (valor == "N") {

    document.form1.ed47_v_ender.readOnly         = true;
    document.form1.ed47_v_ender.style.background = "#DEB887";
    document.links[0].style.color                = "blue";
    document.links[0].style.textDecoration       = "underline";
    document.links[0].href                       = "#";
  
  }

}

function js_preenchepesquisa(chave) {

  db_iframe_alunofora.hide();
  <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>

}

function js_novo() {
  
  parent.location = "edu1_alunoforaabas001.php";

}

LiberaEndereco("N");

</script>