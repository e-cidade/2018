<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
$cltipcalc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q85_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("q92_descr");
$clrotulo->label("q89_descr");
?>
<script>
function js_troca(obj){
  if(obj=='t'){
    document.form1.q81_tippro.disabled=true;
  }else{
    document.form1.q81_tippro.disabled=false;
  }  
}
function js_hab(){
    document.form1.q81_tippro.disabled=false;
}
</script>
<form name="form1" method="post" action="">
  <center>
    <fieldset style="width:790px;margin-top:20px;">
      <legend><strong>Tipo de calculo:</strong></legend>
      <table border="0" style="width:790px;">
        <tr>
          <td nowrap title="<?=@$Tq81_codigo?>">
             <?=@$Lq81_codigo?>
          </td>
          <td> 
          <?
            db_input('q81_codigo',10,$Iq81_codigo,true,'text',3)
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_descr?>">
             <?=@$Lq81_descr?>
          </td>
          <td> 
            <?
              db_input('q81_descr',60,$Iq81_descr,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_abrev?>">
             <?=@$Lq81_abrev?>
          </td>
          <td> 
            <?
              db_input('q81_abrev',60,$Iq81_abrev,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_cadcalc?>">
             <?
               db_ancora(@$Lq81_cadcalc,"js_pesquisaq81_cadcalc(true);",$db_opcao);
             ?>
          </td>
          <td> 
            <?
               db_input('q81_cadcalc',10,$Iq81_cadcalc,true,'text',$db_opcao," onchange='js_pesquisaq81_cadcalc(false);'");
               db_input('q85_descr',47,$Iq85_descr,true,'text',3,'');
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_integr?>">
             <?=@$Lq81_integr?>
          </td>
          <td> 
              <?
                $x = array("f"=>"NAO","t"=>"SIM");
                db_select('q81_integr',$x,true,$db_opcao,"onchange='js_troca(this.value);'");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_tippro?>">
             <?=@$Lq81_tippro?>
          </td>
          <td nowrap> 
              <?
                $x = array("Q"=>"QUINZENAL","M"=>"MENSAL","S"=>"SEMESTRAL","D"=>"DIARIA","T"=>"TRIMESTRAL");
                db_select('q81_tippro',$x,true,$db_opcao,"");
                echo @$Lq81_tipo;
                $x = array("1"=>"ISSQN","2"=>"SANITÁRIO","3"=>"VISTORIA LOCALIZAÇÃO","4"=>"ALVARÁ","5"=>"TAXA", "6"=>"VISTORIA SANITÁRIO");
                db_select('q81_tipo',$x,true,$db_opcao,"");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_recexe?>">
             <?
               db_ancora(@$Lq81_recexe,"js_pesquisaq81_recexe(true);",$db_opcao);
             ?>
          </td>
          <td> 
             <?
               db_input('q81_recexe',10,$Iq81_recexe,true,'text',$db_opcao," onchange='js_pesquisaq81_recexe(false);'");
               db_input('k02_descr',47,$Ik02_descr,true,'text',3,'','k02_descrexe');
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_qiexe?>">
             <?=@$Lq81_qiexe?>
          </td>
          <td> 
            <?
              db_input('q81_qiexe',10,$Iq81_qiexe,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_qfexe?>">
             <?=@$Lq81_qfexe?>
          </td>
          <td> 
            <?
              db_input('q81_qfexe',10,$Iq81_qfexe,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_valexe?>">
             <?=@$Lq81_valexe?>
          </td>
          <td> 
            <?
              db_input('q81_valexe',10,$Iq81_valexe,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_recpro?>">
             <?
             db_ancora(@$Lq81_recpro,"js_pesquisaq81_recpro(true);",$db_opcao);
             ?>
          </td>
          <td> 
            <?
              db_input('q81_recpro',10,$Iq81_recpro,true,'text',$db_opcao," onchange='js_pesquisaq81_recpro(false);'");
              db_input('k02_descr',47,$Ik02_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_qipro?>">
             <?=@$Lq81_qipro?>
          </td>
          <td> 
            <?
              db_input('q81_qipro',10,$Iq81_qipro,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_qfpro?>">
             <?=@$Lq81_qfpro?>
          </td>
          <td> 
            <?
              db_input('q81_qfpro',10,$Iq81_qfpro,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_valpro?>">
             <?=@$Lq81_valpro?>
          </td>
          <td> 
            <?
              db_input('q81_valpro',10,$Iq81_valpro,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_uqtab?>">
             <?=@$Lq81_uqtab?>
          </td>
          <td> 
            <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('q81_uqtab',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_uqcad?>">
             <?=@$Lq81_uqcad?>
          </td>
          <td> 
            <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('q81_uqcad',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
      
      
        <tr>
          <td nowrap title="<?=@$Tq81_gera?>">
             <?
             db_ancora(@$Lq81_gera,"js_pesquisaq81_gera(true);",$db_opcao);
             ?>
          </td>
          <td> 
              <?
              db_input('q81_gera',10,$Iq81_gera,true,'text',$db_opcao," onchange='js_pesquisaq81_gera(false);'");
              db_input('q89_descr',47,$Iq89_descr,true,'text',3,'');
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_percprovis?>">
             <?=@$Lq81_percprovis?>
          </td>
          <td>
          <?
            db_input('q81_percprovis',15,$Iq81_percprovis,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_usaretido?>">
             <?=@$Lq81_usaretido?>
          </td>
          <td>
          <?
            $x = array("f"=>"NAO","t"=>"SIM");
            db_select('q81_usaretido',$x,true,$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tq81_excedenteativ?>">
             <?=@$Lq81_excedenteativ?>
          </td>
          <td>
          <?
            db_input('q81_excedenteativ',15,$Iq81_excedenteativ,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>
      </table>
    </fieldset>  
    
    
    <div style="margin-top: 10px;">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>"type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao==1||$db_opcao==2?"onclick='js_hab();'":"")?>>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </div>  

  </center>

</form>
<script>
function js_pesquisaq81_cadcalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cadcalc','func_cadcalc.php?funcao_js=parent.js_mostracadcalc1|q85_codigo|q85_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cadcalc','func_cadcalc.php?pesquisa_chave='+document.form1.q81_cadcalc.value+'&funcao_js=parent.js_mostracadcalc','Pesquisa',false);
  }
}
function js_mostracadcalc(chave,erro){
  document.form1.q85_descr.value = chave; 
  if(erro==true){ 
    document.form1.q81_cadcalc.focus(); 
    document.form1.q81_cadcalc.value = ''; 
  }
}
function js_mostracadcalc1(chave1,chave2){
  document.form1.q81_cadcalc.value = chave1;
  document.form1.q85_descr.value = chave2;
  db_iframe_cadcalc.hide();
}
function js_pesquisaq81_recexe(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrecexe','func_tabrec.php?funcao_js=parent.js_mostratabrec1exe|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrecexe','func_tabrec.php?pesquisa_chave='+document.form1.q81_recexe.value+'&funcao_js=parent.js_mostratabrecexe','Pesquisa',false);
  }
}
function js_mostratabrecexe(chave,erro){
  document.form1.k02_descrexe.value = chave; 
  if(erro==true){ 
    document.form1.q81_recexe.focus(); 
    document.form1.q81_recexe.value = ''; 
  }
}
function js_mostratabrec1exe(chave1,chave2){
  document.form1.q81_recexe.value = chave1;
  document.form1.k02_descrexe.value = chave2;
  db_iframe_tabrecexe.hide();
}
function js_pesquisaq81_recpro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.q81_recpro.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.q81_recpro.focus(); 
    document.form1.q81_recpro.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.q81_recpro.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisaq81_gera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_geradesc','func_geradesc.php?funcao_js=parent.js_mostrageradesc1|q89_codigo|q89_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_geradesc','func_geradesc.php?pesquisa_chave='+document.form1.q81_gera.value+'&funcao_js=parent.js_mostrageradesc','Pesquisa',false);
  }
}
function js_mostrageradesc(chave,erro){
  document.form1.q89_descr.value = chave; 
  if(erro==true){ 
    document.form1.q81_gera.focus(); 
    document.form1.q81_gera.value = ''; 
  }
}
function js_mostrageradesc1(chave1,chave2){
  document.form1.q81_gera.value = chave1;
  document.form1.q89_descr.value = chave2;
  db_iframe_geradesc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tipcalc','func_tipcalc.php?funcao_js=parent.js_preenchepesquisa|q81_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipcalc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
<?
if(($db_opcao==1||$db_opcao==2) && isset($q81_integr)){
  echo "js_troca('$q81_integr');\n";
}
?>
</script>