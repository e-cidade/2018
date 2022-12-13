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
    $clformulario_rel_pes->tfinome = "tipfil";
    $clformulario_rel_pes->onchpad = true;
    $clformulario_rel_pes->usaregi = true;
    $clformulario_rel_pes->usalota = true;
    $clformulario_rel_pes->tipores = true;
    $clformulario_rel_pes->strngtipores = "gl";
    $clformulario_rel_pes->desabam = true;
    $clformulario_rel_pes->gera_form(db_anofolha(),db_mesfolha());
    ?>
    <tr>
      <td align='right'><b>Reajuste:</b></td>
      <td align='left'>
        <?
        if(!isset($lancar)){
          $lancar = "p";
        }
        $arr_forma = Array('p'=>'Reajusta padrões','f'=>'Atualizar fórmulas');
        db_select("lancar",$arr_forma,true,1,"onchange='js_desabcampos(this.value);'");
        ?>
      </td>
    </tr>
    <tr>
      <td align='right'><b>% reajuste:</b></td>
      <td align='left'>
        <?
        db_input('rh02_salari',10, 4, true, 'text', 1, "");
        ?>
      </td>
    </tr>
  </table>
<input name="incluir" type="submit" id="db_opcao" value="Processar dados" onblur="js_setfocus(true);" onclick="return js_enviar();">
</center>
</form>
<script>
function js_desabcampos(valor){
  if(valor == "p"){
    document.form1.rh02_salari.readOnly = false;
    document.form1.rh02_salari.style.backgroundColor = "";
  }else{
    document.form1.rh02_salari.value = "";
    document.form1.rh02_salari.readOnly = true;
    document.form1.rh02_salari.style.backgroundColor = "#DEB887";
  }
  js_setfocus(false);
}
function js_enviar(){
  retorno = true;
  if(document.form1.lancar.value == "p"){
    if(document.form1.rh02_salari.value == ""){
      alert("Informe o percentual de reajuste!");
      document.form1.rh02_salari.focus();
      retorno = false;
    }
  }else if(document.form1.selmatri){
    js_seleciona_combo(document.form1.selmatri);
  }else if(document.form1.sellotac){
    js_seleciona_combo(document.form1.sellotac);
  }
  return retorno;
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
js_desabcampos(document.form1.lancar.value);
</script>