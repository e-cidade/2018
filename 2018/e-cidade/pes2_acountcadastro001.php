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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_db_usuarios_classe.php");

$clusuarios     = new cl_db_usuarios;
$rotulocampo    = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");

$dtoper     = '';
$dtoper_dia = '';
$dtoper_mes = '';
$dtoper_ano = '';

$dtoper1_dia = '';
$dtoper1_mes = '';
$dtoper1_ano = '';

$dtoper2_dia = '';
$dtoper2_mes = '';
$dtoper2_ano = '';

?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script>
    /**
     *  Emite o relatório
     *  @returns void
     */
    function js_relatorio() {

      var F = document.form1;
      var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
      var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
      selecionados = "";
      virgula_ssel = "";
      for(var i=0; i<document.form1.sselecionados.length; i++){
        selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
        virgula_ssel = ",";
      }

      $('colunas').setValue(selecionados);
      $('ordem').setValue(F.ordem.value);
      $('tipo_alt').setValue(F.tipo.value);
      $('dataini').setValue(datai);
      $('datafin').setValue(dataf);

      jan = window.open('','__relatorio','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);

      F.submit();
    }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <form name="form1" method="post" action="pes2_acountcadastro002.php" target="__relatorio" >
        <fieldset>
          <legend>Account do Cadastro de Servidores</legend>
          <table class="form-container">
            <tr>
              <td>Alterações Entre:</td>
              <td>
                <?php
                db_inputdata('datai',$dtoper_dia,$dtoper_mes,$dtoper_ano,true,'text',1);
                ?>
                e
                <?php
                db_inputdata('dataf',$dtoper_dia,$dtoper_mes,$dtoper_ano,true,'text',1);
                ?>
              </td>
            </tr>
            <tr >
              <td>Ordem</td>
              <td>
                <?php
                $arr_ordem = array("a"=>"Alfabetica","n"=>"Numerica","d"=>"Data/Hora");
                db_select('ordem',$arr_ordem,true,4,"");
                ?>
              </td>
            </tr>
            <tr >
              <td>Tipo:</td>
              <td>
                <?php

                $arr_tipo = array(
                  "t"=>"Tudo",
                  "a"=>"Alteracoes",
                  "i"=>"Inclusoes",
                  "e"=>"Exclusoes"
                );
                db_select('tipo',$arr_tipo,true,4,"");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <Legend>Selecione os Usuarios</Legend>
                  <?php

                  db_input("valor", 3, 0, true, 'hidden', 3);
                  db_input("colunas_sselecionados", 3, 0, true, 'hidden', 3);
                  db_input("colunas_nselecionados", 3, 0, true, 'hidden', 3);

                  /**
                   *  Dados que serão enviados por post para que não ocorra estouro de URL
                   */
                  db_input("colunas", 40, 0, true, 'hidden', 3);
                  db_input("ordem",   40, 0, true, 'hidden', 3);
                  db_input("tipo_alt",40, 0, true, 'hidden', 3);
                  db_input("dataini", 40, 0, true, 'hidden', 3);
                  db_input("datafin", 40, 0, true, 'hidden', 3);


                  if(!isset($result_usuarios)){
                    $result_usuarios = $clusuarios->sql_record($clusuarios->sql_query_file(null, "id_usuario, id_usuario||'-'||upper(nome) as rh30_descr", "nome"  ));
                    for($x=0; $x<$clusuarios->numrows; $x++){
                      db_fieldsmemory($result_usuarios,$x);
                      $arr_colunas[$id_usuario]= $rh30_descr;
                    }
                  }
                  $arr_colunas_final   = Array();
                  $arr_colunas_inicial = Array();

                  if(isset($colunas_sselecionados) && $colunas_sselecionados != ""){
                    $colunas_sselecionados = split(",",$colunas_sselecionados);
                    for($Ic=0;$Ic < count($colunas_sselecionados);$Ic++){
                      $arr_colunas_final[$colunas_sselecionados[$Ic]] = $arr_colunas[$colunas_sselecionados[$Ic]];
                    }
                  }

                  if(isset($colunas_nselecionados) && $colunas_nselecionados != ""){
                    $colunas_nselecionados = split(",",$colunas_nselecionados);
                    for($Ic=0;$Ic < count($colunas_nselecionados);$Ic++){
                      $arr_colunas_inicial[$colunas_nselecionados[$Ic]] = $arr_colunas[$colunas_nselecionados[$Ic]];
                    }
                  }

                  if(!isset($colunas_sselecionados) || !isset($colunas_sselecionados) || $colunas_sselecionados == ""){
                    $arr_colunas_final  = Array();
                    $arr_colunas_inicial = $arr_colunas;
                  }
                  db_multiploselect("id_usuario","rh30_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true, "js_complementar('c');");
                  ?>
                </fieldset>

              </td>
            </tr>
          </table>
        </fieldset>
        <input name="Imprimir" type="button" id="imprimir" onClick="js_relatorio()" value="Imprimir">
      </form>
    </div>
    <?php db_menu();?>
  </body>
</html>
