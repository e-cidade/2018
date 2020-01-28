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

//MODULO: pessoal
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clvtffunc->rotulo->label();
$clrotulo->label("r16_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("r70_descr");
$clrotulo->label("r70_estrut");
$db_opcao = 1;
if(isset($opcao)){
  if($opcao == "alterar"){
    $db_opcao = 2;
  }else{
    $db_opcao = 3;
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>Informações do funcionário</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tr17_regist?>">
              <?
              db_ancora($Lr17_regist,"js_pesquisar17_regist(true)",(isset($opcao)?3:$db_opcao));
              ?>
            </td>
            <td colspan="3"> 
              <?
              db_input('r17_regist',8,$Ir17_regist,true,'text',(isset($opcao)?3:$db_opcao),"onchange='js_pesquisar17_regist(false)'");
              db_input('z01_numcgm',13,$Iz01_numcgm,true,'text',3,"");
              db_input('z01_nome',30,$Iz01_nome,true,'text',3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr17_lotac?>">
              <?
              db_ancora($Lr17_lotac,"",3);
              ?>
            </td>
            <td colspan="3"> 
              <?
              db_input('r17_lotac',8,$Ir17_lotac,true,'text',3);
              db_input('r70_estrut',13,$Ir70_estrut,true,'text',3);
              db_input('r70_descr',30,$Ir70_descr,true,'text',3);
              ?>
            </td>
          </tr>
          
          <tr>
            <td>
              <input type="button" value="Locais de Trabalho" onclick="js_cadastraLocaisTrabalho()" />
            </td>
          </tr>
          
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>Informações dos vales</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tr17_codigo?>">
              <?
              db_ancora(@$Lr17_codigo,"js_pesquisar17_codigo(true);",(isset($opcao)?3:$db_opcao));
              ?>
            </td>
            <td colspan="3"> 
              <?
              db_input('r17_codigo',8,$Ir17_codigo,true,'text',(isset($opcao)?3:$db_opcao)," onchange='js_pesquisar17_codigo(false);'");
              db_input('r16_descr',46,$Ir16_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr17_tipo?>">
              <?=@$Lr17_tipo?>
            </td>
            <td> 
              <?
              if(!isset($r17_tipo)){
                $r17_tipo = "t";
              }
              $x = array("f"=>"Diário","t"=>"Mensal");
              db_select('r17_tipo',$x,true,$db_opcao,"onchange='js_verificatipo(this.value);'");
              ?>
            </td>
            <td nowrap title="<?=@$Tr17_difere?>" align="right">
              <?=@$Lr17_difere?>
            </td>
            <td> 
              <?
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r17_difere',$x,true,(isset($opcao)?3:$db_opcao));
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr17_quant?>">
              <?=@$Lr17_quant?>
            </td>
            <td> 
              <?
              db_input('r17_quant',8,$Ir17_quant,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap title="<?=@$Tr17_situac?>" align="right">
              <?=@$Lr17_situac?>
            </td>
            <td> 
              <?
              $x = array("A"=>"Ativo","I"=>"Inativo");
              db_select('r17_situac',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <div id="semanal">
                <fieldset>
                  <legend><b>Vales diários</b></legend>
                  <table border="1" cellpadding="0" cellspacing="0">
                    <tr>
                      <td align="center">
                        <b>Dom</b>
                      </td>
                      <td align="center">
                        <b>Seg</b>
                      </td>
                      <td align="center">
                        <b>Ter</b>
                      </td>
                      <td align="center">
                        <b>Qua</b>
                      </td>
                      <td align="center">
                        <b>Qui</b>
                      </td>
                      <td align="center">
                        <b>Sex</b>
                      </td>
                      <td align="center">
                        <b>Sab</b>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <?
                        db_input('dom',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('seg',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('ter',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('qua',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('qui',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('sex',10,3,true,'text',3,"")
                        ?>
                      </td>
                      <td align="center">
                        <?
                        db_input('sab',10,3,true,'text',3,"")
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <?
                        $arr_obrigatorio = array("t"=>"Sim","f"=>"Não");
                        if(!isset($odom)){
                          $odom = "f";
                        }
                        db_select('odom',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($oseg)){
                          $oseg = "f";
                        }
                        db_select('oseg',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($oter)){
                          $oter = "f";
                        }
                        db_select('oter',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($oqua)){
                          $oqua = "f";
                        }
                        db_select('oqua',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($oqui)){
                          $oqui = "f";
                        }
                        db_select('oqui',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($osex)){
                          $osex = "f";
                        }
                        db_select('osex',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                      <td align="center">
                        <?
                        if(!isset($osab)){
                          $osab = "f";
                        }
                        db_select('osab',$arr_obrigatorio,true,$db_opcao)
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </div>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?if(isset($opcao)){?>
      <input name="novo" type="button" id="novo" value="Outro vale" onclick="location.href='pes4_vtffunc001.php?r17_regist=<?=@$r17_regist?>'" >
      <input name="novamatricula" type="button" id="novamatricula" value="Outra matrícula" onclick="location.href='pes4_vtffunc001.php';" >
      <?}?>
    </td>
  </tr>
  <tr>
    <td align="center">
      <?
      $ano = db_anofolha();
      $mes = db_mesfolha();
      $res_cf = pg_query("select * from cfpess where r11_anousu = $ano and r11_mesusu = $mes and r11_instit = ".db_getsession('DB_instit'));
      db_fieldsmemory($res_cf,0);
      $campo_quantidade = "";
      if($r11_vtprop == "t"){
  $campo_quantidade = "quantvale_afas(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,'".$r11_vtfer."',".db_dias_mes($ano,$mes).",".db_getsession("DB_instit").") as ";
      }else{
  $campo_quantidade = "quantvale(r17_codigo,r17_regist,r17_anousu,r17_mesusu,0,r17_difere,".db_getsession("DB_instit").") as ";
      }
      $dbwhere = "r17_anousu = $ano and r17_mesusu = $mes and r17_regist = ".@$r17_regist; 
      if(isset($codigo) && trim($codigo) != "" && isset($difere) && trim($difere) != ""){
        $dbwhere.= " and (r17_codigo <> '".$codigo."' or (r17_codigo = '".$codigo."' and r17_difere <> '".$difere."'))";
      }
      $chavepri = array("r17_anousu"=>$ano,"r17_mesusu"=>$mes,"r17_regist"=>@$r17_regist,"r17_codigo"=>@$r17_codigo,"r17_difere"=>@$r17_difere);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql = $clvtffunc->sql_query(
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             null,
                                                             "r17_anousu,r17_mesusu,r17_codigo,r16_descr,".$campo_quantidade." r17_quant,r17_difere,case when r17_situac = 'I' then 'Inativo' else 'Ativo' end as r17_situac,case when r17_tipo = 'f' then 'Diário' else 'Mensal' end as r17_tipo,r17_regist",
                                                             "r17_codigo",
                                                             $dbwhere
                                                            );
      // echo $cliframe_alterar_excluir->sql;
      $cliframe_alterar_excluir->campos   = "r17_codigo,r16_descr,r17_quant,r17_difere,r17_situac,r17_tipo";
      $cliframe_alterar_excluir->opcoes   = 3;
      $cliframe_alterar_excluir->legenda  = "";
      $cliframe_alterar_excluir->iframe_height = "200";
      $cliframe_alterar_excluir->opcoes   = 1;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>

/**
 * Exibe Componente de Manutenção de Locais de Trabalho 
 * @access public
 * @return void
 */
function js_cadastraLocaisTrabalho() {

  oLocaisTrabalho = new DBViewManutencaoLocalTrabalho('oLocaisTrabalho', 'locaisTrabalho');
  oLocaisTrabalho.setCodigoServidor(<?php echo @$r17_regist; ?>); 
  oLocaisTrabalho.show();
}
 
/**
 * js_verificatipo 
 * 
 * @param  valor valor 
 * @access public
 * @return void
 */
function js_verificatipo(valor){
  <?if($db_opcao != 3){?>
  if(valor == 't'){
    document.form1.dom.value                 = "";
    document.form1.seg.value                 = "";
    document.form1.ter.value                 = "";
    document.form1.qua.value                 = "";
    document.form1.qui.value                 = "";
    document.form1.sex.value                 = "";
    document.form1.sab.value                 = "";
    document.form1.odom.value                = 1;
    document.form1.oseg.value                = 1;
    document.form1.oter.value                = 1;
    document.form1.oqua.value                = 1;
    document.form1.oqui.value                = 1;
    document.form1.osex.value                = 1;
    document.form1.osab.value                = 1;
    document.form1.odom.disabled             = true;
    document.form1.oseg.disabled             = true;
    document.form1.oter.disabled             = true;
    document.form1.oqua.disabled             = true;
    document.form1.oqui.disabled             = true;
    document.form1.osex.disabled             = true;
    document.form1.osab.disabled             = true;
    document.form1.dom.readOnly              = true;
    document.form1.seg.readOnly              = true;
    document.form1.ter.readOnly              = true;
    document.form1.qua.readOnly              = true;
    document.form1.qui.readOnly              = true;
    document.form1.sex.readOnly              = true;
    document.form1.sab.readOnly              = true;
    document.form1.r17_quant.readOnly        = false;
    document.form1.dom.style.backgroundColor = "#DEB887";
    document.form1.seg.style.backgroundColor = "#DEB887";
    document.form1.ter.style.backgroundColor = "#DEB887";
    document.form1.qua.style.backgroundColor = "#DEB887";
    document.form1.qui.style.backgroundColor = "#DEB887";
    document.form1.sex.style.backgroundColor = "#DEB887";
    document.form1.sab.style.backgroundColor = "#DEB887";
    document.form1.r17_quant.style.backgroundColor = "";
  }else{
    <?
    if($db_opcao == 1){
    ?>
    document.form1.r17_quant.value           = "";
    <?
    }
    ?>
    document.form1.odom.disabled             = false;
    document.form1.oseg.disabled             = false;
    document.form1.oter.disabled             = false;
    document.form1.oqua.disabled             = false;
    document.form1.oqui.disabled             = false;
    document.form1.osex.disabled             = false;
    document.form1.osab.disabled             = false;
    document.form1.dom.readOnly              = false;
    document.form1.seg.readOnly              = false;
    document.form1.ter.readOnly              = false;
    document.form1.qua.readOnly              = false;
    document.form1.qui.readOnly              = false;
    document.form1.sex.readOnly              = false;
    document.form1.sab.readOnly              = false;
    document.form1.r17_quant.readOnly        = true;
    document.form1.dom.style.backgroundColor = "";
    document.form1.seg.style.backgroundColor = "";
    document.form1.ter.style.backgroundColor = "";
    document.form1.qua.style.backgroundColor = "";
    document.form1.qui.style.backgroundColor = "";
    document.form1.sex.style.backgroundColor = "";
    document.form1.sab.style.backgroundColor = "";
    document.form1.r17_quant.style.backgroundColor = "#DEB887";
  }
  js_tabulacaoforms("form1","r17_regist",false,1,"r17_regist",false);
  <?}?>
}
function js_pesquisar17_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=raf&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true,'20');
  }else{
    if(document.form1.r17_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=raf&pesquisa_chave='+document.form1.r17_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.z01_numcgm.value = '';
      document.form1.r17_lotac.value = '';
      document.form1.r70_descr.value = '';
      document.form1.r70_estrut.value = '';
      document.form1.dom.value = "";
      document.form1.seg.value = "";
      document.form1.ter.value = "";
      document.form1.qua.value = "";
      document.form1.qui.value = "";
      document.form1.sex.value = "";
      document.form1.sab.value = "";
      document.form1.submit();
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r17_regist.focus(); 
    document.form1.r17_regist.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r17_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}
function js_pesquisar17_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vtfempr','func_vtfempr.php?funcao_js=parent.js_mostravtfempr1|r16_codigo|r16_descr','Pesquisa',true);
  }else{
    if(document.form1.r17_codigo.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_vtfempr','func_vtfempr.php?pesquisa_chave='+document.form1.r17_codigo.value+'&funcao_js=parent.js_mostravtfempr','Pesquisa',false);
    }else{
      document.form1.r16_descr.value = ''; 
    }
  }
}
function js_mostravtfempr(chave,erro){
  document.form1.r16_descr.value = chave; 
  if(erro==true){ 
    document.form1.r17_codigo.focus(); 
    document.form1.r17_codigo.value = ''; 
  }
}
function js_mostravtfempr1(chave1,chave2){
  document.form1.r17_codigo.value = chave1;
  document.form1.r16_descr.value = chave2;
  db_iframe_vtfempr.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vtffunc','func_vtffunc.php?funcao_js=parent.js_preenchepesquisa|r17_anousu|r17_mesusu|r17_regist|r17_codigo|r17_difere','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2,chave3,chave4){
  db_iframe_vtffunc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3+'&chavepesquisa4='+chave4";
  }
  ?>
}
js_verificatipo(document.form1.r17_tipo.value);
</script>