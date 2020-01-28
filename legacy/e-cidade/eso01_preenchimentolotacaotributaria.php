<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);

$aCGM = array();
$sMsg = null;

try {
    $sSqlCGM  = '     select distinct z01_numcgm as cgm,                 ';
    $sSqlCGM .= '            z01_cgccpf||\' - \'||z01_nome as empregador ';
    $sSqlCGM .= '       from rhlota                                      ';
    $sSqlCGM .= ' inner join cgm                                         ';
    $sSqlCGM .= '         on rhlota.r70_numcgm = cgm.z01_numcgm          ';
    $sSqlCGM .= '      where r70_instit = '. db_getsession("DB_instit")   ;
    $sSqlCGM .= '   order by z01_numcgm '                                 ;

    $rsSqlCGM = db_query($sSqlCGM);

    if (!$rsSqlCGM) {
        throw new DBException("Ocorreu um erro ao consultar os CGM vinculados as lotações.\nContate o suporte.");
    }

    if (pg_num_rows($rsSqlCGM) > 0) {
        $aCGM = db_utils::makeCollectionFromRecord($rsSqlCGM, function ($oItemCGM) {
            return (object)array('cgm'=>$oItemCGM->cgm,'empregador'=>$oItemCGM->empregador);
        });
    } else {
        throw new DBException("Desculpe, não encontramos nenhum Empregador vinculado na instituição.\nContate o suporte.");   
    }
} catch (Exception $e) {
    $sMsg = $e->getMessage();
}
?>
<html>
    <head>
        <title>DBSeller Informática Ltda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <?php
        db_app::load("scripts.js");
        db_app::load("prototype.js");
        db_app::load("object.js");
        db_app::load("Input/DBInput.widget.js, DBInputHora.widget.js, Input/DBInputCep.widget.js,Input/DBInputCNPJ.js,Input/DBInputCpf.widget.js,Input/DBInputDate.widget.js");
        db_app::load("Input/DBInputInteger.widget.js, Input/DBInputTelefone.widget.js,Input/DBInputValor.widget.js");
        db_app::load("Input/DBInputCheckboxRadio.widget.js, Input/DBCheckBox.widget.js,Input/DBRadio.widget.js,Collection.widget.js");
        db_app::load("avaliacao/DBViewFormulario.classe.js, avaliacao/DBViewGrupoPerguntas.classe.js,avaliacao/DBViewPergunta.classe.js,avaliacao/DBViewResposta.classe.js");
        db_app::load("AjaxRequest.js,estilos.css,grid.style.css,avaliacao.css");
        ?>

        <style>
            .controle {
                width: 80px;
            }

            #anterior {
                margin-left: 2px;
                float: left;
            }

            #proximo {
                margin-right: 2px;
                float: right;
            }

            .db-tooltip {
                display: none;
            }
        </style>
    </head>
<body>
    <form class="container" style="width: 800px;">
            <fieldset>
                <legend><label for="cgm">Escolha o Empregador</label></legend>
                <select id = 'cgm' style="width:100%" onchange="buscarAvaliacao(event)">
                    <?php 
                    if (!empty($aCGM)) { 
                        foreach ($aCGM as $oCGM) {
                            ?>
                            <option value="<?php echo $oCGM->cgm; ?>"><?php echo $oCGM->empregador; ?></option>
                            <?php 
                        }
                    } 
                    ?>
                </select>
            </fieldset>
    <fieldset>
    <legend>Formulário de Cadastro para o eSocial</legend>
        <div id="questionario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle" />
    <input type="button" id="salvar"   name="salvar"   value="Salvar"   class="controle" />
    <input type="button" id="proximo"  name="proximo"  value="Próximo"  class="controle" />
    <br>
    <br>
    <!-- TODO ENVIO -->
    <!-- Só descomentar -->
    <!-- <input type="button" id="envioESocial"  name="envioESocial"  value="Enviar para eSocial" /> -->
    <!-- FIM TODO ENVIO -->
    <form>
    <script type="text/javascript">
        var viewAvaliacao      = '';
        var iCGMAnterior = '';

        (function() {
            try {
                buscarAvaliacao();
            } catch (e) {
                alert(e);
            }
        })();

        function buscarAvaliacao(event) {

            if(event) {
                if(!confirmaSaida("Se você trocar de empregador os dados que não foram salvos serão perdidos.\nTem certeza que deseja trocar?")) {
                    $('cgm').value = iCGMAnterior;
                    return false;
                }
            }

            removeEventoBotoes();
            iCGMAnterior = $F('cgm');
            $('questionario').innerHTML = '';

            var iCGM   = $F('cgm');
            var oDados = {
                exec : 'buscarAvaliacao'
            };

            if (!empty(iCGM)) {
                oDados.iCGM = iCGM;
            }

            AjaxRequest.create('eso01_preenchimentolotacaotributaria.RPC.php', oDados, montarAvaliacao)
                .setMessage('Buscando dados...')
                .execute();
        }

        function montarAvaliacao(oResponse, lErro) {

            if (lErro) {
                alert(oResponse.mensagem);
            }

            viewAvaliacao = DBViewFormulario.makeFromObject(oResponse.oFormulario)
                .setEvent('changeStep', controlarBotoes)
                .show($('questionario'));

            $('proximo').observe('click', function() {
                this.blur();
                salvarQuestionario(viewAvaliacao, viewAvaliacao.getStatus().grupoAtual.getCodigo());
            });

            $('anterior').observe('click', function() {
                var sMensagem = "As informações preenchidas, poderão ser perdidas.\n";
                sMensagem    += "Tem certeza que deja voltar?";

                if(confirm(sMensagem)) {
                    viewAvaliacao.recurarGrupo();
                }
            });

            $('salvar').observe('click', function() {
                salvarQuestionario(viewAvaliacao);
            });
        }

        function salvarQuestionario(viewAvaliacao, iCodigoGrupo) {
            if(! viewAvaliacao.getStatus().grupoAtual.isValido()) {
                alert("Há informações obrigatórias inconsistentes.\nVerifique.");
                return false;
            }
            AjaxRequest.create(
                'eso01_preenchimentolotacaotributaria.RPC.php',
                {
                    exec                  : 'salvarAvaliacao',
                    iCGM                  : iCGMAnterior,
                    iCodigoAvaliacao      : viewAvaliacao.codigo,
                    iCodigoGrupoPerguntas : iCodigoGrupo,
                    aPerguntasRespostas   : viewAvaliacao.getDados(iCodigoGrupo)
                },
                function(oResponse, lErro){
                    if (!iCodigoGrupo || lErro) {
                        alert(oResponse.mensagem);
                    }
                    if (lErro) {
                        return ;
                    }
                    viewAvaliacao.avancarGrupo();
                }
            ).setMessage('Salvando dados...').execute();
            return true;
        }

        function removeEventoBotoes() {
            $('salvar').stopObserving('click');
            $('proximo').stopObserving('click');
            $('anterior').stopObserving('click');
        }

        function confirmaSaida (sMensagem) {
            if(typeof sMensagem == 'undefined' || sMensagem == null || sMensagem == false) {
                sMensagem = 'Você está saindo do cadastro do e-social.\nAntes de sair, salve seus dados.';
            }

            if (!confirm(sMensagem)) {
                return false;
            }
            return true;
        }

        var controlarBotoes = function(event) {

            var status = this.getStatus();

            $('proximo').disabled  = true;
            $('anterior').disabled = true;
            $('salvar').disabled   = true;

            if (status.grupoPosterior) {
                $('proximo').disabled = false;
            }

            if (status.grupoAnterior) {
                $('anterior').disabled = false;
            }

            if (status.grupoAtual) {
                $('salvar').disabled = false;
            }
        };

        // TODO Envio
        // Verificar quando implementarem a funcao de envio do arquivo da lotacao
        // se o exec terá esse nome e os parametros do exec "Nivelar"; 
        // $('envioESocial').addEventListener('click', function(){

        //     var parametros = {'exec': 'agendarLotacaoTributaria',  'cgm': $F('cgm')};
        //     new AjaxRequest('eso4_esocialapi.RPC.php', parametros, function(retorno) {

        //         alert(retorno.sMessage);
        //         if (retorno.erro) {
        //             return false;
        //         }
        //     }).setMessage('Agendando envio para o eSocial').execute();
        // });
        // FIM TODO Envio
    </script>
    <?php
    db_menu();
    if (!empty($sMsg)) {
        db_msgbox($sMsg);
    }
    ?>
</body>
