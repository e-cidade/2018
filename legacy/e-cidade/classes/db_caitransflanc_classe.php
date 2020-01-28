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

//MODULO: caixa
//CLASSE DA ENTIDADE caitransflanc
class cl_caitransflanc { 
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
   var $k93_transf = 0; 
   var $k93_sequen = 0; 
   var $k93_instit = 0; 
   var $k93_debito = 0; 
   var $k93_credito = 0; 
   var $k93_finalidade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k93_transf = int4 = Trasferencia 
                 k93_sequen = int4 = Sequencial 
                 k93_instit = int4 = Instituição 
                 k93_debito = int4 = Debito 
                 k93_credito = int4 = Credito 
                 k93_finalidade = text = Finalidade 
                 ";
   //funcao construtor da classe 
   function cl_caitransflanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caitransflanc"); 
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
       $this->k93_transf = ($this->k93_transf == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_transf"]:$this->k93_transf);
       $this->k93_sequen = ($this->k93_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_sequen"]:$this->k93_sequen);
       $this->k93_instit = ($this->k93_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_instit"]:$this->k93_instit);
       $this->k93_debito = ($this->k93_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_debito"]:$this->k93_debito);
       $this->k93_credito = ($this->k93_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_credito"]:$this->k93_credito);
       $this->k93_finalidade = ($this->k93_finalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_finalidade"]:$this->k93_finalidade);
     }else{
       $this->k93_sequen = ($this->k93_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["k93_sequen"]:$this->k93_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($k93_sequen){ 
      $this->atualizacampos();
     if($this->k93_transf == null ){ 
       $this->erro_sql = " Campo Trasferencia nao Informado.";
       $this->erro_campo = "k93_transf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k93_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k93_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k93_debito == null ){ 
       $this->erro_sql = " Campo Debito nao Informado.";
       $this->erro_campo = "k93_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k93_credito == null ){ 
       $this->erro_sql = " Campo Credito nao Informado.";
       $this->erro_campo = "k93_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k93_finalidade == null ){ 
       $this->erro_sql = " Campo Finalidade nao Informado.";
       $this->erro_campo = "k93_finalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k93_sequen == "" || $k93_sequen == null ){
       $result = db_query("select nextval('caitransflanc_k93_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caitransflanc_k93_sequen_seq do campo: k93_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k93_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from caitransflanc_k93_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $k93_sequen)){
         $this->erro_sql = " Campo k93_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k93_sequen = $k93_sequen; 
       }
     }
     if(($this->k93_sequen == null) || ($this->k93_sequen == "") ){ 
       $this->erro_sql = " Campo k93_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caitransflanc(
                                       k93_transf 
                                      ,k93_sequen 
                                      ,k93_instit 
                                      ,k93_debito 
                                      ,k93_credito 
                                      ,k93_finalidade 
                       )
                values (
                                $this->k93_transf 
                               ,$this->k93_sequen 
                               ,$this->k93_instit 
                               ,$this->k93_debito 
                               ,$this->k93_credito 
                               ,'$this->k93_finalidade' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k93_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k93_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k93_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k93_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8325,'$this->k93_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,1406,8324,'','".AddSlashes(pg_result($resaco,0,'k93_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1406,8325,'','".AddSlashes(pg_result($resaco,0,'k93_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1406,8326,'','".AddSlashes(pg_result($resaco,0,'k93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1406,8327,'','".AddSlashes(pg_result($resaco,0,'k93_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1406,8328,'','".AddSlashes(pg_result($resaco,0,'k93_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1406,8329,'','".AddSlashes(pg_result($resaco,0,'k93_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k93_sequen=null) { 
      $this->atualizacampos();
     $sql = " update caitransflanc set ";
     $virgula = "";
     if(trim($this->k93_transf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_transf"])){ 
       $sql  .= $virgula." k93_transf = $this->k93_transf ";
       $virgula = ",";
       if(trim($this->k93_transf) == null ){ 
         $this->erro_sql = " Campo Trasferencia nao Informado.";
         $this->erro_campo = "k93_transf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k93_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_sequen"])){ 
       $sql  .= $virgula." k93_sequen = $this->k93_sequen ";
       $virgula = ",";
       if(trim($this->k93_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k93_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k93_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_instit"])){ 
       $sql  .= $virgula." k93_instit = $this->k93_instit ";
       $virgula = ",";
       if(trim($this->k93_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k93_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k93_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_debito"])){ 
       $sql  .= $virgula." k93_debito = $this->k93_debito ";
       $virgula = ",";
       if(trim($this->k93_debito) == null ){ 
         $this->erro_sql = " Campo Debito nao Informado.";
         $this->erro_campo = "k93_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k93_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_credito"])){ 
       $sql  .= $virgula." k93_credito = $this->k93_credito ";
       $virgula = ",";
       if(trim($this->k93_credito) == null ){ 
         $this->erro_sql = " Campo Credito nao Informado.";
         $this->erro_campo = "k93_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k93_finalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k93_finalidade"])){ 
       $sql  .= $virgula." k93_finalidade = '$this->k93_finalidade' ";
       $virgula = ",";
       if(trim($this->k93_finalidade) == null ){ 
         $this->erro_sql = " Campo Finalidade nao Informado.";
         $this->erro_campo = "k93_finalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k93_sequen!=null){
       $sql .= " k93_sequen = $this->k93_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k93_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8325,'$this->k93_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_transf"]))
           $resac = db_query("insert into db_acount values($acount,1406,8324,'".AddSlashes(pg_result($resaco,$conresaco,'k93_transf'))."','$this->k93_transf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_sequen"]))
           $resac = db_query("insert into db_acount values($acount,1406,8325,'".AddSlashes(pg_result($resaco,$conresaco,'k93_sequen'))."','$this->k93_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_instit"]))
           $resac = db_query("insert into db_acount values($acount,1406,8326,'".AddSlashes(pg_result($resaco,$conresaco,'k93_instit'))."','$this->k93_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_debito"]))
           $resac = db_query("insert into db_acount values($acount,1406,8327,'".AddSlashes(pg_result($resaco,$conresaco,'k93_debito'))."','$this->k93_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_credito"]))
           $resac = db_query("insert into db_acount values($acount,1406,8328,'".AddSlashes(pg_result($resaco,$conresaco,'k93_credito'))."','$this->k93_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k93_finalidade"]))
           $resac = db_query("insert into db_acount values($acount,1406,8329,'".AddSlashes(pg_result($resaco,$conresaco,'k93_finalidade'))."','$this->k93_finalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k93_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k93_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k93_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k93_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k93_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8325,'$k93_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,1406,8324,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1406,8325,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1406,8326,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1406,8327,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1406,8328,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1406,8329,'','".AddSlashes(pg_result($resaco,$iresaco,'k93_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caitransflanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k93_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k93_sequen = $k93_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k93_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k93_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k93_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:caitransflanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k93_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransflanc ";
     $sql .= "      inner join db_config as config on  config.codigo = caitransflanc.k93_instit";
     $sql .= "      inner join caitransf  on  caitransf.k91_transf = caitransflanc.k93_transf";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k93_sequen!=null ){
         $sql2 .= " where caitransflanc.k93_sequen = $k93_sequen "; 
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
   function sql_query_file ( $k93_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransflanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($k93_sequen!=null ){
         $sql2 .= " where caitransflanc.k93_sequen = $k93_sequen "; 
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