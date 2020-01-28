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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_folha_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_POST);

$clfolha   = new cl_folha;
$clselecao = new cl_selecao;
$clgerfsal = new cl_gerfsal;
$clgerfadi = new cl_gerfadi;
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfcom = new cl_gerfcom;
$clgerffx  = new cl_gerffx;

$clrotulo  = new rotulocampo;
$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');
$clrotulo->label('DBtxt23');
$db_opcao = 1;
$db_botao = true;
$geraform = new cl_formulario_rel_pes;

$ano      = db_anofolha();
$anofolha = db_anofolha();
$mesfolha = db_mesfolha();

$geraform->selecao   = true;                    // CAMPO PARA ESCOLHA DA SELEÇÃO
$geraform->manomes  = false;
$geraform->usarubr = true;
$geraform->selrubr = true;
$geraform->onchpad = true;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      #fieldset_selrubri {
        width: 100%;
      }

      #selrubri {
         width: 540px !important;
      }

      #rh27_descr {
        width: 409px;
      }
      #r44_descr {
        width: 408px;
      }
    </style>
  </head>
  <body>
    <form name="form1" method="post" action="" class="container">
      <fieldset>
        <legend>Relatório de Rubricas Anuais</legend>
        <?php
          db_input('anofolha',4,$IDBtxt23,true,'hidden',2,'');
          db_input('mesfolha',4,$IDBtxt23,true,'hidden',2,'');
        ?>

        <table class="form-container" width="700px">
          <tr>
            <td width='100px'>
              Tipo:
            </td>
            <td>
              <?
              $arr_tipo = array("v" => "Valor", "q" => "Quantidade");
              db_select("tipo", $arr_tipo, true, 1, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>Ano:</td>
            <td>
              <?php db_input('ano',4,$IDBtxt23,true,'text',2,''); ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="2">
              <?
              db_input("folhaselecion", 3, 0, true, 'hidden', 3);
              $arr_pontosgerfs_inicial = array();
              $arr_pontosgerfs_final   = array();
              $arr_pontos              = array(
                "1" =>"Salário",
                "2" =>"Adiantamento",
                "3" =>"Complementar",
                "4" =>"Rescisão",
                "5" =>"Saldo do 13o",
                "6" =>"Cálculo Fixo"
              );

              if(isset($objeto1)){
                foreach ($objeto1 as $index) {
                  $arr_pontosgerfs_inicial[$index] = $arr_pontos[$index];
                }
              }else{
                $arr_pontosgerfs_inicial = $arr_pontos;
              }
              if(isset($objeto2)){
                foreach ($objeto2 as $index) {
                  $arr_pontosgerfs_final[$index] = $arr_pontos[$index];
                }
              }
              db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 6, 250, "", "", true);
              ?>
            </td>
          </tr>
          <?php $geraform->gera_form($anofolha,$mesfolha); ?>
        </table>
      </fieldset>
      <input name="incluir" type="button" id="db_opcao" onclick="js_enviardados();" value="Gerar">
    </form>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
  function js_enviardados(){

    if(document.form1.anofolha.value == ""){
      alert("Informe o ano a ser pesquisado.");
      document.form1.anofolha.focus();
  }else if(document.form1.mesfolha.value == ""){
    alert("Informe o mês a ser pesquisado.");
    document.form1.mesfolha.focus();
  }else{

    stringretorno = "?anofolha=" + document.form1.anofolha.value;
    stringretorno+= "&mesfolha=" + document.form1.mesfolha.value;
    stringretorno+= "&ano=" + document.form1.ano.value;
    stringretorno+= "&sel="+document.form1.selecao.value;
    stringretorno+= "&tipo="+document.form1.tipo.value;

    stringretorno+= "&ponts=";
    virstrretorno = "";
    for(i=0;i<document.form1.objeto2.length;i++){
      stringretorno+= virstrretorno+document.form1.objeto2.options[i].value;
      virstrretorno = ",";
    }

    stringretorno+= "&rubrs=";
    virstrretorno = "";
    for(i=0;i<document.form1.selrubri.length;i++){
      stringretorno+= virstrretorno+document.form1.selrubri.options[i].value;
      virstrretorno = ",";
    }


    jan = window.open('pes2_rubricasanuais002.php' + stringretorno,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);

  }
}
</script>
