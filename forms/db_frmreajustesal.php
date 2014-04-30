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

$clrotulo = new rotulocampo;
$clrotulo->label("rh02_salari");
?>
<form name="form1" method="post" action="">
<center>
  <table>
    <?
    $clformulario_rel_pes = new cl_formulario_rel_pes;
    $clformulario_rel_pes->tipresumo = "Tipo";
    $clformulario_rel_pes->lo1nome = "lotini";
    $clformulario_rel_pes->lo2nome = "lotfim";
    $clformulario_rel_pes->lo3nome = "sellotac";
    $clformulario_rel_pes->re1nome = "matini";
    $clformulario_rel_pes->re2nome = "matfim";
    $clformulario_rel_pes->re3nome = "selmatri";
    $clformulario_rel_pes->trenome = "tipres";
    $clformulario_rel_pes->tfinome = "tipfil";
    $clformulario_rel_pes->ca1nome = "carini";
    $clformulario_rel_pes->ca2nome = "carfim";  
    $clformulario_rel_pes->ca3nome = "selcargo"; 
    $clformulario_rel_pes->ca4nome = "Cargo";  
    $clformulario_rel_pes->onchpad = true;
    $clformulario_rel_pes->usaregi = true;
    $clformulario_rel_pes->usalota = true;
    $clformulario_rel_pes->usacarg = true;    
    $clformulario_rel_pes->tipores = true;
    $clformulario_rel_pes->strngtipores = "glmc";
    $clformulario_rel_pes->desabam = true;
    $clformulario_rel_pes->testarescisaoregi = "r";
    $clformulario_rel_pes->gera_form(db_anofolha(),db_mesfolha());
    ?>
    <tr>
      <td align='right'><b>Lançamento:</b></td>
      <td align='left'>
        <?
        if(!isset($lancar)){
          $lancar = "a";
        }
        $arr_forma = Array('m'=>'Manual','a'=>'Automático');
        db_select("lancar",$arr_forma,true,1,"onchange='js_trancacampos(this.value);'");
        ?>
      </td>
    </tr>
    <tr>
      <td align='right'><b>Para:</b></td>
      <td align='left'>
        <?
        if(!isset($para)){
          $para = "s";
        }
        $arr_para = Array('t'=>'Todos','s'=>'Funcionários com salário');
        db_select("para",$arr_para,true,1);
        ?>
      </td>
    </tr>
    <tr>
      <td align='right'><b>Percentual:</b></td>
      <td align='left'>
        <?
        db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "", 'perce');
        ?>
      </td>
    </tr>
  </table>
<input name="incluir" type="submit" id="db_opcao" value="" onblur="js_setfocus(true);" onclick="return js_enviar();">
</center>
</form>
<script>
function js_trancacampos(valor){
  if(valor == "a"){
    document.form1.para.disabled = true;
    document.form1.perce.readOnly = false;
    document.form1.perce.style.backgroundColor = '';
    document.form1.incluir.value = "Processar";
    js_tabulacaoforms("form1","perce",true,1,"perce",true);
  }else{
    document.form1.para.disabled = false;
    document.form1.perce.value    = '';
    document.form1.perce.readOnly = true;
    document.form1.perce.style.backgroundColor = '#DEB887';
    document.form1.incluir.value = "Enviar dados";
    js_tabulacaoforms("form1","para",true,1,"para",true);
  }
}
function js_enviar(){
  if(document.form1.selmatri){
    for(var i=0; i<document.form1.selmatri.length; i++){
      document.form1.selmatri.options[i].selected = true;
    }
  }else if(document.form1.sellotac){
    for(var i=0; i<document.form1.sellotac.length; i++){
      document.form1.sellotac.options[i].selected = true;
    }
  }else if(document.form1.selcargo){
	  for(var i=0; i<document.form1.selcargo.length; i++) {
	    document.form1.selcargo.options[i].selected = true;
	  }
	}

  if(document.form1.lancar.value != "a"){
    document.form1.action = "pes1_reajustesal002.php";
  }
  return true;
}
function js_setfocus(acao){
  if(document.form1.matini){
    js_tabulacaoforms("form1","matini",acao,1,"matini",acao);
  }else if(document.form1.lotini){
    js_tabulacaoforms("form1","lotini",acao,1,"lotini",acao);
  }else if(document.form1.rh01_regist){
    js_tabulacaoforms("form1","rh01_regist",acao,1,"rh01_regist",acao);
  }else if(document.form1.r70_estrut){
    js_tabulacaoforms("form1","r70_estrut",acao,1,"r70_estrut",acao);
  }else if(document.form1.tipfil){
    js_tabulacaoforms("form1","tipfil",acao,1,"tipfil",acao);
  }else{
    js_tabulacaoforms("form1","tipres",acao,1,"tipres",acao);
  }
}
js_trancacampos("<?=$lancar?>");
</script>