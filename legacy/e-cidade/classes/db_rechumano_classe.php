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

//MODULO: escola
//CLASSE DA ENTIDADE rechumano
class cl_rechumano { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ed20_i_codigo = 0; 
   var $ed20_c_outroscursos = null; 
   var $ed20_c_posgraduacao = null; 
   var $ed20_i_escolaridade = 0; 
   var $ed20_i_censomunicender = 0; 
   var $ed20_i_censoufender = 0; 
   var $ed20_c_passaporte = null; 
   var $ed20_i_censoufcert = 0; 
   var $ed20_c_certidaocart = null; 
   var $ed20_c_certidaodata_dia = null; 
   var $ed20_c_certidaodata_mes = null; 
   var $ed20_c_certidaodata_ano = null; 
   var $ed20_c_certidaodata = null; 
   var $ed20_c_certidaolivro = null; 
   var $ed20_c_certidaofolha = null; 
   var $ed20_c_certidaonum = null; 
   var $ed20_i_certidaotipo = 0; 
   var $ed20_d_dataident_dia = null; 
   var $ed20_d_dataident_mes = null; 
   var $ed20_d_dataident_ano = null; 
   var $ed20_d_dataident = null; 
   var $ed20_i_censoufident = 0; 
   var $ed20_i_censoorgemiss = 0; 
   var $ed20_c_identcompl = null; 
   var $ed20_i_censomunicnat = 0; 
   var $ed20_i_censoufnat = 0; 
   var $ed20_i_nacionalidade = 0; 
   var $ed20_i_raca = 0; 
   var $ed20_c_nis = null; 
   var $ed20_i_codigoinep = 0; 
   var $ed20_i_pais = 0; 
   var $ed20_i_tiposervidor = 0; 
   var $ed20_i_rhregime = 0; 
   var $ed20_c_efetividade = null; 
   var $ed20_i_censocartorio = 0; 
   var $ed20_i_zonaresidencia = 0;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed20_i_codigo = int8 = Código 
                 ed20_c_outroscursos = char(15) = Outros Cursos 
                 ed20_c_posgraduacao = char(10) = Pós - Graduação 
                 ed20_i_escolaridade = int4 = Escolaridade 
                 ed20_i_censomunicender = int4 = Município Endereço 
                 ed20_i_censoufender = int4 = UF Endereço 
                 ed20_c_passaporte = char(20) = N° Passaporte 
                 ed20_i_censoufcert = int4 = UF Cartório 
                 ed20_c_certidaocart = char(100) = Nome do Cartório 
                 ed20_c_certidaodata = date = Data Emissão 
                 ed20_c_certidaolivro = char(8) = Livro 
                 ed20_c_certidaofolha = char(4) = Folha 
                 ed20_c_certidaonum = char(8) = Número do Termo 
                 ed20_i_certidaotipo = int4 = Certidão Tipo 
                 ed20_d_dataident = date = Data de Expedição 
                 ed20_i_censoufident = int4 = UF da Identidade 
                 ed20_i_censoorgemiss = int4 = Órgão Emissor 
                 ed20_c_identcompl = char(4) = Complemento 
                 ed20_i_censomunicnat = int4 = Município de Nascimento 
                 ed20_i_censoufnat = int4 = UF de nascimento 
                 ed20_i_nacionalidade = int4 = Nacionalidade 
                 ed20_i_raca = int4 = Cor/Raça 
                 ed20_c_nis = char(11) = N° NIS 
                 ed20_i_codigoinep = int8 = Código INEP 
                 ed20_i_pais = int4 = País 
                 ed20_i_tiposervidor = int4 = Servidor da Prefeitura 
                 ed20_i_rhregime = int8 = Regime 
                 ed20_c_efetividade = char(1) = Informar Efetividade 
                 ed20_i_censocartorio = int4 = Cartório 
                 ed20_i_zonaresidencia = int4 = Zona de Residência 
                 ";
   //funcao construtor da classe 
   function cl_rechumano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumano"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ed20_i_codigo = ($this->ed20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]:$this->ed20_i_codigo);
       $this->ed20_c_outroscursos = ($this->ed20_c_outroscursos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_outroscursos"]:$this->ed20_c_outroscursos);
       $this->ed20_c_posgraduacao = ($this->ed20_c_posgraduacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_posgraduacao"]:$this->ed20_c_posgraduacao);
       $this->ed20_i_escolaridade = ($this->ed20_i_escolaridade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_escolaridade"]:$this->ed20_i_escolaridade);
       $this->ed20_i_censomunicender = ($this->ed20_i_censomunicender == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicender"]:$this->ed20_i_censomunicender);
       $this->ed20_i_censoufender = ($this->ed20_i_censoufender == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufender"]:$this->ed20_i_censoufender);
       $this->ed20_c_passaporte = ($this->ed20_c_passaporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_passaporte"]:$this->ed20_c_passaporte);
       $this->ed20_i_censoufcert = ($this->ed20_i_censoufcert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufcert"]:$this->ed20_i_censoufcert);
       $this->ed20_c_certidaocart = ($this->ed20_c_certidaocart == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaocart"]:$this->ed20_c_certidaocart);
       if($this->ed20_c_certidaodata == ""){
         $this->ed20_c_certidaodata_dia = ($this->ed20_c_certidaodata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_dia"]:$this->ed20_c_certidaodata_dia);
         $this->ed20_c_certidaodata_mes = ($this->ed20_c_certidaodata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_mes"]:$this->ed20_c_certidaodata_mes);
         $this->ed20_c_certidaodata_ano = ($this->ed20_c_certidaodata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_ano"]:$this->ed20_c_certidaodata_ano);
         if($this->ed20_c_certidaodata_dia != ""){
            $this->ed20_c_certidaodata = $this->ed20_c_certidaodata_ano."-".$this->ed20_c_certidaodata_mes."-".$this->ed20_c_certidaodata_dia;
         }
       }
       $this->ed20_c_certidaolivro = ($this->ed20_c_certidaolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaolivro"]:$this->ed20_c_certidaolivro);
       $this->ed20_c_certidaofolha = ($this->ed20_c_certidaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaofolha"]:$this->ed20_c_certidaofolha);
       $this->ed20_c_certidaonum = ($this->ed20_c_certidaonum == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaonum"]:$this->ed20_c_certidaonum);
       $this->ed20_i_certidaotipo = ($this->ed20_i_certidaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_certidaotipo"]:$this->ed20_i_certidaotipo);
       if($this->ed20_d_dataident == ""){
         $this->ed20_d_dataident_dia = ($this->ed20_d_dataident_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_dia"]:$this->ed20_d_dataident_dia);
         $this->ed20_d_dataident_mes = ($this->ed20_d_dataident_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_mes"]:$this->ed20_d_dataident_mes);
         $this->ed20_d_dataident_ano = ($this->ed20_d_dataident_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_ano"]:$this->ed20_d_dataident_ano);
         if($this->ed20_d_dataident_dia != ""){
            $this->ed20_d_dataident = $this->ed20_d_dataident_ano."-".$this->ed20_d_dataident_mes."-".$this->ed20_d_dataident_dia;
         }
       }
       $this->ed20_i_censoufident = ($this->ed20_i_censoufident == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufident"]:$this->ed20_i_censoufident);
       $this->ed20_i_censoorgemiss = ($this->ed20_i_censoorgemiss == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censoorgemiss"]:$this->ed20_i_censoorgemiss);
       $this->ed20_c_identcompl = ($this->ed20_c_identcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_identcompl"]:$this->ed20_c_identcompl);
       $this->ed20_i_censomunicnat = ($this->ed20_i_censomunicnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicnat"]:$this->ed20_i_censomunicnat);
       $this->ed20_i_censoufnat = ($this->ed20_i_censoufnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufnat"]:$this->ed20_i_censoufnat);
       $this->ed20_i_nacionalidade = ($this->ed20_i_nacionalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_nacionalidade"]:$this->ed20_i_nacionalidade);
       $this->ed20_i_raca = ($this->ed20_i_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_raca"]:$this->ed20_i_raca);
       $this->ed20_c_nis = ($this->ed20_c_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_nis"]:$this->ed20_c_nis);
       $this->ed20_i_codigoinep = ($this->ed20_i_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_codigoinep"]:$this->ed20_i_codigoinep);
       $this->ed20_i_pais = ($this->ed20_i_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_pais"]:$this->ed20_i_pais);
       $this->ed20_i_tiposervidor = ($this->ed20_i_tiposervidor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_tiposervidor"]:$this->ed20_i_tiposervidor);
       $this->ed20_i_rhregime = ($this->ed20_i_rhregime == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_rhregime"]:$this->ed20_i_rhregime);
       $this->ed20_c_efetividade = ($this->ed20_c_efetividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_c_efetividade"]:$this->ed20_c_efetividade);
       $this->ed20_i_censocartorio = ($this->ed20_i_censocartorio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_censocartorio"]:$this->ed20_i_censocartorio);
       $this->ed20_i_zonaresidencia = ($this->ed20_i_zonaresidencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_zonaresidencia"]:$this->ed20_i_zonaresidencia);
     }else{
       $this->ed20_i_codigo = ($this->ed20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]:$this->ed20_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed20_i_codigo){ 
      $this->atualizacampos();
     if($this->ed20_c_outroscursos == null ){ 
       $this->erro_sql = " Campo Outros Cursos nao Informado.";
       $this->erro_campo = "ed20_c_outroscursos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_escolaridade == null ){ 
       $this->erro_sql = " Campo Escolaridade nao Informado.";
       $this->erro_campo = "ed20_i_escolaridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_censomunicender == null || $this->ed20_i_censomunicender == " "){
       $this->ed20_i_censomunicender = "null";
     }
     if($this->ed20_i_censoufender == null || $this->ed20_i_censoufender == " "){
       $this->ed20_i_censoufender = "null";
     }
     if($this->ed20_i_censoufcert == null || $this->ed20_i_censoufcert == " "){
       $this->ed20_i_censoufcert = "null";
     }
     if($this->ed20_c_certidaodata == null ){
       $this->ed20_c_certidaodata = "null";
     }
     if($this->ed20_i_certidaotipo == null ){
       $this->ed20_i_certidaotipo = "null";
     }
     if($this->ed20_d_dataident == null ){
       $this->ed20_d_dataident = "null";
     }
     if($this->ed20_i_censoufident == null || $this->ed20_i_censoufident == " "){
       $this->ed20_i_censoufident = "null";
     }
     if($this->ed20_i_censoorgemiss == null || $this->ed20_i_censoorgemiss == " " ){
       $this->ed20_i_censoorgemiss = "null";
     }
     if($this->ed20_i_censomunicnat == null || $this->ed20_i_censomunicnat == " " ){
       $this->ed20_i_censomunicnat = "null";
     }
     if($this->ed20_i_censoufnat == null || $this->ed20_i_censoufnat == " " ){
       $this->ed20_i_censoufnat = "null";
     }
     if($this->ed20_i_nacionalidade == null ){ 
       $this->erro_sql = " Campo Nacionalidade nao Informado.";
       $this->erro_campo = "ed20_i_nacionalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_raca == null ){ 
       $this->erro_sql = " Campo Cor/Raça nao Informado.";
       $this->erro_campo = "ed20_i_raca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_codigoinep == null ){ 
       $this->ed20_i_codigoinep = "null";
     }
     if($this->ed20_i_pais == null ){ 
       $this->erro_sql = " Campo País nao Informado.";
       $this->erro_campo = "ed20_i_pais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_tiposervidor == null ){ 
       $this->erro_sql = " Campo Servidor da Prefeitura nao Informado.";
       $this->erro_campo = "ed20_i_tiposervidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_rhregime == null ){ 
       $this->ed20_i_rhregime = "null";
     }
     if($this->ed20_c_efetividade == null ){ 
       $this->erro_sql = " Campo Informar Efetividade nao Informado.";
       $this->erro_campo = "ed20_c_efetividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed20_i_censocartorio == null || $this->ed20_i_censocartorio == " " ){ 
       $this->ed20_i_censocartorio = "null";
     }
     if($this->ed20_i_zonaresidencia == null ){
       $this->erro_sql = " Campo Zona de Residência nao Informado.";
       $this->erro_campo = "ed20_i_zonaresidencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed20_i_codigo == "" || $ed20_i_codigo == null ){
       $result = db_query("select nextval('rechumano_ed20_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumano_ed20_i_codigo_seq do campo: ed20_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed20_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumano_ed20_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed20_i_codigo)){
         $this->erro_sql = " Campo ed20_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed20_i_codigo = $ed20_i_codigo; 
       }
     }
     if(($this->ed20_i_codigo == null) || ($this->ed20_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed20_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumano(
                                       ed20_i_codigo 
                                      ,ed20_c_outroscursos 
                                      ,ed20_c_posgraduacao 
                                      ,ed20_i_escolaridade 
                                      ,ed20_i_censomunicender 
                                      ,ed20_i_censoufender 
                                      ,ed20_c_passaporte 
                                      ,ed20_i_censoufcert 
                                      ,ed20_c_certidaocart 
                                      ,ed20_c_certidaodata 
                                      ,ed20_c_certidaolivro 
                                      ,ed20_c_certidaofolha 
                                      ,ed20_c_certidaonum 
                                      ,ed20_i_certidaotipo 
                                      ,ed20_d_dataident 
                                      ,ed20_i_censoufident 
                                      ,ed20_i_censoorgemiss 
                                      ,ed20_c_identcompl 
                                      ,ed20_i_censomunicnat 
                                      ,ed20_i_censoufnat 
                                      ,ed20_i_nacionalidade 
                                      ,ed20_i_raca 
                                      ,ed20_c_nis 
                                      ,ed20_i_codigoinep 
                                      ,ed20_i_pais 
                                      ,ed20_i_tiposervidor 
                                      ,ed20_i_rhregime 
                                      ,ed20_c_efetividade 
                                      ,ed20_i_censocartorio 
                                      ,ed20_i_zonaresidencia 
                       )
                values (
                                $this->ed20_i_codigo 
                               ,'$this->ed20_c_outroscursos' 
                               ,'$this->ed20_c_posgraduacao' 
                               ,$this->ed20_i_escolaridade 
                               ,$this->ed20_i_censomunicender 
                               ,$this->ed20_i_censoufender 
                               ,'$this->ed20_c_passaporte' 
                               ,$this->ed20_i_censoufcert 
                               ,'$this->ed20_c_certidaocart' 
                               ,".($this->ed20_c_certidaodata == "null" || $this->ed20_c_certidaodata == ""?"null":"'".$this->ed20_c_certidaodata."'")." 
                               ,'$this->ed20_c_certidaolivro' 
                               ,'$this->ed20_c_certidaofolha' 
                               ,'$this->ed20_c_certidaonum' 
                               ,$this->ed20_i_certidaotipo 
                               ,".($this->ed20_d_dataident == "null" || $this->ed20_d_dataident == ""?"null":"'".$this->ed20_d_dataident."'")." 
                               ,$this->ed20_i_censoufident 
                               ,$this->ed20_i_censoorgemiss 
                               ,'$this->ed20_c_identcompl' 
                               ,$this->ed20_i_censomunicnat 
                               ,$this->ed20_i_censoufnat 
                               ,$this->ed20_i_nacionalidade 
                               ,$this->ed20_i_raca 
                               ,'$this->ed20_c_nis' 
                               ,$this->ed20_i_codigoinep 
                               ,$this->ed20_i_pais 
                               ,$this->ed20_i_tiposervidor 
                               ,$this->ed20_i_rhregime 
                               ,'$this->ed20_c_efetividade' 
                               ,$this->ed20_i_censocartorio 
                               ,$this->ed20_i_zonaresidencia
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Recursos Humanos ($this->ed20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Recursos Humanos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Recursos Humanos ($this->ed20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed20_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008513,'$this->ed20_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010087,1008513,'','".AddSlashes(pg_result($resaco,0,'ed20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13819,'','".AddSlashes(pg_result($resaco,0,'ed20_c_outroscursos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13818,'','".AddSlashes(pg_result($resaco,0,'ed20_c_posgraduacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13817,'','".AddSlashes(pg_result($resaco,0,'ed20_i_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13816,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censomunicender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13815,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censoufender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13814,'','".AddSlashes(pg_result($resaco,0,'ed20_c_passaporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13813,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censoufcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13812,'','".AddSlashes(pg_result($resaco,0,'ed20_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13811,'','".AddSlashes(pg_result($resaco,0,'ed20_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13810,'','".AddSlashes(pg_result($resaco,0,'ed20_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13809,'','".AddSlashes(pg_result($resaco,0,'ed20_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13808,'','".AddSlashes(pg_result($resaco,0,'ed20_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13807,'','".AddSlashes(pg_result($resaco,0,'ed20_i_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13806,'','".AddSlashes(pg_result($resaco,0,'ed20_d_dataident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13805,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censoufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13804,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censoorgemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13803,'','".AddSlashes(pg_result($resaco,0,'ed20_c_identcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13802,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censomunicnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13801,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censoufnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13800,'','".AddSlashes(pg_result($resaco,0,'ed20_i_nacionalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13799,'','".AddSlashes(pg_result($resaco,0,'ed20_i_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13798,'','".AddSlashes(pg_result($resaco,0,'ed20_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13797,'','".AddSlashes(pg_result($resaco,0,'ed20_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,13823,'','".AddSlashes(pg_result($resaco,0,'ed20_i_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,17288,'','".AddSlashes(pg_result($resaco,0,'ed20_i_tiposervidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,17336,'','".AddSlashes(pg_result($resaco,0,'ed20_i_rhregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,17444,'','".AddSlashes(pg_result($resaco,0,'ed20_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,18011,'','".AddSlashes(pg_result($resaco,0,'ed20_i_censocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010087,18919,'','".AddSlashes(pg_result($resaco,0,'ed20_i_zonaresidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed20_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rechumano set ";
     $virgula = "";
     if(trim($this->ed20_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"])){ 
       $sql  .= $virgula." ed20_i_codigo = $this->ed20_i_codigo ";
       $virgula = ",";
       if(trim($this->ed20_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed20_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_c_outroscursos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_outroscursos"])){ 
       $sql  .= $virgula." ed20_c_outroscursos = '$this->ed20_c_outroscursos' ";
       $virgula = ",";
       if(trim($this->ed20_c_outroscursos) == null ){ 
         $this->erro_sql = " Campo Outros Cursos nao Informado.";
         $this->erro_campo = "ed20_c_outroscursos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_c_posgraduacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_posgraduacao"])){ 
       $sql  .= $virgula." ed20_c_posgraduacao = '$this->ed20_c_posgraduacao' ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_escolaridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_escolaridade"])){ 
       $sql  .= $virgula." ed20_i_escolaridade = $this->ed20_i_escolaridade ";
       $virgula = ",";
       if(trim($this->ed20_i_escolaridade) == null ){ 
         $this->erro_sql = " Campo Escolaridade nao Informado.";
         $this->erro_campo = "ed20_i_escolaridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
        if(trim($this->ed20_i_censomunicender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicender"])){
        if(trim($this->ed20_i_censomunicender)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicender"])){
           $this->ed20_i_censomunicender = "null" ;
        }
       $sql  .= $virgula." ed20_i_censomunicender = $this->ed20_i_censomunicender ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_censoufender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufender"])){
        if(trim($this->ed20_i_censoufender)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufender"])){
           $this->ed20_i_censoufender = "null" ;
        }
       $sql  .= $virgula." ed20_i_censoufender = $this->ed20_i_censoufender ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_passaporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_passaporte"])){
       $sql  .= $virgula." ed20_c_passaporte = '$this->ed20_c_passaporte' ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_censoufcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufcert"])){
        if(trim($this->ed20_i_censoufcert)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufcert"])){
           $this->ed20_i_censoufcert = "null" ;
        }
       $sql  .= $virgula." ed20_i_censoufcert = $this->ed20_i_censoufcert ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_certidaocart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaocart"])){
       $sql  .= $virgula." ed20_c_certidaocart = '$this->ed20_c_certidaocart' ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_certidaodata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_dia"] !="") ){
       $sql  .= $virgula." ed20_c_certidaodata = '$this->ed20_c_certidaodata' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata_dia"])){
         $sql  .= $virgula." ed20_c_certidaodata = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed20_c_certidaolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaolivro"])){
       $sql  .= $virgula." ed20_c_certidaolivro = '$this->ed20_c_certidaolivro' ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_certidaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaofolha"])){
       $sql  .= $virgula." ed20_c_certidaofolha = '$this->ed20_c_certidaofolha' ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_certidaonum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaonum"])){
       $sql  .= $virgula." ed20_c_certidaonum = '$this->ed20_c_certidaonum' ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_certidaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_certidaotipo"])){
        if(trim($this->ed20_i_certidaotipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_certidaotipo"])){
           $this->ed20_i_certidaotipo = "null" ;
        }
       $sql  .= $virgula." ed20_i_certidaotipo = $this->ed20_i_certidaotipo ";
       $virgula = ",";
     }
     if(trim($this->ed20_d_dataident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_dia"] !="") ){
       $sql  .= $virgula." ed20_d_dataident = '$this->ed20_d_dataident' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident_dia"])){
         $sql  .= $virgula." ed20_d_dataident = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed20_i_censoufident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufident"])){
        if(trim($this->ed20_i_censoufident)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufident"])){
           $this->ed20_i_censoufident = "null" ;
        }
       $sql  .= $virgula." ed20_i_censoufident = $this->ed20_i_censoufident ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_censoorgemiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoorgemiss"])){
        if(trim($this->ed20_i_censoorgemiss)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoorgemiss"])){
           $this->ed20_i_censoorgemiss = "null" ;
        }
       $sql  .= $virgula." ed20_i_censoorgemiss = $this->ed20_i_censoorgemiss ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_identcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_identcompl"])){
       $sql  .= $virgula." ed20_c_identcompl = '$this->ed20_c_identcompl' ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_censomunicnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicnat"])){
        if(trim($this->ed20_i_censomunicnat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicnat"])){
           $this->ed20_i_censomunicnat = "null" ;
        }
       $sql  .= $virgula." ed20_i_censomunicnat = $this->ed20_i_censomunicnat ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_censoufnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufnat"])){
        if(trim($this->ed20_i_censoufnat)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufnat"])){
           $this->ed20_i_censoufnat = "null" ;
        }
       $sql  .= $virgula." ed20_i_censoufnat = $this->ed20_i_censoufnat ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_nacionalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_nacionalidade"])){ 
       $sql  .= $virgula." ed20_i_nacionalidade = $this->ed20_i_nacionalidade ";
       $virgula = ",";
       if(trim($this->ed20_i_nacionalidade) == null ){ 
         $this->erro_sql = " Campo Nacionalidade nao Informado.";
         $this->erro_campo = "ed20_i_nacionalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_i_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_raca"])){ 
       $sql  .= $virgula." ed20_i_raca = $this->ed20_i_raca ";
       $virgula = ",";
       if(trim($this->ed20_i_raca) == null ){ 
         $this->erro_sql = " Campo Cor/Raça nao Informado.";
         $this->erro_campo = "ed20_i_raca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_c_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_nis"])){ 
       $sql  .= $virgula." ed20_c_nis = '$this->ed20_c_nis' ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigoinep"])){ 
        if(trim($this->ed20_i_codigoinep)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigoinep"])){ 
           $this->ed20_i_codigoinep = "null" ; 
        } 
       $sql  .= $virgula." ed20_i_codigoinep = $this->ed20_i_codigoinep ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_pais"])){ 
       $sql  .= $virgula." ed20_i_pais = $this->ed20_i_pais ";
       $virgula = ",";
       if(trim($this->ed20_i_pais) == null ){ 
         $this->erro_sql = " Campo País nao Informado.";
         $this->erro_campo = "ed20_i_pais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_i_tiposervidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_tiposervidor"])){ 
       $sql  .= $virgula." ed20_i_tiposervidor = $this->ed20_i_tiposervidor ";
       $virgula = ",";
       if(trim($this->ed20_i_tiposervidor) == null ){ 
         $this->erro_sql = " Campo Servidor da Prefeitura nao Informado.";
         $this->erro_campo = "ed20_i_tiposervidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_i_rhregime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_rhregime"])){ 
        if(trim($this->ed20_i_rhregime)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_rhregime"])){ 
           $this->ed20_i_rhregime = "null" ; 
        } 
       $sql  .= $virgula." ed20_i_rhregime = $this->ed20_i_rhregime ";
       $virgula = ",";
     }
     if(trim($this->ed20_c_efetividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_efetividade"])){ 
       $sql  .= $virgula." ed20_c_efetividade = '$this->ed20_c_efetividade' ";
       $virgula = ",";
       if(trim($this->ed20_c_efetividade) == null ){ 
         $this->erro_sql = " Campo Informar Efetividade nao Informado.";
         $this->erro_campo = "ed20_c_efetividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed20_i_censocartorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censocartorio"])){ 
        if(trim($this->ed20_i_censocartorio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censocartorio"])){ 
           $this->ed20_i_censocartorio = "null" ; 
        } 
       $sql  .= $virgula." ed20_i_censocartorio = $this->ed20_i_censocartorio ";
       $virgula = ",";
     }
     if(trim($this->ed20_i_zonaresidencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_zonaresidencia"])){
       $sql  .= $virgula." ed20_i_zonaresidencia = $this->ed20_i_zonaresidencia ";
       $virgula = ",";
       if(trim($this->ed20_i_zonaresidencia) == null ){
         $this->erro_sql = " Campo Zona de Residência nao Informado.";
         $this->erro_campo = "ed20_i_zonaresidencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed20_i_codigo!=null){
       $sql .= " ed20_i_codigo = $this->ed20_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed20_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008513,'$this->ed20_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigo"]) || $this->ed20_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010087,1008513,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_codigo'))."','$this->ed20_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_outroscursos"]) || $this->ed20_c_outroscursos != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13819,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_outroscursos'))."','$this->ed20_c_outroscursos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_posgraduacao"]) || $this->ed20_c_posgraduacao != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13818,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_posgraduacao'))."','$this->ed20_c_posgraduacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_escolaridade"]) || $this->ed20_i_escolaridade != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13817,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_escolaridade'))."','$this->ed20_i_escolaridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicender"]) || $this->ed20_i_censomunicender != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13816,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censomunicender'))."','$this->ed20_i_censomunicender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufender"]) || $this->ed20_i_censoufender != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13815,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censoufender'))."','$this->ed20_i_censoufender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_passaporte"]) || $this->ed20_c_passaporte != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13814,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_passaporte'))."','$this->ed20_c_passaporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufcert"]) || $this->ed20_i_censoufcert != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13813,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censoufcert'))."','$this->ed20_i_censoufcert',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaocart"]) || $this->ed20_c_certidaocart != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13812,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_certidaocart'))."','$this->ed20_c_certidaocart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaodata"]) || $this->ed20_c_certidaodata != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13811,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_certidaodata'))."','$this->ed20_c_certidaodata',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaolivro"]) || $this->ed20_c_certidaolivro != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13810,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_certidaolivro'))."','$this->ed20_c_certidaolivro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaofolha"]) || $this->ed20_c_certidaofolha != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13809,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_certidaofolha'))."','$this->ed20_c_certidaofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_certidaonum"]) || $this->ed20_c_certidaonum != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13808,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_certidaonum'))."','$this->ed20_c_certidaonum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_certidaotipo"]) || $this->ed20_i_certidaotipo != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13807,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_certidaotipo'))."','$this->ed20_i_certidaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_d_dataident"]) || $this->ed20_d_dataident != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13806,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_d_dataident'))."','$this->ed20_d_dataident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufident"]) || $this->ed20_i_censoufident != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13805,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censoufident'))."','$this->ed20_i_censoufident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoorgemiss"]) || $this->ed20_i_censoorgemiss != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13804,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censoorgemiss'))."','$this->ed20_i_censoorgemiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_identcompl"]) || $this->ed20_c_identcompl != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13803,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_identcompl'))."','$this->ed20_c_identcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censomunicnat"]) || $this->ed20_i_censomunicnat != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13802,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censomunicnat'))."','$this->ed20_i_censomunicnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censoufnat"]) || $this->ed20_i_censoufnat != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13801,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censoufnat'))."','$this->ed20_i_censoufnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_nacionalidade"]) || $this->ed20_i_nacionalidade != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13800,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_nacionalidade'))."','$this->ed20_i_nacionalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_raca"]) || $this->ed20_i_raca != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13799,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_raca'))."','$this->ed20_i_raca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_nis"]) || $this->ed20_c_nis != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13798,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_nis'))."','$this->ed20_c_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_codigoinep"]) || $this->ed20_i_codigoinep != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13797,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_codigoinep'))."','$this->ed20_i_codigoinep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_pais"]) || $this->ed20_i_pais != "")
           $resac = db_query("insert into db_acount values($acount,1010087,13823,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_pais'))."','$this->ed20_i_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_tiposervidor"]) || $this->ed20_i_tiposervidor != "")
           $resac = db_query("insert into db_acount values($acount,1010087,17288,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_tiposervidor'))."','$this->ed20_i_tiposervidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_rhregime"]) || $this->ed20_i_rhregime != "")
           $resac = db_query("insert into db_acount values($acount,1010087,17336,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_rhregime'))."','$this->ed20_i_rhregime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_c_efetividade"]) || $this->ed20_c_efetividade != "")
           $resac = db_query("insert into db_acount values($acount,1010087,17444,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_c_efetividade'))."','$this->ed20_c_efetividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_censocartorio"]) || $this->ed20_i_censocartorio != "")
           $resac = db_query("insert into db_acount values($acount,1010087,18011,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_censocartorio'))."','$this->ed20_i_censocartorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed20_i_zonaresidencia"]) || $this->ed20_i_zonaresidencia != "")
           $resac = db_query("insert into db_acount values($acount,1010087,18919,'".AddSlashes(pg_result($resaco,$conresaco,'ed20_i_zonaresidencia'))."','$this->ed20_i_zonaresidencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recursos Humanos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recursos Humanos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed20_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed20_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008513,'$ed20_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010087,1008513,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13819,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_outroscursos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13818,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_posgraduacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13817,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13816,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censomunicender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13815,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censoufender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13814,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_passaporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13813,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censoufcert'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13812,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_certidaocart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13811,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_certidaodata'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13810,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_certidaolivro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13809,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_certidaofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13808,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_certidaonum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13807,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_certidaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13806,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_d_dataident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13805,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censoufident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13804,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censoorgemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13803,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_identcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13802,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censomunicnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13801,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censoufnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13800,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_nacionalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13799,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_raca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13798,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13797,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_codigoinep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,13823,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,17288,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_tiposervidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,17336,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_rhregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,17444,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_c_efetividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,18011,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_censocartorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010087,18919,'','".AddSlashes(pg_result($resaco,$iresaco,'ed20_i_zonaresidencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rechumano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed20_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed20_i_codigo = $ed20_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recursos Humanos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recursos Humanos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rechumano";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
// funcao do sql
   function sql_query ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from rechumano ";
     //se rh vem da rhpessoal
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                                          and rhpessoalmov.rh02_mesusu  = $mes
                                          and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                          and rhpessoalmov.rh02_instit  = $instit";
     $sql .= "      left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      left join rhpesdoc  on  rhpesdoc.rh16_regist = rhpessoal.rh01_regist";
     $sql .= "      left join rhlota  on  rhlota.r70_codigo = rhpessoal.rh01_lotac";
     $sql .= "      left join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      left join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      left join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      left join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     //se rh vem direto do cgm
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join cgmdoc on  cgmdoc.z02_i_cgm = cgmcgm.z01_numcgm";
     $sql .= "      left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime";
     //demais ligações da rechumano
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = rechumano.ed20_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = rechumano.ed20_i_censoufcert";
     $sql .= "      left  join censouf as censoufender on  censoufender.ed260_i_codigo = rechumano.ed20_i_censoufender";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censomunic as censomunicender on  censomunicender.ed261_i_codigo = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }


   // funcao do sql
   function sql_query_file ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from rechumano ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  function sql_query_escola ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from rechumano ";
     //se rh vem da rhpessoal
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                                          and rhpessoalmov.rh02_mesusu  = $mes
                                          and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                          and rhpessoalmov.rh02_instit  = $instit";
     $sql .= "      left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      left join rhlota  on  rhlota.r70_codigo = rhpessoal.rh01_lotac";
     $sql .= "      left join rhpesdoc  on  rhpesdoc.rh16_regist = rhpessoal.rh01_regist";
     $sql .= "      left join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      left join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit";
     $sql .= "      left join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      left join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     //se rh vem direto do cgm
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join cgmdoc on  cgmdoc.z02_i_cgm = cgmcgm.z01_numcgm";
     $sql .= "      left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime";
    // $sql .= "      inner join rhregime on rhregime.rh30_codreg = rechumano.ed20_i_rhregime ";
     //demais ligações da rechumano
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      left join relacaotrabalho  on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join rechumanoativ  on  rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql .= "      left join disciplina  on  disciplina.ed12_i_codigo = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      left join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = rechumano.ed20_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = rechumano.ed20_i_censoufcert";
     $sql .= "      left  join censouf as censoufender on  censoufender.ed260_i_codigo = rechumano.ed20_i_censoufender";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censomunic as censomunicender on  censomunicender.ed261_i_codigo = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
function sql_query_censo ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from rechumano ";
     //se rh vem da rhpessoal
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                                          and rhpessoalmov.rh02_mesusu  = $mes
                                          and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                          and rhpessoalmov.rh02_instit  = $instit";
     $sql .= "      left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      left join rhlota  on  rhlota.r70_codigo = rhpessoal.rh01_lotac";
     $sql .= "      left join rhpesdoc  on  rhpesdoc.rh16_regist = rhpessoal.rh01_regist";
     $sql .= "      left join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      left join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit";
     $sql .= "      left join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      left join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     //se rh vem direto do cgm
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join cgmdoc on  cgmdoc.z02_i_cgm = cgmcgm.z01_numcgm";
     $sql .= "      left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime";
     $sql .= "      left join rhregime on rhregime.rh30_codreg = rechumano.ed20_i_rhregime ";
     //demais ligações da rechumano
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      left join relacaotrabalho  on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join rechumanoativ  on  rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql .= "      left join disciplina  on  disciplina.ed12_i_codigo = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      left join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf as censoufident on  censoufident.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censouf as censoufnat on  censoufnat.ed260_i_codigo = rechumano.ed20_i_censoufnat";
     $sql .= "      left  join censouf as censoufcert on  censoufcert.ed260_i_codigo = rechumano.ed20_i_censoufcert";
     $sql .= "      left  join censouf as censoufender on  censoufender.ed260_i_codigo = rechumano.ed20_i_censoufender";
     $sql .= "      left  join censomunic as censomunicnat on  censomunicnat.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censomunic as censomunicender on  censomunicender.ed261_i_codigo = rechumano.ed20_i_censomunicender";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql .= "      left  join censocartorio  on  censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  function sql_query_rechumano($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from rechumano ';
    $sSql .= '   inner join ((select cgm.*, rhpesdoc.rh16_ctps_n::varchar as ctps_num, ';
    $sSql .= '                      rhpesdoc.rh16_ctps_s::varchar as ctps_serie, rhpesdoc.rh16_ctps_uf as ctps_uf, ';
    $sSql .= '                       rhpesdoc.rh16_pis as pis, rechumanopessoal.ed284_i_rechumano as rechumano,';
    $sSql .= '                       1 as tipo ';
    $sSql .= '                  from rechumano as a';
    $sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
    $sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
    $sSql .= '                    left join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist ';
    $sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
    $sSql .= '                                      union ';
    $sSql .= '               (select cgm.*, cgmdoc.z02_c_ctpsnum::varchar, cgmdoc.z02_c_ctpsserie::varchar, ';
    $sSql .= '                       cgmdoc.z02_c_ctpsuf, cgmdoc.z02_i_pis::varchar,';
    $sSql .= '                       rechumanocgm.ed285_i_rechumano as rechumano, 2 as tipo ';
    $sSql .= '                 from rechumano as a';
    $sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
    $sSql .= '                   left  join cgmdoc on cgmdoc.z02_i_cgm = cgm.z01_numcgm ';
    $sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo)) as reccgm';
    $sSql .= '     on reccgm.rechumano = rechumano.ed20_i_codigo ';
    $sSql .= '   inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais';
    $sSql .= '   left join rhregime as regimecgm on regimecgm.rh30_codreg = rechumano.ed20_i_rhregime';
    $sSql .= '   left join censouf as censoufident on censoufident.ed260_i_codigo = rechumano.ed20_i_censoufident';
    $sSql .= '   left join censouf as censoufnat on censoufnat.ed260_i_codigo = rechumano.ed20_i_censoufnat';
    $sSql .= '   left join censouf as censoufcert on censoufcert.ed260_i_codigo = rechumano.ed20_i_censoufcert';
    $sSql .= '   left join censouf as censoufender on censoufender.ed260_i_codigo = rechumano.ed20_i_censoufender';
    $sSql .= '   left join censomunic as censomunicnat on censomunicnat.ed261_i_codigo = rechumano.ed20_i_censomunicnat';
    $sSql .= '   left join censomunic as censomunicender on censomunicender.ed261_i_codigo = rechumano.ed20_i_censomunicender';
    $sSql .= '   left join censoorgemissrg on censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss';
    $sSql .= '   left join censocartorio on censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumano.ed20_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  function sql_query_censomodel($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from rechumano ";
    $sSql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sSql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm ";
    $sSql .= "      inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo ";
    $sSql .= "      inner join escola on ed18_i_codigo = ed75_i_escola ";
    $sSql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumano.ed20_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
  
   function sql_query_atolegal ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $instit = db_getsession("DB_instit");
     $ano = db_anofolha();
     $mes = db_mesfolha();
     $sql .= " from rechumano ";
     //se rh vem da rhpessoal
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                                          and rhpessoalmov.rh02_mesusu  = $mes
                                          and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                          and rhpessoalmov.rh02_instit  = $instit";
     $sql .= "      left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      left join rhlota  on  rhlota.r70_codigo = rhpessoal.rh01_lotac";
     $sql .= "      left join rhpesdoc  on  rhpesdoc.rh16_regist = rhpessoal.rh01_regist";
     $sql .= "      left join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      left join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit";
     $sql .= "      left join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      left join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     //se rh vem direto do cgm
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      left join cgmdoc on  cgmdoc.z02_i_cgm = cgmcgm.z01_numcgm";
     $sql .= "      left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime";
    // $sql .= "      inner join rhregime on rhregime.rh30_codreg = rechumano.ed20_i_rhregime ";
     //demais ligações da rechumano
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      left join relacaotrabalho  on  relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join rechumanoativ  on  rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
     $sql .= "      left join atolegal on atolegal.ed05_i_codigo = rechumanoativ.ed22_i_atolegal";
     $sql .= "      left join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql .= "      left join disciplina  on  disciplina.ed12_i_codigo = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      left join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed20_i_codigo!=null ){
         $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  
  
  function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $instit = db_getsession("DB_instit");
    $ano    = db_anofolha();
    $mes    = db_mesfolha();
    $sSql  .= " from rechumano ";
    $sSql  .= "      inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo";
    $sSql  .= "      left join rechumanoativ on ed22_i_rechumanoescola = ed75_i_codigo";
    $sSql  .= "      left join atividaderh on ed01_i_codigo = ed22_i_atividade";
    $sSql  .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sSql  .= "      left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano";
    $sSql  .= "                              and rhpessoalmov.rh02_mesusu  = $mes";
    $sSql  .= "                              and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist";
    $sSql  .= "                              and rhpessoalmov.rh02_instit  = $instit";
    $sSql  .= "      left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg";
    $sSql  .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sSql  .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sSql  .= "      left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumano.ed20_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
/*
 *Query utilizada na rotina de solicitação de informações sem codigo inep.
 *@date 29/02/2012
 */

 function sql_query_solicitaseminep($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {
    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from rechumano ';
    $sSql .= '   inner join ((select cgm.*, rhpesdoc.rh16_ctps_n::varchar as ctps_num, ';
    $sSql .= '                      rhpesdoc.rh16_ctps_s::varchar as ctps_serie, rhpesdoc.rh16_ctps_uf as ctps_uf, ';
    $sSql .= '                       rhpesdoc.rh16_pis as pis, rechumanopessoal.ed284_i_rechumano as rechumano,';
    $sSql .= '                       1 as tipo ';
    $sSql .= '                  from rechumano as a';
    $sSql .= '                    inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                    inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal';
    $sSql .= '                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm';
    $sSql .= '                    left join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist ';
    $sSql .= '                      where rechumanopessoal.ed284_i_rechumano = a.ed20_i_codigo)';
    $sSql .= '                                      union ';
    $sSql .= '               (select cgm.*, cgmdoc.z02_c_ctpsnum::varchar, cgmdoc.z02_c_ctpsserie::varchar, ';
    $sSql .= '                       cgmdoc.z02_c_ctpsuf, cgmdoc.z02_i_pis::varchar,';
    $sSql .= '                       rechumanocgm.ed285_i_rechumano as rechumano, 2 as tipo ';
    $sSql .= '                 from rechumano as a';
    $sSql .= '                   inner join rechumanocgm on rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo ';
    $sSql .= '                   inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm';
    $sSql .= '                   left  join cgmdoc on cgmdoc.z02_i_cgm = cgm.z01_numcgm ';
    $sSql .= '                     where rechumanocgm.ed285_i_rechumano = a.ed20_i_codigo';
    $sSql .= '                          )) as reccgm';
    $sSql .= '                             on reccgm.rechumano = rechumano.ed20_i_codigo ';
    $sSql .= '   inner join rechumanoescola on ed20_i_codigo = ed75_i_rechumano';
    $sSql .= '   left join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais';
    $sSql .= '   left join rhregime as regimecgm on regimecgm.rh30_codreg = rechumano.ed20_i_rhregime';
    $sSql .= '   left join censouf as censoufident on censoufident.ed260_i_codigo = rechumano.ed20_i_censoufident';
    $sSql .= '   left join censouf as censoufnat on censoufnat.ed260_i_codigo = rechumano.ed20_i_censoufnat';
    $sSql .= '   left join censouf as censoufcert on censoufcert.ed260_i_codigo = rechumano.ed20_i_censoufcert';
    $sSql .= '   left join censouf as censoufender on censoufender.ed260_i_codigo = rechumano.ed20_i_censoufender';
    $sSql .= '   left join censomunic as censomunicnat on censomunicnat.ed261_i_codigo = rechumano.ed20_i_censomunicnat';
    $sSql .= '   left join censomunic as censomunicender on censomunicender.ed261_i_codigo = rechumano.ed20_i_censomunicender';
    $sSql .= '   inner join escola on escola.ed18_i_codigo =  rechumanoescola.ed75_i_escola';
    $sSql .= '   inner join calendarioescola on calendarioescola.ed38_i_escola = escola.ed18_i_codigo'; 
    $sSql .= '   inner join calendario on calendario.ed52_i_codigo = calendarioescola.ed38_i_calendario';
    $sSql .= '   left join censoorgemissrg on censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss';
    $sSql .= '   left join censocartorio on censocartorio.ed291_i_codigo = rechumano.ed20_i_censocartorio';
    $sSql .= '   inner join rechumanoativ   on ed22_i_rechumanoescola = ed75_i_codigo';
    $sSql .= '   inner join atividaderh     on ed22_i_atividade       = ed01_i_codigo';
    $sSql2 = '';

    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumano.ed20_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;
 }
 
  function sql_query_rechumano_cgm ($ed20_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {
  	
 	  $sql = "select ";
 		if ($campos != "*") {
 			 
	 		$campos_sql = split("#",$campos);
	 		$virgula    = "";
	 		for ($i = 0; $i < sizeof($campos_sql); $i++) {
	 			
	 			$sql .= $virgula.$campos_sql[$i];
	 			$virgula = ",";
	 		}
	 	} else {
 			$sql .= $campos;
 		}
 	  $sql .= "  from rechumano ";          
 	  $sql .= " inner join rechumanoescola on rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo "; 
 	  /**
 	   * Busca identificar o cgm do professor
 	   */
 	  $sql .= " left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
 	  $sql .= " left join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
 	  $sql .= " left join cgm as cgmrh     on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm              ";
 	  $sql .= " left join rechumanocgm     on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo            ";
 	  $sql .= " left join cgm as cgmcgm    on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm           ";
 	  $sql2 = "";                                                                                                       
 	  if ($dbwhere == "") {
 	  	
 	  	if ($ed20_i_codigo != null) {
 	  		$sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
 	  	}
 	  } else if($dbwhere != "") {
 	  	$sql2 = " where $dbwhere";
 	  }
 	  $sql .= $sql2;
 	  if($ordem != null) {
 	  	
 	  	$sql       .= " order by ";
 	  	$campos_sql = split("#",$ordem);
 	  	$virgula    = "";
 	  	for ($i = 0; $i < sizeof($campos_sql); $i++) {
 	  		
 	  		$sql    .= $virgula.$campos_sql[$i];
 	  		$virgula = ",";
 	  	}
 	  }
 	  return $sql;
  }

  function sql_query_consulta_rechumano($iCgm) {


    $sSql  = " select ed20_i_codigo as rechumano                                                                       ";
    $sSql .= "   from rechumano                                                                                        ";
    $sSql .= "  inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
    $sSql .= "  inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql .= "  inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm              ";
    $sSql .= "  where rhpessoal.rh01_numcgm = {$iCgm}                                                                  ";
    $sSql .= "                                                                                                         ";
    $sSql .= " union all                                                                                               ";
    $sSql .= "                                                                                                         ";
    $sSql .= " select ed20_i_codigo as rechumano                                                                       ";
    $sSql .= "   from rechumano                                                                                        ";
    $sSql .= "  inner join rechumanocgm  on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo               ";
    $sSql .= "  inner join cgm              on cgm.z01_numcgm                     = rechumanocgm.ed285_i_cgm           ";
    $sSql .= "  where rechumanocgm.ed285_i_cgm = {$iCgm}                                                               ";
    return $sSql;
  }

  
  function sql_query_movimentacao_professor_cgm ($iCgm) {
  	
  	$sSql  = " select ed321_sequencial as codigo, ed20_i_codigo as rechumano, trim(z01_nome) as z01_nome , 'A'::varchar as tipo   ";
  	$sSql .= "   from docenteausencia                                                                                  ";
  	$sSql .= "  inner join rechumano        on rechumano.ed20_i_codigo            = docenteausencia.ed321_rechumano    ";
  	$sSql .= "  inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
  	$sSql .= "  inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
  	$sSql .= "  inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm              ";
  	$sSql .= "  where rhpessoal.rh01_numcgm = {$iCgm}                                                             ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " union all                                                                                               ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " select ed321_sequencial as codigo, ed20_i_codigo as rechumano, trim(z01_nome) as z01_nome, 'A'::varchar as tipo    ";
  	$sSql .= "   from docenteausencia                                                                                  ";
  	$sSql .= "  inner join rechumano     on rechumano.ed20_i_codigo            = docenteausencia.ed321_rechumano       ";
  	$sSql .= "  inner join rechumanocgm  on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo               ";
  	$sSql .= "  inner join cgm              on cgm.z01_numcgm                     = rechumanocgm.ed285_i_cgm           ";
  	$sSql .= "  where rechumanocgm.ed285_i_cgm = {$iCgm}                                                          ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " union all                                                                                               ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " select ed322_sequencial as codigo, ed20_i_codigo as rechumano, trim(z01_nome) as z01_nome , 'S'::varchar as tipo   ";
  	$sSql .= "   from docentesubstituto                                                                                ";
  	$sSql .= "  inner join rechumano        on rechumano.ed20_i_codigo            = docentesubstituto.ed322_rechumano  ";
  	$sSql .= "  inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo            ";
  	$sSql .= "  inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal ";
  	$sSql .= "  inner join cgm              on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm              ";
  	$sSql .= "  where rhpessoal.rh01_numcgm = {$iCgm}                                                             ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " union all                                                                                               ";
  	$sSql .= "                                                                                                         ";
  	$sSql .= " select ed322_sequencial as codigo, ed20_i_codigo as rechumano, trim(z01_nome) as z01_nome, 'S'::varchar as tipo    ";
  	$sSql .= "   from docentesubstituto                                                                                  ";
  	$sSql .= "  inner join rechumano     on rechumano.ed20_i_codigo            = docentesubstituto.ed322_rechumano     ";
  	$sSql .= "  inner join rechumanocgm  on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo               ";
  	$sSql .= "  inner join cgm           on cgm.z01_numcgm                     = rechumanocgm.ed285_i_cgm              ";
  	$sSql .= "  where rechumanocgm.ed285_i_cgm = {$iCgm}                                                          ";
  	 
  	return $sSql;
  }

  function sql_query_censo_2015 ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from rechumano ";
    $sql .= "      left  join rechumanopessoal on rechumanopessoal.ed284_i_rechumano   = rechumano.ed20_i_codigo";
    $sql .= "      left  join rhpessoal        on rhpessoal.rh01_regist                = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      left  join cgm as cgmrh     on cgmrh.z01_numcgm                     = rhpessoal.rh01_numcgm";
    $sql .= "      left  join rechumanocgm     on rechumanocgm.ed285_i_rechumano       = rechumano.ed20_i_codigo";
    $sql .= "      left  join cgm as cgmcgm    on cgmcgm.z01_numcgm                    = rechumanocgm.ed285_i_cgm";
    $sql .= "      inner join rechumanoescola  on rechumanoescola.ed75_i_rechumano     = rechumano.ed20_i_codigo";
    $sql .= "      inner join escola           on escola.ed18_i_codigo                 = rechumanoescola.ed75_i_escola";
    $sql .= "      left  join rechumanoativ    on rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo";
    $sql .= "      left  join atividaderh      on atividaderh.ed01_i_codigo            = rechumanoativ.ed22_i_atividade";
    $sql .= "      left  join docenteausencia  on docenteausencia.ed321_rechumano      = rechumano.ed20_i_codigo";
    $sql .= "                                 and docenteausencia.ed321_escola         = rechumanoescola.ed75_i_escola";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed20_i_codigo != null ) {
        $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }

  function sql_query_servidor ( $ed20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";

    if( $campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from rechumano ";
    $sql .= "      inner join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= "      inner join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= "      inner join rhpessoalmov     on rhpessoalmov.rh02_regist           = rhpessoal.rh01_regist";
    $sql .= "      inner join rhfuncao         on rhfuncao.rh37_funcao               = rhpessoalmov.rh02_funcao";
    $sql .= "                                 and rhfuncao.rh37_instit               = rhpessoalmov.rh02_instit";
    $sql .= "      left  join rhpescargo       on rhpescargo.rh20_seqpes             = rhpessoalmov.rh02_seqpes";
    $sql .= "      left  join rhcargo          on rhcargo.rh04_codigo                = rhpescargo.rh20_cargo";
    $sql2 = "";

    if( $dbwhere == "" ) {

      if( $ed20_i_codigo != null ) {
        $sql2 .= " where rechumano.ed20_i_codigo = $ed20_i_codigo ";
      }
    } else if( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if( $ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}