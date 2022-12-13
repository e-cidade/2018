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

//MODULO: notificacoes
//CLASSE DA ENTIDADE notiemissao
class cl_notiemissao { 
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
   var $k136_sequencial = 0; 
   var $k136_notificatipogeracao = 0; 
   var $k136_recibo = 'f'; 
   var $k136_data_dia = null; 
   var $k136_data_mes = null; 
   var $k136_data_ano = null; 
   var $k136_data = null; 
   var $k136_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k136_sequencial = int4 = Sequencial 
                 k136_notificatipogeracao = int4 = Tipos de Geração de Notificação 
                 k136_recibo = bool = Recibo 
                 k136_data = date = Data 
                 k136_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_notiemissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notiemissao"); 
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
       $this->k136_sequencial = ($this->k136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_sequencial"]:$this->k136_sequencial);
       $this->k136_notificatipogeracao = ($this->k136_notificatipogeracao == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_notificatipogeracao"]:$this->k136_notificatipogeracao);
       $this->k136_recibo = ($this->k136_recibo == "f"?@$GLOBALS["HTTP_POST_VARS"]["k136_recibo"]:$this->k136_recibo);
       if($this->k136_data == ""){
         $this->k136_data_dia = ($this->k136_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_data_dia"]:$this->k136_data_dia);
         $this->k136_data_mes = ($this->k136_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_data_mes"]:$this->k136_data_mes);
         $this->k136_data_ano = ($this->k136_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_data_ano"]:$this->k136_data_ano);
         if($this->k136_data_dia != ""){
            $this->k136_data = $this->k136_data_ano."-".$this->k136_data_mes."-".$this->k136_data_dia;
         }
       }
       $this->k136_usuario = ($this->k136_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_usuario"]:$this->k136_usuario);
     }else{
       $this->k136_sequencial = ($this->k136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k136_sequencial"]:$this->k136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k136_sequencial){ 
      $this->atualizacampos();
     if($this->k136_notificatipogeracao == null ){ 
       $this->erro_sql = " Campo Tipos de Geração de Notificação nao Informado.";
       $this->erro_campo = "k136_notificatipogeracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k136_recibo == null ){ 
       $this->erro_sql = " Campo Recibo nao Informado.";
       $this->erro_campo = "k136_recibo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k136_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k136_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k136_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k136_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k136_sequencial == "" || $k136_sequencial == null ){
       $result = db_query("select nextval('notiemissao_k136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notiemissao_k136_sequencial_seq do campo: k136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notiemissao_k136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k136_sequencial)){
         $this->erro_sql = " Campo k136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k136_sequencial = $k136_sequencial; 
       }
     }
     if(($this->k136_sequencial == null) || ($this->k136_sequencial == "") ){ 
       $this->erro_sql = " Campo k136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notiemissao(
                                       k136_sequencial 
                                      ,k136_notificatipogeracao 
                                      ,k136_recibo 
                                      ,k136_data 
                                      ,k136_usuario 
                       )
                values (
                                $this->k136_sequencial 
                               ,$this->k136_notificatipogeracao 
                               ,'$this->k136_recibo' 
                               ,".($this->k136_data == "null" || $this->k136_data == ""?"null":"'".$this->k136_data."'")." 
                               ,$this->k136_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Emissão de Notificações ($this->k136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Emissão de Notificações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Emissão de Notificações ($this->k136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k136_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18545,'$this->k136_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3282,18545,'','".AddSlashes(pg_result($resaco,0,'k136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3282,18548,'','".AddSlashes(pg_result($resaco,0,'k136_notificatipogeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3282,18551,'','".AddSlashes(pg_result($resaco,0,'k136_recibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3282,18549,'','".AddSlashes(pg_result($resaco,0,'k136_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3282,18550,'','".AddSlashes(pg_result($resaco,0,'k136_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update notiemissao set ";
     $virgula = "";
     if(trim($this->k136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k136_sequencial"])){ 
       $sql  .= $virgula." k136_sequencial = $this->k136_sequencial ";
       $virgula = ",";
       if(trim($this->k136_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k136_notificatipogeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k136_notificatipogeracao"])){ 
       $sql  .= $virgula." k136_notificatipogeracao = $this->k136_notificatipogeracao ";
       $virgula = ",";
       if(trim($this->k136_notificatipogeracao) == null ){ 
         $this->erro_sql = " Campo Tipos de Geração de Notificação nao Informado.";
         $this->erro_campo = "k136_notificatipogeracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k136_recibo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k136_recibo"])){ 
       $sql  .= $virgula." k136_recibo = '$this->k136_recibo' ";
       $virgula = ",";
       if(trim($this->k136_recibo) == null ){ 
         $this->erro_sql = " Campo Recibo nao Informado.";
         $this->erro_campo = "k136_recibo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k136_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k136_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k136_data_dia"] !="") ){ 
       $sql  .= $virgula." k136_data = '$this->k136_data' ";
       $virgula = ",";
       if(trim($this->k136_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k136_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k136_data_dia"])){ 
         $sql  .= $virgula." k136_data = null ";
         $virgula = ",";
         if(trim($this->k136_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k136_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k136_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k136_usuario"])){ 
       $sql  .= $virgula." k136_usuario = $this->k136_usuario ";
       $virgula = ",";
       if(trim($this->k136_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k136_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k136_sequencial!=null){
       $sql .= " k136_sequencial = $this->k136_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k136_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18545,'$this->k136_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k136_sequencial"]) || $this->k136_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3282,18545,'".AddSlashes(pg_result($resaco,$conresaco,'k136_sequencial'))."','$this->k136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k136_notificatipogeracao"]) || $this->k136_notificatipogeracao != "")
           $resac = db_query("insert into db_acount values($acount,3282,18548,'".AddSlashes(pg_result($resaco,$conresaco,'k136_notificatipogeracao'))."','$this->k136_notificatipogeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k136_recibo"]) || $this->k136_recibo != "")
           $resac = db_query("insert into db_acount values($acount,3282,18551,'".AddSlashes(pg_result($resaco,$conresaco,'k136_recibo'))."','$this->k136_recibo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k136_data"]) || $this->k136_data != "")
           $resac = db_query("insert into db_acount values($acount,3282,18549,'".AddSlashes(pg_result($resaco,$conresaco,'k136_data'))."','$this->k136_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k136_usuario"]) || $this->k136_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3282,18550,'".AddSlashes(pg_result($resaco,$conresaco,'k136_usuario'))."','$this->k136_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissão de Notificações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissão de Notificações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k136_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k136_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18545,'$k136_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3282,18545,'','".AddSlashes(pg_result($resaco,$iresaco,'k136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3282,18548,'','".AddSlashes(pg_result($resaco,$iresaco,'k136_notificatipogeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3282,18551,'','".AddSlashes(pg_result($resaco,$iresaco,'k136_recibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3282,18549,'','".AddSlashes(pg_result($resaco,$iresaco,'k136_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3282,18550,'','".AddSlashes(pg_result($resaco,$iresaco,'k136_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notiemissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k136_sequencial = $k136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissão de Notificações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissão de Notificações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:notiemissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiemissao ";
     $sql .= "      inner join notificatipogeracao  on  notificatipogeracao.k135_sequencial = notiemissao.k136_notificatipogeracao";
     $sql2 = "";
     if($dbwhere==""){
       if($k136_sequencial!=null ){
         $sql2 .= " where notiemissao.k136_sequencial = $k136_sequencial "; 
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
   function sql_query_file ( $k136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiemissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k136_sequencial!=null ){
         $sql2 .= " where notiemissao.k136_sequencial = $k136_sequencial "; 
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