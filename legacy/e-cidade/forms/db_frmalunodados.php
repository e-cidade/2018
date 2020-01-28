<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: educação
$claluno->rotulo->label();
$clalunoprimat->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("ed47_municipioestrangeiro");
if($db_opcao!=1 && @$ed47_i_codigo!=""){
 $sql = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $ed47_i_codigo";
 $query = db_query($sql);
 $linhas4 = pg_num_rows($query);
 if($linhas4==0){
  $db_botao = true;
 }elseif(db_getsession("DB_coddepto")!=pg_result($query,0,0)){
  $db_botao = false;
 }else{
  $db_botao = true;
 }
}
?>

<div id="ctnCidadao"></div>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
 <tr valign="top">
  <td colspan="2">
   <fieldset><legend><b>Dados Pessoais</b></legend>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
     <tr>
      <td valign="middle" width="15%" align="center">
       <iframe name="frame_imagem" id="frame_imagem" src="edu4_mostraimagem.php" width="110" height="125" frameborder="1" scrolling="no"></iframe>
       <?
       if ((isset($chavepesquisa) || isset($alterar)) && isset($ed47_c_foto)) {

        if ($ed47_o_oid != 0) {

          $arquivo = "tmp/".$ed47_c_foto;

          db_query("begin");
          $lResultExport = pg_lo_export($ed47_o_oid, $arquivo, $conn);
          db_query("end");

          if (!$lResultExport) {

            db_msgbox("Erro ao recuperar o foto do aluno.");

          } elseif (!file_exists($arquivo)) {
            db_msgbox("Foto do aluno não encontrada.");
          }

          if ($db_botao == true) {

          ?>
          <br><input type="button" name="excfoto" value="Excluir Foto"
                     onclick="location.href='edu1_alunodados002.php?excluirfoto&chavepesquisa=<?=$chavepesquisa?>'"
                     style="font-size:9px;height:14px;padding:0px;">
          <?

         }

        }else{
         $arquivo = "imagens/none1.jpeg";
        }
        ?>
        <script>
        frame_imagem.location.href="edu4_mostraimagem.php?imagem_gerada=<?=$arquivo?>";
        </script>
       <?}?>
      </td>
      <td valign="top">
       <table border="0" cellspacing="1" cellpadding="0" width="100%">
        <tr>
         <td>
          <?=$Led47_i_codigo?>
         </td>
         <td>
          <?db_input('ed47_i_codigo',20,$Ied47_i_codigo,true,'text',3);?>
          <?=@$Led47_c_codigoinep?>
          <?db_input('ed47_c_codigoinep',12,$Ied47_c_codigoinep,true,'text',$db_opcao,'')?>
          <?=@$Led47_c_nis?>
          <?db_input('ed47_c_nis',11,$Ied47_c_nis,true,'text',$db_opcao,"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_v_nome?>
         </td>
         <td>
          <?db_input('ed47_v_nome',70,$Ied47_v_nome,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_nome]','f','t',event);\"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=$Led47_d_nasc?>
         </td>
         <td>
          <?db_inputdata('ed47_d_nasc',@$ed47_d_nasc_dia,@$ed47_d_nasc_mes,@$ed47_d_nasc_ano,true,'text',$db_opcao, "onchange='validaIdade();'", '', '', '','', '', "validaIdade()");?>
          <b> Idade: </b>
          <?php db_input('idade',25,'',true,'text',3,"");?>
          <?=$Led47_v_sexo?>
          <?
          $sex = array(""=>"","M"=>"Masculino","F"=>"Feminino");
          db_select('ed47_v_sexo',$sex,true,$db_opcao);
          ?>
          <?php
            $aTipoSanguineo       = array();
            $aTipoSanguineo[ "" ] = "";

            echo $Led47_tiposanguineo;

            $oDaoTipoSanguineo   = new cl_tiposanguineo();
            $sSqlTipoSanguineo   = $oDaoTipoSanguineo->sql_query_file();
            $rsTipoSanguineo     = db_query( $sSqlTipoSanguineo );
            $iTotalTipoSanguineo = pg_num_rows( $rsTipoSanguineo );

            for( $iContador = 0; $iContador < $iTotalTipoSanguineo; $iContador++ ) {

              $oDadosTipoSanguineo                                      = db_utils::fieldsMemory( $rsTipoSanguineo, $iContador );
              $aTipoSanguineo[ $oDadosTipoSanguineo->sd100_sequencial ] = $oDadosTipoSanguineo->sd100_tipo;
            }

            db_select( 'ed47_tiposanguineo', $aTipoSanguineo, true, $db_opcao );
          ?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_i_filiacao?>
         </td>
         <td>
          <?
          $fil = array("0"=>"NÃO DECLARADO / IGNORADO","1"=>"PAI E/OU MÃE");
          db_select('ed47_i_filiacao',$fil,true,$db_opcao," onchange='js_filiacao(this.value)'");
          ?>
          <?=@$Led47_c_raca?>
          <?
          $x = array('NÃO DECLARADA'=>'NÃO DECLARADA','BRANCA'=>'BRANCA','PRETA'=>'PRETA','PARDA'=>'PARDA','AMARELA'=>'AMARELA','INDÍGENA'=>'INDÍGENA');
          db_select('ed47_c_raca',$x,true,$db_opcao,"");

          echo $Led47_i_estciv;
          $x = array("1"=>"Solteiro","2"=>"Casado","3"=>"Viúvo","4"=>"Divorciado");
          db_select('ed47_i_estciv',$x,true,$db_opcao);
          ?>
         </td>
        </tr>
        <tr>
         <td nowrap title="<?=@$Ted47_c_foto?>">
          <b>Foto:</b>
         </td>
         <td colspan="2">
          <iframe name="frame_file" id="frame_file" src="edu1_framefile.php" width="100%" height="25" frameborder="0" scrolling="no"></iframe>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr>
   <td colspan="2">
     <fieldset>
       <legend class="bold">Filiação 1</legend>
       <table>
         <tr style="display: none;">
           <td><label class="bold">Código Cidadão:</label></td>
           <td id="inputCodigoCidadaoMae"></td>
         </tr>
         <tr>
           <td><a href="#" onclick='js_pesquisaCidadao(true, $("oInputCpfMae"));' class="bold">CPF: </a></td>
           <td id="inputCpfMae"></td>
           <td><label class="bold">Nome:</label></td>
           <td>
             <?
               db_input('ed47_v_mae',
                        70,
                        $Ied47_v_mae,
                        true,
                        'text',
                        $iOpcaoFiliacao,
                        " onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_mae]','f','t',event);\"");
             ?>
           </td>
           <td>
             <input id="btnCidadaoMae" class='btnCidadao' type="button" value="Cidadão" onclick="js_abreTelaCidadao($('oInputCpfMae'))" />
           </td>
         </tr>
       </table>
     </fieldset>
   </td>
 </tr>
 <tr>
   <td colspan="2">
     <fieldset>
       <legend class="bold">Filiação 2</legend>
       <table>
         <tr style="display:none ">
           <td><label class="bold">Código Cidadão:</label></td>
           <td id="inputCodigoCidadaoPai"></td>
         </tr>
         <tr>
           <td><a href="#" onclick='js_pesquisaCidadao(true, $("oInputCpfPai"));' class="bold">CPF: </a></td>
           <td id="inputCpfPai"></td>
           <td><label class="bold">Nome:</label></td>
           <td>
             <?
               db_input('ed47_v_pai',
                        70,
                        $Ied47_v_pai,
                        true,
                        'text',
                        $iOpcaoFiliacao,
                        " onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_pai]','f','t',event);\"");
             ?>
           </td>
           <td>
             <input id="btnCidadaoPai" class='btnCidadao' type="button" value="Cidadão" onclick="js_abreTelaCidadao($('oInputCpfPai'))" />
           </td>
         </tr>
       </table>
     </fieldset>
   </td>
 </tr>
 <tr>
   <td colspan="2">
     <fieldset>
       <legend class="bold">Responsável</legend>
       <table>
         <tr style="display:none ">
           <td><label class="bold">Código Cidadão:</label></td>
           <td id="inputCodigoCidadaoResponsavel"></td>
         </tr>
         <tr>
           <td><a href="#" onclick='js_pesquisaCidadao(true, $("oInputCpfResponsavel"));' class="bold">CPF: </a></td>
           <td id="inputCpfResponsavel"></td>
           <td><label class="bold">Nome:</label></td>
           <td>
             <?
               db_input('ed47_c_nomeresp',
                        70,
                        $Ied47_c_nomeresp,
                        true,
                        'text',
                        $iOpcaoFiliacao,
                        " onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_c_nomeresp]','f','t',event);\"");
             ?>
           </td>
           <td>
             <input id="btnCidadaoResponsavel" class='btnCidadao' type="button" value="Cidadão" onclick="js_abreTelaCidadao($('oInputCpfResponsavel'))" />
           </td>
         </tr>
       </table>
     </fieldset>
   </td>
 </tr>
 <tr>
   <td colspan="2">
     <fieldset>
       <legend>Dados Contato</legend>
       <table>
         <tr>
           <td><label class="bold">Contato:</label></td>
           <td id="cboContato"></td>
         </tr>
         <tr>
           <td><label class="bold">Email do contato:</label></td>
           <td>
            <?php
              db_input('ed47_c_emailresp',40,$Ied47_c_emailresp,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,4,'$GLOBALS[Sed47_c_emailresp]','f','t',event);\"");
            ?>
           </td>
         </tr>
         <tr>
           <td><label class="bold">Celular do contato:</label></td>
           <td>
            <?php
              if (isset($ed47_celularresponsavel)) {

                $dddcelularresponsavel   = substr($ed47_celularresponsavel, 0, 2);
                $ed47_celularresponsavel = substr($ed47_celularresponsavel, 2, strlen($ed47_celularresponsavel));
              }
              db_input('dddcelularresponsavel', 2, $Ied47_celularresponsavel,true,'text',$db_opcao);
              db_input('ed47_celularresponsavel', 8,$Ied47_celularresponsavel,true,'text',$db_opcao);
            ?>
           </td>
         </tr>
       </table>
     </fieldset>
   </td>
 </tr>
 <tr valign="top">
  <td width="45%" valign="top">
   <fieldset style="height:240px"><legend><b>Endereço Residencial / Contato do Aluno</b></legend>
    <table border="0" cellspacing="1" cellpadding="0" width="100%">
     <tr>
      <td colspan="2">
       <b>Libera Endereço:</b>
       <?
       $x = array("N"=>"NÃO","S"=>"SIM");
       db_select('liberaendereco',$x,true,$db_opcao," onchange='LiberaEndereco(this.value);'");
       ?>
       <b>Libera Bairro:</b>
       <?
       $b = array("N"=>"NÃO","S"=>"SIM");
       db_select('liberabairro',$b,true,$db_opcao," onchange='LiberaBairro(this.value);'");
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?db_ancora(@$Led47_v_ender,"js_ruas();",$db_opcao);?>
      </td>
      <td>
       <?db_input('ed47_v_ender',40,$Ied47_v_ender,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_v_ender]','f','t',event);\"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_numero?>
      </td>
      <td>
       <?db_input('ed47_c_numero',10,$Ied47_c_numero,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_c_numero]','t','t',event);\"")?>
       &nbsp;
       <?=@$Led47_v_compl?>
       <?db_input('ed47_v_compl',20,$Ied47_v_compl,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_v_compl]','t','t',event);\"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censoufend?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed47_i_censoufend",$result_uf,"","","","","","  ","iframe_uf.location.href='edu1_aluno004.php?campo=end&censouf='+this.value",1);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censomunicend?>
      </td>
      <td>
       <?
       if(isset($ed47_i_censoufend) && $ed47_i_censoufend!=""){
        $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $ed47_i_censoufend"));
        if($clcensomunic->numrows==0){
         $x = array(' '=>'Selecione o Estado');
         db_select('ed47_i_censomunicend',$x,true,@$db_opcao,"");
        }else{
         db_selectrecord("ed47_i_censomunicend",$result_munic,"","","","","","  ","",1);
        }
       }else{
        $x = array(' '=>'Selecione o Estado');
        db_select('ed47_i_censomunicend',$x,true,@$db_opcao,"");
       }
       ?>
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr>
      <td>
       <?db_ancora(@$Led47_v_bairro,"js_bairro();",$db_opcao);?>
      </td>
      <td>
       <?db_input('j13_codi',10,$Ij13_codi,true,'text',3);?>
      <?db_input('ed47_v_bairro',25,$Ied47_v_bairro,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_zona?>
      </td>
      <td>
       <?
       $x = array('URBANA'=>'Urbana','RURAL'=>'Rural');
       db_select('ed47_c_zona',$x,true,$db_opcao,"");
       ?>
       <?=@$Led47_v_cep?>
       <?db_input('ed47_v_cep',8,$Ied47_v_cep,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_telef?>
      </td>
      <td>
       <?db_input('ed47_v_telef',12,$Ied47_v_telef,true,'text',$db_opcao);?>
       <?=@$Led47_v_telcel?>
       <?db_input('ed47_v_telcel',12,$Ied47_v_telcel,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_fax?>
      </td>
      <td>
       <?db_input('ed47_v_fax',12,$Ied47_v_fax,true,'text',$db_opcao);?>
       <?=@$Led47_v_cxpostal?>
       <?db_input('ed47_v_cxpostal',10,$Ied47_v_cxpostal,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_email?>
      </td>
      <td>
       <?db_input('ed47_v_email',30,$Ied47_v_email,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,4,'$GLOBALS[Sed47_v_email]','f','t',event);\"");?>
      </td>
     </tr>
    </table>
   </fielset>
  </td>
  <td valign="top">
   <fieldset style="height:240px"><legend><b>Outras Informações</b></legend>
    <table border="0" cellspacing="1" cellpadding="0">
     <tr>
      <td>
       <?=$Led47_i_nacion?>
      </td>
      <td>
       <?
       $x = array("1"=>"Brasileira","2"=>"Brasileira no Exterior ou Naturalizado","3"=>"Estrangeira");
       db_select('ed47_i_nacion',$x,true,$db_opcao," onchange='js_nacionalidade(this.value)'");
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_i_pais?>
      </td>
      <td>
       <?
       if(!isset($ed47_i_pais)){
        $ed47_i_pais = 10;
       }
       $result_pais = $clpais->sql_record($clpais->sql_query_file("","ed228_i_codigo,ed228_c_descr","ed228_c_descr",""));
       if($clpais->numrows==0){
        $x = array(''=>'NENHUM REGISTRO');
        db_select('ed47_i_pais',$x,true,$db_opcao,"");
       }else{
        db_selectrecord("ed47_i_pais",$result_pais,"",$db_opcao,"","","","  ","","");
       }
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censoufnat?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed47_i_censoufnat",$result_uf,"","","","","","  ","iframe_uf.location.href='edu1_aluno004.php?campo=nat&censouf='+this.value",1);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censomunicnat?>
      </td>
      <td>
       <?
       if(isset($ed47_i_censoufnat) && trim($ed47_i_censoufnat)!=""){
        $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $ed47_i_censoufnat"));
        if($clcensomunic->numrows==0){
         $x = array(' '=>'Selecione o Estado');
         db_select('ed47_i_censomunicnat',$x,true,@$db_opcao,"");
        }else{
         db_selectrecord("ed47_i_censomunicnat",$result_munic,"","","","","","  ","",1);
        }
       }else{
        $x = array(' '=>'Selecione o Estado');
        db_select('ed47_i_censomunicnat',$x,true,@$db_opcao,"");
       }
       ?>
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr id="linhaMunicipioEstrangeiro" style="display: none;">
      <td>
        <label for="ed47_municipioestrangeiro"> <?=$Led47_municipioestrangeiro?> </label>
      </td>
      <td>
        <?php db_input('ed47_municipioestrangeiro',30,$Ied47_municipioestrangeiro, true, 'text',$db_opcao); ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_i_transpublico?>
      </td>
      <td>
       <?
       $x = array("0"=>"Não Utiliza","1"=>"Utiliza");
       db_select('ed47_i_transpublico',$x,true,$db_opcao);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_transporte?>
      </td>
      <td>
       <?
       $x = array(''=>'','1'=>'Estadual','2'=>'Municipal');
       db_select('ed47_c_transporte',$x,true,$db_opcao,"");
       ?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <?=$Led47_c_atenddifer?>
       <?
       $x = array("3"=>"Não Recebe","1"=>"Em Hospital","2"=>"Em Domicílio");
       db_select('ed47_c_atenddifer',$x,true,$db_opcao);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_bolsafamilia?>
      </td>
      <td>
       <?
       $x = array('N'=>'NÃO','S'=>'SIM');
       db_select('ed47_c_bolsafamilia',$x,true,$db_opcao,"");
       ?>
       <?db_input('ed47_i_atendespec',10,$Ied47_i_atendespec,true,'hidden',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_v_profis?>
      </td>
      <td>
       <?db_input('ed47_v_profis',40,$Ied47_v_profis,true,'text',$db_opcao);?>
      </td>
     </tr>
    </table>
   </fielset>
  </td>
 </tr>
 <tr valign="top">
  <td colspan="2">
   <fieldset style="<?=isset($leitor)?"visibility:hidden;position:absolute":""?>"><legend><b>Procedência do Aluno</b></legend>
    <table width="100%">
     <tr>
      <td nowrap title="<?=@$Ted76_i_escola?>">
       <?db_ancora(@$Led76_i_escola,"js_pesquisaed76_i_escola(true);",$db_opcao);?>
       <?db_input('ed76_i_escola',20,$Ied76_i_escola,true,'text',3," onchange='js_pesquisaed76_i_escola(false);'")?>
       <?db_input('nomeescola',40,@$Inomeescola,true,'text',3,"")?>
       <?db_input('ed76_c_tipo',10,@$Ied76_c_tipo,true,'hidden',3,"")?>
       <?db_input('ed76_i_codigo',20,@$Ied76_i_codigo,true,'hidden',3,"")?>
       <input type="button" name="limpar" value="Limpar" onclick="document.form1.ed76_i_escola.value='';document.form1.nomeescola.value='';document.form1.ed76_c_tipo.value='';">
       <?=@$Led76_d_data?>
       <?db_inputdata('ed76_d_data',@$ed76_d_data_dia,@$ed76_d_data_mes,@$ed76_d_data_ano,true,'text',$db_opcao);?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr align="center">
  <td height="30">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
          type="submit"
          id="db_opcao"
          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>
          onclick="return js_valida();">
   <?if(!isset($leitor)){?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
   <?}else{?>
    <input name="leitor" type="hidden" value="<?=$leitor?>">
   <?}?>
   <input name="ed47_o_oid" type="hidden" id="ed47_o_oid" value="<?=@$ed47_c_foto?>" size="30">
  </td>
  <td align="right">
   <?=@$Led47_d_cadast?>
   <?db_inputdata('ed47_d_cadast',@$ed47_d_cadast_dia,@$ed47_d_cadast_mes,@$ed47_d_cadast_ano,true,'text',3);?>
   <?=@$Led47_d_ultalt?>
   <?db_inputdata('ed47_d_ultalt',@$ed47_d_ultalt_dia,@$ed47_d_ultalt_mes,@$ed47_d_ultalt_ano,true,'text',3);?>
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
function js_pesquisaed76_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escola','func_escolaproced.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome|tipoescola','Pesquisa de Escolas',true);
 }
}
function js_mostraescola1(chave1,chave2,chave3){
 document.form1.ed76_i_escola.value = chave1;
 document.form1.nomeescola.value = chave2;
 document.form1.ed76_c_tipo.value = chave3;
 db_iframe_escola.hide();
}
function js_pesquisa() {

 js_OpenJanelaIframe('','db_iframe_aluno',
                     'func_aluno.php?funcao_js=parent.js_preenchepesquisa|ed47_i_codigo','Pesquisa Alunos',
                      true
                    );
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

function LiberaBairro(valor){
 if(valor=="S"){
  document.form1.ed47_v_bairro.readOnly = false;
  document.form1.ed47_v_bairro.style.background = "#FFFFFF";
  document.links[1].style.color = "#000000";
  document.links[1].style.textDecoration = "none";
  document.links[1].href = "";
 }else if(valor=="N"){
  document.form1.ed47_v_bairro.readOnly = true;
  document.form1.ed47_v_bairro.style.background = "#DEB887";
  document.links[1].style.color = "blue";
  document.links[1].style.textDecoration = "underline";
  document.links[1].href = "#";
 }
}
function js_preenchepesquisa(chave){

  db_iframe_aluno.hide();
  <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>
}
function js_novo(){
 parent.document.formaba.a2.disabled = true;
 parent.document.formaba.a3.disabled = true;
 parent.document.formaba.a4.disabled = true;
 parent.document.formaba.a5.disabled = true;
 parent.document.formaba.a6.disabled = true;
 location.href = "edu1_alunodados001.php";
}
<?if($db_opcao==1||$db_opcao==2){?>
LiberaEndereco("N");
<?}?>

function js_valida(){

  datanasc = document.form1.ed47_d_nasc.value;

  if (empty(datanasc)) {

    alert(_M('educacao.escola.db_frmalunodados.data_nascimento_nao_informada'));
    return false;
  }

  if (datanasc != "") {

    dianasc = datanasc.substr(0,2);
    mesnasc = datanasc.substr(3,2);
    anonasc = datanasc.substr(6,4);
    data_hj = <?=date("Y").date("m").date("d")?>;

    if (anonasc < 1918) {

      alert("Ano da Data de Nascimento deve ser maior que 1917!");
      return false;
    }

    data_nasc = anonasc+""+mesnasc+""+dianasc;

    if (parseInt(data_nasc) >= parseInt(data_hj)) {

      alert("Data de Nascimento deve ser menor que a data corrente!");
      return false;
    }
  }

  if ( empty($('ed47_v_sexo').value) ) {

    alert(_M('educacao.escola.db_frmalunodados.sexo_nao_informado'));
    return false;
  }

  filiacao = document.form1.ed47_i_filiacao.value;
  pai = document.form1.ed47_v_pai.value;
  mae = document.form1.ed47_v_mae.value;
  if(filiacao==0 && ( pai!="" || mae!="" )){
   alert("Campo Filiação definido como Não Declarado / Ignorado!\nFiliação 1 e Filiação 2 NÃO devem ser preenchidos.");
   return false;
  }
  if(filiacao==1 && pai=="" && mae=="" ){
   alert("Campo Filiação definido como Pai e /ou Mãe!\nFiliação 1 e/ou Filiação 2 deve ser preenchido.");
   return false;
  }
  if(pai!="" && mae!="" && pai==mae){
   alert("Campos Filiação 1 e Filiação 2 devem ser diferentes!");
   return false;
  }
  nacion = document.form1.ed47_i_nacion.value;
  pais = document.form1.ed47_i_pais.value;
  if((nacion==1 || nacion==2) && pais!=10){
   alert("Campo País deve ser BRASIL quando nacionalidade for Brasileira ou Brasileira no Exterior!");
   return false;
  }
  if(nacion==3 && pais==10){
   alert("Campo País deve ser diferente de BRASIL quando nacionalidade for Estrangeira!");
   return false;
  }
  if(pais==" "){
    alert("Campo País não informado!");
    return false;
  }
  naturalidade = document.form1.ed47_i_censomunicnat.value;
  naturalidadeuf = document.form1.ed47_i_censoufnat.value;
  if(nacion==1 && (naturalidade==" " || naturalidadeuf==" ")){
   alert("Campos UF de Nascimento e Naturalidade devem ser preenchidos quando nacionalidade for Brasileira!");
   return false;
  }
  if(nacion!=1 && (naturalidade!=" " || naturalidadeuf!=" ")){
   alert("Campos UF de Nascimento e Naturalidade NÃO devem ser preenchidos quando nacionalidade for diferente de Brasileira!");
   return false;
  }
  cep = document.form1.ed47_v_cep.value;
  end = document.form1.ed47_v_ender.value;
  num = document.form1.ed47_c_numero.value;
  com = document.form1.ed47_v_compl.value;
  bai  = document.form1.ed47_v_bairro.value;
  uf = document.form1.ed47_i_censoufend.value;
  mun = document.form1.ed47_i_censomunicend.value;
  if(cep=="" && (end!="" || num!="" || com!="" || bai!="" || uf!=" " || mun!=" ") ){
   alert("Campo CEP deve ser informado quando\num dos campos abaixo estiverem informados:\n\nEndereço\nNúmero\nComplemento\nBairro\nUF\nMunicípio");
   return false;
  }
  if(end=="" && (cep!="" || uf!=" " || mun!=" ") ){
   alert("Campo Endereço deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nUF\nMunicípio");
   return false;
  }
  if(uf==" " && (cep!="" || mun!=" ") ){
   alert("Campo UF deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nMunicípio");
   return false;
  }
  if(mun==" " && (cep!="" || uf!=" ") ){
   alert("Campo Município deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nUF");
   return false;
  }
  if(num!="" && cep=="" && end=="" && uf==" " && mun==" "){
   alert("Campo Número só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
   return false;
  }
  if(com!="" && cep=="" && end=="" && uf==" " && mun==" "){
   alert("Campo Complemento só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
   return false;
  }
  if(bai!="" && cep=="" && end=="" && uf==" " && mun==" "){
   alert("Campo Bairro só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
   return false;
  }
  if(cep!="" && cep.length!=8){
   alert("Campo CEP deve conter 8 dígitos!");
   return false;
  }
  if(document.form1.ed47_i_transpublico.value==0 && document.form1.ed47_c_transporte.value!=""){
   alert("Campo Poder Publico Responsável só pode ser informado quando campo Transporte Escolar Público for igual a Utiliza!");
   return false;
  }
  if(document.form1.ed47_i_transpublico.value==1 && document.form1.ed47_c_transporte.value==""){
   alert("Campo Poder Publico Responsável deve ser informado quando campo Transporte Escolar Público for igual a Utiliza!");
   return false;
  }

  if ( empty(bai) ) {

    alert(_M('educacao.escola.db_frmalunodados.bairro_nao_informado'));
    return false;
  }

  <?if(!isset($leitor)){?>
  if(parent.iframe_a2.document.form1.ed47_c_passaporte && document.form1.ed47_i_nacion.value!=3 && parent.iframe_a2.document.form1.ed47_c_passaporte.value!=""){
   alert("Aluno com nacionalidade Brasileira ou Brasileira no Exterior NÃO deve ter o campo Passaporte informado (Aba Documentos)!");
   return false;
  }
  if($('dddcelularresponsavel').value != '' && $('ed47_celularresponsavel').value == '') {

    alert('Deve ser informado o celular do responsável ao informar o DDD.');
    return false;
  }
  if($('dddcelularresponsavel').value == '' && $('ed47_celularresponsavel').value != '') {

    alert('Deve ser informado o DDD ao informar o celular do responsável.');
    return false;
  }

  aInicioCelular = new Array('7', '8', '9');
  if ($F('ed47_celularresponsavel').trim() != "" && !js_search_in_array(aInicioCelular, $F('ed47_celularresponsavel').substring(0, 1))) {

    alert('Número informado não é um celular válido');
    return false;
  }

  <?}?>
  Vemailresp = "<?=@$GLOBALS[Sed47_c_emailresp]?>";
  Vemail = "<?=@$GLOBALS[Sed47_v_email]?>";
  if(jsValidaEmail(document.form1.ed47_c_emailresp.value,Vemailresp)==false){
   return false;
  }
  if(jsValidaEmail(document.form1.ed47_v_email.value,Vemail)==false){
   return false;
  }
  return true;
}

function js_filiacao(valor) {

  if (valor == 0) {

    document.form1.ed47_v_pai.readOnly = true;
    document.form1.ed47_v_mae.readOnly = true;
    document.form1.ed47_v_pai.value = "";
    document.form1.ed47_v_mae.value = "";

    $('oInputCpfPai').readOnly         = true;
    $('oInputCpfPai').style.background = "#DEB887";
    $('btnCidadaoPai').disabled        = true;

    $('oInputCpfMae').readOnly         = true;
    $('oInputCpfMae').style.background = "#DEB887";
    $('btnCidadaoMae').disabled        = true;
  } else {

    document.form1.ed47_v_pai.readOnly = false;
    document.form1.ed47_v_mae.readOnly = false;

    $('oInputCpfPai').readOnly         = false;
    $('oInputCpfPai').style.background = "white";
    $('btnCidadaoPai').disabled        = false;

    $('oInputCpfMae').readOnly         = false;
    $('oInputCpfMae').style.background = "white";
    $('btnCidadaoMae').disabled        = false;
  }
}

function js_nacionalidade( valor ) {

  apresentaNascionalidade();

  if( valor == 3 && document.form1.ed47_i_codigo.value != "" ) {
    iframe_uf.location.href='edu1_aluno004.php?nacionalidade='+document.form1.ed47_i_codigo.value;
  }
}

<?if(isset($ed47_i_codigo) && $ed47_i_codigo!=""){?>

  <?
  $sql = "SELECT ed18_i_censomunic as cod_munic FROM escola WHERE ed18_i_codigo = ".db_getsession("DB_coddepto");
  $query = db_query($sql);
  db_fieldsmemory($query,0);
  if($cod_munic!=$ed47_i_censomunicend){

    ?>
    LiberaEndereco("S");
    LiberaBairro("S");
    document.form1.liberaendereco.value = "S";
    document.form1.liberabairro.value = "S";
    <?

  }

}
?>
$('dddcelularresponsavel').maxLength   = 2;
$('ed47_celularresponsavel').maxLength = 9;

function validaIdade() {

  var oIdade = js_idade($F('ed47_d_nasc_dia'), $F('ed47_d_nasc_mes'), $F('ed47_d_nasc_ano'));

  $('idade').value = oIdade.string;
  if ($F('ed47_d_nasc') == '') {
    $('idade').value = '';
  }

}

var iOpcaoFiliacao = <?=$iOpcaoFiliacao;?>;

/**
 * Elementos input dos códigos e cpf da mãe, pai e responsável
 */
var oInputCodigoMae      = document.createElement('input');
    oInputCodigoMae.id   = 'oInputCodigoMae';
    oInputCodigoMae.name = 'oInputCodigoMae';
$('inputCodigoCidadaoMae').appendChild(oInputCodigoMae);

var oInputCodigoPai      = document.createElement('input');
    oInputCodigoPai.id   = 'oInputCodigoPai';
    oInputCodigoPai.name = 'oInputCodigoPai';
$('inputCodigoCidadaoPai').appendChild(oInputCodigoPai);

var oInputCodigoResponsavel      = document.createElement('input');
    oInputCodigoResponsavel.id   = 'oInputCodigoResponsavel';
    oInputCodigoResponsavel.name = 'oInputCodigoResponsavel';
$('inputCodigoCidadaoResponsavel').appendChild(oInputCodigoResponsavel);

var oInputCpfMae             = document.createElement('input');
    oInputCpfMae.id          = 'oInputCpfMae';
    oInputCpfMae.style.width = '100px';
$('inputCpfMae').appendChild(oInputCpfMae);

var oInputCpfPai             = document.createElement('input');
    oInputCpfPai.id          = 'oInputCpfPai';
    oInputCpfPai.style.width = '100px';
$('inputCpfPai').appendChild(oInputCpfPai);

var oInputCpfResponsavel             = document.createElement('input');
    oInputCpfResponsavel.id          = 'oInputCpfResponsavel';
    oInputCpfResponsavel.style.width = '100px';
$('inputCpfResponsavel').appendChild(oInputCpfResponsavel);

/**
 * Eventos a serem observados dos inputs de CPF
 */
$('oInputCpfMae').observe("change", function() {

  if (empty($('oInputCpfMae').value)) {

    oInputCodigoMae.value                 = '';
    $('ed47_v_mae').value                 = '';
    $('ed47_v_mae').readOnly              = false;
    $('ed47_v_mae').style.backgroundColor = '#E6E4F1';
  } else {
    js_pesquisaCidadao(false, this);
  }
});

$('oInputCpfMae').observe('keyup', function() {
  $('oInputCpfMae').value = $('oInputCpfMae').value.replace(/[^0-9]/, '');
});

$('oInputCpfPai').observe("change", function() {

  if (empty($('oInputCpfPai').value)) {

    oInputCodigoPai.value                 = '';
    $('ed47_v_pai').value                 = '';
    $('ed47_v_pai').readOnly              = false;
    $('ed47_v_pai').style.backgroundColor = '#E6E4F1';
  } else {
    js_pesquisaCidadao(false, this);
  }
});

$('oInputCpfPai').observe('keydown', function() {
  $('oInputCpfPai').value = $('oInputCpfPai').value.replace(/[^0-9]/, '');
});

$('oInputCpfResponsavel').observe("change", function() {

  if (empty($('oInputCpfResponsavel').value)) {

    oInputCodigoResponsavel.value              = '';
    $('ed47_c_nomeresp').value                 = '';
    $('ed47_c_nomeresp').readOnly              = false;
    $('ed47_c_nomeresp').style.backgroundColor = '#E6E4F1';
  } else {
    js_pesquisaCidadao(false, this);
  }
});

$('oInputCpfResponsavel').observe('keydown', function() {
  $('oInputCpfResponsavel').value = $('oInputCpfResponsavel').value.replace(/[^0-9]/, '');
});

<?php if ($db_opcao == 2 ) { ?>
validaIdade();
js_filiacao($F('ed47_i_filiacao'));
<?php }?>

/**
 * Elemento select para escolha de quem é o contato do aluno
 */
var oSelectContato             = document.createElement('select');
    oSelectContato.id          = 'oSelectContato';
    oSelectContato.name        = 'oSelectContato';
    oSelectContato.style.width = '117px';
    oSelectContato.add(new Option('', ''));
    oSelectContato.add(new Option('Filiação 1', 1));
    oSelectContato.add(new Option('Filiação 2', 2));
    oSelectContato.add(new Option('Responsável', 3));
$('cboContato').appendChild(oSelectContato);

$('oSelectContato').observe("change", function() {

  $('ed47_c_emailresp').value        = '';
  $('dddcelularresponsavel').value   = '';
  $('ed47_celularresponsavel').value = '';
  if ( !empty($('oSelectContato').value) ) {
    js_buscaDadosContatoResponsavel( $('oSelectContato').value );
  }
});

require_once('scripts/classes/cidadao/DBViewCidadao.classe.js');
require_once('scripts/classes/cidadao/Cidadao.classe.js');

var oElemento   = null;
var oCidadao    = null;
var sRpcCidadao = 'ouv4_cidadao.RPC.php';

/**
 * Pesquisa um cidadão pelo CPF
 * @param boolen lMostra - Controla se a lookup deve ser apresentada ou não
 * @param Object oInputCpf - Elemento que originou a chamada da lookup
 */
function js_pesquisaCidadao(lMostra, oInputCpf) {

  if (oInputCpf.id != 'oInputCpfResponsavel' && $F('ed47_i_filiacao') == '0') {
    return false;
  }
  oElemento = oInputCpf;
  var sUrl = 'func_cidadao.php?funcao_js=parent.js_retornoCidadao';

  if (lMostra) {
    sUrl += '|ov02_sequencial|ov02_nome|ov02_cnpjcpf';
  } else {

    if (!empty(oInputCpf.value)) {
      sUrl += '&pesquisa_chave='+oInputCpf.value+'&lPesquisaCpf=1';
    } else {

      oInputCpf.value = '';
      $(oInputCpf.id.replace('oInputCpf', 'oInputCodigo')).value = '';
    }
  }

  js_OpenJanelaIframe('', 'db_iframe_cidadao', sUrl, 'Pesquisa Cidadão', lMostra);
}

/**
 * Retorno da dos dados do cidadão. Caso não exista cidadão pelo CPF informado, abre o cadastro do cidadão.
 */
function js_retornoCidadao() {

  db_iframe_cidadao.hide();

  var oValores          = new Object();
      oValores.iCidadao = '';
      oValores.sNome    = '';
      oValores.sCpf     = '';

  if (arguments[2] !== false && arguments[2] !== true) {

    oValores.iCidadao = arguments[0];
    oValores.sNome    = arguments[1];
    oValores.sCpf     = arguments[2];
    js_atribuiValoresFiliacao(oElemento, oValores);

    js_buscaDadosContatoResponsavel();
  } else {

    if (arguments[2] === true) {

      oValores.sCpf = oElemento.value;
      js_atribuiValoresFiliacao(oElemento, oValores);
      js_condicaoNomeFiliacao(oElemento);

      oCidadao = js_abreTelaCidadao(oElemento);
      oCidadao.setCpfCnpj(oElemento.value);
    } else {

      oValores.iCidadao = arguments[0];
      oValores.sCpf     = arguments[3];
      oValores.sNome    = arguments[1];

      js_atribuiValoresFiliacao(oElemento, oValores);
    }
  }

  if (oElemento.id == 'oInputCpfResponsavel' && empty($('ed47_v_ender').value)) {
    js_buscaEnderecoCidadao(arguments[0]);
  }
}

/**
 * Atribui os valores da filiação, validando pelo elemento input do CPF
 * @param Object oElementoCidadao - Elemento input do CPF para validação e preenchimento dos demais campos da filiação
 * @param Object oValores         - Objecto com os valores a serem preenchidos
 */
function js_atribuiValoresFiliacao(oElementoCidadao, oValores) {

  if (oElementoCidadao.id.replace('oInputCpf', '').toLowerCase() == 'responsavel') {

    $('ed47_c_nomeresp').value                 = oValores.sNome;
    $('ed47_c_nomeresp').readOnly              = true;
    $('ed47_c_nomeresp').style.backgroundColor = '#DEB887';
  } else {

    $(oElementoCidadao.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).setAttribute('value', oValores.sNome);
    $(oElementoCidadao.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).value                 = oValores.sNome;
    $(oElementoCidadao.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).readOnly              = true;
    $(oElementoCidadao.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).style.backgroundColor = '#DEB887';
  }

  oElementoCidadao.value = oValores.sCpf;
  $(oElementoCidadao.id.replace('oInputCpf', 'oInputCodigo')).value = oValores.iCidadao;
}

/**
 * Carrega a tela do cidadão ao clicar no botão de acordo com o tipo do elemento passado
 * @param Object oElementoCidadao - Elemento input do CPF para validação e preenchimento dos demais campos da filiação
 */
function js_abreTelaCidadao(oElementoCidadao) {

  oCidadao = new DBViewCidadao.Cidadao($(oElementoCidadao.id.replace('oInputCpf', 'oInputCodigo')).value,
                                                                     $('ctnCidadao'), 'oCidadao');

  /**
   * Verifica qual a filiação de origem do click do botão Cidadão, e caso tenha sido informado um nome, já carrega o
   * este na tela do cidadão
   */
  var sElementoFiliacao = oElementoCidadao.id.replace('oInputCpf', '').toLowerCase();
  if ( sElementoFiliacao == 'mae' && !empty($('ed47_v_mae').value) ) {
    oCidadao.setNome( $('ed47_v_mae').value );
  }

  if ( sElementoFiliacao == 'pai' && !empty($('ed47_v_pai').value) ) {
    oCidadao.setNome( $('ed47_v_pai').value );
  }

  if ( sElementoFiliacao == 'responsavel' && !empty($('ed47_c_nomeresp').value) ) {
    oCidadao.setNome( $('ed47_c_nomeresp').value );
  }

  oCidadao.setCpfCnpj( oElementoCidadao.value );
  oCidadao.setWindowAux(true);
  oCidadao.cpfObrigatorio(true);
  oCidadao.aposSalvar(function() {

    var oValores          = new Object();
        oValores.sNome    = this.oDadosCidadao.sNome.urlDecode();
        oValores.sCpf     = this.oDadosCidadao.sCpf.urlDecode();
        oValores.iCidadao = this.oDadosCidadao.iCidadao;

    js_atribuiValoresFiliacao(oElementoCidadao, oValores);

    if (oElementoCidadao.id == 'oInputCpfResponsavel' && empty($('ed47_v_ender').value)) {

      $('ed47_v_ender').value  = this.oDadosCidadao.sEndereco.urlDecode();
      $('ed47_c_numero').value = this.oDadosCidadao.iNumero;
      $('ed47_v_compl').value  = this.oDadosCidadao.sComplemento.urlDecode();
      $('ed47_v_bairro').value = this.oDadosCidadao.sBairro.urlDecode();
      $('ed47_v_cep').value    = this.oDadosCidadao.sCep.urlDecode();
    }

    if ( oValores.sCpf == oInputCpfMae.value ) {
      $('ed47_v_mae').value = oValores.sNome;
    }

    if ( oValores.sCpf == oInputCpfPai.value ) {
      $('ed47_v_pai').value = oValores.sNome;
    }

    if ( oValores.sCpf == oInputCpfResponsavel.value ) {
      $('ed47_c_nomeresp').value = oValores.sNome;
    }
  });

  oCidadao.show();
  return oCidadao;
}

/**
 * Busca o endereço do cidadão para preenchimento do endereço do aluno, caso o mesmo esteja vazio
 */
function js_buscaEnderecoCidadao() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getDados';
      oParametro.iCidadao  = arguments[0];

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornaEnderecoCidadao;

  js_divCarregando(_M('educacao.escola.db_frmalunodados.buscando_endereco_cidadao'), "msgBox");
  new Ajax.Request(sRpcCidadao, oDadosRequisicao);
}

/**
 * Retorno do endereço do cidadão e preenchimento do endereço do aluno
 */
function js_retornaEnderecoCidadao(oResponse) {

  js_removeObj("msgBox");
  oResponse = eval('(' + oResponse.responseText + ')');

  $('ed47_v_ender').value  = oResponse.sEndereco.urlDecode();
  $('ed47_c_numero').value = oResponse.iNumero;
  $('ed47_v_compl').value  = oResponse.sComplemento.urlDecode();
  $('ed47_v_bairro').value = oResponse.sBairro.urlDecode();
  $('ed47_v_cep').value    = oResponse.sCep.urlDecode();
}

/**
 * Busca os dados do Pai e mãe vinculados ao aluno como cidadão
 */
function js_buscaCidadaoFiliacao() {

  if (!empty($('ed47_i_codigo').value)) {

    var oParametro        = new Object();
        oParametro.exec   = 'cidadaosFiliacao';
        oParametro.iAluno = $('ed47_i_codigo').value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornaCidadaoFiliacao;

    js_divCarregando(_M('educacao.escola.db_frmalunodados.buscando_cidadao_filiacao'), "msgBox");
    new Ajax.Request('edu4_aluno.RPC.php', oDadosRequisicao);
  }
}

/**
 * Atualiza os campos referentes a filiação
 */
function js_retornaCidadaoFiliacao(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.oPai != null) {

    oInputCodigoPai.value = oRetorno.oPai.iCodigo;
    oInputCpfPai.value    = oRetorno.oPai.sCpf.urlDecode();
  }

  if (oRetorno.oMae != null) {

    oInputCodigoMae.value = oRetorno.oMae.iCodigo;
    oInputCpfMae.value    = oRetorno.oMae.sCpf.urlDecode();
  }

  js_buscaCidadaoResponsavel();
}

/**
 * Busca os dados do responsável vinculado como um cidadao
 */
function js_buscaCidadaoResponsavel() {

  if (!empty($('ed47_i_codigo').value)) {

    var oParametro        = new Object();
        oParametro.exec   = 'cidadaoResponsavel';
        oParametro.iAluno = $('ed47_i_codigo').value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornaCidadaoResponsavel;

    js_divCarregando(_M('educacao.escola.db_frmalunodados.buscando_cidadao_responsavel'), "msgBox");
    new Ajax.Request('edu4_aluno.RPC.php', oDadosRequisicao);
  }
}

/**
 * Atualiza os campos do responsável
 */
function js_retornaCidadaoResponsavel(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.oResponsavel != null) {

    oInputCodigoResponsavel.value = oRetorno.oResponsavel.iCodigo;
    oInputCpfResponsavel.value    = oRetorno.oResponsavel.sCpf.urlDecode();
  }

  js_buscaCidadaoContato();
}

/**
 * Busca os dados do contato vinculado ao aluno como um cidadão
 */
function js_buscaCidadaoContato() {

  var oGet = js_urlToObject();

  if ( oGet.lMensagemApresentada == 'true') {
    return false;
  }

  if (!empty($('ed47_i_codigo').value)) {

    var oParametro        = new Object();
        oParametro.exec   = 'cidadaoContato';
        oParametro.iAluno = $('ed47_i_codigo').value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornaCidadaoContato;

    js_divCarregando(_M('educacao.escola.db_frmalunodados.buscando_cidadao_contato'), "msgBox");
    new Ajax.Request('edu4_aluno.RPC.php', oDadosRequisicao);
  }
}

/**
 * Atualiza o select do responsável
 */
function js_retornaCidadaoContato(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.oContato != null) {

    var iCodigo                                        = null;
    var aCidadaoContato                                = new Object();
        aCidadaoContato[oInputCodigoMae.value]         = 1;
        aCidadaoContato[oInputCodigoPai.value]         = 2;
        aCidadaoContato[oInputCodigoResponsavel.value] = 3;

    oSelectContato.value = aCidadaoContato[oRetorno.oContato.iCodigo];

    if ( !empty(oInputCodigoResponsavel.value) && oInputCodigoMae.value == oInputCodigoResponsavel.value &&
         oRetorno.oContato.iCodigo == oInputCodigoMae.value
       ) {
      oSelectContato.value = 1;
    }

    if ( !empty(oInputCodigoResponsavel.value) && oInputCodigoPai.value == oInputCodigoResponsavel.value &&
         oRetorno.oContato.iCodigo == oInputCodigoPai.value
       ) {
      oSelectContato.value = 2;
    }
  }

  js_validaCadastroCidadaoFiliacao();
}

 /**
  * Exibe uma mensagem ao usuario caso o Pai, Mãe ou Responsavel ( Cidadãos ) não estejam vinculados ao aluno
  */
function js_validaCadastroCidadaoFiliacao() {

  var oVariavel           = new Object();
      oVariavel.sMensagem = '';

  if ($F('ed47_i_filiacao') == 1) {

    if ( empty(oInputCodigoMae.value) && empty(oInputCodigoPai.value) ) {
      oVariavel.sMensagem = 'Filiação 1 e/ou Filiação 2';
    }
  } else if (empty(oInputCodigoResponsavel.value)) {
    oVariavel.sMensagem = 'Responsável';
  }

  if (oVariavel.sMensagem != '') {
    alert(_M('educacao.escola.db_frmalunodados.atualizar_cadastro_cidadao', oVariavel));
  }

  js_validaopcao();
}

function js_condicaoNomeFiliacao(oElemento) {

  if (oElemento.id == 'oInputCpfResponsavel') {

    $('ed47_c_nomeresp').readOnly              = false;
    $('ed47_c_nomeresp').style.backgroundColor = '#E6E4F1';
  } else {

    $(oElemento.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).readOnly              = false;
    $(oElemento.id.replace('oInputCpf', 'ed47_v_').toLowerCase()).style.backgroundColor = '#E6E4F1';
  }
}

<?if($db_opcao==1){?>
document.form1.ed47_i_filiacao.value = 1;
//js_filiacao(1);
<?}?>

/**
 * Caso seja alteração ou exclusão, verifica se o CPF da mãe, pai e/ou responsável está preenchido, bloqueando o nome de
 * cada
 */
function js_validaopcao() {

  if ( iOpcaoFiliacao != 1 ) {

    if ( !empty($('oInputCpfMae').value) ) {

      $('ed47_v_mae').readOnly              = true;
      $('ed47_v_mae').style.backgroundColor = '#DEB887';
    }

    if ( !empty($('oInputCpfPai').value) ) {

      $('ed47_v_pai').readOnly              = true;
      $('ed47_v_pai').style.backgroundColor = '#DEB887';
    }

    if ( !empty($('oInputCpfResponsavel').value) ) {

      $('ed47_c_nomeresp').readOnly              = true;
      $('ed47_c_nomeresp').style.backgroundColor = '#DEB887';
    }
  }
}

/**
 * Busca os dados do contato selecionado
 */
function js_buscaDadosContatoResponsavel( iOpcaoSelecionada ) {

  var iCidadao = null;

  switch( iOpcaoSelecionada ) {

    case '1':

      iCidadao = oInputCodigoMae.value;
      break;

    case '2':

      iCidadao = oInputCodigoPai.value;
      break;

    case '3':

      iCidadao = oInputCodigoResponsavel.value;
      break;
  }

  if ( empty(iCidadao) ) {
    return false;
  }

  var oParametro           = new Object();
      oParametro.sExecucao = 'getDados';
      oParametro.iCidadao  = iCidadao;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaDadosContatoResponsavel;

  js_divCarregando( _M('educacao.escola.db_frmalunodados.buscando_dados_contato_responsavel'), "msgBox" );
  new Ajax.Request( sRpcCidadao, oDadosRequisicao );
}

/**
 * Retorna e preenche os dados do contato
 */
function js_retornoBuscaDadosContatoResponsavel( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  if ( oRetorno.aEmail.length > 0 ) {
    $('ed47_c_emailresp').value = oRetorno.aEmail[0].sEmail.urlDecode();
  }

  if ( oRetorno.aTelefones.length > 0 ) {

    oRetorno.aTelefones.each(function( oTelefone, iLinha ) {

      if ( oTelefone.iTipo == 2 ) {

        $('dddcelularresponsavel').value   = oTelefone.sDDD.urlDecode();
        $('ed47_celularresponsavel').value = oTelefone.iNumero;
      }
    });
  }
}
js_removerAspas($('ed47_v_nome'));

document.observe("dom:loaded", function() {

  mascaraTelefone($('ed47_v_telef'));
  mascaraTelefone($('ed47_v_telcel'));
  mascaraTelefone($('ed47_v_fax'));
  apresentaNascionalidade();
});

/**
 * Define quais campos devem estar habilitados para ser preenchidos de acordo com a nacionalidade do aluno.
 */
function apresentaNascionalidade() {

  $('ed47_i_censoufnat').removeClassName('readOnly');
  $('ed47_i_censomunicnat').removeClassName('readOnly');
  $('ed47_i_censoufnat').removeAttribute( 'disabled' );
  $('ed47_i_censomunicnat').removeAttribute('disabled' );
  $('linhaMunicipioEstrangeiro').style.display = 'none';

  if( $F('ed47_i_nacion') == 3 ) {

    $('ed47_i_censoufnat').addClassName('readOnly');
    $('ed47_i_censomunicnat').addClassName('readOnly');
    $('ed47_i_censoufnat').setAttribute( 'disabled', 'disabled' );
    $('ed47_i_censomunicnat').setAttribute( 'disabled', 'disabled' );
    $('ed47_i_censoufnat').value                 = ' ';
    $('ed47_i_censomunicnat').value              = ' ';
    $('linhaMunicipioEstrangeiro').style.display = '';
    return;
  }

  $('ed47_municipioestrangeiro').value = '';
}

</script>