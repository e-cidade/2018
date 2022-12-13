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

//MODULO: social
//CLASSE DA ENTIDADE importacaocadastrounico
class cl_importacaocadastrounico { 
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
   var $as07_sequencial = 0; 
   var $as07_usuario = 0; 
   var $as07_dataarquivo_dia = null; 
   var $as07_dataarquivo_mes = null; 
   var $as07_dataarquivo_ano = null; 
   var $as07_dataarquivo = null; 
   var $as07_dataprocessamento_dia = null; 
   var $as07_dataprocessamento_mes = null; 
   var $as07_dataprocessamento_ano = null; 
   var $as07_dataprocessamento = null; 
   var $as07_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as07_sequencial = int4 = Código 
                 as07_usuario = int4 = Usuário 
                 as07_dataarquivo = date = Data do Arquivo 
                 as07_dataprocessamento = date = Data de Processamento 
                 as07_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_importacaocadastrounico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("importacaocadastrounico"); 
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
       $this->as07_sequencial = ($this->as07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_sequencial"]:$this->as07_sequencial);
       $this->as07_usuario = ($this->as07_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_usuario"]:$this->as07_usuario);
       if($this->as07_dataarquivo == ""){
         $this->as07_dataarquivo_dia = ($this->as07_dataarquivo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_dia"]:$this->as07_dataarquivo_dia);
         $this->as07_dataarquivo_mes = ($this->as07_dataarquivo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_mes"]:$this->as07_dataarquivo_mes);
         $this->as07_dataarquivo_ano = ($this->as07_dataarquivo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_ano"]:$this->as07_dataarquivo_ano);
         if($this->as07_dataarquivo_dia != ""){
            $this->as07_dataarquivo = $this->as07_dataarquivo_ano."-".$this->as07_dataarquivo_mes."-".$this->as07_dataarquivo_dia;
         }
       }
       if($this->as07_dataprocessamento == ""){
         $this->as07_dataprocessamento_dia = ($this->as07_dataprocessamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_dia"]:$this->as07_dataprocessamento_dia);
         $this->as07_dataprocessamento_mes = ($this->as07_dataprocessamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_mes"]:$this->as07_dataprocessamento_mes);
         $this->as07_dataprocessamento_ano = ($this->as07_dataprocessamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_ano"]:$this->as07_dataprocessamento_ano);
         if($this->as07_dataprocessamento_dia != ""){
            $this->as07_dataprocessamento = $this->as07_dataprocessamento_ano."-".$this->as07_dataprocessamento_mes."-".$this->as07_dataprocessamento_dia;
         }
       }
       $this->as07_hora = ($this->as07_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_hora"]:$this->as07_hora);
     }else{
       $this->as07_sequencial = ($this->as07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as07_sequencial"]:$this->as07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as07_sequencial){ 
      $this->atualizacampos();
     if($this->as07_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "as07_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as07_dataarquivo == null ){ 
       $this->erro_sql = " Campo Data do Arquivo nao Informado.";
       $this->erro_campo = "as07_dataarquivo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as07_dataprocessamento == null ){ 
       $this->erro_sql = " Campo Data de Processamento nao Informado.";
       $this->erro_campo = "as07_dataprocessamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as07_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "as07_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as07_sequencial == "" || $as07_sequencial == null ){
       $result = db_query("select nextval('importacaocadastrounico_as07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: importacaocadastrounico_as07_sequencial_seq do campo: as07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from importacaocadastrounico_as07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as07_sequencial)){
         $this->erro_sql = " Campo as07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as07_sequencial = $as07_sequencial; 
       }
     }
     if(($this->as07_sequencial == null) || ($this->as07_sequencial == "") ){ 
       $this->erro_sql = " Campo as07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into importacaocadastrounico(
                                       as07_sequencial 
                                      ,as07_usuario 
                                      ,as07_dataarquivo 
                                      ,as07_dataprocessamento 
                                      ,as07_hora 
                       )
                values (
                                $this->as07_sequencial 
                               ,$this->as07_usuario 
                               ,".($this->as07_dataarquivo == "null" || $this->as07_dataarquivo == ""?"null":"'".$this->as07_dataarquivo."'")." 
                               ,".($this->as07_dataprocessamento == "null" || $this->as07_dataprocessamento == ""?"null":"'".$this->as07_dataprocessamento."'")." 
                               ,'$this->as07_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "importacaocadastrounico ($this->as07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "importacaocadastrounico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "importacaocadastrounico ($this->as07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->as07_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19090,'$this->as07_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3397,19090,'','".AddSlashes(pg_result($resaco,0,'as07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3397,19091,'','".AddSlashes(pg_result($resaco,0,'as07_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3397,19092,'','".AddSlashes(pg_result($resaco,0,'as07_dataarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3397,19093,'','".AddSlashes(pg_result($resaco,0,'as07_dataprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3397,19094,'','".AddSlashes(pg_result($resaco,0,'as07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update importacaocadastrounico set ";
     $virgula = "";
     if(trim($this->as07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as07_sequencial"])){ 
       $sql  .= $virgula." as07_sequencial = $this->as07_sequencial ";
       $virgula = ",";
       if(trim($this->as07_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as07_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as07_usuario"])){ 
       $sql  .= $virgula." as07_usuario = $this->as07_usuario ";
       $virgula = ",";
       if(trim($this->as07_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "as07_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as07_dataarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_dia"] !="") ){ 
       $sql  .= $virgula." as07_dataarquivo = '$this->as07_dataarquivo' ";
       $virgula = ",";
       if(trim($this->as07_dataarquivo) == null ){ 
         $this->erro_sql = " Campo Data do Arquivo nao Informado.";
         $this->erro_campo = "as07_dataarquivo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo_dia"])){ 
         $sql  .= $virgula." as07_dataarquivo = null ";
         $virgula = ",";
         if(trim($this->as07_dataarquivo) == null ){ 
           $this->erro_sql = " Campo Data do Arquivo nao Informado.";
           $this->erro_campo = "as07_dataarquivo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as07_dataprocessamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_dia"] !="") ){ 
       $sql  .= $virgula." as07_dataprocessamento = '$this->as07_dataprocessamento' ";
       $virgula = ",";
       if(trim($this->as07_dataprocessamento) == null ){ 
         $this->erro_sql = " Campo Data de Processamento nao Informado.";
         $this->erro_campo = "as07_dataprocessamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento_dia"])){ 
         $sql  .= $virgula." as07_dataprocessamento = null ";
         $virgula = ",";
         if(trim($this->as07_dataprocessamento) == null ){ 
           $this->erro_sql = " Campo Data de Processamento nao Informado.";
           $this->erro_campo = "as07_dataprocessamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->as07_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as07_hora"])){ 
       $sql  .= $virgula." as07_hora = '$this->as07_hora' ";
       $virgula = ",";
       if(trim($this->as07_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "as07_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as07_sequencial!=null){
       $sql .= " as07_sequencial = $this->as07_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->as07_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19090,'$this->as07_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as07_sequencial"]) || $this->as07_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3397,19090,'".AddSlashes(pg_result($resaco,$conresaco,'as07_sequencial'))."','$this->as07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as07_usuario"]) || $this->as07_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3397,19091,'".AddSlashes(pg_result($resaco,$conresaco,'as07_usuario'))."','$this->as07_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as07_dataarquivo"]) || $this->as07_dataarquivo != "")
           $resac = db_query("insert into db_acount values($acount,3397,19092,'".AddSlashes(pg_result($resaco,$conresaco,'as07_dataarquivo'))."','$this->as07_dataarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as07_dataprocessamento"]) || $this->as07_dataprocessamento != "")
           $resac = db_query("insert into db_acount values($acount,3397,19093,'".AddSlashes(pg_result($resaco,$conresaco,'as07_dataprocessamento'))."','$this->as07_dataprocessamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["as07_hora"]) || $this->as07_hora != "")
           $resac = db_query("insert into db_acount values($acount,3397,19094,'".AddSlashes(pg_result($resaco,$conresaco,'as07_hora'))."','$this->as07_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "importacaocadastrounico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "importacaocadastrounico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as07_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($as07_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19090,'$as07_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3397,19090,'','".AddSlashes(pg_result($resaco,$iresaco,'as07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3397,19091,'','".AddSlashes(pg_result($resaco,$iresaco,'as07_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3397,19092,'','".AddSlashes(pg_result($resaco,$iresaco,'as07_dataarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3397,19093,'','".AddSlashes(pg_result($resaco,$iresaco,'as07_dataprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3397,19094,'','".AddSlashes(pg_result($resaco,$iresaco,'as07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from importacaocadastrounico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as07_sequencial = $as07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "importacaocadastrounico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "importacaocadastrounico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:importacaocadastrounico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from importacaocadastrounico ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = importacaocadastrounico.as07_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($as07_sequencial!=null ){
         $sql2 .= " where importacaocadastrounico.as07_sequencial = $as07_sequencial "; 
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
   function sql_query_file ( $as07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from importacaocadastrounico ";
     $sql2 = "";
     if($dbwhere==""){
       if($as07_sequencial!=null ){
         $sql2 .= " where importacaocadastrounico.as07_sequencial = $as07_sequencial "; 
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