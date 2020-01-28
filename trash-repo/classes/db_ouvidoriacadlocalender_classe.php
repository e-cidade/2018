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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriacadlocalender
class cl_ouvidoriacadlocalender { 
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
   var $ov26_sequencial = 0; 
   var $ov26_ruas = 0; 
   var $ov26_ouvidoriacadlocal = 0; 
   var $ov26_numero = 0; 
   var $ov26_complemento = null; 
   var $ov26_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov26_sequencial = int4 = Sequencial 
                 ov26_ruas = int4 = Endereço 
                 ov26_ouvidoriacadlocal = int4 = Local 
                 ov26_numero = int4 = Número 
                 ov26_complemento = varchar(50) = Complemento 
                 ov26_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriacadlocalender() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriacadlocalender"); 
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
       $this->ov26_sequencial = ($this->ov26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_sequencial"]:$this->ov26_sequencial);
       $this->ov26_ruas = ($this->ov26_ruas == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_ruas"]:$this->ov26_ruas);
       $this->ov26_ouvidoriacadlocal = ($this->ov26_ouvidoriacadlocal == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_ouvidoriacadlocal"]:$this->ov26_ouvidoriacadlocal);
       $this->ov26_numero = ($this->ov26_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_numero"]:$this->ov26_numero);
       $this->ov26_complemento = ($this->ov26_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_complemento"]:$this->ov26_complemento);
       $this->ov26_observacao = ($this->ov26_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_observacao"]:$this->ov26_observacao);
     }else{
       $this->ov26_sequencial = ($this->ov26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov26_sequencial"]:$this->ov26_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov26_sequencial){ 
      $this->atualizacampos();
     if($this->ov26_ruas == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "ov26_ruas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov26_ouvidoriacadlocal == null ){ 
       $this->erro_sql = " Campo Local nao Informado.";
       $this->erro_campo = "ov26_ouvidoriacadlocal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov26_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "ov26_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov26_sequencial == "" || $ov26_sequencial == null ){
       $result = db_query("select nextval('ouvidoriacadlocalender_ov26_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriacadlocalender_ov26_sequencial_seq do campo: ov26_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov26_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriacadlocalender_ov26_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov26_sequencial)){
         $this->erro_sql = " Campo ov26_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov26_sequencial = $ov26_sequencial; 
       }
     }
     if(($this->ov26_sequencial == null) || ($this->ov26_sequencial == "") ){ 
       $this->erro_sql = " Campo ov26_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriacadlocalender(
                                       ov26_sequencial 
                                      ,ov26_ruas 
                                      ,ov26_ouvidoriacadlocal 
                                      ,ov26_numero 
                                      ,ov26_complemento 
                                      ,ov26_observacao 
                       )
                values (
                                $this->ov26_sequencial 
                               ,$this->ov26_ruas 
                               ,$this->ov26_ouvidoriacadlocal 
                               ,$this->ov26_numero 
                               ,'$this->ov26_complemento' 
                               ,'$this->ov26_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Endereço do Local da Ouvidoria ($this->ov26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Endereço do Local da Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Endereço do Local da Ouvidoria ($this->ov26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov26_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov26_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14970,'$this->ov26_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2631,14970,'','".AddSlashes(pg_result($resaco,0,'ov26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2631,14971,'','".AddSlashes(pg_result($resaco,0,'ov26_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2631,14972,'','".AddSlashes(pg_result($resaco,0,'ov26_ouvidoriacadlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2631,14973,'','".AddSlashes(pg_result($resaco,0,'ov26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2631,14974,'','".AddSlashes(pg_result($resaco,0,'ov26_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2631,18287,'','".AddSlashes(pg_result($resaco,0,'ov26_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov26_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriacadlocalender set ";
     $virgula = "";
     if(trim($this->ov26_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_sequencial"])){ 
       $sql  .= $virgula." ov26_sequencial = $this->ov26_sequencial ";
       $virgula = ",";
       if(trim($this->ov26_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov26_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov26_ruas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_ruas"])){ 
       $sql  .= $virgula." ov26_ruas = $this->ov26_ruas ";
       $virgula = ",";
       if(trim($this->ov26_ruas) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "ov26_ruas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov26_ouvidoriacadlocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_ouvidoriacadlocal"])){ 
       $sql  .= $virgula." ov26_ouvidoriacadlocal = $this->ov26_ouvidoriacadlocal ";
       $virgula = ",";
       if(trim($this->ov26_ouvidoriacadlocal) == null ){ 
         $this->erro_sql = " Campo Local nao Informado.";
         $this->erro_campo = "ov26_ouvidoriacadlocal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov26_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_numero"])){ 
       $sql  .= $virgula." ov26_numero = $this->ov26_numero ";
       $virgula = ",";
       if(trim($this->ov26_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "ov26_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov26_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_complemento"])){ 
       $sql  .= $virgula." ov26_complemento = '$this->ov26_complemento' ";
       $virgula = ",";
     }
     if(trim($this->ov26_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov26_observacao"])){ 
       $sql  .= $virgula." ov26_observacao = '$this->ov26_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ov26_sequencial!=null){
       $sql .= " ov26_sequencial = $this->ov26_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov26_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14970,'$this->ov26_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_sequencial"]) || $this->ov26_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2631,14970,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_sequencial'))."','$this->ov26_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_ruas"]) || $this->ov26_ruas != "")
           $resac = db_query("insert into db_acount values($acount,2631,14971,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_ruas'))."','$this->ov26_ruas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_ouvidoriacadlocal"]) || $this->ov26_ouvidoriacadlocal != "")
           $resac = db_query("insert into db_acount values($acount,2631,14972,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_ouvidoriacadlocal'))."','$this->ov26_ouvidoriacadlocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_numero"]) || $this->ov26_numero != "")
           $resac = db_query("insert into db_acount values($acount,2631,14973,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_numero'))."','$this->ov26_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_complemento"]) || $this->ov26_complemento != "")
           $resac = db_query("insert into db_acount values($acount,2631,14974,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_complemento'))."','$this->ov26_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov26_observacao"]) || $this->ov26_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2631,18287,'".AddSlashes(pg_result($resaco,$conresaco,'ov26_observacao'))."','$this->ov26_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço do Local da Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereço do Local da Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov26_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov26_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14970,'$ov26_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2631,14970,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2631,14971,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2631,14972,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_ouvidoriacadlocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2631,14973,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2631,14974,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2631,18287,'','".AddSlashes(pg_result($resaco,$iresaco,'ov26_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriacadlocalender
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov26_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov26_sequencial = $ov26_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço do Local da Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Endereço do Local da Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov26_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriacadlocalender";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriacadlocalender ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = ouvidoriacadlocalender.ov26_ruas";
     $sql .= "      inner join ouvidoriacadlocal  on  ouvidoriacadlocal.ov25_sequencial = ouvidoriacadlocalender.ov26_ouvidoriacadlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($ov26_sequencial!=null ){
         $sql2 .= " where ouvidoriacadlocalender.ov26_sequencial = $ov26_sequencial "; 
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
   function sql_query_file ( $ov26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriacadlocalender ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov26_sequencial!=null ){
         $sql2 .= " where ouvidoriacadlocalender.ov26_sequencial = $ov26_sequencial "; 
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