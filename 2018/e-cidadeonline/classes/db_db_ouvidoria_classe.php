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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_ouvidoria
class cl_db_ouvidoria { 
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
   var $po01_sequencial = 0; 
   var $po01_nome = null; 
   var $po01_email = null; 
   var $po01_tipo = 0; 
   var $po01_datanascimento_dia = null; 
   var $po01_datanascimento_mes = null; 
   var $po01_datanascimento_ano = null; 
   var $po01_datanascimento = null; 
   var $po01_sexo = 'f'; 
   var $po01_profissao = null; 
   var $po01_escolaridade = 0; 
   var $po01_cpf = null; 
   var $po01_rg = null; 
   var $po01_telefone = null; 
   var $po01_celular = null; 
   var $po01_enderecoresidencial = null; 
   var $po01_enderecocomercial = null; 
   var $po01_cidade = null; 
   var $po01_db_uf = 0; 
   var $po01_sigilo = 'f'; 
   var $po01_resposta = 'f'; 
   var $po01_tiporesposta = 0; 
   var $po01_assunto = null; 
   var $po01_mensagem = null; 
   var $po01_url01 = null; 
   var $po01_url02 = null; 
   var $po01_ip = null; 
   var $po01_revisado_dia = null; 
   var $po01_revisado_mes = null; 
   var $po01_revisado_ano = null; 
   var $po01_revisado = null; 
   var $po01_data_dia = null; 
   var $po01_data_mes = null; 
   var $po01_data_ano = null; 
   var $po01_data = null; 
   var $po01_texto = null; 
   var $po01_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 po01_sequencial = int4 = Sequencial 
                 po01_nome = varchar(100) = Nome 
                 po01_email = varchar(255) = E-mail 
                 po01_tipo = int4 = Código 
                 po01_datanascimento = date = Data de Nascimento 
                 po01_sexo = bool = Sexo 
                 po01_profissao = varchar(50) = Profissão 
                 po01_escolaridade = int4 = Escolaridade 
                 po01_cpf = varchar(20) = CPF 
                 po01_rg = varchar(20) = RG 
                 po01_telefone = varchar(20) = Telefone fixo com DDD 
                 po01_celular = varchar(20) = Telefone celular com DDD 
                 po01_enderecoresidencial = varchar(255) = Endereço Residencial 
                 po01_enderecocomercial = varchar(255) = Endereço Comercial 
                 po01_cidade = varchar(100) = Cidade 
                 po01_db_uf = int4 = Estado 
                 po01_sigilo = bool = Preservar nome e dados em sigilo? 
                 po01_resposta = bool = Deseja receber resposta? 
                 po01_tiporesposta = int4 = Tipo da Resposta 
                 po01_assunto = varchar(100) = Assunto 
                 po01_mensagem = text = Mensagem 
                 po01_url01 = varchar(255) = URl 01 
                 po01_url02 = varchar(255) = URL 02 
                 po01_ip = varchar(50) = IP 
                 po01_revisado = date = Revisado 
                 po01_data = date = Data 
                 po01_texto = text = Texto 
                 po01_id_usuario = int4 = Usuario 
                 ";
   //funcao construtor da classe 
   function cl_db_ouvidoria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_ouvidoria"); 
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
       $this->po01_sequencial = ($this->po01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_sequencial"]:$this->po01_sequencial);
       $this->po01_nome = ($this->po01_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_nome"]:$this->po01_nome);
       $this->po01_email = ($this->po01_email == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_email"]:$this->po01_email);
       $this->po01_tipo = ($this->po01_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_tipo"]:$this->po01_tipo);
       if($this->po01_datanascimento == ""){
         $this->po01_datanascimento_dia = ($this->po01_datanascimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_dia"]:$this->po01_datanascimento_dia);
         $this->po01_datanascimento_mes = ($this->po01_datanascimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_mes"]:$this->po01_datanascimento_mes);
         $this->po01_datanascimento_ano = ($this->po01_datanascimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_ano"]:$this->po01_datanascimento_ano);
         if($this->po01_datanascimento_dia != ""){
            $this->po01_datanascimento = $this->po01_datanascimento_ano."-".$this->po01_datanascimento_mes."-".$this->po01_datanascimento_dia;
         }
       }
       $this->po01_sexo = ($this->po01_sexo == "f"?@$GLOBALS["HTTP_POST_VARS"]["po01_sexo"]:$this->po01_sexo);
       $this->po01_profissao = ($this->po01_profissao == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_profissao"]:$this->po01_profissao);
       $this->po01_escolaridade = ($this->po01_escolaridade == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_escolaridade"]:$this->po01_escolaridade);
       $this->po01_cpf = ($this->po01_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_cpf"]:$this->po01_cpf);
       $this->po01_rg = ($this->po01_rg == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_rg"]:$this->po01_rg);
       $this->po01_telefone = ($this->po01_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_telefone"]:$this->po01_telefone);
       $this->po01_celular = ($this->po01_celular == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_celular"]:$this->po01_celular);
       $this->po01_enderecoresidencial = ($this->po01_enderecoresidencial == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_enderecoresidencial"]:$this->po01_enderecoresidencial);
       $this->po01_enderecocomercial = ($this->po01_enderecocomercial == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_enderecocomercial"]:$this->po01_enderecocomercial);
       $this->po01_cidade = ($this->po01_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_cidade"]:$this->po01_cidade);
       $this->po01_db_uf = ($this->po01_db_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_db_uf"]:$this->po01_db_uf);
       $this->po01_sigilo = ($this->po01_sigilo == "f"?@$GLOBALS["HTTP_POST_VARS"]["po01_sigilo"]:$this->po01_sigilo);
       $this->po01_resposta = ($this->po01_resposta == "f"?@$GLOBALS["HTTP_POST_VARS"]["po01_resposta"]:$this->po01_resposta);
       $this->po01_tiporesposta = ($this->po01_tiporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_tiporesposta"]:$this->po01_tiporesposta);
       $this->po01_assunto = ($this->po01_assunto == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_assunto"]:$this->po01_assunto);
       $this->po01_mensagem = ($this->po01_mensagem == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_mensagem"]:$this->po01_mensagem);
       $this->po01_url01 = ($this->po01_url01 == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_url01"]:$this->po01_url01);
       $this->po01_url02 = ($this->po01_url02 == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_url02"]:$this->po01_url02);
       $this->po01_ip = ($this->po01_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_ip"]:$this->po01_ip);
       if($this->po01_revisado == ""){
         $this->po01_revisado_dia = ($this->po01_revisado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_revisado_dia"]:$this->po01_revisado_dia);
         $this->po01_revisado_mes = ($this->po01_revisado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_revisado_mes"]:$this->po01_revisado_mes);
         $this->po01_revisado_ano = ($this->po01_revisado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_revisado_ano"]:$this->po01_revisado_ano);
         if($this->po01_revisado_dia != ""){
            $this->po01_revisado = $this->po01_revisado_ano."-".$this->po01_revisado_mes."-".$this->po01_revisado_dia;
         }
       }
       if($this->po01_data == ""){
         $this->po01_data_dia = ($this->po01_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_data_dia"]:$this->po01_data_dia);
         $this->po01_data_mes = ($this->po01_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_data_mes"]:$this->po01_data_mes);
         $this->po01_data_ano = ($this->po01_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_data_ano"]:$this->po01_data_ano);
         if($this->po01_data_dia != ""){
            $this->po01_data = $this->po01_data_ano."-".$this->po01_data_mes."-".$this->po01_data_dia;
         }
       }
       $this->po01_texto = ($this->po01_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_texto"]:$this->po01_texto);
       $this->po01_id_usuario = ($this->po01_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_id_usuario"]:$this->po01_id_usuario);
     }else{
       $this->po01_sequencial = ($this->po01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["po01_sequencial"]:$this->po01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($po01_sequencial){ 
      $this->atualizacampos();
     if($this->po01_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "po01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_email == null ){ 
       $this->erro_sql = " Campo E-mail nao Informado.";
       $this->erro_campo = "po01_email";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_tipo == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "po01_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_datanascimento == null ){ 
       $this->po01_datanascimento = "null";
     }
     if($this->po01_sexo == null ){ 
       $this->po01_sexo = "f";
     }
     if($this->po01_escolaridade == null ){ 
       $this->po01_escolaridade = "0";
     }
     if($this->po01_db_uf == null ){ 
       $this->erro_sql = " Campo Estado nao Informado.";
       $this->erro_campo = "po01_db_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_sigilo == null ){ 
       $this->erro_sql = " Campo Preservar nome e dados em sigilo? nao Informado.";
       $this->erro_campo = "po01_sigilo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_resposta == null ){ 
       $this->erro_sql = " Campo Deseja receber resposta? nao Informado.";
       $this->erro_campo = "po01_resposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_tiporesposta == null ){ 
       $this->erro_sql = " Campo Tipo da Resposta nao Informado.";
       $this->erro_campo = "po01_tiporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_assunto == null ){ 
       $this->erro_sql = " Campo Assunto nao Informado.";
       $this->erro_campo = "po01_assunto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_mensagem == null ){ 
       $this->erro_sql = " Campo Mensagem nao Informado.";
       $this->erro_campo = "po01_mensagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "po01_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->po01_revisado == null ){ 
       $this->po01_revisado = "null";
     }
     if($this->po01_data == null ){ 
       $this->po01_data = "null";
     }
     if($this->po01_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "po01_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($po01_sequencial == "" || $po01_sequencial == null ){
       $result = db_query("select nextval('db_ouvidoria_po01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_ouvidoria_po01_sequencial_seq do campo: po01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->po01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_ouvidoria_po01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $po01_sequencial)){
         $this->erro_sql = " Campo po01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->po01_sequencial = $po01_sequencial; 
       }
     }
     if(($this->po01_sequencial == null) || ($this->po01_sequencial == "") ){ 
       $this->erro_sql = " Campo po01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_ouvidoria(
                                       po01_sequencial 
                                      ,po01_nome 
                                      ,po01_email 
                                      ,po01_tipo 
                                      ,po01_datanascimento 
                                      ,po01_sexo 
                                      ,po01_profissao 
                                      ,po01_escolaridade 
                                      ,po01_cpf 
                                      ,po01_rg 
                                      ,po01_telefone 
                                      ,po01_celular 
                                      ,po01_enderecoresidencial 
                                      ,po01_enderecocomercial 
                                      ,po01_cidade 
                                      ,po01_db_uf 
                                      ,po01_sigilo 
                                      ,po01_resposta 
                                      ,po01_tiporesposta 
                                      ,po01_assunto 
                                      ,po01_mensagem 
                                      ,po01_url01 
                                      ,po01_url02 
                                      ,po01_ip 
                                      ,po01_revisado 
                                      ,po01_data 
                                      ,po01_texto 
                                      ,po01_id_usuario 
                       )
                values (
                                $this->po01_sequencial 
                               ,'$this->po01_nome' 
                               ,'$this->po01_email' 
                               ,$this->po01_tipo 
                               ,".($this->po01_datanascimento == "null" || $this->po01_datanascimento == ""?"null":"'".$this->po01_datanascimento."'")." 
                               ,'$this->po01_sexo' 
                               ,'$this->po01_profissao' 
                               ,$this->po01_escolaridade 
                               ,'$this->po01_cpf' 
                               ,'$this->po01_rg' 
                               ,'$this->po01_telefone' 
                               ,'$this->po01_celular' 
                               ,'$this->po01_enderecoresidencial' 
                               ,'$this->po01_enderecocomercial' 
                               ,'$this->po01_cidade' 
                               ,$this->po01_db_uf 
                               ,'$this->po01_sigilo' 
                               ,'$this->po01_resposta' 
                               ,$this->po01_tiporesposta 
                               ,'$this->po01_assunto' 
                               ,'$this->po01_mensagem' 
                               ,'$this->po01_url01' 
                               ,'$this->po01_url02' 
                               ,'$this->po01_ip' 
                               ,".($this->po01_revisado == "null" || $this->po01_revisado == ""?"null":"'".$this->po01_revisado."'")." 
                               ,".($this->po01_data == "null" || $this->po01_data == ""?"null":"'".$this->po01_data."'")." 
                               ,'$this->po01_texto' 
                               ,$this->po01_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ouvidoria ($this->po01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ouvidoria ($this->po01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->po01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->po01_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19857,'$this->po01_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,188,19857,'','".AddSlashes(pg_result($resaco,0,'po01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19826,'','".AddSlashes(pg_result($resaco,0,'po01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19858,'','".AddSlashes(pg_result($resaco,0,'po01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19859,'','".AddSlashes(pg_result($resaco,0,'po01_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19827,'','".AddSlashes(pg_result($resaco,0,'po01_datanascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19828,'','".AddSlashes(pg_result($resaco,0,'po01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19829,'','".AddSlashes(pg_result($resaco,0,'po01_profissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19830,'','".AddSlashes(pg_result($resaco,0,'po01_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19831,'','".AddSlashes(pg_result($resaco,0,'po01_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19832,'','".AddSlashes(pg_result($resaco,0,'po01_rg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19833,'','".AddSlashes(pg_result($resaco,0,'po01_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19834,'','".AddSlashes(pg_result($resaco,0,'po01_celular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19835,'','".AddSlashes(pg_result($resaco,0,'po01_enderecoresidencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19836,'','".AddSlashes(pg_result($resaco,0,'po01_enderecocomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19837,'','".AddSlashes(pg_result($resaco,0,'po01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19838,'','".AddSlashes(pg_result($resaco,0,'po01_db_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19839,'','".AddSlashes(pg_result($resaco,0,'po01_sigilo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19840,'','".AddSlashes(pg_result($resaco,0,'po01_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19841,'','".AddSlashes(pg_result($resaco,0,'po01_tiporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19842,'','".AddSlashes(pg_result($resaco,0,'po01_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19860,'','".AddSlashes(pg_result($resaco,0,'po01_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19843,'','".AddSlashes(pg_result($resaco,0,'po01_url01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19844,'','".AddSlashes(pg_result($resaco,0,'po01_url02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19845,'','".AddSlashes(pg_result($resaco,0,'po01_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19862,'','".AddSlashes(pg_result($resaco,0,'po01_revisado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19861,'','".AddSlashes(pg_result($resaco,0,'po01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19863,'','".AddSlashes(pg_result($resaco,0,'po01_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,188,19864,'','".AddSlashes(pg_result($resaco,0,'po01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($po01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_ouvidoria set ";
     $virgula = "";
     if(trim($this->po01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_sequencial"])){ 
       $sql  .= $virgula." po01_sequencial = $this->po01_sequencial ";
       $virgula = ",";
       if(trim($this->po01_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "po01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_nome"])){ 
       $sql  .= $virgula." po01_nome = '$this->po01_nome' ";
       $virgula = ",";
       if(trim($this->po01_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "po01_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_email"])){ 
       $sql  .= $virgula." po01_email = '$this->po01_email' ";
       $virgula = ",";
       if(trim($this->po01_email) == null ){ 
         $this->erro_sql = " Campo E-mail nao Informado.";
         $this->erro_campo = "po01_email";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_tipo"])){ 
       $sql  .= $virgula." po01_tipo = $this->po01_tipo ";
       $virgula = ",";
       if(trim($this->po01_tipo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "po01_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_datanascimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_dia"] !="") ){ 
       $sql  .= $virgula." po01_datanascimento = '$this->po01_datanascimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["po01_datanascimento_dia"])){ 
         $sql  .= $virgula." po01_datanascimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->po01_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_sexo"])){ 
       $sql  .= $virgula." po01_sexo = '$this->po01_sexo' ";
       $virgula = ",";
     }
     if(trim($this->po01_profissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_profissao"])){ 
       $sql  .= $virgula." po01_profissao = '$this->po01_profissao' ";
       $virgula = ",";
     }
     if(trim($this->po01_escolaridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_escolaridade"])){ 
        if(trim($this->po01_escolaridade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["po01_escolaridade"])){ 
           $this->po01_escolaridade = "0" ; 
        } 
       $sql  .= $virgula." po01_escolaridade = $this->po01_escolaridade ";
       $virgula = ",";
     }
     if(trim($this->po01_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_cpf"])){ 
       $sql  .= $virgula." po01_cpf = '$this->po01_cpf' ";
       $virgula = ",";
     }
     if(trim($this->po01_rg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_rg"])){ 
       $sql  .= $virgula." po01_rg = '$this->po01_rg' ";
       $virgula = ",";
     }
     if(trim($this->po01_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_telefone"])){ 
       $sql  .= $virgula." po01_telefone = '$this->po01_telefone' ";
       $virgula = ",";
     }
     if(trim($this->po01_celular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_celular"])){ 
       $sql  .= $virgula." po01_celular = '$this->po01_celular' ";
       $virgula = ",";
     }
     if(trim($this->po01_enderecoresidencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_enderecoresidencial"])){ 
       $sql  .= $virgula." po01_enderecoresidencial = '$this->po01_enderecoresidencial' ";
       $virgula = ",";
     }
     if(trim($this->po01_enderecocomercial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_enderecocomercial"])){ 
       $sql  .= $virgula." po01_enderecocomercial = '$this->po01_enderecocomercial' ";
       $virgula = ",";
     }
     if(trim($this->po01_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_cidade"])){ 
       $sql  .= $virgula." po01_cidade = '$this->po01_cidade' ";
       $virgula = ",";
     }
     if(trim($this->po01_db_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_db_uf"])){ 
       $sql  .= $virgula." po01_db_uf = $this->po01_db_uf ";
       $virgula = ",";
       if(trim($this->po01_db_uf) == null ){ 
         $this->erro_sql = " Campo Estado nao Informado.";
         $this->erro_campo = "po01_db_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_sigilo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_sigilo"])){ 
       $sql  .= $virgula." po01_sigilo = '$this->po01_sigilo' ";
       $virgula = ",";
       if(trim($this->po01_sigilo) == null ){ 
         $this->erro_sql = " Campo Preservar nome e dados em sigilo? nao Informado.";
         $this->erro_campo = "po01_sigilo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_resposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_resposta"])){ 
       $sql  .= $virgula." po01_resposta = '$this->po01_resposta' ";
       $virgula = ",";
       if(trim($this->po01_resposta) == null ){ 
         $this->erro_sql = " Campo Deseja receber resposta? nao Informado.";
         $this->erro_campo = "po01_resposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_tiporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_tiporesposta"])){ 
       $sql  .= $virgula." po01_tiporesposta = $this->po01_tiporesposta ";
       $virgula = ",";
       if(trim($this->po01_tiporesposta) == null ){ 
         $this->erro_sql = " Campo Tipo da Resposta nao Informado.";
         $this->erro_campo = "po01_tiporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_assunto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_assunto"])){ 
       $sql  .= $virgula." po01_assunto = '$this->po01_assunto' ";
       $virgula = ",";
       if(trim($this->po01_assunto) == null ){ 
         $this->erro_sql = " Campo Assunto nao Informado.";
         $this->erro_campo = "po01_assunto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_mensagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_mensagem"])){ 
       $sql  .= $virgula." po01_mensagem = '$this->po01_mensagem' ";
       $virgula = ",";
       if(trim($this->po01_mensagem) == null ){ 
         $this->erro_sql = " Campo Mensagem nao Informado.";
         $this->erro_campo = "po01_mensagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_url01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_url01"])){ 
       $sql  .= $virgula." po01_url01 = '$this->po01_url01' ";
       $virgula = ",";
     }
     if(trim($this->po01_url02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_url02"])){ 
       $sql  .= $virgula." po01_url02 = '$this->po01_url02' ";
       $virgula = ",";
     }
     if(trim($this->po01_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_ip"])){ 
       $sql  .= $virgula." po01_ip = '$this->po01_ip' ";
       $virgula = ",";
       if(trim($this->po01_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "po01_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->po01_revisado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_revisado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["po01_revisado_dia"] !="") ){ 
       $sql  .= $virgula." po01_revisado = '$this->po01_revisado' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["po01_revisado_dia"])){ 
         $sql  .= $virgula." po01_revisado = null ";
         $virgula = ",";
       }
     }
     if(trim($this->po01_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["po01_data_dia"] !="") ){ 
       $sql  .= $virgula." po01_data = '$this->po01_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["po01_data_dia"])){ 
         $sql  .= $virgula." po01_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->po01_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_texto"])){ 
       $sql  .= $virgula." po01_texto = '$this->po01_texto' ";
       $virgula = ",";
     }
     if(trim($this->po01_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["po01_id_usuario"])){ 
       $sql  .= $virgula." po01_id_usuario = $this->po01_id_usuario ";
       $virgula = ",";
       if(trim($this->po01_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "po01_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($po01_sequencial!=null){
       $sql .= " po01_sequencial = $this->po01_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->po01_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19857,'$this->po01_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_sequencial"]) || $this->po01_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,188,19857,'".AddSlashes(pg_result($resaco,$conresaco,'po01_sequencial'))."','$this->po01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_nome"]) || $this->po01_nome != "")
             $resac = db_query("insert into db_acount values($acount,188,19826,'".AddSlashes(pg_result($resaco,$conresaco,'po01_nome'))."','$this->po01_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_email"]) || $this->po01_email != "")
             $resac = db_query("insert into db_acount values($acount,188,19858,'".AddSlashes(pg_result($resaco,$conresaco,'po01_email'))."','$this->po01_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_tipo"]) || $this->po01_tipo != "")
             $resac = db_query("insert into db_acount values($acount,188,19859,'".AddSlashes(pg_result($resaco,$conresaco,'po01_tipo'))."','$this->po01_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_datanascimento"]) || $this->po01_datanascimento != "")
             $resac = db_query("insert into db_acount values($acount,188,19827,'".AddSlashes(pg_result($resaco,$conresaco,'po01_datanascimento'))."','$this->po01_datanascimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_sexo"]) || $this->po01_sexo != "")
             $resac = db_query("insert into db_acount values($acount,188,19828,'".AddSlashes(pg_result($resaco,$conresaco,'po01_sexo'))."','$this->po01_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_profissao"]) || $this->po01_profissao != "")
             $resac = db_query("insert into db_acount values($acount,188,19829,'".AddSlashes(pg_result($resaco,$conresaco,'po01_profissao'))."','$this->po01_profissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_escolaridade"]) || $this->po01_escolaridade != "")
             $resac = db_query("insert into db_acount values($acount,188,19830,'".AddSlashes(pg_result($resaco,$conresaco,'po01_escolaridade'))."','$this->po01_escolaridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_cpf"]) || $this->po01_cpf != "")
             $resac = db_query("insert into db_acount values($acount,188,19831,'".AddSlashes(pg_result($resaco,$conresaco,'po01_cpf'))."','$this->po01_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_rg"]) || $this->po01_rg != "")
             $resac = db_query("insert into db_acount values($acount,188,19832,'".AddSlashes(pg_result($resaco,$conresaco,'po01_rg'))."','$this->po01_rg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_telefone"]) || $this->po01_telefone != "")
             $resac = db_query("insert into db_acount values($acount,188,19833,'".AddSlashes(pg_result($resaco,$conresaco,'po01_telefone'))."','$this->po01_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_celular"]) || $this->po01_celular != "")
             $resac = db_query("insert into db_acount values($acount,188,19834,'".AddSlashes(pg_result($resaco,$conresaco,'po01_celular'))."','$this->po01_celular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_enderecoresidencial"]) || $this->po01_enderecoresidencial != "")
             $resac = db_query("insert into db_acount values($acount,188,19835,'".AddSlashes(pg_result($resaco,$conresaco,'po01_enderecoresidencial'))."','$this->po01_enderecoresidencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_enderecocomercial"]) || $this->po01_enderecocomercial != "")
             $resac = db_query("insert into db_acount values($acount,188,19836,'".AddSlashes(pg_result($resaco,$conresaco,'po01_enderecocomercial'))."','$this->po01_enderecocomercial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_cidade"]) || $this->po01_cidade != "")
             $resac = db_query("insert into db_acount values($acount,188,19837,'".AddSlashes(pg_result($resaco,$conresaco,'po01_cidade'))."','$this->po01_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_db_uf"]) || $this->po01_db_uf != "")
             $resac = db_query("insert into db_acount values($acount,188,19838,'".AddSlashes(pg_result($resaco,$conresaco,'po01_db_uf'))."','$this->po01_db_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_sigilo"]) || $this->po01_sigilo != "")
             $resac = db_query("insert into db_acount values($acount,188,19839,'".AddSlashes(pg_result($resaco,$conresaco,'po01_sigilo'))."','$this->po01_sigilo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_resposta"]) || $this->po01_resposta != "")
             $resac = db_query("insert into db_acount values($acount,188,19840,'".AddSlashes(pg_result($resaco,$conresaco,'po01_resposta'))."','$this->po01_resposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_tiporesposta"]) || $this->po01_tiporesposta != "")
             $resac = db_query("insert into db_acount values($acount,188,19841,'".AddSlashes(pg_result($resaco,$conresaco,'po01_tiporesposta'))."','$this->po01_tiporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_assunto"]) || $this->po01_assunto != "")
             $resac = db_query("insert into db_acount values($acount,188,19842,'".AddSlashes(pg_result($resaco,$conresaco,'po01_assunto'))."','$this->po01_assunto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_mensagem"]) || $this->po01_mensagem != "")
             $resac = db_query("insert into db_acount values($acount,188,19860,'".AddSlashes(pg_result($resaco,$conresaco,'po01_mensagem'))."','$this->po01_mensagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_url01"]) || $this->po01_url01 != "")
             $resac = db_query("insert into db_acount values($acount,188,19843,'".AddSlashes(pg_result($resaco,$conresaco,'po01_url01'))."','$this->po01_url01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_url02"]) || $this->po01_url02 != "")
             $resac = db_query("insert into db_acount values($acount,188,19844,'".AddSlashes(pg_result($resaco,$conresaco,'po01_url02'))."','$this->po01_url02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_ip"]) || $this->po01_ip != "")
             $resac = db_query("insert into db_acount values($acount,188,19845,'".AddSlashes(pg_result($resaco,$conresaco,'po01_ip'))."','$this->po01_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_revisado"]) || $this->po01_revisado != "")
             $resac = db_query("insert into db_acount values($acount,188,19862,'".AddSlashes(pg_result($resaco,$conresaco,'po01_revisado'))."','$this->po01_revisado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_data"]) || $this->po01_data != "")
             $resac = db_query("insert into db_acount values($acount,188,19861,'".AddSlashes(pg_result($resaco,$conresaco,'po01_data'))."','$this->po01_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_texto"]) || $this->po01_texto != "")
             $resac = db_query("insert into db_acount values($acount,188,19863,'".AddSlashes(pg_result($resaco,$conresaco,'po01_texto'))."','$this->po01_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["po01_id_usuario"]) || $this->po01_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,188,19864,'".AddSlashes(pg_result($resaco,$conresaco,'po01_id_usuario'))."','$this->po01_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->po01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->po01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->po01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($po01_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($po01_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19857,'$po01_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,188,19857,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19826,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19858,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19859,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19827,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_datanascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19828,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19829,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_profissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19830,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_escolaridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19831,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19832,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_rg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19833,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19834,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_celular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19835,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_enderecoresidencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19836,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_enderecocomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19837,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19838,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_db_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19839,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_sigilo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19840,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19841,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_tiporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19842,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19860,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19843,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_url01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19844,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_url02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19845,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19862,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_revisado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19861,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19863,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,188,19864,'','".AddSlashes(pg_result($resaco,$iresaco,'po01_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_ouvidoria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($po01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " po01_sequencial = $po01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$po01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$po01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$po01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_ouvidoria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $po01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ouvidoria ";
     $sql .= "      left join db_usuarios  on  db_usuarios.id_usuario = db_ouvidoria.po01_id_usuario";
     $sql .= "      inner join db_tipo  on  db_tipo.w03_codtipo = db_ouvidoria.po01_tipo";
     $sql .= "      inner join db_uf  on  db_uf.db12_codigo = db_ouvidoria.po01_db_uf";
     $sql2 = "";
     if($dbwhere==""){
       if($po01_sequencial!=null ){
         $sql2 .= " where db_ouvidoria.po01_sequencial = $po01_sequencial "; 
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
   function sql_query_file ( $po01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ouvidoria ";
     $sql2 = "";
     if($dbwhere==""){
       if($po01_sequencial!=null ){
         $sql2 .= " where db_ouvidoria.po01_sequencial = $po01_sequencial "; 
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
}
?>