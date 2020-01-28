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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhipe
class cl_rhipe { 
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
   var $rh14_instit = 0; 
   var $rh14_sequencia = 0; 
   var $rh14_matipe = 0; 
   var $rh14_dtvinc_dia = null; 
   var $rh14_dtvinc_mes = null; 
   var $rh14_dtvinc_ano = null; 
   var $rh14_dtvinc = null; 
   var $rh14_estado = null; 
   var $rh14_dtalt_dia = null; 
   var $rh14_dtalt_mes = null; 
   var $rh14_dtalt_ano = null; 
   var $rh14_dtalt = null; 
   var $rh14_contrato = 0; 
   var $rh14_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh14_instit = int4 = Cod. Instituição 
                 rh14_sequencia = int4 = Sequencial 
                 rh14_matipe = int8 = Matrícula do IPE 
                 rh14_dtvinc = date = Data do Vínculo com IPE 
                 rh14_estado = varchar(2) = Situação do IPE 
                 rh14_dtalt = date = Data da Alteração 
                 rh14_contrato = int8 = Contrato 
                 rh14_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_rhipe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhipe"); 
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
       $this->rh14_instit = ($this->rh14_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_instit"]:$this->rh14_instit);
       $this->rh14_sequencia = ($this->rh14_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_sequencia"]:$this->rh14_sequencia);
       $this->rh14_matipe = ($this->rh14_matipe == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_matipe"]:$this->rh14_matipe);
       if($this->rh14_dtvinc == ""){
         $this->rh14_dtvinc_dia = ($this->rh14_dtvinc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_dia"]:$this->rh14_dtvinc_dia);
         $this->rh14_dtvinc_mes = ($this->rh14_dtvinc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_mes"]:$this->rh14_dtvinc_mes);
         $this->rh14_dtvinc_ano = ($this->rh14_dtvinc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_ano"]:$this->rh14_dtvinc_ano);
         if($this->rh14_dtvinc_dia != ""){
            $this->rh14_dtvinc = $this->rh14_dtvinc_ano."-".$this->rh14_dtvinc_mes."-".$this->rh14_dtvinc_dia;
         }
       }
       $this->rh14_estado = ($this->rh14_estado == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_estado"]:$this->rh14_estado);
       if($this->rh14_dtalt == ""){
         $this->rh14_dtalt_dia = ($this->rh14_dtalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_dia"]:$this->rh14_dtalt_dia);
         $this->rh14_dtalt_mes = ($this->rh14_dtalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_mes"]:$this->rh14_dtalt_mes);
         $this->rh14_dtalt_ano = ($this->rh14_dtalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_ano"]:$this->rh14_dtalt_ano);
         if($this->rh14_dtalt_dia != ""){
            $this->rh14_dtalt = $this->rh14_dtalt_ano."-".$this->rh14_dtalt_mes."-".$this->rh14_dtalt_dia;
         }
       }
       $this->rh14_contrato = ($this->rh14_contrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_contrato"]:$this->rh14_contrato);
       $this->rh14_valor = ($this->rh14_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_valor"]:$this->rh14_valor);
     }else{
       $this->rh14_sequencia = ($this->rh14_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh14_sequencia"]:$this->rh14_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($rh14_sequencia){ 
      $this->atualizacampos();
     if($this->rh14_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "rh14_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh14_matipe == null ){ 
       $this->rh14_matipe = "0";
     }
     if($this->rh14_dtvinc == null ){ 
       $this->rh14_dtvinc = "null";
     }
     if($this->rh14_dtalt == null ){ 
       $this->rh14_dtalt = "null";
     }
     if($this->rh14_contrato == null ){ 
       $this->erro_sql = " Campo Contrato nao Informado.";
       $this->erro_campo = "rh14_contrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh14_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh14_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh14_sequencia == "" || $rh14_sequencia == null ){
       $result = db_query("select nextval('rhipe_rh14_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhipe_rh14_sequencia_seq do campo: rh14_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh14_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhipe_rh14_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh14_sequencia)){
         $this->erro_sql = " Campo rh14_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh14_sequencia = $rh14_sequencia; 
       }
     }
     if(($this->rh14_sequencia == null) || ($this->rh14_sequencia == "") ){ 
       $this->erro_sql = " Campo rh14_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhipe(
                                       rh14_instit 
                                      ,rh14_sequencia 
                                      ,rh14_matipe 
                                      ,rh14_dtvinc 
                                      ,rh14_estado 
                                      ,rh14_dtalt 
                                      ,rh14_contrato 
                                      ,rh14_valor 
                       )
                values (
                                $this->rh14_instit 
                               ,$this->rh14_sequencia 
                               ,$this->rh14_matipe 
                               ,".($this->rh14_dtvinc == "null" || $this->rh14_dtvinc == ""?"null":"'".$this->rh14_dtvinc."'")." 
                               ,'$this->rh14_estado' 
                               ,".($this->rh14_dtalt == "null" || $this->rh14_dtalt == ""?"null":"'".$this->rh14_dtalt."'")." 
                               ,$this->rh14_contrato 
                               ,$this->rh14_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro do IPE ($this->rh14_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro do IPE já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro do IPE ($this->rh14_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh14_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh14_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9572,'$this->rh14_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1166,9907,'','".AddSlashes(pg_result($resaco,0,'rh14_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,9572,'','".AddSlashes(pg_result($resaco,0,'rh14_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,7059,'','".AddSlashes(pg_result($resaco,0,'rh14_matipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,7060,'','".AddSlashes(pg_result($resaco,0,'rh14_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,7061,'','".AddSlashes(pg_result($resaco,0,'rh14_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,7062,'','".AddSlashes(pg_result($resaco,0,'rh14_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,8868,'','".AddSlashes(pg_result($resaco,0,'rh14_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1166,9573,'','".AddSlashes(pg_result($resaco,0,'rh14_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh14_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update rhipe set ";
     $virgula = "";
     if(trim($this->rh14_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_instit"])){ 
       $sql  .= $virgula." rh14_instit = $this->rh14_instit ";
       $virgula = ",";
       if(trim($this->rh14_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh14_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh14_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_sequencia"])){ 
       $sql  .= $virgula." rh14_sequencia = $this->rh14_sequencia ";
       $virgula = ",";
       if(trim($this->rh14_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh14_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh14_matipe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_matipe"])){ 
        if(trim($this->rh14_matipe)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh14_matipe"])){ 
           $this->rh14_matipe = "0" ; 
        } 
       $sql  .= $virgula." rh14_matipe = $this->rh14_matipe ";
       $virgula = ",";
     }
     if(trim($this->rh14_dtvinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_dia"] !="") ){ 
       $sql  .= $virgula." rh14_dtvinc = '$this->rh14_dtvinc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc_dia"])){ 
         $sql  .= $virgula." rh14_dtvinc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh14_estado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_estado"])){ 
       $sql  .= $virgula." rh14_estado = '$this->rh14_estado' ";
       $virgula = ",";
     }
     if(trim($this->rh14_dtalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_dia"] !="") ){ 
       $sql  .= $virgula." rh14_dtalt = '$this->rh14_dtalt' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtalt_dia"])){ 
         $sql  .= $virgula." rh14_dtalt = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh14_contrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_contrato"])){ 
       $sql  .= $virgula." rh14_contrato = $this->rh14_contrato ";
       $virgula = ",";
       if(trim($this->rh14_contrato) == null ){ 
         $this->erro_sql = " Campo Contrato nao Informado.";
         $this->erro_campo = "rh14_contrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh14_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh14_valor"])){ 
       $sql  .= $virgula." rh14_valor = $this->rh14_valor ";
       $virgula = ",";
       if(trim($this->rh14_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh14_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh14_sequencia!=null){
       $sql .= " rh14_sequencia = $this->rh14_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh14_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9572,'$this->rh14_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_instit"]))
           $resac = db_query("insert into db_acount values($acount,1166,9907,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_instit'))."','$this->rh14_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1166,9572,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_sequencia'))."','$this->rh14_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_matipe"]))
           $resac = db_query("insert into db_acount values($acount,1166,7059,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_matipe'))."','$this->rh14_matipe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtvinc"]))
           $resac = db_query("insert into db_acount values($acount,1166,7060,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_dtvinc'))."','$this->rh14_dtvinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_estado"]))
           $resac = db_query("insert into db_acount values($acount,1166,7061,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_estado'))."','$this->rh14_estado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_dtalt"]))
           $resac = db_query("insert into db_acount values($acount,1166,7062,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_dtalt'))."','$this->rh14_dtalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_contrato"]))
           $resac = db_query("insert into db_acount values($acount,1166,8868,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_contrato'))."','$this->rh14_contrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh14_valor"]))
           $resac = db_query("insert into db_acount values($acount,1166,9573,'".AddSlashes(pg_result($resaco,$conresaco,'rh14_valor'))."','$this->rh14_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do IPE nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh14_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do IPE nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh14_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh14_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh14_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh14_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9572,'$rh14_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1166,9907,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,9572,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,7059,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_matipe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,7060,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_dtvinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,7061,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_estado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,7062,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_dtalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,8868,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_contrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1166,9573,'','".AddSlashes(pg_result($resaco,$iresaco,'rh14_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhipe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh14_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh14_sequencia = $rh14_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro do IPE nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh14_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro do IPE nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh14_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh14_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhipe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh14_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhipe ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhipe.rh14_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh14_sequencia!=null ){
         $sql2 .= " where rhipe.rh14_sequencia = $rh14_sequencia "; 
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
   function sql_query_file ( $rh14_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhipe ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh14_sequencia!=null ){
         $sql2 .= " where rhipe.rh14_sequencia = $rh14_sequencia "; 
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
   function sql_query_cons ( $rh14_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhipe
                    left join rhiperegist on rhiperegist.rh62_sequencia = rhipe.rh14_sequencia
                    left join rhipenumcgm on rhipenumcgm.rh63_sequencia = rhipe.rh14_sequencia
                    left join rhpessoal   on rhpessoal.rh01_regist = rhiperegist.rh62_regist
                    left join cgm a       on a.z01_numcgm = rhpessoal.rh01_numcgm
                    left join cgm b       on b.z01_numcgm = rhipenumcgm.rh63_numcgm
                    ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh14_sequencia!=null ){
         $sql2 .= " where rhipe.rh14_sequencia = $rh14_sequencia "; 
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
   function sql_query_ipe ( $rh14_sequencia=null,$campos="*",$ordem=null,$dbwhere="",$ano="",$mes=""){
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
     $sql .= " from rhipe
                    left join rhipenumcgm   on rhipenumcgm.rh63_sequencia = rhipe.rh14_sequencia
                    left join cgm          on cgm.z01_numcgm = rhipenumcgm.rh63_numcgm
                    left join rhiperegist   on rhiperegist.rh62_sequencia = rhipe.rh14_sequencia
                    left join rhpessoal     on rhpessoal.rh01_regist = rhiperegist.rh62_regist
                    left join rhpessoalmov  on rhpessoalmov.rh02_anousu = $ano 
             		                           and rhpessoalmov.rh02_mesusu = $mes 
					                                 and rhpessoalmov.rh02_regist = rhpessoal.rh01_regist 
																					 and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."
                    left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                    left join rhregime     on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
										                      and rhregime.rh30_instit = rhpessoalmov.rh02_instit
                    ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh14_sequencia!=null ){
         $sql2 .= " where rhipe.rh14_sequencia = $rh14_sequencia ";
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