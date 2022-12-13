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
//CLASSE DA ENTIDADE rhpagatra
class cl_rhpagatra { 
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
   var $rh57_seq = 0; 
   var $rh57_ano = 0; 
   var $rh57_mes = 0; 
   var $rh57_regist = 0; 
   var $rh57_valorini = 0; 
   var $rh57_saldo = 0; 
   var $rh57_tipoatra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh57_seq = int8 = Sequencial 
                 rh57_ano = int4 = Ano 
                 rh57_mes = int4 = Mês 
                 rh57_regist = int4 = Matrícula 
                 rh57_valorini = float8 = Valor Inicial 
                 rh57_saldo = float8 = Saldo 
                 rh57_tipoatra = int4 = Tipo de Atraso 
                 ";
   //funcao construtor da classe 
   function cl_rhpagatra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpagatra"); 
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
       $this->rh57_seq = ($this->rh57_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_seq"]:$this->rh57_seq);
       $this->rh57_ano = ($this->rh57_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_ano"]:$this->rh57_ano);
       $this->rh57_mes = ($this->rh57_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_mes"]:$this->rh57_mes);
       $this->rh57_regist = ($this->rh57_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_regist"]:$this->rh57_regist);
       $this->rh57_valorini = ($this->rh57_valorini == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_valorini"]:$this->rh57_valorini);
       $this->rh57_saldo = ($this->rh57_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_saldo"]:$this->rh57_saldo);
       $this->rh57_tipoatra = ($this->rh57_tipoatra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_tipoatra"]:$this->rh57_tipoatra);
     }else{
       $this->rh57_seq = ($this->rh57_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh57_seq"]:$this->rh57_seq);
     }
   }
   // funcao para inclusao
   function incluir ($rh57_seq){ 
      $this->atualizacampos();
     if($this->rh57_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh57_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh57_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh57_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh57_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "rh57_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh57_valorini == null ){ 
       $this->erro_sql = " Campo Valor Inicial nao Informado.";
       $this->erro_campo = "rh57_valorini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh57_saldo == null ){ 
       $this->erro_sql = " Campo Saldo nao Informado.";
       $this->erro_campo = "rh57_saldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh57_tipoatra == null ){ 
       $this->erro_sql = " Campo Tipo de Atraso nao Informado.";
       $this->erro_campo = "rh57_tipoatra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh57_seq == "" || $rh57_seq == null ){
       $result = db_query("select nextval('rhpagatra_rh57_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpagatra_rh57_seq_seq do campo: rh57_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh57_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpagatra_rh57_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh57_seq)){
         $this->erro_sql = " Campo rh57_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh57_seq = $rh57_seq; 
       }
     }
     if(($this->rh57_seq == null) || ($this->rh57_seq == "") ){ 
       $this->erro_sql = " Campo rh57_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpagatra(
                                       rh57_seq 
                                      ,rh57_ano 
                                      ,rh57_mes 
                                      ,rh57_regist 
                                      ,rh57_valorini 
                                      ,rh57_saldo 
                                      ,rh57_tipoatra 
                       )
                values (
                                $this->rh57_seq 
                               ,$this->rh57_ano 
                               ,$this->rh57_mes 
                               ,$this->rh57_regist 
                               ,$this->rh57_valorini 
                               ,$this->rh57_saldo 
                               ,$this->rh57_tipoatra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pagamentos atrasados ($this->rh57_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pagamentos atrasados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pagamentos atrasados ($this->rh57_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh57_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh57_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9024,'$this->rh57_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1545,9024,'','".AddSlashes(pg_result($resaco,0,'rh57_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9025,'','".AddSlashes(pg_result($resaco,0,'rh57_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9026,'','".AddSlashes(pg_result($resaco,0,'rh57_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9027,'','".AddSlashes(pg_result($resaco,0,'rh57_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9028,'','".AddSlashes(pg_result($resaco,0,'rh57_valorini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9029,'','".AddSlashes(pg_result($resaco,0,'rh57_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1545,9030,'','".AddSlashes(pg_result($resaco,0,'rh57_tipoatra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh57_seq=null) { 
      $this->atualizacampos();
     $sql = " update rhpagatra set ";
     $virgula = "";
     if(trim($this->rh57_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_seq"])){ 
       $sql  .= $virgula." rh57_seq = $this->rh57_seq ";
       $virgula = ",";
       if(trim($this->rh57_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh57_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_ano"])){ 
       $sql  .= $virgula." rh57_ano = $this->rh57_ano ";
       $virgula = ",";
       if(trim($this->rh57_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh57_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_mes"])){ 
       $sql  .= $virgula." rh57_mes = $this->rh57_mes ";
       $virgula = ",";
       if(trim($this->rh57_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh57_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_regist"])){ 
       $sql  .= $virgula." rh57_regist = $this->rh57_regist ";
       $virgula = ",";
       if(trim($this->rh57_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "rh57_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_valorini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_valorini"])){ 
       $sql  .= $virgula." rh57_valorini = $this->rh57_valorini ";
       $virgula = ",";
       if(trim($this->rh57_valorini) == null ){ 
         $this->erro_sql = " Campo Valor Inicial nao Informado.";
         $this->erro_campo = "rh57_valorini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_saldo"])){ 
       $sql  .= $virgula." rh57_saldo = $this->rh57_saldo ";
       $virgula = ",";
       if(trim($this->rh57_saldo) == null ){ 
         $this->erro_sql = " Campo Saldo nao Informado.";
         $this->erro_campo = "rh57_saldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh57_tipoatra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh57_tipoatra"])){ 
       $sql  .= $virgula." rh57_tipoatra = $this->rh57_tipoatra ";
       $virgula = ",";
       if(trim($this->rh57_tipoatra) == null ){ 
         $this->erro_sql = " Campo Tipo de Atraso nao Informado.";
         $this->erro_campo = "rh57_tipoatra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh57_seq!=null){
       $sql .= " rh57_seq = $this->rh57_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh57_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9024,'$this->rh57_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_seq"]))
           $resac = db_query("insert into db_acount values($acount,1545,9024,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_seq'))."','$this->rh57_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_ano"]))
           $resac = db_query("insert into db_acount values($acount,1545,9025,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_ano'))."','$this->rh57_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_mes"]))
           $resac = db_query("insert into db_acount values($acount,1545,9026,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_mes'))."','$this->rh57_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_regist"]))
           $resac = db_query("insert into db_acount values($acount,1545,9027,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_regist'))."','$this->rh57_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_valorini"]))
           $resac = db_query("insert into db_acount values($acount,1545,9028,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_valorini'))."','$this->rh57_valorini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_saldo"]))
           $resac = db_query("insert into db_acount values($acount,1545,9029,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_saldo'))."','$this->rh57_saldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh57_tipoatra"]))
           $resac = db_query("insert into db_acount values($acount,1545,9030,'".AddSlashes(pg_result($resaco,$conresaco,'rh57_tipoatra'))."','$this->rh57_tipoatra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos atrasados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh57_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos atrasados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh57_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh57_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh57_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh57_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9024,'$rh57_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1545,9024,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9025,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9026,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9027,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9028,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_valorini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9029,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1545,9030,'','".AddSlashes(pg_result($resaco,$iresaco,'rh57_tipoatra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhpagatra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh57_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh57_seq = $rh57_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos atrasados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh57_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos atrasados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh57_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh57_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpagatra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagatra ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpagatra.rh57_regist";
     $sql .= "      inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist ";
     $sql .= "      inner join rhtipoatras  on  rhtipoatras.rh60_codigo = rhpagatra.rh57_tipoatra";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
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
   function sql_query_file ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagatra ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
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
   function sql_query_tipoatras ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhpagatra ";
     $sql .= "      inner join rhtipoatras   on  rhtipoatras.rh60_codigo   = rhpagatra.rh57_tipoatra ";
     $sql .= "      left  join rhpagocor     on  rhpagocor.rh58_seq        = rhpagatra.rh57_seq ";
     if($database != null && trim($database) != ""){
       $sql .= "                            and  rh58_data >= '".$database."' ";
     }
     $sql .= "      left  join rhpagtipoocor on  rhpagtipoocor.rh59_codigo = rhpagocor.rh58_tipoocor ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql2 = "";
     if($dbgroupby != null ){
       $sql .= " group by ";
       $campos_sql = split("#",$dbgroupby);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     $sql .= $sql2;
     $sql2 = "";
     if($dbhaving != ""){
       $sql2 = " having $dbhaving";
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
   function sql_query_relatorio ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere="",$dbhaving="",$dbgroupby=null,$ano, $mes){ 
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
     $sql .= " from rhpagatra ";
     $sql .= "      inner join rhpessoal     on rhpessoal.rh01_regist     = rhpagatra.rh57_regist ";
     $sql .= "      left  join rhpessoalmov  on rhpessoalmov.rh02_anousu  = ".$ano."
                                            and rhpessoalmov.rh02_mesusu  = ".$mes."
                                            and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist ";
     $sql .= "      left  join rhpesbanco    on rhpesbanco.rh44_seqpes    = rhpessoalmov.rh02_seqpes "; 
     $sql .= "      inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota ";
     $sql .= "      left  join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg ";
     $sql .= "      left  join rhpespadrao   on rhpespadrao.rh03_seqpes   = rhpessoalmov.rh02_seqpes ";
     $sql .= "      left  join rhfuncao      on rhfuncao.rh37_funcao      = rhpessoal.rh01_funcao ";
     $sql .= "      inner join rhtipoatras   on rhtipoatras.rh60_codigo   = rhpagatra.rh57_tipoatra ";
     $sql .= "      left  join (
                                select distinct rh61_regist
                                from rhpesjustica
                                where (
                                       '".date("Y-m-d",db_getsession("DB_datausu"))."' between rh61_dataini and rh61_datafim
                                       or rh61_datafim is null
                                      )
                               ) rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql2 = "";
     if($dbgroupby != null ){
       $sql .= " group by ";
       $campos_sql = split("#",$dbgroupby);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     $sql .= $sql2;
     $sql2 = "";
     if($dbhaving != ""){
       $sql2 = " having $dbhaving";
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
   function sql_query_tipocgm ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagatra ";
     $sql .= "      inner join rhpessoal     on rhpessoal.rh01_regist     = rhpagatra.rh57_regist ";
     $sql .= "      inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhtipoatras   on rhtipoatras.rh60_codigo   = rhpagatra.rh57_tipoatra ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
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
   function sql_query_rhpesjustica ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhpagatra ";
     $sql .= "      left join rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
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
   function sql_query_notjustica ( $rh57_seq=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhpagatra ";
     $sql .= "      left join (
                               select distinct rh61_regist
                               from rhpesjustica
                               where (
                                      '".date("Y-m-d",db_getsession("DB_datausu"))."' between rh61_dataini and rh61_datafim
                                      or rh61_datafim is null
                                     )
                              ) rhpesjustica on rhpesjustica.rh61_regist = rhpagatra.rh57_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh57_seq!=null ){
         $sql2 .= " where rhpagatra.rh57_seq = $rh57_seq "; 
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