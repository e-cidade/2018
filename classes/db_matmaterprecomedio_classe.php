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

//MODULO: material
//CLASSE DA ENTIDADE matmaterprecomedio
class cl_matmaterprecomedio { 
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
   var $m85_sequencial = 0; 
   var $m85_matmater = 0; 
   var $m85_instit = 0; 
   var $m85_precomedio = 0; 
   var $m85_hora = null; 
   var $m85_data_dia = null; 
   var $m85_data_mes = null; 
   var $m85_data_ano = null; 
   var $m85_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m85_sequencial = int4 = Sequencial 
                 m85_matmater = int4 = Código do Material 
                 m85_instit = int4 = Instit 
                 m85_precomedio = float4 = Último Preço Médio 
                 m85_hora = varchar(5) = Hora 
                 m85_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_matmaterprecomedio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmaterprecomedio"); 
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
       $this->m85_sequencial = ($this->m85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_sequencial"]:$this->m85_sequencial);
       $this->m85_matmater = ($this->m85_matmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_matmater"]:$this->m85_matmater);
       $this->m85_instit = ($this->m85_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_instit"]:$this->m85_instit);
       $this->m85_precomedio = ($this->m85_precomedio == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_precomedio"]:$this->m85_precomedio);
       $this->m85_hora = ($this->m85_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_hora"]:$this->m85_hora);
       if($this->m85_data == ""){
         $this->m85_data_dia = ($this->m85_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_data_dia"]:$this->m85_data_dia);
         $this->m85_data_mes = ($this->m85_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_data_mes"]:$this->m85_data_mes);
         $this->m85_data_ano = ($this->m85_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_data_ano"]:$this->m85_data_ano);
         if($this->m85_data_dia != ""){
            $this->m85_data = $this->m85_data_ano."-".$this->m85_data_mes."-".$this->m85_data_dia;
         }
       }
     }else{
       $this->m85_sequencial = ($this->m85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m85_sequencial"]:$this->m85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m85_sequencial){ 
      $this->atualizacampos();
     if($this->m85_matmater == null ){ 
       $this->erro_sql = " Campo Código do Material nao Informado.";
       $this->erro_campo = "m85_matmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m85_instit == null ){ 
       $this->erro_sql = " Campo Instit nao Informado.";
       $this->erro_campo = "m85_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m85_precomedio == null ){ 
       $this->erro_sql = " Campo Último Preço Médio nao Informado.";
       $this->erro_campo = "m85_precomedio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m85_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m85_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m85_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "m85_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m85_sequencial == "" || $m85_sequencial == null ){
       $result = db_query("select nextval('matmaterprecomedio_m85_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matmaterprecomedio_m85_sequencial_seq do campo: m85_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m85_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matmaterprecomedio_m85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m85_sequencial)){
         $this->erro_sql = " Campo m85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m85_sequencial = $m85_sequencial; 
       }
     }
     if(($this->m85_sequencial == null) || ($this->m85_sequencial == "") ){ 
       $this->erro_sql = " Campo m85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmaterprecomedio(
                                       m85_sequencial 
                                      ,m85_matmater 
                                      ,m85_instit 
                                      ,m85_precomedio 
                                      ,m85_hora 
                                      ,m85_data 
                       )
                values (
                                $this->m85_sequencial 
                               ,$this->m85_matmater 
                               ,$this->m85_instit 
                               ,$this->m85_precomedio 
                               ,'$this->m85_hora' 
                               ,".($this->m85_data == "null" || $this->m85_data == ""?"null":"'".$this->m85_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Material Preço Médio ($this->m85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Material Preço Médio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Material Preço Médio ($this->m85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17928,'$this->m85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3169,17928,'','".AddSlashes(pg_result($resaco,0,'m85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3169,17929,'','".AddSlashes(pg_result($resaco,0,'m85_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3169,17931,'','".AddSlashes(pg_result($resaco,0,'m85_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3169,17932,'','".AddSlashes(pg_result($resaco,0,'m85_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3169,17937,'','".AddSlashes(pg_result($resaco,0,'m85_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3169,17938,'','".AddSlashes(pg_result($resaco,0,'m85_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m85_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matmaterprecomedio set ";
     $virgula = "";
     if(trim($this->m85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_sequencial"])){ 
       $sql  .= $virgula." m85_sequencial = $this->m85_sequencial ";
       $virgula = ",";
       if(trim($this->m85_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m85_matmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_matmater"])){ 
       $sql  .= $virgula." m85_matmater = $this->m85_matmater ";
       $virgula = ",";
       if(trim($this->m85_matmater) == null ){ 
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "m85_matmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m85_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_instit"])){ 
       $sql  .= $virgula." m85_instit = $this->m85_instit ";
       $virgula = ",";
       if(trim($this->m85_instit) == null ){ 
         $this->erro_sql = " Campo Instit nao Informado.";
         $this->erro_campo = "m85_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m85_precomedio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_precomedio"])){ 
       $sql  .= $virgula." m85_precomedio = $this->m85_precomedio ";
       $virgula = ",";
       if(trim($this->m85_precomedio) == null ){ 
         $this->erro_sql = " Campo Último Preço Médio nao Informado.";
         $this->erro_campo = "m85_precomedio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m85_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_hora"])){ 
       $sql  .= $virgula." m85_hora = '$this->m85_hora' ";
       $virgula = ",";
       if(trim($this->m85_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m85_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m85_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m85_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m85_data_dia"] !="") ){ 
       $sql  .= $virgula." m85_data = '$this->m85_data' ";
       $virgula = ",";
       if(trim($this->m85_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "m85_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m85_data_dia"])){ 
         $sql  .= $virgula." m85_data = null ";
         $virgula = ",";
         if(trim($this->m85_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "m85_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($m85_sequencial!=null){
       $sql .= " m85_sequencial = $this->m85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17928,'$this->m85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_sequencial"]) || $this->m85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3169,17928,'".AddSlashes(pg_result($resaco,$conresaco,'m85_sequencial'))."','$this->m85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_matmater"]) || $this->m85_matmater != "")
           $resac = db_query("insert into db_acount values($acount,3169,17929,'".AddSlashes(pg_result($resaco,$conresaco,'m85_matmater'))."','$this->m85_matmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_instit"]) || $this->m85_instit != "")
           $resac = db_query("insert into db_acount values($acount,3169,17931,'".AddSlashes(pg_result($resaco,$conresaco,'m85_instit'))."','$this->m85_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_precomedio"]) || $this->m85_precomedio != "")
           $resac = db_query("insert into db_acount values($acount,3169,17932,'".AddSlashes(pg_result($resaco,$conresaco,'m85_precomedio'))."','$this->m85_precomedio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_hora"]) || $this->m85_hora != "")
           $resac = db_query("insert into db_acount values($acount,3169,17937,'".AddSlashes(pg_result($resaco,$conresaco,'m85_hora'))."','$this->m85_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m85_data"]) || $this->m85_data != "")
           $resac = db_query("insert into db_acount values($acount,3169,17938,'".AddSlashes(pg_result($resaco,$conresaco,'m85_data'))."','$this->m85_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material Preço Médio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Material Preço Médio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m85_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m85_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17928,'$m85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3169,17928,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3169,17929,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_matmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3169,17931,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3169,17932,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3169,17937,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3169,17938,'','".AddSlashes(pg_result($resaco,$iresaco,'m85_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matmaterprecomedio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m85_sequencial = $m85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Material Preço Médio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Material Preço Médio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmaterprecomedio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterprecomedio ";
     $sql .= "      inner join db_config  on  db_config.codigo = matmaterprecomedio.m85_instit";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matmaterprecomedio.m85_matmater";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($m85_sequencial!=null ){
         $sql2 .= " where matmaterprecomedio.m85_sequencial = $m85_sequencial "; 
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
   function sql_query_file ( $m85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matmaterprecomedio ";
     $sql2 = "";
     if($dbwhere==""){
       if($m85_sequencial!=null ){
         $sql2 .= " where matmaterprecomedio.m85_sequencial = $m85_sequencial "; 
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