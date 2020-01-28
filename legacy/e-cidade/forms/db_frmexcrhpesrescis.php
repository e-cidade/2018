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
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("rh01_admiss");
$clrotulo->label("z01_nome");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r59_aviso");
$clrotulo->label("rh02_seqpes");
$clrotulo->label("rh02_codreg");
?>
<form name="form1" method="post">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>FUNCIONÁRIO</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Trh01_regist?>" align="right">
	      <?
              db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",$db_opcao);
	      ?>
            </td>
            <td>
              <?
              db_input('rh01_regist',6,$Irh01_regist,true,'text',$db_opcao,"onchange='js_pesquisarh01_regist(false);'");
              db_input('rh02_seqpes',6,$Irh02_seqpes,true,'hidden',3,"");
              db_input('rh02_codreg',6,$Irh02_codreg,true,'hidden',3,"");
              db_input('r59_aviso',6,$Ir59_aviso,true,'hidden',3,"");
              db_input('pagar_13_salario_na_rescisao',6,0,true,'hidden',3,"");
              ?>
              <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
              ?>
            </td>
            <td nowrap title="<?=@$Trh01_admiss?>" align="right">
              <?
              db_ancora(@$Lrh01_admiss,"",3);
              ?>
            </td>
            <td>
              <?
              db_inputdata('rh01_admiss',@$rh01_admiss_dia,@$rh01_admiss_mes,@$rh01_admiss_ano,true,'text',3,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>RESCISÃO</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Trh05_recis?>" align="right">
              <?=@$Lrh05_recis?>
            </td>
            <td>
              <?
              db_inputdata('rh05_recis',@$rh05_recis_dia,@$rh05_recis_mes,@$rh05_recis_ano,true,'text',3)
              ?>
            </td>
            <td nowrap title="<?=@$Trh05_causa?>" align="right">
              <?
              db_ancora(@$Lrh05_causa,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_causa',6,$Irh05_causa,true,'text',3,"")
              ?>
              <?
              db_input('r59_descr',40,$Ir59_descr,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td nowrap title="<?=@$Trh05_caub?>" align="right">
              <?
              db_ancora(@$Lrh05_caub,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_caub',6,$Irh05_caub,true,'text',3,"")
              ?>
              <?
              db_input('r59_descr1',40,$Ir59_descr1,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_taviso?>" align="right">
              <?
              db_ancora(@$Lrh05_taviso,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('taviso',20,0,true,'text',3,"")
              ?>
            </td>
            <td nowrap title="<?=@$Trh05_aviso?>" align="right">
              <?=@$Lrh05_aviso?>
            </td>
            <td>
              <?
              db_inputdata('rh05_aviso',@$rh05_aviso_dia,@$rh05_aviso_mes,@$rh05_aviso_ano,true,'text',3)
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_mremun?>" align="right">
              <?
              db_ancora(@$Lrh05_mremun,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_mremun',6,$Irh05_mremun,true,'text',3,"")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=($db_botao==false?"disabled":"")?>  <?if($db_opcao!=3)echo "onclick='return js_verificadados();'";?>>
    </td>
  </tr>
</table> 
<script>
function js_faltas(registro){
  qry = 'opcao=dadosrescis';
  qry+= '&seqpes='+document.form1.rh02_seqpes.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa',false);
}
function js_verificadados(){
  x = document.form1;
  if(x.rh01_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    x.rh01_regist.focus();
    return false;
  }
  return true;
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalrecis.php?testarescisao=af&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome|rh01_admiss|rh02_seqpes|r30_proc1|r30_proc2|r30_per1f|r30_per2f|rh02_codreg|rh14_matipe|rh14_dtvinc|rh05_recis|rescindido&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  }else{
     if(document.form1.rh01_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalrecis.php?testarescisao=af&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrarhpessoal&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       document.form1.rh01_admiss_dia.value = '';
       document.form1.rh01_admiss_mes.value = '';
       document.form1.rh01_admiss_ano.value = '';
       document.form1.rh05_recis_dia.value  = '';
       document.form1.rh05_recis_mes.value  = '';
       document.form1.rh05_recis_ano.value  = '';
       document.form1.rh05_causa.value      = '';
       document.form1.r59_descr.value       = '';
       document.form1.rh05_caub.value       = '';
       document.form1.r59_descr1.value      = '';
       document.form1.taviso.value          = '';
       document.form1.rh05_aviso_dia.value  = '';
       document.form1.rh05_aviso_mes.value  = '';
       document.form1.rh05_aviso_ano.value  = '';
       document.form1.rh05_mremun.value     = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,chave12,erro){
  document.form1.z01_nome.value = chave;
  if(chave11 == "" && erro == false){
    alert("Funcionário "+document.form1.rh01_regist.value+" ("+chave+") não rescindiu contrato.");
    erro = true;
  }
  if(erro==true){ 
    document.form1.rh01_regist.value = '';
    js_pesquisarh01_regist(false); 
    document.form1.rh01_regist.focus(); 
  }else{
    subpes = "<?=(db_anofolha()."/".db_mesfolha())?>";
    anomes = chave11.substring(0,4)+"/"+chave11.substring(5,7);
    if(anomes < subpes && chave12 != ""){
      alert("Contrato não rescindido neste ano/mês.");
    }
    if(chave4 == subpes || chave5 == subpes){
      alert("ALERTA: Funcionário tem pagamento de férias no mês.");
    }
    document.form1.rh01_admiss_ano.value = chave2.substring(0,4);
    document.form1.rh01_admiss_mes.value = chave2.substring(5,7);
    document.form1.rh01_admiss_dia.value = chave2.substring(8,10);
    document.form1.rh02_seqpes.value = chave3;
    document.form1.rh02_codreg.value = chave8;
    js_faltas(chave);
  }
}
function js_mostrarhpessoal1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,chave12,chave13){
  if(chave12 == ""){
    alert("Funcionário "+chave1+" ("+chave2+") não rescindiu contrato.");
    document.form1.rh01_regist.value = '';
    js_pesquisarh01_regist(false); 
    document.form1.rh01_regist.focus();
  }else{
    subpes = "<?=(db_anofolha()."/".db_mesfolha())?>";
    anomes = chave12.substring(0,4)+"/"+chave12.substring(5,7);
    if(anomes < subpes && chave13 != ""){
      alert("Contrato não rescindido neste ano/mês.");
    }
    if(chave5 == subpes || chave6 == subpes){
      alert("ALERTA: Funcionário tem pagamento de férias no mês.");
    }
    document.form1.rh01_regist.value = chave1;
    document.form1.z01_nome.value = chave2;
    document.form1.rh01_admiss_ano.value = chave3.substring(0,4);
    document.form1.rh01_admiss_mes.value = chave3.substring(5,7);
    document.form1.rh01_admiss_dia.value = chave3.substring(8,10);
    document.form1.rh02_seqpes.value = chave4;
    document.form1.rh02_codreg.value = chave9;
    js_faltas(chave1);
    db_iframe_rhpessoal.hide();
  }
}
</script>