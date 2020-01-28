<?

function getCaracteristicasConstrucao( $sGrupo = null, $iCodigoArquivo = null) {
  
  $aCaracteristicaConstrucao = array();

  /**
   * Características da utilização da construção
   *  1 => 101 => Fixa
   *  2 => 102 => Fechada
   *  3 => 103 => Temporária
   */  
     
  $aCaracteristicaConstrucao['utilizacao'] = array(1 => 100, 
                                                   2 => 101,
                                                   3 => 102);
                                                 
  /*
   * Características da localização da unidade da construção
   * 1 => 250 =>
   * 2 => 251 =>
   * 3 => 252 =>
   * 4 => 253 =>
   * 5 => 254 =>
   * 6 => 255 =>
   * 7 => 256 =>
   * 8 => 257 =>
   * 9 => 258 =>
   *
   */
     
  $aCaracteristicaConstrucao['localizacao'] = array(1 => 250,                                             
                                                    2 => 251,
                                                    3 => 252,
                                                    4 => 253,
                                                    5 => 254,
                                                    6 => 255,
                                                    7 => 256,
                                                    8 => 257,
                                                    9 => 258);

  /*
   * Características do tipo de construção
   * 1  => 105 => Casa
   * 2  => 106 => Apartamento
   * 3  => 107 => Sala
   * 4  => 108 => Loja
   * 5  => 109 => Galpão Aberto
   * 6  => 110 => Galpão Fechado
   * 7  => 111 => Telheiro
   * 8  => 112 => Dependência
   * 9  => 113 => Barraco 
   * 10 => 114 => Outros
   * 
   */
  
  $aCaracteristicaConstrucao['tipo'] = array(1  => 105,  
                                             2  => 106,  
                                             3  => 107,  
                                             4  => 108,  
                                             5  => 109,  
                                             6  => 110, 
                                             7  => 111,  
                                             8  => 112,  
                                             9  => 113,  
                                             10 => 114);   

  /*
   * Características do padrão construtivo
   *
   * 1 => 631 => Fino
   * 2 => 632 => Bom
   * 3 => 633 => Regular
   * 4 => 634 => Popular
   * 5 => 635 => Rústico
   *
   */

  $aCaracteristicaConstrucao['padraoconstrutivo'] = array(1 => 630,
                                                          2 => 631,
                                                          3 => 632,
                                                          4 => 633,
                                                          5 => 634);

  /*
   * Características da conservação da construção
   *
   * 1 => 640 => Nova 
   * 2 => 641 => Boa
   * 3 => 643 => Regular
   * 4 => 644 => Má
   *
   */

  $aCaracteristicaConstrucao['conservacao'] = array(1 => 640,
                                                    2 => 641,
                                                    3 => 643,
                                                    4 => 644);
                                                   
  /*
   * Características do uso da construção
   *  1 => 300 => Residencial
   *  2 => 301 => Comercial
   *  3 => 302 => Industrial
   *  4 => 303 => Religioso
   *  5 => 304 => Administração Pública
   *  6 => 305 => Serviços 
   *  7 => 306 => Atividades Culturais
   */

  $aCaracteristicaConstrucao['uso'] = array(1 => 300, 
                                            2 => 301, 
                                            3 => 302, 
                                            4 => 303, 
                                            5 => 304, 
                                            6 => 305, 
                                            7 => 306); 

 /*
   * Características da estrutura da construção
   *  1 => 350 => Adobe
   *  2 => 351 => Taipa
   *  3 => 352 => Madeira
   *  4 => 353 => Alvenaria
   *  5 => 354 => Metálica
   *  6 => 355 => Concreto
   *  7 => 356 => Mista
   */

  $aCaracteristicaConstrucao['estrutura'] = array(1 => 350, 
                                                  2 => 351, 
                                                  3 => 352, 
                                                  4 => 353, 
                                                  5 => 354, 
                                                  6 => 355, 
                                                  7 => 356);  

  /*
   * Características da água da construção
   *  1 => 360 => Sem
   *  2 => 361 => Poço
   *  3 => 362 => Rede
   */

  $aCaracteristicaConstrucao['agua'] = array(1 => 360, 
                                             2 => 361, 
                                             3 => 362); 


  /*
   * Características do esgoto da construção
   *  1 => 370 => Ligado a rede
   *  2 => 371 => Sem
   *  3 => 372 => Fossa Negra
   *  4 => 373 => Fossa Séptica
   */

  $aCaracteristicaConstrucao['esgoto']    = array(1 => 370, 
                                                  2 => 371, 
                                                  3 => 372, 
                                                  4 => 373);

  /*
   * Características da instalação elétrica da construção
   *  1 => 370 => Sem  
   *  2 => 371 => Externa
   *  3 => 372 => Semi-embutido
   *  4 => 373 => Embutido
   */

  $aCaracteristicaConstrucao['eletrica']    = array(1 => 380, 
                                                    2 => 381, 
                                                    3 => 382, 
                                                    4 => 383); 
  /*
   * Características da instalação elétrica da construção
   *  1 => 390 => Sem 
   *  2 => 391 => Externa
   *  3 => 392 => Interna
   *  4 => 393 => Mais de uma
   */

  $aCaracteristicaConstrucao['sanitaria']    = array(1 => 390, 
                                                     2 => 391, 
                                                     3 => 392, 
                                                     4 => 393); 

  /*
   * Características da cobertura da construção
   *  1 => 400 => Palha
   *  2 => 401 => Zinco
   *  3 => 402 => Telha
   *  4 => 403 => Amianto
   *  5 => 404 => Laje
   *  6 => 405 => Especial
   */

  $aCaracteristicaConstrucao['cobertura']    = array(1 => 400, 
                                                     2 => 401, 
                                                     3 => 402, 
                                                     4 => 403,   
                                                     5 => 404,   
                                                     6 => 405);  
  /*
   * Características da esquadria da construção
   *  1 => 410 => Sem
   *  2 => 411 => Precário
   *  3 => 412 => Boa
   *  4 => 413 => Especial
   */

  $aCaracteristicaConstrucao['esquadria']    = array(1 => 410, 
                                                     2 => 411, 
                                                     3 => 412, 
                                                     4 => 413); 

  /*
   * Características do piso da construção
   *  1 => 420 => Tijolo
   *  2 => 421 => Cimento
   *  3 => 422 => Tábua
   *  4 => 423 => Taco
   *  5 => 424 => Cerâmica
   *  6 => 425 => Especial
   */

  $aCaracteristicaConstrucao['piso']    = array(1 => 420, 
                                                2 => 421, 
                                                3 => 422, 
                                                4 => 423,
                                                5 => 424,
                                                6 => 425);  

  /*
   * Características do piso da construção
   *  1 => 430 => Sem
   *  2 => 431 => Reboco
   *  3 => 432 => Cerâmica
   */

  $aCaracteristicaConstrucao['revestimento']    = array(1 => 430, 
                                                        2 => 431, 
                                                        3 => 432);

  /**
   * Características de pavimento da construção
   * 1 => 120
   * 2 => 121
   * 3 => 122
   * ...
   */

  $aCaracteristicaConstrucao['pavimento']       = array( 1 => 120,
                                                         2 => 121,
                                                         3 => 122,
                                                         4 => 123,
                                                         5 => 124,
                                                         6 => 125,
                                                         7 => 126,
                                                         8 => 127,
                                                         9 => 128,
                                                        10 => 129,
                                                        11 => 130,
                                                        12 => 131,
                                                        13 => 132,
                                                        14 => 133,
                                                        15 => 134,
                                                        16 => 135,
                                                        17 => 136,
                                                        18 => 137,
                                                        19 => 138,
                                                        20 => 139,
                                                        21 => 140,
                                                        22 => 141,
                                                        23 => 142,
                                                        24 => 143,
                                                        25 => 144,
                                                        26 => 145,
                                                        27 => 146,
                                                        28 => 147,
                                                        29 => 148,
                                                        30 => 149,
                                                        31 => 150,
                                                        32 => 151,
                                                        33 => 152,
                                                        34 => 153,
                                                        35 => 154,
                                                        36 => 155,
                                                        37 => 156,
                                                        38 => 157,
                                                        39 => 158,
                                                        40 => 159,
                                                        41 => 160,
                                                        42 => 161,
                                                        43 => 162,
                                                        44 => 163,
                                                        45 => 164,
                                                        46 => 165,
                                                        47 => 166,
                                                        48 => 167,
                                                        49 => 168,
                                                        50 => 169,
                                                        51 => 170,
                                                        52 => 171,
                                                        53 => 172,
                                                        54 => 173,
                                                        55 => 174,
                                                        56 => 175,
                                                        57 => 176,
                                                        58 => 177,
                                                        59 => 178,
                                                        60 => 179,
                                                        61 => 180,
                                                        62 => 181,
                                                        63 => 182,
                                                        64 => 183,
                                                        65 => 184,
                                                        66 => 185,
                                                        67 => 186,
                                                        68 => 187,
                                                        69 => 188,
                                                        70 => 189,
                                                        71 => 190,
                                                        72 => 191,
                                                        73 => 192,
                                                        74 => 193,
                                                        75 => 194,
                                                        76 => 195,
                                                        77 => 196,
                                                        78 => 197,
                                                        79 => 198,
                                                        80 => 199,
                                                        81 => 200,
                                                        82 => 201,
                                                        83 => 202,
                                                        84 => 203,
                                                        85 => 204,
                                                        86 => 205,
                                                        87 => 206,
                                                        88 => 207,
                                                        89 => 208,
                                                        90 => 209,
                                                        91 => 210,
                                                        92 => 211,
                                                        93 => 212,
                                                        94 => 213,
                                                        95 => 214,
                                                        96 => 215,
                                                        97 => 216,
                                                        98 => 217,
                                                        99 => 218,
                                                       100 => 219);



  if ( !is_null( $sGrupo ) ) {

    if ( !is_null( $iCodigoArquivo ) ) {
       return isset($aCaracteristicaConstrucao[$sGrupo][$iCodigoArquivo]) ? $aCaracteristicaConstrucao[$sGrupo][$iCodigoArquivo] : 0;
    }
    return isset($aCaracteristicaConstrucao[$sGrupo]) ? $aCaracteristicaConstrucao[$sGrupo] : array();
  }

  return $aCaracteristicaConstrucao;

}

function getCaracteristicasLote( $sGrupo = null, $iCodigoArquivo = null ) {
 
  $aCaracteristicaLote = array();

  /**
   * Características do lote do tipo de propriedade
   *  1 => 30 => Federal
   *  2 => 31 => Estadual
   *  3 => 32 => Municipal
   *  4 => 33 => Religioso
   *  5 => 34 => Particular
   */  
     
  $aCaracteristicaLote['propriedade']    = array(1 => 30, 
                                                 2 => 31,
                                                 3 => 32,
                                                 4 => 33,
                                                 5 => 34) ;

  /**
   * Características do lote do tipo de situacao
   *  1 => 40 => Esquina 
   *  2 => 41 => Encravado
   *  3 => 42 => Meio de Quadra
   *  4 => 43 => Toda Quadra
   *  5 => 44 => Gleba
   */  
 
  $aCaracteristicaLote['situacao']       = array(1 => 40, 
                                                 2 => 41,
                                                 3 => 42,
                                                 4 => 43,
                                                 5 => 44);

  /**
   * Características do lote tipo de lote 
   *  1 => 50 => Acidentado 
   *  2 => 51 => Horizontal
   */   
  $aCaracteristicaLote['caracteristica'] = array(1 => 50, 
                                                 2 => 51); 
 
  /**
   * Características do lote nível
   *  1 => 55 => Nível          
   *  2 => 56 => Acima
   *  3 => 57 => Abaixo
   */  
 
  $aCaracteristicaLote['nivel']          = array(1 => 55, 
                                                 2 => 56,
                                                 3 => 57);
  /**
   * Características do lote frentes
   * 0 frentes => 70
   * 1 frentes => 71
   * 2 frentes => 72
   * 3 frentes => 73
   * 4 frentes => 74
   * 5 frentes => 75
   * 6 frentes => 76
   * 7 frentes => 77
   * 8 frentes => 78
   * 9 frentes => 79
   */
 
  $aCaracteristicaLote['frentes']        = array(0 => 70,
                                                 1 => 71, 
                                                 2 => 72,
                                                 3 => 73,
                                                 4 => 74,
                                                 5 => 75,
                                                 6 => 76,
                                                 7 => 77,
                                                 8 => 78,
                                                 9 => 79);
                    
  /**
   * Características do lote ocupação
   * 1 => 60 => Vago
   * 2 => 61 => Edificado
   * 3 => 62 => Em construção
   * 4 => 63 => Construção Paralisada
   * 5 => 64 => Em demolição
   * 6 => 65 => Ruína
   * 7 => 66 => Praça
   * 8 => 67 => Edif. temp
   */  
 
  $aCaracteristicaLote['ocupacao']       = array(1 => 60,
                                                 2 => 61, 
                                                 3 => 62,
                                                 4 => 63,
                                                 5 => 64,
                                                 6 => 65,
                                                 7 => 66,
                                                 8 => 67); 
  if ( !is_null( $sGrupo ) ) {

    if ( !is_null( $iCodigoArquivo ) ) {
      return isset($aCaracteristicaLote[$sGrupo][$iCodigoArquivo]) ? $aCaracteristicaLote[$sGrupo][$iCodigoArquivo] : 0;
    }
    return isset($aCaracteristicaLote[$sGrupo]) ? $aCaracteristicaLote[$sGrupo] : array();
  }                  
  return $aCaracteristicaLote;
}  

?>
