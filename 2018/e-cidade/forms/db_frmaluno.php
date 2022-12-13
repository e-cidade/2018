<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
$oClRotulo = new rotulocampo;
$iEscola   = db_getsession("DB_coddepto");

if ($db_opcao != 1 && $chavepesquisa != "") {
	
  $sql     = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $chavepesquisa";
  $query   = db_query($sql);
  $linhas4 = pg_num_rows($query);
  
  if ($linhas4 == 0) {
    $db_botao = true;
  } elseif ($iEscola != pg_result($query,0,0)) {
    $db_botao = false;
  } else {
    $db_botao = true;
  }
}

if ($ed47_i_nacion == 3) {
  $db_opcao1 = 3;
} else {
  $db_opcao1 = 1;
}

?>
<form id="frmDocumentacaoAluno" name="form1" method="post" action="">
<center>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr valign="top">
      <td align="center">
        <?db_ancora(@$Led47_i_codigo, "", 3);?>
        <?db_input('ed47_i_codigo', 20, $Ied47_i_codigo, true, 'text', 3, "")?>
        <?db_input('ed47_v_nome', 40, $Ied47_v_nome, true, 'text', 3, '')?>
        <?=@$Led47_c_codigoinep?>
        <?db_input('ed47_c_codigoinep', 12, $Ied47_c_codigoinep, true, 'text', 3, '')?>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset><legend><b>Certidão</b></legend>
          <table border="0" id="tblMatricula" name="tblMatricula" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td>
                <?=@$Led47_certidaomatricula?>
              </td>
              <td>
                <input type="text" id="matri_cartorio" name="matri_cartorio" tabindex="1" 
                       onchange="js_buscaCartorioMatricula();" onkeyup="js_mudafoco(this, 6, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="6" maxlength="6" title="Cartório" />
                <input type="text" id="matri_tipoacervo" name="matri_tipoacervo" tabindex="2"
                       onchange="js_checaTpAcervoMatricula();" onkeyup="js_mudafoco(this, 2, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="2" maxlength="2" title="Tipo do Acervo" />
                <input type="text" id="matri_numservico" name="matri_numservico" tabindex="3"
                       onchange="js_checaNumServicoMatricula();" onkeyup="js_mudafoco(this, 2, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="2" maxlength="2" title="Número Serviço" />
                <input type="text" id="matri_anoregistro" name="matri_anoregistro" tabindex="4"
                       onchange="js_checaAnoRegistroMatricula();" onkeyup="js_mudafoco(this, 4, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="4" maxlength="4" title="Ano do Registro" />
                <input type="text" id="matri_tipolivro" name="matri_tipolivro" tabindex="5"
                       onchange="js_checaTpLivroMatricula();" onkeyup="js_mudafoco(this, 1, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="1" maxlength="1" title="Tipo do Livro" />
                <input type="text" id="matri_numlivro" name="matri_numlivro" tabindex="6"
                       onchange="js_checaNumLivroMatricula();" onkeyup="js_mudafoco(this, 5, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="5" maxlength="5" title="Número do Livro" />
                <input type="text" id="matri_numfolha" name="matri_numfolha" tabindex="7"
                       onchange="js_checaFolhaMatricula();" onkeyup="js_mudafoco(this, 3, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="3" maxlength="3" title="Número da Folha" />
                <input type="text" id="matri_termo" name="matri_termo" tabindex="8"
                       onchange="js_checaTermoMatricula();" onkeyup="js_mudafoco(this, 7, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="7" maxlength="7" title="Termo" />
                <input type="text" id="matri_codverificador" name="matri_codverificador" tabindex="9" 
                       onchange="js_checaCodVerifMatricula();" onkeyup="js_mudafoco(this, 2, event);"
                       onkeypress="return js_checaSomenteNumero(event);"
                       size="2" maxlength="2" title="Código Verificador" />

                <input type="hidden" name="ed47_certidaomatricula" id="ed47_certidaomatricula" value="" />

                <input type="button" onclick="js_habilitaMatriculaManual();" value="Limpar Matrícula" 
                       id="btnMatricula" name="btnMatricula" title="Limpar Matrícula" tabindex="10" />

              </td>
            </tr>
            <tr>
              <td width="15%">
                <?=@$Led47_c_certidaotipo?>
              </td>
              <td>
                <?php
                  $x = array('' => '', 'N' => 'NASCIMENTO', 'C' => 'CASAMENTO');
                  db_select('ed47_c_certidaotipo', $x, true, $db_opcao1, "");

                  echo @$Led47_c_certidaonum;
                  db_input('ed47_c_certidaonum', 8, $Ied47_c_certidaonum, true, 'text', $db_opcao1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Led47_c_certidaofolha?>
              </td>
              <td>
                <?php
                  db_input('ed47_c_certidaofolha', 4, $Ied47_c_certidaofolha, true, 'text', $db_opcao1, "");
                  echo @$Led47_c_certidaolivro;

                  db_input('ed47_c_certidaolivro', 8, $Ied47_c_certidaolivro, true, 'text', $db_opcao1, "");
                  echo @$Led47_c_certidaodata;
                  db_inputdata('ed47_c_certidaodata', @$ed47_c_certidaodata_dia, @$ed47_c_certidaodata_mes,
                                @$ed47_c_certidaodata_ano, true, 'text', $db_opcao1, "");
                ?>
              </td>
            </tr>           
            <tr>
              <td>
                <?=@$Led47_i_censoufcert?>  
              </td>
              <td>        
                <?
                  $sSqlUf    = $oDaoCensoUf->sql_query_file("", "ed260_i_codigo,ed260_c_nome", "ed260_c_nome");
                  $result_uf = $oDaoCensoUf->sql_record($sSqlUf); 
                ?>  
                <select name="ed47_i_censoufcert" id="uf" onChange="js_uf(this.value);"
                        style="height:18px;font-size:10px;">
                  <option value=""></option>
                  <? 
                    for ($t = 0; $t < $oDaoCensoUf->numrows; $t++) {
        
                      db_fieldsmemory($result_uf,$t);
                
                  ?>
                  <option value="<?=$ed260_i_codigo?>"><?=$ed260_c_nome?></option>
      
                <? } ?>
                </select>

                <?=@$Led47_i_censomuniccert?>
                <select name="ed47_i_censomuniccert" id="select_municipio" onchange="js_cartorio(this.value)" 
                        style="width:150px;height:18px;font-size:10px;;" >
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Led47_i_censocartorio?>
              </td>
              <td>
                <select name="ed47_i_censocartorio" id="select_cartorio"
                        style="width:500px;height:18px;font-size:10px;;" >
                </select>
              </td>
            </tr>       
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset><legend><b>Identidade</b></legend>
          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td width="15%">
                <?=@$Led47_v_ident?>
              </td>
              <td>
                <?php
                  db_input('ed47_v_ident', 15, $Ied47_v_ident, true, 'text', $db_opcao1);
                  echo @$Led47_v_identcompl;

                  db_input('ed47_v_identcompl', 4, @$Ied47_v_identcompl, true, 'text', $db_opcao1);
                  echo @$Led47_i_censoufident;

                  $sQueryUf  = $oDaoCensoUf->sql_query_file("", "ed260_i_codigo,ed260_c_nome", "ed260_c_nome");
                  $result_uf = $oDaoCensoUf->sql_record($sQueryUf);
                  db_selectrecord("ed47_i_censoufident", $result_uf, "", $db_opcao1, "", "", "", "  ", "", 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Led47_i_censoorgemissrg?>
              </td>
              <td>
                <?
                  $sSqlCensoOrgEmissRg = $oDaoCensoOrgEmissRg->sql_query_file(
                                                                               "",
                                                                               "ed132_i_codigo, ed132_c_descr",
                                                                               "ed132_c_descr"
                                                                             );
                  $result_org = $oDaoCensoOrgEmissRg->sql_record($sSqlCensoOrgEmissRg);
                  db_selectrecord("ed47_i_censoorgemissrg", $result_org, "", $db_opcao1, "", "", "", "  ", "", 1);

                  echo @$Led47_d_identdtexp;
                  db_inputdata('ed47_d_identdtexp', @$ed47_d_identdtexp_dia, @$ed47_d_identdtexp_mes,
                                @$ed47_d_identdtexp_ano, true, 'text', $db_opcao1
                               );
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset><legend><b>CNH</b></legend>
          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td width="15%">
                <?=@$Led47_v_cnh?>
              </td>
              <td>
                <?php
                  db_input('ed47_v_cnh', 15, $Ied47_v_cnh, true, 'text', $db_opcao1, "");
                  echo @$Led47_v_categoria;

                  $y = array("" => "", "A" => "A", "B" => "B", "C" => "C", "D" => "D", "E" => "E");
                  db_select('ed47_v_categoria', $y, true, $db_opcao1);

                  echo @$Led47_d_dtemissao;
                  db_inputdata('ed47_d_dtemissao', @$ed47_d_dtemissao_dia, @$ed47_d_dtemissao_mes,
                                @$ed47_d_dtemissao_ano, true, 'text', $db_opcao1
                               );
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?=@$Led47_d_dthabilitacao?>
              </td>
              <td>
                <?php
                  db_inputdata('ed47_d_dthabilitacao', @$ed47_d_dthabilitacao_dia, @$ed47_d_dthabilitacao_mes,
                                @$ed47_d_dthabilitacao_ano, true, 'text', $db_opcao1
                               );
                  echo @$Led47_d_dtvencimento;

                  db_inputdata('ed47_d_dtvencimento', @$ed47_d_dtvencimento_dia, @$ed47_d_dtvencimento_mes,
                                @$ed47_d_dtvencimento_ano, true, 'text', $db_opcao1
                               ); 
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset><legend><b>Outros</b></legend>
          <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td width="15%">
                <?=@$Led47_v_cpf?>
              </td>
              <td>
                <?php
                  db_input('ed47_v_cpf', 11, @$Ied47_v_cpf, true, 'text', $db_opcao1,
                            "onChange='js_verificacpf(this);'"
                           );
                  $desabpassaporte = $ed47_i_nacion != 3 ? "readOnly style='background:#DEB887'" : "";
                  echo @$Led47_c_passaporte;
                  db_input('ed47_c_passaporte', 20, $Ied47_c_passaporte, true, 'text',
                            $db_opcao, " $desabpassaporte "
                           );
                echo $Led47_cartaosus;
                db_input( 'ed47_cartaosus', 20, $Ied47_cartaosus, true, 'text', $db_opcao );
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td align="center">
        <table align="center">
          <tr>
            <td nowrap title="<?=@$Ted47_t_obs?>">
              <?=@$Led47_t_obs?><br>
              <? db_textarea('ed47_t_obs', 4, 60, $Ied47_t_obs, true, 'text', $db_opcao, "") ?>
            </td>
            <td width="10%"></td>
            <td>
              <?=@$Led47_v_contato?><br>
              <? db_textarea('ed47_v_contato', 4, 60, $Ied47_v_contato, true, 'text', $db_opcao, "") ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</center>
<input id="alterar" name="alterar" type="submit" value="Alterar" <?=($db_botao == false ? "disabled" : "")?> 
       onclick="return js_valida();">
</form>
<script>

js_init();
js_checaMatricula();

var sUrlRpcEscola = "edu4_escola.RPC.php";

function js_buscaCartorioMatricula() {

  if( $F('matri_cartorio') == '' ) {
    return;
  }

  if ($('matri_cartorio').value != "") {

    var oParam = new Object();

    $('matri_cartorio').value                 = strPad($('matri_cartorio').value, 6, "0", "L");
    $('matri_cartorio').style.backgroundColor = '#FFFFFF';

    oParam.exec               = "getCartorioMatricula";
    oParam.iCartorio          = $('matri_cartorio').value;

    js_webajax(oParam, 'js_retornoBuscaCartorio', sUrlRpcEscola);
  } else {

    alert('Número de cartório não é válido, verifique!');
    $('matri_cartorio').style.backgroundColor = '#99A9AE';
  }
}

function js_retornoBuscaCartorio(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    $('matri_cartorio').value = "";
    $('matri_cartorio').focus();
  } else {

    $('uf').innerHTML               = "";
    sHtml                           = "<option value='"+oRetorno.ed260_i_codigo+"'>"+
                                       oRetorno.ed260_c_nome.urlDecode()+"</option>";
    $('uf').innerHTML               = sHtml;

    $('select_municipio').innerHTML = "";
    sHtml                           = "<option value='"+oRetorno.ed261_i_codigo+"'>"+
                                      oRetorno.ed261_c_nome.urlDecode()+"</option>";
    $('select_municipio').innerHTML = sHtml;

    $('select_cartorio').innerHTML  = "";
    sHtml                           = "<option value='"+oRetorno.ed291_i_codigo+"'>"+
                                       oRetorno.ed291_c_nome.urlDecode()+"</option>";
    $('select_cartorio').innerHTML  = sHtml;
  }
}

function js_checaTpAcervoMatricula() {

  if (   $('matri_tipoacervo').value == ""
      || $('matri_tipoacervo').value.length < 2) {

    alert('Tipo de acervo não é válido, verifique!');
    $('matri_tipoacervo').style.backgroundColor = '#99A9AE';
    $('matri_tipoacervo').value                 = "";
    $('matri_tipoacervo').focus();
  } else {
    $('matri_tipoacervo').style.backgroundColor = '#FFFFFF';
  }
}

function js_checaNumServicoMatricula() {

  if (   $('matri_numservico').value != ""
      || $('matri_numservico').value.length == 2) {

    if ($('matri_numservico').value != "55") {

      alert('Número de serviço não é válido, verifique!');
      $('matri_numservico').style.backgroundColor = '#99A9AE';
      $('matri_numservico').value                 = "";
      $('matri_numservico').focus();
    } else {
      $('matri_numservico').style.backgroundColor = '#FFFFFF';
    }
  } else {

    alert('Número inválido!');
    $('matri_numservico').focus();
  }
}

function js_checaAnoRegistroMatricula() {

  var iAno       = $('matri_anoregistro').value;
  var oData      = new Date();
  var iAnoAtual  = oData.getFullYear();

  if (iAno == "" || iAno.length < 4) {

    alert('Ano de registro não é válido, verifique!');
    $('matri_anoregistro').style.backgroundColor = '#99A9AE';
    $('matri_anoregistro').value                 = "";
    $('matri_anoregistro').focus();
  } else {
    $('matri_anoregistro').style.backgroundColor = '#FFFFFF';
  }

  if ( 1900 > iAno || iAno > iAnoAtual) {

    alert('Número inválido!!');
    $('matri_anoregistro').style.backgroundColor = '#99A9AE';
    $('matri_anoregistro').value                 = "";
    $('matri_anoregistro').focus();
  } else {
    $('matri_anoregistro').style.backgroundColor = '#FFFFFF';
  }
}

function js_checaTpLivroMatricula() {

  var iTipoLivro = $('matri_tipolivro').value;

  if (iTipoLivro == 1) {

    $('ed47_c_certidaotipo').innerHTML         = "";
    $('ed47_c_certidaotipo').innerHTML         = "<option value='N'>NASCIMENTO</option>";
    $('matri_tipolivro').style.backgroundColor = '#FFFFFF';
  } else if (iTipoLivro == 2) {

    $('ed47_c_certidaotipo').innerHTML         = "";
    $('ed47_c_certidaotipo').innerHTML         = "<option value='C'>CASAMENTO</option>";
    $('matri_tipolivro').style.backgroundColor = '#FFFFFF';
  } else {

    alert('Tipo do livro não é válido, verifique!');
    $('matri_tipolivro').style.backgroundColor = '#99A9AE';
    $('matri_tipolivro').value                 = "";
    $('matri_tipolivro').focus();
  }
}

function js_checaNumLivroMatricula() {

  $('matri_numlivro').value = strPad($('matri_numlivro').value, 5, "0", "L");
  var numLivro              = $('matri_numlivro').value;

  if (   numLivro == ""
      || numLivro.length < 5) {
    
    alert('Número do livro não é válido, verifique!');
    $('matri_numlivro').style.backgroundColor = '#99A9AE';
    $('matri_numlivro').value                 = "";
    $('matri_numlivro').focus();
  } else {

    $('ed47_c_certidaolivro').value           = numLivro;
    $('ed47_c_certidaolivro').setAttribute('readOnly','readonly');
    $('matri_numlivro').style.backgroundColor = '#FFFFFF';
  }
}

function js_checaFolhaMatricula() {

  $('matri_numfolha').value = strPad($('matri_numfolha').value, 3, "0", "L");
  var iNumFolha             = $('matri_numfolha').value;

  if (   iNumFolha == ""
      || iNumFolha.length < 3) {
    
    alert('Número da folha não é válido, verifique!');
    $('matri_numfolha').style.backgroundColor = '#99A9AE';
    $('matri_numfolha').value                 = "";
    $('matri_numfolha').focus();
  } else {

    $('ed47_c_certidaofolha').setAttribute('readOnly','readonly');
    $('ed47_c_certidaofolha').value           = iNumFolha;
    $('matri_numfolha').style.backgroundColor = '#FFFFFF';
  }
}

function js_checaTermoMatricula() {

  $('matri_termo').value = strPad($('matri_termo').value, 7, "0", "L");
  var iTermo             = $('matri_termo').value;

  if (   iTermo == ""
      || iTermo.length < 7) {

    alert('Número do termo não é válido, verifique!');
    $('matri_termo').style.backgroundColor = '#99A9AE';
    $('matri_termo').value                 = "";
    $('matri_termo').focus();
  } else {

    $('ed47_c_certidaonum').setAttribute('readOnly','readonly');
    $('ed47_c_certidaonum').value          = iTermo;
    $('matri_termo').style.backgroundColor = '#FFFFFF';
  }
}

function js_checaCodVerifMatricula() {

  var iCodVerif = $('matri_codverificador').value;

  if (   iCodVerif == ""
      || iCodVerif.length < 2) {

    alert('Código verificador não é válido, verifique!');
    $('matri_codverificador').style.backgroundColor = '#99A9AE';
    $('matri_codverificador').value                 = "";
    $('matri_codverificador').focus();
  } else {
    $('matri_codverificador').style.backgroundColor = '#FFFFFF';
  }
}

function js_valEnvMatricula() {

  var iCartorio    = $('matri_cartorio').value;
  var iTpAcervo    = $('matri_tipoacervo').value;
  var iNumServico  = $('matri_numservico').value;
  var iAnoRegistro = $('matri_anoregistro').value;
  var sTipoLivro   = $('matri_tipolivro').value;
  var iNumLivro    = $('matri_numlivro').value;
  var iNumFolha    = $('matri_numfolha').value;
  var iTermo       = $('matri_termo').value;
  var iCodVerif    = $('matri_codverificador').value;

  if (iCartorio.trim() == "") {

    if ((iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim() || sTipoLivro.trim()
         || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iTpAcervo.trim() == "") {

    if ((iCartorio.trim() || iNumServico.trim() || iAnoRegistro.trim() || sTipoLivro.trim()
         || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iNumServico.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iAnoRegistro.trim() || sTipoLivro.trim()
         || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iAnoRegistro.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || sTipoLivro.trim()
         || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (sTipoLivro.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim()
         || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iNumLivro.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim()
         || sTipoLivro.trim() || iNumFolha.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iNumFolha.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim()
         || sTipoLivro.trim() || iNumLivro.trim() || iTermo.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iTermo.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim()
         || sTipoLivro.trim() || iNumLivro.trim() || iNumFolha.trim() || iCodVerif.trim()) != "") {
      return false;
    }
  }

  if (iCodVerif.trim() == "") {

    if ((iCartorio.trim() || iTpAcervo.trim() || iNumServico.trim() || iAnoRegistro.trim()
         || sTipoLivro.trim() || iNumLivro.trim() || iNumFolha.trim() || iTermo.trim()) != "") {
      return false;
    }
  }

  return true;
}

function js_valida() {

  nacion           = <?=$ed47_i_nacion?>;
  datanasc         = "<?=$ed47_d_nasc?>";

  var iCartorio    = $('matri_cartorio').value;
  var iTpAcervo    = $('matri_tipoacervo').value;
  var iNumServico  = $('matri_numservico').value;
  var iAnoRegistro = $('matri_anoregistro').value;
  var sTipoLivro   = $('matri_tipolivro').value;
  var iNumLivro    = $('matri_numlivro').value;
  var iNumFolha    = $('matri_numfolha').value;
  var iTermo       = $('matri_termo').value;
  var iCodVerif    = $('matri_codverificador').value;

  if (js_valEnvMatricula() == false) {

    alert("Campo matrícula inválido, verifique o número da matrícula antes de processeguir!");
    return false;
  } else if (js_valEnvMatricula()) {
  
    $('ed47_certidaomatricula').value = iCartorio + iTpAcervo + iNumServico + iAnoRegistro + sTipoLivro + 
                                        iNumLivro + iNumFolha + iTermo + iCodVerif;
  } else if (nacion != 3) {
	  
    identnum  = document.form1.ed47_v_ident.value;
    identcomp = document.form1.ed47_v_identcompl.value;
    identorg  = document.form1.ed47_i_censoorgemissrg.value;
    identuf   = document.form1.ed47_i_censoufident.value;
    identdata = document.form1.ed47_d_identdtexp.value;
    
    if (   nacion == 3
        && (identnum != "" 
            || identcomp != "" 
            || identorg != " " 
            || identuf != " " 
            || identdata != "")) {

      sMsg  = " Aluno com nacionalidade Estrangeira (Aba Dados Pessoais).\nCampos referente";
      sMsg += " a Identidade NÃO devem ser informados!";       
      alert(sMsg);
      return false;
    }
    
    if (   identnum == ""
        && (identcomp != "" 
            || identorg != " " 
            || identuf != " " 
            || identdata != "")) {

      sMsgIdent  = " Campo N° Identidade deve ser informado quando\num dos campos abaixo estiverem ";
      sMsgIdent += " informados:\n\nComplemento\nUF Identidade\nÓrgao Emissor\nData Expedição Identidade";  
      alert(sMsgIdent);
      return false;
    }
    
    if (   identorg == " "
        && (identnum != "" 
            || identuf != " ")) {

      sMsgOrg  = " Campo Órgão Emissor deve ser informado quando\num dos campos abaixo";
      sMsgOrg += " estiverem informados:\n\nN° Identidade\nUF Identidade"; 
      alert(sMsgOrg);
      return false;
    }
    
    if (   identuf == " "
        && (identnum != "" 
            || identorg != " ")) {

      sMsgUf  = " Campo UF Identidade deve ser informado quando\num dos campos abaixo";
      sMsgUf += " estiverem informados:\n\nN° Identidade\nÓrgão Emissor"; 
      alert(sMsgUf);
      return false;
    }
    
    if (   identcomp != ""
        && identnum == "" 
        && identorg == " " 
        && identuf == " ") {

      sMsgComp  = " Campo Complemento só pode ser informado quando\num dos campos abaixo estiverem";
      sMsgComp += " informados:\n\nN° Identidade\nÓrgão Emissor\nUF Identidade"; 
      alert(sMsgComp);
      return false;
    }
    
    if (   identdata != ""
        && identnum == "" 
        && identorg == " " 
        && identuf == " ") {

      sMsgData  = " Campo Data Expedição Identidade só pode ser informado quando\num dos campos abaixo estiverem";
      sMsgData += " informados:\n\nN° Identidade\nÓrgão Emissor\nUF Identidade"; 
      alert(sMsgData);
      return false;
    }
    
    if (identdata != "") {
        
      diaident = identdata.substr(0,2);
      mesident = identdata.substr(3,2);
      anoident = identdata.substr(6,4);
      dianasc  = datanasc.substr(8,2);
      mesnasc  = datanasc.substr(5,2);
      anonasc  = datanasc.substr(0,4);
      data_hj  = <?=date("Y").date("m").date("d")?>;
      
      if (anoident < 1900) {
          
        alert("Ano da Data de Expedição deve ser maior que 1899!");
        return false;
      }
      
      data_ident = anoident+""+mesident+""+diaident;
      data_nasc  = anonasc+""+mesnasc+""+dianasc;
      
      if (parseInt(data_ident) >= parseInt(data_hj)) {
          
        alert("Campo Data de Expedição deve ser menor que a data corrente!");
        return false;
      }
      
      if (parseInt(data_ident) <= parseInt(data_nasc)) {

        sMsgNasc  = " Campo Data de Expedição deve ser maior que a data de";
        sMsgNasc += " nascimento do aluno ("+dianasc+"/"+mesnasc+"/"+anonasc+")!";
        alert(sMsgNasc);
        return false;
      }
    }
    
    certtip       = document.form1.ed47_c_certidaotipo.value;
    certnum       = document.form1.ed47_c_certidaonum.value;
    certfol       = document.form1.ed47_c_certidaofolha.value;
    certliv       = document.form1.ed47_c_certidaolivro.value;
    certcar       = document.form1.ed47_i_censocartorio.value;
    certdat       = document.form1.ed47_c_certidaodata.value;
    certuf        = document.form1.ed47_i_censoufcert.value;
    certmun       = document.form1.ed47_i_censomuniccert.value;
    censocartorio = document.form1.ed47_i_censocartorio.value;

    if (   nacion == 3
        && (certtip != "" 
            || certnum != "" 
            || certfol != "" 
            || certliv != "" 
            || certcar != "" 
            || certdat != "" 
            || certuf != "" 
            || certmun != "")) {

      sMsgNacion  = " Aluno com nacionalidade Estrangeira (Aba Dados Pessoais).\nCampos ";
      sMsgNacion += " referente a Certidão NÃO devem ser informados!";
      alert(sMsgNacion);
      return false;
    }
    
    if (   certtip == ""
        && (certnum != "" 
            || certfol != "" 
            || certliv != "" 
            || certdat != "" 
            || certuf != "" 
            || certcar != "" 
            || certmun != "" )) {

      sMsgCert  = " Campo Tipo de Certidão deve ser informado quando\num dos campos abaixo estiverem";
      sMsgCert += " informados:\n\nNúmero do Termo\nFolha\nLivro\nData da Emissão\nUF Cartório\nCartório\nMunicípio"; 
      alert(sMsgCert);
      return false;
    }

    if (   censocartorio == ""
        && (certnum != "" 
            || certfol != "" 
            || certliv != "" 
            || certdat != "" 
            || certuf != "" 
            || certcar != "" 
            || certmun != "" )) {

      sMsgCartorio  = " Campo Cartório deve ser informado quando\num dos campos abaixo estiverem";
      sMsgCartorio += " informados:\n\nNúmero do Termo\nFolha\nLivro\nData da Emissão\nUF Cartório\nMunicípio"; 
      alert();
      return false;
    }
    
    if (   certnum == ""
        && (certtip != "" 
            || certuf != "" 
            || certcar != "" 
            || certmun != "" )) {

      sMsgNum  = " Campo Número do Termo deve ser informado quando\num dos campos abaixo estiverem ";
      sMsgNum += " informados:\n\nTipo de Certidão\nUF Cartório\nCartório\nMunicípio"; 
      alert(sMsgNum);
      return false;
    }
    
    if (   certcar == ""
        && (certtip != "" 
            || certuf != "" 
            || certnum != "" 
            || certmun != "" )) {

      sMsgCar  = " Campo Cartório deve ser informado quando\num dos campos abaixo";
      sMsgCar += " estiverem informados:\n\nTipo de Certidão\nUF Cartório\nNúmero do Termo\nMunicípio"; 
      alert(sMsgCar);
      return false;
    }
    
    if (   certuf == ""
        && (certtip != "" 
            || certcar != "" 
            || certnum != "" 
            || certmun != "" )) {        

      sMsgCertUf  = " Campo UF Cartório deve ser informado quando\num dos campos abaixo estiverem";
      sMsgCertUf += " informados:\n\nTipo de Certidão\nCartório\nNúmero do Termo\nMunicípio";   
      alert(sMsgCertUf);
      return false;
    }
    
    if (   certfol != ""
        && certtip == "" 
        && certnum == "" 
        && certuf == "" 
        && certcar == "") {

      sMsgFol  = " Campo Folha só pode ser informado quando\num dos campos abaixo";
      sMsgFol += " estiverem informados:\n\nTipo de Certidão\nNúmero do Termo\nUF Cartório\nCartório"; 
      alert(sMsgFol);
      return false;
    }
    
    if (   certliv != ""
        && certtip == "" 
        && certnum == "" 
        && certuf == "" 
        && certcar == "") {

      sMsgLiv  = " Campo Livro só pode ser informado quando\num dos campos abaixo estiverem";
      sMsgLiv += " informados:\n\nTipo de Certidão\nNúmero do Termo\nUF Cartório\nCartório";
      alert(sMsgLiv);
      return false;
    }
    
    if (   certdat != ""
        && certtip == "" 
        && certnum == "" 
        && certuf == "" 
        && certcar == "") {

      sMsgFim  = " Campo Data de Emissão só pode ser informado quando\num dos campos abaixo ";
      sMsgFim += " estiverem informados:\n\nTipo de Certidão\nNúmero do Termo\nUF Cartório\nCartório"; 
      alert(sMsgFim);
      return false;
    }
    
    if (certdat != "") {
        
      diacert   = certdat.substr(0,2);
      mescert   = certdat.substr(3,2);
      anocert   = certdat.substr(6,4);
      dianasc   = datanasc.substr(8,2);
      mesnasc   = datanasc.substr(5,2);
      anonasc   = datanasc.substr(0,4);
      data_hj   = <?=date("Y").date("m").date("d")?>;
      data_cert = anocert+""+mescert+""+diacert;
      data_nasc = anonasc+""+mesnasc+""+dianasc;
      
      if (parseInt(data_cert) >= parseInt(data_hj)) {
          
        alert("Campo Data de Emissão deve ser menor que a data corrente!");
        return false;
      }
      
      if (certtip == "N") {
          
        if (parseInt(data_cert) < parseInt(data_nasc)) {

          sMsgTip  = " Campo Data de Emissão deve ser maior ou igual a data de";
          sMsgTip += " nascimento do aluno ("+dianasc+"/"+mesnasc+"/"+anonasc+")!"; 
          alert(sMsgTip);
          return false;
        }
      } else if(certtip == "C") {
          
        if (parseInt(data_cert) <= parseInt(data_nasc)) {

          sMsgCertTip  = " Campo Data de Emissão deve ser maior que a data de nascimento";
          sMsgCertTip += " do aluno ("+dianasc+"/"+mesnasc+"/"+anonasc+")!";  
          alert(sMsgCertTip);
          return false;
        }
      }
    }
  }
  
  if (   nacion != 3
      && document.form1.ed47_c_passaporte.value != "") {

    sMsgPass  = " Campo N° Passaporte só pode ser informado quando nacionalidade do";
    sMsgPass += " aluno for Estrangeira (Aba Dados Pessoais).";   
    alert(sMsgPass);
    return false;
  }

  return true;
}

function js_TestaNi(cNI) {
	
  var NI;
  NI = js_LimpaCampo(cNI.value,10);
  
  if (NI.length != 11) {
	  
    alert('O número do CPF informado está incorreto');
    cNI.value = "";
    cNI.select();
    cNI.focus();
    return(false);
  }
  
  if (NI.substr(9, 2) != js_CalculaDV(NI.substr(0, 9), 11)) {
	  
    alert('O número do CPF informado está incorreto');
    cNI.value = "";
    cNI.select();
    cNI.focus();
    return(false);
  }

  return (true);
}

function js_verificacpf(obcgc) {
	
 if (   obcgc.value == 00000000000
     || obcgc.value == 00000000191) {
	 
   alert('Valor Informado não é Válido para CPF.');
   obcgc.value = "";
   obcgc.select();
   obcgc.focus();
 }
 
 if (obcgc.value.length == 11) {
   return js_TestaNi(obcgc);
 }
 
 if (obcgc.value != "") {
	 
   alert('Valor Informado não é Válido para CPF.');
   obcgc.value = "";
   obcgc.select();
   obcgc.focus();
 }

 return false;
}

function js_uf(uf) {

  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaMunicipio';
  var url     = 'edu1_aluno.RPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 asynchronous: false,
                                 parameters: 'uf='+uf+
                                             '&sAction='+sAction,
                                 onComplete: js_retornoPesquisaMunicipio
                               }
                              );
	    
}

function js_retornoPesquisaMunicipio(oAjax) {
	    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';
  if (oRetorno.length == 0) {
    
    sHtml += '<option value="">Selecione o Estado</option>';
    $('select_municipio').innerHTML = sHtml;
  } else {
	          
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno.length; i++) {
	            
      with (oRetorno[i]) {
        sHtml += '<option value="'+ed261_i_codigo+'">'+ed261_c_nome.urlDecode()+'</option>';
      }
    }

    $('select_municipio').innerHTML = sHtml;
    document.form1.select_municipio[0].selected = true;
    js_cartorio(document.form1.select_municipio.value);
  }

  $('select_municipio').disabled  = false;
}
	
function js_cartorio(municipio) {
	    
  $('select_cartorio').innerHTML      = "";
  $('select_cartorio').disabled       = true;

  js_divCarregando("Aguarde, carregando registro(s)","msgBox");
  var sAction = 'PesquisaCartorio';
  var url     = 'edu1_aluno.RPC.php';
  var oAjax = new Ajax.Request(url,
                               {
                                 method    : 'post',
                                 asynchronous: false,
                                 parameters: 'uf='+document.form1.uf.value+'&municipio='+municipio+'&sAction='+sAction,
                                 onComplete: js_retornoPesquisaCartorio
                               }
                              );
}

function js_retornoPesquisaCartorio(oAjax) {
	    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '';

  if (oRetorno.length==0) {
    sHtml += '<option value="">Não há cartório</option>';
  } else {
      
    sHtml += '<option value=""></option>';
    for (var i = 0;i < oRetorno.length; i++) {
	        
      with (oRetorno[i]) {

        if ( oRetorno[i] != "" ) {
    	    sHtml += '<option value="'+ed291_i_codigo+'">'+ed291_c_nome.urlDecode()+'</option>';
        }
      }
    }
  }

  $('select_cartorio').innerHTML = sHtml;
  $('select_cartorio').disabled  = false;
}

function js_matricula() {

  if ($('ed47_i_codigo').value != "") {

    var oParam        = new Object();
        oParam.exec   = "getMatriculaAluno";
        oParam.iAluno = $('ed47_i_codigo').value;

    sUrl = 'edu4_escola.RPC.php';

    js_webajax(oParam, 'js_retornoMatricula', sUrl);
  }
}

function js_retornoMatricula(oResponse) {

  var oRetorno = eval("("+oResponse.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    $('ed47_certidaomatricula').value = oRetorno.ed47_certidaomatricula;
    js_insereDadosMatricula(oRetorno.ed47_certidaomatricula);
  }
}

function js_insereDadosMatricula(iMatricula) {

  $('matri_cartorio').value       = iMatricula.substr(0, 6);
  $('matri_tipoacervo').value     = iMatricula.substr(6, 2);
  $('matri_numservico').value     = iMatricula.substr(8, 2);
  $('matri_anoregistro').value    = iMatricula.substr(10, 4);
  $('matri_tipolivro').value      = iMatricula.substr(14, 1);
  $('matri_numlivro').value       = iMatricula.substr(15, 5);
  $('matri_numfolha').value       = iMatricula.substr(20, 3);
  $('matri_termo').value          = iMatricula.substr(23, 7);
  $('matri_codverificador').value = iMatricula.substr(30, 2);
}

function js_initMatricula() {

  if ($('ed47_i_codigo').value != "") {

    var oParam        = new Object();
        oParam.exec   = "getMatriculaAluno";
        oParam.iAluno = $('ed47_i_codigo').value;

    sUrl = 'edu4_escola.RPC.php';

    js_webajax(oParam, 'js_retornoInitMatricula', sUrl);
  }
}

function js_retornoInitMatricula(oResponse) {

  var oRetorno = eval("("+oResponse.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('ed47_certidaomatricula').value = oRetorno.ed47_certidaomatricula;
    js_insereDadosMatricula(oRetorno.ed47_certidaomatricula);
  }
}


function js_init() {

  js_initMatricula();
		    
  if (   <?=isset($ed47_i_censoufcert)
      && !empty($ed47_i_censoufcert) ? 'true' : 'false'?>) {
		      
    var oUf = $('uf');
    for (var iCont = 0; iCont < oUf.length; iCont++) {

      if (oUf.options[iCont].value == '<?=$ed47_i_censoufcert?>') {

        oUf.selectedIndex = iCont;
        break;
      }
    }

    js_uf(oUf.value);
  }

  if (   <?=isset($ed47_i_censomuniccert)
      && !empty($ed47_i_censomuniccert) ? 'true' : 'false'?>) {

    var oMunicipio = $('select_municipio');
    for (var iCont = 0; iCont < oMunicipio.length; iCont++) {

      if (oMunicipio.options[iCont].value == '<?=$ed47_i_censomuniccert?>') {
	                  
        oMunicipio.selectedIndex = iCont;
        break;
      }
	  }

	  js_cartorio(oMunicipio.value);
  }

  if (   <?=isset($ed47_i_censocartorio)
      && !empty($ed47_i_censocartorio) ? 'true' : 'false'?>) {
		      
	  var oCartorio = $('select_cartorio');
    for (var iCont = 0; iCont < oCartorio.length; iCont++) {

      if (oCartorio.options[iCont].value == '<?=$ed47_i_censocartorio?>') {
                    
        oCartorio.selectedIndex = iCont;
        break;
      }
    }
  }
}

function js_checaMatricula() {

  if ($('matri_cartorio').value != "") {

    $('ed47_c_certidaonum').setAttribute('readOnly','readonly');
    $('ed47_c_certidaolivro').setAttribute('readOnly','readonly');
    $('ed47_c_certidaofolha').setAttribute('readOnly','readonly');
    $('ed47_c_certidaotipo').disabled = true;
    $('uf').disabled                  = true;
    $('select_municipio').disabled    = true;
    $('select_cartorio').disabled     = true;
  } else {

    $('ed47_c_certidaonum').removeAttribute('readOnly');
    $('ed47_c_certidaolivro').removeAttribute('readOnly');
    $('ed47_c_certidaofolha').removeAttribute('readOnly');
    $('ed47_c_certidaotipo').disabled = false;
    $('uf').disabled                  = false;
    $('select_municipio').disabled    = false;
    $('select_cartorio').disabled     = false;
  }
}

function js_habilitaMatriculaManual() {

  if (confirm('Deseja limpar o número da matrícula?')) {

    $('matri_cartorio').value         = "";
    $('matri_tipoacervo').value       = "";
    $('matri_numservico').value       = "";
    $('matri_anoregistro').value      = "";
    $('matri_tipolivro').value        = "";
    $('matri_numlivro').value         = "";
    $('matri_numfolha').value         = "";
    $('matri_termo').value            = "";
    $('matri_codverificador').value   = "";
    $('ed47_certidaomatricula').value = "";

    js_checaMatricula();

    var check1 = "";
    var check2 = "";

    if ($('ed47_c_certidaotipo').value == "N") {
      check1 = "selected";
    } else if ($('ed47_c_certidaotipo').value == "C") {
      check2 = "selected";
    }

    $('ed47_c_certidaotipo').innerHTML = "";
    sHtml  = "<option value=''></option><option value='N' "+check1+">NASCIMENTO</option>";
    sHtml += "<option value='C' "+check2+">CASAMENTO</option>";
    $('ed47_c_certidaotipo').innerHTML = sHtml;
  }
}

function js_retornoGetUf(oRetorno) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    sHtml = '';
    if (oRetorno.aResultado.length == 0) {

      sHtml             = "<option value''>Selecione o Estado</option>";
      $('uf').innerHTML = sHtml;
    } else {

      sHtml = "<option value=''></option>";
      
      for (var iCont = 0; iCont < oRetorno.aResultado.length; iCont++) {

        sHtml += "<option value='"+oRetorno.aResultado[iCont].ed260_i_codigo+"'>"+
                 oRetorno.aResultado[iCont].ed260_c_nome.urlDecode()+"</option>";
      }

      $('uf').innerHTML               = sHtml;
      document.form1.uf[0].selected   = true;
      js_uf($('uf').value);
    }

    $('select_cartorio').innerHTML = "";
    $('select_cartorio').innerHTML = "<option value=''>Selecione o Município</option>";
  }
}

function js_limpaCamposMatricula() {

  $('matri_cartorio').value       = "";
  $('matri_tipoacervo').value     = "";
  $('matri_numservico').value     = "";
  $('matri_anoregistro').value    = "";
  $('matri_tipolivro').value      = "";
  $('matri_numlivro').value       = "";
  $('matri_numfolha').value       = "";
  $('matri_termo').value          = "";
  $('matri_codverificador').value = "";
}

function js_mudafoco(elemento, iTamanho, evento) {

  var iTecla = 0;
  iTecla = evento.which;

  if (iTecla != '16' && iTecla != '9') {

    if (elemento.value.length == iTamanho) {
      elemento.next().focus();
    }
  }
}

function js_checaSomenteNumero(sCaractere) {

  var iTecla = 0;

  iTecla = sCaractere.which;

  if (   (iTecla > 47 && iTecla < 58)
      || iTecla == 8
      || iTecla == 127
      || iTecla == 0
      || iTecla == 9
      || iTecla == 13) {

    return true;
  } else {
    return false;
  }
}

function preencheDadosCertidao() {

  if( $F('matri_cartorio') != '' ) {
    js_buscaCartorioMatricula();
  }

  if( $F('matri_tipoacervo') != '' ) {
    js_checaTpAcervoMatricula();
  }

  if( $F('matri_numservico') != '' ) {
    js_checaNumServicoMatricula();
  }

  if( $F('matri_anoregistro') != '' ) {
    js_checaAnoRegistroMatricula();
  }

  if( $F('matri_tipolivro') != '' ) {
    js_checaTpLivroMatricula();
  }

  if( $F('matri_numlivro') != '' ) {
    js_checaNumLivroMatricula();
  }

  if( $F('matri_numfolha') != '' ) {
    js_checaFolhaMatricula();
  }

  if( $F('matri_termo') != '' ) {
    js_checaTermoMatricula();
  }

  if( $F('matri_codverificador') != '' ) {
    js_checaCodVerifMatricula();
  }
}

preencheDadosCertidao();
</script>