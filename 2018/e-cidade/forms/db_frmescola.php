<?php
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

$clescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
?>
<div class="container">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <fieldset>
      <legend>Dados da Escola</legend>
      <table>
        <tr>
          <td>
            <table>
              <tr>
                <td nowrap title="<?=@$Ted18_i_codigo?>">
                  <?php
                  db_ancora( @$Led18_i_codigo, "js_pesquisaed18_i_codigo(true);", 3 );
                  ?>
                </td>
                <td>
                  <?php
                  db_input( 'ed18_i_codigo', 10, $Ied18_i_codigo, true, 'text', 3 );
                  ?>
                  <label class="bold">Código Referência:</label>
                  <?php
                  db_input( 'ed18_codigoreferencia', 10, $Ied18_codigoreferencia, true, 'text', 2 );

                  echo @$Led18_i_funcionamento;

                  $x = array( '1' => 'EM ATIVIDADE', '2' => 'PARALISADA', '3' => 'EXTINTA' );
                  db_select( 'ed18_i_funcionamento', $x, true, @$db_opcao1 );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_c_nome?>">
                  <?=@$Led18_c_nome?>
                </td>
                <td>
                  <?php
                  $sScript = " onKeyUp=\"js_ValidaCamposEdu(this,2,'$GLOBALS[Sed18_c_nome]','f','t',event);\"";
                  db_input( 'ed18_c_nome', 70, $Ied18_c_nome, true, 'text', $db_opcao, $sScript );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_c_abrev?>">
                  <?=@$Led18_c_abrev?>
                </td>
                <td>
                  <?php
                  db_input( 'ed18_c_abrev', 45, $Ied18_c_abrev, true, 'text', $db_opcao );
                  echo @$Led18_c_codigoinep;
                  db_input( 'ed18_c_codigoinep', 8, $Ied18_c_codigoinep, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_anoinicio?>">
                  <?=@$Led18_i_anoinicio?>
                </td>
                <td>
                  <?php
                  db_input( 'ed18_i_anoinicio', 4, $Ied18_i_anoinicio, true, 'text', $db_opcao );
                  echo @$Led18_i_cnpj;
                  db_input( 'ed18_i_cnpj', 15, $Ied18_i_cnpj, true, 'text', $db_opcao );
                  echo @$Led18_i_credenciamento;
                  $x = array( '' => '', '0' => 'NÃO CREDENCIADA', '1' => 'CREDENCIADA', '2' => 'EM TRAMITAÇÃO' );
                  db_select( 'ed18_i_credenciamento', $x, true, $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_censouf?>">
                  <?=@$Led18_i_censouf?>
                </td>
                <td>
                  <?php
                  $sSqlCensoUf = $clcensouf->sql_query_file( "", "ed260_i_codigo, ed260_c_nome", "ed260_c_nome" );
                  $result_uf   = $clcensouf->sql_record( $sSqlCensoUf );
                  db_selectrecord( "ed18_i_censouf", $result_uf, "", "", "", "", "", "  ", "iframe_uf.location.href='edu1_escola004.php?censouf='+this.value", 1 );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_censomunic?>">
                  <?=@$Led18_i_censomunic?>
                </td>
                <td>
                  <?php
                  if( isset( $ed18_i_censouf ) && $ed18_i_censouf != "" ) {

                    $sCamposCensoMunic = "ed261_i_codigo, ed261_c_nome";
                    $sWhereCensoMunic  = "ed261_i_censouf = {$ed18_i_censouf}";
                    $sSqlCensoMunic    = $clcensomunic->sql_query_file( "", $sCamposCensoMunic, "ed261_c_nome", $sWhereCensoMunic );
                    $result_munic      = $clcensomunic->sql_record( $sSqlCensoMunic );

                    if( $clcensomunic->numrows == 0 ) {

                      $x = array( '' => 'Selecione o Estado' );
                      db_select(
                                 'ed18_i_censomunic',
                                 $x,
                                 true,
                                 @$db_opcao1,
                                 "onchange=\"iframe_uf.location.href='edu1_escola004.php?censomunic='+this.value\""
                               );
                    } else {
                      db_selectrecord(
                                       "ed18_i_censomunic",
                                       $result_munic,
                                       "",
                                       "",
                                       "",
                                       "",
                                       "",
                                       "  ",
                                       "iframe_uf.location.href='edu1_escola004.php?censomunic='+this.value",
                                       1
                                     );
                    }
                  } else {

                    $x = array( '' => 'Selecione o Estado' );
                    db_select(
                               'ed18_i_censomunic',
                               $x,
                               true,
                               @$db_opcao1,
                               "onchange=\"iframe_uf.location.href='edu1_escola004.php?censomunic='+this.value\""
                             );
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?=@$Led18_i_censodistrito?>
                </td>
                <td>
                  <?php
                  if( isset( $ed18_i_censomunic ) && $ed18_i_censomunic != "" ) {

                    $sCamposCensoDistrito = "ed262_i_codigo, ed262_c_nome";
                    $sWhereCensoDistrito  = "ed262_i_censomunic = {$ed18_i_censomunic} AND ed261_i_censouf = {$ed18_i_censouf}";
                    $sSqlCensoDistrito    = $clcensodistrito->sql_query( "", $sCamposCensoDistrito, "ed262_c_nome", $sWhereCensoDistrito );
                    $result_distrito      = $clcensodistrito->sql_record( $sSqlCensoDistrito );

                    if( $clcensodistrito->numrows == 0 ) {

                      $x = array( '' => 'Selecione a Cidade' );
                      db_select( 'ed18_i_censodistrito', $x, true, @$db_opcao1 );
                    } else {
                      db_selectrecord( "ed18_i_censodistrito", $result_distrito, "", "", "", "", "", "  ", "", 1 );
                    }
                  } else {

                    $x = array( '' => 'Selecione a Cidade' );
                    db_select( 'ed18_i_censodistrito', $x, true, @$db_opcao1 );
                  }
                  ?>
                  <iframe name="iframe_uf"
                          src=""
                          framedorder="0"
                          width="0"
                          height="0"
                          style="visibility:hidden;position:absolute;">
                  </iframe>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_bairro?>">
                  <?php
                  db_ancora( @$Led18_i_bairro, "js_pesquisaed18_i_bairro(true);", $db_opcao );
                  ?>
                </td>
                <td>
                  <?php
                  $sScript = "onchange='js_pesquisaed18_i_bairro(false);'";
                  db_input( 'ed18_i_bairro',10, $Ied18_i_bairro,true, 'text', $db_opcao, $sScript );
                  db_input( 'j13_descr',    40, $Ij13_descr,    true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_rua?>">
                  <?php
                  db_ancora( @$Led18_i_rua, "js_pesquisaed18_i_rua(true);", $db_opcao );
                  ?>
                </td>
                <td>
                  <?php
                  db_input( 'ed18_i_rua', 10, $Ied18_i_rua, true, 'text', $db_opcao, "onchange='js_pesquisaed18_i_rua(false);'" );
                  db_input( 'j14_nome',   40, $Ij14_nome,   true, 'text',         3 );
                  echo @$Led18_c_cep;
                  db_input( 'ed18_c_cep', 8, @$ed18_c_cep, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap>
                  <?=@$Led18_latitude?>
                </td>
                <td>
                  <?php
                  db_input( 'ed18_latitude', 10, $Ied18_latitude, true, 'text', $db_opcao );
                  echo @$Led18_longitude;
                  db_input( 'ed18_longitude', 10, $Ied18_longitude, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_numero?>">
                  <?=@$Led18_i_numero?>
                </td>
                <td nowrap title="<?=@$Ted18_i_numero?>">
                  <?php
                  db_input( 'ed18_i_numero', 10, $Ied18_i_numero, true, 'text', $db_opcao );
                  echo @$Led18_c_compl;

                  $sScript = " onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed18_c_compl]','t','t',event);\"";
                  db_input( 'ed18_c_compl', 20, $Ied18_c_compl, true, 'text', $db_opcao, $sScript );

                  echo @$Led18_c_local;
                  $x = array( '1' => 'URBANA', '2' => 'RURAL' );
                  db_select( 'ed18_c_local', $x, true, @$db_opcao1 );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted18_i_censoorgreg?>">
                  <?=@$Led18_i_censoorgreg?>
                </td>
                <td>
                  <?php
                  if( isset( $ed18_i_censouf ) && $ed18_i_censouf != "" ) {

                    $sCamposCensoOrgReg = "ed263_i_codigo, ed263_c_nome";
                    $sWhereCensoOrgReg  = "ed263_i_censouf = {$ed18_i_censouf}";
                    $sSqlCensoOrgReg    = $clcensoorgreg->sql_query_file( "", $sCamposCensoOrgReg, "ed263_c_nome", $sWhereCensoOrgReg );
                    $result_orgreg      = $clcensoorgreg->sql_record( $sSqlCensoOrgReg );

                    if( $clcensoorgreg->numrows == 0 ) {

                      $x = array( '' => 'Nenhum registro neste estado' );
                      db_select( 'ed18_i_censoorgreg', $x, true, @$db_opcao1 );
                    } else {
                      db_selectrecord( "ed18_i_censoorgreg", $result_orgreg, "", "", "", "", "", "  ", "", 1 );
                    }
                  } else {

                    $x = array( '' => 'Selecione o Estado' );
                    db_select( 'ed18_i_censoorgreg', $x, true, @$db_opcao1 );
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table border="0" style="position:absolute;">
                    <tr>
                      <td >
                        <?=@$Led18_c_mantenedora?>
                        <?php
                        $x = array( '' => '', '1' => 'FEDERAL', '2' => 'ESTADUAL', '3' => 'MUNICIPAL', '4' => 'PRIVADA' );
                        db_select( 'ed18_c_mantenedora', $x, true, $db_opcao, " onchange='js_mantenedora(this.value)'" );

                        if( isset( $ed18_c_mantenedora ) && $ed18_c_mantenedora == 4 ) {
                          $visible3 = "visible";
                        } else {
                          $visible3 = "hidden";
                        }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table id="privada" colspan="top" style="visibility:<?=$visible3?>">
                          <tr>
                            <td nowrap valign="top">
                              <fieldset style="height: 122px;">
                                <legend><?=@$Led18_i_categprivada?></legend>
                                <input type="radio"
                                       name="ed18_i_categprivada"
                                       value="1"
                                       onclick='js_categoria(this.value);'
                                       <?=@$ed18_i_categprivada == "1" ? "checked" : ""?>> Particular<br />
                                <input type="radio"
                                       name="ed18_i_categprivada"
                                       value="2"
                                       onclick='js_categoria(this.value);'
                                       <?=@$ed18_i_categprivada == "2" ? "checked" : ""?>> Comunitária<br />
                                <input type="radio"
                                       name="ed18_i_categprivada"
                                       value="3"
                                       onclick='js_categoria(this.value);'
                                       <?=@$ed18_i_categprivada == "3" ? "checked" : ""?>> Confessional<br />
                                <input type="radio"
                                       name="ed18_i_categprivada"
                                       value="4"
                                       onclick='js_categoria(this.value);'
                                       <?=@$ed18_i_categprivada == "4" ? "checked" : ""?>> Filantrópica<br />
                                <?php
                                if( !isset( $ed18_c_mantprivada ) ) {
                                  $ed18_c_mantprivada = "0000";
                                }
                                ?>
                              </fieldset>
                            </td>
                            <td nowrap valign="top">
                              <fieldset style="height: 122px;">
                                <legend><?=@$Led18_c_mantprivada?></legend>
                                <input <?=substr( @$ed18_c_mantprivada, 0, 1 ) == "1" ? "checked" : ""?>
                                       id="ed18_c_mantprivada"
                                       name="ed18_c_mantprivada[]"
                                       type="checkbox"
                                       value="1"> Empresas / Grupos Empresariais ou Pessoa Física<br>
                                <input <?=substr( @$ed18_c_mantprivada, 1, 1 ) == "1" ? "checked" : ""?>
                                       id="ed18_c_mantprivada"
                                       name="ed18_c_mantprivada[]"
                                       type="checkbox"
                                       value="2"> Sindicatos / Associações / Cooperativas<br>
                                <input <?=substr( @$ed18_c_mantprivada, 2, 1 ) == "1" ? "checked" : ""?>
                                       id="ed18_c_mantprivada"
                                       name="ed18_c_mantprivada[]"
                                       type="checkbox"
                                       value="3"> ONG - Internacional ou Nacional<br>
                                <input <?=substr( @$ed18_c_mantprivada, 3, 1 ) == "1" ? "checked" : ""?>
                                       id="ed18_c_mantprivada"
                                       name="ed18_c_mantprivada[]"
                                       type="checkbox"
                                       value="4"> Instituições sem fins lucrativos<br>
                                <input <?=substr( @$ed18_c_mantprivada, 4, 1 ) == "1" ? "checked" : ""?>
                                       id="ed18_c_mantprivada"
                                       name="ed18_c_mantprivada[]"
                                       type="checkbox"
                                       value="5"> Sistema S (Sesi, Senai, Sesc, outros)<br>
                              </fieldset>
                            </td>
                            <td nowrap>
                              <fieldset>
                                <legend><?=@$Led18_i_conveniada?></legend>
                                <table>
                                  <tr>
                                    <td nowrap>
                                      <input type="radio"
                                             name="ed18_i_conveniada"
                                             value="1"
                                             <?=@$ed18_i_conveniada == "1" ? "checked" : ""?>> Estadual
                                    </td>
                                    <td nowrap>
                                      <input type="radio"
                                             name="ed18_i_conveniada"
                                             value="2"
                                             <?=@$ed18_i_conveniada == "2" ? "checked" : ""?>> Municipal
                                    </td>
                                  </tr>
                                  <tr>
                                    <td nowrap>
                                      <?=@$Led18_i_cnas?>
                                    </td>
                                    <td nowrap>
                                      <?php
                                      $sScript = "onClick='js_cnascebas(1,this);'";
                                      db_input( 'ed18_i_cnas', 15, @$Ied18_i_cnas, true, 'text', $db_opcao, $sScript );
                                      ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td nowrap>
                                      <?=@$Led18_i_cebas?>
                                    </td>
                                    <td nowrap>
                                      <?php
                                      $sScript = "onClick='js_cnascebas(2,this);'";
                                      db_input( 'ed18_i_cebas', 15, @$Ied18_i_cebas, true, 'text', $db_opcao, $sScript );
                                      ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td nowrap>
                                      <?=@$Led18_i_cnpjmantprivada?>
                                    </td>
                                    <td nowrap>
                                      <?php
                                      db_input( 'ed18_i_cnpjmantprivada', 15, @$Ied18_i_cnpjmantprivada, true, 'text', $db_opcao );
                                      ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td nowrap>
                                      <?=@$Led18_i_cnpjprivada?>
                                    </td>
                                    <td nowrap>
                                      <?php
                                      db_input( 'ed18_i_cnpjprivada', 15, @$Ied18_i_cebas, true, 'text', $db_opcao );
                                      ?>
                                    </td>
                                  </tr>
                                </table>
                              </fieldset>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
          <td valign="top">
            <table border="0">
              <tr>
                <td valign="top">
                  <fieldset>
                    <legend><?=@$Led18_i_locdiferenciada?></legend>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="1"
                           <?=(@$ed18_i_locdiferenciada == "1" ? "checked" : "" )?>>Área de assentamento<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="2"
                           <?=(@$ed18_i_locdiferenciada == "2" ? "checked" : "" )?>>Terra indígena<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="3"
                           <?=(@$ed18_i_locdiferenciada == "3" ? "checked" : "" )?>>Área remanescente de quilombos<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="4"
                           <?=(@$ed18_i_locdiferenciada == "4" ? "checked" : "" )?>>Unidade de uso sustentável<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="5"
                           <?=(@$ed18_i_locdiferenciada == "5" ? "checked" : "" )?>>Unidade de uso sustentável em Terra indígena<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="6"
                           <?=(@$ed18_i_locdiferenciada == "6" ? "checked" : "" )?>>Unidade de uso sustentável em Área remanescente de quilombos<br/>
                    <input type="radio"
                           name="ed18_i_locdiferenciada"
                           value="7"
                           <?=(@$ed18_i_locdiferenciada == "7" ? "checked" : "" )?>>Não se aplica
                  </fieldset>
                </td>
                <td valign="top" align="center">
                  <fieldset style="width:130px;height:152px">
                    <legend>Logotipo</legend>
                    <?php
                    if( @$ed18_c_logo != "" ) {
                      echo "<img src='imagens/".$ed18_c_logo."' width='130' height='110'>";
                    }
                    ?>
                  </fieldset>
                  <?php
                  if( @$ed18_c_logo != "" ) {
                    echo "<a href='?excluirfoto&codigoescola=$ed18_i_codigo&logo=$ed18_c_logo'>Excluir Imagem</a>";
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <fieldset>
                    <legend><?=@$Led18_i_educindigena?></legend>
                    <input type="radio"
                           name="ed18_i_educindigena"
                           value="1"
                           <?=(@$ed18_i_educindigena == "1" ? "checked" : "" )?>
                           onclick="js_educingidena(this.value);"> SIM
                    <input type="radio"
                           name="ed18_i_educindigena"
                           value="0"
                           <?=(@$ed18_i_educindigena == "0" ? "checked" : "" )?>
                           onclick="js_educingidena(this.value);"> NÃO
                    <?php
                    if( isset( $ed18_i_educindigena ) && $ed18_i_educindigena == 1 ) {
                      $visibility1 = "visible";
                    } else {
                      $visibility1 = "hidden";
                    }
                    ?>
                    <fieldset id="linguaministrada" style="visibility:<?=$visibility1?>">
                      <legend>Língua Ministrada</legend>
                      <input type="checkbox"
                             name="ed18_i_tipolinguapt"
                             value="1"
                             <?=(@$ed18_i_tipolinguapt == 1 ? "checked" : "" )?>
                             onclick="js_tipolinguapt();"> <?=@$Led18_i_tipolinguapt?>
                      <input type="checkbox"
                             name="ed18_i_tipolinguain"
                             value="1"
                             <?=(@$ed18_i_tipolinguain == 1 ? "checked" : "" )?>
                             onclick="js_tipolinguain();"> <?=@$Led18_i_tipolinguain?><br>
                      <?php
                      if( isset( $ed18_i_tipolinguain ) && $ed18_i_tipolinguain == 1 ) {
                        $visibility2 = "visible";
                      } else {
                        $visibility2 = "hidden";
                      }
                      ?>
                      <span id="codigolingua" style="visibility:<?=$visibility2?>">
                        <?php
                        db_ancora( @$Led18_i_linguaindigena, "js_pesquisaed18_i_linguaindigena(true);", $db_opcao );
                        ?>
                        <br>
                        <?php
                        db_input( 'ed18_i_linguaindigena', 10, @$Ied18_i_linguaindigena, true, 'text', 3 );
                        db_input( 'ed264_c_nome',          35, @$Ied264_c_nome,          true, 'text', 3 );
                        ?>
                                </span>
                    </fieldset>
                  </fieldset>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted18_c_logo?>" valign="bottom">
            <br><br><br><br><br><br><br><br><br><br><br><br>
            <label class="bold">Logotipo:</label>
            <?php
            db_input( 'ed18_c_logo', 50, $Ied18_c_logo, true, 'file', $db_opcao );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="submit"
           id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           <?=( $db_botao == false ? "disabled" : "" )?>
           onclick="return js_valida();">
  </form>
</div>
<script>
function js_pesquisaed18_i_rua( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_ruas',
                         'func_ruasedu.php?rural=1&funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome|cep|',
                         'Pesquisa Ruas',
                         true
                       );
  } else {

    if( document.form1.ed18_i_rua.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_ruas',
                           'func_ruasedu.php?rural=1'
                                          +'&pesquisa_chave='+document.form1.ed18_i_rua.value
                                          +'&funcao_js=parent.js_mostraruas',
                           'Pesquisa Ruas',
                           false
                         );
    } else {

      document.form1.j14_nome.value   = '';
      document.form1.ed18_c_cep.value = '';
    }
  }
}

function js_mostraruas( chave, chave1, chave2, chave3, erro ) {

  document.form1.j14_nome.value   = chave;
  document.form1.ed18_c_cep.value = chave2;

  if( chave1 == true ) {

    document.form1.ed18_i_rua.focus();
    document.form1.ed18_i_rua.value = '';
    document.form1.ed18_c_cep.value = '';
  }
}

function js_mostraruas1( chave1, chave2, chave3 ) {

  document.form1.ed18_i_rua.value = chave1;
  document.form1.j14_nome.value   = chave2;
  document.form1.ed18_c_cep.value = chave3;
  db_iframe_ruas.hide();
}

function js_pesquisaed18_i_linguaindigena( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lingua',
                         'func_censolinguaindig.php?funcao_js=parent.js_mostralingua1|ed264_i_codigo|ed264_c_nome',
                         'Pesquisa Lingua Indígena',
                         true
                       );
  }
}

function js_mostralingua1( chave1, chave2 ) {

  document.form1.ed18_i_linguaindigena.value = chave1;
  document.form1.ed264_c_nome.value          = chave2;
  db_iframe_lingua.hide();
}

function js_pesquisaed18_i_bairro( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr',
                         'Pesquisa Bairros',
                         true
                       );
  } else {

    if( document.form1.ed18_i_bairro.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_bairro',
                           'func_bairro.php?pesquisa_chave='+document.form1.ed18_i_bairro.value
                                         +'&funcao_js=parent.js_mostrabairro',
                           'Pesquisa Bairros',
                           false
                         );
    } else {
      document.form1.j13_descr.value = '';
    }
  }
}

function js_mostrabairro( chave, erro ) {

  document.form1.j13_descr.value = chave;

  if( erro == true ) {

    document.form1.ed18_i_bairro.focus();
    document.form1.ed18_i_bairro.value = '';
  }
}

function js_mostrabairro1( chave1, chave2 ) {

  document.form1.ed18_i_bairro.value = chave1;
  document.form1.j13_descr.value     = chave2;
  db_iframe_bairro.hide();
}

function js_educingidena( valor ) {

  if( valor == 1 ) {
    document.getElementById("linguaministrada").style.visibility = "visible";
  } else {

    document.getElementById("linguaministrada").style.visibility = "hidden";
    document.getElementById("codigolingua").style.visibility     = "hidden";
    document.form1.ed18_i_tipolinguapt.checked                   = false;
    document.form1.ed18_i_tipolinguain.checked                   = false;
    document.form1.ed18_i_tipolinguapt.value                     = 0;
    document.form1.ed18_i_tipolinguain.value                     = 0;
    document.form1.ed18_i_linguaindigena.value                   = "";
    document.form1.ed264_c_nome.value                            = "";
  }
}

function js_tipolinguain() {

  if( document.form1.ed18_i_tipolinguain.checked == true ) {

    document.getElementById("codigolingua").style.visibility = "visible";
    document.form1.ed18_i_tipolinguain.value                 = 1;
  } else {

    document.getElementById("codigolingua").style.visibility = "hidden";
    document.form1.ed18_i_linguaindigena.value               = "";
    document.form1.ed264_c_nome.value                        = "";
    document.form1.ed18_i_tipolinguain.value                 = 0;
  }
}

function js_tipolinguapt() {

  if( document.form1.ed18_i_tipolinguapt.checked == true ) {
    document.form1.ed18_i_tipolinguapt.value = 1;
  } else {
    document.form1.ed18_i_tipolinguapt.value = 0;
  }
}

function js_valida() {

  if( document.form1.ed18_c_nome.value.length < 4 ) {

    alert("Descrição da Escola deve ter no mínimo 4 dígitos!");
    document.form1.ed18_c_nome.style.backgroundColor = '#99A9AE';
    document.form1.ed18_c_nome.focus();
    return false;
  }

  if( document.form1.ed18_i_tipolinguain.checked == true && document.form1.ed18_i_linguaindigena.value == "" ) {

    alert("Informe a Língua Indígena!");
    document.form1.ed18_i_linguaindigena.style.backgroundColor = '#99A9AE';
    document.form1.ed18_i_linguaindigena.focus();
    return false;
  }

  if(    document.form1.ed18_i_educindigena[0].checked == true
    && document.form1.ed18_i_tipolinguapt.checked == false
    && document.form1.ed18_i_tipolinguain.checked == false ) {

    alert("Informe pelo menos uma das Línguas Ministradas!");
    return false;
  }

  if( document.form1.ed18_c_codigoinep.value.length < 8 ) {

    alert("Código INEP deve conter 8 dígitos!");
    document.form1.ed18_c_codigoinep.style.backgroundColor = '#99A9AE';
    document.form1.ed18_c_codigoinep.focus();
    return false;
  }

  if(    document.form1.ed18_c_mantenedora.value == 4
    && document.form1.ed18_i_categprivada[0].checked == false
    && document.form1.ed18_i_categprivada[1].checked == false
    && document.form1.ed18_i_categprivada[2].checked == false
    && document.form1.ed18_i_categprivada[3].checked == false ){

    alert("Informe a Categoria da Escola Privada!");
    return false;
  }

  if( document.form1.ed18_i_censoorgreg.options[document.form1.ed18_i_censoorgreg.selectedIndex].text == "" ) {

    alert("Informe o Órgão de Ensino!");
    document.form1.ed18_i_censoorgreg.style.backgroundColor = '#99A9AE';
    document.form1.ed18_i_censoorgreg.focus();
    return false;
  }

  if( document.form1.ed18_i_categprivada[3].checked == true && document.form1.ed18_i_cnas.value == "" ) {

    alert("Informe o Nº Registro no CNAS!");
    document.form1.ed18_i_cnas.style.backgroundColor = '#99A9AE';
    document.form1.ed18_i_cnas.focus();
    return false;
  }

  if( document.form1.ed18_i_categprivada[3].checked == true && document.form1.ed18_i_cebas.value == "" ) {

    alert("Informe o Nº CEBAS!");
    document.form1.ed18_i_cebas.style.backgroundColor = '#99A9AE';
    document.form1.ed18_i_cebas.focus();
    return false;
  }

  if( document.form1.ed18_i_cnpjprivada.value != "" && document.form1.ed18_i_cnpj.value != "" ) {

    alert("Campo CNPJ deve ficar em branco quando CNPJ da Escola Privada for preenchido!");
    document.form1.ed18_i_cnpj.style.backgroundColor = '#99A9AE';
    document.form1.ed18_i_cnpj.focus();
    return false;
  }

  if( document.form1.ed18_latitude.value != '' ) {

    if( !isNumeric( document.form1.ed18_latitude.value, false ) ) {

      alert ('A latitude esta informada de forma incorreta. Valores para latitude devem estar entre -33.75208 e 5.271841.');
      document.form1.ed18_latitude.focus();
      return false;
    }

    if( document.form1.ed18_latitude.value.charAt(0) != '-' ) {

      if( document.form1.ed18_latitude.value > 5.271841 ) {

        alert ('A latitude não pode ser maior que 5.271841. Valores para latitude devem estar entre -33.75208 e 5.271841.');
        document.form1.ed18_latitude.focus();
        return false;
      }
    }

    if( document.form1.ed18_latitude.value.charAt(0) == '-' ) {

      if( document.form1.ed18_latitude.value.substr(1) > 33.75208 ) {

        alert ('A latitude não pode ser menor que -33.75208. Valores para latitude devem estar entre -33.75208 e 5.271841.');
        document.form1.ed18_latitude.focus();
        return false;
      }
    }
  }

  if( document.form1.ed18_longitude.value != '' ) {

    if( !isNumeric( document.form1.ed18_longitude.value, false ) ) {

      alert ('A longitude esta informada de forma incorreta. Valores para longitude devem estar entre -32.39091 e -73.99045.');
      document.form1.ed18_longitude.focus();
      return false;
    }

    if( document.form1.ed18_longitude.value.charAt(0) == '-' ) {

      if( document.form1.ed18_longitude.value.substr(1) < 32.39091 ) {

        alert ('A longitude não pode ser maior que -32.39091. Valores para longitude devem estar entre -32.39091 e -73.99045.');
        document.form1.ed18_longitude.focus();
        return false;
      }

      if( document.form1.ed18_longitude.value.substr(1) > 73.99045 ) {

        alert ('A longitude não pode ser maior que -73.99045. Valores para longitude devem estar entre -32.39091 e -73.99045.');
        document.form1.ed18_longitude.focus();
        return false;
      }
    } else {

      alert ('A longitude não pode conter valores positivos. Valores para longitude devem estar entre -32.39091 e -73.99045.');
      return false;
    }
  }

  if(    (document.form1.ed18_longitude.value != '' && document.form1.ed18_latitude.value  == '')
    || (document.form1.ed18_latitude.value != '' && document.form1.ed18_longitude.value == '') ) {

    alert ('Se latitude e/ou longitude forem informadas, ambas devem possuir valores válidos.');
    document.form1.ed18_latitude.focus();
    return false;
  }

  return true;
}

function js_mantenedora( valor ) {

  if( valor == 4 ) {
    document.getElementById("privada").style.visibility = "visible";
  } else {

    document.getElementById("privada").style.visibility = "hidden";
    document.form1.ed18_i_categprivada[0].checked       = false;
    document.form1.ed18_i_categprivada[1].checked       = false;
    document.form1.ed18_i_categprivada[2].checked       = false;
    document.form1.ed18_i_categprivada[3].checked       = false;
    document.form1.ed18_i_conveniada[0].checked         = false;
    document.form1.ed18_i_conveniada[1].checked         = false;
    document.form1.ed18_i_cnas.value                    = "";
    document.form1.ed18_i_cebas.value                   = "";
    document.form1.ed18_i_cnpjprivada.value             = "";
    document.form1.ed18_c_mantprivada[0].checked        = false;
    document.form1.ed18_c_mantprivada[1].checked        = false;
    document.form1.ed18_c_mantprivada[2].checked        = false;
    document.form1.ed18_c_mantprivada[3].checked        = false;
  }
}

function js_categoria( valor ) {

  if( valor == 1 ) {

    document.form1.ed18_i_cnas.readOnly  = true;
    document.form1.ed18_i_cebas.readOnly = true;
    document.form1.ed18_i_cnas.value     = "";
    document.form1.ed18_i_cebas.value    = "";
  } else {

    document.form1.ed18_i_cnas.readOnly  = false;
    document.form1.ed18_i_cebas.readOnly = false;
  }
}

function js_cnascebas( tipo, campo ) {

  if( tipo == 1 ) msg = "Nº de Registro no CNAS";
  if( tipo == 2 ) msg = "Nº CEBAS";

  if( document.form1.ed18_i_categprivada[0].checked == true ) {

    alert( msg + " preenchido somente quando Categoria da Escola Privada for diferente de PARTICULAR!");
    campo.value = "";
  }
}

$('ed18_i_codigo').className         = 'field-size2';
$('ed18_codigoreferencia').className = 'field-size2';
$('ed18_i_funcionamento').className  = 'field-size3';
$('ed18_c_nome').className           = 'field-size-max';
$('ed18_c_abrev').className          = 'field-size9';
$('ed18_c_codigoinep').className     = 'field-size2';
$('ed18_i_anoinicio').className      = 'field-size2';
$('ed18_i_cnpj').className           = 'field-size3';
$('ed18_i_credenciamento').className = 'field-size4';
$('ed18_i_censouf').className        = 'field-size-max';
$('ed18_i_censomunic').className     = 'field-size-max';
$('ed18_i_censodistrito').className  = 'field-size-max';
$('ed18_i_bairro').className         = 'field-size2';
$('j13_descr').className             = 'field-size7';
$('ed18_i_rua').className            = 'field-size2';
$('j14_nome').className              = 'field-size7';
$('ed18_c_cep').className            = 'field-size2';
$('ed18_latitude').className         = 'field-size2';
$('ed18_longitude').className        = 'field-size2';
$('ed18_i_numero').className         = 'field-size2';
$('ed18_c_compl').className          = 'field-size4';
$('ed18_c_local').className          = 'field-size3';
$('ed18_i_censoorgreg').className    = 'field-size-max';
$('ed18_c_mantenedora').className    = 'field-size3';
</script>