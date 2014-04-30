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
//CLASSE DA ENTIDADE mensagemnotificacao
class cl_mensagemnotificacao { 
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
   var $db134_sequencial = 0; 
   var $db134_mensagemnotificacaotipo = 0; 
   var $db134_enviada = 'f'; 
   var $db134_telefone = null; 
   var $db134_email = null; 
   var $db134_assunto = null; 
   var $db134_resumo = null; 
   var $db134_mensagem = null; 
   var $db134_mensagemretorno = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db134_sequencial = int4 = Código Mensagem Notificação 
                 db134_mensagemnotificacaotipo = int4 = Mensagem Notificação Tipo 
                 db134_enviada = bool = Enviada 
                 db134_telefone = varchar(11) = Telefone 
                 db134_email = varchar(50) = Email 
                 db134_assunto = varchar(50) = Assunto 
                 db134_resumo = varchar(100) = Resumo 
                 db134_mensagem = text = Mensagem 
                 db134_mensagemretorno = varchar(150) = Mensagem de Retorno 
                 ";
   //funcao construtor da classe 
   function cl_mensagemnotificacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mensagemnotificacao"); 
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
       $this->db134_sequencial = ($this->db134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_sequencial"]:$this->db134_sequencial);
       $this->db134_mensagemnotificacaotipo = ($this->db134_mensagemnotificacaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_mensagemnotificacaotipo"]:$this->db134_mensagemnotificacaotipo);
       $this->db134_enviada = ($this->db134_enviada == "f"?@$GLOBALS["HTTP_POST_VARS"]["db134_enviada"]:$this->db134_enviada);
       $this->db134_telefone = ($this->db134_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_telefone"]:$this->db134_telefone);
       $this->db134_email = ($this->db134_email == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_email"]:$this->db134_email);
       $this->db134_assunto = ($this->db134_assunto == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_assunto"]:$this->db134_assunto);
       $this->db134_resumo = ($this->db134_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_resumo"]:$this->db134_resumo);
       $this->db134_mensagem = ($this->db134_mensagem == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_mensagem"]:$this->db134_mensagem);
       $this->db134_mensagemretorno = ($this->db134_mensagemretorno == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_mensagemretorno"]:$this->db134_mensagemretorno);
     }else{
       $this->db134_sequencial = ($this->db134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db134_sequencial"]:$this->db134_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db134_sequencial){ 
      $this->atualizacampos();
     if($this->db134_mensagemnotificacaotipo == null ){ 
       $this->erro_sql = " Campo Mensagem Notificação Tipo nao Informado.";
       $this->erro_campo = "db134_mensagemnotificacaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db134_enviada == null ){ 
       $this->erro_sql = " Campo Enviada nao Informado.";
       $this->erro_campo = "db134_enviada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db134_telefone == null ){ 
       $this->db134_telefone = "0";
     }
     if($this->db134_assunto == null ){ 
       $this->erro_sql = " Campo Assunto nao Informado.";
       $this->erro_campo = "db134_assunto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db134_resumo == null ){ 
       $this->erro_sql = " Campo Resumo nao Informado.";
       $this->erro_campo = "db134_resumo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db134_mensagem == null ){ 
       $this->erro_sql = " Campo Mensagem nao Informado.";
       $this->erro_campo = "db134_mensagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db134_sequencial == "" || $db134_sequencial == null ){
       $result = db_query("select nextval('mensagemnotificacao_db134_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mensagemnotificacao_db134_sequencial_seq do campo: db134_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db134_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mensagemnotificacao_db134_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db134_sequencial)){
         $this->erro_sql = " Campo db134_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db134_sequencial = $db134_sequencial; 
       }
     }
     if(($this->db134_sequencial == null) || ($this->db134_sequencial == "") ){ 
       $this->erro_sql = " Campo db134_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mensagemnotificacao(
                                       db134_sequencial 
                                      ,db134_mensagemnotificacaotipo 
                                      ,db134_enviada 
                                      ,db134_telefone 
                                      ,db134_email 
                                      ,db134_assunto 
                                      ,db134_resumo 
                                      ,db134_mensagem 
                                      ,db134_mensagemretorno 
                       )
                values (
                                $this->db134_sequencial 
                               ,$this->db134_mensagemnotificacaotipo 
                               ,'$this->db134_enviada' 
                               ,'$this->db134_telefone' 
                               ,'$this->db134_email' 
                               ,'$this->db134_assunto' 
                               ,'$this->db134_resumo' 
                               ,'$this->db134_mensagem' 
                               ,'$this->db134_mensagemretorno' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mensagemnotificacao ($this->db134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mensagemnotificacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mensagemnotificacao ($this->db134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db134_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db134_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19259,'$this->db134_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3424,19259,'','".AddSlashes(pg_result($resaco,0,'db134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19260,'','".AddSlashes(pg_result($resaco,0,'db134_mensagemnotificacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19261,'','".AddSlashes(pg_result($resaco,0,'db134_enviada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19262,'','".AddSlashes(pg_result($resaco,0,'db134_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19263,'','".AddSlashes(pg_result($resaco,0,'db134_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19264,'','".AddSlashes(pg_result($resaco,0,'db134_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19265,'','".AddSlashes(pg_result($resaco,0,'db134_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19266,'','".AddSlashes(pg_result($resaco,0,'db134_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3424,19267,'','".AddSlashes(pg_result($resaco,0,'db134_mensagemretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db134_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update mensagemnotificacao set ";
     $virgula = "";
     if(trim($this->db134_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_sequencial"])){ 
       $sql  .= $virgula." db134_sequencial = $this->db134_sequencial ";
       $virgula = ",";
       if(trim($this->db134_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Mensagem Notificação nao Informado.";
         $this->erro_campo = "db134_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_mensagemnotificacaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagemnotificacaotipo"])){ 
       $sql  .= $virgula." db134_mensagemnotificacaotipo = $this->db134_mensagemnotificacaotipo ";
       $virgula = ",";
       if(trim($this->db134_mensagemnotificacaotipo) == null ){ 
         $this->erro_sql = " Campo Mensagem Notificação Tipo nao Informado.";
         $this->erro_campo = "db134_mensagemnotificacaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_enviada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_enviada"])){ 
       $sql  .= $virgula." db134_enviada = '$this->db134_enviada' ";
       $virgula = ",";
       if(trim($this->db134_enviada) == null ){ 
         $this->erro_sql = " Campo Enviada nao Informado.";
         $this->erro_campo = "db134_enviada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_telefone"])){ 
       $sql  .= $virgula." db134_telefone = '$this->db134_telefone' ";
       $virgula = ",";
     }
     if(trim($this->db134_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_email"])){ 
       $sql  .= $virgula." db134_email = '$this->db134_email' ";
       $virgula = ",";
     }
     if(trim($this->db134_assunto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_assunto"])){ 
       $sql  .= $virgula." db134_assunto = '$this->db134_assunto' ";
       $virgula = ",";
       if(trim($this->db134_assunto) == null ){ 
         $this->erro_sql = " Campo Assunto nao Informado.";
         $this->erro_campo = "db134_assunto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_resumo"])){ 
       $sql  .= $virgula." db134_resumo = '$this->db134_resumo' ";
       $virgula = ",";
       if(trim($this->db134_resumo) == null ){ 
         $this->erro_sql = " Campo Resumo nao Informado.";
         $this->erro_campo = "db134_resumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_mensagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagem"])){ 
       $sql  .= $virgula." db134_mensagem = '$this->db134_mensagem' ";
       $virgula = ",";
       if(trim($this->db134_mensagem) == null ){ 
         $this->erro_sql = " Campo Mensagem nao Informado.";
         $this->erro_campo = "db134_mensagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db134_mensagemretorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagemretorno"])){ 
       $sql  .= $virgula." db134_mensagemretorno = '$this->db134_mensagemretorno' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db134_sequencial!=null){
       $sql .= " db134_sequencial = $this->db134_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db134_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19259,'$this->db134_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_sequencial"]) || $this->db134_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3424,19259,'".AddSlashes(pg_result($resaco,$conresaco,'db134_sequencial'))."','$this->db134_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagemnotificacaotipo"]) || $this->db134_mensagemnotificacaotipo != "")
           $resac = db_query("insert into db_acount values($acount,3424,19260,'".AddSlashes(pg_result($resaco,$conresaco,'db134_mensagemnotificacaotipo'))."','$this->db134_mensagemnotificacaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_enviada"]) || $this->db134_enviada != "")
           $resac = db_query("insert into db_acount values($acount,3424,19261,'".AddSlashes(pg_result($resaco,$conresaco,'db134_enviada'))."','$this->db134_enviada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_telefone"]) || $this->db134_telefone != "")
           $resac = db_query("insert into db_acount values($acount,3424,19262,'".AddSlashes(pg_result($resaco,$conresaco,'db134_telefone'))."','$this->db134_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_email"]) || $this->db134_email != "")
           $resac = db_query("insert into db_acount values($acount,3424,19263,'".AddSlashes(pg_result($resaco,$conresaco,'db134_email'))."','$this->db134_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_assunto"]) || $this->db134_assunto != "")
           $resac = db_query("insert into db_acount values($acount,3424,19264,'".AddSlashes(pg_result($resaco,$conresaco,'db134_assunto'))."','$this->db134_assunto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_resumo"]) || $this->db134_resumo != "")
           $resac = db_query("insert into db_acount values($acount,3424,19265,'".AddSlashes(pg_result($resaco,$conresaco,'db134_resumo'))."','$this->db134_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagem"]) || $this->db134_mensagem != "")
           $resac = db_query("insert into db_acount values($acount,3424,19266,'".AddSlashes(pg_result($resaco,$conresaco,'db134_mensagem'))."','$this->db134_mensagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db134_mensagemretorno"]) || $this->db134_mensagemretorno != "")
           $resac = db_query("insert into db_acount values($acount,3424,19267,'".AddSlashes(pg_result($resaco,$conresaco,'db134_mensagemretorno'))."','$this->db134_mensagemretorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mensagemnotificacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mensagemnotificacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db134_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db134_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19259,'$db134_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3424,19259,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19260,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_mensagemnotificacaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19261,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_enviada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19262,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19263,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19264,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_assunto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19265,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19266,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_mensagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3424,19267,'','".AddSlashes(pg_result($resaco,$iresaco,'db134_mensagemretorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mensagemnotificacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db134_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db134_sequencial = $db134_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mensagemnotificacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mensagemnotificacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db134_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:mensagemnotificacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mensagemnotificacao ";
     $sql .= "      inner join mensagemnotificacaotipo  on  mensagemnotificacaotipo.db133_sequencial = mensagemnotificacao.db134_mensagemnotificacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($db134_sequencial!=null ){
         $sql2 .= " where mensagemnotificacao.db134_sequencial = $db134_sequencial "; 
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
   function sql_query_file ( $db134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mensagemnotificacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db134_sequencial!=null ){
         $sql2 .= " where mensagemnotificacao.db134_sequencial = $db134_sequencial "; 
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