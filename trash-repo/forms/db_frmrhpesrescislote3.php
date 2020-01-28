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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpesrescisao->rotulo->label();
$clselecao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>FILTRO</b></legend>
        <table width="100%">
          <tr>
	<?
  $geraform = new cl_formulario_rel_pes;

  $geraform->manomes = false;                     // PARA N�O MOSTRAR ANO E MES DE COMPET�NCIA DA FOLHA

  $geraform->usaregi = true;                      // PERMITIR SELE��O DE MATR�CULAS
  $geraform->usalota = true;                      // PERMITIR SELE��O DE LOTA��ES

  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATR�CULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATR�CULA FINAL
  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELE��O DE MATR�CULAS

  $geraform->lo1nome = "lotai";                  // NOME DO CAMPO DA LOTA��O INICIAL
  $geraform->lo2nome = "lotaf";                  // NOME DO CAMPO DA LOTA��O FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELE��O DE LOTA��ES

  $geraform->trenome = "tipo";               // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  //$geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADR�O
  //$geraform->resumopadrao = "g";                  // TIPO DE RESUMO PADR�O

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATR�CULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTA��ES SELECIONADAS

  $geraform->strngtipores = "gml";                // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - Matr�cula,
                                                  //                                       r - Resumo
  $geraform->selecao = true;

  $geraform->onchpad      = true;                 // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
  ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="excluir" value="Excluir Rescis�es" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.r44_selec.focus();" onclick="return js_verificadados();">
    </td>
  </tr>
</table> 
<script>
function js_verificadados(){
  x = document.form1;
  if(document.form1.selreg == ''){
    alert("Informe a matr�cula do funcion�rio.");
    x.rh01_regist.focus();
  }else{
    if(document.form1.selreg){
      if(document.form1.selreg.length > 0){
        document.form1.fre.value = js_campo_recebe_valores();
      }
    }
    
    if(document.form1.sellot){
      if(document.form1.sellot.length > 0){
        document.form1.flt.value = js_campo_recebe_valores();
      }
    }
  }
  return true; 
}
js_disabdata("<?=($rh05_taviso)?>");

</script>