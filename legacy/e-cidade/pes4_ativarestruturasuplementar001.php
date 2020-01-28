<?php
  /*
   *     E-cidade Software Publico para Gestao Municipal
   *  Copyright (C) 2014  DBselller Servicos de Informatica
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
  require_once("libs/db_stdlib.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");

  require_once ("classes/db_cfpess_classe.php");

  $clcfpess  = new cl_cfpess;
  $db_opcao  = 2;
  $db_botao  = true;

  $sCampos = "r11_suplementar                                                                                                                                        ";

  $sSql     = $clcfpess->sql_query(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),$sCampos);
  $result   = $clcfpess->sql_record($sSql);

  if($result != false && $clcfpess->numrows > 0){
    db_fieldsmemory($result,0);
  }
  $clcfpess->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("db77_descr");
  $clrotulo->label("i01_descr");
  $clrotulo->label("rh32_descr");
  $clrotulo->label("c50_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <fieldset class="ativacao-parametro-suplementar">
        <legend>Ativação da Estrutura Suplementar</legend>
        <div>
          <table class="suplementar" style="width: 100%">
            <tr>
              <td colspan="2">
                <?php if ( !isset($r11_suplementar) || ($r11_suplementar == 'f' || $r11_suplementar === false || $r11_suplementar == '0' ) ): ?>
                    <center class="aviso-suplementar"  style="background-color: rgb(255, 255, 255); padding: 5px 10px; font-weight: bold;">Atenção! Ao ativar a estrutura suplementar, o procedimento não poderá ser desfeito.<br>A implantação será feita na próxima inicialização do ponto, caso não exista eventos financeiros.</center>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="left" title="<?php echo $Tr11_suplementar; ?>">
                <?php 
                  if ( isset($r11_suplementar) && ($r11_suplementar == 't' || $r11_suplementar === true || $r11_suplementar == '1' ) ){
                    echo $Lr11_suplementar;
                  }
                 ?>
              </td>
              <td>
                <?php if ( isset($r11_suplementar) && ($r11_suplementar == 't' || $r11_suplementar === true || $r11_suplementar == '1' ) ): ?>
                  <input type="text" value="Ativada" readonly="" size="28" title="<?php echo $Tr11_suplementar; ?>"style="background-color:#DEB887;" >
                <?php else: ?>
                  <center>
                    <input type="button" onclick="js_ativarsuplementar(true, '<?php echo db_getsession("DB_instit")?>');" value="Ativar Suplementar" />
                  </center>
                <?php endif; ?>
              </td>
            </tr>
          </table>
        </div>
      </fieldset>
    </div>  
   

    <script type="text/javascript">

      (function(){
      })();

      /**
       * Processa os dados do forumalario, atraves do RPC da tela;
       * @return void
       */
      function js_ativarsuplementar(lAtivar, iInstituicao) {

        if(lAtivar){
          sMsgAtivacao = "Deseja realmente ativar a estrutura suplementar?\nOBS.: Após finalizada, a nova estrutura não poderá ser desfeita.";
        }

        if ( confirm(sMsgAtivacao) ){

          var sUrl                = 'pes1_cfpessRPC.php';
              
          var oParam              = new Object();
              oParam.exec         = 'ativarSuplementar';
              oParam.iInstituicao = iInstituicao;
              oParam.lAtivar      = lAtivar;

          new AjaxRequest(sUrl, oParam, function(oRetorno, erro) {

            alert( oRetorno.sMessage.urlDecode() );
            
            if (!oRetorno.erro)
              location.href = location.href;
            
          }).execute();
        }
      }
    </script>
    <?php db_menu(); ?>
  </body>
</html>