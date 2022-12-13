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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_atributo_componente
class cl_lab_atributo_componente {
   
  var $db_opcao      = 1;
  var $iRequiitem    = "";
  var $lCheck        = false;
  var $iBordas       = 1;
  var $atributosdisp = Array();
  var $iExane        = 0;
  var $aRepositorios = Array();
  var $oPDF          = "";

  private $irequiitem;

  //Construtor da classe
  function cl_lab_atributo_componemte(){

    $this->db_opcao       = 1;
    $this->iRequiitem     = "";
    $this->lCheck         = false;
    $this->iBordas        = 1;
    $this->aAtributosdisp = Array();
    $this->iExane         = 0;
    $this->aRepositorios  = Array();
    $this->oPDF           = "";
  }
   
//funções de interface da classe

  /**
   * Atributos
   *   Procedimento que monta no HTML da pagina um tabela com a lista de atibutos podendo ser montada
   *   no formato de edição com o db_opção igual a 1 ou 2, inclusão ou alteração
   *   EX:
   *   <table>
   *       <tr>
   *           <td>nomedo atributo<td><td>Imput para entrar com o valor se ja tiver valor ele carrega o mesmo<td>
   *       </tr>
   *       [... N atributos]
   *   </table>
   *
   *
   *   Se o db_opcao for igual a 3 exclusão valor de referencia é mostrado na tela
   *   EX:
   *   <table>
   *       <tr>
   *           <td>nomedo atributo<td><td>somente Mostra o valor do banco referente o atributo<td>
   *       </tr>
   *       [... N atributos]
   *   </table>
   *
   * @param        $iExame
   * @param        $iAtributo_pai - Codigo do atributo pai
   * @param string $iRequiitem
   * @param int    $db_opcao      -  modod de exibiÃ§ao dos dados
   * @param int    $fieldset      - define se vai exibir o fieldset 1 para sim 2 para nÃ£o
   * @param int    $iBordas
   * @internal param $irequiitem - se for informado o irequiitem vai carregar os dados do banco referente ao requiitem
   * @internal param $ibordas - 0 sem bordas de 1 acima grossura da borda
   */
  function atributos( $iExame, $iAtributo_pai, $iRequiitem = "", $db_opcao = 1, $fieldset = 0, $iBordas = 0 ) {

    $this->irequiitem = $iRequiitem;
    $this->db_opcao   = $db_opcao;
    $this->iBordas    = $iBordas;
    $this->iExame     = $iExame;
    $this->listarDisp();

    $pai=$this->dadosAtributo( $iAtributo_pai );

    if( $pai != false ) {

      if( $fieldset == 1 ) {
        echo"<fieldset><legend><b>Atributos</b></legend>";
      }

      echo "<table border=\"$iBordas\" >";

      if( $this->db_opcao == 4 ) {
        $this->montaSinteticocheck( $pai->codigo, $pai->estrutural, $pai->nome, $pai->nivel );
      } else {
        $this->montaSintetico( $pai->codigo, $pai->estrutural, $pai->nome, $pai->nivel );
      }

      echo "</table>";

      if( $fieldset == 1 ) {
        echo"</fieldset>";
      }
    } else {
      echo(" <b> Dados do atributo($iAtributo_pai) nao encontrados! </b> ");
    }
  }
   
  /**
   * Monta tada estrutur de atributos em PDF no formato paisagem ocupando a folha de lado a lado
   * @param $iAtributo_pai
   * @param $irequiitem
   * @param $bordas
   * @param $corCelula
   * @param $negrito
   * @param $orientacao
   */
  function atributosPDF( $pdf, $iExame, $iAtributo_pai, $irequiitem = "", $bordas = 1 ) {

    $this->irequiitem = $irequiitem;
    $this->iBordas    = $bordas;
    $this->oPDF       = $pdf;
    $pai=$this->dadosAtributo( $iAtributo_pai );
    $this->iExame = $iExame;
    $this->listarDisp();

    if( $pai != false ) {
      $this->montaSinteticoPDF( $pai->codigo, $pai->estrutural, $pai->nome, $pai->nivel );
    } else {
      die(" <b> Dados do atributo($iAtributo_pai) nao encontrados! </b> ");
    }
  }

  function atributosMapaPDF( $pdf, $iExame, $iAtributo_pai, $bordas = 1 ) {

    $this->iBordas = $bordas;
    $this->oPDF    = $pdf;
    $pai=$this->dadosAtributo( $iAtributo_pai );
    $this->iExame = $iExame;
    $this->listarDisp();

    if( $pai != false ) {
      $this->montaSinteticoMapaPDF( $pai->codigo, $pai->estrutural, $pai->nome, $pai->nivel );
    } else {
      die(" <b> Dados do atributo($iAtributo_pai) nao encontrados! </b> ");
    }
  }

  /**
   * Gera uma lista com o nome dos inputs criados para receber os valores de cada atributo
   * @return String com lista de input's utilizados na entrada de atributos
   */
  function getInputs() {

    $sStr = "";
    $sSep = "";

    for( $x = 0; $x < count( $this->aRepositorios ); $x++ ) {

      $sStr = $sStr . $sSep . $this->aRepositorios[$x];
      $sSep = ",";
    }

    return $sStr;
  }

  //funções internas da classe

  /**
   * retorna uma string com &nbsp; de acordo com a quantidade selecionada e o nivel do atriburto
   * @param $iNivel - Nivel do atributo
   * @param $iQuant - Qauntidade de espaços que ser dada para dividir cada nivel
   */
  function nivel( $iNivel, $iQuant, $iTipo = 1 ) {

    $sStr = "";
    $iNivel--;

    if( $iTipo == 1 ) {
      $sSep = "&nbsp;";
    } else {
      $sSep = " ";
    }

    for( $x = 0; $x < $iNivel; $x++ ) {

      for( $y = 0; $y < $iQuant; $y++ ) {
        $sStr .= $sSep;
      }
    }

    return $sStr;
  }

  /**
   * Remover casas nÃ£o utilizadas do estrutural
   * @param $sEstrutural estrutural do atributo tem que utilizar somente 0(zero) e '.' na sua mascara
   */
  function removeCasas( $sEstrutural ) {

	  $aVet = explode( ".", $sEstrutural );
	  $sStr = "$aVet[0]";

	  for( $x = 1; $x < count($aVet); $x++ ) {

	  	if( substr_count( $aVet[$x], '0' ) < strlen( $aVet[$x] ) ) {
	      $sStr.=".$aVet[$x]";
	    }
    }

    return $sStr;
  }

  /**
   * carrega os dados de referencia de um atributo definindo se ele é alphanumerico ou numerico
   * @param $iAtributo_codigo
   * @return stdClass Objeto com com parametros codigo,unidade,alfanumerino,numerico,etc...
   */
  function dadosRef( $iAtributo_codigo ) {

  	$sCampos  = " la30_i_codigo, la30_f_normalmin, la30_f_normalmax, la30_f_absurdomin, la30_f_absurdomax";
    $sCampos .= ", la30_c_calculavel, la13_c_descr, la29_i_codigo, la29_i_fixo";

    $sSql    = cl_lab_valorreferencia::sql_query_tipos( "", $sCampos, "", "la27_i_atributo = {$iAtributo_codigo} " );
    $rResult = cl_lab_valorreferencia::sql_record( $sSql );

    if( $rResult != false ) {

    	$aResultado               = db_utils::getCollectionByRecord($rResult);
      $oResultado               = new stdClass;
      $oResultado->numerico     = $aResultado[0]->la30_i_codigo;
      $oResultado->normalmin    = $aResultado[0]->la30_f_normalmin;
      $oResultado->normalmax    = $aResultado[0]->la30_f_normalmax;
      $oResultado->absurdomin   = $aResultado[0]->la30_f_absurdomin;
      $oResultado->absurdomax   = $aResultado[0]->la30_f_absurdomax;
      $oResultado->calculavel   = $aResultado[0]->la30_c_calculavel;
      $oResultado->unidade      = $aResultado[0]->la13_c_descr;
      $oResultado->alfanumerico = $aResultado[0]->la29_i_codigo;
  	  $oResultado->fixo         = $aResultado[0]->la29_i_fixo;

  	  return $oResultado;
    } else {
      return false;
    }
  }
   
  /**
   * carrega o dado ja lançado na referencia para determinado intem da requisicao
   * @param $iAtributo_codigo
   * @param integer $itipo define qual o tipo de dados 1 numerico, 2 alfanumerico, 3 selecionave
   * @return integer - Com o valor do referencial numerico
   */
  function loadRef( $iAtributo_codigo,$iTipo = 1 ) {

  	if( $iTipo == 1 ) {
      $sCampos = " la41_f_valor as valor ";
  	} else if( $iTipo == 3 ) {
  	  $sCampos=" la40_c_valor as valor ";
  	} else {
  	  $sCampos=" la40_i_valorrefsel as valor ";
  	}

    $sWhere  = " la39_i_atributo = {$iAtributo_codigo} and la52_i_requiitem = {$this->irequiitem} ";
    $sSql    = cl_lab_resultado::sql_query_item( "", $sCampos, "", $sWhere );
    $rResult = cl_lab_resultado::sql_record( $sSql );

    if( $rResult != false ) {

    	$oValor = db_utils::getCollectionByRecord($rResult);
      return $oValor[0]->valor;
    } else {
      return "";
    }
  }
   
  /**
   * Carrega todos os dados de um atributo
   * @param $iAtributo_codigo
   * @return stdClass Objeto com com parametros codigo,nome,estrutural,tipo,nivel
   */
  function dadosAtributo( $iatributo_codigo ) {

    $sCampos  = "la25_i_codigo as codigo, la25_c_descr as nome, la25_c_estrutural as estrutural, la25_c_tipo as tipo";
    $sCampos .= ", la25_i_nivel as nivel ";
    $sSql     = cl_lab_atributo::sql_query( $iatributo_codigo, $sCampos );
    $rResult  = cl_lab_atributo::sql_record( $sSql );

    if( $rResult != false ) {

    	$aResultado             = db_utils::getCollectionByRecord($rResult);
    	$oResultado             = new StdClass;
      $oResultado->codigo     = $aResultado[0]->codigo;
      $oResultado->nome       = $aResultado[0]->nome;
      $oResultado->estrutural = $aResultado[0]->estrutural;
      $oResultado->tipo       = $aResultado[0]->tipo;
      $oResultado->nivel      = $aResultado[0]->nivel;

      return $oResultado;
    } else {
      return false;
    }
  }
   
  /**
   * Retorna um objeto com os filhos da mÃ£e e seus atributos
   * @param  integer $iPai_atributo codigo do atributo pai
   * @return array com os parametros de cadafilho referente ao estrutural
   */
  function levantamentoFilhos( $iPai_atributo ) {

    $oFilhos = array();
  	$sCampos = " la26_i_exameatributofilho as codigo ";
    $sWhere  = " la26_i_exameatributopai = {$iPai_atributo} ";
    $sSql    = cl_lab_exameatributoligacao::sql_query_file( "", $sCampos, " la26_i_exameatributofilho ", $sWhere );
    $rResult = cl_lab_atributo::sql_record( $sSql );

    if( $rResult != false ) {

    	if( pg_num_rows( $rResult ) > 0 ) {

        $aitens = db_utils::getCollectionByRecord( $rResult );

        for( $x = 0; $x < count( $aitens ); $x++ ) {

       	  $ofilho                    = $this->dadosAtributo($aitens[$x]->codigo);
       	  $oFilhos[$x]['codigo']     = $ofilho->codigo;
          $oFilhos[$x]['nome']       = $ofilho->nome;
       	  $oFilhos[$x]['estrutural'] = $ofilho->estrutural;
       	  $oFilhos[$x]['tipo']       = $ofilho->tipo;
       	  $oFilhos[$x]['nivel']      = $ofilho->nivel;
        }
      }
    }

  	return $oFilhos;
  }

  /**
   * Retorna um objeto com os filhos da mãe e seus atributos
   * @param integer $iPai_atributo codigo do atributo pai
   * @return array com os parametros de cadafilho referente ao estrutural
   */
  function valorSelecionavel( $iReferencia ) {

    $aOptions = array();
  	$sCampos  = " la28_i_codigo as codigo ,la28_c_descr as nome";
    $sSql     = cl_lab_valorrefselgrupo::sql_query( "", $sCampos, "", " la51_i_referencia = {$iReferencia} " );
    $rResult  = cl_lab_valorrefselgrupo::sql_record( $sSql );

    if( $rResult != false ) {

      if( pg_num_rows( $rResult ) > 0 ) {

        $aitens = db_utils::getCollectionByRecord($rResult);
        for( $x = 0; $x < count( $aitens ); $x++ ) {

      	  $aOptions[$x][0] = $aitens[$x]->codigo;
          $aOptions[$x][1] = $aitens[$x]->nome;
        }
      }
    }

  	return $aOptions;
  }

  /**
   * Cria o imput que recebe e exibe o valor do atributo
   * @param $iAtributo_codigo - codigo do atributo a ser lido
   */
  function valorRef( $iAtributo_codigo ) {

	  $oValorRef = $this->dadosRef( $iAtributo_codigo );
	  $sStr      = "";

	  if( $oValorRef != false ) {

	    if( $oValorRef->alfanumerico != "" ) {

	    	if( $oValorRef->fixo > 0 ) {

	    	  $sValorRefLoad = "";

	    		if( $this->db_opcao == 2 ) {
	   	      $sValorRefLoad=$this->loadRef( $iAtributo_codigo, 3 );
	        }

	        $this->aRepositorios[] = "inp_A{$iAtributo_codigo}#1";
	    	  $sStr = "<input type='text'
	    	                  tabindex=\"".count($this->aRepositorios)."\"
	    	                  onkeydown=\"return js_controla_tecla_enter(this,event);\"
	    	                  name='inp_A$iAtributo_codigo'
	    	                  id='inp_A$iAtributo_codigo'
	    	                  value='$sValorRefLoad'
	    	                  size=\"20\"  >";
	    	} else {

	    		$iValorRefLoad="";
	        if( $this->db_opcao == 2 ) {
	   	      $iValorRefLoad=$this->loadRef( $iAtributo_codigo, 2 );
	        }

	        $this->aRepositorios[] = "sel_A{$iAtributo_codigo}#2";
	        $sStr = "<select tabindex=\"".count($this->aRepositorios)."\"
	                         onkeydown=\"return js_controla_tecla_enter(this,event);\"
	                         name=\"sel_A$iAtributo_codigo\"
	                         id=\"sel_A$iAtributo_codigo\" >";
	        $aOptions = $this->valorSelecionavel( $oValorRef->alfanumerico );

	      	for( $x = 0; $x < count( $aOptions ); $x++ ) {

	      	  $sChecked = "";

	      	  if( $aOptions[$x][0] == $iValorRefLoad ) {
	      	    $sChecked="selected";
	      	  }

	      	  $sStr .= " <option value=\"".$aOptions[$x][0]."\" $sChecked > ".$aOptions[$x][1]." </option>";
	        }

	        $sStr.="</select>";
	      }
	    } else {

	   	  $iValorRefLoad = "";

	      if( $this->db_opcao == 2 ) {
	   	    $iValorRefLoad=$this->loadRef( $iAtributo_codigo, 1 );
	      }

	      $this->aRepositorios[] = "inp_A{$iAtributo_codigo}#3";
	   	  $sStr = "{$oValorRef->calculavel}<input type='text'
	   	                                          onkeydown=\"return js_controla_tecla_enter(this,event);\"
	   	                                          tabindex=\"".count($this->aRepositorios)."\"
	   	                                          name='inp_A$iAtributo_codigo'
	   	                                          id='inp_A$iAtributo_codigo'
	   	                                          value='$iValorRefLoad'
	   	                                          onchange=\"js_ValidaValor$iAtributo_codigo(this.value)\"
	   	                                          size=\"5\"  > $oValorRef->unidade";
        $sStr.=" <script>
                 function js_ValidaValor$iAtributo_codigo( valor ) {

	                 if( ( valor < $oValorRef->absurdomin ) || ( valor > $oValorRef->absurdomax ) ) {

	                   alert('O Valor deve estar entre: \\n       $oValorRef->absurdomin até $oValorRef->absurdomax');
	                   document.getElementById('inp_A$iAtributo_codigo').value = '';
	                 }

	                 if( ( valor < $oValorRef->normalmin ) || ( valor > $oValorRef->normalmax ) ) {
	                   document.getElementById('inp_A$iAtributo_codigo').style.backgroundColor='red';
	                 } else {
	                   document.getElementById('inp_A$iAtributo_codigo').style.backgroundColor='lightgreen';
	                 }
	               }
                 </script> ";
      }
	  } else {
	    $sStr = "<b> valor referencial nao encontrado! </b>";
	  }

	  return $sStr;
  }

  /**
   * Retorna o valor minimo e maximo do referencial
   * @param $iAtributo_codigo - codigo do atributo a ser lido
   */
  function infoValorRef( $iAtributo_codigo ) {

	  $oValorRef = $this->dadosRef( $iAtributo_codigo );
	  $sStr      = "";

	  if( $oValorRef != false ) {

	    if( $oValorRef->alfanumerico == "" ) {
	   	  $sStr = $oValorRef->normalmin." até  ".$oValorRef->normalmax;
      }
	  }

	  return $sStr;
  }

  /**
   * retorna o valor do atributo para o PDF
   * @param $iAtributo_codigo - codigo do atributo a ser lido
   */
  function valorRefPDF( $iAtributo_codigo, $lPdf = true ) {

	  $oValorRef = $this->dadosRef( $iAtributo_codigo );
	  $sStr      = "";

	  if( $oValorRef != false ) {

	    if( $oValorRef->alfanumerico != "" ) {

	    	if( $oValorRef->fixo > 0 ) {

	    	  $sValorRefLoad = "";
	   	    $sValorRefLoad = $this->loadRef( $iAtributo_codigo, 3 );
	    	  $sStr          = "$sValorRefLoad";
	    	} else {

	    		$iValorRefLoad = "";
	   	    $iValorRefLoad = $this->loadRef( $iAtributo_codigo, 2 );
	        $aOptions      = $this->valorSelecionavel( $oValorRef->alfanumerico );

	      	for( $x = 0; $x < count( $aOptions ); $x++ ) {

	      	  if( $aOptions[$x][0] == $iValorRefLoad ) {
              $sStr.= $aOptions[$x][1];
	      	  }
	        }
	      }
	    } else {

        $iValorRefLoad = $this->loadRef( $iAtributo_codigo );
        $sFont1        = $sFont2 = "";

        if( $lPdf == false ) {

        	if( ( $iValorRefLoad < $oValorRef->normalmin ) || ( $iValorRefLoad > $oValorRef->normalmax ) ) {

        	  $sFont1 = "<font style=\"background-color: red;\" >";
        	  $sFont2 = "</font>";
        	}
        }

        if( $oValorRef->calculavel != "" ) {
          $sStr = "$oValorRef->calculavel = $sFont1 $iValorRefLoad $sFont2 $oValorRef->unidade";
        } else {
          $sStr = "$sFont1 $iValorRefLoad $sFont2 $oValorRef->unidade ";
        }
      }
	  } else {
	    $sStr = " valor referencial nao encontrado! ";
	  }

	  return $sStr;
  }

  /**
   * retorna o valor do atributo para o PDF
   * @param $iAtributo_codigo - codigo do atributo a ser lido
   */
  function valorRefMapaPDF( $iAtributo_codigo ) {

	  $oValorRef = $this->dadosRef( $iAtributo_codigo );
    $sStr      = "";

	  if( $oValorRef != false ) {

	    if( $oValorRef->alfanumerico != "" ) {

	    	if( $oValorRef->fixo > 0 ) {
	    	  $sStr = "_______________________";
	    	} else {

	        $aOptions = $this->valorSelecionavel( $oValorRef->alfanumerico );

	      	for( $x = 0; $x < count( $aOptions ); $x++ ) {
            $sStr .= "( )".$aOptions[$x][1]." \n";
	        }
	      }
	    } else {

        if( $oValorRef->calculavel != "" ) {
          $sStr = "$oValorRef->calculavel = __________ $oValorRef->unidade";
        } else {
          $sStr = " __________ $oValorRef->unidade";
        }
      }
	  } else {
	    $sStr = " valor referencial nao encontrado! ";
	  }

	  return $sStr;
  }
   
  /**
   * Verifica se o atributo é válido para este exame
   * @param $iPai_atributo
   * @return boolean retorna falso caso o atributo tenha sido descartado pelo usuario
   */
  function showAtributo( $iAtributo ) {

  	if( $this->arrayBusca( $iAtributo, $this->atributosdisp ) ) {
  	  return false;
    }

  	return true;
  }
   
  /**
   * carrega lista de atributos dipensados no exame
   */
  function listarDisp() {

    $sCampos = " la50_i_atributo as item";
    $sSql    = cl_lab_examedisp::sql_query( "", $sCampos, "", " la42_i_exame = {$this->iExame} ");
    $rResult = cl_lab_examedisp::sql_record( $sSql );
    $aLista  = array();

    if( $rResult != false ) {

      $oLista = db_utils::getCollectionByRecord($rResult);

      for( $x = 0; $x < count( $oLista ); $x++ ) {
        $aLista[$x] = $oLista[$x]->item;
      }

      $this->atributosdisp = $aLista;
    }
  }

  /**
   *
   * @param $iAtributo
   */
  function marca( $iAtributo ) {

  	$check = "checked";

    if( $this->arrayBusca( $iAtributo, $this->atributosdisp ) ) {
      $check = "";
    }

    $sNome = $this->aRepositorios[] = "chek_A{$iAtributo}";
	  $sStr  = " <input type=\"checkbox\" name=\"$sNome\" id=\"$sNome\" $check> ";

	  return $sStr;
  }
   
  /**
   * Função recursiva que monta as linhas da tabela de edição e exibição dos atributos
   * @param $iPai_atributo - codigo do atributo pai
   * @param $cPai_estrutural - estrutural do atributo pai
   * @param $cPai_nome - descrição do atributo pai
   * @param $cPai_nivel - nivel do atributo pai
   * @param $db_opcao - opção de exibição
   * @param $irequiitem - codigo do item da requisicao utilizado para carrega o valor do banco
   */
  function montaSintetico( $iPai_atributo, $cPai_estrutural, $cPai_nome, $cPai_nivel ) {

    if( $this->showAtributo( $iPai_atributo ) ) {
  	  echo "<tr><td colspan=3><b>".$this->nivel($cPai_nivel,5).$this->removeCasas($cPai_estrutural)." - $cPai_nome </b></td></tr>";
    }

  	$oFilhos = $this->levantamentoFilhos( $iPai_atributo );

  	for( $x = 0; $x < count( $oFilhos ); $x++ ) {

      if( $oFilhos[$x]['tipo'] == 1 ) {
        $this->montaSintetico( $oFilhos[$x]['codigo'], $oFilhos[$x]['estrutural'], $oFilhos[$x]['nome'], $oFilhos[$x]['nivel'] );
      } else {

        if( $this->showAtributo( $oFilhos[$x]['codigo'] ) ) {

      	  $sValor = "";

        	if( $this->db_opcao == 3 ) {
        		$sValor = $this->valorRefPDF( $oFilhos[$x]['codigo'], false );
        	} else {
        		$sValor = $this->valorRef( $oFilhos[$x]['codigo'] );
        	}

      	  echo "<tr><td>".$this->nivel($oFilhos[$x]['nivel'],5).$this->removeCasas($oFilhos[$x]['estrutural'])." - ".$oFilhos[$x]['nome']."</td>
                 <td align=\"right\">$sValor</td><td>".$this->infoValorRef($oFilhos[$x]['codigo'])."</td></tr>";
        }
      }
    }
  }

  /**
   * Função recursiva que monta as linhas da tabela de edição e exibição dos atributos
   * @param $iPai_atributo - codigo do atributo pai
   * @param $cPai_estrutural - estrutural do atributo pai
   * @param $cPai_nome - descrição do atributo pai
   * @param $cPai_nivel - nivel do atributo pai
   * @param $db_opcao - opção de exibição
   * @param $irequiitem - codigo do item da requisicao utilizado para carrega o valor do banco
   */
  function montaSinteticocheck( $iPai_atributo, $cPai_estrutural, $cPai_nome, $cPai_nivel ) {

    echo "<tr><td colspan=2><b>".$this->nivel($cPai_nivel,5).$this->marca($iPai_atributo).$this->removeCasas($cPai_estrutural)." - $cPai_nome </b></td></tr>";
    $oFilhos = $this->levantamentoFilhos( $iPai_atributo );

    for( $x = 0; $x < count( $oFilhos ); $x++ ) {

      if( $oFilhos[$x]['tipo'] == 1 ) {
        $this->montaSinteticocheck( $oFilhos[$x]['codigo'], $oFilhos[$x]['estrutural'], $oFilhos[$x]['nome'], $oFilhos[$x]['nivel'] );
      } else {
        echo "<tr><td>".$this->nivel($oFilhos[$x]['nivel'],5).$this->marca($oFilhos[$x]['codigo']).$this->removeCasas($oFilhos[$x]['estrutural'])." - ".$oFilhos[$x]['nome']."</td></tr>";
      }
    }
  }
   
  /**
   * Função recursiva que monta as linhas da tabela de edição e exibição dos atributos
   * @param $iPai_atributo - codigo do atributo pai
   * @param $cPai_estrutural - estrutural do atributo pai
   * @param $cPai_nome - descrição do atributo pai
   * @param $cPai_nivel - nivel do atributo pai
   * @param $db_opcao - opção de exibição
   * @param $irequiitem - codigo do item da requisicao utilizado para carrega o valor do banco
   */
  function montaSinteticoPDF( $iPai_atributo, $cPai_estrutural, $cPai_nome, $cPai_nivel ) {

    if( $this->showAtributo( $iPai_atributo ) ) {

  	  $this->oPDF->setfont( 'arial', 'b', 8 );
  	  $this->oPDF->cell( 190, 5, $this->nivel( $cPai_nivel, 5, 2 ) . $this->removeCasas( $cPai_estrutural ) . " - {$cPai_nome} ", $this->iBordas, 1, "L", 0 );
    }

  	$oFilhos = $this->levantamentoFilhos( $iPai_atributo );

  	for( $x = 0; $x < count( $oFilhos ); $x++ ) {

      if( $oFilhos[$x]['tipo'] == 1 ) {
        $this->montaSinteticoPDF( $oFilhos[$x]['codigo'], $oFilhos[$x]['estrutural'], $oFilhos[$x]['nome'], $oFilhos[$x]['nivel'] );
      } else {

        if( $this->showAtributo( $oFilhos[$x]['codigo'] ) ) {

        	$this->oPDF->setfont( 'arial', '', 8 );
      	  $this->oPDF->cell( 120, 5, $this->nivel( $oFilhos[$x]['nivel'], 5, 2 ) . $this->removeCasas( $oFilhos[$x]['estrutural'] ) . " - " . $oFilhos[$x]['nome'], $this->iBordas, 0, "L", 0 );
        	$this->oPDF->cell( 70, 5, "" . $this->valorRefPDF( $oFilhos[$x]['codigo'] ), $this->iBordas, 1, "R", 0 );
        }
      }
    }
  }
   
  /**
   * Função recursiva que monta as linhas da tabela de edição e exibição dos atributos
   * @param $iPai_atributo - codigo do atributo pai
   * @param $cPai_estrutural - estrutural do atributo pai
   * @param $cPai_nome - descrição do atributo pai
   * @param $cPai_nivel - nivel do atributo pai
   * @param $db_opcao - opção de exibição
   * @param $irequiitem - codigo do item da requisicao utilizado para carrega o valor do banco
   */
  function montaSinteticoMapaPDF( $iPai_atributo, $cPai_estrutural, $cPai_nome, $cPai_nivel ) {

 	  if( $this->showAtributo( $iPai_atributo ) ) {

 	    $this->oPDF->setfont( 'arial', 'b', 8 );
  	  $this->oPDF->cell( 190, 5, $this->nivel( $cPai_nivel, 5, 2 ) . $this->removeCasas( $cPai_estrutural ) . " - {$cPai_nome} ", $this->iBordas, 1, "L", 0 );
    }

  	$oFilhos = $this->levantamentoFilhos( $iPai_atributo );

  	for( $x = 0; $x < count( $oFilhos ); $x++ ) {

      if( $oFilhos[$x]['tipo'] == 1 ) {
        $this->montaSinteticoMapaPDF( $oFilhos[$x]['codigo'], $oFilhos[$x]['estrutural'], $oFilhos[$x]['nome'], $oFilhos[$x]['nivel'] );
      } else {

        if( $this->showAtributo( $oFilhos[$x]['codigo'] ) ) {

        	$this->oPDF->setfont( 'arial', '', 8 );
        	$borda2 = "LTR";

          if( $x == ( count( $oFilhos ) - 1 ) ) {
            $borda2 = 1;
          }

        	$this->oPDF->cell( 120, 5, $this->nivel( $oFilhos[$x]['nivel'], 5, 2 ) . $this->removeCasas( $oFilhos[$x]['estrutural'] ) . " - " . $oFilhos[$x]['nome'], $borda2, 0, "L", 0 );
          $this->oPDF->multicell( 70, 5, "" . $this->valorRefMapaPDF( $oFilhos[$x]['codigo'] ), $this->iBordas, 1, "R", 0 );
        }
      }
    }
  }

  /**
   *
   * @param $valor
   * @param $array
   */
  function arrayBusca( $valor, $array ) {

  	if( !empty( $array ) ) {

  	  foreach( $array as $akey => $aval ) {

        if( $aval == $valor ) {
  	  	  return true;
        }
      }
  	}

  	return false;
  }

  /**
   * Converte um array para objeto
   * @param $array
   * @return objeto
   */
  function array_to_object( $array = array() ) {

    if( !empty( $array ) ) {

      $data = false;

      foreach( $array as $akey => $aval ) {
        $data -> {$akey} = $aval;
      }

      return $data;
    }

    return false;
  }
}