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

//MODULO: fiscal
//CLASSE DA ENTIDADE aidofcanc
class cl_aidofcanc { 
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
   var $y03_codigo = 0; 
   var $y03_aidof = 0; 
   var $y03_data_dia = null; 
   var $y03_data_mes = null; 
   var $y03_data_ano = null; 
   var $y03_data = null; 
   var $y03_usuario = 0; 
   var $y03_obs = null; 
   var $y03_tipocanc = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y03_codigo = int4 = Código Sequencial 
                 y03_aidof = int4 = Código do Aidof 
                 y03_data = date = Data Cancelamento 
                 y03_usuario = int4 = Cod. Usuário 
                 y03_obs = text = Observação 
                 y03_tipocanc = bool = Cancelamento 
                 ";
   //funcao construtor da classe 
   function cl_aidofcanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aidofcanc"); 
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
       $this->y03_codigo = ($this->y03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_codigo"]:$this->y03_codigo);
       $this->y03_aidof = ($this->y03_aidof == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_aidof"]:$this->y03_aidof);
       if($this->y03_data == ""){
         $this->y03_data_dia = ($this->y03_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_data_dia"]:$this->y03_data_dia);
         $this->y03_data_mes = ($this->y03_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_data_mes"]:$this->y03_data_mes);
         $this->y03_data_ano = ($this->y03_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_data_ano"]:$this->y03_data_ano);
         if($this->y03_data_dia != ""){
            $this->y03_data = $this->y03_data_ano."-".$this->y03_data_mes."-".$this->y03_data_dia;
         }
       }
       $this->y03_usuario = ($this->y03_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_usuario"]:$this->y03_usuario);
       $this->y03_obs = ($this->y03_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_obs"]:$this->y03_obs);
       $this->y03_tipocanc = ($this->y03_tipocanc == "f"?@$GLOBALS["HTTP_POST_VARS"]["y03_tipocanc"]:$this->y03_tipocanc);
     }else{
       $this->y03_codigo = ($this->y03_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y03_codigo"]:$this->y03_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($y03_codigo){ 
      $this->atualizacampos();
     if($this->y03_aidof == null ){ 
       $this->erro_sql = " Campo Código do Aidof nao Informado.";
       $this->erro_campo = "y03_aidof";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y03_data == null ){ 
       $this->erro_sql = " Campo Data Cancelamento nao Informado.";
       $this->erro_campo = "y03_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y03_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "y03_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y03_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "y03_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y03_tipocanc == null ){ 
       $this->erro_sql = " Campo Cancelamento nao Informado.";
       $this->erro_campo = "y03_tipocanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y03_codigo == "" || $y03_codigo == null ){
       $result = db_query("select nextval('aidofcanc_y03_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aidofcanc_y03_codigo_seq do campo: y03_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y03_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aidofcanc_y03_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y03_codigo)){
         $this->erro_sql = " Campo y03_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y03_codigo = $y03_codigo; 
       }
     }
     if(($this->y03_codigo == null) || ($this->y03_codigo == "") ){ 
       $this->erro_sql = " Campo y03_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aidofcanc(
                                       y03_codigo 
                                      ,y03_aidof 
                                      ,y03_data 
                                      ,y03_usuario 
                                      ,y03_obs 
                                      ,y03_tipocanc 
                       )
                values (
                                $this->y03_codigo 
                               ,$this->y03_aidof 
                               ,".($this->y03_data == "null" || $this->y03_data == ""?"null":"'".$this->y03_data."'")." 
                               ,$this->y03_usuario 
                               ,'$this->y03_obs' 
                               ,'$this->y03_tipocanc' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento de aidof ($this->y03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento de aidof já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento de aidof ($this->y03_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y03_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y03_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9107,'$this->y03_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1559,9107,'','".AddSlashes(pg_result($resaco,0,'y03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1559,9108,'','".AddSlashes(pg_result($resaco,0,'y03_aidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1559,9109,'','".AddSlashes(pg_result($resaco,0,'y03_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1559,9110,'','".AddSlashes(pg_result($resaco,0,'y03_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1559,9111,'','".AddSlashes(pg_result($resaco,0,'y03_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1559,9113,'','".AddSlashes(pg_result($resaco,0,'y03_tipocanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y03_codigo=null) { 
      $this->atualizacampos();
     $sql = " update aidofcanc set ";
     $virgula = "";
     if(trim($this->y03_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_codigo"])){ 
       $sql  .= $virgula." y03_codigo = $this->y03_codigo ";
       $virgula = ",";
       if(trim($this->y03_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "y03_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y03_aidof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_aidof"])){ 
       $sql  .= $virgula." y03_aidof = $this->y03_aidof ";
       $virgula = ",";
       if(trim($this->y03_aidof) == null ){ 
         $this->erro_sql = " Campo Código do Aidof nao Informado.";
         $this->erro_campo = "y03_aidof";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y03_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y03_data_dia"] !="") ){ 
       $sql  .= $virgula." y03_data = '$this->y03_data' ";
       $virgula = ",";
       if(trim($this->y03_data) == null ){ 
         $this->erro_sql = " Campo Data Cancelamento nao Informado.";
         $this->erro_campo = "y03_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y03_data_dia"])){ 
         $sql  .= $virgula." y03_data = null ";
         $virgula = ",";
         if(trim($this->y03_data) == null ){ 
           $this->erro_sql = " Campo Data Cancelamento nao Informado.";
           $this->erro_campo = "y03_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y03_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_usuario"])){ 
       $sql  .= $virgula." y03_usuario = $this->y03_usuario ";
       $virgula = ",";
       if(trim($this->y03_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y03_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y03_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_obs"])){ 
       $sql  .= $virgula." y03_obs = '$this->y03_obs' ";
       $virgula = ",";
       if(trim($this->y03_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "y03_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y03_tipocanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y03_tipocanc"])){ 
       $sql  .= $virgula." y03_tipocanc = '$this->y03_tipocanc' ";
       $virgula = ",";
       if(trim($this->y03_tipocanc) == null ){ 
         $this->erro_sql = " Campo Cancelamento nao Informado.";
         $this->erro_campo = "y03_tipocanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y03_codigo!=null){
       $sql .= " y03_codigo = $this->y03_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y03_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9107,'$this->y03_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1559,9107,'".AddSlashes(pg_result($resaco,$conresaco,'y03_codigo'))."','$this->y03_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_aidof"]))
           $resac = db_query("insert into db_acount values($acount,1559,9108,'".AddSlashes(pg_result($resaco,$conresaco,'y03_aidof'))."','$this->y03_aidof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_data"]))
           $resac = db_query("insert into db_acount values($acount,1559,9109,'".AddSlashes(pg_result($resaco,$conresaco,'y03_data'))."','$this->y03_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1559,9110,'".AddSlashes(pg_result($resaco,$conresaco,'y03_usuario'))."','$this->y03_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_obs"]))
           $resac = db_query("insert into db_acount values($acount,1559,9111,'".AddSlashes(pg_result($resaco,$conresaco,'y03_obs'))."','$this->y03_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y03_tipocanc"]))
           $resac = db_query("insert into db_acount values($acount,1559,9113,'".AddSlashes(pg_result($resaco,$conresaco,'y03_tipocanc'))."','$this->y03_tipocanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento de aidof nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento de aidof nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y03_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y03_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9107,'$y03_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1559,9107,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1559,9108,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_aidof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1559,9109,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1559,9110,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1559,9111,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1559,9113,'','".AddSlashes(pg_result($resaco,$iresaco,'y03_tipocanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aidofcanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y03_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y03_codigo = $y03_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento de aidof nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y03_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento de aidof nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y03_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y03_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aidofcanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aidofcanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aidofcanc.y03_usuario";
     $sql .= "      inner join aidof  on  aidof.y08_codigo = aidofcanc.y03_aidof";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = aidof.y08_inscr";
     $sql .= "      inner join db_usuarios a on  a.id_usuario = aidof.y08_login";
     $sql .= "      inner join notasiss  on  notasiss.q09_codigo = aidof.y08_nota";
     $sql .= "      inner join graficas  on  graficas.y20_grafica = aidof.y08_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y03_codigo!=null ){
         $sql2 .= " where aidofcanc.y03_codigo = $y03_codigo "; 
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
   function sql_query_file ( $y03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aidofcanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($y03_codigo!=null ){
         $sql2 .= " where aidofcanc.y03_codigo = $y03_codigo "; 
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
   function sql_query_nome ( $y03_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aidofcanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aidofcanc.y03_usuario";
     $sql .= "      inner join aidof  on  aidof.y08_codigo = aidofcanc.y03_aidof";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = aidof.y08_inscr";
     $sql .= "      inner join cgm on z01_numcgm = q02_numcgm";
     $sql .= "      inner join db_usuarios a on  a.id_usuario = aidof.y08_login";
     $sql .= "      inner join notasiss  on  notasiss.q09_codigo = aidof.y08_nota";
     $sql .= "      inner join graficas  on  graficas.y20_grafica = aidof.y08_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y03_codigo!=null ){
         $sql2 .= " where aidofcanc.y03_codigo = $y03_codigo "; 
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