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

//MODULO: compras
//CLASSE DA ENTIDADE solicita
class cl_solicita { 
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
   var $pc10_numero = 0; 
   var $pc10_data_dia = null; 
   var $pc10_data_mes = null; 
   var $pc10_data_ano = null; 
   var $pc10_data = null; 
   var $pc10_resumo = null; 
   var $pc10_depto = 0; 
   var $pc10_log = 0; 
   var $pc10_instit = 0; 
   var $pc10_correto = 'f'; 
   var $pc10_login = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc10_numero = int4 = numero da solicitacao 
                 pc10_data = date = data da solicitacao 
                 pc10_resumo = text = Resumo da solicitacao 
                 pc10_depto = int4 = Departamento 
                 pc10_log = int4 = log 
                 pc10_instit = int4 = Instituição 
                 pc10_correto = bool = Correto 
                 pc10_login = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_solicita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicita"); 
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
       $this->pc10_numero = ($this->pc10_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_numero"]:$this->pc10_numero);
       if($this->pc10_data == ""){
         $this->pc10_data_dia = ($this->pc10_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_data_dia"]:$this->pc10_data_dia);
         $this->pc10_data_mes = ($this->pc10_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_data_mes"]:$this->pc10_data_mes);
         $this->pc10_data_ano = ($this->pc10_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_data_ano"]:$this->pc10_data_ano);
         if($this->pc10_data_dia != ""){
            $this->pc10_data = $this->pc10_data_ano."-".$this->pc10_data_mes."-".$this->pc10_data_dia;
         }
       }
       $this->pc10_resumo = ($this->pc10_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_resumo"]:$this->pc10_resumo);
       $this->pc10_depto = ($this->pc10_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_depto"]:$this->pc10_depto);
       $this->pc10_log = ($this->pc10_log == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_log"]:$this->pc10_log);
       $this->pc10_instit = ($this->pc10_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_instit"]:$this->pc10_instit);
       $this->pc10_correto = ($this->pc10_correto == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc10_correto"]:$this->pc10_correto);
       $this->pc10_login = ($this->pc10_login == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_login"]:$this->pc10_login);
     }else{
       $this->pc10_numero = ($this->pc10_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["pc10_numero"]:$this->pc10_numero);
     }
   }
   // funcao para inclusao
   function incluir ($pc10_numero){ 
      $this->atualizacampos();
     if($this->pc10_data == null ){ 
       $this->erro_sql = " Campo data da solicitacao nao Informado.";
       $this->erro_campo = "pc10_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc10_depto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "pc10_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc10_log == null ){ 
       $this->pc10_log = "0";
     }
     if($this->pc10_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "pc10_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc10_correto == null ){ 
       $this->erro_sql = " Campo Correto nao Informado.";
       $this->erro_campo = "pc10_correto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc10_login == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "pc10_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc10_numero == "" || $pc10_numero == null ){
       $result = @pg_query("select nextval('solicita_pc10_numero_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicita_pc10_numero_seq do campo: pc10_numero"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc10_numero = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from solicita_pc10_numero_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc10_numero)){
         $this->erro_sql = " Campo pc10_numero maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc10_numero = $pc10_numero; 
       }
     }
     if(($this->pc10_numero == null) || ($this->pc10_numero == "") ){ 
       $this->erro_sql = " Campo pc10_numero nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicita(
                                       pc10_numero 
                                      ,pc10_data 
                                      ,pc10_resumo 
                                      ,pc10_depto 
                                      ,pc10_log 
                                      ,pc10_instit 
                                      ,pc10_correto 
                                      ,pc10_login 
                       )
                values (
                                $this->pc10_numero 
                               ,".($this->pc10_data == "null" || $this->pc10_data == ""?"null":"'".$this->pc10_data."'")." 
                               ,'$this->pc10_resumo' 
                               ,$this->pc10_depto 
                               ,$this->pc10_log 
                               ,$this->pc10_instit 
                               ,'$this->pc10_correto' 
                               ,$this->pc10_login 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitacao de Compras ($this->pc10_numero) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitacao de Compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitacao de Compras ($this->pc10_numero) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc10_numero;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc10_numero));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,5542,'$this->pc10_numero','I')");
       $resac = pg_query("insert into db_acount values($acount,869,5542,'','".AddSlashes(pg_result($resaco,0,'pc10_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,5541,'','".AddSlashes(pg_result($resaco,0,'pc10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,5544,'','".AddSlashes(pg_result($resaco,0,'pc10_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,5545,'','".AddSlashes(pg_result($resaco,0,'pc10_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,5546,'','".AddSlashes(pg_result($resaco,0,'pc10_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,6333,'','".AddSlashes(pg_result($resaco,0,'pc10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,6617,'','".AddSlashes(pg_result($resaco,0,'pc10_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,869,6759,'','".AddSlashes(pg_result($resaco,0,'pc10_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc10_numero=null) { 
      $this->atualizacampos();
     $sql = " update solicita set ";
     $virgula = "";
     if(trim($this->pc10_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_numero"])){ 
       $sql  .= $virgula." pc10_numero = $this->pc10_numero ";
       $virgula = ",";
       if(trim($this->pc10_numero) == null ){ 
         $this->erro_sql = " Campo numero da solicitacao nao Informado.";
         $this->erro_campo = "pc10_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc10_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc10_data_dia"] !="") ){ 
       $sql  .= $virgula." pc10_data = '$this->pc10_data' ";
       $virgula = ",";
       if(trim($this->pc10_data) == null ){ 
         $this->erro_sql = " Campo data da solicitacao nao Informado.";
         $this->erro_campo = "pc10_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_data_dia"])){ 
         $sql  .= $virgula." pc10_data = null ";
         $virgula = ",";
         if(trim($this->pc10_data) == null ){ 
           $this->erro_sql = " Campo data da solicitacao nao Informado.";
           $this->erro_campo = "pc10_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc10_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_resumo"])){ 
       $sql  .= $virgula." pc10_resumo = '$this->pc10_resumo' ";
       $virgula = ",";
     }
     if(trim($this->pc10_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_depto"])){ 
       $sql  .= $virgula." pc10_depto = $this->pc10_depto ";
       $virgula = ",";
       if(trim($this->pc10_depto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "pc10_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc10_log)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_log"])){ 
        if(trim($this->pc10_log)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc10_log"])){ 
           $this->pc10_log = "0" ; 
        } 
       $sql  .= $virgula." pc10_log = $this->pc10_log ";
       $virgula = ",";
     }
     if(trim($this->pc10_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_instit"])){ 
       $sql  .= $virgula." pc10_instit = $this->pc10_instit ";
       $virgula = ",";
       if(trim($this->pc10_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "pc10_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc10_correto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_correto"])){ 
       $sql  .= $virgula." pc10_correto = '$this->pc10_correto' ";
       $virgula = ",";
       if(trim($this->pc10_correto) == null ){ 
         $this->erro_sql = " Campo Correto nao Informado.";
         $this->erro_campo = "pc10_correto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc10_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc10_login"])){ 
       $sql  .= $virgula." pc10_login = $this->pc10_login ";
       $virgula = ",";
       if(trim($this->pc10_login) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "pc10_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc10_numero!=null){
       $sql .= " pc10_numero = $this->pc10_numero";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc10_numero));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5542,'$this->pc10_numero','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_numero"]))
           $resac = pg_query("insert into db_acount values($acount,869,5542,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_numero'))."','$this->pc10_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_data"]))
           $resac = pg_query("insert into db_acount values($acount,869,5541,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_data'))."','$this->pc10_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_resumo"]))
           $resac = pg_query("insert into db_acount values($acount,869,5544,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_resumo'))."','$this->pc10_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_depto"]))
           $resac = pg_query("insert into db_acount values($acount,869,5545,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_depto'))."','$this->pc10_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_log"]))
           $resac = pg_query("insert into db_acount values($acount,869,5546,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_log'))."','$this->pc10_log',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_instit"]))
           $resac = pg_query("insert into db_acount values($acount,869,6333,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_instit'))."','$this->pc10_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_correto"]))
           $resac = pg_query("insert into db_acount values($acount,869,6617,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_correto'))."','$this->pc10_correto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc10_login"]))
           $resac = pg_query("insert into db_acount values($acount,869,6759,'".AddSlashes(pg_result($resaco,$conresaco,'pc10_login'))."','$this->pc10_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitacao de Compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc10_numero;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitacao de Compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc10_numero;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc10_numero;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc10_numero=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc10_numero));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,5542,'$pc10_numero','E')");
         $resac = pg_query("insert into db_acount values($acount,869,5542,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,5541,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,5544,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,5545,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,5546,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,6333,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,6617,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_correto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,869,6759,'','".AddSlashes(pg_result($resaco,$iresaco,'pc10_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc10_numero != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc10_numero = $pc10_numero ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitacao de Compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc10_numero;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitacao de Compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc10_numero;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc10_numero;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:solicita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicita ";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero "; 
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
   function sql_query_file ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicita ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero "; 
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
   function sql_query_func ($pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicita ";
     $sql .= "      left join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
     $sql .= "      left join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
     $sql .= "      inner join solicitem on solicitem.pc11_numero = solicita.pc10_numero";
     $sql .= "      left  join pcdotac on pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      inner join db_depart on db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner join pcproc on pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join pcorcamitemproc on pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcorcamitem on pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
     $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero ";
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
   function sql_query_cons ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicita ";
     $sql .= "      left join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
     $sql .= "      left join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
     $sql .= "      inner join solicitem on solicitem.pc11_numero = solicita.pc10_numero";
     $sql .= "      left join solicitemunid on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left join matunid on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join pcdotac on pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      inner join db_depart on db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      left  join pcsugforn on pcsugforn.pc40_solic = solicita.pc10_numero";
     $sql .= "      left  join cgm on pcsugforn.pc40_numcgm = cgm.z01_numcgm";
     $sql .= "      left  join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcproc on pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      left  join pcorcamitemsol on pcorcamitemsol.pc29_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcorcamitemproc on pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem";
     $sql .= "      left  join pcorcamitem a on a.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      left  join pcorcamitem c on c.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
     $sql .= "      left  join pcorcam b on b.pc20_codorc = a.pc22_codorc";
     $sql .= "      left  join pcorcam d on d.pc20_codorc = c.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero ";
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
   function sql_query_tipo ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicita ";
     $sql .= "      left join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
     $sql .= "      left join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
     $sql .= "      inner join db_depart on db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario = solicita.pc10_login";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero ";
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
   function sql_query_rel ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicita ";
     $sql .= "      left join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
     $sql .= "      left join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
     $sql .= "      inner join solicitem on solicitem.pc11_numero = solicita.pc10_numero";
     $sql .= "      left  join pcdotac on pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      inner join db_depart on db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join pcsugforn on pcsugforn.pc40_solic = solicita.pc10_numero";
     $sql .= "      left  join cgm on pcsugforn.pc40_numcgm = cgm.z01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero ";
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
   function sql_query_solicita ( $pc10_numero=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicita ";
     $sql .= "      left join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
     $sql .= "      inner join db_depart on db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc10_numero!=null ){
         $sql2 .= " where solicita.pc10_numero = $pc10_numero ";
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