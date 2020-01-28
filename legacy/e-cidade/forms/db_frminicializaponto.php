<?
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
?>
<form name="form1" method="post" action="">
  <center>
    <table>

        <?php

          if(!isset($rubini)){
             $rubini = "0001";
          }

          if(!isset($rubfim)){
             $rubfim = "R999";
          }

          $clformulario_rel_pes = new cl_formulario_rel_pes;
          $clformulario_rel_pes->ru1nome = "rubini";
          $clformulario_rel_pes->ru2nome = "rubfim";
          $clformulario_rel_pes->lo1nome = "lotini";
          $clformulario_rel_pes->lo2nome = "lotfim";
          $clformulario_rel_pes->re1nome = "matini";
          $clformulario_rel_pes->re2nome = "matfim";
          $clformulario_rel_pes->tfonome = "opcao";
          $clformulario_rel_pes->trenome = "glm";
          $clformulario_rel_pes->onchpad = true;
          $clformulario_rel_pes->usaregi = true;
          $clformulario_rel_pes->usalota = true;
          $clformulario_rel_pes->usarubr = true;
          $clformulario_rel_pes->intrubr = true;
          $clformulario_rel_pes->tipofol = true;
          $clformulario_rel_pes->tipores = true;
          $clformulario_rel_pes->strngtipores = "glm";
          $clformulario_rel_pes->arr_tipofol = Array("1"=>"Salário","2"=>"Adiantamento");
          $clformulario_rel_pes->desabam = true;
          $clformulario_rel_pes->testarescisaoregi = "r";
          $clformulario_rel_pes->gera_form(db_anofolha(),db_mesfolha());
        ?>

     </table>
    <input name="incluir" type="submit" id="db_opcao" value="Inicializar ponto" onblur="js_setfocus(2);" onclick="return js_enviar();">

    <?php if (count($aConfiguracoesAutomaticas) > 0 ) { ?>

      <fieldset class="form-container">
        <div id="oLancamentos"></div>

        <?php foreach ($aConfiguracoesAutomaticas as $oConfiguracaoAutomatica) { ?>

          <table cellpadding="0" cellspacing="0" width="400px" class="lancamento">
            <tr>
              <td><b>Descrição: </b><?=$oConfiguracaoAutomatica->getDescricao(); ?></td>
            </tr>
            <tr>
              <td><b>Seleção: </b><?=$oConfiguracaoAutomatica->getSelecao()->getDescricao(); ?></td>
            </tr>
            <tr>
              <td><b>Rubrica: </b><?=$oConfiguracaoAutomatica->getRubrica()->getCodigo()." - ".$oConfiguracaoAutomatica->getRubrica()->getDescricao(); ?></td>
            </tr>
          </table>

        <?php } ?>
      </fieldset>
    <?php } ?>
  </center>
</form>

<style type="text/css">

  #oLancamentos {
    margin-bottom: 10px;
  }

  .form-container {
    margin-top: 20px;
  }

  .lancamento {
    margin-bottom: 5px;
  }

  .lancamento tr {
    background-color: #fcf8e3; 
    border: 1px solid #fcc888; 
  }

  .lancamento tr td{
    padding: 0 10px;
  }

  fieldset{
    width: 400px;
  }
  
</style>

<script>


(function() {

  if ($('oLancamentos') !== null){

    var oMessageBoard = new DBMessageBoard('msgboard1','Lançamentos Automáticos','Nesta competência serão lançados os seguintes eventos financeiros:',$('oLancamentos'));
        oMessageBoard.show();
  }
})();

function js_enviar(){
  if(document.form1.rubini.value == "" || document.form1.rubfim.value == ""){
    alert("Informe uma faixa de rubricas.");
  }else{
    virgula = "";
    if(document.form1.selregist){
      for(i=0; i<document.form1.selregist.length; i++){
	document.form1.campo_auxilio_regi.value+= virgula+document.form1.selregist.options[i].value;
	virgula = ",";
      }
    }else if(document.form1.sellotac){
      for(i=0; i<document.form1.sellotac.length; i++){
        document.form1.campo_auxilio_lota.value+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
	virgula = ",";
      }
    }
    document.form1.action = "pes4_inicializaponto002.php";
    return true;
  }
  return false;
}
function js_setfocus(opcao){
  if(document.form1.matini){
    js_tabulacaoforms("form1","matini",true,1,"matini",true);
  }else if(document.form1.lotini){
    js_tabulacaoforms("form1","lotini",true,1,"lotini",true);
  }else if(document.form1.rh01_regist && document.form1.rubini.value != "" && opcao == 1){
    js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
  }else if(document.form1.r70_estrut && document.form1.rubini.value != "" && opcao == 1){
    js_tabulacaoforms("form1","r70_estrut",true,1,"r70_estrut",true);
  }else{
    js_tabulacaoforms("form1","rubini",true,1,"rubini",true);
  }
}
</script>