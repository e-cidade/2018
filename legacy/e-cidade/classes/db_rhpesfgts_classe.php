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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhpesfgts
class cl_rhpesfgts { 
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
   var $rh15_regist = 0; 
   var $rh15_data_dia = null; 
   var $rh15_data_mes = null; 
   var $rh15_data_ano = null; 
   var $rh15_data = null; 
   var $rh15_banco = null; 
   var $rh15_agencia = null; 
   var $rh15_agencia_d = null; 
   var $rh15_contac = null; 
   var $rh15_contac_d = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh15_regist = int4 = Matrícula do Servidor 
                 rh15_data = date = Opção do FGTS 
                 rh15_banco = varchar(10) = Banco do FGTS 
                 rh15_agencia = varchar(5) = Agência do FGTS 
                 rh15_agencia_d = varchar(2) = Dígito da Agência do FGTS 
                 rh15_contac = varchar(12) = Conta  do FGTS 
                 rh15_contac_d = varchar(2) = Dígito da Conta do FGTS 
                 ";
   //funcao construtor da classe 
   function cl_rhpesfgts() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpesfgts"); 
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
       $this->rh15_regist = ($this->rh15_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_regist"]:$this->rh15_regist);
       if($this->rh15_data == ""){
         $this->rh15_data_dia = ($this->rh15_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_data_dia"]:$this->rh15_data_dia);
         $this->rh15_data_mes = ($this->rh15_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_data_mes"]:$this->rh15_data_mes);
         $this->rh15_data_ano = ($this->rh15_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_data_ano"]:$this->rh15_data_ano);
         if($this->rh15_data_dia != ""){
            $this->rh15_data = $this->rh15_data_ano."-".$this->rh15_data_mes."-".$this->rh15_data_dia;
         }
       }
       $this->rh15_banco = ($this->rh15_banco == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_banco"]:$this->rh15_banco);
       $this->rh15_agencia = ($this->rh15_agencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_agencia"]:$this->rh15_agencia);
       $this->rh15_agencia_d = ($this->rh15_agencia_d == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_agencia_d"]:$this->rh15_agencia_d);
       $this->rh15_contac = ($this->rh15_contac == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_contac"]:$this->rh15_contac);
       $this->rh15_contac_d = ($this->rh15_contac_d == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_contac_d"]:$this->rh15_contac_d);
     }else{
       $this->rh15_regist = ($this->rh15_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh15_regist"]:$this->rh15_regist);
     }
   }
   // funcao para inclusao
   function incluir ($rh15_regist){ 
      $this->atualizacampos();
     if($this->rh15_data == null ){ 
       $this->erro_sql = " Campo Opção do FGTS não informado.";
       $this->erro_campo = "rh15_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh15_banco == null ){ 
       $this->erro_sql = " Campo Banco do FGTS não informado.";
       $this->erro_campo = "rh15_banco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh15_agencia == null ){ 
       $this->erro_sql = " Campo Agência do FGTS não informado.";
       $this->erro_campo = "rh15_agencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh15_contac == null ){ 
       $this->erro_sql = " Campo Conta  do FGTS não informado.";
       $this->erro_campo = "rh15_contac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh15_regist = $rh15_regist; 
     if(($this->rh15_regist == null) || ($this->rh15_regist == "") ){ 
       $this->erro_sql = " Campo rh15_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpesfgts(
                                       rh15_regist 
                                      ,rh15_data 
                                      ,rh15_banco 
                                      ,rh15_agencia 
                                      ,rh15_agencia_d 
                                      ,rh15_contac 
                                      ,rh15_contac_d 
                       )
                values (
                                $this->rh15_regist 
                               ,".($this->rh15_data == "null" || $this->rh15_data == ""?"null":"'".$this->rh15_data."'")." 
                               ,'$this->rh15_banco' 
                               ,'$this->rh15_agencia' 
                               ,'$this->rh15_agencia_d' 
                               ,'$this->rh15_contac' 
                               ,'$this->rh15_contac_d' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Funcionários com FGTS ($this->rh15_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Funcionários com FGTS já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Funcionários com FGTS ($this->rh15_regist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh15_regist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh15_regist  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7063,'$this->rh15_regist','I')");
         $resac = db_query("insert into db_acount values($acount,1167,7063,'','".AddSlashes(pg_result($resaco,0,'rh15_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7064,'','".AddSlashes(pg_result($resaco,0,'rh15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7065,'','".AddSlashes(pg_result($resaco,0,'rh15_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7066,'','".AddSlashes(pg_result($resaco,0,'rh15_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7067,'','".AddSlashes(pg_result($resaco,0,'rh15_agencia_d'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7068,'','".AddSlashes(pg_result($resaco,0,'rh15_contac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1167,7069,'','".AddSlashes(pg_result($resaco,0,'rh15_contac_d'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh15_regist=null) { 
      $this->atualizacampos();
     $sql = " update rhpesfgts set ";
     $virgula = "";
     if(trim($this->rh15_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_regist"])){ 
       $sql  .= $virgula." rh15_regist = $this->rh15_regist ";
       $virgula = ",";
       if(trim($this->rh15_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor não informado.";
         $this->erro_campo = "rh15_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh15_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh15_data_dia"] !="") ){ 
       $sql  .= $virgula." rh15_data = '$this->rh15_data' ";
       $virgula = ",";
       if(trim($this->rh15_data) == null ){ 
         $this->erro_sql = " Campo Opção do FGTS não informado.";
         $this->erro_campo = "rh15_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_data_dia"])){ 
         $sql  .= $virgula." rh15_data = null ";
         $virgula = ",";
         if(trim($this->rh15_data) == null ){ 
           $this->erro_sql = " Campo Opção do FGTS não informado.";
           $this->erro_campo = "rh15_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh15_banco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_banco"])){ 
       $sql  .= $virgula." rh15_banco = '$this->rh15_banco' ";
       $virgula = ",";
       if(trim($this->rh15_banco) == null ){ 
         $this->erro_sql = " Campo Banco do FGTS não informado.";
         $this->erro_campo = "rh15_banco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh15_agencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_agencia"])){ 
       $sql  .= $virgula." rh15_agencia = '$this->rh15_agencia' ";
       $virgula = ",";
       if(trim($this->rh15_agencia) == null ){ 
         $this->erro_sql = " Campo Agência do FGTS não informado.";
         $this->erro_campo = "rh15_agencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh15_agencia_d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_agencia_d"])){ 
       $sql  .= $virgula." rh15_agencia_d = '$this->rh15_agencia_d' ";
       $virgula = ",";
     }
     if(trim($this->rh15_contac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_contac"])){ 
       $sql  .= $virgula." rh15_contac = '$this->rh15_contac' ";
       $virgula = ",";
       if(trim($this->rh15_contac) == null ){ 
         $this->erro_sql = " Campo Conta  do FGTS não informado.";
         $this->erro_campo = "rh15_contac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh15_contac_d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh15_contac_d"])){ 
       $sql  .= $virgula." rh15_contac_d = '$this->rh15_contac_d' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh15_regist!=null){
       $sql .= " rh15_regist = $this->rh15_regist";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh15_regist));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7063,'$this->rh15_regist','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_regist"]) || $this->rh15_regist != "")
             $resac = db_query("insert into db_acount values($acount,1167,7063,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_regist'))."','$this->rh15_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_data"]) || $this->rh15_data != "")
             $resac = db_query("insert into db_acount values($acount,1167,7064,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_data'))."','$this->rh15_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_banco"]) || $this->rh15_banco != "")
             $resac = db_query("insert into db_acount values($acount,1167,7065,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_banco'))."','$this->rh15_banco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_agencia"]) || $this->rh15_agencia != "")
             $resac = db_query("insert into db_acount values($acount,1167,7066,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_agencia'))."','$this->rh15_agencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_agencia_d"]) || $this->rh15_agencia_d != "")
             $resac = db_query("insert into db_acount values($acount,1167,7067,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_agencia_d'))."','$this->rh15_agencia_d',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_contac"]) || $this->rh15_contac != "")
             $resac = db_query("insert into db_acount values($acount,1167,7068,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_contac'))."','$this->rh15_contac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh15_contac_d"]) || $this->rh15_contac_d != "")
             $resac = db_query("insert into db_acount values($acount,1167,7069,'".AddSlashes(pg_result($resaco,$conresaco,'rh15_contac_d'))."','$this->rh15_contac_d',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionários com FGTS nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh15_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionários com FGTS nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh15_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh15_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh15_regist=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh15_regist));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7063,'$rh15_regist','E')");
           $resac  = db_query("insert into db_acount values($acount,1167,7063,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7064,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7065,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_banco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7066,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_agencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7067,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_agencia_d'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7068,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_contac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1167,7069,'','".AddSlashes(pg_result($resaco,$iresaco,'rh15_contac_d'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpesfgts
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh15_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh15_regist = $rh15_regist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Funcionários com FGTS nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh15_regist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Funcionários com FGTS nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh15_regist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh15_regist;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpesfgts";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh15_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesfgts ";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rhpesfgts.rh15_banco";
     $sql2 = "";
     if($dbwhere==""){
       if($rh15_regist!=null ){
         $sql2 .= " where rhpesfgts.rh15_regist = $rh15_regist "; 
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
   function sql_query_file ( $rh15_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesfgts ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh15_regist!=null ){
         $sql2 .= " where rhpesfgts.rh15_regist = $rh15_regist "; 
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
   function sql_query_banco ( $rh15_regist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpesfgts ";
     $sql .= "      inner join db_bancos on db_bancos.db90_codban = rhpesfgts.rh15_banco ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh15_regist!=null ){
         $sql2 .= " where rhpesfgts.rh15_regist = $rh15_regist "; 
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