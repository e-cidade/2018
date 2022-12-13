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

//MODULO: caixa
//CLASSE DA ENTIDADE cadmodcarne
class cl_cadmodcarne { 
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
   var $k47_sequencial = 0; 
   var $k47_descr = null; 
   var $k47_obs = null; 
   var $k47_altura = 0; 
   var $k47_largura = 0; 
   var $k47_orientacao = null; 
   var $k47_tipoconvenio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k47_sequencial = int4 = Codigo do modelo 
                 k47_descr = varchar(40) = Descri��o do modelo 
                 k47_obs = text = Obs 
                 k47_altura = int4 = Altura da pagina 
                 k47_largura = int4 = Largura da pagina 
                 k47_orientacao = char(1) = Orienta��o da pagina 
                 k47_tipoconvenio = int4 = Tipo de convenio 
                 ";
   //funcao construtor da classe 
   function cl_cadmodcarne() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadmodcarne"); 
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
       $this->k47_sequencial = ($this->k47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_sequencial"]:$this->k47_sequencial);
       $this->k47_descr = ($this->k47_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_descr"]:$this->k47_descr);
       $this->k47_obs = ($this->k47_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_obs"]:$this->k47_obs);
       $this->k47_altura = ($this->k47_altura == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_altura"]:$this->k47_altura);
       $this->k47_largura = ($this->k47_largura == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_largura"]:$this->k47_largura);
       $this->k47_orientacao = ($this->k47_orientacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_orientacao"]:$this->k47_orientacao);
       $this->k47_tipoconvenio = ($this->k47_tipoconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_tipoconvenio"]:$this->k47_tipoconvenio);
     }else{
       $this->k47_sequencial = ($this->k47_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k47_sequencial"]:$this->k47_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k47_sequencial){ 
      $this->atualizacampos();
     if($this->k47_descr == null ){ 
       $this->erro_sql = " Campo Descri��o do modelo nao Informado.";
       $this->erro_campo = "k47_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k47_altura == null ){ 
       $this->k47_altura = "0";
     }
     if($this->k47_largura == null ){ 
       $this->k47_largura = "0";
     }
     if($this->k47_tipoconvenio == null ){ 
       $this->erro_sql = " Campo Tipo de convenio nao Informado.";
       $this->erro_campo = "k47_tipoconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k47_sequencial == "" || $k47_sequencial == null ){
       $result = db_query("select nextval('cadmodcarne_k47_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadmodcarne_k47_sequencial_seq do campo: k47_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k47_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadmodcarne_k47_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k47_sequencial)){
         $this->erro_sql = " Campo k47_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k47_sequencial = $k47_sequencial; 
       }
     }
     if(($this->k47_sequencial == null) || ($this->k47_sequencial == "") ){ 
       $this->erro_sql = " Campo k47_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadmodcarne(
                                       k47_sequencial 
                                      ,k47_descr 
                                      ,k47_obs 
                                      ,k47_altura 
                                      ,k47_largura 
                                      ,k47_orientacao 
                                      ,k47_tipoconvenio 
                       )
                values (
                                $this->k47_sequencial 
                               ,'$this->k47_descr' 
                               ,'$this->k47_obs' 
                               ,$this->k47_altura 
                               ,$this->k47_largura 
                               ,'$this->k47_orientacao' 
                               ,$this->k47_tipoconvenio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastros de modelos de carnes/recibos ($this->k47_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastros de modelos de carnes/recibos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastros de modelos de carnes/recibos ($this->k47_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k47_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k47_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8878,'$this->k47_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1515,8878,'','".AddSlashes(pg_result($resaco,0,'k47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,8879,'','".AddSlashes(pg_result($resaco,0,'k47_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,8880,'','".AddSlashes(pg_result($resaco,0,'k47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,8946,'','".AddSlashes(pg_result($resaco,0,'k47_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,8947,'','".AddSlashes(pg_result($resaco,0,'k47_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,8948,'','".AddSlashes(pg_result($resaco,0,'k47_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1515,9359,'','".AddSlashes(pg_result($resaco,0,'k47_tipoconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k47_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadmodcarne set ";
     $virgula = "";
     if(trim($this->k47_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_sequencial"])){ 
       $sql  .= $virgula." k47_sequencial = $this->k47_sequencial ";
       $virgula = ",";
       if(trim($this->k47_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo do modelo nao Informado.";
         $this->erro_campo = "k47_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k47_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_descr"])){ 
       $sql  .= $virgula." k47_descr = '$this->k47_descr' ";
       $virgula = ",";
       if(trim($this->k47_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o do modelo nao Informado.";
         $this->erro_campo = "k47_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k47_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_obs"])){ 
       $sql  .= $virgula." k47_obs = '$this->k47_obs' ";
       $virgula = ",";
     }
     if(trim($this->k47_altura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_altura"])){ 
        if(trim($this->k47_altura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k47_altura"])){ 
           $this->k47_altura = "0" ; 
        } 
       $sql  .= $virgula." k47_altura = $this->k47_altura ";
       $virgula = ",";
     }
     if(trim($this->k47_largura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_largura"])){ 
        if(trim($this->k47_largura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k47_largura"])){ 
           $this->k47_largura = "0" ; 
        } 
       $sql  .= $virgula." k47_largura = $this->k47_largura ";
       $virgula = ",";
     }
     if(trim($this->k47_orientacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_orientacao"])){ 
       $sql  .= $virgula." k47_orientacao = '$this->k47_orientacao' ";
       $virgula = ",";
     }
     if(trim($this->k47_tipoconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k47_tipoconvenio"])){ 
       $sql  .= $virgula." k47_tipoconvenio = $this->k47_tipoconvenio ";
       $virgula = ",";
       if(trim($this->k47_tipoconvenio) == null ){ 
         $this->erro_sql = " Campo Tipo de convenio nao Informado.";
         $this->erro_campo = "k47_tipoconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k47_sequencial!=null){
       $sql .= " k47_sequencial = $this->k47_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k47_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8878,'$this->k47_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1515,8878,'".AddSlashes(pg_result($resaco,$conresaco,'k47_sequencial'))."','$this->k47_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_descr"]))
           $resac = db_query("insert into db_acount values($acount,1515,8879,'".AddSlashes(pg_result($resaco,$conresaco,'k47_descr'))."','$this->k47_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_obs"]))
           $resac = db_query("insert into db_acount values($acount,1515,8880,'".AddSlashes(pg_result($resaco,$conresaco,'k47_obs'))."','$this->k47_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_altura"]))
           $resac = db_query("insert into db_acount values($acount,1515,8946,'".AddSlashes(pg_result($resaco,$conresaco,'k47_altura'))."','$this->k47_altura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_largura"]))
           $resac = db_query("insert into db_acount values($acount,1515,8947,'".AddSlashes(pg_result($resaco,$conresaco,'k47_largura'))."','$this->k47_largura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_orientacao"]))
           $resac = db_query("insert into db_acount values($acount,1515,8948,'".AddSlashes(pg_result($resaco,$conresaco,'k47_orientacao'))."','$this->k47_orientacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k47_tipoconvenio"]))
           $resac = db_query("insert into db_acount values($acount,1515,9359,'".AddSlashes(pg_result($resaco,$conresaco,'k47_tipoconvenio'))."','$this->k47_tipoconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastros de modelos de carnes/recibos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k47_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastros de modelos de carnes/recibos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k47_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k47_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k47_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k47_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8878,'$k47_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1515,8878,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,8879,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,8880,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,8946,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_altura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,8947,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_largura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,8948,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1515,9359,'','".AddSlashes(pg_result($resaco,$iresaco,'k47_tipoconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadmodcarne
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k47_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k47_sequencial = $k47_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastros de modelos de carnes/recibos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k47_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastros de modelos de carnes/recibos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k47_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k47_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cadmodcarne";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadmodcarne ";
     $sql2 = "";
     if($dbwhere==""){
       if($k47_sequencial!=null ){
         $sql2 .= " where cadmodcarne.k47_sequencial = $k47_sequencial "; 
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
   function sql_query_file ( $k47_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadmodcarne ";
     $sql2 = "";
     if($dbwhere==""){
       if($k47_sequencial!=null ){
         $sql2 .= " where cadmodcarne.k47_sequencial = $k47_sequencial "; 
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