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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_liclicitemlote_classe.php"));
require_once(modification("classes/db_liclicita_classe.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clliclicitemlote = new cl_liclicitemlote;
$clliclicita      = new cl_liclicita;

$db_botao = false;

if (isset($licitacao)&&trim($licitacao)!=""){
     $result = $clliclicita->sql_record($clliclicita->sql_query($licitacao,"l08_altera"));
     if ($clliclicita->numrows > 0){
          db_fieldsmemory($result,0);

          if ($l08_altera == "t"){
             	 $db_botao = true;
          } else if ($l20_licsituacao == 1){
               $erro_msg = "Licitação já julgada.";
          }else{

               $erro_msg = "Licitação não pode ser alterada\\nAlteração não permitida.";

					}
     }
}

if (isset($alterar)&&trim($alterar)!=""){
     $sqlerro  = false;
     $erro_msg = "";

     db_inicio_transacao();

     $vetor = split(",", $descricao);

     $l04_liclicitem = "";
     $l04_descricao  = "";
     for($i = 0; $i < sizeof($vetor); $i++){
          $vetor_codigos  = split("_",$vetor[$i]);

          $l04_liclicitem = $vetor_codigos[0]*1;
          $l04_descricao  = $vetor_codigos[1];

          if (trim($l04_descricao) == "AUTO"){
            $l04_descricao .= "_".$vetor_codigos[2]."_".$vetor_codigos[3];
          }

          if (trim($l04_descricao)=="0"){
               $l04_descricao = "";
          }

          $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_file(null,"*","l04_liclicitem","l04_liclicitem = $l04_liclicitem"));
          if ($clliclicitemlote->numrows > 0){
               $clliclicitemlote->excluir(null,"l04_liclicitem = $l04_liclicitem");
               if ($clliclicitemlote->erro_status == 0){
                    $sqlerro  = true;
                    $erro_msg = $clliclicitemlote->erro_msg;
                    break;
               }
          }

          if (strlen($l04_descricao) > 0){
               $clliclicitemlote->l04_liclicitem = $l04_liclicitem;
               $clliclicitemlote->l04_descricao  = $l04_descricao;

               $clliclicitemlote->incluir(null);
               if ($clliclicitemlote->erro_status == 0){
                    $sqlerro  = true;
                    $erro_msg = $clliclicitemlote->erro_msg;
                    break;
               } else {
                    $erro_msg = $clliclicitemlote->erro_msg;
               }
          }
     }

     db_fim_transacao($sqlerro);
}

if (!isset($selecionado)&&trim(@$selecionado)==""){
     $selecionado = 0;
}

?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" action="lic1_liclicitemlote001.php" method="post">
<?
    db_input("licitacao",10,"",true,"hidden",3);
    db_input("selecionado",1,"",true,"hidden",3);
    db_input("descricao",500,"",true,"hidden",3);
?>
<center>
<table border="0" cellspacing="0" cellpadding="5" width="80%" align="center">
  <tr colspan=2>
    <td align="center"><iframe name="itens_lote" id="itens_lote" src="lic1_liclicitemlote011.php?licitacao=<?=@$licitacao?>" width="1200" height="300" marginwidth="0" marginheight="0" frameborder="1">
	</iframe></td>
  </tr>
  <tr><td colspan="2"></td></tr>
  <tr align="center">
    <td nowrap>
      <input type="submit" name="alterar" value="Alterar" title="Altera itens do lote" onClick="js_alterar();" <?=($db_botao==false?'disabled':'')?>>
      <input type="button" name="novo"    value="Novo"    title="Novo lote" onClick="js_novo();" <?=($db_botao==false?'disabled':'')?>>
      <input type="button" name="excluir" value="Excluir" title="Excluir lote" onClick="js_excluir();" <?=($db_botao==false?'disabled':'')?>>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<script>
   function js_habilitar(valor){
       document.form1.alterar.disabled = valor;
   }

   function js_selecionado(){
<?
       if (!empty($l08_altera) && $l08_altera == "t"){
?>
       js_habilitar(false);
<?
      } else {
?>
       js_habilitar(true);
<?
      }
?>
   }

   function js_alterar(){
       var tam         = itens_lote.document.form1.elements.length;
       var lista_itens = new String("");
       var separador   = "";

       for(i = 0; i < tam; i++){
            if (itens_lote.document.form1.elements[i].type == "select-one"){
                 valor        = new String(itens_lote.document.form1.elements[i].value);
                 lista_itens += separador+valor;
                 separador    = ", ";
            }
       }

       if (lista_itens.length > 0){
            document.form1.selecionado.value = 0;
            document.form1.descricao.value   = lista_itens;
            document.form1.submit();
       }
   }

   function js_novo(){
       var licitacao = itens_lote.document.form1.licitacao.value;

       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liclicitemlote','db_iframe_lotenovo','lic1_liclicitemlotenovo.php?licitacao='+licitacao,'Novo Lote',true,"0");
   }

   function js_excluir(){
       var licitacao = itens_lote.document.form1.licitacao.value;

       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liclicitemlote','db_iframe_loteexcluir','lic1_liclicitemlote003.php?licitacao='+licitacao,'Excluir Lote',true,"0");
   }
</script>
<?
  if ($selecionado==0){
       echo "<script>js_habilitar(true);</script>";
  }

  if (isset($erro_msg)&&trim($erro_msg)!=""){
       db_msgbox($erro_msg);
  }
?>