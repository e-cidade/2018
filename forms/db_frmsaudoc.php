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

//MODULO: saude
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");



/*
if($db_opcao!=1 && @$z01_i_cgsund!=""){
 $sql = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $z01_i_cgsund";
 $query = pg_query($sql);
 $linhas4 = pg_num_rows($query);
 if($linhas4==0){
  $db_botao = true;
 }elseif(db_getsession("DB_coddepto")!=pg_result($query,0,0)){
  $db_botao = false;
 }else{
  $db_botao = true;
 }
}
*/
?>
<form name="form1" method="post" action="">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>

   <fieldset style="width:90%"><legend><b>Gerais</b></legend>
     <table border="0" >
       <tr>
        <td nowrap width=21% title="<?=@$Tz01_c_pis?>">
          <?=@$Lz01_c_pis?>
         </td>
         <td nowrap width=20% title="<?=@$Tz01_c_pis?>">
          <?db_input('z01_c_pis',10,$Iz01_c_pis,true,'text',$db_opcao);?>
         </td>
         <td nowrap align="right" width=30% title="<?=@$Tz01_v_uf?>">
          <?=@$Lz01_v_uf?>
         </td>
         <td nowrap width=22% title="<?=@$Tz01_v_uf?>">
          <?db_input('z01_v_uf',5,@$Iz01_v_uf,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz01_c_naturalidade?>">
          <?=@$Lz01_c_naturalidade?>
         </td>
         <td nowrap colspan="3" title="<?=@$Tz01_c_naturalidade?>">
          <?db_input('z01_c_naturalidade',56,@$Iz01_c_naturalidade,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Ted228_c_descr?>">
          <b>País Origem:</b>
         </td>
         <td nowrap colspan="3" title="<?=@$Ted228_c_descr?>">
          <?db_input('ed228_c_descr',56,@$Ied228_c_descr,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz01_c_datapais?>">
          <b>Data Entrada:</b>
         </td>
         <td nowrap title="<?=@$Tz01_c_datapais?>">
          <?db_inputdata('z01_d_datapais',@$z01_d_datapais_dia,@$z01_d_datapais_mes,@$z01_d_datapais_ano,true,'text',$db_opcao);?>      </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz01_c_escolaridade?>">
          <?=@$Lz01_c_escolaridade?>
         </td>
         <td nowrap colspan="3" title="<?=@$Tz01_c_escolaridade?>">
          <?db_input('z01_c_escolaridade',56,@$Iz01_c_escolaridade,true,'text',$db_opcao);?>
         </td>
      </tr>
     </table>
   </fieldset>



  <fieldset style="width:90%"><legend><b>Certidão</b></legend>
   <table border="0" >
     <tr>
        <td nowrap width=23% title="<?=@$Tz01_c_certidaotipo?>">
          <?=@$Lz01_c_certidaotipo?>
         </td>
         <td nowrap width=20% title="<?=@$Tz01_c_certidaotipo?>">
          <?db_input('z01_c_certidaotipo',15,$Iz01_c_certidaotipo,true,'text',$db_opcao,"");?>
         </td>
         <td nowrap width=24% align="right" title="<?=@$Tz01_c_certidaocart?>">
          <?=@$Lz01_c_certidaocart?>
         </td>
         <td nowrap title="<?=@$Tz01_c_certidaocart?>">
          <?db_input('z01_c_certidaocart',15,$Iz01_c_certidaocart,true,'text',$db_opcao,"");?>
         </td>
         <tr>
         <td nowrap title="<?=@$Tz01_c_certidaolivro?>">
          <?=@$Lz01_c_certidaolivro?>
         </td>
         <td nowrap title="<?=@$Tz01_c_certidaolivro?>">
          <?db_input('z01_c_certidaolivro',15,$Iz01_c_certidaolivro,true,'text',$db_opcao,"");?>
         </td>
          <td nowrap align="right" title="<?=@$Tz01_c_certidaofolha?>">
          <?=@$Lz01_c_certidaofolha?>
         </td>
         <td nowrap title="<?=@$Tz01_c_certidaofolha?>">
          <?db_input('z01_c_certidaofolha',15,$Iz01_c_certidaofolha,true,'text',$db_opcao,"");?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tz01_c_certidaotermo?>">
          <b>Termo</b>
         </td>
         <td nowrap title="<?=@$Tz01_c_certidaotermo?>">
          <?db_input('z01_c_certidaotermo',15,@$Iz01_c_certidaotermo,true,'text',$db_opcao,"");?>
         </td>
          <td nowrap title="<?=@$Tz01_c_certidaodata?>" align='right'>
          <?=@$Lz01_c_certidaodata?>
         </td>
         <td nowrap title="<?=@$Tz01_c_certidaodata?>">
          <?
          if(isset($z01_c_certidaodata) && !empty($z01_c_certidaodata)) {

            $dAux = explode('-', $z01_c_certidaodata);

            $z01_c_certidaodata_dia = $dAux[2];
            $z01_c_certidaodata_mes = $dAux[1];
            $z01_c_certidaodata_ano = $dAux[0];

          }
          db_inputdata('z01_c_certidaodata',@$z01_c_certidaodata_dia,@$z01_c_certidaodata_mes,@$z01_c_certidaodata_ano,true,'text',$db_opcao);
          ?>
         </td>
    </tr>
   </table>
  </fieldset>


 
 <fieldset style="width:90%"><legend><b>Dados Bancários</b></legend>
  <table border="0"  >
    <tr>
      <td nowrap width=23% title="<?=@$Tz01_c_banco?>">
        <?=@$Lz01_c_banco?>
        </td>
         <td nowrap width=20% title="<?=@$Tz01_c_banco?>">
          <?db_input('z01_c_banco',15,$Iz01_c_banco,true,'text',$db_opcao,"");?>
         </td>
         <td nowrap align="right" width=24% title="<?=@$Tz01_c_agencia?>">
        <?=@$Lz01_c_agencia?>
        </td>
         <td nowrap title="<?=@$Tz01_c_agencia?>">
          <?db_input('z01_c_agencia',15,$Iz01_c_agencia,true,'text',$db_opcao,"");?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tz01_c_conta?>">
        <?=@$Lz01_c_conta?>
        </td>
         <td nowrap title="<?=@$Tz01_c_conta?>">
          <?db_input('z01_c_conta',15,$Iz01_c_conta,true,'text',$db_opcao,"");?>
         </td>
    </tr>
  </table>
 </fieldset>
  </td>

 <td>
 <fieldset style="width:90%"><legend><b>Identidade</b></legend>
  <table border="0" >
    <tr>
     <td nowrap width=60% title="<?=@$Tz01_v_ident?>">
          <?=@$Lz01_v_ident?>
         </td>
         <td nowrap width=40% title="<?=@$Tz01_v_ident?>">
          <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',$db_opcao,"");?>
          </td>
          </tr>
          <tr>
         <td nowrap title="<?=@$Tz01_d_dtemissao?>">
          <?=@$Lz01_d_dtemissao?>
         </td>
         <td nowrap title="<?=@$Tz01_d_dtemissao?>">
          <?db_inputdata('z01_d_dtemissao',@$z01_d_dtemissao_dia,@$z01_d_dtemissao_mes,@$z01_d_dtemissao_ano,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tsd51_v_descricao?>">
          <b>Órgão Emissor:</b>
         </td>
         <td nowrap title="<?=@$Tsd51_v_descricao?>">
          <?db_input('sd51_v_descricao',15,@$Isd51_v_descricao,true,'text',$db_opcao,"");?>
          </td>
          </tr>
          <tr>
         <td nowrap title="<?=@$Tz01_c_ufident?>">
          <?=@$Lz01_c_ufident?>
         </td>
         <td nowrap title="<?=@$Tz01_c_ufident?>">
          <?db_input('z01_c_ufident',15,$Iz01_c_ufident,true,'text',$db_opcao,"");?>
          </td>
   </tr>
  </table>
 </fieldset>

 <fieldset style="width:90%"><legend><b>CNH</b></legend>
  <table border="0"  >
   <tr>
     <td nowrap width=60% title="<?=@$Tz01_v_cnh?>">
          <?=@$Lz01_v_cnh?>
         </td>
         <td nowrap width=40% title="<?=@$Tz01_v_cnh?>">
          <?db_input('z01_v_cnh',15,$Iz01_v_cnh,true,'text',$db_opcao,"");?>
          </td>
          </tr>
          <tr>
          <td>
          <?=@$Lz01_v_categoria?>
          </td>
          <td>
          <?
          $y = array(""=>"","A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","AB"=>"AB","AC"=>"AC","AD"=>"AD","AE"=>"AE");
          db_select('z01_v_categoria',$y,true,$db_opcao);
          ?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tz01_d_dtemissaocnh?>">
          <?=@$Lz01_d_dtemissao?>
         </td>
         <td nowrap title="<?=@$Tz01_d_dtemissaocnh?>">
          <?db_inputdata('z01_d_dtemissaocnh',@$z01_d_dtemissaocnh_dia,@$z01_d_dtemissaocnh_mes,@$z01_d_dtemissaocnh_ano,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tz01_d_dthabilitacao?>">
          <?=@$Lz01_d_dthabilitacao?>
         </td>
         <td nowrap title="<?=@$Tz01_d_dthabilitacao?>">
          <?db_inputdata('z01_d_dthabilitacao',@$z01_d_dthabilitacao_dia,@$z01_d_dthabilitacao_mes,@$z01_d_dthabilitacao_ano,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz01_d_dtvencimento?>">
          <?=@$Lz01_d_dtvencimento?>
         </td>
         <td nowrap title="<?=@$Tz01_d_dtvencimento?>">
          <?db_inputdata('z01_d_dtvencimento',@$z01_d_dtvencimento_dia,@$z01_d_dtvencimento_mes,@$z01_d_dtvencimento_ano,true,'text',$db_opcao);?>
     </td>
   </tr>
  </table>
 </fieldset>
 
 <fieldset style="width:90%"><legend><b>CTPS</b></legend>
  <table border="0" >
    <tr>
     <td nowrap width=60% title="<?=@$Tz01_c_numctps?>">
          <?=@$Lz01_c_numctps?>
         </td>
         <td nowrap width=40% title="<?=@$Tz01_c_numctps?>">
          <?db_input('z01_c_numctps',15,$Iz01_c_numctps,true,'text',$db_opcao,"");?>
         </td>
         </tr>
         <tr>
         <td nowrap title=<?=@$Tz01_c_seriectps?>>
          <?=@$Lz01_c_seriectps?>
         </td>
         <td nowrap title="<?=@$Tz01_c_seriectps?>">
          <?db_input('z01_c_seriectps',15,$Iz01_c_seriectps,true,'text',$db_opcao,"");?>
         </td>
         </tr>
         <tr>
         <td nowrap title="<?=@$Tz01_d_dtemissaoctps?>">
          <?=@$Lz01_d_dtemissaoctps?>
         </td>
         <td nowrap title="<?=@$Tz01_d_dtemissaoctps?>">
         <?db_inputdata('z01_d_dtemissaoctps',@$z01_d_dtemissaoctps_dia,@$z01_d_dtemissaoctps_mes,@$z01_d_dtemissaoctps_ano,true,'text',$db_opcao);?>
         </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz01_c_ufctps?>">
          <?=@$Lz01_c_ufctps?>
         </td>
         <td nowrap title="<?=@$Tz01_c_ufctps?>">
          <?db_input('z01_c_ufctps',15,$Iz01_c_ufctps,true,'text',$db_opcao,"");?>
         </td>
    </tr>
  </table>
 </fieldset>
       <tr align="center" valign="middle">
       <td height="30" colspan="2" nowrap>

      <?
      if (!isset($lReadOnly) || !$lReadOnly) {
      ?>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      }
      ?>

      <input name="z01_i_cgsund" type="hidden"  value="<?=$chavepesquisa?>">
      <input name="z01_v_nome" type="hidden"  value="<?=$z01_v_nome?>">
		<?
		if( isset( $retornacgs ) ){
			echo "<input name='fechar' type='submit' value='Fechar''";
		}
		?>
      
      
      </td>
      </tr>
</td>
</tr>
</table>
</fieldset>
</form>
<script>
 function js_ruas(){
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas(chave,chave1){
   document.form1.z01_v_ender.value = chave1;
   db_iframe_ruas.hide();
 }
 function js_bairro(){
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro(chave,chave1){
  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
 }
 function js_ruas1(){
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
 }
 function js_preenchepesquisaruas1(chave,chave1){
   document.form1.z01_v_endcon.value = chave1;
   db_iframe_ruas1.hide();
 }
 function js_bairro1(){
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
 }
 function js_preenchebairro1(chave,chave1){
  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
 }
 function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund','Pesquisa CGS',true);
 }
 function js_preenchepesquisa(chave){
  db_iframe_cgs_und.hide();
  <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>
 }
 function js_novo(){
  parent.location="sau1_cgs_und000.php?id=1";
 }

</script>