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
   * Método para processar a manutenção do Imovel 
   */
  function processar();
  /**
   * Lança Ocorrencia
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
