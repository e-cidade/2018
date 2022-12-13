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
$clausencias->rotulo->label();
$clrotulo = new rotulocampo;
//Nome do profissional
$clrotulo->label("z01_nome");
//Unidade/Departamento
$clrotulo->label("descrdepto");
//Rhcbo
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");

//Profissional
$clrotulo->label("sd04_i_medico");
$clrotulo->label("sd04_i_unidade");
//Especmed
$clrotulo->label("sd27_i_codigo");

?>
<form name="form1" method="post">
  <table>
    <tr>
      <td>
         <fieldset><legend><?=converteCodificacao("Ausências");?></legend>
         <center>
         <table border="0">
             <tr>
                 <td nowrap title="<?=@$Tsd04_i_medico?>">
                      <?
                         db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",$db_opcao);
                      ?>
                 </td>
                 <td>
                    <?
                      db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd04_i_medico(false);'");
                      db_input('z01_nome',59,$Iz01_nome,true,'text',3,'');
                     ?>
                 </td>
             </tr>
             <!--  vinculo -->
             <tr>
                 <td nowrap title="<?=@$Tsd06_i_especmed?>">
                     <?
                        db_ancora(@$Lsd06_i_especmed,"js_pesquisasd06_i_especmed(true);",$db_opcao);
                     ?>
                 </td>
                 <td>
                     <?
                       db_input('sd06_i_especmed',10,$Isd06_i_especmed,true,'text',$db_opcao," onchange='js_pesquisasd06_i_especmed(false);'");
                       db_input('rh70_descr',59,$Irh70_descr,true,'text',3,'');
                     ?>
                 </td>
             </tr>
             
             <tr>
                 <td nowrap title="<?=@$Tsd04_i_unidade?>">
                    <?=@$Lsd04_i_unidade?>
                 </td>
                 <td>
                 <?
                    db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',3)
                 ?>
                 <?
                    db_input('descrdepto',59,$Idescrdepto,true,'text',3,'');
                 ?>
                 </td>
             </tr>       
             
             <tr>
                 <td colspan="2" >
                    <fieldset><legend>Lançamento</legend>
                        
                        <table border="0" width="100%">
                            <tr>
                                <td rowspan="2" width="25%"> 
                                    <table border="0"> 
                                      <tr>
                                          <td nowrap title="<?=@$Tsd06_i_codigo?>"><?=@$Lsd06_i_codigo?></td>
                                          <td>
                                             <?
                                                db_input('sd06_i_codigo',10,$Isd06_i_codigo,true,'text',3)
                                             ?>                                          
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="<?=@$Tsd06_i_tipo?>"><?=@$Lsd06_i_tipo?></td>
                                          <td>
                                             <?
					        $sql = $clmotivo_ausencia->sql_query(null,"s139_i_codigo, s139_c_descr","s139_i_codigo");
						//echo $sql;
                                                $resultado = $clmotivo_ausencia->sql_record($sql);
						if($resultado)
						  db_selectrecord('s139_i_codigo',$resultado,true,1,'','sd06_i_tipo','','','',1);
						else
					          db_msgbox("Ocorreu um erro na busca dos motivos de ausencia!");

						//$x = array('1'=>'Folga','2'=>'Férias');
                                                //db_select('sd06_i_tipo',$x,true,$db_opcao,"");
                                             ?>                                             
                                          </td>
                                      </tr>
                                      <tr>
                                          <td nowrap title="Quantidade de Dias"><b>Qtde Dias:</b></td>
                                          <td>
                                             <?
                                                db_input('sd06_i_qtd',10,@$Isd06_i_qtd,true,'text',3)
                                             ?>                                             
                                          </td>
                                      </tr>
                                    </table>
                                </td>
                                <td width="75%"> 
                                    <table border="0">
                                       <tr>
                                            <td> 
                                                <fieldset><legend>Data</legend>
                                                    <table>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tsd06_d_inicio?>"><?=@$Lsd06_d_inicio?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('sd06_d_inicio',@$sd06_d_inicio_dia,@$sd06_d_inicio_mes,@$sd06_d_inicio_ano,true,'text',$db_opcao);
                                                               ?>
                                                            </td>
                                                         </tr>
                                                         <tr>
                                                            <td nowrap title="<?=@$Tsd06_d_fim?>"><?=@$Lsd06_d_fim?></td>
                                                            <td>
                                                               <?
                                                                  db_inputdata('sd06_d_fim',@$sd06_d_fim_dia,@$sd06_d_fim_mes,@$sd06_d_fim_ano,true,'text',$db_opcao );
                                                               ?>
                                                            </td>
                                                         </tr>
                                                    </table>
                                                </fieldset>
                                            </td>
                                            <td>
                                                <fieldset><legend>Horario</legend>
                                                <table>
                                                     <tr>
                                                         <td nowrap title="<?=@$Tsd06_d_inicio?>"><?=@$Lsd06_d_inicio?></td>
                                                         <td><?db_input('sd06_c_horainicio',5,@$Isd06_c_horainicio,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'sd06_c_horainicio', event)\" ");?></td>
                                                     </tr>
                                                     <tr>
                                                         <td nowrap title="<?=@$Tsd06_c_horafim?>"><?=@$Lsd06_c_horafim?></td>
                                                         <td><?db_input('sd06_c_horafim',5,@$Isd06_c_horafim,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'sd06_c_horafim', event)\" ");?></td>
                                                     </tr>
                                                </table>
                                                </fieldset>
                                           </td>
                                      </tr>
                                   </table>
 
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                 </td>
             </tr>
         </table>
         <center>
		<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
		       type="submit" id="db_opcao" onclick="return <?=($db_opcao==1||$db_opcao==2||$db_opcao==22)?'js_validadata()':'confirm(\'Voce quer realmente excluir este registro?\')' ?>"  
		       value="<?=($db_opcao==1?converteCodificacao("Inclusão"):($db_opcao==2||$db_opcao==22?"Alteração":"Exclusão"))?>" >
         <input type="button" name="limpa" value="Limpa" onclick="location.href='sau4_ausencia.php?sd04_i_medico=<?=$sd04_i_medico?>&z01_nome=<?=$z01_nome?>'">
         </center>
         <table width="100%">
           <tr>
              <td valign="top"><br>
                <?
                  $chavepri= array("sd06_i_codigo"=>@$sd06_i_codigo );
                  $cliframe_alterar_excluir->chavepri=$chavepri;
		              @$cliframe_alterar_excluir->sql = $clausencias->sql_query_ext("","sd06_i_codigo, sd04_i_unidade, sd06_d_inicio, sd06_d_fim, sd06_i_especmed,sd06_c_horainicio,sd06_c_horafim, 
                                                                        sau_motivo_ausencia.s139_c_descr as sd06_i_tipo,(sd06_d_fim - sd06_d_inicio) + 1 as sd27_i_quantidade ",
                                                                                           " sd06_i_codigo desc "," sd04_i_medico = $sd04_i_medico");
                  @$cliframe_alterar_excluir->campos  ="sd06_i_codigo, sd06_i_especmed, sd06_i_tipo, sd06_d_inicio, sd06_d_fim, sd06_c_horainicio, sd06_c_horafim, sd27_i_quantidade";
                  $cliframe_alterar_excluir->legenda=converteCodificacao("Ausências");
                  $cliframe_alterar_excluir->alignlegenda = "left";
                  //$cliframe_alterar_excluir->iframe_height ="200";
                  $cliframe_alterar_excluir->iframe_width ="100%";
                  $cliframe_alterar_excluir->tamfontecabec = 9;
                  $cliframe_alterar_excluir->tamfontecorpo = 9;
                  $cliframe_alterar_excluir->formulario = false;
                  @$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao2);
                ?>
               </td>           
           </tr>
         </table>
         
         </fieldset>
      </td>
    </tr>
 </table>
</form>


<script type="text/javascript">

function js_pesquisasd06_i_especmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_sd04_i_medico='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal','Pesquisa',true);
  }else{
     if(document.form1.sd06_i_especmed.value != ''){
        x  = 'func_especmedico.php';
        x += '?chave_sd04_i_medico='+document.form1.sd04_i_medico.value;
        x += '&chave_sd27_i_codigo='+document.form1.sd06_i_especmed.value;
        x += '&funcao_js=parent.js_mostraespecmedico1|sd27_i_codigo|rh70_descr|sd02_i_codigo|descrdepto|sd43_cod_turnat|sd43_v_descricao|sd43_c_horainicial|sd43_c_horafinal';
        js_OpenJanelaIframe('','db_iframe_especmedico',x,'Pesquisa',false);
     }else{
       document.form1.sd06_i_especmed.value = ''; 
     }
  }
}
function js_mostraespecmedico1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8){
  document.form1.sd06_i_especmed.value      = chave1;
  document.form1.rh70_descr.value         = chave2;
  document.form1.sd04_i_unidade.value     = chave3;
  document.form1.descrdepto.value         = chave4;
  
  db_iframe_especmedico.hide();
}



function js_validadata(){
	inicio = new Date(document.form1.sd06_d_inicio.value.substring(6,10),
	                  document.form1.sd06_d_inicio.value.substring(3,5),
	                  document.form1.sd06_d_inicio.value.substring(0,2));
	fim    = new Date(document.form1.sd06_d_fim.value.substring(6,10),
	                  document.form1.sd06_d_fim.value.substring(3,5),
	                  document.form1.sd06_d_fim.value.substring(0,2));
	
	if( inicio > fim ){
		alert('Data de Início esta maior que data Fim.');
		document.form1.sd06_d_inicio.value = '';
		document.form1.sd06_d_fim.value = '';
		document.form1.sd06_d_inicio.focus();
		return false;
	}
	
	return true;
}

function js_pesquisasd06_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?chave_sd04_i_medico='+document.form1.sd06_i_medico.value+'&funcao_js=parent.js_mostraunidademedicos1|sd04_i_unidade|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd06_i_unidade.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_unidademedicos','func_unidademedicos.php?chave_sd04_i_medico='+document.form1.sd06_i_medico.value+'&chave_sd04_i_unidade='+document.form1.sd06_i_unidade.value+'&funcao_js=parent.js_mostraunidademedicos1|sd04_i_unidade|descrdepto','Pesquisa',true);
     }else{
       document.form1.sd06_i_unidade.value = ''; 
     }
  }
}
function js_mostraespecmedico(chave,erro){
  document.form1.sd30_i_undmed.value = chave; 
  if(erro==true){ 
    document.form1.sd06_i_unidade.focus(); 
    document.form1.sd06_i_unidade.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd06_i_unidade.value = chave1;
  document.form1.descrdepto.value     = chave2;
  
  db_iframe_unidademedicos.hide();
}

function js_pesquisasd04_i_medico(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('','db_iframe_medico','func_medicos.php?funcao_js=parent.js_mostramedico1|sd03_i_codigo|z01_nome','Pesquisa',true);
   }else{
      if(document.form1.sd04_i_medico.value != ''){
           js_OpenJanelaIframe('','db_iframe_medico','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedico','Pesquisa',true);
      }else{
          document.form1.z01_nome.value = '';
      }
   }
}
function js_mostramedico(chave1,chave2){
   document.form1.z01_nome.value = chave1;
   if(chave2==true){
      document.form1.sd04_i_medico.focus();
      document.form1.sd04_i_medico.value = '';
   }
   document.forms["form1"].submit();
}
function js_mostramedico1(chave1,chave2){
   document.form1.sd04_i_medico.value = chave1;
   document.form1.z01_nome.value = chave2;
   db_iframe_medico.hide();
   document.forms["form1"].submit();
}
function js_pesquisa(){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedico1|sd04_i_medico|z01_nome','Pesquisa',true);
}
</script>