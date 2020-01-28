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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolharubrica
class cl_rhempenhofolharubrica { 
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
   var $rh73_sequencial = 0; 
   var $rh73_rubric = null; 
   var $rh73_seqpes = 0; 
   var $rh73_instit = 0; 
   var $rh73_valor = 0; 
   var $rh73_pd = 0; 
   var $rh73_tiporubrica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh73_sequencial = int4 = Sequencial 
                 rh73_rubric = char(4) = Rubrica 
                 rh73_seqpes = int4 = Cadastro Pessoal 
                 rh73_instit = int4 = Instituição 
                 rh73_valor = float8 = Valor 
                 rh73_pd = int4 = Provento/Desconto 
                 rh73_tiporubrica = int4 = Tipo Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolharubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolharubrica"); 
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
       $this->rh73_sequencial = ($this->rh73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_sequencial"]:$this->rh73_sequencial);
       $this->rh73_rubric = ($this->rh73_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_rubric"]:$this->rh73_rubric);
       $this->rh73_seqpes = ($this->rh73_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_seqpes"]:$this->rh73_seqpes);
       $this->rh73_instit = ($this->rh73_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_instit"]:$this->rh73_instit);
       $this->rh73_valor = ($this->rh73_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_valor"]:$this->rh73_valor);
       $this->rh73_pd = ($this->rh73_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_pd"]:$this->rh73_pd);
       $this->rh73_tiporubrica = ($this->rh73_tiporubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_tiporubrica"]:$this->rh73_tiporubrica);
     }else{
       $this->rh73_sequencial = ($this->rh73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh73_sequencial"]:$this->rh73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh73_sequencial){ 
      $this->atualizacampos();
     if($this->rh73_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh73_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh73_seqpes == null ){ 
       $this->erro_sql = " Campo Cadastro Pessoal nao Informado.";
       $this->erro_campo = "rh73_seqpes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh73_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh73_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh73_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh73_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh73_pd == null ){ 
       $this->erro_sql = " Campo Provento/Desconto nao Informado.";
       $this->erro_campo = "rh73_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh73_tiporubrica == null ){ 
       $this->erro_sql = " Campo Tipo Rubrica nao Informado.";
       $this->erro_campo = "rh73_tiporubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh73_sequencial == "" || $rh73_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolharubrica_rh73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolharubrica_rh73_sequencial_seq do campo: rh73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolharubrica_rh73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh73_sequencial)){
         $this->erro_sql = " Campo rh73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh73_sequencial = $rh73_sequencial; 
       }
     }
     if(($this->rh73_sequencial == null) || ($this->rh73_sequencial == "") ){ 
       $this->erro_sql = " Campo rh73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolharubrica(
                                       rh73_sequencial 
                                      ,rh73_rubric 
                                      ,rh73_seqpes 
                                      ,rh73_instit 
                                      ,rh73_valor 
                                      ,rh73_pd 
                                      ,rh73_tiporubrica 
                       )
                values (
                                $this->rh73_sequencial 
                               ,'$this->rh73_rubric' 
                               ,$this->rh73_seqpes 
                               ,$this->rh73_instit 
                               ,$this->rh73_valor 
                               ,$this->rh73_pd 
                               ,$this->rh73_tiporubrica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhempenhofolharubrica ($this->rh73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhempenhofolharubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhempenhofolharubrica ($this->rh73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14244,'$this->rh73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2507,14244,'','".AddSlashes(pg_result($resaco,0,'rh73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14245,'','".AddSlashes(pg_result($resaco,0,'rh73_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14246,'','".AddSlashes(pg_result($resaco,0,'rh73_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14247,'','".AddSlashes(pg_result($resaco,0,'rh73_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14250,'','".AddSlashes(pg_result($resaco,0,'rh73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14251,'','".AddSlashes(pg_result($resaco,0,'rh73_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2507,14252,'','".AddSlashes(pg_result($resaco,0,'rh73_tiporubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolharubrica set ";
     $virgula = "";
     if(trim($this->rh73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_sequencial"])){ 
       $sql  .= $virgula." rh73_sequencial = $this->rh73_sequencial ";
       $virgula = ",";
       if(trim($this->rh73_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_rubric"])){ 
       $sql  .= $virgula." rh73_rubric = '$this->rh73_rubric' ";
       $virgula = ",";
       if(trim($this->rh73_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh73_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_seqpes"])){ 
       $sql  .= $virgula." rh73_seqpes = $this->rh73_seqpes ";
       $virgula = ",";
       if(trim($this->rh73_seqpes) == null ){ 
         $this->erro_sql = " Campo Cadastro Pessoal nao Informado.";
         $this->erro_campo = "rh73_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_instit"])){ 
       $sql  .= $virgula." rh73_instit = $this->rh73_instit ";
       $virgula = ",";
       if(trim($this->rh73_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh73_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_valor"])){ 
       $sql  .= $virgula." rh73_valor = $this->rh73_valor ";
       $virgula = ",";
       if(trim($this->rh73_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh73_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_pd"])){ 
       $sql  .= $virgula." rh73_pd = $this->rh73_pd ";
       $virgula = ",";
       if(trim($this->rh73_pd) == null ){ 
         $this->erro_sql = " Campo Provento/Desconto nao Informado.";
         $this->erro_campo = "rh73_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh73_tiporubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh73_tiporubrica"])){ 
       $sql  .= $virgula." rh73_tiporubrica = $this->rh73_tiporubrica ";
       $virgula = ",";
       if(trim($this->rh73_tiporubrica) == null ){ 
         $this->erro_sql = " Campo Tipo Rubrica nao Informado.";
         $this->erro_campo = "rh73_tiporubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh73_sequencial!=null){
       $sql .= " rh73_sequencial = $this->rh73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14244,'$this->rh73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_sequencial"]) || $this->rh73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2507,14244,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_sequencial'))."','$this->rh73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_rubric"]) || $this->rh73_rubric != "")
           $resac = db_query("insert into db_acount values($acount,2507,14245,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_rubric'))."','$this->rh73_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_seqpes"]) || $this->rh73_seqpes != "")
           $resac = db_query("insert into db_acount values($acount,2507,14246,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_seqpes'))."','$this->rh73_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_instit"]) || $this->rh73_instit != "")
           $resac = db_query("insert into db_acount values($acount,2507,14247,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_instit'))."','$this->rh73_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_valor"]) || $this->rh73_valor != "")
           $resac = db_query("insert into db_acount values($acount,2507,14250,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_valor'))."','$this->rh73_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_pd"]) || $this->rh73_pd != "")
           $resac = db_query("insert into db_acount values($acount,2507,14251,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_pd'))."','$this->rh73_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh73_tiporubrica"]) || $this->rh73_tiporubrica != "")
           $resac = db_query("insert into db_acount values($acount,2507,14252,'".AddSlashes(pg_result($resaco,$conresaco,'rh73_tiporubrica'))."','$this->rh73_tiporubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharubrica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharubrica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14244,'$rh73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2507,14244,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14245,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14246,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14247,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14250,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14251,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2507,14252,'','".AddSlashes(pg_result($resaco,$iresaco,'rh73_tiporubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolharubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh73_sequencial = $rh73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolharubrica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolharubrica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolharubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubrica                                                                                       ";
     $sql .= "      inner join db_config                  on db_config.codigo               = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join rhpessoalmov               on rhpessoalmov.rh02_seqpes       = rhempenhofolharubrica.rh73_seqpes 
                                                         and rhpessoalmov.rh02_instit       = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join rhrubricas                 on rhrubricas.rh27_rubric         = rhempenhofolharubrica.rh73_rubric 
                                                         and rhrubricas.rh27_instit         = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join cgm                        on cgm.z01_numcgm                 = db_config.numcgm                  ";
     $sql .= "      inner join rhlota                     on rhlota.r70_codigo              = rhpessoalmov.rh02_lota            ";
     $sql .= "      inner join rhregime                   on rhregime.rh30_codreg           = rhpessoalmov.rh02_codreg          ";
     $sql .= "      inner join rhtipomedia                on rhtipomedia.rh29_tipo          = rhrubricas.rh27_calc1             ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh73_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubrica.rh73_sequencial = $rh73_sequencial "; 
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
   function sql_query_file ( $rh73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh73_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubrica.rh73_sequencial = $rh73_sequencial "; 
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
  function sql_query_pessoal( $rh73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

    $sql = "select ";
    if ($campos != "*" ){
  
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
  
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
       $sql .= $campos;
    }
    $sql .= " from rhempenhofolharubrica ";
    $sql .= "      inner join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial ";
    $sql .= "      inner join rhempenhofolha on rh81_rhempenhofolha = rh72_sequencial                    ";
    $sql .= "      inner join rhpessoalmov   on rh73_seqpes         = rh02_seqpes                        ";
    $sql .= "                               and rh73_instit         = rh02_instit                        ";
    $sql .= "                               and rh72_mesusu         = rh02_mesusu                        ";
    $sql .= "                               and rh72_anousu         = rh72_anousu                        ";
    $sql .= "      inner join rhpessoal      on rh02_regist         = rh01_regist                        ";
    $sql .= "      inner join cgm            on rh01_numcgm         = z01_numcgm                         ";
    $sql .= "      inner join orcorgao       on rh72_orgao          = o40_orgao                          ";
    $sql .= "                               and rh72_anousu         = o40_anousu                         ";
    $sql .= "      inner join orcunidade     on o40_orgao           = o41_orgao                          ";
    $sql .= "                               and o40_anousu          = o41_anousu                         ";
    $sql .= "                               and rh72_unidade        = o41_unidade                        ";    
       
    $sql2 = "";
       
    if ($dbwhere==""){
  
      if($rh73_sequencial!=null ){
       $sql2 .= " where rhempenhofolharubrica.rh73_sequencial = $rh73_sequencial "; 
      } 
       
     } else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if ($ordem != null ) {
        
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for ($i = 0; $i < sizeof($campos_sql); $i++) {
           
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  
  function sql_query_dados( $rh73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolharubrica                                                                                         ";
     $sql .= "      inner join db_config                    on db_config.codigo                 = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join rhpessoalmov                 on rhpessoalmov.rh02_seqpes         = rhempenhofolharubrica.rh73_seqpes 
                                                           and rhpessoalmov.rh02_instit         = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join rhrubricas                   on rhrubricas.rh27_rubric           = rhempenhofolharubrica.rh73_rubric 
                                                           and rhrubricas.rh27_instit           = rhempenhofolharubrica.rh73_instit ";
     $sql .= "      inner join cgm                          on cgm.z01_numcgm                   = db_config.numcgm                  ";
     $sql .= "      inner join rhlota                       on rhlota.r70_codigo                = rhpessoalmov.rh02_lota            ";
     $sql .= "      inner join rhregime                     on rhregime.rh30_codreg             = rhpessoalmov.rh02_codreg          ";
     $sql .= "      inner join rhtipomedia                  on rhtipomedia.rh29_tipo            = rhrubricas.rh27_calc1             ";
     $sql .= "      left  join rhslipfolharhemprubrica      on rhslipfolharhemprubrica.rh80_rhempenhofolharubrica    = rhempenhofolharubrica.rh73_sequencial ";
     $sql .= "      left  join rhslipfolha                  on rhslipfolha.rh79_sequencial      = rhslipfolharhemprubrica.rh80_rhslipfolha";
     $sql .= "      left  join rhslipfolhaslip              on rhslipfolhaslip.rh82_rhslipfolha = rhslipfolha.rh79_sequencial       ";
     $sql .= "      left  join rhempenhofolharhemprubrica   on rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial ";
     $sql .= "      left  join rhempenhofolha               on rhempenhofolha.rh72_sequencial   = rhempenhofolharhemprubrica.rh81_rhempenhofolha";
     $sql .= "      left  join rhempenhofolhaempenho        on rhempenhofolhaempenho.rh76_rhempenhofolha = rhempenhofolha.rh72_sequencial";
     $sql .= "      left  join rhdevolucaofolharhemprubrica on rhdevolucaofolharhemprubrica.rh87_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial ";
     $sql .= "      left  join rhdevolucaofolha             on rhdevolucaofolha.rh69_sequencial = rhdevolucaofolharhemprubrica.rh87_devolucaofolha";     
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh73_sequencial!=null ){
         $sql2 .= " where rhempenhofolharubrica.rh73_sequencial = $rh73_sequencial "; 
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

  function sql_query_rhempenhofolharubricas( $rh73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     
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
     
     $sql .= "from rhempenhofolharubrica ";
     $sql .= "     inner join rhrubricas                    on rhempenhofolharubrica.rh73_rubric                         = rhrubricas.rh27_rubric                             \n"; 
     $sql .= "                                             and rhempenhofolharubrica.rh73_instit                         = rhrubricas.rh27_instit                             \n";
     $sql .= "      left join rhempenhofolharhemprubrica    on rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica     = rhempenhofolharubrica.rh73_sequencial              \n"; 
     $sql .= "      left join rhempenhofolha                on rhempenhofolharhemprubrica.rh81_rhempenhofolha            = rhempenhofolha.rh72_sequencial                     \n";
     $sql .= "      left join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica  = rhempenhofolharubrica.rh73_sequencial              \n";
     $sql .= "      left join retencaotiporec               on retencaotiporec.e21_sequencial                            = rhempenhofolharubricaretencao.rh78_retencaotiporec \n"; 
     $sql .= "      left join retencaotiporeccgm            on retencaotiporeccgm.e48_retencaotiporec                    = retencaotiporec.e21_sequencial                     \n";
     $sql .= "      left join rhslipfolharhemprubrica       on rhslipfolharhemprubrica.rh80_rhempenhofolharubrica        = rhempenhofolharubrica.rh73_sequencial              \n";  
     $sql .= "      left join rhslipfolha                   on rhslipfolharhemprubrica.rh80_rhslipfolha                  = rhslipfolha.rh79_sequencial                        \n";
     $sql .= "      left join rhcontasrec as conta_slip     on conta_slip.rh41_codigo                                    = rhslipfolha.rh79_recurso                           \n";
     $sql .= "                                             and conta_slip.rh41_anousu                                    = rhslipfolha.rh79_anousu                            \n";
     $sql .= "                                             and conta_slip.rh41_instit                                    = rhempenhofolharubrica.rh73_instit                  \n";
     $sql .= "      left join rhcontasrec as conta_empenho  on conta_empenho.rh41_codigo                                 = rhempenhofolha.rh72_recurso                        \n"; 
     $sql .= "                                             and conta_empenho.rh41_anousu                                 = rhempenhofolha.rh72_anousu                         \n";
     $sql .= "                                             and conta_empenho.rh41_instit                                 = rhempenhofolharubrica.rh73_instit                  \n";
     $sql .= "      left join rhempenhofolharubricaplanilha on rhempenhofolharubricaplanilha.rh111_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial              \n";
  
     $sql2 = "";
     if($dbwhere==""){
       if($k17_codigo!=null ){
         $sql2 .= " where rhempenhofolharubrica.rh73_sequencial = $rh73_sequencial "; 
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
