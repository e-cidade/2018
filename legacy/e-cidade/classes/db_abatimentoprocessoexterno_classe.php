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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentoprocessoexterno
class cl_abatimentoprocessoexterno { 
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
   var $k160_sequencial = 0; 
   var $k160_abatimento = 0; 
   var $k160_numeroprocesso = null; 
   var $k160_data_dia = null; 
   var $k160_data_mes = null; 
   var $k160_data_ano = null; 
   var $k160_data = null; 
   var $k160_nometitular = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k160_sequencial = int8 = Código Processo 
                 k160_abatimento = int4 = Sequencial 
                 k160_numeroprocesso = varchar(50) = Número Processo 
                 k160_data = date = Data Processo 
                 k160_nometitular = varchar(50) = Nome Titular 
                 ";
   //funcao construtor da classe 
   function cl_abatimentoprocessoexterno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentoprocessoexterno"); 
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
       $this->k160_sequencial = ($this->k160_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_sequencial"]:$this->k160_sequencial);
       $this->k160_abatimento = ($this->k160_abatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_abatimento"]:$this->k160_abatimento);
       $this->k160_numeroprocesso = ($this->k160_numeroprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_numeroprocesso"]:$this->k160_numeroprocesso);
       if($this->k160_data == ""){
         $this->k160_data_dia = ($this->k160_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_data_dia"]:$this->k160_data_dia);
         $this->k160_data_mes = ($this->k160_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_data_mes"]:$this->k160_data_mes);
         $this->k160_data_ano = ($this->k160_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_data_ano"]:$this->k160_data_ano);
         if($this->k160_data_dia != ""){
            $this->k160_data = $this->k160_data_ano."-".$this->k160_data_mes."-".$this->k160_data_dia;
         }
       }
       $this->k160_nometitular = ($this->k160_nometitular == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_nometitular"]:$this->k160_nometitular);
     }else{
       $this->k160_sequencial = ($this->k160_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k160_sequencial"]:$this->k160_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k160_sequencial){ 
      $this->atualizacampos();
     if($this->k160_abatimento == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "k160_abatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k160_numeroprocesso == null ){ 
       $this->erro_sql = " Campo Número Processo nao Informado.";
       $this->erro_campo = "k160_numeroprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k160_data == null ){ 
       $this->erro_sql = " Campo Data Processo nao Informado.";
       $this->erro_campo = "k160_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k160_nometitular == null ){ 
       $this->erro_sql = " Campo Nome Titular nao Informado.";
       $this->erro_campo = "k160_nometitular";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k160_sequencial == "" || $k160_sequencial == null ){
       $result = db_query("select nextval('abatimentoprocessoexterno_k160_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentoprocessoexterno_k160_sequencial_seq do campo: k160_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k160_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from abatimentoprocessoexterno_k160_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k160_sequencial)){
         $this->erro_sql = " Campo k160_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k160_sequencial = $k160_sequencial; 
       }
     }
     if(($this->k160_sequencial == null) || ($this->k160_sequencial == "") ){ 
       $this->erro_sql = " Campo k160_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentoprocessoexterno(
                                       k160_sequencial 
                                      ,k160_abatimento 
                                      ,k160_numeroprocesso 
                                      ,k160_data 
                                      ,k160_nometitular 
                       )
                values (
                                $this->k160_sequencial 
                               ,$this->k160_abatimento 
                               ,'$this->k160_numeroprocesso' 
                               ,".($this->k160_data == "null" || $this->k160_data == ""?"null":"'".$this->k160_data."'")." 
                               ,'$this->k160_nometitular' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "abatimentoprocessoexterno ($this->k160_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "abatimentoprocessoexterno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "abatimentoprocessoexterno ($this->k160_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k160_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k160_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19622,'$this->k160_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3488,19622,'','".AddSlashes(pg_result($resaco,0,'k160_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3488,19625,'','".AddSlashes(pg_result($resaco,0,'k160_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3488,19624,'','".AddSlashes(pg_result($resaco,0,'k160_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3488,19626,'','".AddSlashes(pg_result($resaco,0,'k160_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3488,19623,'','".AddSlashes(pg_result($resaco,0,'k160_nometitular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k160_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentoprocessoexterno set ";
     $virgula = "";
     if(trim($this->k160_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k160_sequencial"])){ 
       $sql  .= $virgula." k160_sequencial = $this->k160_sequencial ";
       $virgula = ",";
       if(trim($this->k160_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Processo nao Informado.";
         $this->erro_campo = "k160_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k160_abatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k160_abatimento"])){ 
       $sql  .= $virgula." k160_abatimento = $this->k160_abatimento ";
       $virgula = ",";
       if(trim($this->k160_abatimento) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k160_abatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k160_numeroprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k160_numeroprocesso"])){ 
       $sql  .= $virgula." k160_numeroprocesso = '$this->k160_numeroprocesso' ";
       $virgula = ",";
       if(trim($this->k160_numeroprocesso) == null ){ 
         $this->erro_sql = " Campo Número Processo nao Informado.";
         $this->erro_campo = "k160_numeroprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k160_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k160_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k160_data_dia"] !="") ){ 
       $sql  .= $virgula." k160_data = '$this->k160_data' ";
       $virgula = ",";
       if(trim($this->k160_data) == null ){ 
         $this->erro_sql = " Campo Data Processo nao Informado.";
         $this->erro_campo = "k160_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k160_data_dia"])){ 
         $sql  .= $virgula." k160_data = null ";
         $virgula = ",";
         if(trim($this->k160_data) == null ){ 
           $this->erro_sql = " Campo Data Processo nao Informado.";
           $this->erro_campo = "k160_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k160_nometitular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k160_nometitular"])){ 
       $sql  .= $virgula." k160_nometitular = '$this->k160_nometitular' ";
       $virgula = ",";
       if(trim($this->k160_nometitular) == null ){ 
         $this->erro_sql = " Campo Nome Titular nao Informado.";
         $this->erro_campo = "k160_nometitular";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k160_sequencial!=null){
       $sql .= " k160_sequencial = $this->k160_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k160_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19622,'$this->k160_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k160_sequencial"]) || $this->k160_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3488,19622,'".AddSlashes(pg_result($resaco,$conresaco,'k160_sequencial'))."','$this->k160_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k160_abatimento"]) || $this->k160_abatimento != "")
           $resac = db_query("insert into db_acount values($acount,3488,19625,'".AddSlashes(pg_result($resaco,$conresaco,'k160_abatimento'))."','$this->k160_abatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k160_numeroprocesso"]) || $this->k160_numeroprocesso != "")
           $resac = db_query("insert into db_acount values($acount,3488,19624,'".AddSlashes(pg_result($resaco,$conresaco,'k160_numeroprocesso'))."','$this->k160_numeroprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k160_data"]) || $this->k160_data != "")
           $resac = db_query("insert into db_acount values($acount,3488,19626,'".AddSlashes(pg_result($resaco,$conresaco,'k160_data'))."','$this->k160_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k160_nometitular"]) || $this->k160_nometitular != "")
           $resac = db_query("insert into db_acount values($acount,3488,19623,'".AddSlashes(pg_result($resaco,$conresaco,'k160_nometitular'))."','$this->k160_nometitular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "abatimentoprocessoexterno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k160_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "abatimentoprocessoexterno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k160_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k160_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19622,'$k160_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3488,19622,'','".AddSlashes(pg_result($resaco,$iresaco,'k160_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3488,19625,'','".AddSlashes(pg_result($resaco,$iresaco,'k160_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3488,19624,'','".AddSlashes(pg_result($resaco,$iresaco,'k160_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3488,19626,'','".AddSlashes(pg_result($resaco,$iresaco,'k160_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3488,19623,'','".AddSlashes(pg_result($resaco,$iresaco,'k160_nometitular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from abatimentoprocessoexterno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k160_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k160_sequencial = $k160_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "abatimentoprocessoexterno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k160_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "abatimentoprocessoexterno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k160_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k160_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentoprocessoexterno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k160_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoprocessoexterno ";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentoprocessoexterno.k160_abatimento";
     $sql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
     $sql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
     $sql2 = "";
     if($dbwhere==""){
       if($k160_sequencial!=null ){
         $sql2 .= " where abatimentoprocessoexterno.k160_sequencial = $k160_sequencial "; 
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
   function sql_query_file ( $k160_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoprocessoexterno ";
     $sql2 = "";
     if($dbwhere==""){
       if($k160_sequencial!=null ){
         $sql2 .= " where abatimentoprocessoexterno.k160_sequencial = $k160_sequencial "; 
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