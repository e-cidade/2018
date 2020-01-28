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

//MODULO: Caixa
$cltabplansaldorecurso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k02_anousu");
$clrotulo->label("k02_anousu");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("k02_anousu");
$clrotulo->label("k02_anousu");
?>
<form name="form1" method="post" action="">
<center>
  <table>
    <tr>
      <td>
        <fieldset>
        <legend><b>Saldos Por Recurso</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tk111_sequencial?>">
               <?=@$Lk111_sequencial?>
            </td>
            <td> 
              <?
              db_input('k111_sequencial',10,$Ik111_sequencial,true,'text', 3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_tabplan?>">
               <?
               db_ancora(@$Lk111_tabplan,"js_pesquisak111_tabplan(true);",3);
               ?>
            </td>
            <td> 
              <?
              db_input('k111_tabplan',10,$Ik111_tabplan,true,'text',3," onchange='js_pesquisak111_tabplan(false);'")
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_recurso?>">
               <?
               db_ancora(@$Lk111_recurso,"js_pesquisak111_recurso(true);",$db_opcao);
               ?>
            </td>
            <td> 
              <?
              db_input('k111_recurso',10,$Ik111_recurso,true,'text',$db_opcao," onchange='js_pesquisak111_recurso(false);'");
              db_input('o15_descr',20,$Io15_descr,true,'text',3,'');
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_dataimplantacao?>">
               <?=@$Lk111_dataimplantacao?>
            </td>
            <td> 
              <?
              db_inputdata('k111_dataimplantacao',@$k111_dataimplantacao_dia,
                           @$k111_dataimplantacao_mes,@$k111_dataimplantacao_ano,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_creditoinicial?>">
               <?=@$Lk111_creditoinicial?>
            </td>
            <td> 
            <?
            db_input('k111_creditoinicial',10,$Ik111_creditoinicial,true,'text',$db_opcao,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_debitoinicial?>">
               <?=@$Lk111_debitoinicial?>
            </td>
            <td> 
              <?
              db_input('k111_debitoinicial',10,$Ik111_debitoinicial,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_dataatualizacao?>">
               <?=@$Lk111_dataatualizacao?>
            </td>
            <td> 
            <?
            db_inputdata('k111_dataatualizacao',@$k111_dataatualizacao_dia,
                                               @$k111_dataatualizacao_mes,@$k111_dataatualizacao_ano,true,'text',3);
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_creditoatualizado?>">
               <?=@$Lk111_creditoatualizado?>
            </td>
            <td> 
            <?
            db_input('k111_creditoatualizado',10,$Ik111_creditoatualizado,true,'text', 3,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk111_debitoatualizado?>">
               <?=@$Lk111_debitoatualizado?>
            </td>
            <td> 
            <?
            db_input('k111_debitoatualizado',10,$Ik111_debitoatualizado,true,'text', 3,"")
            ?>
            </td>
          </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>  
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <?
  if($db_opcao != 1){
  ?>
  <input name="novo" type="button" id="novo" value="Novo"
         onclick="location.href='cai4_tabplansaldos001.php?k111_tabplan=<?=$k111_tabplan?>'">
  <?
  }
  ?>
  <table>
    <tr>
      <td valign="top"  align="center">  
        <?
        include("dbforms/db_classesgenericas.php");
        $cliframe_alterar_excluir = new cl_iframe_alterar_excluir; 
        $dbwhere = " k111_tabplan = ".$k111_tabplan;
        if (isset($k111_sequencial) && trim($k111_sequencial) != ""){
          $dbwhere .= " and k111_sequencial <> ".$k111_sequencial;
        }
        $sql = $cltabplansaldorecurso->sql_query(null, 
                                                 "tabplansaldorecurso.*,o15_codigo, o15_descr",
                                                 "k111_sequencial",
                                                 $dbwhere);
        $chavepri= array("k111_sequencial"=>@$k111_sequencial);
        $cliframe_alterar_excluir->chavepri=$chavepri;
        $cliframe_alterar_excluir->sql     = $sql;
        $cliframe_alterar_excluir->campos  ="k111_sequencial, k111_creditoinicial, k111_debitoinicial,o15_codigo,o15_descr";
        $cliframe_alterar_excluir->legenda ="Saldos Lançados";
        $cliframe_alterar_excluir->iframe_height = "160";
        $cliframe_alterar_excluir->iframe_width  = "700";
        $cliframe_alterar_excluir->opcoes        = 2;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
</center>
</form>
<script>
function js_pesquisak111_tabplan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?funcao_js=parent.js_mostratabplan1|k02_anousu|k02_anousu','Pesquisa',true);
  }else{
     if(document.form1.k111_tabplan.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?pesquisa_chave='+document.form1.k111_tabplan.value+'&funcao_js=parent.js_mostratabplan','Pesquisa',false);
     }else{
       document.form1.k02_anousu.value = ''; 
     }
  }
}
function js_mostratabplan(chave,erro){
  document.form1.k02_anousu.value = chave; 
  if(erro==true){ 
    document.form1.k111_tabplan.focus(); 
    document.form1.k111_tabplan.value = ''; 
  }
}
function js_mostratabplan1(chave1,chave2){
  document.form1.k111_tabplan.value = chave1;
  document.form1.k02_anousu.value = chave2;
  db_iframe_tabplan.hide();
}
function js_pesquisak111_tabplan(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?funcao_js=parent.js_mostratabplan1|k02_codigo|k02_anousu','Pesquisa',true);
  }else{
     if(document.form1.k111_tabplan.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?pesquisa_chave='+document.form1.k111_tabplan.value+'&funcao_js=parent.js_mostratabplan','Pesquisa',false);
     }else{
       document.form1.k02_anousu.value = ''; 
     }
  }
}
function js_mostratabplan(chave,erro){
  document.form1.k02_anousu.value = chave; 
  if(erro==true){ 
    document.form1.k111_tabplan.focus(); 
    document.form1.k111_tabplan.value = ''; 
  }
}
function js_mostratabplan1(chave1,chave2){
  document.form1.k111_tabplan.value = chave1;
  document.form1.k02_anousu.value = chave2;
  db_iframe_tabplan.hide();
}
function js_pesquisak111_recurso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
     if(document.form1.k111_recurso.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.k111_recurso.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }else{
       document.form1.o15_descr.value = ''; 
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.k111_recurso.focus(); 
    document.form1.k111_recurso.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.k111_recurso.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisak111_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?funcao_js=parent.js_mostratabplan1|k02_anousu|k02_anousu','Pesquisa',true);
  }else{
     if(document.form1.k111_anousu.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?pesquisa_chave='+document.form1.k111_anousu.value+'&funcao_js=parent.js_mostratabplan','Pesquisa',false);
     }else{
       document.form1.k02_anousu.value = ''; 
     }
  }
}
function js_mostratabplan(chave,erro){
  document.form1.k02_anousu.value = chave; 
  if(erro==true){ 
    document.form1.k111_anousu.focus(); 
    document.form1.k111_anousu.value = ''; 
  }
}
function js_mostratabplan1(chave1,chave2){
  document.form1.k111_anousu.value = chave1;
  document.form1.k02_anousu.value = chave2;
  db_iframe_tabplan.hide();
}
function js_pesquisak111_anousu(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?funcao_js=parent.js_mostratabplan1|k02_codigo|k02_anousu','Pesquisa',true);
  }else{
     if(document.form1.k111_anousu.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabplan','func_tabplan.php?pesquisa_chave='+document.form1.k111_anousu.value+'&funcao_js=parent.js_mostratabplan','Pesquisa',false);
     }else{
       document.form1.k02_anousu.value = ''; 
     }
  }
}
function js_mostratabplan(chave,erro){
  document.form1.k02_anousu.value = chave; 
  if(erro==true){ 
    document.form1.k111_anousu.focus(); 
    document.form1.k111_anousu.value = ''; 
  }
}
function js_mostratabplan1(chave1,chave2){
  document.form1.k111_anousu.value = chave1;
  document.form1.k02_anousu.value = chave2;
  db_iframe_tabplan.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tabplansaldorecurso','func_tabplansaldorecurso.php?funcao_js=parent.js_preenchepesquisa|k111_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tabplansaldorecurso.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>