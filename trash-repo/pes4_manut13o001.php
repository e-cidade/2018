<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$anofolha = db_anofolha();
$mesfolha = db_mesfolha();
db_sel_cfpess($anofolha, $mesfolha);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post" action="">
  <tr>
    <td align="right" width="40%">
      <b>Op��o:</b>
    </td>
    <td>
      <?
      if(!isset($opcao)){
	$opcao = 0;
      }
      $arr_opcoes = Array(0=>"Selecionar op��o", 1=>"Adiantamento 13o", 2=>"Saldo 13o", 3=>"Complemento 13o");
      db_select("opcao", $arr_opcoes, true, 1, "onchange='js_opcao_selecionada();'");
      ?>
    </td>
  </tr>
  <?
  $erro_msg = "";
  $sql_erro = false;
  if(isset($opcao) && $opcao != 0){

    if($opcao == 1 || $opcao == 2){

      if($mesfolha != $r11_mes13 && $opcao == 2){
        $sql_erro = true;
        $erro_msg = $arr_opcoes[$opcao]." s� pode ser pago no m�s ".$r11_mes13." / ".$anofolha.".";
      }else if($mesfolha >= $r11_mes13 && $opcao == 1){
        $sql_erro = true;
        $erro_msg = $arr_opcoes[$opcao]." s� pode ser pago antes de ".$r11_mes13." / ".$anofolha.".";
      }

      $opcao_fracao = 3;
      $fracao_certa = 100;
      if($opcao == 1){
  ?>
  <tr>
    <td align="right"><b>Pagar complemento de adiantamento:</b></td>
    <td>
      <?
      $arr_adianta = Array('t'=>'Sim', 'f'=>'N�o');
      db_select("pagaradiantamentonovamente", $arr_adianta, true, 1);
      ?>
    </td>
  </tr>
  <?
        $opcao_fracao = 1;
	$fracao_certa = 50;
      }else{
      $pagaradiantamentonovamente = 't';
      db_input('pagaradiantamentonovamente',6,4,true,'hidden','');
      }
  ?>
  <tr>
    <td align="right">
      <b>Fra��o para pagamento:</b>
    </td>
    <td>
      <?
      db_input('fracao_certa',6,4,true,'text',$opcao_fracao,'');
      $mesana = 11;
      db_input('mesana',6,4,true,'hidden',3,'');
      ?>
      &nbsp;<b>%</b>
    </td>
  </tr>
  <?
      if($opcao == 2 && $mesfolha == 12){
  ?>
  <tr>
    <td align="center" colspan="2" valign="top" nowrap>
      <table border=0>
        <tr>
          <td valign='top'><input type='radio' name='informe' value='1'></td>
          <td>
            1 - se folha de pagamento de dezembro estiver concluida.
          </td>
        </tr>
        <tr>
          <td valign='top'><input type='radio' name='informe' value='2'></td>
          <td>
            2 - se folha de pagamento de dezembro n�o estiver conclu�da caso a op��o seja pelo <2>,
                ap�s o fechamento da folha dezembro, execute a rotina de complemento de 13o sal�rio.
                Esta rotina gerara 1/12 para rubricas de m�dias por quantidade e por n�mero de meses
                (referente dezembro) no ponto de sal�rio. Em  seguida,  reprocesse folha de sal�rio.
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <?
      }

    }
     
    if(!isset($opcao_gml)){
      $opcao_gml = "m";
    }
    if(!isset($opcao_filtro)){
      $opcao_filtro = "s";
    }

    include("dbforms/db_classesgenericas.php");
    $geraform = new cl_formulario_rel_pes;
    
    $geraform->manomes = false;                     // PARA N�O MOSTRAR ANO E MES DE COMPET�NCIA DA FOLHA
    
    $geraform->usaregi = true;                      // PERMITIR SELE��O DE MATR�CULAS
    $geraform->usalota = true;                      // PERMITIR SELE��O DE LOTA��ES
    
    $geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATR�CULA INICIAL
    $geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATR�CULA FINAL
        
    $geraform->lo1nome = "r110_lotaci";             // NOME DO CAMPO DA LOTA��O INICIAL
    $geraform->lo2nome = "r110_lotacf";             // NOME DO CAMPO DA LOTA��O FINAL
    
    $geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
    $geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO
    
    $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATR�CULAS SELECIONADAS
    $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTA��ES SELECIONADAS
    
    $geraform->filtropadrao = "s";                  // NOME DO DAS LOTA��ES SELECIONADAS
    $geraform->resumopadrao = "m";                  // NOME DO DAS LOTA��ES SELECIONADAS
    
    $geraform->strngtipores = "gml";                // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - Geral,
                                                    //                                       m - Matr�cula,
                                                    //                                       l - Lota��o
    $geraform->testarescisaoregi = "r";
    $geraform->onchpad      = true;                 // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
    $geraform->tipresumo    = "Sele��o";            // LABEL DA SELE��O
    $geraform->gera_form(null,null);
    ?>
  <?if($opcao == 3 && $mesfolha != $r11_mes13){?>
  <tr>
    <td colspan="2" align="center"><font color="red">AVISO: Saldo de 13o n�o est� calculado.</font></td>
  </tr>
  <?}?>
  <tr>
    <td colspan='2' align='center'>
      <input type="submit" name="processar" value="Processar" onclick="return js_enviar_dados();">
    </td>
  </tr>
  <?
  }
  ?>
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_opcao_selecionada(){
  document.form1.submit();
}
function js_enviar_dados(){
  if(document.form1.selregist){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    document.form1.faixa_regis.value = valores;
    document.form1.selregist.selected = 0;
  }else if(document.form1.sellotac){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
  }

  if(document.form1.informe){
    okSubmit = false;
    for(i=0; i<document.form1.informe.length; i++){
      if(document.form1.informe[i].checked == true){
        okSubmit = true;
        if(document.form1.informe[i].value == 1){
          document.form1.mesana.value = "12";
        }else{
          document.form1.mesana.value = "11";
        }
        break;
      }
    }

    if(okSubmit == false){
      alert("Selecione se a folha de pagamento referente ao m�s \nde dezembro j� foi conclu�da ou n�o.");
      return false;
    }
  }

  document.form1.action = 'pes4_manut13o002.php';
  return true;

}
js_trocacordeselect();
</script>
</html>
<?
if($sql_erro == true){
  db_msgbox($erro_msg);
  echo "<script>location.href = 'pes4_manut13o001.php'</script>";
}
?>