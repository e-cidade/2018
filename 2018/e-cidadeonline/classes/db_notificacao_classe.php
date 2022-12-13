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

//MODULO: caixa
//CLASSE DA ENTIDADE notificacao
class cl_notificacao { 
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
   var $k50_notifica = 0; 
   var $k50_procede = 0; 
   var $k50_dtemite_dia = null; 
   var $k50_dtemite_mes = null; 
   var $k50_dtemite_ano = null; 
   var $k50_dtemite = null; 
   var $k50_obs = null; 
   var $k50_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k50_notifica = int4 = Notificação 
                 k50_procede = int4 = Procedência 
                 k50_dtemite = date = Data Emissão 
                 k50_obs = text = Observação 
                 k50_instit = int4 = Cód. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_notificacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notificacao"); 
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
       $this->k50_notifica = ($this->k50_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_notifica"]:$this->k50_notifica);
       $this->k50_procede = ($this->k50_procede == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_procede"]:$this->k50_procede);
       if($this->k50_dtemite == ""){
         $this->k50_dtemite_dia = ($this->k50_dtemite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_dtemite_dia"]:$this->k50_dtemite_dia);
         $this->k50_dtemite_mes = ($this->k50_dtemite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_dtemite_mes"]:$this->k50_dtemite_mes);
         $this->k50_dtemite_ano = ($this->k50_dtemite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_dtemite_ano"]:$this->k50_dtemite_ano);
         if($this->k50_dtemite_dia != ""){
            $this->k50_dtemite = $this->k50_dtemite_ano."-".$this->k50_dtemite_mes."-".$this->k50_dtemite_dia;
         }
       }
       $this->k50_obs = ($this->k50_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_obs"]:$this->k50_obs);
       $this->k50_instit = ($this->k50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_instit"]:$this->k50_instit);
     }else{
       $this->k50_notifica = ($this->k50_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k50_notifica"]:$this->k50_notifica);
     }
   }
   // funcao para inclusao
   function incluir ($k50_notifica){ 
      $this->atualizacampos();
     if($this->k50_procede == null ){ 
       $this->erro_sql = " Campo Procedência nao Informado.";
       $this->erro_campo = "k50_procede";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k50_dtemite == null ){ 
       $this->erro_sql = " Campo Data Emissão nao Informado.";
       $this->erro_campo = "k50_dtemite_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k50_instit == null ){ 
       $this->erro_sql = " Campo Cód. Instituição nao Informado.";
       $this->erro_campo = "k50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k50_notifica == "" || $k50_notifica == null ){
       $result = db_query("select nextval('notificacao_k50_notifica_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notificacao_k50_notifica_seq do campo: k50_notifica"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k50_notifica = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notificacao_k50_notifica_seq");
       if(($result != false) && (pg_result($result,0,0) < $k50_notifica)){
         $this->erro_sql = " Campo k50_notifica maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k50_notifica = $k50_notifica; 
       }
     }
     if(($this->k50_notifica == null) || ($this->k50_notifica == "") ){ 
       $this->erro_sql = " Campo k50_notifica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notificacao(
                                       k50_notifica 
                                      ,k50_procede 
                                      ,k50_dtemite 
                                      ,k50_obs 
                                      ,k50_instit 
                       )
                values (
                                $this->k50_notifica 
                               ,$this->k50_procede 
                               ,".($this->k50_dtemite == "null" || $this->k50_dtemite == ""?"null":"'".$this->k50_dtemite."'")." 
                               ,'$this->k50_obs' 
                               ,$this->k50_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Notificação de Débitos ($this->k50_notifica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Notificação de Débitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Notificação de Débitos ($this->k50_notifica) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k50_notifica;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k50_notifica));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4703,'$this->k50_notifica','I')");
       $resac = db_query("insert into db_acount values($acount,621,4703,'','".AddSlashes(pg_result($resaco,0,'k50_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,621,4704,'','".AddSlashes(pg_result($resaco,0,'k50_procede'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,621,4705,'','".AddSlashes(pg_result($resaco,0,'k50_dtemite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,621,4706,'','".AddSlashes(pg_result($resaco,0,'k50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,621,10718,'','".AddSlashes(pg_result($resaco,0,'k50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k50_notifica=null) { 
      $this->atualizacampos();
     $sql = " update notificacao set ";
     $virgula = "";
     if(trim($this->k50_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k50_notifica"])){ 
       $sql  .= $virgula." k50_notifica = $this->k50_notifica ";
       $virgula = ",";
       if(trim($this->k50_notifica) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k50_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k50_procede)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k50_procede"])){ 
       $sql  .= $virgula." k50_procede = $this->k50_procede ";
       $virgula = ",";
       if(trim($this->k50_procede) == null ){ 
         $this->erro_sql = " Campo Procedência nao Informado.";
         $this->erro_campo = "k50_procede";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k50_dtemite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k50_dtemite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k50_dtemite_dia"] !="") ){ 
       $sql  .= $virgula." k50_dtemite = '$this->k50_dtemite' ";
       $virgula = ",";
       if(trim($this->k50_dtemite) == null ){ 
         $this->erro_sql = " Campo Data Emissão nao Informado.";
         $this->erro_campo = "k50_dtemite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k50_dtemite_dia"])){ 
         $sql  .= $virgula." k50_dtemite = null ";
         $virgula = ",";
         if(trim($this->k50_dtemite) == null ){ 
           $this->erro_sql = " Campo Data Emissão nao Informado.";
           $this->erro_campo = "k50_dtemite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k50_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k50_obs"])){ 
       $sql  .= $virgula." k50_obs = '$this->k50_obs' ";
       $virgula = ",";
     }
     if(trim($this->k50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k50_instit"])){ 
       $sql  .= $virgula." k50_instit = $this->k50_instit ";
       $virgula = ",";
       if(trim($this->k50_instit) == null ){ 
         $this->erro_sql = " Campo Cód. Instituição nao Informado.";
         $this->erro_campo = "k50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k50_notifica!=null){
       $sql .= " k50_notifica = $this->k50_notifica";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k50_notifica));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4703,'$this->k50_notifica','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k50_notifica"]))
           $resac = db_query("insert into db_acount values($acount,621,4703,'".AddSlashes(pg_result($resaco,$conresaco,'k50_notifica'))."','$this->k50_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k50_procede"]))
           $resac = db_query("insert into db_acount values($acount,621,4704,'".AddSlashes(pg_result($resaco,$conresaco,'k50_procede'))."','$this->k50_procede',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k50_dtemite"]))
           $resac = db_query("insert into db_acount values($acount,621,4705,'".AddSlashes(pg_result($resaco,$conresaco,'k50_dtemite'))."','$this->k50_dtemite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k50_obs"]))
           $resac = db_query("insert into db_acount values($acount,621,4706,'".AddSlashes(pg_result($resaco,$conresaco,'k50_obs'))."','$this->k50_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k50_instit"]))
           $resac = db_query("insert into db_acount values($acount,621,10718,'".AddSlashes(pg_result($resaco,$conresaco,'k50_instit'))."','$this->k50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificação de Débitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k50_notifica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificação de Débitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k50_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k50_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k50_notifica=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k50_notifica));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4703,'$k50_notifica','E')");
         $resac = db_query("insert into db_acount values($acount,621,4703,'','".AddSlashes(pg_result($resaco,$iresaco,'k50_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,621,4704,'','".AddSlashes(pg_result($resaco,$iresaco,'k50_procede'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,621,4705,'','".AddSlashes(pg_result($resaco,$iresaco,'k50_dtemite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,621,4706,'','".AddSlashes(pg_result($resaco,$iresaco,'k50_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,621,10718,'','".AddSlashes(pg_result($resaco,$iresaco,'k50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notificacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k50_notifica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k50_notifica = $k50_notifica ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Notificação de Débitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k50_notifica;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Notificação de Débitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k50_notifica;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k50_notifica;
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
        $this->erro_sql   = "Record Vazio na Tabela:notificacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_noticontri ( $d08_contri=null,$d08_matric=null,$d08_notif=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contrinot ";
     $sql .= "      inner join contricalc  on  contricalc.d09_sequencial = contrinot.d08_contricalc ";
     $sql .= "      inner join notificacao on  notificacao.k50_notifica = contrinot.d08_notif and notificacao.k50_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join contrib  on  contrib.d07_contri = contricalc.d09_contri and  contrib.d07_matric = contricalc.d09_matric";
     $sql .= "      inner join editalrua on   editalrua.d02_contri = contricalc.d09_contri ";
     $sql .= "      inner join edital on   editalrua.d02_codedi = edital.d01_codedi ";
     $sql .= "      inner join ruas on   editalrua.d02_codigo = ruas.j14_codigo ";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql .= "      inner join proprietario on proprietario.j01_matric = contrib.d07_matric ";
     $sql2 = "";
     if($dbwhere==""){
       if($d08_contri!=null ){
         $sql2 .= " where contricalc.d09_contri = $d08_contri ";
       }
       if($d08_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " contrinot.d08_matric = $d08_matric ";
       }
       if($d08_notif!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " contrinot.d08_notif = $d08_notif ";
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
   function sql_query ( $k50_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificacao ";
     $sql .= " left join notiusu       on  notificacao.k50_notifica  = k52_notifica                  ";
     $sql .= " left join noticonf      on  notificacao.k50_notifica  = k54_notifica                  ";
     $sql .= " left join notinumcgm    on  notificacao.k50_notifica  = k57_notifica                  ";
     $sql .= " left join notimatric    on  notificacao.k50_notifica  = k55_notifica                  ";
     $sql .= " left join notiinscr     on  notificacao.k50_notifica  = k56_inscr                     ";
     $sql .= "inner join db_config     on  db_config.codigo 	       = notificacao.k50_instit        ";
     $sql .= "inner join notitipo      on  notitipo.k51_procede 	   = notificacao.k50_procede       ";
     $sql .= "inner join cgm           on  cgm.z01_numcgm 			     = db_config.numcgm              ";
	   $sql .= " left join notificadoc   on  notificadoc.k100_notifica = notificacao.k50_notifica      ";
	   $sql .= " left join db_documento  on  db_documento.db03_docum   = notificadoc.k100_db_documento ";
	   $sql .= " left join db_usuarios   on db_usuarios.id_usuario     = notiusu.k52_id_usuario        ";
	   $sql .= " left join notidebitos   on notidebitos.k53_notifica   = notificacao.k50_notifica      ";
	   
     $sql2 = "";
     if($dbwhere==""){
       if($k50_notifica!=null ){
         $sql2 .= " where notificacao.k50_notifica = $k50_notifica "; 
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
   function sql_query_file ( $k50_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k50_notifica!=null ){
         $sql2 .= " where notificacao.k50_notifica = $k50_notifica "; 
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
   function sql_query_nome( $k50_notifica=null,$dbwhere="",$ordem=null){

$sql = "select k50_notifica,";
$sql .= "           k50_procede, ";
$sql .= "           k50_dtemite, ";
$sql .= "           k50_obs, ";
$sql .= "           k55_matric, ";
$sql .= "           k56_inscr, ";
$sql .= "           k57_numcgm, ";
$sql .= "          substr(z01_nome,1,6)::integer as z01_numcgm, ";
$sql .= "          substr(z01_nome,8,40)::varchar(40) as z01_nome ";
$sql .= "from ( ";
$sql .= "select notificacao.*, ";
$sql .= "       k55_matric, ";
$sql .= "       k56_inscr, ";
$sql .= "       k57_numcgm, ";
$sql .= "       case when k55_matric is not null ";
$sql .= "            then (select lpad(z01_numcgm,6,0)||' '||z01_nome ";
$sql .= "                  from proprietario_nome ";
$sql .= "                  where j01_matric = k55_matric limit 1) ";
$sql .= "            else case when k56_inscr is not null ";
$sql .= "                      then (select lpad(q02_numcgm,6,0)||' '||z01_nome ";
$sql .= "                            from empresa ";
$sql .= "                            where q02_inscr = k56_inscr limit 1) ";
$sql .= "                 else (select lpad(z01_numcgm,6,0)||' '||z01_nome ";
$sql .= "                       from cgm ";
$sql .= "                       where z01_numcgm = k57_numcgm limit 1) ";
$sql .= "                 end";
$sql .= "        end as z01_nome ";
$sql .= "from notificacao ";
$sql .= "     left join notimatric on k50_notifica = k55_notifica ";
$sql .= "     left join notiinscr  on k50_notifica = k56_notifica ";
$sql .= "     left join notinumcgm on k50_notifica = k57_notifica) as x ";
$sql2 = "";

     if($dbwhere==""){
       if($k50_notifica!=null ){
         $sql2 .= " where k50_notifica = $k50_notifica ";
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
  function sql_query_usuario ( $k50_notifica=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from notificacao ";
    $sql .= "      left  join notiusu        on  notificacao.k50_notifica = k52_notifica ";
    $sql .= "      left  join db_usuarios    on  db_usuarios.id_usuario   = k52_id_usuario ";
    $sql .= "      left  join noticonf       on  notificacao.k50_notifica = k54_notifica ";
    $sql .= "      left  join notinumcgm     on  notificacao.k50_notifica = k57_notifica ";
    $sql .= "      left  join notimatric     on  notificacao.k50_notifica = k55_notifica ";
    $sql .= "      left  join notiinscr      on  notificacao.k50_notifica = k56_notifica ";
    $sql .= "      inner join db_config      on  db_config.codigo = notificacao.k50_instit";
    $sql .= "      inner join notitipo       on  notitipo.k51_procede = notificacao.k50_procede";
    $sql .= "      inner join cgm            on  cgm.z01_numcgm = db_config.numcgm";
    $sql .= "      left  join notidebitosreg on  notificacao.k50_notifica = k43_notifica";
    $sql .= "      left  join notidebitos    on  notidebitos.k53_notifica = notificacao.k50_notifica";
    $sql2 = "";
    if($dbwhere==""){
      if($k50_notifica!=null ){
        $sql2 .= " where notificacao.k50_notifica = $k50_notifica ";
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

  /**
   * Retorna débitos das notificação selecionada
   * @param integer  $iCodigoNotificacao
   * @param string   $sCampos
   * @return string
   */
  function sql_query_debitos_notificacao ($iCodigoNotificacao, $sCampos = "*") {
     
    $sql = "select {$sCampos}                                                                    ";
    $sql.= "  from notidebitos                                                                   ";
    $sql.= " inner join listanotifica on listanotifica.k63_notifica = notidebitos.k53_notifica   ";
    $sql.= " inner join lista         on listanotifica.k63_codigo   = lista.k60_codigo           ";
    $sql.= " inner join debitos       on k22_numpre                 = k53_numpre                 ";
    $sql.= "                         and k22_numpar                 = k53_numpar                 ";
    $sql.= "                         and k22_data                   = k60_datadeb                ";
    $sql.= " inner join arretipo      on arretipo.k00_tipo          = k22_tipo                   ";
    $sql.= " where k53_notifica = {$iCodigoNotificacao}                                          ";
    $sql.= "   and exists (select 1                                                              ";
    $sql.= "                 from arrecad                                                        ";
    $sql.= "                where arrecad.k00_numpre = debitos.k22_numpre                        ";
    $sql.= "                  and arrecad.k00_numpar = debitos.k22_numpar                        ";
    $sql.= "                limit 1)                                                             ";
    return $sql;
  }
  
  /**
   * Retorna dados das notificações conforme lista selecionada
   * @param integer $iCodigoLista
   * @param string  $sOrderBy
   * @param integer $iLimit
   * @return string - Consulta SQL a ser executada
   */
  function sql_query_lista_notificacoes($iCodigoLista, $sOrderBy = 'codigo_notificacao', $iLimit = null) {
     
    $sSqlLista = "select distinct                                                                                                      ";
    $sSqlLista.= "       listanotifica     .k63_notifica      as codigo_notificacao,                                                   ";
    $sSqlLista.= "       db_config         .numcgm            as cgm_prefeitura,                                                       ";
    $sSqlLista.= "       db_config         .nomeinstabrev     as nome_prefeitura_abreviado,                                            ";
    $sSqlLista.= "       db_config         .nomeinst          as nome_prefeitura,                                                      ";
    $sSqlLista.= "       cgm_prefeitura    .z01_cep           as cep_prefeitura,                                                       ";
    $sSqlLista.= "       cgm_prefeitura    .z01_ender         as endereco_prefeitura,                                                  ";
    $sSqlLista.= "       cgm_prefeitura    .z01_numero        as numero_prefeitura,                                                    ";
    $sSqlLista.= "       cgm_prefeitura    .z01_compl         as complemento_endereco_prefeitura,                                      ";
    $sSqlLista.= "       cgm_prefeitura    .z01_bairro        as bairro_prefeitura,                                                    ";
    $sSqlLista.= "       cgm_prefeitura    .z01_munic         as cidade_prefeitura,                                                    ";
    $sSqlLista.= "       cgm_prefeitura    .z01_uf            as uf_prefeitura,                                                        ";
    $sSqlLista.= "       cgm_prefeitura    .z01_telef         as telefone_prefeitura,                                                  ";
    $sSqlLista.= "       cgm_prefeitura    .z01_email         as email_prefeitura,                                                     ";
    $sSqlLista.= "       cgm_prefeitura    .z01_fax           as fax_prefeitura,                                                       ";
    $sSqlLista.= "       cgm_prefeitura    .z01_cxpostal      as caixa_postal_prefeitura,                                              ";
    $sSqlLista.= "       to_char(                                                                                                      ";
    $sSqlLista.= "         fc_getsession('DB_datausu')::date,                                                                          ";
    $sSqlLista.= "         'dd/mm/yyyy'                                                                                                ";
    $sSqlLista.= "       )                                    as data_operacao,                                                        ";
    $sSqlLista.= "       notificacao       .k50_dtemite       as data_emissao_lista,                                                   ";
    $sSqlLista.= "       dados_destinatario.oid_contato       as codigo_origem_notificacao, /* NUMERO + C ou M ou I*/                  ";
    $sSqlLista.= "       dados_destinatario.nome              as nome_cgm_origem,                                                      ";
    $sSqlLista.= "       dados_destinatario.telefone          as telefone_cgm_origem,                                                  ";
    $sSqlLista.= "       dados_destinatario.email             as email_cgm_origem,                                                     ";
    $sSqlLista.= "       dados_destinatario.numero_fax        as fax_cgm_origem,                                                       ";
    $sSqlLista.= "       dados_destinatario.cep               as cep_cgm_origem,                                                       ";
    $sSqlLista.= "       dados_destinatario.endereco          as endereco_cgm_origem,                                                  ";
    $sSqlLista.= "       dados_destinatario.numero            as numero_endereco_cgm_origem,                                           ";
    $sSqlLista.= "       dados_destinatario.complemento       as complemento_endereco_cgm_origem,                                      ";
    $sSqlLista.= "       dados_destinatario.bairro            as bairro_endereco_cgm_origem,                                           ";
    $sSqlLista.= "       dados_destinatario.cidade            as cidade_endereco_cgm_origem,                                           ";
    $sSqlLista.= "       dados_destinatario.uf                as uf_endereco_cgm_origem,                                               ";
    $sSqlLista.= "       dados_destinatario.caixa_postal      as caixa_postal_endereco_cgm_origem,                                     ";
    $sSqlLista.= "       (select array_to_string(array_accum(db02_texto), '')                                                          ";
    $sSqlLista.= "          from listadoc                                                                                              ";
    $sSqlLista.= "               inner join db_docparag       on db04_docum   = listadoc.k64_docum                                     ";
    $sSqlLista.= "               inner join db_paragrafo      on db02_idparag = db04_idparag                                           ";
    $sSqlLista.= "         where k63_notifica = listanotifica.k63_notifica                                                             ";
    $sSqlLista.= "       )                                    as texto_campo                                                           ";
    $sSqlLista.= "from listanotifica                                                                                                   ";
    $sSqlLista.= "       inner join lista                     on lista.k60_codigo            = listanotifica.k63_codigo                ";
    $sSqlLista.= "       inner join notificacao               on notificacao.k50_notifica    = listanotifica.k63_notifica              ";
    $sSqlLista.= "       inner join db_config                 on db_config.codigo            = notificacao.k50_instit                  ";
    $sSqlLista.= "       inner join cgm cgm_prefeitura        on cgm_prefeitura.z01_numcgm   = db_config.numcgm                        ";
    $sSqlLista.= "       inner join listadoc                  on listadoc.k64_codigo         = listanotifica.k63_codigo                ";
    $sSqlLista.= "       inner join db_docparag               on db_docparag.db04_docum      = listadoc.k64_docum                      ";
    $sSqlLista.= "       inner join db_paragrafo              on db_paragrafo.db02_idparag   = db_docparag.db04_idparag                ";
    $sSqlLista.= "       inner join                                                                                                    ";
    $sSqlLista.= "                  ( select k50_notifica     as oid_doc,                                                              ";
    $sSqlLista.= "                           k57_numcgm||'C'  as oid_contato,                                                          ";
    $sSqlLista.= "                           z01_nome         as nome,                                                                 ";
    $sSqlLista.= "                           z01_telef        as telefone,                                                             ";
    $sSqlLista.= "                           z01_email        as email,                                                                ";
    $sSqlLista.= "                           z01_fax          as numero_fax,                                                           ";
    $sSqlLista.= "                           z01_cep          as cep,                                                                  ";
    $sSqlLista.= "                           z01_ender        as endereco,                                                             ";
    $sSqlLista.= "                           z01_numero       as numero,                                                               ";
    $sSqlLista.= "                           z01_compl        as complemento,                                                          ";
    $sSqlLista.= "                           z01_bairro       as bairro,                                                               ";
    $sSqlLista.= "                           z01_munic        as cidade,                                                               ";
    $sSqlLista.= "                           z01_uf           as uf,                                                                   ";
    $sSqlLista.= "                           z01_cxpostal     as caixa_postal                                                          ";
    $sSqlLista.= "                      from notificacao                                                                               ";
    $sSqlLista.= "                           inner join listanotifica  on listanotifica.k63_codigo   = {$iCodigoLista}                 ";
    $sSqlLista.= "                                                    and listanotifica.k63_notifica = k50_notifica                    ";
    $sSqlLista.= "                           inner join notinumcgm     on k57_notifica               = k50_notifica                    ";
    $sSqlLista.= "                           inner join cgm            on z01_numcgm                 = k57_numcgm                      ";
    $sSqlLista.= "                           left  join notimatric     on k55_notifica               = k50_notifica                    ";
    $sSqlLista.= "                           left  join notiinscr      on k56_notifica               = k50_notifica                    ";
    $sSqlLista.= "                       and k55_notifica is null                                                                      ";
    $sSqlLista.= "                       and k56_notifica is null                                                                      ";
    $sSqlLista.= "                                                                                                                     ";
    $sSqlLista.= "                     union                                                                                           ";
    $sSqlLista.= "                                                                                                                     ";
    $sSqlLista.= "                    select k50_notifica     as oid_doc,                                                              ";
    $sSqlLista.= "                           k56_inscr||'I'   as oid_contato,                                                          ";
    $sSqlLista.= "                           z01_nome         as nome,                                                                 ";
    $sSqlLista.= "                           z01_telef        as telefone,                                                             ";
    $sSqlLista.= "                           z01_email        as email,                                                                ";
    $sSqlLista.= "                           z01_fax          as numero_fax,                                                           ";
    $sSqlLista.= "                           z01_cep          as cep,                                                                  ";
    $sSqlLista.= "                           z01_ender        as endereco,                                                             ";
    $sSqlLista.= "                           z01_numero       as numero,                                                               ";
    $sSqlLista.= "                           z01_compl        as complemento,                                                          ";
    $sSqlLista.= "                           z01_bairro       as bairro,                                                               ";
    $sSqlLista.= "                           z01_munic        as cidade,                                                               ";
    $sSqlLista.= "                           z01_uf           as uf,                                                                   ";
    $sSqlLista.= "                           z01_cxpostal     as caixa_postal                                                          ";
    $sSqlLista.= "                      from notificacao                                                                               ";
    $sSqlLista.= "                           inner join listanotifica  on listanotifica.k63_codigo   = {$iCodigoLista}                 ";
    $sSqlLista.= "                                                    and listanotifica.k63_notifica = k50_notifica                    ";
    $sSqlLista.= "                           inner join notiinscr      on k56_notifica               = k50_notifica                    ";
    $sSqlLista.= "                           inner join issbase        on q02_inscr                  = k56_inscr                       ";
    $sSqlLista.= "                           inner join cgm            on z01_numcgm                 = q02_numcgm                      ";
    $sSqlLista.= "                     union                                                                                           ";
    $sSqlLista.= "                                                                                                                     ";
    $sSqlLista.= "                    select k50_notifica                                 as oid_doc,                                  ";
    $sSqlLista.= "                           k55_matric||'M'                              as oid_contato,                              ";
    $sSqlLista.= "                           z01_nome                                     as nome,                                     ";
    $sSqlLista.= "                           z01_telef                                    as telefone,                                 ";
    $sSqlLista.= "                           z01_email                                    as email,                                    ";
    $sSqlLista.= "                           z01_fax                                      as numero_fax,                               ";
    $sSqlLista.= "                           substr(fc_iptuender,159,08)                  as cep,                                      ";
    $sSqlLista.= "                           substr(fc_iptuender,001,40)                  as endereco,                                 ";
    $sSqlLista.= "                           case trim(substr(fc_iptuender,042,10))                                                    ";
    $sSqlLista.= "                             when ''                                                                                 ";
    $sSqlLista.= "                             then 0                                                                                  ";
    $sSqlLista.= "                             else trim(substr(fc_iptuender,042,10))::integer                                         ";
    $sSqlLista.= "                           end                                          as numero,                                   ";
    $sSqlLista.= "                           substr(fc_iptuender,053,20)                  as complemento,                              ";
    $sSqlLista.= "                           substr(fc_iptuender,074,40)                  as bairro,                                   ";
    $sSqlLista.= "                           substr(fc_iptuender,115,40)                  as cidade,                                   ";
    $sSqlLista.= "                           substr(fc_iptuender,156,02)                  as uf,                                       ";
    $sSqlLista.= "                           substr(fc_iptuender,168,20)                  as caixa_postal                              ";
    $sSqlLista.= "                      from (                                                                                         ";
    $sSqlLista.= "                            select fc_iptuender(k55_matric),                                                         ";
    $sSqlLista.= "                                   k50_notifica,                                                                     ";
    $sSqlLista.= "                                   k55_matric,                                                                       ";
    $sSqlLista.= "                                   cgm.z01_nome,                                                                     ";
    $sSqlLista.= "                                   cgm.z01_telef,                                                                    ";
    $sSqlLista.= "                                   cgm.z01_email,                                                                    ";
    $sSqlLista.= "                                   cgm.z01_fax                                                                       ";
    $sSqlLista.= "                              from notificacao                                                                       ";
    $sSqlLista.= "                                   inner join listanotifica  on listanotifica.k63_codigo   = {$iCodigoLista}         ";
    $sSqlLista.= "                                                            and listanotifica.k63_notifica = k50_notifica            ";
    $sSqlLista.= "                                   inner join notimatric     on k55_notifica               = k50_notifica            ";
    $sSqlLista.= "                                   inner join proprietario   on j01_matric                 = k55_matric              ";
    $sSqlLista.= "                                   inner join cgm            on cgm.z01_numcgm             = proprietario.z01_cgmpri ";
    $sSqlLista.= "                          ) as dados_endereco                                                                        ";
    $sSqlLista.= "                  ) as dados_destinatario   on dados_destinatario.oid_doc = listanotifica.k63_notifica               ";
    $sSqlLista.= "  where k63_codigo = {$iCodigoLista}                                                                                 ";
    $sSqlLista.= "  order by {$sOrderBy}       
                                                                                            ";
                                                                                                                                       
    if($iLimit != null) {                                                                                                              
    	$sSqlLista.= "  limit {$iLimit}                                                                                                  ";
    }
    
    return $sSqlLista;
  }
}
?>