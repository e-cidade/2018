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
//CLASSE DA ENTIDADE rhpagocor
class cl_rhpagocor { 
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
   var $rh58_codigo = 0; 
   var $rh58_seq = 0; 
   var $rh58_tipoocor = 0; 
   var $rh58_valor = 0; 
   var $rh58_obs = null; 
   var $rh58_data_dia = null; 
   var $rh58_data_mes = null; 
   var $rh58_data_ano = null; 
   var $rh58_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh58_codigo = int8 = Código da ocorrência 
                 rh58_seq = int8 = Sequencial 
                 rh58_tipoocor = int4 = Tipo de Ocorrência 
                 rh58_valor = float8 = Valor 
                 rh58_obs = text = Observação 
                 rh58_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_rhpagocor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpagocor"); 
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
       $this->rh58_codigo = ($this->rh58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_codigo"]:$this->rh58_codigo);
       $this->rh58_seq = ($this->rh58_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_seq"]:$this->rh58_seq);
       $this->rh58_tipoocor = ($this->rh58_tipoocor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_tipoocor"]:$this->rh58_tipoocor);
       $this->rh58_valor = ($this->rh58_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_valor"]:$this->rh58_valor);
       $this->rh58_obs = ($this->rh58_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_obs"]:$this->rh58_obs);
       if($this->rh58_data == ""){
         $this->rh58_data_dia = ($this->rh58_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_data_dia"]:$this->rh58_data_dia);
         $this->rh58_data_mes = ($this->rh58_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_data_mes"]:$this->rh58_data_mes);
         $this->rh58_data_ano = ($this->rh58_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_data_ano"]:$this->rh58_data_ano);
         if($this->rh58_data_dia != ""){
            $this->rh58_data = $this->rh58_data_ano."-".$this->rh58_data_mes."-".$this->rh58_data_dia;
         }
       }
     }else{
       $this->rh58_codigo = ($this->rh58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh58_codigo"]:$this->rh58_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh58_codigo){ 
      $this->atualizacampos();
     if($this->rh58_seq == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "rh58_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh58_tipoocor == null ){ 
       $this->erro_sql = " Campo Tipo de Ocorrência nao Informado.";
       $this->erro_campo = "rh58_tipoocor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh58_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh58_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
  if($this->rh58_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "rh58_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh58_codigo == "" || $rh58_codigo == null ){
       $result = db_query("select nextval('rhpagocor_rh58_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpagocor_rh58_codigo_seq do campo: rh58_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh58_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpagocor_rh58_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh58_codigo)){
         $this->erro_sql = " Campo rh58_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh58_codigo = $rh58_codigo; 
       }
     }
     if(($this->rh58_codigo == null) || ($this->rh58_codigo == "") ){ 
       $this->erro_sql = " Campo rh58_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpagocor(
                                       rh58_codigo 
                                      ,rh58_seq 
                                      ,rh58_tipoocor 
                                      ,rh58_valor 
                                      ,rh58_obs 
                                      ,rh58_data 
                       )
                values (
                                $this->rh58_codigo 
                               ,$this->rh58_seq 
                               ,$this->rh58_tipoocor 
                               ,$this->rh58_valor 
                               ,'$this->rh58_obs' 
                               ,".($this->rh58_data == "null" || $this->rh58_data == ""?"null":"'".$this->rh58_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ocorrências de pagamentos ($this->rh58_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ocorrências de pagamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ocorrências de pagamentos ($this->rh58_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh58_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh58_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9047,'$this->rh58_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1546,9047,'','".AddSlashes(pg_result($resaco,0,'rh58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1546,9031,'','".AddSlashes(pg_result($resaco,0,'rh58_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1546,9032,'','".AddSlashes(pg_result($resaco,0,'rh58_tipoocor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1546,9033,'','".AddSlashes(pg_result($resaco,0,'rh58_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1546,9034,'','".AddSlashes(pg_result($resaco,0,'rh58_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1546,9035,'','".AddSlashes(pg_result($resaco,0,'rh58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh58_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhpagocor set ";
     $virgula = "";
     if(trim($this->rh58_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_codigo"])){ 
       $sql  .= $virgula." rh58_codigo = $this->rh58_codigo ";
       $virgula = ",";
       if(trim($this->rh58_codigo) == null ){ 
         $this->erro_sql = " Campo Código da ocorrência nao Informado.";
         $this->erro_campo = "rh58_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh58_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_seq"])){ 
       $sql  .= $virgula." rh58_seq = $this->rh58_seq ";
       $virgula = ",";
       if(trim($this->rh58_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh58_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh58_tipoocor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_tipoocor"])){ 
       $sql  .= $virgula." rh58_tipoocor = $this->rh58_tipoocor ";
       $virgula = ",";
       if(trim($this->rh58_tipoocor) == null ){ 
         $this->erro_sql = " Campo Tipo de Ocorrência nao Informado.";
         $this->erro_campo = "rh58_tipoocor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh58_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_valor"])){ 
       $sql  .= $virgula." rh58_valor = $this->rh58_valor ";
       $virgula = ",";
       if(trim($this->rh58_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh58_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh58_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_obs"])){ 
       $sql  .= $virgula." rh58_obs = '$this->rh58_obs' ";
       $virgula = ",";
  }
     if(trim($this->rh58_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh58_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh58_data_dia"] !="") ){ 
       $sql  .= $virgula." rh58_data = '$this->rh58_data' ";
       $virgula = ",";
       if(trim($this->rh58_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "rh58_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_data_dia"])){ 
         $sql  .= $virgula." rh58_data = null ";
         $virgula = ",";
         if(trim($this->rh58_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "rh58_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($rh58_codigo!=null){
       $sql .= " rh58_codigo = $this->rh58_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh58_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9047,'$this->rh58_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1546,9047,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_codigo'))."','$this->rh58_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_seq"]))
           $resac = db_query("insert into db_acount values($acount,1546,9031,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_seq'))."','$this->rh58_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_tipoocor"]))
           $resac = db_query("insert into db_acount values($acount,1546,9032,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_tipoocor'))."','$this->rh58_tipoocor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_valor"]))
           $resac = db_query("insert into db_acount values($acount,1546,9033,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_valor'))."','$this->rh58_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_obs"]))
           $resac = db_query("insert into db_acount values($acount,1546,9034,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_obs'))."','$this->rh58_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh58_data"]))
           $resac = db_query("insert into db_acount values($acount,1546,9035,'".AddSlashes(pg_result($resaco,$conresaco,'rh58_data'))."','$this->rh58_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ocorrências de pagamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh58_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ocorrências de pagamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh58_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh58_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9047,'$rh58_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1546,9047,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1546,9031,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1546,9032,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_tipoocor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1546,9033,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1546,9034,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1546,9035,'','".AddSlashes(pg_result($resaco,$iresaco,'rh58_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpagocor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh58_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh58_codigo = $rh58_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ocorrências de pagamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh58_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ocorrências de pagamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh58_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh58_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpagocor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagocor ";
     $sql .= "      inner join rhpagtipoocor  on  rhpagtipoocor.rh59_codigo = rhpagocor.rh58_tipoocor";
     $sql2 = "";
     if($dbwhere==""){
       if($rh58_codigo!=null ){
         $sql2 .= " where rhpagocor.rh58_codigo = $rh58_codigo "; 
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
   function sql_query_file ( $rh58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagocor ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh58_codigo!=null ){
         $sql2 .= " where rhpagocor.rh58_codigo = $rh58_codigo "; 
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
   function sql_query_atraso ( $rh58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagocor ";
     $sql .= "      inner join rhpagatra      on rhpagatra.rh57_seq        = rhpagocor.rh58_seq ";
     $sql .= "      inner join rhpagtipoocor  on rhpagtipoocor.rh59_codigo = rhpagocor.rh58_tipoocor ";
     $sql .= "      inner join rhpessoal      on rhpessoal.rh01_regist     = rhpagatra.rh57_regist ";
     $sql .= "      inner join cgm            on cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh58_codigo!=null ){
         $sql2 .= " where rhpagocor.rh58_codigo = $rh58_codigo "; 
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
   function sql_query_rhpagatra ( $rh58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagocor ";
     $sql .= "      inner join rhpagatra    on rhpagatra.rh57_seq       = rhpagocor.rh58_seq ";
     $sql .= "      left  join rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh58_codigo!=null ){
         $sql2 .= " where rhpagocor.rh58_codigo = $rh58_codigo "; 
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
   function sql_query_notjustica ( $rh58_codigo=null,$campos="*",$ordem=null,$dbwhere="",$usarleft=false,$tipoocor="",$data_pagamento="",$ano="",$mes=""){
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
     $sql .= " from rhpagocor ";
     $sql .= "      inner join rhpagatra on rhpagatra.rh57_seq = rhpagocor.rh58_seq ";
     if($usarleft == true){
       if(trim($tipoocor) == "" || $tipoocor == null){
	 $sql .= "      left join (
                                   select distinct rh61_regist
                                   from rhpesjustica
                                   where (
																	        '".date("Y-m-d",db_getsession("DB_datausu"))."' between rh61_dataini and rh61_datafim
                                          or rh61_datafim is null 
																				 )
                                  ) rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist ";
       }else{
         $sql .= "      left  join (
                                    select distinct rh61_regist 
                                    from rhpesjustica
                                         inner join rhpagatra on rhpagatra.rh57_regist = rhpesjustica.rh61_regist
                                         inner join rhpagocor on rhpagocor.rh58_seq    = rhpagatra.rh57_seq
                                    where (
                                           '".date("Y-m-d",db_getsession("DB_datausu"))."' between rh61_dataini and rh61_datafim
                                           or rh61_datafim is null
                                          )
                                      and rh58_tipoocor = ".$tipoocor."
                                      and (rh58_data = '".$data_pagamento."')
                                   ) rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist
                 ";
       }
     }
     if(trim($ano) != "" && trim($mes) != ""){
       $sql .= "      inner join rhpagtipoocor  on rhpagtipoocor.rh59_codigo = rhpagocor.rh58_tipoocor ";
       $sql .= "      inner join rhpessoal      on rhpessoal.rh01_regist     = rhpagatra.rh57_regist ";
       $sql .= "      left  join rhpessoalmov   on rhpessoalmov.rh02_anousu  = ".$ano.
                                             " and rhpessoalmov.rh02_mesusu  = ".$mes.
                                             " and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist ";
       $sql .= "      left  join rhpesbanco     on rhpesbanco.rh44_seqpes    = rhpessoalmov.rh02_seqpes "; 
       $sql .= "      left  join cgm            on cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
       $sql .= "      left  join rhregime       on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg ";
       $sql .= "      left  join rhpespadrao    on rhpespadrao.rh03_seqpes   = rhpessoalmov.rh02_seqpes ";
       $sql .= "      left  join rhfuncao       on rhfuncao.rh37_funcao      = rhpessoal.rh01_funcao ";
     }
     $sql2 = "";
     if($dbwhere==""){
       if($rh58_codigo!=null ){
         $sql2 .= " where rhpagocor.rh58_codigo = $rh58_codigo "; 
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