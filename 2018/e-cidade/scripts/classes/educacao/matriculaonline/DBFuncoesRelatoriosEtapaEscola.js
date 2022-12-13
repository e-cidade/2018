/**
 * Verifica se o código da fase foi selecionado
 * @return boolean
 */
function validaCodigoFaseSelecionado() {

  if ( $F('mo04_codigo') == '') {

    alert('Selecione a fase antes.');
    return false;
  }
  return true;
}

function limpaCamposEtapa() {

    $('ed11_i_codigo').value = '';
    $('ed11_c_descr').value  = '';
}

function limpaCamposEscola() {

    $('ed18_i_codigo').value = '';
    $('ed18_c_nome').value   = '';
}

function pesquisaEtapaFase( lMostra, iTipo ) {

  if ( !validaCodigoFaseSelecionado() ){

    limpaCamposEtapa();
    return ;
  }

  var sUrl = 'func_etapamatriculaonline.php';
  sUrl    += '?iFase=' + $F('mo04_codigo');
  sUrl    += '&iEscola=' + $F('ed18_i_codigo');
  sUrl    += '&iTipoConsulta=' + iTipo;
  if( lMostra ) {

    sUrl += '&funcao_js=parent.retornoPesquisaEtapaFase|ed11_i_codigo|ed11_c_descr';
    js_OpenJanelaIframe( '', 'db_iframe_etapa_matricula_online', sUrl, 'Pesquisa Etapa', true);

  } else if ( $F('ed11_i_codigo') != '' ) {

    sUrl += '&funcao_js=parent.retornoPesquisaEtapaFase';
    sUrl += '&pesquisa_chave=' + $F('ed11_i_codigo');
    js_OpenJanelaIframe( '', 'db_iframe_etapa_matricula_online', sUrl, 'Pesquisa Etapa', false);

  } else {
    limpaCamposEtapa();
  }

}

function retornoPesquisaEtapaFase () {

  if ( typeof arguments[1] == 'boolean') {

    $('ed11_c_descr').value  = arguments[0];
    if (arguments[1]) {
      $('ed11_i_codigo').value = '';
    }

    return;
  }

  $('ed11_i_codigo').value = arguments[0];
  $('ed11_c_descr').value  = arguments[1];
  db_iframe_etapa_matricula_online.hide();

}

function pesquisaEscolaFase( lMostra, iTipo ) {

  if ( !validaCodigoFaseSelecionado() ) {

    limpaCamposEscola();
    return false;
  }

  var sUrl = 'func_escolamatriculaonline.php';
  sUrl    += '?iFase='  + $F('mo04_codigo');
  sUrl    += '&iEtapa=' + $F('ed11_i_codigo');
  sUrl    += '&iTipoConsulta=' + iTipo;
  if( lMostra ) {

    sUrl += '&funcao_js=parent.retornoPesquisaEscolaFase|ed18_i_codigo|ed18_c_nome';
    js_OpenJanelaIframe( '', 'db_iframe_escola', sUrl, 'Pesquisa Escola', true);

  } else if ($F('ed18_i_codigo') != '') {

    sUrl += '&funcao_js=parent.retornoPesquisaEscolaFase';
    sUrl += '&pesquisa_chave=' + $F('ed18_i_codigo');
    js_OpenJanelaIframe( '', 'db_iframe_escola', sUrl, 'Pesquisa Escola', false);

  } else {
    limpaCamposEscola();
  }

}

function retornoPesquisaEscolaFase () {

  if( typeof arguments[1] == "boolean" ) {

    $('ed18_c_nome').value = arguments[0];

    if ( arguments[1] ) {
      $('ed18_i_codigo').value = '';
    }
    return;
  }

  $('ed18_c_nome').value   = arguments[1];
  $('ed18_i_codigo').value = arguments[0];
  db_iframe_escola.hide()
}