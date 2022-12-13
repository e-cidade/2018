<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE registroprecomovimentacao
class cl_registroprecomovimentacao { 
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
   var $pc58_sequencial = 0; 
   var $pc58_tipo = 0; 
   var $pc58_usuario = 0; 
   var $pc58_data_dia = null; 
   var $pc58_data_mes = null; 
   var $pc58_data_ano = null; 
   var $pc58_data = null; 
   var $pc58_situacao = 0; 
   var $pc58_solicita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc58_sequencial = int4 = Sequencial 
                 pc58_tipo = int4 = Código do Tipo 
                 pc58_usuario = int4 = Código do Usuário 
                 pc58_data = date = Data 
                 pc58_situacao = int4 = Código da Situação 
                 pc58_solicita = int4 = Código da Compilação 
                 ";
   //funcao construtor da classe 
   function cl_registroprecomovimentacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecomovimentacao"); 
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
       $this->pc58_sequencial = ($this->pc58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_sequencial"]:$this->pc58_sequencial);
       $this->pc58_tipo = ($this->pc58_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_tipo"]:$this->pc58_tipo);
       $this->pc58_usuario = ($this->pc58_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_usuario"]:$this->pc58_usuario);
       if($this->pc58_data == ""){
         $this->pc58_data_dia = ($this->pc58_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_data_dia"]:$this->pc58_data_dia);
         $this->pc58_data_mes = ($this->pc58_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_data_mes"]:$this->pc58_data_mes);
         $this->pc58_data_ano = ($this->pc58_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_data_ano"]:$this->pc58_data_ano);
         if($this->pc58_data_dia != ""){
            $this->pc58_data = $this->pc58_data_ano."-".$this->pc58_data_mes."-".$this->pc58_data_dia;
         }
       }
       $this->pc58_situacao = ($this->pc58_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_situacao"]:$this->pc58_situacao);
       $this->pc58_solicita = ($this->pc58_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_solicita"]:$this->pc58_solicita);
     }else{
       $this->pc58_sequencial = ($this->pc58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc58_sequencial"]:$this->pc58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc58_sequencial){ 
      $this->atualizacampos();
     if($this->pc58_tipo == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "pc58_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc58_usuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "pc58_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc58_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "pc58_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc58_situacao == null ){ 
       $this->erro_sql = " Campo Código da Situação nao Informado.";
       $this->erro_campo = "pc58_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc58_solicita == null ){ 
       $this->erro_sql = " Campo Código da Compilação nao Informado.";
       $this->erro_campo = "pc58_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc58_sequencial == "" || $pc58_sequencial == null ){
       $result = db_query("select nextval('registroprecomovimentacao_pc58_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecomovimentacao_pc58_sequencial_seq do campo: pc58_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc58_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecomovimentacao_pc58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc58_sequencial)){
         $this->erro_sql = " Campo pc58_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc58_sequencial = $pc58_sequencial; 
       }
     }
     if(($this->pc58_sequencial == null) || ($this->pc58_sequencial == "") ){ 
       $this->erro_sql = " Campo pc58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecomovimentacao(
                                       pc58_sequencial 
                                      ,pc58_tipo 
                                      ,pc58_usuario 
                                      ,pc58_data 
                                      ,pc58_situacao 
                                      ,pc58_solicita 
                       )
                values (
                                $this->pc58_sequencial 
                               ,$this->pc58_tipo 
                               ,$this->pc58_usuario 
                               ,".($this->pc58_data == "null" || $this->pc58_data == ""?"null":"'".$this->pc58_data."'")." 
                               ,$this->pc58_situacao 
                               ,$this->pc58_solicita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Movimentação dos Preços ($this->pc58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Movimentação dos Preços já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Movimentação dos Preços ($this->pc58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15291,'$this->pc58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2695,15291,'','".AddSlashes(pg_result($resaco,0,'pc58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2695,15292,'','".AddSlashes(pg_result($resaco,0,'pc58_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2695,15294,'','".AddSlashes(pg_result($resaco,0,'pc58_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2695,15295,'','".AddSlashes(pg_result($resaco,0,'pc58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2695,15296,'','".AddSlashes(pg_result($resaco,0,'pc58_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2695,18204,'','".AddSlashes(pg_result($resaco,0,'pc58_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc58_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecomovimentacao set ";
     $virgula = "";
     if(trim($this->pc58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_sequencial"])){ 
       $sql  .= $virgula." pc58_sequencial = $this->pc58_sequencial ";
       $virgula = ",";
       if(trim($this->pc58_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc58_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_tipo"])){ 
       $sql  .= $virgula." pc58_tipo = $this->pc58_tipo ";
       $virgula = ",";
       if(trim($this->pc58_tipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "pc58_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc58_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_usuario"])){ 
       $sql  .= $virgula." pc58_usuario = $this->pc58_usuario ";
       $virgula = ",";
       if(trim($this->pc58_usuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "pc58_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc58_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc58_data_dia"] !="") ){ 
       $sql  .= $virgula." pc58_data = '$this->pc58_data' ";
       $virgula = ",";
       if(trim($this->pc58_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "pc58_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_data_dia"])){ 
         $sql  .= $virgula." pc58_data = null ";
         $virgula = ",";
         if(trim($this->pc58_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "pc58_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc58_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_situacao"])){ 
       $sql  .= $virgula." pc58_situacao = $this->pc58_situacao ";
       $virgula = ",";
       if(trim($this->pc58_situacao) == null ){ 
         $this->erro_sql = " Campo Código da Situação nao Informado.";
         $this->erro_campo = "pc58_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc58_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc58_solicita"])){ 
       $sql  .= $virgula." pc58_solicita = $this->pc58_solicita ";
       $virgula = ",";
       if(trim($this->pc58_solicita) == null ){ 
         $this->erro_sql = " Campo Código da Compilação nao Informado.";
         $this->erro_campo = "pc58_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc58_sequencial!=null){
       $sql .= " pc58_sequencial = $this->pc58_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15291,'$this->pc58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_sequencial"]) || $this->pc58_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2695,15291,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_sequencial'))."','$this->pc58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_tipo"]) || $this->pc58_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2695,15292,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_tipo'))."','$this->pc58_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_usuario"]) || $this->pc58_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2695,15294,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_usuario'))."','$this->pc58_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_data"]) || $this->pc58_data != "")
           $resac = db_query("insert into db_acount values($acount,2695,15295,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_data'))."','$this->pc58_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_situacao"]) || $this->pc58_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2695,15296,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_situacao'))."','$this->pc58_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc58_solicita"]) || $this->pc58_solicita != "")
           $resac = db_query("insert into db_acount values($acount,2695,18204,'".AddSlashes(pg_result($resaco,$conresaco,'pc58_solicita'))."','$this->pc58_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Movimentação dos Preços nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Movimentação dos Preços nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc58_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc58_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15291,'$pc58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2695,15291,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2695,15292,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2695,15294,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2695,15295,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2695,15296,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2695,18204,'','".AddSlashes(pg_result($resaco,$iresaco,'pc58_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecomovimentacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc58_sequencial = $pc58_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Movimentação dos Preços nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Movimentação dos Preços nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecomovimentacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecomovimentacao ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = registroprecomovimentacao.pc58_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc58_sequencial!=null ){
         $sql2 .= " where registroprecomovimentacao.pc58_sequencial = $pc58_sequencial "; 
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
   function sql_query_file ( $pc58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecomovimentacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc58_sequencial!=null ){
         $sql2 .= " where registroprecomovimentacao.pc58_sequencial = $pc58_sequencial "; 
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