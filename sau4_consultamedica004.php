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

//MODULO:saude
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clcgs = new cl_cgs;
$clcgs_und = new cl_cgs_und;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;

$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
//select * from prontuarios inner join cgs_und on z01_i_cgsund = sd24_i_numcgs where sd24_i_numcgs = ( select sd24_i_numcgs from prontuarios where sd24_i_codigo = 4 order by sd24_i_codigo) 

$sql = "select * from cgs_und where z01_i_cgsund = ( select sd24_i_numcgs from prontuarios where sd24_i_codigo = $chave_sd24_i_codigo order by sd24_i_codigo)";
 $query = pg_query($sql);
 $linhas4 = pg_num_rows($query);
db_fieldsmemory($query,0);
?>
<form name="form1" method="post" action="">
<table width="100%" border="0">
  <tr>
   <td align="CENTER"  width="50%">
    DADOS DO PACIENTE
   </td>
   <td align="center">
    PRONTU�RIO
   </td>
 </tr>
 <tr>
 
  <!--tabela paciente-->
  <td>
    <table>
     <tr>
      <td>
        <tr>
        <td nowrap title="<?=@$Tz01_v_cgccpf?>">
         <?=@$Lz01_v_cgccpf?>      
          </td>
          <td>    
         <?db_input('z01_v_cgccpf',15,@$Iz01_v_cgccpf,true,'text',3,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");?>
        </td>
        </tr>
        <tr>
        <td nowrap title="<?=@$Tz01_v_ident?>">         
         <?=@$Lz01_v_ident?>          
         </td>
          <td>
         <?db_input('z01_v_ident',15,@$Iz01_v_ident,true,'text',3);?>
        </td>
        </tr>  
       <tr>
        <td width="27%" title='<?=$Tz01_i_cgsund?>' nowrap>
         <?=$Lz01_i_cgsund?>
        </td>
        <td width="73%" nowrap>
         <?db_input('z01_i_cgsund',20,$Iz01_i_cgsund,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_nome?>">
         <?=@$Lz01_v_nome?>
        </td>
        <td nowrap title="<?=@$Tz01_v_nome?>" align="left">
         <?db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,"");?>
        </td>
       </tr>
       <tr>
        <td nowrap title=<?=@$Tz01_v_pai?>>
         <?=@$Lz01_v_pai?>
        </td>
        <td nowrap title="<?=@$Tz01_v_pai?>">
         <?db_input('z01_v_pai',40,$Iz01_v_pai,true,'text',3,"");?>
        </td>
       </tr>
       <tr>
        <td nowrap title=<?=@$Tz01_v_mae?>>
         <?=@$Lz01_v_mae?>
        </td>
        <td nowrap title="<?=@$Tz01_v_mae?>">
         <?db_input('z01_v_mae',40,$Iz01_v_mae,true,'text',3,"");?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=$Tz01_d_nasc?>">
         <?=$Lz01_d_nasc?>
        </td>
        <td nowrap title="<?=$Tz01_d_nasc?>">
         <?db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=$Tz01_i_estciv?>">
         <?=$Lz01_i_estciv?>
        </td>
         <td nowrap title="<?=$Tz01_i_estciv?>">
          <?
          $x = array("1"=>"Solteiro","2"=>"Casado","3"=>"Vi�vo","4"=>"Divorciado");
          db_select('z01_i_estciv',$x,true,3);
          ?>
          <?=$Lz01_v_sexo?>
          <?
          $sex = array("M"=>"Masculino","F"=>"Feminino");
          db_select('z01_v_sexo',$sex,true,3);
          ?>
         </td>
        </tr>
         <tr>
        <td nowrap title="<?=@$Tz01_v_ender?>">
         <?db_ancora(@$Lz01_v_ender,"js_ruas();",3);?>
        </td>
        <td nowrap>
         <?db_input('z01_v_ender',40,$Iz01_v_ender,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td width="29%" nowrap title="<?=@$Tz01_i_numero?>">
         <?=@$Lz01_i_numero?>
        </td>
        <td width="71%" nowrap>
         <a name="AN3">
         <?db_input('z01_i_numero',8,$Iz01_i_numero,true,'text',3);?>
         &nbsp;
         <?=@$Lz01_v_compl?>
         <?db_input('z01_v_compl',10,$Iz01_v_compl,true,'text',3);?>
         </a>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_munic?>">
         <?=@$Lz01_v_munic?>
        </td>
        <td nowrap>
         <?db_input('z01_v_munic',20,$Iz01_v_munic,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_uf?>">
         <?=@$Lz01_v_uf?>
        </td>
        <td nowrap>
         <?db_input('z01_v_uf',2,$Iz01_v_uf,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_bairro?>">
         <?db_ancora(@$Lz01_v_bairro,"js_bairro();",3);?>
        </td>
        <td nowrap>
         <?db_input('j13_codi',10,$Ij13_codi,true,'text',3);?>
         <?db_input('z01_v_bairro',25,$Iz01_v_bairro,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_cep?>">
         <?=@$Lz01_v_cep?>
        </td>
        <td nowrap>
         <?db_input('z01_v_cep',9,$Iz01_v_cep,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_telef?>">
         <?=@$Lz01_v_telef?>
        </td>
        <td nowrap>
         <?db_input('z01_v_telef',12,$Iz01_v_telef,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_telcel?>">
         <?=@$Lz01_v_telcel?>
        </td>
        <td nowrap>
         <?db_input('z01_v_telcel',12,$Iz01_v_telcel,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_email?>">
         <?=@$Lz01_v_email?>
        </td>
        <td nowrap>
         <?db_input('z01_v_email',30,$Iz01_v_email,true,'text',3);?>
        </td>
       </tr>
       <tr>
        <td nowrap title="<?=@$Tz01_v_cxpostal?>">
         <?=@$Lz01_v_cxpostal?>
        </td>
        <td nowrap>
         <?db_input('z01_v_cxpostal',10,$Iz01_v_cxpostal,true,'text',3);?>
        </td>
       </tr>
       <tr align="left" valign="middle">
     <td>
      <?=@$Lz01_d_cadast?>
      </td>
       <td>
      <?db_inputdata('z01_d_cadast',@$z01_d_cadast_dia,@$z01_d_cadast_mes,@$z01_d_cadast_ano,true,'text',3);?>
     </td>
    </tr>
      
      </td>
     </tr>
    </table>
  </td>
  <!--iframe-->
  <td>
  <iframe name="prontmedico" id="prontmedico" src="sau4_consultamedica005.php?chave_sd24_i_numcgs=<?=$z01_i_cgsund?>" width="100%" height="100%" scrolling="yes"></iframe>
  </td>
</tr>
</table>
</form>
<script>
 function js_ruas(){
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas(chave,chave1){
   document.form1.ed47_v_ender.value = chave1;
   db_iframe_ruas.hide();
 }
 function js_bairro(){
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro(chave,chave1){
  document.form1.j13_codi.value = chave;
  document.form1.ed47_v_bairro.value = chave1;
  db_iframe_bairro.hide();
 }
 function js_ruas1(){
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas1(chave,chave1){
   document.form1.ed47_v_endcon.value = chave1;
   db_iframe_ruas1.hide();
 }
 function js_bairro1(){
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro1(chave,chave1){
  document.form1.ed47_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
 }
 function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_prontmedicos','sau4_consultamedica004.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund','Pesquisa Prontuários Médicos',true);
 }
 function LiberaEndereco(valor){
  if(valor=="S"){
   document.form1.ed47_v_ender.readOnly = false;
   document.form1.ed47_v_ender.style.background = "#FFFFFF";
   document.links[0].style.color = "#000000";
   document.links[0].style.textDecoration = "none";
   document.links[0].href = "";
  }else if(valor=="N"){
   document.form1.ed47_v_ender.readOnly = true;
   document.form1.ed47_v_ender.style.background = "#DEB887";
   document.links[0].style.color = "blue";
   document.links[0].style.textDecoration = "underline";
   document.links[0].href = "#";
  }
 }
 function js_preenchepesquisa(chave){
  db_iframe_aluno.hide();
  <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>
 }
 function js_novo(){
  parent.location="edu1_alunoabas001.php";
 }
 LiberaEndereco("N");
</script>