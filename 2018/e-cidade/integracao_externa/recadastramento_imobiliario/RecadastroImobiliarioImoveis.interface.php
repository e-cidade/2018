<?php

/**
 * Interface para Ser utilizada no processamento 
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
interface RecadastroImobiliarioImoveisInterface {
  
  /**
   * Construtor da Classe
   * @param object $oRegistro
   */
  function __construct( $oRegistro );
  
  /**
   * M�todo para processar a manuten��o do Imovel 
   */
  function processar();
  /**
   * Lan�a Ocorrencia
   */
  function registrarOcorrencia();

  /**
   * Retorna o Log do Processamento 
   * 
   * @access public
   * @return void
   */
  function getLog();
}
