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
//CLASSE DA ENTIDADE rhpesjustica
class cl_rhpesjustica { 
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
   var $rh61_codigo = 0; 
   var $rh61_regist = 0; 
   var $rh61_dataini_dia = null; 
   var $rh61_dataini_mes = null; 
   var $rh61_dataini_ano = null; 
   var $rh61_dataini = null; 
   var $rh61_datafim_dia = null; 
   var $rh61_datafim_mes = null; 
   var $rh61_datafim_ano = null; 
   var $rh61_datafim = null; 
   var $rh61_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh61_codigo = int4 = Código 
                 rh61_regist = int4 = Matrícula do Servidor 
                 rh61_dataini = date = Data inicial 
                 rh61_datafim = date = Data final 
                 rh61_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_rhpesjustica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpesjustica"); 
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
       $this->rh61_codigo = ($this->rh61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_codigo"]:$this->rh61_codigo);
       $this->rh61_regist = ($this->rh61_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_regist"]:$this->rh61_regist);
       if($this->rh61_dataini == ""){
         $this->rh61_dataini_dia = ($this->rh61_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_dataini_dia"]:$this->rh61_dataini_dia);
         $this->rh61_dataini_mes = ($this->rh61_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_dataini_mes"]:$this->rh61_dataini_mes);
         $this->rh61_dataini_ano = ($this->rh61_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_dataini_ano"]:$this->rh61_dataini_ano);
         if($this->rh61_dataini_dia != ""){
            $this->rh61_dataini = $this->rh61_dataini_ano."-".$this->rh61_dataini_mes."-".$this->rh61_dataini_dia;
         }
       }
       if($this->rh61_datafim == ""){
         $this->rh61_datafim_dia = ($this->rh61_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_datafim_dia"]:$this->rh61_datafim_dia);
         $this->rh61_datafim_mes = ($this->rh61_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_datafim_mes"]:$this->rh61_datafim_mes);
         $this->rh61_datafim_ano = ($this->rh61_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_datafim_ano"]:$this->rh61_datafim_ano);
         if($this->rh61_datafim_dia != ""){
            $this->rh61_datafim = $this->rh61_datafim_ano."-".$this->rh61_datafim_mes."-".$this->rh61_datafim_dia;
         }
       }
       $this->rh61_obs = ($this->rh61_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_obs"]:$this->rh61_obs);
     }else{
       $this->rh61_codigo = ($this->rh61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh61_codigo"]:$this->rh61_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh61_codigo){ 
      $this->atualizacampos();
     if($this->rh61_regist == null ){ 
       $this->erro_sql = " Campo Matrícula do Servidor nao Informado.";
       $this->erro_campo = "rh61_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh61_dataini == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "rh61_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh61_datafim == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "rh61_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh61_codigo == "" || $rh61_codigo == null ){
       $result = db_query("select nextval('rhpesjustica_rh61_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpesjustica_rh61_codigo_seq do campo: rh61_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh61_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpesjustica_rh61_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh61_codigo)){
         $this->erro_sql = " Campo rh61_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh61_codigo = $rh61_codigo; 
       }
     }
     if(($this->rh61_codigo == null) || ($this->rh61_codigo == "") ){ 
       $this->erro_sql = " Campo rh61_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpesjustica(
                                       rh61_codigo 
                                      ,rh61_regist 
                                      ,rh61_dataini 
                                      ,rh61_datafim 
                                      ,rh61_obs 
                       )
                values (
                                $this->rh61_codigo 
                               ,$this->rh61_regist 
                               ,".($this->rh61_dataini == "null" || $this->rh61_dataini == ""?"null":"'".$this->rh61_dataini."'")." 
                               ,".($this->rh61_datafim == "null" || $this->rh61_datafim == ""?"null":"'".$this->rh61_datafim."'")." 
                               ,'$this->rh61_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Funcionários na justiça ($this->rh61_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Funcionários na justiça já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Funcionários na justiça ($this->rh61_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh61_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh61_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9180,'$this->rh61_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1571,9180,'','".AddSlashes(pg_result($resaco,0,'rh61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1571,9181,'','".AddSlashes(pg_result($resaco,0,'rh61_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1571,9182,'','".AddSlashes(pg_result($resaco,0,'rh61_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1571,9183,'','".AddSlashes(pg_result($resaco,0,'rh61_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1571,9184,'','".AddSlashes(pg_result($resaco,0,'rh61_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh61_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhpesjustica set ";
     $virgula = "";
     if(trim($this->rh61_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh61_codigo"])){ 
       $sql  .= $virgula." rh61_codigo = $this->rh61_codigo ";
       $virgula = ",";
       if(trim($this->rh61_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh61_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh61_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh61_regist"])){ 
       $sql  .= $virgula." rh61_regist = $this->rh61_regist ";
       $virgula = ",";
       if(trim($this->rh61_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor nao Informado.";
         $this->erro_campo = "rh61_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh61_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh61_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh61_dataini_dia"] !="") ){ 
       $sql  .= $virgula." rh61_dataini = '$this->rh61_dataini' ";
       $virgula = ",";
       if(trim($this->rh61_dataini) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "rh61_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_dataini_dia"])){ 
         $sql  .= $virgula." rh61_dataini = null ";
         $virgula = ",";
         if(trim($this->rh61_dataini) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "rh61_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh61_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh61_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh61_datafim_dia"] !="") ){ 
       $sql  .= $virgula." rh61_datafim = '$this->rh61_datafim' ";
       $virgula = ",";
       if(trim($this->rh61_datafim) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "rh61_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_datafim_dia"])){ 
         $sql  .= $virgula." rh61_datafim = null ";
         $virgula = ",";
         if(trim($this->rh61_datafim) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "rh61_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh61_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh61_obs"])){ 
       $sql  .= $virgula." rh61_obs = '$this->rh61_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh61_codigo!=null){
       $sql .= " rh61_codigo = $this->rh61_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh61_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9180,'$this->rh61_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1571,9180,'".AddSlashes(pg_result($resaco,$conresaco,'rh61_codigo'))."','$this->rh61_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_regist"]))
           $resac = db_query("insert into db_acount values($acount,1571,9181,'".AddSlashes(pg_result($resaco,$conresaco,'rh61_regist'))."','$this->rh61_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_dataini"]))
           $resac = db_query("insert into db_acount values($acount,1571,9182,'".AddSlashes(pg_result($resaco,$conresaco,'rh61_dataini'))."','$this->rh61_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_datafim"]))
           $resac = db_query("insert into db_acount values($acount,1571,9183,'".AddSlashes(pg_result($resaco,$conresaco,'rh61_datafim'))."','$this->rh61_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh61_obs"]))
           $resac = db_query("insert into db_acount values($acount,1571,9184,'".AddSlashes(pg_result($resaco,$conresaco,'rh61_obs'))."','$this->rh61_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionários na justiça nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh61_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionários na justiça nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh61_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh61_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9180,'$rh61_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1571,9180,'','".AddSlashes(pg_result($resaco,$iresaco,'rh61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1571,9181,'','".AddSlashes(pg_result($resaco,$iresaco,'rh61_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1571,9182,'','".AddSlashes(pg_result($resaco,$iresaco,'rh61_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1571,9183,'','".AddSlashes(pg_result($resaco,$iresaco,'rh61_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1571,9184,'','".AddSlashes(pg_result($resaco,$iresaco,'rh61_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpesjustica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh61_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh61_codigo = $rh61_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionários na justiça nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh61_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionários na justiça nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh61_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpesjustica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesjustica ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpesjustica.rh61_regist";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
		                                         and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
																						 and  rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
																						 and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
		                                  and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
		                                    and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru ";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($rh61_codigo!=null ){
         $sql2 .= " where rhpesjustica.rh61_codigo = $rh61_codigo "; 
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
   function sql_query_file ( $rh61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesjustica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh61_codigo!=null ){
         $sql2 .= " where rhpesjustica.rh61_codigo = $rh61_codigo "; 
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
   function sql_query_cgm ( $rh61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesjustica ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpesjustica.rh61_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh61_codigo!=null ){
         $sql2 .= " where rhpesjustica.rh61_codigo = $rh61_codigo "; 
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