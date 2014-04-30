<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_remessawebservice
class cl_db_remessawebservice { 
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
   var $db127_sequencial = 0; 
   var $db127_sistemaexterno = 0; 
   var $db127_usuario = 0; 
   var $db127_descricao = null; 
   var $db127_datacriacao_dia = null; 
   var $db127_datacriacao_mes = null; 
   var $db127_datacriacao_ano = null; 
   var $db127_datacriacao = null; 
   var $db127_dataprocessamento_dia = null; 
   var $db127_dataprocessamento_mes = null; 
   var $db127_dataprocessamento_ano = null; 
   var $db127_dataprocessamento = null; 
   var $db127_processada = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db127_sequencial = int4 = Sequencial 
                 db127_sistemaexterno = int4 = Sistema Externo 
                 db127_usuario = int4 = Usuario 
                 db127_descricao = varchar(200) = Descricao da Remessa 
                 db127_datacriacao = date = Data criacao 
                 db127_dataprocessamento = date = Data Processamento 
                 db127_processada = bool = Processada 
                 ";
   //funcao construtor da classe 
   function cl_db_remessawebservice() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_remessawebservice"); 
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
       $this->db127_sequencial = ($this->db127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_sequencial"]:$this->db127_sequencial);
       $this->db127_sistemaexterno = ($this->db127_sistemaexterno == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_sistemaexterno"]:$this->db127_sistemaexterno);
       $this->db127_usuario = ($this->db127_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_usuario"]:$this->db127_usuario);
       $this->db127_descricao = ($this->db127_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_descricao"]:$this->db127_descricao);
       if($this->db127_datacriacao == ""){
         $this->db127_datacriacao_dia = ($this->db127_datacriacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_dia"]:$this->db127_datacriacao_dia);
         $this->db127_datacriacao_mes = ($this->db127_datacriacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_mes"]:$this->db127_datacriacao_mes);
         $this->db127_datacriacao_ano = ($this->db127_datacriacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_ano"]:$this->db127_datacriacao_ano);
         if($this->db127_datacriacao_dia != ""){
            $this->db127_datacriacao = $this->db127_datacriacao_ano."-".$this->db127_datacriacao_mes."-".$this->db127_datacriacao_dia;
         }
       }
       if($this->db127_dataprocessamento == ""){
         $this->db127_dataprocessamento_dia = ($this->db127_dataprocessamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_dia"]:$this->db127_dataprocessamento_dia);
         $this->db127_dataprocessamento_mes = ($this->db127_dataprocessamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_mes"]:$this->db127_dataprocessamento_mes);
         $this->db127_dataprocessamento_ano = ($this->db127_dataprocessamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_ano"]:$this->db127_dataprocessamento_ano);
         if($this->db127_dataprocessamento_dia != ""){
            $this->db127_dataprocessamento = $this->db127_dataprocessamento_ano."-".$this->db127_dataprocessamento_mes."-".$this->db127_dataprocessamento_dia;
         }
       }
       $this->db127_processada = ($this->db127_processada == "f"?@$GLOBALS["HTTP_POST_VARS"]["db127_processada"]:$this->db127_processada);
     }else{
       $this->db127_sequencial = ($this->db127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db127_sequencial"]:$this->db127_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db127_sequencial){ 
      $this->atualizacampos();
     if($this->db127_sistemaexterno == null ){ 
       $this->erro_sql = " Campo Sistema Externo nao Informado.";
       $this->erro_campo = "db127_sistemaexterno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db127_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "db127_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db127_descricao == null ){ 
       $this->erro_sql = " Campo Descricao da Remessa nao Informado.";
       $this->erro_campo = "db127_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db127_datacriacao == null ){ 
       $this->erro_sql = " Campo Data criacao nao Informado.";
       $this->erro_campo = "db127_datacriacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db127_dataprocessamento == null ){ 
       $this->db127_dataprocessamento = "null";
     }
     if($this->db127_processada == null ){ 
       $this->erro_sql = " Campo Processada nao Informado.";
       $this->erro_campo = "db127_processada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db127_sequencial == "" || $db127_sequencial == null ){
       $result = db_query("select nextval('db_remessawebservice_db127_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_remessawebservice_db127_sequencial_seq do campo: db127_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db127_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_remessawebservice_db127_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db127_sequencial)){
         $this->erro_sql = " Campo db127_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db127_sequencial = $db127_sequencial; 
       }
     }
     if(($this->db127_sequencial == null) || ($this->db127_sequencial == "") ){ 
       $this->erro_sql = " Campo db127_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_remessawebservice(
                                       db127_sequencial 
                                      ,db127_sistemaexterno 
                                      ,db127_usuario 
                                      ,db127_descricao 
                                      ,db127_datacriacao 
                                      ,db127_dataprocessamento 
                                      ,db127_processada 
                       )
                values (
                                $this->db127_sequencial 
                               ,$this->db127_sistemaexterno 
                               ,$this->db127_usuario 
                               ,'$this->db127_descricao' 
                               ,".($this->db127_datacriacao == "null" || $this->db127_datacriacao == ""?"null":"'".$this->db127_datacriacao."'")." 
                               ,".($this->db127_dataprocessamento == "null" || $this->db127_dataprocessamento == ""?"null":"'".$this->db127_dataprocessamento."'")." 
                               ,'$this->db127_processada' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Remessa de Dados ($this->db127_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Remessa de Dados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Remessa de Dados ($this->db127_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db127_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db127_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19030,'$this->db127_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3389,19030,'','".AddSlashes(pg_result($resaco,0,'db127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19053,'','".AddSlashes(pg_result($resaco,0,'db127_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19057,'','".AddSlashes(pg_result($resaco,0,'db127_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19052,'','".AddSlashes(pg_result($resaco,0,'db127_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19055,'','".AddSlashes(pg_result($resaco,0,'db127_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19056,'','".AddSlashes(pg_result($resaco,0,'db127_dataprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3389,19054,'','".AddSlashes(pg_result($resaco,0,'db127_processada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db127_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_remessawebservice set ";
     $virgula = "";
     if(trim($this->db127_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_sequencial"])){ 
       $sql  .= $virgula." db127_sequencial = $this->db127_sequencial ";
       $virgula = ",";
       if(trim($this->db127_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db127_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db127_sistemaexterno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_sistemaexterno"])){ 
       $sql  .= $virgula." db127_sistemaexterno = $this->db127_sistemaexterno ";
       $virgula = ",";
       if(trim($this->db127_sistemaexterno) == null ){ 
         $this->erro_sql = " Campo Sistema Externo nao Informado.";
         $this->erro_campo = "db127_sistemaexterno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db127_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_usuario"])){ 
       $sql  .= $virgula." db127_usuario = $this->db127_usuario ";
       $virgula = ",";
       if(trim($this->db127_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "db127_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db127_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_descricao"])){ 
       $sql  .= $virgula." db127_descricao = '$this->db127_descricao' ";
       $virgula = ",";
       if(trim($this->db127_descricao) == null ){ 
         $this->erro_sql = " Campo Descricao da Remessa nao Informado.";
         $this->erro_campo = "db127_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db127_datacriacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_dia"] !="") ){ 
       $sql  .= $virgula." db127_datacriacao = '$this->db127_datacriacao' ";
       $virgula = ",";
       if(trim($this->db127_datacriacao) == null ){ 
         $this->erro_sql = " Campo Data criacao nao Informado.";
         $this->erro_campo = "db127_datacriacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db127_datacriacao_dia"])){ 
         $sql  .= $virgula." db127_datacriacao = null ";
         $virgula = ",";
         if(trim($this->db127_datacriacao) == null ){ 
           $this->erro_sql = " Campo Data criacao nao Informado.";
           $this->erro_campo = "db127_datacriacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db127_dataprocessamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_dia"] !="") ){ 
       $sql  .= $virgula." db127_dataprocessamento = '$this->db127_dataprocessamento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento_dia"])){ 
         $sql  .= $virgula." db127_dataprocessamento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->db127_processada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db127_processada"])){ 
       $sql  .= $virgula." db127_processada = '$this->db127_processada' ";
       $virgula = ",";
       if(trim($this->db127_processada) == null ){ 
         $this->erro_sql = " Campo Processada nao Informado.";
         $this->erro_campo = "db127_processada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db127_sequencial!=null){
       $sql .= " db127_sequencial = $this->db127_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db127_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19030,'$this->db127_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_sequencial"]) || $this->db127_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3389,19030,'".AddSlashes(pg_result($resaco,$conresaco,'db127_sequencial'))."','$this->db127_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_sistemaexterno"]) || $this->db127_sistemaexterno != "")
           $resac = db_query("insert into db_acount values($acount,3389,19053,'".AddSlashes(pg_result($resaco,$conresaco,'db127_sistemaexterno'))."','$this->db127_sistemaexterno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_usuario"]) || $this->db127_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3389,19057,'".AddSlashes(pg_result($resaco,$conresaco,'db127_usuario'))."','$this->db127_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_descricao"]) || $this->db127_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3389,19052,'".AddSlashes(pg_result($resaco,$conresaco,'db127_descricao'))."','$this->db127_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_datacriacao"]) || $this->db127_datacriacao != "")
           $resac = db_query("insert into db_acount values($acount,3389,19055,'".AddSlashes(pg_result($resaco,$conresaco,'db127_datacriacao'))."','$this->db127_datacriacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_dataprocessamento"]) || $this->db127_dataprocessamento != "")
           $resac = db_query("insert into db_acount values($acount,3389,19056,'".AddSlashes(pg_result($resaco,$conresaco,'db127_dataprocessamento'))."','$this->db127_dataprocessamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db127_processada"]) || $this->db127_processada != "")
           $resac = db_query("insert into db_acount values($acount,3389,19054,'".AddSlashes(pg_result($resaco,$conresaco,'db127_processada'))."','$this->db127_processada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remessa de Dados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remessa de Dados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db127_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db127_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19030,'$db127_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3389,19030,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19053,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_sistemaexterno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19057,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19052,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19055,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19056,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_dataprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3389,19054,'','".AddSlashes(pg_result($resaco,$iresaco,'db127_processada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_remessawebservice
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db127_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db127_sequencial = $db127_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remessa de Dados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remessa de Dados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db127_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_remessawebservice";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_remessawebservice ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_remessawebservice.db127_usuario";
     $sql .= "      inner join db_sistemaexterno  on  db_sistemaexterno.db124_sequencial = db_remessawebservice.db127_sistemaexterno";
     $sql2 = "";
     if($dbwhere==""){
       if($db127_sequencial!=null ){
         $sql2 .= " where db_remessawebservice.db127_sequencial = $db127_sequencial "; 
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
   function sql_query_file ( $db127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_remessawebservice ";
     $sql2 = "";
     if($dbwhere==""){
       if($db127_sequencial!=null ){
         $sql2 .= " where db_remessawebservice.db127_sequencial = $db127_sequencial "; 
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