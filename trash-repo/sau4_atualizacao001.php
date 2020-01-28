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

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_jsplibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("sau4_atualizacao002.php");

db_postmemory($HTTP_POST_VARS);

$oDaoSauAtualiza   = db_utils::getdao('sau_atualiza');
$oDaoSauFechamento = db_utils::getdao('sau_fechamento');

$db_opcao          = isset($enabled_ver) && $enabled_ver == 'true' ? 1 : 3;
$db_botao          = true;

$oDaoSauFechamento->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("estilos.css");
?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>

    <center>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="left" valign="top" bgcolor="#CCCCCC"> 
            <br>
            <form name="form1" method="post" action=""  enctype="multipart/form-data">
            <center>
              <fieldset><legend><b>Atualização SIA</b></legend>
                <table border="0" align="left">
                  <tr>
                    <td height="18">&nbsp;</td>
                    <td height="18">&nbsp;</td>
                  </tr>          
              
                  <tr>
                    <td nowrap title="<?=@$Tfa05_i_codigo?>">
                      <b>Origem das Tabelas:</b>
                    </td>
                    <td> 
                      <?
                      db_input('origem', 50, @$origem, true, 'file', 1, "onblur=js_upload();");
                      ?>
                      <input name="upload" type="submit" id="upload" value="Enviar Arquivo" disabled>
                    </td>
                  </tr> 
              
                  <tr>
                    <td nowrap title="<?=@$Tsd97_i_compmes?>">
                      <b>
                      <?
                      db_ancora("Competencia", "js_pesquisasd98_i_fechamento(true);", $db_opcao);
                      ?>
                    </td>
                    <td> 
                      <?
                      db_input('sd97_i_compmes', 10, @$Isd97_i_compmes, true, 'text', $db_opcao, 
                               "onchange=js_atualizapasta()"
                              );
                      ?> 
                      / 
                      <?
                      db_input('sd97_i_compano', 10, @$Isd97_i_compano, true, 'text', $db_opcao, 
                               "onchange=js_atualizapasta()"
                              );
                      ?>
                    </td>
                  </tr> 
                      
                  <tr>
                    <td nowrap title="<?=@$Tfa05_i_codigo?>">
                      <b>Pasta de Origem:</b>
                    </td>
                    <td>
                     <?
                     db_input('AArquivo',50,@$AArquivo,true,'text',$db_opcao );
                     ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" id="listatabelas" style="display: none">
                      <center>
                        <fieldset style="width: 400" ><legend><b>Tabelas</b></legend>
                          <table border="1" style="width: 395">
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_detalhe" id="sau_detalhe" checked 
                                  onclick="js_marcadetalhe();">
                                sau_detalhe
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_cid" id="rl_procedimento_cid" checked>
                                rl_procedimento_cid
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_financiamento" id="sau_financiamento" checked>
                                sau_financiamento
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_detalhe" id="rl_procedimento_detalhe" 
                                  checked>
                                rl_procedimento_detalhe
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_grupo" id="sau_grupo" checked>
                                sau_grupo
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_incremento" id="rl_procedimento_incremento" 
                                  checked>
                                rl_procedimento_incremento
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_habilitacao" id="sau_habilitacao" checked 
                                  onclick="js_marcahabilita();">
                                sau_habilitacao
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_leito" id="rl_procedimento_leito" 
                                  checked>
                                rl_procedimento_leito
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_modalidade" id="sau_modalidade" checked 
                                  onclick="js_marcamodalidade();">
                                sau_modalidade
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_modalidade" id="rl_procedimento_modalidade" 
                                  checked>
                                rl_procedimento_modalidade
                              </td>
                            <tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_registro" id="sau_registro" checked 
                                  onclick="js_marcaregistro();">
                                sau_registro
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_origem" id="rl_procedimento_origem" 
                                  checked>
                                rl_procedimento_origem
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_rubrica" id="sau_rubrica" checked>
                                sau_rubrica
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_registro" id="rl_procedimento_registro" 
                                  checked>
                                rl_procedimento_registro
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_servico" id="sau_servico" checked 
                                  onclick="js_marcaservico();">
                                sau_servico
                              </td>
                              <td>
                                <input type="checkbox" name="tb_servico_classificacao" id="tb_servico_classificacao" 
                                  checked>
                                tb_servico_classificacao
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_tipoleito" id="sau_tipoleito" checked>
                                sau_tipoleito
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_servico" id="rl_procedimento_servico" 
                                  checked>
                                rl_procedimento_servico
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_subgrupo" id="sau_subgrupo" checked>
                                sau_subgrupo
                              </td>
                              <td>
                                <input type="checkbox" name="tb_sia_sih" id="tb_sia_sih" checked>
                                tb_sia_sih
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="sau_formaorganizacao" id="sau_formaorganizacao" checked>
                                sau_formaorganizacao
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_sia_sih" id="rl_procedimento_sia_sih" 
                                  checked>
                                rl_procedimento_sia_sih
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="tb_procedimento" id="tb_procedimento" checked 
                                  onclick="js_marcaproced()">
                                tb_procedimento
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_ocupacao" id="rl_procedimento_ocupacao" 
                                  checked>
                                rl_procedimento_ocupacao
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <input type="checkbox" name="tb_cid" id="tb_cid" checked>
                                tb_cid
                              </td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_compativel" id="rl_procedimento_compativel" 
                                  checked>
                                rl_procedimento_compativel
                              </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td>
                                <input type="checkbox" name="rl_excecao_compatibilidade" id="rl_excecao_compatibilidade" 
                                  checked>
                                rl_excecao_compatibilidade
                              </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td>
                                <input type="checkbox" name="rl_procedimento_habilitacao" 
                                  id="rl_procedimento_habilitacao" checked>
                                rl_procedimento_habilitacao
                              </td>
                            </tr>
                          </table>
                        </fieldset>
                      </center>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tfa05_i_codigo?>">
                      <b>Executando:</b>
                    </td>
                    <td> 
                      <table>
                        <tr>
                          <td>
                            <?=db_criatermometro('termometro', 'Concluido...', 'blue', 1);?>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>     
                </table>
              </fieldset>
                      
              <table border="0">
                <tr>
                  <td height="18">&nbsp;</td>
                  <td height="18">&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    <input name="processar" type="submit" id="processar" value="Processar" onclick="js_montalista();" 
                      <?=(isset($enabled) && $enabled == 'true') ? 
                          (isset($processar) ? 'disabled' : '') : 'disabled' ?>>
                    <input name="verificacao" type="submit" id="verificacao" value="Verificação" 
                      <?=(isset($enabled_ver) && $enabled_ver == 'true') ? '' : 'disabled' ?> >
                    <input name="relatorio" type="button" id="relatorio" value="Relatório de Conferência" 
                      onclick="js_relatorio();" <?=(isset($enabled) && $enabled == 'true' ? '' : 'disabled') ?> >
                    <input type="hidden" name="str" id="str" value="">
                  </td>
                </tr>
              </table>
            </center>
            </form>
          </td>
        </tr>
      </table>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
            db_getsession("DB_anousu"), db_getsession("DB_instit")
           );
    ?>

<script>

function js_upload() {
  document.form1.upload.disabled = (document.form1.origem.value == '');
}
function js_relatorio() {

  sQuery = "ano=<?=@$sd97_i_compano?>&mes=<?=@$sd97_i_compmes?>";
  oJan = window.open('sau4_atualizacao003.php?'+sQuery, '', 'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJan.moveTo(0, 0);

}

function js_atualizapasta() {

  oForm                      = document.form1;
  oForm.sd97_i_compmes.value = strPad(oForm.sd97_i_compmes.value, 2, '0', 'L' );  
  oForm.sd97_i_compano.value = strPad(oForm.sd97_i_compano.value, 4, '0', 'L' );  
  oForm.AArquivo.value       = "tmp/TabelaUnificada_"+oForm.sd97_i_compano.value+oForm.sd97_i_compmes.value;
  
  oForm.processar.disabled   = true;
  oForm.relatorio.disabled   = true;
   
}
function js_pesquisasd98_i_fechamento(mostra) {

  if (mostra == true) {

     js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_fechamento', 'func_sau_fechamento.php?'+
                         'funcao_js=parent.js_mostrasau_fechamento1|sd97_i_compmes|sd97_i_compano', 
                         'Pesquisa', true
                        );

  } else {

    if (document.form1.sd98_i_fechamento.value != '') {

      js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_fechamento', 'func_sau_fechamento.php?pesquisa_chave='+
                          document.form1.sd98_i_fechamento.value+'&funcao_js=parent.js_mostrasau_fechamento', 
                          'Pesquisa', false
                         );

     } else {
       document.form1.sd97_i_compmes.value = ''; 
     }

  }

}

function js_mostrasau_fechamento(chave, erro) {

  document.form1.sd97_i_compmes.value = chave; 
  if (erro == true) {

    document.form1.sd98_i_fechamento.focus(); 
    document.form1.sd98_i_fechamento.value = '';
     
  }
  js_atualizapasta();

}

function js_mostrasau_fechamento1(chave1, chave2) {

  document.form1.sd97_i_compmes.value = chave1;
  document.form1.sd97_i_compano.value = chave2;
  db_iframe_sau_fechamento.hide();
  js_atualizapasta();

}

tamanho = 1;
function iniciabarra(linhas) {

  if (tamanho < linhas) {

    percentagem            = Number((100 * tamanho) / linhas);
    j                      = Number((percentagem / 100) * 500);
    $('barra').style.width = j;
    $('percent').innerHTML = parseInt(percentagem)+'%';
    tamanho++;
    setTimeout('iniciabarra('+linhas+')', 20);

  } else {
    document.getElementById('percent').innerHTML = '100%';
  }

}
</script>

<?

//Valida botão UPLOAD
if (isset($upload)) {
  
  $sDestino = "tmp/".substr($_FILES['origem']['name'], 0, 22);
  @mkdir($sDestino);

  if (file_exists("bin/unzip")) {
     if (move_uploaded_file($_FILES['origem']['tmp_name'], $sDestino."/".$_FILES['origem']['name'])) {

        @system("bin/unzip -qud$sDestino ".$sDestino."/".$_FILES['origem']['name'], $lRetorno);

        if( !isset( $lRetorno ) ){
          db_msgbox("Não foi possível descompactar arquivo: ".$sDestino."/".$_FILES['origem']['name']. 
                "\\n\\nContate o adminstrador para verificar permissões e configurações do sistema.");
        }else{
          db_msgbox("Cópia efetuada com sucesso! ");

          $iAno = substr($sDestino, -6, 4);
          $iMes = substr($sDestino, -2);
          db_redireciona("sau4_atualizacao001.php?enabled_ver=true&sd97_i_compano=".$iAno.
                     "&sd97_i_compmes=$iMes&AArquivo=$sDestino"
                    );
        }
     } else {
        db_msgbox("Erro ao copiar o arquivo {$_FILES['origem']['name']}. ");
     }
  }else{
     db_msgbox("ERRO: não foi possível encontrar o utilitário para descompactar o arquivo.\\n\\nContate o adminsitrador.");
  }

}

//Valida botão Verificar
if (isset($verificacao)) {
  
  if (empty($AArquivo)) {
    db_msgbox("Pasta contendo os arquivos não foi informado.");
  } else {

    $pFile = @fopen($AArquivo."/tb_procedimento.txt", "r");
    if (is_resource($pFile)) {

      // $sComp = trim(substr(fgets($pFile), 320, 6));
      $sComp = trim(substr(fgets($pFile), 324, 6));

      if ($sComp == $sd97_i_compano.$sd97_i_compmes) {
 
        $sSql   = $oDaoSauAtualiza->sql_query(null, '*', null, "s100_i_anocomp = $sd97_i_compano and".
                                              " s100_i_mescomp = $sd97_i_compmes"
                                             );
        $rs     = $oDaoSauAtualiza->sql_record($sSql);
        if ($oDaoSauAtualiza->numrows > 0) {

          $oDadosSauAtualiza = db_utils::fieldsMemory($rs, 0);
          db_msgbox("Competência $sd97_i_compano/$sd97_i_compmes já atualizada em".
                    " {$oDadosSauAtualiza->s100_d_data} por {$oDadosSauAtualiza->login}"
                   );
          echo "<script> 
                if (!confirm('Deseja continuar com a importação?')) { 
                  location.href = 'sau4_atualizacao001.php';
                }
                </script>";

        } else {
          db_msgbox("Competência das tabelas SIA são válidas.");
        }

        db_redireciona("sau4_atualizacao001.php?enabled=true".
                       "&sd97_i_compano=$sd97_i_compano&sd97_i_compmes=$sd97_i_compmes&AArquivo=$AArquivo&show=1"
                      );

      } else {
        db_msgbox("Competência invalida.");     
      }

    } else {

      db_msgbox("Localização dos arquivos inválida ou sem permissão de acesso. \\n\\n$AArquivo");
      db_redireciona("sau4_atualizacao001.php?sd97_i_compano=$sd97_i_compano".
                     "&sd97_i_compmes=$sd97_i_compmes&AArquivo=$AArquivo"
                    );

    }

  }
  
}


//Valida botão processar
if (isset($processar)) {

  if (empty($AArquivo)) {
    db_msgbox("Pasta contendo os arquivos não foi informado.");
  } else {

    $pFile = @fopen( $AArquivo."/tb_procedimento.txt", "r");
    if (!is_resource($pFile)) {

      db_msgbox("Localização dos arquivos inválida. \\n\\n$AArquivo");
      exit;

    }

    db_inicio_transacao();

    //Grava atuaização na tabela sau_atualiza
    $oDaoSauAtualiza->s100_i_login   = DB_getsession("DB_id_usuario");
    $oDaoSauAtualiza->s100_i_mescomp = $sd97_i_compmes;
    $oDaoSauAtualiza->s100_i_anocomp = $sd97_i_compano;
    $oDaoSauAtualiza->incluir(null);
    
    if ($oDaoSauAtualiza->erro_status == '0') {

      $oDaoSauAtualiza->erro(true, false);
      db_fim_transacao(true);

    } else {

      $pArqConf = fopen("tmp/conferencia_$sd97_i_compmes$sd97_i_compano", 'w');
  
      //Tabelas sem competencia, podem gerar erro ja q esta cadastrado    
      $aVet     = explode('|', $str);
      $aTabela  = array();

      if ($aVet[0] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_detalhe", "tabela" => "sau_detalhe", 
                           "nextval" => "nextval('sau_detalhe_sd73_i_codigo_seq')", 
                           "campos" => "sd73_i_codigo, sd73_c_detalhe, sd73_c_nome, sd73_i_anocomp, sd73_i_mescomp"
                          );
      }
      if ($aVet[1] == '1') {;

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_financiamento", "tabela" => "sau_financiamento", 
                           "nextval" => "nextval('sau_financiamento_sd65_i_codigo_seq')", 
                           "campos" => "sd65_i_codigo, sd65_c_financiamento, sd65_c_nome,".
                           " sd65_i_anocomp, sd65_i_mescomp"
                          );

      }
      if ($aVet[2] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_grupo", "tabela" => "sau_grupo", 
                           "nextval" => "nextval('sau_grupo_sd60_i_codigo_seq')", 
                           "campos" => "sd60_i_codigo, sd60_c_grupo, sd60_c_nome, sd60_i_anocomp, sd60_i_mescomp"
                          );

      }
      if ($aVet[3] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_habilitacao", "tabela" => "sau_habilitacao", 
                           "nextval" => "nextval('sau_habilitacao_sd75_i_codigo_seq')", 
                           "campos" => "sd75_i_codigo, sd75_c_habilitacao, sd75_c_nome, sd75_i_anocomp, sd75_i_mescomp"
                          );

      }
      if ($aVet[4] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_modalidade", "tabela" => "sau_modalidade", 
                           "nextval" => "nextval('sau_modalidade_sd82_i_codigo_seq')", 
                           "campos" => "sd82_i_codigo, sd82_c_modalidade, sd82_c_nome, sd82_i_anocomp, sd82_i_mescomp"
                          );

      }
      if ($aVet[5] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_registro", "tabela" => "sau_registro", 
                           "nextval" => "nextval('sau_registro_sd84_i_codigo_seq')", 
                           "campos" => "sd84_i_codigo, sd84_c_registro, sd84_c_nome, sd84_i_anocomp, sd84_i_mescomp"
                          );

      }
      if ($aVet[6] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_rubrica", "tabela" => "sau_rubrica", 
                           "nextval" => "nextval('sau_rubrica_sd64_i_codigo_seq')", 
                           "campos" => "sd64_i_codigo, sd64_c_rubrica, sd64_c_nome, sd64_i_anocomp, sd64_i_mescomp"
                          );

      }
      if ($aVet[7] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_servico", "tabela" => "sau_servico", 
                           "nextval" => "nextval('sau_servico_sd86_i_codigo_seq')", 
                           "campos" => "sd86_i_codigo, sd86_c_servico, sd86_c_nome, sd86_i_anocomp, sd86_i_mescomp"
                          );

      }
      if ($aVet[8] == '1') {

        $aTabela[] = array("funcao" => "", "arquivo" => "tb_tipo_leito", "tabela" => "sau_tipoleito", 
                           "nextval" => "nextval('sau_tipoleito_sd80_i_codigo_seq')", 
                           "campos" => "sd80_i_codigo, sd80_c_leito, sd80_c_nome, sd80_i_anocomp, sd80_i_mescomp"
                          );

      }
      if ($aVet[9] == '1') {
        $aTabela[] = array("funcao" => "funcSubgrupo", "arquivo" => "tb_sub_grupo", "tabela" => "sau_subgrupo");
      }
      if ($aVet[10] == '1') {

        $aTabela[] = array("funcao" => "funcFormaOrganizacao", "arquivo" => "tb_forma_organizacao", 
                           "tabela" => "sau_formaorganizacao", 
                          );

      }
      if ($aVet[11] == '1') {

        $aTabela[] = array("funcao" => "funcProcedimento", "arquivo" => "tb_procedimento", 
                           "tabela" => "sau_procedimento"
                          );

      }         
      if ($aVet[27] == '1') {
        $aTabela[] = array("funcao" => "funcCid", "arquivo" => "tb_cid", "tabela" => "sau_cid");
      }
      if ($aVet[21] == '1') {
        $aTabela[] = array("funcao" => "funcSiasihTipoproc", "arquivo" => "tb_sia_sih", "tabela" => "sau_siasih");
      }
      if ($aVet[19] == '1') {

        $aTabela[] = array("funcao" => "funcServClassificacao", "arquivo" => "tb_servico_classificacao", 
                           "tabela" => "sau_servclassificacao"
                          );

      }

      if ($aVet[12] == '1') {
        $aTabela[] = array("funcao" => "funcProcCid", "arquivo" => "rl_procedimento_cid", "tabela" => "sau_proccid");
      }
      if ($aVet[13] == '1') {

        $aTabela[] = array("funcao" => "funcProcDetalhe", "arquivo" => "rl_procedimento_detalhe", 
                           "tabela" => "sau_procdetalhe");

      }
      if ($aVet[14] == '1') {

        $aTabela[] = array("funcao" => "funcProcIncremento", "arquivo" => "rl_procedimento_incremento", 
                           "tabela" => "sau_procincremento"
                          );

      }
      if ($aVet[15] == '1') {

        $aTabela[] = array("funcao" => "funcProcLeito", "arquivo" => "rl_procedimento_leito", 
                           "tabela" => "sau_procleito"
                          );

      }
      if ($aVet[16] == '1') {

        $aTabela[] = array("funcao" => "funcProcModalidade", "arquivo" => "rl_procedimento_modalidade", 
                           "tabela" => "sau_procmodalidade"
                          );

      }
      if ($aVet[17] == '1') {

        $aTabela[] = array("funcao" => "funcProcOrigem", "arquivo" => "rl_procedimento_origem", 
                           "tabela" => "sau_procorigem"
                          );

      }
      if ($aVet[18] == '1') {

        $aTabela[] = array("funcao" => "funcProcRegistro", "arquivo" => "rl_procedimento_registro", 
                           "tabela" => "sau_procregistro"
                          );
      }
      if ($aVet[20] == '1') {

        $aTabela[] = array("funcao" => "funcProcServico", "arquivo" => "rl_procedimento_servico", 
                           "tabela" => "sau_procservico"
                          );

      }
      if ($aVet[22] == '1') {

        $aTabela[] = array("funcao" => "funcProcSiasih", "arquivo" => "rl_procedimento_sia_sih", 
                           "tabela" => "sau_procsiasih"
                          );
      }
      if ($aVet[23] == '1') {

        $aTabela[] = array("funcao" => "funcProcCbo", "arquivo" => "rl_procedimento_ocupacao", 
                           "tabela" => "sau_proccbo"
                          );

      }
      if ($aVet[24] == '1') {

        $aTabela[] = array("funcao" => "funcProcCompativel", "arquivo" => "rl_procedimento_compativel", 
                           "tabela" => "sau_proccompativel"
                          );

      }
      if ($aVet[25] == '1') {

        $aTabela[] = array("funcao" => "funcProcRestricao", "arquivo" => "rl_excecao_compatibilidade", 
                           "tabela" => "sau_execaocompatibilidade"
                          );

      }
      if ($aVet[26] == '1') {

        $aTabela[] = array("funcao" => "funcProcHabilitacao", "arquivo" => "rl_procedimento_habilitacao", 
                           "tabela" => "sau_prochabilitacao"
                          );

      }

      $sPasta            = $AArquivo;
                         
      $lSucesso          = true;
      $iTamTabelas       = count($aTabela);
      $iContRegInseridos = 0; // Número de registros que foram inseridos em cada tabela
      for ($iContArq = 0; $iContArq < $iTamTabelas; $iContArq++) {

        $iContRegInseridos = 0; // Zero o número de registros para comecar a contar para cada tabela
        
        flush(); // descarga do processamento PHP para o HTML
        if (!$lSucesso) {
          break;
        }

        //Arquivos
        $sArqTb     = $sPasta."/".$aTabela[$iContArq]["arquivo"].".txt";
        $sArqLayout = $sPasta."/".$aTabela[$iContArq]["arquivo"]."_layout.txt";
        
        //Chama função definida
        if (!empty($aTabela[$iContArq]["funcao"])) {

          $lSucesso = call_user_func($aTabela[$iContArq]["funcao"], $sArqTb, $sd97_i_compano.$sd97_i_compmes);
          $sStrConf = $lSucesso ? 'Importado' : 'Cancelado';
          fwrite($pArqConf, "$sArqTb|$sStrConf|$iContRegInseridos\n"); 

          if (!$lSucesso) {
            break;
          }

          db_atutermometro($iContArq, count($aTabela), 'termometro', 1, $aTabela[$iContArq]["funcao"]);
          continue;

        }
        if (!file_exists($sArqTb) || !file_exists($sArqLayout)) {

          db_msgbox("Arquivo Inexistente: \\n $sArqTb ou $sArqLayout. \\n\\n Verifique as permissões da pasta.");
          $lSucesso = false;
          break;

        } else {

          $aArqTb     = file($sArqTb);
          $aArqLayout = file($sArqLayout);

          //verifica layout
          unset($aVetLayout);
          $iTamArqLayout = count($aArqLayout);

          for($iContLayout = 1; $iContLayout < $iTamArqLayout; $iContLayout++) {
            $aVetLayout[] = explode(',', $aArqLayout[$iContLayout]);
          }

          //Values
          $iTamTb = count($aArqTb);
          for ($iContTb = 0; $iContTb < $iTamTb; $iContTb++) {

            $sStrValues = "";
            $sStrSep    = "";
            $sStrComp   = "";
            
            //Pega tamanho dos campo no layout
            $iTamLayout = count($aVetLayout);
            for ($iContLayout = 0; $iContLayout < $iTamLayout; $iContLayout++) {

              if ($aVetLayout[$iContLayout][0] == "DT_COMPETENCIA") {

                $sStrValues .= $sStrSep."'";
                $sStrValues .= trim(substr($aArqTb[$iContTb], $aVetLayout[$iContLayout][2] - 1, 4))."'";
                $sStrValues .= $sStrSep."'";
                $sStrValues .= trim(substr($aArqTb[$iContTb], ($aVetLayout[$iContLayout][2] - 1)+4, 2))."'";
                $sStrComp    = trim(substr($aArqTb[$iContTb], 
                                           $aVetLayout[$iContLayout][2] - 1, 
                                           $aVetLayout[$iContLayout][1]
                                          )
                                   );

              } else {
                
                // nome (campo de descricao)
                if ($iContLayout == 1) {

                  $sStrValues .= $sStrSep."'".
                                 strtoupper(TiraAcento(trim(str_replace("'", 
                                                                        '',
                                                                        substr($aArqTb[$iContTb], 
                                                                               $aVetLayout[$iContLayout][2] - 1,
                                                                               $aVetLayout[$iContLayout][1]
                                                                              )
                                                                       )
                                                           )
                                                      )
                                           )."'";

                } else {

                  $sStrValues .= $sStrSep."'".trim(substr($aArqTb[$iContTb], 
                                                          $aVetLayout[$iContLayout][2] - 1,
                                                          $aVetLayout[$iContLayout][1]
                                                         )
                                                  )."'";

                }

              }

              $sStrSep = ", ";

            }

            //Insert
            if ($sStrComp == ($sd97_i_compano.$sd97_i_compmes)) {

              // Verifico se o registro já foi incluído
              $aCampos                 = explode(',', $aTabela[$iContArq]['campos']);
              $sTmp                    = trim($sStrValues);
              $sTmp[0]                 = ' '; // Tiro a primeira aspa
              $sTmp[strlen($sTmp) - 1] = ' '; // Tiro a última aspa
              $sTmp                    = trim($sTmp); // Tiro os espaços em branco do início e do fim
              $aValores                = explode("', '", $sTmp);
              $oDao                    = db_utils::getdao($aTabela[$iContArq]['tabela']);
              $sSql                    = $oDao->sql_query_file(null, '*', '', $aCampos[1]." = '".$aValores[0]."'".
                                                               ' and '.$aCampos[3].' = '.$aValores[2].
                                                               ' and '.$aCampos[4].' = '.$aValores[3]
                                                              );
              $oDao->sql_record($sSql);
              if ($oDao->numrows > 0) { // Se já foi incluído, vou para o próximo registro
                continue;
              }

              $sStrInsert  = "insert into ".$aTabela[$iContArq]["tabela"]." (";                       
              $sStrInsert .= $aTabela[$iContArq]["campos"]." ) ";           
              $sStrInsert .= "values ( ".$aTabela[$iContArq]["nextval"].", ".$sStrValues." );";
              $lRetornoIns = @pg_query($sStrInsert); 
              if ($lRetornoIns == false) {

                db_msgbox("Arquivo: $sArqTb \\nTabela: ".$aTabela[$iContArq]["tabela"]." \\n\\n".pg_errormessage());
                fwrite($pArqConf, "$sArqTb|Cancelado|$iContRegInseridos\n");
                $iContArq--; // decremento para anular o incremento do fim do for
                $lSucesso = false;
                break;

              }
              $iContRegInseridos++; // Incremento o número de registros importados
              
            }

          } // for
          fwrite($pArqConf, "$sArqTb|Importado|$iContRegInseridos\n"); 

        } // else existe arquivo de tabela e de layout

        db_atutermometro($iContArq, count($aTabela), 'termometro', 1, $aTabela[$iContArq]["funcao"]);      

      } // for tabelas

      db_fim_transacao(!$lSucesso);
      if (!$lSucesso) {
        db_msgbox('Erro na atualização da tabela '.$aTabela[$iContArq]["tabela"]);
      } else {
        db_msgbox('Atualização realizada com sucesso!');
      }

    } // else não houve erro de inserção na tabela sau_atualiza

  } // else arquivo não vazio

} // if do botao processar

?>

  </body>
</html>
<script>
<?if (isset($show)) {?>
    $('listatabelas').style.display='';
<?}?>
function js_marcaproced() {
   
  if ($('tb_procedimento').checked == false) {
    ok = false;
  } else {
    ok = true;
  }
  
  $('rl_procedimento_cid').checked = ok;
  if ($('sau_detalhe').checked == true) {
    $('rl_procedimento_detalhe').checked = ok;
  }

  $('rl_procedimento_incremento').checked = ok;
  $('rl_procedimento_leito').checked      = ok;

  if ($('sau_modalidade').checked == true) {
    $('rl_procedimento_modalidade').checked = ok;
  }

  $('rl_procedimento_origem').checked = ok;
  if ($('sau_registro').checked == true) {
    $('rl_procedimento_registro').checked = ok;
  }

  $('tb_servico_classificacao').checked = ok;
  if ($('sau_servico').checked == true) {
    $('rl_procedimento_servico').checked = ok;
  }
  $('tb_sia_sih').checked                 = ok;
  $('rl_procedimento_sia_sih').checked    = ok;
  $('rl_procedimento_ocupacao').checked   = ok;
  $('rl_procedimento_compativel').checked = ok;
  $('rl_excecao_compatibilidade').checked = ok;

  if ($('sau_habilitacao').checked == true) {
    $('rl_procedimento_habilitacao').checked = ok;
  }

}
function js_marcadetalhe() {

  if ($('sau_detalhe').checked == false) {
    ok = false;
  } else {
    ok = true;
  } 
  $('rl_procedimento_detalhe').checked = ok;

}
function js_marcahabilita() {

  if ($('sau_habilitacao').checked == false) {
    ok = false;
  } else {
    ok = true;
  } 
  $('rl_procedimento_habilitacao').checked = ok;

}
function js_marcamodalidade() {

  if ($('sau_modalidade').checked == false) {
    ok = false;
  } else {
    ok = true;
  } 
  $('rl_procedimento_modalidade').checked = ok;
   
}
function js_marcaregistro() {

  if ($('sau_registro').checked == false) {
    ok = false;
  } else {
    ok = true;
  } 
  $('rl_procedimento_registro').checked = ok;

}
function js_marcaservico() {

   if ($('sau_servico').checked==false) {
     ok = false;
   } else {
     ok = true;
   } 
   $('rl_procedimento_servico').checked = ok;

}
function js_montalista() {

  $('processar').disabled = true;
  str                     = '';
  str                     = ($('sau_detalhe').checked == true) ? '1' : '0';
  str                    += ($('sau_financiamento').checked == true)? '|1' : '|0';
  str                    += ($('sau_grupo').checked == true) ? '|1' : '|0';
  str                    += ($('sau_habilitacao').checked == true) ? '|1' : '|0';
  str                    += ($('sau_modalidade').checked == true) ? '|1' : '|0';
  str                    += ($('sau_registro').checked == true) ? '|1' : '|0';
  str                    += ($('sau_rubrica').checked == true) ? '|1' : '|0';
  str                    += ($('sau_servico').checked == true) ? '|1' : '|0';
  str                    += ($('sau_tipoleito').checked == true) ? '|1' : '|0';
  str                    += ($('sau_subgrupo').checked == true) ? '|1' : '|0';
  str                    += ($('sau_formaorganizacao').checked == true) ? '|1' : '|0';
  str                    += ($('tb_procedimento').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_cid').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_detalhe').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_incremento').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_leito').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_modalidade').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_origem').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_registro').checked == true) ? '|1' : '|0';
  str                    += ($('tb_servico_classificacao').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_servico').checked == true) ? '|1' : '|0';
  str                    += ($('tb_sia_sih').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_sia_sih').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_ocupacao').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_compativel').checked == true) ? '|1' : '|0';
  str                    += ($('rl_excecao_compatibilidade').checked == true) ? '|1' : '|0';
  str                    += ($('rl_procedimento_habilitacao').checked == true) ? '|1' : '|0';
  str                    += ($('tb_cid').checked == true) ? '|1' : '|0';
  $('str').value          = str;
  $('processar').disabled = false;

}
</script>