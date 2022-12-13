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

//MODULO: prefeitura
//CLASSE DA ENTIDADE certidaovalidaonline
class cl_certidaovalidaonline { 
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
   var $w18_sequencial = 0; 
   var $w18_dtvalidacao_dia = null; 
   var $w18_dtvalidacao_mes = null; 
   var $w18_dtvalidacao_ano = null; 
   var $w18_dtvalidacao = null; 
   var $w18_hora = null; 
   var $w18_codigovalidacao = null; 
   var $w18_ip = null; 
   var $w18_status = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w18_sequencial = int4 = sequencial 
                 w18_dtvalidacao = date = data validacao 
                 w18_hora = char(5) = Hora 
                 w18_codigovalidacao = varchar(50) = Codigo Validacao 
                 w18_ip = varchar(15) = IP 
                 w18_status = int4 = Status 
                 ";
   //funcao construtor da classe 
   function cl_certidaovalidaonline() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidaovalidaonline"); 
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
       $this->w18_sequencial = ($this->w18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_sequencial"]:$this->w18_sequencial);
       if($this->w18_dtvalidacao == ""){
         $this->w18_dtvalidacao_dia = ($this->w18_dtvalidacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_dia"]:$this->w18_dtvalidacao_dia);
         $this->w18_dtvalidacao_mes = ($this->w18_dtvalidacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_mes"]:$this->w18_dtvalidacao_mes);
         $this->w18_dtvalidacao_ano = ($this->w18_dtvalidacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_ano"]:$this->w18_dtvalidacao_ano);
         if($this->w18_dtvalidacao_dia != ""){
            $this->w18_dtvalidacao = $this->w18_dtvalidacao_ano."-".$this->w18_dtvalidacao_mes."-".$this->w18_dtvalidacao_dia;
         }
       }
       $this->w18_hora = ($this->w18_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_hora"]:$this->w18_hora);
       $this->w18_codigovalidacao = ($this->w18_codigovalidacao == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_codigovalidacao"]:$this->w18_codigovalidacao);
       $this->w18_ip = ($this->w18_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_ip"]:$this->w18_ip);
       $this->w18_status = ($this->w18_status == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_status"]:$this->w18_status);
     }else{
       $this->w18_sequencial = ($this->w18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w18_sequencial"]:$this->w18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w18_sequencial){ 
      $this->atualizacampos();
     if($this->w18_dtvalidacao == null ){ 
       $this->erro_sql = " Campo data validacao nao Informado.";
       $this->erro_campo = "w18_dtvalidacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w18_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "w18_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w18_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "w18_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w18_status == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "w18_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w18_sequencial == "" || $w18_sequencial == null ){
       $result = db_query("select nextval('certidaovalidaonline_w18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidaovalidaonline_w18_sequencial_seq do campo: w18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidaovalidaonline_w18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w18_sequencial)){
         $this->erro_sql = " Campo w18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w18_sequencial = $w18_sequencial; 
       }
     }
     if(($this->w18_sequencial == null) || ($this->w18_sequencial == "") ){ 
       $this->erro_sql = " Campo w18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidaovalidaonline(
                                       w18_sequencial 
                                      ,w18_dtvalidacao 
                                      ,w18_hora 
                                      ,w18_codigovalidacao 
                                      ,w18_ip 
                                      ,w18_status 
                       )
                values (
                                $this->w18_sequencial 
                               ,".($this->w18_dtvalidacao == "null" || $this->w18_dtvalidacao == ""?"null":"'".$this->w18_dtvalidacao."'")." 
                               ,'$this->w18_hora' 
                               ,'$this->w18_codigovalidacao' 
                               ,'$this->w18_ip' 
                               ,$this->w18_status 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valida Certidao On-line ($this->w18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valida Certidao On-line já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valida Certidao On-line ($this->w18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15023,'$this->w18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2641,15023,'','".AddSlashes(pg_result($resaco,0,'w18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2641,15024,'','".AddSlashes(pg_result($resaco,0,'w18_dtvalidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2641,15025,'','".AddSlashes(pg_result($resaco,0,'w18_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2641,15026,'','".AddSlashes(pg_result($resaco,0,'w18_codigovalidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2641,15027,'','".AddSlashes(pg_result($resaco,0,'w18_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2641,15028,'','".AddSlashes(pg_result($resaco,0,'w18_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidaovalidaonline set ";
     $virgula = "";
     if(trim($this->w18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_sequencial"])){ 
       $sql  .= $virgula." w18_sequencial = $this->w18_sequencial ";
       $virgula = ",";
       if(trim($this->w18_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "w18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w18_dtvalidacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_dia"] !="") ){ 
       $sql  .= $virgula." w18_dtvalidacao = '$this->w18_dtvalidacao' ";
       $virgula = ",";
       if(trim($this->w18_dtvalidacao) == null ){ 
         $this->erro_sql = " Campo data validacao nao Informado.";
         $this->erro_campo = "w18_dtvalidacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao_dia"])){ 
         $sql  .= $virgula." w18_dtvalidacao = null ";
         $virgula = ",";
         if(trim($this->w18_dtvalidacao) == null ){ 
           $this->erro_sql = " Campo data validacao nao Informado.";
           $this->erro_campo = "w18_dtvalidacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->w18_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_hora"])){ 
       $sql  .= $virgula." w18_hora = '$this->w18_hora' ";
       $virgula = ",";
       if(trim($this->w18_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "w18_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w18_codigovalidacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_codigovalidacao"])){ 
       $sql  .= $virgula." w18_codigovalidacao = '$this->w18_codigovalidacao' ";
       $virgula = ",";
     }
     if(trim($this->w18_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_ip"])){ 
       $sql  .= $virgula." w18_ip = '$this->w18_ip' ";
       $virgula = ",";
       if(trim($this->w18_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "w18_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w18_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w18_status"])){ 
       $sql  .= $virgula." w18_status = $this->w18_status ";
       $virgula = ",";
       if(trim($this->w18_status) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "w18_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w18_sequencial!=null){
       $sql .= " w18_sequencial = $this->w18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15023,'$this->w18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_sequencial"]) || $this->w18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2641,15023,'".AddSlashes(pg_result($resaco,$conresaco,'w18_sequencial'))."','$this->w18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_dtvalidacao"]) || $this->w18_dtvalidacao != "")
           $resac = db_query("insert into db_acount values($acount,2641,15024,'".AddSlashes(pg_result($resaco,$conresaco,'w18_dtvalidacao'))."','$this->w18_dtvalidacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_hora"]) || $this->w18_hora != "")
           $resac = db_query("insert into db_acount values($acount,2641,15025,'".AddSlashes(pg_result($resaco,$conresaco,'w18_hora'))."','$this->w18_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_codigovalidacao"]) || $this->w18_codigovalidacao != "")
           $resac = db_query("insert into db_acount values($acount,2641,15026,'".AddSlashes(pg_result($resaco,$conresaco,'w18_codigovalidacao'))."','$this->w18_codigovalidacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_ip"]) || $this->w18_ip != "")
           $resac = db_query("insert into db_acount values($acount,2641,15027,'".AddSlashes(pg_result($resaco,$conresaco,'w18_ip'))."','$this->w18_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w18_status"]) || $this->w18_status != "")
           $resac = db_query("insert into db_acount values($acount,2641,15028,'".AddSlashes(pg_result($resaco,$conresaco,'w18_status'))."','$this->w18_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valida Certidao On-line nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valida Certidao On-line nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         //$resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15023,'$w18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2641,15023,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2641,15024,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_dtvalidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2641,15025,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2641,15026,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_codigovalidacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2641,15027,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2641,15028,'','".AddSlashes(pg_result($resaco,$iresaco,'w18_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidaovalidaonline
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w18_sequencial = $w18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valida Certidao On-line nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valida Certidao On-line nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidaovalidaonline";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $w18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaovalidaonline ";
     $sql2 = "";
     if($dbwhere==""){
       if($w18_sequencial!=null ){
         $sql2 .= " where certidaovalidaonline.w18_sequencial = $w18_sequencial "; 
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
   function sql_query_file ( $w18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaovalidaonline ";
     $sql2 = "";
     if($dbwhere==""){
       if($w18_sequencial!=null ){
         $sql2 .= " where certidaovalidaonline.w18_sequencial = $w18_sequencial "; 
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