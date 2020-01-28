<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE corgrupocorrente
class cl_corgrupocorrente { 
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
   var $k105_sequencial = 0; 
   var $k105_corgrupo = 0; 
   var $k105_data_dia = null; 
   var $k105_data_mes = null; 
   var $k105_data_ano = null; 
   var $k105_data = null; 
   var $k105_autent = 0; 
   var $k105_id = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k105_sequencial = int4 = Código Sequencial 
                 k105_corgrupo = int4 = Código do Grupo 
                 k105_data = date = data da Autenticação 
                 k105_autent = int4 = Sequencia da Autenticação 
                 k105_id = int4 = Código do caixa 
                 ";
   //funcao construtor da classe 
   function cl_corgrupocorrente() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("corgrupocorrente"); 
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
       $this->k105_sequencial = ($this->k105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_sequencial"]:$this->k105_sequencial);
       $this->k105_corgrupo = ($this->k105_corgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_corgrupo"]:$this->k105_corgrupo);
       if($this->k105_data == ""){
         $this->k105_data_dia = ($this->k105_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_data_dia"]:$this->k105_data_dia);
         $this->k105_data_mes = ($this->k105_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_data_mes"]:$this->k105_data_mes);
         $this->k105_data_ano = ($this->k105_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_data_ano"]:$this->k105_data_ano);
         if($this->k105_data_dia != ""){
            $this->k105_data = $this->k105_data_ano."-".$this->k105_data_mes."-".$this->k105_data_dia;
         }
       }
       $this->k105_autent = ($this->k105_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_autent"]:$this->k105_autent);
       $this->k105_id = ($this->k105_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_id"]:$this->k105_id);
     }else{
       $this->k105_sequencial = ($this->k105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k105_sequencial"]:$this->k105_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k105_sequencial){ 
      $this->atualizacampos();
     if($this->k105_corgrupo == null ){ 
       $this->erro_sql = " Campo Código do Grupo nao Informado.";
       $this->erro_campo = "k105_corgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k105_data == null ){ 
       $this->erro_sql = " Campo data da Autenticação nao Informado.";
       $this->erro_campo = "k105_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k105_autent == null ){ 
       $this->erro_sql = " Campo Sequencia da Autenticação nao Informado.";
       $this->erro_campo = "k105_autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k105_id == null ){ 
       $this->erro_sql = " Campo Código do caixa nao Informado.";
       $this->erro_campo = "k105_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k105_sequencial == "" || $k105_sequencial == null ){
       $result = db_query("select nextval('corgrupocorrente_k105_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: corgrupocorrente_k105_sequencial_seq do campo: k105_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k105_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from corgrupocorrente_k105_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k105_sequencial)){
         $this->erro_sql = " Campo k105_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k105_sequencial = $k105_sequencial; 
       }
     }
     if(($this->k105_sequencial == null) || ($this->k105_sequencial == "") ){ 
       $this->erro_sql = " Campo k105_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into corgrupocorrente(
                                       k105_sequencial 
                                      ,k105_corgrupo 
                                      ,k105_data 
                                      ,k105_autent 
                                      ,k105_id 
                       )
                values (
                                $this->k105_sequencial 
                               ,$this->k105_corgrupo 
                               ,".($this->k105_data == "null" || $this->k105_data == ""?"null":"'".$this->k105_data."'")." 
                               ,$this->k105_autent 
                               ,$this->k105_id 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentos do grupo ($this->k105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentos do grupo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentos do grupo ($this->k105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k105_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k105_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12447,'$this->k105_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2171,12447,'','".AddSlashes(pg_result($resaco,0,'k105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2171,12448,'','".AddSlashes(pg_result($resaco,0,'k105_corgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2171,12449,'','".AddSlashes(pg_result($resaco,0,'k105_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2171,12450,'','".AddSlashes(pg_result($resaco,0,'k105_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2171,12451,'','".AddSlashes(pg_result($resaco,0,'k105_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k105_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update corgrupocorrente set ";
     $virgula = "";
     if(trim($this->k105_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k105_sequencial"])){ 
       $sql  .= $virgula." k105_sequencial = $this->k105_sequencial ";
       $virgula = ",";
       if(trim($this->k105_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "k105_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k105_corgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k105_corgrupo"])){ 
       $sql  .= $virgula." k105_corgrupo = $this->k105_corgrupo ";
       $virgula = ",";
       if(trim($this->k105_corgrupo) == null ){ 
         $this->erro_sql = " Campo Código do Grupo nao Informado.";
         $this->erro_campo = "k105_corgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k105_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k105_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k105_data_dia"] !="") ){ 
       $sql  .= $virgula." k105_data = '$this->k105_data' ";
       $virgula = ",";
       if(trim($this->k105_data) == null ){ 
         $this->erro_sql = " Campo data da Autenticação nao Informado.";
         $this->erro_campo = "k105_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k105_data_dia"])){ 
         $sql  .= $virgula." k105_data = null ";
         $virgula = ",";
         if(trim($this->k105_data) == null ){ 
           $this->erro_sql = " Campo data da Autenticação nao Informado.";
           $this->erro_campo = "k105_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k105_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k105_autent"])){ 
       $sql  .= $virgula." k105_autent = $this->k105_autent ";
       $virgula = ",";
       if(trim($this->k105_autent) == null ){ 
         $this->erro_sql = " Campo Sequencia da Autenticação nao Informado.";
         $this->erro_campo = "k105_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k105_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k105_id"])){ 
       $sql  .= $virgula." k105_id = $this->k105_id ";
       $virgula = ",";
       if(trim($this->k105_id) == null ){ 
         $this->erro_sql = " Campo Código do caixa nao Informado.";
         $this->erro_campo = "k105_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k105_sequencial!=null){
       $sql .= " k105_sequencial = $this->k105_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k105_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12447,'$this->k105_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k105_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2171,12447,'".AddSlashes(pg_result($resaco,$conresaco,'k105_sequencial'))."','$this->k105_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k105_corgrupo"]))
           $resac = db_query("insert into db_acount values($acount,2171,12448,'".AddSlashes(pg_result($resaco,$conresaco,'k105_corgrupo'))."','$this->k105_corgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k105_data"]))
           $resac = db_query("insert into db_acount values($acount,2171,12449,'".AddSlashes(pg_result($resaco,$conresaco,'k105_data'))."','$this->k105_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k105_autent"]))
           $resac = db_query("insert into db_acount values($acount,2171,12450,'".AddSlashes(pg_result($resaco,$conresaco,'k105_autent'))."','$this->k105_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k105_id"]))
           $resac = db_query("insert into db_acount values($acount,2171,12451,'".AddSlashes(pg_result($resaco,$conresaco,'k105_id'))."','$this->k105_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos do grupo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos do grupo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k105_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k105_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12447,'$k105_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2171,12447,'','".AddSlashes(pg_result($resaco,$iresaco,'k105_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2171,12448,'','".AddSlashes(pg_result($resaco,$iresaco,'k105_corgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2171,12449,'','".AddSlashes(pg_result($resaco,$iresaco,'k105_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2171,12450,'','".AddSlashes(pg_result($resaco,$iresaco,'k105_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2171,12451,'','".AddSlashes(pg_result($resaco,$iresaco,'k105_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from corgrupocorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k105_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k105_sequencial = $k105_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentos do grupo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentos do grupo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k105_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:corgrupocorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from corgrupocorrente ";
     $sql .= "      inner join corrente  on  corrente.k12_id = corgrupocorrente.k105_id and  corrente.k12_data = corgrupocorrente.k105_data and  corrente.k12_autent = corgrupocorrente.k105_autent";
     $sql .= "      inner join corgrupo  on  corgrupo.k104_sequencial = corgrupocorrente.k105_corgrupo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = corrente.k12_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k105_sequencial!=null ){
         $sql2 .= " where corgrupocorrente.k105_sequencial = $k105_sequencial "; 
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
   function sql_query_file ( $k105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from corgrupocorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($k105_sequencial!=null ){
         $sql2 .= " where corgrupocorrente.k105_sequencial = $k105_sequencial "; 
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